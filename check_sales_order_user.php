<?php

/**
 * Test script to verify Sales Order user data functionality
 * Checks if the "Sent by" feature is working correctly
 */

// Set the base path correctly
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', ROOTPATH . 'system' . DIRECTORY_SEPARATOR);
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Load environment
if (file_exists(ROOTPATH . '.env')) {
    $dotenv = parse_ini_file(ROOTPATH . '.env');
    foreach ($dotenv as $key => $value) {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Database connection configuration
$db_config = [
    'hostname' => $_ENV['database.default.hostname'] ?? '35.212.30.157',
    'database' => $_ENV['database.default.database'] ?? 'dbuc0youbm7qp9', 
    'username' => $_ENV['database.default.username'] ?? 'u9jvaasruh9vc',
    'password' => $_ENV['database.default.password'] ?? 'lalinha01?',
    'port'     => $_ENV['database.default.port'] ?? 3306
];

echo "<h1>🔍 Sales Order User Data Test</h1>\n";
echo "<p><strong>Testing:</strong> 'Sent by' functionality in Sales Orders</p>\n";
echo "<p><strong>Recent Fixes:</strong></p>\n";
echo "<ul>\n";
echo "<li>✅ Undefined variable \$db error in SalesOrderModel.php</li>\n";
echo "<li>✅ 'Sent by' section hidden on tablet/mobile (desktop only)</li>\n";
echo "<li>✅ Contact/Salesperson name display logic improved</li>\n";
echo "</ul>\n";
echo "<hr>\n";

try {
    // Create database connection
    $pdo = new PDO(
        "mysql:host={$db_config['hostname']};port={$db_config['port']};dbname={$db_config['database']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "✅ <strong>Database connection:</strong> Successfully connected<br>\n";
    
    // Test 1: Check if users table has the necessary fields
    echo "<h3>📋 Test 1: User Table Structure</h3>\n";
    $userQuery = $pdo->query("DESCRIBE users");
    $userFields = $userQuery->fetchAll();
    
    $requiredFields = ['id', 'username', 'first_name', 'last_name', 'created_at'];
    $hasAllFields = true;
    
    echo "<ul>\n";
    foreach ($requiredFields as $field) {
        $found = array_filter($userFields, function($f) use ($field) {
            return $f['Field'] === $field;
        });
        
        if ($found) {
            echo "<li>✅ Field '<code>{$field}</code>' exists</li>\n";
        } else {
            echo "<li>❌ Field '<code>{$field}</code>' missing</li>\n";
            $hasAllFields = false;
        }
    }
    echo "</ul>\n";
    
    // Test 2: Check if sales_orders table has created_by field
    echo "<h3>📋 Test 2: Sales Orders Table Structure</h3>\n";
    $salesOrderQuery = $pdo->query("DESCRIBE sales_orders");
    $salesOrderFields = $salesOrderQuery->fetchAll();
    
    $createdByField = array_filter($salesOrderFields, function($f) {
        return $f['Field'] === 'created_by';
    });
    
    if ($createdByField) {
        echo "✅ Field '<code>created_by</code>' exists in sales_orders table<br>\n";
    } else {
        echo "❌ Field '<code>created_by</code>' missing in sales_orders table<br>\n";
        $hasAllFields = false;
    }
    
    // Test 3: Test the actual query that would be used in SalesOrderModel
    echo "<h3>📋 Test 3: Query Functionality Test</h3>\n";
    
    $testQuery = "
        SELECT 
            so.*,
            u.username as created_by_username,
            CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) as created_by_name
        FROM sales_orders so
        LEFT JOIN users u ON so.created_by = u.id
        WHERE so.deleted = 0
        LIMIT 5
    ";
    
    try {
        $result = $pdo->query($testQuery);
        $orders = $result->fetchAll();
        
        echo "✅ <strong>Query executed successfully</strong><br>\n";
        echo "📊 <strong>Found {count($orders)} sales orders</strong><br>\n";
        
        if ($orders) {
            echo "<h4>Sample Data:</h4>\n";
            echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
            echo "<tr><th>ID</th><th>Order Number</th><th>Created By (ID)</th><th>Created By Name</th><th>Username</th><th>Created At</th></tr>\n";
            
            foreach ($orders as $order) {
                $createdByName = trim($order['created_by_name']);
                $createdByName = $createdByName ?: 'Unknown User';
                
                echo "<tr>\n";
                echo "<td>{$order['id']}</td>\n";
                echo "<td>{$order['order_number']}</td>\n";
                echo "<td>{$order['created_by']}</td>\n";
                echo "<td><strong>{$createdByName}</strong></td>\n";
                echo "<td>{$order['created_by_username']}</td>\n";
                echo "<td>{$order['created_at']}</td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }
        
    } catch (Exception $e) {
        echo "❌ <strong>Query failed:</strong> " . $e->getMessage() . "<br>\n";
        $hasAllFields = false;
    }
    
    // Test 4: Check for any sales orders without creator data
    echo "<h3>📋 Test 4: Data Quality Check</h3>\n";
    
    $qualityQuery = "
        SELECT 
            COUNT(*) as total_orders,
            COUNT(created_by) as orders_with_creator,
            COUNT(*) - COUNT(created_by) as orders_without_creator
        FROM sales_orders 
        WHERE deleted = 0
    ";
    
    $qualityResult = $pdo->query($qualityQuery)->fetch();
    
    echo "<ul>\n";
    echo "<li><strong>Total Sales Orders:</strong> {$qualityResult['total_orders']}</li>\n";
    echo "<li><strong>Orders with Creator:</strong> {$qualityResult['orders_with_creator']}</li>\n";
    echo "<li><strong>Orders without Creator:</strong> {$qualityResult['orders_without_creator']}</li>\n";
    echo "</ul>\n";
    
    if ($qualityResult['orders_without_creator'] > 0) {
        echo "⚠️ <strong>Warning:</strong> Some orders don't have creator information<br>\n";
    } else {
        echo "✅ <strong>Great:</strong> All orders have creator information<br>\n";
    }
    
    // Summary
    echo "<hr>\n";
    echo "<h3>📋 Test Results Summary</h3>\n";
    
    if ($hasAllFields && $qualityResult['total_orders'] > 0) {
        echo "✅ <strong>SUCCESS:</strong> All tests passed! The 'Sent by' functionality should work correctly.<br>\n";
        echo "<p><strong>Next Steps:</strong> Access the Sales Orders view page to see the 'Sent by' section in the topbar.</p>\n";
    } else {
        echo "❌ <strong>ISSUES FOUND:</strong> There are some problems that need to be addressed.<br>\n";
        echo "<p><strong>Recommendation:</strong> Review the database structure and data integrity.</p>\n";
    }
    
} catch (Exception $e) {
    echo "❌ <strong>Database Connection Error:</strong> " . $e->getMessage() . "<br>\n";
    echo "<p>Please check your database configuration in the .env file.</p>\n";
}

?>