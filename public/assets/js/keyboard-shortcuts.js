/**
 * Global Keyboard Shortcuts System
 * Provides quick navigation between modules using number keys
 */

class KeyboardShortcuts {
    constructor() {
        this.shortcuts = {
            '1': {
                url: 'sales_orders',
                name: 'Sales Orders',
                icon: 'ri-shopping-cart-line'
            },
            '2': {
                url: 'service_orders',
                name: 'Service Orders', 
                icon: 'ri-tools-line'
            },
            '3': {
                url: 'car_wash',
                name: 'Car Wash',
                icon: 'ri-car-washing-line'
            },
            '4': {
                url: 'recon_orders',
                name: 'Recon Orders',
                icon: 'ri-search-eye-line'
            },
            '5': {
                url: 'vehicles',
                name: 'Vehicles',
                icon: 'ri-car-line'
            },
            '6': {
                url: 'clients',
                name: 'Clients',
                icon: 'ri-user-line'
            },
            '7': {
                url: 'contacts',
                name: 'Contacts',
                icon: 'ri-contacts-line'
            },
            '8': {
                url: 'settings',
                name: 'Settings',
                icon: 'ri-settings-line'
            },
            '9': {
                url: 'profile',
                name: 'Profile',
                icon: 'ri-user-settings-line'
            },
            '0': {
                url: '',
                name: 'Dashboard',
                icon: 'ri-dashboard-line'
            }
        };
        
        this.isEnabled = true;
        this.showNotifications = true;
        this.helpModalVisible = false;
        this.ctrlPressed = false;
        this.hintsVisible = false;
        this.originalTitles = new Map(); // Store original titles
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.createHelpModal();
        this.createHints();
        this.loadUserPreferences();
        this.initializeUI();
        
        // Show welcome notification on first load
        if (localStorage.getItem('keyboard_shortcuts_welcome') !== 'shown') {
            setTimeout(() => {
                this.showWelcomeNotification();
                localStorage.setItem('keyboard_shortcuts_welcome', 'shown');
            }, 2000);
        }
    }
    
    initializeUI() {
        // Add visual indicator to keyboard shortcuts button
        const shortcutsBtn = document.getElementById('keyboard-shortcuts-btn');
        if (shortcutsBtn) {
            // Add a subtle pulse animation when shortcuts are enabled
            if (this.isEnabled) {
                shortcutsBtn.classList.add('shortcuts-enabled');
            }
            
            // Update button state when shortcuts are toggled
            this.updateButtonState();
        }
    }
    
    updateButtonState() {
        const shortcutsBtn = document.getElementById('keyboard-shortcuts-btn');
        if (shortcutsBtn) {
            if (this.isEnabled) {
                shortcutsBtn.classList.add('shortcuts-enabled');
                shortcutsBtn.classList.remove('shortcuts-disabled');
                shortcutsBtn.title = 'Keyboard Shortcuts (Press ? for help)';
            } else {
                shortcutsBtn.classList.remove('shortcuts-enabled');
                shortcutsBtn.classList.add('shortcuts-disabled');
                shortcutsBtn.title = 'Keyboard Shortcuts (Disabled - Press Ctrl+/ to enable)';
            }
        }
    }
    
    bindEvents() {
        document.addEventListener('keydown', (e) => {
            this.handleKeyDown(e);
        });
        
        document.addEventListener('keyup', (e) => {
            this.handleKeyUp(e);
        });
        
        // Prevent shortcuts in input fields
        document.addEventListener('focusin', (e) => {
            if (this.isInputElement(e.target)) {
                this.isEnabled = false;
            }
        });
        
        document.addEventListener('focusout', (e) => {
            if (this.isInputElement(e.target)) {
                this.isEnabled = true;
            }
        });
    }
    
    handleKeyDown(e) {
        // Track Ctrl key state
        if (e.key === 'Control') {
            this.ctrlPressed = true;
            if (this.isEnabled && !this.isInputElement(e.target)) {
                this.showHints();
            }
            return;
        }
        
        this.handleKeyPress(e);
    }
    
    handleKeyUp(e) {
        // Track Ctrl key release
        if (e.key === 'Control') {
            this.ctrlPressed = false;
            this.hideHints();
            return;
        }
    }
    
    handleKeyPress(e) {
        // Don't trigger if shortcuts are disabled or user is typing
        if (!this.isEnabled || this.isInputElement(e.target)) {
            return;
        }
        
        // Handle help modal toggle (? key)
        if (e.key === '?' && !e.ctrlKey && !e.altKey && !e.metaKey) {
            e.preventDefault();
            this.toggleHelpModal();
            return;
        }
        
        // Handle Escape key to close help modal
        if (e.key === 'Escape' && this.helpModalVisible) {
            e.preventDefault();
            this.hideHelpModal();
            return;
        }
        
        // Handle Escape key to go home (when not in modal)
        if (e.key === 'Escape' && !this.helpModalVisible && !e.ctrlKey && !e.altKey && !e.metaKey) {
            e.preventDefault();
            this.navigateToHome();
            return;
        }
        
        // Handle number keys for navigation
        if (this.shortcuts[e.key] && !e.ctrlKey && !e.altKey && !e.metaKey) {
            e.preventDefault();
            this.navigateToModule(e.key);
            return;
        }
        
        // Handle Ctrl+number keys for tab navigation within modules
        if ((e.ctrlKey || e.metaKey) && e.key >= '1' && e.key <= '9') {
            e.preventDefault();
            this.navigateToTab(e.key);
            return;
        }
        
        // Handle Ctrl+/ or Cmd+/ to toggle shortcuts
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            this.toggleShortcuts();
        }
    }
    
    createHints() {
        // Create hints container
        const hintsContainer = document.createElement('div');
        hintsContainer.id = 'keyboard-hints-container';
        hintsContainer.className = 'keyboard-hints-container';
        document.body.appendChild(hintsContainer);
        
        this.hintsContainer = hintsContainer;
    }
    
    showHints() {
        if (this.hintsVisible || !this.isEnabled) return;
        
        this.hintsVisible = true;
        this.hintsContainer.innerHTML = '';
        
        // Find all menu items and add hints to their titles
        const menuItems = this.findMenuItems();
        
        menuItems.forEach(item => {
            const shortcutKey = this.getShortcutForUrl(item.url);
            if (shortcutKey) {
                this.addHintToTitle(item.element, shortcutKey, item.type);
            }
        });
        
        // Find all tabs and add hints to their titles
        this.addTabHintsToTitles();
        
        // Show hints container
        this.hintsContainer.classList.add('visible');
        
        // Add minimal floating hint
        this.createFloatingHint('? = help', 'bottom-right');
    }
    
    hideHints() {
        if (!this.hintsVisible) return;
        
        this.hintsVisible = false;
        this.hintsContainer.classList.remove('visible');
        
        // Restore original titles
        this.restoreOriginalTitles();
        
        // Clear all hints after animation
        setTimeout(() => {
            this.hintsContainer.innerHTML = '';
        }, 300);
    }
    
    findMenuItems() {
        const items = [];
        
        // Find sidebar menu items - more specific selectors
        const sidebarSelectors = [
            '.navbar-nav .nav-link[href]',
            '.menu-link[href]',
            '#sidebar-menu .nav-link[href]',
            '.vertical-menu .nav-link[href]',
            '.sidebar .nav-link[href]',
            '.main-menu .nav-link[href]',
            '.side-nav .nav-link[href]',
            '[data-key] a[href]'
        ];
        
        sidebarSelectors.forEach(selector => {
            const links = document.querySelectorAll(selector);
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href && !href.includes('javascript:') && !href.includes('#')) {
                    const url = this.extractModuleFromUrl(href);
                    const name = this.getElementText(link);
                    if (url && name && !this.isDuplicate(items, url, name)) {
                        items.push({ 
                            element: link, 
                            url, 
                            name,
                            type: 'sidebar'
                        });
                    }
                }
            });
        });
        
        // Find topbar items
        const topbarSelectors = [
            '.header-item a[href]',
            '.topbar-head-dropdown a[href]',
            '.navbar-header a[href]',
            '.topbar a[href]'
        ];
        
        topbarSelectors.forEach(selector => {
            const links = document.querySelectorAll(selector);
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href && !href.includes('javascript:') && !href.includes('#')) {
                    const url = this.extractModuleFromUrl(href);
                    const name = this.getElementText(link) || link.getAttribute('title') || '';
                    if (url && name && !this.isDuplicate(items, url, name)) {
                        items.push({ 
                            element: link, 
                            url, 
                            name,
                            type: 'topbar'
                        });
                    }
                }
            });
        });
        
        return items;
    }
    
    getElementText(element) {
        // Try to get clean text from element
        let text = '';
        
        // First try direct text content
        const directText = element.textContent?.trim();
        if (directText && directText.length > 0 && directText.length < 50) {
            text = directText;
        }
        
        // If no direct text, try span inside
        if (!text) {
            const span = element.querySelector('span');
            if (span) {
                text = span.textContent?.trim() || '';
            }
        }
        
        // Clean up the text
        text = text.replace(/\s+/g, ' ').trim();
        
        // Remove common menu artifacts
        text = text.replace(/^\d+\s*/, ''); // Remove leading numbers
        text = text.replace(/\s*\(\d+\)$/, ''); // Remove trailing counts
        
        return text;
    }
    
    isDuplicate(items, url, name) {
        return items.some(item => item.url === url || item.name === name);
    }
    
    extractModuleFromUrl(href) {
        try {
            const url = new URL(href, window.location.origin);
            const path = url.pathname.replace(/^\/[^\/]*\//, ''); // Remove base path
            return path.split('/')[0] || '';
        } catch (e) {
            return '';
        }
    }
    
    getShortcutForUrl(url) {
        for (const [key, shortcut] of Object.entries(this.shortcuts)) {
            if (shortcut.url === url || (url === '' && shortcut.url === '')) {
                return key;
            }
        }
        return null;
    }
    
    createHint(element, key, name, type = 'sidebar') {
        const rect = element.getBoundingClientRect();
        
        const hint = document.createElement('span');
        hint.className = `keyboard-hint ${type}-hint`;
        hint.textContent = key;
        
        // Position hint next to element (simple positioning)
        let left = rect.right + 5;
        let top = rect.top + (rect.height / 2) - 8;
        
        // Adjust positioning for screen edges
        const viewportWidth = window.innerWidth;
        
        // If hint would go off right edge, position it to the left
        if (left + 50 > viewportWidth) {
            left = rect.left - 25;
            hint.classList.add('hint-left');
        }
        
        hint.style.left = left + 'px';
        hint.style.top = top + 'px';
        
        this.hintsContainer.appendChild(hint);
        
        // Animate in
        setTimeout(() => {
            hint.classList.add('show');
        }, Math.random() * 100); // Stagger animations
    }
    
    createFloatingHint(text, position = 'bottom-right') {
        const hint = document.createElement('div');
        hint.className = `keyboard-hint floating-hint ${position}`;
        hint.innerHTML = `<div class="hint-text">${text}</div>`;
        
        this.hintsContainer.appendChild(hint);
        
        setTimeout(() => {
            hint.classList.add('show');
        }, 50);
    }
    
    navigateToModule(key) {
        const shortcut = this.shortcuts[key];
        if (!shortcut) return;
        
        // Hide hints if visible
        this.hideHints();
        
        // Show navigation notification
        if (this.showNotifications) {
            this.showNavigationNotification(shortcut);
        }
        
        // Navigate to the module
        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;
        const targetUrl = shortcut.url ? `${baseUrl}/${shortcut.url}` : baseUrl;
        
        // Add loading effect
        this.showLoadingIndicator();
        
        // Navigate after a brief delay to show the notification
        setTimeout(() => {
            window.location.href = targetUrl;
        }, 300);
    }
    
    navigateToHome() {
        // Hide hints if visible
        this.hideHints();
        
        // Show navigation notification
        if (this.showNotifications) {
            this.showNavigationNotification({ name: 'Home', url: '' });
        }
        
        // Navigate to home/dashboard
        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || window.location.origin;
        
        // Add loading effect
        this.showLoadingIndicator();
        
        // Navigate after a brief delay to show the notification
        setTimeout(() => {
            window.location.href = baseUrl;
        }, 300);
    }
    
    navigateToTab(key) {
        const tabIndex = parseInt(key) - 1; // Convert to 0-based index
        
        // Try different tab selectors (ordered by priority)
        const tabSelectors = [
            '.nav-tabs .nav-link:not(.disabled)',           // Bootstrap tabs
            '.nav-pills .nav-link:not(.disabled)',          // Bootstrap pills
            '.tab-nav .nav-link:not(.disabled)',            // Custom tab nav
            '[role="tab"]:not(.disabled)',                  // ARIA tabs
            '.tab-button:not(.disabled)',                   // Custom tab buttons
            '.nav-item .nav-link:not(.disabled)',           // Bootstrap nav items
            '.tabs .tab:not(.disabled)',                    // Generic tab class
            '.module-tabs .nav-link:not(.disabled)',        // Module specific tabs
            '.page-tabs .nav-link:not(.disabled)',          // Page specific tabs
            '.nav .nav-link:not(.disabled)'                 // Generic nav links
        ];
        
        let tabFound = false;
        
        for (const selector of tabSelectors) {
            const tabs = document.querySelectorAll(selector);
            if (tabs.length > tabIndex) {
                const targetTab = tabs[tabIndex];
                
                // Check if tab is visible and clickable
                if (targetTab && targetTab.offsetParent !== null && !targetTab.hasAttribute('disabled')) {
                    // Trigger click event
                    targetTab.click();
                    
                    // Also trigger focus for accessibility
                    targetTab.focus();
                    
                    tabFound = true;
                    
                    // Show success notification
                    if (this.showNotifications && typeof Toastify !== 'undefined') {
                        const tabText = this.getCleanTabText(targetTab);
                        Toastify({
                            text: `📑 Switched to ${tabText}`,
                            duration: 1500,
                            gravity: "top",
                            position: "right",
                            style: { 
                                background: "#28a745",
                                borderRadius: "6px",
                                fontSize: "14px"
                            }
                        }).showToast();
                    }
                    break;
                }
            }
        }
        
        // If no tab found, show error notification
        if (!tabFound && this.showNotifications && typeof Toastify !== 'undefined') {
            Toastify({
                text: `❌ Tab ${key} not available on this page`,
                duration: 2000,
                gravity: "top",
                position: "right",
                style: { 
                    background: "#dc3545",
                    borderRadius: "6px",
                    fontSize: "14px"
                }
            }).showToast();
        }
    }
    
    getCleanTabText(tabElement) {
        // Get clean text from tab element
        let text = tabElement.textContent || tabElement.innerText || '';
        
        // Clean up the text
        text = text.trim();
        text = text.replace(/\s+/g, ' '); // Replace multiple spaces with single space
        text = text.replace(/^\d+\s*/, ''); // Remove leading numbers
        text = text.replace(/\s*\(\d+\)$/, ''); // Remove trailing counts like (5)
        text = text.replace(/\s*\[\d+\]$/, ''); // Remove trailing counts like [5]
        
        // Limit length for hints
        if (text.length > 20) {
            text = text.substring(0, 20) + '...';
        }
        
        return text || 'Tab';
    }
    
    ensureIconsArePreserved(element) {
        // Common icon selectors to ensure they're preserved
        const iconSelectors = [
            'i[class*="ri-"]',      // Remix icons
            'i[class*="fa-"]',      // Font Awesome
            'i[class*="bx-"]',      // Boxicons  
            'i[class*="feather-"]', // Feather icons
            'i[class*="icon-"]',    // Generic icons
            '.icon',                // Generic icon class
            'svg'                   // SVG icons
        ];
        
        // Check if element has any icons
        let hasIcons = false;
        iconSelectors.forEach(selector => {
            if (element.querySelector(selector)) {
                hasIcons = true;
            }
        });
        
        return hasIcons;
    }
    
    isSidebarElement(element) {
        // Check if element is part of sidebar navigation
        const sidebarSelectors = [
            '.navbar-nav',
            '.sidebar',
            '.vertical-menu',
            '.main-menu',
            '.side-nav',
            '[data-simplebar]'
        ];
        
        return sidebarSelectors.some(selector => {
            return element.closest(selector) !== null;
        });
    }
    
    addHintToTitle(element, key, type) {
        // Store original HTML and styles if not already stored
        if (!this.originalTitles.has(element)) {
            const originalHTML = element.innerHTML.trim();
            const originalStyles = {
                display: element.style.display || '',
                alignItems: element.style.alignItems || '',
                justifyContent: element.style.justifyContent || ''
            };
            this.originalTitles.set(element, { html: originalHTML, styles: originalStyles });
        }
        
        // Check if element already has a shortcut hint to avoid duplicates
        if (element.querySelector('.keyboard-shortcut-hint')) {
            return;
        }
        
        // Get the original HTML
        const originalData = this.originalTitles.get(element);
        const originalHTML = originalData.html;
        
        // Create hint span with improved styling
        const hintSpan = document.createElement('span');
        hintSpan.className = `keyboard-shortcut-hint ${type}-hint`;
        hintSpan.textContent = ` (${key})`;
        
        // Apply styling that works well with different layouts
        const colorMap = {
            'sidebar': '#28a745',
            'topbar': '#17a2b8', 
            'tab': '#6f42c1'
        };
        
        // Check if this is a sidebar element for conservative styling
        const isSidebar = this.isSidebarElement(element);
        
        // Apply conservative styling that preserves sidebar layout
        hintSpan.style.cssText = `
            color: ${colorMap[type] || '#6c757d'};
            font-size: ${isSidebar ? '0.7em' : '0.75em'};
            font-weight: 500;
            opacity: ${isSidebar ? '0.6' : '0.7'};
            font-family: monospace;
            margin-left: ${isSidebar ? '2px' : '4px'};
            display: inline;
            white-space: nowrap;
            position: relative;
            top: ${isSidebar ? '-1px' : '0'};
        `;
        
        // Update element content preserving all original HTML structure
        element.innerHTML = originalHTML + hintSpan.outerHTML;
    }
    
    addTabHintsToTitles() {
        // Try different tab selectors to find visible tabs
        const tabSelectors = [
            '.nav-tabs .nav-link:not(.disabled)',           // Bootstrap tabs
            '.nav-pills .nav-link:not(.disabled)',          // Bootstrap pills
            '.tab-nav .nav-link:not(.disabled)',            // Custom tab nav
            '[role="tab"]:not(.disabled)',                  // ARIA tabs
            '.tab-button:not(.disabled)',                   // Custom tab buttons
            '.nav-item .nav-link:not(.disabled)',           // Bootstrap nav items
            '.tabs .tab:not(.disabled)',                    // Generic tab class
            '.module-tabs .nav-link:not(.disabled)',        // Module specific tabs
            '.page-tabs .nav-link:not(.disabled)',          // Page specific tabs
            '.nav .nav-link:not(.disabled)'                 // Generic nav links
        ];
        
        let tabsFound = false;
        
        for (const selector of tabSelectors) {
            const tabs = document.querySelectorAll(selector);
            
            if (tabs.length > 1) { // Only show hints if there are multiple tabs
                tabs.forEach((tab, index) => {
                    if (index < 9 && tab.offsetParent !== null && !tab.hasAttribute('disabled')) {
                        const tabNumber = index + 1;
                        this.addHintToTitle(tab, `Ctrl+${tabNumber}`, 'tab');
                        tabsFound = true;
                    }
                });
                
                // If we found tabs with this selector, don't try other selectors
                if (tabsFound) {
                    break;
                }
            }
        }
    }
    
    restoreOriginalTitles() {
        // Restore all original HTML content and styles
        this.originalTitles.forEach((originalData, element) => {
            if (element && element.parentNode) {
                // Restore HTML
                element.innerHTML = originalData.html;
                
                // Restore original styles
                element.style.display = originalData.styles.display;
                element.style.alignItems = originalData.styles.alignItems;
                element.style.justifyContent = originalData.styles.justifyContent;
            }
        });
        
        // Clear the map for next time
        this.originalTitles.clear();
    }
    
    addTabHints() {
        // Try different tab selectors to find visible tabs
        const tabSelectors = [
            '.nav-tabs .nav-link:not(.disabled)',           // Bootstrap tabs
            '.nav-pills .nav-link:not(.disabled)',          // Bootstrap pills
            '.tab-nav .nav-link:not(.disabled)',            // Custom tab nav
            '[role="tab"]:not(.disabled)',                  // ARIA tabs
            '.tab-button:not(.disabled)',                   // Custom tab buttons
            '.nav-item .nav-link:not(.disabled)',           // Bootstrap nav items
            '.tabs .tab:not(.disabled)',                    // Generic tab class
            '.module-tabs .nav-link:not(.disabled)',        // Module specific tabs
            '.page-tabs .nav-link:not(.disabled)',          // Page specific tabs
            '.nav .nav-link:not(.disabled)'                 // Generic nav links
        ];
        
        let tabsFound = false;
        
        for (const selector of tabSelectors) {
            const tabs = document.querySelectorAll(selector);
            
            if (tabs.length > 1) { // Only show hints if there are multiple tabs
                tabs.forEach((tab, index) => {
                    if (index < 9 && tab.offsetParent !== null && !tab.hasAttribute('disabled')) {
                        const tabNumber = index + 1;
                        const tabText = this.getCleanTabText(tab);
                        
                        // Create hint for this tab
                        this.createHint(tab, `Ctrl+${tabNumber}`, tabText, 'tab');
                        tabsFound = true;
                    }
                });
                
                // If we found tabs with this selector, don't try other selectors
                if (tabsFound) {
                    break;
                }
            }
        }
        
        // Removed general floating hint to reduce clutter
    }
    
    showNavigationNotification(shortcut) {
        // Remove any existing navigation notifications
        const existing = document.querySelector('.shortcut-notification');
        if (existing) {
            existing.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = 'shortcut-notification';
        notification.innerHTML = `
            <div class="shortcut-notification-content">
                <i class="${shortcut.icon} shortcut-icon"></i>
                <span class="shortcut-text">Navigating to ${shortcut.name}...</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Remove after animation
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 2000);
    }
    
    showWelcomeNotification() {
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: "🚀 Keyboard shortcuts active! Hold Ctrl to see hints, use 1-9,0 to navigate, or press '?' for help",
                duration: 6000,
                gravity: "top",
                position: "right",
                style: { 
                    background: "#405189",
                    borderRadius: "8px",
                    fontSize: "14px"
                },
                onClick: () => {
                    this.showHelpModal();
                }
            }).showToast();
        }
    }
    
    showLoadingIndicator() {
        // Create a subtle loading indicator
        const loader = document.createElement('div');
        loader.className = 'shortcut-loader';
        loader.innerHTML = '<div class="shortcut-loader-spinner"></div>';
        document.body.appendChild(loader);
        
        setTimeout(() => {
            if (loader.parentNode) {
                loader.remove();
            }
        }, 1000);
    }
    
    createHelpModal() {
        const modal = document.createElement('div');
        modal.className = 'shortcuts-help-modal';
        modal.innerHTML = `
            <div class="shortcuts-help-overlay"></div>
            <div class="shortcuts-help-content">
                <div class="shortcuts-help-header">
                    <h3><i class="ri-keyboard-line me-2"></i>Keyboard Shortcuts</h3>
                    <button class="shortcuts-help-close" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="shortcuts-help-body">
                    <div class="shortcuts-grid">
                        ${Object.entries(this.shortcuts).map(([key, shortcut]) => `
                            <div class="shortcut-item">
                                <div class="shortcut-key">
                                    <kbd>${key}</kbd>
                                </div>
                                <div class="shortcut-info">
                                    <i class="${shortcut.icon} shortcut-item-icon"></i>
                                    <span>${shortcut.name}</span>
                                </div>
                            </div>
                        `).join('')}
                        <div class="shortcut-item special">
                            <div class="shortcut-key">
                                <kbd>Ctrl</kbd>
                            </div>
                            <div class="shortcut-info">
                                <i class="ri-eye-line shortcut-item-icon"></i>
                                <span>Show hints (hold)</span>
                            </div>
                        </div>
                        <div class="shortcut-item special">
                            <div class="shortcut-key">
                                <kbd>?</kbd>
                            </div>
                            <div class="shortcut-info">
                                <i class="ri-question-line shortcut-item-icon"></i>
                                <span>Show this help</span>
                            </div>
                        </div>
                        <div class="shortcut-item special">
                            <div class="shortcut-key">
                                <kbd>Escape</kbd>
                            </div>
                            <div class="shortcut-info">
                                <i class="ri-home-line shortcut-item-icon"></i>
                                <span>Go to Home</span>
                            </div>
                        </div>
                        <div class="shortcut-item special">
                            <div class="shortcut-key">
                                <kbd>Ctrl</kbd> + <kbd>1-9</kbd>
                            </div>
                            <div class="shortcut-info">
                                <i class="ri-file-list-line shortcut-item-icon"></i>
                                <span>Switch to Tab</span>
                            </div>
                        </div>
                        <div class="shortcut-item special">
                            <div class="shortcut-key">
                                <kbd>Ctrl</kbd> + <kbd>/</kbd>
                            </div>
                            <div class="shortcut-info">
                                <i class="ri-toggle-line shortcut-item-icon"></i>
                                <span>Toggle shortcuts</span>
                            </div>
                        </div>
                    </div>
                    <div class="shortcuts-help-footer">
                        <div class="shortcuts-settings">
                            <label class="shortcuts-checkbox">
                                <input type="checkbox" id="showNotificationsToggle" ${this.showNotifications ? 'checked' : ''}>
                                <span class="checkmark"></span>
                                Show navigation notifications
                            </label>
                        </div>
                        <p class="shortcuts-note">
                            <i class="ri-information-line me-1"></i>
                            Hold Ctrl to see hints on menu items. Shortcuts are disabled when typing in input fields.
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Bind events
        modal.querySelector('.shortcuts-help-close').addEventListener('click', () => {
            this.hideHelpModal();
        });
        
        modal.querySelector('.shortcuts-help-overlay').addEventListener('click', () => {
            this.hideHelpModal();
        });
        
        modal.querySelector('#showNotificationsToggle').addEventListener('change', (e) => {
            this.showNotifications = e.target.checked;
            this.saveUserPreferences();
        });
        
        this.helpModal = modal;
    }
    
    showHelpModal() {
        if (this.helpModal) {
            this.helpModal.classList.add('show');
            this.helpModalVisible = true;
            document.body.style.overflow = 'hidden';
        }
    }
    
    hideHelpModal() {
        if (this.helpModal) {
            this.helpModal.classList.remove('show');
            this.helpModalVisible = false;
            document.body.style.overflow = '';
        }
    }
    
    toggleHelpModal() {
        if (this.helpModalVisible) {
            this.hideHelpModal();
        } else {
            this.showHelpModal();
        }
    }
    
    toggleShortcuts() {
        this.isEnabled = !this.isEnabled;
        
        // Update button state
        this.updateButtonState();
        
        // Hide hints if shortcuts are disabled
        if (!this.isEnabled) {
            this.hideHints();
        }
        
        const message = this.isEnabled ? 
            '✅ Keyboard shortcuts enabled' : 
            '❌ Keyboard shortcuts disabled';
            
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 2000,
                gravity: "top",
                position: "right",
                style: { 
                    background: this.isEnabled ? "#28a745" : "#dc3545",
                    borderRadius: "6px"
                }
            }).showToast();
        }
        
        this.saveUserPreferences();
    }
    
    isInputElement(element) {
        const inputTypes = ['input', 'textarea', 'select'];
        const editableElements = element.isContentEditable;
        
        return inputTypes.includes(element.tagName.toLowerCase()) || 
               editableElements ||
               element.closest('[contenteditable="true"]') !== null;
    }
    
    saveUserPreferences() {
        const preferences = {
            enabled: this.isEnabled,
            showNotifications: this.showNotifications
        };
        localStorage.setItem('keyboard_shortcuts_preferences', JSON.stringify(preferences));
    }
    
    loadUserPreferences() {
        try {
            const saved = localStorage.getItem('keyboard_shortcuts_preferences');
            if (saved) {
                const preferences = JSON.parse(saved);
                this.isEnabled = preferences.enabled !== false; // Default to true
                this.showNotifications = preferences.showNotifications !== false; // Default to true
            }
        } catch (e) {
            console.warn('Could not load keyboard shortcuts preferences:', e);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if not already initialized
    if (!window.keyboardShortcuts) {
        window.keyboardShortcuts = new KeyboardShortcuts();
    }
});

// Also initialize immediately if DOM is already loaded
if (document.readyState === 'loading') {
    // DOM is still loading
} else {
    // DOM is already loaded
    if (!window.keyboardShortcuts) {
        window.keyboardShortcuts = new KeyboardShortcuts();
    }
}
