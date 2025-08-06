<?php
// Verificar y corregir columnas faltantes definitivamente
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mda', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICACIÓN CRÍTICA DE COLUMNAS ===\n\n";
    
    // Verificar sales_orders
    $stmt = $pdo->query("SHOW COLUMNS FROM sales_orders");
    $columns = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        $columns[] = $col['Field'];
    }
    
    echo "Columnas actuales en sales_orders:\n";
    foreach ($columns as $col) {
        echo "  - $col\n";
    }
    
    $missingColumns = [];
    $requiredColumns = ['deleted', 'completed_at', 'date'];
    
    foreach ($requiredColumns as $col) {
        if (!in_array($col, $columns)) {
            $missingColumns[] = $col;
        }
    }
    
    if (!empty($missingColumns)) {
        echo "\n❌ COLUMNAS FALTANTES: " . implode(', ', $missingColumns) . "\n";
        echo "🔧 AGREGANDO COLUMNAS...\n\n";
        
        $definitions = [
            'deleted' => 'TINYINT(1) DEFAULT 0',
            'completed_at' => 'DATETIME NULL',
            'date' => 'DATE NULL'
        ];
        
        foreach ($missingColumns as $col) {
            try {
                $sql = "ALTER TABLE sales_orders ADD COLUMN $col {$definitions[$col]}";
                $pdo->exec($sql);
                echo "✅ Agregada: $col\n";
            } catch (Exception $e) {
                echo "❌ Error agregando $col: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "\n✅ TODAS LAS COLUMNAS EXISTEN\n";
    }
    
    // Verificar sales_orders_services también
    echo "\n=== VERIFICANDO sales_orders_services ===\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM sales_orders_services LIKE 'service_status'");
    echo "service_status: " . ($stmt->rowCount() > 0 ? "✅ EXISTE" : "❌ FALTA") . "\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM sales_orders_services LIKE 'show_in_orders'");
    echo "show_in_orders: " . ($stmt->rowCount() > 0 ? "✅ EXISTE" : "❌ FALTA") . "\n";
    
    // Probar las consultas que estaban fallando
    echo "\n=== PROBANDO CONSULTAS ===\n";
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM sales_orders WHERE deleted = 0");
        echo "✅ Consulta con 'deleted' funciona\n";
    } catch (Exception $e) {
        echo "❌ Consulta con 'deleted' falla: " . $e->getMessage() . "\n";
    }
    
    try {
        $stmt = $pdo->query("SELECT * FROM sales_orders_services WHERE service_status = 'active' LIMIT 1");
        echo "✅ Consulta con 'service_status' funciona\n";
    } catch (Exception $e) {
        echo "❌ Consulta con 'service_status' falla: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎯 VERIFICACIÓN COMPLETADA\n";
    
} catch (Exception $e) {
    echo "ERROR CRÍTICO: " . $e->getMessage() . "\n";
}
?>
