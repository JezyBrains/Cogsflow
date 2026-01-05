-- Minimal Database Schema for Batches Module
-- This creates only the essential tables needed for /batches/new

SET FOREIGN_KEY_CHECKS=0;

-- Users Table (required for authentication and foreign keys)
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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

-- Suppliers Table
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
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

-- Purchase Orders Table
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE `purchase_orders` (
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

-- Batches Table
DROP TABLE IF EXISTS `batches`;
CREATE TABLE `batches` (
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
DROP TABLE IF EXISTS `batch_bags`;
CREATE TABLE `batch_bags` (
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

SET FOREIGN_KEY_CHECKS=1;

-- Insert admin user (password: password)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `is_active`, `created_at`) VALUES
('admin', 'admin@nipoagro.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW());

-- Insert sample supplier
INSERT INTO `suppliers` (`name`, `contact_person`, `email`, `phone`, `supplier_type`, `status`, `created_at`) VALUES
('Sample Grain Supplier', 'John Doe', 'supplier@example.com', '+255712345678', 'grain', 'active', NOW());

-- Insert sample approved purchase order
INSERT INTO `purchase_orders` (`po_number`, `supplier_id`, `grain_type`, `quantity_mt`, `unit_price`, `total_amount`, `order_date`, `status`, `approved_by`, `approved_at`, `created_at`) VALUES
('PO-2026-001', 1, 'Maize', 100.00, 500000.00, 50000000.00, '2026-01-05', 'approved', 1, NOW(), NOW());
