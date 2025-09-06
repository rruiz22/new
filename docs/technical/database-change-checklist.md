# 📋 Checklist de Cambios en Base de Datos - MDA

Este documento establece el proceso obligatorio para realizar cambios en la estructura de la base de datos del sistema MDA, garantizando la integridad y continuidad del servicio.

---

## 🚨 **ANTES DE HACER CUALQUIER CAMBIO**

### ✅ **Pre-requisitos Obligatorios**

- [ ] **Backup completo realizado** y verificado
  ```bash
  php spark db:backup --full --verify
  ```
- [ ] **Conexión a base de datos confirmada** como estable
- [ ] **Ambiente de desarrollo disponible** para pruebas
- [ ] **Horario de mantenimiento programado** (si es producción)
- [ ] **Equipo notificado** sobre el cambio planificado
- [ ] **Plan de rollback preparado** y documentado

---

## 📊 **ANÁLISIS DE IMPACTO**

### 🔍 **Verificación de Estado Actual**

- [ ] **Ejecutar análisis de BD actual**
  ```bash
  php database-monitor.php --report
  ```
- [ ] **Revisar migraciones pendientes**
  ```bash
  php review-migrations.php --classify
  ```
- [ ] **Auditar tablas vacías**
  ```bash
  php audit-empty-tables.php --deep-scan
  ```
- [ ] **Verificar integridad de foreign keys**
- [ ] **Revisar índices y performance actual**

### 📝 **Documentación del Cambio**

- [ ] **Motivo del cambio** claramente documentado
- [ ] **Impacto esperado** en funcionalidad existente
- [ ] **Tablas/columnas afectadas** listadas
- [ ] **Queries que podrían fallar** identificadas
- [ ] **Tiempo estimado de ejecución** calculado

---

## 🔧 **PROCESO DE IMPLEMENTACIÓN**

### **FASE 1: Preparación**

- [ ] **Crear rama específica** en git para el cambio
  ```bash
  git checkout -b database/descripcion-del-cambio
  ```
- [ ] **Backup de seguridad adicional**
  ```bash
  php spark db:backup --full --compress
  ```
- [ ] **Verificar espacio disponible** en disco
- [ ] **Confirmar que no hay procesos críticos** ejecutándose

### **FASE 2: Desarrollo y Pruebas**

- [ ] **Crear migración(es)** siguiendo convenciones CodeIgniter
- [ ] **Revisar código SQL** generado por las migraciones
- [ ] **Probar en ambiente de desarrollo** local
- [ ] **Ejecutar test suite completo** después del cambio
- [ ] **Verificar funcionalidad afectada** manualmente
- [ ] **Revisar logs** por errores o warnings

### **FASE 3: Clasificación de Riesgo**

- [ ] **Ejecutar clasificador de migraciones**
  ```bash
  php review-migrations.php --show-content
  ```

#### 🟢 **Bajo Riesgo** (CREATE TABLE, INSERT básicos)
- [ ] Puede ejecutarse sin supervisión adicional
- [ ] Backup básico suficiente
- [ ] Notificar cambio después de ejecutar

#### 🟡 **Medio Riesgo** (ALTER TABLE, ADD COLUMN, CREATE INDEX)
- [ ] **Revisar impacto en performance**
- [ ] **Verificar compatibilidad con código existente**
- [ ] **Programar en horario de menor uso**
- [ ] **Supervisión durante ejecución**

#### 🔴 **Alto Riesgo** (DROP TABLE/COLUMN, ALTER estructura, DELETE data)
- [ ] **Aprobación del administrador del sistema**
- [ ] **Revisión por pares obligatoria**
- [ ] **Backup completo + verificación de integridad**
- [ ] **Plan de rollback detallado y probado**
- [ ] **Ventana de mantenimiento programada**
- [ ] **Monitoreo en tiempo real durante ejecución**

---

## ⚡ **EJECUCIÓN EN PRODUCCIÓN**

### 🎯 **Checklist de Ejecución**

- [ ] **Confirmar horario de mantenimiento**
- [ ] **Equipo técnico disponible** para soporte
- [ ] **Logs habilitados** en máximo detalle
- [ ] **Monitoreo de performance** activo
- [ ] **Backup final** antes de ejecutar cambios

### 📋 **Durante la Ejecución**

- [ ] **Ejecutar migraciones una por una** (no en lote)
- [ ] **Verificar cada paso** antes del siguiente
- [ ] **Monitorear conexiones activas** a la BD
- [ ] **Revisar logs** continuamente por errores
- [ ] **Verificar integridad** después de cada cambio mayor

### 🔍 **Comandos de Monitoreo**

```bash
# Estado general de la BD
php spark db:check --quick

# Verificar migraciones ejecutadas
php spark migrate:status

# Monitoreo completo post-cambio
php database-monitor.php --report
```

---

## ✅ **POST-IMPLEMENTACIÓN**

### 🧪 **Verificación de Funcionamiento**

- [ ] **Test suite completo** ejecutado sin errores
- [ ] **Funcionalidades críticas** verificadas manualmente
- [ ] **Performance** dentro de parámetros esperados
- [ ] **Logs limpios** sin errores relacionados
- [ ] **Backup post-cambio** realizado y verificado

### 📝 **Documentación y Comunicación**

- [ ] **Actualizar CLAUDE.md** con nueva estructura
  ```bash
  php database-monitor.php --update-docs
  ```
- [ ] **Documentar cambios** en CHANGELOG del proyecto
- [ ] **Notificar al equipo** sobre cambios completados
- [ ] **Actualizar diagramas de BD** si es necesario
- [ ] **Archivar backups** con etiquetas apropiadas

---

## 🚨 **PLAN DE ROLLBACK**

### ⚠️ **Situaciones que Requieren Rollback**

- Errores críticos en la aplicación
- Performance inaceptablemente degradada
- Pérdida de datos detectada
- Fallo en verificaciones de integridad
- Procesos críticos de negocio afectados

### 🔄 **Proceso de Rollback**

- [ ] **Detener ejecución** inmediatamente
- [ ] **Evaluar daños** y scope del problema
- [ ] **Restaurar backup** más reciente verificado
- [ ] **Revertir cambios en código** si es necesario
- [ ] **Verificar integridad** del rollback
- [ ] **Notificar al equipo** sobre el rollback
- [ ] **Documentar causas** del fallo para análisis

---

## 📊 **MÉTRICAS Y SEGUIMIENTO**

### 📈 **KPIs a Monitorear**

- [ ] **Tiempo de ejecución** de migraciones
- [ ] **Uso de espacio** en disco
- [ ] **Performance de queries** principales
- [ ] **Conexiones activas** durante el cambio
- [ ] **Errores en logs** post-implementación

### 🔍 **Revisión Post-Mortem**

- [ ] **Analizar lo que salió bien**
- [ ] **Identificar áreas de mejora**
- [ ] **Actualizar este checklist** con lecciones aprendidas
- [ ] **Compartir conocimientos** con el equipo

---

## 📞 **CONTACTOS DE EMERGENCIA**

### 🆘 **Escalación de Problemas**

1. **Administrador de BD**: [Contacto principal]
2. **Lead Developer**: [Contacto técnico]
3. **DevOps Team**: [Soporte de infraestructura]

### 📱 **Canales de Comunicación**

- **Chat del equipo**: Para actualizaciones regulares
- **Email**: Para documentación formal
- **Teléfono/SMS**: Solo para emergencias críticas

---

## 📚 **RECURSOS ADICIONALES**

### 🔗 **Herramientas de MDA**

- `php database-monitor.php` - Monitoreo general
- `php review-migrations.php` - Análisis de migraciones
- `php audit-empty-tables.php` - Auditoría de tablas
- `php spark db:backup` - Backup automatizado
- `php spark db:check` - Verificación rápida

### 📖 **Documentación**

- [Database Schema](./database-schema.md)
- [Clean Migrations Guide](./clean-migrations-guide.md)
- [Deployment Guide](./deployment.md)

---

## ⚡ **CHECKLIST RÁPIDO - Cambios Menores**

Para cambios de **bajo riesgo** (CREATE TABLE simples, INSERT de configuración):

- [ ] `php spark db:backup --tables=tabla_afectada`
- [ ] Ejecutar migración en desarrollo
- [ ] Verificar funcionalidad
- [ ] Ejecutar en producción
- [ ] `php database-monitor.php --update-docs`
- [ ] Notificar al equipo

---

*Este documento es un estándar vivo que debe actualizarse con cada experiencia y mejora del proceso.*

**Última actualización**: 2025-09-05  
**Versión**: 1.0  
**Mantenido por**: Equipo de Desarrollo MDA