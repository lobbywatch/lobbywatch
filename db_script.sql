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

-- 08.02.2014

UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
p.updated_visa='roland';

-- 10.02.2014

SELECT * FROM `COLUMNS` WHERE `TABLE_NAME`='parlamentarier' and `TABLE_SCHEMA`='lobbywatch';

-- 02.03.2014

SELECT * FROM  `country` WHERE `name_de` like '%, %';
UPDATE `country` SET `name_de`=REPLACE(`name_de`, ',Republik', '')  WHERE `name_de` like '%,Republik';


-- 16.03.2014

