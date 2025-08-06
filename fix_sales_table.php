<?php

// Fix missing column in sales_orders table
echo "=== Fixing sales_orders table ===\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=mda", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "1. Connected to database\n";
    
    // Check if updated_by column exists
    $stmt = $pdo->query("DESCRIBE sales_orders");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('updated_by', $columns)) {
        echo "2. Adding missing updated_by column...\n";
        $pdo->exec("ALTER TABLE sales_orders ADD COLUMN updated_by INT(11) AFTER updated_at");
        echo "3. Column added successfully\n";
    } else {
        echo "2. updated_by column already exists\n";
    }
    
    // Now test insert again
    $testData = [
        'client_id' => 1,
        'created_by' => 6,
        'stock' => 'TEST001',
        'vin' => 'TEST123456789',
        'vehicle' => 'Test Vehicle',
        'service_id' => 1,
        'date' => '2025-07-12',
        'time' => '12:00:00',
        'status' => 'pending',
        'instructions' => 'Test instructions',
        'notes' => 'Test notes',
        'updated_by' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    echo "4. Testing insert with all required fields...\n";
    
    $columns = implode(',', array_keys($testData));
    $placeholders = ':' . implode(', :', array_keys($testData));
    $sql = "INSERT INTO sales_orders ($columns) VALUES ($placeholders)";
    
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($testData)) {
        $insertId = $pdo->lastInsertId();
        echo "5. Insert SUCCESS (ID: $insertId)\n";
        
        // Clean up test record
        $pdo->exec("DELETE FROM sales_orders WHERE id = $insertId");
        echo "6. Test record cleaned up\n";
    } else {
        echo "5. Insert FAILED\n";
        $errorInfo = $stmt->errorInfo();
        echo "   Error: " . $errorInfo[2] . "\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Fix Complete ===\n";
