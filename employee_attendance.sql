-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2020 at 08:09 PM
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
-- Database: `bangles`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee_attendance`
--

CREATE TABLE `employee_attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `attendance` varchar(3) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_attendance`
--

INSERT INTO `employee_attendance` (`id`, `employee_id`, `date`, `attendance`, `ts`) VALUES
(1, 9, '2020-02-15', '3', '2020-02-24 17:40:44'),
(2, 9, '2020-02-16', 'P', '2020-02-24 17:40:44'),
(3, 9, '2020-02-17', 'P', '2020-02-24 17:40:44'),
(4, 9, '2020-02-18', 'P', '2020-02-24 17:40:44'),
(5, 9, '2020-02-19', 'P', '2020-02-24 17:40:44'),
(6, 9, '2020-02-20', 'P', '2020-02-24 17:40:44'),
(7, 9, '2020-02-21', 'F', '2020-02-24 17:40:44'),
(8, 7, '2020-02-15', 'P', '2020-02-24 17:40:44'),
(9, 7, '2020-02-16', 'P', '2020-02-24 17:40:44'),
(10, 7, '2020-02-17', 'P', '2020-02-24 17:40:44'),
(11, 7, '2020-02-18', 'P', '2020-02-24 17:40:44'),
(12, 7, '2020-02-19', 'P', '2020-02-24 17:40:44'),
(13, 7, '2020-02-20', 'P', '2020-02-24 17:40:44'),
(14, 7, '2020-02-21', 'F', '2020-02-24 17:40:44'),
(15, 8, '2020-02-15', 'P', '2020-02-24 17:40:44'),
(16, 8, '2020-02-16', 'P', '2020-02-24 17:40:44'),
(17, 8, '2020-02-17', 'P', '2020-02-24 17:40:44'),
(18, 8, '2020-02-18', 'P', '2020-02-24 17:40:44'),
(19, 8, '2020-02-19', 'P', '2020-02-24 17:40:44'),
(20, 8, '2020-02-20', 'P', '2020-02-24 17:40:44'),
(21, 8, '2020-02-21', 'F', '2020-02-24 17:40:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee_attendance`
--
ALTER TABLE `employee_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
