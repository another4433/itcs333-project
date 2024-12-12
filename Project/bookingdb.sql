-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 12, 2024 at 06:32 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `BookingID` int NOT NULL AUTO_INCREMENT,
  `PersonID` int NOT NULL,
  `RoomID` int NOT NULL,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `Date` date NOT NULL,
  `Description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`BookingID`),
  KEY `RoomID` (`RoomID`),
  KEY `PersonID` (`PersonID`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BookingID`, `PersonID`, `RoomID`, `StartTime`, `EndTime`, `Date`, `Description`) VALUES
(14, 4, 8, '09:00:00', '10:00:00', '2024-12-12', 'Economy Tutorial ');

-- --------------------------------------------------------

--
-- Table structure for table `favourite`
--

DROP TABLE IF EXISTS `favourite`;
CREATE TABLE IF NOT EXISTS `favourite` (
  `RoomID` int NOT NULL,
  `PersonID` int NOT NULL,
  PRIMARY KEY (`RoomID`,`PersonID`),
  KEY `PersonID` (`PersonID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favourite`
--

INSERT INTO `favourite` (`RoomID`, `PersonID`) VALUES
(7, 4),
(8, 3),
(12, 4);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
  `PersonID` int NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `LastName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `hasAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `ImageName` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`PersonID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`PersonID`, `FirstName`, `LastName`, `Email`, `Password`, `hasAdmin`, `ImageName`) VALUES
(1, 'Super', 'Admin', '999999999@stu.uob.edu.bh', '$2y$10$sCgyzqfmnHtrbSCwUFt9SeVSnATgOD7dK5aLwq2kjhf/ZXv1Np9nC', 1, NULL),
(2, 'Ali', 'Othman', '202209999@stu.uob.edu.bh', '$2y$10$S5qb7NhO7mFATSTjTlCP6ec7TpDvT1HNKkxR.0zBthjrpGDv/UqKO', 0, NULL),
(3, 'saud', 'salah', '237283627467@stu.uob.edu.bh', '$2y$10$z6WmWkmuhXZwKNhOlMj/u.p8dKc4UBmWMj0vyuNzxf1GqZTliOW82', 0, NULL),
(4, 'Ali', 'Othman', '202229999@stu.uob.edu.bh', '$2y$10$uMdnWk0JOoIgxu3OeQhfa.O.ayGSnMuSHPnTNESRdgWMWfKR.ubQ2', 0, 'Images/default.png'),
(5, 'Abbas', 'Hassan', '202239999@stu.uob.edu.bh', '$2y$10$8cfbqBulGpu/NobwTrETyukQ5n7s170M18No8sB5rl9wkcy.cj6ui', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `RoomID` int NOT NULL AUTO_INCREMENT,
  `RoomName` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Location` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Capacity` int DEFAULT NULL,
  `HasPCs` tinyint(1) NOT NULL,
  `HasProjectors` tinyint(1) NOT NULL,
  `ImageName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`RoomID`),
  UNIQUE KEY `RoomName` (`RoomName`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`RoomID`, `RoomName`, `Location`, `Capacity`, `HasPCs`, `HasProjectors`, `ImageName`) VALUES
(8, 'S1B-158', 'S1B', 35, 0, 0, '674d86d608ed1.jpg'),
(7, 'S1B-156', 'S1B', 32, 0, 0, '674d8604767c1.jpg'),
(6, 'S1B-042', 'S1B', 50, 0, 0, '674d8329ad431.jpg'),
(9, 'S40-049', 'S40', 55, 0, 1, '674d87283f909.jpg'),
(10, 'S40-060', 'S40', 53, 0, 1, '674d87865b2d1.jpg'),
(11, 'S40-086', 'S40', 65, 1, 1, '674d87dc10cdd.jpg'),
(12, 'S40-1014', 'S40', 45, 1, 1, '674d899188564.jpg'),
(13, 'S40-1047', 'S40', 40, 0, 0, '674d8a045d1a3.jpg'),
(14, 'S40-1050', 'S40', 57, 1, 1, '674d8a3470475.jpg'),
(15, 'S40-2043', 'S40', 62, 1, 1, '674d8a97d3a2e.jpg'),
(16, 'S40-2045', 'S40', 45, 1, 1, '674d8b11c6166.jpg'),
(17, 'S40-2081', 'S40', 65, 1, 1, '674d8b3a813ce.jpg'),
(18, 'S40-2086', 'S40', 35, 0, 1, '674d8b73d2921.jpg'),
(19, 'S40-2088', 'S40', 30, 0, 1, '674d8bb789516.jpg'),
(20, 'S41-1058', 'S41', 40, 0, 0, '674d8c1a7684e.jpg'),
(21, 'S41-1059', 'S41', 50, 0, 0, '674d8c6c30d47.jpg'),
(22, 'S50-06', 'S50', 120, 0, 1, '674d8ef272232.jpg'),
(23, 'S50-14', 'S50', 120, 0, 1, '674d8fed73aef.jpg'),
(24, 'S40-1002', 'S40', 150, 1, 1, '674d91e3571bc.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
