# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.39-1)
# Database: bingo
# Generation Time: 2014-10-11 18:58:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table blokker
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blokker`;

CREATE TABLE `blokker` (
  `kontrollnr` int(11) NOT NULL,
  `bong` text,
  `fingeravtrykk` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`kontrollnr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `blokker` WRITE;
/*!40000 ALTER TABLE `blokker` DISABLE KEYS */;

INSERT INTO `blokker` (`kontrollnr`, `bong`, `fingeravtrykk`)
VALUES
	(10000,';6;;.;;26;;30;;40;;.;;.;;.;;83;-;9;;14;;.;;.;;41;;52;;60;;.;;.;-;.;;18;;.;;39;;.;;.;;69;;77;;86;',NULL),
	(10001,';.;;17;;27;;31;;.;;.;;.;;73;;81;-;.;;.;;28;;36;;47;;55;;.;;.;;90;-;2;;.;;29;;.;;.;;59;;65;;75;;.;',NULL),
	(10002,';1;;.;;20;;.;;.;;.;;64;;70;;82;-;4;;.;;.;;32;;49;;.;;.;;71;;89;-;.;;15;;.;;33;;.;;57;;67;;72;;.;',NULL),
	(10003,';8;;11;;.;;.;;43;;50;;.;;.;;85;-;.;;19;;23;;34;;.;;58;;.;;76;;.;-;.;;.;;25;;.;;46;;.;;61;;79;;87;',NULL),
	(10004,';.;;12;;22;;35;;.;;51;;62;;.;;.;-;.;;13;;24;;.;;44;;.;;66;;78;;.;-;3;;16;;.;;.;;48;;54;;.;;.;;80;',NULL),
	(10005,';5;;10;;.;;.;;42;;53;;.;;.;;84;-;7;;.;;21;;37;;.;;56;;63;;.;;.;-;.;;.;;.;;38;;45;;.;;68;;74;;88;',NULL);

/*!40000 ALTER TABLE `blokker` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blokker_hefter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blokker_hefter`;

CREATE TABLE `blokker_hefter` (
  `salgsnr` int(11) NOT NULL,
  `kontrollnr` int(11) NOT NULL,
  KEY `fk_blokker_hefter_blokker1_idx` (`kontrollnr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `blokker_hefter` WRITE;
/*!40000 ALTER TABLE `blokker_hefter` DISABLE KEYS */;

INSERT INTO `blokker_hefter` (`salgsnr`, `kontrollnr`)
VALUES
	(12345,10000),
	(12345,10001),
	(12345,10002),
	(12345,10003),
	(12345,10004),
	(12345,10005);

/*!40000 ALTER TABLE `blokker_hefter` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table hefter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `hefter`;

CREATE TABLE `hefter` (
  `salgsnr` int(11) NOT NULL AUTO_INCREMENT,
  `utsalgsstedid` int(11) DEFAULT NULL,
  `statusid` int(11) NOT NULL,
  PRIMARY KEY (`salgsnr`),
  KEY `fk_hefter_utsalgssteder1_idx` (`utsalgsstedid`)
) ENGINE=InnoDB AUTO_INCREMENT=12346 DEFAULT CHARSET=utf8;

LOCK TABLES `hefter` WRITE;
/*!40000 ALTER TABLE `hefter` DISABLE KEYS */;

INSERT INTO `hefter` (`salgsnr`, `utsalgsstedid`, `statusid`)
VALUES
	(12345,1,4);

/*!40000 ALTER TABLE `hefter` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kunder
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kunder`;

CREATE TABLE `kunder` (
  `kundeid` int(11) NOT NULL AUTO_INCREMENT,
  `navn` varchar(100) DEFAULT NULL,
  `stedid` int(11) DEFAULT NULL,
  PRIMARY KEY (`kundeid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `kunder` WRITE;
/*!40000 ALTER TABLE `kunder` DISABLE KEYS */;

INSERT INTO `kunder` (`kundeid`, `navn`, `stedid`)
VALUES
	(1,'Adrian K. Eriksen',1),
	(2,'Marius Flage',2),
	(4,'Reinert Årseth',1),
	(5,'Navn Navnesen',5);

/*!40000 ALTER TABLE `kunder` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table omganger
# ------------------------------------------------------------

DROP TABLE IF EXISTS `omganger`;

CREATE TABLE `omganger` (
  `omgangid` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL,
  `navn` tinyint(4) NOT NULL,
  `tall` text,
  `tidligereTall` text,
  `antallRader` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`omgangid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `omganger` WRITE;
/*!40000 ALTER TABLE `omganger` DISABLE KEYS */;

INSERT INTO `omganger` (`omgangid`, `type`, `navn`, `tall`, `tidligereTall`, `antallRader`)
VALUES
	(1,'V',1,'22;38;2;60;61;70;53;45;8;82;31;11;33;72;68;1;85;32;65;51;25','76;79;56;75;20;71;10;13;64;89;19;83;58;40;69;41;5;59;48;90;7;4;80;37;67;14;86;27;52;57;35;77;47;81;30;3;55;34;84;42;88;21;78;16;43;73;39;46;29;23;44;50;87;9;12;6;15;49;18;66;17;74;63;62;28;36;26;24;54',1);

/*!40000 ALTER TABLE `omganger` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table statuser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `statuser`;

CREATE TABLE `statuser` (
  `statusid` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

LOCK TABLES `statuser` WRITE;
/*!40000 ALTER TABLE `statuser` DISABLE KEYS */;

INSERT INTO `statuser` (`statusid`, `status`)
VALUES
	(1,'Til trykking'),
	(2,'Mottatt av NK'),
	(3,'Motatt av kommisjonær'),
	(4,'Solgt til kunde'),
	(5,'Premie ikke avhentet'),
	(6,'Premie delvis avhentet'),
	(7,'Premie utbetalt');

/*!40000 ALTER TABLE `statuser` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table steder
# ------------------------------------------------------------

DROP TABLE IF EXISTS `steder`;

CREATE TABLE `steder` (
  `stedid` int(11) NOT NULL AUTO_INCREMENT,
  `sted` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`stedid`),
  KEY `fk_steder_kunder1_idx` (`sted`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `steder` WRITE;
/*!40000 ALTER TABLE `steder` DISABLE KEYS */;

INSERT INTO `steder` (`stedid`, `sted`)
VALUES
	(5,'Reipå'),
	(2,'Tromsø'),
	(1,'Ørnes');

/*!40000 ALTER TABLE `steder` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table utsalgssteder
# ------------------------------------------------------------

DROP TABLE IF EXISTS `utsalgssteder`;

CREATE TABLE `utsalgssteder` (
  `utsalgsstedid` int(11) NOT NULL AUTO_INCREMENT,
  `navn` varchar(45) NOT NULL,
  PRIMARY KEY (`utsalgsstedid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `utsalgssteder` WRITE;
/*!40000 ALTER TABLE `utsalgssteder` DISABLE KEYS */;

INSERT INTO `utsalgssteder` (`utsalgsstedid`, `navn`)
VALUES
	(1,'Joker\'n Reipå'),
	(2,'Rema 1000 Tromsø'),
	(3,'Nærkanalens lokaler');

/*!40000 ALTER TABLE `utsalgssteder` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table vinnere
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vinnere`;

CREATE TABLE `vinnere` (
  `vinnerid` int(11) NOT NULL AUTO_INCREMENT,
  `kundeid` int(11) NOT NULL,
  `kontrollnr` int(11) NOT NULL,
  `dato` date NOT NULL,
  `omgangid` int(11) NOT NULL,
  `igjenUtbetale` int(11) NOT NULL,
  `utbetaling` int(11) NOT NULL,
  `statusid` int(11) NOT NULL,
  PRIMARY KEY (`vinnerid`),
  KEY `fk_vinnere_statuser1_idx` (`statusid`),
  KEY `fk_vinnere_kunder1_idx` (`kundeid`),
  KEY `fk_vinnere_omgang1_idx` (`omgangid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

LOCK TABLES `vinnere` WRITE;
/*!40000 ALTER TABLE `vinnere` DISABLE KEYS */;

INSERT INTO `vinnere` (`vinnerid`, `kundeid`, `kontrollnr`, `dato`, `omgangid`, `igjenUtbetale`, `utbetaling`, `statusid`)
VALUES
	(1,1,10000,'2014-10-11',1,500,500,5),
	(2,2,10001,'2014-10-11',1,250,250,5),
	(3,1,10002,'2014-10-11',1,200,200,5),
	(4,4,10003,'2014-10-11',1,300,300,5),
	(5,5,10004,'2014-10-11',1,400,400,5),
	(6,2,10005,'2014-10-11',1,550,550,5);

/*!40000 ALTER TABLE `vinnere` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
