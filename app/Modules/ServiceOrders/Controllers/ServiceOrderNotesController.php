<?php

namespace Modules\ServiceOrders\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ServiceOrderNoteModel;

class ServiceOrderNotesController extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';
    protected $noteModel;
    protected $activityModel;

    public function __construct()
    {
        helper(['auth', 'form']);
        $this->noteModel = new ServiceOrderNoteModel();
        $this->activityModel = new \Modules\ServiceOrders\Models\ServiceOrderActivityModel();
    }

    /**
     * Get notes for a specific service order
     */
    public function getOrderNotes($serviceOrderId = null)
    {
        if (!$serviceOrderId) {
            return $this->failValidationErrors(['service_order_id' => 'Service Order ID is required']);
        }

        try {
            // Pagination parameters
            $page = $this->request->getGet('page') ?? 1;
            $limit = $this->request->getGet('limit') ?? 5; // Default 5 notes per page
            $offset = ($page - 1) * $limit;

            $filters = [];
            $search = $this->request->getGet('search');
            $authorId = $this->request->getGet('author_id');
            $dateFrom = $this->request->getGet('date_from');
            $dateTo = $this->request->getGet('date_to');

            if ($search) $filters['search'] = $search;
            if ($authorId) $filters['author_id'] = $authorId;
            if ($dateFrom) $filters['date_from'] = $dateFrom;
            if ($dateTo) $filters['date_to'] = $dateTo;

            // Add pagination to filters
            $filters['limit'] = $limit;
            $filters['offset'] = $offset;

            $notes = $this->noteModel->getOrderNotes($serviceOrderId, $filters);
            $totalNotes = $this->noteModel->getOrderNotesCount($serviceOrderId, $filters);

            // Process notes for display
            foreach ($notes as &$note) {
                $note['content_processed'] = $this->noteModel->processMentions($note['content']);
                $note['created_at_relative'] = $this->getTimeAgo($note['created_at']);
                $note['created_at_formatted'] = date('M j, Y \a\t g:i A', strtotime($note['created_at']));
                $note['can_edit'] = $this->canEditNote($note);
                $note['can_delete'] = $this->canDeleteNote($note);
            }

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
                    'total_loaded' => $offset + count($notes)
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error fetching service order notes: ' . $e->getMessage());
            return $this->failServerError('Unable to fetch notes');
        }
    }

    /**
     * Create a new note
     */
    public function create()
    {
        $rules = [
            'order_id' => 'required|integer',
            'content' => 'required|min_length[3]|max_length[5000]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            // Get current user ID using CodeIgniter Shield
            $currentUserId = auth()->id();
            if (!$currentUserId) {
                return $this->failUnauthorized('User not authenticated');
            }

            $noteData = [
                'service_order_id' => $this->request->getPost('order_id'),
                'author_id' => $currentUserId,
                'content' => $this->request->getPost('content')
            ];

            // Extract mentions from content
            $mentionedUserIds = $this->noteModel->extractMentions($noteData['content']);
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

            $noteId = $this->noteModel->insert($noteData);

            if (!$noteId) {
                return $this->failServerError('Unable to create note');
            }

            // Log activity for internal note creation
            $this->logNoteActivity(
                $noteData['service_order_id'], 
                $currentUserId, 
                'internal_note_added', 
                'Internal note added',
                [
                    'note_id' => $noteId,
                    'note_preview' => substr($noteData['content'], 0, 100) . (strlen($noteData['content']) > 100 ? '...' : ''),
                    'note_content' => $noteData['content'], // Full content for tooltip
                    'has_attachments' => !empty($uploadedFiles),
                    'mentions_count' => count($mentionedUserIds)
                ]
            );

            // Get the created note with author info
            $createdNote = $this->noteModel->getOrderNotes($noteData['service_order_id']);
            $createdNote = array_filter($createdNote, function($note) use ($noteId) {
                return $note['id'] == $noteId;
            });
            $createdNote = reset($createdNote);

            // Process attachments to add URLs
            $processedAttachments = [];
            if (!empty($noteData['attachments'])) {
                $attachments = json_decode($noteData['attachments'], true);
                foreach ($attachments as $attachment) {
                    $attachment['url'] = base_url('service-order-notes/download/' . urlencode($attachment['filename']));
                    $processedAttachments[] = $attachment;
                }
            }

            return $this->respondCreated([
                'success' => true,
                'message' => 'Note created successfully',
                'data' => [
                    'id' => $noteId,
                    'mentions' => $mentionedUserIds,
                    'content' => $noteData['content'],
                    'content_processed' => $this->noteModel->processMentions($noteData['content']),
                    'time_ago' => 'ahora',
                    'author_name' => $createdNote['author_name'] ?? 'Usuario Actual',
                    'can_edit' => true,
                    'can_delete' => true,
                    'attachments' => $processedAttachments
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error creating service order note: ' . $e->getMessage());
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

            // Check if user can edit this note
            $note = $this->noteModel->find($id);
            if (!$note) {
                return $this->failNotFound('Note not found');
            }

            $currentUserId = auth()->id();
            if ($note['author_id'] != $currentUserId) {
                return $this->failForbidden('You can only edit your own notes');
            }

            $updateData = ['content' => $content];
            
            // Extract mentions
            $mentionedUserIds = $this->noteModel->extractMentions($content);
            if (!empty($mentionedUserIds)) {
                $updateData['mentions'] = json_encode($mentionedUserIds);
            } else {
                $updateData['mentions'] = null;
            }

            $updated = $this->noteModel->update($id, $updateData);

            if (!$updated) {
                return $this->failServerError('Unable to update note');
            }

            // Log activity for internal note update
            $this->logNoteActivity(
                $note['service_order_id'], 
                $currentUserId, 
                'internal_note_updated', 
                'Internal note updated',
                [
                    'note_id' => $id,
                    'old_content' => substr($note['content'], 0, 100) . (strlen($note['content']) > 100 ? '...' : ''),
                    'new_content' => substr($content, 0, 100) . (strlen($content) > 100 ? '...' : ''),
                    'old_content_full' => $note['content'], // Full old content for tooltip
                    'new_content_full' => $content, // Full new content for tooltip
                    'mentions_count' => count($mentionedUserIds)
                ]
            );

            return $this->respond([
                'success' => true,
                'message' => 'Note updated successfully',
                'data' => [
                    'content' => $content,
                    'mentions' => $mentionedUserIds
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error updating service order note: ' . $e->getMessage());
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
            // Check if user can delete this note
            $note = $this->noteModel->find($id);
            if (!$note) {
                return $this->failNotFound('Note not found');
            }

            $currentUserId = auth()->id();
            if ($note['author_id'] != $currentUserId) {
                return $this->failForbidden('You can only delete your own notes');
            }

            $deleted = $this->noteModel->delete($id);

            if (!$deleted) {
                return $this->failServerError('Unable to delete note');
            }

            // Log activity for internal note deletion
            $this->logNoteActivity(
                $note['service_order_id'], 
                $currentUserId, 
                'internal_note_deleted', 
                'Internal note deleted',
                [
                    'note_id' => $id,
                    'deleted_content' => substr($note['content'], 0, 100) . (strlen($note['content']) > 100 ? '...' : ''),
                    'deleted_content_full' => $note['content'] // Full deleted content for tooltip
                ]
            );

            return $this->respond([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error deleting service order note: ' . $e->getMessage());
            return $this->failServerError('Unable to delete note');
        }
    }

    /**
     * Add a reply to a note
     */
    public function addReply()
    {
        $rules = [
            'parent_note_id' => 'required|integer',
            'order_id' => 'required|integer',
            'content' => 'required|min_length[3]|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        try {
            $currentUserId = auth()->id();
            if (!$currentUserId) {
                return $this->failUnauthorized('User not authenticated');
            }

            // Verify parent note exists
            $parentNote = $this->noteModel->find($this->request->getPost('parent_note_id'));
            if (!$parentNote) {
                return $this->failNotFound('Parent note not found');
            }

            $replyData = [
                'service_order_id' => $this->request->getPost('order_id'),
                'author_id' => $currentUserId,
                'content' => $this->request->getPost('content'),
                'parent_note_id' => $this->request->getPost('parent_note_id')
            ];

            // Extract mentions from content
            $mentionedUserIds = $this->noteModel->extractMentions($replyData['content']);
            if (!empty($mentionedUserIds)) {
                $replyData['mentions'] = json_encode($mentionedUserIds);
            }

            $replyId = $this->noteModel->insert($replyData);

            if (!$replyId) {
                return $this->failServerError('Unable to create reply');
            }

            // Log activity for internal note reply
            $this->logNoteActivity(
                $replyData['service_order_id'], 
                $currentUserId, 
                'internal_note_reply_added', 
                'Reply added to internal note',
                [
                    'reply_id' => $replyId,
                    'parent_note_id' => $replyData['parent_note_id'],
                    'reply_preview' => substr($replyData['content'], 0, 100) . (strlen($replyData['content']) > 100 ? '...' : ''),
                    'mentions_count' => count($mentionedUserIds)
                ]
            );

            // Get the created reply with author info
            $createdReply = $this->noteModel->getOrderNotes($replyData['service_order_id']);
            $createdReply = array_filter($createdReply, function($note) use ($replyId) {
                return $note['id'] == $replyId;
            });
            $createdReply = reset($createdReply);

            // Get current user info
            $currentUser = auth()->user();
            $authorName = 'User';
            if ($currentUser) {
                if (!empty($currentUser->first_name) || !empty($currentUser->last_name)) {
                    $authorName = trim(($currentUser->first_name ?? '') . ' ' . ($currentUser->last_name ?? ''));
                } else {
                    $authorName = $currentUser->username ?? 'User ' . $currentUser->id;
                }
            }

            return $this->respondCreated([
                'success' => true,
                'message' => 'Reply added successfully',
                'reply' => [
                    'id' => $replyId,
                    'content' => $replyData['content'],
                    'author_name' => $createdReply['author_name'] ?? $authorName,
                    'avatar_url' => $createdReply['avatar'] ?? null,
                    'created_at_relative' => $this->getTimeAgo(date('Y-m-d H:i:s')),
                    'created_at_formatted' => date('M j, Y \a\t g:i A'),
                    'mentions' => $mentionedUserIds
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error creating reply: ' . $e->getMessage());
            return $this->failServerError('Unable to create reply: ' . $e->getMessage());
        }
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
     * Get unread mentions for current user
     */
    public function getUnreadMentions()
    {
        try {
            $currentUserId = auth()->id();
            if (!$currentUserId) {
                return $this->failUnauthorized('User not authenticated');
            }

            $mentions = $this->noteModel->getUnreadMentions($currentUserId);

            return $this->respond([
                'success' => true,
                'data' => $mentions
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error fetching unread mentions: ' . $e->getMessage());
            return $this->failServerError('Unable to fetch mentions');
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
            $currentUserId = auth()->id();
            if (!$currentUserId) {
                return $this->failUnauthorized('User not authenticated');
            }

            $marked = $this->noteModel->markMentionAsRead($noteId, $currentUserId);

            return $this->respond([
                'success' => true,
                'message' => 'Mention marked as read'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error marking mention as read: ' . $e->getMessage());
            return $this->failServerError('Unable to mark mention as read');
        }
    }

    private function getTimeAgo($datetime)
    {
        $time = time() - strtotime($datetime);

        if ($time < 60) return lang('App.just_now');
        if ($time < 3600) {
            $minutes = floor($time/60);
            return $minutes == 1 ? lang('App.one_minute_ago') : lang('App.minutes_ago', [$minutes]);
        }
        if ($time < 86400) {
            $hours = floor($time/3600);
            return $hours == 1 ? lang('App.one_hour_ago') : lang('App.hours_ago', [$hours]);
        }
        if ($time < 2592000) {
            $days = floor($time/86400);
            return $days == 1 ? lang('App.one_day_ago') : lang('App.days_ago', [$days]);
        }
        if ($time < 31536000) {
            $months = floor($time/2592000);
            return $months == 1 ? lang('App.one_month_ago') : lang('App.months_ago', [$months]);
        }

        $years = floor($time/31536000);
        return $years == 1 ? lang('App.one_year_ago') : lang('App.years_ago', [$years]);
    }

    private function canEditNote($note)
    {
        $currentUserId = auth()->id();
        return $currentUserId && ($currentUserId == $note['author_id']);
    }

    private function canDeleteNote($note)
    {
        $currentUserId = auth()->id();
        return $currentUserId && ($currentUserId == $note['author_id']);
    }

    /**
     * Download attachment file
     */
    public function download($filename = null)
    {
        if (!$filename) {
            return $this->failNotFound('File not found');
        }

        // Decode the filename
        $filename = urldecode($filename);
        
        // Security: Only allow alphanumeric characters, dots, dashes, and underscores
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            return $this->failNotFound('Invalid filename');
        }

        $filePath = WRITEPATH . 'uploads/notes/' . $filename;

        if (!file_exists($filePath)) {
            return $this->failNotFound('File not found');
        }

        // Check if user has access (staff or admin only)
        $currentUser = auth()->user();
        if (!$currentUser || ($currentUser->user_type !== 'staff' && $currentUser->user_type !== 'admin')) {
            return $this->failForbidden('Access denied');
        }

        // Get file info
        $fileInfo = pathinfo($filePath);
        $mimeType = mime_content_type($filePath);
        $extension = strtolower($fileInfo['extension'] ?? '');

        // Define file types that can be viewed in browser
        $viewableTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'txt' => 'text/plain',
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'text/javascript',
            'json' => 'application/json',
            'xml' => 'text/xml'
        ];

        // Check if file can be viewed in browser
        $canViewInBrowser = isset($viewableTypes[$extension]);
        
        // Get the action parameter (view or download)
        $action = $this->request->getGet('action') ?? 'view';
        
        // Determine content disposition
        if ($canViewInBrowser && $action === 'view') {
            $disposition = 'inline'; // Show in browser
        } else {
            $disposition = 'attachment'; // Force download
        }

        // Set headers
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', $disposition . '; filename="' . basename($filename) . '"')
            ->setHeader('Content-Length', filesize($filePath))
            ->setHeader('Cache-Control', 'no-cache, must-revalidate')
            ->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT')
                         ->setBody(file_get_contents($filePath));
    }

    /**
     * Log activity for internal notes (only visible to staff)
     */
    private function logNoteActivity($orderId, $userId, $action, $description, $metadata = [])
    {
        try {
            $this->activityModel->logActivity($orderId, $userId, $action, $description, $metadata);
        } catch (\Exception $e) {
            log_message('error', 'Error logging note activity: ' . $e->getMessage());
        }
    }
} 