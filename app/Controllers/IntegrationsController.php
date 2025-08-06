<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class IntegrationsController extends BaseController
{
    public function index()
    {
        $integrationModel = new \App\Models\IntegrationModel();
        $servicesStatus = $integrationModel->getServicesStatus();
        
        $data = [
            'title' => lang('App.manage_integrations'),
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => '']
            ],
            'services_status' => $servicesStatus
        ];

        return view('integrations/index', $data);
    }

    public function api()
    {
        $data = [
            'title' => lang('App.api_settings'),
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => base_url('integrations')],
                ['name' => lang('App.api_settings'), 'url' => '']
            ]
        ];

        return view('integrations/api', $data);
    }

    public function webhooks()
    {
        $data = [
            'title' => lang('App.webhook_settings'),
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => base_url('integrations')],
                ['name' => lang('App.webhook_settings'), 'url' => '']
            ]
        ];

        return view('integrations/webhooks', $data);
    }

    public function storage()
    {
        $integrationModel = new \App\Models\IntegrationModel();
        $s3Config = $integrationModel->getServiceConfiguration('s3');
        
        $data = [
            'title' => 'AWS S3 Storage Configuration',
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => base_url('integrations')],
                ['name' => 'AWS S3 Storage', 'url' => '']
            ],
            'config' => $s3Config
        ];

        return view('integrations/storage', $data);
    }

    public function media()
    {
        $integrationModel = new \App\Models\IntegrationModel();
        $tinypngConfig = $integrationModel->getServiceConfiguration('tinypng');
        $cloudinaryConfig = $integrationModel->getServiceConfiguration('cloudinary');
        
        $data = [
            'title' => 'Media Optimization Configuration',
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => base_url('integrations')],
                ['name' => 'Media Optimization', 'url' => '']
            ],
            'tinypng_config' => $tinypngConfig,
            'cloudinary_config' => $cloudinaryConfig
        ];

        return view('integrations/media', $data);
    }

    public function video()
    {
        $integrationModel = new \App\Models\IntegrationModel();
        $ffmpegConfig = $integrationModel->getServiceConfiguration('ffmpeg');
        
        // Load FFmpeg helper for auto-detection only when needed
        helper('ffmpeg');
        
        // If no config or invalid config, use auto-detection for display
        if (empty($ffmpegConfig) || 
            !isset($ffmpegConfig['ffmpeg_path']['value']) || 
            $ffmpegConfig['ffmpeg_path']['value'] === 'ffmpeg') {
            
            $autoDetectedPath = get_ffmpeg_path();
            
            // Update the config for display purposes
            if (empty($ffmpegConfig)) {
                $ffmpegConfig = [];
            }
            
            $ffmpegConfig['ffmpeg_path'] = [
                'value' => $autoDetectedPath,
                'label' => 'FFmpeg Path'
            ];
        }
        
        $data = [
            'title' => 'Video Processing Configuration',
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => base_url('integrations')],
                ['name' => 'Video Processing', 'url' => '']
            ],
            'ffmpeg_config' => $ffmpegConfig
        ];

        return view('integrations/video', $data);
    }

    public function external()
    {
        $data = [
            'title' => 'External Services Configuration',
            'breadcrumb' => [
                ['name' => lang('App.settings'), 'url' => base_url('settings')],
                ['name' => lang('App.manage_integrations'), 'url' => base_url('integrations')],
                ['name' => 'External Services', 'url' => '']
            ]
        ];

        return view('integrations/external', $data);
    }

    public function save()
    {
        try {
            log_message('info', 'Integration save started');
            log_message('info', 'Request method: ' . $this->request->getMethod());
            log_message('info', 'Is AJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));
            
            $integrationModel = new \App\Models\IntegrationModel();
            
            // Get raw input and parse it
            $rawInput = $this->request->getBody();
            log_message('info', 'Raw input: ' . $rawInput);
            
            // Get POST data (we're sending as form-urlencoded, not JSON)
            $postData = $this->request->getPost();
            $varData = $this->request->getVar();
            
            log_message('info', 'POST data: ' . json_encode($postData));
            log_message('info', 'VAR data: ' . json_encode($varData));
            
            // Get service name from available sources
            $serviceName = $postData['service_name'] ?? $varData['service_name'] ?? null;
            log_message('info', 'Service name: ' . ($serviceName ?? 'NULL'));
            
            if (!$serviceName) {
                log_message('error', 'Service name is missing from all data sources');
                throw new \Exception('Service name is required');
            }
            
            // Use the data source that has service_name
            if (isset($postData['service_name'])) {
                $sourceData = $postData;
            } else {
                $sourceData = $varData;
            }
            
            unset($sourceData['service_name']);
            
            // Clean and validate data
            $cleanData = [];
            foreach ($sourceData as $key => $value) {
                if ($value !== null && $value !== '') {
                    $cleanData[$key] = is_string($value) ? trim($value) : $value;
                }
            }
            
            log_message('info', 'Clean data: ' . json_encode($cleanData));
            
            if (empty($cleanData)) {
                throw new \Exception('No configuration data provided');
            }
            
            // Save configuration
            log_message('info', 'About to save configuration with data: ' . json_encode($cleanData));
            $result = $integrationModel->saveServiceConfiguration($serviceName, $cleanData);
            
            log_message('info', 'Save result: ' . ($result ? 'success' : 'failed'));
            
            if (!$result) {
                log_message('error', 'Failed to save configuration to database');
                throw new \Exception('Failed to save configuration to database');
            }
            
            log_message('info', 'Configuration saved successfully, preparing response');
            
            // Always return JSON for AJAX requests
            $response = [
                'success' => true,
                'message' => ucfirst($serviceName) . ' configuration saved successfully!'
            ];
            
            log_message('info', 'Sending response: ' . json_encode($response));
            return $this->response->setJSON($response);
            
        } catch (\Throwable $e) {
            log_message('error', 'Integration save error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            log_message('error', 'Raw input: ' . $this->request->getBody());
            log_message('error', 'POST data: ' . json_encode($this->request->getPost()));
            log_message('error', 'VAR data: ' . json_encode($this->request->getVar()));
            
            // Always return JSON for errors too
            $errorResponse = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            
            log_message('error', 'Sending error response: ' . json_encode($errorResponse));
            return $this->response->setJSON($errorResponse);
        }
    }

    public function testConnection()
    {
        try {
            $serviceName = $this->request->getPost('service_name');
            $integrationModel = new \App\Models\IntegrationModel();
            
            // Get service configuration
            $config = $integrationModel->getServiceConfiguration($serviceName);
            
            // For FFmpeg, allow testing even without configuration
            if (empty($config) && $serviceName !== 'ffmpeg') {
                throw new \Exception('Service not configured');
            }
            
            // Simulate connection test based on service
            $success = $this->performConnectionTest($serviceName, $config);
            
            // Update last tested timestamp
            $integrationModel->updateLastTested($serviceName, $success);
            
            return $this->response->setJSON([
                'success' => $success,
                'message' => $success ? 'Connection successful!' : 'Connection failed!'
            ]);
            
        } catch (\Throwable $e) {
            log_message('error', 'Connection test error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    private function performConnectionTest($serviceName, $config)
    {
        // Simple validation based on service type
        switch ($serviceName) {
            case 's3':
                return !empty($config['aws_access_key']['value']) && 
                       !empty($config['aws_secret_key']['value']) && 
                       !empty($config['aws_region']['value']) && 
                       !empty($config['s3_bucket']['value']);
                       
            case 'tinypng':
                return !empty($config['tinypng_api_key']['value']);
                
            case 'ffmpeg':
                // Test FFmpeg installation using helper
                helper('ffmpeg');
                
                // Try configured path first, fallback to auto-detection
                if (!empty($config) && isset($config['ffmpeg_path']['value']) && $config['ffmpeg_path']['value'] !== 'ffmpeg') {
                    $ffmpegPath = $config['ffmpeg_path']['value'];
                    return test_ffmpeg_path($ffmpegPath);
                } else {
                    // Use auto-detection
                    $ffmpegPath = get_ffmpeg_path();
                    return test_ffmpeg_path($ffmpegPath);
                }
                
            case 'stripe':
                return !empty($config['stripe_public_key']['value']) && 
                       !empty($config['stripe_secret_key']['value']);
                       
            case 'mailgun':
                return !empty($config['mailgun_domain']['value']) && 
                       !empty($config['mailgun_api_key']['value']);
                       
            case 'twilio':
                return !empty($config['twilio_sid']['value']) && 
                       !empty($config['twilio_token']['value']);
                       
            default:
                return true; // Default to success for unknown services
        }
    }

    public function saveVideoConfig()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        $validationRules = [
            'ffmpeg_path' => 'required|string|max_length[255]',
            'max_video_size' => 'required|integer|greater_than[0]',
            'video_quality' => 'required|in_list[low,medium,high]',
            'output_format' => 'required|in_list[mp4,webm,avi,mov]',
            'generate_thumbnails' => 'in_list[0,1]',
            'thumbnail_time' => 'integer|greater_than[0]',
            'max_resolution' => 'required|in_list[720p,1080p,1440p,2160p]'
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Validation failed',
                'messages' => $this->validator->getErrors()
            ]);
        }

        try {
            $integrationModel = new \App\Models\IntegrationModel();
            $postData = $this->request->getPost();

            $configurations = [
                'ffmpeg_path' => $postData['ffmpeg_path'],
                'max_video_size' => $postData['max_video_size'],
                'video_quality' => $postData['video_quality'],
                'output_format' => $postData['output_format'],
                'generate_thumbnails' => $postData['generate_thumbnails'] ?? '0',
                'thumbnail_time' => $postData['thumbnail_time'] ?? '5',
                'max_resolution' => $postData['max_resolution']
            ];

            foreach ($configurations as $key => $value) {
                $integrationModel->saveServiceConfiguration('ffmpeg', $key, $value);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'FFmpeg configuration saved successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'FFmpeg configuration save failed: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error' => 'Configuration save failed',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function testFFmpeg()
    {
        try {
            log_message('info', 'FFmpeg test started');
            
            // Load FFmpeg helper
            helper('ffmpeg');
            
            // Get FFmpeg path from request or use auto-detection
            $requestedPath = $this->request->getPost('ffmpeg_path') ?? $this->request->getVar('ffmpeg_path') ?? null;
            
            if (!empty($requestedPath) && $requestedPath !== 'ffmpeg') {
                // Test the specific path requested
                $ffmpegPath = trim($requestedPath);
                log_message('info', 'Testing requested FFmpeg path: ' . $ffmpegPath);
                
                if (test_ffmpeg_path($ffmpegPath)) {
                    $testPath = $ffmpegPath;
                } else {
                    // Fallback to auto-detection
                    $testPath = get_ffmpeg_path();
                    log_message('info', 'Requested path failed, using auto-detected: ' . $testPath);
                }
            } else {
                // Use auto-detection
                $testPath = get_ffmpeg_path();
                log_message('info', 'Using auto-detected FFmpeg path: ' . $testPath);
            }
            
            // Get comprehensive FFmpeg information
            $ffmpegInfo = get_ffmpeg_info();
            log_message('info', 'FFmpeg info: ' . json_encode($ffmpegInfo));
            
            if ($ffmpegInfo['installed']) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'FFmpeg is working correctly',
                    'version' => $ffmpegInfo['version'],
                    'codecs' => $ffmpegInfo['codecs'],
                    'status' => 'installed',
                    'path' => $ffmpegInfo['path'],
                    'environment' => $ffmpegInfo['environment'],
                    'os' => $ffmpegInfo['os']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'FFmpeg not found or not working',
                    'status' => 'not_installed',
                    'attempted_path' => $testPath,
                    'environment' => ENVIRONMENT,
                    'os' => PHP_OS_FAMILY
                ]);
            }
            
        } catch (\Throwable $e) {
            log_message('error', 'FFmpeg test error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'FFmpeg test failed: ' . $e->getMessage(),
                'status' => 'error'
            ]);
        }
    }
} 