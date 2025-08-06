<?php
/**
 * Test Debug Page for Redirect Functionality
 * URL: http://localhost/mda/test-redirect
 */

namespace App\Controllers;

class TestController extends BaseController
{
    public function testRedirect()
    {
        // This page should be protected and trigger the redirect
        $data = [
            'current_url' => current_url(),
            'base_url' => base_url(),
            'intended_url' => session()->get('intended_url'),
            'user_logged_in' => auth()->loggedIn(),
            'session_data' => session()->get(),
            'filter_working' => 'YES - If you see this page, the filter is NOT working correctly!',
        ];
        
        log_message('info', 'TestController: testRedirect accessed - Filter did NOT work!');
        
        return view('test_redirect', $data);
    }
    
    public function showSession()
    {
        // Show current session data for debugging
        $sessionData = [
            'intended_url' => session()->get('intended_url'),
            'isLoggedIn' => session()->get('isLoggedIn'),
            'role' => session()->get('role'),
            'user_logged_in_shield' => auth()->loggedIn(),
            'all_session' => session()->get(),
        ];
        
        header('Content-Type: application/json');
        return json_encode($sessionData, JSON_PRETTY_PRINT);
    }
    
    public function testFilter()
    {
        // Simple test to verify filter behavior
        return 'FILTER NOT WORKING - You should not see this message if the filter is applied correctly';
    }
}
