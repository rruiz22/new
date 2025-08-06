<?php
/**
 * CodeIgniter 4 - Bootstrap file for development
 * 
 * This file is a bootstrap for development environments.
 * In production, you should configure your web server to point
 * directly to the public/ directory.
 */

// Path to the front controller (public/index.php)
$publicPath = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'index.php';

// Check if the public index file exists
if (file_exists($publicPath)) {
    require $publicPath;
} else {
    // Show error if public/index.php doesn't exist
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'CodeIgniter public/index.php file not found.';
    exit(1);
} 