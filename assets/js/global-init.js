/*
 * Global Initialization Script
 * Ensures proper loading order and asset path availability
 */

(function() {
    'use strict';
    
    // Ensure baseUrl is available
    function ensureBaseUrl() {
        return new Promise((resolve) => {
            function check() {
                if (window.baseUrl) {
                    resolve();
                } else {
                    setTimeout(check, 10);
                }
            }
            check();
        });
    }
    
    // Wait for both DOM and baseUrl to be ready
    async function initializeApp() {
        // Wait for baseUrl to be available
        await ensureBaseUrl();
        
        // Make sure DOM is ready
        if (document.readyState === 'loading') {
            await new Promise(resolve => {
                document.addEventListener('DOMContentLoaded', resolve);
            });
        }
        
        // Add initialization flag
        window.appInitialized = true;
        
        // Dispatch custom event when everything is ready
        window.dispatchEvent(new CustomEvent('appReady'));
        
        console.log('✅ App initialization complete');
    }
    
    // Start initialization
    initializeApp().catch(console.error);
})(); 