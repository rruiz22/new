<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArchiveMuteFieldsChannels extends Migration
{
    public function up()
    {
        // Agregar campos para archivar y silenciar en la tabla chat_channel_members
        $this->forge->addColumn('chat_channel_members', [
            'archived' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'is_admin'
            ],
            'muted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'archived'
            ]
        ]);
    }

    public function down()
    {
        // Eliminar los campos agregados
        $this->forge->dropColumn('chat_channel_members', 'archived');
        $this->forge->dropColumn('chat_channel_members', 'muted');
    }
} 