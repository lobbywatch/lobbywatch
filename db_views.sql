-- VIEWS ------------------

-- Last updated views

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
  'Lobbygruppe' name,
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
CREATE OR REPLACE VIEW `v_last_updated_organisation_anhang` AS
  (SELECT
  'organisation_anhang' table_name,
  'Organisationsanhang' name,
  (select count(*) from `organisation_anhang`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `organisation_anhang` t
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
CREATE OR REPLACE VIEW `v_last_updated_organisation_jahr` AS
  (SELECT
  'organisation_jahr' table_name,
  'Organisationsjahr' name,
  (select count(*) from `organisation_jahr`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `organisation_jahr` t
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
CREATE OR REPLACE VIEW `v_last_updated_fraktion` AS
  (SELECT
  'fraktion' table_name,
  'Fraktion' name,
  (select count(*) from `fraktion`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `fraktion` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_rat` AS
  (SELECT
  'rat' table_name,
  'Rat' name,
  (select count(*) from `rat`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `rat` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_kanton` AS
  (SELECT
  'kanton' table_name,
  'Kanton' name,
  (select count(*) from `kanton`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `kanton` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_kanton_jahr` AS
  (SELECT
  'kanton_jahr' table_name,
  'Kantonjahr' name,
  (select count(*) from `kanton_jahr`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `kanton_jahr` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_settings` AS
  (SELECT
  'settings' table_name,
  'Einstellungen' name,
  (select count(*) from `settings`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `settings` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_settings_category` AS
  (SELECT
  'settings_category' table_name,
  'Einstellungskategorien' name,
  (select count(*) from `settings_category`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `settings_category` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_zutrittsberechtigung` AS
  (SELECT
  'zutrittsberechtigung' table_name,
  'Zutrittsberechtigter' name,
  (select count(*) from `zutrittsberechtigung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `zutrittsberechtigung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_zutrittsberechtigung_anhang` AS
  (SELECT
  'zutrittsberechtigung_anhang' table_name,
  'Zutrittsberechtigunganhang' name,
  (select count(*) from `zutrittsberechtigung_anhang`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `zutrittsberechtigung_anhang` t
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
SELECT * FROM v_last_updated_organisation_anhang
UNION
SELECT * FROM v_last_updated_organisation_beziehung
UNION
SELECT * FROM v_last_updated_organisation_jahr
UNION
SELECT * FROM v_last_updated_parlamentarier
UNION
SELECT * FROM v_last_updated_parlamentarier_anhang
UNION
SELECT * FROM v_last_updated_partei
UNION
SELECT * FROM v_last_updated_fraktion
UNION
SELECT * FROM v_last_updated_rat
UNION
SELECT * FROM v_last_updated_kanton
UNION
SELECT * FROM v_last_updated_kanton_jahr
UNION
SELECT * FROM v_last_updated_settings
UNION
SELECT * FROM v_last_updated_settings_category
UNION
SELECT * FROM v_last_updated_zutrittsberechtigung
UNION
SELECT * FROM v_last_updated_zutrittsberechtigung_anhang;

CREATE OR REPLACE VIEW `v_last_updated_tables` AS
SELECT * FROM `v_last_updated_tables_unordered`
ORDER BY last_updated DESC;

-- ------------------------------------------------------------------------------
-- VIEWS
-- ------------------------------------------------------------------------------

CREATE OR REPLACE VIEW `v_settings_category` AS
SELECT `settings_category`.*,
UNIX_TIMESTAMP(created_date) as created_date_unix, UNIX_TIMESTAMP(updated_date) as updated_date_unix
FROM `settings_category`;

CREATE OR REPLACE VIEW `v_settings` AS
SELECT `settings`.*, settings_category.name as category_name,
UNIX_TIMESTAMP(settings.created_date) as created_date_unix, UNIX_TIMESTAMP(settings.updated_date) as updated_date_unix
FROM `settings`
LEFT JOIN `v_settings_category` settings_category
ON settings.category_id = settings_category.id;

CREATE OR REPLACE VIEW `v_country` AS
SELECT country.name_de as anzeige_name, country.*,
UNIX_TIMESTAMP(created_date) as created_date_unix, UNIX_TIMESTAMP(updated_date) as updated_date_unix
FROM `country`;

CREATE OR REPLACE VIEW `v_rat` AS
SELECT rat.name_de as anzeige_name, rat.*,
UNIX_TIMESTAMP(rat.created_date) as created_date_unix, UNIX_TIMESTAMP(rat.updated_date) as updated_date_unix, UNIX_TIMESTAMP(rat.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(rat.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(rat.freigabe_datum) as freigabe_datum_unix
FROM `rat`
ORDER BY `gewicht` ASC;;

CREATE OR REPLACE VIEW `v_kanton_jahr` AS
SELECT kanton_jahr.*,
UNIX_TIMESTAMP(kanton_jahr.created_date) as created_date_unix, UNIX_TIMESTAMP(kanton_jahr.updated_date) as updated_date_unix, UNIX_TIMESTAMP(kanton_jahr.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(kanton_jahr.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(kanton_jahr.freigabe_datum) as freigabe_datum_unix
FROM `kanton_jahr`;

CREATE OR REPLACE VIEW `v_kanton_jahr_last` AS
SELECT MAX(kanton_jahr.jahr) max_jahr, kanton_jahr.*
FROM `kanton_jahr`
GROUP BY kanton_jahr.kanton_id;

CREATE OR REPLACE VIEW `v_kanton_2012` AS
SELECT kanton.name_de as anzeige_name, kanton.*, kanton_jahr.`id` as kanton_jahr_id, kanton_jahr.`jahr`, kanton_jahr.einwohner, kanton_jahr.auslaenderanteil, kanton_jahr.bevoelkerungsdichte, kanton_jahr.anzahl_gemeinden, kanton_jahr.anzahl_nationalraete
FROM `kanton`
LEFT JOIN `v_kanton_jahr` kanton_jahr
ON kanton_jahr.kanton_id = kanton.id AND kanton_jahr.jahr=2012;

CREATE OR REPLACE VIEW `v_kanton` AS
SELECT kanton.name_de as anzeige_name, kanton.*, kanton_jahr.`id` as kanton_jahr_id, kanton_jahr.`jahr`, kanton_jahr.einwohner, kanton_jahr.auslaenderanteil, kanton_jahr.bevoelkerungsdichte, kanton_jahr.anzahl_gemeinden, kanton_jahr.anzahl_nationalraete
FROM `kanton`
LEFT JOIN `v_kanton_jahr_last` kanton_jahr
ON kanton_jahr.kanton_id = kanton.id;

CREATE OR REPLACE VIEW `v_interessenraum` AS
SELECT interessenraum.name as anzeige_name, interessenraum.*,
UNIX_TIMESTAMP(interessenraum.created_date) as created_date_unix, UNIX_TIMESTAMP(interessenraum.updated_date) as updated_date_unix, UNIX_TIMESTAMP(interessenraum.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(interessenraum.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(interessenraum.freigabe_datum) as freigabe_datum_unix
FROM `interessenraum` interessenraum
ORDER BY interessenraum.`reihenfolge` ASC;

CREATE OR REPLACE VIEW `v_kommission` AS
SELECT CONCAT(kommission.name, ' (', kommission.abkuerzung, ')') AS anzeige_name, kommission.*,
UNIX_TIMESTAMP(kommission.created_date) as created_date_unix, UNIX_TIMESTAMP(kommission.updated_date) as updated_date_unix, UNIX_TIMESTAMP(kommission.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(kommission.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(kommission.freigabe_datum) as freigabe_datum_unix
FROM `kommission`;

CREATE OR REPLACE VIEW `v_partei` AS
SELECT CONCAT(partei.name, ' (', partei.abkuerzung, ')') AS anzeige_name, partei.*,
UNIX_TIMESTAMP(partei.created_date) as created_date_unix, UNIX_TIMESTAMP(partei.updated_date) as updated_date_unix, UNIX_TIMESTAMP(partei.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(partei.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(partei.freigabe_datum) as freigabe_datum_unix
FROM `partei`;

CREATE OR REPLACE VIEW `v_fraktion` AS
SELECT CONCAT_WS(', ', fraktion.abkuerzung, fraktion.name) AS anzeige_name, fraktion.*,
UNIX_TIMESTAMP(fraktion.created_date) as created_date_unix, UNIX_TIMESTAMP(fraktion.updated_date) as updated_date_unix, UNIX_TIMESTAMP(fraktion.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(fraktion.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(fraktion.freigabe_datum) as freigabe_datum_unix
FROM `fraktion`;

CREATE OR REPLACE VIEW `v_interessenbindung_simple` AS
SELECT interessenbindung.*,
UNIX_TIMESTAMP(bis) as bis_unix, UNIX_TIMESTAMP(von) as von_unix,
UNIX_TIMESTAMP(interessenbindung.created_date) as created_date_unix, UNIX_TIMESTAMP(interessenbindung.updated_date) as updated_date_unix, UNIX_TIMESTAMP(interessenbindung.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(interessenbindung.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(interessenbindung.freigabe_datum) as freigabe_datum_unix
FROM `interessenbindung`;

CREATE OR REPLACE VIEW `v_mandat_simple` AS SELECT mandat.*,
UNIX_TIMESTAMP(bis) as bis_unix, UNIX_TIMESTAMP(von) as von_unix,
UNIX_TIMESTAMP(mandat.created_date) as created_date_unix, UNIX_TIMESTAMP(mandat.updated_date) as updated_date_unix, UNIX_TIMESTAMP(mandat.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(mandat.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(mandat.freigabe_datum) as freigabe_datum_unix
FROM `mandat`;

CREATE OR REPLACE VIEW `v_branche` AS
SELECT CONCAT(branche.name) AS anzeige_name,
branche.*,
kommission.anzeige_name as kommission,
UNIX_TIMESTAMP(branche.created_date) as created_date_unix, UNIX_TIMESTAMP(branche.updated_date) as updated_date_unix, UNIX_TIMESTAMP(branche.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(branche.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(branche.freigabe_datum) as freigabe_datum_unix
FROM `branche`
LEFT JOIN `v_kommission` kommission
ON kommission.id = branche.kommission_id
;

CREATE OR REPLACE VIEW `v_branche_name_with_null` AS
SELECT branche.id, CONCAT(branche.name) AS anzeige_name
FROM `branche`
UNION
SELECT NULL as ID, 'NULL' as anzeige_name
;

CREATE OR REPLACE VIEW `v_interessengruppe` AS
SELECT CONCAT(interessengruppe.name) AS anzeige_name,
interessengruppe.*,
branche.anzeige_name as branche,
branche.kommission_id as kommission_id,
branche.kommission as kommission,
UNIX_TIMESTAMP(interessengruppe.created_date) as created_date_unix, UNIX_TIMESTAMP(interessengruppe.updated_date) as updated_date_unix, UNIX_TIMESTAMP(interessengruppe.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(interessengruppe.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(interessengruppe.freigabe_datum) as freigabe_datum_unix
FROM `interessengruppe`
LEFT JOIN `v_branche` branche
ON branche.id = interessengruppe.branche_id
;

CREATE OR REPLACE VIEW `v_organisation_jahr` AS
SELECT `organisation_jahr`.*,
UNIX_TIMESTAMP(organisation_jahr.created_date) as created_date_unix, UNIX_TIMESTAMP(organisation_jahr.updated_date) as updated_date_unix, UNIX_TIMESTAMP(organisation_jahr.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(organisation_jahr.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(organisation_jahr.freigabe_datum) as freigabe_datum_unix
FROM `organisation_jahr`;

CREATE OR REPLACE VIEW `v_kanton_jahr_last` AS
SELECT MAX(kanton_jahr.jahr) max_jahr, kanton_jahr.*
FROM `kanton_jahr`
GROUP BY kanton_jahr.kanton_id;

CREATE OR REPLACE VIEW `v_organisation_jahr_last` AS
SELECT MAX(organisation_jahr.jahr) max_jahr, `organisation_jahr`.*
FROM `organisation_jahr`
GROUP BY organisation_jahr.organisation_id;

CREATE OR REPLACE VIEW `v_organisation_anhang` AS
SELECT organisation_anhang.organisation_id as organisation_id2, organisation_anhang.*
FROM `organisation_anhang`;

CREATE OR REPLACE VIEW `v_in_kommission` AS
SELECT in_kommission.*, rat.abkuerzung as rat, rat.abkuerzung as ratstyp, kommission.abkuerzung as kommission_abkuerzung, kommission.name as kommission_name, kommission.art as kommission_art, kommission.typ as kommission_typ, kommission.beschreibung as kommission_beschreibung, kommission.sachbereiche as kommission_sachbereiche, kommission.mutter_kommission_id as kommission_mutter_kommission_id, kommission.parlament_url as kommission_parlament_url,
UNIX_TIMESTAMP(bis) as bis_unix, UNIX_TIMESTAMP(von) as von_unix,
UNIX_TIMESTAMP(in_kommission.created_date) as created_date_unix, UNIX_TIMESTAMP(in_kommission.updated_date) as updated_date_unix, UNIX_TIMESTAMP(in_kommission.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(in_kommission.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(in_kommission.freigabe_datum) as freigabe_datum_unix
FROM `in_kommission`
INNER JOIN `parlamentarier`
ON in_kommission.parlamentarier_id = parlamentarier.id
LEFT JOIN `kanton`
ON parlamentarier.kanton_id = kanton.id
LEFT JOIN `rat`
ON parlamentarier.rat_id = rat.id
LEFT JOIN `kommission`
ON in_kommission.kommission_id = kommission.id;

CREATE OR REPLACE VIEW `v_organisation_beziehung` AS
SELECT organisation_beziehung.*,
UNIX_TIMESTAMP(bis) as bis_unix, UNIX_TIMESTAMP(von) as von_unix,
UNIX_TIMESTAMP(organisation_beziehung.created_date) as created_date_unix, UNIX_TIMESTAMP(organisation_beziehung.updated_date) as updated_date_unix, UNIX_TIMESTAMP(organisation_beziehung.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(organisation_beziehung.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(organisation_beziehung.freigabe_datum) as freigabe_datum_unix
FROM `organisation_beziehung`;

CREATE OR REPLACE VIEW `v_parlamentarier_anhang` AS
SELECT parlamentarier_anhang.parlamentarier_id as parlamentarier_id2, parlamentarier_anhang.*
FROM `parlamentarier_anhang`;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_anhang` AS
SELECT zutrittsberechtigung_anhang.zutrittsberechtigung_id as zutrittsberechtigung_id2, zutrittsberechtigung_anhang.*
FROM `zutrittsberechtigung_anhang`;

CREATE OR REPLACE VIEW `v_user` AS
SELECT IFNULL(CONCAT_WS(' ', u.vorname, u.nachname ), u.name) as anzeige_name, u.name as username, u.*,
UNIX_TIMESTAMP(u.created_date) as created_date_unix, UNIX_TIMESTAMP(u.updated_date) as updated_date_unix
FROM `user` u;

CREATE OR REPLACE VIEW `v_user_permission` AS
SELECT t.*
FROM `user_permission` t;

CREATE OR REPLACE VIEW `v_mil_grad` AS
SELECT mil_grad.*,
UNIX_TIMESTAMP(mil_grad.created_date) as created_date_unix, UNIX_TIMESTAMP(mil_grad.updated_date) as updated_date_unix
FROM `mil_grad`
ORDER BY `ranghoehe` ASC;

CREATE OR REPLACE VIEW `v_organisation_simple` AS
SELECT CONCAT_WS('; ', o.name_de, o.name_fr, o.name_it) AS anzeige_name,
CONCAT_WS('; ', o.name_de , o.name_fr, o.name_it) AS name,
o.*,
branche.anzeige_name as branche,
interessengruppe1.anzeige_name as interessengruppe,
interessengruppe1.branche as interessengruppe_branche,
interessengruppe1.branche_id as interessengruppe_branche_id,
interessengruppe2.anzeige_name as interessengruppe2,
interessengruppe2.branche as interessengruppe2_branche,
interessengruppe2.branche_id as interessengruppe2_branche_id,
interessengruppe3.anzeige_name as interessengruppe3,
interessengruppe3.branche as interessengruppe3_branche,
interessengruppe3.branche_id as interessengruppe3_branche_id,
country.name_de as land,
interessenraum.anzeige_name as interessenraum,
organisation_jahr.`id` as organisation_jahr_id, organisation_jahr.jahr, organisation_jahr.umsatz, organisation_jahr.gewinn, organisation_jahr.kapital, organisation_jahr.mitarbeiter_weltweit, organisation_jahr.mitarbeiter_schweiz, organisation_jahr.geschaeftsbericht_url, organisation_jahr.quelle_url,
UNIX_TIMESTAMP(o.created_date) as created_date_unix, UNIX_TIMESTAMP(o.updated_date) as updated_date_unix, UNIX_TIMESTAMP(o.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(o.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(o.freigabe_datum) as freigabe_datum_unix
FROM `organisation` o
LEFT JOIN `v_branche` branche
ON branche.id = o.branche_id
LEFT JOIN `v_interessengruppe` interessengruppe1
ON interessengruppe1.id = o.interessengruppe_id
LEFT JOIN `v_interessengruppe` interessengruppe2
ON interessengruppe2.id = o.interessengruppe2_id
LEFT JOIN `v_interessengruppe` interessengruppe3
ON interessengruppe3.id = o.interessengruppe3_id
LEFT JOIN `v_country` country
ON country.id = o.land_id
LEFT JOIN `v_interessenraum` interessenraum
ON interessenraum.id = o.interessenraum_id
LEFT JOIN `v_organisation_jahr_last` organisation_jahr
ON organisation_jahr.organisation_id = o.id
;

CREATE OR REPLACE VIEW `v_interessenbindung` AS
SELECT interessenbindung.*,
IF(organisation.vernehmlassung IN ('immmer', 'punktuell')
  AND interessenbindung.art IN ('geschaeftsfuehrend','vorstand')
  AND EXISTS (
    SELECT in_kommission.kommission_id
    FROM in_kommission in_kommission
    LEFT JOIN branche branche
    ON in_kommission.kommission_id = branche.kommission_id
    WHERE (in_kommission.bis >= NOW() OR in_kommission.bis IS NULL) 
    AND in_kommission.parlamentarier_id = parlamentarier.id
    AND branche.id IN (organisation.branche_id, organisation.interessengruppe_branche_id, organisation.interessengruppe2_branche_id, organisation.interessengruppe3_branche_id)), 'hoch', 
IF(organisation.vernehmlassung IN ('immmer', 'punktuell')
  AND interessenbindung.art IN ('taetig','beirat','finanziell'), 'mittel', 'tief')) wirksamkeit,
parlamentarier.im_rat_seit as parlamentarier_im_rat_seit
FROM `v_interessenbindung_simple` interessenbindung
INNER JOIN `v_organisation_simple` organisation
ON interessenbindung.organisation_id = organisation.id
INNER JOIN `parlamentarier` parlamentarier
ON interessenbindung.parlamentarier_id = parlamentarier.id;

CREATE OR REPLACE VIEW `v_mandat` AS
SELECT mandat.*,
IF(organisation.vernehmlassung IN ('immmer', 'punktuell')
  AND mandat.art IN ('geschaeftsfuehrend','vorstand')
  , 'hoch', 
IF((organisation.vernehmlassung IN ('immmer', 'punktuell')
  AND mandat.art IN ('taetig','beirat','finanziell'))
  OR (mandat.art IN ('geschaeftsfuehrend','vorstand')), 'mittel', 'tief')) wirksamkeit
FROM `v_mandat_simple` mandat
INNER JOIN `organisation` organisation
ON mandat.organisation_id = organisation.id;

CREATE OR REPLACE VIEW `v_organisation_lobbyeinfluss` AS
SELECT organisation.id,
COUNT(DISTINCT interessenbindung_tief.id) as anzahl_interessenbindung_tief,
COUNT(DISTINCT interessenbindung_mittel.id) as anzahl_interessenbindung_mittel,
COUNT(DISTINCT interessenbindung_hoch.id) as anzahl_interessenbindung_hoch,
COUNT(DISTINCT interessenbindung_tief_nach_wahl.id) as anzahl_interessenbindung_tief_nach_wahl,
COUNT(DISTINCT interessenbindung_mittel_nach_wahl.id) as anzahl_interessenbindung_mittel_nach_wahl,
COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) as anzahl_interessenbindung_hoch_nach_wahl,
COUNT(DISTINCT mandat_tief.id) as anzahl_mandat_tief,
COUNT(DISTINCT mandat_mittel.id) as anzahl_mandat_mittel,
COUNT(DISTINCT mandat_hoch.id) as anzahl_mandat_hoch,
IF(COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) > 0 OR COUNT(DISTINCT interessenbindung_hoch.id) > 1 OR (COUNT(DISTINCT interessenbindung_hoch.id) > 0 AND COUNT(DISTINCT mandat_hoch.id) > 0), 'extrem hoch',
IF(COUNT(DISTINCT interessenbindung_hoch.id) > 0 OR (COUNT(DISTINCT interessenbindung_mittel.id) > 0 AND COUNT(DISTINCT mandat_mittel.id) > 0), 'hoch', 
IF(COUNT(DISTINCT interessenbindung_mittel.id) > 0 OR COUNT(DISTINCT mandat_hoch.id) > 0, 'mittel',
'tief'))) as lobbyeinfluss
FROM `organisation` organisation
LEFT JOIN `v_interessenbindung` interessenbindung_hoch ON organisation.id = interessenbindung_hoch.organisation_id AND (interessenbindung_hoch.bis IS NULL OR interessenbindung_hoch.bis >= NOW()) AND interessenbindung_hoch.wirksamkeit='hoch'
LEFT JOIN `v_interessenbindung` interessenbindung_mittel ON organisation.id = interessenbindung_mittel.organisation_id AND (interessenbindung_mittel.bis IS NULL OR interessenbindung_mittel.bis >= NOW()) AND interessenbindung_mittel.wirksamkeit='mittel'
LEFT JOIN `v_interessenbindung` interessenbindung_tief ON organisation.id = interessenbindung_tief.organisation_id AND (interessenbindung_tief.bis IS NULL OR interessenbindung_tief.bis >= NOW()) AND interessenbindung_tief.wirksamkeit='tief'
LEFT JOIN `v_interessenbindung` interessenbindung_hoch_nach_wahl ON organisation.id = interessenbindung_hoch_nach_wahl.organisation_id AND (interessenbindung_hoch_nach_wahl.bis IS NULL OR interessenbindung_hoch_nach_wahl.bis >= NOW()) AND interessenbindung_hoch_nach_wahl.wirksamkeit='hoch' AND interessenbindung_hoch_nach_wahl.von > interessenbindung_hoch_nach_wahl.parlamentarier_im_rat_seit
LEFT JOIN `v_interessenbindung` interessenbindung_mittel_nach_wahl ON organisation.id = interessenbindung_mittel_nach_wahl.organisation_id AND (interessenbindung_mittel_nach_wahl.bis IS NULL OR interessenbindung_mittel_nach_wahl.bis >= NOW()) AND interessenbindung_mittel_nach_wahl.wirksamkeit='mittel' AND interessenbindung_mittel_nach_wahl.von > interessenbindung_mittel_nach_wahl.parlamentarier_im_rat_seit
LEFT JOIN `v_interessenbindung` interessenbindung_tief_nach_wahl ON organisation.id = interessenbindung_tief_nach_wahl.organisation_id AND (interessenbindung_tief_nach_wahl.bis IS NULL OR interessenbindung_tief_nach_wahl.bis >= NOW()) AND interessenbindung_tief_nach_wahl.wirksamkeit='tief' AND interessenbindung_tief_nach_wahl.von > interessenbindung_tief_nach_wahl.parlamentarier_im_rat_seit
LEFT JOIN `v_mandat` mandat_hoch ON organisation.id = mandat_hoch.organisation_id AND (mandat_hoch.bis IS NULL OR mandat_hoch.bis >= NOW()) AND mandat_hoch.wirksamkeit='hoch'
LEFT JOIN `v_mandat` mandat_mittel ON organisation.id = mandat_mittel.organisation_id AND (mandat_mittel.bis IS NULL OR mandat_mittel.bis >= NOW()) AND mandat_mittel.wirksamkeit='mittel'
LEFT JOIN `v_mandat` mandat_tief ON organisation.id = mandat_tief.organisation_id AND (mandat_tief.bis IS NULL OR mandat_tief.bis >= NOW()) AND mandat_tief.wirksamkeit='tief'
GROUP BY organisation.id;

CREATE OR REPLACE VIEW `v_organisation` AS
SELECT
organisation.*,
lobbyeinfluss.lobbyeinfluss
FROM `v_organisation_simple` organisation
LEFT JOIN `v_organisation_lobbyeinfluss` lobbyeinfluss
ON lobbyeinfluss.id = organisation.id
;

CREATE OR REPLACE VIEW `v_parlamentarier_simple` AS
SELECT CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) AS anzeige_name,
CONCAT_WS(' ', parlamentarier.vorname, parlamentarier.zweiter_vorname, parlamentarier.nachname) AS name,
parlamentarier.*,
parlamentarier.im_rat_seit as von, parlamentarier.im_rat_bis as bis,
UNIX_TIMESTAMP(geburtstag) as geburtstag_unix, 
UNIX_TIMESTAMP(im_rat_seit) as im_rat_seit_unix, UNIX_TIMESTAMP(im_rat_bis) as im_rat_bis_unix,
UNIX_TIMESTAMP(parlamentarier.created_date) as created_date_unix, UNIX_TIMESTAMP(parlamentarier.updated_date) as updated_date_unix, UNIX_TIMESTAMP(parlamentarier.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(parlamentarier.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(parlamentarier.freigabe_datum) as freigabe_datum_unix,
UNIX_TIMESTAMP(im_rat_seit) as von_unix, UNIX_TIMESTAMP(im_rat_bis) as bis_unix
FROM `parlamentarier` parlamentarier;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_lobbyfaktor` AS
SELECT zutrittsberechtigung.id,
COUNT(DISTINCT mandat_tief.id) as anzahl_mandat_tief,
COUNT(DISTINCT mandat_mittel.id) as anzahl_mandat_mittel,
COUNT(DISTINCT mandat_hoch.id) as anzahl_mandat_hoch,
COUNT(DISTINCT mandat_tief.id) + COUNT(DISTINCT mandat_mittel.id) * 5 + COUNT(DISTINCT mandat_hoch.id) * 11 as lobbyfaktor
FROM `zutrittsberechtigung` zutrittsberechtigung
LEFT JOIN `v_mandat` mandat_hoch ON zutrittsberechtigung.id = mandat_hoch.zutrittsberechtigung_id AND (mandat_hoch.bis IS NULL OR mandat_hoch.bis >= NOW()) AND mandat_hoch.wirksamkeit='hoch'
LEFT JOIN `v_mandat` mandat_mittel ON zutrittsberechtigung.id = mandat_mittel.zutrittsberechtigung_id AND (mandat_mittel.bis IS NULL OR mandat_mittel.bis >= NOW()) AND mandat_mittel.wirksamkeit='mittel'
LEFT JOIN `v_mandat` mandat_tief ON zutrittsberechtigung.id = mandat_tief.zutrittsberechtigung_id AND (mandat_tief.bis IS NULL OR mandat_tief.bis >= NOW()) AND mandat_tief.wirksamkeit='tief'
GROUP BY zutrittsberechtigung.id;

CREATE OR REPLACE VIEW `v_parlamentarier_lobbyfaktor` AS
SELECT parlamentarier.id,
COUNT(DISTINCT interessenbindung_tief.id) as anzahl_interessenbindung_tief,
COUNT(DISTINCT interessenbindung_mittel.id) as anzahl_interessenbindung_mittel,
COUNT(DISTINCT interessenbindung_hoch.id) as anzahl_interessenbindung_hoch,
COUNT(DISTINCT interessenbindung_tief_nach_wahl.id) as anzahl_interessenbindung_tief_nach_wahl,
COUNT(DISTINCT interessenbindung_mittel_nach_wahl.id) as anzahl_interessenbindung_mittel_nach_wahl,
COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) as anzahl_interessenbindung_hoch_nach_wahl,
(COUNT(DISTINCT interessenbindung_tief.id) * 1 + COUNT(DISTINCT interessenbindung_mittel.id) * 5 + COUNT(DISTINCT interessenbindung_hoch.id) * 11) + (COUNT(DISTINCT interessenbindung_tief_nach_wahl.id) * 1 + COUNT(DISTINCT interessenbindung_mittel_nach_wahl.id) * 5 + COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) * 11) as lobbyfaktor,
COUNT(DISTINCT interessenbindung_tief.id) * 1 + COUNT(DISTINCT interessenbindung_mittel.id) * 5 + COUNT(DISTINCT interessenbindung_hoch.id) * 11 as lobbyfaktor_einfach
FROM `v_parlamentarier_simple` parlamentarier
LEFT JOIN `v_interessenbindung` interessenbindung_hoch ON parlamentarier.id = interessenbindung_hoch.parlamentarier_id AND (interessenbindung_hoch.bis IS NULL OR interessenbindung_hoch.bis >= NOW()) AND interessenbindung_hoch.wirksamkeit='hoch'
LEFT JOIN `v_interessenbindung` interessenbindung_mittel ON parlamentarier.id = interessenbindung_mittel.parlamentarier_id AND (interessenbindung_mittel.bis IS NULL OR interessenbindung_mittel.bis >= NOW()) AND interessenbindung_mittel.wirksamkeit='mittel'
LEFT JOIN `v_interessenbindung` interessenbindung_tief ON parlamentarier.id = interessenbindung_tief.parlamentarier_id AND (interessenbindung_tief.bis IS NULL OR interessenbindung_tief.bis >= NOW()) AND interessenbindung_tief.wirksamkeit='tief'
LEFT JOIN `v_interessenbindung` interessenbindung_hoch_nach_wahl ON parlamentarier.id = interessenbindung_hoch_nach_wahl.parlamentarier_id AND (interessenbindung_hoch_nach_wahl.bis IS NULL OR interessenbindung_hoch_nach_wahl.bis >= NOW()) AND interessenbindung_hoch_nach_wahl.wirksamkeit='hoch' AND interessenbindung_hoch_nach_wahl.von > parlamentarier.im_rat_seit
LEFT JOIN `v_interessenbindung` interessenbindung_mittel_nach_wahl ON parlamentarier.id = interessenbindung_mittel_nach_wahl.parlamentarier_id AND (interessenbindung_mittel_nach_wahl.bis IS NULL OR interessenbindung_mittel_nach_wahl.bis >= NOW()) AND interessenbindung_mittel_nach_wahl.wirksamkeit='mittel' AND interessenbindung_mittel_nach_wahl.von > parlamentarier.im_rat_seit
LEFT JOIN `v_interessenbindung` interessenbindung_tief_nach_wahl ON parlamentarier.id = interessenbindung_tief_nach_wahl.parlamentarier_id AND (interessenbindung_tief_nach_wahl.bis IS NULL OR interessenbindung_tief_nach_wahl.bis >= NOW()) AND interessenbindung_tief_nach_wahl.wirksamkeit='tief' AND interessenbindung_tief_nach_wahl.von > parlamentarier.im_rat_seit
GROUP BY parlamentarier.id;

CREATE OR REPLACE VIEW `v_parlamentarier_lobbyfaktor_max` AS
SELECT 
MAX(lobbyfaktor.anzahl_interessenbindung_tief) as anzahl_interessenbindung_tief_max,
MAX(lobbyfaktor.anzahl_interessenbindung_mittel) as anzahl_interessenbindung_mittel_max,
MAX(lobbyfaktor.anzahl_interessenbindung_hoch) as anzahl_interessenbindung_hoch_max,
MAX(lobbyfaktor) as lobbyfaktor_max
FROM `v_parlamentarier_lobbyfaktor` lobbyfaktor
-- GROUP BY lobbyfaktor.id
;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_lobbyfaktor_max` AS
SELECT 
MAX(lobbyfaktor.anzahl_mandat_tief) as anzahl_mandat_tief_max,
MAX(lobbyfaktor.anzahl_mandat_mittel) as anzahl_mandat_mittel_max,
MAX(lobbyfaktor.anzahl_mandat_hoch) as anzahl_mandat_hoch_max,
MAX(lobbyfaktor) as lobbyfaktor_max
FROM `v_zutrittsberechtigung_lobbyfaktor` lobbyfaktor
-- GROUP BY lobbyfaktor.id
;

CREATE OR REPLACE VIEW `v_parlamentarier` AS
SELECT parlamentarier.*,
rat.abkuerzung as rat, rat.abkuerzung as ratstyp, kanton.abkuerzung as kanton_abkuerzung, kanton.abkuerzung as kanton, kanton.name_de as kanton_name_de,
CAST(
(CASE rat.abkuerzung
  WHEN 'SR' THEN ROUND(kanton.einwohner / kanton.anzahl_staenderaete)
  WHEN 'NR' THEN ROUND(kanton.einwohner / kanton.anzahl_nationalraete)
  ELSE NULL
END)
AS UNSIGNED INTEGER) AS vertretene_bevoelkerung,
GROUP_CONCAT(DISTINCT CONCAT(k.name, '(', k.abkuerzung, ')') ORDER BY k.abkuerzung SEPARATOR ', ') kommissionen_namen,
GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') kommissionen_abkuerzung,
COUNT(DISTINCT k.id) AS kommissionen_anzahl,
partei.abkuerzung AS partei, partei.name AS partei_name, fraktion.abkuerzung AS fraktion, mil_grad.name as militaerischer_grad,
CONCAT(IF(parlamentarier.geschlecht='M', rat.name_de, ''), IF(parlamentarier.geschlecht='F' AND rat.abkuerzung='NR', 'Nationalrätin', ''), IF(parlamentarier.geschlecht='F' AND rat.abkuerzung='SR', 'Ständerätin', '')) titel_de,
lobbyfaktor.anzahl_interessenbindung_tief,
lobbyfaktor.anzahl_interessenbindung_mittel,
lobbyfaktor.anzahl_interessenbindung_hoch,
lobbyfaktor.anzahl_interessenbindung_tief_nach_wahl,
lobbyfaktor.anzahl_interessenbindung_mittel_nach_wahl,
lobbyfaktor.anzahl_interessenbindung_hoch_nach_wahl,
lobbyfaktor.lobbyfaktor,
lobbyfaktor_max.lobbyfaktor_max,
lobbyfaktor.lobbyfaktor / lobbyfaktor_max.lobbyfaktor_max as lobbyfaktor_percent_max,
lobbyfaktor_max.anzahl_interessenbindung_tief_max,
lobbyfaktor_max.anzahl_interessenbindung_mittel_max,
lobbyfaktor_max.anzahl_interessenbindung_hoch_max
FROM `v_parlamentarier_simple` parlamentarier
LEFT JOIN `v_in_kommission` ik ON parlamentarier.id = ik.parlamentarier_id AND ik.bis IS NULL
LEFT JOIN `v_kommission` k ON ik.kommission_id=k.id
LEFT JOIN `v_partei` partei ON parlamentarier.partei_id=partei.id
LEFT JOIN `v_fraktion` fraktion ON parlamentarier.fraktion_id=fraktion.id
LEFT JOIN `v_mil_grad` mil_grad ON parlamentarier.militaerischer_grad_id=mil_grad.id
LEFT JOIN `v_kanton` kanton ON parlamentarier.kanton_id = kanton.id
LEFT JOIN `v_rat` rat ON parlamentarier.rat_id = rat.id
LEFT JOIN `v_parlamentarier_lobbyfaktor` lobbyfaktor ON parlamentarier.id = lobbyfaktor.id
, v_parlamentarier_lobbyfaktor_max lobbyfaktor_max
GROUP BY parlamentarier.id;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung` AS
SELECT CONCAT(zutrittsberechtigung.nachname, ', ', zutrittsberechtigung.vorname) AS anzeige_name, CONCAT(zutrittsberechtigung.vorname, ' ', zutrittsberechtigung.nachname) AS name,
zutrittsberechtigung.*,
partei.abkuerzung AS partei,
parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.freigabe_datum as parlamentarier_freigabe_datum, UNIX_TIMESTAMP(parlamentarier.freigabe_datum) as parlamentarier_freigabe_datum_unix,
UNIX_TIMESTAMP(zutrittsberechtigung.bis) as bis_unix, UNIX_TIMESTAMP(zutrittsberechtigung.von) as von_unix,
UNIX_TIMESTAMP(zutrittsberechtigung.created_date) as created_date_unix, UNIX_TIMESTAMP(zutrittsberechtigung.updated_date) as updated_date_unix, UNIX_TIMESTAMP(zutrittsberechtigung.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(zutrittsberechtigung.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(zutrittsberechtigung.freigabe_datum) as freigabe_datum_unix,
lobbyfaktor.anzahl_mandat_tief,
lobbyfaktor.anzahl_mandat_mittel,
lobbyfaktor.anzahl_mandat_hoch,
lobbyfaktor.lobbyfaktor,
lobbyfaktor_max.lobbyfaktor_max,
lobbyfaktor.lobbyfaktor / lobbyfaktor_max.lobbyfaktor_max as lobbyfaktor_percent_max,
lobbyfaktor_max.anzahl_mandat_tief_max,
lobbyfaktor_max.anzahl_mandat_mittel_max,
lobbyfaktor_max.anzahl_mandat_hoch_max
FROM `zutrittsberechtigung`
LEFT JOIN `v_partei` partei
ON zutrittsberechtigung.partei_id=partei.id
LEFT JOIN `v_parlamentarier` parlamentarier
ON parlamentarier.id = zutrittsberechtigung.parlamentarier_id
LEFT JOIN `v_zutrittsberechtigung_lobbyfaktor` lobbyfaktor ON zutrittsberechtigung.id = lobbyfaktor.id
, v_zutrittsberechtigung_lobbyfaktor_max lobbyfaktor_max
;

-- Kommissionen für Parlamentarier
-- Connector: in_kommission.parlamentarier_id
CREATE OR REPLACE VIEW `v_in_kommission_liste` AS
SELECT kommission.abkuerzung, kommission.name, kommission.typ, kommission.art, kommission.beschreibung, kommission.sachbereiche, kommission.mutter_kommission_id, kommission.parlament_url, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_kommission kommission
  ON in_kommission.kommission_id = kommission.id
ORDER BY kommission.abkuerzung;

-- Parlamentarier einer Kommission
-- Connector: in_kommission.kommission_id
CREATE OR REPLACE VIEW `v_in_kommission_parlamentarier` AS
SELECT
`parlamentarier`.`anzeige_name` as parlamentarier_name
, `parlamentarier`.`name`
, `parlamentarier`.`nachname`
, `parlamentarier`.`vorname`
, `parlamentarier`.`zweiter_vorname`
, `parlamentarier`.`rat_id`
, `parlamentarier`.`kanton_id`
, `parlamentarier`.`kommissionen`
, `parlamentarier`.`partei_id`
, `parlamentarier`.`parteifunktion`
, `parlamentarier`.`fraktion_id`
, `parlamentarier`.`fraktionsfunktion`
, `parlamentarier`.`im_rat_seit`
, `parlamentarier`.`im_rat_bis`
, `parlamentarier`.`ratswechsel`
, `parlamentarier`.`ratsunterbruch_von`
, `parlamentarier`.`ratsunterbruch_bis`
, `parlamentarier`.`beruf`
, `parlamentarier`.`beruf_interessengruppe_id`
, `parlamentarier`.`zivilstand`
, `parlamentarier`.`anzahl_kinder`
, `parlamentarier`.`militaerischer_grad_id`
, `parlamentarier`.`geschlecht`
, `parlamentarier`.`geburtstag`
, `parlamentarier`.`photo`
, `parlamentarier`.`photo_dateiname`
, `parlamentarier`.`photo_dateierweiterung`
, `parlamentarier`.`photo_dateiname_voll`
, `parlamentarier`.`photo_mime_type`
, `parlamentarier`.`kleinbild`
, `parlamentarier`.`sitzplatz`
, `parlamentarier`.`email`
, `parlamentarier`.`homepage`
, `parlamentarier`.`parlament_biografie_id`
, `parlamentarier`.`twitter_name`
, `parlamentarier`.`linkedin_profil_url`
, `parlamentarier`.`xing_profil_name`
, `parlamentarier`.`facebook_name`
, `parlamentarier`.`arbeitssprache`
, `parlamentarier`.`adresse_firma`
, `parlamentarier`.`adresse_strasse`
, `parlamentarier`.`adresse_zusatz`
, `parlamentarier`.`adresse_plz`
, `parlamentarier`.`adresse_ort`
, `parlamentarier`.`im_rat_seit_unix`
, `parlamentarier`.`im_rat_bis_unix`
-- , `parlamentarier`.`von`
-- , `parlamentarier`.`bis`
-- , `parlamentarier`.`von_unix`
-- , `parlamentarier`.`bis_unix`
-- , `parlamentarier`.`rat`
-- , `parlamentarier`.`ratstyp`
, `parlamentarier`.`kanton`
, `parlamentarier`.`vertretene_bevoelkerung`
, `parlamentarier`.`kommissionen_namen`
, `parlamentarier`.`kommissionen_abkuerzung`
, `parlamentarier`.`partei`
, `parlamentarier`.`fraktion`
, `parlamentarier`.`militaerischer_grad`
, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_parlamentarier parlamentarier
  ON in_kommission.parlamentarier_id = parlamentarier.id
ORDER BY parlamentarier.anzeige_name;

-- Interessenbindung eines Parlamentariers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste` AS
SELECT
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`name`
-- , `organisation`.`id`
, `organisation`.`name_de`
, `organisation`.`name_fr`
, `organisation`.`name_it`
, `organisation`.`ort`
, `organisation`.`land_id`
, `organisation`.`interessenraum_id`
, `organisation`.`rechtsform`
, `organisation`.`typ`
, `organisation`.`vernehmlassung`
, `organisation`.`interessengruppe_id`
, `organisation`.`interessengruppe2_id`
, `organisation`.`interessengruppe3_id`
, `organisation`.`branche_id`
, `organisation`.`homepage`
, `organisation`.`handelsregister_url`
, `organisation`.`twitter_name`
, `organisation`.`beschreibung` as organisation_beschreibung
, `organisation`.`adresse_strasse`
, `organisation`.`adresse_zusatz`
, `organisation`.`adresse_plz`
, `organisation`.`branche`
, `organisation`.`interessengruppe`
, `organisation`.`interessengruppe_branche`
, `organisation`.`interessengruppe_branche_id`
, `organisation`.`interessengruppe2`
, `organisation`.`interessengruppe2_branche`
, `organisation`.`interessengruppe2_branche_id`
, `organisation`.`interessengruppe3`
, `organisation`.`interessengruppe3_branche`
, `organisation`.`interessengruppe3_branche_id`
, `organisation`.`land`
, `organisation`.`interessenraum`
, `organisation`.`organisation_jahr_id`
, `organisation`.`jahr`
, `organisation`.`umsatz`
, `organisation`.`gewinn`
, `organisation`.`kapital`
, `organisation`.`mitarbeiter_weltweit`
, `organisation`.`mitarbeiter_schweiz`
, `organisation`.`geschaeftsbericht_url`
-- , `organisation`.`quelle_url`
, interessenbindung.*
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
ORDER BY interessenbindung.wirksamkeit, organisation.anzeige_name;

-- Indirekte Interessenbindungen eines Parlamentariers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste_indirekt` AS
SELECT 'direkt' as beziehung, interessenbindung_liste.* FROM v_interessenbindung_liste interessenbindung_liste
UNION
SELECT 'indirekt' as beziehung, 
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`name`
-- , `organisation`.`id`
, `organisation`.`name_de`
, `organisation`.`name_fr`
, `organisation`.`name_it`
, `organisation`.`ort`
, `organisation`.`land_id`
, `organisation`.`interessenraum_id`
, `organisation`.`rechtsform`
, `organisation`.`typ`
, `organisation`.`vernehmlassung`
, `organisation`.`interessengruppe_id`
, `organisation`.`interessengruppe2_id`
, `organisation`.`interessengruppe3_id`
, `organisation`.`branche_id`
, `organisation`.`homepage`
, `organisation`.`handelsregister_url`
, `organisation`.`twitter_name`
, `organisation`.`beschreibung` as organisation_beschreibung
, `organisation`.`adresse_strasse`
, `organisation`.`adresse_zusatz`
, `organisation`.`adresse_plz`
, `organisation`.`branche`
, `organisation`.`interessengruppe`
, `organisation`.`interessengruppe_branche`
, `organisation`.`interessengruppe_branche_id`
, `organisation`.`interessengruppe2`
, `organisation`.`interessengruppe2_branche`
, `organisation`.`interessengruppe2_branche_id`
, `organisation`.`interessengruppe3`
, `organisation`.`interessengruppe3_branche`
, `organisation`.`interessengruppe3_branche_id`
, `organisation`.`land`
, `organisation`.`interessenraum`
, `organisation`.`organisation_jahr_id`
, `organisation`.`jahr`
, `organisation`.`umsatz`
, `organisation`.`gewinn`
, `organisation`.`kapital`
, `organisation`.`mitarbeiter_weltweit`
, `organisation`.`mitarbeiter_schweiz`
, `organisation`.`geschaeftsbericht_url`
-- , `organisation`.`quelle_url`
, interessenbindung.*
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON interessenbindung.organisation_id = organisation_beziehung.organisation_id
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, organisation_name;

-- Mandate einer Zutrittsberechtigung (INNER JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mandate` AS
SELECT zutrittsberechtigung.parlamentarier_id, 
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`name`
-- , `organisation`.`id`
, `organisation`.`name_de`
, `organisation`.`name_fr`
, `organisation`.`name_it`
, `organisation`.`ort`
, `organisation`.`land_id`
, `organisation`.`interessenraum_id`
, `organisation`.`rechtsform`
, `organisation`.`typ`
, `organisation`.`vernehmlassung`
, `organisation`.`interessengruppe_id`
, `organisation`.`interessengruppe2_id`
, `organisation`.`interessengruppe3_id`
, `organisation`.`branche_id`
, `organisation`.`homepage`
, `organisation`.`handelsregister_url`
, `organisation`.`twitter_name`
, `organisation`.`beschreibung` as organisation_beschreibung
, `organisation`.`adresse_strasse`
, `organisation`.`adresse_zusatz`
, `organisation`.`adresse_plz`
, `organisation`.`branche`
, `organisation`.`interessengruppe`
, `organisation`.`interessengruppe_branche`
, `organisation`.`interessengruppe2`
, `organisation`.`interessengruppe2_branche`
, `organisation`.`interessengruppe3`
, `organisation`.`interessengruppe3_branche`
, `organisation`.`land`
, `organisation`.`interessenraum`
, `organisation`.`organisation_jahr_id`
, `organisation`.`jahr`
, `organisation`.`umsatz`
, `organisation`.`gewinn`
, `organisation`.`kapital`
, `organisation`.`mitarbeiter_weltweit`
, `organisation`.`mitarbeiter_schweiz`
, `organisation`.`geschaeftsbericht_url`
-- , `organisation`.`quelle_url`
, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name
, zutrittsberechtigung.funktion
, mandat.*
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_mandat mandat
  ON zutrittsberechtigung.id = mandat.zutrittsberechtigung_id
INNER JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
ORDER BY mandat.wirksamkeit, organisation.anzeige_name;

-- Mandate einer Zutrittsberechtigung (LFET JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mit_mandaten` AS
SELECT 
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`name`
-- , `organisation`.`id`
, `organisation`.`name_de`
, `organisation`.`name_fr`
, `organisation`.`name_it`
, `organisation`.`ort`
, `organisation`.`land_id`
, `organisation`.`interessenraum_id`
, `organisation`.`rechtsform`
, `organisation`.`typ`
, `organisation`.`vernehmlassung`
, `organisation`.`interessengruppe_id`
, `organisation`.`interessengruppe2_id`
, `organisation`.`interessengruppe3_id`
, `organisation`.`branche_id`
, `organisation`.`homepage`
, `organisation`.`handelsregister_url`
, `organisation`.`twitter_name`
, `organisation`.`beschreibung` as organisation_beschreibung
, `organisation`.`adresse_strasse`
, `organisation`.`adresse_zusatz`
, `organisation`.`adresse_plz`
, `organisation`.`branche`
, `organisation`.`interessengruppe`
, `organisation`.`interessengruppe_branche`
, `organisation`.`interessengruppe2`
, `organisation`.`interessengruppe2_branche`
, `organisation`.`interessengruppe3`
, `organisation`.`interessengruppe3_branche`
, `organisation`.`land`
, `organisation`.`interessenraum`
, `organisation`.`organisation_jahr_id`
, `organisation`.`jahr`
, `organisation`.`umsatz`
, `organisation`.`gewinn`
, `organisation`.`kapital`
, `organisation`.`mitarbeiter_weltweit`
, `organisation`.`mitarbeiter_schweiz`
, `organisation`.`geschaeftsbericht_url`
-- , `organisation`.`quelle_url`
, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion, zutrittsberechtigung.parlamentarier_id, mandat.*
FROM v_zutrittsberechtigung zutrittsberechtigung
LEFT JOIN v_mandat mandat
  ON zutrittsberechtigung.id = mandat.zutrittsberechtigung_id
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
ORDER BY zutrittsberechtigung.anzeige_name;

-- Indirekte Mandate einer Zutrittsberechtigung (INNER JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mit_mandaten_indirekt` AS
SELECT 'direkt' as beziehung, zutrittsberechtigung.* FROM v_zutrittsberechtigung_mit_mandaten zutrittsberechtigung
UNION
SELECT 'indirekt' as beziehung, 
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`name`
-- , `organisation`.`id`
, `organisation`.`name_de`
, `organisation`.`name_fr`
, `organisation`.`name_it`
, `organisation`.`ort`
, `organisation`.`land_id`
, `organisation`.`interessenraum_id`
, `organisation`.`rechtsform`
, `organisation`.`typ`
, `organisation`.`vernehmlassung`
, `organisation`.`interessengruppe_id`
, `organisation`.`interessengruppe2_id`
, `organisation`.`interessengruppe3_id`
, `organisation`.`branche_id`
, `organisation`.`homepage`
, `organisation`.`handelsregister_url`
, `organisation`.`twitter_name`
, `organisation`.`beschreibung` as organisation_beschreibung
, `organisation`.`adresse_strasse`
, `organisation`.`adresse_zusatz`
, `organisation`.`adresse_plz`
, `organisation`.`branche`
, `organisation`.`interessengruppe`
, `organisation`.`interessengruppe_branche`
, `organisation`.`interessengruppe2`
, `organisation`.`interessengruppe2_branche`
, `organisation`.`interessengruppe3`
, `organisation`.`interessengruppe3_branche`
, `organisation`.`land`
, `organisation`.`interessenraum`
, `organisation`.`organisation_jahr_id`
, `organisation`.`jahr`
, `organisation`.`umsatz`
, `organisation`.`gewinn`
, `organisation`.`kapital`
, `organisation`.`mitarbeiter_weltweit`
, `organisation`.`mitarbeiter_schweiz`
, `organisation`.`geschaeftsbericht_url`
-- , `organisation`.`quelle_url`
, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion, zutrittsberechtigung.parlamentarier_id, mandat.*
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_mandat mandat
  ON zutrittsberechtigung.id = mandat.zutrittsberechtigung_id
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
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.ort
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
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, in welcher eine Organisation Mitglied ist.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglied_von` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.ort
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
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Muttergesellschaften.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_muttergesellschaft` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'tochtergesellschaft von'
ORDER BY organisation.anzeige_name;

-- Tochtergesellschaften
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_tochtergesellschaften` AS
SELECT organisation.anzeige_name as organisation_name, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'tochtergesellschaft von'
ORDER BY organisation.anzeige_name;

-- Parlamentarier, die eine Interessenbindung zu dieser Organisation haben.
-- Connector: interessenbindung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_parlamentarier` AS
SELECT 
`parlamentarier`.`anzeige_name` as parlamentarier_name
-- , `parlamentarier`.`anzeige_name`
, `parlamentarier`.`name`
, `parlamentarier`.`nachname`
, `parlamentarier`.`vorname`
, `parlamentarier`.`zweiter_vorname`
, `parlamentarier`.`rat_id`
, `parlamentarier`.`kanton_id`
, `parlamentarier`.`kommissionen`
, `parlamentarier`.`partei_id`
, `parlamentarier`.`parteifunktion`
, `parlamentarier`.`fraktion_id`
, `parlamentarier`.`fraktionsfunktion`
, `parlamentarier`.`im_rat_seit`
, `parlamentarier`.`im_rat_bis`
, `parlamentarier`.`ratswechsel`
, `parlamentarier`.`ratsunterbruch_von`
, `parlamentarier`.`ratsunterbruch_bis`
, `parlamentarier`.`beruf`
, `parlamentarier`.`beruf_interessengruppe_id`
, `parlamentarier`.`zivilstand`
, `parlamentarier`.`anzahl_kinder`
, `parlamentarier`.`militaerischer_grad_id`
, `parlamentarier`.`geschlecht`
, `parlamentarier`.`geburtstag`
, `parlamentarier`.`photo`
, `parlamentarier`.`photo_dateiname`
, `parlamentarier`.`photo_dateierweiterung`
, `parlamentarier`.`photo_dateiname_voll`
, `parlamentarier`.`photo_mime_type`
, `parlamentarier`.`kleinbild`
, `parlamentarier`.`sitzplatz`
, `parlamentarier`.`email`
, `parlamentarier`.`homepage`
, `parlamentarier`.`parlament_biografie_id`
, `parlamentarier`.`twitter_name`
, `parlamentarier`.`linkedin_profil_url`
, `parlamentarier`.`xing_profil_name`
, `parlamentarier`.`facebook_name`
, `parlamentarier`.`arbeitssprache`
, `parlamentarier`.`adresse_firma`
, `parlamentarier`.`adresse_strasse`
, `parlamentarier`.`adresse_zusatz`
, `parlamentarier`.`adresse_plz`
, `parlamentarier`.`adresse_ort`
, `parlamentarier`.`im_rat_seit_unix`
, `parlamentarier`.`im_rat_bis_unix`
-- , `parlamentarier`.`von`
-- , `parlamentarier`.`bis`
-- , `parlamentarier`.`von_unix`
-- , `parlamentarier`.`bis_unix`
-- , `parlamentarier`.`rat`
-- , `parlamentarier`.`ratstyp`
, `parlamentarier`.`kanton`
, `parlamentarier`.`vertretene_bevoelkerung`
, `parlamentarier`.`kommissionen_namen`
, `parlamentarier`.`kommissionen_abkuerzung`
, `parlamentarier`.`partei`
, `parlamentarier`.`fraktion`
, `parlamentarier`.`militaerischer_grad`
, interessenbindung.*
FROM v_interessenbindung interessenbindung
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
ORDER BY parlamentarier.anzeige_name;

-- Parlamentarier, die eine Interessenbindung zu dieser Organisation haben.
-- Connector: interessenbindung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_zutrittsberechtigung` AS
SELECT 
`zutrittsberechtigung`.`anzeige_name`
, `zutrittsberechtigung`.`anzeige_name` as zutrittsberechtigung_name
, `zutrittsberechtigung`.`name`
-- , `zutrittsberechtigung`.`id`
, `zutrittsberechtigung`.`parlamentarier_id`
, `zutrittsberechtigung`.`nachname`
, `zutrittsberechtigung`.`vorname`
, `zutrittsberechtigung`.`zweiter_vorname`
, `zutrittsberechtigung`.`funktion`
, `zutrittsberechtigung`.`beruf`
, `zutrittsberechtigung`.`beruf_interessengruppe_id`
, `zutrittsberechtigung`.`partei_id`
, `zutrittsberechtigung`.`geschlecht`
, `zutrittsberechtigung`.`email`
, `zutrittsberechtigung`.`homepage`
, `zutrittsberechtigung`.`twitter_name`
, `zutrittsberechtigung`.`linkedin_profil_url`
, `zutrittsberechtigung`.`xing_profil_name`
, `zutrittsberechtigung`.`facebook_name`
-- , `zutrittsberechtigung`.`von`
-- , `zutrittsberechtigung`.`bis`
-- , `zutrittsberechtigung`.`notizen`
-- , `zutrittsberechtigung`.`eingabe_abgeschlossen_visa`
-- , `zutrittsberechtigung`.`eingabe_abgeschlossen_datum`
-- , `zutrittsberechtigung`.`kontrolliert_visa`
-- , `zutrittsberechtigung`.`kontrolliert_datum`
-- , `zutrittsberechtigung`.`autorisierung_verschickt_visa`
-- , `zutrittsberechtigung`.`autorisierung_verschickt_datum`
-- , `zutrittsberechtigung`.`autorisiert_visa`
-- , `zutrittsberechtigung`.`autorisiert_datum`
-- , `zutrittsberechtigung`.`freigabe_visa`
-- , `zutrittsberechtigung`.`freigabe_datum`
-- , `zutrittsberechtigung`.`ALT_lobbyorganisation_id`
-- , `zutrittsberechtigung`.`created_visa`
-- , `zutrittsberechtigung`.`created_date`
-- , `zutrittsberechtigung`.`updated_visa`
-- , `zutrittsberechtigung`.`updated_date`
, `zutrittsberechtigung`.`partei`
, `zutrittsberechtigung`.`parlamentarier_name`
-- , `zutrittsberechtigung`.`parlamentarier_freigabe_datum`
-- , `zutrittsberechtigung`.`parlamentarier_freigabe_datum_unix`
-- , `zutrittsberechtigung`.`bis_unix`
-- , `zutrittsberechtigung`.`von_unix`
-- , `zutrittsberechtigung`.`created_date_unix`
-- , `zutrittsberechtigung`.`updated_date_unix`
-- , `zutrittsberechtigung`.`eingabe_abgeschlossen_datum_unix`
-- , `zutrittsberechtigung`.`kontrolliert_datum_unix`
-- , `zutrittsberechtigung`.`freigabe_datum_unix`
, mandat.*
FROM v_mandat mandat
INNER JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id
ORDER BY zutrittsberechtigung.anzeige_name;

-- Parlamentarier, die eine indirekte Interessenbindung zu dieser Organisation haben.
-- Connector: connector_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_indirekt` AS
SELECT 'direkt' as beziehung, organisation_parlamentarier.*, organisation_parlamentarier.organisation_id as connector_organisation_id FROM v_organisation_parlamentarier organisation_parlamentarier
UNION
SELECT 'indirekt' as beziehung, 
`parlamentarier`.`anzeige_name` as parlamentarier_name
, `parlamentarier`.`name`
, `parlamentarier`.`nachname`
, `parlamentarier`.`vorname`
, `parlamentarier`.`zweiter_vorname`
, `parlamentarier`.`rat_id`
, `parlamentarier`.`kanton_id`
, `parlamentarier`.`kommissionen`
, `parlamentarier`.`partei_id`
, `parlamentarier`.`parteifunktion`
, `parlamentarier`.`fraktion_id`
, `parlamentarier`.`fraktionsfunktion`
, `parlamentarier`.`im_rat_seit`
, `parlamentarier`.`im_rat_bis`
, `parlamentarier`.`ratswechsel`
, `parlamentarier`.`ratsunterbruch_von`
, `parlamentarier`.`ratsunterbruch_bis`
, `parlamentarier`.`beruf`
, `parlamentarier`.`beruf_interessengruppe_id`
, `parlamentarier`.`zivilstand`
, `parlamentarier`.`anzahl_kinder`
, `parlamentarier`.`militaerischer_grad_id`
, `parlamentarier`.`geschlecht`
, `parlamentarier`.`geburtstag`
, `parlamentarier`.`photo`
, `parlamentarier`.`photo_dateiname`
, `parlamentarier`.`photo_dateierweiterung`
, `parlamentarier`.`photo_dateiname_voll`
, `parlamentarier`.`photo_mime_type`
, `parlamentarier`.`kleinbild`
, `parlamentarier`.`sitzplatz`
, `parlamentarier`.`email`
, `parlamentarier`.`homepage`
, `parlamentarier`.`parlament_biografie_id`
, `parlamentarier`.`twitter_name`
, `parlamentarier`.`linkedin_profil_url`
, `parlamentarier`.`xing_profil_name`
, `parlamentarier`.`facebook_name`
, `parlamentarier`.`arbeitssprache`
, `parlamentarier`.`adresse_firma`
, `parlamentarier`.`adresse_strasse`
, `parlamentarier`.`adresse_zusatz`
, `parlamentarier`.`adresse_plz`
, `parlamentarier`.`adresse_ort`
, `parlamentarier`.`im_rat_seit_unix`
, `parlamentarier`.`im_rat_bis_unix`
-- , `parlamentarier`.`von`
-- , `parlamentarier`.`bis`
-- , `parlamentarier`.`von_unix`
-- , `parlamentarier`.`bis_unix`
-- , `parlamentarier`.`rat`
-- , `parlamentarier`.`ratstyp`
, `parlamentarier`.`kanton`
, `parlamentarier`.`vertretene_bevoelkerung`
, `parlamentarier`.`kommissionen_namen`
, `parlamentarier`.`kommissionen_abkuerzung`
, `parlamentarier`.`partei`
, `parlamentarier`.`fraktion`
, `parlamentarier`.`militaerischer_grad`
, interessenbindung.*, organisation_beziehung.ziel_organisation_id as connector_organisation_id
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_interessenbindung interessenbindung
  ON organisation_beziehung.organisation_id = interessenbindung.organisation_id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, parlamentarier_name;

-- Parlamentarier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben.
-- Connector: organisation_id oder parlamentarier_id
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_beide` AS
SELECT 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as zutrittsberechtigung_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, interessenbindung.bis,  interessenbindung.organisation_id, interessenbindung.freigabe_datum
FROM v_interessenbindung interessenbindung
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
UNION
SELECT 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.id as zutrittsberechtigung_id, zutrittsberechtigung.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von, mandat.bis, mandat.organisation_id, mandat.freigabe_datum
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id;

-- Parlamentarier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben.
-- Connector: connector_organisation_id oder parlamentarier_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_beide_indirekt` AS
SELECT 'direkt' as beziehung, organisation_parlamentarier.verbindung, organisation_parlamentarier.parlamentarier_id, organisation_parlamentarier.parlamentarier_name, organisation_parlamentarier.ratstyp, organisation_parlamentarier.kanton, organisation_parlamentarier.partei_id, organisation_parlamentarier.partei, organisation_parlamentarier.kommissionen, organisation_parlamentarier.parlament_biografie_id, organisation_parlamentarier.zutrittsberechtigung_id, organisation_parlamentarier.zutrittsberechtigter, organisation_parlamentarier.art, organisation_parlamentarier.von, organisation_parlamentarier.bis, NULL as zwischenorganisation_id, organisation_parlamentarier.organisation_id as connector_organisation_id, organisation_parlamentarier.freigabe_datum
FROM v_organisation_parlamentarier_beide organisation_parlamentarier
UNION
SELECT 'indirekt' as beziehung, 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as zutrittsberechtigung_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, interessenbindung.bis, organisation_beziehung.organisation_id as zwischenorganisation_id, organisation_beziehung.ziel_organisation_id as connector_organisation_id, organisation_beziehung.freigabe_datum
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_interessenbindung interessenbindung
  ON organisation_beziehung.organisation_id = interessenbindung.organisation_id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
UNION
SELECT 'indirekt' as beziehung, 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.id as zutrittsberechtigung_id, zutrittsberechtigung.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von, mandat.bis, organisation_beziehung.organisation_id as zwischenorganisation_id, organisation_beziehung.ziel_organisation_id as connector_organisation_id, organisation_beziehung.freigabe_datum
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_mandat mandat
  ON organisation_beziehung.organisation_id = mandat.organisation_id
INNER JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer';

-- Authorisieurngsemail Interessenbindung für Parlamentarier
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_authorisierungs_email` AS
SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, organisation.anzeige_name as organisation_name, IFNULL(organisation.rechtsform,'') rechtsform, IFNULL(organisation.ort,'') ort, interessenbindung.art, interessenbindung.beschreibung
FROM v_interessenbindung interessenbindung
INNER JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
ORDER BY organisation.anzeige_name;

-- Authorisieurngsemail Interessenbindung für Zutrittsberechtigte
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_authorisierungs_email` AS
SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, zutrittsberechtigung.name zutrittsberechtigung_name, zutrittsberechtigung.funktion
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
GROUP BY parlamentarier.id;

-- Authorisieurngsemail Interessenbindung für Parlamentarier
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

-- Authorisieurngsemail Interessenbindung für Parlamentarier
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
    CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', IFNULL(interessenbindung.beschreibung, ''))
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p>Ihre <b>Gäste</b>:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion)
    ORDER BY zutrittsberechtigung.name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p><b>Mandate</b> der Gäste:</p>',
  '<ul>',
  GROUP_CONCAT(DISTINCT
    CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion,
    IF (organisation2.id IS NOT NULL,
      CONCAT(', ',
        organisation2.anzeige_name
        , IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', organisation2.rechtsform)), IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)), ', '
        , IFNULL(mandat.art, ''), ', ', IFNULL(mandat.beschreibung, '')
      ),
      '')
    )
    ORDER BY zutrittsberechtigung.name, organisation2.anzeige_name
    SEPARATOR ' '
  ),
  '</ul>',
  '<p>Freundliche Grüsse<br></p>'
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
    CONCAT('* ', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', IFNULL(interessenbindung.beschreibung, ''), '\r\n')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '\r\nIhre Gäste:\r\n',
  GROUP_CONCAT(DISTINCT
    CONCAT('* ', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion, '\r\n')
    ORDER BY organisation.anzeige_name
    SEPARATOR ' '
  ),
  '\r\nMit freundlichen Grüssen,\r\n'
)) email_text_for_url
FROM v_parlamentarier parlamentarier
LEFT JOIN v_interessenbindung interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id AND interessenbindung.bis IS NULL
LEFT JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
LEFT JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND zutrittsberechtigung.bis IS NULL
LEFT JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id AND mandat.bis IS NULL
LEFT JOIN v_organisation organisation2
  ON mandat.organisation_id = organisation2.id
WHERE
  parlamentarier.im_rat_bis IS NULL
GROUP BY parlamentarier.id;

