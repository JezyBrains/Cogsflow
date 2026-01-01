<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Landing Page Routes (no authentication required)
$routes->get('/', 'HomeController::index');
$routes->get('about', 'HomeController::about');
$routes->get('services', 'HomeController::services');
$routes->get('products', 'HomeController::products');
$routes->get('features', 'HomeController::features');
$routes->get('contact', 'HomeController::contact');
$routes->post('contact/submit', 'HomeController::submitContact');

// Authentication routes
$routes->get('login', 'AuthController::login');
$routes->post('auth/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Debug routes
$routes->get('test', 'TestController::index');
$routes->get('test/settings', 'TestController::settings');

// Dashboard Module (temporarily remove auth filter for testing)
$routes->get('dashboard', 'DashboardController::index');

// Batches Module (temporarily remove role filter for testing)
$routes->group('batches', function ($routes) {
    $routes->get('/', 'BatchController::index');
    $routes->get('new', 'BatchController::new');
    $routes->post('create', 'BatchController::create');
    $routes->get('view/(:num)', 'BatchController::view/$1');
    $routes->get('edit/(:num)', 'BatchController::edit/$1');
    $routes->post('update/(:num)', 'BatchController::update/$1');
    $routes->post('delete/(:num)', 'BatchController::delete/$1');
    $routes->post('approve/(:num)', 'BatchController::approve/$1');
    $routes->post('reject/(:num)', 'BatchController::reject/$1');
    $routes->get('po-details/(:num)', 'BatchController::getPODetails/$1');
    $routes->get('receiving', 'BatchReceivingController::index');
    $routes->get('receiving/inspection/(:num)', 'BatchReceivingController::inspectionForm/$1');
    $routes->post('receiving/process-inspection', 'BatchReceivingController::processInspection');
});

// Dispatches Module
$routes->group('dispatches', ['filter' => 'role:admin,warehouse_staff,dispatches.view'], function ($routes) {
    $routes->get('/', 'DispatchController::index');
    $routes->get('new', 'DispatchController::new', ['filter' => 'role:admin,warehouse_staff,dispatches.create']);
    $routes->post('create', 'DispatchController::create', ['filter' => 'role:admin,warehouse_staff,dispatches.create']);
    $routes->get('view/(:num)', 'DispatchController::view/$1');
    $routes->get('edit/(:num)', 'DispatchController::edit/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->post('update/(:num)', 'DispatchController::update/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->post('updateStatus/(:num)', 'DispatchController::updateStatus/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->get('receive/(:num)', 'DispatchController::showReceiveForm/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->post('receive', 'DispatchController::receive', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->post('mark-arrived/(:num)', 'DispatchController::markAsArrived/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->get('inspection/(:num)', 'DispatchController::inspectionForm/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
    $routes->post('perform-inspection/(:num)', 'DispatchController::performInspection/$1', ['filter' => 'role:admin,warehouse_staff,dispatches.edit']);
});

// Purchase Orders Module
$routes->group('purchase-orders', function ($routes) {
    $routes->get('/', 'PurchaseOrderController::index');
    $routes->get('new', 'PurchaseOrderController::new');
    $routes->post('create', 'PurchaseOrderController::create');
    $routes->get('search', 'PurchaseOrderController::search');
    $routes->get('testSearch', 'PurchaseOrderController::testSearch');
    $routes->get('getBatches/(:num)', 'PurchaseOrderController::getBatches/$1');
    $routes->get('(:num)', 'PurchaseOrderController::show/$1');
    $routes->get('(:num)/edit', 'PurchaseOrderController::edit/$1');
    $routes->put('(:num)', 'PurchaseOrderController::update/$1');
    $routes->delete('(:num)', 'PurchaseOrderController::delete/$1');
    $routes->post('approve/(:num)', 'PurchaseOrderController::approve/$1');
    $routes->post('reject/(:num)', 'PurchaseOrderController::reject/$1');
    $routes->get('fulfillment-progress/(:num)', 'PurchaseOrderController::getFulfillmentProgress/$1');
    $routes->get('fulfillment-history/(:num)', 'PurchaseOrderController::getFulfillmentHistory/$1');
    $routes->get('discrepancy-analysis/(:num)', 'PurchaseOrderController::getDiscrepancyAnalysis/$1');
    $routes->get('completion-timeline/(:num)', 'PurchaseOrderController::getCompletionTimeline/$1');
    $routes->get('batch-recommendations/(:num)', 'PurchaseOrderController::getBatchRecommendations/$1');
});

// Inventory Module
$routes->group('inventory', ['filter' => 'role:admin,warehouse_staff,inventory.view'], function ($routes) {
    $routes->get('/', 'InventoryController::index');
    $routes->get('adjust', 'InventoryController::showAdjustForm', ['filter' => 'role:admin,warehouse_staff,inventory.edit']);
    $routes->post('adjust', 'InventoryController::adjust', ['filter' => 'role:admin,warehouse_staff,inventory.edit']);
});

// Suppliers Module
$routes->group('suppliers', ['filter' => 'role:admin,warehouse_staff,suppliers.view'], function ($routes) {
    $routes->get('/', 'SupplierController::index');
    $routes->get('new', 'SupplierController::new', ['filter' => 'role:admin,warehouse_staff,suppliers.create']);
    $routes->post('create', 'SupplierController::create', ['filter' => 'role:admin,warehouse_staff,suppliers.create']);
    $routes->get('(:num)', 'SupplierController::show/$1');
    $routes->get('(:num)/edit', 'SupplierController::edit/$1', ['filter' => 'role:admin,warehouse_staff,suppliers.edit']);
    $routes->put('(:num)/update', 'SupplierController::update/$1', ['filter' => 'role:admin,warehouse_staff,suppliers.edit']);
    $routes->delete('(:num)/archive', 'SupplierController::archive/$1', ['filter' => 'role:admin,suppliers.delete']);
    $routes->patch('(:num)/restore', 'SupplierController::restore/$1', ['filter' => 'role:admin,suppliers.delete']);
    $routes->get('search', 'SupplierController::search', ['filter' => 'auth']);
    $routes->get('export', 'SupplierController::export', ['filter' => 'role:admin,warehouse_staff,suppliers.export']);
    $routes->get('(:num)/statistics', 'SupplierController::statistics/$1');
    $routes->get('(:num)/activity', 'SupplierController::activity/$1');
});

// Suppliers (AJAX)
$routes->post('suppliers/create-ajax', 'SupplierController::createAjax', ['filter' => 'auth']);

// Expenses Module
$routes->group('expenses', ['filter' => 'auth'], function ($routes) {
    // Main expense routes
    $routes->get('/', 'ExpenseController::index');
    $routes->get('new', 'ExpenseController::new');
    $routes->post('store', 'ExpenseController::store');
    $routes->get('show/(:num)', 'ExpenseController::show/$1');
    $routes->get('edit/(:num)', 'ExpenseController::edit/$1');
    $routes->post('update/(:num)', 'ExpenseController::update/$1');
    $routes->post('delete/(:num)', 'ExpenseController::delete/$1');
    
    // Approval routes (admin only)
    $routes->post('approve/(:num)', 'ExpenseController::approve/$1', ['filter' => 'role:admin']);
    $routes->post('reject/(:num)', 'ExpenseController::reject/$1', ['filter' => 'role:admin']);
    
    // Analytics and export
    $routes->get('analytics', 'ExpenseController::analytics');
    $routes->get('export', 'ExpenseController::export');
    
    // Category management
    $routes->get('categories', 'ExpenseCategoryController::index');
    $routes->get('categories/create', 'ExpenseCategoryController::create', ['filter' => 'role:admin']);
    $routes->post('categories/store', 'ExpenseCategoryController::store', ['filter' => 'role:admin']);
    $routes->get('categories/edit/(:num)', 'ExpenseCategoryController::edit/$1', ['filter' => 'role:admin']);
    $routes->post('categories/update/(:num)', 'ExpenseCategoryController::update/$1', ['filter' => 'role:admin']);
    $routes->post('categories/toggle/(:num)', 'ExpenseCategoryController::toggleStatus/$1', ['filter' => 'role:admin']);
    $routes->post('categories/delete/(:num)', 'ExpenseCategoryController::delete/$1', ['filter' => 'role:admin']);
    $routes->get('categories/active', 'ExpenseCategoryController::getActive'); // AJAX
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

// Workflow Dashboard Routes
$routes->group('workflow', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'WorkflowDashboardController::index');
    $routes->get('dashboard', 'WorkflowDashboardController::index');
    $routes->get('analytics', 'WorkflowDashboardController::getAnalytics');
    $routes->get('export-report', 'WorkflowDashboardController::exportReport');
});

// Batch Receiving Routes
$routes->group('batch-receiving', ['filter' => 'role:admin,warehouse_staff'], function($routes) {
    $routes->get('/', 'BatchReceivingController::index');
    $routes->get('test/(:num)', 'BatchReceivingController::testInspection/$1');
    $routes->get('inspection/(:num)', 'BatchReceivingController::inspectionForm/$1');
    $routes->post('process-inspection', 'BatchReceivingController::processInspection');
    
    // New bag-by-bag inspection API endpoints
    $routes->post('get-bag-details', 'BatchReceivingController::getBagDetails');
    $routes->post('process-bag-inspection', 'BatchReceivingController::processBagInspection');
    $routes->post('complete-inspection', 'BatchReceivingController::completeBagInspection');
    $routes->get('get-inspections', 'BatchReceivingController::getInspections');
    $routes->get('print-labels/(:num)', 'BatchReceivingController::printLabels/$1');
    $routes->get('print-labels-from-batch/(:num)', 'BatchReceivingController::printLabelsFromBatch/$1');
    $routes->get('debug-labels/(:num)', 'BatchReceivingController::debugLabels/$1');
    $routes->get('qr-code/(:segment)', 'BatchReceivingController::generateQRCode/$1');
    
    // Phase 1 API endpoints - Visual Grid & Real-time Progress
    $routes->get('api/bag-inspection-data', 'BatchReceivingController::getBagInspectionData');
    $routes->post('api/record-bag-inspection', 'BatchReceivingController::recordBagInspection');
    
    $routes->get('batch-history/(:num)', 'BatchReceivingController::batchHistory/$1');
    $routes->get('export-report/(:num)', 'BatchReceivingController::exportInspectionReport/$1');
});

// Document Management Routes
$routes->group('documents', ['filter' => 'auth'], function($routes) {
    $routes->post('upload', 'DocumentController::upload');
    $routes->get('(:segment)/(:num)', 'DocumentController::getDocuments/$1/$2');
    $routes->get('required/(:segment)/(:segment)/(:num)', 'DocumentController::getRequiredDocuments/$1/$2/$3');
    $routes->get('check/(:segment)/(:segment)/(:num)', 'DocumentController::checkRequiredDocuments/$1/$2/$3');
    $routes->delete('delete/(:num)', 'DocumentController::delete/$1');
    $routes->get('download/(:num)', 'DocumentController::download/$1');
    $routes->post('update-status/(:num)', 'DocumentController::updateStatus/$1');
    $routes->get('types/(:segment)', 'DocumentController::getDocumentTypes/$1');
    $routes->get('widget/(:segment)/(:segment)/(:num)', 'DocumentController::renderUploadWidget/$1/$2/$3');
});

// Set 404 override
$routes->set404Override(function() {
    return view('errors/custom_404');
});
