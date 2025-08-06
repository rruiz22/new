<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Config\AWS;
use Exception;

class S3Service
{
    private $s3Client;
    private $bucket;
    private $config;

    public function __construct()
    {
        $this->config = new AWS();
        
        // Validate AWS credentials
        if (empty($this->config->s3['credentials']['key']) || $this->config->s3['credentials']['key'] === 'YOUR_AWS_ACCESS_KEY_HERE') {
            throw new \Exception('AWS Access Key not configured. Please set AWS_ACCESS_KEY_ID in .env file.');
        }
        
        if (empty($this->config->s3['credentials']['secret']) || $this->config->s3['credentials']['secret'] === 'YOUR_AWS_SECRET_KEY_HERE') {
            throw new \Exception('AWS Secret Key not configured. Please set AWS_SECRET_ACCESS_KEY in .env file.');
        }
        
        if (empty($this->config->s3['bucket'])) {
            throw new \Exception('S3 Bucket not configured. Please set S3_BUCKET in .env file.');
        }
        
        try {
            // Initialize S3 Client
            $this->s3Client = new S3Client([
                'version' => $this->config->s3['version'],
                'region' => $this->config->s3['region'],
                'credentials' => [
                    'key' => $this->config->s3['credentials']['key'],
                    'secret' => $this->config->s3['credentials']['secret'],
                ],
            ]);
        } catch (\Exception $e) {
            log_message('error', 'S3 Client Initialization Error: ' . $e->getMessage());
            throw new \Exception('Failed to initialize S3 Client: ' . $e->getMessage());
        }

        $this->bucket = $this->config->s3['bucket'];
    }

    /**
     * Upload file to S3
     */
    public function uploadFile($filePath, $s3Key, $contentType = null)
    {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $s3Key,
                'SourceFile' => $filePath,
                'ContentType' => $contentType ?: mime_content_type($filePath),
                'Metadata' => [
                    'uploaded_at' => date('Y-m-d H:i:s'),
                    'uploaded_by' => auth()->id() ?? 'system',
                ]
            ]);

            return [
                'success' => true,
                'url' => $result['ObjectURL'],
                'key' => $s3Key,
                'etag' => $result['ETag'],
            ];

        } catch (AwsException $e) {
            log_message('error', 'S3 Upload Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload file content directly (from memory)
     */
    public function uploadContent($content, $s3Key, $contentType = 'image/jpeg')
    {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $s3Key,
                'Body' => $content,
                'ContentType' => $contentType,
                'Metadata' => [
                    'uploaded_at' => date('Y-m-d H:i:s'),
                    'uploaded_by' => auth()->id() ?? 'system',
                ]
            ]);

            return [
                'success' => true,
                'url' => $result['ObjectURL'],
                'key' => $s3Key,
                'etag' => $result['ETag'],
            ];

        } catch (AwsException $e) {
            log_message('error', 'S3 Upload Content Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List files in a folder
     */
    public function listFiles($prefix = '', $maxKeys = 1000)
    {
        try {
            $result = $this->s3Client->listObjectsV2([
                'Bucket' => $this->bucket,
                'Prefix' => $prefix,
                'MaxKeys' => $maxKeys,
            ]);

            $files = [];
            if (isset($result['Contents'])) {
                foreach ($result['Contents'] as $object) {
                    $files[] = [
                        'key' => $object['Key'],
                        'size' => $object['Size'],
                        'modified' => $object['LastModified']->format('Y-m-d H:i:s'),
                        'url' => $this->getPublicUrl($object['Key']),
                        'etag' => trim($object['ETag'], '"'),
                    ];
                }
            }

            return [
                'success' => true,
                'files' => $files,
                'count' => count($files),
            ];

        } catch (AwsException $e) {
            log_message('error', 'S3 List Files Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'files' => [],
            ];
        }
    }

    /**
     * Delete file from S3
     */
    public function deleteFile($s3Key)
    {
        try {
            $this->s3Client->deleteObject([
                'Bucket' => $this->bucket,
                'Key' => $s3Key,
            ]);

            return [
                'success' => true,
                'message' => 'File deleted successfully',
            ];

        } catch (AwsException $e) {
            log_message('error', 'S3 Delete Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get public URL for a file
     */
    public function getPublicUrl($s3Key)
    {
        return "https://{$this->bucket}.s3.amazonaws.com/{$s3Key}";
    }

    /**
     * Generate presigned URL for secure access
     */
    public function getPresignedUrl($s3Key, $expiration = '+20 minutes')
    {
        try {
            $cmd = $this->s3Client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $s3Key,
            ]);

            $request = $this->s3Client->createPresignedRequest($cmd, $expiration);
            return (string) $request->getUri();

        } catch (AwsException $e) {
            log_message('error', 'S3 Presigned URL Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if file exists
     */
    public function fileExists($s3Key)
    {
        try {
            $this->s3Client->headObject([
                'Bucket' => $this->bucket,
                'Key' => $s3Key,
            ]);
            return true;
        } catch (AwsException $e) {
            return false;
        }
    }

    /**
     * Get vehicle photos folder path
     */
    public function getVehiclePath($vinNumber)
    {
        return str_replace('{vin}', $vinNumber, $this->config->paths['vehicle_photos']);
    }

    /**
     * Get thumbnail folder path
     */
    public function getThumbnailPath($vinNumber)
    {
        return str_replace('{vin}', $vinNumber, $this->config->paths['thumbnails']);
    }

    /**
     * Create thumbnail from image
     */
    public function createThumbnail($sourceKey, $thumbnailKey, $width = 300, $height = 200)
    {
        try {
            // Get original image
            $result = $this->s3Client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $sourceKey,
            ]);

            $imageContent = $result['Body']->getContents();
            
            // Create thumbnail using GD
            $image = imagecreatefromstring($imageContent);
            if (!$image) {
                return ['success' => false, 'error' => 'Invalid image format'];
            }

            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate proportional dimensions
            $ratio = min($width / $originalWidth, $height / $originalHeight);
            $newWidth = intval($originalWidth * $ratio);
            $newHeight = intval($originalHeight * $ratio);

            // Create thumbnail
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Convert to JPEG
            ob_start();
            imagejpeg($thumbnail, null, $this->config->files['quality']);
            $thumbnailContent = ob_get_contents();
            ob_end_clean();

            // Clean up memory
            imagedestroy($image);
            imagedestroy($thumbnail);

            // Upload thumbnail
            return $this->uploadContent($thumbnailContent, $thumbnailKey, 'image/jpeg');

        } catch (AwsException $e) {
            log_message('error', 'S3 Thumbnail Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Batch upload files
     */
    public function batchUpload($files, $vinNumber)
    {
        $folderPath = $this->getVehiclePath($vinNumber);
        $thumbnailPath = $this->getThumbnailPath($vinNumber);
        
        $uploadedFiles = [];
        $failedFiles = [];

        foreach ($files as $file) {
            $fileName = $file['name'];
            $fileContent = $file['content'];
            $mimeType = $file['mime_type'];
            
            // Generate unique filename
            $timestamp = date('Y-m-d_H-i-s');
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $extension;
            $s3Key = $folderPath . $uniqueFileName;
            
            // Upload original
            $uploadResult = $this->uploadContent($fileContent, $s3Key, $mimeType);
            
            if ($uploadResult['success']) {
                // Create thumbnail
                $thumbnailKey = $thumbnailPath . 'thumb_' . $uniqueFileName;
                $thumbnailResult = $this->createThumbnail($s3Key, $thumbnailKey);
                
                $uploadedFiles[] = [
                    'id' => md5($s3Key),
                    'original_name' => $fileName,
                    'stored_name' => $uniqueFileName,
                    'url' => $uploadResult['url'],
                    'thumbnail' => $thumbnailResult['success'] ? $thumbnailResult['url'] : $uploadResult['url'],
                    'size' => strlen($fileContent),
                    'key' => $s3Key,
                    'uploaded_at' => date('Y-m-d H:i:s'),
                ];
            } else {
                $failedFiles[] = ['file' => $fileName, 'error' => $uploadResult['error']];
            }
        }

        return [
            'success' => count($uploadedFiles) > 0,
            'uploaded_files' => $uploadedFiles,
            'failed_files' => $failedFiles,
            'total_uploaded' => count($uploadedFiles),
            'total_failed' => count($failedFiles),
        ];
    }

    /**
     * Get file content from S3
     */
    public function getFileContent($s3Key)
    {
        try {
            $result = $this->s3Client->getObject([
                'Bucket' => $this->bucket,
                'Key'    => $s3Key
            ]);

            return (string) $result['Body'];

        } catch (Exception $e) {
            log_message('error', 'S3 getFileContent error: ' . $e->getMessage());
            return false;
        }
    }
}