<?php
/**
 * Disable CSP Completely
 */

echo "<h1>🔧 Disabling CSP</h1>";

// 1. Update .env file
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    
    // Remove any existing CSP setting
    $content = preg_replace('/^app\.CSPEnabled\s*=.*$/m', '', $content);
    
    // Add disabled setting
    $content .= "\n# Disable Content Security Policy\napp.CSPEnabled = false\n";
    
    file_put_contents($envFile, $content);
    echo "✅ Updated .env file<br>";
} else {
    echo "❌ .env file not found<br>";
}

// 2. Update App.php to disable by default
$appFile = __DIR__ . '/app/Config/App.php';
if (file_exists($appFile)) {
    $content = file_get_contents($appFile);
    
    // Replace CSPEnabled = true with false
    $content = str_replace('public bool $CSPEnabled = true;', 'public bool $CSPEnabled = false;', $content);
    
    file_put_contents($appFile, $content);
    echo "✅ Updated App.php config<br>";
}

echo "<h2>✅ CSP Disabled!</h2>";
echo "<p>Clear your browser cache and try again.</p>";
echo "<p><strong>Test URL:</strong> <a href='/'>Go to login page</a></p>";
?>
