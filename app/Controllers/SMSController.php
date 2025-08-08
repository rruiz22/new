<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\TwilioService;
use App\Models\SMSConversationModel;

class SMSController extends BaseController
{
    protected $twilioService;
    protected $smsModel;

    public function __construct()
    {
        $this->twilioService = new TwilioService();
        $this->smsModel = new SMSConversationModel();
    }

    /**
     * Send SMS - Global endpoint for all modules
     */
    public function send()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Get form data
            $phone = $this->request->getPost('phone');
            $message = $this->request->getPost('message');
            $orderId = $this->request->getPost('order_id');
            $module = $this->request->getPost('module');
            $contactName = $this->request->getPost('contact_name');

            // Validate required fields
            if (empty($phone) || empty($message)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Phone number and message are required'
                ]);
            }

            // Validate message length
            if (strlen($message) > 1600) { // Twilio limit for long messages
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Message is too long. Maximum 1600 characters allowed.'
                ]);
            }

            // Check if Twilio is configured
            if (!$this->twilioService->isConfigured()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'SMS service is not configured properly'
                ]);
            }

            // Prepare metadata
            $metadata = [
                'order_id' => $orderId,
                'module' => $module,
                'contact_name' => $contactName,
                'sent_by_user_id' => auth()->id() ?? session()->get('user_id')
            ];

            // Send SMS
            $result = $this->twilioService->sendSMS($phone, $message, $metadata);

            if ($result['success']) {
                // Log activity in the appropriate module
                $this->logModuleActivity($module, $orderId, $phone, $message, $contactName);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'SMS sent successfully to ' . $contactName,
                    'twilio_sid' => $result['twilio_sid'] ?? null
                ]);
            } else {
                return $this->response->setJSON($result);
            }

        } catch (\Exception $e) {
            log_message('error', 'SMS Send Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get conversation history
     */
    public function getConversation()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $phone = $this->request->getGet('phone');
            $orderId = $this->request->getGet('order_id');
            $module = $this->request->getGet('module');

            if (empty($phone)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Phone number is required'
                ]);
            }

            $messages = $this->twilioService->getConversation($phone, $orderId, $module);

            return $this->response->setJSON([
                'success' => true,
                'messages' => $messages,
                'twilio_number' => $this->twilioService->getTwilioNumber()
            ]);

        } catch (\Exception $e) {
            log_message('error', 'SMS Conversation Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load conversation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Twilio webhook handler for incoming SMS
     */
    public function webhook()
    {
        try {
            // Validate Twilio signature (optional but recommended)
            // $this->validateTwilioSignature();

            $webhookData = $this->request->getPost();
            log_message('info', 'Twilio Webhook Data: ' . json_encode($webhookData));

            $result = $this->twilioService->processIncomingSMS($webhookData);

            // Respond with TwiML (required by Twilio)
            $this->response->setHeader('Content-Type', 'text/xml');
            return $this->response->setBody('<?xml version="1.0" encoding="UTF-8"?><Response></Response>');

        } catch (\Exception $e) {
            log_message('error', 'SMS Webhook Error: ' . $e->getMessage());
            
            // Still return valid TwiML even on error
            $this->response->setHeader('Content-Type', 'text/xml');
            return $this->response->setBody('<?xml version="1.0" encoding="UTF-8"?><Response></Response>');
        }
    }

    /**
     * Status webhook handler for delivery receipts
     */
    public function statusWebhook()
    {
        try {
            $webhookData = $this->request->getPost();
            log_message('info', 'Twilio Status Webhook: ' . json_encode($webhookData));

            $messageSid = $webhookData['MessageSid'] ?? '';
            $status = $webhookData['MessageStatus'] ?? '';
            $errorCode = $webhookData['ErrorCode'] ?? null;

            if ($messageSid && $status) {
                $errorMessage = $errorCode ? "Error Code: {$errorCode}" : null;
                $this->smsModel->updateMessageStatus($messageSid, $status, $errorMessage);
            }

            return $this->response->setBody('OK');

        } catch (\Exception $e) {
            log_message('error', 'SMS Status Webhook Error: ' . $e->getMessage());
            return $this->response->setBody('ERROR');
        }
    }

    /**
     * Get SMS templates
     */
    public function getTemplates()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $module = $this->request->getGet('module') ?? 'general';
        
        $templates = $this->getSMSTemplates($module);

        return $this->response->setJSON([
            'success' => true,
            'templates' => $templates
        ]);
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $conversationId = $this->request->getPost('conversation_id');
            
            if ($conversationId) {
                $this->smsModel->markAsRead($conversationId);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Conversation marked as read'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to mark as read'
            ]);
        }
    }

    /**
     * Log activity in appropriate module
     */
    private function logModuleActivity($module, $orderId, $phone, $message, $contactName)
    {
        try {
            if (!$orderId || !$module) {
                return;
            }

            $userId = auth()->id() ?? session()->get('user_id') ?? 1;

            switch ($module) {
                case 'sales_orders':
                    $activityModel = new \Modules\SalesOrders\Models\OrderActivityModel();
                    if (method_exists($activityModel, 'logSMSSent')) {
                        $activityModel->logSMSSent($orderId, $userId, $phone, $message);
                    }
                    break;

                case 'carwash':
                    // Add when CarWash module activity model is available
                    break;

                case 'recon':
                    // Add when Recon module activity model is available
                    break;

                case 'service':
                    // Add when Service module activity model is available
                    break;
            }

        } catch (\Exception $e) {
            log_message('error', 'SMS Module Activity Logging Error: ' . $e->getMessage());
        }
    }

    /**
     * Get SMS templates by module
     */
    private function getSMSTemplates($module)
    {
        $templates = [
            'general' => [
                ['name' => 'Appointment Reminder', 'message' => 'Hi {contact_name}, this is a reminder about your appointment scheduled for {date} at {time}. Please reply to confirm. View details: {order_url}'],
                ['name' => 'Order Ready', 'message' => 'Hi {contact_name}, your order is ready for pickup. Please visit us at your convenience. View details: {order_url}'],
                ['name' => 'Thank You', 'message' => 'Thank you for choosing our services, {contact_name}! We appreciate your business. View details: {order_url}'],
            ],
            'sales_orders' => [
                ['name' => 'Order Confirmation', 'message' => 'Hi {contact_name}, your sales order has been confirmed. Order #: {order_number}. We\'ll keep you updated on the progress. View details: {order_url}'],
                ['name' => 'Order Processing', 'message' => 'Hi {contact_name}, your order {order_number} is now being processed. Estimated completion: {estimated_date}. View details: {order_url}'],
                ['name' => 'Order Complete', 'message' => 'Great news {contact_name}! Your order {order_number} is complete and ready for pickup. View details: {order_url}'],
                ['name' => 'Payment Reminder', 'message' => 'Hi {contact_name}, this is a friendly reminder about the payment for order {order_number}. Please contact us if you have any questions. View details: {order_url}'],
            ],
            'carwash' => [
                ['name' => 'Service Ready', 'message' => 'Hi {contact_name}, your car wash service is complete! Your vehicle is ready for pickup. View details: {order_url}'],
                ['name' => 'Appointment Confirmation', 'message' => 'Hi {contact_name}, your car wash appointment for {date} at {time} has been confirmed. View details: {order_url}'],
            ],
            'recon' => [
                ['name' => 'Inspection Complete', 'message' => 'Hi {contact_name}, the vehicle inspection is complete. Please contact us to discuss the findings. View details: {order_url}'],
                ['name' => 'Estimate Ready', 'message' => 'Hi {contact_name}, your vehicle estimate is ready. Please call us to review the details. View details: {order_url}'],
            ],
            'service' => [
                ['name' => 'Service Reminder', 'message' => 'Hi {contact_name}, your vehicle is due for service. Please schedule an appointment at your convenience. View details: {order_url}'],
                ['name' => 'Service Complete', 'message' => 'Hi {contact_name}, your vehicle service is complete and ready for pickup. View details: {order_url}'],
            ]
        ];

        return $templates[$module] ?? $templates['general'];
    }

    /**
     * Validate Twilio signature (security)
     */
    private function validateTwilioSignature()
    {
        $signature = $this->request->getHeaderLine('X-Twilio-Signature');
        $url = current_url();
        $postVars = $this->request->getPost();
        
        // Implement Twilio signature validation
        // This is optional but recommended for production
    }
}
