<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    /**
     * Twilio Service
     */
    public static function twilio($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('twilio');
        }

        // Load settings from database
        $settingsModel = new \App\Models\SettingsModel();
        $twilioSettings = $settingsModel->getTwilioSettings();
        
        // Fallback to environment variables if database settings are empty
        $accountSid = !empty($twilioSettings['twilio_sid']) ? 
            $twilioSettings['twilio_sid'] : 
            env('TWILIO_ACCOUNT_SID', '');
            
        $authToken = !empty($twilioSettings['twilio_token']) ? 
            $twilioSettings['twilio_token'] : 
            env('TWILIO_AUTH_TOKEN', '');
            
        $twilioNumber = !empty($twilioSettings['twilio_number']) ? 
            $twilioSettings['twilio_number'] : 
            env('TWILIO_PHONE_NUMBER', '');

        if (empty($accountSid) || empty($authToken)) {
            throw new \Exception('Twilio credentials not configured. Please set them in Settings or environment variables.');
        }

        return new \Twilio\Rest\Client($accountSid, $authToken);
    }

    /**
     * Pusher Service
     */
    public static function pusher($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('pusher');
        }

        $settingsModel = new \App\Models\SettingsModel();
        $settings = $settingsModel->getSettings();
        
        $pusherConfig = [
            'app_id' => $settings['pusher_app_id'] ?? '',
            'key' => $settings['pusher_key'] ?? '',
            'secret' => $settings['pusher_secret'] ?? '',
            'cluster' => $settings['pusher_cluster'] ?? 'us2',
            'useTLS' => true
        ];
        
        if (!empty($pusherConfig['app_id']) && !empty($pusherConfig['key']) && !empty($pusherConfig['secret'])) {
            return new \Pusher\Pusher(
                $pusherConfig['key'],
                $pusherConfig['secret'],
                $pusherConfig['app_id'],
                [
                    'cluster' => $pusherConfig['cluster'],
                    'useTLS' => $pusherConfig['useTLS']
                ]
            );
        }
        
        return null;
    }

    /**
     * Email Service with SMTP configuration from settings
     */
    public static function emailer($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('emailer');
        }

        $settingsModel = new \App\Models\SettingsModel();
        $settings = $settingsModel->getSettings();
        
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => $settings['smtp_host'] ?? '',
            'SMTPPort' => (int)($settings['smtp_port'] ?? 587),
            'SMTPUser' => $settings['smtp_user'] ?? '',
            'SMTPPass' => $settings['smtp_pass'] ?? '',
            'SMTPCrypto' => $settings['smtp_encryption'] ?? 'tls',
            'mailType' => 'html',
            'charset' => 'utf-8',
            'priority' => 1,
            'newline' => "\r\n",
            'SMTPTimeout' => 15,
            'wordWrap' => true,
            'fromEmail' => $settings['smtp_from'] ?? '',
            'fromName' => $settings['app_name'] ?? 'Sales Order System'
        ];

        // Add SSL options for better compatibility
        $config['SMTPOptions'] = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        if (!empty($config['SMTPHost']) && !empty($config['SMTPUser']) && !empty($config['SMTPPass'])) {
            return \Config\Services::email($config);
        }
        
        return null;
    }
}
