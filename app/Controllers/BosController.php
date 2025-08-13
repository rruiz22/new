<?php

namespace App\Controllers;

class BosController extends BaseController
{
    /**
     * Display the BOS index page without authentication
     * This serves the HTML file from public/bos/index.html
     */
    public function index()
    {
        // Get the path to the HTML file
        $htmlPath = FCPATH . 'bos/index.html';
        
        // Check if the file exists
        if (!file_exists($htmlPath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('BOS index file not found');
        }
        
        // Read and return the HTML content
        $htmlContent = file_get_contents($htmlPath);
        
        // Set the content type to HTML
        $this->response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        
        return $this->response->setBody($htmlContent);
    }
}
