<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePublicPagesTable extends Migration
{
    public function up()
    {
        // Create public_pages table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
            ],
            'content' => [
                'type' => 'LONGTEXT',
            ],
            'excerpt' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'privacy_level' => [
                'type' => 'ENUM',
                'constraint' => ['public', 'password', 'users_only', 'roles', 'private'],
                'default' => 'public',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'allowed_roles' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'template' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'default' => 'default',
            ],
            'custom_css' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'custom_js' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published', 'archived'],
                'default' => 'draft',
            ],
            'featured_image' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'views_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
            ],
            'likes_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
            ],
            'comments_enabled' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'social_sharing' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_author' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'show_date' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'version' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 1,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'published_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('slug');
        $this->forge->addKey('status');
        $this->forge->addKey('privacy_level');
        $this->forge->addKey('created_by');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('updated_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('public_pages');

        // Create public_page_files table for multimedia content
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'page_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'filename' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'original_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'mime_type' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'file_size' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'file_path' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
            ],
            'alt_text' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
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
        $this->forge->addKey('page_id');
        $this->forge->addForeignKey('page_id', 'public_pages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('public_page_files');

        // Create public_page_versions table for version control
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'page_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'version_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'content' => [
                'type' => 'LONGTEXT',
            ],
            'changes_summary' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['page_id', 'version_number']);
        $this->forge->addForeignKey('page_id', 'public_pages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('public_page_versions');

        // Create public_page_views table for analytics
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'page_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'referrer' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'session_id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => true,
            ],
            'viewed_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('page_id');
        $this->forge->addKey('viewed_at');
        $this->forge->addForeignKey('page_id', 'public_pages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('public_page_views');

        // Create public_page_likes table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'page_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'reaction_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'like',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['page_id', 'user_id']);
        $this->forge->addKey(['page_id', 'ip_address']);
        $this->forge->addForeignKey('page_id', 'public_pages', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('public_page_likes');
    }

    public function down()
    {
        $this->forge->dropTable('public_page_likes');
        $this->forge->dropTable('public_page_views');
        $this->forge->dropTable('public_page_versions');
        $this->forge->dropTable('public_page_files');
        $this->forge->dropTable('public_pages');
    }
}
