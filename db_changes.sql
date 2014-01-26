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
