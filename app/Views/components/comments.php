<?php
/**
 * Comments Component
 * Reutilizable para todos los módulos (Sales Orders, Service Orders, CarWash, Recon)
 * 
 * Parámetros requeridos:
 * - $order_id: ID de la orden
 * - $module_type: Tipo de módulo ('sales_orders', 'service_orders', 'car_wash_orders', 'recon_orders')
 * 
 * Parámetros opcionales:
 * - $max_height: Altura máxima de la lista de comentarios (default: 400px)
 * - $allow_attachments: Permitir archivos adjuntos (default: true)
 * - $allow_mentions: Permitir menciones @username (default: true)
 * - $show_refresh_button: Mostrar botón refresh (default: true)
 * - $accepted_file_types: Tipos de archivo aceptados (default: predefinido)
 * - $placeholder_text: Texto placeholder personalizado (opcional)
 */

$order_id = $order_id ?? 0;
$module_type = $module_type ?? 'sales_orders';
$max_height = $max_height ?? 400;
$allow_attachments = $allow_attachments ?? true;
$allow_mentions = $allow_mentions ?? true;
$show_refresh_button = $show_refresh_button ?? true;
$accepted_file_types = $accepted_file_types ?? '.pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4,.mov,.txt';
$placeholder_text = $placeholder_text ?? 'Add a comment...';

if ($allow_mentions) {
    $placeholder_text .= ' Use @username to mention staff members';
}

// Generar IDs únicos para el componente
$component_id = 'comments_' . $module_type . '_' . $order_id;
$form_id = $component_id . '_form';
$textarea_id = $component_id . '_textarea';
$list_id = $component_id . '_list';
$count_id = $component_id . '_count';
$attachments_id = $component_id . '_attachments';
$mentions_id = $component_id . '_mentions';
?>

<!-- Comments Component -->
<div class="card order-comments" id="<?= $component_id ?>">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold">
            <i data-feather="message-circle" class="icon-sm me-2"></i>
            Comments & Attachments
            <span id="<?= $count_id ?>" class="badge bg-primary ms-1">0</span>
        </h5>
        <?php if ($show_refresh_button): ?>
        <button class="btn btn-sm btn-outline-primary" onclick="loadComments('<?= $component_id ?>', <?= $order_id ?>, '<?= $module_type ?>', true)">
            <i data-feather="refresh-cw" class="icon-xs me-1"></i>
            Refresh
        </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <!-- Enhanced Add Comment Form -->
        <form id="<?= $form_id ?>" class="mb-3" enctype="multipart/form-data">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="module_type" value="<?= $module_type ?>">
            
            <div class="mb-2">
                <div class="position-relative">
                    <textarea class="form-control" id="<?= $textarea_id ?>" name="comment_text" 
                             rows="3" placeholder="<?= $placeholder_text ?>" required></textarea>
                    <?php if ($allow_mentions): ?>
                    <div id="<?= $mentions_id ?>" class="mention-suggestions-dropdown" style="display: none;"></div>
                    <?php endif; ?>
                </div>
                <div class="form-text">
                    <i data-feather="info" class="icon-xs me-1"></i>
                    <?php if ($allow_mentions && $allow_attachments): ?>
                    Type @ followed by username to mention staff members. Supports images, videos, and documents.
                    <?php elseif ($allow_mentions): ?>
                    Type @ followed by username to mention staff members.
                    <?php elseif ($allow_attachments): ?>
                    Supports file attachments including images, videos, and documents.
                    <?php else: ?>
                    Share your comments and feedback.
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <?php if ($allow_attachments): ?>
                    <input type="file" id="<?= $attachments_id ?>" name="attachments[]" 
                           multiple class="form-control form-control-sm d-none" 
                           accept="<?= $accepted_file_types ?>">
                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                            onclick="document.getElementById('<?= $attachments_id ?>').click()">
                        <i data-feather="paperclip" class="icon-xs me-1"></i>
                        Attach Files
                    </button>
                    <span id="<?= $component_id ?>_attachment_count" class="text-muted small ms-2"></span>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i data-feather="send" class="icon-xs me-1"></i>
                    Add Comment
                </button>
            </div>
        </form>
        
        <!-- Comments List -->
        <div id="<?= $list_id ?>" style="max-height: <?= $max_height ?>px !important; overflow-y: auto !important; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px;">
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Loading comments...</p>
            </div>
        </div>
    </div>
</div>

<!-- Comments Component JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const componentId = '<?= $component_id ?>';
    const orderId = <?= $order_id ?>;
    const moduleType = '<?= $module_type ?>';
    const allowMentions = <?= $allow_mentions ? 'true' : 'false' ?>;
    const allowAttachments = <?= $allow_attachments ? 'true' : 'false' ?>;
    
    // Initialize Comments Component
    initCommentsComponent(componentId, orderId, moduleType, allowMentions, allowAttachments);
});

function initCommentsComponent(componentId, orderId, moduleType, allowMentions, allowAttachments) {
    const form = document.getElementById(componentId + '_form');
    const textarea = document.getElementById(componentId + '_textarea');
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitComment(componentId, orderId, moduleType);
    });
    
    // Handle attachment selection
    if (allowAttachments) {
        const attachmentInput = document.getElementById(componentId + '_attachments');
        const attachmentCount = document.getElementById(componentId + '_attachment_count');
        
        attachmentInput.addEventListener('change', function() {
            const fileCount = this.files.length;
            if (fileCount > 0) {
                const files = Array.from(this.files);
                const fileNames = files.map(f => f.name).join(', ');
                attachmentCount.textContent = `${fileCount} file(s): ${fileNames.substring(0, 50)}${fileNames.length > 50 ? '...' : ''}`;
            } else {
                attachmentCount.textContent = '';
            }
        });
    }
    
    // Handle mentions
    if (allowMentions) {
        let mentionUsers = [];
        
        textarea.addEventListener('input', function(e) {
            handleMentions(e, componentId, mentionUsers);
        });
        
        // Load staff users for mentions
        loadMentionUsers(componentId).then(users => {
            mentionUsers = users;
        });
    }
    
    // Load initial comments
    loadComments(componentId, orderId, moduleType, false);
}

// Submit comment
function submitComment(componentId, orderId, moduleType) {
    const form = document.getElementById(componentId + '_form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';
    
    // Prepare form data
    const formData = new FormData(form);
    
    // Submit comment
    fetch(`/api/comments/${moduleType}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Comment added successfully!', 'success');
            
            // Reset form
            form.reset();
            document.getElementById(componentId + '_attachment_count').textContent = '';
            
            // Reload comments
            loadComments(componentId, orderId, moduleType, false);
        } else {
            showToast(data.message || 'Failed to add comment', 'error');
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
        showToast('Error submitting comment', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Load comments
function loadComments(componentId, orderId, moduleType, forceRefresh = false) {
    const listContainer = document.getElementById(componentId + '_list');
    const countBadge = document.getElementById(componentId + '_count');
    
    if (forceRefresh) {
        listContainer.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0">Refreshing comments...</p>
            </div>
        `;
    }
    
    fetch(`/api/comments/${moduleType}/${orderId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderComments(listContainer, data.comments);
            countBadge.textContent = data.comments.length;
        } else {
            listContainer.innerHTML = `
                <div class="text-center py-3 text-muted">
                    <i data-feather="message-circle" class="icon-sm mb-2"></i>
                    <p class="mb-0">No comments yet</p>
                    <small>Be the first to add a comment!</small>
                </div>
            `;
            countBadge.textContent = '0';
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
        listContainer.innerHTML = `
            <div class="text-center py-3 text-danger">
                <i data-feather="alert-circle" class="icon-sm mb-2"></i>
                <p class="mb-0">Error loading comments</p>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="loadComments('${componentId}', ${orderId}, '${moduleType}', true)">
                    Try Again
                </button>
            </div>
        `;
        countBadge.textContent = '?';
    });
}

// Render comments
function renderComments(container, comments) {
    if (comments.length === 0) {
        container.innerHTML = `
            <div class="text-center py-3 text-muted">
                <i data-feather="message-circle" class="icon-sm mb-2"></i>
                <p class="mb-0">No comments yet</p>
                <small>Be the first to add a comment!</small>
            </div>
        `;
        return;
    }
    
    let html = '';
    comments.forEach(comment => {
        html += renderCommentHTML(comment);
    });
    
    container.innerHTML = html;
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
}

// Render individual comment HTML
function renderCommentHTML(comment) {
    const createdAt = new Date(comment.created_at).toLocaleString();
    const userInitials = (comment.user_name || 'U').substring(0, 2).toUpperCase();
    
    let attachmentsHTML = '';
    if (comment.attachments && comment.attachments.length > 0) {
        attachmentsHTML = '<div class="comment-attachments mt-2">';
        comment.attachments.forEach(attachment => {
            attachmentsHTML += `
                <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-secondary me-1 mb-1">
                    <i data-feather="paperclip" class="icon-xs me-1"></i>
                    ${attachment.name}
                </a>
            `;
        });
        attachmentsHTML += '</div>';
    }
    
    return `
        <div class="comment-item" data-comment-id="${comment.id}">
            <div class="comment-header">
                <div class="comment-user-info">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2">
                            <div class="avatar-title bg-primary text-white rounded-circle">
                                ${userInitials}
                            </div>
                        </div>
                        <div>
                            <div class="fw-medium">${comment.user_name || 'Unknown User'}</div>
                            <small class="text-muted">${createdAt}</small>
                        </div>
                    </div>
                </div>
                <div class="comment-actions">
                    <button class="btn btn-sm btn-outline-secondary" onclick="editComment(${comment.id})" title="Edit">
                        <i data-feather="edit-3" class="icon-xs"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})" title="Delete">
                        <i data-feather="trash-2" class="icon-xs"></i>
                    </button>
                </div>
            </div>
            <div class="comment-content mt-2">
                <p class="mb-0">${comment.comment}</p>
                ${attachmentsHTML}
            </div>
        </div>
    `;
}

// Handle mentions
function handleMentions(e, componentId, mentionUsers) {
    const textarea = e.target;
    const text = textarea.value;
    const cursorPos = textarea.selectionStart;
    
    // Find @ mentions
    const beforeCursor = text.substring(0, cursorPos);
    const mentionMatch = beforeCursor.match(/@(\w*)$/);
    
    if (mentionMatch) {
        const query = mentionMatch[1].toLowerCase();
        const suggestions = mentionUsers.filter(user => 
            user.username.toLowerCase().includes(query) ||
            user.name.toLowerCase().includes(query)
        );
        
        showMentionSuggestions(componentId, suggestions, mentionMatch.index);
    } else {
        hideMentionSuggestions(componentId);
    }
}

// Show mention suggestions
function showMentionSuggestions(componentId, suggestions, position) {
    const dropdown = document.getElementById(componentId + '_mentions');
    if (!dropdown) return;
    
    if (suggestions.length === 0) {
        hideMentionSuggestions(componentId);
        return;
    }
    
    let html = '';
    suggestions.forEach(user => {
        html += `
            <div class="mention-suggestion" onclick="selectMention('${componentId}', '${user.username}', '${user.name}')">
                <strong>@${user.username}</strong>
                <small class="text-muted ms-1">${user.name}</small>
            </div>
        `;
    });
    
    dropdown.innerHTML = html;
    dropdown.style.display = 'block';
}

// Hide mention suggestions
function hideMentionSuggestions(componentId) {
    const dropdown = document.getElementById(componentId + '_mentions');
    if (dropdown) {
        dropdown.style.display = 'none';
    }
}

// Select mention
function selectMention(componentId, username, name) {
    const textarea = document.getElementById(componentId + '_textarea');
    const text = textarea.value;
    const cursorPos = textarea.selectionStart;
    
    // Replace the partial mention with the selected one
    const beforeCursor = text.substring(0, cursorPos);
    const afterCursor = text.substring(cursorPos);
    const mentionMatch = beforeCursor.match(/@(\w*)$/);
    
    if (mentionMatch) {
        const newText = beforeCursor.substring(0, mentionMatch.index) + `@${username} ` + afterCursor;
        textarea.value = newText;
        textarea.focus();
        
        // Position cursor after the mention
        const newCursorPos = mentionMatch.index + username.length + 2;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
    }
    
    hideMentionSuggestions(componentId);
}

// Load mention users
async function loadMentionUsers(componentId) {
    try {
        const response = await fetch('/api/users/staff');
        const data = await response.json();
        return data.success ? data.users : [];
    } catch (error) {
        console.error('Error loading mention users:', error);
        return [];
    }
}

// Edit comment
function editComment(commentId) {
    console.log('Edit comment:', commentId);
    showToast('Edit comment feature coming soon!', 'info');
}

// Delete comment
function deleteComment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        console.log('Delete comment:', commentId);
        showToast('Delete comment feature coming soon!', 'info');
    }
}

// Show toast function (placeholder - debe implementarse globalmente)
function showToast(message, type = 'info') {
    console.log(`Toast [${type}]: ${message}`);
    // Implementar sistema de toast notification
}
</script>

<!-- Comments Component Styles -->
<style>
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

.avatar {
    width: 32px;
    height: 32px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 600;
}

.avatar-sm {
    width: 28px;
    height: 28px;
}

.comment-attachments .btn {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .comment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .comment-actions {
        opacity: 1;
    }
}
</style>