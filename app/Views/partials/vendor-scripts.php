<!-- JAVASCRIPT -->
<script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>

<!-- jQuery Easing Fix - Must load before ApexCharts to prevent easing function errors -->
<script src="<?= base_url('assets/js/jquery-easing-fix.js') ?>"></script>

<!-- ApexCharts for dashboard charts - Load early for better timing -->
<script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js') ?>"></script>

<script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/node-waves/waves.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/feather-icons/feather.min.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/plugins/lord-icon-2.1.0.js') ?>"></script>
<script src="<?= base_url('assets/js/plugins.js') ?>"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<!-- Choices.js -->
<script src="<?= base_url('assets/libs/choices.js/public/assets/scripts/choices.min.js') ?>"></script>

<!-- Custom fixes for asset paths - COMMENTED OUT: Interferes with fixed language system -->
<!-- <script src="<?= base_url('assets/js/custom-fixes.js') ?>"></script> -->

<!-- SweetAlert2 -->
<script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

<!-- Toastify for notifications -->
<script src="<?= base_url('assets/libs/toastify/toastify.min.js') ?>"></script>

<!-- Notifications System - Provides window.showToast functions -->
<script src="<?= base_url('assets/js/notifications-system.js?v=2.0') ?>"></script>

<!-- Global Notifications System -->
<script src="<?= base_url('assets/js/global-notifications.js') ?>"></script>

<!-- CSRF Token Setup -->
<script>
    // Make CSRF token available globally for AJAX requests
    $(document).ready(function() {
        // Set up AJAX defaults with CSRF token
        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type) && !this.crossDomain) {
                    const token = $('meta[name="<?= csrf_token() ?>"]').attr('content') || 
                                  $('input[name="<?= csrf_token() ?>"]').val();
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        if (settings.data) {
                            if (typeof settings.data === 'string') {
                                settings.data += '&<?= csrf_token() ?>=' + encodeURIComponent(token);
                            } else if (typeof settings.data === 'object') {
                                settings.data['<?= csrf_token() ?>'] = token;
                            }
                        }
                    }
                }
            }
        });
        
        // Update CSRF hash after successful requests
        $(document).ajaxSuccess(function(event, xhr, settings) {
            const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
            if (newToken) {
                $('meta[name="<?= csrf_token() ?>"]').attr('content', newToken);
                $('input[name="<?= csrf_token() ?>"]').val(newToken);
                window.csrfHash = newToken;
            }
        });
        
        // Show body after everything is loaded
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 100);
    });
</script>