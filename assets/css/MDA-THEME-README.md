# 🎨 MDA Unified Theme System

## 📋 Descripción

El **MDA Theme** es un sistema de diseño unificado que proporciona una interfaz compacta, limpia y consistente para todo el sistema. Inspirado en aplicaciones de productividad modernas, ofrece tipografía 15% más pequeña, sombras suaves y transiciones fluidas.

## ✨ Características Principales

- ✅ **Tipografía 15% más compacta** - Optimizada para máxima legibilidad en menor espacio
- ✅ **Sombras suaves y sutiles** - Sin efectos duros o llamativos  
- ✅ **Transiciones fluidas** - 150-300ms con curvas suaves cubic-bezier
- ✅ **Colores neutros claros** - Sin gradientes ni esquemas oscuros
- ✅ **Diseño responsivo** - Optimizado para desktop, tablet y móvil
- ✅ **Accesibilidad completa** - Soporte para preferencias de usuario y navegación por teclado

## 🚀 Implementación

### Activación Global
El tema se activa automáticamente con la clase `mda-theme` en el elemento `<body>`:

```html
<body class="mda-theme">
  <!-- Todo el contenido tendrá el estilo MDA aplicado -->
</body>
```

### Activación Selectiva
También se puede aplicar a elementos específicos:

```html
<div class="mda-theme">
  <!-- Solo este contenedor tendrá el estilo MDA -->
</div>
```

## 🎯 Variables CSS Principales

### Tipografía (15% reducida)
```css
--mda-font-size-xs: 0.6375rem;     /* ~10.2px */
--mda-font-size-sm: 0.74375rem;    /* ~11.9px */
--mda-font-size-base: 0.85rem;     /* ~13.6px */
--mda-font-size-lg: 0.95625rem;    /* ~15.3px */
--mda-font-size-xl: 1.0625rem;     /* ~17px */
--mda-font-size-2xl: 1.275rem;     /* ~20.4px */
--mda-font-size-3xl: 1.59375rem;   /* ~25.5px */
--mda-font-size-4xl: 1.9125rem;    /* ~30.6px */
```

### Colores Neutros (Sin gradientes)
```css
--mda-text-primary: #212121;
--mda-text-secondary: #757575;
--mda-text-muted: #9e9e9e;
--mda-bg-primary: #ffffff;
--mda-bg-secondary: #fafafa;
--mda-bg-tertiary: #f5f5f5;
```

### Sombras Suaves
```css
--mda-shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
--mda-shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px 0 rgba(0, 0, 0, 0.02);
--mda-shadow-base: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
```

### Transiciones Fluidas
```css
--mda-transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
--mda-transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
--mda-transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);
```

## 🧩 Clases de Utilidad

### Tipografía
```css
.mda-text-xs       /* Texto extra pequeño */
.mda-text-sm       /* Texto pequeño */
.mda-text-base     /* Texto base */
.mda-text-lg       /* Texto grande */
.mda-text-xl       /* Texto extra grande */
```

### Colores de Texto
```css
.mda-text-primary     /* Texto principal */
.mda-text-secondary   /* Texto secundario */
.mda-text-muted       /* Texto apagado */
.mda-text-light       /* Texto claro */
```

### Pesos de Fuente
```css
.mda-font-normal      /* 400 */
.mda-font-medium      /* 500 */
.mda-font-semibold    /* 600 */
.mda-font-bold        /* 700 */
```

### Sombras
```css
.mda-shadow-xs        /* Sombra extra suave */
.mda-shadow-sm        /* Sombra suave */
.mda-shadow-base      /* Sombra base */
.mda-shadow-lg        /* Sombra grande */
.mda-shadow-xl        /* Sombra extra grande */
```

### Fondos
```css
.mda-bg-primary       /* Fondo primario (blanco) */
.mda-bg-secondary     /* Fondo secundario (gris muy claro) */
.mda-bg-tertiary      /* Fondo terciario (gris claro) */
.mda-bg-hover         /* Fondo al pasar el cursor */
```

### Espaciado Compacto
```css
.mda-p-1, .mda-m-1   /* 4px */
.mda-p-2, .mda-m-2   /* 8px */
.mda-p-3, .mda-m-3   /* 12px */
.mda-p-4, .mda-m-4   /* 16px */
.mda-p-5, .mda-m-5   /* 20px */
.mda-p-6, .mda-m-6   /* 24px */
```

## 📱 Componentes Soportados

### Componentes Bootstrap Estilizados
- ✅ **Cards** - Bordes suaves, sombras sutiles, tipografía compacta
- ✅ **Buttons** - Efectos hover suaves, sin gradientes
- ✅ **Forms** - Campos compactos, focus states suaves
- ✅ **Tables** - Headers compactos, hover states suaves
- ✅ **Modals** - Sombras suaves, tipografía consistente
- ✅ **Dropdowns** - Bordes suaves, animaciones fluidas
- ✅ **Alerts** - Colores neutros, tipografía compacta
- ✅ **Navigation** - Estados hover suaves, tipografía consistente

### Componentes Personalizados MDA
```html
<!-- Card compacta -->
<div class="card mda-card-compact">
  <div class="card-header">
    <h4 class="card-title">Título Compacto</h4>
  </div>
  <div class="card-body">
    Contenido con espaciado reducido
  </div>
</div>

<!-- Tabla compacta -->
<table class="table mda-table-compact">
  <thead>
    <tr>
      <th>Header</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Data con padding reducido</td>
    </tr>
  </tbody>
</table>

<!-- Botón compacto -->
<button class="btn btn-primary mda-btn-compact">
  Botón Compacto
</button>

<!-- Formulario compacto -->
<div class="mda-form-compact">
  <label class="form-label">Label</label>
  <input class="form-control" type="text">
</div>
```

## 🎨 Ejemplos de Uso

### Dashboard con Métricas
```html
<div class="mda-theme">
  <div class="row">
    <div class="col-md-3">
      <div class="metrics-card mda-shadow-sm">
        <div class="metric-value mda-text-2xl mda-font-bold">1,234</div>
        <div class="metric-label mda-text-sm mda-text-secondary">Total Orders</div>
      </div>
    </div>
  </div>
</div>
```

### Formulario Estilizado
```html
<div class="mda-theme">
  <form>
    <div class="mb-3">
      <label class="form-label mda-text-sm mda-font-medium">Email</label>
      <input type="email" class="form-control" placeholder="usuario@ejemplo.com">
    </div>
    <button type="submit" class="btn btn-primary mda-shadow-sm">
      Enviar
    </button>
  </form>
</div>
```

## 📱 Responsividad

### Móvil (< 768px)
- Reducción adicional de fuentes para mejor ajuste
- Espaciado optimizado para pantallas táctiles
- Elementos interactivos de mayor tamaño

### Tablet (768px - 1024px)
- Tipografía estándar del sistema MDA
- Espaciado normal optimizado

### Desktop (> 1024px)
- Tipografía completa del sistema MDA
- Espaciado y sombras optimizados para precisión

## ♿ Accesibilidad

### Soporte Automático
- **Reduced Motion**: Respeta `prefers-reduced-motion: reduce`
- **High Contrast**: Ajusta colores para `prefers-contrast: high`
- **Focus Visible**: Estados de focus claramente definidos
- **Keyboard Navigation**: Navegación completa por teclado

### Implementación
```css
/* Movimiento reducido */
@media (prefers-reduced-motion: reduce) {
  .mda-theme * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}

/* Alto contraste */
@media (prefers-contrast: high) {
  .mda-theme {
    --mda-text-primary: #000000;
    --mda-border-light: #999999;
  }
}

/* Focus visible */
.mda-theme *:focus-visible {
  outline: 2px solid #005fcc;
  outline-offset: 2px;
}
```

## 🔧 Personalización Avanzada

### Override de Variables
```css
:root {
  /* Personalizar colores */
  --mda-text-primary: #1a1a1a;
  --mda-bg-primary: #fefefe;
  
  /* Personalizar espaciado */
  --mda-space-4: 1.2rem;
  
  /* Personalizar transiciones */
  --mda-transition-base: 250ms ease-out;
}
```

### Variantes de Componentes
```css
/* Card con fondo colorido suave */
.mda-card-info {
  background-color: rgba(33, 150, 243, 0.05);
  border-left: 3px solid #2196f3;
}

/* Botón con efecto personalizado */
.mda-btn-subtle {
  background-color: var(--mda-bg-hover);
  color: var(--mda-text-primary);
  border: 1px solid var(--mda-border-light);
}
```

## 🔄 Migración y Rollback

### Desactivar el Tema
Para desactivar temporalmente el tema MDA:

```html
<!-- Cambiar de: -->
<body class="mda-theme">

<!-- A: -->
<body>
```

### Rollback Completo
1. Remover la línea del CSS en `head-css.php`:
   ```html
   <!-- Comentar o eliminar esta línea -->
   <!-- <link href="<?= base_url('assets/css/mda-theme.css') ?>" rel="stylesheet" type="text/css" /> -->
   ```

2. Remover la clase del layout:
   ```html
   <body> <!-- Sin clase mda-theme -->
   ```

## 📊 Comparación de Tamaños

| Elemento | Tamaño Original | Tamaño MDA | Reducción |
|----------|----------------|-------------|-----------|
| H1       | 2.5rem (40px)  | 1.59rem (25.5px) | 36% |
| H2       | 2rem (32px)    | 1.275rem (20.4px) | 36% |
| H3       | 1.75rem (28px) | 1.06rem (17px) | 39% |
| Body     | 1rem (16px)    | 0.85rem (13.6px) | 15% |
| Small    | 0.875rem (14px)| 0.74rem (11.9px) | 16% |

## 🎯 Casos de Uso Ideales

### ✅ Recomendado Para:
- Dashboards con mucha información
- Formularios extensos
- Tablas de datos densas
- Interfaces administrativas
- Aplicaciones de gestión
- Paneles de control

### ⚠️ Considerar Alternativas:
- Sitios web promocionales
- Landing pages marketing
- Interfaces para usuarios mayores
- Aplicaciones con mucho contenido de lectura

## 📞 Soporte y Documentación

### Archivos del Sistema
- **SCSS Principal**: `assets/scss/_mda-theme.scss`
- **Componentes**: `assets/scss/_mda-components.scss`
- **CSS Compilado**: `assets/css/mda-theme.css`
- **Import Principal**: `assets/scss/app.scss`

### Estructura de Archivos
```
assets/
├── scss/
│   ├── _mda-theme.scss        # Variables y estilos base
│   ├── _mda-components.scss   # Componentes Bootstrap
│   └── app.scss              # Import principal
└── css/
    ├── mda-theme.css         # CSS compilado
    └── MDA-THEME-README.md   # Esta documentación
```

---

**MDA Theme v2.0** - Sistema de diseño unificado para aplicaciones de productividad  
*Última actualización: 2025-01-02*