<?php
// Verificar y corregir columnas faltantes en múltiples tablas
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mda', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICACIÓN COMPLETA DE TABLAS ===\n\n";
    
    // Configuración de tablas y columnas esperadas
    $tablesConfig = [
        'sales_orders' => [
            'deleted' => 'TINYINT(1) DEFAULT 0',
            'completed_at' => 'DATETIME NULL',
            'date' => 'DATE NULL'
        ],
        'sales_orders_services' => [
            'service_status' => "ENUM('active', 'inactive') DEFAULT 'active'",
            'show_in_orders' => 'TINYINT(1) DEFAULT 1',
            'service_price' => 'DECIMAL(10,2) DEFAULT 0.00',
            'client_id' => 'INT(11) UNSIGNED NULL',
            'created_by' => 'INT(11) UNSIGNED NULL',
            'updated_by' => 'INT(11) UNSIGNED NULL',
            'deleted' => 'TINYINT(1) DEFAULT 0'
        ]
    ];
    
    $totalColumnsAdded = 0;
    
    foreach ($tablesConfig as $tableName => $expectedColumns) {
        echo "🔍 Verificando tabla: $tableName\n";
        
        // Verificar si la tabla existe
        $stmt = $pdo->query("SHOW TABLES LIKE '$tableName'");
        if ($stmt->rowCount() == 0) {
            echo "   ⚠️  Tabla '$tableName' no existe - saltando\n\n";
            continue;
        }
        
        // Obtener columnas actuales
        $stmt = $pdo->query("DESCRIBE $tableName");
        $currentColumns = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
            $currentColumns[] = $col['Field'];
        }
        
        $tableColumnsAdded = 0;
        
        foreach ($expectedColumns as $column => $definition) {
            if (!in_array($column, $currentColumns)) {
                echo "   ➕ Agregando: $column\n";
                try {
                    $sql = "ALTER TABLE $tableName ADD COLUMN $column $definition";
                    $pdo->exec($sql);
                    $tableColumnsAdded++;
                    $totalColumnsAdded++;
                } catch (Exception $e) {
                    echo "   ❌ Error agregando $column: " . $e->getMessage() . "\n";
                }
            } else {
                echo "   ✅ $column ya existe\n";
            }
        }
        
        echo "   📊 Columnas agregadas en $tableName: $tableColumnsAdded\n\n";
    }
    
    // Verificar también otras tablas comunes que podrían necesitar columnas 'deleted'
    echo "🔍 Verificando otras tablas que podrían necesitar 'deleted':\n";
    
    $commonTables = ['clients', 'contacts', 'services', 'users'];
    
    foreach ($commonTables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE 'deleted'");
            if ($stmt->rowCount() == 0) {
                echo "   ➕ Agregando 'deleted' a $table\n";
                try {
                    $pdo->exec("ALTER TABLE $table ADD COLUMN deleted TINYINT(1) DEFAULT 0");
                    $totalColumnsAdded++;
                } catch (Exception $e) {
                    echo "   ❌ Error: " . $e->getMessage() . "\n";
                }
            } else {
                echo "   ✅ $table ya tiene 'deleted'\n";
            }
        }
    }
    
    echo "\n=== RESUMEN FINAL ===\n";
    echo "🎉 Total de columnas agregadas: $totalColumnsAdded\n";
    echo "✅ Verificación completa de tablas finalizada\n";
    
} catch (Exception $e) {
    echo "ERROR GENERAL: " . $e->getMessage() . "\n";
}
?>
