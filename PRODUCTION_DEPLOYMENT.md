# CogsFlow Production Deployment Guide
## Domain: nipoagro.com

This document provides comprehensive instructions for deploying CogsFlow to production on nipoagro.com.

## Pre-Deployment Checklist

### 1. Server Requirements
- **PHP**: 8.1 or higher
- **MySQL**: 5.7 or higher (recommended: 8.0+)
- **Apache/Nginx**: with mod_rewrite enabled
- **SSL Certificate**: Valid SSL certificate for nipoagro.com
- **Composer**: Latest version
- **Node.js**: For asset compilation (if needed)

### 2. Domain Configuration
- Domain: `nipoagro.com`
- Document Root: `/path/to/cogsflow/public`
- SSL: Enforced (HTTP redirects to HTTPS)
- WWW: Redirects to non-www version

## Deployment Steps

### 1. Upload Files
Upload all project files to the server, ensuring the document root points to the `public` directory.

### 2. Environment Configuration
```bash
# Copy production environment file
cp .env.production .env

# Edit the .env file with actual production values
nano .env
```

**Required Environment Variables to Update:**
```env
# Database Configuration
database.default.hostname = your_mysql_host
database.default.database = nipoagro_cogsflow
database.default.username = your_db_username
database.default.password = YOUR_SECURE_DB_PASSWORD

# Encryption Key (generate using: php spark key:generate)
encryption.key = YOUR_GENERATED_ENCRYPTION_KEY

# Email Configuration
email.SMTPHost = mail.nipoagro.com
email.SMTPUser = noreply@nipoagro.com
email.SMTPPass = YOUR_EMAIL_PASSWORD
```

### 3. Run Deployment Script
```bash
chmod +x deploy.sh
./deploy.sh
```

### 4. Manual Steps After Deployment

#### Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE nipoagro_cogsflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'nipoagro_user'@'localhost' IDENTIFIED BY 'YOUR_SECURE_PASSWORD';
GRANT ALL PRIVILEGES ON nipoagro_cogsflow.* TO 'nipoagro_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations and seeders
php spark migrate
php spark db:seed ProductionSeeder
```

#### File Permissions
```bash
# Set proper ownership (replace 'www-data' with your web server user)
chown -R www-data:www-data /path/to/cogsflow
chmod -R 755 /path/to/cogsflow
chmod -R 777 /path/to/cogsflow/writable
chmod 600 /path/to/cogsflow/.env
```

## Security Configuration

### 1. Apache Virtual Host Configuration
```apache
<VirtualHost *:80>
    ServerName nipoagro.com
    ServerAlias www.nipoagro.com
    DocumentRoot /path/to/cogsflow/public
    
    # Force HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName nipoagro.com
    DocumentRoot /path/to/cogsflow/public
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/ssl/certificate.crt
    SSLCertificateKeyFile /path/to/ssl/private.key
    SSLCertificateChainFile /path/to/ssl/ca_bundle.crt
    
    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    
    # Directory Configuration
    <Directory /path/to/cogsflow/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Deny access to sensitive files
    <Files ".env">
        Require all denied
    </Files>
    
    <DirectoryMatch "^/.*/\.git/">
        Require all denied
    </DirectoryMatch>
</VirtualHost>
```

### 2. Nginx Configuration (Alternative)
```nginx
server {
    listen 80;
    server_name nipoagro.com www.nipoagro.com;
    return 301 https://nipoagro.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name www.nipoagro.com;
    return 301 https://nipoagro.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name nipoagro.com;
    root /path/to/cogsflow/public;
    index index.php;
    
    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    
    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options DENY always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # PHP Configuration
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    
    # CodeIgniter URL Rewriting
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }
    
    location ~ /\.git {
        deny all;
    }
}
```

## Default Admin Credentials

**⚠️ IMPORTANT: Change these credentials immediately after first login!**

- **Username**: `admin`
- **Email**: `admin@nipoagro.com`
- **Password**: `NipoAgro2025!`

## Post-Deployment Tasks

### 1. Security Hardening
- [ ] Change default admin password
- [ ] Update all placeholder passwords in .env
- [ ] Configure email settings
- [ ] Set up SSL certificate
- [ ] Configure firewall rules
- [ ] Set up database backups

### 2. System Configuration
- [ ] Update company information in settings
- [ ] Configure notification preferences
- [ ] Set up user roles and permissions
- [ ] Test all modules and functionality
- [ ] Configure backup schedules

### 3. Monitoring Setup
- [ ] Set up error logging
- [ ] Configure performance monitoring
- [ ] Set up uptime monitoring
- [ ] Configure backup verification

## Backup Strategy

### Database Backup
```bash
# Daily backup script
mysqldump -u nipoagro_user -p nipoagro_cogsflow > /backups/cogsflow_$(date +%Y%m%d).sql
```

### File Backup
```bash
# Weekly file backup
tar -czf /backups/cogsflow_files_$(date +%Y%m%d).tar.gz /path/to/cogsflow --exclude=vendor --exclude=writable/cache
```

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check file permissions
   - Verify .htaccess configuration
   - Check error logs

2. **Database Connection Error**
   - Verify database credentials
   - Check database server status
   - Ensure database exists

3. **SSL Certificate Issues**
   - Verify certificate installation
   - Check certificate validity
   - Ensure proper redirect configuration

### Log Locations
- **Application Logs**: `/path/to/cogsflow/writable/logs/`
- **Apache Logs**: `/var/log/apache2/error.log`
- **Nginx Logs**: `/var/log/nginx/error.log`
- **PHP Logs**: `/var/log/php/error.log`

## Support

For technical support or issues with the deployment, please contact:
- **Email**: admin@nipoagro.com
- **Documentation**: Check the main README.md file

## Version Information

- **CogsFlow Version**: 1.0.0
- **CodeIgniter Version**: 4.x
- **PHP Version**: 8.1+
- **Deployment Date**: $(date +%Y-%m-%d)

---

**Remember**: Always test the deployment in a staging environment before deploying to production!
