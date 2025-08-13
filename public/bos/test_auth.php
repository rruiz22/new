<?php
/**
 * test_auth.php - Simple test to verify authentication endpoint
 */

echo "<h2>🔍 Authentication Test</h2>";

// Test the check_auth.php endpoint
echo "<h3>Testing check_auth.php endpoint:</h3>";

$url = 'http://localhost' . dirname($_SERVER['REQUEST_URI']) . '/check_auth.php?debug=true';
echo "<p><strong>URL:</strong> <a href='$url' target='_blank'>$url</a></p>";

// Use cURL to test the endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "<p><strong>HTTP Code:</strong> $httpCode</p>";

if ($error) {
    echo "<p><strong>cURL Error:</strong> $error</p>";
}

if ($response) {
    // Split headers and body
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    echo "<h4>Response Headers:</h4>";
    echo "<pre>" . htmlspecialchars($headers) . "</pre>";
    
    echo "<h4>Response Body:</h4>";
    echo "<pre>" . htmlspecialchars($body) . "</pre>";
    
    // Try to decode JSON
    $json = json_decode($body, true);
    if ($json) {
        echo "<h4>Parsed JSON:</h4>";
        echo "<pre>" . print_r($json, true) . "</pre>";
    }
} else {
    echo "<p><strong>No response received</strong></p>";
}

echo "<hr>";

// Test direct file access
echo "<h3>Direct file test:</h3>";
echo "<p><strong>File exists:</strong> " . (file_exists('check_auth.php') ? 'Yes' : 'No') . "</p>";
echo "<p><strong>File readable:</strong> " . (is_readable('check_auth.php') ? 'Yes' : 'No') . "</p>";

// Test session info
session_start();
echo "<h3>Session Information:</h3>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Name:</strong> " . session_name() . "</p>";
echo "<p><strong>Session Variables:</strong></p>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Test cookies
echo "<h3>Cookie Information:</h3>";
echo "<pre>" . print_r($_COOKIE, true) . "</pre>";

// PHP Info
echo "<h3>PHP Environment:</h3>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
h2, h3, h4 { color: #333; }
a { color: #007bff; }
</style>
