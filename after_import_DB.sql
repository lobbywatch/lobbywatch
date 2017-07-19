-- After import DB script
-- This script will automatically be called after DB imports.
-- It can be used to automatically correct the DB

-- Egger, Thomas ist ab 15.5.2017 selbst Nationalrat. Lösche falsche Zutrittsberechtigung von Engler, Stefan
-- SELECT * FROM `zutrittsberechtigung` WHERE `parlamentarier_id` = 223 AND `person_id` = 275 AND von BETWEEN STR_TO_DATE('15.06.2017','%d.%m.%Y') AND STR_TO_DATE('01.12.2019','%d.%m.%Y') AND bis IS NULL;
DELETE FROM `zutrittsberechtigung` WHERE `parlamentarier_id` = 223 AND `person_id` = 275 AND von BETWEEN STR_TO_DATE('15.06.2017','%d.%m.%Y') AND STR_TO_DATE('01.12.2019','%d.%m.%Y') AND bis IS NULL;
