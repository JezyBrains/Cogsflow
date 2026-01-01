# CogsFlow Deployment Guide for Dokploy

This guide will help you deploy the CogsFlow grain management system to Dokploy.

## Prerequisites

1. **Dokploy Server**: You need a Dokploy instance running (self-hosted or cloud)
2. **GitHub Repository**: Your code should be in a GitHub repository
3. **Domain Name**: Optional but recommended for production

## Deployment Methods

Dokploy supports multiple deployment methods. Choose the one that fits your needs:

### Method 1: GitHub Integration (Recommended)

#### Step 1: Prepare Your Repository

1. Ensure all files are committed and pushed to GitHub:
   ```bash
   git add -A
   git commit -m "Add Dokploy deployment configuration"
   git push origin main
   ```

2. Your repository should include:
   - ✅ `Dockerfile`
   - ✅ `docker-compose.yml`
   - ✅ `.dockerignore`
   - ✅ `docker/apache.conf`
   - ✅ `.env.example`

#### Step 2: Configure Dokploy

1. **Login to Dokploy Dashboard**
   - Access your Dokploy instance (e.g., `https://dokploy.yourdomain.com`)
   - Login with your credentials

2. **Create New Application**
   - Click "Create Application"
   - Choose "Docker Compose" as deployment type
   - Name: `cogsflow`

3. **Connect GitHub Repository**
   - Select "GitHub" as source
   - Authorize Dokploy to access your repository
   - Select repository: `JezyBrains/Cogsflow`
   - Branch: `main`

4. **Configure Environment Variables**
   
   In Dokploy, add these environment variables:
   
   ```env
   CI_ENVIRONMENT=production
   
   # Database Configuration
   DB_DATABASE=cogsflow_db
   DB_USERNAME=cogsflow_user
   DB_PASSWORD=YOUR_SECURE_PASSWORD_HERE
   DB_ROOT_PASSWORD=YOUR_ROOT_PASSWORD_HERE
   
   # Application Configuration
   APP_URL=https://your-domain.com
   
   # Encryption Key (32 characters)
   ENCRYPTION_KEY=YOUR_32_CHAR_ENCRYPTION_KEY_HERE
   
   # Database Connection (for CodeIgniter)
   database.default.hostname=db
   database.default.database=cogsflow_db
   database.default.username=cogsflow_user
   database.default.password=YOUR_SECURE_PASSWORD_HERE
   database.default.DBDriver=MySQLi
   database.default.port=3306
   ```

   **Generate Encryption Key:**
   ```bash
   php -r "echo bin2hex(random_bytes(16));"
   ```

5. **Configure Domain (Optional)**
   - Go to "Domains" section
   - Add your custom domain
   - Enable SSL/HTTPS (Let's Encrypt)

6. **Deploy**
   - Click "Deploy" button
   - Dokploy will:
     - Clone your repository
     - Build Docker images
     - Start containers
     - Run database migrations

#### Step 3: Post-Deployment Setup

1. **Run Database Migrations**
   
   Access the container shell in Dokploy:
   ```bash
   php spark migrate
   ```

2. **Seed Default Data**
   ```bash
   php spark db:seed DefaultUserSeeder
   php spark db:seed DefaultSettingsSeeder
   ```

3. **Verify Installation**
   - Visit your domain: `https://your-domain.com`
   - Login with default credentials:
     - Username: `admin`
     - Password: `NipoAgro2025!`

4. **Change Default Password**
   - Go to Settings → User Profile
   - Update admin password immediately

---

### Method 2: Docker Compose Direct Deployment

If you prefer to deploy without GitHub integration:

#### Step 1: Upload Files to Server

1. **SSH into your Dokploy server:**
   ```bash
   ssh user@your-dokploy-server.com
   ```

2. **Create project directory:**
   ```bash
   mkdir -p /opt/cogsflow
   cd /opt/cogsflow
   ```

3. **Upload files via SCP or Git:**
   ```bash
   git clone https://github.com/JezyBrains/Cogsflow.git .
   ```

#### Step 2: Configure Environment

1. **Create .env file:**
   ```bash
   cp .env.example .env
   nano .env
   ```

2. **Update environment variables** (see Method 1, Step 2, Point 4)

#### Step 3: Deploy with Docker Compose

1. **Build and start containers:**
   ```bash
   docker-compose up -d --build
   ```

2. **Check container status:**
   ```bash
   docker-compose ps
   ```

3. **View logs:**
   ```bash
   docker-compose logs -f app
   ```

4. **Run migrations:**
   ```bash
   docker-compose exec app php spark migrate
   docker-compose exec app php spark db:seed DefaultUserSeeder
   ```

---

## Dokploy-Specific Configuration

### Auto-Deploy on Git Push

1. In Dokploy dashboard, enable "Auto Deploy"
2. Select trigger: "Push to main branch"
3. Every push to main will automatically redeploy

### Health Checks

Dokploy will monitor your application health:
- HTTP endpoint: `/` (should return 200)
- Interval: 30 seconds
- Timeout: 10 seconds
- Retries: 3

### Scaling

To scale your application:
1. Go to "Scaling" section in Dokploy
2. Increase replicas (e.g., 2-3 instances)
3. Dokploy will handle load balancing

### Backups

Configure automatic backups in Dokploy:
1. Go to "Backups" section
2. Enable database backups
3. Schedule: Daily at 2 AM
4. Retention: 7 days

---

## Troubleshooting

### Issue: Database Connection Failed

**Solution:**
```bash
# Check if database container is running
docker-compose ps

# Check database logs
docker-compose logs db

# Verify environment variables
docker-compose exec app env | grep database
```

### Issue: Permission Denied on writable/ folder

**Solution:**
```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/writable
docker-compose exec app chmod -R 755 /var/www/html/writable
```

### Issue: 404 on all routes

**Solution:**
```bash
# Check Apache mod_rewrite is enabled
docker-compose exec app apache2ctl -M | grep rewrite

# Verify .htaccess exists
docker-compose exec app ls -la /var/www/html/public/.htaccess
```

### Issue: Build fails

**Solution:**
```bash
# Clear Docker cache and rebuild
docker-compose down
docker system prune -a
docker-compose up -d --build
```

---

## Monitoring & Maintenance

### View Application Logs

```bash
# Real-time logs
docker-compose logs -f app

# Last 100 lines
docker-compose logs --tail=100 app
```

### Database Backup

```bash
# Manual backup
docker-compose exec db mysqldump -u root -p${DB_ROOT_PASSWORD} cogsflow_db > backup_$(date +%Y%m%d).sql

# Restore backup
docker-compose exec -T db mysql -u root -p${DB_ROOT_PASSWORD} cogsflow_db < backup_20260101.sql
```

### Update Application

```bash
# Pull latest code
git pull origin main

# Rebuild and restart
docker-compose up -d --build

# Run new migrations
docker-compose exec app php spark migrate
```

---

## Security Recommendations

1. **Change Default Credentials** immediately after first login
2. **Use Strong Passwords** for database and admin accounts
3. **Enable HTTPS** with Let's Encrypt in Dokploy
4. **Regular Backups** - Enable automatic backups in Dokploy
5. **Update Regularly** - Keep CodeIgniter and dependencies updated
6. **Firewall Rules** - Only expose ports 80 and 443
7. **Environment Variables** - Never commit .env to repository

---

## Performance Optimization

### Enable OPcache

Add to Dockerfile:
```dockerfile
RUN docker-php-ext-install opcache
```

### Configure PHP Memory

In Dokploy environment variables:
```env
PHP_MEMORY_LIMIT=256M
PHP_MAX_EXECUTION_TIME=60
```

### Database Optimization

```bash
# Optimize tables
docker-compose exec db mysqlcheck -u root -p${DB_ROOT_PASSWORD} --optimize cogsflow_db
```

---

## Support & Resources

- **Dokploy Documentation**: https://docs.dokploy.com
- **CodeIgniter 4 Docs**: https://codeigniter.com/user_guide/
- **GitHub Repository**: https://github.com/JezyBrains/Cogsflow
- **Issue Tracker**: https://github.com/JezyBrains/Cogsflow/issues

---

## Quick Reference Commands

```bash
# Start application
docker-compose up -d

# Stop application
docker-compose down

# Restart application
docker-compose restart

# View logs
docker-compose logs -f

# Access container shell
docker-compose exec app bash

# Run migrations
docker-compose exec app php spark migrate

# Clear cache
docker-compose exec app php spark cache:clear

# Database backup
docker-compose exec db mysqldump -u root -p${DB_ROOT_PASSWORD} cogsflow_db > backup.sql
```

---

## Deployment Checklist

- [ ] Repository pushed to GitHub
- [ ] Dockerfile and docker-compose.yml created
- [ ] Environment variables configured in Dokploy
- [ ] Domain configured (optional)
- [ ] SSL/HTTPS enabled
- [ ] Application deployed successfully
- [ ] Database migrations run
- [ ] Default user seeded
- [ ] Admin password changed
- [ ] Backups configured
- [ ] Monitoring enabled
- [ ] Health checks passing

---

**Deployment Complete! 🎉**

Your CogsFlow application should now be running on Dokploy. Access it at your configured domain and start managing your grain operations.
