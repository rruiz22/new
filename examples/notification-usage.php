<?php

/**
 * EJEMPLOS DE USO DEL SISTEMA DE NOTIFICACIONES
 * ==============================================
 * 
 * Este archivo muestra cómo usar correctamente el nuevo sistema
 * de notificaciones Toast y SweetAlert2 en tu aplicación.
 */

// ========================================
// EJEMPLO 1: USO EN CONTROLADORES (PHP)
// ========================================

class ExampleController extends BaseController
{
    public function saveData()
    {
        // Procesar datos...
        
        if ($success) {
            // Notificación de éxito con Toast
            set_toast_success('Los datos se guardaron correctamente');
            return redirect()->to('lista');
        } else {
            // Notificación de error con Toast
            set_toast_error('Error al guardar los datos');
            return redirect()->back()->withInput();
        }
    }
    
    public function deleteItem($id)
    {
        // Eliminar elemento...
        
        if ($deleted) {
            set_toast_success('Elemento eliminado correctamente');
        } else {
            set_toast_error('No se pudo eliminar el elemento');
        }
        
        return redirect()->to('lista');
    }
    
    public function processForm()
    {
        // Diferentes tipos de notificaciones
        set_toast('Información importante', 'info');
        set_toast_warning('Revise los datos antes de continuar');
        set_toast_error('Faltan campos obligatorios');
        set_toast_success('Formulario procesado correctamente');
        
        return redirect()->to('formulario');
    }
}

?>

<!-- ========================================
EJEMPLO 2: USO EN VISTAS (JavaScript)
======================================== -->

<script>
// Toast notifications directamente en JavaScript
function ejemplosToast() {
    // Notificaciones básicas
    window.showSuccessToast('¡Operación exitosa!');
    window.showErrorToast('Error al procesar');
    window.showWarningToast('Advertencia importante');
    window.showInfoToast('Información útil');
    
    // Toast personalizado
    window.showToast('Mensaje personalizado', 'success', {
        duration: 8000,
        style: {
            background: 'linear-gradient(45deg, #405189, #0ab39c)'
        }
    });
}

// SweetAlert2 notifications
function ejemplosSweetAlert() {
    // Alerta de éxito
    window.showSuccessAlert({
        title: '¡Éxito!',
        text: 'La operación se completó correctamente'
    });
    
    // Alerta de error
    window.showErrorAlert({
        title: 'Error',
        text: 'Ocurrió un problema al procesar la solicitud'
    });
    
    // Confirmación
    window.showConfirmAlert({
        title: '¿Está seguro?',
        text: '¿Desea continuar con esta acción?'
    }).then((result) => {
        if (result.isConfirmed) {
            window.showSuccessToast('Acción confirmada');
        }
    });
    
    // Confirmación para eliminar
    window.showDeleteAlert({
        title: '¿Eliminar elemento?',
        text: 'Esta acción no se puede deshacer'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceder con la eliminación
            deleteElement();
        }
    });
    
    // Alerta de carga
    window.showLoadingAlert('Procesando...', 'Por favor espere');
    
    // Cerrar alerta de carga después de 3 segundos
    setTimeout(() => {
        window.hideLoadingAlert();
        window.showSuccessToast('Proceso completado');
    }, 3000);
}

// Uso en formularios AJAX
function enviarFormularioAjax() {
    const formData = new FormData(document.getElementById('miFormulario'));
    
    // Mostrar carga
    window.showLoadingAlert('Enviando...', 'Por favor espere');
    
    fetch('/api/enviar-formulario', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        window.hideLoadingAlert();
        
        if (data.success) {
            window.showSuccessToast('Formulario enviado correctamente');
        } else {
            window.showErrorToast('Error: ' + data.message);
        }
    })
    .catch(error => {
        window.hideLoadingAlert();
        window.showErrorToast('Error de conexión');
    });
}

// Manejo de respuestas AJAX con notificaciones
function manejarRespuestaAjax(response) {
    if (response.notification) {
        const { type, message } = response.notification;
        window.showToast(message, type);
    }
    
    if (response.swal) {
        const { title, text, icon } = response.swal;
        window.showSuccessAlert({ title, text, icon });
    }
}
</script>

<?php

// ========================================
// EJEMPLO 3: USO CON AJAX EN CONTROLADORES
// ========================================

class AjaxController extends BaseController
{
    public function processAjaxRequest()
    {
        // Procesar datos...
        
        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $result,
                'notification' => [
                    'type' => 'success',
                    'message' => 'Operación completada exitosamente'
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'notification' => [
                    'type' => 'error',
                    'message' => 'Error al procesar la solicitud'
                ]
            ]);
        }
    }
    
    public function deleteWithConfirmation($id)
    {
        // Para eliminar con confirmación SweetAlert2
        return $this->response->setJSON([
            'success' => true,
            'swal' => [
                'title' => '¿Eliminar elemento?',
                'text' => 'Esta acción no se puede deshacer',
                'icon' => 'warning',
                'showCancelButton' => true,
                'confirmButtonText' => 'Sí, eliminar',
                'cancelButtonText' => 'Cancelar'
            ]
        ]);
    }
}

// ========================================
// EJEMPLO 4: FUNCIONES DE UTILIDAD
// ========================================

// Verificar si hay notificaciones pendientes
if (has_notifications()) {
    $notifications = get_pending_notifications();
    // Procesar notificaciones...
}

// Limpiar todas las notificaciones
clear_all_notifications();

// Uso de funciones de compatibilidad
flash_message('success', 'Mensaje usando función legacy');
notification('Mensaje genérico', 'info', 'toast');

// ========================================
// EJEMPLO 5: USO EN VISTAS CON PHP
// ========================================

?>

<!-- En tus vistas PHP -->
<div class="row">
    <div class="col-12">
        <button onclick="confirmarEliminacion(<?= $item['id'] ?>)" class="btn btn-danger">
            Eliminar
        </button>
    </div>
</div>

<script>
function confirmarEliminacion(id) {
    window.showDeleteAlert({
        title: '¿Eliminar elemento?',
        text: 'Esta acción no se puede deshacer'
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar vía AJAX
            fetch(`<?= base_url('eliminar/') ?>${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.showSuccessToast('Elemento eliminado');
                    // Recargar tabla o remover elemento
                    location.reload();
                } else {
                    window.showErrorToast('Error al eliminar');
                }
            });
        }
    });
}
</script>

<?php

/**
 * NOTAS IMPORTANTES:
 * ==================
 * 
 * 1. FUNCIONES PHP DISPONIBLES:
 *    - set_toast_success($message)
 *    - set_toast_error($message)  
 *    - set_toast_warning($message)
 *    - set_toast_info($message)
 *    - set_toast($message, $type)
 * 
 * 2. FUNCIONES JAVASCRIPT DISPONIBLES:
 *    - window.showSuccessToast(message)
 *    - window.showErrorToast(message)
 *    - window.showWarningToast(message)
 *    - window.showInfoToast(message)
 *    - window.showToast(message, type, options)
 *    
 *    - window.showSuccessAlert(options)
 *    - window.showErrorAlert(options)
 *    - window.showConfirmAlert(options)
 *    - window.showDeleteAlert(options)
 *    - window.showLoadingAlert(title, text)
 *    - window.hideLoadingAlert()
 * 
 * 3. POSICIONAMIENTO:
 *    - Toast: Aparecen en bottom-right automáticamente
 *    - SweetAlert2: Aparecen centrados en la pantalla
 * 
 * 4. DEBUGGING:
 *    - Revisa la consola del navegador para logs
 *    - Revisa logs de CI4 para debugging PHP
 */

?> 