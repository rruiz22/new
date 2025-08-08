<!-- Enhanced SMS Modal (80% screen width) -->
<div class="modal fade" id="enhancedSmsModal" tabindex="-1" aria-labelledby="enhancedSmsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 80vw; height: 80vh;">
        <div class="modal-content h-100">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-sm">
                            <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                <i data-feather="message-circle" class="icon-md"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="modal-title mb-0" id="enhancedSmsModalLabel"><?= lang('App.sms_conversation') ?></h4>
                        <small class="text-muted" id="smsContactInfo"><?= lang('App.loading_contact_info') ?>...</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <span class="badge bg-success-subtle text-success" id="smsStatus">
                            <i data-feather="wifi" class="icon-xs me-1"></i>
                            <?= lang('App.connected') ?>
                        </span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang('App.close') ?>"></button>
                </div>
            </div>
            
            <div class="modal-body p-0 d-flex flex-column h-100">
                <!-- Conversation Area -->
                <div class="flex-grow-1 d-flex flex-column">
                    <!-- Messages Container -->
                    <div class="flex-grow-1 p-3" style="overflow-y: auto; max-height: calc(80vh - 200px);">
                        <div id="smsConversationArea">
                            <!-- Messages will be loaded here -->
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden"><?= lang('App.loading_conversation') ?>...</span>
                                </div>
                                <p class="mt-2 text-muted"><?= lang('App.loading_conversation_history') ?>...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Typing Indicator (hidden by default) -->
                    <div id="typingIndicator" class="px-3 pb-2" style="display: none;">
                        <div class="d-flex align-items-center text-muted">
                            <div class="typing-animation me-2">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <small><?= lang('App.contact_is_typing') ?>...</small>
                        </div>
                    </div>
                </div>
                
                <!-- Message Input Area -->
                <div class="border-top bg-light p-3">
                    <form id="enhancedSmsForm" class="d-flex flex-column">
                        <!-- Quick Templates Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="smsTemplateSelect" class="form-label small"><?= lang('App.quick_templates') ?></label>
                                <select class="form-select form-select-sm" id="smsTemplateSelect">
                                    <option value=""><?= lang('App.choose_template') ?>...</option>
                                    <!-- Templates will be loaded dynamically -->
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small"><?= lang('App.message_info') ?></label>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">
                                        <span id="charCount">0</span>/1600 <?= lang('App.characters') ?>
                                    </small>
                                    <small class="text-muted">
                                        <span id="smsCount">1</span> SMS
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Message Input Row -->
                        <div class="d-flex align-items-end gap-2">
                            <div class="flex-grow-1">
                                <textarea 
                                    class="form-control" 
                                    id="enhancedSmsMessage" 
                                    name="message"
                                    rows="3" 
                                    placeholder="<?= lang('App.type_message_here') ?>..." 
                                    maxlength="1600"
                                    style="resize: none;"
                                ></textarea>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <!-- Send Button -->
                                <button type="submit" class="btn btn-primary" id="sendSmsBtn">
                                    <i data-feather="send" class="icon-sm"></i>
                                </button>
                                <!-- Attachment Button (Future feature) -->
                                <button type="button" class="btn btn-outline-secondary" title="<?= lang('App.attachments_coming_soon') ?>" disabled>
                                    <i data-feather="paperclip" class="icon-sm"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Hidden fields -->
                        <input type="hidden" id="smsOrderId" name="order_id" value="">
                        <input type="hidden" id="smsModule" name="module" value="">
                        <input type="hidden" id="smsPhone" name="phone" value="">
                        <input type="hidden" id="smsContactName" name="contact_name" value="">
                    </form>
                </div>
            </div>
            
            <!-- Modal Footer with Actions -->
            <div class="modal-footer border-top">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <small class="text-muted">
                            <i data-feather="phone" class="icon-xs me-1"></i>
                            <?= lang('App.from') ?>: <span id="twilioNumber"><?= lang('App.loading') ?>...</span>
                        </small>
                        <small class="text-muted">
                            <i data-feather="clock" class="icon-xs me-1"></i>
                            <?= lang('App.last_updated') ?>: <span id="lastUpdated"><?= lang('App.just_now') ?></span>
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshConversation">
                            <i data-feather="refresh-cw" class="icon-xs me-1"></i>
                            <?= lang('App.refresh') ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <?= lang('App.close') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SMS Modal Styles -->
<style>
.typing-animation {
    display: inline-flex;
    gap: 2px;
}

.typing-animation span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #6c757d;
    display: inline-block;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-animation span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-animation span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

.sms-message {
    max-width: 75%;
    margin-bottom: 15px;
    word-wrap: break-word;
}

.sms-message.outbound {
    margin-left: auto;
}

.sms-message.inbound {
    margin-right: auto;
}

.sms-message .message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
}

.sms-message.outbound .message-bubble {
    background-color: var(--vz-primary);
    color: white;
    border-bottom-right-radius: 4px;
}

.sms-message.inbound .message-bubble {
    background-color: var(--vz-light);
    color: var(--vz-body-color);
    border-bottom-left-radius: 4px;
    border: 1px solid var(--vz-border-color);
}

.sms-message .message-info {
    font-size: 11px;
    opacity: 0.7;
    margin-top: 4px;
}

.sms-message.outbound .message-info {
    text-align: right;
}

.sms-message.inbound .message-info {
    text-align: left;
}

.message-status {
    font-size: 10px;
    margin-left: 5px;
}

.message-status.delivered {
    color: var(--vz-success);
}

.message-status.failed {
    color: var(--vz-danger);
}

.message-status.pending {
    color: var(--vz-warning);
}

.char-counter.warning {
    color: var(--vz-warning) !important;
}

.char-counter.danger {
    color: var(--vz-danger) !important;
}

/* Scrollbar styling */
#smsConversationArea::-webkit-scrollbar {
    width: 6px;
}

#smsConversationArea::-webkit-scrollbar-track {
    background: var(--vz-light);
    border-radius: 3px;
}

#smsConversationArea::-webkit-scrollbar-thumb {
    background: var(--vz-secondary);
    border-radius: 3px;
}

#smsConversationArea::-webkit-scrollbar-thumb:hover {
    background: var(--vz-dark);
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    #enhancedSmsModal .modal-dialog {
        max-width: 95vw !important;
        height: 90vh !important;
        margin: 5vh auto !important;
    }
    
    .sms-message {
        max-width: 85%;
    }
    
    #enhancedSmsMessage {
        min-height: 2rem;
    }
}
</style>

<!-- Enhanced SMS JavaScript -->
<script>
class EnhancedSMSModal {
    constructor() {
        this.modal = null;
        this.conversationArea = null;
        this.messageInput = null;
        this.form = null;
        this.refreshInterval = null;
        this.currentOrderId = null;
        this.currentModule = null;
        this.currentPhone = null;
        this.currentContactName = null;
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        this.modal = document.getElementById('enhancedSmsModal');
        this.conversationArea = document.getElementById('smsConversationArea');
        this.messageInput = document.getElementById('enhancedSmsMessage');
        this.form = document.getElementById('enhancedSmsForm');
        
        if (this.modal) {
            this.bindEvents();
        }
    }
    
    bindEvents() {
        // Modal events
        this.modal.addEventListener('shown.bs.modal', () => {
            this.onModalShown();
        });
        
        this.modal.addEventListener('hidden.bs.modal', () => {
            this.onModalHidden();
        });
        
        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });
        
        // Character counter
        this.messageInput.addEventListener('input', () => {
            this.updateCharacterCount();
        });
        
        // Template selection
        document.getElementById('smsTemplateSelect').addEventListener('change', (e) => {
            this.applyTemplate(e.target.value);
        });
        
        // Refresh button
        document.getElementById('refreshConversation').addEventListener('click', () => {
            this.loadConversation();
        });
        
        // Enter key to send (Shift+Enter for new line)
        this.messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
    }
    
    open(orderData) {
        this.currentOrderId = orderData.order_id;
        this.currentModule = orderData.module;
        this.currentPhone = orderData.phone;
        this.currentContactName = orderData.contact_name;
        
        // Update modal title and contact info
        document.getElementById('smsContactInfo').textContent = 
            `${orderData.contact_name} • ${orderData.phone} • ${orderData.module.replace('_', ' ').toUpperCase()} #${orderData.order_id}`;
        
        // Set hidden form fields
        document.getElementById('smsOrderId').value = orderData.order_id;
        document.getElementById('smsModule').value = orderData.module;
        document.getElementById('smsPhone').value = orderData.phone;
        document.getElementById('smsContactName').value = orderData.contact_name;
        
        // Show modal
        const modalInstance = new bootstrap.Modal(this.modal);
        modalInstance.show();
    }
    
    onModalShown() {
        this.messageInput.focus();
        this.loadConversation();
        this.loadTemplates();
        this.startRefreshInterval();
    }
    
    onModalHidden() {
        this.stopRefreshInterval();
        this.clearForm();
    }
    
    async loadConversation() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        
        try {
            const response = await fetch(`<?= base_url() ?>sms/getConversation?phone=${encodeURIComponent(this.currentPhone)}&order_id=${this.currentOrderId}&module=${this.currentModule}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.renderMessages(data.messages);
                document.getElementById('twilioNumber').textContent = data.twilio_number || 'Not configured';
                this.updateLastUpdated();
            } else {
                this.showError('Failed to load conversation: ' + data.message);
            }
            
        } catch (error) {
            console.error('Error loading conversation:', error);
            this.showError('Failed to load conversation');
        } finally {
            this.isLoading = false;
        }
    }
    
    renderMessages(messages) {
        this.conversationArea.innerHTML = '';
        
        if (messages.length === 0) {
            this.conversationArea.innerHTML = `
                                        <div class="text-center py-4">
                            <i data-feather="message-circle" class="icon-lg text-muted mb-2"></i>
                            <p class="text-muted"><?= lang('App.no_messages_yet') ?>. <?= lang('App.start_conversation') ?>!</p>
                        </div>
            `;
            feather.replace();
            return;
        }
        
        messages.forEach(message => {
            const messageElement = this.createMessageElement(message);
            this.conversationArea.appendChild(messageElement);
        });
        
        // Scroll to bottom
        this.scrollToBottom();
        feather.replace();
    }
    
    createMessageElement(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `sms-message ${message.direction}`;
        
        const statusIcon = this.getStatusIcon(message.status);
        const timestamp = this.formatTimestamp(message.created_at);
        const senderName = message.direction === 'outbound' ? 
            (message.sender_name || 'You') : 
            this.currentContactName;
        
        messageDiv.innerHTML = `
            <div class="message-bubble">
                <div class="message-text">${this.escapeHtml(message.message)}</div>
            </div>
            <div class="message-info">
                <span class="sender-name">${senderName}</span>
                <span class="timestamp">${timestamp}</span>
                ${message.direction === 'outbound' ? `<span class="message-status ${message.status}">${statusIcon}</span>` : ''}
            </div>
        `;
        
        return messageDiv;
    }
    
    async sendMessage() {
        const message = this.messageInput.value.trim();
        
        if (!message) {
            this.showError('Please enter a message');
            return;
        }
        
        if (message.length > 1600) {
            this.showError('Message is too long');
            return;
        }
        
        const sendBtn = document.getElementById('sendSmsBtn');
        const originalHTML = sendBtn.innerHTML;
        
        try {
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i data-feather="loader" class="icon-sm spinning"></i>';
            
            const formData = new FormData();
            formData.append('phone', this.currentPhone);
            formData.append('message', message);
            formData.append('order_id', this.currentOrderId);
            formData.append('module', this.currentModule);
            formData.append('contact_name', this.currentContactName);
            
            const response = await fetch(`<?= base_url() ?>sms/send`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.messageInput.value = '';
                this.updateCharacterCount();
                this.loadConversation(); // Reload to show sent message
                this.showSuccess(data.message);
            } else {
                this.showError(data.message);
            }
            
        } catch (error) {
            console.error('Error sending SMS:', error);
            this.showError('Failed to send message');
        } finally {
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalHTML;
            feather.replace();
        }
    }
    
    async loadTemplates() {
        try {
            const response = await fetch(`<?= base_url() ?>sms/getTemplates?module=${this.currentModule}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                const select = document.getElementById('smsTemplateSelect');
                select.innerHTML = '<option value="">Choose a template...</option>';
                
                data.templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.message;
                    option.textContent = template.name;
                    select.appendChild(option);
                });
            }
            
        } catch (error) {
            console.error('Error loading templates:', error);
        }
    }
    
    applyTemplate(templateMessage) {
        if (!templateMessage) return;
        
        // Get the order URL (will be processed server-side, but show placeholder for now)
        const orderUrl = `<?= base_url() ?>${this.currentModule}/view/${this.currentOrderId}`;
        
        // Replace placeholders with actual data
        let message = templateMessage
            .replace('{contact_name}', this.currentContactName)
            .replace('{order_number}', `${this.currentModule.toUpperCase()}-${String(this.currentOrderId).padStart(5, '0')}`)
            .replace('{date}', new Date().toLocaleDateString())
            .replace('{time}', new Date().toLocaleTimeString())
            .replace('{order_url}', orderUrl);
        
        this.messageInput.value = message;
        this.updateCharacterCount();
        this.messageInput.focus();
    }
    
    updateCharacterCount() {
        const count = this.messageInput.value.length;
        const charCountElement = document.getElementById('charCount');
        const smsCountElement = document.getElementById('smsCount');
        
        charCountElement.textContent = count;
        
        // Calculate SMS count (160 chars per SMS for GSM, 70 for Unicode)
        const smsCount = Math.ceil(count / 160) || 1;
        smsCountElement.textContent = smsCount;
        
        // Update styling based on character count
        charCountElement.className = '';
        if (count > 1400) {
            charCountElement.classList.add('char-counter', 'danger');
        } else if (count > 1200) {
            charCountElement.classList.add('char-counter', 'warning');
        }
    }
    
    startRefreshInterval() {
        // Refresh conversation every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.loadConversation();
        }, 30000);
    }
    
    stopRefreshInterval() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }
    
    scrollToBottom() {
        this.conversationArea.scrollTop = this.conversationArea.scrollHeight;
    }
    
    clearForm() {
        this.messageInput.value = '';
        this.updateCharacterCount();
        document.getElementById('smsTemplateSelect').value = '';
    }
    
    updateLastUpdated() {
        document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
    }
    
    getStatusIcon(status) {
        const icons = {
            'sent': '✓',
            'delivered': '✓✓',
            'failed': '✗',
            'pending': '⏳'
        };
        return icons[status] || '';
    }
    
    formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diffInHours = (now - date) / (1000 * 60 * 60);
        
        if (diffInHours < 1) {
            const minutes = Math.floor((now - date) / (1000 * 60));
            return minutes <= 0 ? 'Just now' : `${minutes}m ago`;
        } else if (diffInHours < 24) {
            return `${Math.floor(diffInHours)}h ago`;
        } else {
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    showSuccess(message) {
        if (typeof showToast === 'function') {
            showToast('success', message);
        } else {
            alert(message);
        }
    }
    
    showError(message) {
        if (typeof showToast === 'function') {
            showToast('error', message);
        } else {
            alert('Error: ' + message);
        }
    }
}

// Initialize enhanced SMS modal when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('enhancedSmsModal')) {
        window.enhancedSMS = new EnhancedSMSModal();
    }
});

// Global function to open enhanced SMS modal
function openEnhancedSMSModal(orderData) {
    if (window.enhancedSMS) {
        window.enhancedSMS.open(orderData);
    }
}
</script>
