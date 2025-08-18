<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\AuthService;
use CodeIgniter\Shield\Models\UserModel;
use Exception;

class AuthController extends BaseController
{
    private AuthService $authService;

    /**
     * Validation rules for login
     */
    private array $loginRules = [
        'username_email' => 'required|min_length[3]|max_length[254]',
        'password' => 'required|min_length[8]|max_length[255]',
    ];

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Verify Turnstile token
     */
    private function verifyTurnstile(): bool
    {
        $turnstileToken = $this->request->getPost('cf-turnstile-response');
        $clientIp = $this->request->getIPAddress();
        
        $verification = verify_turnstile($turnstileToken, $clientIp);
        
        return $verification['success'] ?? false;
    }

    public function login()
    {
        // Display the login form if it's not a POST request
        if (!$this->request->is('post')) {
            return view('auth/login');
        }

        // If user is already logged in, redirect to dashboard
        if (auth()->loggedIn()) {
            return redirect()->to($this->authService->getRedirectUrl())
                ->with('message', 'You are already logged in.');
        }

        // Validate input data
        if (!$this->validate($this->loginRules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Load Turnstile helper and verify if enabled
        helper('turnstile');
        if (is_turnstile_enabled() && !$this->verifyTurnstile()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Security verification failed. Please try again.');
        }

        // Get form data
        $usernameEmail = $this->request->getPost('username_email');
        $password = $this->request->getPost('password');
        $remember = (bool)$this->request->getPost('remember');
        
        // Attempt authentication using AuthService
        if (!$this->authService->authenticate($usernameEmail, $password, $remember)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid credentials. Please check your username/email and password.');
        }

        // Login successful - redirect
        $redirectUrl = $this->authService->getRedirectUrl();
        log_message('info', 'User logged in successfully, redirecting to: ' . $redirectUrl);
        
        return redirect()->to($redirectUrl)
            ->with('message', 'Welcome back! You have been logged in successfully.');
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
        $this->authService->logout();
        
        return redirect()->to('login')
            ->with('message', 'Session cleared successfully. Please try logging in again.');
    }

    public function logout()
    {
        // Log the logout attempt
        $user = auth()->user();
        if ($user) {
            log_message('info', 'Logout for user: ' . ($user->username ?? $user->email ?? 'unknown'));
        }
        
        // Clean logout using AuthService
        $this->authService->logout();

        return redirect()->to('login')
            ->with('message', 'You have been successfully logged out.');
    }
} 