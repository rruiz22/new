<?php

namespace Modules\ReconOrders\Models;

use CodeIgniter\Model;

/**
 * Base model for handling attachments
 * Shared functionality between ReconCommentModel and ReconNoteModel
 */
abstract class BaseAttachmentModel extends Model
{
    /**
     * Process file attachments for upload
     */
    public function processAttachments($files, $orderId)
    {
        $attachments = [];
        
        if (!empty($files)) {
            $uploadPath = WRITEPATH . 'uploads/recon_orders/' . $orderId . '/';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName();
                    $file->move($uploadPath, $fileName);
                    
                    $fileInfo = [
                        'original_name' => $file->getClientName(),
                        'stored_name' => $fileName,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                        'type' => $this->getFileType($file->getClientMimeType()),
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        'path' => 'uploads/recon_orders/' . $orderId . '/' . $fileName
                    ];
                    
                    // Process images for thumbnails if needed
                    if (str_starts_with($file->getClientMimeType(), 'image/')) {
                        $fileInfo = $this->processImage($uploadPath . $fileName, $fileInfo, $orderId);
                    }
                    
                    $attachments[] = $fileInfo;
                }
            }
        }
        
        return $attachments;
    }

    /**
     * Process image attachments for thumbnails
     */
    public function processImage($imagePath, $fileInfo, $orderId)
    {
        try {
            // Get image dimensions
            $imageInfo = getimagesize($imagePath);
            if ($imageInfo) {
                $fileInfo['width'] = $imageInfo[0];
                $fileInfo['height'] = $imageInfo[1];
                
                // Create thumbnail if image is large
                if ($imageInfo[0] > 800 || $imageInfo[1] > 600) {
                    // Thumbnail creation logic would go here
                    $fileInfo['has_thumbnail'] = false; // Placeholder
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error processing image: ' . $e->getMessage());
        }
        
        return $fileInfo;
    }

    /**
     * Get file type based on MIME type
     */
    public function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, ['application/pdf'])) {
            return 'document';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            return 'office';
        } else {
            return 'file';
        }
    }

    /**
     * Process attachments from JSON string
     */
    public function processAttachmentsJson($attachmentsJson, $orderId)
    {
        if (empty($attachmentsJson)) {
            return [];
        }
        
        try {
            $attachments = json_decode($attachmentsJson, true);
            if (!is_array($attachments)) {
                return [];
            }
            
            // Add full URLs for attachments
            foreach ($attachments as &$attachment) {
                if (isset($attachment['path'])) {
                    $attachment['url'] = base_url($attachment['path']);
                    $attachment['download_url'] = base_url('recon_orders/attachment/' . $orderId . '/' . $attachment['stored_name']);
                }
            }
            
            return $attachments;
        } catch (\Exception $e) {
            log_message('error', 'Error processing attachments JSON: ' . $e->getMessage());
            return [];
        }
    }
}
