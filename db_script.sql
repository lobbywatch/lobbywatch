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

CALL takeSnapshot('roland', 'UREK published in_kommission_fixed');

CALL `refreshMaterializedViews`();

-- 27.11.2014

SET @freigabe_date = STR_TO_DATE('26.11.2014', '%d.%m.%Y');
SET @freigabe_name = 'roland*';
SET @kommission_id = 3;
UPDATE in_kommission SET freigabe_datum = @freigabe_date, freigabe_visa=@freigabe_name, updated_date = @freigabe_date, updated_visa= @freigabe_name WHERE kommission_id = @kommission_id AND freigabe_datum IS NULL;

CALL takeSnapshot('roland', 'UREK published in_kommission freigabe fixed');

CALL `refreshMaterializedViews`();
