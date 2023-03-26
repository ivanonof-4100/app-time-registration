-- MySQL dump 10.13  Distrib 8.0.31, for Linux (x86_64)
--
-- Host: localhost    Database: np_timesheets
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `employee_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `person_first_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `person_middle_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `person_last_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `person_gender` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci NOT NULL,
  `person_birthday` date NOT NULL,
  `person_native_language` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'en',
  PRIMARY KEY (`employee_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES ('3131eb82-47c6-11ed-9727-1c1bb5a9bf9b','Ivan','Mark','Andersen','1973-10-15','da','M'),('597e8483-467d-11ed-b005-1c1bb5a9bf9b','Dr. John','','Doe','1973-10-15','en','M');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_employee_uuid` BEFORE INSERT ON `employee` FOR EACH ROW BEGIN 
	SET NEW.employee_uuid = UUID(); 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `timesheet`
--

DROP TABLE IF EXISTS `timesheet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheet` (
  `timesheet_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'UUID()',
  `employee_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contract_uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `timesheet_work_date` date NOT NULL,
  `timesheet_hours_regular` float NOT NULL,
  `timesheet_hours_overtime` float NOT NULL,
  `timesheet_hours_break` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`timesheet_uuid`),
  KEY `fk_timesheet_employee` (`employee_uuid`),
  CONSTRAINT `fk_timesheet_employee` FOREIGN KEY (`employee_uuid`) REFERENCES `employee` (`employee_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheet`
--

LOCK TABLES `timesheet` WRITE;
/*!40000 ALTER TABLE `timesheet` DISABLE KEYS */;
INSERT INTO `timesheet` VALUES ('08f80277-47c9-11ed-9727-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-06',3,1,0.5,'2022-10-09 13:53:48'),('3855b040-4aec-11ed-80db-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-13',5.5,0,0.5,'2022-10-13 13:43:13'),('3ec11f6e-47c9-11ed-9727-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-03',5.5,1.5,0.5,'2022-10-09 13:55:18'),('593f531f-49a5-11ed-9bbf-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-10',4.5,5,0.5,'2022-10-11 22:43:23'),('5b9b1884-49ad-11ed-9bbf-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-09-27',3.5,2,0.5,'2022-10-11 23:40:43'),('60805fb3-4ae5-11ed-80db-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-12',3.5,5.5,0.5,'2022-10-13 12:54:14'),('6dd2fcf3-4ae9-11ed-80db-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-14',2.5,1,0.5,'2022-10-13 13:23:14'),('703e2dea-4ae4-11ed-80db-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-11',3.5,1,0.5,'2022-10-13 12:47:31'),('7e334e99-4ae5-11ed-80db-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-15',2.5,0,0.5,'2022-10-13 12:55:04'),('99b68524-47c0-11ed-9727-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-07',6,2,0.5,'2022-10-09 12:53:25'),('a0d5950e-49ac-11ed-9bbf-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-16',4.5,1,0.5,'2022-10-11 23:35:29'),('a43cac29-47c0-11ed-9727-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-08',4,2,0.5,'2022-10-09 12:53:43'),('c6d4ebe1-47ca-11ed-9727-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-04',7,0,0.5,'2022-10-09 14:06:16'),('feb7d4bd-49ab-11ed-9bbf-1c1bb5a9bf9b','597e8483-467d-11ed-b005-1c1bb5a9bf9b',NULL,'2022-10-05',2.5,0,0.5,'2022-10-11 23:30:58');
/*!40000 ALTER TABLE `timesheet` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `trigger_timesheet_beforeinsert` BEFORE INSERT ON `timesheet` FOR EACH ROW BEGIN 
	SET NEW.timesheet_uuid = UUID(); 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping events for database 'np_timesheets'
--

--
-- Dumping routines for database 'np_timesheets'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-10-17  0:51:27
