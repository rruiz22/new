<?php

namespace App\Services;

use CodeIgniter\Shield\Models\UserModel;
use Exception;

class AuthService
{
    /**
     * Default redirect URL after login
     */
    private string $defaultRedirect = 'dashboard';

    /**
     * Session timeout in seconds (30 minutes)
     */
    private int $sessionTimeout = 1800;

    /**
     * Authenticate user with username or email
     */
    public function authenticate(string $usernameEmail, string $password, bool $remember = false): bool
    {
        $isEmail = filter_var($usernameEmail, FILTER_VALIDATE_EMAIL);
        
        // Primary attempt based on input type
        $credentials = [
            ($isEmail ? 'email' : 'username') => $usernameEmail,
            'password' => $password,
        ];
        
        if (auth()->attempt($credentials, $remember)) {
            $this->setupUserSession();
            return true;
        }
        
        // Fallback attempt if primary fails and input wasn't clearly an email
        if (!$isEmail) {
            $emailCredentials = [
                'email' => $usernameEmail,
                'password' => $password,
            ];
            
            if (auth()->attempt($emailCredentials, $remember)) {
                $this->setupUserSession();
                return true;
            }
        }
        
        return false;
    }

    /**
     * Set up user session data after successful login
     */
    public function setupUserSession(): void
    {
        $user = auth()->user();
        $userRole = null;
        
        if ($user) {
            $userGroups = $user->getGroups();
            $userRole = $user->role ?? ($userGroups[0] ?? null);
        }
        
        session()->set([
            'isLoggedIn' => true,
            'role' => $userRole,
            'last_activity' => time(),
            'login_time' => time(),
        ]);
    }

    /**
     * Clean logout with session cleanup
     */
    public function logout(): void
    {
        try {
            // Use Shield's logout functionality
            if (auth()->loggedIn()) {
                auth()->logout();
            }
            
            // Clear custom session data
            session()->remove(['isLoggedIn', 'role', 'last_activity', 'login_time', 'intended_url']);
            
            // Regenerate session ID for security
            session()->regenerate();
            
            // Clear any cached authentication data
            if (cache()) {
                cache()->deleteMatching('auth_*');
            }
            
        } catch (Exception $e) {
            log_message('error', 'Error during logout: ' . $e->getMessage());
        }
    }

    /**
     * Get redirect URL after login
     */
    public function getRedirectUrl(): string
    {
        $intendedUrl = session()->get('intended_url');
        
        // Clear intended URL from session
        if ($intendedUrl) {
            session()->remove('intended_url');
        }
        
        // Validate intended URL for security
        if ($intendedUrl && $this->isValidRedirectUrl($intendedUrl)) {
            return $intendedUrl;
        }
        
        return $this->defaultRedirect;
    }

    /**
     * Check if user session has expired
     */
    public function isSessionExpired(): bool
    {
        $lastActivity = session()->get('last_activity');
        
        if (!$lastActivity) {
            return true;
        }
        
        return (time() - $lastActivity) > $this->sessionTimeout;
    }

    /**
     * Refresh user session activity
     */
    public function refreshSession(): void
    {
        if (auth()->loggedIn()) {
            session()->set('last_activity', time());
            
            // Regenerate session ID periodically for security
            $lastActivity = session()->get('last_activity');
            if (!$lastActivity || (time() - $lastActivity) > $this->sessionTimeout) {
                session()->regenerate();
            }
        }
    }

    /**
     * Check if redirect URL is valid and safe
     */
    private function isValidRedirectUrl(string $url): bool
    {
        // Parse URL to check components
        $parsedUrl = parse_url($url);
        
        // Reject URLs with external hosts
        if (isset($parsedUrl['host']) && $parsedUrl['host'] !== $_SERVER['HTTP_HOST']) {
            return false;
        }
        
        // Reject URLs with dangerous schemes
        if (isset($parsedUrl['scheme']) && !in_array($parsedUrl['scheme'], ['http', 'https'])) {
            return false;
        }
        
        // Reject URLs pointing to auth pages
        $path = $parsedUrl['path'] ?? '';
        $dangerousPaths = ['/login', '/register', '/logout', '/auth/'];
        
        foreach ($dangerousPaths as $dangerousPath) {
            if (str_contains($path, $dangerousPath)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get user role safely
     */
    public function getUserRole(): ?string
    {
        if (!auth()->loggedIn()) {
            return null;
        }
        
        return session()->get('role');
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        $userRole = $this->getUserRole();
        return $userRole === $role;
    }

    /**
     * Get session statistics
     */
    public function getSessionInfo(): array
    {
        if (!auth()->loggedIn()) {
            return [];
        }
        
        $loginTime = session()->get('login_time');
        $lastActivity = session()->get('last_activity');
        $currentTime = time();
        
        return [
            'login_time' => $loginTime,
            'last_activity' => $lastActivity,
            'session_duration' => $loginTime ? $currentTime - $loginTime : 0,
            'idle_time' => $lastActivity ? $currentTime - $lastActivity : 0,
            'expires_in' => $lastActivity ? $this->sessionTimeout - ($currentTime - $lastActivity) : 0,
        ];
    }
}
