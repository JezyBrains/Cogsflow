# 🚨 CRITICAL: Server Configuration Issue

## The Problem
If even `debug.php` returns a 500 error, this indicates a **fundamental server configuration issue**, not a CodeIgniter problem.

## Immediate Tests

### Test 1: Static HTML
Visit: `http://localhost:8000/check.html`
- ✅ **If it works**: Web server is running, issue is with PHP
- ❌ **If it fails**: Web server configuration problem

### Test 2: Simple PHP
Visit: `http://localhost:8000/simple.php`
- ✅ **If it works**: PHP is working, issue is with complex scripts
- ❌ **If it fails**: PHP configuration problem

### Test 3: PHP Info
Visit: `http://localhost:8000/info.php`
- ✅ **If it works**: PHP is fully functional
- ❌ **If it fails**: PHP configuration problem

## Root Cause Analysis

### Issue 1: Web Server Document Root
**Most Likely Problem**: Your web server is pointing to the wrong directory.

**Current Setup (WRONG)**:
```
Document Root: /path/to/cogsflow/
```

**Correct Setup**:
```
Document Root: /path/to/cogsflow/public/
```

**How to Fix**:

#### For Apache (cPanel/WHM):
1. Go to cPanel → "Subdomains" or "Addon Domains"
2. Set document root to: `/public_html/cogsflow/public`
3. Or edit `.htaccess` in your main domain root:
```apache
RewriteEngine On
RewriteCond %{HTTP_HOST} ^(www\.)?nipoagro\.com$ [NC]
RewriteCond %{REQUEST_URI} !^/cogsflow/public/
RewriteRule ^(.*)$ /cogsflow/public/$1 [L]
```

#### For Nginx:
```nginx
server {
    listen 443 ssl;
    server_name localhost:8000 www.localhost:8000;
    root /path/to/cogsflow/public;  # This is the key line
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Issue 2: PHP Not Working
If `simple.php` fails, PHP is not properly configured.

**Check PHP Status**:
```bash
# Check if PHP is installed
php -v

# Check if PHP-FPM is running (for Nginx)
systemctl status php8.1-fpm

# Check if Apache PHP module is loaded
apache2ctl -M | grep php

# Check PHP error log
tail -f /var/log/php_errors.log
```

### Issue 3: File Permissions
**Set Correct Permissions**:
```bash
# Make sure you're in the right directory
cd /path/to/cogsflow

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions  
find . -type f -exec chmod 644 {} \;

# Set writable directory permissions
chmod -R 777 writable/

# Make sure PHP files are readable
chmod 644 *.php
chmod 644 public/*.php
```

### Issue 4: Missing Files
**Check if files were uploaded correctly**:
```bash
# Check if main files exist
ls -la /path/to/cogsflow/
ls -la /path/to/cogsflow/public/
ls -la /path/to/cogsflow/public/index.php
ls -la /path/to/cogsflow/app/
```

## Server-Specific Solutions

### cPanel/Shared Hosting:
1. **Upload files to the correct directory**:
   - Main files: `/public_html/cogsflow/`
   - Public files: `/public_html/` (or create subdomain pointing to `/public_html/cogsflow/public/`)

2. **Set PHP version**:
   - Go to cPanel → "Select PHP Version"
   - Choose PHP 8.0 or higher
   - Enable required extensions

3. **Check error logs**:
   - cPanel → "Error Logs"
   - Look for recent PHP errors

### VPS/Dedicated Server:
1. **Check web server status**:
```bash
# For Apache
systemctl status apache2
systemctl restart apache2

# For Nginx  
systemctl status nginx
systemctl restart nginx

# For PHP-FPM
systemctl status php8.1-fpm
systemctl restart php8.1-fpm
```

2. **Check configuration**:
```bash
# Test Apache config
apache2ctl configtest

# Test Nginx config
nginx -t

# Check PHP config
php --ini
```

## Emergency Workaround

If you can't fix the document root, create this `.htaccess` in your main domain root:

```apache
# Emergency redirect to public folder
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /public/$1 [L]

# Handle CodeIgniter routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /public/index.php?/$1 [L]
```

## Quick Diagnostic Commands

Run these on your server to gather information:

```bash
# 1. Check web server
ps aux | grep apache
ps aux | grep nginx

# 2. Check PHP
php -v
php -m

# 3. Check file structure
ls -la /path/to/your/domain/
ls -la /path/to/your/domain/public/

# 4. Check permissions
ls -la /path/to/cogsflow/
ls -la /path/to/cogsflow/writable/

# 5. Check error logs
tail -20 /var/log/apache2/error.log
tail -20 /var/log/nginx/error.log
tail -20 /var/log/php_errors.log
```

## Contact Your Hosting Provider

If the above doesn't work, contact your hosting provider with this information:

1. **"I need my domain localhost:8000 to point to the `/public` subdirectory of my application"**
2. **"I'm getting 500 errors on all PHP files, including simple ones"**
3. **"Please check if PHP is properly configured and if the document root is correct"**

## Next Steps

1. **Test the HTML file first**: `http://localhost:8000/check.html`
2. **Check your hosting control panel** for PHP settings
3. **Look at error logs** in your hosting control panel
4. **Contact hosting support** if you can't access server configuration

The issue is definitely at the server configuration level, not with the CodeIgniter application code.
