-- =====================================================
-- EXPENSES MODULE - DATABASE MIGRATION SQL
-- =====================================================
-- This SQL script creates the enhanced expenses module
-- with categories, audit logging, and approval workflow
-- =====================================================

-- Drop existing expenses table if exists
DROP TABLE IF EXISTS `expenses`;

-- =====================================================
-- 1. CREATE expense_categories TABLE
-- =====================================================
CREATE TABLE `expense_categories` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 2. CREATE expenses TABLE (Enhanced)
-- =====================================================
CREATE TABLE `expenses` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `expense_number` VARCHAR(50) NOT NULL,
    `expense_date` DATE NOT NULL,
    `category_id` INT(11) UNSIGNED NULL,
    `description` TEXT NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL,
    `vendor_name` VARCHAR(255) NULL,
    `receipt_number` VARCHAR(100) NULL,
    `reference_type` VARCHAR(50) NULL COMMENT 'batch, dispatch, purchase_order, general',
    `reference_id` INT(11) UNSIGNED NULL,
    `notes` TEXT NULL,
    `recorded_by` INT(11) UNSIGNED NOT NULL,
    `approved_by` INT(11) UNSIGNED NULL,
    `approval_status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `approval_date` DATETIME NULL,
    `approval_notes` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `expense_number` (`expense_number`),
    KEY `expense_date` (`expense_date`),
    KEY `category_id` (`category_id`),
    KEY `recorded_by` (`recorded_by`),
    KEY `approval_status` (`approval_status`),
    CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `expenses_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 3. CREATE expense_audit_log TABLE
-- =====================================================
CREATE TABLE `expense_audit_log` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `expense_id` INT(11) UNSIGNED NOT NULL,
    `action` VARCHAR(50) NOT NULL COMMENT 'created, updated, deleted, approved, rejected',
    `user_id` INT(11) UNSIGNED NOT NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `expense_id` (`expense_id`),
    KEY `user_id` (`user_id`),
    KEY `action` (`action`),
    CONSTRAINT `expense_audit_log_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `expense_audit_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- 4. INSERT DEFAULT EXPENSE CATEGORIES
-- =====================================================
INSERT INTO `expense_categories` (`name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
('Transportation', 'Vehicle fuel, maintenance, and transport costs', 1, NOW(), NOW()),
('Storage', 'Warehouse rent, storage fees, and facility costs', 1, NOW(), NOW()),
('Labor', 'Wages, salaries, and labor costs', 1, NOW(), NOW()),
('Equipment', 'Machinery, tools, and equipment purchases', 1, NOW(), NOW()),
('Maintenance', 'Repairs and maintenance of equipment and facilities', 1, NOW(), NOW()),
('Utilities', 'Electricity, water, and other utility bills', 1, NOW(), NOW()),
('Insurance', 'Insurance premiums and coverage costs', 1, NOW(), NOW()),
('Administrative', 'Office supplies, stationery, and administrative expenses', 1, NOW(), NOW()),
('Packaging', 'Bags, containers, and packaging materials', 1, NOW(), NOW()),
('Quality Control', 'Testing, inspection, and quality assurance costs', 1, NOW(), NOW()),
('Marketing', 'Advertising, promotions, and marketing expenses', 1, NOW(), NOW()),
('Other', 'Miscellaneous expenses not covered by other categories', 1, NOW(), NOW());

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================
-- Run these to verify the tables were created successfully

-- Check expense_categories table
SELECT 'expense_categories' AS table_name, COUNT(*) AS record_count FROM expense_categories;

-- Check expenses table structure
SHOW CREATE TABLE expenses;

-- Check expense_audit_log table structure
SHOW CREATE TABLE expense_audit_log;

-- List all categories
SELECT id, name, description, is_active FROM expense_categories ORDER BY name;

-- =====================================================
-- ROLLBACK SCRIPT (if needed)
-- =====================================================
-- Uncomment and run these if you need to rollback

-- DROP TABLE IF EXISTS `expense_audit_log`;
-- DROP TABLE IF EXISTS `expenses`;
-- DROP TABLE IF EXISTS `expense_categories`;

-- =====================================================
-- END OF MIGRATION
-- =====================================================
