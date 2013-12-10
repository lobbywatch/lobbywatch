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


UPDATE `lobbycontrol`.`branche` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbycontrol`.`parlamentarier` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbycontrol`.`kommission` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbycontrol`.`interessenbindung` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbycontrol`.`zugangsberechtigung` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbycontrol`.`lobbyorganisation` SET
`created_visa` = 'import',
`updated_visa` = 'import';

UPDATE `lobbycontrol`.`interessengruppe` SET
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

UPDATE `lobbycontrol`.`partei` SET `created_visa` = 'roland',`updated_visa` = 'roland' WHERE `partei`.`id` =3;

UPDATE `parlamentarier` l LEFT JOIN partei p ON l.`ALT_partei` = p.abkuerzung SET l.`partei_id` = p.id,
l.`updated_visa` = 'roland'

SELECT p.abkuerzung, `ALT_partei` FROM `parlamentarier` l left join partei p on l.partei_id=p.id

UPDATE lobbycontrol.`parlamentarier` n JOIN lobbycontrol_old.`parlamentarier` o ON o.id_parlam = n.id SET n.im_rat_seit = o.im_rat_seit;

UPDATE lobbycontrol.`parlamentarier` n JOIN lobbycontrol_old.`parlamentarier` o ON o.id_parlam = n.id SET n.im_rat_seit = STR_TO_DATE(CONCAT('01.01.', o.im_rat_seit),'%d.%m.%Y')


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

-- TRIGGERS

-- http://blog.mclaughlinsoftware.com/2012/07/03/placement-over-substance/
SET SQL_MODE=(SELECT CONCAT(@@sql_mode,',IGNORE_SPACE'));

-- Before MySQL 5.5
drop trigger if exists trg_organisation_name_ins;
delimiter //
create trigger trg_organisation_name_ins before insert on organisation
for each row
begin
    if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
        call organisation_name_de_fr_it_must_be_set;
    end if;
end
//

delimiter ;
drop trigger if exists trg_organisation_name_upd;
delimiter //
create trigger trg_organisation_name_upd before update on organisation
for each row
begin
    if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
        call organisation_name_de_fr_it_must_be_set;
    end if;
end
//
delimiter ;

-- MySQL 5.5 required
-- delimiter $$
-- drop trigger if exists trg_organisation_name_ins $$
-- create trigger trg_organisation_name_ins before insert on organisation
-- for each row
-- begin
--     declare msg varchar(255);
--     if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
--         set msg = concat('NameError: Either name_de, name_fr or name_it must be set. ID: ', cast(new.id as char));
--         signal sqlstate '45000' set message_text = msg;
--     end if;
-- end $$
-- delimiter ;
-- delimiter $$
-- drop trigger if exists trg_organisation_name_upd $$
-- create trigger trg_organisation_name_upd before update on organisation
-- for each row
-- begin
--     declare msg varchar(255);
--     if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
--         set msg = concat('NameError: Either name_de, name_fr or name_it must be set. ID: ', cast(new.id as char));
--         signal sqlstate '45000' set message_text = msg;
--     end if;
-- end $$
-- delimiter ;

-- -- Compatibility VIEWS for Auswertung
-- 
-- CREATE OR REPLACE VIEW `parlamentarier` AS SELECT t.*  FROM `v_parlamentarier` t;
-- 
-- CREATE OR REPLACE VIEW `kommission` AS SELECT t.* FROM `v_kommission` t;
-- 
-- CREATE OR REPLACE VIEW `partei` AS SELECT t.* FROM `v_partei` t;
-- 
-- CREATE OR REPLACE VIEW `interessenbindung` AS SELECT t.* FROM `v_interessenbindung` t;
-- 
-- CREATE OR REPLACE VIEW `zugangsberechtigung` AS SELECT t.* FROM `v_zugangsberechtigung` t;
-- 
-- CREATE OR REPLACE VIEW `organisation` AS SELECT t.* FROM `v_organisation` t;
-- 
-- CREATE OR REPLACE VIEW `interessengruppe` AS SELECT t.* FROM `v_interessengruppe` t;
-- 
-- CREATE OR REPLACE VIEW `branche` AS SELECT t.* FROM `v_branche` t;
-- 
-- CREATE OR REPLACE VIEW `mandat` AS SELECT t.* FROM `v_mandat` t;
-- 
-- CREATE OR REPLACE VIEW `in_kommission` AS SELECT t.* FROM `v_in_kommission` t;
-- 
-- CREATE OR REPLACE VIEW `organisation_beziehung` AS SELECT t.* FROM `v_organisation_beziehung` t;
-- 
-- CREATE OR REPLACE VIEW `parlamentarier_anhang` AS SELECT t.* FROM `v_parlamentarier_anhang` t;

-- start with connect by http://explainextended.com/2009/03/17/hierarchical-queries-in-mysql/

-- Clean trailing whitespace

UPDATE `zugangsberechtigung` SET `nachname`=TRIM(`nachname`),`vorname`=TRIM(`vorname`)

UPDATE `parlamentarier` SET `nachname`=TRIM(`nachname`),`vorname`=TRIM(`vorname`)

-- VIEWS

CREATE OR REPLACE VIEW `v_parlamentarier` AS SELECT CONCAT(t.nachname, ', ', t.vorname) AS anzeige_name, CONCAT(t.vorname, ' ', t.nachname) AS name, t.*  FROM `parlamentarier` t;

CREATE OR REPLACE VIEW `v_kommission` AS SELECT CONCAT(t.name, ' (', t.abkuerzung, ')') AS anzeige_name, t.* FROM `kommission` t;

CREATE OR REPLACE VIEW `v_partei` AS SELECT CONCAT(t.name, ' (', t.abkuerzung, ')') AS anzeige_name, t.* FROM `partei` t;

CREATE OR REPLACE VIEW `v_interessenbindung` AS SELECT t.* FROM `interessenbindung` t;

CREATE OR REPLACE VIEW `v_zugangsberechtigung` AS SELECT CONCAT(t.nachname, ', ', t.vorname) AS anzeige_name, CONCAT(t.vorname, ' ', t.nachname) AS name, t.* FROM `zugangsberechtigung` t;

CREATE OR REPLACE VIEW `v_organisation` AS SELECT CONCAT_WS('; ', t.name_de , t.name_fr, t.name_it) AS anzeige_name, CONCAT_WS('; ', t.name_de , t.name_fr, t.name_it) AS name, t.* FROM `organisation` t;

CREATE OR REPLACE VIEW `v_interessengruppe` AS SELECT t.* FROM `interessengruppe` t;

CREATE OR REPLACE VIEW `v_branche` AS SELECT t.* FROM `branche` t;

CREATE OR REPLACE VIEW `v_mandat` AS SELECT t.* FROM `mandat` t;

CREATE OR REPLACE VIEW `v_in_kommission` AS SELECT t.* FROM `in_kommission` t;

CREATE OR REPLACE VIEW `v_organisation_beziehung` AS SELECT t.* FROM `organisation_beziehung` t;

CREATE OR REPLACE VIEW `v_parlamentarier_anhang` AS SELECT t.* FROM `parlamentarier_anhang` t;

CREATE OR REPLACE VIEW `v_user` AS SELECT t.* FROM `user` t;

CREATE OR REPLACE VIEW `v_user_permission` AS SELECT t.* FROM `user_permission` t;

-- Der der Kommissionen für Parlamenterier
-- Connector: in_kommission.parlamentarier_id
CREATE OR REPLACE VIEW `v_in_kommission_liste` AS SELECT kommission.abkuerzung, kommission.name, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_kommission kommission
  ON in_kommission.kommission_id = kommission.id
ORDER BY kommission.abkuerzung;

-- Parlamenterier einer Kommission
-- Connector: in_kommission.kommission_id
CREATE OR REPLACE VIEW `v_in_kommission_parlamentarier` AS SELECT parlamentarier.anzeige_name as parlamentarier_name, partei.abkuerzung, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_parlamentarier parlamentarier
  ON in_kommission.parlamentarier_id = parlamentarier.id
LEFT JOIN v_partei partei
  ON parlamentarier.partei_id = partei.id
ORDER BY parlamentarier.anzeige_name;

-- Interessenbindung eines Parlamenteriers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste` AS SELECT organisation.anzeige_name as organisation_name, interessenbindung.*
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
ORDER BY organisation.anzeige_name;

-- Indirekte Interessenbindungen eines Parlamenteriers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste_indirekt` AS 
SELECT 'direkt' as beziehung, interessenbindung_liste.* FROM v_interessenbindung_liste interessenbindung_liste
UNION
SELECT 'indirekt' as beziehung, organisation.anzeige_name as organisation_name, interessenbindung.*
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON interessenbindung.organisation_id = organisation_beziehung.organisation_id
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, organisation_name;

-- Mandate einer Zugangsberechtigung (INNER JOIN)
-- Connector: zugangsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zugangsberechtigung_mandate` AS
SELECT zugangsberechtigung.parlamentarier_id, organisation.anzeige_name as organisation_name, zugangsberechtigung.anzeige_name as zugangsberechtigung_name, zugangsberechtigung.funktion, mandat.*
FROM v_zugangsberechtigung zugangsberechtigung
INNER JOIN v_mandat mandat
  ON zugangsberechtigung.id = mandat.zugangsberechtigung_id
INNER JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
ORDER BY organisation.anzeige_name;

-- Mandate einer Zugangsberechtigung (LFET JOIN)
-- Connector: zugangsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zugangsberechtigung_mit_mandaten` AS
SELECT organisation.anzeige_name as organisation_name, zugangsberechtigung.anzeige_name as zugangsberechtigung_name, zugangsberechtigung.funktion, zugangsberechtigung.parlamentarier_id, mandat.*
FROM v_zugangsberechtigung zugangsberechtigung
LEFT JOIN v_mandat mandat
  ON zugangsberechtigung.id = mandat.zugangsberechtigung_id
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
ORDER BY zugangsberechtigung.anzeige_name;

-- Indirekte Mandate einer Zugangsberechtigung (LFET JOIN)
-- Connector: zugangsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zugangsberechtigung_mit_mandaten_indirekt` AS
SELECT 'direkt' as beziehung, zugangsberechtigung.* FROM v_zugangsberechtigung_mit_mandaten zugangsberechtigung
UNION
SELECT 'indirekt' as beziehung, organisation.name as organisation_name, zugangsberechtigung.anzeige_name as zugangsberechtigung_name, zugangsberechtigung.funktion, zugangsberechtigung.parlamentarier_id, mandat.*
FROM v_zugangsberechtigung zugangsberechtigung
INNER JOIN v_mandat mandat
  ON zugangsberechtigung.id = mandat.zugangsberechtigung_id
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON mandat.organisation_id = organisation_beziehung.organisation_id
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, organisation_name;

-- Organisationen für welche eine PR-Agentur arbeitet.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_arbeitet_fuer` AS SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, die eine PR-Firma beauftragt haben.
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_auftraggeber_fuer` AS SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, in welcher eine Organisation Mitglied ist.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglied_von` AS SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Mitgliedsorganisationen
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglieder` AS SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Parlamenterier, die eine Interessenbindung zu dieser Organisation haben.
-- Connector: interessenbindung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_parlamentarier` AS SELECT parlamentarier.anzeige_name as parlamentarier_name, interessenbindung.*
FROM v_interessenbindung interessenbindung
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
ORDER BY parlamentarier.anzeige_name;

-- Parlamenterier, die eine indirekte Interessenbindung zu dieser Organisation haben.
-- Connector: connector_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_indirekt` AS
SELECT 'direkt' as beziehung, organisation_parlamentarier.*, organisation_parlamentarier.organisation_id as connector_organisation_id FROM v_organisation_parlamentarier organisation_parlamentarier
UNION
SELECT 'indirekt' as beziehung, parlamentarier.anzeige_name as parlamentarier_name, interessenbindung.*, organisation_beziehung.ziel_organisation_id as connector_organisation_id
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_interessenbindung interessenbindung
  ON organisation_beziehung.organisation_id = interessenbindung.organisation_id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, parlamentarier_name;
