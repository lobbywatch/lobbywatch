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

-- 18.01.2014

ALTER TABLE `organisation` CHANGE `ALT_parlam_verbindung` `ALT_parlam_verbindung` SET( 'einzel', 'mehrere', 'mitglied', 'exekutiv', 'kommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Einschätzung der Verbindung der Organisation ins Parlament';

ALTER TABLE `organisation_log` CHANGE `ALT_parlam_verbindung` `ALT_parlam_verbindung` SET( 'einzel', 'mehrere', 'mitglied', 'exekutiv', 'kommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Einschätzung der Verbindung der Organisation ins Parlament';

-- 19.01.2014

ALTER TABLE `interessenbindung` CHANGE `funktion` `funktion_im_gremium` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'mitglied' COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.';

ALTER TABLE `interessenbindung_log` CHANGE `funktion` `funktion_im_gremium` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'mitglied' COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.';

ALTER TABLE `interessenbindung` CHANGE `deklarationstyp` `deklarationstyp` ENUM('deklarationspflichtig','nicht deklarationspflicht') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Art. 11 Offenlegungspflichten: 1. Beim Amtsantritt und jeweils auf Jahresbeginn unterrichtet jedes Ratsmitglied das Büro schriftlich über seine: a. beruflichen Tätigkeiten; b. Tätigkeiten in Führungs- und Aufsichtsgremien sowie Beiräten und ähnlichen Gremien von schweizerischen und ausländischen Körperschaften, Anstalten und Stiftungen des privaten und des öffentlichen Rechts; c. Beratungs- oder Expertentätigkeiten für Bundesstellen; d. dauernden Leitungs- oder Beratungstätigkeiten für schweizerische und ausländische Interessengruppen; e. Mitwirkung in Kommissionen und anderen Organen des Bundes. | 2.  Die Parlamentsdienste erstellen ein öffentliches Register über die Angaben der Ratsmitglieder. | 3.  Ratsmitglieder, die durch einen Beratungsgegenstand in ihren persönlichen Interessen unmittelbar betroffen sind, weisen auf diese Interessenbindung hin, wenn sie sich im Rat oder in einer Kommission äussern. | 4. Das Berufsgeheimnis im Sinne des Strafgesetzbuches bleibt vorbehalten.';

ALTER TABLE `interessenbindung_log` CHANGE `deklarationstyp` `deklarationstyp` ENUM('deklarationspflichtig','nicht deklarationspflicht') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ist diese Interessenbindung deklarationspflichtig? Art. 11 Offenlegungspflichten: 1. Beim Amtsantritt und jeweils auf Jahresbeginn unterrichtet jedes Ratsmitglied das Büro schriftlich über seine: a. beruflichen Tätigkeiten; b. Tätigkeiten in Führungs- und Aufsichtsgremien sowie Beiräten und ähnlichen Gremien von schweizerischen und ausländischen Körperschaften, Anstalten und Stiftungen des privaten und des öffentlichen Rechts; c. Beratungs- oder Expertentätigkeiten für Bundesstellen; d. dauernden Leitungs- oder Beratungstätigkeiten für schweizerische und ausländische Interessengruppen; e. Mitwirkung in Kommissionen und anderen Organen des Bundes. | 2.  Die Parlamentsdienste erstellen ein öffentliches Register über die Angaben der Ratsmitglieder. | 3.  Ratsmitglieder, die durch einen Beratungsgegenstand in ihren persönlichen Interessen unmittelbar betroffen sind, weisen auf diese Interessenbindung hin, wenn sie sich im Rat oder in einer Kommission äussern. | 4. Das Berufsgeheimnis im Sinne des Strafgesetzbuches bleibt vorbehalten.';

ALTER TABLE `interessenbindung` CHANGE `beschreibung` `beschreibung` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt. ';

ALTER TABLE `interessenbindung_log` CHANGE `beschreibung` `beschreibung` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt. ';

ALTER TABLE `zutrittsberechtigung` CHANGE `funktion` `funktion` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Funktion bei der Zutrittsberechtigung.';

ALTER TABLE `zutrittsberechtigung_log` CHANGE `funktion` `funktion` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Funktion bei der Zutrittsberechtigung.';

ALTER TABLE `zutrittsberechtigung` ADD `zweiter_vorname` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person' AFTER `vorname` ;

ALTER TABLE `zutrittsberechtigung_log` ADD `zweiter_vorname` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Zweiter Vorname der zutrittsberechtigten Person' AFTER `vorname` ;

ALTER TABLE `zutrittsberechtigung` ADD `partei` INT NULL DEFAULT NULL COMMENT 'Parteimitgliedschaft der zutrittsberechtigten Person' AFTER `beruf_interessengruppe_id` ,
ADD INDEX ( `partei` ) ;

ALTER TABLE `zutrittsberechtigung` ADD CONSTRAINT `fk_zutrittsberechtigung_partei_id` FOREIGN KEY ( `partei` ) REFERENCES `lobbywatch`.`partei` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE `zutrittsberechtigung_log` ADD `partei` INT NULL DEFAULT NULL COMMENT 'Parteimitgliedschaft der zutrittsberechtigten Person' AFTER `beruf_interessengruppe_id` ,
ADD INDEX ( `partei` ) ;

ALTER TABLE `zutrittsberechtigung_log` ADD CONSTRAINT `fk_zutrittsberechtigung_partei_id` FOREIGN KEY ( `partei` ) REFERENCES `lobbywatch`.`partei` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE `organisation` CHANGE `vernehmlassung` `vernehmlassung` ENUM( 'immer', 'punktuell', 'nie' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Häufigkeit der Teilname an nationalen Vernehmlassungen';

ALTER TABLE `organisation_log` CHANGE `vernehmlassung` `vernehmlassung` ENUM( 'immer', 'punktuell', 'nie' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Häufigkeit der Teilname an nationalen Vernehmlassungen';

ALTER TABLE `mandat` CHANGE `beschreibung` `beschreibung` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.';

ALTER TABLE `mandat_log` CHANGE `beschreibung` `beschreibung` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgwertet, jedoch in den Resultaten angezeigt.';

'branche' => 'Branche',
'' => 'Interessenbindung',
'' => 'Interessengruppe',
'' => 'In Kommission',
'' => 'Kommission',
'' => 'Mandat',
'' => 'Organisation',
'' => 'Organisation Beziehung',
'' => 'Parlamentarier',
'' => 'Parlamentarieranhang',
'' => 'Partei',
'' => 'Zutrittsberechtigung'

ALTER TABLE `branche` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `interessenbindung` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `interessengruppe` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `in_kommission` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `kommission` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `organisation` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `organisation_beziehung` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `parlamentarier` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `partei` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `zutrittsberechtigung` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `parlamentarier` CHANGE `autorisierung_verschickt_datum` `autorisierung_verschickt_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)';

'interessenbindung' => 'Interessenbindung',
'interessengruppe' => 'Interessengruppe',
'in_kommission' => 'In Kommission',
'kommission' => 'Kommission',
'' => 'Mandat',
'organisation' => 'Organisation',
'organisation_beziehung' => 'Organisation Beziehung',
'parlamentarier' => 'Parlamentarier',
'parlamentarier_anhang' => 'Parlamentarieranhang',
'partei' => 'Partei',
'zutrittsberechtigung' => 'Zutrittsberechtigung'

ALTER TABLE `branche` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `interessenbindung` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `interessengruppe` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `in_kommission` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `kommission` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `mandat` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `organisation` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `organisation_beziehung` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `parlamentarier` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `parlamentarier_anhang` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `partei` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `zutrittsberechtigung` CHANGE `freigabe_visa` `freigabe_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)';

ALTER TABLE `mandat` CHANGE `eingabe_abgeschlossen_datum` `eingabe_abgeschlossen_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_datum` `kontrolliert_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

-- 20.01.2014

ALTER TABLE `organisation` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation_log` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `parlamentarier` CHANGE `parlament_url` `biografie_id` INT NULL DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.';

ALTER TABLE `parlamentarier_log` CHANGE `parlament_url` `biografie_id` INT NULL DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.';

ALTER TABLE `parlamentarier` CHANGE `biografie_id` `parlament_biografie_id` INT( 11 ) NULL DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.';

ALTER TABLE `parlamentarier_log` CHANGE `biografie_id` `parlament_biografie_id` INT( 11 ) NULL DEFAULT NULL COMMENT 'Biographie ID auf Parlament.ch; Dient zur Herstellung eines Links auf die Parlament.ch Seite des Parlamenteriers. Zudem kann die ID für die automatische Verarbeitung gebraucht werden.';

ALTER TABLE `parlamentarier` CHANGE `homepage` `homepage` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Homepage des Parlamentariers' AFTER `email` ;

ALTER TABLE `parlamentarier_log` CHANGE `homepage` `homepage` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Homepage des Parlamentariers' AFTER `email` ;

-- 26.01.2014

DROP TABLE IF EXISTS `fraktion`;
CREATE TABLE IF NOT EXISTS `fraktion` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Fraktion',
  `abkuerzung` varchar(20) NOT NULL COMMENT 'Fraktionsabkürzung',
  `name` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener Name der Fraktion',
  `position` enum('links','rechts','mitte','') DEFAULT NULL COMMENT 'Politische Position der Fraktion',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Fraktionen des Parlamentes';

DROP TABLE IF EXISTS `zutrittsberechtigung_anhang`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung_anhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Zutrittsberechtigunganhangs',
  `zutrittsberechtigung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Zutrittsberechtigung',
  `datei` varchar(255) NOT NULL COMMENT 'Datei',
  `dateiname` varchar(255) NOT NULL COMMENT 'Dateiname ohne Erweiterung',
  `dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Erweiterung der Datei',
  `dateiname_voll` varchar(255) NOT NULL COMMENT 'Dateiname inkl. Erweiterung',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME Type der Datei',
  `encoding` varchar(20) NOT NULL COMMENT 'Encoding der Datei',
  `beschreibung` varchar(150) NOT NULL COMMENT 'Beschreibung des Anhangs',
  `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgäendert am',
  PRIMARY KEY (`id`),
  KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Zutrittsberechtigten';

ALTER TABLE `organisation`
  ADD `interessengruppe2_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 2. Interessengruppe der Organisation.' AFTER `interessengruppe_id`,
  ADD `interessengruppe3_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel Interessengruppe. 3. Interessengruppe der Organisation.' AFTER `interessengruppe2_id`;

-- 08.02.2014

ALTER TABLE `parlamentarier` ADD `kommissionen` VARCHAR( 75 ) NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])' AFTER `kanton` ;

ALTER TABLE `parlamentarier_log` ADD `kommissionen` VARCHAR( 75 ) NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])' AFTER `kanton` ;

ALTER TABLE `parlamentarier` CHANGE `militaerischer_grad` `militaerischer_grad_id` INT( 11 ) NULL DEFAULT NULL COMMENT 'Militärischer Grad, leer (NULL) = kein Militärdienst';

ALTER TABLE `parlamentarier_log` CHANGE `militaerischer_grad` `militaerischer_grad_id` INT( 11 ) NULL DEFAULT NULL COMMENT 'Militärischer Grad, leer (NULL) = kein Militärdienst';

-- 09.02.2014

drop trigger if exists `trg_parlamentarier_log_upd`;
delimiter //
create trigger `trg_parlamentarier_log_upd` after update on `parlamentarier`
for each row
thisTrigger: begin

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
delimiter ;

drop trigger if exists `trg_zutrittsberechtigung_log_upd`;
delimiter //
create trigger `trg_zutrittsberechtigung_log_upd` after update on `zutrittsberechtigung`
for each row
thisTrigger: begin

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
delimiter ;

-- 10.02.2014

ALTER TABLE `parlamentarier`
ADD `arbeitssprache` ENUM('d','f','i') NULL DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch' AFTER `parlament_biografie_id`,
ADD `adresse_firma` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `arbeitssprache`,
ADD `adresse_strasse` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_firma`,
ADD `adresse_zusatz` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_strasse`,
ADD `adresse_plz` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_zusatz`,
ADD `adresse_ort` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_plz`;

ALTER TABLE `parlamentarier_log`
ADD `arbeitssprache` ENUM('d','f','i') NULL DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch' AFTER `parlament_biografie_id`,
ADD `adresse_firma` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `arbeitssprache`,
ADD `adresse_strasse` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_firma`,
ADD `adresse_zusatz` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_strasse`,
ADD `adresse_plz` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_zusatz`,
ADD `adresse_ort` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Wohnadresse des Parlamentariers, falls verfügbar, sonst Postadresse; Adressen erhältlich auf parlament.ch' AFTER `adresse_plz`;

-- 14.02.2014

ALTER TABLE `organisation_beziehung` CHANGE `art` `art` ENUM( 'arbeitet fuer', 'mitglied von', 'tochtergesellschaft von' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation';

ALTER TABLE `organisation_beziehung_log` CHANGE `art` `art` ENUM( 'arbeitet fuer', 'mitglied von', 'tochtergesellschaft von' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation';

ALTER TABLE `mandat` ADD `funktion_im_gremium` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.' AFTER `art`;

ALTER TABLE `mandat_log` ADD `funktion_im_gremium` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.' AFTER `art`;

ALTER TABLE `interessenbindung` CHANGE `funktion_im_gremium` `funktion_im_gremium` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.';

ALTER TABLE `interessenbindung_log` CHANGE `funktion_im_gremium` `funktion_im_gremium` ENUM( 'praesident', 'vizepraesident', 'mitglied' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwatlungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.';

ALTER TABLE `mandat` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `mandat_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

-- 22.02.2014

ALTER TABLE `parlamentarier_anhang` CHANGE `encoding` `encoding` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Encoding der Datei';

ALTER TABLE `parlamentarier_anhang_log` CHANGE `encoding` `encoding` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Encoding der Datei';

ALTER TABLE `zutrittsberechtigung_anhang` CHANGE `encoding` `encoding` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Encoding der Datei';

ALTER TABLE `zutrittsberechtigung_anhang_log` CHANGE `encoding` `encoding` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Encoding der Datei';

-- 01.03.2014
ALTER TABLE `country`
  ADD `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von' AFTER `utc`,
  ADD `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am' AFTER `created_visa`,
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von'AFTER `created_date`,
  ADD `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am'AFTER `updated_visa`;

ALTER TABLE `organisation` ADD `land_id` INT NOT NULL COMMENT 'Land der Organisation' AFTER `ort` ,
ADD INDEX ( `idx_land` ) ;

ALTER TABLE `organisation_log` ADD `land_id` INT NOT NULL COMMENT 'Land der Organisation' AFTER `ort` ,
ADD INDEX ( `idx_land` ) ;

ALTER TABLE `organisation` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation_log` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Patronatskomitee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation_log` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Patronatskomitee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

CREATE TABLE IF NOT EXISTS `interessenraum` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel des Interessenraumes',
  `name` varchar(50) NOT NULL COMMENT 'Name des Interessenbereiches',
  `beschreibung` text NULL DEFAULT NULL COMMENT 'Beschreibung des Interessenraumes',
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
  UNIQUE KEY `interessenraum_name_unique` (`name`) COMMENT 'Fachlicher unique constraint'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Interessenbereiche (Stammdaten)';

ALTER TABLE `organisation` ADD `interessenraum_id` INT NULL DEFAULT NULL COMMENT 'Interessenraum der Organisation' AFTER `land_id` ,
ADD INDEX ( `interessenraum_id` ) ;

ALTER TABLE `organisation_log` ADD `interessenraum_id` INT NULL DEFAULT NULL COMMENT 'Interessenraum der Organisation' AFTER `land_id`;

-- 15.03.2014

ALTER TABLE `kanton`
  ADD `beschreibung` text NULL DEFAULT NULL COMMENT 'Beschreibung des Kantons',
  ADD `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  ADD `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  ADD `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  ADD `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  ADD `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  ADD `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  ADD `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  ADD `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  ADD `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  ADD `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

ALTER TABLE `kanton_jahr`
  ADD `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  ADD `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  ADD `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  ADD `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  ADD `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  ADD `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  ADD `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  ADD `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  ADD `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  ADD `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

ALTER TABLE `rat`
  ADD `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  ADD `eingabe_abgeschlossen_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  ADD `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  ADD `kontrolliert_visa` varchar(10) DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  ADD `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  ADD `freigabe_visa` varchar(10) DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  ADD `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  ADD `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  ADD `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  ADD `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

-- 16.03.2014

ALTER TABLE `parlamentarier` ADD `rat_id` INT NOT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates' AFTER `kanton` ,
ADD `kanton_id` INT NOT NULL COMMENT 'Kantonszugehörigkeit; Fremdschlüssel des Kantons' AFTER `rat_id` ,
ADD INDEX ( `rat_id` ),
ADD INDEX ( `kanton_id` );

ALTER TABLE `parlamentarier_log` ADD `rat_id` INT NOT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates' AFTER `kanton` ,
ADD `kanton_id` INT NOT NULL COMMENT 'Kantonszugehörigkeit; Fremdschlüssel des Kantons' AFTER `rat_id`;

SET @disable_table_logging = 1;
--   // Your update statement goes here.

UPDATE `parlamentarier` p
    SET
    p.kanton_id=(SELECT k.id FROM kanton k WHERE k.abkuerzung=p.kanton),
    p.rat_id=(SELECT r.id FROM rat r WHERE r.abkuerzung=p.ratstyp),
    p.updated_visa='roland';

ALTER TABLE `parlamentarier` ADD CONSTRAINT `fk_rat_id` FOREIGN KEY ( `rat_id` ) REFERENCES `lobbywatch`.`rat` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;

ALTER TABLE `parlamentarier` ADD CONSTRAINT `fk_kanton_id` FOREIGN KEY ( `kanton_id` ) REFERENCES `lobbywatch`.`kanton` (
`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;


SET @disable_table_logging = NULL;

ALTER TABLE `rat`
  ADD `typ` enum('legislativ','exekutiv','judikativ') NOT NULL COMMENT 'Typ des Rates' AFTER `anzahl_mitglieder`,
  ADD `interessenraum_id` int(11) DEFAULT 1 COMMENT 'Interessenraum des Rates' AFTER `typ`,
  ADD `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige' AFTER `interessenraum_id`,
  ADD `gewicht` int(11) NOT NULL COMMENT 'Reihenfolge der Einträge, je grösser desto tiefer ("schwerer")' AFTER `anzeigestufe`,
  CHANGE `anzahl_mitglieder` `anzahl_mitglieder` SMALLINT NULL DEFAULT NULL COMMENT 'Anzahl Mitglieder des Rates',
  CHANGE `beschreibung` `beschreibung` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Eine Beschreibung' AFTER `gewicht`,
  ADD INDEX ( `interessenraum_id` ),
  ADD CONSTRAINT `fk_interessenraum_id` FOREIGN KEY ( `interessenraum_id` ) REFERENCES `lobbywatch`.`interessenraum` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `rat_log`
  ADD `typ` enum('legislativ','exekutiv','judikativ') NOT NULL COMMENT 'Typ des Rates' AFTER `anzahl_mitglieder`,
  ADD `interessenraum_id` int(11) DEFAULT NULL COMMENT 'Interessenraum des Rates' AFTER `typ`,
  ADD `anzeigestufe` int(11) NOT NULL COMMENT 'Anzeigestufe, je höher desto selektiver, >=0 = alle werden angezeigt, >0 = Standardanzeige' AFTER `interessenraum_id`,
  ADD `gewicht` int(11) NOT NULL COMMENT 'Reihenfolge der Einträge, je grösser desto tiefer ("schwerer")' AFTER `anzeigestufe`,
  CHANGE `anzahl_mitglieder` `anzahl_mitglieder` SMALLINT NULL DEFAULT NULL COMMENT 'Anzahl Mitglieder des Rates',
  CHANGE `beschreibung` `beschreibung` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Eine Beschreibung' AFTER `gewicht`;

ALTER TABLE `parlamentarier_log` DROP `ratstyp` ,
DROP `kanton` ;

-- 23.03.2014

ALTER TABLE `organisation` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Patronatskomitee', 'Ausserparlamentarische Kommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation_log` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Patronatskomitee', 'Ausserparlamentarische Kommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

-- 17.04.2014

-- ALTER TABLE `zutrittsberechtigung`
--   ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY (`ALT_lobbyorganisation_id`) REFERENCES `organisation` (`id`);

-- ALTER TABLE `zutrittsberechtigung` DROP FOREIGN KEY `fk_zb_lo` ;

ALTER TABLE `zutrittsberechtigung` DROP FOREIGN KEY `fk_zb_lo` ;

-- ALTER TABLE `zutrittsberechtigung` ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY ( `ALT_lobbyorganisation_id` ) REFERENCES `organisation` (
-- `id`
-- ) ON DELETE NO ACTION ON UPDATE NO ACTION ;

-- 19.04.2014

ALTER TABLE `parlamentarier` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `parlament_biografie_id` ;

ALTER TABLE `parlamentarier_log` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `parlament_biografie_id` ;

ALTER TABLE `zutrittsberechtigung` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `homepage` ;

ALTER TABLE `zutrittsberechtigung_log` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `homepage` ;

ALTER TABLE `organisation` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `handelsregister_url` ;

ALTER TABLE `organisation_log` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `handelsregister_url` ;

ALTER TABLE `partei` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `email` ;

ALTER TABLE `partei_log` ADD `twitter_name` VARCHAR( 50 ) NULL DEFAULT NULL COMMENT 'Twittername' AFTER `email` ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Anhänge zu Organisationen';

ALTER TABLE `organisation_anhang`
  ADD CONSTRAINT `fk_org_anhang` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `partei`
ADD `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23';

ALTER TABLE `partei_log`
ADD `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23';

ALTER TABLE `fraktion`
ADD `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23';

ALTER TABLE `fraktion_log`
ADD `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23';

ALTER TABLE `fraktion` CHANGE `farbcode` `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23' AFTER `position` ;

ALTER TABLE `fraktion_log` CHANGE `farbcode` `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23' AFTER `position` ;

ALTER TABLE `settings`
  ADD `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  ADD `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  ADD `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  ADD `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

ALTER TABLE `settings_category`
  ADD `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  ADD `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  ADD `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  ADD `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

ALTER TABLE `settings` CHANGE `key` `key_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Schlüsselname der Einstellung. NICHT VERÄNDERN. Wird vom Programm vorgegeben';

ALTER TABLE `settings_log` CHANGE `key` `key_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Schlüsselname der Einstellung. NICHT VERÄNDERN. Wird vom Programm vorgegeben';

ALTER TABLE `interessenbindung` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskommittee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `interessenbindung_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskommittee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `mandat` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskommittee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `mandat_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskommittee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `branche`
ADD `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23' AFTER `angaben`;

ALTER TABLE `branche_log`
ADD `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23' AFTER `angaben`;

ALTER TABLE `parlamentarier` CHANGE `arbeitssprache` `arbeitssprache` ENUM( 'd', 'f', 'i', 'de', 'fr', 'it' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch';

ALTER TABLE `parlamentarier_log` CHANGE `arbeitssprache` `arbeitssprache` ENUM( 'd', 'f', 'i', 'de', 'fr', 'it' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch';

SET @disable_triggers = 1;
UPDATE `parlamentarier` SET `arbeitssprache`='de' WHERE `arbeitssprache`='d';
UPDATE `parlamentarier` SET `arbeitssprache`='fr' WHERE `arbeitssprache`='f';
UPDATE `parlamentarier` SET `arbeitssprache`='it' WHERE `arbeitssprache`='i';
UPDATE `parlamentarier_log` SET `arbeitssprache`='de' WHERE `arbeitssprache`='d';
UPDATE `parlamentarier_log` SET `arbeitssprache`='fr' WHERE `arbeitssprache`='f';
UPDATE `parlamentarier_log` SET `arbeitssprache`='it' WHERE `arbeitssprache`='i';
SET @disable_triggers = NULL;

ALTER TABLE `parlamentarier` CHANGE `arbeitssprache` `arbeitssprache` ENUM('de', 'fr', 'it' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch';

ALTER TABLE `parlamentarier_log` CHANGE `arbeitssprache` `arbeitssprache` ENUM('de', 'fr', 'it' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Arbeitssprache des Parlamentariers, erhältlich auf parlament.ch';

ALTER TABLE `parlamentarier` ADD `linkedin_profil_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil' AFTER `twitter_name` ,
ADD `xing_profil_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link' AFTER `linkedin_profil_url` ,
ADD `facebook_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt' AFTER `xing_profil_name` ;

ALTER TABLE `parlamentarier_log` ADD `linkedin_profil_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil' AFTER `twitter_name` ,
ADD `xing_profil_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link' AFTER `linkedin_profil_url` ,
ADD `facebook_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt' AFTER `xing_profil_name` ;

ALTER TABLE `zutrittsberechtigung` ADD `linkedin_profil_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil' AFTER `twitter_name` ,
ADD `xing_profil_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link' AFTER `linkedin_profil_url` ,
ADD `facebook_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt' AFTER `xing_profil_name` ;

ALTER TABLE `zutrittsberechtigung_log` ADD `linkedin_profil_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL zum LinkedIn-Profil' AFTER `twitter_name` ,
ADD `xing_profil_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Profilname in XING (letzter Teil von Link), wird ergänzt mit https://www.xing.com/profile/ zu einem ganzen Link' AFTER `linkedin_profil_url` ,
ADD `facebook_name` VARCHAR( 150 ) NULL DEFAULT NULL COMMENT 'Facebookname (letzter Teil von Link), wird mit https://www.facebook.com/ zu einem ganzen Link ergänzt' AFTER `xing_profil_name` ;

DROP TABLE IF EXISTS `organisation_jahr`;
CREATE TABLE IF NOT EXISTS `organisation_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Jahreswerte einer Organisation',
  `organisation_id` int(11) NOT NULL COMMENT 'Fremdschlüssel eines Kantons',
  `jahr` smallint(6) NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `umsatz` int(11) NOT NULL COMMENT 'Umsatz der Organisation in Franken',
  `gewinn` int(11) NOT NULL COMMENT 'Gewinn der Organisation in Franken',
  `mitarbeiter_weltweit` int(11) DEFAULT NULL COMMENT 'Anzahl Mitarbeiter weltweit',
  `mitarbeiter_schweiz` int(11) DEFAULT NULL COMMENT 'Anzahl Mitarbeiter in der Schweiz',
  `kapital` int(11) DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken',
  `geschaeftsbericht_url` varchar(255) DEFAULT NULL COMMENT 'Link zum Geschäftsbericht',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Jahresbasierte Angaben zu Organisationen';

ALTER TABLE `organisation_jahr`
  ADD CONSTRAINT `fk_organisation_jahr_organisation_id` FOREIGN KEY (`organisation_id`) REFERENCES `organisation` (`id`);

ALTER TABLE `organisation_jahr` CHANGE `kapital` `kapital` INT( 11 ) NULL DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken' AFTER `gewinn` ;

ALTER TABLE `organisation_jahr_log` CHANGE `kapital` `kapital` INT( 11 ) NULL DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken' AFTER `gewinn` ;

ALTER TABLE `organisation_jahr` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle' AFTER `geschaeftsbericht_url` ;

ALTER TABLE `organisation_jahr_log` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle' AFTER `geschaeftsbericht_url` ;
