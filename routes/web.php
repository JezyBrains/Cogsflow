<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [\App\Http\Controllers\LoginController::class, 'authenticate']);
Route::post('/logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');


Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);

// Phase 1: Security Grid
Route::middleware('auth')->prefix('security')->name('security.')->group(function () {
    Route::get('/users', [\App\Http\Controllers\SecurityController::class, 'users'])->name('users');
    Route::post('/users', [\App\Http\Controllers\SecurityController::class, 'storeUser'])->name('users.store');
    Route::get('/audit', [\App\Http\Controllers\SecurityController::class, 'audit'])->name('audit');
});

// Phase 2: Procurement Intelligent System
Route::middleware('auth')->prefix('procurement')->name('procurement.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProcurementController::class, 'index'])->name('index');
    Route::get('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'suppliers'])->name('suppliers');
    Route::get('/suppliers/{id}', [\App\Http\Controllers\ProcurementController::class, 'showSupplier'])->name('suppliers.show');
    Route::post('/suppliers', [\App\Http\Controllers\ProcurementController::class, 'storeSupplier'])->name('suppliers.store');
    Route::post('/orders', [\App\Http\Controllers\ProcurementController::class, 'storePO'])->name('orders.store');
});

// Phase 3: Logistics & Inventory Terminal
Route::middleware('auth')->prefix('logistics')->name('logistics.')->group(function () {
    Route::get('/batches', [\App\Http\Controllers\LogisticsController::class, 'batches'])->name('batches');
    Route::get('/batches/create', [\App\Http\Controllers\LogisticsController::class, 'createBatch'])->name('batches.create');
    Route::post('/batches', [\App\Http\Controllers\LogisticsController::class, 'storeBatch'])->name('batches.store');
    Route::get('/batches/{id}', [\App\Http\Controllers\LogisticsController::class, 'showBatch'])->name('batches.show');
    Route::get('/dispatches', [\App\Http\Controllers\LogisticsController::class, 'dispatches'])->name('dispatches');
    Route::get('/dispatches/create', [\App\Http\Controllers\LogisticsController::class, 'createDispatch'])->name('dispatches.create');
    Route::post('/dispatches', [\App\Http\Controllers\LogisticsController::class, 'storeDispatch'])->name('dispatches.store');
    Route::post('/dispatches/{id}/deliver', [\App\Http\Controllers\LogisticsController::class, 'confirmDelivery'])->name('dispatches.deliver');

    // Physical Inspection Terminal
    Route::get('/dispatches/{id}/inspect', [\App\Http\Controllers\LogisticsController::class, 'showInspection'])->name('dispatches.inspect');
    Route::post('/dispatches/inspect/bag', [\App\Http\Controllers\LogisticsController::class, 'processBagInspection'])->name('dispatches.inspect.bag');
});

Route::middleware('auth')->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [\App\Http\Controllers\InventoryController::class, 'index'])->name('index');
    Route::get('/adjust', [\App\Http\Controllers\InventoryController::class, 'showAdjust'])->name('adjust.view');
    Route::post('/adjust', [\App\Http\Controllers\InventoryController::class, 'adjust'])->name('adjust');
});

Route::middleware('auth')->prefix('finance')->name('finance.')->group(function () {
    Route::get('/', [\App\Http\Controllers\FinanceController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\FinanceController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\FinanceController::class, 'store'])->name('store');
    Route::post('/approve/{id}', [\App\Http\Controllers\FinanceController::class, 'approve'])->name('approve');

    Route::get('/{id}/edit', [\App\Http\Controllers\FinanceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [\App\Http\Controllers\FinanceController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\FinanceController::class, 'destroy'])->name('destroy');

    // Finance Categories
    Route::resource('categories', \App\Http\Controllers\FinanceCategoryController::class)->names('categories');
});

// Phase 5: Global Intelligence
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReportsController::class, 'index'])->name('index');
    Route::get('/{slug}', [\App\Http\Controllers\ReportsController::class, 'show'])->name('show');
    Route::get('/{slug}/export', [\App\Http\Controllers\ReportsController::class, 'export'])->name('export');
});

// System & Support
Route::middleware('auth')->group(function () {
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/company', [\App\Http\Controllers\SettingsController::class, 'company'])->name('settings.company');
    Route::get('/settings/notifications', [\App\Http\Controllers\SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::post('/settings/update', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/logs', [\App\Http\Controllers\SettingsController::class, 'logs'])->name('settings.logs');
    Route::get('/support', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');

    // Global Attachments
    Route::post('/attachments', [\App\Http\Controllers\AttachmentController::class, 'store'])->name('attachments.store');
    Route::delete('/attachments/{id}', [\App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');
});
