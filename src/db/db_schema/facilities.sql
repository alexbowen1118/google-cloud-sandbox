-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Sep 02, 2022 at 03:19 PM
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
-- Database: `facilities`
--
CREATE DATABASE IF NOT EXISTS `facilities` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `facilities`;

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE `counters` (
  `id` int(11) NOT NULL,
  `park_code` varchar(16) NOT NULL,
  `counter_num` smallint(6) NOT NULL,
  `counter_name` varchar(32) DEFAULT NULL,
  `counter_function` varchar(28) DEFAULT NULL,
  `counter_type` varchar(24) DEFAULT NULL,
  `method` varchar(32) DEFAULT NULL,
  `time_to_check` varchar(8) DEFAULT NULL,
  `counter_brand` varchar(64) DEFAULT NULL,
  `distance_from_VC` varchar(8) DEFAULT NULL,
  `multiplier` text DEFAULT NULL,
  `lat` varchar(16) DEFAULT NULL,
  `lon` varchar(16) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `see_insight_id` varchar(32) DEFAULT NULL,
  `date_u` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `counters`
--

INSERT INTO `counters` (`id`, `park_code`, `counter_num`, `counter_name`, `counter_function`, `counter_type`, `method`, `time_to_check`, `counter_brand`, `distance_from_VC`, `multiplier`, `lat`, `lon`, `comments`, `see_insight_id`, `date_u`) VALUES
(1650, 'NERI', 4, 'Elk Shoals', 'traffic', 'pneumatic', 'Manual (Visit counter)', '1', '', '17', '4', '36.366542', '-81.432558', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 4 times the 1 way count.', '5fe288934763e7746fed359c', '2022-02-09 21:34:39'),
(1813, 'JORD', 6, 'Park Office', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '1', 'Traffic Tally 200', '', '(4) April - October\r\n(2) November - March\r\n\r\nDue to poor connectivity the Insight Counter is not working correctly   ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1812, 'JORD', 5, 'Parkers Creek', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '1', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1811, 'JORD', 4, 'Seaforth ', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '1', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1810, 'JORD', 3, 'Vista Point', 'traffic', '', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '6', '(4) April - October\r\n(2) November - March\r\n\r\nDue to poor connectivity the Insight Counter is not working correctly   ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1809, 'JORD', 2, 'Robeson Creek Canoe Access ', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '10', '(4) April - October\r\n(2) November - March\r\n\r\nDue to poor connectivity the Insight Counter is not working correctly   ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1808, 'JORD', 1, 'Robeson Creek Boat Ramp', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '10', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly  ', '', '', 'Located at park entrance ', '', '2022-02-10 19:10:01'),
(1764, 'PETT', 1, 'Main Gate', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.25', '', '.001', 'x3 for vehicles and 30 for buses', '35.792080', '-76.409580', '', '5fe39a2765a24d0d23e8b346', '2022-02-10 15:49:20'),
(436, 'WEWO', 1, 'WEWO Main Gate', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.5', '', '.10', '', '35.146863', '-79.371831', '', '', NULL),
(2038, 'HARO', 9, 'Vade Mecum', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.415237', '-80.305819', '', 'B40TAB922K3LRCT', '2022-03-01 00:11:32'),
(2037, 'HARO', 8, 'Orrell Road', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights ', '', '3.5', '36.390102', '-80.224378', '', 'B40TAB922VN8JSR ', '2022-03-01 00:11:32'),
(2036, 'HARO', 7, 'Goat Pen parking lot', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.420825', '-80.287537', '', ' B40KAB841GYUVES', '2022-03-01 00:11:32'),
(2035, 'HARO', 6, 'Moore\' s Wall Climbing Access', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.400486', '-80.290283', '', 'B40KAB840BUHG32', '2022-03-01 00:11:32'),
(2033, 'HARO', 4, 'Mountain bike Farmhouse', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.418575', '-80.283747', '', 'B40KAB8413265JH', '2022-03-01 00:11:32'),
(448, 'LURI', 2, 'LURI Chalk Banks', 'traffic', 'pneumatic', 'Manual (Visit counter)', '2', '', '60 miles', '', '34.899110', '-79.354376', 'Located at Chalk Banks on gravel road at front gate.', '', NULL),
(447, 'LURI', 1, 'LURI Princess Ann', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '25 yds', '', '34.389934', '-79.002685', 'located at Princess Ann on pavement at front gate.', '', NULL),
(2201, 'MEMI', 3, 'Family Campground', 'traffic', 'cellular', 'Automatic (Direct upload)', '0.5', '', '2', '3 per car', '', '', '', '115', '2022-07-26 17:02:43'),
(2200, 'MEMI', 2, 'Boat Ramp', 'traffic', 'cellular', 'Automatic (Direct upload)', '', 'See Insights', '1', '3 per car', '', '', '', '116', '2022-07-26 17:02:43'),
(2199, 'MEMI', 1, 'Visitor Center', 'traffic', 'cellular', 'Automatic (Direct upload)', '', 'See Insights', '0.1', '3 per car', '', '', '', '073', '2022-07-26 17:02:43'),
(2172, 'FOFI', 1, 'Parking Lot North Counter', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '0.1', '4 year round', '33.9652', '-77.9219', '', '5fe399e765a24d0d23e8b1cc', '2022-07-20 16:48:06'),
(2174, 'FOFI', 3, '4WD Beach', 'traffic', '', 'Automatic (Direct upload)', '.5', 'Door King', '0.1', '4 year round', '33.9641', '-77.9227', '4WD beach numbers each car that goes onto the beach is uploaded to a computer that is solely dedicated to that system.', '', '2022-07-20 16:48:06'),
(1219, 'KELA', 6, 'Nutbush North', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 24.622', '78 23.954', 'Info from Bryce F input by Dave Head', '', NULL),
(2180, 'FOMA', 2, 'Fort Gate', 'traffic', 'pneumatic', 'Manual (Visit counter)', '0.5', 'K-Hill &quot;Wee&quot; ', '0.10', 'Number of cars x 3 year round', '34.695305', '-76.680301', 'We have been discussing trying to find a new counter that is better designed for user maintenance (i.e. being able to change the batteries without sending it back).  Also an Inductive-loop system would probably be much more reliable and lower maintenance than the current pneumatic tubes (we probably have to replace the traffic hose 2 or 3 times a year).', '', '2022-07-26 16:01:35'),
(2134, 'CACR', 2, 'NC-Carvers-Creek-2', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '.5', 'See Insights', '13', 'We add this counter to Carvers Creek 1. Then divide this number in half to get the vehicle count and then we multiply it by 3 on the weekdays and 4 on weekends and holidays and add in half the count from Carvers Creek Trail counter to get our total visitation count. ', '35 10\' 13.65&amp', '78 53\' 38.01', 'These are uploaded to a web sight so it only take 5 minutes to check and there is no drive time.', '5c53b04c73efc3398781c3ac', '2022-06-30 20:05:53'),
(2133, 'CACR', 1, 'NC-Carvers-Creek-1', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '.5', 'See Insights', '0.10', 'We add this counter to Carvers Creek 2. Then divide this number in half to get the vehicle count and then we multiply it by 3 on the weekdays and 4 on weekends and holidays and add in half the count from Carvers Creek Trail counter to get our total visitation count. ', '35 11\' 50.85&amp', '78 58\' 30.21', 'These are uploaded to a web sight so it only take 5 minutes to check and there is no drive time.', '5c53a7650ff4c31bdbcbfce0', '2022-06-30 20:05:53'),
(638, 'MEMO', 2, 'Campground/Picnic Area', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.5', 'K-Hill Mini', '1.0', '', '', '', '', '', NULL),
(637, 'MEMO', 1, 'Visitor Center', 'VC', 'pneumatic', 'Manual (Visit counter)', '.25', 'K Hill Mini', '0.0', '', '', '', '', '', NULL),
(2152, 'FALA', 8, 'Rollingview Rec Area', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '9', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '36.008104', '-78.72475', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2151, 'FALA', 7, 'BW Wells', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '9', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '35.995572', '-78.624882', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2150, 'FALA', 6, 'Shinleaf', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '5', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '35.993945', '-78.658480', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2149, 'FALA', 5, 'Holly Point', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '5', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '36.008869', '-78.656332', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2148, 'FALA', 4, 'Beaverdam', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '2', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '36.042566', '-78.697956', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2147, 'FALA', 3, 'Sandling Beach', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '2', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '36.042865', '-78.700470', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2146, 'FALA', 2, 'Highway 50 Boat Ramp', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '', 'Diamond', '1', 'x2 per vehicle Oct-Feb\r\nx4 per vehicle Mar-Sept', '36.023486', '-78.694623', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2145, 'FALA', 1, 'Park Office', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Diamond', '', 'x2 per vehicle year-round', '36.012342', '-78.687415', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2075, 'RARO', 3, 'East Loop', 'traffic', 'cellular', 'Automatic (Direct upload)', '', ' ', '22', 'March-November: 3 people per vehicle\r\nDecember-February: 2 people per vehicle', '35.484175', '-78.903418', 'insight auto', 'NCParks072-SP-RARO-EastLoop-Car ', '2022-03-21 16:33:40'),
(2076, 'RARO', 4, 'West Loop', 'traffic', 'cellular', 'Automatic (Direct upload)', '', '', '22', 'March-November: 3 people per vehicle\r\nDecember-February: 2 people per vehicle', '35.484175', '-78.903418', 'insight auto', 'NCParks123-SP-RARO-WestLoop-Car ', '2022-03-21 16:33:40'),
(2073, 'RARO', 1, 'Main Gate', 'traffic', 'cellular', 'Automatic (Direct upload)', '', '', '.3', 'March-November:\r\n3 people per car on weekday\'s &amp; 4 on weekends and state holidays.\r\nDecember - February:\r\n2 people per car on weekdays &amp; 4 people on weekends and state holidays.', '35.460127', '-78.912943', 'insight auto', 'NCParks156-SP-RARO-Main-Car ', '2022-03-21 16:33:40'),
(1899, 'CRMO', 2, 'Linwood Access Area', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3 Weekday\r\n4 Weekend', '35.24108', '-81.268912', 'We do not have a method to record buses. ', '5c77eb1293f3c302c7ad30aa', '2022-02-28 17:34:08'),
(729, 'DISW', 1, 'DISW Parking Lot Counter', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.5', 'K-Hill', '.10', '', '36.5057', '-76.3551', 'We have one counter that is manually checked each morning by the first staff member to arrive. the number is then taken, the number from the previous day is subtracted and that is how we get the number for previous days visitation.', '', NULL),
(2128, 'ENRI', 5, 'Occoneechee Mtn.', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights', '', 'See Insights is set up to count two way traffic, but divide the number in two, thus providing a correct measure of the number of cars that visited the park. That number is then multiplied by 3.5 to account for persons contained within the cars. Therefore, the formula is: (# of cars) x 3.5 = visitation.', '36.05967', '-79.11305', 'NCParks088 (Device Name)', '5fe399cd65a24d0d23e8b118', '2022-03-25 13:29:36'),
(1645, 'MOMO', 2, 'Falls Road Access', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', 'See Insights', '6.3', '4', '35.3991', '-80.1035', 'Installed Oct 2021 on Falls Road after NCDOT right-of-way encroachment agreement (E101-084-21-00136).', '5fe399c665a24d0d23e8b0eb', '2022-02-09 21:05:40'),
(694, 'MARI', 1, 'Mayo Mountain Access', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'KHill wee Counter', '.70', '', '36.4310', '-79.9509', 'Located at gate, no significant time needed to check it', '', NULL),
(695, 'MARI', 2, 'Mayodan Access', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'KHill wee Counter', '2', '', '36.4074', '-79.9895', 'We visit each access daily, so we aren\'t making special trips, at least in general. ', '', NULL),
(696, 'MARI', 3, 'Hickory Creek Access', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'KHill wee Counter', '11', '', '36.5038', '-80.0048', 'We visit each access daily, so we aren\'t making special trips, at least in general. ', '', NULL),
(697, 'MARI', 4, 'Deshazo Mill Access', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'KHill wee Counter', '16', '', '36.5411', '-79.9829', 'We visit each access daily, so we aren\'t making special trips, at least in general. ', '', NULL),
(698, 'MARI', 5, 'Anglin Mill Access', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'KHill wee Counter', '17', '', '36.5297', '-79.9650', 'We visit each access daily, so we aren\'t making special trips, at least in general. ', '', NULL),
(1788, 'LAJA', 3, 'Canal Bridge Access', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '1.335', '35.740654', '-81.884156', '', '60e0516573efc354d1ce1ebf', '2022-02-10 17:08:57'),
(1855, 'SOMO', 1, 'Jacob Fork (manual)', 'traffic', 'pneumatic', 'Manual (Visit counter)', '0.5', 'Wee Counter', '0.25', '4, divided by 2, because tube runs across entire road.  Multiplier is high for the Jacob Fork Access, but probably low when considering all of the visitation coming from unregulated access points.', '35.59371', '-81.59697', 'Our original counter that I have not yet taken out of service.  We are using it to compare to the new counter.', '', '2022-02-14 19:48:17'),
(1857, 'SOMO', 3, 'Clear Creek', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'Seeinsights', '35', '4, divided by 2', '35.64334', '-81.75305', 'Works ok.  Due to location and slow speeds when entering the lot, it seems to double count a lot of the traffic.  We hope to replace with an inground model when he gets it ready.', '5c9538870ff4c33a6bf55855 ', '2022-02-14 19:48:17'),
(2140, 'GRMO', 5, 'Nuwati Trail ', 'trail', 'cellular', 'Manual (Visit counter)', '1.5', '', '6.4', '1:1 Hikers are counted as they enter the trail.', '36.1164', '-81.7808', 'Round trip hiking distance is .8 miles to read this counter. ', '5fe3996d65a24d0d23e8aecd', '2022-06-30 21:00:00'),
(1195, 'PIMO', 8, 'Mtn Trail', 'trail', 'cellular', 'Automatic (Direct upload)', '.5', 'Spartan Blackout infrared trail cam', '', '', '36.328929', '-80.464635', 'on state data plan, sends a pic or short video a few seconds after visitor triggers camera. Moved around to temporary or problem areas\r\n\r\n', '', NULL),
(1191, 'PIMO', 4, 'Bean Shoals Hiking', 'trail', 'cellular', 'Automatic (Direct upload)', '', '', '10', '', '36.267356', '-80.495390', '', 'e00fce6811b3eb6308d0788a', NULL),
(1192, 'PIMO', 5, 'Bean Shoals Rd', 'traffic', 'cellular', 'Automatic (Direct upload)', '', 'Seeinsights', '10', '', '36.268075', '-80.492325', 'currently disabled, road is closed\r\n\r\n', '2d002b001550483553353620', NULL),
(1193, 'PIMO', 6, 'Ivy Bluffs Road', 'traffic', 'cellular', 'Automatic (Direct upload)', '', 'Seeinsights', '21', '', '36.253226', '-80.509035', '', '210023000c47373334363431', NULL),
(1218, 'KELA', 5, 'Nutbush South', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 24.541', '78 23.937', 'Info from Bryce F input by Dave Head', '', NULL),
(1217, 'KELA', 4, 'Steel Creek', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 28.830', '78 23.933', 'Info from Bryce F input by Dave Head', '', NULL),
(1216, 'KELA', 3, 'Hibernia', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 30.292', '78 22.580', 'Info from Bryce F input by Dave Head', '', NULL),
(1194, 'PIMO', 7, 'Ivy Bluffs Trail', 'trail', 'cellular', 'Automatic (Direct upload)', '', 'Seeinsights', '21', '', '36.254107', '-80.508325', '', '3e002e001550483553353620', NULL),
(1190, 'PIMO', 3, 'Corridor North', 'trail', 'cellular', 'Automatic (Direct upload)', '', '', '3.5', '', '36.326979', '-80.463530', '', '340035000647373432363837', NULL),
(1188, 'PIMO', 1, 'Pilot Creek', 'trail', 'cellular', 'Automatic (Direct upload)', '', 'Seeinsights', '3.5', '', '36.358448', '-80.493184', '', '3a0041000647373336373936', NULL),
(1189, 'PIMO', 2, 'Mtn road', 'traffic', 'cellular', 'Automatic (Direct upload)', '', 'Seeinsights', '.2', '', '36.342502', '-80.462147', '', '410027000647373432363837', NULL),
(1215, 'KELA', 2, 'Henderson Point', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 32.138', '78 20.802', 'Info from Bryce F input by Dave Head', '', NULL),
(1214, 'KELA', 1, 'Front Entrance', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 26.393', '78 22.066', 'Info from Bryce F input by Dave Head', '', NULL),
(1220, 'KELA', 7, 'Bullocksville', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 27.454', '78 21.767', 'Info from Bryce F input by Dave Head', '', NULL),
(1221, 'KELA', 8, 'County Line', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 31.426', '78 18.990', 'Info from Bryce F input by Dave Head', '', NULL),
(1222, 'KELA', 9, 'Kimball Point', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', '', '', '', '36 32.206', '78 18.604', 'Info from Bryce F input by Dave Head', '', NULL),
(1877, 'CHRO', 2, 'Eagle Rock', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '1', 'See Insights', '12', '2', '35&deg;28\'22N', '82&deg;14\'39W', '', 'e00fce68e6028344133b193c', '2022-02-15 16:00:10'),
(1878, 'CHRO', 3, 'Chimney Rock Ticket Plaza', 'VC', 'pneumatic', 'Manual (Visit counter)', '.5', 'Point of Sale System', '', '', '35&deg;25\'59N', '82&deg;14\'22W', 'Ticket Plaza POS system, tickets are sold per person and system/contractor sends us totals through e-mail.  ', '', '2022-02-15 16:00:10'),
(1634, 'HABE', 1, 'Main Road', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3', '34 40\' 20&quot;', '77 08\' 25&quot;', '', '', '2022-02-09 18:33:37'),
(1636, 'SILA', 1, 'NCParks124-SP-SILA-SingletaryLak', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights', '.07', '3', '34.580296', '78.449233', '', '5fe399fd65a24d0d23e8b25c', '2022-02-09 18:46:32'),
(2144, 'LAWA', 3, 'Dam', 'traffic', 'pneumatic', 'Manual (Visit counter)', '1', '', '10', '', '34.2611155', '-78.5235526', '', '', '2022-07-01 12:36:48'),
(2143, 'LAWA', 2, 'Big Creek Ramp', 'traffic', 'pneumatic', 'Manual (Visit counter)', '1', '', '1/4 mile', '', '34.290614', '-78.4720417', '', '', '2022-07-01 12:36:48'),
(2142, 'LAWA', 1, 'Visitor Center', 'traffic', 'pneumatic', 'Manual (Visit counter)', '1', '', '.2', '', '34.2794669', '-78.4652304', '', '', '2022-07-01 12:36:48'),
(1346, 'WIUM', 5, 'Umstead-Reedy', 'trail', 'Infra-Red', 'Automatic (Direct upload)', 'n/a', 'See Insights', '18', '', '35.8606', '-78.7711', 'Old Reedy Creek Road Gate.  Pedestrian Entrance.', '59a949147625427e334d0e8b', NULL),
(1342, 'WIUM', 1, 'Umstead-70', 'traffic', 'pneumatic', 'Automatic (Direct upload)', 'n/a', 'See Insights', '.5', '', '35.8888', '-78.7542', '8801 Glenwood Avenue.  Crabtree Creek Entrance.', '59a9570e7625420568efdd9a', NULL),
(1345, 'WIUM', 4, 'Umstead-40', 'traffic', 'pneumatic', 'Automatic (Direct upload)', 'n/a', 'See Insights', '15', '', '35.8346', '-78.7599', '2100 North Harrison Avenue.  Reedy Creek Entrance.', '59446a1b7625420bdacf7f87', NULL),
(1888, 'STMO', 2, 'Stone Mountain Rd. at Longbottom', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'See Insights', '', '4', '36.3733', '-81.0695', 'Counter is set to count from 6am to 10pm and reports every hour.', '5fe39a3b65a24d0d23e8b3c4', '2022-02-15 16:08:42'),
(2207, 'CLNE', 1, 'Park Entrance Counter', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.5', '', '.05', 'Mon-Thur: cars x3\r\nFri-Sun: cars x4', '', '', '', '', '2022-08-02 17:59:14'),
(1644, 'MOMO', 1, 'Main Park Entrance', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', 'See Insights', '1.75', '4', '35.369783', '-80.093286', 'Installed May 2021 near front gate', '5fe39a2a65a24d0d23e8b358', '2022-02-09 21:05:40'),
(1364, 'HARI', 1, 'Iron Ore Belt Access', 'traffic', 'pneumatic', 'Manual (Visit counter)', '0.5', 'Nano Count 1000', '6', '', '36.23721', '-79.78527', 'Counter located at main entrance, by the gate, off 6068 North Church Street.\r\n\r\nNo longer operational; continued problems with the internal battery as of 2020-08-05.', '', NULL),
(1343, 'WIUM', 2, 'Umstead-Graylyn', 'trail', 'cellular', 'Automatic (Direct upload)', 'n/a', 'See Insights', '4', '', '35.8712', '-78.7464', 'Graylyn Drive Pedestrian Entrance.', '599f1d0f7625420a5f4437e1', NULL),
(1344, 'WIUM', 3, 'Umstead-Trenton', 'trail', 'cellular', 'Automatic (Direct upload)', 'n/a', 'See Insights', '10', '', '35.8294', '-78.7349', 'Pedestrian gate at intersection of Trenton Road and Reedy Creek Road.', '59a976087625425d6d08bc93', NULL),
(1649, 'NERI', 3, 'Kings Creek Access Gate', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', '', '6', '4', '36.5276', '-81.3367', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 4 times the 1 way count.', '5fe399a065a24d0d23e8b009', '2022-02-09 21:34:39'),
(1648, 'NERI', 2, 'Wagoner Access Gate', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '1', '', '12', '4', '36.4141', '-81.3896', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 4 times the 1 way count.', '60d8d90173efc36d841e8881', '2022-02-09 21:34:39'),
(1647, 'NERI', 1, '221 Access Gate', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', '', '0.2', '4', '36.4654', '-81.3410', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 4 times the 1 way count.', '5fe399bf65a24d0d23e8b0c6', '2022-02-09 21:34:39'),
(2139, 'GRMO', 4, 'Daniel Boone Scout Trail ', 'trail', 'cellular', 'Manual (Visit counter)', '1.5', '', '6.4', '1:1 Hikers are counted as they enter the trail.', '36.1138', '-81.7810', 'Round trip hiking distance is 1.2 miles to read this counter.', '5fe3998c65a24d0d23e8af82', '2022-06-30 21:00:00'),
(2132, 'MOMI', 2, 'NCParks017', 'trail', 'Infra-Red', 'Manual (Visit counter)', '.5', 'See Insights', '', '1.25', '35.754970', ' -82.255372', 'Counter inputs foot traffic as it enters and exits.', 'e00fce68bda02226c5838065', '2022-06-13 19:33:43'),
(1876, 'CHRO', 1, 'Rumbling Bald', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '.5', 'See Insights', '4', '2', '35&deg;26\'37N', '82&deg;13\'06W', '', 'e00fce6855d440fd3d6af6a3', '2022-02-15 16:00:10'),
(2160, 'ELKN', 1, 'Entrance 2', 'traffic', 'Infra-Red', 'Manual (Visit counter)', '.5', 'Trafx', '.05', '1', '36*19\'57.6393&am', '81*41\'45.9489&am', 'Magnetic, calibrates to the Earth\'s magnetic core.  ', '', '2022-07-07 23:47:34'),
(2138, 'GRMO', 3, 'Black Rock Trail ', 'trail', 'cellular', 'Manual (Visit counter)', '1', '', '12.2', '1:1 Hikers are counted as they enter the trail.', '36.0955', '-81.8290', 'Accessed through the Grandfather Mountain Stewardship Foundation.', '6083003673efc33dd1700a43', '2022-06-30 21:00:00'),
(2170, 'JORI', 1, 'Front gate', 'traffic', 'cellular', 'Automatic (Direct upload)', '0.5', '', '0.1', 'Number of cars multiplied by 4 (average # of people visiting in each car) then total from all counters multiplied by 1.5 to catch visitors walking in from all boundaries.', '35.96515543', '-75.63319522', 'See Insights emails weekly reports and data.', '5fe39a2165a24d0d23e8b322', '2022-07-12 19:34:44'),
(1894, 'GORG', 2, 'Frozen Creek', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', 'SeeInsights', '13', '3', '35.1086', '-82.8837', 'Tube is set to measure two-way traffic, but is divided by two for reports. ', '5fe3999a65a24d0d23e8afdc', '2022-02-19 21:09:09'),
(1893, 'GORG', 1, 'Grassy Ridge ', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', 'SeeInsights', '0.25', '3', '35.0700', '-82.9211', 'Tube is set to measure one-way traffic.', '5c37a4450ff4c3748fa4c950', '2022-02-19 21:09:09'),
(2161, 'ELKN', 2, 'Entrance 1', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights ID # ', '', '2', '36*19\'57.6393&am', '81*41\'45.9489&am', 'Will not be able to use when road is frozen. \r\n\r\nDoes not work since it is solar powered.  The weather is too cold and cloudy.  \r\n ', 'B40TAB922C2V9JE', '2022-07-07 23:47:34'),
(1651, 'MOJE', 1, 'Main Park Gate', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '0.5', 'See Insights', '0.1', '4', '36.3930', '-81.4677', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 4 times the 1 way count.', '5fe399dd65a24d0d23e8b184', '2022-02-09 21:35:25'),
(1786, 'LAJA', 1, 'Catawba River Area', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '35.728313', '-81.902024', '', '5fe399a465a24d0d23e8b024', '2022-02-10 17:08:57'),
(1787, 'LAJA', 2, 'Hidden Cove Access', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '1.335', '35.728826', '-81.892081', '', '5fe399df65a24d0d23e8b196', '2022-02-10 17:08:57'),
(1765, 'PETT', 2, 'Cypress Point', 'traffic', 'pneumatic', 'Manual (Visit counter)', '0.5', '', '10', 'x3 for vehicles and 30 for buses', '35.786951', '-76.519305', '', '5fe399b265a24d0d23e8b075', '2022-02-10 15:49:20'),
(1789, 'LAJA', 4, 'Paddy\'s Creek Area', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights ', '', '3.5', '35.749696', '-81.892275', '', '5ee105fa73efc32cf7d6277e', '2022-02-10 17:08:57'),
(2136, 'GRMO', 1, 'Profile Trail', 'trail', 'cellular', 'Automatic (Direct upload)', '.5', '', '3', '1:1 Hikers are counted as they enter the trail.', '36.1192', '-81.8327', 'Accessed at the Profile Trailhead near restroom facilities.', '5fe3996965a24d0d23e8aeb2', '2022-06-30 21:00:00'),
(2137, 'GRMO', 2, 'Grandfather Trail', 'trail', 'cellular', 'Manual (Visit counter)', '1', '', '12.4', '1:1 Hikers are counted as they enter the trail.', '36.0965', '-81.8317', 'Accessed through the Grandfather Mountain Stewardship Foundation.', '5fe3998865a24d0d23e8af67', '2022-06-30 21:00:00'),
(1814, 'JORD', 7, 'White Oak', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '.5', '(4) April - October\r\n(2) November - March\r\n\r\nDue to poor connectivity the Insight Counter is not working correctly   ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1815, 'JORD', 8, 'Crosswinds', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '1', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1816, 'JORD', 9, 'Poplar Point', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '1', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1817, 'JORD', 10, 'Ebenezer Day Use', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '3', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1818, 'JORD', 11, 'Ebenezer Boat ramp', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '3', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1819, 'JORD', 12, 'New Hope', 'traffic', 'Inductive-loop', 'Manual (Visit counter)', '.5', 'Traffic Tally 200', '6', '(4) April - October\r\n(2) November - March  \r\n\r\nDue to poor connectivity the Insight Counter is not working correctly ', '', '', 'Located at park entrance', '', '2022-02-10 19:10:01'),
(1832, 'GOCR', 1, 'Main Park', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '0.25', '2', '35.4818', '-76.9014', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 2 times the 1 way count.', '5fe39a2965a24d0d23e8b34f', '2022-02-14 15:50:39'),
(1833, 'GOCR', 2, 'Dinah Landing', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '8', '4', '35.4798861', '-76.9318891', 'Tube is set up to measure 2 way traffic, See Insights has it figured where result in reports are 1 way. Our multiplier is 4 times the 1 way count.', '5fe39a0f65a24d0d23e8b2bf', '2022-02-14 15:50:39'),
(1829, 'JONE', 1, 'Entrance Counter', 'traffic', 'pneumatic', 'Automatic (Direct upload)', 'N/A', 'See Insights', '0.1', 'A multiplier of 3 is used throughout the year.  In 2018 a vehicle count study was done and this multiplier is based on that study.  ', '34.682743', '78.595423', '', '5FE399DE65A24D0D23E6B18D', '2022-02-14 15:06:39'),
(1856, 'SOMO', 2, 'Jacob Fork (Auto)', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'Seeinsights', '.1', '4', '35.59588', '-81.60030', 'Works well.  Seems to over record by about 5% as compared to the manual counter.  Chip is working on some upgrades, and SOMO is supposed to receive one when ready.', '5cadd8c893f3c31a3b66e316', '2022-02-14 19:48:17'),
(2173, 'FOFI', 2, 'Parking Lot South Counter', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '0.1', '4 year round', '33.9647', '-77.9225', '', '5fe39a1b65a24d0d23e8b2fe', '2022-07-20 16:48:06'),
(1887, 'STMO', 1, 'John P Frank (upper) Gate', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'See Insights', '', '4', '36.3761', '-81.0183', 'Counter is set to count from 4am to 11pm a day  and reports every hour.  See Insights is still working on setting it for 24hr operations based on location of counter.', '5fe399ae65a24d0d23e8b05a', '2022-02-15 16:08:42'),
(2131, 'MOMI', 1, 'Main Gate Counter', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.5', 'See Insights', '', '3.5', '35.4441', '-82.1639', 'Counter inputs traffic as it enters and exits.', 'e00fce68ecb6fc1265466e49', '2022-06-13 19:33:43'),
(1900, 'CRMO', 3, 'Boulders Access Area', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3 Weekday\r\n4 Weekend', '35.17109', '-81.36413', 'We do not have a method to record buses. ', '5c77eba973efc35f696af20e', '2022-02-28 17:34:08'),
(1898, 'CRMO', 1, 'Sparrow Springs Access Area', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3 Weekday\r\n4 Weekend', '35.21197', '-81.29255', 'We do not have a method to record buses. ', '5cb8c6d60ff4c30b98b89c9a', '2022-02-28 17:34:08'),
(2034, 'HARO', 5, 'Torys Den', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5 ', '36.401500', '-80.30000', 'Counted once, this is not two way. This is one way traffic.', 'B40KAB9268T4KR9', '2022-03-01 00:11:32'),
(2032, 'HARO', 3, 'Lower Cascades', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.414331', '-80.264634', '', 'B40KAB840KYJUXH', '2022-03-01 00:11:32'),
(2031, 'HARO', 2, 'Dan River', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.428007', '-80.248593', '', 'B40KAB841945F2Y', '2022-03-01 00:11:32'),
(2030, 'HARO', 1, 'Admin', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '', '3.5', '36.401339', '-80.267071', '', 'B40KAB841U63KGL', '2022-03-01 00:11:32'),
(2074, 'RARO', 2, 'Bike Field', 'traffic', 'cellular', 'Automatic (Direct upload)', '', '', '.8', 'Two people per car on weekdays and 4 people per car on weekends.', '35.454758', '-78.909045', 'insight auto ', '1W-Daily (NCParks165-SP-RARO-Bik', '2022-03-21 16:33:40'),
(2130, 'ENRI', 7, 'Pump Station - Lower', 'trail', 'Infra-Red', 'Automatic (Direct upload)', '', 'See Insights', '', 'No multiplier is used, because individual persons are counted. However, similar to the traffic counters, See Insights has these sensors set up to divide their final numbers by two in order to account for these hikers coming back out the same way, which is what occurs the majority of the time (the hikers park at this trail head and come back the same way).', '36.05853', '-78.96578', 'NCParks005 (Device Name)', '5fe3996565a24d0d23e8ae97', '2022-03-25 13:29:36'),
(2129, 'ENRI', 6, 'Pump Station - Upper', 'trail', 'Infra-Red', 'Automatic (Direct upload)', '', 'See Insights', '', 'No multiplier is used, because individual persons are counted. However, similar to the traffic counters, See Insights has these sensors set up to divide their final numbers by two in order to account for these hikers coming back out the same way, which is what occurs the majority of the time (the hikers park at this trail head and come back the same way).', '36.05896', '-78.96921', 'NCParks033 (Device Name)', '5fe3998d65a24d0d23e8af8b', '2022-03-25 13:29:36'),
(2127, 'ENRI', 4, 'Cabelands', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights', '', 'See Insights is set up to count two way traffic, but divide the number in two, thus providing a correct measure of the number of cars that visited the park. That number is then multiplied by 3.5 to account for persons contained within the cars. Therefore, the formula is: (# of cars) x 3.5 = visitation.', '36.03938', '-78.99082', 'NCParks114 (Device Name)', '5fe399ef65a24d0d23e8b202', '2022-03-25 13:29:36'),
(2124, 'ENRI', 1, 'Few\'s Ford', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights', '', 'See Insights is set up to count two way traffic, but divide the number in two, thus providing a correct measure of the number of cars that visited the park. That number is then multiplied by 3.5 to account for persons contained within the cars. Therefore, the formula is: (# of cars) x 3.5 = visitation.', '36.07792', '-79.00536', 'NCParks137 (Device Name)', '5fe39a1365a24d0d23e8b2d1', '2022-03-25 13:29:36'),
(2126, 'ENRI', 3, 'Pleasant Green', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights', '', 'See Insights is set up to count two way traffic, but divide the number in two, thus providing a correct measure of the number of cars that visited the park. That number is then multiplied by 3.5 to account for persons contained within the cars. Therefore, the formula is: (# of cars) x 3.5 = visitation.', '36.04684', '-79.0114', 'NCParks139 (Device Name)', '5fe39a1765a24d0d23e8b2e3', '2022-03-25 13:29:36'),
(2125, 'ENRI', 2, 'Cole Mill', 'traffic', 'Inductive-loop', 'Automatic (Direct upload)', '', 'See Insights', '', 'See Insights is set up to count two way traffic, but divide the number in two, thus providing a correct measure of the number of cars that visited the park. That number is then multiplied by 3.5 to account for persons contained within the cars. Therefore, the formula is: (# of cars) x 3.5 = visitation.', '36.05929', '-78.98031', 'NCParks121 (Device Name)', '5fe399f965a24d0d23e8b241', '2022-03-25 13:29:36'),
(2135, 'CACR', 3, 'NC-Carvers-Creek-Trail', 'trail', 'Infra-Red', 'Automatic (Direct upload)', '.5', 'See Insights', '15', 'We take this number and split it in half. Add half to the visitation count.', '35 11\' 44.25&amp', '78 52\' 17.24', 'These are uploaded to a web sight so it only take 5 minutes to check and there is no drive time.', '5d5ed1d073efc36e27c01379', '2022-06-30 20:05:53'),
(2141, 'GRMO', 6, 'Profile Facility-car', 'traffic', 'cellular', 'Automatic (Direct upload)', '', '', '', '2.5 visitors per car.', '36.06069', '-81.84621', 'Accessed on right side of Profile Trail entrance road.', '5fe399f565a24d0d23e8b226', '2022-06-30 21:00:00'),
(2153, 'FALA', 9, 'Rollingview Marina', 'traffic', 'pneumatic', 'Manual (Visit counter)', '', 'Diamond Traffic Tally 2', '9', 'x2 per vehicle year-round', '36.007583', '-78.726157', 'Counter owned and maintained by US Army Corps of Engineers, not DPR.  Time needed to read daily is actually about 5 minutes for each, as closing staff has to close gates or check campers in each area and just reads the counter as part of normal closing duties. No additional distance is added as park staff are already driving to all of those areas anyway as part of closing.', '', '2022-07-01 21:54:06'),
(2169, 'LANO', 2, 'St. Johns', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '2.0', 'This park uses a multiplier of 3 year round.', '35.668622', '-80.944355', '', 'NCParks148', '2022-07-09 21:40:34'),
(2168, 'LANO', 1, 'Main Gate', 'traffic', 'pneumatic', 'Automatic (Direct upload)', '', 'See Insights', '0.75', 'This park uses a multiplier of 3 year round.', '35.668622', '-80.926233', '', 'NCParks069', '2022-07-09 21:40:34'),
(2171, 'JORI', 2, 'Back gate', 'traffic', 'cellular', 'Automatic (Direct upload)', '0.5', '', '1', 'Number of cars multiplied by 4 (average # of people visiting in each car) then total from all counters multiplied by 1.5 to catch visitors walking in from all boundaries.', '35.95178665', '-75.63185746', 'See Insights emails weekly reports and data.', '5fe399e265a24d0d23e8b1a8', '2022-07-12 19:34:44'),
(2179, 'FOMA', 1, 'Bathhouse Gate', 'traffic', 'pneumatic', 'Manual (Visit counter)', '0.5', 'K-Hill &quot;Wee&quot; ', '1.0', 'Number of cars x 3 year round', '34.697076', ' -76.698093', 'We have been discussing trying to find a new counter that is better designed for user maintenance (i.e. being able to change the batteries without sending it back).  Also an Inductive-loop system would probably be much more reliable and lower maintenance than the current pneumatic tubes (we probably have to replace the traffic hose 2 or 3 times a year).', '', '2022-07-26 16:01:35'),
(2228, 'CABE', 1, 'CABE', 'traffic', 'pneumatic', 'Manual (Visit counter)', '.5', 'See Insights', '.25', 'Cars: x4 year round\r\nBuses: x30 year round\r\n\r\nBoaters, Auditorium use based on actual numbers and permits.', '34.045372', '77.904414', 'See Insights emails weekly reports and data.', '5fe399f465a24d0d23e8b21d', '2022-09-02 15:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `counter_needs`
--

CREATE TABLE `counter_needs` (
  `id` int(11) NOT NULL,
  `park_code` varchar(16) NOT NULL,
  `num_need` smallint(6) NOT NULL,
  `time_to_check_need` varchar(8) DEFAULT NULL,
  `distance_from_VC_need` varchar(8) DEFAULT NULL,
  `counter_function_need` varchar(24) DEFAULT NULL,
  `counter_type_need` varchar(24) DEFAULT NULL,
  `comments_need` text DEFAULT NULL,
  `date_u` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `counter_needs`
--

INSERT INTO `counter_needs` (`id`, `park_code`, `num_need`, `time_to_check_need`, `distance_from_VC_need`, `counter_function_need`, `counter_type_need`, `comments_need`, `date_u`) VALUES
(83, 'WEWO', 2, '', '3', 'trail', 'celluar', 'We are not capturing any visitation from Paint Hill', '0000-00-00 00:00:00'),
(82, 'WEWO', 1, '', '5', 'trail', 'celluar', 'WE are not capturing any visitation from Boyd tract', '0000-00-00 00:00:00'),
(94, 'LURI', 2, '1', '35', 'traffic', 'celluar', 'One needed at Recreation Center Drive Access', '0000-00-00 00:00:00'),
(93, 'LURI', 1, '1', '35 miles', 'traffic', 'celluar', 'One needed at Wire Pasture when it gets developed and properly staffed', '0000-00-00 00:00:00'),
(111, 'MEMO', 1, '.5', '2.5', 'traffic', 'IR', 'We need a third counter for the Equestrian Trail parking lot.', '0000-00-00 00:00:00'),
(352, 'MEMI', 1, '1 Minute', '3', 'traffic', 'IR', 'Needed to count cars at Bike trail entrance.  This area never has been counted.', '2022-07-26 17:02:39'),
(144, 'BEPA', 2, '.5', '9', 'trail', 'IR', 'BEPA has seen a significant increase in visitation in 2020. ', '0000-00-00 00:00:00'),
(143, 'BEPA', 1, '.5', '9', 'trail', 'IR', 'BEPA has seen a significant increase in visitation in 2020. ', '0000-00-00 00:00:00'),
(254, 'WIUM', 1, '.5', '12', 'trail', 'celluar', 'Ebenezer Church Road.  There is a pull over where a significant number of vehicles park and there is also a planed City of Raleigh Greenway connection at this location that will be constructed this year (2020).  This will be a critical area to count visitors at.  The area does not have electricity so a solar counter or meter base will need to be installed.', '0000-00-00 00:00:00'),
(226, 'PIMO', 1, '', '3.5', 'trail', 'celluar', 'needed to replace trail cam at Mtn Trail ', '0000-00-00 00:00:00'),
(227, 'PIMO', 2, '', '10', 'trail', 'celluar', 'needed for Ararat River- Not officially open but people are visiting it anyway/walking in', '0000-00-00 00:00:00'),
(353, 'CLNE', 1, '.05', '.05', 'traffic', 'pneumatic', '', '2022-08-02 17:59:14'),
(347, 'JORI', 1, '', '', 'trail', 'celluar', 'See Insights emails weekly reports and data.\r\n5fe3997965a24d0d23e8af15', '2021-09-02 14:35:35'),
(292, 'LAJA', 2, '.5', '6', 'trail', 'IR', 'This location is at our Fonta Flora State Trail, Long Arm Trailhead. ', '2022-02-10 17:07:17'),
(291, 'LAJA', 1, '.5', '.5', 'trail', 'IR', 'This location is at our mountain bike trailhead', '2022-02-10 17:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `spo_dpr`
--

CREATE TABLE `spo_dpr` (
  `hcpacces` text NOT NULL,
  `gis_id` varchar(16) NOT NULL,
  `park_abbr` varchar(4) NOT NULL,
  `fac_name` text NOT NULL,
  `sub_unit` text NOT NULL,
  `fac_type` varchar(40) NOT NULL,
  `capacity` text NOT NULL,
  `status` text NOT NULL,
  `site_num` text NOT NULL,
  `crs_idn` text NOT NULL,
  `doi_id` varchar(24) NOT NULL,
  `fip` text NOT NULL,
  `datecreate` text NOT NULL,
  `daterenova` text NOT NULL,
  `comment` text NOT NULL,
  `historic` text NOT NULL,
  `lat` text NOT NULL,
  `long` text NOT NULL,
  `county` varchar(16) NOT NULL,
  `spo_assid` int(11) NOT NULL,
  `area_sqft` float NOT NULL COMMENT 'facility area in square feet'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counter_needs`
--
ALTER TABLE `counter_needs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spo_dpr`
--
ALTER TABLE `spo_dpr`
  ADD PRIMARY KEY (`gis_id`),
  ADD KEY `park_abbr` (`park_abbr`),
  ADD KEY `doi_id` (`doi_id`),
  ADD KEY `fac_type` (`fac_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2229;

--
-- AUTO_INCREMENT for table `counter_needs`
--
ALTER TABLE `counter_needs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
