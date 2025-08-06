<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? 'Car Wash Order' ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? 'Car Wash Order' ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('car_wash') ?>"><?= lang('App.car_wash_orders') ?></a></li>
<li class="breadcrumb-item active"><?= $title ?? 'Car Wash Order' ?></li>
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
    min-width: 0;
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

/* Time Status Indicators */
.time-status-on-time {
    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
}

.time-status-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.time-status-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
}

/* Comments Styles - Enhanced Version from Sales Orders */
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
    max-width: 100%;
    max-height: 100%;
}

.file-thumbnail-icon {
    font-size: 18px;
}

.file-thumbnail-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background-color: #f8f9fa;
    border-radius: 4px;
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

/* ========================================
   ENHANCED ACTIVITY STYLES (from Sales Orders)
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

/* Icon sizing for feather icons */
.icon-xs {
    width: 12px;
    height: 12px;
}

.icon-sm {
    width: 16px;
    height: 16px;
}

.icon-lg {
    width: 24px;
    height: 24px;
}

/* Mobile Responsive */
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
    
    #activityList {
        max-height: 300px;
    }
    
    .activity-item:hover {
        margin: 0 -0.5rem;
        padding: 0.5rem;
    }
}

/* Mobile & Tablet Quick Actions Responsive */
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
.quick-action-edit .quick-action-icon {
    background: transparent;
}

.quick-action-edit .quick-action-icon i {
    color: #3182ce;
}

.quick-action-edit:hover {
    border-color: #3182ce;
}

.quick-action-status .quick-action-icon {
    background: transparent;
}

.quick-action-status .quick-action-icon i {
    color: #38a169;
}

.quick-action-status:hover {
    border-color: #38a169;
}

.quick-action-qr .quick-action-icon {
    background: transparent;
}

.quick-action-qr .quick-action-icon i {
    color: #9f7aea;
}

.quick-action-qr:hover {
    border-color: #9f7aea;
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

/* File attachments styling */
.attachment-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
    background-color: #f8f9fa;
}

.attachment-item:hover {
    border-color: #6c757d;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.attachment-thumbnail {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    overflow: hidden;
}

.file-thumbnail-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-thumbnail-icon {
    font-size: 20px;
}

.attachment-info {
    flex: 1;
    min-width: 0;
}

.attachment-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}

.attachment-header i {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.attachment-name {
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}

.attachment-size {
    color: #6c757d;
    font-size: 12px;
}

.attachment-actions {
    display: flex;
    gap: 4px;
}

.attachment-actions .btn {
    padding: 4px 8px;
    border-radius: 4px;
}

.attachment-actions .btn i {
    font-size: 12px;
}

/* TinyPNG optimization badge */
.badge.bg-success-subtle {
    background-color: #d1f2eb !important;
    color: #0f6848 !important;
    border: 1px solid #b8e6d3;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 500;
}

.badge.bg-success-subtle i {
    font-size: 8px;
    margin-right: 2px;
}

.badge.bg-success-subtle:hover {
    background-color: #c3e9d6 !important;
    cursor: help;
}

/* Internal Notes System Styles */
.internal-notes-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.nav-tabs-bordered {
    border-bottom: 2px solid #e9ecef;
}

.nav-tabs-bordered .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs-bordered .nav-link:hover {
    border-color: transparent;
    background-color: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

.nav-tabs-bordered .nav-link.active {
    background-color: transparent;
    border-color: #007bff;
    color: #007bff;
    font-weight: 600;
}

.notes-filter-bar {
    background: #f8f9fa;
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.note-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    background: white;
    transition: all 0.2s ease;
}

.note-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-color: #007bff;
}

.note-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.note-author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.note-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.note-author-info {
    flex: 1;
}

.note-author-name {
    font-weight: 600;
    font-size: 0.875rem;
    margin: 0;
    color: #333;
}

.note-timestamp {
    font-size: 0.75rem;
    color: #6c757d;
    margin: 0;
}

.note-actions {
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.note-item:hover .note-actions {
    opacity: 1;
}

.note-action-btn {
    background: none;
    border: 1px solid #dee2e6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    color: #6c757d;
    font-size: 0.75rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.note-action-btn:hover {
    background: #f8f9fa;
    border-color: #007bff;
    color: #007bff;
}

.note-action-btn.edit {
    border-color: #28a745;
    color: #28a745;
}

.note-action-btn.edit:hover {
    background: #d4edda;
}

.note-action-btn.delete {
    border-color: #dc3545;
    color: #dc3545;
}

.note-action-btn.delete:hover {
    background: #f8d7da;
}

.note-content {
    font-size: 0.875rem;
    line-height: 1.5;
    color: #495057;
    margin-bottom: 0.75rem;
}

.note-mention {
    background: #e3f2fd;
    color: #1976d2;
    padding: 1px 4px;
    border-radius: 3px;
    font-weight: 500;
    text-decoration: none;
}

.note-mention:hover {
    background: #bbdefb;
    text-decoration: underline;
}

.note-attachments {
    margin-top: 0.75rem;
}

.note-attachments-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.note-attachment-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.note-attachment-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    background-color: #f8f9fa;
    transition: all 0.2s ease;
    gap: 0.75rem;
}

.note-attachment-item:hover {
    background-color: #e9ecef;
    border-color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.attachment-thumbnail {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #007bff;
    color: white;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
}

/* Internal Notes Styles */
.internal-notes-card {
    border-radius: 16px;
}

.internal-notes-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 16px 16px 0 0;
    border-bottom: 1px solid #e9ecef;
}

.nav-tabs-bordered {
    border-bottom: 2px solid #e9ecef;
}

.nav-tabs-bordered .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    padding: 0.75rem 1rem;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs-bordered .nav-link.active {
    color: #405189;
    border-bottom-color: #405189;
    background: none;
}

.nav-tabs-bordered .nav-link:hover {
    border-bottom-color: #405189;
    color: #405189;
}

.note-item {
    padding: 1rem;
    border-bottom: 1px solid #f1f3f4;
    margin-bottom: 0.5rem;
    border-radius: 8px;
    background: #ffffff;
    transition: all 0.2s ease;
}

.note-item:hover {
    background: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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

.note-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 0.75rem;
    object-fit: cover;
}

.note-author-info h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: #495057;
}

.note-timestamp {
    font-size: 0.75rem;
    color: #6c757d;
    margin: 0;
}

.note-content {
    font-size: 0.875rem;
    line-height: 1.5;
    color: #495057;
    margin-bottom: 0.75rem;
}

.note-actions {
    display: flex;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.note-item:hover .note-actions {
    opacity: 1;
}

.note-action-btn, .reply-action-btn {
    background: none;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    color: #6c757d;
    font-size: 0.75rem;
    transition: all 0.15s ease;
    cursor: pointer;
}

.note-action-btn:hover, .reply-action-btn:hover {
    background: #e9ecef;
    color: #405189;
}

.note-action-btn.reply:hover, .reply-action-btn.reply:hover {
    background: #e3f2fd;
    color: #1976d2;
}

.note-action-btn.edit:hover, .reply-action-btn.edit:hover {
    background: #fff3e0;
    color: #f57c00;
}

.note-action-btn.delete:hover, .reply-action-btn.delete:hover {
    background: #ffebee;
    color: #d32f2f;
}

.reply-item {
    margin-left: 1rem;
    padding: 0.5rem 0;
    border-left: 2px solid #e9ecef;
    padding-left: 0.75rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.reply-item:hover {
    background: #f8f9fa;
    border-left-color: #405189;
}

.reply-item:hover .reply-actions {
    opacity: 1 !important;
}

.reply-actions {
    display: flex;
    gap: 0.25rem;
}

.reply-action-btn {
    padding: 0.125rem 0.25rem;
    font-size: 0.7rem;
}

.reply-form {
    margin-left: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #405189;
    margin-top: 0.5rem;
}

.note-edit-form, .reply-edit-form {
    padding: 1rem;
    background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
    border-radius: 8px;
    border: 1px solid #ffc107;
    border-left: 4px solid #f57c00;
    margin-top: 0.75rem;
    box-shadow: 0 2px 8px rgba(245, 124, 0, 0.1);
    animation: slideInEdit 0.3s ease-out;
}

.reply-edit-form {
    padding: 0.75rem;
    margin-left: 0;
    margin-top: 0.5rem;
    border-left: 4px solid #2196f3;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #2196f3;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.1);
}

.note-edit-form .form-text {
    color: #f57c00;
    font-size: 0.75rem;
    font-weight: 500;
}

.reply-edit-form .form-text {
    color: #1976d2;
    font-size: 0.75rem;
    font-weight: 500;
}

.note-edit-form textarea,
.reply-edit-form textarea {
    border: 1px solid #ddd;
    border-radius: 6px;
    transition: all 0.2s ease;
    background: white;
}

.note-edit-form textarea:focus,
.reply-edit-form textarea:focus {
    border-color: #405189;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.15);
    outline: none;
}

@keyframes slideInEdit {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.note-attachments {
    margin-top: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.note-attachments-title {
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.notes-filter-bar {
    padding: 0.75rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.mention-suggestions-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.mention-suggestion {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #f8f9fa;
    font-size: 0.875rem;
}

.mention-suggestion:hover,
.mention-suggestion.active {
    background: #f8f9fa;
    color: #405189;
}

.mention-suggestion:last-child {
    border-bottom: none;
}

.mention-alert {
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    border-radius: 6px;
    border-left: 3px solid #ffc107;
    background: #fff8e1;
}

.mention-alert.unread {
    background: #e3f2fd;
    border-left-color: #2196f3;
}

.mention-alert.read {
    background: #f5f5f5;
    border-left-color: #9e9e9e;
}

.mention-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.mention-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.mention-note-preview {
    font-size: 0.8rem;
    color: #495057;
}

.mention-alert-action {
    background: none;
    border: none;
    color: #405189;
    font-size: 0.75rem;
    text-decoration: underline;
    cursor: pointer;
}

.mention-alert-action:hover {
    color: #2c3e50;
}

@keyframes rotating {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.rotating {
    animation: rotating 1s linear infinite;
}

/* Infinite Scroll Styles */
#infiniteScrollLoader {
    background: rgba(248, 249, 250, 0.9);
    border-radius: 8px;
    margin: 0.5rem 0;
    transition: opacity 0.3s ease;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

#infiniteScrollLoader .spinner-border {
    width: 1.5rem;
    height: 1.5rem;
    border-width: 0.2em;
}

#infiniteScrollLoader p {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

/* Enhanced Load More Button Styles */
#loadMoreContainer {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    margin: 1rem 0;
    padding: 1rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

#loadMoreContainer::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s ease;
}

#loadMoreContainer:hover::before {
    left: 100%;
}

#loadMoreContainer:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

#loadMoreContainer .btn {
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

#loadMoreContainer .small {
    font-size: 0.75rem;
    opacity: 0.8;
    position: relative;
    z-index: 1;
}

/* Notes container scroll styling */
#notesList {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 8px;
    margin-right: -8px;
}

#notesList::-webkit-scrollbar {
    width: 8px;
}

#notesList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#notesList::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#notesList::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Smooth scroll animation */
html {
    scroll-behavior: smooth;
}

.note-item {
    opacity: 0;
    transform: translateY(20px);
    animation: noteSlideIn 0.4s ease-out forwards;
}

.note-item:nth-child(1) { animation-delay: 0.1s; }
.note-item:nth-child(2) { animation-delay: 0.2s; }
.note-item:nth-child(3) { animation-delay: 0.3s; }
.note-item:nth-child(4) { animation-delay: 0.4s; }
.note-item:nth-child(5) { animation-delay: 0.5s; }

@keyframes noteSlideIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Back Button -->
<div class="row mb-3">
    <div class="col-12">
        <a href="<?= base_url('car_wash') ?>" class="btn btn-secondary">
            <i class="ri-arrow-left-line"></i> <?= lang('App.back_to_list') ?>
        </a>
    </div>
</div>

<!-- Top Bar Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="order-top-bar">
            <div class="row g-0">
                <!-- Order Number -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-car-washing-line text-primary"></i>
                                </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label"><?= lang('App.order_number') ?></div>
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
                            <div class="top-bar-label"><?= lang('App.client') ?></div>
                            <div class="top-bar-value"><?= $order['client_name'] ?></div>
                            <div class="top-bar-sub"><?= $order['contact_name'] ?? '' ?></div>
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
                            <div class="top-bar-label"><?= lang('App.vehicle') ?></div>
                            <div class="top-bar-value"><?= $order['vehicle'] ?></div>
                            <?php if (!empty($order['tag_stock'])): ?>
                            <div class="top-bar-sub">Tag: <?= $order['tag_stock'] ?></div>
                            <?php endif; ?>
                    </div>
                </div>
            </div>

                <!-- Service -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-service-line text-warning"></i>
                                </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label"><?= lang('App.service') ?></div>
                            <div class="top-bar-value"><?= $order['service_name'] ?></div>
                            <div class="top-bar-sub">$<?= number_format($order['service_price'], 2) ?></div>
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
                            <div class="top-bar-label"><?= lang('App.status') ?></div>
                            <div class="top-bar-value">
                                <?php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'confirmed' => 'info',
                                    'in_progress' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $statusColor = $statusColors[$order['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $statusColor ?> order-status-badge"><?= ucwords(str_replace('_', ' ', $order['status'])) ?></span>
                        </div>
                            <div class="top-bar-sub"><?= $order['creator_first_name'] ?? '' ?> <?= $order['creator_last_name'] ?? '' ?></div>
                    </div>
                </div>
            </div>

                <!-- Priority -->
                <div class="col-xl-2 col-lg-3 col-md-6">
                    <div class="top-bar-item">
                        <div class="top-bar-icon">
                            <i class="ri-user-star-line text-primary"></i>
                    </div>
                        <div class="top-bar-content">
                            <div class="top-bar-label">Priority</div>
                            <div class="top-bar-value">
                                <?php if ($order['priority'] === 'waiter'): ?>
                                    <span class="badge bg-danger-subtle text-danger-emphasis px-2 py-1 rounded-pill">
                                        <i class="ri-user-fill me-1"></i>Waiter
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success-emphasis px-2 py-1 rounded-pill">
                                        <i class="ri-check-line me-1"></i>Normal
                                    </span>
                                <?php endif; ?>
                        </div>
                            <div class="top-bar-sub"><?= ucfirst($order['priority']) ?> Priority</div>
                    </div>
                </div>
            </div>


                        </div>
                    </div>
                </div>
            </div>

<div class="row">
    <!-- Main Content -->
    <div class="col-xl-8">
        <div class="row">
            <!-- Order Details Card -->
            <div class="col-12 mb-4">
                <div class="card" style="border-radius: 16px;">
                    <div class="card-header d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-primary text-white">
                                    <i class="ri-information-line"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                            <h5 class="card-title fw-bold mb-0"><?= lang('App.order_details') ?></h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                            <td class="fw-medium"><?= lang('App.order_number') ?>:</td>
                                            <td><?= $order['order_number'] ?></td>
                                    </tr>
                                    <tr>
                                            <td class="fw-medium"><?= lang('App.vehicle') ?>:</td>
                                            <td><?= $order['vehicle'] ?></td>
                                    </tr>
                                        <?php if (!empty($order['tag_stock'])): ?>
                                    <tr>
                                            <td class="fw-medium"><?= lang('App.tag_stock') ?>:</td>
                                            <td><?= $order['tag_stock'] ?></td>
                                    </tr>
                                    <?php endif; ?>
                                        <?php if (!empty($order['vin_number'])): ?>
                                    <tr>
                                            <td class="fw-medium"><?= lang('App.vin_number') ?>:</td>
                                            <td><?= $order['vin_number'] ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="fw-medium"><?= lang('App.service') ?>:</td>
                                            <td><?= $order['service_name'] ?></td>
                            </tr>
                                        <tr>
                                            <td class="fw-medium"><?= lang('App.price') ?>:</td>
                                            <td class="fw-bold text-success">$<?= number_format($order['service_price'], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-medium"><?= lang('App.status') ?>:</td>
                                <td>
                                                <span class="badge bg-<?= $statusColor ?> order-status-badge"><?= ucwords(str_replace('_', ' ', $order['status'])) ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                        
                <?php if (!empty($order['notes'])): ?>
                        <div class="mt-3">
                            <h6 class="fw-bold mb-2"><?= lang('App.notes') ?>:</h6>
                            <p class="text-muted mb-0"><?= nl2br(esc($order['notes'])) ?></p>
        </div>
        <?php endif; ?>
                </div>
            </div>
        </div>



            <!-- Comments Section -->
            <div class="col-12 mb-4">
                <div class="card order-comments" id="comments" style="border-radius: 16px;">
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
            </div>

            <!-- Internal Communication Section - Staff and Admin Only -->
            <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
            <div class="col-12 mb-4">
                <div class="card" id="internal-notes-card" style="border-radius: 16px;">
                    <div class="card-header">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-feather="users" class="icon-sm me-2"></i>
                            <?= lang('App.internal_notes') ?>
                        </h5>
                        <small class="text-muted"><?= lang('App.staff_only_notes_discussions') ?></small>
                    </div>
                    <div class="card-body p-0">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="internalTabsNav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes-pane" type="button" role="tab" aria-controls="notes-pane" aria-selected="true">
                                    <i data-feather="edit-3" class="icon-xs me-1"></i>
                                    <?= lang('App.internal_notes') ?>
                                    <span id="notesCount" class="badge bg-primary ms-1">0</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="mentions-tab" data-bs-toggle="tab" data-bs-target="#mentions-pane" type="button" role="tab" aria-controls="mentions-pane" aria-selected="false">
                                    <i data-feather="at-sign" class="icon-xs me-1"></i>
                                    <?= lang('App.note_mentions') ?>
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
                                                <textarea class="form-control" id="noteContent" rows="3" placeholder="<?= lang('App.note_content_placeholder') ?> Use @username to mention staff members" required></textarea>
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
                                                    <?= lang('App.attach_files') ?>
                                                </button>
                                                <span id="noteAttachmentCount" class="text-muted small ms-2"></span>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i data-feather="send" class="icon-xs me-1"></i>
                                                <?= lang('App.add_note') ?>
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Notes Filter -->
                                    <div class="notes-filter-bar mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text"><i data-feather="search" class="icon-xs"></i></span>
                                                    <input type="text" class="form-control" id="notesSearch" placeholder="<?= lang('App.search_notes_placeholder') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-select form-select-sm" id="notesAuthorFilter">
                                                    <option value=""><?= lang('App.all_authors') ?></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-select form-select-sm" id="notesDateFilter">
                                                    <option value="">All Time</option>
                                                    <option value="today"><?= lang('App.today') ?></option>
                                                    <option value="week"><?= lang('App.this_week') ?></option>
                                                    <option value="month"><?= lang('App.this_month') ?></option>
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
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-xl-4">
        <div class="row">
        
        <!-- QR Code Card -->
        <?php if (isset($qr_data) && $qr_data): ?>
        <div class="col-12 mb-4">
            <div class="card qr-code-card d-none d-md-block" style="border-radius: 16px;">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-info text-white">
                                <i class="ri-qr-code-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title fw-bold mb-0">
                            <i data-feather="smartphone" class="icon-sm me-2"></i>
                            QR Code Access
                        </h5>
                        <small class="text-muted">Instant mobile access</small>
                    </div>
                </div>
                <div class="card-body text-center">
                    <!-- Large QR Code Display -->
                    <div class="qr-large-display">
                        <img src="<?= $qr_data['qr_url'] ?>" 
                             alt="QR Code for Car Wash Order CW-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
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
        </div>
        <?php else: ?>
        <!-- QR Code Not Available Card -->
        <div class="col-12 mb-4">
            <div class="card qr-code-unavailable d-none d-md-block" style="border-radius: 16px;">
                <div class="card-header d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                            <span class="avatar-title rounded-circle bg-warning text-white">
                                <i class="ri-qr-code-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title fw-bold mb-0">
                            <i data-feather="smartphone" class="icon-sm me-2"></i>
                            QR Code Access
                        </h5>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="py-4">
                        <i data-feather="alert-triangle" class="icon-lg text-warning mb-3"></i>
                        <h6 class="text-warning">QR Code Unavailable</h6>
                        <p class="text-muted small mb-3"><?= lang('App.lima_links_not_configured') ?></p>
                        <button class="btn btn-outline-primary btn-sm" onclick="generateQR(<?= $order['id'] ?>)">
                            <i data-feather="settings" class="icon-xs me-1"></i>
                            Generate QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Actions Card -->
            <div class="col-12 mb-4">
                <div class="card quick-actions-card" style="border-radius: 16px;">
                    <div class="card-header d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm">
                                <span class="avatar-title rounded-circle bg-warning text-white">
                                    <i class="ri-flashlight-line"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                            <h5 class="card-title fw-bold mb-0"><?= lang('App.quick_actions') ?></h5>
                </div>
            </div>
            <div class="card-body">
                                    <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="editOrder(<?= $order['id'] ?>)">
                                <i class="ri-edit-line me-2"></i><?= lang('App.edit_order') ?>
                    </button>
                            <button class="btn btn-outline-success" onclick="changeStatus(<?= $order['id'] ?>)">
                                <i class="ri-refresh-line me-2"></i><?= lang('App.change_status') ?>
                        </button>
                            <button class="btn btn-outline-primary" onclick="generateQR(<?= $order['id'] ?>)">
                                <i class="ri-qr-code-line me-2"></i><?= lang('App.generate_qr') ?>
                    </button>
                            <button class="btn btn-outline-warning" onclick="regenerateShortlink(<?= $order['id'] ?>)">
                                <i class="ri-refresh-line me-2"></i>Regenerar Shortlink
                            </button>
                            </div>
                            </div>
                        </div>
                </div>

            <!-- Recent Activity Card -->
            <div class="col-12 mb-4">
                <div class="card order-recent-activity" style="border-radius: 16px;">
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
    </div>
                </div>

  <?= $this->endSection() ?> ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // DEBUG: Log available data for edit modal
    console.log('🔍 CarWash View Debug Info:');
    console.log('Order:', <?= json_encode($order ?? null) ?>);
    console.log('Clients available:', <?= json_encode(!empty($clients)) ?>, 'Count:', <?= count($clients ?? []) ?>);
    console.log('Services available:', <?= json_encode(!empty($carWashServices)) ?>, 'Count:', <?= count($carWashServices ?? []) ?>);
    
    // Additional debug for troubleshooting
    console.log('🔧 CarWash View Technical Details:', {
        'clients_type': typeof <?= json_encode($clients ?? null) ?>,
        'clients_is_array': Array.isArray(<?= json_encode($clients ?? null) ?>),
        'services_type': typeof <?= json_encode($carWashServices ?? null) ?>,
        'services_is_array': Array.isArray(<?= json_encode($carWashServices ?? null) ?>),
        'order_client_id': '<?= esc($order['client_id'] ?? 'N/A') ?>',
        'order_service_id': '<?= esc($order['service_id'] ?? 'N/A') ?>'
    });
    
    // Load recent activity
    loadRecentActivity();
    
    // Load comments with new robust function
    loadComments(true);
    
    // Initialize enhanced comment form
    initializeCommentForm();
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
    
    // Fix modal backdrop issues
    $('#qrModal').on('hidden.bs.modal', function () {
        // Ensure backdrop is completely removed
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({
            'overflow': '',
            'padding-right': ''
        });
    });
    
    // 3. Initialize Internal Notes System for staff and admin users only
    <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
    if (!internalNotesInitialized && !internalNotes) {
        console.log('DOMContentLoaded: Initializing Internal Notes System for CarWash');
        internalNotesInitialized = true;
        internalNotes = new InternalNotesSystem(<?= $order['id'] ?? 0 ?>);
    }
    <?php endif; ?>
});

// Enhanced loadRecentActivity function with robust error handling
function loadRecentActivity(reset = true) {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const activityList = document.getElementById('activityList');
    
    if (reset) {
        activityList.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                                </div>
                <p class="text-muted mt-2 mb-0">Loading activities...</p>
                                    </div>
        `;
    }
    
    fetch(`<?= base_url('car_wash/getActivity/') ?>${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.activities) {
            activityList.innerHTML = '';
            
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
                
                // Initialize tooltips for activities
                initializeActivityTooltips();
            } else {
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
        } else {
            activityList.innerHTML = `
                <div class="text-center py-3">
                    <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                    <p class="text-muted mb-0">Error loading activities</p>
                            </div>
            `;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    })
    .catch(error => {
        console.error('Error loading activity:', error);
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
    });
}

// Create activity HTML with enhanced styling and tooltips
function createActivityHtml(activity) {
    const iconClass = getActivityIcon(activity.activity_type || activity.action);
    const colorClass = getActivityColor(activity.activity_type || activity.action);
    
    // Check if this activity has metadata that should be shown in tooltip
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
        
        // For Comment activities - show complete comment
        if ((activity.activity_type === 'comment_added' || activity.action === 'comment_added') && metadata.comment) {
            tooltipContent = `💬 Complete Comment:\n${metadata.comment}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        
        // For Comment deleted activities - show deleted comment content
        if ((activity.activity_type === 'comment_deleted' || activity.action === 'comment_deleted') && metadata.deleted_comment) {
            tooltipContent = `🗑️ Deleted Comment:\n${metadata.deleted_comment}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        
        // For Internal Note deleted activities - show deleted note content
        if ((activity.activity_type === 'internal_note_deleted' || activity.action === 'internal_note_deleted') && metadata.deleted_note) {
            tooltipContent = `🗑️ Deleted Internal Note:\n${metadata.deleted_note}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        
        // For Internal Note activities - show complete note content
        if ((activity.activity_type === 'internal_note_added' || activity.activity_type === 'internal_note_updated' || activity.activity_type === 'internal_note_deleted' || activity.activity_type === 'internal_note_reply_added') && metadata.full_content) {
            const noteIcon = activity.activity_type === 'internal_note_added' ? '📝' : 
                           activity.activity_type === 'internal_note_updated' ? '✏️' : 
                           activity.activity_type === 'internal_note_deleted' ? '🗑️' : 
                           activity.activity_type === 'internal_note_reply_added' ? '💬' : '📝';
            tooltipContent = `${noteIcon} Complete Note:\n${metadata.full_content}`;
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
                <h6 class="mb-1">${activity.description} ${tooltipContent ? '<i class="icon-xs ms-1 text-muted" data-feather="info"></i>' : ''}</h6>
                ${getActivityExtraInfo(activity)}
                <small class="text-muted">
                    <i data-feather="user" class="icon-xs me-1"></i>
                    ${activity.user_name || 'System'}
                    <span class="mx-2">•</span>
                    <i data-feather="clock" class="icon-xs me-1"></i>
                    ${activity.created_at}
                </small>
            </div>
        </div>
    `;
}

// Get extra info for activity display
function getActivityExtraInfo(activity) {
    if (!activity.metadata) return '';
    
    const metadata = activity.metadata;
    let extraInfo = '';
    
    // For field changes, show old and new values inline
    if (activity.activity_type === 'field_change' && (metadata.old_display_value || metadata.new_display_value)) {
        extraInfo += '<div class="mt-1 small">';
        if (metadata.old_display_value && metadata.new_display_value) {
            extraInfo += `<span class="text-danger me-2">From: <strong>${metadata.old_display_value}</strong></span>`;
            extraInfo += `<span class="text-success">To: <strong>${metadata.new_display_value}</strong></span>`;
        } else if (metadata.new_display_value) {
            extraInfo += `<span class="text-success">New value: <strong>${metadata.new_display_value}</strong></span>`;
        } else if (metadata.old_display_value) {
            extraInfo += `<span class="text-danger">Removed: <strong>${metadata.old_display_value}</strong></span>`;
        }
        extraInfo += '</div>';
    }
    
    // For status changes, show old and new status inline
    if (activity.activity_type === 'status_change' && (metadata.old_status_label || metadata.new_status_label)) {
        extraInfo += '<div class="mt-1 small">';
        if (metadata.old_status_label && metadata.new_status_label) {
            extraInfo += `<span class="text-danger me-2">From: <strong>${metadata.old_status_label}</strong></span>`;
            extraInfo += `<span class="text-success">To: <strong>${metadata.new_status_label}</strong></span>`;
        }
        extraInfo += '</div>';
    }
    
    // For internal note activities, show content preview
    if ((activity.activity_type === 'internal_note_added' || activity.activity_type === 'internal_note_updated' || activity.activity_type === 'internal_note_deleted' || activity.activity_type === 'internal_note_reply_added') && metadata.content_preview) {
        const noteIcon = activity.activity_type === 'internal_note_added' ? '📝' : 
                       activity.activity_type === 'internal_note_updated' ? '✏️' : 
                       activity.activity_type === 'internal_note_deleted' ? '🗑️' : 
                       activity.activity_type === 'internal_note_reply_added' ? '💬' : '📝';
        extraInfo += '<div class="mt-1 small">';
        extraInfo += `<span class="text-muted">${noteIcon} <em>"${metadata.content_preview}"</em></span>`;
        extraInfo += '</div>';
    }
    
    // For comment activities - show comment preview
    if ((activity.activity_type === 'comment_added' || activity.action === 'comment_added') && metadata.comment_preview) {
        extraInfo += '<div class="mt-1 small">';
        extraInfo += `<span class="text-muted">💬 <em>"${metadata.comment_preview}"</em></span>`;
        extraInfo += '</div>';
    }
    
    // For comment deleted activities - show deleted comment preview
    if ((activity.activity_type === 'comment_deleted' || activity.action === 'comment_deleted') && metadata.comment_preview) {
        extraInfo += '<div class="mt-1 small">';
        extraInfo += `<span class="text-muted">🗑️ <em>"${metadata.comment_preview}"</em></span>`;
        extraInfo += '</div>';
    }
    
    return extraInfo;
}

// Helper function to escape content for tooltip text
function escapeForTooltipText(text) {
    return text.replace(/"/g, '&quot;')
               .replace(/'/g, '&#39;');
}

// Helper Functions for activity icons and colors
function getActivityIcon(type) {
    const icons = {
        'status_change': 'refresh-cw',
        'email_sent': 'mail',
        'sms_sent': 'message-square',
        'notification_sent': 'bell',
        'comment_added': 'message-circle',
        'comment_reply_added': 'corner-down-right',
        'comment_updated': 'edit-3',
        'comment_deleted': 'trash-2',
        'internal_note_added': 'file-text',
        'internal_note_updated': 'edit-2',
        'internal_note_deleted': 'trash-2',
        'internal_note_reply_added': 'corner-down-right',
        'order_created': 'plus-circle',
        'order_updated': 'edit-3',
        'field_change': 'edit-3',
        'order_deleted': 'trash',
        'order_restored': 'rotate-ccw'
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
        'comment_updated': 'warning',
        'comment_deleted': 'danger',
        'internal_note_added': 'info',
        'internal_note_updated': 'warning',
        'internal_note_deleted': 'danger',
        'internal_note_reply_added': 'info',
        'order_created': 'success',
        'order_updated': 'primary',
        'field_change': 'primary',
        'order_deleted': 'danger',
        'order_restored': 'success'
    };
    return colors[type] || 'secondary';
}

// Initialize tooltips for activities
function initializeActivityTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
}

// ========================================
// INTERNAL NOTES SYSTEM
// ========================================

// Internal Notes System Class
class InternalNotesSystem {
    constructor(orderId) {
        console.log('InternalNotesSystem constructor called with orderId:', orderId);
        
        // Prevent multiple instances
        if (window.internalNotesInstance) {
            console.log('Destroying existing InternalNotesSystem instance');
            window.internalNotesInstance.destroy();
        }
        
        this.orderId = orderId;
        this.currentUser = null;
        this.staffUsers = [];
        this.mentionSuggestions = [];
        this.selectedSuggestionIndex = -1;
        this.notesData = [];
        this.mentionsData = [];
        this.submittingNote = false;
        this.loadingNotes = false;
        this.submitCounter = 0;
        this.isDestroyed = false;
        this.lastSubmitTime = 0;
        
        // Pagination variables for infinite scroll
        this.currentPage = 1;
        this.hasMore = true;
        this.totalNotes = 0;
        this.loadedNotes = [];
        
        // Register this instance globally
        window.internalNotesInstance = this;
        console.log('InternalNotesSystem instance registered globally');
        
        this.init();
    }
    
    async init() {
        await this.loadCurrentUser();
        await this.loadStaffUsers();
        this.bindEvents();
        console.log('init: Loading notes for first time');
        this.loadNotes();
        this.loadMentions();
        this.loadTeamActivity();
    }
    
    async loadCurrentUser() {
        this.currentUser = {
            id: <?= auth()->id() ?? 0 ?>,
            username: '<?= auth()->user()->username ?? 'user' ?>',
            name: '<?= auth()->user()->first_name ?? 'User' ?> <?= auth()->user()->last_name ?? '' ?>',
            avatar: '<?= getAvatarUrl(auth()->user()) ?>'
        };
    }
    
    async loadStaffUsers() {
        try {
            const response = await fetch(`<?= base_url('car-wash-notes/staff-users') ?>`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                this.staffUsers = result.data || [];
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
        authorFilter.innerHTML = '<option value=""><?= lang('App.all_authors') ?></option>';
        
        this.staffUsers.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = user.name;
            authorFilter.appendChild(option);
        });
    }
    
    bindEvents() {
        // Remove any existing event listeners first
        this.unbindEvents();
        
        // Note form submission
        const noteForm = document.getElementById('noteForm');
        if (noteForm && !noteForm.hasAttribute('data-notes-bound')) {
            this.handleNoteSubmitBound = (e) => this.handleNoteSubmit(e);
            noteForm.addEventListener('submit', this.handleNoteSubmitBound);
            noteForm.setAttribute('data-notes-bound', 'true');
        }
        
        // Mention functionality
        const noteContent = document.getElementById('noteContent');
        if (noteContent && !noteContent.hasAttribute('data-notes-bound')) {
            noteContent.addEventListener('input', (e) => this.handleMentionTyping(e));
            noteContent.addEventListener('keydown', (e) => this.handleMentionNavigation(e));
            noteContent.setAttribute('data-notes-bound', 'true');
        }
        
        // Filter events
        const notesSearch = document.getElementById('notesSearch');
        if (notesSearch && !notesSearch.hasAttribute('data-notes-bound')) {
            notesSearch.addEventListener('input', () => this.filterNotes());
            notesSearch.setAttribute('data-notes-bound', 'true');
        }
        
        const notesAuthorFilter = document.getElementById('notesAuthorFilter');
        if (notesAuthorFilter && !notesAuthorFilter.hasAttribute('data-notes-bound')) {
            notesAuthorFilter.addEventListener('change', () => this.filterNotes());
            notesAuthorFilter.setAttribute('data-notes-bound', 'true');
        }
        
        const notesDateFilter = document.getElementById('notesDateFilter');
        if (notesDateFilter && !notesDateFilter.hasAttribute('data-notes-bound')) {
            notesDateFilter.addEventListener('change', () => this.filterNotes());
            notesDateFilter.setAttribute('data-notes-bound', 'true');
        }
        
        // File attachment count
        const noteAttachments = document.getElementById('noteAttachments');
        if (noteAttachments && !noteAttachments.hasAttribute('data-notes-bound')) {
            noteAttachments.addEventListener('change', (e) => this.updateAttachmentCount(e));
            noteAttachments.setAttribute('data-notes-bound', 'true');
        }
        
        // Tab switching
        const tabButtons = document.querySelectorAll('#internalTabsNav button[data-bs-toggle="tab"]:not([data-notes-bound])');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', (e) => this.handleTabSwitch(e));
            button.setAttribute('data-notes-bound', 'true');
        });
        
        // Infinite scroll for notes
        const notesContainer = document.getElementById('notesList');
        if (notesContainer && !notesContainer.hasAttribute('data-scroll-bound')) {
            notesContainer.addEventListener('scroll', (e) => this.handleNotesScroll(e));
            notesContainer.setAttribute('data-scroll-bound', 'true');
        }
    }
    
    unbindEvents() {
        const noteForm = document.getElementById('noteForm');
        if (noteForm && this.handleNoteSubmitBound) {
            noteForm.removeEventListener('submit', this.handleNoteSubmitBound);
            noteForm.removeAttribute('data-notes-bound');
        }
        
        // Remove other event listeners
        ['noteContent', 'notesSearch', 'notesAuthorFilter', 'notesDateFilter', 'noteAttachments'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.removeAttribute('data-notes-bound');
            }
        });
        
        const notesContainer = document.getElementById('notesList');
        if (notesContainer) {
            notesContainer.removeAttribute('data-scroll-bound');
        }
    }
    
    async handleNoteSubmit(e) {
        e.preventDefault();
        
        if (this.submittingNote) {
            console.log('Note submission already in progress, ignoring...');
            return;
        }
        
        const now = Date.now();
        if (now - this.lastSubmitTime < 1000) {
            console.log('Preventing rapid submission...');
            return;
        }
        
        this.lastSubmitTime = now;
        this.submittingNote = true;
        
        const content = document.getElementById('noteContent').value.trim();
        const attachments = document.getElementById('noteAttachments').files;
        const submitButton = e.target.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
        if (!content) {
            this.showAlert(internalNotesTranslations.noteContentRequired, 'warning');
            this.submittingNote = false;
            return;
        }
        
        try {
            const formData = new FormData();
            formData.append('order_id', this.orderId);
            formData.append('content', content);
            
            // Add file attachments
            for (let i = 0; i < attachments.length; i++) {
                formData.append('attachments[]', attachments[i]);
            }
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i> ' + internalNotesTranslations.addingNote;
            
            const response = await fetch(`<?= base_url('car-wash-notes/create') ?>`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                const notePreview = content.length > 30 ? content.substring(0, 30) + '...' : content;
                this.showAlert(`${internalNotesTranslations.noteAddedSuccessfully}: "${notePreview}"`, 'success');
                
                // Clear form
                document.getElementById('noteContent').value = '';
                document.getElementById('noteAttachments').value = '';
                this.updateAttachmentCount({ target: { files: [] } });
                
                // Reload notes
                this.resetNotesAndReload();
                
                // Reload recent activities to show the new note activity
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(result.message || internalNotesTranslations.failedToAddNote);
            }
        } catch (error) {
            console.error('Error submitting note:', error);
            this.showAlert(internalNotesTranslations.errorAddingNote + ': ' + error.message, 'error');
        } finally {
            this.submittingNote = false;
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    }
    
    resetNotesAndReload() {
        this.currentPage = 1;
        this.hasMore = true;
        this.loadedNotes = [];
        this.loadNotes();
        this.loadTeamActivity(); // Reload activities when notes change
    }
    
    async loadNotes(reset = true) {
        if (this.loadingNotes) {
            console.log('loadNotes: Already loading, skipping...');
            return;
        }
        
        this.loadingNotes = true;
        const notesList = document.getElementById('notesList');
        
        if (reset) {
            this.currentPage = 1;
            this.hasMore = true;
            this.loadedNotes = [];
            notesList.innerHTML = `
                <div class="text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Loading internal notes...</p>
                </div>
            `;
        }
        
        try {
            const params = new URLSearchParams({
                page: this.currentPage,
                limit: 5,
                search: document.getElementById('notesSearch')?.value || '',
                author_id: document.getElementById('notesAuthorFilter')?.value || '',
                date_filter: document.getElementById('notesDateFilter')?.value || ''
            });
            
            const response = await fetch(`<?= base_url('car-wash-notes/order/') ?>${this.orderId}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                const notes = result.data || [];
                this.hasMore = result.pagination.has_more;
                
                if (reset) {
                    this.loadedNotes = notes;
                    this.renderNotes(notes);
                } else {
                    this.loadedNotes = [...this.loadedNotes, ...notes];
                    this.appendNotes(notes);
                }
                
                this.updateNotesCount(result.pagination.total_notes);
                this.currentPage++;
                
                // If no more notes to load, hide the infinite scroll loader
                if (!this.hasMore) {
                    this.hideInfiniteScrollLoader();
                }
            } else {
                throw new Error(result.message || internalNotesTranslations.failedToLoadNotes);
            }
        } catch (error) {
            console.error('Error loading notes:', error);
            this.renderNotesError();
        } finally {
            this.loadingNotes = false;
        }
    }
    
    renderNotes(notes) {
        const notesList = document.getElementById('notesList');
        
        if (!notes || notes.length === 0) {
            notesList.innerHTML = `
                <div class="text-center py-4">
                    <i data-feather="edit-3" class="icon-lg text-muted mb-3"></i>
                    <h6>${internalNotesTranslations.noInternalNotesYet}</h6>
                    <p>${internalNotesTranslations.beFirstAddNote}</p>
                </div>
            `;
            feather.replace();
            return;
        }
        
        let html = '';
        notes.forEach(note => {
            html += this.createNoteHtml(note);
        });
        
        // Add manual load more button as fallback (only if there are more notes)
        if (this.hasMore) {
            html += `
                <div class="text-center py-3" id="loadMoreContainer">
                    <button class="btn btn-outline-primary btn-sm" onclick="internalNotes.loadMoreNotes()">
                        <i data-feather="chevron-down" class="icon-xs me-1"></i>
                        Load More Notes
                    </button>
                    <p class="text-muted mt-2 mb-0 small">
                        <i data-feather="info" class="icon-xs me-1"></i>
                        Scroll to bottom to load more automatically
                    </p>
                </div>
            `;
        }
        
        notesList.innerHTML = html;
        feather.replace();
    }
    
    appendNotes(notes) {
        const notesList = document.getElementById('notesList');
        
        // Remove existing loader
        this.hideInfiniteScrollLoader();
        
        // Remove existing load more button
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        if (loadMoreContainer) {
            loadMoreContainer.remove();
        }
        
        let html = '';
        notes.forEach(note => {
            html += this.createNoteHtml(note);
        });
        
        // Add manual load more button as fallback (only if there are more notes)
        if (this.hasMore) {
            html += `
                <div class="text-center py-3" id="loadMoreContainer">
                    <button class="btn btn-outline-primary btn-sm" onclick="internalNotes.loadMoreNotes()">
                        <i data-feather="chevron-down" class="icon-xs me-1"></i>
                        Load More Notes
                    </button>
                    <p class="text-muted mt-2 mb-0 small">
                        <i data-feather="info" class="icon-xs me-1"></i>
                        Scroll to bottom to load more automatically
                    </p>
                </div>
            `;
        }
        
        notesList.insertAdjacentHTML('beforeend', html);
        feather.replace();
    }
    
    loadMoreNotes() {
        if (this.hasMore && !this.loadingNotes) {
            this.loadNotes(false);
        }
    }
    
    showInfiniteScrollLoader() {
        const notesList = document.getElementById('notesList');
        const existingLoader = document.getElementById('infiniteScrollLoader');
        
        // Remove existing loader if present
        if (existingLoader) {
            existingLoader.remove();
        }
        
        // Remove existing load more button if present
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        if (loadMoreContainer) {
            loadMoreContainer.remove();
        }
        
        // Add infinite scroll loader
        const loaderHtml = `
            <div id="infiniteScrollLoader" class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Loading more notes...</p>
            </div>
        `;
        
        notesList.insertAdjacentHTML('beforeend', loaderHtml);
    }
    
    hideInfiniteScrollLoader() {
        const loader = document.getElementById('infiniteScrollLoader');
        if (loader) {
            loader.remove();
        }
    }
    
    createNoteHtml(note) {
        const avatar = note.avatar || this.getAvatarUrl({ first_name: note.first_name, last_name: note.last_name });
        const canEdit = this.canEditNote(note);
        const canDelete = this.canDeleteNote(note);
        
        let attachmentsHtml = '';
        if (note.attachments && note.attachments.length > 0) {
            attachmentsHtml = `
                <div class="note-attachments">
                    <div class="note-attachments-title">
                        <i data-feather="paperclip" class="icon-xs me-1"></i>
                        Attachments (${note.attachments.length})
                    </div>
                    <div class="note-attachment-list">
                        ${note.attachments.map(attachment => `
                            <div class="note-attachment-item">
                                <div class="attachment-thumbnail">
                                    ${this.getFileExtension(attachment.original_name).toUpperCase()}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="attachment-name">${attachment.original_name}</div>
                                    <div class="attachment-size text-muted">${this.formatFileSize(attachment.size)}</div>
                                </div>
                                <a href="${attachment.url}" class="btn btn-sm btn-outline-primary" download>
                                    <i data-feather="download" class="icon-xs"></i>
                                </a>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }
        
        return `
            <div class="note-item" data-note-id="${note.id}">
                <div class="note-header">
                    <div class="note-author">
                        <img src="${avatar}" alt="${note.author_name}" class="note-avatar">
                        <div class="note-author-info">
                            <h6 class="note-author-name">${note.author_name}</h6>
                            <p class="note-timestamp">${note.created_at_relative}</p>
                        </div>
                    </div>
                    <div class="note-actions">
                        <button class="note-action-btn reply" onclick="internalNotes.toggleReplyForm(${note.id})">
                            <i data-feather="corner-down-right" class="icon-xs me-1"></i>Reply
                        </button>
                        ${canEdit ? `
                        <button class="note-action-btn edit" onclick="internalNotes.editNote(${note.id})">
                            <i data-feather="edit-2" class="icon-xs me-1"></i>${internalNotesTranslations.editNote}
                        </button>` : ''}
                        ${canDelete ? `
                        <button class="note-action-btn delete" onclick="internalNotes.deleteNote(${note.id})">
                            <i data-feather="trash-2" class="icon-xs me-1"></i>${internalNotesTranslations.deleteNote}
                        </button>` : ''}
                    </div>
                </div>
                <div class="note-content" id="noteContent_${note.id}">
                    ${note.content_processed}
                </div>
                ${attachmentsHtml}
                
                <!-- Replies Section -->
                <div class="note-replies" id="noteReplies_${note.id}">
                    ${note.replies ? note.replies.map(reply => this.createReplyHtml(reply, note.id)).join('') : ''}
                </div>
                
                <!-- Reply Form (Hidden by default) -->
                <div class="reply-form" id="replyForm_${note.id}" style="display: none;">
                    <form onsubmit="return false;">
                        <div class="position-relative mb-2">
                            <textarea class="form-control form-control-sm" rows="2" placeholder="Write your reply..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-primary" onclick="internalNotes.submitReply(${note.id})">
                                <i data-feather="send" class="icon-xs me-1"></i>Reply
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelReply(${note.id})">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        `;
    }
    
    createReplyHtml(reply, parentNoteId) {
        const avatar = reply.avatar || this.getAvatarUrl({ first_name: reply.first_name, last_name: reply.last_name });
        const canEdit = this.canEditNote(reply);
        const canDelete = this.canDeleteNote(reply);
        
        return `
            <div class="reply-item" data-reply-id="${reply.id}">
                <div class="d-flex">
                    <img src="${avatar}" alt="${reply.author_name}" class="reply-avatar me-2" style="width: 24px; height: 24px; border-radius: 50%;">
                    <div class="flex-grow-1">
                        <div class="reply-header d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="reply-author-name" style="font-size: 0.8rem; margin: 0;">${reply.author_name}</h6>
                                <span class="reply-timestamp" style="font-size: 0.7rem; color: #6c757d;">${reply.created_at_relative}</span>
                            </div>
                            <div class="reply-actions" style="opacity: 0; transition: opacity 0.2s;">
                                ${canEdit ? `
                                <button class="reply-action-btn edit" onclick="internalNotes.editReply(${reply.id}, ${parentNoteId})" title="Edit Reply">
                                    <i data-feather="edit-2" class="icon-xs"></i>
                                </button>` : ''}
                                ${canDelete ? `
                                <button class="reply-action-btn delete" onclick="internalNotes.deleteReply(${reply.id}, ${parentNoteId})" title="Delete Reply">
                                    <i data-feather="trash-2" class="icon-xs"></i>
                                </button>` : ''}
                            </div>
                        </div>
                        <div class="reply-content" id="replyContent_${reply.id}" style="font-size: 0.8rem; margin-top: 0.25rem;">
                            ${reply.content_processed}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    canEditNote(note) {
        return this.currentUser && (this.currentUser.id == note.author_id);
    }
    
    canDeleteNote(note) {
        return this.currentUser && (this.currentUser.id == note.author_id);
    }
    
    getAvatarUrl(user, size = 150) {
        if (!user) {
            return this.getDefaultAvatar(size);
        }
        
        // If user has an avatar URL, use it
        if (user.avatar) {
            return user.avatar;
        }
        
        // Generate initials avatar
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
        }
        // Try to get from email
        else if (user.email) {
            initials = user.email.substring(0, 2).toUpperCase();
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
        
        // Use UI Avatars service
        const name = encodeURIComponent(initials);
        return `https://ui-avatars.com/api/?name=${name}&size=${size}&background=${backgroundColor}&color=ffffff&bold=true&format=png`;
    }
    
    getDefaultAvatar(size = 150) {
        return `https://ui-avatars.com/api/?name=U&size=${size}&background=6c757d&color=ffffff&bold=true&format=png`;
    }
    
    async editNote(noteId) {
        try {
            // Cancel any other active edits first
            this.cancelAllActiveEdits();
            
            const noteElement = document.querySelector(`[data-note-id="${noteId}"]`);
            const contentElement = document.getElementById(`noteContent_${noteId}`);
            
            if (!contentElement) {
                console.error(`Note content element not found for note ${noteId}`);
                return;
            }
            
            const currentContent = contentElement.textContent.trim();
        
        // Create edit form
        const editForm = document.createElement('div');
        editForm.className = 'note-edit-form';
        editForm.id = `noteEditForm_${noteId}`;
        editForm.innerHTML = `
            <div class="mb-3">
                <textarea class="form-control" rows="3" required>${currentContent}</textarea>
                <div class="form-text">
                    <i data-feather="edit-3" class="icon-xs me-1"></i>
                    Editing note - press Escape to cancel
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Tip: Use @ to mention team members</small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary" onclick="internalNotes.saveNoteEdit(${noteId})">
                        <i data-feather="save" class="icon-xs me-1"></i>Save
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelNoteEdit(${noteId})">
                        Cancel
                    </button>
                </div>
            </div>
        `;
        
        // Hide original content and insert edit form
        contentElement.style.display = 'none';
        contentElement.parentNode.insertBefore(editForm, contentElement.nextSibling);
        
        // Focus on textarea and add keyboard shortcuts
        const textarea = editForm.querySelector('textarea');
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        
        // Add escape key handler
        textarea.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.cancelNoteEdit(noteId);
            } else if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                this.saveNoteEdit(noteId);
            }
        });
        
        feather.replace();
        } catch (error) {
            console.error('Error editing note:', error);
            this.showAlert('Error opening note editor', 'error');
        }
    }
    
    async saveNoteEdit(noteId) {
        const editForm = document.getElementById(`noteEditForm_${noteId}`);
        if (!editForm) {
            console.error(`Edit form not found for note ${noteId}`);
            return;
        }
        
        const textarea = editForm.querySelector('textarea');
        if (!textarea) {
            console.error(`Textarea not found in edit form for note ${noteId}`);
            return;
        }
        
        const content = textarea.value.trim();
        
        if (!content) {
            this.showAlert('Note content cannot be empty', 'warning');
            textarea.focus();
            return;
        }
        
        const saveButton = editForm.querySelector('.btn-primary');
        const cancelButton = editForm.querySelector('.btn-secondary');
        const originalText = saveButton.innerHTML;
        
        try {
            // Disable both buttons and show loading state
            saveButton.disabled = true;
            cancelButton.disabled = true;
            saveButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i>Saving...';
            
            // Add visual feedback to the form
            editForm.style.opacity = '0.7';
            textarea.disabled = true;
            
            const formData = new FormData();
            formData.append('content', content);
            
            const response = await fetch(`<?= base_url('car-wash-notes/update/') ?>${noteId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Show success state briefly before reloading
                saveButton.innerHTML = '<i data-feather="check" class="icon-xs me-1"></i>Saved!';
                saveButton.className = 'btn btn-sm btn-success';
                
                setTimeout(() => {
                    this.showAlert('Note updated successfully', 'success');
                    this.resetNotesAndReload();
                    
                    // Reload recent activities to show the update activity
                    if (typeof loadRecentActivity === 'function') {
                        loadRecentActivity(true);
                    }
                }, 800);
            } else {
                throw new Error(result.message || 'Failed to update note');
            }
        } catch (error) {
            console.error('Error updating note:', error);
            this.showAlert('Error updating note: ' + error.message, 'error');
            
            // Restore form state on error
            editForm.style.opacity = '1';
            textarea.disabled = false;
            saveButton.disabled = false;
            cancelButton.disabled = false;
            saveButton.innerHTML = originalText;
            saveButton.className = 'btn btn-sm btn-primary';
            feather.replace();
        }
    }
    
    cancelAllActiveEdits() {
        // Cancel all note edits
        document.querySelectorAll('.note-edit-form').forEach(form => {
            const noteId = form.id.replace('noteEditForm_', '');
            if (noteId) {
                this.cancelNoteEdit(noteId);
            }
        });
        
        // Cancel all reply edits
        document.querySelectorAll('.reply-edit-form').forEach(form => {
            const replyId = form.id.replace('replyEditForm_', '');
            if (replyId) {
                this.cancelReplyEdit(replyId);
            }
        });
        
        // Hide all reply forms
        document.querySelectorAll('.reply-form').forEach(form => {
            if (form) {
                form.style.display = 'none';
                const textarea = form.querySelector('textarea');
                if (textarea) {
                    textarea.value = '';
                }
            }
        });
    }
    
    cancelNoteEdit(noteId) {
        try {
            const editForm = document.getElementById(`noteEditForm_${noteId}`);
            const contentElement = document.getElementById(`noteContent_${noteId}`);
            
            if (editForm) {
                editForm.remove();
            }
            
            if (contentElement) {
                contentElement.style.display = 'block';
            }
        } catch (error) {
            console.error('Error canceling note edit:', error);
        }
    }
    
    async editReply(replyId, noteId) {
        try {
            // Cancel any other active edits first
            this.cancelAllActiveEdits();
            
            const replyElement = document.querySelector(`[data-reply-id="${replyId}"]`);
            const contentElement = document.getElementById(`replyContent_${replyId}`);
            
            if (!contentElement) {
                console.error(`Reply content element not found for reply ${replyId}`);
                return;
            }
            
            const currentContent = contentElement.textContent.trim();
        
        // Create edit form
        const editForm = document.createElement('div');
        editForm.className = 'reply-edit-form';
        editForm.id = `replyEditForm_${replyId}`;
        editForm.innerHTML = `
            <div class="mb-2">
                <textarea class="form-control form-control-sm" rows="2" required>${currentContent}</textarea>
                <div class="form-text">
                    <i data-feather="edit-3" class="icon-xs me-1"></i>
                    Editing reply - Esc to cancel, Ctrl+Enter to save
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Quick edit</small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary" onclick="internalNotes.saveReplyEdit(${replyId}, ${noteId})">
                        <i data-feather="save" class="icon-xs me-1"></i>Save
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelReplyEdit(${replyId})">
                        Cancel
                    </button>
                </div>
            </div>
        `;
        
        // Hide original content and insert edit form
        contentElement.style.display = 'none';
        const replyContainer = contentElement.parentNode;
        replyContainer.appendChild(editForm);
        
        // Focus on textarea and add keyboard shortcuts
        const textarea = editForm.querySelector('textarea');
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        
        // Add keyboard shortcuts
        textarea.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.cancelReplyEdit(replyId);
            } else if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                this.saveReplyEdit(replyId, noteId);
            }
        });
        
        feather.replace();
        } catch (error) {
            console.error('Error editing reply:', error);
            this.showAlert('Error opening reply editor', 'error');
        }
    }
    
    async saveReplyEdit(replyId, noteId) {
        const editForm = document.getElementById(`replyEditForm_${replyId}`);
        if (!editForm) {
            console.error(`Edit form not found for reply ${replyId}`);
            return;
        }
        
        const textarea = editForm.querySelector('textarea');
        if (!textarea) {
            console.error(`Textarea not found in edit form for reply ${replyId}`);
            return;
        }
        
        const content = textarea.value.trim();
        
        if (!content) {
            this.showAlert('Reply content cannot be empty', 'warning');
            textarea.focus();
            return;
        }
        
        const saveButton = editForm.querySelector('.btn-primary');
        const cancelButton = editForm.querySelector('.btn-secondary');
        const originalText = saveButton.innerHTML;
        
        try {
            // Disable both buttons and show loading state
            saveButton.disabled = true;
            cancelButton.disabled = true;
            saveButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i>Saving...';
            
            // Add visual feedback to the form
            editForm.style.opacity = '0.7';
            textarea.disabled = true;
            
            const formData = new FormData();
            formData.append('content', content);
            
            const response = await fetch(`<?= base_url('car-wash-notes/update/') ?>${replyId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Show success state briefly before reloading
                saveButton.innerHTML = '<i data-feather="check" class="icon-xs me-1"></i>Saved!';
                saveButton.className = 'btn btn-sm btn-success';
                
                setTimeout(() => {
                    this.showAlert('Reply updated successfully', 'success');
                    this.resetNotesAndReload();
                    
                    // Reload recent activities to show the update activity
                    if (typeof loadRecentActivity === 'function') {
                        loadRecentActivity(true);
                    }
                }, 800);
            } else {
                throw new Error(result.message || 'Failed to update reply');
            }
        } catch (error) {
            console.error('Error updating reply:', error);
            this.showAlert('Error updating reply: ' + error.message, 'error');
            
            // Restore form state on error
            editForm.style.opacity = '1';
            textarea.disabled = false;
            saveButton.disabled = false;
            cancelButton.disabled = false;
            saveButton.innerHTML = originalText;
            saveButton.className = 'btn btn-sm btn-primary';
            feather.replace();
        }
    }
    
    cancelReplyEdit(replyId) {
        try {
            const editForm = document.getElementById(`replyEditForm_${replyId}`);
            const contentElement = document.getElementById(`replyContent_${replyId}`);
            
            if (editForm) {
                editForm.remove();
            }
            
            if (contentElement) {
                contentElement.style.display = 'block';
            }
        } catch (error) {
            console.error('Error canceling reply edit:', error);
        }
    }
    
    cancelReply(noteId) {
        try {
            const replyForm = document.getElementById(`replyForm_${noteId}`);
            if (replyForm) {
                replyForm.style.display = 'none';
                const textarea = replyForm.querySelector('textarea');
                if (textarea) {
                    textarea.value = '';
                }
            }
        } catch (error) {
            console.error('Error canceling reply:', error);
        }
    }
    
    async deleteReply(replyId, noteId) {
        const result = await Swal.fire({
            title: 'Delete Reply?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        });
        
        if (!result.isConfirmed) return;
        
        try {
            const response = await fetch(`<?= base_url('car-wash-notes/delete/') ?>${replyId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const deleteResult = await response.json();
            
            if (deleteResult.success) {
                this.showAlert('Reply deleted successfully', 'success');
                
                // Remove reply from DOM
                const replyElement = document.querySelector(`[data-reply-id="${replyId}"]`);
                if (replyElement) {
                    replyElement.remove();
                }
                
                // Reload recent activities to show the delete activity
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(deleteResult.message || 'Failed to delete reply');
            }
        } catch (error) {
            console.error('Error deleting reply:', error);
            this.showAlert('Error deleting reply: ' + error.message, 'error');
        }
    }
    
    renderNotesError() {
        const notesList = document.getElementById('notesList');
        notesList.innerHTML = `
            <div class="text-center py-4">
                <i data-feather="alert-triangle" class="icon-lg text-danger mb-3"></i>
                <h6>${internalNotesTranslations.errorLoadingNotes}</h6>
                <p>${internalNotesTranslations.errorLoadingNotesTryAgain}</p>
                <button class="btn btn-sm btn-outline-primary" onclick="internalNotes.loadNotes()">
                    ${internalNotesTranslations.tryAgain}
                </button>
            </div>
        `;
        feather.replace();
    }
    
    updateNotesCount(count) {
        const notesCount = document.getElementById('notesCount');
        if (notesCount) {
            notesCount.textContent = count || 0;
        }
    }
    
    updateAttachmentCount(e) {
        const files = e.target.files;
        const countElement = document.getElementById('noteAttachmentCount');
        
        if (files.length > 0) {
            countElement.textContent = `${files.length} file(s) selected`;
        } else {
            countElement.textContent = '';
        }
    }
    
    handleMentionTyping(e) {
        const content = e.target.value;
        const cursorPosition = e.target.selectionStart;
        
        // Find the last @ symbol before cursor
        const textBeforeCursor = content.substring(0, cursorPosition);
        const lastAtIndex = textBeforeCursor.lastIndexOf('@');
        
        if (lastAtIndex === -1) {
            this.hideMentionSuggestions();
            return;
        }
        
        const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);
        
        // Check if we're in a mention context (no space after @)
        if (textAfterAt.includes(' ')) {
            this.hideMentionSuggestions();
            return;
        }
        
        const query = textAfterAt.toLowerCase();
        const suggestions = this.staffUsers.filter(user => 
            user.username.toLowerCase().includes(query) ||
            user.name.toLowerCase().includes(query)
        ).slice(0, 5);
        
        if (suggestions.length > 0) {
            this.showMentionSuggestions(suggestions, lastAtIndex);
        } else {
            this.hideMentionSuggestions();
        }
    }
    
    showMentionSuggestions(suggestions, atIndex) {
        this.mentionSuggestions = suggestions;
        this.selectedSuggestionIndex = -1;
        this.mentionAtIndex = atIndex;
        
        const suggestionsContainer = document.getElementById('noteMentionSuggestions');
        let html = '';
        
        suggestions.forEach((user, index) => {
            html += `
                <div class="mention-suggestion ${index === this.selectedSuggestionIndex ? 'active' : ''}" 
                     onclick="internalNotes.insertMention('${user.username}', ${atIndex})"
                     data-index="${index}">
                    <strong>@${user.username}</strong> - ${user.name}
                </div>
            `;
        });
        
        suggestionsContainer.innerHTML = html;
        suggestionsContainer.style.display = 'block';
    }
    
    hideMentionSuggestions() {
        const suggestionsContainer = document.getElementById('noteMentionSuggestions');
        suggestionsContainer.style.display = 'none';
        this.mentionSuggestions = [];
        this.selectedSuggestionIndex = -1;
    }
    
    handleMentionNavigation(e) {
        if (this.mentionSuggestions.length === 0) return;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.selectedSuggestionIndex = Math.min(
                this.selectedSuggestionIndex + 1, 
                this.mentionSuggestions.length - 1
            );
            this.updateSuggestionHighlight();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.selectedSuggestionIndex = Math.max(this.selectedSuggestionIndex - 1, 0);
            this.updateSuggestionHighlight();
        } else if (e.key === 'Enter' && this.selectedSuggestionIndex >= 0) {
            e.preventDefault();
            const selectedUser = this.mentionSuggestions[this.selectedSuggestionIndex];
            this.insertMention(selectedUser.username, this.mentionAtIndex);
        } else if (e.key === 'Escape') {
            this.hideMentionSuggestions();
        }
    }
    
    updateSuggestionHighlight() {
        const suggestions = document.querySelectorAll('#noteMentionSuggestions .mention-suggestion');
        suggestions.forEach((suggestion, index) => {
            suggestion.classList.toggle('active', index === this.selectedSuggestionIndex);
        });
    }
    
    insertMention(username, atIndex) {
        const noteContent = document.getElementById('noteContent');
        const content = noteContent.value;
        const cursorPosition = noteContent.selectionStart;
        
        // Find the end of the current mention text
        const textAfterAt = content.substring(atIndex + 1, cursorPosition);
        const mentionEnd = atIndex + 1 + textAfterAt.length;
        
        // Replace the mention text
        const newContent = content.substring(0, atIndex) + 
                          `@${username} ` + 
                          content.substring(mentionEnd);
        
        noteContent.value = newContent;
        
        // Set cursor position after the mention
        const newCursorPosition = atIndex + username.length + 2;
        noteContent.setSelectionRange(newCursorPosition, newCursorPosition);
        
        this.hideMentionSuggestions();
        noteContent.focus();
    }
    
    filterNotes() {
        // Reset to first page when filtering
        this.currentPage = 1;
        this.hasMore = true;
        this.loadedNotes = [];
        this.loadNotes();
    }
    
    handleTabSwitch(e) {
        const targetId = e.target.getAttribute('data-bs-target');
        
        switch (targetId) {
            case '#notes-pane':
                if (this.loadedNotes.length === 0) {
                    this.loadNotes();
                }
                break;
            case '#mentions-pane':
                this.loadMentions();
                break;
            case '#team-pane':
                this.loadTeamActivity();
                break;
        }
    }
    
    handleNotesScroll(e) {
        const container = e.target;
        const { scrollTop, scrollHeight, clientHeight } = container;
        
        // Check if we're near the bottom (within 100px for better UX)
        const nearBottom = scrollTop + clientHeight >= scrollHeight - 100;
        
        if (nearBottom && !this.loadingNotes && this.hasMore) {
            console.log('Near bottom of notes, loading more...');
            this.showInfiniteScrollLoader();
            this.loadMoreNotes();
        }
    }
    
    toggleReplyForm(noteId) {
        // Cancel any active edits first
        this.cancelAllActiveEdits();
        
        const replyForm = document.getElementById(`replyForm_${noteId}`);
        if (!replyForm) {
            console.error(`Reply form not found for note ${noteId}`);
            return;
        }
        
        const isVisible = replyForm.style.display !== 'none';
        
        // Hide all other reply forms
        document.querySelectorAll('.reply-form').forEach(form => {
            if (form) {
                form.style.display = 'none';
                const textarea = form.querySelector('textarea');
                if (textarea) {
                    textarea.value = '';
                }
            }
        });
        
        if (!isVisible) {
            replyForm.style.display = 'block';
            const textarea = replyForm.querySelector('textarea');
            if (textarea) {
                textarea.focus();
                
                // Add keyboard shortcut for quick reply
                textarea.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.cancelReply(noteId);
                    } else if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                        this.submitReply(noteId);
                    }
                }, { once: true });
            }
        }
    }
    
    cancelReply(noteId) {
        const replyForm = document.getElementById(`replyForm_${noteId}`);
        replyForm.style.display = 'none';
        replyForm.querySelector('textarea').value = '';
    }
    
    async submitReply(noteId) {
        const replyForm = document.getElementById(`replyForm_${noteId}`);
        const textarea = replyForm.querySelector('textarea');
        const content = textarea.value.trim();
        
        if (!content) return;
        
        const submitButton = replyForm.querySelector('button[type="button"]');
        const originalText = submitButton.innerHTML;
        
        try {
            const formData = new FormData();
            formData.append('parent_note_id', noteId);
            formData.append('order_id', this.orderId);
            formData.append('content', content);
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i>Sending...';
            
            const response = await fetch(`<?= base_url('car-wash-notes/add-reply') ?>`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                const replyPreview = content.length > 30 ? content.substring(0, 30) + '...' : content;
                this.showAlert(`Reply added successfully: "${replyPreview}"`, 'success');
                
                // Clear form and hide
                textarea.value = '';
                replyForm.style.display = 'none';
                
                // Reload notes to show the new reply
                this.resetNotesAndReload();
                
                // Reload recent activities to show the reply activity
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(result.message || 'Failed to add reply');
            }
        } catch (error) {
            console.error('Error adding reply:', error);
            this.showAlert('Error adding reply: ' + error.message, 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
            feather.replace();
        }
    }
    
    async loadMentions() {
        const mentionsList = document.getElementById('mentionsList');
        mentionsList.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Loading mentions...</p>
            </div>
        `;
        
        try {
            const response = await fetch(`<?= base_url('car-wash-notes/unread-mentions') ?>`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                this.renderMentions(result.data || []);
                this.updateMentionsCount(result.data ? result.data.length : 0);
            } else {
                mentionsList.innerHTML = '<p class="text-center text-muted">Error loading mentions</p>';
            }
        } catch (error) {
            console.error('Error loading mentions:', error);
            mentionsList.innerHTML = '<p class="text-center text-muted">Error loading mentions</p>';
        }
    }
    
    renderMentions(mentions) {
        const mentionsList = document.getElementById('mentionsList');
        
        if (!mentions || mentions.length === 0) {
            mentionsList.innerHTML = `
                <div class="text-center py-4">
                    <i data-feather="at-sign" class="icon-lg text-muted mb-3"></i>
                    <h6>No Mentions</h6>
                    <p class="text-muted">You'll see mentions here when someone mentions you in notes</p>
                </div>
            `;
            feather.replace();
            return;
        }
        
        let html = '';
        mentions.forEach(mention => {
            html += `
                <div class="mention-alert ${mention.is_read ? 'read' : 'unread'}">
                    <div class="mention-content">
                        <div class="mention-header">
                            <strong>${mention.author_name}</strong> mentioned you
                            <span class="mention-time">${mention.created_at_relative}</span>
                        </div>
                        <div class="mention-note-preview">${mention.content_preview}</div>
                    </div>
                    <div class="mention-actions">
                        <button class="mention-alert-action" onclick="internalNotes.markMentionRead(${mention.note_id})">
                            Mark as Read
                        </button>
                    </div>
                </div>
            `;
        });
        
        mentionsList.innerHTML = html;
    }
    
    updateMentionsCount(count) {
        const mentionsCount = document.getElementById('mentionsCount');
        if (mentionsCount) {
            mentionsCount.textContent = count || 0;
        }
    }
    
    async loadTeamActivity() {
        const teamActivityList = document.getElementById('teamActivityList');
        
        // Show loading state
        teamActivityList.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Loading activities...</p>
            </div>
        `;
        
        try {
            // Load activities using the CarWash activity endpoint
                            const response = await fetch(`<?= base_url('car_wash/getActivity/') ?>${this.orderId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success && result.activities && result.activities.length > 0) {
                let html = '';
                result.activities.forEach(activity => {
                    html += createActivityHtml(activity);
                });
                teamActivityList.innerHTML = html;
                
                // Initialize tooltips and feather icons
                feather.replace();
                initializeActivityTooltips();
            } else {
                teamActivityList.innerHTML = `
                    <div class="text-center py-4">
                        <i data-feather="activity" class="icon-lg text-muted mb-3"></i>
                        <h6>No Activity Yet</h6>
                        <p class="text-muted">Activity will appear here as changes are made</p>
                    </div>
                `;
                feather.replace();
            }
        } catch (error) {
            console.error('Error loading team activity:', error);
            teamActivityList.innerHTML = `
                <div class="text-center py-4">
                    <i data-feather="alert-circle" class="icon-lg text-danger mb-3"></i>
                    <h6>Error Loading Activities</h6>
                    <p class="text-muted">Unable to load recent activities</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="internalNotes.loadTeamActivity()">
                        <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                        Try Again
                    </button>
                </div>
            `;
            feather.replace();
        }
    }
    
    async deleteNote(noteId) {
        const result = await Swal.fire({
            title: 'Delete Note?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        });
        
        if (!result.isConfirmed) return;
        
        try {
            const response = await fetch(`<?= base_url('car-wash-notes/delete/') ?>${noteId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            const deleteResult = await response.json();
            
            if (deleteResult.success) {
                this.showAlert('Note deleted successfully', 'success');
                
                // Remove note from DOM
                const noteElement = document.querySelector(`[data-note-id="${noteId}"]`);
                if (noteElement) {
                    noteElement.remove();
                }
                
                // Update count
                this.updateNotesCount(this.loadedNotes.length - 1);
                
                // Remove from loaded notes array
                this.loadedNotes = this.loadedNotes.filter(note => note.id != noteId);
                
                // Reload recent activities to show the delete activity
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // If no more notes visible, reload
                if (this.loadedNotes.length === 0) {
                    this.resetNotesAndReload();
                }
            } else {
                throw new Error(deleteResult.message || 'Failed to delete note');
            }
        } catch (error) {
            console.error('Error deleting note:', error);
            this.showAlert('Error deleting note: ' + error.message, 'error');
        }
    }
    
    // Helper functions
    getFileExtension(filename) {
        return filename.split('.').pop() || 'file';
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    showAlert(message, type = 'info') {
        // Use the same alert system as the main page
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
        }
    }
    
    destroy() {
        console.log('InternalNotesSystem: Destroying instance');
        this.isDestroyed = true;
        this.submittingNote = false;
        this.loadingNotes = false;
        
        // Remove all event listeners properly
        this.unbindEvents();
        
        // Clear data arrays
        this.loadedNotes = [];
        this.notesData = [];
        this.mentionsData = [];
        this.staffUsers = [];
        this.mentionSuggestions = [];
        
        // Clear global reference
        if (window.internalNotesInstance === this) {
            window.internalNotesInstance = null;
        }
    }
}

// Add translation object for Internal Notes JavaScript
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
    filesSelected: ' file(s) selected',
    failedToAddNote: 'Failed to add note',
    failedToDeleteNote: 'Failed to delete note',
    failedToLoadNotes: 'Failed to load notes'
};

// Initialize Internal Notes System for staff and admin users only
let internalNotes;
let internalNotesInitialized = false;

// Comments functionality with pagination support
let commentsPagination = {
    currentPage: 1,
    totalPages: 1,
    loading: false,
    hasMore: true
};

// Initialize comments scroll handler
function initializeCommentsScrollHandler() {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) {
        console.warn('Comments list element not found');
        return;
    }
    
    // Remove existing listener to prevent duplicates
    commentsList.removeEventListener('scroll', commentsScrollHandler);
    
    // Add new listener
    commentsList.addEventListener('scroll', commentsScrollHandler);
    
    const computedStyle = window.getComputedStyle(commentsList);
    const maxHeight = parseInt(computedStyle.maxHeight);
    const hasScroll = commentsList.scrollHeight > commentsList.clientHeight;
    
    console.log('Comments list scrollable:', hasScroll, 'Height:', commentsList.scrollHeight, 'Client height:', commentsList.clientHeight);
    
    // If content doesn't fill the container, try to load more
    if (commentsList.scrollHeight <= commentsList.clientHeight) {
        console.log('Comments list not scrollable, loading more...');
        // Small delay to prevent immediate loading
        setTimeout(() => {
            loadMoreComments();
        }, 100);
    }
}

function commentsScrollHandler() {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;
    
    const { scrollTop, scrollHeight, clientHeight } = commentsList;
    
    // Check if we're near the bottom (within 50px)
    const nearBottom = scrollTop + clientHeight >= scrollHeight - 50;
    
    if (nearBottom && !commentsPagination.loading && commentsPagination.hasMore) {
        console.log('Near bottom of comments, loading more...');
        loadMoreComments();
    }
}

function loadMoreComments() {
    if (commentsPagination.loading || !commentsPagination.hasMore) {
        return;
    }
    
    const commentsList = document.getElementById('commentsList');
    
    // Add loading indicator at the bottom
    const loader = document.createElement('div');
    loader.className = 'comments-loading';
    loader.innerHTML = `
        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
        <span class="text-muted">Loading more comments...</span>
    `;
    loader.id = 'commentsLoader';
    
    // Remove existing loader if any
    const existingLoader = document.getElementById('commentsLoader');
    if (existingLoader) {
        existingLoader.remove();
    }
    
    commentsList.appendChild(loader);
    
    // Load next page
    commentsPagination.currentPage += 1;
    loadComments(false); // false = append mode
}

// Enhanced loadComments function with replies, mentions, and attachments support
function loadComments(reset = true) {
    const orderId = <?= $order['id'] ?? 0 ?>;
    const commentsList = document.getElementById('commentsList');
    
    console.log(`🔄 loadComments called with reset=${reset}, currentPage=${commentsPagination.currentPage}, loading=${commentsPagination.loading}`);
    
    if (commentsPagination.loading) {
        console.log('Already loading comments, skipping...');
        return;
    }
    
    commentsPagination.loading = true;
    
    if (reset) {
        commentsPagination.currentPage = 1;
        commentsPagination.hasMore = true;
        commentsList.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                <p class="text-muted mt-2 mb-0">Loading comments...</p>
            </div>
        `;
    }
    
    const url = `<?= base_url('car_wash/getComments/') ?>${orderId}?page=${commentsPagination.currentPage}&per_page=10`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequset'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Remove loading indicator
        const loader = document.getElementById('commentsLoader');
        if (loader) {
            loader.remove();
        }
        
        if (reset) {
            commentsList.innerHTML = '';
        }
        
        if (data.success) {
            const comments = data.comments || [];
            commentsPagination.totalPages = data.total_pages || 1;
            commentsPagination.hasMore = commentsPagination.currentPage < commentsPagination.totalPages;
            
            console.log(`📄 Loaded ${comments.length} comments, page ${commentsPagination.currentPage}/${commentsPagination.totalPages}`);
            
            if (comments.length > 0) {
                comments.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.innerHTML = createCommentHtml(comment);
                    commentsList.appendChild(commentElement.firstElementChild);
                });
                
                // Update comments count
                updateCommentsCount(data.total_comments || comments.length);
                
                // Initialize scroll handler
                initializeCommentsScrollHandler();
            } else if (reset) {
                commentsList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="message-circle" class="icon-lg text-muted mb-2"></i>
                        <p class="text-muted mb-0">No comments yet</p>
                        <small class="text-muted">Be the first to add a comment!</small>
                </div>
                `;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        } else {
            if (reset) {
                commentsList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                        <p class="text-muted mb-0">Error loading comments</p>
                        <small class="text-muted">${data.message || 'Please try again'}</small>
            </div>
                `;
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
        
        // Remove loading indicator
        const loader = document.getElementById('commentsLoader');
        if (loader) {
            loader.remove();
        }
        
        if (reset) {
            commentsList.innerHTML = `
                <div class="text-center py-3">
                    <i data-feather="wifi-off" class="icon-lg text-danger mb-2"></i>
                    <p class="text-muted mb-0">Connection error</p>
                    <small class="text-muted">Please check your internet connection</small>
        </div>
            `;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    })
    .finally(() => {
        commentsPagination.loading = false;
        
        // Re-initialize feather icons for any new elements
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}

// Update comments count badge
function updateCommentsCount(count) {
    const badge = document.getElementById('commentsCount');
    if (badge) {
        badge.textContent = count || 0;
    }
}

// Create comment HTML with enhanced structure
function createCommentHtml(comment) {
    // Use various name fields available
    const authorName = comment.author_name || comment.user_name || 
                      (comment.first_name && comment.last_name ? `${comment.first_name} ${comment.last_name}` : 'Anonymous');
    
    // Escape HTML to prevent XSS and convert line breaks to <br> tags
    const commentText = comment.comment || comment.description || comment.content || '';
    const escapedComment = escapeHtml(commentText).replace(/\n/g, '<br>');
    
    // Generate avatar URL
    const avatarUrl = comment.avatar_url || comment.avatar || generateDefaultAvatar(authorName);
    
    // Format timestamp
    const timestamp = comment.created_at_formatted || comment.created_at;
    const relativeTime = comment.created_at_relative || 'recently';
    
    // Process attachments
    let attachmentsHtml = '';
    // console.log('💾 Comment attachments for comment', comment.id, ':', comment.attachments);
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
    // console.log('📎 Creating attachment HTML for:', attachment);
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
                    ${attachment.optimized ? '<span class="badge bg-success-subtle text-success ms-1" title="Optimized with TinyPNG"><i class="fas fa-compress-arrows-alt"></i></span>' : ''}
        </div>
                <span class="attachment-size">${formatFileSize(attachment.size || 0)}</span>
    </div>
            <div class="attachment-actions">
                ${canView ? `<a href="${attachment.url}" class="btn btn-sm btn-outline-primary me-1" target="_blank" title="View in browser">
                    <i data-feather="eye" class="icon-xs"></i>
                </a>` : ''}
                <a href="${attachment.url}" class="btn btn-sm btn-outline-secondary" title="Download" download="${attachment.original_name}">
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
    
    // console.log('🖼️ Getting thumbnail for:', attachment.original_name, 'Extension:', extension, 'Has thumbnail:', !!attachment.thumbnail);
    
    if (imageTypes.includes(extension) && attachment.thumbnail) {
        // For images with thumbnails, show the thumbnail
        // console.log('📸 Using thumbnail:', attachment.thumbnail);
        return `<img src="${attachment.thumbnail}" alt="${attachment.original_name}" class="file-thumbnail-img" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     onload="">
                <div class="file-thumbnail-fallback" style="display: none;">
                    <i class="fas fa-image text-success"></i>
                </div>`;
    } else if (imageTypes.includes(extension)) {
        // For images without thumbnails, try to show the original
        // console.log('📷 Using original image:', attachment.url);
        return `<img src="${attachment.url}" alt="${attachment.original_name}" class="file-thumbnail-img" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     onload="">
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
            'txt': '<i class="fas fa-file-alt text-secondary file-thumbnail-icon"></i>',
            'zip': '<i class="fas fa-file-archive text-warning file-thumbnail-icon"></i>',
            'mp4': '<i class="fas fa-file-video text-danger file-thumbnail-icon"></i>',
            'mp3': '<i class="fas fa-file-audio text-purple file-thumbnail-icon"></i>'
        };
        
        // console.log('📄 Using file type icon for:', extension);
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

// Generate default avatar if none provided
function generateDefaultAvatar(name) {
    if (!name) name = 'User';
    
    // Generate initials from name
    const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    
    // Generate color based on name for consistency
    const colors = [
        '3498db', '9b59b6', 'e74c3c', 'f39c12', 
        '2ecc71', '1abc9c', 'e67e22', 'f1c40f',
        '8e44ad', 'c0392b', 'd35400', '27ae60'
    ];
    
    // Simple hash to get consistent color
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const colorIndex = Math.abs(hash) % colors.length;
    const backgroundColor = colors[colorIndex];
    
    // Use UI Avatars service to generate avatar with initials
    const encodedInitials = encodeURIComponent(initials);
    return `https://ui-avatars.com/api/?name=${encodedInitials}&size=32&background=${backgroundColor}&color=ffffff&bold=true&format=png`;
}

// Escape HTML to prevent XSS
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
    
function editOrder(orderId) {
    // Show the edit modal
    $('#editCarWashModal').modal('show');
    
    // Load order data into the modal
    loadOrderDataForEdit(orderId);
}

function changeStatus(orderId) {
    // Show the change status modal
    $('#changeStatusModal').modal('show');
    
    // Set current status in the modal
    $('#modal_status').val('<?= $order['status'] ?>');
    
    // Store order ID for update
    $('#changeStatusForm').data('order-id', orderId);
}

function generateQR(orderId) {
    console.log('🎯 Generating QR Code for car wash order:', orderId);
    
    // Just show the existing QR modal if we have QR data
    <?php if (isset($qr_data) && $qr_data): ?>
    console.log('📱 Opening existing QR Modal...');
    
    // Get or create modal instance
    const qrModalElement = document.getElementById('qrModal');
    let qrModal = bootstrap.Modal.getInstance(qrModalElement);
    
    if (!qrModal) {
        qrModal = new bootstrap.Modal(qrModalElement, {
            keyboard: true,
            backdrop: true,
            focus: true
        });
    }
    
    // Show modal and initialize feather icons
    qrModal.show();
    
    // Initialize feather icons after modal is shown
    qrModalElement.addEventListener('shown.bs.modal', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, { once: true });
    
    // Clean up event listeners when modal is hidden
    qrModalElement.addEventListener('hidden.bs.modal', function() {
        // Ensure backdrop is removed
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        // Remove any remaining modal classes from body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    }, { once: true });
    
    showToast('success', 'QR Code ready!');
    return;
    <?php endif; ?>
    
    // If no QR data available, show error message
    showToast('warning', 'QR Code not available - Lima Links API not configured');
    console.log('⚠️ No QR data available for car wash order:', orderId);
}

// Show toast notifications
function showToast(type, message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    } else {
        alert(message);
    }
}

// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('❌ Element not found:', elementId);
        return;
    }
    
    // Use modern clipboard API if available
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(element.value).then(() => {
            showToast('success', 'Copied to clipboard!');
            updateCopyButton(element);
        }).catch(err => {
            console.error('❌ Clipboard API failed:', err);
            fallbackCopy(element);
        });
    } else {
        fallbackCopy(element);
    }
}

function fallbackCopy(element) {
    // Fallback for older browsers
    const textarea = document.createElement('textarea');
    textarea.value = element.value;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'Copied to clipboard!');
        updateCopyButton(element);
    } catch (err) {
        console.error('❌ Copy failed:', err);
        showToast('error', 'Failed to copy to clipboard');
    } finally {
        document.body.removeChild(textarea);
    }
}

function updateCopyButton(element) {
    // Visual feedback on button
    const button = element.nextElementSibling;
    if (button) {
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i data-feather="check" class="icon-xs"></i>';
        
        // Re-initialize feather icons immediately
        setTimeout(() => {
            if (typeof feather !== 'undefined') {
                feather.replace();
}
        }, 10);
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }, 2000);
    }
}

// Download QR Code
function downloadQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const qrUrl = '<?= $qr_data['qr_url'] ?>';
    const orderId = <?= $order['id'] ?? 0 ?>;
    
    console.log('💾 Downloading QR Code...');
    
    // Create download link
    const link = document.createElement('a');
    link.href = qrUrl;
    link.download = `car-wash-order-CW-${String(orderId).padStart(5, '0')}-qr.png`;
    
    // Trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showToast('success', 'QR Code downloaded!');
    <?php else: ?>
    showToast('error', 'No QR code to download');
    <?php endif; ?>
}

// Share QR Code (basic implementation)
function shareQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const shortUrl = '<?= $qr_data['short_url'] ?>';
    const orderNumber = 'CW-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    
    if (navigator.share) {
        navigator.share({
            title: `Car Wash Order ${orderNumber}`,
            text: `View car wash order ${orderNumber}`,
            url: shortUrl
        }).then(() => {
            showToast('success', 'Shared successfully!');
        }).catch(() => {
            fallbackShare(shortUrl);
        });
    } else {
        fallbackShare(shortUrl);
    }
    <?php else: ?>
    showToast('error', 'No QR code to share');
    <?php endif; ?>
}

function fallbackShare(url) {
    // Fallback: copy to clipboard
    const textarea = document.createElement('textarea');
    textarea.value = url;
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', 'URL copied to clipboard!');
    } catch (err) {
        showToast('error', 'Unable to share or copy URL');
    } finally {
        document.body.removeChild(textarea);
    }
}

// Regenerate Shortlink
function regenerateShortlink(orderId) {
    console.log('🔄 Regenerating shortlink for car wash order:', orderId);
    
    // Show confirmation dialog
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Regenerar Shortlink?',
            text: 'Esto creará un nuevo enlace corto para esta orden.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, regenerar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                performRegenerateShortlink(orderId);
            }
        });
    } else {
        if (confirm('¿Está seguro que desea regenerar el shortlink?')) {
            performRegenerateShortlink(orderId);
        }
    }
}

function performRegenerateShortlink(orderId) {
    // Show loading state
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Regenerando...',
            text: 'Creando nuevo enlace corto',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    fetch(`<?= base_url('car_wash/regenerateQR/') ?>${orderId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        if (data.success) {
            showToast('success', 'Shortlink regenerado exitosamente!');
            // Reload page to show new QR code and shortlink
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', data.message || 'Error al regenerar shortlink');
        }
    })
    .catch(error => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        console.error('Error regenerating shortlink:', error);
        showToast('error', 'Error de conexión al regenerar shortlink');
    });
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

// Show QR Modal
function showQRModal() {
    <?php if (isset($qr_data) && $qr_data): ?>
    console.log('📱 Opening QR Modal...');
    
    // Get or create modal instance
    const qrModalElement = document.getElementById('qrModal');
    let qrModal = bootstrap.Modal.getInstance(qrModalElement);
    
    if (!qrModal) {
        qrModal = new bootstrap.Modal(qrModalElement, {
            keyboard: true,
            backdrop: true,
            focus: true
        });
    }
    
    // Show modal and initialize feather icons
    qrModal.show();
    
    // Initialize feather icons after modal is shown
    qrModalElement.addEventListener('shown.bs.modal', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, { once: true });
    
    // Clean up event listeners when modal is hidden
    qrModalElement.addEventListener('hidden.bs.modal', function() {
        // Ensure backdrop is removed
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        // Remove any remaining modal classes from body
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    }, { once: true });
    
    <?php else: ?>
    showToast('warning', 'QR Code no disponible');
    <?php endif; ?>
}

// Edit Order Functions
function loadOrderDataForEdit(orderId) {
    // Store order ID for update (data is now pre-populated via HTML)
    $('#editCarWashForm').data('order-id', orderId);
    
    // Debug: Log current form values and available options
    console.log('✅ Edit form loaded with native HTML values:');
    console.log('Order ID:', orderId);
    console.log('Client ID:', $('#edit_client_id').val());
    console.log('Available Clients:', $('#edit_client_id option').length - 1); // -1 for "Select Client"
    console.log('Service ID:', $('#edit_service_id').val());
    console.log('Available Services:', $('#edit_service_id option').length - 1); // -1 for "Select Service"
    console.log('Status:', $('#edit_status').val());
    console.log('Tag/Stock:', $('#edit_tag_stock').val());
    console.log('VIN:', $('#edit_vin_number').val());
    console.log('Vehicle:', $('#edit_vehicle').val());
    
    // Debug: Log service options and selected value
    console.log('Service Options:');
    $('#edit_service_id option').each(function() {
        const isSelected = $(this).prop('selected');
        console.log(`  Option ${$(this).val()}: ${$(this).text()} - Selected: ${isSelected}`);
    });
    
    // Debug: Show order data from PHP
    console.log('Order Data from PHP:', {
        client_id: '<?= esc($order['client_id'] ?? '') ?>',
        service_id: '<?= esc($order['service_id'] ?? '') ?>',
        status: '<?= esc($order['status'] ?? '') ?>',
        tag_stock: '<?= esc($order['tag_stock'] ?? '') ?>',
        vin_number: '<?= esc($order['vin_number'] ?? '') ?>',
        vehicle: '<?= esc($order['vehicle'] ?? '') ?>'
    });
}

function updateCarWashOrder() {
    var form = $('#editCarWashForm');
    var orderId = form.data('order-id');
    var formData = form.serialize();
    
    // Process priority checkbox
    var priorityChecked = $('#edit_priority').is(':checked');
    formData = formData.replace(/&?priority=[^&]*/g, '');
    formData += '&priority=' + (priorityChecked ? 'waiter' : 'normal');
    
    var submitBtn = form.find('button[type="submit"]');
    var spinner = submitBtn.find('.spinner-border');
    
    // Show loading state
    submitBtn.prop('disabled', true);
    spinner.removeClass('d-none');
    
    console.log('Updating order ID:', orderId);
    console.log('Form data:', formData);
    console.log('URL:', '<?= base_url('car_wash/update/') ?>' + orderId);
    
        $.ajax({
        url: '<?= base_url('car_wash/update/') ?>' + orderId,
            type: 'POST',
        data: formData,
        dataType: 'json',
            success: function(response) {
                if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '<?= lang('App.success') ?>',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    $('#editCarWashModal').modal('hide');
                    // Use dynamic refresh instead of page reload
                    if (typeof refreshAllCarWashData === 'function') {
                        refreshAllCarWashData({ showToast: false });
                    } else {
                        // Fallback to page reload if refresh function not available
                        location.reload();
                    }
                });
                } else {
                var errorMessage = response.message;
                if (response.errors) {
                    errorMessage += '<br><ul>';
                    $.each(response.errors, function(field, message) {
                        errorMessage += '<li>' + message + '</li>';
                    });
                    errorMessage += '</ul>';
                }
                Swal.fire({
                    icon: 'error',
                    title: '<?= lang('App.error') ?>',
                    html: errorMessage
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', xhr.responseText);
            console.log('Status:', status);
            console.log('Error:', error);
            
            var errorMessage = 'An error occurred while updating the order';
            if (xhr.responseText) {
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
            }
                } catch (e) {
                    errorMessage = xhr.responseText;
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: '<?= lang('App.error') ?>',
                text: errorMessage
            });
        }
    }).always(function() {
        // Reset button state
        submitBtn.prop('disabled', false);
        spinner.addClass('d-none');
    });
}

// Change Status Functions
function updateOrderStatus() {
    var form = $('#changeStatusForm');
    var orderId = form.data('order-id');
    var newStatus = $('#modal_status').val();
    
    var submitBtn = form.find('button[type="submit"]');
    var spinner = submitBtn.find('.spinner-border');
    
    // Show loading state
    submitBtn.prop('disabled', true);
    spinner.removeClass('d-none');
        
        $.ajax({
        url: '<?= base_url('car_wash/update-status') ?>',
            type: 'POST',
        data: {
            id: orderId,
            status: newStatus
        },
        dataType: 'json',
            success: function(response) {
                if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: '<?= lang('App.success') ?>',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    $('#changeStatusModal').modal('hide');
                    
                    // Update the status badge dynamically
                    updateStatusBadge(newStatus);
                    
                    // Refresh activities
                    loadRecentActivity();
                });
                } else {
                Swal.fire({
                    icon: 'error',
                    title: '<?= lang('App.error') ?>',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: '<?= lang('App.error') ?>',
                text: 'An error occurred while updating the status'
            });
        }
    }).always(function() {
        // Reset button state
        submitBtn.prop('disabled', false);
        spinner.addClass('d-none');
    });
}

function updateStatusBadge(newStatus) {
    var badge = $('.order-status-badge');
    var statusText = '';
    var statusClass = '';
    
    // Define status configurations
    var statusConfig = {
        'pending': {
            text: 'Pending',
            class: 'bg-warning'
        },
        'in_progress': {
            text: 'In Progress', 
            class: 'bg-primary'
        },
        'completed': {
            text: 'Completed',
            class: 'bg-success'
        },
        'cancelled': {
            text: 'Cancelled',
            class: 'bg-danger'
        }
    };
    
    if (statusConfig[newStatus]) {
        statusText = statusConfig[newStatus].text;
        statusClass = statusConfig[newStatus].class;
    }
    
    // Remove all existing status classes
    badge.removeClass('bg-warning bg-primary bg-success bg-danger bg-info bg-secondary');
    
    // Add new status class and update text
    badge.addClass(statusClass).text(statusText);
    
    // Add a subtle animation effect
    badge.addClass('animate__animated animate__pulse');
    setTimeout(function() {
        badge.removeClass('animate__animated animate__pulse');
    }, 1000);
}

// Generate default avatar if none provided
function generateDefaultAvatar(name) {
    if (!name) name = 'User';
    
    // Generate initials from name
    const initials = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    
    // Generate color based on name for consistency
    const colors = [
        '3498db', '9b59b6', 'e74c3c', 'f39c12', 
        '2ecc71', '1abc9c', 'e67e22', 'f1c40f',
        '8e44ad', 'c0392b', 'd35400', '27ae60'
    ];
    
    // Simple hash to get consistent color
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }
    const colorIndex = Math.abs(hash) % colors.length;
    const backgroundColor = colors[colorIndex];
    
    // Use UI Avatars service to generate avatar with initials
    const encodedInitials = encodeURIComponent(initials);
    return `https://ui-avatars.com/api/?name=${encodedInitials}&size=32&background=${backgroundColor}&color=ffffff&bold=true&format=png`;
}

// Escape HTML to prevent XSS
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

// Create reply HTML
function createReplyHtml(reply) {
    const authorName = reply.author_name || reply.user_name || 'Anonymous';
    const avatarUrl = reply.avatar_url || reply.avatar || generateDefaultAvatar(authorName);
    const replyText = reply.comment || reply.description || reply.content || '';
    const escapedReply = escapeHtml(replyText).replace(/\n/g, '<br>');
    
    return `
        <div class="comment-reply" id="reply-${reply.id}">
            <div class="reply-header">
                <div class="d-flex align-items-center">
                    <img src="${avatarUrl}" alt="${authorName}" class="reply-avatar me-2">
                    <span class="reply-user-name">${authorName}</span>
                    <span class="reply-timestamp ms-2">${reply.created_at_relative || reply.created_at}</span>
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

// Show reply form
function showReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.add('show');
        const input = document.getElementById(`reply-input-${commentId}`);
        if (input) {
            input.focus();
        }
    }
}

// Hide reply form
function hideReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    if (replyForm) {
        replyForm.classList.remove('show');
        const input = document.getElementById(`reply-input-${commentId}`);
        if (input) {
            input.value = '';
        }
    }
}

// Submit reply
function submitReply(commentId) {
    const input = document.getElementById(`reply-input-${commentId}`);
    const replyText = input.value.trim();
    
    if (!replyText) {
        showToast('warning', 'Please enter a reply');
        return;
    }
    
    const orderId = <?= $order['id'] ?? 0 ?>;
    
    fetch(`<?= base_url('car_wash/addReply') ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `comment_id=${commentId}&reply=${encodeURIComponent(replyText)}&order_id=${orderId}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Reply added successfully');
            hideReplyForm(commentId);
            
            // Update comment count immediately (replies count as comments)
            const currentBadge = document.getElementById('commentsCount');
            if (currentBadge) {
                const currentCount = parseInt(currentBadge.textContent) || 0;
                updateCommentsCount(currentCount + 1);
            }
            
            loadComments(true); // Refresh comments to show new reply
            loadRecentActivity(true); // Reload activities to show the reply activity
        } else {
            showToast('error', data.message || 'Error adding reply');
        }
    })
    .catch(error => {
        console.error('Error adding reply:', error);
        showToast('error', 'Error adding reply');
    });
}

// Edit comment
function editComment(commentId) {
    console.log('Editing comment:', commentId);
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) return;
    
    const contentElement = commentElement.querySelector('.comment-content');
    if (!contentElement) return;
    
    // Get current comment text (strip HTML)
    const currentText = contentElement.textContent.trim();
    
    // Create edit form
    const editForm = document.createElement('div');
    editForm.className = 'comment-edit-form';
    editForm.innerHTML = `
        <textarea class="form-control mb-2" rows="3">${currentText}</textarea>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary" onclick="saveCommentEdit(${commentId})">Save</button>
            <button class="btn btn-sm btn-secondary" onclick="cancelCommentEdit(${commentId})">Cancel</button>
        </div>
    `;
    
    // Store original content
    editForm.setAttribute('data-original-content', contentElement.innerHTML);
    
    // Replace content with edit form
    contentElement.innerHTML = '';
    contentElement.appendChild(editForm);
}

// Save comment edit
function saveCommentEdit(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) return;
    
    const editForm = commentElement.querySelector('.comment-edit-form');
    const textarea = editForm.querySelector('textarea');
    const newText = textarea.value.trim();
    
    if (!newText) {
        showToast('warning', 'Comment cannot be empty');
        return;
    }
    
    fetch(`<?= base_url('car_wash/updateComment') ?>`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `comment_id=${commentId}&comment=${encodeURIComponent(newText)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Comment updated successfully');
            loadComments(true); // Refresh comments
            loadRecentActivity(true); // Reload activities to show the update activity
        } else {
            showToast('error', data.message || 'Error updating comment');
        }
    })
    .catch(error => {
        console.error('Error updating comment:', error);
        showToast('error', 'Error updating comment');
    });
}

// Cancel comment edit
function cancelCommentEdit(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (!commentElement) return;
    
    const contentElement = commentElement.querySelector('.comment-content');
    const editForm = contentElement.querySelector('.comment-edit-form');
    const originalContent = editForm.getAttribute('data-original-content');
    
    contentElement.innerHTML = originalContent;
}

// Delete comment
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
    
    fetch(`<?= base_url('car_wash/deleteComment/') ?>${commentId}`, {
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
                    
                    // Update comment count immediately
                    const currentBadge = document.getElementById('commentsCount');
                    if (currentBadge) {
                        const currentCount = parseInt(currentBadge.textContent) || 0;
                        updateCommentsCount(Math.max(0, currentCount - 1));
                    }
                    
                    // Reload comments to update the UI
                    loadComments(true);
                    
                    // Reload activities to show the delete activity
                    loadRecentActivity(true);
            } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error deleting comment'
                    });
        }
    })
    .catch(error => {
        console.error('Error deleting comment:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error deleting comment'
                });
            });
        }
    });
}

// Submit Comment Function with enhanced features
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
    formData.append('order_id', orderId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    // Add attachments if any
    for (let i = 0; i < attachments.length; i++) {
        formData.append('attachments[]', attachments[i]);
    }
    
    fetch(`<?= base_url('car_wash/addComment') ?>`, {
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

// Initialize comment form and attachment handling
function initializeCommentForm() {
    // Handle form submission
    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitComment();
    });
    
    // Handle attachment count updates
    document.getElementById('commentAttachments').addEventListener('change', updateAttachmentCount);
    
    // Initialize mentions
    initializeMentions();
}

// Update attachment count display
function updateAttachmentCount() {
    const fileInput = document.getElementById('commentAttachments');
    const countSpan = document.getElementById('attachmentCount');
    
    if (fileInput && countSpan) {
        const fileCount = fileInput.files.length;
        if (fileCount > 0) {
            countSpan.textContent = `${fileCount} file${fileCount > 1 ? 's' : ''} selected`;
            countSpan.className = 'text-primary small ms-2';
        } else {
            countSpan.textContent = '';
        }
    }
}

// ========================================
// MOBILE QUICK ACTIONS MODAL FUNCTIONS
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

// Edit Order from Quick Actions Modal
function editOrderFromModal(orderId) {
    closeQuickActionsModal();
    editOrder(orderId);
    console.log('📱 Edit order from modal:', orderId);
}

// Change Status from Quick Actions Modal
function changeStatusFromModal(orderId) {
    closeQuickActionsModal();
    changeStatus(orderId);
    console.log('📱 Change status from modal:', orderId);
}

// Generate QR from Quick Actions Modal
function generateQRFromModal(orderId) {
    closeQuickActionsModal();
    generateQR(orderId);
    console.log('📱 Generate QR from modal:', orderId);
}

// Regenerate Shortlink from Quick Actions Modal
function regenerateShortlinkFromModal(orderId) {
    closeQuickActionsModal();
    regenerateShortlink(orderId);
    console.log('📱 Regenerate shortlink from modal:', orderId);
}

// ========================================
// END MOBILE QUICK ACTIONS FUNCTIONS
// ========================================

// Mentions system
let mentionUsers = [];
let currentMentionQuery = '';
let mentionStartPos = -1;
let selectedSuggestionIndex = -1;

// Initialize mentions functionality
function initializeMentions() {
    const commentTextarea = document.getElementById('commentText');
    const suggestionsContainer = document.getElementById('mentionSuggestions');
    
    if (!commentTextarea || !suggestionsContainer) {
        console.log('Mentions: Required elements not found');
        return;
    }
    
    // Load users for mentions
    loadMentionUsers();
    
    // Handle input events
    commentTextarea.addEventListener('input', handleMentionInput);
    commentTextarea.addEventListener('keydown', handleMentionKeydown);
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!suggestionsContainer.contains(e.target) && e.target !== commentTextarea) {
            hideMentionSuggestions();
        }
    });
}

// Load users for mentions
function loadMentionUsers() {
    fetch('<?= base_url('car_wash/getUsersForMentions') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                mentionUsers = data.data;
                console.log('Loaded mention users:', mentionUsers);
            } else {
                console.log('Failed to load mention users');
            }
        })
        .catch(error => {
            console.error('Error loading mention users:', error);
        });
}

// Handle mention input
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

// Handle mention keydown
function handleMentionKeydown(e) {
    const suggestionsContainer = document.getElementById('mentionSuggestions');
    if (!suggestionsContainer || suggestionsContainer.style.display === 'none') {
        return;
    }
    
    const suggestions = suggestionsContainer.querySelectorAll('.mention-suggestion');
    
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
            e.preventDefault();
            if (selectedSuggestionIndex >= 0) {
                const selectedSuggestion = suggestions[selectedSuggestionIndex];
                selectMention(selectedSuggestion);
            }
            break;
            
        case 'Escape':
            hideMentionSuggestions();
            break;
    }
}

// Show mention suggestions
function showMentionSuggestions() {
    const filteredUsers = mentionUsers.filter(user => 
        user.username.toLowerCase().includes(currentMentionQuery) ||
        user.name.toLowerCase().includes(currentMentionQuery)
    );
    
    if (filteredUsers.length === 0) {
        hideMentionSuggestions();
        return;
    }
    
    const suggestionsHtml = filteredUsers.map((user, index) => `
        <div class="mention-suggestion ${index === 0 ? 'active' : ''}" 
             data-user-id="${user.id}" 
             data-username="${user.username}"
             onclick="selectMention(this)">
            <strong>@${user.username}</strong> - ${user.name}
        </div>
    `).join('');
    
    const suggestionsContainer = document.getElementById('mentionSuggestions');
    suggestionsContainer.innerHTML = suggestionsHtml;
    suggestionsContainer.style.display = 'block';
    selectedSuggestionIndex = 0;
}

// Hide mention suggestions
function hideMentionSuggestions() {
    const suggestionsContainer = document.getElementById('mentionSuggestions');
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'none';
    }
    selectedSuggestionIndex = -1;
}

// Update suggestion selection
function updateSuggestionSelection() {
    const suggestions = document.querySelectorAll('.mention-suggestion');
    suggestions.forEach((suggestion, index) => {
        if (index === selectedSuggestionIndex) {
            suggestion.classList.add('active');
        } else {
            suggestion.classList.remove('active');
        }
    });
}

// Select mention
function selectMention(suggestionElement) {
    const username = suggestionElement.dataset.username;
    const textarea = document.getElementById('commentText');
    const currentText = textarea.value;
    
    const beforeMention = currentText.substring(0, mentionStartPos);
    const afterCursor = currentText.substring(textarea.selectionStart);
    
    const newText = beforeMention + '@' + username + ' ' + afterCursor;
    textarea.value = newText;
    
    // Set cursor position after the mention
    const newCursorPos = mentionStartPos + username.length + 2;
    textarea.setSelectionRange(newCursorPos, newCursorPos);
    
    hideMentionSuggestions();
    textarea.focus();
}

// Edit reply function
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
    
    // Hide original content and actions
    contentElement.style.display = 'none';
    const actionsElement = replyElement.querySelector('.reply-actions');
    if (actionsElement) {
        actionsElement.style.display = 'none';
    }
    
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
    
    // Add edit form to reply
    replyElement.appendChild(editForm);
    
    // Focus on textarea
    const textarea = document.getElementById(`edit-reply-${replyId}`);
    textarea.focus();
    textarea.setSelectionRange(textarea.value.length, textarea.value.length);
    
    // Reinitialize feather icons
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
    
    fetch(`<?= base_url('car_wash/updateComment/') ?>${replyId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `comment=${encodeURIComponent(newContent)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
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
        
        // Reinitialize feather icons
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

// Delete reply function
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
    
            fetch(`<?= base_url('car_wash/deleteComment/') ?>${replyId}`, {
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
                    
                    // Remove the reply from the UI
                    const replyElement = document.getElementById(`reply-${replyId}`);
                    if (replyElement) {
                        replyElement.remove();
                    }
                    
                    // Update comment count immediately (replies count as comments)
                    const currentBadge = document.getElementById('commentsCount');
                    if (currentBadge) {
                        const currentCount = parseInt(currentBadge.textContent) || 0;
                        updateCommentsCount(Math.max(0, currentCount - 1));
                    }
                    
                    // Reload comments to update the count
                    loadComments(true);
                    
                    // Reload activities to show the delete activity
                    loadRecentActivity(true);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error deleting reply'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error deleting reply'
                });
            });
        }
    });
}

// Helper function to escape HTML (if not already defined)
function escapeHtml(text) {
    if (typeof text !== 'string') return text;
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// ... existing code ...
</script>

<!-- Edit Car Wash Order Modal -->
<div class="modal fade car-wash-modal" id="editCarWashModal" tabindex="-1" aria-labelledby="editCarWashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCarWashModalLabel">
                    <i class="fas fa-edit me-2"></i><?= lang('App.edit_order') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCarWashForm" method="POST" onsubmit="event.preventDefault(); updateCarWashOrder();">
                    <div class="row g-3">
                        <!-- Client -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_client_id" class="form-label">
                                    <i class="fas fa-building me-1"></i><?= lang('App.client') ?> 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit_client_id" name="client_id" required 
                                        style="appearance: auto !important; background-image: none !important;">
                                    <option value=""><?= lang('App.select_client') ?></option>
                                    <?php if (!empty($clients) && is_array($clients)): ?>
                                    <?php foreach ($clients as $client): ?>
                                            <?php 
                                            // Show active clients, fallback to all if status field doesn't exist  
                                            $isActive = !isset($client['status']) || $client['status'] == 'active';
                                            if ($isActive): 
                                            ?>
                                                <option value="<?= esc($client['id'] ?? '') ?>" 
                                                        <?= ($client['id'] == $order['client_id']) ? 'selected' : '' ?>>
                                                    <?= esc($client['name'] ?? 'Unknown Client') ?>
                                                </option>
                                            <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>
                                            <?php if (!isset($clients)): ?>
                                                No clients data loaded
                                            <?php elseif (empty($clients)): ?>
                                                No active clients available
                                            <?php else: ?>
                                                Error loading clients
                                            <?php endif; ?>
                                        </option>
                                        <!-- DEBUG: Add console log with detailed info -->
                                        <script>
                                        console.log('DEBUG CarWash Edit - Clients Issue:', {
                                            'clients_isset': <?= json_encode(isset($clients)) ?>,
                                            'clients_is_array': <?= json_encode(is_array($clients ?? null)) ?>,
                                            'clients_count': <?= json_encode(count($clients ?? [])) ?>,
                                            'clients_sample': <?= json_encode(array_slice($clients ?? [], 0, 2)) ?>
                                        });
                                        </script>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Tag/Stock -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_tag_stock" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Tag/Stock
                                </label>
                                <input type="text" class="form-control" id="edit_tag_stock" name="tag_stock" 
                                       placeholder="Enter tag or stock number" 
                                       value="<?= esc($order['tag_stock'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- VIN# -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vin_number" class="form-label">
                                    <i class="fas fa-barcode me-1"></i>VIN#
                                </label>
                                <div class="vin-input-container position-relative">
                                    <input type="text" class="form-control" id="edit_vin_number" name="vin_number" 
                                           placeholder="<?= lang('App.vin_17_character') ?>" maxlength="17"
                                           value="<?= esc($order['vin_number'] ?? '') ?>">
                                    <span class="vin-status" id="edit-vin-status"></span>
                                </div>
                                <small class="text-muted"><?= lang('App.vin_enter_for_decode') ?></small>
                            </div>
                        </div>

                        <!-- Vehicle -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vehicle" class="form-label">
                                    <i class="fas fa-car me-1"></i><?= lang('App.vehicle') ?> 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="edit_vehicle" name="vehicle" required 
                                       placeholder="<?= lang('App.vin_vehicle_info_auto_fill') ?>"
                                       value="<?= esc($order['vehicle'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Service -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_service_id" class="form-label">
                                    <i class="fas fa-cog me-1"></i><?= lang('App.service') ?> 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="edit_service_id" name="service_id" required
                                        style="appearance: auto !important; background-image: none !important;">
                                    <option value=""><?= lang('App.select_service') ?></option>
                                    <?php if (!empty($carWashServices) && is_array($carWashServices)): ?>
                                        <?php foreach ($carWashServices as $service): ?>
                                            <?php 
                                            // Show active and visible services, fallback to all if fields don't exist
                                            $isActive = !isset($service['is_active']) || $service['is_active'] == 1;
                                            $isVisible = !isset($service['is_visible']) || $service['is_visible'] == 1;
                                            if ($isActive && $isVisible): 
                                            ?>
                                                <option value="<?= esc($service['id'] ?? '') ?>" 
                                                        data-price="<?= esc($service['price'] ?? 0) ?>"
                                                        <?= (isset($order['service_id']) && $service['id'] == $order['service_id']) ? 'selected' : '' ?>>
                                                    <?= esc($service['name'] ?? 'Unknown Service') ?>
                                                    <?php if (!empty($service['price'])): ?>
                                                        - $<?= number_format($service['price'], 2) ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>
                                            <?php if (!isset($carWashServices)): ?>
                                                No services data loaded
                                            <?php elseif (empty($carWashServices)): ?>
                                                No active services available
                                            <?php else: ?>
                                                Error loading services
                                            <?php endif; ?>
                                        </option>
                                        <!-- DEBUG: Add console log with detailed info -->
                                        <script>
                                        console.log('DEBUG CarWash Edit - Services Issue:', {
                                            'services_isset': <?= json_encode(isset($carWashServices)) ?>,
                                            'services_is_array': <?= json_encode(is_array($carWashServices ?? null)) ?>,
                                            'services_count': <?= json_encode(count($carWashServices ?? [])) ?>,
                                            'services_sample': <?= json_encode(array_slice($carWashServices ?? [], 0, 2)) ?>
                                        });
                                        </script>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">
                                    <i class="fas fa-flag me-1"></i><?= lang('App.status') ?>
                                </label>
                                <select class="form-select" id="edit_status" name="status"
                                        style="appearance: auto !important; background-image: none !important;">
                                    <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="in_progress" <?= ($order['status'] == 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                                    <option value="completed" <?= ($order['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= ($order['status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Waiter Priority
                                </label>
                                <div class="form-check" style="margin-top: 0.5rem;">
                                    <input class="form-check-input" type="checkbox" id="edit_priority" name="priority" value="waiter"
                                           <?= (($order['priority'] ?? '') == 'waiter') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="edit_priority">
                                        Mark as Waiter priority
                                    </label>
                                </div>
                                <small class="text-muted">If unchecked, will be saved as Normal priority</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-save me-1"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background-color: #f8f9fa; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title" id="changeStatusModalLabel">
                    <i class="ri-refresh-line me-2"></i><?= lang('App.change_status') ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <form id="changeStatusForm" method="POST" onsubmit="event.preventDefault(); updateOrderStatus();">
                    <div class="mb-3">
                        <label for="modal_status" class="form-label fw-medium">
                            <i class="ri-flag-line me-1"></i><?= lang('App.new_status') ?>
                        </label>
                        <select class="form-select" id="modal_status" name="status" required style="border-radius: 6px;">
                            <option value="pending">
                                <i class="ri-time-line"></i> Pending
                            </option>
                            <option value="in_progress">
                                <i class="ri-play-circle-line"></i> In Progress
                            </option>
                            <option value="completed">
                                <i class="ri-check-circle-line"></i> Completed
                            </option>
                            <option value="cancelled">
                                <i class="ri-close-circle-line"></i> Cancelled
                            </option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info" style="border-radius: 6px;">
                        <i class="ri-information-line me-2"></i>
                        <small>Changing the status will update the order and create an activity record.</small>
                    </div>

                    <div class="modal-footer" style="padding: 1rem 0 0; border-top: 1px solid #e9ecef;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 6px;">
                            <i class="ri-close-line me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px; background-color: #405189; border-color: #405189;">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="ri-save-line me-1"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom styles for edit modal -->
<!-- NOTE: Using native HTML form elements with PHP prepopulation to avoid JavaScript conflicts -->
<style>
.car-wash-modal .modal-dialog {
    margin: 2rem auto;
    max-width: 720px;
}

.car-wash-modal .modal-content {
    border-radius: 12px;
    border: 1px solid #e9ecef;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.car-wash-modal .modal-header {
    padding: 1.25rem 1.5rem 1rem;
    border-bottom: 1px solid #e9ecef;
    border-radius: 12px 12px 0 0;
    background-color: #f8f9fa;
    color: #495057;
}

.car-wash-modal .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
    color: #495057;
}

.car-wash-modal .modal-body {
    padding: 1.5rem;
    background-color: #ffffff;
}

.car-wash-modal .form-label {
    font-weight: 500;
    font-size: 0.875rem;
    color: #495057;
    margin-bottom: 0.375rem;
}

.car-wash-modal .form-control,
.car-wash-modal .form-select {
    border: 1px solid #ced4da;
    border-radius: 6px;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    background-color: white;
}

.car-wash-modal .form-control:focus,
.car-wash-modal .form-select:focus {
    border-color: #405189;
    box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25);
    outline: none;
}

.car-wash-modal .modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background-color: #f8f9fa;
    border-radius: 0 0 12px 12px;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.car-wash-modal .btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    font-size: 0.875rem;
    transition: all 0.15s ease-in-out;
}

.car-wash-modal .btn-primary {
    background-color: #405189;
    border-color: #405189;
    color: white;
    min-width: 120px;
}

.car-wash-modal .btn-primary:hover {
    background-color: #364574;
    border-color: #313a65;
}

.car-wash-modal .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.car-wash-modal .btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
}

.car-wash-modal .spinner-border-sm {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
}

@media (max-width: 768px) {
    .car-wash-modal .modal-dialog {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
    
    .car-wash-modal .modal-body {
        padding: 1rem;
    }
    
    .car-wash-modal .modal-footer {
        padding: 0.75rem 1rem 1rem;
        flex-direction: column;
    }
    
    .car-wash-modal .btn {
        width: 100%;
        margin: 0.25rem 0;
    }
}

/* QR Modal Styles */
#qrModal .modal-dialog {
    max-width: 500px;
}

#qrModal .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

#qrModal .modal-header {
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid #e9ecef;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 16px 16px 0 0;
}

#qrModal .modal-title {
    font-weight: 600;
    color: #495057;
    font-size: 1.1rem;
}

#qrModal .modal-body {
    padding: 2rem 1.5rem;
}

#qrModal .qr-large-container {
    background: #ffffff;
    border-radius: 16px;
    padding: 1rem;
    border: 1px solid #e9ecef;
}

#qrModal .form-control {
    border-radius: 8px;
    border: 1px solid #ced4da;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

#qrModal .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    transition: all 0.15s ease-in-out;
}

#qrModal .btn-primary {
    background-color: #405189;
    border-color: #405189;
}

#qrModal .btn-primary:hover {
    background-color: #364574;
    border-color: #313a65;
}

#qrModal .btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
}

#qrModal .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

#qrModal .bg-light {
    background-color: #f8f9fa !important;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.icon-xs {
    width: 14px;
    height: 14px;
}

.icon-sm {
    width: 18px;
    height: 18px;
}

.icon-lg {
    width: 48px;
    height: 48px;
}

/* Fix modal backdrop issues */
.modal.fade .modal-dialog {
    transform: translate(0, -50px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: none;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-backdrop.fade {
    opacity: 0;
}

.modal-backdrop.show {
    opacity: 0.5;
}
</style>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="qrModalLabel">
                    <i data-feather="smartphone" class="icon-sm me-2"></i>
                    QR Code - Order CW-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (isset($qr_data) && $qr_data): ?>
                <!-- QR Code Display -->
                <div class="qr-large-container mb-4">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Order CW-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
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

<!-- Mobile & Tablet Quick Actions Floating Button -->
<div class="quick-actions-float-btn d-xl-none" onclick="openQuickActionsModal()">
    <i class="ri-flashlight-line"></i>
</div>

<!-- Quick Actions Modal (Mobile) -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content quick-actions-modal">
            <div class="modal-header quick-actions-modal-header">
                <div class="d-flex align-items-center">
                    <div class="quick-actions-icon-wrapper me-3">
                        <i class="ri-flashlight-line quick-actions-main-icon"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="quickActionsModalLabel"><?= lang('App.quick_actions') ?></h5>
                        <small class="quick-actions-subtitle"><?= lang('App.order') ?> <?= $order['order_number'] ?></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body quick-actions-modal-body">
                <!-- Contact Info Card -->
                <div class="quick-actions-contact-card mb-4">
                    <div class="d-flex align-items-center">
                        <div class="contact-avatar me-3">
                            <i class="ri-car-washing-line contact-icon"></i>
                        </div>
                        <div class="contact-info">
                            <h6 class="contact-name mb-1"><?= $order['client_name'] ?></h6>
                            <small class="contact-role text-muted"><?= $order['contact_name'] ?? lang('App.client') ?></small>
                        </div>
                    </div>
                </div>

                <div class="quick-actions-grid">
                    <!-- Status Section -->
                    <div class="quick-action-section mb-4">
                        <label class="quick-action-label">
                            <i class="ri-refresh-line quick-action-label-icon"></i>
                            <?= lang('App.current_status') ?>
                        </label>
                        <div class="form-control-plaintext">
                            <?php
                            $statusIcons = [
                                'pending' => '⏳',
                                'confirmed' => '✅',
                                'in_progress' => '🔄',
                                'completed' => '✅',
                                'cancelled' => '❌'
                            ];
                            $statusIcon = $statusIcons[$order['status']] ?? '📋';
                            echo $statusIcon . ' ' . lang('App.' . $order['status']);
                            ?>
                        </div>
                    </div>

                    <!-- Action Buttons Grid -->
                    <div class="quick-actions-buttons">
                        <!-- Edit Order -->
                        <button class="quick-action-btn quick-action-edit" onclick="editOrderFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i class="ri-edit-line"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title"><?= lang('App.edit_order') ?></span>
                                <small class="quick-action-desc"><?= lang('App.modify_order') ?></small>
                            </div>
                        </button>

                        <!-- Change Status -->
                        <button class="quick-action-btn quick-action-status" onclick="changeStatusFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i class="ri-refresh-line"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title"><?= lang('App.change_status') ?></span>
                                <small class="quick-action-desc"><?= lang('App.update_status') ?></small>
                            </div>
                        </button>

                        <!-- Generate QR -->
                        <button class="quick-action-btn quick-action-qr" onclick="generateQRFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i class="ri-qr-code-line"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title"><?= lang('App.generate_qr') ?></span>
                                <small class="quick-action-desc"><?= lang('App.qr_code') ?></small>
                            </div>
                        </button>

                        <!-- Regenerate Shortlink -->
                        <button class="quick-action-btn quick-action-regenerate" onclick="regenerateShortlinkFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i class="ri-refresh-line"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Regenerar Shortlink</span>
                                <small class="quick-action-desc">Nuevo enlace corto</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 