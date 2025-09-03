<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Order <?= 'SAL-' . str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></title>
</head>
<body>
    
    <!-- Header Section -->
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <?php if ($options['styling']['showLogo'] && !empty($company['logo'])): ?>
                        <img src="<?= FCPATH . 'assets/images/logos/' . $company['logo'] ?>" class="logo" alt="<?= $company['name'] ?>">
                    <?php endif; ?>
                    <div style="margin-top: 15px;">
                        <h1 style="margin: 0; font-size: 18pt; color: #2c5aa0;"><?= $company['name'] ?></h1>
                        <?php if (!empty($company['address'])): ?>
                            <p style="margin: 5px 0; font-size: 10pt;"><?= nl2br(esc($company['address'])) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($company['phone']) || !empty($company['email'])): ?>
                            <p style="margin: 5px 0; font-size: 10pt;">
                                <?php if (!empty($company['phone'])): ?>Tel: <?= esc($company['phone']) ?><?php endif; ?>
                                <?php if (!empty($company['phone']) && !empty($company['email'])): ?> | <?php endif; ?>
                                <?php if (!empty($company['email'])): ?>Email: <?= esc($company['email']) ?><?php endif; ?>
                            </p>
                        <?php endif; ?>
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

    <?php if ($options['sections']['customerInfo']): ?>
    <!-- Customer Information -->
    <div class="section">
        <h2 class="section-title">Customer Information</h2>
        <table class="info-table">
            <tr>
                <td class="label">Client:</td>
                <td colspan="3"><?= esc($order['client_name'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td class="label">Contact:</td>
                <td><?= esc($order['salesperson_name'] ?? 'N/A') ?></td>
                <td class="label">Phone:</td>
                <td><?= esc($order['salesperson_phone'] ?? 'N/A') ?></td>
            </tr>
            <?php if (!empty($order['salesperson_email'])): ?>
            <tr>
                <td class="label">Email:</td>
                <td colspan="3"><?= esc($order['salesperson_email']) ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <?php endif; ?>

    <?php if ($options['sections']['vehicleInfo']): ?>
    <!-- Vehicle Information -->
    <div class="section">
        <h2 class="section-title">Vehicle Information</h2>
        <table class="info-table">
            <tr>
                <td class="label">Vehicle:</td>
                <td colspan="3"><?= esc($order['vehicle'] ?? 'N/A') ?></td>
            </tr>
            <?php if (!empty($order['stock']) || !empty($order['vin'])): ?>
            <tr>
                <td class="label">Stock #:</td>
                <td><?= esc($order['stock'] ?? 'N/A') ?></td>
                <td class="label">VIN:</td>
                <td><?= esc($order['vin'] ?? 'N/A') ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <?php endif; ?>

    <?php if ($options['sections']['services'] && !empty($services)): ?>
    <!-- Services Information -->
    <div class="section">
        <h2 class="section-title">Services Requested</h2>
        <table class="services-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Service Code</th>
                    <th style="width: 45%;">Service Name</th>
                    <th style="width: 40%;">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                <tr>
                    <td><?= esc($service['service_code'] ?? 'N/A') ?></td>
                    <td><?= esc($service['service_name'] ?? 'N/A') ?></td>
                    <td><?= esc($service['description'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if ($options['sections']['notes'] && !empty($order['notes'])): ?>
    <!-- Notes Section -->
    <div class="section">
        <h2 class="section-title">Order Notes</h2>
        <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #2c5aa0; border-radius: 0 5px 5px 0;">
            <?= nl2br(esc($order['notes'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($options['sections']['notes'] && !empty($order['internal_notes'])): ?>
    <!-- Internal Notes Section -->
    <div class="section">
        <h2 class="section-title">Internal Notes</h2>
        <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; border-radius: 0 5px 5px 0;">
            <?= nl2br(esc($order['internal_notes'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- QR Code Section -->
    <?php if ($options['sections']['qrCode'] && $qr_data): ?>
    <div class="qr-section">
        <h3 style="color: #2c5aa0; margin-bottom: 15px;">Quick Access</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <img src="<?= $qr_data['qr_url'] ?>" class="qr-code" alt="QR Code">
                    <p style="font-size: 9pt; color: #666; margin-top: 10px;">
                        Scan for mobile access
                    </p>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                    <h4 style="color: #2c5aa0; margin-bottom: 10px;">Mobile Access</h4>
                    <p style="font-size: 10pt; margin-bottom: 5px;">
                        <strong>Shortlink:</strong><br>
                        <code style="background-color: #f8f9fa; padding: 5px; border-radius: 3px; font-size: 9pt;">
                            <?= str_replace(['https://', 'http://'], '', $qr_data['short_url']) ?>
                        </code>
                    </p>
                    <p style="font-size: 9pt; color: #666;">
                        Use the QR code or shortlink to access this order on any mobile device.
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Activities Section (if showing recent activities) -->
    <?php if (!empty($activities)): ?>
    <div class="section">
        <h2 class="section-title">Recent Activities</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 9pt;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Date</th>
                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">User</th>
                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                <tr>
                    <td style="padding: 6px 8px; border: 1px solid #ddd;">
                        <?= date('M j, Y g:i A', strtotime($activity['created_at'])) ?>
                    </td>
                    <td style="padding: 6px 8px; border: 1px solid #ddd;">
                        <?= esc($activity['user_name'] ?? 'System') ?>
                    </td>
                    <td style="padding: 6px 8px; border: 1px solid #ddd;">
                        <?= esc($activity['description'] ?? $activity['title']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>


</body>
</html>