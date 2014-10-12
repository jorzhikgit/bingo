-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bingo
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Dumping data for table `booklets`
--

LOCK TABLES `booklets` WRITE;
/*!40000 ALTER TABLE `booklets` DISABLE KEYS */;
INSERT INTO `booklets` VALUES (1),(2);
/*!40000 ALTER TABLE `booklets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `distributors`
--

LOCK TABLES `distributors` WRITE;
/*!40000 ALTER TABLE `distributors` DISABLE KEYS */;
INSERT INTO `distributors` VALUES (1,'Joker\'n Reipå','','',NULL,''),(3,'Nærkanalens lokaler','','75720550','bingo@narkanalen.no','');
/*!40000 ALTER TABLE `distributors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `drawing`
--

LOCK TABLES `drawing` WRITE;
/*!40000 ALTER TABLE `drawing` DISABLE KEYS */;
INSERT INTO `drawing` VALUES (1,0,NULL),(2,0,NULL),(3,0,NULL),(4,0,NULL),(5,0,NULL),(6,0,NULL),(7,1,'2014-10-12 21:30:04'),(8,0,NULL),(9,0,NULL),(10,0,NULL),(11,0,NULL),(12,0,NULL),(13,0,NULL),(14,0,NULL),(15,0,NULL),(16,0,NULL),(17,0,NULL),(18,0,NULL),(19,0,NULL),(20,0,NULL),(21,0,NULL),(22,0,NULL),(23,1,'2014-10-12 21:30:02'),(24,0,NULL),(25,0,NULL),(26,0,NULL),(27,1,'2014-10-12 21:28:18'),(28,0,NULL),(29,0,NULL),(30,0,NULL),(31,0,NULL),(32,1,'2014-10-12 21:30:06'),(33,0,NULL),(34,0,NULL),(35,0,NULL),(36,0,NULL),(37,0,NULL),(38,0,NULL),(39,0,NULL),(40,0,NULL),(41,0,NULL),(42,0,NULL),(43,0,NULL),(44,0,NULL),(45,0,NULL),(46,0,NULL),(47,0,NULL),(48,0,NULL),(49,0,NULL),(50,0,NULL),(51,0,NULL),(52,0,NULL),(53,0,NULL),(54,0,NULL),(55,0,NULL),(56,0,NULL),(57,0,NULL),(58,0,NULL),(59,0,NULL),(60,0,NULL),(61,0,NULL),(62,0,NULL),(63,0,NULL),(64,0,NULL),(65,1,'2014-10-12 21:30:08'),(66,0,NULL),(67,0,NULL),(68,0,NULL),(69,1,'2014-10-12 21:30:00'),(70,0,NULL),(71,0,NULL),(72,0,NULL),(73,0,NULL),(74,0,NULL),(75,0,NULL),(76,0,NULL),(77,0,NULL),(78,0,NULL),(79,0,NULL),(80,0,NULL),(81,0,NULL),(82,0,NULL),(83,0,NULL),(84,0,NULL),(85,0,NULL),(86,0,NULL),(87,0,NULL),(88,0,NULL),(89,0,NULL),(90,0,NULL);
/*!40000 ALTER TABLE `drawing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,'Marius Flage'),(2,'Adrian K. Eriksen'),(3,'Petra Brattøy');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,'2014-10-11',3,1,9,20000,0);
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `places`
--

LOCK TABLES `places` WRITE;
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
INSERT INTO `places` VALUES (5,'Reipå'),(2,'Tromsø'),(1,'Ørnes');
/*!40000 ALTER TABLE `places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `players`
--

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;
INSERT INTO `players` VALUES (1,'Adrian K. Eriksen',1),(2,'Marius Flage',2),(4,'Kari Nordmann',1),(5,'Navn Navnesen',5);
/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `rounds`
--

LOCK TABLES `rounds` WRITE;
/*!40000 ALTER TABLE `rounds` DISABLE KEYS */;
INSERT INTO `rounds` VALUES (1,NULL,1,'R',1,'76;79;56;75;20;71;10;13;64;89;19;83;58;40;69;41;5;59;48;90;7;4;80;37;67;14;86;27;52;57;35;77;47;81;30;3;55;34;84;42;88;21;78;16;43;73;39;46;29;23;44;50;87;9;12;6;15;49;18;66;17;74;63;62;28;36;26;24;54;25',2);
/*!40000 ALTER TABLE `rounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,1,1,1,1);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `statuses`
--

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;
INSERT INTO `statuses` VALUES (1,'Til trykking'),(2,'Mottatt av NK'),(3,'Motatt av kommisjonær'),(4,'Solgt til kunde'),(5,'Premie ikke avhentet'),(6,'Premie delvis avhentet'),(7,'Premie utbetalt');
/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `strips`
--

LOCK TABLES `strips` WRITE;
/*!40000 ALTER TABLE `strips` DISABLE KEYS */;
INSERT INTO `strips` VALUES (1,1),(2,1),(3,1),(4,1),(5,1);
/*!40000 ALTER TABLE `strips` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,';11;;33;45;;;78;83-;18;27;;;51;;73;89-6;;29;;49;;69;;90',1),(2,'7;12;;30;;;62;;86-;19;24;;41;;60;72;-;;28;38;;56;;74;84',1),(3,'1;10;;31;44;;;70;-;14;23;;48;59;68;;-;;26;37;;;64;77;81',1),(4,'2;;21;32;;54;63;;-8;15;;34;43;;;71;-;17;22;;47;55;;;88',1),(5,'4;;20;;;;61;79;85-5;;;39;;52;67;;87-;16;;;40;58;66;76;',1),(6,'3;;25;;42;50;;;82-9;;;35;;53;65;;80-;13;;36;46;57;;75;',1);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `winners`
--

LOCK TABLES `winners` WRITE;
/*!40000 ALTER TABLE `winners` DISABLE KEYS */;
/*!40000 ALTER TABLE `winners` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-12 23:37:20
