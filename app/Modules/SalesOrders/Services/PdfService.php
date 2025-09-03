<?php

namespace Modules\SalesOrders\Services;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use App\Models\SettingsModel;
use Exception;

class PdfService
{
    private $mpdf;
    private $settings;
    private $companyInfo;

    public function __construct()
    {
        $this->settings = new SettingsModel();
        $this->companyInfo = $this->getCompanyInfo();
        
        // Initialize mPDF with optimized settings
        $this->mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 25,
            'margin_bottom' => 25,
            'margin_header' => 10,
            'margin_footer' => 10,
            'default_font' => 'Arial',
            'tempDir' => WRITEPATH . 'cache/mpdf'
        ]);
        
        // Set document properties
        $this->mpdf->SetTitle('Sales Order Document');
        $this->mpdf->SetAuthor($this->companyInfo['name']);
        $this->mpdf->SetCreator('MDA System v2.0');
        
        
        // Set default CSS
        $this->setDefaultStyles();
    }

    /**
     * Generate PDF for Sales Order
     */
    public function generateSalesOrderPdf($order, $template = 'invoice', $options = [])
    {
        try {
            log_message('info', 'Starting PDF generation for order ID: ' . ($order['id'] ?? 'unknown'));
            
            // Merge default options
            $options = array_merge([
                'format' => 'A4',
                'orientation' => 'P',
                'watermark' => [
                    'enabled' => false,
                    'text' => '',
                    'opacity' => 0.1
                ],
                'sections' => [
                    'header' => true,
                    'customerInfo' => true,
                    'vehicleInfo' => true,
                    'services' => true,
                    'notes' => true,
                    'terms' => false,
                    'qrCode' => true
                ],
                'styling' => [
                    'colorMode' => 'color',
                    'showLogo' => true,
                    'fontSize' => 'medium'
                ]
            ], $options);

            log_message('info', 'PDF options merged successfully');

            // Set watermark if enabled
            if ($options['watermark']['enabled']) {
                log_message('info', 'Setting watermark: ' . $options['watermark']['text']);
                $this->setWatermark($options['watermark']['text'], $options['watermark']['opacity']);
            }

            // Generate HTML content based on template
            log_message('info', 'Generating HTML content for template: ' . $template);
            $html = $this->generateHtmlContent($order, $template, $options);
            log_message('info', 'HTML content generated, length: ' . strlen($html));
            
            // Add footer directly to HTML content
            $footerContent = '<div style="position: fixed; bottom: 5mm; left: 15mm; right: 15mm; text-align: center; font-size: 7pt; color: #999; border-top: 1px solid #ddd; padding-top: 5px; background: white;">
                Generated on ' . date('F j, Y g:i A') . ' | My Detail Area - Order Management System<br>
                This document was automatically generated from the MDA platform.
            </div>';
            
            // Insert footer before closing body tag
            $html = str_replace('</body>', $footerContent . '</body>', $html);
            
            log_message('info', 'Footer added directly to HTML content');
            
            // Write HTML to PDF
            log_message('info', 'Writing HTML to PDF with mPDF');
            $this->mpdf->WriteHTML($html);
            log_message('info', 'PDF generation completed successfully');
            
            return [
                'success' => true,
                'pdf' => $this->mpdf,
                'filename' => $this->generateFilename($order)
            ];
            
        } catch (Exception $e) {
            log_message('error', 'PDF Generation Error: ' . $e->getMessage());
            log_message('error', 'PDF Generation Stack Trace: ' . $e->getTraceAsString());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Download PDF as file
     */
    public function downloadPdf($order, $template = 'invoice', $options = [])
    {
        try {
            log_message('info', 'downloadPdf called for order: ' . ($order['id'] ?? 'unknown'));
            
            $result = $this->generateSalesOrderPdf($order, $template, $options);
            
            if ($result['success']) {
                $filename = $result['filename'];
                log_message('info', 'Attempting to output PDF with filename: ' . $filename);
                
                // Set appropriate headers for PDF download
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="' . $filename . '"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
                
                return $result['pdf']->Output($filename, Destination::DOWNLOAD);
            } else {
                log_message('error', 'PDF generation failed: ' . $result['error']);
                throw new Exception($result['error']);
            }
        } catch (Exception $e) {
            log_message('error', 'downloadPdf error: ' . $e->getMessage());
            log_message('error', 'downloadPdf stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Get PDF as string
     */
    public function getPdfString($order, $template = 'invoice', $options = [])
    {
        $result = $this->generateSalesOrderPdf($order, $template, $options);
        
        if ($result['success']) {
            return $result['pdf']->Output('', Destination::STRING_RETURN);
        } else {
            throw new Exception($result['error']);
        }
    }

    /**
     * Save PDF to file
     */
    public function savePdf($order, $path, $template = 'invoice', $options = [])
    {
        $result = $this->generateSalesOrderPdf($order, $template, $options);
        
        if ($result['success']) {
            return $result['pdf']->Output($path, Destination::FILE);
        } else {
            throw new Exception($result['error']);
        }
    }

    /**
     * Generate HTML content for PDF
     */
    private function generateHtmlContent($order, $template, $options)
    {
        $templatePath = APPPATH . "Modules/SalesOrders/Views/templates/pdf/{$template}.php";
        
        if (!file_exists($templatePath)) {
            $template = 'invoice'; // Fallback to default template
            $templatePath = APPPATH . "Modules/SalesOrders/Views/templates/pdf/{$template}.php";
        }

        // Prepare data for template - simplified to avoid database issues
        $data = [
            'order' => $order,
            'company' => $this->companyInfo,
            'options' => $options,
            'qr_data' => $this->getQrData($order),
            'services' => [], // Simplified - don't query additional services for now
            'activities' => [] // Simplified - don't query activities for now
        ];
        
        // Log order data for debugging
        log_message('info', 'PDF template data prepared with order ID: ' . $order['id']);
        log_message('info', 'Order keys available: ' . implode(', ', array_keys($order)));
        log_message('info', 'Key order fields: ' . json_encode([
            'client_name' => $order['client_name'] ?? 'missing',
            'client_email' => $order['client_email'] ?? 'missing',
            'client_phone' => $order['client_phone'] ?? 'missing',
            'salesperson_name' => $order['salesperson_name'] ?? 'missing',
            'salesperson_email' => $order['salesperson_email'] ?? 'missing',
            'salesperson_phone' => $order['salesperson_phone'] ?? 'missing',
            'vehicle' => $order['vehicle'] ?? 'missing',
            'stock' => $order['stock'] ?? 'missing',
            'vin' => $order['vin'] ?? 'missing',
            'service_name' => $order['service_name'] ?? 'missing',
            'status' => $order['status'] ?? 'missing'
        ]));

        // Start output buffering
        ob_start();
        extract($data);
        include $templatePath;
        $html = ob_get_clean();

        return $html;
    }

    /**
     * Set default CSS styles
     */
    private function setDefaultStyles()
    {
        $css = '
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .logo {
            max-width: 180px;
            height: auto;
        }
        
        .company-info {
            text-align: right;
            margin-top: 10px;
        }
        
        .order-title {
            font-size: 24pt;
            font-weight: bold;
            color: #2c5aa0;
            text-align: center;
            margin: 20px 0;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #2c5aa0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .info-table .label {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 25%;
        }
        
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .services-table th {
            background-color: #2c5aa0;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        
        .services-table td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        
        .services-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #ffc107; color: #000; }
        .status-processing { background-color: #17a2b8; color: white; }
        .status-in_progress { background-color: #007bff; color: white; }
        .status-completed { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code {
            max-width: 150px;
            height: auto;
        }
        
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 9pt;
            color: #666;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-muted { color: #666; }
        
        @page {
            margin: 15mm 15mm 25mm 15mm;
            @top-center {
                content: "' . $this->companyInfo['name'] . '";
                font-size: 10pt;
                color: #666;
            }
        }';
        
        $this->mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    }

    /**
     * Set watermark
     */
    private function setWatermark($text, $opacity = 0.1)
    {
        $this->mpdf->SetWatermarkText($text, $opacity);
        $this->mpdf->showWatermarkText = true;
    }

    /**
     * Get company information
     */
    private function getCompanyInfo()
    {
        try {
            return [
                'name' => $this->settings->getSetting('company_name') ?: 'My Detail Area',
                'address' => $this->settings->getSetting('company_address') ?: '123 Business Street\nCity, State 12345',
                'phone' => $this->settings->getSetting('company_phone') ?: '(555) 123-4567',
                'email' => $this->settings->getSetting('company_email') ?: 'info@mydetailarea.com',
                'website' => $this->settings->getSetting('company_website') ?: 'www.mydetailarea.com',
                'logo' => $this->settings->getSetting('app_logo') ?: null
            ];
        } catch (Exception $e) {
            log_message('warning', 'Failed to get company settings, using defaults: ' . $e->getMessage());
            return [
                'name' => 'My Detail Area',
                'address' => '123 Business Street\nCity, State 12345',
                'phone' => '(555) 123-4567',
                'email' => 'info@mydetailarea.com',
                'website' => 'www.mydetailarea.com',
                'logo' => null
            ];
        }
    }

    /**
     * Get QR data for order
     */
    private function getQrData($order)
    {
        if (!empty($order['short_url'])) {
            return [
                'short_url' => $order['short_url'],
                'qr_url' => "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($order['short_url']),
                'generated_at' => $order['qr_generated_at'] ?? date('Y-m-d H:i:s')
            ];
        }
        
        // If no short URL, create QR for order view URL
        if (!empty($order['id'])) {
            $orderUrl = base_url("sales-orders/view/{$order['id']}");
            return [
                'short_url' => $orderUrl,
                'qr_url' => "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($orderUrl),
                'generated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return null;
    }

    /**
     * Get order services
     */
    private function getOrderServices($orderId)
    {
        try {
            $db = \Config\Database::connect();
            
            // Log the query attempt
            log_message('info', 'Getting services for order ID: ' . $orderId);
            
            // Instead of looking for order_id, get all available services since this table seems to be service definitions
            // The order already has service_id that links to this table
            $result = $db->table('sales_orders_services')
                      ->where('deleted', 0)
                      ->where('show_in_orders', 1)
                      ->get();
            
            if (!$result) {
                log_message('warning', 'Query failed for sales_orders_services');
                return [];
            }
            
            $services = $result->getResultArray();
            log_message('info', 'Found ' . count($services) . ' services');
            return $services;
            
        } catch (Exception $e) {
            log_message('error', 'Failed to get order services: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get order activities (latest 10)
     */
    private function getOrderActivities($orderId)
    {
        try {
            $db = \Config\Database::connect();
            log_message('info', 'Getting activities for order ID: ' . $orderId);
            
            $result = $db->table('sales_orders_activities soa')
                      ->select('soa.*, CONCAT(COALESCE(u.first_name, ""), " ", COALESCE(u.last_name, "")) as user_name')
                      ->join('users u', 'u.id = soa.user_id', 'left')
                      ->where('soa.order_id', $orderId)
                      ->orderBy('soa.created_at', 'DESC')
                      ->limit(10)
                      ->get();
            
            if (!$result) {
                log_message('warning', 'Query failed for sales_orders_activities');
                return [];
            }
            
            $activities = $result->getResultArray();
            log_message('info', 'Found ' . count($activities) . ' activities');
            return $activities;
            
        } catch (Exception $e) {
            log_message('error', 'Failed to get order activities: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate filename for PDF
     */
    private function generateFilename($order)
    {
        $orderNumber = 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT);
        $date = date('Y-m-d');
        return "{$orderNumber}_{$date}.pdf";
    }

    /**
     * Get available templates
     */
    public function getAvailableTemplates()
    {
        return [
            'invoice' => 'Professional Invoice Style',
            'detailed' => 'Detailed Report Style', 
            'compact' => 'Compact Summary Style'
        ];
    }

    /**
     * Validate template exists
     */
    public function templateExists($template)
    {
        $templatePath = APPPATH . "Modules/SalesOrders/Views/templates/pdf/{$template}.php";
        return file_exists($templatePath);
    }
}