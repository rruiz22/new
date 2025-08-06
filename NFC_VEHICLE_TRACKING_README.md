# 🚗 Sistema de Tracking de Ubicación de Vehículos con NFC

## 📋 Resumen del Sistema

Sistema completo implementado para rastrear la ubicación de vehículos usando **NFC tags** con captura automática de **GPS** en dispositivos móviles y tablets.

## 🏗️ Arquitectura Implementada

### **Base de Datos (3 Tablas Nuevas)**
- `vehicle_location_tokens` - Tokens únicos para cada vehículo
- `parking_spots` - Catálogo de espacios de estacionamiento  
- `vehicle_locations` - Historial completo de ubicaciones

### **Backend (Controller + APIs)**
- `VehicleLocationController.php` - Controller principal
- APIs RESTful para mobile/tablet
- Sistema de validación de tokens seguros

### **Frontend**
- **Interfaz móvil** optimizada para NFC scanning
- **Geolocalización automática** con fallback manual
- **Integración en vehicle view** existente
- **Modal de generación de tokens** con QR codes

## 🔧 Instalación

### 1. **Crear las Tablas de Base de Datos**

Ejecuta este SQL en tu base de datos:

```sql
-- Table for NFC tokens
CREATE TABLE IF NOT EXISTS `vehicle_location_tokens` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vehicle_id` int(11) unsigned DEFAULT NULL,
    `vin_number` varchar(17) NOT NULL,
    `token` varchar(64) NOT NULL UNIQUE,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `vehicle_id` (`vehicle_id`),
    KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for parking spots  
CREATE TABLE IF NOT EXISTS `parking_spots` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `spot_number` varchar(20) NOT NULL,
    `zone` varchar(50) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `spot_number` (`spot_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for location history
CREATE TABLE IF NOT EXISTS `vehicle_locations` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `vehicle_id` int(11) unsigned DEFAULT NULL,
    `vin_number` varchar(17) NOT NULL,
    `spot_id` int(11) unsigned DEFAULT NULL,
    `spot_number` varchar(20) NOT NULL,
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    `accuracy` float DEFAULT NULL,
    `device_info` json DEFAULT NULL,
    `user_id` int(11) unsigned DEFAULT NULL,
    `user_name` varchar(100) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `token_used` varchar(64) DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `vehicle_id` (`vehicle_id`),
    KEY `vin_number` (`vin_number`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 2. **Archivos Ya Creados**
✅ `app/Controllers/VehicleLocationController.php`  
✅ `app/Views/location/mobile_tracker.php`  
✅ `app/Views/location/invalid_token.php`  
✅ `app/Config/Routes.php` (rutas agregadas)  
✅ `app/Modules/Vehicles/Views/vehicles/view.php` (integración)

## 🚀 Cómo Usar el Sistema

### **1. Generar Token NFC para un Vehículo**

1. Ve a la vista de cualquier vehículo: `/vehicles/{vin_last6}`
2. En la sección "Location History", haz clic en **"Generate NFC Token"**
3. Se abrirá un modal con:
   - **URL del NFC**: `http://localhost/mda/location/{token}`
   - **Código QR** para testing
   - **Botón de test** para probar la interfaz móvil

### **2. Programar el NFC Tag**

1. Usa una app de NFC (como **NFC Tools** o **TagWriter**)
2. Copia la URL generada: `http://localhost/mda/location/{token}`
3. Programa el NFC tag con esta URL
4. Pega el tag en el vehículo

### **3. Usar el Sistema (Mobile/Tablet)**

1. **Acerca el dispositivo** al NFC tag
2. **Se abre automáticamente** la interfaz móvil
3. **GPS se captura automáticamente** (o permite entrada manual)
4. **Ingresa el número de spot** (ej: A-15, B-23, 105)
5. **Opcional**: Tu nombre y notas
6. **Presiona "Save Location"** - ¡Listo!

## 📱 URLs del Sistema

### **Interfaz Principal**
- `http://localhost/mda/location/{token}` - Interfaz móvil para NFC

### **APIs Disponibles**
- `POST /api/location/save` - Guardar ubicación
- `GET /api/location/history/{vin}` - Historial de ubicaciones
- `GET /api/location/generate/{vin}` - Generar token NFC

### **Integración**
- `/vehicles/{vin_last6}` - Vista de vehículo con location history

## 🔒 Características de Seguridad

- **Tokens únicos** de 64 caracteres por vehículo
- **Validación de tokens** en cada request
- **Logging completo** de device info y IPs
- **Tokens reutilizables** (no se regeneran si ya existen)

## 📊 Datos Capturados

### **Por cada ubicación:**
- ✅ **Coordenadas GPS** (latitud/longitud)
- ✅ **Precisión** del GPS (±metros)
- ✅ **Número de spot** de estacionamiento
- ✅ **Fecha/hora** exacta
- ✅ **Usuario** que registró (opcional)
- ✅ **Notas** adicionales (opcional)
- ✅ **Info del dispositivo** (IP, user agent)
- ✅ **Token usado** (auditoría)

## 🎨 Interfaz Móvil Features

- 📱 **Responsive design** optimizado para mobile/tablet
- 🎯 **Auto-captura GPS** al cargar la página
- ⚡ **Interfaz rápida** - solo spot number requerido
- 🔄 **Retry automático** si falla el GPS
- ✅ **Feedback visual** en tiempo real
- 📍 **Muestra coordenadas** capturadas
- 📝 **Historial reciente** del vehículo
- 🎨 **UI moderna** con gradientes y animaciones

## 📈 Vista de Historial

En la vista de vehículo (`/vehicles/{vin}`):

- 📋 **Tabla completa** de ubicaciones históricas
- 🏷️ **Spots con badges** coloreados
- 👤 **Usuario** que registró cada ubicación
- 📅 **Fecha/hora** formateada
- 📝 **Notas** de cada registro
- 🎯 **Coordenadas precisas** con accuracy
- 🔄 **Auto-refresh** y retry en errores

## 🛠️ Casos de Uso

### **Ejemplo 1: Taller Automotriz**
1. Pega NFC tag en cada vehículo que ingresa
2. Staff escanea tag al mover vehículo
3. Registra spot (ej: "A-15", "Wash Bay 2")
4. Manager ve historial completo en sistema

### **Ejemplo 2: Concesionario**
1. Vehículos en inventario tienen NFC tags
2. Vendedores escanean al mostrar vehículos
3. Registran ubicación después de test drives
4. Gerencia tiene tracking completo

### **Ejemplo 3: Fleet Management**
1. Cada vehículo de flota tiene token NFC
2. Conductores registran donde parquean
3. Seguimiento automático con GPS
4. Reportes de ubicaciones históricas

## 🔧 Personalización

### **Agregar Más Spots**
```sql
INSERT INTO parking_spots (spot_number, zone, description, created_at, updated_at) 
VALUES ('D-01', 'Zone D', 'Nueva área', NOW(), NOW());
```

### **Modificar Interfaz Móvil**
Edita: `app/Views/location/mobile_tracker.php`

### **Cambiar Validaciones**  
Modifica: `app/Controllers/VehicleLocationController.php`

## 🎯 Beneficios del Sistema

- ⚡ **Rápido**: Scan NFC → GPS automático → Save (30 segundos)
- 📱 **Móvil-first**: Optimizado para smartphones/tablets
- 🔐 **Seguro**: Tokens únicos, validaciones, logging
- 📊 **Completo**: Historial detallado con geolocalización
- 🔄 **Integrado**: Funciona con sistema de vehículos existente
- 🎨 **Moderno**: UI atractiva y profesional

## 🚨 Próximos Pasos Recomendados

1. **Crear las tablas** ejecutando el SQL
2. **Generar tu primer token** en un vehículo existente
3. **Probar la interfaz móvil** usando el QR code
4. **Programar un NFC tag** físico para testing
5. **Agregar spots** específicos de tu ubicación

¡**Sistema completo y listo para usar!** 🎉 