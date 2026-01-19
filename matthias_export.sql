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
-- Table structure for table `bestellung_boot`
--

DROP TABLE IF EXISTS `bestellung_boot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bestellung_boot` (
                                   `bestellung_ID` int unsigned NOT NULL,
                                   `boot_ID` int unsigned NOT NULL,
                                   PRIMARY KEY (`bestellung_ID`,`boot_ID`),
                                   KEY `fk_bb_boot` (`boot_ID`),
                                   CONSTRAINT `fk_bb_bestellung` FOREIGN KEY (`bestellung_ID`) REFERENCES `bestellungen` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
                                   CONSTRAINT `fk_bb_boot` FOREIGN KEY (`boot_ID`) REFERENCES `boote` (`ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bestellung_boot`
--

LOCK TABLES `bestellung_boot` WRITE;
/*!40000 ALTER TABLE `bestellung_boot` DISABLE KEYS */;
/*!40000 ALTER TABLE `bestellung_boot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bestellung_liegeplatz`
--

DROP TABLE IF EXISTS `bestellung_liegeplatz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bestellung_liegeplatz` (
                                         `bestellung_ID` int unsigned NOT NULL,
                                         `liegeplatz_ID` int unsigned NOT NULL,
                                         PRIMARY KEY (`bestellung_ID`,`liegeplatz_ID`),
                                         KEY `fk_bl_liegeplatz` (`liegeplatz_ID`),
                                         CONSTRAINT `fk_bl_bestellung` FOREIGN KEY (`bestellung_ID`) REFERENCES `bestellungen` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
                                         CONSTRAINT `fk_bl_liegeplatz` FOREIGN KEY (`liegeplatz_ID`) REFERENCES `liegeplaetze` (`ID`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bestellung_liegeplatz`
--

LOCK TABLES `bestellung_liegeplatz` WRITE;
/*!40000 ALTER TABLE `bestellung_liegeplatz` DISABLE KEYS */;
/*!40000 ALTER TABLE `bestellung_liegeplatz` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bestellungen`
--

LOCK TABLES `bestellungen` WRITE;
/*!40000 ALTER TABLE `bestellungen` DISABLE KEYS */;
INSERT INTO `bestellungen` VALUES (1,1,1,1,'2026-01-19 00:13:05','2026-01-19 00:13:05',NULL);
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
/*!40000 ALTER TABLE `boot_hat_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boote`
--

DROP TABLE IF EXISTS `boote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `boote` (
                         `ID` int unsigned NOT NULL AUTO_INCREMENT,
                         `laenge` decimal(5,2) DEFAULT NULL,
                         `breite` decimal(5,2) DEFAULT NULL,
                         `tiefgang` decimal(4,2) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boote`
--

LOCK TABLES `boote` WRITE;
/*!40000 ALTER TABLE `boote` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
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
                         `telefon` int NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kunde`
--

LOCK TABLES `kunde` WRITE;
/*!40000 ALTER TABLE `kunde` DISABLE KEYS */;
INSERT INTO `kunde` VALUES (1,'Test','Kunde','testkunde@example.de','2000-01-01',491234567,'Teststraße 1',12345,'Teststadt',1,'2026-01-18 19:40:59','2026-01-18 19:40:59',NULL);
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
                                `beschreibung` varchar(255) NOT NULL,
                                `userID` int unsigned DEFAULT NULL,
                                PRIMARY KEY (`ID`),
                                KEY `fk_liegeplaetze_user` (`userID`),
                                CONSTRAINT `fk_liegeplaetze_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `liegeplaetze`
--

LOCK TABLES `liegeplaetze` WRITE;
/*!40000 ALTER TABLE `liegeplaetze` DISABLE KEYS */;
/*!40000 ALTER TABLE `liegeplaetze` ENABLE KEYS */;
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
                               `telefon` int NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zahlungen`
--

DROP TABLE IF EXISTS `zahlungen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zahlungen` (
                             `ID` int unsigned NOT NULL AUTO_INCREMENT,
                             `bestellung_ID` int unsigned NOT NULL,
                             `zahlungsstatus` tinyint unsigned NOT NULL,
                             `betrag` decimal(10,2) NOT NULL,
                             `bezahlt_am` datetime DEFAULT NULL,
                             `faellig_am` date DEFAULT NULL,
                             `active` tinyint unsigned NOT NULL DEFAULT '1',
                             `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                             `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                             `userID` int unsigned DEFAULT NULL,
                             PRIMARY KEY (`ID`),
                             KEY `fk_zahlung_bestellung` (`bestellung_ID`),
                             KEY `fk_zahlungen_user` (`userID`),
                             CONSTRAINT `fk_zahlung_bestellung` FOREIGN KEY (`bestellung_ID`) REFERENCES `bestellungen` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
                             CONSTRAINT `fk_zahlungen_user` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zahlungen`
--

LOCK TABLES `zahlungen` WRITE;
/*!40000 ALTER TABLE `zahlungen` DISABLE KEYS */;
INSERT INTO `zahlungen` VALUES (1,1,1,1.00,NULL,NULL,1,'2026-01-19 00:13:29','2026-01-19 00:13:29',NULL);
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

-- Dump completed on 2026-01-19  2:45:12
