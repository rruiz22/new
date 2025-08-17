<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ImprovePublicPagesSecurity extends Migration
{
    public function up()
    {
        // Add security indexes to public_pages table
        if ($this->db->tableExists('public_pages')) {
            // Add index for status and privacy_level for better query performance
            $this->forge->addKey(['status', 'privacy_level'], false, false, 'idx_pages_status_privacy');
            $this->forge->processIndexes('public_pages');

            // Add index for slug lookups
            if (!$this->db->indexExists('public_pages', ['slug'])) {
                $this->forge->addKey('slug', false, true, 'idx_pages_slug_unique');
                $this->forge->processIndexes('public_pages');
            }

            // Add index for created_by for permission checks
            if (!$this->db->indexExists('public_pages', ['created_by'])) {
                $this->forge->addKey('created_by', false, false, 'idx_pages_created_by');
                $this->forge->processIndexes('public_pages');
            }
        }

        // Add security indexes to public_page_views table
        if ($this->db->tableExists('public_page_views')) {
            // Add composite index for spam prevention
            if (!$this->db->indexExists('public_page_views', ['page_id', 'ip_address', 'viewed_at'])) {
                $this->forge->addKey(['page_id', 'ip_address', 'viewed_at'], false, false, 'idx_views_spam_check');
                $this->forge->processIndexes('public_page_views');
            }

            // Add index for analytics queries
            if (!$this->db->indexExists('public_page_views', ['page_id', 'viewed_at'])) {
                $this->forge->addKey(['page_id', 'viewed_at'], false, false, 'idx_views_analytics');
                $this->forge->processIndexes('public_page_views');
            }
        }

        // Add security indexes to public_page_likes table
        if ($this->db->tableExists('public_page_likes')) {
            // Add composite index for duplicate prevention
            if (!$this->db->indexExists('public_page_likes', ['page_id', 'user_id'])) {
                $this->forge->addKey(['page_id', 'user_id'], false, false, 'idx_likes_user_unique');
                $this->forge->processIndexes('public_page_likes');
            }

            if (!$this->db->indexExists('public_page_likes', ['page_id', 'ip_address'])) {
                $this->forge->addKey(['page_id', 'ip_address'], false, false, 'idx_likes_ip_unique');
                $this->forge->processIndexes('public_page_likes');
            }
        }

        // Add security indexes to public_page_files table
        if ($this->db->tableExists('public_page_files')) {
            // Add index for file type filtering
            if (!$this->db->indexExists('public_page_files', ['page_id', 'file_type'])) {
                $this->forge->addKey(['page_id', 'file_type'], false, false, 'idx_files_type');
                $this->forge->processIndexes('public_page_files');
            }

            // Add index for filename uniqueness check
            if (!$this->db->indexExists('public_page_files', ['filename'])) {
                $this->forge->addKey('filename', false, false, 'idx_files_filename');
                $this->forge->processIndexes('public_page_files');
            }
        }

        // Create security log table for audit trail
        if (!$this->db->tableExists('public_pages_security_log')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'event_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'description' => [
                    'type' => 'TEXT',
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'ip_address' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 45,
                ],
                'user_agent' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'page_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'severity' => [
                    'type'       => 'ENUM',
                    'constraint' => ['low', 'medium', 'high', 'critical'],
                    'default'    => 'medium',
                ],
                'context_data' => [
                    'type' => 'JSON',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey(['event_type', 'created_at'], false, false, 'idx_security_event_time');
            $this->forge->addKey(['user_id', 'created_at'], false, false, 'idx_security_user_time');
            $this->forge->addKey(['ip_address', 'created_at'], false, false, 'idx_security_ip_time');
            $this->forge->addKey('severity', false, false, 'idx_security_severity');
            
            $this->forge->createTable('public_pages_security_log');
        }

        // Create rate limiting table
        if (!$this->db->tableExists('public_pages_rate_limits')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'identifier' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                ],
                'action_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                ],
                'attempts' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 1,
                ],
                'window_start' => [
                    'type' => 'DATETIME',
                ],
                'expires_at' => [
                    'type' => 'DATETIME',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey(['identifier', 'action_type'], false, true, 'idx_rate_limit_unique');
            $this->forge->addKey('expires_at', false, false, 'idx_rate_limit_expires');
            
            $this->forge->createTable('public_pages_rate_limits');
        }
    }

    public function down()
    {
        // Drop security log table
        if ($this->db->tableExists('public_pages_security_log')) {
            $this->forge->dropTable('public_pages_security_log');
        }

        // Drop rate limiting table
        if ($this->db->tableExists('public_pages_rate_limits')) {
            $this->forge->dropTable('public_pages_rate_limits');
        }

        // Note: We don't remove indexes from existing tables in down() 
        // to avoid potential data loss or performance issues
    }
}
