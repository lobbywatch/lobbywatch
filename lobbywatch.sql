-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: lobbywatch
-- ------------------------------------------------------
-- Server version	5.7.22

SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT ;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS ;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION ;
SET NAMES utf8 ;
SET @OLD_TIME_ZONE=@@TIME_ZONE ;
SET TIME_ZONE='+00:00' ;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 ;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 ;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' ;
SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 ;

--
-- Current Database: `lobbywatch`
--

CREATE DATABASE IF NOT EXISTS `lobbywatch` DEFAULT CHARACTER SET utf8 ;

USE `lobbywatch`;

--
-- Table structure for table `branche`
--

DROP TABLE IF EXISTS `branche`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `branche` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Branche',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Französischer Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Nationalrat',
  `kommission2_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Ständerat',
  `technischer_name` varchar(30) NOT NULL COMMENT 'Technischer Name für Branche. Keine Sonderzeichen sind erlaubt. Wird z.B. für das finden des Branchensymboles gebraucht.',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Branche',
  `angaben` text COMMENT 'Angaben zur Branche',
  `angaben_fr` text COMMENT 'Angaben zur Branche auf Französisch',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `symbol_abs` varchar(255) DEFAULT NULL COMMENT 'Symbolbild (Icon) der Branche, absoluter Pfad',
  `symbol_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden',
  `symbol_klein_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden',
  `symbol_dateiname_wo_ext` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname ohne Erweiterung',
  `symbol_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Dateierweiterung des Symbolbildes',
  `symbol_dateiname` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname mit Erweiterung',
  `symbol_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Symbolbildes',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `branche_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `technischer_name` (`technischer_name`),
  KEY `idx_kommission_freigabe` (`kommission_id`,`freigabe_datum`),
  KEY `kommission2_id` (`kommission2_id`),
  CONSTRAINT `fk_kommission2_id` FOREIGN KEY (`kommission2_id`) REFERENCES `kommission` (`id`),
  CONSTRAINT `fk_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_branche_log_ins` AFTER INSERT ON `branche`
FOR EACH ROW thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `branche` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_branche_log_upd` AFTER UPDATE ON `branche`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'update', null, NOW(), null FROM `branche` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_branche_log_del_before` BEFORE DELETE ON `branche`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `branche` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_branche_log_del_after` AFTER DELETE ON `branche`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `branche_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `branche_log`
--

DROP TABLE IF EXISTS `branche_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `branche_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Französischer Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
  `kommission2_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Ständerat',
  `technischer_name` varchar(30) NOT NULL COMMENT 'Technischer Name für Branche. Keine Sonderzeichen sind erlaubt. Wird z.B. für das finden des Branchensymboles gebraucht.',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Branche',
  `angaben` text COMMENT 'Angaben zur Branche',
  `angaben_fr` text COMMENT 'Angaben zur Branche auf Französisch',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `symbol_abs` varchar(255) DEFAULT NULL COMMENT 'Symbolbild (Icon) der Branche, absoluter Pfad',
  `symbol_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden',
  `symbol_klein_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden',
  `symbol_dateiname_wo_ext` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname ohne Erweiterung',
  `symbol_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Dateierweiterung des Symbolbildes',
  `symbol_dateiname` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname mit Erweiterung',
  `symbol_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Symbolbildes',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `kommission_id` (`kommission_id`),
  KEY `fk_branche_log_snapshot_id` (`snapshot_id`),
  KEY `kommission2_id` (`kommission2_id`),
  CONSTRAINT `fk_branche_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `country` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `continent` enum('Antarctica','Australia','Africa','North America','South America','Europe','Asia') NOT NULL COMMENT 'Kontinent',
  `name_en` varchar(200) NOT NULL COMMENT 'Name des Landes auf Englisch',
  `official_name_en` varchar(200) NOT NULL COMMENT 'Offizieller Name des Landes (englisch)',
  `capital_en` varchar(200) NOT NULL COMMENT 'Hauptstadt auf Englisch',
  `name_de` varchar(200) NOT NULL COMMENT 'Name des Landes auf Deutsch',
  `official_name_de` varchar(200) NOT NULL COMMENT 'Offizieller Name des Landes (deutsch)',
  `capital_de` varchar(200) NOT NULL COMMENT 'Hauptstadt auf Deutsch',
  `name_fr` varchar(200) DEFAULT NULL COMMENT 'Französischer Name',
  `official_name_fr` varchar(200) DEFAULT NULL COMMENT 'Offizieller Name des Landes (französisch)',
  `capital_fr` varchar(200) DEFAULT NULL COMMENT 'Hauptstadt auf Französisch',
  `name_it` varchar(200) DEFAULT NULL COMMENT 'Italienischer Name',
  `official_name_it` varchar(200) DEFAULT NULL COMMENT 'Offizieller Name des Landes (italiensich)',
  `capital_it` varchar(200) DEFAULT NULL COMMENT 'Hauptstadt auf Italienisch',
  `iso-2` varchar(2) NOT NULL COMMENT 'ISO 3166 ALPHA-2 Code',
  `iso-3` varchar(3) NOT NULL COMMENT 'ISO 3166 ALPHA-3 Code',
  `vehicle_code` varchar(4) DEFAULT NULL COMMENT 'Nationalitätszeichen für Fahrzeuge',
  `ioc` varchar(3) NOT NULL COMMENT ' Ländercodes des Internationalen Olympischen Komitees (IOC)',
  `tld` varchar(6) NOT NULL COMMENT 'Top Level Domain für Internet',
  `currency` varchar(5) NOT NULL COMMENT 'Währungsabkürzung',
  `phone` varchar(10) NOT NULL COMMENT 'Internatinale Vorwahl',
  `utc` mediumint(9) NOT NULL COMMENT 'Verschiebung zur Weltzeit GMT',
  `show_level` int(11) NOT NULL DEFAULT '0' COMMENT 'Anzeigestufe je höher desto selektiver',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_name_de` (`name_de`),
  UNIQUE KEY `idx_name_en` (`name_en`),
  KEY `idx_show_level` (`show_level`),
  KEY `idx_continent` (`continent`)
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 COMMENT='Länder der Welt mit ISO Code (http://countrylist.net)';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `fraktion`
--

DROP TABLE IF EXISTS `fraktion`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `fraktion` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Fraktion',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Fraktionsabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Fraktion',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Fraktion',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Fraktion',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `beschreibung` text COMMENT 'Beschreibung der Fraktion',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Fraktion',
  `von` date DEFAULT NULL COMMENT 'Beginn der Fraktion, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Fraktion, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fraktion_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `fraktion_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_fraktion_log_ins` AFTER INSERT ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `fraktion` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_fraktion_log_upd` AFTER UPDATE ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'update', null, NOW(), null FROM `fraktion` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_fraktion_log_del_before` BEFORE DELETE ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `fraktion` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_fraktion_log_del_after` AFTER DELETE ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `fraktion_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `fraktion_log`
--

DROP TABLE IF EXISTS `fraktion_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `fraktion_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Fraktionsabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Fraktion',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Fraktion',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Fraktion',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `beschreibung` text COMMENT 'Beschreibung der Fraktion',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Fraktion',
  `von` date DEFAULT NULL COMMENT 'Beginn der Fraktion, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Fraktion, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_fraktion_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_fraktion_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `in_kommission`
--

DROP TABLE IF EXISTS `in_kommission`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `in_kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Kommissionszugehörigkeit',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` enum('praesident','vizepraesident','mitglied','co-praesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
  `parlament_committee_function` int(11) DEFAULT NULL COMMENT 'committeeFunction von ws.parlament.ch',
  `parlament_committee_function_name` varchar(40) DEFAULT NULL,
  `von` date DEFAULT NULL COMMENT 'Beginn der Kommissionszugehörigkeit, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Kommissionszugehörigkeit, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_parlam_freigabe` (`parlamentarier_id`,`freigabe_datum`,`bis`,`kommission_id`),
  KEY `idx_parlam` (`parlamentarier_id`,`bis`,`kommission_id`),
  KEY `idx_kommission_freigabe` (`kommission_id`,`freigabe_datum`,`bis`,`parlamentarier_id`),
  KEY `idx_kommission` (`kommission_id`,`bis`,`parlamentarier_id`),
  CONSTRAINT `fk_in_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`),
  CONSTRAINT `fk_in_parlamentarier_id` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1394 DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_in_kommission_log_ins` AFTER INSERT ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `in_kommission` WHERE id = NEW.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.id=NEW.parlamentarier_id;
  UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.parlamentarier_id=NEW.parlamentarier_id;
  SET @disable_table_logging = NULL;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_in_kommission_log_upd` AFTER UPDATE ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'update', null, NOW(), null FROM `in_kommission` WHERE id = NEW.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.id=NEW.parlamentarier_id OR p.id=OLD.parlamentarier_id;
  UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.parlamentarier_id=NEW.parlamentarier_id OR p.parlamentarier_id=OLD.parlamentarier_id;
  SET @disable_table_logging = NULL;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_in_kommission_log_del_before` BEFORE DELETE ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `in_kommission` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_in_kommission_log_del_after` AFTER DELETE ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `in_kommission_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NOW(),
      p.updated_visa = CONCAT('*')
    WHERE p.id=OLD.parlamentarier_id;
  UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NOW(),
      p.updated_visa = CONCAT('*')
    WHERE p.parlamentarier_id=OLD.parlamentarier_id;
  SET @disable_table_logging = NULL;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `in_kommission_log`
--

DROP TABLE IF EXISTS `in_kommission_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `in_kommission_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` enum('praesident','vizepraesident','mitglied','co-praesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
  `parlament_committee_function` int(11) DEFAULT NULL COMMENT 'committeeFunction von ws.parlament.ch',
  `parlament_committee_function_name` varchar(40) DEFAULT NULL,
  `von` date DEFAULT NULL COMMENT 'Beginn der Kommissionszugehörigkeit, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Kommissionszugehörigkeit, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `parlamentarier_id` (`parlamentarier_id`),
  KEY `kommissions_id` (`kommission_id`),
  KEY `fk_in_kommission_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_in_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10333 DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `interessenbindung`
--

DROP TABLE IF EXISTS `interessenbindung`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessenbindung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessenbindung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindung durch einen Import zu letzt aktualisiert wurde. Ein Datum bedeutet, dass die Interessenbindung unter der Kontrolle des Importprozesses.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `interessenbindung_art_parlamentarier_organisation_unique` (`art`,`parlamentarier_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `parlamentarier_id` (`parlamentarier_id`,`organisation_id`),
  KEY `organisation_id` (`organisation_id`,`parlamentarier_id`),
  CONSTRAINT `fk_ib_org` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6988 DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_log_ins` AFTER INSERT ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessenbindung` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_log_upd` AFTER UPDATE ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `interessenbindung_jahr`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        interessenbindung_id = NEW.id;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL)
  THEN
      
      UPDATE `interessenbindung_jahr`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        interessenbindung_id = NEW.id;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)

  THEN
      
      UPDATE `organisation`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        id=NEW.organisation_id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessenbindung` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_log_del_before` BEFORE DELETE ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessenbindung` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_log_del_after` AFTER DELETE ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessenbindung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `interessenbindung_jahr`
--

DROP TABLE IF EXISTS `interessenbindung_jahr`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessenbindung_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Jahresvergütung von Intressenbindung',
  `interessenbindung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Interessenbindung',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `verguetung` int(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Beschreibung der Vergütung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen vom Parlamentarier autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_jahr_unique` (`interessenbindung_id`,`jahr`) COMMENT 'Fachlicher unique constraint',
  KEY `interessenbindung_id` (`interessenbindung_id`,`jahr`) COMMENT 'Idx interessenbindung_id',
  CONSTRAINT `fk_interessenbindung_id` FOREIGN KEY (`interessenbindung_id`) REFERENCES `interessenbindung` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1789 DEFAULT CHARSET=utf8 COMMENT='Jahresvergütung durch Interessenbindungen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_jahr_log_ins` AFTER INSERT ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessenbindung_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_jahr_log_upd` AFTER UPDATE ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessenbindung_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_jahr_log_del_before` BEFORE DELETE ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessenbindung_jahr` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessenbindung_jahr_log_del_after` AFTER DELETE ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessenbindung_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `interessenbindung_jahr_log`
--

DROP TABLE IF EXISTS `interessenbindung_jahr_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessenbindung_jahr_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `interessenbindung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Interessenbindung',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `verguetung` int(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Beschreibung der Vergütung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen vom Parlamentarier autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_interessenbindung_jahr_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_interessenbindung_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7173 DEFAULT CHARSET=utf8 COMMENT='Jahresvergütung durch Interessenbindungen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `interessenbindung_log`
--

DROP TABLE IF EXISTS `interessenbindung_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessenbindung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindung durch einen Import zu letzt aktualisiert wurde. Ein Datum bedeutet, dass die Interessenbindung unter der Kontrolle des Importprozesses.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbyorg` (`organisation_id`),
  KEY `fk_interessenbindung_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_interessenbindung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73282 DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `interessengruppe`
--

DROP TABLE IF EXISTS `interessengruppe`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessengruppe` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessengruppe',
  `name` varchar(150) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichnung der Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
  `beschreibung` text NOT NULL COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe',
  `beschreibung_fr` text COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe auf französisch',
  `alias_namen` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `interessengruppe_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_branche_freigabe` (`branche_id`,`freigabe_datum`),
  CONSTRAINT `fk_lg_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessengruppe_log_ins` AFTER INSERT ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessengruppe` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessengruppe_log_upd` AFTER UPDATE ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessengruppe` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessengruppe_log_del_before` BEFORE DELETE ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessengruppe` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_interessengruppe_log_del_after` AFTER DELETE ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessengruppe_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `interessengruppe_log`
--

DROP TABLE IF EXISTS `interessengruppe_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessengruppe_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(150) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichnung der Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
  `beschreibung` text NOT NULL COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe',
  `beschreibung_fr` text COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe auf französisch',
  `alias_namen` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `fk_interessengruppe_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_interessengruppe_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1713 DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `interessenraum`
--

DROP TABLE IF EXISTS `interessenraum`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `interessenraum` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Interessenraumes',
  `name` varchar(50) NOT NULL COMMENT 'Name des Interessenbereiches',
  `name_fr` varchar(50) DEFAULT NULL COMMENT 'Französischer Name des Interessenbereiches',
  `beschreibung` text COMMENT 'Beschreibung des Interessenraumes',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung des Interessenraumes',
  `reihenfolge` int(11) DEFAULT NULL COMMENT 'Anzeigereihenfolge (je kleiner desto höher)',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `interessenraum_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
  KEY `reihenfolge` (`reihenfolge`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Liste der Interessenbereiche (Stammdaten)';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `kanton`
--

DROP TABLE IF EXISTS `kanton`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `kanton` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Kantons',
  `abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH') NOT NULL COMMENT 'Kantonskürzel',
  `kantonsnr` tinyint(4) NOT NULL COMMENT 'Nummer des Kantons gemäss Verfassung',
  `name_de` varchar(50) NOT NULL COMMENT 'Deutscher Name des Kantons',
  `name_fr` varchar(50) NOT NULL COMMENT 'Französischer Name',
  `name_it` varchar(50) NOT NULL COMMENT 'Italienischer Name',
  `anzahl_staenderaete` tinyint(4) NOT NULL COMMENT 'Anzahl Ständeräte',
  `amtssprache` set('de','fr','it','rm') NOT NULL COMMENT 'Amtssprachen des Kantons',
  `hauptort_de` varchar(50) NOT NULL COMMENT 'Hauptort des Kantons',
  `hauptort_fr` varchar(50) DEFAULT NULL COMMENT 'Hauptort auf französisch',
  `hauptort_it` varchar(50) DEFAULT NULL COMMENT 'Hauptort auf italienisch',
  `flaeche_km2` int(11) unsigned NOT NULL COMMENT 'Fläche in km2',
  `beitrittsjahr` smallint(6) unsigned NOT NULL COMMENT 'Beitrittsjahr zur Schweiz',
  `wappen_klein` varchar(255) NOT NULL COMMENT 'Pfad zu kleinem Wappen des Kantons (25px)',
  `wappen` varchar(255) NOT NULL COMMENT 'Pfad zu Wappen des Kantons (50px)',
  `lagebild` varchar(255) NOT NULL COMMENT 'Pfad zum lagebild des Kantons',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage des Kantons',
  `beschreibung` text COMMENT 'Beschreibung des Kantons',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `abkuerzung` (`abkuerzung`),
  UNIQUE KEY `kantonsnr` (`kantonsnr`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='Kantone der Schweiz';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_log_ins` AFTER INSERT ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kanton` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_log_upd` AFTER UPDATE ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kanton` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_log_del_before` BEFORE DELETE ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kanton` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_log_del_after` AFTER DELETE ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kanton_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `kanton_jahr`
--

DROP TABLE IF EXISTS `kanton_jahr`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `kanton_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte eines Kantons',
  `kanton_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Kantons',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `anzahl_nationalraete` tinyint(4) unsigned NOT NULL COMMENT 'Anzahl Nationalräte des Kantons',
  `einwohner` int(11) unsigned NOT NULL COMMENT 'Wohnbevölkerung des Kantons',
  `auslaenderanteil` float NOT NULL COMMENT 'Ausländeranteil, zwischen 0 und 1',
  `bevoelkerungsdichte` smallint(6) unsigned DEFAULT NULL COMMENT 'Bevölkerungsdichte [Einwohner/km2]',
  `anzahl_gemeinden` smallint(6) unsigned DEFAULT NULL COMMENT 'Anzahl Gemeinden',
  `steuereinnahmen` int(11) unsigned DEFAULT NULL COMMENT 'Stuereinnahmen in Franken',
  `ausgaben` int(11) DEFAULT NULL COMMENT 'Ausgaben in Franken',
  `finanzausgleich` int(11) DEFAULT NULL COMMENT 'Geld durch Finanzausgleich bekommen, in Franken',
  `schulden` int(11) DEFAULT NULL COMMENT 'Schulden des Kantons in Franken',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_kanton_jahr_unique` (`kanton_id`,`jahr`) COMMENT 'Fachlicher unique constraint',
  KEY `kanton_id` (`kanton_id`),
  CONSTRAINT `fk_kanton_jahr_kanton_id` FOREIGN KEY (`kanton_id`) REFERENCES `kanton` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Kantonen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_jahr_log_ins` AFTER INSERT ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kanton_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_jahr_log_upd` AFTER UPDATE ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kanton_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_jahr_log_del_before` BEFORE DELETE ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kanton_jahr` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kanton_jahr_log_del_after` AFTER DELETE ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kanton_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `kanton_jahr_log`
--

DROP TABLE IF EXISTS `kanton_jahr_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `kanton_jahr_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `kanton_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Kantons',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `anzahl_nationalraete` tinyint(4) unsigned NOT NULL COMMENT 'Anzahl Nationalräte des Kantons',
  `einwohner` int(11) unsigned NOT NULL COMMENT 'Wohnbevölkerung des Kantons',
  `auslaenderanteil` float NOT NULL COMMENT 'Ausländeranteil, zwischen 0 und 1',
  `bevoelkerungsdichte` smallint(6) unsigned DEFAULT NULL COMMENT 'Bevölkerungsdichte [Einwohner/km2]',
  `anzahl_gemeinden` smallint(6) unsigned DEFAULT NULL COMMENT 'Anzahl Gemeinden',
  `steuereinnahmen` int(11) unsigned DEFAULT NULL COMMENT 'Stuereinnahmen in Franken',
  `ausgaben` int(11) DEFAULT NULL COMMENT 'Ausgaben in Franken',
  `finanzausgleich` int(11) DEFAULT NULL COMMENT 'Geld durch Finanzausgleich bekommen, in Franken',
  `schulden` int(11) DEFAULT NULL COMMENT 'Schulden des Kantons in Franken',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_kanton_jahr_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_kanton_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Kantonen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `kanton_log`
--

DROP TABLE IF EXISTS `kanton_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `kanton_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH') NOT NULL COMMENT 'Kantonskürzel',
  `kantonsnr` tinyint(4) NOT NULL COMMENT 'Nummer des Kantons gemäss Verfassung',
  `name_de` varchar(50) NOT NULL COMMENT 'Deutscher Name des Kantons',
  `name_fr` varchar(50) NOT NULL COMMENT 'Französischer Name',
  `name_it` varchar(50) NOT NULL COMMENT 'Italienischer Name',
  `anzahl_staenderaete` tinyint(4) NOT NULL COMMENT 'Anzahl Ständeräte',
  `amtssprache` set('de','fr','it','rm') NOT NULL COMMENT 'Amtssprachen des Kantons',
  `hauptort_de` varchar(50) NOT NULL COMMENT 'Hauptort des Kantons',
  `hauptort_fr` varchar(50) DEFAULT NULL COMMENT 'Hauptort auf französisch',
  `hauptort_it` varchar(50) DEFAULT NULL COMMENT 'Hauptort auf italienisch',
  `flaeche_km2` int(11) unsigned NOT NULL COMMENT 'Fläche in km2',
  `beitrittsjahr` smallint(6) unsigned NOT NULL COMMENT 'Beitrittsjahr zur Schweiz',
  `wappen_klein` varchar(255) NOT NULL COMMENT 'Pfad zu kleinem Wappen des Kantons (25px)',
  `wappen` varchar(255) NOT NULL COMMENT 'Pfad zu Wappen des Kantons (50px)',
  `lagebild` varchar(255) NOT NULL COMMENT 'Pfad zum lagebild des Kantons',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage des Kantons',
  `beschreibung` text COMMENT 'Beschreibung des Kantons',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_kanton_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_kanton_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8 COMMENT='Kantone der Schweiz';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `kommission`
--

DROP TABLE IF EXISTS `kommission`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Kommission',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `abkuerzung_fr` varchar(15) DEFAULT NULL COMMENT 'Französisches Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Kommission',
  `rat_id` int(11) DEFAULT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates',
  `typ` enum('kommission','subkommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.',
  `beschreibung` text COMMENT 'Beschreibung der Kommission',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Kommission',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `sachbereiche_fr` text COMMENT 'Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".',
  `anzahl_mitglieder` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder',
  `anzahl_nationalraete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Nationalrates',
  `anzahl_staenderaete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Ständerates',
  `mutter_kommission_id` int(11) DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen).  Also die "Oberkommission".',
  `zweitrat_kommission_id` int(11) DEFAULT NULL COMMENT 'Entsprechende Kommission im anderen Rat, Stände- o. Nationalratskommission',
  `von` date DEFAULT NULL COMMENT 'Beginn der Kommission, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Kommission, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `parlament_url` varchar(255) DEFAULT NULL COMMENT 'Link zur Seite auf Parlament.ch',
  `parlament_id` int(11) DEFAULT NULL COMMENT 'Kommissions-ID von ws.parlament.ch',
  `parlament_committee_number` int(11) DEFAULT NULL COMMENT 'committeeNumber auf ws.parlament.ch',
  `parlament_subcommittee_number` int(11) DEFAULT NULL COMMENT 'subcommitteeNumber auf ws.parlament.ch',
  `parlament_type_code` int(11) DEFAULT NULL COMMENT 'typeCode von ws.parlament.ch',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kommission_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `kommission_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
  KEY `zugehoerige_kommission` (`mutter_kommission_id`,`freigabe_datum`),
  KEY `rat_id` (`rat_id`),
  KEY `zweitrat_kommission_id` (`zweitrat_kommission_id`),
  CONSTRAINT `fk_parent_kommission_id` FOREIGN KEY (`mutter_kommission_id`) REFERENCES `kommission` (`id`),
  CONSTRAINT `fk_zweitrat_kommission_id` FOREIGN KEY (`zweitrat_kommission_id`) REFERENCES `kommission` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kommission_log_ins` AFTER INSERT ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kommission` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kommission_log_upd` AFTER UPDATE ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kommission` WHERE id = NEW.id ;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      
      
      
      UPDATE `in_kommission`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        kommission_id = NEW.id AND bis IS NULL;
      
  END IF;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kommission_log_del_before` BEFORE DELETE ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kommission` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_kommission_log_del_after` AFTER DELETE ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kommission_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `kommission_log`
--

DROP TABLE IF EXISTS `kommission_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `kommission_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `abkuerzung_fr` varchar(15) DEFAULT NULL COMMENT 'Französisches Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Kommission',
  `rat_id` int(11) DEFAULT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates',
  `typ` enum('kommission','subkommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.',
  `beschreibung` text COMMENT 'Beschreibung der Kommission',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Kommission',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `sachbereiche_fr` text COMMENT 'Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".',
  `anzahl_mitglieder` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder',
  `anzahl_nationalraete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Nationalrates',
  `anzahl_staenderaete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Ständerates',
  `mutter_kommission_id` int(11) DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen).  Also die "Oberkommission".',
  `zweitrat_kommission_id` int(11) DEFAULT NULL COMMENT 'Entsprechende Kommission im anderen Rat, Stände- o. Nationalratskommission',
  `von` date DEFAULT NULL COMMENT 'Beginn der Kommission, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Kommission, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `parlament_url` varchar(255) DEFAULT NULL COMMENT 'Link zur Seite auf Parlament.ch',
  `parlament_id` int(11) DEFAULT NULL COMMENT 'Kommissions-ID von ws.parlament.ch',
  `parlament_committee_number` int(11) DEFAULT NULL COMMENT 'committeeNumber auf ws.parlament.ch',
  `parlament_subcommittee_number` int(11) DEFAULT NULL COMMENT 'subcommitteeNumber auf ws.parlament.ch',
  `parlament_type_code` int(11) DEFAULT NULL COMMENT 'typeCode von ws.parlament.ch',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `zugehoerige_kommission` (`mutter_kommission_id`),
  KEY `fk_kommission_log_snapshot_id` (`snapshot_id`),
  KEY `rat_id` (`rat_id`),
  KEY `zweitrat_kommission_id` (`zweitrat_kommission_id`),
  CONSTRAINT `fk_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=507 DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mandat`
--

DROP TABLE IF EXISTS `mandat`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mandat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Mandates. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn des Mandates, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende des Mandates, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mandat_person_organisation_art_unique` (`art`,`person_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `organisations_id` (`organisation_id`,`person_id`),
  KEY `person_id` (`person_id`,`organisation_id`) COMMENT 'person_id',
  CONSTRAINT `fk_mandat_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_organisations_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3318 DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_log_ins` AFTER INSERT ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mandat` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_log_upd` AFTER UPDATE ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `mandat_jahr`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        mandat_id = NEW.id;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      
      UPDATE `mandat_jahr`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        mandat_id = NEW.id;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    
  THEN
      UPDATE `organisation`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        id=NEW.organisation_id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mandat` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_log_del_before` BEFORE DELETE ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mandat` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_log_del_after` AFTER DELETE ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mandat_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `mandat_jahr`
--

DROP TABLE IF EXISTS `mandat_jahr`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mandat_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Jahresvergütung von Mandat',
  `mandat_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Mandates',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `verguetung` int(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten des Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Beschreibung der Verfgütung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass das Mandat von der Person autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_jahr_unique` (`mandat_id`,`jahr`) COMMENT 'Fachlicher unique constraint',
  KEY `mandat_id` (`mandat_id`,`jahr`) COMMENT 'Idx mandat_id',
  CONSTRAINT `fk_mandat_id` FOREIGN KEY (`mandat_id`) REFERENCES `mandat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Jahresvergütung durch Mandate';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_jahr_log_ins` AFTER INSERT ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mandat_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_jahr_log_upd` AFTER UPDATE ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mandat_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_jahr_log_del_before` BEFORE DELETE ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mandat_jahr` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mandat_jahr_log_del_after` AFTER DELETE ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mandat_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `mandat_jahr_log`
--

DROP TABLE IF EXISTS `mandat_jahr_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mandat_jahr_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `mandat_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Mandates',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `verguetung` int(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten des Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Beschreibung der Verfgütung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass das Mandat von der Person autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_mandat_jahr_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_mandat_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COMMENT='Jahresvergütung durch Mandate';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mandat_log`
--

DROP TABLE IF EXISTS `mandat_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mandat_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Mandates. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn des Mandates, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende des Mandates, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `organisations_id` (`organisation_id`),
  KEY `zutrittsberechtigung_id` (`person_id`),
  KEY `fk_mandat_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_mandat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30118 DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mil_grad`
--

DROP TABLE IF EXISTS `mil_grad`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mil_grad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Militärischer Grad',
  `name` varchar(30) NOT NULL COMMENT 'Name des militärischen Grades',
  `name_fr` varchar(30) DEFAULT NULL COMMENT 'Französischer Name des militärischen Grades',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Abkürzung des militärischen Grades',
  `abkuerzung_fr` varchar(10) DEFAULT NULL COMMENT 'Französische Abkürzung des militärischen Grades',
  `typ` enum('Mannschaft','Unteroffizier','Hoeherer Unteroffizier','Offizier','Hoeherer Stabsoffizier') NOT NULL COMMENT 'Stufe des militärischen Grades',
  `ranghoehe` int(11) NOT NULL COMMENT 'Ranghöhe des Grades',
  `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`name`),
  UNIQUE KEY `abkuerzung_unique` (`abkuerzung`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='Militärische Grade (Armee XXI)';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mil_grad_log_ins` AFTER INSERT ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mil_grad` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mil_grad_log_upd` AFTER UPDATE ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mil_grad` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mil_grad_log_del_before` BEFORE DELETE ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mil_grad` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_mil_grad_log_del_after` AFTER DELETE ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mil_grad_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `mil_grad_log`
--

DROP TABLE IF EXISTS `mil_grad_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mil_grad_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(30) NOT NULL COMMENT 'Name des militärischen Grades',
  `name_fr` varchar(30) DEFAULT NULL COMMENT 'Französischer Name des militärischen Grades',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Abkürzung des militärischen Grades',
  `abkuerzung_fr` varchar(10) DEFAULT NULL COMMENT 'Französische Abkürzung des militärischen Grades',
  `typ` enum('Mannschaft','Unteroffizier','Hoeherer Unteroffizier','Offizier','Hoeherer Stabsoffizier') NOT NULL COMMENT 'Stufe des militärischen Grades',
  `ranghoehe` int(11) NOT NULL COMMENT 'Ranghöhe des Grades',
  `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_mil_grad_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_mil_grad_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Militärische Grade (Armee XXI)';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_interessenbindung`
--

DROP TABLE IF EXISTS `mv_interessenbindung`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_interessenbindung` (
  `anzeige_name` text,
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Interessenbindung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindung durch einen Import zu letzt aktualisiert wurde. Ein Datum bedeutet, dass die Interessenbindung unter der Kontrolle des Importprozesses.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgeändert am',
  `bis_unix` bigint(11) DEFAULT NULL,
  `von_unix` bigint(11) DEFAULT NULL,
  `created_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `wirksamkeit` varchar(6) NOT NULL DEFAULT '',
  `parlamentarier_im_rat_seit` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
  `wirksamkeit_index` int(1) NOT NULL DEFAULT '0',
  `organisation_lobbyeinfluss` varchar(9) DEFAULT NULL,
  `parlamentarier_lobbyfaktor` bigint(25) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  PRIMARY KEY (`id`),
  KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`,`freigabe_datum`,`bis`,`organisation_id`),
  KEY `idx_parlam_bis` (`parlamentarier_id`,`bis`,`organisation_id`),
  KEY `idx_parlam` (`parlamentarier_id`,`organisation_id`),
  KEY `idx_org_freigabe_bis` (`organisation_id`,`freigabe_datum`,`bis`,`parlamentarier_id`),
  KEY `idx_org_bis` (`organisation_id`,`bis`,`parlamentarier_id`),
  KEY `idx_org` (`organisation_id`,`parlamentarier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_interessenbindung';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_mandat`
--

DROP TABLE IF EXISTS `mv_mandat`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_mandat` (
  `anzeige_name` text,
  `id` int(11) NOT NULL DEFAULT '0',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Mandates. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn des Mandates, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende des Mandates, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgeändert am',
  `bis_unix` bigint(11) DEFAULT NULL,
  `von_unix` bigint(11) DEFAULT NULL,
  `created_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `wirksamkeit` varchar(6) NOT NULL DEFAULT '',
  `wirksamkeit_index` int(1) NOT NULL DEFAULT '0',
  `organisation_lobbyeinfluss` varchar(9) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  PRIMARY KEY (`id`),
  KEY `idx_person_freigabe_bis` (`person_id`,`freigabe_datum`,`bis`,`organisation_id`),
  KEY `idx_person_bis` (`person_id`,`bis`,`organisation_id`),
  KEY `idx_person` (`person_id`,`organisation_id`),
  KEY `idx_org_freigabe_bis` (`organisation_id`,`freigabe_datum`,`bis`,`person_id`),
  KEY `idx_org_bis` (`organisation_id`,`bis`,`person_id`),
  KEY `idx_org` (`organisation_id`,`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_mandat';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_organisation`
--

DROP TABLE IF EXISTS `mv_organisation`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_organisation` (
  `anzeige_name` varchar(454) DEFAULT NULL,
  `anzeige_mixed` varchar(454) DEFAULT NULL,
  `anzeige_bimixed` varchar(302) DEFAULT NULL,
  `searchable_name` text,
  `anzeige_name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `anzeige_name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name` varchar(454) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `uid` varchar(15) DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999',
  `ort` varchar(100) DEFAULT NULL COMMENT 'Ort der Organisation',
  `abkuerzung_de` varchar(20) DEFAULT NULL COMMENT 'Abkürzung der Organisation, kann in der Anzeige dem Namen nachgestellt werden, z.B. Schweizer Kaderorganisation (SKO)',
  `alias_namen_de` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `abkuerzung_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Abkürzung der Organisation',
  `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Französischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `abkuerzung_it` varchar(20) DEFAULT NULL COMMENT 'Italienische Abkürzung der Organisation',
  `alias_namen_it` varchar(255) DEFAULT NULL COMMENT 'Italienischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternativen Namen für die Organisation; bei der Suche wird für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `land_id` int(11) DEFAULT NULL COMMENT 'Land der Organisation',
  `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum der Organisation',
  `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft') DEFAULT NULL COMMENT 'Rechtsform der Organisation',
  `rechtsform_handelsregister` varchar(4) DEFAULT NULL COMMENT 'Code der Rechtsform des Handelsregister, z.B. 0106 für AG. Das Feld kann importiert werden.',
  `rechtsform_zefix` int(11) DEFAULT NULL COMMENT 'Numerischer Rechtsformcode von Zefix, z.B. 3 für AG. Das Feld kann importiert werden.',
  `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert') NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL COMMENT 'Häufigkeit der Teilname an nationalen Vernehmlassungen',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. Hauptinteressengruppe. Über die Interessengruppe wird eine Branche zugeordnet.',
  `interessengruppe2_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 2. Interessengruppe der Organisation.',
  `interessengruppe3_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 3. Interessengruppe der Organisation.',
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche.',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Link zur Webseite',
  `handelsregister_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Eintrag im Handelsregister',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Organisation, Zweck gemäss Handelsregister oder  Statuten',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung',
  `sekretariat` varchar(500) DEFAULT NULL COMMENT 'Für parlamentarische Gruppen: Ansprechsperson, Adresse, Telephonnummer, usw. des Sekretariats der parlamentarischen Gruppen (wird importiert)',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(150) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Organisation durch einen Import zu letzt aktualisiert wurde. Ein Datum bedeutet, dass die Organisation unter der Kontrolle des Importprozesses.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgeändert am',
  `created_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `branche` varchar(100) DEFAULT NULL,
  `branche_de` varchar(100) DEFAULT NULL,
  `branche_fr` varchar(100) DEFAULT NULL,
  `interessengruppe` varchar(150) DEFAULT NULL,
  `interessengruppe_de` varchar(150) DEFAULT NULL,
  `interessengruppe_fr` varchar(150) DEFAULT NULL,
  `interessengruppe_branche` varchar(100) DEFAULT NULL,
  `interessengruppe_branche_de` varchar(100) DEFAULT NULL,
  `interessengruppe_branche_fr` varchar(100) DEFAULT NULL,
  `interessengruppe_branche_id` int(11) COMMENT 'Fremdschlüssel Branche',
  `interessengruppe2` varchar(150) DEFAULT NULL,
  `interessengruppe2_de` varchar(150) DEFAULT NULL,
  `interessengruppe2_fr` varchar(150) DEFAULT NULL,
  `interessengruppe2_branche` varchar(100) DEFAULT NULL,
  `interessengruppe2_branche_de` varchar(100) DEFAULT NULL,
  `interessengruppe2_branche_fr` varchar(100) DEFAULT NULL,
  `interessengruppe2_branche_id` int(11) COMMENT 'Fremdschlüssel Branche',
  `interessengruppe3` varchar(150) DEFAULT NULL,
  `interessengruppe3_de` varchar(150) DEFAULT NULL,
  `interessengruppe3_fr` varchar(150) DEFAULT NULL,
  `interessengruppe3_branche` varchar(100) DEFAULT NULL,
  `interessengruppe3_branche_de` varchar(100) DEFAULT NULL,
  `interessengruppe3_branche_fr` varchar(100) DEFAULT NULL,
  `interessengruppe3_branche_id` int(11) COMMENT 'Fremdschlüssel Branche',
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  `land` varchar(200) COMMENT 'Name des Landes auf Deutsch',
  `interessenraum` varchar(50) COMMENT 'Name des Interessenbereiches',
  `interessenraum_de` varchar(50) COMMENT 'Name des Interessenbereiches',
  `interessenraum_fr` varchar(50) DEFAULT NULL COMMENT 'Französischer Name des Interessenbereiches',
  `organisation_jahr_id` int(11) DEFAULT NULL COMMENT 'Technischer Schlüssel der Jahreswerte einer Organisation',
  `jahr` smallint(6) unsigned COMMENT 'Jahr auf welche sich die Werte beziehen',
  `umsatz` bigint(20) DEFAULT NULL COMMENT 'Umsatz der Organisation in Franken',
  `gewinn` bigint(20) DEFAULT NULL COMMENT 'Gewinn der Organisation in Franken',
  `kapital` bigint(20) unsigned DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken',
  `mitarbeiter_weltweit` int(11) unsigned DEFAULT NULL COMMENT 'Anzahl Mitarbeiter weltweit',
  `mitarbeiter_schweiz` int(11) unsigned DEFAULT NULL COMMENT 'Anzahl Mitarbeiter in der Schweiz',
  `geschaeftsbericht_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Geschäftsbericht',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle',
  `anzahl_interessenbindung_tief` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_mittel` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_hoch` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_tief_nach_wahl` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_mittel_nach_wahl` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_hoch_nach_wahl` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_tief` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_mittel` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_hoch` tinyint(3) unsigned DEFAULT NULL,
  `lobbyeinfluss` varchar(9) DEFAULT NULL,
  `lobbyeinfluss_index` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_freigabe` (`freigabe_datum`),
  KEY `idx_branche_freigabe` (`branche_id`,`freigabe_datum`),
  KEY `idx_interessengruppe_freigabe` (`interessengruppe_id`,`freigabe_datum`),
  KEY `idx_interessengruppe2_freigabe` (`interessengruppe2_id`,`freigabe_datum`),
  KEY `idx_interessengruppe3_freigabe` (`interessengruppe3_id`,`freigabe_datum`),
  KEY `idx_interessengruppe_branche_freigabe` (`interessengruppe_branche_id`,`freigabe_datum`),
  KEY `idx_interessengruppe2_branche_freigabe` (`interessengruppe2_branche_id`,`freigabe_datum`),
  KEY `idx_interessengruppe3_branche_freigabe` (`interessengruppe3_branche_id`,`freigabe_datum`),
  KEY `land` (`land_id`,`freigabe_datum`),
  KEY `interessenraum_id` (`interessenraum_id`,`freigabe_datum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_organisation';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_parlamentarier`
--

DROP TABLE IF EXISTS `mv_parlamentarier`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_parlamentarier` (
  `anzeige_name` varchar(152) DEFAULT NULL,
  `anzeige_name_de` varchar(152) DEFAULT NULL,
  `anzeige_name_fr` varchar(152) DEFAULT NULL,
  `name` varchar(202) DEFAULT NULL,
  `name_de` varchar(202) DEFAULT NULL,
  `name_fr` varchar(202) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel des Parlamentariers',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des Parlamentariers',
  `vorname` varchar(50) NOT NULL COMMENT 'Vornahme des Parlamentariers',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname des Parlamentariers',
  `rat_id` int(11) NOT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates',
  `kanton_id` int(11) NOT NULL COMMENT 'Kantonszugehörigkeit; Fremdschlüssel des Kantons',
  `kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Leer bedeutet parteilos.',
  `parteifunktion` enum('mitglied','praesident','vizepraesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit im nationalen Parlament. Fremdschlüssel.',
  `fraktionsfunktion` enum('mitglied','praesident','vizepraesident') DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Fraktion',
  `im_rat_seit` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
  `im_rat_bis` date DEFAULT NULL COMMENT 'Austrittsdatum aus dem Parlament. Leer (NULL) = aktuell im Rat, nicht leer = historischer Eintrag',
  `ratswechsel` date DEFAULT NULL COMMENT 'Datum in welchem der Parlamentarier den Rat wechselte, in der Regel vom National- in den Ständerat. Leer (NULL) = kein Ratswechsel hat stattgefunden',
  `ratsunterbruch_von` date DEFAULT NULL COMMENT 'Unterbruch in der Ratstätigkeit von, leer (NULL) = kein Unterbruch',
  `ratsunterbruch_bis` date DEFAULT NULL COMMENT 'Unterbruch in der Ratstätigkeit bis, leer (NULL) = kein Unterbruch',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers auf französisch',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `titel` varchar(100) DEFAULT NULL COMMENT 'Titel des Parlamentariers, wird von ws.parlament.ch importiert',
  `aemter` text COMMENT 'Politische Ämter (importiert von ws.parlament.ch mandate)',
  `weitere_aemter` text COMMENT 'Zusätzliche Ämter (importiert von ws.parlament.ch additionalMandate)',
  `zivilstand` enum('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt','verwitwet') DEFAULT NULL COMMENT 'Zivilstand',
  `anzahl_kinder` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl der Kinder',
  `militaerischer_grad_id` int(11) DEFAULT NULL COMMENT 'Militärischer Grad, leer (NULL) = kein Militärdienst',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `geburtstag` date DEFAULT NULL COMMENT 'Geburtstag des Parlamentariers',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Photo des Parlamentariers (JPEG/jpg)',
  `photo_dateiname` varchar(255) DEFAULT NULL COMMENT 'Photodateiname ohne Erweiterung',
  `photo_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Photodatei',
  `photo_dateiname_voll` varchar(255) DEFAULT NULL COMMENT 'Photodateiname mit Erweiterung',
  `photo_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Photos',
  `kleinbild` varchar(80) DEFAULT 'leer.png' COMMENT 'Bild 44x62 px oder leer.png',
  `sitzplatz` int(11) DEFAULT NULL COMMENT 'Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Parlamentariers',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage des Parlamentariers',
  `homepage_2` varchar(255) DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch',
  `parlament_biografie_id` int(11) DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.',
  `parlament_number` int(11) DEFAULT NULL COMMENT 'Number Feld auf ws.parlament.ch, wird von ws.parlament.ch importiert, wird z.B. als ID für Photos verwendet.',
  `parlament_interessenbindungen` text COMMENT 'Importierte Interessenbindungen von ws.parlament.ch',
  `parlament_interessenbindungen_updated` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindungen von ws.parlament.ch zu letzt aktualisiert wurden.',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `wikipedia` varchar(255) DEFAULT NULL COMMENT 'Link zum Wkipedia-Eintrag des Parlamentariers',
  `sprache` enum('de','fr','it','sk','rm','tr') DEFAULT NULL COMMENT 'Sprache des Parlamentariers, wird von ws.parlament.ch importiert',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch',
  `adresse_firma` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_ort` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') DEFAULT NULL COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen und Zutrittsberechtigungen vom Parlamentarier autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgeändert am',
  `beruf_de` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `von` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
  `bis` date DEFAULT NULL COMMENT 'Austrittsdatum aus dem Parlament. Leer (NULL) = aktuell im Rat, nicht leer = historischer Eintrag',
  `geburtstag_unix` bigint(11) DEFAULT NULL,
  `im_rat_seit_unix` bigint(11) NOT NULL DEFAULT '0',
  `im_rat_bis_unix` bigint(11) DEFAULT NULL,
  `created_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `von_unix` bigint(11) NOT NULL DEFAULT '0',
  `bis_unix` bigint(11) DEFAULT NULL,
  `vertretene_bevoelkerung` bigint(13) unsigned DEFAULT NULL,
  `rat` varchar(10) COMMENT 'Kürzel des Rates',
  `kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH') COMMENT 'Kantonskürzel',
  `rat_de` varchar(10) COMMENT 'Kürzel des Rates',
  `kanton_name_de` varchar(50) COMMENT 'Deutscher Name des Kantons',
  `rat_fr` varchar(10) COMMENT 'Französische Abkürzung',
  `kanton_name_fr` varchar(50) COMMENT 'Französischer Name',
  `kommissionen_namen` text,
  `kommissionen_namen_de` text,
  `kommissionen_namen_fr` text,
  `kommissionen_abkuerzung` text,
  `kommissionen_abkuerzung_de` text,
  `kommissionen_abkuerzung_fr` text,
  `kommissionen_anzahl` bigint(21) NOT NULL DEFAULT '0',
  `partei` varchar(20) COMMENT 'Parteiabkürzung',
  `partei_name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `fraktion` varchar(20) COMMENT 'Fraktionsabkürzung',
  `militaerischer_grad` varchar(30) COMMENT 'Name des militärischen Grades',
  `partei_de` varchar(20) COMMENT 'Parteiabkürzung',
  `partei_name_de` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `militaerischer_grad_de` varchar(30) COMMENT 'Name des militärischen Grades',
  `partei_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Parteiabkürzung',
  `partei_name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Partei',
  `militaerischer_grad_fr` varchar(30) DEFAULT NULL COMMENT 'Französischer Name des militärischen Grades',
  `beruf_branche_id` int(11) COMMENT 'Fremdschlüssel Branche',
  `titel_de` varchar(100) DEFAULT NULL,
  `titel_fr` varchar(100) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  `anzahl_interessenbindung_tief` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_mittel` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_hoch` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_tief_nach_wahl` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_mittel_nach_wahl` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_hoch_nach_wahl` tinyint(3) unsigned DEFAULT NULL,
  `lobbyfaktor` smallint(5) unsigned DEFAULT NULL,
  `lobbyfaktor_max` smallint(5) unsigned DEFAULT NULL,
  `lobbyfaktor_percent_max` decimal(4,3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_tief_max` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_mittel_max` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_interessenbindung_hoch_max` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_freigabe_bis` (`freigabe_datum`,`im_rat_bis`),
  KEY `idx_bis` (`im_rat_bis`),
  KEY `idx_rat_freigabe_bis` (`rat`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_rat_bis` (`rat`,`im_rat_bis`),
  KEY `idx_rat_id_freigabe_bis` (`rat_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_rat_id_bis` (`rat_id`,`im_rat_bis`),
  KEY `idx_kanton_freigabe_bis` (`kanton`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_kanton_bis` (`kanton`,`im_rat_bis`),
  KEY `idx_kanton_partei_freigabe_bis` (`kanton`,`partei`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_kanton_partei_bis` (`kanton`,`partei`,`im_rat_bis`),
  KEY `idx_kanton_id_freigabe_bis` (`kanton_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_kanton_id_bis` (`kanton_id`,`im_rat_bis`),
  KEY `idx_partei_freigabe_bis` (`partei`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_partei_bis` (`partei`,`im_rat_bis`),
  KEY `idx_partei_id_freigabe_bis` (`partei_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `idx_partei_id_bis` (`partei_id`,`im_rat_bis`),
  KEY `beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `beruf_interessengruppe_id` (`beruf_interessengruppe_id`,`im_rat_bis`),
  KEY `beruf_branche_id_freigabe` (`beruf_branche_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `beruf_branche_id` (`beruf_branche_id`,`im_rat_bis`),
  KEY `militaerischer_grad_freigabe` (`militaerischer_grad_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `militaerischer_grad` (`militaerischer_grad_id`,`im_rat_bis`),
  KEY `fraktion_freigabe_bis` (`fraktion`,`im_rat_bis`),
  KEY `fraktion_bis` (`fraktion`,`freigabe_datum`,`im_rat_bis`),
  KEY `fraktion_id_freigabe_bis` (`fraktion_id`,`freigabe_datum`,`im_rat_bis`),
  KEY `fraktion_id_bis` (`fraktion_id`,`im_rat_bis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_parlamentarier';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_parlamentarier_lobbyfaktor`
--

DROP TABLE IF EXISTS `mv_parlamentarier_lobbyfaktor`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_parlamentarier_lobbyfaktor` (
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel des Parlamentariers',
  `anzahl_interessenbindung_tief` bigint(21) NOT NULL DEFAULT '0',
  `anzahl_interessenbindung_mittel` bigint(21) NOT NULL DEFAULT '0',
  `anzahl_interessenbindung_hoch` bigint(21) NOT NULL DEFAULT '0',
  `anzahl_interessenbindung_tief_nach_wahl` bigint(21) NOT NULL DEFAULT '0',
  `anzahl_interessenbindung_mittel_nach_wahl` bigint(21) NOT NULL DEFAULT '0',
  `anzahl_interessenbindung_hoch_nach_wahl` bigint(21) NOT NULL DEFAULT '0',
  `lobbyfaktor` bigint(25) NOT NULL DEFAULT '0',
  `lobbyfaktor_einfach` bigint(24) NOT NULL DEFAULT '0',
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_parlamentarier_lobbyfaktor';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_search_table`
--

DROP TABLE IF EXISTS `mv_search_table`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_search_table` (
  `id` int(11) NOT NULL DEFAULT '0',
  `table_name` varchar(20) NOT NULL DEFAULT '',
  `page` varchar(20) NOT NULL DEFAULT '',
  `table_weight` bigint(20) NOT NULL DEFAULT '0',
  `name_de` varchar(193) DEFAULT NULL,
  `name_fr` varchar(193) DEFAULT NULL,
  `search_keywords_de` varchar(512) DEFAULT NULL,
  `search_keywords_fr` text,
  `freigabe_datum` timestamp NULL DEFAULT NULL,
  `bis` date DEFAULT NULL,
  `weight` bigint(20) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  PRIMARY KEY (`id`,`table_name`),
  KEY `idx_search_str_de_long` (`freigabe_datum`,`bis`,`table_weight`,`weight`,`search_keywords_de`(200)),
  KEY `idx_search_str_de_medium` (`freigabe_datum`,`table_weight`,`weight`,`search_keywords_de`(200)),
  KEY `idx_search_str_de_short` (`table_weight`,`weight`,`search_keywords_de`(200)),
  KEY `idx_search_str_fr_long` (`freigabe_datum`,`bis`,`table_weight`,`weight`,`search_keywords_fr`(200)),
  KEY `idx_search_str_fr_medium` (`freigabe_datum`,`table_weight`,`weight`,`search_keywords_fr`(200)),
  KEY `idx_search_str_fr_short` (`table_weight`,`weight`,`search_keywords_fr`(200))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for parlamentarier, zutrittsberechtigung, branche, interessengruppe, kommission, organisation, partei';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `mv_zutrittsberechtigung`
--

DROP TABLE IF EXISTS `mv_zutrittsberechtigung`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `mv_zutrittsberechtigung` (
  `anzeige_name` varchar(152) DEFAULT NULL,
  `anzeige_name_de` varchar(152) DEFAULT NULL,
  `anzeige_name_fr` varchar(152) DEFAULT NULL,
  `name` varchar(151) DEFAULT NULL,
  `name_de` varchar(151) DEFAULT NULL,
  `name_fr` varchar(151) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `beschreibung_de` text COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `parlamentarier_kommissionen_zutrittsberechtigung` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf der Person',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Beruf der Person',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Parteimitgliedschaft der zutrittsberechtigten Person.',
  `geschlecht` enum('M','F') DEFAULT NULL COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Zutrittsberechtigten',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der zutrittsberechtigen Person',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der zutrittsberechtigen Person',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') DEFAULT NULL COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa_person` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum_person` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa_person` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum_person` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa_person` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum_person` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa_person` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date_person` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Erstellt am',
  `updated_visa_person` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date_person` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgeändert am',
  `created_date_unix_person` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix_person` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix_person` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix_person` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix_person` bigint(11) DEFAULT NULL,
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur zutrittsberechtigten Person',
  `zutrittsberechtigung_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Zutrittsberechtigung',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `funktion_fr` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person auf französisch.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zutrittsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zutrittsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgeändert am',
  `bis_unix` bigint(11) DEFAULT NULL,
  `von_unix` bigint(11) DEFAULT NULL,
  `created_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `beruf_branche_id` int(11) COMMENT 'Fremdschlüssel Branche',
  `partei` varchar(20) COMMENT 'Parteiabkürzung',
  `partei_de` varchar(20) COMMENT 'Parteiabkürzung',
  `partei_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Parteiabkürzung',
  `parlamentarier_name` varchar(152) DEFAULT NULL,
  `parlamentarier_freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `parlamentarier_freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `anzahl_mandat_tief` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_mittel` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_hoch` tinyint(3) unsigned DEFAULT NULL,
  `lobbyfaktor` smallint(5) unsigned DEFAULT NULL,
  `lobbyfaktor_max` smallint(5) unsigned DEFAULT NULL,
  `lobbyfaktor_percent_max` decimal(4,3) unsigned DEFAULT NULL,
  `anzahl_mandat_tief_max` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_mittel_max` tinyint(3) unsigned DEFAULT NULL,
  `anzahl_mandat_hoch_max` tinyint(3) unsigned DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am',
  PRIMARY KEY (`zutrittsberechtigung_id`),
  KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`,`freigabe_datum`,`bis`),
  KEY `idx_parlam_bis` (`parlamentarier_id`,`bis`),
  KEY `idx_partei_freigabe` (`partei`,`freigabe_datum`,`bis`),
  KEY `idx_partei` (`partei`,`bis`),
  KEY `idx_partei_id_freigabe` (`partei_id`,`freigabe_datum`,`bis`),
  KEY `idx_partei_id` (`partei_id`,`bis`),
  KEY `idx_beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`,`freigabe_datum`,`bis`),
  KEY `idx_beruf_interessengruppe_id` (`beruf_interessengruppe_id`,`bis`),
  KEY `idx_beruf_branche_id_freigabe` (`beruf_branche_id`,`freigabe_datum`,`bis`),
  KEY `idx_beruf_branche_id` (`beruf_branche_id`,`bis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_zutrittsberechtigung';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `organisation`
--

DROP TABLE IF EXISTS `organisation`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `uid` varchar(15) DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999',
  `ort` varchar(100) DEFAULT NULL COMMENT 'Ort der Organisation',
  `abkuerzung_de` varchar(20) DEFAULT NULL COMMENT 'Abkürzung der Organisation, kann in der Anzeige dem Namen nachgestellt werden, z.B. Schweizer Kaderorganisation (SKO)',
  `alias_namen_de` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `abkuerzung_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Abkürzung der Organisation',
  `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Französischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `abkuerzung_it` varchar(20) DEFAULT NULL COMMENT 'Italienische Abkürzung der Organisation',
  `alias_namen_it` varchar(255) DEFAULT NULL COMMENT 'Italienischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternativen Namen für die Organisation; bei der Suche wird für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `land_id` int(11) DEFAULT NULL COMMENT 'Land der Organisation',
  `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum der Organisation',
  `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft') DEFAULT NULL COMMENT 'Rechtsform der Organisation',
  `rechtsform_handelsregister` varchar(4) DEFAULT NULL COMMENT 'Code der Rechtsform des Handelsregister, z.B. 0106 für AG. Das Feld kann importiert werden.',
  `rechtsform_zefix` int(11) DEFAULT NULL COMMENT 'Numerischer Rechtsformcode von Zefix, z.B. 3 für AG. Das Feld kann importiert werden.',
  `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert') NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL COMMENT 'Häufigkeit der Teilname an nationalen Vernehmlassungen',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. Hauptinteressengruppe. Über die Interessengruppe wird eine Branche zugeordnet.',
  `interessengruppe2_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 2. Interessengruppe der Organisation.',
  `interessengruppe3_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 3. Interessengruppe der Organisation.',
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche.',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Link zur Webseite',
  `handelsregister_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Eintrag im Handelsregister',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Organisation, Zweck gemäss Handelsregister oder  Statuten',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung',
  `sekretariat` varchar(500) DEFAULT NULL COMMENT 'Für parlamentarische Gruppen: Ansprechsperson, Adresse, Telephonnummer, usw. des Sekretariats der parlamentarischen Gruppen (wird importiert)',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(150) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Organisation durch einen Import zu letzt aktualisiert wurde. Ein Datum bedeutet, dass die Organisation unter der Kontrolle des Importprozesses.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organisation_name_de_unique` (`name_de`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `organisation_name_fr_unique` (`name_fr`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `organisation_name_it_unique` (`name_it`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `uid_unique` (`uid`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `interessengruppe2_id` (`interessengruppe2_id`),
  KEY `interessengruppe3_id` (`interessengruppe3_id`),
  KEY `land` (`land_id`),
  KEY `interessenraum_id` (`interessenraum_id`),
  CONSTRAINT `fk_lo_lg` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  CONSTRAINT `fk_org_country` FOREIGN KEY (`land_id`) REFERENCES `country` (`id`),
  CONSTRAINT `fk_org_interessenraum` FOREIGN KEY (`interessenraum_id`) REFERENCES `interessenraum` (`id`),
  CONSTRAINT `fk_organisation_interessengruppe2_id` FOREIGN KEY (`interessengruppe2_id`) REFERENCES `interessengruppe` (`id`),
  CONSTRAINT `fk_organisation_interessengruppe3_id` FOREIGN KEY (`interessengruppe3_id`) REFERENCES `interessengruppe` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6434 DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_name_ins_before` BEFORE INSERT ON organisation
FOR EACH ROW
thisTrigger: BEGIN
    DECLARE msg VARCHAR(255);
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
    IF NEW.name_de IS NULL AND NEW.name_fr IS NULL AND NEW.name_it IS NULL THEN
        SET msg = CONCAT('NameError: Either name_de, name_fr or name_it must be set. ID: ', CAST(NEW.id as CHAR));
        SIGNAL SQLSTATE '45000' SET message_text = msg;
    END IF;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_log_ins` AFTER INSERT ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_name_upd_before` BEFORE UPDATE ON organisation
FOR EACH ROW
thisTrigger: BEGIN
    DECLARE msg VARCHAR(255);
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
    IF NEW.name_de IS NULL AND NEW.name_fr IS NULL AND NEW.name_it IS NULL THEN
        SET msg = CONCAT('NameError: Either name_de, name_fr or name_it must be set. ID: ', CAST(NEW.id as CHAR));
        SIGNAL SQLSTATE '45000' SET message_text = msg;
    END IF;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_log_upd` AFTER UPDATE ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_log_del_before` BEFORE DELETE ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_log_del_after` AFTER DELETE ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `organisation_anhang`
--

DROP TABLE IF EXISTS `organisation_anhang`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_anhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Organisationsanhangs',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Organisation',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(50) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `organisation_id` (`organisation_id`),
  CONSTRAINT `fk_org_anhang` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_anhang_log_ins` AFTER INSERT ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_anhang` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_anhang_log_upd` AFTER UPDATE ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_anhang` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_anhang_log_del_before` BEFORE DELETE ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_anhang` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_anhang_log_del_after` AFTER DELETE ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `organisation_anhang_log`
--

DROP TABLE IF EXISTS `organisation_anhang_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_anhang_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Organisation',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(50) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `fk_organisation_anhang_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_organisation_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `organisation_beziehung`
--

DROP TABLE IF EXISTS `organisation_beziehung`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_beziehung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Organisationsbeziehung',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn der Organisationsbeziehung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Organisationsbeziehung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organisation_beziehung_organisation_zielorganisation_art_unique` (`art`,`organisation_id`,`ziel_organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_org_freigabe` (`organisation_id`,`freigabe_datum`,`bis`,`ziel_organisation_id`),
  KEY `idx_org` (`organisation_id`,`bis`,`ziel_organisation_id`),
  KEY `idx_ziel_freigabe` (`ziel_organisation_id`,`freigabe_datum`,`bis`,`organisation_id`),
  KEY `idx_ziel` (`ziel_organisation_id`,`bis`,`organisation_id`),
  KEY `organisation_id` (`organisation_id`,`ziel_organisation_id`),
  KEY `ziel_organisation_id` (`ziel_organisation_id`,`organisation_id`),
  CONSTRAINT `fk_quell_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  CONSTRAINT `fk_ziel_organisation_id` FOREIGN KEY (`ziel_organisation_id`) REFERENCES `organisation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3392 DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_beziehung_log_ins` AFTER INSERT ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_beziehung` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_beziehung_log_upd` AFTER UPDATE ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_beziehung` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_beziehung_log_del_before` BEFORE DELETE ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_beziehung` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_beziehung_log_del_after` AFTER DELETE ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_beziehung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `organisation_beziehung_log`
--

DROP TABLE IF EXISTS `organisation_beziehung_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_beziehung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `beschreibung_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `von` date DEFAULT NULL COMMENT 'Beginn der Organisationsbeziehung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Organisationsbeziehung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `ziel_organisation_id` (`ziel_organisation_id`),
  KEY `fk_organisation_beziehung_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_organisation_beziehung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15282 DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `organisation_jahr`
--

DROP TABLE IF EXISTS `organisation_jahr`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte einer Organisation',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Kantons',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `umsatz` bigint(20) DEFAULT NULL COMMENT 'Umsatz der Organisation in Franken',
  `gewinn` bigint(20) DEFAULT NULL COMMENT 'Gewinn der Organisation in Franken',
  `kapital` bigint(20) unsigned DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken',
  `mitarbeiter_weltweit` int(11) unsigned DEFAULT NULL COMMENT 'Anzahl Mitarbeiter weltweit',
  `mitarbeiter_schweiz` int(11) unsigned DEFAULT NULL COMMENT 'Anzahl Mitarbeiter in der Schweiz',
  `geschaeftsbericht_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Geschäftsbericht',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_organisation_jahr_unique` (`organisation_id`,`jahr`) COMMENT 'Fachlicher unique constraint',
  KEY `organisation_id` (`organisation_id`),
  CONSTRAINT `fk_organisation_jahr_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_jahr_log_ins` AFTER INSERT ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_jahr_log_upd` AFTER UPDATE ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_jahr` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_jahr_log_del_before` BEFORE DELETE ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_jahr` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_organisation_jahr_log_del_after` AFTER DELETE ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `organisation_jahr_log`
--

DROP TABLE IF EXISTS `organisation_jahr_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_jahr_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Kantons',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `umsatz` bigint(20) DEFAULT NULL COMMENT 'Umsatz der Organisation in Franken',
  `gewinn` bigint(20) DEFAULT NULL COMMENT 'Gewinn der Organisation in Franken',
  `kapital` bigint(20) unsigned DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken',
  `mitarbeiter_weltweit` int(11) unsigned DEFAULT NULL COMMENT 'Anzahl Mitarbeiter weltweit',
  `mitarbeiter_schweiz` int(11) unsigned DEFAULT NULL COMMENT 'Anzahl Mitarbeiter in der Schweiz',
  `geschaeftsbericht_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Geschäftsbericht',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `fk_organisation_jahr_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_organisation_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `organisation_log`
--

DROP TABLE IF EXISTS `organisation_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `organisation_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `uid` varchar(15) DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999',
  `ort` varchar(100) DEFAULT NULL COMMENT 'Ort der Organisation',
  `abkuerzung_de` varchar(20) DEFAULT NULL COMMENT 'Abkürzung der Organisation, kann in der Anzeige dem Namen nachgestellt werden, z.B. Schweizer Kaderorganisation (SKO)',
  `alias_namen_de` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `abkuerzung_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Abkürzung der Organisation',
  `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Französischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `abkuerzung_it` varchar(20) DEFAULT NULL COMMENT 'Italienische Abkürzung der Organisation',
  `alias_namen_it` varchar(255) DEFAULT NULL COMMENT 'Italienischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternativen Namen für die Organisation; bei der Suche wird für ein einfacheres Finden auch in den Alias-Namen gesucht.',
  `land_id` int(11) DEFAULT NULL COMMENT 'Land der Organisation',
  `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum der Organisation',
  `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft') DEFAULT NULL COMMENT 'Rechtsform der Organisation',
  `rechtsform_handelsregister` varchar(4) DEFAULT NULL COMMENT 'Code der Rechtsform des Handelsregister, z.B. 0106 für AG. Das Feld kann importiert werden.',
  `rechtsform_zefix` int(11) DEFAULT NULL COMMENT 'Numerischer Rechtsformcode von Zefix, z.B. 3 für AG. Das Feld kann importiert werden.',
  `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert') NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL COMMENT 'Häufigkeit der Teilname an nationalen Vernehmlassungen',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. Hauptinteressengruppe. Über die Interessengruppe wird eine Branche zugeordnet.',
  `interessengruppe2_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 2. Interessengruppe der Organisation.',
  `interessengruppe3_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 3. Interessengruppe der Organisation.',
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche.',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Link zur Webseite',
  `handelsregister_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Eintrag im Handelsregister',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Organisation, Zweck gemäss Handelsregister oder  Statuten',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung',
  `sekretariat` varchar(500) DEFAULT NULL COMMENT 'Für parlamentarische Gruppen: Ansprechsperson, Adresse, Telephonnummer, usw. des Sekretariats der parlamentarischen Gruppen (wird importiert)',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(150) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Organisation durch einen Import zu letzt aktualisiert wurde. Ein Datum bedeutet, dass die Organisation unter der Kontrolle des Importprozesses.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `interessengruppe2_id` (`interessengruppe2_id`),
  KEY `interessengruppe3_id` (`interessengruppe3_id`),
  KEY `fk_organisation_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_organisation_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72070 DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `parlamentarier`
--

DROP TABLE IF EXISTS `parlamentarier`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `parlamentarier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentariers',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des Parlamentariers',
  `vorname` varchar(50) NOT NULL COMMENT 'Vornahme des Parlamentariers',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname des Parlamentariers',
  `rat_id` int(11) NOT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates',
  `kanton_id` int(11) NOT NULL COMMENT 'Kantonszugehörigkeit; Fremdschlüssel des Kantons',
  `kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Leer bedeutet parteilos.',
  `parteifunktion` enum('mitglied','praesident','vizepraesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit im nationalen Parlament. Fremdschlüssel.',
  `fraktionsfunktion` enum('mitglied','praesident','vizepraesident') DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Fraktion',
  `im_rat_seit` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
  `im_rat_bis` date DEFAULT NULL COMMENT 'Austrittsdatum aus dem Parlament. Leer (NULL) = aktuell im Rat, nicht leer = historischer Eintrag',
  `ratswechsel` date DEFAULT NULL COMMENT 'Datum in welchem der Parlamentarier den Rat wechselte, in der Regel vom National- in den Ständerat. Leer (NULL) = kein Ratswechsel hat stattgefunden',
  `ratsunterbruch_von` date DEFAULT NULL COMMENT 'Unterbruch in der Ratstätigkeit von, leer (NULL) = kein Unterbruch',
  `ratsunterbruch_bis` date DEFAULT NULL COMMENT 'Unterbruch in der Ratstätigkeit bis, leer (NULL) = kein Unterbruch',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers auf französisch',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `titel` varchar(100) DEFAULT NULL COMMENT 'Titel des Parlamentariers, wird von ws.parlament.ch importiert',
  `aemter` text COMMENT 'Politische Ämter (importiert von ws.parlament.ch mandate)',
  `weitere_aemter` text COMMENT 'Zusätzliche Ämter (importiert von ws.parlament.ch additionalMandate)',
  `zivilstand` enum('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt','verwitwet') DEFAULT NULL COMMENT 'Zivilstand',
  `anzahl_kinder` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl der Kinder',
  `militaerischer_grad_id` int(11) DEFAULT NULL COMMENT 'Militärischer Grad, leer (NULL) = kein Militärdienst',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `geburtstag` date DEFAULT NULL COMMENT 'Geburtstag des Parlamentariers',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Photo des Parlamentariers (JPEG/jpg)',
  `photo_dateiname` varchar(255) DEFAULT NULL COMMENT 'Photodateiname ohne Erweiterung',
  `photo_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Photodatei',
  `photo_dateiname_voll` varchar(255) DEFAULT NULL COMMENT 'Photodateiname mit Erweiterung',
  `photo_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Photos',
  `kleinbild` varchar(80) DEFAULT 'leer.png' COMMENT 'Bild 44x62 px oder leer.png',
  `sitzplatz` int(11) DEFAULT NULL COMMENT 'Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Parlamentariers',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage des Parlamentariers',
  `homepage_2` varchar(255) DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch',
  `parlament_biografie_id` int(11) DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.',
  `parlament_number` int(11) DEFAULT NULL COMMENT 'Number Feld auf ws.parlament.ch, wird von ws.parlament.ch importiert, wird z.B. als ID für Photos verwendet.',
  `parlament_interessenbindungen` text COMMENT 'Importierte Interessenbindungen von ws.parlament.ch',
  `parlament_interessenbindungen_updated` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindungen von ws.parlament.ch zu letzt aktualisiert wurden.',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `wikipedia` varchar(255) DEFAULT NULL COMMENT 'Link zum Wkipedia-Eintrag des Parlamentariers',
  `sprache` enum('de','fr','it','sk','rm','tr') DEFAULT NULL COMMENT 'Sprache des Parlamentariers, wird von ws.parlament.ch importiert',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch',
  `adresse_firma` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_ort` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') DEFAULT NULL COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen und Zutrittsberechtigungen vom Parlamentarier autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parlamentarier_rat_sitzplatz` (`rat_id`,`sitzplatz`,`im_rat_bis`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `parlamentarier_nachname_vorname_unique` (`nachname`,`vorname`,`zweiter_vorname`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `parlament_biografie_id_unique` (`parlament_biografie_id`),
  KEY `idx_partei` (`partei_id`),
  KEY `beruf_branche_id` (`beruf_interessengruppe_id`),
  KEY `militaerischer_grad` (`militaerischer_grad_id`),
  KEY `fraktion_id` (`fraktion_id`),
  KEY `rat_id` (`rat_id`),
  KEY `kanton_id` (`kanton_id`),
  CONSTRAINT `fk_beruf_interessengruppe_id` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  CONSTRAINT `fk_kanton_id` FOREIGN KEY (`kanton_id`) REFERENCES `kanton` (`id`),
  CONSTRAINT `fk_mil_grad` FOREIGN KEY (`militaerischer_grad_id`) REFERENCES `mil_grad` (`id`),
  CONSTRAINT `fk_parlamentarier_fraktion_id` FOREIGN KEY (`fraktion_id`) REFERENCES `fraktion` (`id`),
  CONSTRAINT `fk_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`),
  CONSTRAINT `fk_rat_id` FOREIGN KEY (`rat_id`) REFERENCES `rat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=346 DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_log_ins` AFTER INSERT ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `parlamentarier` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_log_upd` AFTER UPDATE ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `interessenbindung`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        parlamentarier_id = NEW.id AND bis IS NULL;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      
      UPDATE `interessenbindung`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        parlamentarier_id = NEW.id AND bis IS NULL;

      
      











      

      
      










  END IF;

    
  IF OLD.im_rat_bis <> NEW.im_rat_bis
    OR ( NEW.im_rat_bis IS NOT NULL)
    OR (OLD.im_rat_bis IS NOT NULL AND NEW.im_rat_bis IS NULL) THEN

    
    UPDATE `zutrittsberechtigung`
      SET
      `notizen` = CONCAT_WS('\n\n', CONCAT(DATE_FORMAT(NEW.updated_date,'%d.%m.%Y'), '/', IFNULL(NEW.updated_visa, '?') , '*: Parlamentarier ', NEW.vorname, ' ', NEW.nachname, ' nicht mehr im Rat. Zutrittsberechtigung erloschen. Bis-Datum von ', IFNULL(DATE_FORMAT(bis,'%d.%m.%Y'), 'NULL'), ' auf ',  IFNULL(DATE_FORMAT(NEW.im_rat_bis,'%d.%m.%Y'), 'NULL'), ' gesetzt.'),`notizen`),
      bis = NEW.im_rat_bis,
      updated_date = NEW.updated_date,
      updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
      parlamentarier_id = NEW.id AND (bis IS NULL OR bis = OLD.im_rat_bis) AND (bis <> NEW.im_rat_bis OR (bis IS NULL AND NEW.im_rat_bis IS NOT NULL) OR (bis IS NOT NULL AND NEW.im_rat_bis IS NULL));

  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'update', null, NOW(), null FROM `parlamentarier` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_log_del_before` BEFORE DELETE ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `parlamentarier` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_log_del_after` AFTER DELETE ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `parlamentarier_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `parlamentarier_anhang`
--

DROP TABLE IF EXISTS `parlamentarier_anhang`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `parlamentarier_anhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentarieranhangs',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Parlamentariers',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(50) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `parlamentarier_id` (`parlamentarier_id`),
  CONSTRAINT `fk_parlam_anhang` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=569 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_anhang_log_ins` AFTER INSERT ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_anhang_log_upd` AFTER UPDATE ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_anhang_log_del_before` BEFORE DELETE ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_parlamentarier_anhang_log_del_after` AFTER DELETE ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `parlamentarier_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `parlamentarier_anhang_log`
--

DROP TABLE IF EXISTS `parlamentarier_anhang_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `parlamentarier_anhang_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Parlamentariers',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(50) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `parlamentarier_id` (`parlamentarier_id`),
  KEY `fk_parlamentarier_anhang_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_parlamentarier_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1522 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `parlamentarier_log`
--

DROP TABLE IF EXISTS `parlamentarier_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `parlamentarier_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des Parlamentariers',
  `vorname` varchar(50) NOT NULL COMMENT 'Vornahme des Parlamentariers',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname des Parlamentariers',
  `rat_id` int(11) NOT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates',
  `kanton_id` int(11) NOT NULL COMMENT 'Kantonszugehörigkeit; Fremdschlüssel des Kantons',
  `kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Leer bedeutet parteilos.',
  `parteifunktion` enum('mitglied','praesident','vizepraesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit im nationalen Parlament. Fremdschlüssel.',
  `fraktionsfunktion` enum('mitglied','praesident','vizepraesident') DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Fraktion',
  `im_rat_seit` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
  `im_rat_bis` date DEFAULT NULL COMMENT 'Austrittsdatum aus dem Parlament. Leer (NULL) = aktuell im Rat, nicht leer = historischer Eintrag',
  `ratswechsel` date DEFAULT NULL COMMENT 'Datum in welchem der Parlamentarier den Rat wechselte, in der Regel vom National- in den Ständerat. Leer (NULL) = kein Ratswechsel hat stattgefunden',
  `ratsunterbruch_von` date DEFAULT NULL COMMENT 'Unterbruch in der Ratstätigkeit von, leer (NULL) = kein Unterbruch',
  `ratsunterbruch_bis` date DEFAULT NULL COMMENT 'Unterbruch in der Ratstätigkeit bis, leer (NULL) = kein Unterbruch',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers auf französisch',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `titel` varchar(100) DEFAULT NULL COMMENT 'Titel des Parlamentariers, wird von ws.parlament.ch importiert',
  `aemter` text COMMENT 'Politische Ämter (importiert von ws.parlament.ch mandate)',
  `weitere_aemter` text COMMENT 'Zusätzliche Ämter (importiert von ws.parlament.ch additionalMandate)',
  `zivilstand` enum('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt','verwitwet') DEFAULT NULL COMMENT 'Zivilstand',
  `anzahl_kinder` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl der Kinder',
  `militaerischer_grad_id` int(11) DEFAULT NULL COMMENT 'Militärischer Grad, leer (NULL) = kein Militärdienst',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `geburtstag` date DEFAULT NULL COMMENT 'Geburtstag des Parlamentariers',
  `photo` varchar(255) DEFAULT NULL COMMENT 'Photo des Parlamentariers (JPEG/jpg)',
  `photo_dateiname` varchar(255) DEFAULT NULL COMMENT 'Photodateiname ohne Erweiterung',
  `photo_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Photodatei',
  `photo_dateiname_voll` varchar(255) DEFAULT NULL COMMENT 'Photodateiname mit Erweiterung',
  `photo_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Photos',
  `kleinbild` varchar(80) DEFAULT 'leer.png' COMMENT 'Bild 44x62 px oder leer.png',
  `sitzplatz` int(11) DEFAULT NULL COMMENT 'Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Parlamentariers',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage des Parlamentariers',
  `homepage_2` varchar(255) DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch',
  `parlament_biografie_id` int(11) DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.',
  `parlament_number` int(11) DEFAULT NULL COMMENT 'Number Feld auf ws.parlament.ch, wird z.B. als ID für Photos verwendet.',
  `parlament_interessenbindungen` text COMMENT 'Importierte Interessenbindungen von ws.parlament.ch',
  `parlament_interessenbindungen_updated` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindungen von ws.parlament.ch zu letzt aktualisiert wurden.',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `wikipedia` varchar(255) DEFAULT NULL COMMENT 'Link zum Wkipedia-Eintrag des Parlamentariers',
  `sprache` enum('de','fr','it','sk','rm','tr') DEFAULT NULL COMMENT 'Sprache des Parlamentariers, wird von ws.parlament.ch importiert',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch',
  `adresse_firma` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_ort` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') DEFAULT NULL COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen und Zutrittsberechtigungen vom Parlamentarier autorisiert wurden.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `idx_partei` (`partei_id`),
  KEY `beruf_branche_id` (`beruf_interessengruppe_id`),
  KEY `militaerischer_grad` (`militaerischer_grad_id`),
  KEY `fraktion_id` (`fraktion_id`),
  KEY `fk_parlamentarier_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_parlamentarier_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12097 DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `partei`
--

DROP TABLE IF EXISTS `partei`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `partei` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Partei',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `abkuerzung_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit der Partei im nationalen Parlament',
  `gruendung` date DEFAULT NULL COMMENT 'Gründungsjahr der Partei. Wenn der genaue Tag unbekannt ist, den 1. Januar wählen.',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der Partei',
  `homepage_fr` varchar(255) DEFAULT NULL COMMENT 'Französische Homepage der Partei',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der Partei',
  `email_fr` varchar(100) DEFAULT NULL COMMENT 'Französische Kontakt E-Mail-Adresse der Partei',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `twitter_name_fr` varchar(50) DEFAULT NULL COMMENT 'Französischer Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Partei',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Partei',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `partei_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `partei_name_unique` (`name`),
  KEY `fraktion_id` (`fraktion_id`),
  CONSTRAINT `fk_partei_fraktion_id` FOREIGN KEY (`fraktion_id`) REFERENCES `fraktion` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_partei_log_ins` AFTER INSERT ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `partei` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_partei_log_upd` AFTER UPDATE ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'update', null, NOW(), null FROM `partei` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_partei_log_del_before` BEFORE DELETE ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `partei` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_partei_log_del_after` AFTER DELETE ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `partei_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `partei_log`
--

DROP TABLE IF EXISTS `partei_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `partei_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `abkuerzung_fr` varchar(20) DEFAULT NULL COMMENT 'Französische Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit der Partei im nationalen Parlament',
  `gruendung` date DEFAULT NULL COMMENT 'Gründungsjahr der Partei. Wenn der genaue Tag unbekannt ist, den 1. Januar wählen.',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der Partei',
  `homepage_fr` varchar(255) DEFAULT NULL COMMENT 'Französische Homepage der Partei',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der Partei',
  `email_fr` varchar(100) DEFAULT NULL COMMENT 'Französische Kontakt E-Mail-Adresse der Partei',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `twitter_name_fr` varchar(50) DEFAULT NULL COMMENT 'Französischer Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Partei',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Partei',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fraktion_id` (`fraktion_id`),
  KEY `fk_partei_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_partei_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `beschreibung_de` text COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `zutrittsberechtigung_von` varchar(75) DEFAULT NULL COMMENT 'Welcher Parlamentarier gab die Zutrittsberechtigung?',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf der Person',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Beruf der Person',
  `titel` varchar(100) DEFAULT NULL COMMENT 'Titel der Person, z.B. Lic. iur.',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Parteimitgliedschaft der zutrittsberechtigten Person.',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Zutrittsberechtigten',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der zutrittsberechtigen Person',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der zutrittsberechtigen Person',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Person durch einen Import zu letzt aktualisiert wurde.',
  `erfasst` enum('Ja','Nein') DEFAULT NULL COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_nachname_zweiter_name_vorname_unique` (`nachname`,`vorname`,`zweiter_vorname`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
  KEY `partei` (`partei_id`),
  CONSTRAINT `fk_zb_lg` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  CONSTRAINT `fk_zutrittsberechtigung_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=728 DEFAULT CHARSET=utf8 COMMENT='Lobbyist';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_log_ins` AFTER INSERT ON `person`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `person` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_log_upd` AFTER UPDATE ON `person`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `mandat`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        person_id = NEW.id AND bis IS NULL;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      
      UPDATE `mandat`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        person_id = NEW.id;
  END IF;

  
  SET @disable_person_update = 1;
  
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `zutrittsberechtigung`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        person_id = NEW.id AND bis IS NULL;
  END IF;

  
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      
      UPDATE `zutrittsberechtigung`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        person_id = NEW.id;
  END IF;
  SET @disable_person_update = NULL;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'update', null, NOW(), null FROM `person` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_log_del_before` BEFORE DELETE ON `person`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `person` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_log_del_after` AFTER DELETE ON `person`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `person_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `person_anhang`
--

DROP TABLE IF EXISTS `person_anhang`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `person_anhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Zutrittsberechtigunganhangs',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person.',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(50) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `zutrittsberechtigung_id` (`person_id`),
  CONSTRAINT `fk_person_anhang_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=499 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_anhang_log_ins` AFTER INSERT ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `person_anhang` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_anhang_log_upd` AFTER UPDATE ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `person_anhang` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_anhang_log_del_before` BEFORE DELETE ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `person_anhang` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_person_anhang_log_del_after` AFTER DELETE ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `person_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `person_anhang_log`
--

DROP TABLE IF EXISTS `person_anhang_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `person_anhang_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(50) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `zutrittsberechtigung_id` (`person_id`),
  KEY `fk_zutrittsberechtigung_anhang_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_person_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1295 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `person_log`
--

DROP TABLE IF EXISTS `person_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `person_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `beschreibung_de` text COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `zutrittsberechtigung_von` varchar(75) DEFAULT NULL COMMENT 'Welcher Parlamentarier gab die Zutrittsberechtigung?',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf der Person',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Beruf der Person',
  `titel` varchar(100) DEFAULT NULL COMMENT 'Titel der Person, z.B. Lic. iur.',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Parteimitgliedschaft der zutrittsberechtigten Person.',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Zutrittsberechtigten',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der zutrittsberechtigen Person',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der zutrittsberechtigen Person',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Person durch einen Import zu letzt aktualisiert wurde.',
  `erfasst` enum('Ja','Nein') DEFAULT NULL COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
  KEY `partei` (`partei_id`),
  KEY `fk_zutrittsberechtigung_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_person_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15604 DEFAULT CHARSET=utf8 COMMENT='Lobbyist';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `rat`
--

DROP TABLE IF EXISTS `rat`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `rat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte eines Rates',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Kürzel des Rates',
  `abkuerzung_fr` varchar(10) NOT NULL COMMENT 'Französische Abkürzung',
  `name_de` varchar(50) NOT NULL COMMENT 'Name auf deutsch',
  `name_fr` varchar(50) DEFAULT NULL COMMENT 'Name auf französisch',
  `name_it` varchar(50) DEFAULT NULL COMMENT 'Name auf italienisch',
  `name_en` varchar(50) DEFAULT NULL COMMENT 'Name auf englisch',
  `anzahl_mitglieder` smallint(6) DEFAULT NULL COMMENT 'Anzahl Mitglieder des Rates',
  `typ` enum('legislativ','exekutiv','judikativ') NOT NULL COMMENT 'Typ des Rates',
  `interessenraum_id` int(11) DEFAULT '1' COMMENT 'Interessenraum des Rates',
  `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige',
  `gewicht` int(11) NOT NULL COMMENT 'Reihenfolge der Einträge, je grösser desto tiefer ("schwerer")',
  `beschreibung` text COMMENT 'Eine Beschreibung',
  `homepage_de` varchar(255) DEFAULT NULL COMMENT 'Deutschsprachige Homepage',
  `homepage_fr` varchar(255) DEFAULT NULL COMMENT 'Franszösichsprache Homepage',
  `homepage_it` varchar(255) DEFAULT NULL COMMENT 'Italienischsprachige Homepage',
  `homepage_en` varchar(255) DEFAULT NULL COMMENT 'Englischsprachige Homepage',
  `mitglied_bezeichnung_maennlich_de` varchar(50) NOT NULL COMMENT 'Deutsche Bezeichnung der Männer',
  `mitglied_bezeichnung_weiblich_de` varchar(50) NOT NULL COMMENT 'Deutsche Bezeichung der Frauen',
  `mitglied_bezeichnung_maennlich_fr` varchar(50) NOT NULL COMMENT 'Französische Bezeichnung der Männer',
  `mitglied_bezeichnung_weiblich_fr` varchar(50) NOT NULL COMMENT 'Französische Bezeichung der Frauen',
  `parlament_id` int(11) NOT NULL COMMENT 'ID auf ws.parlament.ch',
  `parlament_type` char(1) DEFAULT NULL COMMENT 'Ratstypecode von ws.parlament.ch',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_rat_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
  KEY `interessenraum_id` (`interessenraum_id`),
  CONSTRAINT `fk_interessenraum_id` FOREIGN KEY (`interessenraum_id`) REFERENCES `interessenraum` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Tabelle der Räte von Lobbywatch';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_rat_log_ins` AFTER INSERT ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `rat` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_rat_log_upd` AFTER UPDATE ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'update', null, NOW(), null FROM `rat` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_rat_log_del_before` BEFORE DELETE ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `rat` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_rat_log_del_after` AFTER DELETE ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `rat_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `rat_log`
--

DROP TABLE IF EXISTS `rat_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `rat_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Kürzel des Rates',
  `abkuerzung_fr` varchar(10) NOT NULL COMMENT 'Französische Abkürzung',
  `name_de` varchar(50) NOT NULL COMMENT 'Name auf deutsch',
  `name_fr` varchar(50) DEFAULT NULL COMMENT 'Name auf französisch',
  `name_it` varchar(50) DEFAULT NULL COMMENT 'Name auf italienisch',
  `name_en` varchar(50) DEFAULT NULL COMMENT 'Name auf englisch',
  `anzahl_mitglieder` smallint(6) DEFAULT NULL COMMENT 'Anzahl Mitglieder des Rates',
  `typ` enum('legislativ','exekutiv','judikativ') NOT NULL COMMENT 'Typ des Rates',
  `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum des Rates',
  `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige',
  `gewicht` int(11) NOT NULL COMMENT 'Reihenfolge der Einträge, je grösser desto tiefer ("schwerer")',
  `beschreibung` text COMMENT 'Eine Beschreibung',
  `homepage_de` varchar(255) DEFAULT NULL COMMENT 'Deutschsprachige Homepage',
  `homepage_fr` varchar(255) DEFAULT NULL COMMENT 'Franszösichsprache Homepage',
  `homepage_it` varchar(255) DEFAULT NULL COMMENT 'Italienischsprachige Homepage',
  `homepage_en` varchar(255) DEFAULT NULL COMMENT 'Englischsprachige Homepage',
  `mitglied_bezeichnung_maennlich_de` varchar(50) NOT NULL COMMENT 'Deutsche Bezeichnung der Männer',
  `mitglied_bezeichnung_weiblich_de` varchar(50) NOT NULL COMMENT 'Deutsche Bezeichung der Frauen',
  `mitglied_bezeichnung_maennlich_fr` varchar(50) NOT NULL COMMENT 'Französische Bezeichnung der Männer',
  `mitglied_bezeichnung_weiblich_fr` varchar(50) NOT NULL COMMENT 'Französische Bezeichung der Frauen',
  `parlament_id` int(11) NOT NULL COMMENT 'ID auf ws.parlament.ch',
  `parlament_type` char(1) DEFAULT NULL COMMENT 'Ratstypecode von ws.parlament.ch',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_rat_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_rat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='Tabelle der Räte von Lobbywatch';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Settings',
  `key_name` varchar(100) NOT NULL COMMENT 'Schlüsselname der Einstellung. NICHT VERÄNDERN. Wird vom Programm vorgegeben',
  `value` varchar(5000) DEFAULT NULL COMMENT 'Wert der Einstellung. Dieser Wert ist nach den Bedürfnissen anzupassen.',
  `description` text COMMENT 'Hinweise zur Bedeutung dieser Einstellung. Welche Werte sind möglich',
  `category_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key_name`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_settings_category_id` FOREIGN KEY (`category_id`) REFERENCES `settings_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='Einstellungen zur Lobbywatch-DB';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_log_ins` AFTER INSERT ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `settings` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_log_upd` AFTER UPDATE ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'update', null, NOW(), null FROM `settings` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_log_del_before` BEFORE DELETE ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `settings` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_log_del_after` AFTER DELETE ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `settings_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `settings_category`
--

DROP TABLE IF EXISTS `settings_category`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `settings_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Settingsateogrie',
  `name` varchar(50) NOT NULL COMMENT 'Name der Settingskategorie',
  `description` text NOT NULL COMMENT 'Beschreibung der Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Kategorie für Settings';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_category_log_ins` AFTER INSERT ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `settings_category` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_category_log_upd` AFTER UPDATE ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'update', null, NOW(), null FROM `settings_category` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_category_log_del_before` BEFORE DELETE ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `settings_category` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_settings_category_log_del_after` AFTER DELETE ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `settings_category_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `settings_category_log`
--

DROP TABLE IF EXISTS `settings_category_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `settings_category_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(50) NOT NULL COMMENT 'Name der Settingskategorie',
  `description` text NOT NULL COMMENT 'Beschreibung der Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_settings_category_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_settings_category_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='Kategorie für Settings';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `settings_log`
--

DROP TABLE IF EXISTS `settings_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `settings_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `key_name` varchar(100) NOT NULL COMMENT 'Schlüsselname der Einstellung. NICHT VERÄNDERN. Wird vom Programm vorgegeben',
  `value` varchar(5000) DEFAULT NULL COMMENT 'Wert der Einstellung. Dieser Wert ist nach den Bedürfnissen anzupassen.',
  `description` text COMMENT 'Hinweise zur Bedeutung dieser Einstellung. Welche Werte sind möglich',
  `category_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_settings_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_settings_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=649 DEFAULT CHARSET=utf8 COMMENT='Einstellungen zur Lobbywatch-DB';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `snapshot`
--

DROP TABLE IF EXISTS `snapshot`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Snapshots',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Snapshots',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Eintrge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) NOT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Lobbywatch snapshots';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `translation_language`
--

DROP TABLE IF EXISTS `translation_language`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `translation_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `langcode` varchar(2) NOT NULL COMMENT 'ISO-Code der Sprache',
  `name` int(11) NOT NULL COMMENT 'Name der Sprache',
  PRIMARY KEY (`id`),
  UNIQUE KEY `langcode` (`langcode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sprachen von Lobbywatch';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `translation_source`
--

DROP TABLE IF EXISTS `translation_source`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `translation_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `source` text NOT NULL COMMENT 'Eindeutiger Schlüssel',
  `context` varchar(255) NOT NULL DEFAULT 'Context der Übersetzung',
  `textgroup` varchar(255) NOT NULL DEFAULT 'default' COMMENT 'Gruppe von Übersetzungen, z.B. für ein Modul (see hook_locale())',
  `location` varchar(255) DEFAULT NULL COMMENT 'Ort wo der Text vorkommt, DB-Tabelle o. Programmfunktion',
  `field` varchar(128) DEFAULT NULL COMMENT 'Name of the field',
  `version` varchar(20) DEFAULT NULL COMMENT 'Version of Lobbywatch, where the string was last updated (for translation optimization).',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `source_key` (`source`(255),`context`,`textgroup`) COMMENT 'Index for key'
) ENGINE=InnoDB AUTO_INCREMENT=1889 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_source_log_ins` AFTER INSERT ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `translation_source` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_source_log_upd` AFTER UPDATE ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'update', null, NOW(), null FROM `translation_source` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_source_log_del_before` BEFORE DELETE ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `translation_source` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_source_log_del_after` AFTER DELETE ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `translation_source_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `translation_source_log`
--

DROP TABLE IF EXISTS `translation_source_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `translation_source_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `source` text NOT NULL COMMENT 'Eindeutiger Schlüssel',
  `context` varchar(255) NOT NULL DEFAULT 'Context der Übersetzung',
  `textgroup` varchar(255) NOT NULL DEFAULT 'default' COMMENT 'Gruppe von Übersetzungen, z.B. für ein Modul (see hook_locale())',
  `location` varchar(255) DEFAULT NULL COMMENT 'Ort wo der Text vorkommt, DB-Tabelle o. Programmfunktion',
  `field` varchar(128) DEFAULT NULL COMMENT 'Name of the field',
  `version` varchar(20) DEFAULT NULL COMMENT 'Version of Lobbywatch, where the string was last updated (for translation optimization).',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `source_key` (`source`(255),`context`,`textgroup`) COMMENT 'Index for key',
  KEY `fk_translation_source_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_translation_source_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2471 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `translation_target`
--

DROP TABLE IF EXISTS `translation_target`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `translation_target` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `translation_source_id` int(11) NOT NULL COMMENT 'Fremschlüssel auf Übersetzungsquelltext',
  `lang` enum('de','fr') NOT NULL COMMENT 'Sprache des Textes',
  `translation` text NOT NULL COMMENT 'Übersetzter Text; "-", wenn der lange Text genommen wird.',
  `plural_translation_source_id` int(11) DEFAULT NULL,
  `plural` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Plural index number in case of plural strings.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `plural_translation_source_id` (`plural_translation_source_id`),
  KEY `translation_source_id` (`translation_source_id`,`lang`),
  CONSTRAINT `plural_translation_source_id` FOREIGN KEY (`plural_translation_source_id`) REFERENCES `translation_source` (`id`),
  CONSTRAINT `translation_source_id` FOREIGN KEY (`translation_source_id`) REFERENCES `translation_source` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2384 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_target_log_ins` AFTER INSERT ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `translation_target` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_target_log_upd` AFTER UPDATE ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'update', null, NOW(), null FROM `translation_target` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_target_log_del_before` BEFORE DELETE ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `translation_target` WHERE id = OLD.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_translation_target_log_del_after` AFTER DELETE ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `translation_target_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `translation_target_log`
--

DROP TABLE IF EXISTS `translation_target_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `translation_target_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `translation_source_id` int(11) NOT NULL COMMENT 'Fremschlüssel auf Übersetzungsquelltext',
  `lang` enum('de','fr') NOT NULL COMMENT 'Sprache des Textes',
  `translation` text NOT NULL COMMENT 'Übersetzter Text; "-", wenn der lange Text genommen wird.',
  `plural_translation_source_id` int(11) DEFAULT NULL,
  `plural` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Plural index number in case of plural strings.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `plural_translation_source_id` (`plural_translation_source_id`),
  KEY `translation_source_id` (`translation_source_id`,`lang`),
  KEY `fk_translation_target_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_translation_target_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2384 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User',
  `name` varchar(10) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `nachname` varchar(100) DEFAULT NULL COMMENT 'Nachname des Benutzers',
  `vorname` varchar(50) DEFAULT NULL COMMENT 'Vorname des Benutzers',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Benutzers',
  `token` varchar(255) DEFAULT NULL COMMENT 'Internal data for verification and password recovering',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Status of the user. Possible values are as follows: 0 - registered user, 1 - user awaiting verification, 2 - user requested password reset',
  `mobile` varchar(20) DEFAULT NULL COMMENT 'Mobile Nr zum Passwortsenden über WhatsApp',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'Datum des letzten Login',
  `last_access` timestamp NULL DEFAULT NULL COMMENT 'Datum des letzten Zugriffs',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_name_unique` (`name`) COMMENT 'Fachlicher unique constraint: Name muss einzigartig sein'
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COMMENT='PHP Generator users';
SET character_set_client = @saved_cs_client ;

--
-- Table structure for table `user_permission`
--

DROP TABLE IF EXISTS `user_permission`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User Persmissions',
  `user_id` int(11) NOT NULL,
  `page_name` varchar(500) DEFAULT NULL,
  `permission_name` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`page_name`(255),`permission_name`)
) ENGINE=InnoDB AUTO_INCREMENT=469 DEFAULT CHARSET=utf8 COMMENT='PHP Generator user permissions';
SET character_set_client = @saved_cs_client ;

--
-- Temporary table structure for view `v_branche`
--

DROP TABLE IF EXISTS `v_branche`;
DROP VIEW IF EXISTS `v_branche`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_branche` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `kommission_id`,
 1 AS `kommission2_id`,
 1 AS `technischer_name`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `angaben`,
 1 AS `angaben_fr`,
 1 AS `farbcode`,
 1 AS `symbol_abs`,
 1 AS `symbol_rel`,
 1 AS `symbol_klein_rel`,
 1 AS `symbol_dateiname_wo_ext`,
 1 AS `symbol_dateierweiterung`,
 1 AS `symbol_dateiname`,
 1 AS `symbol_mime_type`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `beschreibung_de`,
 1 AS `angaben_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `kommission1`,
 1 AS `kommission1_de`,
 1 AS `kommission1_fr`,
 1 AS `kommission1_name`,
 1 AS `kommission1_name_de`,
 1 AS `kommission1_name_fr`,
 1 AS `kommission1_abkuerzung`,
 1 AS `kommission1_abkuerzung_de`,
 1 AS `kommission1_abkuerzung_fr`,
 1 AS `kommission2`,
 1 AS `kommission2_de`,
 1 AS `kommission2_fr`,
 1 AS `kommission2_name`,
 1 AS `kommission2_name_de`,
 1 AS `kommission2_name_fr`,
 1 AS `kommission2_abkuerzung`,
 1 AS `kommission2_abkuerzung_de`,
 1 AS `kommission2_abkuerzung_fr`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_branche_name_with_null`
--

DROP TABLE IF EXISTS `v_branche_name_with_null`;
DROP VIEW IF EXISTS `v_branche_name_with_null`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_branche_name_with_null` AS SELECT 
 1 AS `id`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_branche_simple`
--

DROP TABLE IF EXISTS `v_branche_simple`;
DROP VIEW IF EXISTS `v_branche_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_branche_simple` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `kommission_id`,
 1 AS `kommission2_id`,
 1 AS `technischer_name`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `angaben`,
 1 AS `angaben_fr`,
 1 AS `farbcode`,
 1 AS `symbol_abs`,
 1 AS `symbol_rel`,
 1 AS `symbol_klein_rel`,
 1 AS `symbol_dateiname_wo_ext`,
 1 AS `symbol_dateierweiterung`,
 1 AS `symbol_dateiname`,
 1 AS `symbol_mime_type`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `beschreibung_de`,
 1 AS `angaben_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_country`
--

DROP TABLE IF EXISTS `v_country`;
DROP VIEW IF EXISTS `v_country`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_country` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `continent`,
 1 AS `name_en`,
 1 AS `official_name_en`,
 1 AS `capital_en`,
 1 AS `name_de`,
 1 AS `official_name_de`,
 1 AS `capital_de`,
 1 AS `name_fr`,
 1 AS `official_name_fr`,
 1 AS `capital_fr`,
 1 AS `name_it`,
 1 AS `official_name_it`,
 1 AS `capital_it`,
 1 AS `iso-2`,
 1 AS `iso-3`,
 1 AS `vehicle_code`,
 1 AS `ioc`,
 1 AS `tld`,
 1 AS `currency`,
 1 AS `phone`,
 1 AS `utc`,
 1 AS `show_level`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_fraktion`
--

DROP TABLE IF EXISTS `v_fraktion`;
DROP VIEW IF EXISTS `v_fraktion`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_fraktion` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `position`,
 1 AS `farbcode`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `beschreibung_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_in_kommission`
--

DROP TABLE IF EXISTS `v_in_kommission`;
DROP VIEW IF EXISTS `v_in_kommission`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_in_kommission` AS SELECT 
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `kommission_id`,
 1 AS `funktion`,
 1 AS `parlament_committee_function`,
 1 AS `parlament_committee_function_name`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `rat`,
 1 AS `rat_de`,
 1 AS `rat_fr`,
 1 AS `rat_mixed`,
 1 AS `ratstyp`,
 1 AS `kommission_abkuerzung`,
 1 AS `kommission_name`,
 1 AS `kommission_abkuerzung_de`,
 1 AS `kommission_name_de`,
 1 AS `kommission_abkuerzung_fr`,
 1 AS `kommission_name_fr`,
 1 AS `kommission_abkuerzung_mixed`,
 1 AS `kommission_name_mixed`,
 1 AS `kommission_art`,
 1 AS `kommission_typ`,
 1 AS `kommission_beschreibung`,
 1 AS `kommission_sachbereiche`,
 1 AS `kommission_mutter_kommission_id`,
 1 AS `kommission_parlament_url`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_in_kommission_liste`
--

DROP TABLE IF EXISTS `v_in_kommission_liste`;
DROP VIEW IF EXISTS `v_in_kommission_liste`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_in_kommission_liste` AS SELECT 
 1 AS `abkuerzung`,
 1 AS `abkuerzung_fr`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `typ`,
 1 AS `art`,
 1 AS `beschreibung`,
 1 AS `sachbereiche`,
 1 AS `mutter_kommission_id`,
 1 AS `parlament_url`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `kommission_id`,
 1 AS `funktion`,
 1 AS `parlament_committee_function`,
 1 AS `parlament_committee_function_name`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_in_kommission_parlamentarier`
--

DROP TABLE IF EXISTS `v_in_kommission_parlamentarier`;
DROP VIEW IF EXISTS `v_in_kommission_parlamentarier`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_in_kommission_parlamentarier` AS SELECT 
 1 AS `parlamentarier_name`,
 1 AS `name`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `parlament_biografie_id`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `rat`,
 1 AS `rat_de`,
 1 AS `rat_fr`,
 1 AS `kanton`,
 1 AS `vertretene_bevoelkerung`,
 1 AS `kommissionen_namen`,
 1 AS `kommissionen_abkuerzung`,
 1 AS `partei`,
 1 AS `partei_de`,
 1 AS `partei_fr`,
 1 AS `fraktion`,
 1 AS `militaerischer_grad`,
 1 AS `militaerischer_grad_de`,
 1 AS `militaerischer_grad_fr`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `kommission_id`,
 1 AS `funktion`,
 1 AS `parlament_committee_function`,
 1 AS `parlament_committee_function_name`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_in_kommission_simple`
--

DROP TABLE IF EXISTS `v_in_kommission_simple`;
DROP VIEW IF EXISTS `v_in_kommission_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_in_kommission_simple` AS SELECT 
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `kommission_id`,
 1 AS `funktion`,
 1 AS `parlament_committee_function`,
 1 AS `parlament_committee_function_name`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung`
--

DROP TABLE IF EXISTS `v_interessenbindung`;
DROP VIEW IF EXISTS `v_interessenbindung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `parlamentarier_im_rat_seit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `parlamentarier_lobbyfaktor`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_authorisierungs_email`
--

DROP TABLE IF EXISTS `v_interessenbindung_authorisierungs_email`;
DROP VIEW IF EXISTS `v_interessenbindung_authorisierungs_email`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_authorisierungs_email` AS SELECT 
 1 AS `parlamentarier_name`,
 1 AS `geschlecht`,
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `rechtsform`,
 1 AS `ort`,
 1 AS `art`,
 1 AS `beschreibung`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_jahr`
--

DROP TABLE IF EXISTS `v_interessenbindung_jahr`;
DROP VIEW IF EXISTS `v_interessenbindung_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_jahr` AS SELECT 
 1 AS `id`,
 1 AS `interessenbindung_id`,
 1 AS `jahr`,
 1 AS `verguetung`,
 1 AS `beschreibung`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_liste`
--

DROP TABLE IF EXISTS `v_interessenbindung_liste`;
DROP VIEW IF EXISTS `v_interessenbindung_liste`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_liste` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `ort`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `organisation_beschreibung`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `branche`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_fr`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_fr`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_fr`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `parlamentarier_im_rat_seit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `parlamentarier_lobbyfaktor`,
 1 AS `refreshed_date`,
 1 AS `verguetung`,
 1 AS `verguetung_jahr`,
 1 AS `verguetung_beschreibung`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_liste_indirekt`
--

DROP TABLE IF EXISTS `v_interessenbindung_liste_indirekt`;
DROP VIEW IF EXISTS `v_interessenbindung_liste_indirekt`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_liste_indirekt` AS SELECT 
 1 AS `beziehung`,
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `ort`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `organisation_beschreibung`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `branche`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_fr`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_fr`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_fr`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `parlamentarier_im_rat_seit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `parlamentarier_lobbyfaktor`,
 1 AS `refreshed_date`,
 1 AS `verguetung`,
 1 AS `verguetung_jahr`,
 1 AS `verguetung_beschreibung`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_medium_raw`
--

DROP TABLE IF EXISTS `v_interessenbindung_medium_raw`;
DROP VIEW IF EXISTS `v_interessenbindung_medium_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_medium_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `parlamentarier_im_rat_seit`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_raw`
--

DROP TABLE IF EXISTS `v_interessenbindung_raw`;
DROP VIEW IF EXISTS `v_interessenbindung_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `parlamentarier_im_rat_seit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `parlamentarier_lobbyfaktor`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenbindung_simple`
--

DROP TABLE IF EXISTS `v_interessenbindung_simple`;
DROP VIEW IF EXISTS `v_interessenbindung_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenbindung_simple` AS SELECT 
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessengruppe`
--

DROP TABLE IF EXISTS `v_interessengruppe`;
DROP VIEW IF EXISTS `v_interessengruppe`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessengruppe` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `branche_id`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `alias_namen`,
 1 AS `alias_namen_fr`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `beschreibung_de`,
 1 AS `alias_namen_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `branche`,
 1 AS `branche_de`,
 1 AS `branche_fr`,
 1 AS `kommission_id`,
 1 AS `kommission1_id`,
 1 AS `kommission2_id`,
 1 AS `kommission1`,
 1 AS `kommission1_de`,
 1 AS `kommission1_fr`,
 1 AS `kommission1_name`,
 1 AS `kommission1_name_de`,
 1 AS `kommission1_name_fr`,
 1 AS `kommission1_abkuerzung`,
 1 AS `kommission1_abkuerzung_de`,
 1 AS `kommission1_abkuerzung_fr`,
 1 AS `kommission2`,
 1 AS `kommission2_de`,
 1 AS `kommission2_fr`,
 1 AS `kommission2_name`,
 1 AS `kommission2_name_de`,
 1 AS `kommission2_name_fr`,
 1 AS `kommission2_abkuerzung`,
 1 AS `kommission2_abkuerzung_de`,
 1 AS `kommission2_abkuerzung_fr`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessengruppe_simple`
--

DROP TABLE IF EXISTS `v_interessengruppe_simple`;
DROP VIEW IF EXISTS `v_interessengruppe_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessengruppe_simple` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `branche_id`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `alias_namen`,
 1 AS `alias_namen_fr`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `beschreibung_de`,
 1 AS `alias_namen_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_interessenraum`
--

DROP TABLE IF EXISTS `v_interessenraum`;
DROP VIEW IF EXISTS `v_interessenraum`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_interessenraum` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `reihenfolge`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `beschreibung_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_kanton`
--

DROP TABLE IF EXISTS `v_kanton`;
DROP VIEW IF EXISTS `v_kanton`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_kanton` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `kantonsnr`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `anzahl_staenderaete`,
 1 AS `amtssprache`,
 1 AS `hauptort_de`,
 1 AS `hauptort_fr`,
 1 AS `hauptort_it`,
 1 AS `flaeche_km2`,
 1 AS `beitrittsjahr`,
 1 AS `wappen_klein`,
 1 AS `wappen`,
 1 AS `lagebild`,
 1 AS `homepage`,
 1 AS `beschreibung`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `kanton_jahr_id`,
 1 AS `jahr`,
 1 AS `einwohner`,
 1 AS `auslaenderanteil`,
 1 AS `bevoelkerungsdichte`,
 1 AS `anzahl_gemeinden`,
 1 AS `anzahl_nationalraete`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_kanton_2012`
--

DROP TABLE IF EXISTS `v_kanton_2012`;
DROP VIEW IF EXISTS `v_kanton_2012`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_kanton_2012` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `kantonsnr`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `anzahl_staenderaete`,
 1 AS `amtssprache`,
 1 AS `hauptort_de`,
 1 AS `hauptort_fr`,
 1 AS `hauptort_it`,
 1 AS `flaeche_km2`,
 1 AS `beitrittsjahr`,
 1 AS `wappen_klein`,
 1 AS `wappen`,
 1 AS `lagebild`,
 1 AS `homepage`,
 1 AS `beschreibung`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `kanton_jahr_id`,
 1 AS `jahr`,
 1 AS `einwohner`,
 1 AS `auslaenderanteil`,
 1 AS `bevoelkerungsdichte`,
 1 AS `anzahl_gemeinden`,
 1 AS `anzahl_nationalraete`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_kanton_jahr`
--

DROP TABLE IF EXISTS `v_kanton_jahr`;
DROP VIEW IF EXISTS `v_kanton_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_kanton_jahr` AS SELECT 
 1 AS `id`,
 1 AS `kanton_id`,
 1 AS `jahr`,
 1 AS `anzahl_nationalraete`,
 1 AS `einwohner`,
 1 AS `auslaenderanteil`,
 1 AS `bevoelkerungsdichte`,
 1 AS `anzahl_gemeinden`,
 1 AS `steuereinnahmen`,
 1 AS `ausgaben`,
 1 AS `finanzausgleich`,
 1 AS `schulden`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_kanton_jahr_last`
--

DROP TABLE IF EXISTS `v_kanton_jahr_last`;
DROP VIEW IF EXISTS `v_kanton_jahr_last`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_kanton_jahr_last` AS SELECT 
 1 AS `max_jahr`,
 1 AS `id`,
 1 AS `kanton_id`,
 1 AS `jahr`,
 1 AS `anzahl_nationalraete`,
 1 AS `einwohner`,
 1 AS `auslaenderanteil`,
 1 AS `bevoelkerungsdichte`,
 1 AS `anzahl_gemeinden`,
 1 AS `steuereinnahmen`,
 1 AS `ausgaben`,
 1 AS `finanzausgleich`,
 1 AS `schulden`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_kanton_simple`
--

DROP TABLE IF EXISTS `v_kanton_simple`;
DROP VIEW IF EXISTS `v_kanton_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_kanton_simple` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `kantonsnr`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `anzahl_staenderaete`,
 1 AS `amtssprache`,
 1 AS `hauptort_de`,
 1 AS `hauptort_fr`,
 1 AS `hauptort_it`,
 1 AS `flaeche_km2`,
 1 AS `beitrittsjahr`,
 1 AS `wappen_klein`,
 1 AS `wappen`,
 1 AS `lagebild`,
 1 AS `homepage`,
 1 AS `beschreibung`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_kommission`
--

DROP TABLE IF EXISTS `v_kommission`;
DROP VIEW IF EXISTS `v_kommission`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_kommission` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `abkuerzung_fr`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `rat_id`,
 1 AS `typ`,
 1 AS `art`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `sachbereiche`,
 1 AS `sachbereiche_fr`,
 1 AS `anzahl_mitglieder`,
 1 AS `anzahl_nationalraete`,
 1 AS `anzahl_staenderaete`,
 1 AS `mutter_kommission_id`,
 1 AS `zweitrat_kommission_id`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `parlament_url`,
 1 AS `parlament_id`,
 1 AS `parlament_committee_number`,
 1 AS `parlament_subcommittee_number`,
 1 AS `parlament_type_code`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `abkuerzung_de`,
 1 AS `beschreibung_de`,
 1 AS `sachbereiche_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_branche`
--

DROP TABLE IF EXISTS `v_last_updated_branche`;
DROP VIEW IF EXISTS `v_last_updated_branche`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_branche` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_fraktion`
--

DROP TABLE IF EXISTS `v_last_updated_fraktion`;
DROP VIEW IF EXISTS `v_last_updated_fraktion`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_fraktion` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_in_kommission`
--

DROP TABLE IF EXISTS `v_last_updated_in_kommission`;
DROP VIEW IF EXISTS `v_last_updated_in_kommission`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_in_kommission` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_interessenbindung`
--

DROP TABLE IF EXISTS `v_last_updated_interessenbindung`;
DROP VIEW IF EXISTS `v_last_updated_interessenbindung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_interessenbindung` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_interessenbindung_jahr`
--

DROP TABLE IF EXISTS `v_last_updated_interessenbindung_jahr`;
DROP VIEW IF EXISTS `v_last_updated_interessenbindung_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_interessenbindung_jahr` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_interessengruppe`
--

DROP TABLE IF EXISTS `v_last_updated_interessengruppe`;
DROP VIEW IF EXISTS `v_last_updated_interessengruppe`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_interessengruppe` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_kanton`
--

DROP TABLE IF EXISTS `v_last_updated_kanton`;
DROP VIEW IF EXISTS `v_last_updated_kanton`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_kanton` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_kanton_jahr`
--

DROP TABLE IF EXISTS `v_last_updated_kanton_jahr`;
DROP VIEW IF EXISTS `v_last_updated_kanton_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_kanton_jahr` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_kommission`
--

DROP TABLE IF EXISTS `v_last_updated_kommission`;
DROP VIEW IF EXISTS `v_last_updated_kommission`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_kommission` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_mandat`
--

DROP TABLE IF EXISTS `v_last_updated_mandat`;
DROP VIEW IF EXISTS `v_last_updated_mandat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_mandat` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_mandat_jahr`
--

DROP TABLE IF EXISTS `v_last_updated_mandat_jahr`;
DROP VIEW IF EXISTS `v_last_updated_mandat_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_mandat_jahr` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_organisation`
--

DROP TABLE IF EXISTS `v_last_updated_organisation`;
DROP VIEW IF EXISTS `v_last_updated_organisation`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_organisation` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_organisation_anhang`
--

DROP TABLE IF EXISTS `v_last_updated_organisation_anhang`;
DROP VIEW IF EXISTS `v_last_updated_organisation_anhang`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_organisation_anhang` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_organisation_beziehung`
--

DROP TABLE IF EXISTS `v_last_updated_organisation_beziehung`;
DROP VIEW IF EXISTS `v_last_updated_organisation_beziehung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_organisation_beziehung` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_organisation_jahr`
--

DROP TABLE IF EXISTS `v_last_updated_organisation_jahr`;
DROP VIEW IF EXISTS `v_last_updated_organisation_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_organisation_jahr` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_parlamentarier`
--

DROP TABLE IF EXISTS `v_last_updated_parlamentarier`;
DROP VIEW IF EXISTS `v_last_updated_parlamentarier`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_parlamentarier` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_parlamentarier_anhang`
--

DROP TABLE IF EXISTS `v_last_updated_parlamentarier_anhang`;
DROP VIEW IF EXISTS `v_last_updated_parlamentarier_anhang`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_parlamentarier_anhang` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_partei`
--

DROP TABLE IF EXISTS `v_last_updated_partei`;
DROP VIEW IF EXISTS `v_last_updated_partei`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_partei` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_person`
--

DROP TABLE IF EXISTS `v_last_updated_person`;
DROP VIEW IF EXISTS `v_last_updated_person`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_person` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_person_anhang`
--

DROP TABLE IF EXISTS `v_last_updated_person_anhang`;
DROP VIEW IF EXISTS `v_last_updated_person_anhang`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_person_anhang` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_rat`
--

DROP TABLE IF EXISTS `v_last_updated_rat`;
DROP VIEW IF EXISTS `v_last_updated_rat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_rat` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_settings`
--

DROP TABLE IF EXISTS `v_last_updated_settings`;
DROP VIEW IF EXISTS `v_last_updated_settings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_settings` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_settings_category`
--

DROP TABLE IF EXISTS `v_last_updated_settings_category`;
DROP VIEW IF EXISTS `v_last_updated_settings_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_settings_category` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_tables`
--

DROP TABLE IF EXISTS `v_last_updated_tables`;
DROP VIEW IF EXISTS `v_last_updated_tables`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_tables` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_tables_unordered`
--

DROP TABLE IF EXISTS `v_last_updated_tables_unordered`;
DROP VIEW IF EXISTS `v_last_updated_tables_unordered`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_tables_unordered` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_last_updated_zutrittsberechtigung`
--

DROP TABLE IF EXISTS `v_last_updated_zutrittsberechtigung`;
DROP VIEW IF EXISTS `v_last_updated_zutrittsberechtigung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_last_updated_zutrittsberechtigung` AS SELECT 
 1 AS `table_name`,
 1 AS `name`,
 1 AS `anzahl_eintraege`,
 1 AS `last_visa`,
 1 AS `last_updated`,
 1 AS `last_updated_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mandat`
--

DROP TABLE IF EXISTS `v_mandat`;
DROP VIEW IF EXISTS `v_mandat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_mandat` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mandat_jahr`
--

DROP TABLE IF EXISTS `v_mandat_jahr`;
DROP VIEW IF EXISTS `v_mandat_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_mandat_jahr` AS SELECT 
 1 AS `id`,
 1 AS `mandat_id`,
 1 AS `jahr`,
 1 AS `verguetung`,
 1 AS `beschreibung`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mandat_medium_raw`
--

DROP TABLE IF EXISTS `v_mandat_medium_raw`;
DROP VIEW IF EXISTS `v_mandat_medium_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_mandat_medium_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mandat_raw`
--

DROP TABLE IF EXISTS `v_mandat_raw`;
DROP VIEW IF EXISTS `v_mandat_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_mandat_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mandat_simple`
--

DROP TABLE IF EXISTS `v_mandat_simple`;
DROP VIEW IF EXISTS `v_mandat_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_mandat_simple` AS SELECT 
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_mil_grad`
--

DROP TABLE IF EXISTS `v_mil_grad`;
DROP VIEW IF EXISTS `v_mil_grad`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_mil_grad` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `abkuerzung`,
 1 AS `abkuerzung_fr`,
 1 AS `typ`,
 1 AS `ranghoehe`,
 1 AS `anzeigestufe`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `abkuerzung_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation`
--

DROP TABLE IF EXISTS `v_organisation`;
DROP VIEW IF EXISTS `v_organisation`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_mixed`,
 1 AS `anzeige_bimixed`,
 1 AS `searchable_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `uid`,
 1 AS `ort`,
 1 AS `abkuerzung_de`,
 1 AS `alias_namen_de`,
 1 AS `abkuerzung_fr`,
 1 AS `alias_namen_fr`,
 1 AS `abkuerzung_it`,
 1 AS `alias_namen_it`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `rechtsform_handelsregister`,
 1 AS `rechtsform_zefix`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `sekretariat`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `branche`,
 1 AS `branche_de`,
 1 AS `branche_fr`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_de`,
 1 AS `interessengruppe_fr`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_de`,
 1 AS `interessengruppe_branche_fr`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_de`,
 1 AS `interessengruppe2_fr`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_de`,
 1 AS `interessengruppe2_branche_fr`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_de`,
 1 AS `interessengruppe3_fr`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_de`,
 1 AS `interessengruppe3_branche_fr`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `refreshed_date`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `interessenraum_de`,
 1 AS `interessenraum_fr`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `quelle_url`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `anzahl_mandat_tief`,
 1 AS `anzahl_mandat_mittel`,
 1 AS `anzahl_mandat_hoch`,
 1 AS `lobbyeinfluss`,
 1 AS `lobbyeinfluss_index`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_anhang`
--

DROP TABLE IF EXISTS `v_organisation_anhang`;
DROP VIEW IF EXISTS `v_organisation_anhang`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_anhang` AS SELECT 
 1 AS `organisation_id2`,
 1 AS `id`,
 1 AS `organisation_id`,
 1 AS `datei`,
 1 AS `dateiname`,
 1 AS `dateierweiterung`,
 1 AS `dateiname_voll`,
 1 AS `mime_type`,
 1 AS `encoding`,
 1 AS `beschreibung`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung`
--

DROP TABLE IF EXISTS `v_organisation_beziehung`;
DROP VIEW IF EXISTS `v_organisation_beziehung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung` AS SELECT 
 1 AS `id`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `organisation_name`,
 1 AS `organisation_name_fr`,
 1 AS `ziel_organisation_name`,
 1 AS `ziel_organisation_name_fr`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung_arbeitet_fuer`
--

DROP TABLE IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;
DROP VIEW IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung_arbeitet_fuer` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `freigabe_datum`,
 1 AS `freigabe_datum_unix`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `rechtsform`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `ort`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung_auftraggeber_fuer`
--

DROP TABLE IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;
DROP VIEW IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung_auftraggeber_fuer` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `freigabe_datum`,
 1 AS `freigabe_datum_unix`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `rechtsform`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `ort`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung_mitglied_von`
--

DROP TABLE IF EXISTS `v_organisation_beziehung_mitglied_von`;
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglied_von`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung_mitglied_von` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `freigabe_datum`,
 1 AS `freigabe_datum_unix`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `rechtsform`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `ort`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung_mitglieder`
--

DROP TABLE IF EXISTS `v_organisation_beziehung_mitglieder`;
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglieder`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung_mitglieder` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `freigabe_datum`,
 1 AS `freigabe_datum_unix`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `rechtsform`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `ort`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung_muttergesellschaft`
--

DROP TABLE IF EXISTS `v_organisation_beziehung_muttergesellschaft`;
DROP VIEW IF EXISTS `v_organisation_beziehung_muttergesellschaft`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung_muttergesellschaft` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `freigabe_datum`,
 1 AS `freigabe_datum_unix`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `rechtsform`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `ort`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_beziehung_tochtergesellschaften`
--

DROP TABLE IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;
DROP VIEW IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_beziehung_tochtergesellschaften` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `organisation_id`,
 1 AS `ziel_organisation_id`,
 1 AS `art`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `freigabe_datum`,
 1 AS `freigabe_datum_unix`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `rechtsform`,
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `ort`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_jahr`
--

DROP TABLE IF EXISTS `v_organisation_jahr`;
DROP VIEW IF EXISTS `v_organisation_jahr`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_jahr` AS SELECT 
 1 AS `id`,
 1 AS `organisation_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_jahr_last`
--

DROP TABLE IF EXISTS `v_organisation_jahr_last`;
DROP VIEW IF EXISTS `v_organisation_jahr_last`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_jahr_last` AS SELECT 
 1 AS `max_jahr`,
 1 AS `id`,
 1 AS `organisation_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_lobbyeinfluss_raw`
--

DROP TABLE IF EXISTS `v_organisation_lobbyeinfluss_raw`;
DROP VIEW IF EXISTS `v_organisation_lobbyeinfluss_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_lobbyeinfluss_raw` AS SELECT 
 1 AS `id`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `anzahl_mandat_tief`,
 1 AS `anzahl_mandat_mittel`,
 1 AS `anzahl_mandat_hoch`,
 1 AS `lobbyeinfluss`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_medium_raw`
--

DROP TABLE IF EXISTS `v_organisation_medium_raw`;
DROP VIEW IF EXISTS `v_organisation_medium_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_medium_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_mixed`,
 1 AS `anzeige_bimixed`,
 1 AS `searchable_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `uid`,
 1 AS `ort`,
 1 AS `abkuerzung_de`,
 1 AS `alias_namen_de`,
 1 AS `abkuerzung_fr`,
 1 AS `alias_namen_fr`,
 1 AS `abkuerzung_it`,
 1 AS `alias_namen_it`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `rechtsform_handelsregister`,
 1 AS `rechtsform_zefix`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `sekretariat`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `branche`,
 1 AS `branche_de`,
 1 AS `branche_fr`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_de`,
 1 AS `interessengruppe_fr`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_de`,
 1 AS `interessengruppe_branche_fr`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_de`,
 1 AS `interessengruppe2_fr`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_de`,
 1 AS `interessengruppe2_branche_fr`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_de`,
 1 AS `interessengruppe3_fr`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_de`,
 1 AS `interessengruppe3_branche_fr`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_parlamentarier`
--

DROP TABLE IF EXISTS `v_organisation_parlamentarier`;
DROP VIEW IF EXISTS `v_organisation_parlamentarier`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_parlamentarier` AS SELECT 
 1 AS `parlamentarier_name`,
 1 AS `name`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `parlament_biografie_id`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `kanton`,
 1 AS `vertretene_bevoelkerung`,
 1 AS `kommissionen_namen`,
 1 AS `kommissionen_abkuerzung`,
 1 AS `partei`,
 1 AS `partei_fr`,
 1 AS `fraktion`,
 1 AS `militaerischer_grad`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_parlamentarier_beide`
--

DROP TABLE IF EXISTS `v_organisation_parlamentarier_beide`;
DROP VIEW IF EXISTS `v_organisation_parlamentarier_beide`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_parlamentarier_beide` AS SELECT 
 1 AS `verbindung`,
 1 AS `parlamentarier_id`,
 1 AS `parlamentarier_name`,
 1 AS `ratstyp`,
 1 AS `kanton`,
 1 AS `partei_id`,
 1 AS `partei`,
 1 AS `kommissionen`,
 1 AS `parlament_biografie_id`,
 1 AS `person_id`,
 1 AS `zutrittsberechtigter`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `organisation_id`,
 1 AS `freigabe_datum`,
 1 AS `im_rat_bis`,
 1 AS `im_rat_bis_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_parlamentarier_beide_indirekt`
--

DROP TABLE IF EXISTS `v_organisation_parlamentarier_beide_indirekt`;
DROP VIEW IF EXISTS `v_organisation_parlamentarier_beide_indirekt`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_parlamentarier_beide_indirekt` AS SELECT 
 1 AS `beziehung`,
 1 AS `verbindung`,
 1 AS `parlamentarier_id`,
 1 AS `parlamentarier_name`,
 1 AS `ratstyp`,
 1 AS `kanton`,
 1 AS `partei_id`,
 1 AS `partei`,
 1 AS `kommissionen`,
 1 AS `parlament_biografie_id`,
 1 AS `person_id`,
 1 AS `zutrittsberechtigter`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `bis_unix`,
 1 AS `zwischen_organisation_id`,
 1 AS `zwischen_organisation_art`,
 1 AS `connector_organisation_id`,
 1 AS `freigabe_datum`,
 1 AS `im_rat_bis`,
 1 AS `im_rat_bis_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_parlamentarier_indirekt`
--

DROP TABLE IF EXISTS `v_organisation_parlamentarier_indirekt`;
DROP VIEW IF EXISTS `v_organisation_parlamentarier_indirekt`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_parlamentarier_indirekt` AS SELECT 
 1 AS `beziehung`,
 1 AS `parlamentarier_name`,
 1 AS `name`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `parlament_biografie_id`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `kanton`,
 1 AS `vertretene_bevoelkerung`,
 1 AS `kommissionen_namen`,
 1 AS `kommissionen_abkuerzung`,
 1 AS `partei`,
 1 AS `partei_fr`,
 1 AS `fraktion`,
 1 AS `militaerischer_grad`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `deklarationstyp`,
 1 AS `status`,
 1 AS `behoerden_vertreter`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `connector_organisation_id`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_raw`
--

DROP TABLE IF EXISTS `v_organisation_raw`;
DROP VIEW IF EXISTS `v_organisation_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_mixed`,
 1 AS `anzeige_bimixed`,
 1 AS `searchable_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `uid`,
 1 AS `ort`,
 1 AS `abkuerzung_de`,
 1 AS `alias_namen_de`,
 1 AS `abkuerzung_fr`,
 1 AS `alias_namen_fr`,
 1 AS `abkuerzung_it`,
 1 AS `alias_namen_it`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `rechtsform_handelsregister`,
 1 AS `rechtsform_zefix`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `sekretariat`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `branche`,
 1 AS `branche_de`,
 1 AS `branche_fr`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_de`,
 1 AS `interessengruppe_fr`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_de`,
 1 AS `interessengruppe_branche_fr`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_de`,
 1 AS `interessengruppe2_fr`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_de`,
 1 AS `interessengruppe2_branche_fr`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_de`,
 1 AS `interessengruppe3_fr`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_de`,
 1 AS `interessengruppe3_branche_fr`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `refreshed_date`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `interessenraum_de`,
 1 AS `interessenraum_fr`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `quelle_url`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `anzahl_mandat_tief`,
 1 AS `anzahl_mandat_mittel`,
 1 AS `anzahl_mandat_hoch`,
 1 AS `lobbyeinfluss`,
 1 AS `lobbyeinfluss_index`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_simple`
--

DROP TABLE IF EXISTS `v_organisation_simple`;
DROP VIEW IF EXISTS `v_organisation_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_simple` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_mixed`,
 1 AS `anzeige_bimixed`,
 1 AS `searchable_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `id`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `uid`,
 1 AS `ort`,
 1 AS `abkuerzung_de`,
 1 AS `alias_namen_de`,
 1 AS `abkuerzung_fr`,
 1 AS `alias_namen_fr`,
 1 AS `abkuerzung_it`,
 1 AS `alias_namen_it`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `rechtsform_handelsregister`,
 1 AS `rechtsform_zefix`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `sekretariat`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `notizen`,
 1 AS `updated_by_import`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_organisation_zutrittsberechtigung`
--

DROP TABLE IF EXISTS `v_organisation_zutrittsberechtigung`;
DROP VIEW IF EXISTS `v_organisation_zutrittsberechtigung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_organisation_zutrittsberechtigung` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `zutrittsberechtigung_name`,
 1 AS `name`,
 1 AS `parlamentarier_id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `funktion`,
 1 AS `beruf`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `partei_id`,
 1 AS `geschlecht`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `partei`,
 1 AS `parlamentarier_name`,
 1 AS `zutrittsberechtigung_bis_unix`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier`
--

DROP TABLE IF EXISTS `v_parlamentarier`;
DROP VIEW IF EXISTS `v_parlamentarier`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `titel`,
 1 AS `aemter`,
 1 AS `weitere_aemter`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `homepage_2`,
 1 AS `parlament_biografie_id`,
 1 AS `parlament_number`,
 1 AS `parlament_interessenbindungen`,
 1 AS `parlament_interessenbindungen_updated`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `wikipedia`,
 1 AS `sprache`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `beruf_de`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `geburtstag_unix`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `von_unix`,
 1 AS `bis_unix`,
 1 AS `vertretene_bevoelkerung`,
 1 AS `rat`,
 1 AS `kanton`,
 1 AS `rat_de`,
 1 AS `kanton_name_de`,
 1 AS `rat_fr`,
 1 AS `kanton_name_fr`,
 1 AS `kommissionen_namen`,
 1 AS `kommissionen_namen_de`,
 1 AS `kommissionen_namen_fr`,
 1 AS `kommissionen_abkuerzung`,
 1 AS `kommissionen_abkuerzung_de`,
 1 AS `kommissionen_abkuerzung_fr`,
 1 AS `kommissionen_anzahl`,
 1 AS `partei`,
 1 AS `partei_name`,
 1 AS `fraktion`,
 1 AS `militaerischer_grad`,
 1 AS `partei_de`,
 1 AS `partei_name_de`,
 1 AS `militaerischer_grad_de`,
 1 AS `partei_fr`,
 1 AS `partei_name_fr`,
 1 AS `militaerischer_grad_fr`,
 1 AS `beruf_branche_id`,
 1 AS `titel_de`,
 1 AS `titel_fr`,
 1 AS `refreshed_date`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `lobbyfaktor`,
 1 AS `lobbyfaktor_max`,
 1 AS `lobbyfaktor_percent_max`,
 1 AS `anzahl_interessenbindung_tief_max`,
 1 AS `anzahl_interessenbindung_mittel_max`,
 1 AS `anzahl_interessenbindung_hoch_max`,
 1 AS `ratstyp`,
 1 AS `kanton_abkuerzung`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_anhang`
--

DROP TABLE IF EXISTS `v_parlamentarier_anhang`;
DROP VIEW IF EXISTS `v_parlamentarier_anhang`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_anhang` AS SELECT 
 1 AS `parlamentarier_id2`,
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `datei`,
 1 AS `dateiname`,
 1 AS `dateierweiterung`,
 1 AS `dateiname_voll`,
 1 AS `mime_type`,
 1 AS `encoding`,
 1 AS `beschreibung`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_lobbyfaktor`
--

DROP TABLE IF EXISTS `v_parlamentarier_lobbyfaktor`;
DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_lobbyfaktor` AS SELECT 
 1 AS `id`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `lobbyfaktor`,
 1 AS `lobbyfaktor_einfach`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_lobbyfaktor_max_raw`
--

DROP TABLE IF EXISTS `v_parlamentarier_lobbyfaktor_max_raw`;
DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor_max_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_lobbyfaktor_max_raw` AS SELECT 
 1 AS `id`,
 1 AS `anzahl_interessenbindung_tief_max`,
 1 AS `anzahl_interessenbindung_mittel_max`,
 1 AS `anzahl_interessenbindung_hoch_max`,
 1 AS `lobbyfaktor_max`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_lobbyfaktor_raw`
--

DROP TABLE IF EXISTS `v_parlamentarier_lobbyfaktor_raw`;
DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_lobbyfaktor_raw` AS SELECT 
 1 AS `id`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `lobbyfaktor`,
 1 AS `lobbyfaktor_einfach`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_medium_raw`
--

DROP TABLE IF EXISTS `v_parlamentarier_medium_raw`;
DROP VIEW IF EXISTS `v_parlamentarier_medium_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_medium_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `titel`,
 1 AS `aemter`,
 1 AS `weitere_aemter`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `homepage_2`,
 1 AS `parlament_biografie_id`,
 1 AS `parlament_number`,
 1 AS `parlament_interessenbindungen`,
 1 AS `parlament_interessenbindungen_updated`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `wikipedia`,
 1 AS `sprache`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `beruf_de`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `geburtstag_unix`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `von_unix`,
 1 AS `bis_unix`,
 1 AS `vertretene_bevoelkerung`,
 1 AS `rat`,
 1 AS `kanton`,
 1 AS `rat_de`,
 1 AS `kanton_name_de`,
 1 AS `rat_fr`,
 1 AS `kanton_name_fr`,
 1 AS `kommissionen_namen`,
 1 AS `kommissionen_namen_de`,
 1 AS `kommissionen_namen_fr`,
 1 AS `kommissionen_abkuerzung`,
 1 AS `kommissionen_abkuerzung_de`,
 1 AS `kommissionen_abkuerzung_fr`,
 1 AS `kommissionen_anzahl`,
 1 AS `partei`,
 1 AS `partei_name`,
 1 AS `fraktion`,
 1 AS `militaerischer_grad`,
 1 AS `partei_de`,
 1 AS `partei_name_de`,
 1 AS `militaerischer_grad_de`,
 1 AS `partei_fr`,
 1 AS `partei_name_fr`,
 1 AS `militaerischer_grad_fr`,
 1 AS `beruf_branche_id`,
 1 AS `titel_de`,
 1 AS `titel_fr`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_raw`
--

DROP TABLE IF EXISTS `v_parlamentarier_raw`;
DROP VIEW IF EXISTS `v_parlamentarier_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `titel`,
 1 AS `aemter`,
 1 AS `weitere_aemter`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `homepage_2`,
 1 AS `parlament_biografie_id`,
 1 AS `parlament_number`,
 1 AS `parlament_interessenbindungen`,
 1 AS `parlament_interessenbindungen_updated`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `wikipedia`,
 1 AS `sprache`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `beruf_de`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `geburtstag_unix`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `von_unix`,
 1 AS `bis_unix`,
 1 AS `vertretene_bevoelkerung`,
 1 AS `rat`,
 1 AS `kanton`,
 1 AS `rat_de`,
 1 AS `kanton_name_de`,
 1 AS `rat_fr`,
 1 AS `kanton_name_fr`,
 1 AS `kommissionen_namen`,
 1 AS `kommissionen_namen_de`,
 1 AS `kommissionen_namen_fr`,
 1 AS `kommissionen_abkuerzung`,
 1 AS `kommissionen_abkuerzung_de`,
 1 AS `kommissionen_abkuerzung_fr`,
 1 AS `kommissionen_anzahl`,
 1 AS `partei`,
 1 AS `partei_name`,
 1 AS `fraktion`,
 1 AS `militaerischer_grad`,
 1 AS `partei_de`,
 1 AS `partei_name_de`,
 1 AS `militaerischer_grad_de`,
 1 AS `partei_fr`,
 1 AS `partei_name_fr`,
 1 AS `militaerischer_grad_fr`,
 1 AS `beruf_branche_id`,
 1 AS `titel_de`,
 1 AS `titel_fr`,
 1 AS `refreshed_date`,
 1 AS `anzahl_interessenbindung_tief`,
 1 AS `anzahl_interessenbindung_mittel`,
 1 AS `anzahl_interessenbindung_hoch`,
 1 AS `anzahl_interessenbindung_tief_nach_wahl`,
 1 AS `anzahl_interessenbindung_mittel_nach_wahl`,
 1 AS `anzahl_interessenbindung_hoch_nach_wahl`,
 1 AS `lobbyfaktor`,
 1 AS `lobbyfaktor_max`,
 1 AS `lobbyfaktor_percent_max`,
 1 AS `anzahl_interessenbindung_tief_max`,
 1 AS `anzahl_interessenbindung_mittel_max`,
 1 AS `anzahl_interessenbindung_hoch_max`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_parlamentarier_simple`
--

DROP TABLE IF EXISTS `v_parlamentarier_simple`;
DROP VIEW IF EXISTS `v_parlamentarier_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_parlamentarier_simple` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `rat_id`,
 1 AS `kanton_id`,
 1 AS `kommissionen`,
 1 AS `partei_id`,
 1 AS `parteifunktion`,
 1 AS `fraktion_id`,
 1 AS `fraktionsfunktion`,
 1 AS `im_rat_seit`,
 1 AS `im_rat_bis`,
 1 AS `ratswechsel`,
 1 AS `ratsunterbruch_von`,
 1 AS `ratsunterbruch_bis`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `titel`,
 1 AS `aemter`,
 1 AS `weitere_aemter`,
 1 AS `zivilstand`,
 1 AS `anzahl_kinder`,
 1 AS `militaerischer_grad_id`,
 1 AS `geschlecht`,
 1 AS `geburtstag`,
 1 AS `photo`,
 1 AS `photo_dateiname`,
 1 AS `photo_dateierweiterung`,
 1 AS `photo_dateiname_voll`,
 1 AS `photo_mime_type`,
 1 AS `kleinbild`,
 1 AS `sitzplatz`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `homepage_2`,
 1 AS `parlament_biografie_id`,
 1 AS `parlament_number`,
 1 AS `parlament_interessenbindungen`,
 1 AS `parlament_interessenbindungen_updated`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `wikipedia`,
 1 AS `sprache`,
 1 AS `arbeitssprache`,
 1 AS `adresse_firma`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `adresse_ort`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `beruf_de`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `geburtstag_unix`,
 1 AS `im_rat_seit_unix`,
 1 AS `im_rat_bis_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `von_unix`,
 1 AS `bis_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_partei`
--

DROP TABLE IF EXISTS `v_partei`;
DROP VIEW IF EXISTS `v_partei`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_partei` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `abkuerzung_mixed`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `abkuerzung_fr`,
 1 AS `name`,
 1 AS `name_fr`,
 1 AS `fraktion_id`,
 1 AS `gruendung`,
 1 AS `position`,
 1 AS `farbcode`,
 1 AS `homepage`,
 1 AS `homepage_fr`,
 1 AS `email`,
 1 AS `email_fr`,
 1 AS `twitter_name`,
 1 AS `twitter_name_fr`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `name_de`,
 1 AS `abkuerzung_de`,
 1 AS `beschreibung_de`,
 1 AS `homepage_de`,
 1 AS `twitter_name_de`,
 1 AS `email_de`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_person`
--

DROP TABLE IF EXISTS `v_person`;
DROP VIEW IF EXISTS `v_person`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_person` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `beschreibung_de`,
 1 AS `beschreibung_fr`,
 1 AS `parlamentarier_kommissionen`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `partei_id`,
 1 AS `geschlecht`,
 1 AS `arbeitssprache`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_person_anhang`
--

DROP TABLE IF EXISTS `v_person_anhang`;
DROP VIEW IF EXISTS `v_person_anhang`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_person_anhang` AS SELECT 
 1 AS `person_id2`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `datei`,
 1 AS `dateiname`,
 1 AS `dateierweiterung`,
 1 AS `dateiname_voll`,
 1 AS `mime_type`,
 1 AS `encoding`,
 1 AS `beschreibung`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_person_mandate`
--

DROP TABLE IF EXISTS `v_person_mandate`;
DROP VIEW IF EXISTS `v_person_mandate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_person_mandate` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `ort`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `organisation_beschreibung`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `branche`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_fr`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_fr`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_fr`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `person_name`,
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `refreshed_date`,
 1 AS `verguetung`,
 1 AS `verguetung_jahr`,
 1 AS `verguetung_beschreibung`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_person_simple`
--

DROP TABLE IF EXISTS `v_person_simple`;
DROP VIEW IF EXISTS `v_person_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_person_simple` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `beschreibung_de`,
 1 AS `beschreibung_fr`,
 1 AS `parlamentarier_kommissionen`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `partei_id`,
 1 AS `geschlecht`,
 1 AS `arbeitssprache`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_rat`
--

DROP TABLE IF EXISTS `v_rat`;
DROP VIEW IF EXISTS `v_rat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_rat` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `anzeige_name_mixed`,
 1 AS `abkuerzung_mixed`,
 1 AS `id`,
 1 AS `abkuerzung`,
 1 AS `abkuerzung_fr`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `name_en`,
 1 AS `anzahl_mitglieder`,
 1 AS `typ`,
 1 AS `interessenraum_id`,
 1 AS `anzeigestufe`,
 1 AS `gewicht`,
 1 AS `beschreibung`,
 1 AS `homepage_de`,
 1 AS `homepage_fr`,
 1 AS `homepage_it`,
 1 AS `homepage_en`,
 1 AS `mitglied_bezeichnung_maennlich_de`,
 1 AS `mitglied_bezeichnung_weiblich_de`,
 1 AS `mitglied_bezeichnung_maennlich_fr`,
 1 AS `mitglied_bezeichnung_weiblich_fr`,
 1 AS `parlament_id`,
 1 AS `parlament_type`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_search_table`
--

DROP TABLE IF EXISTS `v_search_table`;
DROP VIEW IF EXISTS `v_search_table`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_search_table` AS SELECT 
 1 AS `id`,
 1 AS `table_name`,
 1 AS `page`,
 1 AS `table_weight`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `search_keywords_de`,
 1 AS `search_keywords_fr`,
 1 AS `freigabe_datum`,
 1 AS `bis`,
 1 AS `weight`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_search_table_raw`
--

DROP TABLE IF EXISTS `v_search_table_raw`;
DROP VIEW IF EXISTS `v_search_table_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_search_table_raw` AS SELECT 
 1 AS `id`,
 1 AS `table_name`,
 1 AS `page`,
 1 AS `table_weight`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `search_keywords_de`,
 1 AS `search_keywords_fr`,
 1 AS `freigabe_datum`,
 1 AS `bis`,
 1 AS `weight`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_settings`
--

DROP TABLE IF EXISTS `v_settings`;
DROP VIEW IF EXISTS `v_settings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_settings` AS SELECT 
 1 AS `id`,
 1 AS `key_name`,
 1 AS `value`,
 1 AS `description`,
 1 AS `category_id`,
 1 AS `notizen`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `category_name`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_settings_category`
--

DROP TABLE IF EXISTS `v_settings_category`;
DROP VIEW IF EXISTS `v_settings_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_settings_category` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `notizen`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_user`
--

DROP TABLE IF EXISTS `v_user`;
DROP VIEW IF EXISTS `v_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_user` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `username`,
 1 AS `id`,
 1 AS `name`,
 1 AS `password`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `email`,
 1 AS `last_login`,
 1 AS `last_access`,
 1 AS `farbcode`,
 1 AS `notizen`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_user_permission`
--

DROP TABLE IF EXISTS `v_user_permission`;
DROP VIEW IF EXISTS `v_user_permission`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_user_permission` AS SELECT 
 1 AS `id`,
 1 AS `user_id`,
 1 AS `page_name`,
 1 AS `permission_name`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `beschreibung_de`,
 1 AS `beschreibung_fr`,
 1 AS `parlamentarier_kommissionen`,
 1 AS `parlamentarier_kommissionen_zutrittsberechtigung`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `partei_id`,
 1 AS `geschlecht`,
 1 AS `arbeitssprache`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa_person`,
 1 AS `eingabe_abgeschlossen_datum_person`,
 1 AS `kontrolliert_visa_person`,
 1 AS `kontrolliert_datum_person`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa_person`,
 1 AS `freigabe_datum_person`,
 1 AS `created_visa_person`,
 1 AS `created_date_person`,
 1 AS `updated_visa_person`,
 1 AS `updated_date_person`,
 1 AS `created_date_unix_person`,
 1 AS `updated_date_unix_person`,
 1 AS `eingabe_abgeschlossen_datum_unix_person`,
 1 AS `kontrolliert_datum_unix_person`,
 1 AS `freigabe_datum_unix_person`,
 1 AS `parlamentarier_id`,
 1 AS `person_id`,
 1 AS `zutrittsberechtigung_id`,
 1 AS `funktion`,
 1 AS `funktion_fr`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `beruf_branche_id`,
 1 AS `partei`,
 1 AS `partei_de`,
 1 AS `partei_fr`,
 1 AS `parlamentarier_name`,
 1 AS `parlamentarier_freigabe_datum`,
 1 AS `parlamentarier_freigabe_datum_unix`,
 1 AS `anzahl_mandat_tief`,
 1 AS `anzahl_mandat_mittel`,
 1 AS `anzahl_mandat_hoch`,
 1 AS `lobbyfaktor`,
 1 AS `lobbyfaktor_max`,
 1 AS `lobbyfaktor_percent_max`,
 1 AS `anzahl_mandat_tief_max`,
 1 AS `anzahl_mandat_mittel_max`,
 1 AS `anzahl_mandat_hoch_max`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_lobbyfaktor_max_raw`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_max_raw`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_max_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_lobbyfaktor_max_raw` AS SELECT 
 1 AS `id`,
 1 AS `anzahl_mandat_tief_max`,
 1 AS `anzahl_mandat_mittel_max`,
 1 AS `anzahl_mandat_hoch_max`,
 1 AS `lobbyfaktor_max`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_lobbyfaktor_raw`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_raw`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_lobbyfaktor_raw` AS SELECT 
 1 AS `person_id`,
 1 AS `anzahl_mandat_tief`,
 1 AS `anzahl_mandat_mittel`,
 1 AS `anzahl_mandat_hoch`,
 1 AS `lobbyfaktor`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_mandate`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_mandate`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mandate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_mandate` AS SELECT 
 1 AS `parlamentarier_id`,
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `ort`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `organisation_beschreibung`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `branche`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `zutrittsberechtigung_name`,
 1 AS `funktion`,
 1 AS `funktion_fr`,
 1 AS `anzeige_name`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `wirksamkeit`,
 1 AS `wirksamkeit_index`,
 1 AS `organisation_lobbyeinfluss`,
 1 AS `refreshed_date`,
 1 AS `verguetung`,
 1 AS `verguetung_jahr`,
 1 AS `verguetung_beschreibung`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_mit_mandaten`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_mit_mandaten` AS SELECT 
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `ort`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `organisation_beschreibung`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `branche`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `zutrittsberechtigung_name`,
 1 AS `funktion`,
 1 AS `parlamentarier_id`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_mit_mandaten_indirekt`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_mit_mandaten_indirekt` AS SELECT 
 1 AS `beziehung`,
 1 AS `organisation_name`,
 1 AS `organisation_name_de`,
 1 AS `organisation_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `name_it`,
 1 AS `ort`,
 1 AS `land_id`,
 1 AS `interessenraum_id`,
 1 AS `rechtsform`,
 1 AS `typ`,
 1 AS `vernehmlassung`,
 1 AS `interessengruppe_id`,
 1 AS `interessengruppe2_id`,
 1 AS `interessengruppe3_id`,
 1 AS `branche_id`,
 1 AS `homepage`,
 1 AS `handelsregister_url`,
 1 AS `twitter_name`,
 1 AS `organisation_beschreibung`,
 1 AS `adresse_strasse`,
 1 AS `adresse_zusatz`,
 1 AS `adresse_plz`,
 1 AS `branche`,
 1 AS `interessengruppe`,
 1 AS `interessengruppe_branche`,
 1 AS `interessengruppe_branche_id`,
 1 AS `interessengruppe2`,
 1 AS `interessengruppe2_branche`,
 1 AS `interessengruppe2_branche_id`,
 1 AS `interessengruppe3`,
 1 AS `interessengruppe3_branche`,
 1 AS `interessengruppe3_branche_id`,
 1 AS `land`,
 1 AS `interessenraum`,
 1 AS `organisation_jahr_id`,
 1 AS `jahr`,
 1 AS `umsatz`,
 1 AS `gewinn`,
 1 AS `kapital`,
 1 AS `mitarbeiter_weltweit`,
 1 AS `mitarbeiter_schweiz`,
 1 AS `geschaeftsbericht_url`,
 1 AS `zutrittsberechtigung_name`,
 1 AS `funktion`,
 1 AS `parlamentarier_id`,
 1 AS `id`,
 1 AS `person_id`,
 1 AS `organisation_id`,
 1 AS `art`,
 1 AS `funktion_im_gremium`,
 1 AS `beschreibung`,
 1 AS `beschreibung_fr`,
 1 AS `quelle_url`,
 1 AS `quelle_url_gueltig`,
 1 AS `quelle`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_raw`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_raw`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_raw`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_raw` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `beschreibung_de`,
 1 AS `beschreibung_fr`,
 1 AS `parlamentarier_kommissionen`,
 1 AS `parlamentarier_kommissionen_zutrittsberechtigung`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `partei_id`,
 1 AS `geschlecht`,
 1 AS `arbeitssprache`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa_person`,
 1 AS `eingabe_abgeschlossen_datum_person`,
 1 AS `kontrolliert_visa_person`,
 1 AS `kontrolliert_datum_person`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa_person`,
 1 AS `freigabe_datum_person`,
 1 AS `created_visa_person`,
 1 AS `created_date_person`,
 1 AS `updated_visa_person`,
 1 AS `updated_date_person`,
 1 AS `created_date_unix_person`,
 1 AS `updated_date_unix_person`,
 1 AS `eingabe_abgeschlossen_datum_unix_person`,
 1 AS `kontrolliert_datum_unix_person`,
 1 AS `freigabe_datum_unix_person`,
 1 AS `parlamentarier_id`,
 1 AS `person_id`,
 1 AS `zutrittsberechtigung_id`,
 1 AS `funktion`,
 1 AS `funktion_fr`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`,
 1 AS `beruf_branche_id`,
 1 AS `partei`,
 1 AS `partei_de`,
 1 AS `partei_fr`,
 1 AS `parlamentarier_name`,
 1 AS `parlamentarier_freigabe_datum`,
 1 AS `parlamentarier_freigabe_datum_unix`,
 1 AS `anzahl_mandat_tief`,
 1 AS `anzahl_mandat_mittel`,
 1 AS `anzahl_mandat_hoch`,
 1 AS `lobbyfaktor`,
 1 AS `lobbyfaktor_max`,
 1 AS `lobbyfaktor_percent_max`,
 1 AS `anzahl_mandat_tief_max`,
 1 AS `anzahl_mandat_mittel_max`,
 1 AS `anzahl_mandat_hoch_max`,
 1 AS `refreshed_date`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_simple`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_simple`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_simple`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_simple` AS SELECT 
 1 AS `id`,
 1 AS `parlamentarier_id`,
 1 AS `person_id`,
 1 AS `parlamentarier_kommissionen`,
 1 AS `funktion`,
 1 AS `funktion_fr`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `updated_by_import`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `v_zutrittsberechtigung_simple_compat`
--

DROP TABLE IF EXISTS `v_zutrittsberechtigung_simple_compat`;
DROP VIEW IF EXISTS `v_zutrittsberechtigung_simple_compat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE VIEW `v_zutrittsberechtigung_simple_compat` AS SELECT 
 1 AS `anzeige_name`,
 1 AS `anzeige_name_de`,
 1 AS `anzeige_name_fr`,
 1 AS `name`,
 1 AS `name_de`,
 1 AS `name_fr`,
 1 AS `id`,
 1 AS `nachname`,
 1 AS `vorname`,
 1 AS `zweiter_vorname`,
 1 AS `beschreibung_de`,
 1 AS `beschreibung_fr`,
 1 AS `parlamentarier_kommissionen`,
 1 AS `parlamentarier_kommissionen_zutrittsberechtigung`,
 1 AS `beruf`,
 1 AS `beruf_fr`,
 1 AS `beruf_interessengruppe_id`,
 1 AS `partei_id`,
 1 AS `geschlecht`,
 1 AS `arbeitssprache`,
 1 AS `email`,
 1 AS `homepage`,
 1 AS `twitter_name`,
 1 AS `linkedin_profil_url`,
 1 AS `xing_profil_name`,
 1 AS `facebook_name`,
 1 AS `telephon_1`,
 1 AS `telephon_2`,
 1 AS `erfasst`,
 1 AS `notizen`,
 1 AS `eingabe_abgeschlossen_visa_person`,
 1 AS `eingabe_abgeschlossen_datum_person`,
 1 AS `kontrolliert_visa_person`,
 1 AS `kontrolliert_datum_person`,
 1 AS `autorisierung_verschickt_visa`,
 1 AS `autorisierung_verschickt_datum`,
 1 AS `autorisiert_visa`,
 1 AS `autorisiert_datum`,
 1 AS `freigabe_visa_person`,
 1 AS `freigabe_datum_person`,
 1 AS `created_visa_person`,
 1 AS `created_date_person`,
 1 AS `updated_visa_person`,
 1 AS `updated_date_person`,
 1 AS `created_date_unix_person`,
 1 AS `updated_date_unix_person`,
 1 AS `eingabe_abgeschlossen_datum_unix_person`,
 1 AS `kontrolliert_datum_unix_person`,
 1 AS `freigabe_datum_unix_person`,
 1 AS `parlamentarier_id`,
 1 AS `person_id`,
 1 AS `zutrittsberechtigung_id`,
 1 AS `funktion`,
 1 AS `funktion_fr`,
 1 AS `von`,
 1 AS `bis`,
 1 AS `eingabe_abgeschlossen_visa`,
 1 AS `eingabe_abgeschlossen_datum`,
 1 AS `kontrolliert_visa`,
 1 AS `kontrolliert_datum`,
 1 AS `freigabe_visa`,
 1 AS `freigabe_datum`,
 1 AS `created_visa`,
 1 AS `created_date`,
 1 AS `updated_visa`,
 1 AS `updated_date`,
 1 AS `bis_unix`,
 1 AS `von_unix`,
 1 AS `created_date_unix`,
 1 AS `updated_date_unix`,
 1 AS `eingabe_abgeschlossen_datum_unix`,
 1 AS `kontrolliert_datum_unix`,
 1 AS `freigabe_datum_unix`;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `zutrittsberechtigung`
--

DROP TABLE IF EXISTS `zutrittsberechtigung`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `zutrittsberechtigung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zutrittsberechtigung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur zutrittsberechtigten Person',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `funktion_fr` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person auf französisch.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zutrittsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zutrittsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Zutrittsberechtigung durch einen Import zu letzt aktualisiert wurde.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parlamentarier_person_unique` (`parlamentarier_id`,`person_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `person_id` (`person_id`,`parlamentarier_id`),
  CONSTRAINT `fk_zutrittsberechtigung_parlamentarier` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`),
  CONSTRAINT `fk_zutrittsberechtigung_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=943 DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")';
SET character_set_client = @saved_cs_client ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_zutrittsberechtigung_before_ins` BEFORE INSERT ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;

  
  
  SET NEW.parlamentarier_kommissionen = (SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id = NEW.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id);

  
   UPDATE person
    SET
    parlamentarier_kommissionen = NEW.parlamentarier_kommissionen,
    zutrittsberechtigung_von = (SELECT CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) FROM parlamentarier WHERE parlamentarier.id = NEW.parlamentarier_id),
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis IS NULL OR NEW.bis > NOW());
   UPDATE person
    SET
    parlamentarier_kommissionen = NULL,
    zutrittsberechtigung_von = NULL,
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis < NOW());
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_zutrittsberechtigung_log_ins` AFTER INSERT ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_zutrittsberechtigung_before_upd` BEFORE UPDATE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;

  
  
  SET NEW.parlamentarier_kommissionen = (SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id = NEW.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id);

  IF @disable_person_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  
   UPDATE person
    SET
    parlamentarier_kommissionen = NEW.parlamentarier_kommissionen,
    zutrittsberechtigung_von = (SELECT CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) FROM parlamentarier WHERE parlamentarier.id = NEW.parlamentarier_id),
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis IS NULL OR NEW.bis > NOW());
   UPDATE person
    SET
    parlamentarier_kommissionen = NULL,
    zutrittsberechtigung_von = NULL,
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis < NOW());
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_zutrittsberechtigung_log_upd` AFTER UPDATE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  














  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_zutrittsberechtigung_log_del_before` BEFORE DELETE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = OLD.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  
   UPDATE person
    SET
    parlamentarier_kommissionen = NULL,
    zutrittsberechtigung_von = NULL,
    updated_visa = CONCAT(OLD.updated_visa, '*'),
    updated_date = OLD.updated_date
    WHERE person.id = OLD.person_id;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE TRIGGER `trg_zutrittsberechtigung_log_del_after` AFTER DELETE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `zutrittsberechtigung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Table structure for table `zutrittsberechtigung_log`
--

DROP TABLE IF EXISTS `zutrittsberechtigung_log`;
SET @saved_cs_client     = @@character_set_client ;
SET character_set_client = utf8 ;
CREATE TABLE `zutrittsberechtigung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur zutrittsberechtigten Person',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `funktion_fr` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person auf französisch.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zutrittsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zutrittsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
  `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Zutrittsberechtigung durch einen Import zu letzt aktualisiert wurde.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  PRIMARY KEY (`log_id`),
  KEY `fk_zutrittsberechtigung_log_snapshot_id` (`snapshot_id`),
  CONSTRAINT `fk_zutrittsberechtigung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5767 DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")';
SET character_set_client = @saved_cs_client ;

--
-- Dumping routines for database 'lobbywatch'
--
DROP FUNCTION IF EXISTS `UCFIRST` ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE FUNCTION `UCFIRST`(str VARCHAR(4096) CHARSET utf8) RETURNS varchar(4096) CHARSET utf8
    DETERMINISTIC
    COMMENT 'Returns the str with the first character converted to upper case'
BEGIN
	RETURN CONCAT(UCASE(LEFT(str, 1)), SUBSTRING(str, 2));
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
DROP FUNCTION IF EXISTS `UTF8_URLENCODE` ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE FUNCTION `UTF8_URLENCODE`(str VARCHAR(4096) CHARSET utf8) RETURNS varchar(4096) CHARSET utf8
    DETERMINISTIC
    COMMENT 'Encode UTF-8 string as URL'
BEGIN
   
   
   
   DECLARE sub VARCHAR(1) CHARSET utf8;
   
   DECLARE val BIGINT DEFAULT 0;
   
   DECLARE ind INT DEFAULT 1;
   
   
   DECLARE oct INT DEFAULT 0;
   
   DECLARE ret VARCHAR(4096) DEFAULT '';
   
   DECLARE octind INT DEFAULT 0;

   IF ISNULL(str) THEN
      RETURN NULL;
   ELSE
      SET ret = '';
      
      
      WHILE ind <= CHAR_LENGTH(str) DO
         SET sub = MID(str, ind, 1);
         SET val = ORD(sub);
         
         
         IF NOT (val BETWEEN 48 AND 57 OR     
                 val BETWEEN 65 AND 90 OR     
                 val BETWEEN 97 AND 122 OR    
                 
                 val IN (45, 46, 95, 126)) THEN
            
            
            
            
            
            
            SET octind = OCTET_LENGTH(sub);
            WHILE octind > 0 DO
               
               
               
               
               SET oct = (val >> (8 * (octind - 1)));
               
               
               
               SET ret = CONCAT(ret, '%', LPAD(HEX(oct), 2, 0));
               
               
               
               SET val = (val & (POWER(256, (octind - 1)) - 1));
               SET octind = (octind - 1);
            END WHILE;
         ELSE
            
            
            SET ret = CONCAT(ret, sub);
         END IF;
         SET ind = (ind + 1);
      END WHILE;
   END IF;
   RETURN ret;
END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
DROP PROCEDURE IF EXISTS `refreshMaterializedViews` ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE PROCEDURE `refreshMaterializedViews`()
    MODIFIES SQL DATA
    COMMENT 'Aktualisiert die Materialized Views.'
BEGIN
  DECLARE ts TIMESTAMP DEFAULT NOW();
















    REPLACE INTO `mv_interessenbindung`
      SELECT v.* FROM `v_interessenbindung_raw` v; 

    REPLACE INTO `mv_mandat`
      SELECT v.* FROM `v_mandat_raw` v; 







    REPLACE INTO `mv_organisation`
      SELECT v.* FROM `v_organisation_raw` v; 










    REPLACE INTO `mv_parlamentarier`
      SELECT v.* FROM `v_parlamentarier_raw` v; 




    REPLACE INTO `mv_zutrittsberechtigung`
      SELECT v.* FROM `v_zutrittsberechtigung_raw` v; 




    REPLACE INTO `mv_search_table`
      SELECT v.* FROM `v_search_table_raw` v;

END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;
DROP PROCEDURE IF EXISTS `takeSnapshot` ;
SET @saved_cs_client      = @@character_set_client  ;
SET @saved_cs_results     = @@character_set_results  ;
SET @saved_col_connection = @@collation_connection  ;
SET character_set_client  = utf8  ;
SET character_set_results = utf8  ;
SET collation_connection  = utf8_general_ci  ;
SET @saved_sql_mode       = @@sql_mode  ;
SET sql_mode              = 'NO_ENGINE_SUBSTITUTION'  ;
DELIMITER ;;
CREATE PROCEDURE `takeSnapshot`(aVisa VARCHAR(10), aBeschreibung VARCHAR(150))
    MODIFIES SQL DATA
    COMMENT 'Speichert einen Snapshot in die _log Tabellen.'
BEGIN
  DECLARE ts TIMESTAMP DEFAULT NOW();
  DECLARE sid int(11);
  INSERT INTO `snapshot` (`id`, `beschreibung`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES (NULL, aBeschreibung, NULL, aVisa, ts, aVisa, ts);
  SELECT LAST_INSERT_ID() INTO sid;

   INSERT INTO `branche_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `branche`;

   INSERT INTO `interessenbindung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessenbindung`;

   INSERT INTO `interessenbindung_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessenbindung_jahr`;

   INSERT INTO `interessengruppe_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessengruppe`;

   INSERT INTO `in_kommission_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `in_kommission`;

   INSERT INTO `kommission_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kommission`;

   INSERT INTO `mandat_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `mandat`;

   INSERT INTO `mandat_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `mandat_jahr`;

   INSERT INTO `organisation_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation`;

   INSERT INTO `organisation_beziehung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_beziehung`;

   INSERT INTO `organisation_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_anhang`;

   INSERT INTO `organisation_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_jahr`;

   INSERT INTO `parlamentarier_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `parlamentarier`;

   INSERT INTO `parlamentarier_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `parlamentarier_anhang`;

   INSERT INTO `partei_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `partei`;

   INSERT INTO `fraktion_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `fraktion`;

   INSERT INTO `rat_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `rat`;

   INSERT INTO `kanton_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kanton`;

   INSERT INTO `kanton_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kanton_jahr`;

   INSERT INTO `settings_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `settings`;

   INSERT INTO `settings_category_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `settings_category`;

   INSERT INTO `person_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `person`;

   INSERT INTO `person_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `person_anhang`;

   INSERT INTO `zutrittsberechtigung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `zutrittsberechtigung`;

   INSERT INTO `translation_source_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `translation_source`;

   INSERT INTO `translation_target_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `translation_target`;

END ;;
DELIMITER ;
SET sql_mode              = @saved_sql_mode  ;
SET character_set_client  = @saved_cs_client  ;
SET character_set_results = @saved_cs_results  ;
SET collation_connection  = @saved_col_connection  ;

--
-- Current Database: `lobbywatch`
--

USE `lobbywatch`;

--
-- Final view structure for view `v_branche`
--

DROP VIEW IF EXISTS `v_branche`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_branche` AS select `branche`.`anzeige_name` AS `anzeige_name`,`branche`.`anzeige_name_de` AS `anzeige_name_de`,`branche`.`anzeige_name_fr` AS `anzeige_name_fr`,`branche`.`anzeige_name_mixed` AS `anzeige_name_mixed`,`branche`.`id` AS `id`,`branche`.`name` AS `name`,`branche`.`name_fr` AS `name_fr`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`kommission2_id` AS `kommission2_id`,`branche`.`technischer_name` AS `technischer_name`,`branche`.`beschreibung` AS `beschreibung`,`branche`.`beschreibung_fr` AS `beschreibung_fr`,`branche`.`angaben` AS `angaben`,`branche`.`angaben_fr` AS `angaben_fr`,`branche`.`farbcode` AS `farbcode`,`branche`.`symbol_abs` AS `symbol_abs`,`branche`.`symbol_rel` AS `symbol_rel`,`branche`.`symbol_klein_rel` AS `symbol_klein_rel`,`branche`.`symbol_dateiname_wo_ext` AS `symbol_dateiname_wo_ext`,`branche`.`symbol_dateierweiterung` AS `symbol_dateierweiterung`,`branche`.`symbol_dateiname` AS `symbol_dateiname`,`branche`.`symbol_mime_type` AS `symbol_mime_type`,`branche`.`notizen` AS `notizen`,`branche`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`branche`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`branche`.`kontrolliert_visa` AS `kontrolliert_visa`,`branche`.`kontrolliert_datum` AS `kontrolliert_datum`,`branche`.`freigabe_visa` AS `freigabe_visa`,`branche`.`freigabe_datum` AS `freigabe_datum`,`branche`.`created_visa` AS `created_visa`,`branche`.`created_date` AS `created_date`,`branche`.`updated_visa` AS `updated_visa`,`branche`.`updated_date` AS `updated_date`,`branche`.`name_de` AS `name_de`,`branche`.`beschreibung_de` AS `beschreibung_de`,`branche`.`angaben_de` AS `angaben_de`,`branche`.`created_date_unix` AS `created_date_unix`,`branche`.`updated_date_unix` AS `updated_date_unix`,`branche`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`branche`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`branche`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`kommission`.`anzeige_name` AS `kommission1`,`kommission`.`anzeige_name_de` AS `kommission1_de`,`kommission`.`anzeige_name_fr` AS `kommission1_fr`,`kommission`.`name` AS `kommission1_name`,`kommission`.`name_de` AS `kommission1_name_de`,`kommission`.`name_fr` AS `kommission1_name_fr`,`kommission`.`abkuerzung` AS `kommission1_abkuerzung`,`kommission`.`abkuerzung_de` AS `kommission1_abkuerzung_de`,`kommission`.`abkuerzung_fr` AS `kommission1_abkuerzung_fr`,`kommission2`.`anzeige_name` AS `kommission2`,`kommission2`.`anzeige_name_de` AS `kommission2_de`,`kommission2`.`anzeige_name_fr` AS `kommission2_fr`,`kommission2`.`name` AS `kommission2_name`,`kommission2`.`name_de` AS `kommission2_name_de`,`kommission2`.`name_fr` AS `kommission2_name_fr`,`kommission2`.`abkuerzung` AS `kommission2_abkuerzung`,`kommission2`.`abkuerzung_de` AS `kommission2_abkuerzung_de`,`kommission2`.`abkuerzung_fr` AS `kommission2_abkuerzung_fr` from ((`v_branche_simple` `branche` left join `v_kommission` `kommission` on((`kommission`.`id` = `branche`.`kommission_id`))) left join `v_kommission` `kommission2` on((`kommission2`.`id` = `branche`.`kommission2_id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_branche_name_with_null`
--

DROP VIEW IF EXISTS `v_branche_name_with_null`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_branche_name_with_null` AS select `branche`.`id` AS `id`,concat(`branche`.`name`) AS `anzeige_name`,concat(`branche`.`name`) AS `anzeige_name_de`,concat(`branche`.`name_fr`) AS `anzeige_name_fr`,concat_ws(' / ',`branche`.`name`,`branche`.`name_fr`) AS `anzeige_name_mixed` from `branche` order by `branche`.`name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_branche_simple`
--

DROP VIEW IF EXISTS `v_branche_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_branche_simple` AS select concat(`branche`.`name`) AS `anzeige_name`,concat(`branche`.`name`) AS `anzeige_name_de`,concat(`branche`.`name_fr`) AS `anzeige_name_fr`,concat_ws(' / ',`branche`.`name`,`branche`.`name_fr`) AS `anzeige_name_mixed`,`branche`.`id` AS `id`,`branche`.`name` AS `name`,`branche`.`name_fr` AS `name_fr`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`kommission2_id` AS `kommission2_id`,`branche`.`technischer_name` AS `technischer_name`,`branche`.`beschreibung` AS `beschreibung`,`branche`.`beschreibung_fr` AS `beschreibung_fr`,`branche`.`angaben` AS `angaben`,`branche`.`angaben_fr` AS `angaben_fr`,`branche`.`farbcode` AS `farbcode`,`branche`.`symbol_abs` AS `symbol_abs`,`branche`.`symbol_rel` AS `symbol_rel`,`branche`.`symbol_klein_rel` AS `symbol_klein_rel`,`branche`.`symbol_dateiname_wo_ext` AS `symbol_dateiname_wo_ext`,`branche`.`symbol_dateierweiterung` AS `symbol_dateierweiterung`,`branche`.`symbol_dateiname` AS `symbol_dateiname`,`branche`.`symbol_mime_type` AS `symbol_mime_type`,`branche`.`notizen` AS `notizen`,`branche`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`branche`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`branche`.`kontrolliert_visa` AS `kontrolliert_visa`,`branche`.`kontrolliert_datum` AS `kontrolliert_datum`,`branche`.`freigabe_visa` AS `freigabe_visa`,`branche`.`freigabe_datum` AS `freigabe_datum`,`branche`.`created_visa` AS `created_visa`,`branche`.`created_date` AS `created_date`,`branche`.`updated_visa` AS `updated_visa`,`branche`.`updated_date` AS `updated_date`,`branche`.`name` AS `name_de`,`branche`.`beschreibung` AS `beschreibung_de`,`branche`.`angaben` AS `angaben_de`,unix_timestamp(`branche`.`created_date`) AS `created_date_unix`,unix_timestamp(`branche`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`branche`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`branche`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`branche`.`freigabe_datum`) AS `freigabe_datum_unix` from `branche` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_country`
--

DROP VIEW IF EXISTS `v_country`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_country` AS select `country`.`name_de` AS `anzeige_name`,`country`.`name_de` AS `anzeige_name_de`,`country`.`name_fr` AS `anzeige_name_fr`,concat_ws(' / ',`country`.`name_de`,`country`.`name_fr`) AS `anzeige_name_mixed`,`country`.`id` AS `id`,`country`.`continent` AS `continent`,`country`.`name_en` AS `name_en`,`country`.`official_name_en` AS `official_name_en`,`country`.`capital_en` AS `capital_en`,`country`.`name_de` AS `name_de`,`country`.`official_name_de` AS `official_name_de`,`country`.`capital_de` AS `capital_de`,`country`.`name_fr` AS `name_fr`,`country`.`official_name_fr` AS `official_name_fr`,`country`.`capital_fr` AS `capital_fr`,`country`.`name_it` AS `name_it`,`country`.`official_name_it` AS `official_name_it`,`country`.`capital_it` AS `capital_it`,`country`.`iso-2` AS `iso-2`,`country`.`iso-3` AS `iso-3`,`country`.`vehicle_code` AS `vehicle_code`,`country`.`ioc` AS `ioc`,`country`.`tld` AS `tld`,`country`.`currency` AS `currency`,`country`.`phone` AS `phone`,`country`.`utc` AS `utc`,`country`.`show_level` AS `show_level`,`country`.`created_visa` AS `created_visa`,`country`.`created_date` AS `created_date`,`country`.`updated_visa` AS `updated_visa`,`country`.`updated_date` AS `updated_date`,unix_timestamp(`country`.`created_date`) AS `created_date_unix`,unix_timestamp(`country`.`updated_date`) AS `updated_date_unix` from `country` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_fraktion`
--

DROP VIEW IF EXISTS `v_fraktion`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_fraktion` AS select concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name`) AS `anzeige_name`,concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name`) AS `anzeige_name_de`,concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name_fr`) AS `anzeige_name_fr`,concat_ws(' / ',concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name`),concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name_fr`)) AS `anzeige_name_mixed`,`fraktion`.`id` AS `id`,`fraktion`.`abkuerzung` AS `abkuerzung`,`fraktion`.`name` AS `name`,`fraktion`.`name_fr` AS `name_fr`,`fraktion`.`position` AS `position`,`fraktion`.`farbcode` AS `farbcode`,`fraktion`.`beschreibung` AS `beschreibung`,`fraktion`.`beschreibung_fr` AS `beschreibung_fr`,`fraktion`.`von` AS `von`,`fraktion`.`bis` AS `bis`,`fraktion`.`notizen` AS `notizen`,`fraktion`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`fraktion`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`fraktion`.`kontrolliert_visa` AS `kontrolliert_visa`,`fraktion`.`kontrolliert_datum` AS `kontrolliert_datum`,`fraktion`.`freigabe_visa` AS `freigabe_visa`,`fraktion`.`freigabe_datum` AS `freigabe_datum`,`fraktion`.`created_visa` AS `created_visa`,`fraktion`.`created_date` AS `created_date`,`fraktion`.`updated_visa` AS `updated_visa`,`fraktion`.`updated_date` AS `updated_date`,`fraktion`.`name` AS `name_de`,`fraktion`.`beschreibung` AS `beschreibung_de`,unix_timestamp(`fraktion`.`created_date`) AS `created_date_unix`,unix_timestamp(`fraktion`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`fraktion`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`fraktion`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`fraktion`.`freigabe_datum`) AS `freigabe_datum_unix` from `fraktion` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_in_kommission`
--

DROP VIEW IF EXISTS `v_in_kommission`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_in_kommission` AS select `in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`parlament_committee_function` AS `parlament_committee_function`,`in_kommission`.`parlament_committee_function_name` AS `parlament_committee_function_name`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`bis_unix` AS `bis_unix`,`in_kommission`.`von_unix` AS `von_unix`,`in_kommission`.`created_date_unix` AS `created_date_unix`,`in_kommission`.`updated_date_unix` AS `updated_date_unix`,`in_kommission`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`in_kommission`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`in_kommission`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`rat`.`abkuerzung` AS `rat`,`rat`.`abkuerzung` AS `rat_de`,`rat`.`abkuerzung_fr` AS `rat_fr`,concat_ws(' / ',`rat`.`abkuerzung`,`rat`.`abkuerzung_fr`) AS `rat_mixed`,`rat`.`abkuerzung` AS `ratstyp`,`kommission`.`abkuerzung` AS `kommission_abkuerzung`,`kommission`.`name` AS `kommission_name`,`kommission`.`abkuerzung` AS `kommission_abkuerzung_de`,`kommission`.`name` AS `kommission_name_de`,`kommission`.`abkuerzung_fr` AS `kommission_abkuerzung_fr`,`kommission`.`name_fr` AS `kommission_name_fr`,concat_ws(' / ',`kommission`.`abkuerzung`,`kommission`.`abkuerzung_fr`) AS `kommission_abkuerzung_mixed`,concat_ws(' / ',`kommission`.`name`,`kommission`.`name_fr`) AS `kommission_name_mixed`,`kommission`.`art` AS `kommission_art`,`kommission`.`typ` AS `kommission_typ`,`kommission`.`beschreibung` AS `kommission_beschreibung`,`kommission`.`sachbereiche` AS `kommission_sachbereiche`,`kommission`.`mutter_kommission_id` AS `kommission_mutter_kommission_id`,`kommission`.`parlament_url` AS `kommission_parlament_url` from ((((`v_in_kommission_simple` `in_kommission` join `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) left join `kanton` on((`parlamentarier`.`kanton_id` = `kanton`.`id`))) left join `rat` on((`parlamentarier`.`rat_id` = `rat`.`id`))) left join `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_in_kommission_liste`
--

DROP VIEW IF EXISTS `v_in_kommission_liste`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_in_kommission_liste` AS select `kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`abkuerzung_fr` AS `abkuerzung_fr`,`kommission`.`name` AS `name`,`kommission`.`name_fr` AS `name_fr`,`kommission`.`typ` AS `typ`,`kommission`.`art` AS `art`,`kommission`.`beschreibung` AS `beschreibung`,`kommission`.`sachbereiche` AS `sachbereiche`,`kommission`.`mutter_kommission_id` AS `mutter_kommission_id`,`kommission`.`parlament_url` AS `parlament_url`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`parlament_committee_function` AS `parlament_committee_function`,`in_kommission`.`parlament_committee_function_name` AS `parlament_committee_function_name`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`bis_unix` AS `bis_unix`,`in_kommission`.`von_unix` AS `von_unix`,`in_kommission`.`created_date_unix` AS `created_date_unix`,`in_kommission`.`updated_date_unix` AS `updated_date_unix`,`in_kommission`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`in_kommission`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`in_kommission`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_in_kommission_simple` `in_kommission` join `v_kommission` `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) order by `kommission`.`abkuerzung` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_in_kommission_parlamentarier`
--

DROP VIEW IF EXISTS `v_in_kommission_parlamentarier`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_in_kommission_parlamentarier` AS select `parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`rat` AS `rat`,`parlamentarier`.`rat_de` AS `rat_de`,`parlamentarier`.`rat_fr` AS `rat_fr`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`partei_de` AS `partei_de`,`parlamentarier`.`partei_fr` AS `partei_fr`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`parlamentarier`.`militaerischer_grad_de` AS `militaerischer_grad_de`,`parlamentarier`.`militaerischer_grad_fr` AS `militaerischer_grad_fr`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`parlament_committee_function` AS `parlament_committee_function`,`in_kommission`.`parlament_committee_function_name` AS `parlament_committee_function_name`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`bis_unix` AS `bis_unix`,`in_kommission`.`von_unix` AS `von_unix`,`in_kommission`.`created_date_unix` AS `created_date_unix`,`in_kommission`.`updated_date_unix` AS `updated_date_unix`,`in_kommission`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`in_kommission`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`in_kommission`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_in_kommission_simple` `in_kommission` join `v_parlamentarier` `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_in_kommission_simple`
--

DROP VIEW IF EXISTS `v_in_kommission_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_in_kommission_simple` AS select `in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`parlament_committee_function` AS `parlament_committee_function`,`in_kommission`.`parlament_committee_function_name` AS `parlament_committee_function_name`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,unix_timestamp(`in_kommission`.`bis`) AS `bis_unix`,unix_timestamp(`in_kommission`.`von`) AS `von_unix`,unix_timestamp(`in_kommission`.`created_date`) AS `created_date_unix`,unix_timestamp(`in_kommission`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`in_kommission`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`in_kommission`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`in_kommission`.`freigabe_datum`) AS `freigabe_datum_unix` from `in_kommission` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung`
--

DROP VIEW IF EXISTS `v_interessenbindung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung` AS select `mv_interessenbindung`.`anzeige_name` AS `anzeige_name`,`mv_interessenbindung`.`id` AS `id`,`mv_interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`mv_interessenbindung`.`organisation_id` AS `organisation_id`,`mv_interessenbindung`.`art` AS `art`,`mv_interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`mv_interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`mv_interessenbindung`.`status` AS `status`,`mv_interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`mv_interessenbindung`.`beschreibung` AS `beschreibung`,`mv_interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`mv_interessenbindung`.`quelle_url` AS `quelle_url`,`mv_interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mv_interessenbindung`.`quelle` AS `quelle`,`mv_interessenbindung`.`von` AS `von`,`mv_interessenbindung`.`bis` AS `bis`,`mv_interessenbindung`.`notizen` AS `notizen`,`mv_interessenbindung`.`updated_by_import` AS `updated_by_import`,`mv_interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`mv_interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`mv_interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`mv_interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`mv_interessenbindung`.`created_visa` AS `created_visa`,`mv_interessenbindung`.`created_date` AS `created_date`,`mv_interessenbindung`.`updated_visa` AS `updated_visa`,`mv_interessenbindung`.`updated_date` AS `updated_date`,`mv_interessenbindung`.`bis_unix` AS `bis_unix`,`mv_interessenbindung`.`von_unix` AS `von_unix`,`mv_interessenbindung`.`created_date_unix` AS `created_date_unix`,`mv_interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`mv_interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`mv_interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`mv_interessenbindung`.`wirksamkeit_index` AS `wirksamkeit_index`,`mv_interessenbindung`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mv_interessenbindung`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`mv_interessenbindung`.`refreshed_date` AS `refreshed_date` from `mv_interessenbindung` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_authorisierungs_email`
--

DROP VIEW IF EXISTS `v_interessenbindung_authorisierungs_email`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_authorisierungs_email` AS select `parlamentarier`.`name` AS `parlamentarier_name`,ifnull(`parlamentarier`.`geschlecht`,'') AS `geschlecht`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,ifnull(`organisation`.`rechtsform`,'') AS `rechtsform`,ifnull(`organisation`.`ort`,'') AS `ort`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`beschreibung` AS `beschreibung` from ((`v_interessenbindung_simple` `interessenbindung` join `v_organisation_simple` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) group by `parlamentarier`.`id` order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_jahr`
--

DROP VIEW IF EXISTS `v_interessenbindung_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_jahr` AS select `interessenbindung_jahr`.`id` AS `id`,`interessenbindung_jahr`.`interessenbindung_id` AS `interessenbindung_id`,`interessenbindung_jahr`.`jahr` AS `jahr`,`interessenbindung_jahr`.`verguetung` AS `verguetung`,`interessenbindung_jahr`.`beschreibung` AS `beschreibung`,`interessenbindung_jahr`.`quelle_url` AS `quelle_url`,`interessenbindung_jahr`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung_jahr`.`quelle` AS `quelle`,`interessenbindung_jahr`.`notizen` AS `notizen`,`interessenbindung_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung_jahr`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung_jahr`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung_jahr`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung_jahr`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung_jahr`.`created_visa` AS `created_visa`,`interessenbindung_jahr`.`created_date` AS `created_date`,`interessenbindung_jahr`.`updated_visa` AS `updated_visa`,`interessenbindung_jahr`.`updated_date` AS `updated_date`,unix_timestamp(`interessenbindung_jahr`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessenbindung_jahr`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessenbindung_jahr`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessenbindung_jahr`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessenbindung_jahr`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessenbindung_jahr` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_liste`
--

DROP VIEW IF EXISTS `v_interessenbindung_liste`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_liste` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`interessenbindung`.`anzeige_name` AS `anzeige_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`interessenbindung`.`wirksamkeit_index` AS `wirksamkeit_index`,`interessenbindung`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`interessenbindung`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`interessenbindung`.`refreshed_date` AS `refreshed_date`,`interessenbindung_jahr`.`verguetung` AS `verguetung`,`interessenbindung_jahr`.`jahr` AS `verguetung_jahr`,`interessenbindung_jahr`.`beschreibung` AS `verguetung_beschreibung` from ((`v_interessenbindung` `interessenbindung` left join `v_interessenbindung_jahr` `interessenbindung_jahr` on((`interessenbindung_jahr`.`id` = (select `ijn`.`id` from `v_interessenbindung_jahr` `ijn` where ((`ijn`.`interessenbindung_id` = `interessenbindung`.`id`) and (`ijn`.`freigabe_datum` <= now())) order by `ijn`.`jahr` desc limit 1)))) join `v_organisation` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) order by `interessenbindung`.`wirksamkeit`,`organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_liste_indirekt`
--

DROP VIEW IF EXISTS `v_interessenbindung_liste_indirekt`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_liste_indirekt` AS select 'direkt' AS `beziehung`,`interessenbindung_liste`.`organisation_name` AS `organisation_name`,`interessenbindung_liste`.`organisation_name_de` AS `organisation_name_de`,`interessenbindung_liste`.`organisation_name_fr` AS `organisation_name_fr`,`interessenbindung_liste`.`name` AS `name`,`interessenbindung_liste`.`name_de` AS `name_de`,`interessenbindung_liste`.`name_fr` AS `name_fr`,`interessenbindung_liste`.`name_it` AS `name_it`,`interessenbindung_liste`.`ort` AS `ort`,`interessenbindung_liste`.`land_id` AS `land_id`,`interessenbindung_liste`.`interessenraum_id` AS `interessenraum_id`,`interessenbindung_liste`.`rechtsform` AS `rechtsform`,`interessenbindung_liste`.`typ` AS `typ`,`interessenbindung_liste`.`vernehmlassung` AS `vernehmlassung`,`interessenbindung_liste`.`interessengruppe_id` AS `interessengruppe_id`,`interessenbindung_liste`.`interessengruppe2_id` AS `interessengruppe2_id`,`interessenbindung_liste`.`interessengruppe3_id` AS `interessengruppe3_id`,`interessenbindung_liste`.`branche_id` AS `branche_id`,`interessenbindung_liste`.`homepage` AS `homepage`,`interessenbindung_liste`.`handelsregister_url` AS `handelsregister_url`,`interessenbindung_liste`.`twitter_name` AS `twitter_name`,`interessenbindung_liste`.`organisation_beschreibung` AS `organisation_beschreibung`,`interessenbindung_liste`.`adresse_strasse` AS `adresse_strasse`,`interessenbindung_liste`.`adresse_zusatz` AS `adresse_zusatz`,`interessenbindung_liste`.`adresse_plz` AS `adresse_plz`,`interessenbindung_liste`.`branche` AS `branche`,`interessenbindung_liste`.`interessengruppe` AS `interessengruppe`,`interessenbindung_liste`.`interessengruppe_fr` AS `interessengruppe_fr`,`interessenbindung_liste`.`interessengruppe_branche` AS `interessengruppe_branche`,`interessenbindung_liste`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`interessenbindung_liste`.`interessengruppe2` AS `interessengruppe2`,`interessenbindung_liste`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`interessenbindung_liste`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`interessenbindung_liste`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`interessenbindung_liste`.`interessengruppe3` AS `interessengruppe3`,`interessenbindung_liste`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`interessenbindung_liste`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`interessenbindung_liste`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`interessenbindung_liste`.`land` AS `land`,`interessenbindung_liste`.`interessenraum` AS `interessenraum`,`interessenbindung_liste`.`organisation_jahr_id` AS `organisation_jahr_id`,`interessenbindung_liste`.`jahr` AS `jahr`,`interessenbindung_liste`.`umsatz` AS `umsatz`,`interessenbindung_liste`.`gewinn` AS `gewinn`,`interessenbindung_liste`.`kapital` AS `kapital`,`interessenbindung_liste`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`interessenbindung_liste`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`interessenbindung_liste`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`interessenbindung_liste`.`anzeige_name` AS `anzeige_name`,`interessenbindung_liste`.`id` AS `id`,`interessenbindung_liste`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung_liste`.`organisation_id` AS `organisation_id`,`interessenbindung_liste`.`art` AS `art`,`interessenbindung_liste`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung_liste`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung_liste`.`status` AS `status`,`interessenbindung_liste`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung_liste`.`beschreibung` AS `beschreibung`,`interessenbindung_liste`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung_liste`.`quelle_url` AS `quelle_url`,`interessenbindung_liste`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung_liste`.`quelle` AS `quelle`,`interessenbindung_liste`.`von` AS `von`,`interessenbindung_liste`.`bis` AS `bis`,`interessenbindung_liste`.`notizen` AS `notizen`,`interessenbindung_liste`.`updated_by_import` AS `updated_by_import`,`interessenbindung_liste`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung_liste`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung_liste`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung_liste`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung_liste`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung_liste`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung_liste`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung_liste`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung_liste`.`created_visa` AS `created_visa`,`interessenbindung_liste`.`created_date` AS `created_date`,`interessenbindung_liste`.`updated_visa` AS `updated_visa`,`interessenbindung_liste`.`updated_date` AS `updated_date`,`interessenbindung_liste`.`bis_unix` AS `bis_unix`,`interessenbindung_liste`.`von_unix` AS `von_unix`,`interessenbindung_liste`.`created_date_unix` AS `created_date_unix`,`interessenbindung_liste`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung_liste`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung_liste`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung_liste`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung_liste`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung_liste`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`interessenbindung_liste`.`wirksamkeit_index` AS `wirksamkeit_index`,`interessenbindung_liste`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`interessenbindung_liste`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`interessenbindung_liste`.`refreshed_date` AS `refreshed_date`,`interessenbindung_liste`.`verguetung` AS `verguetung`,`interessenbindung_liste`.`verguetung_jahr` AS `verguetung_jahr`,`interessenbindung_liste`.`verguetung_beschreibung` AS `verguetung_beschreibung` from `v_interessenbindung_liste` `interessenbindung_liste` union select 'indirekt' AS `beziehung`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`interessenbindung`.`anzeige_name` AS `anzeige_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`interessenbindung`.`wirksamkeit_index` AS `wirksamkeit_index`,`interessenbindung`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`interessenbindung`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`interessenbindung`.`refreshed_date` AS `refreshed_date`,`interessenbindung_jahr`.`verguetung` AS `verguetung`,`interessenbindung_jahr`.`jahr` AS `verguetung_jahr`,`interessenbindung_jahr`.`beschreibung` AS `verguetung_beschreibung` from (((`v_interessenbindung` `interessenbindung` join `v_organisation_beziehung` `organisation_beziehung` on((`interessenbindung`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) left join `v_interessenbindung_jahr` `interessenbindung_jahr` on((`interessenbindung_jahr`.`id` = (select `ijn`.`id` from `v_interessenbindung_jahr` `ijn` where ((`ijn`.`interessenbindung_id` = `interessenbindung`.`id`) and (`ijn`.`freigabe_datum` <= now())) order by `ijn`.`jahr` desc limit 1)))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`organisation_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_medium_raw`
--

DROP VIEW IF EXISTS `v_interessenbindung_medium_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_medium_raw` AS select concat(`interessenbindung`.`id`,', ',`parlamentarier`.`anzeige_name`,', ',`organisation`.`anzeige_name`,', ',`interessenbindung`.`art`) AS `anzeige_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,if(((`organisation`.`vernehmlassung` in ('immer','punktuell')) and (`interessenbindung`.`art` in ('geschaeftsfuehrend','vorstand')) and exists(select `in_kommission`.`kommission_id` from (`in_kommission` left join `branche` on(((`in_kommission`.`kommission_id` = `branche`.`kommission_id`) or (`in_kommission`.`kommission_id` = `branche`.`kommission2_id`)))) where (((`in_kommission`.`bis` >= now()) or isnull(`in_kommission`.`bis`)) and (`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`) and (`branche`.`id` in (`organisation`.`branche_id`,`organisation`.`interessengruppe_branche_id`,`organisation`.`interessengruppe2_branche_id`,`organisation`.`interessengruppe3_branche_id`))))),'hoch',if(((`organisation`.`vernehmlassung` in ('immer','punktuell')) and (`interessenbindung`.`art` in ('geschaeftsfuehrend','vorstand','taetig','beirat','finanziell'))),'mittel','tief')) AS `wirksamkeit`,`parlamentarier`.`im_rat_seit` AS `parlamentarier_im_rat_seit` from ((`v_interessenbindung_simple` `interessenbindung` join `v_organisation_medium_raw` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `v_parlamentarier_simple` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_raw`
--

DROP VIEW IF EXISTS `v_interessenbindung_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_raw` AS select `interessenbindung`.`anzeige_name` AS `anzeige_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,(case `interessenbindung`.`wirksamkeit` when 'hoch' then 3 when 'mittel' then 2 when 'tief' then 1 else 0 end) AS `wirksamkeit_index`,`organisation`.`lobbyeinfluss` AS `organisation_lobbyeinfluss`,`parlamentarier`.`lobbyfaktor` AS `parlamentarier_lobbyfaktor`,now() AS `refreshed_date` from ((`v_interessenbindung_medium_raw` `interessenbindung` join `v_organisation_raw` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `v_parlamentarier_raw` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenbindung_simple`
--

DROP VIEW IF EXISTS `v_interessenbindung_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenbindung_simple` AS select `interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,unix_timestamp(`interessenbindung`.`bis`) AS `bis_unix`,unix_timestamp(`interessenbindung`.`von`) AS `von_unix`,unix_timestamp(`interessenbindung`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessenbindung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessenbindung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessenbindung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessenbindung`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessenbindung` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessengruppe`
--

DROP VIEW IF EXISTS `v_interessengruppe`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessengruppe` AS select `interessengruppe`.`anzeige_name` AS `anzeige_name`,`interessengruppe`.`anzeige_name_de` AS `anzeige_name_de`,`interessengruppe`.`anzeige_name_fr` AS `anzeige_name_fr`,`interessengruppe`.`anzeige_name_mixed` AS `anzeige_name_mixed`,`interessengruppe`.`id` AS `id`,`interessengruppe`.`name` AS `name`,`interessengruppe`.`name_fr` AS `name_fr`,`interessengruppe`.`branche_id` AS `branche_id`,`interessengruppe`.`beschreibung` AS `beschreibung`,`interessengruppe`.`beschreibung_fr` AS `beschreibung_fr`,`interessengruppe`.`alias_namen` AS `alias_namen`,`interessengruppe`.`alias_namen_fr` AS `alias_namen_fr`,`interessengruppe`.`notizen` AS `notizen`,`interessengruppe`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessengruppe`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessengruppe`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessengruppe`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessengruppe`.`freigabe_visa` AS `freigabe_visa`,`interessengruppe`.`freigabe_datum` AS `freigabe_datum`,`interessengruppe`.`created_visa` AS `created_visa`,`interessengruppe`.`created_date` AS `created_date`,`interessengruppe`.`updated_visa` AS `updated_visa`,`interessengruppe`.`updated_date` AS `updated_date`,`interessengruppe`.`name_de` AS `name_de`,`interessengruppe`.`beschreibung_de` AS `beschreibung_de`,`interessengruppe`.`alias_namen_de` AS `alias_namen_de`,`interessengruppe`.`created_date_unix` AS `created_date_unix`,`interessengruppe`.`updated_date_unix` AS `updated_date_unix`,`interessengruppe`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessengruppe`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessengruppe`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`branche`.`anzeige_name` AS `branche`,`branche`.`anzeige_name_de` AS `branche_de`,`branche`.`anzeige_name_fr` AS `branche_fr`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`kommission_id` AS `kommission1_id`,`branche`.`kommission2_id` AS `kommission2_id`,`branche`.`kommission1` AS `kommission1`,`branche`.`kommission1_de` AS `kommission1_de`,`branche`.`kommission1_fr` AS `kommission1_fr`,`branche`.`kommission1_name` AS `kommission1_name`,`branche`.`kommission1_name_de` AS `kommission1_name_de`,`branche`.`kommission1_name_fr` AS `kommission1_name_fr`,`branche`.`kommission1_abkuerzung` AS `kommission1_abkuerzung`,`branche`.`kommission1_abkuerzung_de` AS `kommission1_abkuerzung_de`,`branche`.`kommission1_abkuerzung_fr` AS `kommission1_abkuerzung_fr`,`branche`.`kommission2` AS `kommission2`,`branche`.`kommission2_de` AS `kommission2_de`,`branche`.`kommission2_fr` AS `kommission2_fr`,`branche`.`kommission2_name` AS `kommission2_name`,`branche`.`kommission2_name_de` AS `kommission2_name_de`,`branche`.`kommission2_name_fr` AS `kommission2_name_fr`,`branche`.`kommission2_abkuerzung` AS `kommission2_abkuerzung`,`branche`.`kommission2_abkuerzung_de` AS `kommission2_abkuerzung_de`,`branche`.`kommission2_abkuerzung_fr` AS `kommission2_abkuerzung_fr` from (`v_interessengruppe_simple` `interessengruppe` left join `v_branche` `branche` on((`branche`.`id` = `interessengruppe`.`branche_id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessengruppe_simple`
--

DROP VIEW IF EXISTS `v_interessengruppe_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessengruppe_simple` AS select concat(`interessengruppe`.`name`) AS `anzeige_name`,concat(`interessengruppe`.`name`) AS `anzeige_name_de`,concat(`interessengruppe`.`name_fr`) AS `anzeige_name_fr`,concat_ws(' / ',`interessengruppe`.`name`,`interessengruppe`.`name_fr`) AS `anzeige_name_mixed`,`interessengruppe`.`id` AS `id`,`interessengruppe`.`name` AS `name`,`interessengruppe`.`name_fr` AS `name_fr`,`interessengruppe`.`branche_id` AS `branche_id`,`interessengruppe`.`beschreibung` AS `beschreibung`,`interessengruppe`.`beschreibung_fr` AS `beschreibung_fr`,`interessengruppe`.`alias_namen` AS `alias_namen`,`interessengruppe`.`alias_namen_fr` AS `alias_namen_fr`,`interessengruppe`.`notizen` AS `notizen`,`interessengruppe`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessengruppe`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessengruppe`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessengruppe`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessengruppe`.`freigabe_visa` AS `freigabe_visa`,`interessengruppe`.`freigabe_datum` AS `freigabe_datum`,`interessengruppe`.`created_visa` AS `created_visa`,`interessengruppe`.`created_date` AS `created_date`,`interessengruppe`.`updated_visa` AS `updated_visa`,`interessengruppe`.`updated_date` AS `updated_date`,`interessengruppe`.`name` AS `name_de`,`interessengruppe`.`beschreibung` AS `beschreibung_de`,`interessengruppe`.`alias_namen` AS `alias_namen_de`,unix_timestamp(`interessengruppe`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessengruppe`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessengruppe`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessengruppe`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessengruppe`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessengruppe` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_interessenraum`
--

DROP VIEW IF EXISTS `v_interessenraum`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_interessenraum` AS select `interessenraum`.`name` AS `anzeige_name`,`interessenraum`.`name` AS `anzeige_name_de`,`interessenraum`.`name_fr` AS `anzeige_name_fr`,concat_ws(' / ',`interessenraum`.`name`,`interessenraum`.`name_fr`) AS `anzeige_name_mixed`,`interessenraum`.`id` AS `id`,`interessenraum`.`name` AS `name`,`interessenraum`.`name_fr` AS `name_fr`,`interessenraum`.`beschreibung` AS `beschreibung`,`interessenraum`.`beschreibung_fr` AS `beschreibung_fr`,`interessenraum`.`reihenfolge` AS `reihenfolge`,`interessenraum`.`notizen` AS `notizen`,`interessenraum`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenraum`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenraum`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenraum`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenraum`.`freigabe_visa` AS `freigabe_visa`,`interessenraum`.`freigabe_datum` AS `freigabe_datum`,`interessenraum`.`created_visa` AS `created_visa`,`interessenraum`.`created_date` AS `created_date`,`interessenraum`.`updated_visa` AS `updated_visa`,`interessenraum`.`updated_date` AS `updated_date`,`interessenraum`.`name` AS `name_de`,`interessenraum`.`beschreibung` AS `beschreibung_de`,unix_timestamp(`interessenraum`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessenraum`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessenraum`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessenraum`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessenraum`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessenraum` order by `interessenraum`.`reihenfolge` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_kanton`
--

DROP VIEW IF EXISTS `v_kanton`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_kanton` AS select `kanton`.`anzeige_name` AS `anzeige_name`,`kanton`.`anzeige_name_de` AS `anzeige_name_de`,`kanton`.`anzeige_name_fr` AS `anzeige_name_fr`,`kanton`.`anzeige_name_mixed` AS `anzeige_name_mixed`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date`,`kanton_jahr`.`id` AS `kanton_jahr_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete` from (`v_kanton_simple` `kanton` left join `v_kanton_jahr_last` `kanton_jahr` on((`kanton_jahr`.`kanton_id` = `kanton`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_kanton_2012`
--

DROP VIEW IF EXISTS `v_kanton_2012`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_kanton_2012` AS select `kanton`.`name_de` AS `anzeige_name`,`kanton`.`name_de` AS `anzeige_name_de`,`kanton`.`name_fr` AS `anzeige_name_fr`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date`,`kanton_jahr`.`id` AS `kanton_jahr_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete` from (`kanton` left join `v_kanton_jahr` `kanton_jahr` on(((`kanton_jahr`.`kanton_id` = `kanton`.`id`) and (`kanton_jahr`.`jahr` = 2012)))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_kanton_jahr`
--

DROP VIEW IF EXISTS `v_kanton_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_kanton_jahr` AS select `kanton_jahr`.`id` AS `id`,`kanton_jahr`.`kanton_id` AS `kanton_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`steuereinnahmen` AS `steuereinnahmen`,`kanton_jahr`.`ausgaben` AS `ausgaben`,`kanton_jahr`.`finanzausgleich` AS `finanzausgleich`,`kanton_jahr`.`schulden` AS `schulden`,`kanton_jahr`.`notizen` AS `notizen`,`kanton_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton_jahr`.`freigabe_visa` AS `freigabe_visa`,`kanton_jahr`.`freigabe_datum` AS `freigabe_datum`,`kanton_jahr`.`created_visa` AS `created_visa`,`kanton_jahr`.`created_date` AS `created_date`,`kanton_jahr`.`updated_visa` AS `updated_visa`,`kanton_jahr`.`updated_date` AS `updated_date`,unix_timestamp(`kanton_jahr`.`created_date`) AS `created_date_unix`,unix_timestamp(`kanton_jahr`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`kanton_jahr`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`kanton_jahr`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`kanton_jahr`.`freigabe_datum`) AS `freigabe_datum_unix` from `kanton_jahr` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_kanton_jahr_last`
--

DROP VIEW IF EXISTS `v_kanton_jahr_last`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_kanton_jahr_last` AS select max(`kanton_jahr`.`jahr`) AS `max_jahr`,`kanton_jahr`.`id` AS `id`,`kanton_jahr`.`kanton_id` AS `kanton_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`steuereinnahmen` AS `steuereinnahmen`,`kanton_jahr`.`ausgaben` AS `ausgaben`,`kanton_jahr`.`finanzausgleich` AS `finanzausgleich`,`kanton_jahr`.`schulden` AS `schulden`,`kanton_jahr`.`notizen` AS `notizen`,`kanton_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton_jahr`.`freigabe_visa` AS `freigabe_visa`,`kanton_jahr`.`freigabe_datum` AS `freigabe_datum`,`kanton_jahr`.`created_visa` AS `created_visa`,`kanton_jahr`.`created_date` AS `created_date`,`kanton_jahr`.`updated_visa` AS `updated_visa`,`kanton_jahr`.`updated_date` AS `updated_date` from `kanton_jahr` group by `kanton_jahr`.`kanton_id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_kanton_simple`
--

DROP VIEW IF EXISTS `v_kanton_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_kanton_simple` AS select `kanton`.`name_de` AS `anzeige_name`,`kanton`.`name_de` AS `anzeige_name_de`,`kanton`.`name_fr` AS `anzeige_name_fr`,concat_ws(' / ',`kanton`.`name_de`,`kanton`.`name_fr`) AS `anzeige_name_mixed`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date` from `kanton` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_kommission`
--

DROP VIEW IF EXISTS `v_kommission`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_kommission` AS select concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') AS `anzeige_name`,concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') AS `anzeige_name_de`,concat(`kommission`.`name_fr`,' (',`kommission`.`abkuerzung_fr`,')') AS `anzeige_name_fr`,concat_ws(' / ',concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')'),concat(`kommission`.`name_fr`,' (',`kommission`.`abkuerzung_fr`,')')) AS `anzeige_name_mixed`,`kommission`.`id` AS `id`,`kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`abkuerzung_fr` AS `abkuerzung_fr`,`kommission`.`name` AS `name`,`kommission`.`name_fr` AS `name_fr`,`kommission`.`rat_id` AS `rat_id`,`kommission`.`typ` AS `typ`,`kommission`.`art` AS `art`,`kommission`.`beschreibung` AS `beschreibung`,`kommission`.`beschreibung_fr` AS `beschreibung_fr`,`kommission`.`sachbereiche` AS `sachbereiche`,`kommission`.`sachbereiche_fr` AS `sachbereiche_fr`,`kommission`.`anzahl_mitglieder` AS `anzahl_mitglieder`,`kommission`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kommission`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kommission`.`mutter_kommission_id` AS `mutter_kommission_id`,`kommission`.`zweitrat_kommission_id` AS `zweitrat_kommission_id`,`kommission`.`von` AS `von`,`kommission`.`bis` AS `bis`,`kommission`.`parlament_url` AS `parlament_url`,`kommission`.`parlament_id` AS `parlament_id`,`kommission`.`parlament_committee_number` AS `parlament_committee_number`,`kommission`.`parlament_subcommittee_number` AS `parlament_subcommittee_number`,`kommission`.`parlament_type_code` AS `parlament_type_code`,`kommission`.`notizen` AS `notizen`,`kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`kommission`.`freigabe_visa` AS `freigabe_visa`,`kommission`.`freigabe_datum` AS `freigabe_datum`,`kommission`.`created_visa` AS `created_visa`,`kommission`.`created_date` AS `created_date`,`kommission`.`updated_visa` AS `updated_visa`,`kommission`.`updated_date` AS `updated_date`,`kommission`.`name` AS `name_de`,`kommission`.`abkuerzung` AS `abkuerzung_de`,`kommission`.`beschreibung` AS `beschreibung_de`,`kommission`.`sachbereiche` AS `sachbereiche_de`,unix_timestamp(`kommission`.`created_date`) AS `created_date_unix`,unix_timestamp(`kommission`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`kommission`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`kommission`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`kommission`.`freigabe_datum`) AS `freigabe_datum_unix` from `kommission` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_branche`
--

DROP VIEW IF EXISTS `v_last_updated_branche`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_branche` AS (select 'branche' AS `table_name`,'Branche' AS `name`,(select count(0) from `branche`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `branche` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_fraktion`
--

DROP VIEW IF EXISTS `v_last_updated_fraktion`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_fraktion` AS (select 'fraktion' AS `table_name`,'Fraktion' AS `name`,(select count(0) from `fraktion`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `fraktion` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_in_kommission`
--

DROP VIEW IF EXISTS `v_last_updated_in_kommission`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_in_kommission` AS (select 'in_kommission' AS `table_name`,'In Kommission' AS `name`,(select count(0) from `in_kommission`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `in_kommission` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_interessenbindung`
--

DROP VIEW IF EXISTS `v_last_updated_interessenbindung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_interessenbindung` AS (select 'interessenbindung' AS `table_name`,'Interessenbindung' AS `name`,(select count(0) from `interessenbindung`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `interessenbindung` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_interessenbindung_jahr`
--

DROP VIEW IF EXISTS `v_last_updated_interessenbindung_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_interessenbindung_jahr` AS (select 'interessenbindung_jahr' AS `table_name`,'Interessenbindungsvergütung' AS `name`,(select count(0) from `interessenbindung_jahr`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `interessenbindung_jahr` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_interessengruppe`
--

DROP VIEW IF EXISTS `v_last_updated_interessengruppe`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_interessengruppe` AS (select 'interessengruppe' AS `table_name`,'Lobbygruppe' AS `name`,(select count(0) from `interessengruppe`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `interessengruppe` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_kanton`
--

DROP VIEW IF EXISTS `v_last_updated_kanton`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_kanton` AS (select 'kanton' AS `table_name`,'Kanton' AS `name`,(select count(0) from `kanton`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `kanton` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_kanton_jahr`
--

DROP VIEW IF EXISTS `v_last_updated_kanton_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_kanton_jahr` AS (select 'kanton_jahr' AS `table_name`,'Kantonjahr' AS `name`,(select count(0) from `kanton_jahr`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `kanton_jahr` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_kommission`
--

DROP VIEW IF EXISTS `v_last_updated_kommission`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_kommission` AS (select 'kommission' AS `table_name`,'Kommission' AS `name`,(select count(0) from `kommission`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `kommission` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_mandat`
--

DROP VIEW IF EXISTS `v_last_updated_mandat`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_mandat` AS (select 'mandat' AS `table_name`,'Mandat' AS `name`,(select count(0) from `mandat`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `mandat` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_mandat_jahr`
--

DROP VIEW IF EXISTS `v_last_updated_mandat_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_mandat_jahr` AS (select 'mandat_jahr' AS `table_name`,'Mandatsvergütung' AS `name`,(select count(0) from `mandat_jahr`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `mandat_jahr` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_organisation`
--

DROP VIEW IF EXISTS `v_last_updated_organisation`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_organisation` AS (select 'organisation' AS `table_name`,'Organisation' AS `name`,(select count(0) from `organisation`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_organisation_anhang`
--

DROP VIEW IF EXISTS `v_last_updated_organisation_anhang`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_organisation_anhang` AS (select 'organisation_anhang' AS `table_name`,'Organisationsanhang' AS `name`,(select count(0) from `organisation_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation_anhang` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_organisation_beziehung`
--

DROP VIEW IF EXISTS `v_last_updated_organisation_beziehung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_organisation_beziehung` AS (select 'organisation_beziehung' AS `table_name`,'Organisation Beziehung' AS `name`,(select count(0) from `organisation_beziehung`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation_beziehung` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_organisation_jahr`
--

DROP VIEW IF EXISTS `v_last_updated_organisation_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_organisation_jahr` AS (select 'organisation_jahr' AS `table_name`,'Organisationsjahr' AS `name`,(select count(0) from `organisation_jahr`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation_jahr` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_parlamentarier`
--

DROP VIEW IF EXISTS `v_last_updated_parlamentarier`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_parlamentarier` AS (select 'parlamentarier' AS `table_name`,'Parlamentarier' AS `name`,(select count(0) from `parlamentarier`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `parlamentarier` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_parlamentarier_anhang`
--

DROP VIEW IF EXISTS `v_last_updated_parlamentarier_anhang`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_parlamentarier_anhang` AS (select 'parlamentarier_anhang' AS `table_name`,'Parlamentarieranhang' AS `name`,(select count(0) from `parlamentarier_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `parlamentarier_anhang` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_partei`
--

DROP VIEW IF EXISTS `v_last_updated_partei`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_partei` AS (select 'partei' AS `table_name`,'Partei' AS `name`,(select count(0) from `partei`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `partei` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_person`
--

DROP VIEW IF EXISTS `v_last_updated_person`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_person` AS (select 'person' AS `table_name`,'Person' AS `name`,(select count(0) from `person`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `person` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_person_anhang`
--

DROP VIEW IF EXISTS `v_last_updated_person_anhang`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_person_anhang` AS (select 'person_anhang' AS `table_name`,'Personenanhang' AS `name`,(select count(0) from `person_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `person_anhang` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_rat`
--

DROP VIEW IF EXISTS `v_last_updated_rat`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_rat` AS (select 'rat' AS `table_name`,'Rat' AS `name`,(select count(0) from `rat`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `rat` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_settings`
--

DROP VIEW IF EXISTS `v_last_updated_settings`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_settings` AS (select 'settings' AS `table_name`,'Einstellungen' AS `name`,(select count(0) from `settings`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `settings` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_settings_category`
--

DROP VIEW IF EXISTS `v_last_updated_settings_category`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_settings_category` AS (select 'settings_category' AS `table_name`,'Einstellungskategorien' AS `name`,(select count(0) from `settings_category`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `settings_category` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_tables`
--

DROP VIEW IF EXISTS `v_last_updated_tables`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_tables` AS select `v_last_updated_tables_unordered`.`table_name` AS `table_name`,`v_last_updated_tables_unordered`.`name` AS `name`,`v_last_updated_tables_unordered`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_tables_unordered`.`last_visa` AS `last_visa`,`v_last_updated_tables_unordered`.`last_updated` AS `last_updated`,`v_last_updated_tables_unordered`.`last_updated_id` AS `last_updated_id` from `v_last_updated_tables_unordered` order by `v_last_updated_tables_unordered`.`last_updated` desc ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_tables_unordered`
--

DROP VIEW IF EXISTS `v_last_updated_tables_unordered`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_tables_unordered` AS select `v_last_updated_branche`.`table_name` AS `table_name`,`v_last_updated_branche`.`name` AS `name`,`v_last_updated_branche`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_branche`.`last_visa` AS `last_visa`,`v_last_updated_branche`.`last_updated` AS `last_updated`,`v_last_updated_branche`.`last_updated_id` AS `last_updated_id` from `v_last_updated_branche` union select `v_last_updated_interessenbindung`.`table_name` AS `table_name`,`v_last_updated_interessenbindung`.`name` AS `name`,`v_last_updated_interessenbindung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessenbindung`.`last_visa` AS `last_visa`,`v_last_updated_interessenbindung`.`last_updated` AS `last_updated`,`v_last_updated_interessenbindung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessenbindung` union select `v_last_updated_interessenbindung_jahr`.`table_name` AS `table_name`,`v_last_updated_interessenbindung_jahr`.`name` AS `name`,`v_last_updated_interessenbindung_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessenbindung_jahr`.`last_visa` AS `last_visa`,`v_last_updated_interessenbindung_jahr`.`last_updated` AS `last_updated`,`v_last_updated_interessenbindung_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessenbindung_jahr` union select `v_last_updated_interessengruppe`.`table_name` AS `table_name`,`v_last_updated_interessengruppe`.`name` AS `name`,`v_last_updated_interessengruppe`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessengruppe`.`last_visa` AS `last_visa`,`v_last_updated_interessengruppe`.`last_updated` AS `last_updated`,`v_last_updated_interessengruppe`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessengruppe` union select `v_last_updated_in_kommission`.`table_name` AS `table_name`,`v_last_updated_in_kommission`.`name` AS `name`,`v_last_updated_in_kommission`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_in_kommission`.`last_visa` AS `last_visa`,`v_last_updated_in_kommission`.`last_updated` AS `last_updated`,`v_last_updated_in_kommission`.`last_updated_id` AS `last_updated_id` from `v_last_updated_in_kommission` union select `v_last_updated_kommission`.`table_name` AS `table_name`,`v_last_updated_kommission`.`name` AS `name`,`v_last_updated_kommission`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kommission`.`last_visa` AS `last_visa`,`v_last_updated_kommission`.`last_updated` AS `last_updated`,`v_last_updated_kommission`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kommission` union select `v_last_updated_mandat`.`table_name` AS `table_name`,`v_last_updated_mandat`.`name` AS `name`,`v_last_updated_mandat`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_mandat`.`last_visa` AS `last_visa`,`v_last_updated_mandat`.`last_updated` AS `last_updated`,`v_last_updated_mandat`.`last_updated_id` AS `last_updated_id` from `v_last_updated_mandat` union select `v_last_updated_mandat_jahr`.`table_name` AS `table_name`,`v_last_updated_mandat_jahr`.`name` AS `name`,`v_last_updated_mandat_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_mandat_jahr`.`last_visa` AS `last_visa`,`v_last_updated_mandat_jahr`.`last_updated` AS `last_updated`,`v_last_updated_mandat_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_mandat_jahr` union select `v_last_updated_organisation`.`table_name` AS `table_name`,`v_last_updated_organisation`.`name` AS `name`,`v_last_updated_organisation`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation`.`last_visa` AS `last_visa`,`v_last_updated_organisation`.`last_updated` AS `last_updated`,`v_last_updated_organisation`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation` union select `v_last_updated_organisation_beziehung`.`table_name` AS `table_name`,`v_last_updated_organisation_beziehung`.`name` AS `name`,`v_last_updated_organisation_beziehung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_beziehung`.`last_visa` AS `last_visa`,`v_last_updated_organisation_beziehung`.`last_updated` AS `last_updated`,`v_last_updated_organisation_beziehung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_beziehung` union select `v_last_updated_organisation_jahr`.`table_name` AS `table_name`,`v_last_updated_organisation_jahr`.`name` AS `name`,`v_last_updated_organisation_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_jahr`.`last_visa` AS `last_visa`,`v_last_updated_organisation_jahr`.`last_updated` AS `last_updated`,`v_last_updated_organisation_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_jahr` union select `v_last_updated_parlamentarier`.`table_name` AS `table_name`,`v_last_updated_parlamentarier`.`name` AS `name`,`v_last_updated_parlamentarier`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_parlamentarier`.`last_visa` AS `last_visa`,`v_last_updated_parlamentarier`.`last_updated` AS `last_updated`,`v_last_updated_parlamentarier`.`last_updated_id` AS `last_updated_id` from `v_last_updated_parlamentarier` union select `v_last_updated_partei`.`table_name` AS `table_name`,`v_last_updated_partei`.`name` AS `name`,`v_last_updated_partei`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_partei`.`last_visa` AS `last_visa`,`v_last_updated_partei`.`last_updated` AS `last_updated`,`v_last_updated_partei`.`last_updated_id` AS `last_updated_id` from `v_last_updated_partei` union select `v_last_updated_fraktion`.`table_name` AS `table_name`,`v_last_updated_fraktion`.`name` AS `name`,`v_last_updated_fraktion`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_fraktion`.`last_visa` AS `last_visa`,`v_last_updated_fraktion`.`last_updated` AS `last_updated`,`v_last_updated_fraktion`.`last_updated_id` AS `last_updated_id` from `v_last_updated_fraktion` union select `v_last_updated_rat`.`table_name` AS `table_name`,`v_last_updated_rat`.`name` AS `name`,`v_last_updated_rat`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_rat`.`last_visa` AS `last_visa`,`v_last_updated_rat`.`last_updated` AS `last_updated`,`v_last_updated_rat`.`last_updated_id` AS `last_updated_id` from `v_last_updated_rat` union select `v_last_updated_kanton`.`table_name` AS `table_name`,`v_last_updated_kanton`.`name` AS `name`,`v_last_updated_kanton`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kanton`.`last_visa` AS `last_visa`,`v_last_updated_kanton`.`last_updated` AS `last_updated`,`v_last_updated_kanton`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kanton` union select `v_last_updated_kanton_jahr`.`table_name` AS `table_name`,`v_last_updated_kanton_jahr`.`name` AS `name`,`v_last_updated_kanton_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kanton_jahr`.`last_visa` AS `last_visa`,`v_last_updated_kanton_jahr`.`last_updated` AS `last_updated`,`v_last_updated_kanton_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kanton_jahr` union select `v_last_updated_zutrittsberechtigung`.`table_name` AS `table_name`,`v_last_updated_zutrittsberechtigung`.`name` AS `name`,`v_last_updated_zutrittsberechtigung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_zutrittsberechtigung`.`last_visa` AS `last_visa`,`v_last_updated_zutrittsberechtigung`.`last_updated` AS `last_updated`,`v_last_updated_zutrittsberechtigung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_zutrittsberechtigung` union select `v_last_updated_person`.`table_name` AS `table_name`,`v_last_updated_person`.`name` AS `name`,`v_last_updated_person`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_person`.`last_visa` AS `last_visa`,`v_last_updated_person`.`last_updated` AS `last_updated`,`v_last_updated_person`.`last_updated_id` AS `last_updated_id` from `v_last_updated_person` union select `v_last_updated_parlamentarier_anhang`.`table_name` AS `table_name`,`v_last_updated_parlamentarier_anhang`.`name` AS `name`,`v_last_updated_parlamentarier_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_parlamentarier_anhang`.`last_visa` AS `last_visa`,`v_last_updated_parlamentarier_anhang`.`last_updated` AS `last_updated`,`v_last_updated_parlamentarier_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_parlamentarier_anhang` union select `v_last_updated_organisation_anhang`.`table_name` AS `table_name`,`v_last_updated_organisation_anhang`.`name` AS `name`,`v_last_updated_organisation_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_anhang`.`last_visa` AS `last_visa`,`v_last_updated_organisation_anhang`.`last_updated` AS `last_updated`,`v_last_updated_organisation_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_anhang` union select `v_last_updated_person_anhang`.`table_name` AS `table_name`,`v_last_updated_person_anhang`.`name` AS `name`,`v_last_updated_person_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_person_anhang`.`last_visa` AS `last_visa`,`v_last_updated_person_anhang`.`last_updated` AS `last_updated`,`v_last_updated_person_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_person_anhang` union select `v_last_updated_settings`.`table_name` AS `table_name`,`v_last_updated_settings`.`name` AS `name`,`v_last_updated_settings`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_settings`.`last_visa` AS `last_visa`,`v_last_updated_settings`.`last_updated` AS `last_updated`,`v_last_updated_settings`.`last_updated_id` AS `last_updated_id` from `v_last_updated_settings` union select `v_last_updated_settings_category`.`table_name` AS `table_name`,`v_last_updated_settings_category`.`name` AS `name`,`v_last_updated_settings_category`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_settings_category`.`last_visa` AS `last_visa`,`v_last_updated_settings_category`.`last_updated` AS `last_updated`,`v_last_updated_settings_category`.`last_updated_id` AS `last_updated_id` from `v_last_updated_settings_category` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_last_updated_zutrittsberechtigung`
--

DROP VIEW IF EXISTS `v_last_updated_zutrittsberechtigung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_last_updated_zutrittsberechtigung` AS (select 'zutrittsberechtigung' AS `table_name`,'Zutrittsberechtigung' AS `name`,(select count(0) from `zutrittsberechtigung`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `zutrittsberechtigung` `t` order by `t`.`updated_date` desc limit 1) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_mandat`
--

DROP VIEW IF EXISTS `v_mandat`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_mandat` AS select `mv_mandat`.`anzeige_name` AS `anzeige_name`,`mv_mandat`.`id` AS `id`,`mv_mandat`.`person_id` AS `person_id`,`mv_mandat`.`organisation_id` AS `organisation_id`,`mv_mandat`.`art` AS `art`,`mv_mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mv_mandat`.`beschreibung` AS `beschreibung`,`mv_mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mv_mandat`.`quelle_url` AS `quelle_url`,`mv_mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mv_mandat`.`quelle` AS `quelle`,`mv_mandat`.`von` AS `von`,`mv_mandat`.`bis` AS `bis`,`mv_mandat`.`notizen` AS `notizen`,`mv_mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mv_mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mv_mandat`.`freigabe_visa` AS `freigabe_visa`,`mv_mandat`.`freigabe_datum` AS `freigabe_datum`,`mv_mandat`.`created_visa` AS `created_visa`,`mv_mandat`.`created_date` AS `created_date`,`mv_mandat`.`updated_visa` AS `updated_visa`,`mv_mandat`.`updated_date` AS `updated_date`,`mv_mandat`.`bis_unix` AS `bis_unix`,`mv_mandat`.`von_unix` AS `von_unix`,`mv_mandat`.`created_date_unix` AS `created_date_unix`,`mv_mandat`.`updated_date_unix` AS `updated_date_unix`,`mv_mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_mandat`.`wirksamkeit` AS `wirksamkeit`,`mv_mandat`.`wirksamkeit_index` AS `wirksamkeit_index`,`mv_mandat`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mv_mandat`.`refreshed_date` AS `refreshed_date` from `mv_mandat` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_mandat_jahr`
--

DROP VIEW IF EXISTS `v_mandat_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_mandat_jahr` AS select `mandat_jahr`.`id` AS `id`,`mandat_jahr`.`mandat_id` AS `mandat_id`,`mandat_jahr`.`jahr` AS `jahr`,`mandat_jahr`.`verguetung` AS `verguetung`,`mandat_jahr`.`beschreibung` AS `beschreibung`,`mandat_jahr`.`quelle_url` AS `quelle_url`,`mandat_jahr`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat_jahr`.`quelle` AS `quelle`,`mandat_jahr`.`notizen` AS `notizen`,`mandat_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat_jahr`.`autorisiert_visa` AS `autorisiert_visa`,`mandat_jahr`.`autorisiert_datum` AS `autorisiert_datum`,`mandat_jahr`.`freigabe_visa` AS `freigabe_visa`,`mandat_jahr`.`freigabe_datum` AS `freigabe_datum`,`mandat_jahr`.`created_visa` AS `created_visa`,`mandat_jahr`.`created_date` AS `created_date`,`mandat_jahr`.`updated_visa` AS `updated_visa`,`mandat_jahr`.`updated_date` AS `updated_date`,unix_timestamp(`mandat_jahr`.`created_date`) AS `created_date_unix`,unix_timestamp(`mandat_jahr`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`mandat_jahr`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`mandat_jahr`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`mandat_jahr`.`freigabe_datum`) AS `freigabe_datum_unix` from `mandat_jahr` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_mandat_medium_raw`
--

DROP VIEW IF EXISTS `v_mandat_medium_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_mandat_medium_raw` AS select concat(`mandat`.`id`,', ',`person`.`anzeige_name`,', ',`organisation`.`anzeige_name`,', ',`mandat`.`art`) AS `anzeige_name`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,if(((`organisation`.`vernehmlassung` in ('immer','punktuell')) and (`mandat`.`art` in ('geschaeftsfuehrend','vorstand'))),'hoch',if((((`organisation`.`vernehmlassung` in ('immer','punktuell')) and (`mandat`.`art` in ('taetig','beirat','finanziell'))) or (`mandat`.`art` in ('geschaeftsfuehrend','vorstand'))),'mittel','tief')) AS `wirksamkeit` from ((`v_mandat_simple` `mandat` join `v_organisation_medium_raw` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) join `v_person_simple` `person` on((`mandat`.`person_id` = `person`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_mandat_raw`
--

DROP VIEW IF EXISTS `v_mandat_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_mandat_raw` AS select `mandat`.`anzeige_name` AS `anzeige_name`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mandat`.`wirksamkeit` AS `wirksamkeit`,(case `mandat`.`wirksamkeit` when 'hoch' then 3 when 'mittel' then 2 when 'tief' then 1 else 0 end) AS `wirksamkeit_index`,`organisation`.`lobbyeinfluss` AS `organisation_lobbyeinfluss`,now() AS `refreshed_date` from (`v_mandat_medium_raw` `mandat` join `v_organisation_raw` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_mandat_simple`
--

DROP VIEW IF EXISTS `v_mandat_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_mandat_simple` AS select `mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,unix_timestamp(`mandat`.`bis`) AS `bis_unix`,unix_timestamp(`mandat`.`von`) AS `von_unix`,unix_timestamp(`mandat`.`created_date`) AS `created_date_unix`,unix_timestamp(`mandat`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`mandat`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`mandat`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`mandat`.`freigabe_datum`) AS `freigabe_datum_unix` from `mandat` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_mil_grad`
--

DROP VIEW IF EXISTS `v_mil_grad`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_mil_grad` AS select `mil_grad`.`id` AS `id`,`mil_grad`.`name` AS `name`,`mil_grad`.`name_fr` AS `name_fr`,`mil_grad`.`abkuerzung` AS `abkuerzung`,`mil_grad`.`abkuerzung_fr` AS `abkuerzung_fr`,`mil_grad`.`typ` AS `typ`,`mil_grad`.`ranghoehe` AS `ranghoehe`,`mil_grad`.`anzeigestufe` AS `anzeigestufe`,`mil_grad`.`created_visa` AS `created_visa`,`mil_grad`.`created_date` AS `created_date`,`mil_grad`.`updated_visa` AS `updated_visa`,`mil_grad`.`updated_date` AS `updated_date`,`mil_grad`.`name` AS `name_de`,`mil_grad`.`abkuerzung` AS `abkuerzung_de`,unix_timestamp(`mil_grad`.`created_date`) AS `created_date_unix`,unix_timestamp(`mil_grad`.`updated_date`) AS `updated_date_unix` from `mil_grad` order by `mil_grad`.`ranghoehe` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation`
--

DROP VIEW IF EXISTS `v_organisation`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation` AS select `mv_organisation`.`anzeige_name` AS `anzeige_name`,`mv_organisation`.`anzeige_mixed` AS `anzeige_mixed`,`mv_organisation`.`anzeige_bimixed` AS `anzeige_bimixed`,`mv_organisation`.`searchable_name` AS `searchable_name`,`mv_organisation`.`anzeige_name_de` AS `anzeige_name_de`,`mv_organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`mv_organisation`.`name` AS `name`,`mv_organisation`.`id` AS `id`,`mv_organisation`.`name_de` AS `name_de`,`mv_organisation`.`name_fr` AS `name_fr`,`mv_organisation`.`name_it` AS `name_it`,`mv_organisation`.`uid` AS `uid`,`mv_organisation`.`ort` AS `ort`,`mv_organisation`.`abkuerzung_de` AS `abkuerzung_de`,`mv_organisation`.`alias_namen_de` AS `alias_namen_de`,`mv_organisation`.`abkuerzung_fr` AS `abkuerzung_fr`,`mv_organisation`.`alias_namen_fr` AS `alias_namen_fr`,`mv_organisation`.`abkuerzung_it` AS `abkuerzung_it`,`mv_organisation`.`alias_namen_it` AS `alias_namen_it`,`mv_organisation`.`land_id` AS `land_id`,`mv_organisation`.`interessenraum_id` AS `interessenraum_id`,`mv_organisation`.`rechtsform` AS `rechtsform`,`mv_organisation`.`rechtsform_handelsregister` AS `rechtsform_handelsregister`,`mv_organisation`.`rechtsform_zefix` AS `rechtsform_zefix`,`mv_organisation`.`typ` AS `typ`,`mv_organisation`.`vernehmlassung` AS `vernehmlassung`,`mv_organisation`.`interessengruppe_id` AS `interessengruppe_id`,`mv_organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`mv_organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`mv_organisation`.`branche_id` AS `branche_id`,`mv_organisation`.`homepage` AS `homepage`,`mv_organisation`.`handelsregister_url` AS `handelsregister_url`,`mv_organisation`.`twitter_name` AS `twitter_name`,`mv_organisation`.`beschreibung` AS `beschreibung`,`mv_organisation`.`beschreibung_fr` AS `beschreibung_fr`,`mv_organisation`.`sekretariat` AS `sekretariat`,`mv_organisation`.`adresse_strasse` AS `adresse_strasse`,`mv_organisation`.`adresse_zusatz` AS `adresse_zusatz`,`mv_organisation`.`adresse_plz` AS `adresse_plz`,`mv_organisation`.`notizen` AS `notizen`,`mv_organisation`.`updated_by_import` AS `updated_by_import`,`mv_organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_organisation`.`freigabe_visa` AS `freigabe_visa`,`mv_organisation`.`freigabe_datum` AS `freigabe_datum`,`mv_organisation`.`created_visa` AS `created_visa`,`mv_organisation`.`created_date` AS `created_date`,`mv_organisation`.`updated_visa` AS `updated_visa`,`mv_organisation`.`updated_date` AS `updated_date`,`mv_organisation`.`created_date_unix` AS `created_date_unix`,`mv_organisation`.`updated_date_unix` AS `updated_date_unix`,`mv_organisation`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_organisation`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_organisation`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_organisation`.`branche` AS `branche`,`mv_organisation`.`branche_de` AS `branche_de`,`mv_organisation`.`branche_fr` AS `branche_fr`,`mv_organisation`.`interessengruppe` AS `interessengruppe`,`mv_organisation`.`interessengruppe_de` AS `interessengruppe_de`,`mv_organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`mv_organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`mv_organisation`.`interessengruppe_branche_de` AS `interessengruppe_branche_de`,`mv_organisation`.`interessengruppe_branche_fr` AS `interessengruppe_branche_fr`,`mv_organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`mv_organisation`.`interessengruppe2` AS `interessengruppe2`,`mv_organisation`.`interessengruppe2_de` AS `interessengruppe2_de`,`mv_organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`mv_organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`mv_organisation`.`interessengruppe2_branche_de` AS `interessengruppe2_branche_de`,`mv_organisation`.`interessengruppe2_branche_fr` AS `interessengruppe2_branche_fr`,`mv_organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`mv_organisation`.`interessengruppe3` AS `interessengruppe3`,`mv_organisation`.`interessengruppe3_de` AS `interessengruppe3_de`,`mv_organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`mv_organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`mv_organisation`.`interessengruppe3_branche_de` AS `interessengruppe3_branche_de`,`mv_organisation`.`interessengruppe3_branche_fr` AS `interessengruppe3_branche_fr`,`mv_organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`mv_organisation`.`refreshed_date` AS `refreshed_date`,`mv_organisation`.`land` AS `land`,`mv_organisation`.`interessenraum` AS `interessenraum`,`mv_organisation`.`interessenraum_de` AS `interessenraum_de`,`mv_organisation`.`interessenraum_fr` AS `interessenraum_fr`,`mv_organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`mv_organisation`.`jahr` AS `jahr`,`mv_organisation`.`umsatz` AS `umsatz`,`mv_organisation`.`gewinn` AS `gewinn`,`mv_organisation`.`kapital` AS `kapital`,`mv_organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`mv_organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`mv_organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`mv_organisation`.`quelle_url` AS `quelle_url`,`mv_organisation`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`mv_organisation`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`mv_organisation`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`mv_organisation`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`mv_organisation`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`mv_organisation`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`mv_organisation`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`mv_organisation`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`mv_organisation`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`mv_organisation`.`lobbyeinfluss` AS `lobbyeinfluss`,`mv_organisation`.`lobbyeinfluss_index` AS `lobbyeinfluss_index` from `mv_organisation` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_anhang`
--

DROP VIEW IF EXISTS `v_organisation_anhang`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_anhang` AS select `organisation_anhang`.`organisation_id` AS `organisation_id2`,`organisation_anhang`.`id` AS `id`,`organisation_anhang`.`organisation_id` AS `organisation_id`,`organisation_anhang`.`datei` AS `datei`,`organisation_anhang`.`dateiname` AS `dateiname`,`organisation_anhang`.`dateierweiterung` AS `dateierweiterung`,`organisation_anhang`.`dateiname_voll` AS `dateiname_voll`,`organisation_anhang`.`mime_type` AS `mime_type`,`organisation_anhang`.`encoding` AS `encoding`,`organisation_anhang`.`beschreibung` AS `beschreibung`,`organisation_anhang`.`freigabe_visa` AS `freigabe_visa`,`organisation_anhang`.`freigabe_datum` AS `freigabe_datum`,`organisation_anhang`.`created_visa` AS `created_visa`,`organisation_anhang`.`created_date` AS `created_date`,`organisation_anhang`.`updated_visa` AS `updated_visa`,`organisation_anhang`.`updated_date` AS `updated_date` from `organisation_anhang` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung`
--

DROP VIEW IF EXISTS `v_organisation_beziehung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung` AS select `organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`beschreibung` AS `beschreibung`,`organisation_beziehung`.`beschreibung_fr` AS `beschreibung_fr`,`organisation_beziehung`.`quelle_url` AS `quelle_url`,`organisation_beziehung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_beziehung`.`quelle` AS `quelle`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_beziehung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_beziehung`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_beziehung`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_beziehung`.`freigabe_visa` AS `freigabe_visa`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date`,`organisation`.`name_de` AS `organisation_name`,`organisation`.`name_fr` AS `organisation_name_fr`,`ziel_organisation`.`name_de` AS `ziel_organisation_name`,`ziel_organisation`.`name_fr` AS `ziel_organisation_name_fr`,unix_timestamp(`organisation_beziehung`.`bis`) AS `bis_unix`,unix_timestamp(`organisation_beziehung`.`von`) AS `von_unix`,unix_timestamp(`organisation_beziehung`.`created_date`) AS `created_date_unix`,unix_timestamp(`organisation_beziehung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`organisation_beziehung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`organisation_beziehung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`organisation_beziehung`.`freigabe_datum`) AS `freigabe_datum_unix` from ((`organisation_beziehung` left join `v_organisation_simple` `organisation` on((`organisation`.`id` = `organisation_beziehung`.`organisation_id`))) left join `v_organisation_simple` `ziel_organisation` on((`ziel_organisation`.`id` = `organisation_beziehung`.`ziel_organisation_id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung_arbeitet_fuer`
--

DROP VIEW IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung_arbeitet_fuer` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung_auftraggeber_fuer`
--

DROP VIEW IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung_auftraggeber_fuer` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung_mitglied_von`
--

DROP VIEW IF EXISTS `v_organisation_beziehung_mitglied_von`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung_mitglied_von` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung_mitglieder`
--

DROP VIEW IF EXISTS `v_organisation_beziehung_mitglieder`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung_mitglieder` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung_muttergesellschaft`
--

DROP VIEW IF EXISTS `v_organisation_beziehung_muttergesellschaft`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung_muttergesellschaft` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'tochtergesellschaft von') order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_beziehung_tochtergesellschaften`
--

DROP VIEW IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_beziehung_tochtergesellschaften` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'tochtergesellschaft von') order by `organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_jahr`
--

DROP VIEW IF EXISTS `v_organisation_jahr`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_jahr` AS select `organisation_jahr`.`id` AS `id`,`organisation_jahr`.`organisation_id` AS `organisation_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`organisation_jahr`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_jahr`.`quelle` AS `quelle`,`organisation_jahr`.`notizen` AS `notizen`,`organisation_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_jahr`.`freigabe_visa` AS `freigabe_visa`,`organisation_jahr`.`freigabe_datum` AS `freigabe_datum`,`organisation_jahr`.`created_visa` AS `created_visa`,`organisation_jahr`.`created_date` AS `created_date`,`organisation_jahr`.`updated_visa` AS `updated_visa`,`organisation_jahr`.`updated_date` AS `updated_date`,unix_timestamp(`organisation_jahr`.`created_date`) AS `created_date_unix`,unix_timestamp(`organisation_jahr`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`organisation_jahr`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`organisation_jahr`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`organisation_jahr`.`freigabe_datum`) AS `freigabe_datum_unix` from `organisation_jahr` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_jahr_last`
--

DROP VIEW IF EXISTS `v_organisation_jahr_last`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_jahr_last` AS select max(`organisation_jahr`.`jahr`) AS `max_jahr`,`organisation_jahr`.`id` AS `id`,`organisation_jahr`.`organisation_id` AS `organisation_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`organisation_jahr`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_jahr`.`quelle` AS `quelle`,`organisation_jahr`.`notizen` AS `notizen`,`organisation_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_jahr`.`freigabe_visa` AS `freigabe_visa`,`organisation_jahr`.`freigabe_datum` AS `freigabe_datum`,`organisation_jahr`.`created_visa` AS `created_visa`,`organisation_jahr`.`created_date` AS `created_date`,`organisation_jahr`.`updated_visa` AS `updated_visa`,`organisation_jahr`.`updated_date` AS `updated_date` from `organisation_jahr` group by `organisation_jahr`.`organisation_id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_lobbyeinfluss_raw`
--

DROP VIEW IF EXISTS `v_organisation_lobbyeinfluss_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_lobbyeinfluss_raw` AS select `organisation`.`id` AS `id`,count(distinct `interessenbindung_tief`.`id`) AS `anzahl_interessenbindung_tief`,count(distinct `interessenbindung_mittel`.`id`) AS `anzahl_interessenbindung_mittel`,count(distinct `interessenbindung_hoch`.`id`) AS `anzahl_interessenbindung_hoch`,count(distinct `interessenbindung_tief_nach_wahl`.`id`) AS `anzahl_interessenbindung_tief_nach_wahl`,count(distinct `interessenbindung_mittel_nach_wahl`.`id`) AS `anzahl_interessenbindung_mittel_nach_wahl`,count(distinct `interessenbindung_hoch_nach_wahl`.`id`) AS `anzahl_interessenbindung_hoch_nach_wahl`,count(distinct `mandat_tief`.`id`) AS `anzahl_mandat_tief`,count(distinct `mandat_mittel`.`id`) AS `anzahl_mandat_mittel`,count(distinct `mandat_hoch`.`id`) AS `anzahl_mandat_hoch`,if(((count(distinct `interessenbindung_hoch_nach_wahl`.`id`) > 0) or (count(distinct `interessenbindung_hoch`.`id`) > 1) or ((count(distinct `interessenbindung_hoch`.`id`) > 0) and (count(distinct `mandat_hoch`.`id`) > 0))),'sehr hoch',if(((count(distinct `interessenbindung_hoch`.`id`) > 0) or ((count(distinct `interessenbindung_mittel`.`id`) > 0) and (count(distinct `mandat_mittel`.`id`) > 0))),'hoch',if(((count(distinct `interessenbindung_mittel`.`id`) > 0) or (count(distinct `mandat_hoch`.`id`) > 0)),'mittel','tief'))) AS `lobbyeinfluss`,now() AS `refreshed_date` from (((((((((`organisation` left join `v_interessenbindung_medium_raw` `interessenbindung_hoch` on(((`organisation`.`id` = `interessenbindung_hoch`.`organisation_id`) and (isnull(`interessenbindung_hoch`.`bis`) or (`interessenbindung_hoch`.`bis` >= now())) and (`interessenbindung_hoch`.`wirksamkeit` = 'hoch')))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel` on(((`organisation`.`id` = `interessenbindung_mittel`.`organisation_id`) and (isnull(`interessenbindung_mittel`.`bis`) or (`interessenbindung_mittel`.`bis` >= now())) and (`interessenbindung_mittel`.`wirksamkeit` = 'mittel')))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief` on(((`organisation`.`id` = `interessenbindung_tief`.`organisation_id`) and (isnull(`interessenbindung_tief`.`bis`) or (`interessenbindung_tief`.`bis` >= now())) and (`interessenbindung_tief`.`wirksamkeit` = 'tief')))) left join `v_interessenbindung_medium_raw` `interessenbindung_hoch_nach_wahl` on(((`organisation`.`id` = `interessenbindung_hoch_nach_wahl`.`organisation_id`) and (isnull(`interessenbindung_hoch_nach_wahl`.`bis`) or (`interessenbindung_hoch_nach_wahl`.`bis` >= now())) and (`interessenbindung_hoch_nach_wahl`.`wirksamkeit` = 'hoch') and (`interessenbindung_hoch_nach_wahl`.`von` > `interessenbindung_hoch_nach_wahl`.`parlamentarier_im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel_nach_wahl` on(((`organisation`.`id` = `interessenbindung_mittel_nach_wahl`.`organisation_id`) and (isnull(`interessenbindung_mittel_nach_wahl`.`bis`) or (`interessenbindung_mittel_nach_wahl`.`bis` >= now())) and (`interessenbindung_mittel_nach_wahl`.`wirksamkeit` = 'mittel') and (`interessenbindung_mittel_nach_wahl`.`von` > `interessenbindung_mittel_nach_wahl`.`parlamentarier_im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief_nach_wahl` on(((`organisation`.`id` = `interessenbindung_tief_nach_wahl`.`organisation_id`) and (isnull(`interessenbindung_tief_nach_wahl`.`bis`) or (`interessenbindung_tief_nach_wahl`.`bis` >= now())) and (`interessenbindung_tief_nach_wahl`.`wirksamkeit` = 'tief') and (`interessenbindung_tief_nach_wahl`.`von` > `interessenbindung_tief_nach_wahl`.`parlamentarier_im_rat_seit`)))) left join `v_mandat_medium_raw` `mandat_hoch` on(((`organisation`.`id` = `mandat_hoch`.`organisation_id`) and (isnull(`mandat_hoch`.`bis`) or (`mandat_hoch`.`bis` >= now())) and (`mandat_hoch`.`wirksamkeit` = 'hoch')))) left join `v_mandat_medium_raw` `mandat_mittel` on(((`organisation`.`id` = `mandat_mittel`.`organisation_id`) and (isnull(`mandat_mittel`.`bis`) or (`mandat_mittel`.`bis` >= now())) and (`mandat_mittel`.`wirksamkeit` = 'mittel')))) left join `v_mandat_medium_raw` `mandat_tief` on(((`organisation`.`id` = `mandat_tief`.`organisation_id`) and (isnull(`mandat_tief`.`bis`) or (`mandat_tief`.`bis` >= now())) and (`mandat_tief`.`wirksamkeit` = 'tief')))) group by `organisation`.`id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_medium_raw`
--

DROP VIEW IF EXISTS `v_organisation_medium_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_medium_raw` AS select `organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_mixed` AS `anzeige_mixed`,`organisation`.`anzeige_bimixed` AS `anzeige_bimixed`,`organisation`.`searchable_name` AS `searchable_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`uid` AS `uid`,`organisation`.`ort` AS `ort`,`organisation`.`abkuerzung_de` AS `abkuerzung_de`,`organisation`.`alias_namen_de` AS `alias_namen_de`,`organisation`.`abkuerzung_fr` AS `abkuerzung_fr`,`organisation`.`alias_namen_fr` AS `alias_namen_fr`,`organisation`.`abkuerzung_it` AS `abkuerzung_it`,`organisation`.`alias_namen_it` AS `alias_namen_it`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`rechtsform_handelsregister` AS `rechtsform_handelsregister`,`organisation`.`rechtsform_zefix` AS `rechtsform_zefix`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`beschreibung_fr` AS `beschreibung_fr`,`organisation`.`sekretariat` AS `sekretariat`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`notizen` AS `notizen`,`organisation`.`updated_by_import` AS `updated_by_import`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`created_date_unix` AS `created_date_unix`,`organisation`.`updated_date_unix` AS `updated_date_unix`,`organisation`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`organisation`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`organisation`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`branche`.`anzeige_name` AS `branche`,`branche`.`anzeige_name_de` AS `branche_de`,`branche`.`anzeige_name_de` AS `branche_fr`,`interessengruppe1`.`anzeige_name` AS `interessengruppe`,`interessengruppe1`.`anzeige_name_de` AS `interessengruppe_de`,`interessengruppe1`.`anzeige_name_fr` AS `interessengruppe_fr`,`interessengruppe1`.`branche` AS `interessengruppe_branche`,`interessengruppe1`.`branche_de` AS `interessengruppe_branche_de`,`interessengruppe1`.`branche_fr` AS `interessengruppe_branche_fr`,`interessengruppe1`.`branche_id` AS `interessengruppe_branche_id`,`interessengruppe2`.`anzeige_name` AS `interessengruppe2`,`interessengruppe2`.`anzeige_name_de` AS `interessengruppe2_de`,`interessengruppe2`.`anzeige_name_fr` AS `interessengruppe2_fr`,`interessengruppe2`.`branche` AS `interessengruppe2_branche`,`interessengruppe2`.`branche_de` AS `interessengruppe2_branche_de`,`interessengruppe2`.`branche_fr` AS `interessengruppe2_branche_fr`,`interessengruppe2`.`branche_id` AS `interessengruppe2_branche_id`,`interessengruppe3`.`anzeige_name` AS `interessengruppe3`,`interessengruppe3`.`anzeige_name_de` AS `interessengruppe3_de`,`interessengruppe3`.`anzeige_name_fr` AS `interessengruppe3_fr`,`interessengruppe3`.`branche` AS `interessengruppe3_branche`,`interessengruppe3`.`branche_de` AS `interessengruppe3_branche_de`,`interessengruppe3`.`branche_fr` AS `interessengruppe3_branche_fr`,`interessengruppe3`.`branche_id` AS `interessengruppe3_branche_id`,now() AS `refreshed_date` from ((((`v_organisation_simple` `organisation` left join `v_branche` `branche` on((`branche`.`id` = `organisation`.`branche_id`))) left join `v_interessengruppe` `interessengruppe1` on((`interessengruppe1`.`id` = `organisation`.`interessengruppe_id`))) left join `v_interessengruppe` `interessengruppe2` on((`interessengruppe2`.`id` = `organisation`.`interessengruppe2_id`))) left join `v_interessengruppe` `interessengruppe3` on((`interessengruppe3`.`id` = `organisation`.`interessengruppe3_id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_parlamentarier`
--

DROP VIEW IF EXISTS `v_organisation_parlamentarier`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_parlamentarier` AS select `parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`partei_fr` AS `partei_fr`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_interessenbindung_simple` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_parlamentarier_beide`
--

DROP VIEW IF EXISTS `v_organisation_parlamentarier_beide`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_parlamentarier_beide` AS select 'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `person_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from (`v_interessenbindung_simple` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) union select 'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`person`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,least(ifnull(`zutrittsberechtigung`.`bis`,`mandat`.`bis`),ifnull(`mandat`.`bis`,`zutrittsberechtigung`.`bis`)) AS `Name_exp_36`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from (((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` join `v_mandat_simple` `mandat` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) join `v_parlamentarier` `parlamentarier` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_parlamentarier_beide_indirekt`
--

DROP VIEW IF EXISTS `v_organisation_parlamentarier_beide_indirekt`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_parlamentarier_beide_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`verbindung` AS `verbindung`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`parlamentarier_name` AS `parlamentarier_name`,`organisation_parlamentarier`.`ratstyp` AS `ratstyp`,`organisation_parlamentarier`.`kanton` AS `kanton`,`organisation_parlamentarier`.`partei_id` AS `partei_id`,`organisation_parlamentarier`.`partei` AS `partei`,`organisation_parlamentarier`.`kommissionen` AS `kommissionen`,`organisation_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`organisation_parlamentarier`.`person_id` AS `person_id`,`organisation_parlamentarier`.`zutrittsberechtigter` AS `zutrittsberechtigter`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`funktion_im_gremium` AS `funktion_im_gremium`,`organisation_parlamentarier`.`beschreibung` AS `beschreibung`,`organisation_parlamentarier`.`von` AS `von`,`organisation_parlamentarier`.`bis` AS `bis`,unix_timestamp(`organisation_parlamentarier`.`bis`) AS `bis_unix`,NULL AS `zwischen_organisation_id`,NULL AS `zwischen_organisation_art`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id`,`organisation_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`organisation_parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`organisation_parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from `v_organisation_parlamentarier_beide` `organisation_parlamentarier` union select concat('indirekt: ',`organisation_beziehung`.`art`) AS `beziehung`,'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `person_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,least(ifnull(`interessenbindung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y'))) AS `bis`,unix_timestamp(least(ifnull(`interessenbindung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')))) AS `bis_unix`,`organisation_beziehung`.`organisation_id` AS `zwischen_organisation_id`,`organisation_beziehung`.`art` AS `zwischen_organisation_art`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from ((`v_parlamentarier` `parlamentarier` join `v_interessenbindung_simple` `interessenbindung` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) join `v_organisation_beziehung` `organisation_beziehung` on(((`organisation_beziehung`.`art` in ('arbeitet fuer','tochtergesellschaft von')) and (`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`)))) union select concat('indirekt: ',`organisation_beziehung`.`art`) AS `beziehung`,'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`person`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,least(ifnull(`zutrittsberechtigung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`mandat`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y'))) AS `bis`,unix_timestamp(least(ifnull(`zutrittsberechtigung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`mandat`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')))) AS `bis_unix`,`organisation_beziehung`.`organisation_id` AS `zwischen_organisation_id`,`organisation_beziehung`.`art` AS `zwischen_organisation_art`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from ((((`v_parlamentarier` `parlamentarier` join `v_zutrittsberechtigung_simple` `zutrittsberechtigung` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`))) join `v_mandat` `mandat` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) join `v_organisation_beziehung` `organisation_beziehung` on(((`organisation_beziehung`.`art` in ('arbeitet fuer','tochtergesellschaft von')) and (`organisation_beziehung`.`organisation_id` = `mandat`.`organisation_id`)))) union select concat('indirekt: ',`organisation_beziehung`.`art`,', reverse') AS `beziehung`,'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `person_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,least(ifnull(`interessenbindung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y'))) AS `bis`,unix_timestamp(least(ifnull(`interessenbindung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')))) AS `bis_unix`,`organisation_beziehung`.`ziel_organisation_id` AS `zwischen_organisation_id`,'tochtergesellschaften' AS `zwischen_organisation_art`,`organisation_beziehung`.`organisation_id` AS `connector_organisation_id`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from ((`v_parlamentarier` `parlamentarier` join `v_interessenbindung_simple` `interessenbindung` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) join `v_organisation_beziehung` `organisation_beziehung` on(((`organisation_beziehung`.`art` = 'tochtergesellschaft von') and (`organisation_beziehung`.`ziel_organisation_id` = `interessenbindung`.`organisation_id`)))) union select concat('indirekt: ',`organisation_beziehung`.`art`,', reverse') AS `beziehung`,'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`person`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,least(ifnull(`zutrittsberechtigung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`mandat`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y'))) AS `bis`,unix_timestamp(least(ifnull(`zutrittsberechtigung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`mandat`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')),ifnull(`organisation_beziehung`.`bis`,str_to_date('01.01.2038','%d.%m.%Y')))) AS `bis_unix`,`organisation_beziehung`.`ziel_organisation_id` AS `zwischen_organisation_id`,'tochtergesellschaften' AS `zwischen_organisation_art`,`organisation_beziehung`.`organisation_id` AS `connector_organisation_id`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix` from ((((`v_parlamentarier` `parlamentarier` join `v_zutrittsberechtigung_simple` `zutrittsberechtigung` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`))) join `v_mandat` `mandat` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) join `v_organisation_beziehung` `organisation_beziehung` on(((`organisation_beziehung`.`art` = 'tochtergesellschaft von') and (`organisation_beziehung`.`ziel_organisation_id` = `mandat`.`organisation_id`)))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_parlamentarier_indirekt`
--

DROP VIEW IF EXISTS `v_organisation_parlamentarier_indirekt`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_parlamentarier_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`parlamentarier_name` AS `parlamentarier_name`,`organisation_parlamentarier`.`name` AS `name`,`organisation_parlamentarier`.`nachname` AS `nachname`,`organisation_parlamentarier`.`vorname` AS `vorname`,`organisation_parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`organisation_parlamentarier`.`rat_id` AS `rat_id`,`organisation_parlamentarier`.`kanton_id` AS `kanton_id`,`organisation_parlamentarier`.`kommissionen` AS `kommissionen`,`organisation_parlamentarier`.`partei_id` AS `partei_id`,`organisation_parlamentarier`.`parteifunktion` AS `parteifunktion`,`organisation_parlamentarier`.`fraktion_id` AS `fraktion_id`,`organisation_parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`organisation_parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`organisation_parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`organisation_parlamentarier`.`ratswechsel` AS `ratswechsel`,`organisation_parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`organisation_parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`organisation_parlamentarier`.`beruf` AS `beruf`,`organisation_parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`organisation_parlamentarier`.`zivilstand` AS `zivilstand`,`organisation_parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`organisation_parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`organisation_parlamentarier`.`geschlecht` AS `geschlecht`,`organisation_parlamentarier`.`geburtstag` AS `geburtstag`,`organisation_parlamentarier`.`photo` AS `photo`,`organisation_parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`organisation_parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`organisation_parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`organisation_parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`organisation_parlamentarier`.`kleinbild` AS `kleinbild`,`organisation_parlamentarier`.`sitzplatz` AS `sitzplatz`,`organisation_parlamentarier`.`email` AS `email`,`organisation_parlamentarier`.`homepage` AS `homepage`,`organisation_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`organisation_parlamentarier`.`twitter_name` AS `twitter_name`,`organisation_parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`organisation_parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`organisation_parlamentarier`.`facebook_name` AS `facebook_name`,`organisation_parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`organisation_parlamentarier`.`adresse_firma` AS `adresse_firma`,`organisation_parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`organisation_parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`organisation_parlamentarier`.`adresse_plz` AS `adresse_plz`,`organisation_parlamentarier`.`adresse_ort` AS `adresse_ort`,`organisation_parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`organisation_parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`organisation_parlamentarier`.`kanton` AS `kanton`,`organisation_parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`organisation_parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`organisation_parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`organisation_parlamentarier`.`partei` AS `partei`,`organisation_parlamentarier`.`partei_fr` AS `partei_fr`,`organisation_parlamentarier`.`fraktion` AS `fraktion`,`organisation_parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`organisation_parlamentarier`.`id` AS `id`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`organisation_id` AS `organisation_id`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`funktion_im_gremium` AS `funktion_im_gremium`,`organisation_parlamentarier`.`deklarationstyp` AS `deklarationstyp`,`organisation_parlamentarier`.`status` AS `status`,`organisation_parlamentarier`.`behoerden_vertreter` AS `behoerden_vertreter`,`organisation_parlamentarier`.`beschreibung` AS `beschreibung`,`organisation_parlamentarier`.`beschreibung_fr` AS `beschreibung_fr`,`organisation_parlamentarier`.`quelle_url` AS `quelle_url`,`organisation_parlamentarier`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_parlamentarier`.`quelle` AS `quelle`,`organisation_parlamentarier`.`von` AS `von`,`organisation_parlamentarier`.`bis` AS `bis`,`organisation_parlamentarier`.`notizen` AS `notizen`,`organisation_parlamentarier`.`updated_by_import` AS `updated_by_import`,`organisation_parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`organisation_parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`organisation_parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`organisation_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`organisation_parlamentarier`.`created_visa` AS `created_visa`,`organisation_parlamentarier`.`created_date` AS `created_date`,`organisation_parlamentarier`.`updated_visa` AS `updated_visa`,`organisation_parlamentarier`.`updated_date` AS `updated_date`,`organisation_parlamentarier`.`bis_unix` AS `bis_unix`,`organisation_parlamentarier`.`von_unix` AS `von_unix`,`organisation_parlamentarier`.`created_date_unix` AS `created_date_unix`,`organisation_parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`organisation_parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`organisation_parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`organisation_parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id` from `v_organisation_parlamentarier` `organisation_parlamentarier` union select 'indirekt' AS `beziehung`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`partei_fr` AS `partei_fr`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`beschreibung_fr` AS `beschreibung_fr`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`updated_by_import` AS `updated_by_import`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id` from ((`v_organisation_beziehung` `organisation_beziehung` join `v_interessenbindung_simple` `interessenbindung` on((`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`parlamentarier_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_raw`
--

DROP VIEW IF EXISTS `v_organisation_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_raw` AS select `organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_mixed` AS `anzeige_mixed`,`organisation`.`anzeige_bimixed` AS `anzeige_bimixed`,`organisation`.`searchable_name` AS `searchable_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`uid` AS `uid`,`organisation`.`ort` AS `ort`,`organisation`.`abkuerzung_de` AS `abkuerzung_de`,`organisation`.`alias_namen_de` AS `alias_namen_de`,`organisation`.`abkuerzung_fr` AS `abkuerzung_fr`,`organisation`.`alias_namen_fr` AS `alias_namen_fr`,`organisation`.`abkuerzung_it` AS `abkuerzung_it`,`organisation`.`alias_namen_it` AS `alias_namen_it`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`rechtsform_handelsregister` AS `rechtsform_handelsregister`,`organisation`.`rechtsform_zefix` AS `rechtsform_zefix`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`beschreibung_fr` AS `beschreibung_fr`,`organisation`.`sekretariat` AS `sekretariat`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`notizen` AS `notizen`,`organisation`.`updated_by_import` AS `updated_by_import`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`created_date_unix` AS `created_date_unix`,`organisation`.`updated_date_unix` AS `updated_date_unix`,`organisation`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`organisation`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`organisation`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`branche` AS `branche`,`organisation`.`branche_de` AS `branche_de`,`organisation`.`branche_fr` AS `branche_fr`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_de` AS `interessengruppe_de`,`organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_de` AS `interessengruppe_branche_de`,`organisation`.`interessengruppe_branche_fr` AS `interessengruppe_branche_fr`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_de` AS `interessengruppe2_de`,`organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_de` AS `interessengruppe2_branche_de`,`organisation`.`interessengruppe2_branche_fr` AS `interessengruppe2_branche_fr`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_de` AS `interessengruppe3_de`,`organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_de` AS `interessengruppe3_branche_de`,`organisation`.`interessengruppe3_branche_fr` AS `interessengruppe3_branche_fr`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`refreshed_date` AS `refreshed_date`,`country`.`name_de` AS `land`,`interessenraum`.`anzeige_name` AS `interessenraum`,`interessenraum`.`anzeige_name_de` AS `interessenraum_de`,`interessenraum`.`anzeige_name_fr` AS `interessenraum_fr`,`organisation_jahr`.`id` AS `organisation_jahr_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`lobbyeinfluss`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`lobbyeinfluss`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`lobbyeinfluss`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`lobbyeinfluss`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`lobbyeinfluss`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`lobbyeinfluss`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`lobbyeinfluss`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`lobbyeinfluss`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`lobbyeinfluss`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`lobbyeinfluss`.`lobbyeinfluss` AS `lobbyeinfluss`,(case `lobbyeinfluss`.`lobbyeinfluss` when 'sehr hoch' then 4 when 'hoch' then 3 when 'mittel' then 2 when 'tief' then 1 else 0 end) AS `lobbyeinfluss_index` from ((((`v_organisation_medium_raw` `organisation` left join `v_organisation_lobbyeinfluss_raw` `lobbyeinfluss` on((`lobbyeinfluss`.`id` = `organisation`.`id`))) left join `v_country` `country` on((`country`.`id` = `organisation`.`land_id`))) left join `v_interessenraum` `interessenraum` on((`interessenraum`.`id` = `organisation`.`interessenraum_id`))) left join `v_organisation_jahr_last` `organisation_jahr` on((`organisation_jahr`.`organisation_id` = `organisation`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_simple`
--

DROP VIEW IF EXISTS `v_organisation_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_simple` AS select concat_ws('; ',`organisation`.`name_de`,`organisation`.`name_fr`,`organisation`.`name_it`) AS `anzeige_name`,concat_ws('; ',`organisation`.`name_de`,`organisation`.`name_fr`,`organisation`.`name_it`) AS `anzeige_mixed`,concat_ws('; ',`organisation`.`name_de`,`organisation`.`name_fr`) AS `anzeige_bimixed`,concat_ws('; ',`organisation`.`name_de`,`organisation`.`abkuerzung_de`,`organisation`.`name_fr`,`organisation`.`abkuerzung_fr`,`organisation`.`uid`,left(`organisation`.`alias_namen_de`,75),left(`organisation`.`alias_namen_fr`,75)) AS `searchable_name`,`organisation`.`name_de` AS `anzeige_name_de`,`organisation`.`name_fr` AS `anzeige_name_fr`,concat_ws('; ',`organisation`.`name_de`,`organisation`.`name_fr`,`organisation`.`name_it`) AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`uid` AS `uid`,`organisation`.`ort` AS `ort`,`organisation`.`abkuerzung_de` AS `abkuerzung_de`,`organisation`.`alias_namen_de` AS `alias_namen_de`,`organisation`.`abkuerzung_fr` AS `abkuerzung_fr`,`organisation`.`alias_namen_fr` AS `alias_namen_fr`,`organisation`.`abkuerzung_it` AS `abkuerzung_it`,`organisation`.`alias_namen_it` AS `alias_namen_it`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`rechtsform_handelsregister` AS `rechtsform_handelsregister`,`organisation`.`rechtsform_zefix` AS `rechtsform_zefix`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`beschreibung_fr` AS `beschreibung_fr`,`organisation`.`sekretariat` AS `sekretariat`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`notizen` AS `notizen`,`organisation`.`updated_by_import` AS `updated_by_import`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,unix_timestamp(`organisation`.`created_date`) AS `created_date_unix`,unix_timestamp(`organisation`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`organisation`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`organisation`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`organisation`.`freigabe_datum`) AS `freigabe_datum_unix` from `organisation` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_organisation_zutrittsberechtigung`
--

DROP VIEW IF EXISTS `v_organisation_zutrittsberechtigung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_organisation_zutrittsberechtigung` AS select `zutrittsberechtigung`.`anzeige_name` AS `anzeige_name`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`name` AS `name`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`nachname` AS `nachname`,`zutrittsberechtigung`.`vorname` AS `vorname`,`zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`beruf` AS `beruf`,`zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`zutrittsberechtigung`.`partei_id` AS `partei_id`,`zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`zutrittsberechtigung`.`email` AS `email`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`zutrittsberechtigung`.`partei` AS `partei`,`zutrittsberechtigung`.`parlamentarier_name` AS `parlamentarier_name`,`zutrittsberechtigung`.`bis_unix` AS `zutrittsberechtigung_bis_unix`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_mandat_simple` `mandat` join `v_zutrittsberechtigung` `zutrittsberechtigung` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) order by `zutrittsberechtigung`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier`
--

DROP VIEW IF EXISTS `v_parlamentarier`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier` AS select `mv_parlamentarier`.`anzeige_name` AS `anzeige_name`,`mv_parlamentarier`.`anzeige_name_de` AS `anzeige_name_de`,`mv_parlamentarier`.`anzeige_name_fr` AS `anzeige_name_fr`,`mv_parlamentarier`.`name` AS `name`,`mv_parlamentarier`.`name_de` AS `name_de`,`mv_parlamentarier`.`name_fr` AS `name_fr`,`mv_parlamentarier`.`id` AS `id`,`mv_parlamentarier`.`nachname` AS `nachname`,`mv_parlamentarier`.`vorname` AS `vorname`,`mv_parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`mv_parlamentarier`.`rat_id` AS `rat_id`,`mv_parlamentarier`.`kanton_id` AS `kanton_id`,`mv_parlamentarier`.`kommissionen` AS `kommissionen`,`mv_parlamentarier`.`partei_id` AS `partei_id`,`mv_parlamentarier`.`parteifunktion` AS `parteifunktion`,`mv_parlamentarier`.`fraktion_id` AS `fraktion_id`,`mv_parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`mv_parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`mv_parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`mv_parlamentarier`.`ratswechsel` AS `ratswechsel`,`mv_parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`mv_parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`mv_parlamentarier`.`beruf` AS `beruf`,`mv_parlamentarier`.`beruf_fr` AS `beruf_fr`,`mv_parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`mv_parlamentarier`.`titel` AS `titel`,`mv_parlamentarier`.`aemter` AS `aemter`,`mv_parlamentarier`.`weitere_aemter` AS `weitere_aemter`,`mv_parlamentarier`.`zivilstand` AS `zivilstand`,`mv_parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`mv_parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`mv_parlamentarier`.`geschlecht` AS `geschlecht`,`mv_parlamentarier`.`geburtstag` AS `geburtstag`,`mv_parlamentarier`.`photo` AS `photo`,`mv_parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`mv_parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`mv_parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`mv_parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`mv_parlamentarier`.`kleinbild` AS `kleinbild`,`mv_parlamentarier`.`sitzplatz` AS `sitzplatz`,`mv_parlamentarier`.`email` AS `email`,`mv_parlamentarier`.`homepage` AS `homepage`,`mv_parlamentarier`.`homepage_2` AS `homepage_2`,`mv_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`mv_parlamentarier`.`parlament_number` AS `parlament_number`,`mv_parlamentarier`.`parlament_interessenbindungen` AS `parlament_interessenbindungen`,`mv_parlamentarier`.`parlament_interessenbindungen_updated` AS `parlament_interessenbindungen_updated`,`mv_parlamentarier`.`twitter_name` AS `twitter_name`,`mv_parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`mv_parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`mv_parlamentarier`.`facebook_name` AS `facebook_name`,`mv_parlamentarier`.`wikipedia` AS `wikipedia`,`mv_parlamentarier`.`sprache` AS `sprache`,`mv_parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`mv_parlamentarier`.`adresse_firma` AS `adresse_firma`,`mv_parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`mv_parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`mv_parlamentarier`.`adresse_plz` AS `adresse_plz`,`mv_parlamentarier`.`adresse_ort` AS `adresse_ort`,`mv_parlamentarier`.`telephon_1` AS `telephon_1`,`mv_parlamentarier`.`telephon_2` AS `telephon_2`,`mv_parlamentarier`.`erfasst` AS `erfasst`,`mv_parlamentarier`.`notizen` AS `notizen`,`mv_parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`mv_parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`mv_parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`mv_parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`mv_parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`mv_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`mv_parlamentarier`.`created_visa` AS `created_visa`,`mv_parlamentarier`.`created_date` AS `created_date`,`mv_parlamentarier`.`updated_visa` AS `updated_visa`,`mv_parlamentarier`.`updated_date` AS `updated_date`,`mv_parlamentarier`.`beruf_de` AS `beruf_de`,`mv_parlamentarier`.`von` AS `von`,`mv_parlamentarier`.`bis` AS `bis`,`mv_parlamentarier`.`geburtstag_unix` AS `geburtstag_unix`,`mv_parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`mv_parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`mv_parlamentarier`.`created_date_unix` AS `created_date_unix`,`mv_parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`mv_parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_parlamentarier`.`von_unix` AS `von_unix`,`mv_parlamentarier`.`bis_unix` AS `bis_unix`,`mv_parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`mv_parlamentarier`.`rat` AS `rat`,`mv_parlamentarier`.`kanton` AS `kanton`,`mv_parlamentarier`.`rat_de` AS `rat_de`,`mv_parlamentarier`.`kanton_name_de` AS `kanton_name_de`,`mv_parlamentarier`.`rat_fr` AS `rat_fr`,`mv_parlamentarier`.`kanton_name_fr` AS `kanton_name_fr`,`mv_parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`mv_parlamentarier`.`kommissionen_namen_de` AS `kommissionen_namen_de`,`mv_parlamentarier`.`kommissionen_namen_fr` AS `kommissionen_namen_fr`,`mv_parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`mv_parlamentarier`.`kommissionen_abkuerzung_de` AS `kommissionen_abkuerzung_de`,`mv_parlamentarier`.`kommissionen_abkuerzung_fr` AS `kommissionen_abkuerzung_fr`,`mv_parlamentarier`.`kommissionen_anzahl` AS `kommissionen_anzahl`,`mv_parlamentarier`.`partei` AS `partei`,`mv_parlamentarier`.`partei_name` AS `partei_name`,`mv_parlamentarier`.`fraktion` AS `fraktion`,`mv_parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`mv_parlamentarier`.`partei_de` AS `partei_de`,`mv_parlamentarier`.`partei_name_de` AS `partei_name_de`,`mv_parlamentarier`.`militaerischer_grad_de` AS `militaerischer_grad_de`,`mv_parlamentarier`.`partei_fr` AS `partei_fr`,`mv_parlamentarier`.`partei_name_fr` AS `partei_name_fr`,`mv_parlamentarier`.`militaerischer_grad_fr` AS `militaerischer_grad_fr`,`mv_parlamentarier`.`beruf_branche_id` AS `beruf_branche_id`,`mv_parlamentarier`.`titel_de` AS `titel_de`,`mv_parlamentarier`.`titel_fr` AS `titel_fr`,`mv_parlamentarier`.`refreshed_date` AS `refreshed_date`,`mv_parlamentarier`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`mv_parlamentarier`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`mv_parlamentarier`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`mv_parlamentarier`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`mv_parlamentarier`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`mv_parlamentarier`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`mv_parlamentarier`.`lobbyfaktor` AS `lobbyfaktor`,`mv_parlamentarier`.`lobbyfaktor_max` AS `lobbyfaktor_max`,`mv_parlamentarier`.`lobbyfaktor_percent_max` AS `lobbyfaktor_percent_max`,`mv_parlamentarier`.`anzahl_interessenbindung_tief_max` AS `anzahl_interessenbindung_tief_max`,`mv_parlamentarier`.`anzahl_interessenbindung_mittel_max` AS `anzahl_interessenbindung_mittel_max`,`mv_parlamentarier`.`anzahl_interessenbindung_hoch_max` AS `anzahl_interessenbindung_hoch_max`,`mv_parlamentarier`.`rat` AS `ratstyp`,`mv_parlamentarier`.`kanton` AS `kanton_abkuerzung` from `mv_parlamentarier` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_anhang`
--

DROP VIEW IF EXISTS `v_parlamentarier_anhang`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_anhang` AS select `parlamentarier_anhang`.`parlamentarier_id` AS `parlamentarier_id2`,`parlamentarier_anhang`.`id` AS `id`,`parlamentarier_anhang`.`parlamentarier_id` AS `parlamentarier_id`,`parlamentarier_anhang`.`datei` AS `datei`,`parlamentarier_anhang`.`dateiname` AS `dateiname`,`parlamentarier_anhang`.`dateierweiterung` AS `dateierweiterung`,`parlamentarier_anhang`.`dateiname_voll` AS `dateiname_voll`,`parlamentarier_anhang`.`mime_type` AS `mime_type`,`parlamentarier_anhang`.`encoding` AS `encoding`,`parlamentarier_anhang`.`beschreibung` AS `beschreibung`,`parlamentarier_anhang`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier_anhang`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier_anhang`.`created_visa` AS `created_visa`,`parlamentarier_anhang`.`created_date` AS `created_date`,`parlamentarier_anhang`.`updated_visa` AS `updated_visa`,`parlamentarier_anhang`.`updated_date` AS `updated_date` from `parlamentarier_anhang` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_lobbyfaktor`
--

DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_lobbyfaktor` AS select `mv_parlamentarier_lobbyfaktor`.`id` AS `id`,`mv_parlamentarier_lobbyfaktor`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`mv_parlamentarier_lobbyfaktor`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`mv_parlamentarier_lobbyfaktor`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`mv_parlamentarier_lobbyfaktor`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`mv_parlamentarier_lobbyfaktor`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`mv_parlamentarier_lobbyfaktor`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`mv_parlamentarier_lobbyfaktor`.`lobbyfaktor` AS `lobbyfaktor`,`mv_parlamentarier_lobbyfaktor`.`lobbyfaktor_einfach` AS `lobbyfaktor_einfach`,`mv_parlamentarier_lobbyfaktor`.`refreshed_date` AS `refreshed_date` from `mv_parlamentarier_lobbyfaktor` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_lobbyfaktor_max_raw`
--

DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor_max_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_lobbyfaktor_max_raw` AS select 1 AS `id`,max(`lobbyfaktor`.`anzahl_interessenbindung_tief`) AS `anzahl_interessenbindung_tief_max`,max(`lobbyfaktor`.`anzahl_interessenbindung_mittel`) AS `anzahl_interessenbindung_mittel_max`,max(`lobbyfaktor`.`anzahl_interessenbindung_hoch`) AS `anzahl_interessenbindung_hoch_max`,max(`lobbyfaktor`.`lobbyfaktor`) AS `lobbyfaktor_max`,now() AS `refreshed_date` from `v_parlamentarier_lobbyfaktor` `lobbyfaktor` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_lobbyfaktor_raw`
--

DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_lobbyfaktor_raw` AS select `parlamentarier`.`id` AS `id`,count(distinct `interessenbindung_tief`.`id`) AS `anzahl_interessenbindung_tief`,count(distinct `interessenbindung_mittel`.`id`) AS `anzahl_interessenbindung_mittel`,count(distinct `interessenbindung_hoch`.`id`) AS `anzahl_interessenbindung_hoch`,count(distinct `interessenbindung_tief_nach_wahl`.`id`) AS `anzahl_interessenbindung_tief_nach_wahl`,count(distinct `interessenbindung_mittel_nach_wahl`.`id`) AS `anzahl_interessenbindung_mittel_nach_wahl`,count(distinct `interessenbindung_hoch_nach_wahl`.`id`) AS `anzahl_interessenbindung_hoch_nach_wahl`,((((count(distinct `interessenbindung_tief`.`id`) * 1) + (count(distinct `interessenbindung_mittel`.`id`) * 5)) + (count(distinct `interessenbindung_hoch`.`id`) * 11)) + (((count(distinct `interessenbindung_tief_nach_wahl`.`id`) * 1) + (count(distinct `interessenbindung_mittel_nach_wahl`.`id`) * 5)) + (count(distinct `interessenbindung_hoch_nach_wahl`.`id`) * 11))) AS `lobbyfaktor`,(((count(distinct `interessenbindung_tief`.`id`) * 1) + (count(distinct `interessenbindung_mittel`.`id`) * 5)) + (count(distinct `interessenbindung_hoch`.`id`) * 11)) AS `lobbyfaktor_einfach`,now() AS `refreshed_date` from ((((((`parlamentarier` left join `v_interessenbindung_medium_raw` `interessenbindung_hoch` on(((`parlamentarier`.`id` = `interessenbindung_hoch`.`parlamentarier_id`) and (isnull(`interessenbindung_hoch`.`bis`) or (`interessenbindung_hoch`.`bis` >= now())) and (`interessenbindung_hoch`.`wirksamkeit` = 'hoch')))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel` on(((`parlamentarier`.`id` = `interessenbindung_mittel`.`parlamentarier_id`) and (isnull(`interessenbindung_mittel`.`bis`) or (`interessenbindung_mittel`.`bis` >= now())) and (`interessenbindung_mittel`.`wirksamkeit` = 'mittel')))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief` on(((`parlamentarier`.`id` = `interessenbindung_tief`.`parlamentarier_id`) and (isnull(`interessenbindung_tief`.`bis`) or (`interessenbindung_tief`.`bis` >= now())) and (`interessenbindung_tief`.`wirksamkeit` = 'tief')))) left join `v_interessenbindung_medium_raw` `interessenbindung_hoch_nach_wahl` on(((`parlamentarier`.`id` = `interessenbindung_hoch_nach_wahl`.`parlamentarier_id`) and (isnull(`interessenbindung_hoch_nach_wahl`.`bis`) or (`interessenbindung_hoch_nach_wahl`.`bis` >= now())) and (`interessenbindung_hoch_nach_wahl`.`wirksamkeit` = 'hoch') and (`interessenbindung_hoch_nach_wahl`.`von` > `parlamentarier`.`im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel_nach_wahl` on(((`parlamentarier`.`id` = `interessenbindung_mittel_nach_wahl`.`parlamentarier_id`) and (isnull(`interessenbindung_mittel_nach_wahl`.`bis`) or (`interessenbindung_mittel_nach_wahl`.`bis` >= now())) and (`interessenbindung_mittel_nach_wahl`.`wirksamkeit` = 'mittel') and (`interessenbindung_mittel_nach_wahl`.`von` > `parlamentarier`.`im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief_nach_wahl` on(((`parlamentarier`.`id` = `interessenbindung_tief_nach_wahl`.`parlamentarier_id`) and (isnull(`interessenbindung_tief_nach_wahl`.`bis`) or (`interessenbindung_tief_nach_wahl`.`bis` >= now())) and (`interessenbindung_tief_nach_wahl`.`wirksamkeit` = 'tief') and (`interessenbindung_tief_nach_wahl`.`von` > `parlamentarier`.`im_rat_seit`)))) group by `parlamentarier`.`id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_medium_raw`
--

DROP VIEW IF EXISTS `v_parlamentarier_medium_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_medium_raw` AS select `parlamentarier`.`anzeige_name` AS `anzeige_name`,`parlamentarier`.`anzeige_name_de` AS `anzeige_name_de`,`parlamentarier`.`anzeige_name_fr` AS `anzeige_name_fr`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`name_de` AS `name_de`,`parlamentarier`.`name_fr` AS `name_fr`,`parlamentarier`.`id` AS `id`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_fr` AS `beruf_fr`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`titel` AS `titel`,`parlamentarier`.`aemter` AS `aemter`,`parlamentarier`.`weitere_aemter` AS `weitere_aemter`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`homepage_2` AS `homepage_2`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`parlament_number` AS `parlament_number`,`parlamentarier`.`parlament_interessenbindungen` AS `parlament_interessenbindungen`,`parlamentarier`.`parlament_interessenbindungen_updated` AS `parlament_interessenbindungen_updated`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`wikipedia` AS `wikipedia`,`parlamentarier`.`sprache` AS `sprache`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`telephon_1` AS `telephon_1`,`parlamentarier`.`telephon_2` AS `telephon_2`,`parlamentarier`.`erfasst` AS `erfasst`,`parlamentarier`.`notizen` AS `notizen`,`parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`created_visa` AS `created_visa`,`parlamentarier`.`created_date` AS `created_date`,`parlamentarier`.`updated_visa` AS `updated_visa`,`parlamentarier`.`updated_date` AS `updated_date`,`parlamentarier`.`beruf_de` AS `beruf_de`,`parlamentarier`.`von` AS `von`,`parlamentarier`.`bis` AS `bis`,`parlamentarier`.`geburtstag_unix` AS `geburtstag_unix`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`created_date_unix` AS `created_date_unix`,`parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`parlamentarier`.`von_unix` AS `von_unix`,`parlamentarier`.`bis_unix` AS `bis_unix`,cast((case `rat`.`abkuerzung` when 'SR' then round((`kanton`.`einwohner` / `kanton`.`anzahl_staenderaete`),0) when 'NR' then round((`kanton`.`einwohner` / `kanton`.`anzahl_nationalraete`),0) else NULL end) as unsigned) AS `vertretene_bevoelkerung`,`rat`.`abkuerzung` AS `rat`,`kanton`.`abkuerzung` AS `kanton`,`rat`.`abkuerzung` AS `rat_de`,`kanton`.`name_de` AS `kanton_name_de`,`rat`.`abkuerzung_fr` AS `rat_fr`,`kanton`.`name_fr` AS `kanton_name_fr`,group_concat(distinct concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') order by `kommission`.`abkuerzung` ASC separator '; ') AS `kommissionen_namen`,group_concat(distinct concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') order by `kommission`.`abkuerzung` ASC separator '; ') AS `kommissionen_namen_de`,group_concat(distinct concat(`kommission`.`name_fr`,' (',`kommission`.`abkuerzung_fr`,')') order by `kommission`.`abkuerzung_fr` ASC separator '; ') AS `kommissionen_namen_fr`,group_concat(distinct `kommission`.`abkuerzung` order by `kommission`.`abkuerzung` ASC separator ', ') AS `kommissionen_abkuerzung`,group_concat(distinct `kommission`.`abkuerzung` order by `kommission`.`abkuerzung` ASC separator ', ') AS `kommissionen_abkuerzung_de`,group_concat(distinct `kommission`.`abkuerzung_fr` order by `kommission`.`abkuerzung_fr` ASC separator ', ') AS `kommissionen_abkuerzung_fr`,count(distinct `kommission`.`id`) AS `kommissionen_anzahl`,`partei`.`abkuerzung` AS `partei`,`partei`.`name` AS `partei_name`,`fraktion`.`abkuerzung` AS `fraktion`,`mil_grad`.`name` AS `militaerischer_grad`,`partei`.`abkuerzung` AS `partei_de`,`partei`.`name` AS `partei_name_de`,`mil_grad`.`name` AS `militaerischer_grad_de`,`partei`.`abkuerzung_fr` AS `partei_fr`,`partei`.`name_fr` AS `partei_name_fr`,`mil_grad`.`name_fr` AS `militaerischer_grad_fr`,`interessengruppe`.`branche_id` AS `beruf_branche_id`,concat(if((`parlamentarier`.`geschlecht` = 'M'),`rat`.`mitglied_bezeichnung_maennlich_de`,''),if((`parlamentarier`.`geschlecht` = 'F'),`rat`.`mitglied_bezeichnung_weiblich_de`,'')) AS `titel_de`,concat(if((`parlamentarier`.`geschlecht` = 'M'),`rat`.`mitglied_bezeichnung_maennlich_fr`,''),if((`parlamentarier`.`geschlecht` = 'F'),`rat`.`mitglied_bezeichnung_weiblich_fr`,'')) AS `titel_fr`,now() AS `refreshed_date` from ((((((((`v_parlamentarier_simple` `parlamentarier` left join `in_kommission` on(((`parlamentarier`.`id` = `in_kommission`.`parlamentarier_id`) and isnull(`in_kommission`.`bis`)))) left join `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) left join `v_partei` `partei` on((`parlamentarier`.`partei_id` = `partei`.`id`))) left join `v_fraktion` `fraktion` on((`parlamentarier`.`fraktion_id` = `fraktion`.`id`))) left join `v_mil_grad` `mil_grad` on((`parlamentarier`.`militaerischer_grad_id` = `mil_grad`.`id`))) left join `v_kanton` `kanton` on((`parlamentarier`.`kanton_id` = `kanton`.`id`))) left join `v_rat` `rat` on((`parlamentarier`.`rat_id` = `rat`.`id`))) left join `v_interessengruppe` `interessengruppe` on((`parlamentarier`.`beruf_interessengruppe_id` = `interessengruppe`.`id`))) group by `parlamentarier`.`id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_raw`
--

DROP VIEW IF EXISTS `v_parlamentarier_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_raw` AS select `parlamentarier`.`anzeige_name` AS `anzeige_name`,`parlamentarier`.`anzeige_name_de` AS `anzeige_name_de`,`parlamentarier`.`anzeige_name_fr` AS `anzeige_name_fr`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`name_de` AS `name_de`,`parlamentarier`.`name_fr` AS `name_fr`,`parlamentarier`.`id` AS `id`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_fr` AS `beruf_fr`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`titel` AS `titel`,`parlamentarier`.`aemter` AS `aemter`,`parlamentarier`.`weitere_aemter` AS `weitere_aemter`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`homepage_2` AS `homepage_2`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`parlament_number` AS `parlament_number`,`parlamentarier`.`parlament_interessenbindungen` AS `parlament_interessenbindungen`,`parlamentarier`.`parlament_interessenbindungen_updated` AS `parlament_interessenbindungen_updated`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`wikipedia` AS `wikipedia`,`parlamentarier`.`sprache` AS `sprache`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`telephon_1` AS `telephon_1`,`parlamentarier`.`telephon_2` AS `telephon_2`,`parlamentarier`.`erfasst` AS `erfasst`,`parlamentarier`.`notizen` AS `notizen`,`parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`created_visa` AS `created_visa`,`parlamentarier`.`created_date` AS `created_date`,`parlamentarier`.`updated_visa` AS `updated_visa`,`parlamentarier`.`updated_date` AS `updated_date`,`parlamentarier`.`beruf_de` AS `beruf_de`,`parlamentarier`.`von` AS `von`,`parlamentarier`.`bis` AS `bis`,`parlamentarier`.`geburtstag_unix` AS `geburtstag_unix`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`created_date_unix` AS `created_date_unix`,`parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`parlamentarier`.`von_unix` AS `von_unix`,`parlamentarier`.`bis_unix` AS `bis_unix`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`rat` AS `rat`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`rat_de` AS `rat_de`,`parlamentarier`.`kanton_name_de` AS `kanton_name_de`,`parlamentarier`.`rat_fr` AS `rat_fr`,`parlamentarier`.`kanton_name_fr` AS `kanton_name_fr`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_namen_de` AS `kommissionen_namen_de`,`parlamentarier`.`kommissionen_namen_fr` AS `kommissionen_namen_fr`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`kommissionen_abkuerzung_de` AS `kommissionen_abkuerzung_de`,`parlamentarier`.`kommissionen_abkuerzung_fr` AS `kommissionen_abkuerzung_fr`,`parlamentarier`.`kommissionen_anzahl` AS `kommissionen_anzahl`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`partei_name` AS `partei_name`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`parlamentarier`.`partei_de` AS `partei_de`,`parlamentarier`.`partei_name_de` AS `partei_name_de`,`parlamentarier`.`militaerischer_grad_de` AS `militaerischer_grad_de`,`parlamentarier`.`partei_fr` AS `partei_fr`,`parlamentarier`.`partei_name_fr` AS `partei_name_fr`,`parlamentarier`.`militaerischer_grad_fr` AS `militaerischer_grad_fr`,`parlamentarier`.`beruf_branche_id` AS `beruf_branche_id`,`parlamentarier`.`titel_de` AS `titel_de`,`parlamentarier`.`titel_fr` AS `titel_fr`,`parlamentarier`.`refreshed_date` AS `refreshed_date`,`lobbyfaktor`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`lobbyfaktor`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`lobbyfaktor`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`lobbyfaktor`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`lobbyfaktor`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`lobbyfaktor`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`lobbyfaktor`.`lobbyfaktor` AS `lobbyfaktor`,`lobbyfaktor_max`.`lobbyfaktor_max` AS `lobbyfaktor_max`,round((`lobbyfaktor`.`lobbyfaktor` / `lobbyfaktor_max`.`lobbyfaktor_max`),3) AS `lobbyfaktor_percent_max`,`lobbyfaktor_max`.`anzahl_interessenbindung_tief_max` AS `anzahl_interessenbindung_tief_max`,`lobbyfaktor_max`.`anzahl_interessenbindung_mittel_max` AS `anzahl_interessenbindung_mittel_max`,`lobbyfaktor_max`.`anzahl_interessenbindung_hoch_max` AS `anzahl_interessenbindung_hoch_max` from ((`v_parlamentarier_medium_raw` `parlamentarier` left join `v_parlamentarier_lobbyfaktor` `lobbyfaktor` on((`parlamentarier`.`id` = `lobbyfaktor`.`id`))) join `v_parlamentarier_lobbyfaktor_max_raw` `lobbyfaktor_max`) group by `parlamentarier`.`id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_parlamentarier_simple`
--

DROP VIEW IF EXISTS `v_parlamentarier_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_parlamentarier_simple` AS select concat(`parlamentarier`.`nachname`,', ',`parlamentarier`.`vorname`) AS `anzeige_name`,concat(`parlamentarier`.`nachname`,', ',`parlamentarier`.`vorname`) AS `anzeige_name_de`,concat(`parlamentarier`.`nachname`,', ',`parlamentarier`.`vorname`) AS `anzeige_name_fr`,concat_ws(' ',`parlamentarier`.`vorname`,`parlamentarier`.`zweiter_vorname`,`parlamentarier`.`nachname`) AS `name`,concat_ws(' ',`parlamentarier`.`vorname`,`parlamentarier`.`zweiter_vorname`,`parlamentarier`.`nachname`) AS `name_de`,concat_ws(' ',`parlamentarier`.`vorname`,`parlamentarier`.`zweiter_vorname`,`parlamentarier`.`nachname`) AS `name_fr`,`parlamentarier`.`id` AS `id`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_fr` AS `beruf_fr`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`titel` AS `titel`,`parlamentarier`.`aemter` AS `aemter`,`parlamentarier`.`weitere_aemter` AS `weitere_aemter`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`homepage_2` AS `homepage_2`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`parlament_number` AS `parlament_number`,`parlamentarier`.`parlament_interessenbindungen` AS `parlament_interessenbindungen`,`parlamentarier`.`parlament_interessenbindungen_updated` AS `parlament_interessenbindungen_updated`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`wikipedia` AS `wikipedia`,`parlamentarier`.`sprache` AS `sprache`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`telephon_1` AS `telephon_1`,`parlamentarier`.`telephon_2` AS `telephon_2`,`parlamentarier`.`erfasst` AS `erfasst`,`parlamentarier`.`notizen` AS `notizen`,`parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`created_visa` AS `created_visa`,`parlamentarier`.`created_date` AS `created_date`,`parlamentarier`.`updated_visa` AS `updated_visa`,`parlamentarier`.`updated_date` AS `updated_date`,`parlamentarier`.`beruf` AS `beruf_de`,`parlamentarier`.`im_rat_seit` AS `von`,`parlamentarier`.`im_rat_bis` AS `bis`,unix_timestamp(`parlamentarier`.`geburtstag`) AS `geburtstag_unix`,unix_timestamp(`parlamentarier`.`im_rat_seit`) AS `im_rat_seit_unix`,unix_timestamp(`parlamentarier`.`im_rat_bis`) AS `im_rat_bis_unix`,unix_timestamp(`parlamentarier`.`created_date`) AS `created_date_unix`,unix_timestamp(`parlamentarier`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`parlamentarier`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`parlamentarier`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`parlamentarier`.`freigabe_datum`) AS `freigabe_datum_unix`,unix_timestamp(`parlamentarier`.`im_rat_seit`) AS `von_unix`,unix_timestamp(`parlamentarier`.`im_rat_bis`) AS `bis_unix` from `parlamentarier` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_partei`
--

DROP VIEW IF EXISTS `v_partei`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_partei` AS select concat(`partei`.`name`,' (',`partei`.`abkuerzung`,')') AS `anzeige_name`,concat(`partei`.`name`,' (',`partei`.`abkuerzung`,')') AS `anzeige_name_de`,concat(`partei`.`name_fr`,' (',`partei`.`abkuerzung_fr`,')') AS `anzeige_name_fr`,concat_ws(' / ',concat(`partei`.`name`,' (',`partei`.`abkuerzung`,')'),concat(`partei`.`name_fr`,' (',`partei`.`abkuerzung_fr`,')')) AS `anzeige_name_mixed`,concat_ws(' / ',`partei`.`abkuerzung`,`partei`.`abkuerzung_fr`) AS `abkuerzung_mixed`,`partei`.`id` AS `id`,`partei`.`abkuerzung` AS `abkuerzung`,`partei`.`abkuerzung_fr` AS `abkuerzung_fr`,`partei`.`name` AS `name`,`partei`.`name_fr` AS `name_fr`,`partei`.`fraktion_id` AS `fraktion_id`,`partei`.`gruendung` AS `gruendung`,`partei`.`position` AS `position`,`partei`.`farbcode` AS `farbcode`,`partei`.`homepage` AS `homepage`,`partei`.`homepage_fr` AS `homepage_fr`,`partei`.`email` AS `email`,`partei`.`email_fr` AS `email_fr`,`partei`.`twitter_name` AS `twitter_name`,`partei`.`twitter_name_fr` AS `twitter_name_fr`,`partei`.`beschreibung` AS `beschreibung`,`partei`.`beschreibung_fr` AS `beschreibung_fr`,`partei`.`notizen` AS `notizen`,`partei`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`partei`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`partei`.`kontrolliert_visa` AS `kontrolliert_visa`,`partei`.`kontrolliert_datum` AS `kontrolliert_datum`,`partei`.`freigabe_visa` AS `freigabe_visa`,`partei`.`freigabe_datum` AS `freigabe_datum`,`partei`.`created_visa` AS `created_visa`,`partei`.`created_date` AS `created_date`,`partei`.`updated_visa` AS `updated_visa`,`partei`.`updated_date` AS `updated_date`,`partei`.`name` AS `name_de`,`partei`.`abkuerzung` AS `abkuerzung_de`,`partei`.`beschreibung` AS `beschreibung_de`,`partei`.`homepage` AS `homepage_de`,`partei`.`twitter_name` AS `twitter_name_de`,`partei`.`email` AS `email_de`,unix_timestamp(`partei`.`created_date`) AS `created_date_unix`,unix_timestamp(`partei`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`partei`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`partei`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`partei`.`freigabe_datum`) AS `freigabe_datum_unix` from `partei` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_person`
--

DROP VIEW IF EXISTS `v_person`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_person` AS select `person`.`anzeige_name` AS `anzeige_name`,`person`.`anzeige_name_de` AS `anzeige_name_de`,`person`.`anzeige_name_fr` AS `anzeige_name_fr`,`person`.`name` AS `name`,`person`.`name_de` AS `name_de`,`person`.`name_fr` AS `name_fr`,`person`.`id` AS `id`,`person`.`nachname` AS `nachname`,`person`.`vorname` AS `vorname`,`person`.`zweiter_vorname` AS `zweiter_vorname`,`person`.`beschreibung_de` AS `beschreibung_de`,`person`.`beschreibung_fr` AS `beschreibung_fr`,`person`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`person`.`beruf` AS `beruf`,`person`.`beruf_fr` AS `beruf_fr`,`person`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`person`.`partei_id` AS `partei_id`,`person`.`geschlecht` AS `geschlecht`,`person`.`arbeitssprache` AS `arbeitssprache`,`person`.`email` AS `email`,`person`.`homepage` AS `homepage`,`person`.`twitter_name` AS `twitter_name`,`person`.`linkedin_profil_url` AS `linkedin_profil_url`,`person`.`xing_profil_name` AS `xing_profil_name`,`person`.`facebook_name` AS `facebook_name`,`person`.`telephon_1` AS `telephon_1`,`person`.`telephon_2` AS `telephon_2`,`person`.`erfasst` AS `erfasst`,`person`.`notizen` AS `notizen`,`person`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`person`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`person`.`kontrolliert_visa` AS `kontrolliert_visa`,`person`.`kontrolliert_datum` AS `kontrolliert_datum`,`person`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`person`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`person`.`autorisiert_visa` AS `autorisiert_visa`,`person`.`autorisiert_datum` AS `autorisiert_datum`,`person`.`freigabe_visa` AS `freigabe_visa`,`person`.`freigabe_datum` AS `freigabe_datum`,`person`.`created_visa` AS `created_visa`,`person`.`created_date` AS `created_date`,`person`.`updated_visa` AS `updated_visa`,`person`.`updated_date` AS `updated_date`,`person`.`created_date_unix` AS `created_date_unix`,`person`.`updated_date_unix` AS `updated_date_unix`,`person`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`person`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`person`.`freigabe_datum_unix` AS `freigabe_datum_unix` from `v_person_simple` `person` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_person_anhang`
--

DROP VIEW IF EXISTS `v_person_anhang`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_person_anhang` AS select `person_anhang`.`person_id` AS `person_id2`,`person_anhang`.`id` AS `id`,`person_anhang`.`person_id` AS `person_id`,`person_anhang`.`datei` AS `datei`,`person_anhang`.`dateiname` AS `dateiname`,`person_anhang`.`dateierweiterung` AS `dateierweiterung`,`person_anhang`.`dateiname_voll` AS `dateiname_voll`,`person_anhang`.`mime_type` AS `mime_type`,`person_anhang`.`encoding` AS `encoding`,`person_anhang`.`beschreibung` AS `beschreibung`,`person_anhang`.`freigabe_visa` AS `freigabe_visa`,`person_anhang`.`freigabe_datum` AS `freigabe_datum`,`person_anhang`.`created_visa` AS `created_visa`,`person_anhang`.`created_date` AS `created_date`,`person_anhang`.`updated_visa` AS `updated_visa`,`person_anhang`.`updated_date` AS `updated_date` from `person_anhang` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_person_mandate`
--

DROP VIEW IF EXISTS `v_person_mandate`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_person_mandate` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `person_name`,`mandat`.`anzeige_name` AS `anzeige_name`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mandat`.`wirksamkeit` AS `wirksamkeit`,`mandat`.`wirksamkeit_index` AS `wirksamkeit_index`,`mandat`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mandat`.`refreshed_date` AS `refreshed_date`,`mandat_jahr`.`verguetung` AS `verguetung`,`mandat_jahr`.`jahr` AS `verguetung_jahr`,`mandat_jahr`.`beschreibung` AS `verguetung_beschreibung` from (((`v_person` `person` join `v_mandat` `mandat` on((`person`.`id` = `mandat`.`person_id`))) left join `v_mandat_jahr` `mandat_jahr` on((`mandat_jahr`.`id` = (select `mjn`.`id` from `v_mandat_jahr` `mjn` where ((`mjn`.`mandat_id` = `mandat`.`id`) and (`mjn`.`freigabe_datum` <= now())) order by `mjn`.`jahr` desc limit 1)))) join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) order by `mandat`.`wirksamkeit`,`organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_person_simple`
--

DROP VIEW IF EXISTS `v_person_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_person_simple` AS select concat(`person`.`nachname`,', ',`person`.`vorname`) AS `anzeige_name`,concat(`person`.`nachname`,', ',`person`.`vorname`) AS `anzeige_name_de`,concat(`person`.`nachname`,', ',`person`.`vorname`) AS `anzeige_name_fr`,concat(`person`.`vorname`,' ',`person`.`nachname`) AS `name`,concat(`person`.`vorname`,' ',`person`.`nachname`) AS `name_de`,concat(`person`.`vorname`,' ',`person`.`nachname`) AS `name_fr`,`person`.`id` AS `id`,`person`.`nachname` AS `nachname`,`person`.`vorname` AS `vorname`,`person`.`zweiter_vorname` AS `zweiter_vorname`,`person`.`beschreibung_de` AS `beschreibung_de`,`person`.`beschreibung_fr` AS `beschreibung_fr`,`person`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`person`.`beruf` AS `beruf`,`person`.`beruf_fr` AS `beruf_fr`,`person`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`person`.`partei_id` AS `partei_id`,`person`.`geschlecht` AS `geschlecht`,`person`.`arbeitssprache` AS `arbeitssprache`,`person`.`email` AS `email`,`person`.`homepage` AS `homepage`,`person`.`twitter_name` AS `twitter_name`,`person`.`linkedin_profil_url` AS `linkedin_profil_url`,`person`.`xing_profil_name` AS `xing_profil_name`,`person`.`facebook_name` AS `facebook_name`,`person`.`telephon_1` AS `telephon_1`,`person`.`telephon_2` AS `telephon_2`,`person`.`erfasst` AS `erfasst`,`person`.`notizen` AS `notizen`,`person`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`person`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`person`.`kontrolliert_visa` AS `kontrolliert_visa`,`person`.`kontrolliert_datum` AS `kontrolliert_datum`,`person`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`person`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`person`.`autorisiert_visa` AS `autorisiert_visa`,`person`.`autorisiert_datum` AS `autorisiert_datum`,`person`.`freigabe_visa` AS `freigabe_visa`,`person`.`freigabe_datum` AS `freigabe_datum`,`person`.`created_visa` AS `created_visa`,`person`.`created_date` AS `created_date`,`person`.`updated_visa` AS `updated_visa`,`person`.`updated_date` AS `updated_date`,unix_timestamp(`person`.`created_date`) AS `created_date_unix`,unix_timestamp(`person`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`person`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`person`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`person`.`freigabe_datum`) AS `freigabe_datum_unix` from `person` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_rat`
--

DROP VIEW IF EXISTS `v_rat`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_rat` AS select `rat`.`name_de` AS `anzeige_name`,`rat`.`name_de` AS `anzeige_name_de`,`rat`.`name_de` AS `anzeige_name_fr`,concat_ws(' / ',`rat`.`name_de`,`rat`.`name_de`) AS `anzeige_name_mixed`,concat_ws(' / ',`rat`.`abkuerzung`,`rat`.`abkuerzung_fr`) AS `abkuerzung_mixed`,`rat`.`id` AS `id`,`rat`.`abkuerzung` AS `abkuerzung`,`rat`.`abkuerzung_fr` AS `abkuerzung_fr`,`rat`.`name_de` AS `name_de`,`rat`.`name_fr` AS `name_fr`,`rat`.`name_it` AS `name_it`,`rat`.`name_en` AS `name_en`,`rat`.`anzahl_mitglieder` AS `anzahl_mitglieder`,`rat`.`typ` AS `typ`,`rat`.`interessenraum_id` AS `interessenraum_id`,`rat`.`anzeigestufe` AS `anzeigestufe`,`rat`.`gewicht` AS `gewicht`,`rat`.`beschreibung` AS `beschreibung`,`rat`.`homepage_de` AS `homepage_de`,`rat`.`homepage_fr` AS `homepage_fr`,`rat`.`homepage_it` AS `homepage_it`,`rat`.`homepage_en` AS `homepage_en`,`rat`.`mitglied_bezeichnung_maennlich_de` AS `mitglied_bezeichnung_maennlich_de`,`rat`.`mitglied_bezeichnung_weiblich_de` AS `mitglied_bezeichnung_weiblich_de`,`rat`.`mitglied_bezeichnung_maennlich_fr` AS `mitglied_bezeichnung_maennlich_fr`,`rat`.`mitglied_bezeichnung_weiblich_fr` AS `mitglied_bezeichnung_weiblich_fr`,`rat`.`parlament_id` AS `parlament_id`,`rat`.`parlament_type` AS `parlament_type`,`rat`.`notizen` AS `notizen`,`rat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`rat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`rat`.`kontrolliert_visa` AS `kontrolliert_visa`,`rat`.`kontrolliert_datum` AS `kontrolliert_datum`,`rat`.`freigabe_visa` AS `freigabe_visa`,`rat`.`freigabe_datum` AS `freigabe_datum`,`rat`.`created_visa` AS `created_visa`,`rat`.`created_date` AS `created_date`,`rat`.`updated_visa` AS `updated_visa`,`rat`.`updated_date` AS `updated_date`,unix_timestamp(`rat`.`created_date`) AS `created_date_unix`,unix_timestamp(`rat`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`rat`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`rat`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`rat`.`freigabe_datum`) AS `freigabe_datum_unix` from `rat` order by `rat`.`gewicht` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_search_table`
--

DROP VIEW IF EXISTS `v_search_table`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_search_table` AS select `mv_search_table`.`id` AS `id`,`mv_search_table`.`table_name` AS `table_name`,`mv_search_table`.`page` AS `page`,`mv_search_table`.`table_weight` AS `table_weight`,`mv_search_table`.`name_de` AS `name_de`,`mv_search_table`.`name_fr` AS `name_fr`,`mv_search_table`.`search_keywords_de` AS `search_keywords_de`,`mv_search_table`.`search_keywords_fr` AS `search_keywords_fr`,`mv_search_table`.`freigabe_datum` AS `freigabe_datum`,`mv_search_table`.`bis` AS `bis`,`mv_search_table`.`weight` AS `weight`,`mv_search_table`.`refreshed_date` AS `refreshed_date` from `mv_search_table` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_search_table_raw`
--

DROP VIEW IF EXISTS `v_search_table_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_search_table_raw` AS select `v_parlamentarier`.`id` AS `id`,'parlamentarier' AS `table_name`,'parlamentarier' AS `page`,(-(20) + if((`v_parlamentarier`.`im_rat_bis` < now()),5,0)) AS `table_weight`,concat_ws(', ',`v_parlamentarier`.`anzeige_name`,concat(if((`v_parlamentarier`.`im_rat_bis` < now()),'Ex-',''),`v_parlamentarier`.`rat_de`),`v_parlamentarier`.`partei_de`,`v_parlamentarier`.`kanton`) AS `name_de`,concat_ws(', ',`v_parlamentarier`.`anzeige_name`,concat(if((`v_parlamentarier`.`im_rat_bis` < now()),'Ex-',''),`v_parlamentarier`.`rat_fr`),`v_parlamentarier`.`partei_fr`,`v_parlamentarier`.`kanton`) AS `name_fr`,concat_ws(' ',`v_parlamentarier`.`nachname`,`v_parlamentarier`.`vorname`,concat(`v_parlamentarier`.`nachname`,', ',`v_parlamentarier`.`vorname`),`v_parlamentarier`.`zweiter_vorname`,`v_parlamentarier`.`nachname`,left(`v_parlamentarier`.`vorname`,25),left(`v_parlamentarier`.`zweiter_vorname`,1),left(`v_parlamentarier`.`nachname`,27)) AS `search_keywords_de`,concat_ws(' ',`v_parlamentarier`.`nachname`,`v_parlamentarier`.`vorname`,concat(`v_parlamentarier`.`nachname`,', ',`v_parlamentarier`.`vorname`),`v_parlamentarier`.`zweiter_vorname`,`v_parlamentarier`.`nachname`,left(`v_parlamentarier`.`vorname`,25),left(`v_parlamentarier`.`zweiter_vorname`,1),left(`v_parlamentarier`.`nachname`,27)) AS `search_keywords_fr`,`v_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`v_parlamentarier`.`im_rat_bis` AS `bis`,-(`v_parlamentarier`.`lobbyfaktor`) AS `weight`,now() AS `refreshed_date` from `v_parlamentarier` union select `v_zutrittsberechtigung`.`id` AS `id`,'zutrittsberechtigung' AS `table_name`,'zutrittsberechtigter' AS `page`,-(15) AS `table_weight`,`v_zutrittsberechtigung`.`anzeige_name` AS `name_de`,`v_zutrittsberechtigung`.`anzeige_name` AS `name_fr`,concat_ws(' ',`v_zutrittsberechtigung`.`nachname`,`v_zutrittsberechtigung`.`vorname`,concat(`v_zutrittsberechtigung`.`nachname`,', ',`v_zutrittsberechtigung`.`vorname`),`v_zutrittsberechtigung`.`zweiter_vorname`,`v_zutrittsberechtigung`.`nachname`,left(`v_zutrittsberechtigung`.`vorname`,25),left(`v_zutrittsberechtigung`.`zweiter_vorname`,1),left(`v_zutrittsberechtigung`.`nachname`,27)) AS `search_keywords_de`,concat_ws(' ',`v_zutrittsberechtigung`.`nachname`,`v_zutrittsberechtigung`.`vorname`,concat(`v_zutrittsberechtigung`.`nachname`,', ',`v_zutrittsberechtigung`.`vorname`),`v_zutrittsberechtigung`.`zweiter_vorname`,`v_zutrittsberechtigung`.`nachname`,left(`v_zutrittsberechtigung`.`vorname`,25),left(`v_zutrittsberechtigung`.`zweiter_vorname`,1),left(`v_zutrittsberechtigung`.`nachname`,27)) AS `search_keywords_fr`,`v_zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,if((max(isnull(`v_zutrittsberechtigung`.`bis`)) = 0),max(`v_zutrittsberechtigung`.`bis`),NULL) AS `bis`,`v_zutrittsberechtigung`.`lobbyfaktor` AS `weight`,now() AS `refreshed_date` from `v_zutrittsberechtigung` group by `v_zutrittsberechtigung`.`id` union select `v_branche`.`id` AS `id`,'branche' AS `table_name`,'branche' AS `page`,-(10) AS `table_weight`,`v_branche`.`anzeige_name_de` AS `name_de`,`v_branche`.`anzeige_name_fr` AS `name_fr`,`v_branche`.`anzeige_name_de` AS `search_keywords_de`,`v_branche`.`anzeige_name_fr` AS `search_keywords_fr`,`v_branche`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_branche` union select `v_interessengruppe`.`id` AS `id`,'interessengruppe' AS `table_name`,'lobbygruppe' AS `page`,-(5) AS `table_weight`,`v_interessengruppe`.`anzeige_name_de` AS `name_de`,`v_interessengruppe`.`anzeige_name_fr` AS `name_fr`,concat_ws('; ',`v_interessengruppe`.`anzeige_name_de`,`v_interessengruppe`.`alias_namen`) AS `search_keywords_de`,concat_ws('; ',`v_interessengruppe`.`anzeige_name_fr`,`v_interessengruppe`.`alias_namen_fr`) AS `search_keywords_fr`,`v_interessengruppe`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_interessengruppe` union select `v_kommission`.`id` AS `id`,'kommission' AS `table_name`,'kommission' AS `page`,0 AS `table_weight`,`v_kommission`.`anzeige_name_de` AS `name_de`,`v_kommission`.`anzeige_name_fr` AS `name_fr`,`v_kommission`.`anzeige_name_de` AS `search_keywords_de`,`v_kommission`.`anzeige_name_fr` AS `search_keywords_fr`,`v_kommission`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_kommission` union select `v_organisation`.`id` AS `id`,'organisation' AS `table_name`,'organisation' AS `page`,15 AS `table_weight`,`v_organisation`.`anzeige_name_de` AS `name_de`,ifnull(`v_organisation`.`anzeige_name_fr`,`v_organisation`.`anzeige_name_de`) AS `name_fr`,concat_ws('; ',`v_organisation`.`anzeige_name_de`,`v_organisation`.`abkuerzung_de`,`v_organisation`.`uid`,`v_organisation`.`alias_namen_de`) AS `search_keywords_de`,concat_ws('; ',`v_organisation`.`anzeige_name_fr`,`v_organisation`.`abkuerzung_fr`,`v_organisation`.`uid`,`v_organisation`.`alias_namen_fr`,`v_organisation`.`anzeige_name_de`) AS `search_keywords_fr`,`v_organisation`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,-(`v_organisation`.`lobbyeinfluss_index`) AS `weight`,now() AS `refreshed_date` from `v_organisation` union select `v_partei`.`id` AS `id`,'partei' AS `table_name`,'partei' AS `page`,20 AS `table_weight`,`v_partei`.`anzeige_name_de` AS `name_de`,`v_partei`.`anzeige_name_fr` AS `name_fr`,`v_partei`.`anzeige_name_de` AS `search_keywords_de`,`v_partei`.`anzeige_name_fr` AS `search_keywords_fr`,`v_partei`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_partei` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_settings`
--

DROP VIEW IF EXISTS `v_settings`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_settings` AS select `settings`.`id` AS `id`,`settings`.`key_name` AS `key_name`,`settings`.`value` AS `value`,`settings`.`description` AS `description`,`settings`.`category_id` AS `category_id`,`settings`.`notizen` AS `notizen`,`settings`.`created_visa` AS `created_visa`,`settings`.`created_date` AS `created_date`,`settings`.`updated_visa` AS `updated_visa`,`settings`.`updated_date` AS `updated_date`,`settings_category`.`name` AS `category_name`,unix_timestamp(`settings`.`created_date`) AS `created_date_unix`,unix_timestamp(`settings`.`updated_date`) AS `updated_date_unix` from (`settings` left join `v_settings_category` `settings_category` on((`settings`.`category_id` = `settings_category`.`id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_settings_category`
--

DROP VIEW IF EXISTS `v_settings_category`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_settings_category` AS select `settings_category`.`id` AS `id`,`settings_category`.`name` AS `name`,`settings_category`.`description` AS `description`,`settings_category`.`notizen` AS `notizen`,`settings_category`.`created_visa` AS `created_visa`,`settings_category`.`created_date` AS `created_date`,`settings_category`.`updated_visa` AS `updated_visa`,`settings_category`.`updated_date` AS `updated_date`,unix_timestamp(`settings_category`.`created_date`) AS `created_date_unix`,unix_timestamp(`settings_category`.`updated_date`) AS `updated_date_unix` from `settings_category` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_user`
--

DROP VIEW IF EXISTS `v_user`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_user` AS select ifnull(concat_ws(' ',`u`.`vorname`,`u`.`nachname`),`u`.`name`) AS `anzeige_name`,`u`.`name` AS `username`,`u`.`id` AS `id`,`u`.`name` AS `name`,`u`.`password` AS `password`,`u`.`nachname` AS `nachname`,`u`.`vorname` AS `vorname`,`u`.`email` AS `email`,`u`.`last_login` AS `last_login`,`u`.`last_access` AS `last_access`,`u`.`farbcode` AS `farbcode`,`u`.`notizen` AS `notizen`,`u`.`created_visa` AS `created_visa`,`u`.`created_date` AS `created_date`,`u`.`updated_visa` AS `updated_visa`,`u`.`updated_date` AS `updated_date`,unix_timestamp(`u`.`created_date`) AS `created_date_unix`,unix_timestamp(`u`.`updated_date`) AS `updated_date_unix` from `user` `u` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_user_permission`
--

DROP VIEW IF EXISTS `v_user_permission`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_user_permission` AS select `t`.`id` AS `id`,`t`.`user_id` AS `user_id`,`t`.`page_name` AS `page_name`,`t`.`permission_name` AS `permission_name` from `user_permission` `t` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung` AS select `mv_zutrittsberechtigung`.`anzeige_name` AS `anzeige_name`,`mv_zutrittsberechtigung`.`anzeige_name_de` AS `anzeige_name_de`,`mv_zutrittsberechtigung`.`anzeige_name_fr` AS `anzeige_name_fr`,`mv_zutrittsberechtigung`.`name` AS `name`,`mv_zutrittsberechtigung`.`name_de` AS `name_de`,`mv_zutrittsberechtigung`.`name_fr` AS `name_fr`,`mv_zutrittsberechtigung`.`id` AS `id`,`mv_zutrittsberechtigung`.`nachname` AS `nachname`,`mv_zutrittsberechtigung`.`vorname` AS `vorname`,`mv_zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`mv_zutrittsberechtigung`.`beschreibung_de` AS `beschreibung_de`,`mv_zutrittsberechtigung`.`beschreibung_fr` AS `beschreibung_fr`,`mv_zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`mv_zutrittsberechtigung`.`parlamentarier_kommissionen_zutrittsberechtigung` AS `parlamentarier_kommissionen_zutrittsberechtigung`,`mv_zutrittsberechtigung`.`beruf` AS `beruf`,`mv_zutrittsberechtigung`.`beruf_fr` AS `beruf_fr`,`mv_zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`mv_zutrittsberechtigung`.`partei_id` AS `partei_id`,`mv_zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`mv_zutrittsberechtigung`.`arbeitssprache` AS `arbeitssprache`,`mv_zutrittsberechtigung`.`email` AS `email`,`mv_zutrittsberechtigung`.`homepage` AS `homepage`,`mv_zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`mv_zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`mv_zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`mv_zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`mv_zutrittsberechtigung`.`telephon_1` AS `telephon_1`,`mv_zutrittsberechtigung`.`telephon_2` AS `telephon_2`,`mv_zutrittsberechtigung`.`erfasst` AS `erfasst`,`mv_zutrittsberechtigung`.`notizen` AS `notizen`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_visa_person` AS `eingabe_abgeschlossen_visa_person`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_datum_person` AS `eingabe_abgeschlossen_datum_person`,`mv_zutrittsberechtigung`.`kontrolliert_visa_person` AS `kontrolliert_visa_person`,`mv_zutrittsberechtigung`.`kontrolliert_datum_person` AS `kontrolliert_datum_person`,`mv_zutrittsberechtigung`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`mv_zutrittsberechtigung`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`mv_zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`mv_zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`mv_zutrittsberechtigung`.`freigabe_visa_person` AS `freigabe_visa_person`,`mv_zutrittsberechtigung`.`freigabe_datum_person` AS `freigabe_datum_person`,`mv_zutrittsberechtigung`.`created_visa_person` AS `created_visa_person`,`mv_zutrittsberechtigung`.`created_date_person` AS `created_date_person`,`mv_zutrittsberechtigung`.`updated_visa_person` AS `updated_visa_person`,`mv_zutrittsberechtigung`.`updated_date_person` AS `updated_date_person`,`mv_zutrittsberechtigung`.`created_date_unix_person` AS `created_date_unix_person`,`mv_zutrittsberechtigung`.`updated_date_unix_person` AS `updated_date_unix_person`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix_person` AS `eingabe_abgeschlossen_datum_unix_person`,`mv_zutrittsberechtigung`.`kontrolliert_datum_unix_person` AS `kontrolliert_datum_unix_person`,`mv_zutrittsberechtigung`.`freigabe_datum_unix_person` AS `freigabe_datum_unix_person`,`mv_zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mv_zutrittsberechtigung`.`person_id` AS `person_id`,`mv_zutrittsberechtigung`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`mv_zutrittsberechtigung`.`funktion` AS `funktion`,`mv_zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`mv_zutrittsberechtigung`.`von` AS `von`,`mv_zutrittsberechtigung`.`bis` AS `bis`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`mv_zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`mv_zutrittsberechtigung`.`created_visa` AS `created_visa`,`mv_zutrittsberechtigung`.`created_date` AS `created_date`,`mv_zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`mv_zutrittsberechtigung`.`updated_date` AS `updated_date`,`mv_zutrittsberechtigung`.`bis_unix` AS `bis_unix`,`mv_zutrittsberechtigung`.`von_unix` AS `von_unix`,`mv_zutrittsberechtigung`.`created_date_unix` AS `created_date_unix`,`mv_zutrittsberechtigung`.`updated_date_unix` AS `updated_date_unix`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_zutrittsberechtigung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_zutrittsberechtigung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_zutrittsberechtigung`.`beruf_branche_id` AS `beruf_branche_id`,`mv_zutrittsberechtigung`.`partei` AS `partei`,`mv_zutrittsberechtigung`.`partei_de` AS `partei_de`,`mv_zutrittsberechtigung`.`partei_fr` AS `partei_fr`,`mv_zutrittsberechtigung`.`parlamentarier_name` AS `parlamentarier_name`,`mv_zutrittsberechtigung`.`parlamentarier_freigabe_datum` AS `parlamentarier_freigabe_datum`,`mv_zutrittsberechtigung`.`parlamentarier_freigabe_datum_unix` AS `parlamentarier_freigabe_datum_unix`,`mv_zutrittsberechtigung`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`mv_zutrittsberechtigung`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`mv_zutrittsberechtigung`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`mv_zutrittsberechtigung`.`lobbyfaktor` AS `lobbyfaktor`,`mv_zutrittsberechtigung`.`lobbyfaktor_max` AS `lobbyfaktor_max`,`mv_zutrittsberechtigung`.`lobbyfaktor_percent_max` AS `lobbyfaktor_percent_max`,`mv_zutrittsberechtigung`.`anzahl_mandat_tief_max` AS `anzahl_mandat_tief_max`,`mv_zutrittsberechtigung`.`anzahl_mandat_mittel_max` AS `anzahl_mandat_mittel_max`,`mv_zutrittsberechtigung`.`anzahl_mandat_hoch_max` AS `anzahl_mandat_hoch_max`,`mv_zutrittsberechtigung`.`refreshed_date` AS `refreshed_date` from `mv_zutrittsberechtigung` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_lobbyfaktor_max_raw`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_max_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_lobbyfaktor_max_raw` AS select 1 AS `id`,max(`lobbyfaktor`.`anzahl_mandat_tief`) AS `anzahl_mandat_tief_max`,max(`lobbyfaktor`.`anzahl_mandat_mittel`) AS `anzahl_mandat_mittel_max`,max(`lobbyfaktor`.`anzahl_mandat_hoch`) AS `anzahl_mandat_hoch_max`,max(`lobbyfaktor`.`lobbyfaktor`) AS `lobbyfaktor_max`,now() AS `refreshed_date` from `v_zutrittsberechtigung_lobbyfaktor_raw` `lobbyfaktor` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_lobbyfaktor_raw`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_lobbyfaktor_raw` AS select `zutrittsberechtigung`.`person_id` AS `person_id`,count(distinct `mandat_tief`.`id`) AS `anzahl_mandat_tief`,count(distinct `mandat_mittel`.`id`) AS `anzahl_mandat_mittel`,count(distinct `mandat_hoch`.`id`) AS `anzahl_mandat_hoch`,((count(distinct `mandat_tief`.`id`) + (count(distinct `mandat_mittel`.`id`) * 5)) + (count(distinct `mandat_hoch`.`id`) * 11)) AS `lobbyfaktor`,now() AS `refreshed_date` from (((`zutrittsberechtigung` left join `v_mandat_medium_raw` `mandat_hoch` on(((`zutrittsberechtigung`.`person_id` = `mandat_hoch`.`person_id`) and (isnull(`mandat_hoch`.`bis`) or (`mandat_hoch`.`bis` >= now())) and (`mandat_hoch`.`wirksamkeit` = 'hoch')))) left join `v_mandat_medium_raw` `mandat_mittel` on(((`zutrittsberechtigung`.`person_id` = `mandat_mittel`.`person_id`) and (isnull(`mandat_mittel`.`bis`) or (`mandat_mittel`.`bis` >= now())) and (`mandat_mittel`.`wirksamkeit` = 'mittel')))) left join `v_mandat_medium_raw` `mandat_tief` on(((`zutrittsberechtigung`.`person_id` = `mandat_tief`.`person_id`) and (isnull(`mandat_tief`.`bis`) or (`mandat_tief`.`bis` >= now())) and (`mandat_tief`.`wirksamkeit` = 'tief')))) group by `zutrittsberechtigung`.`person_id` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_mandate`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_mandate`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_mandate` AS select `zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`mandat`.`anzeige_name` AS `anzeige_name`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mandat`.`wirksamkeit` AS `wirksamkeit`,`mandat`.`wirksamkeit_index` AS `wirksamkeit_index`,`mandat`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mandat`.`refreshed_date` AS `refreshed_date`,`mandat_jahr`.`verguetung` AS `verguetung`,`mandat_jahr`.`jahr` AS `verguetung_jahr`,`mandat_jahr`.`beschreibung` AS `verguetung_beschreibung` from ((((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` join `v_mandat` `mandat` on((`zutrittsberechtigung`.`person_id` = `mandat`.`person_id`))) left join `v_mandat_jahr` `mandat_jahr` on((`mandat_jahr`.`id` = (select `mjn`.`id` from `v_mandat_jahr` `mjn` where ((`mjn`.`mandat_id` = `mandat`.`id`) and (`mjn`.`freigabe_datum` <= now())) order by `mjn`.`jahr` desc limit 1)))) join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) order by `mandat`.`wirksamkeit`,`organisation`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_mit_mandaten`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_mit_mandaten` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` left join `v_mandat_simple` `mandat` on((`zutrittsberechtigung`.`person_id` = `mandat`.`person_id`))) left join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) left join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) order by `person`.`anzeige_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_mit_mandaten_indirekt`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_mit_mandaten_indirekt` AS select 'direkt' AS `beziehung`,`zutrittsberechtigung`.`organisation_name` AS `organisation_name`,`zutrittsberechtigung`.`organisation_name_de` AS `organisation_name_de`,`zutrittsberechtigung`.`organisation_name_fr` AS `organisation_name_fr`,`zutrittsberechtigung`.`name` AS `name`,`zutrittsberechtigung`.`name_de` AS `name_de`,`zutrittsberechtigung`.`name_fr` AS `name_fr`,`zutrittsberechtigung`.`name_it` AS `name_it`,`zutrittsberechtigung`.`ort` AS `ort`,`zutrittsberechtigung`.`land_id` AS `land_id`,`zutrittsberechtigung`.`interessenraum_id` AS `interessenraum_id`,`zutrittsberechtigung`.`rechtsform` AS `rechtsform`,`zutrittsberechtigung`.`typ` AS `typ`,`zutrittsberechtigung`.`vernehmlassung` AS `vernehmlassung`,`zutrittsberechtigung`.`interessengruppe_id` AS `interessengruppe_id`,`zutrittsberechtigung`.`interessengruppe2_id` AS `interessengruppe2_id`,`zutrittsberechtigung`.`interessengruppe3_id` AS `interessengruppe3_id`,`zutrittsberechtigung`.`branche_id` AS `branche_id`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`handelsregister_url` AS `handelsregister_url`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`organisation_beschreibung` AS `organisation_beschreibung`,`zutrittsberechtigung`.`adresse_strasse` AS `adresse_strasse`,`zutrittsberechtigung`.`adresse_zusatz` AS `adresse_zusatz`,`zutrittsberechtigung`.`adresse_plz` AS `adresse_plz`,`zutrittsberechtigung`.`branche` AS `branche`,`zutrittsberechtigung`.`interessengruppe` AS `interessengruppe`,`zutrittsberechtigung`.`interessengruppe_branche` AS `interessengruppe_branche`,`zutrittsberechtigung`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`zutrittsberechtigung`.`interessengruppe2` AS `interessengruppe2`,`zutrittsberechtigung`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`zutrittsberechtigung`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`zutrittsberechtigung`.`interessengruppe3` AS `interessengruppe3`,`zutrittsberechtigung`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`zutrittsberechtigung`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`zutrittsberechtigung`.`land` AS `land`,`zutrittsberechtigung`.`interessenraum` AS `interessenraum`,`zutrittsberechtigung`.`organisation_jahr_id` AS `organisation_jahr_id`,`zutrittsberechtigung`.`jahr` AS `jahr`,`zutrittsberechtigung`.`umsatz` AS `umsatz`,`zutrittsberechtigung`.`gewinn` AS `gewinn`,`zutrittsberechtigung`.`kapital` AS `kapital`,`zutrittsberechtigung`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`zutrittsberechtigung`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`zutrittsberechtigung`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`zutrittsberechtigung`.`zutrittsberechtigung_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`organisation_id` AS `organisation_id`,`zutrittsberechtigung`.`art` AS `art`,`zutrittsberechtigung`.`funktion_im_gremium` AS `funktion_im_gremium`,`zutrittsberechtigung`.`beschreibung` AS `beschreibung`,`zutrittsberechtigung`.`beschreibung_fr` AS `beschreibung_fr`,`zutrittsberechtigung`.`quelle_url` AS `quelle_url`,`zutrittsberechtigung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`zutrittsberechtigung`.`quelle` AS `quelle`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,`zutrittsberechtigung`.`bis_unix` AS `bis_unix`,`zutrittsberechtigung`.`von_unix` AS `von_unix`,`zutrittsberechtigung`.`created_date_unix` AS `created_date_unix`,`zutrittsberechtigung`.`updated_date_unix` AS `updated_date_unix`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`zutrittsberechtigung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`zutrittsberechtigung`.`freigabe_datum_unix` AS `freigabe_datum_unix` from `v_zutrittsberechtigung_mit_mandaten` `zutrittsberechtigung` union select 'indirekt' AS `beziehung`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`beschreibung_fr` AS `beschreibung_fr`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix` from ((((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` join `v_mandat_simple` `mandat` on((`zutrittsberechtigung`.`person_id` = `mandat`.`person_id`))) join `v_organisation_beziehung` `organisation_beziehung` on((`mandat`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`organisation_name` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_raw`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_raw`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_raw` AS select `zutrittsberechtigung`.`anzeige_name` AS `anzeige_name`,`zutrittsberechtigung`.`anzeige_name_de` AS `anzeige_name_de`,`zutrittsberechtigung`.`anzeige_name_fr` AS `anzeige_name_fr`,`zutrittsberechtigung`.`name` AS `name`,`zutrittsberechtigung`.`name_de` AS `name_de`,`zutrittsberechtigung`.`name_fr` AS `name_fr`,`zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`nachname` AS `nachname`,`zutrittsberechtigung`.`vorname` AS `vorname`,`zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`zutrittsberechtigung`.`beschreibung_de` AS `beschreibung_de`,`zutrittsberechtigung`.`beschreibung_fr` AS `beschreibung_fr`,`zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`zutrittsberechtigung`.`parlamentarier_kommissionen_zutrittsberechtigung` AS `parlamentarier_kommissionen_zutrittsberechtigung`,`zutrittsberechtigung`.`beruf` AS `beruf`,`zutrittsberechtigung`.`beruf_fr` AS `beruf_fr`,`zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`zutrittsberechtigung`.`partei_id` AS `partei_id`,`zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`zutrittsberechtigung`.`arbeitssprache` AS `arbeitssprache`,`zutrittsberechtigung`.`email` AS `email`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`zutrittsberechtigung`.`telephon_1` AS `telephon_1`,`zutrittsberechtigung`.`telephon_2` AS `telephon_2`,`zutrittsberechtigung`.`erfasst` AS `erfasst`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa_person` AS `eingabe_abgeschlossen_visa_person`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum_person` AS `eingabe_abgeschlossen_datum_person`,`zutrittsberechtigung`.`kontrolliert_visa_person` AS `kontrolliert_visa_person`,`zutrittsberechtigung`.`kontrolliert_datum_person` AS `kontrolliert_datum_person`,`zutrittsberechtigung`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`zutrittsberechtigung`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa_person` AS `freigabe_visa_person`,`zutrittsberechtigung`.`freigabe_datum_person` AS `freigabe_datum_person`,`zutrittsberechtigung`.`created_visa_person` AS `created_visa_person`,`zutrittsberechtigung`.`created_date_person` AS `created_date_person`,`zutrittsberechtigung`.`updated_visa_person` AS `updated_visa_person`,`zutrittsberechtigung`.`updated_date_person` AS `updated_date_person`,`zutrittsberechtigung`.`created_date_unix_person` AS `created_date_unix_person`,`zutrittsberechtigung`.`updated_date_unix_person` AS `updated_date_unix_person`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix_person` AS `eingabe_abgeschlossen_datum_unix_person`,`zutrittsberechtigung`.`kontrolliert_datum_unix_person` AS `kontrolliert_datum_unix_person`,`zutrittsberechtigung`.`freigabe_datum_unix_person` AS `freigabe_datum_unix_person`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,`zutrittsberechtigung`.`bis_unix` AS `bis_unix`,`zutrittsberechtigung`.`von_unix` AS `von_unix`,`zutrittsberechtigung`.`created_date_unix` AS `created_date_unix`,`zutrittsberechtigung`.`updated_date_unix` AS `updated_date_unix`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`zutrittsberechtigung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`zutrittsberechtigung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessengruppe`.`branche_id` AS `beruf_branche_id`,`partei`.`abkuerzung` AS `partei`,`partei`.`abkuerzung` AS `partei_de`,`partei`.`abkuerzung_fr` AS `partei_fr`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`freigabe_datum` AS `parlamentarier_freigabe_datum`,unix_timestamp(`parlamentarier`.`freigabe_datum`) AS `parlamentarier_freigabe_datum_unix`,`lobbyfaktor`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`lobbyfaktor`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`lobbyfaktor`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`lobbyfaktor`.`lobbyfaktor` AS `lobbyfaktor`,`lobbyfaktor_max`.`lobbyfaktor_max` AS `lobbyfaktor_max`,round((`lobbyfaktor`.`lobbyfaktor` / `lobbyfaktor_max`.`lobbyfaktor_max`),3) AS `lobbyfaktor_percent_max`,`lobbyfaktor_max`.`anzahl_mandat_tief_max` AS `anzahl_mandat_tief_max`,`lobbyfaktor_max`.`anzahl_mandat_mittel_max` AS `anzahl_mandat_mittel_max`,`lobbyfaktor_max`.`anzahl_mandat_hoch_max` AS `anzahl_mandat_hoch_max`,now() AS `refreshed_date` from (((((`v_zutrittsberechtigung_simple_compat` `zutrittsberechtigung` left join `v_partei` `partei` on((`zutrittsberechtigung`.`partei_id` = `partei`.`id`))) left join `v_parlamentarier_raw` `parlamentarier` on((`parlamentarier`.`id` = `zutrittsberechtigung`.`parlamentarier_id`))) left join `v_zutrittsberechtigung_lobbyfaktor_raw` `lobbyfaktor` on((`zutrittsberechtigung`.`person_id` = `lobbyfaktor`.`person_id`))) left join `v_interessengruppe` `interessengruppe` on((`zutrittsberechtigung`.`beruf_interessengruppe_id` = `interessengruppe`.`id`))) join `v_zutrittsberechtigung_lobbyfaktor_max_raw` `lobbyfaktor_max`) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_simple`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_simple`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_simple` AS select `zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`updated_by_import` AS `updated_by_import`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,unix_timestamp(`zutrittsberechtigung`.`bis`) AS `bis_unix`,unix_timestamp(`zutrittsberechtigung`.`von`) AS `von_unix`,unix_timestamp(`zutrittsberechtigung`.`created_date`) AS `created_date_unix`,unix_timestamp(`zutrittsberechtigung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`zutrittsberechtigung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`freigabe_datum`) AS `freigabe_datum_unix` from `zutrittsberechtigung` ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;

--
-- Final view structure for view `v_zutrittsberechtigung_simple_compat`
--

DROP VIEW IF EXISTS `v_zutrittsberechtigung_simple_compat`;
SET @saved_cs_client          = @@character_set_client ;
SET @saved_cs_results         = @@character_set_results ;
SET @saved_col_connection     = @@collation_connection ;
SET character_set_client      = utf8 ;
SET character_set_results     = utf8 ;
SET collation_connection      = utf8_general_ci ;
CREATE  
 
VIEW `v_zutrittsberechtigung_simple_compat` AS select `person`.`anzeige_name` AS `anzeige_name`,`person`.`anzeige_name_de` AS `anzeige_name_de`,`person`.`anzeige_name_fr` AS `anzeige_name_fr`,`person`.`name` AS `name`,`person`.`name_de` AS `name_de`,`person`.`name_fr` AS `name_fr`,`person`.`id` AS `id`,`person`.`nachname` AS `nachname`,`person`.`vorname` AS `vorname`,`person`.`zweiter_vorname` AS `zweiter_vorname`,`person`.`beschreibung_de` AS `beschreibung_de`,`person`.`beschreibung_fr` AS `beschreibung_fr`,`person`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen_zutrittsberechtigung`,`person`.`beruf` AS `beruf`,`person`.`beruf_fr` AS `beruf_fr`,`person`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`person`.`partei_id` AS `partei_id`,`person`.`geschlecht` AS `geschlecht`,`person`.`arbeitssprache` AS `arbeitssprache`,`person`.`email` AS `email`,`person`.`homepage` AS `homepage`,`person`.`twitter_name` AS `twitter_name`,`person`.`linkedin_profil_url` AS `linkedin_profil_url`,`person`.`xing_profil_name` AS `xing_profil_name`,`person`.`facebook_name` AS `facebook_name`,`person`.`telephon_1` AS `telephon_1`,`person`.`telephon_2` AS `telephon_2`,`person`.`erfasst` AS `erfasst`,`person`.`notizen` AS `notizen`,`person`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa_person`,`person`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum_person`,`person`.`kontrolliert_visa` AS `kontrolliert_visa_person`,`person`.`kontrolliert_datum` AS `kontrolliert_datum_person`,`person`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`person`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`person`.`autorisiert_visa` AS `autorisiert_visa`,`person`.`autorisiert_datum` AS `autorisiert_datum`,`person`.`freigabe_visa` AS `freigabe_visa_person`,`person`.`freigabe_datum` AS `freigabe_datum_person`,`person`.`created_visa` AS `created_visa_person`,`person`.`created_date` AS `created_date_person`,`person`.`updated_visa` AS `updated_visa_person`,`person`.`updated_date` AS `updated_date_person`,unix_timestamp(`person`.`created_date`) AS `created_date_unix_person`,unix_timestamp(`person`.`updated_date`) AS `updated_date_unix_person`,unix_timestamp(`person`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix_person`,unix_timestamp(`person`.`kontrolliert_datum`) AS `kontrolliert_datum_unix_person`,unix_timestamp(`person`.`freigabe_datum`) AS `freigabe_datum_unix_person`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,unix_timestamp(`zutrittsberechtigung`.`bis`) AS `bis_unix`,unix_timestamp(`zutrittsberechtigung`.`von`) AS `von_unix`,unix_timestamp(`zutrittsberechtigung`.`created_date`) AS `created_date_unix`,unix_timestamp(`zutrittsberechtigung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`zutrittsberechtigung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`freigabe_datum`) AS `freigabe_datum_unix` from (`zutrittsberechtigung` join `v_person_simple` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) ;
SET character_set_client      = @saved_cs_client ;
SET character_set_results     = @saved_cs_results ;
SET collation_connection      = @saved_col_connection ;
SET TIME_ZONE=@OLD_TIME_ZONE ;

SET SQL_MODE=@OLD_SQL_MODE ;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS ;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS ;
SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT ;
SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS ;
SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION ;
SET SQL_NOTES=@OLD_SQL_NOTES ;

-- Dump completed on 2019-07-04 14:08:26
