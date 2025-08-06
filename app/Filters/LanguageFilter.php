<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Services;

class LanguageFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip language filter for language change endpoints
        $uri = $request->getUri()->getPath();
        if (strpos($uri, '/language/') !== false) {
            return;
        }
        
        // Check if a locale is set in the session
        if (session()->has('locale')) {
            // Set the locale for this request
            $locale = session()->get('locale');
            Services::language()->setLocale($locale);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
} 