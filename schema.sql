
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
  `distributor` tinyint(3) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_hefter_utsalgssteder1_idx` (`distributor`),
  KEY `distributor` (`distributor`),
  KEY `status` (`status`),
  CONSTRAINT `booklets_ibfk_1` FOREIGN KEY (`distributor`) REFERENCES `distributors` (`id`),
  CONSTRAINT `booklets_ibfk_2` FOREIGN KEY (`status`) REFERENCES `statuses` (`id`)
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
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
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
  UNIQUE KEY `producer` (`producer`),
  KEY `presenter` (`presenter`,`producer`),
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
  `game` smallint(5) unsigned NOT NULL,
  `type` char(1) NOT NULL,
  `name` tinyint(4) NOT NULL,
  `numbers` text,
  `drawnNumbers` text,
  `rows` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `strips_booklets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `strips_booklets` (
  `strip` mediumint(8) unsigned NOT NULL,
  `booklet` smallint(5) unsigned NOT NULL,
  KEY `fk_blokker_hefter_blokker1_idx` (`strip`),
  KEY `booklet` (`booklet`),
  KEY `strip` (`strip`),
  KEY `booklet_2` (`booklet`),
  CONSTRAINT `strips_booklets_ibfk_1` FOREIGN KEY (`strip`) REFERENCES `strips` (`id`),
  CONSTRAINT `strips_booklets_ibfk_2` FOREIGN KEY (`booklet`) REFERENCES `booklets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `verification` int(11) NOT NULL,
  `ticket` varchar(60) NOT NULL,
  `strip` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`verification`),
  UNIQUE KEY `ticket` (`ticket`),
  KEY `strip` (`strip`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`strip`) REFERENCES `strips` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `winners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `winners` (
  `winnerId` int(11) NOT NULL AUTO_INCREMENT,
  `player` smallint(5) unsigned DEFAULT NULL,
  `verification` int(11) NOT NULL,
  `date` date NOT NULL,
  `round` smallint(5) unsigned NOT NULL,
  `leftToPay` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `rows` int(11) NOT NULL,
  PRIMARY KEY (`winnerId`),
  KEY `fk_vinnere_statuser1_idx` (`status`),
  KEY `fk_vinnere_kunder1_idx` (`player`),
  KEY `fk_vinnere_omgang1_idx` (`round`),
  KEY `player` (`player`),
  CONSTRAINT `winners_ibfk_3` FOREIGN KEY (`round`) REFERENCES `rounds` (`id`),
  CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`status`) REFERENCES `statuses` (`id`),
  CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`player`) REFERENCES `players` (`id`)
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

