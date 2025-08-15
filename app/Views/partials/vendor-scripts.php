<!-- JAVASCRIPT -->
<script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>

<!-- jQuery Easing Fix - Must load before ApexCharts to prevent easing function errors -->
<script src="<?= base_url('assets/js/jquery-easing-fix.js') ?>"></script>

<!-- ApexCharts for dashboard charts - Load early for better timing -->
<script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js') ?>"></script>

<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/node-waves/waves.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/feather-icons/feather.min.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/plugins/lord-icon-2.1.0.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins.js') ?>"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<!-- Choices.js -->
<script src="<?= base_url('assets/libs/choices.js/public/assets/scripts/choices.min.js') ?>"></script>

<!-- Custom fixes for asset paths - COMMENTED OUT: Interferes with fixed language system -->
<!-- <script src="<?= base_url('assets/js/custom-fixes.js') ?>"></script> -->

<!-- SweetAlert2 -->
<script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

<!-- Toastify for notifications -->
<script src="<?= base_url('assets/libs/toastify/toastify.min.js') ?>"></script>

<!-- Notifications System - Provides window.showToast functions -->
<script src="<?= base_url('assets/js/notifications-system.js?v=2.0') ?>"></script>

<!-- Global Notifications System -->
<script src="<?= base_url('assets/js/global-notifications.js') ?>"></script>

<!-- Global Library Initialization and Verification -->
<script>
    // Global library availability verification
    function verifyGlobalLibraries() {
        console.log('🔍 Verifying global libraries...');
        
        // Verify jQuery
        if (typeof $ !== 'undefined' && typeof jQuery !== 'undefined') {
            console.log('✅ jQuery is available globally');
            window.jQueryAvailable = true;
        } else {
            console.error('❌ jQuery is NOT available globally');
            window.jQueryAvailable = false;
        }
        
        // Verify SweetAlert2
        if (typeof Swal !== 'undefined') {
            console.log('✅ SweetAlert2 is available globally');
            window.sweetAlert2Available = true;
            
            // Test SweetAlert2 functionality
            try {
                // Set global SweetAlert2 defaults
                Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    allowEscapeKey: true
                });
                console.log('✅ SweetAlert2 configured with global defaults');
            } catch (error) {
                console.warn('⚠️ Error configuring SweetAlert2:', error);
            }
        } else {
            console.error('❌ SweetAlert2 is NOT available globally');
            window.sweetAlert2Available = false;
        }
        
        // Verify Toastify
        if (typeof Toastify !== 'undefined') {
            console.log('✅ Toastify is available globally');
            window.toastifyAvailable = true;
        } else {
            console.warn('⚠️ Toastify is not available globally');
            window.toastifyAvailable = false;
        }
        
        // Verify Choices.js
        if (typeof Choices !== 'undefined') {
            console.log('✅ Choices.js is available globally');
            window.choicesJSAvailable = true;
        } else {
            console.warn('⚠️ Choices.js is not available globally');
            window.choicesJSAvailable = false;
        }
        
        // Summary
        const availableLibraries = [];
        if (window.jQueryAvailable) availableLibraries.push('jQuery');
        if (window.sweetAlert2Available) availableLibraries.push('SweetAlert2');
        if (window.toastifyAvailable) availableLibraries.push('Toastify');
        if (window.choicesJSAvailable) availableLibraries.push('Choices.js');
        
        console.log(`✅ Global libraries available: ${availableLibraries.join(', ')}`);
        console.log('🎯 All global libraries verified and ready for use!');
        
        return {
            jquery: window.jQueryAvailable,
            sweetAlert2: window.sweetAlert2Available,
            toastify: window.toastifyAvailable,
            choicesJS: window.choicesJSAvailable
        };
    }
    
    // Global SweetAlert2 helper functions
    window.showAlert = function(type, title, text, options = {}) {
        if (!window.sweetAlert2Available) {
            console.error('❌ SweetAlert2 not available for showAlert');
            alert(title + (text ? '\n' + text : ''));
            return;
        }
        
        const config = {
            icon: type,
            title: title,
            text: text,
            ...options
        };
        
        return Swal.fire(config);
    };
    
    // Global confirmation dialog
    window.showConfirmation = function(title, text, confirmButtonText = 'Yes', cancelButtonText = 'No') {
        if (!window.sweetAlert2Available) {
            return Promise.resolve(confirm(title + (text ? '\n' + text : '')));
        }
        
        return Swal.fire({
            icon: 'question',
            title: title,
            text: text,
            showCancelButton: true,
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText
        }).then((result) => result.isConfirmed);
    };
    
    // Global success message
    window.showSuccess = function(title, text = '') {
        return window.showAlert('success', title, text);
    };
    
    // Global error message
    window.showError = function(title, text = '') {
        return window.showAlert('error', title, text);
    };
    
    // Global warning message
    window.showWarning = function(title, text = '') {
        return window.showAlert('warning', title, text);
    };
    
    // Global info message
    window.showInfo = function(title, text = '') {
        return window.showAlert('info', title, text);
    };
</script>

<!-- Global Choices.js Initialization -->
<script>
    // Global Choices.js initialization and management
    window.choicesInstances = window.choicesInstances || {};
    
    // Initialize Choices.js for elements with data-choices attribute
    function initializeChoicesJS() {
        console.log('🎯 Initializing global Choices.js...');
        
        // Check if Choices.js is available using global verification
        if (!window.choicesJSAvailable) {
            console.warn('⚠️ Choices.js not available globally - initialization skipped');
            return false;
        }
        
        // Find all elements with data-choices attribute that aren't already initialized
        const choicesElements = document.querySelectorAll('[data-choices]:not(.choices__input)');
        
        choicesElements.forEach(function(element) {
            // Skip if already initialized
            if (element.classList.contains('choices__input')) {
                return;
            }
            
            try {
                // Build configuration from data attributes
                const config = {
                    searchEnabled: element.hasAttribute('data-choices-search-true'),
                    itemSelectText: '',
                    shouldSort: !element.hasAttribute('data-choices-sorting-false'),
                    placeholder: !element.hasAttribute('data-choices-no-placeholder'),
                    allowHTML: element.hasAttribute('data-choices-allow-html'),
                    removeItemButton: element.hasAttribute('data-choices-remove-button')
                };
                
                // Set placeholder text
                if (config.placeholder) {
                    const placeholderText = element.getAttribute('data-choices-placeholder') || 
                                          element.querySelector('option[value=""]')?.textContent || 
                                          'Select an option';
                    config.placeholderValue = placeholderText;
                }
                
                // Set search placeholder
                if (config.searchEnabled) {
                    config.searchPlaceholderValue = element.getAttribute('data-choices-search-placeholder') || 'Search...';
                }
                
                // Initialize Choices.js
                const choicesInstance = new Choices(element, config);
                
                // Store instance for later reference
                if (element.id) {
                    window.choicesInstances[element.id] = choicesInstance;
                }
                
                console.log(`✅ Initialized Choices.js for #${element.id || element.className}`);
                
            } catch (error) {
                console.error(`❌ Error initializing Choices.js for element:`, element, error);
            }
        });
        
        return true;
    }
    
    // Function to reinitialize Choices.js for dynamically loaded content
    window.reinitializeChoicesJS = function(container = document) {
        const choicesElements = container.querySelectorAll('[data-choices]:not(.choices__input)');
        choicesElements.forEach(function(element) {
            if (!element.classList.contains('choices__input')) {
                try {
                    const config = {
                        searchEnabled: element.hasAttribute('data-choices-search-true'),
                        itemSelectText: '',
                        shouldSort: !element.hasAttribute('data-choices-sorting-false'),
                        placeholder: !element.hasAttribute('data-choices-no-placeholder'),
                        allowHTML: element.hasAttribute('data-choices-allow-html')
                    };
                    
                    if (config.placeholder) {
                        const placeholderText = element.getAttribute('data-choices-placeholder') || 
                                              element.querySelector('option[value=""]')?.textContent || 
                                              'Select an option';
                        config.placeholderValue = placeholderText;
                    }
                    
                    const choicesInstance = new Choices(element, config);
                    
                    if (element.id) {
                        window.choicesInstances[element.id] = choicesInstance;
                    }
                    
                    console.log(`✅ Re-initialized Choices.js for #${element.id || element.className}`);
                } catch (error) {
                    console.error(`❌ Error re-initializing Choices.js:`, error);
                }
            }
        });
    };
    
    // Function to destroy all Choices.js instances
    window.destroyAllChoicesJS = function() {
        Object.values(window.choicesInstances).forEach(instance => {
            if (instance && typeof instance.destroy === 'function') {
                try {
                    instance.destroy();
                } catch (error) {
                    console.warn('Error destroying Choices instance:', error);
                }
            }
        });
        window.choicesInstances = {};
    };
</script>

<!-- CSRF Token Setup -->
<script>
    // Make CSRF token available globally for AJAX requests
    $(document).ready(function() {
        // Verify all global libraries first
        const libraryStatus = verifyGlobalLibraries();
        
        // Set up AJAX defaults with CSRF token
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                    const token = $('meta[name="<?= csrf_token() ?>"]').attr('content') || 
                                  $('input[name="<?= csrf_token() ?>"]').val();
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        if (settings.data) {
                            if (typeof settings.data === 'string') {
                                settings.data += '&<?= csrf_token() ?>=' + encodeURIComponent(token);
                            } else if (typeof settings.data === 'object') {
                                settings.data['<?= csrf_token() ?>'] = token;
                            }
                        }
                    }
                }
            }
        });
        
        // Update CSRF hash after successful requests
        $(document).ajaxSuccess(function(event, xhr, settings) {
            const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
            if (newToken) {
                $('meta[name="<?= csrf_token() ?>"]').attr('content', newToken);
                $('input[name="<?= csrf_token() ?>"]').val(newToken);
                window.csrfHash = newToken;
            }
        });
        
        // Initialize Choices.js after DOM is ready
        setTimeout(function() {
            initializeChoicesJS();
        }, 200);
        
        // Show body after everything is loaded
        setTimeout(function() {
            document.body.classList.add('loaded');
            
            // Final confirmation message
            console.log('🚀 System ready! All global libraries initialized and available:');
            console.log('   • jQuery: ' + (window.jQueryAvailable ? '✅' : '❌'));
            console.log('   • SweetAlert2: ' + (window.sweetAlert2Available ? '✅' : '❌'));
            console.log('   • Toastify: ' + (window.toastifyAvailable ? '✅' : '❌'));
            console.log('   • Choices.js: ' + (window.choicesJSAvailable ? '✅' : '❌'));
            console.log('🎯 Global helper functions available: showAlert(), showSuccess(), showError(), showWarning(), showInfo(), showConfirmation()');
        }, 300);
    });
</script>