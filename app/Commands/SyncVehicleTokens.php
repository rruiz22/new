<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SyncVehicleTokens extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Vehicles';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'vehicles:sync-tokens';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Sync existing location tokens with MDA shortlinks for unified vehicle system';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'vehicles:sync-tokens [options]';

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
        '--dry-run' => 'Show what would be done without making changes',
        '--force' => 'Force sync even if shortlinks already exist'
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $dryRun = CLI::getOption('dry-run');
        $force = CLI::getOption('force');

        CLI::write('Vehicle Token Synchronization', 'yellow');
        CLI::write('=====================================', 'yellow');
        CLI::newLine();

        if ($dryRun) {
            CLI::write('🔍 DRY RUN MODE - No changes will be made', 'cyan');
            CLI::newLine();
        }

        try {
            $tokenService = new \App\Services\VehicleTokenService();
            
            CLI::write('🔄 Starting token synchronization...', 'green');
            
            if ($dryRun) {
                $this->performDryRun($tokenService);
            } else {
                $result = $tokenService->syncExistingTokens();
                $this->displayResults($result);
            }

        } catch (\Exception $e) {
            CLI::error('❌ Error during synchronization: ' . $e->getMessage());
            return EXIT_ERROR;
        }

        CLI::newLine();
        CLI::write('✅ Token synchronization completed!', 'green');
        return EXIT_SUCCESS;
    }

    /**
     * Perform a dry run to show what would be done
     */
    protected function performDryRun($tokenService)
    {
        $db = \Config\Database::connect();
        
        // Get all active location tokens
        $tokens = $db->table('vehicle_location_tokens')
            ->where('is_active', 1)
            ->get()
            ->getResultArray();

        CLI::write("📊 Found " . count($tokens) . " active location tokens", 'cyan');
        CLI::newLine();

        $wouldSync = 0;
        $alreadyExists = 0;

        foreach ($tokens as $token) {
            CLI::write("🚗 VIN: {$token['vin_number']}", 'white');
            
            // Check if MDA shortlink exists
            try {
                $mdaService = new \App\Services\VehicleMDAService();
                $existing = $mdaService->getVehicleShortlink($token['vin_number']);
                
                if ($existing) {
                    CLI::write("   ✅ MDA shortlink already exists: {$existing['short_url']}", 'green');
                    $alreadyExists++;
                } else {
                    CLI::write("   🔄 Would create MDA shortlink for this vehicle", 'yellow');
                    $wouldSync++;
                }
            } catch (\Exception $e) {
                CLI::write("   ❌ Error checking MDA shortlink: {$e->getMessage()}", 'red');
            }
        }

        CLI::newLine();
        CLI::write("📈 Summary:", 'cyan');
        CLI::write("   - Total tokens: " . count($tokens), 'white');
        CLI::write("   - Would sync: {$wouldSync}", 'yellow');
        CLI::write("   - Already exist: {$alreadyExists}", 'green');
    }

    /**
     * Display synchronization results
     */
    protected function displayResults($result)
    {
        if ($result['success']) {
            CLI::write("📈 Synchronization Results:", 'cyan');
            CLI::write("   - Total tokens processed: {$result['total_tokens']}", 'white');
            CLI::write("   - Successfully synced: {$result['synced']}", 'green');
            CLI::write("   - Errors encountered: {$result['errors']}", $result['errors'] > 0 ? 'red' : 'white');
            
            if ($result['synced'] > 0) {
                CLI::newLine();
                CLI::write("🎉 {$result['synced']} vehicle(s) now have both location tokens and MDA shortlinks!", 'green');
            }
            
            if ($result['errors'] > 0) {
                CLI::newLine();
                CLI::write("⚠️  {$result['errors']} error(s) occurred. Check logs for details.", 'yellow');
            }
        } else {
            CLI::error("❌ Synchronization failed: {$result['error']}");
        }
    }
}
