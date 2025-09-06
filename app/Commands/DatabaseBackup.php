<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * MDA Database Backup Command
 * 
 * Comando para realizar backups de la base de datos remota
 * Incluye opciones de backup completo, por tablas específicas y rotación automática
 * 
 * Uso:
 * php spark db:backup
 * php spark db:backup --tables=sales_orders,service_orders
 * php spark db:backup --full --rotate=30
 * php spark db:backup --structure-only
 * 
 * @package App\Commands
 */
class DatabaseBackup extends BaseCommand
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
    protected $name = 'db:backup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Crear backup de la base de datos MDA con rotación automática';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'db:backup [options]';

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
        '--full'           => 'Backup completo de toda la base de datos',
        '--tables'         => 'Backup de tablas específicas (separadas por comas)',
        '--structure-only' => 'Solo estructura, sin datos',
        '--rotate'         => 'Número de backups a mantener (por defecto: 30)',
        '--compress'       => 'Comprimir el backup con gzip',
        '--verify'         => 'Verificar integridad del backup después de crearlo',
    ];

    /**
     * Base de datos y configuración
     */
    private $dbConfig = [
        'hostname' => '35.212.30.157',
        'database' => 'dbuc0youbm7qp9',
        'username' => 'u9jvaasruh9vc',
        'password' => 'lalinha01?',
        'port' => 3306
    ];

    /**
     * Directorio de backups
     */
    private $backupDir;

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('MDA Database Backup Tool', 'green');
        CLI::write(str_repeat('=', 50), 'yellow');

        // Configurar directorio de backups
        $this->setupBackupDirectory();

        // Obtener opciones
        $full = CLI::getOption('full');
        $tables = CLI::getOption('tables');
        $structureOnly = CLI::getOption('structure-only');
        $rotate = CLI::getOption('rotate') ?? 30;
        $compress = CLI::getOption('compress');
        $verify = CLI::getOption('verify');

        try {
            // Verificar conexión a la BD
            CLI::write('🔌 Verificando conexión a la base de datos...', 'cyan');
            $this->testConnection();
            CLI::write('✅ Conexión exitosa', 'green');

            // Verificar espacio en disco
            $this->checkDiskSpace();

            // Determinar tipo de backup
            if ($tables) {
                $this->backupSpecificTables(explode(',', $tables), $structureOnly, $compress, $verify);
            } else {
                $this->backupFullDatabase($structureOnly, $compress, $verify);
            }

            // Rotar backups antiguos
            if ($rotate > 0) {
                $this->rotateBackups($rotate);
            }

            CLI::newLine();
            CLI::write('✅ Proceso de backup completado exitosamente', 'green');
            CLI::write('📁 Backups disponibles en: ' . $this->backupDir, 'cyan');

        } catch (\Exception $e) {
            CLI::error('❌ Error durante el backup: ' . $e->getMessage());
            return;
        }
    }

    /**
     * Configurar directorio de backups
     */
    private function setupBackupDirectory()
    {
        $this->backupDir = WRITEPATH . 'backups/';
        
        if (!is_dir($this->backupDir)) {
            if (!mkdir($this->backupDir, 0755, true)) {
                throw new \Exception('No se pudo crear el directorio de backups: ' . $this->backupDir);
            }
            CLI::write('📁 Directorio de backups creado: ' . $this->backupDir, 'yellow');
        }

        // Crear archivo .gitignore si no existe
        $gitignoreFile = $this->backupDir . '.gitignore';
        if (!file_exists($gitignoreFile)) {
            file_put_contents($gitignoreFile, "*\n!.gitignore\n");
        }
    }

    /**
     * Verificar conexión a la base de datos
     */
    private function testConnection()
    {
        $db = new \mysqli(
            $this->dbConfig['hostname'],
            $this->dbConfig['username'],
            $this->dbConfig['password'],
            $this->dbConfig['database'],
            $this->dbConfig['port']
        );

        if ($db->connect_error) {
            throw new \Exception('Error de conexión: ' . $db->connect_error);
        }

        $db->close();
    }

    /**
     * Verificar espacio disponible en disco
     */
    private function checkDiskSpace()
    {
        $freeBytes = disk_free_space($this->backupDir);
        $freeMB = $freeBytes / (1024 * 1024);

        CLI::write("💾 Espacio disponible: " . number_format($freeMB, 0) . " MB", 'cyan');

        if ($freeMB < 100) {
            CLI::write('⚠️  Advertencia: Poco espacio disponible en disco', 'yellow');
        }
    }

    /**
     * Crear backup completo de la base de datos
     */
    private function backupFullDatabase($structureOnly = false, $compress = false, $verify = false)
    {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "mda_full_backup_{$timestamp}.sql";
        $filepath = $this->backupDir . $filename;

        CLI::write('📊 Creando backup completo...', 'cyan');

        // Construir comando mysqldump
        $command = $this->buildMysqldumpCommand($filepath, [], $structureOnly);

        CLI::write('⏳ Ejecutando mysqldump...', 'yellow');
        
        $startTime = microtime(true);
        $output = shell_exec($command . ' 2>&1');
        $endTime = microtime(true);

        $duration = round($endTime - $startTime, 2);

        if (!file_exists($filepath) || filesize($filepath) == 0) {
            throw new \Exception('Backup falló. Output: ' . $output);
        }

        $fileSize = filesize($filepath);
        CLI::write("✅ Backup creado: " . basename($filepath), 'green');
        CLI::write("📏 Tamaño: " . $this->formatBytes($fileSize), 'white');
        CLI::write("⏱️  Tiempo: {$duration} segundos", 'white');

        // Comprimir si se solicita
        if ($compress) {
            $this->compressBackup($filepath);
        }

        // Verificar integridad si se solicita
        if ($verify) {
            $this->verifyBackup($filepath);
        }

        return $filepath;
    }

    /**
     * Crear backup de tablas específicas
     */
    private function backupSpecificTables($tables, $structureOnly = false, $compress = false, $verify = false)
    {
        $timestamp = date('Y-m-d_H-i-s');
        $tablesStr = implode('-', $tables);
        $filename = "mda_tables_{$tablesStr}_{$timestamp}.sql";
        $filepath = $this->backupDir . $filename;

        CLI::write('📋 Creando backup de tablas específicas: ' . implode(', ', $tables), 'cyan');

        // Verificar que las tablas existen
        $this->verifyTablesExist($tables);

        // Construir comando mysqldump
        $command = $this->buildMysqldumpCommand($filepath, $tables, $structureOnly);

        CLI::write('⏳ Ejecutando mysqldump...', 'yellow');
        
        $startTime = microtime(true);
        $output = shell_exec($command . ' 2>&1');
        $endTime = microtime(true);

        $duration = round($endTime - $startTime, 2);

        if (!file_exists($filepath) || filesize($filepath) == 0) {
            throw new \Exception('Backup falló. Output: ' . $output);
        }

        $fileSize = filesize($filepath);
        CLI::write("✅ Backup creado: " . basename($filepath), 'green');
        CLI::write("📏 Tamaño: " . $this->formatBytes($fileSize), 'white');
        CLI::write("⏱️  Tiempo: {$duration} segundos", 'white');

        // Comprimir si se solicita
        if ($compress) {
            $this->compressBackup($filepath);
        }

        // Verificar integridad si se solicita
        if ($verify) {
            $this->verifyBackup($filepath);
        }

        return $filepath;
    }

    /**
     * Construir comando mysqldump
     */
    private function buildMysqldumpCommand($outputFile, $tables = [], $structureOnly = false)
    {
        $password = escapeshellarg($this->dbConfig['password']);
        $database = escapeshellarg($this->dbConfig['database']);
        $outputFile = escapeshellarg($outputFile);

        $command = "mysqldump";
        $command .= " -h {$this->dbConfig['hostname']}";
        $command .= " -P {$this->dbConfig['port']}";
        $command .= " -u {$this->dbConfig['username']}";
        $command .= " -p{$password}";
        
        // Opciones adicionales
        $command .= " --single-transaction";
        $command .= " --routines";
        $command .= " --triggers";
        $command .= " --add-drop-table";
        $command .= " --create-options";
        
        if ($structureOnly) {
            $command .= " --no-data";
        }

        $command .= " " . $database;

        // Agregar tablas específicas si se especifican
        if (!empty($tables)) {
            foreach ($tables as $table) {
                $command .= " " . escapeshellarg(trim($table));
            }
        }

        $command .= " > " . $outputFile;

        return $command;
    }

    /**
     * Verificar que las tablas existen
     */
    private function verifyTablesExist($tables)
    {
        $db = new \mysqli(
            $this->dbConfig['hostname'],
            $this->dbConfig['username'],
            $this->dbConfig['password'],
            $this->dbConfig['database'],
            $this->dbConfig['port']
        );

        if ($db->connect_error) {
            throw new \Exception('Error de conexión: ' . $db->connect_error);
        }

        foreach ($tables as $table) {
            $table = trim($table);
            $result = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($table) . "'");
            
            if (!$result || $result->num_rows == 0) {
                $db->close();
                throw new \Exception("La tabla '$table' no existe en la base de datos");
            }
        }

        $db->close();
    }

    /**
     * Comprimir backup
     */
    private function compressBackup($filepath)
    {
        CLI::write('🗜️  Comprimiendo backup...', 'cyan');
        
        $compressedPath = $filepath . '.gz';
        $command = "gzip -9 " . escapeshellarg($filepath);
        
        $output = shell_exec($command . ' 2>&1');
        
        if (file_exists($compressedPath)) {
            $originalSize = filesize($filepath);
            $compressedSize = filesize($compressedPath);
            $ratio = round(($originalSize - $compressedSize) / $originalSize * 100, 1);
            
            CLI::write("✅ Backup comprimido: " . basename($compressedPath), 'green');
            CLI::write("📉 Reducción: {$ratio}%", 'white');
            
            // Eliminar archivo original
            unlink($filepath);
        } else {
            CLI::write("⚠️  Advertencia: No se pudo comprimir el backup", 'yellow');
        }
    }

    /**
     * Verificar integridad del backup
     */
    private function verifyBackup($filepath)
    {
        CLI::write('🔍 Verificando integridad del backup...', 'cyan');
        
        $content = file_get_contents($filepath);
        
        // Verificaciones básicas
        $checks = [
            'Tiene contenido' => !empty($content),
            'Contiene CREATE TABLE' => strpos($content, 'CREATE TABLE') !== false,
            'Contiene datos' => strpos($content, 'INSERT INTO') !== false,
            'Termina correctamente' => strpos($content, '-- Dump completed') !== false || strpos($content, 'SET SQL_MODE') !== false
        ];

        $passed = 0;
        $total = count($checks);

        foreach ($checks as $check => $result) {
            if ($result) {
                CLI::write("  ✅ $check", 'green');
                $passed++;
            } else {
                CLI::write("  ❌ $check", 'red');
            }
        }

        if ($passed === $total) {
            CLI::write("✅ Backup verificado correctamente ({$passed}/{$total})", 'green');
        } else {
            CLI::write("⚠️  Advertencia: Backup puede estar incompleto ({$passed}/{$total})", 'yellow');
        }
    }

    /**
     * Rotar backups antiguos
     */
    private function rotateBackups($keepCount)
    {
        CLI::write("🔄 Rotando backups (manteniendo últimos {$keepCount})...", 'cyan');
        
        $files = glob($this->backupDir . 'mda_*.sql*');
        
        if (empty($files)) {
            CLI::write('📁 No hay backups para rotar', 'white');
            return;
        }

        // Ordenar por fecha de modificación (más reciente primero)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $deleted = 0;
        for ($i = $keepCount; $i < count($files); $i++) {
            if (unlink($files[$i])) {
                $deleted++;
                CLI::write("  🗑️  Eliminado: " . basename($files[$i]), 'yellow');
            }
        }

        if ($deleted > 0) {
            CLI::write("✅ {$deleted} backup(s) antiguos eliminados", 'green');
        } else {
            CLI::write("📁 No hay backups antiguos para eliminar", 'white');
        }
    }

    /**
     * Formatear bytes a unidades legibles
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Mostrar información de ayuda
     */
    public function showHelp()
    {
        CLI::write('MDA Database Backup - Herramienta de Backup de BD', 'green');
        CLI::newLine();
        CLI::write('Uso:', 'yellow');
        CLI::write('  php spark db:backup [opciones]', 'white');
        CLI::newLine();
        CLI::write('Opciones:', 'yellow');
        CLI::write('  --full              Backup completo de toda la base de datos', 'white');
        CLI::write('  --tables=tabla1,tabla2  Backup de tablas específicas', 'white');
        CLI::write('  --structure-only    Solo estructura, sin datos', 'white');
        CLI::write('  --rotate=30         Número de backups a mantener', 'white');
        CLI::write('  --compress          Comprimir el backup con gzip', 'white');
        CLI::write('  --verify            Verificar integridad del backup', 'white');
        CLI::newLine();
        CLI::write('Ejemplos:', 'yellow');
        CLI::write('  php spark db:backup --full                    # Backup completo', 'white');
        CLI::write('  php spark db:backup --tables=sales_orders     # Tabla específica', 'white');
        CLI::write('  php spark db:backup --full --compress --verify # Completo + compresión + verificación', 'white');
        CLI::write('  php spark db:backup --structure-only          # Solo estructura', 'white');
        CLI::newLine();
        CLI::write('Notas:', 'yellow');
        CLI::write('  • Los backups se guardan en: writable/backups/', 'white');
        CLI::write('  • Rotación automática mantiene los últimos 30 backups por defecto', 'white');
        CLI::write('  • Requiere mysqldump instalado en el sistema', 'white');
    }
}