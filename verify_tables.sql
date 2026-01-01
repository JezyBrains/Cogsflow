-- Verification Script for Bag Inspection Tables
-- Run this in your MySQL/phpMyAdmin to verify tables exist

-- Check if tables exist
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    CREATE_TIME
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('bag_inspections', 'inspection_sessions');

-- If tables exist, check their structure
DESC bag_inspections;
DESC inspection_sessions;

-- Check for any data
SELECT COUNT(*) as bag_inspection_count FROM bag_inspections;
SELECT COUNT(*) as session_count FROM inspection_sessions;

-- Check foreign keys
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('bag_inspections', 'inspection_sessions')
AND REFERENCED_TABLE_NAME IS NOT NULL;
