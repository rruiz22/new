<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        log_message('info', 'SessionAuthFilter: BEFORE method called for URL: ' . current_url());
        
        // Verificar si el usuario está autenticado usando Shield
        $isLoggedIn = auth()->loggedIn();
        log_message('info', 'SessionAuthFilter: User logged in status: ' . ($isLoggedIn ? 'YES' : 'NO'));
        
        if (!$isLoggedIn) {
            // Si es una petición AJAX, devolver JSON
            if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                log_message('info', 'SessionAuthFilter: Returning JSON response for AJAX request');
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Session expired. Please login again.',
                        'redirect' => base_url('login')
                    ]);
            }
            
            // Guardar la URL original que se intentó acceder (excepto rutas de auth)
            $currentUrl = current_url();
            $loginUrl = base_url('login');
            $registerUrl = base_url('register');
            
            log_message('info', 'SessionAuthFilter: User not logged in, current URL: ' . $currentUrl);
            
            // No guardar URLs de autenticación como intended URL
            if ($currentUrl !== $loginUrl && $currentUrl !== $registerUrl && 
                !str_contains($currentUrl, '/auth/') && !str_contains($currentUrl, '/logout')) {
                session()->set('intended_url', $currentUrl);
                log_message('info', 'SessionAuthFilter: Saved intended URL: ' . $currentUrl);
                
                // Mensaje más amigable cuando se redirige por intentar acceder a una página protegida
                session()->setFlashdata('message', 'Please log in to access that page. You will be redirected after login.');
            } else {
                log_message('info', 'SessionAuthFilter: Not saving intended URL (auth page): ' . $currentUrl);
                // Mensaje estándar para expiración de sesión
                session()->setFlashdata('error', 'Your session has expired. Please login again.');
            }
            
            // Para peticiones normales, redirigir al login
            log_message('info', 'SessionAuthFilter: Redirecting to login');
            return redirect()->to('login');
        }
        
        log_message('info', 'SessionAuthFilter: User is logged in, allowing access');
        
        // Regenerar la sesión periódicamente para mayor seguridad
        $lastActivity = session()->get('last_activity');
        $currentTime = time();
        
        if (!$lastActivity || ($currentTime - $lastActivity) > 1800) { // 30 minutos
            session()->regenerate();
            session()->set('last_activity', $currentTime);
            log_message('info', 'SessionAuthFilter: Session regenerated');
        }
        
        return null; // Continuar con la petición
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Actualizar la última actividad
        session()->set('last_activity', time());
        log_message('info', 'SessionAuthFilter: AFTER method called, updated last_activity');
        
        return null;
    }
}
