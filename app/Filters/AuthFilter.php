<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');
        // Permitir solo si está autenticado y es admin o superadmin
        if (!$session->get('isLoggedIn') || !in_array($role, ['admin', 'superadmin'])) {
            return service('response')
                ->setStatusCode(403)
                ->setBody(view('errors/custom_403', [
                    'title' => lang('App.forbidden_title'),
                    'message' => lang('App.forbidden_message'),
                    'return_home' => lang('App.return_home'),
                ]));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita lógica post-request
    }
} 