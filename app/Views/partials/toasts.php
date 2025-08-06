<?php 
/**
 * Sistema de Notificaciones Toast - Actualizado
 * Este archivo se incluirá desde el layout principal
 * y mostrará notificaciones tipo toast usando el sistema unificado
 */
?>

<script>
// Verificar notificaciones pendientes cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {

    
    // Esperar a que el sistema de notificaciones esté listo
    setTimeout(function() {
        showPendingToasts();
    }, 1000);
});

/**
 * Mostrar notificaciones pendientes del servidor
 */
function showPendingToasts() {
    
    
    <?php if (session()->getFlashdata('toast_message')): ?>
        const toastType = "<?= session()->getFlashdata('toast_type') ?: 'info' ?>";
        const toastMessage = `<?= addslashes(session()->getFlashdata('toast_message')) ?>`;
        console.log('Toast encontrado:', toastMessage, 'Tipo:', toastType);
        
        if (typeof window.showToast === 'function') {
            window.showToast(toastMessage, toastType);
        } else {
            console.error('Sistema de notificaciones no disponible');
            // Fallback con alert
            alert(toastMessage);
        }
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('message')): ?>
        const successMessage = `<?= addslashes(session()->getFlashdata('message')) ?>`;
        console.log('Mensaje de éxito encontrado:', successMessage);
        
        if (typeof window.showSuccessToast === 'function') {
            window.showSuccessToast(successMessage);
        } else {
            console.error('Sistema de notificaciones no disponible');
            alert('✅ ' + successMessage);
        }
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        const errorMessage = `<?= addslashes(session()->getFlashdata('error')) ?>`;
        console.log('Mensaje de error encontrado:', errorMessage);
        
        if (typeof window.showErrorToast === 'function') {
            window.showErrorToast(errorMessage);
        } else {
            console.error('Sistema de notificaciones no disponible');
            alert('❌ ' + errorMessage);
        }
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('warning')): ?>
        const warningMessage = `<?= addslashes(session()->getFlashdata('warning')) ?>`;
        console.log('Mensaje de advertencia encontrado:', warningMessage);
        
        if (typeof window.showWarningToast === 'function') {
            window.showWarningToast(warningMessage);
        } else {
            console.error('Sistema de notificaciones no disponible');
            alert('⚠️ ' + warningMessage);
        }
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('info')): ?>
        const infoMessage = `<?= addslashes(session()->getFlashdata('info')) ?>`;
        console.log('Mensaje de información encontrado:', infoMessage);
        
        if (typeof window.showInfoToast === 'function') {
            window.showInfoToast(infoMessage);
        } else {
            console.error('Sistema de notificaciones no disponible');
            alert('ℹ️ ' + infoMessage);
        }
    <?php endif; ?>
}

// Función legacy para compatibilidad
function showToastMessage(type, message) {
    if (typeof window.showToast === 'function') {
        return window.showToast(message, type);
    } else {
        console.error('Sistema de notificaciones no disponible, usando fallback');
        alert(message);
        return false;
    }
}
</script> 