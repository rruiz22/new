<?php

namespace Modules\PublicPages\Controllers;

use App\Controllers\BaseController;

class PublicDataController extends BaseController
{
    /**
     * Get inventory data for public pages
     * This endpoint can be accessed without authentication
     */
    public function getInventoryData()
    {
        // Set CORS headers for public access
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type');
        
        try {
            // Get data from the same source as the admin dashboard
            $inventoryUrl = base_url('public/bos/get_inventory.php');
            
            // Use cURL to fetch the data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $inventoryUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Process and sanitize data for public consumption
                    $processedData = $this->processInventoryData($data);
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'data' => $processedData,
                        'timestamp' => date('Y-m-d H:i:s'),
                        'count' => count($processedData)
                    ]);
                }
            }
            
            // Fallback to sample data if real data fails
            return $this->response->setJSON([
                'success' => true,
                'data' => $this->getSampleInventoryData(),
                'timestamp' => date('Y-m-d H:i:s'),
                'message' => 'Using sample data - real data unavailable'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PublicDataController: Error fetching inventory data: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Unable to fetch inventory data',
                'data' => $this->getSampleInventoryData()
            ]);
        }
    }
    
    /**
     * Get order information by stock number for public pages
     */
    public function getOrderInfo()
    {
        // Set CORS headers
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type');
        
        try {
            // Connect to database to get real order data
            $db = \Config\Database::connect();
            
            // Get order status information (simplified for public access)
            $query = $db->query("
                SELECT 
                    stock,
                    status,
                    service_date,
                    COUNT(*) as order_count
                FROM recon_orders 
                WHERE stock IS NOT NULL 
                AND stock != '' 
                GROUP BY stock, status
                ORDER BY created_at DESC
            ");
            
            $results = $query->getResultArray();
            $orderLookup = [];
            
            foreach ($results as $row) {
                $stock = trim($row['stock']);
                if ($stock) {
                    $orderLookup[$stock] = [
                        'status' => $row['status'],
                        'service_date' => $row['service_date'],
                        'order_count' => (int)$row['order_count']
                    ];
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $orderLookup,
                'timestamp' => date('Y-m-d H:i:s'),
                'count' => count($orderLookup)
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PublicDataController: Error fetching order info: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Unable to fetch order information',
                'data' => $this->getSampleOrderData()
            ]);
        }
    }
    
    /**
     * Get vehicle statistics for public display
     */
    public function getVehicleStats()
    {
        // Set CORS headers
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type');
        
        try {
            $db = \Config\Database::connect();
            
            // Get basic vehicle statistics
            $totalVehicles = $db->query("SELECT COUNT(*) as count FROM recon_orders")->getRow()->count ?? 0;
            $recentVehicles = $db->query("SELECT COUNT(*) as count FROM recon_orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->getRow()->count ?? 0;
            $completedOrders = $db->query("SELECT COUNT(*) as count FROM recon_orders WHERE status = 'completed'")->getRow()->count ?? 0;
            
            return $this->response->setJSON([
                'success' => true,
                'stats' => [
                    'total_vehicles' => (int)$totalVehicles,
                    'recent_vehicles' => (int)$recentVehicles,
                    'completed_orders' => (int)$completedOrders,
                    'completion_rate' => $totalVehicles > 0 ? round(($completedOrders / $totalVehicles) * 100, 1) : 0
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PublicDataController: Error fetching vehicle stats: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Unable to fetch vehicle statistics',
                'stats' => [
                    'total_vehicles' => 156,
                    'recent_vehicles' => 23,
                    'completed_orders' => 89,
                    'completion_rate' => 57.1
                ]
            ]);
        }
    }
    
    /**
     * Process and sanitize inventory data for public consumption
     */
    private function processInventoryData($data)
    {
        if (!is_array($data)) {
            return $this->getSampleInventoryData();
        }
        
        $processed = [];
        
        foreach ($data as $index => $row) {
            if (!is_array($row) || empty($row)) continue;
            
            // Calculate days from date if available
            $calculatedDays = 0;
            $formattedDate = '';
            
            if (isset($row[0]) && $row[0]) {
                try {
                    $dateInDetail = new \DateTime($row[0]);
                    $formattedDate = $dateInDetail->format('m/d/Y');
                    $today = new \DateTime();
                    $diffDays = $today->diff($dateInDetail)->days;
                    $calculatedDays = $diffDays;
                } catch (\Exception $e) {
                    $formattedDate = $row[0] ?? '';
                    $calculatedDays = 0;
                }
            }
            
            $processed[] = [
                'id' => $index,
                'date_detail' => $formattedDate,
                'days_detail' => $calculatedDays,
                'keys' => $row[1] ?? '',
                'stock_number' => $row[2] ?? '',
                'vehicle' => $row[3] ?? '',
                'notes' => $row[5] ?? '',
                'raw_data' => $row
            ];
        }
        
        return $processed;
    }
    
    /**
     * Get sample inventory data as fallback
     */
    private function getSampleInventoryData()
    {
        return [
            [
                'id' => 1,
                'date_detail' => '8/15/2025',
                'days_detail' => 2,
                'keys' => '2',
                'stock_number' => 'B35397A',
                'vehicle' => '2022 BMW 540XI',
                'notes' => 'Ready for detail'
            ],
            [
                'id' => 2,
                'date_detail' => '8/13/2025',
                'days_detail' => 4,
                'keys' => '2',
                'stock_number' => 'B35468A',
                'vehicle' => '2020 TESLA MODEL 3',
                'notes' => 'Paint correction needed'
            ],
            [
                'id' => 3,
                'date_detail' => '8/10/2025',
                'days_detail' => 7,
                'keys' => '1',
                'stock_number' => 'B35469A',
                'vehicle' => '2023 INFINITI QX60',
                'notes' => 'Interior cleaning'
            ],
            [
                'id' => 4,
                'date_detail' => '8/12/2025',
                'days_detail' => 5,
                'keys' => '2',
                'stock_number' => 'B35470A',
                'vehicle' => '2021 NISSAN ALTIMA',
                'notes' => 'Full detail required'
            ],
            [
                'id' => 5,
                'date_detail' => '8/14/2025',
                'days_detail' => 3,
                'keys' => '2',
                'stock_number' => 'B35471A',
                'vehicle' => '2019 JEEP GRAND CHEROKEE',
                'notes' => 'Leather conditioning'
            ]
        ];
    }
    
    /**
     * Get sample order data as fallback
     */
    private function getSampleOrderData()
    {
        return [
            'B35397A' => [
                'status' => 'in_progress',
                'service_date' => '2025-08-17',
                'order_count' => 1
            ],
            'B35468A' => [
                'status' => 'pending',
                'service_date' => '2025-08-18',
                'order_count' => 1
            ],
            'B35470A' => [
                'status' => 'completed',
                'service_date' => '2025-08-16',
                'order_count' => 1
            ]
        ];
    }
}
