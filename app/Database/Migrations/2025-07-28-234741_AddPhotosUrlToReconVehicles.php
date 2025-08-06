<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotosUrlToReconVehicles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('recon_vehicles', [
            'photos_url' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'URL where vehicle photos are stored (e.g., Cloud Storage, Google Drive, etc.)'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('recon_vehicles', 'photos_url');
    }
}
