<?php

namespace App\Models;

use CodeIgniter\Model;

class IntegrationModel extends Model
{
    protected $table = 'integration_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array'; 
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'service_name', 
        'configuration_key', 
        'configuration_value', 
        'is_active', 
        'is_encrypted',
        'last_tested_at',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'service_name' => 'required|max_length[100]',
        'configuration_key' => 'required|max_length[100]',
        'configuration_value' => 'required',
        'is_active' => 'in_list[0,1]',
        'is_encrypted' => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'service_name' => [
            'required' => 'Service name is required',
            'max_length' => 'Service name cannot exceed 100 characters'
        ],
        'configuration_key' => [
            'required' => 'Configuration key is required',
            'max_length' => 'Configuration key cannot exceed 100 characters'
        ],
        'configuration_value' => [
            'required' => 'Configuration value is required'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get configuration for a specific service
     */
    public function getServiceConfiguration($serviceName)
    {
        $configurations = $this->where('service_name', $serviceName)->findAll();
        
        $result = [];
        foreach ($configurations as $config) {
            $value = $config['configuration_value'];
            
            // Decrypt if encrypted
            if ($config['is_encrypted'] == 1) {
                $value = $this->decrypt($value);
            }
            
            $result[$config['configuration_key']] = [
                'value' => $value,
                'is_active' => $config['is_active'],
                'last_tested_at' => $config['last_tested_at']
            ];
        }
        
        return $result;
    }

    /**
     * Save service configuration
     */
    public function saveServiceConfiguration($serviceName, $configurations)
    {
        try {
            foreach ($configurations as $key => $value) {
                // Skip empty values
                if (empty($value) && $value !== '0') {
                    continue;
                }
                
                // Determine if this should be encrypted (keys, secrets, tokens)
                $shouldEncrypt = $this->shouldEncryptField($key);
                
                $configValue = $shouldEncrypt ? $this->encrypt($value) : $value;
                
                // Check if configuration already exists
                $existing = $this->where('service_name', $serviceName)
                               ->where('configuration_key', $key)
                               ->first();
                
                $data = [
                    'service_name' => $serviceName,
                    'configuration_key' => $key,
                    'configuration_value' => $configValue,
                    'is_encrypted' => $shouldEncrypt ? 1 : 0,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($existing) {
                    $result = $this->update($existing['id'], $data);
                } else {
                    $result = $this->insert($data);
                }
                
                if (!$result) {
                    log_message('error', 'Failed to save config: ' . $serviceName . '.' . $key);
                    return false;
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving integration configuration: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test service connection and update last_tested_at
     */
    public function updateLastTested($serviceName, $success = true)
    {
        $this->where('service_name', $serviceName)
             ->set('last_tested_at', date('Y-m-d H:i:s'))
             ->update();
        
        return $success;
    }

    /**
     * Get active services
     */
    public function getActiveServices()
    {
        return $this->select('DISTINCT service_name')
                   ->where('is_active', 1)
                   ->findAll();
    }

    /**
     * Toggle service active status
     */
    public function toggleServiceStatus($serviceName, $isActive)
    {
        return $this->where('service_name', $serviceName)
                   ->set('is_active', $isActive ? 1 : 0)
                   ->update();
    }

    /**
     * Get all services with their status
     */
    public function getServicesStatus()
    {
        $services = ['s3', 'tinypng', 'cloudinary', 'ffmpeg', 'stripe', 'mailgun', 'twilio', 'monitoring'];
        $result = [];
        
        foreach ($services as $service) {
            $config = $this->where('service_name', $service)
                          ->where('is_active', 1)
                          ->countAllResults();
            
            $lastTested = $this->where('service_name', $service)
                              ->selectMax('last_tested_at')
                              ->first();
            
            $result[$service] = [
                'is_configured' => $config > 0,
                'last_tested_at' => $lastTested['last_tested_at'] ?? null
            ];
        }
        
        return $result;
    }

    /**
     * Remove service configuration
     */
    public function removeServiceConfiguration($serviceName)
    {
        return $this->where('service_name', $serviceName)->delete();
    }

    /**
     * Determine if a field should be encrypted (disabled for now)
     */
    private function shouldEncryptField($fieldName)
    {
        // Disable encryption to avoid starter key issues
        return false;
    }

    /**
     * Encrypt sensitive data (disabled for now)
     */
    private function encrypt($data)
    {
        // No encryption for now to avoid starter key issues
        return $data;
    }

    /**
     * Decrypt sensitive data (disabled for now)
     */
    private function decrypt($data)
    {
        // No decryption needed
        return $data;
    }

    /**
     * Get masked configuration (for display purposes)
     */
    public function getMaskedConfiguration($serviceName)
    {
        $config = $this->getServiceConfiguration($serviceName);
        
        foreach ($config as $key => &$value) {
            if ($this->shouldEncryptField($key)) {
                $originalValue = $value['value'];
                if (strlen($originalValue) > 4) {
                    $value['value'] = substr($originalValue, 0, 4) . str_repeat('*', strlen($originalValue) - 4);
                } else {
                    $value['value'] = str_repeat('*', strlen($originalValue));
                }
            }
        }
        
        return $config;
    }
} 