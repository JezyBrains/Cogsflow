-- ============================================
-- STEP 1: Make sure you're in the correct database
-- ============================================
USE johsport_nipo;

-- ============================================
-- STEP 2: Create bag_inspections table
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
    KEY `idx_dispatch_id` (`dispatch_id`),
    KEY `idx_batch_id` (`batch_id`),
    KEY `idx_bag_id` (`bag_id`),
    KEY `idx_inspection_status` (`inspection_status`),
    KEY `idx_condition_status` (`condition_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- STEP 3: Create inspection_sessions table
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
    KEY `idx_dispatch_id` (`dispatch_id`),
    KEY `idx_batch_id` (`batch_id`),
    KEY `idx_inspector_id` (`inspector_id`),
    KEY `idx_session_status` (`session_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- STEP 4: Add foreign keys (run AFTER tables created)
-- ============================================
ALTER TABLE `bag_inspections`
    ADD CONSTRAINT `fk_bag_inspections_dispatch` 
        FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_bag_inspections_batch` 
        FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_bag_inspections_user` 
        FOREIGN KEY (`inspected_by`) REFERENCES `users` (`id`) 
        ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `inspection_sessions`
    ADD CONSTRAINT `fk_inspection_sessions_dispatch` 
        FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_inspection_sessions_batch` 
        FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_inspection_sessions_inspector` 
        FOREIGN KEY (`inspector_id`) REFERENCES `users` (`id`) 
        ON DELETE CASCADE ON UPDATE CASCADE;

-- ============================================
-- STEP 5: Verify tables created
-- ============================================
SHOW TABLES LIKE '%inspection%';

-- Should show:
-- bag_inspections
-- inspection_sessions

-- Check structure
DESC bag_inspections;
DESC inspection_sessions;

-- Success message
SELECT 'Tables created successfully!' AS Status;
