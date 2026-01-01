-- =====================================================
-- Bag Tracking System Migration SQL
-- Run this manually on your database to create the enhanced bag tracking tables
-- =====================================================

-- First, let's check what columns exist in batch_bags table
-- DESCRIBE `batch_bags`;

-- Add new columns to existing batch_bags table
-- Note: Placing columns at the end to avoid column name issues
ALTER TABLE `batch_bags` 
ADD COLUMN `bag_id` VARCHAR(50) NULL,
ADD COLUMN `qr_code` VARCHAR(255) NULL,
ADD COLUMN `quality_grade` VARCHAR(20) NULL,
ADD COLUMN `notes` TEXT NULL,
ADD COLUMN `loading_date` DATETIME NULL,
ADD COLUMN `loaded_by` VARCHAR(100) NULL;

-- Create bag_inspections table for receiving inspection tracking
CREATE TABLE `bag_inspections` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `batch_bag_id` INT(11) UNSIGNED NOT NULL,
    `bag_id` VARCHAR(50) NOT NULL,
    `expected_weight_kg` DECIMAL(6,2) NOT NULL,
    `actual_weight_kg` DECIMAL(6,2) NOT NULL,
    `expected_moisture` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `actual_moisture` DECIMAL(5,2) NULL DEFAULT NULL,
    `weight_difference` DECIMAL(6,2) NOT NULL,
    `moisture_difference` DECIMAL(5,2) NULL DEFAULT NULL,
    `condition_status` ENUM('good','damaged','wet','contaminated','missing') NOT NULL DEFAULT 'good',
    `inspection_notes` TEXT NULL,
    `inspected_by` VARCHAR(100) NOT NULL,
    `inspection_date` DATETIME NOT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_dispatch_id` (`dispatch_id`),
    INDEX `idx_batch_bag_id` (`batch_bag_id`),
    INDEX `idx_bag_id` (`bag_id`),
    INDEX `idx_dispatch_batch_bag` (`dispatch_id`, `batch_bag_id`),
    CONSTRAINT `fk_bag_inspections_dispatch` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_bag_inspections_batch_bag` FOREIGN KEY (`batch_bag_id`) REFERENCES `batch_bags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create bag_discrepancies table for tracking issues
CREATE TABLE `bag_discrepancies` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `bag_inspection_id` INT(11) UNSIGNED NOT NULL,
    `discrepancy_type` ENUM('weight_loss','weight_gain','moisture_increase','moisture_decrease','damage','contamination','missing_bag') NOT NULL,
    `severity` ENUM('minor','moderate','major','critical') NOT NULL DEFAULT 'minor',
    `description` TEXT NOT NULL,
    `action_taken` TEXT NULL,
    `resolved` BOOLEAN NOT NULL DEFAULT FALSE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_bag_inspection_id` (`bag_inspection_id`),
    INDEX `idx_discrepancy_type` (`discrepancy_type`),
    INDEX `idx_severity` (`severity`),
    INDEX `idx_resolved` (`resolved`),
    CONSTRAINT `fk_bag_discrepancies_inspection` FOREIGN KEY (`bag_inspection_id`) REFERENCES `bag_inspections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add indexes to batch_bags table for better performance
ALTER TABLE `batch_bags` 
ADD INDEX `idx_bag_id` (`bag_id`),
ADD INDEX `idx_quality_grade` (`quality_grade`),
ADD INDEX `idx_loading_date` (`loading_date`);

-- Update migrations table to record this migration (optional)
INSERT INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`) 
VALUES ('2025-01-21-000001', 'App\\Database\\Migrations\\EnhanceBagTrackingSystem', 'default', 'App', UNIX_TIMESTAMP(), 
    (SELECT COALESCE(MAX(batch), 0) + 1 FROM `migrations` AS m));

-- =====================================================
-- Sample data insertion (optional - for testing)
-- =====================================================

-- Generate sample bag IDs for existing batch_bags (if any exist)
-- This will update existing batch_bags with generated bag IDs
UPDATE `batch_bags` bb
JOIN `batches` b ON b.id = bb.batch_id
SET bb.bag_id = CONCAT(b.batch_number, '-B', LPAD(bb.bag_number, 3, '0'))
WHERE bb.bag_id IS NULL OR bb.bag_id = '';

-- =====================================================
-- Verification queries (run these to check if everything worked)
-- =====================================================

-- Check if new columns were added to batch_bags
-- DESCRIBE `batch_bags`;

-- Check if new tables were created
-- SHOW TABLES LIKE '%bag_%';

-- Count records in new tables
-- SELECT 'bag_inspections' as table_name, COUNT(*) as record_count FROM `bag_inspections`
-- UNION ALL
-- SELECT 'bag_discrepancies' as table_name, COUNT(*) as record_count FROM `bag_discrepancies`;

-- Check foreign key constraints
-- SELECT 
--     TABLE_NAME,
--     COLUMN_NAME,
--     CONSTRAINT_NAME,
--     REFERENCED_TABLE_NAME,
--     REFERENCED_COLUMN_NAME
-- FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
-- WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
--   AND TABLE_NAME IN ('bag_inspections', 'bag_discrepancies');

-- =====================================================
-- Rollback SQL (if you need to undo these changes)
-- =====================================================

/*
-- To rollback these changes, run the following SQL:

-- Drop the new tables
DROP TABLE IF EXISTS `bag_discrepancies`;
DROP TABLE IF EXISTS `bag_inspections`;

-- Remove the new columns from batch_bags
ALTER TABLE `batch_bags` 
DROP COLUMN `loaded_by`,
DROP COLUMN `loading_date`,
DROP COLUMN `notes`,
DROP COLUMN `quality_grade`,
DROP COLUMN `qr_code`,
DROP COLUMN `bag_id`;

-- Remove the migration record
DELETE FROM `migrations` WHERE `class` = 'App\\Database\\Migrations\\EnhanceBagTrackingSystem';
*/
