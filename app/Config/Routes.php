<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rutas de autenticación
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');
$routes->get('auth/clear-session', 'AuthController::clearSession');

// Language routes (no auth required)
$routes->get('language/(:alpha)', 'LanguageController::setLanguage/$1');
$routes->get('language/available', 'LanguageController::getAvailableLanguages');
$routes->get('language/current', 'LanguageController::getCurrentLanguage');
$routes->get('language/translations/(:alpha)', 'LanguageController::getTranslationsJson/$1');

// Test routes for debugging redirect functionality
$routes->get('test-redirect', 'TestController::testRedirect', ['filter' => 'sessionauth']);
$routes->get('test-filter', 'TestController::testFilter', ['filter' => 'sessionauth']);
$routes->get('test-session', 'TestController::showSession');
$routes->get('force-logout', function() {
    auth()->logout();
    session()->destroy();
    return redirect()->to('login')->with('message', 'Logged out successfully. Now test the filter.');
});

// Rutas protegidas (requieren autenticación)
$routes->group('', function ($routes) {
    $routes->get('dashboard', 'DashboardController::index', ['filter' => 'sessionauth']);
    $routes->get('profile', 'ProfileController::index', ['filter' => 'sessionauth']);
    $routes->get('profile/edit', 'ProfileController::edit', ['filter' => 'sessionauth']);
    $routes->post('profile/update', 'ProfileController::update', ['filter' => 'sessionauth']);
    $routes->get('profile/avatar-demo', 'ProfileController::avatarDemo', ['filter' => 'sessionauth']);
    
    // Global SMS Routes (Twilio Integration)
    $routes->post('sms/send', 'SMSController::send');
    $routes->get('sms/getConversation', 'SMSController::getConversation');
    $routes->get('sms/getTemplates', 'SMSController::getTemplates');
    $routes->post('sms/markAsRead', 'SMSController::markAsRead');
});

// Twilio Webhooks (no auth required - outside protected group)
$routes->post('sms/webhook', 'SMSController::webhook');
$routes->post('sms/webhook/status', 'SMSController::statusWebhook');

// Continue with protected routes
$routes->group('', function ($routes) {
    
    // Rutas para la administración de staff
    $routes->get('staff', 'StaffController::index');
    $routes->get('staff/create', 'StaffController::create');
    $routes->post('staff/store', 'StaffController::store');
    $routes->get('staff/edit/(:num)', 'StaffController::edit/$1');
    $routes->post('staff/update/(:num)', 'StaffController::update/$1');
    $routes->get('staff/delete/(:num)', 'StaffController::delete/$1');
    $routes->get('staff/ajax-delete/(:num)', 'StaffController::ajaxDelete/$1');
    
    // Rutas para la gestión de roles
    $routes->get('roles', 'RoleController::index');
    $routes->get('roles/create', 'RoleController::create');
    $routes->post('roles/store', 'RoleController::store');
    $routes->get('roles/edit/(:num)', 'RoleController::edit/$1');
    $routes->post('roles/update/(:num)', 'RoleController::update/$1');
    $routes->get('roles/delete/(:num)', 'RoleController::delete/$1');
    $routes->post('roles/delete/(:num)', 'RoleController::delete/$1'); // AJAX delete
    $routes->post('roles/toggle_status', 'RoleController::toggle_status');
    $routes->post('roles/toggle_staff_form', 'RoleController::toggle_staff_form');
    $routes->post('roles/toggleActive/(:num)', 'RoleController::toggleActive/$1');
    $routes->post('roles/toggleStaffForm/(:num)', 'RoleController::toggleStaffForm/$1');
    $routes->get('roles/users/(:any)', 'RoleController::users/$1');
    $routes->get('roles/add-user/(:any)', 'RoleController::addUser/$1');
    $routes->post('roles/assign', 'RoleController::assignRole');
    $routes->get('roles/remove-user/(:num)/(:any)', 'RoleController::removeUser/$1/$2');
    
    // Rutas para la gestión de clientes (empresas)
    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/create', 'ClientController::create');
    $routes->post('clients/store', 'ClientController::store');
    $routes->get('clients/(:num)', 'ClientController::show/$1');
    $routes->get('clients/edit/(:num)', 'ClientController::edit/$1');
    $routes->post('clients/update/(:num)', 'ClientController::update/$1');
    $routes->post('clients/delete', 'ClientController::delete');
    
    // Rutas para la gestión de contactos (empleados)
    $routes->get('contacts', 'ContactController::index');
    $routes->get('contacts/getContactsData', 'ContactController::getContactsData');
    $routes->get('contacts/create', 'ContactController::create');
    $routes->post('contacts/store', 'ContactController::store');
    $routes->get('contacts/(:num)', 'ContactController::show/$1');
    $routes->get('contacts/edit/(:num)', 'ContactController::edit/$1');
    $routes->post('contacts/update/(:num)', 'ContactController::update/$1');
    $routes->post('contacts/delete', 'ContactController::delete');
    $routes->get('contacts/by-client', 'ContactController::getContactsByClient');
    
    // Contact Groups Management Routes
    $routes->group('contact-groups', function($routes) {
        // Basic CRUD operations
        $routes->get('/', 'ContactGroupController::index');
        $routes->get('create', 'ContactGroupController::create');
        $routes->post('store', 'ContactGroupController::store');
        $routes->get('(:num)', 'ContactGroupController::show/$1');
        $routes->get('(:num)/edit', 'ContactGroupController::edit/$1');
        $routes->post('update/(:num)', 'ContactGroupController::update/$1');
        $routes->post('delete', 'ContactGroupController::delete');
        
        // User assignment AJAX endpoints
        $routes->post('assign-user', 'ContactGroupController::assignUser');
        $routes->post('remove-user', 'ContactGroupController::removeUser');
        $routes->get('get-group-users', 'ContactGroupController::getGroupUsers');
        $routes->get('get-available-users', 'ContactGroupController::getAvailableUsers');
        $routes->get('get-user-groups', 'ContactGroupController::getUserGroups');
        $routes->get('get-available-groups', 'ContactGroupController::getAvailableGroups');
        $routes->post('update-sort-order', 'ContactGroupController::updateSortOrder');
    });
    
    // Staff Roles Management Routes
    $routes->group('staff-roles', function($routes) {
        // Basic CRUD operations
        $routes->get('/', 'StaffRoleController::index');
        $routes->get('create', 'StaffRoleController::create');
        $routes->post('store', 'StaffRoleController::store');
        $routes->get('(:num)', 'StaffRoleController::show/$1');
        $routes->get('(:num)/edit', 'StaffRoleController::edit/$1');
        $routes->post('update/(:num)', 'StaffRoleController::update/$1');
        $routes->post('delete/(:num)', 'StaffRoleController::delete/$1');
        
        // User assignment AJAX endpoints
        $routes->post('assign-user', 'StaffRoleController::assignUser');
        $routes->post('remove-user', 'StaffRoleController::removeUser');
        $routes->get('get-role/(:num)', 'StaffRoleController::getRole/$1');
        $routes->post('update-sort-order', 'StaffRoleController::updateSortOrder');
    });
    
    // Contact Invitations Management Routes
    $routes->group('contact-invitations', function($routes) {
        // Basic CRUD operations for invitations
        $routes->get('/', 'ContactInvitationController::index');
        $routes->get('create', 'ContactInvitationController::create');
        $routes->post('send', 'ContactInvitationController::send');
        
        // AJAX endpoints for invitation management
        $routes->post('cancel', 'ContactInvitationController::cancel');
        $routes->post('resend', 'ContactInvitationController::resend');
        $routes->get('group-invitations', 'ContactInvitationController::getGroupInvitations');
    });
    
    // Public invitation routes (no authentication required)
    $routes->group('invitations', function($routes) {
        $routes->get('accept/(:segment)', 'ContactInvitationController::accept/$1');
        $routes->post('process-acceptance', 'ContactInvitationController::processAcceptance');
    });
    
    // Rutas de administración (si las necesitas)
    $routes->group('admin', function ($routes) {
        $routes->get('/', 'Admin\DashboardController::index');
        $routes->get('users', 'Admin\UsersController::index');
        // Más rutas de administración...
    });
    
    // Todo Routes
    $routes->get('todos', 'TodoController::index');
    $routes->get('todos/search', 'TodoController::searchTodos');
    $routes->get('todos/debug', 'TodoController::debug');
    $routes->get('todos/create', 'TodoController::create');
    $routes->post('todos/store', 'TodoController::store');
    $routes->get('todos/edit/(:num)', 'TodoController::edit/$1');
    $routes->post('todos/update/(:num)', 'TodoController::update/$1');
    $routes->post('todos/toggleStatus/(:num)', 'TodoController::toggleStatus/$1');
    $routes->delete('todos/delete/(:num)', 'TodoController::delete/$1');
    
    // Advanced Todo Routes (AJAX)
    $routes->get('todos/overdue', 'TodoController::getOverdue');
    $routes->get('todos/due-today', 'TodoController::getDueToday');
    $routes->get('todos/getStats', 'TodoController::getStats');
    $routes->get('todos/stats', 'TodoController::getStats');
    $routes->get('todos/getNotifications', 'TodoController::getNotifications');
    $routes->get('todos/notifications', 'TodoController::getNotifications');
    $routes->post('todos/markNotificationRead/(:num)', 'TodoController::markNotificationRead/$1');
    $routes->post('todos/markAllNotificationsRead', 'TodoController::markAllNotificationsRead');
    
    // Todo Notification Processing (for cron jobs)
    $routes->get('todos/process-overdue-notifications', 'TodoController::processOverdueNotifications');
    $routes->get('todos/process-due-today-notifications', 'TodoController::processDueTodayNotifications');
    
    // Internal Notes Routes (for Sales Orders)
    $routes->group('internal-notes', function($routes) {
        // Get notes for a specific order
        $routes->get('order/(:num)', 'InternalNotesController::getOrderNotes/$1');
        
        // CRUD operations
        $routes->post('create', 'InternalNotesController::create');
        $routes->post('update/(:num)', 'InternalNotesController::update/$1');
        $routes->delete('delete/(:num)', 'InternalNotesController::delete/$1');
        $routes->post('delete/(:num)', 'InternalNotesController::delete/$1'); // Support POST for delete
        
        // Reply operations
        $routes->post('addReply', 'InternalNotesController::addReply');
        
        // File download
        $routes->get('download/(:any)', 'InternalNotesController::download/$1');
        
        // Test endpoint
        $routes->get('test', 'InternalNotesController::test');
        
        // Staff users for mentions
        $routes->get('staff-users', 'InternalNotesController::getStaffUsers');
        
        // Mention management
        $routes->post('mark-mention-read/(:num)', 'InternalNotesController::markMentionRead/$1');
        $routes->get('unread-mentions', 'InternalNotesController::getUnreadMentions');
    });
    
    // Rutas JSON para dropdowns (sin filtro de sesión para permitir AJAX)
    $routes->get('clients/get_clients_json', 'ClientController::get_clients_json');
    $routes->get('contacts/get_contacts_by_client_json/(:num)', 'ContactController::get_contacts_by_client_json/$1');
    $routes->get('sales_orders_services/get_services_by_client_json/(:num)', 'SalesOrdersServicesController::get_services_by_client_json/$1');

    // Chat Routes
    $routes->get('chat', 'ChatController::index');
    $routes->get('chat/contacts', 'ChatController::getContacts');
    $routes->get('chat/channels', 'ChatController::getChannels');
    $routes->get('chat/messages/(:num)', 'ChatController::getMessages/$1');
    $routes->get('chat/channel/messages/(:num)', 'ChatController::getChannelMessages/$1');
    $routes->get('chat/conversation/(:num)', 'ChatController::getOrCreateConversation/$1');
    $routes->get('chat/channel-info/(:num)', 'ChatController::getChannelInfo/$1');
    $routes->post('chat/message/send', 'ChatController::sendMessage');
    $routes->post('chat/channel/send', 'ChatController::sendChannelMessage');
    $routes->post('chat/channel/create', 'ChatController::createChannel');
    $routes->post('chat/contact/add', 'ChatController::contactAdd');
    $routes->post('chat/message/read', 'ChatController::markAsRead');
    $routes->post('chat/attachment/upload', 'ChatController::uploadAttachment');
    $routes->get('chat/attachment/download/(:num)', 'ChatController::downloadAttachment/$1');
    
    // Additional Group Chat Routes
    $routes->post('chat/channel/join', 'ChatController::joinChannel');
    $routes->post('chat/channel/leave', 'ChatController::leaveChannel');
    $routes->post('chat/channel/addmember', 'ChatController::addMember');
    $routes->post('chat/channel/removemember', 'ChatController::removeMember');
    $routes->post('chat/channel/archive', 'ChatController::archiveChannel');
    $routes->post('chat/channel/unarchive', 'ChatController::unarchiveChannel');
    $routes->post('chat/channel/mute', 'ChatController::muteChannel');
    $routes->post('chat/channel/unmute', 'ChatController::unmuteChannel');
    $routes->post('chat/channel/delete', 'ChatController::deleteChannel');
    $routes->post('chat/channel/updateinfo', 'ChatController::updateChannelInfo');
    $routes->get('chat/channel/members/(:num)', 'ChatController::getChannelMembers/$1');
    
    // Integrations Routes
    $routes->get('integrations', 'IntegrationsController::index');
    $routes->get('integrations/kaarma', 'IntegrationsController::kaarma');
    $routes->get('integrations/google', 'IntegrationsController::google');
    $routes->get('integrations/whatsapp', 'IntegrationsController::whatsapp');
    $routes->get('integrations/facebook', 'IntegrationsController::facebook');
    $routes->get('integrations/instagram', 'IntegrationsController::instagram');
    $routes->get('integrations/activecampaign', 'IntegrationsController::activecampaign');
    $routes->get('integrations/mailchimp', 'IntegrationsController::mailchimp');
    $routes->post('integrations/save', 'IntegrationsController::save');
    $routes->get('integrations/test', 'IntegrationsController::test');
    
    // Test Routes
    $routes->get('test/datatables', 'TestController::datatables');
    $routes->get('test/demo', 'TestController::demo');
    $routes->get('test/images', 'TestController::images');
    $routes->get('test/debug', 'TestController::debug');
    $routes->get('test/sessionauth', 'TestController::sessionauth');
    $routes->get('test/todos', 'TestController::todos');
    $routes->get('test/performance', 'TestController::performance');
    $routes->get('test/websocket', 'TestController::websocket');
    
    // Debug Routes
    $routes->get('debug', 'DebugController::index');
    $routes->get('debug/sessionauth', 'DebugController::sessionauth');
    $routes->get('debug/routes', 'DebugController::routes');
    $routes->get('debug/database', 'DebugController::database');
    $routes->get('debug/logs', 'DebugController::logs');
    
    // Settings Routes
    $routes->get('settings', 'SettingsController::index');
    $routes->post('settings/save', 'SettingsController::save');
    $routes->post('settings/test-smtp', 'SettingsController::testSmtp');
    $routes->post('settings/send-test-email', 'SettingsController::sendTestEmail');
    $routes->post('settings/testLimaLinks', 'SettingsController::testLimaLinks');
    $routes->post('settings/generateQR', 'SettingsController::generateQR');
    $routes->get('settings/backup', 'SettingsController::backup');
    $routes->get('settings/restore', 'SettingsController::restore');
    $routes->get('settings/maintenance', 'SettingsController::maintenance');
    
    // Email Test Routes
    $routes->get('email-test', 'EmailTestController::index');
    $routes->post('email-test/send', 'EmailTestController::send');
});

// Service Order Notes Routes (for Service Orders)
$routes->group('service-order-notes', ['namespace' => 'Modules\ServiceOrders\Controllers', 'filter' => 'sessionauth'], function($routes) {
    // Get notes for a specific service order
    $routes->get('order/(:num)', 'ServiceOrderNotesController::getOrderNotes/$1');
    
    // CRUD operations
    $routes->post('create', 'ServiceOrderNotesController::create');
    $routes->post('update/(:num)', 'ServiceOrderNotesController::update/$1');
    $routes->delete('delete/(:num)', 'ServiceOrderNotesController::delete/$1');
    $routes->post('delete/(:num)', 'ServiceOrderNotesController::delete/$1'); // Support POST for delete
    
    // Reply operations
    $routes->post('add-reply', 'ServiceOrderNotesController::addReply');
    
    // File download
    $routes->get('download/(:any)', 'ServiceOrderNotesController::download/$1');
    
    // Staff users for mentions
    $routes->get('staff-users', 'ServiceOrderNotesController::getStaffUsers');
    
    // Mention management
    $routes->post('mark-mention-read/(:num)', 'ServiceOrderNotesController::markMentionRead/$1');
    $routes->get('unread-mentions', 'ServiceOrderNotesController::getUnreadMentions');
});

// Service Orders Routes (for Service Orders module)
$routes->group('service_orders', ['namespace' => 'Modules\ServiceOrders\Controllers', 'filter' => 'sessionauth'], function($routes) {
    // Main routes
    $routes->get('/', 'ServiceOrdersController::index');
    $routes->get('view/(:num)', 'ServiceOrdersController::view/$1');
    $routes->post('store', 'ServiceOrdersController::store');
    $routes->post('update/(:num)', 'ServiceOrdersController::update/$1');
    $routes->post('delete/(:num)', 'ServiceOrdersController::delete/$1');
    $routes->post('updateStatus/(:num)', 'ServiceOrdersController::updateStatus/$1');
    $routes->get('getActivity/(:num)', 'ServiceOrdersController::getActivity/$1');
    $routes->get('getNotesPreview/(:num)', 'ServiceOrdersController::getNotesPreview/$1');
    $routes->post('addFollower', 'ServiceOrdersController::addFollower');
    $routes->post('removeFollower', 'ServiceOrdersController::removeFollower');
    $routes->get('getFollowers/(:num)', 'ServiceOrdersController::getFollowers/$1');
    $routes->get('getAvailableFollowers/(:num)', 'ServiceOrdersController::getAvailableFollowers/$1');
    
    // Services
    $routes->get('getServicesForClient/(:num)', 'ServiceOrdersController::getServicesForClient/$1');
    $routes->get('getContactsForClient/(:num)', 'ServiceOrdersController::getContactsForClient/$1');
    
    // Comments
    $routes->get('getComments/(:num)', 'ServiceOrdersController::getComments/$1');
    $routes->get('getCommentsPreview/(:num)', 'ServiceOrdersController::getCommentsPreview/$1');
    $routes->post('addComment', 'ServiceOrdersController::addComment');
    $routes->post('addReply', 'ServiceOrdersController::addReply');
    $routes->post('deleteComment/(:num)', 'ServiceOrdersController::deleteComment/$1');
    $routes->post('updateComment/(:num)', 'ServiceOrdersController::updateComment/$1');
    
    // Modal and forms
    $routes->get('modal_form', 'ServiceOrdersController::modal_form');
    
    // Dashboard and content
    $routes->get('dashboard_content', 'ServiceOrdersController::dashboard_content');
    $routes->get('today_content', 'ServiceOrdersController::today_content');
    $routes->get('tomorrow_content', 'ServiceOrdersController::tomorrow_content');
    $routes->get('pending_content', 'ServiceOrdersController::pending_content');
    $routes->get('week_content', 'ServiceOrdersController::week_content');
    $routes->get('all_content', 'ServiceOrdersController::all_content');
    $routes->get('services_content', 'ServiceOrdersController::services_content');
    $routes->get('deleted_content', 'ServiceOrdersController::deleted_content');
    
    // AJAX endpoints
    $routes->get('get-today-orders', 'ServiceOrdersController::getTodayOrders');
    $routes->get('get-tomorrow-orders', 'ServiceOrdersController::getTomorrowOrders');
    $routes->get('get-pending-orders', 'ServiceOrdersController::getPendingOrders');
    $routes->get('get-week-orders', 'ServiceOrdersController::getWeekOrders');
    $routes->get('get-all-orders', 'ServiceOrdersController::getAllOrders');
    $routes->get('get-deleted-orders', 'ServiceOrdersController::getDeletedOrders');
    $routes->get('dashboard-data', 'ServiceOrdersController::getDashboardStats');
    $routes->get('get-dashboard-stats', 'ServiceOrdersController::getDashboardStats');
    $routes->get('getStaffUsers', 'ServiceOrdersController::getStaffUsers');
    $routes->post('regenerateQR/(:num)', 'ServiceOrdersController::regenerateQR/$1');
    $routes->post('sendEmail/(:num)', 'ServiceOrdersController::sendEmail/$1');
    $routes->post('sendSMS/(:num)', 'ServiceOrdersController::sendSMS/$1');
    $routes->post('sendAlert/(:num)', 'ServiceOrdersController::sendAlert/$1');
});

// Service Orders Services Routes
$routes->group('service_orders_services', ['namespace' => 'Modules\ServiceOrders\Controllers', 'filter' => 'sessionauth'], function($routes) {
    $routes->get('/', 'ServiceOrdersServicesController::index');
    $routes->post('list_data', 'ServiceOrdersServicesController::list_data');
    $routes->get('modal_form', 'ServiceOrdersServicesController::modal_form');
    $routes->post('store', 'ServiceOrdersServicesController::store');
    $routes->get('view/(:num)', 'ServiceOrdersServicesController::view/$1');
    $routes->get('get_data/(:num)', 'ServiceOrdersServicesController::get_data/$1');
    
    // Delete routes - support both GET and POST
    $routes->get('delete/(:num)', 'ServiceOrdersServicesController::delete/$1');
    $routes->post('delete/(:num)', 'ServiceOrdersServicesController::delete/$1');
    $routes->post('delete', 'ServiceOrdersServicesController::delete'); // For AJAX without ID in URL
    
    $routes->get('get_services_json', 'ServiceOrdersServicesController::get_services_json');
    $routes->post('toggle_show_in_orders/(:num)', 'ServiceOrdersServicesController::toggle_show_in_orders/$1');
    $routes->post('toggle_status/(:num)', 'ServiceOrdersServicesController::toggle_status/$1');
    $routes->get('get_services_by_client_json/(:num)', 'ServiceOrdersServicesController::get_services_by_client_json/$1');
});

// Global Notifications Routes (accessible from anywhere in the application)
$routes->group('notifications', function($routes) {
    $routes->get('/', 'NotificationController::index');
    $routes->post('mark-read/(:num)', 'NotificationController::markRead/$1');
    $routes->post('mark-all-read', 'NotificationController::markAllRead');
});

// Car Wash Module Routes
$routes->group('car_wash', ['namespace' => 'Modules\CarWash\Controllers', 'filter' => 'sessionauth'], function($routes) {
    // Main routes
    $routes->get('/', 'CarWashController::index');
    $routes->get('index', 'CarWashController::index');
    $routes->get('view/(:num)', 'CarWashController::view/$1');
    $routes->get('edit/(:num)', 'CarWashController::edit/$1');
    
    // AJAX routes
    $routes->post('store', 'CarWashController::store');
    $routes->post('update/(:num)', 'CarWashController::update/$1');
    $routes->post('delete', 'CarWashController::delete');
    $routes->post('restore', 'CarWashController::restore');
    $routes->post('permanent-delete', 'CarWashController::permanentDelete');
    
    // Status management
    $routes->post('update-status', 'CarWashController::updateStatus');
    $routes->post('updateStatus/(:num)', 'CarWashController::updateStatus/$1');
    
    // Data endpoints
    $routes->get('getTodayOrders', 'CarWashController::getTodayOrders');
    $routes->get('getTomorrowOrders', 'CarWashController::getTomorrowOrders');
    $routes->get('getPendingOrders', 'CarWashController::getPendingOrders');
    $routes->get('getAllActiveOrders', 'CarWashController::getAllActiveOrders');
    $routes->get('getAllOrders', 'CarWashController::getAllOrders');
    $routes->get('getDeletedOrders', 'CarWashController::getDeletedOrders');
    $routes->get('getDashboardStats', 'CarWashController::getDashboardStats');
    
    // Content routes
    $routes->get('dashboard_content', 'CarWashController::dashboard_content');
    $routes->get('today_content', 'CarWashController::today_content');
    $routes->get('all_orders_content', 'CarWashController::all_orders_content');
    $routes->get('tomorrow_content', 'CarWashController::tomorrow_content');
    $routes->get('pending_content', 'CarWashController::pending_content');
    $routes->get('services_content', 'CarWashController::services_content');
    $routes->get('deleted_content', 'CarWashController::deleted_content');
    
    // Modal routes
    $routes->get('modal_form', 'CarWashController::modal_form');
    $routes->get('modal_edit/(:num)', 'CarWashController::modal_edit/$1');
    $routes->get('modal_view/(:num)', 'CarWashController::modal_view/$1');
    
    // Settings routes
    $routes->get('settings', 'CarWashController::settings');
    $routes->post('settings', 'CarWashController::saveSettings');
    
    // Services management
    $routes->get('services', 'CarWashController::services');
    $routes->post('services/store', 'CarWashController::storeService');
    $routes->post('services/update/(:num)', 'CarWashController::updateService/$1');
    $routes->post('services/delete', 'CarWashController::deleteService');
    $routes->get('services/modal_form', 'CarWashController::serviceModalForm');
    $routes->get('services/modal_edit/(:num)', 'CarWashController::serviceModalEdit/$1');
    $routes->get('services/get_data/(:num)', 'CarWashController::getServiceData/$1');
    
    // QR Code routes
    $routes->get('qr/(:any)', 'CarWashController::qr/$1');
    $routes->get('qr-preview/(:num)', 'CarWashController::qrPreview/$1');
    
    // Export routes
    $routes->get('export/today', 'CarWashController::exportToday');
    $routes->get('export/tomorrow', 'CarWashController::exportTomorrow');
    $routes->get('export/pending', 'CarWashController::exportPending');
    $routes->get('export/all', 'CarWashController::exportAll');
    
    // File serving route - supports subdirectories like thumbnails
    $routes->get('file/(:num)/(:any)/(:any)', 'CarWashController::serveAttachment/$1/$2/$3');
});

// Load module routes via events
// The module routes are loaded through their respective Events.php files
// Each module has its own Events.php file that loads its routes dynamically


// Vehicles module routes
$routes->group('vehicles', ['namespace' => 'Modules\Vehicles\Controllers', 'filter' => 'sessionauth'], function($routes) {
    $routes->get('/', 'VehiclesController::index');
    $routes->get('view/(:num)', 'VehiclesController::view/$1');
    $routes->get('search', 'VehiclesController::search');
    
    // Data endpoints (put these before VIN route to avoid conflicts)
    $routes->post('data', 'VehiclesController::getVehiclesData');
    $routes->get('stats', 'VehiclesController::getVehicleStats');
    $routes->get('analytics-data', 'VehiclesController::getAnalyticsData');
    $routes->get('all-vehicles-data', 'VehiclesController::getAllVehiclesData');
    $routes->get('dashboard-data', 'VehiclesController::getDashboardData');
    $routes->get('recent-vehicles-data', 'VehiclesController::getRecentVehiclesData');
    $routes->get('active-vehicles-data', 'VehiclesController::getActiveVehiclesData');
    $routes->get('location-tracking-data', 'VehiclesController::getLocationTrackingData');
    
    // Filter options endpoints
    $routes->get('filter-options/clients', 'VehiclesController::getClientFilterOptions');
    $routes->get('filter-options/makes', 'VehiclesController::getMakeFilterOptions');
    $routes->get('filter-options/years', 'VehiclesController::getYearFilterOptions');
    
    // Action endpoints
    $routes->post('generate-nfc-token', 'VehiclesController::generateNfcToken');
    $routes->get('export-data/(:any)', 'VehiclesController::exportVehicleData/$1');
    $routes->get('export-all', 'VehiclesController::exportAllVehicles');
    
    // Photos URL endpoints
    $routes->get('photos-url/([A-Za-z0-9]{6})', 'VehiclesController::getPhotosUrl/$1');
    $routes->post('photos-url/([A-Za-z0-9]{6})', 'VehiclesController::savePhotosUrl/$1');
    

    
    // AWS S3 Integration
    $routes->get('test-s3', 'VehiclesController::testS3Config');
    $routes->get('s3-photos/(:any)', 'VehiclesController::getS3VehiclePhotos/$1');
    $routes->post('upload-s3-photos/(:any)', 'VehiclesController::uploadS3VehiclePhotos/$1');
    $routes->post('upload-s3', 'VehiclesController::uploadToS3');
    $routes->post('batch-upload-s3', 'VehiclesController::batchUploadS3');
    $routes->post('delete-s3-photo', 'VehiclesController::deleteS3Photo');
    $routes->post('delete-photos', 'VehiclesController::deletePhotos');
    
    // Duplicate photo detection
    $routes->get('detect-duplicates/(:any)', 'VehiclesController::detectDuplicatePhotos/$1');
    $routes->get('debug-duplicates/(:any)', 'VehiclesController::debugDuplicatePhotos/$1');
    $routes->post('remove-duplicates', 'VehiclesController::removeDuplicatePhotos');
    
    // Debug route for VIN testing
    $routes->get('debug/(:any)', 'VehiclesController::debugVin/$1');
    
    // VIN-based view using last 6 characters (put LAST to avoid conflicts)
    $routes->get('([A-Za-z0-9]{6})', 'VehiclesController::viewByVinLast6/$1');
});

// Vehicle Location Tracking Routes (NFC-based)
$routes->get('location/batch', 'VehicleLocationController::batchTracker'); // Batch vehicle scanner
$routes->get('location/(:any)', 'VehicleLocationController::track/$1'); // Mobile NFC interface
$routes->get('location-details/(:num)', 'VehicleLocationController::viewLocationDetails/$1', ['filter' => 'sessionauth']); // Location details view
$routes->group('api/location', function($routes) {
    $routes->post('save', 'VehicleLocationController::saveLocation');
    $routes->get('history/(:any)', 'VehicleLocationController::getLocationHistory/$1');
    $routes->get('vehicle-history/(:any)', 'VehicleLocationController::getVehicleHistory/$1');
    $routes->get('generate/(:any)', 'VehicleLocationController::generateToken/$1');
    $routes->get('details/(:num)', 'VehicleLocationController::getLocationDetails/$1');
    $routes->get('token-info/(:any)', 'VehicleLocationController::getTokenInfo/$1');
    $routes->post('send-email-report', 'VehicleLocationController::sendEmailReport');
    $routes->get('test-email-config', 'VehicleLocationController::testEmailConfig');
    $routes->get('test-csv-generation', 'VehicleLocationController::testCSVGeneration');
    $routes->get('test-email-csv', 'VehicleLocationController::testEmailWithCSV');
});

// Load module routes via events
// The module routes are loaded through their respective Events.php files
// Each module has its own Events.php file that loads its routes dynamically
