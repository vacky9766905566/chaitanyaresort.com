-- Database setup for Chaitanya Resort Visitor Tracking System
-- Run this SQL script in phpMyAdmin to create the database and table

-- Create database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS `chaitanya_resort` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE `chaitanya_resort`;

-- Create visitors table
CREATE TABLE IF NOT EXISTS `visitors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `timestamp` VARCHAR(50) NOT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `contact` VARCHAR(20) DEFAULT NULL,
  `whatsapp_number` VARCHAR(20) DEFAULT NULL,
  `type` VARCHAR(20) DEFAULT NULL COMMENT 'visitor or whatsapp',
  `date` VARCHAR(20) NOT NULL,
  `time` VARCHAR(20) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_type` (`type`),
  INDEX `idx_date` (`date`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Example queries for testing:

-- View all visitors
-- SELECT * FROM visitors ORDER BY created_at DESC;

-- View only regular visitor entries (not WhatsApp clicks)
-- SELECT * FROM visitors WHERE type IS NULL OR type = 'visitor' ORDER BY created_at DESC;

-- View only WhatsApp click entries
-- SELECT * FROM visitors WHERE type = 'whatsapp' ORDER BY created_at DESC;

-- Count total entries
-- SELECT COUNT(*) as total FROM visitors;

-- Count by type
-- SELECT type, COUNT(*) as count FROM visitors GROUP BY type;

