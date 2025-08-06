<?php

namespace App\Helpers;

class EmailHelper
{
    private $emailService;
    private $settingsModel;
    
    public function __construct()
    {
        $this->emailService = \Config\Services::emailer();
        $this->settingsModel = new \App\Models\SettingsModel();
    }
    
    /**
     * Send email with order information
     */
    public function sendOrderEmail($orderId, $toEmail, $subject, $message)
    {
        if (!$this->emailService) {
            return [
                'success' => false,
                'message' => 'Email service not configured. Please check SMTP settings.'
            ];
        }

        if (empty($toEmail) || empty($subject) || empty($message)) {
            return [
                'success' => false,
                'message' => 'All fields are required'
            ];
        }

        try {
            // Get settings
            $fromEmail = $this->settingsModel->getSetting('smtp_from', 'noreply@example.com');
            $appName = $this->settingsModel->getSetting('app_name', 'Sales Order System');

            // Compose email
            $this->emailService->setFrom($fromEmail, $appName);
            $this->emailService->setTo($toEmail);
            $this->emailService->setSubject($subject);
            
            // Create HTML message with order information
            $orderNumber = 'SAL-' . str_pad($orderId, 5, '0', STR_PAD_LEFT);
            $htmlMessage = $this->createOrderEmailTemplate($orderNumber, $message, $appName);
            
            $this->emailService->setMessage($htmlMessage);

            // Send email
            if ($this->emailService->send()) {
                return [
                    'success' => true,
                    'message' => 'Email sent successfully to ' . $toEmail
                ];
            } else {
                $debugInfo = $this->emailService->printDebugger(['headers']);
                log_message('error', 'Email sending failed: ' . $debugInfo);
                
                return [
                    'success' => false,
                    'message' => 'Failed to send email. Please check SMTP configuration.'
                ];
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Email error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Email sending error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create HTML email template
     */
    private function createOrderEmailTemplate($orderNumber, $message, $appName)
    {
        return "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0;'>
                    <h2 style='margin: 0;'>{$appName}</h2>
                    <p style='margin: 5px 0 0 0; opacity: 0.9;'>Regarding Order: {$orderNumber}</p>
                </div>
                <div style='background: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; border: 1px solid #e9ecef;'>
                    <div style='background: white; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                    <div style='margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d;'>
                        <p>This email was sent regarding your order {$orderNumber}.</p>
                        <p>Date: " . date('Y-m-d H:i:s') . "</p>
                        <p>If you have any questions, please don't hesitate to contact us.</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Test email configuration
     */
    public function testEmailConfiguration($testEmail)
    {
        if (!$this->emailService) {
            return [
                'success' => false,
                'message' => 'Email service not configured'
            ];
        }

        try {
            $fromEmail = $this->settingsModel->getSetting('smtp_from', 'noreply@example.com');
            $appName = $this->settingsModel->getSetting('app_name', 'Sales Order System');

            $this->emailService->setFrom($fromEmail, $appName);
            $this->emailService->setTo($testEmail);
            $this->emailService->setSubject('SMTP Configuration Test');
            $this->emailService->setMessage('
            <html>
            <body style="font-family: Arial, sans-serif;">
                <h2>SMTP Test Email</h2>
                <p>This is a test email to verify that your SMTP configuration is working correctly.</p>
                <p><strong>Date:</strong> ' . date('Y-m-d H:i:s') . '</p>
                <p>If you received this email, your SMTP settings are configured properly.</p>
            </body>
            </html>');

            if ($this->emailService->send()) {
                return [
                    'success' => true,
                    'message' => 'Test email sent successfully to ' . $testEmail
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to send test email'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Email test error: ' . $e->getMessage()
            ];
        }
    }
} 