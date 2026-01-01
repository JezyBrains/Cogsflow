-- ============================================
-- Fix Delivered Dispatches That Weren't Inspected
-- ============================================
-- Run this script to fix dispatches that were marked as "delivered"
-- but never went through the proper inspection process
-- ============================================

-- STEP 1: Check the current situation
-- This shows you which dispatches are problematic
SELECT 
    id,
    dispatch_number,
    status,
    received_by,
    inspection_date,
    created_at,
    CASE 
        WHEN status = 'delivered' AND received_by IS NULL THEN 'NEEDS FIX: Delivered but not inspected'
        WHEN status = 'delivered' AND received_by IS NOT NULL THEN 'OK: Properly inspected'
        ELSE 'OK: Not delivered yet'
    END as diagnosis
FROM dispatches
WHERE status = 'delivered'
ORDER BY created_at DESC;

-- STEP 2: Count how many need fixing
SELECT 
    COUNT(*) as total_delivered,
    SUM(CASE WHEN received_by IS NULL THEN 1 ELSE 0 END) as needs_fix,
    SUM(CASE WHEN received_by IS NOT NULL THEN 1 ELSE 0 END) as properly_inspected
FROM dispatches
WHERE status = 'delivered';

-- STEP 3: Preview what will be changed
-- Run this BEFORE the actual update to see what will change
SELECT 
    id,
    dispatch_number,
    status as current_status,
    'arrived' as new_status,
    received_by,
    inspection_date
FROM dispatches
WHERE status = 'delivered'
AND received_by IS NULL
AND inspection_date IS NULL;

-- STEP 4: ACTUAL FIX - Update delivered to arrived for uninspected dispatches
-- ⚠️ IMPORTANT: Review the preview above before running this!
-- Uncomment the lines below when you're ready to run the fix:

/*
UPDATE dispatches 
SET status = 'arrived',
    updated_at = NOW()
WHERE status = 'delivered'
AND received_by IS NULL
AND inspection_date IS NULL;
*/

-- STEP 5: Verify the fix worked
-- Run this after the update to confirm
SELECT 
    status,
    COUNT(*) as count,
    SUM(CASE WHEN received_by IS NOT NULL THEN 1 ELSE 0 END) as with_inspection,
    SUM(CASE WHEN received_by IS NULL THEN 1 ELSE 0 END) as without_inspection
FROM dispatches
GROUP BY status
ORDER BY FIELD(status, 'pending', 'in_transit', 'arrived', 'delivered', 'cancelled');

-- STEP 6: Check batch statuses too
-- Batches should match their dispatch status
SELECT 
    b.id as batch_id,
    b.batch_number,
    b.status as batch_status,
    d.id as dispatch_id,
    d.dispatch_number,
    d.status as dispatch_status,
    d.received_by,
    d.inspection_date
FROM batches b
LEFT JOIN dispatches d ON d.batch_id = b.id
WHERE b.status = 'delivered'
AND (d.received_by IS NULL OR d.inspection_date IS NULL)
ORDER BY b.created_at DESC;

-- STEP 7: Fix batch statuses if needed
-- If batches are marked as delivered but dispatches aren't inspected
/*
UPDATE batches b
INNER JOIN dispatches d ON d.batch_id = b.id
SET b.status = 'dispatched',
    b.updated_at = NOW()
WHERE b.status = 'delivered'
AND d.status = 'arrived'
AND d.received_by IS NULL;
*/

-- ============================================
-- INSTRUCTIONS:
-- ============================================
-- 1. Run STEP 1 to see which dispatches need fixing
-- 2. Run STEP 2 to see how many are affected
-- 3. Run STEP 3 to preview the changes
-- 4. If preview looks good, uncomment and run STEP 4
-- 5. Run STEP 5 to verify the fix worked
-- 6. Run STEP 6 to check if batches need fixing too
-- 7. If needed, uncomment and run STEP 7
-- ============================================

-- ROLLBACK PLAN (if something goes wrong):
-- If you need to undo the changes, run this:
/*
UPDATE dispatches 
SET status = 'delivered',
    updated_at = NOW()
WHERE status = 'arrived'
AND received_by IS NULL
AND inspection_date IS NULL
AND updated_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE);
-- This only affects records updated in the last 5 minutes
*/
