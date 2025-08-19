<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FindVIN extends BaseCommand
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
    protected $name = 'db:find-vin';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Find vehicle by last 6 VIN characters and test MDA generation';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:find-vin <last6>';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'last6' => 'Last 6 characters of VIN'
    ];

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
        $last6 = $params[0] ?? CLI::prompt('Enter last 6 VIN characters');
        
        if (empty($last6)) {
            CLI::error('Last 6 VIN characters are required');
            return EXIT_ERROR;
        }

        CLI::write("🔍 Searching for VIN ending in: {$last6}", 'yellow');
        CLI::newLine();

        try {
            $db = \Config\Database::connect();
            
            // Buscar vehículo con últimos 6 caracteres
            $vehicle = $db->query('SELECT vin_number, vehicle FROM recon_orders WHERE vin_number LIKE ? AND deleted_at IS NULL LIMIT 1', ["%{$last6}"])->getRowArray();

            if ($vehicle) {
                CLI::write('✅ Vehicle found:', 'green');
                CLI::write("   VIN: {$vehicle['vin_number']}", 'white');
                CLI::write("   Vehicle: {$vehicle['vehicle']}", 'white');
                CLI::newLine();
                
                // Probar generar shortlink para este VIN
                CLI::write('🔗 Testing MDA shortlink generation...', 'yellow');
                
                $mdaService = new \App\Services\VehicleMDAService();
                $result = $mdaService->generateVehicleShortlink($vehicle['vin_number']);
                
                if ($result && isset($result['success']) && $result['success']) {
                    CLI::write('✅ MDA shortlink generation successful!', 'green');
                    CLI::write("   Short URL: {$result['short_url']}", 'white');
                    CLI::write("   QR URL: {$result['qr_url']}", 'white');
                    CLI::write("   Custom Slug: {$result['custom_slug']}", 'white');
                    CLI::write("   Is Fallback: " . (isset($result['is_fallback']) && $result['is_fallback'] ? 'Yes' : 'No'), 'white');
                } else {
                    CLI::error('❌ MDA shortlink generation failed');
                    CLI::write('Result: ' . json_encode($result, JSON_PRETTY_PRINT), 'red');
                }
                
                CLI::newLine();
                
                // Test retrieval
                CLI::write('📖 Testing shortlink retrieval...', 'yellow');
                $existing = $mdaService->getVehicleShortlink($vehicle['vin_number']);
                
                if ($existing && isset($existing['success']) && $existing['success']) {
                    CLI::write('✅ Shortlink retrieval successful!', 'green');
                    CLI::write("   Short URL: {$existing['short_url']}", 'white');
                    CLI::write("   QR URL: {$existing['qr_url']}", 'white');
                } else {
                    CLI::write('⚠️  Shortlink retrieval failed or not found', 'yellow');
                    CLI::write('Result: ' . json_encode($existing, JSON_PRETTY_PRINT), 'yellow');
                }
                
            } else {
                CLI::error("❌ No vehicle found with VIN ending in: {$last6}");
                
                // Show available VINs for reference
                CLI::write('📋 Available VINs (last 10):', 'cyan');
                $vins = $db->query('SELECT vin_number, vehicle FROM recon_orders WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 10')->getResultArray();
                
                foreach ($vins as $v) {
                    $last6_available = substr($v['vin_number'], -6);
                    CLI::write("   {$v['vin_number']} ({$v['vehicle']}) -> Last 6: {$last6_available}", 'white');
                }
            }
            
        } catch (\Exception $e) {
            CLI::error('❌ Error: ' . $e->getMessage());
            return EXIT_ERROR;
        }

        return EXIT_SUCCESS;
    }
}
