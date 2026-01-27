-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 02:32 PM
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
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL COMMENT 'Rating from 1 to 5',
  `message` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved' COMMENT 'Moderation status - default approved for instant display',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `name`, `email`, `contact`, `rating`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Vikramsinh Kadam', 'jyotipwr1991@gmail.com', '8888888888', 5, 'SCCSCS', 'approved', '2026-01-27 13:25:03', '2026-01-27 13:29:59'),
(2, 'Vikramsinh Kadam', 'jyotipwr1991@gmail.com', '8888888888', 5, 'ASDCVD', 'approved', '2026-01-27 13:28:08', '2026-01-27 13:29:59'),
(3, 'Vikramsinh Kadam', 'jyotipwr1991@gmail.com', '8888888888', 5, 'LKN', 'approved', '2026-01-27 13:28:56', '2026-01-27 13:29:59'),
(4, 'Vikramsinh Kadam', 'jyotipwr1991@gmail.com', '7666266983', 5, 'SCASDCAD', 'approved', '2026-01-27 13:30:40', '2026-01-27 13:30:40');

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
(1, '2026-01-27T13:30:57.063Z', NULL, NULL, '919421297851', 'whatsapp', '27/01/2026', '19:00:57', '2026-01-27 13:30:57'),
(2, '2026-01-27T13:31:02.243Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '27/01/2026', '19:01:02', '2026-01-27 13:31:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
