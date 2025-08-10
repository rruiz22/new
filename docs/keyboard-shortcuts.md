# Sistema de Atajos de Teclado

## Descripción
Sistema global de atajos de teclado para navegación rápida entre módulos de la aplicación.

## Atajos Disponibles

### Navegación de Módulos
- **1** - Sales Orders
- **2** - Service Orders  
- **3** - Car Wash
- **4** - Recon Orders
- **5** - Vehicles
- **6** - Clients
- **7** - Contacts
- **8** - Settings
- **9** - Profile
- **0** - Dashboard

### Atajos de Sistema
- **?** - Mostrar/ocultar ayuda de atajos
- **Ctrl + /** - Habilitar/deshabilitar atajos
- **Escape** - Cerrar modal de ayuda

## Características

### 🚀 Funcionalidades Principales
- **Navegación rápida**: Acceso directo a módulos con una sola tecla
- **Notificaciones visuales**: Feedback visual al navegar
- **Modal de ayuda**: Referencia completa de atajos disponibles
- **Configuración persistente**: Las preferencias se guardan localmente
- **Detección inteligente**: Se desactiva automáticamente en campos de entrada

### 🎯 Integración
- **Global**: Funciona en toda la aplicación
- **No intrusivo**: No interfiere con formularios o campos de texto
- **Accesible**: Compatible con lectores de pantalla
- **Responsive**: Funciona en dispositivos móviles y desktop

### 🔧 Configuración
- **Habilitado por defecto**: Los atajos están activos al cargar la página
- **Notificaciones configurables**: Se pueden activar/desactivar
- **Bienvenida única**: Solo se muestra la primera vez

## Archivos del Sistema

### JavaScript
- `assets/js/keyboard-shortcuts.js` - Lógica principal del sistema

### CSS
- `assets/css/keyboard-shortcuts.css` - Estilos para modales y notificaciones

### Vistas
- `app/Views/partials/default.php` - Integración en layout principal
- `app/Views/partials/head-css.php` - Carga de estilos
- `app/Views/partials/topbar.php` - Botón de atajos en topbar

## Uso para Desarrolladores

### Agregar Nuevos Atajos
```javascript
// En assets/js/keyboard-shortcuts.js
this.shortcuts = {
    // ... atajos existentes
    'n': {
        url: 'nuevo-modulo',
        name: 'Nuevo Módulo',
        icon: 'ri-nuevo-icono-line'
    }
};
```

### Verificar Estado
```javascript
// Verificar si los atajos están habilitados
if (window.keyboardShortcuts?.isEnabled) {
    // Atajos habilitados
}

// Mostrar modal de ayuda programáticamente
window.keyboardShortcuts?.showHelpModal();
```

### Eventos Personalizados
```javascript
// El sistema emite eventos cuando navega
document.addEventListener('shortcut-navigation', (e) => {
    console.log('Navegando a:', e.detail.module);
});
```

## Personalización

### Modificar Estilos
Los estilos están en `assets/css/keyboard-shortcuts.css` y se pueden personalizar:

```css
/* Cambiar colores del modal */
.shortcuts-help-header {
    background: linear-gradient(135deg, #tu-color-1, #tu-color-2);
}

/* Personalizar notificaciones */
.shortcut-notification {
    background: tu-color-personalizado;
}
```

### Configurar Comportamiento
```javascript
// Deshabilitar notificaciones por defecto
this.showNotifications = false;

// Cambiar tiempo de notificación
setTimeout(() => {
    // tu código
}, 1500); // en lugar de 2000ms
```

## Compatibilidad

### Navegadores Soportados
- ✅ Chrome 70+
- ✅ Firefox 65+
- ✅ Safari 12+
- ✅ Edge 79+

### Dispositivos
- ✅ Desktop (Windows, macOS, Linux)
- ✅ Tablet (con teclado físico)
- ⚠️ Móvil (funcional pero limitado)

## Solución de Problemas

### Los atajos no funcionan
1. Verificar que no estés en un campo de entrada
2. Verificar que los atajos estén habilitados (Ctrl+/)
3. Revisar la consola del navegador por errores

### Modal no se muestra
1. Verificar que el CSS esté cargado
2. Comprobar conflictos con otros modales
3. Verificar z-index en CSS personalizado

### Navegación no funciona
1. Verificar rutas en `shortcuts` object
2. Comprobar que base_url esté configurado
3. Verificar permisos de acceso a módulos

## Mantenimiento

### Actualizar Atajos
Cuando se agreguen nuevos módulos, actualizar:
1. El objeto `shortcuts` en el JavaScript
2. La documentación del modal de ayuda
3. Este archivo de documentación

### Monitoreo
El sistema registra eventos en `localStorage` para análisis:
- `keyboard_shortcuts_welcome` - Primera visita
- `keyboard_shortcuts_preferences` - Configuración del usuario

## Seguridad

- ✅ No ejecuta código arbitrario
- ✅ Validación de URLs antes de navegar
- ✅ Escape de caracteres especiales
- ✅ No almacena información sensible
