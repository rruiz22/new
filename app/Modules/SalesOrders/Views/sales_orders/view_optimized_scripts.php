<?= $this->section('scripts') ?>
<!-- Load Modular JavaScript Files -->
<script src="<?= base_url('assets/js/modules/SalesOrderCore.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/CommentsSystem.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/QuickActions.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/StatusManagement.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/MobileOptimizations.js') ?>"></script>
<script src="<?= base_url('assets/js/modules/SalesOrderInit.js') ?>"></script>

<script>
// ==================== ORDER DATA AND CONFIGURATION ====================
// Pass data from PHP to JavaScript modules
window.ORDER_ID = <?= $order['id'] ?? 0 ?>;
window.ORDER_DATE = '<?= $order['date'] ?? '' ?>';
window.ORDER_TIME = '<?= $order['time'] ?? '' ?>';
window.ORDER_STATUS = '<?= $order['status'] ?? '' ?>';
window.ORDER_STOCK = '<?= $order['stock'] ?? '' ?>';
window.BASE_URL = '<?= base_url() ?>';
window.CUSTOMER_PHONE = '<?= $order['salesperson_phone'] ?? '' ?>';
window.CUSTOMER_EMAIL = '<?= $order['salesperson_email'] ?? '' ?>';
window.ALLOWED_STATUSES = <?= json_encode($allowedStatuses ?? []) ?>;

// Legacy utilities object for backward compatibility - minimal version
const SalesOrderUtils = window.salesOrderCore || {
    safeJsonParse: function(data, fallback = null) {
        try {
            return typeof data === 'string' ? JSON.parse(data) : data;
        } catch (error) {
            return fallback;
        }
    },
    
    debounce: function(func, delay = 300) {
        let timeoutId;
        return function (...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    },

    showToast: function(type, message) {
        if (window.salesOrderCore) {
            window.salesOrderCore.showToast(type, message);
        } else {
            alert(message); // Fallback
        }
    }
};

// Make utilities globally available for backward compatibility
window.SalesOrderUtils = SalesOrderUtils;

// Core legacy functions for immediate use
function goBack() {
    if (window.salesOrderCore) {
        window.salesOrderCore.goBack();
    } else {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '<?= base_url('sales_orders') ?>';
        }
    }
}

function showToast(type, message, duration = 3000) {
    if (window.salesOrderCore) {
        window.salesOrderCore.showToast(type, message, duration);
    } else {
        // Fallback simple notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible`;
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
        toast.innerHTML = `${message}<button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), duration);
    }
}

// Functions that will be handled by modules (placeholders for immediate compatibility)
function updateStatus() { 
    if (window.statusManagement) {
        return window.statusManagement.updateStatus();
    }
}

function loadComments(reset = true) { 
    if (window.commentsSystem) {
        window.commentsSystem.loadComments(reset);
    }
}

function submitComment() { 
    if (window.commentsSystem) {
        window.commentsSystem.submitComment();
    }
}

function showReplyForm(commentId) { 
    if (window.commentsSystem) {
        window.commentsSystem.showReplyForm(commentId);
    }
}

function hideReplyForm(commentId) { 
    if (window.commentsSystem) {
        window.commentsSystem.hideReplyForm(commentId);
    }
}

function submitReply(commentId, event) { 
    if (window.commentsSystem) {
        window.commentsSystem.submitReply(commentId, event);
    }
}

function editComment(commentId) { 
    if (window.commentsSystem) {
        window.commentsSystem.editComment(commentId);
    }
}

function deleteComment(commentId) { 
    if (window.commentsSystem) {
        window.commentsSystem.deleteComment(commentId);
    }
}

function editReply(replyId) { 
    if (window.commentsSystem) {
        window.commentsSystem.editReply(replyId);
    }
}

function deleteReply(replyId) { 
    if (window.commentsSystem) {
        window.commentsSystem.deleteReply(replyId);
    }
}

function toggleQuickActions() { 
    if (window.quickActions) {
        window.quickActions.toggleQuickActions();
    }
}

function openQuickActionsModal() { 
    if (window.quickActions) {
        window.quickActions.openQuickActionsModal();
    }
}

function closeQuickActionsModal() { 
    if (window.quickActions) {
        window.quickActions.closeQuickActionsModal();
    }
}

// Generate default avatar for backward compatibility
function generateDefaultAvatar(name) {
    if (window.salesOrderCore) {
        return window.salesOrderCore.generateDefaultAvatar(name);
    }
    
    // Simple fallback
    const canvas = document.createElement('canvas');
    canvas.width = 40;
    canvas.height = 40;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#6c757d';
    ctx.fillRect(0, 0, 40, 40);
    ctx.fillStyle = '#ffffff';
    ctx.font = '16px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(name.charAt(0).toUpperCase(), 20, 20);
    return canvas.toDataURL();
}

// Sync communication badges
function syncCommunicationBadges() {
    if (window.commentsSystem) {
        window.commentsSystem.syncCommunicationBadges();
    }
}

// Update communication counters
function updateCommunicationCounters() {
    syncCommunicationBadges();
}

// Refresh all communication
function refreshAllCommunication() {
    if (window.commentsSystem) {
        window.commentsSystem.refreshComments();
    }
    showToast('success', 'Communication Hub refreshed');
}

// Error handling for missing dependencies
window.addEventListener('error', function(e) {
    console.error('Error:', e.error);
});

console.log('✅ Optimized sales order view loaded - waiting for modules to initialize...');
</script>
<?= $this->endSection() ?>