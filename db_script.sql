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

-- TRIGGERS

-- http://blog.mclaughlinsoftware.com/2012/07/03/placement-over-substance/
SET SQL_MODE=(SELECT CONCAT(@@sql_mode,',IGNORE_SPACE'));

-- Before MySQL 5.5
drop trigger if exists trg_organisation_name_ins;
delimiter //
create trigger trg_organisation_name_ins before insert on organisation
for each row
thisTrigger: begin
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
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
thisTrigger: begin
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
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



-- PROCEDURES & FUNCTIONS

-- UTF-8 URL encode

--http://jeremythomerson.com/2013/05/30/urlencoder-function-for-mysql/
DELIMITER ;
DROP FUNCTION IF EXISTS UTF8_URLENCODE;
DELIMITER |
CREATE FUNCTION UTF8_URLENCODE(str VARCHAR(4096) CHARSET utf8) RETURNS VARCHAR(4096) CHARSET utf8
DETERMINISTIC
CONTAINS SQL
COMMENT 'Encode UTF-8 string as URL'
BEGIN
   -- the individual character we are converting in our loop
   -- NOTE: must be VARCHAR even though it won't vary in length
   -- CHAR(1), when used with SUBSTRING, made spaces '' instead of ' '
   DECLARE sub VARCHAR(1) CHARSET utf8;
   -- the ordinal value of the character (i.e. ñ becomes 50097)
   DECLARE val BIGINT DEFAULT 0;
   -- the substring index we use in our loop (one-based)
   DECLARE ind INT DEFAULT 1;
   -- the integer value of the individual octet of a character being encoded
   -- (which is potentially multi-byte and must be encoded one byte at a time)
   DECLARE oct INT DEFAULT 0;
   -- the encoded return string that we build up during execution
   DECLARE ret VARCHAR(4096) DEFAULT '';
   -- our loop index for looping through each octet while encoding
   DECLARE octind INT DEFAULT 0;

   IF ISNULL(str) THEN
      RETURN NULL;
   ELSE
      SET ret = '';
      -- loop through the input string one character at a time - regardless
      -- of how many bytes a character consists of
      WHILE ind <= CHAR_LENGTH(str) DO
         SET sub = MID(str, ind, 1);
         SET val = ORD(sub);
         -- these values are ones that should not be converted
         -- see http://tools.ietf.org/html/rfc3986
         IF NOT (val BETWEEN 48 AND 57 OR     -- 48-57  = 0-9
                 val BETWEEN 65 AND 90 OR     -- 65-90  = A-Z
                 val BETWEEN 97 AND 122 OR    -- 97-122 = a-z
                 -- 45 = hyphen, 46 = period, 95 = underscore, 126 = tilde
                 val IN (45, 46, 95, 126)) THEN
            -- This is not an "unreserved" char and must be encoded:
            -- loop through each octet of the potentially multi-octet character
            -- and convert each into its hexadecimal value
            -- we start with the high octect because that is the order that ORD
            -- returns them in - they need to be encoded with the most significant
            -- byte first
            SET octind = OCTET_LENGTH(sub);
            WHILE octind > 0 DO
               -- get the actual value of this octet by shifting it to the right
               -- so that it is at the lowest byte position - in other words, make
               -- the octet/byte we are working on the entire number (or in even
               -- other words, oct will no be between zero and 255 inclusive)
               SET oct = (val >> (8 * (octind - 1)));
               -- we append this to our return string with a percent sign, and then
               -- a left-zero-padded (to two characters) string of the hexadecimal
               -- value of this octet)
               SET ret = CONCAT(ret, '%', LPAD(HEX(oct), 2, 0));
               -- now we need to reset val to essentially zero out the octet that we
               -- just encoded so that our number decreases and we are only left with
               -- the lower octets as part of our integer
               SET val = (val & (POWER(256, (octind - 1)) - 1));
               SET octind = (octind - 1);
            END WHILE;
         ELSE
            -- this character was not one that needed to be encoded and can simply be
            -- added to our return string as-is
            SET ret = CONCAT(ret, sub);
         END IF;
         SET ind = (ind + 1);
      END WHILE;
   END IF;
   RETURN ret;
END;
|
DELIMITER ;

-- -- URL encoder
-- -- Ref: http://www.cybercanibal.com/articulos-tecnicos/experiencia-tecnica/115-mysql-url-base64-encode-decode-function
-- DELIMITER ;
-- DROP FUNCTION IF EXISTS urlencode;
-- DELIMITER |
-- CREATE FUNCTION urlencode (s VARCHAR(4096)) RETURNS VARCHAR(4096)
-- DETERMINISTIC
-- CONTAINS SQL
-- COMMENT "Encode URL"
-- BEGIN
--        DECLARE c VARCHAR(4096) DEFAULT '';
--        DECLARE pointer INT DEFAULT 1;
--        DECLARE s2 VARCHAR(4096) DEFAULT '';
--
--        IF ISNULL(s) THEN
--            RETURN NULL;
--        ELSE
--        SET s2 = '';
--        WHILE pointer <= length(s) DO
--           SET c = MID(s,pointer,1);
--           IF c = ' ' THEN
--              SET c = '+';
--           ELSEIF NOT (ASCII(c) BETWEEN 48 AND 57 OR
--                 ASCII(c) BETWEEN 65 AND 90 OR
--                 ASCII(c) BETWEEN 97 AND 122) THEN
--              SET c = concat("%",LPAD(CONV(ASCII(c),10,16),2,0));
--           END IF;
--           SET s2 = CONCAT(s2,c);
--           SET pointer = pointer + 1;
--        END while;
--        END IF;
--        RETURN s2;
-- END;
--
-- |
--
-- DELIMITER ;
--
-- DROP FUNCTION IF EXISTS urldecode;
--
-- DELIMITER |
--
-- CREATE FUNCTION urldecode (s VARCHAR(4096)) RETURNS VARCHAR(4096)
-- DETERMINISTIC
-- CONTAINS SQL
-- COMMENT "Decode URL"
-- BEGIN
--        DECLARE c VARCHAR(4096) DEFAULT '';
--        DECLARE pointer INT DEFAULT 1;
--        DECLARE h CHAR(2);
--        DECLARE h1 CHAR(1);
--        DECLARE h2 CHAR(1);
--        DECLARE s2 VARCHAR(4096) DEFAULT '';
--
--        IF ISNULL(s) THEN
--           RETURN NULL;
--        ELSE
--        SET s2 = '';
--        WHILE pointer <= LENGTH(s) DO
--           SET c = MID(s,pointer,1);
--           IF c = '+' THEN
--              SET c = ' ';
--           ELSEIF c = '%' AND pointer + 2 <= LENGTH(s) THEN
--              SET h1 = LOWER(MID(s,pointer+1,1));
--              SET h2 = LOWER(MID(s,pointer+2,1));
--              IF (h1 BETWEEN '0' AND '9' OR h1 BETWEEN 'a' AND 'f')
--                  AND
--                  (h2 BETWEEN '0' AND '9' OR h2 BETWEEN 'a' AND 'f')
--                  THEN
--                    SET h = CONCAT(h1,h2);
--                    SET pointer = pointer + 2;
--                    SET c = CHAR(CONV(h,16,10));
--               END IF;
--           END IF;
--           SET s2 = CONCAT(s2,c);
--           SET pointer = pointer + 1;
--        END while;
--        END IF;
--        RETURN s2;
-- END;
--
-- |
-- DELIMITER ;

-- -- base64.sql - MySQL base64 encoding/decoding functions
-- -- Copyright (C) 2006 Ian Gulliver
-- --
-- -- This program is free software; you can redistribute it and/or modify
-- -- it under the terms of version 2 of the GNU General Public License as
-- -- published by the Free Software Foundation.
-- --
-- -- This program is distributed in the hope that it will be useful,
-- -- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- -- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- -- GNU General Public License for more details.
-- --
-- -- You should have received a copy of the GNU General Public License
-- -- along with this program; if not, write to the Free Software
-- -- Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
--
-- delimiter |
--
-- DROP TABLE IF EXISTS base64_data |
-- CREATE TABLE base64_data (c CHAR(1) BINARY, val TINYINT) |
-- INSERT INTO base64_data VALUES
--   ('A',0), ('B',1), ('C',2), ('D',3), ('E',4), ('F',5), ('G',6), ('H',7), ('I',8), ('J',9),
--   ('K',10), ('L',11), ('M',12), ('N',13), ('O',14), ('P',15), ('Q',16), ('R',17), ('S',18), ('T',19),
--   ('U',20), ('V',21), ('W',22), ('X',23), ('Y',24), ('Z',25), ('a',26), ('b',27), ('c',28), ('d',29),
--   ('e',30), ('f',31), ('g',32), ('h',33), ('i',34), ('j',35), ('k',36), ('l',37), ('m',38), ('n',39),
--   ('o',40), ('p',41), ('q',42), ('r',43), ('s',44), ('t',45), ('u',46), ('v',47), ('w',48), ('x',49),
--   ('y',50), ('z',51), ('0',52), ('1',53), ('2',54), ('3',55), ('4',56), ('5',57), ('6',58), ('7',59),
--   ('8',60), ('9',61), ('+',62), ('/',63), ('=',0) |
--
--
-- DROP FUNCTION IF EXISTS BASE64_DECODE |
-- CREATE FUNCTION BASE64_DECODE (input BLOB)
--   RETURNS BLOB
--   CONTAINS SQL
--   DETERMINISTIC
--   SQL SECURITY INVOKER
-- BEGIN
--   DECLARE ret BLOB DEFAULT '';
--   DECLARE done TINYINT DEFAULT 0;
--
--   IF input IS NULL THEN
--     RETURN NULL;
--   END IF;
--
-- each_block:
--   WHILE NOT done DO BEGIN
--     DECLARE accum_value BIGINT UNSIGNED DEFAULT 0;
--     DECLARE in_count TINYINT DEFAULT 0;
--     DECLARE out_count TINYINT DEFAULT 3;
--
-- each_input_char:
--     WHILE in_count < 4 DO BEGIN
--       DECLARE first_char CHAR(1);
--
--       IF LENGTH(input) = 0 THEN
--         RETURN ret;
--       END IF;
--
--       SET first_char = SUBSTRING(input,1,1);
--       SET input = SUBSTRING(input,2);
--
--       BEGIN
--         DECLARE tempval TINYINT UNSIGNED;
--         DECLARE error TINYINT DEFAULT 0;
--         DECLARE base64_getval CURSOR FOR SELECT val FROM base64_data WHERE c = first_char;
--         DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET error = 1;
--
--         OPEN base64_getval;
--         FETCH base64_getval INTO tempval;
--         CLOSE base64_getval;
--
--         IF error THEN
--           ITERATE each_input_char;
--         END IF;
--
--         SET accum_value = (accum_value << 6) + tempval;
--       END;
--
--       SET in_count = in_count + 1;
--
--       IF first_char = '=' THEN
--         SET done = 1;
--         SET out_count = out_count - 1;
--       END IF;
--     END; END WHILE;
--
--     -- We've now accumulated 24 bits; deaccumulate into bytes
--
--     -- We have to work from the left, so use the third byte position and shift left
--     WHILE out_count > 0 DO BEGIN
--       SET ret = CONCAT(ret,CHAR((accum_value & 0xff0000) >> 16));
--       SET out_count = out_count - 1;
--       SET accum_value = (accum_value << 8) & 0xffffff;
--     END; END WHILE;
--
--   END; END WHILE;
--
--   RETURN ret;
-- END |
--
-- DROP FUNCTION IF EXISTS BASE64_ENCODE |
-- CREATE FUNCTION BASE64_ENCODE (input BLOB)
--   RETURNS BLOB
--   CONTAINS SQL
--   DETERMINISTIC
--   SQL SECURITY INVOKER
-- BEGIN
--   DECLARE ret BLOB DEFAULT '';
--   DECLARE done TINYINT DEFAULT 0;
--
--   IF input IS NULL THEN
--     RETURN NULL;
--   END IF;
--
-- each_block:
--   WHILE NOT done DO BEGIN
--     DECLARE accum_value BIGINT UNSIGNED DEFAULT 0;
--     DECLARE in_count TINYINT DEFAULT 0;
--     DECLARE out_count TINYINT;
--
-- each_input_char:
--     WHILE in_count < 3 DO BEGIN
--       DECLARE first_char CHAR(1);
--
--       IF LENGTH(input) = 0 THEN
--         SET done = 1;
--         SET accum_value = accum_value << (8 * (3 - in_count));
--         LEAVE each_input_char;
--       END IF;
--
--       SET first_char = SUBSTRING(input,1,1);
--       SET input = SUBSTRING(input,2);
--
--       SET accum_value = (accum_value << 8) + ASCII(first_char);
--
--       SET in_count = in_count + 1;
--     END; END WHILE;
--
--     -- We've now accumulated 24 bits; deaccumulate into base64 characters
--
--     -- We have to work from the left, so use the third byte position and shift left
--     CASE
--       WHEN in_count = 3 THEN SET out_count = 4;
--       WHEN in_count = 2 THEN SET out_count = 3;
--       WHEN in_count = 1 THEN SET out_count = 2;
--       ELSE RETURN ret;
--     END CASE;
--
--     WHILE out_count > 0 DO BEGIN
--       BEGIN
--         DECLARE out_char CHAR(1);
--         DECLARE base64_getval CURSOR FOR SELECT c FROM base64_data WHERE val = (accum_value >> 18);
--
--         OPEN base64_getval;
--         FETCH base64_getval INTO out_char;
--         CLOSE base64_getval;
--
--         SET ret = CONCAT(ret,out_char);
--         SET out_count = out_count - 1;
--         SET accum_value = accum_value << 6 & 0xffffff;
--       END;
--     END; END WHILE;
--
--     CASE
--       WHEN in_count = 2 THEN SET ret = CONCAT(ret,'=');
--       WHEN in_count = 1 THEN SET ret = CONCAT(ret,'==');
--       ELSE BEGIN END;
--     END CASE;
--
--   END; END WHILE;
--
--   RETURN ret;
-- END |

-- Clean trailing whitespace

UPDATE `zugangsberechtigung` SET `nachname`=TRIM(`nachname`),`vorname`=TRIM(`vorname`);

UPDATE `parlamentarier` SET `nachname`=TRIM(`nachname`),`vorname`=TRIM(`vorname`);

-- Last updated tables
SELECT * FROM (
SELECT *
FROM (
(SELECT
  'branche' table_name,
  'Branche' name,
  (select count(*) from `branche`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `branche` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'interessenbindung' table_name,
  'Interessenbindung' name,
  (select count(*) from `interessenbindung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `interessenbindung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'interessengruppe' table_name,
  'Interessengruppe' name,
  (select count(*) from `interessengruppe`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `interessengruppe` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'in_kommission' table_name,
  'In Kommission' name,
  (select count(*) from `in_kommission`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `in_kommission` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'kommission' table_name,
  'Kommission' name,
  (select count(*) from `kommission`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `kommission` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'mandat' table_name,
  'Mandat' name,
  (select count(*) from `mandat`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `mandat` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'organisation' table_name,
  'Organisation' name,
  (select count(*) from `organisation`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `organisation` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'organisation_beziehung' table_name,
  'Organisation Beziehung' name,
  (select count(*) from `organisation_beziehung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `organisation_beziehung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'parlamentarier' table_name,
  'Parlamentarier' name,
  (select count(*) from `parlamentarier`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `parlamentarier` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'parlamentarier_anhang' table_name,
  'Parlamentarieranhang' name,
  (select count(*) from `parlamentarier_anhang`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `parlamentarier_anhang` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'partei' table_name,
  'Partei' name,
  (select count(*) from `partei`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `partei` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
UNION
(SELECT
  'zugangsberechtigung' table_name,
  'Zugangsberechtigung' name,
  (select count(*) from `zugangsberechtigung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `zugangsberechtigung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  )
) union_query
) complete
ORDER BY complete.last_updated DESC;

-- VIEWS ------------------

-- Views Last updated

CREATE OR REPLACE VIEW `v_last_updated_branche` AS
  (SELECT
  'branche' table_name,
  'Branche' name,
  (select count(*) from `branche`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `branche` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_interessenbindung` AS
  (SELECT
  'interessenbindung' table_name,
  'Interessenbindung' name,
  (select count(*) from `interessenbindung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `interessenbindung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_interessengruppe` AS
  (SELECT
  'interessengruppe' table_name,
  'Interessengruppe' name,
  (select count(*) from `interessengruppe`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `interessengruppe` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_in_kommission` AS
  (SELECT
  'in_kommission' table_name,
  'In Kommission' name,
  (select count(*) from `in_kommission`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `in_kommission` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_kommission` AS
  (SELECT
  'kommission' table_name,
  'Kommission' name,
  (select count(*) from `kommission`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `kommission` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_mandat` AS
  (SELECT
  'mandat' table_name,
  'Mandat' name,
  (select count(*) from `mandat`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `mandat` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_organisation` AS
  (SELECT
  'organisation' table_name,
  'Organisation' name,
  (select count(*) from `organisation`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `organisation` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_organisation_beziehung` AS
  (SELECT
  'organisation_beziehung' table_name,
  'Organisation Beziehung' name,
  (select count(*) from `organisation_beziehung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `organisation_beziehung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_parlamentarier` AS
  (SELECT
  'parlamentarier' table_name,
  'Parlamentarier' name,
  (select count(*) from `parlamentarier`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `parlamentarier` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_parlamentarier_anhang` AS
  (SELECT
  'parlamentarier_anhang' table_name,
  'Parlamentarieranhang' name,
  (select count(*) from `parlamentarier_anhang`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `parlamentarier_anhang` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_partei` AS
  (SELECT
  'partei' table_name,
  'Partei' name,
  (select count(*) from `partei`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `partei` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_zugangsberechtigung` AS
  (SELECT
  'zugangsberechtigung' table_name,
  'Zugangsberechtigung' name,
  (select count(*) from `zugangsberechtigung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `zugangsberechtigung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_tables_unordered` AS
SELECT * FROM v_last_updated_branche
UNION
SELECT * FROM v_last_updated_interessenbindung
UNION
SELECT * FROM v_last_updated_interessengruppe
UNION
SELECT * FROM v_last_updated_in_kommission
UNION
SELECT * FROM v_last_updated_kommission
UNION
SELECT * FROM v_last_updated_mandat
UNION
SELECT * FROM v_last_updated_organisation
UNION
SELECT * FROM v_last_updated_organisation_beziehung
UNION
SELECT * FROM v_last_updated_parlamentarier
UNION
SELECT * FROM v_last_updated_parlamentarier_anhang
UNION
SELECT * FROM v_last_updated_partei
UNION
SELECT * FROM v_last_updated_zugangsberechtigung;

CREATE OR REPLACE VIEW `v_last_updated_tables` AS
SELECT * FROM `v_last_updated_tables_unordered`
ORDER BY last_updated DESC;

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

CREATE OR REPLACE VIEW `v_parlamentarier_anhang` AS SELECT t.parlamentarier_id as parlamentarier_id2, t.* FROM `parlamentarier_anhang` t;

CREATE OR REPLACE VIEW `v_user` AS SELECT t.* FROM `user` t;

CREATE OR REPLACE VIEW `v_user_permission` AS SELECT t.* FROM `user_permission` t;

-- Der der Kommissionen für Parlamenterier
-- Connector: in_kommission.parlamentarier_id
CREATE OR REPLACE VIEW `v_in_kommission_liste` AS
SELECT kommission.abkuerzung, kommission.name, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_kommission kommission
  ON in_kommission.kommission_id = kommission.id
ORDER BY kommission.abkuerzung;

-- Parlamenterier einer Kommission
-- Connector: in_kommission.kommission_id
CREATE OR REPLACE VIEW `v_in_kommission_parlamentarier` AS
SELECT parlamentarier.anzeige_name as parlamentarier_name, partei.abkuerzung, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_parlamentarier parlamentarier
  ON in_kommission.parlamentarier_id = parlamentarier.id
LEFT JOIN v_partei partei
  ON parlamentarier.partei_id = partei.id
ORDER BY parlamentarier.anzeige_name;

-- Interessenbindung eines Parlamenteriers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste` AS
SELECT organisation.anzeige_name as organisation_name, interessenbindung.*
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
CREATE OR REPLACE VIEW `v_organisation_beziehung_arbeitet_fuer` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, die eine PR-Firma beauftragt haben.
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_auftraggeber_fuer` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, in welcher eine Organisation Mitglied ist.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglied_von` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Mitgliedsorganisationen
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglieder` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.*
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Parlamenterier, die eine Interessenbindung zu dieser Organisation haben.
-- Connector: interessenbindung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_parlamentarier` AS
SELECT parlamentarier.anzeige_name as parlamentarier_name, interessenbindung.*
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

-- Authorisieurngsemail Interessenbindung für Parlamenterier
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_authorisierungs_email` AS
SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, organisation.anzeige_name as organisation_name, IFNULL(organisation.rechtsform,'') rechtsform, IFNULL(organisation.ort,'') ort, interessenbindung.art, interessenbindung.beschreibung
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
ORDER BY organisation.anzeige_name;

-- Authorisieurngsemail Interessenbindung für Parlamenterier
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zugangsberechtigung_authorisierungs_email` AS
SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, zugangsberechtigung.name zugangsberechtigung_name, zugangsberechtigung.funktion
FROM v_zugangsberechtigung zugangsberechtigung
INNER JOIN v_parlamentarier parlamentarier
  ON zugangsberechtigung.parlamentarier_id = parlamentarier.id
GROUP BY parlamentarier.id;

-- Authorisieurngsemail Interessenbindung für Parlamenterier
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_authorisierungs_email` AS
SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, organisation.anzeige_name as organisation_name, IFNULL(organisation.rechtsform,'') rechtsform, IFNULL(organisation.ort,'') ort, interessenbindung.art, interessenbindung.beschreibung
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
GROUP BY parlamentarier.id
ORDER BY organisation.anzeige_name;

-- Authorisieurngsemail Interessenbindung für Parlamenterier
-- Connector: parlamentarier_id
CREATE OR REPLACE VIEW `v_parlamentarier_authorisierungs_email` AS
SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.email,
CONCAT(
  CASE parlamentarier.geschlecht
    WHEN 'M' THEN CONCAT('<p>Sehr geehrter Herr ', parlamentarier.nachname, '</p>')
    WHEN 'F' THEN CONCAT('<p>Sehr geehrte Frau ', parlamentarier.nachname, '</p>')
    ELSE CONCAT('<p>Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '</p>')
  END,
  '<p>[Einleitung]</p>',
  '<p>Ihre <b>Interessenbindungen</b>:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', interessenbindung.beschreibung)
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p>Ihre <b>Gäste</b>:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zugangsberechtigung.name, ', ', zugangsberechtigung.funktion)
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p>Mit freundlichen Grüssen,<br></p>'
) email_text_html,
UTF8_URLENCODE(CONCAT(
  CASE parlamentarier.geschlecht
    WHEN 'M' THEN CONCAT('Sehr geehrter Herr ', parlamentarier.nachname, '\r\n')
    WHEN 'F' THEN CONCAT('Sehr geehrte Frau ', parlamentarier.nachname, '\r\n')
    ELSE CONCAT('Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '\r\n')
  END,
  '\r\n[Ersetze Text mit HTML-Vorlage]\r\n',
  'Ihre Interessenbindungen:\r\n',
  GROUP_CONCAT(DISTINCT
    CONCAT('* ', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', interessenbindung.beschreibung, '\r\n')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '\r\nIhre Gäste:\r\n',
  GROUP_CONCAT(DISTINCT
    CONCAT('* ', zugangsberechtigung.name, ', ', zugangsberechtigung.funktion, '\r\n')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '\r\nMit freundlichen Grüssen,\r\n'
)) email_text_for_url
FROM v_parlamentarier parlamentarier
LEFT JOIN v_interessenbindung interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id
LEFT JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zugangsberechtigung zugangsberechtigung
  ON zugangsberechtigung.parlamentarier_id = parlamentarier.id
GROUP BY parlamentarier.id;
