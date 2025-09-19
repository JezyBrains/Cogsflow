/**
 * jQuery Fix Script
 * Ensures jQuery is available for all scripts
 */

// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.log('Loading jQuery dynamically');
    
    // Create script element
    var script = document.createElement('script');
    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
    script.integrity = 'sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=';
    script.crossOrigin = 'anonymous';
    
    // Add to document
    document.head.appendChild(script);
    
    // Define $ if needed
    if (typeof $ === 'undefined') {
        window.$ = window.jQuery;
    }
}

// Bootstrap fix
if (typeof bootstrap === 'undefined') {
    console.log('Loading Bootstrap JS dynamically');
    
    // Create script element
    var bootstrapScript = document.createElement('script');
    bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
    bootstrapScript.integrity = 'sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL';
    bootstrapScript.crossOrigin = 'anonymous';
    
    // Add to document
    document.head.appendChild(bootstrapScript);
}

console.log('jQuery fix script loaded');