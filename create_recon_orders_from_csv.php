<?php

/**
 * Script to create Recon Orders from CSV data
 * 
 * This script reads the recon-20250815-040134.csv file and creates recon orders
 * with the specified parameters:
 * - service_id: 31
 * - status: pending
 * - from_inventory: 1
 * - source_type: inventory
 */

class ReconOrderImporter
{
    private $db;
    private $csvFile = 'recon-20250815-040134.csv';
    
    public function __construct()
    {
        // Database configuration from .env file
        $dbConfig = [
            'hostname' => '35.212.30.157',           // Remote database host
            'username' => 'u9jvaasruh9vc',           // Database username
            'password' => 'lalinha01?',              // Database password
            'database' => 'dbuc0youbm7qp9',          // Database name
            'port' => 3306
        ];
        
        try {
            // Create PDO connection
            $dsn = "mysql:host={$dbConfig['hostname']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset=utf8mb4";
            $this->db = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // For SiteGround compatibility
            ]);
            
            echo "✅ Connected to database: {$dbConfig['database']} on {$dbConfig['hostname']}\n";
        } catch (PDOException $e) {
            throw new Exception("❌ Database connection failed: " . $e->getMessage());
        }
    }
    
    public function importFromCSV()
    {
        if (!file_exists($this->csvFile)) {
            throw new \Exception("❌ CSV file not found: {$this->csvFile}");
        }
        
        $handle = fopen($this->csvFile, 'r');
        if ($handle === false) {
            throw new \Exception("❌ Could not open CSV file: {$this->csvFile}");
        }
        
        // Read header row
        $headers = fgetcsv($handle);
        if ($headers === false) {
            throw new \Exception("❌ Could not read CSV headers");
        }
        
        // Remove BOM from first header if present
        if (isset($headers[0])) {
            $headers[0] = ltrim($headers[0], "\xEF\xBB\xBF\xFEFF");
        }
        
        if (!$this->validateHeaders($headers)) {
            $expected = ['service_date', 'client_id', 'stock', 'vin_number'];
            echo "🔍 DEBUG: Actual headers after BOM removal: " . json_encode($headers) . "\n";
            echo "🔍 DEBUG: Expected headers: " . json_encode($expected) . "\n";
            throw new \Exception("❌ Invalid CSV headers. Expected: service_date,client_id,stock,vin_number");
        }
        
        $successCount = 0;
        $errorCount = 0;
        $skippedCount = 0;
        $errors = [];
        $lineNumber = 1; // Header is line 1
        
        echo "\n🚀 Starting import process...\n";
        echo "📋 Headers found: " . implode(', ', $headers) . "\n";
        echo "----------------------------------------\n";
        
        // Process each data row
        while (($data = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            try {
                $orderData = $this->prepareOrderData($data, $headers);
                
                // Validate required fields
                if (empty($orderData['service_date']) || empty($orderData['client_id']) || 
                    empty($orderData['stock']) || empty($orderData['vin_number'])) {
                    throw new \Exception("Missing required fields in line {$lineNumber}");
                }
                
                // Check if order already exists
                $stmt = $this->db->prepare("SELECT id FROM recon_orders WHERE stock = ? AND vin_number = ? AND from_inventory = 1 AND deleted_at IS NULL");
                $stmt->execute([$orderData['stock'], $orderData['vin_number']]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    $skippedCount++;
                    echo "⏭️  SKIPPED Line {$lineNumber}: Order already exists for stock {$orderData['stock']} and VIN {$orderData['vin_number']}\n";
                    continue;
                }
                
                // Create the order
                $orderId = $this->insertOrder($orderData);
                
                if ($orderId) {
                    $successCount++;
                    echo "✅ SUCCESS Line {$lineNumber}: Created order ID {$orderId} for stock {$orderData['stock']}\n";
                } else {
                    $errorCount++;
                    $errorMsg = "Failed to create order";
                    $errors[] = "Line {$lineNumber}: {$errorMsg}";
                    echo "❌ ERROR Line {$lineNumber}: {$errorMsg}\n";
                }
                
            } catch (\Exception $e) {
                $errorCount++;
                $errorMsg = $e->getMessage();
                $errors[] = "Line {$lineNumber}: {$errorMsg}";
                echo "❌ ERROR Line {$lineNumber}: {$errorMsg}\n";
            }
        }
        
        fclose($handle);
        
        // Display summary
        echo "\n========================================\n";
        echo "📊 IMPORT SUMMARY\n";
        echo "========================================\n";
        echo "📋 Total processed: " . ($successCount + $errorCount + $skippedCount) . "\n";
        echo "✅ Successful imports: {$successCount}\n";
        echo "⏭️  Skipped (duplicates): {$skippedCount}\n";
        echo "❌ Errors: {$errorCount}\n";
        
        if (!empty($errors)) {
            echo "\n🔍 ERROR DETAILS:\n";
            foreach ($errors as $error) {
                echo "- {$error}\n";
            }
        }
        
        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'skipped_count' => $skippedCount,
            'errors' => $errors
        ];
    }
    
    private function validateHeaders($headers)
    {
        $expectedHeaders = ['service_date', 'client_id', 'stock', 'vin_number'];
        return $headers === $expectedHeaders;
    }
    
    private function insertOrder($orderData)
    {
        // Generate order number
        $orderData['order_number'] = $this->generateOrderNumber();
        $orderData['created_at'] = date('Y-m-d H:i:s');
        $orderData['updated_at'] = date('Y-m-d H:i:s');
        
        $fields = implode(',', array_keys($orderData));
        $placeholders = ':' . implode(', :', array_keys($orderData));
        
        $sql = "INSERT INTO recon_orders ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute($orderData)) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    private function generateOrderNumber()
    {
        $prefix = 'RO';
        $maxAttempts = 10;
        $attempts = 0;
        
        while ($attempts < $maxAttempts) {
            $orderNumber = $prefix . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Check if order number already exists
            $stmt = $this->db->prepare("SELECT id FROM recon_orders WHERE order_number = ?");
            $stmt->execute([$orderNumber]);
            
            if (!$stmt->fetch()) {
                return $orderNumber;
            }
            
            $attempts++;
        }
        
        // Fallback to timestamp-based order number
        return $prefix . date('YmdHis') . '-' . mt_rand(100, 999);
    }
    
    private function prepareOrderData($data, $headers)
    {
        // Map CSV data to array using headers
        $csvData = array_combine($headers, $data);
        
        // Prepare order data with required fields
        $orderData = [
            'service_date' => $csvData['service_date'],
            'client_id' => (int)$csvData['client_id'],
            'stock' => trim($csvData['stock']),
            'vin_number' => trim($csvData['vin_number']),
            'vehicle' => $this->generateVehicleDescription($csvData['vin_number']), // Required field
            'service_id' => 31, // As specified
            'status' => 'pending', // As specified
            'from_inventory' => 1, // As specified
            'source_type' => 'inventory', // As specified
            'pictures' => 0, // Default
            'created_by' => 1, // System user
            'notes' => 'Created from CSV import on ' . date('Y-m-d H:i:s'),
            'inventory_data' => json_encode($csvData) // Store original CSV data
        ];
        
        return $orderData;
    }
    
    private function generateVehicleDescription($vin)
    {
        // Basic vehicle description based on VIN
        // This is a simplified version - in production you might want to decode the VIN
        $year = $this->getYearFromVIN($vin);
        return "Vehicle VIN: {$vin}" . ($year ? " ({$year})" : "");
    }
    
    private function getYearFromVIN($vin)
    {
        if (strlen($vin) !== 17) {
            return null;
        }
        
        // VIN year codes (simplified - 10th character)
        $yearCodes = [
            'A' => 2010, 'B' => 2011, 'C' => 2012, 'D' => 2013, 'E' => 2014,
            'F' => 2015, 'G' => 2016, 'H' => 2017, 'J' => 2018, 'K' => 2019,
            'L' => 2020, 'M' => 2021, 'N' => 2022, 'P' => 2023, 'R' => 2024,
            'S' => 2025, 'T' => 2026, 'V' => 2027, 'W' => 2028, 'X' => 2029,
            'Y' => 2030
        ];
        
        $yearCode = substr($vin, 9, 1);
        return isset($yearCodes[$yearCode]) ? $yearCodes[$yearCode] : null;
    }
}

// Script ready to run with configured database credentials

// Run the import with configured database credentials
try {
    $importer = new ReconOrderImporter();
    $result = $importer->importFromCSV();
    
    echo "\n🎉 Import completed successfully!\n";
    
} catch (\Exception $e) {
    echo "💥 FATAL ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

?>
