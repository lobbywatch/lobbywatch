-- This script sets up logging of tables changes and snapshot capability.

-- Run: mysql -u root -h 127.0.0.1 lobbywatch < lobbywatch_log.sql


-- Error message if problem with _log tables:
-- #1136 - Column count doesn't match value count at row 1

-- Ein Fehler ist aufgetreten wärend dem Update:
-- Cannot execute SQL: UPDATE `kommission` SET `abkuerzung` = 'FinDel', `name` = 'Finanzdelegation', `typ` = 'spezialkommission', `mutter_kommission` = NULL, `beschreibung` = NULL, `sachbereiche` = 'Zuständige Sachbereiche\r\n\r\nGemäss Art. 51 Abs. 2 des Parlamentsgesetzes obliegt der Finanzdelegation die nähere Überprüfung und Überwachung des gesamten Finanzhaushaltes.\r\nSie zählt je drei Mitglieder des Nationalrates und des Ständerates, die den Finanzkommissionen des entsprechenden Rates angehören.\r\n\r\nDer Auftrag der Finanzdelegation umfasst vor allem fünf Aufgaben:\r\nSie verfügt über besoldungs- und kreditrechtliche Kompetenzen, nimmt die Revisionsberichte der Eidgenössischen Finanzkontrolle (EFK) ab, übt die mitschreitende Aufsicht über die Finanzpolitik des Bundesrates aus und kann Mitberichte zu Botschaften des Bundesrates an die Finanzkommissionen oder andere Kommissionen verfassen.', `parlament_link` = NULL, `notizen` = NULL, `freigabe_von` = NULL, `freigabe_datum` = NULL, `updated_visa` = 'roland', `updated_date` = '2013-12-16 11:35:49' WHERE `id` = 43 Cannot add or update a child row: a foreign key constraint fails (`lobbywatch`.`kommission_log`, CONSTRAINT `fk_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`))

-- Disable logging:
--   SET @disable_table_logging = 1;
--   // Your update statement goes here.
--   SET @disable_table_logging = NULL;

-- Disable triggers
--   SET @disable_triggers = 1;
--   // Your update statement goes here.
--   SET @disable_triggers = NULL;
-- Ref:
--    - http://stackoverflow.com/questions/6480074/mysql-disable-all-triggers
--    - http://mysql-0v34c10ck.blogspot.ch/2011/06/how-to-disableenable-triggers-on-demand.html

-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 14. Dez 2013 um 23:50
-- Server Version: 5.6.12
-- PHP-Version: 5.5.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- ;SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `lobbywatch`
--
-- ; CREATE DATABASE IF NOT EXISTS `lobbywatch` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8_general_ci;
-- USE `lobbywatch`;

-- --------------------------------------------------------

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Lobbywatch snapshots';

--
-- Tabellenstruktur für Tabelle `branche`
--
-- Erzeugt am: 02. Dez 2013 um 09:25
--

-- DROP TABLE IF EXISTS `branche`;
-- CREATE TABLE IF NOT EXISTS `branche` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Live-Daten',
--   `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
--   `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
--   `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
--   `angaben` text COMMENT 'Angaben zur Branche',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `branche_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
--   KEY `kommission_id` (`kommission_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Wirtschaftsbranchen' AUTO_INCREMENT=19 ;

--
-- RELATIONEN DER TABELLE `branche`:
--   `kommission_id`
--       `kommission` -> `id`
--

DROP TABLE IF EXISTS `branche_log`;
CREATE TABLE IF NOT EXISTS `branche_log` LIKE `branche`;
ALTER TABLE `branche_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `branche_name_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_branche_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `branche`:
--   `kommission_id`
--       `kommission` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung`
--
-- Erzeugt am: 14. Dez 2013 um 10:59
--

-- DROP TABLE IF EXISTS `interessenbindung`;
-- CREATE TABLE IF NOT EXISTS `interessenbindung` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessenbindung',
--   `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
--   `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
--   `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
--   `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
--   `verguetung` int(11) DEFAULT NULL COMMENT 'Monatliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
--   `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Bezeichnung wird zur Auswertung wahrscheinlich nicht gebraucht.',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am',
--   `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `interessenbindung_art_parlamentarier_organisation_unique` (`art`,`parlamentarier_id`,`organisation_id`) COMMENT 'Fachlicher unique constraint',
--   KEY `idx_parlam` (`parlamentarier_id`),
--   KEY `idx_lobbyorg` (`organisation_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Interessenbindungen von Parlamentariern' AUTO_INCREMENT=337 ;

DROP TABLE IF EXISTS `interessenbindung_log`;
CREATE TABLE IF NOT EXISTS `interessenbindung_log` LIKE `interessenbindung`;
ALTER TABLE `interessenbindung_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `interessenbindung_art_parlamentarier_organisation_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_interessenbindung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `interessenbindung`:
--   `organisation_id`
--       `organisation` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessengruppe`
--
-- Erzeugt am: 02. Dez 2013 um 09:26
--

-- DROP TABLE IF EXISTS `interessengruppe`;
-- CREATE TABLE IF NOT EXISTS `interessengruppe` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessengruppe',
--   `name` varchar(150) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
--   `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
--   `beschreibung` text NOT NULL COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `interessengruppe_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
--   KEY `idx_lobbytyp` (`branche_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Interessengruppen einer Branche' AUTO_INCREMENT=10 ;

DROP TABLE IF EXISTS `interessengruppe_log`;
CREATE TABLE IF NOT EXISTS `interessengruppe_log` LIKE `interessengruppe`;
ALTER TABLE `interessengruppe_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `interessengruppe_name_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_interessengruppe_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `interessengruppe`:
--   `branche_id`
--       `branche` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `in_kommission`
--
-- Erzeugt am: 02. Dez 2013 um 09:26
--

-- DROP TABLE IF EXISTS `in_kommission`;
-- CREATE TABLE IF NOT EXISTS `in_kommission` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Kommissionszugehörigkeit',
--   `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
--   `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
--   `funktion` set('praesident','vizepraesident','mitglied','delegation') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`) COMMENT 'Fachlicher unique constraint',
--   KEY `parlamentarier_id` (`parlamentarier_id`),
--   KEY `kommissions_id` (`kommission_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Kommissionszugehörigkeit von Parlamentariern' AUTO_INCREMENT=117 ;

DROP TABLE IF EXISTS `in_kommission_log`;
CREATE TABLE IF NOT EXISTS `in_kommission_log` LIKE `in_kommission`;
ALTER TABLE `in_kommission_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `in_kommission_parlamentarier_kommission_funktion_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_in_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `in_kommission`:
--   `kommission_id`
--       `kommission` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission`
--
-- Erzeugt am: 14. Dez 2013 um 08:58
--

-- DROP TABLE IF EXISTS `kommission`;
-- CREATE TABLE IF NOT EXISTS `kommission` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Kommission',
--   `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
--   `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
--   `typ` enum('kommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
--   `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
--   `abkuerung_delegation` varchar(15) DEFAULT NULL COMMENT 'Abkürzung der Delegation. Delegation im engeren Sinne als Subkommission.',
--   `parlament_link` varchar(255) DEFAULT NULL COMMENT 'Link zur Seite auf Parlament.ch',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `idx_ko_unique_name` (`name`) COMMENT 'Fachlicher unique constraint',
--   UNIQUE KEY `kommission_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
--   UNIQUE KEY `kommission_name_unique` (`name`) COMMENT 'Fachlicher unique constraint'
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Parlamententskommissionen' AUTO_INCREMENT=44 ;

DROP TABLE IF EXISTS `kommission_log`;
CREATE TABLE IF NOT EXISTS `kommission_log` LIKE `kommission`;
ALTER TABLE `kommission_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `kommission_abkuerzung_unique`,
  DROP INDEX `kommission_name_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_kommission_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat`
--
-- Erzeugt am: 14. Dez 2013 um 11:00
--

-- DROP TABLE IF EXISTS `mandat`;
-- CREATE TABLE IF NOT EXISTS `mandat` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `zutrittsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Zugangsberechtigung.',
--   `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
--   `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat') DEFAULT NULL COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
--   `verguetung` int(11) DEFAULT NULL COMMENT 'Monatliche Vergütung CHF für Tätigkeiten aus dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
--   `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird zur Auswertung wahrscheinlich nicht gebraucht.',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am',
--   `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `mandat_zutrittsberechtigung_organisation_art_unique` (`art`,`zutrittsberechtigung_id`,`organisation_id`) COMMENT 'Fachlicher unique constraint',
--   KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`),
--   KEY `organisations_id` (`organisation_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Mandate der Zugangsberechtigten' AUTO_INCREMENT=4 ;

DROP TABLE IF EXISTS `mandat_log`;
CREATE TABLE IF NOT EXISTS `mandat_log` LIKE `mandat`;
ALTER TABLE `mandat_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `mandat_zutrittsberechtigung_organisation_art_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_mandat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `mandat`:
--   `organisation_id`
--       `organisation` -> `id`
--   `zutrittsberechtigung_id`
--       `zutrittsberechtigung` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation`
--
-- Erzeugt am: 09. Dez 2013 um 19:28
--

-- DROP TABLE IF EXISTS `organisation`;
-- CREATE TABLE IF NOT EXISTS `organisation` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Lobbyorganisation',
--   `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
--   `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
--   `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
--   `ort` varchar(100) DEFAULT NULL COMMENT 'Ort der Organisation',
--   `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich','Einzelunternehmen','KG') DEFAULT NULL COMMENT 'Rechtsform der Organisation',
--   `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby') NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.',
--   `vernehmlassung` enum('immer','punktuell','nie') NOT NULL COMMENT 'Häufigkeit der Vernehmlassungsteilnahme',
--   `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. Über die Interessengruppe wird eine Branche zugeordnet.',
--   `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche.',
--   `url` varchar(255) DEFAULT NULL COMMENT 'Link zur Webseite',
--   `beschreibung` text NOT NULL COMMENT 'Beschreibung der Lobbyorganisation',
--   `ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') NOT NULL COMMENT 'Bisherige Verbindung der Organisation ins Parlament',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `organisation_name_unique` (`name_de`) COMMENT 'Fachlicher unique constraint',
--   KEY `idx_lobbytyp` (`branche_id`),
--   KEY `idx_lobbygroup` (`interessengruppe_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Liste der Lobbyorganisationen' AUTO_INCREMENT=350 ;

DROP TABLE IF EXISTS `organisation_log`;
CREATE TABLE IF NOT EXISTS `organisation_log` LIKE `organisation`;
ALTER TABLE `organisation_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `organisation_name_de_unique`,
  DROP INDEX `organisation_name_fr_unique`,
  DROP INDEX `organisation_name_it_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_organisation_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `organisation`:
--   `interessengruppe_id`
--       `interessengruppe` -> `id`
--   `branche_id`
--       `branche` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_beziehung`
--
-- Erzeugt am: 02. Dez 2013 um 13:07
--

-- DROP TABLE IF EXISTS `organisation_beziehung`;
-- CREATE TABLE IF NOT EXISTS `organisation_beziehung` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Organisationsbeziehung',
--   `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
--   `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
--   `art` enum('arbeitet fuer','mitglied von') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `organisation_beziehung_organisation_zielorganisation_art_unique` (`art`,`organisation_id`,`ziel_organisation_id`) COMMENT 'Fachlicher unique constraint',
--   KEY `organisation_id` (`organisation_id`),
--   KEY `ziel_organisation_id` (`ziel_organisation_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Beschreibt die Beziehung von Organisationen zueinander' AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `organisation_beziehung_log`;
CREATE TABLE IF NOT EXISTS `organisation_beziehung_log` LIKE `organisation_beziehung`;
ALTER TABLE `organisation_beziehung_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `organisation_beziehung_organisation_zielorganisation_art_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_organisation_beziehung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `organisation_beziehung`:
--   `organisation_id`
--       `organisation` -> `id`
--   `ziel_organisation_id`
--       `organisation` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier`
--
-- Erzeugt am: 14. Dez 2013 um 20:07
--

-- DROP TABLE IF EXISTS `parlamentarier`;
-- CREATE TABLE IF NOT EXISTS `parlamentarier` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentariers',
--   `nachname` varchar(100) NOT NULL COMMENT 'Nachname des Parlamentariers',
--   `vorname` varchar(50) NOT NULL COMMENT 'Vornahme des Parlamentariers',
--   `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname des Parlamentariers',
--   `ratstyp` enum('NR','SR') NOT NULL COMMENT 'National- oder Ständerat?',
--   `kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH') NOT NULL COMMENT 'Kantonskürzel',
--   `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Leer bedeutet parteilos.',
--   `parteifunktion` set('mitglied','praesident','vizepraesident','fraktionschef') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei',
--   `im_rat_seit` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
--   `beruf` varchar(150) NOT NULL COMMENT 'Beruf des Parlamentariers',
--   `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
--   `geburtstag` date DEFAULT NULL COMMENT 'Geburtstag des Parlamentariers',
--   `photo` varchar(255) DEFAULT NULL COMMENT 'Photo des Parlamentariers (JPEG/jpg)',
--   `photo_dateiname` varchar(255) DEFAULT NULL COMMENT 'Photodateiname ohne Erweiterung',
--   `photo_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Photodatei',
--   `photo_dateiname_voll` varchar(255) DEFAULT NULL COMMENT 'Photodateiname mit Erweiterung',
--   `photo_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Photos',
--   `kleinbild` varchar(80) DEFAULT 'leer.png' COMMENT 'Bild 44x62 px oder leer.png',
--   `sitzplatz` int(11) DEFAULT NULL COMMENT 'Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch',
--   `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Parlamentariers',
--   `parlament_link` varchar(255) DEFAULT NULL COMMENT 'Link zur Biographie auf Parlament.ch',
--   `homepage` varchar(150) DEFAULT NULL COMMENT 'Homepage des Parlamentariers',
--   `ALT_kommission` varchar(255) DEFAULT NULL COMMENT 'Kommissionen als Einträge in Tabelle "in_kommission" erfassen. Wird später entfernt. Mitglied in Kommission(en) als Freitext',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `parlamentarier_nachname_vorname_unique` (`nachname`,`vorname`) COMMENT 'Fachlicher unique constraint',
--   UNIQUE KEY `parlamentarier_rat_sitzplatz` (`ratstyp`,`sitzplatz`) COMMENT 'Fachlicher unique constraint',
--   KEY `idx_partei` (`partei_id`),
--   KEY `beruf_branche_id` (`beruf_interessengruppe_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Liste der Parlamentarier' AUTO_INCREMENT=39 ;

DROP TABLE IF EXISTS `parlamentarier_log`;
CREATE TABLE IF NOT EXISTS `parlamentarier_log` LIKE `parlamentarier`;
ALTER TABLE `parlamentarier_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `parlamentarier_nachname_vorname_unique`,
  DROP INDEX `parlamentarier_rat_sitzplatz`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_parlamentarier_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- MIME TYPEN DER TABELLE `parlamentarier`:
--   `kleinbild`
--       `Image_JPEG`
--   `photo`
--       `Image_JPEG`
--

--
-- RELATIONEN DER TABELLE `parlamentarier`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `partei_id`
--       `partei` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier_anhang`
--
-- Erzeugt am: 12. Dez 2013 um 05:44
--

-- DROP TABLE IF EXISTS `parlamentarier_anhang`;
-- CREATE TABLE IF NOT EXISTS `parlamentarier_anhang` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentarieranhangs',
--   `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Parlamentariers',
--   `datei` varchar(255) NOT NULL COMMENT 'Datei',
--   `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
--   `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
--   `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
--   `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
--   `encoding` varchar(20) NOT NULL COMMENT 'Encoding der Datei',
--   `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
--   PRIMARY KEY (`id`),
--   KEY `parlamentarier_id` (`parlamentarier_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Anhänge zu Parlamentariern' AUTO_INCREMENT=56 ;

DROP TABLE IF EXISTS `parlamentarier_anhang_log`;
CREATE TABLE IF NOT EXISTS `parlamentarier_anhang_log` LIKE `parlamentarier_anhang`;
ALTER TABLE `parlamentarier_anhang_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_parlamentarier_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- MIME TYPEN DER TABELLE `parlamentarier_anhang`:
--   `datei`
--       `Application_Octetstream`
--

--
-- RELATIONEN DER TABELLE `parlamentarier_anhang`:
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei`
--
-- Erzeugt am: 02. Dez 2013 um 09:27
--

-- DROP TABLE IF EXISTS `partei`;
-- CREATE TABLE IF NOT EXISTS `partei` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Partei',
--   `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
--   `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
--   `gruendung` year(4) DEFAULT NULL COMMENT 'Gründungsjahr der Partei',
--   `position` enum('links','rechts','mitte','') DEFAULT NULL COMMENT 'Politische Position der Partei',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `partei_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
--   UNIQUE KEY `partei_name_unique` (`name`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Politische Parteien des Parlamentes' AUTO_INCREMENT=9 ;

DROP TABLE IF EXISTS `partei_log`;
CREATE TABLE IF NOT EXISTS `partei_log` LIKE `partei`;
ALTER TABLE `partei_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `partei_abkuerzung_unique`,
  DROP INDEX `partei_name_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_partei_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);


DROP TABLE IF EXISTS `fraktion_log`;
CREATE TABLE IF NOT EXISTS `fraktion_log` LIKE `fraktion`;
ALTER TABLE `fraktion_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `fraktion_abkuerzung_unique`,
  DROP INDEX `fraktion_name_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_fraktion_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--
-- Erzeugt am: 02. Dez 2013 um 09:27
--

-- DROP TABLE IF EXISTS `user`;
-- CREATE TABLE IF NOT EXISTS `user` (
--   `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel User',
--   `name` varchar(10) NOT NULL,
--   `password` varchar(255) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='PHP Generator users';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_permission`
--
-- Erzeugt am: 02. Dez 2013 um 09:27
--

-- DROP TABLE IF EXISTS `user_permission`;
-- CREATE TABLE IF NOT EXISTS `user_permission` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User Persmissions',
--   `user_id` int(11) NOT NULL,
--   `page_name` varchar(500) DEFAULT NULL,
--   `permission_name` varchar(6) DEFAULT NULL,
--   PRIMARY KEY (`id`),
--   KEY `user_id` (`user_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='PHP Generator user permissions' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zutrittsberechtigung`
--
-- Erzeugt am: 09. Dez 2013 um 21:58
--

-- DROP TABLE IF EXISTS `zutrittsberechtigung`;
-- CREATE TABLE IF NOT EXISTS `zutrittsberechtigung` (
--   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
--   `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zu Parlamentarier',
--   `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
--   `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
--   `funktion` varchar(150) DEFAULT NULL COMMENT 'Angegebene Funktion bei der Zugangsberechtigung',
--   `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
--   `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
--   `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
--   `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
--   `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
--   `ALT_lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Wird später entfernt. Fremschlüssel zur Lobbyorganisation',
--   `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
--   `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
--   `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
--   `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `zutrittsberechtigung_nachname_vorname_unique` (`nachname`,`vorname`,`parlamentarier_id`) COMMENT 'Fachlicher unique constraint',
--   KEY `idx_parlam` (`parlamentarier_id`),
--   KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
--   KEY `idx_lobbyorg` (`ALT_lobbyorganisation_id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='Dauerhafter Badge für einen Gast ("Götti")' AUTO_INCREMENT=63 ;

DROP TABLE IF EXISTS `zutrittsberechtigung_log`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_log` LIKE `zutrittsberechtigung`;
ALTER TABLE `zutrittsberechtigung_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `zutrittsberechtigung_nachname_vorname_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_zutrittsberechtigung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);


DROP TABLE IF EXISTS `zutrittsberechtigung_anhang_log`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_anhang_log` LIKE `zutrittsberechtigung_anhang`;
ALTER TABLE `zutrittsberechtigung_anhang_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_zutrittsberechtigung_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--
-- RELATIONEN DER TABELLE `zutrittsberechtigung`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `ALT_lobbyorganisation_id`
--       `organisation` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

DROP TABLE IF EXISTS `mil_grad_log`;
CREATE TABLE IF NOT EXISTS `mil_grad_log` LIKE `mil_grad`;
ALTER TABLE `mil_grad_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `name_unique`,
  DROP INDEX `abkuerzung_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_mil_grad_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- --------------------------------------------------------

--
-- Constraints der exportierten Tabellen
--

-- --
-- -- Constraints der Tabelle `branche`
-- --
-- ALTER TABLE `branche`
--   ADD CONSTRAINT `fk_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`);
--
-- --
-- -- Constraints der Tabelle `interessenbindung`
-- --
-- ALTER TABLE `interessenbindung`
--   ADD CONSTRAINT `fk_ib_org` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
--   ADD CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);
--
-- --
-- -- Constraints der Tabelle `interessengruppe`
-- --
-- ALTER TABLE `interessengruppe`
--   ADD CONSTRAINT `fk_lg_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);
--
-- --
-- -- Constraints der Tabelle `in_kommission`
-- --
-- ALTER TABLE `in_kommission`
--   ADD CONSTRAINT `fk_in_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`),
--   ADD CONSTRAINT `fk_in_parlamentarier_id` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);
--
-- --
-- -- Constraints der Tabelle `mandat`
-- --
-- ALTER TABLE `mandat`
--   ADD CONSTRAINT `fk_organisations_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
--   ADD CONSTRAINT `fk_zutrittsberechtigung_id` FOREIGN KEY (`zutrittsberechtigung_id`) REFERENCES `zutrittsberechtigung` (`id`);
--
-- --
-- -- Constraints der Tabelle `organisation`
-- --
-- ALTER TABLE `organisation`
--   ADD CONSTRAINT `fk_lo_lg` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
--   ADD CONSTRAINT `fk_lo_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);
--
-- --
-- -- Constraints der Tabelle `organisation_beziehung`
-- --
-- ALTER TABLE `organisation_beziehung`
--   ADD CONSTRAINT `fk_quell_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
--   ADD CONSTRAINT `fk_ziel_organisation_id` FOREIGN KEY (`ziel_organisation_id`) REFERENCES `organisation` (`id`);
--
-- --
-- -- Constraints der Tabelle `parlamentarier`
-- --
-- ALTER TABLE `parlamentarier`
--   ADD CONSTRAINT `fk_beruf_interessengruppe_id` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
--   ADD CONSTRAINT `fk_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`);
--
-- --
-- -- Constraints der Tabelle `parlamentarier_anhang`
-- --
-- ALTER TABLE `parlamentarier_anhang`
--   ADD CONSTRAINT `fk_parlam_anhang` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- --
-- -- Constraints der Tabelle `zutrittsberechtigung`
-- --
-- ALTER TABLE `zutrittsberechtigung`
--   ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
--   ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY (`ALT_lobbyorganisation_id`) REFERENCES `organisation` (`id`),
--   ADD CONSTRAINT `fk_zb_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

-- interessenbindung_jahr

-- 16.03.2014

DROP TABLE IF EXISTS `rat_log`;
CREATE TABLE IF NOT EXISTS `rat_log` LIKE `rat`;
ALTER TABLE `rat_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `idx_rat_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_rat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

DROP TABLE IF EXISTS `kanton_log`;
CREATE TABLE IF NOT EXISTS `kanton_log` LIKE `kanton`;
ALTER TABLE `kanton_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `abkuerzung`,
  DROP INDEX `kantonsnr`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_kanton_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

DROP TABLE IF EXISTS `kanton_jahr_log`;
CREATE TABLE IF NOT EXISTS `kanton_jahr_log` LIKE `kanton_jahr`;
ALTER TABLE `kanton_jahr_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `idx_kanton_jahr_unique`,
  DROP INDEX `kanton_id`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_kanton_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 19.04.2014

-- organisation_anhang

DROP TABLE IF EXISTS `organisation_anhang_log`;
CREATE TABLE IF NOT EXISTS `organisation_anhang_log` LIKE `organisation_anhang`;
ALTER TABLE `organisation_anhang_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_organisation_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- settings

DROP TABLE IF EXISTS `settings_log`;
CREATE TABLE IF NOT EXISTS `settings_log` LIKE `settings`;
ALTER TABLE `settings_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `category_id`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_settings_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- settings_category

DROP TABLE IF EXISTS `settings_category_log`;
CREATE TABLE IF NOT EXISTS `settings_category_log` LIKE `settings_category`;
ALTER TABLE `settings_category_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_settings_category_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- organisation_jahr

DROP TABLE IF EXISTS `organisation_jahr_log`;
CREATE TABLE IF NOT EXISTS `organisation_jahr_log` LIKE `organisation_jahr`;
ALTER TABLE `organisation_jahr_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `idx_organisation_jahr_unique`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_organisation_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 29.11.2014

-- translation_source

DROP TABLE IF EXISTS `translation_source_log`;
CREATE TABLE IF NOT EXISTS `translation_source_log` LIKE `translation_source`;
ALTER TABLE `translation_source_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_translation_source_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- translation_target

DROP TABLE IF EXISTS `translation_target_log`;
CREATE TABLE IF NOT EXISTS `translation_target_log` LIKE `translation_target`;
ALTER TABLE `translation_target_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_translation_target_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- Re: 13.12.2014

-- zutrittsberechtigung

DROP TABLE IF EXISTS `zutrittsberechtigung_log`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_log` LIKE `zutrittsberechtigung`;
ALTER TABLE `zutrittsberechtigung_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP KEY `parlamentarier_person_unique`,
  DROP KEY `person_id`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_zutrittsberechtigung_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 29.06.2015

DROP TABLE IF EXISTS `interessenbindung_jahr_log`;
CREATE TABLE IF NOT EXISTS `interessenbindung_jahr_log` LIKE `interessenbindung_jahr`;
ALTER TABLE `interessenbindung_jahr_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  DROP INDEX `interessenbindung_id`,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_interessenbindung_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- mandat_jahr
DROP TABLE IF EXISTS `mandat_jahr_log`;
CREATE TABLE IF NOT EXISTS `mandat_jahr_log` LIKE `mandat_jahr`;
ALTER TABLE `mandat_jahr_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  DROP INDEX `mandat_id`,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_mandat_jahr_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

--  09.09.2019

DROP TABLE IF EXISTS `parlamentarier_transparenz_log`;
CREATE TABLE IF NOT EXISTS `parlamentarier_transparenz_log` LIKE `parlamentarier_transparenz`;
ALTER TABLE `parlamentarier_transparenz_log`
  CHANGE `id` `id` INT(11) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  DROP INDEX `idx_stichdatum_unique`,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_parlamentarier_transparenz_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 07.11.2020 Lobbypedia

-- Table wissensartikelzieltabelle_log
DROP TABLE IF EXISTS `wissensartikelzieltabelle_log`;
CREATE TABLE IF NOT EXISTS `wissensartikelzieltabelle_log` LIKE `wissensartikelzieltabelle`;
ALTER TABLE `wissensartikelzieltabelle_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_wissensartikelzieltabelle_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- Table wissensartikel_link_log
DROP TABLE IF EXISTS `wissensartikel_link_log`;
CREATE TABLE IF NOT EXISTS `wissensartikel_link_log` LIKE `wissensartikel_link`;
ALTER TABLE `wissensartikel_link_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `idx_nid`,
  -- DROP INDEX `idx_nid_dummy`,
  DROP INDEX `idx_target`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_wissensartikel_link_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 27.11.2020 Add country and interessenraum

-- Table interessenraum_log
DROP TABLE IF EXISTS `interessenraum_log`;
CREATE TABLE IF NOT EXISTS `interessenraum_log` LIKE `interessenraum`;
ALTER TABLE `interessenraum_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_interessenraum_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- Table country_log
DROP TABLE IF EXISTS `country_log`;
CREATE TABLE IF NOT EXISTS `country_log` LIKE `country`;
ALTER TABLE `country_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_country_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 04.12.2021 add in_rat, in_fraktion, in_partei

-- Table in_rat_log
DROP TABLE IF EXISTS `in_rat_log`;
CREATE TABLE IF NOT EXISTS `in_rat_log` LIKE `in_rat`;
ALTER TABLE `in_rat_log`
  DROP INDEX `in_rat_parlamentarier_rat_unique` ,
  DROP INDEX `idx_parlam_freigabe`,
  DROP INDEX `idx_parlam` ,
  DROP INDEX `idx_rat_freigabe`,
  DROP INDEX `idx_rat`,
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP `in_rat_parlamentarier_rat_unique`,
  ADD `in_rat_parlamentarier_rat_unique` VARCHAR(0) NULL DEFAULT NULL COMMENT 'Platzhalter für fachlichen unique constraint' AFTER `updated_date`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_in_rat_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- Table in_partei_log
DROP TABLE IF EXISTS `in_partei_log`;
CREATE TABLE IF NOT EXISTS `in_partei_log` LIKE `in_partei`;
ALTER TABLE `in_partei_log`
  DROP INDEX `in_partei_parlamentarier_partei_unique` ,
  DROP INDEX `idx_parlam_freigabe`,
  DROP INDEX `idx_parlam`,
  DROP INDEX `idx_partei_freigabe` ,
  DROP INDEX `idx_partei`,
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP `in_partei_parlamentarier_partei_unique`,
  ADD `in_partei_parlamentarier_partei_unique` VARCHAR(0) NULL DEFAULT NULL COMMENT 'Platzhalter für fachlichen unique constraint' AFTER `updated_date`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_in_partei_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- Table in_fraktion_log
DROP TABLE IF EXISTS `in_fraktion_log`;
CREATE TABLE IF NOT EXISTS `in_fraktion_log` LIKE `in_fraktion`;
ALTER TABLE `in_fraktion_log`
  DROP INDEX `in_fraktion_parlamentarier_fraktion_unique`,
  DROP INDEX `idx_parlam_freigabe`,
  DROP INDEX `idx_parlam`,
  DROP INDEX `idx_fraktion_freigabe`,
  DROP INDEX `idx_fraktion`,
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP `in_fraktion_parlamentarier_fraktion_unique`,
  ADD `in_fraktion_parlamentarier_fraktion_unique` VARCHAR(0) NULL DEFAULT NULL COMMENT 'Platzhalter für fachlichen unique constraint' AFTER `updated_date`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_in_fraktion_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

