<?= $this->include('partials/main') ?>

<?php helper('turnstile'); ?>

<head>
    <?php echo view('partials/title-meta', array('title' => lang('Auth.signin'))); ?>
    <?= $this->include('partials/head-css') ?>
    
    <!-- Cloudflare Turnstile -->
    <?php if (is_turnstile_enabled()): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
    
    <!-- Apple-style Login Styles -->
    <style>
        :root {
            --primary-color: #007AFF;
            --primary-dark: #0056CC;
            --secondary-color: #f5f5f7;
            --accent-color: #30D158;
            --text-primary: #1d1d1f;
            --text-secondary: #86868b;
            --text-light: #a1a1a6;
            --white: #ffffff;
            --gray-50: #f5f5f7;
            --gray-100: #f2f2f7;
            --gray-200: #e5e5ea;
            --gray-800: #1d1d1f;
            --gray-900: #000000;
            --success-color: #30D158;
            --error-color: #FF3B30;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
            --shadow-lg: 0 8px 25px rgba(0,0,0,0.15);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            font-size: 17px;
        }

        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.47059;
            color: var(--text-primary);
            background: var(--gray-50);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            min-height: 100vh;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            background: var(--gray-50);
        }

        .login-container {
            background: var(--white);
            border-radius: 18px;
            box-shadow: var(--shadow-xl);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease-out;
            border: 1px solid var(--gray-200);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .brand-icon:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        .brand-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .login-title {
            font-size: 32px;
            font-weight: 600;
            line-height: 1.08349;
            letter-spacing: -0.003em;
            margin: 0 0 6px 0;
            color: var(--text-primary);
        }

        .login-subtitle {
            font-size: 17px;
            font-weight: 400;
            line-height: 1.42105;
            letter-spacing: 0.012em;
            color: var(--text-secondary);
            margin: 0 0 1.8rem 0;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-label {
            display: block;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.47059;
            letter-spacing: -0.022em;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .form-label i {
            color: var(--text-secondary);
            margin-right: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            font-size: 16px;
            font-weight: 400;
            background: var(--white);
            color: var(--text-primary);
            transition: all 0.3s ease;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            transform: scale(1.02);
        }

        .form-control::placeholder {
            color: var(--text-light);
        }

        .form-text {
            font-size: 15px;
            font-weight: 400;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: var(--gray-50);
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
            margin-right: 8px;
            background: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .form-check-label {
            font-size: 15px;
            font-weight: 400;
            line-height: 1.47059;
            letter-spacing: -0.022em;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.47059;
            letter-spacing: -0.022em;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            transform: scale(1.02);
        }

        .submit-btn {
            width: 100%;
            padding: 14px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 48px;
        }

        .submit-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            margin: 1.5rem 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .register-link {
            text-align: center;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.47059;
            letter-spacing: -0.022em;
            color: var(--text-secondary);
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: var(--primary-dark);
            transform: scale(1.02);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.47059;
            letter-spacing: -0.022em;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid;
        }

        .alert-danger {
            background: rgba(255, 59, 48, 0.1);
            color: var(--error-color);
            border-color: rgba(255, 59, 48, 0.2);
        }

        .alert-success {
            background: rgba(48, 209, 88, 0.1);
            color: var(--success-color);
            border-color: rgba(48, 209, 88, 0.2);
        }

        /* Loading spinner */
        .spinner {
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            html {
                font-size: 16px;
            }

            .login-container {
                padding: 2rem;
                margin: 1rem;
                border-radius: 16px;
                max-width: 100%;
            }

            .login-title {
                font-size: 32px;
            }

            .login-subtitle {
                font-size: 17px;
            }

            .form-control {
                font-size: 16px;
            }

            .submit-btn {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 0.5rem;
            }

            .brand-icon {
                width: 50px;
                height: 50px;
            }

            .brand-icon i {
                font-size: 1.5rem;
            }
        }

        /* Focus styles for accessibility */
        .form-control:focus,
        .submit-btn:focus,
        .form-check-input:focus,
        .password-toggle:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Header -->
            <div class="login-header">
                <div class="brand-icon">
                    <i class="ri-user-line"></i>
                </div>
                <h1 class="login-title"><?= lang('Auth.welcome_back', [], 'Welcome back') ?></h1>
                <p class="login-subtitle"><?= lang('Auth.signin_continue', [], 'Please sign in to your account') ?></p>
            </div>

            <!-- Alerts -->
            <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <i class="ri-error-warning-line"></i>
                <?= session('error') ?>
            </div>
            <?php endif ?>
            
            <?php if (session()->has('message')): ?>
            <div class="alert alert-success">
                <i class="ri-checkbox-circle-line"></i>
                <?= session('message') ?>
            </div>
            <?php endif ?>

            <!-- Login Form -->
            <form action="<?= base_url('login') ?>" method="post" id="loginForm">
                <?= csrf_field() ?>
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="username_email" class="form-label">
                        <i class="ri-user-line"></i>Username or Email
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username_email" 
                        name="username_email" 
                        value="<?= old('username_email') ?>" 
                        placeholder="Enter username or email address"
                        required
                        autocomplete="username"
                    >
                    
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="ri-lock-line"></i>Password
                    </label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="ri-eye-line" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot -->
                <div class="form-row">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Turnstile Widget -->
                <?php if (is_turnstile_enabled()): ?>
                <div class="turnstile-container" style="margin-bottom: 1.5rem;">
                    <div class="cf-turnstile" 
                         data-sitekey="<?= get_turnstile_site_key() ?>" 
                         data-theme="light"
                         data-size="normal"
                         data-callback="onTurnstileSuccess"
                         data-error-callback="onTurnstileError">
                    </div>
                    <div id="turnstile-error" class="field-error" style="display: none;">
                        <i class="ri-error-warning-line"></i>
                        <span>Security verification failed. Please try again.</span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn" id="submitBtn" disabled>
                    <span id="btnText">
                        <i class="ri-login-circle-line"></i>
                        Sign in
                    </span>
                </button>
            </form>

            
        </div>
    </div>

    <?= $this->include('partials/vendor-scripts') ?>

    <script>
        // Turnstile callback functions
        let turnstileVerified = false;
        
        function onTurnstileSuccess(token) {
            turnstileVerified = true;
            document.getElementById('turnstile-error').style.display = 'none';
            updateSubmitButton();
        }
        
        function onTurnstileError() {
            turnstileVerified = false;
            document.getElementById('turnstile-error').style.display = 'block';
            updateSubmitButton();
        }
        
        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitBtn');
            const isTurnstileEnabled = <?= is_turnstile_enabled() ? 'true' : 'false' ?>;
            
            if (isTurnstileEnabled) {
                submitBtn.disabled = !turnstileVerified;
                if (turnstileVerified) {
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                } else {
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.cursor = 'not-allowed';
                }
            } else {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        }

        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'ri-eye-off-line';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'ri-eye-line';
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const isTurnstileEnabled = <?= is_turnstile_enabled() ? 'true' : 'false' ?>;
            
            // Prevent submission if Turnstile is enabled but not verified
            if (isTurnstileEnabled && !turnstileVerified) {
                e.preventDefault();
                document.getElementById('turnstile-error').style.display = 'block';
                return false;
            }
            
            // Validate form fields
            const usernameEmail = document.getElementById('username_email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!usernameEmail || !password) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            submitBtn.disabled = true;
            btnText.innerHTML = '<div class="spinner"></div> Signing in...';
        });

        // Enhanced form validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            
            if (field.hasAttribute('required') && !value) {
                showFieldError(field, 'This field is required');
                return false;
            }
            
            if (field.name === 'username_email' && value) {
                // Allow both username and email formats
                const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                const isUsername = /^[a-zA-Z0-9_]{3,}$/.test(value);
                
                if (!isEmail && !isUsername) {
                    // Provide more specific feedback
                    if (value.includes('@')) {
                        showFieldError(field, 'Please enter a valid email address');
                    } else {
                        showFieldError(field, 'Username must be at least 3 characters (letters, numbers, underscore)');
                    }
                    return false;
                }
            }
            
            clearFieldError(field);
            return true;
        }

        function showFieldError(field, message) {
            clearFieldError(field);
            field.classList.add('error');
            field.style.borderColor = 'var(--error-color)';
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.style.cssText = 'color: var(--error-color); font-size: 0.75rem; margin-top: 0.25rem;';
            errorDiv.textContent = message;
            
            field.parentNode.appendChild(errorDiv);
        }

        function clearFieldError(field) {
            field.classList.remove('error');
            field.style.borderColor = '';
            
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && document.activeElement.type !== 'submit') {
                e.preventDefault();
                
                const form = document.getElementById('loginForm');
                const inputs = form.querySelectorAll('.form-control');
                const currentIndex = Array.from(inputs).indexOf(document.activeElement);
                
                if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
                    inputs[currentIndex + 1].focus();
                } else {
                    form.submit();
                }
            }
        });

        // Auto-focus first input and initialize submit button state
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('.form-control');
            if (firstInput && !firstInput.value) {
                setTimeout(() => firstInput.focus(), 100);
            }
            
            // Initialize submit button state
            updateSubmitButton();
            
            // Enable submit button if Turnstile is disabled
            const isTurnstileEnabled = <?= is_turnstile_enabled() ? 'true' : 'false' ?>;
            if (!isTurnstileEnabled) {
                turnstileVerified = true;
                updateSubmitButton();
            }
        });
    </script>
</body>

</html> 