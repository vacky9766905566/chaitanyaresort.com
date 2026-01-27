-- Feedback/Testimonial Table Creation Script
-- Run this script to create the feedbacks table in your database

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL COMMENT 'Rating from 1 to 5',
  `message` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved' COMMENT 'Moderation status - default approved for instant display',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data (optional - remove if not needed)
-- INSERT INTO `feedbacks` (`name`, `email`, `contact`, `rating`, `message`, `status`) VALUES
-- ('John Doe', 'john@example.com', '9876543210', 5, 'Great resort with amazing beach views!', 'approved'),
-- ('Jane Smith', 'jane@example.com', '9876543211', 4, 'Loved the stay, will come again.', 'approved');
