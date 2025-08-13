<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Protegida - <?= esc($page['title']) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/app.min.css') ?>" rel="stylesheet">
    <!-- Icons -->
    <link href="<?= base_url('assets/css/icons.min.css') ?>" rel="stylesheet">
</head>
<body class="password-protected">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="bx bx-lock-alt display-4 text-warning"></i>
                            <h3 class="mt-3">Página Protegida</h3>
                            <p class="text-muted">Esta página requiere una contraseña para acceder</p>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bx bx-error-circle me-2"></i>
                                <?= esc($error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="page-info mb-4">
                            <h5 class="text-center"><?= esc($page['title']) ?></h5>
                            <?php if (!empty($page['excerpt'])): ?>
                                <p class="text-muted text-center small"><?= esc($page['excerpt']) ?></p>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="<?= current_url() ?>">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-key"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Ingresa la contraseña"
                                           required 
                                           autofocus>
                                    <button type="button" 
                                            class="btn btn-outline-secondary" 
                                            onclick="togglePassword()"
                                            id="toggleBtn">
                                        <i class="bx bx-show" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-log-in me-2"></i>
                                    Acceder
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <a href="<?= base_url() ?>" class="text-muted">
                                <i class="bx bx-arrow-back me-1"></i>
                                Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bx bx-hide';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bx bx-show';
            }
        }

        // Auto focus on password field
        document.getElementById('password').focus();

        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value.trim();
            if (!password) {
                e.preventDefault();
                alert('Por favor ingresa la contraseña');
            }
        });
    </script>

    <style>
        .password-protected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .page-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .input-group .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #004085);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .display-4 {
            font-size: 3.5rem;
        }

        @media (max-width: 576px) {
            .container {
                padding: 1rem;
            }
            
            .card-body {
                padding: 2rem 1.5rem !important;
            }
        }
    </style>
</body>
</html>
