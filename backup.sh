#!/bin/bash

# CogsFlow Backup Script
# Creates a complete backup of the application and database

BACKUP_DIR="/backups/cogsflow"
DATE=$(date +%Y%m%d_%H%M%S)
PROJECT_DIR="/path/to/cogsflow"

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

echo "Starting CogsFlow backup - $DATE"

# Database backup
echo "Backing up database..."
mysqldump -u johsport_jezakh -p johsport_nipo > $BACKUP_DIR/database_$DATE.sql

# Application files backup (excluding vendor and cache)
echo "Backing up application files..."
tar -czf $BACKUP_DIR/application_$DATE.tar.gz \
    --exclude='vendor' \
    --exclude='writable/cache' \
    --exclude='writable/logs' \
    --exclude='writable/session' \
    --exclude='.git' \
    --exclude='node_modules' \
    $PROJECT_DIR

# Environment file backup (separate for security)
echo "Backing up environment configuration..."
cp $PROJECT_DIR/.env $BACKUP_DIR/env_$DATE.backup

# Create backup manifest
echo "Creating backup manifest..."
cat > $BACKUP_DIR/manifest_$DATE.txt << EOF
CogsFlow Backup Manifest
Date: $DATE
Database: database_$DATE.sql
Application: application_$DATE.tar.gz
Environment: env_$DATE.backup

Backup completed: $(date)
EOF

# Clean up old backups (keep last 30 days)
echo "Cleaning up old backups..."
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
find $BACKUP_DIR -name "*.backup" -mtime +30 -delete
find $BACKUP_DIR -name "manifest_*.txt" -mtime +30 -delete

echo "Backup completed successfully!"
echo "Files created:"
echo "- $BACKUP_DIR/database_$DATE.sql"
echo "- $BACKUP_DIR/application_$DATE.tar.gz"
echo "- $BACKUP_DIR/env_$DATE.backup"
echo "- $BACKUP_DIR/manifest_$DATE.txt"
