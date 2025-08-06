<?= $this->include('partials/main') ?>

<head>
    <meta charset="utf-8" />
    <title><?= $this->renderSection('page_title') ?? 'Portal - My Detail Area' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="My Detail Area Portal" name="description" />
    <meta content="Lima Web Studios" name="author" />
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>">

    <?= $this->include('partials/head-css') ?>
    
    <!-- Page specific styles -->
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        
        <?= $this->include('partials/menu') ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
                <div class="main-content">

            <!-- Page Title -->
            <?= $this->include('partials/page-title') ?>

            <!-- Start Page-content -->
            <div class="page-content">
                <div class="container-fluid px-3">
                    <?= $this->renderSection('content') ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?= $this->include('partials/footer') ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- CUSTOMIZER THEME -->
    <?= $this->include('partials/customizer') ?>

    <!-- JAVASCRIPT VENDOR -->
    <?= $this->include('partials/vendor-scripts') ?>
    
    <!-- Alerts System -->
    <?= $this->include('partials/alerts') ?>
    
    <!-- Toast notifications -->
    <?= $this->include('partials/toasts') ?>

    <!-- DataTables Scripts -->
     <?= $this->include('partials/datatables-scripts') ?>

    <!-- Custom scripts for this page -->
    <?= $this->renderSection('scripts') ?>
</body>
</html> 