<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'class', 'key', 'value', 'type', 'context', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all settings as key-value pairs
     */
    public function getSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }

    /**
     * Alias for getSettings() for backward compatibility
     */
    public function getAllSettings()
    {
        return $this->getSettings();
    }

    /**
     * Get a single setting value
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    /**
     * Set or update a setting
     */
    public function setSetting($key, $value, $type = 'string')
    {
        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'value' => $value,
                'type' => $type
            ]);
        } else {
            return $this->insert([
                'key' => $key,
                'value' => $value,
                'type' => $type
            ]);
        }
    }

    /**
     * Get Twilio settings
     */
    public function getTwilioSettings()
    {
        $keys = ['twilio_sid', 'twilio_token', 'twilio_number'];
        $settings = [];
        
        foreach ($keys as $key) {
            $settings[$key] = $this->getSetting($key, '');
        }
        
        return $settings;
    }

    /**
     * Get Pusher settings
     */
    public function getPusherSettings()
    {
        $keys = ['pusher_app_id', 'pusher_key', 'pusher_secret', 'pusher_cluster'];
        $settings = [];
        
        foreach ($keys as $key) {
            $settings[$key] = $this->getSetting($key, '');
        }
        
        return $settings;
    }

    /**
     * Get SMTP settings
     */
    public function getSmtpSettings()
    {
        $keys = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from'];
        $settings = [];
        
        foreach ($keys as $key) {
            $settings[$key] = $this->getSetting($key, '');
        }
        
        return $settings;
    }
} 