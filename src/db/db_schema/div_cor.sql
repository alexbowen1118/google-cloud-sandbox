-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Feb 21, 2022 at 06:53 PM
-- Server version: 5.5.64-MariaDB-1~trusty
-- PHP Version: 8.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `div_cor`
--
CREATE DATABASE IF NOT EXISTS `div_cor` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE `div_cor`;

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE `access` (
  `id` int(11) NOT NULL,
  `admin_id` varchar(8) NOT NULL DEFAULT '',
  `operation_id` varchar(8) NOT NULL DEFAULT '',
  `apc_id` varchar(8) NOT NULL DEFAULT '',
  `engn_id` varchar(8) NOT NULL DEFAULT '',
  `le_id` varchar(8) NOT NULL DEFAULT '',
  `opaa_id` varchar(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id`, `admin_id`, `operation_id`, `apc_id`, `engn_id`, `le_id`, `opaa_id`) VALUES
(1, '', '60032920', '60032920', '60032833', '60033165', ''),
(2, '60032784', '60033018', '60032784', '', '', ''),
(3, '', '', '60032931', '', '', ''),
(25, '', '', '65016281', '', '', ''),
(30, '', '', '60095523', '', '', ''),
(29, '', '', '', '', '', '60033148'),
(24, '', '', '', '', '', ''),
(9, '', '', '60032892', '', '', ''),
(10, '', '', '60033148', '', '', ''),
(11, '', '', '60033093', '', '', ''),
(12, '', '', '60032983', '', '', ''),
(19, '', '', '60033016', '', '', ''),
(20, '60033202', '', '', '', '', ''),
(21, '60033018', '', '', '', '', ''),
(22, '', '', '', '', '', ''),
(23, '60033165', '', '', '', '', ''),
(26, '', '', '', '', '', ''),
(27, '', '', '', '', '60033146', ''),
(28, '60033138', '', '', '', '', ''),
(31, '', '', '60033110', '', '', ''),
(32, '60033160', '', '', '', '', ''),
(33, '', '60032784', '', '', '', ''),
(34, '', '60033202', '', '', '', ''),
(35, '', '60033165', '', '', '', ''),
(36, '', '', '', '', '', ''),
(37, '', '', '', '', '', ''),
(38, '', '60032912', '', '', '', ''),
(39, '60032912', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access`
--
ALTER TABLE `access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
