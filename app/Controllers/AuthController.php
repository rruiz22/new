<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use Exception;

class AuthController extends BaseController
{
    /**
     * Force clear all session data and authentication state
     */
    private function forceClearSession(): void
    {
        try {
            // Get session instance
            $session = session();
            
            // Check if there's an active session
            $hasActiveSession = session_status() === PHP_SESSION_ACTIVE;
            
            // Try to logout if logged in
            if ($hasActiveSession && auth()->loggedIn()) {
                auth()->logout();
            }
            
            // Only destroy and regenerate if we have an active session
            if ($hasActiveSession) {
                // Destroy session
                $session->destroy();
                
                // Start a new session
                $session->start();
                
                // Remove all session data manually (extra safety)
                $session->remove('user');
                $session->remove('isLoggedIn');
                $session->remove('role');
                
                // Regenerate session ID with the new session
                $session->regenerate(true);
            } else {
                // If no active session, start one and clear any data
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    $session->start();
                }
                
                // Clear session data
                $session->remove('user');
                $session->remove('isLoggedIn');
                $session->remove('role');
            }
            
            log_message('info', 'Session forcefully cleared');
            
        } catch (Exception $e) {
            log_message('error', 'Error clearing session: ' . $e->getMessage());
            // If all else fails, try to start a fresh session
            try {
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session()->start();
                }
            } catch (Exception $fallbackError) {
                log_message('error', 'Fallback session start failed: ' . $fallbackError->getMessage());
            }
        }
    }

    public function login()
    {
        // Display the login form if it's not a POST request
        if (! $this->request->is('post')) {
            return view('auth/login');
        }

        // Load Turnstile helper
        helper('turnstile');

        // Check if user is already logged in and clear session completely
        if (auth()->loggedIn()) {
            log_message('info', 'User already logged in, forcing session clear');
            $this->forceClearSession();
            
            // Redirect back to login form after clearing session
            return redirect()->to('login')
                ->with('message', 'Previous session cleared. Please try logging in again.');
        }

        // Verify Turnstile token if enabled
        if (is_turnstile_enabled()) {
            $turnstileToken = $this->request->getPost('cf-turnstile-response');
            $clientIp = $this->request->getIPAddress();
            
            $verification = verify_turnstile($turnstileToken, $clientIp);
            
            if (!$verification['success']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Security verification failed. Please try again.');
            }
        }

        // Attempt to authenticate the user
        $usernameEmail = $this->request->getPost('username_email');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');
        
        // Log the login attempt for debugging
        log_message('info', 'Login attempt for: ' . $usernameEmail);
        
        // Determine if the input is an email or username
        $isEmail = filter_var($usernameEmail, FILTER_VALIDATE_EMAIL);
        log_message('info', 'Detected as ' . ($isEmail ? 'email' : 'username'));
        
        $validCreds = false;
        
        if ($isEmail) {
            // Try email authentication first
            $credentials = [
                'email' => $usernameEmail,
                'password' => $password,
            ];
            log_message('info', 'Attempting email authentication');
            $validCreds = auth()->attempt($credentials, $remember);
        } else {
            // Try username authentication first
            $credentials = [
                'username' => $usernameEmail,
                'password' => $password,
            ];
            log_message('info', 'Attempting username authentication');
            $validCreds = auth()->attempt($credentials, $remember);
            
            // If username fails, try as email (fallback)
            if (!$validCreds) {
                log_message('info', 'Username auth failed, trying as email fallback');
                $credentials = [
                    'email' => $usernameEmail,
                    'password' => $password,
                ];
                $validCreds = auth()->attempt($credentials, $remember);
            }
        }

        log_message('info', 'Authentication result: ' . ($validCreds ? 'SUCCESS' : 'FAILED'));

        if (! $validCreds) {
            return redirect()->back()->withInput()->with('error', 'Invalid username/email or password. Please try again.');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Set custom session values for sidebar and filters
        $userRole = null;
        if ($user) {
            $userGroups = $user->getGroups();
            $userRole = $user->role ?? ($userGroups ? $userGroups[0] : null);
        }
        
        session()->set([
            'isLoggedIn' => true,
            'role' => $userRole,
        ]);

        // Get the intended URL (where user was trying to go before login)
        $intendedUrl = session()->get('intended_url');
        log_message('info', 'AuthController: Login successful. Intended URL: ' . ($intendedUrl ?: 'none'));
        
        // Clear the intended URL from session
        session()->remove('intended_url');
        
        // Redirect to intended URL or dashboard as fallback
        $redirectTo = $intendedUrl ?: 'dashboard';
        log_message('info', 'AuthController: Redirecting to: ' . $redirectTo);
        
        return redirect()->to($redirectTo)
            ->with('message', lang('Auth.successLogin'));
    }

    public function register()
    {
        // Display the registration form if it's not a POST request
        if (! $this->request->is('post')) {
            return view('auth/register');
        }

        // Load Turnstile helper
        helper('turnstile');

        // Verify Turnstile token if enabled
        if (is_turnstile_enabled()) {
            $turnstileToken = $this->request->getPost('cf-turnstile-response');
            $clientIp = $this->request->getIPAddress();
            
            $verification = verify_turnstile($turnstileToken, $clientIp);
            
            if (!$verification['success']) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['Security verification failed. Please try again.']);
            }
        }

        // Validate user input
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|strong_password',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Create the user
        $users = model(UserModel::class);

        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        // Save user first to get an ID
        if (!$users->save($user)) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }
        
        // Get the user with its ID from the database 
        $userId = $users->getInsertID();
        $user = $users->find($userId);
        
        // Now assign user role and activate
        $user->addGroup('user');
        
        // Activate the user
        $user->activate();

        // Redirect to login
        return redirect()->to('login')
            ->with('message', lang('Auth.registerSuccess'));
    }

    /**
     * Clear stuck sessions - can be called via URL if needed
     * GET /auth/clear-session
     */
    public function clearSession()
    {
        $this->forceClearSession();
        
        return redirect()->to('login')
            ->with('message', 'Session cleared successfully. Please try logging in again.');
    }

    public function logout()
    {
        // Log the logout attempt
        $user = auth()->user();
        if ($user) {
            log_message('info', 'Logout attempt for user: ' . ($user->username ?? $user->email));
        }
        
        // Check if there's an active session before attempting operations
        $hasActiveSession = session_status() === PHP_SESSION_ACTIVE && session()->has('isLoggedIn');
        
        // Logout the user if there's an active session
        if ($hasActiveSession && auth()->loggedIn()) {
            auth()->logout();
        }
        
        // Only destroy and regenerate if we have an active session
        if ($hasActiveSession) {
            // Completely destroy the session to ensure all user data is removed
            session()->destroy();
            
            // Start a new session after destroying the old one
            session()->start();
            
            // Now regenerate the session ID with the new session
            session()->regenerate();
        } else {
            // If no active session, just ensure we start fresh
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session()->start();
            }
            // Clear any existing session data
            session()->setFlashdata('message', 'You have been successfully logged out.');
        }
        
        // Clear any potential cached authentication data
        if (cache()) {
            cache()->deleteMatching('auth_*');
        }

        return redirect()->to('login')
            ->with('message', 'You have been successfully logged out.');
    }
} 