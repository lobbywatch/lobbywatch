-- DB changes

-- 16.12.2013

ALTER TABLE `kommission` CHANGE `typ` `typ` ENUM( 'kommission', 'subkommission', 'spezialkommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission umfasst auch Delegationen im weiteren Sinne).';

ALTER TABLE `kommission` CHANGE `zugehoerige_kommission` `mutter_kommission` INT( 11 ) NULL DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen). Also die "Oberkommission".';,
ADD INDEX ( `zugehoerige_kommission` ) ;

SET @disable_table_logging = 1;
UPDATE `kommission` SET `zugehoerige_kommission`=NULL;
SET @disable_table_logging = NULL;

ALTER TABLE `kommission` ADD CONSTRAINT `fk_parent_kommission_id` FOREIGN KEY ( `zugehoerige_kommission` ) REFERENCES `kommission` ( `id` ) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE `kommission` DROP `abkuerung_delegation` ;

ALTER TABLE `in_kommission` CHANGE `funktion` `funktion` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission';

ALTER TABLE `kommission` ADD `beschreibung` TEXT NULL COMMENT 'Beschreibung der Kommission' AFTER `typ` ;

ALTER TABLE `parlamentarier` CHANGE `parteifunktion` `parteifunktion` ENUM( 'mitglied', 'praesident', 'vizepraesident' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei';

ALTER TABLE `parlamentarier` ADD `fraktionsfunktion` ENUM( 'mitglied', 'praesident', 'vizepraesident' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Fraktion' AFTER `parteifunktion`;

ALTER TABLE `interessenbindung` ADD `deklarationstyp` ENUM( 'deklarationspflichtig', 'nicht deklarationspflicht', '', '' ) NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig?' AFTER `art` ;

ALTER TABLE `interessenbindung` CHANGE `deklarationstyp` `deklarationstyp` ENUM( 'deklarationspflichtig', 'nicht deklarationspflicht' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig?';

-- _log

ALTER TABLE `kommission_log`
CHANGE `typ` `typ` ENUM( 'kommission', 'subkommission', 'spezialkommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission umfasst auch Delegationen im weiteren Sinne).',
CHANGE `mutter_kommission` `mutter_kommission` INT( 11 ) NULL DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen). Also die "Oberkommission".',
DROP `abkuerung_delegation` ;

-- ALTER TABLE `in_kommission_log`
-- CHANGE `funktion` `funktion` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission';

ALTER TABLE `kommission_log` ADD `beschreibung` TEXT NULL DEFAULT NULL COMMENT 'Beschreibung der Kommission' AFTER `typ` ;

ALTER TABLE `parlamentarier_log` CHANGE `parteifunktion` `parteifunktion` ENUM( 'mitglied', 'praesident', 'vizepraesident' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Partei';

ALTER TABLE `parlamentarier_log` ADD `fraktionsfunktion` ENUM( 'mitglied', 'praesident', 'vizepraesident' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Fraktion' AFTER `parteifunktion`;

ALTER TABLE `interessenbindung_log` ADD `deklarationstyp` ENUM( 'deklarationspflichtig', 'nicht deklarationspflicht', '', '' ) NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig?' AFTER `art` ;

ALTER TABLE `interessenbindung_log` CHANGE `deklarationstyp` `deklarationstyp` ENUM( 'deklarationspflichtig', 'nicht deklarationspflicht' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig?';

-- 05.01.2014

ALTER TABLE `partei` CHANGE `gruendung` `gruendung` DATE NULL DEFAULT NULL COMMENT 'Gründungsjahr der Partei. Wenn der genaue Tag unbekannt ist, den 1. Januar wählen.';
ALTER TABLE `partei_log` CHANGE `gruendung` `gruendung` DATE NULL DEFAULT NULL COMMENT 'Gründungsjahr der Partei. Wenn der genaue Tag unbekannt ist, den 1. Januar wählen.';
