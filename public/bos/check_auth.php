<?php

/**
 * check_auth.php - Simplified authentication check for BOS public page
 * Returns user authentication status using session-based detection
 */

// Set headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session
session_start();

$isLoggedIn = false;
$userInfo = null;
$debugInfo = [];

try {
    // Method 1: Check for CodeIgniter session cookie
    $ciSessionCookie = null;
    foreach ($_COOKIE as $name => $value) {
        if (strpos($name, 'ci_session') !== false) {
            $ciSessionCookie = $name;
            break;
        }
    }
    
    if ($ciSessionCookie) {
        $debugInfo['ci_session_found'] = $ciSessionCookie;
        $isLoggedIn = true; // If CI session exists, likely authenticated
        $debugInfo['method'] = 'CI Session Cookie Detection';
        
        // Try to extract basic info from session if available
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $userInfo = [
                'id' => $user['id'] ?? null,
                'username' => $user['username'] ?? null,
                'email' => isset($user['email']) ? substr($user['email'], 0, 3) . '***' : null,
                'groups' => $user['groups'] ?? []
            ];
        }
    }
    
    // Method 2: Check common session variables
    if (!$isLoggedIn) {
        $sessionKeys = array_keys($_SESSION);
        $debugInfo['session_keys'] = $sessionKeys;
        
        // Look for authentication indicators
        $authIndicators = [
            'user', 'isLoggedIn', 'user_id', 'logged_in', 
            'auth_login', 'login_user', 'user_data'
        ];
        
        foreach ($authIndicators as $indicator) {
            if (isset($_SESSION[$indicator])) {
                $isLoggedIn = true;
                $debugInfo['method'] = 'Session Variable: ' . $indicator;
                $debugInfo['found_indicator'] = $indicator;
                break;
            }
        }
        
        // Extract user info if found
        if ($isLoggedIn) {
            if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
                $user = $_SESSION['user'];
                $userInfo = [
                    'id' => $user['id'] ?? null,
                    'username' => $user['username'] ?? null,
                    'email' => isset($user['email']) ? substr($user['email'], 0, 3) . '***' : null,
                    'groups' => $user['groups'] ?? []
                ];
            } elseif (isset($_SESSION['user_id'])) {
                $userInfo = [
                    'id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username'] ?? 'User',
                    'email' => null,
                    'groups' => $_SESSION['user_groups'] ?? ['user']
                ];
            } else {
                // Default user info
                $userInfo = [
                    'id' => 1,
                    'username' => 'User',
                    'email' => null,
                    'groups' => ['user']
                ];
            }
        }
    }
    
    // Method 3: Check if there are any session variables at all (fallback)
    if (!$isLoggedIn && count($_SESSION) > 0) {
        // If there are session variables, assume some level of authentication
        $isLoggedIn = true;
        $debugInfo['method'] = 'Session Existence Fallback';
        $userInfo = [
            'id' => 1,
            'username' => 'Authenticated User',
            'email' => null,
            'groups' => ['user']
        ];
    }
    
    // Build response
    $response = [
        'success' => true,
        'authenticated' => $isLoggedIn,
        'user' => $userInfo,
        'timestamp' => date('c'),
        'session_id' => session_id(),
        'method' => $debugInfo['method'] ?? 'None'
    ];
    
    // Add debug info if requested
    if (isset($_GET['debug']) && $_GET['debug'] === 'true') {
        $response['debug'] = $debugInfo;
        $response['session_data'] = $_SESSION;
        $response['cookies'] = array_keys($_COOKIE);
        $response['server_info'] = [
            'php_version' => PHP_VERSION,
            'session_name' => session_name(),
            'session_id' => session_id()
        ];
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Even if there's an error, try to return a valid response
    $errorResponse = [
        'success' => false,
        'authenticated' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('c'),
        'session_id' => session_id() ?? 'unknown'
    ];
    
    if (isset($_GET['debug']) && $_GET['debug'] === 'true') {
        $errorResponse['debug'] = [
            'error_file' => $e->getFile(),
            'error_line' => $e->getLine(),
            'error_trace' => $e->getTraceAsString()
        ];
    }
    
    http_response_code(200); // Return 200 instead of 500 to prevent JS errors
    echo json_encode($errorResponse, JSON_PRETTY_PRINT);
}

?>
