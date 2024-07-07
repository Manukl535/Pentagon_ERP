-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2024 at 07:50 PM
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
-- Table structure for table `associates`
--

CREATE TABLE `associates` (
  `count` int(11) NOT NULL,
  `id` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `department` varchar(20) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `associates`
--

INSERT INTO `associates` (`count`, `id`, `username`, `department`, `barcode`, `created_at`, `updated_at`) VALUES
(1, '21078', 'rohits', 'inventory', 'BARCODEb02954498de843bbe2677f9fefbfebcd', '2024-06-30 13:21:13', '2024-06-30 17:32:06'),
(2, '21079', 'ruhis', 'receipt', 'BARCODE0c144d6cb34c65273f7a2edc2b37c2f2', '2024-06-30 13:21:13', '2024-06-30 17:32:18'),
(3, '21123', 'srishal', 'pickpack', 'BARCODE4cbb818c631acb8d47b7dd19d8e3e686', '2024-06-30 13:21:13', '2024-06-30 17:32:26'),
(4, '22403', 'manukl', 'inventory', 'BARCODEbdf4d268f232b3937ff84aa76535444e', '2024-06-30 13:21:13', '2024-06-30 17:32:31');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `associate_id` varchar(10) DEFAULT NULL,
  `department` varchar(30) NOT NULL,
  `attendance_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `associate_id`, `department`, `attendance_time`, `remarks`) VALUES
(1, '22403', 'Inventory', '2024-06-30 17:26:52', 'Reported at 10');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `audit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `article_no` varchar(255) DEFAULT NULL,
  `qty_23_24` varchar(100) NOT NULL,
  `audit_quantity` varchar(100) NOT NULL,
  `audit_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`audit_id`, `user_id`, `user_name`, `location`, `article_no`, `qty_23_24`, `audit_quantity`, `audit_timestamp`) VALUES
(2, 141108, 'Manu', 'A01', 'APT101', '100', '10', '2024-06-15 20:17:34'),
(134, 141108, 'Manu', 'A02', 'APT101', '30', '30', '2024-06-15 20:17:34'),
(135, 141108, 'Manu', 'A03', 'APT101', '20', '20', '2024-06-15 20:17:34'),
(136, 141108, 'Manu', 'A04', 'APT101', '15', '15', '2024-06-15 20:17:34'),
(137, 141108, 'Manu', 'B01', 'APT101', '30', '30', '2024-06-15 20:17:34'),
(138, 141108, 'Manu', 'B02', 'APT101', '30', '30', '2024-06-15 20:17:34'),
(139, 141108, 'Manu', 'B03', 'APT101', '20', '20', '2024-06-15 20:17:34'),
(140, 141108, 'Manu', 'B04', 'APT101', '15', '15', '2024-06-15 20:17:34'),
(141, 141108, 'Manu', 'C01', 'APS102', '50', '50', '2024-06-15 20:17:34'),
(142, 141108, 'Manu', 'C02', 'APS102', '30', '30', '2024-06-15 20:17:34'),
(143, 141108, 'Manu', 'C03', 'APS102', '20', '20', '2024-06-15 20:17:34'),
(144, 141108, 'Manu', 'C04', 'APS102', '15', '15', '2024-06-15 20:17:34'),
(145, 141108, 'Manu', 'D01', 'APS102', '50', '50', '2024-06-15 20:17:34'),
(146, 141108, 'Manu', 'D02', 'APS102', '30', '30', '2024-06-15 20:17:34'),
(147, 141108, 'Manu', 'D03', 'APS102', '20', '20', '2024-06-15 20:17:34'),
(148, 141108, 'Manu', 'D04', 'APS102', '15', '15', '2024-06-15 20:17:34'),
(149, 141108, 'Manu', 'E01', 'APP103', '50', '50', '2024-06-15 20:17:34'),
(150, 141108, 'Manu', 'E02', 'APP103', '30', '30', '2024-06-15 20:17:34'),
(151, 141108, 'Manu', 'E03', 'APP103', '20', '20', '2024-06-15 20:17:34'),
(152, 141108, 'Manu', 'E04', 'APP103', '15', '15', '2024-06-15 20:17:34'),
(153, 141108, 'Manu', 'F01', 'APP103', '50', '50', '2024-06-15 20:17:34'),
(154, 141108, 'Manu', 'F02', 'APP103', '30', '30', '2024-06-15 20:17:34'),
(155, 141108, 'Manu', 'F03', 'APP103', '20', '20', '2024-06-15 20:17:34'),
(156, 141108, 'Manu', 'F04', 'APP103', '15', '15', '2024-06-15 20:17:34'),
(157, 141108, 'Manu', 'G01', 'APK104', '50', '50', '2024-06-15 20:17:34'),
(158, 141108, 'Manu', 'G02', 'APK104', '30', '30', '2024-06-15 20:17:34'),
(159, 141108, 'Manu', 'G03', 'APK104', '20', '20', '2024-06-15 20:17:34'),
(160, 141108, 'Manu', 'G04', 'APK104', '15', '15', '2024-06-15 20:17:34'),
(161, 141108, 'Manu', 'H01', 'APK104', '50', '50', '2024-06-15 20:17:34'),
(162, 141108, 'Manu', 'H02', 'APK104', '30', '30', '2024-06-15 20:17:34'),
(163, 141108, 'Manu', 'H03', 'APK104', '20', '20', '2024-06-15 20:17:34'),
(164, 141108, 'Manu', 'H04', 'APK104', '15', '15', '2024-06-15 20:17:34'),
(165, 141108, 'Manu', 'I01', 'APJ105', '50', '50', '2024-06-15 20:17:34'),
(166, 141108, 'Manu', 'I02', 'APJ105', '30', '30', '2024-06-15 20:17:34'),
(167, 141108, 'Manu', 'I03', 'APJ105', '20', '20', '2024-06-15 20:17:34'),
(168, 141108, 'Manu', 'I04', 'APJ105', '15', '15', '2024-06-15 20:17:34'),
(169, 141108, 'Manu', 'J01', 'APJ105', '50', '50', '2024-06-15 20:17:34'),
(170, 141108, 'Manu', 'J02', 'APJ105', '30', '30', '2024-06-15 20:17:34'),
(171, 141108, 'Manu', 'J03', 'APJ105', '20', '20', '2024-06-15 20:17:34'),
(172, 141108, 'Manu', 'J04', 'APJ105', '15', '15', '2024-06-15 20:17:34'),
(173, 141108, 'Manu', 'K01', 'APH106', '50', '50', '2024-06-15 20:17:34'),
(174, 141108, 'Manu', 'K02', 'APH106', '30', '30', '2024-06-15 20:17:34'),
(175, 141108, 'Manu', 'K03', 'APH106', '20', '20', '2024-06-15 20:17:34'),
(176, 141108, 'Manu', 'K04', 'APH106', '15', '15', '2024-06-15 20:17:34'),
(177, 141108, 'Manu', 'L01', 'APH106', '50', '50', '2024-06-15 20:17:34'),
(178, 141108, 'Manu', 'L02', 'APH106', '30', '30', '2024-06-15 20:17:34'),
(179, 141108, 'Manu', 'L03', 'APH106', '20', '20', '2024-06-15 20:17:34'),
(180, 141108, 'Manu', 'L04', 'APH106', '15', '15', '2024-06-15 20:17:34'),
(181, 141108, 'Manu', 'L05', 'APH106', '20', '20', '2024-06-15 20:17:34'),
(182, 141108, 'Manu', 'L06', 'APH106', '20', '20', '2024-06-15 20:17:34'),
(183, 141108, 'Manu', 'M01', 'FSA107', '50', '50', '2024-06-15 20:17:34'),
(184, 141108, 'Manu', 'M02', 'FSA107', '30', '30', '2024-06-15 20:17:34'),
(185, 141108, 'Manu', 'M03', 'FSA107', '30', '30', '2024-06-15 20:17:34'),
(186, 141108, 'Manu', 'M04', 'FSA107', '10', '10', '2024-06-15 20:17:34'),
(187, 141108, 'Manu', 'M05', 'FSA107', '20', '20', '2024-06-15 20:17:34'),
(188, 141108, 'Manu', 'N01', 'FSA107', '50', '50', '2024-06-15 20:17:34'),
(189, 141108, 'Manu', 'N02', 'FSA107', '30', '30', '2024-06-15 20:17:34'),
(190, 141108, 'Manu', 'N03', 'FSA107', '30', '30', '2024-06-15 20:17:34'),
(191, 141108, 'Manu', 'N04', 'FSA107', '10', '10', '2024-06-15 20:17:34'),
(192, 141108, 'Manu', 'N05', 'FSA107', '20', '20', '2024-06-15 20:17:34'),
(193, 141108, 'Manu', 'O01', 'FSH108', '50', '50', '2024-06-15 20:17:34'),
(194, 141108, 'Manu', 'O02', 'FSH108', '30', '30', '2024-06-15 20:17:34'),
(195, 141108, 'Manu', 'O03', 'FSH108', '30', '30', '2024-06-15 20:17:34'),
(196, 141108, 'Manu', 'O04', 'FSH108', '10', '10', '2024-06-15 20:17:34'),
(197, 141108, 'Manu', 'O05', 'FSH108', '20', '20', '2024-06-15 20:17:34'),
(198, 141108, 'Manu', 'P01', 'FSH108', '50', '50', '2024-06-15 20:17:34'),
(199, 141108, 'Manu', 'P02', 'FSH108', '30', '30', '2024-06-15 20:17:34'),
(200, 141108, 'Manu', 'P03', 'FSH108', '30', '30', '2024-06-15 20:17:34'),
(201, 141108, 'Manu', 'P04', 'FSH108', '10', '10', '2024-06-15 20:17:34'),
(202, 141108, 'Manu', 'P05', 'FSH108', '20', '20', '2024-06-15 20:17:34'),
(203, 141108, 'Manu', 'Q01', 'FHL109', '50', '50', '2024-06-15 20:17:34'),
(204, 141108, 'Manu', 'Q02', 'FHL109', '30', '30', '2024-06-15 20:17:34'),
(205, 141108, 'Manu', 'Q03', 'FHL109', '30', '30', '2024-06-15 20:17:34'),
(206, 141108, 'Manu', 'Q04', 'FHL109', '10', '10', '2024-06-15 20:17:34'),
(207, 141108, 'Manu', 'Q05', 'FHL109', '20', '20', '2024-06-15 20:17:34'),
(208, 141108, 'Manu', 'R01', 'FHL109', '50', '50', '2024-06-15 20:17:34'),
(209, 141108, 'Manu', 'R02', 'FHL109', '30', '30', '2024-06-15 20:17:34'),
(210, 141108, 'Manu', 'R03', 'FHL109', '30', '30', '2024-06-15 20:17:34'),
(211, 141108, 'Manu', 'R04', 'FHL109', '10', '10', '2024-06-15 20:17:34'),
(212, 141108, 'Manu', 'R05', 'FHL109', '20', '20', '2024-06-15 20:17:34'),
(213, 141108, 'Manu', 'S01', 'FCR110', '50', '50', '2024-06-15 20:17:34'),
(214, 141108, 'Manu', 'S02', 'FCR110', '30', '30', '2024-06-15 20:17:34'),
(215, 141108, 'Manu', 'S03', 'FCR110', '30', '30', '2024-06-15 20:17:34'),
(216, 141108, 'Manu', 'S04', 'FCR110', '10', '10', '2024-06-15 20:17:34'),
(217, 141108, 'Manu', 'S05', 'FCR110', '20', '20', '2024-06-15 20:17:34'),
(218, 141108, 'Manu', 'T01', 'FCR110', '50', '50', '2024-06-15 20:17:34'),
(219, 141108, 'Manu', 'T02', 'FCR110', '30', '30', '2024-06-15 20:17:34'),
(220, 141108, 'Manu', 'T03', 'FCR110', '30', '30', '2024-06-15 20:17:34'),
(221, 141108, 'Manu', 'T04', 'FCR110', '10', '10', '2024-06-15 20:17:34'),
(222, 141108, 'Manu', 'T05', 'FCR110', '20', '20', '2024-06-15 20:17:34'),
(223, 141108, 'Manu', 'U01', 'FSC111', '50', '50', '2024-06-15 20:17:34'),
(224, 141108, 'Manu', 'U02', 'FSC111', '30', '30', '2024-06-15 20:17:34'),
(225, 141108, 'Manu', 'U03', 'FSC111', '30', '30', '2024-06-15 20:17:34'),
(226, 141108, 'Manu', 'U04', 'FSC111', '10', '10', '2024-06-15 20:17:34'),
(227, 141108, 'Manu', 'U05', 'FSC111', '20', '20', '2024-06-15 20:17:34'),
(228, 141108, 'Manu', 'V01', 'FSC111', '50', '50', '2024-06-15 20:17:34'),
(229, 141108, 'Manu', 'V02', 'FSC111', '30', '30', '2024-06-15 20:17:34'),
(230, 141108, 'Manu', 'V03', 'FSC111', '30', '30', '2024-06-15 20:17:34'),
(231, 141108, 'Manu', 'V04', 'FSC111', '10', '10', '2024-06-15 20:17:34'),
(232, 141108, 'Manu', 'V05', 'FSC111', '20', '20', '2024-06-15 20:17:34'),
(233, 141108, 'Manu', 'W01', 'ASG112', '50', '50', '2024-06-15 20:17:34'),
(234, 141108, 'Manu', 'W02', 'ASG112', '30', '30', '2024-06-15 20:17:34'),
(235, 141108, 'Manu', 'W03', 'ASG113', '30', '30', '2024-06-15 20:17:34'),
(236, 141108, 'Manu', 'W04', 'ASG113', '10', '10', '2024-06-15 20:17:34'),
(237, 141108, 'Manu', 'W05', 'ASG114', '20', '20', '2024-06-15 20:17:34'),
(238, 141108, 'Manu', 'W06', 'ASG114', '50', '50', '2024-06-15 20:17:34'),
(239, 141108, 'Manu', 'X01', 'AHT115', '30', '30', '2024-06-15 20:17:34'),
(240, 141108, 'Manu', 'X02', 'AHT115', '30', '30', '2024-06-15 20:17:34'),
(241, 141108, 'Manu', 'X03', 'AHT115', '10', '10', '2024-06-15 20:17:34'),
(242, 141108, 'Manu', 'X04', 'AHT116', '20', '20', '2024-06-15 20:17:34'),
(243, 141108, 'Manu', 'X05', 'AHT116', '40', '40', '2024-06-15 20:17:34'),
(244, 141108, 'Manu', 'X06', 'AHT116', '30', '30', '2024-06-15 20:17:34'),
(245, 141108, 'Manu', 'Y01', 'AWT117', '30', '30', '2024-06-15 20:17:34'),
(246, 141108, 'Manu', 'Y02', 'AWT117', '10', '10', '2024-06-15 20:17:34'),
(247, 141108, 'Manu', 'Y03', 'AWT117', '20', '20', '2024-06-15 20:17:34'),
(248, 141108, 'Manu', 'Y04', 'AWT118', '40', '40', '2024-06-15 20:17:34'),
(249, 141108, 'Manu', 'Y05', 'AWT118', '30', '30', '2024-06-15 20:17:34'),
(250, 141108, 'Manu', 'Y06', 'AWT118', '30', '30', '2024-06-15 20:17:34'),
(251, 141108, 'Manu', 'Y07', 'AHB119', '10', '10', '2024-06-15 20:17:34'),
(252, 141108, 'Manu', 'Y08', 'AHB119', '20', '20', '2024-06-15 20:17:34'),
(253, 141108, 'Manu', 'Y09', 'AHB119', '40', '40', '2024-06-15 20:17:34'),
(254, 141108, 'Manu', 'Y10', 'AHB120', '30', '30', '2024-06-15 20:17:34'),
(255, 141108, 'Manu', 'Y11', 'AHB120', '30', '30', '2024-06-15 20:17:34'),
(256, 141108, 'Manu', 'Y12', 'AHB120', '10', '10', '2024-06-15 20:17:34'),
(257, 141108, 'Manu', 'Z01', 'AWC121', '20', '20', '2024-06-15 20:17:34'),
(258, 141108, 'Manu', 'Z02', 'AWC121', '40', '40', '2024-06-15 20:17:34'),
(259, 141108, 'Manu', 'Z03', 'AWC121', '30', '30', '2024-06-15 20:17:34'),
(260, 141108, 'Manu', 'Z04', 'AWC122', '30', '30', '2024-06-15 20:17:34'),
(261, 141108, 'Manu', 'Z05', 'AWC122', '10', '10', '2024-06-15 20:17:34'),
(262, 141108, 'Manu', 'Z06', 'AWC122', '20', '20', '2024-06-15 20:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `cc_data`
--

CREATE TABLE `cc_data` (
  `count` int(11) NOT NULL,
  `cc_id` varchar(50) NOT NULL,
  `associate_name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `available_qty` varchar(50) DEFAULT NULL,
  `scanned_qty` varchar(50) DEFAULT NULL,
  `assigned_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cc_data`
--

INSERT INTO `cc_data` (`count`, `cc_id`, `associate_name`, `location`, `available_qty`, `scanned_qty`, `assigned_on`) VALUES
(41, 'A010324', 'ruhis', 'A01', '100', NULL, '2024-07-03 17:38:07');

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
-- Table structure for table `deletion_logs`
--

CREATE TABLE `deletion_logs` (
  `count` int(10) NOT NULL,
  `location` varchar(10) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deletion_logs`
--

INSERT INTO `deletion_logs` (`count`, `location`, `remarks`) VALUES
(1, 'A001', 'No use'),
(2, 'A01', 'No more usage'),
(3, 'A001', 'No more usage'),
(4, 'A002', 'No more usage'),
(5, 'A001', 'No more usage');

-- --------------------------------------------------------

--
-- Table structure for table `inv_location`
--

CREATE TABLE `inv_location` (
  `id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `article_no` varchar(11) NOT NULL,
  `article_description` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `available_quantity` int(11) DEFAULT NULL,
  `article_size` varchar(10) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_location`
--

INSERT INTO `inv_location` (`id`, `location`, `capacity`, `article_no`, `article_description`, `color`, `available_quantity`, `article_size`, `category`) VALUES
(1, 'A01', 100, 'APT101', 'Men T-shirt', 'White', 100, 'S', 'Apparel'),
(2, 'A02', 60, 'APT101', 'Men T-shirt\n', 'White', 30, 'M', 'Apparel'),
(3, 'A03', 50, 'APT101', 'Men T-shirt\n', 'White', 20, 'L', 'Apparel'),
(4, 'A04', 30, 'APT101', 'Men T-shirt\n', 'White', 15, 'XL', 'Apparel'),
(5, 'B01', 100, 'APT101', 'Men T-shirt\n', 'Blue', 30, 'S', 'Apparel'),
(6, 'B02', 60, 'APT101', 'Men T-shirt\n', 'Blue', 30, 'M', 'Apparel'),
(7, 'B03', 50, 'APT101', 'Men T-shirt\n', 'Blue', 20, 'L', 'Apparel'),
(8, 'B04', 30, 'APT101', 'Men T-shirt\n', 'Blue', 15, 'XL', 'Apparel'),
(9, 'C01', 100, 'APS102', 'Men Shirt\n', 'White', 50, 'S', 'Apparel'),
(10, 'C02', 60, 'APS102', 'Men Shirt\n', 'White', 30, 'M', 'Apparel'),
(11, 'C03', 50, 'APS102', 'Men Shirt', 'White', 20, 'L', 'Apparel'),
(12, 'C04', 30, 'APS102', 'Men Shirt', 'White', 15, 'XL', 'Apparel'),
(13, 'D01', 100, 'APS102', 'Men Shirt\n', 'Blue', 50, 'S', 'Apparel'),
(14, 'D02', 60, 'APS102', 'Men Shirt', 'Blue', 30, 'M', 'Apparel'),
(15, 'D03', 50, 'APS102', 'Men Shirt', 'Blue', 20, 'L', 'Apparel'),
(16, 'D04', 30, 'APS102', 'Men Shirt', 'Blue', 15, 'XL', 'Apparel'),
(17, 'E01', 100, 'APP103', 'Men Pant', 'White', 50, 'S', 'Apparel'),
(18, 'E02', 60, 'APP103', 'Men Pant', 'White', 30, 'M', 'Apparel'),
(19, 'E03', 50, 'APP103', 'Men Pant', 'White', 20, 'L', 'Apparel'),
(20, 'E04', 30, 'APP103', 'Men Pant', 'White', 15, 'XL', 'Apparel'),
(21, 'F01', 100, 'APP103', 'Men Pant', 'Blue', 50, 'S', 'Apparel'),
(22, 'F02', 60, 'APP103', 'Men Pant', 'Blue', 30, 'M', 'Apparel'),
(23, 'F03', 50, 'APP103', 'Men Pant', 'Blue', 20, 'L', 'Apparel'),
(24, 'F04', 30, 'APP103', 'Men Pant', 'Blue', 15, 'XL', 'Apparel'),
(25, 'G01', 100, 'APK104', 'Women Kurta', 'Black', 50, 'S', 'Apparel'),
(26, 'G02', 60, 'APK104', 'Women Kurta', 'Black', 30, 'M', 'Apparel'),
(27, 'G03', 50, 'APK104', 'Women Kurta', 'Black', 20, 'L', 'Apparel'),
(28, 'G04', 30, 'APK104', 'Women Kurta', 'Black', 15, 'XL', 'Apparel'),
(29, 'H01', 100, 'APK104', 'Women Kurta', 'RED', 50, 'S', 'Apparel'),
(30, 'H02', 60, 'APK104', 'Women Kurta', 'RED', 30, 'M', 'Apparel'),
(31, 'H03', 50, 'APK104', 'Women Kurta', 'RED', 20, 'L', 'Apparel'),
(32, 'H04', 30, 'APK104', 'Women Kurta', 'RED', 15, 'XL', 'Apparel'),
(33, 'I01', 100, 'APJ105', 'Men Pant', 'Black', 50, 'S', 'Apparel'),
(34, 'I02', 60, 'APJ105', 'Men Pant', 'Black', 30, 'M', 'Apparel'),
(35, 'I03', 50, 'APJ105', 'Men Pant', 'Black', 20, 'L', 'Apparel'),
(36, 'I04', 30, 'APJ105', 'Men Pant', 'Black', 15, 'XL', 'Apparel'),
(37, 'J01', 100, 'APJ105', 'Women Pant', 'Blue', 50, 'S', 'Apparel'),
(38, 'J02', 60, 'APJ105', 'Women Pant', 'Blue', 30, 'M', 'Apparel'),
(39, 'J03', 50, 'APJ105', 'Women Pant', 'Blue', 20, 'L', 'Apparel'),
(40, 'J04', 30, 'APJ105', 'Women Pant', 'Blue', 15, 'XL', 'Apparel'),
(41, 'K01', 100, 'APH106', 'Men Hoodie', 'Blue', 50, 'S', 'Apparel'),
(42, 'K02', 60, 'APH106', 'Men Hoodie', 'Blue', 30, 'M', 'Apparel'),
(43, 'K03', 50, 'APH106', 'Men Hoodie', 'Blue', 20, 'L', 'Apparel'),
(44, 'K04', 30, 'APH106', 'Men Hoodie', 'Blue', 15, 'XL', 'Apparel'),
(45, 'L01', 100, 'APH106', 'Women Hoodie', 'Lavender', 50, 'S', 'Apparel'),
(46, 'L02', 60, 'APH106', 'Women Hoodie', 'Lavender', 30, 'M', 'Apparel'),
(47, 'L03', 50, 'APH106', 'Women Hoodie', 'Lavender', 20, 'L', 'Apparel'),
(48, 'L04', 30, 'APH106', 'Women Hoodie', 'Lavender', 15, 'XL', 'Apparel'),
(49, 'L05', 30, 'APH106', 'Women Hoodie', 'Blue', 20, 'XXL', 'Apparel'),
(50, 'L06', 30, 'APH106', 'Men Hoodie', 'Lavender', 20, 'XXL', 'Apparel'),
(51, 'M01', 100, 'FSA107', 'Men Sandals', 'Black', 50, '7', 'Footwear'),
(52, 'M02', 50, 'FSA107', 'Men Sandals', 'Black', 30, '8', 'Footwear'),
(53, 'M03', 60, 'FSA107', 'Men Sandals', 'Black', 30, '9', 'Footwear'),
(54, 'M04', 30, 'FSA107', 'Men Sandals', 'Black', 10, '10', 'Footwear'),
(55, 'M05', 40, 'FSA107', 'Men Sandals', 'Black', 20, '11', 'Footwear'),
(56, 'N01', 100, 'FSA107', 'Women Sandals', 'Gold', 50, '7', 'Footwear'),
(57, 'N02', 50, 'FSA107', 'Women Sandals', 'Gold', 30, '8', 'Footwear'),
(58, 'N03', 60, 'FSA107', 'Women Sandals', 'Gold', 30, '9', 'Footwear'),
(59, 'N04', 30, 'FSA107', 'Women Sandals', 'Gold', 10, '10', 'Footwear'),
(60, 'N05', 40, 'FSA107', 'Women Sandals', 'Gold', 20, '11', 'Footwear'),
(61, 'O01', 100, 'FSH108', 'Men Shoes', 'White', 50, '7', 'Footwear'),
(62, 'O02', 50, 'FSH108', 'Men Shoes', 'White', 30, '8', 'Footwear'),
(63, 'O03', 60, 'FSH108', 'Men Shoes', 'White', 30, '9', 'Footwear'),
(64, 'O04', 30, 'FSH108', 'Men Shoes', 'White', 10, '10', 'Footwear'),
(65, 'O05', 40, 'FSH108', 'Men Shoes', 'White', 20, '11', 'Footwear'),
(66, 'P01', 100, 'FSH108', 'Women Shoes', 'Black', 50, '7', 'Footwear'),
(67, 'P02', 50, 'FSH108', 'Women Shoes', 'Black', 30, '8', 'Footwear'),
(68, 'P03', 60, 'FSH108', 'Women Shoes', 'Black', 30, '9', 'Footwear'),
(69, 'P04', 30, 'FSH108', 'Women Shoes', 'Black', 10, '10', 'Footwear'),
(70, 'P05', 40, 'FSH108', 'Women Shoes', 'Black', 20, '11', 'Footwear'),
(71, 'Q01', 100, 'FHL109', 'Women Heals', 'Gold', 50, '7', 'Footwear'),
(72, 'Q02', 50, 'FHL109', 'Women Heals', 'Gold', 30, '8', 'Footwear'),
(73, 'Q03', 60, 'FHL109', 'Women Heals', 'Gold', 30, '9', 'Footwear'),
(74, 'Q04', 30, 'FHL109', 'Women Heals', 'Gold', 10, '10', 'Footwear'),
(75, 'Q05', 40, 'FHL109', 'Women Heals', 'Gold', 20, '11', 'Footwear'),
(76, 'R01', 100, 'FHL109', 'Women Heals', 'Black', 50, '7', 'Footwear'),
(77, 'R02', 50, 'FHL109', 'Women Heals', 'Black', 30, '8', 'Footwear'),
(78, 'R03', 60, 'FHL109', 'Women Heals', 'Black', 30, '9', 'Footwear'),
(79, 'R04', 30, 'FHL109', 'Women Heals', 'Black', 10, '10', 'Footwear'),
(80, 'R05', 40, 'FHL109', 'Women Heals', 'Black', 20, '11', 'Footwear'),
(81, 'S01', 100, 'FCR110', 'Women Crocs', 'White', 50, '7', 'Footwear'),
(82, 'S02', 50, 'FCR110', 'Women Crocs', 'White', 30, '8', 'Footwear'),
(83, 'S03', 60, 'FCR110', 'Women Crocs', 'White', 30, '9', 'Footwear'),
(84, 'S04', 30, 'FCR110', 'Women Crocs', 'White', 10, '10', 'Footwear'),
(85, 'S05', 40, 'FCR110', 'Women Crocs', 'White', 20, '11', 'Footwear'),
(86, 'T01', 100, 'FCR110', 'Men Crocs', 'White', 50, '7', 'Footwear'),
(87, 'T02', 50, 'FCR110', 'Men Crocs', 'White', 30, '8', 'Footwear'),
(88, 'T03', 60, 'FCR110', 'Men Crocs', 'White', 30, '9', 'Footwear'),
(89, 'T04', 30, 'FCR110', 'Men Crocs', 'White', 10, '10', 'Footwear'),
(90, 'T05', 40, 'FCR110', 'Men Crocs', 'White', 20, '11', 'Footwear'),
(91, 'U01', 100, 'FSC111', 'Socks', 'Black', 50, '6', 'Footwear'),
(92, 'U02', 50, 'FSC111', 'Socks', 'Black', 30, '8', 'Footwear'),
(93, 'U03', 60, 'FSC111', 'Socks', 'Black', 30, '10', 'Footwear'),
(94, 'U04', 30, 'FSC111', 'Socks', 'Black', 10, '12', 'Footwear'),
(95, 'U05', 40, 'FSC111', 'Socks', 'Black', 20, '14', 'Footwear'),
(96, 'V01', 100, 'FSC111', 'Socks', 'White', 50, '6', 'Footwear'),
(97, 'V02', 50, 'FSC111', 'Socks', 'White', 30, '8', 'Footwear'),
(98, 'V03', 60, 'FSC111', 'Socks', 'White', 30, '10', 'Footwear'),
(99, 'V04', 30, 'FSC111', 'Socks', 'White', 10, '12', 'Footwear'),
(100, 'V05', 40, 'FSC111', 'Socks', 'White', 20, '14', 'Footwear'),
(101, 'W01', 100, 'ASG112', 'Round Sunglass', 'White', 50, '115', 'Accessories'),
(102, 'W02', 50, 'ASG112', 'Round Sunglass', 'Blue', 30, '128', 'Accessories'),
(103, 'W03', 60, 'ASG113', 'Rectangle sunglass', 'White', 30, '115', 'Accessories'),
(104, 'W04', 30, 'ASG113', 'Rectangle sunglass', 'Blue', 10, '128', 'Accessories'),
(105, 'W05', 40, 'ASG114', 'Snowline Sunglass', 'White', 20, '115', 'Accessories'),
(106, 'W06', 100, 'ASG114', 'Snowline Sunglass', 'Blue', 50, '128', 'Accessories'),
(107, 'X01', 50, 'AHT115', 'Straw Hat', 'Blue', 30, '6', 'Accessories'),
(108, 'X02', 60, 'AHT115', 'Straw Hat', 'White', 30, '7', 'Accessories'),
(109, 'X03', 30, 'AHT115', 'Straw Hat', 'Sandal', 10, '8', 'Accessories'),
(110, 'X04', 40, 'AHT116', 'Sun Hat', 'Blue', 20, '6', 'Accessories'),
(111, 'X05', 80, 'AHT116', 'Sun Hat', 'White', 40, '7', 'Accessories'),
(112, 'X06', 50, 'AHT116', 'Sun Hat', 'Sandal', 30, '8', 'Accessories'),
(113, 'Y01', 60, 'AWT117', 'Bifold Wallet', 'Black', 30, '2ltr', 'Accessories'),
(114, 'Y02', 30, 'AWT117', 'Bifold Wallet', 'White', 10, '2ltr', 'Accessories'),
(115, 'Y03', 40, 'AWT117', 'Bifold Wallet', 'Brown', 20, '2ltr', 'Accessories'),
(116, 'Y04', 80, 'AWT118', 'Trifold Wallet', 'Black', 40, '2ltr', 'Accessories'),
(117, 'Y05', 50, 'AWT118', 'Trifold Wallet', 'Brown', 30, '2ltr', 'Accessories'),
(118, 'Y06', 60, 'AWT118', 'Trifold Wallet', 'White', 30, '2ltr', 'Accessories'),
(119, 'Y07', 30, 'AHB119', 'Sling Bag', 'Brown', 10, '3ltr', 'Accessories'),
(120, 'Y08', 40, 'AHB119', 'Sling Bag', 'Grey', 20, '3ltr', 'Accessories'),
(121, 'Y09', 80, 'AHB119', 'Sling Bag', 'Blue', 40, '3ltr', 'Accessories'),
(122, 'Y10', 50, 'AHB120', 'Barrel Bag', 'Brown', 30, '3ltr', 'Accessories'),
(123, 'Y11', 60, 'AHB120', 'Barrel Bag', 'Grey', 30, '3ltr', 'Accessories'),
(124, 'Y12', 30, 'AHB120', 'Barrel Bag', 'Blue', 10, '3ltr', 'Accessories'),
(125, 'Z01', 40, 'AWC121', 'Beltstrap Watch', 'Brown', 20, '38', 'Accessories'),
(126, 'Z02', 80, 'AWC121', 'Beltstrap Watch', 'White', 40, '38', 'Accessories'),
(127, 'Z03', 50, 'AWC121', 'Beltstrap Watch', 'Black', 30, '38', 'Accessories'),
(128, 'Z04', 60, 'AWC122', 'Smart Watch', 'Brown', 30, '38', 'Accessories'),
(129, 'Z05', 30, 'AWC122', 'Smart Watch', 'White', 10, '38', 'Accessories'),
(130, 'Z06', 40, 'AWC122', 'Smart Watch', 'Black', 20, '38', 'Accessories'),
(149, 'A001', 100, '', NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mails`
--

CREATE TABLE `mails` (
  `id` int(11) NOT NULL,
  `to_email` varchar(255) NOT NULL,
  `cc_email` varchar(255) DEFAULT NULL,
  `from_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mails`
--

INSERT INTO `mails` (`id`, `to_email`, `cc_email`, `from_email`, `subject`, `message`, `sent_at`, `is_read`) VALUES
(61, 'receipt@pentagon.com', '', 'inventory@pentagon.com', 'manu', 'manu', '2024-06-28 05:44:21', ''),
(62, 'inventory@pentagon.com', '', 'inventory@pentagon.com', 'Re: manu', 'madhu', '2024-06-28 05:45:18', '');

-- --------------------------------------------------------

--
-- Table structure for table `pp_customer`
--

CREATE TABLE `pp_customer` (
  `count` int(11) NOT NULL,
  `customer_id` varchar(75) NOT NULL,
  `customer_name` varchar(75) DEFAULT NULL,
  `company_name` varchar(75) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `gstin` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pp_customer`
--

INSERT INTO `pp_customer` (`count`, `customer_id`, `customer_name`, `company_name`, `address`, `phone`, `email`, `gstin`) VALUES
(1, 'C001', 'Srisha', 'Srisha Fashions', 'HOSA ROAD,BANGALORE', 9343532878, 'sri@gmail.com', '29AABCU9601D1ZQ'),
(2, 'C002', 'Madhu', 'Madhuu Fashions', 'MG ROAD,HOSUR', 7950213425, 'Madhuu2@gmail.com', '36AABCZ1234E1ZP'),
(3, 'C003', 'Sowmya', 'Golden Clothing Solutions', 'JP NAGAR,BANGALORE', 9901234589, 'Sowmya@example.com', '09AAACI2345F1ZJ'),
(4, 'C004', 'Manu', 'Manu Fashions', 'ATTIBELE,BANGLORE', 998876611, 'Manu4@gmail.com', '27AAADP5678G1ZK'),
(5, 'C005', 'Maithili', 'Mangrove Clothing', 'T NAGAR,KOLAR', 9977446583, 'Maithili5@gmail.com', '08AAAEQ9876H1ZL'),
(6, 'C006', 'Aria', 'Elicit Fashions', 'VINAYAKA NAGAR,CHENNAI', 6789546319, 'Aria6@gmail.com', '33AAAFP6543I1ZM'),
(7, 'C007', 'Murali', 'Elite Fabrics', 'ELITE ROAD ,CHENNAI', 7890129902, 'Elite7@gmail.com', '22AAAGS8765J1ZN'),
(8, 'C008', 'Ruhi', 'Ruhi Fashions', 'GANDHI NAGAR, HOSUR', 8901234567, 'Ruhi8@gmail.com', '10AAATH4321K1ZO'),
(9, 'C009', 'Rohit', 'Rohit Designs', 'GAURAV LAYOUT,ATTIBELE', 9012345678, 'Rohitsharma@gmail.com', '18AAAUI9876L1ZP'),
(10, 'C010', 'Shraddha', 'Shravs Garments', 'ABC ROAD,BANGALORE', 6789054321, 'Shravs@gmail.com', '21AAAVJ2345M1ZQ');

-- --------------------------------------------------------

--
-- Table structure for table `trash`
--

CREATE TABLE `trash` (
  `id` int(11) NOT NULL,
  `from_email` varchar(255) NOT NULL,
  `to_email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trash`
--

INSERT INTO `trash` (`id`, `from_email`, `to_email`, `subject`, `message`, `sent_at`, `deleted_at`) VALUES
(14, 'inventory@pentagon.com', 'receipt@pentagon.com', 'asd', 'asdf', '2024-06-28 11:06:29', '2024-06-28 05:41:51'),
(15, 'inventory@pentagon.com', 'receipt@pentagon.com', 'Re: asd', 'dfsdf', '2024-06-28 11:07:28', '2024-06-28 05:44:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `associates`
--
ALTER TABLE `associates`
  ADD PRIMARY KEY (`count`,`id`,`username`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `idx_associates_id` (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `associate_id` (`associate_id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`audit_id`),
  ADD KEY `article_no` (`article_no`);

--
-- Indexes for table `cc_data`
--
ALTER TABLE `cc_data`
  ADD PRIMARY KEY (`count`,`cc_id`),
  ADD UNIQUE KEY `cc_id` (`cc_id`);

--
-- Indexes for table `credentials`
--
ALTER TABLE `credentials`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `deletion_logs`
--
ALTER TABLE `deletion_logs`
  ADD PRIMARY KEY (`count`);

--
-- Indexes for table `inv_location`
--
ALTER TABLE `inv_location`
  ADD PRIMARY KEY (`id`,`article_no`),
  ADD UNIQUE KEY `unique_location` (`location`);

--
-- Indexes for table `mails`
--
ALTER TABLE `mails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pp_customer`
--
ALTER TABLE `pp_customer`
  ADD PRIMARY KEY (`count`,`customer_id`);

--
-- Indexes for table `trash`
--
ALTER TABLE `trash`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `associates`
--
ALTER TABLE `associates`
  MODIFY `count` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT for table `cc_data`
--
ALTER TABLE `cc_data`
  MODIFY `count` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `credentials`
--
ALTER TABLE `credentials`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `deletion_logs`
--
ALTER TABLE `deletion_logs`
  MODIFY `count` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inv_location`
--
ALTER TABLE `inv_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `mails`
--
ALTER TABLE `mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `pp_customer`
--
ALTER TABLE `pp_customer`
  MODIFY `count` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trash`
--
ALTER TABLE `trash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`associate_id`) REFERENCES `associates` (`id`);

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`article_no`) REFERENCES `inv_location` (`article_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
