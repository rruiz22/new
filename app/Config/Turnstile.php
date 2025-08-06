<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Turnstile extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Cloudflare Turnstile Configuration
     * --------------------------------------------------------------------------
     *
     * These are the site key and secret key for Cloudflare Turnstile
     * Get your keys from: https://dash.cloudflare.com/?to=/:account/turnstile
     */

    /**
     * Site Key (Public Key)
     * This is visible in the frontend HTML
     */
    public string $siteKey = '1x00000000000000000000AA'; // Test key - always passes

    /**
     * Secret Key (Private Key)
     * This is used for server-side verification
     */
    public string $secretKey = '1x0000000000000000000000000000000AA'; // Test key - always passes

    /**
     * Turnstile Verify URL
     * Cloudflare's endpoint for token verification
     */
    public string $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Enable/Disable Turnstile verification
     * Set to false for development if needed
     */
    public bool $enabled = true;

    /**
     * Timeout for verification requests (in seconds)
     */
    public int $timeout = 10;

    /**
     * Whether to verify the client IP
     * Set to false if behind proxy/CDN
     */
    public bool $verifyRemoteIp = false;
} 