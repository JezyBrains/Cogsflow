# Troubleshooting: Cannot Approve Batch

## Quick Checks

### 1. Verify Your Role
Check if you're actually logged in as admin:

**Option A: Check in UI**
- Look at the top right corner of the page
- Your username should be displayed
- Check if you see admin-only menu items

**Option B: Check in Database**
```sql
-- Find your user record
SELECT id, username, role, status 
FROM users 
WHERE username = 'your_username';

-- Should show role = 'admin'
```

### 2. Clear Session and Re-login
Sometimes the session doesn't have the updated role:

1. **Log out completely**
2. **Clear browser cache** (Ctrl+Shift+Delete)
3. **Close all browser tabs**
4. **Log back in**
5. **Try approving the batch again**

### 3. Check the Batch Status
The batch must be in "pending" status to be approved:

```sql
SELECT id, batch_number, status, purchase_order_id 
FROM batches 
WHERE id = YOUR_BATCH_ID;

-- Status should be 'pending'
```

### 4. Check if PO is Approved
The Purchase Order must be approved first:

```sql
SELECT po.id, po.po_number, po.status, po.approved_by,
       b.id as batch_id, b.batch_number, b.status as batch_status
FROM purchase_orders po
LEFT JOIN batches b ON b.purchase_order_id = po.id
WHERE b.id = YOUR_BATCH_ID;

-- PO status should be 'approved'
-- PO approved_by should have a value
```

### 5. Check Application Logs
Look at the debug logs to see what's happening:

```bash
# View recent logs
tail -f writable/logs/log-*.php

# Look for lines like:
# Batch Approval Check - User ID: X, Role: admin, Batch ID: Y
```

## Common Issues & Solutions

### Issue 1: Role is Not 'admin'
**Symptom**: Error message appears even though you think you're admin

**Solution**:
```sql
-- Update your user role to admin
UPDATE users 
SET role = 'admin' 
WHERE username = 'your_username';
```

Then log out and log back in.

### Issue 2: Session Not Updated
**Symptom**: Database shows role = 'admin' but still can't approve

**Solution**:
1. Clear browser cookies for the site
2. Log out
3. Close browser completely
4. Open browser again
5. Log in
6. Try again

### Issue 3: Batch Status Not 'pending'
**Symptom**: Batch shows as 'approved' or other status

**Solution**:
```sql
-- Check current status
SELECT id, batch_number, status FROM batches WHERE id = YOUR_BATCH_ID;

-- If needed, reset to pending
UPDATE batches 
SET status = 'pending', 
    approved_by = NULL, 
    approved_at = NULL 
WHERE id = YOUR_BATCH_ID;
```

### Issue 4: PO Not Approved
**Symptom**: Error about PO not being approved

**Solution**:
1. Go to Purchase Orders page
2. Find the PO linked to your batch
3. Approve the PO first
4. Then try approving the batch

### Issue 5: Cache Issue
**Symptom**: Code changes not taking effect

**Solution**:
```bash
# Clear CodeIgniter cache
rm -rf writable/cache/*

# Or through browser
php spark cache:clear
```

## Step-by-Step Debugging

### Step 1: Verify User Info
```sql
SELECT * FROM users WHERE id = YOUR_USER_ID;
```
Expected: `role = 'admin'`

### Step 2: Verify Session
Add this temporarily to any controller:
```php
echo '<pre>';
print_r(session()->get());
echo '</pre>';
die();
```
Expected: `'role' => 'admin'`

### Step 3: Verify Batch
```sql
SELECT b.*, po.po_number, po.status as po_status, po.approved_by as po_approved_by
FROM batches b
LEFT JOIN purchase_orders po ON po.id = b.purchase_order_id
WHERE b.id = YOUR_BATCH_ID;
```
Expected: 
- `b.status = 'pending'`
- `po.status = 'approved'`
- `po.approved_by` has a value

### Step 4: Test the Function Directly
Add this to BatchController temporarily:
```php
public function testApproval($batchId)
{
    $userId = session()->get('user_id');
    $role = session()->get('role');
    
    echo "User ID: " . $userId . "<br>";
    echo "Role: " . $role . "<br>";
    echo "Is Admin? " . ($role === 'admin' ? 'YES' : 'NO') . "<br>";
    
    $result = $this->batchModel->canUserApproveBatch($batchId, $userId);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    die();
}
```

Access: `/batches/testApproval/YOUR_BATCH_ID`

## Manual Override (Emergency)

If nothing else works, you can manually approve the batch:

```sql
-- Get current user ID
SELECT id FROM users WHERE username = 'your_username';

-- Manually approve the batch
UPDATE batches 
SET status = 'approved',
    approved_by = YOUR_USER_ID,
    approved_at = NOW()
WHERE id = YOUR_BATCH_ID;
```

⚠️ **Warning**: This bypasses the approval workflow. Use only as last resort.

## What to Check in Code

### File: app/Models/BatchModel.php (line 199)
```php
if ($userRole === 'admin') {
    return ['can_approve' => true, 'message' => 'Admin can approve this batch'];
}
```

This should be present. If not, the fix wasn't applied.

### File: app/Controllers/BatchController.php (line 239)
```php
$canApprove = $this->batchModel->canUserApproveBatch($id, $userId);
if (!$canApprove['can_approve']) {
    session()->setFlashdata('error', $canApprove['message']);
    return redirect()->back();
}
```

This calls the check. The error message you see comes from here.

## Still Not Working?

### Check These:

1. **Are you on the right environment?**
   - Development vs Production
   - Different database?

2. **Is the code actually deployed?**
   - Check file modification time
   - Verify the changes are in the file

3. **Is there a different approval method being used?**
   - Check if there's a WorkflowController
   - Check routes for batch approval

4. **Browser Issues?**
   - Try incognito/private mode
   - Try different browser
   - Clear all cookies

## Get More Info

Run this SQL to see everything about your situation:

```sql
SELECT 
    'USER INFO' as section,
    u.id as user_id,
    u.username,
    u.role,
    u.status
FROM users u
WHERE u.username = 'your_username'

UNION ALL

SELECT 
    'PENDING BATCHES' as section,
    b.id,
    b.batch_number,
    b.status,
    CONCAT('PO: ', po.po_number, ' (', po.status, ')')
FROM batches b
LEFT JOIN purchase_orders po ON po.id = b.purchase_order_id
WHERE b.status = 'pending';
```

## Contact Info

If still stuck, provide this info:
1. Your username
2. Your role from database
3. Batch ID you're trying to approve
4. Exact error message
5. Screenshot of the error
6. Contents of latest log file

---

**Last Updated**: January 27, 2025
