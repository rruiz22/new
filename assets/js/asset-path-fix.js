/*
 * Asset Path Fix Script
 * Ensures proper base URL usage across all scripts
 */

(function() {
    'use strict';
    
    // Wait for DOM and ensure baseUrl is available
    function waitForInit() {
        if (!window.baseUrl) {
            setTimeout(waitForInit, 50);
            return;
        }
        
        // All scripts are now ready to use window.baseUrl
        console.log('✅ Asset paths ready with baseUrl:', window.baseUrl);
        
        // Dispatch event that base URL is ready
        window.dispatchEvent(new CustomEvent('baseUrlReady', {
            detail: { baseUrl: window.baseUrl }
        }));
    }
    
    // Start waiting
    waitForInit();
})(); 