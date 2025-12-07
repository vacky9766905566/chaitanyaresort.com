-- Chaitanya Resort Database Schema

CREATE DATABASE IF NOT EXISTS chaitanya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE chaitanya;

-- Users table (for customers and admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    room_name VARCHAR(100) NOT NULL,
    description TEXT,
    max_occupancy INT DEFAULT 2,
    price_per_day DECIMAL(10, 2) NOT NULL DEFAULT 2000.00,
    extra_bed_price DECIMAL(10, 2) NOT NULL DEFAULT 300.00,
    has_ac BOOLEAN DEFAULT FALSE,
    has_attached_bathroom BOOLEAN DEFAULT TRUE,
    check_in_time TIME DEFAULT '14:00:00',
    check_out_time TIME DEFAULT '12:00:00',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    number_of_days INT NOT NULL,
    number_of_guests INT NOT NULL DEFAULT 2,
    extra_beds INT DEFAULT 0,
    room_price DECIMAL(10, 2) NOT NULL,
    extra_bed_price DECIMAL(10, 2) DEFAULT 0.00,
    total_price DECIMAL(10, 2) NOT NULL,
    guest_name VARCHAR(100) NOT NULL,
    guest_email VARCHAR(100) NOT NULL,
    guest_phone VARCHAR(20) NOT NULL,
    special_requests TEXT,
    payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    payment_link VARCHAR(255),
    booking_status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    INDEX idx_check_in (check_in_date),
    INDEX idx_check_out (check_out_date),
    INDEX idx_room_date (room_id, check_in_date, check_out_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
-- Password hash for 'admin123': $2y$10$BfEWhncFZBsQgYKqZUnbzeB/dd8VVCJwali0efvkn7zwRhzYkA7.G
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@chaitanyaresort.com', '$2y$10$BfEWhncFZBsQgYKqZUnbzeB/dd8VVCJwali0efvkn7zwRhzYkA7.G', 'admin')
ON DUPLICATE KEY UPDATE name=name;

-- Insert 6 rooms
INSERT INTO rooms (room_number, room_name, description, max_occupancy) VALUES
('R001', 'Room 1', 'Comfortable room with attached bathroom', 2),
('R002', 'Room 2', 'Comfortable room with attached bathroom', 2),
('R003', 'Room 3', 'Comfortable room with attached bathroom', 2),
('R004', 'Room 4', 'Comfortable room with attached bathroom', 2),
('R005', 'Room 5', 'Comfortable room with attached bathroom', 2),
('R006', 'Room 6', 'Comfortable room with attached bathroom', 2)
ON DUPLICATE KEY UPDATE room_name=room_name;
