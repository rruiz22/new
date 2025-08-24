# ⚡ Workflows por Módulo - Documentación Completa

## 📋 **Información General**

Esta documentación detalla los flujos de trabajo operativos de cada módulo del sistema MDA, proporcionando una guía paso a paso para usuarios, managers y administradores.

---

## 🛒 **Sales Orders - Workflows**

### **Workflow Principal: Gestión de Órdenes de Venta**

#### **1. Creación de Orden**
```mermaid
flowchart TD
    A[Cliente solicita servicio] --> B[Staff crea orden]
    B --> C[Seleccionar cliente existente o crear nuevo]
    C --> D[Ingresar información del vehículo]
    D --> E[Seleccionar servicios necesarios]
    E --> F[Programar fecha y hora]
    F --> G[Asignar personal responsable]
    G --> H[Generar número de orden]
    H --> I[Crear URL corta y QR code]
    I --> J[Enviar confirmación por email/SMS]
    J --> K[Orden creada - Estado: Pendiente]
```

**Roles Involucrados:**
- **Staff/Manager**: Crea la orden
- **Cliente**: Proporciona información
- **Sistema**: Genera automáticamente número, URL y QR

**Tiempo Estimado:** 5-10 minutos

#### **2. Confirmación y Programación**
```mermaid
flowchart TD
    A[Orden en estado Pendiente] --> B[Revisar disponibilidad de recursos]
    B --> C{¿Recursos disponibles?}
    C -->|Sí| D[Confirmar orden]
    C -->|No| E[Reprogramar o asignar otros recursos]
    E --> B
    D --> F[Cambiar estado a Confirmado]
    F --> G[Notificar al cliente]
    G --> H[Notificar al personal asignado]
    H --> I[Agregar a calendario de trabajo]
```

**Responsable:** Manager o Staff Senior
**Tiempo Estimado:** 2-5 minutos

#### **3. Ejecución del Servicio**
```mermaid
flowchart TD
    A[Día de la cita] --> B[Personal revisa orden]
    B --> C[Cambiar estado a En Progreso]
    C --> D[Realizar servicios asignados]
    D --> E[Documentar progreso en comentarios]
    E --> F[Subir fotos si es necesario]
    F --> G{¿Servicios completados?}
    G -->|No| H[Continuar trabajo]
    H --> D
    G -->|Sí| I[Control de calidad]
    I --> J{¿Pasa QC?}
    J -->|No| K[Corregir problemas]
    K --> D
    J -->|Sí| L[Cambiar estado a Completado]
    L --> M[Generar factura/recibo]
    M --> N[Notificar al cliente]
```

**Responsables:** Personal asignado + Supervisor
**Tiempo Estimado:** Según tipo de servicio (30 min - 8 horas)

### **Workflow de Emergencia: Orden Urgente**
```mermaid
flowchart TD
    A[Solicitud urgente] --> B[Manager evalúa urgencia]
    B --> C{¿Realmente urgente?}
    C -->|No| D[Proceso normal]
    C -->|Sí| E[Crear orden con prioridad URGENTE]
    E --> F[Reasignar recursos si es necesario]
    F --> G[Notificar a todo el equipo]
    G --> H[Seguimiento cada hora]
    H --> I[Completar en tiempo récord]
```

---

## 🔧 **Service Orders - Workflows**

### **Workflow Principal: Órdenes de Servicio Técnico**

#### **1. Recepción del Vehículo**
```mermaid
flowchart TD
    A[Vehículo llega al taller] --> B[Recepcionista crea orden]
    B --> C[Inspección visual inicial]
    C --> D[Documentar condición actual]
    D --> E[Tomar fotos del vehículo]
    E --> F[Registrar queja del cliente]
    F --> G[Asignar técnico para diagnóstico]
    G --> H[Estado: Recibido]
    H --> I[Entregar llaves y documentos]
    I --> J[Cliente recibe número de orden]
```

**Responsable:** Recepcionista + Service Advisor
**Tiempo Estimado:** 15-20 minutos

#### **2. Diagnóstico Técnico**
```mermaid
flowchart TD
    A[Técnico recibe vehículo] --> B[Cambiar estado a Diagnosticando]
    B --> C[Realizar inspección técnica]
    C --> D[Usar herramientas de diagnóstico]
    D --> E[Identificar problemas]
    E --> F[Documentar hallazgos en notas]
    F --> G[Estimar costos de reparación]
    G --> H{¿Requiere aprobación cliente?}
    H -->|Sí| I[Crear cotización]
    H -->|No| J[Proceder con reparación]
    I --> K[Enviar cotización al cliente]
    K --> L[Esperar aprobación]
    L --> M{¿Cliente aprueba?}
    M -->|No| N[Devolver vehículo]
    M -->|Sí| J
```

**Responsable:** Técnico asignado
**Tiempo Estimado:** 30 minutos - 2 horas

#### **3. Proceso de Reparación**
```mermaid
flowchart TD
    A[Iniciar reparación] --> B[Cambiar estado a En Reparación]
    B --> C{¿Repuestos disponibles?}
    C -->|No| D[Cambiar estado a Esperando Repuestos]
    D --> E[Ordenar repuestos necesarios]
    E --> F[Esperar llegada de repuestos]
    F --> C
    C -->|Sí| G[Realizar reparaciones]
    G --> H[Documentar trabajo realizado]
    H --> I[Actualizar tiempo y materiales]
    I --> J[Tomar fotos del progreso]
    J --> K{¿Reparación completada?}
    K -->|No| G
    K -->|Sí| L[Cambiar estado a Control de Calidad]
```

**Responsable:** Técnico + Supervisor
**Tiempo Estimado:** 2-8 horas (según complejidad)

#### **4. Control de Calidad**
```mermaid
flowchart TD
    A[QC Inspector revisa trabajo] --> B[Probar funcionamiento]
    B --> C[Verificar calidad de reparación]
    C --> D[Revisar checklist de QC]
    D --> E{¿Pasa control de calidad?}
    E -->|No| F[Documentar problemas]
    F --> G[Devolver a técnico]
    G --> H[Corregir problemas]
    H --> A
    E -->|Sí| I[Cambiar estado a Listo para Entrega]
    I --> J[Limpiar vehículo]
    J --> K[Preparar documentos de entrega]
    K --> L[Contactar al cliente]
```

**Responsable:** QC Inspector
**Tiempo Estimado:** 30-60 minutos

### **Workflow Especializado: Servicios de Garantía**
```mermaid
flowchart TD
    A[Vehículo con problema de garantía] --> B[Verificar cobertura de garantía]
    B --> C{¿Cubierto por garantía?}
    C -->|No| D[Proceso normal con costo]
    C -->|Sí| E[Marcar como servicio de garantía]
    E --> F[Documentar problema original]
    F --> G[Realizar reparación sin costo]
    G --> H[Reportar a fabricante/proveedor]
    H --> I[Procesar claim de garantía]
```

---

## 🚗 **Car Wash - Workflows**

### **Workflow Principal: Servicio de Lavado**

#### **1. Recepción y Programación**
```mermaid
flowchart TD
    A[Cliente solicita servicio] --> B{¿Cita programada?}
    B -->|No| C[Walk-in: Verificar disponibilidad]
    B -->|Sí| D[Cliente llega a cita]
    C --> E{¿Capacidad disponible?}
    E -->|No| F[Ofrecer próximo slot disponible]
    E -->|Sí| G[Crear orden walk-in]
    D --> H[Confirmar orden existente]
    F --> I[Cliente acepta o se va]
    G --> J[Inspección inicial del vehículo]
    H --> J
    J --> K[Documentar condición previa]
    K --> L[Seleccionar/confirmar servicios]
    L --> M[Procesar pago o autorización]
    M --> N[Asignar a equipo de lavado]
```

**Responsable:** Recepcionista + Supervisor
**Tiempo Estimado:** 5-10 minutos

#### **2. Proceso de Lavado**
```mermaid
flowchart TD
    A[Equipo recibe vehículo] --> B[Cambiar estado a En Progreso]
    B --> C[Pre-enjuague del vehículo]
    C --> D[Aplicar productos químicos]
    D --> E[Lavado manual/automático según servicio]
    E --> F[Enjuague completo]
    F --> G{¿Servicio incluye interior?}
    G -->|Sí| H[Aspirado y limpieza interior]
    G -->|No| I[Secado exterior]
    H --> I
    I --> J[Aplicar productos finales (cera, etc.)]
    J --> K[Inspección final]
    K --> L{¿Calidad aceptable?}
    L -->|No| M[Corregir problemas]
    M --> K
    L -->|Sí| N[Cambiar estado a Completado]
    N --> O[Mover a área de entrega]
```

**Responsables:** Equipo de lavado (2-3 personas)
**Tiempo Estimado:** 30 minutos - 2 horas

#### **3. Entrega del Vehículo**
```mermaid
flowchart TD
    A[Vehículo listo] --> B[Notificar al cliente]
    B --> C[Cliente llega para recoger]
    C --> D[Inspección final con cliente]
    D --> E{¿Cliente satisfecho?}
    E -->|No| F[Identificar problemas]
    F --> G[Corregir sin costo adicional]
    G --> D
    E -->|Sí| H[Procesar pago final]
    H --> I[Entregar llaves y recibo]
    I --> J[Solicitar feedback/rating]
    J --> K[Programar próximo servicio (opcional)]
```

**Responsable:** Supervisor + Recepcionista
**Tiempo Estimado:** 5-10 minutos

### **Workflow de Temporada Alta**
```mermaid
flowchart TD
    A[Identificar temporada alta] --> B[Activar modo de alta capacidad]
    B --> C[Extender horarios de operación]
    C --> D[Asignar staff adicional]
    D --> E[Implementar sistema de cola rápida]
    E --> F[Ofrecer servicios express]
    F --> G[Monitorear tiempos de espera]
    G --> H[Ajustar recursos según demanda]
```

---

## 📋 **Recon Orders - Workflows**

### **Workflow Principal: Proceso de Reconocimiento**

#### **1. Ingreso de Vehículo**
```mermaid
flowchart TD
    A[Vehículo llega para reconocimiento] --> B[Crear orden de recon]
    B --> C[Registrar información básica]
    C --> D[Asignar número de stock]
    D --> E[Tomar fotos iniciales]
    E --> F[Cambiar estado a Recibido]
    F --> G[Asignar inspector]
    G --> H[Programar inspección detallada]
```

**Responsable:** Recepcionista + Manager
**Tiempo Estimado:** 15-20 minutos

#### **2. Inspección Detallada**
```mermaid
flowchart TD
    A[Inspector comienza evaluación] --> B[Cambiar estado a En Inspección]
    B --> C[Inspección exterior completa]
    C --> D[Inspección interior completa]
    D --> E[Inspección mecánica básica]
    E --> F[Documentar todos los hallazgos]
    F --> G[Tomar fotos detalladas]
    G --> H[Calcular score de condición]
    H --> I[Estimar costos de preparación]
    I --> J{¿Vehículo aceptable?}
    J -->|No| K[Cambiar estado a Rechazado]
    J -->|Sí| L[Cambiar estado a Servicios Asignados]
    L --> M[Crear lista de servicios necesarios]
```

**Responsable:** Inspector certificado
**Tiempo Estimado:** 1-2 horas

#### **3. Preparación del Vehículo**
```mermaid
flowchart TD
    A[Servicios asignados] --> B[Cambiar estado a En Proceso]
    B --> C[Crear órdenes de trabajo específicas]
    C --> D[Asignar técnicos especializados]
    D --> E[Realizar servicios mecánicos]
    E --> F[Realizar servicios cosméticos]
    F --> G[Detallado completo]
    G --> H[Actualizar fotos de progreso]
    H --> I{¿Todos los servicios completados?}
    I -->|No| J[Continuar con servicios pendientes]
    J --> E
    I -->|Sí| K[Cambiar estado a Control de Calidad]
```

**Responsables:** Múltiples técnicos especializados
**Tiempo Estimado:** 3-10 días

#### **4. Control de Calidad Final**
```mermaid
flowchart TD
    A[QC Manager revisa vehículo] --> B[Inspección final completa]
    B --> C[Verificar todos los servicios realizados]
    C --> D[Tomar fotos finales]
    D --> E{¿Pasa control de calidad?}
    E -->|No| F[Identificar problemas restantes]
    F --> G[Devolver para correcciones]
    G --> H[Realizar correcciones necesarias]
    H --> B
    E -->|Sí| I[Cambiar estado a Listo para Venta]
    I --> J[Calcular costo total de preparación]
    J --> K[Calcular precio de venta sugerido]
    K --> L[Crear listing para inventario]
    L --> M[Notificar a equipo de ventas]
```

**Responsable:** QC Manager
**Tiempo Estimado:** 2-4 horas

### **Workflow de Importación Masiva CSV**
```mermaid
flowchart TD
    A[Recibir archivo CSV] --> B[Validar formato del archivo]
    B --> C{¿Formato correcto?}
    C -->|No| D[Reportar errores de formato]
    C -->|Sí| E[Vista previa de datos]
    E --> F[Usuario confirma importación]
    F --> G[Procesar registros por lotes]
    G --> H[Crear órdenes de recon automáticamente]
    H --> I[Asignar números de stock]
    I --> J[Generar reporte de importación]
    J --> K[Notificar a inspectores]
```

---

## 🚙 **Vehicles - Workflows**

### **Workflow Principal: Tracking de Ubicación NFC**

#### **1. Generación de Token NFC**
```mermaid
flowchart TD
    A[Usuario solicita token para vehículo] --> B[Sistema verifica VIN único]
    B --> C{¿Token ya existe?}
    C -->|Sí| D[Mostrar token existente]
    C -->|No| E[Generar token único de 64 caracteres]
    E --> F[Crear URL personalizada]
    F --> G[Generar código QR]
    G --> H[Guardar en base de datos]
    H --> I[Mostrar token y QR al usuario]
    I --> J[Usuario programa NFC tag]
```

**Responsable:** Manager o Admin
**Tiempo Estimado:** 2-3 minutos

#### **2. Registro de Ubicación**
```mermaid
flowchart TD
    A[Usuario escanea NFC tag] --> B[Abrir interfaz móvil]
    B --> C[Capturar GPS automáticamente]
    C --> D{¿GPS disponible?}
    D -->|No| E[Permitir entrada manual de coordenadas]
    D -->|Sí| F[Mostrar ubicación en mapa]
    E --> F
    F --> G[Usuario ingresa número de spot]
    G --> H[Usuario agrega notas opcionales]
    H --> I[Validar datos ingresados]
    I --> J[Guardar ubicación en base de datos]
    J --> K[Mostrar confirmación]
    K --> L[Actualizar historial de ubicaciones]
```

**Responsable:** Cualquier usuario con acceso al NFC tag
**Tiempo Estimado:** 1-2 minutos

#### **3. Consulta de Ubicación**
```mermaid
flowchart TD
    A[Usuario busca vehículo] --> B[Ingresar VIN o número de stock]
    B --> C[Sistema busca en base de datos]
    C --> D{¿Vehículo encontrado?}
    D -->|No| E[Mostrar mensaje de no encontrado]
    D -->|Sí| F[Mostrar ubicación actual]
    F --> G[Mostrar historial de ubicaciones]
    G --> H[Mostrar mapa con ubicación]
    H --> I[Opciones adicionales: direcciones, notas]
```

**Responsable:** Staff autorizado
**Tiempo Estimado:** 30 segundos - 2 minutos

### **Workflow de Analytics de Vehículos**
```mermaid
flowchart TD
    A[Sistema ejecuta análisis nocturno] --> B[Recopilar datos de todos los módulos]
    B --> C[Calcular métricas por vehículo]
    C --> D[Generar gráficos de tendencias]
    D --> E[Identificar patrones de uso]
    E --> F[Crear alertas para anomalías]
    F --> G[Actualizar dashboard de analytics]
    G --> H[Enviar reporte semanal a managers]
```

---

## 🌐 **Public Pages - Workflows**

### **Workflow de Creación de Contenido**

#### **1. Creación de Página Pública**
```mermaid
flowchart TD
    A[Admin decide crear página] --> B[Acceder al CMS]
    B --> C[Crear nueva página]
    C --> D[Ingresar título y slug]
    D --> E[Seleccionar template]
    E --> F[Escribir contenido con editor WYSIWYG]
    F --> G[Configurar nivel de privacidad]
    G --> H[Agregar imagen destacada]
    H --> I[Configurar SEO]
    I --> J[Vista previa de la página]
    J --> K{¿Contenido correcto?}
    K -->|No| L[Editar contenido]
    L --> F
    K -->|Sí| M[Publicar página]
    M --> N[Página disponible públicamente]
```

**Responsable:** Admin o Content Manager
**Tiempo Estimado:** 15-30 minutos

#### **2. Actualización de API Pública**
```mermaid
flowchart TD
    A[Cambios en inventario/órdenes] --> B[Sistema detecta cambios]
    B --> C[Filtrar datos para API pública]
    C --> D[Actualizar cache de API]
    D --> E[Regenerar responses JSON]
    E --> F[Notificar a sistemas integrados]
    F --> G[Actualizar timestamp de última actualización]
```

**Responsable:** Sistema automático
**Tiempo Estimado:** Tiempo real (automático)

---

## 📊 **Workflows Transversales**

### **Workflow de Notificaciones**
```mermaid
flowchart TD
    A[Evento ocurre en el sistema] --> B[Sistema identifica tipo de evento]
    B --> C[Determinar usuarios a notificar]
    C --> D[Verificar preferencias de notificación]
    D --> E{¿Email habilitado?}
    E -->|Sí| F[Enviar email]
    E -->|No| G{¿SMS habilitado?}
    G -->|Sí| H[Enviar SMS]
    G -->|No| I{¿Push habilitado?}
    I -->|Sí| J[Enviar notificación push]
    F --> K[Registrar en historial]
    H --> K
    J --> K
    K --> L[Marcar como enviada]
```

### **Workflow de Backup y Mantenimiento**
```mermaid
flowchart TD
    A[Cron job diario] --> B[Crear backup de base de datos]
    B --> C[Subir backup a S3]
    C --> D[Limpiar logs antiguos]
    D --> E[Optimizar tablas de BD]
    E --> F[Limpiar cache del sistema]
    F --> G[Verificar salud del sistema]
    G --> H[Generar reporte de estado]
    H --> I[Enviar reporte a admins]
```

---

**Estos workflows proporcionan una guía operativa completa para todos los procesos del sistema MDA, asegurando consistencia y eficiencia en las operaciones diarias.**

---

*Documentación actualizada: 2025-01-19*  
*Versión de workflows: MDA v2.0*  
*Para más información: [Volver a Documentación Principal](../../claude.md)*


