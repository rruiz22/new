<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class EmailTestController extends BaseController
{
    /**
     * Test email sending with current SMTP settings
     */
    public function testEmail()
    {
        if ($this->request->getMethod() === 'POST') {
            $toEmail = $this->request->getPost('email');
            $subject = $this->request->getPost('subject') ?: 'Test Email from Sales Order System';
            $message = $this->request->getPost('message') ?: 'This is a test email to verify SMTP configuration.';
            
            if (empty($toEmail)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email address is required'
                ]);
            }
            
            // Use EmailHelper to send test email
            $emailHelper = new \App\Helpers\EmailHelper();
            $result = $emailHelper->sendOrderEmail(1, $toEmail, $subject, $message);
            
            return $this->response->setJSON($result);
        }
        
        return view('email_test/form');
    }
    
    /**
     * Test SMTP configuration
     */
    public function testSmtpConfig()
    {
        $testEmail = $this->request->getPost('test_email');
        
        if (empty($testEmail)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Test email address is required'
            ]);
        }
        
        $emailHelper = new \App\Helpers\EmailHelper();
        $result = $emailHelper->testEmailConfiguration($testEmail);
        
        return $this->response->setJSON($result);
    }
    
    /**
     * Get current SMTP settings
     */
    public function getSmtpSettings()
    {
        $settingsModel = new \App\Models\SettingsModel();
        $smtpSettings = $settingsModel->getSmtpSettings();
        
        // Hide password for security
        $smtpSettings['smtp_pass'] = !empty($smtpSettings['smtp_pass']) ? '••••••••' : '';
        
        return $this->response->setJSON([
            'success' => true,
            'settings' => $smtpSettings
        ]);
    }
} 