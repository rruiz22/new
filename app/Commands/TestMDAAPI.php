<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestMDAAPI extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Testing';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'test:mda-api';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Test MDA.to API configuration and connectivity';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'test:mda-api [options]';

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
    protected $options = [
        '--vin' => 'Test with specific VIN (default: 5UXCR6C04L9C91038)'
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('🧪 Testing MDA.to API Configuration', 'yellow');
        CLI::write('=====================================', 'yellow');
        CLI::newLine();

        // Get configuration
        $apiKey = env('MDA_API_KEY');
        $brandedDomain = env('MDA_BRANDED_DOMAIN') ?: 'mda.to';
        $apiBaseUrl = env('MDA_API_BASE_URL') ?: 'https://mda.to';
        
        CLI::write('📋 Configuration:', 'cyan');
        CLI::write("   API Key: " . ($apiKey ? (substr($apiKey, 0, 4) . '***' . substr($apiKey, -4)) : 'NOT SET'), 'white');
        CLI::write("   Branded Domain: {$brandedDomain}", 'white');
        CLI::write("   API Base URL: {$apiBaseUrl}", 'white');
        CLI::newLine();

        // Validate API key
        $isValidApiKey = $apiKey && $apiKey !== 'your_mda_api_key_here' && strlen($apiKey) >= 5;
        
        if (!$isValidApiKey) {
            CLI::error('❌ Invalid or missing MDA API key');
            CLI::write('💡 Please set MDA_API_KEY in your .env file', 'yellow');
            return EXIT_ERROR;
        }

        CLI::write('✅ API key appears valid', 'green');
        CLI::newLine();

        // Test VIN
        $testVin = CLI::getOption('vin') ?: '5UXCR6C04L9C91038';
        $vinLast6 = substr($testVin, -6);
        
        CLI::write("🚗 Testing with VIN: {$testVin} (Last 6: {$vinLast6})", 'cyan');
        CLI::newLine();

        try {
            // Test vehicle shortlink generation
            CLI::write('🔗 Testing vehicle shortlink generation...', 'yellow');
            
            $mdaService = new \App\Services\VehicleMDAService();
            $result = $mdaService->generateVehicleShortlink($testVin);
            
            if ($result && isset($result['success']) && $result['success']) {
                CLI::write('✅ Shortlink generation successful!', 'green');
                CLI::write("   Short URL: {$result['short_url']}", 'white');
                CLI::write("   QR URL: {$result['qr_url']}", 'white');
                CLI::write("   Custom Slug: {$result['custom_slug']}", 'white');
                CLI::write("   Is Fallback: " . (isset($result['is_fallback']) && $result['is_fallback'] ? 'Yes' : 'No'), 'white');
            } else {
                CLI::error('❌ Shortlink generation failed');
                if (isset($result['message'])) {
                    CLI::write("   Error: {$result['message']}", 'red');
                }
            }
            
            CLI::newLine();
            
            // Test getting existing shortlink
            CLI::write('📖 Testing shortlink retrieval...', 'yellow');
            
            $existing = $mdaService->getVehicleShortlink($testVin);
            
            if ($existing && isset($existing['success']) && $existing['success']) {
                CLI::write('✅ Shortlink retrieval successful!', 'green');
                CLI::write("   Short URL: {$existing['short_url']}", 'white');
                CLI::write("   QR URL: {$existing['qr_url']}", 'white');
                CLI::write("   Created: {$existing['created_at']}", 'white');
            } else {
                CLI::write('ℹ️  No existing shortlink found (this is normal for first run)', 'yellow');
                if (isset($existing['message'])) {
                    CLI::write("   Message: {$existing['message']}", 'white');
                }
            }
            
        } catch (\Exception $e) {
            CLI::error('❌ Error during testing: ' . $e->getMessage());
            return EXIT_ERROR;
        }

        CLI::newLine();
        CLI::write('🎉 MDA API test completed!', 'green');
        
        return EXIT_SUCCESS;
    }
}
