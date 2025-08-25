// ====================================
// BMW UI Components - JavaScript Implementation
// BOS Inventory Management System
// ====================================

/**
 * BMW Toast Notification System
 */
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
  
  success(message, title = '') {
    return this.show(message, 'success', title);
  }
  
  warning(message, title = '') {
    return this.show(message, 'warning', title);
  }
  
  error(message, title = '') {
    return this.show(message, 'error', title);
  }
  
  info(message, title = '') {
    return this.show(message, 'info', title);
  }
}

/**
 * BMW Counter Animation System
 */
class BMWCounterAnimator {
  static animate(element, start, end, duration = 1000) {
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
  
  static animateMultiple(counters, duration = 1000) {
    counters.forEach(counter => {
      const { element, start, end } = counter;
      this.animate(element, start, end, duration);
    });
  }
}

/**
 * BMW Chart Configuration
 */
class BMWChartConfig {
  static getBaseConfig() {
    return {
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
  }
  
  static getColors() {
    return [
      '#0066CC', '#3399FF', '#0099FF', '#1C4E80',
      '#059669', '#D97706', '#DC2626', '#6B7280'
    ];
  }
  
  static createBarChart(ctx, data, options = {}) {
    const config = {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: data.label || 'Dataset',
          data: data.values,
          backgroundColor: this.getColors()[0] + '20',
          borderColor: this.getColors()[0],
          borderWidth: 2,
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        ...this.getBaseConfig(),
        ...options
      }
    };
    
    return new Chart(ctx, config);
  }
  
  static createLineChart(ctx, data, options = {}) {
    const config = {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: data.label || 'Dataset',
          data: data.values,
          borderColor: this.getColors()[0],
          backgroundColor: this.getColors()[0] + '10',
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: this.getColors()[0],
          pointBorderColor: '#FFFFFF',
          pointBorderWidth: 2,
          pointRadius: 5
        }]
      },
      options: {
        ...this.getBaseConfig(),
        ...options
      }
    };
    
    return new Chart(ctx, config);
  }
  
  static createDoughnutChart(ctx, data, options = {}) {
    const config = {
      type: 'doughnut',
      data: {
        labels: data.labels,
        datasets: [{
          data: data.values,
          backgroundColor: this.getColors(),
          borderWidth: 0,
          hoverBorderWidth: 4,
          hoverBorderColor: '#FFFFFF'
        }]
      },
      options: {
        ...this.getBaseConfig(),
        cutout: '70%',
        ...options
      }
    };
    
    return new Chart(ctx, config);
  }
}

/**
 * BMW FAB (Floating Action Button) Manager
 */
class BMWFabManager {
  static initializeSpeedDial(fabSelector) {
    const fab = document.querySelector(fabSelector);
    if (!fab) return;
    
    const speedDial = fab.closest('.bmw-fab-speed-dial');
    if (!speedDial) return;
    
    fab.addEventListener('click', (e) => {
      e.stopPropagation();
      speedDial.classList.toggle('open');
    });
    
    // Close on outside click
    document.addEventListener('click', (e) => {
      if (!speedDial.contains(e.target)) {
        speedDial.classList.remove('open');
      }
    });
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        speedDial.classList.remove('open');
      }
    });
  }
  
  static createFab(options = {}) {
    const {
      type = 'primary',
      icon = '✦',
      position = 'bottom-right',
      size = 'normal',
      extended = false,
      text = '',
      onClick = () => {}
    } = options;
    
    const fab = document.createElement('button');
    fab.className = `bmw-fab bmw-fab-${type}`;
    
    if (size === 'mini') {
      fab.classList.add('bmw-fab-mini');
    }
    
    if (extended) {
      fab.classList.add('bmw-fab-extended');
      fab.innerHTML = `
        <span class="bmw-fab-icon">${icon}</span>
        ${text ? `<span>${text}</span>` : ''}
      `;
    } else {
      fab.innerHTML = `<span class="bmw-fab-icon">${icon}</span>`;
    }
    
    // Position
    const [vertical, horizontal] = position.split('-');
    fab.style[vertical] = '24px';
    fab.style[horizontal] = '24px';
    
    fab.addEventListener('click', onClick);
    
    document.body.appendChild(fab);
    return fab;
  }
}

/**
 * BMW UI Utilities
 */
class BMWUIUtils {
  static addGlassEffect(element) {
    element.classList.add('bmw-glass-card');
  }
  
  static animateOnScroll(selector, animationClass = 'bmw-animate-in') {
    const elements = document.querySelectorAll(selector);
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add(animationClass);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    elements.forEach(element => observer.observe(element));
  }
  
  static createLoadingSpinner(container, size = 'normal') {
    const spinner = document.createElement('div');
    spinner.className = `bmw-loading-spinner ${size}`;
    spinner.innerHTML = `
      <div class="bmw-spinner-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    `;
    
    if (container) {
      container.appendChild(spinner);
    }
    
    return spinner;
  }
  
  static debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  
  static throttle(func, limit) {
    let inThrottle;
    return function(...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }
  
  static formatNumber(num) {
    return new Intl.NumberFormat().format(num);
  }
  
  static formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: currency
    }).format(amount);
  }
  
  static copyToClipboard(text) {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(text).then(() => {
        bmwToast.success('Copied to clipboard');
      });
    } else {
      // Fallback for older browsers
      const textarea = document.createElement('textarea');
      textarea.value = text;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      bmwToast.success('Copied to clipboard');
    }
  }
}

/**
 * BMW Table Enhancements
 */
class BMWTableEnhancements {
  static addHoverEffects(tableSelector) {
    const table = document.querySelector(tableSelector);
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
      row.addEventListener('mouseenter', () => {
        row.style.transform = 'translateX(4px)';
      });
      
      row.addEventListener('mouseleave', () => {
        row.style.transform = '';
      });
    });
  }
  
  static addSortingIndicators(tableSelector) {
    const table = document.querySelector(tableSelector);
    if (!table) return;
    
    const headers = table.querySelectorAll('thead th[data-sortable]');
    
    headers.forEach(header => {
      header.style.cursor = 'pointer';
      header.style.userSelect = 'none';
      
      const indicator = document.createElement('span');
      indicator.className = 'bmw-sort-indicator';
      indicator.innerHTML = '↕';
      header.appendChild(indicator);
      
      header.addEventListener('click', () => {
        // Remove active state from other headers
        headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
        
        // Toggle sorting state
        if (header.classList.contains('sort-asc')) {
          header.classList.remove('sort-asc');
          header.classList.add('sort-desc');
          indicator.innerHTML = '↓';
        } else {
          header.classList.remove('sort-desc');
          header.classList.add('sort-asc');
          indicator.innerHTML = '↑';
        }
      });
    });
  }
  
  static addRowSelection(tableSelector) {
    const table = document.querySelector(tableSelector);
    if (!table) return;
    
    const checkboxes = table.querySelectorAll('input[type="checkbox"]');
    const selectAll = table.querySelector('thead input[type="checkbox"]');
    
    if (selectAll) {
      selectAll.addEventListener('change', () => {
        checkboxes.forEach(checkbox => {
          if (checkbox !== selectAll) {
            checkbox.checked = selectAll.checked;
          }
        });
      });
    }
    
    checkboxes.forEach(checkbox => {
      if (checkbox !== selectAll) {
        checkbox.addEventListener('change', () => {
          const checkedCount = Array.from(checkboxes)
            .filter(cb => cb !== selectAll && cb.checked).length;
          const totalCount = checkboxes.length - 1; // Exclude select all
          
          selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
          selectAll.checked = checkedCount === totalCount;
        });
      }
    });
  }
}

// ====================================
// INITIALIZATION
// ====================================

// Global instances
const bmwToast = new BMWToastManager();
window.bmwToast = bmwToast;
window.BMWCounterAnimator = BMWCounterAnimator;
window.BMWChartConfig = BMWChartConfig;
window.BMWFabManager = BMWFabManager;
window.BMWUIUtils = BMWUIUtils;
window.BMWTableEnhancements = BMWTableEnhancements;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  // Initialize speed dial FABs
  const speedDialFabs = document.querySelectorAll('.bmw-fab-speed-dial .bmw-fab');
  speedDialFabs.forEach(fab => {
    BMWFabManager.initializeSpeedDial(fab);
  });
  
  // Add table enhancements
  const tables = document.querySelectorAll('.bmw-table');
  tables.forEach(table => {
    BMWTableEnhancements.addHoverEffects(`#${table.id}`);
    BMWTableEnhancements.addSortingIndicators(`#${table.id}`);
    BMWTableEnhancements.addRowSelection(`#${table.id}`);
  });
  
  // Animate elements on scroll
  BMWUIUtils.animateOnScroll('.bmw-stat-card, .bmw-glass-card');
  
  console.log('BMW UI Components initialized successfully');
});

// CSS for animations and loading spinner
const additionalCSS = `
.bmw-animate-in {
  animation: bmw-fade-in-up 0.6s ease-out;
}

@keyframes bmw-fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.bmw-loading-spinner {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.bmw-spinner-ring {
  display: inline-block;
  position: relative;
  width: 40px;
  height: 40px;
}

.bmw-spinner-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 32px;
  height: 32px;
  margin: 4px;
  border: 4px solid var(--bmw-primary-blue);
  border-radius: 50%;
  animation: bmw-spinner-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: var(--bmw-primary-blue) transparent transparent transparent;
}

.bmw-spinner-ring div:nth-child(1) { animation-delay: -0.45s; }
.bmw-spinner-ring div:nth-child(2) { animation-delay: -0.3s; }
.bmw-spinner-ring div:nth-child(3) { animation-delay: -0.15s; }

@keyframes bmw-spinner-ring {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.bmw-sort-indicator {
  margin-left: 8px;
  opacity: 0.5;
  transition: opacity 0.2s ease;
}

th:hover .bmw-sort-indicator {
  opacity: 1;
}

th.sort-asc .bmw-sort-indicator,
th.sort-desc .bmw-sort-indicator {
  opacity: 1;
  color: var(--bmw-primary-blue);
}
`;

// Inject additional CSS
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalCSS;
document.head.appendChild(styleSheet);