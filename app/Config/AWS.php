<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class AWS extends BaseConfig
{
    /**
     * AWS S3 Configuration
     */
    public $s3 = [
        'version' => 'latest',
        'region' => 'us-east-1',
        'credentials' => [
            'key' => '',
            'secret' => '',
        ],
        'bucket' => 'mda-vehicle-photos',
        'url_expiration' => '+20 minutes', // Tiempo de expiración para URLs firmadas
    ];

    /**
     * S3 Paths Configuration
     */
    public $paths = [
        'vehicle_photos' => 'vehicles/{vin}/', // vehicles/VIN123/
        'thumbnails' => 'thumbnails/{vin}/',   // thumbnails/VIN123/
    ];

    /**
     * File Configuration
     */
    public $files = [
        'max_size' => 10 * 1024 * 1024, // 10MB max file size
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'quality' => 85, // JPEG quality for thumbnails
    ];

    public function __construct()
    {
        parent::__construct();
        
        // Load from environment variables if available
        $this->s3['credentials']['key'] = getenv('AWS_ACCESS_KEY_ID') ?: $this->s3['credentials']['key'];
        $this->s3['credentials']['secret'] = getenv('AWS_SECRET_ACCESS_KEY') ?: $this->s3['credentials']['secret'];
        $this->s3['bucket'] = getenv('S3_BUCKET') ?: $this->s3['bucket'];
        $this->s3['region'] = getenv('S3_REGION') ?: $this->s3['region'];
    }
}