<?php

/**
 * Security Helper for PublicPages Module
 * 
 * Provides security functions for content sanitization and validation
 */

if (!function_exists('sanitize_page_content')) {
    /**
     * Sanitize page content for safe display
     * 
     * @param string $content Raw HTML content
     * @param bool $allowHtml Whether to allow HTML tags
     * @return string Sanitized content
     */
    function sanitize_page_content(string $content, bool $allowHtml = true): string
    {
        if (empty($content)) {
            return '';
        }

        // Si el contenido ya está sanitizado y es seguro, devolverlo tal como está
        if (!$allowHtml) {
            return strip_tags($content);
        }

        // Para contenido que viene del editor Quill, ya está relativamente limpio
        // Solo necesitamos eliminar elementos realmente peligrosos
        
        // Remove dangerous tags but keep structure
        $dangerousTags = [
            'script', 'iframe', 'object', 'embed', 'applet', 'form', 'input', 
            'textarea', 'select', 'button', 'link', 'meta', 'style'
        ];
        
        foreach ($dangerousTags as $tag) {
            $content = preg_replace("/<\/?{$tag}[^>]*>/i", '', $content);
        }

        // Remove dangerous attributes but keep safe ones
        $dangerousAttrs = [
            'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate',
            'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus',
            'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate',
            'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu',
            'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged',
            'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend',
            'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop',
            'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus',
            'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup',
            'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter',
            'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup',
            'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste',
            'onpropertychange', 'onreadystatechange', 'onreset', 'onresize',
            'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete',
            'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange',
            'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'
        ];
        
        foreach ($dangerousAttrs as $attr) {
            $content = preg_replace("/\s*{$attr}\s*=\s*[\"'][^\"']*[\"']/i", '', $content);
        }

        // Remove dangerous URL schemes
        $content = preg_replace('/javascript:/i', '', $content);
        $content = preg_replace('/vbscript:/i', '', $content);
        $content = preg_replace('/data:(?!image)/i', '', $content); // Allow data: for images only

        return $content;
    }
}

if (!function_exists('validate_css_content')) {
    /**
     * Validate and sanitize CSS content
     * 
     * @param string $css CSS content
     * @param string $userRole User role for permission check
     * @return string Sanitized CSS or empty string if not allowed
     */
    function validate_css_content(string $css, string $userRole = 'user'): string
    {
        $config = config('Modules\PublicPages\Config\Security');
        
        // Check if user has permission to use custom CSS
        if (!($config->rolePermissions[$userRole]['use_custom_css'] ?? false)) {
            return '';
        }

        if (empty($css)) {
            return '';
        }

        // Remove dangerous CSS patterns
        $dangerousPatterns = [
            '/expression\s*\(/i',           // IE expression
            '/javascript:/i',               // javascript URLs
            '/vbscript:/i',                // vbscript URLs
            '/data:/i',                    // data URLs
            '/@import/i',                  // @import statements
            '/url\s*\(\s*["\']?data:/i',   // data URLs in url()
            '/binding/i',                  // IE binding
            '/behavior/i',                 // IE behavior
            '/moz-binding/i',              // Mozilla binding
            '/xbl:/i',                     // XBL namespace
            '/\.htc/i',                    // HTC files
        ];

        foreach ($dangerousPatterns as $pattern) {
            $css = preg_replace($pattern, '/* REMOVED FOR SECURITY */', $css);
        }

        // Limit CSS length
        if (strlen($css) > $config->contentSanitization['maxCssLength']) {
            $css = substr($css, 0, $config->contentSanitization['maxCssLength']);
        }

        return $css;
    }
}

if (!function_exists('validate_js_content')) {
    /**
     * Validate and sanitize JavaScript content
     * 
     * @param string $js JavaScript content
     * @param string $userRole User role for permission check
     * @return string Sanitized JS or empty string if not allowed
     */
    function validate_js_content(string $js, string $userRole = 'user'): string
    {
        $config = config('Modules\PublicPages\Config\Security');
        
        // Check if user has permission to use custom JS
        if (!($config->rolePermissions[$userRole]['use_custom_js'] ?? false)) {
            return '';
        }

        if (empty($js)) {
            return '';
        }

        // Remove extremely dangerous patterns
        $dangerousPatterns = [
            '/eval\s*\(/i',                    // eval function
            '/Function\s*\(/i',                // Function constructor
            '/setTimeout\s*\(\s*["\'][^"\']*["\'],/i', // setTimeout with string
            '/setInterval\s*\(\s*["\'][^"\']*["\'],/i', // setInterval with string
            '/document\.write/i',              // document.write
            '/innerHTML\s*=/i',                // innerHTML assignment
            '/outerHTML\s*=/i',                // outerHTML assignment
            '/document\.cookie/i',             // cookie access
            '/window\.location\s*=/i',         // location manipulation
            '/location\.href\s*=/i',           // href manipulation
            '/XMLHttpRequest/i',               // AJAX requests
            '/ActiveXObject/i',                // ActiveX objects
            '/importScripts/i',                // Web Workers import
            '/postMessage/i',                  // Cross-origin messaging
            '/localStorage/i',                 // Local storage access
            '/sessionStorage/i',               // Session storage access
            '/indexedDB/i',                    // IndexedDB access
            '/WebSocket/i',                    // WebSocket connections
            '/EventSource/i',                  // Server-sent events
            '/fetch\s*\(/i',                  // Fetch API
            '/navigator\./i',                  // Navigator object access
            '/history\./i',                    // History manipulation
        ];

        foreach ($dangerousPatterns as $pattern) {
            $js = preg_replace($pattern, '/* REMOVED FOR SECURITY */', $js);
        }

        // Limit JS length
        if (strlen($js) > $config->contentSanitization['maxJsLength']) {
            $js = substr($js, 0, $config->contentSanitization['maxJsLength']);
        }

        return $js;
    }
}

if (!function_exists('check_rate_limit')) {
    /**
     * Check if action is within rate limits
     * 
     * @param string $action Action type (pageViews, likes, fileUploads)
     * @param string $identifier User ID or IP address
     * @return bool True if within limits, false if exceeded
     */
    function check_rate_limit(string $action, string $identifier): bool
    {
        $config = config('Modules\PublicPages\Config\Security');
        
        if (!isset($config->rateLimiting[$action])) {
            return true; // No limit defined, allow
        }

        $limits = $config->rateLimiting[$action];
        $cacheKey = "rate_limit_{$action}_{$identifier}";
        
        $cache = \Config\Services::cache();
        $attempts = $cache->get($cacheKey, 0);

        if ($attempts >= $limits['maxAttempts']) {
            return false; // Limit exceeded
        }

        // Increment counter
        $cache->save($cacheKey, $attempts + 1, $limits['window']);
        
        return true;
    }
}

if (!function_exists('generate_csp_header')) {
    /**
     * Generate Content Security Policy header
     * 
     * @return string CSP header value
     */
    function generate_csp_header(): string
    {
        $config = config('Modules\PublicPages\Config\Security');
        $cspParts = [];

        foreach ($config->csp as $directive => $sources) {
            $cspParts[] = $directive . ' ' . implode(' ', $sources);
        }

        return implode('; ', $cspParts);
    }
}

if (!function_exists('validate_file_upload')) {
    /**
     * Validate uploaded file for security
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @return array [bool $isValid, string $reason]
     */
    function validate_file_upload($file): array
    {
        $config = config('Modules\PublicPages\Config\Security');
        
        if (!$file->isValid()) {
            return [false, 'File upload failed'];
        }

        // Check file size
        if ($file->getSize() > $config->fileUpload['maxSize']) {
            return [false, 'File size exceeds maximum allowed size'];
        }

        // Check file extension
        $extension = strtolower($file->getClientExtension());
        if (in_array($extension, $config->fileUpload['blockedExtensions'])) {
            return [false, 'File type not allowed'];
        }

        // Check MIME type
        $mimeType = $file->getClientMimeType();
        if (!in_array($mimeType, $config->fileUpload['allowedMimeTypes'])) {
            return [false, 'File type not supported'];
        }

        // Additional content validation
        $tempFile = $file->getTempName();
        if (file_exists($tempFile)) {
            $content = file_get_contents($tempFile, false, null, 0, 1024); // Read first 1KB
            
            // Check for malicious patterns
            $maliciousPatterns = [
                '/<\?php/i', '/<script/i', '/javascript:/i', '/vbscript:/i',
                '/eval\(/i', '/base64_decode/i', '/shell_exec/i'
            ];
            
            foreach ($maliciousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    return [false, 'File contains potentially malicious content'];
                }
            }
        }

        return [true, 'File is valid'];
    }
}

if (!function_exists('log_security_event')) {
    /**
     * Log security-related events
     * 
     * @param string $event Event type
     * @param string $description Event description
     * @param array $context Additional context
     */
    function log_security_event(string $event, string $description, array $context = []): void
    {
        $logData = [
            'event' => $event,
            'description' => $description,
            'ip' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent() ? service('request')->getUserAgent()->__toString() : '',
            'timestamp' => date('Y-m-d H:i:s'),
            'context' => $context
        ];

        log_message('warning', 'PublicPages Security Event: ' . json_encode($logData));
    }
}
