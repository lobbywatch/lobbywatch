-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 13. Dez 2014 um 07:30
-- Server Version: 5.6.21
-- PHP-Version: 5.5.19

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

DELIMITER $$
--
-- Prozeduren
--
DROP PROCEDURE IF EXISTS `refreshMaterializedViews`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `refreshMaterializedViews`()
    MODIFIES SQL DATA
    COMMENT 'Aktualisiert die Materialized Views.'
BEGIN
  DECLARE ts TIMESTAMP DEFAULT NOW();

--	REPLACE INTO `mv_organisation_lobbyeinfluss`
--	  SELECT * FROM `v_organisation_lobbyeinfluss_raw`;

--	REPLACE INTO `mv_zutrittsberechtigung_lobbyfaktor`
--	  SELECT * FROM `v_zutrittsberechtigung_lobbyfaktor_raw`;

--	REPLACE INTO `mv_parlamentarier_lobbyfaktor`
--	  SELECT * FROM `v_parlamentarier_lobbyfaktor_raw`;

--	REPLACE INTO `mv_zutrittsberechtigung_lobbyfaktor_max`
--	  SELECT * FROM `v_zutrittsberechtigung_lobbyfaktor_max_raw`;

--	REPLACE INTO `mv_parlamentarier_lobbyfaktor_max`
--	  SELECT * FROM `v_parlamentarier_lobbyfaktor_max_raw`;

	REPLACE INTO `mv_interessenbindung`
	  SELECT * FROM `v_interessenbindung_raw`;

	REPLACE INTO `mv_mandat`
	  SELECT * FROM `v_mandat_raw`;

--	REPLACE INTO `mv_organisation_medium`
--	  SELECT * FROM `v_organisation_medium_raw`;
--
--	REPLACE INTO `mv_organisation_medium_myisam`
--	  SELECT * FROM `v_organisation_medium_raw`;

	REPLACE INTO `mv_organisation`
	  SELECT * FROM `v_organisation_raw`;

--	REPLACE INTO `mv_organisation_myisam`
--	  SELECT * FROM `v_organisation_raw`;

--	REPLACE INTO `mv_parlamentarier_medium`
--	  SELECT * FROM `v_parlamentarier_medium_raw`;
--
--	REPLACE INTO `mv_parlamentarier_medium_myisam`
--	  SELECT * FROM `v_parlamentarier_medium_raw`;

	REPLACE INTO `mv_parlamentarier`
	  SELECT * FROM `v_parlamentarier_raw`;

--	REPLACE INTO `mv_parlamentarier_myisam`
--	  SELECT * FROM `v_parlamentarier_raw`;

	REPLACE INTO `mv_zutrittsberechtigung`
	  SELECT * FROM `v_zutrittsberechtigung_raw`;

--	REPLACE INTO `mv_zutrittsberechtigung_myisam`
--	  SELECT * FROM `v_zutrittsberechtigung_raw`;

	REPLACE INTO `mv_search_table`
	  SELECT * FROM `v_search_table_raw`;

END$$

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

END$$

--
-- Funktionen
--
DROP FUNCTION IF EXISTS `UCFIRST`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `UCFIRST`(str VARCHAR(4096) CHARSET utf8) RETURNS varchar(4096) CHARSET utf8
    DETERMINISTIC
    COMMENT 'Returns the str with the first character converted to upper case'
BEGIN
	RETURN CONCAT(UCASE(LEFT(str, 1)), SUBSTRING(str, 2));
END$$

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `branche`;
CREATE TABLE IF NOT EXISTS `branche` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Branche',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Französischer Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `branche_log`;
CREATE TABLE IF NOT EXISTS `branche_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Französischer Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen';

--
-- RELATIONEN DER TABELLE `branche_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
`id` int(10) NOT NULL COMMENT 'Primary key',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 COMMENT='Länder der Welt mit ISO Code (http://countrylist.net)';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fraktion`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `fraktion`;
CREATE TABLE IF NOT EXISTS `fraktion` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Fraktion',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `fraktion_log`;
CREATE TABLE IF NOT EXISTS `fraktion_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes';

--
-- RELATIONEN DER TABELLE `fraktion_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `interessenbindung`;
CREATE TABLE IF NOT EXISTS `interessenbindung` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Interessenbindung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=1339 DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern';

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
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate freigabe from mandat to organisation
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
	  -- Person
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
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung_log`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `interessenbindung_log`;
CREATE TABLE IF NOT EXISTS `interessenbindung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern';

--
-- RELATIONEN DER TABELLE `interessenbindung_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessengruppe`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `interessengruppe`;
CREATE TABLE IF NOT EXISTS `interessengruppe` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Interessengruppe',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `interessengruppe_log`;
CREATE TABLE IF NOT EXISTS `interessengruppe_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=578 DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche';

--
-- RELATIONEN DER TABELLE `interessengruppe_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenraum`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `interessenraum`;
CREATE TABLE IF NOT EXISTS `interessenraum` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Interessenraumes',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='Liste der Interessenbereiche (Stammdaten)';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `in_kommission`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `in_kommission`;
CREATE TABLE IF NOT EXISTS `in_kommission` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel einer Kommissionszugehörigkeit',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` enum('praesident','vizepraesident','mitglied','co-praesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am'
) ENGINE=InnoDB AUTO_INCREMENT=637 DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern';

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

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- Fill parlamentarier.kommissionen on change
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

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- Fill parlamentarier.kommissionen on change
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

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- Fill parlamentarier.kommissionen on change
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
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `in_kommission_log`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `in_kommission_log`;
CREATE TABLE IF NOT EXISTS `in_kommission_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` enum('praesident','vizepraesident','mitglied','co-praesident') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=3149 DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern';

--
-- RELATIONEN DER TABELLE `in_kommission_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `kanton`;
CREATE TABLE IF NOT EXISTS `kanton` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Kantons',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='Kantone der Schweiz';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `kanton_jahr`;
CREATE TABLE IF NOT EXISTS `kanton_jahr` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Jahreswerte eines Kantons',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Kantonen';

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
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Kantonen';

--
-- RELATIONEN DER TABELLE `kanton_jahr_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kanton_log`
--
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 COMMENT='Kantone der Schweiz';

--
-- RELATIONEN DER TABELLE `kanton_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `kommission`;
CREATE TABLE IF NOT EXISTS `kommission` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Kommission',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `abkuerzung_fr` varchar(15) DEFAULT NULL COMMENT 'Französisches Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Kommission',
  `typ` enum('kommission','subkommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.',
  `beschreibung` text COMMENT 'Beschreibung der Kommission',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Kommission',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `sachbereiche_fr` text COMMENT 'Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".',
  `anzahl_nationalraete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Nationalrates',
  `anzahl_staenderaete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Ständerates',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen';

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

  -- Propagate freigabe from kommission to his interessenbindungen
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
	  -- in_kommission
	  -- Avoid ERROR 1442 (HY000): Cant update table 'in_kommission' in stored function/trigger because it is already used by statement which invoked this stored function/trigger
	  -- SET @disable_parlamentarier_kommissionen_update = 1;
	  UPDATE `in_kommission`
	    SET
	    freigabe_datum = NEW.freigabe_datum,
	    freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
	    updated_date = NEW.updated_date,
	    updated_visa = CONCAT(NEW.updated_visa, '*')
	    WHERE
	    kommission_id=NEW.id AND bis IS NULL;
	  -- SET @disable_parlamentarier_kommissionen_update = NULL;
  END IF;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission_log`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `kommission_log`;
CREATE TABLE IF NOT EXISTS `kommission_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `abkuerzung_fr` varchar(15) DEFAULT NULL COMMENT 'Französisches Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Kommission',
  `typ` enum('kommission','subkommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.',
  `beschreibung` text COMMENT 'Beschreibung der Kommission',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Kommission',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `sachbereiche_fr` text COMMENT 'Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".',
  `anzahl_nationalraete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Nationalrates',
  `anzahl_staenderaete` tinyint(3) unsigned DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Ständerates',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen';

--
-- RELATIONEN DER TABELLE `kommission_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `mandat`;
CREATE TABLE IF NOT EXISTS `mandat` (
`id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am'
) ENGINE=InnoDB AUTO_INCREMENT=636 DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten';

--
-- RELATIONEN DER TABELLE `mandat`:
--   `person_id`
--       `person` -> `id`
--   `organisation_id`
--       `organisation` -> `id`
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

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate freigabe from mandat to organisation
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
	  -- Person
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
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat_log`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `mandat_log`;
CREATE TABLE IF NOT EXISTS `mandat_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=4725 DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten';

--
-- RELATIONEN DER TABELLE `mandat_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mil_grad`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `mil_grad`;
CREATE TABLE IF NOT EXISTS `mil_grad` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel Militärischer Grad',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am'
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='Militärische Grade (Armee XXI)';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `mil_grad_log`;
CREATE TABLE IF NOT EXISTS `mil_grad_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Militärische Grade (Armee XXI)';

--
-- RELATIONEN DER TABELLE `mil_grad_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mv_interessenbindung`
--
-- Erstellt am: 08. Dez 2014 um 09:16
--

DROP TABLE IF EXISTS `mv_interessenbindung`;
CREATE TABLE IF NOT EXISTS `mv_interessenbindung` (
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Interessenbindung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht') NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `behoerden_vertreter` enum('J','N') DEFAULT NULL COMMENT 'Enstand diese Interessenbindung als Behoerdenvertreter von amteswegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von amteswegen einsitz nimmt.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
  `quelle_url_gueltig` tinyint(1) DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?',
  `quelle` varchar(80) DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen',
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
  `wirksamkeit_index` int(0) NOT NULL DEFAULT '0',
  `organisation_lobbyeinfluss` varchar(9) DEFAULT NULL,
  `parlamentarier_lobbyfaktor` bigint(25) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_interessenbindung';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mv_mandat`
--
-- Erstellt am: 08. Dez 2014 um 09:16
--

DROP TABLE IF EXISTS `mv_mandat`;
CREATE TABLE IF NOT EXISTS `mv_mandat` (
  `id` int(11) NOT NULL DEFAULT '0',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `funktion_im_gremium` enum('praesident','vizepraesident','mitglied') DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Jährliche Vergütung CHF dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.',
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
  `updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Abgäendert am',
  `bis_unix` bigint(11) DEFAULT NULL,
  `von_unix` bigint(11) DEFAULT NULL,
  `created_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `updated_date_unix` bigint(11) NOT NULL DEFAULT '0',
  `eingabe_abgeschlossen_datum_unix` bigint(11) DEFAULT NULL,
  `kontrolliert_datum_unix` bigint(11) DEFAULT NULL,
  `freigabe_datum_unix` bigint(11) DEFAULT NULL,
  `wirksamkeit` varchar(6) NOT NULL DEFAULT '',
  `wirksamkeit_index` int(0) NOT NULL DEFAULT '0',
  `organisation_lobbyeinfluss` varchar(9) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_mandat';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mv_organisation`
--
-- Erstellt am: 08. Dez 2014 um 09:16
--

DROP TABLE IF EXISTS `mv_organisation`;
CREATE TABLE IF NOT EXISTS `mv_organisation` (
  `anzeige_name` varchar(454) DEFAULT NULL,
  `anzeige_name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `anzeige_name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name` varchar(454) DEFAULT NULL,
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `uid` varchar(15) DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html)',
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
  `beschreibung_fr` text COMMENT 'Französische Beschreibung',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
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
  `lobbyeinfluss_index` int(0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_organisation';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mv_parlamentarier`
--
-- Erstellt am: 08. Dez 2014 um 09:16
--

DROP TABLE IF EXISTS `mv_parlamentarier`;
CREATE TABLE IF NOT EXISTS `mv_parlamentarier` (
  `anzeige_name` varchar(152) NOT NULL DEFAULT '',
  `anzeige_name_de` varchar(152) NOT NULL DEFAULT '',
  `anzeige_name_fr` varchar(152) NOT NULL DEFAULT '',
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
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.',
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
  `ratstyp_BAD` varchar(10) DEFAULT NULL COMMENT 'Not used, duplicate',
  `kanton_abkuerzung_BAD` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH') DEFAULT NULL COMMENT 'Not used, duplicate',
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
  `anzahl_interessenbindung_hoch_max` tinyint(3) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_parlamentarier';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mv_search_table`
--
-- Erstellt am: 08. Dez 2014 um 09:16
--

DROP TABLE IF EXISTS `mv_search_table`;
CREATE TABLE IF NOT EXISTS `mv_search_table` (
  `id` int(11) NOT NULL DEFAULT '0',
  `table_name` varchar(20) NOT NULL DEFAULT '',
  `page` varchar(20) NOT NULL DEFAULT '',
  `table_weight` bigint(20) NOT NULL DEFAULT '0',
  `name_de` varchar(190) DEFAULT NULL,
  `name_fr` varchar(190) DEFAULT NULL,
  `search_keywords_de` varchar(509) DEFAULT NULL,
  `search_keywords_fr` varchar(509) DEFAULT NULL,
  `freigabe_datum` timestamp NULL DEFAULT NULL,
  `bis` date DEFAULT NULL,
  `weight` bigint(20) DEFAULT NULL,
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for parlamentarier, zutrittsberechtigung, branche, interessengruppe, kommission, organisation, partei';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mv_zutrittsberechtigung`
--
-- Erstellt am: 08. Dez 2014 um 09:16
--

DROP TABLE IF EXISTS `mv_zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `mv_zutrittsberechtigung` (
  `anzeige_name` varchar(152) NOT NULL DEFAULT '',
  `anzeige_name_de` varchar(152) NOT NULL DEFAULT '',
  `anzeige_name_fr` varchar(152) NOT NULL DEFAULT '',
  `name` varchar(151) NOT NULL DEFAULT '',
  `name_de` varchar(151) NOT NULL DEFAULT '',
  `name_fr` varchar(151) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL DEFAULT '0' COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `beschreibung_de` text COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf der Person',
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
  `erfasst` enum('Ja','Nein') NOT NULL DEFAULT 'Ja' COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `autorisierung_verschickt_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch',
  `autorisierung_verschickt_datum` timestamp NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.',
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
  `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Materialzed view for v_zutrittsberechtigung';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation`
--
-- Erstellt am: 29. Nov 2014 um 05:56
--

DROP TABLE IF EXISTS `organisation`;
CREATE TABLE IF NOT EXISTS `organisation` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `uid` varchar(15) DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html)',
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
  `beschreibung_fr` text COMMENT 'Französische Beschreibung',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=1579 DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen';

--
-- RELATIONEN DER TABELLE `organisation`:
--   `interessengruppe_id`
--       `interessengruppe` -> `id`
--   `land_id`
--       `country` -> `id`
--   `interessenraum_id`
--       `interessenraum` -> `id`
--   `interessengruppe2_id`
--       `interessengruppe` -> `id`
--   `interessengruppe3_id`
--       `interessengruppe` -> `id`
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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `organisation_anhang`;
CREATE TABLE IF NOT EXISTS `organisation_anhang` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Organisationsanhangs',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen';

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
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen';

--
-- RELATIONEN DER TABELLE `organisation_anhang_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_beziehung`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `organisation_beziehung`;
CREATE TABLE IF NOT EXISTS `organisation_beziehung` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel einer Organisationsbeziehung',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am'
) ENGINE=InnoDB AUTO_INCREMENT=536 DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `organisation_beziehung_log`;
CREATE TABLE IF NOT EXISTS `organisation_beziehung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=2535 DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander';

--
-- RELATIONEN DER TABELLE `organisation_beziehung_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_jahr`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `organisation_jahr`;
CREATE TABLE IF NOT EXISTS `organisation_jahr` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Jahreswerte einer Organisation',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen';

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
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen';

--
-- RELATIONEN DER TABELLE `organisation_jahr_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_log`
--
-- Erstellt am: 29. Nov 2014 um 05:56
--

DROP TABLE IF EXISTS `organisation_log`;
CREATE TABLE IF NOT EXISTS `organisation_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `uid` varchar(15) DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999',
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
  `beschreibung_fr` text COMMENT 'Französische Beschreibung',
  `adresse_strasse` varchar(100) DEFAULT NULL COMMENT 'Adresse der Organisation',
  `adresse_zusatz` varchar(100) DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach',
  `adresse_plz` varchar(10) DEFAULT NULL COMMENT 'Postleitzahl der Organisation',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=10102 DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen';

--
-- RELATIONEN DER TABELLE `organisation_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `parlamentarier`;
CREATE TABLE IF NOT EXISTS `parlamentarier` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Parlamentariers',
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
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier';

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
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        parlamentarier_id=NEW.id AND bis IS NULL;
  END IF;

  -- Propagate freigabe from parlamentarier to his interessenbindungen
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
	  -- Interessenbindung
	  UPDATE `interessenbindung`
	    SET
	    freigabe_datum = NEW.freigabe_datum,
	    freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
	    updated_date = NEW.updated_date,
	    updated_visa = CONCAT(NEW.updated_visa, '*')
	    WHERE
	    parlamentarier_id=NEW.id AND bis IS NULL;

	  -- TODO organisationen von interessenbindungen?
	  -- TODO set non-null freigabe_datum only if freigabe_datum IS NULL

	  -- zutrittsberechtigung
	  UPDATE `zutrittsberechtigung`
	    SET
	    freigabe_datum = NEW.freigabe_datum,
	    freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
	    updated_date = NEW.updated_date,
	    updated_visa = CONCAT(NEW.updated_visa, '*')
	    WHERE
	    parlamentarier_id=NEW.id AND bis IS NULL;

	  -- TODO organisationen von zutrittsberechtigten?

	  -- in_kommission
	  -- Avoid ERROR 1442 (HY000): Cant update table 'in_kommission' in stored function/trigger because it is already used by statement which invoked this stored function/trigger
-- 	  SET @disable_parlamentarier_kommissionen_update = 1;
-- 	  UPDATE `in_kommission`
-- 	    SET
-- 	    freigabe_datum = NEW.freigabe_datum,
-- 	    freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
-- 	    updated_date = NEW.updated_date,
-- 	    updated_visa = CONCAT(NEW.updated_visa, '*')
-- 	    WHERE
-- 	    parlamentarier_id=NEW.id AND bis IS NULL;
-- 	  SET @disable_parlamentarier_kommissionen_update = NULL;
  END IF;

    -- Propagate im_rat_bis from parlamentarier to his zutrittsberechtigte, if NULL or SAME date as parlamentarier
  IF OLD.im_rat_bis <> NEW.im_rat_bis
    OR (OLD.im_rat_bis IS NULL AND NEW.im_rat_bis IS NOT NULL)
    OR (OLD.im_rat_bis IS NOT NULL AND NEW.im_rat_bis IS NULL) THEN

    -- zutrittsberechtigung
    UPDATE `zutrittsberechtigung`
      SET
      `notizen` = CONCAT_WS('

', CONCAT(DATE_FORMAT(NEW.updated_date,'%d.%m.%Y'), '/', IFNULL(NEW.updated_visa, '?') , '*: Parlamentarier ', NEW.vorname, ' ', NEW.nachname, ' nicht mehr im Rat. Zutrittsberechtigung erloschen. Bis-Datum von ', IFNULL(DATE_FORMAT(bis,'%d.%m.%Y'), 'NULL'), ' auf ',  IFNULL(DATE_FORMAT(NEW.im_rat_bis,'%d.%m.%Y'), 'NULL'), ' gesetzt.'),`notizen`),
      bis = NEW.im_rat_bis,
      updated_date = NEW.updated_date,
      updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
      parlamentarier_id=NEW.id AND (bis IS NULL OR bis = OLD.im_rat_bis);

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `parlamentarier_anhang`;
CREATE TABLE IF NOT EXISTS `parlamentarier_anhang` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Parlamentarieranhangs',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am'
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern';

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
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=364 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern';

--
-- RELATIONEN DER TABELLE `parlamentarier_anhang_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier_log`
--
-- Erstellt am: 08. Dez 2014 um 09:15
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
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers auf französisch',
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
  `telephon_1` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz',
  `telephon_2` varchar(25) DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon',
  `erfasst` enum('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=3017 DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier';

--
-- RELATIONEN DER TABELLE `parlamentarier_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `partei`;
CREATE TABLE IF NOT EXISTS `partei` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Partei',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes';

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
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `partei_log`;
CREATE TABLE IF NOT EXISTS `partei_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes';

--
-- RELATIONEN DER TABELLE `partei_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `person`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person',
  `beschreibung_de` text COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `beschreibung_fr` text COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])',
  `zutrittsberechtigung_von` varchar(75) DEFAULT NULL COMMENT 'Welcher Parlamentarier gab die Zutrittsberechtigung?',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf der Person',
  `beruf_fr` varchar(150) DEFAULT NULL COMMENT 'Französische Bezeichung des Beruf der Person',
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
  `erfasst` enum('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=450 DEFAULT CHARSET=utf8 COMMENT='Lobbyist';

--
-- RELATIONEN DER TABELLE `person`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `partei_id`
--       `partei` -> `id`
--

--
-- Trigger `person`
--
DROP TRIGGER IF EXISTS `trg_person_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_person_log_del_after` AFTER DELETE ON `person`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `person_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_person_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_person_log_del_before` BEFORE DELETE ON `person`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `person` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_person_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_person_log_ins` AFTER INSERT ON `person`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `person` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_person_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_person_log_upd` AFTER UPDATE ON `person`
 FOR EACH ROW thisTrigger: begin

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate authorization from person to his mandate
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
        person_id=NEW.id AND bis IS NULL;
  END IF;

  -- Propagate freigabe from person to his mandate
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
	  -- Person
	  UPDATE `mandat`
	    SET
	    freigabe_datum = NEW.freigabe_datum,
	    freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
	    updated_date = NEW.updated_date,
	    updated_visa = CONCAT(NEW.updated_visa, '*')
	    WHERE
	    person_id=NEW.id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'update', null, NOW(), null FROM `person` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `person_anhang`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `person_anhang`;
CREATE TABLE IF NOT EXISTS `person_anhang` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Zutrittsberechtigunganhangs',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am'
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten';

--
-- RELATIONEN DER TABELLE `person_anhang`:
--   `person_id`
--       `person` -> `id`
--

--
-- Trigger `person_anhang`
--
DROP TRIGGER IF EXISTS `trg_person_anhang_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_person_anhang_log_del_after` AFTER DELETE ON `person_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `person_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_person_anhang_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_person_anhang_log_del_before` BEFORE DELETE ON `person_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `person_anhang` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_person_anhang_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_person_anhang_log_ins` AFTER INSERT ON `person_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `person_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_person_anhang_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_person_anhang_log_upd` AFTER UPDATE ON `person_anhang`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `person_anhang` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `person_anhang_log`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `person_anhang_log`;
CREATE TABLE IF NOT EXISTS `person_anhang_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten';

--
-- RELATIONEN DER TABELLE `person_anhang_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `person_log`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `person_log`;
CREATE TABLE IF NOT EXISTS `person_log` (
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
  `erfasst` enum('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=3057 DEFAULT CHARSET=utf8 COMMENT='Lobbyist';

--
-- RELATIONEN DER TABELLE `person_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rat`
--
-- Erstellt am: 29. Nov 2014 um 05:56
--

DROP TABLE IF EXISTS `rat`;
CREATE TABLE IF NOT EXISTS `rat` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Jahreswerte eines Rates',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Tabelle der Räte von Lobbywatch';

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
-- Erstellt am: 29. Nov 2014 um 05:56
--

DROP TABLE IF EXISTS `rat_log`;
CREATE TABLE IF NOT EXISTS `rat_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='Tabelle der Räte von Lobbywatch';

--
-- RELATIONEN DER TABELLE `rat_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--
-- Erstellt am: 22. Nov 2014 um 10:28
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Settings',
  `key_name` varchar(100) NOT NULL COMMENT 'Schlüsselname der Einstellung. NICHT VERÄNDERN. Wird vom Programm vorgegeben',
  `value` varchar(5000) DEFAULT NULL COMMENT 'Wert der Einstellung. Dieser Wert ist nach den Bedürfnissen anzupassen.',
  `description` text COMMENT 'Hinweise zur Bedeutung dieser Einstellung. Welche Werte sind möglich',
  `category_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Einstellungen zur Lobbywatch-DB';

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
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `settings_category`;
CREATE TABLE IF NOT EXISTS `settings_category` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Settingsateogrie',
  `name` varchar(50) NOT NULL COMMENT 'Name der Settingskategorie',
  `description` text NOT NULL COMMENT 'Beschreibung der Settingskategorie',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Kategorie für Settings';

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
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Kategorie für Settings';

--
-- RELATIONEN DER TABELLE `settings_category_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_log`
--
-- Erstellt am: 22. Nov 2014 um 10:28
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8 COMMENT='Einstellungen zur Lobbywatch-DB';

--
-- RELATIONEN DER TABELLE `settings_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `snapshot`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `snapshot`;
CREATE TABLE IF NOT EXISTS `snapshot` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Snapshots',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Snapshots',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Eintrge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) NOT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Lobbywatch snapshots';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation_language`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `translation_language`;
CREATE TABLE IF NOT EXISTS `translation_language` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel',
  `langcode` varchar(2) NOT NULL COMMENT 'ISO-Code der Sprache',
  `name` int(11) NOT NULL COMMENT 'Name der Sprache'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sprachen von Lobbywatch';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation_source`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `translation_source`;
CREATE TABLE IF NOT EXISTS `translation_source` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel',
  `source` text NOT NULL COMMENT 'Eindeutiger Schlüssel',
  `context` varchar(255) NOT NULL DEFAULT 'Context der Übersetzung',
  `textgroup` varchar(255) NOT NULL DEFAULT 'default' COMMENT 'Gruppe von Übersetzungen, z.B. für ein Modul (see hook_locale())',
  `location` varchar(255) DEFAULT NULL COMMENT 'Ort wo der Text vorkommt, DB-Tabelle o. Programmfunktion',
  `field` varchar(128) DEFAULT NULL COMMENT 'Name of the field',
  `version` varchar(20) DEFAULT NULL COMMENT 'Version of Lobbywatch, where the string was last updated (for translation optimization).',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=377 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';

--
-- Trigger `translation_source`
--
DROP TRIGGER IF EXISTS `trg_translation_source_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_translation_source_log_del_after` AFTER DELETE ON `translation_source`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `translation_source_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_translation_source_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_translation_source_log_del_before` BEFORE DELETE ON `translation_source`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `translation_source` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_translation_source_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_translation_source_log_ins` AFTER INSERT ON `translation_source`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `translation_source` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_translation_source_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_translation_source_log_upd` AFTER UPDATE ON `translation_source`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'update', null, NOW(), null FROM `translation_source` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation_source_log`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `translation_source_log`;
CREATE TABLE IF NOT EXISTS `translation_source_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=377 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';

--
-- RELATIONEN DER TABELLE `translation_source_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation_target`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `translation_target`;
CREATE TABLE IF NOT EXISTS `translation_target` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel',
  `translation_source_id` int(11) NOT NULL COMMENT 'Fremschlüssel auf Übersetzungsquelltext',
  `lang` enum('de','fr') NOT NULL COMMENT 'Sprache des Textes',
  `translation` text NOT NULL COMMENT 'Übersetzter Text; "-", wenn der lange Text genommen wird.',
  `plural_translation_source_id` int(11) DEFAULT NULL,
  `plural` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Plural index number in case of plural strings.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=700 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';

--
-- RELATIONEN DER TABELLE `translation_target`:
--   `plural_translation_source_id`
--       `translation_source` -> `id`
--   `translation_source_id`
--       `translation_source` -> `id`
--

--
-- Trigger `translation_target`
--
DROP TRIGGER IF EXISTS `trg_translation_target_log_del_after`;
DELIMITER //
CREATE TRIGGER `trg_translation_target_log_del_after` AFTER DELETE ON `translation_target`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `translation_target_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_translation_target_log_del_before`;
DELIMITER //
CREATE TRIGGER `trg_translation_target_log_del_before` BEFORE DELETE ON `translation_target`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `translation_target` WHERE id = OLD.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_translation_target_log_ins`;
DELIMITER //
CREATE TRIGGER `trg_translation_target_log_ins` AFTER INSERT ON `translation_target`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `translation_target` WHERE id = NEW.id ;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_translation_target_log_upd`;
DELIMITER //
CREATE TRIGGER `trg_translation_target_log_upd` AFTER UPDATE ON `translation_target`
 FOR EACH ROW thisTrigger: begin
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'update', null, NOW(), null FROM `translation_target` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `translation_target_log`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `translation_target_log`;
CREATE TABLE IF NOT EXISTS `translation_target_log` (
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB AUTO_INCREMENT=700 DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';

--
-- RELATIONEN DER TABELLE `translation_target_log`:
--   `snapshot_id`
--       `snapshot` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel User',
  `name` varchar(10) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `nachname` varchar(100) DEFAULT NULL COMMENT 'Nachname des Benutzers',
  `vorname` varchar(50) DEFAULT NULL COMMENT 'Vorname des Benutzers',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Benutzers',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'Datum des letzten Login',
  `last_access` timestamp NULL DEFAULT NULL COMMENT 'Datum des letzten Zugriffs',
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='PHP Generator users';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_permission`
--
-- Erstellt am: 22. Nov 2014 um 10:27
--

DROP TABLE IF EXISTS `user_permission`;
CREATE TABLE IF NOT EXISTS `user_permission` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel User Persmissions',
  `user_id` int(11) NOT NULL,
  `page_name` varchar(500) DEFAULT NULL,
  `permission_name` varchar(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=utf8 COMMENT='PHP Generator user permissions';

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_branche`
--
DROP VIEW IF EXISTS `v_branche`;
CREATE TABLE IF NOT EXISTS `v_branche` (
`anzeige_name` varchar(100)
,`anzeige_name_de` varchar(100)
,`anzeige_name_fr` varchar(100)
,`id` int(11)
,`name` varchar(100)
,`name_fr` varchar(100)
,`kommission_id` int(11)
,`beschreibung` text
,`beschreibung_fr` text
,`angaben` text
,`angaben_fr` text
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
,`name_de` varchar(100)
,`beschreibung_de` text
,`angaben_de` text
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`kommission` varchar(118)
,`kommission_de` varchar(118)
,`kommission_fr` varchar(118)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_branche_name_with_null`
--
DROP VIEW IF EXISTS `v_branche_name_with_null`;
CREATE TABLE IF NOT EXISTS `v_branche_name_with_null` (
`id` int(11)
,`anzeige_name` varchar(100)
,`anzeige_name_de` varchar(100)
,`anzeige_name_fr` varchar(100)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_branche_simple`
--
DROP VIEW IF EXISTS `v_branche_simple`;
CREATE TABLE IF NOT EXISTS `v_branche_simple` (
`anzeige_name` varchar(100)
,`anzeige_name_de` varchar(100)
,`anzeige_name_fr` varchar(100)
,`id` int(11)
,`name` varchar(100)
,`name_fr` varchar(100)
,`kommission_id` int(11)
,`beschreibung` text
,`beschreibung_fr` text
,`angaben` text
,`angaben_fr` text
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
,`name_de` varchar(100)
,`beschreibung_de` text
,`angaben_de` text
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_country`
--
DROP VIEW IF EXISTS `v_country`;
CREATE TABLE IF NOT EXISTS `v_country` (
`anzeige_name` varchar(200)
,`anzeige_name_de` varchar(200)
,`anzeige_name_fr` varchar(200)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_fraktion`
--
DROP VIEW IF EXISTS `v_fraktion`;
CREATE TABLE IF NOT EXISTS `v_fraktion` (
`anzeige_name` varchar(122)
,`anzeige_name_de` varchar(122)
,`anzeige_name_fr` varchar(122)
,`id` int(11)
,`abkuerzung` varchar(20)
,`name` varchar(100)
,`name_fr` varchar(100)
,`position` enum('links','rechts','mitte')
,`farbcode` varchar(15)
,`beschreibung` text
,`beschreibung_fr` text
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
,`name_de` varchar(100)
,`beschreibung_de` text
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
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
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`parlamentarier_im_rat_seit` date
,`wirksamkeit_index` int(0)
,`organisation_lobbyeinfluss` varchar(9)
,`parlamentarier_lobbyfaktor` bigint(25)
,`refreshed_date` timestamp
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
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`rechtsform` varchar(33)
,`ort` varchar(100)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`beschreibung` varchar(150)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_liste`
--
DROP VIEW IF EXISTS `v_interessenbindung_liste`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_liste` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`name` varchar(454)
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
,`organisation_beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_id` int(11)
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
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`parlamentarier_im_rat_seit` date
,`wirksamkeit_index` int(0)
,`organisation_lobbyeinfluss` varchar(9)
,`parlamentarier_lobbyfaktor` bigint(25)
,`refreshed_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_liste_indirekt`
--
DROP VIEW IF EXISTS `v_interessenbindung_liste_indirekt`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_liste_indirekt` (
`beziehung` varchar(8)
,`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`name` varchar(454)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` varchar(33)
,`typ` varchar(123)
,`vernehmlassung` varchar(9)
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`organisation_beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_id` int(11)
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
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(4)
,`quelle` varchar(80)
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
,`bis_unix` bigint(20)
,`von_unix` bigint(20)
,`created_date_unix` bigint(20)
,`updated_date_unix` bigint(20)
,`eingabe_abgeschlossen_datum_unix` bigint(20)
,`kontrolliert_datum_unix` bigint(20)
,`freigabe_datum_unix` bigint(20)
,`wirksamkeit` varchar(6)
,`parlamentarier_im_rat_seit` date
,`wirksamkeit_index` int(11)
,`organisation_lobbyeinfluss` varchar(9)
,`parlamentarier_lobbyfaktor` bigint(25)
,`refreshed_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_medium_raw`
--
DROP VIEW IF EXISTS `v_interessenbindung_medium_raw`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_medium_raw` (
`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`parlamentarier_im_rat_seit` date
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_raw`
--
DROP VIEW IF EXISTS `v_interessenbindung_raw`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_raw` (
`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`parlamentarier_im_rat_seit` date
,`wirksamkeit_index` int(0)
,`organisation_lobbyeinfluss` varchar(9)
,`parlamentarier_lobbyfaktor` bigint(25)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_simple`
--
DROP VIEW IF EXISTS `v_interessenbindung_simple`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_simple` (
`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessengruppe`
--
DROP VIEW IF EXISTS `v_interessengruppe`;
CREATE TABLE IF NOT EXISTS `v_interessengruppe` (
`anzeige_name` varchar(150)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`id` int(11)
,`name` varchar(150)
,`name_fr` varchar(150)
,`branche_id` int(11)
,`beschreibung` text
,`beschreibung_fr` text
,`alias_namen` varchar(255)
,`alias_namen_fr` varchar(255)
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
,`name_de` varchar(150)
,`beschreibung_de` text
,`alias_namen_de` varchar(255)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`branche` varchar(100)
,`branche_de` varchar(100)
,`branche_fr` varchar(100)
,`kommission_id` int(11)
,`kommission` varchar(118)
,`kommission_de` varchar(118)
,`kommission_fr` varchar(118)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessengruppe_simple`
--
DROP VIEW IF EXISTS `v_interessengruppe_simple`;
CREATE TABLE IF NOT EXISTS `v_interessengruppe_simple` (
`anzeige_name` varchar(150)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`id` int(11)
,`name` varchar(150)
,`name_fr` varchar(150)
,`branche_id` int(11)
,`beschreibung` text
,`beschreibung_fr` text
,`alias_namen` varchar(255)
,`alias_namen_fr` varchar(255)
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
,`name_de` varchar(150)
,`beschreibung_de` text
,`alias_namen_de` varchar(255)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenraum`
--
DROP VIEW IF EXISTS `v_interessenraum`;
CREATE TABLE IF NOT EXISTS `v_interessenraum` (
`anzeige_name` varchar(50)
,`anzeige_name_de` varchar(50)
,`anzeige_name_fr` varchar(50)
,`id` int(11)
,`name` varchar(50)
,`name_fr` varchar(50)
,`beschreibung` text
,`beschreibung_fr` text
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
,`name_de` varchar(50)
,`beschreibung_de` text
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
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
,`funktion` enum('praesident','vizepraesident','mitglied','co-praesident')
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`rat` varchar(10)
,`rat_de` varchar(10)
,`rat_fr` varchar(10)
,`ratstyp` varchar(10)
,`kommission_abkuerzung` varchar(15)
,`kommission_name` varchar(100)
,`kommission_abkuerzung_de` varchar(15)
,`kommission_name_de` varchar(100)
,`kommission_abkuerzung_fr` varchar(15)
,`kommission_name_fr` varchar(100)
,`kommission_art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne')
,`kommission_typ` enum('kommission','subkommission','spezialkommission')
,`kommission_beschreibung` text
,`kommission_sachbereiche` text
,`kommission_mutter_kommission_id` int(11)
,`kommission_parlament_url` varchar(255)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission_liste`
--
DROP VIEW IF EXISTS `v_in_kommission_liste`;
CREATE TABLE IF NOT EXISTS `v_in_kommission_liste` (
`abkuerzung` varchar(15)
,`name` varchar(100)
,`typ` enum('kommission','subkommission','spezialkommission')
,`art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne')
,`beschreibung` text
,`sachbereiche` text
,`mutter_kommission_id` int(11)
,`parlament_url` varchar(255)
,`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` enum('praesident','vizepraesident','mitglied','co-praesident')
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission_parlamentarier`
--
DROP VIEW IF EXISTS `v_in_kommission_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_in_kommission_parlamentarier` (
`parlamentarier_name` varchar(152)
,`name` varchar(202)
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
,`im_rat_seit_unix` bigint(11)
,`im_rat_bis_unix` bigint(11)
,`rat` varchar(10)
,`rat_de` varchar(10)
,`rat_fr` varchar(10)
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`vertretene_bevoelkerung` bigint(13) unsigned
,`kommissionen_namen` text
,`kommissionen_abkuerzung` text
,`partei` varchar(20)
,`partei_de` varchar(20)
,`partei_fr` varchar(20)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
,`militaerischer_grad_de` varchar(30)
,`militaerischer_grad_fr` varchar(30)
,`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` enum('praesident','vizepraesident','mitglied','co-praesident')
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission_simple`
--
DROP VIEW IF EXISTS `v_in_kommission_simple`;
CREATE TABLE IF NOT EXISTS `v_in_kommission_simple` (
`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` enum('praesident','vizepraesident','mitglied','co-praesident')
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kanton`
--
DROP VIEW IF EXISTS `v_kanton`;
CREATE TABLE IF NOT EXISTS `v_kanton` (
`anzeige_name` varchar(50)
,`anzeige_name_de` varchar(50)
,`anzeige_name_fr` varchar(50)
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
,`anzeige_name_de` varchar(50)
,`anzeige_name_fr` varchar(50)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
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
-- Stellvertreter-Struktur des Views `v_kanton_simple`
--
DROP VIEW IF EXISTS `v_kanton_simple`;
CREATE TABLE IF NOT EXISTS `v_kanton_simple` (
`anzeige_name` varchar(50)
,`anzeige_name_de` varchar(50)
,`anzeige_name_fr` varchar(50)
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
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_kommission`
--
DROP VIEW IF EXISTS `v_kommission`;
CREATE TABLE IF NOT EXISTS `v_kommission` (
`anzeige_name` varchar(118)
,`anzeige_name_de` varchar(118)
,`anzeige_name_fr` varchar(118)
,`id` int(11)
,`abkuerzung` varchar(15)
,`abkuerzung_fr` varchar(15)
,`name` varchar(100)
,`name_fr` varchar(100)
,`typ` enum('kommission','subkommission','spezialkommission')
,`art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne')
,`beschreibung` text
,`beschreibung_fr` text
,`sachbereiche` text
,`sachbereiche_fr` text
,`anzahl_nationalraete` tinyint(3) unsigned
,`anzahl_staenderaete` tinyint(3) unsigned
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
,`name_de` varchar(100)
,`abkuerzung_de` varchar(15)
,`beschreibung_de` text
,`sachbereiche_de` text
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
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
-- Stellvertreter-Struktur des Views `v_last_updated_person`
--
DROP VIEW IF EXISTS `v_last_updated_person`;
CREATE TABLE IF NOT EXISTS `v_last_updated_person` (
`table_name` varchar(6)
,`name` varchar(6)
,`anzahl_eintraege` bigint(21)
,`last_visa` varchar(10)
,`last_updated` timestamp
,`last_updated_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_last_updated_person_anhang`
--
DROP VIEW IF EXISTS `v_last_updated_person_anhang`;
CREATE TABLE IF NOT EXISTS `v_last_updated_person_anhang` (
`table_name` varchar(13)
,`name` varchar(14)
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
`table_name` varchar(22)
,`name` varchar(22)
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
`table_name` varchar(22)
,`name` varchar(22)
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
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mandat`
--
DROP VIEW IF EXISTS `v_mandat`;
CREATE TABLE IF NOT EXISTS `v_mandat` (
`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`wirksamkeit_index` int(0)
,`organisation_lobbyeinfluss` varchar(9)
,`refreshed_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mandat_medium_raw`
--
DROP VIEW IF EXISTS `v_mandat_medium_raw`;
CREATE TABLE IF NOT EXISTS `v_mandat_medium_raw` (
`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mandat_raw`
--
DROP VIEW IF EXISTS `v_mandat_raw`;
CREATE TABLE IF NOT EXISTS `v_mandat_raw` (
`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`wirksamkeit_index` int(0)
,`organisation_lobbyeinfluss` varchar(9)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mandat_simple`
--
DROP VIEW IF EXISTS `v_mandat_simple`;
CREATE TABLE IF NOT EXISTS `v_mandat_simple` (
`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mil_grad`
--
DROP VIEW IF EXISTS `v_mil_grad`;
CREATE TABLE IF NOT EXISTS `v_mil_grad` (
`id` int(11)
,`name` varchar(30)
,`name_fr` varchar(30)
,`abkuerzung` varchar(10)
,`abkuerzung_fr` varchar(10)
,`typ` enum('Mannschaft','Unteroffizier','Hoeherer Unteroffizier','Offizier','Hoeherer Stabsoffizier')
,`ranghoehe` int(11)
,`anzeigestufe` int(11)
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`name_de` varchar(30)
,`abkuerzung_de` varchar(10)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation`
--
DROP VIEW IF EXISTS `v_organisation`;
CREATE TABLE IF NOT EXISTS `v_organisation` (
`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`uid` varchar(15)
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
,`beschreibung_fr` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`branche` varchar(100)
,`branche_de` varchar(100)
,`branche_fr` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_de` varchar(150)
,`interessengruppe_fr` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_de` varchar(100)
,`interessengruppe_branche_fr` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_de` varchar(150)
,`interessengruppe2_fr` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_de` varchar(100)
,`interessengruppe2_branche_fr` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_de` varchar(150)
,`interessengruppe3_fr` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_de` varchar(100)
,`interessengruppe3_branche_fr` varchar(100)
,`interessengruppe3_branche_id` int(11)
,`refreshed_date` timestamp
,`land` varchar(200)
,`interessenraum` varchar(50)
,`interessenraum_de` varchar(50)
,`interessenraum_fr` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
,`anzahl_interessenbindung_tief` tinyint(3) unsigned
,`anzahl_interessenbindung_mittel` tinyint(3) unsigned
,`anzahl_interessenbindung_hoch` tinyint(3) unsigned
,`anzahl_interessenbindung_tief_nach_wahl` tinyint(3) unsigned
,`anzahl_interessenbindung_mittel_nach_wahl` tinyint(3) unsigned
,`anzahl_interessenbindung_hoch_nach_wahl` tinyint(3) unsigned
,`anzahl_mandat_tief` tinyint(3) unsigned
,`anzahl_mandat_mittel` tinyint(3) unsigned
,`anzahl_mandat_hoch` tinyint(3) unsigned
,`lobbyeinfluss` varchar(9)
,`lobbyeinfluss_index` int(0)
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
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_arbeitet_fuer`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_arbeitet_fuer` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`von` date
,`bis` date
,`freigabe_datum` timestamp
,`freigabe_datum_unix` bigint(11)
,`id` int(11)
,`name_de` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`ort` varchar(100)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_auftraggeber_fuer`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_auftraggeber_fuer` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`von` date
,`bis` date
,`freigabe_datum` timestamp
,`freigabe_datum_unix` bigint(11)
,`id` int(11)
,`name_de` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`ort` varchar(100)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_mitglieder`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglieder`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_mitglieder` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`von` date
,`bis` date
,`freigabe_datum` timestamp
,`freigabe_datum_unix` bigint(11)
,`id` int(11)
,`name_de` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`ort` varchar(100)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_mitglied_von`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglied_von`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_mitglied_von` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`von` date
,`bis` date
,`freigabe_datum` timestamp
,`freigabe_datum_unix` bigint(11)
,`id` int(11)
,`name_de` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`ort` varchar(100)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_muttergesellschaft`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_muttergesellschaft`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_muttergesellschaft` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`von` date
,`bis` date
,`freigabe_datum` timestamp
,`freigabe_datum_unix` bigint(11)
,`id` int(11)
,`name_de` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`ort` varchar(100)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_tochtergesellschaften`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_tochtergesellschaften` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
,`von` date
,`bis` date
,`freigabe_datum` timestamp
,`freigabe_datum_unix` bigint(11)
,`id` int(11)
,`name_de` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG','Genossenschaft','Staatlich','Ausserparlamentarische Kommission','Einfache Gesellschaft')
,`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`ort` varchar(100)
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
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
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
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
-- Stellvertreter-Struktur des Views `v_organisation_lobbyeinfluss_raw`
--
DROP VIEW IF EXISTS `v_organisation_lobbyeinfluss_raw`;
CREATE TABLE IF NOT EXISTS `v_organisation_lobbyeinfluss_raw` (
`id` int(11)
,`anzahl_interessenbindung_tief` bigint(21)
,`anzahl_interessenbindung_mittel` bigint(21)
,`anzahl_interessenbindung_hoch` bigint(21)
,`anzahl_interessenbindung_tief_nach_wahl` bigint(21)
,`anzahl_interessenbindung_mittel_nach_wahl` bigint(21)
,`anzahl_interessenbindung_hoch_nach_wahl` bigint(21)
,`anzahl_mandat_tief` bigint(21)
,`anzahl_mandat_mittel` bigint(21)
,`anzahl_mandat_hoch` bigint(21)
,`lobbyeinfluss` varchar(9)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_medium_raw`
--
DROP VIEW IF EXISTS `v_organisation_medium_raw`;
CREATE TABLE IF NOT EXISTS `v_organisation_medium_raw` (
`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`uid` varchar(15)
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
,`beschreibung_fr` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`branche` varchar(100)
,`branche_de` varchar(100)
,`branche_fr` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_de` varchar(150)
,`interessengruppe_fr` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_de` varchar(100)
,`interessengruppe_branche_fr` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_de` varchar(150)
,`interessengruppe2_fr` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_de` varchar(100)
,`interessengruppe2_branche_fr` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_de` varchar(150)
,`interessengruppe3_fr` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_de` varchar(100)
,`interessengruppe3_branche_fr` varchar(100)
,`interessengruppe3_branche_id` int(11)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier` (
`parlamentarier_name` varchar(152)
,`name` varchar(202)
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
,`im_rat_seit_unix` bigint(11)
,`im_rat_bis_unix` bigint(11)
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`vertretene_bevoelkerung` bigint(13) unsigned
,`kommissionen_namen` text
,`kommissionen_abkuerzung` text
,`partei` varchar(20)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`deklarationstyp` enum('deklarationspflichtig','nicht deklarationspflicht')
,`status` enum('deklariert','nicht-deklariert')
,`behoerden_vertreter` enum('J','N')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
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
,`person_id` int(11)
,`zutrittsberechtigter` varchar(152)
,`art` varchar(18)
,`von` date
,`bis` date
,`organisation_id` int(11)
,`freigabe_datum` timestamp
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
,`person_id` int(11)
,`zutrittsberechtigter` varchar(152)
,`art` varchar(18)
,`von` date
,`bis` date
,`zwischenorganisation_id` int(11)
,`connector_organisation_id` int(11)
,`freigabe_datum` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier_indirekt`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier_indirekt`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier_indirekt` (
`beziehung` varchar(8)
,`parlamentarier_name` varchar(152)
,`name` varchar(202)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`rat_id` int(11)
,`kanton_id` int(11)
,`kommissionen` varchar(75)
,`partei_id` int(11)
,`parteifunktion` varchar(14)
,`fraktion_id` int(11)
,`fraktionsfunktion` varchar(14)
,`im_rat_seit` date
,`im_rat_bis` date
,`ratswechsel` date
,`ratsunterbruch_von` date
,`ratsunterbruch_bis` date
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`zivilstand` varchar(26)
,`anzahl_kinder` tinyint(4) unsigned
,`militaerischer_grad_id` int(11)
,`geschlecht` varchar(1)
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
,`arbeitssprache` varchar(2)
,`adresse_firma` varchar(100)
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`adresse_ort` varchar(100)
,`im_rat_seit_unix` bigint(20)
,`im_rat_bis_unix` bigint(20)
,`kanton` varchar(2)
,`vertretene_bevoelkerung` bigint(20) unsigned
,`kommissionen_namen` text
,`kommissionen_abkuerzung` text
,`partei` varchar(20)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
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
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(4)
,`quelle` varchar(80)
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
,`bis_unix` bigint(20)
,`von_unix` bigint(20)
,`created_date_unix` bigint(20)
,`updated_date_unix` bigint(20)
,`eingabe_abgeschlossen_datum_unix` bigint(20)
,`kontrolliert_datum_unix` bigint(20)
,`freigabe_datum_unix` bigint(20)
,`connector_organisation_id` int(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_raw`
--
DROP VIEW IF EXISTS `v_organisation_raw`;
CREATE TABLE IF NOT EXISTS `v_organisation_raw` (
`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`uid` varchar(15)
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
,`beschreibung_fr` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`branche` varchar(100)
,`branche_de` varchar(100)
,`branche_fr` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_de` varchar(150)
,`interessengruppe_fr` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_de` varchar(100)
,`interessengruppe_branche_fr` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_de` varchar(150)
,`interessengruppe2_fr` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_de` varchar(100)
,`interessengruppe2_branche_fr` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_de` varchar(150)
,`interessengruppe3_fr` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_de` varchar(100)
,`interessengruppe3_branche_fr` varchar(100)
,`interessengruppe3_branche_id` int(11)
,`refreshed_date` datetime
,`land` varchar(200)
,`interessenraum` varchar(50)
,`interessenraum_de` varchar(50)
,`interessenraum_fr` varchar(50)
,`organisation_jahr_id` int(11)
,`jahr` smallint(6) unsigned
,`umsatz` bigint(20)
,`gewinn` bigint(20)
,`kapital` bigint(20) unsigned
,`mitarbeiter_weltweit` int(11) unsigned
,`mitarbeiter_schweiz` int(11) unsigned
,`geschaeftsbericht_url` varchar(255)
,`quelle_url` varchar(255)
,`anzahl_interessenbindung_tief` bigint(21)
,`anzahl_interessenbindung_mittel` bigint(21)
,`anzahl_interessenbindung_hoch` bigint(21)
,`anzahl_interessenbindung_tief_nach_wahl` bigint(21)
,`anzahl_interessenbindung_mittel_nach_wahl` bigint(21)
,`anzahl_interessenbindung_hoch_nach_wahl` bigint(21)
,`anzahl_mandat_tief` bigint(21)
,`anzahl_mandat_mittel` bigint(21)
,`anzahl_mandat_hoch` bigint(21)
,`lobbyeinfluss` varchar(9)
,`lobbyeinfluss_index` int(0)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_simple`
--
DROP VIEW IF EXISTS `v_organisation_simple`;
CREATE TABLE IF NOT EXISTS `v_organisation_simple` (
`anzeige_name` varchar(454)
,`anzeige_name_de` varchar(150)
,`anzeige_name_fr` varchar(150)
,`name` varchar(454)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`uid` varchar(15)
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
,`beschreibung_fr` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_zutrittsberechtigung`
--
DROP VIEW IF EXISTS `v_organisation_zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `v_organisation_zutrittsberechtigung` (
`anzeige_name` varchar(152)
,`zutrittsberechtigung_name` varchar(152)
,`name` varchar(151)
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
,`partei` varchar(20)
,`parlamentarier_name` varchar(152)
,`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier`
--
DROP VIEW IF EXISTS `v_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(202)
,`name_de` varchar(202)
,`name_fr` varchar(202)
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
,`beruf_fr` varchar(150)
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
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
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
,`von` date
,`bis` date
,`geburtstag_unix` bigint(11)
,`im_rat_seit_unix` bigint(11)
,`im_rat_bis_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`von_unix` bigint(11)
,`bis_unix` bigint(11)
,`vertretene_bevoelkerung` bigint(13) unsigned
,`rat` varchar(10)
,`ratstyp_BAD` varchar(10)
,`kanton_abkuerzung_BAD` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`rat_de` varchar(10)
,`kanton_name_de` varchar(50)
,`rat_fr` varchar(10)
,`kanton_name_fr` varchar(50)
,`kommissionen_namen` text
,`kommissionen_namen_de` text
,`kommissionen_namen_fr` text
,`kommissionen_abkuerzung` text
,`kommissionen_abkuerzung_de` text
,`kommissionen_abkuerzung_fr` text
,`kommissionen_anzahl` bigint(21)
,`partei` varchar(20)
,`partei_name` varchar(100)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
,`partei_de` varchar(20)
,`partei_name_de` varchar(100)
,`militaerischer_grad_de` varchar(30)
,`partei_fr` varchar(20)
,`partei_name_fr` varchar(100)
,`militaerischer_grad_fr` varchar(30)
,`beruf_branche_id` int(11)
,`titel_de` varchar(100)
,`titel_fr` varchar(100)
,`refreshed_date` timestamp
,`anzahl_interessenbindung_tief` tinyint(3) unsigned
,`anzahl_interessenbindung_mittel` tinyint(3) unsigned
,`anzahl_interessenbindung_hoch` tinyint(3) unsigned
,`anzahl_interessenbindung_tief_nach_wahl` tinyint(3) unsigned
,`anzahl_interessenbindung_mittel_nach_wahl` tinyint(3) unsigned
,`anzahl_interessenbindung_hoch_nach_wahl` tinyint(3) unsigned
,`lobbyfaktor` smallint(5) unsigned
,`lobbyfaktor_max` smallint(5) unsigned
,`lobbyfaktor_percent_max` decimal(4,3) unsigned
,`anzahl_interessenbindung_tief_max` tinyint(3) unsigned
,`anzahl_interessenbindung_mittel_max` tinyint(3) unsigned
,`anzahl_interessenbindung_hoch_max` tinyint(3) unsigned
,`ratstyp` varchar(10)
,`kanton_abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
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
-- Stellvertreter-Struktur des Views `v_parlamentarier_lobbyfaktor_max_raw`
--
DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor_max_raw`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_lobbyfaktor_max_raw` (
`id` int(1)
,`anzahl_interessenbindung_tief_max` bigint(21)
,`anzahl_interessenbindung_mittel_max` bigint(21)
,`anzahl_interessenbindung_hoch_max` bigint(21)
,`lobbyfaktor_max` bigint(25)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_lobbyfaktor_raw`
--
DROP VIEW IF EXISTS `v_parlamentarier_lobbyfaktor_raw`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_lobbyfaktor_raw` (
`id` int(11)
,`anzahl_interessenbindung_tief` bigint(21)
,`anzahl_interessenbindung_mittel` bigint(21)
,`anzahl_interessenbindung_hoch` bigint(21)
,`anzahl_interessenbindung_tief_nach_wahl` bigint(21)
,`anzahl_interessenbindung_mittel_nach_wahl` bigint(21)
,`anzahl_interessenbindung_hoch_nach_wahl` bigint(21)
,`lobbyfaktor` bigint(25)
,`lobbyfaktor_einfach` bigint(24)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_medium_raw`
--
DROP VIEW IF EXISTS `v_parlamentarier_medium_raw`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_medium_raw` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(202)
,`name_de` varchar(202)
,`name_fr` varchar(202)
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
,`beruf_fr` varchar(150)
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
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
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
,`von` date
,`bis` date
,`geburtstag_unix` bigint(11)
,`im_rat_seit_unix` bigint(11)
,`im_rat_bis_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`von_unix` bigint(11)
,`bis_unix` bigint(11)
,`vertretene_bevoelkerung` bigint(13) unsigned
,`rat` varchar(10)
,`ratstyp` varchar(10)
,`kanton_abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`rat_de` varchar(10)
,`kanton_name_de` varchar(50)
,`rat_fr` varchar(10)
,`kanton_name_fr` varchar(50)
,`kommissionen_namen` text
,`kommissionen_namen_de` text
,`kommissionen_namen_fr` text
,`kommissionen_abkuerzung` text
,`kommissionen_abkuerzung_de` text
,`kommissionen_abkuerzung_fr` text
,`kommissionen_anzahl` bigint(21)
,`partei` varchar(20)
,`partei_name` varchar(100)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
,`partei_de` varchar(20)
,`partei_name_de` varchar(100)
,`militaerischer_grad_de` varchar(30)
,`partei_fr` varchar(20)
,`partei_name_fr` varchar(100)
,`militaerischer_grad_fr` varchar(30)
,`beruf_branche_id` int(11)
,`titel_de` varchar(100)
,`titel_fr` varchar(100)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_raw`
--
DROP VIEW IF EXISTS `v_parlamentarier_raw`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_raw` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(202)
,`name_de` varchar(202)
,`name_fr` varchar(202)
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
,`beruf_fr` varchar(150)
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
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
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
,`von` date
,`bis` date
,`geburtstag_unix` bigint(11)
,`im_rat_seit_unix` bigint(11)
,`im_rat_bis_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`von_unix` bigint(11)
,`bis_unix` bigint(11)
,`vertretene_bevoelkerung` bigint(13) unsigned
,`rat` varchar(10)
,`ratstyp` varchar(10)
,`kanton_abkuerzung` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`rat_de` varchar(10)
,`kanton_name_de` varchar(50)
,`rat_fr` varchar(10)
,`kanton_name_fr` varchar(50)
,`kommissionen_namen` text
,`kommissionen_namen_de` text
,`kommissionen_namen_fr` text
,`kommissionen_abkuerzung` text
,`kommissionen_abkuerzung_de` text
,`kommissionen_abkuerzung_fr` text
,`kommissionen_anzahl` bigint(21)
,`partei` varchar(20)
,`partei_name` varchar(100)
,`fraktion` varchar(20)
,`militaerischer_grad` varchar(30)
,`partei_de` varchar(20)
,`partei_name_de` varchar(100)
,`militaerischer_grad_de` varchar(30)
,`partei_fr` varchar(20)
,`partei_name_fr` varchar(100)
,`militaerischer_grad_fr` varchar(30)
,`beruf_branche_id` int(11)
,`titel_de` varchar(100)
,`titel_fr` varchar(100)
,`refreshed_date` datetime
,`anzahl_interessenbindung_tief` bigint(21)
,`anzahl_interessenbindung_mittel` bigint(21)
,`anzahl_interessenbindung_hoch` bigint(21)
,`anzahl_interessenbindung_tief_nach_wahl` bigint(21)
,`anzahl_interessenbindung_mittel_nach_wahl` bigint(21)
,`anzahl_interessenbindung_hoch_nach_wahl` bigint(21)
,`lobbyfaktor` bigint(25)
,`lobbyfaktor_max` bigint(25)
,`lobbyfaktor_percent_max` decimal(28,4)
,`anzahl_interessenbindung_tief_max` bigint(21)
,`anzahl_interessenbindung_mittel_max` bigint(21)
,`anzahl_interessenbindung_hoch_max` bigint(21)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_simple`
--
DROP VIEW IF EXISTS `v_parlamentarier_simple`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_simple` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(202)
,`name_de` varchar(202)
,`name_fr` varchar(202)
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
,`beruf_fr` varchar(150)
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
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
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
,`von` date
,`bis` date
,`geburtstag_unix` bigint(11)
,`im_rat_seit_unix` bigint(11)
,`im_rat_bis_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`von_unix` bigint(11)
,`bis_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_partei`
--
DROP VIEW IF EXISTS `v_partei`;
CREATE TABLE IF NOT EXISTS `v_partei` (
`anzeige_name` varchar(123)
,`anzeige_name_de` varchar(123)
,`anzeige_name_fr` varchar(123)
,`id` int(11)
,`abkuerzung` varchar(20)
,`abkuerzung_fr` varchar(20)
,`name` varchar(100)
,`name_fr` varchar(100)
,`fraktion_id` int(11)
,`gruendung` date
,`position` enum('links','rechts','mitte')
,`farbcode` varchar(15)
,`homepage` varchar(255)
,`homepage_fr` varchar(255)
,`email` varchar(100)
,`email_fr` varchar(100)
,`twitter_name` varchar(50)
,`twitter_name_fr` varchar(50)
,`beschreibung` text
,`beschreibung_fr` text
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
,`name_de` varchar(100)
,`abkuerzung_de` varchar(20)
,`beschreibung_de` text
,`homepage_de` varchar(255)
,`twitter_name_de` varchar(50)
,`email_de` varchar(100)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_person`
--
DROP VIEW IF EXISTS `v_person`;
CREATE TABLE IF NOT EXISTS `v_person` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(151)
,`name_de` varchar(151)
,`name_fr` varchar(151)
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`beschreibung_de` text
,`beschreibung_fr` text
,`parlamentarier_kommissionen` varchar(75)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`partei_id` int(11)
,`geschlecht` enum('M','F')
,`arbeitssprache` enum('de','fr','it')
,`email` varchar(100)
,`homepage` varchar(255)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_person_anhang`
--
DROP VIEW IF EXISTS `v_person_anhang`;
CREATE TABLE IF NOT EXISTS `v_person_anhang` (
`person_id2` int(11)
,`id` int(11)
,`person_id` int(11)
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
-- Stellvertreter-Struktur des Views `v_person_simple`
--
DROP VIEW IF EXISTS `v_person_simple`;
CREATE TABLE IF NOT EXISTS `v_person_simple` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(151)
,`name_de` varchar(151)
,`name_fr` varchar(151)
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`beschreibung_de` text
,`beschreibung_fr` text
,`parlamentarier_kommissionen` varchar(75)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`partei_id` int(11)
,`geschlecht` enum('M','F')
,`arbeitssprache` enum('de','fr','it')
,`email` varchar(100)
,`homepage` varchar(255)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_rat`
--
DROP VIEW IF EXISTS `v_rat`;
CREATE TABLE IF NOT EXISTS `v_rat` (
`anzeige_name` varchar(50)
,`anzeige_name_de` varchar(50)
,`anzeige_name_fr` varchar(50)
,`id` int(11)
,`abkuerzung` varchar(10)
,`abkuerzung_fr` varchar(10)
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
,`mitglied_bezeichnung_maennlich_de` varchar(50)
,`mitglied_bezeichnung_weiblich_de` varchar(50)
,`mitglied_bezeichnung_maennlich_fr` varchar(50)
,`mitglied_bezeichnung_weiblich_fr` varchar(50)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_search_table`
--
DROP VIEW IF EXISTS `v_search_table`;
CREATE TABLE IF NOT EXISTS `v_search_table` (
`id` int(11)
,`table_name` varchar(20)
,`page` varchar(20)
,`table_weight` bigint(20)
,`name_de` varchar(190)
,`name_fr` varchar(190)
,`search_keywords_de` varchar(509)
,`search_keywords_fr` varchar(509)
,`freigabe_datum` timestamp
,`bis` date
,`weight` bigint(20)
,`refreshed_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_search_table_raw`
--
DROP VIEW IF EXISTS `v_search_table_raw`;
CREATE TABLE IF NOT EXISTS `v_search_table_raw` (
`id` int(11)
,`table_name` varchar(20)
,`page` varchar(20)
,`table_weight` bigint(20)
,`name_de` varchar(190)
,`name_fr` varchar(190)
,`search_keywords_de` varchar(509)
,`search_keywords_fr` varchar(509)
,`freigabe_datum` timestamp
,`bis` date
,`weight` bigint(20)
,`refreshed_date` datetime
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
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
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_user`
--
DROP VIEW IF EXISTS `v_user`;
CREATE TABLE IF NOT EXISTS `v_user` (
`anzeige_name` varchar(151)
,`username` varchar(10)
,`id` int(11)
,`name` varchar(10)
,`password` varchar(255)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`email` varchar(100)
,`last_login` timestamp
,`last_access` timestamp
,`farbcode` varchar(15)
,`notizen` text
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
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
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(151)
,`name_de` varchar(151)
,`name_fr` varchar(151)
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`beschreibung_de` text
,`beschreibung_fr` text
,`parlamentarier_kommissionen` varchar(75)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`partei_id` int(11)
,`geschlecht` enum('M','F')
,`arbeitssprache` enum('de','fr','it')
,`email` varchar(100)
,`homepage` varchar(255)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
,`notizen` text
,`autorisierung_verschickt_visa` varchar(10)
,`autorisierung_verschickt_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`parlamentarier_id` int(11)
,`person_id` int(11)
,`zutrittsberechtigung_id` int(11)
,`funktion` varchar(150)
,`funktion_fr` varchar(150)
,`von` date
,`bis` date
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`beruf_branche_id` int(11)
,`partei` varchar(20)
,`partei_de` varchar(20)
,`partei_fr` varchar(20)
,`parlamentarier_name` varchar(152)
,`parlamentarier_freigabe_datum` timestamp
,`parlamentarier_freigabe_datum_unix` bigint(11)
,`anzahl_mandat_tief` tinyint(3) unsigned
,`anzahl_mandat_mittel` tinyint(3) unsigned
,`anzahl_mandat_hoch` tinyint(3) unsigned
,`lobbyfaktor` smallint(5) unsigned
,`lobbyfaktor_max` smallint(5) unsigned
,`lobbyfaktor_percent_max` decimal(4,3) unsigned
,`anzahl_mandat_tief_max` tinyint(3) unsigned
,`anzahl_mandat_mittel_max` tinyint(3) unsigned
,`anzahl_mandat_hoch_max` tinyint(3) unsigned
,`refreshed_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_anhang`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_anhang`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_anhang` (
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_lobbyfaktor_max_raw`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_max_raw`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_lobbyfaktor_max_raw` (
`id` int(1)
,`anzahl_mandat_tief_max` bigint(21)
,`anzahl_mandat_mittel_max` bigint(21)
,`anzahl_mandat_hoch_max` bigint(21)
,`lobbyfaktor_max` bigint(24)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_lobbyfaktor_raw`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_raw`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_lobbyfaktor_raw` (
`person_id` int(11)
,`anzahl_mandat_tief` bigint(21)
,`anzahl_mandat_mittel` bigint(21)
,`anzahl_mandat_hoch` bigint(21)
,`lobbyfaktor` bigint(24)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_mandate`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mandate`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_mandate` (
`parlamentarier_id` int(11)
,`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`name` varchar(454)
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
,`organisation_beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_id` int(11)
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
,`zutrittsberechtigung_name` varchar(152)
,`funktion` varchar(150)
,`funktion_fr` varchar(150)
,`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`wirksamkeit` varchar(6)
,`wirksamkeit_index` int(0)
,`organisation_lobbyeinfluss` varchar(9)
,`refreshed_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_mit_mandaten`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_mit_mandaten` (
`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`name` varchar(454)
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
,`organisation_beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_id` int(11)
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
,`zutrittsberechtigung_name` varchar(152)
,`funktion` varchar(150)
,`parlamentarier_id` int(11)
,`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat','finanziell','gesellschafter')
,`funktion_im_gremium` enum('praesident','vizepraesident','mitglied')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(1)
,`quelle` varchar(80)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_mit_mandaten_indirekt`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt` (
`beziehung` varchar(8)
,`organisation_name` varchar(454)
,`organisation_name_de` varchar(150)
,`organisation_name_fr` varchar(150)
,`name` varchar(454)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(100)
,`land_id` int(11)
,`interessenraum_id` int(11)
,`rechtsform` varchar(33)
,`typ` varchar(123)
,`vernehmlassung` varchar(9)
,`interessengruppe_id` int(11)
,`interessengruppe2_id` int(11)
,`interessengruppe3_id` int(11)
,`branche_id` int(11)
,`homepage` varchar(255)
,`handelsregister_url` varchar(255)
,`twitter_name` varchar(50)
,`organisation_beschreibung` text
,`adresse_strasse` varchar(100)
,`adresse_zusatz` varchar(100)
,`adresse_plz` varchar(10)
,`branche` varchar(100)
,`interessengruppe` varchar(150)
,`interessengruppe_branche` varchar(100)
,`interessengruppe_branche_id` int(11)
,`interessengruppe2` varchar(150)
,`interessengruppe2_branche` varchar(100)
,`interessengruppe2_branche_id` int(11)
,`interessengruppe3` varchar(150)
,`interessengruppe3_branche` varchar(100)
,`interessengruppe3_branche_id` int(11)
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
,`zutrittsberechtigung_name` varchar(152)
,`funktion` varchar(150)
,`parlamentarier_id` int(11)
,`id` int(11)
,`person_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`funktion_im_gremium` varchar(14)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`quelle_url` varchar(255)
,`quelle_url_gueltig` tinyint(4)
,`quelle` varchar(80)
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
,`bis_unix` bigint(20)
,`von_unix` bigint(20)
,`created_date_unix` bigint(20)
,`updated_date_unix` bigint(20)
,`eingabe_abgeschlossen_datum_unix` bigint(20)
,`kontrolliert_datum_unix` bigint(20)
,`freigabe_datum_unix` bigint(20)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_raw`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_raw`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_raw` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(151)
,`name_de` varchar(151)
,`name_fr` varchar(151)
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`beschreibung_de` text
,`beschreibung_fr` text
,`parlamentarier_kommissionen` varchar(75)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`partei_id` int(11)
,`geschlecht` enum('M','F')
,`arbeitssprache` enum('de','fr','it')
,`email` varchar(100)
,`homepage` varchar(255)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
,`notizen` text
,`autorisierung_verschickt_visa` varchar(10)
,`autorisierung_verschickt_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`parlamentarier_id` int(11)
,`person_id` int(11)
,`zutrittsberechtigung_id` int(11)
,`funktion` varchar(150)
,`funktion_fr` varchar(150)
,`von` date
,`bis` date
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
,`beruf_branche_id` int(11)
,`partei` varchar(20)
,`partei_de` varchar(20)
,`partei_fr` varchar(20)
,`parlamentarier_name` varchar(152)
,`parlamentarier_freigabe_datum` timestamp
,`parlamentarier_freigabe_datum_unix` bigint(11)
,`anzahl_mandat_tief` bigint(21)
,`anzahl_mandat_mittel` bigint(21)
,`anzahl_mandat_hoch` bigint(21)
,`lobbyfaktor` bigint(24)
,`lobbyfaktor_max` bigint(24)
,`lobbyfaktor_percent_max` decimal(27,4)
,`anzahl_mandat_tief_max` bigint(21)
,`anzahl_mandat_mittel_max` bigint(21)
,`anzahl_mandat_hoch_max` bigint(21)
,`refreshed_date` datetime
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_simple`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_simple`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_simple` (
`id` int(11)
,`parlamentarier_id` int(11)
,`person_id` int(11)
,`parlamentarier_kommissionen` varchar(75)
,`funktion` varchar(150)
,`funktion_fr` varchar(150)
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zutrittsberechtigung_simple_compat`
--
DROP VIEW IF EXISTS `v_zutrittsberechtigung_simple_compat`;
CREATE TABLE IF NOT EXISTS `v_zutrittsberechtigung_simple_compat` (
`anzeige_name` varchar(152)
,`anzeige_name_de` varchar(152)
,`anzeige_name_fr` varchar(152)
,`name` varchar(151)
,`name_de` varchar(151)
,`name_fr` varchar(151)
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`beschreibung_de` text
,`beschreibung_fr` text
,`parlamentarier_kommissionen` varchar(75)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`partei_id` int(11)
,`geschlecht` enum('M','F')
,`arbeitssprache` enum('de','fr','it')
,`email` varchar(100)
,`homepage` varchar(255)
,`twitter_name` varchar(50)
,`linkedin_profil_url` varchar(255)
,`xing_profil_name` varchar(150)
,`facebook_name` varchar(150)
,`telephon_1` varchar(25)
,`telephon_2` varchar(25)
,`erfasst` enum('Ja','Nein')
,`notizen` text
,`autorisierung_verschickt_visa` varchar(10)
,`autorisierung_verschickt_datum` timestamp
,`autorisiert_visa` varchar(10)
,`autorisiert_datum` date
,`parlamentarier_id` int(11)
,`person_id` int(11)
,`zutrittsberechtigung_id` int(11)
,`funktion` varchar(150)
,`funktion_fr` varchar(150)
,`von` date
,`bis` date
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
,`bis_unix` bigint(11)
,`von_unix` bigint(11)
,`created_date_unix` bigint(11)
,`updated_date_unix` bigint(11)
,`eingabe_abgeschlossen_datum_unix` bigint(11)
,`kontrolliert_datum_unix` bigint(11)
,`freigabe_datum_unix` bigint(11)
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung`
--
-- Erstellt am: 08. Dez 2014 um 09:15
--

DROP TABLE IF EXISTS `zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung` (
`id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Zutrittsberechtigung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur zutrittsberechtigten Person',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `funktion_fr` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person auf französisch.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zutrittsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zutrittsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'
) ENGINE=InnoDB AUTO_INCREMENT=440 DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")';

--
-- RELATIONEN DER TABELLE `zutrittsberechtigung`:
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--   `person_id`
--       `person` -> `id`
--

--
-- Trigger `zutrittsberechtigung`
--
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_before_ins`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_before_ins` BEFORE INSERT ON `zutrittsberechtigung`
 FOR EACH ROW thisTrigger: begin
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Fill parlamentarier.kommissionen on change
  -- Other triggers are trg_in_kommission_log_* trigger
  SET NEW.parlamentarier_kommissionen = (SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id = NEW.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id);

  -- propagate parlamentarier_kommissionen to person
   UPDATE person
	SET
	parlamentarier_kommissionen = NEW.parlamentarier_kommissionen,
	zutrittsberechtigung_von = (SELECT parlamentarier.anzeige_name FROM v_parlamentarier_simple parlamentarier WHERE parlamentarier.id = NEW.parlamentarier_id)
	WHERE person.id = NEW.person_id AND (NEW.bis IS NULL OR NEW.bis > NOW());
   UPDATE person
	SET
	parlamentarier_kommissionen = NULL,
	zutrittsberechtigung_von = NULL
	WHERE person.id = NEW.person_id AND (NEW.bis < NOW());
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_before_upd`;
DELIMITER //
CREATE TRIGGER `trg_zutrittsberechtigung_before_upd` BEFORE UPDATE ON `zutrittsberechtigung`
 FOR EACH ROW thisTrigger: begin

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Fill parlamentarier.kommissionen on change
  -- Other triggers are trg_in_kommission_log_* trigger
  SET NEW.parlamentarier_kommissionen = (SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id = NEW.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id);

  -- propagate parlamentarier_kommissionen to person
   UPDATE person
	SET
	parlamentarier_kommissionen = NEW.parlamentarier_kommissionen,
	zutrittsberechtigung_von = (SELECT parlamentarier.anzeige_name FROM v_parlamentarier_simple parlamentarier WHERE parlamentarier.id = NEW.parlamentarier_id)
	WHERE person.id = NEW.person_id AND (NEW.bis IS NULL OR NEW.bis > NOW());
   UPDATE person
	SET
	parlamentarier_kommissionen = NULL,
	zutrittsberechtigung_von = NULL
	WHERE person.id = NEW.person_id AND (NEW.bis < NOW());
end
//
DELIMITER ;
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
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = OLD.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- propagate parlamentarier_kommissionen to person
   UPDATE person
	SET
	parlamentarier_kommissionen = NULL,
	zutrittsberechtigung_von = NULL
	WHERE person.id = OLD.person_id;
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

  -- Propagate freigabe from zutrittsberechtigung to person
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
	  -- Person
	  UPDATE `person`
	    SET
	    freigabe_datum = NEW.freigabe_datum,
	    freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
	    updated_date = NEW.updated_date,
	    updated_visa = CONCAT(NEW.updated_visa, '*')
	    WHERE
	    person.id=NEW.person_id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung_log`
--
-- Erstellt am: 13. Dez 2014 um 06:30
--

DROP TABLE IF EXISTS `zutrittsberechtigung_log`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_log` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `person_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zur zutrittsberechtigten Person',
  `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person.',
  `funktion_fr` varchar(150) DEFAULT NULL COMMENT 'Funktion der zutrittsberechtigen Person auf französisch.',
  `von` date DEFAULT NULL COMMENT 'Beginn der Zutrittsberechtigung, leer (NULL) = unbekannt',
  `bis` date DEFAULT NULL COMMENT 'Ende der Zutrittsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag',
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
`log_id` int(11) NOT NULL COMMENT 'Technischer Log-Schlüssel',
  `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")';

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branche` AS select `branche`.`anzeige_name` AS `anzeige_name`,`branche`.`anzeige_name_de` AS `anzeige_name_de`,`branche`.`anzeige_name_fr` AS `anzeige_name_fr`,`branche`.`id` AS `id`,`branche`.`name` AS `name`,`branche`.`name_fr` AS `name_fr`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`beschreibung` AS `beschreibung`,`branche`.`beschreibung_fr` AS `beschreibung_fr`,`branche`.`angaben` AS `angaben`,`branche`.`angaben_fr` AS `angaben_fr`,`branche`.`farbcode` AS `farbcode`,`branche`.`symbol_abs` AS `symbol_abs`,`branche`.`symbol_rel` AS `symbol_rel`,`branche`.`symbol_klein_rel` AS `symbol_klein_rel`,`branche`.`symbol_dateiname_wo_ext` AS `symbol_dateiname_wo_ext`,`branche`.`symbol_dateierweiterung` AS `symbol_dateierweiterung`,`branche`.`symbol_dateiname` AS `symbol_dateiname`,`branche`.`symbol_mime_type` AS `symbol_mime_type`,`branche`.`notizen` AS `notizen`,`branche`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`branche`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`branche`.`kontrolliert_visa` AS `kontrolliert_visa`,`branche`.`kontrolliert_datum` AS `kontrolliert_datum`,`branche`.`freigabe_visa` AS `freigabe_visa`,`branche`.`freigabe_datum` AS `freigabe_datum`,`branche`.`created_visa` AS `created_visa`,`branche`.`created_date` AS `created_date`,`branche`.`updated_visa` AS `updated_visa`,`branche`.`updated_date` AS `updated_date`,`branche`.`name_de` AS `name_de`,`branche`.`beschreibung_de` AS `beschreibung_de`,`branche`.`angaben_de` AS `angaben_de`,`branche`.`created_date_unix` AS `created_date_unix`,`branche`.`updated_date_unix` AS `updated_date_unix`,`branche`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`branche`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`branche`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`kommission`.`anzeige_name` AS `kommission`,`kommission`.`anzeige_name_de` AS `kommission_de`,`kommission`.`anzeige_name_fr` AS `kommission_fr` from (`v_branche_simple` `branche` left join `v_kommission` `kommission` on((`kommission`.`id` = `branche`.`kommission_id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_branche_name_with_null`
--
DROP TABLE IF EXISTS `v_branche_name_with_null`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branche_name_with_null` AS select `branche`.`id` AS `id`,concat(`branche`.`name`) AS `anzeige_name`,concat(`branche`.`name`) AS `anzeige_name_de`,concat(`branche`.`name_fr`) AS `anzeige_name_fr` from `branche` union select NULL AS `ID`,'NULL' AS `anzeige_name`,'NULL' AS `anzeige_name_de`,'NULL' AS `anzeige_name_fr`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_branche_simple`
--
DROP TABLE IF EXISTS `v_branche_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branche_simple` AS select concat(`branche`.`name`) AS `anzeige_name`,concat(`branche`.`name`) AS `anzeige_name_de`,concat(`branche`.`name_fr`) AS `anzeige_name_fr`,`branche`.`id` AS `id`,`branche`.`name` AS `name`,`branche`.`name_fr` AS `name_fr`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`beschreibung` AS `beschreibung`,`branche`.`beschreibung_fr` AS `beschreibung_fr`,`branche`.`angaben` AS `angaben`,`branche`.`angaben_fr` AS `angaben_fr`,`branche`.`farbcode` AS `farbcode`,`branche`.`symbol_abs` AS `symbol_abs`,`branche`.`symbol_rel` AS `symbol_rel`,`branche`.`symbol_klein_rel` AS `symbol_klein_rel`,`branche`.`symbol_dateiname_wo_ext` AS `symbol_dateiname_wo_ext`,`branche`.`symbol_dateierweiterung` AS `symbol_dateierweiterung`,`branche`.`symbol_dateiname` AS `symbol_dateiname`,`branche`.`symbol_mime_type` AS `symbol_mime_type`,`branche`.`notizen` AS `notizen`,`branche`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`branche`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`branche`.`kontrolliert_visa` AS `kontrolliert_visa`,`branche`.`kontrolliert_datum` AS `kontrolliert_datum`,`branche`.`freigabe_visa` AS `freigabe_visa`,`branche`.`freigabe_datum` AS `freigabe_datum`,`branche`.`created_visa` AS `created_visa`,`branche`.`created_date` AS `created_date`,`branche`.`updated_visa` AS `updated_visa`,`branche`.`updated_date` AS `updated_date`,`branche`.`name` AS `name_de`,`branche`.`beschreibung` AS `beschreibung_de`,`branche`.`angaben` AS `angaben_de`,unix_timestamp(`branche`.`created_date`) AS `created_date_unix`,unix_timestamp(`branche`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`branche`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`branche`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`branche`.`freigabe_datum`) AS `freigabe_datum_unix` from `branche`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_country`
--
DROP TABLE IF EXISTS `v_country`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_country` AS select `country`.`name_de` AS `anzeige_name`,`country`.`name_de` AS `anzeige_name_de`,`country`.`name_fr` AS `anzeige_name_fr`,`country`.`id` AS `id`,`country`.`continent` AS `continent`,`country`.`name_en` AS `name_en`,`country`.`official_name_en` AS `official_name_en`,`country`.`capital_en` AS `capital_en`,`country`.`name_de` AS `name_de`,`country`.`official_name_de` AS `official_name_de`,`country`.`capital_de` AS `capital_de`,`country`.`name_fr` AS `name_fr`,`country`.`official_name_fr` AS `official_name_fr`,`country`.`capital_fr` AS `capital_fr`,`country`.`name_it` AS `name_it`,`country`.`official_name_it` AS `official_name_it`,`country`.`capital_it` AS `capital_it`,`country`.`iso-2` AS `iso-2`,`country`.`iso-3` AS `iso-3`,`country`.`vehicle_code` AS `vehicle_code`,`country`.`ioc` AS `ioc`,`country`.`tld` AS `tld`,`country`.`currency` AS `currency`,`country`.`phone` AS `phone`,`country`.`utc` AS `utc`,`country`.`show_level` AS `show_level`,`country`.`created_visa` AS `created_visa`,`country`.`created_date` AS `created_date`,`country`.`updated_visa` AS `updated_visa`,`country`.`updated_date` AS `updated_date`,unix_timestamp(`country`.`created_date`) AS `created_date_unix`,unix_timestamp(`country`.`updated_date`) AS `updated_date_unix` from `country`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_fraktion`
--
DROP TABLE IF EXISTS `v_fraktion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_fraktion` AS select concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name`) AS `anzeige_name`,concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name`) AS `anzeige_name_de`,concat_ws(', ',`fraktion`.`abkuerzung`,`fraktion`.`name_fr`) AS `anzeige_name_fr`,`fraktion`.`id` AS `id`,`fraktion`.`abkuerzung` AS `abkuerzung`,`fraktion`.`name` AS `name`,`fraktion`.`name_fr` AS `name_fr`,`fraktion`.`position` AS `position`,`fraktion`.`farbcode` AS `farbcode`,`fraktion`.`beschreibung` AS `beschreibung`,`fraktion`.`beschreibung_fr` AS `beschreibung_fr`,`fraktion`.`von` AS `von`,`fraktion`.`bis` AS `bis`,`fraktion`.`notizen` AS `notizen`,`fraktion`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`fraktion`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`fraktion`.`kontrolliert_visa` AS `kontrolliert_visa`,`fraktion`.`kontrolliert_datum` AS `kontrolliert_datum`,`fraktion`.`freigabe_visa` AS `freigabe_visa`,`fraktion`.`freigabe_datum` AS `freigabe_datum`,`fraktion`.`created_visa` AS `created_visa`,`fraktion`.`created_date` AS `created_date`,`fraktion`.`updated_visa` AS `updated_visa`,`fraktion`.`updated_date` AS `updated_date`,`fraktion`.`name` AS `name_de`,`fraktion`.`beschreibung` AS `beschreibung_de`,unix_timestamp(`fraktion`.`created_date`) AS `created_date_unix`,unix_timestamp(`fraktion`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`fraktion`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`fraktion`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`fraktion`.`freigabe_datum`) AS `freigabe_datum_unix` from `fraktion`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung`
--
DROP TABLE IF EXISTS `v_interessenbindung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung` AS select `mv_interessenbindung`.`id` AS `id`,`mv_interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`mv_interessenbindung`.`organisation_id` AS `organisation_id`,`mv_interessenbindung`.`art` AS `art`,`mv_interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`mv_interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`mv_interessenbindung`.`status` AS `status`,`mv_interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`mv_interessenbindung`.`verguetung` AS `verguetung`,`mv_interessenbindung`.`beschreibung` AS `beschreibung`,`mv_interessenbindung`.`quelle_url` AS `quelle_url`,`mv_interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mv_interessenbindung`.`quelle` AS `quelle`,`mv_interessenbindung`.`von` AS `von`,`mv_interessenbindung`.`bis` AS `bis`,`mv_interessenbindung`.`notizen` AS `notizen`,`mv_interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`mv_interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`mv_interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`mv_interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`mv_interessenbindung`.`created_visa` AS `created_visa`,`mv_interessenbindung`.`created_date` AS `created_date`,`mv_interessenbindung`.`updated_visa` AS `updated_visa`,`mv_interessenbindung`.`updated_date` AS `updated_date`,`mv_interessenbindung`.`bis_unix` AS `bis_unix`,`mv_interessenbindung`.`von_unix` AS `von_unix`,`mv_interessenbindung`.`created_date_unix` AS `created_date_unix`,`mv_interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`mv_interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`mv_interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`mv_interessenbindung`.`wirksamkeit_index` AS `wirksamkeit_index`,`mv_interessenbindung`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mv_interessenbindung`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`mv_interessenbindung`.`refreshed_date` AS `refreshed_date` from `mv_interessenbindung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_authorisierungs_email`
--
DROP TABLE IF EXISTS `v_interessenbindung_authorisierungs_email`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_authorisierungs_email` AS select `parlamentarier`.`name` AS `parlamentarier_name`,ifnull(`parlamentarier`.`geschlecht`,'') AS `geschlecht`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,ifnull(`organisation`.`rechtsform`,'') AS `rechtsform`,ifnull(`organisation`.`ort`,'') AS `ort`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`beschreibung` AS `beschreibung` from ((`v_interessenbindung_simple` `interessenbindung` join `v_organisation_simple` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) group by `parlamentarier`.`id` order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_liste`
--
DROP TABLE IF EXISTS `v_interessenbindung_liste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_liste` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`interessenbindung`.`wirksamkeit_index` AS `wirksamkeit_index`,`interessenbindung`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`interessenbindung`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`interessenbindung`.`refreshed_date` AS `refreshed_date` from (`v_interessenbindung` `interessenbindung` join `v_organisation` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) order by `interessenbindung`.`wirksamkeit`,`organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_liste_indirekt`
--
DROP TABLE IF EXISTS `v_interessenbindung_liste_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_liste_indirekt` AS select 'direkt' AS `beziehung`,`interessenbindung_liste`.`organisation_name` AS `organisation_name`,`interessenbindung_liste`.`organisation_name_de` AS `organisation_name_de`,`interessenbindung_liste`.`organisation_name_fr` AS `organisation_name_fr`,`interessenbindung_liste`.`name` AS `name`,`interessenbindung_liste`.`name_de` AS `name_de`,`interessenbindung_liste`.`name_fr` AS `name_fr`,`interessenbindung_liste`.`name_it` AS `name_it`,`interessenbindung_liste`.`ort` AS `ort`,`interessenbindung_liste`.`land_id` AS `land_id`,`interessenbindung_liste`.`interessenraum_id` AS `interessenraum_id`,`interessenbindung_liste`.`rechtsform` AS `rechtsform`,`interessenbindung_liste`.`typ` AS `typ`,`interessenbindung_liste`.`vernehmlassung` AS `vernehmlassung`,`interessenbindung_liste`.`interessengruppe_id` AS `interessengruppe_id`,`interessenbindung_liste`.`interessengruppe2_id` AS `interessengruppe2_id`,`interessenbindung_liste`.`interessengruppe3_id` AS `interessengruppe3_id`,`interessenbindung_liste`.`branche_id` AS `branche_id`,`interessenbindung_liste`.`homepage` AS `homepage`,`interessenbindung_liste`.`handelsregister_url` AS `handelsregister_url`,`interessenbindung_liste`.`twitter_name` AS `twitter_name`,`interessenbindung_liste`.`organisation_beschreibung` AS `organisation_beschreibung`,`interessenbindung_liste`.`adresse_strasse` AS `adresse_strasse`,`interessenbindung_liste`.`adresse_zusatz` AS `adresse_zusatz`,`interessenbindung_liste`.`adresse_plz` AS `adresse_plz`,`interessenbindung_liste`.`branche` AS `branche`,`interessenbindung_liste`.`interessengruppe` AS `interessengruppe`,`interessenbindung_liste`.`interessengruppe_branche` AS `interessengruppe_branche`,`interessenbindung_liste`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`interessenbindung_liste`.`interessengruppe2` AS `interessengruppe2`,`interessenbindung_liste`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`interessenbindung_liste`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`interessenbindung_liste`.`interessengruppe3` AS `interessengruppe3`,`interessenbindung_liste`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`interessenbindung_liste`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`interessenbindung_liste`.`land` AS `land`,`interessenbindung_liste`.`interessenraum` AS `interessenraum`,`interessenbindung_liste`.`organisation_jahr_id` AS `organisation_jahr_id`,`interessenbindung_liste`.`jahr` AS `jahr`,`interessenbindung_liste`.`umsatz` AS `umsatz`,`interessenbindung_liste`.`gewinn` AS `gewinn`,`interessenbindung_liste`.`kapital` AS `kapital`,`interessenbindung_liste`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`interessenbindung_liste`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`interessenbindung_liste`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`interessenbindung_liste`.`id` AS `id`,`interessenbindung_liste`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung_liste`.`organisation_id` AS `organisation_id`,`interessenbindung_liste`.`art` AS `art`,`interessenbindung_liste`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung_liste`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung_liste`.`status` AS `status`,`interessenbindung_liste`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung_liste`.`verguetung` AS `verguetung`,`interessenbindung_liste`.`beschreibung` AS `beschreibung`,`interessenbindung_liste`.`quelle_url` AS `quelle_url`,`interessenbindung_liste`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung_liste`.`quelle` AS `quelle`,`interessenbindung_liste`.`von` AS `von`,`interessenbindung_liste`.`bis` AS `bis`,`interessenbindung_liste`.`notizen` AS `notizen`,`interessenbindung_liste`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung_liste`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung_liste`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung_liste`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung_liste`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung_liste`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung_liste`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung_liste`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung_liste`.`created_visa` AS `created_visa`,`interessenbindung_liste`.`created_date` AS `created_date`,`interessenbindung_liste`.`updated_visa` AS `updated_visa`,`interessenbindung_liste`.`updated_date` AS `updated_date`,`interessenbindung_liste`.`bis_unix` AS `bis_unix`,`interessenbindung_liste`.`von_unix` AS `von_unix`,`interessenbindung_liste`.`created_date_unix` AS `created_date_unix`,`interessenbindung_liste`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung_liste`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung_liste`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung_liste`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung_liste`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung_liste`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`interessenbindung_liste`.`wirksamkeit_index` AS `wirksamkeit_index`,`interessenbindung_liste`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`interessenbindung_liste`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`interessenbindung_liste`.`refreshed_date` AS `refreshed_date` from `v_interessenbindung_liste` `interessenbindung_liste` union select 'indirekt' AS `beziehung`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,`interessenbindung`.`wirksamkeit_index` AS `wirksamkeit_index`,`interessenbindung`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`interessenbindung`.`parlamentarier_lobbyfaktor` AS `parlamentarier_lobbyfaktor`,`interessenbindung`.`refreshed_date` AS `refreshed_date` from ((`v_interessenbindung` `interessenbindung` join `v_organisation_beziehung` `organisation_beziehung` on((`interessenbindung`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`organisation_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_medium_raw`
--
DROP TABLE IF EXISTS `v_interessenbindung_medium_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_medium_raw` AS select `interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,if(((`organisation`.`vernehmlassung` in ('immmer','punktuell')) and (`interessenbindung`.`art` in ('geschaeftsfuehrend','vorstand')) and exists(select `in_kommission`.`kommission_id` from (`in_kommission` left join `branche` on((`in_kommission`.`kommission_id` = `branche`.`kommission_id`))) where (((`in_kommission`.`bis` >= now()) or isnull(`in_kommission`.`bis`)) and (`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`) and (`branche`.`id` in (`organisation`.`branche_id`,`organisation`.`interessengruppe_branche_id`,`organisation`.`interessengruppe2_branche_id`,`organisation`.`interessengruppe3_branche_id`))))),'hoch',if(((`organisation`.`vernehmlassung` in ('immmer','punktuell')) and (`interessenbindung`.`art` in ('geschaeftsfuehrend','vorstand','taetig','beirat','finanziell'))),'mittel','tief')) AS `wirksamkeit`,`parlamentarier`.`im_rat_seit` AS `parlamentarier_im_rat_seit` from ((`v_interessenbindung_simple` `interessenbindung` join `v_organisation_medium_raw` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_raw`
--
DROP TABLE IF EXISTS `v_interessenbindung_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_raw` AS select `interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessenbindung`.`wirksamkeit` AS `wirksamkeit`,`interessenbindung`.`parlamentarier_im_rat_seit` AS `parlamentarier_im_rat_seit`,(case `interessenbindung`.`wirksamkeit` when 'hoch' then 3 when 'mittel' then 2 when 'tief' then 1 else 0 end) AS `wirksamkeit_index`,`organisation`.`lobbyeinfluss` AS `organisation_lobbyeinfluss`,`parlamentarier`.`lobbyfaktor` AS `parlamentarier_lobbyfaktor`,now() AS `refreshed_date` from ((`v_interessenbindung_medium_raw` `interessenbindung` join `v_organisation_raw` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) join `v_parlamentarier_raw` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_simple`
--
DROP TABLE IF EXISTS `v_interessenbindung_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_simple` AS select `interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,unix_timestamp(`interessenbindung`.`bis`) AS `bis_unix`,unix_timestamp(`interessenbindung`.`von`) AS `von_unix`,unix_timestamp(`interessenbindung`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessenbindung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessenbindung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessenbindung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessenbindung`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessenbindung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessengruppe`
--
DROP TABLE IF EXISTS `v_interessengruppe`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessengruppe` AS select `interessengruppe`.`anzeige_name` AS `anzeige_name`,`interessengruppe`.`anzeige_name_de` AS `anzeige_name_de`,`interessengruppe`.`anzeige_name_fr` AS `anzeige_name_fr`,`interessengruppe`.`id` AS `id`,`interessengruppe`.`name` AS `name`,`interessengruppe`.`name_fr` AS `name_fr`,`interessengruppe`.`branche_id` AS `branche_id`,`interessengruppe`.`beschreibung` AS `beschreibung`,`interessengruppe`.`beschreibung_fr` AS `beschreibung_fr`,`interessengruppe`.`alias_namen` AS `alias_namen`,`interessengruppe`.`alias_namen_fr` AS `alias_namen_fr`,`interessengruppe`.`notizen` AS `notizen`,`interessengruppe`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessengruppe`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessengruppe`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessengruppe`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessengruppe`.`freigabe_visa` AS `freigabe_visa`,`interessengruppe`.`freigabe_datum` AS `freigabe_datum`,`interessengruppe`.`created_visa` AS `created_visa`,`interessengruppe`.`created_date` AS `created_date`,`interessengruppe`.`updated_visa` AS `updated_visa`,`interessengruppe`.`updated_date` AS `updated_date`,`interessengruppe`.`name_de` AS `name_de`,`interessengruppe`.`beschreibung_de` AS `beschreibung_de`,`interessengruppe`.`alias_namen_de` AS `alias_namen_de`,`interessengruppe`.`created_date_unix` AS `created_date_unix`,`interessengruppe`.`updated_date_unix` AS `updated_date_unix`,`interessengruppe`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessengruppe`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessengruppe`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`branche`.`anzeige_name` AS `branche`,`branche`.`anzeige_name_de` AS `branche_de`,`branche`.`anzeige_name_fr` AS `branche_fr`,`branche`.`kommission_id` AS `kommission_id`,`branche`.`kommission` AS `kommission`,`branche`.`kommission_de` AS `kommission_de`,`branche`.`kommission_fr` AS `kommission_fr` from (`v_interessengruppe_simple` `interessengruppe` left join `v_branche` `branche` on((`branche`.`id` = `interessengruppe`.`branche_id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessengruppe_simple`
--
DROP TABLE IF EXISTS `v_interessengruppe_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessengruppe_simple` AS select concat(`interessengruppe`.`name`) AS `anzeige_name`,concat(`interessengruppe`.`name`) AS `anzeige_name_de`,concat(`interessengruppe`.`name_fr`) AS `anzeige_name_fr`,`interessengruppe`.`id` AS `id`,`interessengruppe`.`name` AS `name`,`interessengruppe`.`name_fr` AS `name_fr`,`interessengruppe`.`branche_id` AS `branche_id`,`interessengruppe`.`beschreibung` AS `beschreibung`,`interessengruppe`.`beschreibung_fr` AS `beschreibung_fr`,`interessengruppe`.`alias_namen` AS `alias_namen`,`interessengruppe`.`alias_namen_fr` AS `alias_namen_fr`,`interessengruppe`.`notizen` AS `notizen`,`interessengruppe`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessengruppe`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessengruppe`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessengruppe`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessengruppe`.`freigabe_visa` AS `freigabe_visa`,`interessengruppe`.`freigabe_datum` AS `freigabe_datum`,`interessengruppe`.`created_visa` AS `created_visa`,`interessengruppe`.`created_date` AS `created_date`,`interessengruppe`.`updated_visa` AS `updated_visa`,`interessengruppe`.`updated_date` AS `updated_date`,`interessengruppe`.`name` AS `name_de`,`interessengruppe`.`beschreibung` AS `beschreibung_de`,`interessengruppe`.`alias_namen` AS `alias_namen_de`,unix_timestamp(`interessengruppe`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessengruppe`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessengruppe`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessengruppe`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessengruppe`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessengruppe`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenraum`
--
DROP TABLE IF EXISTS `v_interessenraum`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenraum` AS select `interessenraum`.`name` AS `anzeige_name`,`interessenraum`.`name` AS `anzeige_name_de`,`interessenraum`.`name_fr` AS `anzeige_name_fr`,`interessenraum`.`id` AS `id`,`interessenraum`.`name` AS `name`,`interessenraum`.`name_fr` AS `name_fr`,`interessenraum`.`beschreibung` AS `beschreibung`,`interessenraum`.`beschreibung_fr` AS `beschreibung_fr`,`interessenraum`.`reihenfolge` AS `reihenfolge`,`interessenraum`.`notizen` AS `notizen`,`interessenraum`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenraum`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenraum`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenraum`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenraum`.`freigabe_visa` AS `freigabe_visa`,`interessenraum`.`freigabe_datum` AS `freigabe_datum`,`interessenraum`.`created_visa` AS `created_visa`,`interessenraum`.`created_date` AS `created_date`,`interessenraum`.`updated_visa` AS `updated_visa`,`interessenraum`.`updated_date` AS `updated_date`,`interessenraum`.`name` AS `name_de`,`interessenraum`.`beschreibung` AS `beschreibung_de`,unix_timestamp(`interessenraum`.`created_date`) AS `created_date_unix`,unix_timestamp(`interessenraum`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`interessenraum`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`interessenraum`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`interessenraum`.`freigabe_datum`) AS `freigabe_datum_unix` from `interessenraum` order by `interessenraum`.`reihenfolge`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission`
--
DROP TABLE IF EXISTS `v_in_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission` AS select `in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`bis_unix` AS `bis_unix`,`in_kommission`.`von_unix` AS `von_unix`,`in_kommission`.`created_date_unix` AS `created_date_unix`,`in_kommission`.`updated_date_unix` AS `updated_date_unix`,`in_kommission`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`in_kommission`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`in_kommission`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`rat`.`abkuerzung` AS `rat`,`rat`.`abkuerzung` AS `rat_de`,`rat`.`abkuerzung_fr` AS `rat_fr`,`rat`.`abkuerzung` AS `ratstyp`,`kommission`.`abkuerzung` AS `kommission_abkuerzung`,`kommission`.`name` AS `kommission_name`,`kommission`.`abkuerzung` AS `kommission_abkuerzung_de`,`kommission`.`name` AS `kommission_name_de`,`kommission`.`abkuerzung_fr` AS `kommission_abkuerzung_fr`,`kommission`.`name_fr` AS `kommission_name_fr`,`kommission`.`art` AS `kommission_art`,`kommission`.`typ` AS `kommission_typ`,`kommission`.`beschreibung` AS `kommission_beschreibung`,`kommission`.`sachbereiche` AS `kommission_sachbereiche`,`kommission`.`mutter_kommission_id` AS `kommission_mutter_kommission_id`,`kommission`.`parlament_url` AS `kommission_parlament_url` from ((((`v_in_kommission_simple` `in_kommission` join `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) left join `kanton` on((`parlamentarier`.`kanton_id` = `kanton`.`id`))) left join `rat` on((`parlamentarier`.`rat_id` = `rat`.`id`))) left join `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_liste`
--
DROP TABLE IF EXISTS `v_in_kommission_liste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_liste` AS select `kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`name` AS `name`,`kommission`.`typ` AS `typ`,`kommission`.`art` AS `art`,`kommission`.`beschreibung` AS `beschreibung`,`kommission`.`sachbereiche` AS `sachbereiche`,`kommission`.`mutter_kommission_id` AS `mutter_kommission_id`,`kommission`.`parlament_url` AS `parlament_url`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`bis_unix` AS `bis_unix`,`in_kommission`.`von_unix` AS `von_unix`,`in_kommission`.`created_date_unix` AS `created_date_unix`,`in_kommission`.`updated_date_unix` AS `updated_date_unix`,`in_kommission`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`in_kommission`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`in_kommission`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_in_kommission_simple` `in_kommission` join `v_kommission` `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) order by `kommission`.`abkuerzung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_parlamentarier`
--
DROP TABLE IF EXISTS `v_in_kommission_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_parlamentarier` AS select `parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`rat` AS `rat`,`parlamentarier`.`rat_de` AS `rat_de`,`parlamentarier`.`rat_fr` AS `rat_fr`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`partei_de` AS `partei_de`,`parlamentarier`.`partei_fr` AS `partei_fr`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`parlamentarier`.`militaerischer_grad_de` AS `militaerischer_grad_de`,`parlamentarier`.`militaerischer_grad_fr` AS `militaerischer_grad_fr`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,`in_kommission`.`bis_unix` AS `bis_unix`,`in_kommission`.`von_unix` AS `von_unix`,`in_kommission`.`created_date_unix` AS `created_date_unix`,`in_kommission`.`updated_date_unix` AS `updated_date_unix`,`in_kommission`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`in_kommission`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`in_kommission`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_in_kommission_simple` `in_kommission` join `v_parlamentarier` `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_simple`
--
DROP TABLE IF EXISTS `v_in_kommission_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_simple` AS select `in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`von` AS `von`,`in_kommission`.`bis` AS `bis`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`in_kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`in_kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`in_kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`in_kommission`.`freigabe_visa` AS `freigabe_visa`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date`,unix_timestamp(`in_kommission`.`bis`) AS `bis_unix`,unix_timestamp(`in_kommission`.`von`) AS `von_unix`,unix_timestamp(`in_kommission`.`created_date`) AS `created_date_unix`,unix_timestamp(`in_kommission`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`in_kommission`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`in_kommission`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`in_kommission`.`freigabe_datum`) AS `freigabe_datum_unix` from `in_kommission`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton`
--
DROP TABLE IF EXISTS `v_kanton`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton` AS select `kanton`.`anzeige_name` AS `anzeige_name`,`kanton`.`anzeige_name_de` AS `anzeige_name_de`,`kanton`.`anzeige_name_fr` AS `anzeige_name_fr`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date`,`kanton_jahr`.`id` AS `kanton_jahr_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete` from (`v_kanton_simple` `kanton` left join `v_kanton_jahr_last` `kanton_jahr` on((`kanton_jahr`.`kanton_id` = `kanton`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_2012`
--
DROP TABLE IF EXISTS `v_kanton_2012`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_2012` AS select `kanton`.`name_de` AS `anzeige_name`,`kanton`.`name_de` AS `anzeige_name_de`,`kanton`.`name_fr` AS `anzeige_name_fr`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date`,`kanton_jahr`.`id` AS `kanton_jahr_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete` from (`kanton` left join `v_kanton_jahr` `kanton_jahr` on(((`kanton_jahr`.`kanton_id` = `kanton`.`id`) and (`kanton_jahr`.`jahr` = 2012))));

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_jahr`
--
DROP TABLE IF EXISTS `v_kanton_jahr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_jahr` AS select `kanton_jahr`.`id` AS `id`,`kanton_jahr`.`kanton_id` AS `kanton_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`steuereinnahmen` AS `steuereinnahmen`,`kanton_jahr`.`ausgaben` AS `ausgaben`,`kanton_jahr`.`finanzausgleich` AS `finanzausgleich`,`kanton_jahr`.`schulden` AS `schulden`,`kanton_jahr`.`notizen` AS `notizen`,`kanton_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton_jahr`.`freigabe_visa` AS `freigabe_visa`,`kanton_jahr`.`freigabe_datum` AS `freigabe_datum`,`kanton_jahr`.`created_visa` AS `created_visa`,`kanton_jahr`.`created_date` AS `created_date`,`kanton_jahr`.`updated_visa` AS `updated_visa`,`kanton_jahr`.`updated_date` AS `updated_date`,unix_timestamp(`kanton_jahr`.`created_date`) AS `created_date_unix`,unix_timestamp(`kanton_jahr`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`kanton_jahr`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`kanton_jahr`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`kanton_jahr`.`freigabe_datum`) AS `freigabe_datum_unix` from `kanton_jahr`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_jahr_last`
--
DROP TABLE IF EXISTS `v_kanton_jahr_last`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_jahr_last` AS select max(`kanton_jahr`.`jahr`) AS `max_jahr`,`kanton_jahr`.`id` AS `id`,`kanton_jahr`.`kanton_id` AS `kanton_id`,`kanton_jahr`.`jahr` AS `jahr`,`kanton_jahr`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kanton_jahr`.`einwohner` AS `einwohner`,`kanton_jahr`.`auslaenderanteil` AS `auslaenderanteil`,`kanton_jahr`.`bevoelkerungsdichte` AS `bevoelkerungsdichte`,`kanton_jahr`.`anzahl_gemeinden` AS `anzahl_gemeinden`,`kanton_jahr`.`steuereinnahmen` AS `steuereinnahmen`,`kanton_jahr`.`ausgaben` AS `ausgaben`,`kanton_jahr`.`finanzausgleich` AS `finanzausgleich`,`kanton_jahr`.`schulden` AS `schulden`,`kanton_jahr`.`notizen` AS `notizen`,`kanton_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton_jahr`.`freigabe_visa` AS `freigabe_visa`,`kanton_jahr`.`freigabe_datum` AS `freigabe_datum`,`kanton_jahr`.`created_visa` AS `created_visa`,`kanton_jahr`.`created_date` AS `created_date`,`kanton_jahr`.`updated_visa` AS `updated_visa`,`kanton_jahr`.`updated_date` AS `updated_date` from `kanton_jahr` group by `kanton_jahr`.`kanton_id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kanton_simple`
--
DROP TABLE IF EXISTS `v_kanton_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kanton_simple` AS select `kanton`.`name_de` AS `anzeige_name`,`kanton`.`name_de` AS `anzeige_name_de`,`kanton`.`name_fr` AS `anzeige_name_fr`,`kanton`.`id` AS `id`,`kanton`.`abkuerzung` AS `abkuerzung`,`kanton`.`kantonsnr` AS `kantonsnr`,`kanton`.`name_de` AS `name_de`,`kanton`.`name_fr` AS `name_fr`,`kanton`.`name_it` AS `name_it`,`kanton`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kanton`.`amtssprache` AS `amtssprache`,`kanton`.`hauptort_de` AS `hauptort_de`,`kanton`.`hauptort_fr` AS `hauptort_fr`,`kanton`.`hauptort_it` AS `hauptort_it`,`kanton`.`flaeche_km2` AS `flaeche_km2`,`kanton`.`beitrittsjahr` AS `beitrittsjahr`,`kanton`.`wappen_klein` AS `wappen_klein`,`kanton`.`wappen` AS `wappen`,`kanton`.`lagebild` AS `lagebild`,`kanton`.`homepage` AS `homepage`,`kanton`.`beschreibung` AS `beschreibung`,`kanton`.`notizen` AS `notizen`,`kanton`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kanton`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kanton`.`kontrolliert_visa` AS `kontrolliert_visa`,`kanton`.`kontrolliert_datum` AS `kontrolliert_datum`,`kanton`.`freigabe_visa` AS `freigabe_visa`,`kanton`.`freigabe_datum` AS `freigabe_datum`,`kanton`.`created_visa` AS `created_visa`,`kanton`.`created_date` AS `created_date`,`kanton`.`updated_visa` AS `updated_visa`,`kanton`.`updated_date` AS `updated_date` from `kanton`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kommission`
--
DROP TABLE IF EXISTS `v_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kommission` AS select concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') AS `anzeige_name`,concat(`kommission`.`name`,' (',`kommission`.`abkuerzung`,')') AS `anzeige_name_de`,concat(`kommission`.`name_fr`,' (',`kommission`.`abkuerzung_fr`,')') AS `anzeige_name_fr`,`kommission`.`id` AS `id`,`kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`abkuerzung_fr` AS `abkuerzung_fr`,`kommission`.`name` AS `name`,`kommission`.`name_fr` AS `name_fr`,`kommission`.`typ` AS `typ`,`kommission`.`art` AS `art`,`kommission`.`beschreibung` AS `beschreibung`,`kommission`.`beschreibung_fr` AS `beschreibung_fr`,`kommission`.`sachbereiche` AS `sachbereiche`,`kommission`.`sachbereiche_fr` AS `sachbereiche_fr`,`kommission`.`anzahl_nationalraete` AS `anzahl_nationalraete`,`kommission`.`anzahl_staenderaete` AS `anzahl_staenderaete`,`kommission`.`mutter_kommission_id` AS `mutter_kommission_id`,`kommission`.`parlament_url` AS `parlament_url`,`kommission`.`notizen` AS `notizen`,`kommission`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`kommission`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`kommission`.`kontrolliert_visa` AS `kontrolliert_visa`,`kommission`.`kontrolliert_datum` AS `kontrolliert_datum`,`kommission`.`freigabe_visa` AS `freigabe_visa`,`kommission`.`freigabe_datum` AS `freigabe_datum`,`kommission`.`created_visa` AS `created_visa`,`kommission`.`created_date` AS `created_date`,`kommission`.`updated_visa` AS `updated_visa`,`kommission`.`updated_date` AS `updated_date`,`kommission`.`name` AS `name_de`,`kommission`.`abkuerzung` AS `abkuerzung_de`,`kommission`.`beschreibung` AS `beschreibung_de`,`kommission`.`sachbereiche` AS `sachbereiche_de`,unix_timestamp(`kommission`.`created_date`) AS `created_date_unix`,unix_timestamp(`kommission`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`kommission`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`kommission`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`kommission`.`freigabe_datum`) AS `freigabe_datum_unix` from `kommission`;

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
-- Struktur des Views `v_last_updated_person`
--
DROP TABLE IF EXISTS `v_last_updated_person`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_person` AS (select 'person' AS `table_name`,'Person' AS `name`,(select count(0) from `person`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `person` `t` order by `t`.`updated_date` desc limit 1);

-- --------------------------------------------------------

--
-- Struktur des Views `v_last_updated_person_anhang`
--
DROP TABLE IF EXISTS `v_last_updated_person_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_person_anhang` AS (select 'person_anhang' AS `table_name`,'Personenanhang' AS `name`,(select count(0) from `person_anhang`) AS `anzahl_eintraege`,`t`.`updated_visa` AS `last_visa`,`t`.`updated_date` AS `last_updated`,`t`.`id` AS `last_updated_id` from `person_anhang` `t` order by `t`.`updated_date` desc limit 1);

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_last_updated_tables_unordered` AS select `v_last_updated_branche`.`table_name` AS `table_name`,`v_last_updated_branche`.`name` AS `name`,`v_last_updated_branche`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_branche`.`last_visa` AS `last_visa`,`v_last_updated_branche`.`last_updated` AS `last_updated`,`v_last_updated_branche`.`last_updated_id` AS `last_updated_id` from `v_last_updated_branche` union select `v_last_updated_interessenbindung`.`table_name` AS `table_name`,`v_last_updated_interessenbindung`.`name` AS `name`,`v_last_updated_interessenbindung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessenbindung`.`last_visa` AS `last_visa`,`v_last_updated_interessenbindung`.`last_updated` AS `last_updated`,`v_last_updated_interessenbindung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessenbindung` union select `v_last_updated_interessengruppe`.`table_name` AS `table_name`,`v_last_updated_interessengruppe`.`name` AS `name`,`v_last_updated_interessengruppe`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_interessengruppe`.`last_visa` AS `last_visa`,`v_last_updated_interessengruppe`.`last_updated` AS `last_updated`,`v_last_updated_interessengruppe`.`last_updated_id` AS `last_updated_id` from `v_last_updated_interessengruppe` union select `v_last_updated_in_kommission`.`table_name` AS `table_name`,`v_last_updated_in_kommission`.`name` AS `name`,`v_last_updated_in_kommission`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_in_kommission`.`last_visa` AS `last_visa`,`v_last_updated_in_kommission`.`last_updated` AS `last_updated`,`v_last_updated_in_kommission`.`last_updated_id` AS `last_updated_id` from `v_last_updated_in_kommission` union select `v_last_updated_kommission`.`table_name` AS `table_name`,`v_last_updated_kommission`.`name` AS `name`,`v_last_updated_kommission`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kommission`.`last_visa` AS `last_visa`,`v_last_updated_kommission`.`last_updated` AS `last_updated`,`v_last_updated_kommission`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kommission` union select `v_last_updated_mandat`.`table_name` AS `table_name`,`v_last_updated_mandat`.`name` AS `name`,`v_last_updated_mandat`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_mandat`.`last_visa` AS `last_visa`,`v_last_updated_mandat`.`last_updated` AS `last_updated`,`v_last_updated_mandat`.`last_updated_id` AS `last_updated_id` from `v_last_updated_mandat` union select `v_last_updated_organisation`.`table_name` AS `table_name`,`v_last_updated_organisation`.`name` AS `name`,`v_last_updated_organisation`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation`.`last_visa` AS `last_visa`,`v_last_updated_organisation`.`last_updated` AS `last_updated`,`v_last_updated_organisation`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation` union select `v_last_updated_organisation_beziehung`.`table_name` AS `table_name`,`v_last_updated_organisation_beziehung`.`name` AS `name`,`v_last_updated_organisation_beziehung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_beziehung`.`last_visa` AS `last_visa`,`v_last_updated_organisation_beziehung`.`last_updated` AS `last_updated`,`v_last_updated_organisation_beziehung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_beziehung` union select `v_last_updated_organisation_jahr`.`table_name` AS `table_name`,`v_last_updated_organisation_jahr`.`name` AS `name`,`v_last_updated_organisation_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_jahr`.`last_visa` AS `last_visa`,`v_last_updated_organisation_jahr`.`last_updated` AS `last_updated`,`v_last_updated_organisation_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_jahr` union select `v_last_updated_parlamentarier`.`table_name` AS `table_name`,`v_last_updated_parlamentarier`.`name` AS `name`,`v_last_updated_parlamentarier`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_parlamentarier`.`last_visa` AS `last_visa`,`v_last_updated_parlamentarier`.`last_updated` AS `last_updated`,`v_last_updated_parlamentarier`.`last_updated_id` AS `last_updated_id` from `v_last_updated_parlamentarier` union select `v_last_updated_partei`.`table_name` AS `table_name`,`v_last_updated_partei`.`name` AS `name`,`v_last_updated_partei`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_partei`.`last_visa` AS `last_visa`,`v_last_updated_partei`.`last_updated` AS `last_updated`,`v_last_updated_partei`.`last_updated_id` AS `last_updated_id` from `v_last_updated_partei` union select `v_last_updated_fraktion`.`table_name` AS `table_name`,`v_last_updated_fraktion`.`name` AS `name`,`v_last_updated_fraktion`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_fraktion`.`last_visa` AS `last_visa`,`v_last_updated_fraktion`.`last_updated` AS `last_updated`,`v_last_updated_fraktion`.`last_updated_id` AS `last_updated_id` from `v_last_updated_fraktion` union select `v_last_updated_rat`.`table_name` AS `table_name`,`v_last_updated_rat`.`name` AS `name`,`v_last_updated_rat`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_rat`.`last_visa` AS `last_visa`,`v_last_updated_rat`.`last_updated` AS `last_updated`,`v_last_updated_rat`.`last_updated_id` AS `last_updated_id` from `v_last_updated_rat` union select `v_last_updated_kanton`.`table_name` AS `table_name`,`v_last_updated_kanton`.`name` AS `name`,`v_last_updated_kanton`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kanton`.`last_visa` AS `last_visa`,`v_last_updated_kanton`.`last_updated` AS `last_updated`,`v_last_updated_kanton`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kanton` union select `v_last_updated_kanton_jahr`.`table_name` AS `table_name`,`v_last_updated_kanton_jahr`.`name` AS `name`,`v_last_updated_kanton_jahr`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_kanton_jahr`.`last_visa` AS `last_visa`,`v_last_updated_kanton_jahr`.`last_updated` AS `last_updated`,`v_last_updated_kanton_jahr`.`last_updated_id` AS `last_updated_id` from `v_last_updated_kanton_jahr` union select `v_last_updated_zutrittsberechtigung`.`table_name` AS `table_name`,`v_last_updated_zutrittsberechtigung`.`name` AS `name`,`v_last_updated_zutrittsberechtigung`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_zutrittsberechtigung`.`last_visa` AS `last_visa`,`v_last_updated_zutrittsberechtigung`.`last_updated` AS `last_updated`,`v_last_updated_zutrittsberechtigung`.`last_updated_id` AS `last_updated_id` from `v_last_updated_zutrittsberechtigung` union select `v_last_updated_person`.`table_name` AS `table_name`,`v_last_updated_person`.`name` AS `name`,`v_last_updated_person`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_person`.`last_visa` AS `last_visa`,`v_last_updated_person`.`last_updated` AS `last_updated`,`v_last_updated_person`.`last_updated_id` AS `last_updated_id` from `v_last_updated_person` union select `v_last_updated_parlamentarier_anhang`.`table_name` AS `table_name`,`v_last_updated_parlamentarier_anhang`.`name` AS `name`,`v_last_updated_parlamentarier_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_parlamentarier_anhang`.`last_visa` AS `last_visa`,`v_last_updated_parlamentarier_anhang`.`last_updated` AS `last_updated`,`v_last_updated_parlamentarier_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_parlamentarier_anhang` union select `v_last_updated_organisation_anhang`.`table_name` AS `table_name`,`v_last_updated_organisation_anhang`.`name` AS `name`,`v_last_updated_organisation_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_organisation_anhang`.`last_visa` AS `last_visa`,`v_last_updated_organisation_anhang`.`last_updated` AS `last_updated`,`v_last_updated_organisation_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_organisation_anhang` union select `v_last_updated_person_anhang`.`table_name` AS `table_name`,`v_last_updated_person_anhang`.`name` AS `name`,`v_last_updated_person_anhang`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_person_anhang`.`last_visa` AS `last_visa`,`v_last_updated_person_anhang`.`last_updated` AS `last_updated`,`v_last_updated_person_anhang`.`last_updated_id` AS `last_updated_id` from `v_last_updated_person_anhang` union select `v_last_updated_settings`.`table_name` AS `table_name`,`v_last_updated_settings`.`name` AS `name`,`v_last_updated_settings`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_settings`.`last_visa` AS `last_visa`,`v_last_updated_settings`.`last_updated` AS `last_updated`,`v_last_updated_settings`.`last_updated_id` AS `last_updated_id` from `v_last_updated_settings` union select `v_last_updated_settings_category`.`table_name` AS `table_name`,`v_last_updated_settings_category`.`name` AS `name`,`v_last_updated_settings_category`.`anzahl_eintraege` AS `anzahl_eintraege`,`v_last_updated_settings_category`.`last_visa` AS `last_visa`,`v_last_updated_settings_category`.`last_updated` AS `last_updated`,`v_last_updated_settings_category`.`last_updated_id` AS `last_updated_id` from `v_last_updated_settings_category`;

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mandat` AS select `mv_mandat`.`id` AS `id`,`mv_mandat`.`person_id` AS `person_id`,`mv_mandat`.`organisation_id` AS `organisation_id`,`mv_mandat`.`art` AS `art`,`mv_mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mv_mandat`.`verguetung` AS `verguetung`,`mv_mandat`.`beschreibung` AS `beschreibung`,`mv_mandat`.`quelle_url` AS `quelle_url`,`mv_mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mv_mandat`.`quelle` AS `quelle`,`mv_mandat`.`von` AS `von`,`mv_mandat`.`bis` AS `bis`,`mv_mandat`.`notizen` AS `notizen`,`mv_mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mv_mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mv_mandat`.`freigabe_visa` AS `freigabe_visa`,`mv_mandat`.`freigabe_datum` AS `freigabe_datum`,`mv_mandat`.`created_visa` AS `created_visa`,`mv_mandat`.`created_date` AS `created_date`,`mv_mandat`.`updated_visa` AS `updated_visa`,`mv_mandat`.`updated_date` AS `updated_date`,`mv_mandat`.`bis_unix` AS `bis_unix`,`mv_mandat`.`von_unix` AS `von_unix`,`mv_mandat`.`created_date_unix` AS `created_date_unix`,`mv_mandat`.`updated_date_unix` AS `updated_date_unix`,`mv_mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_mandat`.`wirksamkeit` AS `wirksamkeit`,`mv_mandat`.`wirksamkeit_index` AS `wirksamkeit_index`,`mv_mandat`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mv_mandat`.`refreshed_date` AS `refreshed_date` from `mv_mandat`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_mandat_medium_raw`
--
DROP TABLE IF EXISTS `v_mandat_medium_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mandat_medium_raw` AS select `mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,if(((`organisation`.`vernehmlassung` in ('immmer','punktuell')) and (`mandat`.`art` in ('geschaeftsfuehrend','vorstand'))),'hoch',if((((`organisation`.`vernehmlassung` in ('immmer','punktuell')) and (`mandat`.`art` in ('taetig','beirat','finanziell'))) or (`mandat`.`art` in ('geschaeftsfuehrend','vorstand'))),'mittel','tief')) AS `wirksamkeit` from (`v_mandat_simple` `mandat` join `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_mandat_raw`
--
DROP TABLE IF EXISTS `v_mandat_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mandat_raw` AS select `mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mandat`.`wirksamkeit` AS `wirksamkeit`,(case `mandat`.`wirksamkeit` when 'hoch' then 3 when 'mittel' then 2 when 'tief' then 1 else 0 end) AS `wirksamkeit_index`,`organisation`.`lobbyeinfluss` AS `organisation_lobbyeinfluss`,now() AS `refreshed_date` from (`v_mandat_medium_raw` `mandat` join `v_organisation_raw` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_mandat_simple`
--
DROP TABLE IF EXISTS `v_mandat_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mandat_simple` AS select `mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,unix_timestamp(`mandat`.`bis`) AS `bis_unix`,unix_timestamp(`mandat`.`von`) AS `von_unix`,unix_timestamp(`mandat`.`created_date`) AS `created_date_unix`,unix_timestamp(`mandat`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`mandat`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`mandat`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`mandat`.`freigabe_datum`) AS `freigabe_datum_unix` from `mandat`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_mil_grad`
--
DROP TABLE IF EXISTS `v_mil_grad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mil_grad` AS select `mil_grad`.`id` AS `id`,`mil_grad`.`name` AS `name`,`mil_grad`.`name_fr` AS `name_fr`,`mil_grad`.`abkuerzung` AS `abkuerzung`,`mil_grad`.`abkuerzung_fr` AS `abkuerzung_fr`,`mil_grad`.`typ` AS `typ`,`mil_grad`.`ranghoehe` AS `ranghoehe`,`mil_grad`.`anzeigestufe` AS `anzeigestufe`,`mil_grad`.`created_visa` AS `created_visa`,`mil_grad`.`created_date` AS `created_date`,`mil_grad`.`updated_visa` AS `updated_visa`,`mil_grad`.`updated_date` AS `updated_date`,`mil_grad`.`name` AS `name_de`,`mil_grad`.`abkuerzung` AS `abkuerzung_de`,unix_timestamp(`mil_grad`.`created_date`) AS `created_date_unix`,unix_timestamp(`mil_grad`.`updated_date`) AS `updated_date_unix` from `mil_grad` order by `mil_grad`.`ranghoehe`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation`
--
DROP TABLE IF EXISTS `v_organisation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation` AS select `mv_organisation`.`anzeige_name` AS `anzeige_name`,`mv_organisation`.`anzeige_name_de` AS `anzeige_name_de`,`mv_organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`mv_organisation`.`name` AS `name`,`mv_organisation`.`id` AS `id`,`mv_organisation`.`name_de` AS `name_de`,`mv_organisation`.`name_fr` AS `name_fr`,`mv_organisation`.`name_it` AS `name_it`,`mv_organisation`.`uid` AS `uid`,`mv_organisation`.`ort` AS `ort`,`mv_organisation`.`land_id` AS `land_id`,`mv_organisation`.`interessenraum_id` AS `interessenraum_id`,`mv_organisation`.`rechtsform` AS `rechtsform`,`mv_organisation`.`typ` AS `typ`,`mv_organisation`.`vernehmlassung` AS `vernehmlassung`,`mv_organisation`.`interessengruppe_id` AS `interessengruppe_id`,`mv_organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`mv_organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`mv_organisation`.`branche_id` AS `branche_id`,`mv_organisation`.`homepage` AS `homepage`,`mv_organisation`.`handelsregister_url` AS `handelsregister_url`,`mv_organisation`.`twitter_name` AS `twitter_name`,`mv_organisation`.`beschreibung` AS `beschreibung`,`mv_organisation`.`beschreibung_fr` AS `beschreibung_fr`,`mv_organisation`.`adresse_strasse` AS `adresse_strasse`,`mv_organisation`.`adresse_zusatz` AS `adresse_zusatz`,`mv_organisation`.`adresse_plz` AS `adresse_plz`,`mv_organisation`.`notizen` AS `notizen`,`mv_organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_organisation`.`freigabe_visa` AS `freigabe_visa`,`mv_organisation`.`freigabe_datum` AS `freigabe_datum`,`mv_organisation`.`created_visa` AS `created_visa`,`mv_organisation`.`created_date` AS `created_date`,`mv_organisation`.`updated_visa` AS `updated_visa`,`mv_organisation`.`updated_date` AS `updated_date`,`mv_organisation`.`created_date_unix` AS `created_date_unix`,`mv_organisation`.`updated_date_unix` AS `updated_date_unix`,`mv_organisation`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_organisation`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_organisation`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_organisation`.`branche` AS `branche`,`mv_organisation`.`branche_de` AS `branche_de`,`mv_organisation`.`branche_fr` AS `branche_fr`,`mv_organisation`.`interessengruppe` AS `interessengruppe`,`mv_organisation`.`interessengruppe_de` AS `interessengruppe_de`,`mv_organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`mv_organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`mv_organisation`.`interessengruppe_branche_de` AS `interessengruppe_branche_de`,`mv_organisation`.`interessengruppe_branche_fr` AS `interessengruppe_branche_fr`,`mv_organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`mv_organisation`.`interessengruppe2` AS `interessengruppe2`,`mv_organisation`.`interessengruppe2_de` AS `interessengruppe2_de`,`mv_organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`mv_organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`mv_organisation`.`interessengruppe2_branche_de` AS `interessengruppe2_branche_de`,`mv_organisation`.`interessengruppe2_branche_fr` AS `interessengruppe2_branche_fr`,`mv_organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`mv_organisation`.`interessengruppe3` AS `interessengruppe3`,`mv_organisation`.`interessengruppe3_de` AS `interessengruppe3_de`,`mv_organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`mv_organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`mv_organisation`.`interessengruppe3_branche_de` AS `interessengruppe3_branche_de`,`mv_organisation`.`interessengruppe3_branche_fr` AS `interessengruppe3_branche_fr`,`mv_organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`mv_organisation`.`refreshed_date` AS `refreshed_date`,`mv_organisation`.`land` AS `land`,`mv_organisation`.`interessenraum` AS `interessenraum`,`mv_organisation`.`interessenraum_de` AS `interessenraum_de`,`mv_organisation`.`interessenraum_fr` AS `interessenraum_fr`,`mv_organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`mv_organisation`.`jahr` AS `jahr`,`mv_organisation`.`umsatz` AS `umsatz`,`mv_organisation`.`gewinn` AS `gewinn`,`mv_organisation`.`kapital` AS `kapital`,`mv_organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`mv_organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`mv_organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`mv_organisation`.`quelle_url` AS `quelle_url`,`mv_organisation`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`mv_organisation`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`mv_organisation`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`mv_organisation`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`mv_organisation`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`mv_organisation`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`mv_organisation`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`mv_organisation`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`mv_organisation`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`mv_organisation`.`lobbyeinfluss` AS `lobbyeinfluss`,`mv_organisation`.`lobbyeinfluss_index` AS `lobbyeinfluss_index` from `mv_organisation`;

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung` AS select `organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`quelle_url` AS `quelle_url`,`organisation_beziehung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_beziehung`.`quelle` AS `quelle`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_beziehung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_beziehung`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_beziehung`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_beziehung`.`freigabe_visa` AS `freigabe_visa`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date`,unix_timestamp(`organisation_beziehung`.`bis`) AS `bis_unix`,unix_timestamp(`organisation_beziehung`.`von`) AS `von_unix`,unix_timestamp(`organisation_beziehung`.`created_date`) AS `created_date_unix`,unix_timestamp(`organisation_beziehung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`organisation_beziehung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`organisation_beziehung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`organisation_beziehung`.`freigabe_datum`) AS `freigabe_datum_unix` from `organisation_beziehung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_arbeitet_fuer`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_arbeitet_fuer` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_auftraggeber_fuer`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_auftraggeber_fuer` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_mitglieder`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_mitglieder`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_mitglieder` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_mitglied_von`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_mitglied_von`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_mitglied_von` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_muttergesellschaft`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_muttergesellschaft`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_muttergesellschaft` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'tochtergesellschaft von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_tochtergesellschaften`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_tochtergesellschaften`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_tochtergesellschaften` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`von` AS `von`,`organisation_beziehung`.`bis` AS `bis`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`ort` AS `ort` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation_simple` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'tochtergesellschaft von') order by `organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_jahr`
--
DROP TABLE IF EXISTS `v_organisation_jahr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_jahr` AS select `organisation_jahr`.`id` AS `id`,`organisation_jahr`.`organisation_id` AS `organisation_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`organisation_jahr`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_jahr`.`quelle` AS `quelle`,`organisation_jahr`.`notizen` AS `notizen`,`organisation_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_jahr`.`freigabe_visa` AS `freigabe_visa`,`organisation_jahr`.`freigabe_datum` AS `freigabe_datum`,`organisation_jahr`.`created_visa` AS `created_visa`,`organisation_jahr`.`created_date` AS `created_date`,`organisation_jahr`.`updated_visa` AS `updated_visa`,`organisation_jahr`.`updated_date` AS `updated_date`,unix_timestamp(`organisation_jahr`.`created_date`) AS `created_date_unix`,unix_timestamp(`organisation_jahr`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`organisation_jahr`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`organisation_jahr`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`organisation_jahr`.`freigabe_datum`) AS `freigabe_datum_unix` from `organisation_jahr`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_jahr_last`
--
DROP TABLE IF EXISTS `v_organisation_jahr_last`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_jahr_last` AS select max(`organisation_jahr`.`jahr`) AS `max_jahr`,`organisation_jahr`.`id` AS `id`,`organisation_jahr`.`organisation_id` AS `organisation_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`organisation_jahr`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_jahr`.`quelle` AS `quelle`,`organisation_jahr`.`notizen` AS `notizen`,`organisation_jahr`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_jahr`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_jahr`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_jahr`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_jahr`.`freigabe_visa` AS `freigabe_visa`,`organisation_jahr`.`freigabe_datum` AS `freigabe_datum`,`organisation_jahr`.`created_visa` AS `created_visa`,`organisation_jahr`.`created_date` AS `created_date`,`organisation_jahr`.`updated_visa` AS `updated_visa`,`organisation_jahr`.`updated_date` AS `updated_date` from `organisation_jahr` group by `organisation_jahr`.`organisation_id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_lobbyeinfluss_raw`
--
DROP TABLE IF EXISTS `v_organisation_lobbyeinfluss_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_lobbyeinfluss_raw` AS select `organisation`.`id` AS `id`,count(distinct `interessenbindung_tief`.`id`) AS `anzahl_interessenbindung_tief`,count(distinct `interessenbindung_mittel`.`id`) AS `anzahl_interessenbindung_mittel`,count(distinct `interessenbindung_hoch`.`id`) AS `anzahl_interessenbindung_hoch`,count(distinct `interessenbindung_tief_nach_wahl`.`id`) AS `anzahl_interessenbindung_tief_nach_wahl`,count(distinct `interessenbindung_mittel_nach_wahl`.`id`) AS `anzahl_interessenbindung_mittel_nach_wahl`,count(distinct `interessenbindung_hoch_nach_wahl`.`id`) AS `anzahl_interessenbindung_hoch_nach_wahl`,count(distinct `mandat_tief`.`id`) AS `anzahl_mandat_tief`,count(distinct `mandat_mittel`.`id`) AS `anzahl_mandat_mittel`,count(distinct `mandat_hoch`.`id`) AS `anzahl_mandat_hoch`,if(((count(distinct `interessenbindung_hoch_nach_wahl`.`id`) > 0) or (count(distinct `interessenbindung_hoch`.`id`) > 1) or ((count(distinct `interessenbindung_hoch`.`id`) > 0) and (count(distinct `mandat_hoch`.`id`) > 0))),'sehr hoch',if(((count(distinct `interessenbindung_hoch`.`id`) > 0) or ((count(distinct `interessenbindung_mittel`.`id`) > 0) and (count(distinct `mandat_mittel`.`id`) > 0))),'hoch',if(((count(distinct `interessenbindung_mittel`.`id`) > 0) or (count(distinct `mandat_hoch`.`id`) > 0)),'mittel','tief'))) AS `lobbyeinfluss`,now() AS `refreshed_date` from (((((((((`organisation` left join `v_interessenbindung_medium_raw` `interessenbindung_hoch` on(((`organisation`.`id` = `interessenbindung_hoch`.`organisation_id`) and (isnull(`interessenbindung_hoch`.`bis`) or (`interessenbindung_hoch`.`bis` >= now())) and (`interessenbindung_hoch`.`wirksamkeit` = 'hoch')))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel` on(((`organisation`.`id` = `interessenbindung_mittel`.`organisation_id`) and (isnull(`interessenbindung_mittel`.`bis`) or (`interessenbindung_mittel`.`bis` >= now())) and (`interessenbindung_mittel`.`wirksamkeit` = 'mittel')))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief` on(((`organisation`.`id` = `interessenbindung_tief`.`organisation_id`) and (isnull(`interessenbindung_tief`.`bis`) or (`interessenbindung_tief`.`bis` >= now())) and (`interessenbindung_tief`.`wirksamkeit` = 'tief')))) left join `v_interessenbindung_medium_raw` `interessenbindung_hoch_nach_wahl` on(((`organisation`.`id` = `interessenbindung_hoch_nach_wahl`.`organisation_id`) and (isnull(`interessenbindung_hoch_nach_wahl`.`bis`) or (`interessenbindung_hoch_nach_wahl`.`bis` >= now())) and (`interessenbindung_hoch_nach_wahl`.`wirksamkeit` = 'hoch') and (`interessenbindung_hoch_nach_wahl`.`von` > `interessenbindung_hoch_nach_wahl`.`parlamentarier_im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel_nach_wahl` on(((`organisation`.`id` = `interessenbindung_mittel_nach_wahl`.`organisation_id`) and (isnull(`interessenbindung_mittel_nach_wahl`.`bis`) or (`interessenbindung_mittel_nach_wahl`.`bis` >= now())) and (`interessenbindung_mittel_nach_wahl`.`wirksamkeit` = 'mittel') and (`interessenbindung_mittel_nach_wahl`.`von` > `interessenbindung_mittel_nach_wahl`.`parlamentarier_im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief_nach_wahl` on(((`organisation`.`id` = `interessenbindung_tief_nach_wahl`.`organisation_id`) and (isnull(`interessenbindung_tief_nach_wahl`.`bis`) or (`interessenbindung_tief_nach_wahl`.`bis` >= now())) and (`interessenbindung_tief_nach_wahl`.`wirksamkeit` = 'tief') and (`interessenbindung_tief_nach_wahl`.`von` > `interessenbindung_tief_nach_wahl`.`parlamentarier_im_rat_seit`)))) left join `v_mandat_medium_raw` `mandat_hoch` on(((`organisation`.`id` = `mandat_hoch`.`organisation_id`) and (isnull(`mandat_hoch`.`bis`) or (`mandat_hoch`.`bis` >= now())) and (`mandat_hoch`.`wirksamkeit` = 'hoch')))) left join `v_mandat_medium_raw` `mandat_mittel` on(((`organisation`.`id` = `mandat_mittel`.`organisation_id`) and (isnull(`mandat_mittel`.`bis`) or (`mandat_mittel`.`bis` >= now())) and (`mandat_mittel`.`wirksamkeit` = 'mittel')))) left join `v_mandat_medium_raw` `mandat_tief` on(((`organisation`.`id` = `mandat_tief`.`organisation_id`) and (isnull(`mandat_tief`.`bis`) or (`mandat_tief`.`bis` >= now())) and (`mandat_tief`.`wirksamkeit` = 'tief')))) group by `organisation`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_medium_raw`
--
DROP TABLE IF EXISTS `v_organisation_medium_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_medium_raw` AS select `organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`uid` AS `uid`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`beschreibung_fr` AS `beschreibung_fr`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`created_date_unix` AS `created_date_unix`,`organisation`.`updated_date_unix` AS `updated_date_unix`,`organisation`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`organisation`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`organisation`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`branche`.`anzeige_name` AS `branche`,`branche`.`anzeige_name_de` AS `branche_de`,`branche`.`anzeige_name_de` AS `branche_fr`,`interessengruppe1`.`anzeige_name` AS `interessengruppe`,`interessengruppe1`.`anzeige_name_de` AS `interessengruppe_de`,`interessengruppe1`.`anzeige_name_fr` AS `interessengruppe_fr`,`interessengruppe1`.`branche` AS `interessengruppe_branche`,`interessengruppe1`.`branche_de` AS `interessengruppe_branche_de`,`interessengruppe1`.`branche_fr` AS `interessengruppe_branche_fr`,`interessengruppe1`.`branche_id` AS `interessengruppe_branche_id`,`interessengruppe2`.`anzeige_name` AS `interessengruppe2`,`interessengruppe2`.`anzeige_name_de` AS `interessengruppe2_de`,`interessengruppe2`.`anzeige_name_fr` AS `interessengruppe2_fr`,`interessengruppe2`.`branche` AS `interessengruppe2_branche`,`interessengruppe2`.`branche_de` AS `interessengruppe2_branche_de`,`interessengruppe2`.`branche_fr` AS `interessengruppe2_branche_fr`,`interessengruppe2`.`branche_id` AS `interessengruppe2_branche_id`,`interessengruppe3`.`anzeige_name` AS `interessengruppe3`,`interessengruppe3`.`anzeige_name_de` AS `interessengruppe3_de`,`interessengruppe3`.`anzeige_name_fr` AS `interessengruppe3_fr`,`interessengruppe3`.`branche` AS `interessengruppe3_branche`,`interessengruppe3`.`branche_de` AS `interessengruppe3_branche_de`,`interessengruppe3`.`branche_fr` AS `interessengruppe3_branche_fr`,`interessengruppe3`.`branche_id` AS `interessengruppe3_branche_id`,now() AS `refreshed_date` from ((((`v_organisation_simple` `organisation` left join `v_branche` `branche` on((`branche`.`id` = `organisation`.`branche_id`))) left join `v_interessengruppe` `interessengruppe1` on((`interessengruppe1`.`id` = `organisation`.`interessengruppe_id`))) left join `v_interessengruppe` `interessengruppe2` on((`interessengruppe2`.`id` = `organisation`.`interessengruppe2_id`))) left join `v_interessengruppe` `interessengruppe3` on((`interessengruppe3`.`id` = `organisation`.`interessengruppe3_id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier` AS select `parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_interessenbindung_simple` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_beide`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_beide`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_beide` AS select 'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `person_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum` from (`v_interessenbindung_simple` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) union select 'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`person`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`freigabe_datum` AS `freigabe_datum` from (((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` join `v_mandat_simple` `mandat` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) join `v_parlamentarier` `parlamentarier` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_beide_indirekt`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_beide_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_beide_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`verbindung` AS `verbindung`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`parlamentarier_name` AS `parlamentarier_name`,`organisation_parlamentarier`.`ratstyp` AS `ratstyp`,`organisation_parlamentarier`.`kanton` AS `kanton`,`organisation_parlamentarier`.`partei_id` AS `partei_id`,`organisation_parlamentarier`.`partei` AS `partei`,`organisation_parlamentarier`.`kommissionen` AS `kommissionen`,`organisation_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`organisation_parlamentarier`.`person_id` AS `person_id`,`organisation_parlamentarier`.`zutrittsberechtigter` AS `zutrittsberechtigter`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`von` AS `von`,`organisation_parlamentarier`.`bis` AS `bis`,NULL AS `zwischenorganisation_id`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id`,`organisation_parlamentarier`.`freigabe_datum` AS `freigabe_datum` from `v_organisation_parlamentarier_beide` `organisation_parlamentarier` union select 'indirekt' AS `beziehung`,'interessenbindung' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,NULL AS `person_id`,NULL AS `zutrittsberechtigter`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`organisation_beziehung`.`organisation_id` AS `zwischenorganisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum` from ((`v_organisation_beziehung` `organisation_beziehung` join `v_interessenbindung_simple` `interessenbindung` on((`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') union select 'indirekt' AS `beziehung`,'zutritt-mandat' AS `verbindung`,`parlamentarier`.`id` AS `parlamentarier_id`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`person`.`anzeige_name` AS `zutrittsberechtigter`,`mandat`.`art` AS `art`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`organisation_beziehung`.`organisation_id` AS `zwischenorganisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum` from ((((`v_organisation_beziehung` `organisation_beziehung` join `v_mandat` `mandat` on((`organisation_beziehung`.`organisation_id` = `mandat`.`organisation_id`))) join `v_zutrittsberechtigung_simple` `zutrittsberechtigung` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) join `v_parlamentarier` `parlamentarier` on((`zutrittsberechtigung`.`parlamentarier_id` = `parlamentarier`.`id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer');

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_indirekt`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`parlamentarier_name` AS `parlamentarier_name`,`organisation_parlamentarier`.`name` AS `name`,`organisation_parlamentarier`.`nachname` AS `nachname`,`organisation_parlamentarier`.`vorname` AS `vorname`,`organisation_parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`organisation_parlamentarier`.`rat_id` AS `rat_id`,`organisation_parlamentarier`.`kanton_id` AS `kanton_id`,`organisation_parlamentarier`.`kommissionen` AS `kommissionen`,`organisation_parlamentarier`.`partei_id` AS `partei_id`,`organisation_parlamentarier`.`parteifunktion` AS `parteifunktion`,`organisation_parlamentarier`.`fraktion_id` AS `fraktion_id`,`organisation_parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`organisation_parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`organisation_parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`organisation_parlamentarier`.`ratswechsel` AS `ratswechsel`,`organisation_parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`organisation_parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`organisation_parlamentarier`.`beruf` AS `beruf`,`organisation_parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`organisation_parlamentarier`.`zivilstand` AS `zivilstand`,`organisation_parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`organisation_parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`organisation_parlamentarier`.`geschlecht` AS `geschlecht`,`organisation_parlamentarier`.`geburtstag` AS `geburtstag`,`organisation_parlamentarier`.`photo` AS `photo`,`organisation_parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`organisation_parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`organisation_parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`organisation_parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`organisation_parlamentarier`.`kleinbild` AS `kleinbild`,`organisation_parlamentarier`.`sitzplatz` AS `sitzplatz`,`organisation_parlamentarier`.`email` AS `email`,`organisation_parlamentarier`.`homepage` AS `homepage`,`organisation_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`organisation_parlamentarier`.`twitter_name` AS `twitter_name`,`organisation_parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`organisation_parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`organisation_parlamentarier`.`facebook_name` AS `facebook_name`,`organisation_parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`organisation_parlamentarier`.`adresse_firma` AS `adresse_firma`,`organisation_parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`organisation_parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`organisation_parlamentarier`.`adresse_plz` AS `adresse_plz`,`organisation_parlamentarier`.`adresse_ort` AS `adresse_ort`,`organisation_parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`organisation_parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`organisation_parlamentarier`.`kanton` AS `kanton`,`organisation_parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`organisation_parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`organisation_parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`organisation_parlamentarier`.`partei` AS `partei`,`organisation_parlamentarier`.`fraktion` AS `fraktion`,`organisation_parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`organisation_parlamentarier`.`id` AS `id`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`organisation_id` AS `organisation_id`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`funktion_im_gremium` AS `funktion_im_gremium`,`organisation_parlamentarier`.`deklarationstyp` AS `deklarationstyp`,`organisation_parlamentarier`.`status` AS `status`,`organisation_parlamentarier`.`behoerden_vertreter` AS `behoerden_vertreter`,`organisation_parlamentarier`.`verguetung` AS `verguetung`,`organisation_parlamentarier`.`beschreibung` AS `beschreibung`,`organisation_parlamentarier`.`quelle_url` AS `quelle_url`,`organisation_parlamentarier`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`organisation_parlamentarier`.`quelle` AS `quelle`,`organisation_parlamentarier`.`von` AS `von`,`organisation_parlamentarier`.`bis` AS `bis`,`organisation_parlamentarier`.`notizen` AS `notizen`,`organisation_parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation_parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation_parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation_parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation_parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`organisation_parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`organisation_parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`organisation_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`organisation_parlamentarier`.`created_visa` AS `created_visa`,`organisation_parlamentarier`.`created_date` AS `created_date`,`organisation_parlamentarier`.`updated_visa` AS `updated_visa`,`organisation_parlamentarier`.`updated_date` AS `updated_date`,`organisation_parlamentarier`.`bis_unix` AS `bis_unix`,`organisation_parlamentarier`.`von_unix` AS `von_unix`,`organisation_parlamentarier`.`created_date_unix` AS `created_date_unix`,`organisation_parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`organisation_parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`organisation_parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`organisation_parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id` from `v_organisation_parlamentarier` `organisation_parlamentarier` union select 'indirekt' AS `beziehung`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`funktion_im_gremium` AS `funktion_im_gremium`,`interessenbindung`.`deklarationstyp` AS `deklarationstyp`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`behoerden_vertreter` AS `behoerden_vertreter`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`quelle_url` AS `quelle_url`,`interessenbindung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`interessenbindung`.`quelle` AS `quelle`,`interessenbindung`.`von` AS `von`,`interessenbindung`.`bis` AS `bis`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`interessenbindung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`interessenbindung`.`kontrolliert_visa` AS `kontrolliert_visa`,`interessenbindung`.`kontrolliert_datum` AS `kontrolliert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`freigabe_visa` AS `freigabe_visa`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`interessenbindung`.`bis_unix` AS `bis_unix`,`interessenbindung`.`von_unix` AS `von_unix`,`interessenbindung`.`created_date_unix` AS `created_date_unix`,`interessenbindung`.`updated_date_unix` AS `updated_date_unix`,`interessenbindung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`interessenbindung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`interessenbindung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id` from ((`v_organisation_beziehung` `organisation_beziehung` join `v_interessenbindung_simple` `interessenbindung` on((`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`parlamentarier_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_raw`
--
DROP TABLE IF EXISTS `v_organisation_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_raw` AS select `organisation`.`anzeige_name` AS `anzeige_name`,`organisation`.`anzeige_name_de` AS `anzeige_name_de`,`organisation`.`anzeige_name_fr` AS `anzeige_name_fr`,`organisation`.`name` AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`uid` AS `uid`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`beschreibung_fr` AS `beschreibung_fr`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,`organisation`.`created_date_unix` AS `created_date_unix`,`organisation`.`updated_date_unix` AS `updated_date_unix`,`organisation`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`organisation`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`organisation`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`organisation`.`branche` AS `branche`,`organisation`.`branche_de` AS `branche_de`,`organisation`.`branche_fr` AS `branche_fr`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_de` AS `interessengruppe_de`,`organisation`.`interessengruppe_fr` AS `interessengruppe_fr`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_de` AS `interessengruppe_branche_de`,`organisation`.`interessengruppe_branche_fr` AS `interessengruppe_branche_fr`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_de` AS `interessengruppe2_de`,`organisation`.`interessengruppe2_fr` AS `interessengruppe2_fr`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_de` AS `interessengruppe2_branche_de`,`organisation`.`interessengruppe2_branche_fr` AS `interessengruppe2_branche_fr`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_de` AS `interessengruppe3_de`,`organisation`.`interessengruppe3_fr` AS `interessengruppe3_fr`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_de` AS `interessengruppe3_branche_de`,`organisation`.`interessengruppe3_branche_fr` AS `interessengruppe3_branche_fr`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`refreshed_date` AS `refreshed_date`,`country`.`name_de` AS `land`,`interessenraum`.`anzeige_name` AS `interessenraum`,`interessenraum`.`anzeige_name_de` AS `interessenraum_de`,`interessenraum`.`anzeige_name_fr` AS `interessenraum_fr`,`organisation_jahr`.`id` AS `organisation_jahr_id`,`organisation_jahr`.`jahr` AS `jahr`,`organisation_jahr`.`umsatz` AS `umsatz`,`organisation_jahr`.`gewinn` AS `gewinn`,`organisation_jahr`.`kapital` AS `kapital`,`organisation_jahr`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation_jahr`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation_jahr`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`organisation_jahr`.`quelle_url` AS `quelle_url`,`lobbyeinfluss`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`lobbyeinfluss`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`lobbyeinfluss`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`lobbyeinfluss`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`lobbyeinfluss`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`lobbyeinfluss`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`lobbyeinfluss`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`lobbyeinfluss`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`lobbyeinfluss`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`lobbyeinfluss`.`lobbyeinfluss` AS `lobbyeinfluss`,(case `lobbyeinfluss`.`lobbyeinfluss` when 'sehr hoch' then 4 when 'hoch' then 3 when 'mittel' then 2 when 'tief' then 1 else 0 end) AS `lobbyeinfluss_index` from ((((`v_organisation_medium_raw` `organisation` left join `v_organisation_lobbyeinfluss_raw` `lobbyeinfluss` on((`lobbyeinfluss`.`id` = `organisation`.`id`))) left join `v_country` `country` on((`country`.`id` = `organisation`.`land_id`))) left join `v_interessenraum` `interessenraum` on((`interessenraum`.`id` = `organisation`.`interessenraum_id`))) left join `v_organisation_jahr_last` `organisation_jahr` on((`organisation_jahr`.`organisation_id` = `organisation`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_simple`
--
DROP TABLE IF EXISTS `v_organisation_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_simple` AS select concat_ws('; ',`organisation`.`name_de`,`organisation`.`name_fr`,`organisation`.`name_it`) AS `anzeige_name`,`organisation`.`name_de` AS `anzeige_name_de`,`organisation`.`name_fr` AS `anzeige_name_fr`,concat_ws('; ',`organisation`.`name_de`,`organisation`.`name_fr`,`organisation`.`name_it`) AS `name`,`organisation`.`id` AS `id`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`uid` AS `uid`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `beschreibung`,`organisation`.`beschreibung_fr` AS `beschreibung_fr`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`notizen` AS `notizen`,`organisation`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`organisation`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`organisation`.`kontrolliert_visa` AS `kontrolliert_visa`,`organisation`.`kontrolliert_datum` AS `kontrolliert_datum`,`organisation`.`freigabe_visa` AS `freigabe_visa`,`organisation`.`freigabe_datum` AS `freigabe_datum`,`organisation`.`created_visa` AS `created_visa`,`organisation`.`created_date` AS `created_date`,`organisation`.`updated_visa` AS `updated_visa`,`organisation`.`updated_date` AS `updated_date`,unix_timestamp(`organisation`.`created_date`) AS `created_date_unix`,unix_timestamp(`organisation`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`organisation`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`organisation`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`organisation`.`freigabe_datum`) AS `freigabe_datum_unix` from `organisation`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_zutrittsberechtigung`
--
DROP TABLE IF EXISTS `v_organisation_zutrittsberechtigung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_zutrittsberechtigung` AS select `zutrittsberechtigung`.`anzeige_name` AS `anzeige_name`,`zutrittsberechtigung`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`name` AS `name`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`nachname` AS `nachname`,`zutrittsberechtigung`.`vorname` AS `vorname`,`zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`beruf` AS `beruf`,`zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`zutrittsberechtigung`.`partei_id` AS `partei_id`,`zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`zutrittsberechtigung`.`email` AS `email`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`zutrittsberechtigung`.`partei` AS `partei`,`zutrittsberechtigung`.`parlamentarier_name` AS `parlamentarier_name`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (`v_mandat_simple` `mandat` join `v_zutrittsberechtigung` `zutrittsberechtigung` on((`mandat`.`person_id` = `zutrittsberechtigung`.`person_id`))) order by `zutrittsberechtigung`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier`
--
DROP TABLE IF EXISTS `v_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier` AS select `mv_parlamentarier`.`anzeige_name` AS `anzeige_name`,`mv_parlamentarier`.`anzeige_name_de` AS `anzeige_name_de`,`mv_parlamentarier`.`anzeige_name_fr` AS `anzeige_name_fr`,`mv_parlamentarier`.`name` AS `name`,`mv_parlamentarier`.`name_de` AS `name_de`,`mv_parlamentarier`.`name_fr` AS `name_fr`,`mv_parlamentarier`.`id` AS `id`,`mv_parlamentarier`.`nachname` AS `nachname`,`mv_parlamentarier`.`vorname` AS `vorname`,`mv_parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`mv_parlamentarier`.`rat_id` AS `rat_id`,`mv_parlamentarier`.`kanton_id` AS `kanton_id`,`mv_parlamentarier`.`kommissionen` AS `kommissionen`,`mv_parlamentarier`.`partei_id` AS `partei_id`,`mv_parlamentarier`.`parteifunktion` AS `parteifunktion`,`mv_parlamentarier`.`fraktion_id` AS `fraktion_id`,`mv_parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`mv_parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`mv_parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`mv_parlamentarier`.`ratswechsel` AS `ratswechsel`,`mv_parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`mv_parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`mv_parlamentarier`.`beruf` AS `beruf`,`mv_parlamentarier`.`beruf_fr` AS `beruf_fr`,`mv_parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`mv_parlamentarier`.`zivilstand` AS `zivilstand`,`mv_parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`mv_parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`mv_parlamentarier`.`geschlecht` AS `geschlecht`,`mv_parlamentarier`.`geburtstag` AS `geburtstag`,`mv_parlamentarier`.`photo` AS `photo`,`mv_parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`mv_parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`mv_parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`mv_parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`mv_parlamentarier`.`kleinbild` AS `kleinbild`,`mv_parlamentarier`.`sitzplatz` AS `sitzplatz`,`mv_parlamentarier`.`email` AS `email`,`mv_parlamentarier`.`homepage` AS `homepage`,`mv_parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`mv_parlamentarier`.`twitter_name` AS `twitter_name`,`mv_parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`mv_parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`mv_parlamentarier`.`facebook_name` AS `facebook_name`,`mv_parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`mv_parlamentarier`.`adresse_firma` AS `adresse_firma`,`mv_parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`mv_parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`mv_parlamentarier`.`adresse_plz` AS `adresse_plz`,`mv_parlamentarier`.`adresse_ort` AS `adresse_ort`,`mv_parlamentarier`.`telephon_1` AS `telephon_1`,`mv_parlamentarier`.`telephon_2` AS `telephon_2`,`mv_parlamentarier`.`erfasst` AS `erfasst`,`mv_parlamentarier`.`notizen` AS `notizen`,`mv_parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`mv_parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`mv_parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`mv_parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`mv_parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`mv_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`mv_parlamentarier`.`created_visa` AS `created_visa`,`mv_parlamentarier`.`created_date` AS `created_date`,`mv_parlamentarier`.`updated_visa` AS `updated_visa`,`mv_parlamentarier`.`updated_date` AS `updated_date`,`mv_parlamentarier`.`von` AS `von`,`mv_parlamentarier`.`bis` AS `bis`,`mv_parlamentarier`.`geburtstag_unix` AS `geburtstag_unix`,`mv_parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`mv_parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`mv_parlamentarier`.`created_date_unix` AS `created_date_unix`,`mv_parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`mv_parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_parlamentarier`.`von_unix` AS `von_unix`,`mv_parlamentarier`.`bis_unix` AS `bis_unix`,`mv_parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`mv_parlamentarier`.`rat` AS `rat`,`mv_parlamentarier`.`ratstyp_BAD` AS `ratstyp_BAD`,`mv_parlamentarier`.`kanton_abkuerzung_BAD` AS `kanton_abkuerzung_BAD`,`mv_parlamentarier`.`kanton` AS `kanton`,`mv_parlamentarier`.`rat_de` AS `rat_de`,`mv_parlamentarier`.`kanton_name_de` AS `kanton_name_de`,`mv_parlamentarier`.`rat_fr` AS `rat_fr`,`mv_parlamentarier`.`kanton_name_fr` AS `kanton_name_fr`,`mv_parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`mv_parlamentarier`.`kommissionen_namen_de` AS `kommissionen_namen_de`,`mv_parlamentarier`.`kommissionen_namen_fr` AS `kommissionen_namen_fr`,`mv_parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`mv_parlamentarier`.`kommissionen_abkuerzung_de` AS `kommissionen_abkuerzung_de`,`mv_parlamentarier`.`kommissionen_abkuerzung_fr` AS `kommissionen_abkuerzung_fr`,`mv_parlamentarier`.`kommissionen_anzahl` AS `kommissionen_anzahl`,`mv_parlamentarier`.`partei` AS `partei`,`mv_parlamentarier`.`partei_name` AS `partei_name`,`mv_parlamentarier`.`fraktion` AS `fraktion`,`mv_parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`mv_parlamentarier`.`partei_de` AS `partei_de`,`mv_parlamentarier`.`partei_name_de` AS `partei_name_de`,`mv_parlamentarier`.`militaerischer_grad_de` AS `militaerischer_grad_de`,`mv_parlamentarier`.`partei_fr` AS `partei_fr`,`mv_parlamentarier`.`partei_name_fr` AS `partei_name_fr`,`mv_parlamentarier`.`militaerischer_grad_fr` AS `militaerischer_grad_fr`,`mv_parlamentarier`.`beruf_branche_id` AS `beruf_branche_id`,`mv_parlamentarier`.`titel_de` AS `titel_de`,`mv_parlamentarier`.`titel_fr` AS `titel_fr`,`mv_parlamentarier`.`refreshed_date` AS `refreshed_date`,`mv_parlamentarier`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`mv_parlamentarier`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`mv_parlamentarier`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`mv_parlamentarier`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`mv_parlamentarier`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`mv_parlamentarier`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`mv_parlamentarier`.`lobbyfaktor` AS `lobbyfaktor`,`mv_parlamentarier`.`lobbyfaktor_max` AS `lobbyfaktor_max`,`mv_parlamentarier`.`lobbyfaktor_percent_max` AS `lobbyfaktor_percent_max`,`mv_parlamentarier`.`anzahl_interessenbindung_tief_max` AS `anzahl_interessenbindung_tief_max`,`mv_parlamentarier`.`anzahl_interessenbindung_mittel_max` AS `anzahl_interessenbindung_mittel_max`,`mv_parlamentarier`.`anzahl_interessenbindung_hoch_max` AS `anzahl_interessenbindung_hoch_max`,`mv_parlamentarier`.`rat` AS `ratstyp`,`mv_parlamentarier`.`kanton` AS `kanton_abkuerzung` from `mv_parlamentarier`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_anhang`
--
DROP TABLE IF EXISTS `v_parlamentarier_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_anhang` AS select `parlamentarier_anhang`.`parlamentarier_id` AS `parlamentarier_id2`,`parlamentarier_anhang`.`id` AS `id`,`parlamentarier_anhang`.`parlamentarier_id` AS `parlamentarier_id`,`parlamentarier_anhang`.`datei` AS `datei`,`parlamentarier_anhang`.`dateiname` AS `dateiname`,`parlamentarier_anhang`.`dateierweiterung` AS `dateierweiterung`,`parlamentarier_anhang`.`dateiname_voll` AS `dateiname_voll`,`parlamentarier_anhang`.`mime_type` AS `mime_type`,`parlamentarier_anhang`.`encoding` AS `encoding`,`parlamentarier_anhang`.`beschreibung` AS `beschreibung`,`parlamentarier_anhang`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier_anhang`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier_anhang`.`created_visa` AS `created_visa`,`parlamentarier_anhang`.`created_date` AS `created_date`,`parlamentarier_anhang`.`updated_visa` AS `updated_visa`,`parlamentarier_anhang`.`updated_date` AS `updated_date` from `parlamentarier_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_lobbyfaktor_max_raw`
--
DROP TABLE IF EXISTS `v_parlamentarier_lobbyfaktor_max_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_lobbyfaktor_max_raw` AS select 1 AS `id`,max(`lobbyfaktor`.`anzahl_interessenbindung_tief`) AS `anzahl_interessenbindung_tief_max`,max(`lobbyfaktor`.`anzahl_interessenbindung_mittel`) AS `anzahl_interessenbindung_mittel_max`,max(`lobbyfaktor`.`anzahl_interessenbindung_hoch`) AS `anzahl_interessenbindung_hoch_max`,max(`lobbyfaktor`.`lobbyfaktor`) AS `lobbyfaktor_max`,now() AS `refreshed_date` from `v_parlamentarier_lobbyfaktor_raw` `lobbyfaktor`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_lobbyfaktor_raw`
--
DROP TABLE IF EXISTS `v_parlamentarier_lobbyfaktor_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_lobbyfaktor_raw` AS select `parlamentarier`.`id` AS `id`,count(distinct `interessenbindung_tief`.`id`) AS `anzahl_interessenbindung_tief`,count(distinct `interessenbindung_mittel`.`id`) AS `anzahl_interessenbindung_mittel`,count(distinct `interessenbindung_hoch`.`id`) AS `anzahl_interessenbindung_hoch`,count(distinct `interessenbindung_tief_nach_wahl`.`id`) AS `anzahl_interessenbindung_tief_nach_wahl`,count(distinct `interessenbindung_mittel_nach_wahl`.`id`) AS `anzahl_interessenbindung_mittel_nach_wahl`,count(distinct `interessenbindung_hoch_nach_wahl`.`id`) AS `anzahl_interessenbindung_hoch_nach_wahl`,((((count(distinct `interessenbindung_tief`.`id`) * 1) + (count(distinct `interessenbindung_mittel`.`id`) * 5)) + (count(distinct `interessenbindung_hoch`.`id`) * 11)) + (((count(distinct `interessenbindung_tief_nach_wahl`.`id`) * 1) + (count(distinct `interessenbindung_mittel_nach_wahl`.`id`) * 5)) + (count(distinct `interessenbindung_hoch_nach_wahl`.`id`) * 11))) AS `lobbyfaktor`,(((count(distinct `interessenbindung_tief`.`id`) * 1) + (count(distinct `interessenbindung_mittel`.`id`) * 5)) + (count(distinct `interessenbindung_hoch`.`id`) * 11)) AS `lobbyfaktor_einfach`,now() AS `refreshed_date` from ((((((`parlamentarier` left join `v_interessenbindung_medium_raw` `interessenbindung_hoch` on(((`parlamentarier`.`id` = `interessenbindung_hoch`.`parlamentarier_id`) and (isnull(`interessenbindung_hoch`.`bis`) or (`interessenbindung_hoch`.`bis` >= now())) and (`interessenbindung_hoch`.`wirksamkeit` = 'hoch')))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel` on(((`parlamentarier`.`id` = `interessenbindung_mittel`.`parlamentarier_id`) and (isnull(`interessenbindung_mittel`.`bis`) or (`interessenbindung_mittel`.`bis` >= now())) and (`interessenbindung_mittel`.`wirksamkeit` = 'mittel')))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief` on(((`parlamentarier`.`id` = `interessenbindung_tief`.`parlamentarier_id`) and (isnull(`interessenbindung_tief`.`bis`) or (`interessenbindung_tief`.`bis` >= now())) and (`interessenbindung_tief`.`wirksamkeit` = 'tief')))) left join `v_interessenbindung_medium_raw` `interessenbindung_hoch_nach_wahl` on(((`parlamentarier`.`id` = `interessenbindung_hoch_nach_wahl`.`parlamentarier_id`) and (isnull(`interessenbindung_hoch_nach_wahl`.`bis`) or (`interessenbindung_hoch_nach_wahl`.`bis` >= now())) and (`interessenbindung_hoch_nach_wahl`.`wirksamkeit` = 'hoch') and (`interessenbindung_hoch_nach_wahl`.`von` > `parlamentarier`.`im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_mittel_nach_wahl` on(((`parlamentarier`.`id` = `interessenbindung_mittel_nach_wahl`.`parlamentarier_id`) and (isnull(`interessenbindung_mittel_nach_wahl`.`bis`) or (`interessenbindung_mittel_nach_wahl`.`bis` >= now())) and (`interessenbindung_mittel_nach_wahl`.`wirksamkeit` = 'mittel') and (`interessenbindung_mittel_nach_wahl`.`von` > `parlamentarier`.`im_rat_seit`)))) left join `v_interessenbindung_medium_raw` `interessenbindung_tief_nach_wahl` on(((`parlamentarier`.`id` = `interessenbindung_tief_nach_wahl`.`parlamentarier_id`) and (isnull(`interessenbindung_tief_nach_wahl`.`bis`) or (`interessenbindung_tief_nach_wahl`.`bis` >= now())) and (`interessenbindung_tief_nach_wahl`.`wirksamkeit` = 'tief') and (`interessenbindung_tief_nach_wahl`.`von` > `parlamentarier`.`im_rat_seit`)))) group by `parlamentarier`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_medium_raw`
--
DROP TABLE IF EXISTS `v_parlamentarier_medium_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_medium_raw` AS select `parlamentarier`.`anzeige_name` AS `anzeige_name`,`parlamentarier`.`anzeige_name_de` AS `anzeige_name_de`,`parlamentarier`.`anzeige_name_fr` AS `anzeige_name_fr`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`name_de` AS `name_de`,`parlamentarier`.`name_fr` AS `name_fr`,`parlamentarier`.`id` AS `id`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_fr` AS `beruf_fr`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`telephon_1` AS `telephon_1`,`parlamentarier`.`telephon_2` AS `telephon_2`,`parlamentarier`.`erfasst` AS `erfasst`,`parlamentarier`.`notizen` AS `notizen`,`parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`created_visa` AS `created_visa`,`parlamentarier`.`created_date` AS `created_date`,`parlamentarier`.`updated_visa` AS `updated_visa`,`parlamentarier`.`updated_date` AS `updated_date`,`parlamentarier`.`von` AS `von`,`parlamentarier`.`bis` AS `bis`,`parlamentarier`.`geburtstag_unix` AS `geburtstag_unix`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`created_date_unix` AS `created_date_unix`,`parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`parlamentarier`.`von_unix` AS `von_unix`,`parlamentarier`.`bis_unix` AS `bis_unix`,cast((case `rat`.`abkuerzung` when 'SR' then round((`kanton`.`einwohner` / `kanton`.`anzahl_staenderaete`),0) when 'NR' then round((`kanton`.`einwohner` / `kanton`.`anzahl_nationalraete`),0) else NULL end) as unsigned) AS `vertretene_bevoelkerung`,`rat`.`abkuerzung` AS `rat`,`rat`.`abkuerzung` AS `ratstyp`,`kanton`.`abkuerzung` AS `kanton_abkuerzung`,`kanton`.`abkuerzung` AS `kanton`,`rat`.`abkuerzung` AS `rat_de`,`kanton`.`name_de` AS `kanton_name_de`,`rat`.`abkuerzung_fr` AS `rat_fr`,`kanton`.`name_fr` AS `kanton_name_fr`,group_concat(distinct concat(`kommission`.`name`,'(',`kommission`.`abkuerzung`,')') order by `kommission`.`abkuerzung` ASC separator ', ') AS `kommissionen_namen`,group_concat(distinct concat(`kommission`.`name`,'(',`kommission`.`abkuerzung`,')') order by `kommission`.`abkuerzung` ASC separator ', ') AS `kommissionen_namen_de`,group_concat(distinct concat(`kommission`.`name_fr`,'(',`kommission`.`abkuerzung_fr`,')') order by `kommission`.`abkuerzung_fr` ASC separator ', ') AS `kommissionen_namen_fr`,group_concat(distinct `kommission`.`abkuerzung` order by `kommission`.`abkuerzung` ASC separator ', ') AS `kommissionen_abkuerzung`,group_concat(distinct `kommission`.`abkuerzung` order by `kommission`.`abkuerzung` ASC separator ', ') AS `kommissionen_abkuerzung_de`,group_concat(distinct `kommission`.`abkuerzung_fr` order by `kommission`.`abkuerzung_fr` ASC separator ', ') AS `kommissionen_abkuerzung_fr`,count(distinct `kommission`.`id`) AS `kommissionen_anzahl`,`partei`.`abkuerzung` AS `partei`,`partei`.`name` AS `partei_name`,`fraktion`.`abkuerzung` AS `fraktion`,`mil_grad`.`name` AS `militaerischer_grad`,`partei`.`abkuerzung` AS `partei_de`,`partei`.`name` AS `partei_name_de`,`mil_grad`.`name` AS `militaerischer_grad_de`,`partei`.`abkuerzung_fr` AS `partei_fr`,`partei`.`name_fr` AS `partei_name_fr`,`mil_grad`.`name_fr` AS `militaerischer_grad_fr`,`interessengruppe`.`branche_id` AS `beruf_branche_id`,concat(if((`parlamentarier`.`geschlecht` = 'M'),`rat`.`mitglied_bezeichnung_maennlich_de`,''),if((`parlamentarier`.`geschlecht` = 'F'),`rat`.`mitglied_bezeichnung_weiblich_de`,'')) AS `titel_de`,concat(if((`parlamentarier`.`geschlecht` = 'M'),`rat`.`mitglied_bezeichnung_maennlich_fr`,''),if((`parlamentarier`.`geschlecht` = 'F'),`rat`.`mitglied_bezeichnung_weiblich_fr`,'')) AS `titel_fr`,now() AS `refreshed_date` from ((((((((`v_parlamentarier_simple` `parlamentarier` left join `in_kommission` on(((`parlamentarier`.`id` = `in_kommission`.`parlamentarier_id`) and isnull(`in_kommission`.`bis`)))) left join `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) left join `v_partei` `partei` on((`parlamentarier`.`partei_id` = `partei`.`id`))) left join `v_fraktion` `fraktion` on((`parlamentarier`.`fraktion_id` = `fraktion`.`id`))) left join `v_mil_grad` `mil_grad` on((`parlamentarier`.`militaerischer_grad_id` = `mil_grad`.`id`))) left join `v_kanton` `kanton` on((`parlamentarier`.`kanton_id` = `kanton`.`id`))) left join `v_rat` `rat` on((`parlamentarier`.`rat_id` = `rat`.`id`))) left join `v_interessengruppe` `interessengruppe` on((`parlamentarier`.`beruf_interessengruppe_id` = `interessengruppe`.`id`))) group by `parlamentarier`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_raw`
--
DROP TABLE IF EXISTS `v_parlamentarier_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_raw` AS select `parlamentarier`.`anzeige_name` AS `anzeige_name`,`parlamentarier`.`anzeige_name_de` AS `anzeige_name_de`,`parlamentarier`.`anzeige_name_fr` AS `anzeige_name_fr`,`parlamentarier`.`name` AS `name`,`parlamentarier`.`name_de` AS `name_de`,`parlamentarier`.`name_fr` AS `name_fr`,`parlamentarier`.`id` AS `id`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_fr` AS `beruf_fr`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`telephon_1` AS `telephon_1`,`parlamentarier`.`telephon_2` AS `telephon_2`,`parlamentarier`.`erfasst` AS `erfasst`,`parlamentarier`.`notizen` AS `notizen`,`parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`created_visa` AS `created_visa`,`parlamentarier`.`created_date` AS `created_date`,`parlamentarier`.`updated_visa` AS `updated_visa`,`parlamentarier`.`updated_date` AS `updated_date`,`parlamentarier`.`von` AS `von`,`parlamentarier`.`bis` AS `bis`,`parlamentarier`.`geburtstag_unix` AS `geburtstag_unix`,`parlamentarier`.`im_rat_seit_unix` AS `im_rat_seit_unix`,`parlamentarier`.`im_rat_bis_unix` AS `im_rat_bis_unix`,`parlamentarier`.`created_date_unix` AS `created_date_unix`,`parlamentarier`.`updated_date_unix` AS `updated_date_unix`,`parlamentarier`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`parlamentarier`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`parlamentarier`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`parlamentarier`.`von_unix` AS `von_unix`,`parlamentarier`.`bis_unix` AS `bis_unix`,`parlamentarier`.`vertretene_bevoelkerung` AS `vertretene_bevoelkerung`,`parlamentarier`.`rat` AS `rat`,`parlamentarier`.`ratstyp` AS `ratstyp`,`parlamentarier`.`kanton_abkuerzung` AS `kanton_abkuerzung`,`parlamentarier`.`kanton` AS `kanton`,`parlamentarier`.`rat_de` AS `rat_de`,`parlamentarier`.`kanton_name_de` AS `kanton_name_de`,`parlamentarier`.`rat_fr` AS `rat_fr`,`parlamentarier`.`kanton_name_fr` AS `kanton_name_fr`,`parlamentarier`.`kommissionen_namen` AS `kommissionen_namen`,`parlamentarier`.`kommissionen_namen_de` AS `kommissionen_namen_de`,`parlamentarier`.`kommissionen_namen_fr` AS `kommissionen_namen_fr`,`parlamentarier`.`kommissionen_abkuerzung` AS `kommissionen_abkuerzung`,`parlamentarier`.`kommissionen_abkuerzung_de` AS `kommissionen_abkuerzung_de`,`parlamentarier`.`kommissionen_abkuerzung_fr` AS `kommissionen_abkuerzung_fr`,`parlamentarier`.`kommissionen_anzahl` AS `kommissionen_anzahl`,`parlamentarier`.`partei` AS `partei`,`parlamentarier`.`partei_name` AS `partei_name`,`parlamentarier`.`fraktion` AS `fraktion`,`parlamentarier`.`militaerischer_grad` AS `militaerischer_grad`,`parlamentarier`.`partei_de` AS `partei_de`,`parlamentarier`.`partei_name_de` AS `partei_name_de`,`parlamentarier`.`militaerischer_grad_de` AS `militaerischer_grad_de`,`parlamentarier`.`partei_fr` AS `partei_fr`,`parlamentarier`.`partei_name_fr` AS `partei_name_fr`,`parlamentarier`.`militaerischer_grad_fr` AS `militaerischer_grad_fr`,`parlamentarier`.`beruf_branche_id` AS `beruf_branche_id`,`parlamentarier`.`titel_de` AS `titel_de`,`parlamentarier`.`titel_fr` AS `titel_fr`,`parlamentarier`.`refreshed_date` AS `refreshed_date`,`lobbyfaktor`.`anzahl_interessenbindung_tief` AS `anzahl_interessenbindung_tief`,`lobbyfaktor`.`anzahl_interessenbindung_mittel` AS `anzahl_interessenbindung_mittel`,`lobbyfaktor`.`anzahl_interessenbindung_hoch` AS `anzahl_interessenbindung_hoch`,`lobbyfaktor`.`anzahl_interessenbindung_tief_nach_wahl` AS `anzahl_interessenbindung_tief_nach_wahl`,`lobbyfaktor`.`anzahl_interessenbindung_mittel_nach_wahl` AS `anzahl_interessenbindung_mittel_nach_wahl`,`lobbyfaktor`.`anzahl_interessenbindung_hoch_nach_wahl` AS `anzahl_interessenbindung_hoch_nach_wahl`,`lobbyfaktor`.`lobbyfaktor` AS `lobbyfaktor`,`lobbyfaktor_max`.`lobbyfaktor_max` AS `lobbyfaktor_max`,(`lobbyfaktor`.`lobbyfaktor` / `lobbyfaktor_max`.`lobbyfaktor_max`) AS `lobbyfaktor_percent_max`,`lobbyfaktor_max`.`anzahl_interessenbindung_tief_max` AS `anzahl_interessenbindung_tief_max`,`lobbyfaktor_max`.`anzahl_interessenbindung_mittel_max` AS `anzahl_interessenbindung_mittel_max`,`lobbyfaktor_max`.`anzahl_interessenbindung_hoch_max` AS `anzahl_interessenbindung_hoch_max` from ((`v_parlamentarier_medium_raw` `parlamentarier` left join `v_parlamentarier_lobbyfaktor_raw` `lobbyfaktor` on((`parlamentarier`.`id` = `lobbyfaktor`.`id`))) join `v_parlamentarier_lobbyfaktor_max_raw` `lobbyfaktor_max`) group by `parlamentarier`.`id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_simple`
--
DROP TABLE IF EXISTS `v_parlamentarier_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_simple` AS select concat(`parlamentarier`.`nachname`,', ',`parlamentarier`.`vorname`) AS `anzeige_name`,concat(`parlamentarier`.`nachname`,', ',`parlamentarier`.`vorname`) AS `anzeige_name_de`,concat(`parlamentarier`.`nachname`,', ',`parlamentarier`.`vorname`) AS `anzeige_name_fr`,concat_ws(' ',`parlamentarier`.`vorname`,`parlamentarier`.`zweiter_vorname`,`parlamentarier`.`nachname`) AS `name`,concat_ws(' ',`parlamentarier`.`vorname`,`parlamentarier`.`zweiter_vorname`,`parlamentarier`.`nachname`) AS `name_de`,concat_ws(' ',`parlamentarier`.`vorname`,`parlamentarier`.`zweiter_vorname`,`parlamentarier`.`nachname`) AS `name_fr`,`parlamentarier`.`id` AS `id`,`parlamentarier`.`nachname` AS `nachname`,`parlamentarier`.`vorname` AS `vorname`,`parlamentarier`.`zweiter_vorname` AS `zweiter_vorname`,`parlamentarier`.`rat_id` AS `rat_id`,`parlamentarier`.`kanton_id` AS `kanton_id`,`parlamentarier`.`kommissionen` AS `kommissionen`,`parlamentarier`.`partei_id` AS `partei_id`,`parlamentarier`.`parteifunktion` AS `parteifunktion`,`parlamentarier`.`fraktion_id` AS `fraktion_id`,`parlamentarier`.`fraktionsfunktion` AS `fraktionsfunktion`,`parlamentarier`.`im_rat_seit` AS `im_rat_seit`,`parlamentarier`.`im_rat_bis` AS `im_rat_bis`,`parlamentarier`.`ratswechsel` AS `ratswechsel`,`parlamentarier`.`ratsunterbruch_von` AS `ratsunterbruch_von`,`parlamentarier`.`ratsunterbruch_bis` AS `ratsunterbruch_bis`,`parlamentarier`.`beruf` AS `beruf`,`parlamentarier`.`beruf_fr` AS `beruf_fr`,`parlamentarier`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`parlamentarier`.`zivilstand` AS `zivilstand`,`parlamentarier`.`anzahl_kinder` AS `anzahl_kinder`,`parlamentarier`.`militaerischer_grad_id` AS `militaerischer_grad_id`,`parlamentarier`.`geschlecht` AS `geschlecht`,`parlamentarier`.`geburtstag` AS `geburtstag`,`parlamentarier`.`photo` AS `photo`,`parlamentarier`.`photo_dateiname` AS `photo_dateiname`,`parlamentarier`.`photo_dateierweiterung` AS `photo_dateierweiterung`,`parlamentarier`.`photo_dateiname_voll` AS `photo_dateiname_voll`,`parlamentarier`.`photo_mime_type` AS `photo_mime_type`,`parlamentarier`.`kleinbild` AS `kleinbild`,`parlamentarier`.`sitzplatz` AS `sitzplatz`,`parlamentarier`.`email` AS `email`,`parlamentarier`.`homepage` AS `homepage`,`parlamentarier`.`parlament_biografie_id` AS `parlament_biografie_id`,`parlamentarier`.`twitter_name` AS `twitter_name`,`parlamentarier`.`linkedin_profil_url` AS `linkedin_profil_url`,`parlamentarier`.`xing_profil_name` AS `xing_profil_name`,`parlamentarier`.`facebook_name` AS `facebook_name`,`parlamentarier`.`arbeitssprache` AS `arbeitssprache`,`parlamentarier`.`adresse_firma` AS `adresse_firma`,`parlamentarier`.`adresse_strasse` AS `adresse_strasse`,`parlamentarier`.`adresse_zusatz` AS `adresse_zusatz`,`parlamentarier`.`adresse_plz` AS `adresse_plz`,`parlamentarier`.`adresse_ort` AS `adresse_ort`,`parlamentarier`.`telephon_1` AS `telephon_1`,`parlamentarier`.`telephon_2` AS `telephon_2`,`parlamentarier`.`erfasst` AS `erfasst`,`parlamentarier`.`notizen` AS `notizen`,`parlamentarier`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`parlamentarier`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`parlamentarier`.`kontrolliert_visa` AS `kontrolliert_visa`,`parlamentarier`.`kontrolliert_datum` AS `kontrolliert_datum`,`parlamentarier`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`parlamentarier`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`parlamentarier`.`freigabe_visa` AS `freigabe_visa`,`parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`parlamentarier`.`created_visa` AS `created_visa`,`parlamentarier`.`created_date` AS `created_date`,`parlamentarier`.`updated_visa` AS `updated_visa`,`parlamentarier`.`updated_date` AS `updated_date`,`parlamentarier`.`im_rat_seit` AS `von`,`parlamentarier`.`im_rat_bis` AS `bis`,unix_timestamp(`parlamentarier`.`geburtstag`) AS `geburtstag_unix`,unix_timestamp(`parlamentarier`.`im_rat_seit`) AS `im_rat_seit_unix`,unix_timestamp(`parlamentarier`.`im_rat_bis`) AS `im_rat_bis_unix`,unix_timestamp(`parlamentarier`.`created_date`) AS `created_date_unix`,unix_timestamp(`parlamentarier`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`parlamentarier`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`parlamentarier`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`parlamentarier`.`freigabe_datum`) AS `freigabe_datum_unix`,unix_timestamp(`parlamentarier`.`im_rat_seit`) AS `von_unix`,unix_timestamp(`parlamentarier`.`im_rat_bis`) AS `bis_unix` from `parlamentarier`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_partei`
--
DROP TABLE IF EXISTS `v_partei`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_partei` AS select concat(`partei`.`name`,' (',`partei`.`abkuerzung`,')') AS `anzeige_name`,concat(`partei`.`name`,' (',`partei`.`abkuerzung`,')') AS `anzeige_name_de`,concat(`partei`.`name_fr`,' (',`partei`.`abkuerzung_fr`,')') AS `anzeige_name_fr`,`partei`.`id` AS `id`,`partei`.`abkuerzung` AS `abkuerzung`,`partei`.`abkuerzung_fr` AS `abkuerzung_fr`,`partei`.`name` AS `name`,`partei`.`name_fr` AS `name_fr`,`partei`.`fraktion_id` AS `fraktion_id`,`partei`.`gruendung` AS `gruendung`,`partei`.`position` AS `position`,`partei`.`farbcode` AS `farbcode`,`partei`.`homepage` AS `homepage`,`partei`.`homepage_fr` AS `homepage_fr`,`partei`.`email` AS `email`,`partei`.`email_fr` AS `email_fr`,`partei`.`twitter_name` AS `twitter_name`,`partei`.`twitter_name_fr` AS `twitter_name_fr`,`partei`.`beschreibung` AS `beschreibung`,`partei`.`beschreibung_fr` AS `beschreibung_fr`,`partei`.`notizen` AS `notizen`,`partei`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`partei`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`partei`.`kontrolliert_visa` AS `kontrolliert_visa`,`partei`.`kontrolliert_datum` AS `kontrolliert_datum`,`partei`.`freigabe_visa` AS `freigabe_visa`,`partei`.`freigabe_datum` AS `freigabe_datum`,`partei`.`created_visa` AS `created_visa`,`partei`.`created_date` AS `created_date`,`partei`.`updated_visa` AS `updated_visa`,`partei`.`updated_date` AS `updated_date`,`partei`.`name` AS `name_de`,`partei`.`abkuerzung` AS `abkuerzung_de`,`partei`.`beschreibung` AS `beschreibung_de`,`partei`.`homepage` AS `homepage_de`,`partei`.`twitter_name` AS `twitter_name_de`,`partei`.`email` AS `email_de`,unix_timestamp(`partei`.`created_date`) AS `created_date_unix`,unix_timestamp(`partei`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`partei`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`partei`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`partei`.`freigabe_datum`) AS `freigabe_datum_unix` from `partei`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_person`
--
DROP TABLE IF EXISTS `v_person`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_person` AS select `person`.`anzeige_name` AS `anzeige_name`,`person`.`anzeige_name_de` AS `anzeige_name_de`,`person`.`anzeige_name_fr` AS `anzeige_name_fr`,`person`.`name` AS `name`,`person`.`name_de` AS `name_de`,`person`.`name_fr` AS `name_fr`,`person`.`id` AS `id`,`person`.`nachname` AS `nachname`,`person`.`vorname` AS `vorname`,`person`.`zweiter_vorname` AS `zweiter_vorname`,`person`.`beschreibung_de` AS `beschreibung_de`,`person`.`beschreibung_fr` AS `beschreibung_fr`,`person`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`person`.`beruf` AS `beruf`,`person`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`person`.`partei_id` AS `partei_id`,`person`.`geschlecht` AS `geschlecht`,`person`.`arbeitssprache` AS `arbeitssprache`,`person`.`email` AS `email`,`person`.`homepage` AS `homepage`,`person`.`twitter_name` AS `twitter_name`,`person`.`linkedin_profil_url` AS `linkedin_profil_url`,`person`.`xing_profil_name` AS `xing_profil_name`,`person`.`facebook_name` AS `facebook_name`,`person`.`telephon_1` AS `telephon_1`,`person`.`telephon_2` AS `telephon_2`,`person`.`erfasst` AS `erfasst`,`person`.`notizen` AS `notizen`,`person`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`person`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`person`.`kontrolliert_visa` AS `kontrolliert_visa`,`person`.`kontrolliert_datum` AS `kontrolliert_datum`,`person`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`person`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`person`.`autorisiert_visa` AS `autorisiert_visa`,`person`.`autorisiert_datum` AS `autorisiert_datum`,`person`.`freigabe_visa` AS `freigabe_visa`,`person`.`freigabe_datum` AS `freigabe_datum`,`person`.`created_visa` AS `created_visa`,`person`.`created_date` AS `created_date`,`person`.`updated_visa` AS `updated_visa`,`person`.`updated_date` AS `updated_date`,`person`.`created_date_unix` AS `created_date_unix`,`person`.`updated_date_unix` AS `updated_date_unix`,`person`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`person`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`person`.`freigabe_datum_unix` AS `freigabe_datum_unix` from `v_person_simple` `person`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_person_anhang`
--
DROP TABLE IF EXISTS `v_person_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_person_anhang` AS select `person_anhang`.`person_id` AS `person_id2`,`person_anhang`.`id` AS `id`,`person_anhang`.`person_id` AS `person_id`,`person_anhang`.`datei` AS `datei`,`person_anhang`.`dateiname` AS `dateiname`,`person_anhang`.`dateierweiterung` AS `dateierweiterung`,`person_anhang`.`dateiname_voll` AS `dateiname_voll`,`person_anhang`.`mime_type` AS `mime_type`,`person_anhang`.`encoding` AS `encoding`,`person_anhang`.`beschreibung` AS `beschreibung`,`person_anhang`.`freigabe_visa` AS `freigabe_visa`,`person_anhang`.`freigabe_datum` AS `freigabe_datum`,`person_anhang`.`created_visa` AS `created_visa`,`person_anhang`.`created_date` AS `created_date`,`person_anhang`.`updated_visa` AS `updated_visa`,`person_anhang`.`updated_date` AS `updated_date` from `person_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_person_simple`
--
DROP TABLE IF EXISTS `v_person_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_person_simple` AS select concat(`person`.`nachname`,', ',`person`.`vorname`) AS `anzeige_name`,concat(`person`.`nachname`,', ',`person`.`vorname`) AS `anzeige_name_de`,concat(`person`.`nachname`,', ',`person`.`vorname`) AS `anzeige_name_fr`,concat(`person`.`vorname`,' ',`person`.`nachname`) AS `name`,concat(`person`.`vorname`,' ',`person`.`nachname`) AS `name_de`,concat(`person`.`vorname`,' ',`person`.`nachname`) AS `name_fr`,`person`.`id` AS `id`,`person`.`nachname` AS `nachname`,`person`.`vorname` AS `vorname`,`person`.`zweiter_vorname` AS `zweiter_vorname`,`person`.`beschreibung_de` AS `beschreibung_de`,`person`.`beschreibung_fr` AS `beschreibung_fr`,`person`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`person`.`beruf` AS `beruf`,`person`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`person`.`partei_id` AS `partei_id`,`person`.`geschlecht` AS `geschlecht`,`person`.`arbeitssprache` AS `arbeitssprache`,`person`.`email` AS `email`,`person`.`homepage` AS `homepage`,`person`.`twitter_name` AS `twitter_name`,`person`.`linkedin_profil_url` AS `linkedin_profil_url`,`person`.`xing_profil_name` AS `xing_profil_name`,`person`.`facebook_name` AS `facebook_name`,`person`.`telephon_1` AS `telephon_1`,`person`.`telephon_2` AS `telephon_2`,`person`.`erfasst` AS `erfasst`,`person`.`notizen` AS `notizen`,`person`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`person`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`person`.`kontrolliert_visa` AS `kontrolliert_visa`,`person`.`kontrolliert_datum` AS `kontrolliert_datum`,`person`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`person`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`person`.`autorisiert_visa` AS `autorisiert_visa`,`person`.`autorisiert_datum` AS `autorisiert_datum`,`person`.`freigabe_visa` AS `freigabe_visa`,`person`.`freigabe_datum` AS `freigabe_datum`,`person`.`created_visa` AS `created_visa`,`person`.`created_date` AS `created_date`,`person`.`updated_visa` AS `updated_visa`,`person`.`updated_date` AS `updated_date`,unix_timestamp(`person`.`created_date`) AS `created_date_unix`,unix_timestamp(`person`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`person`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`person`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`person`.`freigabe_datum`) AS `freigabe_datum_unix` from `person`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_rat`
--
DROP TABLE IF EXISTS `v_rat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rat` AS select `rat`.`name_de` AS `anzeige_name`,`rat`.`name_de` AS `anzeige_name_de`,`rat`.`name_de` AS `anzeige_name_fr`,`rat`.`id` AS `id`,`rat`.`abkuerzung` AS `abkuerzung`,`rat`.`abkuerzung_fr` AS `abkuerzung_fr`,`rat`.`name_de` AS `name_de`,`rat`.`name_fr` AS `name_fr`,`rat`.`name_it` AS `name_it`,`rat`.`name_en` AS `name_en`,`rat`.`anzahl_mitglieder` AS `anzahl_mitglieder`,`rat`.`typ` AS `typ`,`rat`.`interessenraum_id` AS `interessenraum_id`,`rat`.`anzeigestufe` AS `anzeigestufe`,`rat`.`gewicht` AS `gewicht`,`rat`.`beschreibung` AS `beschreibung`,`rat`.`homepage_de` AS `homepage_de`,`rat`.`homepage_fr` AS `homepage_fr`,`rat`.`homepage_it` AS `homepage_it`,`rat`.`homepage_en` AS `homepage_en`,`rat`.`mitglied_bezeichnung_maennlich_de` AS `mitglied_bezeichnung_maennlich_de`,`rat`.`mitglied_bezeichnung_weiblich_de` AS `mitglied_bezeichnung_weiblich_de`,`rat`.`mitglied_bezeichnung_maennlich_fr` AS `mitglied_bezeichnung_maennlich_fr`,`rat`.`mitglied_bezeichnung_weiblich_fr` AS `mitglied_bezeichnung_weiblich_fr`,`rat`.`notizen` AS `notizen`,`rat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`rat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`rat`.`kontrolliert_visa` AS `kontrolliert_visa`,`rat`.`kontrolliert_datum` AS `kontrolliert_datum`,`rat`.`freigabe_visa` AS `freigabe_visa`,`rat`.`freigabe_datum` AS `freigabe_datum`,`rat`.`created_visa` AS `created_visa`,`rat`.`created_date` AS `created_date`,`rat`.`updated_visa` AS `updated_visa`,`rat`.`updated_date` AS `updated_date`,unix_timestamp(`rat`.`created_date`) AS `created_date_unix`,unix_timestamp(`rat`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`rat`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`rat`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`rat`.`freigabe_datum`) AS `freigabe_datum_unix` from `rat` order by `rat`.`gewicht`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_search_table`
--
DROP TABLE IF EXISTS `v_search_table`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_search_table` AS select `mv_search_table`.`id` AS `id`,`mv_search_table`.`table_name` AS `table_name`,`mv_search_table`.`page` AS `page`,`mv_search_table`.`table_weight` AS `table_weight`,`mv_search_table`.`name_de` AS `name_de`,`mv_search_table`.`name_fr` AS `name_fr`,`mv_search_table`.`search_keywords_de` AS `search_keywords_de`,`mv_search_table`.`search_keywords_fr` AS `search_keywords_fr`,`mv_search_table`.`freigabe_datum` AS `freigabe_datum`,`mv_search_table`.`bis` AS `bis`,`mv_search_table`.`weight` AS `weight`,`mv_search_table`.`refreshed_date` AS `refreshed_date` from `mv_search_table`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_search_table_raw`
--
DROP TABLE IF EXISTS `v_search_table_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_search_table_raw` AS select `v_parlamentarier`.`id` AS `id`,'parlamentarier' AS `table_name`,'parlamentarier' AS `page`,-(20) AS `table_weight`,concat_ws(', ',`v_parlamentarier`.`anzeige_name`,`v_parlamentarier`.`rat_de`,`v_parlamentarier`.`partei_de`,`v_parlamentarier`.`kanton`) AS `name_de`,concat_ws(', ',`v_parlamentarier`.`anzeige_name`,`v_parlamentarier`.`rat_fr`,`v_parlamentarier`.`partei_fr`,`v_parlamentarier`.`kanton`) AS `name_fr`,concat_ws('; ',`v_parlamentarier`.`anzeige_name`,concat(`v_parlamentarier`.`vorname`,' ',`v_parlamentarier`.`nachname`),concat(`v_parlamentarier`.`vorname`,' ',`v_parlamentarier`.`zweiter_vorname`,' ',`v_parlamentarier`.`nachname`)) AS `search_keywords_de`,concat_ws('; ',`v_parlamentarier`.`anzeige_name`,concat(`v_parlamentarier`.`vorname`,' ',`v_parlamentarier`.`nachname`),concat(`v_parlamentarier`.`vorname`,' ',`v_parlamentarier`.`zweiter_vorname`,' ',`v_parlamentarier`.`nachname`)) AS `search_keywords_fr`,`v_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`v_parlamentarier`.`im_rat_bis` AS `bis`,-(`v_parlamentarier`.`lobbyfaktor`) AS `weight`,now() AS `refreshed_date` from `v_parlamentarier` union all select `v_zutrittsberechtigung`.`id` AS `id`,'zutrittsberechtigung' AS `table_name`,'zutrittsberechtigter' AS `page`,-(15) AS `table_weight`,`v_zutrittsberechtigung`.`anzeige_name` AS `name_de`,`v_zutrittsberechtigung`.`anzeige_name` AS `name_fr`,`v_zutrittsberechtigung`.`anzeige_name` AS `search_keywords_de`,`v_zutrittsberechtigung`.`anzeige_name` AS `search_keywords_fr`,`v_zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`v_zutrittsberechtigung`.`bis` AS `bis`,-(`v_zutrittsberechtigung`.`lobbyfaktor`) AS `weight`,now() AS `refreshed_date` from `v_zutrittsberechtigung` where (isnull(`v_zutrittsberechtigung`.`bis`) or (`v_zutrittsberechtigung`.`bis` > now())) union all select `v_branche`.`id` AS `id`,'branche' AS `table_name`,'branche' AS `page`,-(10) AS `table_weight`,`v_branche`.`anzeige_name_de` AS `name_de`,`v_branche`.`anzeige_name_fr` AS `name_fr`,`v_branche`.`anzeige_name_de` AS `search_keywords_de`,`v_branche`.`anzeige_name_fr` AS `search_keywords_fr`,`v_branche`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_branche` union all select `v_interessengruppe`.`id` AS `id`,'interessengruppe' AS `table_name`,'lobbygruppe' AS `page`,-(5) AS `table_weight`,`v_interessengruppe`.`anzeige_name_de` AS `name_de`,`v_interessengruppe`.`anzeige_name_fr` AS `name_fr`,concat_ws('; ',`v_interessengruppe`.`anzeige_name_de`,`v_interessengruppe`.`alias_namen`) AS `search_keywords_de`,concat_ws('; ',`v_interessengruppe`.`anzeige_name_fr`,`v_interessengruppe`.`alias_namen_fr`) AS `search_keywords_fr`,`v_interessengruppe`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_interessengruppe` union all select `v_kommission`.`id` AS `id`,'kommission' AS `table_name`,'kommission' AS `page`,0 AS `table_weight`,`v_kommission`.`anzeige_name_de` AS `name_de`,`v_kommission`.`anzeige_name_fr` AS `name_fr`,`v_kommission`.`anzeige_name_de` AS `search_keywords_de`,`v_kommission`.`anzeige_name_fr` AS `search_keywords_fr`,`v_kommission`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_kommission` union all select `v_organisation`.`id` AS `id`,'organisation' AS `table_name`,'organisation' AS `page`,15 AS `table_weight`,`v_organisation`.`anzeige_name_de` AS `name_de`,ifnull(`v_organisation`.`anzeige_name_fr`,`v_organisation`.`anzeige_name_de`) AS `name_fr`,`v_organisation`.`anzeige_name_de` AS `search_keywords_de`,ifnull(`v_organisation`.`anzeige_name_fr`,`v_organisation`.`anzeige_name_de`) AS `search_keywords_fr`,`v_organisation`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,-(`v_organisation`.`lobbyeinfluss_index`) AS `weight`,now() AS `refreshed_date` from `v_organisation` union all select `v_partei`.`id` AS `id`,'partei' AS `table_name`,'partei' AS `page`,20 AS `table_weight`,`v_partei`.`anzeige_name_de` AS `name_de`,`v_partei`.`anzeige_name_fr` AS `name_fr`,`v_partei`.`anzeige_name_de` AS `search_keywords_de`,`v_partei`.`anzeige_name_fr` AS `search_keywords_fr`,`v_partei`.`freigabe_datum` AS `freigabe_datum`,NULL AS `bis`,0 AS `weight`,now() AS `refreshed_date` from `v_partei`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_settings`
--
DROP TABLE IF EXISTS `v_settings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_settings` AS select `settings`.`id` AS `id`,`settings`.`key_name` AS `key_name`,`settings`.`value` AS `value`,`settings`.`description` AS `description`,`settings`.`category_id` AS `category_id`,`settings`.`notizen` AS `notizen`,`settings`.`created_visa` AS `created_visa`,`settings`.`created_date` AS `created_date`,`settings`.`updated_visa` AS `updated_visa`,`settings`.`updated_date` AS `updated_date`,`settings_category`.`name` AS `category_name`,unix_timestamp(`settings`.`created_date`) AS `created_date_unix`,unix_timestamp(`settings`.`updated_date`) AS `updated_date_unix` from (`settings` left join `v_settings_category` `settings_category` on((`settings`.`category_id` = `settings_category`.`id`)));

-- --------------------------------------------------------

--
-- Struktur des Views `v_settings_category`
--
DROP TABLE IF EXISTS `v_settings_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_settings_category` AS select `settings_category`.`id` AS `id`,`settings_category`.`name` AS `name`,`settings_category`.`description` AS `description`,`settings_category`.`notizen` AS `notizen`,`settings_category`.`created_visa` AS `created_visa`,`settings_category`.`created_date` AS `created_date`,`settings_category`.`updated_visa` AS `updated_visa`,`settings_category`.`updated_date` AS `updated_date`,unix_timestamp(`settings_category`.`created_date`) AS `created_date_unix`,unix_timestamp(`settings_category`.`updated_date`) AS `updated_date_unix` from `settings_category`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_user`
--
DROP TABLE IF EXISTS `v_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user` AS select ifnull(concat_ws(' ',`u`.`vorname`,`u`.`nachname`),`u`.`name`) AS `anzeige_name`,`u`.`name` AS `username`,`u`.`id` AS `id`,`u`.`name` AS `name`,`u`.`password` AS `password`,`u`.`nachname` AS `nachname`,`u`.`vorname` AS `vorname`,`u`.`email` AS `email`,`u`.`last_login` AS `last_login`,`u`.`last_access` AS `last_access`,`u`.`farbcode` AS `farbcode`,`u`.`notizen` AS `notizen`,`u`.`created_visa` AS `created_visa`,`u`.`created_date` AS `created_date`,`u`.`updated_visa` AS `updated_visa`,`u`.`updated_date` AS `updated_date`,unix_timestamp(`u`.`created_date`) AS `created_date_unix`,unix_timestamp(`u`.`updated_date`) AS `updated_date_unix` from `user` `u`;

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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung` AS select `mv_zutrittsberechtigung`.`anzeige_name` AS `anzeige_name`,`mv_zutrittsberechtigung`.`anzeige_name_de` AS `anzeige_name_de`,`mv_zutrittsberechtigung`.`anzeige_name_fr` AS `anzeige_name_fr`,`mv_zutrittsberechtigung`.`name` AS `name`,`mv_zutrittsberechtigung`.`name_de` AS `name_de`,`mv_zutrittsberechtigung`.`name_fr` AS `name_fr`,`mv_zutrittsberechtigung`.`id` AS `id`,`mv_zutrittsberechtigung`.`nachname` AS `nachname`,`mv_zutrittsberechtigung`.`vorname` AS `vorname`,`mv_zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`mv_zutrittsberechtigung`.`beschreibung_de` AS `beschreibung_de`,`mv_zutrittsberechtigung`.`beschreibung_fr` AS `beschreibung_fr`,`mv_zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`mv_zutrittsberechtigung`.`beruf` AS `beruf`,`mv_zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`mv_zutrittsberechtigung`.`partei_id` AS `partei_id`,`mv_zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`mv_zutrittsberechtigung`.`arbeitssprache` AS `arbeitssprache`,`mv_zutrittsberechtigung`.`email` AS `email`,`mv_zutrittsberechtigung`.`homepage` AS `homepage`,`mv_zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`mv_zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`mv_zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`mv_zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`mv_zutrittsberechtigung`.`telephon_1` AS `telephon_1`,`mv_zutrittsberechtigung`.`telephon_2` AS `telephon_2`,`mv_zutrittsberechtigung`.`erfasst` AS `erfasst`,`mv_zutrittsberechtigung`.`notizen` AS `notizen`,`mv_zutrittsberechtigung`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`mv_zutrittsberechtigung`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`mv_zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`mv_zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`mv_zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mv_zutrittsberechtigung`.`person_id` AS `person_id`,`mv_zutrittsberechtigung`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`mv_zutrittsberechtigung`.`funktion` AS `funktion`,`mv_zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`mv_zutrittsberechtigung`.`von` AS `von`,`mv_zutrittsberechtigung`.`bis` AS `bis`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mv_zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`mv_zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`mv_zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`mv_zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`mv_zutrittsberechtigung`.`created_visa` AS `created_visa`,`mv_zutrittsberechtigung`.`created_date` AS `created_date`,`mv_zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`mv_zutrittsberechtigung`.`updated_date` AS `updated_date`,`mv_zutrittsberechtigung`.`bis_unix` AS `bis_unix`,`mv_zutrittsberechtigung`.`von_unix` AS `von_unix`,`mv_zutrittsberechtigung`.`created_date_unix` AS `created_date_unix`,`mv_zutrittsberechtigung`.`updated_date_unix` AS `updated_date_unix`,`mv_zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mv_zutrittsberechtigung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mv_zutrittsberechtigung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mv_zutrittsberechtigung`.`beruf_branche_id` AS `beruf_branche_id`,`mv_zutrittsberechtigung`.`partei` AS `partei`,`mv_zutrittsberechtigung`.`partei_de` AS `partei_de`,`mv_zutrittsberechtigung`.`partei_fr` AS `partei_fr`,`mv_zutrittsberechtigung`.`parlamentarier_name` AS `parlamentarier_name`,`mv_zutrittsberechtigung`.`parlamentarier_freigabe_datum` AS `parlamentarier_freigabe_datum`,`mv_zutrittsberechtigung`.`parlamentarier_freigabe_datum_unix` AS `parlamentarier_freigabe_datum_unix`,`mv_zutrittsberechtigung`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`mv_zutrittsberechtigung`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`mv_zutrittsberechtigung`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`mv_zutrittsberechtigung`.`lobbyfaktor` AS `lobbyfaktor`,`mv_zutrittsberechtigung`.`lobbyfaktor_max` AS `lobbyfaktor_max`,`mv_zutrittsberechtigung`.`lobbyfaktor_percent_max` AS `lobbyfaktor_percent_max`,`mv_zutrittsberechtigung`.`anzahl_mandat_tief_max` AS `anzahl_mandat_tief_max`,`mv_zutrittsberechtigung`.`anzahl_mandat_mittel_max` AS `anzahl_mandat_mittel_max`,`mv_zutrittsberechtigung`.`anzahl_mandat_hoch_max` AS `anzahl_mandat_hoch_max`,`mv_zutrittsberechtigung`.`refreshed_date` AS `refreshed_date` from `mv_zutrittsberechtigung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_anhang`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_anhang` AS select `zutrittsberechtigung_anhang`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id2`,`zutrittsberechtigung_anhang`.`id` AS `id`,`zutrittsberechtigung_anhang`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung_anhang`.`datei` AS `datei`,`zutrittsberechtigung_anhang`.`dateiname` AS `dateiname`,`zutrittsberechtigung_anhang`.`dateierweiterung` AS `dateierweiterung`,`zutrittsberechtigung_anhang`.`dateiname_voll` AS `dateiname_voll`,`zutrittsberechtigung_anhang`.`mime_type` AS `mime_type`,`zutrittsberechtigung_anhang`.`encoding` AS `encoding`,`zutrittsberechtigung_anhang`.`beschreibung` AS `beschreibung`,`zutrittsberechtigung_anhang`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung_anhang`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung_anhang`.`created_visa` AS `created_visa`,`zutrittsberechtigung_anhang`.`created_date` AS `created_date`,`zutrittsberechtigung_anhang`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung_anhang`.`updated_date` AS `updated_date` from `zutrittsberechtigung_anhang`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_lobbyfaktor_max_raw`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_max_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_lobbyfaktor_max_raw` AS select 1 AS `id`,max(`lobbyfaktor`.`anzahl_mandat_tief`) AS `anzahl_mandat_tief_max`,max(`lobbyfaktor`.`anzahl_mandat_mittel`) AS `anzahl_mandat_mittel_max`,max(`lobbyfaktor`.`anzahl_mandat_hoch`) AS `anzahl_mandat_hoch_max`,max(`lobbyfaktor`.`lobbyfaktor`) AS `lobbyfaktor_max`,now() AS `refreshed_date` from `v_zutrittsberechtigung_lobbyfaktor_raw` `lobbyfaktor`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_lobbyfaktor_raw`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_lobbyfaktor_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_lobbyfaktor_raw` AS select `zutrittsberechtigung`.`person_id` AS `person_id`,count(distinct `mandat_tief`.`id`) AS `anzahl_mandat_tief`,count(distinct `mandat_mittel`.`id`) AS `anzahl_mandat_mittel`,count(distinct `mandat_hoch`.`id`) AS `anzahl_mandat_hoch`,((count(distinct `mandat_tief`.`id`) + (count(distinct `mandat_mittel`.`id`) * 5)) + (count(distinct `mandat_hoch`.`id`) * 11)) AS `lobbyfaktor`,now() AS `refreshed_date` from (((`zutrittsberechtigung` left join `v_mandat_medium_raw` `mandat_hoch` on(((`zutrittsberechtigung`.`person_id` = `mandat_hoch`.`person_id`) and (isnull(`mandat_hoch`.`bis`) or (`mandat_hoch`.`bis` >= now())) and (`mandat_hoch`.`wirksamkeit` = 'hoch')))) left join `v_mandat_medium_raw` `mandat_mittel` on(((`zutrittsberechtigung`.`person_id` = `mandat_mittel`.`person_id`) and (isnull(`mandat_mittel`.`bis`) or (`mandat_mittel`.`bis` >= now())) and (`mandat_mittel`.`wirksamkeit` = 'mittel')))) left join `v_mandat_medium_raw` `mandat_tief` on(((`zutrittsberechtigung`.`person_id` = `mandat_tief`.`person_id`) and (isnull(`mandat_tief`.`bis`) or (`mandat_tief`.`bis` >= now())) and (`mandat_tief`.`wirksamkeit` = 'tief')))) group by `zutrittsberechtigung`.`person_id`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_mandate`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_mandate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_mandate` AS select `zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`mandat`.`wirksamkeit` AS `wirksamkeit`,`mandat`.`wirksamkeit_index` AS `wirksamkeit_index`,`mandat`.`organisation_lobbyeinfluss` AS `organisation_lobbyeinfluss`,`mandat`.`refreshed_date` AS `refreshed_date` from (((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` join `v_mandat` `mandat` on((`zutrittsberechtigung`.`person_id` = `mandat`.`person_id`))) join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) order by `mandat`.`wirksamkeit`,`organisation`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_mit_mandaten`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_mit_mandaten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_mit_mandaten` AS select `organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix` from (((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` left join `v_mandat_simple` `mandat` on((`zutrittsberechtigung`.`person_id` = `mandat`.`person_id`))) left join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) left join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) order by `person`.`anzeige_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_mit_mandaten_indirekt`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_mit_mandaten_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_mit_mandaten_indirekt` AS select 'direkt' AS `beziehung`,`zutrittsberechtigung`.`organisation_name` AS `organisation_name`,`zutrittsberechtigung`.`organisation_name_de` AS `organisation_name_de`,`zutrittsberechtigung`.`organisation_name_fr` AS `organisation_name_fr`,`zutrittsberechtigung`.`name` AS `name`,`zutrittsberechtigung`.`name_de` AS `name_de`,`zutrittsberechtigung`.`name_fr` AS `name_fr`,`zutrittsberechtigung`.`name_it` AS `name_it`,`zutrittsberechtigung`.`ort` AS `ort`,`zutrittsberechtigung`.`land_id` AS `land_id`,`zutrittsberechtigung`.`interessenraum_id` AS `interessenraum_id`,`zutrittsberechtigung`.`rechtsform` AS `rechtsform`,`zutrittsberechtigung`.`typ` AS `typ`,`zutrittsberechtigung`.`vernehmlassung` AS `vernehmlassung`,`zutrittsberechtigung`.`interessengruppe_id` AS `interessengruppe_id`,`zutrittsberechtigung`.`interessengruppe2_id` AS `interessengruppe2_id`,`zutrittsberechtigung`.`interessengruppe3_id` AS `interessengruppe3_id`,`zutrittsberechtigung`.`branche_id` AS `branche_id`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`handelsregister_url` AS `handelsregister_url`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`organisation_beschreibung` AS `organisation_beschreibung`,`zutrittsberechtigung`.`adresse_strasse` AS `adresse_strasse`,`zutrittsberechtigung`.`adresse_zusatz` AS `adresse_zusatz`,`zutrittsberechtigung`.`adresse_plz` AS `adresse_plz`,`zutrittsberechtigung`.`branche` AS `branche`,`zutrittsberechtigung`.`interessengruppe` AS `interessengruppe`,`zutrittsberechtigung`.`interessengruppe_branche` AS `interessengruppe_branche`,`zutrittsberechtigung`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`zutrittsberechtigung`.`interessengruppe2` AS `interessengruppe2`,`zutrittsberechtigung`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`zutrittsberechtigung`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`zutrittsberechtigung`.`interessengruppe3` AS `interessengruppe3`,`zutrittsberechtigung`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`zutrittsberechtigung`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`zutrittsberechtigung`.`land` AS `land`,`zutrittsberechtigung`.`interessenraum` AS `interessenraum`,`zutrittsberechtigung`.`organisation_jahr_id` AS `organisation_jahr_id`,`zutrittsberechtigung`.`jahr` AS `jahr`,`zutrittsberechtigung`.`umsatz` AS `umsatz`,`zutrittsberechtigung`.`gewinn` AS `gewinn`,`zutrittsberechtigung`.`kapital` AS `kapital`,`zutrittsberechtigung`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`zutrittsberechtigung`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`zutrittsberechtigung`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`zutrittsberechtigung`.`zutrittsberechtigung_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`organisation_id` AS `organisation_id`,`zutrittsberechtigung`.`art` AS `art`,`zutrittsberechtigung`.`funktion_im_gremium` AS `funktion_im_gremium`,`zutrittsberechtigung`.`verguetung` AS `verguetung`,`zutrittsberechtigung`.`beschreibung` AS `beschreibung`,`zutrittsberechtigung`.`quelle_url` AS `quelle_url`,`zutrittsberechtigung`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`zutrittsberechtigung`.`quelle` AS `quelle`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,`zutrittsberechtigung`.`bis_unix` AS `bis_unix`,`zutrittsberechtigung`.`von_unix` AS `von_unix`,`zutrittsberechtigung`.`created_date_unix` AS `created_date_unix`,`zutrittsberechtigung`.`updated_date_unix` AS `updated_date_unix`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`zutrittsberechtigung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`zutrittsberechtigung`.`freigabe_datum_unix` AS `freigabe_datum_unix` from `v_zutrittsberechtigung_mit_mandaten` `zutrittsberechtigung` union select 'indirekt' AS `beziehung`,`organisation`.`anzeige_name` AS `organisation_name`,`organisation`.`anzeige_name_de` AS `organisation_name_de`,`organisation`.`anzeige_name_fr` AS `organisation_name_fr`,`organisation`.`name` AS `name`,`organisation`.`name_de` AS `name_de`,`organisation`.`name_fr` AS `name_fr`,`organisation`.`name_it` AS `name_it`,`organisation`.`ort` AS `ort`,`organisation`.`land_id` AS `land_id`,`organisation`.`interessenraum_id` AS `interessenraum_id`,`organisation`.`rechtsform` AS `rechtsform`,`organisation`.`typ` AS `typ`,`organisation`.`vernehmlassung` AS `vernehmlassung`,`organisation`.`interessengruppe_id` AS `interessengruppe_id`,`organisation`.`interessengruppe2_id` AS `interessengruppe2_id`,`organisation`.`interessengruppe3_id` AS `interessengruppe3_id`,`organisation`.`branche_id` AS `branche_id`,`organisation`.`homepage` AS `homepage`,`organisation`.`handelsregister_url` AS `handelsregister_url`,`organisation`.`twitter_name` AS `twitter_name`,`organisation`.`beschreibung` AS `organisation_beschreibung`,`organisation`.`adresse_strasse` AS `adresse_strasse`,`organisation`.`adresse_zusatz` AS `adresse_zusatz`,`organisation`.`adresse_plz` AS `adresse_plz`,`organisation`.`branche` AS `branche`,`organisation`.`interessengruppe` AS `interessengruppe`,`organisation`.`interessengruppe_branche` AS `interessengruppe_branche`,`organisation`.`interessengruppe_branche_id` AS `interessengruppe_branche_id`,`organisation`.`interessengruppe2` AS `interessengruppe2`,`organisation`.`interessengruppe2_branche` AS `interessengruppe2_branche`,`organisation`.`interessengruppe2_branche_id` AS `interessengruppe2_branche_id`,`organisation`.`interessengruppe3` AS `interessengruppe3`,`organisation`.`interessengruppe3_branche` AS `interessengruppe3_branche`,`organisation`.`interessengruppe3_branche_id` AS `interessengruppe3_branche_id`,`organisation`.`land` AS `land`,`organisation`.`interessenraum` AS `interessenraum`,`organisation`.`organisation_jahr_id` AS `organisation_jahr_id`,`organisation`.`jahr` AS `jahr`,`organisation`.`umsatz` AS `umsatz`,`organisation`.`gewinn` AS `gewinn`,`organisation`.`kapital` AS `kapital`,`organisation`.`mitarbeiter_weltweit` AS `mitarbeiter_weltweit`,`organisation`.`mitarbeiter_schweiz` AS `mitarbeiter_schweiz`,`organisation`.`geschaeftsbericht_url` AS `geschaeftsbericht_url`,`person`.`anzeige_name` AS `zutrittsberechtigung_name`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`mandat`.`id` AS `id`,`mandat`.`person_id` AS `person_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`funktion_im_gremium` AS `funktion_im_gremium`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`quelle_url` AS `quelle_url`,`mandat`.`quelle_url_gueltig` AS `quelle_url_gueltig`,`mandat`.`quelle` AS `quelle`,`mandat`.`von` AS `von`,`mandat`.`bis` AS `bis`,`mandat`.`notizen` AS `notizen`,`mandat`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`mandat`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`mandat`.`kontrolliert_visa` AS `kontrolliert_visa`,`mandat`.`kontrolliert_datum` AS `kontrolliert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`freigabe_visa` AS `freigabe_visa`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date`,`mandat`.`bis_unix` AS `bis_unix`,`mandat`.`von_unix` AS `von_unix`,`mandat`.`created_date_unix` AS `created_date_unix`,`mandat`.`updated_date_unix` AS `updated_date_unix`,`mandat`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`mandat`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`mandat`.`freigabe_datum_unix` AS `freigabe_datum_unix` from ((((`v_zutrittsberechtigung_simple` `zutrittsberechtigung` join `v_mandat_simple` `mandat` on((`zutrittsberechtigung`.`person_id` = `mandat`.`person_id`))) join `v_organisation_beziehung` `organisation_beziehung` on((`mandat`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) join `v_person` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `beziehung`,`organisation_name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_raw`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_raw`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_raw` AS select `zutrittsberechtigung`.`anzeige_name` AS `anzeige_name`,`zutrittsberechtigung`.`anzeige_name_de` AS `anzeige_name_de`,`zutrittsberechtigung`.`anzeige_name_fr` AS `anzeige_name_fr`,`zutrittsberechtigung`.`name` AS `name`,`zutrittsberechtigung`.`name_de` AS `name_de`,`zutrittsberechtigung`.`name_fr` AS `name_fr`,`zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`nachname` AS `nachname`,`zutrittsberechtigung`.`vorname` AS `vorname`,`zutrittsberechtigung`.`zweiter_vorname` AS `zweiter_vorname`,`zutrittsberechtigung`.`beschreibung_de` AS `beschreibung_de`,`zutrittsberechtigung`.`beschreibung_fr` AS `beschreibung_fr`,`zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`zutrittsberechtigung`.`beruf` AS `beruf`,`zutrittsberechtigung`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`zutrittsberechtigung`.`partei_id` AS `partei_id`,`zutrittsberechtigung`.`geschlecht` AS `geschlecht`,`zutrittsberechtigung`.`arbeitssprache` AS `arbeitssprache`,`zutrittsberechtigung`.`email` AS `email`,`zutrittsberechtigung`.`homepage` AS `homepage`,`zutrittsberechtigung`.`twitter_name` AS `twitter_name`,`zutrittsberechtigung`.`linkedin_profil_url` AS `linkedin_profil_url`,`zutrittsberechtigung`.`xing_profil_name` AS `xing_profil_name`,`zutrittsberechtigung`.`facebook_name` AS `facebook_name`,`zutrittsberechtigung`.`telephon_1` AS `telephon_1`,`zutrittsberechtigung`.`telephon_2` AS `telephon_2`,`zutrittsberechtigung`.`erfasst` AS `erfasst`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`zutrittsberechtigung`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`zutrittsberechtigung_id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,`zutrittsberechtigung`.`bis_unix` AS `bis_unix`,`zutrittsberechtigung`.`von_unix` AS `von_unix`,`zutrittsberechtigung`.`created_date_unix` AS `created_date_unix`,`zutrittsberechtigung`.`updated_date_unix` AS `updated_date_unix`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix` AS `eingabe_abgeschlossen_datum_unix`,`zutrittsberechtigung`.`kontrolliert_datum_unix` AS `kontrolliert_datum_unix`,`zutrittsberechtigung`.`freigabe_datum_unix` AS `freigabe_datum_unix`,`interessengruppe`.`branche_id` AS `beruf_branche_id`,`partei`.`abkuerzung` AS `partei`,`partei`.`abkuerzung` AS `partei_de`,`partei`.`abkuerzung_fr` AS `partei_fr`,`parlamentarier`.`anzeige_name` AS `parlamentarier_name`,`parlamentarier`.`freigabe_datum` AS `parlamentarier_freigabe_datum`,unix_timestamp(`parlamentarier`.`freigabe_datum`) AS `parlamentarier_freigabe_datum_unix`,`lobbyfaktor`.`anzahl_mandat_tief` AS `anzahl_mandat_tief`,`lobbyfaktor`.`anzahl_mandat_mittel` AS `anzahl_mandat_mittel`,`lobbyfaktor`.`anzahl_mandat_hoch` AS `anzahl_mandat_hoch`,`lobbyfaktor`.`lobbyfaktor` AS `lobbyfaktor`,`lobbyfaktor_max`.`lobbyfaktor_max` AS `lobbyfaktor_max`,(`lobbyfaktor`.`lobbyfaktor` / `lobbyfaktor_max`.`lobbyfaktor_max`) AS `lobbyfaktor_percent_max`,`lobbyfaktor_max`.`anzahl_mandat_tief_max` AS `anzahl_mandat_tief_max`,`lobbyfaktor_max`.`anzahl_mandat_mittel_max` AS `anzahl_mandat_mittel_max`,`lobbyfaktor_max`.`anzahl_mandat_hoch_max` AS `anzahl_mandat_hoch_max`,now() AS `refreshed_date` from (((((`v_zutrittsberechtigung_simple_compat` `zutrittsberechtigung` left join `v_partei` `partei` on((`zutrittsberechtigung`.`partei_id` = `partei`.`id`))) left join `v_parlamentarier_raw` `parlamentarier` on((`parlamentarier`.`id` = `zutrittsberechtigung`.`parlamentarier_id`))) left join `v_zutrittsberechtigung_lobbyfaktor_raw` `lobbyfaktor` on((`zutrittsberechtigung`.`person_id` = `lobbyfaktor`.`person_id`))) left join `v_interessengruppe` `interessengruppe` on((`zutrittsberechtigung`.`beruf_interessengruppe_id` = `interessengruppe`.`id`))) join `v_zutrittsberechtigung_lobbyfaktor_max_raw` `lobbyfaktor_max`);

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_simple`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_simple`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_simple` AS select `zutrittsberechtigung`.`id` AS `id`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`notizen` AS `notizen`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zutrittsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,unix_timestamp(`zutrittsberechtigung`.`bis`) AS `bis_unix`,unix_timestamp(`zutrittsberechtigung`.`von`) AS `von_unix`,unix_timestamp(`zutrittsberechtigung`.`created_date`) AS `created_date_unix`,unix_timestamp(`zutrittsberechtigung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`zutrittsberechtigung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`freigabe_datum`) AS `freigabe_datum_unix` from `zutrittsberechtigung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zutrittsberechtigung_simple_compat`
--
DROP TABLE IF EXISTS `v_zutrittsberechtigung_simple_compat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zutrittsberechtigung_simple_compat` AS select `person`.`anzeige_name` AS `anzeige_name`,`person`.`anzeige_name_de` AS `anzeige_name_de`,`person`.`anzeige_name_fr` AS `anzeige_name_fr`,`person`.`name` AS `name`,`person`.`name_de` AS `name_de`,`person`.`name_fr` AS `name_fr`,`person`.`id` AS `id`,`person`.`nachname` AS `nachname`,`person`.`vorname` AS `vorname`,`person`.`zweiter_vorname` AS `zweiter_vorname`,`person`.`beschreibung_de` AS `beschreibung_de`,`person`.`beschreibung_fr` AS `beschreibung_fr`,`person`.`parlamentarier_kommissionen` AS `parlamentarier_kommissionen`,`person`.`beruf` AS `beruf`,`person`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`person`.`partei_id` AS `partei_id`,`person`.`geschlecht` AS `geschlecht`,`person`.`arbeitssprache` AS `arbeitssprache`,`person`.`email` AS `email`,`person`.`homepage` AS `homepage`,`person`.`twitter_name` AS `twitter_name`,`person`.`linkedin_profil_url` AS `linkedin_profil_url`,`person`.`xing_profil_name` AS `xing_profil_name`,`person`.`facebook_name` AS `facebook_name`,`person`.`telephon_1` AS `telephon_1`,`person`.`telephon_2` AS `telephon_2`,`person`.`erfasst` AS `erfasst`,`person`.`notizen` AS `notizen`,`person`.`autorisierung_verschickt_visa` AS `autorisierung_verschickt_visa`,`person`.`autorisierung_verschickt_datum` AS `autorisierung_verschickt_datum`,`person`.`autorisiert_visa` AS `autorisiert_visa`,`person`.`autorisiert_datum` AS `autorisiert_datum`,`zutrittsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zutrittsberechtigung`.`person_id` AS `person_id`,`zutrittsberechtigung`.`id` AS `zutrittsberechtigung_id`,`zutrittsberechtigung`.`funktion` AS `funktion`,`zutrittsberechtigung`.`funktion_fr` AS `funktion_fr`,`zutrittsberechtigung`.`von` AS `von`,`zutrittsberechtigung`.`bis` AS `bis`,`zutrittsberechtigung`.`eingabe_abgeschlossen_visa` AS `eingabe_abgeschlossen_visa`,`zutrittsberechtigung`.`eingabe_abgeschlossen_datum` AS `eingabe_abgeschlossen_datum`,`zutrittsberechtigung`.`kontrolliert_visa` AS `kontrolliert_visa`,`zutrittsberechtigung`.`kontrolliert_datum` AS `kontrolliert_datum`,`zutrittsberechtigung`.`freigabe_visa` AS `freigabe_visa`,`zutrittsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zutrittsberechtigung`.`created_visa` AS `created_visa`,`zutrittsberechtigung`.`created_date` AS `created_date`,`zutrittsberechtigung`.`updated_visa` AS `updated_visa`,`zutrittsberechtigung`.`updated_date` AS `updated_date`,unix_timestamp(`zutrittsberechtigung`.`bis`) AS `bis_unix`,unix_timestamp(`zutrittsberechtigung`.`von`) AS `von_unix`,unix_timestamp(`zutrittsberechtigung`.`created_date`) AS `created_date_unix`,unix_timestamp(`zutrittsberechtigung`.`updated_date`) AS `updated_date_unix`,unix_timestamp(`zutrittsberechtigung`.`eingabe_abgeschlossen_datum`) AS `eingabe_abgeschlossen_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`kontrolliert_datum`) AS `kontrolliert_datum_unix`,unix_timestamp(`zutrittsberechtigung`.`freigabe_datum`) AS `freigabe_datum_unix` from (`zutrittsberechtigung` join `v_person_simple` `person` on((`person`.`id` = `zutrittsberechtigung`.`person_id`)));

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `branche`
--
ALTER TABLE `branche`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `branche_name_unique` (`name`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_kommission_freigabe` (`kommission_id`,`freigabe_datum`);

--
-- Indizes für die Tabelle `branche_log`
--
ALTER TABLE `branche_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `kommission_id` (`kommission_id`), ADD KEY `fk_branche_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `country`
--
ALTER TABLE `country`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_name_de` (`name_de`), ADD UNIQUE KEY `idx_name_en` (`name_en`), ADD KEY `idx_show_level` (`show_level`), ADD KEY `idx_continent` (`continent`);

--
-- Indizes für die Tabelle `fraktion`
--
ALTER TABLE `fraktion`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `fraktion_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint', ADD UNIQUE KEY `fraktion_name_unique` (`name`);

--
-- Indizes für die Tabelle `fraktion_log`
--
ALTER TABLE `fraktion_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_fraktion_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `interessenbindung`
--
ALTER TABLE `interessenbindung`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `interessenbindung_art_parlamentarier_organisation_unique` (`art`,`parlamentarier_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint', ADD KEY `parlamentarier_id` (`parlamentarier_id`,`organisation_id`), ADD KEY `organisation_id` (`organisation_id`,`parlamentarier_id`);

--
-- Indizes für die Tabelle `interessenbindung_log`
--
ALTER TABLE `interessenbindung_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `idx_parlam` (`parlamentarier_id`), ADD KEY `idx_lobbyorg` (`organisation_id`), ADD KEY `fk_interessenbindung_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `interessengruppe`
--
ALTER TABLE `interessengruppe`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `interessengruppe_name_unique` (`name`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_branche_freigabe` (`branche_id`,`freigabe_datum`);

--
-- Indizes für die Tabelle `interessengruppe_log`
--
ALTER TABLE `interessengruppe_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `idx_lobbytyp` (`branche_id`), ADD KEY `fk_interessengruppe_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `interessenraum`
--
ALTER TABLE `interessenraum`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `interessenraum_name_unique` (`name`) COMMENT 'Fachlicher unique constraint', ADD KEY `reihenfolge` (`reihenfolge`);

--
-- Indizes für die Tabelle `in_kommission`
--
ALTER TABLE `in_kommission`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`,`bis`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_parlam_freigabe` (`parlamentarier_id`,`freigabe_datum`,`bis`,`kommission_id`), ADD KEY `idx_parlam` (`parlamentarier_id`,`bis`,`kommission_id`), ADD KEY `idx_kommission_freigabe` (`kommission_id`,`freigabe_datum`,`bis`,`parlamentarier_id`), ADD KEY `idx_kommission` (`kommission_id`,`bis`,`parlamentarier_id`);

--
-- Indizes für die Tabelle `in_kommission_log`
--
ALTER TABLE `in_kommission_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `parlamentarier_id` (`parlamentarier_id`), ADD KEY `kommissions_id` (`kommission_id`), ADD KEY `fk_in_kommission_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `kanton`
--
ALTER TABLE `kanton`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `abkuerzung` (`abkuerzung`), ADD UNIQUE KEY `kantonsnr` (`kantonsnr`);

--
-- Indizes für die Tabelle `kanton_jahr`
--
ALTER TABLE `kanton_jahr`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_kanton_jahr_unique` (`kanton_id`,`jahr`) COMMENT 'Fachlicher unique constraint', ADD KEY `kanton_id` (`kanton_id`);

--
-- Indizes für die Tabelle `kanton_jahr_log`
--
ALTER TABLE `kanton_jahr_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_kanton_jahr_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `kanton_log`
--
ALTER TABLE `kanton_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_kanton_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `kommission`
--
ALTER TABLE `kommission`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `kommission_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint', ADD UNIQUE KEY `kommission_name_unique` (`name`) COMMENT 'Fachlicher unique constraint', ADD KEY `zugehoerige_kommission` (`mutter_kommission_id`,`freigabe_datum`);

--
-- Indizes für die Tabelle `kommission_log`
--
ALTER TABLE `kommission_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `zugehoerige_kommission` (`mutter_kommission_id`), ADD KEY `fk_kommission_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `mandat`
--
ALTER TABLE `mandat`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `mandat_person_organisation_art_unique` (`art`,`person_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint', ADD KEY `organisations_id` (`organisation_id`,`person_id`), ADD KEY `person_id` (`person_id`,`organisation_id`) COMMENT 'person_id';

--
-- Indizes für die Tabelle `mandat_log`
--
ALTER TABLE `mandat_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `organisations_id` (`organisation_id`), ADD KEY `zutrittsberechtigung_id` (`person_id`), ADD KEY `fk_mandat_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `mil_grad`
--
ALTER TABLE `mil_grad`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name_unique` (`name`), ADD UNIQUE KEY `abkuerzung_unique` (`abkuerzung`);

--
-- Indizes für die Tabelle `mil_grad_log`
--
ALTER TABLE `mil_grad_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_mil_grad_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `mv_interessenbindung`
--
ALTER TABLE `mv_interessenbindung`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`,`freigabe_datum`,`bis`,`organisation_id`), ADD KEY `idx_parlam_bis` (`parlamentarier_id`,`bis`,`organisation_id`), ADD KEY `idx_parlam` (`parlamentarier_id`,`organisation_id`), ADD KEY `idx_org_freigabe_bis` (`organisation_id`,`freigabe_datum`,`bis`,`parlamentarier_id`), ADD KEY `idx_org_bis` (`organisation_id`,`bis`,`parlamentarier_id`), ADD KEY `idx_org` (`organisation_id`,`parlamentarier_id`);

--
-- Indizes für die Tabelle `mv_mandat`
--
ALTER TABLE `mv_mandat`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_person_freigabe_bis` (`person_id`,`freigabe_datum`,`bis`,`organisation_id`), ADD KEY `idx_person_bis` (`person_id`,`bis`,`organisation_id`), ADD KEY `idx_person` (`person_id`,`organisation_id`), ADD KEY `idx_org_freigabe_bis` (`organisation_id`,`freigabe_datum`,`bis`,`person_id`), ADD KEY `idx_org_bis` (`organisation_id`,`bis`,`person_id`), ADD KEY `idx_org` (`organisation_id`,`person_id`);

--
-- Indizes für die Tabelle `mv_organisation`
--
ALTER TABLE `mv_organisation`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_freigabe` (`freigabe_datum`), ADD KEY `idx_branche_freigabe` (`branche_id`,`freigabe_datum`), ADD KEY `idx_interessengruppe_freigabe` (`interessengruppe_id`,`freigabe_datum`), ADD KEY `idx_interessengruppe2_freigabe` (`interessengruppe2_id`,`freigabe_datum`), ADD KEY `idx_interessengruppe3_freigabe` (`interessengruppe3_id`,`freigabe_datum`), ADD KEY `idx_interessengruppe_branche_freigabe` (`interessengruppe_branche_id`,`freigabe_datum`), ADD KEY `idx_interessengruppe2_branche_freigabe` (`interessengruppe2_branche_id`,`freigabe_datum`), ADD KEY `idx_interessengruppe3_branche_freigabe` (`interessengruppe3_branche_id`,`freigabe_datum`), ADD KEY `land` (`land_id`,`freigabe_datum`), ADD KEY `interessenraum_id` (`interessenraum_id`,`freigabe_datum`);

--
-- Indizes für die Tabelle `mv_parlamentarier`
--
ALTER TABLE `mv_parlamentarier`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_freigabe_bis` (`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_bis` (`im_rat_bis`), ADD KEY `idx_rat_freigabe_bis` (`rat`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_rat_bis` (`rat`,`im_rat_bis`), ADD KEY `idx_rat_id_freigabe_bis` (`rat_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_rat_id_bis` (`rat_id`,`im_rat_bis`), ADD KEY `idx_kanton_freigabe_bis` (`kanton`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_kanton_bis` (`kanton`,`im_rat_bis`), ADD KEY `idx_kanton_partei_freigabe_bis` (`kanton`,`partei`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_kanton_partei_bis` (`kanton`,`partei`,`im_rat_bis`), ADD KEY `idx_kanton_id_freigabe_bis` (`kanton_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_kanton_id_bis` (`kanton_id`,`im_rat_bis`), ADD KEY `idx_partei_freigabe_bis` (`partei`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_partei_bis` (`partei`,`im_rat_bis`), ADD KEY `idx_partei_id_freigabe_bis` (`partei_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `idx_partei_id_bis` (`partei_id`,`im_rat_bis`), ADD KEY `beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `beruf_interessengruppe_id` (`beruf_interessengruppe_id`,`im_rat_bis`), ADD KEY `beruf_branche_id_freigabe` (`beruf_branche_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `beruf_branche_id` (`beruf_branche_id`,`im_rat_bis`), ADD KEY `militaerischer_grad_freigabe` (`militaerischer_grad_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `militaerischer_grad` (`militaerischer_grad_id`,`im_rat_bis`), ADD KEY `fraktion_freigabe_bis` (`fraktion`,`im_rat_bis`), ADD KEY `fraktion_bis` (`fraktion`,`freigabe_datum`,`im_rat_bis`), ADD KEY `fraktion_id_freigabe_bis` (`fraktion_id`,`freigabe_datum`,`im_rat_bis`), ADD KEY `fraktion_id_bis` (`fraktion_id`,`im_rat_bis`);

--
-- Indizes für die Tabelle `mv_search_table`
--
ALTER TABLE `mv_search_table`
 ADD PRIMARY KEY (`id`,`table_name`), ADD KEY `idx_search_str_de_long` (`freigabe_datum`,`bis`,`table_weight`,`weight`,`search_keywords_de`(255)), ADD KEY `idx_search_str_de_medium` (`freigabe_datum`,`table_weight`,`weight`,`search_keywords_de`(255)), ADD KEY `idx_search_str_de_short` (`table_weight`,`weight`,`search_keywords_de`(255)), ADD KEY `idx_search_str_fr_long` (`freigabe_datum`,`bis`,`table_weight`,`weight`,`search_keywords_fr`(255)), ADD KEY `idx_search_str_fr_medium` (`freigabe_datum`,`table_weight`,`weight`,`search_keywords_fr`(255)), ADD KEY `idx_search_str_fr_short` (`table_weight`,`weight`,`search_keywords_fr`(255));

--
-- Indizes für die Tabelle `mv_zutrittsberechtigung`
--
ALTER TABLE `mv_zutrittsberechtigung`
 ADD PRIMARY KEY (`zutrittsberechtigung_id`), ADD KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`,`freigabe_datum`,`bis`), ADD KEY `idx_parlam_bis` (`parlamentarier_id`,`bis`), ADD KEY `idx_partei_freigabe` (`partei`,`freigabe_datum`,`bis`), ADD KEY `idx_partei` (`partei`,`bis`), ADD KEY `idx_partei_id_freigabe` (`partei_id`,`freigabe_datum`,`bis`), ADD KEY `idx_partei_id` (`partei_id`,`bis`), ADD KEY `idx_beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`,`freigabe_datum`,`bis`), ADD KEY `idx_beruf_interessengruppe_id` (`beruf_interessengruppe_id`,`bis`), ADD KEY `idx_beruf_branche_id_freigabe` (`beruf_branche_id`,`freigabe_datum`,`bis`), ADD KEY `idx_beruf_branche_id` (`beruf_branche_id`,`bis`);

--
-- Indizes für die Tabelle `organisation`
--
ALTER TABLE `organisation`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `organisation_name_de_unique` (`name_de`) COMMENT 'Fachlicher unique constraint', ADD UNIQUE KEY `organisation_name_fr_unique` (`name_fr`) COMMENT 'Fachlicher unique constraint', ADD UNIQUE KEY `organisation_name_it_unique` (`name_it`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_lobbytyp` (`branche_id`), ADD KEY `idx_lobbygroup` (`interessengruppe_id`), ADD KEY `interessengruppe2_id` (`interessengruppe2_id`), ADD KEY `interessengruppe3_id` (`interessengruppe3_id`), ADD KEY `land` (`land_id`), ADD KEY `interessenraum_id` (`interessenraum_id`);

--
-- Indizes für die Tabelle `organisation_anhang`
--
ALTER TABLE `organisation_anhang`
 ADD PRIMARY KEY (`id`), ADD KEY `organisation_id` (`organisation_id`);

--
-- Indizes für die Tabelle `organisation_anhang_log`
--
ALTER TABLE `organisation_anhang_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `organisation_id` (`organisation_id`), ADD KEY `fk_organisation_anhang_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `organisation_beziehung`
--
ALTER TABLE `organisation_beziehung`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `organisation_beziehung_organisation_zielorganisation_art_unique` (`art`,`organisation_id`,`ziel_organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_org_freigabe` (`organisation_id`,`freigabe_datum`,`bis`,`ziel_organisation_id`), ADD KEY `idx_org` (`organisation_id`,`bis`,`ziel_organisation_id`), ADD KEY `idx_ziel_freigabe` (`ziel_organisation_id`,`freigabe_datum`,`bis`,`organisation_id`), ADD KEY `idx_ziel` (`ziel_organisation_id`,`bis`,`organisation_id`), ADD KEY `organisation_id` (`organisation_id`,`ziel_organisation_id`), ADD KEY `ziel_organisation_id` (`ziel_organisation_id`,`organisation_id`);

--
-- Indizes für die Tabelle `organisation_beziehung_log`
--
ALTER TABLE `organisation_beziehung_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `organisation_id` (`organisation_id`), ADD KEY `ziel_organisation_id` (`ziel_organisation_id`), ADD KEY `fk_organisation_beziehung_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `organisation_jahr`
--
ALTER TABLE `organisation_jahr`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_organisation_jahr_unique` (`organisation_id`,`jahr`) COMMENT 'Fachlicher unique constraint', ADD KEY `organisation_id` (`organisation_id`);

--
-- Indizes für die Tabelle `organisation_jahr_log`
--
ALTER TABLE `organisation_jahr_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `organisation_id` (`organisation_id`), ADD KEY `fk_organisation_jahr_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `organisation_log`
--
ALTER TABLE `organisation_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `idx_lobbytyp` (`branche_id`), ADD KEY `idx_lobbygroup` (`interessengruppe_id`), ADD KEY `interessengruppe2_id` (`interessengruppe2_id`), ADD KEY `interessengruppe3_id` (`interessengruppe3_id`), ADD KEY `fk_organisation_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `parlamentarier`
--
ALTER TABLE `parlamentarier`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `parlamentarier_rat_sitzplatz` (`rat_id`,`sitzplatz`,`im_rat_bis`) COMMENT 'Fachlicher unique constraint', ADD UNIQUE KEY `parlamentarier_nachname_vorname_unique` (`nachname`,`vorname`,`zweiter_vorname`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_partei` (`partei_id`), ADD KEY `beruf_branche_id` (`beruf_interessengruppe_id`), ADD KEY `militaerischer_grad` (`militaerischer_grad_id`), ADD KEY `fraktion_id` (`fraktion_id`), ADD KEY `rat_id` (`rat_id`), ADD KEY `kanton_id` (`kanton_id`);

--
-- Indizes für die Tabelle `parlamentarier_anhang`
--
ALTER TABLE `parlamentarier_anhang`
 ADD PRIMARY KEY (`id`), ADD KEY `parlamentarier_id` (`parlamentarier_id`);

--
-- Indizes für die Tabelle `parlamentarier_anhang_log`
--
ALTER TABLE `parlamentarier_anhang_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `parlamentarier_id` (`parlamentarier_id`), ADD KEY `fk_parlamentarier_anhang_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `parlamentarier_log`
--
ALTER TABLE `parlamentarier_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `idx_partei` (`partei_id`), ADD KEY `beruf_branche_id` (`beruf_interessengruppe_id`), ADD KEY `militaerischer_grad` (`militaerischer_grad_id`), ADD KEY `fraktion_id` (`fraktion_id`), ADD KEY `fk_parlamentarier_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `partei`
--
ALTER TABLE `partei`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `partei_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint', ADD UNIQUE KEY `partei_name_unique` (`name`), ADD KEY `fraktion_id` (`fraktion_id`);

--
-- Indizes für die Tabelle `partei_log`
--
ALTER TABLE `partei_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fraktion_id` (`fraktion_id`), ADD KEY `fk_partei_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `person`
--
ALTER TABLE `person`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `person_nachname_zweiter_name_vorname_unique` (`nachname`,`vorname`,`zweiter_vorname`) COMMENT 'Fachlicher unique constraint', ADD KEY `idx_lobbygroup` (`beruf_interessengruppe_id`), ADD KEY `partei` (`partei_id`);

--
-- Indizes für die Tabelle `person_anhang`
--
ALTER TABLE `person_anhang`
 ADD PRIMARY KEY (`id`), ADD KEY `zutrittsberechtigung_id` (`person_id`);

--
-- Indizes für die Tabelle `person_anhang_log`
--
ALTER TABLE `person_anhang_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `zutrittsberechtigung_id` (`person_id`), ADD KEY `fk_zutrittsberechtigung_anhang_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `person_log`
--
ALTER TABLE `person_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `idx_lobbygroup` (`beruf_interessengruppe_id`), ADD KEY `partei` (`partei_id`), ADD KEY `fk_zutrittsberechtigung_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `rat`
--
ALTER TABLE `rat`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_rat_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint', ADD KEY `interessenraum_id` (`interessenraum_id`);

--
-- Indizes für die Tabelle `rat_log`
--
ALTER TABLE `rat_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_rat_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `key` (`key_name`), ADD KEY `category_id` (`category_id`);

--
-- Indizes für die Tabelle `settings_category`
--
ALTER TABLE `settings_category`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `settings_category_log`
--
ALTER TABLE `settings_category_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_settings_category_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `settings_log`
--
ALTER TABLE `settings_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_settings_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `snapshot`
--
ALTER TABLE `snapshot`
 ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `translation_language`
--
ALTER TABLE `translation_language`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `langcode` (`langcode`);

--
-- Indizes für die Tabelle `translation_source`
--
ALTER TABLE `translation_source`
 ADD PRIMARY KEY (`id`), ADD KEY `source_key` (`source`(255),`context`,`textgroup`) COMMENT 'Index for key';

--
-- Indizes für die Tabelle `translation_source_log`
--
ALTER TABLE `translation_source_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `source_key` (`source`(255),`context`,`textgroup`) COMMENT 'Index for key', ADD KEY `fk_translation_source_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `translation_target`
--
ALTER TABLE `translation_target`
 ADD PRIMARY KEY (`id`), ADD KEY `plural_translation_source_id` (`plural_translation_source_id`), ADD KEY `translation_source_id` (`translation_source_id`,`lang`);

--
-- Indizes für die Tabelle `translation_target_log`
--
ALTER TABLE `translation_target_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `plural_translation_source_id` (`plural_translation_source_id`), ADD KEY `translation_source_id` (`translation_source_id`,`lang`), ADD KEY `fk_translation_target_log_snapshot_id` (`snapshot_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idx_name_unique` (`name`) COMMENT 'Fachlicher unique constraint: Name muss einzigartig sein';

--
-- Indizes für die Tabelle `user_permission`
--
ALTER TABLE `user_permission`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`,`page_name`(255),`permission_name`);

--
-- Indizes für die Tabelle `zutrittsberechtigung`
--
ALTER TABLE `zutrittsberechtigung`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `parlamentarier_person_unique` (`parlamentarier_id`,`person_id`,`bis`) COMMENT 'Fachlicher unique constraint', ADD KEY `person_id` (`person_id`,`parlamentarier_id`);

--
-- Indizes für die Tabelle `zutrittsberechtigung_log`
--
ALTER TABLE `zutrittsberechtigung_log`
 ADD PRIMARY KEY (`log_id`), ADD KEY `fk_zutrittsberechtigung_log_snapshot_id` (`snapshot_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `branche`
--
ALTER TABLE `branche`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Branche',AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT für Tabelle `branche_log`
--
ALTER TABLE `branche_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT für Tabelle `country`
--
ALTER TABLE `country`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT für Tabelle `fraktion`
--
ALTER TABLE `fraktion`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Fraktion',AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `fraktion_log`
--
ALTER TABLE `fraktion_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT für Tabelle `interessenbindung`
--
ALTER TABLE `interessenbindung`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessenbindung',AUTO_INCREMENT=1339;
--
-- AUTO_INCREMENT für Tabelle `interessenbindung_log`
--
ALTER TABLE `interessenbindung_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=10001;
--
-- AUTO_INCREMENT für Tabelle `interessengruppe`
--
ALTER TABLE `interessengruppe`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessengruppe',AUTO_INCREMENT=128;
--
-- AUTO_INCREMENT für Tabelle `interessengruppe_log`
--
ALTER TABLE `interessengruppe_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=578;
--
-- AUTO_INCREMENT für Tabelle `interessenraum`
--
ALTER TABLE `interessenraum`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Interessenraumes',AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `in_kommission`
--
ALTER TABLE `in_kommission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Kommissionszugehörigkeit',AUTO_INCREMENT=637;
--
-- AUTO_INCREMENT für Tabelle `in_kommission_log`
--
ALTER TABLE `in_kommission_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=3149;
--
-- AUTO_INCREMENT für Tabelle `kanton`
--
ALTER TABLE `kanton`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Kantons',AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT für Tabelle `kanton_jahr`
--
ALTER TABLE `kanton_jahr`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte eines Kantons',AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT für Tabelle `kanton_jahr_log`
--
ALTER TABLE `kanton_jahr_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=105;
--
-- AUTO_INCREMENT für Tabelle `kanton_log`
--
ALTER TABLE `kanton_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT für Tabelle `kommission`
--
ALTER TABLE `kommission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Kommission',AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT für Tabelle `kommission_log`
--
ALTER TABLE `kommission_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT für Tabelle `mandat`
--
ALTER TABLE `mandat`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=636;
--
-- AUTO_INCREMENT für Tabelle `mandat_log`
--
ALTER TABLE `mandat_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=4725;
--
-- AUTO_INCREMENT für Tabelle `mil_grad`
--
ALTER TABLE `mil_grad`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Militärischer Grad',AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT für Tabelle `mil_grad_log`
--
ALTER TABLE `mil_grad_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel';
--
-- AUTO_INCREMENT für Tabelle `organisation`
--
ALTER TABLE `organisation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Lobbyorganisation',AUTO_INCREMENT=1579;
--
-- AUTO_INCREMENT für Tabelle `organisation_anhang`
--
ALTER TABLE `organisation_anhang`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Organisationsanhangs',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `organisation_anhang_log`
--
ALTER TABLE `organisation_anhang_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `organisation_beziehung`
--
ALTER TABLE `organisation_beziehung`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Organisationsbeziehung',AUTO_INCREMENT=536;
--
-- AUTO_INCREMENT für Tabelle `organisation_beziehung_log`
--
ALTER TABLE `organisation_beziehung_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=2535;
--
-- AUTO_INCREMENT für Tabelle `organisation_jahr`
--
ALTER TABLE `organisation_jahr`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte einer Organisation',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `organisation_jahr_log`
--
ALTER TABLE `organisation_jahr_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `organisation_log`
--
ALTER TABLE `organisation_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=10102;
--
-- AUTO_INCREMENT für Tabelle `parlamentarier`
--
ALTER TABLE `parlamentarier`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentariers',AUTO_INCREMENT=257;
--
-- AUTO_INCREMENT für Tabelle `parlamentarier_anhang`
--
ALTER TABLE `parlamentarier_anhang`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentarieranhangs',AUTO_INCREMENT=119;
--
-- AUTO_INCREMENT für Tabelle `parlamentarier_anhang_log`
--
ALTER TABLE `parlamentarier_anhang_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=364;
--
-- AUTO_INCREMENT für Tabelle `parlamentarier_log`
--
ALTER TABLE `parlamentarier_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=3017;
--
-- AUTO_INCREMENT für Tabelle `partei`
--
ALTER TABLE `partei`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Partei',AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `partei_log`
--
ALTER TABLE `partei_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT für Tabelle `person`
--
ALTER TABLE `person`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zugangsberechtigung',AUTO_INCREMENT=450;
--
-- AUTO_INCREMENT für Tabelle `person_anhang`
--
ALTER TABLE `person_anhang`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Zutrittsberechtigunganhangs',AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT für Tabelle `person_anhang_log`
--
ALTER TABLE `person_anhang_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=244;
--
-- AUTO_INCREMENT für Tabelle `person_log`
--
ALTER TABLE `person_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=3057;
--
-- AUTO_INCREMENT für Tabelle `rat`
--
ALTER TABLE `rat`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte eines Rates',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `rat_log`
--
ALTER TABLE `rat_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT für Tabelle `settings`
--
ALTER TABLE `settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Settings',AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT für Tabelle `settings_category`
--
ALTER TABLE `settings_category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Settingsateogrie',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `settings_category_log`
--
ALTER TABLE `settings_category_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `settings_log`
--
ALTER TABLE `settings_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=137;
--
-- AUTO_INCREMENT für Tabelle `snapshot`
--
ALTER TABLE `snapshot`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Snapshots',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `translation_language`
--
ALTER TABLE `translation_language`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel';
--
-- AUTO_INCREMENT für Tabelle `translation_source`
--
ALTER TABLE `translation_source`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',AUTO_INCREMENT=377;
--
-- AUTO_INCREMENT für Tabelle `translation_source_log`
--
ALTER TABLE `translation_source_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=377;
--
-- AUTO_INCREMENT für Tabelle `translation_target`
--
ALTER TABLE `translation_target`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',AUTO_INCREMENT=700;
--
-- AUTO_INCREMENT für Tabelle `translation_target_log`
--
ALTER TABLE `translation_target_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',AUTO_INCREMENT=700;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User',AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT für Tabelle `user_permission`
--
ALTER TABLE `user_permission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User Persmissions',AUTO_INCREMENT=340;
--
-- AUTO_INCREMENT für Tabelle `zutrittsberechtigung`
--
ALTER TABLE `zutrittsberechtigung`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zutrittsberechtigung',AUTO_INCREMENT=440;
--
-- AUTO_INCREMENT für Tabelle `zutrittsberechtigung_log`
--
ALTER TABLE `zutrittsberechtigung_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel';
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
ADD CONSTRAINT `fk_mandat_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
ADD CONSTRAINT `fk_organisations_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`);

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
ADD CONSTRAINT `fk_org_country` FOREIGN KEY (`land_id`) REFERENCES `country` (`id`),
ADD CONSTRAINT `fk_org_interessenraum` FOREIGN KEY (`interessenraum_id`) REFERENCES `interessenraum` (`id`),
ADD CONSTRAINT `fk_organisation_interessengruppe2_id` FOREIGN KEY (`interessengruppe2_id`) REFERENCES `interessengruppe` (`id`),
ADD CONSTRAINT `fk_organisation_interessengruppe3_id` FOREIGN KEY (`interessengruppe3_id`) REFERENCES `interessengruppe` (`id`);

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
-- Constraints der Tabelle `person`
--
ALTER TABLE `person`
ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
ADD CONSTRAINT `fk_zutrittsberechtigung_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`);

--
-- Constraints der Tabelle `person_anhang`
--
ALTER TABLE `person_anhang`
ADD CONSTRAINT `fk_person_anhang_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`);

--
-- Constraints der Tabelle `person_anhang_log`
--
ALTER TABLE `person_anhang_log`
ADD CONSTRAINT `fk_person_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `person_log`
--
ALTER TABLE `person_log`
ADD CONSTRAINT `fk_person_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

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
-- Constraints der Tabelle `translation_source_log`
--
ALTER TABLE `translation_source_log`
ADD CONSTRAINT `fk_translation_source_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `translation_target`
--
ALTER TABLE `translation_target`
ADD CONSTRAINT `plural_translation_source_id` FOREIGN KEY (`plural_translation_source_id`) REFERENCES `translation_source` (`id`),
ADD CONSTRAINT `translation_source_id` FOREIGN KEY (`translation_source_id`) REFERENCES `translation_source` (`id`);

--
-- Constraints der Tabelle `translation_target_log`
--
ALTER TABLE `translation_target_log`
ADD CONSTRAINT `fk_translation_target_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- Constraints der Tabelle `zutrittsberechtigung`
--
ALTER TABLE `zutrittsberechtigung`
ADD CONSTRAINT `fk_zutrittsberechtigung_parlamentarier` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`),
ADD CONSTRAINT `fk_zutrittsberechtigung_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`);

--
-- Constraints der Tabelle `zutrittsberechtigung_log`
--
ALTER TABLE `zutrittsberechtigung_log`
ADD CONSTRAINT `fk_zutrittsberechtigung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
