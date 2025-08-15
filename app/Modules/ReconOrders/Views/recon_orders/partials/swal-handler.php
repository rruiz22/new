<script>
/**
 * Global SweetAlert2 Handler for ReconOrders Module
 * Ensures consistent SweetAlert2 usage across all components
 */

// Wait for libraries to load
function waitForLibraries(callback) {
    if (typeof $ !== 'undefined' && typeof Swal !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForLibraries(callback);
        }, 100);
    }
}

waitForLibraries(function() {
    console.log('✅ SweetAlert2 and jQuery are available for ReconOrders');
    
    // Override global showToast if not properly defined
    if (typeof window.showToast === 'undefined' || !window.showToast.toString().includes('Swal')) {
        console.log('🔧 Setting up global showToast with SweetAlert2');
        
        window.showToast = function(message, type) {
            console.log('🍞 Global showToast called with:', { message, type });
            console.log('🔍 Swal available:', typeof Swal !== 'undefined');
            console.log('🔍 Message type:', typeof message);
            console.log('🔍 Type type:', typeof type);
            
            if (typeof Swal === 'undefined') {
                console.error('❌ SweetAlert2 (Swal) is not loaded!');
                alert(`${type}: ${message}`);
                return;
            }
            
            // Validate parameters
            if (!message || typeof message !== 'string') {
                console.error('❌ Invalid message parameter:', message);
                message = 'Unknown message';
            }
            
            if (!type || typeof type !== 'string') {
                console.error('❌ Invalid type parameter:', type);
                type = 'info';
            }
            
            const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
            console.log('🎯 Using icon:', icon, 'for type:', type);
            
            try {
                const toastConfig = {
                    icon: icon,
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                };
                
                console.log('🚀 Firing Swal with config:', toastConfig);
                Swal.fire(toastConfig);
            } catch (error) {
                console.error('❌ Error showing toast:', error);
                alert(message);
            }
        };
    }
    
    // Global confirmation dialog
    window.showConfirmDialog = function(title, text, confirmText = 'Yes', cancelText = 'Cancel') {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true
        });
    };
    
    // Global success dialog
    window.showSuccessDialog = function(title, text = '') {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    };
    
    // Global error dialog
    window.showErrorDialog = function(title, text = '') {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    };
    
    console.log('🎉 ReconOrders SweetAlert2 handler initialized successfully');
});
</script>
