-- ============================================
-- Fix Dispatch Status ENUM and Blank Values
-- Run this SQL to fix the blank status issue
-- ============================================

-- 1. Check current ENUM values (for reference)
SELECT COLUMN_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'dispatches' 
AND COLUMN_NAME = 'status';

-- 2. Alter ENUM to include 'arrived' status
ALTER TABLE dispatches 
MODIFY COLUMN status ENUM('pending', 'in_transit', 'arrived', 'delivered', 'cancelled') 
DEFAULT 'pending';

-- 3. Fix existing blank statuses based on dispatch data

-- Fix dispatches that have arrived (has arrival time but no inspection)
UPDATE dispatches 
SET status = 'arrived' 
WHERE (status = '' OR status IS NULL) 
AND actual_arrival IS NOT NULL 
AND (received_by IS NULL OR received_by = '');

-- Fix dispatches that are delivered (has inspection data)
UPDATE dispatches 
SET status = 'delivered' 
WHERE (status = '' OR status IS NULL) 
AND received_by IS NOT NULL 
AND received_by != '';

-- Fix dispatches that are in transit (has departure but no arrival)
UPDATE dispatches 
SET status = 'in_transit' 
WHERE (status = '' OR status IS NULL) 
AND actual_departure IS NOT NULL 
AND (actual_arrival IS NULL OR actual_arrival = '');

-- Fix remaining dispatches to pending
UPDATE dispatches 
SET status = 'pending' 
WHERE status = '' OR status IS NULL;

-- 4. Verify the fix
SELECT 
    status, 
    COUNT(*) as count,
    GROUP_CONCAT(dispatch_number ORDER BY id SEPARATOR ', ') as dispatch_numbers
FROM dispatches 
GROUP BY status
ORDER BY 
    FIELD(status, 'pending', 'in_transit', 'arrived', 'delivered', 'cancelled');

-- 5. Check for any remaining blank statuses (should be 0)
SELECT COUNT(*) as blank_status_count 
FROM dispatches 
WHERE status = '' OR status IS NULL;

-- 6. Show all dispatches with their current status
SELECT 
    id,
    dispatch_number,
    status,
    actual_departure,
    actual_arrival,
    received_by,
    created_at
FROM dispatches 
ORDER BY id DESC
LIMIT 20;
