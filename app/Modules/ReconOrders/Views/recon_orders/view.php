<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? lang('App.recon_order') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? lang('App.recon_order') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('recon_orders') ?>"><?= lang('App.recon_orders') ?></a></li>
<li class="breadcrumb-item active"><?= $title ?? lang('App.recon_order') ?></li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
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

/* VIN Link Styling */
.vin-link {
    font-family: 'Courier New', monospace !important;
    transition: all 0.2s ease;
    border-radius: 4px;
    padding: 2px 4px;
    font-size: inherit;
    font-weight: 600 !important;
    display: inline-block;
}

.vin-link:hover {
    background-color: rgba(13, 110, 253, 0.1);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-decoration: underline !important;
}

.vin-link:focus {
    outline: 2px solid rgba(13, 110, 253, 0.3);
    outline-offset: 2px;
}

/* VIN in top bar specific styling */
.top-bar-sub .vin-link {
    font-size: 0.75rem;
    color: #0d6efd !important;
    text-decoration: none !important;
}

.top-bar-sub .vin-link:hover {
    color: #0a58ca !important;
}

/* VIN in basic information specific styling */  
.list-unstyled .vin-link {
    color: #0d6efd !important;
    text-decoration: none !important;
}

.list-unstyled .vin-link:hover {
    color: #0a58ca !important;
}

/* Quick Actions Vehicle Button */
.btn-outline-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
}

.btn-outline-success small {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

.quick-actions-card .btn {
    transition: all 0.2s ease;
}

.quick-actions-card .btn:hover {
    transform: translateY(-1px);
}

/* Vehicle Details Button Styling */
.quick-actions-card .border-top {
    border-color: rgba(0, 0, 0, 0.1) !important;
}

.quick-actions-card .btn-outline-success.w-100 {
    padding: 0.75rem 1rem;
    font-weight: 500;
}

.quick-actions-card .btn-outline-success.w-100:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.25);
}

/* Enhanced Comments System Styles */
.mention-suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
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

.comments-list {
    max-height: 500px;
    overflow-y: auto;
}

.comment-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
    transition: all 0.3s ease;
}

.comment-item:last-child {
    border-bottom: none;
}

.comment-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
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

.comment-user-name {
    font-weight: 600;
    margin: 0;
    font-size: 13px;
    color: #495057;
}

.comment-timestamp {
    color: #6c757d;
    font-size: 11px;
    margin: 0;
}

.comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 8px;
    object-fit: cover;
}

.comment-content {
    margin-bottom: 8px;
    line-height: 1.4;
    font-size: 14px;
    color: #374151;
}

.comment-mention {
    color: #0d6efd;
    font-weight: 500;
    text-decoration: none;
    background-color: rgba(13, 110, 253, 0.1);
    padding: 1px 3px;
    border-radius: 3px;
}

.comment-mention:hover {
    text-decoration: underline;
    background-color: rgba(13, 110, 253, 0.2);
}

.empty-comments {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.empty-comments i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Internal Notes System Styles */
.nav-tabs-bordered {
    border-bottom: 2px solid #e5e7eb;
}

.nav-tabs-bordered .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-weight: 500;
    padding: 0.75rem 1rem;
}

.nav-tabs-bordered .nav-link:hover {
    border-color: transparent;
    color: #374151;
    background-color: #f9fafb;
}

.nav-tabs-bordered .nav-link.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
    background-color: transparent;
}

/* Comments List Container with Scroll */
#commentsList {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

#commentsList::-webkit-scrollbar {
    width: 6px;
}

#commentsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#commentsList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#commentsList::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Comment Attachments Styles */
.comment-attachments {
    margin-top: 8px;
}

.comment-attachments-title {
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 6px;
    color: #495057;
}

.attachment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 8px;
}

.attachment-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
    transition: transform 0.2s;
}

.attachment-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.attachment-image,
.attachment-video {
    width: 100%;
    height: 60px;
    object-fit: cover;
    cursor: pointer;
}

.attachment-document {
    padding: 12px;
    text-align: center;
    height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    cursor: pointer;
}

.attachment-document i {
    font-size: 20px;
    color: #6c757d;
    margin-bottom: 2px;
}

.attachment-name {
    font-size: 11px;
    color: #495057;
    margin: 0;
    word-break: break-word;
}

.attachment-thumbnail {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
}

.file-thumbnail-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.25rem;
}

.file-thumbnail-icon {
    font-size: 1.25rem;
}

.file-thumbnail-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.attachment-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.attachment-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.attachment-name {
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    font-size: 0.875rem;
}

.attachment-size {
    font-size: 0.75rem;
    color: #6c757d;
    white-space: nowrap;
}

.attachment-actions {
    display: flex;
    gap: 0.25rem;
    flex-shrink: 0;
}

.attachment-actions .btn {
    padding: 0.375rem 0.5rem;
    line-height: 1;
    border-radius: 0.375rem;
}

.attachment-actions .icon-xs {
    width: 14px;
    height: 14px;
}

/* File type specific colors */
.text-purple {
    color: #6f42c1 !important;
}

/* Attachment Modal Styling */
.attachment-modal-image {
    max-width: 90vw;
    max-height: 80vh;
    object-fit: contain;
    padding: 15px 12px;
    margin: 0 -12px;
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
    transition: all 0.3s ease;
}

.comment-reply:hover {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 6px 8px;
    margin: 8px -8px;
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

.reply-actions .btn {
    padding: 2px 6px;
    font-size: 11px;
    border-radius: 3px;
}

.reply-form {
    margin-left: 28px;
    margin-top: 8px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.reply-form .reply-avatar {
    width: 24px;
    height: 24px;
}

.reply-edit-form {
    margin-top: 6px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 12px;
    margin-right: 10px;
}

.comment-author {
    font-weight: 600;
    color: #333;
    margin-right: 8px;
}

.comment-date {
    color: #666;
    font-size: 0.875rem;
}

.comment-content {
    color: #374151;
    line-height: 1.5;
    margin-bottom: 8px;
}

.comment-mention {
    color: #3b82f6;
    font-weight: 600;
    text-decoration: none;
    background-color: rgba(59, 130, 246, 0.1);
    padding: 1px 4px;
    border-radius: 3px;
}

.comment-mention:hover {
    color: #2563eb;
    background-color: rgba(59, 130, 246, 0.2);
}

/* Animation for new comments/replies */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

.slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive Design for Comments */
@media (max-width: 768px) {
    .comment-item {
        padding: 12px;
    }
    
    .comment-avatar {
        width: 32px;
        height: 32px;
    }
    
    .attachment-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 8px;
    }
    
    .comment-actions {
        opacity: 1;
    }
    
    #commentsList {
        max-height: 400px;
    }
}

/* QR Code Styles */
.qr-sidebar-image {
    transition: transform 0.3s ease;
}

.qr-sidebar-image:hover {
    transform: scale(1.05);
}

/* Quick Actions Card */
.quick-actions-card {
    position: sticky;
    top: 20px;
}

/* Followers Card */
.followers-card {
    position: sticky;
    top: 20px;
}

/* Activity item styling */
.activity-item {
    border-left: 4px solid #e5e7eb;
    padding: 1rem;
    margin-bottom: 1rem;
    background: #ffffff;
    border-radius: 0 8px 8px 0;
    transition: all 0.3s ease;
    border: 1px solid #f1f5f9;
}

.activity-item:hover {
    border-left-color: #3b82f6;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transform: translateX(2px);
}

.activity-item.activity-status {
    border-left-color: #10b981;
}

.activity-item.activity-note {
    border-left-color: #f59e0b;
}

.activity-item.activity-comment {
    border-left-color: #6366f1;
}

.activity-item.activity-edit {
    border-left-color: #8b5cf6;
}

.activity-item.activity-created {
    border-left-color: #059669;
}

.activity-item.activity-updated {
    border-left-color: #0ea5e9;
}

.activity-item.activity-deleted {
    border-left-color: #dc2626;
}

.activity-item.activity-picture {
    border-left-color: #f97316;
}

/* Activity list container with scroll */
#activityList {
    max-height: 500px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 0.5rem;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

#activityList::-webkit-scrollbar {
    width: 6px;
}

#activityList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#activityList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#activityList::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Activity preview styling */
.activity-preview {
    border-left: 2px solid #e5e7eb;
    margin-top: 0.5rem;
    font-style: italic;
}

.activity-preview-content {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

/* Activity changes styling */
.activity-changes {
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

/* Activity value tooltip styling */
.activity-value-tooltip {
    cursor: help;
    transition: all 0.2s ease;
}

.activity-value-tooltip:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Activity description tooltip styling */
.activity-description-tooltip {
    cursor: help;
    transition: all 0.2s ease;
    text-decoration: underline;
    text-decoration-style: dotted;
    text-underline-offset: 2px;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
    max-width: 100%;
    display: inline-block;
}

.activity-description-tooltip:hover {
    text-decoration-style: solid;
    color: #0d6efd;
}

/* Activity description general styling to prevent horizontal scroll */
.activity-description {
    color: #4b5563;
    font-size: 0.875rem;
    line-height: 1.4;
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
    max-width: 100%;
    overflow: hidden;
}

/* Activity item container to prevent overflow */
.activity-item {
    overflow: hidden;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.activity-item .flex-grow-1 {
    min-width: 0; /* This is crucial for flex items to shrink properly */
    max-width: 100%;
    overflow: hidden;
}

.change-item {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.change-item:last-child {
    margin-bottom: 0;
}

.activity-title-section h6 {
    color: #1f2937;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.75rem;
    white-space: nowrap;
}

.activity-meta {
    font-size: 0.75rem;
    color: #6b7280;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

/* Mention suggestions dropdown */
.mention-suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
}

.mention-suggestions-dropdown .mention-item {
    padding: 0.5rem;
    cursor: pointer;
    border-bottom: 1px solid #f3f4f6;
}

.mention-suggestions-dropdown .mention-item:hover,
.mention-suggestions-dropdown .mention-item.active {
    background-color: #f3f4f6;
}

.mention-suggestions-dropdown .mention-item:last-child {
    border-bottom: none;
}

/* Follower item styling */
.follower-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    background: #ffffff;
    transition: all 0.3s ease;
}

.follower-item:hover {
    border-color: #d1d5db;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.follower-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
}

.follower-info {
    flex: 1;
}

.follower-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.125rem;
}

.follower-type {
    font-size: 0.75rem;
    color: #6b7280;
}

.follower-actions {
    display: flex;
    gap: 0.25rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (isset($order) && !empty($order)): ?>
<div class="container-fluid">

<!-- Enhanced Top Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="order-top-bar">
            <div class="row g-0">
                <!-- Order Number -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-file-list-3-line text-primary"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Order Number</div>
                            <div class="top-bar-value"><?= $order['order_number'] ?></div>
                            <div class="top-bar-sub"><?= date('M d, Y', strtotime($order['created_at'])) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Client -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-building-line text-info"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Client</div>
                            <div class="top-bar-value"><?= $order['client_name'] ?? 'N/A' ?></div>
                            <div class="top-bar-sub">Assigned Client</div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-car-line text-success"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Vehicle</div>
                            <div class="top-bar-value"><?= $order['vehicle'] ?? 'N/A' ?></div>
                            <div class="top-bar-sub">
                                <span>VIN:</span>
                                <?php if (!empty($order['vin_number'])): ?>
                                    <a href="<?= base_url('vehicles/' . urlencode(substr(strtoupper($order['vin_number']), -6))) ?>" 
                                       class="vin-link ms-1"
                                       title="Click to view vehicle details">
                                        <?= esc($order['vin_number']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted ms-1">N/A</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-archive-line text-warning"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Stock</div>
                            <div class="top-bar-value"><?= $order['stock'] ?? 'N/A' ?></div>
                            <div class="top-bar-sub">Stock Number</div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-flag-line text-danger"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Status</div>
                            <div class="top-bar-value">
                                <?php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'in_progress' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusColor = $statusColors[$order['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $statusColor ?> order-status-badge"><?= ucwords(str_replace('_', ' ', $order['status'])) ?></span>
                            </div>
                            <div class="top-bar-sub">Current Status</div>
                        </div>
                    </div>
                </div>

                <!-- Pictures -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-camera-line text-primary"></i>
                        </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Pictures</div>
                            <div class="top-bar-value">
                                <div class="form-check">
                                    <input class="form-check-input pictures-checkbox" type="checkbox" id="pictures_checkbox" 
                                           <?= $order['pictures'] ? 'checked' : '' ?> 
                                           data-order-id="<?= $order['id'] ?>">
                                    <label class="form-check-label pictures-label fw-bold" for="pictures_checkbox">
                                        <?= $order['pictures'] ? 'Pictures taken' : 'No pictures yet' ?>
                                    </label>
                                </div>
                            </div>
                            <div class="top-bar-sub">Click to toggle</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Left Column - Order Details -->
    <div class="col-xl-8">
        <!-- Order Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-feather="info" class="icon-sm me-2"></i>
                            Order Information
                        </h5>
                        <small class="text-muted">Complete details for this recon order</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm" onclick="editReconOrder(<?= $order['id'] ?>)">
                            <i data-feather="edit" class="icon-xs me-1"></i>
                            Edit Order
                        </button>
                       
                        <button class="btn btn-outline-danger btn-sm" onclick="downloadReconPDF(<?= $order['id'] ?>)">
                            <i data-feather="download" class="icon-xs me-1"></i>
                            Download PDF
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="printReconOrder()">
                            <i data-feather="printer" class="icon-xs me-1"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">Basic Information</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>Order Number:</strong> <?= $order['order_number'] ?></li>
                            <li class="mb-2"><strong>Client:</strong> <?= $order['client_name'] ?? 'Not assigned' ?></li>
                            <li class="mb-2"><strong>Service:</strong> <?= $order['service_name'] ?? 'Not assigned' ?></li>
                            <li class="mb-2"><strong>Vehicle:</strong> <?= $order['vehicle'] ?? 'Not provided' ?></li>
                            <li class="mb-2"><strong>Stock:</strong> <?= $order['stock'] ?? 'Not provided' ?></li>
                            <li class="mb-2">
                                <strong>VIN:</strong>
                                <?php if (!empty($order['vin_number'])): ?>
                                    <a href="<?= base_url('vehicles/' . urlencode(substr(strtoupper($order['vin_number']), -6))) ?>" 
                                       class="vin-link ms-1"
                                       title="Click to view vehicle details in Vehicles module">
                                        <?= esc($order['vin_number']) ?>
                                        <i class="ri-external-link-line ms-1 fs-6"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted ms-1">Not provided</span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-primary mb-3">Status & Timeline</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Status:</strong> 
                                <span class="badge bg-<?= $statusColor ?> ms-1">
                                    <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                                </span>
                            </li>
                            <li class="mb-2">
                                <strong>Pictures:</strong> 
                                <span class="badge bg-<?= $order['pictures'] ? 'success' : 'secondary' ?> ms-1">
                                    <?= $order['pictures'] ? 'Taken' : 'Pending' ?>
                                </span>
                            </li>
                            <li class="mb-2"><strong>Created:</strong> <?= date('M d, Y g:i A', strtotime($order['created_at'])) ?></li>
                            <li class="mb-2"><strong>Updated:</strong> <?= $order['updated_at'] ? date('M d, Y g:i A', strtotime($order['updated_at'])) : 'Never' ?></li>
                            <li class="mb-2"><strong>Service Date:</strong> <?= $order['service_date'] ? date('M d, Y', strtotime($order['service_date'])) : 'Not scheduled' ?></li>
                        </ul>
                    </div>
                </div>
                
                <?php if (!empty($order['notes'])): ?>
                <div class="mt-4">
                    <h6 class="fw-bold text-primary">Order Notes</h6>
                    <div class="bg-light p-3 rounded">
                        <?= nl2br(esc($order['notes'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Enhanced Comments Section with Attachments and Mentions -->
        <div class="card" id="comments">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="message-circle" class="icon-sm me-2"></i>
                    Comments & Attachments
                    <span id="commentsCount" class="badge bg-primary ms-1">0</span>
                </h5>
                <button class="btn btn-sm btn-outline-primary" onclick="loadComments()">
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
                            <input type="file" id="commentAttachments" name="attachments[]" multiple class="form-control form-control-sm d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.txt">
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
                    <div id="attachmentPreview" class="mt-2" style="display: none;">
                        <small class="text-muted">Selected files:</small>
                        <div id="attachmentList" class="mt-1"></div>
                    </div>
                </form>

                <!-- Comments List -->
                <div id="commentsList">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Loading comments...</p>
                    </div>
                </div>

                <!-- Load More Button -->
                <div id="loadMoreComments" class="text-center mt-3" style="display: none;">
                    <button class="btn btn-outline-primary btn-sm" onclick="loadMoreComments()">
                        <i data-feather="chevron-down" class="icon-xs me-1"></i>
                        Load More Comments
                    </button>
                </div>
            </div>
        </div>

        <!-- Internal Communication Section - Staff and Admin Only -->
        <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
        <div class="card mt-4" id="internal-notes-card">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="users" class="icon-sm me-2"></i>
                    Internal Notes
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
                            Note Mentions
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
                                        <textarea class="form-control" id="noteContent" rows="3" placeholder="Add internal note... Use @username to mention staff members" required></textarea>
                                        <div id="noteMentionSuggestions" class="mention-suggestions-dropdown" style="display: none;"></div>
                                    </div>
                                    <div class="form-text">
                                        <i data-feather="info" class="icon-xs me-1"></i>
                                        Type @ followed by username to mention staff members. Supports file attachments.
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="file" id="noteAttachments" multiple class="form-control form-control-sm d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('noteAttachments').click()">
                                            <i data-feather="paperclip" class="icon-xs me-1"></i>
                                            Attach Files
                                        </button>
                                        <span id="noteAttachmentCount" class="text-muted small ms-2"></span>
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
        <?php endif; ?>
    </div>

    <!-- Right Column - Sidebar -->
    <div class="col-xl-4">
        <!-- QR Code Card -->
        <?php if (isset($qr_data) && $qr_data): ?>
        <div class="card mb-4 d-none d-md-block">
                <div class="card-header">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code Access
                </h5>
                <small class="text-muted">Instant mobile access</small>
            </div>
            <div class="card-body text-center">
                <!-- Large QR Code Display -->
                <div class="qr-large-display">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Order RO-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
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
                    <h6 class="text-warning">QR Code Unavailable</h6>
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
        <div class="card quick-actions-card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="zap" class="icon-sm me-2"></i>
                    Quick Actions
                </h5>
                <small class="text-muted">Actions for this order</small>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <!-- Update Status - Only for Staff and Admin Users -->
                    <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                    <div>
                        <label class="form-label fw-medium">Update Status</label>
                        <select class="form-select" id="statusSelect" onchange="updateStatus()">
                            <option value="pending" <?= ($order['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                            <option value="in_progress" <?= ($order['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>🔄 In Progress</option>
                            <option value="completed" <?= ($order['status'] ?? '') == 'completed' ? 'selected' : '' ?>>✅ Completed</option>
                            <option value="cancelled" <?= ($order['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                        </select>
                    </div>

                    <!-- Edit Order Button -->
                    <button class="btn btn-outline-primary" onclick="editOrder(<?= $order['id'] ?>)">
                        <i data-feather="edit" class="icon-xs me-1"></i>
                        Edit Order
                    </button>

                    <!-- Delete Order Button -->
                    <button class="btn btn-outline-danger" onclick="deleteOrder(<?= $order['id'] ?>)">
                        <i data-feather="trash-2" class="icon-xs me-1"></i>
                        Delete Order
                    </button>

                    <!-- Regenerate QR Button -->
                    <?php if (isset($qr_data) && $qr_data): ?>
                    <button class="btn btn-outline-info" onclick="regenerateQR(<?= $order['id'] ?>)">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        Regenerate QR Code
                    </button>
                    <?php endif; ?>
                    <?php else: ?>
                    <!-- Status Display Only for Non-Staff Users -->
                    <div>
                        <label class="form-label fw-medium">Current Status</label>
                        <div class="form-control-plaintext">
                            <span class="badge bg-<?= $statusColor ?>"><?= ucwords(str_replace('_', ' ', $order['status'])) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- View Vehicle Button - Available for All Users -->
                    <?php if (!empty($order['vin_number'])): ?>
                    <div class="mt-3 pt-3 border-top">
                        <a href="<?= base_url('vehicles/' . urlencode(substr(strtoupper($order['vin_number']), -6))) ?>" 
                           class="btn btn-outline-success text-decoration-none w-100"
                           title="View vehicle details and service history">
                            <i data-feather="truck" class="icon-xs me-1"></i>
                            View Vehicle Details
                            <small class="d-block mt-1 opacity-75" style="font-size: 0.7rem;">
                                VIN: <?= esc($order['vin_number']) ?>
                            </small>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="activity" class="icon-sm me-2"></i>
                    Recent Activity
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i data-feather="alert-circle" class="icon-lg text-warning mb-3"></i>
                <h4>Recon Order Not Found</h4>
                <p class="text-muted">The requested recon order could not be found or you don't have permission to view it.</p>
                <a href="<?= base_url('recon_orders') ?>" class="btn btn-primary">
                    <i data-feather="arrow-left" class="icon-sm me-1"></i>
                    Back to Recon Orders
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code - Recon Order RO-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (isset($qr_data) && $qr_data): ?>
                <!-- QR Code Available -->
                <div class="mb-4">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Recon Order RO-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
                         class="img-fluid" 
                         style="max-width: 300px; border-radius: 12px;">
                </div>
                
                <!-- Short URL Display -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Short URL:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="qrShortUrl" value="<?= $qr_data['short_url'] ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('qrShortUrl')">
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
                    <p class="text-muted small"><?= lang('App.lima_links_not_configured') ?></p>
                </div>
                <?php endif; ?>
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="followNotifications" checked>
                            <label class="form-check-label" for="followNotifications">
                                Send notifications for updates
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addFollower()">
                    <i data-feather="user-plus" class="icon-xs me-1"></i>
                    Add Follower
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables for order management
const orderId = <?= $order['id'] ?? 0 ?>;
const reconOrderId = orderId;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 ReconOrders View Page - DOMContentLoaded');
    console.log('Order ID:', orderId);

    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // 1. Initialize pictures toggle functionality
    initializePicturesToggle();

    // 2. Load comments (first page)
    if (typeof loadComments === 'function') {
        loadComments();
    }

    // 3. Initialize Internal Notes System for staff and admin users only
    <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
    if (typeof InternalNotesSystem !== 'undefined') {
        console.log('DOMContentLoaded: Initializing Internal Notes System for ReconOrders');
        window.internalNotesSystem = new InternalNotesSystem(orderId);
        console.log('✅ Internal Notes System initialized successfully for ReconOrders');
    } else {
        console.error('❌ InternalNotesSystem class not found');
    }
    <?php endif; ?>

    // 4. Initialize followers system
    if (typeof loadFollowers === 'function') {
        loadFollowers();
    }

    // 5. Load recent activity (first page)  
    if (typeof loadRecentActivity === 'function') {
        loadRecentActivity();
    }

    // 6. Initialize comments form and mentions
    initializeCommentsForm();

    console.log('✅ ReconOrders View Page initialized successfully');
});

// ========================================
// PICTURES TOGGLE FUNCTIONALITY  
// ========================================

function initializePicturesToggle() {
    $('#pictures_checkbox').on('change', function() {
        var isChecked = $(this).is(':checked');
        var orderId = $(this).data('order-id');
        var label = $('.pictures-label');
        
        // Update label text immediately for better UX
        label.text(isChecked ? 'Pictures taken' : 'No pictures yet');
        
        $.ajax({
            url: '<?= base_url('recon_orders/updatePicturesStatus/') ?>' + orderId,
            type: 'POST',
            data: {
                pictures: isChecked ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Pictures status updated successfully');
                    
                    // Auto-refresh activities to show the pictures change activity
                    setTimeout(() => {
                        autoRefreshActivities();
                    }, 1000);
                } else {
                    // Revert checkbox if failed
                    $('#pictures_checkbox').prop('checked', !isChecked);
                    label.text(!isChecked ? 'Pictures taken' : 'No pictures yet');
                    showToast('error', response.message || 'Failed to update pictures status');
                }
            },
            error: function() {
                // Revert checkbox if failed
                $('#pictures_checkbox').prop('checked', !isChecked);
                label.text(!isChecked ? 'Pictures taken' : 'No pictures yet');
                showToast('error', 'An error occurred while updating pictures status');
            }
        });
    });
}

// ========================================
// QR CODE FUNCTIONALITY
// ========================================

function showQRModal() {
    $('#qrModal').modal('show');
}

function generateQRCode(orderId) {
    console.log('🎯 Generating QR Code for recon order:', orderId);
    
    fetch(`<?= base_url('recon_orders/generateQRCode/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'QR Code ready!');
            // Reload page to show QR code
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('warning', 'QR Code not available - MDA Links API not configured');
        }
    });
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    showToast('success', 'Copied to clipboard!');
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

function downloadQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const link = document.createElement('a');
    link.href = '<?= $qr_data['qr_url'] ?>';
    link.download = 'recon-order-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>-qr.png';
    link.click();
    showToast('success', 'QR Code downloaded!');
    <?php else: ?>
    showToast('warning', 'QR Code not available for download');
    <?php endif; ?>
}

function shareQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    if (navigator.share) {
        navigator.share({
            title: 'Recon Order QR Code',
            text: 'Access recon order details',
            url: '<?= $qr_data['short_url'] ?>'
        }).then(() => {
            showToast('success', 'Shared successfully!');
        }).catch(() => {
            // Fallback to clipboard
            navigator.clipboard.writeText('<?= $qr_data['short_url'] ?>');
            showToast('info', 'Link copied to clipboard for sharing');
        });
    } else {
        navigator.clipboard.writeText('<?= $qr_data['short_url'] ?>');
        showToast('info', 'Link copied to clipboard for sharing');
    }
    <?php else: ?>
    showToast('warning', 'QR Code not available for sharing');
    <?php endif; ?>
}

// ========================================
// QUICK ACTIONS FUNCTIONALITY
// ========================================

function updateStatus() {
    const statusSelect = document.getElementById('statusSelect');
    const newStatus = statusSelect.value;
    const orderId = <?= $order['id'] ?? 0 ?>;
    
    if (confirm('Are you sure you want to change the status to "' + newStatus.replace('_', ' ') + '"?')) {
        $.ajax({
            url: '<?= base_url('recon_orders/updateStatus/') ?>' + orderId,
            type: 'POST',
            data: {
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Status updated successfully!');
                    
                    // Auto-refresh activities to show the status change activity
                    setTimeout(() => {
                        autoRefreshActivities();
                    }, 1000);
                    
                    // Reload page to show updated status
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showToast('error', response.message || 'Failed to update status');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while updating status');
            }
        });
    }
}

function editOrder(orderId) {
    // Redirect to edit page or open edit modal
    window.location.href = '<?= base_url('recon_orders/edit/') ?>' + orderId;
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        $.ajax({
            url: '<?= base_url('recon_orders/delete/') ?>' + orderId,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Order deleted successfully');
                    // Redirect to orders list
                    setTimeout(function() {
                        window.location.href = '<?= base_url('recon_orders') ?>';
                    }, 1000);
                } else {
                    showToast('error', response.message || 'Failed to delete order');
                }
            },
            error: function() {
                showToast('error', 'An error occurred while deleting the order');
            }
        });
    }
}

// ========================================
// ORDER ACTION FUNCTIONS
// ========================================

function editReconOrder(orderId) {
    // Redirect to edit page
    window.location.href = '<?= base_url('recon_orders/edit/') ?>' + orderId;
}

function downloadReconPDF(orderId) {
    // Open PDF download in new tab
    window.open('<?= base_url('recon_orders/pdf/') ?>' + orderId, '_blank');
}

function printReconOrder() {
    // Print current page
    window.print();
}

// ========================================
// COMMENTS FORM AND MENTIONS FUNCTIONALITY
// ========================================

// Global variables for mentions
let mentionUsers = [];
let currentMentionQuery = '';
let mentionStartPos = -1;
let selectedSuggestionIndex = -1;

function initializeCommentsForm() {
    console.log('Initializing comments form and mentions...');
    
    // Load users for mentions
    loadMentionUsers();
    
    // Get form elements
    const commentForm = document.getElementById('commentForm');
    const commentText = document.getElementById('commentText');
    const mentionSuggestions = document.getElementById('mentionSuggestions');
    
    if (!commentForm || !commentText) {
        console.error('Comment form elements not found');
        return;
    }
    
    // Handle form submission
    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        submitComment();
    });
    
    // Handle mention input
    commentText.addEventListener('input', handleMentionInput);
    commentText.addEventListener('keydown', handleMentionKeydown);
    
    // Handle attachment selection
    const attachmentInput = document.getElementById('commentAttachments');
    if (attachmentInput) {
        attachmentInput.addEventListener('change', handleAttachmentSelection);
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (mentionSuggestions && !mentionSuggestions.contains(e.target) && e.target !== commentText) {
            hideMentionSuggestions();
        }
    });
    
    console.log('Comments form initialized successfully');
}

function loadMentionUsers() {
    fetch('<?= base_url('recon_orders/getUsersForMentions') ?>', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            mentionUsers = data.data;
            console.log('Loaded mention users:', mentionUsers.length);
        } else {
            console.error('Failed to load mention users:', data.message);
        }
    })
    .catch(error => {
        console.error('Error loading mention users:', error);
    });
}

function handleMentionInput(e) {
    const textarea = e.target;
    const cursorPos = textarea.selectionStart;
    const text = textarea.value;
    
    // Find @ symbol before cursor
    const beforeCursor = text.substring(0, cursorPos);
    const atIndex = beforeCursor.lastIndexOf('@');
    
    if (atIndex !== -1) {
        const afterAt = beforeCursor.substring(atIndex + 1);
        
        // Check if we're still in a mention (no spaces)
        if (!afterAt.includes(' ') && afterAt.length <= 20) {
            currentMentionQuery = afterAt.toLowerCase();
            mentionStartPos = atIndex;
            showMentionSuggestions();
        } else {
            hideMentionSuggestions();
        }
    } else {
        hideMentionSuggestions();
    }
}

function handleMentionKeydown(e) {
    const mentionSuggestions = document.getElementById('mentionSuggestions');
    if (!mentionSuggestions || mentionSuggestions.style.display === 'none') {
        return;
    }
    
    const suggestions = mentionSuggestions.querySelectorAll('.mention-suggestion');
    
    switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, suggestions.length - 1);
            updateSuggestionSelection();
            break;
            
        case 'ArrowUp':
            e.preventDefault();
            selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, 0);
            updateSuggestionSelection();
            break;
            
        case 'Enter':
        case 'Tab':
            e.preventDefault();
            if (selectedSuggestionIndex >= 0 && suggestions[selectedSuggestionIndex]) {
                selectMention(suggestions[selectedSuggestionIndex]);
            }
            break;
            
        case 'Escape':
            hideMentionSuggestions();
            break;
    }
}

function showMentionSuggestions() {
    const filteredUsers = mentionUsers.filter(user => 
        user.username.toLowerCase().includes(currentMentionQuery) ||
        user.name.toLowerCase().includes(currentMentionQuery)
    );
    
    if (filteredUsers.length === 0) {
        hideMentionSuggestions();
        return;
    }
    
    const mentionSuggestions = document.getElementById('mentionSuggestions');
    if (!mentionSuggestions) return;
    
    const suggestionsHtml = filteredUsers.map((user, index) => `
        <div class="mention-suggestion ${index === 0 ? 'active' : ''}" 
             data-user-id="${user.id}" 
             data-username="${user.username}"
             onclick="selectMention(this)">
            <strong>@${user.username}</strong> - ${user.name}
        </div>
    `).join('');
    
    mentionSuggestions.innerHTML = suggestionsHtml;
    mentionSuggestions.style.display = 'block';
    selectedSuggestionIndex = 0;
}

function hideMentionSuggestions() {
    const mentionSuggestions = document.getElementById('mentionSuggestions');
    if (mentionSuggestions) {
        mentionSuggestions.style.display = 'none';
    }
    selectedSuggestionIndex = -1;
}

function updateSuggestionSelection() {
    const suggestions = document.querySelectorAll('.mention-suggestion');
    suggestions.forEach((suggestion, index) => {
        suggestion.classList.toggle('active', index === selectedSuggestionIndex);
    });
}

function selectMention(element) {
    const username = element.getAttribute('data-username');
    const textarea = document.getElementById('commentText');
    
    if (mentionStartPos >= 0) {
        const text = textarea.value;
        const beforeMention = text.substring(0, mentionStartPos);
        const afterMention = text.substring(textarea.selectionStart);
        
        const newText = beforeMention + '@' + username + ' ' + afterMention;
        textarea.value = newText;
        
        // Set cursor position after the mention
        const newCursorPos = mentionStartPos + username.length + 2;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
        textarea.focus();
    }
    
    hideMentionSuggestions();
}

function handleAttachmentSelection(e) {
    const files = e.target.files;
    const attachmentCount = document.getElementById('attachmentCount');
    const attachmentPreview = document.getElementById('attachmentPreview');
    const attachmentList = document.getElementById('attachmentList');
    
    if (files.length > 0) {
        attachmentCount.textContent = `${files.length} file(s) selected`;
        attachmentPreview.style.display = 'block';
        
        let html = '';
        Array.from(files).forEach((file, index) => {
            html += `<span class="badge bg-secondary me-1">${file.name}</span>`;
        });
        attachmentList.innerHTML = html;
    } else {
        attachmentCount.textContent = '';
        attachmentPreview.style.display = 'none';
    }
}

function submitComment() {
    const commentText = document.getElementById('commentText');
    const comment = commentText.value.trim();
    
    if (!comment) {
        showToast('warning', 'Please enter a comment');
        return;
    }
    
    const submitBtn = document.querySelector('#commentForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Submitting...';
    
    // Prepare form data
    const formData = new FormData();
    formData.append('comment', comment);
    
    // Add attachments if any
    const attachmentInput = document.getElementById('commentAttachments');
    if (attachmentInput.files.length > 0) {
        Array.from(attachmentInput.files).forEach(file => {
            formData.append('attachments[]', file);
        });
    }
    
    fetch(`<?= base_url('recon_orders/addComment/') ?>${reconOrderId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Comment added successfully');
            
            // Clear form
            commentText.value = '';
            attachmentInput.value = '';
            handleAttachmentSelection({target: {files: []}});
            
            // Reload comments to show the new one
            setTimeout(() => {
                loadComments();
            }, 500);
            
            // Auto-refresh activities to show the new comment activity
            setTimeout(() => {
                autoRefreshActivities();
            }, 1000);
        } else {
            showToast('error', data.message || 'Failed to add comment');
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
        showToast('error', 'Error submitting comment');
    })
    .finally(() => {
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// ========================================
// COMMENTS SYSTEM
// ========================================

// Global variables for comments system
let commentsState = {
    currentPage: 1,
    hasMore: true,
    isLoading: false,
    totalComments: 0,
    loadedComments: [],
    perPage: 10
};

function loadComments(page = 1, append = false) {
    // Prevent multiple simultaneous loads
    if (commentsState.isLoading) {
        console.log('Comments already loading, skipping request');
        return;
    }

    // Reset state for fresh load
    if (!append) {
        commentsState.currentPage = 1;
        commentsState.hasMore = true;
        commentsState.loadedComments = [];
        commentsState.totalComments = 0;
    }

    commentsState.isLoading = true;

    console.log('Loading comments:', {
        page: page,
        append: append,
        orderId: reconOrderId,
        currentState: commentsState
    });

    fetch(`<?= base_url('recon_orders/getComments/') ?>${reconOrderId}?page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.status === 302) {
            console.error('❌ getComments: Authentication required - redirected to login');
            throw new Error('Authentication required');
        }
        
        if (!response.ok) {
            console.error('❌ getComments: HTTP error', response.status, response.statusText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('❌ getComments: Invalid JSON response:', e);
                console.error('❌ getComments: Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        console.log('Comments response:', data);

        if (data.success && data.comments !== undefined) {
            // Update pagination state
            commentsState.hasMore = data.pagination.has_more;
            commentsState.totalComments = data.pagination.total;
            commentsState.currentPage = data.pagination.current_page;

            // Process new comments
            const newComments = data.comments || [];
            console.log(`Received ${newComments.length} comments for page ${page}`);

            if (append) {
                // For append mode, only add truly new comments
                const existingIds = new Set(commentsState.loadedComments.map(c => c.id));
                const uniqueNewComments = newComments.filter(c => !existingIds.has(c.id));
                
                console.log(`Adding ${uniqueNewComments.length} new comments to existing ${commentsState.loadedComments.length}`);

                if (uniqueNewComments.length > 0) {
                    commentsState.loadedComments.push(...uniqueNewComments);
                    displayComments(uniqueNewComments, true);
                } else {
                    console.log('No new comments to add');
                }
            } else {
                // Fresh load - replace all comments
                commentsState.loadedComments = newComments;
                displayComments(newComments, false);
            }

            // Update UI state
            updateCommentsUI();

            // Update comments count badge
            updateCommentsCount(commentsState.totalComments);
        } else {
            console.error('Failed to load comments:', data.message);
            showError('Failed to load comments: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
        showError('Error loading comments: ' + error.message);
    })
    .finally(() => {
        commentsState.isLoading = false;
        hideCommentsLoadingIndicator();
    });
}

// Update comments UI based on current state
function updateCommentsUI() {
    removeLoadMoreCommentsButton();
    
    if (commentsState.hasMore && commentsState.loadedComments.length > 0) {
        showLoadMoreCommentsButton();
    }
}

function displayComments(comments, append = false) {
    const container = $('#commentsList');
    
    if (!append) {
        container.empty();
        removeLoadMoreCommentsButton();
    } else {
        // Remove existing load more button for append mode
        removeLoadMoreCommentsButton();
        hideCommentsLoadingIndicator();
    }

    if (comments.length === 0 && !append) {
        container.html(`
            <div class="text-center py-4 text-muted">
                <i data-feather="message-circle" class="icon-lg mb-3"></i>
                <p>No comments yet. Be the first to add a comment!</p>
            </div>
        `);
        feather.replace();
        return;
    }

    if (append) {
        // Get existing comment IDs to avoid duplicates
        const existingIds = new Set();
        container.find('.comment-item').each(function() {
            existingIds.add(parseInt($(this).data('comment-id')));
        });
        
        // Only add comments that don't already exist
        const newComments = comments.filter(comment => !existingIds.has(comment.id));
        console.log('Appending new comments:', newComments.length, 'out of', comments.length, 'total');
        
        if (newComments.length === 0) {
            console.log('No new comments to append - all comments already exist');
            return;
        }
        
        newComments.forEach(comment => {
            const commentHtml = createCommentHtml(comment);
            container.append(commentHtml);
        });
    } else {
        // Fresh load - display all comments
        console.log('Fresh load - displaying', comments.length, 'comments');
        comments.forEach(comment => {
            const commentHtml = createCommentHtml(comment);
            container.append(commentHtml);
        });
    }
    
    feather.replace();
}

function createRepliesHtml(replies) {
    if (!replies || replies.length === 0) return '';
    
    return replies.map(reply => {
        const currentUserId = <?= auth()->id() ?? 0 ?>;
        const canEdit = currentUserId && (currentUserId == reply.created_by || currentUserId == reply.user_id);
        
        const replyActionButtons = canEdit ? `
            <div class="reply-actions">
                <button type="button" class="btn btn-xs btn-outline-primary" onclick="editReply(${reply.id}, '${escapeForJs(reply.comment || reply.description || '')}')">
                    <i data-feather="edit-2"></i>
                </button>
                <button type="button" class="btn btn-xs btn-outline-danger" onclick="deleteComment(${reply.id})">
                    <i data-feather="trash-2"></i>
                </button>
            </div>
        ` : '';
        
        const processedReply = processCommentMentions(reply.comment || reply.description || '', reply.mentions || []);
        
        return `
            <div class="comment-reply" data-comment-id="${reply.id}">
                <div class="reply-header">
                    <div class="d-flex align-items-center">
                        <img src="${getUserAvatarUrl(reply, 20)}" 
                             alt="${reply.first_name} ${reply.last_name}" 
                             class="reply-avatar">
                        <div class="reply-user-info ms-2">
                            <p class="reply-user-name mb-0">${reply.first_name} ${reply.last_name}</p>
                            <p class="reply-timestamp mb-0" title="${reply.created_at_formatted || reply.created_at}">
                                ${reply.created_at_relative || formatRelativeTime(reply.created_at)}
                            </p>
                        </div>
                    </div>
                    ${replyActionButtons}
                </div>
                <div class="reply-content" data-original-text="${escapeForJs(reply.comment || reply.description || '')}">${processedReply}</div>
            </div>
        `;
    }).join('');
}

function createCommentHtml(comment) {
    const attachmentsHtml = comment.attachments && comment.attachments.length > 0 
        ? createAttachmentsHtml(comment.attachments) 
        : '';

    const processedComment = processCommentMentions(comment.comment || comment.description || '', comment.mentions || []);

    // Check if current user can edit/delete this comment
    const currentUserId = <?= auth()->id() ?? 0 ?>;
    const canEdit = currentUserId && (currentUserId == comment.created_by || currentUserId == comment.user_id);
    
    const actionButtons = `
        <div class="comment-actions">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleReplyForm(${comment.id})">
                <i data-feather="corner-down-right"></i>
            </button>
            ${canEdit ? `
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editComment(${comment.id}, '${escapeForJs(comment.comment || comment.description || '')}')">
                    <i data-feather="edit-2"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})">
                    <i data-feather="trash-2"></i>
                </button>
            ` : ''}
        </div>
    `;

    return `
        <div class="comment-item fade-in" data-comment-id="${comment.id}">
            <div class="comment-header">
                <div class="d-flex align-items-center">
                    <img src="${getUserAvatarUrl(comment, 32)}" 
                         alt="${comment.first_name} ${comment.last_name}" 
                         class="comment-avatar">
                    <div class="comment-user-info ms-2">
                        <p class="comment-user-name mb-0">${comment.first_name} ${comment.last_name}</p>
                        <p class="comment-timestamp mb-0" title="${comment.created_at_formatted || comment.created_at}">
                            ${comment.created_at_relative || formatRelativeTime(comment.created_at)}
                        </p>
                    </div>
                </div>
                ${actionButtons}
            </div>
            <div class="comment-content" data-original-text="${escapeForJs(comment.comment || comment.description || '')}">${processedComment}</div>
            ${attachmentsHtml}
            
            <!-- Reply Form (initially hidden) -->
            <div class="reply-form" id="replyForm_${comment.id}" style="display: none;">
                <div class="d-flex gap-2 mt-3">
                    <img src="${getCurrentUserAvatar()}" alt="You" class="reply-avatar">
                    <div class="flex-grow-1">
                        <textarea class="form-control" id="replyText_${comment.id}" rows="2" placeholder="Write a reply..."></textarea>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-primary" onclick="submitReply(${comment.id})">
                                <i data-feather="send" class="me-1"></i>Reply
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="cancelReply(${comment.id})">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Replies -->
            <div class="comment-replies" id="replies_${comment.id}">
                ${createRepliesHtml(comment.replies || [])}
            </div>
        </div>
    `;
}

function updateCommentsCount(count) {
    const countBadge = document.getElementById('commentsCount');
    if (countBadge) {
        countBadge.textContent = count;
    }
}

function showLoadMoreCommentsButton() {
    const loadMoreContainer = document.getElementById('loadMoreComments');
    if (loadMoreContainer) {
        loadMoreContainer.style.display = 'block';
    }
}

function removeLoadMoreCommentsButton() {
    const loadMoreContainer = document.getElementById('loadMoreComments');
    if (loadMoreContainer) {
        loadMoreContainer.style.display = 'none';
    }
}

function hideCommentsLoadingIndicator() {
    // Remove any loading indicators
    const loadingElements = document.querySelectorAll('#commentsList .spinner-border');
    loadingElements.forEach(el => {
        const parent = el.closest('.text-center');
        if (parent) {
            parent.remove();
        }
    });
}

function loadMoreComments() {
    if (commentsState.hasMore && !commentsState.isLoading) {
        loadComments(commentsState.currentPage + 1, true);
    }
}

// ========================================
// FOLLOWERS FUNCTIONALITY
// ========================================

let followersData = [];

/**
 * Load followers for the current order
 */
function loadFollowers() {
    const orderId = reconOrderId;
    
    fetch(`<?= base_url('recon_orders/getFollowers') ?>/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
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
    
    if (!followers || followers.length === 0) {
        followersList.innerHTML = `
            <div class="text-center py-3">
                <i data-feather="users" class="icon-lg text-muted mb-2"></i>
                <p class="text-muted mb-0">No followers yet</p>
                <small class="text-muted">Add followers to keep them updated on this order</small>
            </div>
        `;
        feather.replace();
        return;
    }

    let html = '';
    followers.forEach(follower => {
        const initials = follower.user_name ? follower.user_name.split(' ').map(n => n[0]).join('').toUpperCase() : 'U';
        const typeColor = follower.type === 'staff' ? 'primary' : 'success';
        const typeName = follower.type === 'staff' ? 'Staff' : 'Client Contact';

        html += `
            <div class="follower-item">
                <div class="follower-avatar">
                    ${follower.avatar ? 
                        `<img src="${follower.avatar}" alt="${follower.user_name}" class="rounded-circle" width="32" height="32">` :
                        initials
                    }
                </div>
                <div class="follower-info">
                    <div class="follower-name">${follower.user_name}</div>
                    <small class="follower-type">
                        <span class="badge bg-${typeColor}">${typeName}</span>
                        ${follower.email ? `• ${follower.email}` : ''}
                    </small>
                </div>
                <div class="follower-actions">
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFollower(${follower.id})" title="Remove follower">
                        <i data-feather="user-minus" class="icon-xs"></i>
                    </button>
                </div>
            </div>
        `;
    });

    followersList.innerHTML = html;
    feather.replace();
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
        countBadge.textContent = count || 0;
    }
}

function showAddFollowerModal() {
    $('#addFollowerModal').modal('show');
    loadAvailableFollowers();
}

/**
 * Load available users for adding as followers
 */
function loadAvailableFollowers() {
    const orderId = reconOrderId;
    const followerUserIdSelect = document.getElementById('followerUserId');
    
    followerUserIdSelect.innerHTML = '<option value="">Loading users...</option>';
    
    fetch(`<?= base_url('recon_orders/getAvailableFollowers') ?>/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            followerUserIdSelect.innerHTML = '<option value="">Select a user...</option>';
            
            if (data.users && data.users.length > 0) {
                data.users.forEach(user => {
                    followerUserIdSelect.innerHTML += `<option value="${user.id}">${user.name} (${user.email})</option>`;
                });
            } else {
                followerUserIdSelect.innerHTML = '<option value="">No available users</option>';
            }
        } else {
            followerUserIdSelect.innerHTML = '<option value="">Error loading users</option>';
        }
    })
    .catch(error => {
        console.error('Error loading available followers:', error);
        followerUserIdSelect.innerHTML = '<option value="">Error loading users</option>';
    });
}

function addFollower() {
    const userId = document.getElementById('followerUserId').value;
    const type = document.getElementById('followerType').value;
    const notifications = document.getElementById('followNotifications').checked;
    
    if (!userId) {
        showToast('warning', 'Please select a user');
        return;
    }
    
    const orderId = reconOrderId;
    
    fetch(`<?= base_url('recon_orders/addFollower') ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            order_id: orderId,
            user_id: userId,
            type: type,
            notifications: notifications
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Follower added successfully');
            $('#addFollowerModal').modal('hide');
            
            // Reset form
            document.getElementById('addFollowerForm').reset();
            document.getElementById('followNotifications').checked = true;
            
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

function removeFollower(followerId) {
    if (!confirm('Are you sure you want to remove this follower?')) {
        return;
    }
    
    fetch(`<?= base_url('recon_orders/removeFollower') ?>/${followerId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message || 'Follower removed successfully');
            // Reload followers
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

// ========================================
// RECENT ACTIVITY FUNCTIONALITY
// ========================================

// Activity pagination state
let activitiesPagination = {
    currentPage: 1,
    hasMore: true,
    loading: false
};

function loadRecentActivity(reset = true) {
    const orderId = reconOrderId;
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
    
    if (activitiesPagination.loading) {
        console.log('Activities already loading, skipping request');
        return;
    }
    
    activitiesPagination.loading = true;
    const page = activitiesPagination.currentPage;
    
    fetch(`<?= base_url('recon_orders/getActivity/') ?>${orderId}?page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.status === 302) {
            console.error('❌ getActivity: Authentication required - redirected to login');
            throw new Error('Authentication required');
        }
        
        if (!response.ok) {
            console.error('❌ getActivity: HTTP error', response.status, response.statusText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('❌ getActivity: Invalid JSON response:', e);
                console.error('Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
    .then(data => {
        console.log('Activities response:', data);
        
        if (data.success && data.activities) {
            if (reset) {
                activityList.innerHTML = '';
            } else {
                removeActivitiesLoader();
            }
            
            // Update pagination state
            if (data.pagination) {
                activitiesPagination.hasMore = data.pagination.has_more;
                activitiesPagination.currentPage = data.pagination.current_page;
            }
            
            if (data.activities.length > 0) {
                // Filter out duplicates if appending
                let activitiesToAdd = data.activities;
                if (!reset) {
                    const existingIds = new Set();
                    activityList.querySelectorAll('.activity-item[data-activity-id]').forEach(item => {
                        existingIds.add(item.getAttribute('data-activity-id'));
                    });
                    activitiesToAdd = data.activities.filter(activity => !existingIds.has(activity.id.toString()));
                }
                
                activitiesToAdd.forEach(activity => {
                    const activityElement = document.createElement('div');
                    activityElement.innerHTML = createEnhancedActivityHtml(activity);
                    activityList.appendChild(activityElement.firstElementChild);
                });
                
                // Re-initialize feather icons for new elements
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                
                // Initialize tooltips for new elements
                initializeActivityTooltips();
                
                // Initialize infinite scroll for activities if not already done
                initializeActivitiesScroll();
                
            } else if (reset) {
                activityList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="activity" class="icon-lg text-muted mb-2"></i>
                        <p class="text-muted mb-0">No activities yet</p>
                        <small class="text-muted">Activities will appear here as actions are taken</small>
                    </div>
                `;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
            
            // Add "Load More" button only if there are more activities and we have activities
            if (data.pagination && data.pagination.has_more && activityList.children.length > 0) {
                addLoadMoreButton(activityList, 'activities');
            }
            
        } else {
            if (reset) {
                activityList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="alert-circle" class="icon-lg text-warning mb-2"></i>
                        <p class="text-muted mb-0">Unable to load activities</p>
                        <small class="text-muted">${data.message || 'Please try again later'}</small>
                    </div>
                `;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error loading activities:', error);
        if (reset) {
            activityList.innerHTML = `
                <div class="text-center py-3">
                    <i data-feather="wifi-off" class="icon-lg text-danger mb-2"></i>
                    <p class="text-danger mb-0">Connection Error</p>
                    <small class="text-muted">${error.message}</small>
                    <br>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadRecentActivity()">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        Try Again
                    </button>
                </div>
            `;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    })
    .finally(() => {
        activitiesPagination.loading = false;
    });
}

function createActivityHtml(activity) {
    const activityType = activity.activity_type || 'general';
    const activityClass = `activity-${activityType}`;
    
    return `
        <div class="activity-item ${activityClass}">
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                    <div class="activity-icon">
                        ${getActivityIcon(activityType)}
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="activity-header d-flex justify-content-between">
                        <h6 class="activity-title mb-1">${activity.title}</h6>
                        <small class="activity-time text-muted">${formatRelativeTime(activity.created_at)}</small>
                    </div>
                    <p class="activity-description mb-1">${activity.description}</p>
                    <div class="activity-meta">
                        <small class="text-muted">
                            <i data-feather="user" class="icon-xs me-1"></i>
                            ${activity.user_name || 'System'}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getActivityIcon(type) {
    const icons = {
        'status': '<i data-feather="flag" class="icon-sm text-success"></i>',
        'note': '<i data-feather="edit-3" class="icon-sm text-warning"></i>',
        'comment': '<i data-feather="message-circle" class="icon-sm text-info"></i>',
        'picture': '<i data-feather="camera" class="icon-sm text-primary"></i>',
        'created': '<i data-feather="plus-circle" class="icon-sm text-success"></i>',
        'updated': '<i data-feather="edit" class="icon-sm text-primary"></i>',
        'deleted': '<i data-feather="trash-2" class="icon-sm text-danger"></i>',
        'edit': '<i data-feather="edit-2" class="icon-sm text-primary"></i>'
    };
    return icons[type] || '<i data-feather="activity" class="icon-sm text-secondary"></i>';
}

// Enhanced activity HTML with preview and old/new value support
function createEnhancedActivityHtml(activity) {
    const activityType = activity.activity_type || 'general';
    const activityClass = `activity-${activityType}`;
    
    // Build preview section if available
    let previewHtml = '';
    if (activity.preview) {
        previewHtml = `
            <div class="activity-preview mt-2 p-2 bg-light rounded">
                <small class="text-muted">Preview:</small>
                <div class="activity-preview-content">"${activity.preview}"</div>
            </div>
        `;
    }
    
    // Build changes section if available
    let changesHtml = '';
    if (activity.has_changes && (activity.old_values_formatted || activity.new_values_formatted)) {
        const oldVals = activity.old_values_formatted || {};
        const newVals = activity.new_values_formatted || {};
        
        changesHtml = `
            <div class="activity-changes mt-2">
                <small class="text-muted d-block mb-1">Changes:</small>
                <div class="changes-content">
        `;
        
        // Show old -> new value changes
        const allKeys = new Set([...Object.keys(oldVals), ...Object.keys(newVals)]);
        allKeys.forEach(key => {
            if (oldVals[key] !== newVals[key]) {
                changesHtml += `
                    <div class="change-item d-flex align-items-center mb-1">
                        <small class="text-muted me-2">${key}:</small>
                        ${formatValueForDisplay(oldVals[key], 'old')}
                        <i data-feather="arrow-right" class="icon-xs mx-1"></i>
                        ${formatValueForDisplay(newVals[key], 'new')}
                    </div>
                `;
            }
        });
        
        changesHtml += `
                </div>
            </div>
        `;
    }
    
    // Format description with tooltip support for truncated content
    let descriptionHtml = '';
    if (activity.description) {
        if (typeof activity.description === 'object' && activity.description.is_truncated) {
            const tooltipContent = escapeForTooltip(activity.description.full);
            descriptionHtml = `<span class="activity-description-tooltip" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    data-bs-html="false" 
                                    title="${tooltipContent}">${activity.description.text}</span>`;
        } else {
            descriptionHtml = activity.description;
        }
    }
    
    return `
        <div class="activity-item ${activityClass} fade-in" data-activity-id="${activity.id}">
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                    <div class="activity-icon">
                        ${getActivityIcon(activityType)}
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="activity-header d-flex justify-content-between align-items-start">
                        <div class="activity-title-section">
                            <h6 class="activity-title mb-1">${activity.title || 'Activity'}</h6>
                            <p class="activity-description mb-1">${descriptionHtml}</p>
                        </div>
                        <small class="activity-time text-muted" title="${activity.created_at_formatted || activity.created_at}">
                            ${activity.created_at_relative || formatRelativeTime(activity.created_at)}
                        </small>
                    </div>
                    
                    ${previewHtml}
                    ${changesHtml}
                    
                    <div class="activity-meta mt-2">
                        <small class="text-muted">
                            <i data-feather="user" class="icon-xs me-1"></i>
                            ${activity.user_name || 'System'}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Helper function to format values for display with tooltip support
function formatValueForDisplay(value, type) {
    if (!value) {
        return '';
    }
    
    const badgeClass = type === 'old' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';
    
    // Check if value is a truncated object
    if (typeof value === 'object' && value.is_truncated) {
        const tooltipContent = escapeForTooltip(value.full);
        return `<span class="badge ${badgeClass} me-1 activity-value-tooltip" 
                      data-bs-toggle="tooltip" 
                      data-bs-placement="top" 
                      data-bs-html="false" 
                      title="${tooltipContent}">${value.truncated}</span>`;
    } else {
        return `<span class="badge ${badgeClass} me-1">${value}</span>`;
    }
}

// Helper function to escape content for tooltip
function escapeForTooltip(text) {
    if (!text) return '';
    return text.replace(/"/g, '&quot;')
               .replace(/'/g, '&#39;')
               .replace(/</g, '&lt;')
               .replace(/>/g, '&gt;');
}

// Initialize tooltips for activity elements
function initializeActivityTooltips() {
    // Initialize Bootstrap tooltips for activity value elements
    const tooltipTriggerList = document.querySelectorAll('.activity-value-tooltip[data-bs-toggle="tooltip"], .activity-description-tooltip[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover focus',
            placement: 'top',
            html: false
        });
    });
}

// Initialize infinite scroll for activities
function initializeActivitiesScroll() {
    const activityContainer = document.getElementById('activityList');
    if (!activityContainer || activityContainer.hasAttribute('data-scroll-initialized')) {
        return;
    }
    
    console.log('Initializing infinite scroll for activities');
    
    let scrollTimeout;
    let lastScrollTop = 0;
    
    activityContainer.addEventListener('scroll', function() {
        const currentScrollTop = activityContainer.scrollTop;
        
        // Only trigger on downward scroll
        if (currentScrollTop <= lastScrollTop) {
            lastScrollTop = currentScrollTop;
            return;
        }

        lastScrollTop = currentScrollTop;
        
        // Debounce scroll events
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Check if we're near the bottom (within 100px)
            const scrollTop = activityContainer.scrollTop;
            const scrollHeight = activityContainer.scrollHeight;
            const clientHeight = activityContainer.clientHeight;
            const nearBottom = scrollTop + clientHeight >= scrollHeight - 100;
            
            console.log('Activity scroll check:', {
                scrollTop,
                scrollHeight,
                clientHeight,
                nearBottom,
                hasMore: activitiesPagination.hasMore,
                loading: activitiesPagination.loading
            });
            
            if (nearBottom && activitiesPagination.hasMore && !activitiesPagination.loading) {
                console.log('Loading more activities via infinite scroll');
                loadMoreActivity();
            }
        }, 200);
    });
    
    activityContainer.setAttribute('data-scroll-initialized', 'true');
}

// Auto-refresh activities function
function autoRefreshActivities() {
    // Only refresh if we're on the first page and not currently loading
    if (activitiesPagination.currentPage === 1 && !activitiesPagination.loading) {
        console.log('Auto-refreshing activities');
        loadRecentActivity(true);
    }
}

function addLoadMoreButton(container, type) {
    const loadMoreBtn = document.createElement('div');
    loadMoreBtn.className = 'text-center mt-3';
    loadMoreBtn.innerHTML = `
        <button class="btn btn-outline-primary btn-sm" onclick="loadMore${type.charAt(0).toUpperCase() + type.slice(1, -1)}()">
            <i data-feather="chevron-down" class="icon-xs me-1"></i>
            Load More ${type.charAt(0).toUpperCase() + type.slice(1)}
        </button>
    `;
    container.appendChild(loadMoreBtn);
    feather.replace();
}

function removeActivitiesLoader() {
    const activityList = document.getElementById('activityList');
    const loadMoreBtns = activityList.querySelectorAll('.text-center');
    loadMoreBtns.forEach(btn => {
        if (btn.textContent.includes('Load More')) {
            btn.remove();
        }
    });
}

function loadMoreActivity() {
    if (activitiesPagination.hasMore && !activitiesPagination.loading) {
        activitiesPagination.currentPage++;
        loadRecentActivity(false);
    }
}

// ========================================
// UTILITY FUNCTIONS
// ========================================

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function formatRelativeTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'just now';
    if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
    if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
    if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + 'd ago';
    
    return date.toLocaleDateString();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function nl2br(text) {
    return text.replace(/\n/g, '<br>');
}

function showSuccess(message) {
    showToast('success', message);
}

function showError(message) {
    showToast('error', message);
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

// ========================================
// COMMENT ACTIONS AND REPLIES SYSTEM
// ========================================

// Helper functions
function escapeForJs(str) {
    if (!str) return '';
    return str.replace(/'/g, '\\\'').replace(/"/g, '\\"').replace(/\r?\n/g, '\\n');
}

function formatRelativeTime(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diffInSeconds = (now - date) / 1000;
    
    if (diffInSeconds < 60) return 'just now';
    if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
    if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
    if (diffInSeconds < 604800) return Math.floor(diffInSeconds / 86400) + 'd ago';
    return date.toLocaleDateString();
}

function getCurrentUserAvatar() {
    // Use the system's avatar helper instead of hardcoded URL
    const currentUser = <?= json_encode([
        'first_name' => auth()->user()->first_name ?? '',
        'last_name' => auth()->user()->last_name ?? '',
        'username' => auth()->user()->username ?? '',
        'avatar' => auth()->user()->avatar ?? '',
        'id' => auth()->id() ?? 0
    ]) ?>;
    
    return getUserAvatarUrl(currentUser, 24);
}

function getUserAvatarUrl(user, size = 32) {
    // Check for uploaded avatar first
    if (user.avatar && user.avatar !== '') {
        return `<?= base_url('assets/images/users/') ?>${user.avatar}`;
    }
    
    // Generate initials-based avatar using the same system as the helpers
    let initials = '';
    
    // Try to get from first_name and last_name
    if (user.first_name && user.last_name) {
        initials = (user.first_name.charAt(0) + user.last_name.charAt(0)).toUpperCase();
    }
    // Try to get from username
    else if (user.username) {
        const parts = user.username.split('_');
        if (parts.length >= 2) {
            initials = (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
        } else {
            initials = user.username.substring(0, 2).toUpperCase();
        }
    } else {
        initials = 'U';
    }
    
    // Generate colors based on user ID for consistency
    const colors = [
        '3498db', '9b59b6', 'e74c3c', 'f39c12', 
        '2ecc71', '1abc9c', 'e67e22', 'f1c40f',
        '8e44ad', 'c0392b', 'd35400', '27ae60'
    ];
    const colorIndex = (user.id || 1) % colors.length;
    const backgroundColor = colors[colorIndex];
    
    // Use UI Avatars service with consistent styling
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&size=${size}&background=${backgroundColor}&color=ffffff&bold=true&format=png`;
}

function getCurrentOrderId() {
    return <?= $order['id'] ?? 0 ?>;
}

function formatFileSize(bytes) {
    if (!bytes || bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}

function getFileIcon(extension) {
    const iconMap = {
        // Images
        'jpg': '<i class="fas fa-image text-success"></i>',
        'jpeg': '<i class="fas fa-image text-success"></i>',
        'png': '<i class="fas fa-image text-success"></i>',
        'gif': '<i class="fas fa-image text-success"></i>',
        'webp': '<i class="fas fa-image text-success"></i>',
        'svg': '<i class="fas fa-image text-success"></i>',
        'bmp': '<i class="fas fa-image text-success"></i>',
        
        // Documents
        'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
        'doc': '<i class="fas fa-file-word text-primary"></i>',
        'docx': '<i class="fas fa-file-word text-primary"></i>',
        'xls': '<i class="fas fa-file-excel text-success"></i>',
        'xlsx': '<i class="fas fa-file-excel text-success"></i>',
        'ppt': '<i class="fas fa-file-powerpoint text-warning"></i>',
        'pptx': '<i class="fas fa-file-powerpoint text-warning"></i>',
        
        // Text files
        'txt': '<i class="fas fa-file-alt text-secondary"></i>',
        'rtf': '<i class="fas fa-file-alt text-secondary"></i>',
        
        // Archives
        'zip': '<i class="fas fa-file-archive text-warning"></i>',
        'rar': '<i class="fas fa-file-archive text-warning"></i>',
        
        // Video
        'mp4': '<i class="fas fa-file-video text-danger"></i>',
        'avi': '<i class="fas fa-file-video text-danger"></i>',
        'mov': '<i class="fas fa-file-video text-danger"></i>',
        
        // Audio
        'mp3': '<i class="fas fa-file-audio text-purple"></i>',
        'wav': '<i class="fas fa-file-audio text-purple"></i>',
    };
    
    return iconMap[extension] || '<i class="fas fa-file text-secondary"></i>';
}

function getFileThumbnail(attachment, extension) {
    const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    
    if (imageTypes.includes(extension)) {
        // For images, show thumbnail if available, otherwise the image itself
        const thumbnailUrl = attachment.thumbnail || `<?= base_url('recon_orders/attachment/') ?>${getCurrentOrderId()}/${attachment.filename}?action=view`;
        return `<img src="${thumbnailUrl}" alt="${attachment.original_name}" class="file-thumbnail-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="file-thumbnail-fallback" style="display: none;">
                    <i class="fas fa-image text-success"></i>
                </div>`;
    } else {
        // For other files, show a file type icon
        return getFileIcon(extension);
    }
}

function createAttachmentsHtml(attachments) {
    if (!attachments || attachments.length === 0) return '';
    
    // Ensure attachments is always an array
    let attachmentsArray = [];
    if (typeof attachments === 'string') {
        try {
            attachmentsArray = JSON.parse(attachments);
        } catch (e) {
            console.warn('Failed to parse attachments JSON:', attachments);
            return '';
        }
    } else if (Array.isArray(attachments)) {
        attachmentsArray = attachments;
    } else {
        console.warn('Unexpected attachments format:', attachments);
        return '';
    }
    
    if (!Array.isArray(attachmentsArray) || attachmentsArray.length === 0) return '';

    const attachmentItems = attachmentsArray.map(attachment => {
        const isImage = attachment.type === 'image';
        const isVideo = attachment.type === 'video';
        const isDocument = attachment.type === 'document';
        const extension = attachment.filename ? attachment.filename.split('.').pop().toLowerCase() : '';
        const viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'txt', 'html', 'htm'];
        const canView = viewableTypes.includes(extension);
        
        // Use the new file serving endpoint
        const viewUrl = `<?= base_url('recon_orders/attachment/') ?>${getCurrentOrderId()}/${attachment.filename}?action=view`;
        const downloadUrl = `<?= base_url('recon_orders/attachment/') ?>${getCurrentOrderId()}/${attachment.filename}?action=download`;
        
        // Get file icon and thumbnail
        const fileIcon = getFileIcon(extension);
        const thumbnail = getFileThumbnail(attachment, extension);

        return `
            <div class="comment-attachment-item">
                <div class="attachment-thumbnail">
                    ${thumbnail}
                </div>
                <div class="attachment-info">
                    <div class="attachment-header">
                        ${fileIcon}
                        <span class="attachment-name" title="${attachment.original_name}">${attachment.original_name}</span>
                    </div>
                    <span class="attachment-size">${formatFileSize(attachment.size)}</span>
                </div>
                <div class="attachment-actions">
                    ${canView ? `<button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="viewAttachment('${viewUrl}')" title="Ver en navegador">
                        <i data-feather="eye" class="icon-xs"></i>
                    </button>` : ''}
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadAttachment('${downloadUrl}', '${attachment.original_name}')" title="Descargar">
                        <i data-feather="download" class="icon-xs"></i>
                    </button>
                </div>
            </div>
        `;
    }).join('');

    return `
        <div class="comment-attachments">
            <div class="comment-attachments-title">
                <i data-feather="paperclip" class="icon-xs me-1"></i>
                Attachments (${attachmentsArray.length})
            </div>
            <div class="comment-attachment-list">
                ${attachmentItems}
            </div>
        </div>
    `;
}

// Process comment mentions
function processCommentMentions(content, mentions) {
    let processedContent = content;
    
    if (mentions && mentions.length > 0) {
        mentions.forEach(mention => {
            const mentionRegex = new RegExp(`@${mention.username}\\b`, 'gi');
            processedContent = processedContent.replace(mentionRegex, 
                `<a href="#" class="comment-mention" data-user-id="${mention.user_id}">@${mention.username}</a>`
            );
        });
    }
    
    return processedContent;
}

// Toggle reply form
window.toggleReplyForm = function(commentId) {
    const replyForm = $(`#replyForm_${commentId}`);
    const isVisible = replyForm.is(':visible');
    
    // Hide all other reply forms
    $('.reply-form').hide();
    
    if (!isVisible) {
        replyForm.show();
        $(`#replyText_${commentId}`).focus();
    }
};

// Cancel reply
window.cancelReply = function(commentId) {
    $(`#replyForm_${commentId}`).hide();
    $(`#replyText_${commentId}`).val('');
};

// Submit reply
window.submitReply = function(commentId) {
    const replyText = $(`#replyText_${commentId}`).val().trim();
    
    if (!replyText) {
        showError('Please enter a reply');
        return;
    }

    const formData = new FormData();
    formData.append('order_id', reconOrderId);
    formData.append('parent_id', commentId);
    formData.append('comment', replyText);

    fetch('<?= base_url('recon_orders/addReply') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add the new reply to the UI
            const repliesContainer = $(`#replies_${commentId}`);
            const newReplyHtml = createRepliesHtml([data.comment]);
            repliesContainer.append(newReplyHtml);
            
            // Hide and reset the reply form
            cancelReply(commentId);
            
            feather.replace();
            showSuccess('Reply added successfully');
            
            // Auto-refresh activities to show the new reply activity
            setTimeout(() => {
                autoRefreshActivities();
            }, 1000);
        } else {
            showError(data.message || 'Failed to add reply');
        }
    })
    .catch(error => {
        console.error('Error adding reply:', error);
        showError('Error adding reply');
    });
};

// Edit comment function
window.editComment = function(commentId, currentText) {
    console.log('Edit comment:', commentId, 'Current text:', currentText);
    
    const commentItem = $(`.comment-item[data-comment-id="${commentId}"]`);
    const contentDiv = commentItem.find('.comment-content');
    
    // Create edit form
    const editForm = `
        <div class="comment-edit-form">
            <textarea class="form-control mb-2" id="editCommentText_${commentId}" rows="3">${currentText}</textarea>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success" onclick="saveComment(${commentId})">
                    <i data-feather="save" class="me-1"></i>Save
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit(${commentId})">
                    <i data-feather="x" class="me-1"></i>Cancel
                </button>
            </div>
        </div>
    `;
    
    // Hide original content and show edit form
    contentDiv.hide();
    contentDiv.after(editForm);
    
    // Hide action buttons
    commentItem.find('.comment-actions').hide();
    
    // Focus on textarea
    $(`#editCommentText_${commentId}`).focus();
    feather.replace();
};

// Save edited comment
window.saveComment = function(commentId) {
    const newText = $(`#editCommentText_${commentId}`).val().trim();
    
    if (!newText) {
        showError('Comment cannot be empty');
        return;
    }

    const formData = new FormData();
    formData.append('comment', newText);

    const updateUrl = `<?= base_url('recon_orders/updateComment/') ?>${commentId}`;
    console.log('Updating comment with URL:', updateUrl);

    fetch(updateUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Update response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Update response data:', data);
        if (data.success) {
            // Update the comment in the UI
            const commentItem = $(`.comment-item[data-comment-id="${commentId}"]`);
            const contentDiv = commentItem.find('.comment-content');
            
            // Update content
            contentDiv.attr('data-original-text', newText);
            contentDiv.html(processCommentMentions(newText, data.comment.mentions || []));
            
            // Remove edit form and show original content
            commentItem.find('.comment-edit-form').remove();
            contentDiv.show();
            commentItem.find('.comment-actions').show();
            
            showSuccess('Comment updated successfully');
            
            // Auto-refresh activities to show the comment update activity
            setTimeout(() => {
                autoRefreshActivities();
            }, 1000);
        } else {
            showError(data.message || 'Failed to update comment');
        }
    })
    .catch(error => {
        console.error('Error updating comment:', error);
        showError('Error updating comment: ' + error.message);
    });
};

// Cancel edit
window.cancelEdit = function(commentId) {
    const commentItem = $(`.comment-item[data-comment-id="${commentId}"]`);
    
    // Remove edit form and show original content
    commentItem.find('.comment-edit-form').remove();
    commentItem.find('.comment-content').show();
    commentItem.find('.comment-actions').show();
};

// Edit reply function
window.editReply = function(replyId, currentText) {
    const replyItem = $(`.comment-reply[data-comment-id="${replyId}"]`);
    const contentDiv = replyItem.find('.reply-content');
    
    // Create edit form
    const editForm = `
        <div class="reply-edit-form">
            <textarea class="form-control mb-2" id="editReplyText_${replyId}" rows="2">${currentText}</textarea>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-success" onclick="saveReply(${replyId})">
                    <i data-feather="save" class="me-1"></i>Save
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="cancelReplyEdit(${replyId})">
                    <i data-feather="x" class="me-1"></i>Cancel
                </button>
            </div>
        </div>
    `;
    
    // Hide original content and show edit form
    contentDiv.hide();
    contentDiv.after(editForm);
    
    // Hide action buttons
    replyItem.find('.reply-actions').hide();
    
    // Focus on textarea
    $(`#editReplyText_${replyId}`).focus();
    feather.replace();
};

// Save edited reply
window.saveReply = function(replyId) {
    const newText = $(`#editReplyText_${replyId}`).val().trim();
    
    if (!newText) {
        showError('Reply cannot be empty');
        return;
    }

    const formData = new FormData();
    formData.append('comment', newText);

    const updateUrl = `<?= base_url('recon_orders/updateComment/') ?>${replyId}`;
    console.log('Updating reply with URL:', updateUrl);

    fetch(updateUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Update reply response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Update reply response data:', data);
        if (data.success) {
            // Update the reply in the UI
            const replyItem = $(`.comment-reply[data-comment-id="${replyId}"]`);
            const contentDiv = replyItem.find('.reply-content');
            
            // Update content
            contentDiv.attr('data-original-text', newText);
            contentDiv.html(processCommentMentions(newText, data.comment.mentions || []));
            
            // Remove edit form and show original content
            replyItem.find('.reply-edit-form').remove();
            contentDiv.show();
            replyItem.find('.reply-actions').show();
            
            showSuccess('Reply updated successfully');
        } else {
            showError(data.message || 'Failed to update reply');
        }
    })
    .catch(error => {
        console.error('Error updating reply:', error);
        showError('Error updating reply: ' + error.message);
    });
};

// Cancel reply edit
window.cancelReplyEdit = function(replyId) {
    const replyItem = $(`.comment-reply[data-comment-id="${replyId}"]`);
    
    // Remove edit form and show original content
    replyItem.find('.reply-edit-form').remove();
    replyItem.find('.reply-content').show();
    replyItem.find('.reply-actions').show();
};

// Delete comment function
window.deleteComment = function(commentId) {
    Swal.fire({
        title: 'Delete Comment?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const deleteUrl = `<?= base_url('recon_orders/deleteComment/') ?>${commentId}`;
            console.log('Deleting comment with URL:', deleteUrl);

            fetch(deleteUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Delete response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete response data:', data);
                if (data.success) {
                    // Remove comment from UI with animation
                    const commentItem = $(`.comment-item[data-comment-id="${commentId}"], .comment-reply[data-comment-id="${commentId}"]`);
                    commentItem.fadeOut(300, function() {
                        $(this).remove();
                    });
                    
                    // Update comments count (decrease by 1)
                    const currentCount = parseInt(document.getElementById('commentsCount').textContent);
                    updateCommentsCount(Math.max(0, currentCount - 1));
                    
                    showSuccess('Comment deleted successfully');
                    
                    // Auto-refresh activities to show the comment deletion activity
                    setTimeout(() => {
                        autoRefreshActivities();
                    }, 1000);
                } else {
                    showError(data.message || 'Failed to delete comment');
                }
            })
            .catch(error => {
                console.error('Error deleting comment:', error);
                showError('Error deleting comment: ' + error.message);
            });
        }
    });
};

// Attachment functions
window.viewAttachment = function(url) {
    // Check if it's an image by URL or extension
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    const extension = url.split('.').pop().toLowerCase().split('?')[0];
    
    if (imageExtensions.includes(extension)) {
        // Show image in modal
        Swal.fire({
            imageUrl: url,
            imageAlt: 'Attachment',
            showConfirmButton: false,
            showCloseButton: true,
            width: 'auto',
            padding: '1rem',
            customClass: {
                image: 'attachment-modal-image'
            }
        });
    } else {
        // Open in new tab for other file types
        window.open(url, '_blank');
    }
};

window.downloadAttachment = function(url, filename) {
    // Create a temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    if (filename) {
        link.download = filename;
    }
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// ========================================
// INTERNAL NOTES SYSTEM
// ========================================

// Internal Notes System Class
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
        
        console.log('🔧 Creating new Internal Notes instance for ReconOrder:', orderId);
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
        
        console.log('✅ Internal Notes System initialized for ReconOrder:', orderId);
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
        this.currentUser = {
            id: <?= auth()->user()->id ?? 0 ?>,
            username: '<?= auth()->user()->username ?? 'user' ?>',
            name: '<?= auth()->user()->first_name ?? 'User' ?> <?= auth()->user()->last_name ?? '' ?>',
            avatar: '<?= getAvatarUrl(auth()->user()) ?>'
        };
    }
    
    async loadStaffUsers() {
        try {
            const response = await fetch(`<?= base_url() ?>/internal-notes/staff-users`);
            const result = await response.json();
            
            if (result.success) {
                this.staffUsers = result.data;
                this.populateAuthorFilter();
            }
        } catch (error) {
            console.error('Error loading staff users:', error);
        }
    }
    
    bindEvents() {
        // Note form submission
        const noteForm = document.getElementById('noteForm');
        if (noteForm) {
            noteForm.addEventListener('submit', (e) => this.handleNoteSubmit(e));
        }
        
        // Mention functionality
        const noteContent = document.getElementById('noteContent');
        if (noteContent) {
            noteContent.addEventListener('input', (e) => this.handleMentionTyping(e));
            noteContent.addEventListener('keydown', (e) => this.handleMentionNavigation(e));
        }
        
        // Filter events
        const notesSearch = document.getElementById('notesSearch');
        if (notesSearch) {
            notesSearch.addEventListener('input', () => this.filterNotes());
        }
        
        const notesAuthorFilter = document.getElementById('notesAuthorFilter');
        if (notesAuthorFilter) {
            notesAuthorFilter.addEventListener('change', () => this.filterNotes());
        }
    }
    
    async handleNoteSubmit(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (this.submittingNote) {
            console.log('Already submitting, skipping duplicate submission');
            return;
        }
        
        const content = document.getElementById('noteContent').value.trim();
        const attachments = document.getElementById('noteAttachments').files;
        
        if (!content) {
            this.showAlert('Note content is required', 'warning');
            return;
        }
        
        const formData = new FormData();
        formData.append('order_id', this.orderId);
        formData.append('content', content);
        
        // Add attachments
        for (let i = 0; i < attachments.length; i++) {
            formData.append('attachments[]', attachments[i]);
        }
        
        const form = e.target;
        let submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton ? submitButton.innerHTML : '';
        
        // Set submitting flag
        this.submittingNote = true;
        
        try {
            if (submitButton) {
                submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i> Adding Note...';
                submitButton.disabled = true;
            }
            
            const response = await fetch(`<?= base_url('internal-notes/create') ?>`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('Note added successfully, reloading notes');
                this.showAlert('Note added successfully', 'success');
                this.clearNoteForm();
                
                // Reload notes
                this.loadNotes(true); // true = reset mode to avoid duplicates
                
                // Refresh recent activities if function exists
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(result.message || 'Failed to add note');
            }
        } catch (error) {
            console.error('Error adding note:', error);
            this.showAlert('Error adding note: ' + error.message, 'error');
        } finally {
            // Reset submitting flag
            this.submittingNote = false;
            
            if (submitButton) {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        }
    }
    
    async loadNotes(reset = true) {
        if (this.isDestroyed || this.loadingNotes) {
            return;
        }
        
        this.loadingNotes = true;
        
        try {
            // Reset pagination if this is a fresh load
            if (reset) {
                this.currentPage = 1;
                this.hasMore = true;
                this.loadedNotes = [];
            }
            
            const searchQuery = document.getElementById('notesSearch')?.value || '';
            const authorFilter = document.getElementById('notesAuthorFilter')?.value || '';
            
            const params = new URLSearchParams({
                search: searchQuery,
                author: authorFilter,
                page: this.currentPage,
                limit: 5 // Load 5 notes per page
            });
            
            const response = await fetch(`<?= base_url('internal-notes/order/') ?>${this.orderId}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Update pagination info
                if (result.pagination) {
                    this.hasMore = result.pagination.has_more;
                    this.totalNotes = result.pagination.total_notes;
                }
                
                // Filter unique notes before adding to loaded notes
                const uniqueNotes = [];
                const seenIds = new Set(this.loadedNotes.map(note => note.id));
                
                for (const note of result.data) {
                    if (!seenIds.has(note.id)) {
                        uniqueNotes.push(note);
                    }
                }
                
                if (reset) {
                    this.loadedNotes = uniqueNotes;
                } else {
                    this.loadedNotes = [...this.loadedNotes, ...uniqueNotes];
                }
                
                this.displayNotes(this.loadedNotes, reset);
                this.updateNotesCount(this.totalNotes);
                
                // Add load more button if there are more notes
                if (this.hasMore && this.loadedNotes.length > 0) {
                    this.addLoadMoreButton();
                }
            } else {
                throw new Error(result.message || 'Failed to load notes');
            }
        } catch (error) {
            console.error('Error loading notes:', error);
            this.displayNotesError();
        } finally {
            this.loadingNotes = false;
        }
    }
    
    displayNotes(notes, reset = true) {
        const container = document.getElementById('notesList');
        if (!container) {
            return;
        }
        
        if (reset) {
            container.innerHTML = '';
        } else {
            this.removeLoadMoreButton();
        }
        
        if (notes.length === 0 && reset) {
            container.innerHTML = `
                <div class="empty-notes">
                    <i data-feather="edit-3"></i>
                    <h6>No internal notes yet</h6>
                    <p>Be the first to add a note</p>
                </div>
            `;
        } else if (notes.length > 0) {
            const noteHtmlArray = notes.map(note => this.createNoteHtml(note));
            if (reset) {
                container.innerHTML = noteHtmlArray.join('');
            } else {
                container.insertAdjacentHTML('beforeend', noteHtmlArray.join(''));
            }
        }
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    createNoteHtml(note) {
        const processedContent = this.processNoteMentions(note.note, note.mentions || []);
        
        // Check if current user can edit/delete this note
        const currentUserId = <?= json_encode(auth()->id() ?? 0) ?>;
        const canEdit = currentUserId && (currentUserId == note.author_id);
        
        const actionButtons = canEdit ? `
            <div class="note-actions">
                <button class="note-action-btn edit" onclick="window.internalNotesInstance.editNote(${note.id})">
                    <i data-feather="edit-2" class="icon-xs me-1"></i>Edit
                </button>
                <button class="note-action-btn delete" onclick="window.internalNotesInstance.deleteNote(${note.id})">
                    <i data-feather="trash-2" class="icon-xs me-1"></i>Delete
                </button>
            </div>
        ` : '';
        
        return `
            <div class="note-item" data-note-id="${note.id}">
                <div class="note-header">
                    <div class="note-author">
                        <img src="${note.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(note.first_name + ' ' + note.last_name) + '&size=32&background=007bff&color=ffffff'}" 
                             alt="${note.first_name} ${note.last_name}" 
                             class="note-avatar">
                        <div class="note-author-info">
                            <p class="note-author-name">${note.first_name} ${note.last_name}</p>
                            <p class="note-timestamp" title="${note.created_at_formatted}">
                                ${note.created_at_relative}
                            </p>
                        </div>
                    </div>
                    ${actionButtons}
                </div>
                <div class="note-content" data-original-text="${note.note}">${processedContent}</div>
            </div>
        `;
    }
    
    processNoteMentions(content, mentions) {
        // Simple mention processing
        return content.replace(/@(\w+)/g, '<span class="mention">@$1</span>');
    }
    
    clearNoteForm() {
        const noteContent = document.getElementById('noteContent');
        const noteAttachments = document.getElementById('noteAttachments');
        
        if (noteContent) noteContent.value = '';
        if (noteAttachments) noteAttachments.value = '';
        
        this.updateAttachmentCount(0);
    }
    
    updateAttachmentCount(count) {
        const attachmentCount = document.getElementById('noteAttachmentCount');
        if (attachmentCount) {
            attachmentCount.textContent = count > 0 ? `${count} file(s) selected` : '';
        }
    }
    
    updateNotesCount(count) {
        const notesCount = document.getElementById('notesCount');
        if (notesCount) {
            notesCount.textContent = count || 0;
        }
    }
    
    addLoadMoreButton() {
        this.removeLoadMoreButton(); // Remove existing button
        
        const container = document.getElementById('notesList');
        if (container && this.hasMore) {
            const loadMoreButton = document.createElement('div');
            loadMoreButton.className = 'load-more-notes text-center mt-3';
            loadMoreButton.innerHTML = `
                <button class="btn btn-outline-primary btn-sm" onclick="window.internalNotesInstance.loadMoreNotes()">
                    <i data-feather="chevron-down" class="icon-xs me-1"></i>
                    Load More Notes
                </button>
            `;
            container.appendChild(loadMoreButton);
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    }
    
    removeLoadMoreButton() {
        const loadMoreButton = document.querySelector('.load-more-notes');
        if (loadMoreButton) {
            loadMoreButton.remove();
        }
    }
    
    loadMoreNotes() {
        if (this.hasMore && !this.loadingNotes) {
            this.currentPage++;
            this.loadNotes(false); // false = append mode
        }
    }
    
    filterNotes() {
        this.loadNotes(true); // Reset and reload with filters
    }
    
    async editNote(noteId) {
        // Implementation for editing notes
        console.log('Edit note:', noteId);
    }
    
    async deleteNote(noteId) {
        if (!confirm('Are you sure you want to delete this note?')) {
            return;
        }
        
        try {
            const response = await fetch(`<?= base_url('internal-notes/delete/') ?>${noteId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showAlert('Note deleted successfully', 'success');
                this.loadNotes(true); // Reload notes
                
                // Refresh recent activities if function exists
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(result.message || 'Failed to delete note');
            }
        } catch (error) {
            console.error('Error deleting note:', error);
            this.showAlert('Error deleting note: ' + error.message, 'error');
        }
    }
    
    populateAuthorFilter() {
        const authorFilter = document.getElementById('notesAuthorFilter');
        if (authorFilter && this.staffUsers.length > 0) {
            authorFilter.innerHTML = '<option value="">All Authors</option>';
            this.staffUsers.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.first_name} ${user.last_name}`;
                authorFilter.appendChild(option);
            });
        }
    }
    
    handleMentionTyping(e) {
        // Basic mention functionality
        const textarea = e.target;
        const value = textarea.value;
        const cursorPos = textarea.selectionStart;
        
        // Find if we're typing after an @ symbol
        const beforeCursor = value.substring(0, cursorPos);
        const match = beforeCursor.match(/@(\w*)$/);
        
        if (match) {
            console.log('Mention detected:', match[1]);
            // Could implement mention suggestions here
        }
    }
    
    handleMentionNavigation(e) {
        // Handle keyboard navigation for mentions
    }
    
    loadMentions() {
        // Load mentions for current user
        console.log('Loading mentions...');
    }
    
    loadTeamActivity() {
        // Load team activity
        console.log('Loading team activity...');
    }
    
    displayNotesError() {
        const container = document.getElementById('notesList');
        if (container) {
            container.innerHTML = `
                <div class="alert alert-danger">
                    <h6>Error loading notes</h6>
                    <p>Please try again later.</p>
                    <button class="btn btn-outline-danger btn-sm" onclick="window.internalNotesInstance.loadNotes(true)">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        Try Again
                    </button>
                </div>
            `;
        }
    }
    
    showAlert(message, type = 'info') {
        // Use SweetAlert if available
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type === 'error' ? 'error' : type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'info',
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            console.log(`${type.toUpperCase()}: ${message}`);
            // Fallback to browser alert
            alert(message);
        }
    }
    
    destroy() {
        if (this.isDestroyed) {
            return;
        }
        
        console.log('Notes: Destroying Internal Notes System instance');
        this.isDestroyed = true;
        this.submittingNote = false;
        this.loadingNotes = false;
        
        // Clear global references
        if (window.internalNotesInstance === this) {
            window.internalNotesInstance = null;
            window.internalNotesInitialized = null;
        }
        
        console.log('Notes: Internal Notes System destroyed');
    }
}



// Translation object for internal notes
window.internalNotesTranslations = {
    noteAddedSuccessfully: 'Note added successfully',
    noteUpdatedSuccessfully: 'Note updated successfully', 
    noteDeletedSuccessfully: 'Note deleted successfully',
    errorAddingNote: 'Error adding note',
    errorUpdatingNote: 'Error updating note',
    errorDeletingNote: 'Error deleting note',
    errorLoadingNotes: 'Error loading notes',
    deleteNoteConfirmation: 'Delete Note?',
    deleteNoteText: 'Are you sure you want to delete this note?',
    yesDeleteNote: 'Yes, Delete',
    cancelDelete: 'Cancel',
    noInternalNotesYet: 'No internal notes yet',
    beFirstAddNote: 'Be the first to add a note',
    noteContentRequired: 'Note content is required',
    addingNote: 'Adding Note...',
    editNote: 'Edit',
    deleteNote: 'Delete'
};

// Initialize Internal Notes System
let internalNotes; // Keep for backward compatibility

// Function to regenerate QR code
window.regenerateQR = function(orderId) {
    Swal.fire({
        title: 'Regenerate QR Code?',
        text: 'This will create a new MDA Links short URL and QR code.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, regenerate!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Regenerating QR Code...',
                text: 'Creating new MDA Links short URL',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Call regenerate endpoint
            fetch(`<?= base_url('recon_orders/regenerateQR') ?>/${orderId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
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
                console.error('Error regenerating QR:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to regenerate QR code. Please try again.'
                });
            });
        }
    });
};
</script>
<?= $this->endSection() ?> 