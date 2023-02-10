-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Aug 12, 2022 at 06:50 PM
-- Server version: 10.6.8-MariaDB-1:10.6.8+maria~focal
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `annual_pass`
--
CREATE DATABASE IF NOT EXISTS `annual_pass` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `annual_pass`;

-- --------------------------------------------------------

--
-- Table structure for table `passes`
--

CREATE TABLE `passes` (
  `id` int(11) NOT NULL,
  `type_pass` varchar(16) DEFAULT NULL,
  `sub_type_pass` varchar(32) NOT NULL,
  `pass_number` varchar(16) DEFAULT NULL,
  `issuing_park` varchar(4) NOT NULL,
  `year` varchar(4) NOT NULL,
  `date_issued` date NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `comments` text NOT NULL,
  `void` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `passes`
--

INSERT INTO `passes` (`id`, `type_pass`, `sub_type_pass`, `pass_number`, `issuing_park`, `year`, `date_issued`, `name`, `email`, `comments`, `void`) VALUES
(1, 'Individual', '', '3524', 'JORD', '2018', '2017-11-20', 'john doe', 'johndoe@email.com', '', ''),
(2, 'Individual', '', '5101', 'HABE', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', 'Receipt#232813085', ''),
(3, 'Individual', '', '2999', 'FALA', '2018', '2017-11-27', 'john doe', 'johndoe@email.com', '', ''),
(4, 'Individual', '', '2998', 'FALA', '2018', '2017-11-29', 'john doe', 'johndoe@email.com', '', ''),
(5, 'Individual', '', '2997', 'FALA', '2018', '2017-11-29', 'john doe', 'johndoe@email.com', '', ''),
(6, 'Individual', '', '1363', 'ARCH', '2018', '2017-11-28', 'john doe', 'johndoe@email.com', 'WARE #3175 (one of two)', ''),
(7, 'Individual', '', '1364', 'ARCH', '2018', '2017-11-28', 'john doe', 'johndoe@email.com', 'WARE #3175 (two of two)', ''),
(8, 'Family', '', '10167', 'ARCH', '2018', '2017-11-28', 'john doe', 'johndoe@email.com', 'WARE #3174\r\n', ''),
(9, 'Individual', '', '1362', 'ARCH', '2018', '2017-11-28', 'john doe', 'johndoe@email.com', 'WARE #3158', ''),
(10, 'Individual', '', '1361', 'ARCH', '2018', '2017-11-28', 'john doe', 'johndoe@email.com', 'WARE #3154 - Jane Doe purchased for John Doe\r\n', ''),
(11, 'Individual', '', '1360', 'ARCH', '2018', '2017-11-20', 'john doe', 'johndoe@email.com', 'WARE #3152', ''),
(12, 'Family', '', '10166', 'ARCH', '2018', '2017-11-20', 'john doe', 'johndoe@email.com', 'WARE #3148', ''),
(13, 'Family', '', '10165', 'ARCH', '2018', '2017-11-17', 'john doe', 'johndoe@email.com', 'WARE #3133\r\n', ''),
(14, 'Family', '', '10163', 'ARCH', '2018', '2017-11-08', 'john doe', 'johndoe@email.com', 'WARE #3106', ''),
(15, 'Family', '', '10164', 'ARCH', '2018', '2017-11-08', 'john doe', 'johndoe@email.com', 'WARE #3105', ''),
(16, 'Family', '', '10168', 'ARCH', '2018', '2017-11-30', 'john doe', 'johndoe@email.com', 'WARE #3176', ''),
(17, 'Individual', '', '1201', 'KELA', '2018', '2017-12-04', 'john doe', 'johndoe@email.com', '', ''),
(18, 'Individual', '', '1202', 'KELA', '2018', '2017-12-04', 'john doe', 'johndoe@email.com', '', ''),
(19, 'Individual', '', '1203', 'KELA', '2018', '2017-12-04', 'john doe', 'johndoe@email.com', '', ''),
(20, 'Individual', '', '1204', 'KELA', '2018', '2017-12-04', 'john doe', 'johndoe@email.com', '', ''),
(21, 'Individual', '', '1205', 'KELA', '2018', '2017-12-04', 'john doe', 'johndoe@email.com', '', ''),
(22, 'Family', '', '10171', 'ARCH', '2018', '2017-12-01', 'john doe', 'johndoe@email.com', 'WARE #3182', ''),
(23, 'Family', '', '10172', 'ARCH', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', 'WARE #3190\r\n', ''),
(24, 'Family', '', '10170', 'ARCH', '2018', '2017-12-05', 'john doe', 'johndoe@email.com', 'WARE #3195\r\n', ''),
(25, 'Individual', '', '1365', 'ARCH', '2018', '2017-12-05', 'john doe', 'johndoe@email.com', 'WARE #3208', ''),
(26, 'Family', '', '10173', 'ARCH', '2018', '2017-12-05', 'john doe', 'johndoe@email.com', 'WARE #3214', ''),
(27, 'Family', '', '10175', 'ARCH', '2018', '2017-12-06', 'john doe', 'johndoe@email.com', 'WARE #3225', ''),
(28, 'Individual', '', '1366', 'ARCH', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', 'WARE #3215', ''),
(29, 'Family', '', '10174', 'ARCH', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', 'WARE# 3224.  Gift for John Doe.', ''),
(30, 'Individual', '', '1368', 'ARCH', '2018', '2017-12-07', 'john doe', 'johndoe@email.com', 'WARE #3232\r\n', ''),
(31, 'Individual', '', '1367', 'ARCH', '2018', '2017-12-07', 'john doe', 'johndoe@email.com', 'WARE #3231\r\n', ''),
(32, 'Family', '', '10176', 'ARCH', '2018', '2017-12-07', 'john doe', 'johndoe@email.com', 'WARE #3226 from Jane Doe to John Doe', ''),
(33, 'Family', '', '10194', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3269 for John Doe', ''),
(34, 'Family', '', '10193', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3267\r\n', ''),
(35, 'Family', '', '10192', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3251 for John Doe', ''),
(36, 'Family', '', '10191', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3258 for John Doe', ''),
(37, 'Family', '', '10180', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3260 for John Doe', ''),
(38, 'Family', '', '10179', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3256 for John Doe', ''),
(39, 'Family', '', '10178', 'ARCH', '2018', '2017-12-11', 'john doe', 'johndoe@email.com', 'WARE #3262 for The John Doe Crew', ''),
(40, 'Individual', '', '3525', 'JORD', '2018', '2017-12-12', 'john doe', 'johndoe@email.com', '', ''),
(41, 'Individual', '', '3526', 'JORD', '2018', '2017-12-12', 'john doe', 'johndoe@email.com', '', ''),
(42, 'Family', '', '10197', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3284\r\n', ''),
(43, 'Individual', '', '1374', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #1374', ''),
(44, 'Individual', '', '1375', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #1375', ''),
(45, 'Individual', '', '1372', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3279\r\n', ''),
(46, 'Family', '', '10198', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3283', ''),
(47, 'Individual', '', '1373', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3286 gift for John Doe', ''),
(48, 'Family', '', '10195', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3276 for John Doe', ''),
(49, 'Family', '', '10196', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3272', ''),
(50, 'Individual', '', '1369', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3271 one of three', ''),
(51, 'Individual', '', '1370', 'ARCH', '2018', '2017-12-14', 'john doe', 'johndoe@email.com', 'WARE #3271  two of three', ''),
(52, 'Individual', '', '1371', 'ARCH', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', 'WARE #3271 three of three 1369, 1370, 1371', ''),
(53, 'Individual', '', '3527', 'JORD', '2018', '2017-12-15', 'john doe', 'johndoe@email.com', 'Carolina Sailing Club', ''),
(54, 'Individual', '', '3528', 'JORD', '2018', '2017-12-15', 'john doe', 'johndoe@email.com', 'Carolina Sailing Club', ''),
(55, 'Individual', '', '3529', 'JORD', '2018', '2017-12-15', 'john doe', 'johndoe@email.com', 'Carolina sailing club', ''),
(56, 'Individual', '', '6001', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '\r\n', ''),
(57, 'Individual', '', '6002', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '\r\n', ''),
(58, 'Individual', '', '6003', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(59, 'Individual', '', '6004', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(60, 'Individual', '', '6005', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(61, 'Individual', '', '6006', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(62, 'Individual', '', '6007', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(63, 'Individual', '', '6008', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(64, 'Individual', '', '6009', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(65, 'Individual', '', '6010', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(66, 'Individual', '', '6011', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(67, 'Individual', '', '6012', 'FOFI', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', '', ''),
(68, 'Individual', '', '6013', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(69, 'Individual', '', '6014', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(70, 'Individual', '', '6015', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(71, 'Individual', '', '6028', 'FOFI', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', '', ''),
(72, 'Individual', '', '6016', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(73, 'Individual', '', '6018', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(74, 'Individual', '', '6019', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(75, 'Individual', '', '6020', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(76, 'Individual', '', '6021', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(77, 'Individual', '', '6022', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(78, 'Individual', '', '6023', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(79, 'Individual', '', '6024', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(80, 'Individual', '', '6025', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(81, 'Individual', '', '6026', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(82, 'Individual', '', '6027', 'FOFI', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', '', ''),
(83, 'Individual', '', '6029', 'FOFI', '2018', '2017-12-18', 'john doe', 'johndoe@email.com', '', ''),
(84, 'Individual', '', '6030', 'FOFI', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', '', ''),
(85, 'Family', '', '10501', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3311 for John Doe', ''),
(86, 'Family', '', '10502', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3312 for John Doe', ''),
(87, 'Individual', '', '1383', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3310 for John Doe', ''),
(88, 'Individual', '', '1382', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3309', ''),
(89, 'Individual', '', '1381', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3305\r\n', ''),
(90, 'Individual', '', '1380', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3300', ''),
(91, 'Individual', '', '1379', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3299 for John Doe', ''),
(92, 'Individual', '', '1378', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3298\r\n', ''),
(93, 'Individual', '', '3295', 'ARCH', '2018', '0000-00-00', 'john doe', 'johndoe@email.com', 'WARE #3295 forJohn Doe John Doe', 'x'),
(94, 'Individual', '', '1377', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3295 for John Doe', ''),
(95, 'Individual', '', '1376', 'ARCH', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', 'WARE #3291', ''),
(96, 'Individual', '', '3530', 'JORD', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', '', ''),
(97, 'Individual', '', '3531', 'JORD', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', '', ''),
(98, 'Individual', '', '6032', 'FOFI', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', '', ''),
(99, 'Individual', '', '6033', 'FOFI', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', '', ''),
(100, 'Individual', '', '6034', 'FOFI', '2018', '2017-12-19', 'john doe', 'johndoe@email.com', '', ''),
(35200, 'FOFI_FWD', '', 'F22-04872', 'FOFI', '2022', '2022-07-29', 'john doe', 'johndoe@email.com', '', ''),
(35201, 'FOFI_FWD', '', 'F22-04873', 'FOFI', '2022', '2022-07-29', 'john doe', 'johndoe@email.com', '', ''),
(35202, 'FOFI_FWD', '', 'F22-04874', 'FOFI', '2022', '2022-07-29', 'john doe', 'johndoe@email.com', '', ''),
(35203, 'FOFI_FWD', '', 'F22-04875', 'FOFI', '2022', '2022-07-29', 'john doe', 'johndoe@email.com', '', ''),
(35204, 'Annual', '', '22-01581', 'CLNE', '2022', '2022-07-31', 'john doe', 'johndoe@email.com', '252.252.2525', ''),
(35205, 'FOFI_FWD', '', 'F22-04881', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35206, 'FOFI_FWD', '', 'F22-04882', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35207, 'FOFI_FWD', '', 'F22-04883', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35208, 'FOFI_FWD', '', 'F22-04884', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35209, 'FOFI_FWD', '', 'F22-04885', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35210, 'FOFI_FWD', '', 'F22-04886', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35211, 'FOFI_FWD', '', 'F22-04887', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35212, 'FOFI_FWD', '', 'F22-04888', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35213, 'FOFI_FWD', '', 'F22-04889', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35214, 'FOFI_FWD', '', 'F22-04890', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35215, 'FOFI_FWD', '', 'F22-04891', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35216, 'FOFI_FWD', '', 'F22-04892', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35217, 'FOFI_FWD', '', 'F22-04893', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35218, 'FOFI_FWD', '', 'F22-04894', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35219, 'FOFI_FWD', '', 'F22-04895', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35220, 'FOFI_FWD', '', 'F22-04896', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35221, 'FOFI_FWD', '', 'F22-04897', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35222, 'FOFI_FWD', '', 'F22-04898', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35223, 'FOFI_FWD', '', 'F22-04899', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35224, 'FOFI_FWD', '', 'F22-04900', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35225, 'FOFI_FWD', '', 'F22-04901', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35226, 'FOFI_FWD', '', 'F22-04902', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35227, 'FOFI_FWD', '', 'F22-04903', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35228, 'FOFI_FWD', '', 'F22-04904', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35229, 'FOFI_FWD', '', 'F22-04905', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35230, 'FOFI_FWD', '', 'F22-04906', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35231, 'FOFI_FWD', '', 'F22-04907', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35232, 'FOFI_FWD', '', 'F22-04908', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35233, 'FOFI_FWD', '', 'F22-04909', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35234, 'FOFI_FWD', '', 'F22-04876', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35235, 'FOFI_FWD', '', 'F22-04877', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35236, 'FOFI_FWD', '', 'F22-04878', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35237, 'FOFI_FWD', '', 'F22-04879', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35238, 'FOFI_FWD', '', 'F22-04880', 'FOFI', '2022', '2022-08-01', 'john doe', 'johndoe@email.com', '', ''),
(35239, 'FOFI_FWD', '', 'F22-04910', 'FOFI', '2022', '2022-08-02', 'john doe', 'johndoe@email.com', '', ''),
(35240, 'Annual', '', '22-01000', 'HABE', '2022', '2022-07-30', 'john doe', 'johndoe@email.com', 'Receipt#368159228 Boat Ramp', ''),
(35241, 'FOFI_FWD', '', 'F22-04914', 'FOFI', '2022', '2022-08-04', 'john doe', 'johndoe@email.com', 'Replacement', ''),
(35242, 'FOFI_FWD', '', 'F22-04911', 'FOFI', '2022', '2022-08-03', 'john doe', 'johndoe@email.com', '', ''),
(35243, 'FOFI_FWD', '', 'F22-04912', 'FOFI', '2022', '2022-08-04', 'john doe', 'johndoe@email.com', '', ''),
(35244, 'FOFI_FWD', '', 'F22-04913', 'FOFI', '2022', '2022-08-04', 'john doe', 'johndoe@email.com', '', ''),
(35245, 'FOFI_FWD', '', 'F22-05800', 'FOFI', '2022', '2022-08-04', 'john doe', 'johndoe@email.com', '', ''),
(35246, 'FOFI_FWD', '', 'F22-04915', 'FOFI', '2022', '2022-08-04', 'john doe', 'johndoe@email.com', '', ''),
(35247, 'FOFI_FWD', '', 'F22-04916', 'FOFI', '2022', '2022-08-05', 'john doe', 'johndoe@email.com', '', ''),
(35248, 'FOFI_FWD', '', 'F22-04917', 'FOFI', '2022', '2022-08-05', 'john doe', 'johndoe@email.com', '', ''),
(35249, 'FOFI_FWD', '', 'F22-04918', 'FOFI', '2022', '2022-08-05', 'john doe', 'johndoe@email.com', '', ''),
(35250, 'FOFI_FWD', '', 'F22-04919', 'FOFI', '2022', '2022-08-05', 'john doe', 'johndoe@email.com', '', ''),
(35251, 'FOFI_FWD', '', 'F22-04920', 'FOFI', '2022', '2022-08-05', 'john doe', 'johndoe@email.com', '', ''),
(35252, 'FOFI_FWD', '', 'F22-04943', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35253, 'FOFI_FWD', '', 'F22-04944', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35254, 'FOFI_FWD', '', 'F22-04945', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35255, 'FOFI_FWD', '', 'F22-04946', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35256, 'FOFI_FWD', '', 'F22-04947', 'FOFI', '2022', '2022-08-08', 'john doe', 'johndoe@email.com', '', ''),
(35257, 'FOFI_FWD', '', 'F22-04948', 'FOFI', '2022', '2022-08-08', 'john doe', 'johndoe@email.com', '', ''),
(35258, 'FOFI_FWD', '', 'F22-04921', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35259, 'FOFI_FWD', '', 'F22-04922', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35260, 'FOFI_FWD', '', 'F22-04923', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35261, 'FOFI_FWD', '', 'F22-04924', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35262, 'FOFI_FWD', '', 'F22-04925', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35263, 'FOFI_FWD', '', 'F22-04926', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35264, 'FOFI_FWD', '', 'F22-04927', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35265, 'FOFI_FWD', '', 'F22-04928', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35266, 'FOFI_FWD', '', 'F22-04929', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35267, 'FOFI_FWD', '', 'F22-04930', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35268, 'FOFI_FWD', '', 'F22-04931', 'FOFI', '2022', '2022-08-06', 'john doe', 'johndoe@email.com', '', ''),
(35269, 'FOFI_FWD', '', 'F22-04932', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35270, 'FOFI_FWD', '', 'F22-04933', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35271, 'FOFI_FWD', '', 'F22-04934', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35272, 'FOFI_FWD', '', 'F22-04935', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35273, 'FOFI_FWD', '', 'F22-04936', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35274, 'FOFI_FWD', '', 'F22-04937', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35275, 'FOFI_FWD', '', 'F22-04938', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35276, 'FOFI_FWD', '', 'F22-04939', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35277, 'FOFI_FWD', '', 'F22-04940', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35278, 'FOFI_FWD', '', 'F22-04941', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35279, 'FOFI_FWD', '', 'F22-04942', 'FOFI', '2022', '2022-08-07', 'john doe', 'johndoe@email.com', '', ''),
(35280, 'FOFI_FWD', '', 'F22-04949', 'FOFI', '2022', '2022-08-08', 'john doe', 'johndoe@email.com', '', ''),
(35281, 'FOFI_FWD', '', 'F22-04950', 'FOFI', '2022', '2022-08-08', 'john doe', 'johndoe@email.com', '', ''),
(35282, 'FOFI_FWD', '', 'F22-04951', 'FOFI', '2022', '2022-08-09', 'john doe', 'johndoe@email.com', '', ''),
(35283, 'FOFI_FWD', '', 'F22-04952', 'FOFI', '2022', '2022-08-09', 'john doe', 'johndoe@email.com', '', ''),
(35284, 'FOFI_FWD', '', 'F22-04953', 'FOFI', '2022', '2022-08-11', 'john doe', 'johndoe@email.com', '', ''),
(35285, 'FOFI_FWD', '', 'F22-04954', 'FOFI', '2022', '2022-08-10', 'john doe', 'johndoe@email.com', '', ''),
(35286, 'FOFI_FWD', '', 'F22-04955', 'FOFI', '2022', '2022-08-10', 'john doe', 'johndoe@email.com', '', ''),
(35287, 'FOFI_FWD', '', 'F22-04956', 'FOFI', '2022', '2022-08-10', 'john doe', 'johndoe@email.com', '', ''),
(35288, 'FOFI_FWD', '', 'F22-04957', 'FOFI', '2022', '2022-08-11', 'john doe', 'johndoe@email.com', '', ''),
(35289, 'FOFI_FWD', '', 'F22-04958', 'FOFI', '2022', '2022-08-11', 'john doe', 'johndoe@email.com', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `passes`
--
ALTER TABLE `passes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pass_number` (`pass_number`,`year`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `passes`
--
ALTER TABLE `passes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35292;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
