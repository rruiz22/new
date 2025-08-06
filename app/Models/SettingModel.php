<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    protected $allowedFields = ['key', 'value'];
    public $timestamps = false;

    public function getSetting($key, $default = null)
    {
        $row = $this->where('key', $key)->first();
        return $row ? $row['value'] : $default;
    }

    public function setSetting($key, $value)
    {
        $exists = $this->where('key', $key)->first();
        if ($exists) {
            return $this->update($key, ['value' => $value]);
        } else {
            return $this->insert(['key' => $key, 'value' => $value]);
        }
    }

    public function getAllSettings()
    {
        $settings = [];
        foreach ($this->findAll() as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
} 