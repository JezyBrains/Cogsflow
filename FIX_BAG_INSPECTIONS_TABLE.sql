-- ============================================
-- FIX bag_inspections table structure
-- The table has wrong columns - needs to be recreated
-- ============================================

USE johsport_nipo;

-- Drop the old incorrect table
DROP TABLE IF EXISTS `bag_inspections`;

-- Create with CORRECT structure
CREATE TABLE `bag_inspections` (
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
    `has_discrepancy` TINYINT(1) DEFAULT 0,
    `inspection_status` ENUM('pending', 'inspected', 'skipped') DEFAULT 'pending',
    `inspection_notes` TEXT NULL,
    `photo_path` VARCHAR(255) NULL,
    `voice_note_path` VARCHAR(255) NULL,
    `inspected_by` INT(11) UNSIGNED NULL,
    `inspected_at` DATETIME NULL,
    `inspection_duration_seconds` INT(11) NULL,
    `qr_scanned` TINYINT(1) DEFAULT 0,
    `scan_timestamp` DATETIME NULL,
    `device_info` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    INDEX `idx_dispatch_id` (`dispatch_id`),
    INDEX `idx_batch_id` (`batch_id`),
    INDEX `idx_bag_id` (`bag_id`),
    INDEX `idx_inspection_status` (`inspection_status`),
    INDEX `idx_condition_status` (`condition_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Verify the fix
SELECT '✅ bag_inspections table recreated with correct structure!' AS Status;

SHOW COLUMNS FROM bag_inspections;
