<!DOCTYPE html>
<html>
<head>
    <title>CSS Test - Bag Inspection</title>
    <link rel="stylesheet" href="assets/css/bag-inspection.css?v=6.0">
</head>
<body style="padding: 40px; font-family: Arial;">
    <h1>CSS Test Page</h1>
    
    <h2>If you see this styled correctly, CSS is loading:</h2>
    
    <div class="bag-grid" style="margin: 20px 0;">
        <div class="bag-card status-pending">
            <div class="bag-icon"><i class="bx bxs-shopping-bag"></i></div>
            <div class="bag-num">01</div>
        </div>
        <div class="bag-card status-ok">
            <div class="bag-icon"><i class="bx bxs-shopping-bag"></i></div>
            <div class="bag-num">02</div>
            <div class="bag-wt">45.2kg</div>
        </div>
        <div class="bag-card status-warning">
            <div class="bag-icon"><i class="bx bxs-shopping-bag"></i></div>
            <div class="bag-num">03</div>
            <div class="bag-wt">48.5kg</div>
        </div>
        <div class="bag-card status-damaged">
            <div class="bag-icon"><i class="bx bxs-shopping-bag"></i></div>
            <div class="bag-num">04</div>
            <div class="bag-wt">42.0kg</div>
        </div>
    </div>
    
    <h3>Expected Result:</h3>
    <ul>
        <li>Grid layout with 60x60px squares</li>
        <li>White background on grid</li>
        <li>Bag #1: White/gray (pending)</li>
        <li>Bag #2: Green (ok)</li>
        <li>Bag #3: Yellow (warning)</li>
        <li>Bag #4: Red (damaged)</li>
    </ul>
    
    <hr>
    
    <h3>CSS File Check:</h3>
    <p>CSS file path: <code>assets/css/bag-inspection.css?v=6.0</code></p>
    <p>File exists: <?php echo file_exists(__DIR__ . '/assets/css/bag-inspection.css') ? '✅ YES' : '❌ NO'; ?></p>
    <p>File size: <?php echo file_exists(__DIR__ . '/assets/css/bag-inspection.css') ? filesize(__DIR__ . '/assets/css/bag-inspection.css') . ' bytes' : 'N/A'; ?></p>
    <p>Last modified: <?php echo file_exists(__DIR__ . '/assets/css/bag-inspection.css') ? date('Y-m-d H:i:s', filemtime(__DIR__ . '/assets/css/bag-inspection.css')) : 'N/A'; ?></p>
    
    <hr>
    
    <h3>CSS Content Preview (first 500 chars):</h3>
    <pre style="background: #f5f5f5; padding: 15px; overflow-x: auto;"><?php 
        if (file_exists(__DIR__ . '/assets/css/bag-inspection.css')) {
            echo htmlspecialchars(substr(file_get_contents(__DIR__ . '/assets/css/bag-inspection.css'), 0, 500));
        } else {
            echo 'File not found';
        }
    ?></pre>
    
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</body>
</html>
