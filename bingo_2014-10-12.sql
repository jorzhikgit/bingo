# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.39-1)
# Database: bingo
# Generation Time: 2014-10-12 12:03:56 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tickets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tickets`;

CREATE TABLE `tickets` (
  `verification` int(11) NOT NULL,
  `ticket` text,
  `fingerprint` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`verification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;

INSERT INTO `tickets` (`verification`, `ticket`, `fingerprint`)
VALUES
	(10000,'1;11;;;46;50;;;81-9;;20;35;;52;;70;-;;21;;48;;63;76;87',NULL),
	(10001,';17;;31;40;;60;75;-;;25;38;43;56;;77;-5;;;39;;58;66;;86',NULL),
	(10002,'2;12;;33;42;;;74;-;18;22;;45;54;67;;-;;28;34;;;68;79;83',NULL),
	(10003,'4;;27;30;;55;;;80-7;10;;37;47;;64;;-;16;29;;49;;;71;88',NULL),
	(10004,'3;;23;32;;51;;;85-8;13;;;41;53;62;;-;19;26;;;;69;78;89',NULL),
	(10005,';14;24;;;;61;72;82-;15;;36;;57;;73;84-6;;;;44;59;65;;90',NULL);

/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tickets_booklets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tickets_booklets`;

CREATE TABLE `tickets_booklets` (
  `saleId` int(11) NOT NULL,
  `verification` int(11) NOT NULL,
  KEY `fk_blokker_hefter_blokker1_idx` (`verification`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `tickets_booklets` WRITE;
/*!40000 ALTER TABLE `tickets_booklets` DISABLE KEYS */;

INSERT INTO `tickets_booklets` (`saleId`, `verification`)
VALUES
	(12345,10000),
	(12345,10001),
	(12345,10002),
	(12345,10003),
	(12345,10004),
	(12345,10005);

/*!40000 ALTER TABLE `tickets_booklets` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table booklets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `booklets`;

CREATE TABLE `booklets` (
  `saleId` int(11) NOT NULL AUTO_INCREMENT,
  `distributorId` int(11) DEFAULT NULL,
  `statusId` int(11) NOT NULL,
  PRIMARY KEY (`saleId`),
  KEY `fk_hefter_utsalgssteder1_idx` (`distributorId`)
) ENGINE=InnoDB AUTO_INCREMENT=12346 DEFAULT CHARSET=utf8;

LOCK TABLES `booklets` WRITE;
/*!40000 ALTER TABLE `booklets` DISABLE KEYS */;

INSERT INTO `booklets` (`saleId`, `distributorId`, `statusId`)
VALUES
	(12345,1,4);

/*!40000 ALTER TABLE `booklets` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `customerId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `placeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`customerId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;

INSERT INTO `customers` (`customerId`, `name`, `placeId`)
VALUES
	(1,'Adrian K. Eriksen',1),
	(2,'Marius Flage',2),
	(4,'Kari Nordmann',1),
	(5,'Navn Navnesen',5);

/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nights
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nights`;

CREATE TABLE `nights` (
  `nightId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `jackpotNumber` tinyint(3) unsigned NOT NULL,
  `jackpot` int(11) unsigned NOT NULL,
  `gotJackpot` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`nightId`),
  UNIQUE KEY `dato` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

LOCK TABLES `nights` WRITE;
/*!40000 ALTER TABLE `nights` DISABLE KEYS */;

INSERT INTO `nights` (`nightId`, `date`, `jackpotNumber`, `jackpot`, `gotJackpot`)
VALUES
	(1,'2014-10-11',88,7000,'N'),
	(12,'2014-10-12',64,7500,'N');

/*!40000 ALTER TABLE `nights` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rounds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rounds`;

CREATE TABLE `rounds` (
  `roundId` int(11) NOT NULL AUTO_INCREMENT,
  `nightId` int(11) NOT NULL,
  `type` char(1) NOT NULL,
  `name` tinyint(4) NOT NULL,
  `numbers` text,
  `drawnNumbers` text,
  `rows` tinyint(4) NOT NULL,
  PRIMARY KEY (`roundId`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

LOCK TABLES `rounds` WRITE;
/*!40000 ALTER TABLE `rounds` DISABLE KEYS */;

INSERT INTO `rounds` (`roundId`, `nightId`, `type`, `name`, `numbers`, `drawnNumbers`, `rows`)
VALUES
	(1,1,'R',1,'22;38;2;60;61;70;53;45;8;82;31;11;33;72;68;1;85;32;65;51','76;79;56;75;20;71;10;13;64;89;19;83;58;40;69;41;5;59;48;90;7;4;80;37;67;14;86;27;52;57;35;77;47;81;30;3;55;34;84;42;88;21;78;16;43;73;39;46;29;23;44;50;87;9;12;6;15;49;18;66;17;74;63;62;28;36;26;24;54;25',2),
	(5,12,'R',1,'17;53;44;25;8;85;30;26;46;84;79;41;6;9;43;80;14;2;69;29;86;70','61;38;48;54;73;31;66;83;62;49;22;13;24;58;1;3;42;75;16;81;78;77;36;20;50;55;57;82;71;27;5;90;51;35;60;39;52;45;68;37;23;88;74;64;33;40;47;12;65;76;63;10;32;18;67;21;11;15;19;4;59;89;72;87;7;28;34;56',1),
	(6,12,'R',0,'51;61;41;37;43;79;77;4;62;83;87;5;64;32;45;18;59;27;76;89;81;30;12;10;68;55;25;56;9;7;47;44;38;50;34;53;13;24','42;14;36;46;71;33;84;90;86;72;26;21;54;82;70;35;11;15;88;1;69;39;65;20;22;19;58;78;75;85;31;2;40;52;60;28;16;29;57;8;66;63;67;6;74;73;80;17;23;3;49;48',2);

/*!40000 ALTER TABLE `rounds` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table statuses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `statuses`;

CREATE TABLE `statuses` (
  `statusId` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`statusId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;

INSERT INTO `statuses` (`statusId`, `status`)
VALUES
	(1,'Til trykking'),
	(2,'Mottatt av NK'),
	(3,'Motatt av kommisjonær'),
	(4,'Solgt til kunde'),
	(5,'Premie ikke avhentet'),
	(6,'Premie delvis avhentet'),
	(7,'Premie utbetalt');

/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table places
# ------------------------------------------------------------

DROP TABLE IF EXISTS `places`;

CREATE TABLE `places` (
  `placeId` int(11) NOT NULL AUTO_INCREMENT,
  `place` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`placeId`),
  KEY `fk_steder_kunder1_idx` (`place`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `places` WRITE;
/*!40000 ALTER TABLE `places` DISABLE KEYS */;

INSERT INTO `places` (`placeId`, `place`)
VALUES
	(5,'Reipå'),
	(2,'Tromsø'),
	(1,'Ørnes');

/*!40000 ALTER TABLE `places` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table distributors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `distributors`;

CREATE TABLE `distributors` (
  `distributorId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`distributorId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `distributors` WRITE;
/*!40000 ALTER TABLE `distributors` DISABLE KEYS */;

INSERT INTO `distributors` (`distributorId`, `name`)
VALUES
	(1,'Joker\'n Reipå'),
	(2,'Rema 1000 Tromsø'),
	(3,'Nærkanalens lokaler');

/*!40000 ALTER TABLE `distributors` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table winners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `winners`;

CREATE TABLE `winners` (
  `winnerId` int(11) NOT NULL AUTO_INCREMENT,
  `customerId` int(11) NOT NULL,
  `verification` int(11) NOT NULL,
  `date` date NOT NULL,
  `roundId` int(11) NOT NULL,
  `leftToPay` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `statusId` int(11) NOT NULL,
  `rows` int(11) NOT NULL,
  PRIMARY KEY (`winnerId`),
  KEY `fk_vinnere_statuser1_idx` (`statusId`),
  KEY `fk_vinnere_kunder1_idx` (`customerId`),
  KEY `fk_vinnere_omgang1_idx` (`roundId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

LOCK TABLES `winners` WRITE;
/*!40000 ALTER TABLE `winners` DISABLE KEYS */;

INSERT INTO `winners` (`winnerId`, `customerId`, `verification`, `date`, `roundId`, `leftToPay`, `price`, `statusId`, `rows`)
VALUES
	(1,1,10000,'2014-10-11',1,500,500,5,1),
	(2,2,10001,'2014-10-11',1,250,250,5,1),
	(3,1,10002,'2014-10-11',1,200,200,5,1),
	(4,4,10003,'2014-10-11',1,300,300,5,2),
	(5,5,10004,'2014-10-11',1,400,400,5,2),
	(6,2,10005,'2014-10-11',1,550,550,5,2),
	(7,4,10000,'2014-10-12',5,200,200,5,1),
	(8,4,10002,'2014-10-12',6,300,300,5,2),
	(9,2,10001,'2014-10-12',6,1000,1000,5,2);

/*!40000 ALTER TABLE `winners` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
