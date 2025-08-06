<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid NFC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .error-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            max-width: 400px;
            margin: 20px;
            overflow: hidden;
        }
        
        .error-header {
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        
        .error-body {
            padding: 30px;
            text-align: center;
        }
        
        .btn-outline-primary {
            border-color: #405189;
            color: #405189;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: #405189;
            border-color: #405189;
            transform: translateY(-1px);
        }
        
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            transition: all 0.3s ease;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            transform: translateY(-1px);
        }
        
        .d-grid.gap-2 > button {
            padding: 12px 24px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-header">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem; margin-bottom: 15px;"></i>
            <h1 class="h4 mb-0">Invalid NFC</h1>
        </div>
        
        <div class="error-body">
            <p class="mb-4 text-muted">
                The NFC code you entered is invalid, expired, or doesn't exist. 
                Please verify the NFC code or contact your administrator for assistance.
            </p>
            
            <div class="d-grid gap-2">
                <button class="btn btn-outline-danger" onclick="closeCurrentTab()">
                    <i class="bi bi-x-circle me-2"></i>
                    Close Tab
                </button>
                <button class="btn btn-outline-primary" onclick="tryBatchTracker()">
                    <i class="bi bi-phone-vibrate me-2"></i>
                    Try Batch NFC Tracker
                </button>
            </div>
        </div>
    </div>

    <script>
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
                    alert('Please close this tab manually.');
                }, 1000);
                
            } catch (e) {
                console.log('Cannot close tab automatically:', e);
                alert('Please close this tab manually.');
            }
        }

        function tryBatchTracker() {
            // Open batch NFC tracker and close current error tab
            try {
                window.open('<?= base_url('location/batch') ?>', '_blank');
                closeCurrentTab();
            } catch (e) {
                // Fallback: navigate to batch NFC tracker
                window.location.href = '<?= base_url('location/batch') ?>';
            }
        }
    </script>
</body>
</html> 