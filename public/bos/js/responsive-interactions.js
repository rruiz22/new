/**
 * BOS Inventory - Responsive Interactions Handler
 * Handles mobile sidebar, touch interactions, and responsive behavior
 */

class ResponsiveManager {
    constructor() {
        this.currentBreakpoint = this.getCurrentBreakpoint();
        this.sidebarOpen = false;
        this.touchStartX = 0;
        this.touchStartY = 0;
        this.isTouch = 'ontouchstart' in window;
        
        this.init();
    }
    
    init() {
        this.setupBreakpointListeners();
        this.setupMobileSidebar();
        this.setupTouchInteractions();
        this.setupResponsiveTable();
        this.setupMobileCardView();
        this.setupKeyboardNavigation();
        this.setupIntersectionObserver();
        
        // Initialize on load
        this.handleBreakpointChange();
    }
    
    getCurrentBreakpoint() {
        const width = window.innerWidth;
        
        if (width <= 767) return 'mobile';
        if (width <= 1365) return 'tablet';
        if (width <= 1919) return 'laptop';
        return 'desktop';
    }
    
    setupBreakpointListeners() {
        let resizeTimer;
        
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const newBreakpoint = this.getCurrentBreakpoint();
                
                if (newBreakpoint !== this.currentBreakpoint) {
                    this.currentBreakpoint = newBreakpoint;
                    this.handleBreakpointChange();
                }
            }, 150);
        });
        
        // Listen for orientation changes
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                const newBreakpoint = this.getCurrentBreakpoint();
                if (newBreakpoint !== this.currentBreakpoint) {
                    this.currentBreakpoint = newBreakpoint;
                    this.handleBreakpointChange();
                }
            }, 300);
        });
    }
    
    handleBreakpointChange() {
        console.log(`Breakpoint changed to: ${this.currentBreakpoint}`);
        
        // Update body class for CSS targeting
        document.body.className = document.body.className.replace(/breakpoint-\w+/g, '');
        document.body.classList.add(`breakpoint-${this.currentBreakpoint}`);
        
        // Handle sidebar state
        if (this.currentBreakpoint !== 'mobile' && this.sidebarOpen) {
            this.closeMobileSidebar();
        }
        
        // Update table view
        this.updateTableView();
        
        // Update stats grid
        this.updateStatsGrid();
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('breakpointChange', {
            detail: { breakpoint: this.currentBreakpoint }
        }));
    }
    
    setupMobileSidebar() {
        // Create mobile sidebar elements if they don't exist
        this.createMobileSidebarElements();
        
        // Toggle button
        const toggleButton = document.querySelector('.mobile-sidebar-toggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                this.toggleMobileSidebar();
            });
        }
        
        // Overlay click to close
        const overlay = document.querySelector('.mobile-sidebar-overlay');
        if (overlay) {
            overlay.addEventListener('click', () => {
                this.closeMobileSidebar();
            });
        }
        
        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.sidebarOpen) {
                this.closeMobileSidebar();
            }
        });
        
        // Swipe gesture to close
        this.setupSidebarSwipeGestures();
    }
    
    createMobileSidebarElements() {
        // Add sidebar toggle button to header
        const navbar = document.querySelector('.navbar-header');
        if (navbar && !document.querySelector('.mobile-sidebar-toggle')) {
            const toggleButton = document.createElement('button');
            toggleButton.className = 'mobile-sidebar-toggle';
            toggleButton.innerHTML = '<i class="ri-menu-line"></i>';
            toggleButton.setAttribute('aria-label', 'Toggle menu');
            
            const leftDiv = navbar.querySelector('.d-flex') || navbar.firstElementChild;
            if (leftDiv) {
                leftDiv.insertBefore(toggleButton, leftDiv.firstChild);
            }
        }
        
        // Create sidebar container
        if (!document.querySelector('.mobile-sidebar')) {
            const sidebar = document.createElement('div');
            sidebar.className = 'mobile-sidebar';
            sidebar.innerHTML = `
                <div class="mobile-sidebar-header">
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                        <h5 class="mb-0">Menu</h5>
                        <button class="btn btn-link p-0 mobile-sidebar-close" aria-label="Close menu">
                            <i class="ri-close-line fs-20"></i>
                        </button>
                    </div>
                </div>
                <div class="mobile-sidebar-body">
                    <div class="p-3">
                        <div class="mobile-sidebar-section">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Navigation</h6>
                            <ul class="list-unstyled mobile-sidebar-menu">
                                <li><a href="#inventory" class="mobile-sidebar-link">Inventory</a></li>
                                <li><a href="#stats" class="mobile-sidebar-link">Statistics</a></li>
                                <li><a href="#filters" class="mobile-sidebar-link">Filters</a></li>
                            </ul>
                        </div>
                        
                        <div class="mobile-sidebar-section">
                            <h6 class="text-muted text-uppercase fs-12 mb-3">Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm mobile-refresh-btn">
                                    <i class="ri-refresh-line me-1"></i>Refresh
                                </button>
                                <button class="btn btn-outline-secondary btn-sm mobile-clear-filters-btn">
                                    <i class="ri-filter-off-line me-1"></i>Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(sidebar);
            
            // Close button
            const closeButton = sidebar.querySelector('.mobile-sidebar-close');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    this.closeMobileSidebar();
                });
            }
            
            // Menu links
            const menuLinks = sidebar.querySelectorAll('.mobile-sidebar-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = link.getAttribute('href');
                    this.scrollToSection(target);
                    this.closeMobileSidebar();
                });
            });
            
            // Quick action buttons
            const refreshBtn = sidebar.querySelector('.mobile-refresh-btn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => {
                    const mainRefreshBtn = document.getElementById('refreshInventoryBtn');
                    if (mainRefreshBtn) {
                        mainRefreshBtn.click();
                    }
                    this.closeMobileSidebar();
                });
            }
            
            const clearFiltersBtn = sidebar.querySelector('.mobile-clear-filters-btn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    const mainClearBtn = document.getElementById('clearAllFilters');
                    if (mainClearBtn) {
                        mainClearBtn.click();
                    }
                    this.closeMobileSidebar();
                });
            }
        }
        
        // Create overlay
        if (!document.querySelector('.mobile-sidebar-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'mobile-sidebar-overlay';
            document.body.appendChild(overlay);
        }
    }
    
    toggleMobileSidebar() {
        if (this.sidebarOpen) {
            this.closeMobileSidebar();
        } else {
            this.openMobileSidebar();
        }
    }
    
    openMobileSidebar() {
        const sidebar = document.querySelector('.mobile-sidebar');
        const overlay = document.querySelector('.mobile-sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            this.sidebarOpen = true;
            
            // Focus management
            const firstFocusable = sidebar.querySelector('button, a');
            if (firstFocusable) {
                firstFocusable.focus();
            }
        }
    }
    
    closeMobileSidebar() {
        const sidebar = document.querySelector('.mobile-sidebar');
        const overlay = document.querySelector('.mobile-sidebar-overlay');
        
        if (sidebar && overlay) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            this.sidebarOpen = false;
            
            // Return focus to toggle button
            const toggleButton = document.querySelector('.mobile-sidebar-toggle');
            if (toggleButton) {
                toggleButton.focus();
            }
        }
    }
    
    setupSidebarSwipeGestures() {
        const sidebar = document.querySelector('.mobile-sidebar');
        if (!sidebar || !this.isTouch) return;
        
        let startX, currentX, isDragging = false;
        
        sidebar.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        }, { passive: true });
        
        sidebar.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            
            currentX = e.touches[0].clientX;
            const deltaX = currentX - startX;
            
            // Only allow closing swipe (left)
            if (deltaX < 0) {
                const opacity = Math.max(0, 1 + deltaX / 300);
                const overlay = document.querySelector('.mobile-sidebar-overlay');
                if (overlay) {
                    overlay.style.opacity = opacity;
                }
                
                sidebar.style.transform = `translateX(${Math.min(0, deltaX)}px)`;
            }
        }, { passive: true });
        
        sidebar.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            
            const deltaX = currentX - startX;
            
            // Close if swiped more than 100px left
            if (deltaX < -100) {
                this.closeMobileSidebar();
            } else {
                // Reset position
                sidebar.style.transform = '';
                const overlay = document.querySelector('.mobile-sidebar-overlay');
                if (overlay) {
                    overlay.style.opacity = '';
                }
            }
            
            isDragging = false;
        }, { passive: true });
    }
    
    setupTouchInteractions() {
        if (!this.isTouch) return;
        
        // Add touch feedback for buttons
        const buttons = document.querySelectorAll('.btn, .card, .stat-card');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            }, { passive: true });
            
            button.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-active');
                }, 150);
            }, { passive: true });
        });
        
        // Add swipe navigation for table
        this.setupTableSwipeNavigation();
    }
    
    setupTableSwipeNavigation() {
        const tableWrapper = document.querySelector('.table-wrapper');
        if (!tableWrapper || !this.isTouch) return;
        
        let isScrolling = false;
        let startX, scrollLeft;
        
        tableWrapper.addEventListener('touchstart', (e) => {
            startX = e.touches[0].pageX - tableWrapper.offsetLeft;
            scrollLeft = tableWrapper.scrollLeft;
            isScrolling = true;
        }, { passive: true });
        
        tableWrapper.addEventListener('touchmove', (e) => {
            if (!isScrolling) return;
            
            e.preventDefault();
            const x = e.touches[0].pageX - tableWrapper.offsetLeft;
            const walk = (x - startX) * 2;
            tableWrapper.scrollLeft = scrollLeft - walk;
        });
        
        tableWrapper.addEventListener('touchend', () => {
            isScrolling = false;
        }, { passive: true });
    }
    
    setupResponsiveTable() {
        const table = document.querySelector('#inventoryTable');
        if (!table) return;
        
        // Add table responsiveness enhancements
        this.addTableScrollIndicators();
        this.setupTableColumnToggle();
    }
    
    addTableScrollIndicators() {
        const wrapper = document.querySelector('.table-wrapper');
        if (!wrapper) return;
        
        const updateScrollIndicators = () => {
            const { scrollLeft, scrollWidth, clientWidth } = wrapper;
            const canScrollLeft = scrollLeft > 0;
            const canScrollRight = scrollLeft < scrollWidth - clientWidth - 1;
            
            wrapper.classList.toggle('can-scroll-left', canScrollLeft);
            wrapper.classList.toggle('can-scroll-right', canScrollRight);
        };
        
        wrapper.addEventListener('scroll', updateScrollIndicators, { passive: true });
        
        // Initial check
        setTimeout(updateScrollIndicators, 100);
        
        // Recheck on window resize
        window.addEventListener('resize', updateScrollIndicators, { passive: true });
    }
    
    setupTableColumnToggle() {
        if (this.currentBreakpoint !== 'mobile') return;
        
        // Create column toggle controls for mobile
        const tableContainer = document.querySelector('.table-container');
        if (!tableContainer || tableContainer.querySelector('.column-toggles')) return;
        
        const toggleContainer = document.createElement('div');
        toggleContainer.className = 'column-toggles mobile-only p-3 border-bottom';
        toggleContainer.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted fw-medium">Visible Columns</small>
                <button class="btn btn-link btn-sm p-0" id="toggleAllColumns">Toggle All</button>
            </div>
            <div class="d-flex flex-wrap gap-1">
                <button class="btn btn-outline-secondary btn-sm column-toggle active" data-column="date">Date</button>
                <button class="btn btn-outline-secondary btn-sm column-toggle active" data-column="days">Days</button>
                <button class="btn btn-outline-secondary btn-sm column-toggle active" data-column="stock">Stock</button>
                <button class="btn btn-outline-secondary btn-sm column-toggle active" data-column="vehicle">Vehicle</button>
                <button class="btn btn-outline-secondary btn-sm column-toggle active" data-column="status">Status</button>
            </div>
        `;
        
        tableContainer.insertBefore(toggleContainer, tableContainer.firstChild);
        
        // Handle column toggle
        const columnToggles = toggleContainer.querySelectorAll('.column-toggle');
        columnToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
                const column = toggle.dataset.column;
                this.toggleTableColumn(column, toggle.classList.contains('active'));
            });
        });
        
        // Handle toggle all
        const toggleAllBtn = toggleContainer.querySelector('#toggleAllColumns');
        toggleAllBtn.addEventListener('click', () => {
            const allActive = Array.from(columnToggles).every(t => t.classList.contains('active'));
            
            columnToggles.forEach(toggle => {
                if (allActive) {
                    toggle.classList.remove('active');
                } else {
                    toggle.classList.add('active');
                }
                
                const column = toggle.dataset.column;
                this.toggleTableColumn(column, toggle.classList.contains('active'));
            });
        });
    }
    
    toggleTableColumn(column, visible) {
        const table = document.querySelector('#inventoryTable');
        if (!table) return;
        
        const columnMap = {
            'date': 1,
            'days': 2,
            'stock': 3,
            'vehicle': 4,
            'status': 5
        };
        
        const columnIndex = columnMap[column];
        if (columnIndex === undefined) return;
        
        const headers = table.querySelectorAll(`th:nth-child(${columnIndex + 1})`);
        const cells = table.querySelectorAll(`td:nth-child(${columnIndex + 1})`);
        
        [...headers, ...cells].forEach(element => {
            element.style.display = visible ? '' : 'none';
        });
        
        // Update DataTable if available
        if (window.inventoryTable && window.inventoryTable.column) {
            try {
                window.inventoryTable.column(columnIndex).visible(visible);
            } catch (e) {
                console.debug('DataTable column toggle failed:', e);
            }
        }
    }
    
    setupMobileCardView() {
        // Create mobile card view container
        const tableContainer = document.querySelector('.table-container');
        if (!tableContainer) return;
        
        const cardView = document.createElement('div');
        cardView.className = 'mobile-card-view';
        cardView.innerHTML = '<div class="mobile-cards-container"></div>';
        
        tableContainer.appendChild(cardView);
        
        // Populate cards when table data changes
        this.updateMobileCardView();
    }
    
    updateMobileCardView() {
        if (this.currentBreakpoint !== 'mobile') return;
        
        const container = document.querySelector('.mobile-cards-container');
        const table = document.querySelector('#inventoryTable tbody');
        
        if (!container || !table) return;
        
        container.innerHTML = '';
        
        const rows = table.querySelectorAll('tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length === 0) return;
            
            const card = this.createMobileCard({
                date: cells[1]?.textContent?.trim() || '-',
                days: cells[2]?.textContent?.trim() || '-',
                stock: cells[3]?.textContent?.trim() || '-',
                vehicle: cells[4]?.textContent?.trim() || '-',
                status: cells[5]?.textContent?.trim() || '-',
            });
            
            container.appendChild(card);
        });
    }
    
    createMobileCard(data) {
        const card = document.createElement('div');
        card.className = 'mobile-inventory-card';
        
        // Determine badge class for days
        let badgeClass = 'bg-success';
        const days = parseInt(data.days);
        if (!isNaN(days)) {
            if (days >= 6) badgeClass = 'bg-danger';
            else if (days >= 2) badgeClass = 'bg-warning';
        }
        
        card.innerHTML = `
            <div class="mobile-card-header">
                <div class="mobile-card-stock">${data.stock}</div>
                <div class="badge ${badgeClass} mobile-card-days">${data.days}</div>
            </div>
            <div class="mobile-card-body">
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Date</span>
                    <span class="mobile-card-value">${data.date}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Vehicle</span>
                    <span class="mobile-card-value">${data.vehicle}</span>
                </div>
                <div class="mobile-card-row">
                    <span class="mobile-card-label">Status</span>
                    <span class="mobile-card-value">${data.status}</span>
                </div>
            </div>
            <div class="mobile-card-actions">
                <button class="btn btn-primary btn-sm flex-fill">
                    <i class="ri-arrow-right-line me-1"></i>Move
                </button>
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="ri-more-line"></i>
                </button>
            </div>
        `;
        
        // Add click handler
        const moveBtn = card.querySelector('.btn-primary');
        moveBtn?.addEventListener('click', () => {
            if (typeof window.moveToRecon === 'function') {
                window.moveToRecon(data.stock);
            }
        });
        
        return card;
    }
    
    updateTableView() {
        if (this.currentBreakpoint === 'mobile') {
            document.querySelector('.table-view')?.classList.add('d-none');
            document.querySelector('.mobile-card-view')?.classList.remove('d-none');
            this.updateMobileCardView();
        } else {
            document.querySelector('.table-view')?.classList.remove('d-none');
            document.querySelector('.mobile-card-view')?.classList.add('d-none');
        }
    }
    
    updateStatsGrid() {
        const statsContainer = document.querySelector('.row .g-2');
        if (!statsContainer) return;
        
        // Update grid classes based on breakpoint
        const cards = statsContainer.querySelectorAll('.col-xl, .col-lg-3, .col-md-6');
        
        cards.forEach(card => {
            // Remove existing responsive classes
            card.className = card.className.replace(/col-\w+-?\d*/g, '').trim();
            
            // Add appropriate classes for current breakpoint
            switch (this.currentBreakpoint) {
                case 'mobile':
                    card.className += ' col-12';
                    break;
                case 'tablet':
                    card.className += ' col-6';
                    break;
                case 'laptop':
                    card.className += ' col-4';
                    break;
                case 'desktop':
                    card.className += ' col-xl';
                    break;
            }
        });
    }
    
    setupKeyboardNavigation() {
        // Add keyboard navigation for mobile sidebar
        document.addEventListener('keydown', (e) => {
            if (this.sidebarOpen) {
                const sidebar = document.querySelector('.mobile-sidebar');
                const focusableElements = sidebar.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.key === 'Tab') {
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Alt + M to toggle mobile menu
            if (e.altKey && e.key === 'm' && this.currentBreakpoint === 'mobile') {
                e.preventDefault();
                this.toggleMobileSidebar();
            }
            
            // Alt + R to refresh
            if (e.altKey && e.key === 'r') {
                e.preventDefault();
                const refreshBtn = document.getElementById('refreshInventoryBtn');
                refreshBtn?.click();
            }
            
            // Alt + C to clear filters
            if (e.altKey && e.key === 'c') {
                e.preventDefault();
                const clearBtn = document.getElementById('clearAllFilters');
                clearBtn?.click();
            }
        });
    }
    
    setupIntersectionObserver() {
        if (!('IntersectionObserver' in window)) return;
        
        // Lazy load table rows for better performance
        const tableObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            rootMargin: '50px'
        });
        
        // Observe table rows
        const observeTableRows = () => {
            const rows = document.querySelectorAll('#inventoryTable tbody tr');
            rows.forEach(row => {
                if (!row.classList.contains('observed')) {
                    row.classList.add('observed');
                    tableObserver.observe(row);
                }
            });
        };
        
        // Initial observation
        setTimeout(observeTableRows, 1000);
        
        // Re-observe when table updates
        window.addEventListener('tablesReady', observeTableRows);
    }
    
    scrollToSection(target) {
        const element = document.querySelector(target);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
    
    // Public methods for external usage
    updateForNewData() {
        if (this.currentBreakpoint === 'mobile') {
            this.updateMobileCardView();
        }
    }
    
    getCurrentBreakpointInfo() {
        return {
            current: this.currentBreakpoint,
            isMobile: this.currentBreakpoint === 'mobile',
            isTablet: this.currentBreakpoint === 'tablet',
            isLaptop: this.currentBreakpoint === 'laptop',
            isDesktop: this.currentBreakpoint === 'desktop',
            isTouch: this.isTouch
        };
    }
}

// Initialize responsive manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.responsiveManager = new ResponsiveManager();
    
    // Make it globally available
    window.ResponsiveManager = ResponsiveManager;
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ResponsiveManager;
}