<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        @media print {
            .no-print { display: none !important; }
        }
        
        @page {
            margin: 8mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #333;
            margin: 5mm;
            margin-bottom: 15mm;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .header {
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 10px;
            margin-bottom: 15px;
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
            font-size: 20pt;
            font-weight: bold;
            color: #2c5aa0;
            text-align: center;
            margin: 10px 0;
        }
        
        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #2c5aa0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 6px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 9pt;
        }
        
        .info-table .label {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 25%;
            color: #2c5aa0;
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
            margin: 10px 0 5px 0;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f9f9f9;
        }
        
        .qr-code {
            max-width: 90px;
            height: auto;
        }
        
        .footer {
            position: fixed;
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            background-color: white;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-muted { color: #666; }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print</button>
    
    <!-- Header Section -->
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <div style="margin-top: 15px;">
                        <h1 style="margin: 0; font-size: 18pt; color: #2c5aa0;">My Detail Area</h1>
                        <p style="margin: 5px 0; font-size: 10pt;">123 Business Street<br>City, State 12345</p>
                        <p style="margin: 5px 0; font-size: 10pt;">
                            Tel: (555) 123-4567 | Email: info@mydetailarea.com
                        </p>
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top; text-align: right;">
                    <h1 class="order-title">SALES ORDER</h1>
                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                        <table style="width: 100%; font-size: 11pt;">
                            <tr>
                                <td class="text-bold">Order #:</td>
                                <td>SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            </tr>
                            <tr>
                                <td class="text-bold">Date:</td>
                                <td><?= date('F j, Y', strtotime($order['date'])) ?></td>
                            </tr>
                            <tr>
                                <td class="text-bold">Time:</td>
                                <td><?= $order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled' ?></td>
                            </tr>
                            <tr>
                                <td class="text-bold">Status:</td>
                                <td>
                                    <span class="status-badge status-<?= $order['status'] ?>">
                                        <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Customer Information -->
    <div class="section">
        <h2 class="section-title">Customer Information</h2>
        <table class="info-table">
            <tr>
                <td class="label">Client:</td>
                <td colspan="3"><?= $order['client_name'] ?? 'N/A' ?></td>
            </tr>
            <tr>
                <td class="label">Contact:</td>
                <td><?= $order['salesperson_name'] ?? 'N/A' ?></td>
                <td class="label">Phone:</td>
                <td><?= $order['salesperson_phone'] ?? 'N/A' ?></td>
            </tr>
            <?php if (!empty($order['salesperson_email'])): ?>
            <tr>
                <td class="label">Email:</td>
                <td colspan="3"><?= $order['salesperson_email'] ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Vehicle Information -->
    <div class="section">
        <h2 class="section-title">Vehicle Information</h2>
        <table class="info-table">
            <tr>
                <td class="label">Vehicle:</td>
                <td colspan="3"><?= $order['vehicle'] ?? 'N/A' ?></td>
            </tr>
            <?php if (!empty($order['stock']) || !empty($order['vin'])): ?>
            <tr>
                <td class="label">Stock #:</td>
                <td><?= $order['stock'] ?? 'N/A' ?></td>
                <td class="label">VIN:</td>
                <td><?= $order['vin'] ?? 'N/A' ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Services Information -->
    <div class="section">
        <h2 class="section-title">Services Requested</h2>
        <table class="info-table">
            <tr>
                <td class="label">Service:</td>
                <td><?= $order['service_name'] ?? 'N/A' ?></td>
            </tr>
        </table>
    </div>

    <?php if (!empty($order['notes'])): ?>
    <!-- Notes Section -->
    <div class="section">
        <h2 class="section-title">Order Notes</h2>
        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #2c5aa0; border-radius: 0 5px 5px 0;">
            <?= nl2br(htmlspecialchars($order['notes'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($order['instructions'])): ?>
    <!-- Instructions Section -->
    <div class="section">
        <h2 class="section-title">Instructions</h2>
        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #2c5aa0; border-radius: 0 5px 5px 0;">
            <?= nl2br(htmlspecialchars($order['instructions'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- QR Code Section -->
    <?php if (!empty($order['short_url'])): ?>
    <div class="qr-section">
        <h3 style="color: #2c5aa0; margin-bottom: 8px; font-size: 11pt;">Quick Access</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <?php 
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($order['short_url']);
                    ?>
                    <img src="<?= $qrUrl ?>" class="qr-code" alt="QR Code">
                    <p style="font-size: 7pt; color: #666; margin-top: 5px;">
                        Scan for mobile access
                    </p>
                </td>
                <td style="width: 60%; vertical-align: top; padding-left: 15px;">
                    <h4 style="color: #2c5aa0; margin-bottom: 6px; font-size: 10pt;">Mobile Access</h4>
                    <p style="font-size: 8pt; margin-bottom: 6px;">
                        <strong>Shortlink:</strong><br>
                        <span style="background-color: #fff; padding: 4px; border-radius: 2px; font-size: 7pt; border: 1px solid #ddd; display: inline-block; margin-top: 3px; word-break: break-all;">
                            <?= str_replace(['https://', 'http://'], '', $order['short_url']) ?>
                        </span>
                    </p>
                    <p style="font-size: 7pt; color: #666; line-height: 1.2;">
                        Use the QR code or shortlink to access this order on any mobile device.
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p style="margin: 0;">
            Generated on <?= date('F j, Y \a\t g:i A') ?> | My Detail Area - Order Management System<br>
            This document was automatically generated from the MDA platform.
        </p>
    </div>

    <script>
        // Auto print when opened in new window
        if (window.opener) {
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        }
    </script>
</body>
</html>