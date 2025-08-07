<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?= base_url('home') ?>" class="logo logo-dark">
            <span class="logo-sm">
                <span style="font-size: 14px; font-weight: 600; color: #495057;">MDA</span>
            </span>
            <span class="logo-lg">
                <span style="font-size: 16px; font-weight: 600; color: #495057;">My Detail Area</span>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?= base_url('home') ?>" class="logo logo-light">
            <span class="logo-sm">
                <span style="font-size: 14px; font-weight: 600; color: #ffffff;">MDA</span>
            </span>
            <span class="logo-lg">
                <span style="font-size: 16px; font-weight: 600; color: #ffffff;">My Detail Area</span>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">Anna Adame</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
       
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span><?= lang('App.menu') ?></span></li>
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('dashboard') ?>">
                        <i data-feather="home" class="icon-dual"></i> <span><?= lang('App.dashboard') ?></span>
                    </a>
                </li>

                <!-- Todo List -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('todos') ?>">
                        <i data-feather="check-square" class="icon-dual"></i> <span><?= lang('App.todos') ?></span>
                    </a>
                </li>

                <!-- Chat -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('chat') ?>">
                        <i data-feather="message-square" class="icon-dual"></i> <span><?= lang('App.chat') ?></span>
                    </a>
                </li>

                <!-- Sales Orders -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('sales_orders') ?>">
                        <i data-feather="shopping-cart" class="icon-dual"></i> <span><?= lang('App.sales_orders') ?></span>
                    </a>
                </li>

                <!-- Service Orders -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('service_orders') ?>">
                        <i data-feather="settings" class="icon-dual"></i> <span><?= lang('App.service_orders') ?></span>
                    </a>
                </li>

                <!-- Car Wash Orders -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('car_wash') ?>">
                        <i data-feather="truck" class="icon-dual"></i> <span><?= lang('App.car_wash_orders') ?></span>
                    </a>
                </li>

                <!-- Recon Orders -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('recon_orders') ?>">
                        <i data-feather="search" class="icon-dual"></i> <span>Recon Orders</span>
                    </a>
                </li>

                <!-- Vehicles Registry -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('vehicles') ?>">
                        <i data-feather="truck" class="icon-dual"></i> <span><?= lang('App.vehicles') ?></span>
                    </a>
                </li>

                <!-- Clients -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('clients') ?>">
                        <i data-feather="briefcase" class="icon-dual"></i> <span><?= lang('App.clients') ?></span>
                    </a>
                </li>

                <!-- Contacts -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarContacts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarContacts">
                        <i data-feather="users" class="icon-dual"></i> <span><?= lang('App.contacts') ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarContacts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?= base_url('contacts') ?>" class="nav-link">
                                    <i data-feather="list" class="icon-dual-sm"></i> <?= lang('App.contact_list') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('contacts/create') ?>" class="nav-link">
                                    <i data-feather="plus-circle" class="icon-dual-sm"></i> <?= lang('App.create_contact') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('contact-groups') ?>" class="nav-link">
                                    <i data-feather="shield" class="icon-dual-sm"></i> <?= lang('App.manage_contact_groups') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Staff -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarStaff" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarStaff">
                        <i data-feather="user-check" class="icon-dual"></i> <span><?= lang('App.staff') ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarStaff">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?= base_url('staff') ?>" class="nav-link">
                                    <i data-feather="users" class="icon-dual-sm"></i> <?= lang('App.staff_list') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('roles') ?>" class="nav-link">
                                    <i data-feather="shield" class="icon-dual-sm"></i> <?= lang('App.roles') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('staff-roles') ?>" class="nav-link">
                                    <i data-feather="lock" class="icon-dual-sm"></i> <?= lang('App.staff_roles_abp') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Settings -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                        <i data-feather="settings" class="icon-dual"></i> <span><?= lang('App.settings') ?></span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?= base_url('settings') ?>" class="nav-link">
                                    <i data-feather="sliders" class="icon-dual-sm"></i> <?= lang('App.general_settings') ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('integrations') ?>" class="nav-link">
                                    <i data-feather="link" class="icon-dual-sm"></i> <?= lang('App.manage_integrations') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Audit Trail -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="<?= base_url('audit') ?>">
                        <i data-feather="shield" class="icon-dual"></i> <span><?= lang('App.audit_trail') ?></span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>