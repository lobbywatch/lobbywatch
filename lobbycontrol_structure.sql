-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 02. Dez 2013 um 17:04
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
-- Erzeugt am: 02. Dez 2013 um 09:25
--

DROP TABLE IF EXISTS `branche`;
CREATE TABLE IF NOT EXISTS `branche` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Branche',
  `name` varchar(100) NOT NULL COMMENT 'Name der Branche, z.B. Gesundheit, Energie',
  `kommission_id` int(11) DEFAULT NULL COMMENT 'Zuständige Kommission im Parlament',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Branche',
  `angaben` text COMMENT 'Angaben zur Branche',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindung`
--
-- Erzeugt am: 02. Dez 2013 um 09:25
--

DROP TABLE IF EXISTS `interessenbindung`;
CREATE TABLE IF NOT EXISTS `interessenbindung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessenbindung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Parlamentarier',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat') NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung',
  `status` enum('deklariert','nicht-deklariert') NOT NULL DEFAULT 'deklariert' COMMENT 'Status der Interessenbindung',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Monatliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Bezeichnung wird zur Auswertung wahrscheinlich nicht gebraucht.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `interessenbindung_art_parlamentarier_organisation_unique` (`art`,`parlamentarier_id`,`organisation_id`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbyorg` (`organisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessenbindungen von Parlamentariern' AUTO_INCREMENT=337 ;

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

DROP TABLE IF EXISTS `interessengruppe`;
CREATE TABLE IF NOT EXISTS `interessengruppe` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Interessengruppe',
  `name` varchar(150) NOT NULL COMMENT 'Bezeichnung der Interessengruppe',
  `branche_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Branche',
  `beschreibung` text NOT NULL COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `interessengruppe_name_unique` (`name`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_lobbytyp` (`branche_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Interessengruppen einer Branche' AUTO_INCREMENT=10 ;

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

DROP TABLE IF EXISTS `in_kommission`;
CREATE TABLE IF NOT EXISTS `in_kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Kommissionszugehörigkeit',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Parlamentariers',
  `kommission_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Kommission',
  `funktion` set('praesident','vizepraesident','mitglied','delegation') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`) COMMENT 'Fachlicher unique constraint',
  KEY `parlamentarier_id` (`parlamentarier_id`),
  KEY `kommissions_id` (`kommission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Kommissionszugehörigkeit von Parlamentariern' AUTO_INCREMENT=45 ;

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
-- Erzeugt am: 02. Dez 2013 um 09:26
--

DROP TABLE IF EXISTS `kommission`;
CREATE TABLE IF NOT EXISTS `kommission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Kommission',
  `abkuerzung` varchar(15) NOT NULL COMMENT 'Kürzel der Kommission',
  `name` varchar(100) NOT NULL COMMENT 'Ausgeschriebener Name der Kommission',
  `typ` enum('kommission','spezialkommission') NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission ist eine Delegation im weiteren Sinne).',
  `sachbereiche` text NOT NULL COMMENT 'Liste der Sachbereiche der Kommission, abgetrennt durch ";".',
  `abkuerung_delegation` varchar(15) DEFAULT NULL COMMENT 'Abkürzung der Delegation. Delegation im engeren Sinne als Subkommission.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ko_unique_name` (`name`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `kommission_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `kommission_name_unique` (`name`) COMMENT 'Fachlicher unique constraint'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Parlamententskommissionen' AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mandat`
--
-- Erzeugt am: 02. Dez 2013 um 09:26
--

DROP TABLE IF EXISTS `mandat`;
CREATE TABLE IF NOT EXISTS `mandat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zugangsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Zugangsberechtigung.',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation',
  `art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat') DEFAULT NULL COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation',
  `verguetung` int(11) DEFAULT NULL COMMENT 'Monatliche Vergütung CHF für Tätigkeiten aus dieses Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird zur Auswertung wahrscheinlich nicht gebraucht.',
  `autorisiert_datum` date DEFAULT NULL COMMENT 'Autorisiert am',
  `autorisiert_visa` varchar(10) DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mandat_zugangsberechtigung_organisation_art_unique` (`art`,`zugangsberechtigung_id`,`organisation_id`) COMMENT 'Fachlicher unique constraint',
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
-- Erzeugt am: 02. Dez 2013 um 09:26
--

DROP TABLE IF EXISTS `organisation`;
CREATE TABLE IF NOT EXISTS `organisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Lobbyorganisation',
  `name_de` varchar(150) NOT NULL COMMENT 'Name der Organisation. Sollte nur juristischem Namen entsprechen, ohne Zusätze, wie Adresse.',
  `name_fr` varchar(150) DEFAULT NULL COMMENT 'Französischer Name',
  `name_it` varchar(150) DEFAULT NULL COMMENT 'Italienischer Name',
  `ort` varchar(150) DEFAULT NULL COMMENT 'Ort der Organisation',
  `rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich') NOT NULL COMMENT 'Rechtsform der Organisation',
  `typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby') NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL COMMENT 'Häufigkeit der Vernehmlassungsteilnahme',
  `interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. Über die Interessengruppe wird eine Branche zugeordnet.',
  `branche_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Branche.',
  `url` varchar(255) DEFAULT NULL COMMENT 'Link zur Webseite',
  `beschreibung` text NOT NULL COMMENT 'Beschreibung der Lobbyorganisation',
  `ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') NOT NULL COMMENT 'Bisherige Verbindung der Organisation ins Parlament',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organisation_name_unique` (`name_de`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_lobbytyp` (`branche_id`),
  KEY `idx_lobbygroup` (`interessengruppe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Lobbyorganisationen' AUTO_INCREMENT=349 ;

--
-- RELATIONEN DER TABELLE `organisation`:
--   `interessengruppe_id`
--       `interessengruppe` -> `id`
--   `branche_id`
--       `branche` -> `id`
--

--
-- Trigger `organisation`
--
DROP TRIGGER IF EXISTS `trg_organisation_name_ins`;
DELIMITER //
CREATE TRIGGER `trg_organisation_name_ins` BEFORE INSERT ON `organisation`
 FOR EACH ROW begin
    if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
        call organisation_name_de_fr_it_must_be_set;
    end if;
end
//
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_organisation_name_upd`;
DELIMITER //
CREATE TRIGGER `trg_organisation_name_upd` BEFORE UPDATE ON `organisation`
 FOR EACH ROW begin
    if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
        call organisation_name_de_fr_it_must_be_set;
    end if;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `organisation_beziehung`
--
-- Erzeugt am: 02. Dez 2013 um 13:07
--

DROP TABLE IF EXISTS `organisation_beziehung`;
CREATE TABLE IF NOT EXISTS `organisation_beziehung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel einer Organisationsbeziehung',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel Organisation.',
  `ziel_organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Zielorganisation.',
  `art` enum('arbeitet fuer','mitglied von') NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organisation_beziehung_organisation_zielorganisation_art_unique` (`art`,`organisation_id`,`ziel_organisation_id`) COMMENT 'Fachlicher unique constraint',
  KEY `organisation_id` (`organisation_id`),
  KEY `ziel_organisation_id` (`ziel_organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Beschreibt die Beziehung von Organisationen zueinander' AUTO_INCREMENT=1 ;

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
-- Erzeugt am: 02. Dez 2013 um 09:27
--

DROP TABLE IF EXISTS `parlamentarier`;
CREATE TABLE IF NOT EXISTS `parlamentarier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Parlamentariers',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des Parlamentariers',
  `vorname` varchar(50) NOT NULL COMMENT 'Vornahme des Parlamentariers',
  `zweiter_vorname` varchar(50) DEFAULT NULL COMMENT 'Zweiter Vorname des Parlamentariers',
  `ratstyp` enum('NR','SR') NOT NULL COMMENT 'National- oder Ständerat?',
  `kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH') NOT NULL COMMENT 'Kantonskürzel',
  `partei_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Partei. Leer bedeutet parteilos.',
  `parteifunktion` set('mitglied','praesident','vizepraesident','fraktionschef') NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei',
  `im_rat_seit` date NOT NULL COMMENT 'Jahr der Zugehörigkeit zum Parlament',
  `beruf` varchar(150) NOT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Zuordnung (Fremdschlüssel) zu Interessengruppe für den Beruf des Parlamentariers',
  `Geburtstag` date DEFAULT NULL COMMENT 'Geburtstag des Parlamentariers',
  `photo` varchar(100) DEFAULT NULL COMMENT 'Photo des Parlamentariers (JPEG/jpg)',
  `kleinbild` varchar(80) NOT NULL DEFAULT 'leer.png' COMMENT 'Bild 44x62 px oder leer.png',
  `sitzplatz` int(11) NOT NULL COMMENT 'Sitzplatznr im Parlament. Siehe Sitzordnung auf parlament.ch',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Parlamentariers',
  `homepage` varchar(150) DEFAULT NULL COMMENT 'Homepage des Parlamentariers',
  `ALT_kommission` varchar(255) NOT NULL COMMENT 'Kommissionen als Einträge in Tabelle "in_kommission" erfassen. Wird später entfernt. Mitglied in Kommission(en) als Freitext',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parlamentarier_nachname_vorname_unique` (`nachname`,`vorname`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `parlamentarier_rat_sitzplatz` (`ratstyp`,`sitzplatz`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_partei` (`partei_id`),
  KEY `beruf_branche_id` (`beruf_interessengruppe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Parlamentarier' AUTO_INCREMENT=39 ;

--
-- MIME TYPEN DER TABELLE `parlamentarier`:
--   `kleinbild`
--       `Image_PNG`
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
-- Erzeugt am: 02. Dez 2013 um 10:09
--

DROP TABLE IF EXISTS `parlamentarier_anhang`;
CREATE TABLE IF NOT EXISTS `parlamentarier_anhang` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel des Parlamentarieranhangs',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Parlamentariers',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  KEY `parlamentarier_id` (`parlamentarier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Parlamentariern';

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

DROP TABLE IF EXISTS `partei`;
CREATE TABLE IF NOT EXISTS `partei` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Partei',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Parteiabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Partei',
  `gruendung` year(4) DEFAULT NULL COMMENT 'Gründungsjahr der Partei',
  `position` enum('links','rechts','mitte','') DEFAULT NULL COMMENT 'Politische Position der Partei',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `partei_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
  UNIQUE KEY `partei_name_unique` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Politische Parteien des Parlamentes' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--
-- Erzeugt am: 02. Dez 2013 um 09:27
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL COMMENT 'Technischer Schlüssel User',
  `name` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='PHP Generator users';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_permission`
--
-- Erzeugt am: 02. Dez 2013 um 09:27
--

DROP TABLE IF EXISTS `user_permission`;
CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel User Persmissions',
  `user_id` int(11) NOT NULL,
  `page_name` varchar(500) DEFAULT NULL,
  `permission_name` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='PHP Generator user permissions' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_branche`
--
DROP VIEW IF EXISTS `v_branche`;
CREATE TABLE IF NOT EXISTS `v_branche` (
`id` int(11)
,`name` varchar(100)
,`kommission_id` int(11)
,`beschreibung` text
,`angaben` text
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat')
,`status` enum('deklariert','nicht-deklariert')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_interessenbindung_liste`
--
DROP VIEW IF EXISTS `v_interessenbindung_liste`;
CREATE TABLE IF NOT EXISTS `v_interessenbindung_liste` (
`name` varchar(150)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat')
,`status` enum('deklariert','nicht-deklariert')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
,`name` varchar(150)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`status` varchar(16)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` varchar(7)
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
`id` int(11)
,`name` varchar(150)
,`branche_id` int(11)
,`beschreibung` text
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
,`funktion` set('praesident','vizepraesident','mitglied','delegation')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
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
,`funktion` set('praesident','vizepraesident','mitglied','delegation')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_in_kommission_parlamentarier`
--
DROP VIEW IF EXISTS `v_in_kommission_parlamentarier`;
CREATE TABLE IF NOT EXISTS `v_in_kommission_parlamentarier` (
`name` varchar(151)
,`abkuerzung` varchar(20)
,`id` int(11)
,`parlamentarier_id` int(11)
,`kommission_id` int(11)
,`funktion` set('praesident','vizepraesident','mitglied','delegation')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
`id` int(11)
,`abkuerzung` varchar(15)
,`name` varchar(100)
,`typ` enum('kommission','spezialkommission')
,`sachbereiche` text
,`abkuerung_delegation` varchar(15)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_mandat`
--
DROP VIEW IF EXISTS `v_mandat`;
CREATE TABLE IF NOT EXISTS `v_mandat` (
`id` int(11)
,`zugangsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
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
`name` varchar(150)
,`id` int(11)
,`name_de` varchar(150)
,`name_fr` varchar(150)
,`name_it` varchar(150)
,`ort` varchar(150)
,`rechtsform` enum('AG','GmbH','Stiftung','Verein','Informelle Gruppe','Parlamentarische Gruppe','Oeffentlich-rechtlich')
,`typ` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby')
,`vernehmlassung` enum('immer','punktuell','nie')
,`interessengruppe_id` int(11)
,`branche_id` int(11)
,`url` varchar(255)
,`beschreibung` text
,`ALT_parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
,`art` enum('arbeitet fuer','mitglied von')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
`name` varchar(150)
,`id` int(11)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_auftraggeber_fuer`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_auftraggeber_fuer` (
`name` varchar(150)
,`id` int(11)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_mitglieder`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglieder`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_mitglieder` (
`name` varchar(150)
,`id` int(11)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_beziehung_mitglied_von`
--
DROP VIEW IF EXISTS `v_organisation_beziehung_mitglied_von`;
CREATE TABLE IF NOT EXISTS `v_organisation_beziehung_mitglied_von` (
`name` varchar(150)
,`id` int(11)
,`organisation_id` int(11)
,`ziel_organisation_id` int(11)
,`art` enum('arbeitet fuer','mitglied von')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
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
`name` varchar(151)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat')
,`status` enum('deklariert','nicht-deklariert')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_organisation_parlamentarier_indirekt`
--
DROP VIEW IF EXISTS `v_organisation_parlamentarier_indirekt`;
CREATE TABLE IF NOT EXISTS `v_organisation_parlamentarier_indirekt` (
`beziehung` varchar(8)
,`name` varchar(151)
,`id` int(11)
,`parlamentarier_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`status` varchar(16)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` varchar(7)
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
`name` varchar(151)
,`id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`zweiter_vorname` varchar(50)
,`ratstyp` enum('NR','SR')
,`kanton` enum('AG','AR','AI','BL','BS','BE','FR','GE','GL','GR','JU','LU','NE','NW','OW','SH','SZ','SO','SG','TI','TG','UR','VD','VS','ZG','ZH')
,`partei_id` int(11)
,`parteifunktion` set('mitglied','praesident','vizepraesident','fraktionschef')
,`im_rat_seit` date
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`Geburtstag` date
,`photo` varchar(100)
,`kleinbild` varchar(80)
,`sitzplatz` int(11)
,`email` varchar(100)
,`homepage` varchar(150)
,`ALT_kommission` varchar(255)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_parlamentarier_anhang`
--
DROP VIEW IF EXISTS `v_parlamentarier_anhang`;
CREATE TABLE IF NOT EXISTS `v_parlamentarier_anhang` (
`id` int(11)
,`parlamentarier_id` int(11)
,`datei` varchar(255)
,`beschreibung` varchar(150)
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_partei`
--
DROP VIEW IF EXISTS `v_partei`;
CREATE TABLE IF NOT EXISTS `v_partei` (
`id` int(11)
,`abkuerzung` varchar(20)
,`name` varchar(100)
,`gruendung` year(4)
,`position` enum('links','rechts','mitte','')
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
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
`id` int(11)
,`name` varchar(10)
,`password` varchar(255)
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
-- Stellvertreter-Struktur des Views `v_zugangsberechtigung`
--
DROP VIEW IF EXISTS `v_zugangsberechtigung`;
CREATE TABLE IF NOT EXISTS `v_zugangsberechtigung` (
`name` varchar(151)
,`id` int(11)
,`parlamentarier_id` int(11)
,`nachname` varchar(100)
,`vorname` varchar(50)
,`funktion` varchar(150)
,`beruf` varchar(150)
,`beruf_interessengruppe_id` int(11)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`ALT_lobbyorganisation_id` int(11)
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zugangsberechtigung_mandate`
--
DROP VIEW IF EXISTS `v_zugangsberechtigung_mandate`;
CREATE TABLE IF NOT EXISTS `v_zugangsberechtigung_mandate` (
`parlamentarier_id` int(11)
,`organisation_name` varchar(150)
,`name` varchar(151)
,`funktion` varchar(150)
,`id` int(11)
,`zugangsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zugangsberechtigung_mit_mandaten`
--
DROP VIEW IF EXISTS `v_zugangsberechtigung_mit_mandaten`;
CREATE TABLE IF NOT EXISTS `v_zugangsberechtigung_mit_mandaten` (
`parlamentarier_id` int(11)
,`name` varchar(151)
,`funktion` varchar(150)
,`organisation_name` varchar(150)
,`id` int(11)
,`zugangsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` enum('mitglied','geschaeftsfuehrend','vorstand','taetig','beirat')
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` enum('otto','rebecca','thomas','bane','roland')
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `v_zugangsberechtigung_mit_mandaten_indirekt`
--
DROP VIEW IF EXISTS `v_zugangsberechtigung_mit_mandaten_indirekt`;
CREATE TABLE IF NOT EXISTS `v_zugangsberechtigung_mit_mandaten_indirekt` (
`beziehung` varchar(8)
,`parlamentarier_id` int(11)
,`name` varchar(151)
,`funktion` varchar(150)
,`organisation_name` varchar(150)
,`id` int(11)
,`zugangsberechtigung_id` int(11)
,`organisation_id` int(11)
,`art` varchar(18)
,`verguetung` int(11)
,`beschreibung` varchar(150)
,`autorisiert_datum` date
,`autorisiert_visa` varchar(10)
,`notizen` text
,`freigabe_von` varchar(7)
,`freigabe_datum` timestamp
,`created_visa` varchar(10)
,`created_date` timestamp
,`updated_visa` varchar(10)
,`updated_date` timestamp
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zugangsberechtigung`
--
-- Erzeugt am: 02. Dez 2013 um 09:27
--

DROP TABLE IF EXISTS `zugangsberechtigung`;
CREATE TABLE IF NOT EXISTS `zugangsberechtigung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zugangsberechtigung',
  `parlamentarier_id` int(11) NOT NULL COMMENT 'Fremdschlüssel zu Parlamentarier',
  `nachname` varchar(100) NOT NULL COMMENT 'Nachname des berechtigten Persion',
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname der berechtigten Person',
  `funktion` varchar(150) DEFAULT NULL COMMENT 'Angegebene Funktion bei der Zugangsberechtigung',
  `beruf` varchar(150) DEFAULT NULL COMMENT 'Beruf des Parlamentariers',
  `beruf_interessengruppe_id` int(11) DEFAULT NULL COMMENT 'Fremschlüssel zur Interessengruppe für den Beruf',
  `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `freigabe_von` enum('otto','rebecca','thomas','bane','roland') DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `ALT_lobbyorganisation_id` int(11) DEFAULT NULL COMMENT 'Wird später entfernt. Fremschlüssel zur Lobbyorganisation',
  `created_visa` varchar(10) DEFAULT NULL COMMENT 'Erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `zugangsberechtigung_nachname_vorname_unique` (`nachname`,`vorname`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_parlam` (`parlamentarier_id`),
  KEY `idx_lobbygroup` (`beruf_interessengruppe_id`),
  KEY `idx_lobbyorg` (`ALT_lobbyorganisation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")' AUTO_INCREMENT=63 ;

--
-- RELATIONEN DER TABELLE `zugangsberechtigung`:
--   `beruf_interessengruppe_id`
--       `interessengruppe` -> `id`
--   `ALT_lobbyorganisation_id`
--       `organisation` -> `id`
--   `parlamentarier_id`
--       `parlamentarier` -> `id`
--

-- --------------------------------------------------------

--
-- Struktur des Views `v_branche`
--
DROP TABLE IF EXISTS `v_branche`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_branche` AS select `t`.`id` AS `id`,`t`.`name` AS `name`,`t`.`kommission_id` AS `kommission_id`,`t`.`beschreibung` AS `beschreibung`,`t`.`angaben` AS `angaben`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `branche` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung`
--
DROP TABLE IF EXISTS `v_interessenbindung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung` AS select `t`.`id` AS `id`,`t`.`parlamentarier_id` AS `parlamentarier_id`,`t`.`organisation_id` AS `organisation_id`,`t`.`art` AS `art`,`t`.`status` AS `status`,`t`.`verguetung` AS `verguetung`,`t`.`beschreibung` AS `beschreibung`,`t`.`autorisiert_datum` AS `autorisiert_datum`,`t`.`autorisiert_visa` AS `autorisiert_visa`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `interessenbindung` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_liste`
--
DROP TABLE IF EXISTS `v_interessenbindung_liste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_liste` AS select `organisation`.`name` AS `name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`freigabe_von` AS `freigabe_von`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from (`v_interessenbindung` `interessenbindung` join `v_organisation` `organisation` on((`interessenbindung`.`organisation_id` = `organisation`.`id`))) order by `organisation`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessenbindung_liste_indirekt`
--
DROP TABLE IF EXISTS `v_interessenbindung_liste_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessenbindung_liste_indirekt` AS select 'direkt' AS `beziehung`,`interessenbindung_liste`.`name` AS `name`,`interessenbindung_liste`.`id` AS `id`,`interessenbindung_liste`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung_liste`.`organisation_id` AS `organisation_id`,`interessenbindung_liste`.`art` AS `art`,`interessenbindung_liste`.`status` AS `status`,`interessenbindung_liste`.`verguetung` AS `verguetung`,`interessenbindung_liste`.`beschreibung` AS `beschreibung`,`interessenbindung_liste`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung_liste`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung_liste`.`notizen` AS `notizen`,`interessenbindung_liste`.`freigabe_von` AS `freigabe_von`,`interessenbindung_liste`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung_liste`.`created_visa` AS `created_visa`,`interessenbindung_liste`.`created_date` AS `created_date`,`interessenbindung_liste`.`updated_visa` AS `updated_visa`,`interessenbindung_liste`.`updated_date` AS `updated_date` from `v_interessenbindung_liste` `interessenbindung_liste` union select 'indirekt' AS `beziehung`,`organisation`.`name` AS `name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`freigabe_von` AS `freigabe_von`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from ((`v_interessenbindung` `interessenbindung` join `v_organisation_beziehung` `organisation_beziehung` on((`interessenbindung`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer');

-- --------------------------------------------------------

--
-- Struktur des Views `v_interessengruppe`
--
DROP TABLE IF EXISTS `v_interessengruppe`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_interessengruppe` AS select `t`.`id` AS `id`,`t`.`name` AS `name`,`t`.`branche_id` AS `branche_id`,`t`.`beschreibung` AS `beschreibung`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `interessengruppe` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission`
--
DROP TABLE IF EXISTS `v_in_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission` AS select `t`.`id` AS `id`,`t`.`parlamentarier_id` AS `parlamentarier_id`,`t`.`kommission_id` AS `kommission_id`,`t`.`funktion` AS `funktion`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `in_kommission` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_liste`
--
DROP TABLE IF EXISTS `v_in_kommission_liste`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_liste` AS select `kommission`.`abkuerzung` AS `abkuerzung`,`kommission`.`name` AS `name`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`freigabe_von` AS `freigabe_von`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date` from (`v_in_kommission` `in_kommission` join `v_kommission` `kommission` on((`in_kommission`.`kommission_id` = `kommission`.`id`))) order by `kommission`.`abkuerzung`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_in_kommission_parlamentarier`
--
DROP TABLE IF EXISTS `v_in_kommission_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_in_kommission_parlamentarier` AS select `parlamentarier`.`name` AS `name`,`partei`.`abkuerzung` AS `abkuerzung`,`in_kommission`.`id` AS `id`,`in_kommission`.`parlamentarier_id` AS `parlamentarier_id`,`in_kommission`.`kommission_id` AS `kommission_id`,`in_kommission`.`funktion` AS `funktion`,`in_kommission`.`notizen` AS `notizen`,`in_kommission`.`freigabe_von` AS `freigabe_von`,`in_kommission`.`freigabe_datum` AS `freigabe_datum`,`in_kommission`.`created_visa` AS `created_visa`,`in_kommission`.`created_date` AS `created_date`,`in_kommission`.`updated_visa` AS `updated_visa`,`in_kommission`.`updated_date` AS `updated_date` from ((`v_in_kommission` `in_kommission` join `v_parlamentarier` `parlamentarier` on((`in_kommission`.`parlamentarier_id` = `parlamentarier`.`id`))) left join `v_partei` `partei` on((`parlamentarier`.`partei_id` = `partei`.`id`))) order by `parlamentarier`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_kommission`
--
DROP TABLE IF EXISTS `v_kommission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_kommission` AS select `t`.`id` AS `id`,`t`.`abkuerzung` AS `abkuerzung`,`t`.`name` AS `name`,`t`.`typ` AS `typ`,`t`.`sachbereiche` AS `sachbereiche`,`t`.`abkuerung_delegation` AS `abkuerung_delegation`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `kommission` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_mandat`
--
DROP TABLE IF EXISTS `v_mandat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_mandat` AS select `t`.`id` AS `id`,`t`.`zugangsberechtigung_id` AS `zugangsberechtigung_id`,`t`.`organisation_id` AS `organisation_id`,`t`.`art` AS `art`,`t`.`verguetung` AS `verguetung`,`t`.`beschreibung` AS `beschreibung`,`t`.`autorisiert_datum` AS `autorisiert_datum`,`t`.`autorisiert_visa` AS `autorisiert_visa`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `mandat` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation`
--
DROP TABLE IF EXISTS `v_organisation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation` AS select concat(`t`.`name_de`) AS `name`,`t`.`id` AS `id`,`t`.`name_de` AS `name_de`,`t`.`name_fr` AS `name_fr`,`t`.`name_it` AS `name_it`,`t`.`ort` AS `ort`,`t`.`rechtsform` AS `rechtsform`,`t`.`typ` AS `typ`,`t`.`vernehmlassung` AS `vernehmlassung`,`t`.`interessengruppe_id` AS `interessengruppe_id`,`t`.`branche_id` AS `branche_id`,`t`.`url` AS `url`,`t`.`beschreibung` AS `beschreibung`,`t`.`ALT_parlam_verbindung` AS `ALT_parlam_verbindung`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `organisation` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung`
--
DROP TABLE IF EXISTS `v_organisation_beziehung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung` AS select `t`.`id` AS `id`,`t`.`organisation_id` AS `organisation_id`,`t`.`ziel_organisation_id` AS `ziel_organisation_id`,`t`.`art` AS `art`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `organisation_beziehung` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_arbeitet_fuer`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_arbeitet_fuer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_arbeitet_fuer` AS select `organisation`.`name` AS `name`,`organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`freigabe_von` AS `freigabe_von`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_auftraggeber_fuer`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_auftraggeber_fuer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_auftraggeber_fuer` AS select `organisation`.`name` AS `name`,`organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`freigabe_von` AS `freigabe_von`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer') order by `organisation`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_mitglieder`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_mitglieder`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_mitglieder` AS select `organisation`.`name` AS `name`,`organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`freigabe_von` AS `freigabe_von`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_beziehung_mitglied_von`
--
DROP TABLE IF EXISTS `v_organisation_beziehung_mitglied_von`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_beziehung_mitglied_von` AS select `organisation`.`name` AS `name`,`organisation_beziehung`.`id` AS `id`,`organisation_beziehung`.`organisation_id` AS `organisation_id`,`organisation_beziehung`.`ziel_organisation_id` AS `ziel_organisation_id`,`organisation_beziehung`.`art` AS `art`,`organisation_beziehung`.`notizen` AS `notizen`,`organisation_beziehung`.`freigabe_von` AS `freigabe_von`,`organisation_beziehung`.`freigabe_datum` AS `freigabe_datum`,`organisation_beziehung`.`created_visa` AS `created_visa`,`organisation_beziehung`.`created_date` AS `created_date`,`organisation_beziehung`.`updated_visa` AS `updated_visa`,`organisation_beziehung`.`updated_date` AS `updated_date` from (`v_organisation_beziehung` `organisation_beziehung` join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'mitglied von') order by `organisation`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier` AS select `parlamentarier`.`name` AS `name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`freigabe_von` AS `freigabe_von`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date` from (`v_interessenbindung` `interessenbindung` join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) order by `parlamentarier`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_organisation_parlamentarier_indirekt`
--
DROP TABLE IF EXISTS `v_organisation_parlamentarier_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_organisation_parlamentarier_indirekt` AS select 'direkt' AS `beziehung`,`organisation_parlamentarier`.`name` AS `name`,`organisation_parlamentarier`.`id` AS `id`,`organisation_parlamentarier`.`parlamentarier_id` AS `parlamentarier_id`,`organisation_parlamentarier`.`organisation_id` AS `organisation_id`,`organisation_parlamentarier`.`art` AS `art`,`organisation_parlamentarier`.`status` AS `status`,`organisation_parlamentarier`.`verguetung` AS `verguetung`,`organisation_parlamentarier`.`beschreibung` AS `beschreibung`,`organisation_parlamentarier`.`autorisiert_datum` AS `autorisiert_datum`,`organisation_parlamentarier`.`autorisiert_visa` AS `autorisiert_visa`,`organisation_parlamentarier`.`notizen` AS `notizen`,`organisation_parlamentarier`.`freigabe_von` AS `freigabe_von`,`organisation_parlamentarier`.`freigabe_datum` AS `freigabe_datum`,`organisation_parlamentarier`.`created_visa` AS `created_visa`,`organisation_parlamentarier`.`created_date` AS `created_date`,`organisation_parlamentarier`.`updated_visa` AS `updated_visa`,`organisation_parlamentarier`.`updated_date` AS `updated_date`,`organisation_parlamentarier`.`organisation_id` AS `connector_organisation_id` from `v_organisation_parlamentarier` `organisation_parlamentarier` union select 'indirekt' AS `beziehung`,`parlamentarier`.`name` AS `name`,`interessenbindung`.`id` AS `id`,`interessenbindung`.`parlamentarier_id` AS `parlamentarier_id`,`interessenbindung`.`organisation_id` AS `organisation_id`,`interessenbindung`.`art` AS `art`,`interessenbindung`.`status` AS `status`,`interessenbindung`.`verguetung` AS `verguetung`,`interessenbindung`.`beschreibung` AS `beschreibung`,`interessenbindung`.`autorisiert_datum` AS `autorisiert_datum`,`interessenbindung`.`autorisiert_visa` AS `autorisiert_visa`,`interessenbindung`.`notizen` AS `notizen`,`interessenbindung`.`freigabe_von` AS `freigabe_von`,`interessenbindung`.`freigabe_datum` AS `freigabe_datum`,`interessenbindung`.`created_visa` AS `created_visa`,`interessenbindung`.`created_date` AS `created_date`,`interessenbindung`.`updated_visa` AS `updated_visa`,`interessenbindung`.`updated_date` AS `updated_date`,`organisation_beziehung`.`ziel_organisation_id` AS `connector_organisation_id` from ((`v_organisation_beziehung` `organisation_beziehung` join `v_interessenbindung` `interessenbindung` on((`organisation_beziehung`.`organisation_id` = `interessenbindung`.`organisation_id`))) join `v_parlamentarier` `parlamentarier` on((`interessenbindung`.`parlamentarier_id` = `parlamentarier`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer');

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier`
--
DROP TABLE IF EXISTS `v_parlamentarier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier` AS select concat(`t`.`vorname`,' ',`t`.`nachname`) AS `name`,`t`.`id` AS `id`,`t`.`nachname` AS `nachname`,`t`.`vorname` AS `vorname`,`t`.`zweiter_vorname` AS `zweiter_vorname`,`t`.`ratstyp` AS `ratstyp`,`t`.`kanton` AS `kanton`,`t`.`partei_id` AS `partei_id`,`t`.`parteifunktion` AS `parteifunktion`,`t`.`im_rat_seit` AS `im_rat_seit`,`t`.`beruf` AS `beruf`,`t`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`t`.`Geburtstag` AS `Geburtstag`,`t`.`photo` AS `photo`,`t`.`kleinbild` AS `kleinbild`,`t`.`sitzplatz` AS `sitzplatz`,`t`.`email` AS `email`,`t`.`homepage` AS `homepage`,`t`.`ALT_kommission` AS `ALT_kommission`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `parlamentarier` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_parlamentarier_anhang`
--
DROP TABLE IF EXISTS `v_parlamentarier_anhang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_parlamentarier_anhang` AS select `t`.`id` AS `id`,`t`.`parlamentarier_id` AS `parlamentarier_id`,`t`.`datei` AS `datei`,`t`.`beschreibung` AS `beschreibung`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `parlamentarier_anhang` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_partei`
--
DROP TABLE IF EXISTS `v_partei`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_partei` AS select `t`.`id` AS `id`,`t`.`abkuerzung` AS `abkuerzung`,`t`.`name` AS `name`,`t`.`gruendung` AS `gruendung`,`t`.`position` AS `position`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `partei` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_user`
--
DROP TABLE IF EXISTS `v_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user` AS select `t`.`id` AS `id`,`t`.`name` AS `name`,`t`.`password` AS `password` from `user` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_user_permission`
--
DROP TABLE IF EXISTS `v_user_permission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_permission` AS select `t`.`id` AS `id`,`t`.`user_id` AS `user_id`,`t`.`page_name` AS `page_name`,`t`.`permission_name` AS `permission_name` from `user_permission` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zugangsberechtigung`
--
DROP TABLE IF EXISTS `v_zugangsberechtigung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zugangsberechtigung` AS select concat(`t`.`vorname`,' ',`t`.`nachname`) AS `name`,`t`.`id` AS `id`,`t`.`parlamentarier_id` AS `parlamentarier_id`,`t`.`nachname` AS `nachname`,`t`.`vorname` AS `vorname`,`t`.`funktion` AS `funktion`,`t`.`beruf` AS `beruf`,`t`.`beruf_interessengruppe_id` AS `beruf_interessengruppe_id`,`t`.`notizen` AS `notizen`,`t`.`freigabe_von` AS `freigabe_von`,`t`.`freigabe_datum` AS `freigabe_datum`,`t`.`ALT_lobbyorganisation_id` AS `ALT_lobbyorganisation_id`,`t`.`created_visa` AS `created_visa`,`t`.`created_date` AS `created_date`,`t`.`updated_visa` AS `updated_visa`,`t`.`updated_date` AS `updated_date` from `zugangsberechtigung` `t`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zugangsberechtigung_mandate`
--
DROP TABLE IF EXISTS `v_zugangsberechtigung_mandate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zugangsberechtigung_mandate` AS select `zugangsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`organisation`.`name` AS `organisation_name`,`zugangsberechtigung`.`name` AS `name`,`zugangsberechtigung`.`funktion` AS `funktion`,`mandat`.`id` AS `id`,`mandat`.`zugangsberechtigung_id` AS `zugangsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`notizen` AS `notizen`,`mandat`.`freigabe_von` AS `freigabe_von`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from ((`v_zugangsberechtigung` `zugangsberechtigung` join `v_mandat` `mandat` on((`zugangsberechtigung`.`id` = `mandat`.`zugangsberechtigung_id`))) join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) order by `organisation`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zugangsberechtigung_mit_mandaten`
--
DROP TABLE IF EXISTS `v_zugangsberechtigung_mit_mandaten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zugangsberechtigung_mit_mandaten` AS select `zugangsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zugangsberechtigung`.`name` AS `name`,`zugangsberechtigung`.`funktion` AS `funktion`,`organisation`.`name` AS `organisation_name`,`mandat`.`id` AS `id`,`mandat`.`zugangsberechtigung_id` AS `zugangsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`notizen` AS `notizen`,`mandat`.`freigabe_von` AS `freigabe_von`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from ((`v_zugangsberechtigung` `zugangsberechtigung` left join `v_mandat` `mandat` on((`zugangsberechtigung`.`id` = `mandat`.`zugangsberechtigung_id`))) left join `v_organisation` `organisation` on((`mandat`.`organisation_id` = `organisation`.`id`))) order by `zugangsberechtigung`.`name`;

-- --------------------------------------------------------

--
-- Struktur des Views `v_zugangsberechtigung_mit_mandaten_indirekt`
--
DROP TABLE IF EXISTS `v_zugangsberechtigung_mit_mandaten_indirekt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_zugangsberechtigung_mit_mandaten_indirekt` AS select 'direkt' AS `beziehung`,`zugangsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zugangsberechtigung`.`name` AS `name`,`zugangsberechtigung`.`funktion` AS `funktion`,`zugangsberechtigung`.`organisation_name` AS `organisation_name`,`zugangsberechtigung`.`id` AS `id`,`zugangsberechtigung`.`zugangsberechtigung_id` AS `zugangsberechtigung_id`,`zugangsberechtigung`.`organisation_id` AS `organisation_id`,`zugangsberechtigung`.`art` AS `art`,`zugangsberechtigung`.`verguetung` AS `verguetung`,`zugangsberechtigung`.`beschreibung` AS `beschreibung`,`zugangsberechtigung`.`autorisiert_datum` AS `autorisiert_datum`,`zugangsberechtigung`.`autorisiert_visa` AS `autorisiert_visa`,`zugangsberechtigung`.`notizen` AS `notizen`,`zugangsberechtigung`.`freigabe_von` AS `freigabe_von`,`zugangsberechtigung`.`freigabe_datum` AS `freigabe_datum`,`zugangsberechtigung`.`created_visa` AS `created_visa`,`zugangsberechtigung`.`created_date` AS `created_date`,`zugangsberechtigung`.`updated_visa` AS `updated_visa`,`zugangsberechtigung`.`updated_date` AS `updated_date` from `v_zugangsberechtigung_mit_mandaten` `zugangsberechtigung` union select 'indirekt' AS `beziehung`,`zugangsberechtigung`.`parlamentarier_id` AS `parlamentarier_id`,`zugangsberechtigung`.`name` AS `name`,`zugangsberechtigung`.`funktion` AS `funktion`,`organisation`.`name` AS `organisation_name`,`mandat`.`id` AS `id`,`mandat`.`zugangsberechtigung_id` AS `zugangsberechtigung_id`,`mandat`.`organisation_id` AS `organisation_id`,`mandat`.`art` AS `art`,`mandat`.`verguetung` AS `verguetung`,`mandat`.`beschreibung` AS `beschreibung`,`mandat`.`autorisiert_datum` AS `autorisiert_datum`,`mandat`.`autorisiert_visa` AS `autorisiert_visa`,`mandat`.`notizen` AS `notizen`,`mandat`.`freigabe_von` AS `freigabe_von`,`mandat`.`freigabe_datum` AS `freigabe_datum`,`mandat`.`created_visa` AS `created_visa`,`mandat`.`created_date` AS `created_date`,`mandat`.`updated_visa` AS `updated_visa`,`mandat`.`updated_date` AS `updated_date` from (((`v_zugangsberechtigung` `zugangsberechtigung` join `v_mandat` `mandat` on((`zugangsberechtigung`.`id` = `mandat`.`zugangsberechtigung_id`))) join `v_organisation_beziehung` `organisation_beziehung` on((`mandat`.`organisation_id` = `organisation_beziehung`.`organisation_id`))) join `v_organisation` `organisation` on((`organisation_beziehung`.`ziel_organisation_id` = `organisation`.`id`))) where (`organisation_beziehung`.`art` = 'arbeitet fuer');

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
  ADD CONSTRAINT `fk_ib_org` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

--
-- Constraints der Tabelle `interessengruppe`
--
ALTER TABLE `interessengruppe`
  ADD CONSTRAINT `fk_lg_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `in_kommission`
--
ALTER TABLE `in_kommission`
  ADD CONSTRAINT `fk_in_kommission_id` FOREIGN KEY (`kommission_id`) REFERENCES `kommission` (`id`),
  ADD CONSTRAINT `fk_in_parlamentarier_id` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);

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
  ADD CONSTRAINT `fk_lo_lt` FOREIGN KEY (`branche_id`) REFERENCES `branche` (`id`);

--
-- Constraints der Tabelle `organisation_beziehung`
--
ALTER TABLE `organisation_beziehung`
  ADD CONSTRAINT `fk_quell_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_ziel_organisation_id` FOREIGN KEY (`ziel_organisation_id`) REFERENCES `organisation` (`id`);

--
-- Constraints der Tabelle `parlamentarier`
--
ALTER TABLE `parlamentarier`
  ADD CONSTRAINT `fk_beruf_interessengruppe_id` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_partei_id` FOREIGN KEY (`partei_id`) REFERENCES `partei` (`id`);

--
-- Constraints der Tabelle `parlamentarier_anhang`
--
ALTER TABLE `parlamentarier_anhang`
  ADD CONSTRAINT `fk_parlam_anhang` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `zugangsberechtigung`
--
ALTER TABLE `zugangsberechtigung`
  ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`beruf_interessengruppe_id`) REFERENCES `interessengruppe` (`id`),
  ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY (`ALT_lobbyorganisation_id`) REFERENCES `organisation` (`id`),
  ADD CONSTRAINT `fk_zb_parlam` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
