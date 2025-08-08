<?php

namespace App\Libraries;

use Config\Services;
use Exception;

class TwilioService
{
    private $accountSid;
    private $authToken;
    private $twilioNumber;
    private $webhookUrl;
    
    public function __construct()
    {
        // Load Twilio configuration from environment or config
        $this->accountSid = getenv('TWILIO_ACCOUNT_SID') ?: '';
        $this->authToken = getenv('TWILIO_AUTH_TOKEN') ?: '';
        $this->twilioNumber = getenv('TWILIO_PHONE_NUMBER') ?: '';
        $this->webhookUrl = getenv('TWILIO_WEBHOOK_URL') ?: base_url('sms/webhook');
        
        if (empty($this->accountSid) || empty($this->authToken) || empty($this->twilioNumber)) {
            log_message('error', 'Twilio credentials not configured properly');
        }
    }
    
    /**
     * Send SMS message
     *
     * @param string $to Recipient phone number
     * @param string $message Message content
     * @param array $metadata Additional metadata (order_id, module, etc.)
     * @return array Response with success status and message
     */
    public function sendSMS($to, $message, $metadata = [])
    {
        try {
            if (empty($this->accountSid) || empty($this->authToken)) {
                throw new Exception('Twilio credentials not configured');
            }
            
            // Clean phone number
            $to = $this->cleanPhoneNumber($to);
            
            if (!$this->isValidPhoneNumber($to)) {
                throw new Exception('Invalid phone number format');
            }
            
            // Process message to replace order URL placeholder
            $message = $this->processMessagePlaceholders($message, $metadata);
            
            // Initialize cURL
            $curl = curl_init();
            
            $postData = [
                'From' => $this->twilioNumber,
                'To' => $to,
                'Body' => $message,
                'StatusCallback' => $this->webhookUrl . '/status'
            ];
            
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($postData),
                CURLOPT_USERPWD => $this->accountSid . ':' . $this->authToken,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/x-www-form-urlencoded'
                ],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 30
            ]);
            
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);
            
            if ($error) {
                throw new Exception('cURL Error: ' . $error);
            }
            
            $responseData = json_decode($response, true);
            
            if ($httpCode >= 200 && $httpCode < 300) {
                // Success - log the conversation
                $this->logSMSConversation([
                    'twilio_sid' => $responseData['sid'] ?? null,
                    'direction' => 'outbound',
                    'from_number' => $this->twilioNumber,
                    'to_number' => $to,
                    'message' => $message,
                    'status' => $responseData['status'] ?? 'sent',
                    'metadata' => json_encode($metadata),
                    'sent_by_user_id' => auth()->id() ?? session()->get('user_id') ?? null
                ]);
                
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'twilio_sid' => $responseData['sid'] ?? null,
                    'status' => $responseData['status'] ?? 'sent'
                ];
            } else {
                $errorMessage = $responseData['message'] ?? 'Unknown error occurred';
                throw new Exception('Twilio API Error: ' . $errorMessage);
            }
            
        } catch (Exception $e) {
            log_message('error', 'SMS Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get SMS conversation history
     *
     * @param string $phoneNumber Phone number to get conversation for
     * @param int $orderId Optional order ID to filter by
     * @param string $module Optional module to filter by
     * @param int $limit Number of messages to retrieve
     * @return array Conversation messages
     */
    public function getConversation($phoneNumber, $orderId = null, $module = null, $limit = 50)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('sms_conversations sc');
        
        $builder->select('sc.*, u.first_name, u.last_name, 
                         CONCAT(u.first_name, " ", u.last_name) as sender_name')
                ->join('users u', 'u.id = sc.sent_by_user_id', 'left')
                ->where('sc.to_number', $this->cleanPhoneNumber($phoneNumber))
                ->orWhere('sc.from_number', $this->cleanPhoneNumber($phoneNumber));
        
        if ($orderId) {
            $builder->where('JSON_EXTRACT(sc.metadata, "$.order_id")', $orderId);
        }
        
        if ($module) {
            $builder->where('JSON_EXTRACT(sc.metadata, "$.module")', $module);
        }
        
        $messages = $builder->orderBy('sc.created_at', 'DESC')
                           ->limit($limit)
                           ->get()
                           ->getResultArray();
        
        // Reverse to show chronological order
        return array_reverse($messages);
    }
    
    /**
     * Process incoming SMS webhook from Twilio
     *
     * @param array $webhookData Twilio webhook data
     * @return array Processing result
     */
    public function processIncomingSMS($webhookData)
    {
        try {
            $from = $webhookData['From'] ?? '';
            $to = $webhookData['To'] ?? '';
            $message = $webhookData['Body'] ?? '';
            $twilioSid = $webhookData['MessageSid'] ?? '';
            
            if (empty($from) || empty($message)) {
                throw new Exception('Invalid webhook data');
            }
            
            // Find the original conversation context
            $conversationContext = $this->findConversationContext($from);
            
            // Log incoming message
            $this->logSMSConversation([
                'twilio_sid' => $twilioSid,
                'direction' => 'inbound',
                'from_number' => $from,
                'to_number' => $to,
                'message' => $message,
                'status' => 'received',
                'metadata' => json_encode($conversationContext),
                'sent_by_user_id' => null // Incoming message
            ]);
            
            // Notify the original sender if context is found
            if ($conversationContext) {
                $this->notifyOriginalSender($conversationContext, $from, $message);
            }
            
            return [
                'success' => true,
                'message' => 'Incoming SMS processed successfully'
            ];
            
        } catch (Exception $e) {
            log_message('error', 'Incoming SMS Processing Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to process incoming SMS: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Clean and format phone number
     */
    private function cleanPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present (assuming US/Canada)
        if (strlen($phone) == 10) {
            $phone = '1' . $phone;
        }
        
        // Add + prefix for international format
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Validate phone number format
     */
    private function isValidPhoneNumber($phone)
    {
        // Basic validation for international format
        return preg_match('/^\+[1-9]\d{1,14}$/', $phone);
    }
    
    /**
     * Log SMS conversation to database
     */
    private function logSMSConversation($data)
    {
        $db = \Config\Database::connect();
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $db->table('sms_conversations')->insert($data);
    }
    
    /**
     * Find conversation context for incoming SMS
     */
    private function findConversationContext($phoneNumber)
    {
        $db = \Config\Database::connect();
        
        // Find the most recent outbound message to this number
        $lastOutbound = $db->table('sms_conversations')
                          ->where('direction', 'outbound')
                          ->where('to_number', $phoneNumber)
                          ->orderBy('created_at', 'DESC')
                          ->limit(1)
                          ->get()
                          ->getRowArray();
        
        if ($lastOutbound && $lastOutbound['metadata']) {
            return json_decode($lastOutbound['metadata'], true);
        }
        
        return null;
    }
    
    /**
     * Notify original sender about incoming reply
     */
    private function notifyOriginalSender($context, $fromPhone, $message)
    {
        if (!isset($context['order_id']) || !isset($context['module'])) {
            return;
        }
        
        // Create notification for the original sender
        $notificationData = [
            'user_id' => $context['sent_by_user_id'] ?? null,
            'type' => 'sms_reply',
            'title' => 'SMS Reply Received',
            'message' => "Reply from {$fromPhone}: " . substr($message, 0, 100) . (strlen($message) > 100 ? '...' : ''),
            'data' => json_encode([
                'order_id' => $context['order_id'],
                'module' => $context['module'],
                'phone' => $fromPhone,
                'full_message' => $message
            ]),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $db = \Config\Database::connect();
        $db->table('notifications')->insert($notificationData);
        
        // Also log as activity if we have order context
        $this->logSMSActivity($context, $fromPhone, $message);
    }
    
    /**
     * Log SMS activity to order activities
     */
    private function logSMSActivity($context, $fromPhone, $message)
    {
        try {
            if (!isset($context['order_id']) || !isset($context['module'])) {
                return;
            }
            
            $orderId = $context['order_id'];
            $module = $context['module'];
            
            // Get the appropriate activity model based on module
            $activityModel = null;
            switch ($module) {
                case 'sales_orders':
                    $activityModel = new \Modules\SalesOrders\Models\OrderActivityModel();
                    break;
                case 'carwash':
                    // Add when implemented
                    break;
                case 'recon':
                    // Add when implemented
                    break;
                case 'service':
                    // Add when implemented
                    break;
            }
            
            if ($activityModel && method_exists($activityModel, 'logSMSReceived')) {
                $activityModel->logSMSReceived(
                    $orderId,
                    1, // System user for incoming messages
                    $fromPhone,
                    $message
                );
            }
            
        } catch (Exception $e) {
            log_message('error', 'SMS Activity Logging Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Get Twilio phone number
     */
    public function getTwilioNumber()
    {
        return $this->twilioNumber;
    }
    
    /**
     * Check if Twilio is properly configured
     */
    public function isConfigured()
    {
        return !empty($this->accountSid) && !empty($this->authToken) && !empty($this->twilioNumber);
    }
    
    /**
     * Process message placeholders, especially {order_url}
     */
    private function processMessagePlaceholders($message, $metadata)
    {
        // If no order_id or module, return message as-is
        if (!isset($metadata['order_id']) || !isset($metadata['module'])) {
            return $message;
        }
        
        try {
            $orderId = $metadata['order_id'];
            $module = $metadata['module'];
            $orderUrl = $this->getOrderUrl($orderId, $module);
            
            // Replace the {order_url} placeholder
            $message = str_replace('{order_url}', $orderUrl, $message);
            
            return $message;
            
        } catch (Exception $e) {
            log_message('error', 'Error processing message placeholders: ' . $e->getMessage());
            return $message; // Return original message if error
        }
    }
    
    /**
     * Get the order URL (preferring mda.to short URL if available)
     */
    private function getOrderUrl($orderId, $module)
    {
        $db = \Config\Database::connect();
        
        try {
            switch ($module) {
                case 'sales_orders':
                    $table = 'sales_orders';
                    $viewRoute = 'sales_orders/view/';
                    break;
                case 'carwash':
                    $table = 'carwash_orders';
                    $viewRoute = 'carwash/view/';
                    break;
                case 'recon':
                    $table = 'recon_orders';
                    $viewRoute = 'recon_orders/view/';
                    break;
                case 'service':
                    $table = 'service_orders';
                    $viewRoute = 'service_orders/view/';
                    break;
                default:
                    return base_url();
            }
            
            // Get the order with short_url if available
            $query = $db->table($table)
                        ->select('short_url, short_url_slug')
                        ->where('id', $orderId)
                        ->where('deleted', 0)
                        ->get();
            
            $order = $query->getRowArray();
            
            if ($order && !empty($order['short_url']) && !empty($order['short_url_slug'])) {
                // Use the mda.to short URL
                return $order['short_url'];
            } else {
                // Fallback to regular URL
                return base_url($viewRoute . $orderId);
            }
            
        } catch (Exception $e) {
            log_message('error', 'Error getting order URL: ' . $e->getMessage());
            // Fallback to regular URL
            return base_url($viewRoute . $orderId);
        }
    }
}
