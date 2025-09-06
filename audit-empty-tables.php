<?php
/**
 * MDA Empty Tables Audit Tool
 * 
 * Script para auditar tablas vacías y determinar si pueden eliminarse de forma segura
 * Analiza referencias en código, foreign keys y uso en migraciones
 * 
 * Uso:
 * php audit-empty-tables.php
 * php audit-empty-tables.php --deep-scan
 * php audit-empty-tables.php --generate-cleanup
 * php audit-empty-tables.php --export-report
 * 
 * @author Claude AI
 * @date 2025-09-05
 */

class EmptyTablesAuditor
{
    private $db;
    private $emptyTables = [];
    private $codebaseReferences = [];
    private $foreignKeyReferences = [];
    private $migrationReferences = [];
    private $auditResults = [];
    
    public function __construct()
    {
        $this->connectToDatabase();
    }
    
    private function connectToDatabase()
    {
        $this->db = new mysqli('35.212.30.157', 'u9jvaasruh9vc', 'lalinha01?', 'dbuc0youbm7qp9', 3306);
        if ($this->db->connect_error) {
            die("❌ Error de conexión: " . $this->db->connect_error . "\n");
        }
        echo "✅ Conectado a la base de datos remota\n";
    }
    
    public function run($options = [])
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "           MDA EMPTY TABLES AUDIT TOOL - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $this->findEmptyTables();
        
        if (empty($this->emptyTables)) {
            echo "✅ No se encontraron tablas vacías.\n";
            return;
        }
        
        echo "📊 RESUMEN INICIAL\n";
        echo str_repeat("-", 40) . "\n";
        echo "Tablas vacías encontradas: " . count($this->emptyTables) . "\n\n";
        
        $this->analyzeTableUsage($options);
        $this->showAuditResults();
        
        if (in_array('--generate-cleanup', $options)) {
            $this->generateCleanupScript();
        }
        
        if (in_array('--export-report', $options)) {
            $this->exportReport();
        }
        
        $this->showRecommendations();
    }
    
    private function findEmptyTables()
    {
        echo "🔍 Buscando tablas vacías...\n";
        
        $result = $this->db->query('SHOW TABLES');
        while ($row = $result->fetch_array()) {
            $tableName = $row[0];
            $countResult = $this->db->query("SELECT COUNT(*) as count FROM " . $tableName);
            $count = $countResult->fetch_assoc()['count'];
            
            if ($count == 0) {
                $this->emptyTables[] = $tableName;
                echo "  ⚪ $tableName\n";
            }
        }
        echo "\n";
    }
    
    private function analyzeTableUsage($options = [])
    {
        $deepScan = in_array('--deep-scan', $options);
        
        echo "🔍 Analizando uso de tablas vacías...\n\n";
        
        foreach ($this->emptyTables as $table) {
            echo "▸ Analizando: $table\n";
            
            $analysis = [
                'table' => $table,
                'codebase_references' => $this->findCodebaseReferences($table, $deepScan),
                'foreign_key_references' => $this->findForeignKeyReferences($table),
                'migration_references' => $this->findMigrationReferences($table),
                'table_structure' => $this->getTableStructure($table),
                'safety_level' => 'unknown'
            ];
            
            $analysis['safety_level'] = $this->determineSafetyLevel($analysis);
            $this->auditResults[$table] = $analysis;
            
            echo "  Referencias en código: " . count($analysis['codebase_references']) . "\n";
            echo "  Foreign keys: " . count($analysis['foreign_key_references']) . "\n";
            echo "  En migraciones: " . count($analysis['migration_references']) . "\n";
            echo "  Nivel de seguridad: " . $this->getSafetyIcon($analysis['safety_level']) . "\n\n";
        }
    }
    
    private function findCodebaseReferences($tableName, $deepScan = false)
    {
        $references = [];
        $searchPaths = [
            __DIR__ . '/app/',
        ];
        
        if ($deepScan) {
            $searchPaths[] = __DIR__ . '/public/';
            $searchPaths[] = __DIR__ . '/writable/';
        }
        
        $searchPatterns = [
            $tableName,
            str_replace('_', '', $tableName), // sin underscores
            ucfirst(str_replace('_', '', $tableName)), // PascalCase
            str_replace('_', '-', $tableName), // con guiones
        ];
        
        foreach ($searchPaths as $path) {
            if (!is_dir($path)) continue;
            
            foreach ($searchPatterns as $pattern) {
                $command = "grep -r -l \"$pattern\" \"$path\" 2>/dev/null";
                $output = shell_exec($command);
                
                if ($output) {
                    $files = array_filter(explode("\n", trim($output)));
                    foreach ($files as $file) {
                        if (!in_array($file, $references)) {
                            $references[] = $file;
                        }
                    }
                }
            }
        }
        
        return $references;
    }
    
    private function findForeignKeyReferences($tableName)
    {
        $references = [];
        
        // Buscar FKs que referencian esta tabla
        $query = "
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME
        FROM
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            REFERENCED_TABLE_NAME = '$tableName'
            AND TABLE_SCHEMA = 'dbuc0youbm7qp9'
        ";
        
        $result = $this->db->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $references[] = [
                    'referencing_table' => $row['TABLE_NAME'],
                    'referencing_column' => $row['COLUMN_NAME'],
                    'constraint_name' => $row['CONSTRAINT_NAME']
                ];
            }
        }
        
        // Buscar FKs desde esta tabla hacia otras
        $query = "
        SELECT 
            COLUMN_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME,
            CONSTRAINT_NAME
        FROM
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            TABLE_NAME = '$tableName'
            AND REFERENCED_TABLE_NAME IS NOT NULL
            AND TABLE_SCHEMA = 'dbuc0youbm7qp9'
        ";
        
        $result = $this->db->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $references[] = [
                    'column' => $row['COLUMN_NAME'],
                    'referenced_table' => $row['REFERENCED_TABLE_NAME'],
                    'referenced_column' => $row['REFERENCED_COLUMN_NAME'],
                    'constraint_name' => $row['CONSTRAINT_NAME'],
                    'type' => 'outgoing'
                ];
            }
        }
        
        return $references;
    }
    
    private function findMigrationReferences($tableName)
    {
        $references = [];
        $migrationsPath = __DIR__ . '/app/Database/Migrations/';
        
        if (!is_dir($migrationsPath)) {
            return $references;
        }
        
        $migrationFiles = glob($migrationsPath . '*.php');
        
        foreach ($migrationFiles as $file) {
            $content = file_get_contents($file);
            
            // Buscar referencias a la tabla
            $patterns = [
                "/['\"]$tableName['\"]/",
                "/\\b$tableName\\b/",
                "/forge->createTable\\(['\"]$tableName['\"]/",
                "/forge->dropTable\\(['\"]$tableName['\"]/",
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content, $matches)) {
                    $references[] = [
                        'file' => basename($file),
                        'full_path' => $file,
                        'match' => $matches[0] ?? $pattern
                    ];
                    break; // Solo una referencia por archivo
                }
            }
        }
        
        return $references;
    }
    
    private function getTableStructure($tableName)
    {
        $structure = [];
        
        $result = $this->db->query("DESCRIBE $tableName");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $structure[] = $row;
            }
        }
        
        return $structure;
    }
    
    private function determineSafetyLevel($analysis)
    {
        $codeReferences = count($analysis['codebase_references']);
        $fkReferences = count($analysis['foreign_key_references']);
        $migrationReferences = count($analysis['migration_references']);
        $tableName = $analysis['table'];
        
        // Tablas del sistema críticas
        $criticalSystemTables = [
            'auth_permissions_users',
            'auth_remember_tokens',
            'auth_token_logins',
            'audit_trail',
            'migrations'
        ];
        
        // Tablas de funcionalidades específicas
        $featureTables = [
            'chat_',
            'todo',
            'integration_settings'
        ];
        
        if (in_array($tableName, $criticalSystemTables)) {
            return 'keep'; // Mantener siempre
        }
        
        if ($fkReferences > 0) {
            return 'keep'; // Tiene relaciones activas
        }
        
        if ($codeReferences > 0) {
            return 'review'; // Necesita revisión
        }
        
        if ($migrationReferences > 0) {
            return 'review'; // Podría necesitarse en futuro
        }
        
        // Verificar si es parte de una funcionalidad
        foreach ($featureTables as $prefix) {
            if (strpos($tableName, $prefix) === 0) {
                return 'safe'; // Probablemente se puede eliminar
            }
        }
        
        return 'safe'; // Sin referencias, probablemente se puede eliminar
    }
    
    private function getSafetyIcon($safetyLevel)
    {
        switch ($safetyLevel) {
            case 'keep': return '🔒 MANTENER';
            case 'review': return '⚠️  REVISAR';
            case 'safe': return '✅ ELIMINAR SEGURO';
            default: return '❓ DESCONOCIDO';
        }
    }
    
    private function showAuditResults()
    {
        echo "📋 RESULTADOS DE AUDITORÍA\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $safetyGroups = [
            'keep' => [],
            'review' => [],
            'safe' => [],
            'unknown' => []
        ];
        
        foreach ($this->auditResults as $table => $analysis) {
            $safetyGroups[$analysis['safety_level']][] = $analysis;
        }
        
        foreach ($safetyGroups as $level => $tables) {
            if (empty($tables)) continue;
            
            $icon = $this->getSafetyIcon($level);
            echo "$icon (" . count($tables) . " tablas)\n";
            echo str_repeat("-", 60) . "\n";
            
            foreach ($tables as $analysis) {
                echo "▸ {$analysis['table']}\n";
                echo "  Columnas: " . count($analysis['table_structure']) . "\n";
                echo "  Referencias código: " . count($analysis['codebase_references']) . "\n";
                echo "  Foreign keys: " . count($analysis['foreign_key_references']) . "\n";
                echo "  En migraciones: " . count($analysis['migration_references']) . "\n";
                
                // Mostrar detalles si hay referencias
                if (!empty($analysis['codebase_references'])) {
                    echo "  📄 Archivos que la referencian:\n";
                    foreach (array_slice($analysis['codebase_references'], 0, 3) as $file) {
                        echo "     • " . basename($file) . "\n";
                    }
                    if (count($analysis['codebase_references']) > 3) {
                        $more = count($analysis['codebase_references']) - 3;
                        echo "     ... y $more más\n";
                    }
                }
                
                if (!empty($analysis['foreign_key_references'])) {
                    echo "  🔗 Relaciones activas:\n";
                    foreach ($analysis['foreign_key_references'] as $fk) {
                        if (isset($fk['referencing_table'])) {
                            echo "     • {$fk['referencing_table']}.{$fk['referencing_column']}\n";
                        } else {
                            echo "     • {$fk['column']} -> {$fk['referenced_table']}\n";
                        }
                    }
                }
                
                echo "\n";
            }
            echo "\n";
        }
    }
    
    private function generateCleanupScript()
    {
        $scriptFile = 'cleanup-empty-tables-' . date('Y-m-d-H-i-s') . '.sql';
        $script = "-- MDA Empty Tables Cleanup Script\n";
        $script .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $script .= "-- IMPORTANT: Review each DROP statement carefully before execution\n\n";
        
        $safeTables = [];
        foreach ($this->auditResults as $table => $analysis) {
            if ($analysis['safety_level'] === 'safe') {
                $safeTables[] = $table;
            }
        }
        
        if (!empty($safeTables)) {
            $script .= "-- SAFE TO DROP (No references found)\n";
            $script .= "-- Total: " . count($safeTables) . " tables\n\n";
            
            foreach ($safeTables as $table) {
                $script .= "-- DROP TABLE IF EXISTS `$table`;\n";
            }
            
            $script .= "\n-- UNCOMMENT THE LINES ABOVE AFTER MANUAL VERIFICATION\n\n";
        }
        
        // Agregar tablas que requieren revisión
        $reviewTables = [];
        foreach ($this->auditResults as $table => $analysis) {
            if ($analysis['safety_level'] === 'review') {
                $reviewTables[] = $table;
            }
        }
        
        if (!empty($reviewTables)) {
            $script .= "-- REQUIRES MANUAL REVIEW BEFORE DROP\n";
            $script .= "-- Total: " . count($reviewTables) . " tables\n\n";
            
            foreach ($reviewTables as $table) {
                $analysis = $this->auditResults[$table];
                $script .= "-- TABLE: $table\n";
                $script .= "-- Code references: " . count($analysis['codebase_references']) . "\n";
                $script .= "-- Migration references: " . count($analysis['migration_references']) . "\n";
                $script .= "-- Review manually: DROP TABLE IF EXISTS `$table`;\n\n";
            }
        }
        
        file_put_contents($scriptFile, $script);
        echo "🗑️  Script de limpieza generado: $scriptFile\n\n";
    }
    
    private function exportReport()
    {
        $reportFile = 'empty-tables-audit-' . date('Y-m-d-H-i-s') . '.json';
        
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'total_empty_tables' => count($this->emptyTables),
            'safety_summary' => [
                'keep' => 0,
                'review' => 0,
                'safe' => 0,
                'unknown' => 0
            ],
            'audit_results' => $this->auditResults
        ];
        
        // Contar por niveles de seguridad
        foreach ($this->auditResults as $analysis) {
            $report['safety_summary'][$analysis['safety_level']]++;
        }
        
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        echo "📊 Reporte detallado exportado a: $reportFile\n\n";
    }
    
    private function showRecommendations()
    {
        echo "💡 RECOMENDACIONES DE ACCIÓN\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $counts = [
            'keep' => 0,
            'review' => 0,
            'safe' => 0,
            'unknown' => 0
        ];
        
        foreach ($this->auditResults as $analysis) {
            $counts[$analysis['safety_level']]++;
        }
        
        if ($counts['safe'] > 0) {
            echo "✅ ACCIÓN INMEDIATA ({$counts['safe']} tablas)\n";
            echo "   • Pueden eliminarse de forma segura\n";
            echo "   • No tienen referencias activas en el código\n";
            echo "   • Ejecutar script de limpieza generado\n\n";
        }
        
        if ($counts['review'] > 0) {
            echo "⚠️  REQUIERE REVISIÓN ({$counts['review']} tablas)\n";
            echo "   • Tienen referencias en código o migraciones\n";
            echo "   • Revisar cada referencia manualmente\n";
            echo "   • Evaluar si las referencias son críticas\n";
            echo "   • Considerar refactoring antes de eliminar\n\n";
        }
        
        if ($counts['keep'] > 0) {
            echo "🔒 MANTENER ({$counts['keep']} tablas)\n";
            echo "   • Tablas críticas del sistema\n";
            echo "   • Tienen foreign keys activos\n";
            echo "   • NO eliminar bajo ninguna circunstancia\n\n";
        }
        
        echo "📋 PROCESO RECOMENDADO:\n";
        echo "1. 💾 Hacer backup completo de la base de datos\n";
        echo "2. 🧪 Probar eliminaciones en ambiente de desarrollo\n";
        echo "3. ✅ Eliminar primero las tablas 'safe'\n";
        echo "4. 🔍 Revisar manualmente las tablas 'review'\n";
        echo "5. 📊 Verificar integridad después de cada eliminación\n";
        echo "6. 📝 Documentar cambios realizados\n\n";
        
        echo "⚠️  PRECAUCIONES:\n";
        echo "   • NUNCA eliminar durante horas de producción\n";
        echo "   • Verificar que no hay procesos usando las tablas\n";
        echo "   • Tener plan de rollback preparado\n";
        echo "   • Comunicar cambios al equipo\n\n";
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
    $auditor = new EmptyTablesAuditor();
    
    $args = $argv ?? [];
    
    if (count($args) === 1) {
        echo "MDA Empty Tables Audit Tool\n\n";
        echo "Opciones disponibles:\n";
        echo "  --deep-scan         : Escaneo profundo del código fuente\n";
        echo "  --generate-cleanup  : Generar script SQL de limpieza\n";
        echo "  --export-report     : Exportar reporte detallado a JSON\n\n";
        echo "Ejemplos:\n";
        echo "  php audit-empty-tables.php\n";
        echo "  php audit-empty-tables.php --deep-scan\n";
        echo "  php audit-empty-tables.php --generate-cleanup --export-report\n\n";
        
        $auditor->run();
    } else {
        $auditor->run(array_slice($args, 1));
    }
}