-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 26, 2018 at 11:58 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp1web`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL,
  `adminName` varchar(50) NOT NULL,
  `adminEmail` varchar(50) NOT NULL,
  `adminPass` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminName`, `adminEmail`, `adminPass`) VALUES
(1, 'Sim', 'sim@gmail.com', '123456'),
(2, 'wong', 'wong@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `class_lesson`
--

CREATE TABLE `class_lesson` (
  `classID` int(11) NOT NULL,
  `type` varchar(12) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `duration` varchar(12) NOT NULL,
  `subjectID` varchar(12) NOT NULL,
  `roomID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_lesson`
--

INSERT INTO `class_lesson` (`classID`, `type`, `dateTime`, `duration`, `subjectID`, `roomID`) VALUES
(7, 'lecture', '2018-04-09 07:00:00', '10', 'bit200', 1),
(8, 'tutorial', '2018-04-10 07:00:00', '10', 'bit200', 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_rescheduling`
--

CREATE TABLE `class_rescheduling` (
  `id` int(11) NOT NULL,
  `newDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(30) NOT NULL,
  `subjectID` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_rescheduling`
--

INSERT INTO `class_rescheduling` (`id`, `newDateTime`, `status`, `subjectID`) VALUES
(1, '2018-04-10 04:12:00', 'pending', 'bit200'),
(2, '2001-10-05 16:30:00', 'pending', 'bit306'),
(3, '2018-03-20 11:00:48', 'pending', 'bit208'),
(4, '2018-03-16 11:00:48', 'pending', 'bit301'),
(5, '2018-03-16 11:00:48', 'pending', 'bit103'),
(6, '2018-03-16 11:00:48', 'pending', 'bit216'),
(7, '2018-03-21 11:00:48', 'pending', 'bit301'),
(8, '2018-03-17 11:00:48', 'pending', 'bit203'),
(9, '2018-03-18 11:00:48', 'pending', 'bit208');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturerID` varchar(30) NOT NULL,
  `lecturerName` varchar(50) NOT NULL,
  `lecturerEmail` varchar(60) NOT NULL,
  `lecturerPassword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturerID`, `lecturerName`, `lecturerEmail`, `lecturerPassword`) VALUES
('s0000001', 'Anita', '', ''),
('s0000002', 'Fong', '', ''),
('s0000003', 'Kok', '', ''),
('s0000004', 'Koon', '', ''),
('s0000005', 'Seetha', '', ''),
('s0000006', 'Ng SM', '', ''),
('s0000007', 'Sien', '', ''),
('s0000008', 'Dewi', '', ''),
('s0000009', 'Steven', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `reportID` int(11) NOT NULL,
  `lecturerID` varchar(30) NOT NULL,
  `subjectID` varchar(30) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `roomID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`roomID`, `name`, `capacity`) VALUES
(1, 'sr2.1', 30),
(2, 'sr2.2', 30),
(3, 'sr2.3', 20),
(4, 'lh2.1', 40);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectID` varchar(30) NOT NULL,
  `subjectName` varchar(60) NOT NULL,
  `lecturerID` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectID`, `subjectName`, `lecturerID`) VALUES
('bit103', 'Intro DB', 's0000006'),
('bit200', 'IT & Entrepre', 's0000001'),
('bit203', 'Java 2', 's0000003'),
('bit208', 'Data Struct', 's0000002'),
('bit216', 'Software Engrg', 's0000005'),
('bit301', 'IT Proj Mgmt', 's0000006'),
('bit306', 'Web Tech', 's0000002');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `class_lesson`
--
ALTER TABLE `class_lesson`
  ADD PRIMARY KEY (`classID`);

--
-- Indexes for table `class_rescheduling`
--
ALTER TABLE `class_rescheduling`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_rescheduling_ibfk_1` (`subjectID`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturerID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `report_ibfk_1` (`lecturerID`),
  ADD KEY `report_ibfk_2` (`subjectID`),
  ADD KEY `report_ibfk_3` (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`roomID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectID`),
  ADD KEY `subject_ibfk_1` (`lecturerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `class_lesson`
--
ALTER TABLE `class_lesson`
  MODIFY `classID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `class_rescheduling`
--
ALTER TABLE `class_rescheduling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `reportID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `roomID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_rescheduling`
--
ALTER TABLE `class_rescheduling`
  ADD CONSTRAINT `class_rescheduling_ibfk_1` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`),
  ADD CONSTRAINT `report_ibfk_3` FOREIGN KEY (`id`) REFERENCES `class_rescheduling` (`id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
