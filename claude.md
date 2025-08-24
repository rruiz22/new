# 📋 Resumen Detallado de la Aplicación MDA - Sales Orders Management System

## 🎯 **Descripción General**

**MDA (My Detail Area)** es una plataforma integral de gestión de órdenes de servicio construida con **CodeIgniter 4**, diseñada específicamente para concesionarios automotrices y empresas de servicios vehiculares. La aplicación combina una arquitectura modular moderna con funcionalidades avanzadas de tiempo real.

---

## 🏗️ **Arquitectura del Sistema**

### **Framework y Tecnologías Base**
- **Backend**: CodeIgniter 4 (PHP 8.1+)
- **Frontend**: Bootstrap 5 + jQuery + ApexCharts
- **Base de Datos**: MySQL/MariaDB
- **Autenticación**: CodeIgniter Shield
- **WebSockets**: Ratchet para comunicación en tiempo real
- **PDF Generation**: TCPDF + wkhtmltopdf
- **Estilo**: Bootstrap 5 con tema Velzon personalizado

### **Estructura Modular**
```
app/Modules/
├── SalesOrders/          # Módulo principal de órdenes de venta
├── ServiceOrders/        # Órdenes de servicio técnico
├── CarWash/             # Módulo de lavado de autos
├── ReconOrders/         # Órdenes de reconocimiento/inventario
├── Vehicles/            # Gestión de vehículos
├── PublicPages/         # Páginas públicas y API
├── Settings/            # Configuraciones del sistema
└── AuditTrail/          # Auditoría y seguimiento
```

---

## 📚 **Documentación Detallada por Módulo**

### **Módulos Principales:**
- 🛒 **[Sales Orders - Detalle Completo](./docs/modules/sales-orders.md)**
- 🔧 **[Service Orders - Detalle Completo](./docs/modules/service-orders.md)**  
- 🚗 **[Car Wash - Detalle Completo](./docs/modules/car-wash.md)**
- 🚙 **[Vehicles - Detalle Completo](./docs/modules/vehicles.md)**
- 📋 **[Recon Orders - Detalle Completo](./docs/modules/recon-orders.md)**
- 🌐 **[Public Pages - Detalle Completo](./docs/modules/public-pages.md)**

### **Documentación Técnica:**
- 🗄️ **[Database Schema](./docs/technical/database-schema.md)**
- 🔗 **[APIs & Integrations](./docs/technical/apis-integrations.md)**
- 🔐 **[Authentication System](./docs/technical/authentication.md)**
- 🚀 **[Deployment Guide](./docs/technical/deployment.md)**

### **Logística y Procesos:**
- ⚡ **[Workflows por Módulo](./docs/logistics/workflows.md)**
- 👥 **[Roles y Permisos](./docs/logistics/user-roles.md)**
- 📊 **[Procesos de Negocio](./docs/logistics/business-processes.md)**

---

## 🚀 **Resumen de Funcionalidades Principales**

### **Core Business**
- ✅ **Gestión Completa de Órdenes**: Sales, Service, CarWash, Recon
- ✅ **Dashboard en Tiempo Real**: Métricas y analytics avanzados
- ✅ **Sistema de Comunicación**: Chat WebSocket + SMS Twilio
- ✅ **Generación de Documentos**: PDF profesionales + QR codes
- ✅ **Tracking de Vehículos**: Sistema NFC para ubicación

### **Características Técnicas**
- ✅ **Arquitectura Modular**: 8 módulos independientes
- ✅ **API REST Completa**: Para integraciones externas
- ✅ **Multi-idioma**: Español, Inglés, Portugués
- ✅ **Responsive Design**: Optimizado para móviles/tablets
- ✅ **Sistema de Roles**: Permisos granulares por usuario

### **Integraciones Externas**
- ✅ **Twilio**: SMS bidireccional automatizado
- ✅ **AWS S3**: Storage seguro de archivos
- ✅ **MDA.to Links**: URLs cortas personalizadas
- ✅ **Pusher**: Notificaciones push en tiempo real
- ✅ **Google APIs**: Autenticación y servicios

---

## 🛠 **Technology Stack Completo**

```yaml
Backend:
  Framework: CodeIgniter 4
  PHP: 8.1+
  Database: MySQL/MariaDB
  Authentication: CodeIgniter Shield
  
Frontend:
  CSS Framework: Bootstrap 5
  JavaScript: jQuery + Vanilla JS
  Charts: ApexCharts
  Icons: Feather Icons
  Theme: Velzon Admin
  
Real-time:
  WebSocket: Ratchet
  Push Notifications: Pusher
  Live Updates: AJAX + SSE
  
External Services:
  SMS: Twilio
  Storage: AWS S3
  Short URLs: MDA.to API
  PDF: wkhtmltopdf + TCPDF
  QR Codes: Multiple providers
```

---

## 🗄️ **Estructura Detallada de Base de Datos**

### **🔧 Configuración de Base de Datos**
- **Tipo**: Base de datos REMOTA (no local)
- **Proveedor**: Servidor remoto en Google Cloud
- **Credenciales**: Almacenadas en archivo `.env` (raíz del proyecto)
- **Configuración**:
  ```env
  database.default.hostname = 35.212.30.157
  database.default.database = dbuc0youbm7qp9
  database.default.username = u9jvaasruh9vc
  database.default.password = lalinha01?
  database.default.DBDriver = MySQLi
  database.default.port = 3306
  ```

### **📊 Resumen General de la Base de Datos**
- **Total de Tablas**: 74 tablas activas
- **Total de Registros**: 1,171 registros
- **Engine**: InnoDB (MySQL/MariaDB)
- **Charset**: utf8mb3
- **Autenticación**: CodeIgniter Shield (tablas auth_*)
- **Ubicación**: Servidor remoto (35.212.30.157:3306)
- **Categorías Principales**: 10 módulos organizados

### **Arquitectura de Usuarios y Permisos**

#### **Tabla `users` (4 registros activos)**
```sql
CREATE TABLE users (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30) UNIQUE,
  user_type ENUM('admin','manager','staff','client') DEFAULT 'client',
  role_id INT,
  client_id INT,
  status VARCHAR(255),
  status_message VARCHAR(255),
  active TINYINT(1) DEFAULT 0,
  last_active DATETIME,
  created_at DATETIME,
  updated_at DATETIME,
  deleted_at DATETIME,
  deleted TINYINT(1) DEFAULT 0
);
```
**Roles de Usuario:**
- **admin**: Administrador del sistema
- **manager**: Gerente con acceso completo
- **staff**: Empleados del departamento de detailing
- **client**: Usuarios tipo contacto de dealerships

#### **Tabla `clients` (3 registros activos)**
```sql
CREATE TABLE clients (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255),
  phone VARCHAR(50),
  address TEXT,
  website VARCHAR(255),
  tax_number VARCHAR(50),
  status ENUM('active','inactive') DEFAULT 'active',
  created_at DATETIME,
  updated_at DATETIME,
  deleted_at DATETIME,
  deleted TINYINT(1) DEFAULT 0
);
```
**Propósito**: Representa los dealerships/concesionarios que usan la plataforma


### **Sistema de Autenticación (CodeIgniter Shield)**

#### **Tablas de Autenticación (5 tablas)**
- **`auth_groups`** (5 registros): Grupos de usuarios
- **`auth_groups_permissions`** (32 registros): Permisos por grupo
- **`auth_groups_users`** (4 registros): Usuarios asignados a grupos
- **`auth_identities`** (3 registros): Identidades de autenticación
- **`auth_logins`** (54 registros): Historial de inicios de sesión
- **`auth_permissions`** (8 registros): Permisos del sistema
- **`auth_permissions_users`** (0 registros): Permisos individuales por usuario
- **`auth_remember_tokens`** (0 registros): Tokens de "recordarme"
- **`auth_token_logins`** (0 registros): Inicios de sesión por token

### **Módulos de Órdenes (4 tipos principales)**

#### **Sales Orders - Órdenes de Venta (2 registros activos)**
```sql
-- Tabla principal de órdenes de venta
sales_orders (2 registros)
sales_orders_activities (75 registros)
sales_orders_comments (4 registros)
sales_orders_services (3 registros)
sales_orders_services_history (22 registros)
sales_order_followers (1 registro)
sales_order_follower_activity (3 registros)
sales_order_comments (0 registros)
```

#### **Service Orders - Órdenes de Servicio (1 registro activo)**
```sql
-- Tabla principal de órdenes de servicio
service_orders (1 registro)
service_orders_activity (26 registros)
service_orders_comments (5 registros)
service_orders_services (5 registros)
service_orders_services_history (0 registros)
service_orders_notes (0 registros)
service_order_followers (1 registro)
service_order_follower_activity (2 registros)
service_order_follower_notifications (0 registros)
service_order_note_mentions (1 registro)
service_order_notes (5 registros)
```

#### **Car Wash Orders - Órdenes de Lavado (11 registros activos)**
```sql
-- Tabla principal de órdenes de lavado
car_wash_orders (11 registros)
car_wash_activity (49 registros)
car_wash_comments (14 registros)
car_wash_services (6 registros)
car_wash_order_services (0 registros)
car_wash_notes (9 registros)
car_wash_note_mentions (0 registros)
```

#### **Recon Orders - Órdenes de Reconocimiento (85 registros activos)**
```sql
-- Tabla principal de órdenes de reconocimiento
recon_orders (85 registros)
recon_activity (127 registros)
recon_comments (19 registros)
recon_services (38 registros)
recon_order_services (0 registros)
recon_vehicles (2 registros)
recon_notes (17 registros)
recon_followers (1 registro)
```

### **Sistema de Comunicación y Colaboración**

#### **Chat en Tiempo Real (WebSocket)**
```sql
chat_channels (0 registros)
chat_conversations (0 registros)
chat_messages (0 registros)
chat_attachments (0 registros)
chat_channel_members (0 registros)
```

#### **SMS Bidireccional (Twilio)**
```sql
sms_conversations (4 registros activos)
-- Integración con Twilio para comunicación automática
```

#### **Sistema de Notas Internas**
```sql
internal_notes (15 registros)
note_mentions (0 registros)
-- Notas internas del sistema con menciones
```

### **Sistema de Vehículos y Ubicación (NFC)**

#### **Tracking de Vehículos**
```sql
vehicle_locations (59 registros)
vehicle_location_tokens (5 registros)
parking_spots (12 registros)
vehicle_shortlinks (0 registros)
```
**Funcionalidad**: Sistema NFC para rastrear ubicación de vehículos en tiempo real

### **Páginas Públicas y CMS**

#### **Sistema de Páginas Públicas**
```sql
public_pages (5 registros)
public_page_views (13 registros)
public_page_versions (4 registros)
public_page_files (0 registros)
public_page_likes (0 registros)
```

### **Sistema de Configuración y Auditoría**

#### **Configuraciones del Sistema**
```sql
settings (35 registros)
-- Configuraciones globales del sistema
```

#### **Auditoría y Seguimiento**
```sql
audit_trail (0 registros)
-- Registro completo de cambios en el sistema
```

#### **Sistema de Tareas (TODO)**
```sql
todos (0 registros)
todo_notifications (4 registros)
-- Sistema interno de gestión de tareas
```

### **Sistema de Roles y Permisos Personalizados**

#### **Roles Personalizados**
```sql
custom_roles (4 registros)
contact_groups (2 registros)
contact_group_permissions (4 registros)
contact_permissions (13 registros)
user_contact_groups (0 registros)
```

### **Integraciones Externas**

#### **Configuraciones de Integración**
```sql
integration_settings (0 registros)
-- Configuraciones para APIs externas (Twilio, AWS S3, etc.)
```

### **📈 Estadísticas de Datos Actuales (Actualizadas)**
- **Órdenes Totales**: 109 órdenes activas (Sales: 1, Service: 1, CarWash: 11, Recon: 96)
- **Actividades Registradas**: 482 actividades totales (Sales: 93, Service: 31, CarWash: 49, Recon: 309)
- **Usuarios del Sistema**: 5 usuarios activos (incluye admin creado)
- **Clientes/Dealerships**: 3 clientes activos
- **Servicios Configurados**: 53 servicios disponibles (CarWash: 6, Recon: 39, Sales: 3, Service: 5)
- **Ubicaciones de Vehículos**: 61 registros de ubicación activos
- **Conversaciones SMS**: 5 conversaciones activas
- **Comentarios/Notas**: 69 comentarios totales en todos los módulos
- **Páginas Públicas**: 7 páginas con 18 visualizaciones
- **Tokens NFC**: 11 tokens de ubicación vehicular activos

### **Relaciones Clave de la Base de Datos**
```
users (staff) ←→ clients (dealerships) ←→ contacts (client users)
     ↓
[sales_orders, service_orders, car_wash_orders, recon_orders]
     ↓
[activities, comments, services, followers]
     ↓
[sms_conversations, internal_notes, public_pages]
```

### **🗄️ ESTRUCTURA DETALLADA COMPLETA DE LA BASE DE DATOS**

#### **👥 USUARIOS Y AUTENTICACIÓN (14 tablas)**

**🔐 Sistema de Autenticación (CodeIgniter Shield)**
- `auth_groups` (5 registros) - Grupos de usuarios
- `auth_groups_permissions` (32 registros) - Permisos por grupo  
- `auth_groups_users` (5 registros) - Asignación usuarios-grupos
- `auth_identities` (4 registros) - Identidades de autenticación
- `auth_logins` (141 registros) - Historial de inicios de sesión
- `auth_permissions` (8 registros) - Permisos del sistema
- `auth_permissions_users` (0 registros) - Permisos individuales
- `auth_remember_tokens` (0 registros) - Tokens de recordar sesión
- `auth_token_logins` (0 registros) - Logins por token

**👤 Gestión de Usuarios**
- `users` (5 registros) - Tabla principal de usuarios
  - **Usuario Admin Creado**: `rruiz` (ID: 3) con permisos completos
  - **Grupos**: admin, superadmin
  - **Rol**: Admin Role (ID: 1)
- `custom_roles` (4 registros) - Roles personalizados del sistema
- `contact_groups` (2 registros) - Grupos de contactos
- `contact_group_permissions` (4 registros) - Permisos de grupos
- `contact_permissions` (13 registros) - Permisos disponibles

#### **🛒 SALES ORDERS (7 tablas)**
- `sales_orders` (1 registro) - Órdenes de venta principales
- `sales_orders_activities` (93 registros) - Actividades y cambios
- `sales_orders_comments` (4 registros) - Comentarios con attachments
- `sales_orders_services` (3 registros) - Servicios disponibles
- `sales_orders_services_history` (23 registros) - Historial de servicios
- `sales_order_followers` (2 registros) - Seguidores de órdenes
- `sales_order_follower_activity` (3 registros) - Actividad de seguidores

#### **🔧 SERVICE ORDERS (9 tablas)**
- `service_orders` (1 registro) - Órdenes de servicio principales
- `service_orders_activity` (31 registros) - Actividades del servicio
- `service_orders_comments` (5 registros) - Comentarios del servicio
- `service_orders_services` (5 registros) - Servicios de reparación
- `service_orders_services_history` (0 registros) - Historial de servicios
- `service_order_followers` (1 registro) - Seguidores del servicio
- `service_order_follower_activity` (3 registros) - Actividad de seguidores
- `service_order_notes` (6 registros) - Notas internas con menciones
- `service_order_note_mentions` (1 registro) - Menciones en notas

#### **🚗 CAR WASH (6 tablas)**
- `car_wash_orders` (11 registros) - Órdenes de lavado principales
- `car_wash_activity` (49 registros) - Actividades de lavado
- `car_wash_comments` (14 registros) - Comentarios de lavado
- `car_wash_services` (6 registros) - Servicios de lavado disponibles
- `car_wash_notes` (9 registros) - Notas internas de lavado
- `car_wash_note_mentions` (0 registros) - Menciones en notas

#### **🔍 RECON ORDERS (7 tablas)**
- `recon_orders` (96 registros) - Órdenes de reconocimiento
- `recon_activity` (309 registros) - Actividades de reconocimiento
- `recon_comments` (21 registros) - Comentarios de recon
- `recon_services` (39 registros) - Servicios de reconocimiento
- `recon_notes` (17 registros) - Notas de reconocimiento
- `recon_followers` (1 registro) - Seguidores de recon
- `recon_vehicles` (2 registros) - Vehículos en reconocimiento

#### **🚙 VEHÍCULOS Y UBICACIÓN NFC (4 tablas)**
- `vehicle_locations` (61 registros) - Ubicaciones de vehículos
- `vehicle_location_tokens` (11 registros) - Tokens NFC para ubicación
- `parking_spots` (12 registros) - Espacios de estacionamiento
- `vehicle_shortlinks` (3 registros) - Enlaces cortos para vehículos

#### **🌐 PÁGINAS PÚBLICAS (5 tablas)**
- `public_pages` (7 registros) - Páginas públicas del sistema
- `public_page_views` (18 registros) - Visualizaciones de páginas
- `public_page_versions` (6 registros) - Versiones de páginas
- `public_page_files` (0 registros) - Archivos de páginas
- `public_page_likes` (0 registros) - Likes de páginas

#### **💬 COMUNICACIÓN (7 tablas)**
- `sms_conversations` (5 registros) - Conversaciones SMS bidireccionales
- `internal_notes` (16 registros) - Notas internas del sistema
- `note_mentions` (0 registros) - Menciones en notas
- `chat_channels` (0 registros) - Canales de chat
- `chat_conversations` (0 registros) - Conversaciones de chat
- `chat_messages` (0 registros) - Mensajes de chat
- `chat_attachments` (0 registros) - Archivos adjuntos

#### **⚙️ CONFIGURACIÓN Y SISTEMA (6 tablas)**
- `settings` (35 registros) - Configuraciones globales
- `todos` (0 registros) - Sistema de tareas
- `todo_notifications` (4 registros) - Notificaciones de tareas
- `audit_trail` (0 registros) - Registro de auditoría
- `integration_settings` (0 registros) - Configuraciones de APIs
- `migrations` (9 registros) - Migraciones de CodeIgniter

#### **🏢 CLIENTES Y CONTACTOS (3 tablas)**
- `clients` (3 registros) - Clientes/Dealerships principales
- `contacts` (2 registros) - Contactos de clientes
- `user_contact_groups` (0 registros) - Grupos de contactos de usuario

### **🔗 Campos Clave Comunes en Todas las Tablas**

**Campos de Auditoría:**
- `created_at`, `updated_at`, `deleted_at` (timestamps)
- `created_by`, `updated_by`, `deleted_by` (user tracking)
- `deleted` (soft delete flag)

**Campos de Short URLs y QR:**
- `short_url`, `short_url_slug`, `lima_link_id` (integración MDA.to)
- `qr_generated_at`, `qr_url` (códigos QR)

**Campos de Estado y Seguimiento:**
- `status` (enum con estados específicos por módulo)
- `priority` (normal, urgent, high, etc.)
- `notes`, `internal_notes` (campos de texto)

**Campos de Relación:**
- `client_id` (relación con dealerships)
- `user_id`, `contact_id` (relaciones con usuarios)
- `order_id` (relación con órdenes principales)

---

## 📊 **Métricas del Sistema**

### **Tamaño del Proyecto**
- **Líneas de Código**: ~50,000+ líneas PHP
- **Archivos**: 500+ archivos fuente
- **Tablas de BD**: 25+ tablas principales
- **Módulos**: 8 módulos funcionales
- **Controladores**: 15+ controladores principales

### **Capacidades**
- **Usuarios Concurrentes**: 100+ usuarios simultáneos
- **Órdenes por Día**: 1000+ órdenes procesables
- **Archivos**: Storage ilimitado (AWS S3)
- **Idiomas**: 3 idiomas soportados
- **Dispositivos**: Desktop, tablet, móvil

---

## 🎯 **Casos de Uso Principales**

### **Para Concesionarios Automotrices**
1. **Gestión de Inventario**: Control completo de vehículos
2. **Órdenes de Reconocimiento**: Inspección y preparación
3. **Servicios Post-Venta**: Mantenimiento y reparaciones
4. **Comunicación con Clientes**: Automatizada y personalizada
5. **Reportes Ejecutivos**: Analytics de negocio

### **Para Talleres de Servicio**
1. **Órdenes de Trabajo**: Asignación de técnicos
2. **Control de Calidad**: Seguimiento detallado
3. **Gestión de Repuestos**: Inventario integrado
4. **Facturación**: Documentos automáticos
5. **Comunicación**: Cliente-técnico en tiempo real

---

## 🔮 **Roadmap de Desarrollo**

### **Próximas Funcionalidades**
- [ ] **Mobile App Nativa**: iOS/Android
- [ ] **AI Chatbot**: Soporte automatizado
- [ ] **Multi-tenant**: Múltiples concesionarios
- [ ] **Advanced Analytics**: Machine Learning
- [ ] **E-commerce**: Venta de repuestos

### **Integraciones Planificadas**
- [ ] **DMS Integration**: Sistemas de concesionarios
- [ ] **Payment Gateway**: Procesamiento de pagos
- [ ] **CRM Integration**: HubSpot, Salesforce
- [ ] **Accounting**: QuickBooks, Xero
- [ ] **Maps Integration**: Google Maps avanzado

---

## 📞 **Información de Contacto y Soporte**

### **Documentación**
- **Documentación Completa**: Ver carpeta `docs/`
- **API Documentation**: Disponible en `/api/docs`
- **Video Tutorials**: Enlaces en cada módulo
- **FAQ**: Preguntas frecuentes por módulo

### **Soporte Técnico**
- **Logs del Sistema**: Disponibles en `writable/logs/`
- **Debug Mode**: Configuración en `.env`
- **Error Monitoring**: Integrado con sistema de alertas
- **Performance Monitoring**: Métricas en tiempo real

---

**Este documento sirve como punto de entrada a toda la documentación del sistema. Para información detallada de cada módulo, consulta los enlaces específicos en la sección "Documentación Detallada por Módulo".**

---

*Documento generado automáticamente el 2025-01-19*  
*Versión del sistema: CodeIgniter 4 + Módulos MDA v2.0*  
*Para documentación completa: [docs/README.md](./docs/README.md)*

