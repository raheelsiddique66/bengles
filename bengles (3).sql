-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2020 at 03:18 PM
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
(6, 'Milan', '', 0, '', 1, '2020-02-15 15:45:57');

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
(1, 1, '2020-02-04 18:46:00', '44.00', 0, '', 1, '2020-02-04 13:46:29');

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
(1, 0, '2020-02-02', 1, 'aa', 1, 1, '2020-02-03 10:55:06');

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
(2, 1, 2, 2, 1, 2, 2, '10.00', '2020-02-04 13:32:30');

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
(2, 'Nagin', '', 0, 1, '2020-02-15 15:42:17'),
(3, 'Noor', '', 0, 1, '2020-02-15 15:42:21'),
(4, 'Namakpara', '', 0, 1, '2020-02-15 15:42:56'),
(5, 'Ujala', '', 0, 1, '2020-02-15 15:43:56'),
(6, 'Barfi', '', 0, 1, '2020-02-15 15:44:05');

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
(7, 'admin', 'yousuf', 900786019, 1, '4000.00', '40.00', 1, '2020-02-17 15:19:00');

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
(7, 121, '2020-02-15', 5, 4, 1, '2020-02-15 18:24:40');

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
(58, 7, 2, 9, 1, 18, '2020-02-15 18:24:40');

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
(4, 'Bilal', 1, '2020-02-15 23:24:40');

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
(28, 'Manage Expenses', 'expense_manage.php', 30, 1, 20, 'manage-expense.png', 'car'),
(32, 'Manage Transactions', 'transaction_manage.php', 30, 1, 18, 'transaction.png', 'money'),
(35, 'Manage Accounts', 'account_manage.php', 30, 1, 19, 'manage-accounts.png', 'balance-scale'),
(40, 'Expense Category', 'expense_category_manage.php', 30, 1, 21, 'expense-category.png', 'server'),
(74, 'Incoming', 'incoming_manage.php', 78, 1, 13, 'incoming.png', 'rub'),
(75, 'Washing', 'washing_manage.php', 78, 1, 14, 'washing.png', 'eraser'),
(76, 'Delivery', 'delivery_manage.php', 78, 1, 15, 'delivery.png', 'yelp'),
(53, 'Balance Sheet', 'report_manage.php?tab=balance_sheet', 22, 1, 24, 'balance-sheet.png', '500px'),
(54, 'Income Report', 'report_manage.php?tab=income', 22, 1, 25, 'income-report.png', 'backward'),
(73, 'Labour', 'labour_manage.php', 68, 1, 12, 'labour.png', 'male'),
(70, 'Size', 'size_manage.php', 68, 1, 8, 'size.png', 'exchange'),
(71, 'Design', 'design_manage.php', 68, 1, 9, 'design.png', 'empire'),
(72, 'Customer', 'customer_manage.php', 30, 1, 10, 'customer.png', 'male'),
(77, 'Customer Payment', 'customer_payment_manage.php', 30, 1, 11, 'customer-payment.png', 'random'),
(78, 'Bangles Manufacturing', '#', 0, 0, 7, 'bengles-manufacturing.jpg', 'map-signs'),
(79, 'Employees', '#', 0, 0, 18, 'employees.png', 'male'),
(80, 'Manage Employee', 'employee_manage.php', 79, 1, 27, 'manage-employee.png', 'user');

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
(80, 1);

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
(1, '2=', '', 0, 1, '2020-02-15 15:39:36'),
(2, '21', '21', 0, 1, '2020-02-15 15:39:23'),
(3, '2', '', 0, 1, '2020-02-15 15:39:41'),
(4, '21=', '', 0, 1, '2020-02-15 15:39:48'),
(5, '111=', '', 0, 1, '2020-02-15 15:39:52'),
(6, '111', '', 0, 1, '2020-02-15 16:49:53'),
(7, '11', '', 0, 1, '2020-02-15 16:50:12'),
(8, '1=', '', 0, 1, '2020-02-15 16:50:18'),
(9, '1', '', 0, 1, '2020-02-15 16:50:23');

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
(1, '2020-02-02', 2, 1, '2020-02-01 19:25:36');

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
(3, 1, 1, 1, 1, 30, '2020-02-01 19:25:36');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_payment`
--
ALTER TABLE `customer_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `design`
--
ALTER TABLE `design`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incoming`
--
ALTER TABLE `incoming`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `incoming_items`
--
ALTER TABLE `incoming_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `labour`
--
ALTER TABLE `labour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `scheduled_transaction`
--
ALTER TABLE `scheduled_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `washing_items`
--
ALTER TABLE `washing_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
