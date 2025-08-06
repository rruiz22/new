<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLimaApiBaseUrlSetting extends Migration
{
    public function up()
    {
        // Insert the new lima_api_base_url setting
        $data = [
            'key' => 'lima_api_base_url',
            'value' => 'https://mda.to',
            'description' => 'Base URL for Lima Links API endpoints',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if the setting already exists
        $builder = $this->db->table('settings');
        $existing = $builder->where('key', 'lima_api_base_url')->get()->getRowArray();

        if (!$existing) {
            $builder->insert($data);
        }
    }

    public function down()
    {
        // Remove the lima_api_base_url setting
        $this->db->table('settings')->where('key', 'lima_api_base_url')->delete();
    }
}
