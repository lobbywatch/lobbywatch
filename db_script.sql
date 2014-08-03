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
UPDATE branche SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE interessenbindung SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE interessengruppe SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE in_kommission SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE kommission SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE mandat SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE organisation SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE organisation_beziehung SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE organisation_jahr SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE parlamentarier SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE partei SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE fraktion SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE rat SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE kanton SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE kanton_jahr SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';
UPDATE zutrittsberechtigung SET freigabe_datum = NOW(), freigabe_visa='*', updated_date = NOW(), updated_visa= '*';

-- Alle ent-freigeben
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
