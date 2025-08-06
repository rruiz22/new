<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateReconNotesTableStructure extends Migration
{
    public function up()
    {
        // Rename 'note' column to 'content'
        $this->forge->modifyColumn('recon_notes', [
            'note' => [
                'name' => 'content',
                'type' => 'TEXT',
                'null' => false,
            ]
        ]);

        // Add missing columns
        $this->forge->addColumn('recon_notes', [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'user_id'
            ]
        ]);

        $this->forge->addColumn('recon_notes', [
            'attachments' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'mentions'
            ]
        ]);

        $this->forge->addColumn('recon_notes', [
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'attachments'
            ]
        ]);

        $this->forge->addColumn('recon_notes', [
            'is_edited' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'metadata'
            ]
        ]);

        // Add index for parent_id
        $this->db->query('ALTER TABLE recon_notes ADD INDEX idx_parent_id (parent_id)');
        $this->db->query('ALTER TABLE recon_notes ADD INDEX idx_order_parent (order_id, parent_id)');
    }

    public function down()
    {
        // Remove added columns
        $this->forge->dropColumn('recon_notes', ['parent_id', 'attachments', 'metadata', 'is_edited']);
        
        // Rename 'content' back to 'note'
        $this->forge->modifyColumn('recon_notes', [
            'content' => [
                'name' => 'note',
                'type' => 'TEXT',
                'null' => false,
            ]
        ]);
    }
}
