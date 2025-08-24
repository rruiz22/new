# 🗄️ Database Schema - Documentación Completa

## 📋 **Información General**

Esta documentación detalla el esquema completo de la base de datos del sistema MDA, incluyendo todas las tablas, relaciones, índices y consideraciones de rendimiento.

### **Motor de Base de Datos**
- **DBMS**: MySQL 5.7+ / MariaDB 10.3+
- **Charset**: utf8mb4_unicode_ci
- **Engine**: InnoDB (para transacciones ACID)
- **Collation**: utf8mb4_unicode_ci

---

## 🏗️ **Arquitectura de la Base de Datos**

### **Organización por Módulos**
```
Database: mda_system
├── Core Tables (Usuarios, Autenticación, Configuración)
├── Sales Orders (Órdenes de venta y servicios)
├── Service Orders (Órdenes de servicio técnico)
├── Car Wash (Servicios de lavado)
├── Recon Orders (Reconocimiento e inventario)
├── Vehicles (Gestión de vehículos)
├── Public Pages (CMS y páginas públicas)
├── Communication (Chat, SMS, Notificaciones)
├── System (Auditoría, Logs, Settings)
└── Integration (APIs externas, Webhooks)
```

---

## 👥 **Tablas Core del Sistema**

### **Usuarios y Autenticación**
```sql
-- Tabla principal de usuarios (CodeIgniter Shield)
CREATE TABLE `users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(30) DEFAULT NULL,
    `status` varchar(255) DEFAULT NULL,
    `status_message` varchar(255) DEFAULT NULL,
    `active` tinyint(1) NOT NULL DEFAULT 0,
    `last_active` datetime DEFAULT NULL,
    `first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) DEFAULT NULL,
    `user_type` varchar(50) DEFAULT NULL,
    `role_id` int(11) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `web_notifications` tinyint(1) DEFAULT 1,
    `email_notifications` tinyint(1) DEFAULT 1,
    `sms_notifications` tinyint(1) DEFAULT 0,
    `client_permissions` text DEFAULT NULL,
    `deleted` tinyint(1) DEFAULT 0,
    `avatar` varchar(255) DEFAULT NULL,
    `date_format` varchar(20) DEFAULT 'Y-m-d',
    `timezone` varchar(50) DEFAULT 'UTC',
    `client_id` int(11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_users_active` (`active`),
    KEY `idx_users_user_type` (`user_type`),
    KEY `idx_users_client_id` (`client_id`)
);

-- Identidades de autenticación (emails, passwords)
CREATE TABLE `auth_identities` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `type` varchar(255) NOT NULL,
    `name` varchar(255) DEFAULT NULL,
    `secret` varchar(255) NOT NULL,
    `secret2` varchar(255) DEFAULT NULL,
    `expires` datetime DEFAULT NULL,
    `extra` text DEFAULT NULL,
    `force_reset` tinyint(1) NOT NULL DEFAULT 0,
    `last_used_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `type_secret` (`type`,`secret`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- Grupos de usuarios (roles)
CREATE TABLE `auth_groups_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `group` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `auth_groups_users_user_id_foreign` (`user_id`),
    CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

### **Clientes y Contactos**
```sql
-- Clientes principales (concesionarios, empresas)
CREATE TABLE `clients` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `address` text DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `state` varchar(100) DEFAULT NULL,
    `zip_code` varchar(20) DEFAULT NULL,
    `country` varchar(100) DEFAULT 'US',
    `website` varchar(255) DEFAULT NULL,
    `logo` varchar(255) DEFAULT NULL,
    `tax_id` varchar(50) DEFAULT NULL,
    `billing_address` text DEFAULT NULL,
    `payment_terms` varchar(50) DEFAULT NULL,
    `credit_limit` decimal(10,2) DEFAULT NULL,
    `status` enum('active','inactive','suspended') DEFAULT 'active',
    `notes` text DEFAULT NULL,
    `created_by` int(11) DEFAULT NULL,
    `updated_by` int(11) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_clients_status` (`status`),
    KEY `idx_clients_name` (`name`)
);

-- Contactos de clientes
CREATE TABLE `contacts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `client_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `first_name` varchar(255) NOT NULL,
    `last_name` varchar(255) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `position` varchar(100) DEFAULT NULL,
    `department` varchar(100) DEFAULT NULL,
    `is_primary` tinyint(1) DEFAULT 0,
    `is_billing` tinyint(1) DEFAULT 0,
    `is_technical` tinyint(1) DEFAULT 0,
    `notes` text DEFAULT NULL,
    `status` enum('active','inactive') DEFAULT 'active',
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_contacts_client_id` (`client_id`),
    KEY `idx_contacts_user_id` (`user_id`),
    KEY `idx_contacts_email` (`email`),
    CONSTRAINT `contacts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
);
```

---

## 🛒 **Sales Orders - Esquema**

### **Órdenes Principales**
```sql
-- Órdenes de venta principales
CREATE TABLE `sales_orders` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_number` varchar(50) DEFAULT NULL,
    `client_id` int(11) unsigned DEFAULT NULL,
    `contact_id` int(11) unsigned DEFAULT NULL,
    `vehicle` varchar(255) DEFAULT NULL,
    `vin` varchar(17) DEFAULT NULL,
    `stock` varchar(100) DEFAULT NULL,
    `service_id` int(11) unsigned DEFAULT NULL,
    `date` date DEFAULT NULL,
    `time` time DEFAULT NULL,
    `status` enum('pending','confirmed','in_progress','completed','cancelled') DEFAULT 'pending',
    `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
    `assigned_to` int(11) unsigned DEFAULT NULL,
    `total_amount` decimal(10,2) DEFAULT NULL,
    `paid_amount` decimal(10,2) DEFAULT 0.00,
    `payment_status` enum('pending','partial','paid','refunded') DEFAULT 'pending',
    `notes` text DEFAULT NULL,
    `internal_notes` text DEFAULT NULL,
    `customer_notes` text DEFAULT NULL,
    
    -- QR Code and Short URL fields
    `short_url` varchar(255) DEFAULT NULL,
    `short_url_slug` varchar(50) DEFAULT NULL,
    `lima_link_id` varchar(100) DEFAULT NULL,
    `qr_generated_at` datetime DEFAULT NULL,
    
    -- Tracking fields
    `created_by` int(11) unsigned DEFAULT NULL,
    `updated_by` int(11) unsigned DEFAULT NULL,
    `deleted_by` int(11) unsigned DEFAULT NULL,
    `deleted` tinyint(1) DEFAULT 0,
    
    -- Timestamps
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_sales_orders_order_number` (`order_number`),
    KEY `idx_sales_orders_client_id` (`client_id`),
    KEY `idx_sales_orders_status` (`status`),
    KEY `idx_sales_orders_date` (`date`),
    KEY `idx_sales_orders_assigned_to` (`assigned_to`),
    KEY `idx_sales_orders_vin` (`vin`),
    KEY `idx_sales_orders_deleted` (`deleted`)
);

-- Servicios disponibles para órdenes de venta
CREATE TABLE `sales_orders_services` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `service_name` varchar(255) NOT NULL,
    `service_description` text DEFAULT NULL,
    `service_category` varchar(100) DEFAULT NULL,
    `base_price` decimal(10,2) DEFAULT NULL,
    `estimated_duration` int(11) DEFAULT NULL, -- en minutos
    `service_status` enum('active','inactive') DEFAULT 'active',
    `show_in_orders` tinyint(1) DEFAULT 1,
    `client_specific` tinyint(1) DEFAULT 0,
    `allowed_clients` text DEFAULT NULL, -- JSON array de client_ids
    `requires_approval` tinyint(1) DEFAULT 0,
    `service_code` varchar(50) DEFAULT NULL,
    `tax_rate` decimal(5,2) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_services_status` (`service_status`),
    KEY `idx_services_category` (`service_category`)
);

-- Actividades de órdenes de venta
CREATE TABLE `order_activity` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `activity_type` varchar(100) NOT NULL,
    `activity_description` text NOT NULL,
    `old_value` text DEFAULT NULL,
    `new_value` text DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_activity_order_id` (`order_id`),
    KEY `idx_activity_user_id` (`user_id`),
    KEY `idx_activity_type` (`activity_type`),
    KEY `idx_activity_created_at` (`created_at`)
);
```

---

## 🔧 **Service Orders - Esquema**

### **Órdenes de Servicio Técnico**
```sql
-- Órdenes de servicio principales
CREATE TABLE `service_orders` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_number` varchar(50) DEFAULT NULL,
    `client_id` int(11) unsigned DEFAULT NULL,
    `contact_id` int(11) unsigned DEFAULT NULL,
    `vehicle_info` text DEFAULT NULL,
    `vin_number` varchar(17) DEFAULT NULL,
    `stock_number` varchar(100) DEFAULT NULL,
    `service_type` varchar(100) DEFAULT NULL,
    `service_category` varchar(100) DEFAULT NULL,
    `scheduled_date` datetime DEFAULT NULL,
    `started_at` datetime DEFAULT NULL,
    `completed_at` datetime DEFAULT NULL,
    `estimated_completion` datetime DEFAULT NULL,
    
    -- Estados específicos de servicio
    `status` enum('received','diagnosing','waiting_parts','in_repair','quality_check','ready_for_delivery','delivered','cancelled') DEFAULT 'received',
    `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
    
    -- Asignaciones
    `assigned_technician` int(11) unsigned DEFAULT NULL,
    `supervisor_id` int(11) unsigned DEFAULT NULL,
    `quality_checker` int(11) unsigned DEFAULT NULL,
    
    -- Información financiera
    `estimated_cost` decimal(10,2) DEFAULT NULL,
    `actual_cost` decimal(10,2) DEFAULT NULL,
    `labor_hours` decimal(5,2) DEFAULT NULL,
    `parts_cost` decimal(10,2) DEFAULT NULL,
    `total_amount` decimal(10,2) DEFAULT NULL,
    
    -- Información del cliente
    `customer_complaint` text DEFAULT NULL,
    `diagnosis_notes` text DEFAULT NULL,
    `work_performed` text DEFAULT NULL,
    `quality_notes` text DEFAULT NULL,
    `customer_approval_required` tinyint(1) DEFAULT 0,
    `customer_approved` tinyint(1) DEFAULT 0,
    `customer_approved_at` datetime DEFAULT NULL,
    
    -- QR y URLs cortas
    `short_url` varchar(255) DEFAULT NULL,
    `short_url_slug` varchar(50) DEFAULT NULL,
    `lima_link_id` varchar(100) DEFAULT NULL,
    `qr_generated_at` datetime DEFAULT NULL,
    
    -- Tracking
    `created_by` int(11) unsigned DEFAULT NULL,
    `updated_by` int(11) unsigned DEFAULT NULL,
    `deleted` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_service_orders_order_number` (`order_number`),
    KEY `idx_service_orders_client_id` (`client_id`),
    KEY `idx_service_orders_status` (`status`),
    KEY `idx_service_orders_technician` (`assigned_technician`),
    KEY `idx_service_orders_scheduled_date` (`scheduled_date`),
    KEY `idx_service_orders_vin` (`vin_number`)
);

-- Sistema de notas jerárquicas para service orders
CREATE TABLE `service_order_notes` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `service_order_id` int(11) unsigned NOT NULL,
    `parent_note_id` int(11) unsigned DEFAULT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `note_type` enum('technical','admin','client','internal','quality','parts') DEFAULT 'technical',
    `note_content` text NOT NULL,
    `is_private` tinyint(1) DEFAULT 0,
    `mentioned_users` text DEFAULT NULL, -- JSON array de user_ids mencionados
    `attachments` text DEFAULT NULL, -- JSON array de archivos adjuntos
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_service_notes_order_id` (`service_order_id`),
    KEY `idx_service_notes_parent_id` (`parent_note_id`),
    KEY `idx_service_notes_user_id` (`user_id`),
    KEY `idx_service_notes_type` (`note_type`)
);

-- Sistema de seguidores para service orders
CREATE TABLE `service_order_followers` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `service_order_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `notification_preferences` text DEFAULT NULL, -- JSON con preferencias
    `added_by` int(11) unsigned DEFAULT NULL,
    `added_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_followers_unique` (`service_order_id`, `user_id`),
    KEY `idx_followers_order_id` (`service_order_id`),
    KEY `idx_followers_user_id` (`user_id`)
);
```

---

## 🚗 **Car Wash - Esquema**

### **Órdenes de Lavado**
```sql
-- Órdenes de car wash
CREATE TABLE `car_wash_orders` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_number` varchar(50) DEFAULT NULL,
    `client_id` int(11) unsigned DEFAULT NULL,
    `contact_id` int(11) unsigned DEFAULT NULL,
    
    -- Información del vehículo
    `vehicle_make` varchar(100) DEFAULT NULL,
    `vehicle_model` varchar(100) DEFAULT NULL,
    `vehicle_year` int(4) DEFAULT NULL,
    `vehicle_color` varchar(50) DEFAULT NULL,
    `license_plate` varchar(20) DEFAULT NULL,
    `vin_number` varchar(17) DEFAULT NULL,
    `vehicle_size` enum('compact','sedan','suv','truck','van') DEFAULT 'sedan',
    
    -- Información del servicio
    `service_type` enum('basic','premium','deluxe','custom') DEFAULT 'basic',
    `selected_services` text DEFAULT NULL, -- JSON array de servicios
    `scheduled_date` date DEFAULT NULL,
    `scheduled_time` time DEFAULT NULL,
    `estimated_duration` int(11) DEFAULT NULL, -- minutos
    `actual_start_time` datetime DEFAULT NULL,
    `actual_end_time` datetime DEFAULT NULL,
    
    -- Estados específicos de car wash
    `status` enum('pending','confirmed','in_progress','quality_check','completed','cancelled') DEFAULT 'pending',
    `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
    
    -- Asignaciones
    `assigned_staff` text DEFAULT NULL, -- JSON array de staff_ids
    `supervisor_id` int(11) unsigned DEFAULT NULL,
    `quality_checker` int(11) unsigned DEFAULT NULL,
    
    -- Información financiera
    `base_price` decimal(8,2) DEFAULT NULL,
    `additional_services_cost` decimal(8,2) DEFAULT NULL,
    `discount_amount` decimal(8,2) DEFAULT NULL,
    `tax_amount` decimal(8,2) DEFAULT NULL,
    `total_amount` decimal(8,2) DEFAULT NULL,
    `payment_method` varchar(50) DEFAULT NULL,
    `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
    
    -- Notas y comentarios
    `customer_notes` text DEFAULT NULL,
    `staff_notes` text DEFAULT NULL,
    `quality_notes` text DEFAULT NULL,
    `special_instructions` text DEFAULT NULL,
    
    -- QR y URLs
    `short_url` varchar(255) DEFAULT NULL,
    `short_url_slug` varchar(50) DEFAULT NULL,
    `lima_link_id` varchar(100) DEFAULT NULL,
    `qr_generated_at` datetime DEFAULT NULL,
    
    -- Tracking
    `created_by` int(11) unsigned DEFAULT NULL,
    `updated_by` int(11) unsigned DEFAULT NULL,
    `deleted` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_car_wash_order_number` (`order_number`),
    KEY `idx_car_wash_client_id` (`client_id`),
    KEY `idx_car_wash_status` (`status`),
    KEY `idx_car_wash_scheduled_date` (`scheduled_date`),
    KEY `idx_car_wash_license_plate` (`license_plate`)
);

-- Servicios disponibles para car wash
CREATE TABLE `car_wash_services` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `service_name` varchar(255) NOT NULL,
    `service_category` enum('exterior','interior','full_service','detailing','additional') NOT NULL,
    `service_description` text DEFAULT NULL,
    `base_price` decimal(8,2) NOT NULL,
    `duration_minutes` int(11) DEFAULT NULL,
    `vehicle_size_pricing` text DEFAULT NULL, -- JSON con precios por tamaño
    `is_addon` tinyint(1) DEFAULT 0,
    `requires_appointment` tinyint(1) DEFAULT 0,
    `service_status` enum('active','inactive') DEFAULT 'active',
    `display_order` int(11) DEFAULT 0,
    `service_icon` varchar(255) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_car_wash_services_category` (`service_category`),
    KEY `idx_car_wash_services_status` (`service_status`)
);
```

---

## 📋 **Recon Orders - Esquema**

### **Órdenes de Reconocimiento**
```sql
-- Órdenes de reconocimiento
CREATE TABLE `recon_orders` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `order_number` varchar(50) DEFAULT NULL,
    `client_id` int(11) unsigned NOT NULL,
    `stock` varchar(100) NOT NULL,
    `vin_number` varchar(17) NOT NULL,
    `vehicle` varchar(255) NOT NULL,
    `vehicle_year` int(4) DEFAULT NULL,
    `vehicle_make` varchar(100) DEFAULT NULL,
    `vehicle_model` varchar(100) DEFAULT NULL,
    `vehicle_color` varchar(50) DEFAULT NULL,
    `mileage` int(11) DEFAULT NULL,
    
    -- Información del servicio
    `service_id` int(11) unsigned DEFAULT NULL,
    `service_date` date DEFAULT NULL,
    `services` text DEFAULT NULL, -- JSON array de servicios asignados
    `estimated_completion_date` date DEFAULT NULL,
    `actual_completion_date` date DEFAULT NULL,
    
    -- Estados del proceso de recon
    `status` enum('received','inspecting','services_assigned','in_process','quality_check','ready_for_sale','rejected') DEFAULT 'received',
    `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
    
    -- Información de inspección
    `inspection_score` int(3) DEFAULT NULL, -- 0-100
    `condition_grade` enum('excellent','good','fair','poor') DEFAULT NULL,
    `estimated_prep_cost` decimal(10,2) DEFAULT NULL,
    `actual_prep_cost` decimal(10,2) DEFAULT NULL,
    `estimated_market_value` decimal(10,2) DEFAULT NULL,
    `projected_profit` decimal(10,2) DEFAULT NULL,
    
    -- Fotos y documentación
    `photos` text DEFAULT NULL, -- JSON array de URLs de fotos
    `inspection_report` text DEFAULT NULL,
    `services_completed` text DEFAULT NULL,
    `quality_report` text DEFAULT NULL,
    
    -- Notas
    `notes` text DEFAULT NULL,
    `internal_notes` text DEFAULT NULL,
    `inspector_notes` text DEFAULT NULL,
    
    -- Asignaciones
    `assigned_to` int(11) unsigned DEFAULT NULL,
    `inspector_id` int(11) unsigned DEFAULT NULL,
    `quality_checker` int(11) unsigned DEFAULT NULL,
    
    -- URLs y QR
    `short_url` varchar(255) DEFAULT NULL,
    `short_url_slug` varchar(50) DEFAULT NULL,
    `lima_link_id` varchar(100) DEFAULT NULL,
    `qr_generated_at` datetime DEFAULT NULL,
    
    -- Metadata de importación
    `from_inventory` tinyint(1) DEFAULT 0,
    `source_type` varchar(50) DEFAULT 'manual',
    `inventory_data` text DEFAULT NULL, -- JSON con datos originales
    
    -- Tracking
    `created_by` int(11) unsigned DEFAULT NULL,
    `updated_by` int(11) unsigned DEFAULT NULL,
    `deleted_by` int(11) unsigned DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_recon_order_number` (`order_number`),
    UNIQUE KEY `idx_recon_vin_client` (`vin_number`, `client_id`),
    KEY `idx_recon_client_id` (`client_id`),
    KEY `idx_recon_status` (`status`),
    KEY `idx_recon_stock` (`stock`),
    KEY `idx_recon_service_date` (`service_date`)
);

-- Vehículos principales (tabla maestra)
CREATE TABLE `recon_vehicles` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vin_number` varchar(17) NOT NULL,
    `stock_number` varchar(100) DEFAULT NULL,
    `make` varchar(100) NOT NULL,
    `model` varchar(100) NOT NULL,
    `year` int(4) NOT NULL,
    `color` varchar(50) DEFAULT NULL,
    `trim` varchar(100) DEFAULT NULL,
    `engine` varchar(100) DEFAULT NULL,
    `transmission` varchar(100) DEFAULT NULL,
    `fuel_type` varchar(50) DEFAULT NULL,
    `drivetrain` varchar(50) DEFAULT NULL,
    `mileage` int(11) DEFAULT NULL,
    `condition` enum('new','used','certified') DEFAULT 'used',
    `status` enum('in_inventory','in_service','sold','exported') DEFAULT 'in_inventory',
    `acquisition_date` date DEFAULT NULL,
    `acquisition_cost` decimal(10,2) DEFAULT NULL,
    `current_location` varchar(255) DEFAULT NULL,
    `last_location_update` datetime DEFAULT NULL,
    `photos` text DEFAULT NULL, -- JSON array
    `documents` text DEFAULT NULL, -- JSON array
    `notes` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_vehicles_vin` (`vin_number`),
    KEY `idx_vehicles_make_model` (`make`, `model`),
    KEY `idx_vehicles_year` (`year`),
    KEY `idx_vehicles_status` (`status`)
);
```

---

## 🚙 **Vehicles - Esquema**

### **Sistema de Tracking de Vehículos**
```sql
-- Relación vehículo-órdenes (cross-module)
CREATE TABLE `vehicle_orders` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vehicle_id` int(11) unsigned DEFAULT NULL,
    `vin_number` varchar(17) NOT NULL,
    `order_type` enum('recon','sales','service','carwash') NOT NULL,
    `order_id` int(11) unsigned NOT NULL,
    `order_number` varchar(50) DEFAULT NULL,
    
    -- Información del cliente
    `client_id` int(11) unsigned DEFAULT NULL,
    `client_name` varchar(255) DEFAULT NULL,
    
    -- Información específica de la orden
    `stock` varchar(100) DEFAULT NULL,
    `service_name` varchar(255) DEFAULT NULL,
    `service_date` datetime DEFAULT NULL,
    `service_color` varchar(7) DEFAULT NULL, -- Hex color
    `order_status` varchar(50) DEFAULT NULL,
    `order_date` datetime DEFAULT NULL,
    
    -- Metadata
    `from_inventory` tinyint(1) DEFAULT 0,
    `source_type` varchar(50) DEFAULT 'manual',
    `is_primary` tinyint(1) DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    KEY `idx_vehicle_orders_vehicle` (`vehicle_id`),
    KEY `idx_vehicle_orders_vin` (`vin_number`),
    KEY `idx_vehicle_orders_type` (`order_type`, `order_id`),
    KEY `idx_vehicle_orders_client` (`client_id`),
    UNIQUE KEY `unique_vehicle_order` (`vehicle_id`, `order_type`, `order_id`)
);

-- URLs cortas por vehículo
CREATE TABLE `vehicle_shortlinks` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vin_number` varchar(17) NOT NULL,
    `vehicle_id` int(11) unsigned DEFAULT NULL,
    `short_url` varchar(255) NOT NULL,
    `short_url_slug` varchar(50) DEFAULT NULL, -- VIN last 6 digits
    `mda_link_id` varchar(100) DEFAULT NULL, -- MDA Links API ID
    `target_url` varchar(500) NOT NULL, -- Original vehicle URL
    `qr_url` varchar(500) DEFAULT NULL, -- QR code URL
    `qr_image` text DEFAULT NULL, -- Base64 QR image data
    `is_active` tinyint(1) DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_vin_shortlink` (`vin_number`),
    KEY `idx_shortlinks_slug` (`short_url_slug`),
    KEY `idx_shortlinks_vehicle` (`vehicle_id`)
);

-- Tokens NFC para ubicación
CREATE TABLE `vehicle_location_tokens` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vehicle_id` int(11) unsigned DEFAULT NULL,
    `vin_number` varchar(17) NOT NULL,
    `token` varchar(64) NOT NULL UNIQUE,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_location_tokens_vehicle` (`vehicle_id`),
    KEY `idx_location_tokens_token` (`token`)
);

-- Historial de ubicaciones
CREATE TABLE `vehicle_locations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vin_number` varchar(17) NOT NULL,
    `token_used` varchar(64) DEFAULT NULL,
    `latitude` decimal(10, 8) DEFAULT NULL,
    `longitude` decimal(11, 8) DEFAULT NULL,
    `accuracy` decimal(8, 2) DEFAULT NULL, -- metros
    `spot_number` varchar(50) DEFAULT NULL,
    `zone` varchar(100) DEFAULT NULL,
    `recorded_by` varchar(255) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `device_info` varchar(500) DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `recorded_at` datetime NOT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_locations_vin` (`vin_number`),
    KEY `idx_locations_recorded_at` (`recorded_at`),
    KEY `idx_locations_spot` (`spot_number`)
);

-- Espacios de estacionamiento
CREATE TABLE `parking_spots` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `spot_number` varchar(20) NOT NULL,
    `zone` varchar(50) DEFAULT NULL,
    `spot_type` enum('regular','vip','service','delivery') DEFAULT 'regular',
    `is_occupied` tinyint(1) DEFAULT 0,
    `current_vehicle_id` int(11) unsigned DEFAULT NULL,
    `current_vin` varchar(17) DEFAULT NULL,
    `occupied_since` datetime DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_spots_number` (`spot_number`),
    KEY `idx_spots_zone` (`zone`),
    KEY `idx_spots_type` (`spot_type`),
    KEY `idx_spots_occupied` (`is_occupied`)
);
```

---

## 🌐 **Public Pages - Esquema**

### **CMS y Páginas Públicas**
```sql
-- Páginas públicas del CMS
CREATE TABLE `public_pages` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `content` text DEFAULT NULL,
    `excerpt` text DEFAULT NULL,
    
    -- Control de acceso
    `privacy_level` enum('public','password','roles','private','api_only') DEFAULT 'public',
    `password` varchar(255) DEFAULT NULL,
    `allowed_roles` json DEFAULT NULL,
    
    -- Personalización
    `template` varchar(100) DEFAULT 'default',
    `custom_css` text DEFAULT NULL,
    `custom_js` text DEFAULT NULL,
    
    -- Estado y configuración
    `status` enum('draft','published','archived') DEFAULT 'draft',
    `featured_image` varchar(500) DEFAULT NULL,
    `views_count` int(11) DEFAULT 0,
    `likes_count` int(11) DEFAULT 0,
    `comments_enabled` tinyint(1) DEFAULT 0,
    `social_sharing` tinyint(1) DEFAULT 1,
    `show_author` tinyint(1) DEFAULT 1,
    `show_date` tinyint(1) DEFAULT 1,
    
    -- Versionado
    `version` int(11) DEFAULT 1,
    `created_by` int(11) unsigned DEFAULT NULL,
    `updated_by` int(11) unsigned DEFAULT NULL,
    `published_at` datetime DEFAULT NULL,
    
    -- Timestamps
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_public_pages_slug` (`slug`),
    KEY `idx_public_pages_status` (`status`),
    KEY `idx_public_pages_privacy` (`privacy_level`),
    KEY `idx_public_pages_published` (`published_at`)
);

-- Logs de acceso a páginas públicas
CREATE TABLE `public_page_views` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `page_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_agent` text DEFAULT NULL,
    `referer` varchar(500) DEFAULT NULL,
    `session_id` varchar(128) DEFAULT NULL,
    `view_duration` int(11) DEFAULT NULL, -- segundos
    `viewed_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_page_views_page_id` (`page_id`),
    KEY `idx_page_views_user_id` (`user_id`),
    KEY `idx_page_views_ip` (`ip_address`),
    KEY `idx_page_views_viewed_at` (`viewed_at`)
);
```

---

## 💬 **Communication - Esquema**

### **Sistema de Chat y Comunicación**
```sql
-- Conversaciones de chat
CREATE TABLE `chat_conversations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `type` enum('direct','group','channel') NOT NULL DEFAULT 'direct',
    `name` varchar(255) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `created_by` int(11) unsigned NOT NULL,
    `is_private` tinyint(1) DEFAULT 1,
    `max_participants` int(11) DEFAULT NULL,
    `settings` json DEFAULT NULL,
    `last_message_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_conversations_type` (`type`),
    KEY `idx_conversations_created_by` (`created_by`),
    KEY `idx_conversations_last_message` (`last_message_at`)
);

-- Mensajes de chat
CREATE TABLE `chat_messages` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `conversation_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `message_type` enum('text','image','file','system','notification') DEFAULT 'text',
    `message_content` text NOT NULL,
    `attachments` json DEFAULT NULL,
    `mentioned_users` json DEFAULT NULL,
    `reply_to_message_id` int(11) unsigned DEFAULT NULL,
    `is_edited` tinyint(1) DEFAULT 0,
    `edited_at` datetime DEFAULT NULL,
    `is_deleted` tinyint(1) DEFAULT 0,
    `deleted_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_messages_conversation` (`conversation_id`),
    KEY `idx_messages_user` (`user_id`),
    KEY `idx_messages_created_at` (`created_at`),
    KEY `idx_messages_reply_to` (`reply_to_message_id`)
);

-- Participantes de conversaciones
CREATE TABLE `chat_participants` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `conversation_id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `role` enum('admin','moderator','member') DEFAULT 'member',
    `joined_at` datetime NOT NULL,
    `last_read_message_id` int(11) unsigned DEFAULT NULL,
    `last_read_at` datetime DEFAULT NULL,
    `notifications_enabled` tinyint(1) DEFAULT 1,
    `is_muted` tinyint(1) DEFAULT 0,
    `left_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_participants_unique` (`conversation_id`, `user_id`),
    KEY `idx_participants_conversation` (`conversation_id`),
    KEY `idx_participants_user` (`user_id`)
);

-- SMS con Twilio
CREATE TABLE `sms_conversations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `phone_number` varchar(20) NOT NULL,
    `contact_name` varchar(255) DEFAULT NULL,
    `contact_id` int(11) unsigned DEFAULT NULL,
    `client_id` int(11) unsigned DEFAULT NULL,
    `module_type` varchar(50) DEFAULT NULL, -- sales_orders, service_orders, etc.
    `module_id` int(11) unsigned DEFAULT NULL,
    `twilio_conversation_sid` varchar(100) DEFAULT NULL,
    `status` enum('active','archived','blocked') DEFAULT 'active',
    `last_message_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_sms_phone` (`phone_number`),
    KEY `idx_sms_contact` (`contact_id`),
    KEY `idx_sms_module` (`module_type`, `module_id`)
);

-- Mensajes SMS
CREATE TABLE `sms_messages` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `conversation_id` int(11) unsigned NOT NULL,
    `twilio_message_sid` varchar(100) DEFAULT NULL,
    `direction` enum('inbound','outbound') NOT NULL,
    `from_number` varchar(20) NOT NULL,
    `to_number` varchar(20) NOT NULL,
    `message_body` text NOT NULL,
    `media_urls` json DEFAULT NULL,
    `status` varchar(50) DEFAULT NULL, -- Twilio status
    `error_code` varchar(10) DEFAULT NULL,
    `error_message` text DEFAULT NULL,
    `sent_by_user_id` int(11) unsigned DEFAULT NULL,
    `template_used` varchar(100) DEFAULT NULL,
    `sent_at` datetime DEFAULT NULL,
    `delivered_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_sms_messages_conversation` (`conversation_id`),
    KEY `idx_sms_messages_twilio_sid` (`twilio_message_sid`),
    KEY `idx_sms_messages_direction` (`direction`),
    KEY `idx_sms_messages_sent_at` (`sent_at`)
);
```

---

## ⚙️ **System Tables - Esquema**

### **Configuración y Sistema**
```sql
-- Configuraciones del sistema
CREATE TABLE `settings` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `setting_key` varchar(255) NOT NULL,
    `setting_value` text DEFAULT NULL,
    `setting_type` enum('string','integer','boolean','json','text') DEFAULT 'string',
    `setting_group` varchar(100) DEFAULT 'general',
    `is_public` tinyint(1) DEFAULT 0,
    `is_encrypted` tinyint(1) DEFAULT 0,
    `description` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_settings_key` (`setting_key`),
    KEY `idx_settings_group` (`setting_group`)
);

-- Auditoría del sistema
CREATE TABLE `audit_trail` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned DEFAULT NULL,
    `event_type` varchar(100) NOT NULL,
    `table_name` varchar(100) DEFAULT NULL,
    `record_id` int(11) unsigned DEFAULT NULL,
    `old_values` json DEFAULT NULL,
    `new_values` json DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_audit_user_id` (`user_id`),
    KEY `idx_audit_event_type` (`event_type`),
    KEY `idx_audit_table_record` (`table_name`, `record_id`),
    KEY `idx_audit_created_at` (`created_at`)
);

-- Sistema de TODOs
CREATE TABLE `todos` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `status` enum('pending','in_progress','completed','cancelled') DEFAULT 'pending',
    `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
    `due_date` datetime DEFAULT NULL,
    `assigned_to` int(11) unsigned DEFAULT NULL,
    `created_by` int(11) unsigned NOT NULL,
    `completed_at` datetime DEFAULT NULL,
    `completed_by` int(11) unsigned DEFAULT NULL,
    `tags` json DEFAULT NULL,
    `related_module` varchar(50) DEFAULT NULL,
    `related_id` int(11) unsigned DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_todos_assigned_to` (`assigned_to`),
    KEY `idx_todos_status` (`status`),
    KEY `idx_todos_priority` (`priority`),
    KEY `idx_todos_due_date` (`due_date`),
    KEY `idx_todos_related` (`related_module`, `related_id`)
);

-- Notificaciones del sistema
CREATE TABLE `notifications` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` int(11) unsigned NOT NULL,
    `type` varchar(100) NOT NULL,
    `title` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `data` json DEFAULT NULL,
    `read_at` datetime DEFAULT NULL,
    `action_url` varchar(500) DEFAULT NULL,
    `expires_at` datetime DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_notifications_user_id` (`user_id`),
    KEY `idx_notifications_type` (`type`),
    KEY `idx_notifications_read_at` (`read_at`),
    KEY `idx_notifications_created_at` (`created_at`)
);

-- Integraciones externas
CREATE TABLE `integrations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `integration_name` varchar(100) NOT NULL,
    `integration_type` varchar(50) NOT NULL,
    `is_active` tinyint(1) DEFAULT 0,
    `configuration` json DEFAULT NULL,
    `credentials` json DEFAULT NULL, -- Encrypted
    `last_sync_at` datetime DEFAULT NULL,
    `sync_status` enum('success','error','in_progress') DEFAULT NULL,
    `error_message` text DEFAULT NULL,
    `created_by` int(11) unsigned DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_integrations_name` (`integration_name`),
    KEY `idx_integrations_type` (`integration_type`),
    KEY `idx_integrations_active` (`is_active`)
);
```

---

## 📊 **Índices y Optimizaciones**

### **Índices de Performance**
```sql
-- Índices compuestos para queries complejas
ALTER TABLE `sales_orders` ADD INDEX `idx_sales_status_date` (`status`, `date`);
ALTER TABLE `service_orders` ADD INDEX `idx_service_technician_status` (`assigned_technician`, `status`);
ALTER TABLE `car_wash_orders` ADD INDEX `idx_carwash_scheduled` (`scheduled_date`, `status`);
ALTER TABLE `recon_orders` ADD INDEX `idx_recon_client_status` (`client_id`, `status`);

-- Índices para búsquedas de texto
ALTER TABLE `sales_orders` ADD FULLTEXT(`notes`, `internal_notes`);
ALTER TABLE `service_orders` ADD FULLTEXT(`customer_complaint`, `diagnosis_notes`);
ALTER TABLE `public_pages` ADD FULLTEXT(`title`, `content`);

-- Índices para ordenamiento por fecha
ALTER TABLE `order_activity` ADD INDEX `idx_activity_order_date` (`order_id`, `created_at` DESC);
ALTER TABLE `chat_messages` ADD INDEX `idx_messages_conv_date` (`conversation_id`, `created_at` DESC);
ALTER TABLE `vehicle_locations` ADD INDEX `idx_locations_vin_date` (`vin_number`, `recorded_at` DESC);
```

### **Particionado de Tablas (Para Grandes Volúmenes)**
```sql
-- Particionado por fecha para tablas de logs
ALTER TABLE `audit_trail` 
PARTITION BY RANGE (YEAR(created_at)) (
    PARTITION p2023 VALUES LESS THAN (2024),
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION pmax VALUES LESS THAN MAXVALUE
);

-- Particionado para mensajes de chat
ALTER TABLE `chat_messages`
PARTITION BY RANGE (YEAR(created_at)) (
    PARTITION p2023 VALUES LESS THAN (2024),
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION pmax VALUES LESS THAN MAXVALUE
);
```

---

## 🔗 **Relaciones y Foreign Keys**

### **Claves Foráneas Principales**
```sql
-- Sales Orders relationships
ALTER TABLE `sales_orders` 
    ADD CONSTRAINT `fk_sales_orders_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_sales_orders_contact` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_sales_orders_service` FOREIGN KEY (`service_id`) REFERENCES `sales_orders_services` (`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_sales_orders_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Service Orders relationships
ALTER TABLE `service_orders`
    ADD CONSTRAINT `fk_service_orders_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
    ADD CONSTRAINT `fk_service_orders_technician` FOREIGN KEY (`assigned_technician`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Vehicle relationships
ALTER TABLE `vehicle_orders`
    ADD CONSTRAINT `fk_vehicle_orders_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `recon_vehicles` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_vehicle_orders_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

-- Chat relationships
ALTER TABLE `chat_messages`
    ADD CONSTRAINT `fk_chat_messages_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `fk_chat_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
```

---

## 📈 **Consideraciones de Performance**

### **Optimizaciones de Consultas**
- **Índices Compuestos**: Para queries multi-campo frecuentes
- **Índices Parciales**: Para consultas con condiciones WHERE específicas
- **FULLTEXT Search**: Para búsquedas de texto en contenido
- **Query Cache**: Activado para consultas repetitivas
- **Connection Pooling**: Para manejo eficiente de conexiones

### **Estrategias de Escalabilidad**
- **Read Replicas**: Para distribuir carga de lectura
- **Particionado**: Para tablas con gran volumen histórico
- **Archivado**: Mover datos antiguos a tablas de archivo
- **Caching**: Redis/Memcached para datos frecuentemente accedidos
- **CDN**: Para archivos estáticos y multimedia

---

**Este esquema de base de datos está diseñado para soportar todas las funcionalidades del sistema MDA con alta performance, escalabilidad y mantenibilidad.**

---

*Documentación actualizada: 2025-01-19*  
*Versión de la base de datos: MDA v2.0*  
*Para más información: [Volver a Documentación Principal](../../claude.md)*


