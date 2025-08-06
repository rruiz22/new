<?php

namespace Modules\CarWash\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CarWashNoteModel;

class CarWashNotesController extends ResourceController
{
    use ResponseTrait;

    protected $format = 'json';
    protected $noteModel;
    protected $activityModel;

    public function __construct()
    {
        helper(['auth', 'form']);
        $this->noteModel = new CarWashNoteModel();
        $this->activityModel = new \Modules\CarWash\Models\CarWashActivityModel();
    }

    /**
     * Get notes for a specific car wash order
     */
    public function getOrderNotes($carWashOrderId = null)
    {
        if (!$carWashOrderId) {
            return $this->failValidationErrors(['car_wash_order_id' => 'Car Wash Order ID is required']);
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

            $notes = $this->noteModel->getOrderNotes($carWashOrderId, $filters);
            $totalNotes = $this->noteModel->getOrderNotesCount($carWashOrderId, $filters);

            // Process notes for display
            foreach ($notes as &$note) {
                $note['content_processed'] = $this->noteModel->processMentions($note['content']);
                $note['created_at_relative'] = $this->getTimeAgo($note['created_at']);
                $note['created_at_formatted'] = date('M j, Y \a\t g:i A', strtotime($note['created_at']));
                $note['can_edit'] = $this->canEditNote($note);
                $note['can_delete'] = $this->canDeleteNote($note);
                
                // Process replies for display
                if (!empty($note['replies'])) {
                    foreach ($note['replies'] as &$reply) {
                        $reply['content_processed'] = $this->noteModel->processMentions($reply['content']);
                        $reply['created_at_relative'] = $this->getTimeAgo($reply['created_at']);
                        $reply['created_at_formatted'] = date('M j, Y \a\t g:i A', strtotime($reply['created_at']));
                        $reply['can_edit'] = $this->canEditNote($reply);
                        $reply['can_delete'] = $this->canDeleteNote($reply);
                    }
                }
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
            log_message('error', 'Error fetching car wash notes: ' . $e->getMessage());
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
                'car_wash_order_id' => $this->request->getPost('order_id'),
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
            $contentPreview = strlen($noteData['content']) > 50 ? substr($noteData['content'], 0, 50) . '...' : $noteData['content'];
            $this->activityModel->logInternalNoteAdded($noteData['car_wash_order_id'], $currentUserId, $contentPreview, $noteData['content']);

            // Get created note with author information
            $createdNote = $this->noteModel->getOrderNotes($noteData['car_wash_order_id'], ['limit' => 1]);
            $createdNote = $createdNote[0] ?? null;

            // Process attachments for response
            $processedAttachments = [];
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $attachment) {
                    $attachment['url'] = base_url('car-wash-notes/download/' . urlencode($attachment['filename']));
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
            log_message('error', 'Error creating car wash note: ' . $e->getMessage());
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
            $contentPreview = strlen($content) > 50 ? substr($content, 0, 50) . '...' : $content;
            $this->activityModel->logInternalNoteUpdated($note['car_wash_order_id'], $currentUserId, $contentPreview, $content);

            return $this->respond([
                'success' => true,
                'message' => 'Note updated successfully',
                'data' => [
                    'id' => $id,
                    'content' => $content,
                    'content_processed' => $this->noteModel->processMentions($content),
                    'mentions' => $mentionedUserIds,
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error updating car wash note: ' . $e->getMessage());
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
            $contentPreview = strlen($note['content']) > 50 ? substr($note['content'], 0, 50) . '...' : $note['content'];
            $this->activityModel->logInternalNoteDeleted($note['car_wash_order_id'], $currentUserId, $contentPreview, $note['content']);

            return $this->respond([
                'success' => true,
                'message' => 'Note deleted successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error deleting car wash note: ' . $e->getMessage());
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
                'car_wash_order_id' => $this->request->getPost('order_id'),
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
            $contentPreview = strlen($replyData['content']) > 50 ? substr($replyData['content'], 0, 50) . '...' : $replyData['content'];
            $this->activityModel->logInternalNoteReplyAdded($replyData['car_wash_order_id'], $currentUserId, $contentPreview, $replyData['content']);

            // Get user information for response
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($currentUserId);
            $authorName = !empty($user['first_name']) || !empty($user['last_name']) 
                ? trim($user['first_name'] . ' ' . $user['last_name'])
                : $user['username'] ?? 'Unknown User';

            return $this->respondCreated([
                'success' => true,
                'message' => 'Reply added successfully',
                'data' => [
                    'id' => $replyId,
                    'content' => $replyData['content'],
                    'content_processed' => $this->noteModel->processMentions($replyData['content']),
                    'author_name' => $authorName,
                    'created_at_relative' => 'ahora',
                    'created_at_formatted' => date('M j, Y \a\t g:i A'),
                    'can_edit' => true,
                    'can_delete' => true,
                    'mentions' => $mentionedUserIds
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error creating reply: ' . $e->getMessage());
            return $this->failServerError('Unable to create reply');
        }
    }

    /**
     * Get staff users for mentions
     */
    public function getStaffUsers()
    {
        try {
            $search = $this->request->getGet('search') ?? '';
            $users = $this->noteModel->getStaffUsers($search);

            return $this->respond([
                'success' => true,
                'data' => $users
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error fetching staff users: ' . $e->getMessage());
            return $this->failServerError('Unable to fetch staff users');
        }
    }

    /**
     * Download attachment
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

        return $this->response->download($filePath, null);
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
                'success' => $marked,
                'message' => $marked ? 'Mention marked as read' : 'Unable to mark mention as read'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error marking mention as read: ' . $e->getMessage());
            return $this->failServerError('Unable to mark mention as read');
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
            return $this->failServerError('Unable to fetch unread mentions');
        }
    }

    /**
     * Helper method to get time ago
     */
    private function getTimeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'ahora';
        if ($time < 3600) return floor($time/60) . ' min';
        if ($time < 86400) return floor($time/3600) . ' h';
        if ($time < 2592000) return floor($time/86400) . ' d';
        if ($time < 31536000) return floor($time/2592000) . ' m';
        return floor($time/31536000) . ' a';
    }

    /**
     * Check if current user can edit note
     */
    private function canEditNote($note)
    {
        $currentUserId = auth()->id();
        return $currentUserId && $currentUserId == $note['author_id'];
    }

    /**
     * Check if current user can delete note
     */
    private function canDeleteNote($note)
    {
        $currentUserId = auth()->id();
        return $currentUserId && $currentUserId == $note['author_id'];
    }
} 