-- DB changes

-- Clean trailing whitespace

UPDATE `zutrittsberechtigung` SET `nachname`=TRIM(`nachname`),`vorname`=TRIM(`vorname`);

UPDATE `parlamentarier` SET `nachname`=TRIM(`nachname`),`vorname`=TRIM(`vorname`);

UPDATE `organisation` SET `name_de`=TRIM(`name_de`);

UPDATE `interessenbindung` SET `beschreibung`=TRIM(`beschreibung`);

UPDATE `interessengruppe` SET `name`=TRIM(`name`),`beschreibung`=TRIM(`beschreibung`);

UPDATE `branche` SET `name`=TRIM(`name`);

-- DML

ALTER TABLE `partei`
CHANGE `created_by` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
CHANGE `created_date` `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
CHANGE `updated_by` `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
CHANGE `updated_date` `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `parlamentarier`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `kommission`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `interessenbindung`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `zugangsberechtigung`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `lobbyorganisation`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `interessengruppe`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `branche`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `mandat`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `kommissionsarbeit`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `organisation_beziehung`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `parlamentarier_anhang`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';

ALTER TABLE `mil_grad`
ADD COLUMN `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Erstellt von',
ADD COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
ADD COLUMN `updated_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abgäendert von',
ADD COLUMN `updated_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Abgäendert am';


UPDATE `lobbywatch`.`branche` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbywatch`.`parlamentarier` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbywatch`.`kommission` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbywatch`.`interessenbindung` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbywatch`.`zugangsberechtigung` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbywatch`.`lobbyorganisation` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbywatch`.`interessengruppe` SET
`created_visa` = 'import',
`updated_visa` = 'import';

ALTER TABLE `parlamentarier` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `sitzplatz` ;

ALTER TABLE `interessenbindung` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `autorisiert_datum` ;

ALTER TABLE `mandat` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `autorisiert_datum` ;

ALTER TABLE `zugangsberechtigung` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `beruf_interessengruppe_id` ;

ALTER TABLE `partei` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `position` ;

ALTER TABLE `in_kommission` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `kommission_id` ;

ALTER TABLE `interessengruppe` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `branche_id` ;

ALTER TABLE `kommission` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `beschreibung` ;

ALTER TABLE `organisation` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `interessengruppe_id` ;

ALTER TABLE `organisation_beziehung` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `ziel_organisation_id` ;

ALTER TABLE `branche` ADD `notizen` TEXT NULL COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `kommission_id` ;

SELECT `art` , `parlamentarier_id` , `organisation_id`
FROM `interessenbindung`
GROUP BY `art` , `parlamentarier_id` , `organisation_id`
HAVING count( `art` ) >1
AND count( `parlamentarier_id` ) >1
AND count( `organisation_id` ) >1
LIMIT 0 , 30;

UPDATE `lobbywatch`.`partei` SET `created_visa` = 'roland',`updated_visa` = 'roland' WHERE `partei`.`id` =3;

UPDATE `parlamentarier` l LEFT JOIN partei p ON l.`ALT_partei` = p.abkuerzung SET l.`partei_id` = p.id,
l.`updated_visa` = 'roland'

SELECT p.abkuerzung, `ALT_partei` FROM `parlamentarier` l left join partei p on l.partei_id=p.id

UPDATE lobbywatch.`parlamentarier` n JOIN lobbywatch_old.`parlamentarier` o ON o.id_parlam = n.id SET n.im_rat_seit = o.im_rat_seit;

UPDATE lobbywatch.`parlamentarier` n JOIN lobbywatch_old.`parlamentarier` o ON o.id_parlam = n.id SET n.im_rat_seit = STR_TO_DATE(CONCAT('01.01.', o.im_rat_seit),'%d.%m.%Y')


ALTER TABLE `t_partei`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)',
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `notizen`;

ALTER TABLE `t_parlamentarier`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_kommission`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_interessenbindung`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_zugangsberechtigung`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_organisation`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_interessengruppe`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_branche`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_mandat`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_in_kommission`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_organisation_beziehung`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `notizen`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;

ALTER TABLE `t_parlamentarier_anhang`
ADD COLUMN `freigabe_von` enum('otto','rebecca','thomas','bane','roland') NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)' AFTER `beschreibung`,
ADD COLUMN `freigabe_datum` TIMESTAMP NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)' AFTER `freigabe_von`;


-- File metadata
ALTER TABLE `parlamentarier_anhang`
ADD `dateiname` VARCHAR( 255 ) NOT NULL COMMENT 'Dateiname ohne Erweiterung' AFTER `datei` ,
ADD `dateierweiterung` VARCHAR( 15 ) NOT NULL COMMENT 'Erweiterung der Datei' AFTER `dateiname` ,
ADD `dateiname_voll` VARCHAR( 255 ) NOT NULL COMMENT 'Dateiname mit Erweiterung' AFTER `datei` ,
ADD `mime_type` VARCHAR( 100 ) NOT NULL COMMENT 'MIME Type der Datei' AFTER `dateierweiterung` ,
ADD `encoding` VARCHAR( 20 ) NOT NULL COMMENT 'Encoding der Datei' AFTER `mime_type` ;

ALTER TABLE `parlamentarier`
ADD `photo_dateiname` VARCHAR( 255 ) NOT NULL COMMENT 'Photodateiname ohne Erweiterung' AFTER `photo` ,
ADD `photo_dateierweiterung` VARCHAR( 15 ) NOT NULL COMMENT 'Erweiterung der Photodatei' AFTER `photo_dateiname` ,
ADD `photo_dateiname_voll` VARCHAR( 255 ) NOT NULL COMMENT 'Photodateiname mit Erweiterung' AFTER `photo_dateierweiterung` ,
ADD `photo_mime_type` VARCHAR( 100 ) NOT NULL COMMENT 'MIME Type des Photos' AFTER `photo_dateiname_voll`;


-- Geschlecht

ALTER TABLE `parlamentarier` ADD `geschlecht` ENUM( 'M', 'F' ) NULL COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau' AFTER `beruf_interessengruppe_id` ;

ALTER TABLE `parlamentarier_log` ADD `geschlecht` ENUM( 'M', 'F' ) NULL COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau' AFTER `beruf_interessengruppe_id` ;

ALTER TABLE `zugangsberechtigung` ADD `geschlecht` ENUM( 'M', 'F' ) NULL COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau' AFTER `beruf_interessengruppe_id` ;

ALTER TABLE `zugangsberechtigung_log` ADD `geschlecht` ENUM( 'M', 'F' ) NULL COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau' AFTER `beruf_interessengruppe_id` ;


ALTER TABLE `parlamentarier` CHANGE `geschlecht` `geschlecht` ENUM( 'M', 'F' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau';

ALTER TABLE `parlamentarier_log` CHANGE `geschlecht` `geschlecht` ENUM( 'M', 'F' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau';

ALTER TABLE `zugangsberechtigung` CHANGE `geschlecht` `geschlecht` ENUM( 'M', 'F' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau';

ALTER TABLE `zugangsberechtigung_log` CHANGE `geschlecht` `geschlecht` ENUM( 'M', 'F' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'M' COMMENT 'Geschlecht des Parlamentariers, M=Mann, F=Frau';

--  Rename freigabe_von to freigabe_visa

ALTER TABLE `branche` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `interessenbindung` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `interessengruppe` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `in_kommission` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `kommission` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `mandat` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `organisation` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `organisation_beziehung` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `parlamentarier` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `parlamentarier_anhang` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `partei` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

ALTER TABLE `zugangsberechtigung` CHANGE `freigabe_von` `freigabe_visa` ENUM( 'otto', 'rebecca', 'thomas', 'bane', 'roland' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Freigabe von (Freigabe = Daten sind fertig)';

-- created_visa NN
ALTER TABLE `branche` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `interessenbindung` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `interessengruppe` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `in_kommission` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `kommission` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `mandat` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `organisation` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `organisation_beziehung` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `parlamentarier` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `parlamentarier_anhang` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `partei` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `zutrittsberechtigung` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';
ALTER TABLE `mil_grad` CHANGE `created_visa` `created_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Datensatz erstellt von';

-- von bis

ALTER TABLE `interessenbindung` ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn' AFTER `beschreibung` ,
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende Leer (NULL) = aktuell gültig' AFTER `von` ;

ALTER TABLE `interessenbindung` CHANGE `von` `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt';

ALTER TABLE `interessenbindung` CHANGE `bis` `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag';

ALTER TABLE `interessenbindung` ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Interessenbindung, leer (NULL) = unbekannt',
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Interessenbindung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag';

ALTER TABLE `in_kommission` ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Kommissionszugehörigkeit, leer (NULL) = unbekannt',
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Kommissionszugehörigkeit, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag';

ALTER TABLE `mandat` ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn des Mandates, leer (NULL) = unbekannt',
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende des Mandates, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag';

ALTER TABLE `organisation_beziehung` ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Organisationsbeziehung, leer (NULL) = unbekannt',
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Organisationsbeziehung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag';

ALTER TABLE `zugangsberechtigung` ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Zugangsberechtigung, leer (NULL) = unbekannt',
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Zugangsberechtigung, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag';

-- Copy data from test to prod

SELECT id, beschreibung, sachbereiche FROM `csvimsne_lobbywatchtest`.`kommission`

UPDATE `csvimsne_lobbywatch`.`kommission` k
INNER JOIN `csvimsne_lobbywatchtest`.`kommission` t
ON k.`id` = t.`id`
SET
k.`beschreibung` = t.`beschreibung`,
k.`sachbereiche` = t.`sachbereiche`


-- Workflow fields

ALTER TABLE `branche` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `interessenbindung` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `interessengruppe` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `in_kommission` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `kommission` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `mandat` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `organisation` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `organisation_beziehung` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `parlamentarier` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `partei` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

ALTER TABLE `zutrittsberechtigung` ADD `eingabe_abgeschlossen_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.' AFTER `notizen` ,
ADD `eingabe_abgeschlossen_date` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)' AFTER `eingabe_abgeschlossen_visa` ,
ADD `kontrolliert_visa` VARCHAR( 10 ) NULL DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.' AFTER `eingabe_abgeschlossen_date` ,
ADD `kontrolliert_date` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)' AFTER `kontrolliert_visa` ;

-- rename _date → _datum

ALTER TABLE `branche` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `interessenbindung` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `interessengruppe` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `in_kommission` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `kommission` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `mandat` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `organisation` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `organisation_beziehung` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `parlamentarier` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `partei` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

ALTER TABLE `zutrittsberechtigung` CHANGE `eingabe_abgeschlossen_date` `eingabe_abgeschlossen_datum` DATETIME NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
CHANGE `kontrolliert_date` `kontrolliert_datum` DATETIME NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)';

-- autorisiert_datum

ALTER TABLE `parlamentarier`
ADD `autorisiert_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.' AFTER `kontrolliert_datum`,
ADD `autorisiert_datum` DATE NULL DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen und Zutrittsberechtigungen vom Parlamentarier autorisiert wurden.' AFTER `autorisiert_visa`;

ALTER TABLE `zutrittsberechtigung`
ADD `autorisiert_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.' AFTER `kontrolliert_visa`,
ADD `autorisiert_datum` DATE NULL DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass der Eintrag vom Parlamentarier autorisiert wurde.' AFTER `autorisiert_visa`;

-- zutrittsberechtigung.autorisierung_verschickt

ALTER TABLE `zutrittsberechtigung`
ADD `autorisierung_verschickt_visa` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt durch' AFTER `kontrolliert_datum`,
ADD `autorisierung_verschickt_datum` DATETIME NULL DEFAULT NULL COMMENT 'Autorisierungsanfrage verschickt am. (Leer/NULL bedeutet noch keine Anfrage verschickt.)' AFTER `autorisierung_verschickt_visa`;

-- 16.12.2013

ALTER TABLE `kommission` CHANGE `typ` `typ` ENUM( 'kommission', 'subkommission', 'spezialkommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'kommission' COMMENT 'Typ einer Kommission (Spezialkommission umfasst auch Delegationen im weiteren Sinne).';

ALTER TABLE `kommission` CHANGE `zugehoerige_kommission` `mutter_kommission` INT( 11 ) NULL DEFAULT NULL COMMENT 'Zugehörige Kommission von Delegationen im engeren Sinne (=Subkommissionen). Also die "Oberkommission".',
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

ALTER TABLE `organisation_jahr`
CHANGE `umsatz` `umsatz` INT( 11 ) NULL COMMENT 'Umsatz der Organisation in Franken',
CHANGE `gewinn` `gewinn` INT( 11 ) NULL COMMENT 'Gewinn der Organisation in Franken';

ALTER TABLE `organisation_jahr_log`
CHANGE `umsatz` `umsatz` INT( 11 ) NULL COMMENT 'Umsatz der Organisation in Franken',
CHANGE `gewinn` `gewinn` INT( 11 ) NULL COMMENT 'Gewinn der Organisation in Franken';

ALTER TABLE `organisation` CHANGE `typ` `typ` SET( 'EinzelOrganisation', 'DachOrganisation', 'MitgliedsOrganisation', 'LeistungsErbringer', 'dezidierteLobby', 'Gemeinnuetzig', 'Gewinnorientiert' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.';

ALTER TABLE `organisation_log` CHANGE `typ` `typ` SET( 'EinzelOrganisation', 'DachOrganisation', 'MitgliedsOrganisation', 'LeistungsErbringer', 'dezidierteLobby', 'Gemeinnuetzig', 'Gewinnorientiert' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Typ der Organisation. Beziehungen können über Organisation_Beziehung eingegeben werden.';

ALTER TABLE `organisation` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Ausserparlamentarische Kommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation_log` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Ausserparlamentarische Kommission' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `interessenbindung` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `interessenbindung_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `mandat` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `mandat_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `partei_log` CHANGE `farbcode` `farbcode` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23' AFTER `position`;

ALTER TABLE `organisation_jahr`
CHANGE `umsatz` `umsatz` BIGINT NULL DEFAULT NULL COMMENT 'Umsatz der Organisation in Franken',
CHANGE `gewinn` `gewinn` BIGINT NULL DEFAULT NULL COMMENT 'Gewinn der Organisation in Franken',
CHANGE `kapital` `kapital` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken',
CHANGE `mitarbeiter_weltweit` `mitarbeiter_weltweit` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Mitarbeiter weltweit',
CHANGE `mitarbeiter_schweiz` `mitarbeiter_schweiz` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Mitarbeiter in der Schweiz',
CHANGE `jahr` `jahr` SMALLINT( 6 ) UNSIGNED NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen';

ALTER TABLE `organisation_jahr_log`
CHANGE `umsatz` `umsatz` BIGINT NULL DEFAULT NULL COMMENT 'Umsatz der Organisation in Franken',
CHANGE `gewinn` `gewinn` BIGINT NULL DEFAULT NULL COMMENT 'Gewinn der Organisation in Franken',
CHANGE `kapital` `kapital` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Marktkapitalisierung, Stiftungskapital, … in Franken',
CHANGE `mitarbeiter_weltweit` `mitarbeiter_weltweit` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Mitarbeiter weltweit',
CHANGE `mitarbeiter_schweiz` `mitarbeiter_schweiz` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Mitarbeiter in der Schweiz',
CHANGE `jahr` `jahr` SMALLINT( 6 ) UNSIGNED NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen';

ALTER TABLE `kanton`
CHANGE `flaeche_km2` `flaeche_km2` INT( 11 ) UNSIGNED NOT NULL COMMENT 'Fläche in km2',
CHANGE `beitrittsjahr` `beitrittsjahr` SMALLINT( 6 ) UNSIGNED NOT NULL COMMENT 'Beitrittsjahr zur Schweiz';

ALTER TABLE `kanton_jahr`
CHANGE `jahr` `jahr` SMALLINT( 6 ) UNSIGNED NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
CHANGE `anzahl_nationalraete` `anzahl_nationalraete` TINYINT( 4 ) UNSIGNED NOT NULL COMMENT 'Anzahl Nationalräte des Kantons',
CHANGE `einwohner` `einwohner` INT( 11 ) UNSIGNED NOT NULL COMMENT 'Wohnbevölkerung des Kantons',
CHANGE `bevoelkerungsdichte` `bevoelkerungsdichte` SMALLINT( 6 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Bevölkerungsdichte [Einwohner/km2]',
CHANGE `anzahl_gemeinden` `anzahl_gemeinden` SMALLINT( 6 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Gemeinden',
CHANGE `steuereinnahmen` `steuereinnahmen` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Stuereinnahmen in Franken';

ALTER TABLE `kanton_log`
CHANGE `flaeche_km2` `flaeche_km2` INT( 11 ) UNSIGNED NOT NULL COMMENT 'Fläche in km2',
CHANGE `beitrittsjahr` `beitrittsjahr` SMALLINT( 6 ) UNSIGNED NOT NULL COMMENT 'Beitrittsjahr zur Schweiz';

ALTER TABLE `kanton_jahr_log`
CHANGE `jahr` `jahr` SMALLINT( 6 ) UNSIGNED NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
CHANGE `anzahl_nationalraete` `anzahl_nationalraete` TINYINT( 4 ) UNSIGNED NOT NULL COMMENT 'Anzahl Nationalräte des Kantons',
CHANGE `einwohner` `einwohner` INT( 11 ) UNSIGNED NOT NULL COMMENT 'Wohnbevölkerung des Kantons',
CHANGE `bevoelkerungsdichte` `bevoelkerungsdichte` SMALLINT( 6 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Bevölkerungsdichte [Einwohner/km2]',
CHANGE `anzahl_gemeinden` `anzahl_gemeinden` SMALLINT( 6 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Gemeinden',
CHANGE `steuereinnahmen` `steuereinnahmen` INT( 11 ) UNSIGNED NULL DEFAULT NULL COMMENT 'Stuereinnahmen in Franken';


-- 01.05.2014

ALTER TABLE `parlamentarier`
  ADD `ratswechsel` date DEFAULT NULL COMMENT 'Datum in welchem der Parlamentarier den Rat wechselte, in der Regel vom National- in den Ständerat. Leer (NULL) = kein Ratswechsel hat stattgefunden' AFTER `im_rat_bis`;

ALTER TABLE `parlamentarier_log`
  ADD `ratswechsel` date DEFAULT NULL COMMENT 'Datum in welchem der Parlamentarier den Rat wechselte, in der Regel vom National- in den Ständerat. Leer (NULL) = kein Ratswechsel hat stattgefunden' AFTER `im_rat_bis`;

-- 06.07.2014

ALTER TABLE `user`
  ADD `last_login` TIMESTAMP NULL DEFAULT NULL COMMENT 'Datum des letzten Login' AFTER `vorname` ;

ALTER TABLE `user`
  ADD `notizen` text COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.' AFTER `last_login`,
  ADD `created_visa` varchar(10) NULL COMMENT 'Datensatz erstellt von' AFTER `notizen` ,
  -- ADD `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am' AFTER `created_visa`, -- PHP 5.6
  ADD `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am' AFTER `created_visa`,
  ADD `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von' AFTER `created_date`,
  ADD `updated_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am' AFTER `updated_visa`;

ALTER TABLE `user`
  ADD `email` varchar(100) DEFAULT NULL COMMENT 'E-Mail-Adresse des Benutzers' AFTER `vorname` ;

ALTER TABLE `organisation` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Ausserparlamentarische Kommission', 'Einfache Gesellschaft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation_log` CHANGE `rechtsform` `rechtsform` ENUM( 'AG', 'GmbH', 'Stiftung', 'Verein', 'Informelle Gruppe', 'Parlamentarische Gruppe', 'Oeffentlich-rechtlich', 'Einzelunternehmen', 'KG', 'Genossenschaft', 'Staatlich', 'Ausserparlamentarische Kommission', 'Einfache Gesellschaft' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Rechtsform der Organisation';

ALTER TABLE `organisation`
  ADD `adresse_strasse` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Adresse der Organisation' AFTER `beschreibung`,
  ADD `adresse_zusatz` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach' AFTER `adresse_strasse`,
  ADD `adresse_plz` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Postleitzahl der Organisation' AFTER `adresse_zusatz`;

ALTER TABLE `organisation_log`
  ADD `adresse_strasse` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Adresse der Organisation' AFTER `beschreibung`,
  ADD `adresse_zusatz` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach' AFTER `adresse_strasse`,
  ADD `adresse_plz` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Postleitzahl der Organisation' AFTER `adresse_zusatz`;

ALTER TABLE `interessenbindung` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `interessenbindung_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `mandat` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `mandat_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'patronatskomitee', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `organisation_beziehung` CHANGE `art` `art` ENUM( 'arbeitet fuer', 'mitglied von', 'tochtergesellschaft von', 'partner von' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation';

ALTER TABLE `organisation_beziehung_log` CHANGE `art` `art` ENUM( 'arbeitet fuer', 'mitglied von', 'tochtergesellschaft von', 'partner von' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation';

-- Part 2

UPDATE `interessenbindung` SET art='beirat' WHERE art='patronatskomitee';

UPDATE `interessenbindung_log` SET art='beirat' WHERE art='patronatskomitee';

UPDATE `mandat` SET art='beirat' WHERE art='patronatskomitee';

UPDATE `mandat_log` SET art='beirat' WHERE art='patronatskomitee';

ALTER TABLE `interessenbindung` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `interessenbindung_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `mandat` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `mandat_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `parlamentarier` CHANGE `zivilstand` `zivilstand` ENUM( 'ledig', 'verheirated', 'geschieden', 'eingetragene partnerschaft', 'verheiratet' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';

ALTER TABLE `parlamentarier_log` CHANGE `zivilstand` `zivilstand` ENUM( 'ledig', 'verheirated', 'geschieden', 'eingetragene partnerschaft', 'verheiratet' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';

UPDATE `parlamentarier` SET `zivilstand`='verheiratet' WHERE `zivilstand`= 'verheirated';

UPDATE `parlamentarier_log` SET `zivilstand`='verheiratet' WHERE `zivilstand`= 'verheirated';

ALTER TABLE `parlamentarier` CHANGE `zivilstand` `zivilstand` ENUM( 'ledig', 'verheiratet', 'geschieden', 'eingetragene partnerschaft') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';

ALTER TABLE `parlamentarier_log` CHANGE `zivilstand` `zivilstand` ENUM( 'ledig', 'verheiratet', 'geschieden', 'eingetragene partnerschaft') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';

UPDATE `user` SET `created_visa`='roland', `created_date`=STR_TO_DATE('08.11.2013','%d.%m.%Y'), `updated_visa`='roland', `updated_date`=NOW() WHERE `id` IN (1, 2, 3, 4, 5, 6, 7);
UPDATE `user` SET `created_visa`='roland', `created_date`=NOW(), `updated_visa`='roland', `updated_date`=NOW() WHERE `id` > 7;

ALTER TABLE `branche`
  ADD `symbol_abs` varchar(255) DEFAULT NULL COMMENT 'Symbolbild (Icon) der Branche, absoluter Pfad' AFTER `farbcode`,
  ADD `symbol_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden' AFTER `symbol_abs`,
  ADD `symbol_klein_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden' AFTER `symbol_rel`,
  ADD `symbol_dateiname_wo_ext` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname ohne Erweiterung' AFTER symbol_klein_rel,
  ADD `symbol_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Dateierweiterung des Symbolbildes' AFTER symbol_dateiname_wo_ext,
  ADD `symbol_dateiname` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname mit Erweiterung' AFTER symbol_dateierweiterung,
  ADD `symbol_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Symbolbildes' AFTER symbol_dateiname;

ALTER TABLE `branche_log`
  ADD `symbol_abs` varchar(255) DEFAULT NULL COMMENT 'Symbolbild (Icon) der Branche, absoluter Pfad' AFTER `farbcode`,
  ADD `symbol_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden' AFTER `symbol_abs`,
  ADD `symbol_klein_rel` varchar(255) DEFAULT NULL COMMENT 'Kleines Symbolbild (Icon) der Branche, relativer Pfad, kann mit $rel_files_url/ zu URL ergänzt werden' AFTER `symbol_rel`,
  ADD `symbol_dateiname_wo_ext` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname ohne Erweiterung' AFTER symbol_klein_rel,
  ADD `symbol_dateierweiterung` varchar(15) DEFAULT NULL COMMENT 'Dateierweiterung des Symbolbildes' AFTER symbol_dateiname_wo_ext,
  ADD `symbol_dateiname` varchar(255) DEFAULT NULL COMMENT 'Symbolbilddateiname mit Erweiterung' AFTER symbol_dateierweiterung,
  ADD `symbol_mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME Type des Symbolbildes' AFTER symbol_dateiname;

-- 10.07.2014

ALTER TABLE `user`
  ADD `last_access` TIMESTAMP NULL DEFAULT NULL COMMENT 'Datum des letzten Zugriffs' AFTER `last_login` ;

-- 12.07.2014

SET GLOBAL group_concat_max_len=10000;

-- 21.07.2014

ALTER TABLE `interessenbindung` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg' AFTER `beschreibung` ,
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url` ;

ALTER TABLE `interessenbindung_log` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg' AFTER `beschreibung` ,
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url` ;

ALTER TABLE `mandat` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg' AFTER `beschreibung` ,
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url` ;

ALTER TABLE `mandat_log` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg' AFTER `beschreibung` ,
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url` ;

ALTER TABLE `organisation_beziehung` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg' AFTER `art` ,
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url` ;

ALTER TABLE `organisation_beziehung_log` ADD `quelle_url` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg' AFTER `art` ,
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url` ;

ALTER TABLE `user` ADD
  `farbcode` varchar(15) DEFAULT NULL COMMENT 'HTML-Farbcode, z.B. red oder #23FF23' AFTER `last_access`;

ALTER TABLE `interessenbindung` ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `interessenbindung_log` ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `mandat` ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `mandat_log` ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `organisation_beziehung` ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `organisation_beziehung_log` ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `organisation_jahr`
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url`,
ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

ALTER TABLE `organisation_jahr_log`
ADD `quelle_url_gueltig` BOOLEAN NULL DEFAULT NULL COMMENT 'Ist Quell-URL noch gueltig? Funktioniert er noch?' AFTER `quelle_url`,
ADD `quelle` VARCHAR( 80 ) NULL DEFAULT NULL COMMENT 'Quellenangabe, Format: "[Publikation], DD.MM.YYYY", falls vorhanden bitte die URL im Feld "Quelle URL" auch hinzufügen' AFTER `quelle_url_gueltig`;

-- 22.07.2014

ALTER TABLE `kommission`
ADD `anzahl_nationalraete` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Nationalrates' AFTER `sachbereiche`,
ADD `anzahl_staenderaete` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Ständerates' AFTER `anzahl_nationalraete`;

ALTER TABLE `kommission_log`
ADD `anzahl_nationalraete` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Nationalrates' AFTER `sachbereiche`,
ADD `anzahl_staenderaete` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder des Ständerates' AFTER `anzahl_nationalraete`;

-- 10.08.2014

ALTER TABLE `branche`
DROP KEY `kommission_id`,
ADD KEY `idx_kommission_freigabe` (`kommission_id`, `freigabe_datum`);

ALTER TABLE `interessenbindung`
-- MV is used for web
--	ADD KEY `idx_parlam_freigabe` (`parlamentarier_id`, `freigabe_datum`, `bis`, `organisation_id`),
--	ADD KEY `idx_parlam` (`parlamentarier_id`, `bis`, `organisation_id`),
--	ADD KEY `idx_org_freigabe` (`organisation_id`, `freigabe_datum`, `bis`, `parlamentarier_id`),
--	ADD KEY `idx_org` (`organisation_id`, `bis`, `parlamentarier_id`)
DROP KEY `idx_parlam`,
DROP KEY `idx_lobbyorg`,
ADD KEY `parlamentarier_id` (`parlamentarier_id`, `organisation_id`),
ADD KEY `organisation_id` (`organisation_id`, `parlamentarier_id`)
-- most probably not used
-- ADD KEY `idx_bis` (`bis`)
;

ALTER TABLE `interessengruppe`
DROP KEY `idx_lobbytyp`,
ADD KEY `idx_branche_freigabe` (`branche_id`, `freigabe_datum`);

ALTER TABLE `in_kommission`
DROP KEY `parlamentarier_id`,
DROP KEY `kommissions_id`,
ADD KEY `idx_parlam_freigabe` (`parlamentarier_id`, `freigabe_datum`, `bis`, `kommission_id`),
ADD KEY `idx_parlam` (`parlamentarier_id`, `bis`, `kommission_id`),
ADD KEY `idx_kommission_freigabe` (`kommission_id`, `freigabe_datum`, `bis`, `parlamentarier_id`),
ADD KEY `idx_kommission` (`kommission_id`, `bis`, `parlamentarier_id`)
-- most probably not used
-- ADD KEY `idx_bis` (`bis`)
;

ALTER TABLE `kommission`
DROP KEY `zugehoerige_kommission`,
ADD KEY `zugehoerige_kommission` (`mutter_kommission_id`, `freigabe_datum`);

ALTER TABLE `mandat`
-- MV is used for web
--	ADD KEY `idx_zutrittsberechtigung_freigabe` (`zutrittsberechtigung_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_zutrittsberechtigung` (`zutrittsberechtigung_id`, `bis`),
--	ADD KEY `idx_org_freigabe` (`organisation_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_org` (`organisation_id`, `bis`)
DROP KEY `organisations_id`,
DROP KEY `zutrittsberechtigung_id`,
ADD KEY `organisations_id` (`organisation_id`, `zutrittsberechtigung_id`),
ADD KEY `zutrittsberechtigung_id` (`zutrittsberechtigung_id`, `organisation_id`)
-- most probably not used
-- ADD KEY `idx_bis` (`bis`)
;

-- MV is used for web
--	ALTER TABLE `organisation`
--	ADD KEY `idx_branche_freigabe` (`branche_id`, `freigabe_datum`),
--	ADD KEY `idx_branche` (`branche_id`),
--	ADD KEY `idx_interessengruppe_freigabe` (`interessengruppe_id`, `freigabe_datum`),
--	ADD KEY `idx_interessengruppe` (`interessengruppe_id`),
--	ADD KEY `idx_interessengruppe2_freigabe` (`interessengruppe2_id`, `freigabe_datum`),
--	ADD KEY `idx_interessengruppe2` (`interessengruppe2_id`),
--	ADD KEY `idx_interessengruppe3_freigabe` (`interessengruppe3_id`, `freigabe_datum`),
--	ADD KEY `idx_interessengruppe3` (`interessengruppe3_id`);

ALTER TABLE `organisation_beziehung`
ADD KEY `idx_org_freigabe` (`organisation_id`, `freigabe_datum`, `bis`, `ziel_organisation_id`),
ADD KEY `idx_org` (`organisation_id`, `bis`, `ziel_organisation_id`),
ADD KEY `idx_ziel_freigabe` (`ziel_organisation_id`, `freigabe_datum`, `bis`, `organisation_id`),
ADD KEY `idx_ziel` (`ziel_organisation_id`, `bis`, `organisation_id`),
DROP KEY `organisation_id`,
DROP KEY `ziel_organisation_id`,
ADD KEY `organisation_id` (`organisation_id`, `ziel_organisation_id`),
ADD KEY `ziel_organisation_id` (`ziel_organisation_id`, `organisation_id`)
;

--	ALTER TABLE `organisation_jahr`
--	ADD KEY `idx_updated` (`updated_date`, `id`);

ALTER TABLE `parlamentarier`
-- MV is used for web
--	ADD KEY `idx_rat_id_freigabe` (`rat_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_rat_id` (`rat_id`, `im_rat_bis`),
--	ADD KEY `idx_kanton_id_freigabe` (`kanton_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_kanton_id` (`kanton_id`, `im_rat_bis`),
--	ADD KEY `idx_partei_id_freigabe` (`partei_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_partei_id` (`partei_id`, `im_rat_bis`),
--	ADD KEY `idx_beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_beruf_interessengruppe_id` (`beruf_interessengruppe_id`, `im_rat_bis`),
--	ADD KEY `idx_beruf_branche_id_freigabe` (`beruf_interessengruppe_branche_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_beruf_branche_id` (`beruf_interessengruppe_branche_id`, `im_rat_bis`),
--	ADD KEY `idx_militaerischer_grad_freigabe` (`militaerischer_grad_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_militaerischer_grad` (`militaerischer_grad_id`, `im_rat_bis`),
--	ADD KEY `idx_fraktion_id_freigabe` (`fraktion_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_fraktion_id` (`fraktion_id`, `im_rat_bis`),
DROP KEY `parlamentarier_nachname_vorname_unique`,
ADD UNIQUE KEY `parlamentarier_nachname_vorname_unique` (`nachname`,`vorname`,`zweiter_vorname`)
-- COMMENT 'Fachlicher unique constraint'
-- most probably not used
-- ADD KEY `idx_bis` (`im_rat_bis`)
;

--	ALTER TABLE `partei`
--	ADD KEY `idx_updated` (`updated_date`, `id`);

--	ALTER TABLE `fraktion`
--	ADD KEY `idx_updated` (`updated_date`, `id`);
--
--	ALTER TABLE `kanton_jahr`
--	ADD KEY `idx_updated` (`updated_date`, `id`);

-- ALTER TABLE `zutrittsberechtigung`
-- MV is used for web
--	ADD KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_parlam_bis` (`parlamentarier_id`, `bis`),
--	ADD KEY `idx_parlam_anzeige` (`parlamentarier_id`),
--	ADD KEY `idx_partei_id_freigabe` (`partei_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_partei_id` (`partei_id`, `bis`),
--	ADD KEY `idx_beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_beruf_interessengruppe_id` (`beruf_interessengruppe_id`, `bis`)
-- most probably not used
-- ADD KEY `idx_bis` (`bis`)
-- ;

--	ALTER TABLE `parlamentarier_anhang`
--	ADD KEY `idx_updated` (`updated_date`, `id`);
--
--	ALTER TABLE `organisation_anhang`
--	ADD KEY `idx_updated` (`updated_date`, `id`);
--
--	ALTER TABLE `zutrittsberechtigung_anhang`
--	ADD KEY `idx_updated` (`updated_date`, `id`);
--
--	ALTER TABLE `settings`
--	ADD KEY `idx_updated` (`updated_date`, `id`);
--
--	ALTER TABLE `settings_category`
--	ADD KEY `idx_updated` (`updated_date`, `id`);

DROP VIEW `v_organisation_medium`;
DROP TABLE IF EXISTS `mv_organisation_medium`;
DROP TABLE IF EXISTS `mv_organisation_medium_myisam`;

DROP VIEW `v_parlamentarier_medium`;
DROP TABLE IF EXISTS `mv_parlamentarier_medium`;
DROP TABLE IF EXISTS `mv_parlamentarier_medium_myisam`;
DROP TABLE IF EXISTS `mv_organisation_lobbyeinfluss`;
DROP TABLE IF EXISTS `mv_zutrittsberechtigung_lobbyfaktor`;
DROP TABLE IF EXISTS `mv_parlamentarier_lobbyfaktor`;
DROP TABLE IF EXISTS `mv_parlamentarier_lobbyfaktor_max`;
DROP TABLE IF EXISTS `mv_zutrittsberechtigung_lobbyfaktor_max`;

DROP TABLE IF EXISTS `mv_parlamentarier_myisam`;
DROP TABLE IF EXISTS `mv_organisation_myisam`;
DROP TABLE IF EXISTS `mv_zutrittsberechtigung_myisam`;

-- These indexes seem not to be useful
--	ALTER TABLE `parlamentarier`
--	ADD KEY `idx_bis` (`im_rat_bis`, `nachname`);
--
--	ALTER TABLE `zutrittsberechtigung`
--	ADD KEY `idx_bis` (`bis`, `nachname`);
--
--	ALTER TABLE `interessenbindung`
--	ADD KEY `idx_bis` (`bis`);
--
--	ALTER TABLE `mandat`
--	ADD KEY `idx_bis` (`bis`);
--
--	ALTER TABLE `in_kommission`
--	ADD KEY `idx_bis` (`bis`);
--
--	ALTER TABLE `organisation_beziehung`
--	ADD KEY `idx_bis` (`bis`);

ALTER TABLE `user`
  CHANGE `password` `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  ADD UNIQUE KEY `idx_name_unique` (`name`)
  -- COMMENT 'Fachlicher unique constraint: Name muss einzigartig sein'
  ;

ALTER TABLE `user_permission`
DROP KEY `user_id`,
ADD KEY `user_id` (`user_id`, `page_name`, `permission_name`);

DROP VIEW `v_organisation_lobbyeinfluss`;
DROP VIEW `v_zutrittsberechtigung_lobbyfaktor`;
DROP VIEW `v_parlamentarier_lobbyfaktor`;
DROP VIEW `v_parlamentarier_lobbyfaktor_max`;
DROP VIEW `v_zutrittsberechtigung_lobbyfaktor_max`;

-- 13.09.2014

ALTER TABLE `organisation` DROP FOREIGN KEY `fk_lo_lt` ;

ALTER TABLE `parlamentarier` DROP `ALT_kommission` ;

ALTER TABLE `parlamentarier_log` DROP `ALT_kommission` ;

ALTER TABLE `organisation` DROP `ALT_parlam_verbindung` ;

ALTER TABLE `organisation_log` DROP `ALT_parlam_verbindung` ;

ALTER TABLE `zutrittsberechtigung` DROP `ALT_lobbyorganisation_id` ;

ALTER TABLE `zutrittsberechtigung_log` DROP `ALT_lobbyorganisation_id` ;

ALTER TABLE `parlamentarier`
ADD `telephon_1` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz' AFTER `adresse_ort` ,
ADD `telephon_2` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon' AFTER `telephon_1` ;

ALTER TABLE `parlamentarier_log`
ADD `telephon_1` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz' AFTER `adresse_ort` ,
ADD `telephon_2` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon' AFTER `telephon_1` ;

ALTER TABLE `interessenbindung` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell', 'gesellschafter' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `interessenbindung_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell', 'gesellschafter' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Interessenbindung';

ALTER TABLE `mandat` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell', 'gesellschafter' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `mandat_log` CHANGE `art` `art` ENUM( 'mitglied', 'geschaeftsfuehrend', 'vorstand', 'taetig', 'beirat', 'finanziell', 'gesellschafter' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Art der Funktion des Mandatsträgers innerhalb der Organisation';

ALTER TABLE `in_kommission` CHANGE `funktion` `funktion` ENUM( 'praesident', 'vizepraesident', 'mitglied', 'co-praesident' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission';

ALTER TABLE `in_kommission_log` CHANGE `funktion` `funktion` ENUM( 'praesident', 'vizepraesident', 'mitglied', 'co-praesident' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'mitglied' COMMENT 'Funktion des Parlamentariers in der Kommission';

ALTER TABLE `zutrittsberechtigung`
ADD `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])' AFTER funktion;

ALTER TABLE `zutrittsberechtigung_log`
ADD `parlamentarier_kommissionen` varchar(75) DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission Trigger])' AFTER funktion;

ALTER TABLE `zutrittsberechtigung`
ADD `telephon_1` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz' AFTER `facebook_name` ,
ADD `telephon_2` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon' AFTER `telephon_1` ;

ALTER TABLE `zutrittsberechtigung_log`
ADD `telephon_1` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 1, z.B. Festnetz' AFTER `facebook_name` ,
ADD `telephon_2` VARCHAR( 25 ) NULL DEFAULT NULL COMMENT 'Telephonnummer 2, z.B. Mobiltelephon' AFTER `telephon_1` ;

UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
	p.updated_visa='roland',
	p.updated_date=NOW();

-- 15.11.2014

ALTER TABLE `zutrittsberechtigung`
  ADD `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Zutrittsberechtigten' AFTER `geschlecht`;

ALTER TABLE `zutrittsberechtigung_log`
  ADD `arbeitssprache` enum('de','fr','it') DEFAULT NULL COMMENT 'Arbeitssprache des Zutrittsberechtigten' AFTER `geschlecht`;

-- 16.11.2014

ALTER TABLE `organisation` ADD `uid` VARCHAR( 15 ) NULL DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999' AFTER `name_it` ;

ALTER TABLE `organisation_log` ADD `uid` VARCHAR( 15 ) NULL DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999' AFTER `name_it` ;

ALTER TABLE `interessengruppe` ADD `alias_namen` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `beschreibung` ;

ALTER TABLE `interessengruppe_log` ADD `alias_namen` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `beschreibung` ;

ALTER TABLE `organisation_beziehung` CHANGE `art` `art` ENUM( 'arbeitet fuer', 'mitglied von', 'tochtergesellschaft von', 'partner von', 'beteiligt an' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation';

ALTER TABLE `organisation_beziehung_log` CHANGE `art` `art` ENUM( 'arbeitet fuer', 'mitglied von', 'tochtergesellschaft von', 'partner von', 'beteiligt an' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Beschreibt die Beziehung einer Organisation zu einer Zielorgansation';

-- 17.11.2014 DEV

ALTER TABLE `branche`
  DROP `name_fr`,
  DROP `beschreibung_fr`,
  DROP `angaben_fr`;

ALTER TABLE `fraktion`
  DROP `name_fr`,
  DROP `beschreibung_fr`;

ALTER TABLE `interessengruppe`
  DROP `name_fr`,
  DROP `beschreibung_fr`,
  DROP `alias_namen_fr`;

ALTER TABLE `interessenraum`
  DROP `name_fr`,
  DROP `beschreibung_fr`;

ALTER TABLE `kommission`
  DROP `abkuerzung_fr`,
  DROP `name_fr`,
  DROP `beschreibung_fr`,
  DROP `sachbereiche_fr`;

ALTER TABLE `mil_grad`
  DROP `name_fr`,
  DROP `abkuerzung_fr`;

ALTER TABLE `partei`
  DROP `abkuerzung_fr`,
  DROP `name_fr`,
  DROP `homepage_fr`,
  DROP `email_fr`,
  DROP `twitter_name_fr`,
  DROP `beschreibung_fr`;


ALTER TABLE `branche_log`
  DROP `name_fr`,
  DROP `beschreibung_fr`,
  DROP `angaben_fr`;

ALTER TABLE `fraktion_log`
  DROP `name_fr`,
  DROP `beschreibung_fr`;

ALTER TABLE `interessengruppe_log`
  DROP `name_fr`,
  DROP `beschreibung_fr`,
  DROP `alias_namen_fr`;

ALTER TABLE `kommission_log`
  DROP `abkuerzung_fr`,
  DROP `name_fr`,
  DROP `beschreibung_fr`,
  DROP `sachbereiche_fr`;

ALTER TABLE `mil_grad_log`
  DROP `name_fr`,
  DROP `abkuerzung_fr`;

ALTER TABLE `partei_log`
  DROP `abkuerzung_fr`,
  DROP `name_fr`,
  DROP `homepage_fr`,
  DROP `email_fr`,
  DROP `twitter_name_fr`,
  DROP `beschreibung_fr`;

-- 17.11.2014

ALTER TABLE `branche`
  ADD `name_fr` varchar(100) DEFAULT NULL COMMENT 'Französischer Name der Branche, z.B. Gesundheit, Energie' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Branche' AFTER `beschreibung`,
  ADD `angaben_fr` text DEFAULT NULL COMMENT 'Angaben zur Branche auf Französisch' AFTER `angaben`;

ALTER TABLE `fraktion`
  ADD `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Fraktion' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Fraktion' AFTER `beschreibung`;

ALTER TABLE `interessengruppe`
  ADD `name_fr` varchar(150) DEFAULT NULL  COMMENT 'Französische Bezeichnung der Interessengruppe' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL  COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe auf französisch' AFTER `beschreibung`,
  ADD `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `alias_namen`;

ALTER TABLE `interessenraum`
  ADD `name_fr` varchar(50) DEFAULT NULL  COMMENT 'Französischer Name des Interessenbereiches' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung des Interessenraumes' AFTER `beschreibung`;

ALTER TABLE `kommission`
  ADD `abkuerzung_fr` varchar(15) DEFAULT NULL  COMMENT 'Französisches Kürzel der Kommission' AFTER `abkuerzung`,
  ADD `name_fr` varchar(100) DEFAULT NULL  COMMENT 'Ausgeschriebener französischer Name der Kommission' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Kommission' AFTER `beschreibung`,
  ADD `sachbereiche_fr` text DEFAULT NULL COMMENT 'Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".' AFTER `sachbereiche`;

ALTER TABLE `mil_grad`
  ADD `name_fr` varchar(30) DEFAULT NULL COMMENT 'Französischer Name des militärischen Grades' AFTER `name`,
  ADD `abkuerzung_fr` varchar(10) DEFAULT NULL  COMMENT 'Französische Abkürzung des militärischen Grades' AFTER `abkuerzung`;

ALTER TABLE `partei`
  ADD `abkuerzung_fr` varchar(20) DEFAULT NULL  COMMENT 'Französische Parteiabkürzung' AFTER `abkuerzung`,
  ADD `name_fr` varchar(100) DEFAULT NULL  COMMENT 'Ausgeschriebener französischer Name der Partei' AFTER `name`,
  ADD `homepage_fr` varchar(255) DEFAULT NULL COMMENT 'Französische Homepage der Partei' AFTER `homepage`,
  ADD `email_fr` varchar(100) DEFAULT NULL COMMENT 'Französische Kontakt E-Mail-Adresse der Partei' AFTER `email`,
  ADD `twitter_name_fr` varchar(50) DEFAULT NULL COMMENT 'Französischer Twittername' AFTER `twitter_name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Partei' AFTER `beschreibung`;


ALTER TABLE `branche_log`
  ADD `name_fr` varchar(100) DEFAULT NULL COMMENT 'Französischer Name der Branche, z.B. Gesundheit, Energie' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Branche' AFTER `beschreibung`,
  ADD `angaben_fr` text DEFAULT NULL COMMENT 'Angaben zur Branche auf Französisch' AFTER `angaben`;

ALTER TABLE `fraktion_log`
  ADD `name_fr` varchar(100) DEFAULT NULL COMMENT 'Ausgeschriebener französischer Name der Fraktion' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Fraktion' AFTER `beschreibung`;

ALTER TABLE `interessengruppe_log`
  ADD `name_fr` varchar(150) DEFAULT NULL  COMMENT 'Französische Bezeichnung der Interessengruppe' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL  COMMENT 'Eingrenzung und Beschreibung zur Interessengruppe auf französisch' AFTER `beschreibung`,
  ADD `alias_namen_fr` varchar(255) DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternativen französischen Namen für die Lobbygruppe; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `alias_namen`;

ALTER TABLE `kommission_log`
  ADD `abkuerzung_fr` varchar(15) DEFAULT NULL  COMMENT 'Französisches Kürzel der Kommission' AFTER `abkuerzung`,
  ADD `name_fr` varchar(100) DEFAULT NULL  COMMENT 'Ausgeschriebener französischer Name der Kommission' AFTER `name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Kommission' AFTER `beschreibung`,
  ADD `sachbereiche_fr` text DEFAULT NULL COMMENT 'Liste der Sachbereiche der Kommission auf französisch, abgetrennt durch ";".' AFTER `sachbereiche`;

ALTER TABLE `mil_grad_log`
  ADD `name_fr` varchar(30) DEFAULT NULL COMMENT 'Französischer Name des militärischen Grades' AFTER `name`,
  ADD `abkuerzung_fr` varchar(10) DEFAULT NULL  COMMENT 'Französische Abkürzung des militärischen Grades' AFTER `abkuerzung`;

ALTER TABLE `partei_log`
  ADD `abkuerzung_fr` varchar(20) DEFAULT NULL  COMMENT 'Französische Parteiabkürzung' AFTER `abkuerzung`,
  ADD `name_fr` varchar(100) DEFAULT NULL  COMMENT 'Ausgeschriebener französischer Name der Partei' AFTER `name`,
  ADD `homepage_fr` varchar(255) DEFAULT NULL COMMENT 'Französische Homepage der Partei' AFTER `homepage`,
  ADD `email_fr` varchar(100) DEFAULT NULL COMMENT 'Französische Kontakt E-Mail-Adresse der Partei' AFTER `email`,
  ADD `twitter_name_fr` varchar(50) DEFAULT NULL COMMENT 'Französischer Twittername' AFTER `twitter_name`,
  ADD `beschreibung_fr` text DEFAULT NULL COMMENT 'Französische Beschreibung der Partei' AFTER `beschreibung`;

-- 26.11.2014
-- Fix index for mv_search_table: it must be
ALTER TABLE `mv_search_table`
ADD KEY `idx_search_str_fix_long` (freigabe_datum, bis, table_weight, weight, `search_keywords`),
ADD KEY `idx_search_str_fix_medium` (freigabe_datum, table_weight, weight, `search_keywords`),
ADD KEY `idx_search_str_fix_short` (table_weight, weight, `search_keywords`);

-- 22.11.2014

ALTER TABLE `rat`
  ADD `abkuerzung_fr` VARCHAR(10) NOT NULL COMMENT 'Französische Abkürzung' AFTER `abkuerzung`,
  ADD `mitglied_bezeichnung_maennlich_de` VARCHAR(50) NOT NULL COMMENT 'Deutsche Bezeichnung der Männer' AFTER `homepage_en`,
  ADD `mitglied_bezeichnung_weiblich_de` VARCHAR(50) NOT NULL COMMENT 'Deutsche Bezeichung der Frauen' AFTER `mitglied_bezeichnung_maennlich_de`,
  ADD `mitglied_bezeichnung_maennlich_fr` VARCHAR(50) NOT NULL COMMENT 'Französische Bezeichnung der Männer' AFTER `mitglied_bezeichnung_weiblich_de`,
  ADD `mitglied_bezeichnung_weiblich_fr` VARCHAR(50) NOT NULL COMMENT 'Französische Bezeichung der Frauen' AFTER `mitglied_bezeichnung_maennlich_fr`;
ALTER TABLE `rat_log`
  ADD `abkuerzung_fr` VARCHAR(10) NOT NULL COMMENT 'Französische Abkürzung' AFTER `abkuerzung`,
  ADD `mitglied_bezeichnung_maennlich_de` VARCHAR(50) NOT NULL COMMENT 'Deutsche Bezeichnung der Männer' AFTER `homepage_en`,
  ADD `mitglied_bezeichnung_weiblich_de` VARCHAR(50) NOT NULL COMMENT 'Deutsche Bezeichung der Frauen' AFTER `mitglied_bezeichnung_maennlich_de`,
  ADD `mitglied_bezeichnung_maennlich_fr` VARCHAR(50) NOT NULL COMMENT 'Französische Bezeichnung der Männer' AFTER `mitglied_bezeichnung_weiblich_de`,
  ADD `mitglied_bezeichnung_weiblich_fr` VARCHAR(50) NOT NULL COMMENT 'Französische Bezeichung der Frauen' AFTER `mitglied_bezeichnung_maennlich_fr`;

UPDATE `rat` SET `abkuerzung_fr` = 'CN', mitglied_bezeichnung_maennlich_de='Nationalrat', mitglied_bezeichnung_weiblich_de='Nationalrätin', mitglied_bezeichnung_maennlich_fr='Le Conseiller national', mitglied_bezeichnung_weiblich_fr='La Conseillère nationale' WHERE `rat`.`id` = 1;
UPDATE `rat` SET `abkuerzung_fr` = 'CE', mitglied_bezeichnung_maennlich_de='Ständerat', mitglied_bezeichnung_weiblich_de='Ständerätin', mitglied_bezeichnung_maennlich_fr='Le Conseiller aux Etats', mitglied_bezeichnung_weiblich_fr='La Conseillère aux Etats' WHERE `rat`.`id` = 2;
UPDATE `rat` SET `abkuerzung_fr` = 'CF', mitglied_bezeichnung_maennlich_de='Bundesrat', mitglied_bezeichnung_weiblich_de='Bundesrätin', mitglied_bezeichnung_maennlich_fr='Le Conseiller federal', mitglied_bezeichnung_weiblich_fr='La Conseillère federal' WHERE `rat`.`id` = 3;

ALTER TABLE `organisation` ADD `beschreibung_fr` TEXT NULL DEFAULT NULL COMMENT 'Französische Beschreibung' AFTER `beschreibung`;

ALTER TABLE `organisation_log` ADD `beschreibung_fr` TEXT NULL DEFAULT NULL COMMENT 'Französische Beschreibung' AFTER `beschreibung`;

UPDATE `parlamentarier` SET twitter_name = substring(twitter_name, 2), updated_visa = 'roland*' WHERE twitter_name like '@%';
UPDATE `zutrittsberechtigung` SET twitter_name = substring(twitter_name, 2), updated_visa = 'roland*' WHERE twitter_name like '@%';
UPDATE `organisation` SET twitter_name = substring(twitter_name, 2), updated_visa = 'roland*' WHERE twitter_name like '@%';

-- stage level

UPDATE `partei` SET twitter_name = substring(twitter_name, 2), updated_visa = 'roland*' WHERE twitter_name like '@%';
UPDATE `partei` SET twitter_name_fr = substring(twitter_name_fr, 2), updated_visa = 'roland*' WHERE twitter_name_fr like '@%';

DROP TABLE IF EXISTS `translation_target`;

DROP TABLE IF EXISTS `translation_source`;
CREATE TABLE IF NOT EXISTS `translation_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `source` text NOT NULL COMMENT 'Eindeutiger Schlüssel',
  `context` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' 'Context der Übersetzung',
  `textgroup` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default' COMMENT 'Gruppe von Übersetzungen, z.B. für ein Modul (see hook_locale())',
  `location` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ort wo der Text vorkommt, DB-Tabelle o. Programmfunktion',
  `field` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Name of the field',
  `version` varchar(20) DEFAULT NULL COMMENT 'Version of Lobbywatch, where the string was last updated (for translation optimization).',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  -- `created_date` timestamp NOT NULL COMMENT 'Erstellt am', -- MySQL 5.5 compatiblity
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  -- `updated_date` timestamp NOT NULL COMMENT 'Abgeändert am', -- MySQL 5.5 compatiblity
  PRIMARY KEY (`id`),
  INDEX `source_key` (`source`(255), `context`, `textgroup`) COMMENT 'Index for key'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';

CREATE TABLE IF NOT EXISTS `translation_target` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `translation_source_id` int(11) NOT NULL COMMENT 'Fremschlüssel auf Übersetzungsquelltext',
  `lang` enum('de','fr') NOT NULL COMMENT 'Sprache des Textes',
  `translation` text NOT NULL COMMENT 'Übersetzter Text; "-", wenn der lange Text genommen wird.',
  `plural_translation_source_id` int(11) DEFAULT NULL,
  `plural` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Plural index number in case of plural strings.',
  `created_visa` varchar(10) NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  -- `created_date` timestamp NOT NULL COMMENT 'Erstellt am', -- MySQL 5.5 compatiblity
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  -- `updated_date` timestamp NOT NULL COMMENT 'Abgeändert am', -- MySQL 5.5 compatiblity
  PRIMARY KEY (`id`),
  KEY `plural_translation_source_id` (`plural_translation_source_id`),
  KEY `translation_source_id` (`translation_source_id`,`lang`),
  CONSTRAINT `plural_translation_source_id` FOREIGN KEY (`plural_translation_source_id`) REFERENCES `translation_source` (`id`),
  CONSTRAINT `translation_source_id` FOREIGN KEY (`translation_source_id`) REFERENCES `translation_source` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Translations for lobbywatch DB';

-- ALTER TABLE `translation_source` ADD `textgroup` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default' COMMENT 'Gruppe von Übersetzungen, z.B. für ein Modul (see hook_locale())' AFTER `context`;
-- ALTER TABLE `translation_source_log` ADD `textgroup` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default' COMMENT 'Gruppe von Übersetzungen, z.B. für ein Modul (see hook_locale())' AFTER `context`;

ALTER TABLE `parlamentarier`
  ADD `beruf_fr` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Beruf des Parlamentariers auf französisch' AFTER `beruf`;

ALTER TABLE `parlamentarier_log`
  ADD `beruf_fr` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Beruf des Parlamentariers auf französisch' AFTER `beruf`;

-- Zwischentabelle Zutrittsberechtigung auf Person
RENAME TABLE `zutrittsberechtigung` TO `person`;
RENAME TABLE `zutrittsberechtigung_log` TO `person_log`;
RENAME TABLE `zutrittsberechtigung_anhang` TO `person_anhang`;
RENAME TABLE `zutrittsberechtigung_anhang_log` TO `person_anhang_log`;

DROP TABLE IF EXISTS `zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `zutrittsberechtigung` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel der Zutrittsberechtigung',
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
  -- `created_date` timestamp NOT NULL COMMENT 'Erstellt am', -- MySQL 5.5 compatiblity
  `updated_visa` varchar(10) DEFAULT NULL COMMENT 'Abgeändert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  -- `updated_date` timestamp NOT NULL COMMENT 'Abgeändert am', -- MySQL 5.5 compatiblity
  PRIMARY KEY (`id`),
  UNIQUE KEY `parlamentarier_person_unique` (`parlamentarier_id`,`person_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `person_id` (`person_id`,`parlamentarier_id`),
  CONSTRAINT `fk_zutrittsberechtigung_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  CONSTRAINT `fk_zutrittsberechtigung_parlamentarier` FOREIGN KEY (`parlamentarier_id`) REFERENCES `parlamentarier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Dauerhafter Badge für einen Gast ("Götti")';

INSERT INTO `zutrittsberechtigung` (`parlamentarier_id`, `person_id`, `funktion`, `von`, `bis`, `notizen`, `eingabe_abgeschlossen_visa`, `eingabe_abgeschlossen_datum`, `kontrolliert_visa`, `kontrolliert_datum`, `autorisiert_visa`, `autorisiert_datum`, `freigabe_visa`, `freigabe_datum`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) SELECT `parlamentarier_id`, `id`, `funktion`, `von`, `bis`, '29.11.2014/roland: Migriert von alter Zutrittsberechtigungstabelle' as `notizen`, `eingabe_abgeschlossen_visa`, `eingabe_abgeschlossen_datum`, `kontrolliert_visa`, `kontrolliert_datum`, `autorisiert_visa`, `autorisiert_datum`, `freigabe_visa`, `freigabe_datum`, `created_visa`, `created_date`, `updated_visa`, `updated_date` FROM `person`;

ALTER TABLE `person`
  COMMENT = 'Lobbyist',
  DROP FOREIGN KEY `fk_zb_parlam`,
  CHANGE `parlamentarier_id` `parlamentarier_id` INT(11) NULL COMMENT 'VERALTET: Fremdschlüssel zu Parlamentarier',
  CHANGE `funktion` `beschreibung_de` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  ADD `beschreibung_fr` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.' AFTER `beschreibung_de`,
  CHANGE `beruf` `beruf` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Beruf der Person',
  ADD `beruf_fr` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Französische Bezeichung des Beruf der Person' AFTER `beruf`,
  ADD `zutrittsberechtigung_von` varchar(75) DEFAULT NULL COMMENT 'Welcher Parlamentarier gab die Zutrittsberechtigung?' AFTER parlamentarier_kommissionen;

ALTER TABLE `person_log`
  COMMENT = 'Lobbyist',
  CHANGE `parlamentarier_id` `parlamentarier_id` INT(11) NULL COMMENT 'VERALTET: Fremdschlüssel zu Parlamentarier',
  CHANGE `funktion` `beschreibung_de` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Beschreibung der Person. Der Text ist öffentlich einsehbar.',
  ADD `beschreibung_fr` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Französische Beschreibung der Person. Der Text ist öffentlich einsehbar.' AFTER `beschreibung_de`,
  CHANGE `beruf` `beruf` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Beruf der Person',
  ADD `beruf_fr` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Französische Bezeichung des Beruf der Person' AFTER `beruf`,
  ADD `zutrittsberechtigung_von` varchar(75) DEFAULT NULL COMMENT 'Welcher Parlamentarier gab die Zutrittsberechtigung?' AFTER parlamentarier_kommissionen,
  DROP FOREIGN KEY `fk_zutrittsberechtigung_log_snapshot_id`,
  ADD CONSTRAINT `fk_person_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

drop trigger if exists `trg_zutrittsberechtigung_log_del_before`;
drop trigger if exists `trg_zutrittsberechtigung_log_del_after`;
  -- SELECT * FROM `zutrittsberechtigung` WHERE person_id IN (282, 436)
-- Peter Schuhmacher
UPDATE `zutrittsberechtigung` SET `bis` = '2014-11-01', `notizen` = '29.11.2014/roland: Migriert von alter Zutrittsberechtigungtabelle\n 30.11.2014/roland: Korrektur von doppeltem Eintrag', `eingabe_abgeschlossen_datum` = NULL, `kontrolliert_datum` = NULL, `freigabe_datum` = NULL WHERE `zutrittsberechtigung`.`person_id` = 282 AND `zutrittsberechtigung`.`parlamentarier_id` = 83;
UPDATE `zutrittsberechtigung` SET `person_id` = '282', `notizen` = '29.11.2014/roland: Migriert von alter Zutrittsberechtigungtabelle\n 30.11.2014/roland: Korrektur von doppeltem Eintrag', `eingabe_abgeschlossen_datum` = NULL, `kontrolliert_datum` = NULL, `freigabe_datum` = NULL WHERE `zutrittsberechtigung`.`person_id` = 436 AND `zutrittsberechtigung`.`parlamentarier_id` = 92;
DELETE FROM `person` WHERE `person`.`id` = 436;
-- Urs Meyer
UPDATE `zutrittsberechtigung` SET `bis` = '2014-09-30', `notizen` = '29.11.2014/roland: Migriert von alter Zutrittsberechtigungtabelle\n 30.11.2014/roland: Korrektur von doppeltem Eintrag', `eingabe_abgeschlossen_datum` = NULL, `kontrolliert_datum` = NULL, `freigabe_datum` = NULL WHERE `zutrittsberechtigung`.`person_id` = 199 AND `zutrittsberechtigung`.`parlamentarier_id` = 170;
UPDATE `zutrittsberechtigung` SET `person_id` = '199', `notizen` = '29.11.2014/roland: Migriert von alter Zutrittsberechtigungtabelle\n 30.11.2014/roland: Korrektur von doppeltem Eintrag', `eingabe_abgeschlossen_datum` = NULL, `kontrolliert_datum` = NULL, `freigabe_datum` = NULL WHERE `zutrittsberechtigung`.`person_id` = 435 AND `zutrittsberechtigung`.`parlamentarier_id` = 81;
DELETE FROM `person` WHERE `person`.`id` = 435;

ALTER TABLE `person`
  DROP INDEX `zutrittsberechtigung_nachname_vorname_unique`,
  ADD UNIQUE `person_nachname_zweiter_name_vorname_unique` (`nachname`, `vorname`, `zweiter_vorname`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE `person`
  DROP `parlamentarier_id`,
  DROP `von`,
  DROP `bis`;

ALTER TABLE `person_log`
  DROP `parlamentarier_id`,
  DROP `von`,
  DROP `bis`;

ALTER TABLE `mandat`
  DROP FOREIGN KEY `fk_zugangsberechtigung_id`,
  CHANGE `zutrittsberechtigung_id` `person_id` INT(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  DROP INDEX `zutrittsberechtigung_id`,
  ADD INDEX `person_id` (`person_id`, `organisation_id`) COMMENT 'person_id',
  DROP INDEX `mandat_zutrittsberechtigung_organisation_art_unique`,
  ADD UNIQUE `mandat_person_organisation_art_unique` (`art`, `person_id`, `organisation_id`, `bis`) COMMENT 'Fachlicher unique constraint',
  ADD CONSTRAINT `fk_mandat_person_id` FOREIGN KEY (`person_id`) REFERENCES `person`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `mandat_log`
  CHANGE `zutrittsberechtigung_id` `person_id` INT(11) NOT NULL COMMENT 'Fremdschlüssel Person';

ALTER TABLE `person_anhang`
  DROP FOREIGN KEY `fk_zutrittsberechtigung_anhang_zutrittsberechtigung_id`,
  CHANGE `zutrittsberechtigung_id` `person_id` INT(11) NOT NULL COMMENT 'Fremdschlüssel Person.',
  ADD CONSTRAINT `fk_person_anhang_person_id` FOREIGN KEY (`person_id`) REFERENCES `person`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `person_anhang_log`
  CHANGE `zutrittsberechtigung_id` `person_id` INT(11) NOT NULL COMMENT 'Fremdschlüssel Person',
  DROP FOREIGN KEY `fk_zutrittsberechtigung_anhang_log_snapshot_id`,
  ADD CONSTRAINT `fk_person_anhang_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

DROP VIEW v_parlamentarier_authorisierungs_email;
DROP VIEW v_zutrittsberechtigung_authorisierungs_email;

-- force update of trigger fields
SET @disable_triggers = 1;
UPDATE person
SET zutrittsberechtigung_von = (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())
WHERE person.id = zutrittsberechtigung.person_id);

UPDATE zutrittsberechtigung
SET parlamentarier_kommissionen = (SELECT parlamentarier_kommissionen FROM v_parlamentarier_simple parlamentarier
WHERE parlamentarier.id = zutrittsberechtigung.parlamentarier_id);
SET @disable_triggers = NULL;

-- 07.12.2014

ALTER TABLE `parlamentarier` ADD `erfasst` ENUM('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.' AFTER `telephon_2`;
ALTER TABLE `parlamentarier_log` ADD `erfasst` ENUM('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.' AFTER `telephon_2`;

ALTER TABLE `person` ADD `erfasst` ENUM('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.' AFTER `telephon_2`;
ALTER TABLE `person_log` ADD `erfasst` ENUM('Ja','Nein') NOT NULL DEFAULT 'Nein' COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden.' AFTER `telephon_2`;

UPDATE parlamentarier SET erfasst='Ja' WHERE id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (1, 3));

SET @disable_triggers = 1;
UPDATE person SET erfasst='Ja' WHERE id IN (SELECT person_id FROM zutrittsberechtigung JOIN parlamentarier ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id JOIN in_kommission ON in_kommission.parlamentarier_id = parlamentarier.id WHERE kommission_id IN (1, 3));
SET @disable_triggers = NULL;

-- 14.12.2014

SET @disable_triggers = 1;
UPDATE zutrittsberechtigung
SET parlamentarier_kommissionen = (SELECT kommissionen_abkuerzung_de FROM v_parlamentarier_medium_raw parlamentarier
WHERE parlamentarier.id = zutrittsberechtigung.parlamentarier_id),
updated_date = zutrittsberechtigung.updated_date;

UPDATE person
SET parlamentarier_kommissionen = (SELECT parlamentarier_kommissionen FROM zutrittsberechtigung
WHERE person.id = zutrittsberechtigung.person_id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW())),
updated_date = person.updated_date;
SET @disable_triggers = NULL;

-- 15.02.1015

-- ALTER TABLE `branche`  DROP `tech_name`;
-- ALTER TABLE `branche_log`  DROP `tech_name`;

ALTER TABLE `branche`  ADD `technischer_name` VARCHAR(30) NOT NULL COMMENT 'Technischer Name für Branche. Keine Sonderzeichen sind erlaubt. Wird z.B. für das finden des Branchensymboles gebraucht.'  AFTER `kommission_id`;
ALTER TABLE `branche_log`  ADD `technischer_name` VARCHAR(30) NOT NULL COMMENT 'Technischer Name für Branche. Keine Sonderzeichen sind erlaubt. Wird z.B. für das finden des Branchensymboles gebraucht.'  AFTER `kommission_id`;

UPDATE branche SET technischer_name='gesundheit' WHERE ID=1;
UPDATE branche SET technischer_name='soziale_sicherheit' WHERE ID=2;
UPDATE branche SET technischer_name='energie' WHERE ID=3;
UPDATE branche SET technischer_name='umwelt' WHERE ID=4;
UPDATE branche SET technischer_name='verkehr' WHERE ID=5;
UPDATE branche SET technischer_name='bildung' WHERE ID=7;
UPDATE branche SET technischer_name='kultur' WHERE ID=8;
UPDATE branche SET technischer_name='wirtschaft' WHERE ID=9;
UPDATE branche SET technischer_name='aussenpolitik_aussenwirtschaft' WHERE ID=13;
UPDATE branche SET technischer_name='staatspolitik_staatswirtschaft' WHERE ID=14;
UPDATE branche SET technischer_name='landwirtschaft' WHERE ID=15;
UPDATE branche SET technischer_name='sicherheit' WHERE ID=16;
UPDATE branche SET technischer_name='sport' WHERE ID=17;
UPDATE branche SET technischer_name='kommunikation' WHERE ID=18;

ALTER TABLE `branche`
ADD   UNIQUE  (`technischer_name`) ;

-- 15.02.1015 II.

ALTER TABLE `kommission`
ADD `parlament_id` INT NULL COMMENT 'Kommissions-ID von ws.parlament.ch'  AFTER `parlament_url`,
ADD `parlament_committee_number` INT NULL COMMENT 'committeeNumber auf ws.parlament.ch'  AFTER `parlament_id`,
ADD `parlament_subcommittee_number` INT NULL DEFAULT NULL COMMENT 'subcommitteeNumber auf ws.parlament.ch'  AFTER `parlament_committee_number`;

ALTER TABLE `kommission_log`
ADD `parlament_id` INT NULL COMMENT 'Kommissions-ID von ws.parlament.ch'  AFTER `parlament_url`,
ADD `parlament_committee_number` INT NULL COMMENT 'committeeNumber auf ws.parlament.ch'  AFTER `parlament_id`,
ADD `parlament_subcommittee_number` INT NULL DEFAULT NULL COMMENT 'subcommitteeNumber auf ws.parlament.ch'  AFTER `parlament_committee_number`;

ALTER TABLE `kommission`
CHANGE `parlament_id` `parlament_id` INT(11) NULL COMMENT 'Kommissions-ID von ws.parlament.ch',
CHANGE `parlament_committee_number` `parlament_committee_number` INT(11) NULL COMMENT 'committeeNumber auf ws.parlament.ch';

ALTER TABLE `kommission_log`
CHANGE `parlament_id` `parlament_id` INT(11) NULL COMMENT 'Kommissions-ID von ws.parlament.ch',
CHANGE `parlament_committee_number` `parlament_committee_number` INT(11) NULL COMMENT 'committeeNumber auf ws.parlament.ch';

ALTER TABLE `rat`
ADD `parlament_id` INT NOT NULL COMMENT 'ID auf ws.parlament.ch' AFTER `mitglied_bezeichnung_weiblich_fr`;

ALTER TABLE `rat_log`
ADD `parlament_id` INT NOT NULL COMMENT 'ID auf ws.parlament.ch' AFTER `mitglied_bezeichnung_weiblich_fr`;

UPDATE `rat` SET `parlament_id` = 1 WHERE `id` = 1;
UPDATE `rat` SET `parlament_id` = 2 WHERE `id` = 2;
UPDATE `rat` SET `parlament_id` = NULL WHERE `id` = 3;

INSERT INTO `rat` (`id`, `abkuerzung`, `abkuerzung_fr`, `name_de`, `name_fr`, `name_it`, `name_en`, `anzahl_mitglieder`, `typ`, `interessenraum_id`, `anzeigestufe`, `gewicht`, `beschreibung`, `homepage_de`, `homepage_fr`, `homepage_it`, `homepage_en`, `mitglied_bezeichnung_maennlich_de`, `mitglied_bezeichnung_weiblich_de`, `mitglied_bezeichnung_maennlich_fr`, `mitglied_bezeichnung_weiblich_fr`, `parlament_id`, `notizen`, `eingabe_abgeschlossen_visa`, `eingabe_abgeschlossen_datum`, `kontrolliert_visa`, `kontrolliert_datum`, `freigabe_visa`, `freigabe_datum`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES (NULL, 'B', '', 'Vereinigte Bundesversammlung', 'Assemblée fédérale (Chambres réunies)', NULL, NULL, '248', 'legislativ', '1', '', '', NULL, NULL, NULL, NULL, NULL, '', '', '', '', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'roland', CURRENT_TIMESTAMP, 'roland', CURRENT_TIMESTAMP);

ALTER TABLE `rat`
ADD `parlament_type` CHAR(1) NULL COMMENT 'Ratstypecode von ws.parlament.ch' AFTER `parlament_id`;

ALTER TABLE `rat_log`
ADD `parlament_type` CHAR(1) NULL COMMENT 'Ratstypecode von ws.parlament.ch' AFTER `parlament_id`;

UPDATE `rat` SET `parlament_type` = 'N' WHERE `id` = 1;
UPDATE `rat` SET `parlament_type` = 'S' WHERE `id` = 2;
UPDATE `rat` SET `parlament_type` = 'B' WHERE `id` = 4;

-- ALTER TABLE `kommission`
-- ADD   UNIQUE  (`parlament_id`) ;

-- ALTER TABLE `kommission`
-- DROP `rat_id`;
--
-- ALTER TABLE `kommission_log`
-- DROP `rat_id`;
--
-- ALTER TABLE `in_kommission`
-- DROP `parlament_committee_function`,
-- DROP `parlament_committee_function_name`;
--
-- ALTER TABLE `in_kommission_log`
-- DROP `parlament_committee_function`,
-- DROP `parlament_committee_function_name`;

ALTER TABLE `kommission`
ADD `rat_id` INT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates' AFTER `name_fr` ,
ADD INDEX ( `rat_id` );

ALTER TABLE `kommission_log`
ADD `rat_id` INT NULL COMMENT 'Ratszugehörigkeit; Fremdschlüssel des Rates' AFTER `name_fr` ,
ADD INDEX ( `rat_id` );

ALTER TABLE `in_kommission`
ADD `parlament_committee_function` INT NULL COMMENT 'committeeFunction von ws.parlament.ch' AFTER `funktion`,
ADD `parlament_committee_function_name` VARCHAR(40) NULL AFTER `parlament_committee_function`;

ALTER TABLE `in_kommission_log`
ADD `parlament_committee_function` INT NULL COMMENT 'committeeFunction von ws.parlament.ch' AFTER `funktion`,
ADD `parlament_committee_function_name` VARCHAR(40) NULL AFTER `parlament_committee_function`;

-- 15.02.2015 III.

ALTER TABLE `kommission`
ADD `parlament_type_code` INT NULL COMMENT 'typeCode von ws.parlament.ch'  AFTER `parlament_subcommittee_number`;

ALTER TABLE `kommission_log`
ADD `parlament_type_code` INT NULL COMMENT 'typeCode von ws.parlament.ch'  AFTER `parlament_subcommittee_number`;

-- 23.02.2015

UPDATE parlamentarier SET parlament_biografie_id=4141 WHERE id=248;
UPDATE parlamentarier SET parlament_biografie_id=4144 WHERE id=250;
UPDATE parlamentarier SET parlament_biografie_id=4142 WHERE id=251;
UPDATE parlamentarier SET parlament_biografie_id=4143 WHERE id=252;
UPDATE parlamentarier SET parlament_biografie_id=151 WHERE id=254;
UPDATE parlamentarier SET parlament_biografie_id=4147 WHERE id=255;
UPDATE parlamentarier SET parlament_biografie_id=4146 WHERE id=256;

-- DELETE FROM `in_kommission` WHERE `in_kommission`.`id` = 636;

UPDATE kommission set parlament_id=19, parlament_committee_number=19 WHERE ID=47;
UPDATE kommission set parlament_id=20, parlament_committee_number=20 WHERE ID=48;
UPDATE kommission set parlament_id=22, parlament_committee_number=22 WHERE ID=49;
UPDATE kommission set parlament_id=21, parlament_committee_number=21 WHERE ID=50;
UPDATE kommission set parlament_id=18, parlament_committee_number=18 WHERE ID=51;
UPDATE kommission set parlament_id=23, parlament_committee_number=23 WHERE ID=52;
UPDATE kommission set parlament_id=24, parlament_committee_number=24 WHERE ID=53;
UPDATE kommission set parlament_id=25, parlament_committee_number=25 WHERE ID=54;
UPDATE kommission set parlament_id=17, parlament_committee_number=17 WHERE ID=55;
UPDATE kommission set parlament_id=15, parlament_committee_number=15 WHERE ID=56;
UPDATE kommission set parlament_id=16, parlament_committee_number=16 WHERE ID=57;
UPDATE kommission set parlament_id=14, parlament_committee_number=14 WHERE ID=58;

SET @disable_parlamentarier_kommissionen_update = 1;
UPDATE in_kommission SET kommission_id=47 WHERE kommission_id = 1 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=48 WHERE kommission_id = 3 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=49 WHERE kommission_id = 5 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=50 WHERE kommission_id = 7 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=51 WHERE kommission_id = 9 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=52 WHERE kommission_id = 11 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=53 WHERE kommission_id = 13 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=54 WHERE kommission_id = 15 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=55 WHERE kommission_id = 17 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=56 WHERE kommission_id = 19 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=57 WHERE kommission_id = 27 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
UPDATE in_kommission SET kommission_id=58 WHERE kommission_id = 39 AND parlamentarier_id IN (SELECT id FROM parlamentarier WHERE parlamentarier.rat_id = 2);
SET @disable_parlamentarier_kommissionen_update = NULL;

-- 24.02.2015

ALTER TABLE `kommission`
ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Kommission, leer (NULL) = unbekannt' after `mutter_kommission_id`,
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Kommission, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag' after von;

ALTER TABLE `kommission_log`
ADD `von` DATE NULL DEFAULT NULL COMMENT 'Beginn der Kommission, leer (NULL) = unbekannt' after `mutter_kommission_id`,
ADD `bis` DATE NULL DEFAULT NULL COMMENT 'Ende der Kommission, leer (NULL) = aktuell gültig, nicht leer = historischer Eintrag' after von;

-- SQL script from ws.parlament.ch 24.02.2015
-- Historize old Kommission ER=Parlamentarische Versammlung des Europarates, id=28
UPDATE kommission SET bis=STR_TO_DATE('24.02.2015','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\n\n', '24.02.2015/Roland: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE id=28;
-- Not in_kommission anymore (outdated kommission) ER=Parlamentarische Versammlung des Europarates, id=28
UPDATE in_kommission SET bis=STR_TO_DATE('24.02.2015','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\n\n', '24.02.2015/Roland: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE kommission_id=28;
-- Historize old Kommission NFB=Spezialkommission Neues Führungsmodell für die Bundesverwaltung NFB, id=46
UPDATE kommission SET bis=STR_TO_DATE('24.02.2015','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\n\n', '24.02.2015/Roland: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE id=46;
-- Not in_kommission anymore (outdated kommission) NFB=Spezialkommission Neues Führungsmodell für die Bundesverwaltung NFB, id=46
UPDATE in_kommission SET bis=STR_TO_DATE('24.02.2015','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\n\n', '24.02.2015/Roland: Kommission nicht mehr aktiv auf ws.parlament.ch',`notizen`) WHERE kommission_id=46;
