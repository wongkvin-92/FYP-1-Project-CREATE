-- MySQL dump 10.15  Distrib 10.0.37-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: fyp1web
-- ------------------------------------------------------
-- Server version	10.1.25-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `adminID` int(11) NOT NULL AUTO_INCREMENT,
  `adminName` varchar(50) NOT NULL,
  `adminEmail` varchar(50) NOT NULL,
  `adminPass` varchar(60) NOT NULL,
  PRIMARY KEY (`adminID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Sim','yeohhs@help.edu.my','123456'),(2,'wong','wong@gmail.com','123456'),(3,'shath','shath@gmail.com','123456'),(4,'Dr.Sien','sienvy@gmail.com','123456');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_lesson`
--

DROP TABLE IF EXISTS `class_lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_lesson` (
  `classID` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(12) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `duration` varchar(12) NOT NULL,
  `subjectID` varchar(40) NOT NULL,
  `venue` varchar(14) NOT NULL,
  PRIMARY KEY (`classID`),
  KEY `class_lesson_ibfk_1` (`subjectID`),
  CONSTRAINT `class_lesson_ibfk_1` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_lesson`
--

LOCK TABLES `class_lesson` WRITE;
/*!40000 ALTER TABLE `class_lesson` DISABLE KEYS */;
INSERT INTO `class_lesson` VALUES (1,'lecture1','2019-01-02 01:00:00','3','bit205','tis'),(2,'tutorial1','2019-01-08 01:00:00','2','bit205','sr2.1'),(3,'lecture1','2019-02-06 01:00:00','3','bit216/bit302','sr2.1'),(4,'tutorial1','2018-10-11 01:00:00','2','bit216/bit302','sr2.2'),(5,'lecture1','2018-10-11 01:00:00','3','bit100','lh2.1'),(6,'tutorial1','2018-10-08 01:00:00','2','bit108/dip201','lh2.1'),(7,'tutorial2','2018-10-08 03:00:00','2','bit108/dip201','lh2.1'),(9,'tutorial1','2018-10-09 03:00:00','2','bit203','sr2.1'),(10,'lecture1','2018-10-11 06:30:00','3','bit203','sr2.2'),(11,'tutorial1','2018-10-12 06:00:00','4','bit104','sr2.1'),(12,'lecture1','2018-10-12 06:30:00','3','dip1prg11','ls2'),(13,'tutorial1','2018-10-10 16:00:00','2','dip1prg11','ls2'),(14,'tutorial2','2018-10-11 03:00:00','2','dip1prg11','ls2'),(15,'lecture1','2018-10-10 01:00:00','3','bit108/dip201','lh2.1'),(16,'tutorial1','2018-10-08 06:00:00','2','bit303/bmc306','tis'),(17,'tutorial1','2018-10-08 07:30:00','2','bit310/bmc307','tis'),(18,'lecture1','2018-10-09 02:30:00','2','bit304','tis'),(20,'lecture1','2018-10-10 06:00:00','3','bit310/bmc307','tis'),(21,'lecture1','2018-10-08 05:00:00','3','bit201','sr2.2'),(22,'tutorial1','2018-10-10 06:00:00','2','bit201','sr2.1'),(23,'lecture1','2018-10-13 01:00:00','3','dip1prg11','tis');
/*!40000 ALTER TABLE `class_lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_rescheduling`
--

DROP TABLE IF EXISTS `class_rescheduling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `class_rescheduling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(30) NOT NULL,
  `classID` int(11) NOT NULL,
  `newVenue` varchar(14) NOT NULL,
  `oldDateTime` datetime NOT NULL,
  `createCancellationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `newDateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_rescheduling_ibfk_1` (`classID`),
  CONSTRAINT `class_rescheduling_ibfk_1` FOREIGN KEY (`classID`) REFERENCES `class_lesson` (`classID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_rescheduling`
--

LOCK TABLES `class_rescheduling` WRITE;
/*!40000 ALTER TABLE `class_rescheduling` DISABLE KEYS */;
INSERT INTO `class_rescheduling` VALUES (14,'pending',1,'','2019-01-02 00:00:00','2019-01-02 10:04:26',NULL);
/*!40000 ALTER TABLE `class_rescheduling` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_list`
--

DROP TABLE IF EXISTS `device_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(256) NOT NULL,
  `uuid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_list`
--

LOCK TABLES `device_list` WRITE;
/*!40000 ALTER TABLE `device_list` DISABLE KEYS */;
INSERT INTO `device_list` VALUES (4,'student','b1301746','2019-01-02 00:44:00','ctehQiVI9so:APA91bHVCajLFWRxbX6D0z4MxGPBurULo8kep6vKljCOOducGTzDOmhiRlp3gBiJU1sgzg00fDpV_ll8Kl-xk-JYaS4RDT0dhavpEMqHrhZXKf3wdjb37SPW3QLf9rdVEDhVLfADYD5b','716a525aeb8ea8d5');
/*!40000 ALTER TABLE `device_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecturer`
--

DROP TABLE IF EXISTS `lecturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lecturer` (
  `lecturerID` int(11) NOT NULL AUTO_INCREMENT,
  `lecturerName` varchar(50) NOT NULL,
  `lecturerEmail` varchar(60) NOT NULL,
  `lecturerPassword` varchar(50) NOT NULL,
  PRIMARY KEY (`lecturerID`),
  UNIQUE KEY `lecturerEmail` (`lecturerEmail`),
  UNIQUE KEY `lecturerName` (`lecturerName`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecturer`
--

LOCK TABLES `lecturer` WRITE;
/*!40000 ALTER TABLE `lecturer` DISABLE KEYS */;
INSERT INTO `lecturer` VALUES (1,'Seetha','seethal@help.edu.my','123456'),(2,'Kok','kokch@help.edu.my','123456'),(3,'Koon','koon@help.edu.my','123456'),(4,'Shu Min','ngsm@help.edu.my','123456'),(5,'Anita','anitav@help.edu.my','123456'),(6,'Naline','nalines@help.edu.my','123456');
/*!40000 ALTER TABLE `lecturer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `semester`
--

DROP TABLE IF EXISTS `semester`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `semester` (
  `id` int(11) NOT NULL,
  `start_date` varchar(12) NOT NULL,
  `end_date` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semester`
--

LOCK TABLES `semester` WRITE;
/*!40000 ALTER TABLE `semester` DISABLE KEYS */;
INSERT INTO `semester` VALUES (0,'2019-01-01','2019-04-30');
/*!40000 ALTER TABLE `semester` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `studentID` varchar(40) NOT NULL,
  `studentName` varchar(50) NOT NULL,
  `studentPassword` varchar(50) NOT NULL,
  `studentEmail` varchar(50) NOT NULL,
  PRIMARY KEY (`studentID`),
  UNIQUE KEY `studentEmail` (`studentEmail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
INSERT INTO `student` VALUES ('b1301700','Yong','yong1744','b1301700@helplive.edu.my'),('b1301744','Wong Wai Kiat','waikiat1744','b1301744@helplive.edu.my'),('b1301746','Ibrahim Mohamed Shaatha','ibrahim1746','b1301746@helplive.edu.my');
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject`
--

DROP TABLE IF EXISTS `subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject` (
  `subjectID` varchar(40) NOT NULL,
  `subjectName` varchar(60) NOT NULL,
  `lecturerID` int(11) NOT NULL,
  PRIMARY KEY (`subjectID`),
  KEY `subject_ibfk_1` (`lecturerID`),
  CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`lecturerID`) REFERENCES `lecturer` (`lecturerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject`
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` VALUES ('bit100','Intro Programming',2),('bit1011','Testing123',2),('bit104','Application Maths',2),('bit108/dip201','Discrete Maths',2),('bit201','OOAD',4),('bit203','Java II',2),('bit205','OOP in C++',1),('bit216/bit302','Software Engr',1),('bit303/bmc306','IT Ethics & Sec',5),('bit304','Final Year Project I',5),('bit310/bmc307','Biz Dev',5),('dip1prg11','Intro Visual Prog',6);
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_student_enrolled`
--

DROP TABLE IF EXISTS `subject_student_enrolled`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subject_student_enrolled` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subjectID` varchar(40) NOT NULL,
  `studentID` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_student_enrolled_ibfk_1` (`subjectID`),
  KEY `subject_student_enrolled_ibfk_2` (`studentID`),
  CONSTRAINT `subject_student_enrolled_ibfk_1` FOREIGN KEY (`subjectID`) REFERENCES `subject` (`subjectID`),
  CONSTRAINT `subject_student_enrolled_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `student` (`studentID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_student_enrolled`
--

LOCK TABLES `subject_student_enrolled` WRITE;
/*!40000 ALTER TABLE `subject_student_enrolled` DISABLE KEYS */;
INSERT INTO `subject_student_enrolled` VALUES (6,'bit205','b1301744'),(12,'bit205','b1301746');
/*!40000 ALTER TABLE `subject_student_enrolled` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-02 10:41:27
