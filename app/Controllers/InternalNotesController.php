<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\InternalNoteModel;

class InternalNotesController extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';
    protected $noteModel;

    public function __construct()
    {
        helper(['auth', 'form']);
        try {
        $this->noteModel = new InternalNoteModel();
            log_message('info', 'InternalNotesController::__construct - Model loaded successfully');
        } catch (\Exception $e) {
            log_message('error', 'InternalNotesController::__construct - Failed to load model: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get notes for a specific order
     */
    public function getOrderNotes($orderId = null)
    {
        if (!$orderId) {
            return $this->failValidationErrors(['order_id' => 'Order ID is required']);
        }

        try {
            // Pagination parameters
            $page = $this->request->getGet('page') ?? 1;
            $limit = $this->request->getGet('limit') ?? 5; // Changed from 10 to 5 notes per page to match frontend
            $offset = ($page - 1) * $limit;

            $filters = [];
            $search = $this->request->getGet('search');
            $authorId = $this->request->getGet('author_id');
            $dateFrom = $this->request->getGet('date_from');
            $dateTo = $this->request->getGet('date_to');
            $dateFilter = $this->request->getGet('date_filter');

            if ($search) $filters['search'] = $search;
            if ($authorId) $filters['author_id'] = $authorId;
            if ($dateFrom) $filters['date_from'] = $dateFrom;
            if ($dateTo) $filters['date_to'] = $dateTo;
            
            // Process date_filter parameter from frontend
            if ($dateFilter) {
                $now = new \DateTime();
                switch ($dateFilter) {
                    case 'today':
                        $filters['date_from'] = $now->format('Y-m-d');
                        break;
                    case 'week':
                        $weekAgo = clone $now;
                        $weekAgo->modify('-7 days');
                        $filters['date_from'] = $weekAgo->format('Y-m-d');
                        break;
                    case 'month':
                        $monthAgo = clone $now;
                        $monthAgo->modify('-30 days');
                        $filters['date_from'] = $monthAgo->format('Y-m-d');
                        break;
                }
            }

            // Add pagination to filters
            $filters['limit'] = $limit;
            $filters['offset'] = $offset;

            $notes = $this->noteModel->getOrderNotes($orderId, $filters);
            $totalNotes = $this->noteModel->getOrderNotesCount($orderId, $filters);
            
            // Process notes for display
            foreach ($notes as &$note) {
                $note['content_processed'] = $this->noteModel->processMentions($note['note']);
                $note['created_at_relative'] = $this->getTimeAgo($note['created_at']);
                $note['created_at_formatted'] = date('M j, Y \a\t g:i A', strtotime($note['created_at']));
                $note['can_edit'] = $this->canEditNote($note);
                $note['can_delete'] = $this->canDeleteNote($note);

                // Process replies
                if (!empty($note['replies'])) {
                    foreach ($note['replies'] as &$reply) {
                        $reply['content_processed'] = $this->noteModel->processMentions($reply['note']);
                        $reply['created_at_relative'] = $this->getTimeAgo($reply['created_at']);
                        $reply['created_at_formatted'] = date('M j, Y \a\t g:i A', strtotime($reply['created_at']));
                        $reply['can_edit'] = $this->canEditNote($reply);
                        $reply['can_delete'] = $this->canDeleteNote($reply);
                    }
                }
            }

            // Simple and reliable pagination logic (same as Service Orders)
            $hasMore = ($offset + $limit) < $totalNotes;

            return $this->respond([
                'success' => true,
                'data' => $notes,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_notes' => (int)$totalNotes,
                    'has_more' => $hasMore,
                    'next_page' => $hasMore ? $page + 1 : null,
                    'loaded_count' => count($notes),
                    'total_loaded' => $offset + count($notes),
                    'per_page' => $limit,
                    'offset' => $offset
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'InternalNotesController::getOrderNotes - Exception: ' . $e->getMessage());
            log_message('error', 'InternalNotesController::getOrderNotes - Stack trace: ' . $e->getTraceAsString());
            
            return $this->respond([
                'success' => false,
                'message' => 'Error loading notes: ' . $e->getMessage(),
                'data' => [],
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 0,
                    'total_notes' => 0,
                    'has_more' => false
                ]
            ], 500);
        }
    }

    /**
     * Create a new note
     */
    public function create()
    {
        log_message('info', 'InternalNotesController::create - Request received');
        
        $rules = [
            'order_id' => 'required|integer',
            'content' => 'required|min_length[2]|max_length[5000]'
        ];

        if (!$this->validate($rules)) {
            log_message('error', 'InternalNotesController::create - Validation failed: ' . json_encode($this->validator->getErrors()));
            return $this->respond([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        try {
            log_message('info', 'InternalNotesController::create - Start creating note');
            
            // Get current user ID using CodeIgniter Shield
            $currentUserId = auth()->id();
            log_message('info', 'InternalNotesController::create - Current user ID: ' . ($currentUserId ?? 'NULL'));
            
            if (!$currentUserId) {
                log_message('error', 'InternalNotesController::create - User not authenticated');
                return $this->respond([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Auto-detect order type based on request context
            $detectedModule = $this->detectModuleFromRequest();
            $orderType = $this->moduleToOrderType($detectedModule);
            
            $noteData = [
                'order_type' => $orderType,
                'order_id' => $this->request->getPost('order_id'),
                'author_id' => $currentUserId,
                'note' => $this->request->getPost('content')
            ];
            
            // TEMPORARY DEBUG: Verify this is the correct version
            log_message('info', 'FIXED VERSION - Note data with CORRECT note field: ' . json_encode($noteData));
            
            // DEBUG: Log the exact content received and array structure
            log_message('info', 'DEBUG - Content received: ' . $this->request->getPost('content'));
            log_message('info', 'DEBUG - Array keys: ' . implode(', ', array_keys($noteData)));
            log_message('info', 'InternalNotesController::create - Note data prepared: ' . json_encode($noteData));

            // Extract mentions from content
            $mentionedUserIds = $this->noteModel->extractMentions($noteData['note']);
            if (!empty($mentionedUserIds)) {
                $noteData['mentions'] = json_encode($mentionedUserIds);
            }

            // Handle file attachments if any
            $attachments = $this->request->getFiles();
            if (!empty($attachments['attachments'])) {
                $uploadedFiles = [];
                foreach ($attachments['attachments'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        // Simple file storage - you can enhance this
                        $newName = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/notes/', $newName);
                        $uploadedFiles[] = [
                            'filename' => $newName,
                            'original_name' => $file->getClientName(),
                            'size' => $file->getSize()
                        ];
                    }
                }
                if (!empty($uploadedFiles)) {
                    $noteData['attachments'] = json_encode($uploadedFiles);
                }
            }

            log_message('info', 'InternalNotesController::create - About to insert note: ' . json_encode($noteData));
            $noteId = $this->noteModel->insert($noteData);
            log_message('info', 'InternalNotesController::create - Note inserted with ID: ' . $noteId);

            if (!$noteId) {
                log_message('error', 'InternalNotesController::create - Failed to insert note');
                return $this->failServerError('Unable to create note');
            }

            // Log activity in sales orders activities
            $contentPreview = substr($noteData['note'], 0, 15) . (strlen($noteData['note']) > 15 ? '...' : '');
            $this->logInternalNoteActivity($noteData['order_id'], $currentUserId, 'internal_note_added', 'Internal note was added: ' . $contentPreview, [
                'note_id' => $noteId,
                'content_preview' => $contentPreview,
                'full_content' => $noteData['note']
            ]);

            // Get the created note with author info
            $createdNote = $this->noteModel->getOrderNotes($noteData['order_id']);
            $createdNote = array_filter($createdNote, function($note) use ($noteId) {
                return $note['id'] == $noteId;
            });
            $createdNote = reset($createdNote);

            return $this->respondCreated([
                'success' => true,
                'message' => 'Note created successfully',
                'data' => [
                    'id' => $noteId,
                    'mentions' => $mentionedUserIds,
                    'content' => $noteData['note'],
                    'content_processed' => $this->noteModel->processMentions($noteData['note']),
                    'time_ago' => 'ahora',
                    'author_name' => $createdNote['author_name'] ?? 'Usuario Actual',
                    'can_edit' => true,
                    'can_delete' => true,
                    'attachments' => json_decode($noteData['attachments'] ?? '[]', true)
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error creating note: ' . $e->getMessage());
            return $this->failServerError('Unable to create note: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing note
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->failValidationErrors(['id' => 'Note ID is required']);
        }

        try {
            $content = $this->request->getPost('content');
            if (empty($content)) {
                return $this->failValidationErrors(['content' => 'Content is required']);
            }

            $updateData = ['note' => $content];
            
            // Extract mentions
            $mentionedUserIds = $this->noteModel->extractMentions($content);
            if (!empty($mentionedUserIds)) {
                $updateData['mentions'] = json_encode($mentionedUserIds);
            }

            // Get the note before updating to get order_id
            $note = $this->noteModel->find($id);
            if (!$note) {
                return $this->failNotFound('Note not found');
            }

            $updated = $this->noteModel->update($id, $updateData);

            if (!$updated) {
                return $this->failServerError('Unable to update note');
            }

            // Log activity in sales orders activities
            $currentUserId = auth()->id();
            if ($currentUserId) {
                $contentPreview = substr($content, 0, 15) . (strlen($content) > 15 ? '...' : '');
                $this->logInternalNoteActivity($note['order_id'], $currentUserId, 'internal_note_updated', 'Internal note was updated: ' . $contentPreview, [
                    'note_id' => $id,
                    'content_preview' => $contentPreview,
                    'full_content' => $content
                ]);
            }

            return $this->respond([
                'success' => true,
                'message' => 'Note updated successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error updating note: ' . $e->getMessage());
            return $this->failServerError('Unable to update note');
        }
    }

    /**
     * Delete a note
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->failValidationErrors(['id' => 'Note ID is required']);
        }

        try {
            // Get the note before deleting to get order_id
            $note = $this->noteModel->find($id);
            if (!$note) {
                return $this->failNotFound('Note not found');
            }

            $deleted = $this->noteModel->delete($id);

            if (!$deleted) {
                return $this->failServerError('Unable to delete note');
            }

            // Log activity in sales orders activities
            $currentUserId = auth()->id();
            if ($currentUserId) {
                $contentPreview = substr($note['note'], 0, 15) . (strlen($note['note']) > 15 ? '...' : '');
                $this->logInternalNoteActivity($note['order_id'], $currentUserId, 'internal_note_deleted', 'Internal note was deleted: ' . $contentPreview, [
                    'note_id' => $id,
                    'content_preview' => $contentPreview,
                    'full_content' => $note['note']
                ]);
            }

            return $this->respond([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error deleting note: ' . $e->getMessage());
            return $this->failServerError('Unable to delete note');
        }
    }

    /**
     * Test endpoint to check authentication and basic functionality
     */
    public function test()
    {
        log_message('info', 'InternalNotesController::test - Test endpoint called');
        
        $currentUserId = auth()->id();
        $isLoggedIn = auth()->loggedIn();
        
        return $this->respond([
            'success' => true,
            'message' => 'Test endpoint working',
            'data' => [
                'user_id' => $currentUserId,
                'is_logged_in' => $isLoggedIn,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Get staff users for mentions
     */
    public function getStaffUsers()
    {
        try {
            $search = $this->request->getGet('search') ?? '';
            $staffUsers = $this->noteModel->getStaffUsers($search);

            // Format for frontend
            $formattedUsers = array_map(function($user) {
                $firstName = $user['first_name'] ?? '';
                $lastName = $user['last_name'] ?? '';
                $username = $user['username'] ?? '';
                
                // Create display name
                if (!empty($firstName) || !empty($lastName)) {
                    $displayName = trim($firstName . ' ' . $lastName);
                } else {
                    $displayName = $username ?: 'User ' . $user['id'];
                }
                
                return [
                    'id' => $user['id'],
                    'name' => $displayName,
                    'username' => $username,
                    'email' => $user['email'] ?? '',
                    'avatar' => $user['avatar']
                ];
            }, $staffUsers);

            return $this->respond([
                'success' => true,
                'data' => $formattedUsers
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error fetching staff users: ' . $e->getMessage());
            return $this->failServerError('Unable to fetch staff users');
        }
    }

    /**
     * Get unread mentions
     */
    public function getUnreadMentions()
    {
        try {
            $currentUserId = session()->get('user_id') ?? 1;
            $mentions = $this->noteModel->getUnreadMentions($currentUserId);

            return $this->respond([
                'success' => true,
                'data' => $mentions,
                'count' => count($mentions)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error fetching unread mentions: ' . $e->getMessage());
            return $this->failServerError('Unable to fetch unread mentions');
        }
    }

    /**
     * Mark mention as read
     */
    public function markMentionRead($noteId = null)
    {
        if (!$noteId) {
            return $this->failValidationErrors(['note_id' => 'Note ID is required']);
        }

        try {
            $currentUserId = session()->get('user_id') ?? 1;
            $success = $this->noteModel->markMentionAsRead($noteId, $currentUserId);

            if (!$success) {
                return $this->failServerError('Unable to mark mention as read');
            }

            return $this->respond([
                'success' => true,
                'message' => 'Mention marked as read'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error marking mention as read: ' . $e->getMessage());
            return $this->failServerError('Unable to mark mention as read');
        }
    }

    /**
     * Helper: Get time ago string
     */
    private function getTimeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return 'hace ' . $time . ' segundos';
        if ($time < 3600) return 'hace ' . floor($time/60) . ' minutos';
        if ($time < 86400) return 'hace ' . floor($time/3600) . ' horas';
        if ($time < 2592000) return 'hace ' . floor($time/86400) . ' días';
        if ($time < 31536000) return 'hace ' . floor($time/2592000) . ' meses';
        
        return 'hace ' . floor($time/31536000) . ' años';
    }

    /**
     * Helper: Check if user can edit note
     */
    private function canEditNote($note)
    {
        $currentUserId = session()->get('user_id') ?? 1;
        return $note['author_id'] == $currentUserId;
    }

    /**
     * Helper: Check if user can delete note
     */
    private function canDeleteNote($note)
    {
        $currentUserId = session()->get('user_id') ?? 1;
        return $note['author_id'] == $currentUserId;
    }

    /**
     * Add reply to a note
     */
    public function addReply()
    {
        $rules = [
            'note_id' => 'required|integer',
            'content' => 'required|min_length[3]|max_length[5000]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $currentUserId = auth()->id();
            if (!$currentUserId) {
                return $this->failUnauthorized('User not authenticated');
            }

            // Get the parent note to find the order_id
            $parentNote = $this->noteModel->find($this->request->getPost('note_id'));
            if (!$parentNote) {
                return $this->failNotFound('Parent note not found');
            }

            $replyData = [
                'order_type' => 'sales_order',
                'order_id' => $parentNote['order_id'],
                'parent_note_id' => $this->request->getPost('note_id'),
                'author_id' => $currentUserId,
                'note' => $this->request->getPost('content')
            ];

            // Extract mentions from content
            $mentionedUserIds = $this->noteModel->extractMentions($replyData['note']);
            if (!empty($mentionedUserIds)) {
                $replyData['mentions'] = json_encode($mentionedUserIds);
            }

            $replyId = $this->noteModel->insert($replyData);

            if (!$replyId) {
                return $this->failServerError('Unable to create reply');
            }

            // Log activity in sales orders activities
            $contentPreview = substr($replyData['note'], 0, 15) . (strlen($replyData['note']) > 15 ? '...' : '');
            $this->logInternalNoteActivity($parentNote['order_id'], $currentUserId, 'internal_note_reply_added', 'Reply was added to internal note: ' . $contentPreview, [
                'note_id' => $parentNote['id'],
                'reply_id' => $replyId,
                'content_preview' => $contentPreview,
                'full_content' => $replyData['note']
            ]);

            return $this->respondCreated([
                'success' => true,
                'message' => 'Reply created successfully',
                'data' => [
                    'id' => $replyId,
                    'mentions' => $mentionedUserIds
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error creating reply: ' . $e->getMessage());
            return $this->failServerError('Unable to create reply: ' . $e->getMessage());
        }
    }

    /**
     * Download attachment file
     */
    public function download($filename = null)
    {
        if (!$filename) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        $filePath = WRITEPATH . 'uploads/notes/' . $filename;
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        // Security check - ensure user has access to this note
        // This is a basic implementation, you may want to add more security
        
        return $this->response->download($filePath, null);
    }

    /**
     * Log internal note activity in the appropriate module's activities
     */
    private function logInternalNoteActivity($orderId, $userId, $activityType, $description, $metadata = [])
    {
        try {
            // Determine which module the order belongs to by checking the referrer or URL
            $module = $this->detectModuleFromRequest();
            
            // Load the appropriate activity model based on module
            switch ($module) {
                case 'CarWash':
                    $activityModel = new \Modules\CarWash\Models\CarWashActivityModel();
                    break;
                case 'ServiceOrders':
                    $activityModel = new \Modules\ServiceOrders\Models\ServiceOrderActivityModel();
                    break;
                case 'ReconOrders':
                    $activityModel = new \Modules\ReconOrders\Models\ReconActivityModel();
                    break;
                case 'SalesOrders':
                default:
                    $activityModel = new \Modules\SalesOrders\Models\OrderActivityModel();
                    break;
            }
            
            // Use the activity model's internal note logging method if available
            if (method_exists($activityModel, 'logInternalNoteActivity')) {
                return $activityModel->logInternalNoteActivity($orderId, $userId, $activityType, $description, $metadata);
            }
            
            // Fallback to generic logging for older models
            $titles = [
                'internal_note_added' => 'Internal Note Added',
                'internal_note_updated' => 'Internal Note Updated', 
                'internal_note_deleted' => 'Internal Note Deleted',
                'internal_note_reply_added' => 'Internal Note Reply Added'
            ];
            
            $title = $titles[$activityType] ?? 'Internal Note Activity';
            
            return $activityModel->insert([
                'order_id' => $orderId,
                'user_id' => $userId,
                'activity_type' => $activityType,
                'title' => $title,
                'description' => $description,
                'field_name' => 'internal_note',
                'metadata' => json_encode($metadata),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error logging internal note activity: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Detect which module is calling based on request context
     */
    private function detectModuleFromRequest()
    {
        // Check referrer URL
        $referrer = $this->request->getServer('HTTP_REFERER');
        
        if ($referrer) {
            if (strpos($referrer, '/car-wash/') !== false) {
                return 'CarWash';
            } elseif (strpos($referrer, '/service-orders/') !== false) {
                return 'ServiceOrders';
            } elseif (strpos($referrer, '/sales-orders/') !== false) {
                return 'SalesOrders';
            } elseif (strpos($referrer, '/recon_orders/') !== false) {
                return 'ReconOrders';
            }
        }
        
        // Check current URL or route
        $currentUrl = current_url();
        if (strpos($currentUrl, '/car-wash/') !== false) {
            return 'CarWash';
        } elseif (strpos($currentUrl, '/service-orders/') !== false) {
            return 'ServiceOrders';
        } elseif (strpos($currentUrl, '/sales-orders/') !== false) {
            return 'SalesOrders';
        } elseif (strpos($currentUrl, '/recon_orders/') !== false) {
            return 'ReconOrders';
        }
        
        // Check for module parameter in request
        $module = $this->request->getGet('module') ?? $this->request->getPost('module');
        if ($module) {
            return $module;
        }
        
        // Default to SalesOrders for backward compatibility
        return 'SalesOrders';
    }

    /**
     * Convert module name to order_type for database storage
     */
    private function moduleToOrderType($module)
    {
        $mapping = [
            'CarWash' => 'car_wash',
            'ServiceOrders' => 'service_order',
            'SalesOrders' => 'sales_order',
            'ReconOrders' => 'recon_order'
        ];
        
        return $mapping[$module] ?? 'sales_order';
    }
} 