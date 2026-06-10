-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2026 at 12:15 AM
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
-- Database: `alp_luciole`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`name`, `username`, `password`) VALUES
('ADMIN', 'admin01', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `price` int(11) DEFAULT NULL,
  `category` enum('Ice Cream','Drinks','Snacks') NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `status` enum('Available','Sold Out') NOT NULL DEFAULT 'Available',
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `price`, `category`, `qty`, `status`, `image`) VALUES
(1, 'Vanilla Ice Cream', 8000, 'Ice Cream', 16, 'Available', '1781090633_McDonald_s_Vanilla_Cone_Ice_Cream.jpg'),
(2, 'Chocolate Ice Cream', 7000, 'Ice Cream', 6, 'Sold Out', '1781094400_WhatsApp_Image_2026-06-10_at_19.25.26.jpeg'),
(3, 'Hojicha', 35000, 'Drinks', 13, 'Available', '1781094504_WhatsApp_Image_2026-06-10_at_19.28.04.jpeg'),
(4, 'Crispy Almond Waffle', 12000, 'Snacks', 32, 'Available', '1781094472_WhatsApp_Image_2026-06-10_at_19.27.20.jpeg'),
(5, 'Matcha Latte', 25000, 'Drinks', 7, 'Available', '1781094555_WhatsApp_Image_2026-06-10_at_19.28.58.jpeg'),
(6, 'Blue Pacific', 18000, 'Ice Cream', 10, 'Available', '1781094438_WhatsApp_Image_2026-06-10_at_19.26.38.jpeg'),
(8, 'Hojicha Large', 20000, 'Drinks', 15, 'Available', '1781096059_WhatsApp_Image_2026-06-10_at_19.28.04.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `order_status` enum('Incoming','In Progress','Ready to Pick Up','Completed') NOT NULL DEFAULT 'Incoming',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `menu_id`, `order_status`, `created_at`) VALUES
(1, 0, 'Incoming', '2026-06-10 02:49:08'),
(2, 0, 'In Progress', '2026-06-10 02:49:08'),
(3, 0, 'Ready to Pick Up', '2026-06-10 02:49:08'),
(4, 0, 'Incoming', '2026-06-10 03:25:36'),
(5, 0, 'Incoming', '2026-06-10 03:25:41'),
(6, 0, 'Incoming', '2026-06-10 03:27:30'),
(7, 0, 'Incoming', '2026-06-10 03:27:33'),
(8, 0, 'In Progress', '2026-06-10 03:29:39'),
(9, 0, 'Incoming', '2026-06-10 10:28:18'),
(10, 0, 'Incoming', '2026-06-10 10:29:01'),
(11, 0, 'Incoming', '2026-06-10 10:48:51'),
(12, 0, 'In Progress', '2026-06-10 10:49:37'),
(13, 0, 'Incoming', '2026-06-10 11:29:58'),
(14, 0, 'Incoming', '2026-06-10 12:23:28'),
(15, 0, 'Incoming', '2026-06-10 12:24:19'),
(16, 0, 'Completed', '2026-06-10 12:41:57'),
(17, 0, 'Incoming', '2026-06-10 12:43:57'),
(18, 0, 'Completed', '2026-06-10 12:47:38'),
(19, 0, 'In Progress', '2026-06-10 12:47:53'),
(20, 0, 'Ready to Pick Up', '2026-06-10 12:48:14'),
(21, 0, 'Completed', '2026-06-10 12:48:58'),
(22, 0, 'Completed', '2026-06-10 12:52:48'),
(23, 0, 'Incoming', '2026-06-10 20:41:02');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--

CREATE TABLE `transaction_detail` (
  `detail_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_detail`
--

INSERT INTO `transaction_detail` (`detail_id`, `transaction_id`, `menu_id`, `qty`, `price`) VALUES
(1, 1, 1, 2, 0),
(2, 2, 2, 1, 0),
(3, 3, 4, 3, 0),
(4, 6, 1, 1, 15000),
(5, 7, 1, 1, 15000),
(6, 8, 4, 1, 15000),
(7, 9, 4, 1, 15000),
(8, 10, 1, 3, 15000),
(9, 11, 1, 1, 15000),
(10, 11, 4, 1, 15000),
(11, 12, 1, 1, 15000),
(12, 12, 5, 1, 15000),
(13, 13, 6, 1, 15000),
(15, 15, 5, 2, 25000),
(16, 15, 4, 1, 12000),
(17, 16, 4, 2, 12000),
(18, 16, 2, 1, 10000),
(19, 17, 6, 1, 18000),
(20, 18, 2, 1, 10000),
(21, 18, 6, 1, 18000),
(22, 19, 2, 1, 10000),
(23, 20, 4, 1, 12000),
(24, 20, 3, 1, 35000),
(25, 21, 2, 2, 10000),
(26, 21, 4, 1, 12000),
(27, 22, 6, 2, 18000),
(28, 22, 2, 1, 10000),
(29, 23, 3, 1, 35000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD CONSTRAINT `transaction_detail_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_detail_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
