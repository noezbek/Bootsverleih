CREATE DATABASE  IF NOT EXISTS `bootsverleih` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bootsverleih`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: bootsverleih
-- ------------------------------------------------------
-- Server version	8.0.39

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
-- Table structure for table `bestellungen`
--

DROP TABLE IF EXISTS `bestellungen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bestellungen` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `kunde_ID` int unsigned NOT NULL,
  `bestellstatus` tinyint unsigned NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_bestellung_kunde` (`kunde_ID`),
  KEY `fk_bestellungen_user` (`userID`),
  CONSTRAINT `fk_bestellung_kunde` FOREIGN KEY (`kunde_ID`) REFERENCES `kunde` (`ID`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_bestellungen_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bestellungen`
--

LOCK TABLES `bestellungen` WRITE;
/*!40000 ALTER TABLE `bestellungen` DISABLE KEYS */;
INSERT INTO `bestellungen` VALUES (4,7,2,1,'2026-02-01 16:29:35','2026-02-01 16:29:35',NULL),(5,7,2,1,'2026-02-01 16:29:35','2026-02-01 16:29:35',NULL),(6,7,2,1,'2026-02-01 16:29:35','2026-02-01 21:38:09',NULL);
/*!40000 ALTER TABLE `bestellungen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boot_hat_feature`
--

DROP TABLE IF EXISTS `boot_hat_feature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boot_hat_feature` (
  `boot_ID` int unsigned NOT NULL,
  `feature_ID` int unsigned NOT NULL,
  PRIMARY KEY (`boot_ID`,`feature_ID`),
  KEY `fk_bhf_feature` (`feature_ID`),
  CONSTRAINT `fk_bhf_boot` FOREIGN KEY (`boot_ID`) REFERENCES `boote` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bhf_feature` FOREIGN KEY (`feature_ID`) REFERENCES `features` (`ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boot_hat_feature`
--

LOCK TABLES `boot_hat_feature` WRITE;
/*!40000 ALTER TABLE `boot_hat_feature` DISABLE KEYS */;
INSERT INTO `boot_hat_feature` VALUES (10,2),(9,3),(9,4),(15,4),(10,5),(11,6),(12,7),(12,8),(12,9),(13,10),(13,11),(14,12),(15,13),(16,14),(18,14),(16,15),(17,16),(17,17),(18,18),(19,19),(19,20);
/*!40000 ALTER TABLE `boot_hat_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boot_mieten`
--

DROP TABLE IF EXISTS `boot_mieten`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boot_mieten` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `bestellung_ID` int unsigned NOT NULL,
  `boot_ID` int unsigned NOT NULL,
  `startdatum` date NOT NULL,
  `enddatum` date NOT NULL,
  `preis_pro_tag` decimal(10,2) NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `userID` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `fk_bm_bestellung` (`bestellung_ID`),
  KEY `fk_bm_boot` (`boot_ID`),
  KEY `fk_boot_mieten_user` (`userID`),
  CONSTRAINT `fk_bm_bestellung` FOREIGN KEY (`bestellung_ID`) REFERENCES `bestellungen` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_bm_boot` FOREIGN KEY (`boot_ID`) REFERENCES `boote` (`ID`),
  CONSTRAINT `fk_boot_mieten_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boot_mieten`
--

LOCK TABLES `boot_mieten` WRITE;
/*!40000 ALTER TABLE `boot_mieten` DISABLE KEYS */;
INSERT INTO `boot_mieten` VALUES (4,5,5,'2025-12-28','2025-12-28',120.00,1,NULL,'2026-02-01 21:48:27','2026-02-01 21:48:27');
/*!40000 ALTER TABLE `boot_mieten` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boote`
--

DROP TABLE IF EXISTS `boote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boote` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `laenge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `breite` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tiefgang` decimal(4,2) NOT NULL DEFAULT '0.00',
  `beschreibung` varchar(255) NOT NULL,
  `kapazitaet` int DEFAULT NULL,
  `bootstyp` tinyint DEFAULT NULL,
  `verfuegbarkeit` tinyint DEFAULT NULL,
  `preis_pro_tag` decimal(10,2) NOT NULL,
  `kaution` decimal(10,2) NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_boote_user` (`userID`),
  CONSTRAINT `fk_boote_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boote`
--

LOCK TABLES `boote` WRITE;
/*!40000 ALTER TABLE `boote` DISABLE KEYS */;
INSERT INTO `boote` VALUES (5,8.50,1.00,1.00,'Windspiel',4,1,1,120.00,500.00,1,'2026-01-20 11:43:34','2026-02-01 16:00:46',NULL),(6,6.20,1.00,1.00,'Meerblick',6,2,1,1.00,1.00,1,'2026-01-20 11:43:34','2026-01-20 11:43:34',NULL),(7,3.50,1.00,1.00,'Wellenreiter',2,3,1,1.00,1.00,1,'2026-01-20 11:43:34','2026-01-20 11:43:34',NULL),(8,10.00,1.00,1.00,'Sonnenschein',6,1,1,1.00,1.00,1,'2026-01-20 11:43:34','2026-01-20 11:43:34',NULL),(9,7.20,0.00,0.00,'Poseidon',8,2,1,180.00,600.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(10,6.80,0.00,0.00,'Meeresbrise',4,1,1,95.00,400.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(11,4.20,0.00,0.00,'Forelle',2,3,1,35.00,100.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(12,9.50,0.00,0.00,'Neptun Express',10,2,2,250.00,800.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(13,10.20,0.00,0.00,'Seeadler',8,1,1,200.00,700.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(14,4.80,0.00,0.00,'Waldbach',3,4,1,40.00,80.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(15,6.50,0.00,0.00,'Sunset Cruiser',6,2,1,150.00,550.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(16,3.20,0.00,0.00,'Wave Rider',1,5,1,25.00,50.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(17,4.50,0.00,0.00,'Lake Explorer',2,3,2,38.00,100.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(18,3.40,0.00,0.00,'Ocean Breeze',1,5,1,28.00,50.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL),(19,5.20,0.00,0.00,'Familie Plus',4,4,1,45.00,120.00,1,'2026-02-01 16:07:27','2026-02-01 17:00:24',NULL);
/*!40000 ALTER TABLE `boote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(80) NOT NULL,
  `beschreibung` varchar(255) DEFAULT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uq_boot_features_name` (`bezeichnung`),
  KEY `fk_features_user` (`userID`),
  CONSTRAINT `fk_features_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (1,'WC',NULL,1,'2026-01-20 11:44:10','2026-02-01 16:03:20',NULL),(2,'Kajüte',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(3,'Sonnendeck',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(4,'Kühlbox',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(5,'Navigation',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(6,'Schwimmwesten inkl.',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(7,'Kabine',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(8,'Grill',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(9,'Radio',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(10,'2 Kajüten',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(11,'Küche',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(12,'Paddel inkl.',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(13,'Musikanlage',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(14,'Paddel',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(15,'Pumpe',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(16,'Wasserdicht',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(17,'GPS',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(18,'Tasche',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(19,'Stabil',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL),(20,'geräumig',NULL,1,'2026-01-20 11:44:10','2026-01-20 11:44:10',NULL);
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kunde`
--

DROP TABLE IF EXISTS `kunde`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kunde` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `geburtsdatum` date NOT NULL,
  `telefon` varchar(30) NOT NULL,
  `strasse` varchar(150) NOT NULL,
  `plz` int NOT NULL,
  `stadt` varchar(100) NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_kunde_user` (`userID`),
  CONSTRAINT `fk_kunde_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kunde`
--

LOCK TABLES `kunde` WRITE;
/*!40000 ALTER TABLE `kunde` DISABLE KEYS */;
INSERT INTO `kunde` VALUES (7,'Max','Müller','max.mueller@email.de','1985-06-15','+49 170 1234567','Hauptstraße 15',19395,'Plau am See',1,'2026-02-01 16:25:02','2026-02-01 16:25:02',NULL),(8,'Anna','Schmidt','anna.schmidt@email.de','1990-09-22','+49 172 9876543','Seestraße 42',19395,'Plau am See',1,'2026-02-01 16:25:02','2026-02-01 16:25:02',NULL),(9,'Thomas','Weber','thomas.weber@email.de','1978-03-10','+49 151 5551234','Uferweg 8',17213,'Malchow',1,'2026-02-01 16:25:02','2026-02-01 16:25:02',NULL),(10,'Julia','Fischer','julia.fischer@email.de','1995-01-05','+49 160 7778888','Fischerweg 23',17192,'Waren',1,'2026-02-01 16:25:02','2026-02-01 16:25:02',NULL),(11,'Peter','Schneider','peter.schneider@email.de','1970-02-18','+49 175 4443332','Bergstraße 67',17207,'Röbel',0,'2026-02-01 16:25:02','2026-02-01 16:25:02',NULL),(12,'Sarah','Bauer','sarah.bauer@email.de','1988-11-12','+49 162 1112223','Gartenweg 5',19395,'Plau am See',1,'2026-02-01 16:25:02','2026-02-01 16:25:02',NULL);
/*!40000 ALTER TABLE `kunde` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `liegeplaetze`
--

DROP TABLE IF EXISTS `liegeplaetze`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `liegeplaetze` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(255) DEFAULT NULL,
  `beschreibung` varchar(255) NOT NULL,
  `preis_pro_tag` decimal(10,2) DEFAULT NULL,
  `kapazitaet` int DEFAULT NULL,
  `pos_x` decimal(5,2) NOT NULL DEFAULT '0.00',
  `pos_y` decimal(5,2) NOT NULL DEFAULT '0.00',
  `pos_w` decimal(5,2) NOT NULL DEFAULT '0.00',
  `pos_h` decimal(5,2) NOT NULL DEFAULT '0.00',
  `userID` int unsigned DEFAULT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `fk_liegeplaetze_user` (`userID`),
  CONSTRAINT `fk_liegeplaetze_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liegeplaetze`
--

LOCK TABLES `liegeplaetze` WRITE;
/*!40000 ALTER TABLE `liegeplaetze` DISABLE KEYS */;
INSERT INTO `liegeplaetze` VALUES (3,'A1','A-1',50.00,2,20.30,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(4,'A2','A-2',50.00,2,23.50,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(5,'A3','A-3',50.00,2,26.80,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(6,'A4','A-4',50.00,2,34.70,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(7,'A5','A-5',50.00,2,38.70,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(8,'A6','A-6',50.00,2,42.60,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(9,'A7','A-7',50.00,2,46.30,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(10,'A8','A-8',50.00,2,49.90,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(11,'A9','A-9',50.00,2,53.70,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(12,'A10','A-10',50.00,2,57.40,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(13,'A11','A-11',50.00,2,59.70,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(14,'A12','A-12',50.00,2,64.10,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(15,'A13','A-13',50.00,2,67.20,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(16,'A14','A-14',50.00,2,70.50,30.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(17,'B1','B-1',50.00,2,20.00,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(18,'B2','B-2',50.00,2,21.80,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(19,'B3','B-3',50.00,2,23.60,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(20,'B4','B-4',50.00,2,27.00,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(21,'B5','B-5',50.00,2,30.30,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(22,'B6','B-6',50.00,2,33.20,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(23,'B7','B-7',50.00,2,36.00,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(24,'B8','B-8',50.00,2,38.80,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(25,'B9','B-9',50.00,2,41.80,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(26,'B10','B-10',50.00,2,45.10,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56'),(27,'B11','B-11',50.00,2,48.40,59.00,1.50,7.00,NULL,1,'2026-02-01 15:55:56','2026-02-01 15:55:56');
/*!40000 ALTER TABLE `liegeplaetze` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `liegeplatz_reservierungen`
--

DROP TABLE IF EXISTS `liegeplatz_reservierungen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `liegeplatz_reservierungen` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `bestellung_ID` int unsigned NOT NULL,
  `liegeplatz_ID` int unsigned NOT NULL,
  `boot_ID` int unsigned NOT NULL,
  `startdatum` date NOT NULL,
  `enddatum` date NOT NULL,
  `preis_pro_tag` decimal(10,2) NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `userID` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `fk_lpr_bestellung` (`bestellung_ID`),
  KEY `fk_lpr_liegeplatz` (`liegeplatz_ID`),
  KEY `fk_lpr_boot` (`boot_ID`),
  KEY `fk_liegeplatz_res_user` (`userID`),
  CONSTRAINT `fk_liegeplatz_res_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_lpr_bestellung` FOREIGN KEY (`bestellung_ID`) REFERENCES `bestellungen` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_lpr_boot` FOREIGN KEY (`boot_ID`) REFERENCES `boote` (`ID`),
  CONSTRAINT `fk_lpr_liegeplatz` FOREIGN KEY (`liegeplatz_ID`) REFERENCES `liegeplaetze` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liegeplatz_reservierungen`
--

LOCK TABLES `liegeplatz_reservierungen` WRITE;
/*!40000 ALTER TABLE `liegeplatz_reservierungen` DISABLE KEYS */;
INSERT INTO `liegeplatz_reservierungen` VALUES (2,4,27,5,'2024-02-05','2024-02-05',50.00,1,NULL,'2026-02-01 21:51:07','2026-02-01 21:51:07'),(3,6,26,5,'2025-12-28','2024-02-05',50.00,1,NULL,'2026-02-01 21:51:07','2026-02-01 21:51:07');
/*!40000 ALTER TABLE `liegeplatz_reservierungen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mitarbeiter`
--

DROP TABLE IF EXISTS `mitarbeiter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mitarbeiter` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `vorname` varchar(100) NOT NULL,
  `nachname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `geburtsdatum` date NOT NULL,
  `telefon` varchar(30) NOT NULL,
  `strasse` varchar(150) NOT NULL,
  `plz` int NOT NULL,
  `stadt` varchar(100) NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_mitarbeiter_user` (`userID`),
  CONSTRAINT `fk_mitarbeiter_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mitarbeiter`
--

LOCK TABLES `mitarbeiter` WRITE;
/*!40000 ALTER TABLE `mitarbeiter` DISABLE KEYS */;
/*!40000 ALTER TABLE `mitarbeiter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `kunde_ID` int unsigned DEFAULT NULL,
  `mitarbeiter_ID` int unsigned DEFAULT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_user_kunde` (`kunde_ID`),
  KEY `fk_user_mitarbeiter` (`mitarbeiter_ID`),
  CONSTRAINT `fk_user_kunde` FOREIGN KEY (`kunde_ID`) REFERENCES `kunde` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_user_mitarbeiter` FOREIGN KEY (`mitarbeiter_ID`) REFERENCES `mitarbeiter` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'noezbek','$2y$10$9TP9ZiG7coNHgFtnKwyqae4kKWRRtMwY9n/wwvJLANmadJrrRG676',7,NULL,1,'2026-02-01 19:50:37','2026-02-01 21:37:27'),(6,'mbarth','$2y$10$9TP9ZiG7coNHgFtnKwyqae4kKWRRtMwY9n/wwvJLANmadJrrRG676',8,NULL,1,'2026-02-01 19:50:37','2026-02-01 21:37:27');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vertraege`
--

DROP TABLE IF EXISTS `vertraege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vertraege` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `bestellung_ID` int unsigned NOT NULL,
  `vertragsbeginn` date NOT NULL,
  `zahlungsrhythmus` tinyint unsigned NOT NULL,
  `zahlungsmethode` tinyint unsigned NOT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `gekuendigt_am` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_vertrag_bestellung` (`bestellung_ID`),
  KEY `fk_vertrag_user` (`userID`),
  CONSTRAINT `fk_vertrag_bestellung` FOREIGN KEY (`bestellung_ID`) REFERENCES `bestellungen` (`ID`) ON DELETE CASCADE,
  CONSTRAINT `fk_vertrag_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vertraege`
--

LOCK TABLES `vertraege` WRITE;
/*!40000 ALTER TABLE `vertraege` DISABLE KEYS */;
INSERT INTO `vertraege` VALUES (10,4,'2024-02-05',3,2,1,NULL,'2026-02-01 21:44:25','2026-02-01 21:50:22',NULL),(11,5,'2025-06-21',2,4,1,NULL,'2026-02-01 21:44:25','2026-02-01 21:44:25',NULL),(12,6,'2025-12-28',1,1,1,NULL,'2026-02-01 21:44:25','2026-02-01 21:47:23',NULL);
/*!40000 ALTER TABLE `vertraege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zahlungen`
--

DROP TABLE IF EXISTS `zahlungen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zahlungen` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `vertrag_ID` int unsigned NOT NULL,
  `zahlungsstatus` tinyint unsigned NOT NULL,
  `betrag` decimal(10,2) NOT NULL,
  `bezahlt_am` datetime DEFAULT NULL,
  `faellig_am` date DEFAULT NULL,
  `active` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `fk_zahlungen_user` (`userID`),
  KEY `fk_zahlung_vertrag` (`vertrag_ID`),
  CONSTRAINT `fk_zahlung_vertrag` FOREIGN KEY (`vertrag_ID`) REFERENCES `vertraege` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_zahlungen_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zahlungen`
--

LOCK TABLES `zahlungen` WRITE;
/*!40000 ALTER TABLE `zahlungen` DISABLE KEYS */;
INSERT INTO `zahlungen` VALUES (22,10,2,1250.00,'2025-02-05 00:00:00','2025-02-05',1,'2026-02-01 21:55:55','2026-02-01 21:55:55',NULL),(23,10,2,1250.00,'2024-02-05 00:00:00','2024-02-05',1,'2026-02-01 21:55:56','2026-02-01 21:55:56',NULL),(24,11,2,350.00,'2025-06-25 00:00:00','2025-06-30',1,'2026-02-01 21:55:56','2026-02-01 21:55:56',NULL),(25,11,2,350.00,'2025-07-25 00:00:00','2025-07-30',1,'2026-02-01 21:55:56','2026-02-01 21:55:56',NULL),(26,12,1,580.00,NULL,'2026-02-05',1,'2026-02-01 21:55:56','2026-02-01 21:55:56',NULL);
/*!40000 ALTER TABLE `zahlungen` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-01 23:12:09
