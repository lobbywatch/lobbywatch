-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 06. Jul 2014 um 21:51
-- Server Version: 5.6.12
-- PHP-Version: 5.5.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `lobbywatch`
--
CREATE DATABASE IF NOT EXISTS `lobbywatch` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `lobbywatch`;

DELIMITER $$
--
-- Prozeduren
--
DROP PROCEDURE IF EXISTS `takeSnapshot`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `takeSnapshot`(aVisa VARCHAR(10), aBeschreibung VARCHAR(150))
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

   INSERT INTO `interessengruppe_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessengruppe`;

   INSERT INTO `in_kommission_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `in_kommission`;

   INSERT INTO `kommission_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kommission`;

   INSERT INTO `mandat_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `mandat`;

   INSERT INTO `organisation_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation`;

   INSERT INTO `organisation_beziehung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_beziehung`;

   INSERT INTO `organisation_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_anhang`;

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

   INSERT INTO `zutrittsberechtigung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `zutrittsberechtigung`;

   INSERT INTO `zutrittsberechtigung_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `zutrittsberechtigung_anhang`;

END$$

--
-- Funktionen
--
DROP FUNCTION IF EXISTS `UTF8_URLENCODE`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `UTF8_URLENCODE`(str VARCHAR(4096) CHARSET utf8) RETURNS varchar(4096) CHARSET utf8
    DETERMINISTIC
    COMMENT 'Encode UTF-8 string as URL'
BEGIN
   -- the individual character we are converting in our loop
   -- NOTE: must be VARCHAR even though it won't vary in length
   -- CHAR(1), when used with SUBSTRING, made spaces '' instead of ' '
   DECLARE sub VARCHAR(1) CHARSET utf8;
   -- the ordinal value of the character (i.e. ñ becomes 50097)
   DECLARE val BIGINT DEFAULT 0;
   -- the substring index we use in our loop (one-based)
   DECLARE ind INT DEFAULT 1;
   -- the integer value of the individual octet of a character being encoded
   -- (which is potentially multi-byte and must be encoded one byte at a time)
   DECLARE oct INT DEFAULT 0;
   -- the encoded return string that we build up during execution
   DECLARE ret VARCHAR(4096) DEFAULT '';
   -- our loop index for looping through each octet while encoding
   DECLARE octind INT DEFAULT 0;

   IF ISNULL(str) THEN
      RETURN NULL;
   ELSE
      SET ret = '';
      -- loop through the input string one character at a time - regardless
      -- of how many bytes a character consists of
      WHILE ind <= CHAR_LENGTH(str) DO
         SET sub = MID(str, ind, 1);
         SET val = ORD(sub);
         -- these values are ones that should not be converted
         -- see http://tools.ietf.org/html/rfc3986
         IF NOT (val BETWEEN 48 AND 57 OR     -- 48-57  = 0-9
                 val BETWEEN 65 AND 90 OR     -- 65-90  = A-Z
                 val BETWEEN 97 AND 122 OR    -- 97-122 = a-z
                 -- 45 = hyphen, 46 = period, 95 = underscore, 126 = tilde
                 val IN (45, 46, 95, 126)) THEN
            -- This is not an "unreserved" char and must be encoded:
            -- loop through each octet of the potentially multi-octet character
            -- and convert each into its hexadecimal value
            -- we start with the high octect because that is the order that ORD
            -- returns them in - they need to be encoded with the most significant
            -- byte first
            SET octind = OCTET_LENGTH(sub);
            WHILE octind > 0 DO
               -- get the actual value of this octet by shifting it to the right
               -- so that it is at the lowest byte position - in other words, make
               -- the octet/byte we are working on the entire number (or in even
               -- other words, oct will no be between zero and 255 inclusive)
               SET oct = (val >> (8 * (octind - 1)));
               -- we append this to our return string with a percent sign, and then
               -- a left-zero-padded (to two characters) string of the hexadecimal
               -- value of this octet)
               SET ret = CONCAT(ret, '%', LPAD(HEX(oct), 2, 0));
               -- now we need to reset val to essentially zero out the octet that we
               -- just encoded so that our number decreases and we are only left with
               -- the lower octets as part of our integer
               SET val = (val & (POWER(256, (octind - 1)) - 1));
               SET octind = (octind - 1);
            END WHILE;
         ELSE
            -- this character was not one that needed to be encoded and can simply be
            -- added to our return string as-is
            SET ret = CONCAT(ret, sub);
         END IF;
         SET ind = (ind + 1);
      END WHILE;
   END IF;
   RETURN ret;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `branche`
--
-- Erzeugt am: 06. Jul 2014 um 17:39
--

DROP TABLE IF EXISTS `branche`;
CREATE TABLE IF NOT EXISTS `branche` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Branche',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `angaben` text COMMENT 'Angaben zur Branche',
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
  KEY `kommission_id` (`kommission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen' AUTO_INCREMENT=19 ;

--
-- RELATIONEN DER TABELLE `branche`:
--   `kommission_id`
--       `kommission` -> `id`
--

--
-- Trigger `branche`
--
DROP TRIGGER IF EXISTS `trg_branche_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_branche_log_del_after` AFTER DELETE ON `branche`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `branche_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_branche_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_branche_log_del_before` BEFORE DELETE ON `branche`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `branche` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_branche_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_branche_log_ins` AFTER INSERT ON `branche`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `branche` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_branche_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_branche_log_upd` AFTER UPDATE ON `branche`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'update', null, NOW(), null FROM `branche` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `branche_log`
--
-- Erzeugt am: 06. Jul 2014 um 17:39
--

DROP TABLE IF EXISTS `branche_log`;
CREATE TABLE IF NOT EXISTS `branche_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `angaben` text COMMENT 'Angaben zur Branche',
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
  KEY `fk_branche_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen' AUTO_INCREMENT=30 ;

--
-- RELATIONEN DER TABELLE `branche_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Länder der Welt mit ISO Code (http://countrylist.net)' AUTO_INCREMENT=252 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fraktion`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `fraktion`;
CREATE TABLE IF NOT EXISTS `fraktion` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Fraktion',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Fraktionsabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Fraktion',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Fraktion',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `beschreibung` text COMMENT 'Beschreibung der Fraktion',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes' AUTO_INCREMENT=8 ;

--
-- Trigger `fraktion`
--
DROP TRIGGER IF EXISTS `trg_fraktion_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_fraktion_log_del_after` AFTER DELETE ON `fraktion`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `fraktion_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_fraktion_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_fraktion_log_del_before` BEFORE DELETE ON `fraktion`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `fraktion` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_fraktion_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_fraktion_log_ins` AFTER INSERT ON `fraktion`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `fraktion` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_fraktion_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_fraktion_log_upd` AFTER UPDATE ON `fraktion`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'update', null, NOW(), null FROM `fraktion` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fraktion_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `fraktion_log`;
CREATE TABLE IF NOT EXISTS `fraktion_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Fraktionsabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Fraktion',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Fraktion',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `beschreibung` text COMMENT 'Beschreibung der Fraktion',
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
  KEY `fk_fraktion_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes' AUTO_INCREMENT=23 ;

--
-- RELATIONEN DER TABELLE `fraktion_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung`
--
-- Erzeugt am: 06. Jul 2014 um 11:31
--

DROP TABLE IF EXISTS `interessenbindung`;
CREATE TABLE IF NOT EXISTS `interessenbindung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessenbindung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
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
  UNIQUE KEY `interessenbindung_art_parlamentarier_organisation_unique` (`art`,`parlamentarier_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbyorg` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern' AUTO_INCREMENT=592 ;

--
-- RELATIONEN DER TABELLE `interessenbindung`:
--   `organisation_id`
--       `organisation` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

--
-- Trigger `interessenbindung`
--
DROP TRIGGER IF EXISTS `trg_interessenbindung_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_interessenbindung_log_del_after` AFTER DELETE ON `interessenbindung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessenbindung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_interessenbindung_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_interessenbindung_log_del_before` BEFORE DELETE ON `interessenbindung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessenbindung` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_interessenbindung_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_interessenbindung_log_ins` AFTER INSERT ON `interessenbindung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessenbindung` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_interessenbindung_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_interessenbindung_log_upd` AFTER UPDATE ON `interessenbindung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessenbindung` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `interessenbindung_log`;
CREATE TABLE IF NOT EXISTS `interessenbindung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','patronatskomitee') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
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
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbyorg` (`organisation_id`),
  KEY `fk_interessenbindung_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern' AUTO_INCREMENT=2008 ;

--
-- RELATIONEN DER TABELLE `interessenbindung_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessengruppe`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `interessengruppe`;
CREATE TABLE IF NOT EXISTS `interessengruppe` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessengruppe',
  `name` varchar(150) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
  `beschreibung` text NOT NULL COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe',
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
  KEY `idx_lobbytyp` (`branche_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche' AUTO_INCREMENT=103 ;

--
-- RELATIONEN DER TABELLE `interessengruppe`:
--   `branche_id`
--       `branche` -> `id`
--

--
-- Trigger `interessengruppe`
--
DROP TRIGGER IF EXISTS `trg_interessengruppe_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_interessengruppe_log_del_after` AFTER DELETE ON `interessengruppe`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessengruppe_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_interessengruppe_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_interessengruppe_log_del_before` BEFORE DELETE ON `interessengruppe`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessengruppe` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_interessengruppe_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_interessengruppe_log_ins` AFTER INSERT ON `interessengruppe`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessengruppe` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_interessengruppe_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_interessengruppe_log_upd` AFTER UPDATE ON `interessengruppe`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessengruppe` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessengruppe_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `interessengruppe_log`;
CREATE TABLE IF NOT EXISTS `interessengruppe_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(150) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
  `beschreibung` text NOT NULL COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe',
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
  KEY `fk_interessengruppe_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche' AUTO_INCREMENT=146 ;

--
-- RELATIONEN DER TABELLE `interessengruppe_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenraum`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `interessenraum`;
CREATE TABLE IF NOT EXISTS `interessenraum` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Interessenraumes',
  `name` varchar(50) NOT NULL COMMENT 'Name des Interessenbereiches',
  `beschreibung` text COMMENT 'Beschreibung des Interessenraumes',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Interessenbereiche (Stammdaten)' AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `in_kommission`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `in_kommission`;
CREATE TABLE IF NOT EXISTS `in_kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Kommissionszugehörigkeit',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` enum('praesident','vizepraesident','mitglied') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `parlamentarier_id` (`parlamentarier_id`),
  KEY `kommissions_id` (`kommission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern' AUTO_INCREMENT=159 ;

--
-- RELATIONEN DER TABELLE `in_kommission`:
--   `kommission_id`
--       `kommission` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

--
-- Trigger `in_kommission`
--
DROP TRIGGER IF EXISTS `trg_in_kommission_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_in_kommission_log_del_after` AFTER DELETE ON `in_kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `in_kommission_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
  -- Fill parlamentarier.kommissionen on change
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id)
    WHERE p.id=OLD.parlamentarier_id;
  SET @disable_table_logging = NULL;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_in_kommission_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_in_kommission_log_del_before` BEFORE DELETE ON `in_kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `in_kommission` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_in_kommission_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_in_kommission_log_ins` AFTER INSERT ON `in_kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `in_kommission` WHERE id = NEW.id ;
  -- Fill parlamentarier.kommissionen on change
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id)
    WHERE p.id=NEW.parlamentarier_id;
  SET @disable_table_logging = NULL;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_in_kommission_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_in_kommission_log_upd` AFTER UPDATE ON `in_kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'update', null, NOW(), null FROM `in_kommission` WHERE id = NEW.id ;
  -- Fill parlamentarier.kommissionen on change
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id)
    WHERE p.id=NEW.parlamentarier_id OR p.id=OLD.parlamentarier_id;
  SET @disable_table_logging = NULL;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `in_kommission_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `in_kommission_log`;
CREATE TABLE IF NOT EXISTS `in_kommission_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` enum('praesident','vizepraesident','mitglied') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
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
  KEY `fk_in_kommission_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern' AUTO_INCREMENT=306 ;

--
-- RELATIONEN DER TABELLE `in_kommission_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `kanton`;
CREATE TABLE IF NOT EXISTS `kanton` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kantone der Schweiz' AUTO_INCREMENT=27 ;

--
-- Trigger `kanton`
--
DROP TRIGGER IF EXISTS `trg_kanton_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_kanton_log_del_after` AFTER DELETE ON `kanton`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kanton_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kanton_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_kanton_log_del_before` BEFORE DELETE ON `kanton`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kanton` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kanton_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_kanton_log_ins` AFTER INSERT ON `kanton`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kanton` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kanton_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_kanton_log_upd` AFTER UPDATE ON `kanton`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kanton` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton_jahr`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `kanton_jahr`;
CREATE TABLE IF NOT EXISTS `kanton_jahr` (
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
  KEY `kanton_id` (`kanton_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Kantonen' AUTO_INCREMENT=27 ;

--
-- RELATIONEN DER TABELLE `kanton_jahr`:
--   `kanton_id`
--       `kanton` -> `id`
--

--
-- Trigger `kanton_jahr`
--
DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_kanton_jahr_log_del_after` AFTER DELETE ON `kanton_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kanton_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_kanton_jahr_log_del_before` BEFORE DELETE ON `kanton_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kanton_jahr` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_kanton_jahr_log_ins` AFTER INSERT ON `kanton_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kanton_jahr` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_kanton_jahr_log_upd` AFTER UPDATE ON `kanton_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kanton_jahr` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton_jahr_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `kanton_jahr_log`;
CREATE TABLE IF NOT EXISTS `kanton_jahr_log` (
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
  KEY `fk_kanton_jahr_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Kantonen' AUTO_INCREMENT=27 ;

--
-- RELATIONEN DER TABELLE `kanton_jahr_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `kanton_log`;
CREATE TABLE IF NOT EXISTS `kanton_log` (
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
  KEY `fk_kanton_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kantone der Schweiz' AUTO_INCREMENT=53 ;

--
-- RELATIONEN DER TABELLE `kanton_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `kommission`;
CREATE TABLE IF NOT EXISTS `kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Kommission',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `typ` enum('kommission','subkommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.',
  `beschreibung` text COMMENT 'Beschreibung der Kommission',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `mutter_kommission_id` int(11) DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen).  Also die "Oberkommission".',
  `parlament_url` varchar(255) DEFAULT NULL COMMENT 'Link zur Seite auf Parlament.ch',
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
  KEY `zugehoerige_kommission` (`mutter_kommission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen' AUTO_INCREMENT=46 ;

--
-- RELATIONEN DER TABELLE `kommission`:
--   `mutter_kommission_id`
--       `kommission` -> `id`
--

--
-- Trigger `kommission`
--
DROP TRIGGER IF EXISTS `trg_kommission_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_kommission_log_del_after` AFTER DELETE ON `kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kommission_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kommission_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_kommission_log_del_before` BEFORE DELETE ON `kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kommission` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kommission_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_kommission_log_ins` AFTER INSERT ON `kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kommission` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_kommission_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_kommission_log_upd` AFTER UPDATE ON `kommission`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kommission` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `kommission_log`;
CREATE TABLE IF NOT EXISTS `kommission_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `typ` enum('kommission','subkommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.',
  `beschreibung` text COMMENT 'Beschreibung der Kommission',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `mutter_kommission_id` int(11) DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen).  Also die "Oberkommission".',
  `parlament_url` varchar(255) DEFAULT NULL COMMENT 'Link zur Seite auf Parlament.ch',
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
  KEY `fk_kommission_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen' AUTO_INCREMENT=35 ;

--
-- RELATIONEN DER TABELLE `kommission_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat`
--
-- Erzeugt am: 06. Jul 2014 um 04:21
--

DROP TABLE IF EXISTS `mandat`;
CREATE TABLE IF NOT EXISTS `mandat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zutrittsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Zutrittsberechtigung.',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','patronatskomitee','finanziell') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mandat_zutrittsberechtigung_organisation_art_unique` (`art`,`zutrittsberechtigung_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `organisations_id` (`organisation_id`),
  KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten' AUTO_INCREMENT=247 ;

--
-- RELATIONEN DER TABELLE `mandat`:
--   `organisation_id`
--       `organisation` -> `id`
--   `zutrittsberechtigung_id`
--       `zutrittsberechtigung` -> `id`
--

--
-- Trigger `mandat`
--
DROP TRIGGER IF EXISTS `trg_mandat_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_mandat_log_del_after` AFTER DELETE ON `mandat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mandat_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_mandat_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_mandat_log_del_before` BEFORE DELETE ON `mandat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mandat` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_mandat_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_mandat_log_ins` AFTER INSERT ON `mandat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mandat` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_mandat_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_mandat_log_upd` AFTER UPDATE ON `mandat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mandat` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `mandat_log`;
CREATE TABLE IF NOT EXISTS `mandat_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `zutrittsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Zutrittsberechtigung.',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','patronatskomitee') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
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
  KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`),
  KEY `fk_mandat_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten' AUTO_INCREMENT=635 ;

--
-- RELATIONEN DER TABELLE `mandat_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mil_grad`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `mil_grad`;
CREATE TABLE IF NOT EXISTS `mil_grad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Militärischer Grad',
  `name` varchar(30) NOT NULL COMMENT 'Name des militärischen Grades',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Abkürzung des militärischen Grades',
  `typ` enum('Mannschaft','Unteroffizier','Hoeherer Unteroffizier','Offizier','Hoeherer Stabsoffizier') NOT NULL COMMENT 'Stufe des militärischen Grades',
  `ranghoehe` int(11) NOT NULL COMMENT 'Ranghöhe des Grades',
  `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique` (`name`),
  UNIQUE KEY `abkuerzung_unique` (`abkuerzung`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Militärische Grade (Armee XXI)' AUTO_INCREMENT=24 ;

--
-- Trigger `mil_grad`
--
DROP TRIGGER IF EXISTS `trg_mil_grad_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_mil_grad_log_del_after` AFTER DELETE ON `mil_grad`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mil_grad_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_mil_grad_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_mil_grad_log_del_before` BEFORE DELETE ON `mil_grad`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mil_grad` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_mil_grad_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_mil_grad_log_ins` AFTER INSERT ON `mil_grad`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mil_grad` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_mil_grad_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_mil_grad_log_upd` AFTER UPDATE ON `mil_grad`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mil_grad` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mil_grad_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `mil_grad_log`;
CREATE TABLE IF NOT EXISTS `mil_grad_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(30) NOT NULL COMMENT 'Name des militärischen Grades',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Abkürzung des militärischen Grades',
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
  KEY `fk_mil_grad_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Militärische Grade (Armee XXI)' AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `mil_grad_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation`
--
-- Erzeugt am: 06. Jul 2014 um 04:03
--

DROP TABLE IF EXISTS `organisation`;
CREATE TABLE IF NOT EXISTS `organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `ort` varchar(100) DEFAULT NULL COMMENT 'Ort der Organisation',
  `land_id` int(11) DEFAULT NULL COMMENT 'Land der Organisation',
  `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum der Organisation',
  `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft') DEFAULT NULL COMMENT 'Rechtsform der Organisation',
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
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
  `ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') DEFAULT NULL COMMENT 'Einschätzung der Verbindung der Organisation ins Parlament',
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
  UNIQUE KEY `organisation_name_de_unique` (`name_de`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `organisation_name_fr_unique` (`name_fr`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `organisation_name_it_unique` (`name_it`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `interessengruppe2_id` (`interessengruppe2_id`),
  KEY `interessengruppe3_id` (`interessengruppe3_id`),
  KEY `land` (`land_id`),
  KEY `interessenraum_id` (`interessenraum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen' AUTO_INCREMENT=751 ;

--
-- RELATIONEN DER TABELLE `organisation`:
--   `interessengruppe_id`
--       `interessengruppe` -> `id`
--   `branche_id`
--       `branche` -> `id`
--   `interessengruppe2_id`
--       `interessengruppe` -> `id`
--   `interessengruppe3_id`
--       `interessengruppe` -> `id`
--   `land_id`
--       `country` -> `id`
--   `interessenraum_id`
--       `interessenraum` -> `id`
--

--
-- Trigger `organisation`
--
DROP TRIGGER IF EXISTS `trg_organisation_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_organisation_log_del_after` AFTER DELETE ON `organisation`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_organisation_log_del_before` BEFORE DELETE ON `organisation`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_organisation_log_ins` AFTER INSERT ON `organisation`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_organisation_log_upd` AFTER UPDATE ON `organisation`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_name_ins`;
DELIMITER //
CREATE TRIGGER `trg_organisation_name_ins` BEFORE INSERT ON `organisation`
 FOR EACH ROW thisTrigger: begin
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
    if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
        call organisation_name_de_fr_it_must_be_set;
    end if;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_name_upd`;
DELIMITER //
CREATE TRIGGER `trg_organisation_name_upd` BEFORE UPDATE ON `organisation`
 FOR EACH ROW thisTrigger: begin
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
    if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
        call organisation_name_de_fr_it_must_be_set;
    end if;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_anhang`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `organisation_anhang`;
CREATE TABLE IF NOT EXISTS `organisation_anhang` (
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
  KEY `organisation_id` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen' AUTO_INCREMENT=2 ;

--
-- RELATIONEN DER TABELLE `organisation_anhang`:
--   `organisation_id`
--       `organisation` -> `id`
--

--
-- Trigger `organisation_anhang`
--
DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_organisation_anhang_log_del_after` AFTER DELETE ON `organisation_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_organisation_anhang_log_del_before` BEFORE DELETE ON `organisation_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_anhang` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_organisation_anhang_log_ins` AFTER INSERT ON `organisation_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_organisation_anhang_log_upd` AFTER UPDATE ON `organisation_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_anhang_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `organisation_anhang_log`;
CREATE TABLE IF NOT EXISTS `organisation_anhang_log` (
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
  KEY `fk_organisation_anhang_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen' AUTO_INCREMENT=2 ;

--
-- RELATIONEN DER TABELLE `organisation_anhang_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_beziehung`
--
-- Erzeugt am: 06. Jul 2014 um 04:39
--

DROP TABLE IF EXISTS `organisation_beziehung`;
CREATE TABLE IF NOT EXISTS `organisation_beziehung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Organisationsbeziehung',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organisation_beziehung_organisation_zielorganisation_art_unique` (`art`,`organisation_id`,`ziel_organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `organisation_id` (`organisation_id`),
  KEY `ziel_organisation_id` (`ziel_organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander' AUTO_INCREMENT=186 ;

--
-- RELATIONEN DER TABELLE `organisation_beziehung`:
--   `organisation_id`
--       `organisation` -> `id`
--   `ziel_organisation_id`
--       `organisation` -> `id`
--

--
-- Trigger `organisation_beziehung`
--
DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_organisation_beziehung_log_del_after` AFTER DELETE ON `organisation_beziehung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_beziehung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_organisation_beziehung_log_del_before` BEFORE DELETE ON `organisation_beziehung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_beziehung` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_organisation_beziehung_log_ins` AFTER INSERT ON `organisation_beziehung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_beziehung` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_organisation_beziehung_log_upd` AFTER UPDATE ON `organisation_beziehung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_beziehung` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_beziehung_log`
--
-- Erzeugt am: 06. Jul 2014 um 04:40
--

DROP TABLE IF EXISTS `organisation_beziehung_log`;
CREATE TABLE IF NOT EXISTS `organisation_beziehung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
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
  KEY `fk_organisation_beziehung_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander' AUTO_INCREMENT=346 ;

--
-- RELATIONEN DER TABELLE `organisation_beziehung_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_jahr`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `organisation_jahr`;
CREATE TABLE IF NOT EXISTS `organisation_jahr` (
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
  KEY `organisation_id` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen' AUTO_INCREMENT=2 ;

--
-- RELATIONEN DER TABELLE `organisation_jahr`:
--   `organisation_id`
--       `organisation` -> `id`
--

--
-- Trigger `organisation_jahr`
--
DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_organisation_jahr_log_del_after` AFTER DELETE ON `organisation_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_organisation_jahr_log_del_before` BEFORE DELETE ON `organisation_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_jahr` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_organisation_jahr_log_ins` AFTER INSERT ON `organisation_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_jahr` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_organisation_jahr_log_upd` AFTER UPDATE ON `organisation_jahr`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_jahr` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_jahr_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `organisation_jahr_log`;
CREATE TABLE IF NOT EXISTS `organisation_jahr_log` (
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
  KEY `fk_organisation_jahr_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen' AUTO_INCREMENT=2 ;

--
-- RELATIONEN DER TABELLE `organisation_jahr_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_log`
--
-- Erzeugt am: 06. Jul 2014 um 04:03
--

DROP TABLE IF EXISTS `organisation_log`;
CREATE TABLE IF NOT EXISTS `organisation_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `ort` varchar(100) DEFAULT NULL COMMENT 'Ort der Organisation',
  `land_id` int(11) DEFAULT NULL COMMENT 'Land der Organisation',
  `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum der Organisation',
  `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft') DEFAULT NULL COMMENT 'Rechtsform der Organisation',
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
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
  `ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') DEFAULT NULL COMMENT 'Einschätzung der Verbindung der Organisation ins Parlament',
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
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `interessengruppe2_id` (`interessengruppe2_id`),
  KEY `interessengruppe3_id` (`interessengruppe3_id`),
  KEY `fk_organisation_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen' AUTO_INCREMENT=2875 ;

--
-- RELATIONEN DER TABELLE `organisation_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier`
--
-- Erzeugt am: 06. Jul 2014 um 11:31
--

DROP TABLE IF EXISTS `parlamentarier`;
CREATE TABLE IF NOT EXISTS `parlamentarier` (
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
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `zivilstand` enum('ledig','verheiratet','geschieden','eingetragene partnerschaft') DEFAULT NULL COMMENT 'Zivilstand',
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
  `parlament_biografie_id` int(11) DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch',
  `adresse_firma` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_ort` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `ALT_kommission` varchar(255) DEFAULT NULL COMMENT 'Kommissionen als Einträge in Tabelle "in_kommission" erfassen. Wird später entfernt. Mitglied in Kommission(en) als Freitext',
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
  UNIQUE KEY `parlamentarier_nachname_vorname_unique` (`nachname`,`vorname`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `parlamentarier_rat_sitzplatz` (`rat_id`,`sitzplatz`,`im_rat_bis`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_partei` (`partei_id`),
  KEY `beruf_branche_id` (`beruf_interessengruppe_id`),
  KEY `militaerischer_grad` (`militaerischer_grad_id`),
  KEY `fraktion_id` (`fraktion_id`),
  KEY `rat_id` (`rat_id`),
  KEY `kanton_id` (`kanton_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier' AUTO_INCREMENT=254 ;

--
-- RELATIONEN DER TABELLE `parlamentarier`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `kanton_id`
--       `kanton` -> `id`
--   `militaerischer_grad_id`
--       `mil_grad` -> `id`
--   `fraktion_id`
--       `fraktion` -> `id`
--   `partei_id`
--       `partei` -> `id`
--   `rat_id`
--       `rat` -> `id`
--

--
-- Trigger `parlamentarier`
--
DROP TRIGGER IF EXISTS `trg_parlamentarier_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_log_del_after` AFTER DELETE ON `parlamentarier`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `parlamentarier_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_parlamentarier_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_log_del_before` BEFORE DELETE ON `parlamentarier`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `parlamentarier` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_parlamentarier_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_log_ins` AFTER INSERT ON `parlamentarier`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `parlamentarier` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_parlamentarier_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_log_upd` AFTER UPDATE ON `parlamentarier`
 FOR EACH ROW thisTrigger: begin

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate authorization from parlamentarier to his interessenbindungen
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `interessenbindung`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = NEW.autorisiert_visa,
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        parlamentarier_id=NEW.id AND bis IS NULL;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'update', null, NOW(), null FROM `parlamentarier` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier_anhang`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `parlamentarier_anhang`;
CREATE TABLE IF NOT EXISTS `parlamentarier_anhang` (
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  KEY `parlamentarier_id` (`parlamentarier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern' AUTO_INCREMENT=45 ;

--
-- RELATIONEN DER TABELLE `parlamentarier_anhang`:
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

--
-- Trigger `parlamentarier_anhang`
--
DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_anhang_log_del_after` AFTER DELETE ON `parlamentarier_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `parlamentarier_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_anhang_log_del_before` BEFORE DELETE ON `parlamentarier_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_anhang_log_ins` AFTER INSERT ON `parlamentarier_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_parlamentarier_anhang_log_upd` AFTER UPDATE ON `parlamentarier_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier_anhang_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `parlamentarier_anhang_log`;
CREATE TABLE IF NOT EXISTS `parlamentarier_anhang_log` (
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
  KEY `fk_parlamentarier_anhang_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern' AUTO_INCREMENT=52 ;

--
-- RELATIONEN DER TABELLE `parlamentarier_anhang_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier_log`
--
-- Erzeugt am: 06. Jul 2014 um 11:31
--

DROP TABLE IF EXISTS `parlamentarier_log`;
CREATE TABLE IF NOT EXISTS `parlamentarier_log` (
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
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `zivilstand` enum('ledig','verheiratet','geschieden','eingetragene partnerschaft') DEFAULT NULL COMMENT 'Zivilstand',
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
  `parlament_biografie_id` int(11) DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch',
  `adresse_firma` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `adresse_ort` varchar(100) DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch',
  `ALT_kommission` varchar(255) DEFAULT NULL COMMENT 'Kommissionen als Einträge in Tabelle "in_kommission" erfassen. Wird später entfernt. Mitglied in Kommission(en) als Freitext',
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
  KEY `fk_parlamentarier_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier' AUTO_INCREMENT=1609 ;

--
-- RELATIONEN DER TABELLE `parlamentarier_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `partei`;
CREATE TABLE IF NOT EXISTS `partei` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Partei',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit der Partei im nationalen Parlament',
  `gruendung` date DEFAULT NULL COMMENT 'Gründungsjahr der Partei. Wenn der genaue Tag unbekannt ist, den 1. Januar wählen.',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der Partei',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der Partei',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Partei',
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
  KEY `fraktion_id` (`fraktion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes' AUTO_INCREMENT=13 ;

--
-- RELATIONEN DER TABELLE `partei`:
--   `fraktion_id`
--       `fraktion` -> `id`
--

--
-- Trigger `partei`
--
DROP TRIGGER IF EXISTS `trg_partei_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_partei_log_del_after` AFTER DELETE ON `partei`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `partei_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_partei_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_partei_log_del_before` BEFORE DELETE ON `partei`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `partei` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_partei_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_partei_log_ins` AFTER INSERT ON `partei`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `partei` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_partei_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_partei_log_upd` AFTER UPDATE ON `partei`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'update', null, NOW(), null FROM `partei` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `partei_log`;
CREATE TABLE IF NOT EXISTS `partei_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `fraktion_id` int(11) DEFAULT NULL COMMENT 'Fraktionszugehörigkeit der Partei im nationalen Parlament',
  `gruendung` date DEFAULT NULL COMMENT 'Gründungsjahr der Partei. Wenn der genaue Tag unbekannt ist, den 1. Januar wählen.',
  `position` enum('links','rechts','mitte') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der Partei',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der Partei',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `beschreibung` text COMMENT 'Beschreibung der Partei',
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
  KEY `fk_partei_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes' AUTO_INCREMENT=25 ;

--
-- RELATIONEN DER TABELLE `partei_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rat`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `rat`;
CREATE TABLE IF NOT EXISTS `rat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte eines Rates',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Kürzel des Rates',
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
  KEY `interessenraum_id` (`interessenraum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabelle der Räte von Lobbywatch' AUTO_INCREMENT=4 ;

--
-- RELATIONEN DER TABELLE `rat`:
--   `interessenraum_id`
--       `interessenraum` -> `id`
--

--
-- Trigger `rat`
--
DROP TRIGGER IF EXISTS `trg_rat_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_rat_log_del_after` AFTER DELETE ON `rat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `rat_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_rat_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_rat_log_del_before` BEFORE DELETE ON `rat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `rat` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_rat_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_rat_log_ins` AFTER INSERT ON `rat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `rat` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_rat_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_rat_log_upd` AFTER UPDATE ON `rat`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'update', null, NOW(), null FROM `rat` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rat_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `rat_log`;
CREATE TABLE IF NOT EXISTS `rat_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(10) NOT NULL COMMENT 'Kürzel des Rates',
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
  KEY `fk_rat_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tabelle der Räte von Lobbywatch' AUTO_INCREMENT=8 ;

--
-- RELATIONEN DER TABELLE `rat_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
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
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Einstellungen zur Lobbywatch-DB' AUTO_INCREMENT=9 ;

--
-- RELATIONEN DER TABELLE `settings`:
--   `category_id`
--       `settings_category` -> `id`
--

--
-- Trigger `settings`
--
DROP TRIGGER IF EXISTS `trg_settings_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_settings_log_del_after` AFTER DELETE ON `settings`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `settings_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_settings_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_settings_log_del_before` BEFORE DELETE ON `settings`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `settings` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_settings_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_settings_log_ins` AFTER INSERT ON `settings`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `settings` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_settings_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_settings_log_upd` AFTER UPDATE ON `settings`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'update', null, NOW(), null FROM `settings` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_category`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `settings_category`;
CREATE TABLE IF NOT EXISTS `settings_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Settingsateogrie',
  `name` varchar(50) NOT NULL COMMENT 'Name der Settingskategorie',
  `description` text NOT NULL COMMENT 'Beschreibung der Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kategorie für Settings' AUTO_INCREMENT=3 ;

--
-- Trigger `settings_category`
--
DROP TRIGGER IF EXISTS `trg_settings_category_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_settings_category_log_del_after` AFTER DELETE ON `settings_category`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `settings_category_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_settings_category_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_settings_category_log_del_before` BEFORE DELETE ON `settings_category`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `settings_category` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_settings_category_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_settings_category_log_ins` AFTER INSERT ON `settings_category`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `settings_category` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_settings_category_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_settings_category_log_upd` AFTER UPDATE ON `settings_category`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'update', null, NOW(), null FROM `settings_category` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_category_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `settings_category_log`;
CREATE TABLE IF NOT EXISTS `settings_category_log` (
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
  KEY `fk_settings_category_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kategorie für Settings' AUTO_INCREMENT=3 ;

--
-- RELATIONEN DER TABELLE `settings_category_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `settings_log`;
CREATE TABLE IF NOT EXISTS `settings_log` (
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
  KEY `fk_settings_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Einstellungen zur Lobbywatch-DB' AUTO_INCREMENT=30 ;

--
-- RELATIONEN DER TABELLE `settings_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `snapshot`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `snapshot`;
CREATE TABLE IF NOT EXISTS `snapshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Snapshots',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Snapshots',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Eintrge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) NOT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lobbywatch snapshots' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--
-- Erzeugt am: 06. Jul 2014 um 05:02
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User',
  `name` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nachname` varchar(100) DEFAULT NULL COMMENT 'Nachname des Benutzers',
  `vorname` varchar(50) DEFAULT NULL COMMENT 'Vorname des Benutzers',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Benutzers',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'Datum des letzten Login',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='PHP Generator users' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_permission`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `user_permission`;
CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User Persmissions',
  `user_id` int(11) NOT NULL,
  `page_name` varchar(500) DEFAULT NULL,
  `permission_name` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='PHP Generator user permissions' AUTO_INCREMENT=224 ;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_branche`
--
DROP VIEW IF EXISTS `v_branche`;
CREATE TABLE IF NOT EXISTS `v_branche` (
`anzeige_name` varchar(100)
,`id` int(11)
,`name` varchar(100)
,`kommission_id` int(11)
,`beschreibung` text
,`angaben` text
,`farbcode` varchar(15)
,`symbol_abs` varchar(255)
,`symbol_rel` varchar(255)
,`symbol_klein_rel` varchar(255)
,`symbol_dateiname_wo_ext` varchar(255)
,`symbol_dateierweiterung` varchar(15)
,`symbol_dateiname` varchar(255)
,`symbol_mime_type` varchar(100)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`kommission` varchar(118)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_country`
--
DROP VIEW IF EXISTS `v_country`;
CREATE TABLE IF NOT EXISTS `v_country` (
`anzeige_name` varchar(200)
,`id` int(10)
,`continent` enum('Antarctica','Australia','Africa','North America','South America','Europe','Asia')
,`name_en` varchar(200)
,`official_name_en` varchar(200)
,`capital_en` varchar(200)
,`name_de` varchar(200)
,`official_name_de` varchar(200)
,`capital_de` varchar(200)
,`name_fr` varchar(200)
,`official_name_fr` varchar(200)
,`capital_fr` varchar(200)
,`name_it` varchar(200)
,`official_name_it` varchar(200)
,`capital_it` varchar(200)
,`iso-2` varchar(2)
,`iso-3` varchar(3)
,`vehicle_code` varchar(4)
,`ioc` varchar(3)
,`tld` varchar(6)
,`currency` varchar(5)
,`phone` varchar(10)
,`utc` mediumint(9)
,`show_level` int(11)
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_fraktion`
--
DROP VIEW IF EXISTS `v_fraktion`;
CREATE TABLE IF NOT EXISTS `v_fraktion` (
`anzeige_name` varchar(122)
,`id` int(11)
,`abkuerzung` varchar(20)
,`name` varchar(100)
,`position` enum('links','rechts','mitte')
,`farbcode` varchar(15)
,`beschreibung` text
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung`
--
DROP VIEW IF EXISTS `v_interessenbindung`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung` (
`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_authorisierungs_email`
--
DROP VIEW IF EXISTS `v_interessenbindung_authorisierungs_email`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_authorisierungs_email` (
`parlamentarier_name` varchar(202)
,`geschlecht` varchar(1)
,`organisation_name` varchar(454)
,`rechtsform` varchar(33)
,`ort` varchar(100)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell')
,`beschreibung` varchar(150)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_liste`
--
DROP VIEW IF EXISTS `v_interessenbindung_liste`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_liste` (
`organisation_name` varchar(454)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_liste_indirekt`
--
DROP VIEW IF EXISTS `v_interessenbindung_liste_indirekt`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_liste_indirekt` (
`beziehung` varchar(8)
,`organisation_name` varchar(454)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`funktion_im_gremium` varchar(14)
,`deklarationstyp` varchar(25)
,`status` varchar(16)
,`behoerden_vertreter` varchar(1)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessengruppe`
--
DROP VIEW IF EXISTS `v_interessengruppe`;
CREATE TABLE IF NOT EXISTS `v_interessengruppe` (
`anzeige_name` varchar(150)
,`id` int(11)
,`name` varchar(150)
,`branche_id` int(11)
,`beschreibung` text
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`kommission_id` int(11)
,`kommission` varchar(118)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenraum`
--
DROP VIEW IF EXISTS `v_interessenraum`;
CREATE TABLE IF NOT EXISTS `v_interessenraum` (
`anzeige_name` varchar(50)
,`id` int(11)
,`name` varchar(50)
,`beschreibung` text
,`reihenfolge` int(11)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission`
--
DROP VIEW IF EXISTS `v_in_kommission`;
CREATE TABLE IF NOT EXISTS `v_in_kommission` (
`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` enum('praesident','vizepraesident','mitglied')
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`rat` varchar(10)
,`ratstyp` varchar(10)
,`partei_id` int(11)
,`fraktion_id` int(11)
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission_liste`
--
DROP VIEW IF EXISTS `v_in_kommission_liste`;
CREATE TABLE IF NOT EXISTS `v_in_kommission_liste` (
`abkuerzung` varchar(15)
,`name` varchar(100)
,`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` enum('praesident','vizepraesident','mitglied')
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`rat` varchar(10)
,`ratstyp` varchar(10)
,`partei_id` int(11)
,`fraktion_id` int(11)
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission_parlamentarier`
--
DROP VIEW IF EXISTS `v_in_kommission_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_in_kommission_parlamentarier` (
`parlamentarier_name` varchar(152)
,`partei` varchar(20)
,`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` enum('praesident','vizepraesident','mitglied')
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`rat` varchar(10)
,`ratstyp` varchar(10)
,`partei_id` int(11)
,`fraktion_id` int(11)
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kanton`
--
DROP VIEW IF EXISTS `v_kanton`;
CREATE TABLE IF NOT EXISTS `v_kanton` (
`anzeige_name` varchar(50)
,`id` int(11)
,`abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`kantonsnr` tinyint(4)
,`name_de` varchar(50)
,`name_fr` varchar(50)
,`name_it` varchar(50)
,`anzahl_staenderaete` tinyint(4)
,`amtssprache` set('de','fr','it','rm')
,`hauptort_de` varchar(50)
,`hauptort_fr` varchar(50)
,`hauptort_it` varchar(50)
,`flaeche_km2` int(11) unsigned
,`beitrittsjahr` smallint(6) unsigned
,`wappen_klein` varchar(255)
,`wappen` varchar(255)
,`lagebild` varchar(255)
,`homepage` varchar(255)
,`beschreibung` text
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`kanton_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`einwohner` int(11) unsigned
,`auslaenderanteil` float
,`bevoelkerungsdichte` smallint(6) unsigned
,`anzahl_gemeinden` smallint(6) unsigned
,`anzahl_nationalraete` tinyint(4) unsigned
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kanton_2012`
--
DROP VIEW IF EXISTS `v_kanton_2012`;
CREATE TABLE IF NOT EXISTS `v_kanton_2012` (
`anzeige_name` varchar(50)
,`id` int(11)
,`abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`kantonsnr` tinyint(4)
,`name_de` varchar(50)
,`name_fr` varchar(50)
,`name_it` varchar(50)
,`anzahl_staenderaete` tinyint(4)
,`amtssprache` set('de','fr','it','rm')
,`hauptort_de` varchar(50)
,`hauptort_fr` varchar(50)
,`hauptort_it` varchar(50)
,`flaeche_km2` int(11) unsigned
,`beitrittsjahr` smallint(6) unsigned
,`wappen_klein` varchar(255)
,`wappen` varchar(255)
,`lagebild` varchar(255)
,`homepage` varchar(255)
,`beschreibung` text
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`kanton_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`einwohner` int(11) unsigned
,`auslaenderanteil` float
,`bevoelkerungsdichte` smallint(6) unsigned
,`anzahl_gemeinden` smallint(6) unsigned
,`anzahl_nationalraete` tinyint(4) unsigned
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kanton_jahr`
--
DROP VIEW IF EXISTS `v_kanton_jahr`;
CREATE TABLE IF NOT EXISTS `v_kanton_jahr` (
`id` int(11)
,`kanton_id` int(11)
,`jahr` smallint(6) unsigned
,`anzahl_nationalraete` tinyint(4) unsigned
,`einwohner` int(11) unsigned
,`auslaenderanteil` float
,`bevoelkerungsdichte` smallint(6) unsigned
,`anzahl_gemeinden` smallint(6) unsigned
,`steuereinnahmen` int(11) unsigned
,`ausgaben` int(11)
,`finanzausgleich` int(11)
,`schulden` int(11)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kanton_jahr_last`
--
DROP VIEW IF EXISTS `v_kanton_jahr_last`;
CREATE TABLE IF NOT EXISTS `v_kanton_jahr_last` (
`max_jahr` smallint(6) unsigned
,`id` int(11)
,`kanton_id` int(11)
,`jahr` smallint(6) unsigned
,`anzahl_nationalraete` tinyint(4) unsigned
,`einwohner` int(11) unsigned
,`auslaenderanteil` float
,`bevoelkerungsdichte` smallint(6) unsigned
,`anzahl_gemeinden` smallint(6) unsigned
,`steuereinnahmen` int(11) unsigned
,`ausgaben` int(11)
,`finanzausgleich` int(11)
,`schulden` int(11)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kommission`
--
DROP VIEW IF EXISTS `v_kommission`;
CREATE TABLE IF NOT EXISTS `v_kommission` (
`anzeige_name` varchar(118)
,`id` int(11)
,`abkuerzung` varchar(15)
,`name` varchar(100)
,`typ` enum('kommission','subkommission','spezialkommission')
,`art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne')
,`beschreibung` text
,`sachbereiche` text
,`mutter_kommission_id` int(11)
,`parlament_url` varchar(255)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_branche`
--
DROP VIEW IF EXISTS `v_last_updated_branche`;
CREATE TABLE IF NOT EXISTS `v_last_updated_branche` (
`table_name` varchar(7)
,`name` varchar(7)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_fraktion`
--
DROP VIEW IF EXISTS `v_last_updated_fraktion`;
CREATE TABLE IF NOT EXISTS `v_last_updated_fraktion` (
`table_name` varchar(8)
,`name` varchar(8)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_interessenbindung`
--
DROP VIEW IF EXISTS `v_last_updated_interessenbindung`;
CREATE TABLE IF NOT EXISTS `v_last_updated_interessenbindung` (
`table_name` varchar(17)
,`name` varchar(17)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_interessengruppe`
--
DROP VIEW IF EXISTS `v_last_updated_interessengruppe`;
CREATE TABLE IF NOT EXISTS `v_last_updated_interessengruppe` (
`table_name` varchar(16)
,`name` varchar(11)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_in_kommission`
--
DROP VIEW IF EXISTS `v_last_updated_in_kommission`;
CREATE TABLE IF NOT EXISTS `v_last_updated_in_kommission` (
`table_name` varchar(13)
,`name` varchar(13)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_kanton`
--
DROP VIEW IF EXISTS `v_last_updated_kanton`;
CREATE TABLE IF NOT EXISTS `v_last_updated_kanton` (
`table_name` varchar(6)
,`name` varchar(6)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_kanton_jahr`
--
DROP VIEW IF EXISTS `v_last_updated_kanton_jahr`;
CREATE TABLE IF NOT EXISTS `v_last_updated_kanton_jahr` (
`table_name` varchar(11)
,`name` varchar(10)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_kommission`
--
DROP VIEW IF EXISTS `v_last_updated_kommission`;
CREATE TABLE IF NOT EXISTS `v_last_updated_kommission` (
`table_name` varchar(10)
,`name` varchar(10)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_mandat`
--
DROP VIEW IF EXISTS `v_last_updated_mandat`;
CREATE TABLE IF NOT EXISTS `v_last_updated_mandat` (
`table_name` varchar(6)
,`name` varchar(6)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_organisation`
--
DROP VIEW IF EXISTS `v_last_updated_organisation`;
CREATE TABLE IF NOT EXISTS `v_last_updated_organisation` (
`table_name` varchar(12)
,`name` varchar(12)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_organisation_anhang`
--
DROP VIEW IF EXISTS `v_last_updated_organisation_anhang`;
CREATE TABLE IF NOT EXISTS `v_last_updated_organisation_anhang` (
`table_name` varchar(19)
,`name` varchar(19)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_organisation_beziehung`
--
DROP VIEW IF EXISTS `v_last_updated_organisation_beziehung`;
CREATE TABLE IF NOT EXISTS `v_last_updated_organisation_beziehung` (
`table_name` varchar(22)
,`name` varchar(22)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_organisation_jahr`
--
DROP VIEW IF EXISTS `v_last_updated_organisation_jahr`;
CREATE TABLE IF NOT EXISTS `v_last_updated_organisation_jahr` (
`table_name` varchar(17)
,`name` varchar(17)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_parlamentarier`
--
DROP VIEW IF EXISTS `v_last_updated_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_last_updated_parlamentarier` (
`table_name` varchar(14)
,`name` varchar(14)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_parlamentarier_anhang`
--
DROP VIEW IF EXISTS `v_last_updated_parlamentarier_anhang`;
CREATE TABLE IF NOT EXISTS `v_last_updated_parlamentarier_anhang` (
`table_name` varchar(21)
,`name` varchar(20)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_partei`
--
DROP VIEW IF EXISTS `v_last_updated_partei`;
CREATE TABLE IF NOT EXISTS `v_last_updated_partei` (
`table_name` varchar(6)
,`name` varchar(6)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_rat`
--
DROP VIEW IF EXISTS `v_last_updated_rat`;
CREATE TABLE IF NOT EXISTS `v_last_updated_rat` (
`table_name` varchar(3)
,`name` varchar(3)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_settings`
--
DROP VIEW IF EXISTS `v_last_updated_settings`;
CREATE TABLE IF NOT EXISTS `v_last_updated_settings` (
`table_name` varchar(8)
,`name` varchar(13)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_settings_category`
--
DROP VIEW IF EXISTS `v_last_updated_settings_category`;
CREATE TABLE IF NOT EXISTS `v_last_updated_settings_category` (
`table_name` varchar(17)
,`name` varchar(22)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_tables`
--
DROP VIEW IF EXISTS `v_last_updated_tables`;
CREATE TABLE IF NOT EXISTS `v_last_updated_tables` (
`table_name` varchar(27)
,`name` varchar(26)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_tables_unordered`
--
DROP VIEW IF EXISTS `v_last_updated_tables_unordered`;
CREATE TABLE IF NOT EXISTS `v_last_updated_tables_unordered` (
`table_name` varchar(27)
,`name` varchar(26)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_zutrittsberechtigung`
--
DROP VIEW IF EXISTS `v_last_updated_zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `v_last_updated_zutrittsberechtigung` (
`table_name` varchar(20)
,`name` varchar(20)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_zutrittsberechtigung_anhang`
--
DROP VIEW IF EXISTS `v_last_updated_zutrittsberechtigung_anhang`;
CREATE TABLE IF NOT EXISTS `v_last_updated_zutrittsberechtigung_anhang` (
`table_name` varchar(27)
,`name` varchar(26)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mandat`
--
DROP VIEW IF EXISTS `v_mandat`;
CREATE TABLE IF NOT EXISTS `v_mandat` (
`id` int(11)
,`zutrittsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','patronatskomitee','finanziell')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mil_grad`
--
DROP VIEW IF EXISTS `v_mil_grad`;
CREATE TABLE IF NOT EXISTS `v_mil_grad` (
`id` int(11)
,`name` varchar(30)
,`abkuerzung` varchar(10)
,`typ` enum('Mannschaft','Unteroffizier','Hoeherer Unteroffizier','Offizier','Hoeherer Stabsoffizier')
,`ranghoehe` int(11)
,`anzeigestufe` int(11)
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation`
--
DROP VIEW IF EXISTS `v_organisation`;
CREATE TABLE IF NOT EXISTS `v_organisation` (
`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_anhang`
--
DROP VIEW IF EXISTS `v_organisation_anhang`;
CREATE TABLE IF NOT EXISTS `v_organisation_anhang` (
`organisation_id2` int(11)
,`id` int(11)
,`organisation_id` int(11)
,`datei` varchar(255)
,`dateiname` varchar(255)
,`dateierweiterung` varchar(15)
,`dateiname_voll` varchar(255)
,`mime_type` varchar(100)
,`encoding` varchar(50)
,`beschreibung` varchar(150)
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung`
--
DROP VIEW IF EXISTS `v_organisation_beziehung`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung` (
`id` int(11)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_arbeitet_fuer`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_arbeitet_fuer` (
`organisation_name` varchar(454)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_auftraggeber_fuer`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_auftraggeber_fuer` (
`organisation_name` varchar(454)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_mitglieder`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglieder`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_mitglieder` (
`organisation_name` varchar(454)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_mitglied_von`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglied_von`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_mitglied_von` (
`organisation_name` varchar(454)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_muttergesellschaft`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_muttergesellschaft`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_muttergesellschaft` (
`organisation_name` varchar(454)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_tochtergesellschaften`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_tochtergesellschaften` (
`organisation_name` varchar(454)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von')
,`von` date
,`bis` date
,`anzeige_name` varchar(454)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby','Gemeinnuetzig','Gewinnorientiert')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`land` varchar(200)
,`interessenraum` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_jahr`
--
DROP VIEW IF EXISTS `v_organisation_jahr`;
CREATE TABLE IF NOT EXISTS `v_organisation_jahr` (
`id` int(11)
,`organisation_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_jahr_last`
--
DROP VIEW IF EXISTS `v_organisation_jahr_last`;
CREATE TABLE IF NOT EXISTS `v_organisation_jahr_last` (
`max_jahr` smallint(6) unsigned
,`id` int(11)
,`organisation_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier` (
`parlamentarier_name` varchar(152)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier_beide`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier_beide`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier_beide` (
`verbindung` varchar(17)
,`parlamentarier_id` int(11)
,`parlamentarier_name` varchar(152)
,`ratstyp` varchar(10)
,`kanton` varchar(2)
,`partei_id` int(11)
,`partei` varchar(20)
,`kommissionen` varchar(75)
,`parlament_biografie_id` int(11)
,`zutrittsberechtigung_id` int(11)
,`zutrittsberechtigter` varchar(152)
,`art` varchar(18)
,`von` date
,`bis` date
,`organisation_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier_beide_indirekt`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier_beide_indirekt`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier_beide_indirekt` (
`beziehung` varchar(8)
,`verbindung` varchar(17)
,`parlamentarier_id` int(11)
,`parlamentarier_name` varchar(152)
,`ratstyp` varchar(10)
,`kanton` varchar(2)
,`partei_id` int(11)
,`partei` varchar(20)
,`kommissionen` varchar(75)
,`parlament_biografie_id` int(11)
,`zutrittsberechtigung_id` int(11)
,`zutrittsberechtigter` varchar(152)
,`art` varchar(18)
,`von` date
,`bis` date
,`zwischenorganisation_id` int(11)
,`connector_organisation_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier_indirekt`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier_indirekt`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier_indirekt` (
`beziehung` varchar(8)
,`parlamentarier_name` varchar(152)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`funktion_im_gremium` varchar(14)
,`deklarationstyp` varchar(25)
,`status` varchar(16)
,`behoerden_vertreter` varchar(1)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`connector_organisation_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier`
--
DROP VIEW IF EXISTS `v_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier` (
`anzeige_name` varchar(152)
,`name` varchar(202)
,`rat` varchar(10)
,`ratstyp` varchar(10)
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`vertretene_bevoelkerung` bigint(13) unsigned
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`rat_id` int(11)
,`kanton_id` int(11)
,`kommissionen` varchar(75)
,`partei_id` int(11)
,`parteifunktion` enum('mitglied','praesident','vizepraesident')
,`fraktion_id` int(11)
,`fraktionsfunktion` enum('mitglied','praesident','vizepraesident')
,`im_rat_seit` date
,`im_rat_bis` date
,`ratswechsel` date
,`ratsunterbruch_von` date
,`ratsunterbruch_bis` date
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`zivilstand` enum('ledig','verheiratet','geschieden','eingetragene partnerschaft')
,`anzahl_kinder` tinyint(3) unsigned
,`militaerischer_grad_id` int(11)
,`geschlecht` enum('M','F')
,`geburtstag` date
,`photo` varchar(255)
,`photo_dateiname` varchar(255)
,`photo_dateierweiterung` varchar(15)
,`photo_dateiname_voll` varchar(255)
,`photo_mime_type` varchar(100)
,`kleinbild` varchar(80)
,`sitzplatz` int(11)
,`email` varchar(100)
,`homepage` varchar(255)
,`parlament_biografie_id` int(11)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`arbeitssprache` enum('de','fr','it')
,`adresse_firma` varchar(100)
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`adresse_ort` varchar(100)
,`ALT_kommission` varchar(255)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisierung_verschickt_visa` varchar(10)
,`autorisierung_verschickt_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`kommissionen_namen` text
,`kommissionen2` double(17,0)
,`kommissionen_abkuerzung` text
,`partei` varchar(20)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_anhang`
--
DROP VIEW IF EXISTS `v_parlamentarier_anhang`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_anhang` (
`parlamentarier_id2` int(11)
,`id` int(11)
,`parlamentarier_id` int(11)
,`datei` varchar(255)
,`dateiname` varchar(255)
,`dateierweiterung` varchar(15)
,`dateiname_voll` varchar(255)
,`mime_type` varchar(100)
,`encoding` varchar(50)
,`beschreibung` varchar(150)
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_authorisierungs_email`
--
DROP VIEW IF EXISTS `v_parlamentarier_authorisierungs_email`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_authorisierungs_email` (
`id` int(11)
,`parlamentarier_name` varchar(152)
,`email` varchar(100)
,`email_text_html` text
,`email_text_for_url` varchar(4096)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_partei`
--
DROP VIEW IF EXISTS `v_partei`;
CREATE TABLE IF NOT EXISTS `v_partei` (
`anzeige_name` varchar(123)
,`id` int(11)
,`abkuerzung` varchar(20)
,`name` varchar(100)
,`fraktion_id` int(11)
,`gruendung` date
,`position` enum('links','rechts','mitte')
,`farbcode` varchar(15)
,`homepage` varchar(255)
,`email` varchar(100)
,`twitter_name` varchar(50)
,`beschreibung` text
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_rat`
--
DROP VIEW IF EXISTS `v_rat`;
CREATE TABLE IF NOT EXISTS `v_rat` (
`anzeige_name` varchar(50)
,`id` int(11)
,`abkuerzung` varchar(10)
,`name_de` varchar(50)
,`name_fr` varchar(50)
,`name_it` varchar(50)
,`name_en` varchar(50)
,`anzahl_mitglieder` smallint(6)
,`typ` enum('legislativ','exekutiv','judikativ')
,`interessenraum_id` int(11)
,`anzeigestufe` int(11)
,`gewicht` int(11)
,`beschreibung` text
,`homepage_de` varchar(255)
,`homepage_fr` varchar(255)
,`homepage_it` varchar(255)
,`homepage_en` varchar(255)
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_settings`
--
DROP VIEW IF EXISTS `v_settings`;
CREATE TABLE IF NOT EXISTS `v_settings` (
`id` int(11)
,`key_name` varchar(100)
,`value` varchar(5000)
,`description` text
,`category_id` int(11)
,`notizen` text
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`category_name` varchar(50)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_settings_category`
--
DROP VIEW IF EXISTS `v_settings_category`;
CREATE TABLE IF NOT EXISTS `v_settings_category` (
`id` int(11)
,`name` varchar(50)
,`description` text
,`notizen` text
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_user`
--
DROP VIEW IF EXISTS `v_user`;
CREATE TABLE IF NOT EXISTS `v_user` (
`anzeige_name` varchar(151)
,`id` int(11)
,`name` varchar(10)
,`password` varchar(255)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`email` varchar(100)
,`last_login` timestamp
,`notizen` text
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_user_permission`
--
DROP VIEW IF EXISTS `v_user_permission`;
CREATE TABLE IF NOT EXISTS `v_user_permission` (
`id` int(11)
,`user_id` int(11)
,`page_name` varchar(500)
,`permission_name` varchar(6)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung` (
`anzeige_name` varchar(152)
,`name` varchar(151)
,`id` int(11)
,`parlamentarier_id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`funktion` varchar(150)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`partei_id` int(11)
,`geschlecht` enum('M','F')
,`email` varchar(100)
,`homepage` varchar(255)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisierung_verschickt_visa` varchar(10)
,`autorisierung_verschickt_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`ALT_lobbyorganisation_id` int(11)
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`partei` varchar(20)
,`parlamentarier_name` varchar(152)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_anhang`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_anhang`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_anhang` (
`zutrittsberechtigung_id2` int(11)
,`id` int(11)
,`zutrittsberechtigung_id` int(11)
,`datei` varchar(255)
,`dateiname` varchar(255)
,`dateierweiterung` varchar(15)
,`dateiname_voll` varchar(255)
,`mime_type` varchar(100)
,`encoding` varchar(50)
,`beschreibung` varchar(150)
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_authorisierungs_email`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_authorisierungs_email`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_authorisierungs_email` (
`parlamentarier_name` varchar(202)
,`geschlecht` varchar(1)
,`zutrittsberechtigung_name` varchar(151)
,`funktion` varchar(150)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_mandate`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mandate`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_mandate` (
`parlamentarier_id` int(11)
,`organisation_name` varchar(454)
,`zutrittsberechtigung_name` varchar(152)
,`funktion` varchar(150)
,`id` int(11)
,`zutrittsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','patronatskomitee','finanziell')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_mit_mandaten`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_mit_mandaten` (
`organisation_name` varchar(454)
,`zutrittsberechtigung_name` varchar(152)
,`funktion` varchar(150)
,`parlamentarier_id` int(11)
,`id` int(11)
,`zutrittsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','patronatskomitee','finanziell')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_mit_mandaten_indirekt`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt` (
`beziehung` varchar(8)
,`organisation_name` varchar(454)
,`zutrittsberechtigung_name` varchar(152)
,`funktion` varchar(150)
,`parlamentarier_id` int(11)
,`id` int(11)
,`zutrittsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`funktion_im_gremium` varchar(14)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`von` date
,`bis` date
,`notizen` text
,`eingabe_abgeschlossen_visa` varchar(10)
,`eingabe_abgeschlossen_datum` timestamp
,`kontrolliert_visa` varchar(10)
,`kontrolliert_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`freigabe_visa` varchar(10)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zu Parlamentarier',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Parteimitgliedschaft der zutrittsberechtigten Person.',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der zutrittsberechtigen Person',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der zutrittsberechtigen Person',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zugangsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zugangsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
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
  `ALT_lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Wird später entfernt. Fremschlüssel zur Lobbyorganisation',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `zutrittsberechtigung_nachname_vorname_unique` (`nachname`,`vorname`,`parlamentarier_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
  KEY `idx_lobbyorg` (`ALT_lobbyorganisation_id`),
  KEY `partei` (`partei_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")' AUTO_INCREMENT=423 ;

--
-- RELATIONEN DER TABELLE `zutrittsberechtigung`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--   `partei_id`
--       `partei` -> `id`
--

--
-- Trigger `zutrittsberechtigung`
--
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_log_del_after` AFTER DELETE ON `zutrittsberechtigung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `zutrittsberechtigung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_log_del_before` BEFORE DELETE ON `zutrittsberechtigung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_log_ins` AFTER INSERT ON `zutrittsberechtigung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_log_upd` AFTER UPDATE ON `zutrittsberechtigung`
 FOR EACH ROW thisTrigger: begin

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate authorization from zutrittsberechtigung to his mandate
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `mandat`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = NEW.autorisiert_visa,
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        zutrittsberechtigung_id=NEW.id AND bis IS NULL;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung_anhang`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `zutrittsberechtigung_anhang`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_anhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Zutrittsberechtigunganhangs',
  `zutrittsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Zutrittsberechtigung',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten' AUTO_INCREMENT=22 ;

--
-- RELATIONEN DER TABELLE `zutrittsberechtigung_anhang`:
--   `zutrittsberechtigung_id`
--       `zutrittsberechtigung` -> `id`
--

--
-- Trigger `zutrittsberechtigung_anhang`
--
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_anhang_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_anhang_log_del_after` AFTER DELETE ON `zutrittsberechtigung_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `zutrittsberechtigung_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_anhang_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_anhang_log_del_before` BEFORE DELETE ON `zutrittsberechtigung_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `zutrittsberechtigung_anhang` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_anhang_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_anhang_log_ins` AFTER INSERT ON `zutrittsberechtigung_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `zutrittsberechtigung_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_anhang_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_anhang_log_upd` AFTER UPDATE ON `zutrittsberechtigung_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `zutrittsberechtigung_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung_anhang_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `zutrittsberechtigung_anhang_log`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_anhang_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `zutrittsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Zutrittsberechtigung',
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
  KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`),
  KEY `fk_zutrittsberechtigung_anhang_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten' AUTO_INCREMENT=24 ;

--
-- RELATIONEN DER TABELLE `zutrittsberechtigung_anhang_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung_log`
--
-- Erzeugt am: 25. Mai 2014 um 18:55
--

DROP TABLE IF EXISTS `zutrittsberechtigung_log`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zu Parlamentarier',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Parteimitgliedschaft der zutrittsberechtigten Person.',
  `geschlecht` enum('M','F') DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau',
  `email` varchar(100) DEFAULT NULL COMMENT 'Kontakt E-Mail-Adresse der zutrittsberechtigen Person',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Homepage der zutrittsberechtigen Person',
  `twitter_name` varchar(50) DEFAULT NULL COMMENT 'Twittername',
  `linkedin_profil_url` varchar(255) DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil',
  `xing_profil_name` varchar(150) DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link',
  `facebook_name` varchar(150) DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zugangsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zugangsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
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
  `ALT_lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Wird später entfernt. Fremschlüssel zur Lobbyorganisation',
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
  KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
  KEY `idx_lobbyorg` (`ALT_lobbyorganisation_id`),
  KEY `partei` (`partei_id`),
  KEY `fk_zutrittsberechtigung_log_snapshot_id` (`snapshot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")' AUTO_INCREMENT=665 ;

--
-- RELATIONEN DER TABELLE `zutrittsberechtigung_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Struktur des Views `v_branche`
--
DROP TABLE IF EXISTS `v_branche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branche` AS select concat(`branche`.`name`) AS `anzeige_name`,`branche`.`id` AS `id`,`branche`.`name` AS `name`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`beschreibung` AS `beschreibung`,`branche`.`angaben` AS `angaben`,`branche`.`farbcode` AS `farbcode`,`branche`.`symbol_abs` AS `symbol_abs`,`branche`.`symbol_rel` AS `symbol_rel`,`branche`.`symbol_klein_rel` AS `symbol_klein_rel`,`branche`.`symbol_dateiname_wo_ext` AS `symbol_dateiname_wo_ext`,`branche`.`symbol_dateierweiterung` AS `symbol_dateierweiterung`,`branche`.`symbol_dateiname` AS `symbol_dateiname`,`branche`.`symbol_mime_type` AS `symbol_mime_type`,`branche`.`notizen` AS `notizen`,`branche`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`branche`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`branche`.`kontrolliert_visa` AS `kontrolliert_visa`,`branche`.`kontrolliert_datum` AS `kontrolliert_datum`,`branche`.`freigabe_visa` AS `freigabe_visa`,`branche`.`freigabe_datum` AS `freigabe_datum`,`branche`.`created_visa` AS `created_visa`,`branche`.`created_date` AS `created_date`,`branche`.`updated_visa` AS `updated_visa`,`branche`.`updated_date` AS `updated_date`,`kommission`.`anzeige_name` AS `kommission` from (`branche` left join `v_kommission` `kommission` on((`kommission`.`id` = `branche`.`kommission_id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_country`
--
DROP TABLE IF EXISTS `v_country`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_country` AS select `country`.`name_de` AS `anzeige_name`,`country`.`id` AS `id`,`country`.`continent` AS `continent`,`country`.`name_en` AS `name_en`,`country`.`official_name_en` AS `official_name_en`,`country`.`capital_en` AS `capital_en`,`country`.`name_de` AS `name_de`,`country`.`official_name_de` AS `official_name_de`,`country`.`capital_de` AS `capital_de`,`country`.`name_fr` AS `name_fr`,`country`.`official_name_fr` AS `official_name_fr`,`country`.`capital_fr` AS `capital_fr`,`country`.`name_it` AS `name_it`,`country`.`official_name_it` AS `official_name_it`,`country`.`capital_it` AS `capital_it`,`country`.`iso-2` AS `iso-2`,`country`.`iso-3` AS `iso-3`,`country`.`vehicle_code` AS `vehicle_code`,`country`.`ioc` AS `ioc`,`country`.`tld` AS `tld`,`country`.`currency` AS `currency`,`country`.`phone` AS `phone`,`country`.`utc` AS `utc`,`country`.`show_level` AS `show_level`,`country`.`created_visa` AS `created_visa`,`country`.`created_date` AS `created_date`,`country`.`updated_visa` AS `updated_visa`,`country`.`updated_date` AS `updated_date` from `country`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_fraktion`
--
DROP TABLE IF EXISTS `v_fraktion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_fraktion` AS select concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name`) AS `anzeige_name`,`fraktion`.`id` AS `id`,`fraktion`.`abkuerzung` AS `abkuerzung`,`fraktion`.`name` AS `name`,`fraktion`.`position` AS `position`,`fraktion`.`farbcode` AS `farbcode`,`fraktion`.`beschreibung` AS `beschreibung`,`fraktion`.`von` AS `von`,`fraktion`.`bis` AS `bis`,`fraktion`.`notizen` AS `notizen`,`fraktion`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`fraktion`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`fraktion`.`kontrolliert_visa` AS `kontrolliert_visa`,`fraktion`.`kontrolliert_datum` AS `kontrolliert_datum`,`fraktion`.`freigabe_visa` AS `freigabe_visa`,`fraktion`.`freigabe_datum` AS `freigabe_datum`,`fraktion`.`created_visa` AS `created_visa`,`fraktion`.`created_date` AS `created_date`,`fraktion`.`updated_visa` AS `updated_visa`,`fraktion`.`updated_date` AS `updated_date` from `fraktion`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung`
--
DROP TABLE IF EXISTS `v_interessenbindung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung` AS select `interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from `interessenbindung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_authorisierungs_email`
--
DROP TABLE IF EXISTS `v_interessenbindung_authorisierungs_email`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_authorisierungs_email` AS select `parlamentarier`.`name` AS `parlamentarier_name`,ifnull(`parlamentarier`.`geschlecht`,'') AS `geschlecht`,`organisation`.`anzeige_name` AS `organisation_name`,ifnull(`organisation`.`rechtsform`,'') AS `rechtsform`,ifnull(`organisation`.`ort`,'') AS `ort`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`beschreibung` AS `beschreibung` from ((`v_interessenbindung` `interessenbindung` join `v_organisation` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) group by `parlamentarier`.`id` order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_liste`
--
DROP TABLE IF EXISTS `v_interessenbindung_liste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_liste` AS select `organisation`.`anzeige_name` AS `organisation_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from (`v_interessenbindung` `interessenbindung` join `v_organisation` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_liste_indirekt`
--
DROP TABLE IF EXISTS `v_interessenbindung_liste_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_liste_indirekt` AS select 'direkt' AS `beziehung`,`interessenbindung_liste`.`organisation_name` AS `organisation_name`,`interessenbindung_liste`.`id` AS `id`,`interessenbindung_liste`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung_liste`.`organisation_id` AS `organisation_id`,`interessenbindung_liste`.`art` AS `art`,`interessenbindung_liste`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung_liste`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung_liste`.`status` AS `status`,`interessenbindung_liste`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung_liste`.`verguetung` AS `verguetung`,`interessenbindung_liste`.`beschreibung` AS `beschreibung`,`interessenbindung_liste`.`von` AS `von`,`interessenbindung_liste`.`bis` AS `bis`,`interessenbindung_liste`.`notizen` AS `notizen`,`interessenbindung_liste`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung_liste`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung_liste`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung_liste`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung_liste`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung_liste`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung_liste`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung_liste`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung_liste`.`created_visa` AS `created_visa`,`interessenbindung_liste`.`created_date` AS `created_date`,`interessenbindung_liste`.`updated_visa` AS `updated_visa`,`interessenbindung_liste`.`updated_date` AS `updated_date` from `v_interessenbindung_liste` `interessenbindung_liste` union select 'indirekt' AS `beziehung`,`organisation`.`anzeige_name` AS `organisation_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from ((`v_interessenbindung` `interessenbindung` join `v_organisation_beziehung` `organisation_beziehung` on((`interessenbindung`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`organisation_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessengruppe`
--
DROP TABLE IF EXISTS `v_interessengruppe`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessengruppe` AS select concat(`interessengruppe`.`name`) AS `anzeige_name`,`interessengruppe`.`id` AS `id`,`interessengruppe`.`name` AS `name`,`interessengruppe`.`branche_id` AS `branche_id`,`interessengruppe`.`beschreibung` AS `beschreibung`,`interessengruppe`.`notizen` AS `notizen`,`interessengruppe`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessengruppe`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessengruppe`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessengruppe`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessengruppe`.`freigabe_visa` AS `freigabe_visa`,`interessengruppe`.`freigabe_datum` AS `freigabe_datum`,`interessengruppe`.`created_visa` AS `created_visa`,`interessengruppe`.`created_date` AS `created_date`,`interessengruppe`.`updated_visa` AS `updated_visa`,`interessengruppe`.`updated_date` AS `updated_date`,`branche`.`anzeige_name` AS `branche`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`kommission` AS `kommission` from (`interessengruppe` left join `v_branche` `branche` on((`branche`.`id` = `interessengruppe`.`branche_id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenraum`
--
DROP TABLE IF EXISTS `v_interessenraum`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenraum` AS select `interessenraum`.`name` AS `anzeige_name`,`interessenraum`.`id` AS `id`,`interessenraum`.`name` AS `name`,`interessenraum`.`beschreibung` AS `beschreibung`,`interessenraum`.`reihenfolge` AS `reihenfolge`,`interessenraum`.`notizen` AS `notizen`,`interessenraum`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenraum`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenraum`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenraum`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenraum`.`freigabe_visa` AS `freigabe_visa`,`interessenraum`.`freigabe_datum` AS `freigabe_datum`,`interessenraum`.`created_visa` AS `created_visa`,`interessenraum`.`created_date` AS `created_date`,`interessenraum`.`updated_visa` AS `updated_visa`,`interessenraum`.`updated_date` AS `updated_date` from `interessenraum` order by `interessenraum`.`reihenfolge`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission`
--
DROP TABLE IF EXISTS `v_in_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission` AS select `in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`rat`.`abkuerzung` AS `rat`,`rat`.`abkuerzung` AS `ratstyp`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`kanton`.`abkuerzung` AS `kanton` from (((`in_kommission` join `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) left join `kanton` on((`parlamentarier`.`kanton_id` = `kanton`.`id`))) left join `rat` on((`parlamentarier`.`rat_id` = `rat`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_liste`
--
DROP TABLE IF EXISTS `v_in_kommission_liste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_liste` AS select `kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`name` AS `name`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`rat` AS `rat`,`in_kommission`.`ratstyp` AS `ratstyp`,`in_kommission`.`partei_id` AS `partei_id`,`in_kommission`.`fraktion_id` AS `fraktion_id`,`in_kommission`.`kanton` AS `kanton` from (`v_in_kommission` `in_kommission` join `v_kommission` `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) order by `kommission`.`abkuerzung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_parlamentarier`
--
DROP TABLE IF EXISTS `v_in_kommission_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_parlamentarier` AS select `parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`partei` AS `partei`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`rat` AS `rat`,`in_kommission`.`ratstyp` AS `ratstyp`,`in_kommission`.`partei_id` AS `partei_id`,`in_kommission`.`fraktion_id` AS `fraktion_id`,`in_kommission`.`kanton` AS `kanton` from (`v_in_kommission` `in_kommission` join `v_parlamentarier` `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton`
--
DROP TABLE IF EXISTS `v_kanton`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton` AS select `kanton`.`name_de` AS `anzeige_name`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date`,`kanton_jahr`.`id` AS `kanton_jahr_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete` from (`kanton` left join `v_kanton_jahr_last` `kanton_jahr` on((`kanton_jahr`.`kanton_id` = `kanton`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_2012`
--
DROP TABLE IF EXISTS `v_kanton_2012`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_2012` AS select `kanton`.`name_de` AS `anzeige_name`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date`,`kanton_jahr`.`id` AS `kanton_jahr_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete` from (`kanton` left join `v_kanton_jahr` `kanton_jahr` on(((`kanton_jahr`.`kanton_id` = `kanton`.`id`) and (`kanton_jahr`.`jahr` = 2012))));

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_jahr`
--
DROP TABLE IF EXISTS `v_kanton_jahr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_jahr` AS select `kanton_jahr`.`id` AS `id`,`kanton_jahr`.`kanton_id` AS `kanton_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`steuereinnahmen` AS `steuereinnahmen`,`kanton_jahr`.`ausgaben` AS `ausgaben`,`kanton_jahr`.`finanzausgleich` AS `finanzausgleich`,`kanton_jahr`.`schulden` AS `schulden`,`kanton_jahr`.`notizen` AS `notizen`,`kanton_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton_jahr`.`freigabe_visa` AS `freigabe_visa`,`kanton_jahr`.`freigabe_datum` AS `freigabe_datum`,`kanton_jahr`.`created_visa` AS `created_visa`,`kanton_jahr`.`created_date` AS `created_date`,`kanton_jahr`.`updated_visa` AS `updated_visa`,`kanton_jahr`.`updated_date` AS `updated_date` from `kanton_jahr`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_jahr_last`
--
DROP TABLE IF EXISTS `v_kanton_jahr_last`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_jahr_last` AS select max(`kanton_jahr`.`jahr`) AS `max_jahr`,`kanton_jahr`.`id` AS `id`,`kanton_jahr`.`kanton_id` AS `kanton_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`steuereinnahmen` AS `steuereinnahmen`,`kanton_jahr`.`ausgaben` AS `ausgaben`,`kanton_jahr`.`finanzausgleich` AS `finanzausgleich`,`kanton_jahr`.`schulden` AS `schulden`,`kanton_jahr`.`notizen` AS `notizen`,`kanton_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton_jahr`.`freigabe_visa` AS `freigabe_visa`,`kanton_jahr`.`freigabe_datum` AS `freigabe_datum`,`kanton_jahr`.`created_visa` AS `created_visa`,`kanton_jahr`.`created_date` AS `created_date`,`kanton_jahr`.`updated_visa` AS `updated_visa`,`kanton_jahr`.`updated_date` AS `updated_date` from `kanton_jahr` group by `kanton_jahr`.`kanton_id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kommission`
--
DROP TABLE IF EXISTS `v_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kommission` AS select concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') AS `anzeige_name`,`kommission`.`id` AS `id`,`kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`name` AS `name`,`kommission`.`typ` AS `typ`,`kommission`.`art` AS `art`,`kommission`.`beschreibung` AS `beschreibung`,`kommission`.`sachbereiche` AS `sachbereiche`,`kommission`.`mutter_kommission_id` AS `mutter_kommission_id`,`kommission`.`parlament_url` AS `parlament_url`,`kommission`.`notizen` AS `notizen`,`kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`kommission`.`freigabe_visa` AS `freigabe_visa`,`kommission`.`freigabe_datum` AS `freigabe_datum`,`kommission`.`created_visa` AS `created_visa`,`kommission`.`created_date` AS `created_date`,`kommission`.`updated_visa` AS `updated_visa`,`kommission`.`updated_date` AS `updated_date` from `kommission`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_branche`
--
DROP TABLE IF EXISTS `v_last_updated_branche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_branche` AS (select 'branche' AS `table_name`,'Branche' AS `name`,(select count(0) from `branche`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `branche` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_fraktion`
--
DROP TABLE IF EXISTS `v_last_updated_fraktion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_fraktion` AS (select 'fraktion' AS `table_name`,'Fraktion' AS `name`,(select count(0) from `fraktion`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `fraktion` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_interessenbindung`
--
DROP TABLE IF EXISTS `v_last_updated_interessenbindung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_interessenbindung` AS (select 'interessenbindung' AS `table_name`,'Interessenbindung' AS `name`,(select count(0) from `interessenbindung`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `interessenbindung` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_interessengruppe`
--
DROP TABLE IF EXISTS `v_last_updated_interessengruppe`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_interessengruppe` AS (select 'interessengruppe' AS `table_name`,'Lobbygruppe' AS `name`,(select count(0) from `interessengruppe`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `interessengruppe` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_in_kommission`
--
DROP TABLE IF EXISTS `v_last_updated_in_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_in_kommission` AS (select 'in_kommission' AS `table_name`,'In Kommission' AS `name`,(select count(0) from `in_kommission`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `in_kommission` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_kanton`
--
DROP TABLE IF EXISTS `v_last_updated_kanton`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_kanton` AS (select 'kanton' AS `table_name`,'Kanton' AS `name`,(select count(0) from `kanton`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `kanton` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_kanton_jahr`
--
DROP TABLE IF EXISTS `v_last_updated_kanton_jahr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_kanton_jahr` AS (select 'kanton_jahr' AS `table_name`,'Kantonjahr' AS `name`,(select count(0) from `kanton_jahr`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `kanton_jahr` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_kommission`
--
DROP TABLE IF EXISTS `v_last_updated_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_kommission` AS (select 'kommission' AS `table_name`,'Kommission' AS `name`,(select count(0) from `kommission`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `kommission` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_mandat`
--
DROP TABLE IF EXISTS `v_last_updated_mandat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_mandat` AS (select 'mandat' AS `table_name`,'Mandat' AS `name`,(select count(0) from `mandat`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `mandat` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_organisation`
--
DROP TABLE IF EXISTS `v_last_updated_organisation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_organisation` AS (select 'organisation' AS `table_name`,'Organisation' AS `name`,(select count(0) from `organisation`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_organisation_anhang`
--
DROP TABLE IF EXISTS `v_last_updated_organisation_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_organisation_anhang` AS (select 'organisation_anhang' AS `table_name`,'Organisationsanhang' AS `name`,(select count(0) from `organisation_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation_anhang` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_organisation_beziehung`
--
DROP TABLE IF EXISTS `v_last_updated_organisation_beziehung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_organisation_beziehung` AS (select 'organisation_beziehung' AS `table_name`,'Organisation Beziehung' AS `name`,(select count(0) from `organisation_beziehung`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation_beziehung` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_organisation_jahr`
--
DROP TABLE IF EXISTS `v_last_updated_organisation_jahr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_organisation_jahr` AS (select 'organisation_jahr' AS `table_name`,'Organisationsjahr' AS `name`,(select count(0) from `organisation_jahr`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `organisation_jahr` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_parlamentarier`
--
DROP TABLE IF EXISTS `v_last_updated_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_parlamentarier` AS (select 'parlamentarier' AS `table_name`,'Parlamentarier' AS `name`,(select count(0) from `parlamentarier`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `parlamentarier` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_parlamentarier_anhang`
--
DROP TABLE IF EXISTS `v_last_updated_parlamentarier_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_parlamentarier_anhang` AS (select 'parlamentarier_anhang' AS `table_name`,'Parlamentarieranhang' AS `name`,(select count(0) from `parlamentarier_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `parlamentarier_anhang` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_partei`
--
DROP TABLE IF EXISTS `v_last_updated_partei`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_partei` AS (select 'partei' AS `table_name`,'Partei' AS `name`,(select count(0) from `partei`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `partei` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_rat`
--
DROP TABLE IF EXISTS `v_last_updated_rat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_rat` AS (select 'rat' AS `table_name`,'Rat' AS `name`,(select count(0) from `rat`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `rat` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_settings`
--
DROP TABLE IF EXISTS `v_last_updated_settings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_settings` AS (select 'settings' AS `table_name`,'Einstellungen' AS `name`,(select count(0) from `settings`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `settings` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_settings_category`
--
DROP TABLE IF EXISTS `v_last_updated_settings_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_settings_category` AS (select 'settings_category' AS `table_name`,'Einstellungskategorien' AS `name`,(select count(0) from `settings_category`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `settings_category` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_tables`
--
DROP TABLE IF EXISTS `v_last_updated_tables`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_tables` AS select `v_last_updated_tables_unordered`.`table_name` AS `table_name`,`v_last_updated_tables_unordered`.`name` AS `name`,`v_last_updated_tables_unordered`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_tables_unordered`.`last_visa` AS `last_visa`,`v_last_updated_tables_unordered`.`last_updated` AS `last_updated`,`v_last_updated_tables_unordered`.`last_updated_id` AS `last_updated_id` from `v_last_updated_tables_unordered` order by `v_last_updated_tables_unordered`.`last_updated` desc;

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_tables_unordered`
--
DROP TABLE IF EXISTS `v_last_updated_tables_unordered`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_tables_unordered` AS select `v_last_updated_branche`.`table_name` AS `table_name`,`v_last_updated_branche`.`name` AS `name`,`v_last_updated_branche`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_branche`.`last_visa` AS `last_visa`,`v_last_updated_branche`.`last_updated` AS `last_updated`,`v_last_updated_branche`.`last_updated_id` AS `last_updated_id` from `v_last_updated_branche` union select `v_last_updated_interessenbindung`.`table_name` AS `table_name`,`v_last_updated_interessenbindung`.`name` AS `name`,`v_last_updated_interessenbindung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessenbindung`.`last_visa` AS `last_visa`,`v_last_updated_interessenbindung`.`last_updated` AS `last_updated`,`v_last_updated_interessenbindung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessenbindung` union select `v_last_updated_interessengruppe`.`table_name` AS `table_name`,`v_last_updated_interessengruppe`.`name` AS `name`,`v_last_updated_interessengruppe`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessengruppe`.`last_visa` AS `last_visa`,`v_last_updated_interessengruppe`.`last_updated` AS `last_updated`,`v_last_updated_interessengruppe`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessengruppe` union select `v_last_updated_in_kommission`.`table_name` AS `table_name`,`v_last_updated_in_kommission`.`name` AS `name`,`v_last_updated_in_kommission`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_in_kommission`.`last_visa` AS `last_visa`,`v_last_updated_in_kommission`.`last_updated` AS `last_updated`,`v_last_updated_in_kommission`.`last_updated_id` AS `last_updated_id` from `v_last_updated_in_kommission` union select `v_last_updated_kommission`.`table_name` AS `table_name`,`v_last_updated_kommission`.`name` AS `name`,`v_last_updated_kommission`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kommission`.`last_visa` AS `last_visa`,`v_last_updated_kommission`.`last_updated` AS `last_updated`,`v_last_updated_kommission`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kommission` union select `v_last_updated_mandat`.`table_name` AS `table_name`,`v_last_updated_mandat`.`name` AS `name`,`v_last_updated_mandat`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_mandat`.`last_visa` AS `last_visa`,`v_last_updated_mandat`.`last_updated` AS `last_updated`,`v_last_updated_mandat`.`last_updated_id` AS `last_updated_id` from `v_last_updated_mandat` union select `v_last_updated_organisation`.`table_name` AS `table_name`,`v_last_updated_organisation`.`name` AS `name`,`v_last_updated_organisation`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation`.`last_visa` AS `last_visa`,`v_last_updated_organisation`.`last_updated` AS `last_updated`,`v_last_updated_organisation`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation` union select `v_last_updated_organisation_anhang`.`table_name` AS `table_name`,`v_last_updated_organisation_anhang`.`name` AS `name`,`v_last_updated_organisation_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_anhang`.`last_visa` AS `last_visa`,`v_last_updated_organisation_anhang`.`last_updated` AS `last_updated`,`v_last_updated_organisation_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_anhang` union select `v_last_updated_organisation_beziehung`.`table_name` AS `table_name`,`v_last_updated_organisation_beziehung`.`name` AS `name`,`v_last_updated_organisation_beziehung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_beziehung`.`last_visa` AS `last_visa`,`v_last_updated_organisation_beziehung`.`last_updated` AS `last_updated`,`v_last_updated_organisation_beziehung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_beziehung` union select `v_last_updated_organisation_jahr`.`table_name` AS `table_name`,`v_last_updated_organisation_jahr`.`name` AS `name`,`v_last_updated_organisation_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_jahr`.`last_visa` AS `last_visa`,`v_last_updated_organisation_jahr`.`last_updated` AS `last_updated`,`v_last_updated_organisation_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_jahr` union select `v_last_updated_parlamentarier`.`table_name` AS `table_name`,`v_last_updated_parlamentarier`.`name` AS `name`,`v_last_updated_parlamentarier`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_parlamentarier`.`last_visa` AS `last_visa`,`v_last_updated_parlamentarier`.`last_updated` AS `last_updated`,`v_last_updated_parlamentarier`.`last_updated_id` AS `last_updated_id` from `v_last_updated_parlamentarier` union select `v_last_updated_parlamentarier_anhang`.`table_name` AS `table_name`,`v_last_updated_parlamentarier_anhang`.`name` AS `name`,`v_last_updated_parlamentarier_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_parlamentarier_anhang`.`last_visa` AS `last_visa`,`v_last_updated_parlamentarier_anhang`.`last_updated` AS `last_updated`,`v_last_updated_parlamentarier_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_parlamentarier_anhang` union select `v_last_updated_partei`.`table_name` AS `table_name`,`v_last_updated_partei`.`name` AS `name`,`v_last_updated_partei`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_partei`.`last_visa` AS `last_visa`,`v_last_updated_partei`.`last_updated` AS `last_updated`,`v_last_updated_partei`.`last_updated_id` AS `last_updated_id` from `v_last_updated_partei` union select `v_last_updated_fraktion`.`table_name` AS `table_name`,`v_last_updated_fraktion`.`name` AS `name`,`v_last_updated_fraktion`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_fraktion`.`last_visa` AS `last_visa`,`v_last_updated_fraktion`.`last_updated` AS `last_updated`,`v_last_updated_fraktion`.`last_updated_id` AS `last_updated_id` from `v_last_updated_fraktion` union select `v_last_updated_rat`.`table_name` AS `table_name`,`v_last_updated_rat`.`name` AS `name`,`v_last_updated_rat`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_rat`.`last_visa` AS `last_visa`,`v_last_updated_rat`.`last_updated` AS `last_updated`,`v_last_updated_rat`.`last_updated_id` AS `last_updated_id` from `v_last_updated_rat` union select `v_last_updated_kanton`.`table_name` AS `table_name`,`v_last_updated_kanton`.`name` AS `name`,`v_last_updated_kanton`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kanton`.`last_visa` AS `last_visa`,`v_last_updated_kanton`.`last_updated` AS `last_updated`,`v_last_updated_kanton`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kanton` union select `v_last_updated_kanton_jahr`.`table_name` AS `table_name`,`v_last_updated_kanton_jahr`.`name` AS `name`,`v_last_updated_kanton_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kanton_jahr`.`last_visa` AS `last_visa`,`v_last_updated_kanton_jahr`.`last_updated` AS `last_updated`,`v_last_updated_kanton_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kanton_jahr` union select `v_last_updated_settings`.`table_name` AS `table_name`,`v_last_updated_settings`.`name` AS `name`,`v_last_updated_settings`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_settings`.`last_visa` AS `last_visa`,`v_last_updated_settings`.`last_updated` AS `last_updated`,`v_last_updated_settings`.`last_updated_id` AS `last_updated_id` from `v_last_updated_settings` union select `v_last_updated_settings_category`.`table_name` AS `table_name`,`v_last_updated_settings_category`.`name` AS `name`,`v_last_updated_settings_category`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_settings_category`.`last_visa` AS `last_visa`,`v_last_updated_settings_category`.`last_updated` AS `last_updated`,`v_last_updated_settings_category`.`last_updated_id` AS `last_updated_id` from `v_last_updated_settings_category` union select `v_last_updated_zutrittsberechtigung`.`table_name` AS `table_name`,`v_last_updated_zutrittsberechtigung`.`name` AS `name`,`v_last_updated_zutrittsberechtigung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_zutrittsberechtigung`.`last_visa` AS `last_visa`,`v_last_updated_zutrittsberechtigung`.`last_updated` AS `last_updated`,`v_last_updated_zutrittsberechtigung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_zutrittsberechtigung` union select `v_last_updated_zutrittsberechtigung_anhang`.`table_name` AS `table_name`,`v_last_updated_zutrittsberechtigung_anhang`.`name` AS `name`,`v_last_updated_zutrittsberechtigung_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_zutrittsberechtigung_anhang`.`last_visa` AS `last_visa`,`v_last_updated_zutrittsberechtigung_anhang`.`last_updated` AS `last_updated`,`v_last_updated_zutrittsberechtigung_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_zutrittsberechtigung_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_zutrittsberechtigung`
--
DROP TABLE IF EXISTS `v_last_updated_zutrittsberechtigung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_zutrittsberechtigung` AS (select 'zutrittsberechtigung' AS `table_name`,'Zutrittsberechtigter' AS `name`,(select count(0) from `zutrittsberechtigung`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `zutrittsberechtigung` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_zutrittsberechtigung_anhang`
--
DROP TABLE IF EXISTS `v_last_updated_zutrittsberechtigung_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_zutrittsberechtigung_anhang` AS (select 'zutrittsberechtigung_anhang' AS `table_name`,'Zutrittsberechtigunganhang' AS `name`,(select count(0) from `zutrittsberechtigung_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `zutrittsberechtigung_anhang` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_mandat`
--
DROP TABLE IF EXISTS `v_mandat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mandat` AS select `mandat`.`id` AS `id`,`mandat`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from `mandat`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_mil_grad`
--
DROP TABLE IF EXISTS `v_mil_grad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mil_grad` AS select `t`.`id` AS `id`,`t`.`name` AS `name`,`t`.`abkuerzung` AS `abkuerzung`,`t`.`typ` AS `typ`,`t`.`ranghoehe` AS `ranghoehe`,`t`.`anzeigestufe` AS `anzeigestufe`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `mil_grad` `t` order by `t`.`ranghoehe`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation`
--
DROP TABLE IF EXISTS `v_organisation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation` AS select concat_ws('; ',`o`.`name_de`,`o`.`name_fr`,`o`.`name_it`) AS `anzeige_name`,concat_ws('; ',`o`.`name_de`,`o`.`name_fr`,`o`.`name_it`) AS `name`,`o`.`id` AS `id`,`o`.`name_de` AS `name_de`,`o`.`name_fr` AS `name_fr`,`o`.`name_it` AS `name_it`,`o`.`ort` AS `ort`,`o`.`land_id` AS `land_id`,`o`.`interessenraum_id` AS `interessenraum_id`,`o`.`rechtsform` AS `rechtsform`,`o`.`typ` AS `typ`,`o`.`vernehmlassung` AS `vernehmlassung`,`o`.`interessengruppe_id` AS `interessengruppe_id`,`o`.`interessengruppe2_id` AS `interessengruppe2_id`,`o`.`interessengruppe3_id` AS `interessengruppe3_id`,`o`.`branche_id` AS `branche_id`,`o`.`homepage` AS `homepage`,`o`.`handelsregister_url` AS `handelsregister_url`,`o`.`twitter_name` AS `twitter_name`,`o`.`beschreibung` AS `beschreibung`,`o`.`adresse_strasse` AS `adresse_strasse`,`o`.`adresse_zusatz` AS `adresse_zusatz`,`o`.`adresse_plz` AS `adresse_plz`,`o`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`o`.`notizen` AS `notizen`,`o`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`o`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`o`.`kontrolliert_visa` AS `kontrolliert_visa`,`o`.`kontrolliert_datum` AS `kontrolliert_datum`,`o`.`freigabe_visa` AS `freigabe_visa`,`o`.`freigabe_datum` AS `freigabe_datum`,`o`.`created_visa` AS `created_visa`,`o`.`created_date` AS `created_date`,`o`.`updated_visa` AS `updated_visa`,`o`.`updated_date` AS `updated_date`,`branche`.`anzeige_name` AS `branche`,`interessengruppe1`.`anzeige_name` AS `interessengruppe`,`interessengruppe1`.`branche` AS `interessengruppe_branche`,`interessengruppe2`.`anzeige_name` AS `interessengruppe2`,`interessengruppe2`.`branche` AS `interessengruppe2_branche`,`interessengruppe3`.`anzeige_name` AS `interessengruppe3`,`interessengruppe3`.`branche` AS `interessengruppe3_branche`,`country`.`name_de` AS `land`,`interessenraum`.`anzeige_name` AS `interessenraum`,`organisation_jahr`.`id` AS `organisation_jahr_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url` from (((((((`organisation` `o` left join `v_branche` `branche` on((`branche`.`id` = `o`.`branche_id`))) left join `v_interessengruppe` `interessengruppe1` on((`interessengruppe1`.`id` = `o`.`interessengruppe_id`))) left join `v_interessengruppe` `interessengruppe2` on((`interessengruppe2`.`id` = `o`.`interessengruppe2_id`))) left join `v_interessengruppe` `interessengruppe3` on((`interessengruppe3`.`id` = `o`.`interessengruppe3_id`))) left join `v_country` `country` on((`country`.`id` = `o`.`land_id`))) left join `v_interessenraum` `interessenraum` on((`interessenraum`.`id` = `o`.`interessenraum_id`))) left join `v_organisation_jahr_last` `organisation_jahr` on((`organisation_jahr`.`organisation_id` = `o`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_anhang`
--
DROP TABLE IF EXISTS `v_organisation_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_anhang` AS select `organisation_anhang`.`organisation_id` AS `organisation_id2`,`organisation_anhang`.`id` AS `id`,`organisation_anhang`.`organisation_id` AS `organisation_id`,`organisation_anhang`.`datei` AS `datei`,`organisation_anhang`.`dateiname` AS `dateiname`,`organisation_anhang`.`dateierweiterung` AS `dateierweiterung`,`organisation_anhang`.`dateiname_voll` AS `dateiname_voll`,`organisation_anhang`.`mime_type` AS `mime_type`,`organisation_anhang`.`encoding` AS `encoding`,`organisation_anhang`.`beschreibung` AS `beschreibung`,`organisation_anhang`.`freigabe_visa` AS `freigabe_visa`,`organisation_anhang`.`freigabe_datum` AS `freigabe_datum`,`organisation_anhang`.`created_visa` AS `created_visa`,`organisation_anhang`.`created_date` AS `created_date`,`organisation_anhang`.`updated_visa` AS `updated_visa`,`organisation_anhang`.`updated_date` AS `updated_date` from `organisation_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung`
--
DROP TABLE IF EXISTS `v_organisation_beziehung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung` AS select `organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_beziehung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_beziehung`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_beziehung`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_beziehung`.`freigabe_visa` AS `freigabe_visa`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date` from `organisation_beziehung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_arbeitet_fuer`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_arbeitet_fuer` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation`.`quelle_url` AS `quelle_url` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_auftraggeber_fuer`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_auftraggeber_fuer` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation`.`quelle_url` AS `quelle_url` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_mitglieder`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_mitglieder`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_mitglieder` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation`.`quelle_url` AS `quelle_url` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_mitglied_von`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_mitglied_von`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_mitglied_von` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation`.`quelle_url` AS `quelle_url` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_muttergesellschaft`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_muttergesellschaft`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_muttergesellschaft` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation`.`quelle_url` AS `quelle_url` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'tochtergesellschaft von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_tochtergesellschaften`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_tochtergesellschaften` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation`.`quelle_url` AS `quelle_url` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'tochtergesellschaft von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_jahr`
--
DROP TABLE IF EXISTS `v_organisation_jahr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_jahr` AS select `organisation_jahr`.`id` AS `id`,`organisation_jahr`.`organisation_id` AS `organisation_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`organisation_jahr`.`notizen` AS `notizen`,`organisation_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_jahr`.`freigabe_visa` AS `freigabe_visa`,`organisation_jahr`.`freigabe_datum` AS `freigabe_datum`,`organisation_jahr`.`created_visa` AS `created_visa`,`organisation_jahr`.`created_date` AS `created_date`,`organisation_jahr`.`updated_visa` AS `updated_visa`,`organisation_jahr`.`updated_date` AS `updated_date` from `organisation_jahr`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_jahr_last`
--
DROP TABLE IF EXISTS `v_organisation_jahr_last`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_jahr_last` AS select max(`organisation_jahr`.`jahr`) AS `max_jahr`,`organisation_jahr`.`id` AS `id`,`organisation_jahr`.`organisation_id` AS `organisation_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`organisation_jahr`.`notizen` AS `notizen`,`organisation_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_jahr`.`freigabe_visa` AS `freigabe_visa`,`organisation_jahr`.`freigabe_datum` AS `freigabe_datum`,`organisation_jahr`.`created_visa` AS `created_visa`,`organisation_jahr`.`created_date` AS `created_date`,`organisation_jahr`.`updated_visa` AS `updated_visa`,`organisation_jahr`.`updated_date` AS `updated_date` from `organisation_jahr` group by `organisation_jahr`.`organisation_id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier` AS select `parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from (`v_interessenbindung` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_beide`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_beide`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_beide` AS select 'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `zutrittsberechtigung_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`organisation_id` AS `organisation_id` from (`v_interessenbindung` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) union select 'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`organisation_id` AS `organisation_id` from ((`v_zutrittsberechtigung` `zutrittsberechtigung` join `v_mandat` `mandat` on((`mandat`.`zutrittsberechtigung_id` = `zutrittsberechtigung`.`id`))) join `v_parlamentarier` `parlamentarier` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_beide_indirekt`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_beide_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_beide_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`verbindung` AS `verbindung`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`parlamentarier_name` AS `parlamentarier_name`,`organisation_parlamentarier`.`ratstyp` AS `ratstyp`,`organisation_parlamentarier`.`kanton` AS `kanton`,`organisation_parlamentarier`.`partei_id` AS `partei_id`,`organisation_parlamentarier`.`partei` AS `partei`,`organisation_parlamentarier`.`kommissionen` AS `kommissionen`,`organisation_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`organisation_parlamentarier`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`organisation_parlamentarier`.`zutrittsberechtigter` AS `zutrittsberechtigter`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`von` AS `von`,`organisation_parlamentarier`.`bis` AS `bis`,NULL AS `zwischenorganisation_id`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id` from `v_organisation_parlamentarier_beide` `organisation_parlamentarier` union select 'indirekt' AS `beziehung`,'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `zutrittsberechtigung_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`organisation_beziehung`.`organisation_id` AS `zwischenorganisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id` from ((`v_organisation_beziehung` `organisation_beziehung` join `v_interessenbindung` `interessenbindung` on((`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') union select 'indirekt' AS `beziehung`,'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`organisation_beziehung`.`organisation_id` AS `zwischenorganisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id` from (((`v_organisation_beziehung` `organisation_beziehung` join `v_mandat` `mandat` on((`organisation_beziehung`.`organisation_id` = `mandat`.`organisation_id`))) join `v_zutrittsberechtigung` `zutrittsberechtigung` on((`mandat`.`zutrittsberechtigung_id` = `zutrittsberechtigung`.`id`))) join `v_parlamentarier` `parlamentarier` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer');

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_indirekt`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`parlamentarier_name` AS `parlamentarier_name`,`organisation_parlamentarier`.`id` AS `id`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`organisation_id` AS `organisation_id`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`funktion_im_gremium` AS `funktion_im_gremium`,`organisation_parlamentarier`.`deklarationstyp` AS `deklarationstyp`,`organisation_parlamentarier`.`status` AS `status`,`organisation_parlamentarier`.`behoerden_vertreter` AS `behoerden_vertreter`,`organisation_parlamentarier`.`verguetung` AS `verguetung`,`organisation_parlamentarier`.`beschreibung` AS `beschreibung`,`organisation_parlamentarier`.`von` AS `von`,`organisation_parlamentarier`.`bis` AS `bis`,`organisation_parlamentarier`.`notizen` AS `notizen`,`organisation_parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`organisation_parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`organisation_parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`organisation_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`organisation_parlamentarier`.`created_visa` AS `created_visa`,`organisation_parlamentarier`.`created_date` AS `created_date`,`organisation_parlamentarier`.`updated_visa` AS `updated_visa`,`organisation_parlamentarier`.`updated_date` AS `updated_date`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id` from `v_organisation_parlamentarier` `organisation_parlamentarier` union select 'indirekt' AS `beziehung`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id` from ((`v_organisation_beziehung` `organisation_beziehung` join `v_interessenbindung` `interessenbindung` on((`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`parlamentarier_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier`
--
DROP TABLE IF EXISTS `v_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier` AS select concat(`p`.`nachname`,', ',`p`.`vorname`) AS `anzeige_name`,concat_ws(' ',`p`.`vorname`,`p`.`zweiter_vorname`,`p`.`nachname`) AS `name`,`rat`.`abkuerzung` AS `rat`,`rat`.`abkuerzung` AS `ratstyp`,`kanton`.`abkuerzung` AS `kanton`,cast((case `rat`.`abkuerzung` when 'SR' then round((`kanton`.`einwohner` / `kanton`.`anzahl_staenderaete`),0) when 'NR' then round((`kanton`.`einwohner` / `kanton`.`anzahl_nationalraete`),0) else NULL end) as unsigned) AS `vertretene_bevoelkerung`,`p`.`id` AS `id`,`p`.`nachname` AS `nachname`,`p`.`vorname` AS `vorname`,`p`.`zweiter_vorname` AS `zweiter_vorname`,`p`.`rat_id` AS `rat_id`,`p`.`kanton_id` AS `kanton_id`,`p`.`kommissionen` AS `kommissionen`,`p`.`partei_id` AS `partei_id`,`p`.`parteifunktion` AS `parteifunktion`,`p`.`fraktion_id` AS `fraktion_id`,`p`.`fraktionsfunktion` AS `fraktionsfunktion`,`p`.`im_rat_seit` AS `im_rat_seit`,`p`.`im_rat_bis` AS `im_rat_bis`,`p`.`ratswechsel` AS `ratswechsel`,`p`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`p`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`p`.`beruf` AS `beruf`,`p`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`p`.`zivilstand` AS `zivilstand`,`p`.`anzahl_kinder` AS `anzahl_kinder`,`p`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`p`.`geschlecht` AS `geschlecht`,`p`.`geburtstag` AS `geburtstag`,`p`.`photo` AS `photo`,`p`.`photo_dateiname` AS `photo_dateiname`,`p`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`p`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`p`.`photo_mime_type` AS `photo_mime_type`,`p`.`kleinbild` AS `kleinbild`,`p`.`sitzplatz` AS `sitzplatz`,`p`.`email` AS `email`,`p`.`homepage` AS `homepage`,`p`.`parlament_biografie_id` AS `parlament_biografie_id`,`p`.`twitter_name` AS `twitter_name`,`p`.`linkedin_profil_url` AS `linkedin_profil_url`,`p`.`xing_profil_name` AS `xing_profil_name`,`p`.`facebook_name` AS `facebook_name`,`p`.`arbeitssprache` AS `arbeitssprache`,`p`.`adresse_firma` AS `adresse_firma`,`p`.`adresse_strasse` AS `adresse_strasse`,`p`.`adresse_zusatz` AS `adresse_zusatz`,`p`.`adresse_plz` AS `adresse_plz`,`p`.`adresse_ort` AS `adresse_ort`,`p`.`ALT_kommission` AS `ALT_kommission`,`p`.`notizen` AS `notizen`,`p`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`p`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`p`.`kontrolliert_visa` AS `kontrolliert_visa`,`p`.`kontrolliert_datum` AS `kontrolliert_datum`,`p`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`p`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`p`.`autorisiert_visa` AS `autorisiert_visa`,`p`.`autorisiert_datum` AS `autorisiert_datum`,`p`.`freigabe_visa` AS `freigabe_visa`,`p`.`freigabe_datum` AS `freigabe_datum`,`p`.`created_visa` AS `created_visa`,`p`.`created_date` AS `created_date`,`p`.`updated_visa` AS `updated_visa`,`p`.`updated_date` AS `updated_date`,group_concat(distinct concat(`k`.`name`,'(',`k`.`abkuerzung`,')') order by `k`.`abkuerzung` ASC separator ', ') AS `kommissionen_namen`,-(-(group_concat(distinct concat(`k`.`name`,'(',`k`.`abkuerzung`,')') order by `k`.`abkuerzung` ASC separator ', '))) AS `kommissionen2`,group_concat(distinct `k`.`abkuerzung` order by `k`.`abkuerzung` ASC separator ', ') AS `kommissionen_abkuerzung`,`partei`.`abkuerzung` AS `partei`,`fraktion`.`abkuerzung` AS `fraktion`,`mil_grad`.`name` AS `militaerischer_grad` from (((((((`parlamentarier` `p` left join `v_in_kommission` `ik` on(((`p`.`id` = `ik`.`parlamentarier_id`) and isnull(`ik`.`bis`)))) left join `v_kommission` `k` on((`ik`.`kommission_id` = `k`.`id`))) left join `v_partei` `partei` on((`p`.`partei_id` = `partei`.`id`))) left join `v_fraktion` `fraktion` on((`p`.`fraktion_id` = `fraktion`.`id`))) left join `v_mil_grad` `mil_grad` on((`p`.`militaerischer_grad_id` = `mil_grad`.`id`))) left join `v_kanton` `kanton` on((`p`.`kanton_id` = `kanton`.`id`))) left join `v_rat` `rat` on((`p`.`rat_id` = `rat`.`id`))) group by `p`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_anhang`
--
DROP TABLE IF EXISTS `v_parlamentarier_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_anhang` AS select `parlamentarier_anhang`.`parlamentarier_id` AS `parlamentarier_id2`,`parlamentarier_anhang`.`id` AS `id`,`parlamentarier_anhang`.`parlamentarier_id` AS `parlamentarier_id`,`parlamentarier_anhang`.`datei` AS `datei`,`parlamentarier_anhang`.`dateiname` AS `dateiname`,`parlamentarier_anhang`.`dateierweiterung` AS `dateierweiterung`,`parlamentarier_anhang`.`dateiname_voll` AS `dateiname_voll`,`parlamentarier_anhang`.`mime_type` AS `mime_type`,`parlamentarier_anhang`.`encoding` AS `encoding`,`parlamentarier_anhang`.`beschreibung` AS `beschreibung`,`parlamentarier_anhang`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier_anhang`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier_anhang`.`created_visa` AS `created_visa`,`parlamentarier_anhang`.`created_date` AS `created_date`,`parlamentarier_anhang`.`updated_visa` AS `updated_visa`,`parlamentarier_anhang`.`updated_date` AS `updated_date` from `parlamentarier_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_authorisierungs_email`
--
DROP TABLE IF EXISTS `v_parlamentarier_authorisierungs_email`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_authorisierungs_email` AS select `parlamentarier`.`id` AS `id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`email` AS `email`,concat((case `parlamentarier`.`geschlecht` when 'M' then concat('<p>Sehr geehrter Herr ',`parlamentarier`.`nachname`,'</p>') when 'F' then concat('<p>Sehr geehrte Frau ',`parlamentarier`.`nachname`,'</p>') else concat('<p>Sehr geehrte(r) Herr/Frau ',`parlamentarier`.`nachname`,'</p>') end),'<p>[Einleitung]</p>','<p>Ihre <b>Interessenbindungen</b>:</p>','<ul>',group_concat(distinct concat('<li>',`organisation`.`anzeige_name`,if((isnull(`organisation`.`rechtsform`) or (trim(`organisation`.`rechtsform`) = '')),'',concat(', ',`organisation`.`rechtsform`)),if((isnull(`organisation`.`ort`) or (trim(`organisation`.`ort`) = '')),'',concat(', ',`organisation`.`ort`)),', ',`interessenbindung`.`art`,', ',ifnull(`interessenbindung`.`beschreibung`,'')) order by `organisation`.`anzeige_name` ASC separator ' '),'</ul>','<p>Ihre <b>Gäste</b>:</p>','<ul>',group_concat(distinct concat('<li>',`zutrittsberechtigung`.`name`,', ',`zutrittsberechtigung`.`funktion`) order by `zutrittsberechtigung`.`name` ASC separator ' '),'</ul>','<p><b>Mandate</b> der Gäste:</p>','<ul>',group_concat(distinct concat('<li>',`zutrittsberechtigung`.`name`,', ',`zutrittsberechtigung`.`funktion`,if((`organisation2`.`id` is not null),concat(', ',`organisation2`.`anzeige_name`,if((isnull(`organisation2`.`rechtsform`) or (trim(`organisation2`.`rechtsform`) = '')),'',concat(', ',`organisation2`.`rechtsform`)),if((isnull(`organisation2`.`ort`) or (trim(`organisation2`.`ort`) = '')),'',concat(', ',`organisation2`.`ort`)),', ',ifnull(`mandat`.`art`,''),', ',ifnull(`mandat`.`beschreibung`,'')),'')) order by `zutrittsberechtigung`.`name` ASC,`organisation2`.`anzeige_name` ASC separator ' '),'</ul>','<p>Freundliche Grüsse<br></p>') AS `email_text_html`,`UTF8_URLENCODE`(concat((case `parlamentarier`.`geschlecht` when 'M' then concat('Sehr geehrter Herr ',`parlamentarier`.`nachname`,'\r\n') when 'F' then concat('Sehr geehrte Frau ',`parlamentarier`.`nachname`,'\r\n') else concat('Sehr geehrte(r) Herr/Frau ',`parlamentarier`.`nachname`,'\r\n') end),'\r\n[Ersetze Text mit HTML-Vorlage]\r\n','Ihre Interessenbindungen:\r\n',group_concat(distinct concat('* ',`organisation`.`anzeige_name`,if((isnull(`organisation`.`rechtsform`) or (trim(`organisation`.`rechtsform`) = '')),'',concat(', ',`organisation`.`rechtsform`)),if((isnull(`organisation`.`ort`) or (trim(`organisation`.`ort`) = '')),'',concat(', ',`organisation`.`ort`)),', ',`interessenbindung`.`art`,', ',ifnull(`interessenbindung`.`beschreibung`,''),'\r\n') order by `organisation`.`anzeige_name` ASC separator ' '),'\r\nIhre Gäste:\r\n',group_concat(distinct concat('* ',`zutrittsberechtigung`.`name`,', ',`zutrittsberechtigung`.`funktion`,'\r\n') order by `organisation`.`anzeige_name` ASC separator ' '),'\r\nMit freundlichen Grüssen,\r\n')) AS `email_text_for_url` from (((((`v_parlamentarier` `parlamentarier` left join `v_interessenbindung` `interessenbindung` on(((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`) and isnull(`interessenbindung`.`bis`)))) left join `v_organisation` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) left join `v_zutrittsberechtigung` `zutrittsberechtigung` on(((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`) and isnull(`zutrittsberechtigung`.`bis`)))) left join `v_mandat` `mandat` on(((`mandat`.`zutrittsberechtigung_id` = `zutrittsberechtigung`.`id`) and isnull(`mandat`.`bis`)))) left join `v_organisation` `organisation2` on((`mandat`.`organisation_id` = `organisation2`.`id`))) where isnull(`parlamentarier`.`im_rat_bis`) group by `parlamentarier`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_partei`
--
DROP TABLE IF EXISTS `v_partei`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_partei` AS select concat(`partei`.`name`,' (',`partei`.`abkuerzung`,')') AS `anzeige_name`,`partei`.`id` AS `id`,`partei`.`abkuerzung` AS `abkuerzung`,`partei`.`name` AS `name`,`partei`.`fraktion_id` AS `fraktion_id`,`partei`.`gruendung` AS `gruendung`,`partei`.`position` AS `position`,`partei`.`farbcode` AS `farbcode`,`partei`.`homepage` AS `homepage`,`partei`.`email` AS `email`,`partei`.`twitter_name` AS `twitter_name`,`partei`.`beschreibung` AS `beschreibung`,`partei`.`notizen` AS `notizen`,`partei`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`partei`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`partei`.`kontrolliert_visa` AS `kontrolliert_visa`,`partei`.`kontrolliert_datum` AS `kontrolliert_datum`,`partei`.`freigabe_visa` AS `freigabe_visa`,`partei`.`freigabe_datum` AS `freigabe_datum`,`partei`.`created_visa` AS `created_visa`,`partei`.`created_date` AS `created_date`,`partei`.`updated_visa` AS `updated_visa`,`partei`.`updated_date` AS `updated_date` from `partei`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_rat`
--
DROP TABLE IF EXISTS `v_rat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rat` AS select `rat`.`name_de` AS `anzeige_name`,`rat`.`id` AS `id`,`rat`.`abkuerzung` AS `abkuerzung`,`rat`.`name_de` AS `name_de`,`rat`.`name_fr` AS `name_fr`,`rat`.`name_it` AS `name_it`,`rat`.`name_en` AS `name_en`,`rat`.`anzahl_mitglieder` AS `anzahl_mitglieder`,`rat`.`typ` AS `typ`,`rat`.`interessenraum_id` AS `interessenraum_id`,`rat`.`anzeigestufe` AS `anzeigestufe`,`rat`.`gewicht` AS `gewicht`,`rat`.`beschreibung` AS `beschreibung`,`rat`.`homepage_de` AS `homepage_de`,`rat`.`homepage_fr` AS `homepage_fr`,`rat`.`homepage_it` AS `homepage_it`,`rat`.`homepage_en` AS `homepage_en`,`rat`.`notizen` AS `notizen`,`rat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`rat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`rat`.`kontrolliert_visa` AS `kontrolliert_visa`,`rat`.`kontrolliert_datum` AS `kontrolliert_datum`,`rat`.`freigabe_visa` AS `freigabe_visa`,`rat`.`freigabe_datum` AS `freigabe_datum`,`rat`.`created_visa` AS `created_visa`,`rat`.`created_date` AS `created_date`,`rat`.`updated_visa` AS `updated_visa`,`rat`.`updated_date` AS `updated_date` from `rat` order by `rat`.`gewicht`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_settings`
--
DROP TABLE IF EXISTS `v_settings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_settings` AS select `settings`.`id` AS `id`,`settings`.`key_name` AS `key_name`,`settings`.`value` AS `value`,`settings`.`description` AS `description`,`settings`.`category_id` AS `category_id`,`settings`.`notizen` AS `notizen`,`settings`.`created_visa` AS `created_visa`,`settings`.`created_date` AS `created_date`,`settings`.`updated_visa` AS `updated_visa`,`settings`.`updated_date` AS `updated_date`,`settings_category`.`name` AS `category_name` from (`settings` left join `v_settings_category` `settings_category` on((`settings`.`category_id` = `settings_category`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_settings_category`
--
DROP TABLE IF EXISTS `v_settings_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_settings_category` AS select `settings_category`.`id` AS `id`,`settings_category`.`name` AS `name`,`settings_category`.`description` AS `description`,`settings_category`.`notizen` AS `notizen`,`settings_category`.`created_visa` AS `created_visa`,`settings_category`.`created_date` AS `created_date`,`settings_category`.`updated_visa` AS `updated_visa`,`settings_category`.`updated_date` AS `updated_date` from `settings_category`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_user`
--
DROP TABLE IF EXISTS `v_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user` AS select concat_ws(' ',`u`.`vorname`,`u`.`nachname`) AS `anzeige_name`,`u`.`id` AS `id`,`u`.`name` AS `name`,`u`.`password` AS `password`,`u`.`nachname` AS `nachname`,`u`.`vorname` AS `vorname`,`u`.`email` AS `email`,`u`.`last_login` AS `last_login`,`u`.`notizen` AS `notizen`,`u`.`created_visa` AS `created_visa`,`u`.`created_date` AS `created_date`,`u`.`updated_visa` AS `updated_visa`,`u`.`updated_date` AS `updated_date` from `user` `u`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_user_permission`
--
DROP TABLE IF EXISTS `v_user_permission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_permission` AS select `t`.`id` AS `id`,`t`.`user_id` AS `user_id`,`t`.`page_name` AS `page_name`,`t`.`permission_name` AS `permission_name` from `user_permission` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung` AS select concat(`zutrittsberechtigung`.`nachname`,', ',`zutrittsberechtigung`.`vorname`) AS `anzeige_name`,concat(`zutrittsberechtigung`.`vorname`,' ',`zutrittsberechtigung`.`nachname`) AS `name`,`zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`nachname` AS `nachname`,`zutrittsberechtigung`.`vorname` AS `vorname`,`zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`beruf` AS `beruf`,`zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`zutrittsberechtigung`.`partei_id` AS `partei_id`,`zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`zutrittsberechtigung`.`email` AS `email`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`zutrittsberechtigung`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`ALT_lobbyorganisation_id` AS `ALT_lobbyorganisation_id`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,`partei`.`abkuerzung` AS `partei`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name` from ((`zutrittsberechtigung` left join `v_partei` `partei` on((`zutrittsberechtigung`.`partei_id` = `partei`.`id`))) left join `v_parlamentarier` `parlamentarier` on((`parlamentarier`.`id` = `zutrittsberechtigung`.`parlamentarier_id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_anhang`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_anhang` AS select `zutrittsberechtigung_anhang`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id2`,`zutrittsberechtigung_anhang`.`id` AS `id`,`zutrittsberechtigung_anhang`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung_anhang`.`datei` AS `datei`,`zutrittsberechtigung_anhang`.`dateiname` AS `dateiname`,`zutrittsberechtigung_anhang`.`dateierweiterung` AS `dateierweiterung`,`zutrittsberechtigung_anhang`.`dateiname_voll` AS `dateiname_voll`,`zutrittsberechtigung_anhang`.`mime_type` AS `mime_type`,`zutrittsberechtigung_anhang`.`encoding` AS `encoding`,`zutrittsberechtigung_anhang`.`beschreibung` AS `beschreibung`,`zutrittsberechtigung_anhang`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung_anhang`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung_anhang`.`created_visa` AS `created_visa`,`zutrittsberechtigung_anhang`.`created_date` AS `created_date`,`zutrittsberechtigung_anhang`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung_anhang`.`updated_date` AS `updated_date` from `zutrittsberechtigung_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_authorisierungs_email`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_authorisierungs_email`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_authorisierungs_email` AS select `parlamentarier`.`name` AS `parlamentarier_name`,ifnull(`parlamentarier`.`geschlecht`,'') AS `geschlecht`,`zutrittsberechtigung`.`name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion` from (`v_zutrittsberechtigung` `zutrittsberechtigung` join `v_parlamentarier` `parlamentarier` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`))) group by `parlamentarier`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_mandate`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_mandate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_mandate` AS select `zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`organisation`.`anzeige_name` AS `organisation_name`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`mandat`.`id` AS `id`,`mandat`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from ((`v_zutrittsberechtigung` `zutrittsberechtigung` join `v_mandat` `mandat` on((`zutrittsberechtigung`.`id` = `mandat`.`zutrittsberechtigung_id`))) join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_mit_mandaten`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_mit_mandaten` AS select `organisation`.`anzeige_name` AS `organisation_name`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mandat`.`id` AS `id`,`mandat`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from ((`v_zutrittsberechtigung` `zutrittsberechtigung` left join `v_mandat` `mandat` on((`zutrittsberechtigung`.`id` = `mandat`.`zutrittsberechtigung_id`))) left join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) order by `zutrittsberechtigung`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_mit_mandaten_indirekt`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_mit_mandaten_indirekt` AS select 'direkt' AS `beziehung`,`zutrittsberechtigung`.`organisation_name` AS `organisation_name`,`zutrittsberechtigung`.`zutrittsberechtigung_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`organisation_id` AS `organisation_id`,`zutrittsberechtigung`.`art` AS `art`,`zutrittsberechtigung`.`funktion_im_gremium` AS `funktion_im_gremium`,`zutrittsberechtigung`.`verguetung` AS `verguetung`,`zutrittsberechtigung`.`beschreibung` AS `beschreibung`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date` from `v_zutrittsberechtigung_mit_mandaten` `zutrittsberechtigung` union select 'indirekt' AS `beziehung`,`organisation`.`name` AS `organisation_name`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mandat`.`id` AS `id`,`mandat`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from (((`v_zutrittsberechtigung` `zutrittsberechtigung` join `v_mandat` `mandat` on((`zutrittsberechtigung`.`id` = `mandat`.`zutrittsberechtigung_id`))) join `v_organisation_beziehung` `organisation_beziehung` on((`mandat`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`organisation_name`;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `branche`
--
ALTER TABLE `branche`
  ADD CONSTRAINT `fk_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`);

--
-- Constraints der Tabelle `branche_log`
--
ALTER TABLE `branche_log`
  ADD CONSTRAINT `fk_branche_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `fraktion_log`
--
ALTER TABLE `fraktion_log`
  ADD CONSTRAINT `fk_fraktion_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `interessenbindung`
--
ALTER TABLE `interessenbindung`
  ADD CONSTRAINT `fk_ib_org` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

--
-- Constraints der Tabelle `interessenbindung_log`
--
ALTER TABLE `interessenbindung_log`
  ADD CONSTRAINT `fk_interessenbindung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `interessengruppe`
--
ALTER TABLE `interessengruppe`
  ADD CONSTRAINT `fk_lg_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `interessengruppe_log`
--
ALTER TABLE `interessengruppe_log`
  ADD CONSTRAINT `fk_interessengruppe_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `in_kommission`
--
ALTER TABLE `in_kommission`
  ADD CONSTRAINT `fk_in_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`),
  ADD CONSTRAINT `fk_in_parlamentarier_id` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

--
-- Constraints der Tabelle `in_kommission_log`
--
ALTER TABLE `in_kommission_log`
  ADD CONSTRAINT `fk_in_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `kanton_jahr`
--
ALTER TABLE `kanton_jahr`
  ADD CONSTRAINT `fk_kanton_jahr_kanton_id` FOREIGN KEY (`kanton_id`) REFERENCES `kanton` (`id`);

--
-- Constraints der Tabelle `kanton_jahr_log`
--
ALTER TABLE `kanton_jahr_log`
  ADD CONSTRAINT `fk_kanton_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `kanton_log`
--
ALTER TABLE `kanton_log`
  ADD CONSTRAINT `fk_kanton_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `kommission`
--
ALTER TABLE `kommission`
  ADD CONSTRAINT `fk_parent_kommission_id` FOREIGN KEY (`mutter_kommission_id`) REFERENCES `kommission` (`id`);

--
-- Constraints der Tabelle `kommission_log`
--
ALTER TABLE `kommission_log`
  ADD CONSTRAINT `fk_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `mandat`
--
ALTER TABLE `mandat`
  ADD CONSTRAINT `fk_organisations_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_zugangsberechtigung_id` FOREIGN KEY (`zutrittsberechtigung_id`) REFERENCES `zutrittsberechtigung` (`id`);

--
-- Constraints der Tabelle `mandat_log`
--
ALTER TABLE `mandat_log`
  ADD CONSTRAINT `fk_mandat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `mil_grad_log`
--
ALTER TABLE `mil_grad_log`
  ADD CONSTRAINT `fk_mil_grad_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `organisation`
--
ALTER TABLE `organisation`
  ADD CONSTRAINT `fk_lo_lg` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_lo_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_organisation_interessengruppe2_id` FOREIGN KEY (`interessengruppe2_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_organisation_interessengruppe3_id` FOREIGN KEY (`interessengruppe3_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_org_country` FOREIGN KEY (`land_id`) REFERENCES `country` (`id`),
  ADD CONSTRAINT `fk_org_interessenraum` FOREIGN KEY (`interessenraum_id`) REFERENCES `interessenraum` (`id`);

--
-- Constraints der Tabelle `organisation_anhang`
--
ALTER TABLE `organisation_anhang`
  ADD CONSTRAINT `fk_org_anhang` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `organisation_anhang_log`
--
ALTER TABLE `organisation_anhang_log`
  ADD CONSTRAINT `fk_organisation_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `organisation_beziehung`
--
ALTER TABLE `organisation_beziehung`
  ADD CONSTRAINT `fk_quell_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_ziel_organisation_id` FOREIGN KEY (`ziel_organisation_id`) REFERENCES `organisation` (`id`);

--
-- Constraints der Tabelle `organisation_beziehung_log`
--
ALTER TABLE `organisation_beziehung_log`
  ADD CONSTRAINT `fk_organisation_beziehung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `organisation_jahr`
--
ALTER TABLE `organisation_jahr`
  ADD CONSTRAINT `fk_organisation_jahr_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`);

--
-- Constraints der Tabelle `organisation_jahr_log`
--
ALTER TABLE `organisation_jahr_log`
  ADD CONSTRAINT `fk_organisation_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `organisation_log`
--
ALTER TABLE `organisation_log`
  ADD CONSTRAINT `fk_organisation_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `parlamentarier`
--
ALTER TABLE `parlamentarier`
  ADD CONSTRAINT `fk_beruf_interessengruppe_id` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_kanton_id` FOREIGN KEY (`kanton_id`) REFERENCES `kanton` (`id`),
  ADD CONSTRAINT `fk_mil_grad` FOREIGN KEY (`militaerischer_grad_id`) REFERENCES `mil_grad` (`id`),
  ADD CONSTRAINT `fk_parlamentarier_fraktion_id` FOREIGN KEY (`fraktion_id`) REFERENCES `fraktion` (`id`),
  ADD CONSTRAINT `fk_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`),
  ADD CONSTRAINT `fk_rat_id` FOREIGN KEY (`rat_id`) REFERENCES `rat` (`id`);

--
-- Constraints der Tabelle `parlamentarier_anhang`
--
ALTER TABLE `parlamentarier_anhang`
  ADD CONSTRAINT `fk_parlam_anhang` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `parlamentarier_anhang_log`
--
ALTER TABLE `parlamentarier_anhang_log`
  ADD CONSTRAINT `fk_parlamentarier_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `parlamentarier_log`
--
ALTER TABLE `parlamentarier_log`
  ADD CONSTRAINT `fk_parlamentarier_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `partei`
--
ALTER TABLE `partei`
  ADD CONSTRAINT `fk_partei_fraktion_id` FOREIGN KEY (`fraktion_id`) REFERENCES `fraktion` (`id`);

--
-- Constraints der Tabelle `partei_log`
--
ALTER TABLE `partei_log`
  ADD CONSTRAINT `fk_partei_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `rat`
--
ALTER TABLE `rat`
  ADD CONSTRAINT `fk_interessenraum_id` FOREIGN KEY (`interessenraum_id`) REFERENCES `interessenraum` (`id`);

--
-- Constraints der Tabelle `rat_log`
--
ALTER TABLE `rat_log`
  ADD CONSTRAINT `fk_rat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `fk_settings_category_id` FOREIGN KEY (`category_id`) REFERENCES `settings_category` (`id`);

--
-- Constraints der Tabelle `settings_category_log`
--
ALTER TABLE `settings_category_log`
  ADD CONSTRAINT `fk_settings_category_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `settings_log`
--
ALTER TABLE `settings_log`
  ADD CONSTRAINT `fk_settings_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `zutrittsberechtigung`
--
ALTER TABLE `zutrittsberechtigung`
  ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_zb_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`),
  ADD CONSTRAINT `fk_zutrittsberechtigung_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`);

--
-- Constraints der Tabelle `zutrittsberechtigung_anhang`
--
ALTER TABLE `zutrittsberechtigung_anhang`
  ADD CONSTRAINT `fk_zutrittsberechtigung_anhang_zutrittsberechtigung_id` FOREIGN KEY (`zutrittsberechtigung_id`) REFERENCES `zutrittsberechtigung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `zutrittsberechtigung_anhang_log`
--
ALTER TABLE `zutrittsberechtigung_anhang_log`
  ADD CONSTRAINT `fk_zutrittsberechtigung_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `zutrittsberechtigung_log`
--
ALTER TABLE `zutrittsberechtigung_log`
  ADD CONSTRAINT `fk_zutrittsberechtigung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
