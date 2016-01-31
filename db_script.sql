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
