<?php
// Get real status data for inventory items based on stock numbers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration from .env
$host = '35.212.30.157';
$dbname = 'dbuc0youbm7qp9';
$username = 'u9jvaasruh9vc';
$password = 'lalinha01?';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Get stock numbers from request (if provided)
    $stocks = [];
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    
    if ($requestMethod === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['stocks']) && is_array($input['stocks'])) {
            $stocks = $input['stocks'];
        }
    } elseif (isset($_GET['stocks'])) {
        $stocks = is_array($_GET['stocks']) ? $_GET['stocks'] : explode(',', $_GET['stocks']);
    }

    // Build the query
    $sql = "
        SELECT 
            ro.stock,
            ro.vin_number,
            ro.status,
            ro.service_date,
            ro.created_at,
            ro.updated_at,
            rs.name as service_name,
            rs.color as service_color,
            CASE 
                WHEN ro.status IS NULL THEN 'no_status'
                ELSE ro.status 
            END as display_status,
            CASE 
                WHEN ro.status = 'pending' THEN 'pending'
                WHEN ro.status = 'in_progress' THEN 'in_progress'
                WHEN ro.status = 'completed' THEN 'completed'
                WHEN ro.status = 'cancelled' THEN 'cancelled'
                ELSE 'no_status'
            END as status_description
        FROM recon_orders ro
        LEFT JOIN recon_services rs ON ro.service_id = rs.id
        WHERE 1=1
    ";

    $params = [];
    
    // Add stock number filter if provided
    if (!empty($stocks)) {
        $placeholders = str_repeat('?,', count($stocks) - 1) . '?';
        $sql .= " AND ro.stock IN ($placeholders)";
        $params = $stocks;
    }

    $sql .= " ORDER BY ro.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();

    // Format the results as a lookup array
    $statusLookup = [];
    foreach ($results as $row) {
        $stockNumber = $row['stock'];
        $statusLookup[$stockNumber] = [
            'status' => $row['display_status'],
            'vin_number' => $row['vin_number'],
            'service_name' => $row['service_name'] ?: 'Detail Process',
            'service_color' => $row['service_color'] ?: '#007bff',
            'service_date' => $row['service_date'],
            'status_description' => $row['status_description'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'real_data' => true
        ];
    }

    // If no specific stocks were requested, get all inventory items without orders
    if (empty($stocks)) {
        // This would require access to the inventory table
        // For now, we'll just return what we have
        echo json_encode([
            'success' => true,
            'data' => $statusLookup,
            'count' => count($statusLookup),
            'message' => 'Real status data loaded successfully'
        ]);
    } else {
        // For specific stocks, make sure all requested stocks have an entry
        foreach ($stocks as $stock) {
            if (!isset($statusLookup[$stock])) {
                $statusLookup[$stock] = [
                    'status' => 'no_status',
                    'vin_number' => null,
                    'service_name' => 'No Order Found',
                    'service_color' => '#6c757d',
                    'service_date' => null,
                    'status_description' => 'no_status',
                    'created_at' => null,
                    'updated_at' => null,
                    'real_data' => true
                ];
            }
        }

        echo json_encode([
            'success' => true,
            'data' => $statusLookup,
            'count' => count($statusLookup),
            'message' => 'Real status data loaded for ' . count($stocks) . ' stock numbers'
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage(),
        'data' => []
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage(),
        'data' => []
    ]);
}
?>
