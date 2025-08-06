<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= lang('App.contact_details') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= lang('App.contact_details') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?><?= lang('App.contacts') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-4 col-xl-3">
        <!-- Contact Profile Card -->
        <div class="card text-center">
            <div class="card-body">
                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                    <div class="avatar-lg">
                        <div class="avatar-title rounded-circle bg-light text-primary border border-dashed border-primary">
                            <i class="ri-user-3-line fs-1"></i>
                        </div>
                    </div>
                    <?php if ($contact['status'] === 'active'): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            <i class="ri-check-line"></i>
                        </span>
                    <?php else: ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <i class="ri-close-line"></i>
                        </span>
                    <?php endif; ?>
                </div>
                
                <h5 class="fs-16 mb-1"><?= esc($contact['name']) ?></h5>
                <p class="text-muted mb-2"><?= esc($contact['position'] ?: 'Sin posición especificada') ?></p>
                
                <?php if ($contact['is_primary'] == 1): ?>
                    <span class="badge bg-primary-subtle text-primary mb-3">
                        <i class="ri-star-fill me-1"></i><?= lang('App.primary_contact') ?>
                    </span>
                <?php else: ?>
                    <span class="badge bg-light text-muted mb-3">
                        <i class="ri-user-line me-1"></i>Contacto Secundario
                    </span>
                <?php endif; ?>
                
                <div class="hstack gap-2 justify-content-center">
                    <a href="<?= base_url("contacts/edit/{$contact['id']}") ?>" class="btn btn-primary">
                        <i class="ri-edit-line align-bottom me-1"></i> <?= lang('App.edit') ?>
                    </a>
                    <a href="<?= base_url('contacts') ?>" class="btn btn-soft-success">
                        <i class="ri-arrow-left-line align-bottom me-1"></i> <?= lang('App.back') ?>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if (!empty($contact['email'])): ?>
                        <a href="mailto:<?= esc($contact['email']) ?>" class="btn btn-soft-primary">
                            <i class="ri-mail-line me-2"></i>Enviar Email
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact['phone'])): ?>
                        <a href="tel:<?= esc($contact['phone']) ?>" class="btn btn-soft-success">
                            <i class="ri-phone-line me-2"></i>Llamar
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($client) && !empty($client['id'])): ?>
                        <a href="<?= base_url("clients/{$client['id']}") ?>" class="btn btn-soft-info">
                            <i class="ri-building-line me-2"></i>Ver Cliente
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8 col-xl-9">
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header border-bottom-dashed">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i class="ri-information-line text-primary me-2"></i>Información del Contacto
                    </h5>
                    <div class="flex-shrink-0">
                        <?php if ($contact['status'] === 'active'): ?>
                            <span class="badge bg-success-subtle text-success fs-12">
                                <i class="ri-check-circle-fill me-1"></i><?= lang('App.active') ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger fs-12">
                                <i class="ri-close-circle-fill me-1"></i><?= lang('App.inactive') ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Personal Information -->
                    <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-primary mb-3">
                                <i class="ri-user-line me-2"></i>Información Personal
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium text-muted" style="width: 30%;">
                                                <i class="ri-user-3-line text-primary me-2"></i>Nombre:
                                            </td>
                                            <td class="fw-semibold"><?= esc($contact['name']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium text-muted">
                                                <i class="ri-briefcase-line text-warning me-2"></i>Posición:
                                            </td>
                                            <td><?= esc($contact['position'] ?: '---') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium text-muted">
                                                <i class="ri-user-star-line text-info me-2"></i>Username:
                                            </td>
                                            <td>
                                                <?php if (!empty($contact['username'])): ?>
                                                    <span class="badge bg-light text-dark border fs-5">
                                                        <i class="ri-at-line me-1"></i><?= esc($contact['username']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">---</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium text-muted">
                                                <i class="ri-star-line text-success me-2"></i>Tipo:
                                            </td>
                                            <td>
                                                <?php if ($contact['is_primary'] == 1): ?>
                                                    <span class="badge bg-primary-subtle text-primary">
                                                        <i class="ri-star-fill me-1"></i>Contacto Principal
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-muted">
                                                        <i class="ri-user-line me-1"></i>Contacto Secundario
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="col-lg-6">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-primary mb-3">
                                <i class="ri-contacts-line me-2"></i>Información de Contacto
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium text-muted" style="width: 30%;">
                                                <i class="ri-mail-line text-primary me-2"></i>Email:
                                            </td>
                                            <td>
                                                <?php if (!empty($contact['email'])): ?>
                                                    <a href="mailto:<?= esc($contact['email']) ?>" class="text-decoration-none">
                                                        <?= esc($contact['email']) ?>
                                                        <i class="ri-external-link-line ms-1 text-muted"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">---</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium text-muted">
                                                <i class="ri-phone-line text-success me-2"></i>Teléfono:
                                            </td>
                                            <td>
                                                <?php if (!empty($contact['phone'])): ?>
                                                    <a href="tel:<?= esc($contact['phone']) ?>" class="text-decoration-none">
                                                        <?= esc($contact['phone']) ?>
                                                        <i class="ri-external-link-line ms-1 text-muted"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">---</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium text-muted">
                                                <i class="ri-shield-check-line text-info me-2"></i>Estado:
                                            </td>
                                            <td>
                                                <?php if ($contact['status'] === 'active'): ?>
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="ri-check-circle-fill me-1"></i><?= lang('App.active') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="ri-close-circle-fill me-1"></i><?= lang('App.inactive') ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Company Information -->
        <div class="card">
            <div class="card-header border-bottom-dashed">
                <h5 class="card-title mb-0">
                    <i class="ri-building-line text-primary me-2"></i>Información de la Empresa
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($client) && !empty($client['id'])): ?>
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title rounded bg-primary-subtle text-primary">
                                    <i class="ri-building-2-line fs-17"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">
                                <a href="<?= base_url("clients/{$client['id']}") ?>" class="text-decoration-none">
                                    <?= esc($client['name']) ?>
                                    <i class="ri-external-link-line ms-1 text-muted fs-12"></i>
                                </a>
                            </h6>
                            <p class="text-muted mb-2">Cliente asociado</p>
                            
                            <?php if (!empty($client['industry'])): ?>
                                <div class="mb-2">
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="ri-price-tag-3-line me-1"></i><?= esc($client['industry']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="hstack gap-2">
                                <a href="<?= base_url("clients/{$client['id']}") ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-eye-line me-1"></i>Ver Cliente
                                </a>
                                <?php if (!empty($client['website'])): ?>
                                    <a href="<?= esc($client['website']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="ri-global-line me-1"></i>Sitio Web
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <div class="avatar-lg mb-3">
                                <div class="avatar-title rounded bg-light text-muted">
                                    <i class="ri-building-2-line fs-24"></i>
                                </div>
                            </div>
                            <h6 class="text-muted mb-1">Sin Cliente Asociado</h6>
                            <p class="text-muted mb-0">Este contacto no tiene un cliente asociado</p>
                        </div>
                    </div>
                <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Groups Information -->
        <div class="card">
            <div class="card-header border-bottom-dashed">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i class="ri-group-line text-primary me-2"></i>Grupos de Acceso
                    </h5>
                    <div class="flex-shrink-0">
                        <span class="badge bg-info-subtle text-info fs-12">
                            <i class="ri-shield-user-line me-1"></i><?= count($contact_groups) ?> Grupo<?= count($contact_groups) != 1 ? 's' : '' ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($contact_groups)): ?>
                    <div class="row g-3">
                        <?php foreach ($contact_groups as $group): ?>
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100 group-card" data-group-color="<?= esc($group['color']) ?>">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm">
                                                <div class="avatar-title rounded-circle" style="background-color: <?= esc($group['color']) ?>15; color: <?= esc($group['color']) ?>;">
                                                    <i class="ri-<?= esc($group['icon']) ?>-line fs-16"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1" style="color: <?= esc($group['color']) ?>;">
                                                <a href="<?= base_url("contact-groups/{$group['id']}") ?>" class="text-decoration-none" style="color: inherit;">
                                                    <?= esc($group['name']) ?>
                                                    <i class="ri-external-link-line ms-1 text-muted fs-12"></i>
                                                </a>
                                            </h6>
                                            
                                            <?php if (!empty($group['description'])): ?>
                                                <p class="text-muted mb-2 fs-13"><?= esc($group['description']) ?></p>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span class="badge" style="background-color: <?= esc($group['color']) ?>20; color: <?= esc($group['color']) ?>; border: 1px solid <?= esc($group['color']) ?>40;">
                                                    <i class="ri-shield-check-line me-1"></i>Acceso Activo
                                                </span>
                                                
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="ri-more-line"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url("contact-groups/{$group['id']}") ?>">
                                                                <i class="ri-eye-line me-2"></i>Ver Grupo
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="<?= base_url("contacts/edit/{$contact['id']}") ?>">
                                                                <i class="ri-settings-line me-2"></i>Gestionar Grupos
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">Gestión de Grupos</h6>
                                <p class="text-muted mb-0 fs-13">Administra los grupos y permisos de acceso de este contacto</p>
                            </div>
                            <div class="hstack gap-2">
                                <a href="<?= base_url("contacts/edit/{$contact['id']}") ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-settings-line me-1"></i>Gestionar Grupos
                                </a>
                                <a href="<?= base_url('contact-groups') ?>" class="btn btn-sm btn-outline-info">
                                    <i class="ri-group-line me-1"></i>Ver Todos los Grupos
                                </a>
                            </div>
                        </div>
                    </div>
                    
                <?php else: ?>
                    <div class="text-center py-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title rounded-circle bg-light text-muted">
                                <i class="ri-group-line fs-24"></i>
                            </div>
                        </div>
                        <h6 class="mb-2">Sin Grupos Asignados</h6>
                        <p class="text-muted mb-3">Este contacto no está asignado a ningún grupo de acceso.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <a href="<?= base_url("contacts/edit/{$contact['id']}") ?>" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i>Asignar a Grupo
                            </a>
                            <a href="<?= base_url('contact-groups') ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="ri-group-line me-1"></i>Ver Grupos Disponibles
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Account Information -->
        <?php if (!empty($contact['user_id'])): ?>
        <div class="card">
            <div class="card-header border-bottom-dashed">
                <h5 class="card-title mb-0">
                    <i class="ri-account-circle-line text-primary me-2"></i>Información de Cuenta de Usuario
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 rounded">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="ri-information-line fs-16"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-1">Cuenta de Usuario Activa</h6>
                            <p class="mb-0">Este contacto tiene una cuenta de usuario activa en el sistema y puede acceder al portal de clientes.</p>
                        </div>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="border rounded p-3 text-center">
                            <i class="ri-user-settings-line text-primary fs-20 mb-2"></i>
                            <h6 class="mb-1">ID de Usuario</h6>
                            <p class="text-muted mb-0">#<?= esc($contact['user_id']) ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border rounded p-3 text-center">
                            <i class="ri-shield-user-line text-success fs-20 mb-2"></i>
                            <h6 class="mb-1">Tipo de Cuenta</h6>
                            <span class="badge bg-success-subtle text-success">Cliente</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.profile-user .badge {
    font-size: 0.6rem;
    padding: 0.25rem 0.4rem;
}

.card .border {
    border-color: var(--vz-border-color) !important;
}

.table-borderless td {
    padding: 0.5rem 0.75rem;
    border: none;
    vertical-align: middle;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
}

.fs-12 {
    font-size: 0.75rem;
}

.fs-13 {
    font-size: 0.8125rem;
}

.fs-16 {
    font-size: 1rem;
}

.fs-17 {
    font-size: 1.0625rem;
}

.fs-24 {
    font-size: 1.5rem;
}

.badge {
    font-weight: 500;
}

.text-decoration-none:hover {
    text-decoration: underline !important;
}

.alert-info {
    background-color: rgba(13, 202, 240, 0.1);
    color: #0dcaf0;
}

.card-header {
    background-color: rgba(var(--vz-primary-rgb), 0.025);
}

/* Group Cards Styling */
.group-card {
    border: 2px solid transparent !important;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.group-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.group-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--group-color, #3577f1), var(--group-color-light, #3577f140));
}

.group-card[data-group-color] {
    --group-color: attr(data-group-color);
    --group-color-light: attr(data-group-color)40;
}

.group-card:hover {
    border-color: var(--group-color, #3577f1) !important;
}

.dropdown-menu {
    border: 1px solid rgba(var(--vz-border-color-rgb), 0.15);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.dropdown-item {
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: rgba(var(--vz-primary-rgb), 0.1);
    color: var(--vz-primary);
}

/* Badge animations */
.badge {
    transition: all 0.2s ease;
}

.group-card:hover .badge {
    transform: scale(1.05);
}

/* Empty state styling */
.text-center .avatar-lg {
    transition: transform 0.3s ease;
}

.text-center:hover .avatar-lg {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .hstack {
        flex-direction: column !important;
        gap: 0.5rem !important;
    }
    
    .btn {
        width: 100%;
    }
    
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .group-card {
        margin-bottom: 0.5rem;
    }
}

/* Improved button styling */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn-outline-secondary:hover {
    background-color: var(--vz-secondary);
    border-color: var(--vz-secondary);
    color: white;
}

/* Card hover effects enhancement */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

/* Avatar enhancements */
.avatar-sm, .avatar-lg {
    transition: transform 0.2s ease;
}

.group-card:hover .avatar-sm {
    transform: scale(1.1);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth hover effects
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.transition = 'transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out';
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
    });
    
    // Add loading animation to action buttons
    const actionButtons = document.querySelectorAll('a[href^="mailto:"], a[href^="tel:"]');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.add('ri-loader-4-line');
                icon.style.animation = 'spin 1s linear infinite';
                
                setTimeout(() => {
                    icon.style.animation = '';
                    icon.classList.remove('ri-loader-4-line');
                }, 1000);
            }
        });
    });
    
    // Enhanced group cards functionality
    initializeGroupCards();
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Group cards initialization
function initializeGroupCards() {
    const groupCards = document.querySelectorAll('.group-card');
    
    groupCards.forEach(card => {
        const groupColor = card.getAttribute('data-group-color');
        
        if (groupColor) {
            // Set CSS custom properties for dynamic theming
            card.style.setProperty('--group-color', groupColor);
            card.style.setProperty('--group-color-light', groupColor + '40');
            card.style.setProperty('--group-color-bg', groupColor + '15');
            
            // Add border top gradient
            card.style.borderTop = `3px solid ${groupColor}`;
            
            // Enhanced hover effects
            card.addEventListener('mouseenter', function() {
                this.style.borderColor = groupColor;
                this.style.boxShadow = `0 8px 25px ${groupColor}30`;
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.borderColor = 'transparent';
                this.style.boxShadow = '';
            });
        }
        
        // Add click analytics (optional)
        card.addEventListener('click', function(e) {
            // Only track if clicking on the card itself, not buttons
            if (e.target === this || e.target.closest('.group-card') === this) {
                console.log('Group card clicked:', card.querySelector('h6').textContent.trim());
            }
        });
    });
    
    // Add badge hover effects
    const groupBadges = document.querySelectorAll('.group-card .badge');
    groupBadges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Add group management quick actions
function addToGroup(groupId, groupName) {
    // This could be enhanced with AJAX functionality
    console.log(`Adding contact to group: ${groupName} (ID: ${groupId})`);
    
    // Show loading state
    showNotification('success', `Contacto agregado al grupo "${groupName}" exitosamente.`);
}

function removeFromGroup(groupId, groupName) {
    if (confirm(`¿Estás seguro de que quieres remover este contacto del grupo "${groupName}"?`)) {
        console.log(`Removing contact from group: ${groupName} (ID: ${groupId})`);
        showNotification('info', `Contacto removido del grupo "${groupName}".`);
    }
}

// Simple notification system
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .group-card {
        animation: fadeInUp 0.6s ease-out;
    }
    
    .group-card:nth-child(even) {
        animation-delay: 0.1s;
    }
`;
document.head.appendChild(style);
</script>
<?= $this->endSection() ?>
