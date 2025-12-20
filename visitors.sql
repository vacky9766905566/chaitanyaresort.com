-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2025 at 10:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chaitanya_resort`
--

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL COMMENT 'visitor or whatsapp',
  `date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `timestamp`, `name`, `contact`, `whatsapp_number`, `type`, `date`, `time`, `created_at`) VALUES
(1, '2025-12-20T09:18:09.356Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '20/12/2025', '14:48:09', '2025-12-20 09:18:09'),
(2, '2025-12-20T09:18:19.905Z', NULL, NULL, '919421297851', 'whatsapp', '20/12/2025', '14:48:19', '2025-12-20 09:18:19'),
(3, '2025-12-20T09:19:42.538Z', 'Jyoti Kadam', '9112680201', NULL, 'visitor', '20/12/2025', '14:49:42', '2025-12-20 09:19:42'),
(4, '2025-12-20T09:19:45.464Z', NULL, NULL, '919112680201', 'whatsapp', '20/12/2025', '14:49:45', '2025-12-20 09:19:45'),
(5, '2025-12-20T09:27:20.241Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '20/12/2025', '14:57:20', '2025-12-20 09:27:20'),
(6, '2025-12-20T09:27:29.465Z', 'Vikramsinh Kadam', '9766905566', '918390347209', 'whatsapp', '20/12/2025', '14:57:29', '2025-12-20 09:27:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_timestamp` (`timestamp`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
