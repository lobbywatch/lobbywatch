-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Nov 2013 um 23:08
-- Server Version: 5.6.12
-- PHP-Version: 5.5.1

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

DROP TABLE IF EXISTS `branche`;
CREATE TABLE IF NOT EXISTS `branche` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Branche',
  `name` varchar(255) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `angaben` text NOT NULL COMMENT 'Angaben zur Branche',
  `kommission` varchar(255) NOT NULL COMMENT 'Kommission im Parlament (Kommissionsabkürzung)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Wirtschaftsbranchen und Parlam. Kommissionen' AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung`
--

DROP TABLE IF EXISTS `interessenbindung`;
CREATE TABLE IF NOT EXISTS `interessenbindung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Interessenbindung',
  `beschreibung` varchar(255) NOT NULL COMMENT 'Bezeichung der Interessenbindung',
  `status` enum('deklariert','nicht-deklariert','zutrittsberechtigung') NOT NULL DEFAULT 'deklariert' COMMENT 'Deklariert oder nicht deklariert?',
  `parlamentarier_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Parlamentarier',
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Branche',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Interessengruppe',
  `lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Lobbyorganisation',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`),
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `idx_lobbyorg` (`lobbyorganisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern' AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessengruppe`
--

DROP TABLE IF EXISTS `interessengruppe`;
CREATE TABLE IF NOT EXISTS `interessengruppe` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Interessengruppe',
  `bezeichnung` varchar(255) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung zur Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschluessel Branche',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`),
  KEY `idx_lobbytyp` (`branche_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche' AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommission`
--

DROP TABLE IF EXISTS `kommission`;
CREATE TABLE IF NOT EXISTS `kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Kommission',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `name` varchar(120) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Kommission',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen' AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lobbyorganisation`
--

DROP TABLE IF EXISTS `lobbyorganisation`;
CREATE TABLE IF NOT EXISTS `lobbyorganisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Lobbyorganisation',
  `name` varchar(255) NOT NULL COMMENT 'Name der Lobbyorganisation',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Lobbyorganisation',
  `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby') NOT NULL COMMENT 'Typ der Lobbyorganisation',
  `url` varchar(255) NOT NULL COMMENT 'Link zur Webseite',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL,
  `parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') NOT NULL,
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Branche',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Interessengruppe',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`),
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen' AUTO_INCREMENT=349 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier`
--

DROP TABLE IF EXISTS `parlamentarier`;
CREATE TABLE IF NOT EXISTS `parlamentarier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel des Parlamentariers',
  `nachname` varchar(150) NOT NULL COMMENT 'Nachname des Parlamentariers',
  `vorname` varchar(150) NOT NULL COMMENT 'Vornahme des Parlamentariers',
  `beruf` varchar(255) NOT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Branche für den Beruf des Parlamentariers',
  `ratstyp` enum('NR','SR') NOT NULL COMMENT 'National- oder Ständerat?',
  `kanton` varchar(2) NOT NULL,
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschluessel Partei',
  `partei` varchar(20) NOT NULL COMMENT 'Partei als Text',
  `parteifunktion` varchar(255) NOT NULL DEFAULT 'Mitglied' COMMENT 'Parteiamt als Freitext',
  `im_rat_seit` varchar(4) NOT NULL,
  `kommission` varchar(255) NOT NULL COMMENT 'Mitglied in Kommission(en) als Freitext',
  `kleinbild` varchar(80) NOT NULL DEFAULT 'leer.png' COMMENT 'Dateiname des Photos (44x62 px), muss auf Server vorhanden sein oder leer.png',
  `sitzplatz` int(11) NOT NULL,
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`),
  KEY `idx_partei` (`partei_id`),
  KEY `beruf_branche_id` (`beruf_branche_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier' AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `partei`
--

DROP TABLE IF EXISTS `partei`;
CREATE TABLE IF NOT EXISTS `partei` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Partei',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `bemerkung` text COMMENT 'Bemerkung zur Partei',
  `gruendung` date DEFAULT NULL COMMENT 'Gruendungsjahr der Partei',
  `position` enum('links','rechts','mitte','') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeaendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeaendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pt_name` (`abkuerzung`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zugangsberechtigung`
--

DROP TABLE IF EXISTS `zugangsberechtigung`;
CREATE TABLE IF NOT EXISTS `zugangsberechtigung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schluessel der Zugangsberechtigung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschluessel zu Parlamentarier',
  `nachname` varchar(160) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(160) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `funktion` varchar(255) DEFAULT NULL COMMENT 'Angegebene Funktion bei der Zugangsberechtigung',
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremschluessel zu Branche',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschluessel zur Interessengruppe',
  `lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Fremschluessel zur Lobbyorganisation',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`),
  KEY `idx_lobbyorg` (`lobbyorganisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Dauerhafte Zugangsberechtigungen für einen Gast ("Badge")' AUTO_INCREMENT=63 ;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `interessenbindung`
--
ALTER TABLE `interessenbindung`
  ADD CONSTRAINT `fk_ib_lobbygroup` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_ib_lobbyorg` FOREIGN KEY (`lobbyorganisation_id`) REFERENCES `lobbyorganisation` (`id`),
  ADD CONSTRAINT `fk_ib_lobbytyp` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

--
-- Constraints der Tabelle `interessengruppe`
--
ALTER TABLE `interessengruppe`
  ADD CONSTRAINT `fk_lg_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `lobbyorganisation`
--
ALTER TABLE `lobbyorganisation`
  ADD CONSTRAINT `fk_lo_lg` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_lo_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `parlamentarier`
--
ALTER TABLE `parlamentarier`
  ADD CONSTRAINT `fk_beruf_branche_id` FOREIGN KEY (`beruf_branche_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`);

--
-- Constraints der Tabelle `zugangsberechtigung`
--
ALTER TABLE `zugangsberechtigung`
  ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY (`lobbyorganisation_id`) REFERENCES `lobbyorganisation` (`id`),
  ADD CONSTRAINT `fk_zb_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`),
  ADD CONSTRAINT `fk_zb_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
