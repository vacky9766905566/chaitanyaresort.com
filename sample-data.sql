-- Sample data for chaitanya_resort database
-- This file contains sample visitor and WhatsApp click data for testing
-- Run this SQL script in phpMyAdmin after creating the database and table

USE `chaitanya_resort`;

-- Insert sample visitor data (regular visitors with name and contact)
INSERT INTO `visitors` (`timestamp`, `name`, `contact`, `whatsapp_number`, `type`, `date`, `time`) VALUES
('2025-12-20T10:30:15.123Z', 'Rajesh Kumar', '9876543210', NULL, 'visitor', '20/12/2025', '16:00:15'),
('2025-12-20T11:15:30.456Z', 'Priya Sharma', '9876543211', NULL, 'visitor', '20/12/2025', '16:45:30'),
('2025-12-20T12:00:45.789Z', 'Amit Patel', '9876543212', NULL, 'visitor', '20/12/2025', '17:30:45'),
('2025-12-20T13:20:10.012Z', 'Sunita Desai', '9876543213', NULL, 'visitor', '20/12/2025', '18:50:10'),
('2025-12-20T14:45:25.345Z', 'Vikram Singh', '9876543214', NULL, 'visitor', '20/12/2025', '20:15:25'),
('2025-12-21T09:30:00.678Z', 'Anjali Mehta', '9876543215', NULL, 'visitor', '21/12/2025', '15:00:00'),
('2025-12-21T10:15:35.901Z', 'Rohit Joshi', '9876543216', NULL, 'visitor', '21/12/2025', '15:45:35'),
('2025-12-21T11:00:50.234Z', 'Kavita Reddy', '9876543217', NULL, 'visitor', '21/12/2025', '16:30:50'),
('2025-12-21T12:30:15.567Z', 'Nikhil Agarwal', '9876543218', NULL, 'visitor', '21/12/2025', '18:00:15'),
('2025-12-21T13:45:40.890Z', 'Meera Iyer', '9876543219', NULL, 'visitor', '21/12/2025', '19:15:40'),
('2025-12-22T08:20:05.123Z', 'Arjun Nair', '9876543220', NULL, 'visitor', '22/12/2025', '13:50:05'),
('2025-12-22T09:10:20.456Z', 'Shreya Menon', '9876543221', NULL, 'visitor', '22/12/2025', '14:40:20'),
('2025-12-22T10:30:45.789Z', 'Ravi Kapoor', '9876543222', NULL, 'visitor', '22/12/2025', '16:00:45'),
('2025-12-22T11:50:10.012Z', 'Deepa Krishnan', '9876543223', NULL, 'visitor', '22/12/2025', '17:20:10'),
('2025-12-22T13:15:35.345Z', 'Suresh Venkat', '9876543224', NULL, 'visitor', '22/12/2025', '18:45:35');

-- Insert sample WhatsApp click tracking data
INSERT INTO `visitors` (`timestamp`, `name`, `contact`, `whatsapp_number`, `type`, `date`, `time`) VALUES
('2025-12-20T10:35:20.123Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '16:05:20'),
('2025-12-20T10:36:45.456Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '16:06:45'),
('2025-12-20T11:20:10.789Z', NULL, NULL, '918390347209', 'whatsapp', '20/12/2025', '16:50:10'),
('2025-12-20T12:05:30.012Z', NULL, NULL, '919112680201', 'whatsapp', '20/12/2025', '17:35:30'),
('2025-12-20T13:25:55.345Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '18:55:55'),
('2025-12-20T14:50:20.678Z', NULL, NULL, '918390347209', 'whatsapp', '20/12/2025', '20:20:20'),
('2025-12-21T09:35:05.901Z', NULL, NULL, '919112680201', 'whatsapp', '21/12/2025', '15:05:05'),
('2025-12-21T10:20:40.234Z', NULL, NULL, '919421297851', 'whatsapp', '21/12/2025', '15:50:40'),
('2025-12-21T11:05:15.567Z', NULL, NULL, '918390347209', 'whatsapp', '21/12/2025', '16:35:15'),
('2025-12-21T12:35:50.890Z', NULL, NULL, '919112680201', 'whatsapp', '21/12/2025', '18:05:50'),
('2025-12-21T13:50:25.123Z', NULL, NULL, '919421297851', 'whatsapp', '21/12/2025', '19:20:25'),
('2025-12-22T08:25:00.456Z', NULL, NULL, '918390347209', 'whatsapp', '22/12/2025', '13:55:00'),
('2025-12-22T09:15:30.789Z', NULL, NULL, '919112680201', 'whatsapp', '22/12/2025', '14:45:30'),
('2025-12-22T10:35:55.012Z', NULL, NULL, '919421297851', 'whatsapp', '22/12/2025', '16:05:55'),
('2025-12-22T11:55:20.345Z', NULL, NULL, '918390347209', 'whatsapp', '22/12/2025', '17:25:20'),
('2025-12-22T13:20:45.678Z', NULL, NULL, '919112680201', 'whatsapp', '22/12/2025', '18:50:45');

-- Verify the data was inserted
SELECT 
    COUNT(*) as total_records,
    SUM(CASE WHEN type = 'visitor' THEN 1 ELSE 0 END) as visitor_count,
    SUM(CASE WHEN type = 'whatsapp' THEN 1 ELSE 0 END) as whatsapp_count
FROM visitors;

-- View sample records
SELECT * FROM visitors ORDER BY created_at DESC LIMIT 10;

