<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Login - GrainFlow</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .brand-logo {
            color: #696cff;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #696cff 0%, #5a67d8 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(105, 108, 255, 0.3);
        }
        
        .test-users {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .test-user {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .test-user:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-1px);
        }
        
        .test-user:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card login-card">
                    <div class="card-body p-4">
                        <!-- Brand -->
                        <div class="text-center mb-4">
                            <div class="brand-logo">
                                <i class="bx bxs-leaf"></i>
                                <span class="fw-bold">Grain<span style="color: #696cff;">Flow</span></span>
                            </div>
                            <h4 class="mb-2">Welcome Back! 👋</h4>
                            <p class="mb-4 text-muted">Please sign in to your account</p>
                        </div>

                        <!-- Alerts -->
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <i class="bx bx-error-circle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <i class="bx bx-check-circle me-2"></i>
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form action="<?= site_url('auth/authenticate') ?>" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Enter your username" value="<?= old('username') ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-lock-alt"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter your password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bx bx-log-in me-2"></i>Sign In
                                </button>
                            </div>
                        </form>

                        <!-- Test Users -->
                        <div class="test-users">
                            <h6 class="text-center mb-3 text-white">
                                <i class="bx bx-test-tube me-2"></i>Test Users
                            </h6>
                            
                            <div class="test-user" onclick="fillLogin('admin', 'admin123')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Administrator</strong>
                                        <small class="d-block text-muted">Full system access</small>
                                    </div>
                                    <span class="badge bg-danger">Admin</span>
                                </div>
                            </div>
                            
                            <div class="test-user" onclick="fillLogin('warehouse', 'warehouse123')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Warehouse Staff</strong>
                                        <small class="d-block text-muted">Inventory & operations</small>
                                    </div>
                                    <span class="badge bg-warning">Staff</span>
                                </div>
                            </div>
                            
                            <div class="test-user" onclick="fillLogin('user', 'user123')">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Standard User</strong>
                                        <small class="d-block text-muted">Read-only access</small>
                                    </div>
                                    <span class="badge bg-info">User</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function fillLogin(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
