<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class MediaConfig extends BaseConfig
{
    /**
     * Storage Configuration
     */
    public array $storage = [
        'driver' => 'local', // local, s3, cloudinary
        'local' => [
            'path' => WRITEPATH . 'uploads/',
            'url_prefix' => '/writable/uploads/'
        ],
        's3' => [
            'bucket' => '',
            'region' => 'us-east-1',
            'access_key' => '',
            'secret_key' => '',
            'cdn_url' => '', // CloudFront URL if using CDN
        ],
        'cloudinary' => [
            'cloud_name' => '',
            'api_key' => '',
            'api_secret' => '',
        ]
    ];

    /**
     * File Size Limits (in bytes)
     */
    public array $fileSizeLimits = [
        'image' => 10 * 1024 * 1024,    // 10MB
        'video' => 100 * 1024 * 1024,   // 100MB
        'document' => 25 * 1024 * 1024, // 25MB
        'audio' => 50 * 1024 * 1024,    // 50MB
    ];

    /**
     * Allowed File Types
     */
    public array $allowedTypes = [
        'image' => [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'
        ],
        'video' => [
            'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'
        ],
        'document' => [
            'pdf', 'doc', 'docx', 'txt', 'rtf', 'odt',
            'xls', 'xlsx', 'ppt', 'pptx', 'csv'
        ],
        'audio' => [
            'mp3', 'wav', 'ogg', 'aac', 'flac'
        ]
    ];

    /**
     * Image Processing Settings
     */
    public array $imageProcessing = [
        'quality' => 85,
        'progressive' => true,
        'strip_metadata' => true,
        'convert_to_webp' => false, // Set to true for better compression
        'sizes' => [
            'thumbnail' => [150, 150],
            'small' => [300, 300],
            'medium' => [800, 600],
            'large' => [1200, 900]
        ],
        'watermark' => [
            'enabled' => false,
            'image_path' => '',
            'position' => 'bottom-right',
            'opacity' => 50
        ]
    ];

    /**
     * Video Processing Settings
     */
    public array $videoProcessing = [
        'generate_thumbnails' => true,
        'thumbnail_time' => 5, // seconds
        'compression' => [
            'enabled' => false,
            'quality' => 'medium', // low, medium, high
            'max_resolution' => '1080p'
        ],
        'streaming' => [
            'enabled' => false,
            'formats' => ['hls', 'dash']
        ]
    ];

    /**
     * Security Settings
     */
    public array $security = [
        'scan_uploads' => false, // Virus scanning
        'check_mime_type' => true,
        'validate_image_content' => true,
        'quarantine_suspicious' => true,
        'max_files_per_upload' => 10,
        'rate_limiting' => [
            'enabled' => true,
            'max_uploads_per_minute' => 20,
            'max_uploads_per_hour' => 100
        ]
    ];

    /**
     * CDN and Optimization
     */
    public array $optimization = [
        'cdn' => [
            'enabled' => false,
            'url' => '',
            'zones' => [
                'images' => '',
                'videos' => '',
                'documents' => ''
            ]
        ],
        'compression' => [
            'images' => [
                'service' => 'tinypng', // tinypng, imageoptim, local
                'api_key' => '',
                'quality' => 85
            ],
            'videos' => [
                'service' => 'ffmpeg', // ffmpeg, cloudinary
                'presets' => [
                    'web' => '-c:v libx264 -crf 23 -c:a aac -b:a 128k',
                    'mobile' => '-c:v libx264 -crf 28 -vf scale=720:-2 -c:a aac -b:a 96k'
                ]
            ]
        ],
        'lazy_loading' => true,
        'progressive_loading' => true
    ];

    /**
     * Backup and Cleanup
     */
    public array $backup = [
        'enabled' => false,
        'schedule' => 'daily', // hourly, daily, weekly
        'retention_days' => 30,
        'destinations' => [
            's3' => [
                'bucket' => '',
                'path' => 'backups/media/'
            ]
        ]
    ];

    /**
     * Monitoring and Analytics
     */
    public array $monitoring = [
        'track_downloads' => true,
        'track_views' => true,
        'generate_reports' => true,
        'alert_on_errors' => true,
        'log_level' => 'info' // debug, info, warning, error
    ];

    /**
     * Cache Settings
     */
    public array $cache = [
        'thumbnails' => [
            'enabled' => true,
            'ttl' => 86400 * 30, // 30 days
            'driver' => 'file' // file, redis, memcached
        ],
        'metadata' => [
            'enabled' => true,
            'ttl' => 86400 * 7, // 7 days
        ]
    ];

    /**
     * Get file size limit for specific type
     */
    public function getFileSizeLimit(string $type): int
    {
        return $this->fileSizeLimits[$type] ?? $this->fileSizeLimits['document'];
    }

    /**
     * Check if file type is allowed
     */
    public function isFileTypeAllowed(string $extension, string $category = null): bool
    {
        $extension = strtolower($extension);
        
        if ($category) {
            return in_array($extension, $this->allowedTypes[$category] ?? []);
        }
        
        // Check all categories
        foreach ($this->allowedTypes as $types) {
            if (in_array($extension, $types)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get file category by extension
     */
    public function getFileCategory(string $extension): string
    {
        $extension = strtolower($extension);
        
        foreach ($this->allowedTypes as $category => $types) {
            if (in_array($extension, $types)) {
                return $category;
            }
        }
        
        return 'other';
    }

    /**
     * Get storage configuration
     */
    public function getStorageConfig(): array
    {
        return $this->storage[$this->storage['driver']] ?? $this->storage['local'];
    }

    /**
     * Get image size configuration
     */
    public function getImageSize(string $size): array
    {
        return $this->imageProcessing['sizes'][$size] ?? $this->imageProcessing['sizes']['medium'];
    }

    /**
     * Check if optimization service is available
     */
    public function isOptimizationEnabled(string $type): bool
    {
        return $this->optimization['compression'][$type]['service'] !== 'local' &&
               !empty($this->optimization['compression'][$type]['api_key']);
    }
} 