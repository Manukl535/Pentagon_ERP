-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2024 at 06:17 AM
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
-- Database: `pentagon`
--

-- --------------------------------------------------------

--
-- Table structure for table `credentials`
--

CREATE TABLE `credentials` (
  `user_id` int(10) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credentials`
--

INSERT INTO `credentials` (`user_id`, `phone`, `name`, `email`, `password`) VALUES
(1, '+917022015320', 'Manu', 'admin@pentagon.com', '1122'),
(2, '+917022015320', 'Ruhi', 'receipt@pentagon.com', '1122'),
(3, '+917022015320', 'Rohit', 'dispatch@pentagon.com', '1122'),
(4, '+917022015320', 'Manu', 'hr@pentagon.com', '1122'),
(5, '+917022015320', 'Srisha', 'pickpack@pentagon.com', '1122'),
(6, '+917022015320', 'Manu', 'inventory@pentagon.com', '1122');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_locations`
--

CREATE TABLE `inventory_locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_locations`
--

INSERT INTO `inventory_locations` (`id`, `name`, `description`, `capacity`) VALUES
(1, 'Location 1', 'Main Warehouse', 1000),
(2, 'Location 2', 'Storage Room A', 500),
(3, 'Location 3', 'Storage Room B', 750),
(4, 'Location 4', 'Backroom', 300),
(5, 'Location 5', 'Shelf 1', 100),
(6, 'Location 6', 'Shelf 2', 100),
(7, 'Location 7', 'Shelf 3', 100),
(8, 'Location 8', 'Shelf 4', 100),
(9, 'Location 9', 'Shelf 5', 100),
(10, 'Location 10', 'Overflow Area', 200);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `inventory_locations`
--
ALTER TABLE `inventory_locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory_locations`
--
ALTER TABLE `inventory_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
