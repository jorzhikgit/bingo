
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
DROP TABLE IF EXISTS `booklets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booklets` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12346 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `distributors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distributors` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL,
  `phone` varchar(8) NOT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `drawing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drawing` (
  `number` tinyint(3) unsigned NOT NULL,
  `picked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `when` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `number` (`number`),
  UNIQUE KEY `when` (`when`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `presenter` tinyint(3) unsigned DEFAULT NULL,
  `producer` tinyint(3) unsigned DEFAULT NULL,
  `jackpot_number` tinyint(3) unsigned NOT NULL,
  `jackpot` int(11) unsigned NOT NULL,
  `got_jackpot` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dato` (`date`),
  KEY `presenter` (`presenter`),
  KEY `producer` (`producer`),
  CONSTRAINT `games_ibfk_2` FOREIGN KEY (`producer`) REFERENCES `employees` (`id`),
  CONSTRAINT `games_ibfk_1` FOREIGN KEY (`presenter`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `places` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `place` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_steder_kunder1_idx` (`place`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `place` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `place` (`place`),
  CONSTRAINT `players_ibfk_1` FOREIGN KEY (`place`) REFERENCES `places` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rounds` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `started` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `game` smallint(5) unsigned NOT NULL,
  `type` char(1) NOT NULL,
  `name` tinyint(4) unsigned NOT NULL,
  `numbers` text,
  `rows` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `started` (`started`),
  KEY `game` (`game`),
  CONSTRAINT `rounds_ibfk_1` FOREIGN KEY (`game`) REFERENCES `games` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `game` smallint(5) unsigned NOT NULL,
  `distributor` tinyint(3) unsigned NOT NULL,
  `booklet` smallint(5) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game` (`game`,`distributor`,`booklet`,`status`),
  KEY `distributor` (`distributor`),
  KEY `booklet` (`booklet`),
  KEY `status` (`status`),
  CONSTRAINT `sales_ibfk_4` FOREIGN KEY (`game`) REFERENCES `games` (`id`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`distributor`) REFERENCES `distributors` (`id`),
  CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`booklet`) REFERENCES `booklets` (`id`),
  CONSTRAINT `sales_ibfk_3` FOREIGN KEY (`status`) REFERENCES `statuses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statuses` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `strips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `strips` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `booklet` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `booklet` (`booklet`),
  CONSTRAINT `strips_ibfk_1` FOREIGN KEY (`booklet`) REFERENCES `booklets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ticket` varchar(60) NOT NULL,
  `strip` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket` (`ticket`),
  KEY `strip` (`strip`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`strip`) REFERENCES `strips` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `winners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `winners` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `player` smallint(5) unsigned DEFAULT NULL,
  `ticket` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `round` smallint(5) unsigned NOT NULL,
  `leftToPay` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `row` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vinnere_statuser1_idx` (`status`),
  KEY `fk_vinnere_kunder1_idx` (`player`),
  KEY `fk_vinnere_omgang1_idx` (`round`),
  KEY `player` (`player`),
  KEY `ticket` (`ticket`),
  CONSTRAINT `winners_ibfk_4` FOREIGN KEY (`ticket`) REFERENCES `tickets` (`id`),
  CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`status`) REFERENCES `statuses` (`id`),
  CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`player`) REFERENCES `players` (`id`),
  CONSTRAINT `winners_ibfk_3` FOREIGN KEY (`round`) REFERENCES `rounds` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

