# 🚗 Módulo Car Wash - Versión Simplificada

## 📋 **Pestañas Disponibles**

El módulo CarWash incluye **6 pestañas principales**:

### 1. 📊 **Dashboard**
- Vista general con estadísticas
- Resumen de órdenes del día, semana y totales
- Métricas de ingresos

### 2. 📅 **Today (Hoy)**
- Órdenes programadas para hoy
- Estado en tiempo real
- Gestión de prioridades

### 3. 📆 **This Week (Esta Semana)** 
- Vista semanal de órdenes (Lunes a Domingo)
- Planificación semanal
- Distribución de carga de trabajo

### 4. 📋 **All Orders (Todas las Órdenes)**
- Listado completo de órdenes
- Filtros avanzados
- Búsqueda global

### 5. ⚙️ **Services (Servicios)**
- Gestión del catálogo de servicios
- Categorías: Exterior, Interior, Servicio Completo, Detallado, Adicionales
- Precios y duración

### 6. 🗑️ **Deleted (Eliminadas)**
- Órdenes eliminadas (soft delete)
- Capacidad de restaurar
- Eliminación permanente

---

## 🔧 **Funcionalidades Principales**

### **Gestión de Órdenes**
- ✅ Crear nuevas órdenes de car wash
- ✅ Editar órdenes existentes
- ✅ Cambiar estados (Pendiente, Confirmado, En Progreso, Completado, Cancelado)
- ✅ Asignar staff a órdenes
- ✅ Gestión de prioridades (Baja, Normal, Alta, Urgente)

### **Información del Vehículo**
- ✅ Marca, modelo, año, color
- ✅ Placa de matrícula
- ✅ Notas específicas del vehículo

### **Tipos de Servicio**
- ✅ Básico, Premium, Deluxe, Personalizado
- ✅ Selección múltiple de servicios
- ✅ Cálculo automático de precios y tiempo

### **Sistema de Actividades**
- ✅ Registro automático de cambios
- ✅ Historial completo de modificaciones
- ✅ Trazabilidad de acciones

### **Sistema de Comentarios**
- ✅ Comentarios y respuestas
- ✅ Menciones (@usuario)
- ✅ Archivos adjuntos

---

## 📊 **Estructura de Base de Datos**

### **Tablas Principales**
1. `car_wash_orders` - Órdenes principales
2. `car_wash_services` - Catálogo de servicios
3. `car_wash_order_services` - Relación órdenes-servicios
4. `car_wash_activity` - Registro de actividades
5. `car_wash_comments` - Sistema de comentarios

---

## 🚀 **Instalación**

1. **Ejecutar SQL**: Usar `car_wash_tables.sql`
2. **Verificar rutas**: El módulo está incluido en `app/Config/Routes.php`
3. **Menú**: Disponible en sidebar entre "Service Orders" y "Clients"
4. **Acceder**: `http://localhost/mda/car_wash`

---

## 🎯 **Rutas Principales**

```
/car_wash                    - Vista principal
/car_wash/view/{id}         - Ver orden específica
/car_wash/edit/{id}         - Editar orden
/car_wash/getDashboardStats - Estadísticas (AJAX)
/car_wash/getTodayOrders    - Órdenes de hoy (AJAX)
/car_wash/getWeekOrders     - Órdenes de la semana (AJAX)
/car_wash/getAllOrders      - Todas las órdenes (AJAX) 
/car_wash/getServices       - Servicios (AJAX)
/car_wash/getDeletedOrders  - Órdenes eliminadas (AJAX)
```

---

## 📱 **Características de UI**

- ✅ **Diseño responsive** - Funciona en desktop, tablet y móvil
- ✅ **Cards con bordes redondeados** (16px)
- ✅ **Títulos en negrita** (fw-bold)
- ✅ **Filas clickeables** en tablas
- ✅ **DataTables avanzados** con filtros
- ✅ **Modales para formularios**
- ✅ **Sistema de notificaciones**
- ✅ **Carga AJAX** para mejor rendimiento

---

## 🔐 **Seguridad**

- ✅ **Protección CSRF** en formularios
- ✅ **Validación de datos** server-side
- ✅ **Autenticación requerida** para todas las operaciones
- ✅ **Soft deletes** para recuperación de datos
- ✅ **Logs de actividad** para auditoría

---

## 🌐 **Multiidioma**

El módulo incluye traducciones completas en:
- **Inglés** (en)
- **Español** (es) 
- **Portugués** (pt)

Todas las cadenas de texto están externalizadas usando el sistema de idiomas de CodeIgniter.

---

## 🎨 **Integración con Tema**

El módulo está completamente integrado con el tema **Velzon** (Codecanyon):
- Colores y estilos consistentes
- Iconos Feather integrados
- Bootstrap 5 components
- Diseño coherente con el resto de la aplicación

---

## ✅ **Estado del Proyecto**

**Módulo CarWash - COMPLETO Y LISTO PARA PRODUCCIÓN**

- ✅ 6 pestañas implementadas
- ✅ CRUD completo
- ✅ Sistema de comentarios
- ✅ Gestión de servicios  
- ✅ Manejo de errores
- ✅ Responsive design
- ✅ Multiidioma
- ✅ Integración completa

**¡El módulo está listo para usar!** 🎉 