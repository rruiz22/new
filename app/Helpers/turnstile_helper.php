<?php

if (!function_exists('verify_turnstile')) {
    /**
     * Verify Cloudflare Turnstile token
     *
     * @param string $token The turnstile token from the frontend
     * @param string|null $remoteIp The client's IP address (optional)
     * @return array Result array with success status and error message if any
     */
    function verify_turnstile(string $token, ?string $remoteIp = null): array
    {
        $config = config('Turnstile');
        
        // If Turnstile is disabled, always return success
        if (!$config->enabled) {
            return ['success' => true, 'message' => 'Turnstile verification disabled'];
        }

        // Validate token format
        if (empty($token)) {
            return ['success' => false, 'message' => 'Missing turnstile token'];
        }

        // Prepare the POST data
        $postData = [
            'secret' => $config->secretKey,
            'response' => $token,
        ];

        // Add remote IP if verification is enabled and IP is provided
        if ($config->verifyRemoteIp && $remoteIp) {
            $postData['remoteip'] = $remoteIp;
        }

        // Initialize cURL
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $config->verifyUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $config->timeout,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'User-Agent: CodeIgniter-Turnstile/1.0'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Check for cURL errors
        if ($curlError) {
            log_message('error', 'Turnstile cURL Error: ' . $curlError);
            return ['success' => false, 'message' => 'Network error during verification'];
        }

        // Check HTTP status
        if ($httpCode !== 200) {
            log_message('error', 'Turnstile HTTP Error: ' . $httpCode);
            return ['success' => false, 'message' => 'Server error during verification'];
        }

        // Parse JSON response
        $result = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Turnstile JSON Error: ' . json_last_error_msg());
            return ['success' => false, 'message' => 'Invalid response format'];
        }

        // Check verification result
        if (!isset($result['success'])) {
            return ['success' => false, 'message' => 'Invalid verification response'];
        }

        if ($result['success'] === true) {
            return ['success' => true, 'message' => 'Verification successful'];
        }

        // Handle errors
        $errorMessage = 'Verification failed';
        if (isset($result['error-codes']) && is_array($result['error-codes'])) {
            $errorMap = [
                'missing-input-secret' => 'Missing secret key',
                'invalid-input-secret' => 'Invalid secret key',
                'missing-input-response' => 'Missing response token',
                'invalid-input-response' => 'Invalid response token',
                'bad-request' => 'Bad request',
                'timeout-or-duplicate' => 'Token has expired or been used',
                'internal-error' => 'Internal server error',
            ];

            $errors = [];
            foreach ($result['error-codes'] as $errorCode) {
                $errors[] = $errorMap[$errorCode] ?? $errorCode;
            }
            $errorMessage = implode(', ', $errors);
        }

        log_message('info', 'Turnstile verification failed: ' . $errorMessage);
        return ['success' => false, 'message' => $errorMessage];
    }
}

if (!function_exists('get_turnstile_site_key')) {
    /**
     * Get the Turnstile site key for frontend usage
     *
     * @return string The site key
     */
    function get_turnstile_site_key(): string
    {
        $config = config('Turnstile');
        return $config->siteKey;
    }
}

if (!function_exists('is_turnstile_enabled')) {
    /**
     * Check if Turnstile verification is enabled
     *
     * @return bool True if enabled, false otherwise
     */
    function is_turnstile_enabled(): bool
    {
        $config = config('Turnstile');
        return $config->enabled;
    }
} 