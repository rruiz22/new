<?php
/**
 * MDA Migration Review Tool
 * 
 * Script para revisar y clasificar migraciones pendientes por nivel de riesgo
 * Ayuda a identificar cuáles pueden ejecutarse de forma segura
 * 
 * Uso:
 * php review-migrations.php
 * php review-migrations.php --show-content
 * php review-migrations.php --classify
 * php review-migrations.php --export-report
 * 
 * @author Claude AI
 * @date 2025-09-05
 */

class MigrationReviewer
{
    private $migrationsPath;
    private $pendingMigrations = [];
    private $riskClassification = [];
    
    public function __construct()
    {
        $this->migrationsPath = __DIR__ . '/app/Database/Migrations/';
        if (!is_dir($this->migrationsPath)) {
            die("❌ Directorio de migraciones no encontrado: {$this->migrationsPath}\n");
        }
    }
    
    public function run($options = [])
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "           MDA MIGRATION REVIEW TOOL - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 80) . "\n\n";
        
        $this->loadPendingMigrations();
        
        if (empty($this->pendingMigrations)) {
            echo "✅ No hay migraciones pendientes para revisar.\n";
            return;
        }
        
        echo "📊 RESUMEN GENERAL\n";
        echo str_repeat("-", 40) . "\n";
        echo "Migraciones pendientes encontradas: " . count($this->pendingMigrations) . "\n\n";
        
        if (in_array('--show-content', $options)) {
            $this->showMigrationContents();
        }
        
        if (in_array('--classify', $options)) {
            $this->classifyMigrations();
            $this->showRiskClassification();
        } else {
            $this->classifyMigrations();
            $this->showQuickClassification();
        }
        
        if (in_array('--export-report', $options)) {
            $this->exportReport();
        }
        
        $this->showRecommendations();
    }
    
    private function loadPendingMigrations()
    {
        // Obtener estado de migraciones
        $output = shell_exec('php spark migrate:status 2>&1');
        
        if (!$output) {
            echo "❌ No se pudo obtener el estado de migraciones\n";
            return;
        }
        
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            if (strpos($line, '---') !== false && strpos($line, '|') !== false) {
                // Extraer información de la línea
                $parts = explode('|', $line);
                if (count($parts) >= 4) {
                    $namespace = trim($parts[1]);
                    $version = trim($parts[2]);
                    $filename = trim($parts[3]);
                    
                    if (!empty($filename) && $filename !== 'Filename') {
                        $this->pendingMigrations[] = [
                            'namespace' => $namespace,
                            'version' => $version,
                            'filename' => $filename,
                            'filepath' => $this->findMigrationFile($filename, $version)
                        ];
                    }
                }
            }
        }
    }
    
    private function findMigrationFile($filename, $version)
    {
        $patterns = [
            $this->migrationsPath . $version . '_' . $filename . '.php',
            $this->migrationsPath . str_replace('-', '_', $version) . '_' . $filename . '.php',
            $this->migrationsPath . $version . '-*_' . $filename . '.php'
        ];
        
        foreach ($patterns as $pattern) {
            $files = glob($pattern);
            if (!empty($files)) {
                return $files[0];
            }
        }
        
        // Buscar por nombre de archivo
        $files = glob($this->migrationsPath . '*' . $filename . '.php');
        if (!empty($files)) {
            return $files[0];
        }
        
        return null;
    }
    
    private function classifyMigrations()
    {
        foreach ($this->pendingMigrations as $index => $migration) {
            $risk = $this->analyzeMigrationRisk($migration);
            $this->riskClassification[$risk][] = $migration;
            $this->pendingMigrations[$index]['risk'] = $risk;
        }
    }
    
    private function analyzeMigrationRisk($migration)
    {
        if (!$migration['filepath'] || !file_exists($migration['filepath'])) {
            return 'unknown';
        }
        
        $content = file_get_contents($migration['filepath']);
        
        // Patrones de alto riesgo
        $highRiskPatterns = [
            '/DROP\s+TABLE/i',
            '/DROP\s+COLUMN/i',
            '/ALTER\s+TABLE.*DROP/i',
            '/DELETE\s+FROM/i',
            '/TRUNCATE/i',
            '/ALTER\s+TABLE.*CHANGE/i',
            '/ALTER\s+TABLE.*MODIFY/i'
        ];
        
        // Patrones de medio riesgo
        $mediumRiskPatterns = [
            '/ALTER\s+TABLE.*ADD\s+COLUMN/i',
            '/CREATE\s+INDEX/i',
            '/ADD\s+FOREIGN\s+KEY/i',
            '/ADD\s+CONSTRAINT/i',
            '/ALTER\s+TABLE.*ADD\s+INDEX/i',
            '/UPDATE\s+.*SET/i'
        ];
        
        // Patrones de bajo riesgo
        $lowRiskPatterns = [
            '/CREATE\s+TABLE/i',
            '/INSERT\s+INTO/i',
            '/CREATE\s+DATABASE/i'
        ];
        
        // Verificar patrones de alto riesgo
        foreach ($highRiskPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return 'high';
            }
        }
        
        // Verificar patrones de medio riesgo
        foreach ($mediumRiskPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return 'medium';
            }
        }
        
        // Verificar patrones de bajo riesgo
        foreach ($lowRiskPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return 'low';
            }
        }
        
        return 'unknown';
    }
    
    private function showQuickClassification()
    {
        echo "🎯 CLASIFICACIÓN POR NIVEL DE RIESGO\n";
        echo str_repeat("-", 40) . "\n";
        
        $counts = [
            'low' => count($this->riskClassification['low'] ?? []),
            'medium' => count($this->riskClassification['medium'] ?? []),
            'high' => count($this->riskClassification['high'] ?? []),
            'unknown' => count($this->riskClassification['unknown'] ?? [])
        ];
        
        echo "✅ BAJO RIESGO: {$counts['low']} migraciones\n";
        echo "   (CREATE TABLE, INSERT básicos)\n\n";
        
        echo "⚠️  MEDIO RIESGO: {$counts['medium']} migraciones\n";
        echo "   (ADD COLUMN, CREATE INDEX, ADD CONSTRAINT)\n\n";
        
        echo "🚨 ALTO RIESGO: {$counts['high']} migraciones\n";
        echo "   (DROP TABLE/COLUMN, ALTER estructura, DELETE data)\n\n";
        
        echo "❓ DESCONOCIDO: {$counts['unknown']} migraciones\n";
        echo "   (Archivo no encontrado o patrón no reconocido)\n\n";
    }
    
    private function showRiskClassification()
    {
        $riskLabels = [
            'low' => '✅ BAJO RIESGO',
            'medium' => '⚠️  MEDIO RIESGO', 
            'high' => '🚨 ALTO RIESGO',
            'unknown' => '❓ DESCONOCIDO'
        ];
        
        foreach ($riskLabels as $risk => $label) {
            if (!empty($this->riskClassification[$risk])) {
                echo "\n$label (" . count($this->riskClassification[$risk]) . " migraciones)\n";
                echo str_repeat("-", 50) . "\n";
                
                foreach ($this->riskClassification[$risk] as $migration) {
                    echo "• {$migration['filename']} ({$migration['version']})\n";
                    echo "  Namespace: {$migration['namespace']}\n";
                    if ($migration['filepath']) {
                        echo "  Archivo: " . basename($migration['filepath']) . "\n";
                    } else {
                        echo "  ⚠️  Archivo no encontrado\n";
                    }
                    echo "\n";
                }
            }
        }
    }
    
    private function showMigrationContents()
    {
        echo "📄 CONTENIDO DE MIGRACIONES\n";
        echo str_repeat("-", 40) . "\n\n";
        
        foreach ($this->pendingMigrations as $migration) {
            echo "▸ {$migration['filename']}\n";
            echo "  Versión: {$migration['version']}\n";
            echo "  Namespace: {$migration['namespace']}\n";
            
            if ($migration['filepath'] && file_exists($migration['filepath'])) {
                echo "  Archivo: " . basename($migration['filepath']) . "\n";
                echo "  " . str_repeat("-", 45) . "\n";
                
                $content = file_get_contents($migration['filepath']);
                // Mostrar solo el método up()
                if (preg_match('/public function up\(\)\s*\{(.*?)\s*\}/s', $content, $matches)) {
                    $upContent = trim($matches[1]);
                    $lines = explode("\n", $upContent);
                    foreach ($lines as $line) {
                        echo "  " . $line . "\n";
                    }
                } else {
                    echo "  ⚠️  No se pudo extraer el método up()\n";
                }
            } else {
                echo "  ❌ Archivo no encontrado: {$migration['filepath']}\n";
            }
            echo "\n" . str_repeat("=", 60) . "\n\n";
        }
    }
    
    private function exportReport()
    {
        $reportFile = 'migration-review-report-' . date('Y-m-d-H-i-s') . '.json';
        
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'total_pending' => count($this->pendingMigrations),
            'risk_summary' => [
                'low' => count($this->riskClassification['low'] ?? []),
                'medium' => count($this->riskClassification['medium'] ?? []),
                'high' => count($this->riskClassification['high'] ?? []),
                'unknown' => count($this->riskClassification['unknown'] ?? [])
            ],
            'migrations' => $this->pendingMigrations,
            'classification' => $this->riskClassification
        ];
        
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        echo "📊 Reporte exportado a: $reportFile\n\n";
    }
    
    private function showRecommendations()
    {
        echo "💡 RECOMENDACIONES DE EJECUCIÓN\n";
        echo str_repeat("-", 40) . "\n";
        
        $lowCount = count($this->riskClassification['low'] ?? []);
        $mediumCount = count($this->riskClassification['medium'] ?? []);
        $highCount = count($this->riskClassification['high'] ?? []);
        $unknownCount = count($this->riskClassification['unknown'] ?? []);
        
        echo "ORDEN RECOMENDADO DE EJECUCIÓN:\n\n";
        
        if ($lowCount > 0) {
            echo "1️⃣  EJECUTAR PRIMERO ($lowCount migraciones de bajo riesgo)\n";
            echo "   ✅ Pueden ejecutarse con seguridad\n";
            echo "   💾 Hacer backup preventivo básico\n\n";
        }
        
        if ($mediumCount > 0) {
            echo "2️⃣  REVISAR Y EJECUTAR ($mediumCount migraciones de medio riesgo)\n";
            echo "   ⚠️  Revisar cada migración individualmente\n";
            echo "   💾 Backup completo OBLIGATORIO antes de ejecutar\n";
            echo "   🧪 Probar primero en ambiente de desarrollo\n\n";
        }
        
        if ($highCount > 0) {
            echo "3️⃣  ANÁLISIS CRÍTICO ($highCount migraciones de alto riesgo)\n";
            echo "   🚨 REQUIEREN REVISIÓN MANUAL EXHAUSTIVA\n";
            echo "   💾 Backup completo + plan de rollback\n";
            echo "   👥 Revisión por pares obligatoria\n";
            echo "   🕐 Ejecutar en horario de mantenimiento\n\n";
        }
        
        if ($unknownCount > 0) {
            echo "❓ INVESTIGAR ($unknownCount migraciones desconocidas)\n";
            echo "   🔍 Revisar archivos faltantes\n";
            echo "   📄 Analizar contenido manualmente\n\n";
        }
        
        echo "⚠️  PRECAUCIONES GENERALES:\n";
        echo "   • NUNCA ejecutar todas las migraciones de una vez\n";
        echo "   • Verificar espacio en disco antes de ejecutar\n";
        echo "   • Monitorear logs durante la ejecución\n";
        echo "   • Tener contacto con administrador disponible\n\n";
        
        // Generar comando sugerido para empezar
        if ($lowCount > 0) {
            echo "🚀 COMANDO SUGERIDO PARA EMPEZAR:\n";
            echo "   1. php spark db:backup\n";
            echo "   2. Ejecutar migraciones de bajo riesgo una por una\n";
            echo "   3. Verificar integridad después de cada lote\n\n";
        }
    }
}

// Ejecución del script
if (php_sapi_name() === 'cli') {
    $reviewer = new MigrationReviewer();
    
    $args = $argv ?? [];
    
    if (count($args) === 1) {
        echo "MDA Migration Review Tool\n\n";
        echo "Opciones disponibles:\n";
        echo "  --show-content    : Mostrar contenido de cada migración\n";
        echo "  --classify        : Mostrar clasificación detallada\n";
        echo "  --export-report   : Exportar reporte a JSON\n\n";
        echo "Ejemplos:\n";
        echo "  php review-migrations.php\n";
        echo "  php review-migrations.php --classify\n";
        echo "  php review-migrations.php --show-content --export-report\n\n";
        
        $reviewer->run();
    } else {
        $reviewer->run(array_slice($args, 1));
    }
}