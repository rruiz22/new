<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Test - Sales Order System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            Email Configuration Test
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Current SMTP Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-muted mb-3">Current SMTP Settings</h5>
                                <div id="smtp-settings" class="bg-light p-3 rounded">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Email Form -->
                        <form id="emailTestForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-at me-1"></i>
                                        Email Address *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">
                                        <i class="fas fa-heading me-1"></i>
                                        Subject
                                    </label>
                                    <input type="text" class="form-control" id="subject" name="subject" 
                                           value="Test Email from Sales Order System">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label">
                                    <i class="fas fa-message me-1"></i>
                                    Message
                                </label>
                                <textarea class="form-control" id="message" name="message" rows="5">Hello!

This is a test email to verify that the SMTP configuration in your Sales Order System is working correctly.

If you received this email, your email service is properly configured and ready to send order notifications.

Best regards,
Sales Order System</textarea>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary" onclick="testSmtpConfig()">
                                    <i class="fas fa-cog me-1"></i>
                                    Test SMTP Connection
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    Send Test Email
                                </button>
                            </div>
                        </form>

                        <!-- Results -->
                        <div id="test-results" class="mt-4"></div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-4 shadow">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tools me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?= base_url('settings') ?>" class="btn btn-outline-primary w-100 mb-2">
                                    <i class="fas fa-cogs me-1"></i>
                                    Configure SMTP Settings
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?= base_url('sales_orders') ?>" class="btn btn-outline-success w-100 mb-2">
                                    <i class="fas fa-file-invoice me-1"></i>
                                    Back to Sales Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load SMTP settings on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSmtpSettings();
        });

        // Load current SMTP settings
        function loadSmtpSettings() {
            fetch('<?= base_url('email-test/smtp-settings') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displaySmtpSettings(data.settings);
                    } else {
                        document.getElementById('smtp-settings').innerHTML = 
                            '<div class="alert alert-warning">Failed to load SMTP settings</div>';
                    }
                })
                .catch(error => {
                    document.getElementById('smtp-settings').innerHTML = 
                        '<div class="alert alert-danger">Error loading settings: ' + error.message + '</div>';
                });
        }

        // Display SMTP settings
        function displaySmtpSettings(settings) {
            const html = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>SMTP Host:</strong> ${settings.smtp_host || 'Not configured'}<br>
                        <strong>SMTP Port:</strong> ${settings.smtp_port || 'Not configured'}<br>
                        <strong>SMTP User:</strong> ${settings.smtp_user || 'Not configured'}
                    </div>
                    <div class="col-md-6">
                        <strong>SMTP Encryption:</strong> ${settings.smtp_encryption || 'None'}<br>
                        <strong>SMTP Password:</strong> ${settings.smtp_pass || 'Not configured'}<br>
                        <strong>From Email:</strong> ${settings.smtp_from || 'Not configured'}
                    </div>
                </div>
            `;
            document.getElementById('smtp-settings').innerHTML = html;
        }

        // Test SMTP configuration
        function testSmtpConfig() {
            const email = document.getElementById('email').value;
            if (!email) {
                showResult('warning', 'Please enter an email address first');
                return;
            }

            showResult('info', 'Testing SMTP configuration...');

            fetch('<?= base_url('email-test/smtp-config') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'test_email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResult('success', data.message);
                } else {
                    showResult('danger', data.message);
                }
            })
            .catch(error => {
                showResult('danger', 'Error testing SMTP: ' + error.message);
            });
        }

        // Send test email
        document.getElementById('emailTestForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            showResult('info', 'Sending test email...');

            fetch('<?= base_url('email-test') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResult('success', data.message);
                } else {
                    showResult('danger', data.message);
                }
            })
            .catch(error => {
                showResult('danger', 'Error sending email: ' + error.message);
            });
        });

        // Show result message
        function showResult(type, message) {
            const alertClass = `alert alert-${type}`;
            const iconClass = type === 'success' ? 'fas fa-check-circle' : 
                            type === 'danger' ? 'fas fa-exclamation-circle' : 
                            type === 'warning' ? 'fas fa-exclamation-triangle' : 
                            'fas fa-info-circle';

            document.getElementById('test-results').innerHTML = `
                <div class="${alertClass} alert-dismissible fade show" role="alert">
                    <i class="${iconClass} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
    </script>
</body>
</html> 