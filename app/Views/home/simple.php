<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $system_name ?? 'GrainFlow Management System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #696cff 0%, #5a67d8 100%);
            color: white;
            padding: 100px 0;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #696cff, #5a67d8);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= site_url('/') ?>">
                <i class="bx bxs-leaf me-2" style="font-size: 28px; color: #696cff;"></i>
                <span>Grain<span style="color: #696cff;">Flow</span></span>
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="btn btn-primary-custom text-white" href="<?= site_url('login') ?>">
                    <i class="bx bx-log-in me-1"></i> Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">
                        Professional Grain Management System
                    </h1>
                    <p class="lead mb-4">
                        Streamline your grain operations with advanced tracking, inventory management, 
                        and comprehensive reporting. Built for modern agricultural businesses.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="<?= site_url('login') ?>" class="btn btn-light btn-lg">
                            <i class="bx bx-log-in me-2"></i> Access System
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="bx bx-info-circle me-2"></i> Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold mb-3">Powerful Features</h2>
                    <p class="lead text-muted">
                        Everything you need to manage your grain operations efficiently and effectively.
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bx bx-package" style="font-size: 3rem; color: #696cff;"></i>
                            </div>
                            <h4 class="mb-3">Batch Management</h4>
                            <p class="text-muted">
                                Comprehensive batch tracking with bag-level traceability and quality control.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bx bx-car" style="font-size: 3rem; color: #696cff;"></i>
                            </div>
                            <h4 class="mb-3">Dispatch Management</h4>
                            <p class="text-muted">
                                Efficient dispatch operations with real-time tracking and delivery confirmation.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bx bx-receipt" style="font-size: 3rem; color: #696cff;"></i>
                            </div>
                            <h4 class="mb-3">Purchase Orders</h4>
                            <p class="text-muted">
                                Streamlined procurement workflow with approval processes and fulfillment tracking.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-3">Get Started Today</h2>
                    <p class="lead text-muted mb-5">
                        Ready to transform your grain management operations? Access the system directly.
                    </p>
                    
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bx bx-envelope" style="font-size: 3rem; color: #696cff;"></i>
                                <h6 class="mt-3">Email</h6>
                                <p class="text-muted"><?= $company_email ?? 'info@grainflow.com' ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bx bx-phone" style="font-size: 3rem; color: #696cff;"></i>
                                <h6 class="mt-3">Phone</h6>
                                <p class="text-muted"><?= $company_phone ?? '+255 123 456 789' ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="bx bx-time" style="font-size: 3rem; color: #696cff;"></i>
                                <h6 class="mt-3">Support</h6>
                                <p class="text-muted">24/7 Available</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="<?= site_url('login') ?>" class="btn btn-primary-custom btn-lg text-white">
                            <i class="bx bx-log-in me-2"></i> Access System
                        </a>
                        <a href="mailto:<?= $company_email ?? 'info@grainflow.com' ?>" class="btn btn-outline-primary btn-lg">
                            <i class="bx bx-envelope me-2"></i> Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bx bxs-leaf me-2" style="font-size: 24px; color: #696cff;"></i>
                        <h5 class="mb-0">Grain<span style="color: #696cff;">Flow</span></h5>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        &copy; <?= date('Y') ?> <?= $company_name ?? 'GrainFlow' ?>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
