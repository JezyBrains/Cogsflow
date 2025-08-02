<?php
$assets = config('Assets');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?> | Grain Management System</title>
    
    <!-- CSS Assets -->
    <link rel="stylesheet" href="<?= $assets->getAssetUrl($assets->css['fontawesome']) ?>">
    <link rel="stylesheet" href="<?= $assets->getAssetUrl($assets->css['adminlte']) ?>">
    <link rel="stylesheet" href="<?= $assets->getAssetUrl($assets->css['custom']) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico') ?>">
    
    <!-- Meta Tags -->
    <meta name="description" content="Professional Grain Management System">
    <meta name="author" content="Grain Management Co.">
    
    <?= $this->renderSection('head') ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <i class="fas fa-seedling fa-3x text-primary pulse"></i>
            <p class="mt-2">Loading...</p>
        </div>
        
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= site_url('dashboard') ?>" class="nav-link">Home</a>
                </li>
            </ul>
            
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">3 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-box mr-2"></i> New batch received
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-truck mr-2"></i> Dispatch completed
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Low stock alert
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                
                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-header">
                            <strong>Admin User</strong><br>
                            <small>admin@grainmanagement.com</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="<?= site_url('settings') ?>" class="dropdown-item">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= site_url('dashboard') ?>" class="brand-link">
                <i class="fas fa-seedling brand-image ml-3 mr-2"></i>
                <span class="brand-text font-weight-light">Grain Management</span>
            </a>
            
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fas fa-user-circle fa-2x text-white"></i>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Admin User</a>
                    </div>
                </div>
                
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= site_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' || uri_string() == '' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= site_url('batches') ?>" class="nav-link <?= strpos(uri_string(), 'batches') === 0 ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>
                                    Batch Management
                                    <span class="badge badge-info right">New</span>
                                </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= site_url('dispatches') ?>" class="nav-link <?= strpos(uri_string(), 'dispatches') === 0 ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-truck"></i>
                                <p>Dispatch Management</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= site_url('purchase-orders') ?>" class="nav-link <?= strpos(uri_string(), 'purchase-orders') === 0 ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Purchase Orders</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= site_url('inventory') ?>" class="nav-link <?= strpos(uri_string(), 'inventory') === 0 ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    Inventory
                                    <span class="badge badge-success right">Live</span>
                                </p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="<?= site_url('expenses') ?>" class="nav-link <?= strpos(uri_string(), 'expenses') === 0 ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-money-bill-wave"></i>
                                <p>Expense Tracking</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">SYSTEM</li>
                        
                        <li class="nav-item">
                            <a href="<?= site_url('settings') ?>" class="nav-link <?= strpos(uri_string(), 'settings') === 0 ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-question-circle"></i>
                                <p>Help & Support</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $this->renderSection('title') ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Home</a></li>
                                <?= $this->renderSection('breadcrumb') ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Notifications Container -->
                    <div id="notifications-container">
                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(session()->getFlashdata('warning')): ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('warning') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(session()->getFlashdata('info')): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-info-circle"></i> <?= session()->getFlashdata('info') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Page Content -->
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>
        
        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?= date('Y') ?> <a href="#">Grain Management System</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    
    <!-- JavaScript Assets -->
    <script src="<?= $assets->getAssetUrl($assets->js['jquery']) ?>"></script>
    <script src="<?= $assets->getAssetUrl($assets->js['bootstrap']) ?>"></script>
    <script src="<?= $assets->getAssetUrl($assets->js['adminlte']) ?>"></script>
    <script src="<?= $assets->getAssetUrl($assets->js['custom']) ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
