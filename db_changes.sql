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

-- 01.03.2015

ALTER TABLE `parlamentarier` CHANGE `erfasst` `erfasst` ENUM('Ja','Nein') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.';

ALTER TABLE `parlamentarier_log` CHANGE `erfasst` `erfasst` ENUM('Ja','Nein') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ist der Parlamentarier erfasst? Falls der Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.';

ALTER TABLE `person` CHANGE `erfasst` `erfasst` ENUM('Ja','Nein') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.';

ALTER TABLE `person_log` CHANGE `erfasst` `erfasst` ENUM('Ja','Nein') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Ist die Person erfasst? Falls der zugehörige Parlamentarier beispielsweise nicht mehr zur Wiederwahl antritt und deshalb die Person nicht erfasst wird, kann dieses Feld auf Nein gestellt werden. NULL bedeutet Status unklar.';

INSERT INTO `settings_category` (`id`, `name`, `description`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES (NULL, 'ErfassungsPeriode', 'Dieser Zeitraum kann in einer Graphik separat ausgewertet werden, z.B. für einen Erfassungswettbewerb.', NULL, 'roland', CURRENT_TIMESTAMP, 'roland', CURRENT_TIMESTAMP);

INSERT INTO `settings` (`id`, `key_name`, `value`, `description`, `category_id`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES (NULL, 'erfassungsPeriodeStart', '01.03.2015', 'Format: DD.MM.YYYY\n\nStart der Erfassungsperiode. Dieser Zeitraum wird für die separate Auswertung der Datenerfassung verwendet, z.B. für einen Erfassungswettbewerb.', '3', NULL, 'roland', CURRENT_TIMESTAMP, 'roland', CURRENT_TIMESTAMP), (NULL, 'erfassungsPeriodeEnde', NULL, 'Format: DD.MM.YYYY oder NULL\n\nEnde der Erfassungsperiode. NULL, wenn das Ende der Erfassungsperiode noch nicht bekannt ist. Dieser Zeitraum wird für die separate Auswertung der Datenerfassung verwendet, z.B. für einen Erfassungswettbewerb.', '3', NULL, 'roland', CURRENT_TIMESTAMP, 'roland', CURRENT_TIMESTAMP);

UPDATE person SET erfasst=NULL WHERE erfasst='Nein';
-- SELECT * FROM `parlamentarier` WHERE erfasst='Nein' AND freigabe_datum IS NULL AND kommissionen NOT LIKE '%WAK%';
UPDATE `parlamentarier`SET erfasst=NULL WHERE erfasst='Nein' AND freigabe_datum IS NULL AND kommissionen NOT LIKE '%WAK%';

-- 15.05.2015

ALTER TABLE `branche` CHANGE `kommission_id` `kommission_id` INT(11) NULL DEFAULT NULL COMMENT 'Zuständige Kommission im Nationalrat';
ALTER TABLE `branche` ADD `kommission2_id` INT NULL DEFAULT NULL COMMENT 'Zuständige Kommission im Ständerat' AFTER `kommission_id`, ADD INDEX (`kommission2_id`) ;

ALTER TABLE `branche_log` ADD `kommission2_id` INT NULL DEFAULT NULL COMMENT 'Zuständige Kommission im Ständerat' AFTER `kommission_id`, ADD INDEX (`kommission2_id`) ;

ALTER TABLE `branche` ADD CONSTRAINT `fk_kommission2_id` FOREIGN KEY (`kommission2_id`) REFERENCES `kommission`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

UPDATE branche SET kommission2_id = 47, updated_visa='roland' WHERE kommission_id = 1;
UPDATE branche SET kommission2_id = 48, updated_visa='roland' WHERE kommission_id = 3;
UPDATE branche SET kommission2_id = 49, updated_visa='roland' WHERE kommission_id = 5;
UPDATE branche SET kommission2_id = 50, updated_visa='roland' WHERE kommission_id = 7;
UPDATE branche SET kommission2_id = 51, updated_visa='roland' WHERE kommission_id = 9;
UPDATE branche SET kommission2_id = 52, updated_visa='roland' WHERE kommission_id = 11;
UPDATE branche SET kommission2_id = 53, updated_visa='roland' WHERE kommission_id = 13;
UPDATE branche SET kommission2_id = 54, updated_visa='roland' WHERE kommission_id = 15;
UPDATE branche SET kommission2_id = 55, updated_visa='roland' WHERE kommission_id = 17;
UPDATE branche SET kommission2_id = 56, updated_visa='roland' WHERE kommission_id = 19;
UPDATE branche SET kommission2_id = 57, updated_visa='roland' WHERE kommission_id = 27;
UPDATE branche SET kommission2_id = 58, updated_visa='roland' WHERE kommission_id = 39;

ALTER TABLE `kommission` ADD `zweitrat_kommission_id` INT NULL DEFAULT NULL COMMENT 'Entsprechende Kommission im anderen Rat, Stände- o. Nationalratskommission' AFTER `mutter_kommission_id`, ADD INDEX (`zweitrat_kommission_id`) ;

ALTER TABLE `kommission_log` ADD `zweitrat_kommission_id` INT NULL DEFAULT NULL COMMENT 'Entsprechende Kommission im anderen Rat, Stände- o. Nationalratskommission' AFTER `mutter_kommission_id`, ADD INDEX (`zweitrat_kommission_id`) ;

ALTER TABLE `kommission` ADD CONSTRAINT `fk_zweitrat_kommission_id` FOREIGN KEY (`zweitrat_kommission_id`) REFERENCES `kommission`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

UPDATE kommission SET zweitrat_kommission_id = 47, updated_visa='roland' WHERE id = 1;UPDATE kommission SET zweitrat_kommission_id = 1, updated_visa='roland' WHERE id = 47;
UPDATE kommission SET zweitrat_kommission_id = 48, updated_visa='roland' WHERE id = 3;UPDATE kommission SET zweitrat_kommission_id = 3, updated_visa='roland' WHERE id = 48;
UPDATE kommission SET zweitrat_kommission_id = 49, updated_visa='roland' WHERE id = 5;UPDATE kommission SET zweitrat_kommission_id = 5, updated_visa='roland' WHERE id = 49;
UPDATE kommission SET zweitrat_kommission_id = 50, updated_visa='roland' WHERE id = 7;UPDATE kommission SET zweitrat_kommission_id = 7, updated_visa='roland' WHERE id = 50;
UPDATE kommission SET zweitrat_kommission_id = 51, updated_visa='roland' WHERE id = 9;UPDATE kommission SET zweitrat_kommission_id = 9, updated_visa='roland' WHERE id = 51;
UPDATE kommission SET zweitrat_kommission_id = 52, updated_visa='roland' WHERE id = 11;UPDATE kommission SET zweitrat_kommission_id = 11, updated_visa='roland' WHERE id = 52;
UPDATE kommission SET zweitrat_kommission_id = 53, updated_visa='roland' WHERE id = 13;UPDATE kommission SET zweitrat_kommission_id = 13, updated_visa='roland' WHERE id = 53;
UPDATE kommission SET zweitrat_kommission_id = 54, updated_visa='roland' WHERE id = 15;UPDATE kommission SET zweitrat_kommission_id = 15, updated_visa='roland' WHERE id = 54;
UPDATE kommission SET zweitrat_kommission_id = 55, updated_visa='roland' WHERE id = 17;UPDATE kommission SET zweitrat_kommission_id = 17, updated_visa='roland' WHERE id = 55;
UPDATE kommission SET zweitrat_kommission_id = 56, updated_visa='roland' WHERE id = 19;UPDATE kommission SET zweitrat_kommission_id = 19, updated_visa='roland' WHERE id = 56;
UPDATE kommission SET zweitrat_kommission_id = 57, updated_visa='roland' WHERE id = 27;UPDATE kommission SET zweitrat_kommission_id = 27, updated_visa='roland' WHERE id = 57;
UPDATE kommission SET zweitrat_kommission_id = 58, updated_visa='roland' WHERE id = 39;UPDATE kommission SET zweitrat_kommission_id = 39, updated_visa='roland' WHERE id = 58;

ALTER TABLE `kommission` ADD `anzahl_mitglieder` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder' AFTER `sachbereiche_fr`;

ALTER TABLE `kommission_log` ADD `anzahl_mitglieder` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Anzahl Kommissionsmitglieder' AFTER `sachbereiche_fr`;

UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 47; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 1;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 48; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 3;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 49; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 5;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 50; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 7;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 51; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 9;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 52; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 11;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 53; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 13;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 54; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 15;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 55; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 17;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 56; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 19;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 57; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 27;
UPDATE kommission SET anzahl_mitglieder = 13, updated_visa='roland' WHERE id = 58; UPDATE kommission SET anzahl_mitglieder = 25, updated_visa='roland' WHERE id = 39;

INSERT INTO kommission (abkuerzung, abkuerzung_fr, name, name_fr, rat_id, typ, parlament_id, parlament_committee_number, parlament_subcommittee_number, parlament_type_code, von, created_visa, created_date, updated_visa, notizen) VALUES ('APF', 'APF', 'Delegation bei der parlamentarischen Versammlung der Frankophonie', 'Délégation auprès de l\'Assemblée parlementaire de la Francophonie', 4, 'kommission', 34, 34, NULL, 1, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Kommission importiert von ws.parlament.ch');

-- Workaround Script syntax highlighting: Add '

-- SQL script from ws.parlament.ch 15.05.2015
-- New in_kommission 4066 (2779) Rosmarie Quadranti Bü=Büro, 11=Fraktionspräsident/in, BDP, ZH, id=169
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (169, 39, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 11, 'Fraktionspräsident/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- Not in_kommission anymore Hansjörg Hassler, Bü=Büro, in_kommission_id=624, id=114
UPDATE in_kommission SET bis=STR_TO_DATE('15.05.2015','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\n\n', '15.05.2015/Roland: Update von ws.parlament.ch',`notizen`) WHERE id=624;
-- Update with new data Jean-Pierre Graber, GPK=Geschäftsprüfungskommissionen, in_kommission_id=680, id=260
UPDATE in_kommission SET parlament_committee_function=1, parlament_committee_function_name='Mitglied', updated_visa='import', notizen=CONCAT_WS('\n\n', '15.05.2015/Roland: Update von ws.parlament.ch',`notizen`) WHERE id=680;
-- Update missing parlament_biografie_id 4148 (3050) Rudolf Winkler BDP, ZH, id=257
UPDATE parlamentarier SET parlament_biografie_id = 4148, updated_visa='import', notizen=CONCAT_WS('\n\n', '15.05.2015/Roland: Update Biographie-ID via ws.parlament.ch',`notizen`) WHERE id = 257;
-- New in_kommission 4148 (3050) Rudolf Winkler SiK=Sicherheitspolitische Kommissionen, 1=Mitglied, BDP, ZH, id=257
-- INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (257, 7, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4110 (3005) Hans Egloff WAK=Kommissionen für Wirtschaft und Abgaben, 1=Mitglied, SVP, ZH, id=73
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (73, 11, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 1345 (2661) Thomas Müller RK=Kommissionen für Rechtsfragen, 1=Mitglied, SVP, SG, id=151
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (151, 15, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- Not in_kommission anymore Hans Egloff, RK=Kommissionen für Rechtsfragen, in_kommission_id=369, id=73
UPDATE in_kommission SET bis=STR_TO_DATE('15.05.2015','%d.%m.%Y'), updated_visa='import', notizen=CONCAT_WS('\n\n', '15.05.2015/Roland: Update von ws.parlament.ch',`notizen`) WHERE id=369;
-- New in_kommission 305 (2370) Didier Berberat APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 1=Mitglied, SP, NE, id=217
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (217, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 350 (2436) Maria Bernasconi APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 1=Mitglied, SP, GE, id=52
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (52, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 453 (2477) André Bugnon APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 1=Mitglied, SVP, VD, id=62
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (62, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4026 (2741) Raphaël Comte APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 12=Stellvertreter/in, FDP-Liberale, NE, id=220
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (220, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 12, 'Stellvertreter/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4079 (2788) Fathi Derder APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 2=Präsident/in, FDP-Liberale, VD, id=72
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (72, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'praesident', 2, 'Präsident/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 3885 (2688) Jean-Pierre Grin APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 12=Stellvertreter/in, SVP, VD, id=104
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (104, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 12, 'Stellvertreter/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 1150 (2613) Christian Levrat APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 6=Vizepräsident/in, SP, FR, id=235
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (235, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'vizepraesident', 6, 'Vizepräsident/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 498 (2521) Jacques Neirynck APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 12=Stellvertreter/in, CVP, VD, id=157
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (157, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 12, 'Stellvertreter/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 3920 (2723) Anne Seydoux-Christe APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 1=Mitglied, CVP, JU, id=243
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (243, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4113 (3007) Manuel Tornare APF-V=Delegation bei der parlamentarischen Versammlung der Frankophonie, 12=Stellvertreter/in, SP, GE, id=197
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (197, 62, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 12, 'Stellvertreter/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4088 (2793) Isidor Baumann NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, CVP, UR, id=216
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (216, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 15 (2270) Max Binder NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, SVP, ZH, id=54
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (54, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 3879 (2682) Olivier Français NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, FDP-Liberale, VD, id=86
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (86, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4076 (2792) Philipp Hadorn NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, SP, SO, id=111
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (111, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 409 (2459) Hans Hess NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, FDP-Liberale, OW, id=231
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (231, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 4145 (3047) Werner Hösli NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, SVP, GL, id=253
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (253, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 1150 (2613) Christian Levrat NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, SP, FR, id=235
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (235, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 540 (2561) Filippo Lombardi NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, CVP, TI, id=236
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (236, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 490 (2512) Ruedi Lustenberger NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, CVP, LU, id=138
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (138, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 1345 (2661) Thomas Müller NAD-V=Neat-Aufsichtsdelegation, 6=Vizepräsident/in, SVP, SG, id=151
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (151, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'vizepraesident', 6, 'Vizepräsident/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 361 (2424) Georges Theiler NAD-V=Neat-Aufsichtsdelegation, 2=Präsident/in, FDP-Liberale, LU, id=245
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (245, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'praesident', 2, 'Präsident/in', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');
-- New in_kommission 3829 (2665) Andy Tschümperlin NAD-V=Neat-Aufsichtsdelegation, 1=Mitglied, SP, SZ, id=200
INSERT INTO in_kommission (parlamentarier_id, kommission_id, von, funktion, parlament_committee_function, parlament_committee_function_name, created_visa, created_date, updated_visa, notizen) VALUES (200, 61, STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'mitglied', 1, 'Mitglied', 'import', STR_TO_DATE('15.05.2015','%d.%m.%Y'), 'import', '15.05.2015/Roland: Import von ws.parlament.ch');

-- 19.05.2015

ALTER TABLE `parlamentarier`
  ADD `parlament_number` INT NULL DEFAULT NULL COMMENT 'Number Feld auf ws.parlament.ch, wird von ws.parlament.ch importiert, wird z.B. als ID für Photos verwendet.' AFTER `parlament_biografie_id`,
  ADD `titel` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Titel des Parlamentariers, wird von ws.parlament.ch importiert' AFTER `beruf_interessengruppe_id`,
  ADD `sprache` ENUM('de','fr','it', 'sk', 'rm') NULL DEFAULT NULL COMMENT 'Sprache des Parlamentariers, wird von ws.parlament.ch importiert' AFTER `facebook_name`,
  ADD `aemter` TEXT NULL DEFAULT NULL COMMENT 'Politische Ämter (importiert von ws.parlament.ch mandate)' AFTER `titel`,
  ADD `weitere_aemter` TEXT NULL DEFAULT NULL COMMENT 'Zusätzliche Ämter (importiert von ws.parlament.ch additionalMandate)' AFTER `aemter`,
  ADD `homepage_2` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch' AFTER `homepage`,
  CHANGE `zivilstand` `zivilstand` ENUM('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt', 'verwitwet') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand',
  -- indexes
  ADD UNIQUE KEY `parlament_biografie_id_unique` (`parlament_biografie_id`);

ALTER TABLE `parlamentarier_log`
  ADD `parlament_number` INT NULL DEFAULT NULL COMMENT 'Number Feld auf ws.parlament.ch, wird z.B. als ID für Photos verwendet.' AFTER `parlament_biografie_id`,
  ADD `titel` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Titel des Parlamentariers' AFTER `beruf_interessengruppe_id`,
  ADD `sprache` ENUM('de','fr','it', 'sk', 'rm') NULL DEFAULT NULL COMMENT 'Sprache des Parlamentariers' AFTER `facebook_name`,
  ADD `aemter` TEXT NULL DEFAULT NULL COMMENT 'Politische Ämter (importiert von ws.parlament.ch mandate)' AFTER `titel`,
  ADD `weitere_aemter` TEXT NULL DEFAULT NULL COMMENT 'Zusätzliche Ämter (importiert von ws.parlament.ch additionalMandate)' AFTER `aemter`,
  ADD `homepage_2` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch' AFTER `homepage`,
  CHANGE `zivilstand` `zivilstand` ENUM('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt', 'verwitwet') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';

-- ALTER TABLE `parlamentarier` ADD `homepage_2` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch' AFTER `homepage`;
-- ALTER TABLE `parlamentarier_log` ADD `homepage_2` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Zweite Homepage, importiert von ws.parlament.ch' AFTER `homepage`;

-- ALTER TABLE `parlamentarier` CHANGE `titel` `titel` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Titel des Parlamentariers, wird von ws.parlament.ch importiert';
-- ALTER TABLE `parlamentarier_log` CHANGE `titel` `titel` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Titel des Parlamentariers, wird von ws.parlament.ch importiert';

-- ALTER TABLE `parlamentarier` CHANGE `zivilstand` `zivilstand` ENUM('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt', 'verwitwet') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';
-- ALTER TABLE `parlamentarier_log` CHANGE `zivilstand` `zivilstand` ENUM('ledig','verheiratet','geschieden','eingetragene partnerschaft','getrennt', 'verwitwet') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Zivilstand';

-- 19.06.2015

UPDATE `in_kommission` SET freigabe_datum = NOW(), freigabe_visa = 'roland';

-- 20.06.2015

SET @freigabe_date = STR_TO_DATE('20.06.2015', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE abkuerzung like 'apk%' OR abkuerzung like 'bü%' OR abkuerzung like 'fk%' OR abkuerzung like 'gpk%' OR abkuerzung like 'ik%'  OR abkuerzung like 'rk%' OR abkuerzung like 'sik%' OR abkuerzung like 'spk%' OR abkuerzung like 'wbk%' OR abkuerzung like 'kvf%' AND freigabe_datum IS NULL;

-- 22.06.2015

ALTER TABLE `person`
  ADD `titel` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Titel der Person, z.B. Lic. iur.' AFTER `beruf_fr`;

ALTER TABLE `person_log`
  ADD `titel` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Titel der Person, z.B. Lic. iur.' AFTER `beruf_fr`;

ALTER TABLE `parlamentarier`
  ADD `wikipedia` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Link zum Wkipedia-Eintrag des Parlamentariers' AFTER `facebook_name`;

ALTER TABLE `parlamentarier_log`
  ADD `wikipedia` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Link zum Wkipedia-Eintrag des Parlamentariers' AFTER `facebook_name`;

CREATE TABLE `interessenbindung_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Jahresvergütung von Intressenbindung',
  `interessenbindung_id` int(11) NOT NULL COMMENT 'Fremdschlüssel der Interessenbindung',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `verguetung` int(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Beschreibung der Vergütung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  INDEX `interessenbindung_id` (`interessenbindung_id`, `jahr`) COMMENT 'Idx interessenbindung_id',
  CONSTRAINT `fk_interessenbindung_id` FOREIGN KEY ( `interessenbindung_id` ) REFERENCES `interessenbindung` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Jahresvergütung durch Interessenbindungen';

CREATE TABLE `mandat_jahr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel Jahresvergütung von Mandat',
  `mandat_id` int(11) NOT NULL COMMENT 'Fremdschlüssel des Mandates',
  `jahr` smallint(6) unsigned NOT NULL COMMENT 'Jahr auf welche sich die Werte beziehen',
  `verguetung` int(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten des Mandates, z.B. Entschädigung für Beiratsfunktion.',
  `beschreibung` varchar(150) DEFAULT NULL COMMENT 'Beschreibung der Verfgütung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.',
  `quelle_url` varchar(255) DEFAULT NULL COMMENT 'URL der Quelle; zum Beleg',
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
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  INDEX `mandat_id` (`mandat_id`, `jahr`) COMMENT 'Idx mandat_id',
  CONSTRAINT `fk_mandat_id` FOREIGN KEY ( `mandat_id` ) REFERENCES `mandat` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Jahresvergütung durch Mandate';

-- SELECT id, 2014 as jahr, verguetung, quelle, quelle_url FROM `interessenbindung` WHERE verguetung IS NOT NULL

TRUNCATE `interessenbindung_jahr`;
TRUNCATE `mandat_jahr`;
INSERT INTO `interessenbindung_jahr` (interessenbindung_id, jahr, verguetung, quelle, quelle_url, updated_date, updated_visa, created_date, created_visa, eingabe_abgeschlossen_visa, eingabe_abgeschlossen_datum, kontrolliert_visa, kontrolliert_datum) SELECT id, 2014 as jahr, verguetung, quelle, quelle_url, NOW() as updated_date, 'roland' as updated_visa, created_date, created_visa, eingabe_abgeschlossen_visa, eingabe_abgeschlossen_datum, kontrolliert_visa, kontrolliert_datum  FROM `interessenbindung` WHERE verguetung IS NOT NULL;
INSERT INTO `mandat_jahr` (mandat_id, jahr, verguetung, quelle, quelle_url, updated_date, updated_visa, created_date, created_visa, eingabe_abgeschlossen_visa, eingabe_abgeschlossen_datum, kontrolliert_visa, kontrolliert_datum) SELECT id, 2014 as jahr, verguetung, quelle, quelle_url, NOW() as updated_date, 'roland' as updated_visa, created_date, created_visa, eingabe_abgeschlossen_visa, eingabe_abgeschlossen_datum, kontrolliert_visa, kontrolliert_datum   FROM `mandat` WHERE verguetung IS NOT NULL;

ALTER TABLE `interessenbindung` DROP `verguetung`;
ALTER TABLE `interessenbindung_log` DROP `verguetung`;

ALTER TABLE `mandat` DROP `verguetung`;
ALTER TABLE `mandat_log` DROP `verguetung`;

ALTER TABLE `interessenbindung_jahr`
  ADD UNIQUE KEY `idx_jahr_unique` (`interessenbindung_id`,`jahr`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE `mandat_jahr`
  ADD UNIQUE KEY `idx_jahr_unique` (`mandat_id`,`jahr`) COMMENT 'Fachlicher unique constraint';

-- 19.08.2015

-- SQL script from ws.parlament.ch 19.08.2015
-- Kommissionen
-- Parlamentarier
-- Update Parlamentarier Allemann, Evi, id=42, fields: anzahl_kinder
UPDATE `parlamentarier` SET anzahl_kinder = 2, updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=42;
-- Update Parlamentarier Bernasconi, Maria, id=52, fields: weitere_aemter
UPDATE `parlamentarier` SET weitere_aemter = 'Co-présidente des femmes socialistes suisses: depuis mars 2001 jusqu\'à novembre 2011; Membre du comité directeur du PS suisse: depuis  2001 jusqu\'à novembre 2011; Membre du comité directeur du PS GE: dès 2000; Vice-présidente du PS Genève: depuis 2002 jusqu\'à 2004', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=52;
-- Update Parlamentarier Böhni, Thomas, id=56, fields: homepage, anzahl_kinder
UPDATE `parlamentarier` SET homepage = 'http://www.thomasboehni.ch', anzahl_kinder = '3', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=56;
-- Update Parlamentarier Candinas, Martin, id=65, fields: anzahl_kinder
UPDATE `parlamentarier` SET anzahl_kinder = 3, updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=65;
-- Update Parlamentarier Geissbühler, Andrea Martina, id=93, fields: anzahl_kinder
UPDATE `parlamentarier` SET anzahl_kinder = 2, updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=93;
-- Update Parlamentarier Grossen, Jürg, id=106, fields: weitere_aemter
UPDATE `parlamentarier` SET weitere_aemter = 'Präsident der glp Thun/Berner Oberland: 2009 bis 2012; Vizepräsident der GLP-Fraktion: seit 2015', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=106;
-- Update Parlamentarier Kiener Nellen, Margret, id=129, fields: weitere_aemter
UPDATE `parlamentarier` SET weitere_aemter = 'Co-Präsidentin SP Frauen Kanton Bern: von März 1992 bis März 1996; Präsidentin Arbeitsgruppe Steuerpolitik: seit 2004; Co-Präsidentin der SPS-Fachkommission für sexuelle Orientierung und Identität: seit März 2010', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=129;
-- Update Parlamentarier Minder, Thomas, id=238, fields: partei_id
UPDATE `parlamentarier` SET partei_id = NULL, updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=238;
-- Update Parlamentarier Recordon, Luc, id=240, fields: aemter
UPDATE `parlamentarier` SET aemter = 'Législatif communal: depuis novembre 1975 jusqu\'à décembre 1989; Exécutif communal: dès janvier 1990; Président du conseil d\'établissement primaire de Prilly - Romanel - Jouxtens-Mézery: dès février 2015; Membre du conseil d\'établissement secondaire Prilly - Romanel - Jouxtens-Mézery: dès février 2015; Législatif cantonal: depuis mars 1990 jusqu\'à novembre 2003', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=240;
-- Update Parlamentarier Schneider Schüttel, Ursula, id=187, fields: homepage
UPDATE `parlamentarier` SET homepage = 'http://www.ursulaschneider.ch', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=187;
-- Update Parlamentarier Semadeni, Silva, id=191, fields: adresse_plz, adresse_ort
UPDATE `parlamentarier` SET adresse_plz = '7000', adresse_ort = 'Coira', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=191;
-- Update Parlamentarier Wermuth, Cédric, id=211, fields: adresse_firma, adresse_strasse, adresse_plz, adresse_ort
UPDATE `parlamentarier` SET adresse_firma = 'Swiss Post Box 104709', adresse_strasse = 'Zürcherstrasse 161', adresse_plz = '8010', adresse_ort = 'Zürich', updated_visa='import', notizen=CONCAT_WS('\n\n', '19.08.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=211;

-- 26.10.2015

INSERT INTO `partei` (`id`, `abkuerzung`, `abkuerzung_fr`, `name`, `name_fr`, `fraktion_id`, `gruendung`, `position`, `farbcode`, `homepage`, `homepage_fr`, `email`, `email_fr`, `twitter_name`, `twitter_name_fr`, `beschreibung`, `beschreibung_fr`, `notizen`, `eingabe_abgeschlossen_visa`, `eingabe_abgeschlossen_datum`, `kontrolliert_visa`, `kontrolliert_datum`, `freigabe_visa`, `freigabe_datum`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES
(NULL, 'PdA', NULL, 'Partei der Arbeit', NULL, NULL, NULL, 'links', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'roland', CURRENT_TIMESTAMP, 'roland', CURRENT_TIMESTAMP),
(NULL, 'LPS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'roland', CURRENT_TIMESTAMP, 'roland', CURRENT_TIMESTAMP);

-- Parlamentarier
-- Insert parlamentarier Addor, Jean-Luc, biografie_id=4154
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4154, NULL, 'Addor', 'Jean-Luc', 1, 23, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Addor, Jean-Luc, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3055', kleinbild = '3055.jpg', sprache = 'fr', nachname = 'Addor', vorname = 'Jean-Luc', partei_id = '5', geburtstag = '1964-04-22', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Ammann, Thomas, biografie_id=4180
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4180, NULL, 'Ammann', 'Thomas', 1, 17, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Ammann, Thomas, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3056', kleinbild = '3056.jpg', sprache = 'de', nachname = 'Ammann', vorname = 'Thomas', partei_id = '7', geburtstag = '1964-07-13', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Arnold, Beat, biografie_id=4155
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4155, NULL, 'Arnold', 'Beat', 1, 4, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Arnold, Beat, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3057', kleinbild = '3057.jpg', sprache = 'de', nachname = 'Arnold', vorname = 'Beat', partei_id = '5', geburtstag = '1978-04-24', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Arslan, Sibel, biografie_id=4184
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4184, NULL, 'Arslan', 'Sibel', 1, 12, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Arslan, Sibel, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3058', kleinbild = '3058.jpg', sprache = 'de', nachname = 'Arslan', vorname = 'Sibel', partei_id = '4', geburtstag = '1980-06-23', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Barrile, Angelo, biografie_id=4203
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4203, NULL, 'Barrile', 'Angelo', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Barrile, Angelo, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3104', kleinbild = '3104.jpg', sprache = 'de', nachname = 'Barrile', vorname = 'Angelo', partei_id = '3', geburtstag = '1976-08-22', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Bauer, Philippe, biografie_id=4187
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4187, NULL, 'Bauer', 'Philippe', 1, 24, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Bauer, Philippe, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3059', kleinbild = '3059.jpg', sprache = 'fr', nachname = 'Bauer', vorname = 'Philippe', partei_id = '1', geburtstag = '1962-04-09', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Béglé, Claude, biografie_id=4182
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4182, NULL, 'Béglé', 'Claude', 1, 22, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Béglé, Claude, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3060', kleinbild = '3060.jpg', sprache = 'fr', nachname = 'Béglé', vorname = 'Claude', partei_id = '7', geburtstag = '1949-12-04', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Borloz, Frédéric, biografie_id=4188
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4188, NULL, 'Borloz', 'Frédéric', 1, 22, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Borloz, Frédéric, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3061', kleinbild = '3061.jpg', sprache = 'fr', nachname = 'Borloz', vorname = 'Frédéric', partei_id = '1', geburtstag = '1966-04-22', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Brélaz, Daniel, biografie_id=724
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (724, NULL, 'Brélaz', 'Daniel', 1, 22, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Brélaz, Daniel, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, titel, sprache, nachname, vorname, kanton_id, rat_id, partei_id, geburtstag, arbeitssprache, geschlecht, anzahl_kinder, zivilstand, beruf, aemter
UPDATE `parlamentarier` SET parlament_number = '2023', kleinbild = '2023.jpg', titel = 'lic. en mathématique', sprache = 'fr', nachname = 'Brélaz', vorname = 'Daniel', kanton_id = '22', rat_id = '1', partei_id = '4', geburtstag = '1950-01-04', arbeitssprache = 'fr', geschlecht = 'M', anzahl_kinder = '1', zivilstand = 'verheiratet', beruf = 'Syndic de Lausanne', aemter = 'Législatif communal: depuis 1986 jusqu\'en 1989; Exécutif communal: depuis 1990 jusqu\'en 2007; Législatif cantonal: depuis 1990 jusqu\'en 2007', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Buffat, Michaël, biografie_id=4156
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4156, NULL, 'Buffat', 'Michaël', 1, 22, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Buffat, Michaël, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3062', kleinbild = '3062.jpg', sprache = 'fr', nachname = 'Buffat', vorname = 'Michaël', partei_id = '5', geburtstag = '1979-09-27', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Bühler, Manfred, biografie_id=4157
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4157, NULL, 'Bühler', 'Manfred', 1, 2, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Bühler, Manfred, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3063', kleinbild = '3063.jpg', sprache = 'fr', nachname = 'Bühler', vorname = 'Manfred', partei_id = '5', geburtstag = '1979-04-10', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Burgherr, Thomas, biografie_id=4158
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4158, NULL, 'Burgherr', 'Thomas', 1, 19, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Burgherr, Thomas, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3064', kleinbild = '3064.jpg', sprache = 'de', nachname = 'Burgherr', vorname = 'Thomas', partei_id = '5', geburtstag = '1962-08-01', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Burkart, Thierry, biografie_id=4189
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4189, NULL, 'Burkart', 'Thierry', 1, 19, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Burkart, Thierry, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, homepage, email, titel, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht, adresse_firma, adresse_ort, adresse_strasse, adresse_plz, aemter, weitere_aemter
UPDATE `parlamentarier` SET parlament_number = '3065', kleinbild = '3065.jpg', homepage = 'http://www.thierry-burkart.ch', email = '-', titel = 'lic. iur., LL.M., Rechtsanwalt', sprache = 'de', nachname = 'Burkart', vorname = 'Thierry', partei_id = '1', geburtstag = '1975-08-21', arbeitssprache = 'de', geschlecht = 'M', adresse_firma = 'VOSER RECHTSANWÄLTE', adresse_ort = 'Baden', adresse_strasse = 'Stadtturmstrasse 19', adresse_plz = '5400', aemter = 'Grossrat des Kantons Aargau von 2001 bis 2015; Grossratspräsident 2014', weitere_aemter = 'Mitglied der Geschäftsleitung der FDP.Die Liberalen Aargau seit 1999; Präsident Jungfreisinnige Aargau von 1999 bis 2002; Vizepräsident der FDP.Die Liberalen Aargau von 2003 bis 2010; Präsident der FDP.Die Liberalen Aargau von 2010 bis 2013', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Update Parlamentarier Buttet, Yannick, id=64, fields: aemter
UPDATE `parlamentarier` SET aemter = 'Exécutif communal de Collombey-Muraz: dès janvier 2009; Conseiller communal de 2009 à 2012; Président dès 2013', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=64;
-- Insert parlamentarier Campell, Duri, biografie_id=4200
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4200, NULL, 'Campell', 'Duri', 1, 18, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Campell, Duri, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3066', kleinbild = '3066.jpg', sprache = 'de', nachname = 'Campell', vorname = 'Duri', partei_id = '8', geburtstag = '1963-04-05', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Chiesa, Marco, biografie_id=4159
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4159, NULL, 'Chiesa', 'Marco', 1, 21, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Chiesa, Marco, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3067', kleinbild = '3067.jpg', sprache = 'it', nachname = 'Chiesa', vorname = 'Marco', partei_id = '5', geburtstag = '1974-10-10', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier de la Reussille, Denis, biografie_id=4201
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4201, NULL, 'de la Reussille', 'Denis', 1, 24, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier de la Reussille, Denis, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3068', kleinbild = '3068.jpg', sprache = 'fr', nachname = 'de la Reussille', vorname = 'Denis', partei_id = '13', geburtstag = '1960-10-24', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Dettling, Marcel, biografie_id=4160
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4160, NULL, 'Dettling', 'Marcel', 1, 5, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Dettling, Marcel, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3069', kleinbild = '3069.jpg', sprache = 'de', nachname = 'Dettling', vorname = 'Marcel', partei_id = '5', geburtstag = '1981-02-01', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Dobler, Marcel, biografie_id=4190
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4190, NULL, 'Dobler', 'Marcel', 1, 17, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Dobler, Marcel, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3070', kleinbild = '3070.jpg', sprache = 'de', nachname = 'Dobler', vorname = 'Marcel', partei_id = '1', geburtstag = '1980-08-29', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Eymann, Christoph, biografie_id=74
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (74, NULL, 'Eymann', 'Christoph', 1, 12, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Eymann, Christoph, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, titel, sprache, nachname, vorname, kanton_id, rat_id, partei_id, geburtstag, arbeitssprache, geschlecht, zivilstand, beruf, militaerischer_grad_id, aemter, weitere_aemter
UPDATE `parlamentarier` SET parlament_number = '2287', kleinbild = '2287.jpg', titel = 'Dr. iur.', sprache = 'de', nachname = 'Eymann', vorname = 'Christoph', kanton_id = '12', rat_id = '1', partei_id = '14', geburtstag = '1951-01-15', arbeitssprache = 'de', geschlecht = 'M', zivilstand = 'geschieden', beruf = 'Regierungsrat', militaerischer_grad_id = '17', aemter = 'Weiterer Bürgerrrat von 1989 bis 1991, Grosser Rat des Kantons Basel-Stadt von 1984 bis 1995', weitere_aemter = 'Präsident Nationales Komitee Europäisches Naturschutzjahr 1995', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Fehlmann Rielle, Laurence, biografie_id=4195
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4195, NULL, 'Fehlmann Rielle', 'Laurence', 1, 25, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Fehlmann Rielle, Laurence, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3071', kleinbild = '3071.jpg', sprache = 'fr', nachname = 'Fehlmann Rielle', vorname = 'Laurence', partei_id = '3', geburtstag = '1955-09-11', arbeitssprache = 'fr', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Fricker, Jonas, biografie_id=4185
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4185, NULL, 'Fricker', 'Jonas', 1, 19, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Fricker, Jonas, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3072', kleinbild = '3072.jpg', sprache = 'de', nachname = 'Fricker', vorname = 'Jonas', partei_id = '4', geburtstag = '1977-03-30', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Genecand, Benoît, biografie_id=4191
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4191, NULL, 'Genecand', 'Benoît', 1, 25, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Genecand, Benoît, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, email, email, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht, anzahl_kinder, zivilstand, adresse_strasse, adresse_plz, adresse_ort, aemter
UPDATE `parlamentarier` SET parlament_number = '3073', kleinbild = '3073.jpg', email = '-', email = 'benoitgenecand@gmail.com', sprache = 'fr', nachname = 'Genecand', vorname = 'Benoît', partei_id = '1', geburtstag = '1964-01-28', arbeitssprache = 'fr', geschlecht = 'M', anzahl_kinder = '5', zivilstand = 'verheiratet', adresse_strasse = 'Rue de la Synagogue 33', adresse_plz = '1204', adresse_ort = 'Genève', aemter = 'Assemblée Constituante genevoise: député (2008-2012); Grand Conseil genevois: député (2013-2015)', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Glarner, Andreas, biografie_id=4161
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4161, NULL, 'Glarner', 'Andreas', 1, 19, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Glarner, Andreas, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3074', kleinbild = '3074.jpg', sprache = 'de', nachname = 'Glarner', vorname = 'Andreas', partei_id = '5', geburtstag = '1962-10-09', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Gmür, Andrea, biografie_id=4181
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4181, NULL, 'Gmür', 'Andrea', 1, 3, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Gmür, Andrea, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3075', kleinbild = '3075.jpg', sprache = 'de', nachname = 'Gmür', vorname = 'Andrea', partei_id = '7', geburtstag = '1964-07-17', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Grüter, Franz, biografie_id=4162
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4162, NULL, 'Grüter', 'Franz', 1, 3, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Grüter, Franz, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3076', kleinbild = '3076.jpg', sprache = 'de', nachname = 'Grüter', vorname = 'Franz', partei_id = '5', geburtstag = '1963-07-29', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Guldimann, Tim, biografie_id=4196
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4196, NULL, 'Guldimann', 'Tim', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Guldimann, Tim, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3077', kleinbild = '3077.jpg', sprache = 'de', nachname = 'Guldimann', vorname = 'Tim', partei_id = '3', geburtstag = '1950-09-19', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Hess, Erich, biografie_id=4163
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4163, NULL, 'Hess', 'Erich', 1, 2, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Hess, Erich, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3078', kleinbild = '3078.jpg', sprache = 'de', nachname = 'Hess', vorname = 'Erich', partei_id = '5', geburtstag = '1981-03-25', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Hess, Hermann, biografie_id=4192
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4192, NULL, 'Hess', 'Hermann', 1, 20, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Hess, Hermann, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3079', kleinbild = '3079.jpg', sprache = 'de', nachname = 'Hess', vorname = 'Hermann', partei_id = '1', geburtstag = '1951-12-22', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Imark, Christian, biografie_id=4164
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4164, NULL, 'Imark', 'Christian', 1, 11, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Imark, Christian, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3080', kleinbild = '3080.jpg', sprache = 'de', nachname = 'Imark', vorname = 'Christian', partei_id = '5', geburtstag = '1982-01-29', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Keller-Inhelder, Barbara, biografie_id=4165
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4165, NULL, 'Keller-Inhelder', 'Barbara', 1, 17, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Keller-Inhelder, Barbara, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3081', kleinbild = '3081.jpg', sprache = 'de', nachname = 'Keller-Inhelder', vorname = 'Barbara', geburtstag = '1968-08-24', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Köppel, Roger, biografie_id=4166
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4166, NULL, 'Köppel', 'Roger', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Köppel, Roger, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3082', kleinbild = '3082.jpg', sprache = 'de', nachname = 'Köppel', vorname = 'Roger', partei_id = '5', geburtstag = '1965-03-21', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Marchand-Balet, Géraldine, biografie_id=4183
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4183, NULL, 'Marchand-Balet', 'Géraldine', 1, 23, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Marchand-Balet, Géraldine, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3083', kleinbild = '3083.jpg', sprache = 'fr', nachname = 'Marchand-Balet', vorname = 'Géraldine', partei_id = '7', geburtstag = '1971-01-18', arbeitssprache = 'fr', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Marti, Min Li, biografie_id=4197
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4197, NULL, 'Marti', 'Min Li', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Marti, Min Li, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3084', kleinbild = '3084.jpg', sprache = 'de', nachname = 'Marti', vorname = 'Min Li', partei_id = '3', geburtstag = '1974-06-01', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Martullo-Blocher, Magdalena, biografie_id=4167
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4167, NULL, 'Martullo-Blocher', 'Magdalena', 1, 18, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Martullo-Blocher, Magdalena, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3085', kleinbild = '3085.jpg', sprache = 'de', nachname = 'Martullo-Blocher', vorname = 'Magdalena', partei_id = '5', geburtstag = '1969-08-13', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Mazzone, Lisa, biografie_id=4186
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4186, NULL, 'Mazzone', 'Lisa', 1, 25, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Mazzone, Lisa, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3086', kleinbild = '3086.jpg', sprache = 'fr', nachname = 'Mazzone', vorname = 'Lisa', partei_id = '4', geburtstag = '1988-01-25', arbeitssprache = 'fr', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Meyer, Mattea, biografie_id=4198
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4198, NULL, 'Meyer', 'Mattea', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Meyer, Mattea, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3087', kleinbild = '3087.jpg', sprache = 'de', nachname = 'Meyer', vorname = 'Mattea', partei_id = '3', geburtstag = '1987-11-09', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Nantermod, Philippe, biografie_id=4193
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4193, NULL, 'Nantermod', 'Philippe', 1, 23, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Nantermod, Philippe, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3088', kleinbild = '3088.jpg', sprache = 'fr', nachname = 'Nantermod', vorname = 'Philippe', partei_id = '1', geburtstag = '1984-03-27', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Nicolet, Jacques, biografie_id=4168
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4168, NULL, 'Nicolet', 'Jacques', 1, 22, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Nicolet, Jacques, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3089', kleinbild = '3089.jpg', sprache = 'fr', nachname = 'Nicolet', vorname = 'Jacques', partei_id = '5', geburtstag = '1965-10-24', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Page, Pierre-André, biografie_id=4169
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4169, NULL, 'Page', 'Pierre-André', 1, 10, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Page, Pierre-André, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3090', kleinbild = '3090.jpg', sprache = 'fr', nachname = 'Page', vorname = 'Pierre-André', partei_id = '5', geburtstag = '1960-04-19', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Ruppen, Franz, biografie_id=4170
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4170, NULL, 'Ruppen', 'Franz', 1, 23, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Ruppen, Franz, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3091', kleinbild = '3091.jpg', sprache = 'de', nachname = 'Ruppen', vorname = 'Franz', partei_id = '5', geburtstag = '1971-02-24', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Salzmann, Werner, biografie_id=4172
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4172, NULL, 'Salzmann', 'Werner', 1, 2, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Salzmann, Werner, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3092', kleinbild = '3092.jpg', sprache = 'de', nachname = 'Salzmann', vorname = 'Werner', partei_id = '5', geburtstag = '1962-11-05', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Sauter, Regine, biografie_id=4202
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4202, NULL, 'Sauter', 'Regine', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Sauter, Regine, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3103', kleinbild = '3103.jpg', sprache = 'de', nachname = 'Sauter', vorname = 'Regine', partei_id = '1', geburtstag = '1966-04-16', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Schmidt, Roberto, biografie_id=3905
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (3905, NULL, 'Schmidt', 'Roberto', 1, 23, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Schmidt, Roberto, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, titel, sprache, nachname, vorname, kanton_id, rat_id, partei_id, geburtstag, arbeitssprache, geschlecht, beruf, militaerischer_grad_id, aemter
UPDATE `parlamentarier` SET parlament_number = '2708', kleinbild = '2708.jpg', titel = 'lic. iur.', sprache = 'de', nachname = 'Schmidt', vorname = 'Roberto', kanton_id = '23', rat_id = '1', partei_id = '7', geburtstag = '1962-07-16', arbeitssprache = 'de', geschlecht = 'M', beruf = 'Gemeindepräsident', militaerischer_grad_id = '2', aemter = 'Exekutive der Gemeinde: Gemeinderat von 2000 bis 2004; Gemeindepräsident seit 2004', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Seiler Graf, Priska, biografie_id=4199
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4199, NULL, 'Seiler Graf', 'Priska', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Seiler Graf, Priska, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3094', kleinbild = '3094.jpg', sprache = 'de', nachname = 'Seiler Graf', vorname = 'Priska', partei_id = '3', geburtstag = '1968-08-29', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Sollberger, Sandra, biografie_id=4173
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4173, NULL, 'Sollberger', 'Sandra', 1, 13, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Sollberger, Sandra, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3095', kleinbild = '3095.jpg', sprache = 'de', nachname = 'Sollberger', vorname = 'Sandra', geburtstag = '1973-10-27', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Steinemann, Barbara, biografie_id=4174
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4174, NULL, 'Steinemann', 'Barbara', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Steinemann, Barbara, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3096', kleinbild = '3096.jpg', sprache = 'de', nachname = 'Steinemann', vorname = 'Barbara', partei_id = '5', geburtstag = '1976-06-18', arbeitssprache = 'de', geschlecht = 'F', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Tuena, Mauro, biografie_id=4175
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4175, NULL, 'Tuena', 'Mauro', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Tuena, Mauro, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3097', kleinbild = '3097.jpg', sprache = 'de', nachname = 'Tuena', vorname = 'Mauro', partei_id = '5', geburtstag = '1972-01-25', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Vogt, Hans-Ueli, biografie_id=4176
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4176, NULL, 'Vogt', 'Hans-Ueli', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Vogt, Hans-Ueli, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3098', kleinbild = '3098.jpg', sprache = 'de', nachname = 'Vogt', vorname = 'Hans-Ueli', partei_id = '5', geburtstag = '1969-12-05', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Walliser, Bruno, biografie_id=4177
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4177, NULL, 'Walliser', 'Bruno', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Walliser, Bruno, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3099', kleinbild = '3099.jpg', sprache = 'de', nachname = 'Walliser', vorname = 'Bruno', partei_id = '5', geburtstag = '1966-04-11', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Wehrli, Laurent, biografie_id=4194
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4194, NULL, 'Wehrli', 'Laurent', 1, 22, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Wehrli, Laurent, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3100', kleinbild = '3100.jpg', sprache = 'fr', nachname = 'Wehrli', vorname = 'Laurent', partei_id = '1', geburtstag = '1965-06-04', arbeitssprache = 'fr', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Zanetti, Claudio, biografie_id=4178
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4178, NULL, 'Zanetti', 'Claudio', 1, 1, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Zanetti, Claudio, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3101', kleinbild = '3101.jpg', sprache = 'de', nachname = 'Zanetti', vorname = 'Claudio', partei_id = '5', geburtstag = '1967-06-16', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();
-- Insert parlamentarier Zuberbühler, David, biografie_id=4179
INSERT INTO parlamentarier (parlament_biografie_id, parlament_number, nachname, vorname, rat_id, kanton_id, im_rat_seit, created_visa, created_date, updated_visa, notizen) VALUES (4179, NULL, 'Zuberbühler', 'David', 1, 15, STR_TO_DATE('30.11.2015','%d.%m.%Y'), 'import', STR_TO_DATE('26.10.2015','%d.%m.%Y'), 'import', '26.10.2015/Roland: Import von ws.parlament.ch');
-- Update Parlamentarier Zuberbühler, David, id=LAST_INSERT_ID(), fields: parlament_number, kleinbild, sprache, nachname, vorname, partei_id, geburtstag, arbeitssprache, geschlecht
UPDATE `parlamentarier` SET parlament_number = '3102', kleinbild = '3102.jpg', sprache = 'de', nachname = 'Zuberbühler', vorname = 'David', partei_id = '5', geburtstag = '1979-01-01', arbeitssprache = 'de', geschlecht = 'M', updated_visa='import', notizen=CONCAT_WS('\n\n', '26.10.2015/Roland: Update via ws.parlament.ch',`notizen`) WHERE id=LAST_INSERT_ID();

-- 29.11.2015

ALTER TABLE `parlamentarier` CHANGE `sprache` `sprache` ENUM('de','fr','it','sk','rm','tr') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Sprache des Parlamentariers, wird von ws.parlament.ch importiert';

ALTER TABLE `parlamentarier_log` CHANGE `sprache` `sprache` ENUM('de','fr','it','sk','rm','tr') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Sprache des Parlamentariers, wird von ws.parlament.ch importiert';

ALTER TABLE `parlamentarier` ADD `parlament_interessenbindungen` TEXT NULL DEFAULT NULL COMMENT 'Importierte Interessenbindungen von ws.parlament.ch' AFTER `parlament_number`;

ALTER TABLE `parlamentarier_log` ADD `parlament_interessenbindungen` TEXT NULL DEFAULT NULL COMMENT 'Importierte Interessenbindungen von ws.parlament.ch' AFTER `parlament_number`;

ALTER TABLE `organisation` ADD `rechtsform_handelsregister` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Code der Rechtsform des Handelsregister, z.B. 0106 für AG. Das Feld kann importiert werden.' AFTER `rechtsform`;
ALTER TABLE `organisation_log` ADD `rechtsform_handelsregister` VARCHAR(4) NULL DEFAULT NULL COMMENT 'Code der Rechtsform des Handelsregister, z.B. 0106 für AG. Das Feld kann importiert werden.' AFTER `rechtsform`;

-- select uid, name_de from organisation group by uid having count(uid) > 1;
-- CHE-101.400.176     2149,2150   Helvetia Schweizerische Versicherungsgesellschaft AG,Helvetia Schweizerische Lebensversicherungsgesellschaft AG
-- CHE-111.743.407     2480,1724   Stiftung Simplon - Ecomuseum & Passwege,Ecomuseum Simplon
-- CHE-259.943.106     1785,1788   Energieplattform Immobilien,Swiss China Investment Platform Association (SCIPA)

UPDATE organisation SET uid='CHE-262.025.530',updated_visa='roland' WHERE id=1785;

ALTER TABLE `organisation`
  ADD UNIQUE `uid_unique` (`uid`) COMMENT 'Fachlicher unique constraint';

-- ALTER TABLE `organisation`
--   DROP KEY `uid_unique`;

-- 01.12.2015

ALTER TABLE `organisation`
  ADD `abkuerzung_de` varchar(20) NULL DEFAULT NULL COMMENT 'Abkürzung der Organisation, kann in der Anzeige dem Namen nachgestellt werden, z.B. Schweizer Kaderorganisation (SKO)' AFTER `ort`,
  ADD `alias_namen_de` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `abkuerzung_de`,
  ADD `abkuerzung_fr` varchar(20) NULL DEFAULT NULL COMMENT 'Französische Abkürzung der Organisation' AFTER `alias_namen_de`,
  ADD `alias_namen_fr` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Französischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `abkuerzung_fr`,
  ADD `rechtsform_zefix` INT NULL DEFAULT NULL COMMENT 'Numerischer Rechtsformcode von Zefix, z.B. 3 für AG. Das Feld kann importiert werden.' AFTER `rechtsform_handelsregister`;

ALTER TABLE `organisation_log`
  ADD `abkuerzung_de` varchar(20) NULL DEFAULT NULL COMMENT 'Abkürzung der Organisation, kann in der Anzeige dem Namen nachgestellt werden, z.B. Schweizer Kaderorganisation (SKO)' AFTER `ort`,
  ADD `alias_namen_de` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `abkuerzung_de`,
  ADD `abkuerzung_fr` varchar(20) NULL DEFAULT NULL COMMENT 'Französische Abkürzung der Organisation' AFTER `alias_namen_de`,
  ADD `alias_namen_fr` VARCHAR( 255 ) NULL DEFAULT NULL COMMENT 'Französischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternative Namen für die Organisation; bei der Suche werden für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `abkuerzung_fr`,
  ADD `rechtsform_zefix` INT NULL DEFAULT NULL COMMENT 'Numerischer Rechtsformcode von Zefix, z.B. 3 für AG. Das Feld kann importiert werden.' AFTER `rechtsform_handelsregister`;

-- 19.12.2015

-- Fix interessenbingung NOT NULL
ALTER TABLE `interessenbindung_jahr` CHANGE `verguetung` `verguetung` INT(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.';
ALTER TABLE `interessenbindung_jahr_log` CHANGE `verguetung` `verguetung` INT(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten aus dieser Interessenbindung, z.B. Entschädigung für Beiratsfunktion.';

ALTER TABLE `mandat_jahr` CHANGE `verguetung` `verguetung` INT(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten des Mandates, z.B. Entschädigung für Beiratsfunktion.';
ALTER TABLE `mandat_jahr_log` CHANGE `verguetung` `verguetung` INT(11) NOT NULL COMMENT 'Jährliche Vergütung CHF für Tätigkeiten des Mandates, z.B. Entschädigung für Beiratsfunktion.';


-- 31.01.2016

-- Fix Zutrittsberechtigung.bis nicht à jour
-- SELECT parlamentarier_id, p.anzeige_name as parlamentarier, p.rat, z.zutrittsberechtigung_id, z.id as person_id, z.anzeige_name as person, z.bis, p.im_rat_bis, z.created_visa, z.created_date FROM `v_zutrittsberechtigung` z, v_parlamentarier p WHERE z.parlamentarier_id=p.id AND z.bis IS NULL AND p.im_rat_bis IS NOT NULL ORDER BY z.zutrittsberechtigung_id;
UPDATE parlamentarier SET updated_visa='roland', updated_date=NOW() WHERE id IN (SELECT /*z.zutrittsberechtigung_id, z.id,*/ parlamentarier_id /*, z.bis, p.im_rat_bis*/ FROM `v_zutrittsberechtigung` z, v_parlamentarier p WHERE z.parlamentarier_id=p.id AND z.bis IS NULL AND p.im_rat_bis IS NOT NULL);

-- Update zb.kommissionen and person.kommissionen nicht à jour
-- SELECT zutrittsberechtigung_id, id, bis, parlamentarier_kommissionen_zutrittsberechtigung, parlamentarier_id, parlamentarier_kommissionen FROM `v_zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND (parlamentarier_kommissionen IS NULL OR parlamentarier_kommissionen_zutrittsberechtigung IS NULL);
UPDATE zutrittsberechtigung SET updated_visa='roland', updated_date=NOW() WHERE id IN (SELECT zutrittsberechtigung_id/*, id, parlamentarier_kommissionen_zutrittsberechtigung, parlamentarier_id, parlamentarier_kommissionen*/ FROM `v_zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND (parlamentarier_kommissionen IS NULL OR parlamentarier_kommissionen_zutrittsberechtigung IS NULL));

-- Parlamentarier Kommissionen not up-to-date
-- SELECT id, p.anzeige_name as parlamentarier, p.rat, p.kommissionen, p.kommissionen_abkuerzung FROM v_parlamentarier p WHERE p.kommissionen <> p.kommissionen_abkuerzung OR (p.kommissionen IS NULL AND p.kommissionen_abkuerzung IS NOT NULL) OR (p.kommissionen IS NOT NULL AND p.kommissionen_abkuerzung IS NULL);
-- Fill parlamentarier.kommissionen on change
SET @disable_table_logging = 1;
UPDATE `parlamentarier` p
  SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
    p.updated_date = NOW(),
    p.updated_visa = CONCAT('roland', '*')
  WHERE p.id IN (SELECT v.id/*, p.anzeige_name as parlamentarier, p.rat, p.kommissionen, p.kommissionen_abkuerzung*/ FROM v_parlamentarier v WHERE v.kommissionen <> v.kommissionen_abkuerzung OR (v.kommissionen IS NULL AND v.kommissionen_abkuerzung IS NOT NULL) OR (v.kommissionen IS NOT NULL AND v.kommissionen_abkuerzung IS NULL));
UPDATE `zutrittsberechtigung` p
  SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
    p.updated_date = NOW(),
    p.updated_visa = CONCAT('roland', '*')
  WHERE p.parlamentarier_id IN (SELECT v.id/*, p.anzeige_name as parlamentarier, p.rat, p.kommissionen, p.kommissionen_abkuerzung*/ FROM v_parlamentarier v WHERE v.kommissionen <> v.kommissionen_abkuerzung OR (v.kommissionen IS NULL AND v.kommissionen_abkuerzung IS NOT NULL) OR (v.kommissionen IS NOT NULL AND v.kommissionen_abkuerzung IS NULL));
SET @disable_table_logging = NULL;

-- 06.04.2016

ALTER TABLE parlamentarier
ADD `parlament_interessenbindungen_updated` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindungen von ws.parlament.ch zu letzt aktualisiert wurden.' AFTER parlament_interessenbindungen;

ALTER TABLE parlamentarier_log
ADD `parlament_interessenbindungen_updated` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Interessenbindungen von ws.parlament.ch zu letzt aktualisiert wurden.' AFTER parlament_interessenbindungen;

-- 07.04.2016

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT lg1.updated_date /*, lg1.id, lg1.nachname, lg1.log_id, lg2.log_id, (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg2.log_id + 1 AND lg1.log_id - 1 AND lgi.id = lg1.id LIMIT 1), lg1.updated_date, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND lg1.log_id > lg2.log_id AND p.id = lg1.id AND NOT EXISTS (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg2.log_id + 1 AND lg1.log_id -1 AND lgi.id = lg1.id) ORDER BY lg1.log_id DESC LIMIT 1);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

-- 24.05.2016

ALTER TABLE `interessenbindung_jahr`
ADD `autorisiert_visa` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.' AFTER `kontrolliert_datum`,
ADD `autorisiert_datum` DATE NULL DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen vom Parlamentarier autorisiert wurden.' AFTER `autorisiert_visa`;

ALTER TABLE `interessenbindung_jahr_log`
ADD `autorisiert_visa` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.' AFTER `kontrolliert_datum`,
ADD `autorisiert_datum` DATE NULL DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass die Interessenbindungen vom Parlamentarier autorisiert wurden.' AFTER `autorisiert_visa`;

ALTER TABLE `mandat_jahr`
ADD `autorisiert_visa` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.' AFTER `kontrolliert_datum`,
ADD `autorisiert_datum` DATE NULL DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass das Mandat von der Person autorisiert wurden.' AFTER `autorisiert_visa`;

ALTER TABLE `mandat_jahr_log`
ADD `autorisiert_visa` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Autorisiert durch. Sonstige Angaben als Notiz erfassen.' AFTER `kontrolliert_datum`,
ADD `autorisiert_datum` DATE NULL DEFAULT NULL COMMENT 'Autorisiert am. Leer/NULL bedeutet noch nicht autorisiert. Ein Datum bedeutet, dass das Mandat von der Person autorisiert wurden.' AFTER `autorisiert_visa`;

-- 24.03.2017

ALTER TABLE person
ADD `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Person durch einen Import zu letzt aktualisiert wurde.' AFTER telephon_2;

ALTER TABLE person_log
ADD `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Person durch einen Import zu letzt aktualisiert wurde.' AFTER telephon_2;

ALTER TABLE zutrittsberechtigung
ADD `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Zutrittsberechtigung durch einen Import zu letzt aktualisiert wurde.' AFTER bis;

ALTER TABLE zutrittsberechtigung_log
ADD `updated_by_import` timestamp NULL DEFAULT NULL COMMENT 'Datum, wann die Zutrittsberechtigung durch einen Import zu letzt aktualisiert wurde.' AFTER bis;


-- 10.04.2017

-- Die SP heisst im PDF auf Französisch abgekürzt PSS und nicht PS, drum:
UPDATE `partei`
SET `abkuerzung_fr` = 'PSS'
WHERE `id` = 3;

-- Die MCG (https://de.wikipedia.org/wiki/Mouvement_citoyens_genevois) heisst in der datenbank MCR. Sie nennt sich scheinbar selbst aber MCG, darum:
UPDATE `partei`
SET `abkuerzung` = 'MCG'
WHERE `id` = 9;

-- Die PdA heisst auf französisch PdT, drum:
UPDATE `partei`
SET `abkuerzung_fr` = 'PdT'
WHERE `id` = 13;

-- Der Mensch heisst Bernhard, nicht Bernard (Siehe http://www.sgv-usam.ch/verband/geschaeftsstelle/team.html)
UPDATE `person`
SET `vorname` = 'Bernhard'
WHERE `id` = 207;

-- Der Mensch wird im PDF als "Ruedi" ausgewiesen, drum kommt hier analog zu anderen der Spitzname dazu (vorher war nur "Rudolf")
UPDATE `person`
SET `vorname` = 'Rudolf "Ruedi"'
WHERE `id` = 121;

-- Der Mensch wird im PDF als "Tim" ausgewiesen, drum kommt hier analog zu anderen der Spitzname dazu (vorher war nur "Timotheos")
UPDATE `person`
SET `vorname` = 'Timotheos "Tim"'
WHERE `id` = 269;

-- 01.12.2017 Yazd

UPDATE in_kommission SET bis=STR_TO_DATE('28.02.2017','%d.%m.%Y'), updated_visa='roland', updated_date=STR_TO_DATE('01.12.2017 14:40:03','%d.%m.%Y %T'), notizen=CONCAT_WS('\n\n', '01.12.2017/rkurmann: Korrigiere duplicate, welches Inkonsistenzen verursacht',`notizen`) WHERE id=1143;

-- 01.07.2018 Osaka

-- MySQL 5.6 timestamp change

ALTER TABLE country
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

ALTER TABLE mil_grad
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE mil_grad_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';;

ALTER TABLE branche
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE branche_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE interessenbindung
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE interessenbindung_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE interessenbindung_jahr
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE interessenbindung_jahr_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE interessengruppe
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE interessengruppe_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE in_kommission
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE in_kommission_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE kommission
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE kommission_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE mandat
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE mandat_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE mandat_jahr
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE mandat_jahr_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE organisation
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE organisation_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE organisation_beziehung
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE organisation_beziehung_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE organisation_jahr
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE organisation_jahr_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE parlamentarier
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE parlamentarier_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE partei
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE partei_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE fraktion
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE fraktion_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE rat
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE rat_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE kanton
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE kanton_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE kanton_jahr
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE kanton_jahr_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE zutrittsberechtigung
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE zutrittsberechtigung_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE person
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE person_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE parlamentarier_anhang
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE parlamentarier_anhang_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE organisation_anhang
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE organisation_anhang_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE person_anhang
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE person_anhang_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE settings
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE settings_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';
ALTER TABLE settings_category
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';
ALTER TABLE settings_category_log MODIFY COLUMN `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion';

-- 27.06.2018 Osaka

ALTER TABLE `organisation`
MODIFY `uid` VARCHAR( 15 ) NULL DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999';

ALTER TABLE `organisation_log`
MODIFY `uid` VARCHAR( 15 ) NULL DEFAULT NULL COMMENT 'UID des Handelsregisters; Schweizweit eindeutige ID (http://www.bfs.admin.ch/bfs/portal/de/index/themen/00/05/blank/03/02.html); Format: CHE-999.999.999';

ALTER TABLE `kommission`
MODIFY `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.';

ALTER TABLE `kommission_log`
MODIFY `art` enum('legislativkommission','aufsichtskommission','parlam verwaltungskontrolle','weitere kommission','delegation im weiteren sinne') DEFAULT NULL COMMENT 'Art der Kommission gemäss Einteilung auf Parlament.ch. Achtung für Delegationen im engeren Sinne (= Subkommissionen) sollte die Art der Mutterkommission gewählt werden, z.B. GPDel ist eine Subkommission der GPK und gehört somit zu den Aufsichtskommissionen.';

ALTER TABLE interessenraum
    MODIFY COLUMN `created_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
    MODIFY COLUMN `updated_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am';

ALTER TABLE branche
    DROP KEY branche_name_unique,
    ADD UNIQUE KEY `branche_name_unique` (`name`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE fraktion
    DROP KEY `fraktion_abkuerzung_unique`,
    ADD UNIQUE KEY `fraktion_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE in_kommission
    DROP KEY `in_kommission_parlamentarier_kommission_funktion_unique`,
    ADD UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`,`bis`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE interessenbindung
    DROP KEY `interessenbindung_art_parlamentarier_organisation_unique`,
    ADD UNIQUE KEY `interessenbindung_art_parlamentarier_organisation_unique` (`art`,`parlamentarier_id`,`organisation_id`,`bis`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE interessengruppe
    DROP KEY `interessengruppe_name_unique`,
    ADD UNIQUE KEY `interessengruppe_name_unique` (`name`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE kanton_jahr
    DROP KEY `idx_kanton_jahr_unique`,
    ADD UNIQUE KEY `idx_kanton_jahr_unique` (`kanton_id`,`jahr`) COMMENT 'Fachlicher unique constraint';

ALTER TABLE `kommission`
    DROP KEY `kommission_abkuerzung_unique`,
    DROP KEY `kommission_name_unique`,
    ADD UNIQUE KEY `kommission_abkuerzung_unique` (`abkuerzung`) COMMENT 'Fachlicher unique constraint',
    ADD UNIQUE KEY `kommission_name_unique` (`name`) COMMENT 'Fachlicher unique constraint';

-- TODO SET all prod and local the same explicit DEFAULT valus

-- 18.07.2018 Osaka

-- add new fields organisation.sekretariat, organisation.update_by_import, organisation.abkuerzung_it, alias_namen_it,  organisation_beziehung.beschreibung_de, organisation_beziehung.beschreibung_fr, interessenbindung.update_by_import, organisation.beschreibung_fr, mandat.beschreibung_fr to forms; reorder organisation

-- organisation.abkuerzung_it, alias_namen_it hinzufügen und change position of sekretariat
ALTER TABLE `organisation`
  ADD `abkuerzung_it` varchar(20) NULL DEFAULT NULL COMMENT 'Italienische Abkürzung der Organisation' AFTER `alias_namen_fr`,
  ADD `alias_namen_it` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Italienischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternativen Namen für die Organisation; bei der Suche wird für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `abkuerzung_it`,
  CHANGE `sekretariat` `sekretariat` VARCHAR(500) NULL COMMENT 'Für parlamentarische Gruppen: Ansprechsperson, Adresse, Telephonnummer, usw. des Sekretariats der parlamentarischen Gruppen (wird importiert)' AFTER `beschreibung_fr`;

ALTER TABLE `organisation_log`
  ADD `abkuerzung_it` varchar(20) NULL DEFAULT NULL COMMENT 'Italienische Abkürzung der Organisation' AFTER `alias_namen_fr`,
  ADD `alias_namen_it` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Italienischer Aliasnamen: Strichpunkt-getrennte Aufzählung von alternativen Namen für die Organisation; bei der Suche wird für ein einfacheres Finden auch in den Alias-Namen gesucht.' AFTER `abkuerzung_it`,
  CHANGE `sekretariat` `sekretariat` VARCHAR(500) NULL COMMENT 'Für parlamentarische Gruppen: Ansprechsperson, Adresse, Telephonnummer, usw. des Sekretariats der parlamentarischen Gruppen (wird importiert)' AFTER `beschreibung_fr`;

-- Add fields organisation_beziehung.beschreibung_de, organisation_beziehung.beschreibung_fr
ALTER TABLE `organisation_beziehung`
  ADD `beschreibung` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER `art`,
  ADD `beschreibung_fr` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Französische Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER `beschreibung`;

ALTER TABLE `organisation_beziehung_log`
  ADD `beschreibung` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER `art`,
  ADD `beschreibung_fr` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Französische Bezeichung der Organisationsbeziehung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER `beschreibung`;

-- Add fields interessenbindung.beschreibung_fr
ALTER TABLE `interessenbindung`
  ADD `beschreibung_fr` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Französische Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER beschreibung;

ALTER TABLE `interessenbindung_log`
  ADD `beschreibung_fr` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Französische Bezeichung der Interessenbindung. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER beschreibung;

-- Add mandat.beschreibung_fr
ALTER TABLE `mandat`
  ADD `beschreibung_fr` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Französische Bezeichung des Mandates. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER beschreibung;

ALTER TABLE `mandat_log`
  ADD `beschreibung_fr` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Französische Bezeichung des Mandates. Möglichst kurz. Wird nicht ausgewertet, jedoch angezeigt.' AFTER beschreibung;

-- 25.09.2018 Kagoshima

ALTER TABLE `organisation` CHANGE `adresse_zusatz` `adresse_zusatz` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach';
ALTER TABLE `organisation_log` CHANGE `adresse_zusatz` `adresse_zusatz` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Adressezusatz, z.B. Postfach';

-- 04.07.2019/rkurmann Update user table

ALTER TABLE user
  ADD mobile VARCHAR(20) NULL DEFAULT NULL COMMENT 'Mobile Nr zum Passwortsenden über WhatsApp' AFTER `email`,
  ADD token VARCHAR(255) NULL DEFAULT NULL COMMENT 'Internal data for verification and password recovering' AFTER `email`,
  ADD status INTEGER NOT NULL DEFAULT 0 COMMENT 'Status of the user. Possible values are as follows: 0 - registered user, 1 - user awaiting verification, 2 - user requested password reset' AFTER `token`;

-- 04.07.2019/rkurmann New fields

-- ALTER TABLE `parlamentarier` DROP `parlament_interessenbindungen_json`;
-- ALTER TABLE `parlamentarier_log` DROP `parlament_interessenbindungen_json`;

ALTER TABLE parlamentarier
  ADD parlament_interessenbindungen_json JSON NULL DEFAULT NULL COMMENT 'Importierte Interessenbindungen von ws.parlament.ch als JSON. Rechtsformen: -, AG, Anst., EG, EidgKomm, Gen., GmbH, KollG, Komm., Körp., Stift., Ve., öffStift; Gremien: -, A, AufR., Bei., D, GL, GL, V, GV, Pat., Sr., V, VR, Vw., ZA, ZV; Funktionen: -, A, AufR., Bei., D, GL, GL, V, GV, Pat., Sr., V, VR, Vw., ZA, ZV' AFTER `parlament_interessenbindungen`;
ALTER TABLE parlamentarier_log
  ADD parlament_interessenbindungen_json JSON NULL DEFAULT NULL COMMENT 'Importierte Interessenbindungen von ws.parlament.ch als JSON. Rechtsformen: -, AG, Anst., EG, EidgKomm, Gen., GmbH, KollG, Komm., Körp., Stift., Ve., öffStift; Gremien: -, A, AufR., Bei., D, GL, GL, V, GV, Pat., Sr., V, VR, Vw., ZA, ZV; Funktionen: -, A, AufR., Bei., D, GL, GL, V, GV, Pat., Sr., V, VR, Vw., ZA, ZV' AFTER `parlament_interessenbindungen`;

-- 17.07.2019, rkurmann: remove indexes on _log tables

/*
SELECT table_name AS `Table`,
       index_name AS `Index`,
       GROUP_CONCAT(column_name ORDER BY seq_in_index) AS `Columns`,
       CONCAT('ALTER TABLE ', table_name, ' DROP INDEX ', index_name, ';') AS STMT
FROM information_schema.statistics
WHERE table_schema = 'lobbywatchtest' AND table_name like '%_log' AND NOT (column_name='log_id' OR column_name='snapshot_id')
GROUP BY 1,2;
*/

-- remove indexes on _log tables
ALTER TABLE branche_log DROP INDEX kommission2_id;
ALTER TABLE branche_log DROP INDEX kommission_id;
ALTER TABLE interessenbindung_log DROP INDEX idx_lobbyorg;
ALTER TABLE interessenbindung_log DROP INDEX idx_parlam;
ALTER TABLE interessengruppe_log DROP INDEX idx_lobbytyp;
ALTER TABLE in_kommission_log DROP INDEX kommissions_id;
ALTER TABLE in_kommission_log DROP INDEX parlamentarier_id;
ALTER TABLE kommission_log DROP INDEX rat_id;
ALTER TABLE kommission_log DROP INDEX zugehoerige_kommission;
ALTER TABLE kommission_log DROP INDEX zweitrat_kommission_id;
ALTER TABLE mandat_log DROP INDEX organisations_id;
ALTER TABLE mandat_log DROP INDEX zutrittsberechtigung_id;
ALTER TABLE organisation_anhang_log DROP INDEX organisation_id;
ALTER TABLE organisation_beziehung_log DROP INDEX organisation_id;
ALTER TABLE organisation_beziehung_log DROP INDEX ziel_organisation_id;
ALTER TABLE organisation_jahr_log DROP INDEX organisation_id;
ALTER TABLE organisation_log DROP INDEX idx_lobbygroup;
ALTER TABLE organisation_log DROP INDEX idx_lobbytyp;
ALTER TABLE organisation_log DROP INDEX interessengruppe2_id;
ALTER TABLE organisation_log DROP INDEX interessengruppe3_id;
ALTER TABLE parlamentarier_anhang_log DROP INDEX parlamentarier_id;
ALTER TABLE parlamentarier_log DROP INDEX beruf_branche_id;
ALTER TABLE parlamentarier_log DROP INDEX fraktion_id;
ALTER TABLE parlamentarier_log DROP INDEX idx_partei;
ALTER TABLE parlamentarier_log DROP INDEX militaerischer_grad;
ALTER TABLE partei_log DROP INDEX fraktion_id;
ALTER TABLE person_anhang_log DROP INDEX zutrittsberechtigung_id;
ALTER TABLE person_log DROP INDEX idx_lobbygroup;
ALTER TABLE person_log DROP INDEX partei;
ALTER TABLE translation_source_log DROP INDEX source_key;
ALTER TABLE translation_target_log DROP INDEX plural_translation_source_id;
ALTER TABLE translation_target_log DROP INDEX translation_source_id;

ALTER TABLE `parlamentarier_log`
  -- DROP INDEX `idx_id`,
  ADD INDEX `idx_id` (`id`) USING BTREE COMMENT 'Index on old primary key';

-- 22.07.2019/RKU increase kommissionen field

-- Fix '0000-00-00' for column 'im_rat_bis'
-- ERROR 1292 (22007) at line 6: Incorrect date value: '0000-00-00' for column 'im_rat_bis' at row 5778
-- SELECT id, nachname, im_rat_bis, updated_date FROM `parlamentarier_log` WHERE `im_rat_bis` = '0000-00-00';

show variables like 'sql_mode';

-- Remove temporary NO_ZERO_IN_DATE,NO_ZERO_DATE
SET SESSION sql_mode="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION";

UPDATE `parlamentarier_log` SET `im_rat_bis` = NULL WHERE `im_rat_bis` = '0000-00-00';
UPDATE `zutrittsberechtigung_log` SET `bis` = NULL WHERE `bis` = '0000-00-00';

SET SESSION sql_mode="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION";

show variables like 'sql_mode';

ALTER TABLE `parlamentarier`
CHANGE `kommissionen` `kommissionen` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])';

ALTER TABLE `parlamentarier_log`
CHANGE `kommissionen` `kommissionen` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des Parlamentariers (automatisch erzeugt [in_Kommission Trigger])';

ALTER TABLE `zutrittsberechtigung`
CHANGE `parlamentarier_kommissionen` `parlamentarier_kommissionen` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])';

ALTER TABLE `zutrittsberechtigung_log`
CHANGE `parlamentarier_kommissionen` `parlamentarier_kommissionen` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/zutrittsberechtigung Trigger])';

ALTER TABLE `person`
CHANGE `parlamentarier_kommissionen` `parlamentarier_kommissionen` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/person Trigger])';

ALTER TABLE `person_log`
CHANGE `parlamentarier_kommissionen` `parlamentarier_kommissionen` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Abkürzungen der Kommissionen des zugehörigen Parlamentariers (automatisch erzeugt [in_Kommission/person Trigger])';

-- 09.02.2020
-- Clean up signor/signora

SET @disable_triggers = 1;
delete from zutrittsberechtigung where person_id IN (select id from person where nachname="signor" OR nachname="signora");
delete from person where nachname="signor" OR nachname="signora";
SET @disable_triggers = 0;

-- 25.04.2020
-- Mirgrate bezahlendes Mitglied from verguetung 0 to -1

SELECT count(*) FROM `interessenbindung_jahr` WHERE `beschreibung`='Bezahlendes Mitglied' and `verguetung`=0;

UPDATE `interessenbindung_jahr` SET `verguetung`=-1 WHERE `beschreibung`='Bezahlendes Mitglied' AND `verguetung`=0;

-- 22.05.2020
-- Fix typos in DB comments

ALTER TABLE `in_kommission`
  CHANGE `parlament_committee_function_name` `parlament_committee_function_name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'committeeFunctionName von ws.parlament.ch';
ALTER TABLE `in_kommission_log`
  CHANGE `parlament_committee_function_name` `parlament_committee_function_name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'committeeFunctionName von ws.parlament.ch';

ALTER TABLE `interessenbindung`
  CHANGE `quelle_url_gueltig` `quelle_url_gueltig` TINYINT(1) NULL DEFAULT NULL COMMENT 'Ist die Quell-URL noch gültig? Funktioniert er noch?',
  CHANGE `funktion_im_gremium` `funktion_im_gremium` ENUM('praesident','vizepraesident','mitglied') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwaltungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  CHANGE `behoerden_vertreter` `behoerden_vertreter` ENUM('J','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Entstand diese Interessenbindung als Behördenvertreter von Amtes wegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von Amtes wegen Einsitz nimmt.';
ALTER TABLE `interessenbindung_log`
  CHANGE `quelle_url_gueltig` `quelle_url_gueltig` TINYINT(1) NULL DEFAULT NULL COMMENT 'Ist die Quell-URL noch gültig? Funktioniert er noch?',
  CHANGE `funktion_im_gremium` `funktion_im_gremium` ENUM('praesident','vizepraesident','mitglied') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Funktion innerhalb des Gremiums, z.B. Präsident in einem Vorstand einer AG entspricht einem Verwaltungsratspräsidenten, Präsident einer Geschäftsleitung entspricht einem CEO.',
  CHANGE `behoerden_vertreter` `behoerden_vertreter` ENUM('J','N') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Entstand diese Interessenbindung als Behördenvertreter von Amtes wegen? Beispielsweise weil ein Regierungsrat in einem Verwaltungsrat von Amtes wegen Einsitz nimmt.';

ALTER TABLE `mandat`
  CHANGE `beschreibung` `beschreibung` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgewertet, jedoch in den Resultaten angezeigt.',
  CHANGE `quelle_url_gueltig` `quelle_url_gueltig` TINYINT(1) NULL DEFAULT NULL COMMENT 'Ist die Quell-URL noch gültig? Funktioniert er noch?';
ALTER TABLE `mandat_log`
  CHANGE `beschreibung` `beschreibung` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Umschreibung des Mandates. Beschreibung wird nicht ausgewertet, jedoch in den Resultaten angezeigt.',
  CHANGE `quelle_url_gueltig` `quelle_url_gueltig` TINYINT(1) NULL DEFAULT NULL COMMENT 'Ist die Quell-URL noch gültig? Funktioniert er noch?';

ALTER TABLE `parlamentarier`
  CHANGE `wikipedia` `wikipedia` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Link zum Wikipedia-Eintrag des Parlamentariers';
ALTER TABLE `parlamentarier_log`
  CHANGE `wikipedia` `wikipedia` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Link zum Wikipedia-Eintrag des Parlamentariers';

ALTER TABLE `person`
  CHANGE `beruf_interessengruppe_id` `beruf_interessengruppe_id` INT(11) NULL DEFAULT NULL COMMENT 'Fremdschlüssel zur Interessengruppe für den Beruf';
ALTER TABLE `person_log`
  CHANGE `beruf_interessengruppe_id` `beruf_interessengruppe_id` INT(11) NULL DEFAULT NULL COMMENT 'Fremdschlüssel zur Interessengruppe für den Beruf';

-- 05.07.2020 pg2020 relax organisation unique name, include rechtsform

ALTER TABLE `organisation`
  DROP INDEX `organisation_name_de_unique`,
  ADD UNIQUE `organisation_name_de_unique` (`name_de`, `rechtsform`) USING BTREE COMMENT 'Fachlicher unique constraint',
  DROP INDEX `organisation_name_fr_unique`,
  ADD UNIQUE `organisation_name_fr_unique` (`name_fr`, `rechtsform`) USING BTREE COMMENT 'Fachlicher unique constraint',
  DROP INDEX `organisation_name_it_unique`,
  ADD UNIQUE `organisation_name_it_unique` (`name_it`, `rechtsform`) USING BTREE COMMENT 'Fachlicher unique constraint';

-- 17.07.2020

SELECT id, nachname, vorname, zutrittsberechtigung_von FROM person
WHERE (zutrittsberechtigung_von <> (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id))
OR (zutrittsberechtigung_von IS NULL AND (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id) IS NOT NULL)
OR (zutrittsberechtigung_von IS NOT NULL AND (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id) IS NULL);

-- force update of person trigger fields which are wrong
SET @disable_triggers = 1;
UPDATE person
SET zutrittsberechtigung_von = (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id)
WHERE (zutrittsberechtigung_von <> (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id))
OR (zutrittsberechtigung_von IS NULL AND (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id) IS NOT NULL)
OR (zutrittsberechtigung_von IS NOT NULL AND (SELECT anzeige_name FROM v_parlamentarier_simple parlamentarier INNER JOIN zutrittsberechtigung ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND (zutrittsberechtigung.bis IS NULL OR zutrittsberechtigung.bis > NOW()) WHERE person.id = zutrittsberechtigung.person_id) IS NULL);
SET @disable_triggers = NULL;

-- 07.11.2020 Lobbypedia

-- Table wissensartikelzieltabelle

ALTER TABLE wissensartikel_link DROP FOREIGN KEY fk_target_table_name;
DROP TABLE IF EXISTS `wissensartikelzieltabelle`;
CREATE TABLE `wissensartikelzieltabelle` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `table_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Technischer Name der Zieltabelle, die mit dem Lobbypedia-Artikel verknüpft werden kann.',
  `name_de` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Deutscher Name der Zieltabelle, die mit dem Lobbypedia-Artikel verknüpft werden kann.',
  `name_fr` varchar(50) COLLATE utf8mb4_unicode_ci COMMENT 'Französischer Name der Zieltabelle, die mit dem Lobbypedia-Artikel verknüpft werden kann.',
  `notizen` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  UNIQUE KEY `target_table_name_unique` (`table_name`) COMMENT 'Fachlicher unique constraint'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Verknüpfung von CMS Lobbypedia-Artikeln mit DB-Datensätzen';

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

-- Table wissensartikel_link
DROP TABLE IF EXISTS `wissensartikel_link`;
CREATE TABLE `wissensartikel_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Schlüssel',
  `node_id` int(10) unsigned NOT NULL COMMENT 'CMS Drupal 7 node id (nid) des Lobbypedia-Artikels',
  `target_table_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Zieltabelle, die mit dem Lobbypedia-Artikel verknüpft wird.',
  `target_id` int(11) NOT NULL COMMENT 'id in der Zieltabelle',
  `target_table_name_with_id` varchar(52) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Zieltabelle#id, ist die Zusammensetzung von Zieltablle und id mit einem Hash (#) getrennt. Dieses Feld ist aus technischen Gründen nötig für den PHP Formulargenerator.',
  `notizen` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Interne Notizen zu diesem Eintrag. Einträge am besten mit Datum und Visa versehen.',
  `eingabe_abgeschlossen_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe abgeschlossen hat.',
  `eingabe_abgeschlossen_datum` timestamp NULL DEFAULT NULL COMMENT 'Die Eingabe ist für den Ersteller der Einträge abgeschlossen und bereit für die Kontrolle. (Leer/NULL bedeutet, dass die Eingabe noch im Gange ist.)',
  `kontrolliert_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kürzel der Person, welche die Eingabe kontrolliert hat.',
  `kontrolliert_datum` timestamp NULL DEFAULT NULL COMMENT 'Der Eintrag wurde durch eine zweite Person am angegebenen Datum kontrolliert. (Leer/NULL bedeutet noch nicht kontrolliert.)',
  `freigabe_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Freigabe von wem? (Freigabe = Daten sind fertig)',
  `freigabe_datum` timestamp NULL DEFAULT NULL COMMENT 'Freigabedatum (Freigabe = Daten sind fertig)',
  `created_visa` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Datensatz erstellt von',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Erstellt am',
  `updated_visa` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Abgäendert von',
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Abgeändert am',
  PRIMARY KEY (`id`),
  -- UNIQUE KEY `in_kommission_parlamentarier_kommission_funktion_unique` (`funktion`,`parlamentarier_id`,`kommission_id`,`bis`) COMMENT 'Fachlicher unique constraint',
  KEY `idx_node_id` (`node_id`),
  KEY `idx_target` (`target_table_name`,`target_id`),
  CONSTRAINT `fk_node_id` FOREIGN KEY (`node_id`) REFERENCES `lobbywat_d7lobbywatch`.`dlw_node` (`nid`),
  CONSTRAINT `fk_target_table_name` FOREIGN KEY (`target_table_name`) REFERENCES `wissensartikelzieltabelle` (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Verknüpfung von CMS Lobbypedia-Artikeln mit DB-Datensätzen';

-- Table wissensartikel_link_log
DROP TABLE IF EXISTS `wissensartikel_link_log`;
CREATE TABLE IF NOT EXISTS `wissensartikel_link_log` LIKE `wissensartikel_link`;
ALTER TABLE `wissensartikel_link_log`
  CHANGE `id` `id` INT( 11 ) NOT NULL COMMENT 'Technischer Schlüssel der Live-Daten',
  CHANGE `created_date` `created_date` timestamp NULL DEFAULT NULL COMMENT 'Erstellt am',
  CHANGE `updated_date` `updated_date` timestamp NULL DEFAULT NULL COMMENT 'Abgeändert am',
  DROP INDEX `idx_node_id`,
  DROP INDEX `idx_target`,
  DROP PRIMARY KEY,
  ADD `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Technischer Log-Schlüssel',
  ADD PRIMARY KEY (`log_id`),
  ADD `action` enum('insert','update','delete','snapshot') NOT NULL COMMENT 'Aktionstyp',
  ADD `state` varchar(20) DEFAULT NULL COMMENT 'Status der Aktion',
  ADD `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datum der Aktion',
  ADD `snapshot_id` int(11) DEFAULT NULL COMMENT 'Fremdschlüssel zu einem Snapshot',
  ADD CONSTRAINT `fk_wissensartikel_link_log_snapshot_id` FOREIGN KEY (`snapshot_id`) REFERENCES `snapshot` (`id`);

-- 'organisation','parlamentarier','interessenbindung','person','mandat','interessengruppe','branche','partei','fraktion','kommission'
INSERT INTO `wissensartikelzieltabelle` (`table_name`, `name_de`, `name_fr`, `created_visa`, `updated_visa`) VALUES
('organisation', 'Organisation', NULL, 'roland', 'roland'),
('parlamentarier', 'Parlamentarier', NULL, 'roland', 'roland'),
-- ('interessenbindung', 'Interessenbindung', NULL, 'roland', 'roland'),
('person', 'Person', NULL, 'roland', 'roland'),
-- ('mandat', 'Mandat', NULL, 'roland', 'roland'),
('interessengruppe', 'Lobbygruppe', NULL, 'roland', 'roland'),
('branche', 'Branche', NULL, 'roland', 'roland'),
('partei', 'Partei', NULL, 'roland', 'roland'),
('fraktion', 'Fraktion', NULL, 'roland', 'roland'),
('kommission', 'Kommission', NULL, 'roland', 'roland');

-- 27.11.2020 add parlamentarier_transparenz.in_liste

ALTER TABLE `parlamentarier_transparenz`
ADD `in_liste` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Ist dieser Eintrag in der Transparenliste mit dem angegebenen Stichdatum enthalten? (Dieses Feld verhindert Transparenzlisteneinträge löschen zu müssen, wenn diese nicht in der Transparenzliste enthalten sind.)' AFTER `stichdatum`;

ALTER TABLE `parlamentarier_transparenz_log`
ADD `in_liste` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Ist dieser Eintrag in der Transparenliste mit dem angegebenen Stichdatum enthalten? (Dieses Feld verhindert Transparenzlisteneinträge löschen zu müssen, wenn diese nicht in der Transparenzliste enthalten sind.)' AFTER `stichdatum`;

-- 28.11.2020 enhance parlamentarier_transparenz.verguetung_transparent comment

ALTER TABLE `parlamentarier_transparenz`
CHANGE `verguetung_transparent` `verguetung_transparent` ENUM('ja','nein','teilweise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Ist der dieser Parlamentarier transparent bzgl seinen Vergütungen? ja, nein, teilweise (Leer/NULL bedeutet noch nicht eingetragen): NEIN=Minimaltransparenz (= gesetzliches Minimum, bezahlt/ehrenamtlich); TEILWEISE=teilweise transparent (=gesetzl Minimum plus einzelne Entschädigungen als Betrag offengelegt); JA=transparent (=gesetzl. Minimum plus alles Entschädigungen offengelegt; Exkl. Entschädigung aus Hauptberuf)';

ALTER TABLE `parlamentarier_transparenz_log`
CHANGE `verguetung_transparent` `verguetung_transparent` ENUM('ja','nein','teilweise') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Ist der dieser Parlamentarier transparent bzgl seinen Vergütungen? ja, nein, teilweise (Leer/NULL bedeutet noch nicht eingetragen): NEIN=Minimaltransparenz (= gesetzliches Minimum, bezahlt/ehrenamtlich); TEILWEISE=teilweise transparent (=gesetzl Minimum plus einzelne Entschädigungen als Betrag offengelegt); JA=transparent (=gesetzl. Minimum plus alles Entschädigungen offengelegt; Exkl. Entschädigung aus Hauptberuf)';

-- 28.11.2020 Add parlamentarier.parlament_beruf, parlament_arbeitgeber, parlament_jobtitel, parlament_beruf_modified

ALTER TABLE `parlamentarier`
ADD `parlament_beruf_json` JSON NULL DEFAULT NULL COMMENT 'Importierter Beruf des Parlamentariers: Beruf, Arbeitgeber, Jobtitel/Funktion, von, bis (von parlament.ch)' AFTER `parlament_number`;


ALTER TABLE `parlamentarier_log`
ADD `parlament_beruf_json` JSON NULL DEFAULT NULL COMMENT 'Importierter Beruf des Parlamentariers: Beruf, Arbeitgeber, Jobtitel/Funktion, von, bis (von parlament.ch)' AFTER `parlament_number`;

-- 19.12.2020 organisation.inaktiv

ALTER TABLE `organisation`
  ADD `in_handelsregister` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Ist die Organisation im Handelsregister (Zefix) eingetragen?' AFTER `uid`,
  ADD `inaktiv` BOOLEAN NULL DEFAULT FALSE COMMENT 'Gibt es die Organisation noch?' AFTER `in_handelsregister`;

ALTER TABLE `organisation_log`
  ADD `in_handelsregister` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Ist die Organisation im Handelsregister (Zefix) eingetragen?' AFTER `uid`,
  ADD `inaktiv` BOOLEAN NULL DEFAULT FALSE COMMENT 'Gibt es die Organisation noch?' AFTER `in_handelsregister`;

-- 29.12.2020

-- https://dba.stackexchange.com/questions/156498/mysql-unique-index-with-nulls-actual-solution-anyone
-- https://lobbywatch.slack.com/archives/CLXU2R9V0/p1606640182011100

-- interessenbindung
ALTER TABLE interessenbindung
  DROP INDEX `interessenbindung_art_parlamentarier_organisation_unique`,
  ADD `interessenbindung_parlamentarier_organisation_art_unique` VARCHAR(45) GENERATED ALWAYS AS (CONCAT_WS('_', `parlamentarier_id`, `organisation_id`, `art`, IFNULL(`bis`, '9999-12-31'))) VIRTUAL NOT NULL COMMENT 'Kombination aus parlamentarier_id, organisation_id, art und bis muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE interessenbindung_log
  ADD `interessenbindung_parlamentarier_organisation_art_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- mandat
ALTER TABLE mandat
  DROP INDEX `mandat_person_organisation_art_unique`,
  ADD `mandat_person_organisation_art_unique` VARCHAR(45) GENERATED ALWAYS AS (CONCAT_WS('_', `person_id`, `organisation_id`, `art`, IFNULL(`bis`, '9999-12-31'))) VIRTUAL NOT NULL COMMENT 'Kombination aus person_id, organisation_id, art und bis muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE mandat_log
  ADD `mandat_person_organisation_art_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- organisation_beziehung
ALTER TABLE organisation_beziehung
  DROP INDEX `organisation_beziehung_organisation_zielorganisation_art_unique`,
  ADD `organisation_beziehung_organisation_ziel_organisation_art_unique` VARCHAR(45) GENERATED ALWAYS AS (CONCAT_WS('_', `organisation_id`, `ziel_organisation_id`, `art`, IFNULL(`bis`, '9999-12-31'))) VIRTUAL NOT NULL COMMENT 'Kombination aus organisation_id, ziel_organisation_id, art und bis muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE organisation_beziehung_log
  ADD `organisation_beziehung_organisation_ziel_organisation_art_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- in_kommission
ALTER TABLE in_kommission
  DROP INDEX `in_kommission_parlamentarier_kommission_funktion_unique`,
  ADD `in_kommission_parlamentarier_kommission_funktion_unique` VARCHAR(45) GENERATED ALWAYS AS (CONCAT_WS('_', `parlamentarier_id`, `kommission_id`, `funktion`, IFNULL(`bis`, '9999-12-31'))) VIRTUAL NOT NULL COMMENT 'Kombination aus parlamentarier_id, kommission_id, funktion und bis muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE in_kommission_log
  ADD `in_kommission_parlamentarier_kommission_funktion_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- zuttrittsberechtigung
ALTER TABLE zutrittsberechtigung
  DROP INDEX `parlamentarier_person_unique`,
  ADD `zutrittsberechtigung_parlamentarier_person_unique` VARCHAR(35) GENERATED ALWAYS AS (CONCAT_WS('_', `parlamentarier_id`, `person_id`, IFNULL(`bis`, '9999-12-31'))) VIRTUAL NOT NULL COMMENT 'Kombination aus parlamentarier_id, person_id und bis muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE zutrittsberechtigung_log
  ADD `zutrittsberechtigung_parlamentarier_person_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- organisation
ALTER TABLE organisation
  CHANGE `land_id` `land_id` INT(11) NULL DEFAULT '191' COMMENT 'Land der Organisation',
  -- DROP INDEX `uid_unique`,
  -- ADD `organisation_uid_unique` VARCHAR(15) GENERATED ALWAYS AS (CONCAT_WS('_', IFNULL(`uid`, CONCAT('UNIQUE_', id)))) VIRTUAL NOT NULL COMMENT 'uid muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE,
  DROP INDEX `organisation_name_de_unique`,
  ADD `organisation_name_de_rechtsform_unique` VARCHAR(190) GENERATED ALWAYS AS (CONCAT_WS('_', name_de, IFNULL(`rechtsform`, '-'))) VIRTUAL NOT NULL COMMENT 'Kombination aus name_de und rechtsform muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE,
  DROP INDEX `organisation_name_fr_unique`,
  ADD `organisation_name_fr_rechtsform_unique` VARCHAR(190) GENERATED ALWAYS AS (CONCAT_WS('_', name_fr, IFNULL(`rechtsform`, '-'))) VIRTUAL NOT NULL COMMENT 'Kombination aus name_fr und rechtsform muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE,
  DROP INDEX `organisation_name_it_unique`,
  ADD `organisation_name_it_rechtsform_unique` VARCHAR(190) GENERATED ALWAYS AS (CONCAT_WS('_', name_it, IFNULL(`rechtsform`, '-'))) VIRTUAL NOT NULL COMMENT 'Kombination aus name_it und rechtsform muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE organisation_log
  CHANGE `land_id` `land_id` INT(11) NULL DEFAULT '191' COMMENT 'Land der Organisation',
  -- ADD `organisation_uid_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint',
  ADD `organisation_name_de_rechtsform_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint',
  ADD `organisation_name_fr_rechtsform_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint',
  ADD `organisation_name_it_rechtsform_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- person
ALTER TABLE person
  DROP INDEX `person_nachname_zweiter_name_vorname_unique`,
  ADD `person_nachname_zweiter_name_vorname_unique` VARCHAR(220) GENERATED ALWAYS AS (CONCAT_WS('_', nachname, vorname, IFNULL(`zweiter_vorname`, '-'),  IFNULL(`namensunterscheidung`, '-'))) VIRTUAL NOT NULL COMMENT 'Kombination aus nachname, vorname, zweiter_vorname und namensunterscheidung muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE person_log
  ADD `person_nachname_zweiter_name_vorname_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';

-- zutrittsberechtigung
ALTER TABLE zutrittsberechtigung
  DROP INDEX `parlamentarier_person_unique`,
  ADD `zutrittsberechtigung_parlamentarier_person_unique` VARCHAR(35) GENERATED ALWAYS AS (CONCAT_WS('_', `parlamentarier_id`, `person_id`, IFNULL(`bis`, '9999-12-31'))) VIRTUAL NOT NULL COMMENT 'Kombination aus parlamentarier_id, person_id und bis muss eindeutig sein. (Fachlicher unique constraint)' UNIQUE;

ALTER TABLE zutrittsberechtigung_log
  ADD `zutrittsberechtigung_parlamentarier_person_unique` VARCHAR(0) COMMENT 'Platzhalter für fachlichen unique constraint';
