<?= $this->include('partials/main') ?>

<?php helper('turnstile'); ?>

<head>
    <?php echo view('partials/title-meta', array('title' => lang('Auth.signup'))); ?>
    <?= $this->include('partials/head-css') ?>

    <!-- Cloudflare Turnstile -->
    <?php if (is_turnstile_enabled()): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
    
    <!-- Apple-style Register Styles -->
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
            --warning-color: #FF9500;
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

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            background: var(--gray-50);
        }

        .register-container {
            background: var(--white);
            border-radius: 18px;
            box-shadow: var(--shadow-xl);
            padding: 2rem;
            width: 100%;
            max-width: 420px;
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

        .register-header {
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

        .register-title {
            font-size: 32px;
            font-weight: 600;
            line-height: 1.08349;
            letter-spacing: -0.003em;
            margin: 0 0 6px 0;
            color: var(--text-primary);
        }

        .register-subtitle {
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

        .required {
            color: var(--error-color);
            margin-left: 4px;
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
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(0, 122, 255, 0.1);
            transform: scale(1.02);
        }

        .form-control::placeholder {
            color: var(--text-light);
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

        .form-text {
            font-size: 15px;
            font-weight: 400;
            color: var(--text-secondary);
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.2rem;
            gap: 8px;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            border: 1px solid var(--gray-200);
            border-radius: 4px;
            background: var(--white);
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
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

        .form-check-label a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .form-check-label a:hover {
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
            margin-bottom: 1.5rem;
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

        .login-link {
            text-align: center;
            font-size: 15px;
            font-weight: 400;
            line-height: 1.47059;
            letter-spacing: -0.022em;
            color: var(--text-secondary);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
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
            border: 1px solid;
        }

        .alert-danger {
            background: rgba(255, 59, 48, 0.1);
            color: var(--error-color);
            border-color: rgba(255, 59, 48, 0.2);
        }

        .alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .alert li {
            margin-bottom: 0.25rem;
        }

        .alert li:last-child {
            margin-bottom: 0;
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 8px;
            font-size: 13px;
        }

        .strength-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            margin: 4px 0;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { width: 25%; background: var(--error-color); }
        .strength-fair { width: 50%; background: var(--warning-color); }
        .strength-good { width: 75%; background: var(--success-color); }
        .strength-strong { width: 100%; background: var(--success-color); }

        /* Loading spinner */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Field validation states */
        .form-control.valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 4px rgba(48, 209, 88, 0.1);
        }

        .form-control.invalid {
            border-color: var(--error-color);
            box-shadow: 0 0 0 4px rgba(255, 59, 48, 0.1);
        }

        .field-error {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .field-success {
            color: var(--success-color);
            font-size: 13px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem 1.2rem;
                margin: 1rem;
                border-radius: 16px;
                max-width: none;
            }

            .register-title {
                font-size: 28px;
            }

            .register-subtitle {
                font-size: 15px;
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

        /* Smooth transitions */
        * {
            transition-duration: 0.3s;
            transition-timing-function: ease;
        }
    </style>
</head>

<body>
    <div class="register-wrapper">
        <div class="register-container">
            <!-- Header -->
            <div class="register-header">
                <div class="brand-icon">
                    <i class="ri-user-add-line"></i>
                </div>
                <h1 class="register-title"><?= lang('Auth.create_account', [], 'Create Account') ?></h1>
                <p class="register-subtitle"><?= lang('Auth.get_free_account', [], 'Join us and get started today') ?></p>
                                </div>

            <!-- Alerts -->
                                    <?php if (session()->has('errors')): ?>
                                        <div class="alert alert-danger">
                <ul>
                                                <?php foreach (session('errors') as $error): ?>
                                                    <li><?= $error ?></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    <?php endif ?>
                                    
            <!-- Register Form -->
            <form action="<?= base_url('register') ?>" method="post" id="registerForm">
                                        <?= csrf_field() ?>
                                        
                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">
                        <i class="ri-user-line"></i>Username<span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="username" 
                        name="username" 
                        value="<?= old('username') ?>" 
                        placeholder="Enter your username"
                        required
                        autocomplete="username"
                    >
                                        </div>
                                        
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="ri-mail-line"></i>Email<span class="required">*</span>
                    </label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        value="<?= old('email') ?>" 
                        placeholder="Enter your email"
                        required
                        autocomplete="email"
                    >
                                        </div>
                                        
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="ri-lock-line"></i>Password<span class="required">*</span>
                    </label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Create a strong password"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password', 'passwordIcon')">
                            <i class="ri-eye-line" id="passwordIcon"></i>
                        </button>
                                            </div>
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span id="strengthText">Password strength</span>
                    </div>
                    <div class="form-text">Use at least 8 characters with letters, numbers and symbols</div>
                        </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirm" class="form-label">
                        <i class="ri-lock-2-line"></i>Confirm Password<span class="required">*</span>
                    </label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password_confirm" 
                            name="password_confirm" 
                            placeholder="Confirm your password"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirm', 'confirmPasswordIcon')">
                            <i class="ri-eye-line" id="confirmPasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-check">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="accept-terms" 
                        name="accept-terms" 
                        value="1" 
                        required
                    >
                    <label for="accept-terms" class="form-check-label">
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
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
                        <i class="ri-user-add-line"></i>
                        Create Account
                    </span>
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                Already have an account? 
                <a href="<?= base_url('login') ?>">Sign in here</a>
            </div>
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

        // Password toggle functionality
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'ri-eye-off-line';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'ri-eye-line';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthIndicator = document.getElementById('passwordStrength');
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            if (!password) {
                strengthIndicator.style.display = 'none';
                return;
            }
            
            strengthIndicator.style.display = 'block';
            
            let score = 0;
            let feedback = '';
            
            // Length check
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            
            // Character variety checks
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            // Remove existing classes
            strengthFill.classList.remove('strength-weak', 'strength-fair', 'strength-good', 'strength-strong');
            
            if (score <= 2) {
                strengthFill.classList.add('strength-weak');
                feedback = 'Weak password';
            } else if (score <= 4) {
                strengthFill.classList.add('strength-fair');
                feedback = 'Fair password';
            } else if (score <= 5) {
                strengthFill.classList.add('strength-good');
                feedback = 'Good password';
            } else {
                strengthFill.classList.add('strength-strong');
                feedback = 'Strong password';
            }
            
            strengthText.textContent = feedback;
        }

        // Form validation
        function validateField(field) {
            const value = field.value.trim();
            
            // Clear previous validation
            clearFieldValidation(field);
            
            if (field.hasAttribute('required') && !value) {
                showFieldError(field, 'This field is required');
                return false;
            }
            
            // Specific validations
            if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    showFieldError(field, 'Please enter a valid email address');
                    return false;
                }
            }
            
            if (field.name === 'username' && value) {
                if (value.length < 3) {
                    showFieldError(field, 'Username must be at least 3 characters');
                    return false;
                }
                if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                    showFieldError(field, 'Username can only contain letters, numbers and underscores');
                    return false;
                }
            }
            
            if (field.name === 'password' && value) {
                if (value.length < 8) {
                    showFieldError(field, 'Password must be at least 8 characters');
                    return false;
                }
            }
            
            if (field.name === 'password_confirm' && value) {
                const password = document.getElementById('password').value;
                if (value !== password) {
                    showFieldError(field, 'Passwords do not match');
                    return false;
                }
            }
            
            showFieldSuccess(field);
            return true;
        }

        function showFieldError(field, message) {
            field.classList.add('invalid');
            field.classList.remove('valid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.innerHTML = `<i class="ri-error-warning-line"></i>${message}`;
            
            field.parentNode.appendChild(errorDiv);
        }

        function showFieldSuccess(field) {
            field.classList.add('valid');
            field.classList.remove('invalid');
        }

        function clearFieldValidation(field) {
            field.classList.remove('valid', 'invalid');
            
            const errorDiv = field.parentNode.querySelector('.field-error');
            if (errorDiv) {
                errorDiv.remove();
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirm');
            
            // Password strength checking
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                validateField(this);
            });
            
            // Real-time validation
            form.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('invalid')) {
                        validateField(this);
                    }
                    
                    // Real-time password confirmation check
                    if (this.name === 'password_confirm' || this.name === 'password') {
                        setTimeout(() => {
                            if (confirmPasswordInput.value) {
                                validateField(confirmPasswordInput);
                            }
                        }, 100);
                    }
                });
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                const btnText = document.getElementById('btnText');
                const isTurnstileEnabled = <?= is_turnstile_enabled() ? 'true' : 'false' ?>;
                
                // Prevent submission if Turnstile is enabled but not verified
                if (isTurnstileEnabled && !turnstileVerified) {
                    e.preventDefault();
                    document.getElementById('turnstile-error').style.display = 'block';
                    return false;
                }
                
                // Validate all fields
                let isValid = true;
                form.querySelectorAll('.form-control').forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });
                
                // Check terms acceptance
                const termsCheckbox = document.getElementById('accept-terms');
                if (!termsCheckbox.checked) {
                    alert('Please accept the Terms of Service and Privacy Policy');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    return;
                }
                
                submitBtn.disabled = true;
                btnText.innerHTML = '<div class="spinner"></div> Creating account...';
            });
            
            // Auto-focus first input
            const firstInput = document.getElementById('username');
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

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && document.activeElement.type !== 'submit' && document.activeElement.type !== 'checkbox') {
                e.preventDefault();
                
                const form = document.getElementById('registerForm');
                const inputs = form.querySelectorAll('.form-control');
                const currentIndex = Array.from(inputs).indexOf(document.activeElement);
                
                if (currentIndex >= 0 && currentIndex < inputs.length - 1) {
                    inputs[currentIndex + 1].focus();
                } else {
                    // Focus on terms checkbox if not checked
                    const termsCheckbox = document.getElementById('accept-terms');
                    if (!termsCheckbox.checked) {
                        termsCheckbox.focus();
                    }
                }
            }
        });
    </script>
</body>

</html> 