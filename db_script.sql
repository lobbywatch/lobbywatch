-- http://explainextended.com/2010/11/03/10-things-in-mysql-that-wont-work-as-expected/

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

-- 07.07.2014

-- Parlamentarier without Zutrittsberechtigungen
select * from parlamentarier where not exists (select * from zutrittsberechtigung where zutrittsberechtigung.parlamentarier_id = parlamentarier.id);

UPDATE branche SET `symbol_klein_rel`='branche_symbole/default_branche.png' WHERE `symbol_klein_rel` IS NULL;

-- 03.08.2014
-- Alle freigeben
UPDATE branche SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
-- UPDATE interessenbindung SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE interessengruppe SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
-- UPDATE in_kommission SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
-- UPDATE kommission SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
-- UPDATE mandat SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE organisation SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE organisation_beziehung SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE organisation_jahr SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
-- UPDATE parlamentarier SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE partei SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE fraktion SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE rat SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE kanton SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
UPDATE kanton_jahr SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';
-- UPDATE zutrittsberechtigung SET freigabe_datum = NOW(), freigabe_visa='roland*', updated_date = NOW(), updated_visa= 'roland*';

-- Alle ENT-freigeben
UPDATE branche SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE interessenbindung SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE interessengruppe SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE in_kommission SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE kommission SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE mandat SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE organisation SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE organisation_beziehung SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE organisation_jahr SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE parlamentarier SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE partei SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE fraktion SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE rat SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE kanton SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE kanton_jahr SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';
UPDATE zutrittsberechtigung SET freigabe_datum = NULL, freigabe_visa=NULL, updated_date = NOW(), updated_visa= '*';

-- 16.09.2014

-- Alle freigeben
SET @freigabe_date = STR_TO_DATE('16.09.2014', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
-- UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
-- UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name;

-- 26.11.2014

-- Alle freigeben
SET @freigabe_date = STR_TO_DATE('26.11.2014', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id = 3;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id = @kommission_id AND freigabe_datum IS NULL;
UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id = @kommission_id AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id = @kommission_id AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung

CALL takeSnapshot('roland', 'UREK published in_kommission_fixed');

CALL `refreshMaterializedViews`();

-- 27.11.2014

SET @freigabe_date = STR_TO_DATE('26.11.2014', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id = 3;
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id = @kommission_id AND freigabe_datum IS NULL;

CALL takeSnapshot('roland', 'UREK published in_kommission freigabe fixed');

CALL `refreshMaterializedViews`();

-- 01.12.2014

-- http://stackoverflow.com/questions/2654720/drupal-moving-module-folder

UPDATE system SET filename = REPLACE(filename, 'sites/all/modules/lobbywatch', 'sites/all/modules/contrib');
UPDATE registry SET filename = REPLACE(filename, 'sites/all/modules', 'sites/all/modules/contrib');
UPDATE registry_file SET filename = REPLACE(filename, 'sites/all/modules', 'sites/all/modules/contrib')


-- 21.03.2015

-- Alle freigeben
SET @freigabe_date = STR_TO_DATE('21.03.2015', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id_nr = 11;
SET @kommission_id_sr = 52;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung

CALL `refreshMaterializedViews`();

CALL takeSnapshot('roland', 'WAK published');

-- 29.06.2015

-- Alle freigeben
SET @freigabe_date = STR_TO_DATE('29.06.2015', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id_nr = 11;
SET @kommission_id_sr = 52;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- UPDATE interessenbindung_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- TODO
-- UPDATE mandat_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- TODO
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung

CALL `refreshMaterializedViews`();

CALL takeSnapshot('roland', 'WAK published');

-- 16.08.2015

-- Alle freigeben
SET @freigabe_date = STR_TO_DATE('16.08.2015', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id_nr = 7;
SET @kommission_id_sr = 50;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung
-- UPDATE interessenbindung_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by interessenbindung
-- UPDATE mandat_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by mandat

CALL takeSnapshot('roland', 'SiK published');

CALL `refreshMaterializedViews`();

-- 06.12.2015

-- 6 Parlamentarier freigeben
SET @freigabe_date = STR_TO_DATE('07.12.2015', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id_nr = 5;
SET @kommission_id_sr = 49;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL
  -- AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
  AND id IN (45, 233, 132, 170, 183, 58);
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung
-- UPDATE interessenbindung_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by interessenbindung
-- UPDATE mandat_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by mandat

-- 20.12.2015 Fix empty updated_date

SELECT COUNT(*) FROM parlamentarier WHERE updated_date IS NULL;

SET @disable_triggers = 1;
UPDATE parlamentarier SET updated_date=STR_TO_DATE('19.12.2015 20:00:00','%d.%m.%Y %T') WHERE updated_date IS NULL;
SET @disable_triggers = NULL;

SELECT COUNT(*) FROM in_kommission WHERE updated_date IS NULL;
SELECT COUNT(*) FROM mandat WHERE updated_date IS NULL;

SET @disable_triggers = 1;
UPDATE in_kommission SET updated_date=STR_TO_DATE('19.12.2015 20:00:00','%d.%m.%Y %T') WHERE updated_date IS NULL;
UPDATE mandat SET updated_date=STR_TO_DATE('19.12.2015 20:00:00','%d.%m.%Y %T') WHERE updated_date IS NULL;
SET @disable_triggers = NULL;

select count(*) from organisation where uid IS NOT NULL AND (rechtsform_handelsregister='' OR rechtsform_handelsregister IS NULL);

select id, uid, name_de from organisation where uid IS NOT NULL AND (rechtsform_handelsregister='' OR rechtsform_handelsregister IS NULL)

-- 25.01.2016

SELECT id, anzeige_name, `kommissionen` FROM `v_parlamentarier` WHERE (im_rat_bis IS NULL OR im_rat_bis > NOW());

SELECT id, anzeige_name, parlamentarier_kommissionen FROM `v_zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND parlamentarier_kommissionen IS NULL;

SELECT id, parlamentarier_kommissionen FROM `zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND parlamentarier_kommissionen IS NULL;

-- UPDATE zutrittsberechtigung SET updated_visa='roland', updated_date=NOW() WHERE id IN (SELECT v.zutrittsberechtigung_id /*, id, parlamentarier_kommissionen, parlamentarier_kommissionen_zutrittsberechtigung*/ FROM `v_zutrittsberechtigung` v WHERE (v.bis IS NULL OR v.bis > NOW()) AND (v.parlamentarier_kommissionen IS NULL OR v.parlamentarier_kommissionen_zutrittsberechtigung IS NULL));

SELECT zutrittsberechtigung_id, id, parlamentarier_id, updated_date, updated_date_person, parlamentarier_kommissionen, parlamentarier_kommissionen_zutrittsberechtigung FROM `v_zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND (parlamentarier_kommissionen IS NULL OR parlamentarier_kommissionen_zutrittsberechtigung IS NULL);

-- Update zb.kommissionen and person.kommissionen nicht à jour

-- UPDATE parlamentarier SET updated_visa='roland', updated_date=NOW() WHERE id IN (SELECT v.parlamentarier_id /*, id, parlamentarier_kommissionen, parlamentarier_kommissionen_zutrittsberechtigung*/ FROM `v_zutrittsberechtigung` v WHERE (v.bis IS NULL OR v.bis > NOW()) AND (v.parlamentarier_kommissionen IS NULL OR v.parlamentarier_kommissionen_zutrittsberechtigung IS NULL));

UPDATE zutrittsberechtigung SET updated_visa='roland', updated_date=NOW() WHERE id IN (SELECT zutrittsberechtigung_id/*, id, parlamentarier_kommissionen_zutrittsberechtigung, parlamentarier_id, parlamentarier_kommissionen*/ FROM `v_zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND (parlamentarier_kommissionen IS NULL OR parlamentarier_kommissionen_zutrittsberechtigung IS NULL));

SELECT zutrittsberechtigung_id, id, bis, parlamentarier_kommissionen_zutrittsberechtigung, parlamentarier_id, parlamentarier_kommissionen FROM `v_zutrittsberechtigung` WHERE (bis IS NULL OR bis > NOW()) AND (parlamentarier_kommissionen IS NULL OR parlamentarier_kommissionen_zutrittsberechtigung IS NULL);

-- Zutrittsberechtigung.bis nicht à jour
-- SELECT z.zutrittsberechtigung_id, z.id, parlamentarier_id, z.bis, p.im_rat_bis FROM `v_zutrittsberechtigung` z, v_parlamentarier p WHERE z.parlamentarier_id=p.id AND z.bis IS NULL AND p.im_rat_bis IS NOT NULL;

SELECT parlamentarier_id, p.anzeige_name as parlamentarier, p.rat, z.zutrittsberechtigung_id, z.id as person_id, z.anzeige_name as person, z.bis, p.im_rat_bis, z.created_visa, z.created_date FROM `v_zutrittsberechtigung` z, v_parlamentarier p WHERE z.parlamentarier_id=p.id AND z.bis IS NULL AND p.im_rat_bis IS NOT NULL ORDER BY z.zutrittsberechtigung_id;

UPDATE parlamentarier SET updated_visa='roland', updated_date=NOW() WHERE id IN (SELECT /*z.zutrittsberechtigung_id, z.id,*/ parlamentarier_id /*, z.bis, p.im_rat_bis*/ FROM `v_zutrittsberechtigung` z, v_parlamentarier p WHERE z.parlamentarier_id=p.id AND z.bis IS NULL AND p.im_rat_bis IS NOT NULL);

-- Parlamentarier Kommissionen not up-to-date

SELECT id, p.anzeige_name as parlamentarier, p.rat, p.kommissionen, p.kommissionen_abkuerzung FROM v_parlamentarier p WHERE p.kommissionen <> p.kommissionen_abkuerzung OR (p.kommissionen IS NULL AND p.kommissionen_abkuerzung IS NOT NULL) OR (p.kommissionen IS NOT NULL AND p.kommissionen_abkuerzung IS NULL);

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

-- 04.02.2016
-- 10 Parlamentarier freigeben
SET @freigabe_date = STR_TO_DATE('04.02.2016', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
-- SET @kommission_id_nr = 5;
-- SET @kommission_id_sr = 49;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL
  -- AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
  AND id IN (262, 263, 265, 266, 267, 268, 270, 271, 276, 72);
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung
-- UPDATE interessenbindung_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by interessenbindung
-- UPDATE mandat_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by mandat

-- 21.03.2016
-- 10 Parlamentarier freigeben
SET @freigabe_date = STR_TO_DATE('21.03.2016', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
-- SET @kommission_id_nr = 5;
-- SET @kommission_id_sr = 49;
UPDATE branche SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE interessenbindung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
UPDATE interessengruppe SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by kommission
-- UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE id IN (@kommission_id_nr, @kommission_id_sr) AND freigabe_datum IS NULL;
-- UPDATE mandat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name; -- triggered by zutrittsberechtigung
UPDATE organisation SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_beziehung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE organisation_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE parlamentarier SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL
  -- AND id IN (SELECT parlamentarier_id FROM in_kommission WHERE kommission_id IN (@kommission_id_nr, @kommission_id_sr) AND (in_kommission.bis IS NULL OR in_kommission.bis > @freigabe_date));
  AND id IN (261, 280, 210, 281, 264, 272, 274, 259, 273, 277, 278, 275);
UPDATE partei SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE fraktion SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE rat SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
UPDATE kanton_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL;
-- UPDATE zutrittsberechtigung SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by parlamentarier
-- person  -- triggered by zutrittsberechtigung
-- UPDATE interessenbindung_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by interessenbindung
-- UPDATE mandat_jahr SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE freigabe_datum IS NULL; -- triggered by mandat

-- 06.04.2016

SELECT lg1.id, MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen AND lg1.id = lg2.id GROUP BY lg1.id ORDER BY MAX(lg1.updated_date) DESC

SELECT lg1.id, lg1.updated_date, lg1.parlament_interessenbindungen FROM `parlamentarier_log` lg1 WHERE lg1.id = 10 ORDER BY lg1.updated_date DESC

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen AND lg1.id = lg2.id AND p.id = lg1.id GROUP BY lg1.id ORDER BY MAX(lg1.updated_date) DESC) WHERE p.parlament_interessenbindungen IS NOT NULL AND p.id=80;
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier WHERE id = 80;
SELECT id, nachname, parlament_interessenbindungen FROM parlamentarier_log WHERE id = 214 ORDER BY updated_date DESC;
SELECT id, log_id, nachname, updated_date, parlament_interessenbindungen FROM parlamentarier_log WHERE id = 80 ORDER BY updated_date DESC;

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen AND lg1.id = lg2.id AND p.id = lg1.id GROUP BY lg1.id ORDER BY MAX(lg1.updated_date) DESC);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen AND lg1.id = lg2.id AND p.id = lg1.id GROUP BY lg1.id ORDER BY MAX(lg1.updated_date) DESC);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;


-- 07.04.2016

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND p.id = lg1.id AND lg1.snapshot_id = lg2.snapshot_id + 1 GROUP BY lg1.id ORDER BY MAX(lg1.updated_date) DESC);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND p.id = lg1.id GROUP BY lg1.id ORDER BY MAX(lg1.updated_date) DESC);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ MAX(lg1.updated_date)/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND p.id = lg1.id AND NOT EXISTS (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg1.log_id AND lg2.log_id AND lgi.id = lg1.id) GROUP BY lg1.id ORDER BY MAX(lg1.log_id) DESC);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT /*lg1.id,*/ lg1.updated_date/*, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND p.id = lg1.id AND lg1.log_id > lg2.log_id AND NOT EXISTS (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg1.log_id AND lg2.log_id AND lgi.id = lg1.id) ORDER BY lg1.log_id DESC LIMIT 1);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

SELECT lg1.id, lg1.nachname, lg1.log_id, lg2.log_id, (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg2.log_id + 1 AND lg1.log_id - 1 AND lgi.id = lg1.id LIMIT 1), lg1.updated_date, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND lg1.log_id > lg2.log_id AND NOT EXISTS (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg2.log_id + 1 AND lg1.log_id -1 AND lgi.id = lg1.id) ORDER BY lg1.log_id DESC;

SET @disable_table_logging = 1;
UPDATE parlamentarier p SET p.parlament_interessenbindungen_updated = (SELECT lg1.updated_date /*, lg1.id, lg1.nachname, lg1.log_id, lg2.log_id, (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg2.log_id + 1 AND lg1.log_id - 1 AND lgi.id = lg1.id LIMIT 1), lg1.updated_date, lg1.parlament_interessenbindungen, lg2.parlament_interessenbindungen*/ FROM `parlamentarier_log` lg1,`parlamentarier_log` lg2 WHERE (lg1.parlament_interessenbindungen <> lg2.parlament_interessenbindungen OR (lg1.parlament_interessenbindungen IS NOT NULL AND lg2.parlament_interessenbindungen IS NULL)) AND lg1.id = lg2.id AND lg1.log_id > lg2.log_id AND p.id = lg1.id AND NOT EXISTS (SELECT lgi.log_id FROM `parlamentarier_log` lgi WHERE lgi.log_id BETWEEN lg2.log_id + 1 AND lg1.log_id -1 AND lgi.id = lg1.id) ORDER BY lg1.log_id DESC LIMIT 1);
SET @disable_table_logging = NULL;
SELECT id, nachname, parlament_interessenbindungen_updated FROM parlamentarier ORDER BY parlament_interessenbindungen_updated DESC;

-- 18.07.2019/RKU show complete parlament_interessenbindungen log

SELECT * FROM (SELECT 999999 log_id, parlamentarier.updated_date, parlamentarier.updated_visa, parlamentarier.parlament_interessenbindungen_updated, REPLACE(REPLACE(REPLACE(parlamentarier.parlament_interessenbindungen, '\"', '\''), '\n', ''), '\r', '') parlament_interessenbindungen_normalized
FROM parlamentarier
WHERE parlamentarier.id=:id
UNION ALL
SELECT parlamentarier_log.log_id, parlamentarier_log.updated_date, parlamentarier_log.updated_visa, parlamentarier_log.parlament_interessenbindungen_updated, REPLACE(REPLACE(REPLACE(parlamentarier_log.parlament_interessenbindungen, '\"', '\''), '\n', ''), '\r', '') parlament_interessenbindungen_normalized
FROM parlamentarier LEFT OUTER JOIN `parlamentarier_log` ON parlamentarier.id = parlamentarier_log.id
WHERE parlamentarier.id=:id) all_data
ORDER BY log_id DESC
-- LIMIT 1
;


SELECT p.id, p.parlament_interessenbindungen_updated, plog.log_id, plog.parlament_interessenbindungen_updated,
CONCAT('UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE(\'', IFNULL(plog.parlament_interessenbindungen_updated, 'NULL'), '\', \'%Y-%m-%d %T\') , updated_visa=\'roland\', notizen = CONCAT_WS(\'\\n\\n\', \'18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (', IFNULL(plog.parlament_interessenbindungen_updated, 'NULL'), ')\', notizen) WHERE parlamentarier.id = ', p.id, ';') stmt
FROM parlamentarier p
LEFT OUTER JOIN (
  SELECT parlamentarier.id, MIN(parlamentarier_log.log_id) log_id, MIN(parlamentarier_log.parlament_interessenbindungen_updated) parlament_interessenbindungen_updated
  FROM parlamentarier
    LEFT OUTER JOIN `parlamentarier_log` ON parlamentarier.id = parlamentarier_log.id AND REPLACE(REPLACE(REPLACE(parlamentarier.parlament_interessenbindungen, '"', '\''), '\n', ''), '\r', '') = REPLACE(REPLACE(REPLACE(parlamentarier_log.parlament_interessenbindungen, '"', '\''), '\n', ''), '\r', '')
  GROUP BY parlamentarier.id
  ORDER BY parlamentarier.id
  ) plog ON p.id = plog.id
WHERE
-- p.id IN (60, 264) AND
p.parlament_interessenbindungen_updated <> plog.parlament_interessenbindungen_updated
ORDER BY p.id
-- LIMIT 10
;

-- stmt
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-03 19:05:26', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-03 19:05:26)', notizen) WHERE parlamentarier.id = 10;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-02-08 19:07:47', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-02-08 19:07:47)', notizen) WHERE parlamentarier.id = 15;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-05-22 03:21:07', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-05-22 03:21:07)', notizen) WHERE parlamentarier.id = 17;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 19;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-06-28 05:30:30', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-06-28 05:30:30)', notizen) WHERE parlamentarier.id = 23;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-06-12 06:29:40', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-06-12 06:29:40)', notizen) WHERE parlamentarier.id = 35;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-21 18:11:33', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-21 18:11:33)', notizen) WHERE parlamentarier.id = 36;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-11-26 19:42:17', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-11-26 19:42:17)', notizen) WHERE parlamentarier.id = 38;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-12-07 09:05:47', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-12-07 09:05:47)', notizen) WHERE parlamentarier.id = 39;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-09-10 08:17:47', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-09-10 08:17:47)', notizen) WHERE parlamentarier.id = 44;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-30 06:56:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-30 06:56:13)', notizen) WHERE parlamentarier.id = 46;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-02-01 06:53:18', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-02-01 06:53:18)', notizen) WHERE parlamentarier.id = 51;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-11-18 20:52:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-11-18 20:52:15)', notizen) WHERE parlamentarier.id = 55;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-12-07 09:05:47', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-12-07 09:05:47)', notizen) WHERE parlamentarier.id = 59;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 60;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-12-07 09:05:47', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-12-07 09:05:47)', notizen) WHERE parlamentarier.id = 61;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-09-15 18:05:18', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-09-15 18:05:18)', notizen) WHERE parlamentarier.id = 63;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-02-23 19:05:24', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-02-23 19:05:24)', notizen) WHERE parlamentarier.id = 73;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-15 19:05:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-15 19:05:15)', notizen) WHERE parlamentarier.id = 74;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-03-09 19:05:50', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-03-09 19:05:50)', notizen) WHERE parlamentarier.id = 79;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-21 18:11:33', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-21 18:11:33)', notizen) WHERE parlamentarier.id = 84;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-04-13 18:05:46', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-04-13 18:05:46)', notizen) WHERE parlamentarier.id = 86;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 88;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 93;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-06-08 18:06:00', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-06-08 18:06:00)', notizen) WHERE parlamentarier.id = 96;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 104;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 108;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-03-09 19:05:50', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-03-09 19:05:50)', notizen) WHERE parlamentarier.id = 117;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-05-27 07:13:22', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-05-27 07:13:22)', notizen) WHERE parlamentarier.id = 122;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-03-31 06:36:59', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-03-31 06:36:59)', notizen) WHERE parlamentarier.id = 131;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-24 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-24 19:05:01)', notizen) WHERE parlamentarier.id = 141;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-04-02 06:25:08', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-04-02 06:25:08)', notizen) WHERE parlamentarier.id = 144;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-05-04 18:05:58', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-05-04 18:05:58)', notizen) WHERE parlamentarier.id = 147;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-24 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-24 19:05:01)', notizen) WHERE parlamentarier.id = 151;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 153;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-12-11 11:29:16', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-12-11 11:29:16)', notizen) WHERE parlamentarier.id = 156;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-04-02 06:25:08', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-04-02 06:25:08)', notizen) WHERE parlamentarier.id = 161;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-10-05 18:07:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-10-05 18:07:48)', notizen) WHERE parlamentarier.id = 162;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-01-02 19:33:52', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-01-02 19:33:52)', notizen) WHERE parlamentarier.id = 167;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-30 06:56:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-30 06:56:13)', notizen) WHERE parlamentarier.id = 170;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 173;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 178;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-12-14 19:07:39', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-12-14 19:07:39)', notizen) WHERE parlamentarier.id = 180;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 183;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 184;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 190;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-12-11 11:29:16', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-12-11 11:29:16)', notizen) WHERE parlamentarier.id = 191;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 193;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-15 19:05:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-15 19:05:15)', notizen) WHERE parlamentarier.id = 195;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-06-25 08:18:31', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-06-25 08:18:31)', notizen) WHERE parlamentarier.id = 197;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-09-07 18:07:24', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-09-07 18:07:24)', notizen) WHERE parlamentarier.id = 198;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-30 06:56:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-30 06:56:13)', notizen) WHERE parlamentarier.id = 199;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-24 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-24 19:05:01)', notizen) WHERE parlamentarier.id = 203;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-30 06:56:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-30 06:56:13)', notizen) WHERE parlamentarier.id = 206;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-10-13 18:05:17', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-10-13 18:05:17)', notizen) WHERE parlamentarier.id = 210;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-30 06:56:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-30 06:56:13)', notizen) WHERE parlamentarier.id = 224;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 225;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-05-09 20:00:18', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-05-09 20:00:18)', notizen) WHERE parlamentarier.id = 228;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-30 06:56:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-30 06:56:13)', notizen) WHERE parlamentarier.id = 237;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-29 20:51:48', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-29 20:51:48)', notizen) WHERE parlamentarier.id = 238;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-01 11:30:59', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-01 11:30:59)', notizen) WHERE parlamentarier.id = 243;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-01 11:30:59', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-01 11:30:59)', notizen) WHERE parlamentarier.id = 251;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-04-13 18:05:46', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-04-13 18:05:46)', notizen) WHERE parlamentarier.id = 265;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-01-18 19:13:11', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-01-18 19:13:11)', notizen) WHERE parlamentarier.id = 270;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-08 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-08 19:05:01)', notizen) WHERE parlamentarier.id = 272;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-15 19:05:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-15 19:05:15)', notizen) WHERE parlamentarier.id = 277;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-07 18:07:45', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-07 18:07:45)', notizen) WHERE parlamentarier.id = 280;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-24 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-24 19:05:01)', notizen) WHERE parlamentarier.id = 282;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-01-02 19:33:52', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-01-02 19:33:52)', notizen) WHERE parlamentarier.id = 286;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-24 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-24 19:05:01)', notizen) WHERE parlamentarier.id = 287;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-03-09 19:05:50', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-03-09 19:05:50)', notizen) WHERE parlamentarier.id = 289;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-12-19 15:25:41', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-12-19 15:25:41)', notizen) WHERE parlamentarier.id = 290;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-12-19 15:25:41', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-12-19 15:25:41)', notizen) WHERE parlamentarier.id = 291;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-05-22 03:21:07', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-05-22 03:21:07)', notizen) WHERE parlamentarier.id = 292;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-01-26 19:05:13', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-01-26 19:05:13)', notizen) WHERE parlamentarier.id = 294;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-03-17 18:34:57', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-03-17 18:34:57)', notizen) WHERE parlamentarier.id = 298;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-15 19:05:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-15 19:05:15)', notizen) WHERE parlamentarier.id = 300;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-11-18 20:52:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-11-18 20:52:15)', notizen) WHERE parlamentarier.id = 304;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-07-06 08:01:28', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-07-06 08:01:28)', notizen) WHERE parlamentarier.id = 305;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-11-30 21:36:34', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-11-30 21:36:34)', notizen) WHERE parlamentarier.id = 306;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2015-12-19 15:25:41', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2015-12-19 15:25:41)', notizen) WHERE parlamentarier.id = 307;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-15 19:05:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-15 19:05:15)', notizen) WHERE parlamentarier.id = 311;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-15 19:05:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-15 19:05:15)', notizen) WHERE parlamentarier.id = 317;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-01-15 19:24:10', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-01-15 19:24:10)', notizen) WHERE parlamentarier.id = 319;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-01-12 19:05:45', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-01-12 19:05:45)', notizen) WHERE parlamentarier.id = 321;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2016-03-21 19:24:37', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2016-03-21 19:24:37)', notizen) WHERE parlamentarier.id = 322;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-06-17 06:31:22', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-06-17 06:31:22)', notizen) WHERE parlamentarier.id = 323;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-11-24 19:05:01', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-11-24 19:05:01)', notizen) WHERE parlamentarier.id = 324;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-01 11:30:59', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-01 11:30:59)', notizen) WHERE parlamentarier.id = 325;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-01 11:30:59', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-01 11:30:59)', notizen) WHERE parlamentarier.id = 327;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2017-12-01 11:30:59', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2017-12-01 11:30:59)', notizen) WHERE parlamentarier.id = 329;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-03-01 04:37:40', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-03-01 04:37:40)', notizen) WHERE parlamentarier.id = 330;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-21 18:11:33', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-21 18:11:33)', notizen) WHERE parlamentarier.id = 332;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-06-01 18:05:43', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-06-01 18:05:43)', notizen) WHERE parlamentarier.id = 335;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2018-12-14 19:07:39', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2018-12-14 19:07:39)', notizen) WHERE parlamentarier.id = 339;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-03-09 18:10:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-03-09 18:10:15)', notizen) WHERE parlamentarier.id = 340;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-03-09 18:10:15', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-03-09 18:10:15)', notizen) WHERE parlamentarier.id = 341;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-07 18:07:45', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-07 18:07:45)', notizen) WHERE parlamentarier.id = 343;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-07 18:07:45', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-07 18:07:45)', notizen) WHERE parlamentarier.id = 344;
UPDATE parlamentarier SET parlamentarier.parlament_interessenbindungen_updated = STR_TO_DATE('2019-06-14 18:08:08', '%Y-%m-%d %T') , updated_visa='roland', notizen = CONCAT_WS('\n\n', '18.07.2019/roland: Korrigiere parlament_interessenbindungen_updated auf letzte wirkliche Änderung (2019-06-14 18:08:08)', notizen) WHERE parlamentarier.id = 345;

-- 19.07.2019/RKU, active person missing zutrittsberechtigung_von and parlamentarier_kommissionen

SELECT person.`id`,person.`nachname`,person.`vorname`, CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) parlam_von, parlamentarier.kommissionen, person.`parlamentarier_kommissionen`,person.`zutrittsberechtigung_von`,zutrittsberechtigung.`parlamentarier_kommissionen` FROM `person` JOIN zutrittsberechtigung ON person.id = zutrittsberechtigung.person_id AND zutrittsberechtigung.bis IS NULL JOIN parlamentarier ON parlamentarier.id = zutrittsberechtigung.parlamentarier_id WHERE person.zutrittsberechtigung_von IS NULL OR person.parlamentarier_kommissionen IS NULL;

-- Disable triggers
SET @disable_triggers = 1;
UPDATE person JOIN zutrittsberechtigung ON person.id = zutrittsberechtigung.person_id AND zutrittsberechtigung.bis IS NULL JOIN parlamentarier ON parlamentarier.id = zutrittsberechtigung.parlamentarier_id SET person.`parlamentarier_kommissionen`=parlamentarier.kommissionen, person.`zutrittsberechtigung_von`= CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname), person.updated_visa='roland' WHERE person.zutrittsberechtigung_von IS NULL OR person.parlamentarier_kommissionen IS NULL;
SET @disable_triggers = NULL;

-- 26.07.2019/RKU fix utf8mb4 migration problems

SET @disable_triggers = 1;
update translation_source set updated_date = created_date where updated_date < created_date;
update translation_source_log set updated_date = created_date where updated_date < created_date;
SET @disable_triggers = NULL;

-- 29.07.2019/RKU Alle aktiven Interessenbindungen und Mandate zählen

select count(*) from interessenbindung join parlamentarier on interessenbindung.parlamentarier_id = parlamentarier.id where parlamentarier.im_rat_bis IS NULL AND interessenbindung.bis IS NULL;

select count(*) from mandat join person on person.id=mandat.person_id join zutrittsberechtigung on zutrittsberechtigung.person_id=person.id join parlamentarier on parlamentarier.id=zutrittsberechtigung.parlamentarier_id WHERE parlamentarier.im_rat_bis IS NULL AND mandat.bis IS NULL AND zutrittsberechtigung.bis IS NULL;

select rat.abkuerzung, count(*)
from interessenbindung
join parlamentarier on interessenbindung.parlamentarier_id = parlamentarier.id and parlamentarier.im_rat_bis IS NULL
join rat on parlamentarier.rat_id = rat.id
where  AND interessenbindung.bis IS NULL
group by rat.abkuerzung;

-- 30.07.2019/RKU Alle aktiven Interessenbindungen pro Parlamentarier einer Partei/Fraktion berechnen

select partei.name partei, count(distinct parlamentarier.id) anzahl_parlamentarier, count(interessenbindung.id) anzahl_interessenbindungen, round(count(interessenbindung.id) / count(distinct parlamentarier.id), 1) anzahl_ib_per_p from interessenbindung join parlamentarier on interessenbindung.parlamentarier_id = parlamentarier.id left join partei on partei.id = parlamentarier.partei_id where parlamentarier.im_rat_bis IS NULL AND interessenbindung.bis IS NULL GROUP BY partei.name order by anzahl_ib_per_p DESC;

select fraktion.name fraktion, count(distinct parlamentarier.id) anzahl_parlamentarier, count(interessenbindung.id) anzahl_interessenbindungen, round(count(interessenbindung.id) / count(distinct parlamentarier.id), 1) anzahl_ib_per_p from interessenbindung join parlamentarier on interessenbindung.parlamentarier_id = parlamentarier.id left join fraktion on fraktion.id = parlamentarier.fraktion_id where parlamentarier.im_rat_bis IS NULL AND interessenbindung.bis IS NULL GROUP BY fraktion.name order by anzahl_ib_per_p DESC;

-- 05.09.2019 active interessenbindung romandie without fr beschreibung

SELECT distinct interessenbindung.beschreibung, interessenbindung.beschreibung_fr FROM `interessenbindung`
JOIN parlamentarier ON interessenbindung.parlamentarier_id =parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())
JOIN kanton ON kanton.id = parlamentarier.kanton_id AND kanton.abkuerzung IN ('GE', 'VD', 'FR', 'VS', 'NE')
WHERE interessenbindung.beschreibung_fr IS NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW())
ORDER BY interessenbindung.beschreibung;

-- 05.10.2019 active interessenbindung romandie without fr with context

SELECT interessenbindung.id, interessenbindung.beschreibung, interessenbindung.beschreibung_fr, parlamentarier.nachname parlamentarier_nachname, parlamentarier.vorname parlamentarier_vorname, organisation.name_de organisation_name_de, organisation.name_fr organisation_name_fr FROM `interessenbindung`
JOIN parlamentarier ON interessenbindung.parlamentarier_id =parlamentarier.id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())
JOIN organisation ON interessenbindung.organisation_id =organisation.id
JOIN kanton ON kanton.id = parlamentarier.kanton_id AND kanton.abkuerzung IN ('GE', 'VD', 'FR', 'VS', 'NE')
WHERE interessenbindung.beschreibung IS NOT NULL AND interessenbindung.beschreibung<>'' AND interessenbindung.beschreibung_fr IS NULL AND (interessenbindung.bis IS NULL OR interessenbindung.bis > NOW())
ORDER BY interessenbindung.beschreibung;

-- 09.09.2019 Fill verguetungstransparenz

INSERT INTO `interessenbindung_jahr` (parlamentarier_id, stichdatum, created_visa, updated_visa, created_date, updated_date)
SELECT id, '2019-09-02', 'roland', 'roland', NOW(), NOW()
FROM parlamentarier
WHERE (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())
ORDER BY id;

-- 26.09.2019 Fill parlamentarier_transparenz for 2017 and 2018

-- Select parlamentarier for parlamentarier_transparenz
SELECT id, nachname, vorname, im_rat_seit, im_rat_bis FROM `parlamentarier` WHERE (`im_rat_seit` IS NULL OR `im_rat_seit` <= '2017-11-01') AND (`im_rat_bis` IS NULL OR `im_rat_bis` >= '2017-11-01') order by im_rat_bis desc;

-- Select parlamentarier for parlamentarier_transparenz with session variable stichdatum
SET @stichdatum = '2017-05-27';
-- SET @stichdatum = '2018-11-01';
SELECT id, nachname, vorname, im_rat_seit, im_rat_bis FROM `parlamentarier` WHERE (`im_rat_seit` IS NULL OR `im_rat_seit` <= @stichdatum) AND (`im_rat_bis` IS NULL OR `im_rat_bis` >= @stichdatum) order by im_rat_bis desc, im_rat_seit desc;

-- Fill parlamentarier_transparenz for 2017 and 2018
SET @stichdatum = '2017-05-27';
INSERT INTO `parlamentarier_transparenz` (parlamentarier_id, stichdatum, created_visa, updated_visa, created_date, updated_date)
SELECT id, @stichdatum, 'roland', 'roland', NOW(), NOW()
FROM parlamentarier
WHERE ((`im_rat_seit` IS NULL OR `im_rat_seit` <= @stichdatum) AND (`im_rat_bis` IS NULL OR `im_rat_bis` >= @stichdatum))
ORDER BY id;
SET @stichdatum = '2018-11-01';
INSERT INTO `parlamentarier_transparenz` (parlamentarier_id, stichdatum, created_visa, updated_visa, created_date, updated_date)
SELECT id, @stichdatum, 'roland', 'roland', NOW(), NOW()
FROM parlamentarier
WHERE ((`im_rat_seit` IS NULL OR `im_rat_seit` <= @stichdatum) AND (`im_rat_bis` IS NULL OR `im_rat_bis` >= @stichdatum))
ORDER BY id;

-- Compare parlamentarier_transparenz
select a.parlamentarier_id, a.stichdatum, b.stichdatum from parlamentarier_transparenz a LEFT OUTER JOIN parlamentarier_transparenz b ON a.parlamentarier_id=b.parlamentarier_id WHERE a.stichdatum < b.stichdatum AND a.stichdatum < '2017-12-31' AND b.stichdatum < '2017-12-31' order by a.parlamentarier_id;

-- BAD Compare parlamentarier_transparenz select a.parlamentarier_id, a.stichdatum, b.stichdatum from parlamentarier_transparenz a RIGHT OUTER JOIN parlamentarier_transparenz b ON a.parlamentarier_id=b.parlamentarier_id AND b.stichdatum = '2017-11-01' WHERE a.stichdatum = '2017-05-27' order by a.parlamentarier_id

-- Compare parlamentarier_transparenz
select a.parlamentarier_id, a.stichdatum, b.stichdatum from parlamentarier_transparenz a LEFT OUTER JOIN parlamentarier_transparenz b ON a.parlamentarier_id=b.parlamentarier_id AND b.stichdatum = '2017-11-01' WHERE a.stichdatum = '2017-05-27' ORDER BY `a`.`parlamentarier_id` ASC

-- 26.11.2020 Copy Vergütungstransparenzliste 2020-01-30 -> 2020-12-31

INSERT INTO `parlamentarier_transparenz` (parlamentarier_id, stichdatum, verguetung_transparent, notizen, `eingabe_abgeschlossen_visa`, `eingabe_abgeschlossen_datum`, `kontrolliert_visa`, `kontrolliert_datum`, `freigabe_visa`, `freigabe_datum`, created_visa, updated_visa, created_date, updated_date)
SELECT parlamentarier_id, '2020-12-31' as stichdatum, verguetung_transparent, notizen, `eingabe_abgeschlossen_visa`, `eingabe_abgeschlossen_datum`, `kontrolliert_visa`, `kontrolliert_datum`, `freigabe_visa`, `freigabe_datum`, created_visa, updated_visa, created_date, updated_date
FROM `parlamentarier_transparenz`
WHERE stichdatum='2020-01-30' ORDER BY id;

-- 24.10.2019 Find duplicated mandate and zutrittsberechtigung

-- Double mandate
SELECT p.id pid, m1.organisation_id, p.nachname, p.vorname, m1.id m1id, m2.id m2id, m1.von m1von, m1.bis m1bis, m2.von m2von, m2.bis m2bis  FROM `person` p JOIN mandat m1 ON p.id = m1.person_id JOIN mandat m2 ON p.id = m2.person_id WHERE m1.organisation_id = m2.organisation_id AND m1.von < m2.bis AND m1.bis IS NULL AND m1.von IS NOT NULL;

-- Double zutrittsberechtigung

SELECT p.id pid, z1.parlamentarier_id, p.nachname, p.vorname, z1.id z1id, z2.id z2id, z1.von z1von, z1.bis z1bis, z2.von z2von, z2.bis z2bis  FROM `person` p JOIN zutrittsberechtigung z1 ON p.id = z1.person_id JOIN zutrittsberechtigung z2 ON p.id = z2.person_id WHERE z1.parlamentarier_id = z2.parlamentarier_id AND z1.von < z2.bis AND z1.bis IS NULL AND z1.von IS NOT NULL;

-- 07.11.2019 Request of Titus

-- Ich habe dazu an die folgenden Zahlen gedacht (Stichtag: heute bzw. «31.10.2019»)

-- - Anzahl Lobbygruppen (von Hand gezählt komme ich auf 139)
-- - Anzahl Lobbygruppen nach Wirksamkeit (hoch/mittel/gering)
-- - Top 5 Lobbygruppen mit den meisten Parlamentsmitgliedern über alles und/oder nach Wirksamkeit
-- - Top 5 Lobbygruppen mit den meisten Lobbyisten über alles und/oder nach Wirksamkeit
-- - Anzahl Organisationen insgesamt in der DB
-- - Anzahl Personen bzw. Lobbyisten (mehr oder weniger die «aktiven» mit mind. 1 Verbindung)
-- - Durchschnittliche Anzahl Verbindungen zwischen Parlamentsmitgliedern und Organisationen
-- - Durchschnittliche Anzahl Verbindungen zwischen Lobbyisten und Organisationen

-- Vielleicht siehst auch Du noch nach Zahlen, die interessant und spannend wären, wenn man sie einmal herausholt (Du dürftest die Datenstruktur wohl fast auswendig kennen).

-- Ich würde das gerne noch mit weiteren Zahlen von ausserhalb der DB ergänzen wollen:
-- - Anzahl Seitenaufrufe pro Zeiteinheit x (wöchentlich, monatlich, …)
-- - Anzahl Such-Anfragen
-- - evtl. Anzahl Newsletter pro Jahr
-- - Anzahl Newsletter-Abonnenten
-- - Anzahl Mitglieder (plus evtl. SpenderInnen)
-- - Einnahmen (Finanzierung) nach Beträgen abgestuft (bis 100 / 500 / 1000 / über 1000 CHF)
-- - Anzahl Stunden für Recherche (gemäss Lohnabrechnungen)
-- - Anzahl Stunden Freiwilligenarbeiten (Vorstand, Schätzung)

-- Weitere Fragen RK
-- - Durchschnittliche/Maximale Anzahl Interessenbindungen/Lobbyisten/Lobbyistenmandate pro Parlamentarier/Branche/Interessengruppe/Organisationen/Kommissionen über alles/aktuell/SR/NR/Kanton/Partei

-- 02.12.2019 fix zb nachname Herr, Frau

SET @disable_triggers = 1;

DELETE FROM zutrittsberechtigung WHERE person_id IN (SELECT id FROM `person` WHERE `nachname` IN ('Herr', 'Frau', 'Madame', 'Monsieur'));
DELETE FROM `person` WHERE `nachname` IN ('Herr', 'Frau', 'Madame', 'Monsieur');

DELETE FROM zutrittsberechtigung_log WHERE person_id IN (SELECT id FROM `person_log` WHERE `nachname` IN ('Herr', 'Frau', 'Madame', 'Monsieur'));
DELETE FROM `person_log` WHERE `nachname` IN ('Herr', 'Frau', 'Madame', 'Monsieur');

-- 03.10.2020 generate alias cols

SELECT CONCAT('interessenbindung.', COLUMN_NAME, ' AS interessenbindung_', COLUMN_NAME, ',') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='interessenbindung' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION;

-- 07.10.2020 vf_interessenbindung

(SELECT CONCAT("SELECT interessenbindung.* FROM interessenbindung;"))
UNION
(SELECT CONCAT("INSERT INTO interessenbindung (", GROUP_CONCAT('\n', COLUMN_NAME), '\n) VALUES (', GROUP_CONCAT('\n:', COLUMN_NAME), '\n);') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='interessenbindung' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION)
UNION
(SELECT CONCAT("UPDATE interessenbindung SET ", GROUP_CONCAT('\n', COLUMN_NAME, ' = :', COLUMN_NAME), '\nWHERE id =:OLD_id;') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='interessenbindung' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION)
UNION
(SELECT CONCAT("DELETE FROM interessenbindung WHERE id = :id;"));

SELECT
interessenbindung.*,
interessenbindung_jahr.verguetung,
interessenbindung_jahr.jahr as verguetung_jahr,
interessenbindung_jahr.beschreibung as verguetung_beschreibung,
interessenbindung_jahr.freigabe_datum as verguetung_freigabe_datum,
interessenbindung_jahr_minus_1.verguetung as jahr_minus_1_verguetung,
interessenbindung_jahr_minus_1.jahr as jahr_minus_1_verguetung_jahr,
interessenbindung_jahr_minus_1.beschreibung as jahr_minus_1_verguetung_beschreibung,
interessenbindung_jahr_minus_1.freigabe_datum as jahr_minus_1_verguetung_freigabe_datum,
interessenbindung_jahr_minus_2.verguetung as jahr_minus_2_verguetung,
interessenbindung_jahr_minus_2.jahr as jahr_minus_2_verguetung_jahr,
interessenbindung_jahr_minus_2.beschreibung as jahr_minus_2_verguetung_beschreibung,
interessenbindung_jahr_minus_2.freigabe_datum as jahr_minus_2_verguetung_freigabe_datum
FROM `interessenbindung`
LEFT JOIN interessenbindung_jahr ON interessenbindung_jahr.interessenbindung_id = interessenbindung.id AND interessenbindung_jahr.jahr = YEAR(NOW())
LEFT JOIN interessenbindung_jahr interessenbindung_jahr_minus_1 ON interessenbindung_jahr_minus_1.interessenbindung_id = interessenbindung.id AND interessenbindung_jahr.jahr = YEAR(NOW()) - 1
LEFT JOIN interessenbindung_jahr interessenbindung_jahr_minus_2 ON interessenbindung_jahr_minus_2.interessenbindung_id = interessenbindung.id AND interessenbindung_jahr.jahr = YEAR(NOW()) - 2;

INSERT INTO interessenbindung (
id,
parlamentarier_id,
organisation_id,
art,
funktion_im_gremium,
deklarationstyp,
status,
hauptberuflich,
behoerden_vertreter,
beschreibung,
beschreibung_fr,
quelle_url,
quelle_url_gueltig,
quelle,
von,
bis,
notizen,
updated_by_import,
eingabe_abgeschlossen_visa,
eingabe_abgeschlossen_datum,
kontrolliert_visa,
kontrolliert_datum,
autorisiert_visa,
autorisiert_datum,
freigabe_visa,
freigabe_datum,
created_visa,
created_date,
updated_visa,
updated_date
) VALUES (
:id,
:parlamentarier_id,
:organisation_id,
:art,
:funktion_im_gremium,
:deklarationstyp,
:status,
:hauptberuflich,
:behoerden_vertreter,
:beschreibung,
:beschreibung_fr,
:quelle_url,
:quelle_url_gueltig,
:quelle,
:von,
:bis,
:notizen,
:updated_by_import,
:eingabe_abgeschlossen_visa,
:eingabe_abgeschlossen_datum,
:kontrolliert_visa,
:kontrolliert_datum,
:autorisiert_visa,
:autorisiert_datum,
:freigabe_visa,
:freigabe_datum,
:created_visa,
:created_date,
:updated_visa,
:updated_date
);

UPDATE interessenbindung SET
id = :id,
parlamentarier_id = :parlamentarier_id,
organisation_id = :organisation_id,
art = :art,
funktion_im_gremium = :funktion_im_gremium,
deklarationstyp = :deklarationstyp,
status = :status,
hauptberuflich = :hauptberuflich,
behoerden_vertreter = :behoerden_vertreter,
beschreibung = :beschreibung,
beschreibung_fr = :beschreibung_fr,
quelle_url = :quelle_url,
quelle_url_gueltig = :quelle_url_gueltig,
quelle = :quelle,
von = :von,
bis = :bis,
notizen = :notizen,
updated_by_import = :updated_by_import,
eingabe_abgeschlossen_visa = :eingabe_abgeschlossen_visa,
eingabe_abgeschlossen_datum = :eingabe_abgeschlossen_datum,
kontrolliert_visa = :kontrolliert_visa,
kontrolliert_datum = :kontrolliert_datum,
autorisiert_visa = :autorisiert_visa,
autorisiert_datum = :autorisiert_datum,
freigabe_visa = :freigabe_visa,
freigabe_datum = :freigabe_datum,
created_visa = :created_visa,
created_date = :created_date,
updated_visa = :updated_visa,
updated_date = :updated_date
WHERE id =:OLD_id;

DELETE FROM interessenbindung WHERE id = :id;

-- 07.10.2020 vf_parlamentarier_transparenz

-- (SELECT CONCAT("SELECT parlamentarier_transparenz.* FROM parlamentarier_transparenz;"))
-- UNION
(SELECT CONCAT("INSERT INTO parlamentarier_transparenz (", GROUP_CONCAT('\n', COLUMN_NAME), '\n) VALUES (', GROUP_CONCAT('\n:', COLUMN_NAME), '\n);') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='parlamentarier_transparenz' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION)
UNION
(SELECT CONCAT("UPDATE parlamentarier_transparenz SET ", GROUP_CONCAT('\n', COLUMN_NAME, ' = :', COLUMN_NAME), '\nWHERE id =:OLD_id;') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='parlamentarier_transparenz' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION)
UNION
(SELECT CONCAT("DELETE FROM parlamentarier_transparenz WHERE id = :id;"));

INSERT INTO parlamentarier_transparenz (
id,
parlamentarier_id,
stichdatum,
in_liste,
verguetung_transparent,
notizen,
eingabe_abgeschlossen_visa,
eingabe_abgeschlossen_datum,
kontrolliert_visa,
kontrolliert_datum,
freigabe_visa,
freigabe_datum,
created_visa,
created_date,
updated_visa,
updated_date
) VALUES (
:id,
:parlamentarier_id,
:stichdatum,
:in_liste,
:verguetung_transparent,
:notizen,
:eingabe_abgeschlossen_visa,
:eingabe_abgeschlossen_datum,
:kontrolliert_visa,
:kontrolliert_datum,
:freigabe_visa,
:freigabe_datum,
:created_visa,
:created_date,
:updated_visa,
:updated_date
);

UPDATE parlamentarier_transparenz SET
id = :id,
parlamentarier_id = :parlamentarier_id,
stichdatum = :stichdatum,
in_liste = :in_liste,
verguetung_transparent = :verguetung_transparent,
notizen = :notizen,
eingabe_abgeschlossen_visa = :eingabe_abgeschlossen_visa,
eingabe_abgeschlossen_datum = :eingabe_abgeschlossen_datum,
kontrolliert_visa = :kontrolliert_visa,
kontrolliert_datum = :kontrolliert_datum,
freigabe_visa = :freigabe_visa,
freigabe_datum = :freigabe_datum,
created_visa = :created_visa,
created_date = :created_date,
updated_visa = :updated_visa,
updated_date = :updated_date
WHERE id =:OLD_id;

DELETE FROM parlamentarier_transparenz WHERE id = :id;

-- 27.11.2020 chart-parlamentarier-transparenz

SELECT
fraktion.abkuerzung,
fraktion.name,
fraktion.farbcode,
CONCAT(fraktion.name, ' (', alle.a_n, ')') x_label,
IFNULL(transparent.t_n, 0),
alle.a_n,
(IFNULL(transparent.t_n, 0) / alle.a_n) ratio,
ROUND(100 * IFNULL(transparent.t_n, 0) / alle.a_n) prozent,
CONCAT(ROUND(100 * IFNULL(transparent.t_n, 0) / alle.a_n), '% (', IFNULL(transparent.t_n, 0), ' von ', alle.a_n, ')') col_label
FROM
    (SELECT parlamentarier_transparenz.fraktion_id, COUNT(parlamentarier_transparenz.parlamentarier_id) a_n
    FROM (%source%) parlamentarier_transparenz
    GROUP BY parlamentarier_transparenz.fraktion_id) alle
LEFT JOIN (SELECT parlamentarier_transparenz.fraktion_id, COUNT(parlamentarier_transparenz.parlamentarier_id) t_n
    FROM (%source%) parlamentarier_transparenz
    WHERE parlamentarier_transparenz.verguetung_transparent='ja'
    GROUP BY parlamentarier_transparenz.fraktion_id) transparent ON transparent.fraktion_id = alle.fraktion_id
LEFT JOIN v_fraktion fraktion ON fraktion.id = alle.fraktion_id
ORDER BY ratio DESC

-- 08.12.2020 uv_interessenbindung_jahr

-- (SELECT CONCAT("SELECT interessenbindung_jahr.* FROM interessenbindung_jahr;"))
-- UNION
(SELECT CONCAT("INSERT INTO interessenbindung_jahr (", GROUP_CONCAT('\n', COLUMN_NAME), '\n) VALUES (', GROUP_CONCAT('\n:', COLUMN_NAME), '\n);') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='interessenbindung_jahr' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION)
UNION
(SELECT CONCAT("UPDATE interessenbindung_jahr SET ", GROUP_CONCAT('\n', COLUMN_NAME, ' = :', COLUMN_NAME), '\nWHERE id =:OLD_id;') FROM information_schema.COLUMNS WHERE `TABLE_NAME`='interessenbindung_jahr' and `TABLE_SCHEMA`='lobbywatch' ORDER BY ORDINAL_POSITION)
UNION
(SELECT CONCAT("DELETE FROM interessenbindung_jahr WHERE id = :id;"));

-- 11.12.2020 Parlamentarier ohne Arbeitgeber, JSON query

SELECT id, anzeige_name, parlament_beruf_json, JSON_LENGTH(parlament_beruf_json) as n, parlament_beruf_json->>'$[0].beruf', parlament_beruf_json->>'$[0].arbeitgeber', parlament_beruf_json->>'$[1].beruf', parlament_beruf_json->>'$[1].arbeitgeber', parlament_beruf_json->>'$[2].beruf', parlament_beruf_json->>'$[2].arbeitgeber', parlament_beruf_json->'$[*].arbeitgeber' FROM `v_parlamentarier` parlamentarier WHERE im_rat_bis IS NULL AND (parlament_beruf_json IS NULL OR JSON_CONTAINS(parlament_beruf_json, 'null', '$[0].arbeitgeber') OR JSON_CONTAINS(parlament_beruf_json, 'null', '$[1].arbeitgeber') OR JSON_CONTAINS(parlament_beruf_json, 'null', '$[2].arbeitgeber'));

-- JSON_TABLE() is available since MySQL 8.0

-- 12.12.2020 Parlamentarier mit mehr als 2 Berufen

SELECT id, anzeige_name, parlament_beruf_json, JSON_LENGTH(parlament_beruf_json) as n, parlament_beruf_json->>'$[0].beruf', parlament_beruf_json->>'$[0].arbeitgeber', parlament_beruf_json->>'$[1].beruf', parlament_beruf_json->>'$[1].arbeitgeber', parlament_beruf_json->>'$[2].beruf', parlament_beruf_json->>'$[2].arbeitgeber', parlament_beruf_json->>'$[*].arbeitgeber' FROM `v_parlamentarier` parlamentarier WHERE JSON_LENGTH(parlament_beruf_json) > 2

-- 18.12.2020 generated columns, _log tables and _log table triggers

SELECT * FROM `COLUMNS` WHERE `TABLE_SCHEMA`='lobbywatch' AND `TABLE_NAME` like '%_log';

SELECT TABLE_NAME, SUBSTR(TABLE_NAME, 1, LENGTH(TABLE_NAME) - 4) AS SOURCE_TABLE FROM `TABLES` WHERE `TABLE_SCHEMA`='lobbywatch' AND `TABLE_NAME` like '%_log';

SELECT * FROM information_schema.`COLUMNS` WHERE extra not like '%GENERATED' AND `TABLE_SCHEMA`='lobbywatchtest' AND `TABLE_NAME` IN (SELECT SUBSTR(TABLE_NAME, 1, LENGTH(TABLE_NAME) - 4) AS SOURCE_TABLE FROM information_schema.`TABLES` WHERE `TABLE_SCHEMA`='lobbywatchtest' AND `TABLE_NAME` like '%_log') ORDER BY TABLE_NAME, ORDINAL_POSITION;

ALTER TABLE `branche`
DROP `gen_test`,
ADD `gen_test` VARCHAR(100) NOT NULL GENERATED ALWAYS AS (CONCAT(name, ' ', IFNULL(name_fr, '-'))) VIRTUAL COMMENT 'x' AFTER `name_fr`;

ALTER TABLE `branche`
DROP gen_test,
DROP gen_test1,
ADD `gen_test1` VARCHAR(100) GENERATED ALWAYS AS (CONCAT(name, ' ', IFNULL(name_fr, '-'))) VIRTUAL NOT NULL COMMENT 'x' AFTER `name_fr`,
DROP gen_test2,
ADD `gen_test2` VARCHAR(100) GENERATED ALWAYS AS (CONCAT(name, ' ', IFNULL(name_fr, '-'))) STORED NOT NULL COMMENT 'x' AFTER `name_fr`,
DROP gen_test3,
ADD `gen_test3` VARCHAR(100) GENERATED ALWAYS AS (CONCAT(name, ' ', IFNULL(name_fr, '-'))) NOT NULL COMMENT 'x' AFTER `name_fr`
;

ALTER TABLE `branche_log`
DROP gen_test1,
ADD `gen_test1` VARCHAR(0) AFTER `name_fr`,
DROP gen_test2,
ADD `gen_test2` VARCHAR(0) AFTER `name_fr`,
DROP gen_test3,
ADD `gen_test3` VARCHAR(0) AFTER `name_fr`
;

INSERT INTO branche (name, technischer_name, beschreibung, created_visa, updated_visa) VALUES ('test3', 't3', 'b1', 'r', 'r');

SELECT * FROM branche_log ORDER BY id DESC;

INSERT INTO branche (name, technischer_name, beschreibung, created_visa, updated_visa) VALUES ('test3', 't3', 'b1', 'r', 'r');

INSERT INTO interessenbindung (art, parlamentarier_id, organisation_id, deklarationstyp, bis, created_visa, updated_visa) VALUES ('mitglied', 1, 2, 'deklarationspflichtig', NULL, 'r', 'r');

INSERT INTO interessenbindung (art, parlamentarier_id, organisation_id, deklarationstyp, bis, created_visa, updated_visa) VALUES ('mitglied', 1, 2, 'deklarationspflichtig', '2020-12-01', 'r', 'r');

SELECT parlamentarier.anzeige_name, organisation.anzeige_name,`art`, `parlamentarier_id`, `organisation_id`, interessenbindung.bis, count(*) anzahl, GROUP_CONCAT(interessenbindung.id SEPARATOR ', ') interessenbindung_ids FROM interessenbindung
JOIN v_parlamentarier_simple parlamentarier ON parlamentarier_id=parlamentarier.id
JOIN v_organisation_simple organisation ON organisation_id=organisation.id
GROUP BY `art`, `parlamentarier_id`, `organisation_id`, interessenbindung.bis
HAVING count(*) > 1;

SELECT person.anzeige_name, organisation.anzeige_name,`art`, `person_id`, `organisation_id`, mandat.bis, count(*) anzahl, GROUP_CONCAT(mandat.id SEPARATOR ', ') mandat_ids FROM mandat
JOIN v_person_simple person ON person_id=person.id
JOIN v_organisation_simple organisation ON organisation_id=organisation.id
GROUP BY `art`, `person_id`, `organisation_id`, mandat.bis
HAVING count(*) > 1;

SELECT organisation1.anzeige_name, organisation2.anzeige_name,`art`, `organisation_id`, `ziel_organisation_id`, organisation_beziehung.bis, count(*) anzahl, GROUP_CONCAT(organisation_beziehung.id SEPARATOR ', ') organisation_beziehung_ids FROM organisation_beziehung
JOIN v_organisation_simple organisation1 ON organisation_id=organisation1.id
JOIN v_organisation_simple organisation2 ON ziel_organisation_id=organisation2.id
GROUP BY `art`, `organisation_id`, `ziel_organisation_id`, organisation_beziehung.bis
HAVING count(*) > 1;

-- List of tables

grep -e "CREATE TABLE \`.*" lobbywatch.sql | grep -v -E -e "(\`v_|\`vf_|\`uv_|\`mv_|_log\`)"

SELECT `TABLE_SCHEMA`, `TABLE_NAME` FROM `COLUMNS` WHERE `COLUMN_NAME`='wikidata_qid' AND `TABLE_SCHEMA`='lobbywatchtest';

SELECT LENGTH(`twitter_name`), SUBSTRING(twitter_name, 1, 15) AS twitter_name_cut, `twitter_name`, `id` FROM organisation WHERE LENGTH(`twitter_name`) > 15;
UPDATE `parlamentarier_log` SET twitter_name = SUBSTRING(twitter_name, 1, 15) WHERE LENGTH(twitter_name) > 15;

-- 07.02.2021 check inaktive parlamentarische gruppen

SELECT organisation.id, organisation.name_de, organisation.rechtsform, organisation.inaktiv, COUNT(interessenbindung.id) FROM `organisation` JOIN interessenbindung ON interessenbindung.organisation_id = organisation.id AND (interessenbindung.bis IS NULL) WHERE `inaktiv`=0 AND organisation.`rechtsform`='Parlamentarische Gruppe' GROUP BY organisation.id
-- HAVING COUNT(interessenbindung.id) = 0

SELECT organisation.id, organisation.name_de, organisation.rechtsform, organisation.inaktiv, interessenbindung.id, interessenbindung.bis, parlamentarier.id, parlamentarier.nachname, parlamentarier.vorname, parlamentarier.im_rat_bis FROM `organisation`
JOIN interessenbindung ON interessenbindung.organisation_id = organisation.id AND (interessenbindung.bis IS NULL)
JOIN parlamentarier ON parlamentarier.id = interessenbindung.parlamentarier_id
WHERE `inaktiv`=0 AND organisation.`rechtsform`='Parlamentarische Gruppe' AND organisation.id = 5305

SELECT organisation.id, organisation.name_de, organisation.rechtsform, organisation.inaktiv, interessenbindung.id, interessenbindung.bis, parlamentarier.id, parlamentarier.nachname, parlamentarier.vorname, parlamentarier.im_rat_bis
FROM `organisation`
JOIN interessenbindung ON interessenbindung.organisation_id = organisation.id AND interessenbindung.bis IS NULL
JOIN parlamentarier ON parlamentarier.id = interessenbindung.parlamentarier_id AND parlamentarier.im_rat_bis IS NOT NULL
WHERE `organisation`.`rechtsform`='Parlamentarische Gruppe'

-- Update SQL beende Interessenbindung zu parlamentarischer Gruppe von zurückgetretenen Parlamentarieren
SELECT organisation.id, organisation.name_de, organisation.rechtsform, organisation.inaktiv, interessenbindung.id, interessenbindung.bis, parlamentarier.id, parlamentarier.nachname, parlamentarier.vorname, parlamentarier.im_rat_bis, CONCAT('UPDATE interessenbindung SET bis=''', parlamentarier.im_rat_bis, ''', updated_visa=''roland'', notizen = CONCAT_WS(''\\n\\n'', ''07.02.2021/roland: beende Interessenbindung zu parlamentarischer Gruppe von zurückgetretenen Parlamentarieren'', notizen) WHERE id=', interessenbindung.id, ';') update_sql
FROM `organisation`
JOIN interessenbindung ON interessenbindung.organisation_id = organisation.id AND interessenbindung.bis IS NULL
JOIN parlamentarier ON parlamentarier.id = interessenbindung.parlamentarier_id AND parlamentarier.im_rat_bis IS NOT NULL
WHERE `organisation`.`rechtsform`='Parlamentarische Gruppe'

-- Update SQL zum Inaktivsetzen von Parlamentarischen Gruppen ohne Mitglieder
SELECT organisation.id, organisation.name_de, organisation.rechtsform, organisation.inaktiv, interessenbindung.id, interessenbindung.bis, parlamentarier.id, parlamentarier.nachname, parlamentarier.vorname, parlamentarier.im_rat_bis, COUNT(interessenbindung.id), CONCAT('UPDATE organisation SET inaktiv=1, updated_visa=''roland'', notizen = CONCAT_WS(''\\n\\n'', ''07.02.2021/roland: setze parlamentarische Gruppe ohne aktive Mitglieder auf inaktiv'', notizen) WHERE id=', organisation.id, ';') update_sql
FROM `organisation`
LEFT JOIN interessenbindung ON interessenbindung.organisation_id = organisation.id AND interessenbindung.bis IS NULL
LEFT JOIN parlamentarier ON parlamentarier.id = interessenbindung.parlamentarier_id
WHERE organisation.`rechtsform`='Parlamentarische Gruppe'
AND `inaktiv`=0
-- AND organisation.id = 5972
GROUP BY organisation.id
HAVING COUNT(interessenbindung.id) = 0
