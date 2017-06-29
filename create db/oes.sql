-- MySQL dump 10.13  Distrib 5.1.40, for Win32 (ia32)
--
-- Host: localhost    Database: oes
-- ------------------------------------------------------
-- Server version	5.1.40-community

--
-- 当前数据库：`oes`
--

CREATE DATABASE `oes` CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

USE `oes`;

--
-- 表“adminlogin”的表结构
--

DROP TABLE IF EXISTS `adminlogin`;
CREATE TABLE `adminlogin` (
  `admname` varchar(32) NOT NULL,
  `admpassword` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`admname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- 转储表“adminlogin”的数据
--

LOCK TABLES `adminlogin` WRITE;
/*!40000 ALTER TABLE `adminlogin` DISABLE KEYS */;
INSERT INTO `adminlogin` VALUES ('root','63a9f0ea7bb98050796b649e85481845');
/*!40000 ALTER TABLE `adminlogin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- 表“question”的表结构
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `qnid` int(11) NOT NULL DEFAULT '0',
  `question` varchar(500) DEFAULT NULL,
  `optiona` varchar(100) DEFAULT NULL,
  `optionb` varchar(100) DEFAULT NULL,
  `optionc` varchar(100) DEFAULT NULL,
  `optiond` varchar(100) DEFAULT NULL,
  `correctanswer` enum('optiona','optionb','optionc','optiond') DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  PRIMARY KEY (`testid`,`qnid`),
  CONSTRAINT `question_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 转储表“question”的数据
--

LOCK TABLES `question` WRITE;
/*!40000 ALTER TABLE `question` DISABLE KEYS */;
/*!40000 ALTER TABLE `question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- 表“student”的表结构
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `stdid` bigint(20) NOT NULL,
  `stdname` varchar(40) DEFAULT NULL,
  `stdpassword` varchar(40) DEFAULT NULL,
  `emailid` varchar(40) DEFAULT NULL,
  `contactno` varchar(20) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`stdid`),
  UNIQUE KEY `stdname` (`stdname`),
  UNIQUE KEY `emailid` (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 转储表“student”的数据
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- 表“studentquestion”的表结构
--

DROP TABLE IF EXISTS `studentquestion`;
CREATE TABLE `studentquestion` (
  `stdid` bigint(20) NOT NULL DEFAULT '0',
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `qnid` int(11) NOT NULL DEFAULT '0',
  `answered` enum('answered','unanswered','review') DEFAULT NULL,
  `stdanswer` enum('optiona','optionb','optionc','optiond') DEFAULT NULL,
  PRIMARY KEY (`stdid`,`testid`,`qnid`),
  KEY `testid` (`testid`,`qnid`),
  CONSTRAINT `studentquestion_ibfk_1` FOREIGN KEY (`stdid`) REFERENCES `student` (`stdid`),
  CONSTRAINT `studentquestion_ibfk_2` FOREIGN KEY (`testid`, `qnid`) REFERENCES `question` (`testid`, `qnid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 转储表“studentquestion”的数据
--

LOCK TABLES `studentquestion` WRITE;
/*!40000 ALTER TABLE `studentquestion` DISABLE KEYS */;
/*!40000 ALTER TABLE `studentquestion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- 表“studenttest”的表结构
--

DROP TABLE IF EXISTS `studenttest`;
CREATE TABLE `studenttest` (
  `stdid` bigint(20) NOT NULL DEFAULT '0',
  `testid` bigint(20) NOT NULL DEFAULT '0',
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `correctlyanswered` int(11) DEFAULT NULL,
  `status` enum('over','inprogress') DEFAULT NULL,
  PRIMARY KEY (`stdid`,`testid`),
  KEY `testid` (`testid`),
  CONSTRAINT `studenttest_ibfk_1` FOREIGN KEY (`stdid`) REFERENCES `student` (`stdid`),
  CONSTRAINT `studenttest_ibfk_2` FOREIGN KEY (`testid`) REFERENCES `test` (`testid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 转储表“studenttest”的数据
--

LOCK TABLES `studenttest` WRITE;
/*!40000 ALTER TABLE `studenttest` DISABLE KEYS */;
/*!40000 ALTER TABLE `studenttest` ENABLE KEYS */;
UNLOCK TABLES;


--
-- 表“testconductor”的表结构
--

DROP TABLE IF EXISTS `testconductor`;
CREATE TABLE `testconductor` (
  `tcid` bigint(20) NOT NULL,
  `tcname` varchar(40) DEFAULT NULL,
  `tcpassword` varchar(40) DEFAULT NULL,
  `emailid` varchar(40) DEFAULT NULL,
  `contactno` varchar(20) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`tcid`),
  UNIQUE KEY `stdname` (`tcname`),
  UNIQUE KEY `emailid` (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 转储表“testconductor”的数据
--

LOCK TABLES `testconductor` WRITE;
/*!40000 ALTER TABLE `testconductor` DISABLE KEYS */;
/*!40000 ALTER TABLE `testconductor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- 表“subject”的表结构
--

DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `subid` int(11) NOT NULL,
  `subname` varchar(40) DEFAULT NULL,
  `subdesc` varchar(100) DEFAULT NULL,
  `tcid` bigint(20),
  PRIMARY KEY (`subid`),
  UNIQUE KEY `subname` (`subname`),
  CONSTRAINT `subject_fk1` FOREIGN KEY (`tcid`) REFERENCES `testconductor` (`tcid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- 转储表“subject”的数据
--

LOCK TABLES `subject` WRITE;
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- 表“test”的表结构
--

DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `testid` bigint(20) NOT NULL,
  `testname` varchar(30) NOT NULL,
  `testdesc` varchar(100) DEFAULT NULL,
  `testdate` date DEFAULT NULL,
  `testtime` time DEFAULT NULL,
  `subid` int(11) DEFAULT NULL,
  `testfrom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `testto` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) DEFAULT NULL,
  `totalquestions` int(11) DEFAULT NULL,
  `attemptedstudents` bigint(20) DEFAULT NULL,
  `testcode` varchar(40) NOT NULL,
  `tcid` bigint(20),
  PRIMARY KEY (`testid`),
  UNIQUE KEY `testname` (`testname`),
  CONSTRAINT `test_fk1` FOREIGN KEY (`subid`) REFERENCES `subject` (`subid`),
  CONSTRAINT `test_fk2` FOREIGN KEY (`tcid`) REFERENCES `testconductor` (`tcid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转储表“test”的数据
--

LOCK TABLES `test` WRITE;
/*!40000 ALTER TABLE `test` DISABLE KEYS */;
/*!40000 ALTER TABLE `test` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
