<?php

namespace Modules\Vehicles\Controllers;

use App\Controllers\BaseController;

class LocalUploadController extends BaseController
{
    /**
     * Upload photos to local storage (fallback for S3)
     */
    public function uploadLocal()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $files = $this->request->getFiles();
        if (empty($files['files'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No files provided'
            ]);
        }

        $vinLast6 = $this->request->getPost('vinLast6');
        if (empty($vinLast6)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN required'
            ]);
        }

        // Create upload directory
        $uploadPath = WRITEPATH . 'uploads/vehicles/' . $vinLast6 . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $uploadedFiles = [];
        $failedFiles = [];

        foreach ($files['files'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getName();
                $fileExtension = $file->getExtension();
                
                // Validate file type
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                    $failedFiles[] = ['file' => $fileName, 'error' => 'Invalid file type'];
                    continue;
                }

                // Validate file size (10MB max)
                if ($file->getSize() > 10 * 1024 * 1024) {
                    $failedFiles[] = ['file' => $fileName, 'error' => 'File too large (max 10MB)'];
                    continue;
                }

                // Generate unique filename
                $timestamp = date('Y-m-d_H-i-s');
                $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $fileExtension;
                
                try {
                    // Move file to upload directory
                    $file->move($uploadPath, $uniqueFileName);
                    
                    // Create thumbnail if it's an image
                    $thumbnailUrl = $this->createThumbnail($uploadPath . $uniqueFileName, $uploadPath . 'thumb_' . $uniqueFileName);
                    
                    $uploadedFiles[] = [
                        'id' => md5($uniqueFileName),
                        'name' => $fileName,
                        'stored_name' => $uniqueFileName,
                        'url' => base_url('writable/uploads/vehicles/' . $vinLast6 . '/' . $uniqueFileName),
                        'thumbnail' => $thumbnailUrl ?: base_url('writable/uploads/vehicles/' . $vinLast6 . '/' . $uniqueFileName),
                        'size' => $file->getSize(),
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        'source' => 'Local Storage'
                    ];
                    
                } catch (\Exception $e) {
                    $failedFiles[] = ['file' => $fileName, 'error' => $e->getMessage()];
                }
            } else {
                $failedFiles[] = ['file' => $file->getName(), 'error' => 'Invalid file'];
            }
        }

        return $this->response->setJSON([
            'success' => count($uploadedFiles) > 0,
            'uploaded' => $uploadedFiles,
            'failed' => $failedFiles,
            'message' => count($uploadedFiles) . ' files uploaded successfully' . 
                        (count($failedFiles) > 0 ? ', ' . count($failedFiles) . ' failed' : '')
        ]);
    }

    /**
     * Get photos from local storage
     */
    public function getLocalPhotos($vinLast6)
    {
        $uploadPath = WRITEPATH . 'uploads/vehicles/' . $vinLast6 . '/';
        
        if (!is_dir($uploadPath)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No photos directory found',
                'photos' => []
            ]);
        }

        $photos = [];
        $files = scandir($uploadPath);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || strpos($file, 'thumb_') === 0) {
                continue;
            }
            
            $filePath = $uploadPath . $file;
            if (is_file($filePath)) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($extension, $allowedTypes)) {
                    $thumbnailFile = 'thumb_' . $file;
                    $thumbnailPath = $uploadPath . $thumbnailFile;
                    
                    $photos[] = [
                        'id' => md5($file),
                        'name' => $file,
                        'url' => base_url('writable/uploads/vehicles/' . $vinLast6 . '/' . $file),
                        'thumbnail' => file_exists($thumbnailPath) ? 
                            base_url('writable/uploads/vehicles/' . $vinLast6 . '/' . $thumbnailFile) :
                            base_url('writable/uploads/vehicles/' . $vinLast6 . '/' . $file),
                        'size' => filesize($filePath),
                        'uploaded_at' => date('Y-m-d H:i:s', filemtime($filePath)),
                        'source' => 'Local Storage'
                    ];
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'photos' => $photos,
            'count' => count($photos)
        ]);
    }

    /**
     * Create thumbnail for image
     */
    private function createThumbnail($sourcePath, $thumbnailPath, $maxWidth = 300, $maxHeight = 200)
    {
        try {
            $imageInfo = getimagesize($sourcePath);
            if (!$imageInfo) {
                return false;
            }

            $sourceWidth = $imageInfo[0];
            $sourceHeight = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // Calculate thumbnail dimensions
            $ratio = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight);
            $thumbWidth = round($sourceWidth * $ratio);
            $thumbHeight = round($sourceHeight * $ratio);

            // Create source image
            switch ($mimeType) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($sourcePath);
                    break;
                default:
                    return false;
            }

            if (!$sourceImage) {
                return false;
            }

            // Create thumbnail
            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
            
            // Preserve transparency for PNG and GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
                imagefilledrectangle($thumbnail, 0, 0, $thumbWidth, $thumbHeight, $transparent);
            }

            // Resize image
            imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $sourceWidth, $sourceHeight);

            // Save thumbnail
            $saved = false;
            switch ($mimeType) {
                case 'image/jpeg':
                    $saved = imagejpeg($thumbnail, $thumbnailPath, 85);
                    break;
                case 'image/png':
                    $saved = imagepng($thumbnail, $thumbnailPath);
                    break;
                case 'image/gif':
                    $saved = imagegif($thumbnail, $thumbnailPath);
                    break;
            }

            // Clean up memory
            imagedestroy($sourceImage);
            imagedestroy($thumbnail);

            if ($saved) {
                $pathInfo = pathinfo($thumbnailPath);
                return base_url('writable/uploads/vehicles/' . basename(dirname($thumbnailPath)) . '/' . $pathInfo['basename']);
            }

            return false;

        } catch (\Exception $e) {
            log_message('error', 'Thumbnail creation failed: ' . $e->getMessage());
            return false;
        }
    }
}
