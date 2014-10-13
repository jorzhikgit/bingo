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
INSERT INTO `drawing` VALUES (1,NULL),(2,NULL),(3,NULL),(4,NULL),(5,NULL),(6,NULL),(7,'2014-10-12 21:30:04'),(8,NULL),(9,NULL),(10,NULL),(11,NULL),(12,NULL),(13,NULL),(14,NULL),(15,NULL),(16,NULL),(17,NULL),(18,NULL),(19,NULL),(20,NULL),(21,NULL),(22,NULL),(23,'2014-10-12 21:30:02'),(24,NULL),(25,NULL),(26,NULL),(27,'2014-10-12 21:28:18'),(28,NULL),(29,NULL),(30,NULL),(31,NULL),(32,'2014-10-12 21:30:06'),(33,NULL),(34,NULL),(35,NULL),(36,NULL),(37,NULL),(38,NULL),(39,NULL),(40,NULL),(41,NULL),(42,NULL),(43,NULL),(44,NULL),(45,NULL),(46,NULL),(47,NULL),(48,NULL),(49,NULL),(50,NULL),(51,NULL),(52,NULL),(53,NULL),(54,NULL),(55,NULL),(56,NULL),(57,NULL),(58,NULL),(59,NULL),(60,NULL),(61,NULL),(62,NULL),(63,NULL),(64,NULL),(65,'2014-10-12 21:30:08'),(66,NULL),(67,NULL),(68,NULL),(69,'2014-10-12 21:30:00'),(70,NULL),(71,NULL),(72,NULL),(73,NULL),(74,NULL),(75,NULL),(76,NULL),(77,NULL),(78,NULL),(79,NULL),(80,NULL),(81,NULL),(82,NULL),(83,NULL),(84,NULL),(85,NULL),(86,NULL),(87,NULL),(88,NULL),(89,NULL),(90,NULL);
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
INSERT INTO `games` VALUES (NULL, '2014-10-11',3,1,9,20000,0);
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
