# 🗄️ Guía para Migraciones Limpias de Base de Datos MDA

## 📋 **Resumen de la Estructura de Base de Datos**

Esta guía te permitirá recrear toda la base de datos desde cero con migraciones limpias, basada en la estructura actual extraída directamente de la base de datos de producción.

---

## 🏗️ **Orden de Creación de Migraciones**

### **1. Tablas Base (Usuarios y Autenticación)**
```bash
# CodeIgniter Shield - Se instala automáticamente
php spark shield:setup

# Tabla de clientes (dealerships)
php spark make:migration CreateClientsTable

# Tabla de contactos (empleados de dealerships)  
php spark make:migration CreateContactsTable

# Tabla de usuarios personalizada (extiende Shield)
php spark make:migration ExtendUsersTable
```

### **2. Sistemas de Roles y Permisos**
```bash
php spark make:migration CreateCustomRolesTable
php spark make:migration CreateContactGroupsTable
php spark make:migration CreateContactPermissionsTable
```

### **3. Módulos de Órdenes (Core Business)**
```bash
# Sales Orders
php spark make:migration CreateSalesOrdersTables
php spark make:migration CreateSalesOrdersActivitiesTable
php spark make:migration CreateSalesOrdersCommentsTable
php spark make:migration CreateSalesOrdersFollowersTable

# Service Orders  
php spark make:migration CreateServiceOrdersTables
php spark make:migration CreateServiceOrdersActivitiesTable
php spark make:migration CreateServiceOrdersCommentsTable
php spark make:migration CreateServiceOrdersNotesTable

# Car Wash Orders
php spark make:migration CreateCarWashTables
php spark make:migration CreateCarWashNotesTable

# Recon Orders
php spark make:migration CreateReconOrdersTables
php spark make:migration CreateReconNotesTable
php spark make:migration CreateReconVehiclesTable
```

### **4. Sistemas de Comunicación**
```bash
# Chat en tiempo real
php spark make:migration CreateChatTables

# SMS Bidireccional
php spark make:migration CreateSMSConversationsTable

# Notas internas
php spark make:migration CreateInternalNotesTable
```

### **5. Sistema de Vehículos y Ubicación**
```bash
php spark make:migration CreateVehicleLocationTables
php spark make:migration CreateVehicleShortlinksTable
```

### **6. Páginas Públicas y CMS**
```bash
php spark make:migration CreatePublicPagesTable
```

### **7. Configuraciones y Auditoría**
```bash
php spark make:migration CreateSettingsTable
php spark make:migration CreateAuditTrailTable
php spark make:migration CreateTodosTable
php spark make:migration CreateIntegrationSettingsTable
```

---

## 📝 **Estructura Detallada por Tabla**

### **Tabla `users` (Extendida de CodeIgniter Shield)**
```php
<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class ExtendUsersTable extends Migration
{
    public function up()
    {
        // Agregar campos personalizados a la tabla users de Shield
        $fields = [
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'unique' => true
            ],
            'user_type' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'manager', 'staff', 'client'],
                'default' => 'client'
            ],
            'role_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'client_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'status_message' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'last_active' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ]
        ];
        
        $this->forge->addColumn('users', $fields);
    }
    
    public function down()
    {
        $this->forge->dropColumn('users', ['username', 'user_type', 'role_id', 'client_id', 'status', 'status_message', 'last_active', 'deleted']);
    }
}
```

### **Tabla `clients` (Dealerships)**
```php
<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateClientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'tax_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->createTable('clients');
    }
    
    public function down()
    {
        $this->forge->dropTable('clients');
    }
}
```

### **Tabla `contacts` (Empleados de Dealerships)**
```php
<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateContactsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'is_primary' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('client_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contacts');
    }
    
    public function down()
    {
        $this->forge->dropTable('contacts');
    }
}
```

---

## 🔄 **Proceso de Migración Limpia**

### **Paso 1: Preparación**
```bash
# Hacer backup de la base de datos actual
mysqldump -h 35.212.30.157 -u u9jvaasruh9vc -p'lalinha01?' dbuc0youbm7qp9 > backup_mda_$(date +%Y%m%d).sql

# Crear base de datos limpia (opcional)
mysql -h 35.212.30.157 -u u9jvaasruh9vc -p'lalinha01?' -e "CREATE DATABASE dbuc0youbm7qp9_clean;"
```

### **Paso 2: Limpiar Migraciones Existentes**
```bash
# Eliminar migraciones corruptas
rm -rf app/Database/Migrations/*

# Resetear tabla de migraciones
mysql -h 35.212.30.157 -u u9jvaasruh9vc -p'lalinha01?' -D dbuc0youbm7qp9 -e "DELETE FROM migrations;"
```

### **Paso 3: Instalar CodeIgniter Shield**
```bash
composer require codeigniter4/shield
php spark shield:setup
```

### **Paso 4: Crear Migraciones Limpias**
```bash
# Crear todas las migraciones en el orden correcto
# (Usar los ejemplos de código de arriba)

# Ejecutar migraciones
php spark migrate
```

### **Paso 5: Migrar Datos**
```bash
# Crear script de migración de datos
php spark make:command MigrateData

# Ejecutar migración de datos desde backup
php spark migrate:data
```

---

## 📊 **Validación Post-Migración**

### **Verificar Estructura**
```sql
-- Contar tablas
SELECT COUNT(*) as total_tables 
FROM information_schema.tables 
WHERE table_schema = 'dbuc0youbm7qp9';

-- Verificar tablas principales
SHOW TABLES LIKE '%orders';
SHOW TABLES LIKE 'auth_%';
SHOW TABLES LIKE '%activities';
```

### **Verificar Datos**
```sql
-- Verificar usuarios
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM clients;
SELECT COUNT(*) FROM contacts;

-- Verificar órdenes
SELECT 
    (SELECT COUNT(*) FROM sales_orders) as sales,
    (SELECT COUNT(*) FROM service_orders) as service,
    (SELECT COUNT(*) FROM car_wash_orders) as carwash,
    (SELECT COUNT(*) FROM recon_orders) as recon;
```

---

## ⚠️ **Consideraciones Importantes**

### **Dependencias de Tablas**
1. **Primero**: `users`, `clients`, `contacts`
2. **Segundo**: Tablas de órdenes principales
3. **Tercero**: Tablas de actividades y comentarios
4. **Último**: Configuraciones y auditoría

### **Campos Comunes en Todas las Tablas**
```php
'created_at' => ['type' => 'DATETIME', 'null' => true],
'updated_at' => ['type' => 'DATETIME', 'null' => true], 
'deleted_at' => ['type' => 'DATETIME', 'null' => true],
'deleted' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
```

### **Índices Recomendados**
- Primary keys en todas las tablas
- Foreign keys para relaciones
- Índices en campos de estado y fechas
- Índices compuestos para consultas frecuentes

---

## 🚀 **Script de Automatización**

```bash
#!/bin/bash
# migrate-clean.sh

echo "🗄️ Iniciando migración limpia de MDA Database..."

# Backup
echo "📦 Creando backup..."
mysqldump -h 35.212.30.157 -u u9jvaasruh9vc -p'lalinha01?' dbuc0youbm7qp9 > "backup_mda_$(date +%Y%m%d_%H%M%S).sql"

# Limpiar migraciones
echo "🧹 Limpiando migraciones existentes..."
rm -rf app/Database/Migrations/*_*.php

# Instalar Shield
echo "🛡️ Instalando CodeIgniter Shield..."
php spark shield:setup

# Crear migraciones base
echo "🏗️ Creando migraciones base..."
php spark make:migration CreateClientsTable
php spark make:migration CreateContactsTable
php spark make:migration ExtendUsersTable

# Ejecutar migraciones
echo "⚡ Ejecutando migraciones..."
php spark migrate

echo "✅ Migración limpia completada!"
```

---

**Esta guía te permitirá recrear completamente la base de datos con migraciones limpias y sin corrupciones. Asegúrate de seguir el orden de creación y validar cada paso.**

