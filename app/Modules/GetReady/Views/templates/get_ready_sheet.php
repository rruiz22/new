<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('GetReady.get_ready_sheet') ?> - <?= $vehicle['vin'] ?></title>
    <style>
        /* Print-optimized CSS */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #2c3e50;
            margin: 0;
            padding: 15px;
            background: white;
        }

        .header {
            border-bottom: 3px solid #1e40af;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo-section {
            flex: 1;
        }

        .company-logo {
            max-width: 200px;
            height: auto;
        }

        .vehicle-info {
            flex: 2;
            margin: 0 20px;
        }

        .qr-section {
            flex: 1;
            text-align: right;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            border: 1px solid #ddd;
            display: inline-block;
            background: #f8f9fa;
            position: relative;
        }

        .document-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e40af;
            margin: 0 0 10px 0;
            text-align: center;
        }

        .vehicle-details {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            margin-bottom: 8px;
            padding: 2px 0;
        }

        .detail-label {
            font-weight: 600;
            width: 120px;
            color: #374151;
        }

        .detail-value {
            flex: 1;
            color: #111827;
        }

        .time-tracking {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .time-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 10px;
        }

        .time-item {
            text-align: center;
        }

        .time-label {
            font-size: 10px;
            color: #6b7280;
            display: block;
            margin-bottom: 4px;
        }

        .time-value {
            font-size: 14px;
            font-weight: 600;
            color: #1e40af;
        }

        .checklist {
            margin-top: 25px;
        }

        .checklist-category {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .category-header {
            background: #1e40af;
            color: white;
            padding: 8px 12px;
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .checklist-items {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            padding: 0 10px;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px dotted #e5e7eb;
        }

        .checkbox {
            width: 14px;
            height: 14px;
            border: 1px solid #9ca3af;
            margin-right: 8px;
            flex-shrink: 0;
            background: white;
        }

        .item-label {
            font-size: 10px;
            flex: 1;
        }

        .signature-section {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .signature-box {
            border: 1px solid #d1d5db;
            padding: 15px;
            text-align: center;
            min-height: 80px;
            position: relative;
        }

        .signature-label {
            position: absolute;
            top: -8px;
            left: 10px;
            background: white;
            padding: 0 5px;
            font-size: 10px;
            color: #6b7280;
        }

        .signature-line {
            border-bottom: 1px solid #9ca3af;
            margin-top: 40px;
            margin-bottom: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .step-progress {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px;
            background: #f3f4f6;
            border-radius: 6px;
        }

        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '→';
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .step-active {
            color: #1e40af;
            font-weight: 600;
        }

        .step-completed {
            color: #059669;
            font-weight: 500;
        }

        .notes-section {
            margin-top: 20px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            min-height: 100px;
            padding: 10px;
        }

        .notes-header {
            font-weight: 600;
            margin-bottom: 10px;
            color: #374151;
        }

        @media print {
            .checklist-items {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .time-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <div class="logo-section">
            <?php if (isset($client_logo) && $client_logo): ?>
                <img src="<?= $client_logo ?>" alt="Logo" class="company-logo">
            <?php else: ?>
                <div style="font-size: 18px; font-weight: 700; color: #1e40af;">
                    <?= $client_name ?? 'My Detail Area' ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="vehicle-info">
            <h1 class="document-title"><?= lang('GetReady.get_ready_sheet') ?></h1>
            <div style="text-align: center; font-size: 12px; color: #6b7280;">
                <?= lang('GetReady.certificate_number') ?>: GR-<?= str_pad($vehicle['id'], 6, '0', STR_PAD_LEFT) ?>-<?= date('Y') ?>
            </div>
        </div>

        <div class="qr-section">
            <div class="qr-code">
                <?php if (isset($qr_code_url)): ?>
                    <img src="<?= $qr_code_url ?>" alt="QR Code" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 10px; color: #9ca3af;">
                        QR Code
                    </div>
                <?php endif; ?>
            </div>
            <div style="margin-top: 5px; font-size: 9px; color: #6b7280;">
                <?= lang('GetReady.scan_qr_nfc') ?>
            </div>
        </div>
    </div>

    <!-- Vehicle Details -->
    <div class="vehicle-details">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
            <div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.vin_number') ?>:</span>
                    <span class="detail-value"><strong><?= $vehicle['vin'] ?></strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.stock_number') ?>:</span>
                    <span class="detail-value"><?= $vehicle['stock_number'] ?? 'N/A' ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.year') ?>:</span>
                    <span class="detail-value"><?= $vehicle['year'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.make') ?>:</span>
                    <span class="detail-value"><?= $vehicle['make'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.model') ?>:</span>
                    <span class="detail-value"><?= $vehicle['model'] ?></span>
                </div>
            </div>
            <div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.color') ?>:</span>
                    <span class="detail-value"><?= $vehicle['color'] ?? 'N/A' ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.mileage') ?>:</span>
                    <span class="detail-value"><?= $vehicle['mileage'] ? number_format($vehicle['mileage']) . ' km' : 'N/A' ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.client') ?>:</span>
                    <span class="detail-value"><?= $vehicle['client'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.priority') ?>:</span>
                    <span class="detail-value">
                        <span style="text-transform: uppercase; font-weight: 600; 
                            <?php 
                            switch($vehicle['priority']) {
                                case 'urgent': echo 'color: #dc2626;'; break;
                                case 'high': echo 'color: #ea580c;'; break;
                                case 'normal': echo 'color: #059669;'; break;
                                default: echo 'color: #6b7280;';
                            }
                            ?>">
                            <?= lang('GetReady.' . ($vehicle['priority'] ?? 'normal')) ?>
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><?= lang('GetReady.created_at') ?>:</span>
                    <span class="detail-value"><?= date('Y-m-d H:i', strtotime($vehicle['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Step Progress -->
    <div class="step-progress">
        <?php foreach ($steps as $step): ?>
            <div class="step-item <?= $step['slug'] === $vehicle['current_step'] ? 'step-active' : ($step['order'] < $current_step_order ? 'step-completed' : '') ?>">
                <?= $step['name'] ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Time Tracking -->
    <div class="time-tracking">
        <h3 style="margin: 0 0 10px 0; color: #1e40af; font-size: 14px;"><?= lang('GetReady.time_in_step') ?></h3>
        <div class="time-grid">
            <div class="time-item">
                <span class="time-label"><?= lang('GetReady.time_in_step') ?></span>
                <div class="time-value"><?= $time_tracking['current_step_time'] ?></div>
            </div>
            <div class="time-item">
                <span class="time-label"><?= lang('GetReady.total_elapsed') ?></span>
                <div class="time-value"><?= $time_tracking['total_time'] ?></div>
            </div>
            <div class="time-item">
                <span class="time-label"><?= lang('GetReady.assigned_to') ?></span>
                <div class="time-value" style="font-size: 11px;"><?= $vehicle['assigned_technician'] ?? 'N/A' ?></div>
            </div>
            <div class="time-item">
                <span class="time-label"><?= lang('GetReady.location') ?></span>
                <div class="time-value" style="font-size: 11px;"><?= $vehicle['location'] ?? 'N/A' ?></div>
            </div>
            <div class="time-item">
                <span class="time-label"><?= lang('GetReady.status') ?></span>
                <div class="time-value" style="font-size: 11px;"><?= lang('GetReady.' . $vehicle['status']) ?></div>
            </div>
            <div class="time-item">
                <span class="time-label"><?= lang('GetReady.generation_date') ?></span>
                <div class="time-value" style="font-size: 10px;"><?= date('Y-m-d H:i') ?></div>
            </div>
        </div>
    </div>

    <!-- 172-Point Inspection Checklist -->
    <div class="checklist">
        <h2 style="text-align: center; color: #1e40af; margin-bottom: 20px; font-size: 16px;">
            172-Point Vehicle Inspection Checklist
        </h2>

        <!-- Exterior Inspection -->
        <div class="checklist-category">
            <div class="category-header"><?= lang('GetReady.exterior_inspection') ?> (35 Points)</div>
            <div class="checklist-items">
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Front bumper condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rear bumper condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Hood condition & alignment</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Trunk/tailgate condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Front doors (both)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rear doors (both)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Door handles & locks</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Window regulators</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Side mirrors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Windshield condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rear window</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Sunroof/moonroof</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Paint condition overall</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Scratches & dents</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rust inspection</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Chrome/trim condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Headlight housings</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Taillight housings</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Front grille</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">License plate area</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wheel wells</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Undercarriage visible areas</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Exhaust system visible</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Fuel door & cap</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Body panel alignment</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Door gap consistency</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Seals & weatherstripping</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Antenna condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Roof rails/rack points</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">External accessories</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Exterior detailing quality</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wax/sealant application</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Water spot removal</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Trim restoration</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Final polish inspection</span></div>
            </div>
        </div>

        <!-- Wheels & Tires -->
        <div class="checklist-category">
            <div class="category-header">Wheels & Tires (20 Points)</div>
            <div class="checklist-items">
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Front left tire condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Front right tire condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rear left tire condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rear right tire condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Spare tire condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Tire pressure (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Tire tread depth</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Tire wear patterns</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wheel condition (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wheel alignment visual</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Hubcaps/center caps</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Valve stems</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">TPMS sensors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Lug nuts/bolts</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wheel well liners</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Brake dust cleaning</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wheel detailing</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Tire shine application</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Jack & tools present</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wheel lock key present</span></div>
            </div>
        </div>

        <!-- Interior Inspection -->
        <div class="checklist-category">
            <div class="category-header"><?= lang('GetReady.interior_inspection') ?> (45 Points)</div>
            <div class="checklist-items">
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Driver seat condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Passenger seat condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rear seats condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Seat adjustments (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Seat belts (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Dashboard condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Instrument cluster</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Center console</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Door panels (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Window switches</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Door locks/handles</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Headliner condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Floor mats/carpets</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Pedals & foot area</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Steering wheel</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Gear shifter</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Handbrake/parking brake</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Cup holders</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Storage compartments</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Glove box</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Interior lighting</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Reading lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Vanity mirrors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Sun visors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Interior trim pieces</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Air vents & controls</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Rearview mirror</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">12V outlets/USB ports</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Armrests</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Window tinting</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Interior odors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Deep cleaning completed</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Leather treatment</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Fabric protection</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Dashboard protection</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Interior detailing quality</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Vacuuming thoroughness</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Steam cleaning (if needed)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Air freshener application</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Final interior inspection</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">All personal items removed</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Owner's manual present</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Keys (all) present</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Remote controls present</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Service records present</span></div>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- Mechanical Systems -->
        <div class="checklist-category">
            <div class="category-header"><?= lang('GetReady.mechanical_systems') ?> (30 Points)</div>
            <div class="checklist-items">
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Engine visual inspection</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Engine oil level/condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Coolant level/condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Brake fluid level</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Power steering fluid</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Transmission fluid</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Windshield washer fluid</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Battery condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Battery terminals</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Belts & hoses visible</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Air filter condition</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Cabin air filter</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Spark plugs (if accessible)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Engine starts properly</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Idle quality</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">No unusual noises</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">No visible leaks</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Exhaust smoke check</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Transmission operation</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Clutch operation (manual)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Brakes feel/response</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Steering response</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Suspension feel</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Alignment check (visual)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Emissions compliance</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Engine bay cleaning</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Fluid top-offs completed</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Maintenance stickers updated</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Service indicators reset</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Test drive completed</span></div>
            </div>
        </div>

        <!-- Electrical Systems -->
        <div class="checklist-category">
            <div class="category-header"><?= lang('GetReady.electrical_systems') ?> (25 Points)</div>
            <div class="checklist-items">
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Headlights (low beam)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Headlights (high beam)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Tail lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Brake lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Turn signals (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Hazard lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Reverse lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Interior lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Dashboard lights</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Warning lights function</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Horn operation</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Wipers (all speeds)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Washer system</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Radio/audio system</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Speakers (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">USB/auxiliary ports</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Bluetooth connectivity</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Navigation system</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Backup camera</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Parking sensors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Power windows (all)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Power locks</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Power mirrors</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Power seats</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Charging ports functional</span></div>
            </div>
        </div>

        <!-- Safety Systems -->
        <div class="checklist-category">
            <div class="category-header"><?= lang('GetReady.safety_systems') ?> (17 Points)</div>
            <div class="checklist-items">
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Airbag system (no warnings)</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">ABS system function</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Traction control</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Stability control</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Emergency brake assist</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Collision detection</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Lane departure warning</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Blind spot monitoring</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Adaptive cruise control</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">TPMS system</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Fire extinguisher present</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">First aid kit present</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Warning triangles</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Emergency tools</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Child safety locks</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">All safety recalls completed</span></div>
                <div class="checklist-item"><div class="checkbox"></div><span class="item-label">Safety inspection current</span></div>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="notes-section">
        <div class="notes-header"><?= lang('GetReady.notes') ?> / Comments:</div>
        <?php if (!empty($vehicle['notes'])): ?>
            <div style="white-space: pre-line; padding: 5px 0; font-size: 10px;">
                <?= nl2br(htmlspecialchars($vehicle['notes'])) ?>
            </div>
        <?php endif; ?>
        <div style="border-bottom: 1px dotted #ccc; margin: 8px 0; height: 15px;"></div>
        <div style="border-bottom: 1px dotted #ccc; margin: 8px 0; height: 15px;"></div>
        <div style="border-bottom: 1px dotted #ccc; margin: 8px 0; height: 15px;"></div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <span class="signature-label">Technician</span>
            <div class="signature-line"></div>
            <div style="font-size: 9px; margin-top: 3px;">Name & Date</div>
        </div>
        <div class="signature-box">
            <span class="signature-label">Quality Control</span>
            <div class="signature-line"></div>
            <div style="font-size: 9px; margin-top: 3px;">Name & Date</div>
        </div>
        <div class="signature-box">
            <span class="signature-label">Manager Approval</span>
            <div class="signature-line"></div>
            <div style="font-size: 9px; margin-top: 3px;">Name & Date</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="margin-bottom: 5px;">
            <strong><?= lang('GetReady.generated_by_mda') ?></strong> - My Detail Area Professional System
        </div>
        <div>
            Generated: <?= date('Y-m-d H:i:s') ?> | Document ID: GR-<?= str_pad($vehicle['id'], 6, '0', STR_PAD_LEFT) ?>-<?= date('Ymd') ?>
        </div>
        <div style="margin-top: 5px; font-size: 9px;">
            This document contains <?= $checklist_total ?? '172' ?> inspection points for comprehensive vehicle preparation
        </div>
    </div>

    <!-- Print Button for Screen View -->
    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        <button onclick="window.print()" 
                style="background: #1e40af; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-size: 14px; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            📄 Print Get Ready Sheet
        </button>
    </div>

    <script>
        // Auto-print when opened directly
        if (window.location.search.includes('auto_print=1')) {
            window.onload = function() {
                setTimeout(() => window.print(), 500);
            };
        }

        // Calculate completion percentage
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checkbox');
            let checked = 0;
            
            checkboxes.forEach(box => {
                if (box.style.backgroundColor === 'green' || box.classList.contains('checked')) {
                    checked++;
                }
            });
            
            const percentage = Math.round((checked / checkboxes.length) * 100);
            console.log(`Inspection Progress: ${checked}/${checkboxes.length} (${percentage}%)`);
        });
    </script>
</body>
</html>