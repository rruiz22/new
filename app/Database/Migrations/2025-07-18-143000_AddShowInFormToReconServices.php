<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddShowInFormToReconServices extends Migration
{
    public function up()
    {
        // Add show_in_form field to recon_services table
        $this->forge->addColumn('recon_services', [
            'show_in_form' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'visibility_type'
            ]
        ]);
    }

    public function down()
    {
        // Remove the show_in_form field
        $this->forge->dropColumn('recon_services', 'show_in_form');
    }
} 