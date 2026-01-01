-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 01, 2026 at 03:41 PM
-- Server version: 10.5.26-MariaDB-cll-lve-log
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `youngtur_chaitanya_resort`
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
(14, '2025-12-20T09:57:55.570Z', 'Rahul', '8275615616', NULL, 'visitor', '20/12/2025', '15:27:55', '2025-12-20 09:57:56'),
(23, '2025-12-20T11:09:11.619Z', 'Shruti Pawar', '7385191223', NULL, 'visitor', '20/12/2025', '16:39:11', '2025-12-20 11:09:12'),
(32, '2025-12-20T16:22:38.551Z', 'Rahul', '8275615616', NULL, 'visitor', '20/12/2025', '21:52:38', '2025-12-20 16:22:42'),
(33, '2025-12-20T16:23:50.341Z', 'Rahul', '8275615616', NULL, 'visitor', '20/12/2025', '21:53:50', '2025-12-20 16:23:53'),
(34, '2025-12-20T16:23:50.341Z', 'Rahul', '8275615616', NULL, 'visitor', '20/12/2025', '21:53:50', '2025-12-20 16:24:19'),
(37, '2025-12-22T06:45:23.212Z', 'Swapnil P Borse, PhD', '8160983540', NULL, 'visitor', '22/12/2025', '12:15:23', '2025-12-22 06:45:28'),
(38, '2025-12-22T07:02:31.550Z', 'Swapnil P Borse, PhD', '8160983540', NULL, 'visitor', '22/12/2025', '12:32:31', '2025-12-22 07:02:32'),
(39, '2025-12-22T13:57:39.897Z', 'Test name', '9860419252', NULL, 'visitor', '22/12/2025', '19:27:39', '2025-12-22 13:57:41'),
(40, '2025-12-23T06:16:30.682Z', 'Pooja Rajendra Salgude', '7262871312', NULL, 'visitor', '23/12/2025', '11:46:30', '2025-12-23 06:16:33'),
(41, '2025-12-27T05:35:30.592Z', 'Manoj Gangawane', '9096318163', NULL, 'visitor', '27/12/2025', '11:05:30', '2025-12-27 05:35:30'),
(42, '2025-12-27T05:35:30.592Z', 'Manoj Gangawane', '9096318163', NULL, 'visitor', '27/12/2025', '11:05:30', '2025-12-27 05:35:44'),
(43, '2025-12-28T12:52:54.113Z', 'शशिकांत', '8806504041', NULL, 'visitor', '28/12/2025', '18:22:54', '2025-12-28 12:52:54'),
(44, '2025-12-29T09:15:44.686Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '29/12/2025', '14:45:44', '2025-12-29 09:15:52'),
(45, '2025-12-29T09:15:44.686Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '29/12/2025', '14:45:44', '2025-12-29 09:16:52'),
(46, '2025-12-29T09:16:59.639Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '29/12/2025', '14:46:59', '2025-12-29 09:17:33'),
(47, '2025-12-29T09:18:20.399Z', 'Vikramsinh Kadam', '9766905566', '919421297851', 'whatsapp', '29/12/2025', '14:48:20', '2025-12-29 09:18:22'),
(48, '2025-12-29T09:28:31.711Z', NULL, NULL, '918390347209', 'whatsapp', '29/12/2025', '14:58:31', '2025-12-29 09:28:33'),
(49, '2025-12-29T09:28:39.283Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '29/12/2025', '14:58:39', '2025-12-29 09:28:40'),
(50, '2025-12-29T09:29:06.839Z', 'Vikramsinh Kadam', '9766905566', '919421297851', 'whatsapp', '29/12/2025', '14:59:06', '2025-12-29 09:29:08'),
(51, '2025-12-29T09:30:18.459Z', 'Vikramsinh Kadam', '9766905566', '917768962339', 'whatsapp', '29/12/2025', '15:00:18', '2025-12-29 09:30:22'),
(52, '2025-12-30T22:15:36.263Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '31/12/2025', '03:45:36', '2025-12-30 22:15:36'),
(53, '2025-12-30T22:15:42.491Z', 'Vikramsinh Kadam', '9766905566', '919112680201', 'whatsapp', '31/12/2025', '03:45:42', '2025-12-30 22:15:43'),
(54, '2025-12-31T09:59:42.914Z', 'Pallavi kadam', '9766048750', NULL, 'visitor', '31/12/2025', '15:29:42', '2025-12-31 09:59:43'),
(55, '2025-12-31T09:59:58.994Z', 'Pallavi kadam', '9766048750', NULL, 'visitor', '31/12/2025', '15:29:58', '2025-12-31 09:59:59'),
(56, '2026-01-01T04:29:51.936Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '01/01/2026', '09:59:51', '2026-01-01 04:29:52'),
(57, '2026-01-01T04:29:51.936Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '01/01/2026', '09:59:51', '2026-01-01 04:30:43'),
(58, '2026-01-01T04:37:12.697Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '01/01/2026', '10:07:12', '2026-01-01 04:37:13'),
(59, '2026-01-01T04:37:42.616Z', 'Vikramsinh Kadam', '9766905566', NULL, 'visitor', '01/01/2026', '10:07:42', '2026-01-01 04:37:42'),
(60, '2026-01-01T04:38:25.188Z', 'vugukh', '7679697896', NULL, 'visitor', '01/01/2026', '10:08:25', '2026-01-01 04:38:25'),
(61, '2026-01-01T04:38:38.341Z', 'vugukh', '7679697896', '919112680201', 'whatsapp', '01/01/2026', '10:08:38', '2026-01-01 04:38:39');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
