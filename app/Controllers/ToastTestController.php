<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ToastTestController extends BaseController
{
    public function index()
    {
        return view('test/toast_test');
    }

    public function success()
    {
        set_toast_success('Este es un mensaje de éxito de prueba');
        return redirect()->to(base_url('toast-test'));
    }

    public function error()
    {
        set_toast_error('Este es un mensaje de error de prueba');
        return redirect()->to(base_url('toast-test'));
    }

    public function warning()
    {
        set_toast('Este es un mensaje de advertencia de prueba', 'warning');
        return redirect()->to(base_url('toast-test'));
    }

    public function info()
    {
        set_toast('Este es un mensaje informativo de prueba', 'info');
        return redirect()->to(base_url('toast-test'));
    }
    
    // Métodos para AJAX
    public function ajaxSuccess()
    {
        // No necesitamos guardar en flash, simplemente devolvemos el mensaje
        return $this->response->setJSON([
            'status' => 'success',
            'type' => 'success',
            'message' => 'Este es un mensaje de éxito AJAX (sin recargar)'
        ]);
    }
    
    public function ajaxError()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'type' => 'danger',
            'message' => 'Este es un mensaje de error AJAX (sin recargar)'
        ]);
    }
    
    public function ajaxWarning()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'type' => 'warning',
            'message' => 'Este es un mensaje de advertencia AJAX (sin recargar)'
        ]);
    }
    
    public function ajaxInfo()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'type' => 'info',
            'message' => 'Este es un mensaje informativo AJAX (sin recargar)'
        ]);
    }
} 