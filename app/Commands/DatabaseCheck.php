<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * MDA Database Check Command
 * 
 * Comando personalizado para verificar el estado de la base de datos
 * Integrado con el sistema de monitoreo de BD
 * 
 * Uso:
 * php spark db:check
 * php spark db:check --full
 * php spark db:check --migrations
 * php spark db:check --update-docs
 * 
 * @package App\Commands
 */
class DatabaseCheck extends BaseCommand
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
    protected $name = 'db:check';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Verifica el estado y estructura de la base de datos MDA';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:check [options]';

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
        '--full'         => 'Generar reporte completo de la base de datos',
        '--migrations'   => 'Verificar solo el estado de migraciones',
        '--update-docs'  => 'Actualizar documentación CLAUDE.md con datos actuales',
        '--quick'        => 'Verificación rápida del estado general',
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('MDA Database Check Tool', 'green');
        CLI::write(str_repeat('=', 50), 'yellow');

        // Verificar si el script de monitoreo existe
        $monitorScript = ROOTPATH . 'database-monitor.php';
        if (!file_exists($monitorScript)) {
            CLI::error('❌ Script de monitoreo no encontrado: ' . $monitorScript);
            return;
        }

        // Obtener opciones
        $full = CLI::getOption('full');
        $migrations = CLI::getOption('migrations');
        $updateDocs = CLI::getOption('update-docs');
        $quick = CLI::getOption('quick');

        try {
            // Verificar conexión a la BD
            CLI::write('🔌 Verificando conexión a la base de datos...', 'cyan');
            $this->testConnection();
            CLI::write('✅ Conexión exitosa', 'green');
            
            if ($full) {
                $this->runFullReport($monitorScript);
            } elseif ($migrations) {
                $this->checkMigrations($monitorScript);
            } elseif ($updateDocs) {
                $this->updateDocumentation($monitorScript);
            } elseif ($quick) {
                $this->quickCheck($monitorScript);
            } else {
                // Comportamiento por defecto: verificación rápida
                $this->quickCheck($monitorScript);
                CLI::newLine();
                CLI::write('💡 Opciones disponibles:', 'yellow');
                CLI::write('   --full         : Reporte completo');
                CLI::write('   --migrations   : Estado de migraciones');
                CLI::write('   --update-docs  : Actualizar documentación');
                CLI::write('   --quick        : Verificación rápida (por defecto)');
            }

        } catch (\Exception $e) {
            CLI::error('❌ Error: ' . $e->getMessage());
            return;
        }

        CLI::newLine();
        CLI::write('✅ Verificación completada', 'green');
    }

    /**
     * Test database connection
     */
    private function testConnection()
    {
        $db = \Config\Database::connect();
        
        if (!$db->connID) {
            throw new \Exception('No se pudo conectar a la base de datos');
        }

        // Test básico de consulta
        $query = $db->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = DATABASE()");
        $result = $query->getRow();
        
        if (!$result) {
            throw new \Exception('Error al ejecutar consulta de prueba');
        }

        CLI::write("   Tablas detectadas: {$result->count}", 'white');
    }

    /**
     * Run full database report
     */
    private function runFullReport($monitorScript)
    {
        CLI::write('📊 Generando reporte completo...', 'cyan');
        CLI::newLine();
        
        $output = shell_exec("php \"{$monitorScript}\" --report 2>&1");
        
        if ($output) {
            echo $output;
        } else {
            CLI::error('❌ No se pudo generar el reporte completo');
        }
    }

    /**
     * Check migration status
     */
    private function checkMigrations($monitorScript)
    {
        CLI::write('🔄 Verificando estado de migraciones...', 'cyan');
        CLI::newLine();
        
        // Ejecutar comando de migraciones de CodeIgniter
        $output = shell_exec('php spark migrate:status 2>&1');
        
        if ($output) {
            $lines = explode("\n", $output);
            $pendingCount = 0;
            $executedCount = 0;
            
            foreach ($lines as $line) {
                if (strpos($line, '---') !== false && strpos($line, '|') !== false) {
                    $pendingCount++;
                } elseif (strpos($line, '|') !== false && preg_match('/\d{4}-\d{2}-\d{2}/', $line)) {
                    $executedCount++;
                }
            }
            
            CLI::write("✅ Migraciones ejecutadas: {$executedCount}", 'green');
            
            if ($pendingCount > 0) {
                CLI::write("⚠️  Migraciones pendientes: {$pendingCount}", 'yellow');
                CLI::write("🚨 ATENCIÓN: Revisar migraciones antes de ejecutar", 'red');
                CLI::newLine();
                CLI::write('Para ver detalles:', 'cyan');
                CLI::write('   php spark migrate:status', 'white');
            } else {
                CLI::write("✅ Todas las migraciones están ejecutadas", 'green');
            }
        } else {
            CLI::error('❌ No se pudo obtener el estado de migraciones');
        }
    }

    /**
     * Update CLAUDE.md documentation
     */
    private function updateDocumentation($monitorScript)
    {
        CLI::write('📝 Actualizando documentación CLAUDE.md...', 'cyan');
        
        $output = shell_exec("php \"{$monitorScript}\" --update-docs 2>&1");
        
        if (strpos($output, '✅') !== false) {
            CLI::write('✅ Documentación actualizada exitosamente', 'green');
        } else {
            CLI::error('❌ Error al actualizar documentación');
            if ($output) {
                CLI::write($output, 'red');
            }
        }
    }

    /**
     * Quick database check
     */
    private function quickCheck($monitorScript)
    {
        CLI::write('🔍 Verificación rápida del estado de la BD...', 'cyan');
        CLI::newLine();
        
        $output = shell_exec("php \"{$monitorScript}\" --quick 2>&1");
        
        if ($output) {
            echo $output;
        } else {
            CLI::error('❌ No se pudo ejecutar verificación rápida');
        }
        
        CLI::newLine();
        CLI::write('💡 Para más detalles usar: php spark db:check --full', 'yellow');
    }

    /**
     * Show help information
     */
    public function showHelp()
    {
        CLI::write('MDA Database Check - Herramienta de Verificación de BD', 'green');
        CLI::newLine();
        CLI::write('Uso:', 'yellow');
        CLI::write('  php spark db:check [opciones]', 'white');
        CLI::newLine();
        CLI::write('Opciones:', 'yellow');
        CLI::write('  --full         Generar reporte completo de la base de datos', 'white');
        CLI::write('  --migrations   Verificar solo el estado de migraciones', 'white');
        CLI::write('  --update-docs  Actualizar documentación CLAUDE.md', 'white');
        CLI::write('  --quick        Verificación rápida (por defecto)', 'white');
        CLI::newLine();
        CLI::write('Ejemplos:', 'yellow');
        CLI::write('  php spark db:check                 # Verificación rápida', 'white');
        CLI::write('  php spark db:check --full          # Reporte completo', 'white');
        CLI::write('  php spark db:check --migrations    # Solo migraciones', 'white');
        CLI::write('  php spark db:check --update-docs   # Actualizar docs', 'white');
    }
}