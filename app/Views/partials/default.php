<?= $this->include('partials/main') ?>

<head>
    <meta charset="utf-8" />
    <title><?= $this->renderSection('page_title') ?? 'Portal - My Detail Area' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="My Detail Area Portal" name="description" />
    <meta content="Lima Web Studios" name="author" />
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <meta name="base-url" content="<?= base_url() ?>">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>">

    <?= $this->include('partials/head-css') ?>
    
    <!-- Page specific styles -->
    <?= $this->renderSection('styles') ?>
</head>

<body class="mda-theme">
    <!-- Begin page -->
    <div id="layout-wrapper">
        
        <?= $this->include('partials/menu') ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
                <div class="main-content">

            <!-- Page Title -->
             

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
    
    <!-- Keyboard Shortcuts -->
    <script src="<?= base_url('assets/js/keyboard-shortcuts.js') ?>"></script>
    
    <!-- Alerts System -->
    <?= $this->include('partials/alerts') ?>
    
    <!-- Toast notifications -->
    <?= $this->include('partials/toasts') ?>
    
    <!-- Global Sales Order Modal -->
    <?= $this->include('partials/global_sales_order_modal') ?>
    

    <!-- DataTables Scripts -->
     <?= $this->include('partials/datatables-scripts') ?>
     
    <!-- Global Modal Scripts -->
    <script>
        // Set global variables for JavaScript
        window.base_url = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('assets/js/global-vin-decoder.js') ?>?v=<?= time() ?>"></script>
    <script src="<?= base_url('assets/js/global-sales-order-modal.js') ?>?v=<?= time() ?>"></script>
    <script>
        // Global function to open modal from topbar dropdown
        function openGlobalModal(orderType) {
            if (typeof GlobalSalesOrderModal !== 'undefined') {
                const modal = new GlobalSalesOrderModal();
                modal.open(); // Use open() method, not show()
            } else {
                console.error('GlobalSalesOrderModal not loaded');
            }
        }
        
    </script>
    
    <!-- Digital Clock Script -->
    <script>
        // Digital Clock functionality
        function updateDigitalClock() {
            const now = new Date();
            
            // Format date: "Friday, Dec 6, 2024"
            const dateOptions = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            };
            const formattedDate = now.toLocaleDateString('en-US', dateOptions);
            
            // Format time: "10:24:35 PM"
            const timeOptions = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            };
            const formattedTime = now.toLocaleTimeString('en-US', timeOptions);
            
            // Update the display
            const dateElement = document.getElementById('current-date');
            const timeElement = document.getElementById('current-time');
            
            if (dateElement) dateElement.textContent = formattedDate;
            if (timeElement) timeElement.textContent = formattedTime;
        }
        
        // Initialize digital clock when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Update immediately
            updateDigitalClock();
            
            // Update every second
            setInterval(updateDigitalClock, 1000);
        });
    </script>

    <!-- Custom scripts for this page -->
    <?= $this->renderSection('scripts') ?>
</body>
</html> 