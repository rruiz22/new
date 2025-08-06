<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Sales Order' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Sales Order' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('sales_orders') ?>"><?= lang('App.sales_orders') ?></a></li>
<li class="breadcrumb-item active"><?= $title ?? 'Sales Order' ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* QR Code Modal Styles */
.qr-container {
    transition: all 0.3s ease;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-container img {
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.qr-container img:hover {
    transform: scale(1.02);
}

/* Topbar QR Styles */
.qr-topbar-image {
    transition: transform 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.qr-topbar-image:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.qr-topbar-container {
    padding: 8px;
}

/* Copy button animations */
.btn-outline-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Modal responsive adjustments */
@media (max-width: 768px) {
    .qr-container {
        min-height: 250px;
    }
    
    .qr-container img {
        max-width: 250px !important;
    }
    
    #qrModal .modal-dialog {
        margin: 0.5rem;
    }
    
    .qr-topbar-image {
        width: 50px !important;
        height: 50px !important;
    }
}

/* QR Sidebar Card Styles */
.qr-sidebar-image {
    transition: transform 0.3s ease;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.qr-sidebar-image:hover {
    transform: scale(1.02) !important;
    box-shadow: 0 6px 25px rgba(0,0,0,0.2);
}

.qr-large-display {
    padding: 10px;
    background: linear-gradient(145deg, #f8f9fa, #ffffff);
    border-radius: 15px;
    border: 1px solid rgba(0,0,0,0.05);
}

/* QR Card responsive adjustments */
@media (max-width: 1200px) {
    .qr-sidebar-image {
        max-width: 150px !important;
    }
}

@media (max-width: 768px) {
    .qr-sidebar-image {
        max-width: 120px !important;
    }
    
    .qr-large-display {
        padding: 8px;
    }
    
    /* QR Card is hidden on mobile via Bootstrap classes (d-none d-md-block) */
    /* This improves UX since QR codes aren't useful on the same device displaying them */
}

/* Time Status Indicators - Enhanced */
.time-status-on-time {
    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
}

.time-status-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    animation: pulse-warning 2s infinite;
}

.time-status-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    animation: flash-danger 1s infinite;
}

@keyframes pulse-warning {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.9;
    }
}

@keyframes flash-danger {
    0%, 100% {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
    50% {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.6);
    }
}

/* Enhanced Topbar Styling */
.order-top-bar {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Font size utilities */
.fs-11 {
    font-size: 0.6875rem !important;
}

.top-bar-item {
    position: relative;
    padding: 1rem 0.75rem;
    border-right: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    align-items: center;
    min-height: 120px;
}

.top-bar-item:last-child {
    border-right: none;
}

.top-bar-item:hover {
    background: rgba(59, 130, 246, 0.05);
}

.top-bar-icon {
    margin-right: 0.75rem;
    flex-shrink: 0;
    width: 32px;
    display: flex;
    justify-content: center;
}

.top-bar-icon i {
    font-size: 1.25rem;
}

.top-bar-content {
    flex: 1;
    min-width: 0; /* Allow text truncation */
}

.top-bar-label {
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #64748b;
}

/* Comments Styles */
.mention-suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.mention-suggestion {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.mention-suggestion:hover,
.mention-suggestion.active {
    background-color: #f8f9fa;
}

.mention-suggestion:last-child {
    border-bottom: none;
}

.comment-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}

.comment-item:last-child {
    border-bottom: none;
}

.comment-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.comment-user-info {
    flex: 1;
}

.comment-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.comment-item:hover .comment-actions {
    opacity: 1;
}

.comment-actions .btn {
    padding: 3px 6px;
    font-size: 11px;
    border-radius: 3px;
}

.comment-edit-form {
    margin-top: 6px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.reply-edit-form {
    margin-top: 6px;
    padding: 8px;
    background-color: #f1f3f4;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.reply-edit-form textarea {
    font-size: 13px;
}

.reply-edit-form .btn {
    font-size: 12px;
    padding: 4px 8px;
}

/* Reply Styles */
.comment-replies {
    margin-left: 28px;
    margin-top: 8px;
    border-left: 2px solid #e9ecef;
    padding-left: 12px;
}

.comment-reply {
    margin-bottom: 8px;
    padding: 6px 0;
}

.reply-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 4px;
}

.reply-avatar {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
}

.reply-user-name {
    font-weight: 600;
    font-size: 12px;
    color: #495057;
}

.reply-timestamp {
    font-size: 10px;
    color: #6c757d;
}

.reply-content {
    font-size: 13px;
    line-height: 1.3;
    margin-left: 24px;
    color: #495057;
}

.reply-actions {
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.comment-reply:hover .reply-actions {
    opacity: 1;
}

.reply-form {
    margin-top: 8px;
    margin-left: 28px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    display: none;
}

.reply-form.show {
    display: block;
}

/* Attachment Styles */
.comment-attachments {
    margin-top: 12px;
    padding: 12px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.attachments-title {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #495057;
    margin-bottom: 10px;
    font-size: 13px;
}

.attachment-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.attachment-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.attachment-item:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.attachment-thumbnail {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    border-radius: 4px;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    flex-shrink: 0;
}

.file-thumbnail-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.file-thumbnail-icon {
    font-size: 18px;
}

.attachment-info {
    flex: 1;
    min-width: 0;
    margin-right: 12px;
}

.attachment-header {
    display: flex;
    align-items: center;
    margin-bottom: 2px;
}

.attachment-header i {
    margin-right: 6px;
    font-size: 14px;
}

.attachment-name {
    font-weight: 500;
    color: #495057;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

.attachment-size {
    font-size: 11px;
    color: #6c757d;
}

.attachment-actions {
    display: flex;
    gap: 4px;
    flex-shrink: 0;
}

.attachment-actions .btn {
    padding: 4px 8px;
    font-size: 11px;
}

.attachment-actions .btn i {
    font-size: 12px;
}

/* Mention Styles */
.mention {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 1px 4px;
    border-radius: 3px;
    font-weight: 500;
    text-decoration: none;
}

.mention:hover {
    background-color: #bbdefb;
    color: #1565c0;
    text-decoration: none;
}

.top-bar-label {
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 0.25rem;
    letter-spacing: 0.5px;
    line-height: 1;
}

.top-bar-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
    line-height: 1.3;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.top-bar-sub {
    font-size: 0.75rem;
    color: #64748b;
    line-height: 1.2;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Better responsive adjustments */
@media (min-width: 1400px) {
    .top-bar-item {
        padding: 1.25rem 1rem;
    }
    
    .top-bar-value {
        font-size: 1rem;
    }
    
    .top-bar-sub {
        font-size: 0.875rem;
    }
}

/* Specific styling for 1240px width as mentioned by user */
@media (min-width: 1200px) and (max-width: 1399.98px) {
    .top-bar-item {
        padding: 1rem 0.8rem;
        min-height: 115px;
    }
    
    .top-bar-icon {
        margin-right: 0.6rem;
        width: 30px;
    }
    
    .top-bar-icon i {
        font-size: 1.15rem;
    }
    
    .top-bar-label {
        font-size: 0.68rem;
        margin-bottom: 0.3rem;
    }
    
    .top-bar-value {
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }
    
    .top-bar-sub {
        font-size: 0.72rem;
    }
    
    /* Ensure equal height for all columns at this breakpoint */
    .order-top-bar .row > div {
        display: flex;
    }
    
    .order-top-bar .top-bar-item {
        width: 100%;
    }
}

@media (max-width: 1199.98px) {
    .top-bar-item {
        padding: 1rem 0.6rem;
        min-height: 110px;
    }
    
    .top-bar-icon {
        margin-right: 0.5rem;
        width: 28px;
    }
    
    .top-bar-icon i {
        font-size: 1.1rem;
    }
    
    .top-bar-label {
        font-size: 0.65rem;
    }
    
    .top-bar-value {
        font-size: 0.8rem;
    }
    
    .top-bar-sub {
        font-size: 0.7rem;
    }
}

@media (max-width: 991.98px) {
    .top-bar-item {
        padding: 0.875rem 0.5rem;
        min-height: 100px;
    }
    
    .top-bar-icon {
        width: 26px;
    }
    
    .top-bar-icon i {
        font-size: 1rem;
    }
    
    .top-bar-label {
        font-size: 0.6rem;
    }
    
    .top-bar-value {
        font-size: 0.75rem;
    }
    
    .top-bar-sub {
        font-size: 0.65rem;
    }
}

@media (max-width: 768px) {
    .top-bar-item {
        padding: 1rem;
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        min-height: auto;
    }
    
    .top-bar-item:last-child {
        border-bottom: none;
    }
    
    .top-bar-icon {
        margin-right: 0.75rem;
        width: 32px;
    }
    
    .top-bar-icon i {
        font-size: 1.25rem;
    }
    
    .top-bar-label {
        font-size: 0.75rem;
    }
    
    .top-bar-value {
        font-size: 0.9rem;
    }
    
    .top-bar-sub {
        font-size: 0.8rem;
    }
}

/* Mobile Quick Actions Floating Button */
@media (max-width: 1199.98px) {
    /* Hide the Quick Actions card on mobile and tablet - use modal instead */
    .quick-actions-card {
        display: none !important;
    }
    
    /* Floating action button - Minimalist style */
    .quick-actions-float-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 56px;
        height: 56px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        z-index: 1050;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
    }
    
    .quick-actions-float-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        background: #f7fafc;
        border-color: #cbd5e0;
    }
    
    .quick-actions-float-btn i {
        color: #4a5568;
        font-size: 20px;
    }
}

@keyframes pulse-fab {
    0%, 100% { 
        opacity: 0; 
        transform: scale(1); 
    }
    50% { 
        opacity: 0.2; 
        transform: scale(1.2); 
    }
}

/* Professional Quick Actions Modal Styles */
.quick-actions-modal {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.quick-actions-modal-header {
    background: #f7fafc;
    border: none;
    padding: 1.5rem;
    color: #2d3748;
    border-bottom: 1px solid #e2e8f0;
}

.quick-actions-icon-wrapper {
    width: 48px;
    height: 48px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
}

.quick-actions-main-icon {
    width: 20px;
    height: 20px;
    color: #4a5568;
}

.quick-actions-subtitle {
    color: #718096;
    font-weight: 400;
    font-size: 0.875rem;
}

.quick-actions-modal-body {
    padding: 1.5rem;
    background: #ffffff;
}

/* Contact Info Card */
.quick-actions-contact-card {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
}

.contact-avatar {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
}

.contact-icon {
    width: 18px;
    height: 18px;
    color: #4a5568;
}

.contact-name {
    font-weight: 600;
    color: #1a202c;
    font-size: 0.95rem;
}

.contact-role {
    font-size: 0.8rem;
    color: #718096;
}

/* Status Section */
.quick-action-section {
    background: #f7fafc;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #e2e8f0;
}

.quick-action-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.quick-action-label-icon {
    width: 14px;
    height: 14px;
    margin-right: 0.5rem;
    color: #718096;
}

.quick-action-select {
    border: 1px solid #cbd5e0;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-weight: 400;
    transition: all 0.2s ease;
    background: white;
    font-size: 0.875rem;
}

.quick-action-select:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    outline: none;
}

/* Action Buttons Grid */
.quick-actions-buttons {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-top: 1rem;
}

.quick-action-btn {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}

.quick-action-btn:hover {
    border-color: #cbd5e0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    text-decoration: none;
}

.quick-action-icon {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.quick-action-content {
    flex-grow: 1;
    text-align: left;
}

.quick-action-title {
    display: block;
    font-weight: 500;
    color: #1a202c;
    font-size: 0.875rem;
    margin-bottom: 0.125rem;
}

.quick-action-desc {
    color: #718096;
    font-size: 0.75rem;
    font-weight: 400;
}

/* Individual button colors - Minimalist without backgrounds */
.quick-action-call .quick-action-icon {
    background: transparent;
}

.quick-action-call .quick-action-icon i {
    color: #3182ce;
}

.quick-action-call:hover {
    border-color: #3182ce;
}

.quick-action-sms .quick-action-icon {
    background: transparent;
}

.quick-action-sms .quick-action-icon i {
    color: #38a169;
}

.quick-action-sms:hover {
    border-color: #38a169;
}

.quick-action-email .quick-action-icon {
    background: transparent;
}

.quick-action-email .quick-action-icon i {
    color: #ed8936;
}

.quick-action-email:hover {
    border-color: #ed8936;
}

.quick-action-alert .quick-action-icon {
    background: transparent;
}

.quick-action-alert .quick-action-icon i {
    color: #9f7aea;
}

.quick-action-alert:hover {
    border-color: #9f7aea;
}

.quick-action-qr .quick-action-icon {
    background: transparent;
}

.quick-action-qr .quick-action-icon i {
    color: #4a5568;
}

.quick-action-qr:hover {
    border-color: #cbd5e0;
}

/* Single column layout for smaller screens */
@media (max-width: 576px) {
    .quick-actions-buttons {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .quick-actions-modal-body {
        padding: 1rem;
    }
    
    .quick-action-btn {
        padding: 0.75rem;
    }
    
    .quick-action-icon {
        width: 32px;
        height: 32px;
        margin-right: 0.5rem;
    }
    
    .quick-actions-contact-card,
    .quick-action-section {
        padding: 0.75rem;
    }
}

/* Tablet adjustments */
@media (min-width: 768px) and (max-width: 1199.98px) {
    .quick-actions-card {
        margin-bottom: 1.5rem;
    }
    
    .quick-actions-body {
        padding: 1rem;
    }
}

/* Internal Notes System Styles */
.reply-edit-form {
    background: #f8f9fa;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 12px;
    margin-top: 8px;
}

.reply-edit-form textarea {
    border: 1px solid #d1d3e2;
    border-radius: 6px;
    resize: vertical;
    min-height: 60px;
}

.reply-edit-form .btn {
    font-size: 12px;
    padding: 4px 12px;
}

.reply-form {
    background: #f8f9fa;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 12px;
    margin-top: 8px;
}

.reply-form textarea {
    border: 1px solid #d1d3e2;
    border-radius: 6px;
    resize: vertical;
    min-height: 50px;
}

.reply-form .btn {
    font-size: 12px;
    padding: 4px 12px;
}

.rotating {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.nav-tabs-bordered {
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 0.5rem 1rem 0;
    margin: 0;
}

.nav-tabs-bordered .nav-link {
    border: none;
    background: none;
    color: #64748b;
    padding: 0.75rem 1rem;
    border-radius: 6px 6px 0 0;
    transition: all 0.2s ease;
    margin-right: 0.25rem;
}

.nav-tabs-bordered .nav-link:hover {
    background: #e2e8f0;
    color: #374151;
}

.nav-tabs-bordered .nav-link.active {
    background: white;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-bottom: 1px solid white;
    margin-bottom: -1px;
    font-weight: 500;
}

.nav-tabs-bordered .nav-link .badge {
    font-size: 0.65rem;
    font-weight: 500;
}

/* Notes List Styles */
.note-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    background: white;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.note-item:hover {
    border-color: #cbd5e0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
}

.note-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.note-author {
    display: flex;
    align-items: center;
}

.note-avatar, .reply-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
    margin-right: 0.75rem;
    text-transform: uppercase;
}

.note-author-info, .reply-author-info {
    display: flex;
    flex-direction: column;
}

.note-author-name, .reply-author-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
    line-height: 1.2;
}

.note-timestamp, .reply-timestamp {
    color: #64748b;
    font-size: 0.75rem;
    margin-top: 0.125rem;
}

.note-content, .reply-content {
    color: #374151;
    line-height: 1.6;
    margin-bottom: 0.75rem;
    word-wrap: break-word;
}

.note-content:last-child, .reply-content:last-child {
    margin-bottom: 0;
}

.note-actions, .reply-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.note-item:hover .note-actions,
.note-reply:hover .reply-actions {
    opacity: 1;
}

.note-action-btn {
    background: none;
    border: none;
    color: #64748b;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
}

.note-action-btn:hover {
    background: #f1f5f9;
    color: #374151;
}

.note-action-btn.reply:hover {
    background: #dbeafe;
    color: #2563eb;
}

.note-action-btn.edit:hover {
    background: #fef3c7;
    color: #d97706;
}

.note-action-btn.delete:hover {
    background: #fee2e2;
    color: #dc2626;
}

/* Note Replies Styles */
.note-reply {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: 0.75rem;
    margin-left: 2rem;
    position: relative;
}

.note-reply:before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 1rem;
    width: 1.5rem;
    height: 1px;
    background: #cbd5e0;
}

.reply-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.reply-author {
    display: flex;
    align-items: center;
}

.reply-avatar {
    width: 28px;
    height: 28px;
    font-size: 0.65rem;
    background: linear-gradient(45deg, #38bdf8 0%, #818cf8 100%);
}

/* Reply Form Styles */
.reply-form {
    border-top: 1px solid #e2e8f0;
    padding-top: 0.75rem;
    margin-top: 0.75rem;
}

.reply-form textarea {
    resize: vertical;
    min-height: 60px;
}

/* Note Edit Form Styles */
.note-edit-form {
    margin-top: 0.5rem;
}

.note-edit-form textarea {
    resize: vertical;
    min-height: 80px;
}

/* Attachment Styles */
.note-attachment-list {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.note-attachment-item {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    color: #4f46e5;
    text-decoration: none;
    font-size: 0.75rem;
    transition: all 0.2s ease;
}

.note-attachment-item:hover {
    background: #e0e7ff;
    color: #3730a3;
    text-decoration: none;
}

/* Empty States */
.empty-notes {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.empty-notes i {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    opacity: 0.5;
}

.empty-notes h6 {
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-notes p {
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
}

/* Loading States */
.notes-loading {
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

/* Notes List Container - Enable scrolling for infinite scroll */
#notesList {
    max-height: 400px !important;
    overflow-y: auto !important;
    padding-right: 8px; /* Space for scrollbar */
    border: 1px solid #e2e8f0; /* Visual border for debugging */
}

/* Scrollbar styling for notes list */
#notesList::-webkit-scrollbar {
    width: 6px;
}

#notesList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#notesList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#notesList::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .note-item {
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .note-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .note-actions, .reply-actions {
        opacity: 1; /* Always show on mobile */
    }
    
    .note-reply {
        margin-left: 1rem;
    }
    
    .note-reply:before {
        left: -1rem;
        width: 0.75rem;
    }
}

/* Duplicate styles removed - using main definitions above */

/* Mentions Styles */
.mention {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 0.125rem 0.375rem;
    border-radius: 4px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
}

.mention:hover {
    background: #bfdbfe;
    color: #1e40af;
}

.mention-suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.mention-suggestion {
    padding: 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s ease;
}

.mention-suggestion:last-child {
    border-bottom: none;
}

.mention-suggestion:hover,
.mention-suggestion.active {
    background: #f8fafc;
}

.mention-suggestion-user {
    display: flex;
    align-items: center;
}

.mention-suggestion-avatar {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
    font-size: 0.75rem;
    color: #64748b;
}

.mention-suggestion-info {
    flex: 1;
}

.mention-suggestion-name {
    font-weight: 500;
    color: #1e293b;
    font-size: 0.875rem;
    margin: 0;
}

.mention-suggestion-username {
    color: #64748b;
    font-size: 0.75rem;
    margin: 0;
}

/* Attachments */
.note-attachments {
    margin-top: 0.5rem;
}

.attachment-item {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    margin-right: 0.5rem;
    font-size: 0.75rem;
    color: #64748b;
    text-decoration: none;
}

.attachment-item:hover {
    background: #e2e8f0;
    color: #374151;
}

.attachment-item i {
    margin-right: 0.25rem;
}

/* Filter Bar */
.notes-filter-bar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.75rem;
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: #64748b;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #cbd5e0;
}

/* Mention Alert */
.mention-alert {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
}

.mention-alert-content {
    display: flex;
    align-items: center;
}

.mention-alert-icon {
    color: #d97706;
    margin-right: 0.5rem;
}

.mention-alert-text {
    flex: 1;
    color: #92400e;
    font-size: 0.875rem;
    margin: 0;
}

.mention-alert-action {
    background: none;
    border: none;
    color: #d97706;
    font-size: 0.75rem;
    cursor: pointer;
    text-decoration: underline;
}

/* Mobile Optimizations */
@media (max-width: 767.98px) {
    .nav-tabs-bordered {
        padding: 0.25rem 0.5rem 0;
    }
    
    .nav-tabs-bordered .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .note-item {
        padding: 0.75rem;
    }
    
    .note-author {
        margin-bottom: 0.25rem;
    }
    
    .note-avatar {
        width: 28px;
        height: 28px;
        margin-right: 0.5rem;
    }
    
    .notes-filter-bar .row > .col-md-3,
    .notes-filter-bar .row > .col-md-6 {
        margin-bottom: 0.5rem;
    }
    
    .note-actions {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .note-action-buttons {
        justify-content: center;
    }
}

/* Enhanced Time Status Badge - Larger size */
.time-status-badge-large {
    font-size: 0.75rem !important;
    padding: 0.4rem 0.8rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
}

/* Live Time Indicator */
.live-time-indicator {
    display: inline-flex;
    align-items: center;
    font-size: 0.7rem;
    color: #64748b;
}

.live-time-pulse {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #22c55e;
    margin-right: 0.4rem;
    animation: pulse-live 2s infinite;
    box-shadow: 0 0 4px rgba(34, 197, 94, 0.5);
}

.live-label {
    margin-right: 0.3rem;
    font-weight: 500;
    color: #9ca3af;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.live-time {
    font-family: 'Consolas', 'Monaco', 'Lucida Console', 'Liberation Mono', 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', 'Courier New', monospace;
    font-weight: 700;
    color: #10b981;
    letter-spacing: 1px;
    font-size: 1rem;
}

.time-remaining-display {
    font-size: 0.85rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

@keyframes pulse-live {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.4;
        transform: scale(1.2);
    }
}

/* SMS Character Count Validation Styles */
.sms-char-count-container {
    transition: all 0.3s ease;
}

.sms-char-count-container.text-danger {
    animation: pulse-danger 2s infinite;
}

.sms-char-count-container.text-warning {
    animation: pulse-warning 2s infinite;
}

@keyframes pulse-danger {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes pulse-warning {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

#smsMessage.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

#smsMessage.border-warning {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
}

#smsMessage.border-success {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
}

#smsCharStatus {
    transition: all 0.3s ease;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

#smsCharWarning {
    transition: all 0.3s ease;
}

#smsLengthAlert {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Disabled button styling for SMS */
#smsForm button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

#smsForm button[type="submit"]:disabled:hover {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

/* ========================================
   ACTIVITY STYLES (from Service Orders)
   ======================================== */

/* Activity styling */
.activity-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: #f8fafc;
    border-radius: 8px;
    padding: 0.75rem;
    margin: 0 -0.75rem;
}

.activity-with-tooltip {
    cursor: help;
}

.activity-with-tooltip:hover {
    background: #f0f9ff;
}

#activityList {
    max-height: 400px;
    overflow-y: auto;
}

/* Custom scrollbar for activity list */
#activityList::-webkit-scrollbar {
    width: 6px;
}

#activityList::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

#activityList::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

#activityList::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="sales-order-view-container">
<?php if (isset($order) && $order): ?>

<!-- FULL WIDTH TOP INFORMATION BAR -->
<div class="order-top-bar mb-4">
    <div class="row g-0">
        <!-- 1. Schedule Information -->
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="calendar" class="text-warning"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label">Scheduled</div>
                    <div class="top-bar-value">
                        <?php if ($order['date'] && $order['time']): ?>
                            <?= date('M j, Y', strtotime($order['date'])) ?>
                        <?php else: ?>
                            Not scheduled
                        <?php endif; ?>
                    </div>
                    <div class="top-bar-sub">
                        <?php if ($order['time']): ?>
                            <?= date('g:i A', strtotime($order['time'])) ?>
                        <?php else: ?>
                            No time set
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Contact Information -->
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="user" class="text-success"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label">Contact</div>
                    <div class="top-bar-value"><?= $order['salesperson_name'] ?? 'Not assigned' ?></div>
                    <div class="top-bar-sub">
                        <?php if (isset($order['salesperson_phone']) && $order['salesperson_phone']): ?>
                            <a href="tel:<?= $order['salesperson_phone'] ?>" class="text-decoration-none">
                                <i data-feather="phone" class="icon-xs me-1"></i>
                                <?= $order['salesperson_phone'] ?>
                            </a>
                        <?php elseif (isset($order['salesperson_email']) && $order['salesperson_email']): ?>
                            <a href="mailto:<?= $order['salesperson_email'] ?>" class="text-decoration-none">
                                <i data-feather="mail" class="icon-xs me-1"></i>
                                <?= $order['salesperson_email'] ?>
                            </a>
                        <?php else: ?>
                            No contact info
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Vehicle Information -->
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="truck" class="text-primary"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label">Vehicle</div>
                    <div class="top-bar-value"><?= $order['vehicle'] ?? 'Not specified' ?></div>
                    <div class="top-bar-sub">Stock: <?= $order['stock'] ?? 'Not specified' ?></div>
                </div>
            </div>
        </div>

        <!-- 4. Service Information -->
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="settings" class="text-info"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label">Service</div>
                    <div class="top-bar-value"><?= $order['service_name'] ?? 'Not specified' ?></div>
                    <div class="top-bar-sub">
                        <?php if (isset($order['service_price']) && $order['service_price']): ?>
                            $<?= number_format($order['service_price'], 2) ?>
                        <?php else: ?>
                            Price not set
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Status -->
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="activity" class="text-info"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label">Status</div>
                    <div class="top-bar-value">
                        <?php
                        $statusClass = 'bg-warning';
                        $statusText = 'Pending'; // Default
                        
                        switch($order['status']) {
                            case 'completed':
                                $statusClass = 'bg-success';
                                $statusText = 'Completed';
                                break;
                            case 'cancelled':
                                $statusClass = 'bg-danger';
                                $statusText = 'Cancelled';
                                break;
                            case 'in_progress':
                                $statusClass = 'bg-info';
                                $statusText = 'In Progress';
                                break;
                            case 'processing':
                                $statusClass = 'bg-primary';
                                $statusText = 'Processing';
                                break;
                            case 'pending':
                                $statusClass = 'bg-warning';
                                $statusText = 'Pending';
                                break;
                            default:
                                $statusText = ucfirst($order['status']);
                                break;
                        }
                        ?>
                        <span id="topBarStatusBadge" class="badge <?= $statusClass ?> fs-12"><?= $statusText ?></span>
                    </div>
                    <div id="topBarLastUpdated" class="top-bar-sub">
                        Last updated: <?= date('M j, g:i A', strtotime($order['updated_at'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Time Remaining Status -->
        <div class="col-xxl-2 col-lg-4 col-md-6 col-sm-6 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="clock" class="text-secondary"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label">Time Status</div>
                    <div class="top-bar-value">
                        <?php
                        $timeStatusText = 'Not scheduled';
                        $timeStatusClass = 'badge bg-secondary time-status-badge-large';
                        
                        if ($order['date'] && $order['time']) {
                            $scheduledDateTime = new DateTime($order['date'] . ' ' . $order['time']);
                            $now = new DateTime();
                            $diff = $scheduledDateTime->getTimestamp() - $now->getTimestamp();
                            $hoursRemaining = $diff / 3600;
                            
                            if ($hoursRemaining < 0) {
                                // Past due
                                $timeStatusText = 'DELAY';
                                $timeStatusClass = 'badge time-status-danger time-status-badge-large';
                            } elseif ($hoursRemaining < 1) {
                                // Less than 1 hour
                                $timeStatusText = 'ATTENTION REQUIRED';
                                $timeStatusClass = 'badge time-status-warning time-status-badge-large';
                            } else {
                                // More than 1 hour
                                $timeStatusText = 'ON TIME';
                                $timeStatusClass = 'badge time-status-on-time time-status-badge-large';
                            }
                        }
                        ?>
                        <span id="timeStatusBadge" class="<?= $timeStatusClass ?>"><?= $timeStatusText ?></span>
                    </div>
                    <div class="top-bar-sub">
                        <!-- Dynamic Time Remaining -->
                        <div id="timeRemainingDisplay" class="time-remaining-display">
                            <?php
                            if ($order['date'] && $order['time']) {
                                if ($hoursRemaining < 0) {
                                    $hoursOverdue = abs($hoursRemaining);
                                    if ($hoursOverdue < 1) {
                                        $minutesOverdue = $hoursOverdue * 60;
                                        echo sprintf('%.0f minutes overdue', $minutesOverdue);
                                    } elseif ($hoursOverdue < 24) {
                                        if ($hoursOverdue == floor($hoursOverdue)) {
                                            echo sprintf('%.0f hours overdue', $hoursOverdue);
                                        } else {
                                            echo sprintf('%.1f hours overdue', $hoursOverdue);
                                        }
                                    } else {
                                        $daysOverdue = floor($hoursOverdue / 24);
                                        $remainingHours = $hoursOverdue - ($daysOverdue * 24);
                                        if ($remainingHours < 1) {
                                            echo sprintf('%d day%s overdue', $daysOverdue, $daysOverdue > 1 ? 's' : '');
                                        } else {
                                            echo sprintf('%d day%s %.1f hours overdue', $daysOverdue, $daysOverdue > 1 ? 's' : '', $remainingHours);
                                        }
                                    }
                                } elseif ($hoursRemaining < 1) {
                                    $minutesRemaining = ($hoursRemaining * 60);
                                    echo sprintf('%.0f minutes left', $minutesRemaining);
                            } elseif ($hoursRemaining < 24) {
                                if ($hoursRemaining == floor($hoursRemaining)) {
                                    echo sprintf('%.0f hours remaining', $hoursRemaining);
                                } else {
                                    echo sprintf('%.1f hours remaining', $hoursRemaining);
                                }
                            } else {
                                $daysRemaining = floor($hoursRemaining / 24);
                                $remainingHours = $hoursRemaining - ($daysRemaining * 24);
                                if ($remainingHours < 1) {
                                    echo sprintf('%d day%s remaining', $daysRemaining, $daysRemaining > 1 ? 's' : '');
                                } else {
                                    echo sprintf('%d day%s %.1f hours remaining', $daysRemaining, $daysRemaining > 1 ? 's' : '', $remainingHours);
                                }
                            }
                        } else {
                            echo 'No time set';
                        }
                        ?>
                        </div>
                        
                        <!-- Live Current Time -->
                        <div class="live-time-indicator">
                            <span class="live-time-pulse"></span>
                            <span class="live-label">Now:</span>
                            <span id="liveCurrentTime" class="live-time"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <button type="button" class="btn btn-outline-secondary" onclick="goBack()">
                <i data-feather="arrow-left" class="icon-sm me-1"></i>
                <?= lang('App.back_to_list') ?>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content Column -->
    <div class="col-xl-8 order-xl-1 order-2 main-content-column">
        <!-- Order Details Card -->
        <div class="card order-details">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">
                            <?= lang('App.order_details') ?> - Order SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                        </h5>
                        <p class="card-subtitle mb-0">Complete order information and status</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="editOrder(<?= $order['id'] ?>)">
                                <i data-feather="edit-3" class="icon-sm me-1"></i>
                                <?= lang('App.edit_order') ?>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="downloadPdfAndRefresh(<?= $order['id'] ?>)">
                                <i data-feather="download" class="icon-sm me-1"></i>
                                Download PDF
                            </button>
                            <a href="<?= base_url('sales_orders/print/' . $order['id']) ?>" target="_blank" class="btn btn-secondary btn-sm">
                                <i data-feather="printer" class="icon-sm me-1"></i>
                                <?= lang('App.print') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.created_on') ?></label>
                            <p class="mb-0"><?= date('F j, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.assigned_contact') ?></label>
                            <p class="mb-0"><?= $order['salesperson_name'] ?? lang('App.not_assigned') ?></p>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.current_status') ?></label>
                            <div>
                                    <span id="detailsStatusBadge" class="badge <?= $statusClass ?> fs-12"><?= $statusText ?></span>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.stock') ?></label>
                            <p class="mb-0"><?= $order['stock'] ?? 'N/A' ?></p>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.service_requested') ?></label>
                            <p class="mb-0"><?= $order['service_name'] ?? 'N/A' ?></p>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.last_updated') ?></label>
                            <p id="detailsLastUpdated" class="mb-0"><?= date('F j, Y \a\t g:i A', strtotime($order['updated_at'])) ?></p>
                        </div>
                            </div>
                </div>
            </div>
        </div>

        <!-- Schedule & Vehicle Information Row -->
        <div class="row order-schedule-vehicle">
        <!-- Schedule Information -->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.schedule_information') ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.date') ?></label>
                            <p class="mb-0">
                                    <?= $order['date'] ? date('F j, Y', strtotime($order['date'])) : lang('App.not_scheduled') ?>
                                </p>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.time') ?></label>
                            <p class="mb-0">
                                    <?= $order['time'] ? date('g:i A', strtotime($order['time'])) : lang('App.not_scheduled') ?>
                                </p>
                            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.salesperson') ?></label>
                            <p class="mb-0"><?= $order['salesperson_name'] ?? lang('App.not_assigned') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle & Client Information -->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vehicle & Client Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.client') ?></label>
                            <p class="mb-0"><?= $order['client_name'] ?? 'N/A' ?></p>
                </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Vehicle</label>
                            <p class="mb-0"><?= $order['vehicle'] ?? 'N/A' ?></p>
            </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.vin') ?></label>
                            <p class="mb-0"><?= $order['vin'] ?? 'N/A' ?></p>
                            </div>
                            </div>
                        </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card order-additional-info">
            <div class="card-header">
                        <h5 class="card-title mb-0"><?= lang('App.additional_information') ?></h5>
                    </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-medium"><?= lang('App.instructions') ?></label>
                    <div><?= $order['instructions'] ? nl2br(esc($order['instructions'])) : 'No instructions provided' ?></div>
                </div>
                <div class="mb-3" style="display: none;">
                    <label class="form-label fw-medium"><?= lang('App.internal_notes') ?></label>
                    <div><?= $order['notes'] ? nl2br(esc($order['notes'])) : 'No notes available' ?></div>
            </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card order-comments" id="comments">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="message-circle" class="icon-sm me-2"></i>
                    Comments & Attachments
                    <span id="commentsCount" class="badge bg-primary ms-1">0</span>
                </h5>
                <button class="btn btn-sm btn-outline-primary" onclick="loadComments(true)">
                    <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                    Refresh
                </button>
                    </div>
            <div class="card-body">
                <!-- Enhanced Add Comment Form -->
                <form id="commentForm" class="mb-3" enctype="multipart/form-data">
                    <div class="mb-2">
                        <div class="position-relative">
                            <textarea class="form-control" id="commentText" rows="3" placeholder="Add a comment... Use @username to mention staff members" required></textarea>
                            <div id="mentionSuggestions" class="mention-suggestions-dropdown" style="display: none;"></div>
                        </div>
                        <div class="form-text">
                            <i data-feather="info" class="icon-xs me-1"></i>
                            Type @ followed by username to mention staff members. Supports images, videos, and documents.
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <input type="file" id="commentAttachments" multiple class="form-control form-control-sm d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.mov,.txt">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('commentAttachments').click()">
                                <i data-feather="paperclip" class="icon-xs me-1"></i>
                                Attach Files
                            </button>
                            <span id="attachmentCount" class="text-muted small ms-2"></span>
                                        </div>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i data-feather="send" class="icon-xs me-1"></i>
                        Add Comment
                                            </button>
                    </div>
                        </form>

                <!-- Comments List -->
                <div id="commentsList" style="max-height: 400px !important; overflow-y: auto !important; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px;">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Loading comments...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Internal Communication Tabs -->
        <div class="card order-internal-comm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i data-feather="users" class="icon-sm me-2"></i>
                    Internal Communication
                </h5>
                <small class="text-muted">Staff-only notes and discussions</small>
            </div>
            <div class="card-body p-0">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-tabs-bordered" id="internalTabsNav" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes-pane" type="button" role="tab" aria-controls="notes-pane" aria-selected="true">
                            <i data-feather="edit-3" class="icon-xs me-1"></i>
                            Internal Notes
                            <span id="notesCount" class="badge bg-primary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mentions-tab" data-bs-toggle="tab" data-bs-target="#mentions-pane" type="button" role="tab" aria-controls="mentions-pane" aria-selected="false">
                            <i data-feather="at-sign" class="icon-xs me-1"></i>
                            My Mentions
                            <span id="mentionsCount" class="badge bg-warning ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="team-tab" data-bs-toggle="tab" data-bs-target="#team-pane" type="button" role="tab" aria-controls="team-pane" aria-selected="false">
                            <i data-feather="users" class="icon-xs me-1"></i>
                            Team Activity
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="internalTabsContent">
                    <!-- Internal Notes Tab -->
                    <div class="tab-pane fade show active" id="notes-pane" role="tabpanel" aria-labelledby="notes-tab">
                        <div class="p-3">
                            <!-- Add Note Form -->
                            <form id="noteForm" class="mb-3">
                                <div class="mb-2">
                                    <div class="position-relative">
                                        <textarea class="form-control" id="noteContent" rows="3" placeholder="Add an internal note... Use @username to mention staff members" required maxlength="5000"></textarea>
                                        <div id="noteMentionSuggestions" class="mention-suggestions-dropdown" style="display: none;"></div>
                                    </div>
                                    <div class="form-text d-flex justify-content-between">
                                        <div>
                                        <i data-feather="info" class="icon-xs me-1"></i>
                                        Type @ followed by username to mention staff members. Supports file attachments.
                                        </div>
                                        <div>
                                            <span id="charCount" class="text-muted">0</span>/<span class="text-muted">5000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="file" id="noteAttachments" multiple class="form-control form-control-sm d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('noteAttachments').click()">
                                            <i data-feather="paperclip" class="icon-xs me-1"></i>
                                            Attach Files
                                        </button>
                                        <span id="attachmentCount" class="text-muted small ms-2"></span>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i data-feather="send" class="icon-xs me-1"></i>
                                        Add Note
                                    </button>
                                </div>
                            </form>

                            <!-- Notes Filter -->
                            <div class="notes-filter-bar mb-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text"><i data-feather="search" class="icon-xs"></i></span>
                                            <input type="text" class="form-control" id="notesSearch" placeholder="Search notes...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" id="notesAuthorFilter">
                                            <option value="">All Authors</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-select form-select-sm" id="notesDateFilter">
                                            <option value="">All Time</option>
                                            <option value="today">Today</option>
                                            <option value="week">This Week</option>
                                            <option value="month">This Month</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes List -->
                            <div id="notesList">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Loading internal notes...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Mentions Tab -->
                    <div class="tab-pane fade" id="mentions-pane" role="tabpanel" aria-labelledby="mentions-tab">
                        <div class="p-3">
                            <div id="mentionsList">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Loading mentions...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Activity Tab -->
                    <div class="tab-pane fade" id="team-pane" role="tabpanel" aria-labelledby="team-tab">
                        <div class="p-3">
                            <div id="teamActivityList">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm text-info" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">Loading team activity...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4 order-xl-2 order-1">
        <!-- QR Code Card -->
        <?php if (isset($qr_data) && $qr_data): ?>
        <div class="card mb-4 d-none d-md-block">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code Access
                </h5>
                <small class="text-muted"><?= lang('App.instant_mobile_access') ?></small>
            </div>
            <div class="card-body text-center">
                <!-- Large QR Code Display -->
                <div class="qr-large-display">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Order SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
                         class="qr-sidebar-image" 
                         style="width: 200px; height: 200px; border-radius: 12px; cursor: pointer;"
                         onclick="showQRModal()"
                         title="Click to view larger">
                </div>
                
                <!-- Short URL Display -->
                <div class="mt-3">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control text-center" 
                               value="<?= $qr_data['short_url'] ?>" 
                               readonly 
                               style="font-size: 0.75rem;">
                        <button class="btn btn-outline-secondary btn-sm" 
                                type="button" 
                                onclick="copyShortUrl()"
                                title="Copy URL">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                    <small class="text-muted d-block mt-1">
                        <?= $qr_data['shortener'] ?? 'Direct URL' ?>
                    </small>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- QR Code Not Available Card -->
        <div class="card mb-4 d-none d-md-block">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code Access
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="py-4">
                    <i data-feather="alert-triangle" class="icon-lg text-warning mb-3"></i>
                    <h6 class="text-warning"><?= lang('App.qr_code_unavailable') ?></h6>
                    <p class="text-muted small mb-3"><?= lang('App.lima_links_not_configured') ?></p>
                    <button class="btn btn-outline-primary btn-sm" onclick="generateQRCode(<?= $order['id'] ?>)" data-bs-toggle="modal" data-bs-target="#qrModal">
                        <i data-feather="settings" class="icon-xs me-1"></i>
                        Generate QR
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card quick-actions-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i data-feather="zap" class="icon-sm me-2"></i>
                    <?= lang('App.quick_actions') ?>
                </h5>
                <small class="text-muted">Actions for assigned contact: <?= $order['salesperson_name'] ?? 'Not assigned' ?></small>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <!-- Update Status - Only for Staff and Admin Users -->
                    <?php if (auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])): ?>
                    <div>
                        <label class="form-label fw-medium"><?= lang('App.update_status') ?></label>
                        <select class="form-select" id="statusSelect" onchange="updateStatus()">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>⏳ <?= lang('App.pending') ?></option>
                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>⚙️ <?= lang('App.processing') ?></option>
                            <option value="in_progress" <?= $order['status'] == 'in_progress' ? 'selected' : '' ?>>🔄 <?= lang('App.in_progress') ?></option>
                            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>✅ <?= lang('App.completed') ?></option>
                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>❌ <?= lang('App.cancelled') ?></option>
                            </select>
                    </div>
                    <?php else: ?>
                    <!-- Status Display Only for Non-Staff/Admin Users -->
                    <div>
                        <label class="form-label fw-medium"><?= lang('App.current_status') ?></label>
                        <div class="form-control-plaintext">
                            <?php
                            $statusIcons = [
                                'pending' => '⏳',
                                'processing' => '⚙️',
                                'in_progress' => '🔄',
                                'completed' => '✅',
                                'cancelled' => '❌'
                            ];
                            $statusIcon = $statusIcons[$order['status']] ?? '';
                            echo $statusIcon . ' ' . lang('App.' . $order['status']);
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Contact Actions -->
                    <?php if ($order['salesperson_phone']): ?>
                    <a href="tel:<?= $order['salesperson_phone'] ?>" class="btn btn-outline-info">
                        <i data-feather="phone" class="icon-sm me-2"></i>
                        <?= lang('App.call_contact') ?>
                    </a>
                    <?php endif; ?>

                    <!-- SMS Action -->
                    <?php if ($order['salesperson_phone']): ?>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#smsModal" onclick="closeQuickActionsModal()">
                        <i data-feather="message-square" class="icon-sm me-2"></i>
                        Send SMS
                    </button>
                    <?php endif; ?>

                    <!-- Email Action -->
                    <?php if ($order['salesperson_email']): ?>
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#emailModal" onclick="closeQuickActionsModal()">
                        <i data-feather="mail" class="icon-sm me-2"></i>
                        Send Email
                    </button>
                    <?php endif; ?>

                    <!-- Notification Action -->
                    <button class="btn btn-outline-secondary" onclick="sendNotificationFromModal(<?= $order['id'] ?>)">
                        <i data-feather="bell" class="icon-sm me-2"></i>
                        Send Alert
                    </button>

                    <!-- QR Code Action -->
                    <button class="btn btn-outline-primary" onclick="generateQRCodeFromModal(<?= $order['id'] ?>)">
                        <i data-feather="smartphone" class="icon-sm me-2"></i>
                        Generate QR Code
                    </button>
                    
                    <!-- Regenerate QR Code Action -->
                    <?php if (isset($qr_data) && $qr_data): ?>
                    <button class="btn btn-outline-warning" onclick="regenerateQRCode(<?= $order['id'] ?>)">
                        <i data-feather="refresh-cw" class="icon-sm me-2"></i>
                        Regenerate QR
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile Quick Actions Floating Button -->
        <div class="quick-actions-float-btn d-xl-none" onclick="openQuickActionsModal()">
            <i data-feather="zap" class="icon-lg"></i>
        </div>

        <!-- Followers -->
        <div class="card followers-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="users" class="icon-sm me-2"></i>
                    Followers
                    <span class="badge bg-primary ms-2" id="followersCount">0</span>
                </h5>
                <button class="btn btn-sm btn-outline-success" onclick="showAddFollowerModal()">
                    <i data-feather="user-plus" class="icon-xs me-1"></i>
                    Add Follower
                </button>
            </div>
            <div class="card-body">
                <div id="followersList">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Loading followers...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card order-recent-activity">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i data-feather="activity" class="icon-sm me-2"></i>
                    <?= lang('App.recent_activity') ?>
                </h5>
                <button class="btn btn-sm btn-outline-primary" onclick="loadRecentActivity()">
                    <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                    Refresh
                </button>
            </div>
            <div class="card-body">
                <div id="activityList">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Loading activities...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-body text-center py-5">
        <div class="mb-3">
            <i data-feather="alert-circle" class="icon-lg text-muted"></i>
        </div>
        <h5 class="text-muted">Order not found</h5>
        <p class="text-muted">The requested sales order could not be found.</p>
        <a href="<?= base_url('sales_orders') ?>" class="btn btn-primary">
            <i data-feather="arrow-left" class="icon-sm me-1"></i>
            Back to Sales Orders
        </a>
    </div>
</div>
<?php endif; ?>
</div>

<!-- Universal Order Edit Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="orderModalContent">
            <!-- Modal content will be loaded via AJAX -->
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Back to previous page function
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // Fallback to sales orders index if no history
        window.location.href = '<?= base_url('sales_orders') ?>';
    }
}

// Order data for JavaScript
const orderData = {
    id: <?= $order['id'] ?? 0 ?>,
    date: '<?= $order['date'] ?? '' ?>',
    time: '<?= $order['time'] ?? '' ?>',
    status: '<?= $order['status'] ?? '' ?>',
    stock: '<?= $order['stock'] ?? '' ?>'
};

// Pagination state for infinite scroll
let activitiesPagination = {
    currentPage: 1,
    hasMore: true,
    loading: false
};

let commentsPagination = {
    currentPage: 1,
    hasMore: true,
    loading: false,
    lastScrollTrigger: null
};

// This will be consolidated into the main DOMContentLoaded listener

// Setup infinite scroll for activities
function setupActivitiesInfiniteScroll() {
    const activityList = document.getElementById('activityList');
    if (!activityList) return;

    // Add scroll event listener
    activityList.addEventListener('scroll', function() {
        if (activitiesPagination.loading || !activitiesPagination.hasMore) return;

        const { scrollTop, scrollHeight, clientHeight } = activityList;
        
        // Check if scrolled near bottom (within 100px)
        if (scrollTop + clientHeight >= scrollHeight - 100) {
            loadMoreActivities();
        }
    });
}

// Setup infinite scroll for comments
function setupCommentsInfiniteScroll() {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) {
        console.warn('Comments list element not found, retrying in 1 second...');
        // Retry after a delay
        setTimeout(setupCommentsInfiniteScroll, 1000);
        return;
    }

    // Remove any existing scroll listeners to prevent duplicates
    commentsList.removeEventListener('scroll', commentsScrollHandler);

    // Add scroll event listener
    commentsList.addEventListener('scroll', commentsScrollHandler);
    
    // Verify the element has scrollable properties
    const computedStyle = window.getComputedStyle(commentsList);
    const hasScroll = computedStyle.overflowY === 'auto' || computedStyle.overflowY === 'scroll';
    
    console.log('Comments infinite scroll initialized');
    console.log('Comments list scrollable:', hasScroll, 'Height:', commentsList.scrollHeight, 'Client height:', commentsList.clientHeight);
    
    // If no content to scroll yet, content might be loading
    if (commentsList.scrollHeight <= commentsList.clientHeight) {
        console.log('Comments list has no scroll content yet, will be handled when content loads');
    }
}

// Separate scroll handler function to allow removal
function commentsScrollHandler() {
    if (commentsPagination.loading || !commentsPagination.hasMore) {
        return;
    }

    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;

        const { scrollTop, scrollHeight, clientHeight } = commentsList;
        
    // Additional check: only trigger if there's enough content to scroll
    if (scrollHeight <= clientHeight) {
        console.log('Comments: No scrollable content, skipping infinite scroll trigger');
        return;
    }
    
    // Check if scrolled near bottom (within 50px) - reduced threshold
    if (scrollTop + clientHeight >= scrollHeight - 50) {
        console.log('Comments: Near bottom, loading more...');
        
        // Add throttling to prevent multiple rapid requests
        if (commentsPagination.lastScrollTrigger) {
            const timeSinceLastTrigger = Date.now() - commentsPagination.lastScrollTrigger;
            if (timeSinceLastTrigger < 1000) { // 1 second throttle
                console.log('Comments: Throttling scroll trigger, too soon since last request');
                return;
            }
        }
        
        commentsPagination.lastScrollTrigger = Date.now();
            loadMoreComments();
        }
}

// Load more activities
function loadMoreActivities() {
    if (activitiesPagination.loading || !activitiesPagination.hasMore) return;

    activitiesPagination.loading = true;
    activitiesPagination.currentPage++;

    // Show loading indicator
    showActivitiesLoader();

    loadRecentActivity(false); // false = append mode
}



// Show loading indicator for activities
function showActivitiesLoader() {
    const activityList = document.getElementById('activityList');
    const loader = document.createElement('div');
    loader.id = 'activityLoader';
    loader.className = 'text-center py-3';
    loader.innerHTML = `
        <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2 mb-0 small">Loading more activities...</p>
    `;
    activityList.appendChild(loader);
}

// Show loading indicator for comments (for infinite scroll)
function showCommentsLoader() {
    const commentsList = document.getElementById('commentsList');
    
    // Remove existing loader first
    removeCommentsLoader();
    
    const loader = document.createElement('div');
    loader.id = 'commentsLoader';
    loader.className = 'comments-loading';
    loader.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="text-muted ms-2">Loading more comments...</span>
    `;
    commentsList.appendChild(loader);
}

// Remove loading indicators
function removeActivitiesLoader() {
    const loader = document.getElementById('activityLoader');
    if (loader) loader.remove();
}

function removeCommentsLoader() {
    const loader = document.getElementById('commentsLoader');
    if (loader) loader.remove();
}

// Updated loadRecentActivity function with infinite scroll support
function loadRecentActivity(reset = true) {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const activityList = document.getElementById('activityList');
    
    if (reset) {
        activitiesPagination.currentPage = 1;
        activitiesPagination.hasMore = true;
        activitiesPagination.loading = false;
        
        activityList.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Loading activities...</p>
            </div>
        `;
    }
    
    const page = activitiesPagination.currentPage;
    
    fetch(`<?= base_url('sales_orders/getActivity/') ?>${orderId}?page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.activities) {
            if (reset) {
                activityList.innerHTML = '';
            } else {
                removeActivitiesLoader();
            }
            
            // Update pagination state
            if (data.pagination) {
                activitiesPagination.hasMore = data.pagination.has_more;
            }
            
            if (data.activities.length > 0) {
                data.activities.forEach(activity => {
                    const activityElement = document.createElement('div');
                    activityElement.innerHTML = createActivityHtml(activity);
                    activityList.appendChild(activityElement.firstElementChild);
                });
                
                // Re-initialize feather icons for new elements
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                
                // Initialize tooltips for activities with message content
                initializeActivityTooltips();
            } else if (reset) {
                activityList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="activity" class="icon-lg text-muted mb-2"></i>
                        <p class="text-muted mb-0">No activities yet</p>
                        <small class="text-muted">Activities will appear here as they happen</small>
                    </div>
                `;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
            
            // Activities don't use infinite scroll, they load all at once
            // No load more button needed for activities
        } else {
            if (reset) {
                activityList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                        <p class="text-muted mb-0">Error loading activities</p>
                    </div>
                `;
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    } else {
                removeActivitiesLoader();
            }
        }
    })
    .catch(error => {
        console.error('Error loading activity:', error);
        if (reset) {
            activityList.innerHTML = `
                <div class="text-center py-3">
                    <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                    <p class="text-muted mb-0">Error loading activities</p>
                    <small class="text-muted">Please try refreshing the page</small>
                </div>
            `;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        } else {
            removeActivitiesLoader();
        }
    })
    .finally(() => {
        activitiesPagination.loading = false;
    });
}

// Enhanced loadComments function with replies, mentions, and attachments support
function loadComments(reset = true) {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const commentsList = document.getElementById('commentsList');
    
    console.log(`🔄 loadComments called with reset=${reset}, currentPage=${commentsPagination.currentPage}, loading=${commentsPagination.loading}`);
    
    // Prevent multiple simultaneous requests
    if (commentsPagination.loading && !reset) {
        console.log('Comments already loading, skipping request');
        return;
    }
    
    if (reset) {
        console.log('🔄 Resetting comments pagination to page 1');
        commentsPagination.currentPage = 1;
        commentsPagination.hasMore = true;
        commentsPagination.loading = false;
        commentsPagination.lastScrollTrigger = null;
        
        commentsList.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Loading comments...</p>
            </div>
        `;
    }
    
    // Set loading state
    commentsPagination.loading = true;
    const page = commentsPagination.currentPage;
    const url = `<?= base_url('sales_orders/getComments/') ?>${orderId}?page=${page}`;
    
    console.log(`🌐 Fetching comments from URL: ${url}`);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.comments) {
            if (reset) {
                commentsList.innerHTML = '';
        } else {
                removeCommentsLoader();
            }
            
            // Update pagination state - use simple server response
            if (data.pagination) {
                const { has_more, total_comments, current_page, total_loaded, loaded_count } = data.pagination;
                
                // Trust the server's has_more response (same logic as Service Orders)
                commentsPagination.hasMore = has_more;
                
                // Update comments badge with total count
                updateCommentsCount(total_comments);
                
                console.log(`Comments pagination: page ${current_page}, server_has_more: ${has_more}, total: ${total_comments}, loaded: ${total_loaded}, loaded_count: ${loaded_count}`);
            } else {
                // If no pagination data, assume no more comments
                commentsPagination.hasMore = false;
                console.log('Comments: No pagination data received, setting hasMore to false');
            }
            
            if (data.comments.length > 0) {
                let newCommentsAdded = 0;
                data.comments.forEach(comment => {
                    // Check if comment already exists to prevent duplicates
                    const existingComment = document.getElementById(`comment-${comment.id}`);
                    if (!existingComment) {
                    const commentElement = document.createElement('div');
                    commentElement.innerHTML = createCommentHtml(comment);
                    commentsList.appendChild(commentElement.firstElementChild);
                        newCommentsAdded++;
                    } else {
                        console.log('Comments: Duplicate comment found, skipping:', comment.id);
                    }
                });
                
                console.log(`Comments: Added ${newCommentsAdded} new comments out of ${data.comments.length} received`);
                
                // Re-initialize feather icons for new elements
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            } else if (reset) {
                commentsList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="message-circle" class="icon-lg text-muted mb-2"></i>
                        <p class="text-muted mb-0">No comments yet</p>
                        <small class="text-muted">Be the first to add a comment</small>
                    </div>
                `;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            } else if (data.comments.length === 0) {
                // No more comments to load
                commentsPagination.hasMore = false;
            }
            
            // Setup infinite scroll for comments if this is the first load
            if (reset && commentsPagination.hasMore) {
                setupCommentsInfiniteScroll();
            }
                } else {
            console.error('Comments API returned error:', data.message || 'Unknown error');
            
            if (reset) {
                commentsList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                        <p class="text-muted mb-0">Error loading comments</p>
                        <small class="text-muted">' + (data.message || 'Please try refreshing the page') + '</small>
                    </div>
                `;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        } else {
                removeCommentsLoader();
                // Show error message without resetting the list
                showToast('error', data.message || 'Error loading more comments');
            }
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
        
        if (reset) {
            commentsList.innerHTML = `
                <div class="text-center py-3">
                    <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                    <p class="text-muted mb-0">Error loading comments</p>
                    <small class="text-muted">Please try refreshing the page</small>
                </div>
            `;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        } else {
            removeCommentsLoader();
            showToast('error', 'Error loading more comments');
        }
    })
    .finally(() => {
        // Always reset loading state
        commentsPagination.loading = false;
        console.log('Comments loading completed, reset loading state');
    });
}

// Setup infinite scroll for comments
function setupCommentsInfiniteScroll() {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) {
        console.log('Comments: Container not found for infinite scroll setup');
        return;
    }

    // Remove existing scroll listener to avoid duplicates
    commentsList.removeEventListener('scroll', handleCommentsScroll);
    
    // Force scrollable styles if missing
    commentsList.style.maxHeight = '400px';
    commentsList.style.overflowY = 'auto';
    
    // Add scroll event listener
    commentsList.addEventListener('scroll', handleCommentsScroll);
    
    console.log('Comments: Infinite scroll setup completed');
}

// Handle scroll events for comments with throttling
let commentsScrollTimeout = null;
function handleCommentsScroll() {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;
    
    // Clear existing timeout
    if (commentsScrollTimeout) {
        clearTimeout(commentsScrollTimeout);
    }
    
    // Throttle scroll events to avoid excessive calls
    commentsScrollTimeout = setTimeout(() => {
        const scrollTop = commentsList.scrollTop;
        const scrollHeight = commentsList.scrollHeight;
        const clientHeight = commentsList.clientHeight;
        const scrollBottom = scrollHeight - scrollTop - clientHeight;
        
        console.log('Comments scroll:', {
            scrollTop: scrollTop,
            scrollHeight: scrollHeight,
            clientHeight: clientHeight,
            scrollBottom: scrollBottom,
            currentPage: commentsPagination.currentPage,
            loading: commentsPagination.loading,
            hasMore: commentsPagination.hasMore
        });
        
        // Trigger load more when within 50px of bottom
        if (scrollBottom <= 50 && !commentsPagination.loading && commentsPagination.hasMore) {
            console.log('Comments: Triggering infinite scroll load more - incrementing page from', commentsPagination.currentPage, 'to', commentsPagination.currentPage + 1);
            commentsPagination.currentPage++;
            showCommentsLoader();
            loadComments(false); // false = append mode
        }
    }, 100); // 100ms throttle
}

// Global function to update comments count badge
function updateCommentsCount(count) {
    const badge = document.getElementById('commentsCount');
    if (badge) {
        badge.textContent = count || 0;
        console.log('updateCommentsCount: Badge updated to:', count);
    }
}

// Create activity HTML with user name and tooltips for message content
function createActivityHtml(activity) {
    const iconClass = getActivityIcon(activity.type);
    const colorClass = getActivityColor(activity.type);
    
    // Check if this activity has message content that should be shown in tooltip
    let tooltipAttributes = '';
    let tooltipContent = '';
    
    if (activity.metadata) {
        let metadata;
        try {
            metadata = typeof activity.metadata === 'string' ? JSON.parse(activity.metadata) : activity.metadata;
        } catch (e) {
            console.error('Error parsing metadata:', e, activity.metadata);
            metadata = activity.metadata;
        }
        
        // For SMS activities - show complete message
        if (activity.type === 'sms_sent' && metadata.message) {
            tooltipContent = `📱 SMS Message:\n${metadata.message}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Email activities - show subject and any additional details
        else if (activity.type === 'email_sent' && metadata.subject) {
            tooltipContent = `📧 Email Subject:\n${metadata.subject}`;
            if (metadata.recipient) {
                tooltipContent += `\n\nTo: ${metadata.recipient}`;
            }
            if (metadata.cc) {
                tooltipContent += `\nCC: ${metadata.cc}`;
            }
            if (metadata.message) {
                tooltipContent += `\n\nMessage:\n${metadata.message}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Comment activities - show complete comment
        else if (activity.type === 'comment_added' && metadata.comment) {
            tooltipContent = `💬 Complete Comment:\n${metadata.comment}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Comment Reply activities - show complete reply
        else if (activity.type === 'comment_reply_added' && metadata.comment) {
            tooltipContent = `↳ Complete Reply:\n${metadata.comment}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Internal Note activities - show complete note content
        else if ((activity.type === 'internal_note_added' || activity.type === 'internal_note_updated' || activity.type === 'internal_note_deleted') && metadata.full_content) {
            const noteIcon = activity.type === 'internal_note_added' ? '📝' : activity.type === 'internal_note_updated' ? '✏️' : '🗑️';
            const noteAction = activity.type === 'internal_note_added' ? 'Added' : activity.type === 'internal_note_updated' ? 'Updated' : 'Deleted';
            tooltipContent = `${noteIcon} Internal Note ${noteAction}:\n${metadata.full_content}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Internal Note Reply activities - show complete reply content
        else if (activity.type === 'internal_note_reply_added' && metadata.full_content) {
            tooltipContent = `💬 Internal Note Reply:\n${metadata.full_content}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
    }
        
    return `
        <div class="activity-item d-flex" ${tooltipAttributes}>
            <div class="me-3">
                <div class="bg-light rounded-circle p-2 text-${colorClass}">
                    <i data-feather="${iconClass}" class="icon-sm"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1">${activity.title} ${tooltipContent ? '<i class="icon-xs ms-1 text-muted" data-feather="info"></i>' : ''}</h6>
                <p class="text-muted mb-1">${activity.description}</p>
                <small class="text-muted">
                    <i data-feather="user" class="icon-xs me-1"></i>
                    ${activity.user_name}
                    <span class="mx-2">•</span>
                    <i data-feather="clock" class="icon-xs me-1"></i>
                    ${activity.created_at}
                </small>
            </div>
        </div>
    `;
}

// Helper function to escape content for tooltip text
function escapeForTooltipText(text) {
    return text.replace(/"/g, '&quot;')
               .replace(/'/g, '&#39;');
}

// Helper Functions
function getActivityIcon(type) {
    const icons = {
        'status_change': 'refresh-cw',
        'email_sent': 'mail',
        'sms_sent': 'message-square',
        'notification_sent': 'bell',
        'comment_added': 'message-circle',
        'comment_reply_added': 'corner-down-right',
        'order_created': 'plus-circle',
        'order_updated': 'edit-3',
        'field_change': 'edit-3',
        'overdue_alert': 'alert-triangle',
        'internal_note_added': 'file-text',
        'internal_note_updated': 'edit',
        'internal_note_deleted': 'trash',
        'internal_note_reply_added': 'corner-down-right'
    };
    return icons[type] || 'activity';
}

function getActivityColor(type) {
    const colors = {
        'status_change': 'primary',
        'email_sent': 'info',
        'sms_sent': 'success',
        'notification_sent': 'warning',
        'comment_added': 'secondary',
        'comment_reply_added': 'secondary',
        'order_created': 'success',
        'order_updated': 'primary',
        'field_change': 'primary',
        'overdue_alert': 'danger',
        'internal_note_added': 'dark',
        'internal_note_updated': 'warning',
        'internal_note_deleted': 'danger',
        'internal_note_reply_added': 'dark'
    };
    return colors[type] || 'secondary';
}

// Edit Order Function - Now opens modal instead of redirecting
function editOrder(orderId) {
    loadOrderForEdit(orderId);
}

// Download PDF Function
function downloadPdfAndRefresh(orderId) {
    if (!orderId) {
        alert('Order ID is required');
        return;
    }
    
    // Show loading indicator
    const btn = event.target;
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i data-feather="loader" class="icon-sm me-1 rotating"></i> Downloading...';
    btn.disabled = true;
    
    // Create download URL
    const downloadUrl = `${window.baseUrl}/sales_orders/downloadPdf/${orderId}`;
    
    // Create a temporary link for download (single method to avoid duplicates)
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = `Order SAL-${String(orderId).padStart(5, '0')}.pdf`;
    link.target = '_blank';
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Reset button after a delay
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        
        // Show success message
        showToast('PDF download initiated', 'success');
        
        // Refresh activities after short delay
        setTimeout(() => {
            if (typeof refreshActivities === 'function') {
                refreshActivities();
            }
        }, 1000);
        
    }, 1500); // Give time for download to start
}

// ========================================
// MODAL FUNCTIONALITY FOR EDITING ORDERS
// ========================================

// Global variables for modal management
window.salesOrderModal = {
    isEditing: false,
    currentOrderData: null
};

// Initialize modal event listeners
function initializeModalEventListeners() {
    const orderModal = document.getElementById('orderModal');
    const smsModal = document.getElementById('smsModal');
    const emailModal = document.getElementById('emailModal');
    
    // Order modal events (existing)
    if (orderModal) {
        orderModal.addEventListener('shown.bs.modal', function() {
            // DISABLED: This function interferes with the dynamic modal system
            // setupNativeEventListeners();
            if (!window.salesOrderModal.isEditing) {
                setDefaultDateTime();
            }
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
        
        orderModal.addEventListener('hidden.bs.modal', function() {
            resetModalForm();
        });
    }
    
    // SMS modal events
    if (smsModal) {
        smsModal.addEventListener('shown.bs.modal', function() {
            initializeSmsForm();
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
        
        smsModal.addEventListener('hidden.bs.modal', function() {
            // Reset SMS form
            document.getElementById('smsMessage').value = '';
            document.getElementById('smsTemplate').value = '';
            updateSmsCharCount();
        });
    }
    
    // Email modal events
    if (emailModal) {
        emailModal.addEventListener('shown.bs.modal', function() {
            initializeEmailForm();
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
        
        emailModal.addEventListener('hidden.bs.modal', function() {
            // Reset Email form
            document.getElementById('emailMessage').value = '';
            document.getElementById('emailTemplate').value = '';
            document.getElementById('emailCc').value = '';
            document.getElementById('emailIncludeOrderDetails').checked = false;
        });
    }
    
    // SMS form submission
    const smsForm = document.getElementById('smsForm');
    if (smsForm) {
        smsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendSMSMessage();
        });
    }
    
    // Email form submission
    const emailForm = document.getElementById('emailForm');
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendEmailMessage();
        });
    }
}

// Load order for editing in modal
function loadOrderForEdit(orderId) {
    console.log('Loading order for edit:', orderId);
    
    if (!orderId) {
        console.error('No order ID provided');
        showToast('error', 'No order ID provided');
        return;
    }
    
    // Set editing mode
    window.salesOrderModal.isEditing = true;
    window.salesOrderModal.currentOrderData = { id: orderId };
    
    // Show loading state
    const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
    
    // Show modal with loading state
    document.getElementById('orderModalContent').innerHTML = `
        <div class="modal-header">
            <h5 class="modal-title"><?= lang('App.edit_sales_order') ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="text-muted">Loading order data...</h6>
                <p class="text-muted small">Please wait while we fetch the order information.</p>
            </div>
        </div>
    `;
    
    orderModal.show();
    
    // Load order data via AJAX
    fetch(`<?= base_url('sales_orders/modal_form') ?>?id=${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.text();
    })
    .then(html => {
        // Update modal content with form
        document.getElementById('orderModalContent').innerHTML = html;
        
        // Execute the script from the loaded HTML manually
        setTimeout(() => {
            // Find and execute script tags in the loaded content
            const scripts = document.getElementById('orderModalContent').querySelectorAll('script');
            scripts.forEach(script => {
                if (script.textContent) {
                    try {
                        // Execute the script content
                        eval(script.textContent);
                        console.log('✅ Modal script executed successfully');
                    } catch (error) {
                        console.error('❌ Error executing modal script:', error);
                    }
                }
            });
            
            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            // Check if order status requires readonly fields
            if (typeof window.checkOrderStatusAndSetReadonly === 'function') {
                setTimeout(() => {
                    window.checkOrderStatusAndSetReadonly();
                }, 100);
            }
            
            console.log('✅ Order form loaded successfully for editing');
        }, 200);
    })
    .catch(error => {
        console.error('Error loading order for edit:', error);
        
        document.getElementById('orderModalContent').innerHTML = `
            <div class="modal-header">
                <h5 class="modal-title text-danger">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4">
                    <i data-feather="alert-circle" class="icon-lg text-danger mb-3"></i>
                    <h6 class="text-danger">Error Loading Order</h6>
                    <p class="text-muted">Unable to load order data. Please try again.</p>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        `;
        
        showToast('error', 'Error loading order for edit');
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Setup native event listeners
function setupNativeEventListeners() {
    console.log('Setting up native event listeners');
    
    // Set up client change listener
    const clientSelect = document.getElementById('client_id');
    if (clientSelect) {
        clientSelect.addEventListener('change', function(e) {
            // Extract the value from the event and check if it's a valid client ID
            const clientId = e.target.value;
            console.log('Global view handler - Client changed to:', clientId);
            
            // Only proceed if we're not in a modal context
            const isInModal = e.target.closest('.modal');
            if (isInModal) {
                console.log('Global view handler - Ignoring change inside modal');
                return;
            }
            
            handleClientChange(clientId);
        });
    }
    
    // Set up form submission
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitOrderForm();
        });
    }
}

// Initialize Choices.js selects
function initializeChoicesSelects() {
    console.log('Initializing Choices.js selects');
    
    // Check if Choices.js is available
    if (typeof Choices === 'undefined') {
        console.warn('⚠️ Choices.js not available, using native selects');
        // Fallback to native selects - just ensure they work normally
        const selectElements = document.querySelectorAll('#orderModal select');
        selectElements.forEach(select => {
            console.log(`✅ Using native select for #${select.id}`);
        });
        return;
    }
    
    // Destroy existing instances first
    if (window.choicesInstances) {
        Object.values(window.choicesInstances).forEach(instance => {
            if (instance && typeof instance.destroy === 'function') {
                try {
                    instance.destroy();
                } catch (error) {
                    console.warn('Error destroying Choices instance:', error);
                }
            }
        });
    }
    
    window.choicesInstances = {};
    
    // Initialize all select elements with data-choices attribute
    const selectElements = document.querySelectorAll('#orderModal select');
    selectElements.forEach(select => {
        if (select.id && !window.choicesInstances[select.id]) {
            try {
                // Simple Choices configuration without search for most selects
                const config = {
                    searchEnabled: false,
                    itemSelectText: '',
                    removeItemButton: false,
                    shouldSort: false,
                    placeholder: true,
                    placeholderValue: select.getAttribute('data-placeholder') || 'Select an option',
                    allowHTML: false
                };
                
                // Enable search only for client select (likely has many options)
                if (select.id === 'client_id') {
                    config.searchEnabled = true;
                    config.searchPlaceholderValue = 'Search clients...';
                }
                
                window.choicesInstances[select.id] = new Choices(select, config);
                console.log(`✅ Initialized Choices.js for #${select.id}`);
            } catch (error) {
                console.error(`❌ Error initializing Choices.js for #${select.id}:`, error);
                // Continue with native select if Choices.js fails
            }
        }
    });
}

// Setup form event listeners
function setupFormEventListeners() {
    console.log('Setting up form event listeners');
    
    // Client change handler
    const clientSelect = document.getElementById('client_id');
    if (clientSelect) {
        // Check if we have Choices.js instance for this select
        if (window.choicesInstances && window.choicesInstances['client_id']) {
            const choicesInstance = window.choicesInstances['client_id'];
            choicesInstance.passedElement.element.addEventListener('change', function(e) {
                handleClientChange(e.target.value);
            });
            console.log('✅ Set up Choices.js event listener for client select');
        } else {
            // Use native select event listener
            clientSelect.addEventListener('change', function(e) {
                handleClientChange(e.target.value);
            });
            console.log('✅ Set up native event listener for client select');
        }
    }
}

// Handle client change
function handleClientChange(clientId) {
    console.log('Client changed to:', clientId);
    
    // Validate clientId - make sure it's not an object or invalid value
    if (typeof clientId === 'object' || clientId === null || clientId === undefined) {
        console.error('Invalid clientId received:', clientId);
        return;
    }
    
    // Convert to string and trim
    clientId = String(clientId).trim();
    
    const salespersonSelect = document.getElementById('salesperson_id');
    const serviceSelect = document.getElementById('service_id');
    
    // Make sure we have the elements
    if (!salespersonSelect || !serviceSelect) {
        console.warn('Salesperson or service select elements not found, skipping handleClientChange');
        return;
    }
    
    if (!clientId) {
        // Clear contacts and services
        updateSelectOptions('salesperson_id', []);
        updateSelectOptions('service_id', []);
        return;
    }
    
    // Load contacts for selected client
    fetch(`<?= base_url('sales_orders/getContactsForClient/') ?>${clientId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateSelectOptions('salesperson_id', data.contacts);
        } else {
            console.error('Error loading contacts:', data.message);
            updateSelectOptions('salesperson_id', []);
        }
    })
    .catch(error => {
        console.error('Error loading contacts:', error);
        updateSelectOptions('salesperson_id', []);
    });
    
    // Load services for selected client
    fetch(`<?= base_url('sales_orders/getServicesForClient/') ?>${clientId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateSelectOptions('service_id', data.services);
        } else {
            console.error('Error loading services:', data.message);
            updateSelectOptions('service_id', []);
        }
    })
    .catch(error => {
        console.error('Error loading services:', error);
        updateSelectOptions('service_id', []);
    });
}

// Update select options
function updateSelectOptions(selectId, options) {
    const selectElement = document.getElementById(selectId);
    if (!selectElement) {
        console.error(`Select element #${selectId} not found`);
        return;
    }
    
    const choicesInstance = window.choicesInstances && window.choicesInstances[selectId];
    
    if (choicesInstance && typeof Choices !== 'undefined') {
        // Using Choices.js
        try {
            // Clear existing options
            choicesInstance.clearStore();
            
            // Add new options
            const formattedOptions = options.map(option => ({
                value: option.id || option.value,
                label: option.name || option.label || option.text,
                selected: false,
                disabled: false
            }));
            
            choicesInstance.setChoices(formattedOptions, 'value', 'label', true);
            console.log(`✅ Updated Choices.js options for #${selectId}`, formattedOptions.length, 'options');
        } catch (error) {
            console.error(`❌ Error updating Choices.js options for #${selectId}:`, error);
            // Fallback to native select update
            updateNativeSelectOptions(selectElement, options);
        }
    } else {
        // Using native select - this handles both modal and regular contexts
        console.log(`ℹ️ No Choices instance found for #${selectId}, using native select update`);
        updateNativeSelectOptions(selectElement, options);
    }
}

// Helper function to update native select options
function updateNativeSelectOptions(selectElement, options) {
    // Clear existing options except the first placeholder option
    const placeholder = selectElement.querySelector('option[value=""]');
    selectElement.innerHTML = '';
    
    // Re-add placeholder if it existed
    if (placeholder) {
        selectElement.appendChild(placeholder);
    } else {
        // Add default placeholder
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select an option';
        defaultOption.selected = true;
        selectElement.appendChild(defaultOption);
    }
    
    // Add new options
    options.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option.id || option.value;
        optionElement.textContent = option.name || option.label || option.text;
        selectElement.appendChild(optionElement);
    });
    
    console.log(`✅ Updated native select options for #${selectElement.id}`, options.length, 'options');
}

// Set default date and time
function setDefaultDateTime() {
    console.log('Setting default date time');
    
    const dateField = document.getElementById('date');
    const timeField = document.getElementById('time');
    
    if (dateField && !dateField.value) {
        // Set to today's date
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        dateField.value = `${year}-${month}-${day}`;
    }
    
    if (timeField && !timeField.value) {
        // Set to current time + 1 hour
        const now = new Date();
        now.setHours(now.getHours() + 1);
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        timeField.value = `${hours}:${minutes}`;
    }
}

// Reset modal form
function resetModalForm() {
    console.log('Resetting modal form');
    
    // Reset editing state
    window.salesOrderModal.isEditing = false;
    window.salesOrderModal.currentOrderData = null;
    
    // Destroy Choices.js instances
    if (window.choicesInstances) {
        Object.values(window.choicesInstances).forEach(instance => {
            if (instance && typeof instance.destroy === 'function') {
                try {
                    instance.destroy();
                } catch (error) {
                    console.warn('Error destroying Choices instance:', error);
                }
            }
        });
        window.choicesInstances = {};
    }
    
    // Clear modal content
    const modalContent = document.getElementById('orderModalContent');
    if (modalContent) {
        modalContent.innerHTML = '';
    }
}

// Submit order form
function submitOrderForm() {
    console.log('Submitting order form');
    
    const form = document.getElementById('orderForm');
    if (!form) {
        console.error('Order form not found');
        return;
    }
    
    // Get form data
    const formData = new FormData(form);
    
    // Add CSRF token
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn ? submitBtn.innerHTML : '';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    }
    
    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Order saved successfully');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('orderModal'));
            if (modal) {
                modal.hide();
            }
            
            // Refresh the page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
            
        } else {
            showToast('error', data.message || 'Error saving order');
            
            // Show validation errors if any
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const fieldElement = document.getElementById(field);
                    if (fieldElement) {
                        fieldElement.classList.add('is-invalid');
                        // Add error message
                        let errorDiv = fieldElement.parentNode.querySelector('.invalid-feedback');
                        if (!errorDiv) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            fieldElement.parentNode.appendChild(errorDiv);
                        }
                        errorDiv.textContent = data.errors[field];
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        showToast('error', 'Error submitting form');
    })
    .finally(() => {
        // Restore submit button
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

// Create comment HTML with enhanced structure for Service Orders functionality
function createCommentHtml(comment) {
    // Use various name fields available
    const authorName = comment.author_name || comment.user_name || 
                      (comment.first_name && comment.last_name ? `${comment.first_name} ${comment.last_name}` : 'Anonymous');
    
    // Escape HTML to prevent XSS and convert line breaks to <br> tags
    const commentText = comment.comment || comment.description || '';
    const escapedComment = escapeHtml(commentText).replace(/\n/g, '<br>');
    
    // Generate avatar URL
    const avatarUrl = comment.avatar_url || generateDefaultAvatar(authorName);
    
    // Format timestamp
    const timestamp = comment.created_at_formatted || comment.created_at;
    const relativeTime = comment.created_at_relative || 'recently';
    
    // Process attachments
    let attachmentsHtml = '';
    console.log('Comment attachments:', comment.attachments);
    if (comment.attachments && comment.attachments.length > 0) {
        attachmentsHtml = `
            <div class="comment-attachments">
                <div class="attachments-title">
                    <i data-feather="paperclip" class="icon-xs me-1"></i>
                    Attachments (${comment.attachments.length})
                    </div>
                <div class="attachment-list">
                    ${comment.attachments.map(attachment => createAttachmentHtml(attachment)).join('')}
                </div>
            </div>
        `;
    }
    
    // Process mentions
    let processedComment = escapedComment;
    if (comment.mentions && comment.mentions.length > 0) {
        comment.mentions.forEach(mention => {
            const mentionRegex = new RegExp(`@${mention.username}`, 'g');
            processedComment = processedComment.replace(mentionRegex, 
                `<span class="mention">@${mention.username}</span>`);
        });
    }
    
    // Process replies
    let repliesHtml = '';
    if (comment.replies && comment.replies.length > 0) {
        repliesHtml = `
            <div class="comment-replies">
                ${comment.replies.map(reply => createReplyHtml(reply)).join('')}
            </div>
        `;
    }
    
    return `
        <div class="comment-item" id="comment-${comment.id}">
            <div class="comment-header">
                <div class="comment-user-info d-flex align-items-center">
                    <img src="${avatarUrl}" alt="${authorName}" class="rounded-circle me-2" width="32" height="32">
                    <div>
                        <div class="fw-semibold">${authorName}</div>
                        <small class="text-muted" title="${timestamp}">${relativeTime}</small>
                    </div>
                </div>
                <div class="comment-actions">
                    <button class="btn btn-sm btn-outline-secondary" onclick="showReplyForm(${comment.id})" title="Reply">
                        <i data-feather="corner-down-left" class="icon-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="editComment(${comment.id})" title="Edit">
                        <i data-feather="edit-2" class="icon-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})" title="Delete">
                        <i data-feather="trash-2" class="icon-xs"></i>
                    </button>
                </div>
            </div>
            <div class="comment-content mt-2">
                ${processedComment}
                ${attachmentsHtml}
            </div>
            ${repliesHtml}
            <div class="reply-form" id="reply-form-${comment.id}">
                <div class="d-flex gap-2 mt-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Write a reply..." id="reply-input-${comment.id}">
                    <button class="btn btn-primary btn-sm" onclick="submitReply(${comment.id})">Reply</button>
                    <button class="btn btn-secondary btn-sm" onclick="hideReplyForm(${comment.id})">Cancel</button>
                </div>
            </div>
        </div>
    `;
}

// Create attachment HTML
function createAttachmentHtml(attachment) {
    const extension = attachment.original_name ? attachment.original_name.split('.').pop().toLowerCase() : '';
    const viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'txt', 'html', 'htm'];
    const canView = viewableTypes.includes(extension);
    
    // Get file icon and thumbnail
    const fileIcon = getFileIcon(extension);
    const thumbnail = getFileThumbnail(attachment, extension);
    
    return `
        <div class="attachment-item">
            <div class="attachment-thumbnail">
                ${thumbnail}
            </div>
            <div class="attachment-info">
                <div class="attachment-header">
                    ${fileIcon}
                    <span class="attachment-name" title="${attachment.original_name}">${attachment.original_name}</span>
                </div>
                <span class="attachment-size">${formatFileSize(attachment.size || 0)}</span>
            </div>
            <div class="attachment-actions">
                ${canView ? `<a href="${attachment.url}" class="btn btn-sm btn-outline-primary me-1" target="_blank" title="Ver en navegador">
                    <i data-feather="eye" class="icon-xs"></i>
                </a>` : ''}
                <a href="${attachment.url}" class="btn btn-sm btn-outline-secondary" title="Descargar" download="${attachment.original_name}">
                    <i data-feather="download" class="icon-xs"></i>
                </a>
            </div>
        </div>
    `;
}

// Get file icon based on extension
function getFileIcon(extension) {
    const iconMap = {
        // Images
        'jpg': '<i class="fas fa-file-image text-success"></i>',
        'jpeg': '<i class="fas fa-file-image text-success"></i>',
        'png': '<i class="fas fa-file-image text-success"></i>',
        'gif': '<i class="fas fa-file-image text-success"></i>',
        'webp': '<i class="fas fa-file-image text-success"></i>',
        'svg': '<i class="fas fa-file-image text-success"></i>',
        
        // Documents
        'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
        'doc': '<i class="fas fa-file-word text-primary"></i>',
        'docx': '<i class="fas fa-file-word text-primary"></i>',
        'xls': '<i class="fas fa-file-excel text-success"></i>',
        'xlsx': '<i class="fas fa-file-excel text-success"></i>',
        'ppt': '<i class="fas fa-file-powerpoint text-warning"></i>',
        'pptx': '<i class="fas fa-file-powerpoint text-warning"></i>',
        
        // Text
        'txt': '<i class="fas fa-file-alt text-secondary"></i>',
        'html': '<i class="fas fa-file-code text-info"></i>',
        'css': '<i class="fas fa-file-code text-info"></i>',
        'js': '<i class="fas fa-file-code text-info"></i>',
        
        // Archives
        'zip': '<i class="fas fa-file-archive text-warning"></i>',
        'rar': '<i class="fas fa-file-archive text-warning"></i>',
        '7z': '<i class="fas fa-file-archive text-warning"></i>',
        
        // Audio
        'mp3': '<i class="fas fa-file-audio text-purple"></i>',
        'wav': '<i class="fas fa-file-audio text-purple"></i>',
        'ogg': '<i class="fas fa-file-audio text-purple"></i>',
        
        // Video
        'mp4': '<i class="fas fa-file-video text-danger"></i>',
        'avi': '<i class="fas fa-file-video text-danger"></i>',
        'mov': '<i class="fas fa-file-video text-danger"></i>',
        'wmv': '<i class="fas fa-file-video text-danger"></i>',
        'flv': '<i class="fas fa-file-video text-danger"></i>'
    };
    
    return iconMap[extension] || '<i class="fas fa-file text-secondary"></i>';
}

// Get file thumbnail
function getFileThumbnail(attachment, extension) {
    const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    
    if (imageTypes.includes(extension) && attachment.thumbnail) {
        // For images with thumbnails, show the thumbnail
        return `<img src="${attachment.thumbnail}" alt="${attachment.original_name}" class="file-thumbnail-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="file-thumbnail-fallback" style="display: none;">
                    <i class="fas fa-image text-success"></i>
                </div>`;
    } else if (imageTypes.includes(extension)) {
        // For images without thumbnails, try to show the original
        return `<img src="${attachment.url}" alt="${attachment.original_name}" class="file-thumbnail-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="file-thumbnail-fallback" style="display: none;">
                    <i class="fas fa-image text-success"></i>
                </div>`;
    } else {
        // For other files, show a file type icon
        const fileTypeIcons = {
            'pdf': '<i class="fas fa-file-pdf text-danger file-thumbnail-icon"></i>',
            'doc': '<i class="fas fa-file-word text-primary file-thumbnail-icon"></i>',
            'docx': '<i class="fas fa-file-word text-primary file-thumbnail-icon"></i>',
            'xls': '<i class="fas fa-file-excel text-success file-thumbnail-icon"></i>',
            'xlsx': '<i class="fas fa-file-excel text-success file-thumbnail-icon"></i>',
            'zip': '<i class="fas fa-file-archive text-warning file-thumbnail-icon"></i>',
            'mp3': '<i class="fas fa-file-audio text-purple file-thumbnail-icon"></i>',
            'mp4': '<i class="fas fa-file-video text-danger file-thumbnail-icon"></i>'
        };
        
        return fileTypeIcons[extension] || '<i class="fas fa-file text-secondary file-thumbnail-icon"></i>';
    }
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Create reply HTML
function createReplyHtml(reply) {
    const authorName = reply.author_name || reply.user_name || 
                      (reply.first_name && reply.last_name ? `${reply.first_name} ${reply.last_name}` : 'Anonymous');
    const escapedReply = escapeHtml(reply.comment || reply.description || '').replace(/\n/g, '<br>');
    const avatarUrl = reply.avatar_url || generateDefaultAvatar(authorName);
    const timestamp = reply.created_at_formatted || reply.created_at;
    const relativeTime = reply.created_at_relative || 'recently';
    
    return `
        <div class="comment-reply" id="reply-${reply.id}">
            <div class="reply-header">
                <div class="d-flex align-items-center">
                    <img src="${avatarUrl}" alt="${authorName}" class="reply-avatar rounded-circle me-2">
                    <div>
                        <span class="reply-user-name">${authorName}</span>
                        <small class="reply-timestamp text-muted ms-2" title="${timestamp}">${relativeTime}</small>
                    </div>
                </div>
                <div class="reply-actions">
                    <button class="btn btn-sm btn-outline-secondary" onclick="editReply(${reply.id})" title="Edit">
                        <i data-feather="edit-2" class="icon-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteReply(${reply.id})" title="Delete">
                        <i data-feather="trash-2" class="icon-xs"></i>
                    </button>
                </div>
            </div>
            <div class="reply-content">${escapedReply}</div>
        </div>
    `;
}

// Generate default avatar
function generateDefaultAvatar(name) {
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&size=32&background=6c757d&color=ffffff&bold=true&format=png`;
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Add reply to specific comment without reloading all comments
function addReplyToComment(commentId, replyData) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) {
        console.warn('Comment element not found, falling back to full reload');
        loadComments(true);
        return;
    }
    
    // Find or create the replies container
    let repliesContainer = commentElement.querySelector('.comment-replies');
    if (!repliesContainer) {
        // Create replies container if it doesn't exist
        repliesContainer = document.createElement('div');
        repliesContainer.className = 'comment-replies';
        
        // Insert before the reply form
        const replyForm = commentElement.querySelector('.reply-form');
        if (replyForm) {
            commentElement.insertBefore(repliesContainer, replyForm);
        } else {
            commentElement.appendChild(repliesContainer);
        }
    }
    
    // Create the reply HTML
    const replyHtml = createReplyHtml(replyData);
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = replyHtml;
    const replyElement = tempDiv.firstElementChild;
    
    // Add the reply to the container
    repliesContainer.appendChild(replyElement);
    
    // Re-initialize feather icons for the new reply
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    console.log('Reply added successfully to comment', commentId);
}

// Enhanced comment functionality functions
function showReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.add('show');
        const input = document.getElementById(`reply-input-${commentId}`);
        if (input) input.focus();
    }
}

function hideReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.remove('show');
        const input = document.getElementById(`reply-input-${commentId}`);
        if (input) input.value = '';
    }
}

function submitReply(commentId) {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const input = document.getElementById(`reply-input-${commentId}`);
    const replyText = input ? input.value.trim() : '';
    
    if (!replyText) {
        showToast('error', 'Please enter a reply');
        return;
    }
    
    // Show loading state on the button
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinning"></i>Adding...';
    
    fetch(`<?= base_url('sales_orders/addReply') ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `parent_id=${commentId}&comment=${encodeURIComponent(replyText)}&sales_order_id=${orderId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Reply added successfully');
            hideReplyForm(commentId);
            
            // Add the reply directly to the comment instead of reloading all comments
            if (data.reply) {
                addReplyToComment(commentId, data.reply);
                
                // Increment comments count immediately for better UX
                const currentBadge = document.getElementById('commentsCount');
                if (currentBadge) {
                    const currentCount = parseInt(currentBadge.textContent) || 0;
                    updateCommentsCount(currentCount + 1);
                }
                
                // Reload activities to show the reply activity
                loadRecentActivity(true);
            } else {
                // Fallback to reloading comments if reply data is not returned
                loadComments(true);
            }
        } else {
            showToast('error', data.message || 'Error adding reply');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error adding reply');
    })
    .finally(() => {
        // Restore button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

function editComment(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) {
        showToast('error', 'Comment not found');
        return;
    }
    
    // Get the current comment content
    const contentElement = commentElement.querySelector('.comment-content');
    if (!contentElement) {
        showToast('error', 'Comment content not found');
        return;
    }
    
    // Extract text content, removing HTML tags but preserving line breaks
    const currentText = contentElement.innerHTML
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<[^>]*>/g, '')
        .trim();
    
    // Create edit form
    const editForm = document.createElement('div');
    editForm.className = 'comment-edit-form mt-2';
    editForm.innerHTML = `
        <div class="mb-2">
            <textarea class="form-control" id="edit-comment-${commentId}" rows="3" placeholder="Edit your comment...">${escapeHtml(currentText)}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" onclick="saveCommentEdit(${commentId})">
                <i data-feather="check" class="icon-xs me-1"></i>Save
            </button>
            <button class="btn btn-secondary btn-sm" onclick="cancelCommentEdit(${commentId})">
                <i data-feather="x" class="icon-xs me-1"></i>Cancel
            </button>
        </div>
    `;
    
    // Hide original content and actions
    contentElement.style.display = 'none';
    const actionsElement = commentElement.querySelector('.comment-actions');
    if (actionsElement) {
        actionsElement.style.display = 'none';
    }
    
    // Insert edit form after content
    contentElement.parentNode.insertBefore(editForm, contentElement.nextSibling);
    
    // Focus on textarea
    const textarea = document.getElementById(`edit-comment-${commentId}`);
    if (textarea) {
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    }
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

function deleteComment(commentId) {
    Swal.fire({
        title: 'Delete Comment?',
        text: 'Are you sure you want to delete this comment? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting Comment...',
                text: 'Please wait while we delete the comment.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
    fetch(`<?= base_url('sales_orders/deleteComment/') ?>${commentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Comment Deleted!',
                        text: 'The comment has been deleted successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Decrement comments count immediately for better UX
                    const currentBadge = document.getElementById('commentsCount');
                    if (currentBadge) {
                        const currentCount = parseInt(currentBadge.textContent) || 0;
                        updateCommentsCount(Math.max(0, currentCount - 1));
                    }
                    
            loadComments(true); // Refresh comments
        } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: data.message || 'Error deleting comment'
                    });
        }
    })
    .catch(error => {
        console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the comment'
                });
            });
        }
    });
}

// Save comment edit
function saveCommentEdit(commentId) {
    const textarea = document.getElementById(`edit-comment-${commentId}`);
    if (!textarea) {
        showToast('error', 'Edit form not found');
        return;
    }
    
    const newContent = textarea.value.trim();
    if (!newContent) {
        showToast('error', 'Comment cannot be empty');
        return;
    }
    
    // Show loading state
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinning"></i>Saving...';
    
    fetch(`<?= base_url('sales_orders/updateComment/') ?>${commentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `description=${encodeURIComponent(newContent)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Comment updated successfully');
            
            // Update the comment content in place
            const commentElement = document.getElementById(`comment-${commentId}`);
            const contentElement = commentElement.querySelector('.comment-content');
            
            // Update content with new text (convert line breaks to <br>)
            const processedContent = escapeHtml(newContent).replace(/\n/g, '<br>');
            contentElement.innerHTML = processedContent;
            
            // Remove edit form and show original content
            cancelCommentEdit(commentId);
            
            // Reload activities to show the edit activity
            loadRecentActivity(true);
        } else {
            showToast('error', data.message || 'Error updating comment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error updating comment');
    })
    .finally(() => {
        // Restore button state
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Cancel comment edit
function cancelCommentEdit(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) return;
    
    // Remove edit form
    const editForm = commentElement.querySelector('.comment-edit-form');
    if (editForm) {
        editForm.remove();
    }
    
    // Show original content and actions
    const contentElement = commentElement.querySelector('.comment-content');
    if (contentElement) {
        contentElement.style.display = 'block';
    }
    
    const actionsElement = commentElement.querySelector('.comment-actions');
    if (actionsElement) {
        actionsElement.style.display = 'flex';
    }
}

function editReply(replyId) {
    const replyElement = document.getElementById(`reply-${replyId}`);
    if (!replyElement) {
        showToast('error', 'Reply not found');
        return;
    }
    
    // Get the current reply content
    const contentElement = replyElement.querySelector('.reply-content');
    if (!contentElement) {
        showToast('error', 'Reply content not found');
        return;
    }
    
    // Extract text content, removing HTML tags but preserving line breaks
    const currentText = contentElement.innerHTML
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<[^>]*>/g, '')
        .trim();
    
    // Create edit form
    const editForm = document.createElement('div');
    editForm.className = 'reply-edit-form mt-2';
    editForm.innerHTML = `
        <div class="mb-2">
            <textarea class="form-control form-control-sm" id="edit-reply-${replyId}" rows="2" placeholder="Edit your reply...">${escapeHtml(currentText)}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" onclick="saveReplyEdit(${replyId})">
                <i data-feather="check" class="icon-xs me-1"></i>Save
            </button>
            <button class="btn btn-secondary btn-sm" onclick="cancelReplyEdit(${replyId})">
                <i data-feather="x" class="icon-xs me-1"></i>Cancel
            </button>
        </div>
    `;
    
    // Hide original content and actions
    contentElement.style.display = 'none';
    const actionsElement = replyElement.querySelector('.reply-actions');
    if (actionsElement) {
        actionsElement.style.display = 'none';
    }
    
    // Insert edit form after content
    contentElement.parentNode.insertBefore(editForm, contentElement.nextSibling);
    
    // Focus on textarea
    const textarea = document.getElementById(`edit-reply-${replyId}`);
    if (textarea) {
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    }
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Save reply edit
function saveReplyEdit(replyId) {
    const textarea = document.getElementById(`edit-reply-${replyId}`);
    if (!textarea) {
        showToast('error', 'Edit form not found');
        return;
    }
    
    const newContent = textarea.value.trim();
    if (!newContent) {
        showToast('error', 'Reply cannot be empty');
        return;
    }
    
    // Show loading state
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinning"></i>Saving...';
    
    fetch(`<?= base_url('sales_orders/updateComment/') ?>${replyId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `description=${encodeURIComponent(newContent)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Reply updated successfully');
            
            // Update the reply content in place
            const replyElement = document.getElementById(`reply-${replyId}`);
            const contentElement = replyElement.querySelector('.reply-content');
            
            // Update content with new text (convert line breaks to <br>)
            const processedContent = escapeHtml(newContent).replace(/\n/g, '<br>');
            contentElement.innerHTML = processedContent;
            
            // Remove edit form and show original content
            cancelReplyEdit(replyId);
            
            // Reload activities to show the edit activity
            loadRecentActivity(true);
        } else {
            showToast('error', data.message || 'Error updating reply');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error updating reply');
    })
    .finally(() => {
        // Restore button state
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Cancel reply edit
function cancelReplyEdit(replyId) {
    const replyElement = document.getElementById(`reply-${replyId}`);
    if (!replyElement) return;
    
    // Remove edit form
    const editForm = replyElement.querySelector('.reply-edit-form');
    if (editForm) {
        editForm.remove();
    }
    
    // Show original content and actions
    const contentElement = replyElement.querySelector('.reply-content');
    if (contentElement) {
        contentElement.style.display = 'block';
    }
    
    const actionsElement = replyElement.querySelector('.reply-actions');
    if (actionsElement) {
        actionsElement.style.display = 'flex';
    }
}

function deleteReply(replyId) {
    Swal.fire({
        title: 'Delete Reply?',
        text: 'Are you sure you want to delete this reply? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting Reply...',
                text: 'Please wait while we delete the reply.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
    fetch(`<?= base_url('sales_orders/deleteComment/') ?>${replyId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reply Deleted!',
                        text: 'The reply has been deleted successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Decrement comments count immediately for better UX (replies count as comments)
                    const currentBadge = document.getElementById('commentsCount');
                    if (currentBadge) {
                        const currentCount = parseInt(currentBadge.textContent) || 0;
                        updateCommentsCount(Math.max(0, currentCount - 1));
                    }
                    
            loadComments(true); // Refresh comments
        } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete Failed',
                        text: data.message || 'Error deleting reply'
                    });
        }
    })
    .catch(error => {
        console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the reply'
                });
            });
        }
    });
}

// Initialize tooltips for activities with message content
function initializeActivityTooltips() {
    // Dispose existing tooltips first to avoid duplicates
    const existingTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    existingTooltips.forEach(element => {
        const tooltip = bootstrap.Tooltip.getInstance(element);
        if (tooltip) {
            tooltip.dispose();
        }
    });
    
    // Initialize new tooltips for activities with tooltip data
    const tooltipElements = document.querySelectorAll('.activity-with-tooltip[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(element => {
        new bootstrap.Tooltip(element, {
            html: false,
            placement: 'top',
            trigger: 'hover focus',
            delay: { show: 300, hide: 150 },
            container: 'body',
            template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
        });
    });
}

// Initialize Forms
function initializeForms() {
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitComment();
        });
    }
    
    // Initialize attachment counter
    const attachmentInput = document.getElementById('commentAttachments');
    if (attachmentInput) {
        attachmentInput.addEventListener('change', updateAttachmentCount);
    }
}

// Update attachment count display
function updateAttachmentCount() {
    const attachmentInput = document.getElementById('commentAttachments');
    const countSpan = document.getElementById('attachmentCount');
    
    if (attachmentInput && countSpan) {
        const fileCount = attachmentInput.files.length;
        if (fileCount > 0) {
            countSpan.textContent = `${fileCount} file${fileCount > 1 ? 's' : ''} selected`;
            countSpan.className = 'text-primary small ms-2';
        } else {
            countSpan.textContent = '';
        }
    }
}

// Submit Comment Function
function submitComment() {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const commentText = document.getElementById('commentText').value.trim();
    const attachments = document.getElementById('commentAttachments').files;
    
    if (!commentText) {
        showToast('error', 'Please enter a comment');
        return;
    }
    
    const submitBtn = document.querySelector('#commentForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-feather="loader" class="icon-xs me-1 spinning"></i>Adding...';
    
    // Create FormData to handle file uploads
    const formData = new FormData();
    formData.append('comment', commentText);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    // Add attachments if any
    for (let i = 0; i < attachments.length; i++) {
        formData.append('attachments[]', attachments[i]);
    }
    
    fetch(`<?= base_url('sales_orders/addComment/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Comment added successfully');
            
            // Clear the form
            document.getElementById('commentText').value = '';
            document.getElementById('commentAttachments').value = '';
            updateAttachmentCount();
            
            // Increment comments count immediately for better UX
            const currentBadge = document.getElementById('commentsCount');
            if (currentBadge) {
                const currentCount = parseInt(currentBadge.textContent) || 0;
                updateCommentsCount(currentCount + 1);
            }
            
            // Reload comments to show the new comment
            loadComments(true);
            
            // Reload activities to show the comment activity
            loadRecentActivity(true);
        } else {
            showToast('error', data.message || 'Error adding comment');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error adding comment');
    })
    .finally(() => {
        // Restore button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
        });
    }
    
// Update Status Function - Improved with SweetAlert confirmation and loading states
function updateStatus() {
    const statusSelect = document.getElementById('statusSelect');
    const newStatus = statusSelect.value;
    const orderId = <?= $order['id'] ?? 0 ?>;

    console.log('🔄 Updating status:', { orderId, newStatus, currentStatus: orderData.status });

    if (!newStatus) {
        Swal.fire({
            icon: 'warning',
            title: 'No Status Selected',
            text: 'Please select a status to update'
        });
        return;
    }

    if (newStatus === orderData.status) {
        Swal.fire({
            icon: 'info',
            title: 'Status Unchanged',
            text: `Status is already set to ${newStatus}`,
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    // Show confirmation dialog
    const statusConfig = getStatusConfig(newStatus);
    const currentStatusConfig = getStatusConfig(orderData.status);
    
    Swal.fire({
        title: 'Update Order Status?',
        html: `
            <div class="text-start">
                <p>Are you sure you want to change the status?</p>
                <div class="row">
                    <div class="col-6">
                        <strong>From:</strong><br>
                        <span class="badge ${currentStatusConfig.class}">${currentStatusConfig.text}</span>
                    </div>
                    <div class="col-6">
                        <strong>To:</strong><br>
                        <span class="badge ${statusConfig.class}">${statusConfig.text}</span>
                    </div>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, update status',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Updating Status...',
                text: 'Please wait while we update the order status.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

    console.log('📤 Sending status update request...');

    fetch(`<?= base_url('sales_orders/updateStatus/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `status=${encodeURIComponent(newStatus)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => {
        console.log('📥 Response received:', response.status, response.statusText);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('✅ Status update response:', data);
        
        if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated',
                        text: data.message || `Status updated to ${statusConfig.text}`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
            // Update global order data
            orderData.status = newStatus;
            
            // Update the top bar status widget
            updateTopBarStatus(newStatus);
            
            // Update all status badges in the page
            updateAllStatusBadges(newStatus);
            
            // Update the select to show the new status
            statusSelect.value = newStatus;
            
            // Reload activities to show status change
            console.log('🔄 Reloading activities...');
            loadRecentActivity(true);
            
            console.log('✅ Status update completed successfully');
                    });
        } else {
            console.error('❌ Status update failed:', data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'Error updating status'
                    });
            
            // Reset select to previous value
            statusSelect.value = orderData.status;
        }
    })
    .catch(error => {
        console.error('❌ Status update error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the status'
                });
        
        // Reset select to previous value
        statusSelect.value = orderData.status;
            });
        } else {
            // User cancelled, reset select to previous value
            statusSelect.value = orderData.status;
        }
    });
}

// Function to update the top bar status widget
function updateTopBarStatus(newStatus) {
    // Get status configuration
    const statusConfig = getStatusConfig(newStatus);
    
    // Update the top bar status badge using the specific ID
    const topBarStatusBadge = document.getElementById('topBarStatusBadge');
    if (topBarStatusBadge) {
        // Remove all existing status classes
        topBarStatusBadge.classList.remove('bg-warning', 'bg-success', 'bg-danger', 'bg-info', 'bg-primary');
        
        // Add new status class
        topBarStatusBadge.classList.add(statusConfig.class);
        topBarStatusBadge.textContent = statusConfig.text;
    }
    
    // Update timestamps in both top bar and details section
        const now = new Date();
    const topBarTime = now.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true 
    });
    const detailsTime = now.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    
    // Update the "Last updated" time in top bar using the specific ID
    const topBarLastUpdated = document.getElementById('topBarLastUpdated');
    if (topBarLastUpdated) {
        topBarLastUpdated.innerHTML = `Last updated: ${topBarTime}`;
    }
    
    // Update the "Last updated" time in details section
    const detailsLastUpdated = document.getElementById('detailsLastUpdated');
    if (detailsLastUpdated) {
        detailsLastUpdated.textContent = detailsTime;
    }
}

// Function to update all status badges in the page
function updateAllStatusBadges(newStatus) {
    const statusConfig = getStatusConfig(newStatus);
    
    // Update specific status badges by ID
    const badgeIds = ['topBarStatusBadge', 'detailsStatusBadge'];
    
    badgeIds.forEach(badgeId => {
        const badge = document.getElementById(badgeId);
        if (badge) {
            // Remove all status classes
            badge.classList.remove('bg-warning', 'bg-success', 'bg-danger', 'bg-info', 'bg-primary');
            
            // Add new status class
            badge.classList.add(statusConfig.class);
            badge.textContent = statusConfig.text;
        }
    });
}

// Function to get status configuration (class and text)
function getStatusConfig(status) {
    const statusConfigs = {
        'pending': {
            class: 'bg-warning',
            text: 'Pending'
        },
        'processing': {
            class: 'bg-primary',
            text: 'Processing'
        },
        'in_progress': {
            class: 'bg-info',
            text: 'In Progress'
        },
        'completed': {
            class: 'bg-success',
            text: 'Completed'
        },
        'cancelled': {
            class: 'bg-danger',
            text: 'Cancelled'
        }
    };
    
    return statusConfigs[status] || {
        class: 'bg-warning',
        text: 'Unknown'
    };
}

function showToast(type, message) {
    // Simple toast notification using SweetAlert2
    const icon = type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info';
    
    Swal.fire({
        icon: icon,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
        timer: 3000,
            timerProgressBar: true
    });
}

// Utility functions
function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        timer: 5000,
        showConfirmButton: true,
        toast: true,
        position: 'top-end'
    });
}

// ========================================
// QUICK ACTIONS FUNCTIONS (SMS, EMAIL, NOTIFICATIONS)
// ========================================

// Send Notification Function
function sendNotification(orderId) {
    const advisorName = '<?= $order['salesperson_name'] ?? 'Advisor' ?>';
    const orderNumber = 'ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    
    Swal.fire({
        title: 'Send Alert Notification',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">To: ${advisorName}</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alert Type:</label>
                    <select id="alertType" class="form-select">
                        <option value="urgent">🔴 Urgent - Immediate Attention Required</option>
                        <option value="priority">🟡 Priority - Review Soon</option>
                        <option value="info">🔵 Information - General Update</option>
                        <option value="reminder">⏰ Reminder - Follow Up Needed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message:</label>
                    <textarea id="alertMessage" class="form-control" rows="3" placeholder="Enter alert message...">${orderNumber} requires your attention.</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Send Alert',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const alertType = document.getElementById('alertType').value;
            const message = document.getElementById('alertMessage').value.trim();
            if (!message) {
                Swal.showValidationMessage('Please enter an alert message');
                return false;
            }
            return { alertType, message };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const alertData = result.value;
            
            // Show loading
            Swal.fire({
                title: 'Sending Alert...',
                text: 'Please wait while we send the notification.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

    fetch(`<?= base_url('sales_orders/sendAlert/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
                body: `alert_type=${encodeURIComponent(alertData.alertType)}&message=${encodeURIComponent(alertData.message)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Alert Sent!',
                        text: data.message || `${alertData.alertType.toUpperCase()} alert sent successfully`,
                        timer: 2000,
                        showConfirmButton: false
                    });
            // Reload activities to show the notification activity
            loadRecentActivity(true);
        } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Send Failed',
                        text: data.message || 'Error sending alert'
                    });
        }
    })
    .catch(error => {
        console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while sending the alert'
                });
            });
        }
    });
}

// ========================================
// SMS FUNCTIONS
// ========================================

// Function to shorten URL for SMS (added for better SMS character management)
let urlCache = {}; // Cache for shortened URLs
async function shortenUrl(longUrl) {
    // Check cache first
    if (urlCache[longUrl]) {
        console.log('✅ Using cached shortened URL:', urlCache[longUrl]);
        return urlCache[longUrl];
    }
    
    // Try Lima Links API first (professional service)
    try {
        const limaApiKey = '<?= env('LIMA_API_KEY') ?? '' ?>';
        if (limaApiKey) {
            const limaPayload = {
                url: longUrl,
                type: 'direct',
                description: 'Sales Order <?= $order['id'] ?? '' ?> - <?= $order['client_name'] ?? 'Order' ?>'
            };
            
            // Add branded domain if configured
            const branddedDomain = '<?= env('LIMA_BRANDED_DOMAIN') ?? '' ?>';
            if (branddedDomain) {
                limaPayload.domain = branddedDomain;
            }
            
            // Add custom alias with order number
            const customAlias = 'order-<?= $order['id'] ?? '' ?>-<?= date('Ymd') ?>';
            limaPayload.custom = customAlias;
            
            console.log('🔗 Attempting Lima Links shortening...');
            const limaResponse = await fetch('<?= \App\Helpers\LimaLinksHelper::buildApiUrl() ?>', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${limaApiKey}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(limaPayload)
            });
            
            if (limaResponse.ok) {
                const limaData = await limaResponse.json();
                if (limaData.error === 0 && limaData.shorturl) {
                    console.log('✅ URL shortened with Lima Links:', limaData.shorturl);
                    urlCache[longUrl] = limaData.shorturl; // Cache the result
                    
                    // Update character count immediately with actual short URL
                    setTimeout(updateSmsCharCount, 100);
                    
                    return limaData.shorturl;
                } else {
                    console.warn('⚠️ Lima Links API error:', limaData.message || 'Unknown error');
                }
            } else {
                console.warn('⚠️ Lima Links HTTP error:', limaResponse.status, limaResponse.statusText);
            }
        } else {
            console.log('ℹ️ Lima Links API key not configured, skipping...');
        }
    } catch (error) {
        console.warn('⚠️ Lima Links failed:', error);
    }
    
    try {
        // Fallback to TinyURL (free, no API key needed)
        const tinyResponse = await fetch(`https://tinyurl.com/api-create.php?url=${encodeURIComponent(longUrl)}`);
        if (tinyResponse.ok) {
            const shortUrl = await tinyResponse.text();
            if (shortUrl && !shortUrl.includes('Error') && shortUrl.startsWith('http')) {
                console.log('✅ URL shortened with TinyURL:', shortUrl);
                urlCache[longUrl] = shortUrl; // Cache the result
                return shortUrl;
            }
        }
    } catch (error) {
        console.warn('⚠️ TinyURL failed:', error);
    }

    try {
        // Final fallback to is.gd (also free, no API key needed)
        const isgdResponse = await fetch(`https://is.gd/create.php?format=simple&url=${encodeURIComponent(longUrl)}`);
        if (isgdResponse.ok) {
            const shortUrl = await isgdResponse.text();
            if (shortUrl && !shortUrl.includes('Error') && shortUrl.startsWith('http')) {
                console.log('✅ URL shortened with is.gd:', shortUrl);
                return shortUrl;
            }
        }
    } catch (error) {
        console.warn('⚠️ is.gd failed:', error);
    }

    // If both fail, return original URL
    console.warn('⚠️ URL shortening failed, using original URL');
    return longUrl;
}

// Load SMS templates
function loadSmsTemplates() {
    console.log('🔄 Loading SMS templates...');
    
    fetch(`<?= base_url('settings/getSmsTemplates') ?>`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.templates) {
            const templateSelect = document.getElementById('smsTemplate');
            
            // Clear existing options except the first one
            while (templateSelect.children.length > 1) {
                templateSelect.removeChild(templateSelect.lastChild);
            }
            
            // Add templates
            data.templates.forEach(template => {
                const option = document.createElement('option');
                option.value = JSON.stringify({
                    name: template.name,
                    content: template.content
                });
                option.textContent = template.name;
                templateSelect.appendChild(option);
            });
            
            console.log(`✅ Loaded ${data.templates.length} SMS templates`);
        } else {
            console.log('ℹ️ No SMS templates available');
        }
    })
    .catch(error => {
        console.error('❌ Error loading SMS templates:', error);
    });
}

// Load Email templates
function loadEmailTemplates() {
    console.log('🔄 Loading Email templates...');
    
    fetch(`<?= base_url('settings/getEmailTemplates') ?>`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.templates) {
            const templateSelect = document.getElementById('emailTemplate');
            
            // Clear existing options except the first one
            while (templateSelect.children.length > 1) {
                templateSelect.removeChild(templateSelect.lastChild);
            }
            
            // Add templates
            data.templates.forEach(template => {
                const option = document.createElement('option');
                option.value = JSON.stringify({
                    name: template.name,
                    subject: template.subject,
                    content: template.content
                });
                option.textContent = template.name;
                templateSelect.appendChild(option);
            });
            
            console.log(`✅ Loaded ${data.templates.length} Email templates`);
        } else {
            console.log('ℹ️ No Email templates available');
        }
    })
    .catch(error => {
        console.error('❌ Error loading Email templates:', error);
    });
}

// Apply SMS template
function applySmsTemplate() {
    const templateSelect = document.getElementById('smsTemplate');
    const messageField = document.getElementById('smsMessage');
    
    if (!templateSelect.value) {
        return;
    }

    try {
        const template = JSON.parse(templateSelect.value);
        
        // Replace variables in template
        let message = template.content;
        
        // Replace order-specific variables
        message = message.replace(/{order_number}/g, 'SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>');
        message = message.replace(/{client_name}/g, '<?= addslashes($order['client_name'] ?? 'N/A') ?>');
        message = message.replace(/{contact_name}/g, '<?= addslashes($order['salesperson_name'] ?? 'Contact') ?>');
        message = message.replace(/{vehicle}/g, '<?= addslashes($order['vehicle'] ?? 'N/A') ?>');
        message = message.replace(/{stock}/g, '<?= addslashes($order['stock'] ?? 'N/A') ?>');
        message = message.replace(/{service_name}/g, '<?= addslashes($order['service_name'] ?? 'N/A') ?>');
        message = message.replace(/{status}/g, '<?= 
            $order['status'] == 'in_progress' ? 'In Progress' : 
            ($order['status'] == 'processing' ? 'Processing' : 
            ($order['status'] == 'completed' ? 'Completed' : 
            ($order['status'] == 'cancelled' ? 'Cancelled' : 
            ($order['status'] == 'pending' ? 'Pending' : ucfirst($order['status'])))))
        ?>');
        
        // Replace date/time variables
        const orderDate = '<?= $order['date'] ? date('M j, Y', strtotime($order['date'])) : 'Not scheduled' ?>';
        const orderTime = '<?= $order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled' ?>';
        
        message = message.replace(/{scheduled_date}/g, orderDate);
        message = message.replace(/{scheduled_time}/g, orderTime);
        
        // Set the message
    messageField.value = message;
        
        // Update character count
    updateSmsCharCount();
        
        console.log('✅ SMS template applied:', template.name);
        
    } catch (error) {
        console.error('❌ Error applying SMS template:', error);
        showToast('error', 'Error applying template');
    }
}

// Apply Email template
function applyEmailTemplate() {
    const templateSelect = document.getElementById('emailTemplate');
    const subjectField = document.getElementById('emailSubject');
    const messageField = document.getElementById('emailMessage');
    
    if (!templateSelect.value) {
        return;
    }

    try {
        const template = JSON.parse(templateSelect.value);
                
                // Replace variables in subject
        let subject = template.subject;
        subject = subject.replace(/{order_number}/g, 'SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>');
        subject = subject.replace(/{client_name}/g, '<?= addslashes($order['client_name'] ?? 'N/A') ?>');
        subject = subject.replace(/{contact_name}/g, '<?= addslashes($order['salesperson_name'] ?? 'Contact') ?>');
        
        // Replace variables in message
        let message = template.content;
        message = message.replace(/{order_number}/g, 'SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>');
        message = message.replace(/{client_name}/g, '<?= addslashes($order['client_name'] ?? 'N/A') ?>');
        message = message.replace(/{contact_name}/g, '<?= addslashes($order['salesperson_name'] ?? 'Contact') ?>');
        message = message.replace(/{vehicle}/g, '<?= addslashes($order['vehicle'] ?? 'N/A') ?>');
        message = message.replace(/{stock}/g, '<?= addslashes($order['stock'] ?? 'N/A') ?>');
        message = message.replace(/{service_name}/g, '<?= addslashes($order['service_name'] ?? 'N/A') ?>');
        message = message.replace(/{status}/g, '<?= 
            $order['status'] == 'in_progress' ? 'In Progress' : 
            ($order['status'] == 'processing' ? 'Processing' : 
            ($order['status'] == 'completed' ? 'Completed' : 
            ($order['status'] == 'cancelled' ? 'Cancelled' : 
            ($order['status'] == 'pending' ? 'Pending' : ucfirst($order['status'])))))
        ?>');
        
        // Replace date/time variables
        const orderDate = '<?= $order['date'] ? date('M j, Y', strtotime($order['date'])) : 'Not scheduled' ?>';
        const orderTime = '<?= $order['time'] ? date('g:i A', strtotime($order['time'])) : 'Not scheduled' ?>';
        
        message = message.replace(/{scheduled_date}/g, orderDate);
        message = message.replace(/{scheduled_time}/g, orderTime);
        
        // Set the fields
                subjectField.value = subject;
        messageField.value = message;
        
        console.log('✅ Email template applied:', template.name);
        
    } catch (error) {
        console.error('❌ Error applying Email template:', error);
        showToast('error', 'Error applying template');
    }
}

// Update SMS character count
function updateSmsCharCount() {
    const messageField = document.getElementById('smsMessage');
    const charCountSpan = document.getElementById('smsCharCount');
    const charWarning = document.getElementById('smsCharWarning');
    const charStatus = document.getElementById('smsCharStatus');
    const lengthAlert = document.getElementById('smsLengthAlert');
    const submitBtn = document.querySelector('#smsForm button[type="submit"]');
    
    if (messageField && charCountSpan) {
        const message = messageField.value;
        const orderUrl = '<?= base_url("sales_orders/view/" . ($order["id"] ?? "")) ?>';
        const orderNumber = 'SAL-<?= str_pad($order["id"], 5, "0", STR_PAD_LEFT) ?>';
        
        // Estimate final length including URL (assume ~23 chars for short URL)
        const estimatedUrlLength = 23;
        const finalMessage = `${message}\n\n${orderNumber}: [short-url]`;
        const estimatedLength = message.length + orderNumber.length + estimatedUrlLength + 4; // +4 for formatting
        
        charCountSpan.textContent = estimatedLength;
        
        // Reset classes
        messageField.classList.remove('is-invalid', 'border-warning', 'border-success');
        
        // Update visual indicators based on character count
        if (estimatedLength > 160) {
            // Over limit - Red/Danger
            charCountSpan.style.color = '#dc3545';
            charCountSpan.parentElement.parentElement.classList.add('text-danger');
            charCountSpan.parentElement.parentElement.classList.remove('text-warning', 'text-success');
            
            // Show warning elements
            if (charWarning) {
                charWarning.style.display = 'inline';
                charWarning.className = 'ms-2 text-danger';
            }
            if (charStatus) {
                charStatus.className = 'badge bg-danger-subtle text-danger';
                charStatus.innerHTML = '<i data-feather="x" class="icon-xs me-1"></i>Too Long';
            }
            if (lengthAlert) {
                lengthAlert.style.display = 'block';
            }
            
            // Add visual feedback to textarea
            messageField.classList.add('is-invalid');
            
            // Disable submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.title = 'Message exceeds 160 character limit';
            }
            
        } else if (estimatedLength > 140) {
            // Warning zone - Yellow/Warning
            charCountSpan.style.color = '#ffc107';
            charCountSpan.parentElement.parentElement.classList.add('text-warning');
            charCountSpan.parentElement.parentElement.classList.remove('text-danger', 'text-success');
            
            // Show warning elements
            if (charWarning) {
                charWarning.style.display = 'inline';
                charWarning.className = 'ms-2 text-warning';
                charWarning.innerHTML = '<i data-feather="alert-triangle" class="icon-xs"></i><small>Getting close!</small>';
            }
            if (charStatus) {
                charStatus.className = 'badge bg-warning-subtle text-warning';
                charStatus.innerHTML = '<i data-feather="alert-triangle" class="icon-xs me-1"></i>Warning';
            }
            if (lengthAlert) {
                lengthAlert.style.display = 'none';
            }
            
            // Add visual feedback to textarea
            messageField.classList.add('border-warning');
            
            // Enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.title = '';
            }
            
    } else {
            // Good zone - Green/Success
            charCountSpan.style.color = '#28a745';
            charCountSpan.parentElement.parentElement.classList.add('text-success');
            charCountSpan.parentElement.parentElement.classList.remove('text-danger', 'text-warning');
            
            // Hide warning elements
            if (charWarning) {
                charWarning.style.display = 'none';
            }
            if (charStatus) {
                charStatus.className = 'badge bg-success-subtle text-success';
                charStatus.innerHTML = '<i data-feather="check" class="icon-xs me-1"></i>Good';
            }
            if (lengthAlert) {
                lengthAlert.style.display = 'none';
            }
            
            // Add visual feedback to textarea
            messageField.classList.add('border-success');
            
            // Enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.title = '';
            }
        }
        
        // Re-initialize feather icons for new elements
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
}

// Initialize SMS form
function initializeSmsForm() {
    const messageField = document.getElementById('smsMessage');
    if (messageField) {
        messageField.addEventListener('input', updateSmsCharCount);
        updateSmsCharCount(); // Initial count
    }
    
    // Load templates when modal opens
    loadSmsTemplates();
}

// Initialize Email form
function initializeEmailForm() {
    // Load templates when modal opens
    loadEmailTemplates();
    
    // Set default subject if empty
    const subjectField = document.getElementById('emailSubject');
    if (subjectField && !subjectField.value) {
        subjectField.value = 'Update on Order SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    }
}

// Send SMS function
async function sendSMSMessage() {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const phone = document.getElementById('smsPhone').value;
    const message = document.getElementById('smsMessage').value.trim();
    
    if (!message) {
        showToast('error', 'Please enter a message');
        return;
    }
    
    // Validate message length before sending
    const orderNumber = 'SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    const estimatedUrlLength = 23;
    const estimatedLength = message.length + orderNumber.length + estimatedUrlLength + 4;
    
    if (estimatedLength > 160) {
        showToast('error', 'Message exceeds 160 character limit. Please shorten your message.');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#smsForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-feather="loader" class="icon-sm me-1 spinning"></i>Shortening URL...';
    
    try {
        // Get the order URL and shorten it
        const orderUrl = '<?= base_url('sales_orders/view/' . ($order['id'] ?? '')) ?>';
        const shortUrl = await shortenUrl(orderUrl);
        
        // Create final message with shortened URL
        const orderNumber = 'SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
        const finalMessage = `${message}\n\n${orderNumber}: ${shortUrl}`;
        
        // Update button text
        submitBtn.innerHTML = '<i data-feather="loader" class="icon-sm me-1 spinning"></i>Sending...';
        
        // Send SMS
        const response = await fetch(`<?= base_url('sales_orders/sendSMS/') ?>${orderId}`, {
        method: 'POST',
        headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
            },
            body: `phone=${encodeURIComponent(phone)}&message=${encodeURIComponent(finalMessage)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('success', data.message || 'SMS sent successfully');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('smsModal'));
            if (modal) {
                modal.hide();
            }
            
            // Clear form
            document.getElementById('smsMessage').value = '';
            document.getElementById('smsTemplate').value = '';
            
            // Reload activities
            loadRecentActivity(true);
        } else {
            showToast('error', data.message || 'Error sending SMS');
        }
    } catch (error) {
        console.error('Error sending SMS:', error);
        showToast('error', 'Error sending SMS: ' + error.message);
    } finally {
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
}

// Send Email function
async function sendEmailMessage() {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const toEmail = document.getElementById('emailTo').value;
    const ccEmail = document.getElementById('emailCc').value;
    const subject = document.getElementById('emailSubject').value;
    const message = document.getElementById('emailMessage').value.trim();
    const includeDetails = document.getElementById('emailIncludeOrderDetails').checked;
    
    if (!toEmail || !subject || !message) {
        showToast('error', 'Please fill in all required fields');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#emailForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-feather="loader" class="icon-sm me-1 spinning"></i>Sending...';
    
    try {
        // Create final message with order URL
        const orderUrl = '<?= base_url('sales_orders/view/' . ($order['id'] ?? '')) ?>';
        const orderNumber = 'SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
        const finalMessage = `${message}\n\nView details: <a href="${orderUrl}" target="_blank">${orderNumber}</a>`;
        
        // Send Email
        const response = await fetch(`<?= base_url('sales_orders/sendEmail/') ?>${orderId}`, {
        method: 'POST',
        headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
            },
            body: `to_email=${encodeURIComponent(toEmail)}&cc_email=${encodeURIComponent(ccEmail)}&subject=${encodeURIComponent(subject)}&message=${encodeURIComponent(finalMessage)}&include_details=${includeDetails ? '1' : '0'}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
        });
        
        
        const data = await response.json();
        
        if (data.success) {
            showToast('success', data.message || 'Email sent successfully');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('emailModal'));
            if (modal) {
                modal.hide();
            }
            
            // Clear form
            document.getElementById('emailMessage').value = '';
            document.getElementById('emailTemplate').value = '';
            document.getElementById('emailCc').value = '';
            document.getElementById('emailIncludeOrderDetails').checked = false;
            
            // Reload activities
            loadRecentActivity(true);
        } else {
            showToast('error', data.message || 'Error sending email');
        }
    } catch (error) {
        console.error('Error sending email:', error);
        showToast('error', 'Error sending email: ' + error.message);
    } finally {
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
}

// Initialize modal event listeners for SMS and Email
function initializeModalEventListeners() {
    const orderModal = document.getElementById('orderModal');
    const smsModal = document.getElementById('smsModal');
    const emailModal = document.getElementById('emailModal');
    
    // Order modal events (existing)
    if (orderModal) {
        orderModal.addEventListener('shown.bs.modal', function() {
            // DISABLED: This function interferes with the dynamic modal system
            // setupNativeEventListeners();
            if (!window.salesOrderModal.isEditing) {
                setDefaultDateTime();
            }
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
        
        orderModal.addEventListener('hidden.bs.modal', function() {
            resetModalForm();
        });
    }
    
    // SMS modal events
    if (smsModal) {
        smsModal.addEventListener('shown.bs.modal', function() {
            initializeSmsForm();
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
        
        smsModal.addEventListener('hidden.bs.modal', function() {
            // Reset SMS form
            document.getElementById('smsMessage').value = '';
            document.getElementById('smsTemplate').value = '';
            updateSmsCharCount();
        });
    }
    
    // Email modal events
    if (emailModal) {
        emailModal.addEventListener('shown.bs.modal', function() {
            initializeEmailForm();
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
        });
        
        emailModal.addEventListener('hidden.bs.modal', function() {
            // Reset Email form
            document.getElementById('emailMessage').value = '';
            document.getElementById('emailTemplate').value = '';
            document.getElementById('emailCc').value = '';
            document.getElementById('emailIncludeOrderDetails').checked = false;
        });
    }
    
    // SMS form submission
    const smsForm = document.getElementById('smsForm');
    if (smsForm) {
        smsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendSMSMessage();
        });
    }
    
    // Email form submission
    const emailForm = document.getElementById('emailForm');
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendEmailMessage();
        });
    }
}

// ========================================
// SMS FUNCTIONS

// ========================================
// QR CODE FUNCTIONS
// ========================================

let currentQRData = null; // Global variable to store current QR data

// QR Cache system to avoid unnecessary regeneration
const qrCache = {
    data: new Map(),
    
    // Generate cache key
    getKey: function(orderId, size, format) {
        return `qr_${orderId}_${size}_${format}`;
    },
    
    // Get cached QR data
    get: function(orderId, size, format) {
        const key = this.getKey(orderId, size, format);
        const cached = this.data.get(key);
        
        if (cached) {
            // Check if cache is still valid (5 minutes)
            const now = Date.now();
            if (now - cached.timestamp < 5 * 60 * 1000) {
                console.log('✅ Using cached QR data:', key);
                return cached.data;
            } else {
                // Remove expired cache
                this.data.delete(key);
                console.log('🧹 Removed expired QR cache:', key);
            }
        }
        
        return null;
    },
    
    // Set cache data
    set: function(orderId, size, format, qrData) {
        const key = this.getKey(orderId, size, format);
        this.data.set(key, {
            data: qrData,
            timestamp: Date.now()
        });
        console.log('💾 Cached QR data:', key);
    },
    
    // Clear cache for specific order
    clearOrder: function(orderId) {
        const keysToDelete = [];
        for (let key of this.data.keys()) {
            if (key.startsWith(`qr_${orderId}_`)) {
                keysToDelete.push(key);
            }
        }
        keysToDelete.forEach(key => this.data.delete(key));
        console.log('🧹 Cleared QR cache for order:', orderId);
    }
};

// Generate QR Code function (Fixed for simplified modal)
function generateQRCode(orderId) {
    console.log('🎯 Generating QR Code for order:', orderId);
    
    // Just show the existing QR modal if we have QR data
    <?php if (isset($qr_data) && $qr_data): ?>
    console.log('📱 Opening existing QR Modal...');
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    qrModal.show();
    showToast('success', 'QR Code ready!');
    return;
    <?php endif; ?>
    
    // If no QR data available, show error message
    showToast('warning', 'QR Code not available - Lima Links API not configured');
    console.log('⚠️ No QR data available for order:', orderId);
}

// Display QR Code in modal (DISABLED - using simplified modal)
/*
function displayQRCode(qrData) {
    console.log('🖼️ Displaying QR Code:', qrData);
    
    // Hide loading state
    document.getElementById('qrLoadingState').style.display = 'none';
    
    // Update UI elements
    document.getElementById('qrImage').src = qrData.qr_url;
    document.getElementById('qrShortUrl').value = qrData.short_url;
    document.getElementById('qrOriginalUrl').value = qrData.order_url;
    
    // Show content
    document.getElementById('qrContent').style.display = 'block';
    document.getElementById('qrOpenBtn').style.display = 'inline-block';
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
    showToast('success', 'QR Code generated successfully!');
}
*/

// Show QR Error state (DISABLED - using simplified modal)
/*
function showQRError(message) {
    document.getElementById('qrLoadingState').style.display = 'none';
    document.getElementById('qrContent').style.display = 'none';
    document.getElementById('qrErrorState').style.display = 'block';
    document.getElementById('qrErrorMessage').textContent = message;
    
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}
*/

// Regenerate QR Code from sidebar button
function regenerateQRCode(orderId) {
    console.log('🔄 Regenerating QR Code for sales order:', orderId);
    
    // Show confirmation dialog
    Swal.fire({
        title: 'Regenerate QR Code?',
        text: 'This will create a new Lima Links short URL for this order.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, regenerate!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Regenerating QR Code...',
                text: 'Creating new Lima Links short URL',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Call regenerate endpoint
            fetch(`<?= base_url('sales_orders/regenerateQR') ?>/${orderId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'QR Code Regenerated!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the page to show the new QR code
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Regeneration Failed',
                        text: data.message || 'Failed to regenerate QR code'
                    });
                }
            })
            .catch(error => {
                console.error('Error regenerating QR code:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while regenerating the QR code'
                });
            });
        }
    });
}

// Show QR Modal from sidebar
function showQRModal() {
    console.log('📱 Opening QR Modal from sidebar...');
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    qrModal.show();
    showToast('success', 'QR Code ready!');
}

// Regenerate QR with new settings
function regenerateQR() {
    const orderId = <?= $order['id'] ?? 0 ?>;
    console.log('🔄 Regenerating QR Code for sales order:', orderId);
    
    // Show confirmation dialog
    Swal.fire({
        title: 'Regenerate QR Code?',
        text: 'This will create a new Lima Links short URL for this order.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, regenerate!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Regenerating QR Code...',
                text: 'Creating new Lima Links short URL',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
    // Clear cache for this order to force regeneration
            if (typeof qrCache !== 'undefined') {
    qrCache.clearOrder(orderId);
            }
            
            // Call regenerate endpoint
            fetch(`<?= base_url('sales_orders/regenerateQR') ?>/${orderId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'QR Code Regenerated!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the page to show the new QR code
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Regeneration Failed',
                        text: data.message || 'Failed to regenerate QR code'
                    });
                }
            })
            .catch(error => {
                console.error('Error regenerating QR code:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while regenerating the QR code'
                });
            });
        }
    });
}

// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('❌ Element not found:', elementId);
        return;
    }
    
    // Create a temporary textarea to copy the text
    const textarea = document.createElement('textarea');
    textarea.value = element.value;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Copied to clipboard!');
        
        // Visual feedback on button
        const button = element.nextElementSibling;
        if (button) {
            const originalIcon = button.innerHTML;
            button.innerHTML = '<i data-feather="check" class="icon-xs"></i>';
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
    setTimeout(() => {
                button.innerHTML = originalIcon;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
    }, 2000);
}
    } catch (err) {
        console.error('❌ Copy failed:', err);
        showToast('error', 'Failed to copy to clipboard');
    } finally {
        document.body.removeChild(textarea);
    }
}

// Copy Short URL
function copyShortUrl() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const shortUrl = '<?= $qr_data['short_url'] ?>';
    
    // Use modern clipboard API if available
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(shortUrl).then(() => {
            showToast('success', '<?= lang('App.url_copied') ?>');
        }).catch(err => {
            console.error('❌ Clipboard API failed:', err);
            fallbackCopyUrl(shortUrl);
        });
    } else {
        fallbackCopyUrl(shortUrl);
    }
    <?php else: ?>
    showToast('error', '<?= lang('App.no_url_to_copy') ?>');
    <?php endif; ?>
}

function fallbackCopyUrl(url) {
    // Fallback for older browsers
    const textarea = document.createElement('textarea');
    textarea.value = url;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', '<?= lang('App.url_copied') ?>');
    } catch (err) {
        console.error('❌ Copy failed:', err);
        showToast('error', '<?= lang('App.copy_failed') ?>');
    } finally {
        document.body.removeChild(textarea);
    }
}

// Download QR Code
function downloadQR() {
    if (!currentQRData || !currentQRData.qr_url) {
        showToast('error', 'No QR code to download');
        return;
    }
    
    console.log('💾 Downloading QR Code...');
    
    const size = document.getElementById('qrSize').value;
    const format = document.getElementById('qrFormat').value;
    const orderId = <?= $order['id'] ?? 0 ?>;
    
    // Create download link
    const link = document.createElement('a');
    link.href = currentQRData.qr_url;
    link.download = `order-SAL-${String(orderId).padStart(5, '0')}-qr.${format}`;
    
    // Trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('success', 'QR Code downloaded!');
}

// Share QR Code
function shareQR() {
    if (!currentQRData) {
        showToast('error', 'No QR code to share');
        return;
    }
    
    const orderId = <?= $order['id'] ?? 0 ?>;
    const shareData = {
        title: `Sales Order SAL-${String(orderId).padStart(5, '0')} - QR Code`,
        text: `Quick access to Sales Order SAL-${String(orderId).padStart(5, '0')}`,
        url: currentQRData.short_url
    };
    
    // Check if Web Share API is available
    if (navigator.share) {
        navigator.share(shareData)
            .then(() => {
                console.log('✅ QR shared successfully');
                showToast('success', 'QR Code shared!');
            })
            .catch((error) => {
                console.error('❌ Share failed:', error);
                fallbackShare();
            });
    } else {
        fallbackShare();
    }
}

// Fallback share function
function fallbackShare() {
    if (!currentQRData) return;
    
    const shareText = `Sales Order SAL-${String(<?= $order['id'] ?? 0 ?>).padStart(5, '0')} - Quick Access: ${currentQRData.short_url}`;
    
    // Copy to clipboard as fallback
    const textarea = document.createElement('textarea');
    textarea.value = shareText;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Share link copied to clipboard!');
    } catch (err) {
        console.error('❌ Fallback share failed:', err);
        showToast('error', 'Unable to share');
    } finally {
        document.body.removeChild(textarea);
    }
}

// Open QR link in new tab
function openQRInNewTab() {
    if (!currentQRData || !currentQRData.short_url) {
        showToast('error', 'No QR link available');
        return;
    }
    
    window.open(currentQRData.short_url, '_blank');
    showToast('info', 'QR link opened in new tab');
}

// Add QR button to SMS and Email messages
function addQRToSMS() {
    if (!currentQRData) return '';
    
    const orderId = <?= $order['id'] ?? 0 ?>;
    const orderNumber = `SAL-${String(orderId).padStart(5, '0')}`;
    return `\n\n📱 QR Code: ${currentQRData.short_url}`;
}

function addQRToEmail() {
    if (!currentQRData) return '';
    
    const orderId = <?= $order['id'] ?? 0 ?>;
    const orderNumber = `SAL-${String(orderId).padStart(5, '0')}`;
    return `\n\n<p><strong>📱 Quick Access QR Code:</strong><br>
    <a href="${currentQRData.short_url}" target="_blank">${orderNumber} - Mobile Access</a></p>`;
}

// ========================================
// END QR CODE FUNCTIONS
// ========================================

// ========================================
// AUTO-GENERATED QR FUNCTIONS
// ========================================

// Download Auto QR Code
function downloadAutoQR() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const qrUrl = '<?= $qr_data['qr_url'] ?>';
    const orderId = <?= $order['id'] ?? 0 ?>;
    
    console.log('💾 Downloading Auto QR Code...');
    
    // Create download link
    const link = document.createElement('a');
    link.href = qrUrl;
    link.download = `order-SAL-${String(orderId).padStart(5, '0')}-auto-qr.png`;
    
    // Trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('success', 'QR Code downloaded!');
    <?php else: ?>
    showToast('error', 'No QR code available to download');
    <?php endif; ?>
}

// Share Auto QR Code
function shareAutoQR() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const orderId = <?= $order['id'] ?? 0 ?>;
    const shortUrl = '<?= $qr_data['short_url'] ?>';
    
    const shareData = {
        title: `Sales Order SAL-${String(orderId).padStart(5, '0')} - Quick Access`,
        text: `View Sales Order SAL-${String(orderId).padStart(5, '0')} details instantly`,
        url: shortUrl
    };
    
    // Check if Web Share API is available
    if (navigator.share) {
        navigator.share(shareData)
            .then(() => {
                console.log('✅ Auto QR shared successfully');
                showToast('success', 'QR Code shared!');
            })
            .catch((error) => {
                console.error('❌ Share failed:', error);
                fallbackShareAutoQR();
            });
    } else {
        fallbackShareAutoQR();
    }
    <?php else: ?>
    showToast('error', 'No QR code available to share');
    <?php endif; ?>
}

// Fallback share for Auto QR
function fallbackShareAutoQR() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const orderId = <?= $order['id'] ?? 0 ?>;
    const shortUrl = '<?= $qr_data['short_url'] ?>';
    
    const shareText = `Sales Order SAL-${String(orderId).padStart(5, '0')} - Quick Access: ${shortUrl}`;
    
    // Copy to clipboard as fallback
    const textarea = document.createElement('textarea');
    textarea.value = shareText;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Share link copied to clipboard!');
    } catch (err) {
        console.error('❌ Fallback share failed:', err);
        showToast('error', 'Unable to share');
    } finally {
        document.body.removeChild(textarea);
    }
    <?php endif; ?>
}

// Enhanced copy function with visual feedback
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('❌ Element not found:', elementId);
        return;
    }
    
    // Create a temporary textarea to copy the text
    const textarea = document.createElement('textarea');
    textarea.value = element.value;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Copied to clipboard!');
        
        // Visual feedback on the element and button
        const button = element.nextElementSibling;
        if (button) {
            const originalIcon = button.innerHTML;
            button.innerHTML = '<i data-feather="check" class="icon-xs text-success"></i>';
            button.classList.add('copy-success');
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
    setTimeout(() => {
                button.innerHTML = originalIcon;
                button.classList.remove('copy-success');
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 2000);
        }
        
        // Add success animation to the input
        element.classList.add('copy-success');
        setTimeout(() => {
            element.classList.remove('copy-success');
        }, 600);
        
    } catch (err) {
        console.error('❌ Copy failed:', err);
        showToast('error', 'Failed to copy to clipboard');
    } finally {
        document.body.removeChild(textarea);
    }
}

// This will be consolidated into the main DOMContentLoaded listener

// ========================================
// END AUTO-GENERATED QR FUNCTIONS
// ========================================

// ========================================
// SIMPLE QR FUNCTIONS
// ========================================

// Show QR Modal (called from topbar QR click)
function showQRModal() {
    <?php if (isset($qr_data) && $qr_data): ?>
    console.log('📱 Opening QR Modal...');
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    qrModal.show();
    <?php else: ?>
    showToast('warning', 'QR Code not available');
    <?php endif; ?>
}

// Download QR Code (simple version)
function downloadQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const qrUrl = '<?= $qr_data['qr_url'] ?>';
    const orderId = <?= $order['id'] ?? 0 ?>;
    
    console.log('💾 Downloading QR Code...');
    
    // Create download link
    const link = document.createElement('a');
    link.href = qrUrl;
    link.download = `order-SAL-${String(orderId).padStart(5, '0')}-qr.png`;
    
    // Trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('success', 'QR Code downloaded!');
    <?php else: ?>
    showToast('error', 'No QR code available to download');
    <?php endif; ?>
}

// Share QR Code (simple version)
function shareQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const orderId = <?= $order['id'] ?? 0 ?>;
    const shortUrl = '<?= $qr_data['short_url'] ?>';
    
    const shareData = {
        title: `Sales Order SAL-${String(orderId).padStart(5, '0')}`,
        text: `View Sales Order SAL-${String(orderId).padStart(5, '0')} details`,
        url: shortUrl
    };
    
    // Check if Web Share API is available
    if (navigator.share) {
        navigator.share(shareData)
            .then(() => {
                console.log('✅ QR shared successfully');
                showToast('success', 'QR Code shared!');
            })
            .catch((error) => {
                console.error('❌ Share failed:', error);
                fallbackShareQR();
            });
    } else {
        fallbackShareQR();
    }
    <?php else: ?>
    showToast('error', 'No QR code available to share');
    <?php endif; ?>
}

// Fallback share for QR
function fallbackShareQR() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const orderId = <?= $order['id'] ?? 0 ?>;
    const shortUrl = '<?= $qr_data['short_url'] ?>';
    
    const shareText = `Sales Order SAL-${String(orderId).padStart(5, '0')}: ${shortUrl}`;
    
    // Copy to clipboard as fallback
    const textarea = document.createElement('textarea');
    textarea.value = shareText;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Share link copied to clipboard!');
    } catch (err) {
        console.error('❌ Fallback share failed:', err);
        showToast('error', 'Unable to share');
    } finally {
        document.body.removeChild(textarea);
    }
    <?php endif; ?>
}

// Enhanced copy function with visual feedback
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('❌ Element not found:', elementId);
        return;
    }
    
    // Create a temporary textarea to copy the text
    const textarea = document.createElement('textarea');
    textarea.value = element.value;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Copied to clipboard!');
        
        // Visual feedback on the button
        const button = element.nextElementSibling;
        if (button) {
            const originalIcon = button.innerHTML;
            button.innerHTML = '<i data-feather="check" class="icon-xs text-success"></i>';
            
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
            setTimeout(() => {
                button.innerHTML = originalIcon;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
    }, 2000);
}
        
    } catch (err) {
        console.error('❌ Copy failed:', err);
        showToast('error', 'Failed to copy to clipboard');
    } finally {
        document.body.removeChild(textarea);
    }
}

// ========================================
// END SIMPLE QR FUNCTIONS
// ========================================

// ========================================
// MOBILE QUICK ACTIONS BUBBLE FUNCTIONS
// ========================================

// Toggle Quick Actions bubble on mobile
function toggleQuickActions() {
    const quickActionsCard = document.querySelector('.quick-actions-card');
    const toggleIcon = document.querySelector('.quick-actions-toggle i');
    
    if (quickActionsCard) {
        quickActionsCard.classList.toggle('collapsed');
        
        // Update icon rotation (handled by CSS, but we can add extra feedback)
        if (quickActionsCard.classList.contains('collapsed')) {
            console.log('📱 Quick Actions bubble collapsed');
        } else {
            console.log('📱 Quick Actions bubble expanded');
        }
        
        // Re-initialize feather icons to ensure proper rendering
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
}

// This will be consolidated into the main DOMContentLoaded listener
function initializeMobileQuickActions() {
    // Check if we're on mobile
    if (window.innerWidth <= 767.98) {
        const quickActionsCard = document.querySelector('.quick-actions-card');
        if (quickActionsCard) {
            quickActionsCard.classList.add('collapsed');
            console.log('📱 Quick Actions auto-collapsed on mobile');
        }
    }
    
    // Handle window resize to manage bubble behavior
    window.addEventListener('resize', function() {
        const quickActionsCard = document.querySelector('.quick-actions-card');
        if (quickActionsCard) {
            if (window.innerWidth > 767.98) {
                // Remove mobile-specific classes on larger screens
                quickActionsCard.classList.remove('collapsed');
            } else {
                // Ensure collapsed state on mobile
                quickActionsCard.classList.add('collapsed');
            }
        }
    });
}

// ========================================
// END MOBILE QUICK ACTIONS BUBBLE FUNCTIONS
// ========================================

// ========================================
// MOBILE QUICK ACTIONS BUTTON FUNCTIONS
// ========================================

// Open Quick Actions Modal
function openQuickActionsModal() {
    const quickActionsModal = new bootstrap.Modal(document.getElementById('quickActionsModal'));
    quickActionsModal.show();
    console.log('📱 Quick Actions modal opened');
}

// Close Quick Actions Modal
function closeQuickActionsModal() {
    const quickActionsModal = bootstrap.Modal.getInstance(document.getElementById('quickActionsModal'));
    if (quickActionsModal) {
        quickActionsModal.hide();
    }
    console.log('📱 Quick Actions modal closed');
}

// Update Status from Quick Actions Modal
function updateStatusFromModal() {
    const statusSelect = document.getElementById('statusSelectMobile');
    const newStatus = statusSelect.value;
    const orderId = <?= $order['id'] ?? 0 ?>;

    console.log('🔄 Updating status:', { orderId, newStatus, currentStatus: orderData.status });

    if (!newStatus) {
        showToast('error', 'Please select a status');
        return;
    }

    if (newStatus === orderData.status) {
        showToast('info', 'Status is already set to ' + newStatus);
        return;
    }

    // Show loading state
    statusSelect.disabled = true;
    const originalSelectHtml = statusSelect.innerHTML;

    // Add loading indicator to the select
    const loadingOption = document.createElement('option');
    loadingOption.value = '';
    loadingOption.textContent = '🔄 Updating...';
    loadingOption.selected = true;
    statusSelect.appendChild(loadingOption);

    console.log('📤 Sending status update request...');

    fetch(`<?= base_url('sales_orders/updateStatus/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `status=${encodeURIComponent(newStatus)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => {
        console.log('📥 Response received:', response.status, response.statusText);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('✅ Status update response:', data);
        
        if (data.success) {
            showToast('success', data.message || `Status updated to ${newStatus}`);
            
            // Update global order data
            orderData.status = newStatus;
            
            // Update the top bar status widget
            updateTopBarStatus(newStatus);
            
            // Update all status badges in the page
            updateAllStatusBadges(newStatus);
            
            // Update the select to show the new status
            statusSelect.value = newStatus;
            
            // Reload activities to show status change
            console.log('🔄 Reloading activities...');
            loadRecentActivity(true);
            
            console.log('✅ Status update completed successfully');
        } else {
            console.error('❌ Status update failed:', data);
            showToast('error', data.message || 'Error updating status');
            
            // Reset select to previous value
            statusSelect.value = orderData.status;
        }
    })
    .catch(error => {
        console.error('❌ Status update error:', error);
        showToast('error', 'Error updating status: ' + error.message);
        
        // Reset select to previous value
        statusSelect.value = orderData.status;
    })
    .finally(() => {
        // Restore select state
        statusSelect.disabled = false;
        
        // Remove loading option and restore original options
        statusSelect.innerHTML = originalSelectHtml;
        statusSelect.value = orderData.status; // Set to current status
        
        console.log('🔄 Status update process completed');
    });
}

// Send Notification from Quick Actions Modal
function sendNotificationFromModal(orderId) {
    const advisorName = '<?= $order['salesperson_name'] ?? 'Advisor' ?>';
    const orderNumber = 'ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    
    Swal.fire({
        title: 'Send Alert Notification',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">To: ${advisorName}</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alert Type:</label>
                    <select id="alertTypeModal" class="form-select">
                        <option value="urgent">🔴 Urgent - Immediate Attention Required</option>
                        <option value="priority">🟡 Priority - Review Soon</option>
                        <option value="info">🔵 Information - General Update</option>
                        <option value="reminder">⏰ Reminder - Follow Up Needed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message:</label>
                    <textarea id="alertMessageModal" class="form-control" rows="3" placeholder="Enter alert message...">${orderNumber} requires your attention.</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Send Alert',
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const alertType = document.getElementById('alertTypeModal').value;
            const message = document.getElementById('alertMessageModal').value.trim();
            if (!message) {
                Swal.showValidationMessage('Please enter an alert message');
                return false;
            }
            return { alertType, message };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const alertData = result.value;
            
            // Show loading
            Swal.fire({
                title: 'Sending Alert...',
                text: 'Please wait while we send the notification.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

    fetch(`<?= base_url('sales_orders/sendAlert/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
                body: `alert_type=${encodeURIComponent(alertData.alertType)}&message=${encodeURIComponent(alertData.message)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Alert Sent!',
                        text: data.message || `${alertData.alertType.toUpperCase()} alert sent successfully`,
                        timer: 2000,
                        showConfirmButton: false
                    });
            // Reload activities to show the notification activity
            loadRecentActivity(true);
        } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Send Failed',
                        text: data.message || 'Error sending alert'
                    });
        }
    })
    .catch(error => {
        console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while sending the alert'
                });
            });
        }
    });
}

// Generate QR Code from Quick Actions Modal
function generateQRCodeFromModal(orderId) {
    console.log('🎯 Generating QR Code for order:', orderId);
    
    // Just show the existing QR modal if we have QR data
    <?php if (isset($qr_data) && $qr_data): ?>
    console.log('📱 Opening existing QR Modal...');
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    qrModal.show();
    showToast('success', 'QR Code ready!');
    return;
    <?php endif; ?>
    
    // If no QR data available, show error message
    showToast('warning', 'QR Code not available - Lima Links API not configured');
    console.log('⚠️ No QR data available for order:', orderId);
}

// Internal Notes System
class InternalNotesSystem {
    constructor(orderId) {
        // Prevent multiple instances
        if (window.internalNotesInstance) {
            console.warn('⚠️ Destroying existing Internal Notes instance');
            window.internalNotesInstance.destroy();
        }
        
        // Check if already initialized for this order
        if (window.internalNotesInitialized === orderId) {
            console.warn('⚠️ Internal Notes already initialized for order:', orderId);
            return window.internalNotesInstance;
        }
        
        console.log('🔧 Creating new Internal Notes instance for order:', orderId);
        this.orderId = orderId;
        this.currentUser = null;
        this.staffUsers = [];
        this.mentionSuggestions = [];
        this.selectedSuggestionIndex = -1;
        this.notesData = [];
        this.mentionsData = [];
        this.submittingNote = false; // Prevent multiple submissions
        this.loadingNotes = false; // Prevent simultaneous note loading
        this.submitCounter = 0; // Track submit attempts
        this.isDestroyed = false; // Track if instance is destroyed
        
        // Pagination variables for infinite scroll
        this.currentPage = 1;
        this.hasMore = true;
        this.totalNotes = 0;
        this.loadedNotes = [];
        
        // Register this instance globally
        window.internalNotesInstance = this;
        window.internalNotesInitialized = orderId;
        
        console.log('✅ Internal Notes System initialized for order:', orderId);
        this.init();
    }
    
    async init() {
        await this.loadCurrentUser();
        await this.loadStaffUsers();
        this.bindEvents();
        this.loadNotes(); // Loads first 5 notes, additional notes loaded via infinite scroll
        this.loadMentions();
        this.loadTeamActivity();
    }
    
    async loadCurrentUser() {
        // This should come from your authentication system
        // For now, we'll use a placeholder
        this.currentUser = {
            id: <?= auth()->user()->id ?? 0 ?>,
            username: '<?= auth()->user()->username ?? 'user' ?>',
            name: '<?= auth()->user()->firstname ?? 'User' ?> <?= auth()->user()->lastname ?? '' ?>'
        };
    }
    
    async loadStaffUsers() {
        try {
            const response = await fetch(`${window.baseUrl}/internal-notes/staff-users`);
            const result = await response.json();
            
            if (result.success) {
                this.staffUsers = result.data;
                this.populateAuthorFilter();
            }
        } catch (error) {
            console.error('Error loading staff users:', error);
        }
    }
    
    populateAuthorFilter() {
        const authorFilter = document.getElementById('notesAuthorFilter');
        if (!authorFilter) return;
        
        // Clear existing options except "All Authors"
        authorFilter.innerHTML = '<option value="">All Authors</option>';
        
        this.staffUsers.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = user.name;
            authorFilter.appendChild(option);
        });
    }
    
    bindEvents() {
        // Note form submission
        const noteForm = document.getElementById('noteForm');
        if (noteForm) {
            console.log('bindEvents: Note form found, adding submit listener');
            noteForm.addEventListener('submit', (e) => this.handleNoteSubmit(e));
        } else {
            console.error('bindEvents: Note form not found!');
        }
        
        // Mention functionality
        const noteContent = document.getElementById('noteContent');
        if (noteContent) {
            noteContent.addEventListener('input', (e) => {
                this.handleMentionTyping(e);
                this.updateCharacterCount(e.target.value);
            });
            noteContent.addEventListener('keydown', (e) => this.handleMentionNavigation(e));
            
            // Initialize character count
            this.updateCharacterCount(noteContent.value);
        }
        
        // Filter events
        const notesSearch = document.getElementById('notesSearch');
        const notesAuthorFilter = document.getElementById('notesAuthorFilter');
        const notesDateFilter = document.getElementById('notesDateFilter');
        
        if (notesSearch) {
            notesSearch.addEventListener('input', () => this.filterNotes());
        }
        if (notesAuthorFilter) {
            notesAuthorFilter.addEventListener('change', () => this.filterNotes());
        }
        if (notesDateFilter) {
            notesDateFilter.addEventListener('change', () => this.filterNotes());
        }
        
        // File attachment count
        const noteAttachments = document.getElementById('noteAttachments');
        if (noteAttachments) {
            noteAttachments.addEventListener('change', (e) => this.updateAttachmentCount(e));
        }
        
        // Tab switching
        const tabButtons = document.querySelectorAll('#internalTabsNav button[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', (e) => this.handleTabSwitch(e));
        });
        
        // Setup infinite scroll for notes
        this.setupNotesInfiniteScroll();
    }
    
    async handleNoteSubmit(e) {
        e.preventDefault();
        this.submitCounter++;
        
        console.log('handleNoteSubmit: Form submitted, counter:', this.submitCounter);
        console.log('handleNoteSubmit: Event target:', e.target);
        
        // Prevent multiple rapid submissions
        if (this.submittingNote) {
            console.log('handleNoteSubmit: Already submitting, skipping');
            return;
        }
        
        const noteContentElement = document.getElementById('noteContent');
        const noteAttachmentsElement = document.getElementById('noteAttachments');
        
        console.log('handleNoteSubmit: Form elements found:', {
            noteContentElement: !!noteContentElement,
            noteAttachmentsElement: !!noteAttachmentsElement
        });
        
        if (!noteContentElement) {
            console.error('handleNoteSubmit: noteContent element not found!');
            this.showAlert('Error: Note content field not found', 'error');
            return;
        }
        
        const content = noteContentElement.value.trim();
        const attachments = noteAttachmentsElement ? noteAttachmentsElement.files : [];
        
        console.log('handleNoteSubmit: Content length:', content.length);
        
        if (!content) {
            this.showAlert(window.internalNotesTranslations.noteContentRequired, 'warning');
            return;
        }
        
        if (content.length < 3) {
            this.showAlert('Note content must be at least 3 characters long', 'warning');
            return;
        }
        
        if (content.length > 5000) {
            this.showAlert('Note content cannot exceed 5000 characters', 'warning');
            return;
        }
        
        const formData = new FormData();
        formData.append('order_id', this.orderId);
        formData.append('content', content);
        
        // Add attachments
        for (let i = 0; i < attachments.length; i++) {
            formData.append('attachments[]', attachments[i]);
        }
        
            const submitButton = e.target.querySelector('button[type="submit"]');
        const originalText = submitButton ? submitButton.innerHTML : '';
        
        // Set submitting flag
        this.submittingNote = true;
        
        try {
            if (submitButton) {
                submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i> ' + window.internalNotesTranslations.addingNote;
            submitButton.disabled = true;
            }
            
            const createUrl = `${window.baseUrl}/internal-notes/create`;
            console.log('handleNoteSubmit: Making request to:', createUrl);
            
            const response = await fetch(createUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            console.log('handleNoteSubmit: Response received:', {
                status: response.status,
                statusText: response.statusText,
                ok: response.ok
            });
            
            const result = await response.json();
            console.log('handleNoteSubmit: Response data:', result);
            
            if (result.success) {
                // Show detailed success message
                const notePreview = result.data && result.data.note ? 
                    result.data.note.substring(0, 50) + (result.data.note.length > 50 ? '...' : '') : 
                    '';
                const successMessage = `${window.internalNotesTranslations.noteAddedSuccessfully}${notePreview ? ': "' + notePreview + '"' : ''}`;
                this.showAlert(successMessage, 'success');
                this.clearNoteForm();
                
                // Increment total notes count immediately for better UX
                this.totalNotes = (this.totalNotes || 0) + 1;
                this.updateNotesCount(this.totalNotes);
                
                // Add a small delay before reloading to prevent race conditions
                setTimeout(() => {
                this.loadNotes();
                }, 100);
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Send notifications for mentions
                if (result.data && result.data.mentions && result.data.mentions.length > 0) {
                    this.showAlert(window.internalNotesTranslations.mentionTeamMembers.replace('{0}', result.data.mentions.length), 'info');
                }
            } else {
                console.error('handleNoteSubmit: Server returned error:', result);
                let errorMessage = result.message || window.internalNotesTranslations.failedToAddNote;
                if (result.errors) {
                    errorMessage += ' - ' + JSON.stringify(result.errors);
                }
                throw new Error(errorMessage);
            }
        } catch (error) {
            console.error('Error adding note:', error);
            console.error('Error details:', {
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            this.showAlert(window.internalNotesTranslations.errorAddingNote + ': ' + error.message, 'error');
        } finally {
            // Reset submitting flag
            this.submittingNote = false;
            
            if (submitButton) {
                submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            }
        }
    }
    
    handleMentionTyping(e) {
        console.log('Mention typing detected in internal notes');
        const textarea = e.target;
        const value = textarea.value;
        const cursorPos = textarea.selectionStart;
        
        // Find if we're typing after an @ symbol
        const beforeCursor = value.substring(0, cursorPos);
        const match = beforeCursor.match(/@(\w*)$/);
        
        console.log('Mention match:', match);
        
        if (match) {
            const query = match[1];
            console.log('Showing mention suggestions for query:', query);
            this.showMentionSuggestions(query, cursorPos - match[0].length);
        } else {
            this.hideMentionSuggestions();
        }
    }
    
    showMentionSuggestions(query, position) {
        console.log('showMentionSuggestions called with query:', query, 'staffUsers:', this.staffUsers.length);
        const filtered = this.staffUsers.filter(user => 
            user.username.toLowerCase().includes(query.toLowerCase()) ||
            user.name.toLowerCase().includes(query.toLowerCase())
        );
        
        console.log('Filtered users:', filtered.length);
        
        if (filtered.length === 0) {
            this.hideMentionSuggestions();
            return;
        }
        
        const dropdown = document.getElementById('noteMentionSuggestions');
        console.log('Dropdown element found:', !!dropdown);
        if (!dropdown) return;
        
        dropdown.innerHTML = '';
        filtered.forEach((user, index) => {
            const suggestion = document.createElement('div');
            suggestion.className = 'mention-suggestion';
            if (index === 0) suggestion.classList.add('active');
            
            suggestion.innerHTML = `
                <div class="mention-suggestion-user">
                    <div class="mention-suggestion-avatar">
                        ${user.name.substring(0, 1).toUpperCase()}
                    </div>
                    <div class="mention-suggestion-info">
                        <div class="mention-suggestion-name">${user.name}</div>
                        <div class="mention-suggestion-username">@${user.username}</div>
                    </div>
                </div>
            `;
            
            suggestion.addEventListener('click', () => {
                this.insertMention(user.username, position);
            });
            
            dropdown.appendChild(suggestion);
        });
        
        this.mentionSuggestions = filtered;
        this.selectedSuggestionIndex = 0;
        dropdown.style.display = 'block';
    }
    
    hideMentionSuggestions() {
        const dropdown = document.getElementById('noteMentionSuggestions');
        if (dropdown) {
            dropdown.style.display = 'none';
        }
        this.selectedSuggestionIndex = -1;
    }
    
    handleMentionNavigation(e) {
        const dropdown = document.getElementById('noteMentionSuggestions');
        if (!dropdown || dropdown.style.display === 'none') return;
        
        const suggestions = dropdown.querySelectorAll('.mention-suggestion');
        
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectedSuggestionIndex = Math.min(this.selectedSuggestionIndex + 1, suggestions.length - 1);
                this.updateSuggestionSelection(suggestions);
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                this.selectedSuggestionIndex = Math.max(this.selectedSuggestionIndex - 1, 0);
                this.updateSuggestionSelection(suggestions);
                break;
                
            case 'Enter':
                e.preventDefault();
                if (this.selectedSuggestionIndex >= 0 && this.mentionSuggestions[this.selectedSuggestionIndex]) {
                    const user = this.mentionSuggestions[this.selectedSuggestionIndex];
                    this.insertMention(user.username);
                }
                break;
                
            case 'Escape':
                this.hideMentionSuggestions();
                break;
        }
    }
    
    updateSuggestionSelection(suggestions) {
        suggestions.forEach((suggestion, index) => {
            suggestion.classList.toggle('active', index === this.selectedSuggestionIndex);
        });
    }
    
    insertMention(username, position = null) {
        const textarea = document.getElementById('noteContent');
        if (!textarea) return;
        
        const value = textarea.value;
        const cursorPos = position !== null ? position : textarea.selectionStart;
        
        // Find the @ symbol before the cursor
        const beforeCursor = value.substring(0, cursorPos);
        const afterCursor = value.substring(textarea.selectionStart);
        const atIndex = beforeCursor.lastIndexOf('@');
        
        if (atIndex !== -1) {
            const newValue = 
                value.substring(0, atIndex) + 
                `@${username} ` + 
                afterCursor;
            
            textarea.value = newValue;
            textarea.focus();
            textarea.setSelectionRange(atIndex + username.length + 2, atIndex + username.length + 2);
        }
        
        this.hideMentionSuggestions();
    }
    
    updateAttachmentCount(e) {
        const count = e.target.files.length;
        const countSpan = document.getElementById('attachmentCount');
        if (countSpan) {
            countSpan.textContent = count > 0 ? `${count}${window.internalNotesTranslations.filesSelected}` : '';
        }
    }
    
    clearNoteForm() {
        const noteContent = document.getElementById('noteContent');
        const noteAttachments = document.getElementById('noteAttachments');
        const attachmentCount = document.getElementById('attachmentCount');
        
        if (noteContent) {
            noteContent.value = '';
            this.updateCharacterCount('');
        }
        if (noteAttachments) noteAttachments.value = '';
        if (attachmentCount) attachmentCount.textContent = '';
    }
    
    updateCharacterCount(text) {
        const charCount = document.getElementById('charCount');
        if (charCount) {
            const length = text.length;
            charCount.textContent = length;
            
            // Change color based on character count
            if (length < 3) {
                charCount.className = 'text-warning';
            } else if (length > 4500) {
                charCount.className = 'text-danger';
            } else {
                charCount.className = 'text-muted';
            }
        }
    }
    
    async loadNotes(reset = true) {
        // Check if instance is destroyed
        if (this.isDestroyed) {
            console.log('Notes: Instance destroyed, skipping load');
            return;
        }
        
        // Prevent simultaneous note loading
        if (this.loadingNotes) {
            console.log('Notes: Already loading, skipping');
            return;
        }
        
        // Check if orderId is valid
        if (!this.orderId || this.orderId <= 0) {
            console.error('Invalid order ID:', this.orderId);
            this.displayNotesError();
            return;
        }
        
        this.loadingNotes = true;
        console.log('Notes: Starting load, reset:', reset, 'page:', this.currentPage);
        
        try {
            // Reset pagination if this is a fresh load
            if (reset) {
                this.currentPage = 1;
                this.hasMore = true;
                this.loadedNotes = [];
                console.log('Notes: Reset pagination');
            }
            
            const searchQuery = document.getElementById('notesSearch')?.value || '';
            const authorFilter = document.getElementById('notesAuthorFilter')?.value || '';
            const dateFilter = document.getElementById('notesDateFilter')?.value || '';
            
            const params = new URLSearchParams({
                search: searchQuery,
                author_id: authorFilter,
                date_filter: dateFilter,
                page: this.currentPage,
                limit: 5 // Load 5 notes per page for infinite scroll
            });
            
            // Remove empty parameters
            for (let [key, value] of params.entries()) {
                if (!value) {
                    params.delete(key);
                }
            }
            
            const url = `${window.baseUrl}/internal-notes/order/${this.orderId}?${params}`;
            console.log('Notes: Fetching from URL:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('HTTP Error:', response.status, errorText);
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }
            
            const result = await response.json();
            console.log('Notes: Server response:', result);
            
            if (result.success) {
                // Update pagination info with validation
                if (result.pagination) {
                    const { has_more, total_notes, current_page, total_loaded, loaded_count } = result.pagination;
                    
                    // Start with server response
                    let actualHasMore = has_more;
                    
                    // Only apply safety checks if we have clear indicators
                    if (total_loaded >= total_notes && total_notes > 0) {
                        actualHasMore = false;
                        console.log(`Notes: All notes loaded - ${total_loaded} of ${total_notes}`);
                    }
                    
                    // If we got 0 notes when expecting more, stop
                    if (loaded_count === 0 && current_page > 1) {
                        actualHasMore = false;
                        console.log('Notes: Got 0 notes on page > 1, stopping');
                    }
                    
                    this.hasMore = actualHasMore;
                    this.totalNotes = total_notes;
                    
                    console.log(`Notes pagination: page ${current_page}, server_has_more: ${has_more}, actual_has_more: ${actualHasMore}, total: ${total_notes}, loaded: ${total_loaded}, loaded_count: ${loaded_count}`);
                    console.log(`Notes: Current state - hasMore: ${this.hasMore}, currentPage: ${this.currentPage}, loadedNotesCount: ${this.loadedNotes.length}`);
                }
                
                // Filter unique notes before adding to loaded notes
                const uniqueNotes = [];
                const seenIds = new Set(this.loadedNotes.map(note => note.id));
                
                console.log('loadNotes: Processing response data:', {
                    responseDataCount: result.data.length,
                    responseDataIds: result.data.map(n => n.id),
                    currentLoadedIds: Array.from(seenIds),
                    reset: reset
                });
                
                for (const note of result.data) {
                    if (!seenIds.has(note.id)) {
                        uniqueNotes.push(note);
                    }
                }
                
                console.log('loadNotes: Unique notes filtered:', {
                    uniqueNotesCount: uniqueNotes.length,
                    uniqueNoteIds: uniqueNotes.map(n => n.id)
                });
                
                if (reset) {
                    // In reset mode, use fresh data from server directly
                    this.loadedNotes = result.data;
                    this.displayNotes(result.data, reset);
                    console.log('loadNotes: Reset mode - loadedNotes updated:', this.loadedNotes.length);
                } else {
                    this.loadedNotes = [...this.loadedNotes, ...uniqueNotes];
                    // Only display the new unique notes, not all loaded notes
                    this.displayNotes(uniqueNotes, reset);
                    console.log('loadNotes: Append mode - loadedNotes updated:', this.loadedNotes.length);
                }
                // Update badge with total number of notes (same as Service Orders)
                this.updateNotesCount(this.totalNotes);
                
                // For reset mode, ensure infinite scroll is setup after DOM is updated
                if (reset) {
                    setTimeout(() => {
                        this.setupNotesInfiniteScroll();
                    }, 100);
                }
            } else {
                throw new Error(result.message || window.internalNotesTranslations.failedToLoadNotes);
            }
        } catch (error) {
            console.error('Error loading notes:', error);
            console.error('Error details:', error.message);
            this.displayNotesError();
        } finally {
            this.loadingNotes = false;
        }
    }
    
    getFilters() {
        const search = document.getElementById('notesSearch')?.value || '';
        const authorId = document.getElementById('notesAuthorFilter')?.value || '';
        const dateFilter = document.getElementById('notesDateFilter')?.value || '';
        
        const filters = {};
        if (search) filters.search = search;
        if (authorId) filters.author_id = authorId;
        if (dateFilter) {
            const now = new Date();
            switch (dateFilter) {
                case 'today':
                    filters.date_from = now.toISOString().split('T')[0];
                    break;
                case 'week':
                    const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                    filters.date_from = weekAgo.toISOString().split('T')[0];
                    break;
                case 'month':
                    const monthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                    filters.date_from = monthAgo.toISOString().split('T')[0];
                    break;
            }
        }
        
        return filters;
    }
    
    // Legacy function - replaced by displayNotes
    renderNotes() {
        console.warn('renderNotes is deprecated, using displayNotes instead');
        this.displayNotes(this.notesData || [], true);
    }
    
    // Legacy function - replaced by createNoteHtml
    renderNoteItem(note) {
        console.warn('renderNoteItem is deprecated, using createNoteHtml instead');
        return this.createNoteHtml(note);
    }
    
    async loadMentions() {
        try {
            const response = await fetch(`${window.baseUrl}/internal-notes/unread-mentions`);
            const result = await response.json();
            
            if (result.success) {
                this.mentionsData = result.data;
                this.renderMentions();
                this.updateMentionsCount(result.count);
            }
        } catch (error) {
            console.error('Error loading mentions:', error);
        }
    }
    
    renderMentions() {
        const mentionsList = document.getElementById('mentionsList');
        if (!mentionsList) return;
        
        if (this.mentionsData.length === 0) {
            mentionsList.innerHTML = `
                <div class="empty-state">
                    <i data-feather="at-sign"></i>
                    <h6>No unread mentions</h6>
                    <p>You're all caught up! Mentions will appear here when teammates mention you.</p>
                </div>
            `;
            feather.replace();
            return;
        }
        
        mentionsList.innerHTML = this.mentionsData.map(mention => `
            <div class="mention-alert">
                <div class="mention-alert-content">
                    <i data-feather="at-sign" class="mention-alert-icon"></i>
                    <div class="mention-alert-text">
                        <strong>${mention.firstname} ${mention.lastname}</strong> mentioned you in order SAL-${String(mention.order_id).padStart(5, '0')}
                        <br><small>"${mention.note.substring(0, 100)}..."</small>
                    </div>
                    <button class="mention-alert-action" onclick="internalNotes.markMentionRead(${mention.note_id})">
                        Mark as read
                    </button>
                </div>
            </div>
        `).join('');
        
        feather.replace();
    }
    
    async loadTeamActivity() {
        // Placeholder for team activity - you can implement this to show recent staff activities
        const teamActivityList = document.getElementById('teamActivityList');
        if (teamActivityList) {
            teamActivityList.innerHTML = `
                <div class="empty-state">
                    <i data-feather="users"></i>
                    <h6>Team Activity</h6>
                    <p>Recent team activities will appear here.</p>
                </div>
            `;
            feather.replace();
        }
    }
    
    updateNotesCount(count) {
        const badge = document.getElementById('notesCount');
        if (badge) {
            badge.textContent = count || 0;
            console.log('updateNotesCount: Badge updated to:', count);
        }
    }
    
    updateMentionsCount(count) {
        const badge = document.getElementById('mentionsCount');
        if (badge) {
            badge.textContent = count;
            badge.className = count > 0 ? 'badge bg-warning ms-1' : 'badge bg-secondary ms-1';
        }
    }
    
    updateCommentsCount(count) {
        const badge = document.getElementById('commentsCount');
        if (badge) {
            badge.textContent = count || 0;
            console.log('updateCommentsCount: Badge updated to:', count);
        }
    }
    
    async markMentionRead(noteId) {
        try {
            const response = await fetch(`${window.baseUrl}/internal-notes/mark-mention-read/${noteId}`, {
                method: 'POST'
            });
            
            const result = await response.json();
            if (result.success) {
                this.loadMentions(); // Reload mentions
                this.showAlert('Mention marked as read', 'success');
            }
        } catch (error) {
            console.error('Error marking mention as read:', error);
        }
    }
    
    filterNotes() {
        // Reset pagination when filtering
        this.currentPage = 1;
        this.hasMore = true;
        this.loadedNotes = [];
        this.loadNotes(true);
    }
    
    handleTabSwitch(e) {
        const tabId = e.target.getAttribute('aria-controls');
        
        switch (tabId) {
            case 'notes-pane':
                this.loadNotes();
                break;
            case 'mentions-pane':
                this.loadMentions();
                break;
            case 'team-pane':
                this.loadTeamActivity();
                break;
        }
    }
    
    // Removed duplicate editNote function - using the full implementation below
    
    async deleteNote(noteId) {
        const confirmed = await new Promise((resolve) => {
            Swal.fire({
            title: window.internalNotesTranslations.deleteNoteConfirmation,
            text: window.internalNotesTranslations.deleteNoteText,
            icon: 'warning',
            showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            confirmButtonText: window.internalNotesTranslations.yesDeleteNote,
            cancelButtonText: window.internalNotesTranslations.cancelDelete
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });

        if (!confirmed) return;
        
            try {
                const response = await fetch(`${window.baseUrl}/internal-notes/delete/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Remove note from UI with animation
                const noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
                if (noteItem) {
                    noteItem.style.transition = 'opacity 0.3s ease';
                    noteItem.style.opacity = '0';
                    const self = this;
                    setTimeout(() => {
                        noteItem.remove();
                        
                        // Decrement total notes count and update badge
                        self.totalNotes = Math.max(0, self.totalNotes - 1);
                        self.updateNotesCount(self.totalNotes);
                    }, 300);
                } else {
                    // If note item not found, still decrement total count
                    this.totalNotes = Math.max(0, this.totalNotes - 1);
                    this.updateNotesCount(this.totalNotes);
                }
                
                    this.showAlert(window.internalNotesTranslations.noteDeletedSuccessfully, 'success');
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                    throw new Error(result.message || window.internalNotesTranslations.failedToDeleteNote);
            }
        } catch (error) {
            console.error('Error deleting note:', error);
                this.showAlert(window.internalNotesTranslations.errorDeletingNote + ': ' + error.message, 'error');
            }
    }

    editNote(noteId) {
        // Check if it's a reply or a main note
        let noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
        let replyItem = document.querySelector(`.note-reply[data-note-id="${noteId}"]`);
        
        if (replyItem) {
            // It's a reply
            this.editReply(noteId);
            return;
        }
        
        if (!noteItem) {
            console.error('Note item not found for ID:', noteId);
            return;
        }
        
        const contentDiv = noteItem.querySelector('.note-content');
        const originalText = contentDiv.getAttribute('data-original-text') || contentDiv.textContent;
        
        // Create edit form
        const editForm = document.createElement('div');
        editForm.className = 'note-edit-form';
        editForm.innerHTML = `
            <textarea class="form-control mb-2" id="editNoteText_${noteId}" rows="3" style="font-size: 13px;">${originalText}</textarea>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success" onclick="internalNotes.saveNote(${noteId})">
                    <i data-feather="save" class="icon-xs me-1"></i>Save
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelEdit(${noteId})">
                    <i data-feather="x" class="icon-xs me-1"></i>Cancel
                </button>
            </div>
        `;
        
        // Hide original content and show edit form
        contentDiv.style.display = 'none';
        contentDiv.parentNode.insertBefore(editForm, contentDiv.nextSibling);
        
        // Hide action buttons
        const actions = noteItem.querySelector('.note-actions');
        if (actions) actions.style.display = 'none';
        
        // Re-initialize feather icons first
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Focus on textarea with delay
        setTimeout(() => {
            const textarea = document.getElementById(`editNoteText_${noteId}`);
            if (textarea) {
                textarea.focus();
                textarea.select();
            }
        }, 100);
    }
    
    editReply(replyId) {
        const replyItem = document.querySelector(`.note-reply[data-note-id="${replyId}"]`);
        if (!replyItem) {
            console.error('Reply item not found for ID:', replyId);
            return;
        }
        
        const contentDiv = replyItem.querySelector('.reply-content');
        const originalText = contentDiv.getAttribute('data-original-text') || contentDiv.textContent;
        
        // Create edit form
        const editForm = document.createElement('div');
        editForm.className = 'reply-edit-form';
        editForm.innerHTML = `
            <textarea class="form-control mb-2" id="editReplyText_${replyId}" rows="2" style="font-size: 13px;">${originalText}</textarea>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success" onclick="internalNotes.saveReply(${replyId})">
                    <i data-feather="save" class="icon-xs me-1"></i>Save
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelReplyEdit(${replyId})">
                    <i data-feather="x" class="icon-xs me-1"></i>Cancel
                </button>
            </div>
        `;
        
        // Hide original content and show edit form
        contentDiv.style.display = 'none';
        contentDiv.parentNode.insertBefore(editForm, contentDiv.nextSibling);
        
        // Hide action buttons
        const actions = replyItem.querySelector('.reply-actions');
        if (actions) actions.style.display = 'none';
        
        // Re-initialize feather icons first
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Focus on textarea with delay
        setTimeout(() => {
            const textarea = document.getElementById(`editReplyText_${replyId}`);
            if (textarea) {
                textarea.focus();
                textarea.select();
            }
        }, 100);
    }
    
    saveNote(noteId) {
        const newText = document.getElementById(`editNoteText_${noteId}`).value.trim();
        
        if (!newText) {
            this.showAlert('Note content cannot be empty', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('content', newText);

        fetch(`${window.baseUrl}/internal-notes/update/${noteId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the note in the UI
                const noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
                const contentDiv = noteItem.querySelector('.note-content');
                
                // Update content
                contentDiv.setAttribute('data-original-text', newText);
                contentDiv.innerHTML = this.noteModel?.processMentions ? this.noteModel.processMentions(newText) : newText;
                
                // Remove edit form and show original content
                noteItem.querySelector('.note-edit-form').remove();
                contentDiv.style.display = 'block';
                const actions = noteItem.querySelector('.note-actions');
                if (actions) actions.style.display = 'flex';
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Show detailed success message
                const notePreview = newText.substring(0, 50) + (newText.length > 50 ? '...' : '');
                this.showAlert(`${window.internalNotesTranslations.noteUpdatedSuccessfully}: "${notePreview}"`, 'success');
            } else {
                this.showAlert(data.message || 'Failed to update note', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating note:', error);
            this.showAlert('Error updating note', 'error');
        });
    }
    
    cancelEdit(noteId) {
        // Check if it's a reply or a main note
        let noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
        let replyItem = document.querySelector(`.note-reply[data-note-id="${noteId}"]`);
        
        if (replyItem) {
            // It's a reply
            this.cancelReplyEdit(noteId);
            return;
        }
        
        if (!noteItem) return;
        
        const editForm = noteItem.querySelector('.note-edit-form');
        const contentDiv = noteItem.querySelector('.note-content');
        const actions = noteItem.querySelector('.note-actions');
        
        // Remove edit form and show original content
        if (editForm) editForm.remove();
        if (contentDiv) contentDiv.style.display = 'block';
        if (actions) actions.style.display = 'flex';
    }
    
    saveReply(replyId) {
        const newText = document.getElementById(`editReplyText_${replyId}`).value.trim();
        
        if (!newText) {
            this.showAlert('Reply content cannot be empty', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('content', newText);

        fetch(`${window.baseUrl}/internal-notes/update/${replyId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the reply in the UI
                const replyItem = document.querySelector(`.note-reply[data-note-id="${replyId}"]`);
                const contentDiv = replyItem.querySelector('.reply-content');
                
                // Update content
                contentDiv.setAttribute('data-original-text', newText);
                contentDiv.innerHTML = this.noteModel?.processMentions ? this.noteModel.processMentions(newText) : newText;
                
                // Remove edit form and show original content
                replyItem.querySelector('.reply-edit-form').remove();
                contentDiv.style.display = 'block';
                const actions = replyItem.querySelector('.reply-actions');
                if (actions) actions.style.display = 'flex';
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Show detailed success message
                const replyPreview = newText.substring(0, 50) + (newText.length > 50 ? '...' : '');
                this.showAlert(`${window.internalNotesTranslations.replyUpdatedSuccessfully}: "${replyPreview}"`, 'success');
            } else {
                this.showAlert(data.message || 'Failed to update reply', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating reply:', error);
            this.showAlert('Error updating reply', 'error');
        });
    }
    
    cancelReplyEdit(replyId) {
        const replyItem = document.querySelector(`.note-reply[data-note-id="${replyId}"]`);
        if (!replyItem) return;
        
        const editForm = replyItem.querySelector('.reply-edit-form');
        const contentDiv = replyItem.querySelector('.reply-content');
        const actions = replyItem.querySelector('.reply-actions');
        
        // Remove edit form and show original content
        if (editForm) editForm.remove();
        if (contentDiv) contentDiv.style.display = 'block';
        if (actions) actions.style.display = 'flex';
    }

    toggleReplyForm(noteId) {
        const noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
        if (!noteItem) {
            console.error('Note item not found for ID:', noteId);
            return;
        }
        
        let replyForm = noteItem.querySelector('.reply-form');
        
        if (replyForm) {
            // Toggle existing form
            if (replyForm.style.display === 'none') {
                replyForm.style.display = 'block';
                // Focus on textarea when showing form
                setTimeout(() => {
                    const textarea = document.getElementById(`replyContent_${noteId}`);
                    if (textarea) {
                        textarea.focus();
                    }
                }, 100);
            } else {
                replyForm.style.display = 'none';
            }
        } else {
            // Create new reply form
            replyForm = document.createElement('div');
            replyForm.className = 'reply-form mt-2';
            replyForm.style.display = 'block';
            replyForm.innerHTML = `
                <div class="reply-form-content">
                    <textarea class="form-control mb-2" id="replyContent_${noteId}" rows="2" placeholder="Write a reply..." style="font-size: 13px;"></textarea>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-primary" onclick="internalNotes.submitReply(${noteId})">
                            <i data-feather="send" class="icon-xs me-1"></i>Reply
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelReply(${noteId})">
                            <i data-feather="x" class="icon-xs me-1"></i>Cancel
                        </button>
                    </div>
                </div>
            `;
            
            noteItem.appendChild(replyForm);
            
            // Re-initialize feather icons first
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            // Focus on textarea with delay to ensure DOM is ready
            setTimeout(() => {
                const textarea = document.getElementById(`replyContent_${noteId}`);
                if (textarea) {
                    textarea.focus();
                    console.log('Reply form focused for note:', noteId);
                } else {
                    console.error('Reply textarea not found for note:', noteId);
                }
            }, 150);
        }
    }

    async submitReply(noteId) {
        const textarea = document.getElementById(`replyContent_${noteId}`);
        const content = textarea.value.trim();
        
        if (!content) {
            this.showAlert('Reply content cannot be empty', 'warning');
            textarea.focus();
            return;
        }

        // Get submit button and show loading state
        const submitButton = document.querySelector(`.reply-form button[onclick="internalNotes.submitReply(${noteId})"]`);
        const originalButtonText = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i>Adding...';
        
        // Re-initialize feather icons for loading spinner
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        const formData = new FormData();
        formData.append('note_id', noteId);
        formData.append('content', content);

        try {
            const response = await fetch(`${window.baseUrl}/internal-notes/addReply`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Show detailed success message
                const replyPreview = content.substring(0, 50) + (content.length > 50 ? '...' : '');
                this.showAlert(`${window.internalNotesTranslations.replyAddedSuccessfully}: "${replyPreview}"`, 'success');
                this.cancelReply(noteId);
                this.loadNotes(); // Reload to show the new reply
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(result.message || 'Failed to add reply');
            }
        } catch (error) {
            console.error('Error adding reply:', error);
            this.showAlert('Error adding reply: ' + error.message, 'error');
            
            // Restore button state on error
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    }

    cancelReply(noteId) {
        const noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
        const replyForm = noteItem.querySelector('.reply-form');
        if (replyForm) {
            replyForm.remove();
        }
    }
    
    renderNotesError() {
        const notesList = document.getElementById('notesList');
        if (notesList) {
            notesList.innerHTML = `
                <div class="empty-state">
                    <i data-feather="alert-triangle"></i>
                    <h6>${window.internalNotesTranslations.errorLoadingNotes}</h6>
                    <p>${window.internalNotesTranslations.errorLoadingNotesTryAgain}</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="internalNotes.loadNotes()">
                        ${window.internalNotesTranslations.tryAgain}
                    </button>
                </div>
            `;
            feather.replace();
        }
    }
    
    showAlert(message, type = 'info') {
        // Use the system's toast notification system
        switch (type) {
            case 'success':
                window.showSuccessToast ? window.showSuccessToast(message) : showToast('success', message);
                break;
            case 'danger':
            case 'error':
                window.showErrorToast ? window.showErrorToast(message) : showToast('error', message);
                break;
            case 'warning':
                window.showWarningToast ? window.showWarningToast(message) : showToast('warning', message);
                break;
            case 'info':
            default:
                window.showInfoToast ? window.showInfoToast(message) : showToast('info', message);
                break;
        }
    }

    // Cleanup function
    destroy() {
        if (this.isDestroyed) {
            return;
        }
        
        console.log('Notes: Destroying Internal Notes System instance');
        
        // Remove scroll event listener
        const notesList = document.getElementById('notesList');
        if (notesList && this.notesScrollHandler) {
            notesList.removeEventListener('scroll', this.notesScrollHandler);
            console.log('Notes: Scroll handler removed');
        }
        
        // Mark as destroyed
        this.isDestroyed = true;
        
        // Clear global references
        if (window.internalNotesInstance === this) {
            window.internalNotesInstance = null;
            window.internalNotesInitialized = null;
        }
        
        console.log('Notes: Internal Notes System destroyed');
    }
    
    // Debug functions removed - issue resolved

    // Setup infinite scroll for notes
    setupNotesInfiniteScroll() {
        const notesList = document.getElementById('notesList');
        if (!notesList) {
            console.warn('Notes: notesList element not found');
            return;
        }

        // Force scrollable styles if not set
        if (!notesList.style.maxHeight) {
            notesList.style.maxHeight = '400px';
            notesList.style.overflowY = 'auto';
            console.log('Notes: Applied scrollable styles to container');
        }

        // Check if element is actually scrollable
        const computedStyle = window.getComputedStyle(notesList);
        console.log('Notes: Container styles - maxHeight:', computedStyle.maxHeight, 'overflowY:', computedStyle.overflowY);
        console.log('Notes: Container dimensions - scrollHeight:', notesList.scrollHeight, 'clientHeight:', notesList.clientHeight);

        // Remove any existing scroll listeners to prevent duplicates
        if (this.notesScrollHandler) {
            notesList.removeEventListener('scroll', this.notesScrollHandler);
            console.log('Notes: Removed existing scroll handler');
        }
        
        // Create bound scroll handler
        this.notesScrollHandler = this.handleNotesScroll.bind(this);
        
        // Add scroll event listener
        notesList.addEventListener('scroll', this.notesScrollHandler);
        console.log('Notes: Infinite scroll initialized successfully');
        
        // Test scroll detection
        const isScrollable = notesList.scrollHeight > notesList.clientHeight;
        console.log('Notes: Container scrollable:', isScrollable, 'scrollHeight:', notesList.scrollHeight, 'clientHeight:', notesList.clientHeight);
        
        // If not scrollable but we have more notes, there might be a CSS issue
        if (!isScrollable && this.hasMore && this.loadedNotes.length >= 5) {
            console.warn('Notes: Container should be scrollable but isn\'t. CSS issue detected.');
            console.log('Notes: Current loaded notes:', this.loadedNotes.length, 'hasMore:', this.hasMore);
        }
        
        // Debug buttons removed - issue resolved
    }

    // Handle scroll events for infinite loading
    handleNotesScroll(e) {
        const container = e ? e.target : document.getElementById('notesList');
        if (!container) {
            console.log('Notes: Scroll handler - container not found');
            return;
        }
        
        const { scrollTop, scrollHeight, clientHeight } = container;
        
        // Debug scroll information
        console.log('Notes: Scroll event detected:', {
            scrollTop: Math.round(scrollTop),
            clientHeight: clientHeight,
            scrollHeight: scrollHeight,
            loadingNotes: this.loadingNotes,
            hasMore: this.hasMore,
            currentPage: this.currentPage,
            loadedNotesCount: this.loadedNotes.length,
            totalNotes: this.totalNotes,
            distanceFromBottom: Math.round(scrollHeight - (scrollTop + clientHeight))
        });
        
        if (this.loadingNotes) {
            console.log('Notes: Skip scroll - already loading');
            return;
        }
        
        if (!this.hasMore) {
            console.log('Notes: Skip scroll - no more notes available');
            return;
        }
        
        // Check if scrolled near bottom (within 50px)
        const distanceFromBottom = scrollHeight - (scrollTop + clientHeight);
        if (distanceFromBottom <= 50) {
            console.log('Notes: Near bottom (distance:', Math.round(distanceFromBottom), 'px), loading more...');
            this.loadMoreNotes();
        } else {
            console.log('Notes: Still', Math.round(distanceFromBottom), 'px from bottom');
        }
    }

    // Removed load more button functions - using infinite scroll only
    
    async loadMoreNotes() {
        if (!this.hasMore || this.loadingNotes) {
            return;
        }
        
        console.log('Notes: Loading more, current page:', this.currentPage);
        this.currentPage++;
        
        // Show loading indicator
        this.showLoadingIndicator();
        
        try {
        await this.loadNotes(false); // false = append mode
        } finally {
        // Hide loading indicator
        this.hideLoadingIndicator();
        }
    }
    
        
    
    showLoadingIndicator() {
        const container = document.getElementById('notesList');
        if (!container || document.getElementById('notesLoadingIndicator')) {
            return; // Indicator already exists
        }
        
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'notesLoadingIndicator';
        loadingIndicator.className = 'text-center py-3';
        loadingIndicator.innerHTML = `
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2 mb-0 small">Loading more notes...</p>
        `;
        container.appendChild(loadingIndicator);
    }
    
    hideLoadingIndicator() {
        const loadingIndicator = document.getElementById('notesLoadingIndicator');
        if (loadingIndicator) {
            loadingIndicator.remove();
        }
    }

    displayNotes(notes, reset = true) {
        const container = document.getElementById('notesList');
        if (!container) {
            console.warn('displayNotes: notesList container not found');
            return;
        }
        
        console.log('displayNotes called:', { 
            notesCount: notes.length, 
            reset: reset, 
            noteIds: notes.map(n => n.id) 
        });
        
        if (reset) {
            // Clear container for fresh load
            container.innerHTML = '';
            console.log('displayNotes: Container cleared for reset');
        }
        
        if (notes.length === 0 && reset) {
            container.innerHTML = `
                <div class="empty-notes">
                    <i data-feather="edit-3"></i>
                    <h6>${window.internalNotesTranslations.noInternalNotesYet}</h6>
                    <p>${window.internalNotesTranslations.beFirstAddNote}</p>
                </div>
            `;
            console.log('displayNotes: Empty state displayed');
        } else if (notes.length > 0) {
            if (reset) {
                const noteHtmlArray = notes.map(note => this.createNoteHtml(note));
                container.innerHTML = noteHtmlArray.join('');
            } else {
                // Append new notes (only the new ones, not all)
                const existingIds = new Set();
                container.querySelectorAll('.note-item').forEach(item => {
                    existingIds.add(parseInt(item.dataset.noteId));
                });
                
                const newNotes = notes.filter(note => !existingIds.has(note.id));
                const newNoteHtmlArray = newNotes.map(note => this.createNoteHtml(note));
                container.insertAdjacentHTML('beforeend', newNoteHtmlArray.join(''));
                console.log('displayNotes: Append mode - new notes added:', {
                    existingIds: Array.from(existingIds),
                    newNotesCount: newNotes.length,
                    newNoteIds: newNotes.map(n => n.id)
                });
            }
        }
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Setup infinite scroll after DOM updates
        if (reset) {
            // For reset, wait a bit longer to ensure DOM is fully updated
            setTimeout(() => {
                this.setupNotesInfiniteScroll();
            }, 300);
        } else {
            // For append, setup immediately
            this.setupNotesInfiniteScroll();
        }
    }

    createNoteHtml(note) {
        const attachmentsHtml = note.attachments && note.attachments.length > 0 
            ? this.createNoteAttachmentsHtml(note.attachments) 
            : '';
        
        const processedContent = note.content_processed || note.note;
        
        // Check if current user can edit/delete this note
        const currentUserId = <?= auth()->id() ?? 0 ?>;
        const canEdit = currentUserId && (currentUserId == note.author_id);
        
        const actionButtons = `
            <div class="note-actions">
                <button class="note-action-btn reply" onclick="internalNotes.toggleReplyForm(${note.id})">
                    <i data-feather="message-circle" class="icon-xs me-1"></i>Reply
                </button>
                ${canEdit ? `
                    <button class="note-action-btn edit" onclick="internalNotes.editNote(${note.id})">
                        <i data-feather="edit-2" class="icon-xs me-1"></i><?= lang('App.edit') ?>
                    </button>
                    <button class="note-action-btn delete" onclick="internalNotes.deleteNote(${note.id})">
                        <i data-feather="trash-2" class="icon-xs me-1"></i><?= lang('App.delete') ?>
                    </button>
                ` : ''}
            </div>
        `;
        
        // Process replies if any
        const repliesHtml = note.replies && note.replies.length > 0 
            ? note.replies.map(reply => this.createReplyHtml(reply)).join('') 
            : '';
        

        
        return `
            <div class="note-item" data-note-id="${note.id}" id="note-${note.id}">
                <div class="note-header">
                    <div class="note-author">
                        <div class="note-avatar">${this.getInitials(note.author_name)}</div>
                        <div class="note-author-info">
                            <div class="note-author-name">${note.author_name || 'Unknown'}</div>
                            <div class="note-timestamp" title="${note.created_at_formatted || ''}">${note.created_at_relative || note.time_ago || 'Recently'}</div>
                        </div>
                    </div>
                    ${actionButtons}
                </div>
                <div class="note-content" data-original-text="${this.escapeHtml(note.note || '')}">${processedContent}</div>
                ${attachmentsHtml}
                ${repliesHtml}
            </div>
        `;
    }

    createReplyHtml(reply) {
        const canEdit = <?= auth()->id() ?? 0 ?> && (<?= auth()->id() ?? 0 ?> == reply.author_id);
        
        return `
            <div class="note-reply" data-note-id="${reply.id}">
                <div class="reply-header">
                    <div class="reply-author">
                        <div class="reply-avatar">${this.getInitials(reply.author_name)}</div>
                        <div class="reply-author-info">
                            <div class="reply-author-name">${reply.author_name || 'Unknown'}</div>
                            <div class="reply-timestamp" title="${reply.created_at_formatted || ''}">${reply.created_at_relative || reply.time_ago || 'Recently'}</div>
                        </div>
                    </div>
                    ${canEdit ? `
                        <div class="reply-actions">
                            <button class="note-action-btn edit" onclick="internalNotes.editNote(${reply.id})">
                                <i data-feather="edit-2" class="icon-xs"></i>
                            </button>
                            <button class="note-action-btn delete" onclick="internalNotes.deleteNote(${reply.id})">
                                <i data-feather="trash-2" class="icon-xs"></i>
                            </button>
                        </div>
                    ` : ''}
                </div>
                <div class="reply-content" data-original-text="${this.escapeHtml(reply.note || '')}">${reply.content_processed || reply.note}</div>
            </div>
        `;
    }

    createNoteAttachmentsHtml(attachments) {
        if (!attachments || attachments.length === 0) return '';
        
        const attachmentItems = attachments.map(attachment => {
            const extension = attachment.filename ? attachment.filename.split('.').pop().toLowerCase() : '';
            const viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'txt', 'html', 'htm'];
            const canView = viewableTypes.includes(extension);
            
            const viewUrl = `${attachment.url}?action=view`;
            const downloadUrl = `${attachment.url}?action=download`;
            
            // Get file icon and thumbnail
            const fileIcon = this.getFileIcon(extension);
            const thumbnail = this.getFileThumbnail(attachment, extension);
            
            return `
                <div class="note-attachment-item">
                    <div class="attachment-thumbnail">
                        ${thumbnail}
                    </div>
                    <div class="attachment-info">
                        <div class="attachment-header">
                            ${fileIcon}
                            <span class="attachment-name">${attachment.original_name}</span>
                        </div>
                        <span class="attachment-size">${this.formatFileSize(attachment.size)}</span>
                    </div>
                    <div class="attachment-actions">
                        ${canView ? `<a href="${viewUrl}" class="btn btn-sm btn-outline-primary me-1" target="_blank" title="Ver en navegador">
                            <i data-feather="eye" class="icon-xs"></i>
                        </a>` : ''}
                        <a href="${downloadUrl}" class="btn btn-sm btn-outline-secondary" title="Descargar">
                            <i data-feather="download" class="icon-xs"></i>
                        </a>
                    </div>
                </div>
            `;
        }).join('');
        
        return `
            <div class="note-attachments">
                <div class="note-attachments-title">
                    <i data-feather="paperclip" class="icon-xs me-1"></i>
                    Attachments (${attachments.length})
                </div>
                <div class="note-attachment-list">
                    ${attachmentItems}
                </div>
            </div>
        `;
    }

    getInitials(name) {
        if (!name) return 'U';
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    displayNotesError() {
        const container = document.getElementById('notesList');
        if (!container) return;
        
        container.innerHTML = `
            <div class="empty-notes">
                <i data-feather="alert-circle"></i>
                <h6>${window.internalNotesTranslations.errorLoadingNotes}</h6>
                <p>${window.internalNotesTranslations.errorLoadingNotesTryAgain}</p>
                <button class="btn btn-sm btn-outline-primary" onclick="internalNotes.loadNotes()">
                    ${window.internalNotesTranslations.tryAgain}
                </button>
            </div>
        `;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    getFileIcon(extension) {
        const iconMap = {
            'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
            'doc': '<i class="fas fa-file-word text-primary"></i>',
            'docx': '<i class="fas fa-file-word text-primary"></i>',
            'xls': '<i class="fas fa-file-excel text-success"></i>',
            'xlsx': '<i class="fas fa-file-excel text-success"></i>',
            'ppt': '<i class="fas fa-file-powerpoint text-warning"></i>',
            'pptx': '<i class="fas fa-file-powerpoint text-warning"></i>',
            'txt': '<i class="fas fa-file-alt text-info"></i>',
            'zip': '<i class="fas fa-file-archive text-warning"></i>',
            'rar': '<i class="fas fa-file-archive text-warning"></i>',
            '7z': '<i class="fas fa-file-archive text-warning"></i>',
            'jpg': '<i class="fas fa-file-image text-success"></i>',
            'jpeg': '<i class="fas fa-file-image text-success"></i>',
            'png': '<i class="fas fa-file-image text-success"></i>',
            'gif': '<i class="fas fa-file-image text-success"></i>',
            'svg': '<i class="fas fa-file-image text-success"></i>',
            'mp3': '<i class="fas fa-file-audio text-purple"></i>',
            'wav': '<i class="fas fa-file-audio text-purple"></i>',
            'mp4': '<i class="fas fa-file-video text-danger"></i>',
            'avi': '<i class="fas fa-file-video text-danger"></i>',
            'mov': '<i class="fas fa-file-video text-danger"></i>',
            'html': '<i class="fas fa-file-code text-info"></i>',
            'css': '<i class="fas fa-file-code text-info"></i>',
            'js': '<i class="fas fa-file-code text-info"></i>',
            'php': '<i class="fas fa-file-code text-info"></i>'
        };
        
        return iconMap[extension] || '<i class="fas fa-file text-secondary"></i>';
    }

    getFileThumbnail(attachment, extension) {
        const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        
        if (imageTypes.includes(extension)) {
            // For images, show a small thumbnail
            return `<img src="${attachment.url}?action=view" alt="${attachment.original_name}" class="file-thumbnail-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="file-thumbnail-fallback" style="display: none;">
                        <i class="fas fa-image text-success"></i>
                    </div>`;
        } else {
            // For other files, show a file type icon
            const fileTypeIcons = {
                'pdf': '<i class="fas fa-file-pdf text-danger file-thumbnail-icon"></i>',
                'doc': '<i class="fas fa-file-word text-primary file-thumbnail-icon"></i>',
                'docx': '<i class="fas fa-file-word text-primary file-thumbnail-icon"></i>',
                'xls': '<i class="fas fa-file-excel text-success file-thumbnail-icon"></i>',
                'xlsx': '<i class="fas fa-file-excel text-success file-thumbnail-icon"></i>',
                'zip': '<i class="fas fa-file-archive text-warning file-thumbnail-icon"></i>',
                'mp3': '<i class="fas fa-file-audio text-purple file-thumbnail-icon"></i>',
                'mp4': '<i class="fas fa-file-video text-danger file-thumbnail-icon"></i>'
            };
            
            return fileTypeIcons[extension] || '<i class="fas fa-file text-secondary file-thumbnail-icon"></i>';
        }
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    destroy() {
        this.isDestroyed = true;
        this.submittingNote = false;
        this.loadingNotes = false;
        
        // Remove event listeners
        const noteForm = document.getElementById('noteForm');
        if (noteForm) {
            noteForm.removeEventListener('submit', this.handleNoteSubmit.bind(this));
        }
        
        // Remove scroll listener for infinite scroll
        const notesList = document.getElementById('notesList');
        if (notesList && this.notesScrollHandler) {
            notesList.removeEventListener('scroll', this.notesScrollHandler);
        }
        
        // Clear global reference
        if (window.internalNotesInstance === this) {
            window.internalNotesInstance = null;
        }
    }
}

// Initialize Internal Notes System
let internalNotes; // Keep for backward compatibility

// Set base URL for AJAX requests
window.baseUrl = '<?= rtrim(base_url(), '/') ?>';

// Test function for internal notes connectivity
async function testInternalNotesConnectivity() {
    try {
        console.log('Testing internal notes connectivity...');
        const testUrl = `${window.baseUrl}/internal-notes/test`;
        console.log('Test URL:', testUrl);
        
        const response = await fetch(testUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Test response status:', response.status);
        
        if (response.ok) {
            const result = await response.json();
            console.log('Internal notes test successful:', result);
        } else {
            console.error('Internal notes test failed:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Internal notes connectivity test error:', error);
    }
}

// Test function for form elements
function testFormElements() {
    console.log('Testing form elements...');
    const noteForm = document.getElementById('noteForm');
    const noteContent = document.getElementById('noteContent');
    const noteAttachments = document.getElementById('noteAttachments');
    
    console.log('Form elements test:', {
        noteForm: !!noteForm,
        noteContent: !!noteContent,
        noteAttachments: !!noteAttachments,
        noteContentValue: noteContent ? noteContent.value : 'N/A'
    });
    
    // Removed automatic test content setting to avoid interference
}

// Real-time time status updates
function initializeTimeStatusUpdates() {
    const orderDate = '<?= $order['date'] ?? '' ?>';
    const orderTime = '<?= $order['time'] ?? '' ?>';
    
    if (!orderDate || !orderTime) {
        console.log('TimeStatus: No date/time set, skipping real-time updates');
        // Still update live time even without schedule
        initializeLiveTime();
        return;
    }
    
    console.log('TimeStatus: Initializing real-time updates for:', orderDate, orderTime);
    
    // Update immediately
    updateTimeStatus();
    
    // Update every minute (60 seconds)
    setInterval(updateTimeStatus, 60000);
    
    // Initialize live current time
    initializeLiveTime();
}

// Initialize live current time display
function initializeLiveTime() {
    updateLiveTime();
    // Update live time every minute (60 seconds)
    setInterval(updateLiveTime, 60000);
}

// Update live current time
function updateLiveTime() {
    const liveTimeElement = document.getElementById('liveCurrentTime');
    if (liveTimeElement) {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: true,
            hour: 'numeric',
            minute: '2-digit'
        });
        liveTimeElement.textContent = timeString;
    }
}

// Format time remaining in compact format with minutes always included (1d 20h 5m)
function formatTimeRemaining(hoursRemaining) {
    const isOverdue = hoursRemaining < 0;
    const absHours = Math.abs(hoursRemaining);
    
    // Convert to total minutes for more precise calculations
    const totalMinutes = Math.floor(absHours * 60);
    
    if (totalMinutes < 1) {
        return isOverdue ? "just overdue" : "almost due";
    }
    
    // Calculate days, hours, and minutes
    const days = Math.floor(totalMinutes / (24 * 60));
    const hours = Math.floor((totalMinutes % (24 * 60)) / 60);
    const minutes = totalMinutes % 60;
    
    let parts = [];
    
    if (days > 0) {
        parts.push(`${days}d`);
    }
    if (hours > 0 || days > 0) { // Show hours if there are days or actual hours
        parts.push(`${hours}h`);
    }
    // Always show minutes (unless more than 7 days)
    if (days <= 7) {
        parts.push(`${minutes}m`);
    }
    
    if (parts.length === 0) {
        return isOverdue ? "just overdue" : "almost due";
    }
    
    const timeString = parts.join(' ');
    return isOverdue ? `${timeString} overdue` : `${timeString} left`;
}

function updateTimeStatus() {
    const orderDate = '<?= $order['date'] ?? '' ?>';
    const orderTime = '<?= $order['time'] ?? '' ?>';
    
    try {
        // Update the time status badge and time remaining
        const timeStatusBadge = document.getElementById('timeStatusBadge');
        const timeRemainingDisplay = document.getElementById('timeRemainingDisplay');
        
        if (!orderDate || !orderTime) {
            if (timeRemainingDisplay) {
                timeRemainingDisplay.innerHTML = '<span class="text-muted">No schedule set</span>';
            }
            return;
        }
        
        // Parse scheduled date/time
        const scheduledDateTime = new Date(orderDate + 'T' + orderTime);
        const now = new Date();
        const diff = scheduledDateTime.getTime() - now.getTime();
        const hoursRemaining = diff / (1000 * 60 * 60);
        
        if (!timeStatusBadge) {
            console.log('TimeStatus: Badge element not found');
            return;
        }
        
        let statusText = '';
        let statusClass = '';
        
        if (hoursRemaining < 0) {
            // Past due - DELAY with flashing danger
            statusText = 'DELAY';
            statusClass = 'badge time-status-danger time-status-badge-large';
        } else if (hoursRemaining < 1) {
            // Less than 1 hour - ATTENTION REQUIRED with warning
            statusText = 'ATTENTION REQUIRED';
            statusClass = 'badge time-status-warning time-status-badge-large';
        } else {
            // More than 1 hour - ON TIME with success
            statusText = 'ON TIME';
            statusClass = 'badge time-status-on-time time-status-badge-large';
        }
        
        // Format the time remaining using the new compact function
        const timeRemainingText = formatTimeRemaining(hoursRemaining);
        
        // Update badge
        timeStatusBadge.className = statusClass;
        timeStatusBadge.textContent = statusText;
        
        // Update time remaining display
        if (timeRemainingDisplay) {
            const isOverdue = hoursRemaining < 0;
            const statusColor = isOverdue ? '#dc2626' : (hoursRemaining < 1 ? '#d97706' : '#059669');
            timeRemainingDisplay.innerHTML = `<span style="color: ${statusColor}; font-weight: 600;">${timeRemainingText}</span>`;
        }
        
        console.log(`⏰ TimeStatus: Updated - ${statusText} (${timeRemainingText})`);
        
    } catch (error) {
        console.error('❌ TimeStatus: Error updating status:', error);
    }
}

// Add translation object for JavaScript
window.internalNotesTranslations = {
    noteAddedSuccessfully: '<?= lang('App.note_added_successfully') ?>',
    noteUpdatedSuccessfully: '<?= lang('App.note_updated_successfully') ?>',
    noteDeletedSuccessfully: '<?= lang('App.note_deleted_successfully') ?>',
    replyAddedSuccessfully: '<?= lang('App.reply_added_successfully') ?>',
    replyUpdatedSuccessfully: '<?= lang('App.reply_updated_successfully') ?>',
    replyDeletedSuccessfully: '<?= lang('App.reply_deleted_successfully') ?>',
    errorAddingNote: '<?= lang('App.error_adding_note') ?>',
    errorUpdatingNote: '<?= lang('App.error_updating_note') ?>',
    errorDeletingNote: '<?= lang('App.error_deleting_note') ?>',
    errorLoadingNotes: '<?= lang('App.error_loading_notes') ?>',
    deleteNoteConfirmation: '<?= lang('App.delete_note_confirmation') ?>',
    deleteNoteText: '<?= lang('App.delete_note_text') ?>',
    yesDeleteNote: '<?= lang('App.yes_delete_note') ?>',
    cancelDelete: '<?= lang('App.cancel_delete') ?>',
    noInternalNotesYet: '<?= lang('App.no_internal_notes_yet') ?>',
    beFirstAddNote: '<?= lang('App.be_first_add_note') ?>',
    noteContentRequired: '<?= lang('App.note_content_required') ?>',
    addingNote: '<?= lang('App.adding_note') ?>',
    mentionTeamMembers: '<?= lang('App.mention_team_members') ?>',
    errorLoadingNotesTryAgain: '<?= lang('App.error_loading_notes_try_again') ?>',
    tryAgain: '<?= lang('App.try_again') ?>',
    editNote: '<?= lang('App.edit_note') ?>',
    deleteNote: '<?= lang('App.delete_note') ?>',
    justNow: '<?= lang('App.just_now') ?>',
    filesSelected: ' archivo(s) seleccionado(s)',
    failedToAddNote: 'Error al agregar nota',
    failedToDeleteNote: 'Error al eliminar nota',
    failedToLoadNotes: 'Error al cargar notas'
};

// Comment Mentions System
let commentMentionUsers = [];
let commentMentionSuggestions = [];
let commentSelectedSuggestionIndex = -1;
let commentMentionStartPos = -1;

// Initialize mentions for comments
function initializeCommentMentions() {
    const commentTextarea = document.getElementById('commentText');
    const suggestionsContainer = document.getElementById('mentionSuggestions');
    
    if (!commentTextarea || !suggestionsContainer) {
        console.log('Comment mentions: Required elements not found');
        return;
    }
    
    // Load users for mentions
    loadCommentMentionUsers();
    
    // Add event listeners
    commentTextarea.addEventListener('input', handleCommentMentionInput);
    commentTextarea.addEventListener('keydown', handleCommentMentionKeydown);
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#commentText') && !e.target.closest('#mentionSuggestions')) {
            hideCommentMentionSuggestions();
        }
    });
}

function handleCommentMentionInput(e) {
    const textarea = e.target;
    const cursorPos = textarea.selectionStart;
    const value = textarea.value;
    
    // Find if we're typing after an @ symbol
    const beforeCursor = value.substring(0, cursorPos);
    const match = beforeCursor.match(/@(\w*)$/);
    
    if (match) {
        const query = match[1];
        commentMentionStartPos = cursorPos - match[0].length;
        showCommentMentionSuggestions(query);
    } else {
        hideCommentMentionSuggestions();
    }
}

function handleCommentMentionKeydown(e) {
    const dropdown = document.getElementById('mentionSuggestions');
    if (!dropdown || dropdown.style.display === 'none') return;
    
    const suggestions = dropdown.querySelectorAll('.mention-suggestion');
    
    switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            commentSelectedSuggestionIndex = Math.min(commentSelectedSuggestionIndex + 1, suggestions.length - 1);
            updateCommentSuggestionSelection(suggestions);
            break;
            
        case 'ArrowUp':
            e.preventDefault();
            commentSelectedSuggestionIndex = Math.max(commentSelectedSuggestionIndex - 1, 0);
            updateCommentSuggestionSelection(suggestions);
            break;
            
        case 'Enter':
            e.preventDefault();
            if (commentSelectedSuggestionIndex >= 0 && commentMentionSuggestions[commentSelectedSuggestionIndex]) {
                const user = commentMentionSuggestions[commentSelectedSuggestionIndex];
                insertCommentMention(user.username);
            }
            break;
            
        case 'Escape':
            hideCommentMentionSuggestions();
            break;
    }
}

function loadCommentMentionUsers() {
    fetch('<?= base_url('sales_orders/getStaffUsers') ?>', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            commentMentionUsers = data.users;
            console.log('Loaded users for comment mentions:', commentMentionUsers);
        } else {
            console.error('Failed to load users for comment mentions:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading users for comment mentions:', error);
    });
}

function showCommentMentionSuggestions(query) {
    const filtered = commentMentionUsers.filter(user => 
        user.username.toLowerCase().includes(query.toLowerCase()) ||
        user.name.toLowerCase().includes(query.toLowerCase())
    );
    
    if (filtered.length === 0) {
        hideCommentMentionSuggestions();
        return;
    }
    
    const dropdown = document.getElementById('mentionSuggestions');
    if (!dropdown) return;
    
    dropdown.innerHTML = '';
    filtered.forEach((user, index) => {
        const suggestion = document.createElement('div');
        suggestion.className = 'mention-suggestion';
        if (index === 0) suggestion.classList.add('active');
        
        suggestion.innerHTML = `
            <div class="mention-suggestion-user">
                <div class="mention-suggestion-avatar">
                    ${user.name.substring(0, 1).toUpperCase()}
                </div>
                <div class="mention-suggestion-info">
                    <div class="mention-suggestion-name">${user.name}</div>
                    <div class="mention-suggestion-username">@${user.username}</div>
                </div>
            </div>
        `;
        
        suggestion.addEventListener('click', () => {
            insertCommentMention(user.username);
        });
        
        dropdown.appendChild(suggestion);
    });
    
    commentMentionSuggestions = filtered;
    commentSelectedSuggestionIndex = 0;
    dropdown.style.display = 'block';
}

function hideCommentMentionSuggestions() {
    const dropdown = document.getElementById('mentionSuggestions');
    if (dropdown) {
        dropdown.style.display = 'none';
    }
    commentSelectedSuggestionIndex = -1;
}

function updateCommentSuggestionSelection(suggestions) {
    suggestions.forEach((suggestion, index) => {
        suggestion.classList.toggle('active', index === commentSelectedSuggestionIndex);
    });
}

function insertCommentMention(username) {
    const textarea = document.getElementById('commentText');
    if (!textarea) return;
    
    const value = textarea.value;
    const cursorPos = textarea.selectionStart;
    
    // Find the @ symbol before the cursor
    const beforeCursor = value.substring(0, cursorPos);
    const afterCursor = value.substring(cursorPos);
    const atIndex = beforeCursor.lastIndexOf('@');
    
    if (atIndex !== -1) {
        const newValue = 
            value.substring(0, atIndex) + 
            `@${username} ` + 
            afterCursor;
        
        textarea.value = newValue;
        textarea.focus();
        textarea.setSelectionRange(atIndex + username.length + 2, atIndex + username.length + 2);
    }
    
    hideCommentMentionSuggestions();
}

// Internal Notes System Class

    // Initialize everything when DOM is ready - CONSOLIDATED LISTENER
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing Sales Order View - Single DOMContentLoaded listener');
    
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Load recent activity (first page)
    loadRecentActivity(true);

    // Initialize forms
    initializeForms();
    
    // Setup infinite scroll for activities
    setupActivitiesInfiniteScroll();

    // Load comments (first page) - infinite scroll will be set up automatically
    loadComments(true);
    
    // Initialize mentions for comments
    initializeCommentMentions();
    
    // Initialize modal event listeners (only once)
    initializeModalEventListeners();
    
    // Initialize real-time time status updates
    initializeTimeStatusUpdates();
    
    // Initialize mobile quick actions
    initializeMobileQuickActions();
    
    // Make auto QR image clickable to open advanced modal
    const autoQrImage = document.querySelector('.qr-image-auto');
    if (autoQrImage) {
        autoQrImage.addEventListener('click', function() {
            const orderId = <?= $order['id'] ?? 0 ?>;
            generateQRCode(orderId);
            
            // Show modal
            const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
            qrModal.show();
        });
        
        // Add tooltip to indicate it's clickable
        autoQrImage.setAttribute('title', 'Click for advanced QR options');
        autoQrImage.style.cursor = 'pointer';
    }
    
    // Initialize followers system
    if (typeof orderData !== 'undefined' && orderData.id) {
        loadFollowers();
    }
    
    // Test internal notes connectivity first
    testInternalNotesConnectivity();
    
    // Test form elements
    setTimeout(() => {
        testFormElements();
    }, 1000);
    
    // Initialize the internal notes system (only once)
    <?php if (isset($order) && $order): ?>
    if (!window.internalNotes && !window.internalNotesInitialized) {
        console.log('🔧 Initializing Internal Notes System for order:', <?= $order['id'] ?>);
        try {
        window.internalNotes = new InternalNotesSystem(<?= $order['id'] ?>);
        internalNotes = window.internalNotes; // For backward compatibility
            console.log('✅ Internal Notes System initialized successfully');
        } catch (error) {
            console.error('❌ Failed to initialize Internal Notes System:', error);
        }
    } else {
        console.warn('⚠️ Internal Notes System already initialized, skipping');
    }
    <?php endif; ?>
    
    // Auto-open edit modal if edit parameter is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('edit') === '1') {
        console.log('OrderView: Auto-opening edit modal due to edit=1 parameter');
        
        // Small delay to ensure page is fully loaded
        setTimeout(() => {
            const editButton = document.querySelector('.btn-primary[onclick*="editOrder"]');
            if (editButton) {
                editButton.click();
                console.log('OrderView: Edit modal opened automatically');
                
                // Remove the edit parameter from URL to avoid re-opening on refresh
                const url = new URL(window.location);
                url.searchParams.delete('edit');
                window.history.replaceState({}, document.title, url);
            } else {
                console.warn('OrderView: Edit button not found for auto-opening modal');
                console.log('OrderView: Available buttons:', document.querySelectorAll('button[onclick*="editOrder"], .btn[onclick*="editOrder"]'));
            }
        }, 500);
    }
    
    console.log('✅ Sales Order View initialization completed');
});
</script>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smsModalLabel">
                    <i data-feather="message-square" class="icon-sm me-2"></i>
                    Send SMS to Contact
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="smsForm">
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i data-feather="user" class="icon-sm me-2"></i>
                        <div>
                            <strong>Sending to:</strong> <?= $order['salesperson_name'] ?? 'Contact' ?> 
                            <span class="text-muted">(Assigned Contact)</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="smsPhone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="smsPhone" value="<?= $order['salesperson_phone'] ?? '' ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="smsMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="smsMessage" rows="4" placeholder="Enter your SMS message..." maxlength="160" required></textarea>
                        <div class="form-text d-flex justify-content-between align-items-center sms-char-count-container">
                            <span>
                            <span id="smsCharCount">0</span>/160 characters
                                <span id="smsCharWarning" class="ms-2" style="display: none;">
                                    <i data-feather="alert-triangle" class="icon-xs"></i>
                                    <small>Message too long!</small>
                                </span>
                            </span>
                            <span id="smsCharStatus" class="badge bg-success-subtle text-success">
                                <i data-feather="check" class="icon-xs me-1"></i>
                                Good
                            </span>
                        </div>
                        <div class="alert alert-light py-2 mt-2">
                            <i data-feather="link" class="icon-xs me-1 text-info"></i>
                            <small class="text-info">Professional short URL will be automatically added to your message (powered by Lima Links)</small>
                        </div>
                        <div id="smsLengthAlert" class="alert alert-danger py-2 mt-2" style="display: none;">
                            <i data-feather="alert-circle" class="icon-xs me-1"></i>
                            <small><strong>Message exceeds SMS limit!</strong> Please shorten your message to send via SMS.</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="smsTemplate" class="form-label">Quick Templates</label>
                        <select class="form-select" id="smsTemplate" onchange="applySmsTemplate()">
                            <option value="">Select a template...</option>
                            <!-- Templates will be loaded dynamically -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i data-feather="send" class="icon-sm me-1"></i>
                        Send SMS
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QR Code Modal (Simplified) -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="qrModalLabel">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code - Order SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (isset($qr_data) && $qr_data): ?>
                <!-- QR Code Display -->
                <div class="qr-large-container mb-4">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Order SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
                         class="img-fluid" 
                         style="max-width: 300px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                </div>
                
                <!-- Short URL Display -->
                <div class="mb-4">
                    <label class="form-label text-muted small">Short URL</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-center" 
                               id="qrShortUrl" 
                               value="<?= $qr_data['short_url'] ?>" 
                               readonly>
                        <button class="btn btn-outline-secondary" onclick="copyToClipboard('qrShortUrl')">
                            <i data-feather="copy" class="icon-xs"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-primary" onclick="downloadQRSimple()">
                        <i data-feather="download" class="icon-xs me-1"></i>
                        Download
                    </button>
                    <button class="btn btn-outline-success" onclick="shareQRSimple()">
                        <i data-feather="share-2" class="icon-xs me-1"></i>
                        Share
                    </button>
                </div>
                
                <!-- Usage Info -->
                <div class="mt-4 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i data-feather="info" class="icon-xs me-1"></i>
                        Scan with any phone camera or QR reader to access this order instantly
                    </small>
                </div>
                <?php else: ?>
                <!-- QR Code Not Available -->
                <div class="text-center py-4">
                    <i data-feather="alert-triangle" class="icon-lg text-warning mb-3"></i>
                    <h6 class="text-warning">QR Code Unavailable</h6>
                    <p class="text-muted small">Lima Links API not configured</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">
                    <i data-feather="mail" class="icon-sm me-2"></i>
                    Send Email to Contact
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <form id="emailForm">
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center mb-3">
                        <i data-feather="user" class="icon-sm me-2"></i>
                <div>
                            <strong>Sending to:</strong> <?= $order['salesperson_name'] ?? 'Contact' ?> 
                            <span class="text-muted">(Assigned Contact)</span>
                </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emailTo" class="form-label">To</label>
                                <input type="email" class="form-control" id="emailTo" value="<?= $order['salesperson_email'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="emailCc" class="form-label">CC (Optional)</label>
                                <input type="email" class="form-control" id="emailCc" placeholder="cc@example.com">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="emailSubject" placeholder="Enter subject..." required>
                    </div>
                    <div class="mb-3">
                        <label for="emailTemplate" class="form-label">Email Templates</label>
                        <select class="form-select" id="emailTemplate" onchange="applyEmailTemplate()">
                            <option value="">Select a template...</option>
                            <!-- Templates will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="emailMessage" rows="6" placeholder="Enter your email message..." required></textarea>
                        <div class="form-text">
                            <i data-feather="info" class="icon-xs me-1"></i>
                            <small class="text-info">Order URL will be automatically added to the end of your message</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="emailIncludeOrderDetails">
                            <label class="form-check-label" for="emailIncludeOrderDetails">
                                Include order details in email
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i data-feather="send" class="icon-sm me-1"></i>
                        Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Actions Modal (Mobile) -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content quick-actions-modal">
            <div class="modal-header quick-actions-modal-header">
                <div class="d-flex align-items-center">
                    <div class="quick-actions-icon-wrapper me-3">
                        <i data-feather="zap" class="quick-actions-main-icon"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="quickActionsModalLabel">Quick Actions</h5>
                        <small class="quick-actions-subtitle">Order SAL-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body quick-actions-modal-body">
                <!-- Contact Info Card -->
                <div class="quick-actions-contact-card mb-4">
                    <div class="d-flex align-items-center">
                        <div class="contact-avatar me-3">
                            <i data-feather="user" class="contact-icon"></i>
                        </div>
                        <div class="contact-info">
                            <h6 class="contact-name mb-1"><?= $order['salesperson_name'] ?? 'Not assigned' ?></h6>
                            <small class="contact-role text-muted">Assigned Contact</small>
                        </div>
                    </div>
                </div>

                <div class="quick-actions-grid">
                    <!-- Update Status Section - Only for Staff and Admin Users -->
                    <?php if (auth()->user() && in_array(auth()->user()->user_type, ['staff', 'admin'])): ?>
                    <div class="quick-action-section mb-4">
                        <label class="quick-action-label">
                            <i data-feather="refresh-cw" class="quick-action-label-icon"></i>
                            Update Status
                        </label>
                        <select class="form-select quick-action-select" id="statusSelectMobile" onchange="updateStatusFromModal()">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>⚙️ Processing</option>
                            <option value="in_progress" <?= $order['status'] == 'in_progress' ? 'selected' : '' ?>>🔄 In Progress</option>
                            <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>✅ Completed</option>
                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                        </select>
                    </div>
                    <?php else: ?>
                    <!-- Status Display Only for Non-Staff/Admin Users -->
                    <div class="quick-action-section mb-4">
                        <label class="quick-action-label">
                            <i data-feather="info" class="quick-action-label-icon"></i>
                            Current Status
                        </label>
                        <div class="form-control-plaintext">
                            <?php
                            $statusIcons = [
                                'pending' => '⏳',
                                'processing' => '⚙️',
                                'in_progress' => '🔄',
                                'completed' => '✅',
                                'cancelled' => '❌'
                            ];
                            $statusIcon = $statusIcons[$order['status']] ?? '';
                            echo $statusIcon . ' ' . ucfirst(str_replace('_', ' ', $order['status']));
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons Grid -->
                    <div class="quick-actions-buttons">
                        <!-- Call Contact -->
                        <?php if ($order['salesperson_phone']): ?>
                        <a href="tel:<?= $order['salesperson_phone'] ?>" class="quick-action-btn quick-action-call">
                            <div class="quick-action-icon">
                                <i data-feather="phone" class="icon-md"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Call</span>
                                <small class="quick-action-desc">Contact directly</small>
                            </div>
                        </a>
                        <?php endif; ?>

                        <!-- Send SMS -->
                        <?php if ($order['salesperson_phone']): ?>
                        <button class="quick-action-btn quick-action-sms" data-bs-toggle="modal" data-bs-target="#smsModal" onclick="closeQuickActionsModal()">
                            <div class="quick-action-icon">
                                <i data-feather="message-square" class="icon-md"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">SMS</span>
                                <small class="quick-action-desc">Send message</small>
                            </div>
                        </button>
                        <?php endif; ?>

                        <!-- Send Email -->
                        <?php if ($order['salesperson_email']): ?>
                        <button class="quick-action-btn quick-action-email" data-bs-toggle="modal" data-bs-target="#emailModal" onclick="closeQuickActionsModal()">
                            <div class="quick-action-icon">
                                <i data-feather="mail" class="icon-md"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Email</span>
                                <small class="quick-action-desc">Send email</small>
                            </div>
                        </button>
                        <?php endif; ?>

                        <!-- Send Alert -->
                        <button class="quick-action-btn quick-action-alert" onclick="sendNotificationFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i data-feather="bell" class="icon-md"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Alert</span>
                                <small class="quick-action-desc">Send notification</small>
                            </div>
                        </button>

                        <!-- Generate QR -->
                        <button class="quick-action-btn quick-action-qr" onclick="generateQRCodeFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i data-feather="smartphone" class="icon-md"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">QR Code</span>
                                <small class="quick-action-desc">Generate QR</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Follower Modal -->
<div class="modal fade" id="addFollowerModal" tabindex="-1" aria-labelledby="addFollowerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFollowerModalLabel">
                    <i data-feather="user-plus" class="icon-sm me-2"></i>
                    Add Follower
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addFollowerForm">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Select User</label>
                        <select class="form-select" id="followerUserId" required>
                            <option value="">Loading users...</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Follower Type</label>
                        <select class="form-select" id="followerType" required>
                            <option value="client_contact">Client Contact</option>
                            <option value="staff">Staff Member</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notification Preferences</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifyStatusChanges" checked>
                                    <label class="form-check-label" for="notifyStatusChanges">
                                        Status Changes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifyComments" checked>
                                    <label class="form-check-label" for="notifyComments">
                                        New Comments
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="notifyMentions" checked>
                                    <label class="form-check-label" for="notifyMentions">
                                        Mentions
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                    <label class="form-check-label" for="emailNotifications">
                                        Email Notifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="smsNotifications">
                                    <label class="form-check-label" for="smsNotifications">
                                        SMS Notifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                    <label class="form-check-label" for="pushNotifications">
                                        Push Notifications
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="addFollower()">
                    <i data-feather="user-plus" class="icon-xs me-1"></i>
                    Add Follower
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Follower Preferences Modal -->
<div class="modal fade" id="followerPreferencesModal" tabindex="-1" aria-labelledby="followerPreferencesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followerPreferencesModalLabel">
                    <i data-feather="settings" class="icon-sm me-2"></i>
                    Notification Preferences
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="followerPreferencesForm">
                    <input type="hidden" id="preferencesUserId">
                    <div class="mb-3">
                        <label class="form-label fw-medium">User</label>
                        <input type="text" class="form-control" id="preferencesUserName" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Notification Types</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prefStatusChanges">
                                    <label class="form-check-label" for="prefStatusChanges">
                                        Status Changes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prefComments">
                                    <label class="form-check-label" for="prefComments">
                                        New Comments
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prefMentions">
                                    <label class="form-check-label" for="prefMentions">
                                        Mentions
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prefEmailNotifications">
                                    <label class="form-check-label" for="prefEmailNotifications">
                                        Email Notifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prefSmsNotifications">
                                    <label class="form-check-label" for="prefSmsNotifications">
                                        SMS Notifications
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="prefPushNotifications">
                                    <label class="form-check-label" for="prefPushNotifications">
                                        Push Notifications
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateFollowerPreferences()">
                    <i data-feather="save" class="icon-xs me-1"></i>
                    Save Preferences
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// ==================== FOLLOWERS FUNCTIONALITY ====================

let followersData = [];
let availableUsers = [];

/**
 * Load followers for the current order
 */
function loadFollowers() {
    const orderId = orderData.id;
    
    fetch(`<?= base_url('sales_orders/getFollowers') ?>/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            followersData = data.followers;
            displayFollowers(data.followers);
            updateFollowersCount(data.count);
        } else {
            console.error('Failed to load followers:', data.message);
            displayFollowersError('Failed to load followers');
        }
    })
    .catch(error => {
        console.error('Error loading followers:', error);
        displayFollowersError('Error loading followers');
    });
}

/**
 * Display followers in the UI
 */
function displayFollowers(followers) {
    const followersList = document.getElementById('followersList');
    
    if (!followersList) {
        console.error('Followers container not found');
        return;
    }
    
    if (!followers || followers.length === 0) {
        followersList.innerHTML = `
            <div class="text-center py-3 text-muted">
                <i data-feather="users" class="icon-md mb-2"></i>
                <p class="mb-0 small">No followers yet</p>
            </div>
        `;
        feather.replace();
        return;
    }
    
    let html = '';
    followers.forEach(follower => {
        const avatar = getAvatarUrl(follower);
        
        // Ensure preferences is valid JSON string
        let preferencesJson = '{}';
        try {
            if (follower.notification_preferences) {
                // If it's already a string, use it; if it's an object, stringify it
                if (typeof follower.notification_preferences === 'string') {
                    JSON.parse(follower.notification_preferences); // Validate it's valid JSON
                    preferencesJson = follower.notification_preferences;
                } else {
                    preferencesJson = JSON.stringify(follower.notification_preferences);
                }
            }
        } catch (error) {
            console.warn('Invalid notification preferences for follower:', follower.user_id, error);
            preferencesJson = '{}';
        }
        
        html += `
            <div class="follower-item d-flex align-items-center justify-content-between py-2 px-1 border-bottom">
                <div class="d-flex align-items-center flex-grow-1 me-2">
                    <img src="${avatar}" alt="${follower.full_name}" class="rounded-circle me-2" width="36" height="36">
                    <div class="flex-grow-1" style="min-width: 0;">
                        <h6 class="mb-0 text-truncate">${follower.full_name}</h6>
                        <small class="text-muted d-block">
                            <span class="badge badge-sm ${follower.follower_type === 'staff' ? 'bg-info' : 'bg-success'} me-1">${follower.follower_type === 'staff' ? 'Staff' : 'Contact'}</span>
                            ${follower.email ? `<span class="text-truncate d-inline-block" style="max-width: 120px;">${follower.email}</span>` : ''}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <button class="btn btn-sm btn-outline-primary follower-settings-btn p-1" 
                            data-user-id="${follower.user_id}" 
                            data-user-name="${escapeHtml(follower.full_name)}" 
                            data-preferences='${escapeHtml(preferencesJson)}'
                            title="Edit Preferences">
                        <i data-feather="settings" class="icon-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger follower-remove-btn p-1" 
                            data-user-id="${follower.user_id}" 
                            data-user-name="${escapeHtml(follower.full_name)}"
                            title="Remove Follower">
                        <i data-feather="user-minus" class="icon-xs"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    followersList.innerHTML = html;
    feather.replace();
    
    // Add event listeners for follower buttons
    document.querySelectorAll('.follower-settings-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const preferences = this.getAttribute('data-preferences');
            editFollowerPreferences(userId, userName, preferences);
        });
    });
    
    document.querySelectorAll('.follower-remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            removeFollower(userId, userName);
        });
    });
}

/**
 * Display error message in followers list
 */
function displayFollowersError(message) {
    const followersList = document.getElementById('followersList');
    followersList.innerHTML = `
        <div class="text-center py-3">
            <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
            <p class="text-danger mb-2">${message}</p>
            <button class="btn btn-sm btn-outline-primary" onclick="loadFollowers()">
                <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                Try Again
            </button>
        </div>
    `;
    feather.replace();
}

/**
 * Update followers count badge
 */
function updateFollowersCount(count) {
    const countBadge = document.getElementById('followersCount');
    if (countBadge) {
        countBadge.textContent = count;
    }
}

/**
 * Show add follower modal
 */
function showAddFollowerModal() {
    // Load available users
    loadAvailableUsers();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('addFollowerModal'));
    
    // Add event listener to handle focus properly  
    const modalElement = document.getElementById('addFollowerModal');
    modalElement.addEventListener('shown.bs.modal', function () {
        // Focus on the first focusable element
        const firstFocusable = modalElement.querySelector('select, input, button:not([disabled])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
    });
    
    modal.show();
}

/**
 * Load available users for adding as followers
 */
function loadAvailableUsers() {
    const orderId = orderData.id;
    const userSelect = document.getElementById('followerUserId');
    
    userSelect.innerHTML = '<option value="">Loading users...</option>';
    
    fetch(`<?= base_url('sales_orders/getAvailableFollowers') ?>/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.text().then(text => {
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data);
        
        if (data.success) {
            populateUserSelect(data.available_users);
        } else {
            console.error('Failed to load available users:', data.message);
            userSelect.innerHTML = '<option value="">Error loading users</option>';
            showToast('error', data.message || 'Failed to load available users');
        }
    })
    .catch(error => {
        console.error('Error loading available users:', error);
        userSelect.innerHTML = '<option value="">Error loading users</option>';
        showToast('error', 'Error loading available users: ' + error.message);
    });
}

/**
 * Populate user select dropdown
 */
function populateUserSelect(availableUsers) {
    const userSelect = document.getElementById('followerUserId');
    
    if (!availableUsers.client_contacts?.length && !availableUsers.staff_users?.length) {
        userSelect.innerHTML = '<option value="">No users available to add</option>';
        return;
    }
    
    let html = '<option value="">Select a user...</option>';
    
    if (availableUsers.client_contacts?.length) {
        html += '<optgroup label="Client Contacts">';
        availableUsers.client_contacts.forEach(user => {
            html += `<option value="${user.id}" data-type="client_contact">${escapeHtml(user.full_name)} (${escapeHtml(user.email || user.username)})</option>`;
        });
        html += '</optgroup>';
    }
    
    if (availableUsers.staff_users?.length) {
        html += '<optgroup label="Staff Members">';
        availableUsers.staff_users.forEach(user => {
            html += `<option value="${user.id}" data-type="staff">${escapeHtml(user.full_name)} (${escapeHtml(user.email || user.username)})</option>`;
        });
        html += '</optgroup>';
    }
    
    userSelect.innerHTML = html;
    
    // Auto-select follower type based on user selection
    userSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const userType = selectedOption.getAttribute('data-type');
        if (userType) {
            document.getElementById('followerType').value = userType;
        }
    });
}

/**
 * Add a new follower
 */
function addFollower() {
    const userId = document.getElementById('followerUserId').value;
    const followerType = document.getElementById('followerType').value;
    
    if (!userId) {
        showToast('warning', 'Please select a user');
        return;
    }
    
    // Get notification preferences
    const preferences = {
        status_changes: document.getElementById('notifyStatusChanges').checked,
        new_comments: document.getElementById('notifyComments').checked,
        mentions: document.getElementById('notifyMentions').checked,
        email_notifications: document.getElementById('emailNotifications').checked,
        sms_notifications: document.getElementById('smsNotifications').checked,
        push_notifications: document.getElementById('pushNotifications').checked
    };
    
    const formData = new FormData();
    formData.append('order_id', orderData.id);
    formData.append('user_id', userId);
    formData.append('follower_type', followerType);
    formData.append('notification_preferences', JSON.stringify(preferences));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= base_url('sales_orders/addFollower') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Follower added successfully');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addFollowerModal'));
            modal.hide();
            
            // Reset form
            document.getElementById('addFollowerForm').reset();
            document.getElementById('notifyStatusChanges').checked = true;
            document.getElementById('notifyComments').checked = true;
            document.getElementById('notifyMentions').checked = true;
            document.getElementById('emailNotifications').checked = true;
            document.getElementById('pushNotifications').checked = true;
            
            // Reload followers
            loadFollowers();
        } else {
            showToast('error', data.message || 'Failed to add follower');
        }
    })
    .catch(error => {
        console.error('Error adding follower:', error);
        showToast('error', 'Error adding follower');
    });
}

/**
 * Remove a follower
 */
function removeFollower(userId, userName) {
    Swal.fire({
        title: 'Remove Follower',
        text: `Are you sure you want to remove ${userName} as a follower?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('order_id', orderData.id);
            formData.append('user_id', userId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
            
            fetch('<?= base_url('sales_orders/removeFollower') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message || 'Follower removed successfully');
                    loadFollowers();
                } else {
                    showToast('error', data.message || 'Failed to remove follower');
                }
            })
            .catch(error => {
                console.error('Error removing follower:', error);
                showToast('error', 'Error removing follower');
            });
        }
    });
}

/**
 * Edit follower notification preferences
 */
function editFollowerPreferences(userId, userName, preferencesJson) {
    // Parse preferences
    let preferences = {};
    try {
        preferences = JSON.parse(preferencesJson);
    } catch (e) {
        console.error('Error parsing preferences:', e);
        preferences = {};
    }
    
    // Set form values
    document.getElementById('preferencesUserId').value = userId;
    document.getElementById('preferencesUserName').value = userName;
    document.getElementById('prefStatusChanges').checked = preferences.status_changes ?? true;
    document.getElementById('prefComments').checked = preferences.new_comments ?? true;
    document.getElementById('prefMentions').checked = preferences.mentions ?? true;
    document.getElementById('prefEmailNotifications').checked = preferences.email_notifications ?? true;
    document.getElementById('prefSmsNotifications').checked = preferences.sms_notifications ?? false;
    document.getElementById('prefPushNotifications').checked = preferences.push_notifications ?? true;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('followerPreferencesModal'));
    modal.show();
}

/**
 * Update follower notification preferences
 */
function updateFollowerPreferences() {
    const userId = document.getElementById('preferencesUserId').value;
    
    const preferences = {
        status_changes: document.getElementById('prefStatusChanges').checked,
        new_comments: document.getElementById('prefComments').checked,
        mentions: document.getElementById('prefMentions').checked,
        email_notifications: document.getElementById('prefEmailNotifications').checked,
        sms_notifications: document.getElementById('prefSmsNotifications').checked,
        push_notifications: document.getElementById('prefPushNotifications').checked
    };
    
    const formData = new FormData();
    formData.append('order_id', orderData.id);
    formData.append('user_id', userId);
    formData.append('preferences', JSON.stringify(preferences));
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= base_url('sales_orders/updateFollowerPreferences') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Preferences updated successfully');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('followerPreferencesModal'));
            modal.hide();
            
            // Reload followers
            loadFollowers();
        } else {
            showToast('error', data.message || 'Failed to update preferences');
        }
    })
    .catch(error => {
        console.error('Error updating preferences:', error);
        showToast('error', 'Error updating preferences');
    });
}

/**
 * Escape HTML to prevent XSS and attribute issues
 */
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/**
 * Get avatar URL for a user
 */
function getAvatarUrl(user) {
    if (user.avatar && user.avatar !== '') {
        return `<?= base_url('assets/images/users/') ?>${user.avatar}`;
    }
    
    // Generate avatar based on avatar_style
    const style = user.avatar_style || 'initials';
    const name = user.full_name || 'User';
    
    switch (style) {
        case 'initials':
            return generateInitialsAvatar(name);
        case 'identicon':
            return generateIdenticonAvatar(name);
        case 'robohash':
            return `https://robohash.org/${encodeURIComponent(name)}.png?size=40x40`;
        default:
            return generateInitialsAvatar(name);
    }
}

/**
 * Generate initials avatar
 */
function generateInitialsAvatar(name) {
    const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    const colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14'];
    const color = colors[name.length % colors.length];
    
    const canvas = document.createElement('canvas');
    canvas.width = 40;
    canvas.height = 40;
    const ctx = canvas.getContext('2d');
    
    // Background
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, 40, 40);
    
    // Text
    ctx.fillStyle = 'white';
    ctx.font = 'bold 16px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(initials, 20, 20);
    
    return canvas.toDataURL();
}

/**
 * Generate identicon avatar
 */
function generateIdenticonAvatar(name) {
    // Simple identicon implementation
    const hash = name.split('').reduce((a, b) => {
        a = ((a << 5) - a) + b.charCodeAt(0);
        return a & a;
    }, 0);
    
    const color = `hsl(${Math.abs(hash) % 360}, 70%, 50%)`;
    
    const canvas = document.createElement('canvas');
    canvas.width = 40;
    canvas.height = 40;
    const ctx = canvas.getContext('2d');
    
    // Background
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, 40, 40);
    
    // Pattern
    ctx.fillStyle = 'rgba(255, 255, 255, 0.3)';
    for (let i = 0; i < 5; i++) {
        for (let j = 0; j < 5; j++) {
            if ((hash >> (i * 5 + j)) & 1) {
                ctx.fillRect(i * 8, j * 8, 8, 8);
            }
        }
    }
    
    return canvas.toDataURL();
}

// Followers initialization is now handled in the main DOMContentLoaded listener
</script>

<style>
/* Custom scrollbar for comments list */
#commentsList::-webkit-scrollbar {
    width: 8px;
}

#commentsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#commentsList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

#commentsList::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Ensure proper scrolling behavior */
#commentsList {
    scroll-behavior: smooth;
}

/* Loading indicator for comments */
.comments-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    border-top: 1px solid #e9ecef;
}

.comments-loading .spinner-border {
    width: 1.5rem;
    height: 1.5rem;
}
</style>

<?= $this->endSection() ?>
