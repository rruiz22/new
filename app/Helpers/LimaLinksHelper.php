<?php

namespace App\Helpers;

use App\Models\SettingsModel;

class LimaLinksHelper
{
    /**
     * Get the dynamic base URL for Lima Links API
     */
    public static function getApiBaseUrl()
    {
        $model = new SettingsModel();
        $baseUrl = $model->getSetting('lima_api_base_url');
        
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
        return "{$baseUrl}/qr/{$linkId}?size={$size}&format={$format}";
    }

    /**
     * Get default domain for short URLs
     */
    public static function getDefaultDomain()
    {
        $model = new SettingsModel();
        $brandedDomain = $model->getSetting('lima_branded_domain');
        
        if ($brandedDomain) {
            return $brandedDomain;
        }
        
        // Extract domain from base URL
        $baseUrl = self::getApiBaseUrl();
        $parsed = parse_url($baseUrl);
        return $parsed['host'] ?? 'mda.to';
    }

    /**
     * Get Lima Links API key
     */
    public static function getApiKey()
    {
        $model = new SettingsModel();
        return $model->getSetting('lima_api_key');
    }

    /**
     * Get branded domain
     */
    public static function getBrandedDomain()
    {
        $model = new SettingsModel();
        return $model->getSetting('lima_branded_domain');
    }

    /**
     * Check if Lima Links is configured
     */
    public static function isConfigured()
    {
        $apiKey = self::getApiKey();
        return !empty($apiKey) && $apiKey !== 'your_lima_links_api_key_here';
    }

    /**
     * Build complete configuration array for Lima Links API calls
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