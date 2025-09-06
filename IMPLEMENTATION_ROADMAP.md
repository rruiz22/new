# 🚀 MDA SYSTEM - IMPLEMENTATION ROADMAP

## 📅 Orden de Implementación Recomendado

### **FASE 1: PULIR MÓDULOS ACTUALES** ✅ (En progreso)
- Optimizar Sales Orders
- Mejorar Service Orders  
- Refinar Car Wash
- Completar Recon Orders

### **FASE 2: LÓGICA DE USUARIOS** ⏳ (Próximo)
- Corregir relaciones user/client/dealer
- Unificar nomenclatura contact/salesperson
- Implementar multi-dealer para staff

### **FASE 3: SISTEMA DE PERMISOS** 📋 (Futuro)
- Implementar scopes universales
- Configurar políticas por módulo
- Sistema de comunicación con restricciones

### **FASE 4: APPS DE VELZON** 📱 (Futuro)
- Integrar aplicaciones empresariales
- Mailbox, Calendar, Tasks, etc.

---

# 📋 PLAN DETALLADO DE IMPLEMENTACIÓN

## 🔐 **PARTE 1: SISTEMA UNIVERSAL DE PERMISOS Y SCOPES**

### **1.1 ARQUITECTURA DE BASE DE DATOS**

#### **Tabla: user_client_assignments**
```sql
-- Permite que staff trabaje con múltiples dealers
CREATE TABLE user_client_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    client_id INT NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    can_create_orders BOOLEAN DEFAULT TRUE,
    can_edit_orders BOOLEAN DEFAULT TRUE,
    can_delete_orders BOOLEAN DEFAULT FALSE,
    assigned_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_assignment (user_id, client_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);
```

#### **Tabla: permission_scopes**
```sql
-- Define los alcances disponibles en el sistema
CREATE TABLE permission_scopes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scope_name VARCHAR(50) UNIQUE,
    description TEXT,
    applies_to_types VARCHAR(100), -- 'client,staff,admin'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos iniciales
INSERT INTO permission_scopes (scope_name, description, applies_to_types) VALUES
('own_only', 'Solo registros propios (creados por o asignados a)', 'client,staff'),
('same_dealer', 'Solo registros del mismo dealer', 'client'),
('assigned_dealers', 'Registros de dealers asignados', 'staff'),
('all_dealers', 'Todos los dealers del sistema', 'admin,superadmin'),
('departmental', 'Solo su departamento', 'staff'),
('cross_dealer', 'Entre diferentes dealers', 'staff,admin');
```

#### **Tabla: module_access_policies**
```sql
-- Políticas de acceso por módulo y tipo de usuario
CREATE TABLE module_access_policies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_name VARCHAR(50),
    user_type ENUM('client', 'staff', 'admin', 'manager', 'superadmin'),
    
    -- Scopes para cada acción CRUD
    view_scope VARCHAR(50),
    create_scope VARCHAR(50),
    edit_scope VARCHAR(50),
    delete_scope VARCHAR(50),
    
    -- Permisos adicionales
    can_see_sensitive_data BOOLEAN DEFAULT FALSE,
    can_export_data BOOLEAN DEFAULT FALSE,
    can_bulk_operate BOOLEAN DEFAULT FALSE,
    
    -- Restricciones en JSON
    restrictions JSON,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_module_policy (module_name, user_type)
);

-- Configuración inicial para Sales Orders
INSERT INTO module_access_policies 
(module_name, user_type, view_scope, create_scope, edit_scope, delete_scope, can_see_sensitive_data) 
VALUES
('sales_orders', 'client', 'same_dealer', 'same_dealer', 'own_only', NULL, FALSE),
('sales_orders', 'staff', 'assigned_dealers', 'assigned_dealers', 'assigned_dealers', 'own_only', TRUE),
('sales_orders', 'admin', 'all_dealers', 'all_dealers', 'all_dealers', 'all_dealers', TRUE);
```

#### **Tabla: order_status_permissions**
```sql
-- Control de permisos por estado de orden
CREATE TABLE order_status_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_name VARCHAR(50),
    user_type ENUM('staff', 'client', 'admin', 'manager'),
    status VARCHAR(50),
    can_edit BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    can_change_status BOOLEAN DEFAULT FALSE,
    notes TEXT,
    UNIQUE KEY unique_status_perm (module_name, user_type, status)
);

-- Reglas iniciales
INSERT INTO order_status_permissions (module_name, user_type, status, can_edit, can_delete, can_change_status, notes) VALUES
-- Clients no pueden editar órdenes completadas/canceladas
('sales_orders', 'client', 'completed', FALSE, FALSE, FALSE, 'Locked for clients'),
('sales_orders', 'client', 'cancelled', FALSE, FALSE, FALSE, 'Locked for clients'),
('sales_orders', 'client', 'pending', TRUE, FALSE, TRUE, 'Can edit and update'),
('sales_orders', 'client', 'in_progress', TRUE, FALSE, FALSE, 'Can edit but not change status'),
-- Staff tiene más flexibilidad
('sales_orders', 'staff', 'completed', FALSE, FALSE, TRUE, 'Can reopen if needed'),
('sales_orders', 'staff', 'cancelled', FALSE, FALSE, TRUE, 'Can reactivate'),
-- Admins sin restricciones
('sales_orders', 'admin', 'completed', TRUE, TRUE, TRUE, 'Full access'),
('sales_orders', 'admin', 'cancelled', TRUE, TRUE, TRUE, 'Full access');
```

#### **Tabla: communication_policies**
```sql
-- Define quién puede comunicarse con quién
CREATE TABLE communication_policies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_user_type ENUM('client', 'staff', 'admin', 'manager'),
    to_user_type ENUM('client', 'staff', 'admin', 'manager'),
    communication_scope VARCHAR(50),
    
    -- Tipos de comunicación permitidos
    can_message BOOLEAN DEFAULT FALSE,
    can_email BOOLEAN DEFAULT FALSE,
    can_call BOOLEAN DEFAULT FALSE,
    can_mention BOOLEAN DEFAULT FALSE,
    can_assign_tasks BOOLEAN DEFAULT FALSE,
    
    -- Restricciones
    requires_approval BOOLEAN DEFAULT FALSE,
    max_daily_messages INT DEFAULT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_comm_policy (from_user_type, to_user_type)
);

-- Políticas de comunicación
INSERT INTO communication_policies 
(from_user_type, to_user_type, communication_scope, can_message, can_email) VALUES
('client', 'client', 'same_dealer', TRUE, TRUE),
('client', 'staff', 'same_dealer', TRUE, TRUE),
('staff', 'client', 'assigned_dealers', TRUE, TRUE),
('staff', 'staff', 'cross_dealer', TRUE, TRUE),
('admin', 'client', 'all', TRUE, TRUE),
('admin', 'staff', 'all', TRUE, TRUE);
```

### **1.2 SERVICIO UNIVERSAL DE PERMISOS**

#### **Archivo: app/Services/UniversalPermissionService.php**

```php
<?php

namespace App\Services;

use App\Models\UserModel;
use CodeIgniter\Database\BaseConnection;

class UniversalPermissionService
{
    protected $db;
    protected $userModel;
    
    /**
     * Configuración de módulos y sus características
     */
    private $moduleConfigs = [
        'sales_orders' => [
            'table' => 'sales_orders',
            'scopes' => [
                'client' => 'same_dealer',
                'staff' => 'assigned_dealers',
                'admin' => 'all_dealers'
            ],
            'filters' => ['client_id', 'assigned_to', 'created_by'],
            'sensitive_fields' => ['cost', 'profit', 'internal_notes'],
            'has_followers' => true,
            'status_field' => 'status'
        ],
        'service_orders' => [
            'table' => 'service_orders',
            'scopes' => [
                'client' => 'same_dealer',
                'staff' => 'assigned_dealers',
                'technician' => 'own_only',
                'admin' => 'all_dealers'
            ],
            'filters' => ['client_id', 'technician_id', 'created_by'],
            'sensitive_fields' => ['labor_cost', 'parts_cost'],
            'has_followers' => true,
            'status_field' => 'status'
        ],
        'car_wash_orders' => [
            'table' => 'car_wash_orders',
            'scopes' => [
                'client' => 'same_dealer',
                'staff' => 'departmental',
                'admin' => 'all_dealers'
            ],
            'filters' => ['client_id', 'department_id'],
            'has_followers' => false,
            'status_field' => 'status'
        ],
        'recon_orders' => [
            'table' => 'recon_orders',
            'scopes' => [
                'client' => 'same_dealer',
                'staff' => 'assigned_dealers',
                'admin' => 'all_dealers'
            ],
            'filters' => ['client_id', 'assigned_to'],
            'has_followers' => true,
            'requires' => ['photos_before_complete'],
            'status_field' => 'status'
        ]
    ];
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
    }
    
    /**
     * Aplica el scope correspondiente a un query según el usuario y módulo
     */
    public function applyScopeToQuery($query, string $module, $user, string $action = 'view')
    {
        // Obtener configuración del módulo
        $config = $this->moduleConfigs[$module] ?? null;
        if (!$config) {
            throw new \Exception("Module {$module} not configured");
        }
        
        // SuperAdmin ve todo
        if ($this->isUserSuperAdmin($user)) {
            return $query;
        }
        
        // Determinar el scope del usuario
        $userScope = $this->getUserScope($user, $module, $action);
        
        // Aplicar el scope al query
        switch ($userScope) {
            case 'own_only':
                $query->groupStart()
                      ->where('created_by', $user->id)
                      ->orWhere('assigned_to', $user->id)
                      ->groupEnd();
                break;
                
            case 'same_dealer':
                $query->where('client_id', $user->client_id);
                break;
                
            case 'assigned_dealers':
                $dealerIds = $this->getUserAssignedDealers($user->id);
                if (empty($dealerIds)) {
                    // Si no tiene dealers asignados, usa su dealer principal
                    $dealerIds = [$user->client_id];
                }
                $query->whereIn('client_id', $dealerIds);
                break;
                
            case 'departmental':
                $query->where('department_id', $user->department_id);
                break;
                
            case 'all_dealers':
                // Sin restricciones
                break;
                
            default:
                // Por defecto, más restrictivo
                $query->where('created_by', $user->id);
        }
        
        // Aplicar filtros adicionales según el módulo
        $this->applyModuleSpecificFilters($query, $module, $user);
        
        return $query;
    }
    
    /**
     * Valida si un usuario puede realizar una acción sobre un registro
     */
    public function canUserPerformAction($user, string $module, string $action, $record = null): array
    {
        // SuperAdmin puede todo
        if ($this->isUserSuperAdmin($user)) {
            return ['allowed' => true];
        }
        
        // Verificar permisos básicos del módulo
        if (!$this->hasModuleAccess($user, $module)) {
            return ['allowed' => false, 'reason' => 'No module access'];
        }
        
        // Si es una acción sobre un registro específico
        if ($record) {
            // Verificar scope
            if (!$this->isInUserScope($user, $module, $record)) {
                return ['allowed' => false, 'reason' => 'Out of scope'];
            }
            
            // Verificar restricciones por estado
            if (in_array($action, ['edit', 'delete'])) {
                $statusCheck = $this->canModifyInCurrentStatus($user, $module, $record->status ?? null);
                if (!$statusCheck['allowed']) {
                    return $statusCheck;
                }
            }
            
            // Verificar si es follower (si aplica)
            if ($this->moduleConfigs[$module]['has_followers'] ?? false) {
                if ($this->isUserFollower($user->id, $record->id, $module)) {
                    // Followers tienen permisos especiales
                    if (in_array($action, ['view', 'comment'])) {
                        return ['allowed' => true];
                    }
                }
            }
        }
        
        return ['allowed' => true];
    }
    
    /**
     * Obtiene los usuarios disponibles para comunicación
     */
    public function getAvailableUsersForCommunication($user)
    {
        $query = $this->db->table('users');
        
        if ($user->user_type == 'client') {
            // Clients solo ven usuarios de su mismo dealer
            $query->where('client_id', $user->client_id)
                  ->where('deleted', 0)
                  ->where('active', 1);
                  
        } elseif ($user->user_type == 'staff') {
            // Staff ve usuarios de dealers asignados + otros staff
            $dealerIds = $this->getUserAssignedDealers($user->id);
            
            $query->groupStart()
                      ->whereIn('client_id', $dealerIds)
                      ->orWhere('user_type', 'staff')
                  ->groupEnd()
                  ->where('deleted', 0)
                  ->where('active', 1);
                  
        } else {
            // Admin ve todos
            $query->where('deleted', 0)
                  ->where('active', 1);
        }
        
        return $query->get()->getResultArray();
    }
    
    /**
     * Valida si dos usuarios pueden comunicarse
     */
    public function canUserMessageUser($fromUser, $toUser): bool
    {
        // Obtener política de comunicación
        $policy = $this->db->table('communication_policies')
            ->where('from_user_type', $fromUser->user_type)
            ->where('to_user_type', $toUser->user_type)
            ->get()
            ->getRowArray();
        
        if (!$policy || !$policy['can_message']) {
            return false;
        }
        
        // Verificar scope de comunicación
        switch ($policy['communication_scope']) {
            case 'same_dealer':
                return $fromUser->client_id == $toUser->client_id;
                
            case 'assigned_dealers':
                $assignedDealers = $this->getUserAssignedDealers($fromUser->id);
                return in_array($toUser->client_id, $assignedDealers);
                
            case 'cross_dealer':
                // Staff puede mensajear entre dealers
                return $fromUser->user_type == 'staff' || $fromUser->user_type == 'admin';
                
            case 'all':
                return true;
                
            default:
                return false;
        }
    }
    
    /**
     * Obtiene los dealers asignados a un usuario
     */
    private function getUserAssignedDealers(int $userId): array
    {
        // Primero buscar en user_client_assignments
        $assignments = $this->db->table('user_client_assignments')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
        
        if (!empty($assignments)) {
            return array_column($assignments, 'client_id');
        }
        
        // Si no tiene asignaciones, usar su dealer principal
        $user = $this->userModel->find($userId);
        if ($user && $user['client_id']) {
            return [$user['client_id']];
        }
        
        return [];
    }
    
    /**
     * Verifica si un usuario es SuperAdmin
     */
    private function isUserSuperAdmin($user): bool
    {
        // Verificar en auth_groups_users
        $isSuperAdmin = $this->db->table('auth_groups_users')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where('auth_groups_users.user_id', $user->id)
            ->where('auth_groups.name', 'superadmin')
            ->countAllResults() > 0;
            
        return $isSuperAdmin;
    }
    
    /**
     * Obtiene el scope del usuario para un módulo
     */
    private function getUserScope($user, string $module, string $action): string
    {
        // Buscar política específica
        $policy = $this->db->table('module_access_policies')
            ->where('module_name', $module)
            ->where('user_type', $user->user_type)
            ->get()
            ->getRowArray();
        
        if ($policy) {
            $scopeField = $action . '_scope';
            if (isset($policy[$scopeField])) {
                return $policy[$scopeField];
            }
        }
        
        // Usar configuración por defecto del módulo
        $config = $this->moduleConfigs[$module];
        return $config['scopes'][$user->user_type] ?? 'own_only';
    }
    
    /**
     * Verifica si un registro está en el scope del usuario
     */
    private function isInUserScope($user, string $module, $record): bool
    {
        $scope = $this->getUserScope($user, $module, 'view');
        
        switch ($scope) {
            case 'own_only':
                return $record->created_by == $user->id || $record->assigned_to == $user->id;
                
            case 'same_dealer':
                return $record->client_id == $user->client_id;
                
            case 'assigned_dealers':
                $dealerIds = $this->getUserAssignedDealers($user->id);
                return in_array($record->client_id, $dealerIds);
                
            case 'all_dealers':
                return true;
                
            default:
                return false;
        }
    }
    
    /**
     * Verifica si se puede modificar un registro en su estado actual
     */
    private function canModifyInCurrentStatus($user, string $module, ?string $status): array
    {
        if (!$status) {
            return ['allowed' => true];
        }
        
        // Buscar restricción por estado
        $permission = $this->db->table('order_status_permissions')
            ->where('module_name', $module)
            ->where('user_type', $user->user_type)
            ->where('status', $status)
            ->get()
            ->getRowArray();
        
        if (!$permission) {
            // Si no hay configuración específica, permitir
            return ['allowed' => true];
        }
        
        if (!$permission['can_edit']) {
            return [
                'allowed' => false, 
                'reason' => "Cannot edit orders in {$status} status"
            ];
        }
        
        return ['allowed' => true];
    }
    
    /**
     * Verifica si un usuario es follower de un registro
     */
    private function isUserFollower(int $userId, int $recordId, string $module): bool
    {
        $followerTable = $this->getFollowerTableName($module);
        if (!$followerTable) {
            return false;
        }
        
        return $this->db->table($followerTable)
            ->where('user_id', $userId)
            ->where('order_id', $recordId)
            ->where('status', 'active')
            ->countAllResults() > 0;
    }
    
    /**
     * Obtiene el nombre de la tabla de followers para un módulo
     */
    private function getFollowerTableName(string $module): ?string
    {
        $tables = [
            'sales_orders' => 'sales_order_followers',
            'service_orders' => 'service_order_followers',
            'recon_orders' => 'recon_followers'
        ];
        
        return $tables[$module] ?? null;
    }
    
    /**
     * Aplica filtros específicos del módulo
     */
    private function applyModuleSpecificFilters($query, string $module, $user)
    {
        // Aquí se pueden agregar filtros específicos por módulo
        // Por ejemplo, ocultar órdenes en estado 'draft' para ciertos usuarios
        
        if ($module == 'sales_orders' && $user->user_type == 'client') {
            // Los clients no ven órdenes en draft que no sean suyas
            $query->where(function($q) use ($user) {
                $q->where('status !=', 'draft')
                  ->orWhere('created_by', $user->id);
            });
        }
        
        return $query;
    }
}
```

### **1.3 IMPLEMENTACIÓN EN CONTROLADORES**

#### **Ejemplo: SalesOrdersController.php actualizado**

```php
<?php

namespace App\Modules\SalesOrders\Controllers;

use App\Controllers\BaseController;
use App\Services\UniversalPermissionService;

class SalesOrdersController extends BaseController
{
    protected $model;
    protected $permissionService;
    protected $moduleName = 'sales_orders';
    
    public function __construct()
    {
        $this->model = model('App\Modules\SalesOrders\Models\SalesOrderModel');
        $this->permissionService = new UniversalPermissionService();
    }
    
    public function index()
    {
        $user = auth()->user();
        
        // Aplicar scope automáticamente
        $query = $this->model->select('*');
        $query = $this->permissionService->applyScopeToQuery($query, $this->moduleName, $user);
        
        $orders = $query->findAll();
        
        return view('App\Modules\SalesOrders\Views\sales_orders\index', [
            'orders' => $orders
        ]);
    }
    
    public function create()
    {
        $user = auth()->user();
        
        // Verificar permiso de creación
        $permission = $this->permissionService->canUserPerformAction($user, $this->moduleName, 'create');
        if (!$permission['allowed']) {
            return redirect()->back()->with('error', $permission['reason'] ?? 'No permission to create orders');
        }
        
        // Obtener dealers disponibles según permisos
        $availableDealers = $this->getAvailableDealers($user);
        
        // Obtener contactos disponibles
        $availableContacts = $this->permissionService->getAvailableUsersForCommunication($user);
        
        return view('App\Modules\SalesOrders\Views\sales_orders\create', [
            'dealers' => $availableDealers,
            'contacts' => $availableContacts
        ]);
    }
    
    public function edit($id)
    {
        $user = auth()->user();
        $order = $this->model->find($id);
        
        if (!$order) {
            return redirect()->to('/sales_orders')->with('error', 'Order not found');
        }
        
        // Verificar permiso de edición
        $permission = $this->permissionService->canUserPerformAction($user, $this->moduleName, 'edit', $order);
        if (!$permission['allowed']) {
            return redirect()->back()->with('error', $permission['reason'] ?? 'No permission to edit this order');
        }
        
        return view('App\Modules\SalesOrders\Views\sales_orders\edit', [
            'order' => $order
        ]);
    }
    
    private function getAvailableDealers($user)
    {
        // SuperAdmin ve todos los dealers
        if ($this->permissionService->isUserSuperAdmin($user)) {
            return model('App\Models\ClientModel')->findAll();
        }
        
        // Staff ve dealers asignados
        if ($user->user_type == 'staff') {
            $dealerIds = $this->permissionService->getUserAssignedDealers($user->id);
            if (!empty($dealerIds)) {
                return model('App\Models\ClientModel')->whereIn('id', $dealerIds)->findAll();
            }
        }
        
        // Por defecto, solo su dealer
        return model('App\Models\ClientModel')->where('id', $user->client_id)->findAll();
    }
}
```

---

## 📱 **PARTE 2: IMPLEMENTACIÓN DE APLICACIONES VELZON**

### **2.1 MAILBOX - Sistema de Email Interno**

#### **Base de Datos**

```sql
CREATE TABLE mail_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_user_id INT NOT NULL,
    to_user_ids JSON,
    cc_user_ids JSON,
    bcc_user_ids JSON,
    subject VARCHAR(255),
    body TEXT,
    body_html TEXT,
    attachments JSON,
    folder ENUM('inbox','sent','draft','trash','archive') DEFAULT 'inbox',
    labels JSON,
    priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
    is_read BOOLEAN DEFAULT FALSE,
    is_starred BOOLEAN DEFAULT FALSE,
    is_important BOOLEAN DEFAULT FALSE,
    dealer_id INT,
    thread_id INT,
    reply_to_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (from_user_id) REFERENCES users(id),
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    INDEX idx_folder (folder),
    INDEX idx_dealer (dealer_id),
    INDEX idx_thread (thread_id)
);

CREATE TABLE mail_folders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100),
    icon VARCHAR(50),
    color VARCHAR(7),
    parent_id INT NULL,
    position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### **Controller: MailboxController.php**

```php
<?php

namespace App\Controllers;

use App\Services\UniversalPermissionService;

class MailboxController extends BaseController
{
    protected $permissionService;
    
    public function __construct()
    {
        $this->permissionService = new UniversalPermissionService();
    }
    
    public function index()
    {
        $user = auth()->user();
        $folder = $this->request->getGet('folder') ?? 'inbox';
        
        // Obtener mensajes según scope
        $messages = $this->getMessages($user, $folder);
        
        // Obtener usuarios disponibles para enviar
        $availableUsers = $this->permissionService->getAvailableUsersForCommunication($user);
        
        return view('mailbox/index', [
            'messages' => $messages,
            'folder' => $folder,
            'availableUsers' => $availableUsers,
            'unreadCount' => $this->getUnreadCount($user)
        ]);
    }
    
    public function compose()
    {
        $user = auth()->user();
        
        // Obtener usuarios disponibles según permisos
        $availableUsers = $this->permissionService->getAvailableUsersForCommunication($user);
        
        return view('mailbox/compose', [
            'availableUsers' => $availableUsers,
            'replyTo' => null
        ]);
    }
    
    public function send()
    {
        $user = auth()->user();
        $data = $this->request->getPost();
        
        // Validar destinatarios
        $toUsers = json_decode($data['to_users'], true);
        foreach ($toUsers as $toUserId) {
            $toUser = model('UserModel')->find($toUserId);
            if (!$this->permissionService->canUserMessageUser($user, $toUser)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "No puedes enviar mensajes a {$toUser->username}"
                ]);
            }
        }
        
        // Crear mensaje
        $messageId = model('MailMessageModel')->insert([
            'from_user_id' => $user->id,
            'to_user_ids' => $data['to_users'],
            'cc_user_ids' => $data['cc_users'] ?? null,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'body_html' => $data['body_html'] ?? null,
            'dealer_id' => $user->client_id,
            'folder' => 'sent'
        ]);
        
        // Crear copia en inbox de destinatarios
        foreach ($toUsers as $toUserId) {
            model('MailMessageModel')->insert([
                'from_user_id' => $user->id,
                'to_user_ids' => json_encode([$toUserId]),
                'subject' => $data['subject'],
                'body' => $data['body'],
                'dealer_id' => $user->client_id,
                'folder' => 'inbox'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Mensaje enviado correctamente'
        ]);
    }
    
    private function getMessages($user, $folder)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('mail_messages');
        
        // Aplicar filtros según folder
        if ($folder == 'inbox') {
            $builder->like('to_user_ids', '"'.$user->id.'"');
        } elseif ($folder == 'sent') {
            $builder->where('from_user_id', $user->id);
        }
        
        // Aplicar scope de dealer para clients
        if ($user->user_type == 'client') {
            $builder->where('dealer_id', $user->client_id);
        }
        
        $builder->where('folder', $folder)
                ->where('deleted_at', null)
                ->orderBy('created_at', 'DESC');
                
        return $builder->get()->getResultArray();
    }
}
```

### **2.2 CALENDAR - Sistema de Calendario**

#### **Base de Datos**

```sql
CREATE TABLE calendar_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    
    -- Tipo y referencias
    event_type ENUM('order','meeting','reminder','maintenance','personal'),
    sales_order_id INT NULL,
    service_order_id INT NULL,
    car_wash_order_id INT NULL,
    recon_order_id INT NULL,
    
    -- Participantes y scope
    dealer_id INT,
    created_by INT NOT NULL,
    assigned_to JSON,
    attendees JSON,
    
    -- Configuración
    color VARCHAR(7) DEFAULT '#3788d8',
    is_all_day BOOLEAN DEFAULT FALSE,
    is_private BOOLEAN DEFAULT FALSE,
    reminder_minutes INT,
    recurrence_rule VARCHAR(255),
    
    -- Estado
    status ENUM('tentative','confirmed','cancelled') DEFAULT 'confirmed',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_date (start_datetime, end_datetime),
    INDEX idx_dealer (dealer_id)
);

CREATE TABLE calendar_reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    remind_at DATETIME NOT NULL,
    is_sent BOOLEAN DEFAULT FALSE,
    sent_at DATETIME NULL,
    FOREIGN KEY (event_id) REFERENCES calendar_events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### **2.3 TASKS/KANBAN - Gestión de Tareas**

#### **Base de Datos**

```sql
CREATE TABLE kanban_boards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    dealer_id INT,
    columns JSON NOT NULL,
    color_scheme JSON,
    is_default BOOLEAN DEFAULT FALSE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE kanban_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    board_id INT NOT NULL,
    column_name VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    
    -- Referencias
    related_order_type VARCHAR(50),
    related_order_id INT,
    
    -- Asignación y prioridad
    assigned_to JSON,
    priority ENUM('low','normal','high','urgent') DEFAULT 'normal',
    due_date DATE,
    
    -- Contenido
    checklist JSON,
    attachments JSON,
    tags JSON,
    
    -- Estado y posición
    is_completed BOOLEAN DEFAULT FALSE,
    completed_at DATETIME NULL,
    position INT DEFAULT 0,
    
    -- Metadata
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (board_id) REFERENCES kanban_boards(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_board_column (board_id, column_name)
);

CREATE TABLE kanban_task_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    attachments JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES kanban_tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### **2.4 SUPPORT TICKETS - Sistema de Soporte**

#### **Base de Datos**

```sql
CREATE TABLE support_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(20) UNIQUE NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    
    -- Categorización
    category ENUM('technical','billing','general','complaint','feature_request'),
    priority ENUM('low','normal','high','critical') DEFAULT 'normal',
    status ENUM('open','pending','in_progress','resolved','closed') DEFAULT 'open',
    
    -- Referencias
    dealer_id INT,
    related_order_type VARCHAR(50),
    related_order_id INT,
    
    -- Personas involucradas
    created_by INT NOT NULL,
    assigned_to INT,
    
    -- SLA y tiempos
    due_date DATETIME,
    first_response_at DATETIME,
    resolved_at DATETIME,
    closed_at DATETIME,
    
    -- Satisfacción
    satisfaction_rating INT,
    satisfaction_comment TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    INDEX idx_status (status),
    INDEX idx_assigned (assigned_to)
);

CREATE TABLE ticket_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    attachments JSON,
    is_internal BOOLEAN DEFAULT FALSE,
    is_solution BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### **2.5 FILE MANAGER - Gestor de Archivos**

#### **Base de Datos**

```sql
CREATE TABLE file_folders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT NULL,
    dealer_id INT,
    path VARCHAR(500),
    
    -- Permisos
    is_public BOOLEAN DEFAULT FALSE,
    is_system BOOLEAN DEFAULT FALSE,
    allowed_users JSON,
    allowed_groups JSON,
    
    -- Metadata
    icon VARCHAR(50),
    color VARCHAR(7),
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (parent_id) REFERENCES file_folders(id) ON DELETE CASCADE,
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_path (path)
);

CREATE TABLE file_uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folder_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255),
    file_path VARCHAR(500) NOT NULL,
    file_size BIGINT,
    mime_type VARCHAR(100),
    extension VARCHAR(10),
    
    -- Referencias
    related_to_type VARCHAR(50),
    related_to_id INT,
    
    -- Metadata
    dealer_id INT,
    uploaded_by INT NOT NULL,
    downloads INT DEFAULT 0,
    last_downloaded_at DATETIME,
    
    -- Versioning
    version INT DEFAULT 1,
    previous_version_id INT NULL,
    
    -- Sharing
    is_shared BOOLEAN DEFAULT FALSE,
    share_token VARCHAR(100),
    share_expires_at DATETIME,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (folder_id) REFERENCES file_folders(id) ON DELETE CASCADE,
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    FOREIGN KEY (previous_version_id) REFERENCES file_uploads(id),
    INDEX idx_folder (folder_id),
    INDEX idx_related (related_to_type, related_to_id)
);
```

### **2.6 INVOICES - Sistema de Facturación**

#### **Base de Datos**

```sql
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    
    -- Referencias a órdenes
    dealer_id INT NOT NULL,
    sales_order_id INT,
    service_order_id INT,
    car_wash_order_id INT,
    recon_order_id INT,
    
    -- Información del cliente
    bill_to JSON NOT NULL,
    ship_to JSON,
    
    -- Items y servicios
    line_items JSON NOT NULL,
    
    -- Montos
    subtotal DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    tax_rate DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    shipping_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    
    -- Pagos
    amount_paid DECIMAL(10,2) DEFAULT 0,
    balance_due DECIMAL(10,2) NOT NULL,
    
    -- Estado y fechas
    status ENUM('draft','sent','viewed','paid','partial','overdue','cancelled') DEFAULT 'draft',
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    paid_date DATE,
    
    -- Configuración
    template VARCHAR(50) DEFAULT 'default',
    currency VARCHAR(3) DEFAULT 'USD',
    notes TEXT,
    terms TEXT,
    
    -- Metadata
    created_by INT NOT NULL,
    sent_to_email VARCHAR(255),
    sent_at DATETIME,
    viewed_at DATETIME,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (dealer_id) REFERENCES clients(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_status (status),
    INDEX idx_dealer (dealer_id),
    INDEX idx_due_date (due_date)
);

CREATE TABLE invoice_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash','check','credit_card','bank_transfer','other'),
    reference_number VARCHAR(100),
    notes TEXT,
    received_by INT NOT NULL,
    received_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (received_by) REFERENCES users(id)
);
```

---

## 🔄 **INTEGRACIÓN ENTRE MÓDULOS**

### **Flujo de Integración**

```php
// Cuando se crea una orden, automáticamente:

1. CALENDAR: Se crea evento en calendario
2. TASKS: Se generan tareas en Kanban
3. MAILBOX: Se envía notificación por email interno
4. FILES: Se crea carpeta para archivos de la orden

// Ejemplo en SalesOrderModel.php:

public function createOrder($data)
{
    // Crear orden
    $orderId = $this->insert($data);
    
    // Crear evento en calendario
    $calendarService = new CalendarService();
    $calendarService->createEventFromOrder('sales', $orderId, $data);
    
    // Crear tareas en Kanban
    $taskService = new TaskService();
    $taskService->createTasksFromOrder('sales', $orderId);
    
    // Enviar notificación por email
    $mailService = new MailService();
    $mailService->sendOrderNotification('sales', $orderId);
    
    // Crear carpeta de archivos
    $fileService = new FileService();
    $fileService->createOrderFolder('sales', $orderId);
    
    return $orderId;
}
```

---

## 📱 **INTERFACES DE USUARIO**

### **Dashboard Unificado**

```php
// app/Views/dashboard/widgets.php

<!-- Widget de Resumen -->
<div class="row">
    <!-- Email Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Unread Emails</p>
                        <h4 class="fs-22 fw-semibold mb-0">
                            <span class="counter-value" data-target="<?= $unreadEmails ?>">0</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded fs-3">
                            <i class="bx bx-envelope text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendar Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Today's Events</p>
                        <h4 class="fs-22 fw-semibold mb-0">
                            <span class="counter-value" data-target="<?= $todayEvents ?>">0</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-calendar text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tasks Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Pending Tasks</p>
                        <h4 class="fs-22 fw-semibold mb-0">
                            <span class="counter-value" data-target="<?= $pendingTasks ?>">0</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-warning rounded fs-3">
                            <i class="bx bx-task text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tickets Widget -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-uppercase fw-medium text-muted mb-0">Open Tickets</p>
                        <h4 class="fs-22 fw-semibold mb-0">
                            <span class="counter-value" data-target="<?= $openTickets ?>">0</span>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-danger rounded fs-3">
                            <i class="bx bx-support text-danger"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 🚀 **ORDEN DE IMPLEMENTACIÓN RECOMENDADO**

### **Fase 1: Pulir Módulos Actuales (ACTUAL)**
✅ Optimizar Sales Orders
⏳ Mejorar Service Orders
⏳ Refinar Car Wash
⏳ Completar Recon Orders

### **Fase 2: Lógica de Usuarios**
⏳ Corregir relaciones user/client/dealer
⏳ Unificar nomenclatura contact/salesperson
⏳ Implementar tabla user_client_assignments

### **Fase 3: Sistema de Permisos**
⏳ Crear UniversalPermissionService
⏳ Implementar scopes y políticas
⏳ Actualizar todos los controladores

### **Fase 4: Apps de Velzon - Orden Sugerido**
1. **Mailbox** - Comunicación interna esencial
2. **Calendar** - Programación y coordinación
3. **Tasks/Kanban** - Gestión de trabajo
4. **Support Tickets** - Soporte al cliente
5. **File Manager** - Gestión documental
6. **Invoices** - Facturación

---

## 📝 **NOTAS IMPORTANTES**

1. **Mantener Compatibilidad**: Todos los cambios deben ser retrocompatibles
2. **Testing Incremental**: Probar cada fase antes de continuar
3. **Documentación**: Actualizar CLAUDE.md con cada cambio
4. **Backups**: Hacer respaldo antes de cada fase
5. **Comunicación**: Informar a usuarios de cambios importantes

---

*Documento creado: <?= date('Y-m-d H:i:s') ?>*
*Última actualización: Al guardar este archivo*
*Versión: 1.0.0*