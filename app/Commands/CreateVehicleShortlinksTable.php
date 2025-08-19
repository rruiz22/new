<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateVehicleShortlinksTable extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Database';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'db:create-vehicle-shortlinks';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Create the vehicle_shortlinks table manually';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:create-vehicle-shortlinks';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Creating vehicle_shortlinks table...', 'yellow');
        
        try {
            $db = \Config\Database::connect();
            
            if ($db->tableExists('vehicle_shortlinks')) {
                CLI::write('✅ Table vehicle_shortlinks already exists', 'green');
                return EXIT_SUCCESS;
            }
            
            $sql = "
            CREATE TABLE vehicle_shortlinks (
                id INT AUTO_INCREMENT PRIMARY KEY,
                vin_number VARCHAR(17) NOT NULL,
                vehicle_id INT NULL,
                short_url VARCHAR(255) NOT NULL,
                short_url_slug VARCHAR(50) NULL,
                mda_link_id VARCHAR(100) NULL,
                target_url VARCHAR(500) NOT NULL,
                qr_url VARCHAR(500) NULL,
                qr_image TEXT NULL,
                is_active TINYINT(1) DEFAULT 1,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL,
                INDEX idx_vin_number (vin_number),
                INDEX idx_short_url_slug (short_url_slug),
                INDEX idx_is_active (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
            
            $db->query($sql);
            
            // Verify table was created
            if ($db->tableExists('vehicle_shortlinks')) {
                CLI::write('✅ Table vehicle_shortlinks created successfully!', 'green');
                
                // Show table structure
                $fields = $db->getFieldData('vehicle_shortlinks');
                CLI::write('📋 Table structure:', 'cyan');
                foreach ($fields as $field) {
                    CLI::write("   - {$field->name} ({$field->type})", 'white');
                }
                
                return EXIT_SUCCESS;
            } else {
                CLI::error('❌ Failed to create table vehicle_shortlinks');
                return EXIT_ERROR;
            }
            
        } catch (\Exception $e) {
            CLI::error('❌ Error creating table: ' . $e->getMessage());
            return EXIT_ERROR;
        }
    }
}
