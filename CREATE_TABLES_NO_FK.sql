-- ============================================
-- VERSION WITHOUT FOREIGN KEYS (safest option)
-- Run this if foreign keys keep failing
-- ============================================

USE johsport_nipo;

-- Drop if exists
DROP TABLE IF EXISTS `bag_inspections`;
DROP TABLE IF EXISTS `inspection_sessions`;

-- ============================================
-- Create bag_inspections (NO foreign keys)
-- ============================================
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

-- ============================================
-- Create inspection_sessions (NO foreign keys)
-- ============================================
CREATE TABLE `inspection_sessions` (
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
    INDEX `idx_dispatch_id` (`dispatch_id`),
    INDEX `idx_batch_id` (`batch_id`),
    INDEX `idx_inspector_id` (`inspector_id`),
    INDEX `idx_session_status` (`session_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Verify
-- ============================================
SHOW TABLES LIKE '%inspection%';
DESC bag_inspections;
DESC inspection_sessions;

SELECT 'Tables created successfully WITHOUT foreign keys!' AS Status;
SELECT 'This is fine - the app will still work!' AS Note;
