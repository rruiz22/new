<?php
/**
 * MDA Duplicate Tables Resolver
 * 
 * Script para investigar y resolver tablas duplicadas en la base de datos
 * Identifica cual tabla usar y genera scripts de limpieza seguros
 * 
 * Uso:
 * php resolve-duplicate-tables.php
 * php resolve-duplicate-tables.php --analyze
 * php resolve-duplicate-tables.php --generate-fix
 * php resolve-duplicate-tables.php --execute-fix
 * 
 * @author Claude AI
 * @date 2025-09-05
 */

class DuplicateTablesResolver
{
    private $db;
    private $duplicatedTables = [
        // Tabla principal vs tabla duplicada
        'sales_orders_comments' => 'sales_order_comments',
        'service_orders_notes' => 'service_order_notes'
    ];
    private $analysisResults = [];
    
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
        echo "         MDA DUPLICATE TABLES RESOLVER - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $this->analyzeDuplicates();
        
        if (in_array('--analyze', $options)) {
            $this->showDetailedAnalysis();
        } else {
            $this->showSummaryAnalysis();
        }
        
        if (in_array('--generate-fix', $options)) {
            $this->generateFixScript();
        }
        
        if (in_array('--execute-fix', $options)) {
            $this->executeFix();
        } else {
            $this->showRecommendations();
        }
    }
    
    private function analyzeDuplicates()
    {
        echo "🔍 Analizando tablas duplicadas identificadas...\n\n";
        
        foreach ($this->duplicatedTables as $table1 => $table2) {
            echo "▸ Analizando par: $table1 vs $table2\n";
            
            $analysis = [
                'table1' => $table1,
                'table2' => $table2,
                'table1_exists' => $this->tableExists($table1),
                'table2_exists' => $this->tableExists($table2),
                'table1_count' => 0,
                'table2_count' => 0,
                'table1_structure' => [],
                'table2_structure' => [],
                'structures_match' => false,
                'code_references' => [],
                'migration_references' => [],
                'recommendation' => 'unknown'
            ];
            
            if ($analysis['table1_exists']) {
                $analysis['table1_count'] = $this->getTableCount($table1);
                $analysis['table1_structure'] = $this->getTableStructure($table1);
            }
            
            if ($analysis['table2_exists']) {
                $analysis['table2_count'] = $this->getTableCount($table2);
                $analysis['table2_structure'] = $this->getTableStructure($table2);
            }
            
            $analysis['structures_match'] = $this->compareStructures(
                $analysis['table1_structure'], 
                $analysis['table2_structure']
            );
            
            $analysis['code_references'] = $this->findCodeReferences([$table1, $table2]);
            $analysis['migration_references'] = $this->findMigrationReferences([$table1, $table2]);
            $analysis['recommendation'] = $this->generateRecommendation($analysis);
            
            $this->analysisResults[] = $analysis;
            
            echo "  {$table1}: " . ($analysis['table1_exists'] ? "{$analysis['table1_count']} registros" : "NO EXISTE") . "\n";
            echo "  {$table2}: " . ($analysis['table2_exists'] ? "{$analysis['table2_count']} registros" : "NO EXISTE") . "\n";
            echo "  Estructuras coinciden: " . ($analysis['structures_match'] ? "✅ SÍ" : "❌ NO") . "\n";
            echo "  Recomendación: " . $this->getRecommendationIcon($analysis['recommendation']) . "\n\n";
        }
    }
    
    private function tableExists($tableName)
    {
        $result = $this->db->query("SHOW TABLES LIKE '$tableName'");
        return $result && $result->num_rows > 0;
    }
    
    private function getTableCount($tableName)
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM $tableName");
        return $result ? $result->fetch_assoc()['count'] : 0;
    }
    
    private function getTableStructure($tableName)
    {
        $structure = [];
        $result = $this->db->query("DESCRIBE $tableName");
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $structure[] = [
                    'field' => $row['Field'],
                    'type' => $row['Type'],
                    'null' => $row['Null'],
                    'key' => $row['Key'],
                    'default' => $row['Default'],
                    'extra' => $row['Extra']
                ];
            }
        }
        
        return $structure;
    }
    
    private function compareStructures($structure1, $structure2)
    {
        if (count($structure1) !== count($structure2)) {
            return false;
        }
        
        // Comparar campo por campo
        for ($i = 0; $i < count($structure1); $i++) {
            if (!isset($structure2[$i])) return false;
            
            $field1 = $structure1[$i];
            $field2 = $structure2[$i];
            
            if ($field1['field'] !== $field2['field'] ||
                $field1['type'] !== $field2['type'] ||
                $field1['null'] !== $field2['null'] ||
                $field1['key'] !== $field2['key']) {
                return false;
            }
        }
        
        return true;
    }
    
    private function findCodeReferences($tables)
    {
        $references = [];
        $searchPath = __DIR__ . '/app/';
        
        foreach ($tables as $table) {
            // Buscar referencias en el código
            $command = "grep -r -l \"$table\" \"$searchPath\" 2>/dev/null";
            $output = shell_exec($command);
            
            if ($output) {
                $files = array_filter(explode("\n", trim($output)));
                $references[$table] = $files;
            } else {
                $references[$table] = [];
            }
        }
        
        return $references;
    }
    
    private function findMigrationReferences($tables)
    {
        $references = [];
        $migrationsPath = __DIR__ . '/app/Database/Migrations/';
        
        foreach ($tables as $table) {
            $references[$table] = [];
            
            if (!is_dir($migrationsPath)) continue;
            
            $migrationFiles = glob($migrationsPath . '*.php');
            
            foreach ($migrationFiles as $file) {
                $content = file_get_contents($file);
                
                if (strpos($content, $table) !== false) {
                    $references[$table][] = basename($file);
                }
            }
        }
        
        return $references;
    }
    
    private function generateRecommendation($analysis)
    {
        $table1 = $analysis['table1'];
        $table2 = $analysis['table2'];
        
        // Si una no existe, mantener la que existe
        if (!$analysis['table1_exists'] && $analysis['table2_exists']) {
            return 'keep_table2';
        }
        if ($analysis['table1_exists'] && !$analysis['table2_exists']) {
            return 'keep_table1';
        }
        
        // Si ninguna existe, no hay problema
        if (!$analysis['table1_exists'] && !$analysis['table2_exists']) {
            return 'no_action';
        }
        
        // Si ambas existen, evaluar cual mantener
        $table1_refs = count($analysis['code_references'][$table1] ?? []);
        $table2_refs = count($analysis['code_references'][$table2] ?? []);
        $table1_data = $analysis['table1_count'];
        $table2_data = $analysis['table2_count'];
        
        // Priorizar la que tiene datos
        if ($table1_data > 0 && $table2_data == 0) {
            return 'keep_table1_drop_table2';
        }
        if ($table2_data > 0 && $table1_data == 0) {
            return 'keep_table2_drop_table1';
        }
        
        // Si ambas tienen datos, priorizar la que más referencias tiene en código
        if ($table1_refs > $table2_refs) {
            return 'keep_table1_merge_table2';
        }
        if ($table2_refs > $table1_refs) {
            return 'keep_table2_merge_table1';
        }
        
        // Por convención, mantener la que sigue el patrón más común del sistema
        // En MDA: sales_orders_comments es más común que sales_order_comments
        if (strpos($table1, '_orders_') !== false) {
            return 'keep_table1_drop_table2';
        }
        if (strpos($table2, '_orders_') !== false) {
            return 'keep_table2_drop_table1';
        }
        
        return 'manual_review';
    }
    
    private function getRecommendationIcon($recommendation)
    {
        switch ($recommendation) {
            case 'keep_table1': 
            case 'keep_table1_drop_table2': 
            case 'keep_table1_merge_table2':
                return '✅ MANTENER TABLA 1';
            case 'keep_table2':
            case 'keep_table2_drop_table1':
            case 'keep_table2_merge_table1':
                return '✅ MANTENER TABLA 2';
            case 'no_action':
                return '🚫 SIN ACCIÓN';
            case 'manual_review':
                return '⚠️  REVISIÓN MANUAL';
            default:
                return '❓ DESCONOCIDO';
        }
    }
    
    private function showSummaryAnalysis()
    {
        echo "📊 RESUMEN DE ANÁLISIS\n";
        echo str_repeat("-", 60) . "\n\n";
        
        foreach ($this->analysisResults as $analysis) {
            $table1 = $analysis['table1'];
            $table2 = $analysis['table2'];
            
            echo "▸ PAR: $table1 vs $table2\n";
            echo "  Estado: ";
            
            if (!$analysis['table1_exists'] && !$analysis['table2_exists']) {
                echo "Ninguna existe - Sin problema\n";
            } elseif (!$analysis['table1_exists']) {
                echo "Solo existe $table2\n";
            } elseif (!$analysis['table2_exists']) {
                echo "Solo existe $table1\n";
            } else {
                echo "Ambas existen - DUPLICADO REAL\n";
                echo "  Datos: $table1 ({$analysis['table1_count']}) vs $table2 ({$analysis['table2_count']})\n";
                echo "  Estructuras: " . ($analysis['structures_match'] ? "Idénticas" : "Diferentes") . "\n";
            }
            
            echo "  Acción: " . $this->getRecommendationIcon($analysis['recommendation']) . "\n\n";
        }
    }
    
    private function showDetailedAnalysis()
    {
        echo "📋 ANÁLISIS DETALLADO\n";
        echo str_repeat("=", 80) . "\n\n";
        
        foreach ($this->analysisResults as $analysis) {
            $table1 = $analysis['table1'];
            $table2 = $analysis['table2'];
            
            echo "🔍 PAR: $table1 vs $table2\n";
            echo str_repeat("-", 50) . "\n";
            
            // Estado de las tablas
            echo "📊 ESTADO:\n";
            echo "  • $table1: " . ($analysis['table1_exists'] ? 
                "Existe ({$analysis['table1_count']} registros)" : "NO EXISTE") . "\n";
            echo "  • $table2: " . ($analysis['table2_exists'] ? 
                "Existe ({$analysis['table2_count']} registros)" : "NO EXISTE") . "\n";
            
            // Comparación de estructuras
            if ($analysis['table1_exists'] && $analysis['table2_exists']) {
                echo "\n🏗️  ESTRUCTURA:\n";
                echo "  • Columnas: {$table1} (" . count($analysis['table1_structure']) . 
                     ") vs {$table2} (" . count($analysis['table2_structure']) . ")\n";
                echo "  • Coinciden: " . ($analysis['structures_match'] ? "✅ SÍ" : "❌ NO") . "\n";
                
                if (!$analysis['structures_match']) {
                    echo "  • Diferencias detectadas en estructura\n";
                }
            }
            
            // Referencias en código
            echo "\n📄 REFERENCIAS EN CÓDIGO:\n";
            foreach ([$table1, $table2] as $table) {
                $refs = $analysis['code_references'][$table] ?? [];
                echo "  • $table: " . count($refs) . " referencias\n";
                if (!empty($refs)) {
                    foreach (array_slice($refs, 0, 3) as $file) {
                        echo "    - " . basename($file) . "\n";
                    }
                    if (count($refs) > 3) {
                        echo "    ... y " . (count($refs) - 3) . " más\n";
                    }
                }
            }
            
            // Referencias en migraciones
            echo "\n🔄 REFERENCIAS EN MIGRACIONES:\n";
            foreach ([$table1, $table2] as $table) {
                $refs = $analysis['migration_references'][$table] ?? [];
                echo "  • $table: " . count($refs) . " migraciones\n";
                foreach ($refs as $migration) {
                    echo "    - $migration\n";
                }
            }
            
            // Recomendación
            echo "\n💡 RECOMENDACIÓN:\n";
            echo "  • " . $this->getRecommendationIcon($analysis['recommendation']) . "\n";
            echo "  • " . $this->getRecommendationDescription($analysis['recommendation']) . "\n";
            
            echo "\n" . str_repeat("=", 80) . "\n\n";
        }
    }
    
    private function getRecommendationDescription($recommendation)
    {
        switch ($recommendation) {
            case 'keep_table1_drop_table2':
                return 'Eliminar tabla 2 (vacía o menos usada)';
            case 'keep_table2_drop_table1':
                return 'Eliminar tabla 1 (vacía o menos usada)';
            case 'keep_table1_merge_table2':
                return 'Migrar datos de tabla 2 a tabla 1, luego eliminar tabla 2';
            case 'keep_table2_merge_table1':
                return 'Migrar datos de tabla 1 a tabla 2, luego eliminar tabla 1';
            case 'no_action':
                return 'Ninguna acción necesaria - no hay duplicado real';
            case 'manual_review':
                return 'Requiere análisis manual - situación compleja';
            default:
                return 'Acción no determinada';
        }
    }
    
    private function generateFixScript()
    {
        $scriptFile = 'fix-duplicate-tables-' . date('Y-m-d-H-i-s') . '.sql';
        $script = "-- MDA Duplicate Tables Fix Script\n";
        $script .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $script .= "-- WARNING: Review each statement carefully before execution\n\n";
        
        foreach ($this->analysisResults as $analysis) {
            $table1 = $analysis['table1'];
            $table2 = $analysis['table2'];
            $recommendation = $analysis['recommendation'];
            
            $script .= "-- PAIR: $table1 vs $table2\n";
            $script .= "-- Recommendation: " . $this->getRecommendationDescription($recommendation) . "\n";
            
            switch ($recommendation) {
                case 'keep_table1_drop_table2':
                    $script .= "-- Safe to drop $table2 (empty or unused)\n";
                    $script .= "-- DROP TABLE IF EXISTS `$table2`;\n\n";
                    break;
                    
                case 'keep_table2_drop_table1':
                    $script .= "-- Safe to drop $table1 (empty or unused)\n";
                    $script .= "-- DROP TABLE IF EXISTS `$table1`;\n\n";
                    break;
                    
                case 'keep_table1_merge_table2':
                    if ($analysis['table2_count'] > 0) {
                        $script .= "-- WARNING: $table2 has data - manual merge required\n";
                        $script .= "-- Step 1: Migrate data from $table2 to $table1\n";
                        $script .= "-- INSERT INTO `$table1` SELECT * FROM `$table2`;\n";
                        $script .= "-- Step 2: Verify data integrity\n";
                        $script .= "-- Step 3: Drop $table2\n";
                        $script .= "-- DROP TABLE IF EXISTS `$table2`;\n\n";
                    } else {
                        $script .= "-- Safe to drop $table2 (no data)\n";
                        $script .= "-- DROP TABLE IF EXISTS `$table2`;\n\n";
                    }
                    break;
                    
                case 'keep_table2_merge_table1':
                    if ($analysis['table1_count'] > 0) {
                        $script .= "-- WARNING: $table1 has data - manual merge required\n";
                        $script .= "-- Step 1: Migrate data from $table1 to $table2\n";
                        $script .= "-- INSERT INTO `$table2` SELECT * FROM `$table1`;\n";
                        $script .= "-- Step 2: Verify data integrity\n";
                        $script .= "-- Step 3: Drop $table1\n";
                        $script .= "-- DROP TABLE IF EXISTS `$table1`;\n\n";
                    } else {
                        $script .= "-- Safe to drop $table1 (no data)\n";
                        $script .= "-- DROP TABLE IF EXISTS `$table1`;\n\n";
                    }
                    break;
                    
                case 'no_action':
                    $script .= "-- No action needed - no actual duplicate\n\n";
                    break;
                    
                case 'manual_review':
                    $script .= "-- MANUAL REVIEW REQUIRED\n";
                    $script .= "-- Complex situation - analyze manually before proceeding\n\n";
                    break;
            }
        }
        
        file_put_contents($scriptFile, $script);
        echo "🗑️  Script de corrección generado: $scriptFile\n\n";
    }
    
    private function executeFix()
    {
        echo "⚠️  EJECUCIÓN DE CORRECCIONES\n";
        echo str_repeat("-", 40) . "\n\n";
        
        echo "🚨 IMPORTANTE: Esta funcionalidad ejecutará cambios en la BD\n";
        echo "¿Está seguro de continuar? Esta acción NO es reversible.\n";
        echo "Tipo 'CONFIRMAR' para continuar: ";
        
        $confirmation = trim(fgets(STDIN));
        
        if ($confirmation !== 'CONFIRMAR') {
            echo "❌ Operación cancelada por el usuario\n";
            return;
        }
        
        echo "\n🔄 Ejecutando correcciones...\n\n";
        
        foreach ($this->analysisResults as $analysis) {
            $table1 = $analysis['table1'];
            $table2 = $analysis['table2'];
            $recommendation = $analysis['recommendation'];
            
            echo "▸ Procesando: $table1 vs $table2\n";
            
            switch ($recommendation) {
                case 'keep_table1_drop_table2':
                    if ($analysis['table2_exists'] && $analysis['table2_count'] == 0) {
                        $this->executeQuery("DROP TABLE IF EXISTS `$table2`");
                        echo "  ✅ Eliminada tabla vacía: $table2\n";
                    } else {
                        echo "  ⚠️  Saltando $table2 - tiene datos o no existe\n";
                    }
                    break;
                    
                case 'keep_table2_drop_table1':
                    if ($analysis['table1_exists'] && $analysis['table1_count'] == 0) {
                        $this->executeQuery("DROP TABLE IF EXISTS `$table1`");
                        echo "  ✅ Eliminada tabla vacía: $table1\n";
                    } else {
                        echo "  ⚠️  Saltando $table1 - tiene datos o no existe\n";
                    }
                    break;
                    
                case 'no_action':
                    echo "  ✅ Sin acción requerida\n";
                    break;
                    
                default:
                    echo "  ⚠️  Requiere intervención manual: $recommendation\n";
                    break;
            }
            
            echo "\n";
        }
        
        echo "✅ Proceso de corrección completado\n";
    }
    
    private function executeQuery($query)
    {
        $result = $this->db->query($query);
        if (!$result) {
            throw new \Exception("Error ejecutando query: " . $this->db->error);
        }
        return $result;
    }
    
    private function showRecommendations()
    {
        echo "💡 RECOMENDACIONES FINALES\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $actionNeeded = false;
        $manualReview = false;
        
        foreach ($this->analysisResults as $analysis) {
            if (in_array($analysis['recommendation'], [
                'keep_table1_drop_table2', 
                'keep_table2_drop_table1',
                'keep_table1_merge_table2',
                'keep_table2_merge_table1'
            ])) {
                $actionNeeded = true;
            }
            
            if ($analysis['recommendation'] === 'manual_review') {
                $manualReview = true;
            }
        }
        
        if (!$actionNeeded && !$manualReview) {
            echo "✅ SITUACIÓN BAJO CONTROL\n";
            echo "   No se detectaron duplicados reales que requieran acción\n\n";
            return;
        }
        
        echo "📋 PLAN DE ACCIÓN RECOMENDADO:\n\n";
        
        if ($actionNeeded) {
            echo "1️⃣  CORRECCIONES AUTOMÁTICAS:\n";
            echo "   • Generar script de corrección:\n";
            echo "     php resolve-duplicate-tables.php --generate-fix\n";
            echo "   • Revisar script generado antes de ejecutar\n";
            echo "   • Hacer backup antes de cualquier cambio:\n";
            echo "     php spark db:backup --full\n\n";
        }
        
        if ($manualReview) {
            echo "2️⃣  REVISIÓN MANUAL REQUERIDA:\n";
            echo "   • Analizar casos complejos individualmente\n";
            echo "   • Verificar impacto en código fuente\n";
            echo "   • Consultar con el equipo de desarrollo\n\n";
        }
        
        echo "⚠️  PRECAUCIONES:\n";
        echo "   • SIEMPRE hacer backup antes de eliminar tablas\n";
        echo "   • Verificar que la aplicación funcione después de cambios\n";
        echo "   • Actualizar modelos de CodeIgniter si es necesario\n";
        echo "   • Documentar cambios realizados\n\n";
        
        echo "🔗 HERRAMIENTAS ÚTILES:\n";
        echo "   • php spark db:backup --full           (backup completo)\n";
        echo "   • php database-monitor.php --report    (estado general)\n";
        echo "   • php spark db:check --quick           (verificación)\n\n";
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
    $resolver = new DuplicateTablesResolver();
    
    $args = $argv ?? [];
    
    if (count($args) === 1) {
        echo "MDA Duplicate Tables Resolver\n\n";
        echo "Opciones disponibles:\n";
        echo "  --analyze         : Análisis detallado de duplicados\n";
        echo "  --generate-fix    : Generar script SQL de corrección\n";
        echo "  --execute-fix     : Ejecutar correcciones (¡PELIGROSO!)\n\n";
        echo "Ejemplos:\n";
        echo "  php resolve-duplicate-tables.php\n";
        echo "  php resolve-duplicate-tables.php --analyze\n";
        echo "  php resolve-duplicate-tables.php --generate-fix\n\n";
        
        $resolver->run();
    } else {
        $resolver->run(array_slice($args, 1));
    }
}