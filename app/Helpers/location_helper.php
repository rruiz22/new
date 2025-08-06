<?php

if (!function_exists('getLocationFromIP')) {
    /**
     * Get location information from IP address
     * Uses a free IP geolocation service
     *
     * @param string $ipAddress
     * @return array
     */
    function getLocationFromIP($ipAddress = '')
    {
        // Default/fallback values
        $defaultLocation = [
            'country' => null,
            'country_code' => null,
            'region' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
            'timezone' => null,
            'flag_emoji' => '🌍',
            'display_name' => 'Unknown Location'
        ];

        if (empty($ipAddress) || $ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return array_merge($defaultLocation, [
                'country' => 'Local',
                'city' => 'Localhost',
                'flag_emoji' => '🏠',
                'display_name' => 'Local Server'
            ]);
        }

        // Check if it's a private IP
        if (isPrivateIP($ipAddress)) {
            return array_merge($defaultLocation, [
                'country' => 'Local Network',
                'city' => 'Private Network',
                'flag_emoji' => '🏢',
                'display_name' => 'Local Network'
            ]);
        }

        // Try to get location from free IP geolocation service
        try {
            // Using ip-api.com (free service with limitations)
            $url = "http://ip-api.com/json/{$ipAddress}?fields=status,message,country,countryCode,region,regionName,city,lat,lon,timezone";
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // 5 seconds timeout
                    'user_agent' => 'MDA Audit System 1.0'
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if ($response !== false) {
                $data = json_decode($response, true);
                
                if ($data && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'country_code' => strtoupper($data['countryCode'] ?? ''),
                        'region' => $data['regionName'] ?? null,
                        'city' => $data['city'] ?? null,
                        'latitude' => $data['lat'] ?? null,
                        'longitude' => $data['lon'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                        'flag_emoji' => getCountryFlag($data['countryCode'] ?? ''),
                        'display_name' => formatLocationDisplay($data)
                    ];
                }
            }
        } catch (Exception $e) {
            // Silently fail and return default
        }

        return $defaultLocation;
    }
}

if (!function_exists('isPrivateIP')) {
    /**
     * Check if IP address is private/local
     *
     * @param string $ip
     * @return bool
     */
    function isPrivateIP($ip)
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }
}

if (!function_exists('getCountryFlag')) {
    /**
     * Get flag emoji for country code
     *
     * @param string $countryCode
     * @return string
     */
    function getCountryFlag($countryCode)
    {
        if (empty($countryCode) || strlen($countryCode) !== 2) {
            return '🌍';
        }

        $countryCode = strtoupper($countryCode);
        
        // Convert country code to flag emoji
        $firstLetter = ord($countryCode[0]) - ord('A') + 127462;
        $secondLetter = ord($countryCode[1]) - ord('A') + 127462;
        
        return mb_chr($firstLetter) . mb_chr($secondLetter);
    }
}

if (!function_exists('formatLocationDisplay')) {
    /**
     * Format location for display
     *
     * @param array $locationData
     * @return string
     */
    function formatLocationDisplay($locationData)
    {
        $parts = [];
        
        if (!empty($locationData['city'])) {
            $parts[] = $locationData['city'];
        }
        
        if (!empty($locationData['regionName']) && $locationData['regionName'] !== $locationData['city']) {
            $parts[] = $locationData['regionName'];
        }
        
        if (!empty($locationData['country'])) {
            $parts[] = $locationData['country'];
        }
        
        return !empty($parts) ? implode(', ', $parts) : 'Unknown Location';
    }
}

if (!function_exists('getLocationIcon')) {
    /**
     * Get appropriate icon for location
     *
     * @param array $locationInfo
     * @return string
     */
    function getLocationIcon($locationInfo)
    {
        if (empty($locationInfo['country'])) {
            return 'help-circle';
        }
        
        if ($locationInfo['country'] === 'Local' || $locationInfo['country'] === 'Local Network') {
            return 'home';
        }
        
        // You can add more specific icons based on country or region
        return 'map-pin';
    }
}

if (!function_exists('formatCoordinates')) {
    /**
     * Format latitude/longitude for display
     *
     * @param float $lat
     * @param float $lng
     * @return string
     */
    function formatCoordinates($lat, $lng)
    {
        if (empty($lat) || empty($lng)) {
            return '-';
        }
        
        $latDir = $lat >= 0 ? 'N' : 'S';
        $lngDir = $lng >= 0 ? 'E' : 'W';
        
        return sprintf('%.4f°%s, %.4f°%s', abs($lat), $latDir, abs($lng), $lngDir);
    }
} 