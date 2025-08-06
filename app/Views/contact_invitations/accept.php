<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Aceptar Invitación<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Aceptar Invitación<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Aceptar Invitación<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white text-center">
                    <h4 class="mb-0">
                        <i data-feather="mail" class="me-2"></i>
                        ¡Bienvenido!
                    </h4>
                    <p class="mb-0 mt-2">Completa tu registro para unirte como contacto</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Invitation Details -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <?php if (!empty($invitation['group_name'])): ?>
                            <div class="group-color-indicator me-2" 
                                 style="width: 20px; height: 20px; border-radius: 50%; background-color: <?= esc($invitation['group_color'] ?? '#3577f1') ?>; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);"></div>
                            <i data-feather="<?= esc($invitation['group_icon'] ?? 'users') ?>" class="me-2" style="color: <?= esc($invitation['group_color'] ?? '#3577f1') ?>;"></i>
                        <?php else: ?>
                            <i data-feather="user-check" class="me-2 text-info"></i>
                        <?php endif; ?>
                        <div>
                            <strong>Invitado por:</strong> <?= esc($invitation['sender_first_name'] . ' ' . $invitation['sender_last_name']) ?>
                            <?php if (!empty($invitation['group_name'])): ?>
                                <br><strong>Grupo asignado:</strong> <span class="text-primary"><?= esc($invitation['group_name']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($invitation['client_name'])): ?>
                                <br><strong>Cliente:</strong> <span class="text-success"><?= esc($invitation['client_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($invitation['message'])): ?>
                        <div class="alert alert-light border-start border-4 border-primary">
                            <h6 class="text-primary mb-2">📝 Mensaje personal:</h6>
                            <p class="mb-0"><?= nl2br(esc($invitation['message'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Registration Form -->
                    <?= form_open('invitations/process-acceptance', ['id' => 'acceptanceForm']) ?>
                        <?= csrf_field() ?>
                        <input type="hidden" name="token" value="<?= esc($token) ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?= old('first_name', $invitation['first_name'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?= old('last_name', $invitation['last_name'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= esc($invitation['email']) ?>" readonly>
                            <div class="form-text">Este email no se puede cambiar</div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de usuario <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username') ?>" required>
                            <div class="form-text">Será usado para iniciar sesión en el sistema</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i data-feather="eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                            <i data-feather="eye" id="confirm_password-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i data-feather="info" class="me-2"></i>
                            <strong>Importante:</strong> Al aceptar esta invitación, tendrás acceso al sistema con los permisos asignados a tu grupo.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i data-feather="check-circle" class="me-2"></i>
                                Crear Cuenta y Unirse
                            </button>
                            <a href="<?= base_url('login') ?>" class="btn btn-link text-center">
                                ¿Ya tienes una cuenta? Inicia sesión
                            </a>
                        </div>
                    <?= form_close() ?>
                </div>

                <div class="card-footer text-center text-muted">
                    <small>
                        Esta invitación expira el <?= date('d/m/Y a las H:i', strtotime($invitation['expires_at'])) ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Auto-generate username from name
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const usernameInput = document.getElementById('username');

    function generateUsername() {
        const firstName = firstNameInput.value.toLowerCase().replace(/[^a-z]/g, '');
        const lastName = lastNameInput.value.toLowerCase().replace(/[^a-z]/g, '');
        
        if (firstName && lastName) {
            usernameInput.value = firstName + lastName;
        } else if (firstName) {
            usernameInput.value = firstName;
        }
    }

    firstNameInput.addEventListener('blur', generateUsername);
    lastNameInput.addEventListener('blur', generateUsername);

    // Password validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    function validatePasswords() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.setAttribute('data-feather', 'eye-off');
    } else {
        field.type = 'password';
        icon.setAttribute('data-feather', 'eye');
    }
    
    // Refresh feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}
</script>
<?= $this->endSection() ?> 