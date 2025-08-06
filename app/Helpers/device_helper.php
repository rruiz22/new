<?php

if (!function_exists('getDeviceInfo')) {
    /**
     * Detect device type and return icon and label based on user agent
     *
     * @param string $userAgent
     * @return array
     */
    function getDeviceInfo($userAgent = '')
    {
        if (empty($userAgent)) {
            return [
                'icon' => 'help-circle',
                'label' => 'Unknown',
                'class' => 'text-muted'
            ];
        }

        $userAgent = strtolower($userAgent);

        // Check for mobile devices
        $mobileKeywords = [
            'mobile', 'android', 'iphone', 'ipod', 'blackberry', 
            'windows phone', 'symbian', 'palm', 'opera mini'
        ];
        
        foreach ($mobileKeywords as $keyword) {
            if (strpos($userAgent, $keyword) !== false) {
                return [
                    'icon' => 'smartphone',
                    'label' => 'Mobile',
                    'class' => 'text-success'
                ];
            }
        }

        // Check for tablets
        $tabletKeywords = ['ipad', 'tablet', 'kindle', 'nexus 7', 'nexus 10'];
        
        foreach ($tabletKeywords as $keyword) {
            if (strpos($userAgent, $keyword) !== false) {
                return [
                    'icon' => 'tablet',
                    'label' => 'Tablet',
                    'class' => 'text-info'
                ];
            }
        }

        // Check for bots/crawlers
        $botKeywords = [
            'bot', 'crawler', 'spider', 'scraper', 'facebook', 
            'twitter', 'google', 'bing', 'yahoo', 'baidu'
        ];
        
        foreach ($botKeywords as $keyword) {
            if (strpos($userAgent, $keyword) !== false) {
                return [
                    'icon' => 'cpu',
                    'label' => 'Bot',
                    'class' => 'text-warning'
                ];
            }
        }

        // Default to desktop
        return [
            'icon' => 'monitor',
            'label' => 'Desktop',
            'class' => 'text-primary'
        ];
    }
}

if (!function_exists('getBrowserInfo')) {
    /**
     * Detect browser type based on user agent
     *
     * @param string $userAgent
     * @return array
     */
    function getBrowserInfo($userAgent = '')
    {
        if (empty($userAgent)) {
            return [
                'name' => 'Unknown',
                'icon' => 'globe'
            ];
        }

        $userAgent = strtolower($userAgent);

        // Browser detection
        if (strpos($userAgent, 'chrome') !== false && strpos($userAgent, 'edge') === false) {
            return ['name' => 'Chrome', 'icon' => 'chrome'];
        } elseif (strpos($userAgent, 'firefox') !== false) {
            return ['name' => 'Firefox', 'icon' => 'firefox'];
        } elseif (strpos($userAgent, 'safari') !== false && strpos($userAgent, 'chrome') === false) {
            return ['name' => 'Safari', 'icon' => 'globe'];
        } elseif (strpos($userAgent, 'edge') !== false) {
            return ['name' => 'Edge', 'icon' => 'globe'];
        } elseif (strpos($userAgent, 'opera') !== false) {
            return ['name' => 'Opera', 'icon' => 'globe'];
        } elseif (strpos($userAgent, 'msie') !== false || strpos($userAgent, 'trident') !== false) {
            return ['name' => 'Internet Explorer', 'icon' => 'globe'];
        }

        return ['name' => 'Unknown', 'icon' => 'globe'];
    }
}

if (!function_exists('getOSInfo')) {
    /**
     * Detect operating system based on user agent
     *
     * @param string $userAgent
     * @return string
     */
    function getOSInfo($userAgent = '')
    {
        if (empty($userAgent)) {
            return 'Unknown OS';
        }

        $userAgent = strtolower($userAgent);

        // OS detection
        if (strpos($userAgent, 'windows nt 10') !== false) {
            return 'Windows 10';
        } elseif (strpos($userAgent, 'windows nt 6.3') !== false) {
            return 'Windows 8.1';
        } elseif (strpos($userAgent, 'windows nt 6.2') !== false) {
            return 'Windows 8';
        } elseif (strpos($userAgent, 'windows nt 6.1') !== false) {
            return 'Windows 7';
        } elseif (strpos($userAgent, 'windows') !== false) {
            return 'Windows';
        } elseif (strpos($userAgent, 'mac os x') !== false) {
            return 'macOS';
        } elseif (strpos($userAgent, 'android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iphone') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'iOS';
        } elseif (strpos($userAgent, 'linux') !== false) {
            return 'Linux';
        }

        return 'Unknown OS';
    }
} 