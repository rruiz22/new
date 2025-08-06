<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateServiceOrdersCommentsTable extends Migration
{
    public function up()
    {
        // Add new columns to support attachments, mentions, and metadata
        $fields = [
            'attachments' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON array of file attachments'
            ],
            'mentions' => [
                'type' => 'JSON', 
                'null' => true,
                'comment' => 'JSON array of mentioned users'
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'JSON object for additional metadata (IP, user agent, etc.)'
            ]
        ];

        $this->forge->addColumn('service_orders_comments', $fields);

        // Add regular indexes for performance
        $this->forge->addKey(['order_id', 'created_at'], false, false, 'idx_service_orders_comments_order_date');
        $this->forge->addKey(['created_by'], false, false, 'idx_service_orders_comments_user');
        
        // Process the table
        $this->forge->processIndexes('service_orders_comments');
    }

    public function down()
    {
        // Drop the added columns
        $this->forge->dropColumn('service_orders_comments', ['attachments', 'mentions', 'metadata']);
        
        // Drop the indexes
        $this->db->query('DROP INDEX IF EXISTS idx_service_orders_comments_order_date ON service_orders_comments');
        $this->db->query('DROP INDEX IF EXISTS idx_service_orders_comments_user ON service_orders_comments');
    }
} 