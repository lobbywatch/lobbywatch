-- Lobbywatch procedures and triggers

SET @OLD_SQL_MODE=@@SQL_MODE;

-- SET FOREIGN_KEY_CHECKS=0;
-- SET FOREIGN_KEY_CHECKS=1;
-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- http://blog.mclaughlinsoftware.com/2012/07/03/placement-over-substance/
-- SET SQL_MODE=(SELECT CONCAT(@@sql_mode,',IGNORE_SPACE'));

-- PROCEDURES & FUNCTIONS

-- Run: CALL takeSnapshot('roland', 'Initial');
DROP PROCEDURE IF EXISTS takeSnapshot;
delimiter //
CREATE PROCEDURE takeSnapshot(aVisa VARCHAR(10), aBeschreibung VARCHAR(150)) MODIFIES SQL DATA
COMMENT 'Speichert einen Snapshot in die _log Tabellen.'
BEGIN
  DECLARE ts TIMESTAMP DEFAULT NOW();
  DECLARE sid int(11);
  INSERT INTO `snapshot` (`id`, `beschreibung`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES (NULL, aBeschreibung, NULL, aVisa, ts, aVisa, ts);
  SELECT LAST_INSERT_ID() INTO sid;

   INSERT INTO `branche_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `branche`;

   INSERT INTO `interessenbindung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessenbindung`;

   INSERT INTO `interessenbindung_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessenbindung_jahr`;

   INSERT INTO `interessengruppe_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `interessengruppe`;

   INSERT INTO `in_kommission_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `in_kommission`;

   INSERT INTO `kommission_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kommission`;

   INSERT INTO `mandat_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `mandat`;

   INSERT INTO `mandat_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `mandat_jahr`;

   INSERT INTO `organisation_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation`;

   INSERT INTO `organisation_beziehung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_beziehung`;

   INSERT INTO `organisation_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_anhang`;

   INSERT INTO `organisation_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `organisation_jahr`;

   INSERT INTO `parlamentarier_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `parlamentarier`;

   INSERT INTO `parlamentarier_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `parlamentarier_anhang`;

   INSERT INTO `partei_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `partei`;

   INSERT INTO `fraktion_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `fraktion`;

   INSERT INTO `rat_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `rat`;

   INSERT INTO `kanton_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kanton`;

   INSERT INTO `kanton_jahr_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `kanton_jahr`;

   INSERT INTO `settings_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `settings`;

   INSERT INTO `settings_category_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `settings_category`;

   INSERT INTO `person_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `person`;

   INSERT INTO `person_anhang_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `person_anhang`;

   INSERT INTO `zutrittsberechtigung_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `zutrittsberechtigung`;

   INSERT INTO `translation_source_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `translation_source`;

   INSERT INTO `translation_target_log`
     SELECT *, null, 'snapshot', null, ts, sid FROM `translation_target`;

END
//
delimiter ;

-- CALL `refreshMaterializedViews`()
DROP PROCEDURE IF EXISTS refreshMaterializedViews;
delimiter //
CREATE PROCEDURE refreshMaterializedViews() MODIFIES SQL DATA
COMMENT 'Aktualisiert die Materialized Views.'
BEGIN
  DECLARE ts TIMESTAMP DEFAULT NOW();

--  REPLACE INTO `mv_organisation_lobbyeinfluss`
--    SELECT * FROM `v_organisation_lobbyeinfluss_raw`;

--  REPLACE INTO `mv_zutrittsberechtigung_lobbyfaktor`
--    SELECT * FROM `v_zutrittsberechtigung_lobbyfaktor_raw`;

--  REPLACE INTO `mv_parlamentarier_lobbyfaktor`
--    SELECT * FROM `v_parlamentarier_lobbyfaktor_raw`;

--  REPLACE INTO `mv_zutrittsberechtigung_lobbyfaktor_max`
--    SELECT * FROM `v_zutrittsberechtigung_lobbyfaktor_max_raw`;

--  REPLACE INTO `mv_parlamentarier_lobbyfaktor_max`
--    SELECT * FROM `v_parlamentarier_lobbyfaktor_max_raw`;

    REPLACE INTO `mv_interessenbindung`
      SELECT v.* FROM `v_interessenbindung_raw` v; /* LEFT JOIN `mv_interessenbindung` mv ON mv.id = v.id WHERE (v.updated_date > mv.updated_date OR mv.updated_date IS NULL);*/

    REPLACE INTO `mv_mandat`
      SELECT v.* FROM `v_mandat_raw` v; /* LEFT JOIN `mv_mandat` mv ON mv.id = v.id WHERE (v.updated_date > mv.updated_date OR mv.updated_date IS NULL);*/

--  REPLACE INTO `mv_organisation_medium`
--    SELECT * FROM `v_organisation_medium_raw`;
--
--  REPLACE INTO `mv_organisation_medium_myisam`
--    SELECT * FROM `v_organisation_medium_raw`;

    REPLACE INTO `mv_organisation`
      SELECT v.* FROM `v_organisation_raw` v; /* LEFT JOIN `mv_organisation` mv ON mv.id = v.id WHERE (v.updated_date > mv.updated_date OR mv.updated_date IS NULL);*/

--  REPLACE INTO `mv_organisation_myisam`
--    SELECT * FROM `v_organisation_raw`;

--  REPLACE INTO `mv_parlamentarier_medium`
--    SELECT * FROM `v_parlamentarier_medium_raw`;
--
--  REPLACE INTO `mv_parlamentarier_medium_myisam`
--    SELECT * FROM `v_parlamentarier_medium_raw`;

    REPLACE INTO `mv_parlamentarier`
      SELECT v.* FROM `v_parlamentarier_raw` v; /* LEFT JOIN `mv_parlamentarier` mv ON mv.id = v.id WHERE (v.updated_date > mv.updated_date OR mv.updated_date IS NULL);*/

--  REPLACE INTO `mv_parlamentarier_myisam`
--    SELECT * FROM `v_parlamentarier_raw`;

    REPLACE INTO `mv_zutrittsberechtigung`
      SELECT v.* FROM `v_zutrittsberechtigung_raw` v; /* LEFT JOIN `mv_zutrittsberechtigung` mv ON mv.id = v.id WHERE (v.updated_date > mv.updated_date OR mv.updated_date IS NULL);*/

--  REPLACE INTO `mv_zutrittsberechtigung_myisam`
--    SELECT * FROM `v_zutrittsberechtigung_raw`;

    REPLACE INTO `mv_search_table`
      SELECT v.* FROM `v_search_table_raw` v;

END
//
delimiter ;

-- UTF-8 URL encode

-- http://jeremythomerson.com/2013/05/30/urlencoder-function-for-mysql/
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

-- UTF-8 URL encode

-- Emulate PHP ucfirst function
DELIMITER ;
DROP FUNCTION IF EXISTS UCFIRST;
DELIMITER |
CREATE FUNCTION UCFIRST(str VARCHAR(4096) CHARSET utf8) RETURNS VARCHAR(4096) CHARSET utf8
DETERMINISTIC
CONTAINS SQL
COMMENT 'Returns the str with the first character converted to upper case'
BEGIN
	RETURN CONCAT(UCASE(LEFT(str, 1)), SUBSTRING(str, 2));
END;
|
DELIMITER ;

-- TRIGGERS

-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET SQL_MODE = "NO_ENGINE_SUBSTITUTION";

-- http://blog.mclaughlinsoftware.com/2012/07/03/placement-over-substance/
-- SET SQL_MODE=(SELECT CONCAT(@@sql_mode,',IGNORE_SPACE'));

-- Log triggers

-- -----------------------------------------------------------------------------
-- TRIGGERS
-- -----------------------------------------------------------------------------

-- branche triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_branche_log_ins`;
delimiter //
CREATE TRIGGER `trg_branche_log_ins` AFTER INSERT ON `branche`
FOR EACH ROW thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `branche` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_branche_log_upd`;
delimiter //
CREATE TRIGGER `trg_branche_log_upd` AFTER UPDATE ON `branche`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'update', null, NOW(), null FROM `branche` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_branche_log_del_before`;
delimiter //
CREATE TRIGGER `trg_branche_log_del_before` BEFORE DELETE ON `branche`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `branche_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `branche` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_branche_log_del_after`;
delimiter //
CREATE TRIGGER `trg_branche_log_del_after` AFTER DELETE ON `branche`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `branche_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- interessenbindung triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_interessenbindung_log_ins`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_log_ins` AFTER INSERT ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessenbindung` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_interessenbindung_log_upd`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_log_upd` AFTER UPDATE ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate freigabe from mandat to organisation
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      -- Organisation
      UPDATE `organisation`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        id=NEW.organisation_id;

      -- interessenbindung_jahr
      UPDATE `interessenbindung_jahr`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        interessenbindung_id=NEW.id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessenbindung` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_interessenbindung_log_del_before`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_log_del_before` BEFORE DELETE ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessenbindung` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_interessenbindung_log_del_after`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_log_del_after` AFTER DELETE ON `interessenbindung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessenbindung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- interessenbindung_jahr triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_interessenbindung_jahr_log_ins`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_jahr_log_ins` AFTER INSERT ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessenbindung_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_interessenbindung_jahr_log_upd`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_jahr_log_upd` AFTER UPDATE ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessenbindung_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_interessenbindung_jahr_log_del_before`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_jahr_log_del_before` BEFORE DELETE ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessenbindung_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessenbindung_jahr` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_interessenbindung_jahr_log_del_after`;
delimiter //
CREATE TRIGGER `trg_interessenbindung_jahr_log_del_after` AFTER DELETE ON `interessenbindung_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessenbindung_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- interessengruppe triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_interessengruppe_log_ins`;
delimiter //
CREATE TRIGGER `trg_interessengruppe_log_ins` AFTER INSERT ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `interessengruppe` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_interessengruppe_log_upd`;
delimiter //
CREATE TRIGGER `trg_interessengruppe_log_upd` AFTER UPDATE ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'update', null, NOW(), null FROM `interessengruppe` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_interessengruppe_log_del_before`;
delimiter //
CREATE TRIGGER `trg_interessengruppe_log_del_before` BEFORE DELETE ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `interessengruppe_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `interessengruppe` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_interessengruppe_log_del_after`;
delimiter //
CREATE TRIGGER `trg_interessengruppe_log_del_after` AFTER DELETE ON `interessengruppe`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `interessengruppe_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- in_kommission triggers

DROP TRIGGER IF EXISTS `trg_in_kommission_log_ins`;
delimiter //
CREATE TRIGGER `trg_in_kommission_log_ins` AFTER INSERT ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `in_kommission` WHERE id = NEW.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- Fill parlamentarier.kommissionen on change
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.id=NEW.parlamentarier_id;
  UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.parlamentarier_id=NEW.parlamentarier_id;
  SET @disable_table_logging = NULL;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_in_kommission_log_upd`;
delimiter //
CREATE TRIGGER `trg_in_kommission_log_upd` AFTER UPDATE ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'update', null, NOW(), null FROM `in_kommission` WHERE id = NEW.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- Fill parlamentarier.kommissionen on change
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.id=NEW.parlamentarier_id OR p.id=OLD.parlamentarier_id;
  UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NEW.updated_date,
      p.updated_visa = CONCAT(NEW.updated_visa, '*')
    WHERE p.parlamentarier_id=NEW.parlamentarier_id OR p.parlamentarier_id=OLD.parlamentarier_id;
  SET @disable_table_logging = NULL;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_in_kommission_log_del_before`;
delimiter //
CREATE TRIGGER `trg_in_kommission_log_del_before` BEFORE DELETE ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `in_kommission_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `in_kommission` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_in_kommission_log_del_after`;
delimiter //
CREATE TRIGGER `trg_in_kommission_log_del_after` AFTER DELETE ON `in_kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `in_kommission_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- Fill parlamentarier.kommissionen on change
  SET @disable_table_logging = 1;
  UPDATE `parlamentarier` p
    SET p.kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NOW(),
      p.updated_visa = CONCAT('*')
    WHERE p.id=OLD.parlamentarier_id;
  UPDATE `zutrittsberechtigung` p
    SET p.parlamentarier_kommissionen=(SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id=p.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id),
      p.updated_date = NOW(),
      p.updated_visa = CONCAT('*')
    WHERE p.parlamentarier_id=OLD.parlamentarier_id;
  SET @disable_table_logging = NULL;
END
//
delimiter ;

-- kommission triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_kommission_log_ins`;
delimiter //
CREATE TRIGGER `trg_kommission_log_ins` AFTER INSERT ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kommission` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_kommission_log_upd`;
delimiter //
CREATE TRIGGER `trg_kommission_log_upd` AFTER UPDATE ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kommission` WHERE id = NEW.id ;

  -- Propagate freigabe from kommission to his in_kommission
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      -- in_kommission
      -- Avoid ERROR 1442 (HY000): Cant update table 'in_kommission' in stored function/trigger because it is already used by statement which invoked this stored function/trigger
      -- SET @disable_parlamentarier_kommissionen_update = 1;
      UPDATE `in_kommission`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        kommission_id=NEW.id AND bis IS NULL;
      -- SET @disable_parlamentarier_kommissionen_update = NULL;
  END IF;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_kommission_log_del_before`;
delimiter //
CREATE TRIGGER `trg_kommission_log_del_before` BEFORE DELETE ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kommission_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kommission` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_kommission_log_del_after`;
delimiter //
CREATE TRIGGER `trg_kommission_log_del_after` AFTER DELETE ON `kommission`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kommission_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- mandat triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_mandat_log_ins`;
delimiter //
CREATE TRIGGER `trg_mandat_log_ins` AFTER INSERT ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mandat` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_mandat_log_upd`;
delimiter //
CREATE TRIGGER `trg_mandat_log_upd` AFTER UPDATE ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate freigabe from mandat to organisation
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      UPDATE `organisation`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        id=NEW.organisation_id;

      -- Mandat_jahr
      UPDATE `mandat_jahr`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        mandat_id=NEW.id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mandat` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_mandat_log_del_before`;
delimiter //
CREATE TRIGGER `trg_mandat_log_del_before` BEFORE DELETE ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mandat` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_mandat_log_del_after`;
delimiter //
CREATE TRIGGER `trg_mandat_log_del_after` AFTER DELETE ON `mandat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mandat_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- mandat_jahr triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_mandat_jahr_log_ins`;
delimiter //
CREATE TRIGGER `trg_mandat_jahr_log_ins` AFTER INSERT ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mandat_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_mandat_jahr_log_upd`;
delimiter //
CREATE TRIGGER `trg_mandat_jahr_log_upd` AFTER UPDATE ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mandat_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_mandat_jahr_log_del_before`;
delimiter //
CREATE TRIGGER `trg_mandat_jahr_log_del_before` BEFORE DELETE ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mandat_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mandat_jahr` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_mandat_jahr_log_del_after`;
delimiter //
CREATE TRIGGER `trg_mandat_jahr_log_del_after` AFTER DELETE ON `mandat_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mandat_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- organisation triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_organisation_log_ins`;
delimiter //
CREATE TRIGGER `trg_organisation_log_ins` AFTER INSERT ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_log_upd`;
delimiter //
CREATE TRIGGER `trg_organisation_log_upd` AFTER UPDATE ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_log_del_before`;
delimiter //
CREATE TRIGGER `trg_organisation_log_del_before` BEFORE DELETE ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_organisation_log_del_after`;
delimiter //
CREATE TRIGGER `trg_organisation_log_del_after` AFTER DELETE ON `organisation`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- organisation special triggers

-- Before MySQL 5.5
-- DROP TRIGGER IF EXISTS trg_organisation_name_ins;
-- delimiter //
-- CREATE TRIGGER `trg_organisation_name_ins` BEFORE INSERT ON `organisation`
-- FOR EACH ROW
-- thisTrigger: BEGIN
--     IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
--     if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
--         call organisation_name_de_fr_it_must_be_set;
--     END if;
-- END
-- //
-- delimiter ;

-- DROP TRIGGER IF EXISTS trg_organisation_name_upd;
-- delimiter //
-- CREATE TRIGGER `trg_organisation_name_upd` BEFORE UPDATE ON `organisation`
-- FOR EACH ROW
-- thisTrigger: BEGIN
--     IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
--     if new.name_de IS NULL AND new.name_fr IS NULL AND new.name_it IS NULL then
--         call organisation_name_de_fr_it_must_be_set;
--     END if;
-- END
-- //
-- delimiter ;

-- MySQL 5.5 required
-- SIGNAL problems http://www.mysqltutorial.org/mysql-signal-resignal/
delimiter //
DROP TRIGGER IF EXISTS `trg_organisation_name_ins_before` //
CREATE TRIGGER `trg_organisation_name_ins_before` BEFORE INSERT ON organisation
FOR EACH ROW
thisTrigger: BEGIN
    DECLARE msg VARCHAR(255);
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
    IF NEW.name_de IS NULL AND NEW.name_fr IS NULL AND NEW.name_it IS NULL THEN
        SET msg = CONCAT('NameError: Either name_de, name_fr or name_it must be set. ID: ', CAST(NEW.id as CHAR));
        SIGNAL SQLSTATE '45000' SET message_text = msg;
    END IF;
END //
delimiter ;

delimiter //
DROP TRIGGER IF EXISTS `trg_organisation_name_upd_before` //
CREATE TRIGGER `trg_organisation_name_upd_before` BEFORE UPDATE ON organisation
FOR EACH ROW
thisTrigger: BEGIN
    DECLARE msg VARCHAR(255);
    IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
    IF NEW.name_de IS NULL AND NEW.name_fr IS NULL AND NEW.name_it IS NULL THEN
        SET msg = CONCAT('NameError: Either name_de, name_fr or name_it must be set. ID: ', CAST(NEW.id as CHAR));
        SIGNAL SQLSTATE '45000' SET message_text = msg;
    END IF;
END //
delimiter ;

-- organisation_beziehung triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_ins`;
delimiter //
CREATE TRIGGER `trg_organisation_beziehung_log_ins` AFTER INSERT ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_beziehung` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_upd`;
delimiter //
CREATE TRIGGER `trg_organisation_beziehung_log_upd` AFTER UPDATE ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_beziehung` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_del_before`;
delimiter //
CREATE TRIGGER `trg_organisation_beziehung_log_del_before` BEFORE DELETE ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_beziehung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_beziehung` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_organisation_beziehung_log_del_after`;
delimiter //
CREATE TRIGGER `trg_organisation_beziehung_log_del_after` AFTER DELETE ON `organisation_beziehung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_beziehung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- organisation_anhang triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_ins`;
delimiter //
CREATE TRIGGER `trg_organisation_anhang_log_ins` AFTER INSERT ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_anhang` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_upd`;
delimiter //
CREATE TRIGGER `trg_organisation_anhang_log_upd` AFTER UPDATE ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_anhang` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_del_before`;
delimiter //
CREATE TRIGGER `trg_organisation_anhang_log_del_before` BEFORE DELETE ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_anhang` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_organisation_anhang_log_del_after`;
delimiter //
CREATE TRIGGER `trg_organisation_anhang_log_del_after` AFTER DELETE ON `organisation_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- organisation_jahr triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_ins`;
delimiter //
CREATE TRIGGER `trg_organisation_jahr_log_ins` AFTER INSERT ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `organisation_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_upd`;
delimiter //
CREATE TRIGGER `trg_organisation_jahr_log_upd` AFTER UPDATE ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `organisation_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_del_before`;
delimiter //
CREATE TRIGGER `trg_organisation_jahr_log_del_before` BEFORE DELETE ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `organisation_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `organisation_jahr` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_organisation_jahr_log_del_after`;
delimiter //
CREATE TRIGGER `trg_organisation_jahr_log_del_after` AFTER DELETE ON `organisation_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `organisation_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- parlamentarier triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_parlamentarier_log_ins`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_log_ins` AFTER INSERT ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `parlamentarier` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_parlamentarier_log_upd`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_log_upd` AFTER UPDATE ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate authorization from parlamentarier to his interessenbindungen
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `interessenbindung`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        parlamentarier_id=NEW.id AND bis IS NULL;
  END IF;

  -- Propagate freigabe from parlamentarier to his interessenbindungen
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      -- Interessenbindung
      UPDATE `interessenbindung`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        parlamentarier_id=NEW.id AND bis IS NULL;

      -- TODO organisationen von interessenbindungen?
      -- TODO set non-null freigabe_datum only if freigabe_datum IS NULL

      -- zutrittsberechtigung
      UPDATE `zutrittsberechtigung`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        parlamentarier_id=NEW.id AND bis IS NULL;

      -- TODO organisationen von zutrittsberechtigten?

      -- in_kommission
      -- Avoid ERROR 1442 (HY000): Cant update table 'in_kommission' in stored function/trigger because it is already used by statement which invoked this stored function/trigger
--    SET @disable_parlamentarier_kommissionen_update = 1;
--    UPDATE `in_kommission`
--      SET
--      freigabe_datum = NEW.freigabe_datum,
--      freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
--      updated_date = NEW.updated_date,
--      updated_visa = CONCAT(NEW.updated_visa, '*')
--      WHERE
--      parlamentarier_id=NEW.id AND bis IS NULL;
--    SET @disable_parlamentarier_kommissionen_update = NULL;
  END IF;

    -- Propagate im_rat_bis from parlamentarier to his zutrittsberechtigte, if NULL or SAME date as parlamentarier
  IF OLD.im_rat_bis <> NEW.im_rat_bis
    OR (OLD.im_rat_bis IS NULL AND NEW.im_rat_bis IS NOT NULL)
    OR (OLD.im_rat_bis IS NOT NULL AND NEW.im_rat_bis IS NULL) THEN

    -- zutrittsberechtigung
    UPDATE `zutrittsberechtigung`
      SET
      `notizen` = CONCAT_WS('\n\n', CONCAT(DATE_FORMAT(NEW.updated_date,'%d.%m.%Y'), '/', IFNULL(NEW.updated_visa, '?') , '*: Parlamentarier ', NEW.vorname, ' ', NEW.nachname, ' nicht mehr im Rat. Zutrittsberechtigung erloschen. Bis-Datum von ', IFNULL(DATE_FORMAT(bis,'%d.%m.%Y'), 'NULL'), ' auf ',  IFNULL(DATE_FORMAT(NEW.im_rat_bis,'%d.%m.%Y'), 'NULL'), ' gesetzt.'),`notizen`),
      bis = NEW.im_rat_bis,
      updated_date = NEW.updated_date,
      updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
      parlamentarier_id=NEW.id AND (bis IS NULL OR bis = OLD.im_rat_bis);

  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'update', null, NOW(), null FROM `parlamentarier` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_parlamentarier_log_del_before`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_log_del_before` BEFORE DELETE ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `parlamentarier` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_parlamentarier_log_del_after`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_log_del_after` AFTER DELETE ON `parlamentarier`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `parlamentarier_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- parlamentarier_anhang  triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_ins`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_anhang_log_ins` AFTER INSERT ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_upd`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_anhang_log_upd` AFTER UPDATE ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_del_before`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_anhang_log_del_before` BEFORE DELETE ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `parlamentarier_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `parlamentarier_anhang` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_parlamentarier_anhang_log_del_after`;
delimiter //
CREATE TRIGGER `trg_parlamentarier_anhang_log_del_after` AFTER DELETE ON `parlamentarier_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `parlamentarier_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- partei triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_partei_log_ins`;
delimiter //
CREATE TRIGGER `trg_partei_log_ins` AFTER INSERT ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `partei` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_partei_log_upd`;
delimiter //
CREATE TRIGGER `trg_partei_log_upd` AFTER UPDATE ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'update', null, NOW(), null FROM `partei` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_partei_log_del_before`;
delimiter //
CREATE TRIGGER `trg_partei_log_del_before` BEFORE DELETE ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `partei_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `partei` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_partei_log_del_after`;
delimiter //
CREATE TRIGGER `trg_partei_log_del_after` AFTER DELETE ON `partei`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `partei_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- fraktion triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_fraktion_log_ins`;
delimiter //
CREATE TRIGGER `trg_fraktion_log_ins` AFTER INSERT ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `fraktion` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_fraktion_log_upd`;
delimiter //
CREATE TRIGGER `trg_fraktion_log_upd` AFTER UPDATE ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'update', null, NOW(), null FROM `fraktion` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_fraktion_log_del_before`;
delimiter //
CREATE TRIGGER `trg_fraktion_log_del_before` BEFORE DELETE ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `fraktion_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `fraktion` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_fraktion_log_del_after`;
delimiter //
CREATE TRIGGER `trg_fraktion_log_del_after` AFTER DELETE ON `fraktion`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `fraktion_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- zutrittsberechtigung triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_ins`;
delimiter //
CREATE TRIGGER `trg_zutrittsberechtigung_log_ins` AFTER INSERT ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_upd`;
delimiter //
CREATE TRIGGER `trg_zutrittsberechtigung_log_upd` AFTER UPDATE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate freigabe from zutrittsberechtigung to person
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      -- Person
      UPDATE `person`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        person.id=NEW.person_id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'update', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_del_before`;
delimiter //
CREATE TRIGGER `trg_zutrittsberechtigung_log_del_before` BEFORE DELETE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `zutrittsberechtigung_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `zutrittsberechtigung` WHERE id = OLD.id ;

  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;
  -- propagate parlamentarier_kommissionen to person
   UPDATE person
    SET
    parlamentarier_kommissionen = NULL,
    zutrittsberechtigung_von = NULL,
    updated_visa = CONCAT(OLD.updated_visa, '*'),
    update_date = OLD.updated_date
    WHERE person.id = OLD.person_id;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_log_del_after`;
delimiter //
CREATE TRIGGER `trg_zutrittsberechtigung_log_del_after` AFTER DELETE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `zutrittsberechtigung_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- zutrittsberechtigung before triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_before_ins`;
delimiter //
CREATE TRIGGER `trg_zutrittsberechtigung_before_ins` BEFORE INSERT ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Fill parlamentarier.kommissionen on change
  -- Other triggers are trg_in_kommission_log_* trigger
  SET NEW.parlamentarier_kommissionen = (SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id = NEW.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id);

  -- propagate parlamentarier_kommissionen to person
   UPDATE person
    SET
    parlamentarier_kommissionen = NEW.parlamentarier_kommissionen,
    zutrittsberechtigung_von = (SELECT CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) FROM parlamentarier WHERE parlamentarier.id = NEW.parlamentarier_id),
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis IS NULL OR NEW.bis > NOW());
   UPDATE person
    SET
    parlamentarier_kommissionen = NULL,
    zutrittsberechtigung_von = NULL,
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis < NOW());
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_zutrittsberechtigung_before_upd`;
delimiter //
CREATE TRIGGER `trg_zutrittsberechtigung_before_upd` BEFORE UPDATE ON `zutrittsberechtigung`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  IF @disable_parlamentarier_kommissionen_update IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Fill parlamentarier.kommissionen on change
  -- Other triggers are trg_in_kommission_log_* trigger
  SET NEW.parlamentarier_kommissionen = (SELECT GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') FROM in_kommission ik  LEFT JOIN kommission k ON ik.kommission_id=k.id WHERE ik.parlamentarier_id = NEW.parlamentarier_id AND ik.bis IS NULL GROUP BY ik.parlamentarier_id);

  -- propagate parlamentarier_kommissionen to person
   UPDATE person
    SET
    parlamentarier_kommissionen = NEW.parlamentarier_kommissionen,
    zutrittsberechtigung_von = (SELECT CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) FROM parlamentarier WHERE parlamentarier.id = NEW.parlamentarier_id),
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis IS NULL OR NEW.bis > NOW());
   UPDATE person
    SET
    parlamentarier_kommissionen = NULL,
    zutrittsberechtigung_von = NULL,
    updated_visa = CONCAT(NEW.updated_visa, '*'),
    updated_date = NEW.updated_date
    WHERE person.id = NEW.person_id AND (NEW.bis < NOW());
END
//
delimiter ;

-- person triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_person_log_ins`;
delimiter //
CREATE TRIGGER `trg_person_log_ins` AFTER INSERT ON `person`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `person` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_person_log_upd`;
delimiter //
CREATE TRIGGER `trg_person_log_upd` AFTER UPDATE ON `person`
FOR EACH ROW
thisTrigger: BEGIN

  IF @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;

  -- Propagate authorization from person to his mandate
  IF OLD.autorisiert_datum <> NEW.autorisiert_datum
    OR (OLD.autorisiert_datum IS NULL AND NEW.autorisiert_datum IS NOT NULL)
    OR (OLD.autorisiert_datum IS NOT NULL AND NEW.autorisiert_datum IS NULL) THEN
    UPDATE `mandat`
      SET
        autorisiert_datum = NEW.autorisiert_datum,
        autorisiert_visa = CONCAT(NEW.autorisiert_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
      WHERE
        person_id=NEW.id AND bis IS NULL;
  END IF;

  -- Propagate freigabe from person to his mandate
  IF OLD.freigabe_datum <> NEW.freigabe_datum
    OR (OLD.freigabe_datum IS NULL AND NEW.freigabe_datum IS NOT NULL)
    OR (OLD.freigabe_datum IS NOT NULL AND NEW.freigabe_datum IS NULL) THEN
      -- Person
      UPDATE `mandat`
        SET
        freigabe_datum = NEW.freigabe_datum,
        freigabe_visa = CONCAT(NEW.freigabe_visa, '*'),
        updated_date = NEW.updated_date,
        updated_visa = CONCAT(NEW.updated_visa, '*')
        WHERE
        person_id=NEW.id;
  END IF;

  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'update', null, NOW(), null FROM `person` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_person_log_del_before`;
delimiter //
CREATE TRIGGER `trg_person_log_del_before` BEFORE DELETE ON `person`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `person` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_person_log_del_after`;
delimiter //
CREATE TRIGGER `trg_person_log_del_after` AFTER DELETE ON `person`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `person_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- person_anhang  triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_person_anhang_log_ins`;
delimiter //
CREATE TRIGGER `trg_person_anhang_log_ins` AFTER INSERT ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `person_anhang` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_person_anhang_log_upd`;
delimiter //
CREATE TRIGGER `trg_person_anhang_log_upd` AFTER UPDATE ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'update', null, NOW(), null FROM `person_anhang` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_person_anhang_log_del_before`;
delimiter //
CREATE TRIGGER `trg_person_anhang_log_del_before` BEFORE DELETE ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `person_anhang_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `person_anhang` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_person_anhang_log_del_after`;
delimiter //
CREATE TRIGGER `trg_person_anhang_log_del_after` AFTER DELETE ON `person_anhang`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `person_anhang_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- mil_grad triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_mil_grad_log_ins`;
delimiter //
CREATE TRIGGER `trg_mil_grad_log_ins` AFTER INSERT ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `mil_grad` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_mil_grad_log_upd`;
delimiter //
CREATE TRIGGER `trg_mil_grad_log_upd` AFTER UPDATE ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'update', null, NOW(), null FROM `mil_grad` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_mil_grad_log_del_before`;
delimiter //
CREATE TRIGGER `trg_mil_grad_log_del_before` BEFORE DELETE ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `mil_grad_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `mil_grad` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_mil_grad_log_del_after`;
delimiter //
CREATE TRIGGER `trg_mil_grad_log_del_after` AFTER DELETE ON `mil_grad`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `mil_grad_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- rat triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_rat_log_ins`;
delimiter //
CREATE TRIGGER `trg_rat_log_ins` AFTER INSERT ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `rat` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_rat_log_upd`;
delimiter //
CREATE TRIGGER `trg_rat_log_upd` AFTER UPDATE ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'update', null, NOW(), null FROM `rat` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_rat_log_del_before`;
delimiter //
CREATE TRIGGER `trg_rat_log_del_before` BEFORE DELETE ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `rat_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `rat` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_rat_log_del_after`;
delimiter //
CREATE TRIGGER `trg_rat_log_del_after` AFTER DELETE ON `rat`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `rat_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- kanton triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_kanton_log_ins`;
delimiter //
CREATE TRIGGER `trg_kanton_log_ins` AFTER INSERT ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kanton` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_kanton_log_upd`;
delimiter //
CREATE TRIGGER `trg_kanton_log_upd` AFTER UPDATE ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kanton` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_kanton_log_del_before`;
delimiter //
CREATE TRIGGER `trg_kanton_log_del_before` BEFORE DELETE ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kanton` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_kanton_log_del_after`;
delimiter //
CREATE TRIGGER `trg_kanton_log_del_after` AFTER DELETE ON `kanton`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kanton_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- kanton_jahr triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_ins`;
delimiter //
CREATE TRIGGER `trg_kanton_jahr_log_ins` AFTER INSERT ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `kanton_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_upd`;
delimiter //
CREATE TRIGGER `trg_kanton_jahr_log_upd` AFTER UPDATE ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'update', null, NOW(), null FROM `kanton_jahr` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_del_before`;
delimiter //
CREATE TRIGGER `trg_kanton_jahr_log_del_before` BEFORE DELETE ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `kanton_jahr_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `kanton_jahr` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_kanton_jahr_log_del_after`;
delimiter //
CREATE TRIGGER `trg_kanton_jahr_log_del_after` AFTER DELETE ON `kanton_jahr`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `kanton_jahr_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- settings triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_settings_log_ins`;
delimiter //
CREATE TRIGGER `trg_settings_log_ins` AFTER INSERT ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `settings` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_settings_log_upd`;
delimiter //
CREATE TRIGGER `trg_settings_log_upd` AFTER UPDATE ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'update', null, NOW(), null FROM `settings` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_settings_log_del_before`;
delimiter //
CREATE TRIGGER `trg_settings_log_del_before` BEFORE DELETE ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `settings` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_settings_log_del_after`;
delimiter //
CREATE TRIGGER `trg_settings_log_del_after` AFTER DELETE ON `settings`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `settings_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- settings_category triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_settings_category_log_ins`;
delimiter //
CREATE TRIGGER `trg_settings_category_log_ins` AFTER INSERT ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `settings_category` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_settings_category_log_upd`;
delimiter //
CREATE TRIGGER `trg_settings_category_log_upd` AFTER UPDATE ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'update', null, NOW(), null FROM `settings_category` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_settings_category_log_del_before`;
delimiter //
CREATE TRIGGER `trg_settings_category_log_del_before` BEFORE DELETE ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `settings_category_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `settings_category` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_settings_category_log_del_after`;
delimiter //
CREATE TRIGGER `trg_settings_category_log_del_after` AFTER DELETE ON `settings_category`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `settings_category_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;


-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_translation_source_log_ins`;
delimiter //
CREATE TRIGGER `trg_translation_source_log_ins` AFTER INSERT ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `translation_source` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_translation_source_log_upd`;
delimiter //
CREATE TRIGGER `trg_translation_source_log_upd` AFTER UPDATE ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'update', null, NOW(), null FROM `translation_source` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_translation_source_log_del_before`;
delimiter //
CREATE TRIGGER `trg_translation_source_log_del_before` BEFORE DELETE ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_source_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `translation_source` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_translation_source_log_del_after`;
delimiter //
CREATE TRIGGER `trg_translation_source_log_del_after` AFTER DELETE ON `translation_source`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `translation_source_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

-- translation_target triggers

-- Ref: http://stackoverflow.com/questions/6787794/how-to-log-all-changes-in-a-mysql-table-to-a-second-one
DROP TRIGGER IF EXISTS `trg_translation_target_log_ins`;
delimiter //
CREATE TRIGGER `trg_translation_target_log_ins` AFTER INSERT ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'insert', null, NOW(), null FROM `translation_target` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_translation_target_log_upd`;
delimiter //
CREATE TRIGGER `trg_translation_target_log_upd` AFTER UPDATE ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'update', null, NOW(), null FROM `translation_target` WHERE id = NEW.id ;
END
//
delimiter ;

DROP TRIGGER IF EXISTS `trg_translation_target_log_del_before`;
delimiter //
CREATE TRIGGER `trg_translation_target_log_del_before` BEFORE DELETE ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  INSERT INTO `translation_target_log`
    SELECT *, null, 'delete', null, NOW(), null FROM `translation_target` WHERE id = OLD.id ;
END
//
delimiter ;

-- id and action = 'delete' are unique
DROP TRIGGER IF EXISTS `trg_translation_target_log_del_after`;
delimiter //
CREATE TRIGGER `trg_translation_target_log_del_after` AFTER DELETE ON `translation_target`
FOR EACH ROW
thisTrigger: BEGIN
  IF @disable_table_logging IS NOT NULL OR @disable_triggers IS NOT NULL THEN LEAVE thisTrigger; END IF;
  UPDATE `translation_target_log`
    SET `state` = 'OK'
    WHERE `id` = OLD.`id` AND `created_date` = OLD.`created_date` AND action = 'delete';
END
//
delimiter ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET SQL_MODE=@OLD_SQL_MODE;
