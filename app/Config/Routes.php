<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirect to dashboard
// Authentication routes
$routes->get('login', 'AuthController::login');
$routes->post('auth/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

$routes->get('/', 'DashboardController::index');

// Debug routes
$routes->get('test', 'TestController::index');
$routes->get('test/settings', 'TestController::settings');

// Dashboard Module
$routes->get('dashboard', 'DashboardController::index');

// Batches Module
$routes->group('batches', ['filter' => 'role:admin,warehouse_staff,batches.view'], function ($routes) {
    $routes->get('/', 'BatchController::index');
    $routes->get('new', 'BatchController::new', ['filter' => 'role:admin,warehouse_staff,batches.create']);
    $routes->post('create', 'BatchController::create', ['filter' => 'role:admin,warehouse_staff,batches.create']);
    $routes->get('view/(:num)', 'BatchController::view/$1');
    $routes->get('edit/(:num)', 'BatchController::edit/$1', ['filter' => 'role:admin,warehouse_staff,batches.edit']);
    $routes->post('update/(:num)', 'BatchController::update/$1', ['filter' => 'role:admin,warehouse_staff,batches.edit']);
    $routes->post('delete/(:num)', 'BatchController::delete/$1', ['filter' => 'role:admin,batches.delete']);
    $routes->post('approve/(:num)', 'BatchController::approve/$1');
    $routes->post('reject/(:num)', 'BatchController::reject/$1');
});

// Dispatches Module
$routes->group('dispatches', ['filter' => 'role:admin,warehouse_staff,dispatches.view'], function ($routes) {
    $routes->get('/', 'DispatchController::index');
    $routes->get('new', 'DispatchController::new', ['filter' => 'role:admin,warehouse_staff,dispatches.create']);
    $routes->post('create', 'DispatchController::create', ['filter' => 'role:admin,warehouse_staff,dispatches.create']);
    $routes->get('view/(:num)', 'DispatchController::view/$1');
    $routes->post('updateStatus/(:num)', 'DispatchController::updateStatus/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->get('receive/(:num)', 'DispatchController::showReceiveForm/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->post('receive', 'DispatchController::receive', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
});

// Purchase Orders Module
$routes->group('purchase-orders', function ($routes) {
    $routes->get('/', 'PurchaseOrderController::index');
    $routes->get('new', 'PurchaseOrderController::new');
    $routes->post('create', 'PurchaseOrderController::create');
});

// Inventory Module
$routes->group('inventory', function ($routes) {
    $routes->get('/', 'InventoryController::index');
    $routes->get('adjust', 'InventoryController::showAdjustForm');
    $routes->post('adjust', 'InventoryController::adjust');
});

// Expenses Module
$routes->group('expenses', function ($routes) {
    $routes->get('/', 'ExpenseController::index');
    $routes->get('new', 'ExpenseController::new');
    $routes->post('log', 'ExpenseController::log');
});

// Settings Module
$routes->group('settings', ['filter' => 'role:admin,settings.view'], function ($routes) {
    $routes->get('/', 'SettingsController::index');
    $routes->post('update', 'SettingsController::update', ['filter' => 'role:admin,settings.edit']);
    $routes->post('admin-utility', 'SettingsController::adminUtility', ['filter' => 'role:admin,settings.admin']);
    $routes->get('system-info', 'SettingsController::systemInfo', ['filter' => 'role:admin,settings.view']);
    $routes->get('logs', 'SettingsController::logs', ['filter' => 'role:admin,settings.view']);
    $routes->get('export', 'SettingsController::exportSettings', ['filter' => 'role:admin,settings.view']);
    $routes->post('import', 'SettingsController::importSettings', ['filter' => 'role:admin,settings.edit']);
});

// Reports Module
$routes->group('reports', function ($routes) {
    $routes->get('/', 'ReportsController::index');
    $routes->get('batches', 'ReportsController::batches');
    $routes->get('inventory', 'ReportsController::inventory');
    $routes->get('financial', 'ReportsController::financial');
    $routes->get('export/(:alpha)', 'ReportsController::export/$1');
});

// Role Management Module
$routes->group('roles', ['filter' => 'role:admin,roles.view'], function ($routes) {
    $routes->get('/', 'RoleController::index');
    $routes->get('get-roles-data', 'RoleController::getRolesData');
    $routes->post('create', 'RoleController::create', ['filter' => 'role:admin,roles.create']);
    $routes->get('get-role/(:num)', 'RoleController::getRole/$1');
    $routes->post('update/(:num)', 'RoleController::update/$1', ['filter' => 'role:admin,roles.edit']);
    $routes->delete('delete/(:num)', 'RoleController::delete/$1', ['filter' => 'role:admin,roles.delete']);
    $routes->match(['get', 'post'], 'manage-permissions/(:num)', 'RoleController::managePermissions/$1', ['filter' => 'role:admin,roles.edit']);
    $routes->match(['get', 'post'], 'assign-users/(:num)', 'RoleController::assignUsers/$1', ['filter' => 'role:admin,roles.assign']);
    $routes->get('get-user-roles/(:num)', 'RoleController::getUserRoles/$1');
    $routes->post('update-user-roles/(:num)', 'RoleController::updateUserRoles/$1', ['filter' => 'role:admin,roles.assign']);
});

// Notification Routes
$routes->group('notifications', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'NotificationController::index');
    $routes->get('recent', 'NotificationController::getRecent');
    $routes->get('get', 'NotificationController::getNotifications');
    $routes->post('mark-read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('mark-read', 'NotificationController::markAsRead');
    $routes->delete('delete/(:num)', 'NotificationController::delete/$1');
    $routes->get('settings', 'NotificationController::settings');
    $routes->post('update-settings', 'NotificationController::updateSettings');
    $routes->post('create', 'NotificationController::create');
    $routes->post('create-bulk', 'NotificationController::createBulk');
    $routes->get('unread-count', 'NotificationController::getUnreadCount');
});

// Report Routes
$routes->group('report-system', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ReportController::index');
    $routes->get('view/(:segment)', 'ReportController::view/$1');
    $routes->get('generate/(:segment)', 'ReportController::generate/$1');
    $routes->get('export-pdf/(:segment)', 'ReportController::exportPdf/$1');
    $routes->get('export-excel/(:segment)', 'ReportController::exportExcel/$1');
    $routes->get('quick-stats', 'ReportController::quickStats');
    $routes->get('debug', 'ReportController::debug');
    $routes->get('export-all/(:segment)', 'ReportController::exportAll/$1');
});

// Set 404 override
$routes->set404Override(function() {
    return view('errors/custom_404');
});
