# Componentes Reutilizables MDA

Este directorio contiene componentes reutilizables extraídos de la vista de Sales Orders para ser utilizados en todos los módulos del sistema (Sales Orders, Service Orders, CarWash, Recon Orders).

## 📋 Componentes Disponibles

### 1. **Order View Layout** (`order_view_layout.php`)
Layout completo para vistas de órdenes con estructura consistente y todos los componentes integrados.

### 2. **Internal Notes** (`internal_notes.php`)
Sistema de notas internas con menciones, archivos adjuntos y tabs organizados.

### 3. **QR Code** (`qr_code.php`)
Generación y visualización de códigos QR con modal y enlaces cortos.

### 4. **Quick Actions** (`quick_actions.php`)
Panel de acciones rápidas con botones para SMS, Email, Print, Notificaciones, etc.

### 5. **Comments** (`comments.php`)
Sistema de comentarios públicos con archivos adjuntos y menciones.

### 6. **Vehicle Information Top Bar** (`vehicle_info_topbar.php`)
Barra superior con información clave de la orden organizada en 6 columnas.

---

## 🚀 Guía de Uso

### Cómo incluir un componente en una vista:

```php
<?= $this->include('components/component_name', [
    'parameter1' => $value1,
    'parameter2' => $value2
]) ?>
```

---

## 📖 Documentación Detallada por Componente

## 1. Order View Layout

### Uso Básico (Layout Completo):
```php
<?= $this->include('components/order_view_layout', [
    'order' => $order,
    'module_type' => 'sales_orders',
    'title' => 'Sales Order #' . $order['id']
]) ?>
```

### Configuración Completa:
```php
<?= $this->include('components/order_view_layout', [
    'order' => $order,                                // REQUERIDO: Array orden
    'module_type' => 'recon_orders',                  // REQUERIDO: Tipo módulo
    'title' => 'Recon Order Details',                 // REQUERIDO: Título página
    'qr_data' => $qr_data,                           // Datos QR (opcional)
    'show_qr_in_topbar' => false,                    // QR en topbar vs sidebar
    'show_order_details' => true,                    // Mostrar card detalles
    'show_schedule_info' => true,                    // Mostrar info horario
    'additional_sidebar_content' => $custom_sidebar,  // HTML adicional sidebar
    'additional_main_content' => $custom_main,        // HTML adicional main
    'custom_breadcrumbs' => [                        // Breadcrumbs personalizados
        ['text' => 'Dashboard', 'url' => '/dashboard'],
        ['text' => 'Recon Orders', 'url' => '/recon_orders'],
        ['text' => 'Order Details']
    ],
    'custom_styles' => '.custom-class { color: red; }', // CSS adicional
    'custom_scripts' => 'console.log("Custom JS");'     // JS adicional
]) ?>
```

### Módulos Compatibles:
- `'sales_orders'` - Órdenes de venta
- `'service_orders'` - Órdenes de servicio
- `'car_wash_orders'` - Órdenes de lavado
- `'recon_orders'` - Órdenes de reconocimiento

### Características del Layout:
- ✅ **Estructura Completa:** Top bar + contenido principal + sidebar
- ✅ **Componentes Integrados:** Todos los componentes incluidos automáticamente
- ✅ **Responsive Design:** Optimizado para todas las pantallas
- ✅ **Estadísticas en Tiempo Real:** Contador de comentarios y notas
- ✅ **Breadcrumbs Automáticos:** Generación automática por módulo
- ✅ **Configuración por Módulo:** Colores, iconos y textos específicos

## 2. Internal Notes

### Uso Básico:
```php
<?= $this->include('components/internal_notes', [
    'order_id' => $order['id'],
    'module_type' => 'sales_orders'
]) ?>
```

### Parámetros Completos:
```php
<?= $this->include('components/internal_notes', [
    'order_id' => $order['id'],                    // REQUERIDO
    'module_type' => 'sales_orders',               // REQUERIDO
    'show_mentions_tab' => true,                   // Mostrar tab menciones
    'show_team_activity_tab' => true,              // Mostrar tab actividad
    'max_char_count' => 5000                       // Máximo caracteres
]) ?>
```

### Módulos Compatibles:
- `'sales_orders'`
- `'service_orders'` 
- `'car_wash_orders'`
- `'recon_orders'`

---

## 2. QR Code

### Uso con QR existente:
```php
<?= $this->include('components/qr_code', [
    'order' => $order,
    'qr_data' => $qr_data,
    'module_prefix' => 'SAL'
]) ?>
```

### Uso sin QR (genera botón):
```php
<?= $this->include('components/qr_code', [
    'order' => $order,
    'qr_data' => null,
    'module_prefix' => 'REC',
    'show_sidebar' => true,
    'show_topbar' => false
]) ?>
```

### Parámetros Completos:
```php
<?= $this->include('components/qr_code', [
    'order' => $order,                             // REQUERIDO: Array orden
    'qr_data' => $qr_data,                         // Datos QR (opcional)
    'module_prefix' => 'SAL',                      // REQUERIDO: SAL|SER|CAR|REC
    'show_sidebar' => true,                        // Mostrar card sidebar
    'show_topbar' => false,                        // Mostrar en topbar
    'sidebar_qr_size' => 200                       // Tamaño QR sidebar (px)
]) ?>
```

### Prefijos por Módulo:
- Sales Orders: `'SAL'`
- Service Orders: `'SER'`
- Car Wash Orders: `'CAR'`
- Recon Orders: `'REC'`

---

## 3. Quick Actions

### Uso Básico:
```php
<?= $this->include('components/quick_actions', [
    'order' => $order,
    'module_type' => 'sales_orders'
]) ?>
```

### Configuración Avanzada:
```php
<?= $this->include('components/quick_actions', [
    'order' => $order,                             // REQUERIDO
    'module_type' => 'recon_orders',               // REQUERIDO
    'show_status_update' => true,                  // Selector estado
    'show_sms_action' => true,                     // Botón SMS
    'show_email_action' => true,                   // Botón Email
    'show_print_action' => true,                   // Botón Print
    'show_qr_action' => true,                      // Botón QR
    'show_notification_action' => true,            // Botón Notificación
    'available_statuses' => [                      // Estados personalizados
        'pending' => ['icon' => '⏳', 'label' => 'Pending'],
        'in_progress' => ['icon' => '🔄', 'label' => 'Working'],
        'completed' => ['icon' => '✅', 'label' => 'Done']
    ]
]) ?>
```

### Estados por Defecto:
```php
$default_statuses = [
    'pending' => ['icon' => '⏳', 'label' => 'Pending'],
    'processing' => ['icon' => '⚙️', 'label' => 'Processing'],
    'in_progress' => ['icon' => '🔄', 'label' => 'In Progress'],
    'completed' => ['icon' => '✅', 'label' => 'Completed'],
    'cancelled' => ['icon' => '❌', 'label' => 'Cancelled']
];
```

---

## 4. Comments

### Uso Básico:
```php
<?= $this->include('components/comments', [
    'order_id' => $order['id'],
    'module_type' => 'car_wash_orders'
]) ?>
```

### Configuración Completa:
```php
<?= $this->include('components/comments', [
    'order_id' => $order['id'],                    // REQUERIDO
    'module_type' => 'service_orders',             // REQUERIDO
    'max_height' => 400,                           // Altura lista (px)
    'allow_attachments' => true,                   // Permitir archivos
    'allow_mentions' => true,                      // Permitir @menciones
    'show_refresh_button' => true,                 // Botón refresh
    'accepted_file_types' => '.pdf,.jpg,.png',     // Tipos archivo
    'placeholder_text' => 'Add your comment...'    // Placeholder custom
]) ?>
```

### Tipos de Archivo por Defecto:
```
'.pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.mov,.txt'
```

---

## 5. Vehicle Information Top Bar

### Uso Básico:
```php
<?= $this->include('components/vehicle_info_topbar', [
    'order' => $order,
    'module_type' => 'recon_orders'
]) ?>
```

### Con QR Code en Top Bar:
```php
<?= $this->include('components/vehicle_info_topbar', [
    'order' => $order,
    'module_type' => 'sales_orders',
    'show_qr_code' => true,
    'qr_data' => $qr_data
]) ?>
```

### Con Items Personalizados:
```php
<?= $this->include('components/vehicle_info_topbar', [
    'order' => $order,
    'module_type' => 'car_wash_orders',
    'custom_items' => [
        [
            'icon' => 'droplet',
            'icon_class' => 'text-info',
            'label' => 'Water Used',
            'value' => '25 gallons',
            'sub' => 'Eco-friendly wash'
        ]
    ]
]) ?>
```

### Configuración por Módulo:
```php
$module_config = [
    'sales_orders' => [
        'contact_field' => 'salesperson',
        'module_prefix' => 'SAL',
        'primary_service' => 'service'
    ],
    'service_orders' => [
        'contact_field' => 'technician',
        'module_prefix' => 'SER', 
        'primary_service' => 'repair_type'
    ],
    'car_wash_orders' => [
        'contact_field' => 'assigned_staff',
        'module_prefix' => 'CAR',
        'primary_service' => 'wash_type'
    ],
    'recon_orders' => [
        'contact_field' => 'inspector',
        'module_prefix' => 'REC',
        'primary_service' => 'inspection_type'
    ]
];
```

---

## 🎨 Ejemplos de Implementación

### 🚀 NUEVO: Uso del Layout Completo (Recomendado)

#### Sales Orders View (Simplificado):
```php
<!-- En lugar de todo el HTML manual, solo esto: -->
<?= $this->include('components/order_view_layout', [
    'order' => $order,
    'module_type' => 'sales_orders',
    'title' => 'Sales Order #' . $order['id'],
    'qr_data' => $qr_data
]) ?>
```

#### Service Orders View (Con contenido personalizado):
```php
<?php
$custom_sidebar = '
<div class="card">
    <div class="card-header">
        <h6>Service Tools</h6>
    </div>
    <div class="card-body">
        <button class="btn btn-outline-primary w-100 mb-2">Diagnostics</button>
        <button class="btn btn-outline-info w-100">Parts Catalog</button>
    </div>
</div>
';

$custom_main = '
<div class="card">
    <div class="card-header">
        <h5>Service History</h5>
    </div>
    <div class="card-body">
        <p>Previous service records will be displayed here...</p>
    </div>
</div>
';
?>

<?= $this->include('components/order_view_layout', [
    'order' => $order,
    'module_type' => 'service_orders',
    'title' => 'Service Order - ' . $order['vehicle'],
    'additional_sidebar_content' => $custom_sidebar,
    'additional_main_content' => $custom_main
]) ?>
```

#### Car Wash Orders (QR en Top Bar):
```php
<?= $this->include('components/order_view_layout', [
    'order' => $order,
    'module_type' => 'car_wash_orders', 
    'title' => 'Car Wash Order',
    'qr_data' => $qr_data,
    'show_qr_in_topbar' => true,  // QR en topbar en lugar de sidebar
    'show_schedule_info' => false  // No mostrar horario para lavados
]) ?>
```

---

### 📝 Implementación Manual (Solo si necesitas control total)

#### Sales Orders View
```php
<!-- Top Bar -->
<?= $this->include('components/vehicle_info_topbar', [
    'order' => $order,
    'module_type' => 'sales_orders',
    'show_qr_code' => false
]) ?>

<!-- Main Content Row -->
<div class="row">
    <!-- Left Column -->
    <div class="col-xl-8">
        <!-- Comments -->
        <?= $this->include('components/comments', [
            'order_id' => $order['id'],
            'module_type' => 'sales_orders'
        ]) ?>
        
        <!-- Internal Notes -->
        <?= $this->include('components/internal_notes', [
            'order_id' => $order['id'],
            'module_type' => 'sales_orders'
        ]) ?>
    </div>
    
    <!-- Right Sidebar -->
    <div class="col-xl-4">
        <!-- QR Code -->
        <?= $this->include('components/qr_code', [
            'order' => $order,
            'qr_data' => $qr_data,
            'module_prefix' => 'SAL'
        ]) ?>
        
        <!-- Quick Actions -->
        <?= $this->include('components/quick_actions', [
            'order' => $order,
            'module_type' => 'sales_orders'
        ]) ?>
    </div>
</div>
```

### Service Orders View (Configuración diferente)
```php
<!-- Service Orders con estados personalizados -->
<?= $this->include('components/quick_actions', [
    'order' => $order,
    'module_type' => 'service_orders',
    'available_statuses' => [
        'received' => ['icon' => '📥', 'label' => 'Received'],
        'diagnosing' => ['icon' => '🔍', 'label' => 'Diagnosing'],
        'waiting_parts' => ['icon' => '⏰', 'label' => 'Waiting Parts'],
        'repairing' => ['icon' => '🔧', 'label' => 'Repairing'],
        'testing' => ['icon' => '🧪', 'label' => 'Testing'],
        'completed' => ['icon' => '✅', 'label' => 'Completed']
    ]
]) ?>
```

### Car Wash Orders (Solo comentarios básicos)
```php
<?= $this->include('components/comments', [
    'order_id' => $order['id'],
    'module_type' => 'car_wash_orders',
    'allow_mentions' => false,
    'accepted_file_types' => '.jpg,.jpeg,.png',
    'placeholder_text' => 'Share photos of the wash results...'
]) ?>
```

### Recon Orders (Top bar con items custom)
```php
<?= $this->include('components/vehicle_info_topbar', [
    'order' => $order,
    'module_type' => 'recon_orders',
    'custom_items' => [
        [
            'icon' => 'check-circle',
            'icon_class' => 'text-success',
            'label' => 'Items Checked',
            'value' => '15/20',
            'sub' => '5 remaining'
        ],
        [
            'icon' => 'alert-triangle', 
            'icon_class' => 'text-warning',
            'label' => 'Issues Found',
            'value' => '3',
            'sub' => 'Minor repairs needed'
        ]
    ]
]) ?>
```

---

## 🔧 JavaScript Functions Disponibles

### Internal Notes
- `submitInternalNote(componentId, orderId, moduleType)`
- `loadInternalNotes(componentId, orderId, moduleType)`
- `loadMyMentions(componentId, orderId, moduleType)`
- `loadTeamActivity(componentId, orderId, moduleType)`

### QR Code
- `showQRModal(modalId)`
- `copyShortUrl(inputId)`
- `generateQRCode(orderId, moduleType)`
- `downloadQRCode(qrUrl, orderNumber)`
- `printQRCode(qrUrl, orderNumber)`

### Quick Actions
- `updateOrderStatus(componentId, orderId, moduleType)`
- `openSMSModal(componentId, orderId, moduleType, phone)`
- `openEmailModal(componentId, orderId, moduleType, email)`
- `printOrder(orderId, moduleType)`
- `sendNotificationAction(orderId, moduleType)`
- `addCustomQuickAction(componentId, buttonHtml)`

### Comments
- `submitComment(componentId, orderId, moduleType)`
- `loadComments(componentId, orderId, moduleType, forceRefresh)`
- `editComment(commentId)`
- `deleteComment(commentId)`

### Vehicle Top Bar
- `refreshTopBarData(componentId, orderId, moduleType)`
- `updateTopBarContent(componentId, orderData)`

---

## 📱 Responsive Design

Todos los componentes incluyen estilos responsive optimizados para:

- **Desktop**: 1400px+ (diseño completo)
- **Large Desktop**: 1200px-1399px (optimizado) 
- **Desktop**: 992px-1199px (compacto)
- **Tablet**: 768px-991px (stack parcial)
- **Mobile**: <768px (stack completo)

---

## 🎯 Personalización

### Agregar Estilos Personalizados
Cada componente puede ser personalizado mediante CSS:

```css
#specific_component_id .custom-class {
    /* Tus estilos personalizados */
}
```

### IDs Únicos Generados
Cada instancia del componente genera IDs únicos:
- Internal Notes: `internal_notes_{module_type}_{order_id}`
- QR Code: `qr_code_{module_prefix}_{order_id}`  
- Quick Actions: `quick_actions_{module_type}_{order_id}`
- Comments: `comments_{module_type}_{order_id}`
- Top Bar: `vehicle_topbar_{module_type}_{order_id}`

---

## ⚠️ Consideraciones Importantes

### Dependencias
- **Bootstrap 5**: Para modals, cards, buttons, etc.
- **Feather Icons**: Para iconos (`feather.replace()`)
- **jQuery**: Para algunos eventos (opcional)

### APIs Esperadas
Los componentes hacen llamadas a estas rutas API:
```
POST /api/notes/{module_type}
GET  /api/notes/{module_type}/{order_id}
POST /api/comments/{module_type}  
GET  /api/comments/{module_type}/{order_id}
PUT  /api/orders/{module_type}/{order_id}/status
POST /api/orders/{module_type}/{order_id}/notify
POST /api/generate-qr/{module_type}/{order_id}
GET  /api/users/staff
```

### Funciones Globales Requeridas
- `showToast(message, type)`: Para notificaciones
- Bootstrap modal inicialización
- Feather icons reemplazo

---

## 🔄 Migración desde Sales Orders

Para migrar una vista existente de Sales Orders a usar estos componentes:

1. **Reemplazar sección top bar:**
```php
<!-- Antes: HTML directo del top bar -->
<!-- Después: -->
<?= $this->include('components/vehicle_info_topbar', [
    'order' => $order,
    'module_type' => 'sales_orders'
]) ?>
```

2. **Reemplazar comentarios:**
```php
<!-- Antes: HTML directo de comentarios -->
<!-- Después: -->
<?= $this->include('components/comments', [
    'order_id' => $order['id'],
    'module_type' => 'sales_orders'
]) ?>
```

3. **Continuar con los demás componentes...**

---

## 📝 Desarrollo y Contribución

### Agregar Nuevos Componentes
1. Crear archivo PHP en `/app/Views/components/`
2. Seguir el patrón de parámetros y documentación
3. Agregar estilos CSS internos
4. Incluir JavaScript con IDs únicos
5. Actualizar este README

### Testing
Probar cada componente en:
- Diferentes módulos
- Diferentes tamaños de pantalla  
- Con y sin datos
- Estados de error

---

*Documentación creada: 2025-01-19*  
*Componentes extraídos de: Sales Orders View*  
*Compatible con: Todos los módulos MDA*