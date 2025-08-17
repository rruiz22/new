<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Vehicle Tracking<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Vehicle Tracking<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<!-- Custom styles for Vehicle Tracking -->

<?= $this->endSection() ?>

<?= $this->section('content') ?>





<!-- Main Vehicle Content -->

        
            
           
                <!-- Include the vehicles_content view -->
                <?= $this->include('Modules\ReconOrders\Views\recon_orders\vehicles_content') ?>
          
        
  

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('body').addClass('loaded');
});
</script>
<?= $this->endSection() ?>

