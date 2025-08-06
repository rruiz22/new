<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?= $page_title ?></title>
    
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
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }
        
        .tracker-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .vehicle-header {
            background-color: #ffffff;
            color: #495057;
            text-align: center;
            padding: 25px 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .vehicle-vin {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            font-weight: bold;
            letter-spacing: 1px;
            color: #405189;
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            display: inline-block;
            margin-top: 8px;
        }
        
        .form-section {
            padding: 25px;
        }
        
        .location-status {
            display: none;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .location-loading {
            background: #f0f8ff;
            color: #405189;
            border: 1px solid #d4e6f7;
        }
        
        .location-success {
            background: #f0fff4;
            color: #28a745;
            border: 1px solid #c3e6cb;
        }
        
        .location-error {
            background: #fff8f8;
            color: #dc3545;
            border: 1px solid #f5c6cb;
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #405189;
            box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25);
        }
        
        .btn-primary {
            background-color: #405189;
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover:not(:disabled) {
            background-color: #364574;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(64, 81, 137, 0.4);
        }
        
        .btn-primary:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        
        .recent-locations {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin-top: 20px;
        }
        
        .location-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .location-item:last-child {
            border-bottom: none;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        
        .coordinates-display {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #495057;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
        }
        
        .btn-primary:not(:disabled):hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(64, 81, 137, 0.3);
        }
        
        .spinner-border-sm {
            animation: spinner-border 0.75s linear infinite;
        }
        
        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Mobile tracker success popup styles */
        .mobile-tracker-success {
            font-size: 0.9rem;
        }
        
        .mobile-tracker-success .swal2-html-container {
            margin: 1rem 0;
        }
        
        .mobile-tracker-success .swal2-confirm {
            padding: 0.75rem 2rem;
            font-size: 1rem;
        }
        
        /* Header styling for white background */
        .vehicle-header .btn-outline-primary {
            transition: all 0.3s ease;
        }
        
        .vehicle-header .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(64, 81, 137, 0.2);
        }
        
        .vehicle-header h1 .bi-geo-alt-fill {
            color: #405189;
        }
        
        /* Timer info alert styling */
        .alert-info {
            background-color: #e7f3ff;
            border-color: #b3d9ff;
            color: #004085;
        }
        
        @media (max-width: 480px) {
            .main-container {
                padding: 10px;
            }
            
            .form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Vehicle Info Card -->
        <div class="card tracker-card">
            <div class="vehicle-header">
                <h1 class="h4 mb-2">
                    <i class="bi bi-geo-alt-fill me-2"></i>
                    Vehicle Location Tracker
                </h1>
                <div class="vehicle-vin"><?= esc($vehicle['vin_number']) ?></div>
                <small class="text-muted mt-1 d-block">Tap to update vehicle location</small>
                
                <!-- Batch Scanner Button -->
                <div class="mt-3">
                    <a href="<?= base_url('location/batch') ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="bi bi-phone-vibrate me-2"></i>
                        <?= lang('App.batch_nfc_tracker') ?>
                    </a>
                    <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">
                        <?= lang('App.for_tapping_multiple_vehicles') ?>
                    </small>
                </div>
            </div>
            
            <div class="form-section">
                <!-- Location Status -->
                <div id="locationStatus" class="location-status">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        <span>Getting your location...</span>
                    </div>
                </div>
                
                <!-- Timer Information -->
                <div id="timerInfo" class="alert alert-info py-2 px-3 mb-3" style="display: none; font-size: 0.85rem;">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Auto-save in 5 seconds</strong> - Touch any field to take manual control
                </div>
                
                <!-- Location Form -->
                <form id="locationForm">
                    <input type="hidden" id="token" value="<?= esc($token) ?>">
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                    <input type="hidden" id="accuracy" name="accuracy">
                    
                    <div class="mb-3">
                        <label for="spotNumber" class="form-label fw-bold">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                            Parking Spot Number (Optional)
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="spotNumber" 
                               name="spot_number" 
                               placeholder="e.g., A-15, B-23, 105..." 
                               autocomplete="off">
                        <div class="form-text">Enter the spot where the vehicle is parked</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="userName" class="form-label fw-bold">
                            <i class="bi bi-person-fill text-primary me-2"></i>
                            <?= lang('App.your_name') ?><?php if (!isset($current_user) || !$current_user): ?> (<?= lang('App.optional') ?>)<?php endif; ?>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="userName" 
                               name="user_name" 
                               value="<?= isset($current_user) && $current_user ? esc($current_user->firstname . ' ' . $current_user->lastname) : '' ?>"
                               placeholder="<?= isset($current_user) && $current_user ? lang('App.your_name_auto_filled') : lang('App.enter_your_name') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">
                            <i class="bi bi-chat-left-text-fill text-primary me-2"></i>
                            Notes (Optional)
                        </label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="2" 
                                  placeholder="Any additional notes..."></textarea>
                    </div>
                    
                    <!-- Coordinates Display - Hidden for client users -->
                    <?php if ($user_type !== 'client'): ?>
                    <div id="coordinatesDisplay" class="coordinates-display" style="display: none;">
                        <small>
                            <strong>📍 Location:</strong><br>
                            <span id="coordsText">Getting location...</span>
                        </small>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        <span id="btnText">Getting Location...</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Recent Locations - Hidden for client users -->
        <?php if (!empty($recent_locations) && $user_type !== 'client'): ?>
        <div class="recent-locations">
            <h5 class="mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i>
                Recent Locations
            </h5>
            <?php foreach ($recent_locations as $location): ?>
            <div class="location-item">
                <div>
                    <strong>Spot <?= esc($location['spot_number']) ?></strong>
                    <br>
                    <small class="text-muted">
                        by <?= esc($location['user_name'] ?? 'Anonymous') ?>
                    </small>
                </div>
                <div class="text-end">
                    <small class="text-muted">
                        <?= date('M j, g:i A', strtotime($location['created_at'])) ?>
                    </small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastify JS -->
    <script src="<?= base_url('assets/libs/toastify-js/src/toastify.js') ?>"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>
    
    <script>
        let userLocation = null;
        let locationWatchId = null;
        let autoSaveTimer = null;
        let userInteracted = false;
        const userType = '<?= $user_type ?? '' ?>';
        const isClientUser = userType === 'client';
        
        // Initialize location tracking on page load
        document.addEventListener('DOMContentLoaded', function() {
            requestLocation();
            setupFormInteractionListeners();
            
            // Check if user arrived from NFC tap
            checkNFCArrival();
        });
        
        // Audio notification function
        function playNotificationSound() {
            try {
                // Create a pleasant success notification sound
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                
                // Success chime frequencies
                const frequencies = [523.25, 659.25, 783.99]; // C5, E5, G5
                const duration = 0.3;
                
                frequencies.forEach((freq, index) => {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.setValueAtTime(freq, audioContext.currentTime + index * 0.1);
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0, audioContext.currentTime + index * 0.1);
                    gainNode.gain.linearRampToValueAtTime(0.2, audioContext.currentTime + index * 0.1 + 0.05);
                    gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + index * 0.1 + duration);
                    
                    oscillator.start(audioContext.currentTime + index * 0.1);
                    oscillator.stop(audioContext.currentTime + index * 0.1 + duration);
                });
                
            } catch (e) {
                // Fallback to simple beep if Web Audio API is not supported
                try {
                    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTOH0PfGgjMGHm7A7d2QQw0PVKzn77BdGAg+ltryxn0vBSl+zPLZizoIGGS57+OZURE');
                    audio.volume = 0.2;
                    audio.play().catch(e => console.log('Audio play failed:', e));
                } catch (e2) {
                    console.log('Audio notification not supported:', e2);
                }
            }
        }
        
        // Toast notification function
        function showToast(message, type = 'success') {
            const backgroundColor = {
                'success': '#28a745',
                'error': '#dc3545',
                'warning': '#ffc107',
                'info': '#17a2b8'
            };
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "center",
                backgroundColor: backgroundColor[type] || backgroundColor['info'],
                stopOnFocus: true
            }).showToast();
        }
        
        function setupFormInteractionListeners() {
            const inputs = ['spotNumber', 'userName', 'notes'];
            
            inputs.forEach(inputId => {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('focus', cancelAutoSave);
                    input.addEventListener('input', cancelAutoSave);
                    input.addEventListener('keydown', cancelAutoSave);
                }
            });
        }
        
        function cancelAutoSave() {
            if (autoSaveTimer && !userInteracted) {
                clearInterval(autoSaveTimer);
                autoSaveTimer = null;
                userInteracted = true;
                
                // Hide timer information message
                const timerInfo = document.getElementById('timerInfo');
                if (timerInfo) {
                    timerInfo.style.display = 'none';
                }
                
                // Update button text to indicate manual save is now required
                const btnText = document.getElementById('btnText');
                if (btnText) {
                    btnText.textContent = 'Save Location';
                }
                
                console.log('Auto-save cancelled due to user interaction');
            }
        }
        
        function startAutoSaveTimer() {
            if (userInteracted) return; // Don't start timer if user already interacted
            
            const btnText = document.getElementById('btnText');
            const timerInfo = document.getElementById('timerInfo');
            let countdown = 5;
            
            // Show timer information message
            timerInfo.style.display = 'block';
            
            // Show initial countdown
            btnText.innerHTML = `<i class="bi bi-hourglass-split me-2"></i>Auto-saving in ${countdown}s...`;
            
            // Create countdown interval
            const countdownInterval = setInterval(() => {
                if (userInteracted) {
                    clearInterval(countdownInterval);
                    return;
                }
                
                countdown--;
                
                if (countdown > 0) {
                    btnText.innerHTML = `<i class="bi bi-hourglass-split me-2"></i>Auto-saving in ${countdown}s...`;
                } else {
                    // Auto-save time reached
                    clearInterval(countdownInterval);
                    timerInfo.style.display = 'none';
                    autoSaveLocation();
                }
            }, 1000);
            
            // Store the interval ID for potential cancellation
            autoSaveTimer = countdownInterval;
        }
        
        function autoSaveLocation() {
            if (userInteracted) return; // Don't auto-save if user interacted
            
            console.log('Auto-saving location...');
            
            // Prepare minimal data (just location, no form fields)
            const formData = {
                token: document.getElementById('token').value,
                spot_number: null, // Auto-save doesn't include form fields
                latitude: document.getElementById('latitude').value || null,
                longitude: document.getElementById('longitude').value || null,
                accuracy: document.getElementById('accuracy').value || null,
                user_name: null,
                notes: null
            };
            
            // Call save function
            saveLocationData(formData, true); // true indicates auto-save
        }
        
        function requestLocation() {
            const status = document.getElementById('locationStatus');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const coordsDisplay = document.getElementById('coordinatesDisplay');
            
            if (!navigator.geolocation) {
                showLocationError('Geolocation is not supported by this device');
                return;
            }
            
            // Show loading status
            status.className = 'location-status location-loading';
            status.style.display = 'block';
            
            const options = {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 60000
            };
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userLocation = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy
                    };
                    
                    // Update hidden fields
                    document.getElementById('latitude').value = userLocation.latitude;
                    document.getElementById('longitude').value = userLocation.longitude;
                    document.getElementById('accuracy').value = userLocation.accuracy;
                    
                    // Show success status
                    status.className = 'location-status location-success';
                    status.innerHTML = `
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Location acquired successfully!
                    `;
                    
                    // Show coordinates (only for non-client users)
                    if (!isClientUser) {
                    const coordsText = document.getElementById('coordsText');
                        if (coordsText) {
                    coordsText.innerHTML = `
                        Lat: ${userLocation.latitude.toFixed(6)}<br>
                        Lng: ${userLocation.longitude.toFixed(6)}<br>
                        Accuracy: ±${Math.round(userLocation.accuracy)}m
                    `;
                    coordsDisplay.style.display = 'block';
                        }
                    }
                    
                    // Enable submit button
                    submitBtn.disabled = false;
                    btnText.textContent = 'Save Location';
                    
                    // Hide status after 3 seconds
                    setTimeout(() => {
                        status.style.display = 'none';
                    }, 3000);
                    
                    // Start auto-save timer (5 seconds)
                    startAutoSaveTimer();
                },
                function(error) {
                    let errorMessage = 'Failed to get location';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Location access denied. Please enable location permissions.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Location request timed out. Please try again.';
                            break;
                    }
                    showLocationError(errorMessage);
                },
                options
            );
        }
        
        function showLocationError(message) {
            const status = document.getElementById('locationStatus');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            
            status.className = 'location-status location-error';
            status.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                ${message}
                <br><button class="btn btn-sm btn-outline-danger mt-2" onclick="requestLocation()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Retry
                </button>
            `;
            status.style.display = 'block';
            
            // Still allow manual submission without location
            submitBtn.disabled = false;
            btnText.textContent = 'Save Without Location';
        }
        
        function saveLocationData(formData, isAutoSave = false) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
            
            // Send to server
            fetch('<?= base_url('api/location/save') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showConfirmationMessage(data, isAutoSave);
                } else {
                    throw new Error(data.message || data.error_details || 'Failed to save location');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error saving location: ' + error.message, 'error');
                
                // Re-enable button on error
                submitBtn.disabled = false;
                btnText.textContent = 'Save Location';
            });
        }
        
        function showConfirmationMessage(data, isAutoSave = false) {
            // Play notification sound
            playNotificationSound();
            
            // Show success toast
            showToast(
                isAutoSave ? 'Location auto-saved successfully!' : 'Location saved successfully!',
                'success'
            );
            
            // Show SweetAlert2 confirmation with auto-close timer
            let autoCloseTimer = 5;
            let autoCloseInterval;
            
            const swalInstance = Swal.fire({
                icon: 'success',
                title: isAutoSave ? 'Location Auto-Saved!' : 'Location Saved Successfully!',
                html: `
                    <div class="mb-3">
                        <strong>Vehicle:</strong> ${data.vehicle}<br>
                        ${data.spot ? `<strong>Parking Spot:</strong> ${data.spot}<br>` : ''}
                        <strong>Time:</strong> ${new Date(data.timestamp).toLocaleString()}
                    </div>
                    <div class="text-muted">
                        ${isAutoSave ? 
                            'Your location was automatically saved based on GPS coordinates.' : 
                            'Thank you for updating the vehicle location.'}
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-warning py-2 px-3" style="font-size: 0.85rem;">
                            <i class="bi bi-clock me-1"></i>
                            <span id="autoCloseMessage">This tab will close automatically in ${autoCloseTimer} seconds</span>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-x-circle me-2"></i>Close Now',
                cancelButtonText: '<i class="bi bi-hand-thumbs-up me-2"></i>Keep Open',
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#405189',
                allowOutsideClick: false,
                allowEscapeKey: true,
                customClass: {
                    popup: 'mobile-tracker-success'
                },
                didOpen: () => {
                    // Start auto-close countdown
                    autoCloseInterval = setInterval(() => {
                        autoCloseTimer--;
                        const messageElement = document.getElementById('autoCloseMessage');
                        if (messageElement) {
                            if (autoCloseTimer > 0) {
                                messageElement.textContent = `This tab will close automatically in ${autoCloseTimer} seconds`;
                            } else {
                                clearInterval(autoCloseInterval);
                                Swal.close();
                                closeCurrentTab();
                            }
                        }
                    }, 1000);
                },
                willClose: () => {
                    // Clear interval when modal is closed manually
                    if (autoCloseInterval) {
                        clearInterval(autoCloseInterval);
                    }
                }
            }).then((result) => {
                // Clear interval in case it's still running
                if (autoCloseInterval) {
                    clearInterval(autoCloseInterval);
                }
                
                if (result.isConfirmed) {
                    // User clicked "Close Now"
                    closeCurrentTab();
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                    // User clicked "Keep Open" - do nothing, let them continue
                    console.log('User chose to keep tab open');
                }
            });
        }
        
        function resetFormForNewEntry() {
            // Reset form fields
            document.getElementById('spotNumber').value = '';
            document.getElementById('userName').value = '';
            document.getElementById('notes').value = '';
            
            // Reset interaction state
            userInteracted = false;
            
            // Re-enable button
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            submitBtn.disabled = false;
            submitBtn.className = 'btn btn-primary';
            btnText.textContent = 'Save Location';
            
            // Refresh location
            requestLocation();
        }
        
        function closeCurrentTab() {
            try {
                // Try multiple methods to close the tab
                if (window.opener) {
                    // If opened by another window, close this one
                    window.close();
                } else if (history.length === 1) {
                    // If this is the only page in history, close the tab
                    window.close();
                } else {
                    // For some browsers, this might work
                    window.open('', '_self').close();
                }
                
                // Fallback: Show instruction if window.close() doesn't work
                setTimeout(() => {
                    showToast('Please close this tab manually and scan the next vehicle', 'info');
                }, 1000);
                
            } catch (e) {
                console.log('Cannot close tab automatically:', e);
                showToast('Please close this tab manually and scan the next vehicle', 'info');
            }
        }
        
        // Handle form submission
        document.getElementById('locationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Cancel auto-save if it's still active
            if (autoSaveTimer && !userInteracted) {
                clearInterval(autoSaveTimer);
                autoSaveTimer = null;
            }
            
            // Mark as user-initiated save
            userInteracted = true;
            
            // Get form data
            const spotNumber = document.getElementById('spotNumber').value.trim();
            const userName = document.getElementById('userName').value.trim();
            const notes = document.getElementById('notes').value.trim();
            
            // Prepare data
            const formData = {
                token: document.getElementById('token').value,
                spot_number: spotNumber || null,
                latitude: document.getElementById('latitude').value || null,
                longitude: document.getElementById('longitude').value || null,
                accuracy: document.getElementById('accuracy').value || null,
                user_name: userName || null,
                notes: notes || null
            };
            
            // Use the shared save function
            saveLocationData(formData, false); // false indicates manual save
        });
        
        // ==========================================
        // NFC DETECTION AND BATCH MODE INTEGRATION
        // ==========================================
        
        function checkNFCArrival() {
            const isFromNFC = detectNFCArrival();
            
            if (isFromNFC) {
                console.log('🔵 User arrived from NFC tap - Setting up batch mode integration');
                
                // Mark in localStorage that user is in "NFC mode"
                localStorage.setItem('nfc_mode_active', 'true');
                localStorage.setItem('nfc_last_access', Date.now());
                
                // Show prominent batch mode option
                showBatchModeOption();
                
                // Show helpful tip
                setTimeout(() => {
                    showToast('💡 Tip: Switch to Batch Mode for faster multiple vehicle scanning!', 'info');
                }, 2000);
            }
        }
        
        function detectNFCArrival() {
            // Multiple heuristics to detect NFC tap arrival
            
            // 1. Check if navigation was very fast (typical of NFC tap)
            const navigationStart = performance.timing.navigationStart;
            const loadComplete = performance.timing.loadEventEnd;
            const loadTime = loadComplete - navigationStart;
            
            // 2. Check if there's no internal referrer
            const hasInternalReferrer = document.referrer.includes(window.location.hostname);
            
            // 3. Check if URL matches NFC pattern
            const urlPattern = /\/location\/[a-zA-Z0-9]+$/;
            const matchesPattern = urlPattern.test(window.location.pathname);
            
            // 4. Check user agent for mobile (NFC usually on mobile)
            const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Combined logic: fast load + no internal referrer + pattern match + mobile
            const fastLoad = loadTime < 3000;
            const likelyNFC = fastLoad && !hasInternalReferrer && matchesPattern && isMobile;
            
            console.log('🔍 NFC Detection:', {
                loadTime,
                hasInternalReferrer,
                matchesPattern,
                isMobile,
                likelyNFC
            });
            
            return likelyNFC;
        }
        
        function showBatchModeOption() {
            // Create prominent batch mode switcher
            const vehicleHeader = document.querySelector('.vehicle-header');
            
            const batchModeAlert = document.createElement('div');
            batchModeAlert.className = 'alert alert-primary mt-3 mb-0';
            batchModeAlert.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="alert-heading mb-1">
                            <i class="bi bi-collection me-2"></i>Multiple Vehicles?
                        </h6>
                        <small>Switch to Batch Mode for faster scanning</small>
                    </div>
                    <div class="col-4 text-end">
                        <button class="btn btn-primary btn-sm" onclick="switchToBatchMode()">
                            <i class="bi bi-arrow-right me-1"></i>Batch Mode
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        ⚡ Next NFC taps will be processed automatically without page changes
                    </small>
                </div>
            `;
            
            // Insert after vehicle header
            vehicleHeader.after(batchModeAlert);
            
            // Add some visual animation
            setTimeout(() => {
                batchModeAlert.style.transform = 'scale(1.02)';
                batchModeAlert.style.boxShadow = '0 4px 20px rgba(13, 110, 253, 0.15)';
                batchModeAlert.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    batchModeAlert.style.transform = 'scale(1)';
                    batchModeAlert.style.boxShadow = '0 2px 10px rgba(13, 110, 253, 0.1)';
                }, 300);
            }, 500);
        }
        
        function switchToBatchMode() {
            // Get current token for pre-loading
            const currentToken = window.location.pathname.split('/').pop();
            
            // Show loading state
            const button = event.target;
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Loading...';
            button.disabled = true;
            
            // Provide user feedback
            showToast('🔄 Switching to Batch Mode...', 'info');
            
            // Navigate to batch mode with preloaded token
            setTimeout(() => {
                window.location.href = `<?= base_url('location/batch') ?>?preload=${currentToken}`;
            }, 1000);
        }
        
        // Enhanced toast function for better UX
        function showToast(message, type = 'info', duration = 4000) {
            const icon = type === 'success' ? 'success' : 
                        type === 'error' ? 'error' : 
                        type === 'warning' ? 'warning' : 'info';
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: duration,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            } else {
                // Fallback
                alert(message);
            }
        }
    </script>
</body>
</html> 