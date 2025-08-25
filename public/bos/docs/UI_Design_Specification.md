# BOS Inventory Management - UI Design Specification

## Overview
This document provides a comprehensive UI design specification for the BOS (BMW of Sudbury) inventory management system, featuring BMW brand-aligned colors, modern glass-morphism effects, interactive charts, and professional user experience components.

## 1. BMW Brand-Aligned Color Palette

### Primary Colors
```css
:root {
  /* BMW Primary Blues */
  --bmw-primary-blue: #0066CC;
  --bmw-primary-blue-dark: #004499;
  --bmw-primary-blue-light: #3399FF;
  --bmw-secondary-blue: #1C4E80;
  --bmw-accent-blue: #0099FF;
  
  /* BMW Whites & Greys */
  --bmw-white: #FFFFFF;
  --bmw-light-grey: #F5F7FA;
  --bmw-medium-grey: #E0E6ED;
  --bmw-dark-grey: #6B7280;
  --bmw-charcoal: #374151;
  
  /* BMW Accent Colors */
  --bmw-silver: #C0C4CC;
  --bmw-platinum: #E5E7EB;
  --bmw-success: #059669;
  --bmw-warning: #D97706;
  --bmw-danger: #DC2626;
  
  /* Gradients */
  --bmw-gradient-primary: linear-gradient(135deg, var(--bmw-primary-blue) 0%, var(--bmw-accent-blue) 100%);
  --bmw-gradient-glass: linear-gradient(135deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.05) 100%);
  --bmw-gradient-card: linear-gradient(135deg, var(--bmw-white) 0%, var(--bmw-light-grey) 100%);
}
```

## 2. Modern Card-Based Layout with Glass-Morphism

### Glass Card Base Class
```css
.bmw-glass-card {
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  box-shadow: 
    0 8px 32px rgba(0, 102, 204, 0.1),
    0 4px 16px rgba(0, 0, 0, 0.1);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.bmw-glass-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--bmw-gradient-primary);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.bmw-glass-card:hover {
  transform: translateY(-4px);
  box-shadow: 
    0 12px 48px rgba(0, 102, 204, 0.15),
    0 8px 24px rgba(0, 0, 0, 0.15);
}

.bmw-glass-card:hover::before {
  opacity: 1;
}
```

### Statistics Cards
```css
.bmw-stat-card {
  @extend .bmw-glass-card;
  padding: 24px;
  text-align: center;
  cursor: pointer;
  min-height: 140px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.bmw-stat-icon {
  width: 48px;
  height: 48px;
  margin: 0 auto 16px;
  background: var(--bmw-gradient-primary);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
}

.bmw-stat-value {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--bmw-primary-blue);
  line-height: 1;
  margin: 8px 0;
  font-family: 'BMW Group Type', 'Helvetica Neue', Arial, sans-serif;
}

.bmw-stat-label {
  font-size: 0.875rem;
  color: var(--bmw-dark-grey);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.bmw-stat-badge {
  margin-top: 8px;
  display: inline-block;
  padding: 4px 12px;
  background: rgba(0, 102, 204, 0.1);
  color: var(--bmw-primary-blue);
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}
```

## 3. Interactive Charts Configuration

### Chart Container
```css
.bmw-chart-container {
  @extend .bmw-glass-card;
  padding: 32px;
  margin: 24px 0;
}

.bmw-chart-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--bmw-medium-grey);
}

.bmw-chart-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--bmw-charcoal);
  display: flex;
  align-items: center;
  gap: 12px;
}

.bmw-chart-controls {
  display: flex;
  gap: 8px;
}
```

### Chart.js Configuration
```javascript
const bmwChartConfig = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        usePointStyle: true,
        padding: 20,
        font: {
          family: "'BMW Group Type', 'Helvetica Neue', Arial, sans-serif",
          size: 12,
          weight: 500
        },
        color: '#374151'
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 102, 204, 0.9)',
      titleColor: '#FFFFFF',
      bodyColor: '#FFFFFF',
      borderColor: '#0066CC',
      borderWidth: 1,
      cornerRadius: 8,
      padding: 12,
      titleFont: {
        weight: 600,
        size: 14
      },
      bodyFont: {
        size: 13
      }
    }
  },
  scales: {
    x: {
      grid: {
        display: false
      },
      ticks: {
        color: '#6B7280',
        font: {
          size: 11,
          weight: 500
        }
      }
    },
    y: {
      grid: {
        color: 'rgba(0, 102, 204, 0.1)',
        borderDash: [5, 5]
      },
      ticks: {
        color: '#6B7280',
        font: {
          size: 11,
          weight: 500
        }
      }
    }
  }
};

const bmwColors = [
  '#0066CC', '#3399FF', '#0099FF', '#1C4E80',
  '#059669', '#D97706', '#DC2626', '#6B7280'
];
```

## 4. Animated Statistics Counters

### Counter Animation CSS
```css
.bmw-counter {
  display: inline-block;
  position: relative;
  overflow: hidden;
}

.bmw-counter-number {
  display: inline-block;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.bmw-counter.updating .bmw-counter-number {
  animation: counterUpdate 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes counterUpdate {
  0% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
  50% {
    transform: translateY(-20px) scale(1.1);
    opacity: 0.7;
    color: var(--bmw-accent-blue);
  }
  100% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
}

.bmw-counter-pulse {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  height: 100%;
  border: 2px solid var(--bmw-primary-blue);
  border-radius: 50%;
  opacity: 0;
  pointer-events: none;
}

.bmw-counter.updating .bmw-counter-pulse {
  animation: counterPulse 0.8s ease-out;
}

@keyframes counterPulse {
  0% {
    transform: translate(-50%, -50%) scale(0.8);
    opacity: 0.8;
  }
  100% {
    transform: translate(-50%, -50%) scale(2);
    opacity: 0;
  }
}
```

### Counter JavaScript
```javascript
function animateCounter(element, start, end, duration = 1000) {
  element.classList.add('updating');
  
  const startTime = Date.now();
  const startValue = parseInt(start) || 0;
  const endValue = parseInt(end) || 0;
  const difference = endValue - startValue;
  
  const updateCounter = () => {
    const elapsed = Date.now() - startTime;
    const progress = Math.min(elapsed / duration, 1);
    const easeProgress = 1 - Math.pow(1 - progress, 3); // easeOut cubic
    
    const current = Math.round(startValue + (difference * easeProgress));
    element.textContent = current.toLocaleString();
    
    if (progress < 1) {
      requestAnimationFrame(updateCounter);
    } else {
      element.classList.remove('updating');
    }
  };
  
  requestAnimationFrame(updateCounter);
}
```

## 5. Professional Sidebar Navigation

### Sidebar Structure
```css
.bmw-sidebar {
  width: 280px;
  height: 100vh;
  background: var(--bmw-gradient-card);
  border-right: 1px solid var(--bmw-medium-grey);
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
  transition: transform 0.3s ease;
  box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
}

.bmw-sidebar-header {
  padding: 32px 24px;
  border-bottom: 1px solid var(--bmw-medium-grey);
  background: var(--bmw-gradient-primary);
  color: white;
  text-align: center;
}

.bmw-sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.bmw-sidebar-subtitle {
  font-size: 0.875rem;
  opacity: 0.9;
  font-weight: 400;
}

.bmw-sidebar-nav {
  padding: 24px 0;
}

.bmw-nav-item {
  margin: 4px 16px;
}

.bmw-nav-link {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px 20px;
  color: var(--bmw-charcoal);
  text-decoration: none;
  border-radius: 12px;
  transition: all 0.2s ease;
  font-weight: 500;
  position: relative;
  overflow: hidden;
}

.bmw-nav-link::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: var(--bmw-primary-blue);
  transform: scaleY(0);
  transition: transform 0.2s ease;
}

.bmw-nav-link:hover,
.bmw-nav-link.active {
  background: rgba(0, 102, 204, 0.1);
  color: var(--bmw-primary-blue);
  transform: translateX(4px);
}

.bmw-nav-link:hover::before,
.bmw-nav-link.active::before {
  transform: scaleY(1);
}

.bmw-nav-icon {
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bmw-nav-badge {
  margin-left: auto;
  background: var(--bmw-primary-blue);
  color: white;
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
}
```

## 6. Clean Minimalist Table Design

### Table Container
```css
.bmw-table-container {
  @extend .bmw-glass-card;
  padding: 0;
  margin: 24px 0;
  overflow: hidden;
}

.bmw-table-header {
  padding: 24px 32px;
  background: var(--bmw-gradient-card);
  border-bottom: 1px solid var(--bmw-medium-grey);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.bmw-table-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--bmw-charcoal);
  display: flex;
  align-items: center;
  gap: 12px;
}

.bmw-table-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.bmw-table-wrapper {
  overflow-x: auto;
  max-height: 600px;
}

.bmw-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.875rem;
}

.bmw-table thead th {
  background: var(--bmw-light-grey);
  color: var(--bmw-charcoal);
  font-weight: 600;
  text-align: left;
  padding: 20px 24px;
  border-bottom: 2px solid var(--bmw-medium-grey);
  position: sticky;
  top: 0;
  z-index: 10;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-size: 0.75rem;
}

.bmw-table tbody td {
  padding: 20px 24px;
  border-bottom: 1px solid var(--bmw-platinum);
  transition: all 0.2s ease;
  vertical-align: middle;
}

.bmw-table tbody tr {
  transition: all 0.2s ease;
  position: relative;
}

.bmw-table tbody tr::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: var(--bmw-primary-blue);
  transform: scaleY(0);
  transition: transform 0.2s ease;
}

.bmw-table tbody tr:hover {
  background: linear-gradient(135deg, 
    rgba(0, 102, 204, 0.03) 0%, 
    rgba(51, 153, 255, 0.03) 100%);
  transform: translateX(4px);
  box-shadow: 0 2px 8px rgba(0, 102, 204, 0.1);
}

.bmw-table tbody tr:hover::before {
  transform: scaleY(1);
}

/* Status-based row styling */
.bmw-table tbody tr.status-pending {
  background: rgba(217, 119, 6, 0.05);
  border-left: 4px solid var(--bmw-warning);
}

.bmw-table tbody tr.status-in-progress {
  background: rgba(0, 102, 204, 0.05);
  border-left: 4px solid var(--bmw-primary-blue);
}

.bmw-table tbody tr.status-completed {
  background: rgba(5, 150, 105, 0.05);
  border-left: 4px solid var(--bmw-success);
}
```

### Table Cell Components
```css
.bmw-stock-number {
  font-family: 'SF Mono', 'Monaco', monospace;
  background: var(--bmw-gradient-primary);
  color: white;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.5px;
  display: inline-block;
  position: relative;
  overflow: hidden;
}

.bmw-stock-number::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, 
    transparent, 
    rgba(255,255,255,0.2), 
    transparent);
  transition: left 0.5s ease;
}

.bmw-stock-number:hover::before {
  left: 100%;
}

.bmw-vehicle-info {
  font-weight: 600;
  color: var(--bmw-charcoal);
  line-height: 1.4;
}

.bmw-days-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-align: center;
  min-width: 60px;
  display: inline-block;
}

.bmw-days-badge.recent {
  background: rgba(5, 150, 105, 0.1);
  color: var(--bmw-success);
}

.bmw-days-badge.moderate {
  background: rgba(217, 119, 6, 0.1);
  color: var(--bmw-warning);
}

.bmw-days-badge.aged {
  background: rgba(220, 38, 38, 0.1);
  color: var(--bmw-danger);
}

.bmw-status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.bmw-status-indicator {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: currentColor;
  opacity: 0.8;
}
```

## 7. Floating Action Buttons

### FAB Base Styles
```css
.bmw-fab {
  position: fixed;
  width: 56px;
  height: 56px;
  border-radius: 28px;
  background: var(--bmw-gradient-primary);
  color: white;
  border: none;
  box-shadow: 
    0 8px 24px rgba(0, 102, 204, 0.3),
    0 4px 12px rgba(0, 0, 0, 0.15);
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  overflow: hidden;
  backdrop-filter: blur(10px);
}

.bmw-fab::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.1);
  border-radius: inherit;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.bmw-fab:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 
    0 12px 36px rgba(0, 102, 204, 0.4),
    0 6px 18px rgba(0, 0, 0, 0.2);
}

.bmw-fab:hover::before {
  opacity: 1;
}

.bmw-fab:active {
  transform: translateY(-1px) scale(1.02);
}

.bmw-fab-primary {
  bottom: 24px;
  right: 24px;
}

.bmw-fab-secondary {
  bottom: 96px;
  right: 24px;
  background: var(--bmw-silver);
  color: var(--bmw-charcoal);
}

.bmw-fab-mini {
  width: 40px;
  height: 40px;
  border-radius: 20px;
}

.bmw-fab-extended {
  width: auto;
  padding: 0 24px;
  min-width: 56px;
  border-radius: 28px;
  font-weight: 600;
  font-size: 0.875rem;
  gap: 8px;
}

.bmw-fab-icon {
  font-size: 24px;
  transition: transform 0.3s ease;
}

.bmw-fab:hover .bmw-fab-icon {
  transform: rotate(90deg);
}

.bmw-fab-refresh:hover .bmw-fab-icon {
  transform: rotate(360deg);
}

/* FAB Speed Dial */
.bmw-fab-speed-dial {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 1000;
}

.bmw-fab-speed-dial-actions {
  position: absolute;
  bottom: 72px;
  right: 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.3s ease;
  pointer-events: none;
}

.bmw-fab-speed-dial.open .bmw-fab-speed-dial-actions {
  opacity: 1;
  transform: translateY(0);
  pointer-events: all;
}

.bmw-fab-speed-dial-action {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 12px 20px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(0, 102, 204, 0.2);
  border-radius: 28px;
  color: var(--bmw-charcoal);
  text-decoration: none;
  font-weight: 500;
  font-size: 0.875rem;
  white-space: nowrap;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.2s ease;
}

.bmw-fab-speed-dial-action:hover {
  background: var(--bmw-primary-blue);
  color: white;
  transform: translateX(-4px);
}
```

## 8. Toast Notifications

### Toast Container
```css
.bmw-toast-container {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 10000;
  display: flex;
  flex-direction: column;
  gap: 12px;
  max-width: 400px;
  pointer-events: none;
}

.bmw-toast {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 12px;
  padding: 16px 20px;
  box-shadow: 
    0 8px 32px rgba(0, 102, 204, 0.15),
    0 4px 16px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 320px;
  opacity: 0;
  transform: translateX(100%);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: all;
  position: relative;
  overflow: hidden;
}

.bmw-toast::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: var(--bmw-primary-blue);
  transition: all 0.3s ease;
}

.bmw-toast.show {
  opacity: 1;
  transform: translateX(0);
}

.bmw-toast.success::before {
  background: var(--bmw-success);
}

.bmw-toast.warning::before {
  background: var(--bmw-warning);
}

.bmw-toast.error::before {
  background: var(--bmw-danger);
}

.bmw-toast.info::before {
  background: var(--bmw-primary-blue);
}

.bmw-toast-icon {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: white;
  flex-shrink: 0;
}

.bmw-toast.success .bmw-toast-icon {
  background: var(--bmw-success);
}

.bmw-toast.warning .bmw-toast-icon {
  background: var(--bmw-warning);
}

.bmw-toast.error .bmw-toast-icon {
  background: var(--bmw-danger);
}

.bmw-toast.info .bmw-toast-icon {
  background: var(--bmw-primary-blue);
}

.bmw-toast-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.bmw-toast-title {
  font-weight: 600;
  color: var(--bmw-charcoal);
  font-size: 0.875rem;
  line-height: 1.2;
}

.bmw-toast-message {
  color: var(--bmw-dark-grey);
  font-size: 0.8125rem;
  line-height: 1.3;
}

.bmw-toast-close {
  background: none;
  border: none;
  color: var(--bmw-dark-grey);
  font-size: 18px;
  cursor: pointer;
  padding: 4px;
  border-radius: 4px;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.bmw-toast-close:hover {
  background: rgba(0, 0, 0, 0.1);
  color: var(--bmw-charcoal);
}

.bmw-toast-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 2px;
  background: var(--bmw-primary-blue);
  width: 100%;
  transform-origin: left;
  animation: toastProgress 4s linear;
}

@keyframes toastProgress {
  from {
    transform: scaleX(1);
  }
  to {
    transform: scaleX(0);
  }
}
```

### Toast JavaScript Implementation
```javascript
class BMWToastManager {
  constructor() {
    this.container = this.createContainer();
    this.toasts = [];
  }
  
  createContainer() {
    const container = document.createElement('div');
    container.className = 'bmw-toast-container';
    document.body.appendChild(container);
    return container;
  }
  
  show(message, type = 'info', title = '', duration = 4000) {
    const toast = this.createToast(message, type, title);
    this.container.appendChild(toast);
    this.toasts.push(toast);
    
    // Trigger animation
    requestAnimationFrame(() => {
      toast.classList.add('show');
    });
    
    // Auto dismiss
    if (duration > 0) {
      setTimeout(() => {
        this.dismiss(toast);
      }, duration);
    }
    
    return toast;
  }
  
  createToast(message, type, title) {
    const toast = document.createElement('div');
    toast.className = `bmw-toast ${type}`;
    
    const icons = {
      success: '✓',
      warning: '⚠',
      error: '✕',
      info: 'ℹ'
    };
    
    const titles = {
      success: title || 'Success',
      warning: title || 'Warning', 
      error: title || 'Error',
      info: title || 'Information'
    };
    
    toast.innerHTML = `
      <div class="bmw-toast-icon">${icons[type]}</div>
      <div class="bmw-toast-content">
        <div class="bmw-toast-title">${titles[type]}</div>
        <div class="bmw-toast-message">${message}</div>
      </div>
      <button class="bmw-toast-close" onclick="bmwToast.dismiss(this.parentElement)">×</button>
      <div class="bmw-toast-progress"></div>
    `;
    
    return toast;
  }
  
  dismiss(toast) {
    toast.classList.remove('show');
    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast);
      }
      this.toasts = this.toasts.filter(t => t !== toast);
    }, 400);
  }
  
  dismissAll() {
    this.toasts.forEach(toast => this.dismiss(toast));
  }
}

// Global instance
const bmwToast = new BMWToastManager();
```

## 9. Responsive Design Considerations

### Breakpoints
```css
:root {
  --bmw-breakpoint-xs: 480px;
  --bmw-breakpoint-sm: 768px;
  --bmw-breakpoint-md: 1024px;
  --bmw-breakpoint-lg: 1280px;
  --bmw-breakpoint-xl: 1536px;
}

/* Mobile First Approach */
@media (max-width: 768px) {
  .bmw-sidebar {
    transform: translateX(-100%);
  }
  
  .bmw-sidebar.open {
    transform: translateX(0);
  }
  
  .bmw-glass-card {
    margin: 12px;
    padding: 16px;
  }
  
  .bmw-fab {
    bottom: 16px;
    right: 16px;
  }
  
  .bmw-toast-container {
    left: 16px;
    right: 16px;
    top: 16px;
  }
  
  .bmw-toast {
    min-width: auto;
  }
}

@media (max-width: 480px) {
  .bmw-stat-card {
    padding: 16px;
    min-height: 120px;
  }
  
  .bmw-stat-value {
    font-size: 2rem;
  }
  
  .bmw-table-container {
    margin: 8px;
  }
  
  .bmw-table thead th,
  .bmw-table tbody td {
    padding: 12px 16px;
  }
}
```

## 10. Accessibility & Performance

### Accessibility Features
```css
/* Focus styles */
.bmw-focusable:focus {
  outline: 2px solid var(--bmw-primary-blue);
  outline-offset: 2px;
  border-radius: 4px;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .bmw-glass-card,
  .bmw-fab,
  .bmw-toast,
  .bmw-nav-link {
    transition-duration: 0.1s;
  }
  
  .bmw-counter.updating .bmw-counter-number {
    animation: none;
  }
}

/* High contrast support */
@media (prefers-contrast: high) {
  .bmw-glass-card {
    border: 2px solid var(--bmw-charcoal);
  }
  
  .bmw-table tbody tr:hover {
    background: var(--bmw-light-grey);
  }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
  :root {
    --bmw-white: #1F2937;
    --bmw-light-grey: #374151;
    --bmw-charcoal: #F9FAFB;
    --bmw-dark-grey: #D1D5DB;
  }
}
```

### Performance Optimizations
```css
/* Hardware acceleration */
.bmw-glass-card,
.bmw-fab,
.bmw-toast {
  will-change: transform, opacity;
  transform: translateZ(0);
}

/* Efficient animations */
.bmw-smooth-scroll {
  scroll-behavior: smooth;
}

/* GPU optimization for backdrop-filter */
.bmw-glass-card {
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
}
```

## 11. Implementation Guidelines

### CSS Class Naming Convention
- Use `bmw-` prefix for all custom classes
- Follow BEM methodology where appropriate
- Use semantic names that describe function, not appearance
- Maintain consistency across all components

### JavaScript Integration
- Use modern ES6+ syntax
- Implement proper error handling
- Optimize for performance with debouncing and throttling
- Ensure accessibility compliance

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 12. File Organization

```
/css/
  ├── bmw-core.css        # Core variables and utilities
  ├── bmw-components.css  # Component styles
  ├── bmw-layout.css      # Layout and grid systems
  └── bmw-responsive.css  # Responsive breakpoints

/js/
  ├── bmw-toast.js        # Toast notification system
  ├── bmw-charts.js       # Chart configurations
  ├── bmw-counters.js     # Animated counters
  └── bmw-main.js         # Main application logic
```

This specification provides a comprehensive foundation for implementing a modern, BMW brand-aligned UI for the BOS inventory management system with glass-morphism effects, interactive elements, and professional user experience components.