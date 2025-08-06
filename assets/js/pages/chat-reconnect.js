/**
 * Funciones para manejar la reconexión y errores en WebSockets
 */

// Variables para reconexión
var reconnectInterval = 5000; // 5 segundos
var reconnectAttempts = 0;
var maxReconnectAttempts = 10;
var wsConnected = false;

// Función para conectar WebSocket con manejo de errores y reconexión
function connectWebSocket() {
    try {
        var wsUrl = 'ws://' + WS_HOST + ':' + WS_PORT;
        window.socket = new WebSocket(wsUrl);
        
        // Event handlers
        window.socket.onopen = function(e) {
            console.log('WebSocket conectado');
            wsConnected = true;
            reconnectAttempts = 0; // Reiniciar contador de intentos
            
            // Autenticar al usuario
            authenticateUser();
            
            // Mostrar mensaje de conexión exitosa
            setTimeout(function() {
                showToast('Conectado al servidor de chat', 'success');
            }, 1000);
        };
        
        window.socket.onmessage = function(e) {
            var data = JSON.parse(e.data);
            handleSocketMessage(data);
        };
        
        window.socket.onclose = function(e) {
            wsConnected = false;
            console.log('WebSocket desconectado');
            
            // Intentar reconectar si la conexión se cerró inesperadamente
            if (reconnectAttempts < maxReconnectAttempts) {
                setTimeout(function() {
                    reconnectAttempts++;
                    console.log('Intento de reconexión #' + reconnectAttempts);
                    connectWebSocket();
                }, reconnectInterval);
                
                // Mostrar mensaje de reconexión
                showToast('Conexión perdida. Intentando reconectar...', 'warning');
            } else {
                // Mostrar mensaje de error después de muchos intentos
                showToast('No se pudo conectar al servidor de chat. Por favor, recarga la página.', 'error');
            }
        };
        
        window.socket.onerror = function(e) {
            console.error('WebSocket error:', e);
            showToast('Error de conexión al servidor de chat', 'error');
        };
    } catch (e) {
        console.error('Error creando WebSocket:', e);
        showToast('Error al crear conexión WebSocket', 'error');
    }
}

// Autenticar al usuario en el servidor WebSocket
function authenticateUser() {
    if (wsConnected && window.socket && window.socket.readyState === WebSocket.OPEN) {
        window.socket.send(JSON.stringify({
            type: 'auth',
            userId: CURRENT_USER_ID,
            token: WS_TOKEN
        }));
    }
}

// Manejo simplificado de mensajes
function handleSocketMessage(data) {
    console.log('Mensaje recibido:', data);
    
    switch (data.type) {
        case 'auth_success':
            console.log('Autenticación exitosa');
            break;
            
        case 'auth_error':
            console.error('Error de autenticación:', data.message);
            showToast('Error de autenticación: ' + data.message, 'error');
            break;
            
        case 'new_message':
            if (typeof receiveNewMessage === 'function') {
                receiveNewMessage(data.message);
            }
            break;
            
        case 'new_channel_message':
            if (typeof receiveNewChannelMessage === 'function') {
                receiveNewChannelMessage(data.message);
            }
            break;
            
        case 'typing_status':
            if (typeof updateTypingStatus === 'function') {
                updateTypingStatus(data.from, data.typing);
            }
            break;
            
        case 'read_status':
            if (typeof updateReadStatus === 'function') {
                updateReadStatus(data.conversation_id, data.by);
            }
            break;
            
        case 'user_status':
            if (typeof updateUserStatus === 'function') {
                updateUserStatus(data.user_id, data.status);
            }
            break;
            
        default:
            console.log('Tipo de mensaje desconocido:', data.type);
    }
}

// Función para mostrar notificaciones toast
function showToast(message, type) {
    var toastClass = 'bg-soft-';
    switch (type) {
        case 'success':
            toastClass += 'success';
            break;
        case 'error':
            toastClass += 'danger';
            break;
        case 'warning':
            toastClass += 'warning';
            break;
        default:
            toastClass += 'info';
    }
    
    var toastHtml = '<div class="toast ' + toastClass + ' fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
                     '<div class="toast-body py-2 px-3">' +
                     '<p class="mb-0 text-' + (type === 'error' ? 'danger' : type) + '">' + message + '</p>' +
                     '</div>' +
                     '</div>';
                     
    var toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.innerHTML = toastHtml;
    
    // Auto-eliminar después de 3 segundos
    setTimeout(function() {
        var toast = toastContainer.querySelector('.toast');
        if (toast) {
            toast.className = toast.className.replace('show', 'hide');
            setTimeout(function() {
                toastContainer.innerHTML = '';
            }, 500);
        }
    }, 3000);
}

// Función para enviar mensajes de forma segura
function sendSocketMessage(message) {
    if (wsConnected && window.socket && window.socket.readyState === WebSocket.OPEN) {
        window.socket.send(JSON.stringify(message));
        return true;
    } else {
        console.error("No hay conexión WebSocket activa");
        showToast("Error al enviar mensaje. Intentando reconectar...", "error");
        
        // Intentar reconectar
        if (reconnectAttempts < maxReconnectAttempts) {
            connectWebSocket();
        }
        
        return false;
    }
}

// Conectar al iniciar
document.addEventListener('DOMContentLoaded', function() {
    // No iniciamos aquí para evitar crear una segunda conexión
    // Esto se hará desde chat.init.js
});
