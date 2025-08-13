<?php

namespace App\Controllers;

class BosController extends BaseController
{
    /**
     * Display the BOS index page without authentication
     * This serves the PHP file from public/bos/index.php
     */
    public function index()
    {
        // Get the path to the PHP file
        $phpPath = FCPATH . 'bos/index.php';
        
        // Check if the file exists
        if (!file_exists($phpPath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('BOS index file not found');
        }
        
        // Include and execute the PHP file
        ob_start();
        include $phpPath;
        $phpContent = ob_get_clean();
        
        // Set the content type to HTML
        $this->response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        
        return $this->response->setBody($phpContent);
    }
}
