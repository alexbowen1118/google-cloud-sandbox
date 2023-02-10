-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Apr 27, 2022 at 06:27 PM
-- Server version: 10.6.7-MariaDB-1:10.6.7+maria~focal
-- PHP Version: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `dprcal_new`
--
CREATE DATABASE IF NOT EXISTS `dprcal_new` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE `dprcal_new`;

-- If something unexplainable is wrong, I removed all instances of "COLLATE utf8mb4_bin " from the create statements
-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `requirements` varchar(255) NOT NULL
);

--
-- Dumping test data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `description`, `requirements`) VALUES
(1, 'Fire Safety 101', 'Basic instruction on how to prevent wildfires', 'No requirements'),
(2, 'Fire Safety 201', 'Advanced safety training on the prevention of wildfires', 'Prereq: Fire Safety 101'),
(3, 'Archery 101', 'Basic training on the fundamentals of archery', 'No prereqs');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
);

-- Dumping test data for table `subjects`
INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'skills'),
(2, 'main'),
(3, 'safety'),
(4, 'law_enforcement'),
(5, 'medical'),
(6, 'rescue'),
(7, 'training'),
(8, 'fire'),
(9, 'environment_education'),
(10, 'administration'),
(11, 'certification'),
(12, 'water');

-- --------------------------------------------------------

--
-- Table structure for table `course_subjects`
--

CREATE TABLE `course_subjects` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`), 
  FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`)
);

-- Dumping test data for table `course_subjects`
INSERT INTO `course_subjects` (`id`, `course_id`, `subject_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `role` varchar(32) NOT NULL
);

--
-- Dumping test data for table `users`
--

INSERT INTO `users` (`id`, `user`, `hash`, `first_name`, `last_name`, `role`) VALUES
(1, 'admin', '$2y$10$kglpeJqbVWsVws8ITdh4oejJQhfKXTe4Ir8r5qK7D3OSPEyhaaffW', 'John', 'Doe', 'ADMIN'),
(4, 'ins1', '$2y$10$kglpeJqbVWsVws8ITdh4oejJQhfKXTe4Ir8r5qK7D3OSPEyhaaffW', 'Karen', 'Smithfield', 'INSTRUCTOR'),
(7, 'ins2', '$2y$10$kglpeJqbVWsVws8ITdh4oejJQhfKXTe4Ir8r5qK7D3OSPEyhaaffW', 'Claude', 'Reigan', 'INSTRUCTOR'),
(10, 'student1', '$2y$10$kglpeJqbVWsVws8ITdh4oejJQhfKXTe4Ir8r5qK7D3OSPEyhaaffW', 'Sally', 'Jones', 'ATTENDEE'),
(13, 'student2', '$2y$10$kglpeJqbVWsVws8ITdh4oejJQhfKXTe4Ir8r5qK7D3OSPEyhaaffW', 'Marcus', 'Stone', 'ATTENDEE');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `addr1` varchar(255) NOT NULL,
  `addr2` varchar(255) NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `zip` varchar(16) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `fax` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);

--
-- Dumping test data for table `instructors`
--

INSERT INTO `instructors` (`id`, `title`, `first_name`, `last_name`, `addr1`, `addr2`, `city`, `state`, `zip`, `phone`, `fax`, `email`, `website`, `user_id`) VALUES
(1, 'Mrs.', 'Karen', 'Smithfield', '789 Address Ct.', 'NA', 'Raleigh', 'NC', '27606', '919-000-0000', '919-000-0000', 'karen@email.com', 'karen.ncsu.edu', 4),
(2, 'Mr.', 'Claude', 'Reigan', '123 Address St.', 'NA', 'Almyra', 'Fodlan', '123', '456', 'fax', 'anemail@me.com', 'wowawebsite.com', 7);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

-- May need to rework this to tie to actual users
CREATE TABLE `logs` (
   `id` int(11) PRIMARY KEY AUTO_INCREMENT,
   `page` varchar(255) NOT NULL,
   -- User who made the action
   `username` varchar(255) NOT NULL,
   `log_time` datetime DEFAULT CURRENT_TIMESTAMP,
   -- Type of action
   `log_action` longtext NOT NULL,
   -- Name of action
   `log_name` varchar(255) NOT NULL,
   -- User who made the action (User table ID)
   `user_id` int(11) NOT NULL,
   -- User's IP
   `ip` int(11) NOT NULL,
   FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
   );

--
-- Dumping test data for table `logs`
--

INSERT INTO `logs` (`id`, `page`, `username`, `log_time`, `log_action`, `log_name`, `user_id`, `ip`) VALUES
(1, 'login.php', 'karen', '2022-06-22 17:18:48', 'Logging in attempt from karen', 'Login Attempt', 4, 10252116156),
(2, 'login.php', 'karen', '2022-06-22 17:18:48', 'Successful login from karen', 'Successful Login', 4, 10252116156);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `meeting_days` varchar(7) NOT NULL,
  `details` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`id`)
);

--
-- Dumping test data for table `sections`
--

INSERT INTO `sections` (`id`, `course_id`, `instructor_id`, `location`, `start_date`, `end_date`, `start_time`, `end_time`, `meeting_days`, `details`, `course_name`) VALUES
(1, 1, 2, 'Raven Rock State Park', '2022-05-01', '2022-05-31', '14:30:00', '16:00:00', '0101010', 'Bring something to write with', 'Fire Safety 101'),
(2, 2, 2, 'Morrow Mountain State Park', '2022-04-30', '2022-05-30', '14:00:00', '16:30:00', '0010100', 'Bring fire safety equipment', 'Fire Safety 201'),
(3, 3, 1, 'Umstead Park', '2022-05-24', '2022-07-24', '13:00:00', '15:15:00', '0000110', 'Bring a bow and arrow', 'Archery 101');

-- --------------------------------------------------------

--
-- Table structure for table `roster`
--

CREATE TABLE `roster` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`), 
  FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`)
);

--
-- Dumping test data for table `roster`
--

INSERT INTO `roster` (`id`, `user_id`, `section_id`) VALUES
(1, 10, 1),
(2, 10, 2),
(3, 13, 3),
(4, 1, 1),
(5, 4, 2),
(6, 10, 3);

-- --------------------------------------------------------
