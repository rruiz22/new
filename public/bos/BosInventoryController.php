<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BosInventoryController extends Controller
{
    public function index()
    {
        // Set up data for the view
        $data = [
            'title' => 'BOS Inventory Management',
            'page_title' => 'Vehicle Inventory Detail Report'
        ];
        
        // Load the view using CodeIgniter's view system
        return view('bos/index', $data);
    }
    
    public function checkAuth()
    {
        // Set headers
        $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-API-Key');

        // Handle preflight
        if ($this->request->getMethod() === 'OPTIONS') {
            return $this->response->setStatusCode(200);
        }

        try {
            // Check authentication using CodeIgniter Shield
            $isLoggedIn = false;
            $userInfo = null;
            
            if (auth()->loggedIn()) {
                $isLoggedIn = true;
                $user = auth()->user();
                
                // Get basic user info without sensitive data
                $userInfo = [
                    'id' => $user->id ?? null,
                    'username' => $user->username ?? null,
                    'email' => isset($user->email) ? substr($user->email, 0, 3) . '***' : null,
                    'groups' => $user->getGroups() ?? []
                ];
            }
            
            // Log the check
            log_message('debug', 'BOS Auth check: ' . ($isLoggedIn ? 'authenticated' : 'not authenticated'));
            
            // Return response
            return $this->response->setJSON([
                'success' => true,
                'authenticated' => $isLoggedIn,
                'user' => $userInfo,
                'timestamp' => date('c'),
                'session_id' => session_id() // For debugging if needed
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'authenticated' => false,
                'error' => 'Authentication check failed',
                'timestamp' => date('c')
            ]);
        }
    }
}
