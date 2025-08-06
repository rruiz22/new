<?php
/**
 * Este archivo se incluirá desde el layout principal
 * y manejará las alertas y notificaciones flash
 */
?>

<script>
// Simple alert functions using native browser alerts
(function() {

    
    // Define basic alert functions at window level
    window.basicAlerts = {
        // Success message alert
        success: function(title, message) {
            const displayMessage = title ? title + ": " + (message || '<?= lang('App.operation_success') ?>') : (message || '<?= lang('App.operation_success') ?>');
            alert(displayMessage);
        },
        
        // Error message alert
        error: function(title, message) {
            const displayMessage = title ? title + ": " + (message || '<?= lang('App.something_wrong') ?>') : (message || '<?= lang('App.something_wrong') ?>');
            alert(displayMessage);
        },
        
        // Warning message alert
        warning: function(title, message) {
            const displayMessage = title ? title + ": " + (message || '<?= lang('App.action_irreversible') ?>') : (message || '<?= lang('App.action_irreversible') ?>');
            alert(displayMessage);
        },
        
        // Confirmation dialog
        deleteConfirm: function(title, message, confirmCallback) {
            const displayMessage = message || '<?= lang('App.delete_confirmation') ?>';
            if (confirm(displayMessage)) {
                if (typeof confirmCallback === 'function') {
                    confirmCallback();
                }
            }
        }
    };
    

})();

// Función para mostrar confirmaciones básicas
function showConfirmation(title, text, confirmCallback, cancelCallback = null) {
    const displayMessage = text || '<?= lang('App.delete_confirmation') ?>';
    if (confirm(displayMessage)) {
        if (typeof confirmCallback === 'function') {
            confirmCallback();
        }
    } else if (typeof cancelCallback === 'function') {
        cancelCallback();
    }
}
</script>

<?php
// Handle legacy session messages in the background
if (session()->has('message')) {
    set_success(session('message'));
}
if (session()->has('error')) {
    set_error(session('error'));
}
if (session()->has('errors') && is_array(session('errors'))) {
    foreach (session('errors') as $error) {
        set_error($error);
    }
}
?> 