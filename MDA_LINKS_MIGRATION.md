# 🔗 Migración Lima Links → MDA Links

## 📋 Resumen

Se ha actualizado la integración de **Lima Links** a **MDA Links** en todo el sistema MDA. Los cambios mantienen compatibilidad hacia atrás para evitar interrupciones.

## ✅ Cambios Realizados

### 1. **Helpers Actualizados**
- ✅ `LimaLinksHelper` → Referencias actualizadas a `MDALinksHelper`
- ✅ `MDALinksHelper` actualizado con compatibilidad hacia atrás
- ✅ Endpoints API actualizados a mda.to

### 2. **Controladores Actualizados**
- ✅ `SettingsController.php`
- ✅ `ServiceOrdersController.php`
- ✅ `ReconOrdersController.php`
- ✅ `CarWashController.php`
- ✅ `SalesOrders/view.php`

### 3. **Frontend Actualizado**
- ✅ Formulario de configuraciones (`settings/index.php`)
- ✅ Funciones JavaScript: `testMDALinks()`, `showMDALinksInfo()`
- ✅ Campos de formulario: `mda_api_key`, `mda_branded_domain`
- ✅ Labels y textos actualizados

### 4. **Base de Datos**
- ✅ Script de migración SQL creado
- ✅ Los campos `lima_link_id` en tablas permanecen (no cambiar por ahora)
- ✅ Configuraciones soportan ambos nombres (lima_* y mda_*)

## 🔧 Configuraciones

### Nuevos Nombres de Configuración:
- `lima_api_key` → `mda_api_key`
- `lima_api_base_url` → `mda_api_base_url` 
- `lima_branded_domain` → `mda_branded_domain`

### Compatibilidad Hacia Atrás:
- ✅ El sistema busca primero las configuraciones `mda_*`
- ✅ Si no existen, usa las configuraciones `lima_*`
- ✅ Funciones antiguas siguen funcionando (deprecated)

## 📊 Campos de Base de Datos

### Campos que NO se cambian (por compatibilidad):
```sql
-- Estos campos mantienen el nombre lima_link_id por ahora
sales_orders.lima_link_id
service_orders.lima_link_id
car_wash_orders.lima_link_id
recon_orders.lima_link_id
```

## 🚀 Migración

### 1. Ejecutar Script SQL
```bash
mysql -u username -p database_name < migrate_lima_to_mda.sql
```

### 2. Actualizar Configuraciones
1. Ir a **Configuraciones → MDA Links**
2. Ingresar nueva API key de mda.to
3. Probar conexión con botón "Test Connection"
4. Guardar configuraciones

### 3. Verificar Funcionalidad
- ✅ Short URLs funcionando
- ✅ QR codes generándose correctamente
- ✅ Prueba de conexión exitosa

## 🔗 Endpoints API

### Nuevas URLs:
- **Base URL**: `https://mda.to`
- **API**: `https://mda.to/api/url/add`
- **QR**: `https://mda.to/qr/{linkId}?size=300&format=png`

### Compatibilidad:
- ✅ Funciona con dominio personalizado
- ✅ Mantiene todas las funciones existentes
- ✅ Misma estructura de respuesta API

## ⚠️ Notas Importantes

1. **Los campos `lima_link_id` en base de datos NO se cambian** para mantener compatibilidad
2. **Las configuraciones antiguas siguen funcionando** como respaldo
3. **Todos los short links existentes siguen funcionando**
4. **La migración es transparente para los usuarios**

## 🧪 Testing

### Elementos a Probar:
1. ✅ Configuración de API key en Settings
2. ✅ Test de conexión MDA Links
3. ✅ Generación de short URLs en órdenes
4. ✅ Generación de códigos QR
5. ✅ Funcionamiento de links existentes

---

**Migración completada exitosamente** ✅  
**Fecha**: 2025-01-19  
**Versión**: MDA Sistema v2.0