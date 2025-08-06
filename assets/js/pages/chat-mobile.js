/**
 * Funciones para mejorar la experiencia móvil en el chat
 */
document.addEventListener('DOMContentLoaded', function() {
    // Elementos principales
    const chatLeftSidebar = document.querySelector('.chat-leftsidebar');
    const userChat = document.querySelector('.user-chat');
    const userChatShow = document.querySelector('.user-chat-show');
    const backButton = document.querySelector('.user-chat-remove');
    
    // Detectar si es un dispositivo móvil
    const isMobile = window.innerWidth < 768;
    
    // Inicializar estado
    if (isMobile) {
        // En móvil, ocultar el chat de usuario al inicio y mostrar la lista de contactos
        if (userChat) {
            userChat.classList.add('hide');
        }
        if (chatLeftSidebar) {
            chatLeftSidebar.classList.remove('hide');
        }
    }
    
    // Al hacer clic en un contacto, mostrar el chat y ocultar la barra lateral
    document.querySelectorAll('.chat-user-list li, .chat-list').forEach(item => {
        item.addEventListener('click', function() {
            if (isMobile) {
                if (chatLeftSidebar) {
                    chatLeftSidebar.classList.add('hide');
                }
                if (userChat) {
                    userChat.classList.remove('hide');
                }
            }
        });
    });
    
    // Al hacer clic en el botón de volver, ocultar el chat y mostrar la barra lateral
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (isMobile) {
                if (userChat) {
                    userChat.classList.add('hide');
                }
                if (chatLeftSidebar) {
                    chatLeftSidebar.classList.remove('hide');
                }
            }
        });
    }
    
    // Manejar cambios de orientación
    window.addEventListener('resize', function() {
        const newIsMobile = window.innerWidth < 768;
        
        // Si cambia entre móvil y escritorio, ajustar la interfaz
        if (newIsMobile !== isMobile) {
            location.reload();
        }
    });
    
    // Ajustar scroll en dispositivos móviles
    const chatConversation = document.querySelector('.chat-conversation');
    if (chatConversation && isMobile) {
        // Asegurar que el scroll sea suave en iOS
        chatConversation.style.WebkitOverflowScrolling = 'touch';
        
        // Scroll al final al abrir un chat
        document.querySelectorAll('.chat-user-list li, .chat-list').forEach(item => {
            item.addEventListener('click', function() {
                setTimeout(function() {
                    chatConversation.scrollTop = chatConversation.scrollHeight;
                }, 100);
            });
        });
    }

    // Evento para cerrar ventana de chat en móvil
    var userChatRemove = document.getElementsByClassName("user-chat-remove");
    if (userChatRemove) {
        Array.from(userChatRemove).forEach(function (item) {
            item.addEventListener("click", function () {
                // Ocultar conversación actual
                if (document.getElementById("users-chat")) {
                    document.getElementById("users-chat").style.display = "none";
                }
                if (document.getElementById("channel-chat")) {
                    document.getElementById("channel-chat").style.display = "none";
                }
                
                // Ocultar la sección de entrada de chat
                if (document.getElementById("chat-input-section")) {
                    document.getElementById("chat-input-section").style.display = "none";
                }
                
                // Mostrar mensaje de selección de chat
                if (document.getElementById("empty-chat")) {
                    document.getElementById("empty-chat").style.display = "block";
                }
                
                // Quitar selección activa de la lista de chats
                document.querySelectorAll('#userList .contact-item.active, #channelList .channel-item.active').forEach(function(item) {
                    item.classList.remove('active');
                });
            });
        });
    }
}); 