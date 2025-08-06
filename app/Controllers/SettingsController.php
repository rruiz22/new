<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingsModel;
use App\Helpers\LimaLinksHelper;

class SettingsController extends BaseController
{
    public function index()
    {
        $model = new SettingsModel();
        $settings = $model->getSettings();

        return view('settings/index', [
            'settings' => $settings
        ]);
    }



    public function save()
    {
        try {
            $model = new SettingsModel();
            $data = $this->request->getPost();
            $errors = [];

            // Lista de claves permitidas
            $allowed = [
                'app_logo', 'app_favicon', 'app_name', 'app_description', 'app_email', 'app_lang',
                'logo_dark_sm', 'logo_dark_lg', 'logo_light_sm', 'logo_light_lg',
                'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from',
                'twilio_sid', 'twilio_token', 'twilio_number',
                'pusher_app_id', 'pusher_key', 'pusher_secret', 'pusher_cluster',
                'slack_webhook',
                'cron_enabled', 'cron_token',
                'show_theme_color_changer', 'footer_text', 'pwa_enabled', 'enable_cron_jobs', 'enable_pwa',
                'sms_templates', 'email_templates',
                'lima_api_key', 'lima_branded_domain', 'lima_api_base_url', 'enable_tinyurl', 'enable_isgd'
            ];

            $uploadPath = ROOTPATH . 'assets/images/logos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Procesar logo principal
            $this->processImageUpload(
                'app_logo',
                $uploadPath,
                $model->getSetting('app_logo'),
                'remove_logo',
                'logo_',
                $data,
                $errors
            );

            // Procesar favicon
            $this->processImageUpload(
                'app_favicon',
                $uploadPath,
                $model->getSetting('app_favicon'),
                'remove_favicon',
                'favicon_',
                $data,
                $errors
            );
            
            // Procesar logos adicionales
            // Dark Small Logo
            $this->processImageUpload(
                'logo_dark_sm',
                $uploadPath,
                $model->getSetting('logo_dark_sm'),
                'remove_logo_dark_sm',
                'dark_sm_',
                $data,
                $errors
            );
            
            // Dark Large Logo
            $this->processImageUpload(
                'logo_dark_lg',
                $uploadPath,
                $model->getSetting('logo_dark_lg'),
                'remove_logo_dark_lg',
                'dark_lg_',
                $data,
                $errors
            );
            
            // Light Small Logo
            $this->processImageUpload(
                'logo_light_sm',
                $uploadPath,
                $model->getSetting('logo_light_sm'),
                'remove_logo_light_sm',
                'light_sm_',
                $data,
                $errors
            );
            
            // Light Large Logo
            $this->processImageUpload(
                'logo_light_lg',
                $uploadPath,
                $model->getSetting('logo_light_lg'),
                'remove_logo_light_lg',
                'light_lg_',
                $data,
                $errors
            );

            if (!empty($errors)) {
                // Si es una petición AJAX, devolver JSON
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => implode(', ', $errors),
                        'errors' => $errors
                    ]);
                }
                return redirect()->back()->withInput()->with('errors', $errors);
            }

            // Process SMS and Email Templates
            if (isset($data['sms_templates'])) {
                $smsTemplates = [];
                foreach ($data['sms_templates'] as $template) {
                    if (!empty($template['name']) && !empty($template['content'])) {
                        $smsTemplates[] = [
                            'name' => $template['name'],
                            'content' => $template['content']
                        ];
                    }
                }
                $data['sms_templates'] = json_encode($smsTemplates);
            }

            if (isset($data['email_templates'])) {
                $emailTemplates = [];
                foreach ($data['email_templates'] as $template) {
                    if (!empty($template['name']) && !empty($template['subject']) && !empty($template['content'])) {
                        $emailTemplates[] = [
                            'name' => $template['name'],
                            'subject' => $template['subject'],
                            'content' => $template['content']
                        ];
                    }
                }
                $data['email_templates'] = json_encode($emailTemplates);
            }

            foreach ($allowed as $key) {
                if (isset($data[$key]) || array_key_exists($key, $data)) {
                    $model->setSetting($key, $data[$key]);
                }
            }

            // Si es una petición AJAX, devolver JSON
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => lang('App.settings_saved')
                ]);
            }

            return redirect()->back()->with('success', lang('App.settings_saved'));
            
        } catch (\Throwable $e) {
            // Capturar cualquier error y devolver JSON si es AJAX
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error del servidor: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Error del servidor: ' . $e->getMessage());
        }
    }

    /**
     * Prueba una conexión SMTP para verificar que la configuración es correcta
     */
    public function testSmtp()
    {
        try {
            // Configuramos la respuesta como JSON desde el principio
            $this->response->setContentType('application/json');
            
            // Obtener parámetros de la solicitud
            $host = trim($this->request->getPost('smtp_host') ?? '');
            $port = trim($this->request->getPost('smtp_port') ?? '');
            $user = trim($this->request->getPost('smtp_user') ?? '');
            $pass = trim($this->request->getPost('smtp_pass') ?? '');
            $encryption = trim($this->request->getPost('smtp_encryption') ?? 'tls');
            $from = trim($this->request->getPost('smtp_from') ?? '');
            
            // Debug: Log received data
            log_message('info', 'SMTP Test - Received data: ' . json_encode([
                'host' => $host ? 'SET' : 'EMPTY',
                'port' => $port ? 'SET' : 'EMPTY', 
                'user' => $user ? 'SET' : 'EMPTY',
                'pass' => $pass ? 'SET' : 'EMPTY',
                'encryption' => $encryption,
                'from' => $from ? 'SET' : 'EMPTY'
            ]));
            
            // Verificar que se proporcionaron todos los datos necesarios
            $missingFields = [];
            if (empty($host)) $missingFields[] = 'SMTP Host';
            if (empty($port)) $missingFields[] = 'SMTP Port';
            if (empty($user)) $missingFields[] = 'SMTP User'; 
            if (empty($pass)) $missingFields[] = 'SMTP Password';
            if (empty($from)) $missingFields[] = 'From Email';
            
            if (!empty($missingFields)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Campos requeridos faltantes: ' . implode(', ', $missingFields),
                    'debug' => [
                        'host' => $host,
                        'port' => $port,
                        'user' => $user,
                        'pass' => !empty($pass) ? '***SET***' : 'EMPTY',
                        'from' => $from,
                        'encryption' => $encryption
                    ]
                ]);
            }
            
            // Asegurarse de que el puerto sea un entero
            $port = (int) $port;
            
            // Ajustar encriptación basada en el puerto común
            if ($port == 465 && $encryption == 'tls') {
                $encryption = 'ssl'; // Puerto 465 generalmente usa SSL
                log_message('info', 'SMTP Test - Changed encryption from TLS to SSL for port 465');
            }
            
            // Configurar el servicio de email manualmente  
            $config = [
                'protocol' => 'smtp',
                'SMTPHost' => $host,
                'SMTPPort' => $port,
                'SMTPUser' => $user,
                'SMTPPass' => $pass,
                'SMTPCrypto' => $encryption,
                'mailType' => 'html',
                'charset' => 'utf-8',
                'priority' => 1,
                'newline' => "\r\n",
                'SMTPTimeout' => 30, // Aumentar timeout para servidores como SiteGround
                'SMTPKeepAlive' => false,
                'wordWrap' => true
            ];
            
            // Configurar opciones SSL/TLS según el tipo de encriptación
            $config['SMTPOptions'] = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT
                ]
            ];
            
            log_message('info', 'SMTP Test - Using config: ' . json_encode([
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
                'user' => $user,
                'timeout' => $config['SMTPTimeout']
            ]));
            
            try {
                $email = \Config\Services::email($config);
                $email->setFrom($from, 'Test Connection');
                $email->setTo($from);
                $email->setSubject('SMTP Connection Test');
                $email->setMessage('This is a test message to verify SMTP connection.');
                
                log_message('info', 'SMTP Test - Attempting connection to ' . $host . ':' . $port);
                
                // Intentar enviar el email de prueba
                $result = $email->send();
                
                if ($result) {
                    log_message('info', 'SMTP Test - Connection successful');
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Conexión SMTP establecida correctamente. Email de prueba enviado.',
                        'details' => [
                            'host' => $host,
                            'port' => $port,
                            'encryption' => $encryption,
                            'user' => $user
                        ]
                    ]);
                } else {
                    $debugInfo = $email->printDebugger(['headers', 'subject', 'body']);
                    log_message('error', 'SMTP Test - Send failed. Debug: ' . $debugInfo);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se pudo enviar el email de prueba',
                        'error' => 'Email send failed',
                        'debug' => $debugInfo
                    ]);
                }
                
            } catch (\Exception $innerEx) {
                $errorMsg = $innerEx->getMessage();
                log_message('error', 'SMTP Test - Exception: ' . $errorMsg);
                
                // Analizar el tipo de error para dar mejores mensajes
                if (strpos($errorMsg, 'Connection refused') !== false) {
                    $message = 'No se puede conectar al servidor SMTP. Verifica el host y puerto.';
                } elseif (strpos($errorMsg, 'Authentication failed') !== false || strpos($errorMsg, '535') !== false) {
                    $message = 'Error de autenticación. Verifica el usuario y contraseña.';
                } elseif (strpos($errorMsg, 'SSL') !== false || strpos($errorMsg, 'TLS') !== false) {
                    $message = 'Error de certificado SSL/TLS. Para puerto 465 usa SSL, para 587 usa TLS.';
                } elseif (strpos($errorMsg, 'timeout') !== false) {
                    $message = 'Timeout de conexión. El servidor puede estar lento o bloqueado.';
                } else {
                    $message = 'Error de conexión SMTP: ' . $errorMsg;
                }
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $message,
                    'error' => $errorMsg,
                    'suggestions' => [
                        'Para puerto 465: usar SSL',
                        'Para puerto 587: usar TLS', 
                        'Verificar que el firewall no bloquee la conexión',
                        'Confirmar que las credenciales sean correctas'
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'SMTP Test - General error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        } catch (\Throwable $e) {
            // Capturamos cualquier tipo de error, incluso los más graves
            log_message('error', 'SMTP Test - Throwable error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Envía un email de prueba para verificar que la configuración SMTP funciona correctamente
     */
    public function sendTestEmail()
    {
        try {
            // Configuramos la respuesta como JSON desde el principio
            $this->response->setContentType('application/json');
            
            // Obtener parámetros de la solicitud
            $host = $this->request->getPost('smtp_host');
            $port = $this->request->getPost('smtp_port');
            $user = $this->request->getPost('smtp_user');
            $pass = $this->request->getPost('smtp_pass');
            $encryption = $this->request->getPost('smtp_encryption');
            $from = $this->request->getPost('smtp_from');
            $to = $this->request->getPost('test_email');
            
            // Verificar que se proporcionaron todos los datos necesarios
            if (empty($host) || empty($port) || empty($user) || empty($pass) || empty($from) || empty($to)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Todos los campos son obligatorios, incluyendo el email de destino'
                ]);
            }
            
            // Asegurarse de que el puerto sea un entero
            $port = (int) $port;
            
            // Configurar el servicio de email
            $config = [
                'protocol' => 'smtp',
                'SMTPHost' => $host,
                'SMTPPort' => $port,
                'SMTPUser' => $user,
                'SMTPPass' => $pass,
                'SMTPCrypto' => $encryption,
                'mailType' => 'html',
                'charset' => 'utf-8',
                'priority' => 1,
                'newline' => "\r\n",
                'SMTPTimeout' => 15, // 15 segundos para el timeout
                'wordWrap' => true,
            ];
            
            // Desactivar verificación SSL para cualquier host en modo de prueba
            $config['SMTPOptions'] = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];
            
            try {
                $email = \Config\Services::email($config);
                $email->setFrom($from, 'Email de Prueba');
                $email->setTo($to);
                $email->setSubject('Correo de prueba SMTP');
                $email->setMessage('<h1>Correo de prueba SMTP</h1>
                    <p>Este es un email de prueba para verificar que la configuración SMTP funciona correctamente.</p>
                    <p><strong>Fecha y hora:</strong> ' . date('Y-m-d H:i:s') . '</p>
                    <p><strong>Configuración usada:</strong></p>
                    <ul>
                        <li>Host: ' . $host . '</li>
                        <li>Puerto: ' . $port . '</li>
                        <li>Encriptación: ' . ($encryption ?: 'Ninguna') . '</li>
                        <li>Usuario: ' . $user . '</li>
                    </ul>
                    <p>Si recibiste este email, la configuración SMTP está funcionando correctamente.</p>');
                
                try {
                    $result = $email->send();
                } catch (\Exception $innerEx) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error en el envío de email',
                        'error' => $innerEx->getMessage()
                    ]);
                }
                
                if ($result) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Email enviado correctamente a ' . $to
                    ]);
                } else {
                    $debugInfo = $email->printDebugger(['headers']);
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'No se pudo enviar el email',
                        'error' => $debugInfo
                    ]);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al configurar email para envío',
                    'error' => $e->getMessage()
                ]);
            }
        } catch (\Throwable $e) {
            // Capturamos cualquier tipo de error, incluso los más graves
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Procesa la subida de una imagen
     *
     * @param string $fieldName Nombre del campo de formulario
     * @param string $uploadPath Ruta de subida
     * @param string|null $oldFile Nombre del archivo antiguo
     * @param string $removeFlag Nombre del checkbox para eliminar
     * @param string $prefix Prefijo para el nuevo nombre de archivo
     * @param array &$data Array de datos para actualizar
     * @param array &$errors Array de errores para añadir
     */
    private function processImageUpload($fieldName, $uploadPath, $oldFile, $removeFlag, $prefix, &$data, &$errors)
    {
        $file = $this->request->getFile($fieldName);
        
        // Si se marca para eliminar, eliminar archivo y establecer valor a null
        if ($this->request->getPost($removeFlag)) {
            if ($oldFile && is_file($uploadPath . $oldFile)) {
                @unlink($uploadPath . $oldFile);
            }
            $data[$fieldName] = null;
            return;
        }
        
        // Si hay un archivo válido, procesarlo
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $maxSize = 2 * 1024 * 1024; // 2MB
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/x-icon'];
            
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                $errors[] = lang('App.invalid_image_type') . ': ' . $fieldName;
            } elseif ($file->getSize() > $maxSize) {
                $errors[] = lang('App.image_too_large') . ': ' . $fieldName;
            } else {
                $newName = $prefix . time() . '.' . $file->getExtension();
                $file->move($uploadPath, $newName);
                $data[$fieldName] = $newName;
                
                // Eliminar el archivo anterior si existe y es diferente
                if ($oldFile && is_file($uploadPath . $oldFile) && $oldFile !== $newName) {
                    @unlink($uploadPath . $oldFile);
                }
            }
        } else {
            // Mantener el valor anterior si no hay nuevo archivo
            $data[$fieldName] = $oldFile;
        }
    }

    /**
     * Get SMS templates with variables replaced
     */
    public function getSmsTemplates()
    {
        $model = new SettingsModel();
        $templates = json_decode($model->getSetting('sms_templates', '[]'), true);
        
        // If no templates exist, provide default templates
        if (empty($templates)) {
            $templates = [
                [
                    'name' => 'Reminder - Appointment',
                    'content' => 'Hi {contact_name}, reminder about your {service_name} appointment for {client_name} on {scheduled_date} at {scheduled_time}.'
                ],
                [
                    'name' => 'Status Update - Processing',
                    'content' => 'Hello {contact_name}, your order {order_number} for {client_name} is now being processed. We\'ll keep you updated!'
                ],
                [
                    'name' => 'Status Update - Completed',
                    'content' => 'Great news {contact_name}! Your order {order_number} for {client_name} has been completed successfully.'
                ],
                [
                    'name' => 'Follow Up',
                    'content' => 'Hi {contact_name}, following up on order {order_number} for {client_name}. Please let us know if you have any questions.'
                ]
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'templates' => $templates
        ]);
    }

    /**
     * Get Email templates with variables replaced
     */
    public function getEmailTemplates()
    {
        $model = new SettingsModel();
        $templates = json_decode($model->getSetting('email_templates', '[]'), true);
        
        // If no templates exist, provide default templates
        if (empty($templates)) {
            $templates = [
                [
                    'name' => 'Order Confirmation',
                    'subject' => 'Order Confirmation - {order_number}',
                    'content' => 'Dear {contact_name},

Thank you for your order! We have received your request for {service_name} for {client_name}.

Order Details:
- Order Number: {order_number}
- Service: {service_name}
- Vehicle: {vehicle}
- Stock: {stock}
- Scheduled Date: {scheduled_date}
- Scheduled Time: {scheduled_time}

We will keep you updated on the progress of your order.

Best regards,
Sales Team'
                ],
                [
                    'name' => 'Status Update',
                    'subject' => 'Status Update for Order {order_number}',
                    'content' => 'Hello {contact_name},

We wanted to update you on the status of your order {order_number} for {client_name}.

Current Status: {status}

If you have any questions or concerns, please don\'t hesitate to contact us.

Best regards,
Sales Team'
                ],
                [
                    'name' => 'Appointment Reminder',
                    'subject' => 'Appointment Reminder - {scheduled_date}',
                    'content' => 'Dear {contact_name},

This is a friendly reminder about your upcoming appointment:

- Client: {client_name}
- Service: {service_name}
- Date: {scheduled_date}
- Time: {scheduled_time}
- Vehicle: {vehicle}

Please let us know if you need to reschedule or have any questions.

Best regards,
Sales Team'
                ],
                [
                    'name' => 'Order Completion',
                    'subject' => 'Order Completed - {order_number}',
                    'content' => 'Dear {contact_name},

Great news! Your order {order_number} for {client_name} has been completed successfully.

Service Details:
- Service: {service_name}
- Vehicle: {vehicle}
- Stock: {stock}

Thank you for choosing our services. We look forward to serving you again.

Best regards,
Sales Team'
                ]
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'templates' => $templates
        ]);
    }

    /**
     * Replace variables in template content
     */
    public function replaceTemplateVariables($content, $orderData = [])
    {
        $variables = [
            '{contact_name}' => $orderData['salesperson_name'] ?? 'Contact',
            '{client_name}' => $orderData['client_name'] ?? 'Customer',
            '{order_number}' => $orderData['id'] ?? '000',
            '{order_date}' => isset($orderData['date']) ? date('M j, Y', strtotime($orderData['date'])) : 'Not scheduled',
            '{order_time}' => isset($orderData['time']) ? date('g:i A', strtotime($orderData['time'])) : 'Not scheduled',
            '{vehicle}' => $orderData['vehicle'] ?? 'N/A',
            '{service_name}' => $orderData['service_name'] ?? 'N/A',
            '{company_name}' => $orderData['company_name'] ?? 'Your Company'
        ];

        return str_replace(array_keys($variables), array_values($variables), $content);
    }

    /**
     * Test Lima Links API connection from backend (avoids CORS issues)
     */
    public function testLimaLinks()
    {
        try {
            // Set JSON response headers
            $this->response->setContentType('application/json');
            
            // Get API key from POST data or settings
            $apiKey = $this->request->getPost('api_key');
            if (!$apiKey) {
                $model = new SettingsModel();
                $apiKey = $model->getSetting('lima_api_key');
            }
            
            if (!$apiKey) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Lima Links API key is required'
                ]);
            }
            
            // Test payload
            $testPayload = [
                'url' => 'https://example.com/test-' . time(),
                'type' => 'direct',
                'description' => 'API Test from Settings - ' . date('Y-m-d H:i:s')
            ];
            
            // Add branded domain if provided
            $brandedDomain = $this->request->getPost('branded_domain');
            if (!$brandedDomain) {
                $model = new SettingsModel();
                $brandedDomain = $model->getSetting('lima_branded_domain');
            }
            
            if ($brandedDomain) {
                $testPayload['domain'] = $brandedDomain;
            }
            
            // Initialize cURL
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => LimaLinksHelper::buildApiUrl(),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($testPayload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ],
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT => 'Sales Order System/1.0'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            // Check for cURL errors
            if ($curlError) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Connection error: ' . $curlError
                ]);
            }
            
            // Check HTTP status
            if ($httpCode !== 200) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'HTTP Error ' . $httpCode . ': Invalid API response',
                    'debug' => [
                        'http_code' => $httpCode,
                        'response' => substr($response, 0, 500)
                    ]
                ]);
            }
            
            // Parse response
            $data = json_decode($response, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid JSON response from Lima Links API',
                    'debug' => [
                        'json_error' => json_last_error_msg(),
                        'response' => substr($response, 0, 500)
                    ]
                ]);
            }
            
            // Enhanced debugging - let's see exactly what Lima Links is returning
            $debugInfo = [
                'api_key_length' => strlen($apiKey),
                'api_key_prefix' => substr($apiKey, 0, 8) . '...',
                'payload_sent' => $testPayload,
                'http_code' => $httpCode,
                'raw_response' => $response,
                'parsed_data' => $data
            ];
            
            // Check API response with better error handling
            if (isset($data['error'])) {
                // Lima Links uses error: 0 for success, > 0 for errors
                if ($data['error'] === 0) {
                    // Success case
                    if (isset($data['shorturl'])) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Lima Links connection successful!',
                            'data' => [
                                'short_url' => $data['shorturl'],
                                'original_url' => $testPayload['url'],
                                'domain' => $brandedDomain ?: LimaLinksHelper::getDefaultDomain(),
                                'full_response' => $data
                            ]
                        ]);
                    } else {
                        // Error: success code but no short URL
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Lima Links API returned success but no short URL was created',
                            'debug' => $debugInfo
                        ]);
                    }
                } else {
                    // Error case (error > 0)
                    $errorMessages = [
                        1 => 'Invalid API key',
                        2 => 'Invalid URL',
                        3 => 'Rate limit exceeded',
                        4 => 'Invalid domain',
                        5 => 'URL already exists',
                        6 => 'Account suspended',
                        7 => 'Insufficient quota'
                    ];
                    
                    $errorMessage = $errorMessages[$data['error']] ?? 'Unknown error code: ' . $data['error'];
                    if (isset($data['message'])) {
                        $errorMessage .= ' - ' . $data['message'];
                    }
                    
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Lima Links API Error: ' . $errorMessage,
                        'debug' => $debugInfo
                    ]);
                }
            } else {
                // No error field in response - unexpected format
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unexpected response format from Lima Links API',
                    'debug' => $debugInfo
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'debug' => [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    /**
     * Generate QR Code using Lima Links API
     */
    public function generateQR()
    {
        try {
            $this->response->setContentType('application/json');
            
            // Get parameters
            $orderId = $this->request->getPost('order_id');
            $size = $this->request->getPost('size') ?: '300';
            $format = $this->request->getPost('format') ?: 'png';
            
            if (!$orderId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Order ID is required'
                ]);
            }
            
            // Get API configuration
            $model = new SettingsModel();
            $apiKey = $model->getSetting('lima_api_key');
            $brandedDomain = $model->getSetting('lima_branded_domain');
            
            if (!$apiKey) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Lima Links API key not configured'
                ]);
            }
            
            // Create order URL
            $orderUrl = base_url("sales_orders/view/{$orderId}");
            
            // First attempt: Try with unique timestamp-based alias
            $timestamp = time();
            $shortUrlPayload = [
                'url' => $orderUrl,
                'type' => 'direct',
                'description' => "QR Code for Sales Order SAL-" . str_pad($orderId, 5, '0', STR_PAD_LEFT),
                'custom' => 'qr-order-' . $orderId . '-' . date('Ymd') . '-' . $timestamp
            ];
            
            if ($brandedDomain) {
                $shortUrlPayload['domain'] = $brandedDomain;
            }
            
            // Attempt to create short URL
            $urlData = $this->createShortUrl($apiKey, $shortUrlPayload);
            
            // If first attempt fails due to alias conflict, try without custom alias
            if (!$urlData['success'] && strpos($urlData['error'], 'alias is taken') !== false) {
                log_message('info', 'QR Generation: Custom alias taken, trying without alias');
                
                // Remove custom alias and try again
                unset($shortUrlPayload['custom']);
                $urlData = $this->createShortUrl($apiKey, $shortUrlPayload);
            }
            
            // If still failing, try with completely random alias
            if (!$urlData['success'] && strpos($urlData['error'], 'alias is taken') !== false) {
                log_message('info', 'QR Generation: Trying with random alias');
                
                // Generate random alias
                $shortUrlPayload['custom'] = 'qr-' . $orderId . '-' . uniqid();
                $urlData = $this->createShortUrl($apiKey, $shortUrlPayload);
            }
            
            if (!$urlData['success']) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create short URL: ' . $urlData['error']
                ]);
            }
            
            $shortUrl = $urlData['data']['shorturl'];
            $linkId = $urlData['data']['id'] ?? null;
            
            // Generate QR code URL
            $qrUrl = $this->generateQRUrl($apiKey, $linkId, $shortUrl, $size, $format);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'short_url' => $shortUrl,
                    'qr_url' => $qrUrl,
                    'qr_direct_url' => $qrUrl,
                    'order_url' => $orderUrl,
                    'size' => $size,
                    'format' => $format,
                    'link_id' => $linkId
                ],
                'message' => 'QR Code generated successfully'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'QR Generation Exception: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Helper method to create short URL with Lima Links
     */
    private function createShortUrl($apiKey, $payload)
    {
        $ch = curl_init();
                    curl_setopt_array($ch, [
                CURLOPT_URL => LimaLinksHelper::buildApiUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'success' => false,
                'error' => 'Connection error: ' . $curlError
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP error ' . $httpCode
            ];
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Invalid JSON response'
            ];
        }
        
        if (!isset($data['error']) || $data['error'] !== 0) {
            $errorMessage = $data['message'] ?? 'Unknown error';
            
            // Log the specific error for debugging
            log_message('info', 'Lima Links API Error: ' . $errorMessage . ' | Payload: ' . json_encode($payload));
            
            return [
                'success' => false,
                'error' => $errorMessage
            ];
        }
        
        if (!isset($data['shorturl'])) {
            return [
                'success' => false,
                'error' => 'No short URL returned'
            ];
        }
        
        return [
            'success' => true,
            'data' => $data
        ];
    }
    
    /**
     * Helper method to generate QR code URL
     */
    private function generateQRUrl($apiKey, $linkId, $shortUrl, $size, $format)
    {
        // Try to use Lima Links QR endpoint if we have link ID
        if ($linkId) {
            $qrUrl = LimaLinksHelper::buildQrUrl($linkId, $size, $format);
            
            // Verify QR endpoint is accessible
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $qrUrl,
                CURLOPT_NOBODY => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey
                ]
            ]);
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                return $qrUrl;
            }
        }
        
        // Fallback to alternative QR generation API
        try {
            $qrPayload = [
                'url' => $shortUrl,
                'size' => intval($size),
                'format' => $format,
                'margin' => 10,
                'errorCorrection' => 'M'
            ];
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => LimaLinksHelper::buildApiUrl('api/qr/generate'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($qrPayload),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ],
                CURLOPT_TIMEOUT => 15,
            ]);
            
            $qrResponse = curl_exec($ch);
            $qrHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($qrHttpCode === 200) {
                $qrData = json_decode($qrResponse, true);
                if (isset($qrData['qr_url'])) {
                    return $qrData['qr_url'];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'QR generation fallback failed: ' . $e->getMessage());
        }
        
        // Final fallback: use direct QR URL pattern
        return LimaLinksHelper::buildQrUrl($linkId, $size, $format);
    }
} 