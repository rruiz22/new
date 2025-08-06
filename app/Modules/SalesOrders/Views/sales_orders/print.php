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
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .order-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        
        .info-row {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        
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
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Print</button>
    
    <div class="header">
        <h1>SALES ORDER</h1>
        <div class="order-number">SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></div>
        <p>Created on <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
    </div>

    <div class="section">
        <div class="section-title">Order Information</div>
        <div class="info-row">
            <span class="info-label">Order ID:</span>
            SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
                                        <?= 
                                $order['status'] == 'in_progress' ? 'In Progress' : 
                                ($order['status'] == 'processing' ? 'Processing' : 
                                ($order['status'] == 'completed' ? 'Completed' : 
                                ($order['status'] == 'cancelled' ? 'Cancelled' : 
                                ($order['status'] == 'pending' ? 'Pending' : ucfirst($order['status'])))))
                            ?>
        </div>
        <div class="info-row">
            <span class="info-label">Date:</span>
            <?= date('l, F j, Y', strtotime($order['date'])) ?>
        </div>
        <div class="info-row">
            <span class="info-label">Time:</span>
            <?= $order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled' ?>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Client Information</div>
        <div class="info-row">
            <span class="info-label">Name:</span>
            <?= $order['client_name'] ?? 'N/A' ?>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <?= $order['client_email'] ?? 'N/A' ?>
        </div>
        <div class="info-row">
            <span class="info-label">Phone:</span>
            <?= $order['client_phone'] ?? 'N/A' ?>
        </div>
        <?php if($order['client_address']): ?>
        <div class="info-row">
            <span class="info-label">Address:</span>
            <?= $order['client_address'] ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Vehicle Information</div>
        <div class="info-row">
            <span class="info-label">Vehicle:</span>
            <?= $order['vehicle'] ?? 'N/A' ?>
        </div>
        <div class="info-row">
            <span class="info-label">Stock:</span>
            <?= $order['stock'] ?? 'N/A' ?>
        </div>
        <div class="info-row">
            <span class="info-label">VIN:</span>
            <?= $order['vin'] ?? 'N/A' ?>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Service Information</div>
        <div class="info-row">
            <span class="info-label">Service:</span>
            <?= $order['service_name'] ?? 'N/A' ?>
        </div>
        <?php if($order['service_description']): ?>
        <div class="info-row">
            <span class="info-label">Description:</span>
            <?= $order['service_description'] ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Assigned Contact</div>
        <div class="info-row">
            <span class="info-label">Name:</span>
            <?= $order['salesperson_name'] ?? 'Not assigned' ?>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <?= $order['salesperson_email'] ?? 'N/A' ?>
        </div>
        <div class="info-row">
            <span class="info-label">Phone:</span>
            <?= $order['salesperson_phone'] ?? 'N/A' ?>
        </div>
    </div>

    <?php if($order['instructions']): ?>
    <div class="section">
        <div class="section-title">Instructions</div>
        <p><?= nl2br(htmlspecialchars($order['instructions'])) ?></p>
    </div>
    <?php endif; ?>

                <?php if(false && $order['notes'] && (session()->get('user_type') === 'staff')): ?>
    <div class="section">
        <div class="section-title">Internal Notes</div>
        <p><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
    </div>
    <?php endif; ?>

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