<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Grain Management System</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 500px;
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .btn-home {
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h2>Page Not Found</h2>
        <p class="lead">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
        <a href="<?= site_url() ?>" class="btn btn-primary btn-home">Go to Dashboard</a>
    </div>
</body>
</html>
