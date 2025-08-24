# 🔗 APIs & Integrations - Documentación Completa

## 📋 **Información General**

Esta documentación detalla todas las APIs internas, externas e integraciones del sistema MDA, incluyendo endpoints, autenticación, rate limiting y ejemplos de uso.

---

## 🌐 **APIs Internas del Sistema**

### **API REST Principal**
**Base URL**: `https://yourdomain.com/api/v1`

#### **Autenticación**
```php
// Bearer Token Authentication
Authorization: Bearer {jwt_token}

// API Key Authentication (para integraciones)
X-API-Key: {api_key}
```

#### **Endpoints Principales**

##### **Sales Orders API**
```php
GET    /api/v1/sales-orders                 // Lista de órdenes
POST   /api/v1/sales-orders                 // Crear orden
GET    /api/v1/sales-orders/{id}           // Detalles de orden
PUT    /api/v1/sales-orders/{id}           // Actualizar orden
DELETE /api/v1/sales-orders/{id}           // Eliminar orden
GET    /api/v1/sales-orders/{id}/pdf       // Generar PDF
POST   /api/v1/sales-orders/{id}/qr        // Generar QR
GET    /api/v1/sales-orders/{id}/activities // Actividades
POST   /api/v1/sales-orders/{id}/comments   // Agregar comentario
```

##### **Service Orders API**
```php
GET    /api/v1/service-orders               // Lista de órdenes de servicio
POST   /api/v1/service-orders               // Crear orden de servicio
GET    /api/v1/service-orders/{id}         // Detalles
PUT    /api/v1/service-orders/{id}/status  // Cambiar estado
POST   /api/v1/service-orders/{id}/notes   // Agregar nota
GET    /api/v1/service-orders/{id}/followers // Lista de seguidores
POST   /api/v1/service-orders/{id}/followers // Agregar seguidor
```

##### **Vehicles API**
```php
GET    /api/v1/vehicles                     // Lista de vehículos
GET    /api/v1/vehicles/{vin}              // Detalles por VIN
GET    /api/v1/vehicles/{vin}/orders       // Órdenes del vehículo
GET    /api/v1/vehicles/{vin}/location     // Ubicación actual
POST   /api/v1/vehicles/{vin}/location     // Actualizar ubicación
GET    /api/v1/vehicles/analytics          // Analytics de vehículos
```

---

## 🔐 **APIs Públicas**

### **Public Data API**
**Base URL**: `https://yourdomain.com/api/public`
**Autenticación**: No requerida (con rate limiting)

#### **Endpoints Públicos**
```php
GET /api/public/inventory       // Inventario público
GET /api/public/orders          // Estadísticas de órdenes
GET /api/public/stats           // Métricas generales
GET /api/public/services        // Servicios disponibles
```

#### **Ejemplo de Respuesta - Inventario**
```json
{
    "success": true,
    "data": {
        "total_vehicles": 150,
        "available_vehicles": 45,
        "vehicles": [
            {
                "id": "Y13173",
                "make": "BMW",
                "model": "X5",
                "year": 2023,
                "color": "White",
                "status": "available",
                "price_range": "$50,000 - $60,000",
                "location": "Main Lot",
                "features": ["AWD", "Premium Package", "Navigation"],
                "images": ["https://cdn.example.com/vehicle1.jpg"],
                "contact_info": {
                    "phone": "+1-555-0123",
                    "email": "sales@dealership.com"
                }
            }
        ],
        "filters": {
            "makes": ["BMW", "Mercedes", "Audi", "Lexus"],
            "years": [2020, 2021, 2022, 2023, 2024],
            "price_ranges": ["$20k-$30k", "$30k-$40k", "$40k-$50k", "$50k+"]
        }
    },
    "meta": {
        "total": 45,
        "page": 1,
        "per_page": 20,
        "last_updated": "2025-01-19T14:30:00Z"
    }
}
```

---

## 📱 **Integraciones Externas**

### **1. Twilio SMS Integration**

#### **Configuración**
```php
// .env Configuration
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_PHONE_NUMBER=+15551234567
TWILIO_WEBHOOK_URL=https://yourdomain.com/sms/webhook
```

#### **Funcionalidades**
- ✅ **SMS Bidireccional**: Envío y recepción de mensajes
- ✅ **Templates Dinámicos**: Plantillas por módulo
- ✅ **Webhooks**: Recepción automática de respuestas
- ✅ **Historial Completo**: Almacenamiento de conversaciones
- ✅ **Notificaciones**: Alertas automáticas

#### **API Usage**
```php
// Enviar SMS
POST /api/v1/sms/send
{
    "to": "+15551234567",
    "message": "Su orden #SO-001 está lista para recoger.",
    "template": "order_ready",
    "module": "sales_orders",
    "module_id": 123
}

// Webhook para recibir respuestas
POST /sms/webhook
{
    "MessageSid": "SMxxxxxxxxxxxxx",
    "From": "+15551234567",
    "To": "+15559876543",
    "Body": "Gracias, estaré ahí en 30 minutos"
}
```

### **2. AWS S3 Integration**

#### **Configuración**
```php
// .env Configuration
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=mda-system-files
AWS_USE_PATH_STYLE_ENDPOINT=false
```

#### **Funcionalidades**
- ✅ **File Upload**: Subida automática de archivos
- ✅ **Presigned URLs**: URLs temporales para acceso seguro
- ✅ **Automatic Cleanup**: Limpieza de archivos temporales
- ✅ **Organized Structure**: Organización por módulo/fecha
- ✅ **CDN Integration**: CloudFront para distribución

#### **Estructura de Archivos**
```
s3://mda-system-files/
├── sales-orders/
│   ├── 2025/01/
│   │   ├── attachments/
│   │   ├── pdfs/
│   │   └── thumbnails/
├── service-orders/
│   ├── 2025/01/
│   │   ├── photos/
│   │   ├── documents/
│   │   └── reports/
└── public-pages/
    ├── images/
    ├── media/
    └── uploads/
```

### **3. MDA.to Links (Short URLs)**

#### **Configuración**
```php
// .env Configuration
LIMA_API_KEY=your_lima_api_key
LIMA_BRANDED_DOMAIN=mda.to
LIMA_DEFAULT_EXPIRY=365 // días
```

#### **Funcionalidades**
- ✅ **Short URLs**: URLs cortas personalizadas
- ✅ **5-Digit Slugs**: Slugs únicos de 5 caracteres
- ✅ **QR Integration**: Generación automática de QR codes
- ✅ **Analytics**: Tracking de clicks y usage
- ✅ **Custom Domain**: Dominio personalizado mda.to

#### **API Usage**
```php
// Crear URL corta
POST https://api.mda.to/v1/links
{
    "url": "https://yourdomain.com/sales_orders/view/123",
    "slug": "SO123",
    "expires_at": "2025-12-31T23:59:59Z"
}

// Respuesta
{
    "success": true,
    "data": {
        "id": "link_12345",
        "short_url": "https://mda.to/SO123",
        "original_url": "https://yourdomain.com/sales_orders/view/123",
        "slug": "SO123",
        "clicks": 0,
        "created_at": "2025-01-19T14:30:00Z"
    }
}
```

### **4. Pusher (Real-time Notifications)**

#### **Configuración**
```php
// .env Configuration
PUSHER_APP_ID=123456
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=us2
```

#### **Channels y Events**
```javascript
// Channels disponibles
const channels = {
    'orders': 'Canal global de órdenes',
    'user-{user_id}': 'Canal privado por usuario',
    'order-{order_id}': 'Canal específico por orden',
    'chat-{conversation_id}': 'Canal de chat'
};

// Events disponibles
const events = {
    'order-created': 'Nueva orden creada',
    'order-updated': 'Orden actualizada',
    'status-changed': 'Estado cambiado',
    'new-comment': 'Nuevo comentario',
    'new-message': 'Nuevo mensaje de chat',
    'notification': 'Notificación general'
};
```

### **5. Google APIs Integration**

#### **Configuración**
```php
// .env Configuration
GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

#### **Funcionalidades Implementadas**
- ✅ **OAuth Authentication**: Login con Google
- ✅ **Drive Integration**: Backup automático (futuro)
- ✅ **Maps Integration**: Geocoding y mapas (futuro)
- ✅ **Calendar Integration**: Sincronización de citas (futuro)

---

## 🔒 **Seguridad de APIs**

### **Rate Limiting**
```php
// Límites por endpoint
$rateLimits = [
    'public_api' => '100 requests/hour/IP',
    'authenticated_api' => '1000 requests/hour/user',
    'admin_api' => '5000 requests/hour/user',
    'webhook_endpoints' => '1000 requests/hour/IP'
];
```

### **Autenticación y Autorización**
```php
// JWT Token Structure
{
    "sub": "user_id",
    "iat": 1642678800,
    "exp": 1642765200,
    "roles": ["admin", "manager"],
    "permissions": ["orders:read", "orders:write"],
    "client_id": 123
}

// API Key Structure
{
    "key": "ak_live_123456789abcdef",
    "name": "Mobile App Integration",
    "permissions": ["orders:read", "vehicles:read"],
    "rate_limit": "500/hour",
    "expires_at": "2025-12-31T23:59:59Z"
}
```

### **Validación de Datos**
```php
// Ejemplo de validación para crear orden
$validation_rules = [
    'client_id' => 'required|integer|exists:clients,id',
    'vehicle' => 'required|string|max:255',
    'service_id' => 'required|integer|exists:sales_orders_services,id',
    'date' => 'required|date|after:today',
    'notes' => 'nullable|string|max:1000'
];
```

---

## 📡 **Webhooks System**

### **Webhook Endpoints**
```php
// Webhooks disponibles para integraciones
$webhooks = [
    'order.created' => 'POST /webhooks/order-created',
    'order.updated' => 'POST /webhooks/order-updated',
    'order.completed' => 'POST /webhooks/order-completed',
    'payment.received' => 'POST /webhooks/payment-received',
    'vehicle.location_updated' => 'POST /webhooks/vehicle-location'
];
```

### **Webhook Payload Example**
```json
{
    "event": "order.created",
    "timestamp": "2025-01-19T14:30:00Z",
    "data": {
        "order_id": 123,
        "order_number": "SO-001",
        "client_id": 1,
        "status": "pending",
        "total_amount": 1250.00,
        "created_at": "2025-01-19T14:30:00Z"
    },
    "signature": "sha256=abcdef123456..."
}
```

### **Webhook Security**
```php
// Verificación de firma
function verifyWebhookSignature($payload, $signature, $secret) {
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    return hash_equals($expected, $signature);
}
```

---

## 🔄 **API Versioning**

### **Estrategia de Versionado**
- **URL Versioning**: `/api/v1/`, `/api/v2/`
- **Header Versioning**: `Accept: application/vnd.mda.v1+json`
- **Backward Compatibility**: Soporte de versiones anteriores por 12 meses
- **Deprecation Warnings**: Headers de advertencia para versiones obsoletas

### **Changelog API**
```php
// v1.0 - Initial release
- Basic CRUD operations
- Authentication with JWT
- Rate limiting

// v1.1 - Enhanced features
- Webhook support
- File upload endpoints
- Advanced filtering

// v1.2 - Real-time features
- WebSocket integration
- Push notifications
- Live updates

// v2.0 - Major update (En desarrollo)
- GraphQL support
- Enhanced security
- Performance improvements
```

---

## 📊 **API Analytics y Monitoring**

### **Métricas Recopiladas**
```php
$api_metrics = [
    'requests_per_minute' => 'Requests por minuto',
    'response_times' => 'Tiempos de respuesta promedio',
    'error_rates' => 'Tasa de errores por endpoint',
    'top_endpoints' => 'Endpoints más utilizados',
    'user_activity' => 'Actividad por usuario/API key',
    'geographic_distribution' => 'Distribución geográfica',
    'device_types' => 'Tipos de dispositivos'
];
```

### **Health Check Endpoints**
```php
GET /api/health                 // Estado general del sistema
GET /api/health/database        // Estado de la base de datos
GET /api/health/redis           // Estado del cache
GET /api/health/storage         // Estado del almacenamiento
GET /api/health/external        // Estado de servicios externos
```

### **Response Format**
```json
{
    "status": "healthy",
    "timestamp": "2025-01-19T14:30:00Z",
    "version": "2.0.1",
    "uptime": 86400,
    "checks": {
        "database": {
            "status": "healthy",
            "response_time": "2ms"
        },
        "redis": {
            "status": "healthy",
            "response_time": "1ms"
        },
        "external_apis": {
            "twilio": "healthy",
            "aws_s3": "healthy",
            "pusher": "degraded"
        }
    }
}
```

---

## 🛠️ **SDKs y Libraries**

### **Official SDKs**
```php
// PHP SDK
composer require mda/php-sdk

// JavaScript SDK
npm install @mda/js-sdk

// Python SDK (En desarrollo)
pip install mda-sdk
```

### **Ejemplo de Uso - PHP SDK**
```php
use MDA\Client;

$mda = new Client([
    'api_key' => 'your_api_key',
    'base_url' => 'https://yourdomain.com/api/v1'
]);

// Crear orden
$order = $mda->salesOrders->create([
    'client_id' => 1,
    'vehicle' => '2023 BMW X5',
    'service_id' => 5,
    'date' => '2025-01-20'
]);

// Obtener órdenes
$orders = $mda->salesOrders->list([
    'status' => 'pending',
    'limit' => 50
]);
```

### **Ejemplo de Uso - JavaScript SDK**
```javascript
import MDA from '@mda/js-sdk';

const mda = new MDA({
    apiKey: 'your_api_key',
    baseURL: 'https://yourdomain.com/api/v1'
});

// Crear orden
const order = await mda.salesOrders.create({
    client_id: 1,
    vehicle: '2023 BMW X5',
    service_id: 5,
    date: '2025-01-20'
});

// Suscribirse a actualizaciones en tiempo real
mda.subscribe('orders', (event) => {
    console.log('Nueva actualización:', event);
});
```

---

## 🚀 **Performance y Optimización**

### **Caching Strategy**
```php
// Cache layers
$cache_layers = [
    'Application Cache' => 'Redis - 1 hour TTL',
    'Database Query Cache' => 'MySQL Query Cache',
    'API Response Cache' => 'Redis - 5 minutes TTL',
    'CDN Cache' => 'CloudFront - 24 hours TTL'
];
```

### **Database Optimization**
- **Connection Pooling**: Pool de conexiones para mejor performance
- **Read Replicas**: Distribución de carga de lectura
- **Índices Optimizados**: Índices específicos para queries de API
- **Query Optimization**: Queries optimizadas con EXPLAIN

### **API Response Optimization**
- **Pagination**: Paginación eficiente para listas grandes
- **Field Selection**: Selección de campos específicos
- **Compression**: Compresión GZIP automática
- **Lazy Loading**: Carga bajo demanda de relaciones

---

## 🔮 **Roadmap de APIs**

### **Próximas Funcionalidades**
- [ ] **GraphQL API**: Queries más flexibles
- [ ] **WebSocket API**: Comunicación bidireccional
- [ ] **Bulk Operations**: Operaciones en lote
- [ ] **Advanced Search**: Búsqueda con Elasticsearch
- [ ] **API Gateway**: Gateway centralizado

### **Nuevas Integraciones**
- [ ] **Stripe**: Procesamiento de pagos
- [ ] **QuickBooks**: Integración contable
- [ ] **Mailchimp**: Marketing automation
- [ ] **Slack**: Notificaciones de equipo
- [ ] **Microsoft Graph**: Integración con Office 365

---

**El sistema de APIs e integraciones de MDA está diseñado para ser escalable, seguro y fácil de usar, proporcionando una base sólida para el crecimiento del sistema.**

---

*Documentación actualizada: 2025-01-19*  
*Versión de APIs: MDA v2.0*  
*Para más información: [Volver a Documentación Principal](../../claude.md)*


