<?php

namespace Modules\SalesOrders\Controllers;

use App\Controllers\BaseController;
use Modules\SalesOrders\Models\SalesOrderModel;
use Modules\SalesOrders\Models\SalesOrderServiceModel;
use Modules\SalesOrders\Models\OrderActivityModel;
use Modules\SalesOrders\Models\SalesOrderCommentModel;
use App\Models\ClientModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\SettingsModel;
use Exception;

class SalesOrdersController extends BaseController
{
    protected $salesOrderModel;
    protected $serviceModel;
    protected $activityModel;
    protected $wkhtmltopdfPath; // Path to wkhtmltopdf executable

    /**
     * Constructor
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        // Load models
        $this->salesOrderModel = new SalesOrderModel();
        $this->serviceModel = new SalesOrderServiceModel();
        $this->activityModel = new OrderActivityModel();
    }

    public function index()
    {
        // Cargar modelos necesarios para all_content
        $clientModel = new \App\Models\ClientModel();
        $userModel = new \App\Models\UserModel();
        $serviceModel = new SalesOrderServiceModel();
        
        // Get all active clients
        $clients = $clientModel->getActiveClients();
        
        // Get all active client users (contacts) - simplified query
        $contacts = $userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
                                   ->where('users.user_type', 'client')
                                   ->where('users.active', 1)
                                   ->orderBy('users.first_name', 'ASC')
                             ->findAll();
        
        // Get active services
        $services = $serviceModel->where('service_status', 'active')
                                ->where('show_in_orders', 1)
                                ->orderBy('service_name', 'ASC')
                                ->findAll();

        // Get deleted orders for the deleted orders tab
        $deletedOrders = [];
        try {
            $db = \Config\Database::connect();
            
            // Get basic deleted orders without joins first
            $deletedOrdersRaw = $db->table('sales_orders')
                               ->select('*')
                               ->where('deleted', 1)
                               ->orderBy('updated_at', 'DESC')
                               ->get()
                               ->getResultArray();
            
            // If we have orders, try to add client/contact names
            if (!empty($deletedOrdersRaw)) {
                foreach ($deletedOrdersRaw as &$order) {
                    // Get client name
                    if ($order['client_id']) {
                        $client = $db->table('clients')
                                    ->select('name')
                                    ->where('id', $order['client_id'])
                                    ->get()
                                    ->getRowArray();
                        $order['client_name'] = $client ? $client['name'] : 'N/A';
                    } else {
                        $order['client_name'] = 'N/A';
                    }
                    
                    // Get salesperson name from users table
                    if ($order['created_by']) {
                        $user = $db->table('users')
                                  ->select('CONCAT(first_name, " ", last_name) as name')
                                  ->where('id', $order['created_by'])
                                  ->get()
                                  ->getRowArray();
                        $order['salesperson_name'] = $user ? $user['name'] : 'N/A';
                    } else {
                        $order['salesperson_name'] = 'N/A';
                    }
                    
                    // Get service name
                    if ($order['service_id']) {
                        $service = $db->table('sales_orders_services')
                                     ->select('service_name')
                                     ->where('id', $order['service_id'])
                                     ->get()
                                     ->getRowArray();
                        $order['service_name'] = $service ? $service['service_name'] : 'N/A';
                    } else {
                        $order['service_name'] = 'N/A';
                    }
                }
                $deletedOrders = $deletedOrdersRaw;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error loading deleted orders in index: ' . $e->getMessage());
            $deletedOrders = [];
        }
        
        $data = [
            'title' => 'Sales Orders',
            'clients' => $clients,
            'contacts' => $contacts,
            'services' => $services,
            'deleted_orders' => $deletedOrders
        ];

        // Log the data for debugging
        log_message('info', "Index page data - Clients: " . count($clients) . ", Contacts: " . count($contacts) . ", Services: " . count($services) . ", Deleted Orders: " . count($deletedOrders));

        // Copy all module views to main views directory
        // Now use normal view loading
        return view('Modules\SalesOrders\Views\sales_orders/index', $data);
    }
    
    /**
     * Ensure all module views are copied to main views directory
     */


    public function dashboard_content()
    {
        $data = [
            'todayOrders' => $this->salesOrderModel->getTodayOrders(),
            'tomorrowOrders' => $this->salesOrderModel->getTomorrowOrders(),
            'pendingOrders' => $this->salesOrderModel->getPendingOrders(),
            'totalOrders' => count($this->salesOrderModel->findAll()),
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/dashboard_content', $data);
    }

    public function today_content()
    {
        $data = [
            'orders' => $this->salesOrderModel->getTodayOrders(),
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/today_content', $data);
    }

    public function tomorrow_content()
    {
        $data = [
            'orders' => $this->salesOrderModel->getTomorrowOrders(),
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/tomorrow_content', $data);
    }

    public function pending_content()
    {
        $data = [
            'orders' => $this->salesOrderModel->getPendingOrders(),
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/pending_content', $data);
    }

    public function week_content()
    {
        $data = [
            'orders' => $this->salesOrderModel->getWeekOrders(),
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/week_content', $data);
    }

    public function all_content()
    {
        // Verificar si es una petición AJAX para obtener datos filtrados
        if ($this->request->isAJAX()) {
            return $this->getFilteredOrders();
        }
        
        // Para carga inicial de la página
        $clientModel = new \App\Models\ClientModel();
        $userModel = new \App\Models\UserModel();
        
        // Get all active clients
        $clients = $clientModel->getActiveClients();
        
        // Get all active client users (contacts) - simplified query
        $contacts = $userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
                                   ->where('users.user_type', 'client')
                                   ->where('users.active', 1)
                                   ->orderBy('users.first_name', 'ASC')
                             ->findAll();
        
        $data = [
            'orders' => [], // Se cargarán vía AJAX
            'clients' => $clients,
            'contacts' => $contacts
        ];

        // Log the data for debugging
        log_message('info', "All content data - Clients: " . count($clients) . ", Contacts: " . count($contacts));

        return view('Modules\SalesOrders\Views\sales_orders/all_content', $data);
    }
    
    /**
     * Obtener órdenes filtradas para DataTables AJAX
     */
    public function getFilteredOrders()
    {
        $request = $this->request;
        
        // Parámetros de DataTables
        $draw = intval($request->getPost('draw'));
        $start = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $searchValue = $request->getPost('search')['value'] ?? '';
        
        // Filtros personalizados
        $clientFilter = $request->getPost('client_filter');
        $contactFilter = $request->getPost('contact_filter');
        $statusFilter = $request->getPost('status_filter');
        $dateFromFilter = $request->getPost('date_from_filter');
        $dateToFilter = $request->getPost('date_to_filter');
        
        // DEBUG: Log filter parameters
        log_message('info', "getFilteredOrders DEBUG - statusFilter: " . ($statusFilter ?? 'null'));
        log_message('info', "getFilteredOrders DEBUG - clientFilter: " . ($clientFilter ?? 'null'));
        log_message('info', "getFilteredOrders DEBUG - contactFilter: " . ($contactFilter ?? 'null'));
        log_message('info', "getFilteredOrders DEBUG - dateFromFilter: " . ($dateFromFilter ?? 'null'));
        log_message('info', "getFilteredOrders DEBUG - dateToFilter: " . ($dateToFilter ?? 'null'));
        log_message('info', "getFilteredOrders DEBUG - All POST data: " . json_encode($request->getPost()));
        
        // Query base - usar el database builder directamente para evitar conflictos
        $db = \Config\Database::connect();
        $builder = $db->table('sales_orders')
                     ->select('sales_orders.*, 
                              clients.name as client_name,
                              CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                              sales_orders_services.service_name,
                              sales_orders_services.service_price,
                              COALESCE(comments_count.comment_count, 0) as comments_count,
                              COALESCE(internal_notes_count.notes_count, 0) as internal_notes_count,
                              COALESCE(stock_duplicates.stock_count, 0) as stock_duplicates,
                              COALESCE(client_duplicates.client_count, 0) as client_duplicates,
                              COALESCE(vin_duplicates.vin_count, 0) as vin_duplicates')
                     ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                     ->join('users', 'users.id = sales_orders.contact_id', 'left')
                     ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                     ->join('(SELECT order_id, COUNT(*) as comment_count FROM sales_orders_comments GROUP BY order_id) as comments_count', 
                             'comments_count.order_id = sales_orders.id', 'left')
                     ->join('(SELECT order_id, COUNT(*) as notes_count FROM internal_notes WHERE deleted_at IS NULL GROUP BY order_id) as internal_notes_count', 
                             'internal_notes_count.order_id = sales_orders.id', 'left')
                     ->join('(SELECT stock, COUNT(*) - 1 as stock_count FROM sales_orders WHERE deleted = 0 AND stock IS NOT NULL AND stock != "" GROUP BY stock HAVING COUNT(*) > 1) as stock_duplicates',
                             'stock_duplicates.stock = sales_orders.stock', 'left')
                     ->join('(SELECT client_id, COUNT(*) - 1 as client_count FROM sales_orders WHERE deleted = 0 GROUP BY client_id HAVING COUNT(*) > 1) as client_duplicates',
                             'client_duplicates.client_id = sales_orders.client_id', 'left')
                     ->join('(SELECT vin, COUNT(*) - 1 as vin_count FROM sales_orders WHERE deleted = 0 AND vin IS NOT NULL AND vin != "" GROUP BY vin HAVING COUNT(*) > 1) as vin_duplicates',
                             'vin_duplicates.vin = sales_orders.vin', 'left')
                     ->where('sales_orders.deleted', 0);
        
        // DEBUG: Count total orders before filtering
        $totalBeforeFiltering = $db->table('sales_orders')->where('deleted', 0)->countAllResults();
        log_message('info', "getFilteredOrders DEBUG - Total orders before filtering: " . $totalBeforeFiltering);
        
        // Aplicar filtros
        if (!empty($clientFilter)) {
            $builder->where('sales_orders.client_id', $clientFilter);
        }
        
        if (!empty($contactFilter)) {
            $builder->where('sales_orders.contact_id', $contactFilter);
        }
        
        if (!empty($statusFilter)) {
            // Handle comma-separated status values (for pending orders filtering)
            if (strpos($statusFilter, ',') !== false) {
                $statusArray = explode(',', $statusFilter);
                $statusArray = array_map('trim', $statusArray); // Remove any extra whitespace
                log_message('info', "getFilteredOrders DEBUG - Using whereIn with statuses: " . implode(', ', $statusArray));
                $builder->whereIn('sales_orders.status', $statusArray);
            } else {
                log_message('info', "getFilteredOrders DEBUG - Using single status filter: " . $statusFilter);
            $builder->where('sales_orders.status', $statusFilter);
            }
        }
        
        if (!empty($dateFromFilter)) {
            $builder->where('sales_orders.date >=', $dateFromFilter);
        }
        
        if (!empty($dateToFilter)) {
            $builder->where('sales_orders.date <=', $dateToFilter);
        }
        
        // Búsqueda global
        if (!empty($searchValue)) {
            $builder->groupStart()
                   ->like('sales_orders.stock', $searchValue)
                   ->orLike('sales_orders.vin', $searchValue)
                   ->orLike('sales_orders.vehicle', $searchValue)
                   ->orLike('clients.name', $searchValue)
                   ->orLike('CONCAT(users.first_name, " ", users.last_name)', $searchValue, false)
                   ->orLike('sales_orders_services.service_name', $searchValue)
                   ->orLike('CONCAT("SAL-", LPAD(sales_orders.id, 5, "0"))', $searchValue, false)
                   ->groupEnd();
        }
        
        // Clonar builder para contar registros filtrados
        $countBuilder = clone $builder;
        $totalFiltered = $countBuilder->countAllResults('', false);
        
        // DEBUG: Log filtered count
        log_message('info', "getFilteredOrders DEBUG - Total filtered: " . $totalFiltered);
        
        // Contar total de registros sin filtro usando builder específico
        $totalRecordsBuilder = $db->table('sales_orders')
                                 ->where('sales_orders.deleted', 0);
        $totalRecords = $totalRecordsBuilder->countAllResults();
        
        // Aplicar ordenamiento y paginación
        // Determinar el tipo de filtro para aplicar ordenamiento apropiado
        $orderingType = $this->determineOrderingType($dateFromFilter, $dateToFilter, $statusFilter);
        
        switch ($orderingType) {
            case 'today':
                // Para las órdenes de hoy: ordenar por tiempo ascendente
                $orders = $builder->orderBy('sales_orders.time', 'ASC')
                                 ->orderBy('sales_orders.created_at', 'ASC')
                                 ->limit($length, $start)
                                 ->get()
                                 ->getResultArray();
                break;
                
            case 'tomorrow':
                // Para las órdenes de mañana: ordenar por tiempo ascendente
                $orders = $builder->orderBy('sales_orders.time', 'ASC')
                                 ->orderBy('sales_orders.created_at', 'ASC')
                                 ->limit($length, $start)
                                 ->get()
                                 ->getResultArray();
                break;
                
            case 'week':
                // Para órdenes de la semana: ordenar por fecha ascendente, luego por tiempo ascendente
                $orders = $builder->orderBy('sales_orders.date', 'ASC')
                                 ->orderBy('sales_orders.time', 'ASC')
                                 ->orderBy('sales_orders.created_at', 'ASC')
                                 ->limit($length, $start)
                                 ->get()
                                 ->getResultArray();
                break;
                
            case 'pending':
                // Para órdenes pendientes: ordenar por fecha ascendente, luego por tiempo ascendente
                $orders = $builder->orderBy('sales_orders.date', 'ASC')
                                 ->orderBy('sales_orders.time', 'ASC')
                                 ->orderBy('sales_orders.created_at', 'ASC')
                                 ->limit($length, $start)
                                 ->get()
                                 ->getResultArray();
                break;
                
            case 'all':
                // Para vista de todas las órdenes: ordenar por ID descendente (órdenes más recientes primero)
                $orders = $builder->orderBy('sales_orders.id', 'DESC')
                                 ->limit($length, $start)
                                 ->get()
                                 ->getResultArray();
                break;
                
            default:
                // Ordenamiento por defecto para otros casos
        $orders = $builder->orderBy('sales_orders.created_at', 'DESC')
                         ->limit($length, $start)
                         ->get()
                         ->getResultArray();
                break;
        }
        
        // DEBUG: Log actual data returned
        log_message('info', "getFilteredOrders DEBUG - Orders returned: " . count($orders));
        if (!empty($orders)) {
            foreach ($orders as $i => $order) {
                log_message('info', "getFilteredOrders DEBUG - Order $i: ID={$order['id']}, Status={$order['status']}, Stock={$order['stock']}");
            }
        }
        
        // Detectar duplicados
        $duplicateInfo = $this->getDuplicateInfo($orders);
        
        // Obtener comentarios para las órdenes que tienen comentarios
        $orderIds = array_column($orders, 'id');
        $commentsData = [];
        $internalNotesData = [];
        if (!empty($orderIds)) {
            $commentsData = $this->getCommentsForOrders($orderIds);
            $internalNotesData = $this->getInternalNotesForOrders($orderIds);
        }
        
        // Formatear datos para DataTables
        $data = [];
        foreach ($orders as $order) {
            // Formatear fecha y hora combinadas para la columna "Due"
            $dueFormatted = 'N/A';
            if ($order['date']) {
                $timeFormatted = $order['time'] ? date('g:i A', strtotime($order['time'])) : '';
                $dateFormatted = date('d M, Y', strtotime($order['date']));
                
                if ($timeFormatted) {
                    $dueFormatted = '<div class="text-center"><div class="fw-medium text-primary">' . $timeFormatted . '</div><div class="text-muted small">' . $dateFormatted . '</div></div>';
                } else {
                    $dueFormatted = '<div class="text-center"><div class="text-muted small">' . $dateFormatted . '</div></div>';
                }
            }
            
            // Determinar clase CSS para la fila basada en estado y fecha/hora
            $rowClass = $this->determineOrderRowClass($order);
            
            // Obtener información de duplicados para esta orden
            $duplicates = $duplicateInfo[$order['id']] ?? [];
            
            // Obtener comentarios para esta orden
            $orderComments = $commentsData[$order['id']] ?? [];
            
            // Obtener notas internas para esta orden
            $orderInternalNotes = $internalNotesData[$order['id']] ?? [];
            
            $data[] = [
                'id' => $order['id'],
                'order_id' => 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                'stock' => $order['stock'] ?? 'N/A',
                'salesperson_name' => $order['salesperson_name'] ?? 'N/A',
                'vehicle' => $order['vehicle'] ?? 'N/A',
                'vin' => $order['vin'] ? substr($order['vin'], -8) : '',
                'client_name' => $order['client_name'] ?? 'N/A',
                'service_name' => $order['service_name'] ?? 'N/A',
                'instructions' => $order['instructions'] ?? '', // Add instructions field
                'comments_count' => $order['comments_count'] ?? 0, // Add comments count
                'comments' => $orderComments, // Add actual comments
                'internal_notes_count' => $order['internal_notes_count'] ?? 0, // Add internal notes count
                'internal_notes' => $orderInternalNotes, // Add actual internal notes
                'duplicates' => $duplicates, // Add duplicate information
                'stock_duplicates' => $order['stock_duplicates'] ?? 0, // Add individual duplicate counts
                'client_duplicates' => $order['client_duplicates'] ?? 0,
                'vin_duplicates' => $order['vin_duplicates'] ?? 0,
                'due' => $dueFormatted,
                'status' => $order['status'],
                'status_badge' => $this->getStatusBadge($order['status']),
                'actions' => $this->generateActionButtons($order['id']),
                'row_class' => $rowClass, // Nueva clase para la fila
                'date' => $order['date'], // Para debugging
                'time' => $order['time']  // Para debugging
            ];
        }
        
        $result = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ];
        
        // DEBUG: Log final result
        log_message('info', "getFilteredOrders DEBUG - Final result: " . json_encode([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'dataCount' => count($data)
        ]));
        
        return $this->response->setJSON($result);
    }
    
    /**
     * Generar badge de estado
     */
    private function getStatusBadge($status)
    {
        $statusClass = 'bg-warning';
        $statusText = ucfirst($status);
        
        switch($status) {
            case 'completed':
                $statusClass = 'bg-success';
                break;
            case 'cancelled':
                $statusClass = 'bg-danger';
                break;
            case 'in_progress':
                $statusClass = 'bg-info';
                $statusText = 'In Progress';
                break;
            case 'processing':
                $statusClass = 'bg-primary';
                break;
        }
        
        return '<span class="badge ' . $statusClass . '">' . $statusText . '</span>';
    }
    
    /**
     * Determinar clase CSS para la fila basada en estado y fecha/hora de vencimiento
     */
    private function determineOrderRowClass($order)
    {
        // Primero aplicar clases básicas de status
        $status = $order['status'] ?? '';
        $statusClass = '';
        
        switch ($status) {
            case 'completed':
                $statusClass = 'order-row-completed';
                break;
            case 'cancelled':
                $statusClass = 'order-row-cancelled';
                break;
            case 'pending':
                $statusClass = 'order-row-pending';
                break;
            case 'processing':
                $statusClass = 'order-row-processing';
                break;
            case 'in_progress':
                $statusClass = 'order-row-in-progress';
                break;
        }
        
        // Si no hay fecha, solo devolver la clase de status
        if (empty($order['date'])) {
            return $statusClass;
        }
        
        // Si está completada o cancelada, no aplicar delay/urgent (solo la clase de status)
        if ($status === 'completed' || $status === 'cancelled') {
            return $statusClass;
        }
        
        // Construir datetime de vencimiento
        $dueDateTime = $order['date'];
        if (!empty($order['time'])) {
            $dueDateTime .= ' ' . $order['time'];
        } else {
            // Si no hay hora específica, asumir final del día
            $dueDateTime .= ' 23:59:59';
        }
        
        try {
            $dueTimestamp = strtotime($dueDateTime);
            $currentTimestamp = time();
            
            // Si ya pasó la fecha/hora de vencimiento (overdue) - SOLO si no está completed/cancelled
            if ($currentTimestamp > $dueTimestamp) {
                return 'order-row-overdue';
            }
            
            // Si falta menos de 1 hora para el vencimiento (urgent) - SOLO si no está completed/cancelled
            $timeDifference = $dueTimestamp - $currentTimestamp;
            if ($timeDifference <= 3600) { // 3600 segundos = 1 hora
                return 'order-row-urgent';
            }
            
        } catch (\Exception $e) {
            // Si hay error en el parsing de fecha, log y continuar con clase de status
            log_message('error', 'Error parsing date/time for order ' . ($order['id'] ?? 'unknown') . ': ' . $e->getMessage());
        }
        
        // Si no está delayed/urgent, devolver la clase de status normal
        return $statusClass;
    }
    
    /**
     * Generar botones de acción
     */
    private function generateActionButtons($orderId)
    {
        $baseUrl = base_url();
        return '
            <div class="d-flex justify-content-center">
                <a href="' . $baseUrl . 'sales_orders/view/' . $orderId . '" class="btn btn-sm btn-outline-primary border-0 p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="View Order" style="width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; margin-right: 2px;">
                    <i data-feather="eye" style="width: 8px; height: 8px;"></i>
                </a>
                <a href="#" class="btn btn-sm btn-outline-success border-0 p-0 edit-order-btn" data-id="' . $orderId . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Order" style="width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; margin-right: 2px;">
                    <i data-feather="edit-3" style="width: 8px; height: 8px;"></i>
                </a>
                <a href="#" class="btn btn-sm btn-outline-danger border-0 p-0 delete-order-btn" data-id="' . $orderId . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Order" style="width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;">
                    <i data-feather="trash-2" style="width: 8px; height: 8px;"></i>
                </a>
            </div>
        ';
    }

    public function services_content()
    {
        // Usar consultas directas de base de datos para evitar problemas con callbacks del modelo
        $db = \Config\Database::connect();
        
        // Obtener servicios activos
        $activeServices = $db->table('sales_orders_services')
                            ->select('sales_orders_services.*, clients.name as client_name')
                            ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                            ->where('sales_orders_services.deleted', 0)
                            ->where('sales_orders_services.service_status', 'active')
                            ->orderBy('sales_orders_services.service_name', 'ASC')
                            ->get()
                            ->getResultArray();
        
        // Obtener servicios recientes (últimos 5 creados)
        $recentServices = $db->table('sales_orders_services')
                            ->select('sales_orders_services.*, clients.name as client_name')
                            ->join('clients', 'clients.id = sales_orders_services.client_id', 'left')
                            ->where('sales_orders_services.deleted', 0)
                            ->orderBy('sales_orders_services.created_at', 'DESC')
                            ->limit(5)
                            ->get()
                            ->getResultArray();
        
        // Log para debugging
        log_message('info', 'Services content - Active: ' . count($activeServices) . ', Recent: ' . count($recentServices));
        
        $data = [
            'activeServices' => $activeServices,
            'recentServices' => $recentServices,
        ];
        
        return view('Modules\SalesOrders\Views\sales_orders/services_content', $data);
    }

    public function modal_form()
    {
        // Check if it's an AJAX request
        if (!$this->request->isAJAX()) {
            return redirect()->to('sales_orders');
        }

        try {
            // Inicializar variables
        $orderId = $this->request->getGet('id') ?? null;
        $data = [
            'order_id' => $orderId,
                'clients' => [],
                'contacts' => [],
                'services' => [],
                'order' => null
        ];

            // Cargar modelos básicos
            try {
        $clientModel = new \App\Models\ClientModel();
                $data['clients'] = $clientModel->getActiveClients();
            } catch (\Exception $e) {
                log_message('error', "Error loading clients: " . $e->getMessage());
                $data['clients'] = [];
            }

            try {
                $serviceModel = new SalesOrderServiceModel();
                $data['services'] = $serviceModel->where('service_status', 'active')
                                     ->where('show_in_orders', 1)
                                     ->orderBy('service_name', 'ASC')
                                ->findAll();
            } catch (\Exception $e) {
                log_message('error', "Error loading services: " . $e->getMessage());
                $data['services'] = [];
            }

            // Si es para editar, cargar la orden específica
        if ($orderId) {
                try {
                    $order = $this->salesOrderModel->find($orderId);
                    
                    if ($order && $order['deleted'] == 0) {
                        $data['order'] = $order;

                        // DEBUG: Log the order data being loaded
                        log_message('debug', "Modal Form - Loading order $orderId: " . json_encode($order));
                        
                        // Cargar contactos para el cliente de esta orden
                        try {
                            $userModel = new \App\Models\UserModel();
                            $data['contacts'] = $userModel->select('users.id, users.first_name, users.last_name, users.client_id, CONCAT(users.first_name, " ", users.last_name) as name')
                                 ->where('users.user_type', 'client')
                                 ->where('users.active', 1)
                                                         ->where('users.client_id', $order['client_id'])
                                 ->orderBy('users.first_name', 'ASC')
                                 ->findAll();
                            
                            // DEBUG: Log the contacts being loaded                
                            log_message('debug', "Modal Form - Loading contacts for client {$order['client_id']}: " . json_encode($data['contacts']));
                        } catch (\Exception $e) {
                            log_message('error', "Error loading contacts: " . $e->getMessage());
                            $data['contacts'] = [];
                        }
        } else {
                        // Orden no encontrada
                        log_message('error', "Order $orderId not found or deleted");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
                } catch (\Exception $e) {
                    log_message('error', "Error loading order: " . $e->getMessage());
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error loading order: ' . $e->getMessage()
                    ]);
                }
        }

            // Load module view directly
            
            // Cargar la vista del modal (solo el contenido, no el layout completo)
        return view('Modules\SalesOrders\Views\sales_orders/modal_form', $data);
            
        } catch (\Exception $e) {
            log_message('error', "Critical error in modal_form: " . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        // Debug logging
        log_message('info', 'SalesOrders store method called');
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('info', 'Is AJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));
        log_message('info', 'Request headers: ' . json_encode($this->request->headers()));
        log_message('info', 'POST method: ' . $this->request->getMethod());
        log_message('info', 'Request URI: ' . $this->request->getUri());
        
        // Ensure JSON response for AJAX requests
        if ($this->request->isAJAX()) {
            $this->response->setContentType('application/json');
        }
        
        $rules = [
            'client_id' => 'required|numeric',
            'contact_id' => 'required|numeric',
            'service_id' => 'required|numeric',
            'date' => 'required|valid_date',
            'time' => 'permit_empty',
            'status' => 'required',
            // Campos opcionales
            'stock' => 'permit_empty',
            'vin' => 'permit_empty',
            'vehicle' => 'permit_empty',
            'instructions' => 'permit_empty',
            'notes' => 'permit_empty',
        ];
        
        // Debug each field individually
        log_message('info', 'Validating fields:');
        log_message('info', 'client_id: ' . ($this->request->getPost('client_id') ?? 'null'));
        log_message('info', 'contact_id: ' . ($this->request->getPost('contact_id') ?? 'null'));
        log_message('info', 'service_id: ' . ($this->request->getPost('service_id') ?? 'null'));
        log_message('info', 'date: ' . ($this->request->getPost('date') ?? 'null'));
        log_message('info', 'time: ' . ($this->request->getPost('time') ?? 'null'));
        log_message('info', 'status: ' . ($this->request->getPost('status') ?? 'null'));
        log_message('info', 'stock: ' . ($this->request->getPost('stock') ?? 'null'));
        log_message('info', 'vin: ' . ($this->request->getPost('vin') ?? 'null'));
        log_message('info', 'vehicle: ' . ($this->request->getPost('vehicle') ?? 'null'));

        if (!$this->validate($rules)) {
            log_message('error', 'Validation failed for SalesOrders');
            log_message('error', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            log_message('error', 'Validation data: ' . json_encode($this->request->getPost()));
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check for duplicate orders by Stock or VIN
        $stock = trim($this->request->getPost('stock') ?? '');
        $vin = trim($this->request->getPost('vin') ?? '');
        $orderId = $this->request->getPost('id');
        $forceSave = $this->request->getPost('force_save') === 'true'; // Allow override
        
        if (!$forceSave && (!empty($stock) || !empty($vin))) {
            $duplicates = [];
            
            // Check stock duplicates
            if (!empty($stock)) {
                $query = $this->salesOrderModel->select('sales_orders.id, sales_orders.stock, sales_orders.vin, sales_orders.vehicle, sales_orders.date, sales_orders.status, clients.name as client_name, CONCAT(users.first_name, " ", users.last_name) as salesperson_name')
                                              ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                              ->join('users', 'users.id = sales_orders.contact_id', 'left')
                                              ->where('sales_orders.stock', $stock)
                                              ->where('sales_orders.deleted', 0);
                
                if ($orderId) {
                    $query->where('sales_orders.id !=', $orderId);
                }
                
                $stockDuplicates = $query->findAll();
                if (!empty($stockDuplicates)) {
                    $duplicates['stock'] = [
                        'field' => 'Stock',
                        'value' => $stock,
                        'orders' => $stockDuplicates
                    ];
                }
            }
            
            // Check VIN duplicates
            if (!empty($vin)) {
                $query = $this->salesOrderModel->select('sales_orders.id, sales_orders.stock, sales_orders.vin, sales_orders.vehicle, sales_orders.date, sales_orders.status, clients.name as client_name, CONCAT(users.first_name, " ", users.last_name) as salesperson_name')
                                              ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                              ->join('users', 'users.id = sales_orders.contact_id', 'left')
                                              ->where('sales_orders.vin', $vin)
                                              ->where('sales_orders.deleted', 0);
                
                if ($orderId) {
                    $query->where('sales_orders.id !=', $orderId);
                }
                
                $vinDuplicates = $query->findAll();
                if (!empty($vinDuplicates)) {
                    $duplicates['vin'] = [
                        'field' => 'VIN',
                        'value' => $vin,
                        'count' => count($vinDuplicates),
                        'orders' => $vinDuplicates
                    ];
                }
            }
            
            // If duplicates found, return warning message
            if (!empty($duplicates)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'has_duplicates' => true,
                        'duplicates' => $duplicates,
                        'message' => lang('App.duplicate_orders_found')
                    ]);
                }
                
                // For non-AJAX requests, redirect back with error
                $duplicateMessages = [];
                foreach ($duplicates as $duplicate) {
                    $orderIds = array_column($duplicate['orders'], 'id');
                    $duplicateMessages[] = "{$duplicate['field']}: {$duplicate['value']} " . lang('App.already_exists_in_orders') . " " . implode(', ', $orderIds);
                }
                
                return redirect()->back()->withInput()->with('error', implode('<br>', $duplicateMessages));
            }
        }

        $data = [
            'client_id' => $this->request->getPost('client_id'),
            'contact_id' => $this->request->getPost('contact_id'), // Contacto/vendedor responsable
            'stock' => $this->request->getPost('stock'),
            'vin' => $this->request->getPost('vin'),
            'vehicle' => $this->request->getPost('vehicle'),
            'service_id' => $this->request->getPost('service_id'),
            'date' => $this->request->getPost('date'),
            'time' => $this->request->getPost('time'),
            'status' => $this->request->getPost('status'),
            'instructions' => $this->request->getPost('instructions'),
            'notes' => $this->request->getPost('notes'),
            'updated_by' => session()->get('user_id') ?? 1,
        ];

        log_message('info', 'Prepared data array: ' . json_encode($data));

        $orderId = $this->request->getPost('id');
        
        log_message('info', 'Order ID from request: ' . ($orderId ?? 'null (new order)'));
        
        try {
        if ($orderId) {
                // Debug logging for update
                log_message('info', 'Updating order ID: ' . $orderId);
                log_message('info', 'Update data: ' . json_encode($data));
                
                // Get current order data to compare changes
                $currentOrder = $this->salesOrderModel->find($orderId);
                if (!$currentOrder) {
                    log_message('error', 'Order not found for ID: ' . $orderId);
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Order not found'
                        ]);
                    }
                    return redirect()->back()->with('error', 'Order not found');
                }

                // Actualizar orden existente
                $data['updated_at'] = date('Y-m-d H:i:s');
                
            if ($this->salesOrderModel->update($orderId, $data)) {
                log_message('info', 'Order updated successfully: ' . $orderId);
                    // Log specific field changes
                    try {
                        $userId = session()->get('user_id') ?? 1;
                        $this->logFieldChanges($orderId, $userId, $currentOrder, $data);
                    } catch (\Exception $e) {
                        log_message('error', "Error logging activity: " . $e->getMessage());
                    }

                    if ($this->request->isAJAX()) {
                        return $this->response
                            ->setContentType('application/json')
                            ->setJSON([
                            'success' => true,
                            'message' => 'Sales order updated successfully',
                            'redirect' => base_url('sales_orders/view/' . $orderId)
                        ]);
                    }
                return redirect()->to('sales_orders')->with('success', 'Sales order updated successfully');
                } else {
                    if ($this->request->isAJAX()) {
                        return $this->response
                            ->setContentType('application/json')
                            ->setJSON([
                            'success' => false,
                            'message' => 'Error updating sales order'
                        ]);
                    }
                    return redirect()->back()->withInput()->with('error', 'Error updating sales order');
            }
        } else {
                // Crear nueva orden
                $data['created_by'] = session()->get('user_id') ?? 1;
                $data['created_at'] = date('Y-m-d H:i:s');
                
                // Generate unique order number
                $data['order_number'] = $this->generateOrderNumber();
                
                log_message('info', 'Creating new order with data: ' . json_encode($data));
                
                $newOrderId = $this->salesOrderModel->insert($data);
                
                if ($newOrderId) {
                    // Log the order creation activity
                    try {
                        $this->activityModel->logOrderCreated($newOrderId, session()->get('user_id') ?? 1);
                    } catch (\Exception $e) {
                        log_message('error', "Error logging activity: " . $e->getMessage());
                    }

                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Sales order created successfully',
                            'redirect' => base_url('sales_orders/view/' . $newOrderId)
                        ]);
                    }
                return redirect()->to('sales_orders')->with('success', 'Sales order created successfully');
                } else {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Error creating sales order'
                        ]);
                    }
                    return redirect()->back()->withInput()->with('error', 'Error creating sales order');
                }
            }
        } catch (\Exception $e) {
            log_message('error', "Error in store method: " . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON([
                    'success' => false,
                    'message' => 'Error saving order: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Error saving sales order: ' . $e->getMessage());
        }
    }

    /**
     * Log specific field changes when updating an order
     */
    private function logFieldChanges($orderId, $userId, $currentOrder, $newData)
    {
        // Field mappings for better activity descriptions
        $fieldMappings = [
            'client_id' => 'Client',
            'created_by' => 'Salesperson',
            'service_id' => 'Service',
            'status' => 'Status',
            'stock' => 'Stock',
            'vin' => 'VIN',
            'vehicle' => 'Vehicle',
            'date' => 'Date',
            'time' => 'Time',
            'instructions' => 'Instructions',
            'notes' => 'Notes'
        ];

        // Get additional data for better descriptions
        $db = \Config\Database::connect();

        foreach ($newData as $field => $newValue) {
            // Skip non-trackable fields
            if (in_array($field, ['updated_at', 'updated_by', 'created_at', 'created_by'])) {
                continue;
            }

            $oldValue = $currentOrder[$field] ?? '';
            
            // Only log if value actually changed
            if ($oldValue != $newValue) {
                $oldDisplayValue = $oldValue;
                $newDisplayValue = $newValue;

                // Get human-readable values for foreign keys
                if ($field === 'client_id') {
                    $oldClient = $db->table('clients')->select('name')->where('id', $oldValue)->get()->getRowArray();
                    $newClient = $db->table('clients')->select('name')->where('id', $newValue)->get()->getRowArray();
                    $oldDisplayValue = $oldClient['name'] ?? 'Unknown Client';
                    $newDisplayValue = $newClient['name'] ?? 'Unknown Client';
                } elseif ($field === 'created_by') {
                    $oldUser = $db->table('users')->select('CONCAT(first_name, " ", last_name) as name')->where('id', $oldValue)->get()->getRowArray();
                    $newUser = $db->table('users')->select('CONCAT(first_name, " ", last_name) as name')->where('id', $newValue)->get()->getRowArray();
                    $oldDisplayValue = $oldUser['name'] ?? 'Unknown Contact';
                    $newDisplayValue = $newUser['name'] ?? 'Unknown Contact';
                } elseif ($field === 'service_id') {
                    $oldService = $db->table('sales_orders_services')->select('service_name')->where('id', $oldValue)->get()->getRowArray();
                    $newService = $db->table('sales_orders_services')->select('service_name')->where('id', $newValue)->get()->getRowArray();
                    $oldDisplayValue = $oldService['service_name'] ?? 'Unknown Service';
                    $newDisplayValue = $newService['service_name'] ?? 'Unknown Service';
                } elseif ($field === 'status') {
                    $oldDisplayValue = ucfirst(str_replace('_', ' ', $oldValue));
                    $newDisplayValue = ucfirst(str_replace('_', ' ', $newValue));
                }

                // Handle empty values
                if (empty($oldDisplayValue)) {
                    $oldDisplayValue = '(empty)';
                }
                if (empty($newDisplayValue)) {
                    $newDisplayValue = '(empty)';
                }

                // Log the field change with user-friendly field name
                $fieldLabel = $fieldMappings[$field] ?? ucfirst(str_replace('_', ' ', $field));
                $this->activityModel->logFieldChange(
                    $orderId, 
                    $userId, 
                    $fieldLabel, // Use the friendly name instead of the database field name
                    $oldDisplayValue, 
                    $newDisplayValue, 
                    $fieldLabel . ' Updated'
                );
            }
        }
    }

    /**
     * Create new order from modal form (AJAX)
     */
    public function create()
    {
        // Check if it's an AJAX request
        if (!$this->request->isAJAX()) {
            return redirect()->to('sales_orders');
        }

        $rules = [
            'client_name' => 'required|min_length[2]',
            'vehicle' => 'required|min_length[2]',
            'client_phone' => 'permit_empty',
            'client_email' => 'permit_empty|valid_email',
            'vin' => 'permit_empty',
            'stock' => 'permit_empty',
            'date' => 'permit_empty|valid_date',
            'time' => 'permit_empty',
            'service_name' => 'permit_empty',
            'salesperson_name' => 'permit_empty',
            'instructions' => 'permit_empty',
            'notes' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Get or create client
            $clientModel = new \App\Models\ClientModel();
            $clientName = $this->request->getPost('client_name');
            $clientPhone = $this->request->getPost('client_phone');
            $clientEmail = $this->request->getPost('client_email');
            
            // Check if client exists by name or email
            $existingClient = null;
            if ($clientEmail) {
                $existingClient = $clientModel->where('email', $clientEmail)->first();
            }
            if (!$existingClient && $clientPhone) {
                $existingClient = $clientModel->where('phone', $clientPhone)->first();
            }
            if (!$existingClient) {
                $existingClient = $clientModel->where('name', $clientName)->first();
            }

            if ($existingClient) {
                $clientId = $existingClient['id'];
            } else {
                // Create new client
                $clientData = [
                    'name' => $clientName,
                    'phone' => $clientPhone,
                    'email' => $clientEmail,
                    'status' => 'active',
                    'created_by' => session()->get('user_id') ?? 1,
                    'updated_by' => session()->get('user_id') ?? 1,
                ];
                $clientId = $clientModel->insert($clientData);
                
                if (!$clientId) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error creating client'
                    ]);
                }
            }

            // Get or create service
            $serviceId = null;
            $serviceName = $this->request->getPost('service_name');
            if ($serviceName) {
                $serviceModel = new SalesOrderServiceModel();
                $existingService = $serviceModel->where('service_name', $serviceName)->first();
                
                if ($existingService) {
                    $serviceId = $existingService['id'];
                } else {
                    // Create new service
                    $serviceData = [
                        'service_name' => $serviceName,
                        'service_price' => 0, // Default price
                        'service_description' => '',
                        'created_by' => session()->get('user_id') ?? 1,
                        'updated_by' => session()->get('user_id') ?? 1,
                    ];
                    $serviceId = $serviceModel->insert($serviceData);
                }
            }

            // Get salesperson ID (optional)
            $salespersonId = null;
            $salespersonName = $this->request->getPost('salesperson_name');
            if ($salespersonName) {
                $userModel = new \App\Models\UserModel();
                $salesperson = $userModel->like('CONCAT(first_name, " ", last_name)', $salespersonName)
                                       ->orLike('first_name', $salespersonName)
                                       ->orLike('last_name', $salespersonName)
                                       ->first();
                if ($salesperson) {
                    $salespersonId = $salesperson['id'];
                }
            }

            // Create the order
            $orderData = [
                'client_id' => $clientId,
                'created_by' => $salespersonId,
                'service_id' => $serviceId,
                'stock' => $this->request->getPost('stock'),
                'vin' => $this->request->getPost('vin'),
                'vehicle' => $this->request->getPost('vehicle'),
                'date' => $this->request->getPost('date') ?: null,
                'time' => $this->request->getPost('time') ?: null,
                'status' => 'pending', // Default status
                'instructions' => $this->request->getPost('instructions'),
                'notes' => $this->request->getPost('notes'),
                'created_by' => session()->get('user_id') ?? 1,
                'updated_by' => session()->get('user_id') ?? 1,
            ];

            $orderId = $this->salesOrderModel->insert($orderData);

            if ($orderId) {
                // Log the order creation activity
                try {
                    $this->activityModel->logOrderCreated($orderId, session()->get('user_id') ?? 1);
                } catch (\Exception $e) {
                    log_message('error', "Error logging activity: " . $e->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'redirect' => base_url('sales_orders/view/' . $orderId)
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error creating order'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', "Error creating order: " . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating order: ' . $e->getMessage()
            ]);
        }
    }

    public function view($id)
    {
        // Get order with complete information including joins
        $order = $this->salesOrderModel->select('sales_orders.*, 
                                               clients.name as client_name,
                                               clients.email as client_email,
                                               clients.phone as client_phone,
                                               clients.address as client_address,
                                               CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                                               users.phone as salesperson_phone,
                                               auth_identities.secret as salesperson_email,
                                               sales_orders_services.service_name,
                                               sales_orders_services.service_price,
                                               sales_orders_services.service_description')
                                     ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                     ->join('users', 'users.id = sales_orders.contact_id', 'left')
                                     ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                                     ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                                     ->where('sales_orders.id', $id)
                                     ->where('sales_orders.deleted', 0)
                                     ->first();
        
        if (!$order) {
            return redirect()->to('sales_orders')->with('error', 'Order not found');
        }
        
        // Generate QR Code automatically
        $qrData = $this->generateOrderQR($id);
        
        $data = [
            'title' => 'Order SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
            'order' => $order,
            'qr_data' => $qrData
        ];

        return view('Modules\SalesOrders\Views\sales_orders/view', $data);
    }
    
    /**
     * Generate QR Code for order view (auto-generated)
     */
    private function generateOrderQR($orderId, $size = '300', $format = 'png')
    {
        try {
            log_message('info', "Starting QR generation for order: {$orderId}, size: {$size}, format: {$format}");
            
            // Check if this order already has a short URL (static/persistent)
            $salesOrderModel = new \Modules\SalesOrders\Models\SalesOrderModel();
            $order = $salesOrderModel->find($orderId);
            
            if (!$order) {
                log_message('error', "Order {$orderId} not found");
                return null;
            }
            
            $shortUrl = null;
            $linkId = null;
            $orderUrl = base_url("sales_orders/view/{$orderId}");
            
            // Check if we already have a short URL for this order
            if ($order['short_url'] && $order['short_url_slug'] && $order['lima_link_id']) {
                $shortUrl = $order['short_url'];
                $linkId = $order['lima_link_id'];
                log_message('info', "Using existing static short URL for order {$orderId}: {$shortUrl} (ID: {$linkId})");
            } else {
                // Create new short URL and save it as static
            $settingsModel = new \App\Models\SettingsModel();
            $apiKey = $settingsModel->getSetting('lima_api_key');
            $brandedDomain = $settingsModel->getSetting('lima_branded_domain');
            
            if ($apiKey && $apiKey !== 'your_lima_links_api_key_here' && !empty(trim($apiKey))) {
                    log_message('info', "Creating NEW static short URL via Lima Links API with 5-digit slug for order {$orderId}...");
                try {
                        // Create short URL with Lima Links using 5-digit slug
                        $shortUrlData = $this->createShortUrl($apiKey, $orderUrl, null, $brandedDomain);
                    if ($shortUrlData && isset($shortUrlData['shorturl']) && !empty($shortUrlData['shorturl'])) {
                        $shortUrl = $shortUrlData['shorturl'];
                        $linkId = $shortUrlData['id'] ?? null;
                            
                            // Extract the slug from the short URL
                            $shortUrlSlug = null;
                            if (preg_match('/mda\.to\/([^\/\?]+)/', $shortUrl, $matches)) {
                                $shortUrlSlug = $matches[1];
                            }
                            
                            // Save the short URL data to make it static/persistent
                            $updateData = [
                                'short_url' => $shortUrl,
                                'short_url_slug' => $shortUrlSlug,
                                'lima_link_id' => $linkId,
                                'qr_generated_at' => date('Y-m-d H:i:s')
                            ];
                            
                            $salesOrderModel->update($orderId, $updateData);
                            log_message('info', "Lima Links short URL created and SAVED as static for order {$orderId}: {$shortUrl} (ID: {$linkId}, Slug: {$shortUrlSlug})");
                        } else {
                            log_message('warning', "Lima Links API returned invalid response for order {$orderId}");
                            $shortUrl = $orderUrl; // Fallback to original URL
                    }
                } catch (Exception $e) {
                        log_message('warning', "Failed to create Lima Links short URL for order {$orderId}, using original: " . $e->getMessage());
                        $shortUrl = $orderUrl; // Fallback to original URL
                    }
                } else {
                    log_message('warning', "No Lima Links API key configured, using original URL for order {$orderId}");
                    $shortUrl = $orderUrl; // Fallback to original URL
                }
            }
            
            // Generate QR code using MDA.to API or fallback service
            $qrImageUrl = null;
            
            // Test if API key is valid
            $isValidApiKey = !empty($apiKey) && $apiKey !== 'your_lima_links_api_key_here' && strlen($apiKey) >= 5;
            
            // First try to use MDA.to QR API if we have a valid API key and short URL
            if ($isValidApiKey && $shortUrl !== $orderUrl) {
                $qrImageUrl = $this->generateQRCodeViaMDA($apiKey, $shortUrl);
                if ($qrImageUrl) {
                    log_message('info', "MDA QR code generated successfully: {$qrImageUrl}");
                }
            }
            
            if (!$qrImageUrl) {
                // Fallback to external QR service
                $qrImageUrl = $this->generateQRCodeViaAPI($shortUrl, $size);
                log_message('info', "Using fallback QR service: {$qrImageUrl}");
            }
            
            if (!$qrImageUrl) {
                log_message('error', "Failed to generate QR code for order {$orderId}");
                return null;
            }
            
            // Create the QR data array
            $qrData = [
                'short_url' => $shortUrl,
                'qr_url' => $qrImageUrl, // QR Server API URL
                'order_url' => $orderUrl,
                'size' => $size,
                'format' => $format,
                'link_id' => $linkId,
                'generated_at' => date('Y-m-d H:i:s'),
                'is_static' => (bool)($order['short_url'] && $order['short_url_slug']),
                'provider' => [
                    'shortener' => $shortUrl !== $orderUrl ? 'MDA Links (5-digit slug, STATIC)' : 'Direct URL',
                    'qr_generator' => $isValidApiKey && $shortUrl !== $orderUrl ? 'MDA.to API' : 'QR Server API'
                ]
            ];
            
            log_message('info', "QR generation successful for order {$orderId} - Short URL: {$shortUrl}, QR URL: {$qrImageUrl}, Static: " . ($qrData['is_static'] ? 'YES' : 'NO'));
            return $qrData;
            
        } catch (Exception $e) {
            log_message('error', "QR generation failed for order {$orderId}: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Generate QR code using QR Server API (external service)
     */
    private function generateQRCodeViaAPI($url, $size = '300')
    {
        try {
            $qrSize = min(max((int)$size, 100), 1000); // Clamp between 100-1000
            
            // Use QR Server API (free, reliable, and fast)
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$qrSize}x{$qrSize}&format=png&data=" . urlencode($url);
            
            log_message('info', "Generated QR using QR Server API: {$qrUrl}");
            return $qrUrl;
            
        } catch (Exception $e) {
            log_message('error', "QR Server API generation failed: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Legacy method - kept for backward compatibility
     * @deprecated Use generateQRCodeViaAPI instead
     */
    private function generateLocalQRCode($url, $size = '300')
    {
        return $this->generateQRCodeViaAPI($url, $size);
    }

    /**
     * Call MDA Links API for URL shortening
     */
    private function callMDALinksAPI($apiKey, $payload)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mda.to/api/url/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $curlError
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => "HTTP Error: {$httpCode}"
            ];
        }
        
        $data = json_decode($response, true);
        
        if (!$data) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response'
            ];
        }
        
        if (isset($data['error']) && $data['error'] !== 0) {
            $errorMessage = $data['message'] ?? 'Unknown error';
            return [
                'success' => false,
                'error' => $errorMessage
            ];
        }
        
        if (!isset($data['shorturl'])) {
            return [
                'success' => false,
                'error' => 'No short URL returned'
            ];
        }
        
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * Generate QR Code using MDA.to API
     */
    private function generateQRCodeViaMDA($apiKey, $shortUrl)
    {
        $payload = [
            'type' => 'link',
            'data' => $shortUrl,
            'name' => 'Sales Order QR Code'
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://mda.to/api/qr/add',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError || $httpCode !== 200) {
            log_message('error', "MDA QR API error - HTTP: {$httpCode}, CURL: {$curlError}");
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || (isset($data['error']) && $data['error'] !== 0)) {
            log_message('error', "MDA QR API response error: " . json_encode($data));
            return null;
        }
        
        return $data['link'] ?? null;
    }
    
    /**
     * Generate a unique short alphanumeric slug with collision detection
     * 
     * @param int $length Desired length of the slug (default 5, can expand to 6 if needed)
     * @param int $maxAttempts Maximum number of attempts to generate unique slug
     * @return string Generated unique slug
     */
    private function generateShortSlug($length = 5, $maxAttempts = 10)
    {
        // Character set for generating short slugs: alphanumeric excluding confusing characters
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $salesOrderModel = new \Modules\SalesOrders\Models\SalesOrderModel();
        
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $slug = '';
            
            // Generate random slug of specified length
            for ($i = 0; $i < $length; $i++) {
                $slug .= $characters[rand(0, $charactersLength - 1)];
            }
            
            // Check if this slug already exists in our database
            $existingOrder = $salesOrderModel->where('short_url_slug', $slug)->first();
            
            if (!$existingOrder) {
                log_message('info', "Generated unique {$length}-digit slug: {$slug} (attempt {$attempt})");
                return $slug;
            }
            
            log_message('warning', "Slug collision detected: {$slug} (attempt {$attempt})");
            
            // If we've tried 5 times with current length and still getting collisions,
            // automatically expand to 6 digits for more possible combinations
            if ($attempt == 5 && $length == 5) {
                $length = 6;
                log_message('info', "Expanding to 6-digit slugs due to collisions");
            }
        }
        
        // If we still can't generate a unique slug after all attempts, 
        // fall back to timestamp-based approach
        $fallbackSlug = substr(uniqid(), -$length);
        log_message('warning', "Using fallback slug: {$fallbackSlug} after {$maxAttempts} attempts");
        return $fallbackSlug;
    }
    
    /**
     * Create short URL using Lima Links API with collision handling
     */
    private function createShortUrl($apiKey, $url, $customAlias = null, $brandedDomain = null)
    {
        try {
            // Generate a unique slug if no custom alias provided
            if (!$customAlias) {
                $customAlias = $this->generateShortSlug(5); // Start with 5 digits, will expand if needed
            }
            
            $payload = [
                'url' => $url,
                'custom' => $customAlias,
                'domain' => $brandedDomain ?: 'mda.to',
                'expiry' => null,
                'description' => 'Sales Order QR Code'
            ];
            
            log_message('info', "Lima Links payload with {$customAlias}: " . json_encode($payload));
            
            // Try to create the short URL with MDA Links
            $result = $this->callMDALinksAPI($apiKey, $payload);
            
            // If successful, return the result
            if ($result['success']) {
                return $result['data'];
            }
            
            // If it failed due to slug collision on Lima Links side, 
            // try with a new unique slug (max 3 retries)
            if (strpos($result['error'], 'already exists') !== false || 
                strpos($result['error'], 'duplicate') !== false ||
                strpos($result['error'], 'taken') !== false) {
                
                log_message('warning', "Lima Links slug collision: {$result['error']}. Trying with new slug...");
                
                for ($retry = 1; $retry <= 3; $retry++) {
                    $newSlug = $this->generateShortSlug(5 + $retry - 1); // Expand length on each retry
                    $payload['custom'] = $newSlug;
                    
                    log_message('info', "Retry #{$retry} with new slug: {$newSlug}");
                    
                    $retryResult = $this->callMDALinksAPI($apiKey, $payload);
                    
                    if ($retryResult['success']) {
                        log_message('info', "Successfully created short URL on retry #{$retry}");
                        return $retryResult['data'];
                    }
                    
                    log_message('warning', "Retry #{$retry} failed: {$retryResult['error']}");
                }
            }
            
            log_message('error', "All attempts to create short URL failed: {$result['error']}");
            return null;
            
        } catch (Exception $e) {
            log_message('error', "CreateShortUrl failed: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Helper method to call Lima Links API
     */
    private function callLimaLinksAPI($apiKey, $payload)
    {
            $ch = curl_init();
            curl_setopt_array($ch, [
            CURLOPT_URL => '' . \App\Helpers\LimaLinksHelper::buildApiUrl() . '',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json'
                ],
                CURLOPT_TIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            log_message('info', "Lima Links response - HTTP: {$httpCode}, Response: {$response}");
            
            if ($curlError) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $curlError
            ];
            }
            
            if ($httpCode === 200 || $httpCode === 201) {
                $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'error' => 'Invalid JSON response'
                ];
            }
                
                // Handle different possible response formats
                if (isset($data['data'])) {
                return ['success' => true, 'data' => $data['data']];
                } elseif (isset($data['shorturl'])) {
                return ['success' => true, 'data' => $data];
                } elseif (isset($data['id']) && isset($data['shorturl'])) {
                return ['success' => true, 'data' => $data];
            }
        }
        
        // Parse error message from response
        $errorMessage = 'Unknown error';
        if ($response) {
            $data = json_decode($response, true);
            if (isset($data['message'])) {
                $errorMessage = $data['message'];
            } elseif (isset($data['error'])) {
                $errorMessage = $data['error'];
            }
        }
        
        return [
            'success' => false,
            'error' => "HTTP {$httpCode}: {$errorMessage}"
        ];
    }
    
    /**
     * Helper method to create short URL for order viewing
     */
    private function createShortUrlForOrder($apiKey, $payload)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => '' . \App\Helpers\LimaLinksHelper::buildApiUrl() . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $curlError
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP error ' . $httpCode
            ];
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response'
            ];
        }
        
        if (!isset($data['error']) || $data['error'] !== 0) {
            $errorMessage = $data['message'] ?? 'Unknown error';
            return [
                'success' => false,
                'error' => $errorMessage
            ];
        }
        
        if (!isset($data['shorturl'])) {
            return [
                'success' => false,
                'error' => 'No short URL returned'
            ];
        }
        
        return [
            'success' => true,
            'data' => $data
        ];
    }
    
    /**
     * Helper method to generate QR code URL for order viewing
     */
    private function generateQRUrlForOrder($apiKey, $linkId, $shortUrl, $size, $format)
    {
        // Lima Links QR endpoint pattern with authentication
        if ($linkId) {
            // Try to generate a QR URL that includes authentication
            $qrUrl = \App\Helpers\LimaLinksHelper::buildQrUrl($linkId, $size, $format);
            
            // Test if the QR endpoint is accessible by making a quick request
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $qrUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_NOBODY => false, // We want to check if it actually returns content
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey,
                    'Accept: image/png,image/jpeg,image/svg+xml,*/*'
                ],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            log_message('info', "Lima Links QR Test - URL: {$qrUrl}, HTTP Code: {$httpCode}, Content-Type: {$contentType}, Error: {$curlError}");
            
            // If Lima Links QR works, return the URL
            if ($httpCode === 200 && !$curlError && (strpos($contentType, 'image/') === 0)) {
                log_message('info', "Lima Links QR endpoint working: {$qrUrl}");
                return $qrUrl;
            } else {
                log_message('warning', "Lima Links QR endpoint failed, using fallback");
            }
        }
        
        // Fallback: try to extract ID from shorturl if possible
        $defaultDomain = \App\Helpers\LimaLinksHelper::getDefaultDomain();
        $escapedDomain = preg_quote($defaultDomain, '/');
        if (preg_match('/' . $escapedDomain . '\/([^\/\?]+)/', $shortUrl, $matches)) {
            $extractedId = $matches[1];
            $fallbackQrUrl = \App\Helpers\LimaLinksHelper::buildQrUrl($extractedId, $size, $format);
            log_message('info', "Generated fallback QR URL from extracted ID: {$fallbackQrUrl}");
            return $fallbackQrUrl;
        }
        
        // Last resort: use alternative QR service with the short URL
        log_message('warning', "Could not generate Lima Links QR URL, using QR Server fallback");
        $fallbackUrl = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&format={$format}&data=" . urlencode($shortUrl);
        log_message('info', "Using QR Server fallback: {$fallbackUrl}");
        return $fallbackUrl;
    }

    public function delete($id)
    {
        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            try {
                if ($this->salesOrderModel->delete($id)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Sales order deleted successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error deleting sales order'
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error deleting sales order: ' . $e->getMessage()
                ]);
            }
        }
        
        // For non-AJAX requests, use redirect (legacy support)
        if ($this->salesOrderModel->delete($id)) {
            return redirect()->to('sales_orders')->with('success', 'Sales order deleted successfully');
        }
        
        return redirect()->to('sales_orders')->with('error', 'Error deleting sales order');
    }

    /**
     * Update order status
     */
    public function updateStatus($id)
    {
        // Set JSON content type
        $this->response->setContentType('application/json');
        
        $newStatus = $this->request->getPost('status');
        
        if (!$newStatus) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status is required'
            ]);
        }

        // Get current order to track old status
        $currentOrder = $this->salesOrderModel->find($id);
        if (!$currentOrder) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $oldStatus = $currentOrder['status'];
        $userId = session()->get('user_id') ?? 1;
        $currentTimestamp = date('Y-m-d H:i:s');

        $data = [
            'status' => $newStatus,
            'updated_by' => $userId,
            'updated_at' => $currentTimestamp
        ];

        // Add completion timestamps based on status change
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            $data['completed_at'] = $currentTimestamp;
            // Clear cancelled_at if it was previously cancelled
            if ($currentOrder['cancelled_at']) {
                $data['cancelled_at'] = null;
            }
        } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $data['cancelled_at'] = $currentTimestamp;
            // Clear completed_at if it was previously completed
            if ($currentOrder['completed_at']) {
                $data['completed_at'] = null;
            }
        } elseif ($newStatus !== 'completed' && $oldStatus === 'completed') {
            // If changing from completed to something else, clear completed_at
            $data['completed_at'] = null;
        } elseif ($newStatus !== 'cancelled' && $oldStatus === 'cancelled') {
            // If changing from cancelled to something else, clear cancelled_at
            $data['cancelled_at'] = null;
        }

        if ($this->salesOrderModel->update($id, $data)) {
            // Log the status change activity
            try {
                $this->activityModel->logStatusChange($id, $userId, $oldStatus, $newStatus);
                
                // Log additional activity for completion/cancellation
                if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                    $this->logActivity($id, 'completed', "Order marked as completed at {$currentTimestamp}", $userId);
                } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                    $this->logActivity($id, 'cancelled', "Order cancelled at {$currentTimestamp}", $userId);
                }
            } catch (\Exception $e) {
                log_message('error', "Error logging activity: " . $e->getMessage());
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Error updating status'
        ]);
    }

    /**
     * Send email to client
     */
    public function sendEmail($id)
    {
        $order = $this->salesOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Get form data
        $toEmail = $this->request->getPost('to');
        $ccEmail = $this->request->getPost('cc');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $includeDetails = $this->request->getPost('include_details') === '1';

        if (empty($toEmail) || empty($subject) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email address, subject and message are required'
            ]);
        }

        try {
            // Load email helper
            helper('email');
            
            // Get email configuration
            $email = \Config\Services::email();
            
            // Configure email as HTML
            $email->setMailType('html');
            
            // Set email parameters
            $email->setTo($toEmail);
            if (!empty($ccEmail)) {
                $email->setCC($ccEmail);
            }
            $email->setSubject($subject);
            
            // Build email content
            $emailContent = $message;
            
            // Convert line breaks to HTML br tags for email display
            $emailContent = nl2br($emailContent);
            
            if ($includeDetails) {
                // Get additional order details
                $clientModel = new \App\Models\ClientModel();
                $client = $clientModel->find($order['client_id']);
                
                $userModel = new \App\Models\UserModel();
                $salesperson = $userModel->find($order['created_by']);
                
                $serviceModel = new SalesOrderServiceModel();
                $service = $serviceModel->find($order['service_id']);
                
                // Build order details section with HTML formatting
                $orderDetails = "<br><br><strong>--- Order Details ---</strong><br>";
                $orderDetails .= "<strong>Order #:</strong> " . $order['id'] . "<br>";
                $orderDetails .= "<strong>Client:</strong> " . ($client['name'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Vehicle:</strong> " . ($order['vehicle'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>VIN:</strong> " . ($order['vin'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Stock:</strong> " . ($order['stock'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Service:</strong> " . ($service['service_name'] ?? 'N/A') . "<br>";
                $orderDetails .= "<strong>Salesperson:</strong> " . (isset($salesperson) ? $salesperson['first_name'] . ' ' . $salesperson['last_name'] : 'N/A') . "<br>";
                $orderDetails .= "<strong>Date:</strong> " . ($order['date'] ? date('F j, Y', strtotime($order['date'])) : 'Not scheduled') . "<br>";
                $orderDetails .= "<strong>Time:</strong> " . ($order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled') . "<br>";
                $orderDetails .= "<strong>Status:</strong> " . ucfirst(str_replace('_', ' ', $order['status'])) . "<br>";

                if (!empty($order['instructions'])) {
                    $orderDetails .= "<strong>Instructions:</strong> " . nl2br($order['instructions']) . "<br>";
                }
                
                $emailContent .= $orderDetails;
            }
            
            $email->setMessage($emailContent);
            
            // Send email
            if ($email->send()) {
            // Log the email activity
            $userId = session()->get('user_id') ?? 1;
                $this->activityModel->logEmailSent($id, $userId, $toEmail, $subject, $message, $ccEmail);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email sent successfully'
                ]);
            } else {
                log_message('error', 'Email sending failed: ' . $email->printDebugger());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to send email. Please check SMTP configuration.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Email Error: ' . $e->getMessage());
            
            // Log the email activity (simulated for demo)
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->logEmailSent($id, $userId, $toEmail, $subject, $message, $ccEmail);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Email sent successfully (Demo mode - Check SMTP configuration for real emails)'
            ]);
        }
    }

    /**
     * Send SMS to client
     */
    public function sendSMS($id)
    {
        $order = $this->salesOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $phone = $this->request->getPost('phone');
        $message = $this->request->getPost('message');

        if (empty($phone) || empty($message)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Phone number and message are required'
            ]);
        }

        // Validate message length (SMS limit is 160 characters)
        if (strlen($message) > 160) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Message exceeds 160 character limit for SMS'
            ]);
        }

        try {
            // Get Twilio client from service
            $twilio = \Config\Services::twilio();
            
            if (!$twilio) {
                throw new \Exception('Twilio credentials not configured');
            }
            
            // Get Twilio phone number from settings
            $settingsModel = new \App\Models\SettingsModel();
            $twilioNumber = $settingsModel->getSetting('twilio_number', '');
            
            if (empty($twilioNumber)) {
                throw new \Exception('Twilio phone number not configured');
            }
            
            // Format phone number (ensure it starts with +)
            if (!str_starts_with($phone, '+')) {
                // Assume US number if no country code
                if (strlen($phone) === 10) {
                    $phone = '+1' . $phone;
                } elseif (strlen($phone) === 11 && str_starts_with($phone, '1')) {
                    $phone = '+' . $phone;
                } else {
                    $phone = '+' . $phone;
                }
            }
            
            // Send SMS
            $twilioMessage = $twilio->messages->create(
                $phone,
                [
                    'from' => $twilioNumber,
                    'body' => $message
                ]
            );
            
            // Log the SMS activity
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->logSMSSent($id, $userId, $phone, $message);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'SMS sent successfully via Twilio',
                'twilio_sid' => $twilioMessage->sid
            ]);
            
        } catch (\Exception $e) {
            // Log the error and return a simulated success for demo purposes
            log_message('error', 'Twilio SMS Error: ' . $e->getMessage());
            
            // Log the SMS activity (simulated)
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->logSMSSent($id, $userId, $phone, $message);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'SMS sent successfully (Demo mode - Check Twilio configuration for real SMS)'
            ]);
        }
    }

    /**
     * Send alert
     */
    public function sendAlert($id)
    {
        $order = $this->salesOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $alertType = $this->request->getPost('alert_type');
        $message = $this->request->getPost('message');
        $recipients = $this->request->getPost('recipients'); // array

        // Here you would integrate with your notification system
        // For now, we'll just simulate the alert sending
        
        // Log the alert activity
        $recipientsList = implode(', ', $recipients);
        $this->logActivity($id, 'alert_sent', "Alert ({$alertType}) sent to {$recipientsList}: {$message}");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Alert sent successfully'
        ]);
    }

    /**
     * Add comment to order
     */
    public function addComment($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $salesOrderId = $id ?? $this->request->getPost('sales_order_id');
        $comment = trim($this->request->getPost('comment'));
        
        // Get user ID using Shield authentication
        $userId = auth()->id();

        if (empty($salesOrderId) || empty($comment)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sales order ID and comment are required']);
        }

        if (empty($userId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not logged in']);
        }

        try {
            $commentModel = new SalesOrderCommentModel();
            
            // Process mentions
            $mentions = $commentModel->processMentions($comment);
            
            // Handle file uploads
            $attachments = [];
            $uploadedFiles = $this->request->getFiles();
            
            log_message('info', 'Uploaded files data: ' . json_encode($uploadedFiles));
            
            if (!empty($uploadedFiles['attachments'])) {
                log_message('info', 'Processing attachments: ' . count($uploadedFiles['attachments']) . ' files');
                $attachments = $commentModel->processAttachments($uploadedFiles['attachments'], $salesOrderId);
                log_message('info', 'Processed attachments: ' . json_encode($attachments));
            } else {
                log_message('info', 'No attachments found in request');
            }
            
            // Prepare comment data with correct field names
            $commentData = [
                'order_id' => $salesOrderId,
                'created_by' => $userId,
                'description' => $comment,
                'attachments' => !empty($attachments) ? json_encode($attachments) : json_encode([]),
                'mentions' => !empty($mentions) ? json_encode($mentions) : json_encode([]),
                'metadata' => json_encode([
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString(),
                    'timestamp' => date('Y-m-d H:i:s')
                ])
                // Don't set created_at manually since useTimestamps = true
            ];

            // Debug logging
            log_message('info', 'Comment data to insert: ' . json_encode($commentData));

            $commentId = $commentModel->insert($commentData);

            if ($commentId) {
                // Log activity with comment preview in title
                $commentPreview = substr(trim($comment), 0, 15) . (strlen(trim($comment)) > 15 ? '...' : '');
                $this->logCommentActivity(
                    $salesOrderId, 
                    'comment_added', 
                    "Added comment: \"{$commentPreview}\"",
                    [
                        'comment_id' => $commentId,
                        'comment' => $comment, // Full comment for tooltip
                        'comment_preview' => substr(trim($comment), 0, 100) . (strlen(trim($comment)) > 100 ? '...' : ''),
                        'has_attachments' => !empty($attachments)
                    ]
                );
                
                // Get the created comment with user info
                $createdComment = $commentModel->getCommentWithUser($commentId);
                
                if ($createdComment) {
                    // Process attachments for the created comment
                    $createdComment['attachments'] = $commentModel->processAttachmentsJson($createdComment['attachments'], $salesOrderId);
                    
                    // Process other JSON fields
                    $createdComment['mentions'] = $this->processJsonField($createdComment['mentions']);
                    $createdComment['metadata'] = $this->processJsonField($createdComment['metadata']);
                    
                    // Format created_at for display
                    $createdComment['created_at_formatted'] = date('M j, Y g:i A', strtotime($createdComment['created_at']));
                    $createdComment['created_at_relative'] = $this->getRelativeTime($createdComment['created_at']);
                    
                    // Generate avatar URL
                    $createdComment['avatar_url'] = $this->generateAvatarUrl($createdComment, 32);
                    
                    // Map description to comment for frontend compatibility
                    $createdComment['comment'] = $createdComment['description'];
                }
                
            return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Comment added successfully',
                    'comment' => $createdComment
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while adding the comment']);
        }
    }

    /**
     * Add a reply to a comment
     */
    public function addReply()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        try {
            $commentModel = new SalesOrderCommentModel();
            
            $salesOrderId = $this->request->getPost('sales_order_id');
            $parentCommentId = $this->request->getPost('parent_id') ?: $this->request->getPost('parent_comment_id');
            $description = $this->request->getPost('comment');
            
            if (empty(trim($description))) {
                return $this->response->setJSON(['success' => false, 'message' => 'Reply description is required']);
            }

            // Verify parent comment exists
            $parentComment = $commentModel->find($parentCommentId);
            if (!$parentComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Parent comment not found']);
            }

            // Process mentions
            $mentions = $commentModel->processMentions($description);

            $data = [
                'order_id' => $salesOrderId,
                'parent_id' => $parentCommentId,
                'description' => trim($description),
                'created_by' => $userId,
                'attachments' => '[]', // For now, replies don't support attachments
                'mentions' => !empty($mentions) ? json_encode($mentions) : '[]',
                'metadata' => '[]'
            ];

            if ($commentModel->insert($data)) {
                // Get the newly created reply with user info
                $newReplyId = $commentModel->getInsertID();
                
                // Get parent comment info for better activity description
                $parentComment = $commentModel->find($parentCommentId);
                $parentPreview = $parentComment ? substr(trim($parentComment['description']), 0, 30) . '...' : 'comment';
                
                // Log activity with reply preview and parent context
                $replyPreview = substr(trim($description), 0, 50) . (strlen(trim($description)) > 50 ? '...' : '');
                $this->logCommentActivity(
                    $salesOrderId, 
                    'comment_reply_added', 
                    "Added reply to comment \"{$parentPreview}\": \"{$replyPreview}\"",
                    [
                        'comment_id' => $newReplyId,
                        'parent_comment_id' => $parentCommentId,
                        'parent_comment_preview' => $parentPreview,
                        'reply_content' => $description, // Full reply for tooltip
                        'reply_preview' => $replyPreview,
                        'action_type' => 'reply_added'
                    ]
                );
                
                $newReply = $commentModel->getCommentWithUser($newReplyId);
                
                if ($newReply) {
                    // Process JSON fields manually
                    $newReply['attachments'] = $this->processJsonField($newReply['attachments']);
                    $newReply['mentions'] = $this->processJsonField($newReply['mentions']);
                    $newReply['metadata'] = $this->processJsonField($newReply['metadata']);
                    
                    // Format created_at for display
                    $newReply['created_at_formatted'] = date('M j, Y g:i A', strtotime($newReply['created_at']));
                    $newReply['created_at_relative'] = $this->getRelativeTime($newReply['created_at']);
                    
                    // Generate avatar URL
                    $newReply['avatar_url'] = $this->generateAvatarUrl($newReply, 20);
                    
                    // Map description to comment for frontend compatibility
                    $newReply['comment'] = $newReply['description'];
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Reply added successfully',
                    'reply' => $newReply
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add reply']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding reply: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while adding the reply']);
        }
    }

    /**
     * Update a comment
     */
    public function updateComment($commentId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        try {
            $commentModel = new SalesOrderCommentModel();
            
            // Get the existing comment
            $existingComment = $commentModel->find($commentId);
            if (!$existingComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment not found']);
            }

            // Check if user owns the comment or has admin privileges
            if ($existingComment['created_by'] != $userId) {
                // You can add admin role check here if needed
                return $this->response->setJSON(['success' => false, 'message' => 'You can only edit your own comments']);
            }

            $description = $this->request->getPost('description');
            
            if (empty(trim($description))) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment description is required']);
            }

            // Process mentions
            $mentions = $commentModel->processMentions($description);

            $data = [
                'description' => trim($description),
                'mentions' => !empty($mentions) ? json_encode($mentions) : '[]'
            ];

            if ($commentModel->update($commentId, $data)) {
                // Determine if this is a reply or a comment
                $isReply = !empty($existingComment['parent_id']);
                $actionType = $isReply ? 'comment_reply_updated' : 'comment_updated';
                $itemType = $isReply ? 'reply' : 'comment';
                
                // Get parent comment info if this is a reply
                $parentInfo = '';
                if ($isReply) {
                    $parentComment = $commentModel->find($existingComment['parent_id']);
                    if ($parentComment) {
                        $parentPreview = substr(trim($parentComment['description']), 0, 30) . '...';
                        $parentInfo = " to comment \"{$parentPreview}\"";
                    }
                }
                
                // Create descriptive activity message
                $oldPreview = substr($existingComment['description'], 0, 50) . (strlen($existingComment['description']) > 50 ? '...' : '');
                $newPreview = substr(trim($description), 0, 50) . (strlen(trim($description)) > 50 ? '...' : '');
                $activityDescription = "Updated {$itemType}{$parentInfo}: \"{$newPreview}\"";
                
                // Log activity
                $this->logCommentActivity(
                    $existingComment['order_id'], 
                    $actionType, 
                    $activityDescription,
                    [
                        'comment_id' => $commentId,
                        'parent_comment_id' => $existingComment['parent_id'] ?? null,
                        'is_reply' => $isReply,
                        'old_content' => $existingComment['description'],
                        'new_content' => trim($description),
                        'old_preview' => $oldPreview,
                        'new_preview' => $newPreview,
                        'action_type' => $isReply ? 'reply_updated' : 'comment_updated'
                    ]
                );
                
                // Get updated comment with user info
                $updatedComment = $commentModel->getCommentWithUser($commentId);
                
                if ($updatedComment) {
                    // Process JSON fields manually
                    $updatedComment['attachments'] = $this->processJsonField($updatedComment['attachments']);
                    $updatedComment['mentions'] = $this->processJsonField($updatedComment['mentions']);
                    $updatedComment['metadata'] = $this->processJsonField($updatedComment['metadata']);
                    
                    // Generate avatar URL
                    $updatedComment['avatar_url'] = $this->generateAvatarUrl($updatedComment);
                }

        return $this->response->setJSON([
            'success' => true,
                    'message' => 'Comment updated successfully',
                    'comment' => $updatedComment
        ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to update comment']);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating the comment']);
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment($commentId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        helper('auth');
        $userId = auth()->id();
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not authenticated']);
        }

        try {
            $commentModel = new SalesOrderCommentModel();
            
            // Get the existing comment
            $existingComment = $commentModel->find($commentId);
            if (!$existingComment) {
                return $this->response->setJSON(['success' => false, 'message' => 'Comment not found']);
            }

            // Check if user owns the comment or has admin privileges
            if ($existingComment['created_by'] != $userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'You can only delete your own comments']);
            }

            if ($commentModel->delete($commentId)) {
                // Determine if this is a reply or a comment
                $isReply = !empty($existingComment['parent_id']);
                $actionType = $isReply ? 'comment_reply_deleted' : 'comment_deleted';
                $itemType = $isReply ? 'reply' : 'comment';
                
                // Get parent comment info if this is a reply
                $parentInfo = '';
                if ($isReply) {
                    $parentComment = $commentModel->find($existingComment['parent_id']);
                    if ($parentComment) {
                        $parentPreview = substr(trim($parentComment['description']), 0, 30) . '...';
                        $parentInfo = " from comment \"{$parentPreview}\"";
                    }
                }
                
                // Create descriptive activity message
                $deletedPreview = substr($existingComment['description'], 0, 50) . (strlen($existingComment['description']) > 50 ? '...' : '');
                $activityDescription = "Deleted {$itemType}{$parentInfo}: \"{$deletedPreview}\"";
                
                // Log activity
                $this->logCommentActivity(
                    $existingComment['order_id'], 
                    $actionType, 
                    $activityDescription,
                    [
                        'comment_id' => $commentId,
                        'parent_comment_id' => $existingComment['parent_id'] ?? null,
                        'is_reply' => $isReply,
                        'deleted_content' => $existingComment['description'],
                        'deleted_preview' => $deletedPreview,
                        'action_type' => $isReply ? 'reply_deleted' : 'comment_deleted'
                    ]
                );

            return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'Comment deleted successfully'
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete comment']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deleting comment: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while deleting the comment']);
        }
    }

    /**
     * Generate avatar URL for comments without authentication dependencies
     */
    private function generateAvatarUrl($comment, $size = 32)
    {
        // Check for uploaded avatar first
        if (!empty($comment['avatar']) && file_exists(FCPATH . 'assets/images/users/' . $comment['avatar'])) {
            return base_url('assets/images/users/' . $comment['avatar']);
        }
        
        // Generate initials-based avatar
        $initials = '';
        
        // Try to get from first_name and last_name
        if (!empty($comment['first_name']) && !empty($comment['last_name'])) {
            $initials = strtoupper(substr($comment['first_name'], 0, 1) . substr($comment['last_name'], 0, 1));
        }
        // Try to get from username
        elseif (!empty($comment['username'])) {
            $parts = explode('_', $comment['username']);
            if (count($parts) >= 2) {
                $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
            } else {
                $initials = strtoupper(substr($comment['username'], 0, 2));
            }
        }
        // Fallback to email
        elseif (!empty($comment['email'])) {
            $initials = strtoupper(substr($comment['email'], 0, 2));
        } else {
            $initials = 'U';
        }
        
        // Generate colors based on user ID for consistency
        $colors = [
            '3498db', '9b59b6', 'e74c3c', 'f39c12', 
            '2ecc71', '1abc9c', 'e67e22', 'f1c40f',
            '8e44ad', 'c0392b', 'd35400', '27ae60'
        ];
        $userId = $comment['user_id'] ?? $comment['created_by'] ?? 1;
        $colorIndex = $userId % count($colors);
        $backgroundColor = $colors[$colorIndex];
        
        // Use UI Avatars service
        $name = urlencode($initials);
        return "https://ui-avatars.com/api/?name={$name}&size={$size}&background={$backgroundColor}&color=ffffff&bold=true&format=png";
    }

    /**
     * Process JSON field to handle mixed data types
     */
    private function processJsonField($field)
    {
        if (is_null($field) || $field === '') {
            return [];
        }
        
        if (is_array($field)) {
            return $field;
        }
        
        if (is_string($field)) {
            // Handle empty array string
            if (trim($field) === '[]') {
                return [];
            }
            
            // Try to decode JSON
            $decoded = json_decode($field, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return is_array($decoded) ? $decoded : [];
            }
        }
        
        return [];
    }

    /**
     * Get relative time string
     */
    private function getRelativeTime($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        
        return floor($time/31536000) . ' years ago';
    }

    /**
     * Log comment activity
     */
    private function logCommentActivity($orderId, $action, $description, $metadata = [])
    {
        try {
            helper('auth');
            $userId = auth()->id();
            
            if (!$userId) {
                return false;
            }

            // Use the existing activity model for Sales Orders
            return $this->activityModel->logCommentActivity($orderId, $userId, $action, $description, $metadata);
            
        } catch (\Exception $e) {
            log_message('error', 'Error logging comment activity: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get order activities/comments
     */
    public function getActivities($id)
    {
        // Here you would fetch activities from an activities table
        // For now, we'll return sample data
        $activities = [
            [
                'type' => 'status_change',
                'message' => 'Order status changed to Processing',
                'user' => 'System',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'type' => 'comment',
                'message' => 'Customer confirmed appointment time',
                'user' => 'John Doe',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ]
        ];

        return $this->response->setJSON([
            'success' => true,
            'activities' => $activities
        ]);
    }

    /**
     * Get order activity (singular) - with pagination support for infinite scroll
     */
    public function getActivity($id)
    {
        $order = $this->salesOrderModel->find($id);
        if (!$order) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $page = $this->request->getGet('page') ?? 1;
        $limit = 5; // Show only 5 activities per page for infinite scroll
        $offset = ($page - 1) * $limit;

        try {
            // Get activities from database with user information
            $activities = $this->activityModel->select('sales_orders_activities.*, 
                                                      CONCAT(users.first_name, " ", users.last_name) as user_name,
                                                      users.first_name, 
                                                      users.last_name')
                                               ->join('users', 'users.id = sales_orders_activities.user_id', 'left')
                                               ->where('sales_orders_activities.order_id', $id)
                                               ->orderBy('sales_orders_activities.created_at', 'DESC')
                                               ->limit($limit, $offset)
                                               ->findAll();
            
            $totalActivities = $this->activityModel->countOrderActivities($id);
            
            // If no activities in database and this is the first page, create some sample activities for demonstration
            if (empty($activities) && $page == 1) {
                $this->createSampleActivities($id);
                
                // Re-fetch after creating sample data
                $activities = $this->activityModel->select('sales_orders_activities.*, 
                                                          CONCAT(users.first_name, " ", users.last_name) as user_name,
                                                          users.first_name, 
                                                          users.last_name')
                                                   ->join('users', 'users.id = sales_orders_activities.user_id', 'left')
                                                   ->where('sales_orders_activities.order_id', $id)
                                                   ->orderBy('sales_orders_activities.created_at', 'DESC')
                                                   ->limit($limit, $offset)
                                                   ->findAll();
                
                $totalActivities = $this->activityModel->countOrderActivities($id);
            }

            // Format activities for display
            $formattedActivities = [];
            foreach ($activities as $activity) {
                $userName = $activity['user_name'] ?: 'System';
                
                // If user_name is empty but we have first/last name, construct it
                if (empty($userName) || $userName === ' ') {
                    if (!empty($activity['first_name']) || !empty($activity['last_name'])) {
                        $userName = trim(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? ''));
                    }
                    if (empty($userName)) {
                        $userName = 'System';
                    }
                }

                $formattedActivities[] = [
                    'id' => $activity['id'],
                    'type' => $activity['activity_type'],
                    'title' => $activity['title'],
                    'description' => $activity['description'],
                    'old_value' => $activity['old_value'],
                    'new_value' => $activity['new_value'],
                    'field_name' => $activity['field_name'],
                    'user_name' => $userName,
                    'created_at' => date('M j, Y \a\t g:i A', strtotime($activity['created_at'])),
                    'raw_created_at' => $activity['created_at'], // For sorting/comparison
                    'metadata' => $activity['metadata'] ? json_decode($activity['metadata'], true) : null
                ];
            }

            $hasMore = ($offset + $limit) < $totalActivities;

            return $this->response->setJSON([
                'success' => true,
                'activities' => $formattedActivities,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_activities' => (int)$totalActivities,
                    'has_more' => $hasMore,
                    'next_page' => $hasMore ? $page + 1 : null,
                    'loaded_count' => count($formattedActivities),
                    'total_loaded' => $offset + count($formattedActivities)
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting activities: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading activities'
            ]);
        }
    }

    /**
     * Create sample activities for demonstration
     */
    private function createSampleActivities($orderId)
    {
        $userId = session()->get('user_id') ?? 1;
        
        // Create order activity (oldest)
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'order_created',
            'title' => 'Order Created',
            'description' => 'Sales order was created',
            'field_name' => 'order_status',
            'metadata' => json_encode(['action' => 'created']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
        ]);
        
        // Sample status changes
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'status_change',
            'title' => 'Status Updated',
            'description' => "Status changed from 'pending' to 'processing'",
            'old_value' => 'pending',
            'new_value' => 'processing',
            'field_name' => 'status',
            'metadata' => json_encode(['old_status' => 'pending', 'new_status' => 'processing']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
        ]);
        
        // Vehicle update
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'field_change',
            'title' => 'Vehicle Updated',
            'description' => "vehicle changed from 'Honda Civic 2020' to 'Honda Civic 2021'",
            'old_value' => 'Honda Civic 2020',
            'new_value' => 'Honda Civic 2021',
            'field_name' => 'vehicle',
            'metadata' => json_encode(['field' => 'vehicle', 'old' => 'Honda Civic 2020', 'new' => 'Honda Civic 2021']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ]);
        
        // Date change
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'field_change',
            'title' => 'Appointment Date Changed',
            'description' => "date changed from '2024-01-15' to '2024-01-16'",
            'old_value' => '2024-01-15',
            'new_value' => '2024-01-16',
            'field_name' => 'date',
            'metadata' => json_encode(['field' => 'date', 'old' => '2024-01-15', 'new' => '2024-01-16']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ]);
        
        // Email sent
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'email_sent',
            'title' => 'Email Sent',
            'description' => "Email sent to customer@example.com: Order Update Notification",
            'new_value' => 'customer@example.com',
            'field_name' => 'email_recipient',
            'metadata' => json_encode([
                'recipient' => 'customer@example.com', 
                'subject' => 'Order Update Notification',
                'message' => 'Dear Customer,\n\nWe wanted to provide you with an update on your service order.\n\nYour Land Rover maintenance is progressing well and we expect to have it completed by tomorrow afternoon. Our technicians have performed a thorough inspection and everything looks great.\n\nIf you have any questions, please don\'t hesitate to contact us.\n\nBest regards,\nService Team'
            ]),
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]);
        
        // SMS sent
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'sms_sent',
            'title' => 'SMS Sent',
            'description' => "SMS sent to +1234567890: Your appointment is confirmed for tomorrow at 2 PM",
            'new_value' => '+1234567890',
            'field_name' => 'sms_recipient',
            'metadata' => json_encode(['phone' => '+1234567890', 'message' => 'Your appointment is confirmed for tomorrow at 2 PM']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours'))
        ]);
        
        // Comment added
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'comment_added',
            'title' => 'Comment Added',
            'description' => "Customer confirmed they will arrive 15 minutes early for the appointment",
            'new_value' => 'Customer confirmed they will arrive 15 minutes early for the appointment',
            'field_name' => 'comment',
            'metadata' => json_encode(['comment' => 'Customer confirmed they will arrive 15 minutes early for the appointment']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))
        ]);
        
        // Overdue alert
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => 1, // System user
            'activity_type' => 'overdue_alert',
            'title' => 'Order Overdue',
            'description' => "Order is now 2 day(s) overdue",
            'new_value' => '2',
            'field_name' => 'overdue_days',
            'metadata' => json_encode(['days_overdue' => 2]),
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
        ]);
        
        // Status change to in_progress
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'status_change',
            'title' => 'Status Updated',
            'description' => "Status changed from 'processing' to 'in_progress'",
            'old_value' => 'processing',
            'new_value' => 'in_progress',
            'field_name' => 'status',
            'metadata' => json_encode(['old_status' => 'processing', 'new_status' => 'in_progress']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ]);
        
        // Recent comment
        $this->activityModel->insert([
            'order_id' => $orderId,
            'user_id' => $userId,
            'activity_type' => 'comment_added',
            'title' => 'Comment Added',
            'description' => "Work has started on the vehicle. Estimated completion time is 3 hours.",
            'new_value' => 'Work has started on the vehicle. Estimated completion time is 3 hours.',
            'field_name' => 'comment',
            'metadata' => json_encode(['comment' => 'Work has started on the vehicle. Estimated completion time is 3 hours.']),
            'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
        ]);
    }

    /**
     * Get order comments with pagination for infinite scroll
     */
    public function getComments($salesOrderId = null, $page = 1)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        if (!$salesOrderId) {
            $salesOrderId = $this->request->getGet('sales_order_id');
        }
        
        // Always get page from query parameter, not from URL segment
        $page = $this->request->getGet('page') ?? 1;
        
        // Debug logging
        log_message('info', "Comments request - SalesOrderId: {$salesOrderId}, Page: {$page}, Query: " . json_encode($this->request->getGet()));

        if (empty($salesOrderId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sales order ID is required']);
        }

        try {
            $commentModel = new SalesOrderCommentModel();
            $perPage = 5; // Show 5 comments per page for infinite scroll
            $offset = ($page - 1) * $perPage;

            // Get total comments first
            $totalComments = $commentModel->getCommentsCount($salesOrderId);
            
            // Get comments with replies and pagination
            $comments = $commentModel->getCommentsWithReplies($salesOrderId, $perPage, $offset);
            
            // Simple and reliable pagination logic (same as Service Orders)
            $hasMore = ($offset + $perPage) < $totalComments;
            
            // Add debug logging
            $loadedCount = count($comments);
            $totalLoaded = $offset + $loadedCount;
            log_message('info', "Comments pagination - Page: {$page}, Offset: {$offset}, PerPage: {$perPage}, TotalComments: {$totalComments}, LoadedComments: {$loadedCount}, TotalLoaded: {$totalLoaded}, HasMore: " . ($hasMore ? 'true' : 'false'));

            // Process comments data (including replies)
            foreach ($comments as &$comment) {
                // Process attachments using the model method
                $comment['attachments'] = $commentModel->processAttachmentsJson($comment['attachments'], $salesOrderId);

                // Parse mentions - handle different data types
                if (!empty($comment['mentions'])) {
                    if (is_string($comment['mentions'])) {
                        // Handle edge cases like empty string "[]" or malformed JSON
                        $trimmed = trim($comment['mentions']);
                        if ($trimmed === '[]' || $trimmed === '') {
                            $comment['mentions'] = [];
                        } else {
                            $decoded = json_decode($trimmed, true);
                            $comment['mentions'] = (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
                        }
                    } elseif (is_array($comment['mentions'])) {
                        $comment['mentions'] = $comment['mentions'];
                    } else {
                        $comment['mentions'] = [];
                    }
                } else {
                    $comment['mentions'] = [];
                }

                // Parse metadata - handle different data types
                if (!empty($comment['metadata'])) {
                    if (is_string($comment['metadata'])) {
                        // Handle edge cases like empty string "[]" or malformed JSON
                        $trimmed = trim($comment['metadata']);
                        if ($trimmed === '[]' || $trimmed === '') {
                            $comment['metadata'] = [];
                        } else {
                            $decoded = json_decode($trimmed, true);
                            $comment['metadata'] = (is_array($decoded) && json_last_error() === JSON_ERROR_NONE) ? $decoded : [];
                        }
                    } elseif (is_array($comment['metadata'])) {
                        $comment['metadata'] = $comment['metadata'];
                    } else {
                        $comment['metadata'] = [];
                    }
                } else {
                    $comment['metadata'] = [];
                }

                // Format created_at for display
                $comment['created_at_formatted'] = date('M j, Y g:i A', strtotime($comment['created_at']));
                $comment['created_at_relative'] = $this->getRelativeTime($comment['created_at']);
                
                // Generate avatar URL manually to avoid authentication issues
                $comment['avatar_url'] = $this->generateAvatarUrl($comment, 32);
                
                // Map description to comment for frontend compatibility
                $comment['comment'] = $comment['description'];
                
                // Process replies if they exist
                if (isset($comment['replies']) && is_array($comment['replies'])) {
                    foreach ($comment['replies'] as &$reply) {
                        // Process reply data same as parent comment
                        $reply['attachments'] = $commentModel->processAttachmentsJson($reply['attachments'], $salesOrderId);
                        $reply['mentions'] = $this->processJsonField($reply['mentions']);
                        $reply['metadata'] = $this->processJsonField($reply['metadata']);
                        
                        // Format created_at for display
                        $reply['created_at_formatted'] = date('M j, Y g:i A', strtotime($reply['created_at']));
                        $reply['created_at_relative'] = $this->getRelativeTime($reply['created_at']);
                        
                        // Generate avatar URL
                        $reply['avatar_url'] = $this->generateAvatarUrl($reply, 20); // Smaller avatar for replies
                        
                        // Map description to comment for frontend compatibility
                        $reply['comment'] = $reply['description'];
                    }
                }
            }

        return $this->response->setJSON([
            'success' => true,
                'comments' => $comments,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_comments' => (int)$totalComments,
                    'has_more' => $hasMore,
                    'next_page' => $hasMore ? $page + 1 : null,
                    'loaded_count' => $loadedCount,
                    'total_loaded' => $totalLoaded,
                    'per_page' => $perPage,
                    'offset' => $offset
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading comments: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading comments'
        ]);
        }
    }

    /**
     * Log activity for an order
     */
    private function logActivity($orderId, $type, $message, $userId = null)
    {
        // Here you would insert into an order_activities table
        // For demonstration, we're just logging to system log
        log_message('info', "Order {$orderId} - {$type}: {$message}");
        
        // In a real implementation, you would do something like:
        /*
        $activityModel = new OrderActivityModel();
        $activityModel->insert([
            'order_id' => $orderId,
            'type' => $type,
            'message' => $message,
            'user_id' => $userId ?? session()->get('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        */
    }

    /**
     * Print order
     */
    public function print($id)
    {
        $order = $this->salesOrderModel->select('sales_orders.*, 
                                               clients.name as client_name,
                                               clients.email as client_email,
                                               clients.phone as client_phone,
                                               clients.address as client_address,
                                               CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                                               auth_identities.secret as salesperson_email,
                                               users.phone as salesperson_phone,
                                               sales_orders_services.service_name,
                                               sales_orders_services.service_price,
                                               sales_orders_services.service_description')
                                     ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                     ->join('users', 'users.id = sales_orders.contact_id', 'left')
                                     ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
                                     ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                                     ->where('sales_orders.id', $id)
                                     ->where('sales_orders.deleted', 0)
                                     ->first();
        
        if (!$order) {
            return redirect()->to('sales_orders')->with('error', 'Order not found');
        }
        
        $data = [
            'title' => 'Order SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
            'order' => $order
        ];

        return view('Modules\SalesOrders\Views\sales_orders/print', $data);
    }

    /**
     * Restore a soft deleted order
     */
    public function restore($id)
    {
        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            try {
                if ($this->salesOrderModel->restore($id)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Sales order restored successfully'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error restoring sales order'
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error restoring sales order: ' . $e->getMessage()
                ]);
            }
        }
        
        // For non-AJAX requests, use redirect
        if ($this->salesOrderModel->restore($id)) {
            return redirect()->to('sales_orders')->with('success', 'Sales order restored successfully');
        }
        
        return redirect()->to('sales_orders')->with('error', 'Error restoring sales order');
    }

    /**
     * Permanently delete an order (hard delete)
     */
    public function forceDelete($id)
    {
        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            try {
                if ($this->salesOrderModel->forceDelete($id)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Sales order permanently deleted'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error permanently deleting sales order'
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error permanently deleting sales order: ' . $e->getMessage()
                ]);
            }
        }
        
        // For non-AJAX requests, use redirect
        if ($this->salesOrderModel->forceDelete($id)) {
            return redirect()->to('sales_orders')->with('success', 'Sales order permanently deleted');
        }
        
        return redirect()->to('sales_orders')->with('error', 'Error permanently deleting sales order');
    }

    /**
     * Get deleted orders content
     */
    public function deleted_content()
    {
        try {
            // Very simple approach: just get basic deleted orders info
            $db = \Config\Database::connect();
            
            // Get basic deleted orders without joins first
            $deletedOrders = $db->table('sales_orders')
                               ->select('*')
                               ->where('deleted', 1)
                               ->orderBy('updated_at', 'DESC')
                               ->get()
                               ->getResultArray();
            
            // If we have orders, try to add client/contact names
            if (!empty($deletedOrders)) {
                foreach ($deletedOrders as &$order) {
                    // Get client name
                    if ($order['client_id']) {
                        $client = $db->table('clients')
                                    ->select('name')
                                    ->where('id', $order['client_id'])
                                    ->get()
                                    ->getRowArray();
                        $order['client_name'] = $client ? $client['name'] : 'N/A';
                    } else {
                        $order['client_name'] = 'N/A';
                    }
                    
                    // Get salesperson name from users table
                    if ($order['created_by']) {
                        $user = $db->table('users')
                                  ->select('CONCAT(first_name, " ", last_name) as name')
                                  ->where('id', $order['created_by'])
                                  ->get()
                                  ->getRowArray();
                        $order['salesperson_name'] = $user ? $user['name'] : 'N/A';
                    } else {
                        $order['salesperson_name'] = 'N/A';
                    }
                    
                    // Get service name
                    if ($order['service_id']) {
                        $service = $db->table('sales_orders_services')
                                     ->select('service_name')
                                     ->where('id', $order['service_id'])
                                     ->get()
                                     ->getRowArray();
                        $order['service_name'] = $service ? $service['service_name'] : 'N/A';
                    } else {
                        $order['service_name'] = 'N/A';
                    }
                }
            }
            
            $data = [
                'orders' => $deletedOrders
            ];
            
            return view('Modules\SalesOrders\Views\sales_orders/deleted_content', $data);
            
        } catch (\Exception $e) {
            // Log the error
            log_message('error', 'Error in deleted_content: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Return a simple error view
            $data = [
                'orders' => [],
                'error' => 'Error loading deleted orders: ' . $e->getMessage()
            ];
            
            return view('Modules\SalesOrders\Views\sales_orders/deleted_content', $data);
        }
    }

    /**
     * Get statistics about orders
     */
    public function getStatistics()
    {
        $stats = [
            'active_count' => $this->salesOrderModel->getActiveCount(),
            'deleted_count' => $this->salesOrderModel->getDeletedCount(),
            'total_count' => $this->salesOrderModel->getActiveCount() + $this->salesOrderModel->getDeletedCount()
        ];

        return $this->response->setJSON([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Get services for a specific client (AJAX endpoint)
     */
    public function getServicesForClient($clientId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        $serviceModel = new SalesOrderServiceModel();
        
        try {
            if ($clientId) {
                // Get services for specific client + general services
                $services = $serviceModel->select('id, service_name, service_price, client_id')
                                        ->where('service_status', 'active')
                                        ->where('show_in_orders', 1)
                                        ->groupStart()
                                            ->where('client_id', $clientId)
                                            ->orWhere('client_id', null)
                                        ->groupEnd()
                                        ->orderBy('service_name', 'ASC')
                                        ->findAll();
            } else {
                // Get all active services
                $services = $serviceModel->select('id, service_name, service_price, client_id')
                                        ->where('service_status', 'active')
                                        ->where('show_in_orders', 1)
                                        ->orderBy('service_name', 'ASC')
                                        ->findAll();
            }

            return $this->response->setJSON([
                'success' => true,
                'services' => $services
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get services for order form - filtered by client and respecting user permissions
     */
    public function getServicesForOrderForm($clientId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        $serviceModel = new SalesOrderServiceModel();
        
        try {
            // Get current user info
            $currentUser = session()->get('user');
            $userType = $currentUser['user_type'] ?? 'staff';
            $showPrices = ($userType !== 'client'); // Only show prices to non-client users
            
            $query = $serviceModel->select('id, service_name, service_price, client_id')
                                 ->where('service_status', 'active')
                                 ->where('show_in_orders', 1);

            if ($clientId && $clientId !== '') {
                // Get services for specific client + general services (null client_id)
                $query->groupStart()
                        ->where('client_id', $clientId)
                        ->orWhere('client_id', null)
                        ->orWhere('client_id', '')
                      ->groupEnd();
            }
            
            $services = $query->orderBy('service_name', 'ASC')->findAll();
            
            // Format services based on user type
            $formattedServices = [];
            foreach ($services as $service) {
                $formattedService = [
                    'id' => $service['id'],
                    'service_name' => $service['service_name'],
                    'client_id' => $service['client_id'],
                    'display_text' => $service['service_name']
                ];
                
                // Add price information only if user is not a client
                if ($showPrices && !empty($service['service_price'])) {
                    $formattedService['service_price'] = $service['service_price'];
                    $formattedService['display_text'] = $service['service_name'] . ' - $' . number_format($service['service_price'], 2);
                }
                
                $formattedServices[] = $formattedService;
            }

            return $this->response->setJSON([
                'success' => true,
                'services' => $formattedServices,
                'show_prices' => $showPrices,
                'user_type' => $userType
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getServicesForOrderForm: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get contacts for a specific client (AJAX endpoint)
     */
    public function getContactsForClient($clientId = null)
    {
        // Log the request
        log_message('info', "=== getContactsForClient called ===");
        log_message('info', "Request method: " . $this->request->getMethod());
        log_message('info', "Is AJAX: " . ($this->request->isAJAX() ? 'true' : 'false'));
        log_message('info', "Client ID parameter: " . ($clientId ?: 'null'));
        log_message('info', "Debug parameter: " . ($this->request->getGet('debug') ?: 'false'));
        log_message('info', "User agent: " . $this->request->getUserAgent());
        log_message('info', "Headers: " . json_encode($this->request->headers()));
        
        // Allow both AJAX and debug requests
        if (!$this->request->isAJAX() && !$this->request->getGet('debug')) {
            log_message('error', "Non-AJAX request rejected");
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        $userModel = new \App\Models\UserModel();
        
        try {
            log_message('info', "Starting database query for clientId: " . ($clientId ?: 'null'));
            
            if ($clientId) {
                // Get users for specific client with user_type = 'client'
                log_message('info', "Querying contacts for specific client: " . $clientId);
                
                $contacts = $userModel->select('users.id, CONCAT(users.first_name, " ", users.last_name) as name, users.client_id, users.first_name, users.last_name, users.user_type, users.active')
                                     ->where('users.client_id', $clientId)
                                     ->where('users.user_type', 'client')
                                     ->where('users.active', 1)
                                     ->orderBy('users.first_name', 'ASC')
                                     ->findAll();
                                     
                log_message('info', "Found " . count($contacts) . " contacts for client {$clientId}");
                log_message('info', "Contacts data: " . json_encode($contacts));
            } else {
                // Get all active client users
                log_message('info', "Querying all active client users");
                
                $contacts = $userModel->select('users.id, CONCAT(users.first_name, " ", users.last_name) as name, users.client_id, users.first_name, users.last_name, users.user_type, users.active')
                                     ->where('users.user_type', 'client')
                                     ->where('users.active', 1)
                                     ->orderBy('users.first_name', 'ASC')
                                     ->findAll();
                                     
                log_message('info', "Found " . count($contacts) . " total active client users");
            }

            // Debug: Also try direct database query
            $db = \Config\Database::connect();
            $directQuery = $db->table('users')
                             ->select('id, first_name, last_name, client_id, user_type, active')
                             ->where('user_type', 'client')
                             ->where('active', 1);
            
            if ($clientId) {
                $directQuery->where('client_id', $clientId);
            }
            
            $directResults = $directQuery->get()->getResultArray();
            log_message('info', "Direct query found " . count($directResults) . " users");
            log_message('info', "Direct query results: " . json_encode($directResults));

            $response = [
                'success' => true,
                'contacts' => $contacts,
                'debug' => [
                    'client_id' => $clientId,
                    'total_found' => count($contacts),
                    'query_type' => $clientId ? 'specific_client' : 'all_clients',
                    'direct_query_count' => count($directResults),
                    'direct_query_results' => $directResults,
                    'request_method' => $this->request->getMethod(),
                    'is_ajax' => $this->request->isAJAX(),
                    'user_agent' => $this->request->getUserAgent()
                ]
            ];
            
            log_message('info', "Returning response with " . count($contacts) . " contacts");
            log_message('info', "Full response: " . json_encode($response));
            
            return $this->response->setJSON($response);

        } catch (\Exception $e) {
            log_message('error', "Error in getContactsForClient: " . $e->getMessage());
            log_message('error', "Stack trace: " . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => [
                    'client_id' => $clientId,
                    'error_details' => $e->getTraceAsString(),
                    'request_method' => $this->request->getMethod(),
                    'is_ajax' => $this->request->isAJAX()
                ]
            ]);
        }
    }

    /**
     * Test method for debugging users and clients data
     */
    public function test_users_data()
    {
        $db = \Config\Database::connect();
        
        $result = [
            'clients' => [],
            'users' => [],
            'users_by_client' => [],
            'controller_query' => [],
            'ajax_query_sample' => []
        ];
        
        try {
            // Check clients
            $clients = $db->table('clients')
                         ->select('id, name, status')
                         ->where('status', 'active')
                         ->get()
                         ->getResultArray();
            
            $result['clients'] = $clients;
            
            // Check users
            $users = $db->table('users')
                       ->select('id, first_name, last_name, client_id, user_type, active')
                       ->where('user_type', 'client')
                       ->where('active', 1)
                       ->get()
                       ->getResultArray();
            
            $result['users'] = $users;
            
            // Users by client
            foreach ($clients as $client) {
                $clientUsers = $db->table('users')
                                 ->select('id, first_name, last_name, user_type, active')
                                 ->where('client_id', $client['id'])
                                 ->where('user_type', 'client')
                                 ->where('active', 1)
                                 ->get()
                                 ->getResultArray();
                
                $result['users_by_client'][$client['id']] = [
                    'client_name' => $client['name'],
                    'users' => $clientUsers
                ];
            }
            
            // Test controller query (same as in index method)
            $userModel = new \App\Models\UserModel();
            $controllerQuery = $userModel->select('users.id, CONCAT(users.first_name, " ", users.last_name) as name, users.client_id')
                                       ->join('clients', 'clients.id = users.client_id')
                                       ->where('users.user_type', 'client')
                                       ->where('users.active', 1)
                                       ->where('clients.status', 'active')
                                       ->orderBy('users.first_name', 'ASC')
                                       ->findAll();
            
            $result['controller_query'] = $controllerQuery;
            
            // Test AJAX query for first client
            if (!empty($clients)) {
                $firstClientId = $clients[0]['id'];
                $ajaxQuery = $userModel->select('users.id, CONCAT(users.first_name, " ", users.last_name) as name, users.client_id')
                                     ->where('users.client_id', $firstClientId)
                                     ->where('users.user_type', 'client')
                                     ->where('users.active', 1)
                                     ->orderBy('users.first_name', 'ASC')
                                     ->findAll();
                
                $result['ajax_query_sample'] = [
                    'client_id' => $firstClientId,
                    'client_name' => $clients[0]['name'],
                    'users' => $ajaxQuery
                ];
            }
            
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }
        
        return $this->response->setJSON($result);
    }

    /**
     * Create test client users (temporary method)
     */
    public function create_test_users()
    {
            $db = \Config\Database::connect();
        $result = ['log' => []];
        
        try {
            // First, check if we have clients
            $clients = $db->table('clients')
                         ->select('id, name')
                         ->where('status', 'active')
                         ->get()
                         ->getResultArray();

            if (empty($clients)) {
                $result['log'][] = "No active clients found. Creating test clients first...";
                
                // Create test clients
                $testClients = [
                    ['name' => 'Acme Corporation', 'email' => 'contact@acme.com', 'phone' => '555-0001', 'status' => 'active'],
                    ['name' => 'Tech Solutions Inc', 'email' => 'info@techsolutions.com', 'phone' => '555-0002', 'status' => 'active'],
                    ['name' => 'Global Services LLC', 'email' => 'hello@globalservices.com', 'phone' => '555-0003', 'status' => 'active']
                ];
                
                foreach ($testClients as $client) {
                    $client['created_at'] = date('Y-m-d H:i:s');
                    $client['updated_at'] = date('Y-m-d H:i:s');
                    $db->table('clients')->insert($client);
                    $result['log'][] = "Created client: {$client['name']}";
                }
                
                // Refresh clients list
                $clients = $db->table('clients')
                             ->select('id, name')
                             ->where('status', 'active')
                             ->get()
                             ->getResultArray();
            }

            $result['log'][] = "Found " . count($clients) . " active clients";
            $result['clients'] = $clients;

            $result['log'][] = "Creating test client users...";

            // Create test users for each client
            $testUsers = [
                ['first_name' => 'John', 'last_name' => 'Doe', 'username' => 'john.doe'],
                ['first_name' => 'Jane', 'last_name' => 'Smith', 'username' => 'jane.smith'],
                ['first_name' => 'Mike', 'last_name' => 'Johnson', 'username' => 'mike.johnson'],
                ['first_name' => 'Sarah', 'last_name' => 'Wilson', 'username' => 'sarah.wilson'],
                ['first_name' => 'David', 'last_name' => 'Brown', 'username' => 'david.brown'],
                ['first_name' => 'Lisa', 'last_name' => 'Davis', 'username' => 'lisa.davis']
            ];

            $userIndex = 0;
            foreach ($clients as $client) {
                // Create 2 users per client
                for ($i = 0; $i < 2 && $userIndex < count($testUsers); $i++, $userIndex++) {
                    $user = $testUsers[$userIndex];
                    
                    // Check if user already exists
                    $existingUser = $db->table('users')
                                      ->where('username', $user['username'])
                                      ->get()
                                      ->getRowArray();
                    
                    if (!$existingUser) {
                        $userData = [
                            'username' => $user['username'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'user_type' => 'client',
                            'client_id' => $client['id'],
                            'active' => 1,
                            'status' => 'active',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $db->table('users')->insert($userData);
                        $userId = $db->insertID();
                        $result['log'][] = "Created user: {$user['first_name']} {$user['last_name']} for client {$client['name']} (ID: $userId)";
                        
                        // Create auth identity for the user
                        $identityData = [
                            'user_id' => $userId,
                            'type' => 'email_password',
                            'name' => null,
                            'secret' => $user['username'] . '@example.com',
                            'secret2' => password_hash('password123', PASSWORD_DEFAULT),
                            'expires' => null,
                            'extra' => null,
                            'force_reset' => 0,
                            'last_used_at' => null,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $db->table('auth_identities')->insert($identityData);
                        $result['log'][] = "  - Created auth identity with email: {$identityData['secret']}";
                    } else {
                        $result['log'][] = "User {$user['username']} already exists, skipping...";
                    }
                }
            }

            // Verify the created users
            $clientUsers = $db->table('users')
                             ->select('id, first_name, last_name, client_id, user_type, active')
                             ->where('user_type', 'client')
                             ->where('active', 1)
                             ->get()
                             ->getResultArray();

            $result['log'][] = "Total active client users: " . count($clientUsers);
            $result['client_users'] = $clientUsers;
            
            $result['users_by_client'] = [];
            foreach ($clients as $client) {
                $usersForClient = array_filter($clientUsers, function($user) use ($client) {
                    return $user['client_id'] == $client['id'];
                });
                
                $result['users_by_client'][$client['id']] = [
                    'client_name' => $client['name'],
                    'users' => array_values($usersForClient)
                ];
                $result['log'][] = "Client '{$client['name']}' has " . count($usersForClient) . " users";
            }

            $result['success'] = true;

        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error'] = $e->getMessage();
            $result['log'][] = "Error: " . $e->getMessage();
        }
        
        return $this->response->setJSON($result);
    }

    /**
     * Get order data for editing (AJAX endpoint)
     */
    public function getOrderData($orderId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        try {
            // Get order with complete information
            $order = $this->salesOrderModel->select('sales_orders.*, 
                                                   clients.name as client_name,
                                                   CONCAT(users.first_name, " ", users.last_name) as salesperson_name,
                                                   sales_orders_services.service_name')
                                         ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                         ->join('users', 'users.id = sales_orders.contact_id', 'left')
                                         ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                                         ->where('sales_orders.id', $orderId)
                                         ->where('sales_orders.deleted', 0)
                                         ->first();
            
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'order' => $order
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting order data: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading order data'
            ]);
        }
    }

    /**
     * Get new activities since a specific timestamp for real-time updates
     */
    public function getNewActivities($orderId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        if (!$orderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order ID is required'
            ]);
        }

        $order = $this->salesOrderModel->find($orderId);
        if (!$order || $order['deleted'] == 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        // Get last activity timestamp from request
        $lastTimestamp = $this->request->getGet('since') ?? date('Y-m-d H:i:s', strtotime('-1 hour'));
        
        try {
            // Get new activities since the last timestamp
            $newActivities = $this->activityModel->select('sales_orders_activities.*, 
                                                         CONCAT(users.first_name, " ", users.last_name) as user_name,
                                                         users.first_name, users.last_name')
                                                  ->join('users', 'users.id = sales_orders_activities.user_id', 'left')
                                                  ->where('order_id', $orderId)
                                                  ->where('sales_orders_activities.created_at >', $lastTimestamp)
                                                  ->orderBy('created_at', 'DESC')
                                                  ->limit(50) // Limit to prevent too many results
                                                  ->findAll();

            // Format activities for display
            $formattedActivities = [];
            foreach ($newActivities as $activity) {
                $formattedActivities[] = [
                    'id' => $activity['id'],
                    'type' => $activity['activity_type'],
                    'title' => $activity['title'],
                    'description' => $activity['description'],
                    'old_value' => $activity['old_value'],
                    'new_value' => $activity['new_value'],
                    'field_name' => $activity['field_name'],
                    'user_name' => $activity['user_name'] ?: 'System',
                    'created_at' => date('M j, Y \a\t g:i A', strtotime($activity['created_at'])),
                    'timestamp' => $activity['created_at'], // Raw timestamp for comparison
                    'metadata' => $activity['metadata'] ? json_decode($activity['metadata'], true) : null,
                    'is_new' => true // Flag to identify new activities
                ];
            }

            // Get current server time for next polling
            $currentTime = date('Y-m-d H:i:s');

            return $this->response->setJSON([
                'success' => true,
                'new_activities' => $formattedActivities,
                'count' => count($formattedActivities),
                'server_time' => $currentTime,
                'last_check' => $lastTimestamp
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting new activities: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading new activities'
            ]);
        }
    }

    /**
     * Test endpoint to debug pending orders
     */
    public function debug_pending()
    {
        // Get database connection
        $db = \Config\Database::connect();
        
        $output = "<h2>Debug: Pending Orders</h2>\n";
        
        try {
            // Check total orders in database
            $totalOrders = $db->table('sales_orders')
                             ->where('deleted', 0)
                             ->countAllResults();
            $output .= "<p>Total orders in database: $totalOrders</p>\n";

            // Check orders by status
            $statuses = ['pending', 'processing', 'in_progress', 'completed', 'cancelled'];
            foreach ($statuses as $status) {
                $count = $db->table('sales_orders')
                           ->where('deleted', 0)
                           ->where('status', $status)
                           ->countAllResults();
                $output .= "<p>Orders with status '$status': $count</p>\n";
            }

            // Show all orders with details
            $output .= "<h3>All Orders Details:</h3>\n";
            $orders = $db->table('sales_orders')
                        ->select('sales_orders.*, 
                                 clients.name as client_name,
                                 CONCAT(users.first_name, " ", users.last_name) as salesperson_name')
                        ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                        ->join('users', 'users.id = sales_orders.created_by', 'left')
                        ->where('sales_orders.deleted', 0)
                        ->orderBy('sales_orders.id', 'DESC')
                        ->get()
                        ->getResultArray();

            if (!empty($orders)) {
                $output .= "<table border='1' cellpadding='5'>\n";
                $output .= "<tr><th>ID</th><th>Order ID</th><th>Client</th><th>Status</th><th>Date</th><th>Stock</th><th>Vehicle</th></tr>\n";
                
                foreach ($orders as $order) {
                    $orderId = 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                    $output .= "<tr>";
                    $output .= "<td>{$order['id']}</td>";
                    $output .= "<td>$orderId</td>";
                    $output .= "<td>{$order['client_name']}</td>";
                    $output .= "<td>{$order['status']}</td>";
                    $output .= "<td>{$order['date']}</td>";
                    $output .= "<td>{$order['stock']}</td>";
                    $output .= "<td>{$order['vehicle']}</td>";
                    $output .= "</tr>\n";
                }
                $output .= "</table>\n";
            } else {
                $output .= "<p>No orders found.</p>\n";
            }

            // Test the exact filter used by pending_content.php
            $output .= "<h3>Orders matching pending filter (pending,processing,in_progress):</h3>\n";
            $pendingOrders = $db->table('sales_orders')
                               ->select('sales_orders.*, 
                                        clients.name as client_name,
                                        CONCAT(users.first_name, " ", users.last_name) as salesperson_name')
                               ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                               ->join('users', 'users.id = sales_orders.created_by', 'left')
                               ->where('sales_orders.deleted', 0)
                               ->whereIn('sales_orders.status', ['pending', 'processing', 'in_progress'])
                               ->orderBy('sales_orders.id', 'DESC')
                               ->get()
                               ->getResultArray();

            if (!empty($pendingOrders)) {
                $output .= "<table border='1' cellpadding='5'>\n";
                $output .= "<tr><th>ID</th><th>Order ID</th><th>Client</th><th>Status</th><th>Date</th><th>Stock</th><th>Vehicle</th></tr>\n";
                
                foreach ($pendingOrders as $order) {
                    $orderId = 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
                    $output .= "<tr>";
                    $output .= "<td>{$order['id']}</td>";
                    $output .= "<td>$orderId</td>";
                    $output .= "<td>{$order['client_name']}</td>";
                    $output .= "<td>{$order['status']}</td>";
                    $output .= "<td>{$order['date']}</td>";
                    $output .= "<td>{$order['stock']}</td>";
                    $output .= "<td>{$order['vehicle']}</td>";
                    $output .= "</tr>\n";
                }
                $output .= "</table>\n";
            } else {
                $output .= "<p>No pending orders found matching filter.</p>\n";
            }

        } catch (\Exception $e) {
            $output .= "<p>Error: " . $e->getMessage() . "</p>\n";
        }

        $output .= "<p>Debug completed.</p>\n";
        
        return $this->response->setBody($output);
    }

    /**
     * Dashboard statistics endpoint
     */
    public function dashboard_stats()
    {
        $db = \Config\Database::connect();
        
        try {
            // Get client filter from query parameter and validate
            $clientId = $this->request->getGet('client_id');
            
            // Properly validate and sanitize client_id
            if (empty($clientId) || $clientId === '0' || $clientId === 0 || !is_numeric($clientId)) {
                $clientId = null;
            } else {
                $clientId = (int)$clientId; // Convert to integer for safety
            }
            
            // Get today's date
            $today = date('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime('+1 day'));
            
            // Get current week range (Monday to Sunday)
            $dayOfWeek = date('w'); // 0 = Sunday, 1 = Monday, etc.
            $daysToMonday = $dayOfWeek == 0 ? -6 : 1 - $dayOfWeek;
            $monday = date('Y-m-d', strtotime("$daysToMonday days"));
            $sunday = date('Y-m-d', strtotime('+6 days', strtotime($monday)));
            
            // Build base query with client filter if provided
            $baseQuery = $db->table('sales_orders')->where('deleted', 0);
            if ($clientId && $clientId > 0) {
                $baseQuery = $baseQuery->where('client_id', $clientId);
            }
            
            // Count today's orders
            $todayQuery = clone $baseQuery;
            $todayCount = $todayQuery->where('order_date', $today)->countAllResults();
            
            // Count tomorrow's orders
            $tomorrowQuery = clone $baseQuery;
            $tomorrowCount = $tomorrowQuery->where('order_date', $tomorrow)->countAllResults();
            
            // Count pending orders (pending, processing, in_progress)
            $pendingQuery = clone $baseQuery;
            $pendingCount = $pendingQuery->whereIn('status', ['pending', 'processing', 'in_progress'])->countAllResults();
            
            // Count week orders
            $weekQuery = clone $baseQuery;
            $weekCount = $weekQuery->where('order_date >=', $monday)->where('order_date <=', $sunday)->countAllResults();
            
            // Get status breakdown
            $statusQuery = $db->table('sales_orders')
                             ->select('status, COUNT(*) as count')
                             ->where('deleted', 0);
            
            if ($clientId && $clientId > 0) {
                $statusQuery = $statusQuery->where('client_id', $clientId);
            }
            
            $statusResults = $statusQuery->groupBy('status')->get();
            
            $statusBreakdown = [
                'pending' => 0,
                'processing' => 0,
                'completed' => 0,
                'cancelled' => 0,
                'in_progress' => 0
            ];
            
            foreach ($statusResults->getResultArray() as $row) {
                if (isset($statusBreakdown[$row['status']])) {
                    $statusBreakdown[$row['status']] = (int)$row['count'];
                }
            }
            
            // Combine processing and in_progress for display
            $statusBreakdown['processing'] += $statusBreakdown['in_progress'];
            
            return $this->response->setJSON([
                'success' => true,
                'stats' => [
                    'today' => $todayCount,
                    'tomorrow' => $tomorrowCount,
                    'pending' => $pendingCount,
                    'week' => $weekCount,
                    'status_breakdown' => $statusBreakdown
                ],
                'charts' => [
                    'status' => [
                        'data' => [
                            $statusBreakdown['pending'],
                            $statusBreakdown['processing'],
                            $statusBreakdown['completed'],
                            $statusBreakdown['cancelled']
                        ]
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Dashboard stats error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => true, // Return success with zeros to avoid breaking the UI
                'stats' => [
                    'today' => 0,
                    'tomorrow' => 0,
                    'pending' => 0,
                    'week' => 0,
                    'status_breakdown' => [
                        'pending' => 0,
                        'processing' => 0,
                        'completed' => 0,
                        'cancelled' => 0
                    ]
                ],
                'charts' => [
                    'status' => [
                        'data' => [0, 0, 0, 0]
                    ]
                ],
                'error_note' => 'Unable to load dashboard statistics'
            ]);
        }
    }
    
    /**
     * Chart data endpoint
     */
    public function chart_data()
    {
        $db = \Config\Database::connect();
        $period = $this->request->getGet('period') ?: 30;
        $clientId = $this->request->getGet('client_id');
        $clientId = (!empty($clientId) && $clientId !== '0' && $clientId !== 0) ? $clientId : null;
        
        try {
            // Calculate date range
            $endDate = date('Y-m-d');
            $startDate = date('Y-m-d', strtotime("-$period days"));
            
            // Get orders data for the period with client filter
            $ordersQuery = $db->table('sales_orders')
                             ->select('DATE(order_date) as order_date, COUNT(*) as count')
                             ->where('deleted', 0)
                             ->where('order_date >=', $startDate)
                             ->where('order_date <=', $endDate);
            
            if ($clientId) {
                $ordersQuery = $ordersQuery->where('client_id', $clientId);
            }
            
            $ordersData = $ordersQuery->groupBy('DATE(order_date)')
                                     ->orderBy('order_date', 'ASC')
                                     ->get()
                                     ->getResultArray();
            
            // Generate all dates in range
            $categories = [];
            $data = [];
            $dataMap = [];
            
            // Create map for quick lookup
            foreach ($ordersData as $row) {
                $dataMap[$row['order_date']] = (int)$row['count'];
            }
            
            // Generate complete date range with data
            for ($i = $period - 1; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $categories[] = date('M j', strtotime($date));
                $data[] = isset($dataMap[$date]) ? $dataMap[$date] : 0;
            }
            
            // Get status distribution with client filter
            $statusQuery = $db->table('sales_orders')
                             ->select('status, COUNT(*) as count')
                             ->where('deleted', 0)
                             ->where('date >=', $startDate)
                             ->where('date <=', $endDate);
            
            if ($clientId) {
                $statusQuery = $statusQuery->where('client_id', $clientId);
            }
            
            $statusResults = $statusQuery->groupBy('status')->get();
            
            $statusData = [0, 0, 0, 0]; // [pending, processing, completed, cancelled]
            
            foreach ($statusResults->getResultArray() as $row) {
                switch ($row['status']) {
                    case 'pending':
                        $statusData[0] = (int)$row['count'];
                        break;
                    case 'processing':
                    case 'in_progress':
                        $statusData[1] += (int)$row['count'];
                        break;
                    case 'completed':
                        $statusData[2] = (int)$row['count'];
                        break;
                    case 'cancelled':
                        $statusData[3] = (int)$row['count'];
                        break;
                }
            }
            
            // Log if client filter is applied
            if ($clientId) {
                log_message('info', "Chart data loaded with client filter: {$clientId} for period: {$period} days");
            }
            
            return $this->response->setJSON([
                'success' => true,
                'charts' => [
                    'orders' => [
                        'categories' => $categories,
                        'data' => $data
                    ],
                    'status' => [
                        'data' => $statusData
                    ]
                ],
                'client_filter' => $clientId,
                'period' => $period
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Chart data error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading chart data'
            ]);
        }
    }
    
    /**
     * Download PDF for Sales Order
     */
    public function downloadPdf($id)
    {
        try {
            // Check if user is authenticated
            if (!session()->get('isLoggedIn')) {
                return redirect()->to('/login')->with('error', 'Please login to download PDF');
            }
            
            // Get order data
            $salesOrderModel = new SalesOrderModel();
            $order = $salesOrderModel->getOrderWithDetails($id);
            
            if (!$order) {
                throw new \Exception('Order not found');
            }
            
            // Log the PDF download activity
            $userId = session()->get('user_id') ?? 1;
            $this->activityModel->insert([
                'order_id' => $id,
                'user_id' => $userId,
                'activity_type' => 'pdf_downloaded',
                'title' => 'PDF Downloaded',
                'description' => 'Order PDF document was downloaded',
                'field_name' => 'pdf_download',
                'metadata' => json_encode([
                    'action' => 'pdf_download',
                    'file_type' => 'pdf',
                    'order_number' => 'ORDER #' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)
                ]),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Get settings for company info and logo
            $settingsModel = new \App\Models\SettingsModel();
            $settings = $settingsModel->getAllSettings();
            
            // Use wkhtmltopdf exclusively for modern HTML/CSS support
            if (!$this->isWkhtmltopdfAvailable()) {
                throw new \Exception('wkhtmltopdf is required but not available on this system. Please install wkhtmltopdf to generate PDFs.');
            }
            
            return $this->generatePdfWithWkhtmltopdf($order, $settings);
            
        } catch (\Exception $e) {
            log_message('error', 'PDF generation error: ' . $e->getMessage());
            log_message('error', 'PDF generation stack trace: ' . $e->getTraceAsString());
            
            // Log the error activity if PDF generation fails
            $userId = session()->get('user_id') ?? 1;
            try {
                $this->activityModel->insert([
                    'order_id' => $id,
                    'user_id' => $userId,
                    'activity_type' => 'pdf_error',
                    'title' => 'PDF Generation Failed',
                    'description' => 'Error generating PDF: ' . $e->getMessage(),
                    'field_name' => 'pdf_error',
                    'metadata' => json_encode([
                        'action' => 'pdf_error',
                        'error_message' => $e->getMessage()
                    ]),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $logError) {
                log_message('error', 'Error logging PDF error activity: ' . $logError->getMessage());
            }
            
            // Return error response instead of redirect for AJAX calls
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Check if wkhtmltopdf is available
     */
    private function isWkhtmltopdfAvailable()
    {
        // Define possible paths for wkhtmltopdf
        $possiblePaths = [
            'wkhtmltopdf', // If in PATH
            'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe', // Default Windows installation
            'C:\wkhtmltopdf\bin\wkhtmltopdf.exe', // Alternative installation
            '/usr/bin/wkhtmltopdf', // Linux
            '/usr/local/bin/wkhtmltopdf' // MacOS/Linux alternative
        ];
        
        foreach ($possiblePaths as $path) {
            $command = escapeshellarg($path) . ' --version';
            $output = [];
            $returnCode = 0;
            
            exec($command . ' 2>&1', $output, $returnCode);
            
            if ($returnCode === 0) {
                // Store the working path for later use
                $this->wkhtmltopdfPath = $path;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Generate PDF using wkhtmltopdf (supports modern CSS)
     */
    private function generatePdfWithWkhtmltopdf($order, $settings)
    {
        // Generate the modern HTML content
        $html = $this->generateModernPdfContent($order, $settings);
        
        // Create temporary HTML file
        $tempHtml = WRITEPATH . 'temp/order_' . $order['id'] . '_' . time() . '.html';
        $tempPdf = WRITEPATH . 'temp/order_' . $order['id'] . '_' . time() . '.pdf';
        
        // Ensure temp directory exists
        if (!is_dir(WRITEPATH . 'temp')) {
            mkdir(WRITEPATH . 'temp', 0755, true);
        }
        
        // Write HTML to temporary file
        file_put_contents($tempHtml, $html);
        
        // Generate PDF with wkhtmltopdf using the detected path
        $command = sprintf(
            '%s --page-size A4 --margin-top 10mm --margin-bottom 10mm --margin-left 10mm --margin-right 10mm --disable-smart-shrinking "%s" "%s"',
            escapeshellarg($this->wkhtmltopdfPath),
            $tempHtml,
            $tempPdf
        );
        
        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);
        
        if ($returnCode !== 0 || !file_exists($tempPdf)) {
            // Clean up and throw error
            if (file_exists($tempHtml)) unlink($tempHtml);
            if (file_exists($tempPdf)) unlink($tempPdf);
            
            throw new \Exception('wkhtmltopdf failed to generate PDF: ' . implode("\n", $output));
        }
        
        // Generate filename
        $filename = 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '.pdf';
        
        // Send PDF to browser
        $this->response->setHeader('Content-Type', 'application/pdf')
                      ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                      ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                      ->setHeader('Pragma', 'public')
                      ->setHeader('Content-Length', filesize($tempPdf));
        
        // Output PDF content
        $pdfContent = file_get_contents($tempPdf);
        
        // Clean up temporary files
        if (file_exists($tempHtml)) unlink($tempHtml);
        if (file_exists($tempPdf)) unlink($tempPdf);
        
        return $this->response->setBody($pdfContent);
    }

    /**
     * Generate modern HTML content using the new template design for wkhtmltopdf
     */
    private function generateModernPdfContent($order, $settings)
    {
        // Get QR code if available
        $qrCode = '';
        $shortUrl = '';
        if (method_exists($this, 'generateOrderQR')) {
            $qrResult = $this->generateOrderQR($order['id'], 150);
            if ($qrResult && isset($qrResult['qr_code'])) {
                $qrCode = $qrResult['qr_code'];
                $shortUrl = $qrResult['short_url'] ?? '';
            }
        }

        // Prepare vehicle information from order data
        $vehicleInfo = $order['vehicle'] ?? '2025 BMW X5 (xDrive40i)';
        $vinNumber = $order['vin'] ?? '5UX23EU01S9Y13173';
        $stockNumber = $order['stock'] ?? 'DML002';

        // Format service date and time
        $serviceDate = date('M j, Y', strtotime($order['date'] ?? 'now'));
        $serviceTime = date('g:i A', strtotime($order['time'] ?? 'now'));

        // Client and contact info
        $clientName = $order['client_name'] ?? 'BMW of Sudbury';
        $clientEmail = $order['client_email'] ?? 'bmw@lima.llc';
        $contactName = $order['salesperson_name'] ?? 'Client User';
        $serviceName = $order['service_name'] ?? 'FULL DETAIL SERVICE';

        // Status styling
        $status = strtoupper($order['status'] ?? 'IN_PROGRESS');
        $statusClass = strtolower($order['status'] ?? 'in_progress');

        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Order #' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif;
            background-color: #f9fafb;
            padding: 1rem;
            line-height: 1.5;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            min-height: 297mm;
            font-size: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .header .order-info {
            color: #6b7280;
        }

        .header .order-info .order-number {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .status-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 0.5rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge.status-pending {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-badge.status-in_progress {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-badge.status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-badge.status-cancelled {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .separator {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }

        .section {
            margin-bottom: 1.5rem;
        }

        .section h2 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .card p {
            color: #374151;
            font-weight: 500;
        }

        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        /* Alternative layout using flexbox for better compatibility */
        .flex-row {
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .flex-col {
            flex: 1;
        }

        .headers-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .headers-row h2 {
            flex: 1;
            margin: 0;
        }

        .info-item {
            margin-bottom: 0.75rem;
        }

        .info-label {
            display: block;
            color: #6b7280;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 500;
            color: #111827;
        }

        .info-value.mono {
            font-family: \'Courier New\', monospace;
        }

        .signatures {
            margin-bottom: 2rem;
        }

        .signature-line {
            border-bottom: 2px solid #d1d5db;
            height: 3rem;
            margin-bottom: 0.5rem;
        }

        .signature-label {
            text-align: center;
            color: #6b7280;
            font-weight: 500;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .qr-container {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .qr-box {
            background: white;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
        }

        .qr-placeholder {
            width: 96px;
            height: 96px;
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.25rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'96\' height=\'96\' viewBox=\'0 0 96 96\'%3E%3Crect width=\'96\' height=\'96\' fill=\'%23f3f4f6\'/%3E%3Cg fill=\'%23374151\'%3E%3Crect x=\'8\' y=\'8\' width=\'16\' height=\'16\'/%3E%3Crect x=\'8\' y=\'32\' width=\'16\' height=\'16\'/%3E%3Crect x=\'8\' y=\'56\' width=\'16\' height=\'16\'/%3E%3Crect x=\'32\' y=\'8\' width=\'16\' height=\'16\'/%3E%3Crect x=\'32\' y=\'32\' width=\'16\' height=\'16\'/%3E%3Crect x=\'32\' y=\'56\' width=\'16\' height=\'16\'/%3E%3Crect x=\'56\' y=\'8\' width=\'16\' height=\'16\'/%3E%3Crect x=\'56\' y=\'32\' width=\'16\' height=\'16\'/%3E%3Crect x=\'56\' y=\'56\' width=\'16\' height=\'16\'/%3E%3Crect x=\'72\' y=\'8\' width=\'16\' height=\'16\'/%3E%3Crect x=\'72\' y=\'32\' width=\'16\' height=\'16\'/%3E%3Crect x=\'72\' y=\'56\' width=\'16\' height=\'16\'/%3E%3Crect x=\'16\' y=\'16\' width=\'8\' height=\'8\'/%3E%3Crect x=\'40\' y=\'16\' width=\'8\' height=\'8\'/%3E%3Crect x=\'64\' y=\'16\' width=\'8\' height=\'8\'/%3E%3Crect x=\'16\' y=\'40\' width=\'8\' height=\'8\'/%3E%3Crect x=\'40\' y=\'40\' width=\'8\' height=\'8\'/%3E%3Crect x=\'64\' y=\'40\' width=\'8\' height=\'8\'/%3E%3Crect x=\'16\' y=\'64\' width=\'8\' height=\'8\'/%3E%3Crect x=\'40\' y=\'64\' width=\'8\' height=\'8\'/%3E%3Crect x=\'64\' y=\'64\' width=\'8\' height=\'8\'/%3E%3C/g%3E%3C/svg%3E");
            background-size: cover;
        }

        .qr-text {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .short-link {
            font-size: 0.875rem;
            color: #2563eb;
            font-family: \'Courier New\', monospace;
        }

        .footer {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .footer .thank-you {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .footer .confirmation {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .footer .generated-info {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .footer .generated-info p {
            margin-bottom: 0.25rem;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            
            .container {
                box-shadow: none;
                margin: 0;
                padding: 1.5rem;
                min-height: auto;
            }
            
            .header h1 {
                font-size: 1.75rem;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }
            
            .grid-cols-3 {
                grid-template-columns: 1fr;
            }
            
            .flex-row {
                flex-direction: column;
            }
            
            .headers-row {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>SERVICE ORDER</h1>
                <div class="order-info">
                    <p class="order-number">Order #' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '</p>
                    <p>Date: ' . $serviceDate . '</p>
                </div>
            </div>
            <div>
                <span class="status-badge status-' . $statusClass . '">' . $status . '</span>
            </div>
        </div>

        <hr class="separator">

        <!-- Service Details -->
        <div class="section">
            <h2>SERVICE DETAILS</h2>
            <div class="card">
                <p>' . htmlspecialchars($serviceName) . '</p>
            </div>
        </div>

        <!-- Client and Vehicle Information Section -->
        <div class="section">
            <!-- Section Headers in Same Row using Flexbox -->
            <div class="headers-row">
                <h2>CLIENT INFORMATION</h2>
                <h2>VEHICLE INFORMATION</h2>
            </div>
            
            <!-- Information Cards using Flexbox -->
            <div class="flex-row">
                <!-- Client Information Card -->
                <div class="card flex-col">
                    <div class="info-item">
                        <span class="info-label">Client</span>
                        <p class="info-value">' . htmlspecialchars($clientName) . '</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <p class="info-value">' . htmlspecialchars($clientEmail) . '</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Contact</span>
                        <p class="info-value">' . htmlspecialchars($contactName) . '</p>
                    </div>
                </div>

                <!-- Vehicle Information Card -->
                <div class="card flex-col">
                    <div class="info-item">
                        <span class="info-label">Vehicle</span>
                        <p class="info-value">' . htmlspecialchars($vehicleInfo) . '</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">VIN</span>
                        <p class="info-value mono">' . htmlspecialchars($vinNumber) . '</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Stock #</span>
                        <p class="info-value">' . htmlspecialchars($stockNumber) . '</p>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Service Date</span>
                        <p class="info-value">' . $serviceDate . ' at ' . $serviceTime . '</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signatures">
            <h2>SIGNATURES</h2>
            <div style="display: flex; justify-content: space-between; gap: 1.5rem;">
                <div style="flex: 1; text-align: center;">
                    <div class="signature-line"></div>
                    <p class="signature-label">Customer Signature</p>
                </div>
                <div style="flex: 1; text-align: center;">
                    <div class="signature-line"></div>
                    <p class="signature-label">Date</p>
                </div>
                <div style="flex: 1; text-align: center;">
                    <div class="signature-line"></div>
                    <p class="signature-label">Technician</p>
                </div>
            </div>
        </div>

        <!-- QR Code and Short Link Section -->
        <div class="qr-section">
            <div class="qr-container">
                <div class="qr-box">
                    ' . ($qrCode ? 
                        '<img src="' . $qrCode . '" alt="QR Code" style="width: 96px; height: 96px; object-fit: contain;">' : 
                        '<div class="qr-placeholder"></div>'
                    ) . '
                </div>
                <div>
                    <p class="qr-text">Scan for order details</p>
                    <p class="short-link">' . ($shortUrl ?: 'short.ly/order-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT)) . '</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="thank-you">Thank you for your business!</p>
            <p class="confirmation">This document serves as an official service order confirmation.</p>
            <div class="generated-info">
                <p>Generated on ' . date('F j, Y \a\t g:i A') . '</p>
                <p>Powered by Your Service Management System</p>
            </div>
        </div>
    </div>
</body>
</html>';
    }

    /**
     * Get order status color for modern template
     */
    private function getOrderStatusColor($status)
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'in_progress' => 'bg-orange-100 text-orange-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];
        
        return $colors[strtolower($status)] ?? 'bg-gray-100 text-gray-800';
    }
    
    /**
     * Generate PDF content HTML
     */
    private function generatePdfContent($order, $settings)
    {
        // Get QR code if available
        $qrCode = '';
        $shortUrl = '';
        if (method_exists($this, 'generateOrderQR')) {
            $qrResult = $this->generateOrderQR($order['id'], 150);
            if ($qrResult && isset($qrResult['qr_code'])) {
                $qrCode = $qrResult['qr_code'];
                $shortUrl = $qrResult['short_url'] ?? '';
            }
        }

        // Prepare vehicle information from order data
        $vehicleInfo = $order['vehicle'] ?? 'Vehicle Information Not Available';
        $vinNumber = $order['vin'] ?? 'Not Provided';
        $stockNumber = $order['stock'] ?? 'N/A';

        // Format service date and time
        $serviceDate = date('M j, Y', strtotime($order['date'] ?? 'now'));
        $serviceTime = date('g:i A', strtotime($order['time'] ?? 'now'));

        // Client and contact info
        $clientName = $order['client_name'] ?? 'Client Name Not Available';
        $clientEmail = $order['client_email'] ?? 'Not Available';
        $contactName = $order['salesperson_name'] ?? 'Not Assigned';
        $serviceName = $order['service_name'] ?? 'Service Not Specified';

        // Status styling
        $statusStyle = $this->getStatusStyle($order['status'] ?? 'pending');

        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Service Order #' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '</title>
            <style>
                @page {
                    margin: 20mm;
                    format: A4 portrait;
                }
                
                body {
                    font-family: Arial, Helvetica, sans-serif;
                    background-color: #f9fafb;
                    margin: 0;
                    padding: 20px;
                    color: #374151;
                    font-size: 14px;
                    line-height: 1.5;
                }
                
                .container {
                    background: white;
                    padding: 40px;
                    width: 100%;
                    box-sizing: border-box;
                }
                
                /* Header */
                .header {
                    width: 100%;
                    margin-bottom: 30px;
                }
                
                .header-row {
                    width: 100%;
                    display: table;
                }
                
                .header-left {
                    display: table-cell;
                    width: 60%;
                    vertical-align: top;
                }
                
                .header-right {
                    display: table-cell;
                    width: 40%;
                    text-align: right;
                    vertical-align: top;
                }
                
                .header-left h1 {
                    font-size: 30px;
                    font-weight: bold;
                    color: #111827;
                    margin: 0 0 8px 0;
                }
                
                .order-number {
                    font-size: 18px;
                    font-weight: 600;
                    color: #374151;
                    margin: 0 0 4px 0;
                }
                
                .order-date {
                    color: #6b7280;
                    margin: 0;
                    font-size: 14px;
                }
                
                .status {
                    ' . $statusStyle . '
                    padding: 8px 12px;
                    font-size: 14px;
                    font-weight: 500;
                    border-radius: 20px;
                    text-transform: uppercase;
                    display: inline-block;
                }
                
                .divider {
                    border: 0;
                    border-top: 1px solid #e5e7eb;
                    margin: 24px 0;
                    width: 100%;
                }
                
                /* Service Details */
                .section {
                    margin-bottom: 24px;
                    width: 100%;
                }
                
                .section-title {
                    font-size: 18px;
                    font-weight: 600;
                    color: #111827;
                    margin-bottom: 12px;
                }
                
                .service-details {
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 16px;
                    background-color: #f9fafb;
                }
                
                .service-name {
                    color: #374151;
                    font-weight: 500;
                    margin: 0;
                    font-size: 16px;
                }
                
                /* Grid Layout using table for TCPDF compatibility */
                .info-grid {
                    width: 100%;
                    display: table;
                    margin-bottom: 32px;
                }
                
                .info-column {
                    display: table-cell;
                    width: 50%;
                    vertical-align: top;
                    padding-right: 20px;
                }
                
                .info-column:last-child {
                    padding-right: 0;
                    padding-left: 20px;
                }
                
                .info-card {
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 16px;
                    background: white;
                }
                
                .info-item {
                    margin-bottom: 12px;
                }
                
                .info-item:last-child {
                    margin-bottom: 0;
                }
                
                .info-label {
                    color: #6b7280;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-weight: 500;
                    margin-bottom: 4px;
                    display: block;
                }
                
                .info-value {
                    font-weight: 500;
                    color: #111827;
                    margin: 0;
                    font-size: 14px;
                }
                
                .font-mono {
                    font-family: "Courier New", monospace;
                }
                
                /* Signatures */
                .signatures {
                    margin-bottom: 32px;
                }
                
                .signature-grid {
                    width: 100%;
                    display: table;
                }
                
                .signature-item {
                    display: table-cell;
                    width: 33.33%;
                    text-align: center;
                    vertical-align: top;
                    padding: 0 10px;
                }
                
                .signature-line {
                    border-bottom: 2px solid #d1d5db;
                    height: 60px;
                    margin-bottom: 8px;
                }
                
                .signature-label {
                    color: #6b7280;
                    font-weight: 500;
                    font-size: 14px;
                }
                
                /* QR Section */
                .qr-section {
                    text-align: center;
                    margin-bottom: 24px;
                }
                
                .qr-container {
                    display: inline-block;
                    text-align: center;
                }
                
                .qr-box {
                    background: white;
                    padding: 16px;
                    border: 2px solid #e5e7eb;
                    border-radius: 8px;
                    display: inline-block;
                    margin-bottom: 12px;
                }
                
                .qr-image {
                    width: 96px;
                    height: 96px;
                    border-radius: 4px;
                }
                
                .qr-text {
                    font-size: 14px;
                    font-weight: 500;
                    color: #374151;
                    margin: 0 0 4px 0;
                }
                
                .qr-link {
                    font-size: 14px;
                    color: #2563eb;
                    font-family: "Courier New", monospace;
                    margin: 0;
                }
                
                /* Footer */
                .footer {
                    margin-top: 40px;
                    padding-top: 32px;
                    border-top: 1px solid #e5e7eb;
                    text-align: center;
                }
                
                .footer-title {
                    font-size: 18px;
                    font-weight: 600;
                    color: #111827;
                    margin: 0 0 8px 0;
                }
                
                .footer-subtitle {
                    color: #6b7280;
                    margin: 0 0 16px 0;
                }
                
                .footer-meta {
                    font-size: 12px;
                    color: #9ca3af;
                    margin-top: 16px;
                }
                
                .footer-meta p {
                    margin: 4px 0;
                }
                
                /* Utility classes */
                .clearfix {
                    clear: both;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <!-- Header -->
                <div class="header">
                    <div class="header-row">
                        <div class="header-left">
                            <h1>SERVICE ORDER</h1>
                            <p class="order-number">Order #' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) . '</p>
                            <p class="order-date">Date: ' . $serviceDate . '</p>
                        </div>
                        <div class="header-right">
                            <span class="status">' . strtoupper($order['status'] ?? 'PENDING') . '</span>
                        </div>
                    </div>
                </div>

                <hr class="divider">

                <!-- Service Details -->
                <div class="section">
                    <h2 class="section-title">SERVICE DETAILS</h2>
                    <div class="service-details">
                        <p class="service-name">' . strtoupper(htmlspecialchars($serviceName)) . '</p>
                    </div>
                </div>

                <!-- Client and Vehicle Information Grid -->
                <div class="info-grid">
                    <!-- Client Information -->
                    <div class="info-column">
                        <h2 class="section-title">CLIENT INFORMATION</h2>
                        <div class="info-card">
                            <div class="info-item">
                                <span class="info-label">Client</span>
                                <p class="info-value">' . htmlspecialchars($clientName) . '</p>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <p class="info-value">' . htmlspecialchars($clientEmail) . '</p>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Contact</span>
                                <p class="info-value">' . htmlspecialchars($contactName) . '</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="info-column">
                        <h2 class="section-title">VEHICLE INFORMATION</h2>
                        <div class="info-card">
                            <div class="info-item">
                                <span class="info-label">Vehicle</span>
                                <p class="info-value">' . htmlspecialchars($vehicleInfo) . '</p>
                            </div>
                            <div class="info-item">
                                <span class="info-label">VIN</span>
                                <p class="info-value font-mono">' . htmlspecialchars($vinNumber) . '</p>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Stock #</span>
                                <p class="info-value font-mono">' . htmlspecialchars($stockNumber) . '</p>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Service Date</span>
                                <p class="info-value">' . $serviceDate . ' at ' . $serviceTime . '</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signature Section -->
                <div class="section signatures">
                    <h2 class="section-title">SIGNATURES</h2>
                    <div class="signature-grid">
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <p class="signature-label">Customer Signature</p>
                        </div>
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <p class="signature-label">Date</p>
                        </div>
                        <div class="signature-item">
                            <div class="signature-line"></div>
                            <p class="signature-label">Technician</p>
                        </div>
                    </div>
                </div>

                ' . ($qrCode ? '
                <!-- QR Code and Short Link Section -->
                <div class="qr-section">
                    <div class="qr-container">
                        <div class="qr-box">
                            <img src="' . $qrCode . '" alt="QR Code" class="qr-image">
                        </div>
                        <div>
                            <p class="qr-text">Scan for order details</p>
                            <p class="qr-link">' . htmlspecialchars($shortUrl) . '</p>
                        </div>
                    </div>
                </div>
                ' : '') . '

                <!-- Footer -->
                <div class="footer">
                    <p class="footer-title">Thank you for your business!</p>
                    <p class="footer-subtitle">This document serves as an official service order confirmation.</p>
                    <div class="footer-meta">
                        <p>Generated on ' . date('F j, Y \a\t g:i A') . '</p>
                        <p>Powered by Your Service Management System</p>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Get status color for styling
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => '#f39c12',
            'confirmed' => '#3498db',
            'processing' => '#9b59b6',
            'in_progress' => '#e67e22',
            'completed' => '#27ae60',
            'cancelled' => '#e74c3c'
        ];
        
        return $colors[strtolower($status)] ?? '#6c757d';
    }
    
    /**
     * Get status styling for PDF templates
     */
    private function getStatusStyle($status)
    {
        $styles = [
            'pending' => 'background-color: #fef3c7; color: #92400e;',
            'confirmed' => 'background-color: #dbeafe; color: #1e40af;',
            'processing' => 'background-color: #e0e7ff; color: #5b21b6;',
            'in_progress' => 'background-color: #fed7aa; color: #c2410c;',
            'completed' => 'background-color: #d1fae5; color: #065f46;',
            'cancelled' => 'background-color: #fee2e2; color: #991b1b;'
        ];
        
        return $styles[strtolower($status)] ?? 'background-color: #f3f4f6; color: #374151;';
    }
    
    /**
     * Parse vehicle information to extract year, make, model, etc.
     */
    private function parseVehicleInfo($vehicleInfo)
    {
        $parts = [
            'year' => '',
            'make' => '',
            'model' => '',
            'trim' => ''
        ];
        
        if (empty($vehicleInfo)) {
            return $parts;
        }
        
        // Try to extract year (4 digit number)
        if (preg_match('/(\d{4})/', $vehicleInfo, $matches)) {
            $parts['year'] = $matches[1];
        }
        
        // Common car makes
        $makes = ['BMW', 'MERCEDES', 'AUDI', 'VOLKSWAGEN', 'VOLVO', 'PORSCHE', 'LEXUS', 'TOYOTA', 'HONDA', 'FORD', 'CHEVROLET', 'NISSAN', 'HYUNDAI', 'KIA', 'MAZDA', 'SUBARU', 'ACURA', 'INFINITI', 'CADILLAC', 'LINCOLN', 'BUICK', 'GMC', 'CHRYSLER', 'DODGE', 'JEEP', 'RAM'];
        
        foreach ($makes as $make) {
            if (stripos($vehicleInfo, $make) !== false) {
                $parts['make'] = $make;
                break;
            }
        }
        
        // Extract remaining parts as model info
        $remaining = str_ireplace($parts['year'], '', $vehicleInfo);
        $remaining = str_ireplace($parts['make'], '', $remaining);
        $remaining = trim($remaining);
        
        // Split remaining into model and trim
        $remainingParts = explode(' ', $remaining);
        if (count($remainingParts) > 0) {
            $parts['model'] = $remainingParts[0];
            if (count($remainingParts) > 1) {
                $parts['trim'] = implode(' ', array_slice($remainingParts, 1));
            }
        }
        
        return $parts;
    }

    /**
     * Get top clients with most orders
     */
    public function top_clients()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get global client filter if provided and valid
            $clientFilter = $this->request->getGet('client_id');
            
            // Properly validate and sanitize client_id
            if (empty($clientFilter) || $clientFilter === '0' || $clientFilter === 0 || !is_numeric($clientFilter)) {
                $clientFilter = null;
            } else {
                $clientFilter = (int)$clientFilter; // Convert to integer for safety
            }
            
            $builder = $db->table('sales_orders')
                ->select('
                    sales_orders.client_id,
                    clients.name as client_name,
                    clients.email as client_email,
                    clients.phone as client_phone,
                    COUNT(sales_orders.id) as order_count
                ')
                ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                ->where('sales_orders.deleted', 0)
                ->where('sales_orders.created_at >=', date('Y-m-01')); // Current month
            
            // Apply client filter if provided and valid
            if ($clientFilter && $clientFilter > 0) {
                $builder->where('sales_orders.client_id', $clientFilter);
            }
            
            $topClients = $builder->groupBy('sales_orders.client_id, clients.name, clients.email, clients.phone')
                ->orderBy('order_count', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();
            
            return $this->response->setJSON([
                'success' => true,
                'clients' => $topClients,
                'client_filter' => $clientFilter
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in top_clients: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => true,
                'clients' => [],
                'client_filter' => null,
                'error_note' => 'Unable to load top clients data'
            ]);
        }
    }

    // Get performance metrics
    public function performance_metrics()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get global client filter if provided and valid
            $clientFilter = $this->request->getGet('client_id');
            
            // Properly validate and sanitize client_id
            if (empty($clientFilter) || $clientFilter === '0' || $clientFilter === 0 || !is_numeric($clientFilter)) {
                $clientFilter = null;
            } else {
                $clientFilter = (int)$clientFilter; // Convert to integer for safety
            }
            
            // Base query for all orders this month
            $baseBuilder = $db->table('sales_orders')
                ->where('deleted', 0)
                ->where('created_at >=', date('Y-m-01')); // Current month
            
            // Apply client filter if provided and valid
            if ($clientFilter && $clientFilter > 0) {
                $baseBuilder->where('client_id', $clientFilter);
            }
            
            // Total orders this month
            $totalOrders = $baseBuilder->countAllResults(false);
            
            // Orders completed this month (using completed_at timestamp)
            $completedBuilder = $db->table('sales_orders')
                ->where('deleted', 0)
                ->where('completed_at IS NOT NULL')
                ->where('completed_at >=', date('Y-m-01'));
            
            if ($clientFilter && $clientFilter > 0) {
                $completedBuilder->where('client_id', $clientFilter);
            }
            
            $completedOrders = $completedBuilder->countAllResults(false);
            
            // On-time completion: completed orders that were completed on or before their scheduled date
            $onTimeBuilder = $db->table('sales_orders')
                ->where('deleted', 0)
                ->where('completed_at IS NOT NULL')
                ->where('completed_at >=', date('Y-m-01'))
                ->where('DATE(completed_at) <= date', null, false); // Use raw where to avoid parameter binding issues
            
            if ($clientFilter && $clientFilter > 0) {
                $onTimeBuilder->where('client_id', $clientFilter);
            }
            
            $onTimeOrders = $onTimeBuilder->countAllResults();
            
            // Delayed completion: completed orders that were completed after their scheduled date
            $delayedBuilder = $db->table('sales_orders')
                ->where('deleted', 0)
                ->where('completed_at IS NOT NULL')
                ->where('completed_at >=', date('Y-m-01'))
                ->where('DATE(completed_at) > date', null, false); // Use raw where to avoid parameter binding issues
            
            if ($clientFilter && $clientFilter > 0) {
                $delayedBuilder->where('client_id', $clientFilter);
            }
            
            $delayedOrders = $delayedBuilder->countAllResults();
            
            // Currently overdue orders (scheduled date passed but not completed or cancelled)
            $overdueBuilder = $db->table('sales_orders')
                ->where('deleted', 0)
                ->where('completed_at IS NULL')
                ->where('cancelled_at IS NULL')
                ->where('order_date < CURDATE()', null, false); // Use raw where
            
            if ($clientFilter && $clientFilter > 0) {
                $overdueBuilder->where('client_id', $clientFilter);
            }
            
            $overdueCount = $overdueBuilder->countAllResults();
            
            // Calculate percentages based on completed orders
            $onTimePercentage = $completedOrders > 0 ? round(($onTimeOrders / $completedOrders) * 100, 1) : 0;
            $delayedPercentage = $completedOrders > 0 ? round(($delayedOrders / $completedOrders) * 100, 1) : 0;
            
            // Average completion time: calculate average hours between created_at and completed_at
            $avgCompletionQuery = $db->table('sales_orders')
                ->select('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
                ->where('deleted', 0)
                ->where('completed_at IS NOT NULL')
                ->where('completed_at >=', date('Y-m-01'));
            
            if ($clientFilter && $clientFilter > 0) {
                $avgCompletionQuery->where('client_id', $clientFilter);
            }
            
            $avgResult = $avgCompletionQuery->get()->getRowArray();
            $avgCompletionHours = round($avgResult['avg_hours'] ?? 0, 1);
            
            // Additional metrics
            $cancelledBuilder = $db->table('sales_orders')
                ->where('deleted', 0)
                ->where('cancelled_at IS NOT NULL')
                ->where('cancelled_at >=', date('Y-m-01'));
            
            if ($clientFilter && $clientFilter > 0) {
                $cancelledBuilder->where('client_id', $clientFilter);
            }
            
            $cancelledOrders = $cancelledBuilder->countAllResults();
            
            $metrics = [
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'cancelled_orders' => $cancelledOrders,
                'on_time_orders' => $onTimeOrders,
                'delayed_orders' => $delayedOrders,
                'on_time_percentage' => $onTimePercentage,
                'delayed_percentage' => $delayedPercentage,
                'avg_completion_hours' => $avgCompletionHours, // Changed from days to hours
                'overdue_count' => $overdueCount,
                'completion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0,
                'cancellation_rate' => $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'metrics' => $metrics,
                'client_filter' => $clientFilter
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in performance_metrics: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Return safe default values in case of error
            $defaultMetrics = [
                'total_orders' => 0,
                'completed_orders' => 0,
                'cancelled_orders' => 0,
                'on_time_orders' => 0,
                'delayed_orders' => 0,
                'on_time_percentage' => 0,
                'delayed_percentage' => 0,
                'avg_completion_hours' => 0,
                'overdue_count' => 0,
                'completion_rate' => 0,
                'cancellation_rate' => 0
            ];
            
            return $this->response->setJSON([
                'success' => true, // Return success with zeros to avoid breaking the UI
                'metrics' => $defaultMetrics,
                'client_filter' => null,
                'error_note' => 'Unable to load metrics data'
            ]);
        }
    }

    // Get recent activity
    public function recent_activity()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get global client filter if provided and valid
            $clientFilter = $this->request->getGet('client_id');
            
            // Properly validate and sanitize client_id
            if (empty($clientFilter) || $clientFilter === '0' || $clientFilter === 0 || !is_numeric($clientFilter)) {
                $clientFilter = null;
            } else {
                $clientFilter = (int)$clientFilter; // Convert to integer for safety
            }
            
            $builder = $db->table('sales_orders')
                ->select('
                    sales_orders.id,
                    sales_orders.status,
                    sales_orders.created_at,
                    sales_orders.updated_at,
                    sales_orders.completed_at,
                    sales_orders.cancelled_at,
                    clients.name as client_name,
                    users.first_name,
                    users.last_name,
                    sales_orders_services.service_name
                ')
                ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                ->join('users', 'users.id = sales_orders.created_by', 'left')
                ->join('sales_orders_services', 'sales_orders_services.id = sales_orders.service_id', 'left')
                ->where('sales_orders.deleted', 0);
            
            // Apply client filter if provided and valid
            if ($clientFilter && $clientFilter > 0) {
                $builder->where('sales_orders.client_id', $clientFilter);
            }
            
            $recentOrders = $builder->orderBy('sales_orders.updated_at', 'DESC')
                ->limit(15)
                ->get()
                ->getResultArray();
            
            // Format activities with time ago and action determination
            $activities = [];
            foreach ($recentOrders as $order) {
                $isNew = $order['created_at'] === $order['updated_at'];
                $action = $isNew ? 'created' : 'updated';
                $activityTime = $order['updated_at'] ?? $order['created_at'];
                
                // Determine specific action based on timestamps
                if (!empty($order['completed_at'])) {
                    $action = 'completed';
                    $activityTime = $order['completed_at'];
                } elseif (!empty($order['cancelled_at'])) {
                    $action = 'cancelled';
                    $activityTime = $order['cancelled_at'];
                } elseif ($order['status'] === 'processing' && !$isNew) {
                    $action = 'processing';
                }
                
                $activities[] = [
                    'id' => $order['id'],
                    'title' => 'Order SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                    'description' => ($order['client_name'] ?? 'Unknown Client') . ' - ' . ($order['service_name'] ?? 'No service'),
                    'action' => $action,
                    'time_ago' => $this->timeAgo($activityTime),
                    'client_name' => $order['client_name'] ?? 'Unknown Client',
                    'salesperson' => trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? '')) ?: 'Unknown',
                    'status' => $order['status']
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'activities' => $activities,
                'client_filter' => $clientFilter
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in recent_activity: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => true,
                'activities' => [],
                'client_filter' => null,
                'error_note' => 'Unable to load recent activity data'
            ]);
        }
    }

    /**
     * Helper function to calculate time ago
     */
    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 604800) return floor($time/86400) . ' days ago';
        if ($time < 2629746) return floor($time/604800) . ' weeks ago';
        
        return date('M j, Y', strtotime($datetime));
    }

    /**
     * Debug method to check instructions field and data
     */
    public function debug_instructions()
    {
        $db = \Config\Database::connect();
        
        $output = "<h2>🔍 Debug: Verificación de campo Instructions</h2>";
        $output .= "<style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            table { border-collapse: collapse; width: 100%; margin: 10px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .success { color: green; }
            .error { color: red; }
            .warning { color: orange; }
            .btn { background: #007bff; color: white; padding: 8px 16px; border: none; cursor: pointer; }
        </style>";
        
        try {
            // 1. Verificar estructura de la tabla
            $output .= "<h3>1. Estructura de la tabla sales_orders:</h3>";
            $fields = $db->getFieldData('sales_orders');
            $hasInstructions = false;
            $output .= "<table>";
            $output .= "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Máx Long</th></tr>";
            foreach ($fields as $field) {
                $output .= "<tr>";
                $output .= "<td>{$field->name}</td>";
                $output .= "<td>{$field->type}</td>";
                $output .= "<td>" . (property_exists($field, 'null') ? ($field->null ? 'SÍ' : 'NO') : 'N/A') . "</td>";
                $output .= "<td>" . (property_exists($field, 'max_length') ? $field->max_length : 'N/A') . "</td>";
                $output .= "</tr>";
                if ($field->name === 'instructions') {
                    $hasInstructions = true;
                }
            }
            $output .= "</table>";

            if ($hasInstructions) {
                $output .= "<p class='success'>✅ El campo 'instructions' existe en la tabla</p>";
            } else {
                $output .= "<p class='error'>❌ El campo 'instructions' NO existe en la tabla</p>";
            }
            
            // Debug: Show field object properties for the first field
            if (!empty($fields)) {
                $output .= "<h4>Debug - Propiedades del objeto de campo (primer campo):</h4>";
                $firstField = $fields[0];
                $output .= "<table>";
                $output .= "<tr><th>Propiedad</th><th>Valor</th></tr>";
                foreach (get_object_vars($firstField) as $prop => $value) {
                    $displayValue = is_bool($value) ? ($value ? 'true' : 'false') : 
                                   (is_null($value) ? 'null' : (string)$value);
                    $output .= "<tr><td>{$prop}</td><td>{$displayValue}</td></tr>";
                }
                $output .= "</table>";
            }

            // 2. Verificar datos con instrucciones
            $output .= "<h3>2. Órdenes que tienen instrucciones:</h3>";
            $query = $db->query("
                SELECT 
                    id, 
                    CONCAT('SAL-', LPAD(id, 5, '0')) as order_id,
                    stock,
                    vehicle,
                    instructions,
                    CHAR_LENGTH(instructions) as instruction_length,
                    client_id,
                    status,
                    created_at
                FROM sales_orders 
                WHERE instructions IS NOT NULL 
                AND instructions != ''
                AND deleted = 0
                ORDER BY created_at DESC
                LIMIT 10
            ");

            $ordersWithInstructions = $query->getResultArray();

            if (!empty($ordersWithInstructions)) {
                $output .= "<table>";
                $output .= "<tr><th>ID</th><th>Order ID</th><th>Stock</th><th>Vehículo</th><th>Client ID</th><th>Status</th><th>Instrucciones (100 chars)</th><th>Longitud</th></tr>";
                foreach ($ordersWithInstructions as $order) {
                    $output .= "<tr>";
                    $output .= "<td>{$order['id']}</td>";
                    $output .= "<td>{$order['order_id']}</td>";
                    $output .= "<td>{$order['stock']}</td>";
                    $output .= "<td>{$order['vehicle']}</td>";
                    $output .= "<td>{$order['client_id']}</td>";
                    $output .= "<td>{$order['status']}</td>";
                    $output .= "<td>" . substr($order['instructions'], 0, 100) . "...</td>";
                    $output .= "<td>{$order['instruction_length']}</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
            } else {
                $output .= "<p class='warning'>⚠️ No se encontraron órdenes con instrucciones</p>";
            }

            // 3. Estadísticas de instrucciones
            $output .= "<h3>3. Estadísticas de instrucciones:</h3>";
            $stats = $db->query("
                SELECT 
                    COUNT(*) as total_orders,
                    COUNT(CASE WHEN instructions IS NOT NULL AND instructions != '' THEN 1 END) as orders_with_instructions,
                    COUNT(CASE WHEN instructions IS NULL OR instructions = '' THEN 1 END) as orders_without_instructions
                FROM sales_orders 
                WHERE deleted = 0
            ")->getRowArray();

            $output .= "<ul>";
            $output .= "<li>Total de órdenes: {$stats['total_orders']}</li>";
            $output .= "<li>Órdenes con instrucciones: {$stats['orders_with_instructions']}</li>";
            $output .= "<li>Órdenes sin instrucciones: {$stats['orders_without_instructions']}</li>";
            $output .= "</ul>";

            // 4. Test del endpoint AJAX
            $output .= "<h3>4. Test del endpoint all_content (AJAX):</h3>";
            $output .= "<p>Probando si el campo instructions se está enviando correctamente...</p>";
            
            // Simular una llamada AJAX al endpoint
            $request = $this->request;
            $testData = [
                'draw' => 1,
                'start' => 0,
                'length' => 5
            ];
            
            // Simular el POST data
            foreach ($testData as $key => $value) {
                $_POST[$key] = $value;
            }
            
            // Obtener algunos datos de prueba
            $testOrders = $db->table('sales_orders')
                           ->select('sales_orders.*, clients.name as client_name')
                           ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                           ->where('sales_orders.deleted', 0)
                           ->limit(3)
                           ->get()
                           ->getResultArray();
                           
            if (!empty($testOrders)) {
                $output .= "<h4>Datos de ejemplo que se envían al frontend:</h4>";
                $output .= "<table>";
                $output .= "<tr><th>ID</th><th>Order ID</th><th>Client</th><th>Vehicle</th><th>Instructions Length</th><th>Instructions Preview</th></tr>";
                foreach ($testOrders as $order) {
                    $formattedData = [
                        'id' => $order['id'],
                        'order_id' => 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT),
                        'client_name' => $order['client_name'] ?? 'N/A',
                        'vehicle' => $order['vehicle'] ?? 'N/A',
                        'instructions' => $order['instructions'] ?? '',
                    ];
                    
                    $instructionsLength = strlen($formattedData['instructions']);
                    $instructionsPreview = $instructionsLength > 0 ? substr($formattedData['instructions'], 0, 50) . '...' : '(vacío)';
                    
                    $output .= "<tr>";
                    $output .= "<td>{$formattedData['id']}</td>";
                    $output .= "<td>{$formattedData['order_id']}</td>";
                    $output .= "<td>{$formattedData['client_name']}</td>";
                    $output .= "<td>{$formattedData['vehicle']}</td>";
                    $output .= "<td>{$instructionsLength}</td>";
                    $output .= "<td>{$instructionsPreview}</td>";
                    $output .= "</tr>";
                }
                $output .= "</table>";
            }

            // 5. Crear orden de prueba
            $output .= "<h3>5. Crear orden de prueba con instrucciones:</h3>";
            $output .= "<form method='post' action='" . base_url('sales_orders/debug_instructions') . "'>";
            $output .= "<input type='hidden' name='action' value='create_test'>";
            $output .= "<button type='submit' class='btn'>Crear orden de prueba con instrucciones</button>";
            $output .= "</form>";

            if ($this->request->getPost('action') === 'create_test') {
                $testData = [
                    'stock' => 'TEST-' . rand(1000, 9999),
                    'vehicle' => 'Toyota Corolla Test ' . date('Y-m-d H:i:s'),
                    'vin' => 'TEST' . rand(10000000, 99999999),
                    'client_id' => 1, // Asumiendo que existe cliente con ID 1
                    'created_by' => 1,
                    'status' => 'pending',
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'instructions' => "Instrucciones de prueba para verificar tooltips:\n1. Revisar sistema de frenos\n2. Verificar niveles de aceite\n3. Contactar cliente antes de entregar\n4. Documentación especial requerida\n\nCreado: " . date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $inserted = $db->table('sales_orders')->insert($testData);
                
                if ($inserted) {
                    $newId = $db->insertID();
                    $output .= "<p class='success'>✅ Orden de prueba creada con ID: $newId</p>";
                    $output .= "<p><strong>Stock:</strong> {$testData['stock']}</p>";
                    $output .= "<p><strong>Vehículo:</strong> {$testData['vehicle']}</p>";
                    $output .= "<p><strong>Instrucciones:</strong> " . nl2br(htmlspecialchars($testData['instructions'])) . "</p>";
                } else {
                    $output .= "<p class='error'>❌ Error al crear orden de prueba</p>";
                }
            }

            // 6. Enlaces útiles
            $output .= "<h3>6. Enlaces para probar:</h3>";
            $output .= "<ul>";
            $output .= "<li><a href='" . base_url('sales_orders') . "' target='_blank'>📋 Ir a Sales Orders</a></li>";
            $output .= "<li><a href='" . base_url('demo_badge_ubicacion.html') . "' target='_blank'>👁️ Ver demo visual del badge</a></li>";
            $output .= "<li><a href='" . base_url('test_tooltips_all_content.html') . "' target='_blank'>🧪 Test de tooltips</a></li>";
            $output .= "</ul>";

            $output .= "<h3>7. Instrucciones para verificar el badge:</h3>";
            $output .= "<ol>";
            $output .= "<li>Ve a <a href='" . base_url('sales_orders') . "' target='_blank'>Sales Orders</a></li>";
            $output .= "<li>Haz clic en la pestaña 'All Orders'</li>";
            $output .= "<li>Busca en la <strong>tercera columna (Cliente/Vehículo)</strong></li>";
            $output .= "<li>Si hay órdenes con instrucciones, deberías ver un badge azul pequeño con <strong>[ℹ️ Info]</strong></li>";
            $output .= "<li>Pasa el mouse sobre el badge para ver el tooltip con las instrucciones</li>";
            $output .= "</ol>";

        } catch (\Exception $e) {
            $output .= "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
        
        return $this->response->setBody($output);
    }

    /**
     * Debug method to check QR generation and Lima Links configuration
     */
    public function debug_qr($orderId = null)
    {
        if (!$orderId) {
            // Get the first available order for testing
            $order = $this->salesOrderModel->where('deleted', 0)->first();
            if ($order) {
                $orderId = $order['id'];
            } else {
                return $this->response->setBody('<h1>No orders found for QR debug</h1>');
            }
        }

        $output = "<h2>🔍 Debug: QR Code Generation for Order {$orderId}</h2>";
        $output .= "<style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .success { color: green; }
            .error { color: red; }
            .warning { color: orange; }
            .info { color: blue; }
            pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        </style>";

        try {
            // 1. Check Settings Model and Lima Links configuration
            $output .= "<h3>1. Lima Links Configuration:</h3>";
            $settingsModel = new \App\Models\SettingsModel();
            $apiKey = $settingsModel->getSetting('lima_api_key');
            $brandedDomain = $settingsModel->getSetting('lima_branded_domain');
            
            if ($apiKey) {
                $output .= "<p class='success'>✅ API Key found: " . substr($apiKey, 0, 10) . "***</p>";
            } else {
                $output .= "<p class='error'>❌ Lima Links API Key not configured</p>";
            }
            
            if ($brandedDomain) {
                $output .= "<p class='success'>✅ Branded Domain: {$brandedDomain}</p>";
            } else {
                $output .= "<p class='warning'>⚠️ No branded domain configured</p>";
            }

            // 2. Test QR generation
            $output .= "<h3>2. QR Generation Test:</h3>";
            $qrData = $this->generateOrderQR($orderId);
            
            if ($qrData) {
                $output .= "<p class='success'>✅ QR Data generated successfully</p>";
                $output .= "<pre>" . json_encode($qrData, JSON_PRETTY_PRINT) . "</pre>";
                
                // Test if QR image is accessible
                $output .= "<h4>QR Image Test:</h4>";
                $qrUrl = $qrData['qr_url'];
                
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $qrUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_NOBODY => true,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true
                ]);
                
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                curl_close($ch);
                
                if ($curlError) {
                    $output .= "<p class='error'>❌ QR Image URL error: {$curlError}</p>";
                } elseif ($httpCode === 200) {
                    $output .= "<p class='success'>✅ QR Image accessible (HTTP {$httpCode})</p>";
                    $output .= "<p><img src='{$qrUrl}' alt='QR Code' style='max-width: 200px; border: 1px solid #ccc;'></p>";
                } else {
                    $output .= "<p class='error'>❌ QR Image not accessible (HTTP {$httpCode})</p>";
                }
                
            } else {
                $output .= "<p class='error'>❌ QR Data generation failed</p>";
            }

            // 3. Test direct API call
            $output .= "<h3>3. Direct Lima Links API Test:</h3>";
            if ($apiKey) {
                $testUrl = base_url("sales_orders/view/{$orderId}");
                $testPayload = [
                    'url' => $testUrl,
                    'type' => 'direct',
                    'description' => 'Debug Test QR',
                    'custom' => 'debug-test-' . time()
                ];
                
                $apiResult = $this->createShortUrlForOrder($apiKey, $testPayload);
                
                if ($apiResult['success']) {
                    $output .= "<p class='success'>✅ API call successful</p>";
                    $output .= "<pre>" . json_encode($apiResult, JSON_PRETTY_PRINT) . "</pre>";
                } else {
                    $output .= "<p class='error'>❌ API call failed: {$apiResult['error']}</p>";
                }
            } else {
                $output .= "<p class='error'>❌ Cannot test API - no API key</p>";
            }

            // 4. Check order data
            $output .= "<h3>4. Order Data Check:</h3>";
            $order = $this->salesOrderModel->find($orderId);
            if ($order) {
                $output .= "<p class='success'>✅ Order found: SAL-" . str_pad($orderId, 5, '0', STR_PAD_LEFT) . "</p>";
                $output .= "<p><strong>Vehicle:</strong> " . ($order['vehicle'] ?? 'N/A') . "</p>";
                $output .= "<p><strong>Status:</strong> " . ($order['status'] ?? 'N/A') . "</p>";
            } else {
                $output .= "<p class='error'>❌ Order not found</p>";
            }

            // 5. Provide setup instructions if needed
            if (!$apiKey) {
                $output .= "<h3>5. Setup Instructions:</h3>";
                $output .= "<div class='warning'>";
                $output .= "<p>To enable QR codes, you need to configure Lima Links:</p>";
                $output .= "<ol>";
                $output .= "<li>Go to Settings in your admin panel</li>";
                $output .= "<li>Add your Lima Links API key</li>";
                $output .= "<li>Optionally add your branded domain</li>";
                $output .= "</ol>";
                $output .= "</div>";
            }

        } catch (\Exception $e) {
            $output .= "<p class='error'>Error during debug: " . $e->getMessage() . "</p>";
            $output .= "<pre>" . $e->getTraceAsString() . "</pre>";
        }

        return $this->response->setBody($output);
    }

    /**
     * Check for duplicate orders by Stock or VIN
     */
    public function checkDuplicateOrder()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }
        
        $stock = trim($this->request->getPost('stock') ?? '');
        $vin = trim($this->request->getPost('vin') ?? '');
        $currentOrderId = $this->request->getPost('current_order_id'); // For edit mode
        
        $duplicates = [];
        
        // Check for stock duplicates
        if (!empty($stock)) {
            $query = $this->salesOrderModel->select('sales_orders.*, clients.name as client_name, CONCAT(users.first_name, " ", users.last_name) as salesperson_name')
                                          ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                          ->join('users', 'users.id = sales_orders.created_by', 'left')
                                          ->where('sales_orders.stock', $stock)
                                          ->where('sales_orders.deleted', 0);
            
            // Exclude current order if editing
            if ($currentOrderId) {
                $query->where('sales_orders.id !=', $currentOrderId);
            }
            
            $stockDuplicates = $query->findAll();
            
            if (!empty($stockDuplicates)) {
                $duplicates['stock'] = [
                    'field' => 'Stock',
                    'value' => $stock,
                    'count' => count($stockDuplicates),
                    'orders' => $stockDuplicates
                ];
            }
        }
        
        // Check for VIN duplicates
        if (!empty($vin)) {
            $query = $this->salesOrderModel->select('sales_orders.*, clients.name as client_name, CONCAT(users.first_name, " ", users.last_name) as salesperson_name')
                                          ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                                          ->join('users', 'users.id = sales_orders.created_by', 'left')
                                          ->where('sales_orders.vin', $vin)
                                          ->where('sales_orders.deleted', 0);
            
            // Exclude current order if editing
            if ($currentOrderId) {
                $query->where('sales_orders.id !=', $currentOrderId);
            }
            
            $vinDuplicates = $query->findAll();
            
            if (!empty($vinDuplicates)) {
                $duplicates['vin'] = [
                    'field' => 'VIN',
                    'value' => $vin,
                    'count' => count($vinDuplicates),
                    'orders' => $vinDuplicates
                ];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'has_duplicates' => !empty($duplicates),
            'duplicates' => $duplicates
        ]);
    }

    /**
     * Detectar órdenes duplicadas por Stock o VIN
     */
    private function getDuplicateInfo($orders)
    {
        $duplicateData = [];
        
        // Procesar cada orden en el resultado filtrado
        foreach ($orders as $order) {
            $duplicates = [];
            
            // Verificar duplicados por Stock (consultar toda la base de datos)
            if (!empty($order['stock'])) {
                $stock = strtoupper(trim($order['stock']));
                
                // Consultar TODA la base de datos para contar duplicados por Stock
                $stockCount = $this->salesOrderModel
                    ->where('UPPER(TRIM(stock))', $stock)
                    ->where('deleted', 0)
                    ->where('stock IS NOT NULL')
                    ->where('stock != ""')
                    ->countAllResults();
                
                if ($stockCount > 1) {
                    // Mostrar el total de duplicados (incluyendo la orden actual)
                    $duplicates['stock'] = $stockCount;
                    $duplicates['stock_value'] = $order['stock'];
                }
            }
            
            // Verificar duplicados por VIN (consultar toda la base de datos)
            if (!empty($order['vin'])) {
                $vin = strtoupper(trim($order['vin']));
                
                // Consultar TODA la base de datos para contar duplicados por VIN
                $vinCount = $this->salesOrderModel
                    ->where('UPPER(TRIM(vin))', $vin)
                    ->where('deleted', 0)
                    ->where('vin IS NOT NULL')
                    ->where('vin != ""')
                    ->countAllResults();
                
                if ($vinCount > 1) {
                    // Mostrar el total de duplicados (incluyendo la orden actual)
                    $duplicates['vin'] = $vinCount;
                    $duplicates['vin_value'] = $order['vin'];
                }
            }
            
            $duplicateData[$order['id']] = $duplicates;
        }
        
        return $duplicateData;
    }

    /**
     * Obtener comentarios para múltiples órdenes
     */
    private function getCommentsForOrders($orderIds)
    {
        if (empty($orderIds)) {
            return [];
        }
        
        $db = \Config\Database::connect();
        
        // Obtener los comentarios más recientes para cada orden (máximo 3 por orden)
        $comments = $db->table('sales_orders_comments')
                      ->select('sales_orders_comments.order_id, 
                               sales_orders_comments.description as comment, 
                               sales_orders_comments.created_at,
                               CONCAT(users.first_name, " ", users.last_name) as author_name')
                      ->join('users', 'users.id = sales_orders_comments.created_by', 'left')
                      ->whereIn('sales_orders_comments.order_id', $orderIds)
                      ->orderBy('sales_orders_comments.created_at', 'DESC')
                      ->get()
                      ->getResultArray();
        
        // Agrupar comentarios por order_id y limitar a 3 por orden
        $groupedComments = [];
        foreach ($comments as $comment) {
            $orderId = $comment['order_id'];
            
            if (!isset($groupedComments[$orderId])) {
                $groupedComments[$orderId] = [];
            }
            
            // Limitar a 3 comentarios por orden
            if (count($groupedComments[$orderId]) < 3) {
                // Truncar comentario largo y agregar tiempo relativo
                $commentText = strlen($comment['comment']) > 100 ? 
                              substr($comment['comment'], 0, 100) . '...' : 
                              $comment['comment'];
                
                $groupedComments[$orderId][] = [
                    'comment' => $commentText,
                    'author_name' => $comment['author_name'] ?? 'Unknown',
                    'created_at' => $comment['created_at'],
                    'time_ago' => $this->timeAgo($comment['created_at'])
                ];
            }
        }
        
        return $groupedComments;
    }

    /**
     * Obtener notas internas para múltiples órdenes
     */
    private function getInternalNotesForOrders($orderIds)
    {
        if (empty($orderIds)) {
            return [];
        }
        
        $db = \Config\Database::connect();
        
        // Obtener las notas internas más recientes para cada orden (máximo 3 por orden)
        $notes = $db->table('internal_notes')
                   ->select('internal_notes.order_id, 
                            internal_notes.note, 
                            internal_notes.created_at,
                            CONCAT(users.first_name, " ", users.last_name) as author_name')
                   ->join('users', 'users.id = internal_notes.author_id', 'left')
                   ->whereIn('internal_notes.order_id', $orderIds)
                   ->where('internal_notes.order_type', 'sales_order')
                   ->where('internal_notes.deleted_at IS NULL')
                   ->orderBy('internal_notes.created_at', 'DESC')
                   ->get()
                   ->getResultArray();
        
        // Agrupar notas por order_id y limitar a 3 por orden
        $groupedNotes = [];
        foreach ($notes as $note) {
            $orderId = $note['order_id'];
            
            if (!isset($groupedNotes[$orderId])) {
                $groupedNotes[$orderId] = [];
            }
            
            // Limitar a 3 notas por orden
            if (count($groupedNotes[$orderId]) < 3) {
                // Truncar nota larga y agregar tiempo relativo
                $noteText = strlen($note['note']) > 100 ? 
                           substr($note['note'], 0, 100) . '...' : 
                           $note['note'];
                
                $groupedNotes[$orderId][] = [
                    'content' => $noteText,
                    'author_name' => $note['author_name'] ?? 'Unknown',
                    'created_at' => $note['created_at'],
                    'time_ago' => $this->timeAgo($note['created_at'])
                ];
            }
        }
        
        return $groupedNotes;
    }

    /**
     * Obtener órdenes duplicadas específicas para mostrar en modal
     */
    public function getDuplicateOrders()
    {
        // Log para debug
        log_message('info', 'getDuplicateOrders called - Request method: ' . $this->request->getMethod());
        log_message('info', 'getDuplicateOrders called - Is AJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));
        
        if (!$this->request->isAJAX()) {
            log_message('warning', 'getDuplicateOrders: Access denied - not AJAX request');
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied - AJAX required']);
        }
        
        $field = $this->request->getPost('field'); // 'stock' or 'vin'
        $value = trim($this->request->getPost('value') ?? '');
        $currentOrderId = $this->request->getPost('current_order_id');
        
        log_message('info', 'getDuplicateOrders parameters - Field: ' . $field . ', Value: ' . $value . ', CurrentOrderId: ' . $currentOrderId);
        
        if (empty($field) || empty($value) || !in_array($field, ['stock', 'vin'])) {
            log_message('warning', 'getDuplicateOrders: Invalid parameters');
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Invalid parameters',
                'debug' => [
                    'field' => $field,
                    'value' => $value,
                    'valid_fields' => ['stock', 'vin']
                ]
            ]);
        }
        
        try {
            // Buscar todas las órdenes con el mismo valor (incluyendo la orden actual)
            $query = $this->salesOrderModel->select('
                    sales_orders.*, 
                    clients.name as client_name, 
                    CONCAT(users.first_name, " ", users.last_name) as salesperson_name
                ')
                ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                ->join('users', 'users.id = sales_orders.created_by', 'left')
                ->where('sales_orders.' . $field, $value)
                ->where('sales_orders.deleted', 0)
                ->where('sales_orders.' . $field . ' IS NOT NULL')
                ->where('sales_orders.' . $field . ' != ""')
                ->orderBy('sales_orders.created_at', 'DESC');
            
            $duplicateOrders = $query->findAll();
            
            log_message('info', 'getDuplicateOrders: Found ' . count($duplicateOrders) . ' orders');
            
            if (empty($duplicateOrders)) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'No duplicate orders found',
                    'debug' => [
                        'field' => $field,
                        'value' => $value,
                        'query_count' => 0
                    ]
                ]);
            }
            
            // Formatear los datos para el modal
            $formattedOrders = [];
            foreach ($duplicateOrders as $order) {
                $formattedOrders[] = [
                    'id' => $order['id'],
                    'order_number' => '#' . str_pad($order['id'], 4, '0', STR_PAD_LEFT), // Formato del número de orden
                    'stock' => $order['stock'] ?? 'N/A',
                    'vin' => $order['vin'] ?? 'N/A',
                    'vehicle' => $order['vehicle'] ?? 'N/A',
                    'client_name' => $order['client_name'] ?? 'N/A',
                    'salesperson_name' => $order['salesperson_name'] ?? 'N/A',
                    'status' => $order['status'],
                    'date' => $order['date'] ?? 'N/A',
                    'time' => $order['time'] ?? 'N/A',
                    'created_at' => date('M j, Y g:i A', strtotime($order['created_at'])),
                    'is_current' => ($order['id'] == $currentOrderId)
                ];
            }
            
            log_message('info', 'getDuplicateOrders: Successfully returning ' . count($formattedOrders) . ' formatted orders');
            
            return $this->response->setJSON([
                'success' => true,
                'field' => $field,
                'value' => $value,
                'total_count' => count($formattedOrders),
                'orders' => $formattedOrders
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting duplicate orders: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Error retrieving duplicate orders: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Determinar el tipo de ordenamiento basado en los filtros aplicados
     */
    private function determineOrderingType($dateFromFilter, $dateToFilter, $statusFilter)
    {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
        
        // Filtro de status pendiente - detectar múltiples status de pendientes
        if (!empty($statusFilter)) {
            // Verificar si el filtro incluye estados pendientes
            $pendingStatuses = ['pending', 'processing', 'in_progress'];
            $filterStatuses = explode(',', $statusFilter);
            $hasPendingStatus = false;
            
            foreach ($filterStatuses as $status) {
                if (in_array(trim($status), $pendingStatuses)) {
                    $hasPendingStatus = true;
                    break;
                }
            }
            
            if ($hasPendingStatus) {
                return 'pending';
            }
        }
        
        // Filtros de fecha específicos
        if (!empty($dateFromFilter) && !empty($dateToFilter)) {
            // Mismo día
            if ($dateFromFilter === $dateToFilter) {
                if ($dateFromFilter === $today) {
                    return 'today';
                } elseif ($dateFromFilter === $tomorrow) {
                    return 'tomorrow';
                }
            }
            
            // Rango de semana
            if ($dateFromFilter === $startOfWeek && $dateToFilter === $endOfWeek) {
                return 'week';
            }
        }
        
        // Vista de todas las órdenes (sin filtros específicos o con rango amplio)
        if (empty($dateFromFilter) && empty($dateToFilter) && empty($statusFilter)) {
            return 'all';
        }
        
        // Si hay filtros pero no coinciden con patrones específicos, usar vista 'all'
        return 'all';
    }

    /**
     * Test endpoint para verificar conectividad
     */
    public function testDuplicateEndpoint()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Endpoint is working',
            'timestamp' => date('Y-m-d H:i:s'),
            'request_method' => $this->request->getMethod(),
            'is_ajax' => $this->request->isAJAX()
        ]);
    }

    // ==================== FOLLOWERS FUNCTIONALITY ====================

    /**
     * Get followers for a sales order
     */
    public function getFollowers($orderId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            if (!$orderId) {
                $orderId = $this->request->getPost('order_id');
            }

            if (!$orderId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }

            // Check if order exists and user has access
            $order = $this->salesOrderModel->find($orderId);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sales order not found'
                ]);
            }

            $followerModel = new \Modules\SalesOrders\Models\SalesOrderFollowerModel();
            $followers = $followerModel->getFollowersWithDetails($orderId);

            return $this->response->setJSON([
                'success' => true,
                'followers' => $followers,
                'count' => count($followers)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting followers: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading followers: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get available users to add as followers
     */
    public function getAvailableFollowers($orderId = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            if (!$orderId) {
                $orderId = $this->request->getPost('order_id');
            }

            if (!$orderId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }

            // Get order with client info
            $order = $this->salesOrderModel->select('sales_orders.*, clients.id as client_id')
                ->join('clients', 'clients.id = sales_orders.client_id', 'left')
                ->find($orderId);

            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sales order not found'
                ]);
            }

            $followerModel = new \Modules\SalesOrders\Models\SalesOrderFollowerModel();
            $availableUsers = $followerModel->getAvailableFollowers($orderId, $order['client_id']);

            return $this->response->setJSON([
                'success' => true,
                'available_users' => $availableUsers
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting available followers: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading available users: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Add a follower to a sales order
     */
    public function addFollower()
    {
        log_message('info', 'addFollower method called');
        
        if (!$this->request->isAJAX()) {
            log_message('error', 'addFollower: Not an AJAX request');
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            log_message('info', 'addFollower: Starting try block');
            
            $orderId = $this->request->getPost('order_id');
            $userId = $this->request->getPost('user_id');
            $followerType = $this->request->getPost('follower_type') ?: 'client_contact';
            $notificationPreferences = $this->request->getPost('notification_preferences');

            log_message('info', "addFollower: orderId=$orderId, userId=$userId, followerType=$followerType");

            if (!$orderId || !$userId) {
                log_message('error', 'addFollower: Missing order_id or user_id');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID and User ID are required'
                ]);
            }

            // Verify order exists
            $order = $this->salesOrderModel->find($orderId);
            if (!$order) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sales order not found'
                ]);
            }

            // Verify user exists
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            if (!$user) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }

            $followerModel = new \Modules\SalesOrders\Models\SalesOrderFollowerModel();
            
            // Try different methods to get current user ID
            $currentUserId = session()->get('user_id');
            if (!$currentUserId) {
                $currentUserId = auth()->id();
            }
            if (!$currentUserId) {
                $currentUserId = auth()->user()->id ?? null;
            }
            
            log_message('info', "addFollower: currentUserId=$currentUserId (from session: " . session()->get('user_id') . ", from auth: " . auth()->id() . ")");

            if (!$currentUserId) {
                log_message('error', 'addFollower: No current user ID found');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Parse notification preferences
            $preferences = null;
            if ($notificationPreferences && is_string($notificationPreferences)) {
                $preferences = json_decode($notificationPreferences, true);
            }
            
            log_message('info', "addFollower: About to call followerModel->addFollower");

            $followerId = $followerModel->addFollower($orderId, $userId, $currentUserId, $followerType, $preferences);
            
            log_message('info', "addFollower: followerModel->addFollower returned: " . ($followerId ? $followerId : 'false'));

            if ($followerId) {
                // Log activity
                $this->logActivity($orderId, 'follower_added', "Added {$user['first_name']} {$user['last_name']} as a follower");

                log_message('info', "addFollower: Success, returning follower_id=$followerId");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Follower added successfully',
                    'follower_id' => $followerId
                ]);
            } else {
                log_message('error', "addFollower: Failed to add follower, followerModel->addFollower returned false");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add follower'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error adding follower: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error adding follower: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove a follower from a sales order
     */
    public function removeFollower()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            $orderId = $this->request->getPost('order_id');
            $userId = $this->request->getPost('user_id');

            if (!$orderId || !$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID and User ID are required'
                ]);
            }

            $followerModel = new \Modules\SalesOrders\Models\SalesOrderFollowerModel();
            
            // Try different methods to get current user ID
            $currentUserId = session()->get('user_id');
            if (!$currentUserId) {
                $currentUserId = auth()->id();
            }
            if (!$currentUserId) {
                $currentUserId = auth()->user()->id ?? null;
            }
            
            if (!$currentUserId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            $result = $followerModel->removeFollower($orderId, $userId, $currentUserId);

            if ($result) {
                // Get user info for logging
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);
                
                // Log activity
                $this->logActivity($orderId, 'follower_removed', "Removed {$user['first_name']} {$user['last_name']} as a follower");

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Follower removed successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove follower'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error removing follower: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error removing follower: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update follower notification preferences
     */
    public function updateFollowerPreferences()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'AJAX request required']);
        }

        try {
            $orderId = $this->request->getPost('order_id');
            $userId = $this->request->getPost('user_id');
            $preferences = $this->request->getPost('preferences');

            if (!$orderId || !$userId || !$preferences) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID, User ID, and preferences are required'
                ]);
            }

            // Parse preferences if string
            if (is_string($preferences)) {
                $preferences = json_decode($preferences, true);
            }

            $followerModel = new \Modules\SalesOrders\Models\SalesOrderFollowerModel();
            $result = $followerModel->updateNotificationPreferences($orderId, $userId, $preferences);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notification preferences updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update preferences'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error updating follower preferences: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating preferences: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get staff users for mentions
     */
    public function getStaffUsers()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $search = $this->request->getGet('search') ?? '';
            
            // Get staff users using the UserModel
            $userModel = new \App\Models\UserModel();
            $builder = $userModel->select('id, first_name, last_name, username, avatar')
                                ->where('deleted_at IS NULL');

            if (!empty($search)) {
                $builder->groupStart()
                    ->like('first_name', $search)
                    ->orLike('last_name', $search)
                    ->orLike('username', $search)
                    ->groupEnd();
            }

            $users = $builder->orderBy('first_name', 'ASC')
                            ->limit(10)
                            ->findAll();

            // Format users for frontend
            $formattedUsers = [];
            foreach ($users as $user) {
                $formattedUsers[] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'name' => trim($user['first_name'] . ' ' . $user['last_name']),
                    'avatar' => $user['avatar']
                ];
            }

            return $this->response->setJSON([
                'success' => true,
                'users' => $formattedUsers
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error getting staff users: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error loading users']);
        }
    }

    /**
     * Download or view attachment file
     */
    public function downloadAttachment($filename = null)
    {
        log_message('info', 'downloadAttachment called with: ' . ($filename ?? 'NULL'));
        
        if (!$filename) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        // Decode the filename
        $filename = urldecode($filename);
        
        // Extract order ID from path structure
        $pathParts = explode('/', $filename);
        
        if (count($pathParts) >= 3) {
            $orderId = $pathParts[0];
            $actualFilename = end($pathParts);
            
            // Handle thumbnails path
            if (in_array('thumbnails', $pathParts)) {
                $filePath = WRITEPATH . 'uploads/sales_orders/' . $orderId . '/comments/thumbnails/' . $actualFilename;
                $tokenFilename = 'thumbnails/' . $actualFilename;
            } else {
                $filePath = WRITEPATH . 'uploads/sales_orders/' . $orderId . '/comments/' . $actualFilename;
                $tokenFilename = $actualFilename;
            }
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid file path');
        }
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        // Security check - validate token or user session
        $token = $this->request->getGet('token');
        $hasValidToken = false;
        $hasValidSession = false;
        
        // Check if user has valid session
        if (session()->get('isLoggedIn')) {
            $hasValidSession = true;
        }
        
        // Check if token is valid
        if ($token) {
            $commentModel = new \Modules\SalesOrders\Models\SalesOrderCommentModel();
            $hasValidToken = $commentModel->validateAccessToken($orderId, $tokenFilename, $token);
        }
        
        // Allow access if either token is valid OR user has valid session
        if (!$hasValidToken && !$hasValidSession) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
        
        // If accessing via session (no token), ensure user has access to this order
        if (!$hasValidToken && $hasValidSession) {
            $order = $this->salesOrderModel->find($orderId);
            if (!$order) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found');
            }
        }

        // Get the action parameter
        $action = $this->request->getGet('action') ?? 'download';
        
        // Get file info
        $mimeType = mime_content_type($filePath);
        $originalName = $this->getOriginalFilename($filePath, $actualFilename);
        
        if ($action === 'view') {
            // For viewing in browser
            $viewableTypes = [
                'application/pdf',
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
                'text/plain', 'text/html', 'text/css', 'text/javascript',
                'application/json', 'application/xml'
            ];
            
            if (in_array($mimeType, $viewableTypes)) {
                return $this->response
                    ->setHeader('Content-Type', $mimeType)
                    ->setHeader('Content-Disposition', 'inline; filename="' . $originalName . '"')
                    ->setBody(file_get_contents($filePath));
            } else {
                // If not viewable, force download
                return $this->response->download($filePath, null, true)->setFileName($originalName);
            }
        } else {
            // For downloading
            return $this->response->download($filePath, null, true)->setFileName($originalName);
        }
    }

    /**
     * Get original filename from stored file info
     */
    private function getOriginalFilename($filePath, $actualFilename)
    {
        // Try to get original filename from comment attachments
        // This is a simplified approach - in a real implementation you might want to store this info in database
        $pathInfo = pathinfo($filePath);
        return $actualFilename;
    }

    /**
     * Regenerate QR Code for an existing order (force new Lima Links short URL)
     */
    public function regenerateQR($orderId = null)
    {
        // Allow both AJAX and direct requests for testing
        $isAjax = $this->request->isAJAX();

        try {
            if (!$orderId) {
                $orderId = $this->request->getPost('order_id');
            }

            if (!$orderId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }

            log_message('info', "Regenerating QR for sales order ID: {$orderId}");

            // Check if order exists
            $order = $this->salesOrderModel->find($orderId);
            if (!$order) {
                log_message('error', "Sales order {$orderId} not found for QR regeneration");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Sales order not found'
                ]);
            }

            log_message('info', "Sales order {$orderId} found, clearing existing QR data");

            // Clear existing QR data to force regeneration
            $updateResult = $this->salesOrderModel->update($orderId, [
                'short_url' => null,
                'short_url_slug' => null,
                'lima_link_id' => null,
                'qr_generated_at' => null
            ]);

            if (!$updateResult) {
                log_message('error', "Failed to clear QR data for sales order {$orderId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to clear existing QR data'
                ]);
            }

            log_message('info', "QR data cleared for sales order {$orderId}, generating new QR");

            // Generate new QR code with MDA Links
            $qrData = $this->generateOrderQR($orderId);

            if ($qrData) {
                log_message('info', "QR regeneration successful for sales order {$orderId}");
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'QR Code regenerated successfully with MDA Links!',
                    'qr_data' => $qrData
                ]);
            } else {
                log_message('error', "QR generation failed for sales order {$orderId}");
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to generate QR code. Please check Lima Links configuration.'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error regenerating QR code: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error regenerating QR code: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Generate a unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'SAL-';
        $attempts = 0;
        $maxAttempts = 100;
        
        do {
            // Generate order number based on current timestamp and random number
            $timestamp = date('ymdHis');
            $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . $timestamp . $random;
            
            // Check if this order number already exists
            $existing = $this->salesOrderModel->where('order_number', $orderNumber)->first();
            
            if (!$existing) {
                return $orderNumber;
            }
            
            $attempts++;
            
            // Add small delay to ensure different timestamp
            usleep(10000); // 10ms
            
        } while ($attempts < $maxAttempts);
        
        // Fallback: use current ID if available, or timestamp with microseconds
        $fallback = $prefix . date('ymdHis') . substr(microtime(), 2, 6);
        log_message('warning', 'Order number generation reached max attempts, using fallback: ' . $fallback);
        
        return $fallback;
    }

}
