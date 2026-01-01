-- ============================================
-- STEP 1: Find all foreign key constraint names
-- ============================================

USE johsport_nipo;

-- Find constraints on bag_inspections
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'johsport_nipo'
AND TABLE_NAME = 'bag_inspections'
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Find constraints on inspection_sessions
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'johsport_nipo'
AND TABLE_NAME = 'inspection_sessions'
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- ============================================
-- STEP 2: Copy the constraint names from above results
-- Then uncomment and run these ALTER statements:
-- ============================================

-- For bag_inspections (replace CONSTRAINT_NAME_HERE with actual names):
-- ALTER TABLE `bag_inspections` DROP FOREIGN KEY `CONSTRAINT_NAME_HERE`;
-- ALTER TABLE `bag_inspections` DROP FOREIGN KEY `CONSTRAINT_NAME_HERE`;
-- ALTER TABLE `bag_inspections` DROP FOREIGN KEY `CONSTRAINT_NAME_HERE`;

-- For inspection_sessions (replace CONSTRAINT_NAME_HERE with actual names):
-- ALTER TABLE `inspection_sessions` DROP FOREIGN KEY `CONSTRAINT_NAME_HERE`;
-- ALTER TABLE `inspection_sessions` DROP FOREIGN KEY `CONSTRAINT_NAME_HERE`;
-- ALTER TABLE `inspection_sessions` DROP FOREIGN KEY `CONSTRAINT_NAME_HERE`;

-- ============================================
-- STEP 3: After dropping constraints, drop tables
-- ============================================

-- DROP TABLE IF EXISTS `bag_inspections`;
-- DROP TABLE IF EXISTS `inspection_sessions`;

-- ============================================
-- STEP 4: Then run the CREATE TABLE statements
-- from CREATE_TABLES_NO_FK.sql
-- ============================================
