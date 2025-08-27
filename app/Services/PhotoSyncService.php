<?php

namespace App\Services;

use App\Libraries\S3Service;
use CodeIgniter\Config\BaseConfig;
use Exception;

/**
 * PhotoSyncService
 * 
 * Automated service to sync photos from shared folder structure to Amazon S3
 * Runs nightly to process photos organized by date/VIN structure
 */
class PhotoSyncService
{
    private $s3Service;
    private $sharedFolderPath;
    private $logFile;
    private $processedCount = 0;
    private $errorCount = 0;
    private $skippedCount = 0;

    public function __construct()
    {
        $this->s3Service = new S3Service();
        
        // Configuration from environment or default
        $this->sharedFolderPath = getenv('PHOTO_SYNC_SHARED_FOLDER') ?: '/path/to/shared/folder';
        $this->logFile = WRITEPATH . 'logs/photo_sync_' . date('Y-m-d') . '.log';
        
        // Ensure log directory exists
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Main execution method - processes current date folder
     */
    public function execute($date = null)
    {
        $startTime = microtime(true);
        $processDate = $date ?: date('Y-m-d');
        
        $this->log("=== Starting Photo Sync Process for {$processDate} ===");
        
        try {
            // Build path for current date
            $dateFolderPath = $this->sharedFolderPath . '/' . $processDate;
            
            if (!is_dir($dateFolderPath)) {
                $this->log("Date folder not found: {$dateFolderPath}");
                return $this->buildResult(false, "Date folder not found for {$processDate}");
            }

            // Get all VIN subfolders
            $vinFolders = $this->getVinFolders($dateFolderPath);
            
            if (empty($vinFolders)) {
                $this->log("No VIN folders found in {$dateFolderPath}");
                return $this->buildResult(true, "No VIN folders to process for {$processDate}");
            }

            $this->log("Found " . count($vinFolders) . " VIN folders to process");

            // Process each VIN folder
            foreach ($vinFolders as $vinFolder) {
                $this->processVinFolder($dateFolderPath, $vinFolder, $processDate);
            }

            $executionTime = round(microtime(true) - $startTime, 2);
            $this->log("=== Process completed in {$executionTime} seconds ===");
            $this->log("Processed: {$this->processedCount}, Errors: {$this->errorCount}, Skipped: {$this->skippedCount}");

            return $this->buildResult(true, "Process completed successfully", [
                'processed' => $this->processedCount,
                'errors' => $this->errorCount,
                'skipped' => $this->skippedCount,
                'execution_time' => $executionTime
            ]);

        } catch (Exception $e) {
            $this->log("FATAL ERROR: " . $e->getMessage());
            return $this->buildResult(false, "Fatal error: " . $e->getMessage());
        }
    }

    /**
     * Get all VIN subfolders in the date directory
     */
    private function getVinFolders($dateFolderPath)
    {
        $vinFolders = [];
        
        if ($handle = opendir($dateFolderPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_dir($dateFolderPath . '/' . $entry)) {
                    // Basic VIN validation (17 characters alphanumeric)
                    if ($this->isValidVin($entry)) {
                        $vinFolders[] = $entry;
                    } else {
                        $this->log("Skipping invalid VIN folder: {$entry}");
                    }
                }
            }
            closedir($handle);
        }
        
        return $vinFolders;
    }

    /**
     * Process a single VIN folder
     */
    private function processVinFolder($basePath, $vinNumber, $processDate)
    {
        $vinFolderPath = $basePath . '/' . $vinNumber;
        $this->log("Processing VIN: {$vinNumber}");

        try {
            // Get all image files in VIN folder
            $imageFiles = $this->getImageFiles($vinFolderPath);
            
            if (empty($imageFiles)) {
                $this->log("No images found in VIN folder: {$vinNumber}");
                $this->skippedCount++;
                return;
            }

            $this->log("Found " . count($imageFiles) . " images in VIN {$vinNumber}");

            // Process each image
            foreach ($imageFiles as $imageFile) {
                $this->processImage($vinFolderPath, $imageFile, $vinNumber, $processDate);
            }

        } catch (Exception $e) {
            $this->log("Error processing VIN {$vinNumber}: " . $e->getMessage());
            $this->errorCount++;
        }
    }

    /**
     * Get all image files from VIN folder
     */
    private function getImageFiles($vinFolderPath)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $imageFiles = [];

        if ($handle = opendir($vinFolderPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $filePath = $vinFolderPath . '/' . $entry;
                    if (is_file($filePath)) {
                        $extension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                        if (in_array($extension, $imageExtensions)) {
                            $imageFiles[] = $entry;
                        }
                    }
                }
            }
            closedir($handle);
        }

        // Sort files by name for consistent processing
        sort($imageFiles);
        return $imageFiles;
    }

    /**
     * Process a single image file
     */
    private function processImage($vinFolderPath, $imageFileName, $vinNumber, $processDate)
    {
        $localFilePath = $vinFolderPath . '/' . $imageFileName;
        
        try {
            // Generate S3 key with date and VIN organization
            $s3Key = $this->generateS3Key($vinNumber, $imageFileName, $processDate);
            
            // Check if file already exists in S3 to avoid duplicates
            if ($this->s3Service->fileExists($s3Key)) {
                $this->log("File already exists in S3, skipping: {$s3Key}");
                $this->skippedCount++;
                return;
            }

            // Upload to S3
            $uploadResult = $this->s3Service->uploadFile($localFilePath, $s3Key);
            
            if ($uploadResult['success']) {
                $this->log("Successfully uploaded: {$imageFileName} -> {$s3Key}");
                $this->processedCount++;
                
                // Create thumbnail if needed
                $this->createThumbnail($s3Key, $vinNumber, $imageFileName, $processDate);
                
            } else {
                $this->log("Failed to upload {$imageFileName}: " . $uploadResult['error']);
                $this->errorCount++;
            }

        } catch (Exception $e) {
            $this->log("Error processing image {$imageFileName}: " . $e->getMessage());
            $this->errorCount++;
        }
    }

    /**
     * Generate S3 key with organized structure
     */
    private function generateS3Key($vinNumber, $imageFileName, $processDate)
    {
        // Structure: photos/YYYY-MM-DD/VIN/filename
        $year = substr($processDate, 0, 4);
        $month = substr($processDate, 5, 2);
        $day = substr($processDate, 8, 2);
        
        // Add timestamp to filename to avoid conflicts
        $timestamp = date('His'); // HHMMSS format
        $fileInfo = pathinfo($imageFileName);
        $uniqueFileName = $fileInfo['filename'] . '_' . $timestamp . '.' . $fileInfo['extension'];
        
        return "photos/{$year}/{$month}/{$day}/{$vinNumber}/{$uniqueFileName}";
    }

    /**
     * Create thumbnail for uploaded image
     */
    private function createThumbnail($originalS3Key, $vinNumber, $imageFileName, $processDate)
    {
        try {
            // Generate thumbnail S3 key
            $thumbnailKey = str_replace('/photos/', '/thumbnails/', $originalS3Key);
            $thumbnailKey = str_replace($imageFileName, 'thumb_' . $imageFileName, $thumbnailKey);
            
            // Create thumbnail using S3Service
            $thumbnailResult = $this->s3Service->createThumbnail($originalS3Key, $thumbnailKey, 300, 200);
            
            if ($thumbnailResult['success']) {
                $this->log("Thumbnail created: {$thumbnailKey}");
            } else {
                $this->log("Thumbnail creation failed for {$originalS3Key}: " . $thumbnailResult['error']);
            }

        } catch (Exception $e) {
            $this->log("Thumbnail error: " . $e->getMessage());
        }
    }

    /**
     * Basic VIN validation
     */
    private function isValidVin($vin)
    {
        // Basic validation: 17 alphanumeric characters
        return preg_match('/^[A-HJ-NPR-Z0-9]{17}$/i', $vin);
    }

    /**
     * Log message to file and console
     */
    private function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
        
        // Write to log file
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
        
        // Also write to CodeIgniter log
        log_message('info', "PhotoSyncService: {$message}");
        
        // Output to console if running from CLI
        if (php_sapi_name() === 'cli') {
            echo $logMessage;
        }
    }

    /**
     * Build standardized result array
     */
    private function buildResult($success, $message, $data = [])
    {
        return [
            'success' => $success,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'log_file' => $this->logFile,
            'data' => $data
        ];
    }

    /**
     * Get service statistics
     */
    public function getStats()
    {
        return [
            'processed_count' => $this->processedCount,
            'error_count' => $this->errorCount,
            'skipped_count' => $this->skippedCount,
            'shared_folder_path' => $this->sharedFolderPath,
            'log_file' => $this->logFile
        ];
    }

    /**
     * Test shared folder connectivity
     */
    public function testConnection()
    {
        if (!is_dir($this->sharedFolderPath)) {
            return [
                'success' => false,
                'message' => 'Shared folder path does not exist: ' . $this->sharedFolderPath
            ];
        }

        if (!is_readable($this->sharedFolderPath)) {
            return [
                'success' => false,
                'message' => 'Shared folder is not readable: ' . $this->sharedFolderPath
            ];
        }

        return [
            'success' => true,
            'message' => 'Shared folder connection successful',
            'path' => $this->sharedFolderPath
        ];
    }

    /**
     * Process specific date range
     */
    public function processDateRange($startDate, $endDate)
    {
        $results = [];
        $currentDate = strtotime($startDate);
        $endDateTime = strtotime($endDate);

        while ($currentDate <= $endDateTime) {
            $dateString = date('Y-m-d', $currentDate);
            $this->log("Processing date: {$dateString}");
            
            // Reset counters for each date
            $this->processedCount = 0;
            $this->errorCount = 0;
            $this->skippedCount = 0;
            
            $result = $this->execute($dateString);
            $results[$dateString] = $result;
            
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $results;
    }
}