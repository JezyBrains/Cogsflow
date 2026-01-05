-- CogsFlow Complete Database Schema
-- Generated: 2026-01-02
-- Database: cogsflow_db

SET FOREIGN_KEY_CHECKS=0;

-- ============================================
-- CORE TABLES
-- ============================================

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'standard_user',
    `first_name` VARCHAR(100) NULL,
    `last_name` VARCHAR(100) NULL,
    `phone` VARCHAR(20) NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `last_login` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_username` (`username`),
    KEY `idx_email` (`email`),
    KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Roles Table
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Permissions Table
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `module` VARCHAR(50) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_name` (`name`),
    KEY `idx_module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Role Permissions Table
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `role_id` INT(11) UNSIGNED NOT NULL,
    `permission_id` INT(11) UNSIGNED NOT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_role_permission` (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User Roles Table
CREATE TABLE IF NOT EXISTS `user_roles` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `role_id` INT(11) UNSIGNED NOT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `assigned_at` DATETIME NULL,
    `assigned_by` INT(11) UNSIGNED NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_role` (`user_id`, `role_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- SUPPLIER MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `suppliers` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `contact_person` VARCHAR(255) NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(20) NULL,
    `address` TEXT NULL,
    `supplier_type` ENUM('grain', 'service', 'equipment', 'other') DEFAULT 'grain',
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `payment_terms` VARCHAR(100) NULL,
    `credit_limit` DECIMAL(15,2) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_name` (`name`),
    KEY `idx_status` (`status`),
    KEY `idx_supplier_type` (`supplier_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- PURCHASE ORDER MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `purchase_orders` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `po_number` VARCHAR(50) NOT NULL UNIQUE,
    `supplier_id` INT(11) UNSIGNED NOT NULL,
    `grain_type` VARCHAR(100) NOT NULL,
    `quantity_mt` DECIMAL(10,2) NOT NULL,
    `unit_price` DECIMAL(10,2) NOT NULL,
    `total_amount` DECIMAL(15,2) NOT NULL,
    `order_date` DATE NOT NULL,
    `expected_delivery_date` DATE NULL,
    `status` ENUM('pending', 'approved', 'rejected', 'completed', 'cancelled') DEFAULT 'pending',
    `approved_by` INT(11) UNSIGNED NULL,
    `approved_at` DATETIME NULL,
    `notes` TEXT NULL,
    `created_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_po_number` (`po_number`),
    KEY `idx_supplier` (`supplier_id`),
    KEY `idx_status` (`status`),
    FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- BATCH MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `batches` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `batch_number` VARCHAR(50) NOT NULL UNIQUE,
    `purchase_order_id` INT(11) UNSIGNED NOT NULL,
    `supplier_id` INT(11) UNSIGNED NOT NULL,
    `grain_type` VARCHAR(100) NOT NULL,
    `total_weight_kg` DECIMAL(10,2) NOT NULL,
    `total_bags` INT(11) NOT NULL,
    `average_moisture` DECIMAL(5,2) NULL,
    `batch_created_date` DATE NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected', 'in_transit', 'delivered') DEFAULT 'pending',
    `approved_by` INT(11) UNSIGNED NULL,
    `approved_at` DATETIME NULL,
    `notes` TEXT NULL,
    `created_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_batch_number` (`batch_number`),
    KEY `idx_purchase_order` (`purchase_order_id`),
    KEY `idx_supplier` (`supplier_id`),
    KEY `idx_status` (`status`),
    FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Batch Bags Table
CREATE TABLE IF NOT EXISTS `batch_bags` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `batch_id` INT(11) UNSIGNED NOT NULL,
    `bag_number` INT(11) NOT NULL,
    `weight_kg` DECIMAL(10,2) NOT NULL,
    `moisture_percentage` DECIMAL(5,2) NOT NULL,
    `quality_grade` VARCHAR(20) NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_batch` (`batch_id`),
    KEY `idx_bag_number` (`bag_number`),
    FOREIGN KEY (`batch_id`) REFERENCES `batches`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Batch History Table
CREATE TABLE IF NOT EXISTS `batch_history` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `batch_id` INT(11) UNSIGNED NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `old_status` VARCHAR(50) NULL,
    `new_status` VARCHAR(50) NULL,
    `performed_by` INT(11) UNSIGNED NULL,
    `notes` TEXT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_batch` (`batch_id`),
    FOREIGN KEY (`batch_id`) REFERENCES `batches`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`performed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DISPATCH MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `dispatches` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dispatch_number` VARCHAR(50) NOT NULL UNIQUE,
    `batch_id` INT(11) UNSIGNED NOT NULL,
    `vehicle_number` VARCHAR(50) NOT NULL,
    `driver_name` VARCHAR(100) NOT NULL,
    `driver_phone` VARCHAR(20) NOT NULL,
    `driver_id_number` VARCHAR(50) NULL,
    `destination` VARCHAR(255) NOT NULL,
    `dispatch_date` DATE NOT NULL,
    `expected_arrival_date` DATE NULL,
    `actual_arrival_date` DATE NULL,
    `status` ENUM('pending', 'in_transit', 'arrived', 'delivered', 'cancelled') DEFAULT 'pending',
    `receiving_officer` INT(11) UNSIGNED NULL,
    `received_at` DATETIME NULL,
    `notes` TEXT NULL,
    `created_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_dispatch_number` (`dispatch_number`),
    KEY `idx_batch` (`batch_id`),
    KEY `idx_status` (`status`),
    FOREIGN KEY (`batch_id`) REFERENCES `batches`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`receiving_officer`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Vehicle Changes Table
CREATE TABLE IF NOT EXISTS `vehicle_changes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `old_vehicle_number` VARCHAR(50) NOT NULL,
    `new_vehicle_number` VARCHAR(50) NOT NULL,
    `reason` TEXT NULL,
    `changed_by` INT(11) UNSIGNED NULL,
    `changed_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_dispatch` (`dispatch_id`),
    FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bag Inspections Table
CREATE TABLE IF NOT EXISTS `bag_inspections` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `batch_bag_id` INT(11) UNSIGNED NOT NULL,
    `bag_id` VARCHAR(50) NOT NULL,
    `expected_weight_kg` DECIMAL(10,2) NOT NULL,
    `actual_weight_kg` DECIMAL(10,2) NOT NULL,
    `expected_moisture` DECIMAL(5,2) NOT NULL,
    `actual_moisture` DECIMAL(5,2) NOT NULL,
    `condition` ENUM('good', 'damaged', 'wet', 'contaminated') DEFAULT 'good',
    `discrepancy_notes` TEXT NULL,
    `inspected_by` INT(11) UNSIGNED NULL,
    `inspected_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_dispatch` (`dispatch_id`),
    KEY `idx_batch_bag` (`batch_bag_id`),
    KEY `idx_bag_id` (`bag_id`),
    FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`batch_bag_id`) REFERENCES `batch_bags`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`inspected_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bag Discrepancies Table
CREATE TABLE IF NOT EXISTS `bag_discrepancies` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `bag_inspection_id` INT(11) UNSIGNED NOT NULL,
    `discrepancy_type` ENUM('weight', 'moisture', 'quality', 'damage', 'missing') NOT NULL,
    `severity` ENUM('minor', 'moderate', 'major', 'critical') DEFAULT 'minor',
    `description` TEXT NOT NULL,
    `resolution` TEXT NULL,
    `resolved_by` INT(11) UNSIGNED NULL,
    `resolved_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_inspection` (`bag_inspection_id`),
    KEY `idx_type` (`discrepancy_type`),
    FOREIGN KEY (`bag_inspection_id`) REFERENCES `bag_inspections`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`resolved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INVENTORY MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `inventory` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `grain_type` VARCHAR(100) NOT NULL,
    `quantity_kg` DECIMAL(10,2) NOT NULL DEFAULT 0,
    `location` VARCHAR(100) NULL,
    `last_updated` DATETIME NULL,
    `updated_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_grain_type` (`grain_type`),
    FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inventory Movements Table
CREATE TABLE IF NOT EXISTS `inventory_movements` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `inventory_id` INT(11) UNSIGNED NOT NULL,
    `movement_type` ENUM('in', 'out', 'adjustment', 'transfer') NOT NULL,
    `quantity_kg` DECIMAL(10,2) NOT NULL,
    `reference_type` VARCHAR(50) NULL,
    `reference_id` INT(11) UNSIGNED NULL,
    `notes` TEXT NULL,
    `performed_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_inventory` (`inventory_id`),
    KEY `idx_type` (`movement_type`),
    FOREIGN KEY (`inventory_id`) REFERENCES `inventory`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`performed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inventory Adjustments Table
CREATE TABLE IF NOT EXISTS `inventory_adjustments` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `grain_type` VARCHAR(100) NOT NULL,
    `adjustment_type` ENUM('increase', 'decrease') NOT NULL,
    `quantity_kg` DECIMAL(10,2) NOT NULL,
    `reason` TEXT NOT NULL,
    `reference_type` VARCHAR(50) NULL,
    `reference_id` INT(11) UNSIGNED NULL,
    `adjusted_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_grain_type` (`grain_type`),
    KEY `idx_type` (`adjustment_type`),
    FOREIGN KEY (`adjusted_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- WAREHOUSE DOCUMENTS
-- ============================================

CREATE TABLE IF NOT EXISTS `warehouse_receipts` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `received_weight_kg` DECIMAL(10,2) NOT NULL,
    `received_bags` INT(11) NOT NULL,
    `discrepancies` TEXT NULL,
    `received_by` INT(11) UNSIGNED NULL,
    `received_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_receipt_number` (`receipt_number`),
    KEY `idx_dispatch` (`dispatch_id`),
    FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`received_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `delivery_notes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `note_number` VARCHAR(50) NOT NULL UNIQUE,
    `dispatch_id` INT(11) UNSIGNED NOT NULL,
    `delivery_date` DATE NOT NULL,
    `recipient_name` VARCHAR(255) NOT NULL,
    `recipient_signature` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `created_by` INT(11) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_note_number` (`note_number`),
    KEY `idx_dispatch` (`dispatch_id`),
    FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DOCUMENT MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `document_types` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `is_required` BOOLEAN DEFAULT FALSE,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `documents` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_type_id` INT(11) UNSIGNED NOT NULL,
    `reference_type` VARCHAR(50) NOT NULL,
    `reference_id` INT(11) UNSIGNED NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT(11) NULL,
    `uploaded_by` INT(11) UNSIGNED NULL,
    `uploaded_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_reference` (`reference_type`, `reference_id`),
    FOREIGN KEY (`document_type_id`) REFERENCES `document_types`(`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `workflow_document_requirements` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `workflow_stage` VARCHAR(50) NOT NULL,
    `document_type_id` INT(11) UNSIGNED NOT NULL,
    `is_mandatory` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_workflow` (`workflow_stage`),
    FOREIGN KEY (`document_type_id`) REFERENCES `document_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- EXPENSE MANAGEMENT
-- ============================================

CREATE TABLE IF NOT EXISTS `expense_categories` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- NOTIFICATIONS
-- ============================================

CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `data` JSON NULL,
    `priority` ENUM('low', 'normal', 'high', 'critical') DEFAULT 'normal',
    `is_read` BOOLEAN DEFAULT FALSE,
    `read_at` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_type` (`type`),
    KEY `idx_is_read` (`is_read`),
    KEY `idx_created_at` (`created_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notification_types` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `default_enabled` BOOLEAN DEFAULT TRUE,
    `role_specific` JSON NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notification_settings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `notification_type` VARCHAR(50) NOT NULL,
    `enabled` BOOLEAN DEFAULT TRUE,
    `delivery_method` ENUM('in_app', 'email', 'both') DEFAULT 'in_app',
    `sound_enabled` BOOLEAN DEFAULT TRUE,
    `desktop_enabled` BOOLEAN DEFAULT FALSE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_notification` (`user_id`, `notification_type`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- REPORTING
-- ============================================

CREATE TABLE IF NOT EXISTS `reports` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `description` TEXT NULL,
    `category` VARCHAR(50) NULL,
    `allowed_roles` JSON NULL,
    `parameters` JSON NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_type` (`type`),
    KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- SYSTEM SETTINGS
-- ============================================

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NULL,
    `type` VARCHAR(20) DEFAULT 'string',
    `category` VARCHAR(50) DEFAULT 'general',
    `description` TEXT NULL,
    `is_public` BOOLEAN DEFAULT FALSE,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_key` (`key`),
    KEY `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `system_logs` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `level` VARCHAR(20) NOT NULL,
    `message` TEXT NOT NULL,
    `context` JSON NULL,
    `user_id` INT(11) UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_level` (`level`),
    KEY `idx_user` (`user_id`),
    KEY `idx_created_at` (`created_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cache_entries` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` LONGTEXT NOT NULL,
    `expiration` INT(11) UNSIGNED NOT NULL,
    `created_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_key` (`key`),
    KEY `idx_expiration` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- MIGRATIONS TRACKING
-- ============================================

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `version` VARCHAR(255) NOT NULL,
    `class` VARCHAR(255) NOT NULL,
    `group` VARCHAR(255) NOT NULL,
    `namespace` VARCHAR(255) NOT NULL,
    `time` INT(11) NOT NULL,
    `batch` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS=1;

-- ============================================
-- INITIAL DATA SEEDING
-- ============================================

-- Insert default roles
INSERT INTO `roles` (`name`, `display_name`, `description`, `is_active`, `created_at`) VALUES
('admin', 'Administrator', 'Full system access with all permissions', 1, NOW()),
('warehouse_staff', 'Warehouse Staff', 'Inventory management and warehouse operations', 1, NOW()),
('standard_user', 'Standard User', 'Limited read access to basic features', 1, NOW())
ON DUPLICATE KEY UPDATE `display_name` = VALUES(`display_name`);

-- Insert admin user (password: password)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
('admin', 'admin@nipoagro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW())
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Insert notification types
INSERT INTO `notification_types` (`name`, `display_name`, `description`, `default_enabled`, `created_at`) VALUES
('batch_created', 'Batch Created', 'New batch created', 1, NOW()),
('batch_approved', 'Batch Approved', 'Batch approved', 1, NOW()),
('dispatch_created', 'Dispatch Created', 'New dispatch created', 1, NOW()),
('dispatch_arrived', 'Dispatch Arrived', 'Dispatch arrived at destination', 1, NOW()),
('inventory_low', 'Low Inventory', 'Inventory below threshold', 1, NOW()),
('system_alert', 'System Alert', 'System alerts and warnings', 1, NOW())
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`);

-- Insert basic settings
INSERT INTO `settings` (`key`, `value`, `type`, `category`, `description`, `created_at`) VALUES
('company_name', 'Nipo Agro', 'string', 'company', 'Company name', NOW()),
('system_name', 'CogsFlow', 'string', 'system', 'System name', NOW()),
('currency', 'TZS', 'string', 'system', 'Default currency', NOW()),
('timezone', 'Africa/Dar_es_Salaam', 'string', 'system', 'System timezone', NOW()),
('session_timeout', '3600', 'integer', 'security', 'Session timeout in seconds', NOW())
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- Insert expense categories
INSERT INTO `expense_categories` (`name`, `description`, `is_active`, `created_at`) VALUES
('Transport', 'Transportation and logistics expenses', 1, NOW()),
('Storage', 'Warehouse and storage costs', 1, NOW()),
('Labor', 'Labor and personnel costs', 1, NOW()),
('Maintenance', 'Equipment and facility maintenance', 1, NOW()),
('Administrative', 'Administrative and office expenses', 1, NOW())
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`);

-- ============================================
-- END OF SCHEMA
-- ============================================
