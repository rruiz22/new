<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?= lang('App.batch_nfc_tracker') ?></title>
    
    <!-- Bootstrap CSS for mobile -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Toastr CSS -->
    <link href="<?= base_url('assets/libs/toastify-js/src/toastify.css') ?>" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .main-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        
        .tracker-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .batch-header {
            background: #ffffff;
            color: #495057;
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
        }
        
        .batch-header h1 {
            color: #405189;
            margin-bottom: 0.5rem;
        }
        
        .batch-header small {
            color: #6c757d;
        }
        
        .scan-input {
            background: #ffffff;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .scan-input input {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            text-align: center;
            font-family: 'Courier New', monospace;
            width: 100%;
        }
        
        .scan-input input:focus {
            outline: none;
            border-color: #405189;
        }
        
        .scan-history {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .vehicle-entry {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .vehicle-entry.success {
            background: #d4edda;
            border-color: #c3e6cb;
        }
        
        .vehicle-entry.error {
            background: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .stats-bar {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        
        .stat-item {
            flex: 1;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #405189;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
        }
        

        
        /* Scan History Header Buttons */
        .scan-header-buttons .btn {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        @media (max-width: 768px) {
            .scan-header-buttons .btn {
                font-size: 0.8rem;
                padding: 5px 10px;
            }
            
            .scan-header-buttons .btn i {
                margin-right: 0.3rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px;
            }
            
            .scan-header-buttons {
                align-self: stretch;
            }
            
            .scan-header-buttons .btn-group {
                width: 100%;
            }
            
            .scan-header-buttons .btn {
                flex: 1;
            }
        }
        
        @media (max-width: 576px) {
            .batch-header {
                padding: 15px !important;
            }
            
            .batch-header h1 {
                font-size: 1.1rem !important;
            }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(64, 81, 137, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(64, 81, 137, 0); }
            100% { box-shadow: 0 0 0 0 rgba(64, 81, 137, 0); }
        }
        
        /* Progress Bar Styles */
        #progressContainer {
            transition: all 0.3s ease;
        }
        
        .progress {
            background-color: #e9ecef;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            border: 1px solid #dee2e6;
        }
        
        .progress-bar {
            transition: width 0.4s ease;
            font-size: 0.75rem;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
            background-size: 1rem 1rem;
        }
        
        .progress-bar.bg-success {
            background-color: #28a745 !important;
        }
        
        .progress-bar.bg-danger {
            background-color: #dc3545 !important;
        }
        
        .progress-bar.bg-primary {
            background-color: #405189 !important;
        }
        
        #progressMessage {
            font-weight: 500;
            color: #495057;
            transition: color 0.3s ease;
        }
        
        /* Responsive Progress Bar */
        @media (max-width: 576px) {
            .progress {
                height: 8px !important;
            }
            
            #progressMessage {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="tracker-card">
            <div class="batch-header text-center">
                <h1 class="h4 mb-2">
                    <i class="bi bi-phone-vibrate me-2"></i>
                    Batch Vehicle Tracker
                </h1>
                <small><?= lang('App.tap_multiple_vehicles_efficiently') ?></small>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number" id="totalScanned">0</div>
                <div class="stat-label"><?= lang('App.tapped') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-success" id="successCount">0</div>
                <div class="stat-label"><?= lang('App.success') ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number text-danger" id="errorCount">0</div>
                <div class="stat-label"><?= lang('App.errors') ?></div>
            </div>
        </div>

        <!-- NFC Input -->
        <div class="tracker-card">
            <div class="scan-input pulse">
                <div class="mb-3 position-relative">
                    <!-- Finish button in top right -->
                    <button class="btn btn-primary btn-sm position-absolute" 
                            style="top: -10px; right: -10px;" 
                            onclick="finishSession()">
                        <i class="bi bi-check-circle me-1"></i>Finish
                    </button>
                    <i class="bi bi-phone-vibrate text-primary" style="font-size: 2rem;"></i>
                    <h5 class="mt-2"><?= lang('App.ready_to_tap') ?></h5>
                </div>
                <input type="text" 
                       id="scanInput" 
                       placeholder="<?= lang('App.tap_nfc_or_enter_code_manually') ?>"
                       autocomplete="off"
                       autofocus>
                <div class="mt-2">
                    <small class="text-muted"><?= lang('App.tap_nfc_tag_or_enter_manually') ?></small>
                </div>
                
                <!-- Progress Bar Container -->
                <div id="progressContainer" class="mt-3" style="display: none;">
                    <div class="progress" style="height: 10px; border-radius: 8px;">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                             role="progressbar" style="width: 0%; border-radius: 8px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <div id="progressText" class="text-center mt-2">
                        <small id="progressMessage" class="text-muted">Processing...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scan History -->
        <div class="tracker-card">
            <div style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        <?= lang('App.scan_history') ?>
                    </h5>
                    <div class="btn-group scan-header-buttons" role="group">
                        <button class="btn btn-success btn-sm" onclick="exportResults()">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="sendEmailReport()">
                            <i class="bi bi-envelope me-1"></i>Email Report
                        </button>
                    </div>
                </div>
                <div id="scanHistory" class="scan-history">
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2"><?= lang('App.no_scans_yet_start_first') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Close Tab Button at Bottom -->
        <div class="text-center mt-4 mb-3">
            <button class="btn btn-outline-danger" onclick="closeCurrentTab()">
                <i class="bi bi-x-circle me-2"></i><?= lang('App.close') ?>
            </button>
            <div class="mt-2">
                <small class="text-muted"><?= lang('App.close_this_tab') ?></small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastify JS -->
    <script src="<?= base_url('assets/libs/toastify-js/src/toastify.js') ?>"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>
    
    <script>
        let scanHistory = [];
        let totalScanned = 0;
        let successCount = 0;
        let errorCount = 0;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            const scanInput = document.getElementById('scanInput');
            
            // Focus on input
            scanInput.focus();
            
            // Handle scan input
            scanInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const token = this.value.trim();
                    if (token) {
                        processToken(token);
                        this.value = '';
                    }
                }
            });
            
            // Keep focus on input
            scanInput.addEventListener('blur', function() {
                setTimeout(() => this.focus(), 100);
            });
            
            // Initialize Web NFC Reader
            initializeNFCReader();
            
            // Check for preloaded token from single mode
            checkPreloadedToken();
        });

        async function processToken(token) {
            // Extract token from URL if needed
            const cleanToken = extractTokenFromUrl(token);
            
            // Show progress bar and start processing
            showProgressBar();
            updateProgressBar(0, '<?= lang('App.validating_nfc') ?>...');
            
            // Clear input for next scan
            document.getElementById('scanInput').value = '';
            
            // Add to history with processing status
            addToHistory(cleanToken, 'processing');
            updateStats();
            
            try {
                // Step 1: Validate token and get vehicle info (25%)
                updateProgressBar(25, '<?= lang('App.getting_vehicle_info') ?>...');
                const vehicleInfo = await fetchVehicleInfo(cleanToken);
                
                // Step 2: Get GPS location (50%)
                updateProgressBar(50, '<?= lang('App.getting_location') ?>...');
                const locationData = await getCurrentLocation();
                
                // Step 3: Save location (75%)
                updateProgressBar(75, '<?= lang('App.saving_location') ?>...');
                const saveResult = await saveLocationDirect(cleanToken, locationData);
                
                // Step 4: Get address (90%)
                updateProgressBar(90, '<?= lang('App.getting_address') ?>...');
                const address = await getAddressFromCoordinates(locationData.latitude, locationData.longitude);
                
                // Step 5: Complete (100%)
                updateProgressBar(100, '<?= lang('App.location_saved') ?>!');
                
                // Update history with success and vehicle info, including location data
                const locationInfo = {
                    spot_number: saveResult.data?.spot_number || 'UNSPECIFIED',
                    notes: saveResult.data?.notes || '',
                    latitude: locationData.latitude,
                    longitude: locationData.longitude,
                    address: address
                };
                
                updateHistoryEntry(cleanToken, 'success', vehicleInfo, locationInfo);
                successCount++;
                updateStats();
                playSuccessSound();
                
                // Hide progress bar after delay
                setTimeout(() => {
                    hideProgressBar();
                }, 1500);
                
            } catch (error) {
                console.error('Error processing token:', error);
                updateProgressBar(100, '<?= lang('App.error_processing') ?>');
                updateHistoryEntry(cleanToken, 'error', null, null);
                errorCount++;
                updateStats();
                
                // Hide progress bar after delay
                setTimeout(() => {
                    hideProgressBar();
                    showToast('error', error.message || '<?= lang('App.error_processing') ?>');
                }, 2000);
            }
        }

        function extractTokenFromUrl(input) {
            // If it's a full URL, extract the token
            if (input.includes('/location/')) {
                const matches = input.match(/\/location\/([^/?]+)/);
                return matches ? matches[1] : input;
            }
            return input;
        }

        function addToHistory(token, status, vehicleInfo = null, locationInfo = null) {
            const historyContainer = document.getElementById('scanHistory');
            const timestamp = new Date().toLocaleTimeString();
            
            // Remove empty state if exists
            if (scanHistory.length === 0) {
                historyContainer.innerHTML = '';
            }
            
            let vehicleDisplay = '';
            if (vehicleInfo && vehicleInfo.vehicle && vehicleInfo.vin) {
                vehicleDisplay = `
                    <strong>Vehicle:</strong> ${vehicleInfo.vehicle}<br>
                    <strong>VIN:</strong> ${vehicleInfo.vin}<br>
                `;
            } else {
                vehicleDisplay = `<strong>NFC:</strong> ${token.substring(0, 8)}...<br>`;
            }
            
            const entryHtml = `
                <div class="vehicle-entry ${status}" id="entry-${token}">
                    <div>
                        ${vehicleDisplay}
                        <small class="text-muted">${timestamp}</small>
                    </div>
                    <div>
                        <span class="badge bg-${status === 'success' ? 'success' : status === 'error' ? 'danger' : 'warning'}">
                            ${status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </div>
                </div>
            `;
            
            historyContainer.insertAdjacentHTML('afterbegin', entryHtml);
            scanHistory.unshift({token, status, timestamp, vehicleInfo, locationInfo});
            
            totalScanned++;
        }

        function updateHistoryEntry(token, newStatus, vehicleInfo = null, locationInfo = null) {
            const entry = document.getElementById(`entry-${token}`);
            if (entry) {
                entry.className = `vehicle-entry ${newStatus}`;
                const badge = entry.querySelector('.badge');
                badge.className = `badge bg-${newStatus === 'success' ? 'success' : 'danger'}`;
                badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                
                // Update vehicle info if provided
                if (vehicleInfo && vehicleInfo.vehicle && vehicleInfo.vin) {
                    const vehicleContent = entry.querySelector('div:first-child');
                    const timestamp = vehicleContent.querySelector('small').outerHTML;
                    vehicleContent.innerHTML = `
                        <strong>Vehicle:</strong> ${vehicleInfo.vehicle}<br>
                        <strong>VIN:</strong> ${vehicleInfo.vin}<br>
                        ${timestamp}
                    `;
                }
                
                // Update scanHistory with location info
                const historyIndex = scanHistory.findIndex(item => item.token === token);
                if (historyIndex !== -1) {
                    scanHistory[historyIndex].vehicleInfo = vehicleInfo;
                    scanHistory[historyIndex].locationInfo = locationInfo;
                }
            }
        }
        
        async function fetchVehicleInfo(token) {
            try {
                const response = await fetch(`<?= base_url('api/location/token-info/') ?>${token}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                if (!response.ok) {
                    throw new Error('<?= lang('App.invalid_nfc_code') ?>');
                }
                
                const data = await response.json();
                if (data.success && data.vehicle) {
                    return {
                        vehicle: data.vehicle.vehicle || 'Unknown Vehicle',
                        vin: data.vehicle.vin_number || 'Unknown VIN'
                    };
                }
                throw new Error('<?= lang('App.vehicle_info_not_available') ?>');
            } catch (error) {
                console.log('Could not fetch vehicle info:', error);
                throw error;
            }
        }
        
        // Progress Bar Functions
        function showProgressBar() {
            document.getElementById('progressContainer').style.display = 'block';
            document.getElementById('progressBar').style.width = '0%';
        }
        
        function updateProgressBar(percentage, message) {
            const progressBar = document.getElementById('progressBar');
            const progressMessage = document.getElementById('progressMessage');
            
            progressBar.style.width = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);
            
            if (message) {
                progressMessage.textContent = message;
            }
            
            // Change color based on progress
            progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated';
            if (percentage === 100) {
                if (message.includes('<?= lang('App.error_processing') ?>')) {
                    progressBar.classList.add('bg-danger');
                } else {
                    progressBar.classList.add('bg-success');
                }
            } else {
                progressBar.classList.add('bg-primary');
            }
        }
        
        function hideProgressBar() {
            document.getElementById('progressContainer').style.display = 'none';
        }
        
        // GPS Location Function
        async function getCurrentLocation() {
            return new Promise((resolve, reject) => {
                if (!navigator.geolocation) {
                    // GPS not available, resolve with null (location is optional)
                    resolve({ latitude: null, longitude: null });
                    return;
                }
                
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    },
                    (error) => {
                        console.log('GPS error:', error);
                        // GPS failed, but continue with null location
                        resolve({ latitude: null, longitude: null });
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 30000
                    }
                );
            });
        }
        
        // Direct Save Location Function
        async function saveLocationDirect(token, locationData) {
            try {
                const payload = {
                    token: token,
                    spot_number: '', // Optional for batch tracking
                    latitude: locationData.latitude,
                    longitude: locationData.longitude,
                    notes: '' // Optional for batch tracking
                };
                
                const response = await fetch('<?= base_url('api/location/save') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });
                
                if (!response.ok) {
                    throw new Error('<?= lang('App.failed_to_save_location') ?>');
                }
                
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || '<?= lang('App.failed_to_save_location') ?>');
                }
                
                return result;
            } catch (error) {
                console.error('Save location error:', error);
                throw error;
            }
        }
        
        // Reverse Geocoding Function
        async function getAddressFromCoordinates(latitude, longitude) {
            if (!latitude || !longitude) {
                return 'No location available';
            }
            
            try {
                // Using Nominatim OpenStreetMap reverse geocoding (free)
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&addressdetails=1`,
                    {
                        headers: {
                            'User-Agent': 'BatchVehicleTracker/1.0'
                        }
                    }
                );
                
                if (!response.ok) {
                    throw new Error('Geocoding service unavailable');
                }
                
                const data = await response.json();
                
                if (data.display_name) {
                    // Format address nicely
                    const address = data.address;
                    const parts = [];
                    
                    if (address.house_number && address.road) {
                        parts.push(`${address.house_number} ${address.road}`);
                    } else if (address.road) {
                        parts.push(address.road);
                    }
                    
                    if (address.neighbourhood || address.suburb) {
                        parts.push(address.neighbourhood || address.suburb);
                    }
                    
                    if (address.city || address.town || address.village) {
                        parts.push(address.city || address.town || address.village);
                    }
                    
                    if (address.state) {
                        parts.push(address.state);
                    }
                    
                    return parts.join(', ') || data.display_name;
                } else {
                    return `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
                }
            } catch (error) {
                console.warn('Reverse geocoding failed:', error);
                return `${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
            }
        }

        function updateStats() {
            document.getElementById('totalScanned').textContent = totalScanned;
            document.getElementById('successCount').textContent = successCount;
            document.getElementById('errorCount').textContent = errorCount;
        }

        function clearHistory() {
            Swal.fire({
                title: 'Clear All History?',
                text: 'This will remove all scanned entries from this session.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, clear all',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    scanHistory = [];
                    totalScanned = 0;
                    successCount = 0;
                    errorCount = 0;
                    updateStats();
                    
                    document.getElementById('scanHistory').innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">No scans yet. Start by scanning your first vehicle!</p>
                        </div>
                    `;
                    
                    showToast('success', 'History cleared successfully');
                }
            });
        }

        function exportResults() {
            if (scanHistory.length === 0) {
                showToast('warning', 'No data to export');
                return;
            }
            
            // Create CSV content with extended headers
            let csvContent = 'Vehicle,VIN,Spot Number,Notes,Latitude,Longitude,Address,Timestamp\n';
            
            scanHistory.forEach(entry => {
                const vehicle = entry.vehicleInfo?.vehicle || 'Unknown Vehicle';
                const vin = entry.vehicleInfo?.vin || 'Unknown VIN';
                const spotNumber = entry.locationInfo?.spot_number || 'UNSPECIFIED';
                const notes = entry.locationInfo?.notes || '';
                const latitude = entry.locationInfo?.latitude ? entry.locationInfo.latitude.toFixed(6) : '';
                const longitude = entry.locationInfo?.longitude ? entry.locationInfo.longitude.toFixed(6) : '';
                const address = entry.locationInfo?.address || 'No location available';
                const timestamp = entry.timestamp;
                
                // Escape commas and quotes in CSV
                const escapeCsv = (field) => {
                    const fieldStr = String(field || '');
                    if (fieldStr.includes(',') || fieldStr.includes('"') || fieldStr.includes('\n')) {
                        return '"' + fieldStr.replace(/"/g, '""') + '"';
                    }
                    return fieldStr;
                };
                
                csvContent += `${escapeCsv(vehicle)},${escapeCsv(vin)},${escapeCsv(spotNumber)},${escapeCsv(notes)},${escapeCsv(latitude)},${escapeCsv(longitude)},${escapeCsv(address)},${escapeCsv(timestamp)}\n`;
            });
            
            // Create and download Excel file
            const blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `vehicle-scan-session-${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            URL.revokeObjectURL(url);
            
            showToast('success', '<?= lang('App.excel_file_exported') ?>');
        }

        async function sendEmailReport() {
            if (scanHistory.length === 0) {
                showToast('warning', '<?= lang('App.no_data_to_send') ?>');
                return;
            }
            
            // Check if user is logged in
            const currentUser = '<?= isset($current_user) && $current_user ? esc($current_user->email) : '' ?>';
            if (!currentUser) {
                showToast('error', '<?= lang('App.login_required_for_email') ?>');
                return;
            }
            
            // Show loading state
            const emailBtn = document.querySelector('button[onclick="sendEmailReport()"]');
            const originalHtml = emailBtn.innerHTML;
            emailBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i><?= lang('App.sending') ?>...';
            emailBtn.disabled = true;
            
            try {
                // Generate CSV data
                const csvData = generateCSVData();
                
                // Prepare email data
                const emailData = {
                    csv_data: csvData,
                    total_vehicles: totalScanned,
                    successful_scans: successCount,
                    error_count: errorCount,
                    session_date: new Date().toLocaleDateString(),
                    session_time: new Date().toLocaleTimeString()
                };
                
                // Send email request
                const response = await fetch('<?= base_url('api/location/send-email-report') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(emailData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast('success', '<?= lang('App.email_sent_successfully') ?>');
                } else {
                    throw new Error(result.message || '<?= lang('App.failed_to_send_email') ?>');
                }
                
            } catch (error) {
                console.error('Email sending error:', error);
                showToast('error', error.message || '<?= lang('App.failed_to_send_email') ?>');
            } finally {
                // Restore button state
                emailBtn.innerHTML = originalHtml;
                emailBtn.disabled = false;
            }
        }
        
        function generateCSVData() {
            let csvContent = 'Vehicle,VIN,Spot Number,Notes,Latitude,Longitude,Address,Timestamp\n';
            
            scanHistory.forEach(entry => {
                const vehicle = entry.vehicleInfo?.vehicle || 'Unknown Vehicle';
                const vin = entry.vehicleInfo?.vin || 'Unknown VIN';
                const spotNumber = entry.locationInfo?.spot_number || 'UNSPECIFIED';
                const notes = entry.locationInfo?.notes || '';
                const latitude = entry.locationInfo?.latitude ? entry.locationInfo.latitude.toFixed(6) : '';
                const longitude = entry.locationInfo?.longitude ? entry.locationInfo.longitude.toFixed(6) : '';
                const address = entry.locationInfo?.address || 'No location available';
                const timestamp = entry.timestamp;
                
                // Escape commas and quotes in CSV
                const escapeCsv = (field) => {
                    const fieldStr = String(field || '');
                    if (fieldStr.includes(',') || fieldStr.includes('"') || fieldStr.includes('\n')) {
                        return '"' + fieldStr.replace(/"/g, '""') + '"';
                    }
                    return fieldStr;
                };
                
                csvContent += `${escapeCsv(vehicle)},${escapeCsv(vin)},${escapeCsv(spotNumber)},${escapeCsv(notes)},${escapeCsv(latitude)},${escapeCsv(longitude)},${escapeCsv(address)},${escapeCsv(timestamp)}\n`;
            });
            
            return csvContent;
        }

        async function testEmailConfig() {
            const testBtn = document.querySelector('button[onclick="testEmailConfig()"]');
            const originalHtml = testBtn.innerHTML;
            testBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Testing...';
            testBtn.disabled = true;
            
            try {
                const response = await fetch('<?= base_url('api/location/test-email-config') ?>');
                const result = await response.json();
                
                if (result.success) {
                    // Show success with SweetAlert2
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Configuration Test',
                        html: `
                            <p><strong>${result.message}</strong></p>
                            <div class="mt-3 text-start">
                                <h6>Current SMTP Settings:</h6>
                                <small>
                                    <strong>Host:</strong> ${result.config.smtp_host}<br>
                                    <strong>Port:</strong> ${result.config.smtp_port}<br>
                                    <strong>User:</strong> ${result.config.smtp_user}<br>
                                    <strong>From:</strong> ${result.config.smtp_from}<br>
                                    <strong>Encryption:</strong> ${result.config.smtp_encryption}
                                </small>
                            </div>
                        `,
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Show error with instructions
                    const instructions = result.instructions ? result.instructions.join('<br>') : '';
                    Swal.fire({
                        icon: 'error',
                        title: 'Email Configuration Issue',
                        html: `
                            <p><strong>${result.message}</strong></p>
                            ${instructions ? `
                                <div class="mt-3 text-start">
                                    <h6>Setup Instructions:</h6>
                                    <small>${instructions}</small>
                                </div>
                            ` : ''}
                            <div class="mt-3 text-start">
                                <h6>Current Settings Status:</h6>
                                <small>
                                    <strong>Host:</strong> ${result.config.smtp_host}<br>
                                    <strong>Port:</strong> ${result.config.smtp_port}<br>
                                    <strong>User:</strong> ${result.config.smtp_user}<br>
                                    <strong>From:</strong> ${result.config.smtp_from}<br>
                                    <strong>Encryption:</strong> ${result.config.smtp_encryption}
                                </small>
                            </div>
                        `,
                        confirmButtonText: 'Go to Settings',
                        showCancelButton: true,
                        cancelButtonText: 'Close'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open('<?= base_url('settings') ?>', '_blank');
                        }
                    });
                }
                
            } catch (error) {
                console.error('Email config test error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Test Failed',
                    text: 'Could not test email configuration. Please check server connection.',
                    confirmButtonText: 'OK'
                });
            } finally {
                testBtn.innerHTML = originalHtml;
                testBtn.disabled = false;
            }
        }

        async function testEmailCSV() {
            const testBtn = document.querySelector('button[onclick="testEmailCSV()"]');
            const originalHtml = testBtn.innerHTML;
            testBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
            testBtn.disabled = true;
            
            try {
                const response = await fetch('<?= base_url('api/location/test-email-csv') ?>');
                const result = await response.json();
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ CSV Email Fixed & Working!',
                        html: `
                            <p><strong>${result.message}</strong></p>
                            <div class="mt-3 text-start">
                                <h6>📊 CSV Details:</h6>
                                <small>
                                    <strong>File:</strong> ${result.details.file_name}<br>
                                    <strong>Size:</strong> ${result.details.csv_size}<br>
                                    <strong>Rows:</strong> ${result.details.rows} (including header)
                                </small>
                            </div>
                            <div class="mt-3 p-2 bg-light rounded">
                                <p class="text-success mb-1"><strong>🔧 System Update:</strong></p>
                                <small class="text-muted">CSV attachment method has been improved to send actual data instead of file paths.</small>
                            </div>
                            <div class="mt-3">
                                <p class="text-info"><strong>📧 Check your email for the working CSV file!</strong></p>
                            </div>
                        `,
                        confirmButtonText: 'Great!'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'CSV Email Test Failed',
                        text: result.message || 'Unknown error occurred',
                        confirmButtonText: 'OK'
                    });
                }
                
            } catch (error) {
                console.error('CSV Email test error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Test Failed',
                    text: 'Could not send test CSV email. Please check server connection.',
                    confirmButtonText: 'OK'
                });
            } finally {
                testBtn.innerHTML = originalHtml;
                testBtn.disabled = false;
            }
        }

        function finishSession() {
            if (scanHistory.length === 0) {
                showToast('info', 'No vehicles scanned in this session');
                return;
            }
            
            Swal.fire({
                title: 'Session Complete!',
                html: `
                    <div class="mb-3">
                        <strong>Total Scanned:</strong> ${totalScanned}<br>
                        <strong>Successful:</strong> ${successCount}<br>
                        <strong>Errors:</strong> ${errorCount}
                    </div>
                    <p class="text-muted">Great job! You've completed scanning ${totalScanned} vehicles.</p>
                `,
                icon: 'success',
                confirmButtonText: 'Close Session',
                confirmButtonColor: '#405189'
            }).then(() => {
                // Option to close or start new session
                window.close();
            });
        }
        
        function closeCurrentTab() {
            // Confirm before closing
            Swal.fire({
                title: '<?= lang('App.close_tab_confirm') ?>',
                text: '<?= lang('App.close_tab_confirm_message') ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<?= lang('App.close_tab') ?>',
                cancelButtonText: '<?= lang('App.cancel') ?>',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Try multiple methods to close the tab
                    try {
                        window.close();
                    } catch (e) {
                        try {
                            window.open('', '_self').close();
                        } catch (e2) {
                            try {
                                window.history.back();
                            } catch (e3) {
                                // Fallback: show message
                                showToast('info', '<?= lang('App.close_tab_manually') ?>');
                            }
                        }
                    }
                }
            });
        }

        function playSuccessSound() {
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTOH0PfGgjMGHm7A7d2QQw0PVKzn77BdGAg+ltryxn0vBSl+zPLZizoIGGS57+OZURE');
                audio.volume = 0.1;
                audio.play().catch(e => console.log('Audio play failed:', e));
            } catch (e) {
                console.log('Audio not supported:', e);
            }
        }

        function showToast(type, message) {
            // Use SweetAlert2 for toast notifications like other modules
            const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                // Fallback to alert if SweetAlert2 is not available
                alert(message);
            }
        }
        
        // ==========================================
        // WEB NFC API IMPLEMENTATION
        // ==========================================
        
        async function initializeNFCReader() {
            // Check if Web NFC is available
            if ('NDEFReader' in window) {
                try {
                    console.log('🔵 Web NFC available - Starting reader...');
                    
                    const ndef = new NDEFReader();
                    await ndef.scan();
                    
                    console.log('✅ NFC Reader active - Ready for taps');
                    showToast('success', '📱 NFC Reader active - Tap your tags!');
                    
                    // Add visual indicator
                    addNFCIndicator(true);
                    
                    // Event when NFC tap is detected
                    ndef.addEventListener("reading", ({ message, serialNumber }) => {
                        console.log('📱 NFC Tag detected:', serialNumber);
                        
                        let token = null;
                        
                        // Process all records in the tag
                        for (const record of message.records) {
                            if (record.recordType === "text") {
                                // Plain text record
                                const textDecoder = new TextDecoder(record.encoding || 'utf-8');
                                token = textDecoder.decode(record.data);
                                break;
                            } else if (record.recordType === "url") {
                                // URL record
                                const url = new TextDecoder().decode(record.data);
                                token = extractTokenFromUrl(url);
                                break;
                            }
                        }
                        
                        if (token) {
                            console.log('🎯 Token extracted:', token);
                            
                            // MAGIC! Insert directly into field WITHOUT navigating
                            const scanInput = document.getElementById('scanInput');
                            scanInput.value = token;
                            
                            // Visual feedback for the tap
                            flashNFCIndicator();
                            
                            // Process automatically
                            processToken(token);
                            scanInput.value = '';
                            
                            // Success feedback
                            showToast('success', '✅ NFC tap processed automatically');
                        } else {
                            console.warn('⚠️ Could not extract token from NFC tag');
                            showToast('warning', 'NFC tag without valid token');
                        }
                    });
                    
                    ndef.addEventListener("readingerror", () => {
                        console.error('❌ NFC reading error');
                        showToast('error', 'NFC reading error');
                    });
                    
                } catch (error) {
                    console.log('❌ Web NFC not available:', error);
                    console.log('🔄 Fallback: User must use manual input or single mode');
                    
                    addNFCIndicator(false);
                    showToast('info', '📱 Use manual input or single scan mode');
                }
            } else {
                console.log('❌ Web NFC not supported in this browser');
                addNFCIndicator(false);
                showToast('info', '📱 NFC tap will open single mode');
            }
        }
        
        function addNFCIndicator(isActive) {
            // Add visual indicator for NFC status
            const header = document.querySelector('.batch-header');
            const indicator = document.createElement('div');
            indicator.id = 'nfc-indicator';
            indicator.className = `nfc-indicator ${isActive ? 'active' : 'inactive'}`;
            indicator.innerHTML = `
                <div class="d-flex align-items-center justify-content-center mt-2">
                    <i class="bi bi-${isActive ? 'phone-vibrate' : 'phone'} me-2"></i>
                    <small class="text-${isActive ? 'success' : 'muted'}">
                        ${isActive ? '🟢 NFC Reader Active' : '🔴 NFC Manual Mode'}
                    </small>
                </div>
            `;
            header.appendChild(indicator);
            
            // Add CSS for the indicator
            if (!document.getElementById('nfc-indicator-styles')) {
                const style = document.createElement('style');
                style.id = 'nfc-indicator-styles';
                style.textContent = `
                    .nfc-indicator {
                        transition: all 0.3s ease;
                    }
                    .nfc-indicator.flash {
                        background-color: rgba(25, 135, 84, 0.1);
                        border-radius: 8px;
                        padding: 5px;
                        animation: nfc-flash 0.6s ease;
                    }
                    @keyframes nfc-flash {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.05); background-color: rgba(25, 135, 84, 0.2); }
                        100% { transform: scale(1); }
                    }
                `;
                document.head.appendChild(style);
            }
        }
        
        function flashNFCIndicator() {
            const indicator = document.getElementById('nfc-indicator');
            if (indicator) {
                indicator.classList.add('flash');
                setTimeout(() => {
                    indicator.classList.remove('flash');
                }, 600);
            }
        }
        
        function checkPreloadedToken() {
            const urlParams = new URLSearchParams(window.location.search);
            const preloadToken = urlParams.get('preload');
            
            if (preloadToken) {
                console.log('🔄 Pre-loading token from single mode:', preloadToken);
                
                showToast('info', '🔄 Processing first vehicle from single mode...');
                
                // Process the token after a short delay
                setTimeout(() => {
                    processToken(preloadToken);
                    
                    // Clean up URL
                    window.history.replaceState({}, '', '/location/batch');
                    
                    setTimeout(() => {
                        showToast('success', '✅ First vehicle processed - Continue with next ones!');
                    }, 2000);
                }, 1000);
            }
        }
    </script>
</body>
</html>