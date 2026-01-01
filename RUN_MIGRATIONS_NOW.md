# 🚨 RUN MIGRATIONS NOW

## Problem
The database is missing columns that the code expects:
1. ❌ `users.role` - Missing (causes batch approval to fail)
2. ❌ `dispatches.driver_id_number` - Missing (causes dispatch creation to fail)

## Solution: Run Migrations

### Step 1: Open Terminal
Navigate to your project folder:
```bash
cd "/Users/noobmaster69/Downloads/nipo final"
```

### Step 2: Run Migrations
Execute this command:
```bash
php spark migrate
```

### Expected Output:
```
Running: 2025-01-27-000001_AddDriverIdToDispatches
Added driver_id_number column to dispatches table
Migration: 2025-01-27-000001_AddDriverIdToDispatches
Migrated: 2025-01-27-000001_AddDriverIdToDispatches

Running: 2025-01-27-000002_AddRoleColumnToUsers
Synced role column for X users
Migration: 2025-01-27-000002_AddRoleColumnToUsers
Migrated: 2025-01-27-000002_AddRoleColumnToUsers

All migrations completed successfully!
```

### Step 3: Verify Columns Were Added

**Check dispatches table:**
```sql
DESCRIBE dispatches;
```
Should show `driver_id_number` column.

**Check users table:**
```sql
DESCRIBE users;
```
Should show `role` column.

**Check your user's role:**
```sql
SELECT id, username, role FROM users WHERE username = 'YOUR_USERNAME';
```
Should show `role = 'admin'`.

## Alternative: Run Migrations Manually (If Command Fails)

### Option A: Add driver_id_number Column
```sql
ALTER TABLE dispatches 
ADD COLUMN driver_id_number VARCHAR(50) NULL 
AFTER driver_phone
COMMENT 'Driver license or national ID number';
```

### Option B: Add role Column
```sql
-- Add column
ALTER TABLE users 
ADD COLUMN role VARCHAR(50) NULL DEFAULT 'standard_user' 
AFTER email;

-- Set your user as admin
UPDATE users 
SET role = 'admin' 
WHERE username = 'YOUR_USERNAME';

-- Sync roles from user_roles table
UPDATE users u
INNER JOIN user_roles ur ON ur.user_id = u.id AND ur.is_active = 1
INNER JOIN roles r ON r.id = ur.role_id
SET u.role = r.name;
```

## After Running Migrations

### Step 1: Verify Database
```sql
-- Check dispatches table
SHOW COLUMNS FROM dispatches LIKE 'driver_id_number';

-- Check users table
SHOW COLUMNS FROM users LIKE 'role';
```

### Step 2: Log Out and Back In
1. Log out of the application
2. Close browser
3. Log back in
4. Session will now have your role

### Step 3: Test Batch Approval
1. Go to Batches
2. Find a pending batch
3. Click "Approve"
4. **Expected**: Success! ✅

### Step 4: Test Dispatch Creation
1. Go to Dispatches
2. Click "Create New Dispatch"
3. Select approved batch
4. Fill in all fields
5. Submit
6. **Expected**: Success! ✅

## Troubleshooting

### Issue: "php: command not found"
**Solution**: Find your PHP path
```bash
which php
# or
/Applications/MAMP/bin/php/php8.x.x/bin/php spark migrate
```

### Issue: "Could not find migration"
**Solution**: Check if files exist
```bash
ls -la "app/Database/Migrations/2025-01-27-*"
```

### Issue: "Table 'database.migrations' doesn't exist"
**Solution**: Create migrations table first
```bash
php spark migrate:create
```

### Issue: Migration runs but column not added
**Check error logs**:
```bash
tail -f writable/logs/log-*.php
```

**Check MySQL errors**:
```sql
SHOW WARNINGS;
```

## What These Migrations Do

### Migration 1: AddDriverIdToDispatches
- Adds `driver_id_number` column to `dispatches` table
- Type: VARCHAR(50)
- Nullable: Yes
- Position: After `driver_phone`
- Purpose: Store driver's license or national ID number

### Migration 2: AddRoleColumnToUsers
- Adds `role` column to `users` table
- Type: VARCHAR(50)
- Default: 'standard_user'
- Position: After `email`
- Auto-syncs roles from `user_roles` table
- Purpose: Simple role checking for admin permissions

## Summary

**Current State**: Missing database columns  
**Action Required**: Run `php spark migrate`  
**Time Required**: < 1 minute  
**Risk**: Low (migrations are reversible)  

**After migrations**:
- ✅ Batch approval will work
- ✅ Dispatch creation will work
- ✅ All features functional

---

## Quick Command Reference

```bash
# Navigate to project
cd "/Users/noobmaster69/Downloads/nipo final"

# Run migrations
php spark migrate

# Check migration status
php spark migrate:status

# Rollback last migration (if needed)
php spark migrate:rollback

# Rollback all migrations (DANGER!)
php spark migrate:rollback -all
```

---

**IMPORTANT**: Run the migrations NOW to fix both issues at once! 🚀
