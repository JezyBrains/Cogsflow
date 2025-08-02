<?php
// Load assets helper
helper('assets');
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="<?= base_url('assets/') ?>" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title><?= $this->renderSection('title') ?> | Grain Management System</title>
    
    <meta name="description" content="Modern Grain Management System with Professional UI">
    <meta name="keywords" content="grain, management, inventory, dashboard">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= css_asset('custom') ?>">
    
    <?= $this->renderSection('head') ?>
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <!-- App Brand -->
                <div class="app-brand demo">
                    <a href="<?= site_url('dashboard') ?>" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <i class="bx bxs-leaf" style="font-size: 28px; color: #696cff;"></i>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">Grain<span style="color: #696cff;">Flow</span></span>
                    </a>
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>
                
                <div class="menu-inner-shadow"></div>
                
                <!-- Menu Items -->
                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item <?= uri_string() == 'dashboard' || uri_string() == '' ? 'active' : '' ?>">
                        <a href="<?= site_url('dashboard') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    
                    <!-- Inventory Management -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Inventory</span>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'batches') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('batches') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-package"></i>
                            <div data-i18n="Batch Management">Batch Management</div>
                            <div class="badge badge-center rounded-pill bg-danger w-px-20 h-px-20 ms-auto">
                                <span class="badge-content">5</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'inventory') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('inventory') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-store"></i>
                            <div data-i18n="Inventory">Inventory</div>
                            <div class="badge badge-center rounded-pill bg-success w-px-20 h-px-20 ms-auto">
                                <i class="bx bx-check bx-xs"></i>
                            </div>
                        </a>
                    </li>
                    
                    <!-- Operations -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Operations</span>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'dispatches') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('dispatches') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-car"></i>
                            <div data-i18n="Dispatches">Dispatch Management</div>
                        </a>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'purchase-orders') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('purchase-orders') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-receipt"></i>
                            <div data-i18n="Purchase Orders">Purchase Orders</div>
                        </a>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'expenses') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('expenses') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-wallet"></i>
                            <div data-i18n="Expenses">Expense Tracking</div>
                        </a>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'reports') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('reports') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                            <div data-i18n="Reports">Reports & Analytics</div>
                        </a>
                    </li>
                    
                    <!-- System -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">System</span>
                    </li>
                    
                    <li class="menu-item <?= strpos(uri_string(), 'settings') === 0 ? 'active' : '' ?>">
                        <a href="<?= site_url('settings') ?>" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Settings">Settings</div>
                        </a>
                    </li>
                    
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-support"></i>
                            <div data-i18n="Support">Help & Support</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->
            
            <!-- Layout container -->
            <div class="layout-page">
                
                <!-- Navbar -->
                <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="container-fluid">
                        <!-- Mobile menu toggle -->
                        <div class="layout-menu-toggle d-xl-none">
                            <a class="nav-link px-0" href="javascript:void(0)" onclick="toggleMobileMenu()">
                                <i class="bx bx-menu bx-md"></i>
                            </a>
                        </div>
                        
                        <!-- Navbar content -->
                        <div class="navbar-collapse d-flex justify-content-between align-items-center flex-grow-1" id="navbar-collapse">
                            
                            <!-- Left side - Search -->
                            <div class="navbar-nav-left d-flex align-items-center">
                                <div class="nav-item navbar-search-wrapper">
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text border-0 bg-transparent">
                                            <i class="bx bx-search fs-5"></i>
                                        </span>
                                        <input type="text" class="form-control border-0 shadow-none navbar-search-input" placeholder="Search..." aria-label="Search...">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right side - Actions -->
                            <div class="navbar-nav-right d-flex align-items-center">
                                <ul class="navbar-nav flex-row align-items-center ms-auto">
                            
                            <!-- Quick Actions -->
                            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="bx bx-grid-alt fs-4 lh-0"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Quick Actions</h5>
                                        </div>
                                    </div>
                                    <div class="dropdown-shortcuts-list scrollable-container">
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="bx bx-package fs-4"></i>
                                                </span>
                                                <a href="<?= site_url('batches/new') ?>" class="stretched-link">New Batch</a>
                                                <small class="text-muted mb-0">Add batch</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="bx bx-car fs-4"></i>
                                                </span>
                                                <a href="<?= site_url('dispatches/new') ?>" class="stretched-link">New Dispatch</a>
                                                <small class="text-muted mb-0">Create dispatch</small>
                                            </div>
                                        </div>
                                        <div class="row row-bordered overflow-visible g-0">
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="bx bx-receipt fs-4"></i>
                                                </span>
                                                <a href="<?= site_url('purchase-orders/new') ?>" class="stretched-link">Purchase Order</a>
                                                <small class="text-muted mb-0">New PO</small>
                                            </div>
                                            <div class="dropdown-shortcuts-item col">
                                                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                    <i class="bx bx-wallet fs-4"></i>
                                                </span>
                                                <a href="<?= site_url('expenses/new') ?>" class="stretched-link">Log Expense</a>
                                                <small class="text-muted mb-0">Track expense</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <!-- Notifications -->
                            <li class="nav-item navbar-dropdown dropdown-notifications dropdown me-3 me-xl-1">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="bx bx-bell bx-sm"></i>
                                    <span class="badge bg-danger rounded-pill badge-notifications">3</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Notifications</h5>
                                            <span class="badge rounded-pill bg-label-primary p-2 me-2">3 New</span>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-package"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">New batch received</h6>
                                                        <p class="mb-0">Batch #B001 has been received</p>
                                                        <small class="text-muted">3 minutes ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-car"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Dispatch completed</h6>
                                                        <p class="mb-0">Dispatch #D001 has been completed</p>
                                                        <small class="text-muted">12 hours ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar">
                                                            <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-error"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">Low stock alert</h6>
                                                        <p class="mb-0">Wheat stock is running low</p>
                                                        <small class="text-muted">2 days ago</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-menu-footer border-top">
                                        <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
                                            View all notifications
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?= base_url('assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiM2OTZjZmYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSI4IiB5PSI4Ij4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTIgMTJaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMTIgMTRDOC4xMzQwMSAxNCA1IDE3LjEzNDEgNSAyMUg3QzcgMTguMjM4NiA5LjIzODU4IDE2IDEyIDE2QzE0Ljc2MTQgMTYgMTcgMTguMjM4NiAxNyAyMUgxOUMxOSAxNy4xMzQxIDE1Ljg2NiAxNCAxMiAxNFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo8L3N2Zz4K'">
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?= base_url('assets/img/avatars/1.png') ?>" alt class="w-px-40 h-auto rounded-circle" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiM2OTZjZmYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSI4IiB5PSI4Ij4KPHBhdGggZD0iTTEyIDEyQzE0LjIwOTEgMTIgMTYgMTAuMjA5MSAxNiA4QzE2IDUuNzkwODYgMTQuMjA5MSA0IDEyIDRDOS43OTA4NiA0IDggNS43OTA4NiA4IDhDOCAxMC4yMDkxIDkuNzkwODYgMTIgMTIgMTJaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMTIgMTRDOC4xMzQwMSAxNCA1IDE3LjEzNDEgNSAyMUg3QzcgMTguMjM4NiA5LjIzODU4IDE2IDEyIDE2QzE0Ljc2MTQgMTYgMTcgMTguMjM4NiAxNyAyMUgxOUMxOSAxNy4xMzQxIDE1Ljg2NiAxNCAxMiAxNFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo8L3N2Zz4K'">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block">Admin User</span>
                                                    <small class="text-muted">Administrator</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= site_url('settings') ?>">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- / Navbar -->
                
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        
                        <!-- Page Header -->
                        <?php 
                        $title = $this->renderSection('title');
                        $breadcrumb = $this->renderSection('breadcrumb');
                        $page_actions = $this->renderSection('page_actions');
                        if (!empty($title) || !empty($breadcrumb) || !empty($page_actions)): 
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <?php if (!empty($title)): ?>
                                        <h4 class="mb-2"><?= $title ?></h4>
                                        <?php endif; ?>
                                        <?php if (!empty($breadcrumb)): ?>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb breadcrumb-style1">
                                                <li class="breadcrumb-item">
                                                    <a href="<?= site_url('dashboard') ?>">Home</a>
                                                </li>
                                                <?= $breadcrumb ?>
                                            </ol>
                                        </nav>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($page_actions)): ?>
                                    <div>
                                        <?= $page_actions ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Notifications Container -->
                        <div id="notifications-container">
                            <?php if(session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <i class="bx bx-check-circle me-2"></i>
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <i class="bx bx-error-circle me-2"></i>
                                    <?= session()->getFlashdata('error') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(session()->getFlashdata('warning')): ?>
                                <div class="alert alert-warning alert-dismissible" role="alert">
                                    <i class="bx bx-error me-2"></i>
                                    <?= session()->getFlashdata('warning') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <?php if(session()->getFlashdata('info')): ?>
                                <div class="alert alert-info alert-dismissible" role="alert">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <?= session()->getFlashdata('info') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Page Content -->
                        <?= $this->renderSection('content') ?>
                        
                    </div>
                    <!-- / Content -->
                    
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © <?= date('Y') ?> <a href="#" target="_blank" class="footer-link fw-medium">GrainFlow</a>. All rights reserved.
                            </div>
                            <div class="d-none d-lg-inline-block">
                                <a href="#" class="footer-link me-4">License</a>
                                <a href="#" class="footer-link me-4">Documentation</a>
                                <a href="#" class="footer-link">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                    
                </div>
                <!-- / Content wrapper -->
                
            </div>
            <!-- / Layout page -->
            
        </div>
        <!-- / Layout container -->
        
    </div>
    <!-- / Layout wrapper -->
    
    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= js_asset('custom') ?>"></script>
    
    <!-- Page specific scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
