-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2020 at 04:39 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengles`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `is_petty_cash` int(1) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `title`, `type`, `description`, `balance`, `is_petty_cash`, `status`, `ts`) VALUES
(1, 'Test', 0, '', '0.00', 0, 1, '2020-02-08 10:21:34');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `admin_type_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `monthly_salary` decimal(10,2) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `admin_type_id`, `username`, `email`, `name`, `monthly_salary`, `password`, `status`, `ts`) VALUES
(1, 1, 'admin', 'vickyali2@hotmail.com', 'Admin', '0.00', 'admin', 1, '2020-02-01 10:09:12');

-- --------------------------------------------------------

--
-- Table structure for table `admin_type`
--

CREATE TABLE `admin_type` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `can_add` int(1) NOT NULL DEFAULT '0',
  `can_edit` int(1) NOT NULL DEFAULT '0',
  `can_delete` int(1) NOT NULL DEFAULT '0',
  `can_read` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_type`
--

INSERT INTO `admin_type` (`id`, `title`, `can_add`, `can_edit`, `can_delete`, `can_read`, `status`, `ts`) VALUES
(1, 'Administrator', 1, 1, 1, 1, 1, '2017-02-27 12:10:38'),
(3, 'Employee', 0, 0, 0, 1, 1, '2017-12-30 22:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `title_urdu` varchar(300) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`id`, `title`, `title_urdu`, `sortorder`, `status`, `ts`) VALUES
(1, 'Copper', 'copper', 2, 1, '2020-02-15 15:38:09'),
(2, 'Golden', 'Golden', 1, 1, '2020-02-15 15:37:53'),
(3, 'Silver', 'silver', 3, 1, '2020-02-15 15:38:19'),
(4, ' Multi', 'multi', 4, 1, '2020-02-15 15:38:41');

-- --------------------------------------------------------

--
-- Table structure for table `config_type`
--

CREATE TABLE `config_type` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `sortorder` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_type`
--

INSERT INTO `config_type` (`id`, `title`, `sortorder`) VALUES
(1, 'General Settings', 1),
(9, 'Invoice Setting', 2);

-- --------------------------------------------------------

--
-- Table structure for table `config_variable`
--

CREATE TABLE `config_variable` (
  `id` int(11) NOT NULL,
  `config_type_id` int(11) NOT NULL,
  `title` varchar(512) NOT NULL,
  `notes` varchar(512) NOT NULL,
  `type` varchar(200) NOT NULL,
  `default_values` text NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  `sortorder` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_variable`
--

INSERT INTO `config_variable` (`id`, `config_type_id`, `title`, `notes`, `type`, `default_values`, `key`, `value`, `sortorder`) VALUES
(1, 1, 'Site URL', '', 'text', '', 'site_url', 'http://localhost/bengles', 2),
(2, 1, 'Site Title', '', 'text', '', 'site_title', 'MK Coatings', 1),
(3, 1, 'Admin Logo', '', 'file', '', 'admin_logo', '', 4),
(10, 1, 'Currency Symbol', '', 'text', '', 'currency_symbol', 'Rs', 5),
(7, 1, 'Admin Email', 'Main Email Address where all the notifications will be sent.', 'text', '', 'admin_email', '', 3),
(18, 1, 'Address/Phone', '', 'editor', '', 'address_phone', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n\r\n</body>\r\n</html>', 6),
(19, 1, 'Header', '', 'editor', '', 'fees_chalan_header', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n\r\n</body>\r\n</html>', 7),
(21, 9, 'Supplier Detail', '', 'editor', '', 'supplier_detail', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n\r\n</body>\r\n</html>', 8),
(22, 1, 'Customer ID', '', 'text', '', 'customer_id', '3', 9);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_name_urdu` varchar(300) NOT NULL,
  `phone` int(20) NOT NULL,
  `address` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_name`, `customer_name_urdu`, `phone`, `address`, `status`, `ts`) VALUES
(1, 'Uzair', '', 0, '', 1, '2020-02-01 10:03:33'),
(2, 'Faiz', '', 2147483647, '', 1, '2020-02-01 10:04:27'),
(3, 'Sajjad Bangles', '', 0, '', 1, '2020-02-15 15:45:39'),
(4, 'Qazi Akram', '', 0, '', 1, '2020-02-15 15:45:45'),
(5, 'Hassan', '', 0, '', 1, '2020-02-15 15:45:50'),
(6, 'Milan', '', 0, '', 1, '2020-02-15 15:45:57'),
(7, 'BURHAN', '', 0, '', 1, '2020-02-19 17:03:49');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payment`
--

CREATE TABLE `customer_payment` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `datetime_added` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `account_id` int(11) NOT NULL,
  `details` varchar(1000) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_payment`
--

INSERT INTO `customer_payment` (`id`, `customer_id`, `datetime_added`, `amount`, `account_id`, `details`, `status`, `ts`) VALUES
(1, 1, '2020-02-04 18:46:00', '44.00', 1, '', 1, '2020-02-20 15:11:16');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `gatepass_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `claim` varchar(50) NOT NULL,
  `labour_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`id`, `gatepass_id`, `date`, `customer_id`, `claim`, `labour_id`, `status`, `ts`) VALUES
(1, 0, '2020-02-02', 1, 'aa', 1, 1, '2020-02-03 10:55:06'),
(2, 6708, '2020-02-20', 3, '', 1, 1, '2020-02-20 14:38:34'),
(3, 2115, '2020-02-20', 6, '', 0, 1, '2020-02-20 15:10:40');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `design_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `extra` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_items`
--

INSERT INTO `delivery_items` (`id`, `delivery_id`, `color_id`, `size_id`, `design_id`, `quantity`, `extra`, `unit_price`, `ts`) VALUES
(1, 1, 1, 1, 1, 3, 2, '6.00', '2020-02-04 13:32:30'),
(2, 1, 2, 2, 1, 2, 2, '10.00', '2020-02-04 13:32:30'),
(3, 2, 4, 1, 8, 100, 200, '150.00', '2020-02-20 14:38:34'),
(4, 2, 4, 2, 8, 50, 200, '150.00', '2020-02-20 14:38:34'),
(5, 2, 4, 3, 8, 300, 200, '150.00', '2020-02-20 14:38:35'),
(6, 2, 4, 4, 8, 180, 200, '150.00', '2020-02-20 14:38:35'),
(7, 2, 4, 5, 8, 200, 200, '150.00', '2020-02-20 14:38:35'),
(8, 2, 4, 6, 8, 140, 200, '150.00', '2020-02-20 14:38:35'),
(9, 2, 4, 7, 8, 40, 200, '150.00', '2020-02-20 14:38:35'),
(10, 2, 4, 8, 8, 30, 200, '150.00', '2020-02-20 14:38:35'),
(11, 2, 4, 9, 8, 5, 200, '150.00', '2020-02-20 14:38:35'),
(12, 2, 4, 10, 8, 20, 200, '150.00', '2020-02-20 14:38:35'),
(13, 2, 4, 1, 5, 50, 140, '80.00', '2020-02-20 14:38:35'),
(14, 2, 4, 2, 5, 40, 140, '80.00', '2020-02-20 14:38:35'),
(15, 2, 4, 3, 5, 170, 140, '80.00', '2020-02-20 14:38:35'),
(16, 2, 4, 4, 5, 180, 140, '80.00', '2020-02-20 14:38:35'),
(17, 2, 4, 5, 5, 90, 140, '80.00', '2020-02-20 14:38:35'),
(18, 2, 4, 6, 5, 110, 140, '80.00', '2020-02-20 14:38:35'),
(19, 2, 4, 7, 5, 225, 140, '80.00', '2020-02-20 14:38:35'),
(20, 2, 4, 8, 5, 35, 140, '80.00', '2020-02-20 14:38:36'),
(21, 2, 4, 9, 5, 60, 140, '80.00', '2020-02-20 14:38:36'),
(22, 2, 4, 10, 5, 115, 140, '80.00', '2020-02-20 14:38:36'),
(23, 3, 1, 1, 8, 25, 2, '140.00', '2020-02-20 15:10:40'),
(24, 3, 1, 2, 8, 10, 2, '140.00', '2020-02-20 15:10:40'),
(25, 3, 1, 3, 8, 30, 2, '140.00', '2020-02-20 15:10:40'),
(26, 3, 1, 4, 8, 40, 2, '140.00', '2020-02-20 15:10:41'),
(27, 3, 1, 5, 8, 30, 2, '140.00', '2020-02-20 15:10:41'),
(28, 3, 1, 6, 8, 20, 2, '140.00', '2020-02-20 15:10:41'),
(29, 3, 1, 7, 8, 10, 2, '140.00', '2020-02-20 15:10:41'),
(30, 3, 1, 8, 8, 10, 2, '140.00', '2020-02-20 15:10:41'),
(31, 3, 1, 9, 8, 5, 2, '140.00', '2020-02-20 15:10:41'),
(32, 3, 1, 10, 8, 20, 2, '140.00', '2020-02-20 15:10:41'),
(33, 3, 3, 1, 6, 22, 6, '150.00', '2020-02-20 15:10:41'),
(34, 3, 3, 2, 6, 42, 6, '150.00', '2020-02-20 15:10:41'),
(35, 3, 3, 3, 6, 29, 6, '150.00', '2020-02-20 15:10:41'),
(36, 3, 3, 4, 6, 34, 6, '150.00', '2020-02-20 15:10:41'),
(37, 3, 3, 5, 6, 33, 6, '150.00', '2020-02-20 15:10:41'),
(38, 3, 3, 6, 6, 33, 6, '150.00', '2020-02-20 15:10:41'),
(39, 3, 3, 7, 6, 30, 6, '150.00', '2020-02-20 15:10:41'),
(40, 3, 3, 8, 6, 15, 6, '150.00', '2020-02-20 15:10:41'),
(41, 3, 3, 9, 6, 18, 6, '150.00', '2020-02-20 15:10:41'),
(42, 3, 3, 10, 6, 30, 6, '150.00', '2020-02-20 15:10:41');

-- --------------------------------------------------------

--
-- Table structure for table `design`
--

CREATE TABLE `design` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `title_urdu` varchar(300) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `design`
--

INSERT INTO `design` (`id`, `title`, `title_urdu`, `sortorder`, `status`, `ts`) VALUES
(1, 'Suraiya', '', 0, 1, '2020-02-15 15:42:09'),
(2, 'Nagin', 'Ã™Â†Ã˜Â§ ÃšÂ¯Ã™Â†', 0, 1, '2020-02-19 16:04:15'),
(3, 'Noor', '', 0, 1, '2020-02-15 15:42:21'),
(4, 'Namakpara', '', 0, 1, '2020-02-15 15:42:56'),
(5, 'Ujala', '', 0, 1, '2020-02-15 15:43:56'),
(6, 'Barfi', '', 0, 1, '2020-02-15 15:44:05'),
(7, 'MORPANKH', 'Ã™Â…Ã™ÂˆÃ˜Â± Ã™Â¾Ã™Â†ÃšÂ©ÃšÂ¾ ', 0, 1, '2020-02-19 16:06:04'),
(8, 'JUGNO', 'Ã˜Â¬ÃšÂ¯Ã™Â†Ã™Âˆ', 0, 1, '2020-02-19 16:03:40');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `phone_number` int(20) NOT NULL,
  `salary_type` tinyint(1) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `over_time_per_hour` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `father_name`, `phone_number`, `salary_type`, `salary`, `over_time_per_hour`, `status`, `ts`) VALUES
(7, 'Umer', 'yousuf', 900786019, 1, '4000.00', '40.00', 1, '2020-02-18 19:47:16'),
(8, 'Zaid', 'Sharif', 0, 0, '20000.00', '20.00', 1, '2020-02-18 19:47:49'),
(9, 'Kamran', 'Ali', 0, 2, '600.00', '10.00', 1, '2020-02-18 19:48:30');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `datetime_added` datetime NOT NULL,
  `expense_category_id` varchar(100) NOT NULL,
  `account_id` int(11) NOT NULL,
  `details` varchar(1000) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `datetime_added`, `expense_category_id`, `account_id`, `details`, `amount`, `added_by`, `status`, `ts`) VALUES
(1, '2020-02-19 20:12:00', '1', 1, '', '1000.00', 1, 1, '2020-02-19 15:12:48');

-- --------------------------------------------------------

--
-- Table structure for table `expense_category`
--

CREATE TABLE `expense_category` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `expense_category`
--

INSERT INTO `expense_category` (`id`, `title`, `status`, `ts`) VALUES
(1, 'Food', 1, '2020-02-19 15:12:11');

-- --------------------------------------------------------

--
-- Table structure for table `incoming`
--

CREATE TABLE `incoming` (
  `id` int(11) NOT NULL,
  `gatepass_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `labour_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `incoming`
--

INSERT INTO `incoming` (`id`, `gatepass_id`, `date`, `customer_id`, `labour_id`, `status`, `ts`) VALUES
(4, 787, '2020-02-01', 2, 1, 1, '2020-02-15 17:44:27'),
(5, 121, '2020-02-15', 5, 0, 1, '2020-02-15 18:22:32'),
(6, 121, '2020-02-15', 5, 0, 1, '2020-02-15 18:22:40'),
(7, 121, '2020-02-15', 5, 4, 1, '2020-02-15 18:24:40'),
(8, 6001, '2020-02-19', 6, 5, 1, '2020-02-19 16:09:07'),
(9, 6002, '2020-02-19', 6, 6, 1, '2020-02-19 16:15:40'),
(10, 6003, '2020-02-19', 7, 7, 1, '2020-02-19 17:04:56'),
(11, 0, '2020-02-20', 7, 1, 1, '2020-02-20 14:42:37'),
(12, 6689, '2020-02-20', 6, 5, 1, '2020-02-20 15:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `incoming_items`
--

CREATE TABLE `incoming_items` (
  `id` int(11) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `design_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `incoming_items`
--

INSERT INTO `incoming_items` (`id`, `incoming_id`, `color_id`, `size_id`, `design_id`, `quantity`, `ts`) VALUES
(12, 4, 2, 2, 1, 71, '2020-02-15 16:29:57'),
(13, 4, 1, 2, 1, 2, '2020-02-15 16:27:39'),
(24, 4, 2, 1, 1, 6, '2020-02-15 16:29:05'),
(25, 4, 1, 1, 1, 1, '2020-02-15 16:27:39'),
(26, 4, 1, 3, 1, 3, '2020-02-15 16:27:39'),
(27, 4, 1, 4, 1, 4, '2020-02-15 16:27:39'),
(28, 4, 1, 5, 1, 5, '2020-02-15 16:27:40'),
(29, 4, 2, 3, 1, 8, '2020-02-15 16:27:40'),
(30, 4, 2, 4, 1, 12, '2020-02-15 16:30:20'),
(31, 4, 2, 5, 1, 10, '2020-02-15 16:27:40'),
(32, 5, 2, 1, 1, 10, '2020-02-15 18:22:32'),
(33, 5, 2, 2, 1, 15, '2020-02-15 18:22:32'),
(34, 5, 2, 3, 1, 11, '2020-02-15 18:22:32'),
(35, 5, 2, 4, 1, 20, '2020-02-15 18:22:32'),
(36, 5, 2, 5, 1, 14, '2020-02-15 18:22:33'),
(37, 5, 2, 6, 1, 19, '2020-02-15 18:22:33'),
(38, 5, 2, 7, 1, 30, '2020-02-15 18:22:33'),
(39, 5, 2, 8, 1, 12, '2020-02-15 18:22:33'),
(40, 5, 2, 9, 1, 18, '2020-02-15 18:22:33'),
(41, 6, 2, 1, 1, 10, '2020-02-15 18:22:40'),
(42, 6, 2, 2, 1, 15, '2020-02-15 18:22:41'),
(43, 6, 2, 3, 1, 11, '2020-02-15 18:22:41'),
(44, 6, 2, 4, 1, 20, '2020-02-15 18:22:41'),
(45, 6, 2, 5, 1, 14, '2020-02-15 18:22:41'),
(46, 6, 2, 6, 1, 19, '2020-02-15 18:22:41'),
(47, 6, 2, 7, 1, 30, '2020-02-15 18:22:41'),
(48, 6, 2, 8, 1, 12, '2020-02-15 18:22:41'),
(49, 6, 2, 9, 1, 18, '2020-02-15 18:22:41'),
(50, 7, 2, 1, 1, 10, '2020-02-15 18:24:40'),
(51, 7, 2, 2, 1, 15, '2020-02-15 18:24:40'),
(52, 7, 2, 3, 1, 11, '2020-02-15 18:24:40'),
(53, 7, 2, 4, 1, 20, '2020-02-15 18:24:40'),
(54, 7, 2, 5, 1, 14, '2020-02-15 18:24:40'),
(55, 7, 2, 6, 1, 19, '2020-02-15 18:24:40'),
(56, 7, 2, 7, 1, 30, '2020-02-15 18:24:40'),
(57, 7, 2, 8, 1, 12, '2020-02-15 18:24:40'),
(58, 7, 2, 9, 1, 18, '2020-02-15 18:24:40'),
(59, 8, 2, 1, 8, 100, '2020-02-19 16:09:07'),
(60, 8, 2, 2, 8, 50, '2020-02-19 16:09:07'),
(61, 8, 2, 3, 8, 25, '2020-02-19 16:09:07'),
(62, 8, 2, 4, 8, 10, '2020-02-19 16:09:07'),
(63, 8, 2, 5, 8, 10, '2020-02-19 16:09:08'),
(64, 9, 1, 1, 7, 200, '2020-02-19 16:15:40'),
(65, 9, 1, 2, 7, 200, '2020-02-19 16:15:40'),
(66, 9, 1, 3, 7, 200, '2020-02-19 16:15:40'),
(67, 9, 1, 4, 7, 100, '2020-02-19 16:15:40'),
(68, 9, 1, 5, 7, 50, '2020-02-19 16:15:40'),
(69, 10, 2, 1, 6, 100, '2020-02-19 17:04:56'),
(70, 10, 2, 2, 6, 100, '2020-02-19 17:04:57'),
(71, 10, 2, 3, 6, 100, '2020-02-19 17:04:57'),
(72, 10, 2, 4, 6, 100, '2020-02-19 17:04:57'),
(73, 10, 2, 5, 6, 100, '2020-02-19 17:04:57'),
(74, 10, 1, 1, 2, 200, '2020-02-19 17:05:36'),
(75, 10, 1, 2, 2, 200, '2020-02-19 17:05:36'),
(76, 10, 1, 3, 2, 200, '2020-02-19 17:05:36'),
(77, 10, 1, 4, 2, 200, '2020-02-19 17:05:36'),
(78, 10, 1, 5, 2, 200, '2020-02-19 17:05:36'),
(79, 11, 4, 1, 5, 40, '2020-02-20 14:42:38'),
(80, 11, 4, 2, 5, 20, '2020-02-20 14:42:38'),
(81, 11, 4, 3, 5, 90, '2020-02-20 14:42:38'),
(82, 11, 4, 4, 5, 100, '2020-02-20 14:42:38'),
(83, 11, 4, 5, 5, 50, '2020-02-20 14:42:38'),
(84, 11, 4, 6, 5, 70, '2020-02-20 14:42:38'),
(85, 11, 4, 7, 5, 200, '2020-02-20 14:42:38'),
(86, 11, 4, 8, 5, 15, '2020-02-20 14:42:38'),
(87, 11, 4, 9, 5, 20, '2020-02-20 14:42:38'),
(88, 11, 4, 10, 5, 100, '2020-02-20 14:42:38'),
(89, 11, 4, 1, 8, 40, '2020-02-20 14:42:38'),
(90, 11, 4, 2, 8, 30, '2020-02-20 14:42:38'),
(91, 11, 4, 3, 8, 190, '2020-02-20 14:42:38'),
(92, 11, 4, 4, 8, 100, '2020-02-20 14:42:38'),
(93, 11, 4, 5, 8, 150, '2020-02-20 14:42:38'),
(94, 11, 4, 6, 8, 40, '2020-02-20 14:42:38'),
(95, 11, 4, 7, 8, 40, '2020-02-20 14:42:38'),
(96, 11, 4, 8, 8, 10, '2020-02-20 14:42:38'),
(97, 11, 4, 9, 8, 5, '2020-02-20 14:42:38'),
(98, 11, 4, 10, 8, 20, '2020-02-20 14:42:39'),
(99, 12, 3, 1, 6, 100, '2020-02-20 15:03:00'),
(100, 12, 3, 2, 6, 50, '2020-02-20 15:03:00'),
(101, 12, 3, 3, 6, 170, '2020-02-20 15:03:00'),
(102, 12, 3, 4, 6, 210, '2020-02-20 15:03:00'),
(103, 12, 3, 5, 6, 150, '2020-02-20 15:03:00'),
(104, 12, 3, 6, 6, 140, '2020-02-20 15:03:01'),
(105, 12, 3, 7, 6, 140, '2020-02-20 15:03:01'),
(106, 12, 3, 8, 6, 50, '2020-02-20 15:03:01'),
(107, 12, 3, 9, 6, 50, '2020-02-20 15:03:01'),
(108, 12, 3, 10, 6, 140, '2020-02-20 15:03:01'),
(109, 12, 1, 1, 8, 180, '2020-02-20 15:03:01'),
(110, 12, 1, 2, 8, 150, '2020-02-20 15:03:01'),
(111, 12, 1, 3, 8, 180, '2020-02-20 15:03:01'),
(112, 12, 1, 4, 8, 180, '2020-02-20 15:03:01'),
(113, 12, 1, 5, 8, 200, '2020-02-20 15:03:01'),
(114, 12, 1, 6, 8, 200, '2020-02-20 15:03:01'),
(115, 12, 1, 7, 8, 250, '2020-02-20 15:03:01'),
(116, 12, 1, 8, 8, 250, '2020-02-20 15:03:01'),
(117, 12, 1, 9, 8, 250, '2020-02-20 15:03:01'),
(118, 12, 1, 10, 8, 260, '2020-02-20 15:03:01');

-- --------------------------------------------------------

--
-- Table structure for table `labour`
--

CREATE TABLE `labour` (
  `id` int(11) NOT NULL,
  `name` varchar(220) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `labour`
--

INSERT INTO `labour` (`id`, `name`, `status`, `ts`) VALUES
(1, 'Bilal', 1, '2020-02-01 15:52:55'),
(2, 'Bilal', 1, '2020-02-15 23:22:32'),
(3, 'Bilal', 1, '2020-02-15 23:22:40'),
(4, 'Bilal', 1, '2020-02-15 23:24:40'),
(5, '', 1, '2020-02-19 21:09:07'),
(6, '', 1, '2020-02-19 21:15:40'),
(7, '', 1, '2020-02-19 22:04:56'),
(8, '', 1, '2020-02-20 19:38:34'),
(9, '', 1, '2020-02-20 20:10:40');

-- --------------------------------------------------------

--
-- Table structure for table `machine`
--

CREATE TABLE `machine` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `machine`
--

INSERT INTO `machine` (`id`, `title`, `status`, `ts`) VALUES
(2, 'machine', 1, '2020-02-20 15:10:45'),
(4, 'green', 1, '2020-02-20 15:12:14'),
(5, 'red', 1, '2020-02-20 15:12:18');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `depth` int(1) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `small_icon` varchar(200) CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `title`, `url`, `parent_id`, `depth`, `sortorder`, `icon`, `small_icon`) VALUES
(1, 'Dashboard', '#', 0, 0, 1, 'dashboard.png', 'home'),
(68, 'Bangles Setting', '#', 0, 0, 6, 'bengles.jpg', 'magnet'),
(69, 'Color', 'color_manage.php', 68, 1, 7, 'color.png', 'circle'),
(8, 'Manage Users', 'admin_manage.php', 1, 1, 4, 'administrator.png', 'user'),
(7, 'General Settings', 'config_manage.php?config_id=1', 1, 1, 2, 'general-settings.png', 'cog'),
(12, 'Upload Center', 'upload_manage.php', 1, 1, 3, 'upload-center.png', 'file-o'),
(22, 'Reports', '#', 0, 0, 24, 'reports.png', 'line-chart'),
(24, 'General Journal', 'report_manage.php?tab=general_journal', 22, 1, 23, 'general-journal.png', 'th-large'),
(26, 'Manage User Types', 'admin_type_manage.php', 1, 1, 5, 'admin-type.png', 'unlock-alt'),
(30, 'Accounts', '#', 0, 0, 19, 'accounts.jpg', 'suitcase'),
(28, 'Manage Expenses', 'expense_manage.php', 30, 1, 14, 'manage-expense.png', 'car'),
(32, 'Manage Transactions', 'transaction_manage.php', 30, 1, 21, 'transaction.png', 'money'),
(35, 'Manage Accounts', 'account_manage.php', 30, 1, 10, 'manage-accounts.png', 'balance-scale'),
(40, 'Expense Category', 'expense_category_manage.php', 30, 1, 13, 'expense-category.png', 'server'),
(74, 'Incoming', 'incoming_manage.php', 78, 1, 13, 'incoming.png', 'rub'),
(75, 'Washing', 'washing_manage.php', 78, 1, 14, 'washing.png', 'eraser'),
(76, 'Delivery', 'delivery_manage.php', 78, 1, 15, 'delivery.png', 'yelp'),
(53, 'Balance Sheet', 'report_manage.php?tab=balance_sheet', 22, 1, 24, 'balance-sheet.png', '500px'),
(54, 'Income Report', 'report_manage.php?tab=income', 22, 1, 25, 'income-report.png', 'backward'),
(73, 'Labour', 'labour_manage.php', 68, 1, 12, 'labour.png', 'male'),
(70, 'Size', 'size_manage.php', 68, 1, 8, 'size.png', 'exchange'),
(71, 'Design', 'design_manage.php', 68, 1, 9, 'design.png', 'empire'),
(72, 'Customer', 'customer_manage.php', 30, 1, 11, 'customer.png', 'male'),
(77, 'Customer Payment', 'customer_payment_manage.php', 30, 1, 12, 'customer-payment.png', 'random'),
(78, 'Bangles Manufacturing', '#', 0, 0, 7, 'bengles-manufacturing.jpg', 'map-signs'),
(79, 'Employees', '#', 0, 0, 18, 'employees.png', 'male'),
(80, 'Manage Employee', 'employee_manage.php', 79, 1, 27, 'manage-employee.png', 'user'),
(81, 'Delivery Report', 'report_manage.php?tab=delivery_report', 22, 1, 28, 'delivery-report.png', '500px'),
(82, 'Stock Report', 'report_manage.php?tab=stock_report', 22, 1, 29, 'stock-report.png', 'cogs'),
(83, 'Machine', 'machine_manage.php', 68, 1, 30, 'machine.png', 'magnet');

-- --------------------------------------------------------

--
-- Table structure for table `menu_2_admin_type`
--

CREATE TABLE `menu_2_admin_type` (
  `menu_id` int(11) NOT NULL,
  `admin_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu_2_admin_type`
--

INSERT INTO `menu_2_admin_type` (`menu_id`, `admin_type_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(4, 3),
(4, 4),
(5, 1),
(5, 3),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(12, 1),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(17, 3),
(18, 1),
(18, 3),
(19, 1),
(20, 1),
(21, 1),
(21, 3),
(22, 1),
(22, 3),
(23, 1),
(24, 1),
(24, 3),
(25, 1),
(26, 1),
(27, 1),
(27, 3),
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1),
(30, 3),
(31, 1),
(32, 1),
(32, 3),
(33, 1),
(33, 3),
(34, 1),
(34, 3),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(41, 3),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(58, 3),
(59, 1),
(59, 3),
(60, 1),
(60, 3),
(61, 1),
(61, 3),
(62, 1),
(62, 3),
(63, 1),
(63, 3),
(64, 1),
(64, 3),
(65, 1),
(65, 3),
(66, 1),
(66, 3),
(67, 1),
(67, 3),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_transaction`
--

CREATE TABLE `scheduled_transaction` (
  `id` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `schedule` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `reference_id` int(1) NOT NULL DEFAULT '0',
  `datetime_added` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `details` text NOT NULL,
  `lastrun` int(11) NOT NULL,
  `nextrun` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `title_urdu` varchar(300) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`id`, `title`, `title_urdu`, `sortorder`, `status`, `ts`) VALUES
(1, '2=', '', 2, 1, '2020-02-19 15:04:36'),
(2, '21', '21', 1, 1, '2020-02-19 15:04:27'),
(3, '2', '', 3, 1, '2020-02-19 15:04:44'),
(4, '21=', '', 4, 1, '2020-02-19 15:06:05'),
(5, '111=', '', 5, 1, '2020-02-19 15:06:11'),
(6, '111', '', 6, 1, '2020-02-18 16:17:40'),
(7, '11', '', 8, 1, '2020-02-19 15:06:56'),
(8, '1=', '', 9, 1, '2020-02-19 15:07:05'),
(9, '1', '', 10, 1, '2020-02-19 15:07:12'),
(10, '11=', '', 7, 1, '2020-02-19 15:06:45');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `reference_id` int(1) NOT NULL DEFAULT '0',
  `datetime_added` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `details` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `filelocation` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `washing`
--

CREATE TABLE `washing` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `washing`
--

INSERT INTO `washing` (`id`, `date`, `customer_id`, `status`, `ts`) VALUES
(1, '2020-02-02', 2, 1, '2020-02-01 19:25:36'),
(2, '2020-02-20', 3, 1, '2020-02-20 14:50:24'),
(3, '2020-02-20', 6, 1, '2020-02-20 15:05:47');

-- --------------------------------------------------------

--
-- Table structure for table `washing_items`
--

CREATE TABLE `washing_items` (
  `id` int(11) NOT NULL,
  `washing_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `design_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `washing_items`
--

INSERT INTO `washing_items` (`id`, `washing_id`, `color_id`, `size_id`, `design_id`, `quantity`, `ts`) VALUES
(1, 1, 2, 2, 2, 40, '2020-02-01 19:25:36'),
(2, 1, 2, 1, 2, 50, '2020-02-01 19:25:36'),
(3, 1, 1, 1, 1, 30, '2020-02-01 19:25:36'),
(4, 2, 4, 1, 8, 8, '2020-02-20 14:50:24'),
(5, 2, 4, 2, 8, 5, '2020-02-20 14:50:24'),
(6, 2, 4, 3, 8, 6, '2020-02-20 14:50:24'),
(7, 2, 4, 4, 8, 14, '2020-02-20 14:50:25'),
(8, 2, 4, 5, 8, 19, '2020-02-20 14:50:25'),
(9, 2, 4, 6, 8, 22, '2020-02-20 14:50:25'),
(10, 2, 4, 7, 8, 11, '2020-02-20 14:50:25'),
(11, 2, 4, 8, 8, 4, '2020-02-20 14:50:25'),
(12, 2, 4, 9, 8, 2, '2020-02-20 14:50:25'),
(13, 2, 4, 10, 8, 20, '2020-02-20 14:50:25'),
(14, 2, 4, 1, 5, 1, '2020-02-20 14:50:25'),
(15, 2, 4, 2, 5, 3, '2020-02-20 14:50:25'),
(16, 2, 4, 3, 5, 1, '2020-02-20 14:50:25'),
(17, 2, 4, 4, 5, 2, '2020-02-20 14:50:25'),
(18, 2, 4, 5, 5, 3, '2020-02-20 14:50:25'),
(19, 2, 4, 6, 5, 2, '2020-02-20 14:50:25'),
(20, 2, 4, 7, 5, 3, '2020-02-20 14:50:25'),
(21, 2, 4, 8, 5, 3, '2020-02-20 14:50:25'),
(22, 2, 4, 9, 5, 4, '2020-02-20 14:50:25'),
(23, 2, 4, 10, 5, 2, '2020-02-20 14:50:25'),
(24, 3, 1, 1, 8, 60, '2020-02-20 15:05:48'),
(25, 3, 1, 2, 8, 50, '2020-02-20 15:05:48'),
(26, 3, 1, 3, 8, 80, '2020-02-20 15:05:48'),
(27, 3, 1, 4, 8, 70, '2020-02-20 15:05:48'),
(28, 3, 1, 5, 8, 120, '2020-02-20 15:05:48'),
(29, 3, 1, 6, 8, 110, '2020-02-20 15:05:48'),
(30, 3, 1, 7, 8, 100, '2020-02-20 15:05:48'),
(31, 3, 1, 8, 8, 100, '2020-02-20 15:05:48'),
(32, 3, 1, 9, 8, 50, '2020-02-20 15:05:48'),
(33, 3, 1, 10, 8, 50, '2020-02-20 15:05:48'),
(34, 3, 3, 1, 6, 80, '2020-02-20 15:05:48'),
(35, 3, 3, 2, 6, 20, '2020-02-20 15:05:48'),
(36, 3, 3, 3, 6, 110, '2020-02-20 15:05:48'),
(37, 3, 3, 4, 6, 10, '2020-02-20 15:05:48'),
(38, 3, 3, 5, 6, 90, '2020-02-20 15:05:48'),
(39, 3, 3, 6, 6, 40, '2020-02-20 15:05:48'),
(40, 3, 3, 7, 6, 25, '2020-02-20 15:05:48'),
(41, 3, 3, 8, 6, 10, '2020-02-20 15:05:48'),
(42, 3, 3, 9, 6, 20, '2020-02-20 15:05:49'),
(43, 3, 3, 10, 6, 25, '2020-02-20 15:05:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_type`
--
ALTER TABLE `admin_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_type`
--
ALTER TABLE `config_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config_variable`
--
ALTER TABLE `config_variable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_payment`
--
ALTER TABLE `customer_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `design`
--
ALTER TABLE `design`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_category`
--
ALTER TABLE `expense_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming`
--
ALTER TABLE `incoming`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incoming_items`
--
ALTER TABLE `incoming_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `labour`
--
ALTER TABLE `labour`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `machine`
--
ALTER TABLE `machine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_2_admin_type`
--
ALTER TABLE `menu_2_admin_type`
  ADD PRIMARY KEY (`menu_id`,`admin_type_id`);

--
-- Indexes for table `scheduled_transaction`
--
ALTER TABLE `scheduled_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `washing`
--
ALTER TABLE `washing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `washing_items`
--
ALTER TABLE `washing_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `admin_type`
--
ALTER TABLE `admin_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `config_type`
--
ALTER TABLE `config_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `config_variable`
--
ALTER TABLE `config_variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer_payment`
--
ALTER TABLE `customer_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `design`
--
ALTER TABLE `design`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `incoming`
--
ALTER TABLE `incoming`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `incoming_items`
--
ALTER TABLE `incoming_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `labour`
--
ALTER TABLE `labour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `machine`
--
ALTER TABLE `machine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `scheduled_transaction`
--
ALTER TABLE `scheduled_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `washing`
--
ALTER TABLE `washing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `washing_items`
--
ALTER TABLE `washing_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
