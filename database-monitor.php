<?php
/**
 * MDA Database Monitor
 * 
 * Script para monitorear la estructura y estado de la base de datos remota
 * Detecta cambios, problemas y genera reportes automatizados
 * 
 * Uso:
 * php database-monitor.php
 * php database-monitor.php --report
 * php database-monitor.php --check-migrations
 * php database-monitor.php --update-docs
 * 
 * @author Claude AI
 * @date 2025-09-05
 */

class DatabaseMonitor
{
    private $db;
    private $config;
    
    public function __construct()
    {
        $this->config = [
            'hostname' => '35.212.30.157',
            'database' => 'dbuc0youbm7qp9',
            'username' => 'u9jvaasruh9vc',
            'password' => 'lalinha01?',
            'port' => 3306
        ];
        
        $this->connect();
    }
    
    private function connect()
    {
        $this->db = new mysqli(
            $this->config['hostname'],
            $this->config['username'], 
            $this->config['password'],
            $this->config['database'],
            $this->config['port']
        );
        
        if ($this->db->connect_error) {
            die("❌ Error de conexión: " . $this->db->connect_error . "\n");
        }
        
        echo "✅ Conectado a la base de datos remota\n";
    }
    
    public function generateFullReport()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "         REPORTE COMPLETO DE BASE DE DATOS MDA - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $this->showConnectionInfo();
        $this->showGeneralStats();
        $this->showModuleBreakdown();
        $this->checkDuplicatedTables();
        $this->checkEmptyTables();
        $this->checkMigrationStatus();
        $this->showTopTables();
        $this->checkForeignKeys();
        $this->generateRecommendations();
        
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "                    REPORTE COMPLETADO\n";
        echo str_repeat("=", 80) . "\n";
    }
    
    private function showConnectionInfo()
    {
        echo "🔌 INFORMACIÓN DE CONEXIÓN\n";
        echo str_repeat("-", 40) . "\n";
        echo "Servidor: {$this->config['hostname']}:{$this->config['port']}\n";
        echo "Base de datos: {$this->config['database']}\n";
        echo "Usuario: {$this->config['username']}\n";
        echo "Estado: ACTIVA\n\n";
    }
    
    private function showGeneralStats()
    {
        // Contar tablas
        $result = $this->db->query("SHOW TABLES");
        $tableCount = $result->num_rows;
        
        // Contar registros totales
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tableName = $row[0];
            $countResult = $this->db->query("SELECT COUNT(*) as count FROM " . $tableName);
            $count = $countResult->fetch_assoc()['count'];
            $tables[$tableName] = $count;
        }
        $totalRecords = array_sum($tables);
        
        echo "📊 ESTADÍSTICAS GENERALES\n";
        echo str_repeat("-", 40) . "\n";
        echo "Total de tablas: $tableCount\n";
        echo "Total de registros: $totalRecords\n";
        echo "Promedio por tabla: " . round($totalRecords / $tableCount, 2) . "\n\n";
        
        return $tables;
    }
    
    private function showModuleBreakdown()
    {
        $tables = $this->getAllTables();
        $modules = $this->groupTablesByModule($tables);
        
        echo "📦 ESTRUCTURA POR MÓDULOS\n";
        echo str_repeat("-", 40) . "\n";
        
        foreach ($modules as $module => $moduleTables) {
            if (count($moduleTables) > 0) {
                $moduleTotal = array_sum($moduleTables);
                echo "▸ " . strtoupper(str_replace('_', ' ', $module)) . "\n";
                echo "  " . count($moduleTables) . " tablas | $moduleTotal registros\n";
                
                // Top 3 tablas más grandes del módulo
                arsort($moduleTables);
                $top3 = array_slice($moduleTables, 0, 3, true);
                foreach ($top3 as $table => $count) {
                    echo "    • $table: $count registros\n";
                }
                echo "\n";
            }
        }
    }
    
    private function checkDuplicatedTables()
    {
        $tables = array_keys($this->getAllTables());
        $potentialDuplicates = [];
        
        // Buscar patrones similares
        $patterns = [
            'sales_orders_comments' => 'sales_order_comments',
            'service_orders_notes' => 'service_order_notes',
            'service_orders_services_history' => 'service_order_services_history'
        ];
        
        echo "⚠️  VERIFICACIÓN DE TABLAS DUPLICADAS\n";
        echo str_repeat("-", 40) . "\n";
        
        $found = false;
        foreach ($patterns as $table1 => $table2) {
            if (in_array($table1, $tables) && in_array($table2, $tables)) {
                $count1 = $this->getTableCount($table1);
                $count2 = $this->getTableCount($table2);
                
                echo "🔍 Posible duplicado detectado:\n";
                echo "   - $table1 ($count1 registros)\n";
                echo "   - $table2 ($count2 registros)\n";
                
                if ($count1 == 0 || $count2 == 0) {
                    $emptyTable = ($count1 == 0) ? $table1 : $table2;
                    echo "   💡 Recomendación: Revisar si '$emptyTable' puede eliminarse\n";
                }
                echo "\n";
                $found = true;
            }
        }
        
        if (!$found) {
            echo "✅ No se encontraron duplicados evidentes\n\n";
        }
    }
    
    private function checkEmptyTables()
    {
        $tables = $this->getAllTables();
        $emptyTables = array_filter($tables, function($count) {
            return $count == 0;
        });
        
        echo "📋 TABLAS VACÍAS\n";
        echo str_repeat("-", 40) . "\n";
        
        if (count($emptyTables) > 0) {
            echo "⚠️  Se encontraron " . count($emptyTables) . " tablas vacías:\n";
            foreach ($emptyTables as $table => $count) {
                echo "   ⚪ $table\n";
            }
            echo "\n💡 Revisar si estas tablas son necesarias o pueden eliminarse\n\n";
        } else {
            echo "✅ No hay tablas vacías\n\n";
        }
    }
    
    private function checkMigrationStatus()
    {
        echo "🔄 ESTADO DE MIGRACIONES\n";
        echo str_repeat("-", 40) . "\n";
        
        // Ejecutar comando spark
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
            
            echo "✅ Migraciones ejecutadas: $executedCount\n";
            echo "⚠️  Migraciones pendientes: $pendingCount\n";
            
            if ($pendingCount > 0) {
                echo "\n🚨 ATENCIÓN: Hay migraciones pendientes que requieren revisión manual\n";
                echo "   Comando: php spark migrate:status\n";
            }
        } else {
            echo "❌ No se pudo obtener el estado de migraciones\n";
        }
        echo "\n";
    }
    
    private function showTopTables()
    {
        $tables = $this->getAllTables();
        arsort($tables);
        
        echo "📈 TOP 10 TABLAS CON MÁS DATOS\n";
        echo str_repeat("-", 40) . "\n";
        
        $count = 0;
        foreach ($tables as $table => $records) {
            if ($records > 0 && $count < 10) {
                $count++;
                echo sprintf("%2d. %-30s: %s registros\n", $count, $table, number_format($records));
            }
        }
        echo "\n";
    }
    
    private function checkForeignKeys()
    {
        $query = "
        SELECT 
            COUNT(*) as fk_count
        FROM
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            REFERENCED_TABLE_NAME IS NOT NULL
            AND TABLE_SCHEMA = '{$this->config['database']}'
        ";
        
        $result = $this->db->query($query);
        $fkCount = $result->fetch_assoc()['fk_count'];
        
        echo "🔗 INTEGRIDAD REFERENCIAL\n";
        echo str_repeat("-", 40) . "\n";
        echo "Foreign Keys definidas: $fkCount\n";
        
        if ($fkCount < 20) {
            echo "⚠️  Pocas foreign keys detectadas. Revisar integridad referencial.\n";
        } else {
            echo "✅ Buena cobertura de foreign keys\n";
        }
        echo "\n";
    }
    
    private function generateRecommendations()
    {
        echo "💡 RECOMENDACIONES\n";
        echo str_repeat("-", 40) . "\n";
        
        $tables = $this->getAllTables();
        $emptyCount = count(array_filter($tables, function($count) { return $count == 0; }));
        $totalRecords = array_sum($tables);
        
        if ($emptyCount > 20) {
            echo "1. ⚠️  Muchas tablas vacías ($emptyCount). Considerar limpieza.\n";
        }
        
        if ($totalRecords > 10000) {
            echo "2. 📊 Base de datos grande ($totalRecords registros). Monitorear performance.\n";
        }
        
        echo "3. 🔄 Ejecutar este reporte mensualmente\n";
        echo "4. 📝 Actualizar CLAUDE.md después de cambios estructurales\n";
        echo "5. 💾 Verificar que los backups estén funcionando\n";
        echo "\n";
    }
    
    private function getAllTables()
    {
        $tables = [];
        $result = $this->db->query('SHOW TABLES');
        
        while ($row = $result->fetch_array()) {
            $tableName = $row[0];
            $countResult = $this->db->query("SELECT COUNT(*) as count FROM " . $tableName);
            $count = $countResult->fetch_assoc()['count'];
            $tables[$tableName] = $count;
        }
        
        return $tables;
    }
    
    private function groupTablesByModule($tables)
    {
        $modules = [
            'auth' => [],
            'sales_orders' => [],
            'service_orders' => [],
            'car_wash' => [],
            'recon' => [],
            'get_ready' => [],
            'public_pages' => [],
            'vehicle' => [],
            'chat' => [],
            'system' => [],
            'other' => []
        ];
        
        foreach ($tables as $table => $count) {
            if (strpos($table, 'auth_') === 0) {
                $modules['auth'][$table] = $count;
            } elseif (strpos($table, 'sales_order') === 0) {
                $modules['sales_orders'][$table] = $count;
            } elseif (strpos($table, 'service_order') === 0) {
                $modules['service_orders'][$table] = $count;
            } elseif (strpos($table, 'car_wash') === 0) {
                $modules['car_wash'][$table] = $count;
            } elseif (strpos($table, 'recon') === 0) {
                $modules['recon'][$table] = $count;
            } elseif (strpos($table, 'get_ready') === 0) {
                $modules['get_ready'][$table] = $count;
            } elseif (strpos($table, 'public_page') === 0) {
                $modules['public_pages'][$table] = $count;
            } elseif (strpos($table, 'vehicle') === 0 || $table === 'parking_spots') {
                $modules['vehicle'][$table] = $count;
            } elseif (strpos($table, 'chat') === 0 || $table === 'sms_conversations') {
                $modules['chat'][$table] = $count;
            } elseif (in_array($table, ['users', 'clients', 'contacts', 'settings', 'migrations', 'todos', 
                                        'todo_notifications', 'audit_trail', 'integration_settings', 
                                        'internal_notes', 'note_mentions', 'custom_roles', 
                                        'contact_groups', 'contact_group_permissions', 
                                        'contact_permissions', 'user_contact_groups'])) {
                $modules['system'][$table] = $count;
            } else {
                $modules['other'][$table] = $count;
            }
        }
        
        return $modules;
    }
    
    private function getTableCount($tableName)
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM " . $tableName);
        return $result->fetch_assoc()['count'];
    }
    
    public function quickCheck()
    {
        echo "🔍 VERIFICACIÓN RÁPIDA DE BD\n";
        echo str_repeat("-", 30) . "\n";
        
        $tables = $this->getAllTables();
        $totalTables = count($tables);
        $totalRecords = array_sum($tables);
        $emptyTables = count(array_filter($tables, function($count) { return $count == 0; }));
        
        echo "Tablas: $totalTables\n";
        echo "Registros: " . number_format($totalRecords) . "\n";
        echo "Tablas vacías: $emptyTables\n";
        
        if ($emptyTables > 20) {
            echo "⚠️  Muchas tablas vacías detectadas\n";
        }
        
        echo "Estado: " . ($totalTables > 70 ? "✅ OK" : "⚠️  Revisar") . "\n";
    }
    
    public function updateClaudeDoc()
    {
        echo "📝 Actualizando documentación CLAUDE.md...\n";
        
        $tables = $this->getAllTables();
        $totalTables = count($tables);
        $totalRecords = array_sum($tables);
        
        // Leer el archivo actual
        $claudeFile = __DIR__ . '/CLAUDE.md';
        if (!file_exists($claudeFile)) {
            echo "❌ Archivo CLAUDE.md no encontrado\n";
            return;
        }
        
        $content = file_get_contents($claudeFile);
        
        // Actualizar estadísticas
        $content = preg_replace(
            '/- \*\*Total de Tablas\*\*: \d+ tablas activas/',
            "- **Total de Tablas**: $totalTables tablas activas",
            $content
        );
        
        $content = preg_replace(
            '/- \*\*Total de Registros\*\*: [\d,]+ registros/',
            "- **Total de Registros**: " . number_format($totalRecords) . " registros",
            $content
        );
        
        $content = preg_replace(
            '/- \*\*Última actualización\*\*: \d{4}-\d{2}-\d{2}/',
            "- **Última actualización**: " . date('Y-m-d'),
            $content
        );
        
        file_put_contents($claudeFile, $content);
        echo "✅ CLAUDE.md actualizado con estadísticas actuales\n";
    }
    
    public function __destruct()
    {
        if ($this->db) {
            $this->db->close();
        }
    }
}

// Ejecución del script
if (php_sapi_name() === 'cli') {
    $monitor = new DatabaseMonitor();
    
    $args = $argv ?? [];
    
    if (in_array('--report', $args)) {
        $monitor->generateFullReport();
    } elseif (in_array('--check-migrations', $args)) {
        $monitor->checkMigrationStatus();
    } elseif (in_array('--update-docs', $args)) {
        $monitor->updateClaudeDoc();
    } elseif (in_array('--quick', $args)) {
        $monitor->quickCheck();
    } else {
        echo "MDA Database Monitor - Opciones disponibles:\n";
        echo "  --report          : Generar reporte completo\n";
        echo "  --quick           : Verificación rápida\n";
        echo "  --check-migrations: Revisar estado de migraciones\n";
        echo "  --update-docs     : Actualizar CLAUDE.md\n";
        echo "\nEjemplo: php database-monitor.php --report\n";
    }
}