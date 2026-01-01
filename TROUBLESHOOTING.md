# 🔧 Troubleshooting Guide

## Error: "Call to a member function getResult() on false"

This error means the database query is failing. Here's how to fix it:

---

## ✅ Step 1: Verify Tables Exist

### Option A: Using phpMyAdmin or MySQL Workbench
1. Open your database tool
2. Select your database (probably `johsport_nipo`)
3. Look for these tables:
   - `bag_inspections`
   - `inspection_sessions`

### Option B: Using SQL Query
Run this in your database:
```sql
SHOW TABLES LIKE '%inspection%';
```

**Expected Result:**
```
bag_inspections
inspection_sessions
```

**If you don't see these tables**, continue to Step 2.

---

## ✅ Step 2: Create Tables Manually

If tables don't exist, run this SQL in your database:

```sql
-- Create bag_inspections table
CREATE TABLE IF NOT EXISTS `bag_inspections` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `batch_id` INT(11) UNSIGNED NOT NULL,
    `bag_id` VARCHAR(50) NOT NULL,
    `bag_number` INT(11) NOT NULL,
    `expected_weight_kg` DECIMAL(10,2) NULL,
    `expected_moisture` DECIMAL(5,2) NULL,
    `actual_weight_kg` DECIMAL(10,2) NULL,
    `actual_moisture` DECIMAL(5,2) NULL,
    `weight_variance_kg` DECIMAL(10,2) NULL,
    `weight_variance_percent` DECIMAL(5,2) NULL,
    `moisture_variance` DECIMAL(5,2) NULL,
    `condition_status` ENUM('good', 'damaged', 'wet', 'contaminated', 'missing') DEFAULT 'good',
    `has_discrepancy` BOOLEAN DEFAULT FALSE,
    `inspection_status` ENUM('pending', 'inspected', 'skipped') DEFAULT 'pending',
    `inspection_notes` TEXT NULL,
    `photo_path` VARCHAR(255) NULL,
    `voice_note_path` VARCHAR(255) NULL,
    `inspected_by` INT(11) UNSIGNED NULL,
    `inspected_at` DATETIME NULL,
    `inspection_duration_seconds` INT(11) NULL,
    `qr_scanned` BOOLEAN DEFAULT FALSE,
    `scan_timestamp` DATETIME NULL,
    `device_info` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_dispatch_id` (`dispatch_id`),
    KEY `idx_batch_id` (`batch_id`),
    KEY `idx_bag_id` (`bag_id`),
    KEY `idx_inspection_status` (`inspection_status`),
    KEY `idx_condition_status` (`condition_status`),
    CONSTRAINT `fk_bag_inspections_dispatch` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bag_inspections_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bag_inspections_user` FOREIGN KEY (`inspected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create inspection_sessions table
CREATE TABLE IF NOT EXISTS `inspection_sessions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `batch_id` INT(11) UNSIGNED NOT NULL,
    `inspector_id` INT(11) UNSIGNED NOT NULL,
    `started_at` DATETIME NULL,
    `completed_at` DATETIME NULL,
    `paused_at` DATETIME NULL,
    `total_duration_seconds` INT(11) NULL,
    `total_bags_expected` INT(11) DEFAULT 0,
    `total_bags_inspected` INT(11) DEFAULT 0,
    `total_bags_skipped` INT(11) DEFAULT 0,
    `total_discrepancies` INT(11) DEFAULT 0,
    `expected_total_weight_kg` DECIMAL(10,2) NULL,
    `actual_total_weight_kg` DECIMAL(10,2) NULL,
    `weight_variance_percent` DECIMAL(5,2) NULL,
    `session_status` ENUM('in_progress', 'completed', 'paused', 'cancelled') DEFAULT 'in_progress',
    `device_type` VARCHAR(50) NULL,
    `inspection_mode` VARCHAR(50) NULL,
    `session_notes` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_dispatch_id` (`dispatch_id`),
    KEY `idx_batch_id` (`batch_id`),
    KEY `idx_inspector_id` (`inspector_id`),
    KEY `idx_session_status` (`session_status`),
    CONSTRAINT `fk_inspection_sessions_dispatch` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_inspection_sessions_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_inspection_sessions_inspector` FOREIGN KEY (`inspector_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## ✅ Step 3: Verify Tables Created

Run this to confirm:
```sql
-- Check tables exist
SHOW TABLES LIKE '%inspection%';

-- Check structure
DESC bag_inspections;
DESC inspection_sessions;

-- Verify they're empty (should return 0)
SELECT COUNT(*) FROM bag_inspections;
SELECT COUNT(*) FROM inspection_sessions;
```

---

## ✅ Step 4: Test Again

1. Clear browser cache
2. Navigate to: `/batch-receiving/inspection/9`
3. Should work now!

---

## 🔍 Still Getting Errors?

### Check 1: Foreign Key Issues

If you get foreign key errors, it means referenced tables don't exist. Check:

```sql
-- Verify these tables exist
SHOW TABLES LIKE 'dispatches';
SHOW TABLES LIKE 'batches';
SHOW TABLES LIKE 'users';
```

If any are missing, you have bigger database issues.

### Check 2: Database Connection

The error message showed: `johsport_nipo` database.

Verify in `app/Config/Database.php`:
```php
'database' => 'johsport_nipo',  // Should match your actual database name
'username' => 'your_username',
'password' => 'your_password',
```

### Check 3: Table Permissions

Make sure your database user has permissions:
```sql
GRANT ALL PRIVILEGES ON johsport_nipo.* TO 'your_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## 🎯 Quick Verification Script

Run the file `verify_tables.sql` in your database to check everything at once.

---

## 📝 What Changed

The code now has better error handling:
- If tables don't exist, you'll see a friendly error message
- Error: "Bag inspection system not initialized. Please run: php spark migrate"
- No more cryptic "getResult() on false" errors

---

## ✅ Success Indicators

You'll know it's working when:
1. ✅ No errors when accessing `/batch-receiving/inspection/9`
2. ✅ You see a visual bag grid
3. ✅ Stats dashboard shows: Total Bags, Inspected, Pending, Issues
4. ✅ You can click on bag cards

---

## 🆘 Still Stuck?

Check the logs:
```bash
tail -f writable/logs/log-*.php
```

Look for:
- "Bag inspection tables not found"
- Any database connection errors
- Foreign key constraint errors

---

**Most Common Issue:** Tables weren't created because SQL wasn't run in the correct database.

**Solution:** Make sure you're in the `johsport_nipo` database before running the CREATE TABLE statements!
