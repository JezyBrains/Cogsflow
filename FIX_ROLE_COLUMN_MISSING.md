# CRITICAL FIX: Missing 'role' Column in Users Table

## Problem Identified
The `users` table doesn't have a `role` column, but the code expects it!

### Error:
```
MySQL said: #1054 - Unknown column 'role' in 'SELECT'
```

### Root Cause:
Your system has two different role management approaches:

1. **RBAC System** (proper, complex):
   - `roles` table
   - `user_roles` table (many-to-many)
   - `permissions` table
   - `role_permissions` table

2. **Simple Role Column** (what the code expects):
   - `users.role` column (doesn't exist!)

The code in `AuthController.php` (line 49) and `BatchModel.php` (line 194) expects:
```php
$user->role  // This field doesn't exist!
```

## Solution: Add Role Column to Users Table

### Step 1: Run the Migration

I've created a migration file that will:
1. Add the `role` column to `users` table
2. Sync existing roles from `user_roles` table

**Run this command:**
```bash
cd "/Users/noobmaster69/Downloads/nipo final"
php spark migrate
```

This will execute: `2025-01-27-000002_AddRoleColumnToUsers.php`

### Step 2: Verify the Column Was Added

Run this SQL:
```sql
DESCRIBE users;
```

You should now see a `role` column with type `VARCHAR(50)`.

### Step 3: Check Your User's Role

```sql
SELECT id, username, email, role, status 
FROM users 
WHERE username = 'YOUR_USERNAME';
```

Expected output:
```
id | username | email | role  | status
1  | admin    | ...   | admin | active
```

### Step 4: If Role is NULL, Set It Manually

If your role shows as NULL:

```sql
-- Set your user as admin
UPDATE users 
SET role = 'admin' 
WHERE username = 'YOUR_USERNAME';
```

### Step 5: Log Out and Back In

1. Log out completely
2. Close browser
3. Log back in
4. Try approving the batch again

## What the Migration Does

### 1. Adds Column
```sql
ALTER TABLE users 
ADD COLUMN role VARCHAR(50) NULL DEFAULT 'standard_user' 
AFTER email;
```

### 2. Syncs Existing Roles
The migration automatically copies roles from the `user_roles` table:
```sql
UPDATE users u
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.is_active = 1
LEFT JOIN roles r ON r.id = ur.role_id
SET u.role = r.name
WHERE r.name IS NOT NULL;
```

## Available Roles

Based on your RBAC system, these roles exist:
- `admin` - Full system access
- `warehouse_staff` - Warehouse operations
- `standard_user` - Basic access

## Verification Queries

### Check All Users and Their Roles:
```sql
SELECT 
    u.id,
    u.username,
    u.role as simple_role,
    GROUP_CONCAT(r.name) as rbac_roles
FROM users u
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.is_active = 1
LEFT JOIN roles r ON r.id = ur.role_id
GROUP BY u.id, u.username, u.role;
```

### Check if Roles Match:
```sql
SELECT 
    u.id,
    u.username,
    u.role as users_table_role,
    r.name as user_roles_table_role,
    CASE 
        WHEN u.role = r.name THEN 'MATCH'
        WHEN u.role IS NULL THEN 'NULL IN USERS TABLE'
        WHEN r.name IS NULL THEN 'NO ROLE IN USER_ROLES'
        ELSE 'MISMATCH'
    END as status
FROM users u
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.is_active = 1
LEFT JOIN roles r ON r.id = ur.role_id;
```

## After Migration

### Test Batch Approval:
1. Go to Batches page
2. Find a pending batch
3. Click "Approve"
4. Should work now!

### Test Dispatch Creation:
1. Go to Dispatches page
2. Click "Create New Dispatch"
3. Select an approved batch
4. Fill in details
5. Submit

## Troubleshooting

### Issue 1: Migration Fails
**Error**: "Table 'users' doesn't exist"

**Solution**: Your users table might have a different name. Check:
```sql
SHOW TABLES LIKE '%user%';
```

### Issue 2: Role Still NULL After Migration
**Solution**: Manually set it:
```sql
UPDATE users SET role = 'admin' WHERE id = YOUR_USER_ID;
```

### Issue 3: Still Can't Approve
**Check**:
1. Role is set: `SELECT role FROM users WHERE id = YOUR_ID;`
2. Session has role: Add debug code to see session
3. Batch is pending: `SELECT status FROM batches WHERE id = BATCH_ID;`
4. PO is approved: Check linked PO status

## Alternative: Manual Fix (If Migration Doesn't Work)

### Option A: Add Column Manually
```sql
ALTER TABLE users 
ADD COLUMN role VARCHAR(50) NULL DEFAULT 'standard_user' 
AFTER email;

-- Set your user as admin
UPDATE users 
SET role = 'admin' 
WHERE username = 'YOUR_USERNAME';
```

### Option B: Sync All Users
```sql
-- Copy roles from user_roles to users table
UPDATE users u
INNER JOIN user_roles ur ON ur.user_id = u.id AND ur.is_active = 1
INNER JOIN roles r ON r.id = ur.role_id
SET u.role = r.name;
```

## Future Considerations

### Keep Both Systems in Sync

When assigning roles through the UI, update both:
1. Insert into `user_roles` table (RBAC)
2. Update `users.role` column (simple check)

### Or: Migrate to Full RBAC

Eventually, you might want to update the code to use only the RBAC system:
- Update `AuthController` to query `user_roles`
- Update `BatchModel` to query `user_roles`
- Remove dependency on `users.role` column

But for now, the simple column fix will work!

## Summary

**What's wrong**: `users` table missing `role` column  
**Quick fix**: Run migration to add the column  
**Command**: `php spark migrate`  
**Then**: Log out and back in  
**Result**: Batch approval should work!

---

**Created**: January 27, 2025  
**Status**: Ready to apply  
**Priority**: CRITICAL - Blocks batch approval
