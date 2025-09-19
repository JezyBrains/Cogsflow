<?php
/**
 * Inline jQuery Fix
 * Directly injects jQuery into the page without requiring file modifications
 */

// Output HTML header
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inline jQuery Fix</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 { color: #2c3e50; }
        h2 { color: #3498db; }
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .code {
            font-family: monospace;
            background-color: #f8f9fa;
            padding: 2px 4px;
            border-radius: 3px;
        }
        .success { color: #27ae60; }
        .warning { color: #e67e22; }
        .error { color: #e74c3c; }
        .copy-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .copy-btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <h1>🔧 Inline jQuery Fix</h1>
    
    <h2>1. Immediate Fix</h2>
    <p>Copy this code and paste it at the <strong>very top</strong> of your <span class="code">app/Views/templates/header.php</span> file:</p>
    <div style="position: relative;">
        <pre id="code1">&lt;!-- jQuery Fix - Added <?= date('Y-m-d') ?> --&gt;
&lt;script src="https://code.jquery.com/jquery-3.6.0.min.js"&gt;&lt;/script&gt;
&lt;script&gt;
// Ensure jQuery is available globally
window.$ = window.jQuery;
// Bootstrap fix
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
        document.head.appendChild(script);
    }
});
&lt;/script&gt;</pre>
        <button class="copy-btn" onclick="copyToClipboard('code1')" style="position: absolute; top: 5px; right: 5px;">Copy</button>
    </div>
    
    <h2>2. Alternative Fix</h2>
    <p>If the above doesn't work, try this inline script at the <strong>very bottom</strong> of your <span class="code">app/Views/templates/footer.php</span> file:</p>
    <div style="position: relative;">
        <pre id="code2">&lt;!-- Emergency jQuery Fix - Added <?= date('Y-m-d') ?> --&gt;
&lt;script&gt;
// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    // Create script element
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    document.head.appendChild(script);
    
    // Wait for it to load
    script.onload = function() {
        console.log('jQuery loaded dynamically');
        window.$ = window.jQuery;
        
        // Now load Bootstrap if needed
        if (typeof bootstrap === 'undefined') {
            var bootstrapScript = document.createElement('script');
            bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
            document.head.appendChild(bootstrapScript);
        }
    };
} else {
    console.log('jQuery already loaded');
    // Define $ if needed
    if (typeof $ === 'undefined') {
        window.$ = window.jQuery;
    }
}
&lt;/script&gt;</pre>
        <button class="copy-btn" onclick="copyToClipboard('code2')" style="position: absolute; top: 5px; right: 5px;">Copy</button>
    </div>
    
    <h2>3. Find Your Template Files</h2>
    <p>Common locations for template files:</p>
    <ul>
        <li><span class="code">app/Views/templates/header.php</span></li>
        <li><span class="code">app/Views/templates/footer.php</span></li>
        <li><span class="code">app/Views/layout/default.php</span></li>
        <li><span class="code">app/Views/layout/main.php</span></li>
    </ul>
    
    <h2>4. Direct Fix for Login Page</h2>
    <p>If you're having issues specifically with the login page, edit <span class="code">app/Views/auth/login.php</span> and add this at the top:</p>
    <div style="position: relative;">
        <pre id="code3">&lt;!-- jQuery Fix for Login - Added <?= date('Y-m-d') ?> --&gt;
&lt;script src="https://code.jquery.com/jquery-3.6.0.min.js"&gt;&lt;/script&gt;
&lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
&lt;script&gt;window.$ = window.jQuery;&lt;/script&gt;</pre>
        <button class="copy-btn" onclick="copyToClipboard('code3')" style="position: absolute; top: 5px; right: 5px;">Copy</button>
    </div>
    
    <h2>5. Create a Global Fix Script</h2>
    <p>Create this file at <span class="code">public/assets/js/fix.js</span> and include it in your templates:</p>
    <div style="position: relative;">
        <pre id="code4">/**
 * Global jQuery Fix
 * Created: <?= date('Y-m-d') ?>
 */

// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.log('Loading jQuery dynamically');
    
    // Create script element
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    
    // Add to document
    document.head.appendChild(script);
    
    // Wait for it to load
    script.onload = function() {
        console.log('jQuery loaded successfully');
        window.$ = window.jQuery;
        
        // Now load Bootstrap if needed
        if (typeof bootstrap === 'undefined') {
            var bootstrapScript = document.createElement('script');
            bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
            document.head.appendChild(bootstrapScript);
        }
    };
} else {
    console.log('jQuery already loaded');
    // Define $ if needed
    if (typeof $ === 'undefined') {
        window.$ = window.jQuery;
    }
}</pre>
        <button class="copy-btn" onclick="copyToClipboard('code4')" style="position: absolute; top: 5px; right: 5px;">Copy</button>
    </div>
    
    <h2>🔍 Testing Your Fix</h2>
    <p>After applying any of these fixes:</p>
    <ol>
        <li>Clear your browser cache (Ctrl+Shift+R or Cmd+Shift+R)</li>
        <li>Open browser developer tools (F12) and check the console for errors</li>
        <li>Try logging in again</li>
    </ol>
    
    <script>
    function copyToClipboard(elementId) {
        const el = document.getElementById(elementId);
        const text = el.textContent;
        navigator.clipboard.writeText(text).then(function() {
            const btn = el.nextElementSibling;
            btn.textContent = "Copied!";
            setTimeout(function() {
                btn.textContent = "Copy";
            }, 2000);
        });
    }
    </script>
    
    <hr>
    <p><small>Generated: <?= date('Y-m-d H:i:s') ?></small></p>
</body>
</html>
<?php
// Create the fix.js file
$jsDir = __DIR__ . '/public/assets/js';
if (!is_dir($jsDir)) {
    @mkdir($jsDir, 0755, true);
}

$fixJsContent = <<<EOT
/**
 * Global jQuery Fix
 * Created: {$date}
 */

// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.log('Loading jQuery dynamically');
    
    // Create script element
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    
    // Add to document
    document.head.appendChild(script);
    
    // Wait for it to load
    script.onload = function() {
        console.log('jQuery loaded successfully');
        window.$ = window.jQuery;
        
        // Now load Bootstrap if needed
        if (typeof bootstrap === 'undefined') {
            var bootstrapScript = document.createElement('script');
            bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
            document.head.appendChild(bootstrapScript);
        }
    };
} else {
    console.log('jQuery already loaded');
    // Define $ if needed
    if (typeof $ === 'undefined') {
        window.$ = window.jQuery;
    }
}
EOT;

@file_put_contents($jsDir . '/fix.js', $fixJsContent);
?>
