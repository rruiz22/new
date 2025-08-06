<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ModuleAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Only check if user is logged in, not specific roles
        if (!$session->get('isLoggedIn')) {
            // For AJAX requests, return JSON error
            if ($request->hasHeader('X-Requested-With') && $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Authentication required'
                    ]);
            }
            
            // For regular requests, redirect to login
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-request logic needed
    }
} 