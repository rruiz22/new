// ====================================
// BMW Charts Integration - Chart.js & ApexCharts
// BOS Inventory Management System
// ====================================

/**
 * BMW Chart.js Configuration and Management
 */
class BMWChartJS {
  static defaultConfig = {
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
        backgroundColor: 'rgba(0, 102, 204, 0.95)',
        titleColor: '#FFFFFF',
        bodyColor: '#FFFFFF',
        borderColor: '#0066CC',
        borderWidth: 1,
        cornerRadius: 12,
        padding: 16,
        titleFont: {
          weight: 600,
          size: 14
        },
        bodyFont: {
          size: 13
        },
        displayColors: true,
        boxPadding: 8
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
    },
    interaction: {
      intersect: false,
      mode: 'index'
    },
    animation: {
      duration: 750,
      easing: 'easeInOutQuart'
    }
  };

  static colors = [
    '#0066CC', '#3399FF', '#0099FF', '#1C4E80',
    '#059669', '#D97706', '#DC2626', '#6B7280',
    '#8B5CF6', '#06B6D4', '#F59E0B', '#10B981'
  ];

  /**
   * Create inventory status distribution chart
   */
  static createInventoryStatusChart(ctx, data) {
    const chartData = {
      labels: data.labels || ['Recent (0-1 days)', 'Moderate (2-5 days)', 'Aged (6+ days)'],
      datasets: [{
        data: data.values || [0, 0, 0],
        backgroundColor: [
          'rgba(5, 150, 105, 0.8)',
          'rgba(217, 119, 6, 0.8)', 
          'rgba(220, 38, 38, 0.8)'
        ],
        borderColor: [
          '#059669',
          '#D97706',
          '#DC2626'
        ],
        borderWidth: 2,
        hoverBorderWidth: 3,
        hoverBorderColor: '#FFFFFF'
      }]
    };

    const config = {
      type: 'doughnut',
      data: chartData,
      options: {
        ...this.defaultConfig,
        cutout: '65%',
        plugins: {
          ...this.defaultConfig.plugins,
          legend: {
            ...this.defaultConfig.plugins.legend,
            position: 'bottom'
          },
          tooltip: {
            ...this.defaultConfig.plugins.tooltip,
            callbacks: {
              label: function(context) {
                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((context.parsed * 100) / total).toFixed(1) : 0;
                return `${context.label}: ${context.parsed} (${percentage}%)`;
              }
            }
          }
        }
      }
    };

    return new Chart(ctx, config);
  }

  /**
   * Create inventory trend line chart
   */
  static createInventoryTrendChart(ctx, data) {
    const chartData = {
      labels: data.labels || [],
      datasets: [{
        label: 'Total Inventory',
        data: data.values || [],
        borderColor: this.colors[0],
        backgroundColor: this.colors[0] + '15',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: this.colors[0],
        pointBorderColor: '#FFFFFF',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
      }]
    };

    const config = {
      type: 'line',
      data: chartData,
      options: {
        ...this.defaultConfig,
        plugins: {
          ...this.defaultConfig.plugins,
          legend: {
            display: false
          }
        },
        scales: {
          ...this.defaultConfig.scales,
          y: {
            ...this.defaultConfig.scales.y,
            beginAtZero: true
          }
        }
      }
    };

    return new Chart(ctx, config);
  }

  /**
   * Create daily inventory movement bar chart
   */
  static createDailyMovementChart(ctx, data) {
    const chartData = {
      labels: data.labels || [],
      datasets: [{
        label: 'Items Added',
        data: data.added || [],
        backgroundColor: 'rgba(5, 150, 105, 0.8)',
        borderColor: '#059669',
        borderWidth: 0,
        borderRadius: 6,
        borderSkipped: false
      }, {
        label: 'Items Moved',
        data: data.moved || [],
        backgroundColor: 'rgba(0, 102, 204, 0.8)',
        borderColor: '#0066CC',
        borderWidth: 0,
        borderRadius: 6,
        borderSkipped: false
      }]
    };

    const config = {
      type: 'bar',
      data: chartData,
      options: {
        ...this.defaultConfig,
        scales: {
          ...this.defaultConfig.scales,
          y: {
            ...this.defaultConfig.scales.y,
            beginAtZero: true,
            stacked: false
          },
          x: {
            ...this.defaultConfig.scales.x,
            stacked: false
          }
        }
      }
    };

    return new Chart(ctx, config);
  }

  /**
   * Create average days in inventory chart
   */
  static createAverageDaysChart(ctx, data) {
    const chartData = {
      labels: data.labels || [],
      datasets: [{
        label: 'Average Days',
        data: data.values || [],
        backgroundColor: this.colors.map(color => color + '20'),
        borderColor: this.colors[0],
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false
      }]
    };

    const config = {
      type: 'bar',
      data: chartData,
      options: {
        ...this.defaultConfig,
        plugins: {
          ...this.defaultConfig.plugins,
          legend: {
            display: false
          }
        },
        scales: {
          ...this.defaultConfig.scales,
          y: {
            ...this.defaultConfig.scales.y,
            beginAtZero: true,
            title: {
              display: true,
              text: 'Days',
              color: '#6B7280',
              font: {
                size: 12,
                weight: 600
              }
            }
          }
        }
      }
    };

    return new Chart(ctx, config);
  }
}

/**
 * BMW ApexCharts Configuration and Management
 */
class BMWApexCharts {
  static defaultConfig = {
    chart: {
      fontFamily: "'BMW Group Type', 'Helvetica Neue', Arial, sans-serif",
      toolbar: {
        show: false
      },
      animations: {
        enabled: true,
        easing: 'easeinout',
        speed: 750
      }
    },
    colors: [
      '#0066CC', '#3399FF', '#0099FF', '#1C4E80',
      '#059669', '#D97706', '#DC2626', '#6B7280'
    ],
    tooltip: {
      theme: 'dark',
      style: {
        fontSize: '13px',
        fontFamily: "'BMW Group Type', 'Helvetica Neue', Arial, sans-serif"
      }
    },
    legend: {
      fontFamily: "'BMW Group Type', 'Helvetica Neue', Arial, sans-serif",
      fontSize: '12px',
      fontWeight: 500,
      labels: {
        colors: '#374151'
      }
    },
    grid: {
      borderColor: 'rgba(0, 102, 204, 0.1)',
      strokeDashArray: 5
    },
    dataLabels: {
      enabled: false
    }
  };

  /**
   * Create real-time inventory gauge
   */
  static createInventoryGauge(element, data) {
    const options = {
      ...this.defaultConfig,
      series: [data.percentage || 0],
      chart: {
        ...this.defaultConfig.chart,
        type: 'radialBar',
        height: 300
      },
      plotOptions: {
        radialBar: {
          startAngle: -135,
          endAngle: 135,
          hollow: {
            margin: 0,
            size: '70%',
            background: 'transparent',
            position: 'front',
            dropShadow: {
              enabled: true,
              top: 3,
              left: 0,
              blur: 4,
              opacity: 0.15
            }
          },
          track: {
            background: '#E0E6ED',
            strokeWidth: '67%',
            margin: 0,
            dropShadow: {
              enabled: true,
              top: -3,
              left: 0,
              blur: 4,
              opacity: 0.35
            }
          },
          dataLabels: {
            show: true,
            name: {
              offsetY: -10,
              show: true,
              color: '#374151',
              fontSize: '16px',
              fontWeight: 600
            },
            value: {
              formatter: function(val) {
                return parseInt(val) + '%';
              },
              color: '#0066CC',
              fontSize: '36px',
              fontWeight: 700,
              show: true
            }
          }
        }
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          type: 'horizontal',
          shadeIntensity: 0.5,
          gradientToColors: ['#3399FF'],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 1,
          stops: [0, 100]
        }
      },
      stroke: {
        lineCap: 'round'
      },
      labels: [data.label || 'Capacity']
    };

    return new ApexCharts(element, options);
  }

  /**
   * Create heatmap for inventory activity
   */
  static createActivityHeatmap(element, data) {
    const options = {
      ...this.defaultConfig,
      series: data.series || [],
      chart: {
        ...this.defaultConfig.chart,
        type: 'heatmap',
        height: 250
      },
      xaxis: {
        type: 'category',
        categories: data.categories || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        labels: {
          style: {
            colors: '#6B7280',
            fontSize: '11px',
            fontWeight: 500
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: '#6B7280',
            fontSize: '11px',
            fontWeight: 500
          }
        }
      },
      plotOptions: {
        heatmap: {
          radius: 4,
          enableShades: false,
          colorScale: {
            ranges: [
              { from: 0, to: 5, color: '#E0E6ED', name: 'Low' },
              { from: 6, to: 15, color: '#3399FF', name: 'Medium' },
              { from: 16, to: 25, color: '#0066CC', name: 'High' },
              { from: 26, to: 50, color: '#1C4E80', name: 'Very High' }
            ]
          }
        }
      }
    };

    return new ApexCharts(element, options);
  }

  /**
   * Create area chart for inventory trends
   */
  static createTrendAreaChart(element, data) {
    const options = {
      ...this.defaultConfig,
      series: [{
        name: 'Total Inventory',
        data: data.values || []
      }],
      chart: {
        ...this.defaultConfig.chart,
        type: 'area',
        height: 300,
        zoom: {
          enabled: false
        }
      },
      xaxis: {
        categories: data.labels || [],
        labels: {
          style: {
            colors: '#6B7280',
            fontSize: '11px',
            fontWeight: 500
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: '#6B7280',
            fontSize: '11px',
            fontWeight: 500
          }
        }
      },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.7,
          opacityTo: 0.1,
          stops: [0, 100]
        }
      },
      stroke: {
        curve: 'smooth',
        width: 3
      }
    };

    return new ApexCharts(element, options);
  }
}

/**
 * BMW Charts Manager - Unified interface for all chart operations
 */
class BMWChartsManager {
  constructor() {
    this.charts = new Map();
    this.initialized = false;
  }

  /**
   * Initialize the charts manager
   */
  async initialize() {
    if (this.initialized) return;

    // Check if Chart.js is available
    if (typeof Chart !== 'undefined') {
      Chart.defaults.font.family = "'BMW Group Type', 'Helvetica Neue', Arial, sans-serif";
      Chart.defaults.color = '#374151';
      console.log('Chart.js initialized with BMW theme');
    }

    // Check if ApexCharts is available
    if (typeof ApexCharts !== 'undefined') {
      console.log('ApexCharts available for advanced charts');
    }

    this.initialized = true;
  }

  /**
   * Create an inventory dashboard with multiple charts
   */
  createInventoryDashboard(containerId, data) {
    const container = document.getElementById(containerId);
    if (!container) {
      console.error(`Container ${containerId} not found`);
      return;
    }

    // Create dashboard HTML structure
    container.innerHTML = `
      <div class="bmw-charts-dashboard">
        <div class="bmw-grid bmw-grid-2">
          <div class="bmw-chart-card bmw-glass-card">
            <div class="bmw-chart-header">
              <h3 class="bmw-chart-title">
                <i class="ri-pie-chart-line"></i>
                Inventory Distribution
              </h3>
            </div>
            <div class="bmw-chart-content">
              <canvas id="statusChart" width="400" height="300"></canvas>
            </div>
          </div>
          
          <div class="bmw-chart-card bmw-glass-card">
            <div class="bmw-chart-header">
              <h3 class="bmw-chart-title">
                <i class="ri-line-chart-line"></i>
                Inventory Trend
              </h3>
            </div>
            <div class="bmw-chart-content">
              <canvas id="trendChart" width="400" height="300"></canvas>
            </div>
          </div>
          
          <div class="bmw-chart-card bmw-glass-card">
            <div class="bmw-chart-header">
              <h3 class="bmw-chart-title">
                <i class="ri-bar-chart-line"></i>
                Daily Movement
              </h3>
            </div>
            <div class="bmw-chart-content">
              <canvas id="movementChart" width="400" height="300"></canvas>
            </div>
          </div>
          
          <div class="bmw-chart-card bmw-glass-card">
            <div class="bmw-chart-header">
              <h3 class="bmw-chart-title">
                <i class="ri-time-line"></i>
                Average Days
              </h3>
            </div>
            <div class="bmw-chart-content">
              <canvas id="averageDaysChart" width="400" height="300"></canvas>
            </div>
          </div>
        </div>
      </div>
    `;

    // Create charts
    if (typeof Chart !== 'undefined') {
      // Status distribution chart
      const statusCtx = document.getElementById('statusChart');
      if (statusCtx) {
        const statusChart = BMWChartJS.createInventoryStatusChart(statusCtx, data.status || {});
        this.charts.set('status', statusChart);
      }

      // Trend chart
      const trendCtx = document.getElementById('trendChart');
      if (trendCtx) {
        const trendChart = BMWChartJS.createInventoryTrendChart(trendCtx, data.trend || {});
        this.charts.set('trend', trendChart);
      }

      // Movement chart
      const movementCtx = document.getElementById('movementChart');
      if (movementCtx) {
        const movementChart = BMWChartJS.createDailyMovementChart(movementCtx, data.movement || {});
        this.charts.set('movement', movementChart);
      }

      // Average days chart
      const avgDaysCtx = document.getElementById('averageDaysChart');
      if (avgDaysCtx) {
        const avgDaysChart = BMWChartJS.createAverageDaysChart(avgDaysCtx, data.averageDays || {});
        this.charts.set('averageDays', avgDaysChart);
      }
    }
  }

  /**
   * Update chart data
   */
  updateChart(chartId, newData) {
    const chart = this.charts.get(chartId);
    if (chart) {
      if (chart.data) {
        // Chart.js chart
        if (newData.labels) chart.data.labels = newData.labels;
        if (newData.datasets) chart.data.datasets = newData.datasets;
        if (newData.values && chart.data.datasets[0]) {
          chart.data.datasets[0].data = newData.values;
        }
        chart.update('active');
      } else if (chart.updateSeries) {
        // ApexCharts chart
        if (newData.series) {
          chart.updateSeries(newData.series);
        } else if (newData.values) {
          chart.updateSeries([{ data: newData.values }]);
        }
      }
    }
  }

  /**
   * Destroy a specific chart
   */
  destroyChart(chartId) {
    const chart = this.charts.get(chartId);
    if (chart) {
      if (chart.destroy) {
        chart.destroy();
      }
      this.charts.delete(chartId);
    }
  }

  /**
   * Destroy all charts
   */
  destroyAllCharts() {
    this.charts.forEach((chart, id) => {
      if (chart.destroy) {
        chart.destroy();
      }
    });
    this.charts.clear();
  }

  /**
   * Resize all charts
   */
  resizeCharts() {
    this.charts.forEach((chart) => {
      if (chart.resize) {
        chart.resize();
      } else if (chart.update) {
        chart.update('resize');
      }
    });
  }

  /**
   * Get chart instance
   */
  getChart(chartId) {
    return this.charts.get(chartId);
  }

  /**
   * Get all chart instances
   */
  getAllCharts() {
    return Array.from(this.charts.values());
  }
}

// Chart-specific CSS
const chartCSS = `
.bmw-charts-dashboard {
  margin: var(--bmw-spacing-lg) 0;
}

.bmw-chart-card {
  padding: 0;
  overflow: hidden;
}

.bmw-chart-header {
  padding: var(--bmw-spacing-lg) var(--bmw-spacing-xl);
  background: var(--bmw-gradient-card);
  border-bottom: 1px solid var(--bmw-medium-grey);
}

.bmw-chart-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--bmw-charcoal);
  margin: 0;
  display: flex;
  align-items: center;
  gap: var(--bmw-spacing-sm);
}

.bmw-chart-content {
  padding: var(--bmw-spacing-xl);
  position: relative;
  height: 300px;
}

.bmw-chart-content canvas {
  max-width: 100% !important;
  height: auto !important;
}

.bmw-chart-loading {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: var(--bmw-dark-grey);
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .bmw-charts-dashboard .bmw-grid-2 {
    grid-template-columns: 1fr;
  }
  
  .bmw-chart-content {
    padding: var(--bmw-spacing-lg);
    height: 250px;
  }
  
  .bmw-chart-header {
    padding: var(--bmw-spacing-md) var(--bmw-spacing-lg);
  }
  
  .bmw-chart-title {
    font-size: 1rem;
  }
}
`;

// Inject chart CSS
const chartStyleSheet = document.createElement('style');
chartStyleSheet.textContent = chartCSS;
document.head.appendChild(chartStyleSheet);

// Global instance
const bmwCharts = new BMWChartsManager();

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  bmwCharts.initialize();
});

// Handle window resize
window.addEventListener('resize', () => {
  bmwCharts.resizeCharts();
});

// Export for global use
window.BMWChartJS = BMWChartJS;
window.BMWApexCharts = BMWApexCharts;
window.BMWChartsManager = BMWChartsManager;
window.bmwCharts = bmwCharts;