<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?><?= $title ?? lang('App.service_order') ?><?= $this->endSection() ?>

<?= $this->section('page_title_main') ?><?= $title ?? lang('App.service_order') ?><?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>
<li class="breadcrumb-item"><a href="<?= base_url('service_orders') ?>"><?= lang('App.service_orders') ?></a></li>
<li class="breadcrumb-item active"><?= $title ?? lang('App.service_order') ?></li>
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

/* VIN Clickable Styles */
.vin-clickable {
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    font-family: 'Courier New', monospace;
    font-weight: 500;
    background-color: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.vin-clickable:hover {
    background-color: rgba(59, 130, 246, 0.15);
    border-color: rgba(59, 130, 246, 0.3);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.vin-clickable i {
    font-size: 0.7rem;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.vin-clickable:hover i {
    opacity: 1;
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

/* Enhanced Time Status Badge - Larger size */
.time-status-badge-large {
    font-size: 0.75rem !important;
    padding: 0.4rem 0.8rem !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
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

/* Live time indicator */
.live-time-indicator {
    margin-top: 0.25rem;
    font-size: 0.65rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.live-time-pulse {
    width: 6px;
    height: 6px;
    background: #22c55e;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(0.8);
    }
}

.live-label {
    font-weight: 500;
}

.live-time {
    font-weight: 600;
    color: #1e293b;
}

/* Service Order specific styles */
.service-order-view-container {
    padding: 0;
}

/* Card styling */
.card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.25rem;
    border-radius: 12px 12px 0 0;
}

.card-body {
    padding: 1.5rem;
}

/* Badge styling */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    font-weight: 600;
}

/* Button styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Form styling */
.form-select, .form-control {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    transition: all 0.2s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Detail row styling */
.detail-row {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 0.9375rem;
    color: #1e293b;
    font-weight: 500;
}

/* VIN styling */
.vin-display {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    background: #f8fafc;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

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

/* SMS Character Count Validation Styles */
.sms-char-count-container {
    transition: all 0.3s ease;
}

/* Character count color overrides */
#smsCharCount {
    font-weight: bold !important;
    transition: color 0.3s ease !important;
}

.sms-char-count-container.text-success #smsCharCount {
    color: #28a745 !important;
}

.sms-char-count-container.text-warning #smsCharCount {
    color: #ffc107 !important;
}

.sms-char-count-container.text-danger #smsCharCount {
    color: #dc3545 !important;
}

.pulse-danger {
    animation: pulse-danger 1.5s infinite;
}

.pulse-warning {
    animation: pulse-warning 2s infinite;
}

@keyframes pulse-danger {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
}

@keyframes pulse-warning {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.02);
        opacity: 0.9;
    }
}

.slide-down {
    animation: slideDown 0.3s ease-out;
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

.btn-disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transition: all 0.3s ease;
}

.btn-disabled:hover {
    opacity: 0.6 !important;
    transform: none !important;
}

/* Enhanced textarea border transitions */
#smsMessage {
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

#smsMessage.border-success {
    border-color: #28a745 !important;
}

#smsMessage.border-warning {
    border-color: #ffc107 !important;
}

#smsMessage.border-danger {
    border-color: #dc3545 !important;
}

/* Character status badge transitions */
#smsCharStatus {
    transition: all 0.3s ease;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Warning text styling */
#smsCharWarning {
    transition: all 0.3s ease;
    font-weight: 600;
}

/* Alert styling */
#smsLengthAlert {
    border-left: 4px solid #dc3545;
    background-color: #f8d7da;
    border-color: #f5c6cb;
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
    margin-left: 42px;
    color: #444;
    line-height: 1.5;
}

.comment-attachments {
    margin-left: 42px;
    margin-top: 10px;
}

.attachment-item {
    display: inline-block;
    margin: 4px 8px 4px 0;
    padding: 6px 10px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    text-decoration: none;
    color: #495057;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.attachment-item:hover {
    background: #e9ecef;
    color: #495057;
    text-decoration: none;
}

.attachment-item i {
    margin-right: 4px;
}

.attachment-image {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    margin: 4px;
    cursor: pointer;
    transition: transform 0.2s;
}

.attachment-image:hover {
    transform: scale(1.05);
}

.attachment-video {
    max-width: 300px;
    border-radius: 8px;
    margin: 4px;
}

.attachment-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.attachment-preview-item {
    display: flex;
    align-items: center;
    padding: 4px 8px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 0.875rem;
}

.attachment-preview-item .remove-attachment {
    margin-left: 8px;
    color: #dc3545;
    cursor: pointer;
    font-weight: bold;
}

.mention-highlight {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 1px 3px;
    border-radius: 3px;
    font-weight: 500;
}

.empty-comments {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.empty-comments i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 16px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .comment-content {
        margin-left: 0;
        margin-top: 8px;
    }
    
    .comment-attachments {
        margin-left: 0;
    }
    
    .attachment-image {
        max-width: 150px;
        max-height: 100px;
    }
    
    .attachment-video {
        max-width: 250px;
    }
}

/* Comments System Styles */
.comment-form-container {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.comment-input-container {
    position: relative;
}

.mention-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    max-height: 200px;
    overflow-y: auto;
}

.mention-suggestions-list {
    padding: 8px 0;
}

.mention-suggestion-item {
    padding: 8px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: background-color 0.2s;
}

.mention-suggestion-item:hover,
.mention-suggestion-item.active {
    background-color: #f8f9fa;
}

.mention-suggestion-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 12px;
    object-fit: cover;
}

.mention-suggestion-info {
    flex: 1;
}

.mention-suggestion-name {
    font-weight: 500;
    margin: 0;
    font-size: 14px;
}

.mention-suggestion-username {
    color: #6c757d;
    margin: 0;
    font-size: 12px;
}

/* File Upload Styles */
.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.file-upload-dropzone {
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-dropzone:hover,
.file-upload-dropzone.dragover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.file-preview-area {
    border-top: 1px solid #dee2e6;
    padding-top: 15px;
}

.file-preview-list {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.file-preview-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 8px;
    background: white;
    min-width: 120px;
    max-width: 200px;
}

.file-preview-image {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 8px;
}

.file-preview-info {
    font-size: 12px;
    color: #6c757d;
}

.file-preview-name {
    font-weight: 500;
    margin-bottom: 4px;
    word-break: break-word;
}

.file-preview-size {
    color: #adb5bd;
}

.file-preview-remove {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Comments List Styles */
.comments-list {
    max-height: 500px;
    overflow-y: auto;
}

.comment-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
    background: white;
    transition: box-shadow 0.2s;
}

.comment-item:hover {
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.comment-header {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.comment-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 8px;
    object-fit: cover;
}

.comment-user-info {
    flex: 1;
}

.comment-user-name {
    font-weight: 600;
    margin: 0;
    font-size: 13px;
}

.comment-timestamp {
    color: #6c757d;
    font-size: 11px;
    margin: 0;
}

.comment-content {
    margin-bottom: 8px;
    line-height: 1.4;
    font-size: 14px;
}

.comment-mention {
    color: #0d6efd;
    font-weight: 500;
    text-decoration: none;
}

.comment-mention:hover {
    text-decoration: underline;
}

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

.attachment-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
}

.attachment-item:hover .attachment-overlay {
    opacity: 1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .comment-form-container {
        padding: 15px;
    }
    
    .file-upload-dropzone {
        padding: 30px 15px;
    }
    
    .attachment-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 8px;
    }
    
    .comment-item {
        padding: 12px;
    }
    
    .comment-avatar {
        width: 32px;
        height: 32px;
    }
}

/* Loading States */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-up {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
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

/* Selected Files Preview Styles */
.selected-files-container {
    margin-top: 0.75rem;
    padding: 0.75rem;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
}

.selected-files-header {
    margin-bottom: 0.5rem;
}

.selected-files-count {
    font-size: 0.875rem;
    font-weight: 500;
    color: #495057;
}

.selected-files-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.selected-file-item {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.selected-file-item:hover {
    border-color: #007bff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.selected-file-preview {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    overflow: hidden;
}

.selected-file-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.selected-file-icon {
    font-size: 1rem;
}

.selected-file-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.selected-file-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #495057;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.selected-file-size {
    font-size: 0.75rem;
    color: #6c757d;
}

.selected-file-remove {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.selected-file-remove:hover {
    background-color: #c82333;
    transform: scale(1.1);
}

.empty-notes {
    text-align: center;
    padding: 2rem 1rem;
    color: #6c757d;
}

.empty-notes i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.mention-alert {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.mention-alert-content {
    flex: 1;
}

.mention-alert-title {
    font-weight: 600;
    font-size: 0.875rem;
    color: #856404;
    margin: 0 0 0.25rem 0;
}

.mention-alert-text {
    font-size: 0.75rem;
    color: #856404;
    margin: 0;
}

.mention-alert-action {
    background: #ffc107;
    color: #212529;
    border: none;
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.mention-alert-action:hover {
    background: #e0a800;
}

/* Note Reply Form Styles */
.note-reply-form {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #e9ecef;
}

.reply-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    object-fit: cover;
}

.note-replies {
    margin-top: 0.75rem;
}

.note-reply {
    margin-left: 1rem;
    padding-left: 0.75rem;
    border-left: 2px solid #e9ecef;
    margin-bottom: 0.5rem;
}

.reply-content {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.reply-content strong {
    color: #495057;
    font-weight: 600;
}

.reply-content p {
    color: #6c757d;
    margin-bottom: 0;
}

/* Notes List Container with Scroll */
#notesList {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

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

/* Loading and Load More Styles */
#notesLoadingIndicator {
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
    padding-top: 1rem;
}

#loadMoreNotesBtn {
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
    padding-top: 1rem;
}

#loadMoreNotesBtn .btn {
    transition: all 0.2s ease;
}

#loadMoreNotesBtn .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Responsive Design for Internal Notes */
@media (max-width: 768px) {
    .notes-filter-bar .row {
        gap: 0.5rem;
    }
    
    .note-item {
        padding: 0.75rem;
    }
    
    .note-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .note-actions {
        opacity: 1;
    }
    
    .note-attachment-list {
        flex-direction: column;
    }
    
    .note-reply {
        margin-left: 0.5rem;
        padding-left: 0.5rem;
    }
    
    #notesList {
        max-height: 400px;
    }
    
    #commentsList {
        max-height: 400px;
    }
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

.rotating {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

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

.reply-actions {
    transition: opacity 0.2s ease;
}

.note-reply:hover .reply-actions {
    opacity: 1 !important;
}

/* Comments Loading and Load More Styles */
#commentsLoadingIndicator {
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
    padding-top: 1rem;
}

#loadMoreCommentsBtn {
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
    padding-top: 1rem;
}

#loadMoreCommentsBtn .btn {
    transition: all 0.2s ease;
}

#loadMoreCommentsBtn .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
.quick-action-status .quick-action-icon {
    background: transparent;
}

.quick-action-status .quick-action-icon i {
    color: #3182ce;
}

.quick-action-status:hover {
    border-color: #3182ce;
}

.quick-action-call .quick-action-icon {
    background: transparent;
}

.quick-action-call .quick-action-icon i {
    color: #38a169;
}

.quick-action-call:hover {
    border-color: #38a169;
}

.quick-action-sms .quick-action-icon {
    background: transparent;
}

.quick-action-sms .quick-action-icon i {
    color: #ed8936;
}

.quick-action-sms:hover {
    border-color: #ed8936;
}

.quick-action-email .quick-action-icon {
    background: transparent;
}

.quick-action-email .quick-action-icon i {
    color: #9f7aea;
}

.quick-action-email:hover {
    border-color: #9f7aea;
}

.quick-action-alert .quick-action-icon {
    background: transparent;
}

.quick-action-alert .quick-action-icon i {
    color: #f56565;
}

.quick-action-alert:hover {
    border-color: #f56565;
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="service-order-view-container">
<?php if (isset($order) && $order): ?>

<!-- FULL WIDTH TOP INFORMATION BAR -->
<div class="order-top-bar mb-4">
    <!-- First Row: 3 cards for tablet/desktop, 2 cards for mobile -->
    <div class="row g-0">
        <!-- 1. Schedule Information -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="calendar" class="text-warning"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label"><?= lang('App.scheduled') ?></div>
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
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="user" class="text-success"></i>
                </div>
                <div class="top-bar-content">
                    <div class="top-bar-label"><?= lang('App.contact') ?></div>
                    <div class="top-bar-value"><?= $order['salesperson_name'] ?? lang('App.not_assigned') ?></div>
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
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="truck" class="text-primary"></i>
                        </div>
                <div class="top-bar-content">
                    <div class="top-bar-label"><?= lang('App.vehicle') ?></div>
                    <div class="top-bar-value"><?= $order['vehicle'] ?? lang('App.not_specified') ?></div>
                    <div class="top-bar-sub">
                        VIN: 
                        <?php if ($order['vin']): ?>
                            <span class="vin-clickable" 
                                  data-vin="<?= htmlspecialchars($order['vin']) ?>" 
                                  onclick="copyVinToClipboard(this)" 
                                  data-bs-toggle="tooltip" 
                                  data-bs-placement="top" 
                                  title="Click to copy full VIN: <?= htmlspecialchars($order['vin']) ?>">
                                <?= substr($order['vin'], -8) ?>
                                <i class="ri-file-copy-line ms-1"></i>
                            </span>
                        <?php else: ?>
                            <?= lang('App.not_specified') ?>
                        <?php endif; ?>
                    </div>
                </div>
                        </div>
                    </div>

        <!-- 4. Service Information -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="settings" class="text-info"></i>
                        </div>
                <div class="top-bar-content">
                    <div class="top-bar-label"><?= lang('App.service') ?></div>
                    <div class="top-bar-value"><?= $order['service_name'] ?? lang('App.not_specified') ?></div>
                    <div class="top-bar-sub">
                        <?php if (isset($order['total_amount']) && $order['total_amount']): ?>
                            $<?= number_format($order['total_amount'], 2) ?>
                        <?php else: ?>
                            Price not set
                        <?php endif; ?>
                    </div>
                </div>
                        </div>
                    </div>

        <!-- 5. Status -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="activity" class="text-info"></i>
                        </div>
                <div class="top-bar-content">
                    <div class="top-bar-label"><?= lang('App.status') ?></div>
                    <div class="top-bar-value">
                        <?php
                        $statusClass = 'bg-warning';
                        $statusText = lang('App.pending');
                        
                        switch($order['status'] ?? 'pending') {
                            case 'completed':
                                $statusClass = 'bg-success';
                                $statusText = lang('App.completed');
                                break;
                            case 'cancelled':
                                $statusClass = 'bg-danger';
                                $statusText = lang('App.cancelled');
                                break;
                            case 'in_progress':
                                $statusClass = 'bg-info';
                                $statusText = lang('App.in_progress');
                                break;
                            case 'processing':
                                $statusClass = 'bg-primary';
                                $statusText = lang('App.processing');
                                break;
                            case 'pending':
                                $statusClass = 'bg-warning';
                                $statusText = lang('App.pending');
                                break;
                        }
                        ?>
                        <span id="topBarStatusBadge" class="badge <?= $statusClass ?> fs-12"><?= $statusText ?></span>
                        </div>
                    <div id="topBarLastUpdated" class="top-bar-sub">
                        <?= lang('App.last_updated') ?>: <?= date('M j, g:i A', strtotime($order['updated_at'] ?? $order['created_at'])) ?>
                    </div>
                </div>
                        </div>
                    </div>

        <!-- 6. Time Remaining Status -->
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="top-bar-item">
                <div class="top-bar-icon">
                    <i data-feather="clock" class="text-secondary"></i>
                        </div>
                <div class="top-bar-content">
                    <div class="top-bar-label"><?= lang('App.time_status') ?></div>
                    <div class="top-bar-value">
                        <?php
                        $timeStatusText = lang('App.not_scheduled');
                        $timeStatusClass = 'badge bg-secondary time-status-badge-large';
                        
                        if ($order['date'] && $order['time']) {
                            $scheduledDateTime = new DateTime($order['date'] . ' ' . $order['time']);
                            $now = new DateTime();
                            $diff = $scheduledDateTime->getTimestamp() - $now->getTimestamp();
                            $hoursRemaining = $diff / 3600;
                            
                            if ($hoursRemaining < 0) {
                                // Past due
                                $timeStatusText = lang('App.delay');
                                $timeStatusClass = 'badge time-status-danger time-status-badge-large';
                            } elseif ($hoursRemaining < 1) {
                                // Less than 1 hour
                                $timeStatusText = lang('App.attention_required');
                                $timeStatusClass = 'badge time-status-warning time-status-badge-large';
                            } else {
                                // More than 1 hour
                                $timeStatusText = lang('App.on_time');
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
                                        echo sprintf('%.0f ' . lang('App.minutes_overdue'), $minutesOverdue);
                                    } elseif ($hoursOverdue < 24) {
                                        if ($hoursOverdue == floor($hoursOverdue)) {
                                            echo sprintf('%.0f ' . lang('App.hours_overdue'), $hoursOverdue);
                                        } else {
                                            echo sprintf('%.1f ' . lang('App.hours_overdue'), $hoursOverdue);
                                        }
                                    } else {
                                        $daysOverdue = floor($hoursOverdue / 24);
                                        $remainingHours = $hoursOverdue - ($daysOverdue * 24);
                                        if ($remainingHours < 1) {
                                            echo sprintf('%d ' . lang('App.days_overdue'), $daysOverdue, $daysOverdue > 1 ? 's' : '');
                                        } else {
                                            echo sprintf('%d day%s %.1f ' . lang('App.hours_overdue'), $daysOverdue, $daysOverdue > 1 ? 's' : '', $remainingHours);
                                        }
                                    }
                                } elseif ($hoursRemaining < 1) {
                                    $minutesRemaining = ($hoursRemaining * 60);
                                    echo sprintf('%.0f ' . lang('App.minutes_left'), $minutesRemaining);
                                } elseif ($hoursRemaining < 24) {
                                    if ($hoursRemaining == floor($hoursRemaining)) {
                                        echo sprintf('%.0f ' . lang('App.hours_remaining'), $hoursRemaining);
                                    } else {
                                        echo sprintf('%.1f ' . lang('App.hours_remaining'), $hoursRemaining);
                                    }
                                } else {
                                    $daysRemaining = floor($hoursRemaining / 24);
                                    $remainingHours = $hoursRemaining - ($daysRemaining * 24);
                                    if ($remainingHours < 1) {
                                        echo sprintf('%d ' . lang('App.days_remaining'), $daysRemaining, $daysRemaining > 1 ? 's' : '');
                                    } else {
                                        echo sprintf('%d day%s %.1f ' . lang('App.hours_remaining'), $daysRemaining, $daysRemaining > 1 ? 's' : '', $remainingHours);
                                    }
                                }
                            } else {
                                echo lang('App.no_time_set');
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
                <?= lang('App.back_to_service_orders') ?>
            </button>
                        </div>
                    </div>
</div>

<div class="row">
    <!-- Main Content Column -->
    <div class="col-xl-8">
        <!-- Order Details Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0 fw-bold"><?= lang('App.order_details') ?> - SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h5>
                    <small class="text-muted"><?= lang('App.complete_order_information_and_status') ?></small>
                        </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" onclick="editOrder(<?= $order['id'] ?>)">
                        <i data-feather="edit" class="icon-xs me-1"></i>
                        <?= lang('App.edit_order') ?>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="downloadPDF(<?= $order['id'] ?>)">
                        <i data-feather="download" class="icon-xs me-1"></i>
                        <?= lang('App.download_pdf') ?>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printOrder()">
                        <i data-feather="printer" class="icon-xs me-1"></i>
                        <?= lang('App.print') ?>
                    </button>
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
                            <p class="mb-0">
                                <?php
                                $statusClass = 'bg-warning';
                                $statusText = lang('App.pending');
                                
                                switch($order['status'] ?? 'pending') {
                                    case 'completed':
                                        $statusClass = 'bg-success';
                                        $statusText = lang('App.completed');
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        $statusText = lang('App.cancelled');
                                        break;
                                    case 'in_progress':
                                        $statusClass = 'bg-info';
                                        $statusText = lang('App.in_progress');
                                        break;
                                    case 'processing':
                                        $statusClass = 'bg-primary';
                                        $statusText = lang('App.processing');
                                        break;
                                    case 'pending':
                                        $statusClass = 'bg-warning';
                                        $statusText = lang('App.pending');
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.ro_number') ?></label>
                            <p class="mb-0"><?= $order['ro_number'] ?? lang('App.na') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.po_number') ?></label>
                            <p class="mb-0"><?= $order['po_number'] ?? lang('App.na') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.tag_number') ?></label>
                            <p class="mb-0"><?= $order['tag_number'] ?? lang('App.na') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.service_requested') ?></label>
                            <p class="mb-0"><?= $order['service_name'] ?? lang('App.na') ?></p>
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
                    <div class="row">
            <!-- Schedule Information -->
                        <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 fw-bold"><?= lang('App.schedule_information') ?></h5>
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
                            <label class="form-label fw-medium"><?= lang('App.advisor') ?></label>
                            <p class="mb-0"><?= $order['salesperson_name'] ?? lang('App.not_assigned') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle & Client Information -->
                        <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0 fw-bold"><?= lang('App.vehicle_client_information') ?></h5>
                        </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.client') ?></label>
                            <p class="mb-0"><?= $order['client_name'] ?? lang('App.na') ?></p>
                    </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.vehicle') ?></label>
                            <p class="mb-0"><?= $order['vehicle'] ?? lang('App.na') ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium"><?= lang('App.vin') ?></label>
                            <p class="mb-0"><?= $order['vin'] ?? lang('App.na') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="card">
                <div class="card-header">
                <h5 class="card-title mb-0 fw-bold"><?= lang('App.additional_information') ?></h5>
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
                            <?= lang('App.add_comment') ?>
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
        <div class="card" id="internal-notes-card">
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
        <?php endif; ?>
            </div>

    <!-- Sidebar -->
    <div class="col-xl-4">
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
                         alt="QR Code for Order SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
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
                <h5 class="card-title mb-0 fw-bold">
                    <i data-feather="zap" class="icon-sm me-2"></i>
                    Quick Actions
                </h5>
                <small class="text-muted">Actions for assigned contact: <?= $order['salesperson_name'] ?? lang('App.not_assigned') ?></small>
                </div>
                <div class="card-body">
                <div class="d-grid gap-3">
                    <!-- Update Status - Only for Staff and Admin Users -->
                    <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                    <div>
                        <label class="form-label fw-medium"><?= lang('App.update_status') ?></label>
                        <select class="form-select" id="statusSelect" onchange="updateStatus()">
                            <option value="pending" <?= ($order['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                            <option value="processing" <?= ($order['status'] ?? '') == 'processing' ? 'selected' : '' ?>>⚙️ Processing</option>
                            <option value="in_progress" <?= ($order['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>🔄 In Progress</option>
                            <option value="completed" <?= ($order['status'] ?? '') == 'completed' ? 'selected' : '' ?>>✅ Completed</option>
                            <option value="cancelled" <?= ($order['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                        </select>
                    </div>
                    <?php else: ?>
                    <!-- Status Display Only for Non-Staff Users -->
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
                            $statusIcon = $statusIcons[$order['status'] ?? 'pending'] ?? '';
                            echo $statusIcon . ' ' . lang('App.' . ($order['status'] ?? 'pending'));
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Contact Actions -->
                    <?php if (isset($order['salesperson_phone']) && $order['salesperson_phone']): ?>
                    <a href="tel:<?= $order['salesperson_phone'] ?>" class="btn btn-outline-info">
                        <i data-feather="phone" class="icon-sm me-2"></i>
                        Call Contact
                    </a>
                    <?php endif; ?>

                    <!-- SMS Action -->
                    <?php if (isset($order['salesperson_phone']) && $order['salesperson_phone']): ?>
                    <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#smsModal">
                        <i data-feather="message-square" class="icon-sm me-2"></i>
                        Send SMS
                    </button>
                    <?php endif; ?>

                    <!-- Email Action -->
                    <?php if (isset($order['salesperson_email']) && $order['salesperson_email']): ?>
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#emailModal">
                        <i data-feather="mail" class="icon-sm me-2"></i>
                        Send Email
                    </button>
                    <?php endif; ?>

                    <!-- Notification Action -->
                    <button class="btn btn-outline-secondary" onclick="sendNotification(<?= $order['id'] ?>)">
                        <i data-feather="bell" class="icon-sm me-2"></i>
                        Send Alert
                    </button>

                    <!-- QR Code Action -->
                    <button class="btn btn-outline-primary" onclick="generateQRCode(<?= $order['id'] ?>)">
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
                <h4><?= lang('App.service_order_not_found') ?></h4>
                <p class="text-muted"><?= lang('App.service_order_not_found_message') ?></p>
                <a href="<?= base_url('service_orders') ?>" class="btn btn-primary">
                            <i data-feather="arrow-left" class="icon-sm me-1"></i>
                    Back to Service Orders
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
                    QR Code - Service Order SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (isset($qr_data) && $qr_data): ?>
                <!-- QR Code Available -->
                <div class="mb-4">
                    <img src="<?= $qr_data['qr_url'] ?>" 
                         alt="QR Code for Service Order SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>" 
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
                    <h6 class="text-warning"><?= lang('App.qr_code_unavailable') ?></h6>
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

<!-- Mobile & Tablet Quick Actions Floating Button -->
<div class="quick-actions-float-btn d-xl-none" onclick="openQuickActionsModal()">
    <i data-feather="zap"></i>
</div>

<!-- Quick Actions Modal (Mobile & Tablet) -->
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
                        <small class="quick-actions-subtitle">Service Order <?= $order['ro_number'] ?? 'N/A' ?></small>
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
                    <!-- Status Section -->
                    <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
                    <div class="quick-action-section mb-4">
                        <label class="quick-action-label">
                            <i data-feather="refresh-cw" class="quick-action-label-icon"></i>
                            Update Status
                        </label>
                        <select class="form-select quick-action-select" id="statusSelectMobile" onchange="updateStatusFromModal()">
                            <option value="pending" <?= ($order['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                            <option value="processing" <?= ($order['status'] ?? '') == 'processing' ? 'selected' : '' ?>>⚙️ Processing</option>
                            <option value="in_progress" <?= ($order['status'] ?? '') == 'in_progress' ? 'selected' : '' ?>>🔄 In Progress</option>
                            <option value="completed" <?= ($order['status'] ?? '') == 'completed' ? 'selected' : '' ?>>✅ Completed</option>
                            <option value="cancelled" <?= ($order['status'] ?? '') == 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                        </select>
                    </div>
                    <?php else: ?>
                    <!-- Status Display Only for Non-Staff Users -->
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
                            $statusIcon = $statusIcons[$order['status'] ?? 'pending'] ?? '';
                            echo $statusIcon . ' ' . ucfirst(str_replace('_', ' ', $order['status'] ?? 'pending'));
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons Grid -->
                    <div class="quick-actions-buttons">
                        <!-- Update Status -->
                        <button class="quick-action-btn quick-action-status" onclick="updateStatusFromModal()">
                            <div class="quick-action-icon">
                                <i data-feather="refresh-cw"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Update Status</span>
                                <small class="quick-action-desc">Change order status</small>
                            </div>
                        </button>

                        <!-- Call Contact -->
                        <?php if (isset($order['salesperson_phone']) && $order['salesperson_phone']): ?>
                        <a href="tel:<?= $order['salesperson_phone'] ?>" class="quick-action-btn quick-action-call">
                            <div class="quick-action-icon">
                                <i data-feather="phone"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Call</span>
                                <small class="quick-action-desc">Contact directly</small>
                            </div>
                        </a>
                        <?php endif; ?>

                        <!-- Send SMS -->
                        <?php if (isset($order['salesperson_phone']) && $order['salesperson_phone']): ?>
                        <button class="quick-action-btn quick-action-sms" data-bs-toggle="modal" data-bs-target="#smsModal" onclick="closeQuickActionsModal()">
                            <div class="quick-action-icon">
                                <i data-feather="message-square"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">SMS</span>
                                <small class="quick-action-desc">Send message</small>
                            </div>
                        </button>
                        <?php endif; ?>

                        <!-- Send Email -->
                        <?php if (isset($order['salesperson_email']) && $order['salesperson_email']): ?>
                        <button class="quick-action-btn quick-action-email" data-bs-toggle="modal" data-bs-target="#emailModal" onclick="closeQuickActionsModal()">
                            <div class="quick-action-icon">
                                <i data-feather="mail"></i>
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
                                <i data-feather="bell"></i>
                            </div>
                            <div class="quick-action-content">
                                <span class="quick-action-title">Alert</span>
                                <small class="quick-action-desc">Send notification</small>
                            </div>
                        </button>

                        <!-- Generate QR -->
                        <button class="quick-action-btn quick-action-qr" onclick="generateQRCodeFromModal(<?= $order['id'] ?>)">
                            <div class="quick-action-icon">
                                <i data-feather="smartphone"></i>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Mark that we're on the service order view page
window.isServiceOrderViewPage = true;

function waitForServiceOrderViewDependencies() {
    if (typeof $ === 'undefined' || typeof Swal === 'undefined') {
        setTimeout(waitForServiceOrderViewDependencies, 100);
        return;
    }

    // Load service orders actions script if not already loaded
    if (typeof window.serviceOrdersActionsLoaded === 'undefined') {
        const script = document.createElement('script');
        script.src = '<?= base_url('assets/js/service_orders_actions.js') ?>';
        script.onload = function() {
            console.log('✅ Service Orders Actions script loaded successfully');
            
            // Initialize service orders actions
            if (typeof window.initServiceOrdersActions === 'function') {
                window.initServiceOrdersActions({
                    baseUrl: '<?= base_url() ?>',
                    csrfTokenName: '<?= csrf_token() ?>',
                    csrfHash: '<?= csrf_hash() ?>'
                });
            }
        };
        script.onerror = function() {
            console.error('❌ Failed to load Service Orders Actions script');
        };
        document.head.appendChild(script);
    }

    $(document).ready(function() {
        // Initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Initialize service order modal function
        window.initializeServiceOrderModal = function() {
            console.log('🔧 Initializing Service Order Modal...');
            
            // Initialize Select2 dropdowns
            if (typeof $.fn.select2 !== 'undefined') {
                $('#editServiceOrderModal .select2').select2({
                    dropdownParent: $('#editServiceOrderModal'),
                    width: '100%'
                });
            }
            
            // Initialize Choices.js if available
            if (typeof Choices !== 'undefined') {
                const choicesElements = document.querySelectorAll('#editServiceOrderModal .choices');
                choicesElements.forEach(element => {
                    if (!element.choicesInstance) {
                        element.choicesInstance = new Choices(element, {
                            searchEnabled: true,
                            itemSelectText: '',
                            removeItemButton: true
                        });
                    }
                });
            }
            
            // Initialize form submission
            const modalForm = document.querySelector('#editServiceOrderModal form');
            if (modalForm) {
                // Remove any existing handlers first
                $(modalForm).off('submit');
                
                modalForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleServiceOrderUpdate(this);
                });
                
                // Also override the jQuery handler that might be set by the global script
                setTimeout(() => {
                    $('#editServiceOrderModal form').off('submit').on('submit', function(e) {
                        e.preventDefault();
                        handleServiceOrderUpdate(this);
                    });
                }, 100);
            }
            
            // Initialize feather icons in modal
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            console.log('✅ Service Order Modal initialized');
        };

        // Handle service order update
        window.handleServiceOrderUpdate = function(form) {
            console.log('🔥 handleServiceOrderUpdate called with form:', form);
            
            const formData = new FormData(form);
            const orderId = <?= $order['id'] ?? 0 ?>;
            
            // Log form data for debugging
            console.log('📝 Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(`  ${key}: ${value}`);
            }
            
            // Find submit button in modal footer
            let submitBtn = document.querySelector('#editServiceOrderModal .modal-footer button[type="submit"]');
            if (!submitBtn) {
                // Try to find button by form attribute
                submitBtn = document.querySelector('button[type="submit"][form="serviceOrderForm"]');
            }
            if (!submitBtn) {
                // Try to find any primary button in the modal
                submitBtn = document.querySelector('#editServiceOrderModal .btn-primary');
            }
            
            let originalText = '';
            
            if (submitBtn) {
                originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-1"></i> Updating...';
                submitBtn.disabled = true;
                console.log('✅ Submit button found and disabled');
            } else {
                console.warn('⚠️ Submit button not found anywhere');
            }
            
            $.ajax({
                url: '<?= base_url('service_orders/update') ?>/' + orderId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Server response received:', response);
                    
                    if (response.success) {
                        console.log('✅ Update successful, processing response...');
                        
                        // Close modal
                        $('#editServiceOrderModal').modal('hide');
                        
                        // Update dashboard badges first
                        if (typeof window.refreshDashboardBadges === 'function') {
                            window.refreshDashboardBadges();
                        }
                        
                        // Update topbar info if we have the updated order data
                        if (response.order && typeof window.updateTopbarInfo === 'function') {
                            console.log('🚀 Calling updateTopbarInfo with:', response.order);
                            window.updateTopbarInfo(response.order);
                        } else {
                            console.log('⚠️ updateTopbarInfo not called:', {
                                hasOrder: !!response.order,
                                functionExists: typeof window.updateTopbarInfo === 'function',
                                response: response
                            });
                        }
                        
                        // Show success message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Service order updated successfully',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the page to show updated data
                                window.location.reload();
                            });
                        } else {
                            alert('Service order updated successfully');
                            // Update dashboard badges before reload
                            if (typeof window.refreshDashboardBadges === 'function') {
                                window.refreshDashboardBadges();
                            }
                            // Update topbar info before reload
                            if (response.order && typeof window.updateTopbarInfo === 'function') {
                                console.log('🚀 Calling updateTopbarInfo (no Swal) with:', response.order);
                                window.updateTopbarInfo(response.order);
                            } else {
                                console.log('⚠️ updateTopbarInfo (no Swal) not called:', {
                                    hasOrder: !!response.order,
                                    functionExists: typeof window.updateTopbarInfo === 'function',
                                    response: response
                                });
                            }
                            window.location.reload();
                        }
                    } else {
                        // Show error message
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to update service order'
                            });
                        } else {
                            alert(response.message || 'Failed to update service order');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error updating service order:', error);
                    
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while updating the service order'
                        });
                    } else {
                        alert('An error occurred while updating the service order');
                    }
                },
                complete: function() {
                    // Restore button state
                    if (submitBtn) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }
            });
        };

        // Live time update
        function updateLiveTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                second: '2-digit',
                hour12: true 
            });
            $('#liveCurrentTime').text(timeString);
        }

        // Update live time every second
        updateLiveTime();
        setInterval(updateLiveTime, 1000);

        // Status update form
        $('#statusUpdateForm').on('submit', function(e) {
            e.preventDefault();

            const status = $('#status').val();
            const orderId = <?= $order['id'] ?? 0 ?>;

            $.ajax({
                url: '<?= base_url('service_orders/updateStatus') ?>/' + orderId,
                method: 'POST',
                data: {
                    status: status,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: response.message || 'Service order status updated successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Update the top bar status badge
                            updateTopBarStatus(status);
                            
                            // Update dashboard badges
                            if (typeof window.refreshDashboardBadges === 'function') {
                                window.refreshDashboardBadges();
                            }
                            
                            // Update topbar info if we have the updated order data
                            if (response.order && typeof window.updateTopbarInfo === 'function') {
                                console.log('🚀 Calling updateTopbarInfo (status update) with:', response.order);
                                window.updateTopbarInfo(response.order);
                            } else {
                                console.log('⚠️ updateTopbarInfo (status update) not called:', {
                                    hasOrder: !!response.order,
                                    functionExists: typeof window.updateTopbarInfo === 'function',
                                    response: response
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: response.message || 'Failed to update service order status'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Status update error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the status'
                    });
                }
                });
        });



        // Comment form submission handled by the main comment system below
    });
    
    // Define topbar update functions directly here to ensure availability
    
    // Function to update topbar information
    window.updateTopbarInfo = function(orderData) {
        console.log('🔄 Updating topbar info with data:', orderData);
        
        // Update scheduled date and time - First column (Schedule Information)
        const scheduledValue = document.querySelector('.row.g-0 .col-xl-2:first-child .top-bar-value');
        const scheduledSub = document.querySelector('.row.g-0 .col-xl-2:first-child .top-bar-sub');
        
        console.log('📅 Found scheduled elements:', scheduledValue, scheduledSub);
        
        if (scheduledValue && scheduledSub) {
            if (orderData.date && orderData.time) {
                const orderDate = new Date(orderData.date + 'T' + orderData.time);
                scheduledValue.textContent = orderDate.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                scheduledSub.textContent = orderDate.toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit', 
                    hour12: true 
                });
                console.log('✅ Updated scheduled info:', scheduledValue.textContent, scheduledSub.textContent);
            } else {
                scheduledValue.textContent = orderData.date ? 
                    new Date(orderData.date).toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        year: 'numeric' 
                    }) : 
                    'Not scheduled';
                scheduledSub.textContent = orderData.time ? 
                    new Date('1970-01-01T' + orderData.time).toLocaleTimeString('en-US', { 
                        hour: 'numeric', 
                        minute: '2-digit', 
                        hour12: true 
                    }) : 
                    'No time set';
                console.log('✅ Updated scheduled info (partial):', scheduledValue.textContent, scheduledSub.textContent);
            }
        } else {
            console.error('❌ Could not find scheduled elements');
        }
        
        // Update time status
        if (typeof window.updateTimeStatus === 'function') {
            window.updateTimeStatus(orderData.date, orderData.time);
        }
        
        // Update last updated timestamp
        const lastUpdatedElement = document.getElementById('topBarLastUpdated');
        if (lastUpdatedElement) {
            const now = new Date();
            lastUpdatedElement.textContent = 'Last updated: ' + now.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric' 
            }) + ', ' + now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit', 
                hour12: true 
            });
            console.log('✅ Updated last updated timestamp');
        } else {
            console.error('❌ Could not find last updated element');
        }
    };

    // Function to update time status badge
    window.updateTimeStatus = function(date, time) {
        console.log('⏰ Updating time status with:', date, time);
        
        const timeStatusBadge = document.getElementById('timeStatusBadge');
        const timeRemainingDisplay = document.getElementById('timeRemainingDisplay');
        
        console.log('🔍 Found time status elements:', timeStatusBadge, timeRemainingDisplay);
        
        if (!timeStatusBadge || !timeRemainingDisplay) {
            console.error('❌ Could not find time status elements');
            return;
        }
        
        let timeStatusText = 'Not scheduled';
        let timeStatusClass = 'badge bg-secondary time-status-badge-large';
        let timeRemainingText = '';
        
        if (date && time) {
            const scheduledDateTime = new Date(date + 'T' + time);
            const now = new Date();
            const diff = scheduledDateTime.getTime() - now.getTime();
            const hoursRemaining = diff / (1000 * 60 * 60);
            
            if (hoursRemaining < 0) {
                // Past due
                timeStatusText = 'Delay';
                timeStatusClass = 'badge time-status-danger time-status-badge-large';
                
                const hoursOverdue = Math.abs(hoursRemaining);
                if (hoursOverdue < 1) {
                    const minutesOverdue = hoursOverdue * 60;
                    timeRemainingText = Math.round(minutesOverdue) + ' minutes overdue';
                } else if (hoursOverdue < 24) {
                    if (hoursOverdue == Math.floor(hoursOverdue)) {
                        timeRemainingText = Math.round(hoursOverdue) + ' hours overdue';
                    } else {
                        timeRemainingText = hoursOverdue.toFixed(1) + ' hours overdue';
                    }
                } else {
                    const daysOverdue = Math.floor(hoursOverdue / 24);
                    const remainingHours = hoursOverdue - (daysOverdue * 24);
                    if (remainingHours < 1) {
                        timeRemainingText = daysOverdue + ' days overdue';
                    } else {
                        timeRemainingText = daysOverdue + ' day' + (daysOverdue > 1 ? 's' : '') + ' ' + remainingHours.toFixed(1) + ' hours overdue';
                    }
                }
            } else if (hoursRemaining < 1) {
                // Less than 1 hour
                timeStatusText = 'Attention required';
                timeStatusClass = 'badge time-status-warning time-status-badge-large';
                
                const minutesRemaining = hoursRemaining * 60;
                timeRemainingText = Math.round(minutesRemaining) + ' minutes left';
            } else {
                // More than 1 hour
                timeStatusText = 'On time';
                timeStatusClass = 'badge time-status-on-time time-status-badge-large';
                
                if (hoursRemaining < 24) {
                    if (hoursRemaining == Math.floor(hoursRemaining)) {
                        timeRemainingText = Math.round(hoursRemaining) + ' hours left';
                    } else {
                        timeRemainingText = hoursRemaining.toFixed(1) + ' hours left';
                    }
                } else {
                    const daysRemaining = Math.floor(hoursRemaining / 24);
                    const remainingHours = hoursRemaining - (daysRemaining * 24);
                    if (remainingHours < 1) {
                        timeRemainingText = daysRemaining + ' days left';
                    } else {
                        timeRemainingText = daysRemaining + ' day' + (daysRemaining > 1 ? 's' : '') + ' ' + remainingHours.toFixed(1) + ' hours left';
                    }
                }
            }
        }
        
        // Update badge
        timeStatusBadge.className = timeStatusClass;
        timeStatusBadge.textContent = timeStatusText;
        
        // Update time remaining display
        timeRemainingDisplay.textContent = timeRemainingText;
        
        console.log('✅ Updated time status:', timeStatusText, 'with class:', timeStatusClass);
        console.log('✅ Updated time remaining:', timeRemainingText);
    };
    
    // Function to refresh dashboard badges
    window.refreshDashboardBadges = function() {
        console.log('🔄 Refreshing dashboard badges...');
        
        // Manually fetch and update badges
        fetch('<?= base_url('/service_orders/dashboard-data') ?>')
            .then(response => response.json())
            .then(data => {
                console.log('📊 Dashboard data received:', data);
                if (data.success) {
                    // Update badges
                    const todayBadge = document.getElementById('todayOrdersBadge');
                    const tomorrowBadge = document.getElementById('tomorrowOrdersBadge');
                    const pendingBadge = document.getElementById('pendingOrdersBadge');
                    const weekBadge = document.getElementById('weekOrdersBadge');
                    
                    if (todayBadge) {
                        todayBadge.textContent = data.todayCount || 0;
                        todayBadge.style.display = data.todayCount > 0 ? 'inline' : 'none';
                    }
                    
                    if (tomorrowBadge) {
                        tomorrowBadge.textContent = data.tomorrowCount || 0;
                        tomorrowBadge.style.display = data.tomorrowCount > 0 ? 'inline' : 'none';
                    }
                    
                    if (pendingBadge) {
                        pendingBadge.textContent = data.pendingCount || 0;
                        pendingBadge.style.display = data.pendingCount > 0 ? 'inline' : 'none';
                    }
                    
                    if (weekBadge) {
                        weekBadge.textContent = data.weekCount || 0;
                        weekBadge.style.display = data.weekCount > 0 ? 'inline' : 'none';
                    }
                    
                    // Update dashboard counts if elements exist
                    const todayCount = document.getElementById('todayOrdersCount');
                    const pendingCount = document.getElementById('pendingOrdersCount');
                    const weekCount = document.getElementById('weekOrdersCount');
                    const totalCount = document.getElementById('totalOrdersCount');
                    
                    if (todayCount) todayCount.textContent = data.todayCount || 0;
                    if (pendingCount) pendingCount.textContent = data.pendingCount || 0;
                    if (weekCount) weekCount.textContent = data.weekCount || 0;
                    if (totalCount) totalCount.textContent = data.totalCount || 0;
                    
                    console.log('✅ Dashboard badges updated successfully');
                }
            })
            .catch(error => {
                console.error('❌ Error refreshing dashboard badges:', error);
            });
    };
    
    // Test function availability
    console.log('🧪 Testing function availability:');
    console.log('updateTopbarInfo function exists:', typeof window.updateTopbarInfo === 'function');
    console.log('updateTimeStatus function exists:', typeof window.updateTimeStatus === 'function');
    console.log('refreshDashboardBadges function exists:', typeof window.refreshDashboardBadges === 'function');
    
    // Test selector availability
    console.log('🧪 Testing selector availability:');
    console.log('Scheduled value element:', document.querySelector('.row.g-0 .col-xl-2:first-child .top-bar-value'));
    console.log('Scheduled sub element:', document.querySelector('.row.g-0 .col-xl-2:first-child .top-bar-sub'));
    console.log('Time status badge:', document.getElementById('timeStatusBadge'));
    console.log('Time remaining display:', document.getElementById('timeRemainingDisplay'));
    console.log('Last updated element:', document.getElementById('topBarLastUpdated'));
}

// Language translations for JavaScript
const translations = {
    'App.pending': '<?= lang('App.pending') ?>',
    'App.completed': '<?= lang('App.completed') ?>',
    'App.cancelled': '<?= lang('App.cancelled') ?>',
    'App.in_progress': '<?= lang('App.in_progress') ?>',
    'App.processing': '<?= lang('App.processing') ?>',
    'App.on_time': '<?= lang('App.on_time') ?>',
    'App.delay': '<?= lang('App.delay') ?>',
    'App.attention_required': '<?= lang('App.attention_required') ?>',
    'App.not_scheduled': '<?= lang('App.not_scheduled') ?>'
};

// JavaScript lang function
function lang(key) {
    return translations[key] || key;
}

// Order data for JavaScript
const orderData = {
    id: <?= $order['id'] ?? 0 ?>,
    date: '<?= $order['date'] ?? '' ?>',
    time: '<?= $order['time'] ?? '' ?>',
    status: '<?= $order['status'] ?? '' ?>'
};

// Action functions
function goBack() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = '<?= base_url('service_orders') ?>';
    }
}

function editOrder(orderId) {
    console.log('✏️ Loading edit modal for service order:', orderId);
    
    // Load the modal form via AJAX
    $.ajax({
        url: '<?= base_url('service_orders/modal_form') ?>?edit=' + orderId,
        type: 'GET',
        beforeSend: function() {
            // Show loading state in modal body
            $('#editServiceOrderModal .modal-body').html(`
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading order data...</p>
                </div>
            `);
            $('#editServiceOrderModal').modal('show');
        },
        success: function(data) {
            // Load only the form content into the modal body
            $('#editServiceOrderModal .modal-body').html(data);
            
            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            // Initialize modal after content is loaded
            setTimeout(() => {
                if (typeof window.initializeServiceOrderModal === 'function') {
                    window.initializeServiceOrderModal();
                }
                
                // Check if order status requires readonly fields
                if (typeof window.checkServiceOrderStatusAndSetReadonly === 'function') {
                    setTimeout(() => {
                        window.checkServiceOrderStatusAndSetReadonly();
                    }, 200);
                }
                
                // Override the form handler to use our custom one (for view page)
                const modalForm = document.querySelector('#editServiceOrderModal form');
                if (modalForm) {
                    console.log('🔧 Setting up custom form handler for view page');
                    
                    // Remove any existing handlers
                    $(modalForm).off('submit');
                    
                    // Set our custom handler
                    $(modalForm).on('submit', function(e) {
                        e.preventDefault();
                        console.log('🚀 Custom handler triggered from view page');
                        handleServiceOrderUpdate(this);
                    });
                }
            }, 100);
        },
        error: function(xhr, status, error) {
            console.error('❌ Error loading edit form:', error);
            
            // Show error in modal
            $('#editServiceOrderModal .modal-body').html(`
                <div class="alert alert-danger">
                    <h6>Error Loading Form</h6>
                    <p>Failed to load order data for editing. Please try again.</p>
                </div>
            `);
        }
    });
}

function printOrder() {
    window.print();
}

function downloadPDF(orderId) {
    window.open('<?= base_url('service_orders/pdf') ?>/' + orderId, '_blank');
}

function updateStatus() {
    const status = document.getElementById('statusSelect').value;
    const orderId = orderData.id;

    $.ajax({
        url: '<?= base_url('service_orders/updateStatus') ?>/' + orderId,
        method: 'POST',
        data: {
            status: status,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
        Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    text: response.message || 'Service order status updated successfully',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    updateAllStatusBadges(status);
                    
                    // Update order data
                    orderData.status = status;
                    
                    // Update topbar info if response includes order data
                    if (response.order && typeof window.updateTopbarInfo === 'function') {
                        console.log('🔄 Updating topbar info from status update response:', response.order);
                        window.updateTopbarInfo(response.order);
                    }
                    
                    // Update dashboard badges
                    if (typeof window.refreshDashboardBadges === 'function') {
                        window.refreshDashboardBadges();
                    }
                    
                    // Refresh activities to show the status change
                    setTimeout(() => {
                        loadRecentActivity(true);
                    }, 500);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: response.message || 'Failed to update service order status'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Status update error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while updating the status'
            });
        }
    });
}

// Update top bar status badge
function updateTopBarStatus(status) {
    const badge = $('#topBarStatusBadge');
    const lastUpdated = $('#topBarLastUpdated');
    
    // Remove existing classes
    badge.removeClass('bg-warning bg-success bg-danger bg-info bg-primary');
    
    // Add appropriate class and text
    let statusClass = 'bg-warning';
    let statusText = lang('App.pending');
    
    switch(status) {
        case 'completed':
            statusClass = 'bg-success';
            statusText = lang('App.completed');
            break;
        case 'cancelled':
            statusClass = 'bg-danger';
            statusText = lang('App.cancelled');
            break;
        case 'in_progress':
            statusClass = 'bg-info';
            statusText = lang('App.in_progress');
            break;
        case 'processing':
            statusClass = 'bg-primary';
            statusText = lang('App.processing');
            break;
        case 'pending':
            statusClass = 'bg-warning';
            statusText = lang('App.pending');
            break;
    }
    
    badge.addClass(statusClass).text(statusText);
    
    // Update last updated time
    const now = new Date();
    const timeString = now.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    lastUpdated.text('Last updated: ' + timeString);
}

// Update all status badges throughout the page
function updateAllStatusBadges(status) {
    // Update top bar status badge
    updateTopBarStatus(status);
    
    // Update main order details status badge
    updateMainStatusBadge(status);
    
    // Update order data for future reference
    orderData.status = status;
}

// Update main order details status badge
function updateMainStatusBadge(status) {
    const mainStatusBadge = document.querySelector('.card-body .badge');
    if (mainStatusBadge) {
        // Remove existing classes
        mainStatusBadge.className = 'badge';
        
        // Add appropriate class and text
        let statusClass = 'bg-warning';
        let statusText = lang('App.pending');
        
        switch(status) {
            case 'completed':
                statusClass = 'bg-success';
                statusText = lang('App.completed');
                break;
            case 'cancelled':
                statusClass = 'bg-danger';
                statusText = lang('App.cancelled');
                break;
            case 'in_progress':
                statusClass = 'bg-info';
                statusText = lang('App.in_progress');
                break;
            case 'processing':
                statusClass = 'bg-primary';
                statusText = lang('App.processing');
                break;
            case 'pending':
                statusClass = 'bg-warning';
                statusText = lang('App.pending');
                break;
        }
        
        mainStatusBadge.classList.add(statusClass);
        mainStatusBadge.textContent = statusText;
    }
}

function sendSMS(orderId) {
    const advisorPhone = '<?= $order['salesperson_phone'] ?? '' ?>';
    const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    
    if (!advisorPhone) {
        showToast('warning', 'No phone number available for advisor');
        return;
    }
    
    Swal.fire({
        title: 'Send SMS',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">To: ${advisorPhone}</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message:</label>
                    <textarea id="smsMessage" class="form-control" rows="4" placeholder="Enter your message...">${orderNumber} - Service order update needed. Please review.</textarea>
                </div>
            </div>
        `,
            showCancelButton: true,
        confirmButtonText: 'Send SMS',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const message = document.getElementById('smsMessage').value.trim();
            if (!message) {
                Swal.showValidationMessage('Please enter a message');
                return false;
            }
            return message;
        }
        }).then((result) => {
            if (result.isConfirmed) {
            // Simulate SMS sending
            showToast('success', 'SMS sent successfully to advisor');
            
            // Log activity
            logOrderActivity(orderId, 'sms_sent', `SMS sent to advisor: ${advisorPhone}`, {
                phone: advisorPhone,
                message: message
            });
        }
    });
}

function sendEmail(orderId) {
    const advisorEmail = '<?= $order['salesperson_email'] ?? '' ?>';
    const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    const orderUrl = window.location.href;
    
    if (!advisorEmail) {
        showToast('warning', 'No email address available for advisor');
        return;
    }
    
    Swal.fire({
        title: 'Send Email',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">To:</label>
                    <input type="email" class="form-control" value="${advisorEmail}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Subject:</label>
                    <input type="text" id="emailSubject" class="form-control" value="Service Order ${orderNumber} - Update Required">
                </div>
                <div class="mb-3">
                    <label class="form-label">Message:</label>
                    <textarea id="emailMessage" class="form-control" rows="5" placeholder="Enter your message...">Hello,

Please review service order ${orderNumber}. 

Order Details: ${orderUrl}

Thank you.</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Send Email',
        confirmButtonColor: '#007bff',
        cancelButtonText: 'Cancel',
        width: '600px',
        preConfirm: () => {
            const subject = document.getElementById('emailSubject').value.trim();
            const message = document.getElementById('emailMessage').value.trim();
            if (!subject || !message) {
                Swal.showValidationMessage('Please fill in all fields');
                return false;
            }
            return { subject, message };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Simulate email sending
            showToast('success', 'Email sent successfully to advisor');
            
            // Log activity
            logOrderActivity(orderId, 'email_sent', `Email sent to advisor: ${advisorEmail}`, {
                recipient: advisorEmail,
                subject: result.value.subject,
                message: result.value.message
            });
        }
    });
}

function sendNotification(orderId) {
    const advisorName = '<?= $order['salesperson_name'] ?? 'Advisor' ?>';
    const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    
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
            
            // Simulate notification sending
            showToast('success', `${alertData.alertType.toUpperCase()} alert sent to advisor`);
            
            // Log activity
            logOrderActivity(orderId, 'alert_sent', `${alertData.alertType.toUpperCase()} alert sent to ${advisorName}: ${alertData.message}`);
        }
    });
}

// Log order activity (simulate for now)
function logOrderActivity(orderId, activityType, description) {
    console.log(`Activity logged for order ${orderId}:`, {
        type: activityType,
        description: description,
        timestamp: new Date().toISOString()
    });
    
    // In a real implementation, this would make an AJAX call to log the activity
    // $.ajax({
    //     url: '<?= base_url('service_orders/log-activity') ?>',
    //     method: 'POST',
    //     data: {
    //         order_id: orderId,
    //         activity_type: activityType,
    //         description: description,
    //         <?= csrf_token() ?>: '<?= csrf_hash() ?>'
    //     }
    // });
}

function generateQRCode(orderId) {
    console.log('🎯 Generating QR Code for service order:', orderId);
    
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
    console.log('⚠️ No QR data available for service order:', orderId);
}

function regenerateQRCode(orderId) {
    console.log('🔄 Regenerating QR Code for service order:', orderId);
    
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
            fetch(`<?= base_url('service_orders/regenerateQR') ?>/${orderId}`, {
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

function showQRModal() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    qrModal.show();
    <?php else: ?>
    showToast('warning', 'QR Code not available');
    <?php endif; ?>
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.select();
        element.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            showToast('success', 'Copied to clipboard!');
        } catch (err) {
            console.error('Failed to copy: ', err);
            showToast('error', 'Failed to copy to clipboard');
        }
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

// Copy VIN to clipboard
function copyVinToClipboard(element) {
    const vin = element.getAttribute('data-vin');
    
    if (!vin) {
        showToast('error', 'VIN not available');
        return;
    }
    
    // Use modern clipboard API if available
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(vin).then(() => {
            showToast('success', `VIN copied to clipboard: ${vin}`);
            
            // Add visual feedback
            element.style.transform = 'scale(1.1)';
            setTimeout(() => {
                element.style.transform = '';
            }, 200);
        }).catch(err => {
            console.error('❌ Clipboard API failed:', err);
            fallbackCopyVin(vin, element);
        });
    } else {
        fallbackCopyVin(vin, element);
    }
}

// Fallback VIN copy for older browsers
function fallbackCopyVin(vin, element) {
    const textarea = document.createElement('textarea');
    textarea.value = vin;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        showToast('success', `VIN copied to clipboard: ${vin}`);
        
        // Add visual feedback
        element.style.transform = 'scale(1.1)';
        setTimeout(() => {
            element.style.transform = '';
        }, 200);
    } catch (err) {
        console.error('❌ VIN copy failed:', err);
        showToast('error', 'Failed to copy VIN to clipboard');
    } finally {
        document.body.removeChild(textarea);
    }
}

function downloadQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const qrUrl = '<?= $qr_data['qr_url'] ?>';
    const link = document.createElement('a');
    link.href = qrUrl;
    link.download = 'service-order-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>-qr.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('success', 'QR Code downloaded!');
    <?php else: ?>
    showToast('warning', 'QR Code not available for download');
    <?php endif; ?>
}

function shareQRSimple() {
    <?php if (isset($qr_data) && $qr_data): ?>
    const shareData = {
        title: 'Service Order SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>',
        text: 'View Service Order Details',
        url: '<?= $qr_data['short_url'] ?>'
    };
    
    if (navigator.share) {
        navigator.share(shareData).then(() => {
            showToast('success', 'Shared successfully!');
        }).catch((err) => {
            console.log('Error sharing:', err);
            fallbackShare();
                            });
                        } else {
        fallbackShare();
    }
    
    function fallbackShare() {
        copyToClipboard('qrShortUrl');
        showToast('info', 'Link copied to clipboard for sharing');
    }
    <?php else: ?>
    showToast('warning', 'QR Code not available for sharing');
    <?php endif; ?>
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

// Update Status from Quick Actions Modal
function updateStatusFromModal() {
    const statusSelect = document.getElementById('statusSelectMobile');
    const newStatus = statusSelect.value;
    const orderId = <?= $order['id'] ?? 0 ?>;

    if (!newStatus) {
        showToast('error', 'Please select a status');
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

    $.ajax({
        url: '<?= base_url('service_orders/updateStatus') ?>/' + orderId,
        method: 'POST',
        data: {
            status: newStatus,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast('success', 'Status updated successfully!');
                
                // Update orderData
                orderData.status = newStatus;
                
                // Update all status badges
                updateAllStatusBadges(newStatus);
                
                // Update topbar info if response includes order data
                if (response.order && typeof window.updateTopbarInfo === 'function') {
                    window.updateTopbarInfo(response.order);
                }
                
                // Update dashboard badges
                if (typeof window.refreshDashboardBadges === 'function') {
                    window.refreshDashboardBadges();
                }
                
                // Update the desktop status select as well
                const desktopStatusSelect = document.getElementById('statusSelect');
                if (desktopStatusSelect) {
                    desktopStatusSelect.value = newStatus;
                }
                
                // Close modal
                closeQuickActionsModal();
                
                // Refresh activities
                setTimeout(() => {
                    loadRecentActivity(true);
                }, 500);
            } else {
                showToast('error', response.message || 'Failed to update status');
            }
        },
        error: function(xhr, status, error) {
            console.error('Status update error:', error);
            showToast('error', 'Error updating status');
        }
    })
    .finally(() => {
        // Restore select state
        statusSelect.disabled = false;
        
        // Remove loading option and restore original options
        statusSelect.innerHTML = originalSelectHtml;
        statusSelect.value = orderData.status; // Set to current status
    });
}

// Send Notification from Quick Actions Modal
function sendNotificationFromModal(orderId) {
    const advisorName = '<?= $order['salesperson_name'] ?? 'Advisor' ?>';
    const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    
    closeQuickActionsModal();
    
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
            
            // Simulate notification sending
            showToast('success', `${alertData.alertType.toUpperCase()} alert sent to advisor`);
            
            // Log activity
            logOrderActivity(orderId, 'alert_sent', `${alertData.alertType.toUpperCase()} alert sent to ${advisorName}: ${alertData.message}`);
        }
    });
}

// Generate QR Code from Quick Actions Modal
function generateQRCodeFromModal(orderId) {
    closeQuickActionsModal();
    generateQRCode(orderId);
    console.log('📱 Generate QR from modal:', orderId);
}

// ========================================
// END MOBILE QUICK ACTIONS FUNCTIONS
// ========================================

// Removed old loadComments function - using the one with pagination below

function loadRecentActivity() {
    const activityList = document.getElementById('activityList');
    if (!activityList) return;

    activityList.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2 mb-0">Loading activities...</p>
        </div>
    `;

    // Simulate loading activities
    setTimeout(() => {
        activityList.innerHTML = `
            <div class="activity-item d-flex align-items-start mb-3">
                <div class="activity-icon me-3">
                    <div class="avatar-xs bg-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i data-feather="edit" class="icon-xs text-white"></i>
                    </div>
                </div>
                <div class="activity-content flex-grow-1">
                    <h6 class="mb-1">Date Updated</h6>
                    <p class="text-muted mb-1">Date changed from 2025-05-23 to 2025-06-10</p>
                    <small class="text-muted">
                        <i data-feather="user" class="icon-xs me-1"></i>
                        Rudy Ruiz
                        <i data-feather="clock" class="icon-xs ms-2 me-1"></i>
                        Jun 10, 2025 at 12:28 AM
                    </small>
                </div>
            </div>
        `;
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 1000);
}

// Initialize when dependencies are loaded
waitForServiceOrderViewDependencies();

// Pagination state for infinite scroll
let activitiesPagination = {
    currentPage: 1,
    hasMore: true,
    loading: false
};

// Removed - consolidated into single DOMContentLoaded listener below

// Load recent activity function with infinite scroll support
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
    
    fetch(`<?= base_url('service_orders/getActivity/') ?>${orderId}?page=${page}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('🔍 getActivity response status:', response.status);
        console.log('🔍 getActivity response headers:', response.headers);
        
        if (response.status === 302) {
            console.error('❌ getActivity: Authentication required - redirected to login');
            throw new Error('Authentication required');
        }
        
        if (!response.ok) {
            console.error('❌ getActivity: HTTP error', response.status, response.statusText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.text().then(text => {
            console.log('🔍 getActivity raw response:', text.substring(0, 200) + '...');
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('❌ getActivity: Invalid JSON response:', e);
                console.error('❌ getActivity: Response text:', text);
                throw new Error('Invalid JSON response');
            }
        });
    })
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
            } else if (reset) {
                activityList.innerHTML = `
                    <div class="text-center py-3">
                        <i data-feather="activity" class="icon-lg text-muted mb-2"></i>
                        <p class="text-muted mb-0"><?= lang('App.no_activities_yet') ?></p>
                        <small class="text-muted"><?= lang('App.activities_will_appear') ?></small>
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
                        <i data-feather="alert-circle" class="icon-lg text-danger mb-2"></i>
                        <p class="text-muted mb-0"><?= lang('App.error_loading_activities') ?></p>
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
                    <p class="text-muted mb-0"><?= lang('App.error_loading_activities') ?></p>
                    <small class="text-muted"><?= lang('App.please_refresh_page') ?></small>
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

// Remove loading indicators
function removeActivitiesLoader() {
    const loader = document.getElementById('activityLoader');
    if (loader) loader.remove();
}

// Add load more button for manual loading
function addLoadMoreButton(container, type) {
    // Remove existing load more button
    const existingBtn = container.querySelector('.load-more-btn');
    if (existingBtn) existingBtn.remove();
    
    const loadMoreBtn = document.createElement('div');
    loadMoreBtn.className = 'text-center py-3 load-more-btn';
    loadMoreBtn.innerHTML = `
        <button class="btn btn-outline-primary btn-sm" onclick="loadMore${type.charAt(0).toUpperCase() + type.slice(1)}()">
            <i data-feather="chevron-down" class="icon-xs me-1"></i>
            Load More ${type.charAt(0).toUpperCase() + type.slice(1)}
        </button>
    `;
    container.appendChild(loadMoreBtn);
            
    // Re-initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Create activity HTML
function createActivityHtml(activity) {
    const iconClass = getActivityIcon(activity.type);
    const colorClass = getActivityColor(activity.type);
    
    // Check if this activity has message content that should be shown in tooltip
    let tooltipAttributes = '';
    let tooltipContent = '';
    
    if (activity.metadata) {
        let metadata = activity.metadata;
        
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
            if (metadata.message) {
                tooltipContent += `\n\nMessage:\n${metadata.message}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Comment activities - show complete comment
        else if (activity.type === 'comment_added' && metadata.comment_preview) {
            tooltipContent = `💬 Complete Comment:\n${metadata.comment_preview}`;
            if (metadata.has_attachments) {
                tooltipContent += '\n\n📎 This comment includes attachments';
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Reply activities - show complete reply
        else if (activity.type === 'comment_reply_added' && metadata.reply_preview) {
            tooltipContent = `↳ Complete Reply:\n${metadata.reply_preview}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Comment Update activities - show old and new content
        else if (activity.type === 'comment_updated' && (metadata.old_content || metadata.new_content)) {
            tooltipContent = `✏️ Comment Update:`;
            if (metadata.old_content) {
                tooltipContent += `\n\nOld: ${metadata.old_content}`;
            }
            if (metadata.new_content) {
                tooltipContent += `\n\nNew: ${metadata.new_content}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Comment Delete activities - show deleted content
        else if (activity.type === 'comment_deleted' && metadata.deleted_content) {
            const itemType = metadata.was_reply ? 'Reply' : 'Comment';
            tooltipContent = `🗑️ Deleted ${itemType}:\n${metadata.deleted_content}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Internal Note Added activities - show complete note content
        else if (activity.type === 'internal_note_added' && metadata.note_content) {
            tooltipContent = `📝 Complete Internal Note:\n${metadata.note_content}`;
            if (metadata.has_attachments) {
                tooltipContent += '\n\n📎 This note includes attachments';
            }
            if (metadata.mentions_count > 0) {
                tooltipContent += `\n\n@️ ${metadata.mentions_count} mention${metadata.mentions_count > 1 ? 's' : ''}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Internal Note Updated activities - show old and new content
        else if (activity.type === 'internal_note_updated' && (metadata.old_content_full || metadata.new_content_full)) {
            tooltipContent = `✏️ Internal Note Update:`;
            if (metadata.old_content_full) {
                tooltipContent += `\n\nOld: ${metadata.old_content_full}`;
            }
            if (metadata.new_content_full) {
                tooltipContent += `\n\nNew: ${metadata.new_content_full}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Internal Note Deleted activities - show deleted content
        else if (activity.type === 'internal_note_deleted' && metadata.deleted_content_full) {
            tooltipContent = `🗑️ Deleted Internal Note:\n${metadata.deleted_content_full}`;
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Field Change activities - show old and new values
        else if (activity.type === 'field_change' && metadata && (metadata.old_display_value || metadata.new_display_value)) {
            tooltipContent = `🔄 Field Change Details:`;
            if (metadata.field_label) {
                tooltipContent += `\n\nField: ${metadata.field_label}`;
            }
            if (metadata.old_display_value) {
                tooltipContent += `\n\nOld Value: ${metadata.old_display_value}`;
            }
            if (metadata.new_display_value) {
                tooltipContent += `\n\nNew Value: ${metadata.new_display_value}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
        // For Status Change activities - show old and new status
        else if (activity.type === 'status_change' && metadata && (metadata.old_status_label || metadata.new_status_label)) {
            tooltipContent = `🔄 Status Change Details:`;
            if (metadata.old_status_label) {
                tooltipContent += `\n\nOld Status: ${metadata.old_status_label}`;
            }
            if (metadata.new_status_label) {
                tooltipContent += `\n\nNew Status: ${metadata.new_status_label}`;
            }
            tooltipAttributes = `data-bs-toggle="tooltip" data-bs-placement="top" data-bs-html="false" title="${escapeForTooltipText(tooltipContent)}" class="activity-with-tooltip"`;
        }
    }
        
    return `
        <div class="activity-item d-flex mb-3" ${tooltipAttributes}>
            <div class="me-3">
                <div class="bg-light rounded-circle p-2 text-${colorClass}">
                    <i data-feather="${iconClass}" class="icon-sm"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1">${activity.title} ${tooltipContent ? '<i class="icon-xs ms-1 text-muted" data-feather="info"></i>' : ''}</h6>
                <p class="text-muted mb-1">${activity.description}</p>
                ${getActivityExtraInfo(activity)}
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

// Get extra info for activity display
function getActivityExtraInfo(activity) {
    if (!activity.metadata) return '';
    
    const metadata = activity.metadata;
    let extraInfo = '';
    
    // For field changes, show old and new values inline
    if (activity.type === 'field_change' && (metadata.old_display_value || metadata.new_display_value)) {
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
    if (activity.type === 'status_change' && (metadata.old_status_label || metadata.new_status_label)) {
        extraInfo += '<div class="mt-1 small">';
        if (metadata.old_status_label && metadata.new_status_label) {
            extraInfo += `<span class="text-danger me-2">From: <strong>${metadata.old_status_label}</strong></span>`;
            extraInfo += `<span class="text-success">To: <strong>${metadata.new_status_label}</strong></span>`;
        }
        extraInfo += '</div>';
    }
    
    return extraInfo;
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
        'comment_updated': 'edit-2',
        'comment_deleted': 'trash-2',
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
        'comment_reply_added': 'info',
        'comment_updated': 'warning',
        'comment_deleted': 'danger',
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

// Log order activity - Real database logging
function logOrderActivity(orderId, activityType, description, metadata = null) {
    // For SMS and Email, make actual AJAX calls to log in database
    if (activityType === 'sms_sent' && metadata && metadata.phone && metadata.message) {
        fetch(`<?= base_url('service_orders/logSMSSent/') ?>${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                phone: metadata.phone,
                message: metadata.message,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh activities to show the new one
                setTimeout(() => {
                    loadRecentActivity(true);
                }, 500);
            }
        })
        .catch(error => {
            console.error('Error logging SMS activity:', error);
        });
    } else if (activityType === 'email_sent' && metadata && metadata.recipient && metadata.subject) {
        fetch(`<?= base_url('service_orders/logEmailSent/') ?>${orderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                recipient: metadata.recipient,
                subject: metadata.subject,
                message: metadata.message || '',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh activities to show the new one
                setTimeout(() => {
                    loadRecentActivity(true);
                }, 500);
            }
        })
        .catch(error => {
            console.error('Error logging email activity:', error);
        });
    } else {
        // For other activity types, just refresh the activities
        // Status changes are already logged by the updateStatus method
        setTimeout(() => {
            loadRecentActivity(true);
        }, 500);
    }
}

// Get status label for display
function getStatusLabel(status) {
    const statusLabels = {
        'pending': lang('App.pending'),
        'processing': lang('App.processing'),
        'in_progress': lang('App.in_progress'),
        'completed': lang('App.completed'),
        'cancelled': lang('App.cancelled')
    };
    
    return statusLabels[status] || status.charAt(0).toUpperCase() + status.slice(1);
}

// ========================================
// SMS FUNCTIONS
// ========================================

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
        message = message.replace(/{order_number}/g, 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>');
        message = message.replace(/{client_name}/g, '<?= addslashes($order['client_name'] ?? lang('App.na')) ?>');
        message = message.replace(/{contact_name}/g, '<?= addslashes($order['salesperson_name'] ?? 'Contact') ?>');
        message = message.replace(/{vehicle}/g, '<?= addslashes($order['vehicle'] ?? lang('App.na')) ?>');
        message = message.replace(/{vin}/g, '<?= addslashes($order['vin'] ?? lang('App.na')) ?>');
        message = message.replace(/{service_name}/g, '<?= addslashes($order['service_name'] ?? lang('App.na')) ?>');
        message = message.replace(/{status}/g, '<?= ucfirst($order['status']) ?>');
        
        // Replace date/time variables
        const orderDate = '<?= $order['date'] ? date('M j, Y', strtotime($order['date'])) : lang('App.not_scheduled') ?>';
        const orderTime = '<?= $order['time'] ? date('g:i A', strtotime($order['time'])) : lang('App.not_scheduled') ?>';
        
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
        subject = subject.replace(/{order_number}/g, 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>');
        subject = subject.replace(/{client_name}/g, '<?= addslashes($order['client_name'] ?? lang('App.na')) ?>');
        subject = subject.replace(/{contact_name}/g, '<?= addslashes($order['salesperson_name'] ?? 'Contact') ?>');
        
        // Replace variables in message
        let message = template.content;
        message = message.replace(/{order_number}/g, 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>');
        message = message.replace(/{client_name}/g, '<?= addslashes($order['client_name'] ?? lang('App.na')) ?>');
        message = message.replace(/{contact_name}/g, '<?= addslashes($order['salesperson_name'] ?? 'Contact') ?>');
        message = message.replace(/{vehicle}/g, '<?= addslashes($order['vehicle'] ?? lang('App.na')) ?>');
        message = message.replace(/{vin}/g, '<?= addslashes($order['vin'] ?? lang('App.na')) ?>');
        message = message.replace(/{service_name}/g, '<?= addslashes($order['service_name'] ?? lang('App.na')) ?>');
        message = message.replace(/{status}/g, '<?= ucfirst($order['status']) ?>');
        
        // Replace date/time variables
        const orderDate = '<?= $order['date'] ? date('M j, Y', strtotime($order['date'])) : lang('App.not_scheduled') ?>';
        const orderTime = '<?= $order['time'] ? date('g:i A', strtotime($order['time'])) : lang('App.not_scheduled') ?>';
        
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

// Update SMS character count with enhanced visual validation
function updateSmsCharCount() {
    const messageField = document.getElementById('smsMessage');
    const charCountSpan = document.getElementById('smsCharCount');
    const charStatus = document.getElementById('smsCharStatus');
    const charWarning = document.getElementById('smsCharWarning');
    const lengthAlert = document.getElementById('smsLengthAlert');
    const sendButton = document.getElementById('smsSendButton');
    
    if (messageField && charCountSpan) {
        const message = messageField.value;
        const orderUrl = '<?= base_url('service_orders/view/' . ($order['id'] ?? '')) ?>';
        const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
        
        // Estimate final length including URL (assume ~23 chars for short URL)
        const estimatedUrlLength = 23;
        const finalMessage = `${message}\n\n${orderNumber}: [short-url]`;
        const estimatedLength = message.length + orderNumber.length + estimatedUrlLength + 4; // +4 for formatting
        
        charCountSpan.textContent = estimatedLength;
        
        // Reset all states
        messageField.classList.remove('border-success', 'border-warning', 'border-danger');
        messageField.style.boxShadow = '';
        const charCountContainer = charCountSpan.parentElement;
        charCountContainer.classList.remove('text-success', 'text-warning', 'text-danger');
        
        // Enhanced visual feedback based on character count
        if (estimatedLength > 160) {
            // Over limit - Red/Danger state
            charCountContainer.classList.add('text-danger');
            messageField.classList.add('border-danger');
            messageField.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
            
            // Update status badge
            if (charStatus) {
                charStatus.className = 'badge bg-danger-subtle text-danger pulse-danger';
                charStatus.innerHTML = '<i data-feather="x-circle" class="icon-xs me-1"></i>Too Long';
            }
            
            // Show warning text
            if (charWarning) {
                charWarning.style.display = 'inline';
                charWarning.className = 'ms-2 text-danger';
            }
            
            // Show alert
            if (lengthAlert) {
                lengthAlert.style.display = 'block';
                lengthAlert.classList.add('slide-down');
            }
            
            // Disable send button
            if (sendButton) {
                sendButton.disabled = true;
                sendButton.classList.add('btn-disabled');
            }
            
        } else if (estimatedLength > 140) {
            // Warning zone - Yellow/Warning state (Getting Close)
            charCountContainer.classList.add('text-warning');
            messageField.classList.add('border-warning');
            messageField.style.boxShadow = '0 0 0 0.2rem rgba(255, 193, 7, 0.25)';
            
            // Update status badge
            if (charStatus) {
                charStatus.className = 'badge bg-warning-subtle text-warning pulse-warning';
                charStatus.innerHTML = '<i data-feather="alert-triangle" class="icon-xs me-1"></i>Getting Close';
            }
            
            // Hide warning text
            if (charWarning) {
                charWarning.style.display = 'none';
            }
            
            // Hide alert
            if (lengthAlert) {
                lengthAlert.style.display = 'none';
                lengthAlert.classList.remove('slide-down');
            }
            
            // Enable send button
            if (sendButton) {
                sendButton.disabled = false;
                sendButton.classList.remove('btn-disabled');
            }
            
        } else {
            // Good zone - Green/Success state
            charCountContainer.classList.add('text-success');
            messageField.classList.add('border-success');
            messageField.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            
            // Update status badge
            if (charStatus) {
                charStatus.className = 'badge bg-success-subtle text-success';
                charStatus.innerHTML = '<i data-feather="check" class="icon-xs me-1"></i>Good';
            }
            
            // Hide warning text
            if (charWarning) {
                charWarning.style.display = 'none';
            }
            
            // Hide alert
            if (lengthAlert) {
                lengthAlert.style.display = 'none';
                lengthAlert.classList.remove('slide-down');
            }
            
            // Enable send button
            if (sendButton) {
                sendButton.disabled = false;
                sendButton.classList.remove('btn-disabled');
            }
        }
        
        // Re-initialize feather icons for dynamic content
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
        subjectField.value = 'Update on Order SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
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
    const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
    const estimatedUrlLength = 23;
    const estimatedLength = message.length + orderNumber.length + estimatedUrlLength + 4;
    
    if (estimatedLength > 160) {
        showToast('error', 'Message is too long for SMS. Please shorten your message.');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#smsForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i data-feather="loader" class="icon-sm me-1 spinning"></i>Sending...';
    
    try {
        // Create final message with order URL
        const orderUrl = '<?= base_url('service_orders/view/' . ($order['id'] ?? '')) ?>';
        const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
        const finalMessage = `${message}\n\n${orderNumber}: ${orderUrl}`;
        
        // Send SMS
        const response = await fetch(`<?= base_url('service_orders/sendSMS/') ?>${orderId}`, {
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
        const orderUrl = '<?= base_url('service_orders/view/' . ($order['id'] ?? '')) ?>';
        const orderNumber = 'SER-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?>';
        const finalMessage = `${message}\n\nView details: <a href="${orderUrl}" target="_blank">${orderNumber}</a>`;
        
        // Send Email
        const response = await fetch(`<?= base_url('service_orders/sendEmail/') ?>${orderId}`, {
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
    const smsModal = document.getElementById('smsModal');
    const emailModal = document.getElementById('emailModal');
    
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
            const messageField = document.getElementById('smsMessage');
            const charStatus = document.getElementById('smsCharStatus');
            const charWarning = document.getElementById('smsCharWarning');
            const lengthAlert = document.getElementById('smsLengthAlert');
            const sendButton = document.getElementById('smsSendButton');
            
            // Clear form values
            messageField.value = '';
            document.getElementById('smsTemplate').value = '';
            
            // Reset all visual states
            messageField.classList.remove('border-success', 'border-warning', 'border-danger');
            messageField.style.boxShadow = '';
            
            // Reset character count container classes
            const charCountContainer = document.querySelector('.sms-char-count-container');
            if (charCountContainer) {
                charCountContainer.classList.remove('text-success', 'text-warning', 'text-danger');
                charCountContainer.classList.add('text-success');
            }
            
            if (charStatus) {
                charStatus.className = 'badge bg-success-subtle text-success';
                charStatus.innerHTML = '<i data-feather="check" class="icon-xs me-1"></i>Good';
            }
            
            if (charWarning) {
                charWarning.style.display = 'none';
            }
            
            if (lengthAlert) {
                lengthAlert.style.display = 'none';
                lengthAlert.classList.remove('slide-down');
            }
            
            if (sendButton) {
                sendButton.disabled = false;
                sendButton.classList.remove('btn-disabled');
            }
            
            // Update character count
            updateSmsCharCount();
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
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

// Removed - consolidated into single DOMContentLoaded listener below

// ========================================
// COMMENTS SYSTEM
// ========================================

// Global variables for comments system
let commentsState = {
    currentPage: 1,
    isLoading: false,
    hasMore: true,
    totalComments: 0,
    loadedComments: [],
    perPage: 5
};
    let selectedFiles = [];
    let mentionUsers = [];

// Global function for refresh button
function loadComments(page = 1, append = false) {
    // Prevent multiple simultaneous requests
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
        page = 1;
    }
    
    commentsState.isLoading = true;
        const serviceOrderId = <?= $order['id'] ?? 0 ?>;

    console.log('Loading comments:', {
        orderId: serviceOrderId,
        page: page,
        append: append,
        currentState: commentsState
    });
        
        fetch(`<?= base_url('service_orders/getComments/') ?>${serviceOrderId}?page=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.status === 302) {
                console.error('Authentication required - redirected to login');
                showError('Authentication required. Please refresh the page and try again.');
            return null;
        }
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            return response.json();
        })
        .then(data => {
        if (!data) return; // Handle redirect case
            
            console.log('Comments response:', data);
            
            if (data.success) {
            // Update pagination state
            if (data.pagination) {
                commentsState.hasMore = data.pagination.has_more;
                commentsState.totalComments = data.pagination.total;
                commentsState.currentPage = data.pagination.current_page;
            }
            
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
            
            // Update UI based on pagination state
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
        addLoadMoreCommentsButton();
    }
    
    console.log('Comments UI updated:', {
        hasMore: commentsState.hasMore,
        loadedCount: commentsState.loadedComments.length,
        totalCount: commentsState.totalComments
    });
}

// Update comments count badge
function updateCommentsCount(count) {
    const countBadge = document.getElementById('commentsCount');
    if (countBadge) {
        countBadge.textContent = count;
    }
}

// Global functions for comments system

    // File upload functionality
    function initializeFileUpload() {
        const dropzone = $('#fileDropzone');
        const fileInput = $('#fileInput');
        const previewArea = $('#filePreviewArea');
        const previewList = $('#filePreviewList');

        // Click to browse
        dropzone.click(function() {
            fileInput.click();
        });

        // File input change
        fileInput.change(function() {
            handleFiles(this.files);
        });

        // Drag and drop
        dropzone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });

        dropzone.on('dragleave dragend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });

        dropzone.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
            
            const files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });
    }

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (validateFile(file)) {
                selectedFiles.push(file);
                addFilePreview(file);
            }
        });

        updateFilePreviewVisibility();
    }

    function validateFile(file) {
        const maxSizes = {
            'image': 10 * 1024 * 1024, // 10MB
            'video': 100 * 1024 * 1024, // 100MB
            'document': 25 * 1024 * 1024 // 25MB
        };

        let fileType = 'document';
        if (file.type.startsWith('image/')) fileType = 'image';
        else if (file.type.startsWith('video/')) fileType = 'video';

        if (file.size > maxSizes[fileType]) {
            showError(`File "${file.name}" is too large. Maximum size for ${fileType}s is ${maxSizes[fileType] / (1024 * 1024)}MB.`);
            return false;
        }

        return true;
    }

    function addFilePreview(file) {
        const fileId = 'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        const isImage = file.type.startsWith('image/');
        
        let previewContent = '';
        if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(`#${fileId} .file-preview-image`).attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            previewContent = '<img class="file-preview-image" src="" alt="Preview">';
        } else {
            previewContent = `<div class="file-preview-placeholder">
                <i class="ri-file-line fs-2 text-muted"></i>
            </div>`;
        }

        const previewHtml = `
            <div class="file-preview-item" id="${fileId}">
                ${previewContent}
                <div class="file-preview-info">
                    <div class="file-preview-name">${file.name}</div>
                    <div class="file-preview-size">${formatFileSize(file.size)}</div>
                </div>
                <button type="button" class="file-preview-remove" onclick="removeFile('${fileId}')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        `;

        $('#filePreviewList').append(previewHtml);
    }

    window.removeFile = function(fileId) {
        const index = selectedFiles.findIndex((file, idx) => 
            'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9) === fileId
        );
        
        if (index > -1) {
            selectedFiles.splice(index, 1);
        }
        
        $(`#${fileId}`).remove();
        updateFilePreviewVisibility();
    };

    function updateFilePreviewVisibility() {
        const previewArea = $('#filePreviewArea');
        if (selectedFiles.length > 0) {
            previewArea.show();
        } else {
            previewArea.hide();
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Mentions functionality
    function initializeMentions() {
        const commentTextarea = $('#commentText');
        const suggestionsContainer = $('#mentionSuggestions');
        const suggestionsList = $('.mention-suggestions-list');
        
        let currentMentionQuery = '';
        let mentionStartPos = -1;
        let selectedSuggestionIndex = -1;

        // Load users for mentions
        loadMentionUsers();

        commentTextarea.on('input keyup', function(e) {
            const text = $(this).val();
            const cursorPos = this.selectionStart;
            
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
        });

        commentTextarea.on('keydown', function(e) {
            if (suggestionsContainer.is(':visible')) {
                const suggestions = $('.mention-suggestion-item');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, suggestions.length - 1);
                    updateSuggestionSelection();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, 0);
                    updateSuggestionSelection();
                } else if (e.key === 'Enter' || e.key === 'Tab') {
                    e.preventDefault();
                    if (selectedSuggestionIndex >= 0) {
                        selectMention(suggestions.eq(selectedSuggestionIndex));
                    }
                } else if (e.key === 'Escape') {
                    hideMentionSuggestions();
                }
            }
        });

        function loadMentionUsers() {
        // Load staff users for mentions from the server
        fetch('<?= base_url('service_orders/getStaffUsers') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mentionUsers = data.users;
                console.log('Loaded users for mentions:', mentionUsers);
            } else {
                console.error('Failed to load users for mentions:', data.message);
                // Fallback to hardcoded users
            mentionUsers = [
                    { id: 1, username: 'admin', name: 'Administrator' },
                    { id: 2, username: 'staff', name: 'Staff User' }
                ];
            }
        })
        .catch(error => {
            console.error('Error loading users for mentions:', error);
            // Fallback to hardcoded users
            mentionUsers = [
                { id: 1, username: 'admin', name: 'Administrator' },
                { id: 2, username: 'staff', name: 'Staff User' }
            ];
        });
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

        const suggestionsHtml = filteredUsers.map((user, index) => `
            <div class="mention-suggestion-item ${index === 0 ? 'selected' : ''}" data-user-id="${user.id}" data-username="${user.username}">
                <strong>@${user.username}</strong> - ${user.name}
                </div>
            `).join('');

        suggestionsContainer.html(suggestionsHtml).show();
            selectedSuggestionIndex = 0;

            // Click handler for suggestions
            $('.mention-suggestion-item').click(function() {
                selectMention($(this));
            });
        }

        function hideMentionSuggestions() {
            suggestionsContainer.hide();
            selectedSuggestionIndex = -1;
        }

        function updateSuggestionSelection() {
        $('.mention-suggestion-item').removeClass('selected');
        $('.mention-suggestion-item').eq(selectedSuggestionIndex).addClass('selected');
        }

        function selectMention($suggestion) {
            const username = $suggestion.data('username');
            const textarea = commentTextarea[0];
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
}

// Comments scroll functionality
function initializeCommentsScroll() {
    const commentsContainer = document.getElementById('commentsList');
    if (!commentsContainer) {
        console.log('Comments container not found for scroll initialization');
        return;
    }

    let scrollTimeout;
    let lastScrollTop = 0;
    
    console.log('Initializing infinite scroll for comments container');
    
    commentsContainer.addEventListener('scroll', function() {
        const currentScrollTop = commentsContainer.scrollTop;
        
        // Only trigger on downward scroll
        if (currentScrollTop <= lastScrollTop) {
            lastScrollTop = currentScrollTop;
            return;
        }

        lastScrollTop = currentScrollTop;
        
        // Debounce scroll events to prevent multiple rapid calls
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Check if we're near the bottom (within 50px for better UX)
            const scrollTop = commentsContainer.scrollTop;
            const scrollHeight = commentsContainer.scrollHeight;
            const clientHeight = commentsContainer.clientHeight;
            const nearBottom = scrollTop + clientHeight >= scrollHeight - 50;
            
            console.log('Scroll check:', {
                scrollTop,
                scrollHeight,
                clientHeight,
                nearBottom,
                hasMore: commentsState.hasMore,
                isLoading: commentsState.isLoading,
                loadedCount: commentsState.loadedComments.length
            });
            
            if (nearBottom && commentsState.hasMore && !commentsState.isLoading) {
                console.log('Infinite scroll triggered - loading more comments');
                loadMoreComments();
            }
        }, 300); // Increased debounce to 300ms for better performance
    });
}

// Load more comments function (removed - using global version below)

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

// Get current user avatar
function getCurrentUserAvatar() {
    // Use the avatar from the internal notes system if available
    if (window.internalNotesInstance && window.internalNotesInstance.currentUser) {
        return window.internalNotesInstance.currentUser.avatar;
    }
    // Fallback to generic avatar
    return 'https://ui-avatars.com/api/?name=You&size=24&background=007bff&color=ffffff&bold=true&format=png';
}

// Process comment mentions
function processCommentMentions(comment, mentions) {
    let processedComment = comment;
    
    if (mentions && mentions.length > 0) {
        mentions.forEach(mention => {
            const mentionRegex = new RegExp(`@${mention.username}\\b`, 'gi');
            processedComment = processedComment.replace(mentionRegex, 
                `<a href="#" class="comment-mention" data-user-id="${mention.user_id}">@${mention.username}</a>`
            );
        });
    }
    
    return processedComment;
}

// Pagination and load more functions (legacy function removed)

function addLoadMoreCommentsButton() {
    const container = $('#commentsList');
    if (container.find('#loadMoreCommentsBtn').length > 0) {
        return; // Button already exists
    }
    
    const loadMoreBtn = $(`
        <div id="loadMoreCommentsBtn" class="text-center py-3" style="border-top: 1px solid #e9ecef; margin-top: 1rem; padding-top: 1rem;">
            <button class="btn btn-outline-primary btn-sm" onclick="loadMoreComments()">
                <i class="ri-arrow-down-line me-1"></i>
                Load More Comments
            </button>
        </div>
    `);
    container.append(loadMoreBtn);
}

function removeLoadMoreCommentsButton() {
    $('#loadMoreCommentsBtn').remove();
}

function showCommentsLoadingIndicator() {
    const container = $('#commentsList');
    if (container.find('#commentsLoadingIndicator').length > 0) {
        return; // Indicator already exists
    }
    
    const loadingIndicator = $(`
        <div id="commentsLoadingIndicator" class="text-center py-3" style="border-top: 1px solid #e9ecef; margin-top: 1rem; padding-top: 1rem;">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-2 mb-0 small">Loading more comments...</p>
        </div>
    `);
    container.append(loadingIndicator);
}

function hideCommentsLoadingIndicator() {
    $('#commentsLoadingIndicator').remove();
}

// Load more comments function (global)
function loadMoreComments() {
    if (!commentsState.hasMore || commentsState.isLoading) {
        console.log('Cannot load more comments:', {
            hasMore: commentsState.hasMore,
            isLoading: commentsState.isLoading
        });
        return;
    }
    
    console.log('Loading more comments - current page:', commentsState.currentPage);
    
    // Show loading indicator
    showCommentsLoadingIndicator();
    
    // Load next page
    const nextPage = commentsState.currentPage + 1;
    loadComments(nextPage, true);
}

// Make it available globally for onclick handlers
window.loadMoreComments = loadMoreComments;

    // Attachment viewing functions
    window.viewAttachment = function(url, type) {
        if (type === 'image') {
            // Open image in modal or lightbox
            window.open(url, '_blank');
        } else if (type === 'video') {
            // Open video in modal or new tab
            window.open(url, '_blank');
        }
    };

    window.downloadAttachment = function(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Edit comment function
    window.editComment = function(commentId, currentText) {
        const commentItem = $(`.comment-item[data-comment-id="${commentId}"]`);
        const contentDiv = commentItem.find('.comment-content');
        
        // Create edit form
        const editForm = `
            <div class="comment-edit-form">
                <textarea class="form-control mb-2" id="editCommentText_${commentId}" rows="3">${currentText}</textarea>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="saveComment(${commentId})">
                        <i class="ri-save-line me-1"></i>Save
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit(${commentId})">
                        <i class="ri-close-line me-1"></i>Cancel
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
    };

    // Save edited comment
    window.saveComment = function(commentId) {
        const newText = $(`#editCommentText_${commentId}`).val().trim();
        
        if (!newText) {
            showError('Comment cannot be empty');
            return;
        }

        const formData = new FormData();
        formData.append('description', newText);

        fetch(`<?= base_url('service_orders/updateComment/') ?>${commentId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
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
                
                // Refresh recent activities
                loadRecentActivity(true);
                
                showSuccess('Comment updated successfully');
            } else {
                showError(data.message || 'Failed to update comment');
            }
        })
        .catch(error => {
            console.error('Error updating comment:', error);
            showError('Error updating comment');
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
                    <button type="button" class="btn btn-sm btn-success" onclick="saveCommentReply(${replyId})">
                        <i class="ri-save-line me-1"></i>Save
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="cancelReplyEdit(${replyId})">
                        <i class="ri-close-line me-1"></i>Cancel
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
    };

    // Save edited reply
    window.saveCommentReply = function(replyId) {
        const newText = $(`#editReplyText_${replyId}`).val().trim();
        
        if (!newText) {
            showError('Reply cannot be empty');
            return;
        }

        const formData = new FormData();
        formData.append('description', newText);

        fetch(`<?= base_url('service_orders/updateComment/') ?>${replyId}`, {
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
                const replyItem = $(`.comment-reply[data-comment-id="${replyId}"]`);
                const contentDiv = replyItem.find('.reply-content');
                
                // Update content
                contentDiv.attr('data-original-text', newText);
                contentDiv.html(processCommentMentions(newText, data.comment.mentions || []));
                
                // Remove edit form and show original content
                replyItem.find('.reply-edit-form').remove();
                contentDiv.show();
                replyItem.find('.reply-actions').show();
                
                // Refresh recent activities
                loadRecentActivity(true);
                
                showSuccess('Reply updated successfully');
            } else {
                showError(data.message || 'Failed to update reply');
            }
        })
        .catch(error => {
            console.error('Error updating reply:', error);
            showError('Error updating reply');
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

    // Process comment mentions
    window.processCommentMentions = function(content, mentions) {
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
                fetch(`<?= base_url('service_orders/deleteComment/') ?>${commentId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove comment from UI with animation
                        const commentItem = $(`.comment-item[data-comment-id="${commentId}"]`);
                        commentItem.fadeOut(300, function() {
                            $(this).remove();
                        });
                        
                        // Update comments count (decrease by 1)
                        const currentCount = parseInt(document.getElementById('commentsCount').textContent);
                        updateCommentsCount(Math.max(0, currentCount - 1));
                        
                        // Refresh recent activities
                        loadRecentActivity(true);
                        
                        showSuccess('Comment deleted successfully');
                    } else {
                        showError(data.message || 'Failed to delete comment');
                    }
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                    showError('Error deleting comment');
                });
                         }
         });
    };

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
        formData.append('order_id', <?= $order['id'] ?? 0 ?>);
        formData.append('parent_comment_id', commentId);
        formData.append('comment', replyText);

        fetch('<?= base_url('service_orders/addReply') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new reply to the UI
                const repliesContainer = $(`#replies_${commentId}`);
                const newReplyHtml = createRepliesHtml([data.reply]);
                repliesContainer.append(newReplyHtml);
                
                // Hide and reset the reply form
                cancelReply(commentId);
                
                // Refresh recent activities
                loadRecentActivity(true);
                
                showSuccess('Reply added successfully');
            } else {
                showError(data.message || 'Failed to add reply');
            }
        })
        .catch(error => {
            console.error('Error adding reply:', error);
            showError('Error adding reply');
        });
    };

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
                <i class="ri-chat-3-line fs-1 mb-3"></i>
                <p>No comments yet. Be the first to add a comment!</p>
            </div>
        `);
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
        console.log('Existing comment IDs:', Array.from(existingIds));
        console.log('New comment IDs:', newComments.map(c => c.id));
        
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
}

    function createRepliesHtml(replies) {
        if (!replies || replies.length === 0) return '';
        
        return replies.map(reply => {
            const currentUserId = <?= auth()->id() ?? 0 ?>;
            const canEdit = currentUserId && (currentUserId == reply.created_by || currentUserId == reply.user_id);
            
            const replyActionButtons = canEdit ? `
                <div class="reply-actions">
                    <button type="button" class="btn btn-xs btn-outline-primary" onclick="editReply(${reply.id}, '${reply.description || reply.comment}')">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="deleteComment(${reply.id})">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            ` : '';
            
            const processedReply = processCommentMentions(reply.description || reply.comment, reply.mentions || []);
            
            return `
                <div class="comment-reply" data-comment-id="${reply.id}">
                    <div class="reply-header">
                        <div class="d-flex align-items-center">
                            <img src="${reply.avatar_url || 'https://ui-avatars.com/api/?name=U&size=20&background=6c757d&color=ffffff&bold=true&format=png'}" 
                                 alt="${reply.first_name} ${reply.last_name}" 
                                 class="reply-avatar">
                            <div class="reply-user-info ms-2">
                                <p class="reply-user-name mb-0">${reply.first_name} ${reply.last_name}</p>
                                <p class="reply-timestamp mb-0" title="${reply.created_at_formatted}">
                                    ${reply.created_at_relative}
                                </p>
                            </div>
                        </div>
                        ${replyActionButtons}
                    </div>
                    <div class="reply-content" data-original-text="${reply.description || reply.comment}">${processedReply}</div>
                </div>
            `;
        }).join('');
    }

    function createCommentHtml(comment) {
        const attachmentsHtml = comment.attachments && comment.attachments.length > 0 
            ? createAttachmentsHtml(comment.attachments) 
            : '';

        const processedComment = processCommentMentions(comment.description || comment.comment, comment.mentions || []);

        // Check if current user can edit/delete this comment
        const currentUserId = <?= auth()->id() ?? 0 ?>;
        const canEdit = currentUserId && (currentUserId == comment.created_by || currentUserId == comment.user_id);
        
        const actionButtons = `
            <div class="comment-actions">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleReplyForm(${comment.id})">
                    <i class="ri-reply-line"></i>
                </button>
                ${canEdit ? `
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editComment(${comment.id}, '${comment.description || comment.comment}')">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                ` : ''}
            </div>
        `;

        return `
            <div class="comment-item fade-in" data-comment-id="${comment.id}">
                <div class="comment-header">
                    <div class="d-flex align-items-center">
                        <img src="${comment.avatar_url || comment.avatar || 'https://ui-avatars.com/api/?name=U&size=32&background=6c757d&color=ffffff&bold=true&format=png'}" 
                             alt="${comment.first_name} ${comment.last_name}" 
                             class="comment-avatar">
                        <div class="comment-user-info ms-2">
                            <p class="comment-user-name mb-0">${comment.first_name} ${comment.last_name}</p>
                            <p class="comment-timestamp mb-0" title="${comment.created_at_formatted}">
                                ${comment.created_at_relative}
                            </p>
                        </div>
                    </div>
                    ${actionButtons}
                </div>
                <div class="comment-content" data-original-text="${comment.description || comment.comment}">${processedComment}</div>
                ${attachmentsHtml}
                
                <!-- Reply Form (initially hidden) -->
                <div class="reply-form" id="replyForm_${comment.id}" style="display: none;">
                    <div class="d-flex gap-2 mt-3">
                        <img src="${getCurrentUserAvatar()}" alt="You" class="reply-avatar">
                        <div class="flex-grow-1">
                            <textarea class="form-control" id="replyText_${comment.id}" rows="2" placeholder="Write a reply..."></textarea>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-sm btn-primary" onclick="submitReply(${comment.id})">
                                    <i class="ri-send-plane-line me-1"></i>Reply
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

    function createAttachmentsHtml(attachments) {
        // Ensure attachments is always an array
        if (!attachments) return '';
        
        // Handle different data types
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

            if (isImage) {
                return `
                    <div class="attachment-item" onclick="viewAttachment('${attachment.url}', 'image')">
                        <img src="${attachment.thumbnail || attachment.url}" 
                             alt="${attachment.original_name}" 
                             class="attachment-image">
                        <div class="attachment-overlay">
                            <i class="ri-eye-line fs-4"></i>
                        </div>
                    </div>
                `;
            } else if (isVideo) {
                return `
                    <div class="attachment-item" onclick="viewAttachment('${attachment.url}', 'video')">
                        <video class="attachment-video" preload="metadata">
                            <source src="${attachment.url}" type="${attachment.mime_type}">
                        </video>
                        <div class="attachment-overlay">
                            <i class="ri-play-circle-line fs-4"></i>
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="attachment-item" onclick="downloadAttachment('${attachment.url}', '${attachment.original_name}')">
                        <div class="attachment-document">
                            <i class="ri-file-text-line"></i>
                            <p class="attachment-name">${attachment.original_name}</p>
                        </div>
                        <div class="attachment-overlay">
                            <i class="ri-download-line fs-4"></i>
                        </div>
                    </div>
                `;
            }
        }).join('');

        return `
            <div class="comment-attachments">
                <div class="comment-attachments-title">
                    <i class="ri-attachment-line me-1"></i>Attachments (${attachmentsArray.length})
                </div>
                <div class="attachment-grid">
                    ${attachmentItems}
                </div>
            </div>
        `;
    }

    // Removed duplicate functions - using global versions above

// Initialize comments system when document is ready
$(document).ready(function() {
    // Check if user is authenticated before initializing comments
    console.log('Initializing comments system...');
    console.log('Current user authenticated:', <?= auth()->loggedIn() ? 'true' : 'false' ?>);
    console.log('User ID:', <?= auth()->id() ?? 'null' ?>);
    
    // Initialize comments system
    console.log('Initializing comments system components...');
    loadComments(1);
    initializeFileUpload();
    initializeMentions();
    
    // Initialize scroll after a short delay to ensure DOM is ready
    setTimeout(() => {
        initializeCommentsScroll();
        console.log('Comments scroll initialized');
    }, 500);
    
    // Submit comment form
    $('#commentForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('order_id', <?= $order['id'] ?? 0 ?>);
        formData.append('comment', $('#commentText').val().trim());
        
        // Add files
        selectedFiles.forEach((file, index) => {
            formData.append('attachments[]', file);
        });

        if (!formData.get('comment')) {
            showError('Please enter a comment');
            return;
        }

        const submitBtn = $('#commentForm button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line me-1 spin"></i>Adding...');

        $.ajax({
            url: '<?= base_url('service_orders/addComment') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#commentForm')[0].reset();
                    selectedFiles = [];
                    $('#filePreviewArea').hide();
                    $('#filePreviewList').empty();
                    
                    // Reload comments (fresh load)
                    loadComments(1, false);
                    
                    // Update comments count (increase by 1)
                    const currentCount = parseInt(document.getElementById('commentsCount').textContent);
                    updateCommentsCount(currentCount + 1);
                    
                    // Refresh recent activities
                    loadRecentActivity(true);
                    
                    showSuccess('Comment added successfully');
                } else {
                    showError(response.message || 'Failed to add comment');
                }
            },
            error: function() {
                showError('Error adding comment');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Load more comments (legacy support)
    $('#loadMoreComments').click(function() {
        loadMoreComments();
    });
});

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
        this.submittingNote = false; // Prevent multiple submissions
        this.loadingNotes = false; // Prevent simultaneous note loading
        this.submitCounter = 0; // Track submit attempts
        this.isDestroyed = false; // Track if instance is destroyed
        this.lastSubmitTime = 0; // Track last submission time
        
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
            const response = await fetch(`<?= base_url('service-order-notes/staff-users') ?>`, {
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
        
        // Note form submission - prevent duplicate listeners
        const noteForm = document.getElementById('noteForm');
        if (noteForm && !noteForm.hasAttribute('data-notes-bound')) {
            // Store the bound function for later removal
            this.handleNoteSubmitBound = (e) => this.handleNoteSubmit(e);
            noteForm.addEventListener('submit', this.handleNoteSubmitBound);
            noteForm.setAttribute('data-notes-bound', 'true');
            console.log('bindEvents: Added submit listener to noteForm');
        } else if (noteForm) {
            console.log('bindEvents: noteForm already has listener, skipping');
        }
        
        // Mention functionality - prevent duplicate listeners
        const noteContent = document.getElementById('noteContent');
        if (noteContent && !noteContent.hasAttribute('data-notes-bound')) {
            noteContent.addEventListener('input', (e) => this.handleMentionTyping(e));
            noteContent.addEventListener('keydown', (e) => this.handleMentionNavigation(e));
            noteContent.setAttribute('data-notes-bound', 'true');
        }
        
        // Filter events - prevent duplicate listeners
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
        
        // File attachment count - prevent duplicate listeners
        const noteAttachments = document.getElementById('noteAttachments');
        if (noteAttachments && !noteAttachments.hasAttribute('data-notes-bound')) {
            noteAttachments.addEventListener('change', (e) => this.updateAttachmentCount(e));
            noteAttachments.setAttribute('data-notes-bound', 'true');
        }
        
        // Tab switching - prevent duplicate listeners
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
        // Remove note form event listener
        const noteForm = document.getElementById('noteForm');
        if (noteForm && this.handleNoteSubmitBound) {
            noteForm.removeEventListener('submit', this.handleNoteSubmitBound);
            noteForm.removeAttribute('data-notes-bound');
            console.log('unbindEvents: Removed submit listener from noteForm');
        }
        
        // Remove other event listeners if they exist
        const noteContent = document.getElementById('noteContent');
        if (noteContent) {
            noteContent.removeAttribute('data-notes-bound');
        }
        
        const notesSearch = document.getElementById('notesSearch');
        if (notesSearch) {
            notesSearch.removeAttribute('data-notes-bound');
        }
        
        const notesAuthorFilter = document.getElementById('notesAuthorFilter');
        if (notesAuthorFilter) {
            notesAuthorFilter.removeAttribute('data-notes-bound');
        }
        
        const notesDateFilter = document.getElementById('notesDateFilter');
        if (notesDateFilter) {
            notesDateFilter.removeAttribute('data-notes-bound');
        }
        
        const noteAttachments = document.getElementById('noteAttachments');
        if (noteAttachments) {
            noteAttachments.removeAttribute('data-notes-bound');
        }
        
        const notesContainer = document.getElementById('notesList');
        if (notesContainer) {
            notesContainer.removeAttribute('data-scroll-bound');
        }
    }
    
    async handleNoteSubmit(e) {
        e.preventDefault();
        e.stopPropagation();
        this.submitCounter++;
        
        console.log('handleNoteSubmit: Called with submitCounter:', this.submitCounter, 'submittingNote:', this.submittingNote);
        
        // Prevent multiple rapid submissions (within 2 seconds)
        const currentTime = Date.now();
        if (this.submittingNote || (currentTime - this.lastSubmitTime < 2000)) {
            console.log('handleNoteSubmit: Already submitting or too soon, skipping. Last submit:', this.lastSubmitTime, 'Current:', currentTime);
            return;
        }
        
        this.lastSubmitTime = currentTime;
        
        // Additional protection: Disable the form immediately
        const form = e.target;
        let submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }
        
        const content = document.getElementById('noteContent').value.trim();
        const attachments = document.getElementById('noteAttachments').files;
        
        if (!content) {
            this.showAlert(internalNotesTranslations.noteContentRequired, 'warning');
            // Re-enable button if validation fails
            if (submitButton) {
                submitButton.disabled = false;
            }
            return;
        }
        
        const formData = new FormData();
        formData.append('order_id', this.orderId);
        formData.append('order_type', 'service_order');
        formData.append('content', content);
        
        // Add attachments
        for (let i = 0; i < attachments.length; i++) {
            formData.append('attachments[]', attachments[i]);
        }
        
        const originalText = submitButton ? submitButton.innerHTML : '';
        
        // Set submitting flag
        this.submittingNote = true;
        
        try {
            if (submitButton) {
                submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i> ' + internalNotesTranslations.addingNote;
                submitButton.disabled = true;
            }
            
            const response = await fetch(`<?= base_url('service-order-notes/create') ?>`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('handleNoteSubmit: Note added successfully, reloading notes');
                // Show detailed success message
                const notePreview = result.data && result.data.content ? 
                    result.data.content.substring(0, 50) + (result.data.content.length > 50 ? '...' : '') : 
                    '';
                const successMessage = `${internalNotesTranslations.noteAddedSuccessfully}${notePreview ? ': "' + notePreview + '"' : ''}`;
                this.showAlert(successMessage, 'success');
                this.clearNoteForm();
                
                // Reload notes only once - remove the delay and just reload immediately
                console.log('handleNoteSubmit: Calling loadNotes after successful submission');
                this.loadNotes(true); // true = reset mode to avoid duplicates
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Send notifications for mentions
                if (result.data && result.data.mentions && result.data.mentions.length > 0) {
                    this.showAlert(internalNotesTranslations.mentionTeamMembers.replace('{0}', result.data.mentions.length), 'info');
                }
            } else {
                throw new Error(result.message || internalNotesTranslations.failedToAddNote);
            }
        } catch (error) {
            console.error('Error adding note:', error);
            this.showAlert(internalNotesTranslations.errorAddingNote + ': ' + error.message, 'error');
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
        const textarea = e.target;
        const value = textarea.value;
        const cursorPos = textarea.selectionStart;
        
        // Find if we're typing after an @ symbol
        const beforeCursor = value.substring(0, cursorPos);
        const match = beforeCursor.match(/@(\w*)$/);
        
        if (match) {
            const query = match[1];
            this.showMentionSuggestions(query, cursorPos - match[0].length);
        } else {
            this.hideMentionSuggestions();
        }
    }
    
    showMentionSuggestions(query, position) {
        const filtered = this.staffUsers.filter(user => 
            user.username.toLowerCase().includes(query.toLowerCase()) ||
            user.name.toLowerCase().includes(query.toLowerCase())
        );
        
        if (filtered.length === 0) {
            this.hideMentionSuggestions();
            return;
        }
        
        const dropdown = document.getElementById('noteMentionSuggestions');
        if (!dropdown) return;
        
        this.mentionSuggestions = filtered;
        this.selectedSuggestionIndex = 0;
        
        const suggestionsList = filtered.map((user, index) => `
            <div class="mention-suggestion-item ${index === 0 ? 'active' : ''}" data-index="${index}">
                <img src="${user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&size=32&background=007bff&color=ffffff'}" 
                     alt="${user.name}" 
                     class="mention-suggestion-avatar">
                <div class="mention-suggestion-info">
                    <p class="mention-suggestion-name">${user.name}</p>
                    <p class="mention-suggestion-username">@${user.username}</p>
                </div>
            </div>
        `).join('');
        
        dropdown.innerHTML = `<div class="mention-suggestions-list">${suggestionsList}</div>`;
        dropdown.style.display = 'block';
        
        // Add click handlers
        dropdown.querySelectorAll('.mention-suggestion-item').forEach((item, index) => {
            item.addEventListener('click', () => this.selectMention(index));
        });
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
        
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                this.selectedSuggestionIndex = Math.min(this.selectedSuggestionIndex + 1, this.mentionSuggestions.length - 1);
                this.updateSuggestionSelection();
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.selectedSuggestionIndex = Math.max(this.selectedSuggestionIndex - 1, 0);
                this.updateSuggestionSelection();
                break;
            case 'Enter':
            case 'Tab':
                e.preventDefault();
                this.selectMention(this.selectedSuggestionIndex);
                break;
            case 'Escape':
                this.hideMentionSuggestions();
                break;
        }
    }
    
    updateSuggestionSelection() {
        const items = document.querySelectorAll('#noteMentionSuggestions .mention-suggestion-item');
        items.forEach((item, index) => {
            item.classList.toggle('active', index === this.selectedSuggestionIndex);
        });
    }
    
    selectMention(index) {
        if (index < 0 || index >= this.mentionSuggestions.length) return;
        
        const user = this.mentionSuggestions[index];
        const textarea = document.getElementById('noteContent');
        const value = textarea.value;
        const cursorPos = textarea.selectionStart;
        
        // Find the @ symbol position
        const beforeCursor = value.substring(0, cursorPos);
        const atIndex = beforeCursor.lastIndexOf('@');
        
        const beforeMention = value.substring(0, atIndex);
        const afterCursor = value.substring(cursorPos);
        
        const newText = beforeMention + '@' + user.username + ' ' + afterCursor;
        const newCursorPos = atIndex + user.username.length + 2;
        
        textarea.value = newText;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
        textarea.focus();
        
        this.hideMentionSuggestions();
    }
    
    updateAttachmentCount(e) {
        const files = e.target.files;
        const countSpan = document.getElementById('noteAttachmentCount');
        
        if (countSpan) {
            const count = files.length;
            if (count > 0) {
                // Create file preview HTML
                const filePreviewsHtml = Array.from(files).map((file, index) => {
                    const extension = file.name.split('.').pop().toLowerCase();
                    const fileIcon = this.getFileIcon(extension);
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'].includes(extension);
                    
                    let thumbnailHtml = '';
                    if (isImage) {
                        const objectUrl = URL.createObjectURL(file);
                        thumbnailHtml = `<img src="${objectUrl}" alt="${file.name}" class="selected-file-thumbnail" data-object-url="${objectUrl}">`;
                        
                        // Store the URL for cleanup later
                        if (!this.objectUrls) this.objectUrls = [];
                        this.objectUrls.push(objectUrl);
                    } else {
                        thumbnailHtml = `<div class="selected-file-icon">${fileIcon}</div>`;
                    }
                    
                    return `
                        <div class="selected-file-item" data-index="${index}">
                            <div class="selected-file-preview">
                                ${thumbnailHtml}
                            </div>
                            <div class="selected-file-info">
                                <span class="selected-file-name" title="${file.name}">${file.name}</span>
                                <span class="selected-file-size">${this.formatFileSize(file.size)}</span>
                            </div>
                            <button type="button" class="selected-file-remove" onclick="internalNotes.removeSelectedFile(${index})" title="Remove file">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                }).join('');
                
                countSpan.innerHTML = `
                    <div class="selected-files-container">
                        <div class="selected-files-header">
                            <span class="selected-files-count">${count} file${count === 1 ? '' : 's'} selected</span>
                        </div>
                        <div class="selected-files-list">
                            ${filePreviewsHtml}
                        </div>
                    </div>
                `;
            } else {
                countSpan.innerHTML = '';
            }
        }
    }
    
    removeSelectedFile(index) {
        const fileInput = document.getElementById('noteAttachments');
        if (!fileInput || !fileInput.files) return;
        
        // Clean up the object URL for the removed file if it exists
        if (this.objectUrls && this.objectUrls[index]) {
            try {
                URL.revokeObjectURL(this.objectUrls[index]);
            } catch (e) {
                console.warn('Failed to revoke object URL:', e);
            }
            this.objectUrls.splice(index, 1);
        }
        
        // Create a new FileList without the removed file
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        fileInput.files = dt.files;
        
        // Update the display
        this.updateAttachmentCount({ target: fileInput });
    }

    clearNoteForm() {
        const noteContent = document.getElementById('noteContent');
        const noteAttachments = document.getElementById('noteAttachments');
        const countSpan = document.getElementById('noteAttachmentCount');
        
        if (noteContent) noteContent.value = '';
        if (noteAttachments) noteAttachments.value = '';
        if (countSpan) countSpan.innerHTML = '';
        
        // Clean up object URLs
        this.cleanupObjectUrls();
    }

    cleanupObjectUrls() {
        if (this.objectUrls && this.objectUrls.length > 0) {
            this.objectUrls.forEach(url => {
                try {
                    URL.revokeObjectURL(url);
                } catch (e) {
                    console.warn('Failed to revoke object URL:', e);
                }
            });
            this.objectUrls = [];
        }
    }
    
    async loadNotes(reset = true) {
        console.log('loadNotes called:', {
            reset: reset,
            currentPage: this.currentPage,
            isDestroyed: this.isDestroyed,
            loadingNotes: this.loadingNotes,
            stackTrace: new Error().stack
        });
        
        // Check if instance is destroyed
        if (this.isDestroyed) {
            console.log('loadNotes: Instance is destroyed, returning');
            return;
        }
        
        // Prevent simultaneous note loading
        if (this.loadingNotes) {
            console.log('loadNotes: Already loading notes, returning');
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
            const dateFilter = document.getElementById('notesDateFilter')?.value || '';
            
            const params = new URLSearchParams({
                search: searchQuery,
                author: authorFilter,
                date_filter: dateFilter,
                page: this.currentPage,
                limit: 5 // Load 5 notes per page
            });
            
            const response = await fetch(`<?= base_url('service-order-notes/order/') ?>${this.orderId}?${params}`, {
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
                this.updateNotesCount(this.totalNotes); // Use total count for badge
                
                // Add load more button if there are more notes
                if (this.hasMore && this.loadedNotes.length > 0) {
                    this.addLoadMoreButton();
                }
            } else {
                throw new Error(result.message || internalNotesTranslations.failedToLoadNotes);
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
            // Clear container for fresh load
            container.innerHTML = '';
        } else {
            // Remove existing load more button for append mode
            this.removeLoadMoreButton();
        }
        
        if (notes.length === 0 && reset) {
            container.innerHTML = `
                <div class="empty-notes">
                    <i data-feather="edit-3"></i>
                    <h6>${internalNotesTranslations.noInternalNotesYet}</h6>
                    <p>${internalNotesTranslations.beFirstAddNote}</p>
                </div>
            `;
        } else if (notes.length > 0) {
            if (reset) {
                // Reset mode: completely replace content
                const noteHtmlArray = notes.map(note => this.createNoteHtml(note));
                container.innerHTML = noteHtmlArray.join('');
            } else {
                // Append mode: add only truly new notes
                const existingIds = new Set();
                container.querySelectorAll('.note-item').forEach(item => {
                    const noteId = parseInt(item.dataset.noteId);
                    if (!isNaN(noteId)) {
                        existingIds.add(noteId);
                    }
                });
                
                // Filter out notes that already exist in the DOM
                const newNotes = notes.filter(note => 
                    note && note.id && !existingIds.has(parseInt(note.id))
                );
                
                // Only add if there are actually new notes
                if (newNotes.length > 0) {
                    const newNoteHtmlArray = newNotes.map(note => this.createNoteHtml(note));
                    container.insertAdjacentHTML('beforeend', newNoteHtmlArray.join(''));
                }
            }
        }
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    createNoteHtml(note) {
        const attachmentsHtml = note.attachments && note.attachments.length > 0 
            ? this.createNoteAttachmentsHtml(note.attachments) 
            : '';
        
        const processedContent = this.processNoteMentions(note.content, note.mentions || []);
        
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
                        <i data-feather="edit-2" class="icon-xs me-1"></i>${internalNotesTranslations.editNote}
                    </button>
                    <button class="note-action-btn delete" onclick="internalNotes.deleteNote(${note.id})">
                        <i data-feather="trash-2" class="icon-xs me-1"></i>${internalNotesTranslations.deleteNote}
                    </button>
                ` : ''}
            </div>
        `;
        
        return `
            <div class="note-item" data-note-id="${note.id}">
                <div class="note-header">
                    <div class="note-author">
                        <img src="${note.avatar_url || note.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(note.author_name) + '&size=32&background=007bff&color=ffffff'}" 
                             alt="${note.author_name}" 
                             class="note-avatar">
                        <div class="note-author-info">
                            <p class="note-author-name">${note.author_name}</p>
                            <p class="note-timestamp" title="${note.created_at_formatted}">
                                ${note.created_at_relative}
                            </p>
                        </div>
                    </div>
                    ${actionButtons}
                </div>
                <div class="note-content" data-original-text="${note.content}">${processedContent}</div>
                ${attachmentsHtml}
                
                <!-- Reply Form -->
                <div class="note-reply-form" id="replyForm_${note.id}" style="display: none;">
                    <div class="d-flex gap-2 mt-3">
                        <img src="${this.currentUser.avatar}" alt="You" class="reply-avatar">
                        <div class="flex-grow-1">
                            <textarea class="form-control" id="replyText_${note.id}" rows="2" placeholder="Write a reply..."></textarea>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-sm btn-primary" onclick="internalNotes.submitReply(${note.id})">
                                    <i data-feather="send" class="icon-xs me-1"></i>Reply
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="internalNotes.cancelReply(${note.id})">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Replies Container -->
                <div class="note-replies" id="replies_${note.id}">
                    ${note.replies ? this.createRepliesHtml(note.replies) : ''}
                </div>
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
    
    processNoteMentions(content, mentions) {
        let processedContent = content;
        
        if (mentions && mentions.length > 0) {
            mentions.forEach(mention => {
                const mentionRegex = new RegExp(`@${mention.username}\\b`, 'gi');
                processedContent = processedContent.replace(mentionRegex, 
                    `<a href="#" class="note-mention" data-user-id="${mention.user_id}">@${mention.username}</a>`
                );
            });
        }
        
        return processedContent;
    }
    
    updateNotesCount(count) {
        const countBadge = document.getElementById('notesCount');
        if (countBadge) {
            countBadge.textContent = count;
        }
    }
    
    addLoadMoreButton() {
        const container = document.getElementById('notesList');
        if (!container || document.getElementById('loadMoreNotesBtn')) {
            return; // Button already exists
        }
        
        const loadMoreBtn = document.createElement('div');
        loadMoreBtn.id = 'loadMoreNotesBtn';
        loadMoreBtn.className = 'text-center py-3';
        loadMoreBtn.innerHTML = `
            <button class="btn btn-outline-primary btn-sm" onclick="internalNotes.loadMoreNotes()">
                <i data-feather="chevron-down" class="icon-xs me-1"></i>
                Load More Notes
            </button>
        `;
        container.appendChild(loadMoreBtn);
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    removeLoadMoreButton() {
        const loadMoreBtn = document.getElementById('loadMoreNotesBtn');
        if (loadMoreBtn) {
            loadMoreBtn.remove();
        }
    }
    
    async loadMoreNotes() {
        if (!this.hasMore || this.loadingNotes) {
            return;
        }
        
        // Show loading indicator
        this.showLoadingIndicator();
        
        this.currentPage++;
        await this.loadNotes(false); // false = append mode
        
        // Hide loading indicator
        this.hideLoadingIndicator();
    }
    
    handleNotesScroll(e) {
        const container = e.target;
        const scrollTop = container.scrollTop;
        const scrollHeight = container.scrollHeight;
        const clientHeight = container.clientHeight;
        
        // Load more when user scrolls to within 100px of the bottom
        if (scrollTop + clientHeight >= scrollHeight - 100) {
            this.loadMoreNotes();
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
    
    displayNotesError() {
        const container = document.getElementById('notesList');
        if (!container) return;
        
        container.innerHTML = `
            <div class="empty-notes">
                <i data-feather="alert-circle"></i>
                <h6>${internalNotesTranslations.errorLoadingNotes}</h6>
                <p>${internalNotesTranslations.errorLoadingNotesTryAgain}</p>
                <button class="btn btn-sm btn-outline-primary" onclick="internalNotes.loadNotes()">
                    ${internalNotesTranslations.tryAgain}
                </button>
            </div>
        `;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    filterNotes() {
        this.loadNotes();
    }
    
    handleTabSwitch(e) {
        const targetTab = e.target.getAttribute('data-bs-target');
        
        switch (targetTab) {
            case '#mentions-pane':
                this.loadMentions();
                break;
            case '#team-pane':
                this.loadTeamActivity();
                break;
        }
    }
    
    async loadMentions() {
        try {
            const response = await fetch(`<?= base_url('service-order-notes/unread-mentions') ?>`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.displayMentions(result.data);
                this.updateMentionsCount(result.data.length);
            }
        } catch (error) {
            console.error('Error loading mentions:', error);
        }
    }
    
    displayMentions(mentions) {
        const container = document.getElementById('mentionsList');
        if (!container) return;
        
        if (mentions.length === 0) {
            container.innerHTML = `
                <div class="empty-notes">
                    <i data-feather="at-sign"></i>
                    <h6>No mentions yet</h6>
                    <p>You'll see mentions when other staff members mention you in notes.</p>
                </div>
            `;
        } else {
            container.innerHTML = mentions.map(mention => `
                <div class="mention-alert">
                    <div class="mention-alert-content">
                        <h6 class="mention-alert-title">Mentioned by ${mention.author_name}</h6>
                        <p class="mention-alert-text">${mention.note_preview}</p>
                    </div>
                    <button class="mention-alert-action" onclick="internalNotes.markMentionRead(${mention.note_id})">
                        Mark as Read
                    </button>
                </div>
            `).join('');
        }
    }
    
    updateMentionsCount(count) {
        const countBadge = document.getElementById('mentionsCount');
        if (countBadge) {
            countBadge.textContent = count;
        }
    }
    
    async markMentionRead(noteId) {
        try {
            const response = await fetch(`<?= base_url('service-order-notes/mark-mention-read/') ?>${noteId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.loadMentions(); // Refresh mentions
            }
        } catch (error) {
            console.error('Error marking mention as read:', error);
        }
    }
    
    loadTeamActivity() {
        const container = document.getElementById('teamActivityList');
        if (!container) return;
        
        // For now, just show a placeholder
        container.innerHTML = `
            <div class="empty-notes">
                <i data-feather="users"></i>
                <h6>Team Activity</h6>
                <p>Recent team activity will appear here.</p>
            </div>
        `;
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    formatFileSize(bytes) {
        if (!bytes) return '0 B';
        
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    getFileIcon(extension) {
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
            
            // Code files
            'html': '<i class="fas fa-code text-warning"></i>',
            'htm': '<i class="fas fa-code text-warning"></i>',
            'css': '<i class="fas fa-code text-info"></i>',
            'js': '<i class="fas fa-code text-warning"></i>',
            'json': '<i class="fas fa-code text-info"></i>',
            'xml': '<i class="fas fa-code text-secondary"></i>',
            'php': '<i class="fas fa-code text-purple"></i>',
            
            // Archives
            'zip': '<i class="fas fa-file-archive text-warning"></i>',
            'rar': '<i class="fas fa-file-archive text-warning"></i>',
            '7z': '<i class="fas fa-file-archive text-warning"></i>',
            'tar': '<i class="fas fa-file-archive text-warning"></i>',
            'gz': '<i class="fas fa-file-archive text-warning"></i>',
            
            // Audio
            'mp3': '<i class="fas fa-file-audio text-purple"></i>',
            'wav': '<i class="fas fa-file-audio text-purple"></i>',
            'flac': '<i class="fas fa-file-audio text-purple"></i>',
            'aac': '<i class="fas fa-file-audio text-purple"></i>',
            
            // Video
            'mp4': '<i class="fas fa-file-video text-danger"></i>',
            'avi': '<i class="fas fa-file-video text-danger"></i>',
            'mov': '<i class="fas fa-file-video text-danger"></i>',
            'wmv': '<i class="fas fa-file-video text-danger"></i>',
            'flv': '<i class="fas fa-file-video text-danger"></i>'
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
    
    async deleteNote(noteId) {
        const confirmed = await new Promise((resolve) => {
            Swal.fire({
                title: internalNotesTranslations.deleteNoteConfirmation,
                text: internalNotesTranslations.deleteNoteText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: internalNotesTranslations.yesDeleteNote,
                cancelButtonText: internalNotesTranslations.cancelDelete
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });

        if (!confirmed) return;
        
        try {
            const response = await fetch(`<?= base_url('service-order-notes/delete/') ?>${noteId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            // Check if response is OK and contains JSON
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('Note deletion endpoint not found (404)');
                } else if (response.status === 401 || response.status === 403) {
                    throw new Error('Authentication required (401/403)');
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                // Response is not JSON - likely a redirect to login page
                throw new Error('Authentication required - redirected to login page');
            }
            
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
                        
                        // Update notes count after removing the note from DOM
                        const remainingNotes = document.querySelectorAll('.note-item').length;
                        self.updateNotesCount(remainingNotes);
                    }, 300);
                } else {
                    // If note item not found, still update the count
                    const remainingNotes = document.querySelectorAll('.note-item').length;
                    this.updateNotesCount(remainingNotes);
                }
                
                this.showAlert(internalNotesTranslations.noteDeletedSuccessfully, 'success');
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
            } else {
                throw new Error(result.message || internalNotesTranslations.failedToDeleteNote);
            }
        } catch (error) {
            console.error('Error deleting note:', error);
            
            // Check if it's an authentication error
            if (error.message.includes('401') || error.message.includes('Unauthorized')) {
                this.showAlert('Session expired. Please login again.', 'error');
                setTimeout(() => {
                    window.location.href = '<?= base_url('login') ?>';
                }, 2000);
            } else if (error.message.includes('404')) {
                this.showAlert('Note deletion endpoint not found. Please contact support.', 'error');
            } else {
                this.showAlert(internalNotesTranslations.errorDeletingNote + ': ' + error.message, 'error');
            }
        }
    }
    

    
    editNote(noteId) {
        const noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
        const contentDiv = noteItem.querySelector('.note-content');
        const originalText = contentDiv.getAttribute('data-original-text');
        
        // Create edit form
        const editForm = document.createElement('div');
        editForm.className = 'note-edit-form';
        editForm.innerHTML = `
            <textarea class="form-control mb-2" id="editNoteText_${noteId}" rows="3">${originalText}</textarea>
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
        noteItem.querySelector('.note-actions').style.display = 'none';
        
        // Focus on textarea
        document.getElementById(`editNoteText_${noteId}`).focus();
        
        // Re-initialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    saveNote(noteId) {
        const newText = document.getElementById(`editNoteText_${noteId}`).value.trim();
        
        if (!newText) {
            this.showAlert('Note content cannot be empty', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('content', newText);

        fetch(`<?= base_url('service-order-notes/update/') ?>${noteId}`, {
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
                contentDiv.innerHTML = this.processNoteMentions(newText, []);
                
                // Remove edit form and show original content
                noteItem.querySelector('.note-edit-form').remove();
                contentDiv.style.display = 'block';
                noteItem.querySelector('.note-actions').style.display = 'flex';
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Show detailed success message
                const notePreview = newText.substring(0, 50) + (newText.length > 50 ? '...' : '');
                this.showAlert(`${internalNotesTranslations.noteUpdatedSuccessfully}: "${notePreview}"`, 'success');
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
        const noteItem = document.querySelector(`.note-item[data-note-id="${noteId}"]`);
        
        // Remove edit form and show original content
        const editForm = noteItem.querySelector('.note-edit-form');
        if (editForm) {
            editForm.remove();
        }
        noteItem.querySelector('.note-content').style.display = 'block';
        noteItem.querySelector('.note-actions').style.display = 'flex';
    }
    
    toggleReplyForm(noteId) {
        const replyForm = document.getElementById(`replyForm_${noteId}`);
        if (!replyForm) {
            console.error(`Reply form not found for note ${noteId}`);
            return;
        }
        
        const isVisible = replyForm.style.display !== 'none' && replyForm.style.display !== '';
        
        // Hide all other reply forms
        document.querySelectorAll('.note-reply-form').forEach(form => {
            if (form.id !== `replyForm_${noteId}`) {
                form.style.display = 'none';
            }
        });
        
        if (!isVisible) {
            replyForm.style.display = 'block';
            
            // Focus on textarea with delay to ensure DOM is ready
            setTimeout(() => {
                const replyTextArea = document.getElementById(`replyText_${noteId}`);
                if (replyTextArea) {
                    replyTextArea.focus();
                    console.log('Reply form focused for note:', noteId);
                } else {
                    console.error('Reply textarea not found for note:', noteId);
                }
            }, 150);
        } else {
            replyForm.style.display = 'none';
        }
    }
    
    cancelReply(noteId) {
        const replyForm = document.getElementById(`replyForm_${noteId}`);
        const replyText = document.getElementById(`replyText_${noteId}`);
        
        if (replyForm) {
            replyForm.style.display = 'none';
        }
        if (replyText) {
            replyText.value = '';
        }
    }
    
    submitReply(noteId) {
        const replyTextElement = document.getElementById(`replyText_${noteId}`);
        if (!replyTextElement) {
            console.error(`Reply text element not found for note ${noteId}`);
            return;
        }
        
        const replyText = replyTextElement.value.trim();
        
        if (!replyText) {
            this.showAlert('Please enter a reply', 'warning');
            replyTextElement.focus();
            return;
        }

        // Get submit button and show loading state
        const submitButton = document.querySelector(`#replyForm_${noteId} button[onclick="internalNotes.submitReply(${noteId})"]`);
        const originalButtonText = submitButton ? submitButton.innerHTML : '';
        
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i data-feather="loader" class="icon-xs me-1 rotating"></i>Adding...';
            
            // Re-initialize feather icons for loading spinner
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        const formData = new FormData();
        formData.append('parent_note_id', noteId);
        formData.append('order_id', this.orderId);
        formData.append('content', replyText);

        fetch('<?= base_url('service-order-notes/add-reply') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new reply to the UI
                const repliesContainer = document.getElementById(`replies_${noteId}`);
                const newReplyHtml = this.createRepliesHtml([data.reply]);
                repliesContainer.insertAdjacentHTML('beforeend', newReplyHtml);
                
                // Hide and reset the reply form
                this.cancelReply(noteId);
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Re-initialize feather icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                
                // Show detailed success message
                const replyPreview = replyText.substring(0, 50) + (replyText.length > 50 ? '...' : '');
                this.showAlert(`${internalNotesTranslations.replyAddedSuccessfully}: "${replyPreview}"`, 'success');
            } else {
                this.showAlert(data.message || 'Failed to add reply', 'error');
                
                // Restore button state on error
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                    
                    // Re-initialize feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error adding reply:', error);
            this.showAlert('Error adding reply', 'error');
            
            // Restore button state on error
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                
                // Re-initialize feather icons
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        });
    }
    
    createRepliesHtml(replies) {
        if (!replies || replies.length === 0) return '';
        
        const currentUserId = <?= auth()->id() ?? 0 ?>;
        
        return replies.map(reply => {
            const canEdit = currentUserId && (currentUserId == reply.author_id);
            
            return `
                <div class="note-reply" data-reply-id="${reply.id}">
                    <div class="d-flex gap-2 mt-2">
                        <img src="${reply.avatar_url || reply.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(reply.author_name) + '&size=24&background=007bff&color=ffffff'}" 
                             alt="${reply.author_name}" 
                             class="reply-avatar">
                        <div class="flex-grow-1">
                            <div class="reply-header d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${reply.author_name}</strong>
                                    <span class="text-muted ms-2">${reply.created_at_relative}</span>
                                </div>
                                ${canEdit ? `
                                    <div class="reply-actions" style="opacity: 0.7;">
                                        <button class="note-action-btn edit" onclick="internalNotes.editReply(${reply.id})" title="Edit Reply">
                                            <i data-feather="edit-2" class="icon-xs"></i>
                                        </button>
                                        <button class="note-action-btn delete" onclick="internalNotes.deleteReply(${reply.id})" title="Delete Reply">
                                            <i data-feather="trash-2" class="icon-xs"></i>
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                            <div class="reply-content" data-original-text="${this.escapeHtml(reply.content || '')}">
                                <p class="mb-1 mt-1">${this.processNoteMentions(reply.content, reply.mentions || [])}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    
    escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    
    showAlert(message, type) {
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
    
    editReply(replyId) {
        const replyItem = document.querySelector(`.note-reply[data-reply-id="${replyId}"]`);
        if (!replyItem) {
            console.error('Reply item not found for ID:', replyId);
            return;
        }
        
        const contentDiv = replyItem.querySelector('.reply-content');
        const originalText = contentDiv.getAttribute('data-original-text') || contentDiv.textContent.trim();
        
        // Create edit form
        const editForm = document.createElement('div');
        editForm.className = 'reply-edit-form mt-2';
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
    
    saveReply(replyId) {
        const newText = document.getElementById(`editReplyText_${replyId}`).value.trim();
        
        if (!newText) {
            this.showAlert('Reply content cannot be empty', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('content', newText);

        fetch(`<?= base_url('service-order-notes/update') ?>/${replyId}`, {
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
                const replyItem = document.querySelector(`.note-reply[data-reply-id="${replyId}"]`);
                const contentDiv = replyItem.querySelector('.reply-content');
                
                // Update content
                contentDiv.setAttribute('data-original-text', newText);
                contentDiv.innerHTML = `<p class="mb-1 mt-1">${this.processNoteMentions(newText, [])}</p>`;
                
                // Remove edit form and show original content
                replyItem.querySelector('.reply-edit-form').remove();
                contentDiv.style.display = 'block';
                const actions = replyItem.querySelector('.reply-actions');
                if (actions) actions.style.display = 'block';
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                // Show detailed success message
                const replyPreview = newText.substring(0, 50) + (newText.length > 50 ? '...' : '');
                this.showAlert(`${internalNotesTranslations.replyUpdatedSuccessfully}: "${replyPreview}"`, 'success');
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
        const replyItem = document.querySelector(`.note-reply[data-reply-id="${replyId}"]`);
        if (!replyItem) return;
        
        const editForm = replyItem.querySelector('.reply-edit-form');
        const contentDiv = replyItem.querySelector('.reply-content');
        const actions = replyItem.querySelector('.reply-actions');
        
        // Remove edit form and show original content
        if (editForm) editForm.remove();
        if (contentDiv) contentDiv.style.display = 'block';
        if (actions) actions.style.display = 'block';
    }
    
    deleteReply(replyId) {
        Swal.fire({
            title: internalNotesTranslations.deleteNoteConfirmation,
            text: 'Are you sure you want to delete this reply?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: internalNotesTranslations.yesDeleteNote,
            cancelButtonText: internalNotesTranslations.cancelDelete
        }).then((result) => {
            if (result.isConfirmed) {
                this.performReplyDelete(replyId);
            }
        });
    }
    
    performReplyDelete(replyId) {
        fetch(`<?= base_url('service-order-notes/delete') ?>/${replyId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the reply from the UI
                const replyItem = document.querySelector(`.note-reply[data-reply-id="${replyId}"]`);
                if (replyItem) {
                    replyItem.remove();
                }
                
                // Refresh recent activities
                if (typeof loadRecentActivity === 'function') {
                    loadRecentActivity(true);
                }
                
                this.showAlert(internalNotesTranslations.replyDeletedSuccessfully, 'success');
            } else {
                this.showAlert(data.message || 'Failed to delete reply', 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting reply:', error);
            this.showAlert('Error deleting reply', 'error');
        });
    }
    
    destroy() {
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

// Initialize Internal Notes System for staff users only
let internalNotes;
let internalNotesInitialized = false;

// SINGLE DOMContentLoaded listener - consolidates all initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded: Starting service order view initialization');
    
    // 1. Load recent activity (first page)
    if (typeof loadRecentActivity === 'function') {
        loadRecentActivity(true);
    }
    
    // 2. Initialize modal event listeners
    if (typeof initializeModalEventListeners === 'function') {
        initializeModalEventListeners();
    }
    
    // 3. Initialize Internal Notes System for staff and admin users only
    <?php if (auth()->user() && (auth()->user()->user_type === 'staff' || auth()->user()->user_type === 'admin')): ?>
    if (!internalNotesInitialized && !internalNotes) {
        console.log('DOMContentLoaded: Initializing Internal Notes System');
        internalNotesInitialized = true;
        internalNotes = new InternalNotesSystem(<?= $order['id'] ?? 0 ?>);
    }
    <?php endif; ?>
    
    // 4. Initialize followers system
    if (typeof loadFollowers === 'function') {
        loadFollowers();
    }
    
    console.log('DOMContentLoaded: Service order view initialization complete');
});

// ==================== FOLLOWERS FUNCTIONALITY ====================

let followersData = [];
let availableUsers = [];

/**
 * Load followers for the current order
 */
function loadFollowers() {
    const orderId = orderData.id;
    
    fetch(`<?= base_url('service_orders/getFollowers') ?>/${orderId}`, {
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
            <div class="follower-item d-flex align-items-center justify-content-between py-2 border-bottom">
                <div class="d-flex align-items-center">
                    <img src="${avatar}" alt="${follower.full_name}" class="rounded-circle me-3" width="40" height="40">
                    <div>
                        <h6 class="mb-0">${follower.full_name}</h6>
                        <small class="text-muted">
                            <span class="badge badge-sm ${follower.follower_type === 'staff' ? 'bg-info' : 'bg-success'}">${follower.follower_type === 'staff' ? 'Staff' : 'Client Contact'}</span>
                            ${follower.email ? `• ${follower.email}` : ''}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-primary follower-settings-btn" 
                            data-user-id="${follower.user_id}" 
                            data-user-name="${escapeHtml(follower.full_name)}" 
                            data-preferences='${escapeHtml(preferencesJson)}'>
                        <i data-feather="settings" class="icon-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger follower-remove-btn" 
                            data-user-id="${follower.user_id}" 
                            data-user-name="${escapeHtml(follower.full_name)}">
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
    
    fetch(`<?= base_url('service_orders/getAvailableFollowers') ?>/${orderId}`, {
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
            availableUsers = data.available_users;
            populateUserSelect(data.available_users);
        } else {
            console.error('Failed to load available users:', data.message);
            userSelect.innerHTML = `<option value="">Error: ${data.message}</option>`;
        }
    })
    .catch(error => {
        console.error('Error loading available users:', error);
        userSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
    });
}

/**
 * Populate user select dropdown
 */
function populateUserSelect(users) {
    const userSelect = document.getElementById('followerUserId');
    let html = '<option value="">Select a user...</option>';
    
    if (users.client_contacts && users.client_contacts.length > 0) {
        html += '<optgroup label="Client Contacts">';
        users.client_contacts.forEach(user => {
            html += `<option value="${user.id}" data-type="client_contact">${user.full_name} (${user.email})</option>`;
        });
        html += '</optgroup>';
    }
    
    if (users.staff_users && users.staff_users.length > 0) {
        html += '<optgroup label="Staff Members">';
        users.staff_users.forEach(user => {
            html += `<option value="${user.id}" data-type="staff">${user.full_name} (${user.email})</option>`;
        });
        html += '</optgroup>';
    }
    
    if ((!users.client_contacts || users.client_contacts.length === 0) && 
        (!users.staff_users || users.staff_users.length === 0)) {
        html = '<option value="">No users available to add</option>';
    }
    
    userSelect.innerHTML = html;
    
    // Auto-select follower type based on selection
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
    
    fetch('<?= base_url('service_orders/addFollower') ?>', {
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
            
            fetch('<?= base_url('service_orders/removeFollower') ?>', {
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
    let preferences = {};
    
    // Safely parse the preferences JSON
    try {
        if (preferencesJson && preferencesJson.trim() !== '') {
            preferences = JSON.parse(preferencesJson);
        }
    } catch (error) {
        console.warn('Failed to parse follower preferences JSON:', preferencesJson, error);
        preferences = {}; // Use empty object as fallback
    }
    
    // Populate modal
    document.getElementById('preferencesUserId').value = userId;
    document.getElementById('preferencesUserName').value = userName;
    
    // Set checkboxes with safe defaults
    document.getElementById('prefStatusChanges').checked = preferences.status_changes !== false;
    document.getElementById('prefComments').checked = preferences.new_comments !== false;
    document.getElementById('prefMentions').checked = preferences.mentions !== false;
    document.getElementById('prefEmailNotifications').checked = preferences.email_notifications !== false;
    document.getElementById('prefSmsNotifications').checked = preferences.sms_notifications === true;
    document.getElementById('prefPushNotifications').checked = preferences.push_notifications !== false;
    
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
    
    fetch('<?= base_url('service_orders/updateFollowerPreferences') ?>', {
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
function escapeHtml(text) {
    if (typeof text !== 'string') return text;
    
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
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

</script>

<!-- SMS Modal -->
<div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smsModalLabel">
                    <i data-feather="message-square" class="icon-sm me-2"></i>
                    <?= lang('App.send_sms_to_contact') ?>
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
                        <label for="smsPhone" class="form-label"><?= lang('App.phone_number') ?></label>
                        <input type="text" class="form-control" id="smsPhone" value="<?= $order['salesperson_phone'] ?? '' ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="smsMessage" class="form-label"><?= lang('App.message') ?></label>
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
                            <small class="text-info"><?= lang('App.order_url_auto_added_sms') ?></small>
                        </div>
                        <div id="smsLengthAlert" class="alert alert-danger py-2 mt-2" style="display: none;">
                            <i data-feather="alert-circle" class="icon-xs me-1"></i>
                            <small><strong>Message exceeds SMS limit!</strong> Please shorten your message to send via SMS.</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="smsTemplate" class="form-label"><?= lang('App.quick_templates') ?></label>
                        <select class="form-select" id="smsTemplate" onchange="applySmsTemplate()">
                            <option value=""><?= lang('App.select_template') ?></option>
                            <!-- Templates will be loaded dynamically -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('App.cancel') ?></button>
                    <button type="submit" class="btn btn-success" id="smsSendButton">
                        <i data-feather="send" class="icon-sm me-1"></i>
                        Send SMS
                    </button>
                </div>
            </form>
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
                                <label for="emailTo" class="form-label"><?= lang('App.to') ?></label>
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
                        <label for="emailSubject" class="form-label"><?= lang('App.subject') ?></label>
                        <input type="text" class="form-control" id="emailSubject" placeholder="Enter subject..." required>
                    </div>
                    <div class="mb-3">
                        <label for="emailTemplate" class="form-label"><?= lang('App.email_templates') ?></label>
                        <select class="form-select" id="emailTemplate" onchange="applyEmailTemplate()">
                            <option value=""><?= lang('App.select_template') ?></option>
                            <!-- Templates will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label"><?= lang('App.message') ?></label>
                        <textarea class="form-control" id="emailMessage" rows="6" placeholder="Enter your email message..." required></textarea>
                        <div class="form-text">
                            <i data-feather="info" class="icon-xs me-1"></i>
                            <small class="text-info"><?= lang('App.order_url_auto_added_email') ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="emailIncludeOrderDetails">
                            <label class="form-check-label" for="emailIncludeOrderDetails">
                                <?= lang('App.include_order_details') ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('App.cancel') ?></button>
                    <button type="submit" class="btn btn-warning">
                        <i data-feather="send" class="icon-sm me-1"></i>
                        Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Order Modal -->
<div class="modal fade" id="editServiceOrderModal" tabindex="-1" aria-labelledby="editServiceOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceOrderModalLabel">
                    <i data-feather="edit" class="icon-sm me-2"></i>
                    Edit Service Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- El contenido del modal se cargará aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i data-feather="x" class="icon-sm me-1"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" form="serviceOrderForm">
                    <i data-feather="save" class="icon-sm me-1"></i>
                    Update Service Order
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?> 
