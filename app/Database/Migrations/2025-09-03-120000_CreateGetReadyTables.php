<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGetReadyTables extends Migration
{
    public function up()
    {
        // Tabla principal de órdenes Get Ready
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'vin_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'stock_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'year' => [
                'type' => 'YEAR',
                'null' => true,
            ],
            'make' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'mileage' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'contact_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'current_step_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'assigned_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'User ID of assigned technician',
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['normal', 'urgent', 'high', 'low'],
                'default' => 'normal',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'completed', 'on_hold', 'cancelled'],
                'default' => 'active',
            ],
            'expected_completion' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'total_time_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total accumulated time across all steps',
            ],
            'photos_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'photos_urls' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array of S3 photo URLs',
            ],
            'current_location' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'internal_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'short_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'short_url_slug' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'lima_link_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'qr_generated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'qr_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'nfc_token' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'NFC scanning token',
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
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('vin_number');
        $this->forge->addIndex('client_id');
        $this->forge->addIndex('current_step_id');
        $this->forge->addIndex('assigned_to');
        $this->forge->addIndex('status');
        $this->forge->addIndex('nfc_token');
        $this->forge->createTable('get_ready_orders');

        // Tabla de pasos dinámicos del flujo Get Ready
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'order_position' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Order in workflow (1=In Transit, 2=In Detail, etc)',
            ],
            'color' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'primary',
                'comment' => 'Bootstrap color class',
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'box',
                'comment' => 'Feather icon name',
            ],
            'is_service_step' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 if this step allows tech assignments',
            ],
            'requires_approval' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'auto_move_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Auto-move to next step after X minutes',
            ],
            'notification_users' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Array of user IDs to notify when vehicle enters this step',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('slug');
        $this->forge->addIndex('order_position');
        $this->forge->addIndex('is_active');
        $this->forge->createTable('get_ready_steps');

        // Tabla de seguimiento de tiempo acumulativo
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'step_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'sub_step' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'For service sub-tracking: waiting_for_parts, assigned_to_tech, etc',
            ],
            'assigned_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'Tech ID for service assignments',
            ],
            'entered_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'exited_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'pause_start' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'When timer was paused',
            ],
            'pause_total_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total paused time',
            ],
            'time_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Active time in this step (excluding pauses)',
            ],
            'is_current' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '1 if vehicle is currently in this step',
            ],
            'notes' => [
                'type' => 'TEXT',
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
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addIndex('order_id');
        $this->forge->addIndex('step_id');
        $this->forge->addIndex('assigned_to');
        $this->forge->addIndex('is_current');
        $this->forge->addIndex('entered_at');
        $this->forge->createTable('get_ready_time_tracking');

        // Tabla de actividades y log de auditoría
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'action' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'created, moved_to_step, assigned_tech, added_photos, etc',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'from_step_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'to_step_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'metadata' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Additional data: tech_id, photo_count, location, etc',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addIndex('order_id');
        $this->forge->addIndex('user_id');
        $this->forge->addIndex('action');
        $this->forge->addIndex('created_at');
        $this->forge->createTable('get_ready_activities');

        // Insertar pasos por defecto
        $steps = [
            [
                'name' => 'In Transit',
                'slug' => 'in_transit',
                'description' => 'Vehicle is being transported to the facility',
                'order_position' => 1,
                'color' => 'primary',
                'icon' => 'truck',
                'is_service_step' => 0,
                'notification_users' => json_encode([]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'In Detail',
                'slug' => 'in_detail',
                'description' => 'Vehicle is being detailed and cleaned',
                'order_position' => 2,
                'color' => 'info',
                'icon' => 'droplet',
                'is_service_step' => 0,
                'notification_users' => json_encode([]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'In Service',
                'slug' => 'in_service',
                'description' => 'Vehicle is being serviced by technicians',
                'order_position' => 3,
                'color' => 'warning',
                'icon' => 'tool',
                'is_service_step' => 1,
                'notification_users' => json_encode([]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'In Bodyshop',
                'slug' => 'in_bodyshop',
                'description' => 'Vehicle is in bodyshop for repairs',
                'order_position' => 4,
                'color' => 'danger',
                'icon' => 'settings',
                'is_service_step' => 1,
                'notification_users' => json_encode([]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('get_ready_steps')->insertBatch($steps);
    }

    public function down()
    {
        $this->forge->dropTable('get_ready_activities');
        $this->forge->dropTable('get_ready_time_tracking');
        $this->forge->dropTable('get_ready_steps');
        $this->forge->dropTable('get_ready_orders');
    }
}