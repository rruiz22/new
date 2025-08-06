<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddArchiveMuteFields extends Migration
{
    public function up()
    {
        // Agregar campos para archivar y silenciar en la tabla chat_conversations
        $this->forge->addColumn('chat_conversations', [
            'archived_by_user_one' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'user_two'
            ],
            'archived_by_user_two' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'archived_by_user_one'
            ],
            'muted_by_user_one' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'archived_by_user_two'
            ],
            'muted_by_user_two' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'muted_by_user_one'
            ]
        ]);
    }

    public function down()
    {
        // Eliminar los campos agregados
        $this->forge->dropColumn('chat_conversations', 'archived_by_user_one');
        $this->forge->dropColumn('chat_conversations', 'archived_by_user_two');
        $this->forge->dropColumn('chat_conversations', 'muted_by_user_one');
        $this->forge->dropColumn('chat_conversations', 'muted_by_user_two');
    }
} 