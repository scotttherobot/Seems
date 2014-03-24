-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: cards
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.12.04.1

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
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testid` int(11) NOT NULL,
  `studentid` int(11) NOT NULL,
  `attempts` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `test_student_unique` (`testid`,`studentid`),
  KEY `studentid` (`studentid`),
  CONSTRAINT `assignments_testid_fk` FOREIGN KEY (`testid`) REFERENCES `tests` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `assignments_studentid_fk` FOREIGN KEY (`studentid`) REFERENCES `students` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assignments`
--

LOCK TABLES `assignments` WRITE;
/*!40000 ALTER TABLE `assignments` DISABLE KEYS */;
INSERT INTO `assignments` VALUES (1,1,2,20),(2,1,4,4),(17,2,2,1),(18,3,2,3);
/*!40000 ALTER TABLE `assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attempts`
--

DROP TABLE IF EXISTS `attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentid` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) DEFAULT NULL,
  `assignmentid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `studentid` (`studentid`),
  KEY `assignmentid` (`assignmentid`),
  CONSTRAINT `attempts_assignmentid_fk` FOREIGN KEY (`assignmentid`) REFERENCES `assignments` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `attempts_studentid_fk` FOREIGN KEY (`studentid`) REFERENCES `students` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attempts`
--

LOCK TABLES `attempts` WRITE;
/*!40000 ALTER TABLE `attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `choices`
--

DROP TABLE IF EXISTS `choices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionid` int(11) NOT NULL,
  `prompt` text NOT NULL,
  `correct` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questionid` (`questionid`),
  CONSTRAINT `choices_questionid_fk` FOREIGN KEY (`questionid`) REFERENCES `questions` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choices`
--

LOCK TABLES `choices` WRITE;
/*!40000 ALTER TABLE `choices` DISABLE KEYS */;
INSERT INTO `choices` VALUES (1,1,'Potato',0),(2,1,'Fish',1),(3,2,'America',1),(4,3,'Reasons.',0),(5,3,'Pie.',1),(6,5,'Police police Police police police police Police police!',1),(7,6,'Mexican Style Fancy Cheese Blend',0),(8,6,'Triple creme brie',0),(9,6,'Gouda',0),(11,9,'The most dangerous chemical known to man.',1),(12,10,'No! I swear!',0),(13,10,'Probably.',1),(14,4,'Now.',0),(15,4,'Later.',0),(16,4,'Today.',0),(17,4,'Never.',0),(18,4,'Anytime.',1),(19,11,'Fancy',0),(20,11,'All',0),(21,11,'None',0),(22,11,'Cheese',1),(23,12,'Scott',1),(24,7,'God',1),(25,13,'Acetone',0),(26,14,'Temperature',0),(27,14,'Pressure',0),(28,14,'Neither',1),(29,15,'234',0),(30,8,'Pizza is everything.',1),(31,7,'One Million Moms',0),(32,3,'Alaska',0),(33,7,'Willam Belli',1);
/*!40000 ALTER TABLE `choices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `medid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `type` enum('IMAGE','VIDEO') NOT NULL,
  `fname` varchar(20) NOT NULL,
  `src` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`medid`),
  KEY `userid` (`userid`),
  CONSTRAINT `media_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `userid` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `avatar` int(11) NOT NULL,
  `about` text,
  PRIMARY KEY (`userid`),
  KEY `profiles_avatar_fk` (`avatar`),
  CONSTRAINT `profiles_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `profiles_avatar_fk` FOREIGN KEY (`avatar`) REFERENCES `media` (`medid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prompt` text NOT NULL,
  `testid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_testid_fk` (`testid`),
  CONSTRAINT `questions_testid_fk` FOREIGN KEY (`testid`) REFERENCES `tests` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'What is the air-speed velocity of an unladen swallow?',1),(2,'Who is Spain?',1),(3,'Why is Hitler?',1),(4,'When is right?',1),(5,'If Police police Police police, who police Police police?',1),(6,'What is the best cheese?',2),(7,'Why am I here?',1),(8,'Do you like pizza?',1),(9,'What is \'di-hydrogen monoxide\'?',1),(10,'Does your mother know?',1),(11,'What is the best kind of shred?',2),(12,'Who eats the cheese?',2),(13,'Which chemical elutes first in a gas chromatograph?',3),(14,'If a substance is held at its critical point, increasing which will produce a liquid?',3),(15,'The molecular weight for water is:',3);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `responses`
--

DROP TABLE IF EXISTS `responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionid` int(11) NOT NULL,
  `attemptid` int(11) NOT NULL,
  `choiceid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attemptid` (`attemptid`),
  KEY `responses_questionid_fk` (`questionid`),
  KEY `responses_choiceid_fk` (`choiceid`),
  CONSTRAINT `responses_questionid_fk` FOREIGN KEY (`questionid`) REFERENCES `questions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `responses_attemptid_fk` FOREIGN KEY (`attemptid`) REFERENCES `attempts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `responses_choiceid_fk` FOREIGN KEY (`choiceid`) REFERENCES `choices` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `responses`
--

LOCK TABLES `responses` WRITE;
/*!40000 ALTER TABLE `responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `userid` int(11) NOT NULL,
  `api_key` varchar(32) NOT NULL,
  `expire` int(11) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `api_key` (`api_key`),
  CONSTRAINT `keys_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (2,'dd6cc19a77dcdf7c1729f5426af9f819',1392099880),(3,'d70504b1a3844d4216d19dd2da1eaefe',1391922505);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `userid` int(11) NOT NULL,
  `teacherid` int(11) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `students_teacherid_fk` (`teacherid`),
  CONSTRAINT `students_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `students_teacherid_fk` FOREIGN KEY (`teacherid`) REFERENCES `teachers` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (2,1),(4,1);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teachers` (
  `userid` int(11) NOT NULL,
  `school` varchar(50) NOT NULL,
  `regcode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  CONSTRAINT `teachers_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,'Oompow!','SATAN-ASSHOLE'),(3,'Ann Coulter\'s Academy','81-clever-feline-76');
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacherid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `attempts` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teacherid` (`teacherid`),
  CONSTRAINT `tests_teacherid_fk` FOREIGN KEY (`teacherid`) REFERENCES `teachers` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` VALUES (1,1,'AP English',10),(2,1,'Queso',5),(3,1,'Chemistry',1),(4,3,'Republicanism',1),(5,3,'Assholism',4),(6,3,'Bitchiness',2);
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_settings` (
  `userid` int(11) NOT NULL,
  `key` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  CONSTRAINT `settings_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `sign_up_date` int(11) NOT NULL,
  `pw_hash` char(128) DEFAULT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `sign_up_date` (`sign_up_date`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,1391745310,'$2a$10$190509864852f4591ee15ONvGRM3pirgaiYvGlqJkYYYkpk28rr0m','Scott','Vanderlind','scott.vanderlind2@gmail.com','scotttherobot'),(2,1391748637,'$2a$10$137912244852f4661d386OSjEei.OWJ/omtfHiKDcrWFWZp847SUK','Donald','Percivalle','dpercivalle@gmail.com','architrex'),(3,1391820567,'$2a$10$134634322752f57f17310uqg25E3icghZlvVT/JfNM/o2sLwZgaxW','Ann','Coulter','ann@coulter.com','anncoulter'),(4,1391821848,'$2a$10$200667619152f58418b58uLBozSSt6thxz/ORl383IKDDdZyydw1i','Brenda','Crawford','brenda@lol.com','brendacrawford');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-10 11:58:44
