<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Si el usuario ya está logueado, redirigir al dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('dashboard');
        }
        
        // Si no está logueado, mostrar la página de inicio
        return view('home');
    }
}
