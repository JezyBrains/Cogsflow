# Database Reset Feature

## Overview
The Database Reset feature provides administrators with the ability to clear or completely reset the CogsFlow database to a fresh state. This is useful for:
- Starting fresh with clean data
- Resetting demo/test environments
- Recovering from data corruption
- Preparing for new deployments

## Features

### 1. Clear All Data
**Purpose**: Removes all business data while preserving system structure and settings.

**What gets cleared**:
- All batches and batch bags
- All dispatches and dispatch records
- All inventory entries
- All purchase orders
- All suppliers (except system defaults)
- All expenses
- All notifications
- All audit logs

**What gets preserved**:
- User accounts and authentication
- System settings and configuration
- Table structure and relationships
- Roles and permissions
- Essential system data

### 2. Reset Database Completely
**Purpose**: Completely resets the database to fresh installation state.

**What happens**:
- All tables are dropped (except migrations)
- Tables are recreated from migrations
- Essential seeders are run automatically
- Default admin user is created
- Default system settings are restored

**Default Credentials After Reset**:
- Username: `admin`
- Email: `admin@nipoagro.com`
- Password: `NipoAgro2025!`

## Safety Features

### Automatic Backup
- A complete database backup is created before any reset operation
- Backups are stored in `writable/backups/` directory
- Backup filename includes timestamp for easy identification
- Operation fails if backup creation fails

### Confirmation Requirements
- User must type "CONFIRM" exactly
- User must check acknowledgment checkbox
- Double confirmation prevents accidental resets
- Clear warnings about data loss

### Audit Logging
- All reset operations are logged with full details
- Includes user information, IP address, and timestamp
- Backup filename is recorded for recovery reference

## Access Control
- Only administrators can access database reset features
- Feature is located in Settings → Admin Tools → Database Management
- Requires active admin session

## Technical Implementation

### Backend Components
1. **AdminUtilities::resetDatabase()** - Complete database reset
2. **AdminUtilities::clearAllData()** - Data clearing only
3. **AdminUtilities::runEssentialSeeders()** - Restore essential data
4. **SettingsController::adminUtility()** - Handle reset requests

### Frontend Components
1. **Database Management Section** - UI controls in settings
2. **Confirmation Modal** - Safety confirmation dialog
3. **JavaScript Handlers** - Form validation and AJAX requests

### Essential Seeders
After database reset, these seeders run automatically:
- `DefaultSettingsSeeder` - System configuration
- `DefaultUserSeeder` - Admin and sample users
- `RolePermissionSeeder` - User roles and permissions
- `NotificationTypesSeeder` - Notification categories
- `ReportsSeeder` - Report definitions

## Usage Instructions

### Clearing Data Only
1. Navigate to Settings → Admin Tools
2. Scroll to "Database Management" section
3. Click "Clear All Data" button
4. Read the warning carefully
5. Type "CONFIRM" in the text field
6. Check the acknowledgment checkbox
7. Click "Proceed with Operation"
8. Wait for completion message
9. Page will reload automatically

### Complete Database Reset
1. Navigate to Settings → Admin Tools
2. Scroll to "Database Management" section
3. Click "Reset Database" button
4. Read the warning carefully
5. Type "CONFIRM" in the text field
6. Check the acknowledgment checkbox
7. Click "Proceed with Operation"
8. Wait for completion message
9. You will be redirected to login page
10. Log in with default admin credentials

## Recovery

### From Backup
If you need to recover data after a reset:
1. Locate the backup file in `writable/backups/`
2. Use MySQL command line or phpMyAdmin
3. Import the backup SQL file
4. Restart the application

### Backup File Format
```
backup_YYYY-MM-DD_HH-MM-SS.sql
Example: backup_2025-01-15_14-30-45.sql
```

## Troubleshooting

### Common Issues
1. **Backup Creation Fails**
   - Check MySQL credentials
   - Ensure mysqldump is available
   - Verify write permissions on backups directory

2. **Migration Fails After Reset**
   - Check database connection
   - Verify migration files exist
   - Ensure proper database permissions

3. **Seeders Fail**
   - Check seeder class names
   - Verify database table structure
   - Review error logs for details

### Error Logs
Check these locations for error details:
- `writable/logs/` - Application logs
- System logs table - Database operation logs
- Web server error logs

## Security Considerations

### Production Use
- Always create manual backup before reset
- Notify all users before performing reset
- Consider maintenance mode during operation
- Test reset procedure in staging first

### Access Restrictions
- Feature requires admin privileges
- Consider additional IP restrictions
- Monitor admin actions through audit logs
- Regular review of admin user accounts

## Best Practices

### Before Reset
1. Notify all system users
2. Create manual backup
3. Document current system state
4. Plan for user re-training if needed

### After Reset
1. Verify system functionality
2. Update system settings as needed
3. Create new user accounts
4. Import essential data if required
5. Test all major workflows

### Regular Maintenance
1. Clean old backup files regularly
2. Monitor disk space usage
3. Test backup restoration periodically
4. Keep documentation updated

## Support
For technical support or questions about the database reset feature, contact the system administrator or development team.
