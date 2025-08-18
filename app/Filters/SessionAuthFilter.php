<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionAuthFilter implements FilterInterface
{
    /**
     * URLs that should not be saved as intended URLs
     */
    private array $excludedPaths = [
        '/login', '/register', '/logout', '/auth/'
    ];

    /**
     * Session regeneration interval (in seconds)
     */
    private int $regenerationInterval = 1800; // 30 minutes

    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is authenticated using Shield
        if (!auth()->loggedIn()) {
            return $this->handleUnauthenticated($request);
        }
        
        // User is authenticated - handle session security
        $this->handleSessionSecurity();
        
        return null; // Continue with request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Update last activity timestamp
        if (auth()->loggedIn()) {
            session()->set('last_activity', time());
        }
        
        return null;
    }

    /**
     * Handle unauthenticated user requests
     */
    private function handleUnauthenticated(RequestInterface $request)
    {
        // Handle AJAX requests
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'success' => false,
                    'message' => 'Session expired. Please login again.',
                    'redirect' => base_url('login')
                ]);
        }
        
        // Save intended URL for redirect after login
        $this->saveIntendedUrl();
        
        // Redirect to login
        return redirect()->to('login');
    }

    /**
     * Save the current URL as intended URL if appropriate
     */
    private function saveIntendedUrl(): void
    {
        $currentUrl = current_url();
        
        // Check if current URL should be saved
        if ($this->shouldSaveUrl($currentUrl)) {
            session()->set('intended_url', $currentUrl);
            session()->setFlashdata('message', 'Please log in to access that page. You will be redirected after login.');
        } else {
            session()->setFlashdata('info', 'Please log in to continue.');
        }
    }

    /**
     * Check if URL should be saved as intended URL
     */
    private function shouldSaveUrl(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        
        foreach ($this->excludedPaths as $excludedPath) {
            if (str_contains($path, $excludedPath)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Handle session security measures
     */
    private function handleSessionSecurity(): void
    {
        $lastActivity = session()->get('last_activity');
        $currentTime = time();
        
        // Regenerate session periodically for security
        if (!$lastActivity || ($currentTime - $lastActivity) > $this->regenerationInterval) {
            session()->regenerate();
            session()->set('last_activity', $currentTime);
        }
    }
}
