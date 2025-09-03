<?php

namespace App\Helpers;

use App\Models\SettingsModel;

class MDALinksHelper
{
    /**
     * Get the dynamic base URL for MDA Links API
     */
    public static function getApiBaseUrl()
    {
        // First check environment variable
        $baseUrl = env('MDA_API_BASE_URL');
        
        if (!$baseUrl) {
            // Fallback to database settings
            $model = new SettingsModel();
            $baseUrl = $model->getSetting('mda_api_base_url');
            
            // Fallback to old setting name for backward compatibility
            if (!$baseUrl) {
                $baseUrl = $model->getSetting('lima_api_base_url');
            }
        }
        
        // Default to mda.to if not configured
        return $baseUrl ?: 'https://mda.to';
    }

    /**
     * Build dynamic API URL
     */
    public static function buildApiUrl($endpoint = 'api/url/add')
    {
        $baseUrl = rtrim(self::getApiBaseUrl(), '/');
        return $baseUrl . '/' . ltrim($endpoint, '/');
    }

    /**
     * Build dynamic QR URL
     */
    public static function buildQrUrl($linkId, $size = 300, $format = 'png')
    {
        $baseUrl = rtrim(self::getApiBaseUrl(), '/');
        
        // Try different QR URL formats for mda.to
        // Format 1: /qr/{linkId}?size={size}&format={format}
        // Format 2: /{linkId}/qr?size={size} 
        // Format 3: /api/qr/{linkId}?size={size}
        
        // First try the standard format
        return "{$baseUrl}/qr/{$linkId}?size={$size}&format={$format}";
    }

    /**
     * Build alternative QR URL formats for testing
     */
    public static function buildAlternativeQrUrl($linkId, $size = 300, $format = 'png')
    {
        $baseUrl = rtrim(self::getApiBaseUrl(), '/');
        
        // Alternative formats to try
        $formats = [
            "{$baseUrl}/{$linkId}/qr?size={$size}",
            "{$baseUrl}/api/qr/{$linkId}?size={$size}",
            "{$baseUrl}/qr?url={$baseUrl}/{$linkId}&size={$size}",
            "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode("{$baseUrl}/{$linkId}")
        ];
        
        return $formats;
    }

    /**
     * Get default domain for short URLs
     */
    public static function getDefaultDomain()
    {
        $model = new SettingsModel();
        $brandedDomain = $model->getSetting('mda_branded_domain');
        
        // Fallback to old setting name for backward compatibility
        if (!$brandedDomain) {
            $brandedDomain = $model->getSetting('lima_branded_domain');
        }
        
        if ($brandedDomain) {
            return $brandedDomain;
        }
        
        // Extract domain from base URL
        $baseUrl = self::getApiBaseUrl();
        $parsed = parse_url($baseUrl);
        return $parsed['host'] ?? 'mda.to';
    }

    /**
     * Get MDA Links API key
     */
    public static function getApiKey()
    {
        // First check environment variable
        $apiKey = env('MDA_API_KEY');
        
        if (!$apiKey) {
            // Fallback to database settings
            $model = new SettingsModel();
            $apiKey = $model->getSetting('mda_api_key');
            
            // Fallback to old setting name for backward compatibility
            if (!$apiKey) {
                $apiKey = $model->getSetting('lima_api_key');
            }
        }
        
        return $apiKey;
    }

    /**
     * Get branded domain
     */
    public static function getBrandedDomain()
    {
        // First check environment variable
        $brandedDomain = env('MDA_BRANDED_DOMAIN');
        
        if (!$brandedDomain) {
            // Fallback to database settings
            $model = new SettingsModel();
            $brandedDomain = $model->getSetting('mda_branded_domain');
            
            // Fallback to old setting name for backward compatibility
            if (!$brandedDomain) {
                $brandedDomain = $model->getSetting('lima_branded_domain');
            }
        }
        
        return $brandedDomain;
    }

    /**
     * Check if MDA Links is configured
     */
    public static function isConfigured()
    {
        $apiKey = self::getApiKey();
        return !empty($apiKey) && !in_array($apiKey, ['your_lima_links_api_key_here', 'your_mda_api_key_here']);
    }

    /**
     * Build complete configuration array for MDA Links API calls
     */
    public static function getConfiguration()
    {
        return [
            'api_key' => self::getApiKey(),
            'branded_domain' => self::getBrandedDomain(),
            'base_url' => self::getApiBaseUrl(),
            'api_url' => self::buildApiUrl(),
            'qr_api_url' => self::buildApiUrl('api/qr/generate'),
            'default_domain' => self::getDefaultDomain(),
            'is_configured' => self::isConfigured()
        ];
    }
}