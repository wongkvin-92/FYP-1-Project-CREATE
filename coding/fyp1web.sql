-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
<<<<<<< HEAD
-- Generation Time: Nov 28, 2018 at 03:04 PM
=======
-- Generation Time: Dec 13, 2018 at 07:39 PM
>>>>>>> 8a2f95249e8b15869850b158be0d08f79896b9b8
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
(1, 'Sim', 'yeohhs@help.edu.my', '123456'),
(2, 'wong', 'wong@gmail.com', '123456'),
(3, 'shath', 'shath@gmail.com', '123456'),
(4, 'Dr.Sien', 'sienvy@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `class_lesson`
--

CREATE TABLE `class_lesson` (
  `classID` int(11) NOT NULL,
  `type` varchar(12) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `duration` varchar(12) NOT NULL,
  `subjectID` varchar(40) NOT NULL,
  `venue` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_lesson`
--

INSERT INTO `class_lesson` (`classID`, `type`, `dateTime`, `duration`, `subjectID`, `venue`) VALUES
(1, 'lecture1', '2018-10-12 01:00:00', '3', 'bit205', 'tis'),
(2, 'tutorial1', '2018-10-09 01:00:00', '2', 'bit205', 'sr2.1'),
(3, 'lecture1', '2018-10-10 01:00:00', '3', 'bit216/bit302', 'sr2.1'),
(4, 'tutorial1', '2018-10-11 01:00:00', '2', 'bit216/bit302', 'sr2.2'),
(5, 'lecture1', '2018-10-11 01:00:00', '3', 'bit100', 'lh2.1'),
(6, 'tutorial1', '2018-10-08 01:00:00', '2', 'bit108/dip201', 'lh2.1'),
(7, 'tutorial2', '2018-10-08 03:00:00', '2', 'bit108/dip201', 'lh2.1'),
(9, 'tutorial1', '2018-10-09 03:00:00', '2', 'bit203', 'sr2.1'),
(10, 'lecture1', '2018-10-11 06:30:00', '3', 'bit203', 'sr2.2'),
(11, 'tutorial1', '2018-10-12 06:00:00', '4', 'bit104', 'sr2.1'),
(12, 'lecture1', '2018-10-12 06:30:00', '3', 'dip1prg11', 'ls2'),
(13, 'tutorial1', '2018-10-10 16:00:00', '2', 'dip1prg11', 'ls2'),
(14, 'tutorial2', '2018-10-11 03:00:00', '2', 'dip1prg11', 'ls2'),
(15, 'lecture1', '2018-10-10 01:00:00', '3', 'bit108/dip201', 'lh2.1'),
(16, 'tutorial1', '2018-10-08 06:00:00', '2', 'bit303/bmc306', 'tis'),
(17, 'tutorial1', '2018-10-08 07:30:00', '2', 'bit310/bmc307', 'tis'),
(18, 'lecture1', '2018-10-09 02:30:00', '2', 'bit304', 'tis'),
(20, 'lecture1', '2018-10-10 06:00:00', '3', 'bit310/bmc307', 'tis'),
(21, 'lecture1', '2018-10-08 05:00:00', '3', 'bit201', 'sr2.2'),
(22, 'tutorial1', '2018-10-10 06:00:00', '2', 'bit201', 'sr2.1');

-- --------------------------------------------------------

--
-- Table structure for table `class_rescheduling`
--

CREATE TABLE `class_rescheduling` (
  `id` int(11) NOT NULL,
  `status` varchar(30) NOT NULL,
  `classID` int(11) NOT NULL,
  `newVenue` varchar(14) NOT NULL,
  `oldDateTime` datetime NOT NULL,
  `createCancellationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `newDateTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class_rescheduling`
--

INSERT INTO `class_rescheduling` (`id`, `status`, `classID`, `newVenue`, `oldDateTime`, `createCancellationDate`, `newDateTime`) VALUES
<<<<<<< HEAD
(1, 'pending', 2, 'sr2.1', '2018-11-27 00:00:00', '2018-11-22 08:52:10', '2018-11-30 14:30:00'),
(2, 'pending', 1, '', '2018-11-30 00:00:00', '2018-11-22 09:06:54', NULL),
(3, 'pending', 4, '', '2018-11-29 00:00:00', '2018-11-22 09:06:55', '2018-11-07 00:00:00'),
=======
(1, 'approved', 2, 'sr2.1', '2018-11-27 00:00:00', '2018-11-22 08:52:10', '2018-11-30 14:30:00'),
(2, 'pending', 1, '', '2018-11-30 00:00:00', '2018-11-22 09:06:54', NULL),
(3, 'approved', 4, 'sr2.1', '2018-11-29 00:00:00', '2018-11-22 09:06:55', '2018-12-28 12:00:00'),
>>>>>>> 8a2f95249e8b15869850b158be0d08f79896b9b8
(4, 'pending', 3, 'sr2.1', '2018-11-28 00:00:00', '2018-11-22 09:06:55', '2018-12-14 10:00:00'),
(5, 'approved', 2, 'sr2.1', '2018-12-04 00:00:00', '2018-11-27 11:43:16', '2018-12-07 13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `device_list`
--

CREATE TABLE `device_list` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(256) NOT NULL,
  `uuid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device_list`
--

INSERT INTO `device_list` (`id`, `type`, `userID`, `createDate`, `token`, `uuid`) VALUES
(1, 'student', 'b1301744', '2018-11-22 00:18:17', 'dSEsp-2mavM:APA91bExTCvqng18HXyS2S-m0awAjr8_tdaZMrTWA9ooTCGgBB8aFAhkk5PJU7EbP_STWO1cPgswx9JSt2mlCQyP8JKu5AE0-OskbEq7iyIeJ-2fbKxPNlMgQCmvrL6haIz7tfUElD7d', '368eb3297e671dcb'),
(2, 'lecturer', '1', '2018-11-22 00:35:11', 'c62dVffq7jA:APA91bEUa9Mw4xqOLpHuVDsNPm1Zn8FR4KuWy5l5Tz1BmzcBn-dekkTmjgjmWCAKFw5mtvJ4TbyuHPG88iUi-dGBB5xoS2YlDXzvFaHq3pRXEAAIxkcUhFq7MbZ8IGCbUCC7TnU7Ku06', 'cf56d43dd2a4ae01');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturerID` int(11) NOT NULL,
  `lecturerName` varchar(50) NOT NULL,
  `lecturerEmail` varchar(60) NOT NULL,
  `lecturerPassword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturerID`, `lecturerName`, `lecturerEmail`, `lecturerPassword`) VALUES
(1, 'Seetha', 'seethal@help.edu.my', '123456'),
(2, 'Kok', 'kokch@help.edu.my', '123456'),
(3, 'Koon', 'koon@help.edu.my', '123456'),
(4, 'Shu Min', 'ngsm@help.edu.my', '123456'),
(5, 'Anita', 'anitav@help.edu.my', '123456'),
(6, 'Naline', 'nalines@help.edu.my', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `start_date` varchar(12) NOT NULL,
  `end_date` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id`, `start_date`, `end_date`) VALUES
<<<<<<< HEAD
(0, '2018-10-07', '2018-12-11');
=======
(0, '2019-01-14', '2019-04-30');
>>>>>>> 8a2f95249e8b15869850b158be0d08f79896b9b8

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `studentID` varchar(40) NOT NULL,
  `studentName` varchar(50) NOT NULL,
  `studentPassword` varchar(50) NOT NULL,
  `studentEmail` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentID`, `studentName`, `studentPassword`, `studentEmail`) VALUES
('b1301700', 'Yong', 'yong1744', 'b1301700@helplive.edu.my'),
('b1301744', 'Wong Wai Kiat', 'waikiat1744', 'b1301744@helplive.edu.my'),
('b1301746', 'Ibrahim Mohamed Shaatha', 'ibrahim1746', 'b1301746@helplive.edu.my');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subjectID` varchar(40) NOT NULL,
  `subjectName` varchar(60) NOT NULL,
  `lecturerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subjectID`, `subjectName`, `lecturerID`) VALUES
('bit100', 'Intro Programming', 2),
('bit104', 'Application Maths', 2),
('bit108/dip201', 'Discrete Maths', 2),
('bit201', 'OOAD', 4),
('bit203', 'Java II', 2),
('bit205', 'OOP in C++', 1),
('bit216/bit302', 'Software Engr', 1),
('bit303/bmc306', 'IT Ethics & Sec', 5),
('bit304', 'Final Year Project I', 5),
('bit310/bmc307', 'Biz Dev', 5),
('dip1prg11', 'Intro Visual Prog', 6);

-- --------------------------------------------------------

--
-- Table structure for table `subject_student_enrolled`
--

CREATE TABLE `subject_student_enrolled` (
  `id` int(11) NOT NULL,
  `subjectID` varchar(40) NOT NULL,
  `studentID` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject_student_enrolled`
--

INSERT INTO `subject_student_enrolled` (`id`, `subjectID`, `studentID`) VALUES
(6, 'bit205', 'b1301744');

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
  ADD PRIMARY KEY (`classID`),
  ADD KEY `class_lesson_ibfk_1` (`subjectID`);

--
-- Indexes for table `class_rescheduling`
--
ALTER TABLE `class_rescheduling`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_rescheduling_ibfk_1` (`classID`);

--
-- Indexes for table `device_list`
--
ALTER TABLE `device_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturerID`),
  ADD UNIQUE KEY `lecturerEmail` (`lecturerEmail`),
  ADD UNIQUE KEY `lecturerName` (`lecturerName`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentID`),
  ADD UNIQUE KEY `studentEmail` (`studentEmail`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectID`),
  ADD KEY `subject_ibfk_1` (`lecturerID`);

--
-- Indexes for table `subject_student_enrolled`
--
ALTER TABLE `subject_student_enrolled`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_student_enrolled_ibfk_1` (`subjectID`),
  ADD KEY `subject_student_enrolled_ibfk_2` (`studentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `class_lesson`
--
ALTER TABLE `class_lesson`
  MODIFY `classID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `class_rescheduling`
--
ALTER TABLE `class_rescheduling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `device_list`
--
ALTER TABLE `device_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lecturerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `subject_student_enrolled`
--
ALTER TABLE `subject_student_enrolled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_lesson`
--
ALTER TABLE `class_lesson`
  ADD CONSTRAINT `class_lesson_ibfk_1` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`);

--
-- Constraints for table `class_rescheduling`
--
ALTER TABLE `class_rescheduling`
  ADD CONSTRAINT `class_rescheduling_ibfk_1` FOREIGN KEY (`classID`) REFERENCES `class_lesson` (`classID`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`);

--
-- Constraints for table `subject_student_enrolled`
--
ALTER TABLE `subject_student_enrolled`
  ADD CONSTRAINT `subject_student_enrolled_ibfk_1` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`),
  ADD CONSTRAINT `subject_student_enrolled_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
