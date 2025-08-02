<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirect to dashboard
$routes->get('/', 'DashboardController::index');

// Dashboard Module
$routes->get('dashboard', 'DashboardController::index');

// Batches Module
$routes->group('batches', function ($routes) {
    $routes->get('/', 'BatchController::index');
    $routes->get('new', 'BatchController::new');
    $routes->post('create', 'BatchController::create');
    $routes->get('view/(:num)', 'BatchController::view/$1');
    $routes->post('approve/(:num)', 'BatchController::approve/$1');
    $routes->post('reject/(:num)', 'BatchController::reject/$1');
});

// Dispatches Module
$routes->group('dispatches', function ($routes) {
    $routes->get('/', 'DispatchController::index');
    $routes->get('new', 'DispatchController::new');
    $routes->post('create', 'DispatchController::create');
    $routes->get('receive/(:num)', 'DispatchController::showReceiveForm/$1');
    $routes->post('receive', 'DispatchController::receive');
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
$routes->group('settings', function ($routes) {
    $routes->get('/', 'SettingsController::index');
    $routes->post('update', 'SettingsController::update');
});

// Reports Module
$routes->group('reports', function ($routes) {
    $routes->get('/', 'ReportsController::index');
    $routes->get('batches', 'ReportsController::batches');
    $routes->get('inventory', 'ReportsController::inventory');
    $routes->get('financial', 'ReportsController::financial');
    $routes->get('export/(:alpha)', 'ReportsController::export/$1');
});

// Set 404 override
$routes->set404Override(function() {
    return view('errors/custom_404');
});
