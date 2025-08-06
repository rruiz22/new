<?php

namespace Modules\Vehicles\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Class VehiclesController
 * 
 * Controller for managing vehicles independently from recon orders
 */
class VehiclesController extends Controller
{
    protected $request;
    protected $helpers = ['form', 'url', 'auth'];
    protected $db;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
    }

    /**
     * Display vehicles index page
     */
    public function index()
    {
        // Check permissions
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => 'Vehicles Registry',
            'page_title' => 'Vehicles Registry'
        ];

        return view('Modules\Vehicles\Views\vehicles\index', $data);
    }

    /**
     * Display vehicle details
     */
    public function view($id = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        if (!$id) {
            return redirect()->to(base_url('vehicles'));
        }

        // Get vehicle data
        $vehicle = $this->getVehicleData($id);
        
        if (!$vehicle) {
            return redirect()->to(base_url('vehicles'))->with('error', 'Vehicle not found');
        }

        // Redirect to VIN-based URL if VIN is available
        if (!empty($vehicle['vin_number'])) {
            $last6 = substr(strtoupper($vehicle['vin_number']), -6);
            return redirect()->to(base_url('vehicles/' . urlencode($last6)), 301);
        }

        // Fallback if no VIN (shouldn't happen normally)
        $data = [
            'title' => 'Vehicle Details',
            'page_title' => 'Vehicle Details',
            'vehicle' => $vehicle
        ];

        return view('Modules\Vehicles\Views\vehicles\view', $data);
    }

    /**
     * View vehicle by VIN number
     */
    public function viewByVin($vin = null)
    {
        // Enhanced debugging
        log_message('debug', 'VehiclesController::viewByVin called with VIN: ' . ($vin ?? 'NULL'));
        
        if (!auth()->loggedIn()) {
            log_message('debug', 'User not logged in, redirecting to login');
            return redirect()->to(base_url('login'));
        }

        if (!$vin) {
            log_message('debug', 'No VIN provided, redirecting to vehicles index');
            return redirect()->to(base_url('vehicles'))->with('error', 'VIN number is required');
        }

        // Clean and validate VIN
        $originalVin = $vin;
        $vin = strtoupper(trim($vin));
        log_message('debug', 'Original VIN: ' . $originalVin . ', Cleaned VIN: ' . $vin);
        
        // Validate VIN format (basic check)
        if (strlen($vin) !== 17) {
            log_message('debug', 'VIN length validation failed. Length: ' . strlen($vin));
            return redirect()->to(base_url('vehicles'))->with('error', 'Invalid VIN format - must be 17 characters');
        }
        
        if (!preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $vin)) {
            log_message('debug', 'VIN regex validation failed for: ' . $vin);
            return redirect()->to(base_url('vehicles'))->with('error', 'Invalid VIN format - contains invalid characters');
        }
        
        log_message('debug', 'VIN format validation passed for: ' . $vin);
        
        // Find the most recent order for this VIN to get vehicle data
        log_message('debug', 'Searching for VIN in database: ' . $vin);
        
        $vehicle = $this->db->table('recon_orders')
            ->select('recon_orders.id, recon_orders.vehicle')
            ->where('recon_orders.vin_number', $vin)
            ->orderBy('recon_orders.created_at', 'DESC')
            ->get()
            ->getRowArray();

        log_message('debug', 'Database query result: ' . json_encode($vehicle));

        if (!$vehicle) {
            log_message('info', 'Vehicle not found for VIN: ' . $vin);
            
            // Check if VIN exists but with different case or whitespace
            $allVins = $this->db->table('recon_orders')
                ->select('vin_number')
                ->distinct()
                ->get()
                ->getResultArray();
            
            log_message('debug', 'Available VINs in database: ' . json_encode(array_column($allVins, 'vin_number')));
            
            return redirect()->to(base_url('vehicles'))->with('error', 'Vehicle with VIN ' . $vin . ' not found');
        }

        // Get vehicle data using the existing method
        log_message('debug', 'Calling getVehicleData with ID: ' . $vehicle['id']);
        $vehicleData = $this->getVehicleData($vehicle['id']);
        
        if (!$vehicleData) {
            log_message('error', 'getVehicleData returned null for ID: ' . $vehicle['id']);
            return redirect()->to(base_url('vehicles'))->with('error', 'Vehicle data not found');
        }

        log_message('debug', 'Vehicle data retrieved successfully');
        
        $data = [
            'title' => ($vehicle['vehicle'] ?? 'Vehicle') . ' - ' . $vin,
            'page_title' => 'Vehicle Details',
            'vehicle' => $vehicleData,
            'vin' => $vin
        ];

        log_message('debug', 'Returning view for VIN: ' . $vin);
        return view('Modules\Vehicles\Views\vehicles\view', $data);
    }

    /**
     * Debug VIN routing
     */
    public function debugVin($vin = null)
    {
        log_message('debug', 'VehiclesController::debugVin called with: ' . ($vin ?? 'NULL'));
        
        $response = [
            'method' => 'debugVin',
            'received_vin' => $vin,
            'vin_length' => $vin ? strlen($vin) : 0,
            'vin_upper' => $vin ? strtoupper($vin) : null,
            'regex_test' => $vin ? preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', strtoupper($vin)) : false,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Check if VIN exists in database
        if ($vin && strlen($vin) === 17) {
            $vehicle = $this->db->table('recon_orders')
                ->select('id, vin_number, vehicle')
                ->where('vin_number', strtoupper($vin))
                ->get()
                ->getRowArray();
            
            $response['database_found'] = !empty($vehicle);
            $response['vehicle_data'] = $vehicle;
        }
        
        return $this->response->setJSON($response);
    }



    /**
     * Get vehicles data for DataTables
     */
    public function getVehiclesData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        // Debug logging
        log_message('debug', 'VehiclesController::getVehiclesData called');
        
        $draw = $this->request->getPost('draw') ?? 1;
        $start = (int)($this->request->getPost('start') ?? 0);
        $length = (int)($this->request->getPost('length') ?? 10);
        $searchData = $this->request->getPost('search');
        $search = $searchData && isset($searchData['value']) ? $searchData['value'] : '';
        
        log_message('debug', 'Request parameters - draw: ' . $draw . ', start: ' . $start . ', length: ' . $length . ', search: ' . $search);

        try {
            // Check if recon_orders table exists and has data
            if (!$this->db->tableExists('recon_orders')) {
                log_message('error', 'recon_orders table does not exist');
                return $this->response->setJSON([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }

            // Build query with aggregated data
            $builder = $this->db->table('recon_orders');
            $builder->select('
                MIN(id) as id,
                vehicle,
                vin_number,
                COUNT(*) as total_orders,
                MIN(created_at) as first_service,
                MAX(created_at) as last_service,
                MIN(order_number) as first_order_number,
                MAX(order_number) as last_order_number
            ');
            $builder->where('vin_number IS NOT NULL');
            $builder->where('vin_number !=', '');
            $builder->groupBy('vin_number');

            // Get total count before applying search and pagination
            $totalQuery = clone $builder;
            $totalRecords = $totalQuery->countAllResults();
            log_message('debug', 'Total unique vehicles: ' . $totalRecords);

            // Apply search
            if (!empty($search)) {
                $builder->groupStart();
                $builder->like('vehicle', $search);
                $builder->orLike('vin_number', $search);
                $builder->groupEnd();
            }

            // Get filtered count
            $filteredQuery = clone $builder;
            $filteredRecords = $filteredQuery->countAllResults();
            log_message('debug', 'Filtered records: ' . $filteredRecords);

            // Apply ordering and pagination
            $builder->orderBy('MAX(created_at)', 'DESC');
            $data = $builder->limit($length, $start)->get()->getResultArray();
            log_message('debug', 'Data rows returned: ' . count($data));
            
        } catch (Exception $e) {
            log_message('error', 'Database error in getVehiclesData: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Database error: ' . $e->getMessage(),
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Format data for DataTables
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                'id' => $row['id'],
                'vehicle_info' => $row['vehicle'] ?? 'N/A',
                'vin_number' => $row['vin_number'] ?? 'N/A',
                'year' => 'N/A',
                'make' => 'N/A',
                'model' => 'N/A',
                'color' => 'N/A',
                'total_orders' => $row['total_orders'] ?? 0,
                'first_service' => $row['first_service'] ? date('M j, Y', strtotime($row['first_service'])) : 'N/A',
                'last_service' => $row['last_service'] ? date('M j, Y', strtotime($row['last_service'])) : 'N/A',
                'first_order_number' => $row['first_order_number'] ?? '',
                'last_order_number' => $row['last_order_number'] ?? ''
            ];
        }

        return $this->response->setJSON([
            'draw' => (int)$draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $formattedData
        ]);
    }

    /**
     * Get vehicle statistics
     */
    public function getVehicleStats()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        // Total vehicles
        $totalVehicles = $this->db->table('recon_orders')
            ->groupBy('vin_number')
            ->countAllResults();

        // Recent vehicles (last 30 days)
        $recentVehicles = $this->db->table('recon_orders')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->groupBy('vin_number')
            ->countAllResults();

        // Most serviced vehicle
        $mostServiced = $this->db->table('recon_orders')
            ->select('vin_number, vehicle, COUNT(*) as total_orders')
            ->groupBy('vin_number')
            ->orderBy('total_orders', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        // Popular makes (extract from vehicle field)
        $popularMakes = $this->db->table('recon_orders')
            ->select('vehicle, COUNT(DISTINCT vin_number) as vehicle_count')
            ->where('vehicle IS NOT NULL')
            ->where('vehicle !=', '')
            ->groupBy('vehicle')
            ->orderBy('vehicle_count', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'stats' => [
                'total_vehicles' => $totalVehicles,
                'recent_vehicles' => $recentVehicles,
                'most_serviced' => $mostServiced,
                'popular_makes' => $popularMakes
            ]
        ]);
    }

    /**
     * Search vehicles
     */
    public function search()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $search = $this->request->getGet('q');
        
        if (empty($search)) {
            return $this->response->setJSON(['data' => []]);
        }

        $vehicles = $this->db->table('recon_orders')
            ->select('id, vehicle, vin_number')
            ->groupStart()
            ->like('vehicle', $search)
            ->orLike('vin_number', $search)
            ->groupEnd()
            ->groupBy('vin_number')
            ->limit(10)
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $vehicles]);
    }

    /**
     * Get detailed vehicle data
     */
    private function getVehicleData($id)
    {
        // Get vehicle data with client information
        $vehicle = $this->db->table('recon_orders')
            ->select('recon_orders.id, recon_orders.vehicle, recon_orders.vin_number, recon_orders.created_at, recon_orders.updated_at, recon_orders.client_id, clients.name as client_name')
            ->join('clients', 'clients.id = recon_orders.client_id', 'left')
            ->where('recon_orders.id', $id)
            ->get()
            ->getRowArray();

        if (!$vehicle) {
            return null;
        }

        // Get service history for this vehicle with client and service information
        if ($this->db->tableExists('recon_services')) {
            // Check if service_id field exists in recon_orders table
            $fields = $this->db->getFieldData('recon_orders');
            $hasServiceId = false;
            foreach ($fields as $field) {
                if ($field->name === 'service_id') {
                    $hasServiceId = true;
                    break;
                }
            }
            
            if ($hasServiceId) {
                // Try direct relationship first (service_id in recon_orders)
                $services = $this->db->table('recon_orders')
                    ->select('recon_orders.id, recon_orders.order_number, recon_orders.services, recon_orders.status, recon_orders.stock, recon_orders.created_at, recon_orders.updated_at, clients.name as client_name, recon_services.name as service_name, recon_services.color as service_color')
                    ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                    ->join('recon_services', 'recon_services.id = recon_orders.service_id', 'left')
                    ->where('recon_orders.vin_number', $vehicle['vin_number'])
                    ->orderBy('recon_orders.created_at', 'DESC')
                    ->get()
                    ->getResultArray();
            } else {
                // No service_id field, fallback to basic query
                $services = $this->db->table('recon_orders')
                    ->select('recon_orders.id, recon_orders.order_number, recon_orders.services, recon_orders.status, recon_orders.stock, recon_orders.created_at, recon_orders.updated_at, clients.name as client_name')
                    ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                    ->where('recon_orders.vin_number', $vehicle['vin_number'])
                    ->orderBy('recon_orders.created_at', 'DESC')
                    ->get()
                    ->getResultArray();
            }
        } else {
            // Fallback without services table
            $services = $this->db->table('recon_orders')
                ->select('recon_orders.id, recon_orders.order_number, recon_orders.services, recon_orders.status, recon_orders.stock, recon_orders.created_at, recon_orders.updated_at, clients.name as client_name')
                ->join('clients', 'clients.id = recon_orders.client_id', 'left')
                ->where('recon_orders.vin_number', $vehicle['vin_number'])
                ->orderBy('recon_orders.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        // Also get services from many-to-many relationship if table exists
        if ($this->db->tableExists('recon_order_services')) {
            foreach ($services as &$service) {
                $orderServices = $this->db->table('recon_order_services')
                    ->select('recon_services.name as service_name, recon_services.color as service_color, recon_order_services.quantity, recon_order_services.price')
                    ->join('recon_services', 'recon_services.id = recon_order_services.service_id', 'left')
                    ->where('recon_order_services.order_id', $service['id'])
                    ->get()
                    ->getResultArray();
                
                $service['order_services'] = $orderServices;
                $service['service_names'] = array_column($orderServices, 'service_name');
                
                // If no direct service but has many-to-many services, use those
                if (empty($service['service_name']) && !empty($service['service_names'])) {
                    $service['service_name'] = implode(', ', array_filter($service['service_names']));
                }
                
                // Debug log for development
                if (ENVIRONMENT === 'development') {
                    log_message('debug', 'Vehicle Service Debug - Order ID: ' . $service['id'] . 
                               ', Direct Service: ' . ($service['service_name'] ?? 'NULL') . 
                               ', Order Services Count: ' . count($service['order_services'] ?? []) . 
                               ', Services Field: ' . ($service['services'] ?? 'NULL'));
                }
            }
        }

        $vehicle['services'] = $services;
        $vehicle['total_services'] = count($services);

        return $vehicle;
    }

    /**
     * View vehicle by last 6 characters of VIN
     */
    public function viewByVinLast6($last6 = null)
    {
        log_message('debug', 'VehiclesController::viewByVinLast6 called with: ' . ($last6 ?? 'NULL'));
        
        if (!auth()->loggedIn()) {
            log_message('debug', 'User not logged in, redirecting to login');
            return redirect()->to(base_url('login'));
        }

        if (!$last6) {
            log_message('debug', 'No VIN last 6 provided, redirecting to vehicles index');
            return redirect()->to(base_url('vehicles'))->with('error', 'VIN identifier is required');
        }

        $originalLast6 = $last6;
        $last6 = strtoupper(trim($last6));
        log_message('debug', 'Original last6: ' . $originalLast6 . ', Cleaned last6: ' . $last6);

        // Validate format (6 alphanumeric characters)
        if (strlen($last6) !== 6) {
            log_message('debug', 'Last6 length validation failed. Length: ' . strlen($last6));
            return redirect()->to(base_url('vehicles'))->with('error', 'Invalid VIN format - must be 6 characters');
        }

        if (!preg_match('/^[A-Z0-9]{6}$/', $last6)) {
            log_message('debug', 'Last6 regex validation failed for: ' . $last6);
            return redirect()->to(base_url('vehicles'))->with('error', 'Invalid VIN format - contains invalid characters');
        }

        log_message('debug', 'Last6 format validation passed for: ' . $last6);
        log_message('debug', 'Searching for VIN ending with: ' . $last6);

        // Search for vehicle by last 6 characters of VIN (using RIGHT function)
        $vehicle = $this->db->table('recon_orders')
            ->select('recon_orders.id, recon_orders.vehicle, recon_orders.vin_number')
            ->where('RIGHT(recon_orders.vin_number, 6)', $last6)
            ->orderBy('recon_orders.created_at', 'DESC')
            ->get()
            ->getRowArray();

        log_message('debug', 'Database query result: ' . json_encode($vehicle));

        if (!$vehicle) {
            log_message('info', 'Vehicle not found for VIN ending with: ' . $last6);
            // Show available VINs ending with similar pattern for debugging
            $similarVins = $this->db->table('recon_orders')
                ->select('vin_number')
                ->like('vin_number', $last6, 'before')
                ->limit(5)
                ->get()
                ->getResultArray();
            log_message('debug', 'Similar VINs found: ' . json_encode(array_column($similarVins, 'vin_number')));
            return redirect()->to(base_url('vehicles'))->with('error', 'Vehicle with VIN ending in ' . $last6 . ' not found');
        }

        // Get vehicle data using the existing method
        log_message('debug', 'Calling getVehicleData with ID: ' . $vehicle['id']);
        $vehicleData = $this->getVehicleData($vehicle['id']);
        
        if (!$vehicleData) {
            log_message('error', 'getVehicleData returned null for ID: ' . $vehicle['id']);
            return redirect()->to(base_url('vehicles'))->with('error', 'Vehicle data not found');
        }

        log_message('debug', 'Vehicle data retrieved successfully');
        
        $data = [
            'title' => ($vehicle['vehicle'] ?? 'Vehicle') . ' - ' . $vehicle['vin_number'],
            'page_title' => 'Vehicle Details',
            'vehicle' => $vehicleData,
            'vin' => $vehicle['vin_number'], // Full VIN for display
            'last6' => $last6 // Last 6 for URL
        ];

        log_message('debug', 'Returning view for VIN last6: ' . $last6);
        return view('Modules\Vehicles\Views\vehicles\view', $data);
    }

    /**
     * Generate vehicle URL by last 6 characters of VIN
     */
    public static function getVehicleUrl($vin)
    {
        $last6 = substr(strtoupper(trim($vin)), -6);
        return base_url('vehicles/' . urlencode($last6));
    }

    /**
     * Get analytics data for the analytics tab
     */
    public function getAnalyticsData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            // Get data for analytics
            $makeDistribution = $this->db->query("
                SELECT 
                    SUBSTRING_INDEX(vehicle, ' ', 1) as make,
                    COUNT(*) as count
                FROM recon_orders 
                WHERE vehicle IS NOT NULL AND vehicle != ''
                GROUP BY SUBSTRING_INDEX(vehicle, ' ', 1)
                ORDER BY count DESC
                LIMIT 10
            ")->getResultArray();

            $servicesDistribution = $this->db->query("
                SELECT 
                    vin_number,
                    vehicle,
                    COUNT(*) as service_count
                FROM recon_orders 
                WHERE vin_number IS NOT NULL AND vin_number != ''
                GROUP BY vin_number
                ORDER BY service_count DESC
                LIMIT 20
            ")->getResultArray();

            $monthlyAdditions = $this->db->query("
                SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(DISTINCT vin_number) as new_vehicles
                FROM recon_orders 
                WHERE vin_number IS NOT NULL AND vin_number != ''
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC
                LIMIT 12
            ")->getResultArray();

            $topVehicles = $this->db->query("
                SELECT 
                    vehicle,
                    vin_number,
                    SUBSTR(vin_number, -6) as vin_last6,
                    COUNT(*) as total_services
                FROM recon_orders 
                WHERE vin_number IS NOT NULL AND vin_number != ''
                GROUP BY vin_number
                ORDER BY total_services DESC
                LIMIT 10
            ")->getResultArray();

            // Calculate analytics
            $avgAge = $this->db->query("
                SELECT AVG(YEAR(NOW()) - YEAR(created_at)) as avg_age
                FROM recon_orders 
                WHERE vin_number IS NOT NULL
            ")->getRow()->avg_age ?? 0;

            $avgServiceFreq = $this->db->query("
                SELECT AVG(service_count) as avg_freq
                FROM (
                    SELECT COUNT(*) as service_count
                    FROM recon_orders 
                    WHERE vin_number IS NOT NULL
                    GROUP BY vin_number
                ) as subquery
            ")->getRow()->avg_freq ?? 0;

            return $this->response->setJSON([
                'success' => true,
                'makeDistribution' => $makeDistribution,
                'servicesDistribution' => $servicesDistribution,
                'monthlyAdditions' => $monthlyAdditions,
                'topVehicles' => $topVehicles,
                'averageAge' => round($avgAge, 1) . ' years',
                'serviceFrequency' => round($avgServiceFreq, 1) . '/vehicle',
                'locationCoverage' => 75, // Sample data
                'clientRetention' => 85 // Sample data
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting analytics data: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Failed to load analytics data']);
        }
    }

    /**
     * Get all vehicles data for the all vehicles tab
     */
    public function getAllVehiclesData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            log_message('debug', 'getAllVehiclesData: Starting query execution');
            
            // Test if table exists first
            if (!$this->db->tableExists('recon_orders')) {
                log_message('error', 'getAllVehiclesData: recon_orders table does not exist');
                return $this->response->setJSON([
                    'success' => false, 
                    'error' => 'recon_orders table does not exist'
                ]);
            }
            
            // Query with proper JOIN to get client names and aggregated data
            $vehicles = $this->db->query("
                SELECT 
                    ro.vin_number,
                    ro.vehicle,
                    SUBSTR(ro.vin_number, -6) as vin_last6,
                    c.name as client_name,
                    SUBSTRING_INDEX(ro.vehicle, ' ', 1) as make,
                    CASE 
                        WHEN LOCATE(' ', ro.vehicle) > 0 
                        THEN TRIM(SUBSTRING(ro.vehicle, LOCATE(' ', ro.vehicle) + 1))
                        ELSE ''
                    END as model,
                    YEAR(ro.created_at) as year,
                    COUNT(*) as total_services,
                    DATE_FORMAT(MIN(ro.created_at), '%Y-%m-%d') as first_service_date,
                    DATE_FORMAT(MAX(ro.created_at), '%Y-%m-%d') as last_service_date,
                    MIN(ro.order_number) as first_order_number,
                    MAX(ro.order_number) as last_order_number,
                    0 as location_tracking_count
                FROM recon_orders ro
                LEFT JOIN clients c ON ro.client_id = c.id
                WHERE ro.vin_number IS NOT NULL 
                AND ro.vin_number != ''
                GROUP BY ro.vin_number, c.name
                ORDER BY MAX(ro.created_at) DESC
            ")->getResultArray();
            
            log_message('debug', 'getAllVehiclesData: Enhanced query executed, found ' . count($vehicles) . ' vehicles');
            
            return $this->response->setJSON([
                'success' => true,
                'vehicles' => $vehicles,
                'count' => count($vehicles)
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting all vehicles data: ' . $e->getMessage());
            log_message('error', 'Error trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false, 
                'error' => 'Failed to load vehicles data',
                'debug' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get dashboard data
     */
    public function getDashboardData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            log_message('debug', 'getDashboardData: Starting dashboard data collection');

            // Get total vehicles
            $totalVehicles = $this->db->query("
                SELECT COUNT(DISTINCT vin_number) as total
                FROM recon_orders 
                WHERE vin_number IS NOT NULL AND vin_number != ''
            ")->getRow()->total ?? 0;

            // Get active vehicles (with services in last 30 days)
            $activeVehicles = $this->db->query("
                SELECT COUNT(DISTINCT vin_number) as active
                FROM recon_orders 
                WHERE vin_number IS NOT NULL AND vin_number != ''
                AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ")->getRow()->active ?? 0;

            // Get recent vehicles (last 30 days)
            $recentCount = $this->db->query("
                SELECT COUNT(DISTINCT vin_number) as recent
                FROM recon_orders 
                WHERE vin_number IS NOT NULL AND vin_number != ''
                AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ")->getRow()->recent ?? 0;

            // Get total services
            $totalServices = $this->db->query("
                SELECT COUNT(*) as total
                FROM recon_orders
            ")->getRow()->total ?? 0;

            // Get location tracking count (if table exists)
            $locationTracked = 0;
            if ($this->db->tableExists('vehicle_locations')) {
                $locationTracked = $this->db->query("
                    SELECT COUNT(DISTINCT SUBSTR(vin_number, -6)) as tracked
                    FROM vehicle_locations 
                    WHERE vin_number IS NOT NULL AND vin_number != ''
                ")->getRow()->tracked ?? 0;
            }

            // Get recent activity
            $recentActivity = $this->db->query("
                SELECT 
                    ro.vin_number,
                    ro.vehicle,
                    SUBSTR(ro.vin_number, -6) as vin_last6,
                    c.name as client_name,
                    ro.order_number,
                    DATE_FORMAT(ro.created_at, '%Y-%m-%d') as service_date,
                    ro.status,
                    0 as has_location_tracking
                FROM recon_orders ro
                LEFT JOIN clients c ON ro.client_id = c.id
                WHERE ro.vin_number IS NOT NULL AND ro.vin_number != ''
                ORDER BY ro.created_at DESC
                LIMIT 10
            ")->getResultArray();

            // Get top clients
            $topClients = $this->db->query("
                SELECT 
                    c.name,
                    COUNT(DISTINCT ro.vin_number) as vehicle_count,
                    COUNT(*) as total_services
                FROM clients c
                INNER JOIN recon_orders ro ON c.id = ro.client_id
                WHERE ro.vin_number IS NOT NULL AND ro.vin_number != ''
                GROUP BY c.id, c.name
                ORDER BY total_services DESC
                LIMIT 10
            ")->getResultArray();

            // Calculate additional stats
            $averageServices = $totalVehicles > 0 ? round($totalServices / $totalVehicles, 1) : 0;
            $locationTrackingPercentage = $totalVehicles > 0 ? round(($locationTracked / $totalVehicles) * 100) : 0;

            // Get most popular make
            $mostPopularMake = $this->db->query("
                SELECT SUBSTRING_INDEX(vehicle, ' ', 1) as make, COUNT(*) as count
                FROM recon_orders 
                WHERE vehicle IS NOT NULL AND vehicle != ''
                GROUP BY SUBSTRING_INDEX(vehicle, ' ', 1)
                ORDER BY count DESC
                LIMIT 1
            ")->getRow()->make ?? 'Unknown';

            // Get newest year
            $newestYear = $this->db->query("
                SELECT YEAR(MAX(created_at)) as year
                FROM recon_orders
            ")->getRow()->year ?? date('Y');

            return $this->response->setJSON([
                'success' => true,
                'totalVehicles' => $totalVehicles,
                'activeVehicles' => $activeVehicles,
                'locationTracked' => $locationTracked,
                'totalServices' => $totalServices,
                'recentCount' => $recentCount,
                'activeCount' => $activeVehicles,
                'locationTrackingCount' => $locationTracked,
                'averageServices' => $averageServices,
                'mostPopularMake' => $mostPopularMake,
                'newestYear' => $newestYear,
                'locationTrackingPercentage' => $locationTrackingPercentage,
                'recentActivity' => $recentActivity,
                'topClients' => $topClients
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting dashboard data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'error' => 'Failed to load dashboard data',
                'debug' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get client filter options
     */
    public function getClientFilterOptions()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            $clients = $this->db->query("
                SELECT DISTINCT c.name
                FROM clients c
                INNER JOIN recon_orders ro ON c.id = ro.client_id
                WHERE ro.vin_number IS NOT NULL AND ro.vin_number != ''
                ORDER BY c.name
            ")->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'clients' => $clients
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting client filter options: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Failed to load client options']);
        }
    }

    /**
     * Get make filter options
     */
    public function getMakeFilterOptions()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            $makes = $this->db->query("
                SELECT DISTINCT SUBSTRING_INDEX(vehicle, ' ', 1) as make
                FROM recon_orders 
                WHERE vehicle IS NOT NULL AND vehicle != ''
                ORDER BY make
            ")->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'makes' => $makes
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting make filter options: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Failed to load make options']);
        }
    }

    /**
     * Get year filter options
     */
    public function getYearFilterOptions()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            $years = $this->db->query("
                SELECT DISTINCT YEAR(created_at) as year
                FROM recon_orders
                ORDER BY year DESC
            ")->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'years' => $years
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting year filter options: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Failed to load year options']);
        }
    }

    /**
     * Generate NFC token for a vehicle
     */
    public function generateNfcToken()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        $vinLast6 = $this->request->getPost('vin_last6');
        
        if (!$vinLast6) {
            return $this->response->setJSON(['success' => false, 'error' => 'VIN required']);
        }

        try {
            // Generate a unique token
            $token = 'VHC_' . strtoupper($vinLast6) . '_' . date('YmdHis') . '_' . substr(md5(uniqid()), 0, 8);
            
            return $this->response->setJSON([
                'success' => true,
                'token' => $token,
                'qr_url' => base_url('vehicles/v/' . $vinLast6) . '?token=' . $token
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error generating NFC token: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'error' => 'Failed to generate token']);
        }
    }

    /**
     * Export vehicle data
     */
    public function exportVehicleData($vinLast6 = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        if (!$vinLast6) {
            return redirect()->to(base_url('vehicles'))->with('error', 'VIN required');
        }

        try {
            // This would typically generate a CSV or Excel file
            // For now, return a simple response
            $this->response->setHeader('Content-Type', 'application/json');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Export functionality will be implemented',
                'vin' => $vinLast6
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error exporting vehicle data: ' . $e->getMessage());
            return redirect()->to(base_url('vehicles'))->with('error', 'Export failed');
        }
    }

    /**
     * Export all vehicles data
     */
    public function exportAllVehicles()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        try {
            // This would typically generate a CSV or Excel file
            // For now, return a simple response
            $this->response->setHeader('Content-Type', 'application/json');
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Export all functionality will be implemented'
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error exporting all vehicles: ' . $e->getMessage());
            return redirect()->to(base_url('vehicles'))->with('error', 'Export failed');
        }
    }

    /**
     * Get recent vehicles data (last 30 days)
     */
    public function getRecentVehiclesData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            log_message('debug', 'getRecentVehiclesData: Starting query execution');

            $vehicles = $this->db->query("
                SELECT 
                    ro.vin_number,
                    ro.vehicle,
                    SUBSTR(ro.vin_number, -6) as vin_last6,
                    c.name as client_name,
                    DATE_FORMAT(MIN(ro.created_at), '%Y-%m-%d') as added_date,
                    DATE_FORMAT(MIN(ro.created_at), '%Y-%m-%d') as first_service_date,
                    MIN(ro.order_number) as first_order_number
                FROM recon_orders ro
                LEFT JOIN clients c ON ro.client_id = c.id
                WHERE ro.vin_number IS NOT NULL 
                AND ro.vin_number != ''
                AND ro.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY ro.vin_number, c.name
                ORDER BY MIN(ro.created_at) DESC
            ")->getResultArray();

            log_message('debug', 'getRecentVehiclesData: Found ' . count($vehicles) . ' recent vehicles');

            return $this->response->setJSON([
                'success' => true,
                'vehicles' => $vehicles,
                'count' => count($vehicles)
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting recent vehicles data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'error' => 'Failed to load recent vehicles data',
                'debug' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get active vehicles data (vehicles with recent services)
     */
    public function getActiveVehiclesData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            log_message('debug', 'getActiveVehiclesData: Starting query execution');

            $vehicles = $this->db->query("
                SELECT 
                    ro.vin_number,
                    ro.vehicle,
                    SUBSTR(ro.vin_number, -6) as vin_last6,
                    c.name as client_name,
                    COUNT(*) as total_services,
                    DATE_FORMAT(MAX(ro.created_at), '%Y-%m-%d') as last_service_date,
                    MAX(ro.order_number) as last_order_number
                FROM recon_orders ro
                LEFT JOIN clients c ON ro.client_id = c.id
                WHERE ro.vin_number IS NOT NULL 
                AND ro.vin_number != ''
                AND ro.created_at >= DATE_SUB(NOW(), INTERVAL 90 DAY)
                GROUP BY ro.vin_number, c.name
                HAVING COUNT(*) >= 2
                ORDER BY MAX(ro.created_at) DESC
            ")->getResultArray();

            log_message('debug', 'getActiveVehiclesData: Found ' . count($vehicles) . ' active vehicles');

            return $this->response->setJSON([
                'success' => true,
                'vehicles' => $vehicles,
                'count' => count($vehicles)
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting active vehicles data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'error' => 'Failed to load active vehicles data',
                'debug' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get location tracking data
     */
    public function getLocationTrackingData()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }

        try {
            log_message('debug', 'getLocationTrackingData: Starting query execution');

            // Check if vehicle_locations table exists
            $hasLocationTable = $this->db->tableExists('vehicle_locations');
            
            if (!$hasLocationTable) {
                // Return empty data if location table doesn't exist
                return $this->response->setJSON([
                    'success' => true,
                    'vehicles' => [],
                    'count' => 0,
                    'message' => 'Location tracking table not found'
                ]);
            }

            $vehicles = $this->db->query("
                SELECT 
                    ro.vin_number,
                    ro.vehicle,
                    SUBSTR(ro.vin_number, -6) as vin_last6,
                    c.name as client_name,
                    COUNT(vl.id) as total_locations,
                    DATE_FORMAT(MAX(vl.created_at), '%Y-%m-%d %H:%i') as last_location_update
                FROM recon_orders ro
                LEFT JOIN clients c ON ro.client_id = c.id
                INNER JOIN vehicle_locations vl ON SUBSTR(ro.vin_number, -6) COLLATE utf8mb4_general_ci = SUBSTR(vl.vin_number, -6) COLLATE utf8mb4_general_ci
                WHERE ro.vin_number IS NOT NULL 
                AND ro.vin_number != ''
                GROUP BY ro.vin_number, c.name
                ORDER BY MAX(vl.created_at) DESC
            ")->getResultArray();

            log_message('debug', 'getLocationTrackingData: Found ' . count($vehicles) . ' vehicles with location data');

            return $this->response->setJSON([
                'success' => true,
                'vehicles' => $vehicles,
                'count' => count($vehicles)
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting location tracking data: ' . $e->getMessage());
            
            // If collation error, try with binary comparison as fallback
            if (strpos($e->getMessage(), 'collation') !== false) {
                try {
                    log_message('debug', 'Trying fallback query with binary comparison for location tracking');
                    
                    $vehicles = $this->db->query("
                        SELECT 
                            ro.vin_number,
                            ro.vehicle,
                            SUBSTR(ro.vin_number, -6) as vin_last6,
                            c.name as client_name,
                            COUNT(vl.id) as total_locations,
                            DATE_FORMAT(MAX(vl.created_at), '%Y-%m-%d %H:%i') as last_location_update
                        FROM recon_orders ro
                        LEFT JOIN clients c ON ro.client_id = c.id
                        INNER JOIN vehicle_locations vl ON BINARY SUBSTR(ro.vin_number, -6) = BINARY SUBSTR(vl.vin_number, -6)
                        WHERE ro.vin_number IS NOT NULL 
                        AND ro.vin_number != ''
                        GROUP BY ro.vin_number, c.name
                        ORDER BY MAX(vl.created_at) DESC
                    ")->getResultArray();

                    log_message('debug', 'getLocationTrackingData: Fallback query found ' . count($vehicles) . ' vehicles with location data');

                    return $this->response->setJSON([
                        'success' => true,
                        'vehicles' => $vehicles,
                        'count' => count($vehicles),
                        'note' => 'Used binary comparison fallback'
                    ]);
                    
                } catch (Exception $fallbackError) {
                    log_message('error', 'Fallback query also failed: ' . $fallbackError->getMessage());
                }
            }
            
            return $this->response->setJSON([
                'success' => false, 
                'error' => 'Failed to load location tracking data',
                'debug' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get photos URL for a vehicle
     */
    public function getPhotosUrl($vinLast6)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        try {
            // Get the full VIN from the last 6 characters
            $query = $this->db->query(
                "SELECT vin_number FROM recon_orders WHERE SUBSTR(vin_number, -6) = ? LIMIT 1",
                [$vinLast6]
            );
            $result = $query->getRowArray();
            
            if (!$result) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Vehicle not found'
                ]);
            }

            $fullVin = $result['vin_number'];

            // Get photos URL from recon_vehicles table
            $vehicleQuery = $this->db->query(
                "SELECT photos_url FROM recon_vehicles WHERE vin_number = ? LIMIT 1",
                [$fullVin]
            );
            $vehicleResult = $vehicleQuery->getRowArray();

            $photosUrl = $vehicleResult['photos_url'] ?? '';

            return $this->response->setJSON([
                'success' => true,
                'photos_url' => $photosUrl,
                'vin_number' => $fullVin
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error getting photos URL: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to get photos URL'
            ]);
        }
    }

    /**
     * Save photos URL for a vehicle (Staff/Admin only)
     */
    public function savePhotosUrl($vinLast6)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        // Check if user is staff or admin
        $user = auth()->user();
        if (!$user || !in_array($user->user_type, ['staff', 'admin'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Insufficient permissions. Only staff and admin can edit photos URL.'
            ]);
        }

        $photosUrl = $this->request->getPost('photos_url');
        
        // Validate URL if provided
        if (!empty($photosUrl) && !filter_var($photosUrl, FILTER_VALIDATE_URL)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid URL format'
            ]);
        }

        try {
            // Get the full VIN from the last 6 characters
            $query = $this->db->query(
                "SELECT vin_number FROM recon_orders WHERE SUBSTR(vin_number, -6) = ? LIMIT 1",
                [$vinLast6]
            );
            $result = $query->getRowArray();
            
            if (!$result) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Vehicle not found'
                ]);
            }

            $fullVin = $result['vin_number'];

            // Check if vehicle exists in recon_vehicles table
            $vehicleQuery = $this->db->query(
                "SELECT id FROM recon_vehicles WHERE vin_number = ? LIMIT 1",
                [$fullVin]
            );
            $vehicleResult = $vehicleQuery->getRowArray();

            if ($vehicleResult) {
                // Update existing record
                $this->db->query(
                    "UPDATE recon_vehicles SET photos_url = ?, updated_at = NOW() WHERE vin_number = ?",
                    [$photosUrl, $fullVin]
                );
            } else {
                // Create new vehicle record
                // Get vehicle info from recon_orders
                $orderQuery = $this->db->query(
                    "SELECT vehicle FROM recon_orders WHERE vin_number = ? LIMIT 1",
                    [$fullVin]
                );
                $orderResult = $orderQuery->getRowArray();
                $vehicleInfo = $orderResult['vehicle'] ?? 'Unknown Vehicle';

                $this->db->query(
                    "INSERT INTO recon_vehicles (vin_number, vehicle_info, photos_url, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
                    [$fullVin, $vehicleInfo, $photosUrl]
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Photos URL saved successfully',
                'photos_url' => $photosUrl
            ]);

        } catch (Exception $e) {
            log_message('error', 'Error saving photos URL: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to save photos URL'
            ]);
        }
    }

    // ==================== AWS S3 METHODS ====================

    /**
     * Get vehicle photos from S3
     */
    public function getS3VehiclePhotos($vinNumber = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        if (empty($vinNumber)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN number is required'
            ]);
        }

        try {
            // Try to initialize S3 Service
            try {
                $s3Service = new \App\Libraries\S3Service();
            } catch (\Exception $e) {
                log_message('error', 'S3 Service initialization failed: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'S3 Configuration Error: ' . $e->getMessage(),
                    'files' => [],
                    'photos' => [],
                ]);
            }
            
            $folderPath = $s3Service->getVehiclePath($vinNumber);
            log_message('debug', "S3 Photos: Looking for photos in path: {$folderPath}");
            
            $result = $s3Service->listFiles($folderPath);
            log_message('debug', "S3 Photos: List files result: " . json_encode($result));
            
            if ($result['success']) {
                // Format files for frontend
                $photos = [];
                foreach ($result['files'] as $file) {
                    $fileName = basename($file['key']);
                    $photos[] = [
                        'id' => md5($file['key']),
                        'name' => $fileName,
                        'size' => $file['size'],
                        'url' => $file['url'],
                        'thumbnail' => $file['url'], // S3 handles this via query params
                        'uploaded_at' => $file['modified'],
                        'key' => $file['key'],
                    ];
                }

                // Sort by upload date (oldest first)
                usort($photos, function($a, $b) {
                    return strtotime($a['uploaded_at']) - strtotime($b['uploaded_at']);
                });

                return $this->response->setJSON([
                    'success' => true,
                    'files' => $photos,
                    'photos' => $photos,
                    'count' => count($photos),
                    'folder_path' => $folderPath,
                ]);
            } else {
                log_message('warning', "S3 Photos: Failed to list files for VIN {$vinNumber} in path {$folderPath}. Error: " . ($result['error'] ?? 'Unknown error'));
                
                return $this->response->setJSON([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to list photos',
                    'files' => [],
                    'photos' => [],
                    'debug_info' => [
                        'vin' => $vinNumber,
                        'folder_path' => $folderPath,
                        'bucket' => $s3Service->getPublicUrl('test'), // This will show the bucket in the URL
                        'help' => 'Check if files exist in the S3 bucket at the specified path'
                    ]
                ]);
            }

        } catch (Exception $e) {
            log_message('error', 'S3 get photos error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to retrieve photos: ' . $e->getMessage(),
                'files' => [],
                'photos' => [],
            ]);
        }
    }

    /**
     * Upload photos to S3
     */
    public function uploadToS3()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $vinNumber = $this->request->getPost('vin_number');
        if (empty($vinNumber)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN number is required'
            ]);
        }

        $files = $this->request->getFiles();
        if (empty($files['photos'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No photos provided'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            $folderPath = $s3Service->getVehiclePath($vinNumber);
            $thumbnailPath = $s3Service->getThumbnailPath($vinNumber);
            
            $uploadedFiles = [];
            $failedFiles = [];

            foreach ($files['photos'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getName();
                    $fileExtension = $file->getExtension();
                    
                    // Validate file type
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                        $failedFiles[] = ['file' => $fileName, 'error' => 'Invalid file type'];
                        continue;
                    }

                    // Generate unique filename
                    $timestamp = date('Y-m-d_H-i-s');
                    $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $fileExtension;
                    $s3Key = $folderPath . $uniqueFileName;
                    
                    // Upload original
                    $uploadResult = $s3Service->uploadFile($file->getTempName(), $s3Key, $file->getMimeType());
                    
                    if ($uploadResult['success']) {
                        // Create thumbnail
                        $thumbnailKey = $thumbnailPath . 'thumb_' . $uniqueFileName;
                        $thumbnailResult = $s3Service->createThumbnail($s3Key, $thumbnailKey);
                        
                        $uploadedFiles[] = [
                            'id' => md5($s3Key),
                            'original_name' => $fileName,
                            'stored_name' => $uniqueFileName,
                            'url' => $uploadResult['url'],
                            'thumbnail' => $thumbnailResult['success'] ? $thumbnailResult['url'] : $uploadResult['url'],
                            'size' => $file->getSize(),
                            'key' => $s3Key,
                            'uploaded_at' => date('Y-m-d H:i:s'),
                        ];
                    } else {
                        $failedFiles[] = ['file' => $fileName, 'error' => $uploadResult['error']];
                    }
                } else {
                    $failedFiles[] = ['file' => $file->getName(), 'error' => 'Invalid file or already moved'];
                }
            }

            return $this->response->setJSON([
                'success' => count($uploadedFiles) > 0,
                'uploadedFiles' => $uploadedFiles,
                'uploaded_files' => $uploadedFiles,
                'failed_files' => $failedFiles,
                'total_uploaded' => count($uploadedFiles),
                'total_failed' => count($failedFiles),
            ]);

        } catch (Exception $e) {
            log_message('error', 'S3 upload error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete photo from S3
     */
    public function deleteS3Photo()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $s3Key = $this->request->getPost('s3_key');
        if (empty($s3Key)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'S3 key is required'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            $result = $s3Service->deleteFile($s3Key);
            
            // Also try to delete thumbnail if exists
            if ($result['success']) {
                $pathInfo = pathinfo($s3Key);
                $thumbnailKey = str_replace('/vehicles/', '/thumbnails/', $pathInfo['dirname']) . '/thumb_' . $pathInfo['basename'];
                $s3Service->deleteFile($thumbnailKey); // Don't check result, thumbnail might not exist
            }

            return $this->response->setJSON($result);

        } catch (Exception $e) {
            log_message('error', 'S3 delete error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Delete failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle batch upload from JavaScript
     */
    public function batchUploadS3()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $input = $this->request->getJSON(true);
        $vinNumber = $input['vin_number'] ?? null;
        $files = $input['files'] ?? [];

        if (empty($vinNumber)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN number is required'
            ]);
        }

        if (empty($files)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No files provided'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            
            // Process files from base64
            $processedFiles = [];
            foreach ($files as $file) {
                $base64Data = $file['data'];
                $fileName = $file['name'];
                $mimeType = $file['type'];
                
                // Decode base64
                if (preg_match('/^data:([^;]+);base64,(.+)$/', $base64Data, $matches)) {
                    $content = base64_decode($matches[2]);
                    $processedFiles[] = [
                        'name' => $fileName,
                        'content' => $content,
                        'mime_type' => $mimeType,
                    ];
                }
            }

            $result = $s3Service->batchUpload($processedFiles, $vinNumber);
            return $this->response->setJSON($result);

        } catch (Exception $e) {
            log_message('error', 'S3 batch upload error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Batch upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Test S3 configuration with detailed diagnostics
     */
    public function testS3Config()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $diagnostics = [];
        
        try {
            // Check AWS configuration
            $awsConfig = new \Config\AWS();
            $diagnostics['aws_config'] = [
                'access_key_configured' => !empty($awsConfig->s3['credentials']['key']) && $awsConfig->s3['credentials']['key'] !== 'YOUR_AWS_ACCESS_KEY_HERE',
                'secret_key_configured' => !empty($awsConfig->s3['credentials']['secret']) && $awsConfig->s3['credentials']['secret'] !== 'YOUR_AWS_SECRET_KEY_HERE',
                'bucket_configured' => !empty($awsConfig->s3['bucket']),
                'region' => $awsConfig->s3['region'],
                'bucket' => $awsConfig->s3['bucket']
            ];

            // Test S3 Service initialization
            $s3Service = new \App\Libraries\S3Service();
            $diagnostics['s3_service'] = ['initialized' => true];
            
            // Test bucket access
            $result = $s3Service->listFiles('', 5);
            $diagnostics['bucket_access'] = [
                'can_list_files' => $result['success'],
                'total_files' => $result['count'] ?? 0,
                'error' => $result['error'] ?? null
            ];
            
            // Test vehicle folder structure
            $testVin = '5UXCR6C04L9C91038'; // VIN from the error logs
            $vehiclePath = $s3Service->getVehiclePath($testVin);
            $vehicleFiles = $s3Service->listFiles($vehiclePath);
            
            $diagnostics['vehicle_folder'] = [
                'path' => $vehiclePath,
                'exists' => $vehicleFiles['success'],
                'files_count' => $vehicleFiles['count'] ?? 0,
                'files' => $vehicleFiles['files'] ?? [],
                'error' => $vehicleFiles['error'] ?? null
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'S3 diagnostic completed',
                'diagnostics' => $diagnostics
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'S3 Test Config Error: ' . $e->getMessage());
            $diagnostics['error'] = $e->getMessage();
            
            return $this->response->setJSON([
                'success' => false,
                'error' => 'S3 Configuration Error: ' . $e->getMessage(),
                'diagnostics' => $diagnostics,
                'help' => [
                    'Make sure .env file exists with AWS credentials',
                    'Check AWS_ACCESS_KEY_ID is set',
                    'Check AWS_SECRET_ACCESS_KEY is set',
                    'Check S3_BUCKET is set',
                    'Verify AWS credentials have S3 permissions',
                    'Check bucket exists and has proper CORS configuration'
                ]
            ]);
        }
    }

    /**
     * Upload photos to S3 for a specific vehicle
     */
    public function uploadS3VehiclePhotos($vinNumber = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        if (empty($vinNumber)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN number is required'
            ]);
        }

        $files = $this->request->getFiles();
        
        if (empty($files['photos'])) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No photos provided'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            
            // Process uploaded files
            $processedFiles = [];
            foreach ($files['photos'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getName();
                    $fileExtension = $file->getExtension();
                    
                    // Validate file type
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array(strtolower($fileExtension), $allowedTypes)) {
                        continue; // Skip invalid files
                    }

                    // Read file content
                    $fileContent = file_get_contents($file->getTempName());
                    $mimeType = $file->getMimeType();

                    $processedFiles[] = [
                        'name' => $fileName,
                        'content' => $fileContent,
                        'mime_type' => $mimeType
                    ];
                }
            }

            if (empty($processedFiles)) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No valid image files found'
                ]);
            }

            // Use S3Service batch upload
            $result = $s3Service->batchUpload($processedFiles, $vinNumber);

            $response = [
                'success' => $result['success'],
                'uploaded_files' => $result['uploaded_files'],
                'failed_files' => $result['failed_files'],
                'total_uploaded' => $result['total_uploaded'],
                'total_failed' => $result['total_failed'],
                'message' => $result['success'] ? 
                    "Successfully uploaded {$result['total_uploaded']} photo(s) to S3" : 
                    "Upload failed"
            ];

            // Auto-detect duplicates after successful upload
            if ($result['success'] && $result['total_uploaded'] > 0) {
                try {
                    $s3Response = $s3Service->listFiles("vehicles/" . strtoupper($vinNumber));
                    if ($s3Response['success']) {
                        $photos = $s3Response['files'] ?? [];
                        if (count($photos) > 1) { // Only check if there are multiple photos
                            $duplicates = $this->findDuplicatePhotos($photos, $s3Service);
                            if (!empty($duplicates)) {
                                $response['duplicates_detected'] = true;
                                $response['duplicate_groups'] = count($duplicates);
                                $response['message'] .= " Warning: {$response['duplicate_groups']} group(s) of duplicate photos detected.";
                            } else {
                                $response['duplicates_detected'] = false;
                            }
                        }
                    }
                } catch (Exception $e) {
                    // Don't fail the upload if duplicate detection fails
                    log_message('warning', 'Auto-duplicate detection failed after upload: ' . $e->getMessage());
                    $response['duplicates_detected'] = null;
                }
            }

            return $this->response->setJSON($response);

        } catch (Exception $e) {
            log_message('error', 'S3 upload error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Debug duplicate detection (shows detailed info)
     */
    public function debugDuplicatePhotos($vinNumber = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        if (!$vinNumber) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN number is required'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            $s3Response = $s3Service->listFiles("vehicles/" . strtoupper($vinNumber));
            
            if (!$s3Response['success']) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Failed to list photos: ' . ($s3Response['error'] ?? 'Unknown error')
                ]);
            }
            
            $photos = $s3Response['files'] ?? [];
            
            // Return debug info
            return $this->response->setJSON([
                'success' => true,
                'debug_info' => [
                    'total_photos' => count($photos),
                    'photos' => $photos,
                    'sample_etags' => array_slice(array_column($photos, 'etag'), 0, 5),
                    'sample_sizes' => array_slice(array_column($photos, 'size'), 0, 5),
                    'sample_filenames' => array_slice(array_column($photos, 'key'), 0, 5)
                ]
            ]);

        } catch (Exception $e) {
            log_message('error', 'Debug duplicate detection error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Debug failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Detect duplicate photos in S3
     */
    public function detectDuplicatePhotos($vinNumber = null)
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        if (!$vinNumber) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'VIN number is required'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            $s3Response = $s3Service->listFiles("vehicles/" . strtoupper($vinNumber));
            
            // Check if S3 operation failed
            if (!$s3Response['success']) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Failed to list photos: ' . ($s3Response['error'] ?? 'Unknown error')
                ]);
            }
            
            $photos = $s3Response['files'] ?? [];
            
            if (empty($photos)) {
                return $this->response->setJSON([
                    'success' => true,
                    'duplicates' => [],
                    'message' => 'No photos found to check for duplicates'
                ]);
            }

            $duplicates = $this->findDuplicatePhotos($photos, $s3Service);
            
            return $this->response->setJSON([
                'success' => true,
                'duplicates' => $duplicates,
                'total_photos' => count($photos),
                'duplicate_groups' => count($duplicates)
            ]);

        } catch (Exception $e) {
            log_message('error', 'Duplicate detection error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Duplicate detection failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove duplicate photos from S3
     */
    public function removeDuplicatePhotos()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Authentication required'
            ]);
        }

        $photoKeys = $this->request->getPost('photo_keys');
        if (empty($photoKeys) || !is_array($photoKeys)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Photo keys are required'
            ]);
        }

        try {
            $s3Service = new \App\Libraries\S3Service();
            $removed = 0;
            $errors = [];

            foreach ($photoKeys as $key) {
                $result = $s3Service->deleteFile($key);
                if ($result['success']) {
                    $removed++;
                    
                    // Also try to delete thumbnail
                    $pathInfo = pathinfo($key);
                    $thumbnailKey = str_replace('/vehicles/', '/thumbnails/', $pathInfo['dirname']) . '/thumb_' . $pathInfo['basename'];
                    $s3Service->deleteFile($thumbnailKey);
                } else {
                    $errors[] = "Failed to delete: " . basename($key);
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'removed' => $removed,
                'total_requested' => count($photoKeys),
                'errors' => $errors
            ]);

        } catch (Exception $e) {
            log_message('error', 'Remove duplicates error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Remove duplicates failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Find EXACT duplicate photos only (same filename AND same size)
     */
    private function findDuplicatePhotos($photos, $s3Service)
    {
        $duplicateGroups = [];

        log_message('info', 'Starting EXACT duplicate detection for ' . count($photos) . ' photos');

        // Normalize photo data for consistent processing
        $normalizedPhotos = [];
        foreach ($photos as $photo) {
            // Validate photo structure
            if (!is_array($photo) || !isset($photo['key']) || empty($photo['key'])) {
                log_message('warning', 'Invalid photo structure, skipping: ' . json_encode($photo));
                continue;
            }
            
            $normalizedPhoto = [
                'key' => $photo['key'],
                'size' => $photo['size'] ?? 0,
                'uploaded_at' => $photo['modified'] ?? date('Y-m-d H:i:s'),
                'public_url' => $photo['url'] ?? '',
                'thumbnail_url' => $this->getThumbnailUrl($photo['key']),
                'filename' => basename($photo['key']),
                'etag' => $photo['etag'] ?? '',
            ];
            $normalizedPhotos[] = $normalizedPhoto;
        }
        
        if (empty($normalizedPhotos)) {
            log_message('warning', 'No valid photos to process for duplicate detection');
            return [];
        }

        // METHOD 1: ETag comparison (most reliable for S3 - identical files)
        log_message('info', 'Method 1: ETag comparison (identical files)');
        $etagGroups = $this->groupByETag($normalizedPhotos);
        foreach ($etagGroups as $etag => $etagGroup) {
            if (count($etagGroup) >= 2) {
                $duplicateGroups[] = [
                    'detection_method' => 'etag_identical',
                    'etag' => $etag,
                    'photos' => $etagGroup
                ];
                log_message('info', 'Found ETag duplicates: ' . $etag . ' (' . count($etagGroup) . ' photos)');
            }
        }

        // METHOD 2: Exact filename AND exact size match
        log_message('info', 'Method 2: Exact filename AND size comparison');
        $exactDuplicates = $this->groupByExactFilenameAndSize($normalizedPhotos);
        foreach ($exactDuplicates as $key => $duplicateGroup) {
            if (count($duplicateGroup) >= 2) {
                // Skip if already found by ETag
                if (!$this->isGroupAlreadyFound($duplicateGroup, $duplicateGroups)) {
                    $duplicateGroups[] = [
                        'detection_method' => 'exact_filename_and_size',
                        'filename' => $duplicateGroup[0]['filename'],
                        'size' => $duplicateGroup[0]['size'],
                        'photos' => $duplicateGroup
                    ];
                    log_message('info', 'Found exact duplicates: ' . $duplicateGroup[0]['filename'] . ' (' . count($duplicateGroup) . ' photos)');
                }
            }
        }

        log_message('info', 'EXACT duplicate detection complete. Found ' . count($duplicateGroups) . ' groups');
        return $duplicateGroups;
    }

    /**
     * Group photos by name similarity
     */
    private function groupByNameSimilarity($photos)
    {
        $groups = [];
        
        foreach ($photos as $photo) {
            $basename = pathinfo($photo['key'], PATHINFO_FILENAME);
            $cleanName = $this->cleanFilename($basename);
            
            if (!isset($groups[$cleanName])) {
                $groups[$cleanName] = [];
            }
            $groups[$cleanName][] = $photo;
        }

        return array_filter($groups, function($group) {
            return count($group) >= 2;
        });
    }

    /**
     * Group photos by exact filename
     */
    private function groupByExactName($photos)
    {
        $groups = [];
        
        foreach ($photos as $photo) {
            $filename = basename($photo['key']);
            
            if (!isset($groups[$filename])) {
                $groups[$filename] = [];
            }
            $groups[$filename][] = $photo;
        }

        return array_filter($groups, function($group) {
            return count($group) >= 2;
        });
    }

    /**
     * Group photos by MD5 hash
     */
    private function groupByHash($photos, $s3Service)
    {
        $hashGroups = [];
        
        foreach ($photos as $photo) {
            try {
                // Get file content and calculate hash
                $content = $s3Service->getFileContent($photo['key']);
                if ($content) {
                    $hash = md5($content);
                    
                    if (!isset($hashGroups[$hash])) {
                        $hashGroups[$hash] = [];
                    }
                    
                    $photo['hash'] = $hash;
                    $hashGroups[$hash][] = $photo;
                }
            } catch (Exception $e) {
                log_message('warning', 'Could not calculate hash for: ' . $photo['key'] . ' - ' . $e->getMessage());
                continue;
            }
        }

        return array_filter($hashGroups, function($group) {
            return count($group) >= 2;
        });
    }

    /**
     * Clean filename for similarity comparison
     */
    private function cleanFilename($filename)
    {
        // Remove common suffixes and prefixes
        $cleaned = preg_replace('/[_\-\s]*\(?\d+\)?[_\-\s]*$/', '', $filename);
        $cleaned = preg_replace('/^[_\-\s]*copy[_\-\s]*/i', '', $cleaned);
        $cleaned = preg_replace('/[_\-\s]*copy[_\-\s]*$/i', '', $cleaned);
        $cleaned = strtolower(trim($cleaned));
        
        return $cleaned;
    }

    /**
     * Get thumbnail URL for a photo
     */
    private function getThumbnailUrl($s3Key)
    {
        $pathInfo = pathinfo($s3Key);
        $thumbnailKey = str_replace('/vehicles/', '/thumbnails/', $pathInfo['dirname']) . '/thumb_' . $pathInfo['basename'];
        
        $s3Service = new \App\Libraries\S3Service();
        if ($s3Service->fileExists($thumbnailKey)) {
            return $s3Service->getPublicUrl($thumbnailKey);
        }
        
        // Fallback to original image if thumbnail doesn't exist
        return $s3Service->getPublicUrl($s3Key);
    }

    /**
     * Group photos by ETag (most reliable method for S3)
     */
    private function groupByETag($photos)
    {
        $groups = [];
        
        foreach ($photos as $photo) {
            if (!is_array($photo) || !isset($photo['etag'])) continue;
            
            $etag = $photo['etag'];
            if (!empty($etag) && $etag !== '""') { // Skip empty or invalid ETags
                if (!isset($groups[$etag])) {
                    $groups[$etag] = [];
                }
                $groups[$etag][] = $photo;
            }
        }

        return array_filter($groups, function($group) {
            return count($group) >= 2;
        });
    }

    /**
     * Group photos by exact file size
     */
    private function groupByExactSize($photos)
    {
        $groups = [];
        
        foreach ($photos as $photo) {
            if (!is_array($photo) || !isset($photo['size'])) continue;
            
            $size = $photo['size'];
            if ($size > 0) { // Skip files with unknown size
                if (!isset($groups[$size])) {
                    $groups[$size] = [];
                }
                $groups[$size][] = $photo;
            }
        }

        return array_filter($groups, function($group) {
            return count($group) >= 2;
        });
    }

    /**
     * Group photos by exact filename AND exact size
     */
    private function groupByExactFilenameAndSize($photos)
    {
        $groups = [];
        
        foreach ($photos as $photo) {
            if (!is_array($photo) || !isset($photo['filename']) || !isset($photo['size'])) continue;
            
            $filename = strtolower($photo['filename']); // Case insensitive
            $size = $photo['size'];
            
            // Create a composite key: filename + size
            $compositeKey = $filename . '_' . $size;
            
            if ($size > 0 && !empty($filename)) { // Skip files with unknown size or empty filename
                if (!isset($groups[$compositeKey])) {
                    $groups[$compositeKey] = [];
                }
                $groups[$compositeKey][] = $photo;
            }
        }

        return array_filter($groups, function($group) {
            return count($group) >= 2;
        });
    }

    /**
     * Group photos by similar file size (within tolerance)
     */
    private function groupBySimilarSize($photos, $tolerance = 0.05)
    {
        $groups = [];
        $processed = [];
        
        foreach ($photos as $i => $photo1) {
            if (!is_array($photo1) || !isset($photo1['size']) || in_array($i, $processed) || $photo1['size'] <= 0) continue;
            
            $sizeGroup = [$photo1];
            $processed[] = $i;
            
            foreach ($photos as $j => $photo2) {
                if (!is_array($photo2) || !isset($photo2['size']) || $i === $j || in_array($j, $processed) || $photo2['size'] <= 0) continue;
                
                // Calculate size difference percentage
                $sizeDiff = abs($photo1['size'] - $photo2['size']);
                $avgSize = ($photo1['size'] + $photo2['size']) / 2;
                $diffPercentage = $avgSize > 0 ? ($sizeDiff / $avgSize) : 1;
                
                if ($diffPercentage <= $tolerance) {
                    $sizeGroup[] = $photo2;
                    $processed[] = $j;
                }
            }
            
            if (count($sizeGroup) >= 2) {
                $sizes = array_column($sizeGroup, 'size');
                if (!empty($sizes)) {
                    $sizeRange = min($sizes) . '-' . max($sizes);
                    $groups[$sizeRange] = $sizeGroup;
                }
            }
        }

        return $groups;
    }

    /**
     * Aggressive filename similarity detection
     */
    private function groupByAggressiveNameSimilarity($photos)
    {
        $groups = [];
        
        foreach ($photos as $photo) {
            $filename = $photo['filename'];
            $patterns = $this->generateNamePatterns($filename);
            
            foreach ($patterns as $pattern) {
                if (!isset($groups[$pattern])) {
                    $groups[$pattern] = [];
                }
                $groups[$pattern][] = $photo;
            }
        }

        // Filter and deduplicate
        $finalGroups = [];
        foreach ($groups as $pattern => $group) {
            if (count($group) >= 2) {
                // Remove duplicates within group
                $uniqueGroup = [];
                $seenKeys = [];
                foreach ($group as $photo) {
                    if (!in_array($photo['key'], $seenKeys)) {
                        $uniqueGroup[] = $photo;
                        $seenKeys[] = $photo['key'];
                    }
                }
                
                if (count($uniqueGroup) >= 2) {
                    $finalGroups[$pattern] = $uniqueGroup;
                }
            }
        }

        return $finalGroups;
    }

    /**
     * Generate patterns for filename matching
     */
    private function generateNamePatterns($filename)
    {
        $patterns = [];
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        
        // Pattern 1: Remove numbers and common suffixes
        $pattern1 = preg_replace('/[_\-\s]*\d+[_\-\s]*/', '', $basename);
        $pattern1 = preg_replace('/[_\-\s]*(copy|duplicate|jpg|jpeg|png|gif)[_\-\s]*$/i', '', $pattern1);
        $pattern1 = trim(strtolower($pattern1), '_-. ');
        if (!empty($pattern1)) $patterns[] = $pattern1;
        
        // Pattern 2: Remove timestamps
        $pattern2 = preg_replace('/\d{4}[\-_]\d{2}[\-_]\d{2}/', '', $basename);
        $pattern2 = preg_replace('/\d{2}[\-_]\d{2}[\-_]\d{4}/', '', $pattern2);
        $pattern2 = trim(strtolower($pattern2), '_-. ');
        if (!empty($pattern2) && $pattern2 !== $pattern1) $patterns[] = $pattern2;
        
        // Pattern 3: Keep only letters
        $pattern3 = preg_replace('/[^a-zA-Z]/', '', $basename);
        $pattern3 = strtolower($pattern3);
        if (strlen($pattern3) >= 3 && $pattern3 !== $pattern1 && $pattern3 !== $pattern2) {
            $patterns[] = $pattern3;
        }
        
        // Pattern 4: First significant word
        $words = preg_split('/[^a-zA-Z]+/', $basename);
        $words = array_filter($words, function($word) {
            return strlen($word) >= 3;
        });
        if (!empty($words)) {
            $words = array_values($words); // Reindex array after filtering
            $pattern4 = strtolower($words[0]);
            if (!in_array($pattern4, $patterns)) $patterns[] = $pattern4;
        }
        
        return array_unique($patterns);
    }

    /**
     * Check if a group of photos is already found in existing duplicate groups
     */
    private function isGroupAlreadyFound($photoGroup, $existingGroups) 
    {
        $groupKeys = array_column($photoGroup, 'key');
        
        foreach ($existingGroups as $existing) {
            $existingKeys = array_column($existing['photos'], 'key');
            $intersection = array_intersect($groupKeys, $existingKeys);
            
            // If more than half of the photos are already found, consider it already found
            if (count($intersection) >= count($groupKeys) / 2) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get photos that haven't been classified as duplicates yet
     */
    private function getRemainingPhotos($allPhotos, $duplicateGroups)
    {
        $foundKeys = [];
        
        // Collect all keys that are already in duplicate groups
        foreach ($duplicateGroups as $group) {
            foreach ($group['photos'] as $photo) {
                $foundKeys[] = $photo['key'];
            }
        }
        
        // Return photos not yet found
        return array_filter($allPhotos, function($photo) use ($foundKeys) {
            return !in_array($photo['key'], $foundKeys);
        });
    }

    /**
     * Delete selected vehicle photos
     */
    public function deletePhotos()
    {
        // Only allow POST requests
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Method not allowed'
            ]);
        }

        try {
            $input = $this->request->getJSON(true);
            $photoKeys = $input['photo_keys'] ?? [];

            if (empty($photoKeys) || !is_array($photoKeys)) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No photo keys provided'
                ]);
            }

            // Initialize S3 service
            $s3Service = new \App\Libraries\S3Service();

            $deletedCount = 0;
            $errors = [];

            foreach ($photoKeys as $photoKey) {
                try {
                    log_message('info', "Attempting to delete photo: {$photoKey}");
                    
                    // Delete the main photo
                    $deleteResult = $s3Service->deleteFile($photoKey);
                    
                    if ($deleteResult['success']) {
                        $deletedCount++;
                        
                        // Also try to delete the thumbnail (if it exists)
                        $thumbnailKey = 'vehicles/thumbnails/' . basename($photoKey);
                        $s3Service->deleteFile($thumbnailKey);
                        
                        log_message('info', "Successfully deleted photo: {$photoKey}");
                    } else {
                        $errorMsg = $deleteResult['error'] ?? 'Unknown error';
                        $errors[] = "Failed to delete " . basename($photoKey) . ": " . $errorMsg;
                        log_message('error', "Failed to delete photo {$photoKey}: {$errorMsg}");
                    }
                } catch (Exception $e) {
                    $errors[] = "Error deleting " . basename($photoKey) . ": " . $e->getMessage();
                    log_message('error', "Exception deleting photo {$photoKey}: " . $e->getMessage());
                }
            }

            $response = [
                'success' => $deletedCount > 0,
                'deleted_count' => $deletedCount,
                'total_requested' => count($photoKeys)
            ];

            if (!empty($errors)) {
                $response['errors'] = $errors;
                $response['partial_success'] = $deletedCount > 0 && $deletedCount < count($photoKeys);
            }

            if ($deletedCount === 0) {
                $response['error'] = 'No photos could be deleted';
            }

            return $this->response->setJSON($response);

        } catch (Exception $e) {
            log_message('error', 'Delete photos error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Failed to delete photos: ' . $e->getMessage()
            ]);
        }
    }
} 
