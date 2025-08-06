<?= $this->extend('partials/default') ?>

<?= $this->section('page_title') ?>Chat<?= $this->endSection() ?>

<?= $this->section('page_title_main') ?>Chat<?= $this->endSection() ?>

<?= $this->section('page_title_breadcrumb') ?>Chat<?= $this->endSection() ?>

<?= $this->section('head_css') ?>
    <!-- glightbox css -->
    <link rel="stylesheet" href="<?= base_url('assets/libs/glightbox/css/glightbox.min.css') ?>">
    <!-- Simplebar css -->
    <link rel="stylesheet" href="<?= base_url('assets/libs/simplebar/simplebar.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
                <div class="container-fluid">
                    <div class="d-lg-flex gap-1 mx-n4 mt-n4 p-1">
                        <div class="chat-leftsidebar">
                            <div class="px-4 pt-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-4">Chats</h5>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="Add Contact">
                                            <button type="button" class="btn btn-soft-success btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
                                                <i data-feather="user-plus" class="align-bottom"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="search-box">
                                    <input type="text" class="form-control bg-light border-light" placeholder="Search here..." id="searchContact">
                                    <i data-feather="search" class="search-icon"></i>
                                </div>
                            </div>

                            <ul class="nav nav-tabs nav-tabs-custom nav-success nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#chats" role="tab">
                                        Chats
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#groups" role="tab">
                                        Groups
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#contacts" role="tab">
                                        Contacts
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content text-muted">
                                <div class="tab-pane active" id="chats" role="tabpanel">
                                    <div class="chat-room-list pt-3" data-simplebar>
                                        <div class="d-flex align-items-center px-4 mb-2">
                                            <div class="flex-grow-1">
                                                <h4 class="mb-0 fs-11 text-muted text-uppercase"><?= lang('Chat.direct_messages') ?></h4>
                                            </div>
                                        </div>
        
                                        <div class="chat-message-list">
                                            <ul class="list-unstyled chat-list chat-user-list" id="userList">
                                                <!-- Chat contacts will be loaded here -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="groups" role="tabpanel">
                                    <div class="chat-room-list pt-3" data-simplebar>
                                        <div class="d-flex align-items-center px-4 mb-2">
                                            <div class="flex-grow-1">
                                                <h4 class="mb-0 fs-11 text-muted text-uppercase"><?= lang('Chat.group_chats') ?></h4>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom" title="<?= lang('Chat.create_group') ?>">
                                                    <button type="button" class="btn btn-soft-success btn-sm" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                                                        <i data-feather="plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="chat-message-list">
                                            <ul class="list-unstyled chat-list chat-user-list" id="groupList">
                                                <!-- Group chats will be loaded here -->
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane" id="contacts" role="tabpanel">
                                    <div class="chat-room-list pt-3" data-simplebar>
                                        <div class="d-flex align-items-center px-4 mb-2">
                                            <div class="flex-grow-1">
                                                <h4 class="mb-0 fs-11 text-muted text-uppercase"><?= lang('Chat.all_users') ?></h4>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <small class="text-muted" id="contactsCount"><?= lang('Chat.users_count', [0]) ?></small>
                                            </div>
                                        </div>
                                        
                                        <div class="sort-contact" id="contactList">
                                            <!-- All contacts will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end chat leftsidebar -->

                        <!-- Start User chat -->
                        <div class="user-chat w-100 overflow-hidden">
                            <div class="chat-content d-lg-flex">
                                <!-- start chat conversation section -->
                                <div class="w-100 overflow-hidden position-relative">
                                    <!-- Welcome Screen -->
                                    <div class="position-relative" id="welcome-screen">
                                        <div class="d-flex align-items-center justify-content-center h-100" style="min-height: 500px;">
                                            <div class="text-center p-4">
                                                <div class="mb-4">
                                                    <i data-feather="message-circle" class="text-primary" style="width: 48px; height: 48px;"></i>
                                                </div>
                                                <h4 class="mb-3"><?= lang('Chat.welcome_title') ?></h4>
                                                <p class="text-muted mb-4"><?= lang('Chat.welcome_message') ?></p>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContactModal">
                                                        <i data-feather="user-plus" class="me-1"></i>
                                                        Add Contact
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                                                        <i data-feather="users" class="me-1"></i>
                                                        Create Group
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Chat Area -->
                                    <div class="position-relative d-none" id="users-chat">
                                        <!-- Chat Header -->
                                        <div class="p-3 user-chat-topbar">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4 col-8">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 d-block d-lg-none me-3">
                                                            <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1" onclick="closeChat()">
                                                                <i data-feather="arrow-left"></i>
                                                            </a>
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <div class="d-flex align-items-center">
                                                                                                                <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                    <img src="<?= base_url('assets/images/users/avatar-2.jpg') ?>" class="rounded-circle avatar-xs" alt="" id="chatContactAvatar">
                                                    <span class="user-status" id="chatContactStatus"></span>
                                                </div>
                                                                <div class="flex-grow-1 overflow-hidden">
                                                                    <h5 class="text-truncate mb-0 fs-16">
                                                                        <a class="text-reset username" href="javascript:void(0);" id="chatContactName">Select Contact</a>
                                                                    </h5>
                                                                    <p class="text-truncate text-muted fs-14 mb-0 userStatus">
                                                                        <small id="chatContactStatusText">Offline</small>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 col-4">
                                                    <ul class="list-inline user-chat-nav text-end mb-0">
                                                        <li class="list-inline-item m-0">
                                                            <div class="dropdown">
                                                                <button class="btn btn-ghost-secondary btn-icon" type="button" data-bs-toggle="dropdown">
                                                                    <i data-feather="search" class="icon-sm"></i>
                                                                </button>
                                                                <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                                                                    <div class="p-2">
                                                                        <div class="search-box">
                                                                            <input type="text" class="form-control bg-light border-light" placeholder="Search messages..." id="searchMessage">
                                                                            <i data-feather="search" class="search-icon"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="list-inline-item d-none d-lg-inline-block m-0">
                                                            <button type="button" class="btn btn-ghost-secondary btn-icon">
                                                                <i data-feather="phone" class="icon-sm"></i>
                                                            </button>
                                                        </li>
                                                        <li class="list-inline-item d-none d-lg-inline-block m-0">
                                                            <button type="button" class="btn btn-ghost-secondary btn-icon">
                                                                <i data-feather="video" class="icon-sm"></i>
                                                            </button>
                                                        </li>
                                                        <li class="list-inline-item m-0">
                                                            <div class="dropdown">
                                                                <button class="btn btn-ghost-secondary btn-icon" type="button" data-bs-toggle="dropdown">
                                                                    <i data-feather="more-horizontal" class="icon-sm"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i data-feather="user" class="icon-sm me-2 align-middle"></i>View Profile</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i data-feather="archive" class="icon-sm me-2 align-middle"></i>Archive</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i data-feather="mic-off" class="icon-sm me-2 align-middle"></i>Muted</a>
                                                                    <a class="dropdown-item" href="javascript:void(0);"><i data-feather="trash-2" class="icon-sm me-2 align-middle"></i>Delete</a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end chat user head -->

                                        <!-- Chat Conversation -->
                                        <div class="chat-conversation p-3 p-lg-4" id="chat-conversation" data-simplebar style="height: 400px;">
                                            <ul class="list-unstyled chat-conversation-list" id="conversation-list">
                                                <!-- Messages will be loaded here -->
                                            </ul>
                                        </div>
                                        <!-- end chat conversation -->

                                        <!-- Chat Input -->
                                        <div class="chat-input-section p-3 p-lg-4 border-top mb-0">
                                            <form id="chatinput-form" enctype="multipart/form-data">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-auto">
                                                        <div class="chat-input-links me-2">
                                                            <div class="links-list-item">
                                                                <button type="button" class="btn btn-link text-decoration-none emoji-btn" id="emoji-btn">
                                                                    <i data-feather="smile" class="icon-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="chat-input-feedback">
                                                            Please Enter a Message
                                                        </div>
                                                        <input type="text" class="form-control chat-input bg-light border-light" id="chat-input" placeholder="Type your message..." autocomplete="off">
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="chat-input-links ms-2">
                                                            <div class="links-list-item">
                                                                <label for="attachedfile-input" class="btn btn-link text-decoration-none">
                                                                    <i data-feather="paperclip" class="icon-sm"></i>
                                                                </label>
                                                                <input type="file" class="d-none" id="attachedfile-input" accept=".zip,.rar,.7zip,.pdf,.jpg,.jpeg,.png,.gif,.webp,.mp4,.mp3,.mov">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="chat-input-links ms-2 me-md-0">
                                                            <div class="links-list-item">
                                                                <button type="submit" class="btn btn-success chat-send waves-effect waves-light">
                                                                    <i data-feather="send" class="icon-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end chat conversation section -->
                            </div>
                        </div>
                        <!-- End User chat -->
                    </div>
                    <!-- end chat-wrapper -->
                </div>
                <!-- container-fluid -->

                <!-- Add Contact Modal -->
                <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addContactModalLabel">Add New Contact</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addContactForm">
                                    <div class="mb-3">
                                        <label for="contactName" class="form-label">Contact Name</label>
                                        <input type="text" class="form-control" id="contactName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contactEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="contactEmail" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="addContact()">Add Contact</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Group Modal -->
                <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createGroupModalLabel">Create New Group</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="createGroupForm">
                                    <div class="mb-3">
                                        <label for="groupName" class="form-label">Group Name</label>
                                        <input type="text" class="form-control" id="groupName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="groupDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="groupDescription" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="groupMembers" class="form-label">Select Members</label>
                                        <select class="form-select" id="groupMembers" multiple>
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <div class="form-text">Hold Ctrl/Cmd to select multiple members</div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-success" onclick="createGroup()">Create Group</button>
                            </div>
                        </div>
                    </div>
                </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- SweetAlert2 -->
    <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

    <!-- Toastify JS -->
    <script src="<?= base_url('assets/libs/toastify-js/src/toastify.js') ?>"></script>

    <!-- glightbox js -->
    <script src="<?= base_url('assets/libs/glightbox/js/glightbox.min.js') ?>"></script>
    
    <!-- fgEmojiPicker js -->
    <script src="<?= base_url('assets/libs/fg-emoji-picker/fgEmojiPicker.js') ?>"></script>

    <!-- Simplebar js -->
    <script src="<?= base_url('assets/libs/simplebar/simplebar.min.js') ?>"></script>

    <script>
    // Chat Application Class
    class ChatApplication {
        constructor() {
            this.currentConversationId = null;
            this.currentUserId = <?= session()->get('user_id') ?? 1 ?>;
            this.contacts = [];
            this.groups = [];
            this.messages = [];
            this.isLoading = false;
            
            this.init();
        }

        init() {
            console.log('🚀 Professional Chat Application Started');
            
            this.setupEventListeners();
            this.loadContacts();
            this.loadGroups();
            this.initializeFeatherIcons();
            this.showWelcomeMessage();
        }

        setupEventListeners() {
            // Search functionality
            document.getElementById('searchContact').addEventListener('input', (e) => {
                this.searchContacts(e.target.value);
            });

            // Chat form submit
            document.getElementById('chatinput-form').addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });

            // File attachment
            document.getElementById('attachedfile-input').addEventListener('change', (e) => {
                this.handleFileAttachment(e.target.files[0]);
            });

            // Enter key to send message
            document.getElementById('chat-input').addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }

        initializeFeatherIcons() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        showWelcomeMessage() {
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: "Chat application ready!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#28a745"
                    }
                }).showToast();
            }
        }

        async loadContacts() {
            try {
                // Load real contacts from API
                const response = await fetch('<?= base_url('chat/contacts') ?>', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.contacts) {
                        // Convert API data to our format
                        this.contacts = data.contacts.map(contact => ({
                            id: contact.id,
                            user_id: contact.user_id,
                            name: contact.name,
                            email: contact.email,
                            avatar: contact.avatar,
                            status: contact.status || 'offline',
                            lastMessage: contact.last_message || 'No messages yet',
                            lastTime: contact.last_time || 'Never',
                            unreadCount: contact.unread_count || 0,
                            client_name: contact.client_name,
                            is_client: contact.is_client,
                            position: contact.position,
                            conversation_id: contact.conversation_id
                        }));
                    } else {
                        // Fallback to sample data if API fails
                        this.loadSampleContacts();
                    }
                } else {
                    console.error('Failed to load contacts:', response.status);
                    this.loadSampleContacts();
                }
            } catch (error) {
                console.error('Error loading contacts:', error);
                this.loadSampleContacts();
            }

            this.renderContacts();
            this.updateContactsCount();
        }

        loadSampleContacts() {
            // Fallback sample contacts
            this.contacts = [
                {
                    id: 1,
                    name: 'John Doe',
                    email: 'john@example.com',
                    avatar: '<?= base_url('assets/images/users/avatar-1.jpg') ?>',
                    status: 'online',
                    lastMessage: 'Hey there! How are you?',
                    lastTime: '2 min ago',
                    unreadCount: 2
                },
                {
                    id: 2,
                    name: 'Jane Smith',
                    email: 'jane@example.com',
                    avatar: '<?= base_url('assets/images/users/avatar-2.jpg') ?>',
                    status: 'offline',
                    lastMessage: 'Thanks for the update!',
                    lastTime: '1 hour ago',
                    unreadCount: 0
                },
                {
                    id: 3,
                    name: 'Mike Johnson',
                    email: 'mike@example.com',
                    avatar: '<?= base_url('assets/images/users/avatar-3.jpg') ?>',
                    status: 'online',
                    lastMessage: 'See you tomorrow',
                    lastTime: '3 hours ago',
                    unreadCount: 1
                }
            ];
        }

        loadGroups() {
            // Sample groups - in real app, load from API
            this.groups = [
                {
                    id: 1,
                    name: 'Development Team',
                    description: 'Main development discussions',
                    members: 5,
                    lastMessage: 'New feature deployed!',
                    lastTime: '30 min ago',
                    unreadCount: 3
                },
                {
                    id: 2,
                    name: 'Project Alpha',
                    description: 'Project Alpha coordination',
                    members: 8,
                    lastMessage: 'Meeting at 3 PM',
                    lastTime: '2 hours ago',
                    unreadCount: 0
                }
            ];

            this.renderGroups();
        }

        renderContacts() {
            const userList = document.getElementById('userList');
            const contactList = document.getElementById('contactList');
            
            let chatHTML = '';
            let contactHTML = '';

            this.contacts.forEach(contact => {
                const unreadBadge = contact.unreadCount > 0 ? 
                    `<span class="badge badge-soft-danger rounded-pill">${contact.unreadCount}</span>` : '';

                chatHTML += `
                    <li onclick="openChat(${contact.id}, 'contact')">
                        <a href="javascript:void(0);" class="unread-msg-user">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 chat-user-img ${contact.status} align-self-center me-2 ms-0">
                                    <div class="avatar-xxs">
                                        <img src="${contact.avatar}" class="rounded-circle img-fluid userprofile" alt="">
                                        <span class="user-status"></span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-truncate mb-0">${contact.name}</p>
                                    <div class="text-truncate text-muted fs-12">
                                        <i data-feather="message-circle" class="me-1 ms-0"></i>${contact.lastMessage}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="text-muted fs-11">${contact.lastTime}</div>
                                        ${unreadBadge}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                `;

                // Show client info if available
                const clientInfo = contact.client_name ? 
                    `<div class="text-truncate text-muted fs-12"><i data-feather="briefcase" class="me-1"></i>${contact.client_name}</div>` : '';
                
                const positionInfo = contact.position ? 
                    `<div class="text-truncate text-muted fs-12">${contact.position}</div>` : '';

                contactHTML += `
                    <div class="contact-list-item p-2 border-bottom" onclick="openChat(${contact.id}, 'contact')" style="cursor: pointer;">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 chat-user-img align-self-center me-3 ms-0">
                                <div class="avatar-xs">
                                    <img src="${contact.avatar}" class="rounded-circle img-fluid" alt="">
                                    <span class="user-status"></span>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="text-truncate mb-1">${contact.name}</h6>
                                <div class="text-truncate text-muted fs-13">${contact.email}</div>
                                ${clientInfo}
                                ${positionInfo}
                            </div>
                            <div class="flex-shrink-0">
                                <div class="d-flex align-items-center">
                                    <i data-feather="circle" class="icon-xs text-${contact.status === 'online' ? 'success' : 'secondary'}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            userList.innerHTML = chatHTML;
            contactList.innerHTML = contactHTML;
            this.initializeFeatherIcons();
        }

        renderGroups() {
            const groupList = document.getElementById('groupList');
            
            let groupHTML = '';

            this.groups.forEach(group => {
                const unreadBadge = group.unreadCount > 0 ? 
                    `<span class="badge badge-soft-danger rounded-pill">${group.unreadCount}</span>` : '';

                groupHTML += `
                    <li onclick="openChat(${group.id}, 'group')">
                        <a href="javascript:void(0);">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 chat-user-img align-self-center me-2 ms-0">
                                    <div class="avatar-xxs">
                                        <div class="avatar-title rounded-circle bg-light text-body">
                                            <i data-feather="users"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-truncate mb-0">${group.name}</p>
                                    <div class="text-truncate text-muted fs-12">
                                        <i data-feather="users" class="me-1 ms-0"></i>${group.members} members
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex flex-column align-items-end">
                                        <div class="text-muted fs-11">${group.lastTime}</div>
                                        ${unreadBadge}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                `;
            });

            groupList.innerHTML = groupHTML;
            this.initializeFeatherIcons();
        }

        updateContactsCount() {
            document.getElementById('contactsCount').textContent = `${this.contacts.length} contacts`;
        }

        searchContacts(query) {
            // Filter contacts based on search query
            const filteredContacts = this.contacts.filter(contact =>
                contact.name.toLowerCase().includes(query.toLowerCase()) ||
                contact.email.toLowerCase().includes(query.toLowerCase())
            );

            // Re-render with filtered results
            // Implementation would go here
        }

        openChat(id, type) {
            this.currentConversationId = id;
            
            // Hide welcome screen and show chat
            const welcomeScreen = document.getElementById('welcome-screen');
            const usersChat = document.getElementById('users-chat');
            
            if (welcomeScreen) {
                welcomeScreen.style.display = 'none';
            }
            
            if (usersChat) {
                usersChat.classList.remove('d-none');
                usersChat.style.display = 'block';
            }

            if (type === 'contact') {
                const contact = this.contacts.find(c => c.id === id);
                if (contact) {
                    this.loadContactChat(contact);
                }
            } else if (type === 'group') {
                const group = this.groups.find(g => g.id === id);
                if (group) {
                    this.loadGroupChat(group);
                }
            }

            // Ensure chat conversation is visible
            const chatConversation = document.getElementById('chat-conversation');
            if (chatConversation) {
                chatConversation.style.display = 'block';
            }
        }

        loadContactChat(contact) {
            // Update chat header
            const chatContactName = document.getElementById('chatContactName');
            const chatContactAvatar = document.getElementById('chatContactAvatar');
            const chatContactStatusText = document.getElementById('chatContactStatusText');
            
            if (chatContactName) {
                chatContactName.textContent = contact.name;
            }
            if (chatContactAvatar) {
                chatContactAvatar.src = contact.avatar;
            }
            if (chatContactStatusText) {
                chatContactStatusText.textContent = contact.status === 'online' ? 'Online' : 'Offline';
            }
            
            // Clear existing messages and load sample messages
            const conversationList = document.getElementById('conversation-list');
            if (conversationList) {
                conversationList.innerHTML = '';
            }
            
            // Load sample messages
            this.loadSampleMessages(contact);
            
            // Show conversation area and chat input
            const chatConversation = document.getElementById('chat-conversation');
            const chatInputSection = document.querySelector('.chat-input-section');
            
            if (chatConversation) {
                chatConversation.style.display = 'block';
                chatConversation.style.visibility = 'visible';
            }
            
            if (chatInputSection) {
                chatInputSection.style.display = 'block';
                chatInputSection.style.visibility = 'visible';
            }

            // Show success message
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: `Chat started with ${contact.name}`,
                    duration: 2000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#28a745"
                    }
                }).showToast();
            }
        }

        loadGroupChat(group) {
            // Update chat header
            document.getElementById('chatContactName').textContent = group.name;
            document.getElementById('chatContactStatusText').textContent = `${group.members} members`;
            
            // Load sample group messages
            this.loadSampleGroupMessages(group);
        }

        loadSampleMessages(contact) {
            const sampleMessages = [
                {
                    id: 1,
                    sender: contact.name,
                    message: 'Hello! How are you today?',
                    time: '10:00 AM',
                    isOwn: false
                },
                {
                    id: 2,
                    sender: 'You',
                    message: 'Hi there! I\'m doing well, thanks for asking.',
                    time: '10:02 AM',
                    isOwn: true
                },
                {
                    id: 3,
                    sender: contact.name,
                    message: 'That\'s great to hear! Are you free for a quick call later?',
                    time: '10:05 AM',
                    isOwn: false
                }
            ];

            this.renderMessages(sampleMessages);
        }

        loadSampleGroupMessages(group) {
            const sampleMessages = [
                {
                    id: 1,
                    sender: 'John Doe',
                    message: 'Hey team! Ready for today\'s standup?',
                    time: '9:00 AM',
                    isOwn: false
                },
                {
                    id: 2,
                    sender: 'You',
                    message: 'Yes, I\'m ready! Let\'s discuss the new features.',
                    time: '9:02 AM',
                    isOwn: true
                },
                {
                    id: 3,
                    sender: 'Jane Smith',
                    message: 'I\'ve finished the UI mockups for review.',
                    time: '9:05 AM',
                    isOwn: false
                }
            ];

            this.renderMessages(sampleMessages);
        }

        renderMessages(messages) {
            const conversationList = document.getElementById('conversation-list');
            let messagesHTML = '';

            messages.forEach(message => {
                const messageClass = message.isOwn ? 'chat-list right' : 'chat-list left';
                
                messagesHTML += `
                    <li class="${messageClass}">
                        <div class="conversation-list">
                            ${!message.isOwn ? `
                                <div class="chat-avatar">
                                    <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="">
                                </div>
                            ` : ''}
                            <div class="user-chat-content">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content">
                                        <p class="mb-0 ctext-content">${message.message}</p>
                                    </div>
                                    <div class="dropdown align-self-start message-box-drop">
                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                            <i data-feather="more-vertical" class="icon-xs"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i data-feather="copy" class="icon-xs me-1"></i>Copy</a></li>
                                            <li><a class="dropdown-item" href="#"><i data-feather="save" class="icon-xs me-1"></i>Save</a></li>
                                            <li><a class="dropdown-item" href="#"><i data-feather="share-2" class="icon-xs me-1"></i>Forward</a></li>
                                            <li><a class="dropdown-item" href="#"><i data-feather="trash-2" class="icon-xs me-1"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="conversation-name">
                                                                    <small class="text-muted time">${message.time}</small>
                                <span class="text-success">
                                    <i data-feather="check-circle" class="align-middle"></i>
                                </span>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
            });

            conversationList.innerHTML = messagesHTML;
            this.initializeFeatherIcons();
            this.scrollToBottom();
        }

        sendMessage() {
            const chatInput = document.getElementById('chat-input');
            const message = chatInput.value.trim();

            if (message) {
                // Add message to UI
                this.addMessageToUI({
                    id: Date.now(),
                    sender: 'You',
                    message: message,
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                    isOwn: true
                });

                // Clear input
                chatInput.value = '';

                // In real app, send to server via API/WebSocket
                this.simulateResponse(message);
            }
        }

        addMessageToUI(message) {
            const conversationList = document.getElementById('conversation-list');
            const messageClass = message.isOwn ? 'chat-list right' : 'chat-list left';
            
            const messageHTML = `
                <li class="${messageClass}">
                    <div class="conversation-list">
                        ${!message.isOwn ? `
                            <div class="chat-avatar">
                                <img src="<?= base_url('assets/images/users/avatar-1.jpg') ?>" alt="">
                            </div>
                        ` : ''}
                        <div class="user-chat-content">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content">
                                    <p class="mb-0 ctext-content">${message.message}</p>
                                </div>
                            </div>
                            <div class="conversation-name">
                                <small class="text-muted time">${message.time}</small>
                                <span class="text-success">
                                    <i data-feather="check-circle" class="align-middle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
            `;

            conversationList.insertAdjacentHTML('beforeend', messageHTML);
            this.initializeFeatherIcons();
            this.scrollToBottom();
        }

        simulateResponse(originalMessage) {
            // Simulate a response after 1-2 seconds
            setTimeout(() => {
                const responses = [
                    "That's interesting! Tell me more.",
                    "I understand. Thanks for letting me know.",
                    "Great! I'll get back to you on that.",
                    "Sounds good to me!",
                    "Let me think about it and get back to you."
                ];

                const randomResponse = responses[Math.floor(Math.random() * responses.length)];

                this.addMessageToUI({
                    id: Date.now(),
                    sender: 'Contact',
                    message: randomResponse,
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                    isOwn: false
                });
            }, Math.random() * 2000 + 1000);
        }

        scrollToBottom() {
            const chatConversation = document.getElementById('chat-conversation');
            chatConversation.scrollTop = chatConversation.scrollHeight;
        }

        handleFileAttachment(file) {
            if (file) {
                // Show file upload message
                this.addMessageToUI({
                    id: Date.now(),
                    sender: 'You',
                    message: `📎 File shared: ${file.name}`,
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                    isOwn: true
                });

                // In real app, upload file to server
                console.log('File selected:', file);
            }
        }

        closeChat() {
            const usersChat = document.getElementById('users-chat');
            const welcomeScreen = document.getElementById('welcome-screen');
            
            if (usersChat) {
                usersChat.classList.add('d-none');
                usersChat.style.display = 'none';
            }
            
            if (welcomeScreen) {
                welcomeScreen.style.display = 'block';
            }
            
            this.currentConversationId = null;
            
            // Clear conversation
            const conversationList = document.getElementById('conversation-list');
            if (conversationList) {
                conversationList.innerHTML = '';
            }
        }
    }

    // Global functions for modal actions
    function addContact() {
        const name = document.getElementById('contactName').value;
        const email = document.getElementById('contactEmail').value;
        
        if (name && email) {
            // Add to contacts list
            const newContact = {
                id: Date.now(),
                name: name,
                email: email,
                avatar: '<?= base_url('assets/images/users/avatar-1.jpg') ?>',
                status: 'offline',
                lastMessage: 'No messages yet',
                lastTime: 'Just added',
                unreadCount: 0
            };

            chatApp.contacts.push(newContact);
            chatApp.renderContacts();
            chatApp.updateContactsCount();

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addContactModal'));
            modal.hide();

            // Show success message
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: `Contact "${name}" added successfully!`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#28a745"
                    }
                }).showToast();
            }

            // Reset form
            document.getElementById('addContactForm').reset();
        }
    }

    function createGroup() {
        const name = document.getElementById('groupName').value;
        const description = document.getElementById('groupDescription').value;
        
        if (name) {
            // Add to groups list
            const newGroup = {
                id: Date.now(),
                name: name,
                description: description,
                members: 1,
                lastMessage: 'Group created',
                lastTime: 'Just now',
                unreadCount: 0
            };

            chatApp.groups.push(newGroup);
            chatApp.renderGroups();

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createGroupModal'));
            modal.hide();

            // Show success message
            if (typeof Toastify !== 'undefined') {
                Toastify({
                    text: `Group "${name}" created successfully!`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#28a745"
                    }
                }).showToast();
            }

            // Reset form
            document.getElementById('createGroupForm').reset();
        }
    }

    function openChat(id, type) {
        chatApp.openChat(id, type);
    }

    function closeChat() {
        chatApp.closeChat();
    }

    // Initialize chat application when page loads
    let chatApp;
    document.addEventListener('DOMContentLoaded', function() {
        chatApp = new ChatApplication();
    });
    </script>
<?= $this->endSection() ?> 