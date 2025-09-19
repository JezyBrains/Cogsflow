/**
 * Global jQuery Fix
 * Created: 
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