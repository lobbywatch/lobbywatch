-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 12. Nov 2013 um 21:57
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
-- Datenbank: `lobbycontrol`
--
CREATE DATABASE IF NOT EXISTS `lobbycontrol` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `lobbycontrol`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `branche`
--
-- Erzeugt am: 12. Nov 2013 um 18:30
--

DROP TABLE IF EXISTS `branche`;
CREATE TABLE IF NOT EXISTS `branche` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Branche',
  `name` varchar(255) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `angaben` text NOT NULL COMMENT 'Angaben zur Branche',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zusändige Kommission im Parlament',
  `OLD_kommission` varchar(255) NOT NULL COMMENT 'Kommission im Parlament (Kommissionsabkürzung)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `kommission_id` (`kommission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen und Parlam. Kommissionen' AUTO_INCREMENT=19 ;

--
-- RELATIONEN DER TABELLE `branche`:
--   `kommission_id`
--       `kommission` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung`
--
-- Erzeugt am: 12. Nov 2013 um 18:24
--

DROP TABLE IF EXISTS `interessenbindung`;
CREATE TABLE IF NOT EXISTS `interessenbindung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessenbindung',
  `beschreibung` varchar(255) NOT NULL COMMENT 'Bezeichung der Interessenbindung',
  `status` enum('deklariert','nicht-deklariert','zutrittsberechtigung') NOT NULL DEFAULT 'deklariert' COMMENT 'Deklariert oder nicht deklariert?',
  `parlamentarier_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `bindungsart` enum('mitglied','geschäftsführend','vorstand','exekutiv') DEFAULT 'mitglied' COMMENT 'Art der Bindung',
  `OLD_branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe',
  `lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Lobbyorganisation',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbytyp` (`OLD_branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `idx_lobbyorg` (`lobbyorganisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern' AUTO_INCREMENT=337 ;

--
-- RELATIONEN DER TABELLE `interessenbindung`:
--   `interessengruppe_id`
--       `interessengruppe` -> `id`
--   `lobbyorganisation_id`
--       `organisation` -> `id`
--   `OLD_branche_id`
--       `branche` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessengruppe`
--
-- Erzeugt am: 11. Nov 2013 um 06:38
--

DROP TABLE IF EXISTS `interessengruppe`;
CREATE TABLE IF NOT EXISTS `interessengruppe` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessengruppe',
  `bezeichnung` varchar(255) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung zur Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `idx_lobbytyp` (`branche_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche' AUTO_INCREMENT=10 ;

--
-- RELATIONEN DER TABELLE `interessengruppe`:
--   `branche_id`
--       `branche` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission`
--
-- Erzeugt am: 11. Nov 2013 um 06:39
--

DROP TABLE IF EXISTS `kommission`;
CREATE TABLE IF NOT EXISTS `kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Kommission',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `name` varchar(120) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Kommission',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen' AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat`
--
-- Erzeugt am: 12. Nov 2013 um 18:33
--

DROP TABLE IF EXISTS `mandat`;
CREATE TABLE IF NOT EXISTS `mandat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel eines Mandates',
  `beschreibung` varchar(255) NOT NULL COMMENT 'Umschreibung des Mandates',
  `zugangsberechtigung_id` int(11) NOT NULL COMMENT 'Fremschlüssel Zugangsberechtigung',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Lobbyorganisation',
  `funktion` enum('mitglied','geschäftsführend','vorstand','mitarbeiter','mandat') DEFAULT NULL COMMENT 'Funktion des Mandatsträgers innerhalb der Organisation',
  PRIMARY KEY (`id`),
  KEY `zugangsberechtigung_id` (`zugangsberechtigung_id`),
  KEY `organisations_id` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Mandate der Zugangsberechtigten' AUTO_INCREMENT=1 ;

--
-- RELATIONEN DER TABELLE `mandat`:
--   `organisation_id`
--       `organisation` -> `id`
--   `zugangsberechtigung_id`
--       `zugangsberechtigung` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation`
--
-- Erzeugt am: 12. Nov 2013 um 17:53
--

DROP TABLE IF EXISTS `organisation`;
CREATE TABLE IF NOT EXISTS `organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name` varchar(255) NOT NULL COMMENT 'Name der Lobbyorganisation',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Lobbyorganisation',
  `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby') NOT NULL COMMENT 'Typ der Lobbyorganisation',
  `url` varchar(255) NOT NULL COMMENT 'Link zur Webseite',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL COMMENT 'Häufigkeit der Teilnahme an Vernehmlassungen',
  `bisherige_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') NOT NULL COMMENT 'Bisherige Verbindung der Organisation ins Parlament',
  `OLD_branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `idx_lobbytyp` (`OLD_branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen' AUTO_INCREMENT=349 ;

--
-- RELATIONEN DER TABELLE `organisation`:
--   `interessengruppe_id`
--       `interessengruppe` -> `id`
--   `OLD_branche_id`
--       `branche` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier`
--
-- Erzeugt am: 12. Nov 2013 um 18:35
--

DROP TABLE IF EXISTS `parlamentarier`;
CREATE TABLE IF NOT EXISTS `parlamentarier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentariers',
  `nachname` varchar(150) NOT NULL COMMENT 'Nachname des Parlamentariers',
  `vorname` varchar(150) NOT NULL COMMENT 'Vornahme des Parlamentariers',
  `beruf` varchar(255) NOT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `ratstyp` enum('NR','SR') NOT NULL COMMENT 'National- oder Ständerat?',
  `kanton` char(2) NOT NULL COMMENT 'Kantonskürzel',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei',
  `partei` varchar(20) NOT NULL COMMENT 'Partei als Text',
  `parteifunktion` enum('mitglied','präsident','vizepräsident','fraktionschef') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei',
  `ALT_parteifunktion` varchar(255) NOT NULL DEFAULT 'Mitglied' COMMENT 'Parteiamt als Freitext',
  `im_rat_seit` varchar(4) NOT NULL COMMENT 'Mitglied im Parlament seit',
  `Geburtstag` date DEFAULT NULL COMMENT 'Geburtstag des Parlamentariers',
  `1_kommission_id` int(11) DEFAULT NULL COMMENT '1. Kommissionszugehörigkeit des Parlamentariers',
  `2_kommission_id` int(11) DEFAULT NULL COMMENT '2. Kommissionszugehörigkeit des Parlamentariers',
  `3_kommission_id` int(11) DEFAULT NULL COMMENT '3. Kommissionszugehörigkeit des Parlamentariers',
  `4_kommission_id` int(11) DEFAULT NULL COMMENT '4. Kommissionszugehörigkeit des Parlamentariers',
  `5_kommission_id` int(11) DEFAULT NULL COMMENT '5. Kommissionszugehörigkeit des Parlamentariers',
  `ALT_kommission` varchar(255) NOT NULL COMMENT 'Mitglied in Kommission(en) als Freitext',
  `kleinbild` varchar(80) NOT NULL DEFAULT 'leer.png' COMMENT 'Dateiname des Photos (44x62 px), muss auf Server vorhanden sein oder leer.png',
  `sitzplatz` int(11) NOT NULL COMMENT 'Sitzplatznr im Parlament',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `idx_partei` (`partei_id`),
  KEY `beruf_branche_id` (`beruf_interessengruppe_id`),
  KEY `1_kommission_id` (`1_kommission_id`),
  KEY `2_kommission_id` (`2_kommission_id`),
  KEY `3_kommission_id` (`3_kommission_id`),
  KEY `4_kommission_id` (`4_kommission_id`),
  KEY `5_kommission_id` (`5_kommission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier' AUTO_INCREMENT=39 ;

--
-- RELATIONEN DER TABELLE `parlamentarier`:
--   `5_kommission_id`
--       `kommission` -> `id`
--   `1_kommission_id`
--       `kommission` -> `id`
--   `2_kommission_id`
--       `mandat` -> `id`
--   `3_kommission_id`
--       `kommission` -> `id`
--   `4_kommission_id`
--       `kommission` -> `id`
--   `beruf_interessengruppe_id`
--       `branche` -> `id`
--   `partei_id`
--       `partei` -> `id`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei`
--
-- Erzeugt am: 11. Nov 2013 um 06:42
--

DROP TABLE IF EXISTS `partei`;
CREATE TABLE IF NOT EXISTS `partei` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Partei',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `bemerkung` text COMMENT 'Bemerkung zur Partei',
  `gruendung` date DEFAULT NULL COMMENT 'Gründungsjahr der Partei',
  `position` enum('links','rechts','mitte','') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pt_name` (`abkuerzung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zugangsberechtigung`
--
-- Erzeugt am: 12. Nov 2013 um 18:37
--

DROP TABLE IF EXISTS `zugangsberechtigung`;
CREATE TABLE IF NOT EXISTS `zugangsberechtigung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zu Parlamentarier',
  `nachname` varchar(160) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(160) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `funktion` varchar(255) DEFAULT NULL COMMENT 'Angegebene Funktion bei der Zugangsberechtigung',
  `beruf` varchar(255) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `ALT_branche_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zu Branche',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `ALT_lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Lobbyorganisation',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbytyp` (`ALT_branche_id`),
  KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
  KEY `idx_lobbyorg` (`ALT_lobbyorganisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")' AUTO_INCREMENT=63 ;

--
-- RELATIONEN DER TABELLE `zugangsberechtigung`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `ALT_lobbyorganisation_id`
--       `organisation` -> `id`
--   `ALT_branche_id`
--       `branche` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `branche`
--
ALTER TABLE `branche`
  ADD CONSTRAINT `fk_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`);

--
-- Constraints der Tabelle `interessenbindung`
--
ALTER TABLE `interessenbindung`
  ADD CONSTRAINT `fk_ib_lobbygroup` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_ib_lobbyorg` FOREIGN KEY (`lobbyorganisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_ib_lobbytyp` FOREIGN KEY (`OLD_branche_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

--
-- Constraints der Tabelle `interessengruppe`
--
ALTER TABLE `interessengruppe`
  ADD CONSTRAINT `fk_lg_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `mandat`
--
ALTER TABLE `mandat`
  ADD CONSTRAINT `fk_organisations_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_zugangsberechtigung_id` FOREIGN KEY (`zugangsberechtigung_id`) REFERENCES `zugangsberechtigung` (`id`);

--
-- Constraints der Tabelle `organisation`
--
ALTER TABLE `organisation`
  ADD CONSTRAINT `fk_lo_lg` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_lo_lt` FOREIGN KEY (`OLD_branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `parlamentarier`
--
ALTER TABLE `parlamentarier`
  ADD CONSTRAINT `fk_5_kommission_id` FOREIGN KEY (`5_kommission_id`) REFERENCES `kommission` (`id`),
  ADD CONSTRAINT `fk_1_kommission_id` FOREIGN KEY (`1_kommission_id`) REFERENCES `kommission` (`id`),
  ADD CONSTRAINT `fk_2_kommission_id` FOREIGN KEY (`2_kommission_id`) REFERENCES `mandat` (`id`),
  ADD CONSTRAINT `fk_3_kommission_id` FOREIGN KEY (`3_kommission_id`) REFERENCES `kommission` (`id`),
  ADD CONSTRAINT `fk_4_kommission_id` FOREIGN KEY (`4_kommission_id`) REFERENCES `kommission` (`id`),
  ADD CONSTRAINT `fk_beruf_branche_id` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`);

--
-- Constraints der Tabelle `zugangsberechtigung`
--
ALTER TABLE `zugangsberechtigung`
  ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY (`ALT_lobbyorganisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_zb_lt` FOREIGN KEY (`ALT_branche_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_zb_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
