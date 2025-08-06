<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Debug Test' ?></title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f5f6fa;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 12px; 
            padding: 30px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #2c3e50; 
            text-align: center; 
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
        h2 { 
            color: #34495e; 
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        .test-section { 
            margin: 20px 0; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            background: #fafbfc;
        }
        .status-success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
            padding: 10px;
            border-radius: 4px;
            margin: 5px 0;
        }
        .status-error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin: 5px 0;
        }
        .status-warning { 
            background: #fff3cd; 
            color: #856404; 
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 4px;
            margin: 5px 0;
        }
        .info-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 15px 0;
        }
        .info-table th, .info-table td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left;
        }
        .info-table th { 
            background: #f8f9fa; 
            font-weight: 600;
        }
        .info-table tr:nth-child(even) { 
            background: #f9f9f9;
        }
        button { 
            background: #3498db; 
            color: white; 
            border: none; 
            padding: 12px 24px; 
            border-radius: 6px; 
            cursor: pointer; 
            margin: 8px; 
            font-size: 14px;
            transition: all 0.3s ease;
        }
        button:hover { 
            background: #2980b9; 
            transform: translateY(-2px);
        }
        button.success { 
            background: #27ae60;
        }
        button.success:hover { 
            background: #219a52;
        }
        button.warning { 
            background: #f39c12;
        }
        button.warning:hover { 
            background: #e67e22;
        }
        .ajax-test { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0;
            border: 1px solid #e1e8ed;
        }
        .result { 
            margin: 15px 0; 
            padding: 15px; 
            border-radius: 6px; 
            min-height: 40px;
        }
        .response-container { 
            max-height: 300px; 
            overflow-y: auto; 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 6px; 
            border: 1px solid #e9ecef;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }
        pre { 
            white-space: pre-wrap; 
            word-wrap: break-word; 
        }
        .alert { 
            padding: 15px; 
            margin-bottom: 20px; 
            border: 1px solid transparent; 
            border-radius: 6px;
        }
        .alert-success { 
            color: #155724; 
            background-color: #d4edda; 
            border-color: #c3e6cb;
        }
        .alert-danger { 
            color: #721c24; 
            background-color: #f8d7da; 
            border-color: #f5c6cb;
        }
        .form-group { 
            margin-bottom: 15px;
        }
        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: 600;
        }
        .form-group input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-size: 14px;
        }
        .grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 20px;
        }
        @media (max-width: 768px) { 
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        
        <?php if (session('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <h2>📊 System Status</h2>
        
        <!-- Server Info -->
        <div class="test-section">
            <h3>🖥️ Server Information</h3>
            <table class="info-table">
                <tr><th>PHP Version</th><td><?= $tests['server']['php_version'] ?></td></tr>
                <tr><th>Server Software</th><td><?= $tests['server']['server_software'] ?></td></tr>
                <tr><th>Current Time</th><td><?= $tests['server']['current_time'] ?></td></tr>
                <tr><th>Base URL</th><td><?= $tests['server']['base_url'] ?></td></tr>
                <tr><th>Environment</th><td><?= $tests['server']['environment'] ?></td></tr>
            </table>
        </div>

        <!-- Database Info -->
        <div class="test-section">
            <h3>🗄️ Database Connection</h3>
            <?php if ($tests['database']['status'] === 'Connected'): ?>
                <div class="status-success">
                    ✅ Database connected successfully<br>
                    <strong>Database:</strong> <?= $tests['database']['database'] ?>
                </div>
            <?php else: ?>
                <div class="status-error">
                    ❌ Database connection failed<br>
                    <strong>Error:</strong> <?= $tests['database']['error'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Settings Table Info -->
        <div class="test-section">
            <h3>⚙️ Settings Table</h3>
            <?php if ($tests['settings_table']['status'] === 'Exists'): ?>
                <div class="status-success">
                    ✅ Settings table exists<br>
                    <strong>Records:</strong> <?= $tests['settings_table']['count'] ?>
                </div>
            <?php elseif ($tests['settings_table']['status'] === 'Not found'): ?>
                <div class="status-warning">
                    ⚠️ Settings table not found<br>
                    <a href="<?= base_url('debug/create-settings-table') ?>">
                        <button class="warning">Create Settings Table</button>
                    </a>
                </div>
            <?php else: ?>
                <div class="status-error">
                    ❌ Settings table check failed<br>
                    <strong>Error:</strong> <?= $tests['settings_table']['error'] ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Settings Model Info -->
        <div class="test-section">
            <h3>🔧 Settings Model</h3>
            <?php if ($tests['settings_model']['status'] === 'Working'): ?>
                <div class="status-success">
                    ✅ SettingsModel working correctly<br>
                    <strong>Settings loaded:</strong> <?= $tests['settings_model']['settings_count'] ?>
                </div>
                
                <?php if (!empty($tests['settings_model']['sample_settings'])): ?>
                    <h4>Sample Settings:</h4>
                    <table class="info-table">
                        <?php foreach ($tests['settings_model']['sample_settings'] as $key => $value): ?>
                            <tr>
                                <th><?= esc($key) ?></th>
                                <td><?= esc(strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            <?php else: ?>
                <div class="status-error">
                    ❌ SettingsModel error<br>
                    <strong>Error:</strong> <?= $tests['settings_model']['error'] ?>
                </div>
            <?php endif; ?>
        </div>

        <h2>🧪 AJAX Tests</h2>
        
        <div class="grid">
            <!-- Test AJAX Save -->
            <div class="ajax-test">
                <h3>💾 Test AJAX Save Settings</h3>
                <div class="form-group">
                    <label>App Name:</label>
                    <input type="text" id="test_app_name" value="Test Application" />
                </div>
                <div class="form-group">
                    <label>App Description:</label>
                    <input type="text" id="test_app_description" value="Testing AJAX functionality" />
                </div>
                <div class="form-group">
                    <label>App Email:</label>
                    <input type="email" id="test_app_email" value="test@example.com" />
                </div>
                <button onclick="testAjaxSave()">Test Save Settings</button>
                <div id="save-result" class="response-container" style="margin-top: 15px;"></div>
            </div>

            <!-- Test AJAX SMTP -->
            <div class="ajax-test">
                <h3>📧 Test AJAX SMTP</h3>
                <div class="form-group">
                    <label>SMTP Host:</label>
                    <input type="text" id="test_smtp_host" value="smtp.gmail.com" />
                </div>
                <div class="form-group">
                    <label>SMTP Port:</label>
                    <input type="text" id="test_smtp_port" value="587" />
                </div>
                <div class="form-group">
                    <label>SMTP User:</label>
                    <input type="email" id="test_smtp_user" value="test@gmail.com" />
                </div>
                <div class="form-group">
                    <label>SMTP Password:</label>
                    <input type="password" id="test_smtp_pass" value="testpassword" />
                </div>
                <div class="form-group">
                    <label>From Email:</label>
                    <input type="email" id="test_smtp_from" value="test@gmail.com" />
                </div>
                <button onclick="testAjaxSmtp()">Test SMTP Connection</button>
                <div id="smtp-result" class="response-container" style="margin-top: 15px;"></div>
            </div>
        </div>

        <h2>🔗 Quick Links</h2>
        <div class="test-section">
            <a href="<?= base_url() ?>"><button>🏠 Home</button></a>
            <a href="<?= base_url('settings') ?>"><button>⚙️ Settings Page</button></a>
            <a href="<?= base_url('dashboard') ?>"><button>📊 Dashboard</button></a>
            <a href="<?= base_url('debug/create-settings-table') ?>"><button class="warning">🔧 Create Settings Table</button></a>
        </div>
    </div>

    <script>
        const baseUrl = '<?= base_url() ?>';
        
        function testAjaxSave() {
            const resultDiv = document.getElementById('save-result');
            resultDiv.innerHTML = '<p>Testing AJAX save...</p>';
            
            const formData = new URLSearchParams();
            formData.append('app_name', document.getElementById('test_app_name').value);
            formData.append('app_description', document.getElementById('test_app_description').value);
            formData.append('app_email', document.getElementById('test_app_email').value);
            
            fetch(baseUrl + 'debug/test-ajax-save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData.toString()
            })
            .then(response => {
                console.log('Save Response status:', response.status);
                return response.text();
            })
            .then(data => {
                console.log('Save Response data:', data);
                
                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.success) {
                        resultDiv.innerHTML = '<div class="status-success"><strong>✅ Success:</strong> ' + jsonData.message + '<br><pre>' + JSON.stringify(jsonData, null, 2) + '</pre></div>';
                    } else {
                        resultDiv.innerHTML = '<div class="status-error"><strong>❌ Error:</strong> ' + jsonData.message + '</div>';
                    }
                } catch (e) {
                    resultDiv.innerHTML = '<div class="status-error"><strong>❌ JSON Parse Error:</strong> ' + e.message + '<br><strong>Response:</strong><pre>' + data + '</pre></div>';
                }
            })
            .catch(error => {
                console.error('Save Error:', error);
                resultDiv.innerHTML = '<div class="status-error"><strong>❌ Network Error:</strong> ' + error.message + '</div>';
            });
        }
        
        function testAjaxSmtp() {
            const resultDiv = document.getElementById('smtp-result');
            resultDiv.innerHTML = '<p>Testing AJAX SMTP...</p>';
            
            const formData = new URLSearchParams();
            formData.append('smtp_host', document.getElementById('test_smtp_host').value);
            formData.append('smtp_port', document.getElementById('test_smtp_port').value);
            formData.append('smtp_user', document.getElementById('test_smtp_user').value);
            formData.append('smtp_pass', document.getElementById('test_smtp_pass').value);
            formData.append('smtp_from', document.getElementById('test_smtp_from').value);
            formData.append('smtp_encryption', 'tls');
            
            fetch(baseUrl + 'debug/test-ajax-smtp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData.toString()
            })
            .then(response => {
                console.log('SMTP Response status:', response.status);
                return response.text();
            })
            .then(data => {
                console.log('SMTP Response data:', data);
                
                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.success) {
                        resultDiv.innerHTML = '<div class="status-success"><strong>✅ Success:</strong> ' + jsonData.message + '<br><pre>' + JSON.stringify(jsonData, null, 2) + '</pre></div>';
                    } else {
                        resultDiv.innerHTML = '<div class="status-error"><strong>❌ Error:</strong> ' + jsonData.message + '</div>';
                    }
                } catch (e) {
                    resultDiv.innerHTML = '<div class="status-error"><strong>❌ JSON Parse Error:</strong> ' + e.message + '<br><strong>Response:</strong><pre>' + data + '</pre></div>';
                }
            })
            .catch(error => {
                console.error('SMTP Error:', error);
                resultDiv.innerHTML = '<div class="status-error"><strong>❌ Network Error:</strong> ' + error.message + '</div>';
            });
        }
    </script>
</body>
</html> 