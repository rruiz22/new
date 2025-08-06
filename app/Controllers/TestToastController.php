<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestToastController extends BaseController
{
    public function index()
    {
        return view('test/toast');
    }

    public function success()
    {
        set_toast_success('¡Operación completada con éxito! Esta es una notificación de éxito.');
        return redirect()->to(base_url('toast-test'));
    }

    public function error()
    {
        set_toast_error('¡Error al procesar la solicitud! Esta es una notificación de error.');
        return redirect()->to(base_url('toast-test'));
    }

    public function warning()
    {
        set_toast_warning('¡Advertencia! Esta es una notificación de advertencia importante.');
        return redirect()->to(base_url('toast-test'));
    }

    public function info()
    {
        set_toast('Información importante. Esta es una notificación informativa.', 'info');
        return redirect()->to(base_url('toast-test'));
    }

    // Métodos AJAX para probar sin recarga
    public function ajaxSuccess()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => '¡Operación AJAX completada con éxito!',
            'type' => 'success'
        ]);
    }

    public function ajaxError()
    {
        return $this->response->setJSON([
            'success' => false,
            'message' => '¡Error en operación AJAX!',
            'type' => 'error'
        ]);
    }

    public function ajaxWarning()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => '¡Advertencia en operación AJAX!',
            'type' => 'warning'
        ]);
    }

    public function ajaxInfo()
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Información de operación AJAX.',
            'type' => 'info'
        ]);
    }

    // Método para probar SweetAlert2
    public function testSweetAlert()
    {
        // Este método puede ser llamado vía AJAX para probar SweetAlert2
        return $this->response->setJSON([
            'success' => true,
            'swal' => [
                'title' => '¡Prueba de SweetAlert2!',
                'text' => 'Esta es una prueba del sistema SweetAlert2 corregido.',
                'icon' => 'success'
            ]
        ]);
    }
} 