<?php

// Add salesperson_id column to sales_orders table
echo "=== Adding salesperson_id column ===\n";

try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'mda';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "1. Database connection: OK\n";
    
    // Check if salesperson_id column already exists
    $stmt = $pdo->query("SHOW COLUMNS FROM sales_orders LIKE 'salesperson_id'");
    if ($stmt->rowCount() > 0) {
        echo "2. salesperson_id column already exists\n";
    } else {
        echo "2. Adding salesperson_id column...\n";
        
        // Add the column after contact_id
        $sql = "ALTER TABLE sales_orders ADD COLUMN salesperson_id INT(11) NULL AFTER contact_id";
        $pdo->exec($sql);
        
        echo "3. salesperson_id column added successfully\n";
        
        // Copy values from contact_id to salesperson_id if contact_id has data
        echo "4. Copying existing contact_id values to salesperson_id...\n";
        $sql = "UPDATE sales_orders SET salesperson_id = contact_id WHERE contact_id IS NOT NULL";
        $result = $pdo->exec($sql);
        
        echo "5. Updated $result records with salesperson_id values\n";
    }
    
    // Show final structure
    echo "6. Final table structure:\n";
    $stmt = $pdo->query("DESCRIBE sales_orders");
    $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($fields as $field) {
        if (in_array($field['Field'], ['contact_id', 'salesperson_id', 'created_by'])) {
            echo "   - {$field['Field']} ({$field['Type']}) - {$field['Null']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
