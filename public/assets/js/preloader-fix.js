/**
 * Preloader Fix
 * 
 * This script ensures backward compatibility with the renamed ID attributes
 * to avoid issues with scripts that might still reference the old IDs.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Fix for the renamed status elements
    ensurePreloaderWorks();
    
    function ensurePreloaderWorks() {
        // Get the preloader elements with new IDs
        const preloaderStatus = document.getElementById('preloader-status');
        const previewStatus = document.getElementById('preview-status');
        
        // Create compatibility references for scripts that might use the old IDs
        if (preloaderStatus) {
            // Create a virtual element with the old ID that proxies to the new one
            const statusProxy = document.createElement('div');
            statusProxy.id = 'status';
            statusProxy.style.display = 'none';
            document.body.appendChild(statusProxy);
            
            // Create a MutationObserver to watch for changes to aria-hidden on the layout-wrapper
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'style' || mutation.attributeName === 'class') {
                        // Proxy any style or class changes to the actual element
                        preloaderStatus.setAttribute('style', statusProxy.getAttribute('style'));
                        preloaderStatus.setAttribute('class', statusProxy.getAttribute('class'));
                    }
                });
            });
            
            // Start observing the proxy element
            observer.observe(statusProxy, { attributes: true });
        }
        
        // Handle the preloader initialization and hiding
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                // Hide preloader after page load
                setTimeout(function() {
                    preloader.style.opacity = '0';
                    preloader.style.visibility = 'hidden';
                    
                    setTimeout(function() {
                        preloader.style.display = 'none';
                    }, 300); // After fade out animation
                }, 500);
            }
        });
    }
}); 