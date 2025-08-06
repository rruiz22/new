<?php

/**
 * Alert Helper - Sistema de Notificaciones Actualizado
 * 
 * Este helper proporciona funciones para configurar alertas y notificaciones
 * que serán mostradas usando el sistema unificado de notificaciones.
 */

if (!function_exists('set_alert')) {
    /**
     * Set Alert Message
     *
     * Sets an alert message to be displayed on the next page load
     *
     * @param string $message The message to display
     * @param string $type The type of alert: success, danger, warning, info
     * @return void
     */
    function set_alert($message, $type = 'info')
    {
        $session = session();
        $session->setFlashdata('alert_message', $message);
        $session->setFlashdata('alert_type', $type);
        
        // También configurar como toast para mayor compatibilidad
        set_toast($message, $type);
    }
}

if (!function_exists('has_alerts')) {
    /**
     * Has Alerts
     *
     * Checks if there are any alerts to display
     *
     * @return bool True if there are alerts, false otherwise
     */
    function has_alerts()
    {
        return !empty(session()->getFlashdata('alerts'));
    }
}

if (!function_exists('get_alerts')) {
    /**
     * Get Alerts
     *
     * Gets all alert messages
     *
     * @return array Array of alert messages
     */
    function get_alerts()
    {
        return session()->getFlashdata('alerts') ?? [];
    }
}

if (!function_exists('clear_alerts')) {
    /**
     * Clear Alerts
     *
     * Clears all alert messages
     *
     * @return void
     */
    function clear_alerts()
    {
        session()->setFlashdata('alerts', []);
    }
}

// Additional shortcut functions for different types of alerts

if (!function_exists('set_success')) {
    function set_success($message)
    {
        set_alert($message, 'success');
    }
}

if (!function_exists('set_error')) {
    function set_error($message)
    {
        set_alert($message, 'danger');
    }
}

if (!function_exists('set_warning')) {
    function set_warning($message)
    {
        set_alert($message, 'warning');
    }
}

if (!function_exists('set_info')) {
    function set_info($message)
    {
        set_alert($message, 'info');
    }
}

// ========================================
// FUNCIONES DE TOAST ACTUALIZADAS
// ========================================

if (!function_exists('set_toast')) {
    /**
     * Set Toast Notification
     *
     * Sets a toast notification to be displayed on the next page load
     *
     * @param string $message The message to display
     * @param string $type The type of toast: success, error, warning, info
     * @return void
     */
    function set_toast($message, $type = 'info')
    {
        $session = session();
        
        // Normalizar tipos
        if ($type === 'danger') {
            $type = 'error';
        }
        
        // Para debugging
        log_message('debug', "Setting toast: {$message} of type {$type}");
        
        $session->setFlashdata('toast_message', $message);
        $session->setFlashdata('toast_type', $type);
        
        // Verificar que los datos se guardaron
        if ($session->getFlashdata('toast_message') !== $message) {
            log_message('error', "Failed to set toast_message flashdata");
        }
    }
}

if (!function_exists('set_toast_success')) {
    function set_toast_success($message)
    {
        log_message('debug', "Setting success toast: {$message}");
        set_toast($message, 'success');
    }
}

if (!function_exists('set_toast_error')) {
    function set_toast_error($message)
    {
        log_message('debug', "Setting error toast: {$message}");
        set_toast($message, 'error');
    }
}

if (!function_exists('set_toast_warning')) {
    function set_toast_warning($message)
    {
        log_message('debug', "Setting warning toast: {$message}");
        set_toast($message, 'warning');
    }
}

if (!function_exists('set_toast_info')) {
    function set_toast_info($message)
    {
        log_message('debug', "Setting info toast: {$message}");
        set_toast($message, 'info');
    }
}

// ========================================
// FUNCIONES DE NOTIFICACIONES DIRECTAS
// ========================================

if (!function_exists('show_message')) {
    /**
     * Show Message (usando el sistema de flashdata estándar de CI)
     *
     * @param string $message The message to display
     * @param string $type The type: success, error, warning, info
     * @return void
     */
    function show_message($message, $type = 'success')
    {
        $session = session();
        
        // Usar el sistema estándar de CI para mensajes
        switch ($type) {
            case 'success':
                $session->setFlashdata('message', $message);
                break;
            case 'error':
            case 'danger':
                $session->setFlashdata('error', $message);
                break;
            case 'warning':
                $session->setFlashdata('warning', $message);
                break;
            case 'info':
                $session->setFlashdata('info', $message);
                break;
            default:
                $session->setFlashdata('message', $message);
                break;
        }
    }
}

if (!function_exists('show_success_message')) {
    function show_success_message($message)
    {
        show_message($message, 'success');
    }
}

if (!function_exists('show_error_message')) {
    function show_error_message($message)
    {
        show_message($message, 'error');
    }
}

if (!function_exists('show_warning_message')) {
    function show_warning_message($message)
    {
        show_message($message, 'warning');
    }
}

if (!function_exists('show_info_message')) {
    function show_info_message($message)
    {
        show_message($message, 'info');
    }
}

// ========================================
// FUNCIONES DE COMPATIBILIDAD
// ========================================

if (!function_exists('flash_message')) {
    /**
     * Función de compatibilidad para otros sistemas
     */
    function flash_message($type, $message)
    {
        set_toast($message, $type);
    }
}

if (!function_exists('notification')) {
    /**
     * Función genérica de notificación
     */
    function notification($message, $type = 'info', $method = 'toast')
    {
        if ($method === 'toast') {
            set_toast($message, $type);
        } else {
            set_alert($message, $type);
        }
    }
}

// ========================================
// FUNCIONES DE UTILIDAD
// ========================================

if (!function_exists('clear_all_notifications')) {
    /**
     * Limpiar todas las notificaciones pendientes
     */
    function clear_all_notifications()
    {
        $session = session();
        
        // Limpiar toast
        $session->remove('toast_message');
        $session->remove('toast_type');
        
        // Limpiar alertas
        $session->remove('alert_message');
        $session->remove('alert_type');
        
        // Limpiar mensajes estándar
        $session->remove('message');
        $session->remove('error');
        $session->remove('warning');
        $session->remove('info');
        
        log_message('debug', 'All notifications cleared');
    }
}

if (!function_exists('has_notifications')) {
    /**
     * Verificar si hay notificaciones pendientes
     */
    function has_notifications()
    {
        $session = session();
        
        return $session->has('toast_message') || 
               $session->has('alert_message') || 
               $session->has('message') || 
               $session->has('error') || 
               $session->has('warning') || 
               $session->has('info');
    }
}

if (!function_exists('get_pending_notifications')) {
    /**
     * Obtener todas las notificaciones pendientes
     */
    function get_pending_notifications()
    {
        $session = session();
        $notifications = [];
        
        // Toast
        if ($session->has('toast_message')) {
            $notifications['toast'] = [
                'message' => $session->getFlashdata('toast_message'),
                'type' => $session->getFlashdata('toast_type') ?: 'info'
            ];
        }
        
        // Mensajes estándar
        if ($session->has('message')) {
            $notifications['success'] = $session->getFlashdata('message');
        }
        
        if ($session->has('error')) {
            $notifications['error'] = $session->getFlashdata('error');
        }
        
        if ($session->has('warning')) {
            $notifications['warning'] = $session->getFlashdata('warning');
        }
        
        if ($session->has('info')) {
            $notifications['info'] = $session->getFlashdata('info');
        }
        
        return $notifications;
    }
} 