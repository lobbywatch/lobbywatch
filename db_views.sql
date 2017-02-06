-- Lobbywatch.ch Views

-- Views can be very slow in MySQL, see
-- http://www.mysqlperformanceblog.com/2007/08/12/mysql-view-as-performance-troublemaker/

-- As a workaround materialized views are created (or better simulated as they do not exist natively in MySQL).
-- See refreshMaterializedViews() at the end of this file.
-- The PROCEDURE refreshMaterializedViews() must be called regularly.

-- Docu on indexes: http://www.percona.com/files/presentations/percona-live/london-2011/PLUK2011-practical-mysql-indexing-guidelines.pdf

SET NAMES 'utf8' COLLATE 'utf8_general_ci';

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
CREATE OR REPLACE VIEW `v_last_updated_interessenbindung_jahr` AS
  (SELECT
  'interessenbindung_jahr' table_name,
  'Interessenbindungsvergütung' name,
  (select count(*) from `interessenbindung_jahr`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `interessenbindung_jahr` t
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
CREATE OR REPLACE VIEW `v_last_updated_mandat_jahr` AS
  (SELECT
  'mandat_jahr' table_name,
  'Mandatsvergütung' name,
  (select count(*) from `mandat_jahr`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `mandat_jahr` t
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
CREATE OR REPLACE VIEW `v_last_updated_zutrittsberechtigung` AS
  (SELECT
  'zutrittsberechtigung' table_name,
  'Zutrittsberechtigung' name,
  (select count(*) from `zutrittsberechtigung`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `zutrittsberechtigung` t
  ORDER BY t.`updated_date` DESC
  LIMIT 1
  );
CREATE OR REPLACE VIEW `v_last_updated_person` AS
  (SELECT
  'person' table_name,
  'Person' name,
  (select count(*) from `person`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `person` t
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
CREATE OR REPLACE VIEW `v_last_updated_person_anhang` AS
  (SELECT
  'person_anhang' table_name,
  'Personenanhang' name,
  (select count(*) from `person_anhang`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `person_anhang` t
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
CREATE OR REPLACE VIEW `v_last_updated_tables_unordered` AS
SELECT * FROM v_last_updated_branche
UNION
SELECT * FROM v_last_updated_interessenbindung
UNION
SELECT * FROM v_last_updated_interessenbindung_jahr
UNION
SELECT * FROM v_last_updated_interessengruppe
UNION
SELECT * FROM v_last_updated_in_kommission
UNION
SELECT * FROM v_last_updated_kommission
UNION
SELECT * FROM v_last_updated_mandat
UNION
SELECT * FROM v_last_updated_mandat_jahr
UNION
SELECT * FROM v_last_updated_organisation
UNION
SELECT * FROM v_last_updated_organisation_beziehung
UNION
SELECT * FROM v_last_updated_organisation_jahr
UNION
SELECT * FROM v_last_updated_parlamentarier
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
SELECT * FROM v_last_updated_zutrittsberechtigung
UNION
SELECT * FROM v_last_updated_person
UNION
SELECT * FROM v_last_updated_parlamentarier_anhang
UNION
SELECT * FROM v_last_updated_organisation_anhang
UNION
SELECT * FROM v_last_updated_person_anhang
UNION
SELECT * FROM v_last_updated_settings
UNION
SELECT * FROM v_last_updated_settings_category;

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
SELECT country.name_de as anzeige_name, country.name_de as anzeige_name_de, country.name_fr as anzeige_name_fr,
CONCAT_WS(' / ', country.name_de, country.name_fr) as anzeige_name_mixed,
country.*,
UNIX_TIMESTAMP(created_date) as created_date_unix, UNIX_TIMESTAMP(updated_date) as updated_date_unix
FROM `country`;

CREATE OR REPLACE VIEW `v_rat` AS
SELECT rat.name_de as anzeige_name, rat.name_de as anzeige_name_de, rat.name_de as anzeige_name_fr,
CONCAT_WS(' / ', rat.name_de, rat.name_de) as anzeige_name_mixed,
CONCAT_WS(' / ', rat.abkuerzung, rat.abkuerzung_fr) as abkuerzung_mixed,
rat.*,
UNIX_TIMESTAMP(rat.created_date) as created_date_unix, UNIX_TIMESTAMP(rat.updated_date) as updated_date_unix, UNIX_TIMESTAMP(rat.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(rat.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(rat.freigabe_datum) as freigabe_datum_unix
FROM `rat`
ORDER BY `gewicht` ASC;

CREATE OR REPLACE VIEW `v_kanton_jahr` AS
SELECT kanton_jahr.*,
UNIX_TIMESTAMP(kanton_jahr.created_date) as created_date_unix, UNIX_TIMESTAMP(kanton_jahr.updated_date) as updated_date_unix, UNIX_TIMESTAMP(kanton_jahr.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(kanton_jahr.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(kanton_jahr.freigabe_datum) as freigabe_datum_unix
FROM `kanton_jahr`;

CREATE OR REPLACE VIEW `v_kanton_jahr_last` AS
SELECT MAX(kanton_jahr.jahr) max_jahr, kanton_jahr.*
FROM `kanton_jahr`
GROUP BY kanton_jahr.kanton_id;

CREATE OR REPLACE VIEW `v_kanton_2012` AS
SELECT kanton.name_de as anzeige_name, kanton.name_de as anzeige_name_de, kanton.name_fr as anzeige_name_fr, kanton.*, kanton_jahr.`id` as kanton_jahr_id, kanton_jahr.`jahr`, kanton_jahr.einwohner, kanton_jahr.auslaenderanteil, kanton_jahr.bevoelkerungsdichte, kanton_jahr.anzahl_gemeinden, kanton_jahr.anzahl_nationalraete
FROM `kanton`
LEFT JOIN `v_kanton_jahr` kanton_jahr
ON kanton_jahr.kanton_id = kanton.id AND kanton_jahr.jahr=2012;

CREATE OR REPLACE VIEW `v_kanton_simple` AS
SELECT kanton.name_de as anzeige_name, kanton.name_de as anzeige_name_de, kanton.name_fr as anzeige_name_fr,
CONCAT_WS(' / ', kanton.name_de, kanton.name_fr) as anzeige_name_mixed,
kanton.*
FROM `kanton`;

CREATE OR REPLACE VIEW `v_kanton` AS
SELECT kanton.*, kanton_jahr.`id` as kanton_jahr_id, kanton_jahr.`jahr`, kanton_jahr.einwohner, kanton_jahr.auslaenderanteil, kanton_jahr.bevoelkerungsdichte, kanton_jahr.anzahl_gemeinden, kanton_jahr.anzahl_nationalraete
FROM `v_kanton_simple` kanton
LEFT JOIN `v_kanton_jahr_last` kanton_jahr
ON kanton_jahr.kanton_id = kanton.id;

CREATE OR REPLACE VIEW `v_interessenraum` AS
SELECT interessenraum.name as anzeige_name, interessenraum.name as anzeige_name_de, interessenraum.name_fr as anzeige_name_fr,
CONCAT_WS(' / ', interessenraum.name, interessenraum.name_fr) as anzeige_name_mixed,
interessenraum.*,
`interessenraum`.name as name_de, `interessenraum`.beschreibung as beschreibung_de,
UNIX_TIMESTAMP(interessenraum.created_date) as created_date_unix, UNIX_TIMESTAMP(interessenraum.updated_date) as updated_date_unix, UNIX_TIMESTAMP(interessenraum.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(interessenraum.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(interessenraum.freigabe_datum) as freigabe_datum_unix
FROM `interessenraum` interessenraum
ORDER BY interessenraum.`reihenfolge` ASC;

CREATE OR REPLACE VIEW `v_kommission` AS
SELECT CONCAT(kommission.name, ' (', kommission.abkuerzung, ')') AS anzeige_name, CONCAT(kommission.name, ' (', kommission.abkuerzung, ')') AS anzeige_name_de, CONCAT(kommission.name_fr, ' (', kommission.abkuerzung_fr, ')') AS anzeige_name_fr,
CONCAT_WS(' / ', CONCAT(kommission.name, ' (', kommission.abkuerzung, ')'), CONCAT(kommission.name_fr, ' (', kommission.abkuerzung_fr, ')')) AS anzeige_name_mixed,
kommission.*,
`kommission`.name as name_de, `kommission`.abkuerzung as abkuerzung_de, `kommission`.beschreibung as beschreibung_de, `kommission`.sachbereiche as sachbereiche_de,
UNIX_TIMESTAMP(kommission.created_date) as created_date_unix, UNIX_TIMESTAMP(kommission.updated_date) as updated_date_unix, UNIX_TIMESTAMP(kommission.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(kommission.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(kommission.freigabe_datum) as freigabe_datum_unix
FROM `kommission`;

CREATE OR REPLACE VIEW `v_partei` AS
SELECT CONCAT(partei.name, ' (', partei.abkuerzung, ')') AS anzeige_name, CONCAT(partei.name, ' (', partei.abkuerzung, ')') AS anzeige_name_de, CONCAT(partei.name_fr, ' (', partei.abkuerzung_fr, ')') AS anzeige_name_fr,
CONCAT_WS(' / ', CONCAT(partei.name, ' (', partei.abkuerzung, ')'), CONCAT(partei.name_fr, ' (', partei.abkuerzung_fr, ')')) AS anzeige_name_mixed,
CONCAT_WS(' / ', `partei`.abkuerzung, `partei`.abkuerzung_fr) as abkuerzung_mixed,
partei.*,
`partei`.name as name_de, `partei`.abkuerzung as abkuerzung_de, `partei`.beschreibung as beschreibung_de, `partei`.homepage as homepage_de, `partei`.twitter_name as twitter_name_de, `partei`.email as email_de,
UNIX_TIMESTAMP(partei.created_date) as created_date_unix, UNIX_TIMESTAMP(partei.updated_date) as updated_date_unix, UNIX_TIMESTAMP(partei.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(partei.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(partei.freigabe_datum) as freigabe_datum_unix
FROM `partei`;

CREATE OR REPLACE VIEW `v_fraktion` AS
SELECT CONCAT_WS(', ', fraktion.abkuerzung, fraktion.name) AS anzeige_name, CONCAT_WS(', ', fraktion.abkuerzung, fraktion.name) AS anzeige_name_de, CONCAT_WS(', ', fraktion.abkuerzung, fraktion.name_fr) AS anzeige_name_fr, CONCAT_WS(' / ', CONCAT_WS(', ', fraktion.abkuerzung, fraktion.name), CONCAT_WS(', ', fraktion.abkuerzung, fraktion.name_fr)) AS anzeige_name_mixed,
fraktion.*,
`fraktion`.name as name_de, `fraktion`.beschreibung as beschreibung_de,
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

CREATE OR REPLACE VIEW `v_interessenbindung_jahr` AS
SELECT interessenbindung_jahr.*,
UNIX_TIMESTAMP(interessenbindung_jahr.created_date) as created_date_unix, UNIX_TIMESTAMP(interessenbindung_jahr.updated_date) as updated_date_unix, UNIX_TIMESTAMP(interessenbindung_jahr.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(interessenbindung_jahr.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(interessenbindung_jahr.freigabe_datum) as freigabe_datum_unix
FROM `interessenbindung_jahr`;

CREATE OR REPLACE VIEW `v_mandat_jahr` AS
SELECT mandat_jahr.*,
UNIX_TIMESTAMP(mandat_jahr.created_date) as created_date_unix, UNIX_TIMESTAMP(mandat_jahr.updated_date) as updated_date_unix, UNIX_TIMESTAMP(mandat_jahr.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(mandat_jahr.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(mandat_jahr.freigabe_datum) as freigabe_datum_unix
FROM `mandat_jahr`;

CREATE OR REPLACE VIEW `v_branche_simple` AS
SELECT CONCAT(branche.name) AS anzeige_name, CONCAT(branche.name) AS anzeige_name_de, CONCAT(branche.name_fr) AS anzeige_name_fr,
CONCAT_WS(' / ', branche.name, branche.name_fr) AS anzeige_name_mixed,
branche.*,
`branche`.name as name_de, `branche`.beschreibung as beschreibung_de, `branche`.angaben as angaben_de,
UNIX_TIMESTAMP(branche.created_date) as created_date_unix, UNIX_TIMESTAMP(branche.updated_date) as updated_date_unix, UNIX_TIMESTAMP(branche.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(branche.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(branche.freigabe_datum) as freigabe_datum_unix
FROM `branche`
;

CREATE OR REPLACE VIEW `v_branche` AS
SELECT
branche.*,
kommission.anzeige_name as kommission1,
kommission.anzeige_name_de as kommission1_de,
kommission.anzeige_name_fr as kommission1_fr,
kommission2.anzeige_name as kommission2,
kommission2.anzeige_name_de as kommission2_de,
kommission2.anzeige_name_fr as kommission2_fr
FROM `v_branche_simple` branche
LEFT JOIN `v_kommission` kommission
ON kommission.id = branche.kommission_id
LEFT JOIN `v_kommission` kommission2
ON kommission2.id = branche.kommission2_id
;

CREATE OR REPLACE VIEW `v_branche_name_with_null` AS
SELECT branche.id, CONCAT(branche.name) AS anzeige_name, CONCAT(branche.name) AS anzeige_name_de, CONCAT(branche.name_fr) AS anzeige_name_fr,
CONCAT_WS(' / ', branche.name, branche.name_fr) AS anzeige_name_mixed
FROM `branche`
ORDER BY branche.name
-- UNION
-- SELECT NULL as ID, 'NULL' as anzeige_name, 'NULL' as anzeige_name_de, 'NULL' as anzeige_name_fr, 'NULL'  AS anzeige_name_mixed
;

CREATE OR REPLACE VIEW `v_interessengruppe_simple` AS
SELECT CONCAT(interessengruppe.name) AS anzeige_name, CONCAT(interessengruppe.name) AS anzeige_name_de, CONCAT(interessengruppe.name_fr) AS anzeige_name_fr,
CONCAT_WS(' / ', interessengruppe.name, interessengruppe.name_fr) AS anzeige_name_mixed,
interessengruppe.*,
`interessengruppe`.name as name_de, `interessengruppe`.beschreibung as beschreibung_de, `interessengruppe`.alias_namen as alias_namen_de,
UNIX_TIMESTAMP(interessengruppe.created_date) as created_date_unix, UNIX_TIMESTAMP(interessengruppe.updated_date) as updated_date_unix, UNIX_TIMESTAMP(interessengruppe.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(interessengruppe.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(interessengruppe.freigabe_datum) as freigabe_datum_unix
FROM `interessengruppe`
;

CREATE OR REPLACE VIEW `v_interessengruppe` AS
SELECT
interessengruppe.*,
branche.anzeige_name as branche,
branche.anzeige_name_de as branche_de,
branche.anzeige_name_fr as branche_fr,
branche.kommission_id as kommission_id,
branche.kommission2_id as kommission2_id,
branche.kommission1 as kommission1,
branche.kommission1_de as kommission1_de,
branche.kommission1_fr as kommission1_fr,
branche.kommission2 as kommission2,
branche.kommission2_de as kommission2_de,
branche.kommission2_fr as kommission2_fr
FROM `v_interessengruppe_simple` interessengruppe
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

CREATE OR REPLACE VIEW `v_in_kommission_simple` AS
SELECT in_kommission.*,
UNIX_TIMESTAMP(bis) as bis_unix, UNIX_TIMESTAMP(von) as von_unix,
UNIX_TIMESTAMP(in_kommission.created_date) as created_date_unix, UNIX_TIMESTAMP(in_kommission.updated_date) as updated_date_unix, UNIX_TIMESTAMP(in_kommission.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(in_kommission.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(in_kommission.freigabe_datum) as freigabe_datum_unix
FROM `in_kommission`
;

CREATE OR REPLACE VIEW `v_in_kommission` AS
SELECT in_kommission.*,
rat.abkuerzung as rat, rat.abkuerzung as rat_de, rat.abkuerzung_fr as rat_fr,
CONCAT_WS(' / ', rat.abkuerzung, rat.abkuerzung_fr) as rat_mixed,
rat.abkuerzung as ratstyp,
kommission.abkuerzung as kommission_abkuerzung, kommission.name as kommission_name,
kommission.abkuerzung as kommission_abkuerzung_de, kommission.name as kommission_name_de,
kommission.abkuerzung_fr as kommission_abkuerzung_fr, kommission.name_fr as kommission_name_fr,
CONCAT_WS(' / ', kommission.abkuerzung, kommission.abkuerzung_fr) as kommission_abkuerzung_mixed, CONCAT_WS(' / ', kommission.name, kommission.name_fr) as kommission_name_mixed,
kommission.art as kommission_art, kommission.typ as kommission_typ, kommission.beschreibung as kommission_beschreibung, kommission.sachbereiche as kommission_sachbereiche, kommission.mutter_kommission_id as kommission_mutter_kommission_id, kommission.parlament_url as kommission_parlament_url
FROM `v_in_kommission_simple` in_kommission
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

CREATE OR REPLACE VIEW `v_person_anhang` AS
SELECT person_anhang.person_id as person_id2, person_anhang.*
FROM `person_anhang`;

CREATE OR REPLACE VIEW `v_user` AS
SELECT IFNULL(CONCAT_WS(' ', u.vorname, u.nachname ), u.name) as anzeige_name, u.name as username, u.*,
UNIX_TIMESTAMP(u.created_date) as created_date_unix, UNIX_TIMESTAMP(u.updated_date) as updated_date_unix
FROM `user` u;

CREATE OR REPLACE VIEW `v_user_permission` AS
SELECT t.*
FROM `user_permission` t;

CREATE OR REPLACE VIEW `v_mil_grad` AS
SELECT mil_grad.*,
`mil_grad`.name as name_de, `mil_grad`.abkuerzung as abkuerzung_de,
UNIX_TIMESTAMP(mil_grad.created_date) as created_date_unix, UNIX_TIMESTAMP(mil_grad.updated_date) as updated_date_unix
FROM `mil_grad`
ORDER BY `ranghoehe` ASC;

CREATE OR REPLACE VIEW `v_organisation_simple` AS
SELECT CONCAT_WS('; ', organisation.name_de, organisation.name_fr, organisation.name_it) AS anzeige_name,
CONCAT_WS('; ', organisation.name_de, organisation.name_fr, organisation.name_it) AS anzeige_mixed,
CONCAT_WS('; ', organisation.name_de, organisation.name_fr) AS anzeige_bimixed,
CONCAT_WS('; ', organisation.name_de, organisation.abkuerzung_de, organisation.name_fr, organisation.abkuerzung_fr, uid, LEFT(organisation.alias_namen_de, 75), LEFT(organisation.alias_namen_fr, 75)) AS searchable_name,
organisation.name_de AS anzeige_name_de,
organisation.name_fr AS anzeige_name_fr,
CONCAT_WS('; ', organisation.name_de , organisation.name_fr, organisation.name_it) AS name,
organisation.*,
UNIX_TIMESTAMP(organisation.created_date) as created_date_unix, UNIX_TIMESTAMP(organisation.updated_date) as updated_date_unix, UNIX_TIMESTAMP(organisation.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(organisation.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(organisation.freigabe_datum) as freigabe_datum_unix
FROM `organisation`
;

CREATE OR REPLACE VIEW `v_organisation_medium_raw` AS
SELECT
organisation.*,
branche.anzeige_name as branche,
branche.anzeige_name_de as branche_de,
branche.anzeige_name_de as branche_fr,
interessengruppe1.anzeige_name as interessengruppe,
interessengruppe1.anzeige_name_de as interessengruppe_de,
interessengruppe1.anzeige_name_fr as interessengruppe_fr,
interessengruppe1.branche as interessengruppe_branche,
interessengruppe1.branche_de as interessengruppe_branche_de,
interessengruppe1.branche_fr as interessengruppe_branche_fr,
interessengruppe1.branche_id as interessengruppe_branche_id,
interessengruppe2.anzeige_name as interessengruppe2,
interessengruppe2.anzeige_name_de as interessengruppe2_de,
interessengruppe2.anzeige_name_fr as interessengruppe2_fr,
interessengruppe2.branche as interessengruppe2_branche,
interessengruppe2.branche_de as interessengruppe2_branche_de,
interessengruppe2.branche_fr as interessengruppe2_branche_fr,
interessengruppe2.branche_id as interessengruppe2_branche_id,
interessengruppe3.anzeige_name as interessengruppe3,
interessengruppe3.anzeige_name_de as interessengruppe3_de,
interessengruppe3.anzeige_name_fr as interessengruppe3_fr,
interessengruppe3.branche as interessengruppe3_branche,
interessengruppe3.branche_de as interessengruppe3_branche_de,
interessengruppe3.branche_fr as interessengruppe3_branche_fr,
interessengruppe3.branche_id as interessengruppe3_branche_id,
NOW() as refreshed_date
FROM `v_organisation_simple` organisation
LEFT JOIN `v_branche` branche
ON branche.id = organisation.branche_id
LEFT JOIN `v_interessengruppe` interessengruppe1
ON interessengruppe1.id = organisation.interessengruppe_id
LEFT JOIN `v_interessengruppe` interessengruppe2
ON interessengruppe2.id = organisation.interessengruppe2_id
LEFT JOIN `v_interessengruppe` interessengruppe3
ON interessengruppe3.id = organisation.interessengruppe3_id
;

--	DROP TABLE IF EXISTS `mv_organisation_medium`;
--	CREATE TABLE IF NOT EXISTS `mv_organisation_medium`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_organisation_medium'
--	AS SELECT * FROM `v_organisation_medium_raw`;
--	ALTER TABLE `mv_organisation_medium`
--	ADD PRIMARY KEY (`id`),
--	ADD KEY `idx_id_freigabe` (`id`, `freigabe_datum`),
--	ADD KEY `idx_name_de` (`name_de`, `freigabe_datum`),
--	ADD KEY `idx_name_fr` (`name_fr`, `freigabe_datum`),
--	ADD KEY `idx_name_it` (`name_it`, `freigabe_datum`),
--	ADD KEY `idx_anzeige_name` (`anzeige_name`, `freigabe_datum`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';
--
--	DROP TABLE IF EXISTS `mv_organisation_medium_myisam`;
--	CREATE TABLE IF NOT EXISTS `mv_organisation_medium_myisam`
--	ENGINE = MyISAM
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_organisation_medium'
--	AS SELECT * FROM `v_organisation_medium_raw`;
--	ALTER TABLE `mv_organisation_medium_myisam`
--	ADD PRIMARY KEY (`id`),
--	ADD UNIQUE KEY `idx_name_de` (`name_de`, `freigabe_datum`),
--	ADD KEY `idx_name_fr` (`name_fr`, `freigabe_datum`),
--	ADD KEY `idx_name_it` (`name_it`, `freigabe_datum`),
--	ADD KEY `idx_anzeige_name` (`anzeige_name`, `freigabe_datum`),
--	ADD KEY `idx_freigabe` (`freigabe_datum`),
--	ADD FULLTEXT(`anzeige_name`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

--	CREATE OR REPLACE VIEW `v_organisation_medium` AS
--	SELECT * FROM `mv_organisation_medium_raw`;

CREATE OR REPLACE VIEW `v_parlamentarier_simple` AS
SELECT
CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) AS anzeige_name,
CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) AS anzeige_name_de,
CONCAT(parlamentarier.nachname, ', ', parlamentarier.vorname) AS anzeige_name_fr,
CONCAT_WS(' ', parlamentarier.vorname, parlamentarier.zweiter_vorname, parlamentarier.nachname) AS name,
CONCAT_WS(' ', parlamentarier.vorname, parlamentarier.zweiter_vorname, parlamentarier.nachname) AS name_de,
CONCAT_WS(' ', parlamentarier.vorname, parlamentarier.zweiter_vorname, parlamentarier.nachname) AS name_fr,
parlamentarier.*,
parlamentarier.beruf as beruf_de,
parlamentarier.im_rat_seit as von, parlamentarier.im_rat_bis as bis,
UNIX_TIMESTAMP(geburtstag) as geburtstag_unix,
UNIX_TIMESTAMP(im_rat_seit) as im_rat_seit_unix, UNIX_TIMESTAMP(im_rat_bis) as im_rat_bis_unix,
UNIX_TIMESTAMP(parlamentarier.created_date) as created_date_unix, UNIX_TIMESTAMP(parlamentarier.updated_date) as updated_date_unix, UNIX_TIMESTAMP(parlamentarier.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(parlamentarier.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(parlamentarier.freigabe_datum) as freigabe_datum_unix,
UNIX_TIMESTAMP(im_rat_seit) as von_unix, UNIX_TIMESTAMP(im_rat_bis) as bis_unix
FROM `parlamentarier` parlamentarier;

-- TODO delete person.von and bis, then person.* should work
CREATE OR REPLACE VIEW `v_person_simple` AS
SELECT
CONCAT(person.nachname, ', ', person.vorname) AS anzeige_name,
CONCAT(person.nachname, ', ', person.vorname) AS anzeige_name_de,
CONCAT(person.nachname, ', ', person.vorname) AS anzeige_name_fr,
CONCAT(person.vorname, ' ', person.nachname) AS name,
CONCAT(person.vorname, ' ', person.nachname) AS name_de,
CONCAT(person.vorname, ' ', person.nachname) AS name_fr,
-- person.*,
person.id,
-- person.`parlamentarier_id` ,
person.`nachname` ,
person.`vorname` ,
person.`zweiter_vorname` ,
person.`beschreibung_de` ,
person.`beschreibung_fr` ,
person.`parlamentarier_kommissionen` ,
person.`beruf` ,
person.`beruf_fr` ,
person.`beruf_interessengruppe_id` ,
person.`partei_id` ,
person.`geschlecht` ,
person.`arbeitssprache` ,
person.`email` ,
person.`homepage` ,
person.`twitter_name` ,
person.`linkedin_profil_url` ,
person.`xing_profil_name` ,
person.`facebook_name` ,
person.`telephon_1` ,
person.`telephon_2` ,
person.`erfasst` ,
-- person.`von` ,
-- person.`bis` ,
person.`notizen` ,
person.`eingabe_abgeschlossen_visa` ,
person.`eingabe_abgeschlossen_datum` ,
person.`kontrolliert_visa` ,
person.`kontrolliert_datum` ,
person.`autorisierung_verschickt_visa` ,
person.`autorisierung_verschickt_datum` ,
person.`autorisiert_visa` ,
person.`autorisiert_datum` ,
person.`freigabe_visa` ,
person.`freigabe_datum` ,
person.`created_visa` ,
person.`created_date` ,
person.`updated_visa` ,
person.`updated_date` ,
-- UNIX_TIMESTAMP(person.bis) as bis_unix, UNIX_TIMESTAMP(person.von) as von_unix,
UNIX_TIMESTAMP(person.created_date) as created_date_unix, UNIX_TIMESTAMP(person.updated_date) as updated_date_unix, UNIX_TIMESTAMP(person.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(person.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(person.freigabe_datum) as freigabe_datum_unix
FROM `person`
;

CREATE OR REPLACE VIEW `v_interessenbindung_medium_raw` AS
SELECT CONCAT(interessenbindung.id, ', ', parlamentarier.anzeige_name, ', ', organisation.anzeige_name, ', ', interessenbindung.art) anzeige_name,
interessenbindung.*,
IF(organisation.vernehmlassung IN ('immer', 'punktuell')
  AND interessenbindung.art IN ('geschaeftsfuehrend','vorstand')
  AND EXISTS (
    SELECT in_kommission.kommission_id
    FROM in_kommission in_kommission
    LEFT JOIN branche branche
    ON (in_kommission.kommission_id = branche.kommission_id OR in_kommission.kommission_id = branche.kommission2_id)
    WHERE (in_kommission.bis >= NOW() OR in_kommission.bis IS NULL)
    AND in_kommission.parlamentarier_id = parlamentarier.id
    AND branche.id IN (organisation.branche_id, organisation.interessengruppe_branche_id, organisation.interessengruppe2_branche_id, organisation.interessengruppe3_branche_id)), 'hoch',
	IF(organisation.vernehmlassung IN ('immer', 'punktuell')
	  AND interessenbindung.art IN ('geschaeftsfuehrend','vorstand','taetig','beirat','finanziell'), 'mittel', 'tief')
) wirksamkeit,
parlamentarier.im_rat_seit as parlamentarier_im_rat_seit
FROM `v_interessenbindung_simple` interessenbindung
INNER JOIN `v_organisation_medium_raw` organisation
ON interessenbindung.organisation_id = organisation.id
INNER JOIN `v_parlamentarier_simple` parlamentarier
ON interessenbindung.parlamentarier_id = parlamentarier.id;

CREATE OR REPLACE VIEW `v_mandat_medium_raw` AS
SELECT CONCAT(mandat.id, ', ', person.anzeige_name, ', ', organisation.anzeige_name, ', ', mandat.art) anzeige_name,
mandat.*,
IF(organisation.vernehmlassung IN ('immer', 'punktuell')
  AND mandat.art IN ('geschaeftsfuehrend','vorstand')
  , 'hoch',
IF((organisation.vernehmlassung IN ('immer', 'punktuell')
  AND mandat.art IN ('taetig','beirat','finanziell'))
  OR (mandat.art IN ('geschaeftsfuehrend','vorstand')), 'mittel', 'tief')) wirksamkeit
FROM `v_mandat_simple` mandat
INNER JOIN `v_organisation_medium_raw` organisation
ON mandat.organisation_id = organisation.id
INNER JOIN `v_person_simple` person
ON mandat.person_id = person.id;

CREATE OR REPLACE VIEW `v_organisation_lobbyeinfluss_raw` AS
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
IF(COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) > 0 OR COUNT(DISTINCT interessenbindung_hoch.id) > 1 OR (COUNT(DISTINCT interessenbindung_hoch.id) > 0 AND COUNT(DISTINCT mandat_hoch.id) > 0), 'sehr hoch',
IF(COUNT(DISTINCT interessenbindung_hoch.id) > 0 OR (COUNT(DISTINCT interessenbindung_mittel.id) > 0 AND COUNT(DISTINCT mandat_mittel.id) > 0), 'hoch',
IF(COUNT(DISTINCT interessenbindung_mittel.id) > 0 OR COUNT(DISTINCT mandat_hoch.id) > 0, 'mittel',
'tief'))) as lobbyeinfluss,
NOW() as refreshed_date
FROM `organisation` organisation
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_hoch ON organisation.id = interessenbindung_hoch.organisation_id AND (interessenbindung_hoch.bis IS NULL OR interessenbindung_hoch.bis >= NOW()) AND interessenbindung_hoch.wirksamkeit='hoch'
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_mittel ON organisation.id = interessenbindung_mittel.organisation_id AND (interessenbindung_mittel.bis IS NULL OR interessenbindung_mittel.bis >= NOW()) AND interessenbindung_mittel.wirksamkeit='mittel'
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_tief ON organisation.id = interessenbindung_tief.organisation_id AND (interessenbindung_tief.bis IS NULL OR interessenbindung_tief.bis >= NOW()) AND interessenbindung_tief.wirksamkeit='tief'
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_hoch_nach_wahl ON organisation.id = interessenbindung_hoch_nach_wahl.organisation_id AND (interessenbindung_hoch_nach_wahl.bis IS NULL OR interessenbindung_hoch_nach_wahl.bis >= NOW()) AND interessenbindung_hoch_nach_wahl.wirksamkeit='hoch' AND interessenbindung_hoch_nach_wahl.von > interessenbindung_hoch_nach_wahl.parlamentarier_im_rat_seit
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_mittel_nach_wahl ON organisation.id = interessenbindung_mittel_nach_wahl.organisation_id AND (interessenbindung_mittel_nach_wahl.bis IS NULL OR interessenbindung_mittel_nach_wahl.bis >= NOW()) AND interessenbindung_mittel_nach_wahl.wirksamkeit='mittel' AND interessenbindung_mittel_nach_wahl.von > interessenbindung_mittel_nach_wahl.parlamentarier_im_rat_seit
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_tief_nach_wahl ON organisation.id = interessenbindung_tief_nach_wahl.organisation_id AND (interessenbindung_tief_nach_wahl.bis IS NULL OR interessenbindung_tief_nach_wahl.bis >= NOW()) AND interessenbindung_tief_nach_wahl.wirksamkeit='tief' AND interessenbindung_tief_nach_wahl.von > interessenbindung_tief_nach_wahl.parlamentarier_im_rat_seit
LEFT JOIN `v_mandat_medium_raw` mandat_hoch ON organisation.id = mandat_hoch.organisation_id AND (mandat_hoch.bis IS NULL OR mandat_hoch.bis >= NOW()) AND mandat_hoch.wirksamkeit='hoch'
LEFT JOIN `v_mandat_medium_raw` mandat_mittel ON organisation.id = mandat_mittel.organisation_id AND (mandat_mittel.bis IS NULL OR mandat_mittel.bis >= NOW()) AND mandat_mittel.wirksamkeit='mittel'
LEFT JOIN `v_mandat_medium_raw` mandat_tief ON organisation.id = mandat_tief.organisation_id AND (mandat_tief.bis IS NULL OR mandat_tief.bis >= NOW()) AND mandat_tief.wirksamkeit='tief'
GROUP BY organisation.id;

--	DROP TABLE IF EXISTS `mv_organisation_lobbyeinfluss`;
--	CREATE TABLE IF NOT EXISTS `mv_organisation_lobbyeinfluss`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_organisation_lobbyeinfluss'
--	AS SELECT * FROM `v_organisation_lobbyeinfluss_raw`;
--	ALTER TABLE `mv_organisation_lobbyeinfluss`
--	ADD PRIMARY KEY (`id`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';
--
--	CREATE OR REPLACE VIEW `v_organisation_lobbyeinfluss` AS
--	SELECT * FROM `mv_organisation_lobbyeinfluss`;

CREATE OR REPLACE VIEW `v_organisation_raw` AS
SELECT
organisation.*,
country.name_de as land,
interessenraum.anzeige_name as interessenraum,
interessenraum.anzeige_name_de as interessenraum_de,
interessenraum.anzeige_name_fr as interessenraum_fr,
organisation_jahr.`id` as organisation_jahr_id, organisation_jahr.jahr, organisation_jahr.umsatz, organisation_jahr.gewinn, organisation_jahr.kapital, organisation_jahr.mitarbeiter_weltweit, organisation_jahr.mitarbeiter_schweiz, organisation_jahr.geschaeftsbericht_url, organisation_jahr.quelle_url,
lobbyeinfluss.anzahl_interessenbindung_tief,
lobbyeinfluss.anzahl_interessenbindung_mittel,
lobbyeinfluss.anzahl_interessenbindung_hoch,
lobbyeinfluss.anzahl_interessenbindung_tief_nach_wahl,
lobbyeinfluss.anzahl_interessenbindung_mittel_nach_wahl,
lobbyeinfluss.anzahl_interessenbindung_hoch_nach_wahl,
lobbyeinfluss.anzahl_mandat_tief,
lobbyeinfluss.anzahl_mandat_mittel,
lobbyeinfluss.anzahl_mandat_hoch,
lobbyeinfluss.lobbyeinfluss,
CASE lobbyeinfluss.lobbyeinfluss
WHEN 'sehr hoch' THEN 4
WHEN 'hoch' THEN 3
WHEN 'mittel' THEN 2
WHEN 'tief' THEN 1
ELSE 0
END AS lobbyeinfluss_index
FROM `v_organisation_medium_raw` organisation
LEFT JOIN `v_organisation_lobbyeinfluss_raw` lobbyeinfluss
ON lobbyeinfluss.id = organisation.id
LEFT JOIN `v_country` country
ON country.id = organisation.land_id
LEFT JOIN `v_interessenraum` interessenraum
ON interessenraum.id = organisation.interessenraum_id
LEFT JOIN `v_organisation_jahr_last` organisation_jahr
ON organisation_jahr.organisation_id = organisation.id
;

DROP TABLE IF EXISTS `mv_organisation`;
CREATE TABLE IF NOT EXISTS `mv_organisation`
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
COMMENT='Materialzed view for v_organisation'
AS SELECT * FROM `v_organisation_raw`;
ALTER TABLE `mv_organisation`
CHANGE `anzahl_interessenbindung_tief` `anzahl_interessenbindung_tief` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_mittel` `anzahl_interessenbindung_mittel` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_hoch` `anzahl_interessenbindung_hoch` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_tief_nach_wahl` `anzahl_interessenbindung_tief_nach_wahl` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_mittel_nach_wahl` `anzahl_interessenbindung_mittel_nach_wahl` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_hoch_nach_wahl` `anzahl_interessenbindung_hoch_nach_wahl` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_tief` `anzahl_mandat_tief` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_mittel` `anzahl_mandat_mittel` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_hoch` `anzahl_mandat_hoch` TINYINT UNSIGNED NULL DEFAULT NULL,
ADD PRIMARY KEY (`id`),
-- ADD KEY `idx_name_de` (`name_de`, `freigabe_datum`),
-- ADD KEY `idx_name_fr` (`name_fr`, `freigabe_datum`),
-- ADD KEY `idx_name_it` (`name_it`, `freigabe_datum`),
-- ADD KEY `idx_freigabe` (`freigabe_datum`, `anzeige_name`),
-- ADD KEY `idx_freigabe_lobbyeinfluss` (`freigabe_datum`, `lobbyeinfluss`, `anzeige_name`),
-- ADD KEY `idx_anzeige` (`anzeige_name`, `freigabe_datum`),
-- ADD KEY `idx_lobbyeinfluss` (`lobbyeinfluss`, `anzeige_name`, `freigabe_datum`),
ADD KEY `idx_freigabe` (`freigabe_datum`),
-- indexes for joins on web
ADD KEY `idx_branche_freigabe` (`branche_id`, `freigabe_datum`),
ADD KEY `idx_interessengruppe_freigabe` (`interessengruppe_id`, `freigabe_datum`),
ADD KEY `idx_interessengruppe2_freigabe` (`interessengruppe2_id`, `freigabe_datum`),
ADD KEY `idx_interessengruppe3_freigabe` (`interessengruppe3_id`, `freigabe_datum`),
ADD KEY `idx_interessengruppe_branche_freigabe` (`interessengruppe_branche_id`, `freigabe_datum`),
ADD KEY `idx_interessengruppe2_branche_freigabe` (`interessengruppe2_branche_id`, `freigabe_datum`),
ADD KEY `idx_interessengruppe3_branche_freigabe` (`interessengruppe3_branche_id`, `freigabe_datum`),
ADD KEY `land` (`land_id`, `freigabe_datum`),
ADD KEY `interessenraum_id` (`interessenraum_id`, `freigabe_datum`),
CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

--	DROP TABLE IF EXISTS `mv_organisation_myisam`;
--	CREATE TABLE IF NOT EXISTS `mv_organisation_myisam`
--	ENGINE = MyISAM
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_organisation'
--	AS SELECT * FROM `v_organisation_raw`;
--	ALTER TABLE `mv_organisation_myisam`
--	ADD PRIMARY KEY (`id`),
--	ADD KEY `idx_name_de` (`name_de`, `freigabe_datum`),
--	ADD KEY `idx_name_fr` (`name_fr`, `freigabe_datum`),
--	ADD KEY `idx_name_it` (`name_it`, `freigabe_datum`),
--	ADD KEY `idx_anzeige_name` (`anzeige_name`, `freigabe_datum`),
--	ADD KEY `idx_lobbyeinfluss` (`lobbyeinfluss`, `anzeige_name`, `freigabe_datum`),
--	ADD KEY `idx_branche_freigabe` (`branche_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_branche` (`branche_id`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe_freigabe` (`interessengruppe_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe` (`interessengruppe_id`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe2_freigabe` (`interessengruppe2_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe2` (`interessengruppe2_id`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe3_freigabe` (`interessengruppe3_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe3` (`interessengruppe3_id`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe_branche_freigabe` (`interessengruppe_branche_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe_branche` (`interessengruppe_branche_id`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe2_branche_freigabe` (`interessengruppe2_branche_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe2_branche` (`interessengruppe2_branche_id`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe3_branche_freigabe` (`interessengruppe3_branche_id`, `freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_interessengruppe3_branche` (`interessengruppe3_branche_id`, `anzeige_name`),
--	ADD KEY `land` (`land_id`, `freigabe_datum`),
--	ADD KEY `interessenraum_id` (`interessenraum_id`, `freigabe_datum`)
--  ADD FULLTEXT(`anzeige_name`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

CREATE OR REPLACE VIEW `v_organisation` AS
SELECT * FROM `mv_organisation`;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_lobbyfaktor_raw` AS
SELECT zutrittsberechtigung.person_id,
COUNT(DISTINCT mandat_tief.id) as anzahl_mandat_tief,
COUNT(DISTINCT mandat_mittel.id) as anzahl_mandat_mittel,
COUNT(DISTINCT mandat_hoch.id) as anzahl_mandat_hoch,
COUNT(DISTINCT mandat_tief.id) + COUNT(DISTINCT mandat_mittel.id) * 5 + COUNT(DISTINCT mandat_hoch.id) * 11 as lobbyfaktor,
NOW() as refreshed_date
FROM `zutrittsberechtigung` zutrittsberechtigung
LEFT JOIN `v_mandat_medium_raw` mandat_hoch ON zutrittsberechtigung.person_id = mandat_hoch.person_id AND (mandat_hoch.bis IS NULL OR mandat_hoch.bis >= NOW()) AND mandat_hoch.wirksamkeit='hoch'
LEFT JOIN `v_mandat_medium_raw` mandat_mittel ON zutrittsberechtigung.person_id = mandat_mittel.person_id AND (mandat_mittel.bis IS NULL OR mandat_mittel.bis >= NOW()) AND mandat_mittel.wirksamkeit='mittel'
LEFT JOIN `v_mandat_medium_raw` mandat_tief ON zutrittsberechtigung.person_id = mandat_tief.person_id AND (mandat_tief.bis IS NULL OR mandat_tief.bis >= NOW()) AND mandat_tief.wirksamkeit='tief'
GROUP BY zutrittsberechtigung.person_id;

--	DROP TABLE IF EXISTS `mv_zutrittsberechtigung_lobbyfaktor`;
--	CREATE TABLE IF NOT EXISTS `mv_zutrittsberechtigung_lobbyfaktor`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_zutrittsberechtigung_lobbyfaktor'
--	AS SELECT * FROM `v_zutrittsberechtigung_lobbyfaktor_raw`;
--	ALTER TABLE `mv_zutrittsberechtigung_lobbyfaktor`
--	ADD PRIMARY KEY (`id`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am' ;
--
--	CREATE OR REPLACE VIEW `v_zutrittsberechtigung_lobbyfaktor` AS
--	SELECT * FROM `mv_zutrittsberechtigung_lobbyfaktor`;

CREATE OR REPLACE VIEW `v_parlamentarier_lobbyfaktor_raw` AS
SELECT parlamentarier.id,
COUNT(DISTINCT interessenbindung_tief.id) as anzahl_interessenbindung_tief,
COUNT(DISTINCT interessenbindung_mittel.id) as anzahl_interessenbindung_mittel,
COUNT(DISTINCT interessenbindung_hoch.id) as anzahl_interessenbindung_hoch,
COUNT(DISTINCT interessenbindung_tief_nach_wahl.id) as anzahl_interessenbindung_tief_nach_wahl,
COUNT(DISTINCT interessenbindung_mittel_nach_wahl.id) as anzahl_interessenbindung_mittel_nach_wahl,
COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) as anzahl_interessenbindung_hoch_nach_wahl,
(COUNT(DISTINCT interessenbindung_tief.id) * 1 + COUNT(DISTINCT interessenbindung_mittel.id) * 5 + COUNT(DISTINCT interessenbindung_hoch.id) * 11) + (COUNT(DISTINCT interessenbindung_tief_nach_wahl.id) * 1 + COUNT(DISTINCT interessenbindung_mittel_nach_wahl.id) * 5 + COUNT(DISTINCT interessenbindung_hoch_nach_wahl.id) * 11) as lobbyfaktor,
COUNT(DISTINCT interessenbindung_tief.id) * 1 + COUNT(DISTINCT interessenbindung_mittel.id) * 5 + COUNT(DISTINCT interessenbindung_hoch.id) * 11 as lobbyfaktor_einfach,
NOW() as refreshed_date
FROM `parlamentarier` parlamentarier
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_hoch ON parlamentarier.id = interessenbindung_hoch.parlamentarier_id AND (interessenbindung_hoch.bis IS NULL OR interessenbindung_hoch.bis >= NOW()) AND interessenbindung_hoch.wirksamkeit='hoch'
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_mittel ON parlamentarier.id = interessenbindung_mittel.parlamentarier_id AND (interessenbindung_mittel.bis IS NULL OR interessenbindung_mittel.bis >= NOW()) AND interessenbindung_mittel.wirksamkeit='mittel'
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_tief ON parlamentarier.id = interessenbindung_tief.parlamentarier_id AND (interessenbindung_tief.bis IS NULL OR interessenbindung_tief.bis >= NOW()) AND interessenbindung_tief.wirksamkeit='tief'
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_hoch_nach_wahl ON parlamentarier.id = interessenbindung_hoch_nach_wahl.parlamentarier_id AND (interessenbindung_hoch_nach_wahl.bis IS NULL OR interessenbindung_hoch_nach_wahl.bis >= NOW()) AND interessenbindung_hoch_nach_wahl.wirksamkeit='hoch' AND interessenbindung_hoch_nach_wahl.von > parlamentarier.im_rat_seit
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_mittel_nach_wahl ON parlamentarier.id = interessenbindung_mittel_nach_wahl.parlamentarier_id AND (interessenbindung_mittel_nach_wahl.bis IS NULL OR interessenbindung_mittel_nach_wahl.bis >= NOW()) AND interessenbindung_mittel_nach_wahl.wirksamkeit='mittel' AND interessenbindung_mittel_nach_wahl.von > parlamentarier.im_rat_seit
LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung_tief_nach_wahl ON parlamentarier.id = interessenbindung_tief_nach_wahl.parlamentarier_id AND (interessenbindung_tief_nach_wahl.bis IS NULL OR interessenbindung_tief_nach_wahl.bis >= NOW()) AND interessenbindung_tief_nach_wahl.wirksamkeit='tief' AND interessenbindung_tief_nach_wahl.von > parlamentarier.im_rat_seit
GROUP BY parlamentarier.id;

--	DROP TABLE IF EXISTS `mv_parlamentarier_lobbyfaktor`;
--	CREATE TABLE IF NOT EXISTS `mv_parlamentarier_lobbyfaktor`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_parlamentarier_lobbyfaktor'
--	AS SELECT * FROM `v_parlamentarier_lobbyfaktor_raw`;
--	ALTER TABLE `mv_parlamentarier_lobbyfaktor`
--	ADD PRIMARY KEY (`id`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';
--
--	CREATE OR REPLACE VIEW `v_parlamentarier_lobbyfaktor` AS
--	SELECT * FROM `mv_parlamentarier_lobbyfaktor`;

CREATE OR REPLACE VIEW `v_parlamentarier_lobbyfaktor_max_raw` AS
SELECT
1 as id,
MAX(lobbyfaktor.anzahl_interessenbindung_tief) as anzahl_interessenbindung_tief_max,
MAX(lobbyfaktor.anzahl_interessenbindung_mittel) as anzahl_interessenbindung_mittel_max,
MAX(lobbyfaktor.anzahl_interessenbindung_hoch) as anzahl_interessenbindung_hoch_max,
MAX(lobbyfaktor) as lobbyfaktor_max,
NOW() as refreshed_date
FROM `v_parlamentarier_lobbyfaktor_raw` lobbyfaktor
-- GROUP BY lobbyfaktor.id
;

--	DROP TABLE IF EXISTS `mv_parlamentarier_lobbyfaktor_max`;
--	CREATE TABLE IF NOT EXISTS `mv_parlamentarier_lobbyfaktor_max`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_parlamentarier_lobbyfaktor_max'
--	AS SELECT * FROM `v_parlamentarier_lobbyfaktor_max_raw`;
--	ALTER TABLE `mv_parlamentarier_lobbyfaktor_max`
--	ADD PRIMARY KEY (`id`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';
--
--	CREATE OR REPLACE VIEW `v_parlamentarier_lobbyfaktor_max` AS
--	SELECT * FROM `mv_parlamentarier_lobbyfaktor_max`;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_lobbyfaktor_max_raw` AS
SELECT
1 as id,
MAX(lobbyfaktor.anzahl_mandat_tief) as anzahl_mandat_tief_max,
MAX(lobbyfaktor.anzahl_mandat_mittel) as anzahl_mandat_mittel_max,
MAX(lobbyfaktor.anzahl_mandat_hoch) as anzahl_mandat_hoch_max,
MAX(lobbyfaktor) as lobbyfaktor_max,
NOW() as refreshed_date
FROM `v_zutrittsberechtigung_lobbyfaktor_raw` lobbyfaktor
-- GROUP BY lobbyfaktor.id
;

--	DROP TABLE IF EXISTS `mv_zutrittsberechtigung_lobbyfaktor_max`;
--	CREATE TABLE IF NOT EXISTS `mv_zutrittsberechtigung_lobbyfaktor_max`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_zutrittsberechtigung_lobbyfaktor_max'
--	AS SELECT * FROM `v_zutrittsberechtigung_lobbyfaktor_max_raw`;
--	ALTER TABLE `mv_zutrittsberechtigung_lobbyfaktor_max`
--	ADD PRIMARY KEY (`id`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';
--
--	CREATE OR REPLACE VIEW `v_zutrittsberechtigung_lobbyfaktor_max` AS
--	SELECT * FROM `mv_zutrittsberechtigung_lobbyfaktor_max`;

CREATE OR REPLACE VIEW `v_parlamentarier_medium_raw` AS
SELECT parlamentarier.*,
CAST(
(CASE rat.abkuerzung
  WHEN 'SR' THEN ROUND(kanton.einwohner / kanton.anzahl_staenderaete)
  WHEN 'NR' THEN ROUND(kanton.einwohner / kanton.anzahl_nationalraete)
  ELSE NULL
END)
AS UNSIGNED INTEGER) AS vertretene_bevoelkerung,
rat.abkuerzung as rat, rat.abkuerzung as ratstyp, kanton.abkuerzung as kanton_abkuerzung, kanton.abkuerzung as kanton,
rat.abkuerzung as rat_de, kanton.name_de as kanton_name_de,
rat.abkuerzung_fr as rat_fr, kanton.name_fr as kanton_name_fr,
GROUP_CONCAT(DISTINCT CONCAT(kommission.name, ' (', kommission.abkuerzung, ')') ORDER BY kommission.abkuerzung SEPARATOR '; ') kommissionen_namen,
GROUP_CONCAT(DISTINCT CONCAT(kommission.name, ' (', kommission.abkuerzung, ')') ORDER BY kommission.abkuerzung SEPARATOR '; ') kommissionen_namen_de,
GROUP_CONCAT(DISTINCT CONCAT(kommission.name_fr, ' (', kommission.abkuerzung_fr, ')') ORDER BY kommission.abkuerzung_fr SEPARATOR '; ') kommissionen_namen_fr,
GROUP_CONCAT(DISTINCT kommission.abkuerzung ORDER BY kommission.abkuerzung SEPARATOR ', ') kommissionen_abkuerzung,
GROUP_CONCAT(DISTINCT kommission.abkuerzung ORDER BY kommission.abkuerzung SEPARATOR ', ') kommissionen_abkuerzung_de,
GROUP_CONCAT(DISTINCT kommission.abkuerzung_fr ORDER BY kommission.abkuerzung_fr SEPARATOR ', ') kommissionen_abkuerzung_fr,
COUNT(DISTINCT kommission.id) AS kommissionen_anzahl,
partei.abkuerzung AS partei, partei.name AS partei_name, fraktion.abkuerzung AS fraktion, mil_grad.name as militaerischer_grad,
partei.abkuerzung AS partei_de, partei.name AS partei_name_de, mil_grad.name as militaerischer_grad_de,
partei.abkuerzung_fr AS partei_fr, partei.name_fr AS partei_name_fr, mil_grad.name_fr as militaerischer_grad_fr,
interessengruppe.branche_id as beruf_branche_id,
-- Workaround: Add  COLLATE utf8_general_ci, otherwise ERROR 1270 (HY000): Illegal mix of collations (latin1_swedish_ci,IMPLICIT), (utf8_general_ci,COERCIBLE), (utf8_general_ci,COERCIBLE) for operation 'concat'
-- CONCAT(IF(parlamentarier.geschlecht='M', rat.name_de, ''), IF(parlamentarier.geschlecht='F' AND rat.abkuerzung='NR', 'Nationalrätin', '') COLLATE utf8_general_ci, IF(parlamentarier.geschlecht='F' AND rat.abkuerzung='SR', 'Ständerätin', '') COLLATE utf8_general_ci) titel_de,
CONCAT(IF(parlamentarier.geschlecht='M', rat.mitglied_bezeichnung_maennlich_de, ''), IF(parlamentarier.geschlecht='F', rat.mitglied_bezeichnung_weiblich_de, '')) titel_de,
-- i18n in rat tabelle verschieben
CONCAT(IF(parlamentarier.geschlecht='M', rat.mitglied_bezeichnung_maennlich_fr, ''), IF(parlamentarier.geschlecht='F', rat.mitglied_bezeichnung_weiblich_fr, '')) titel_fr,
-- GREATEST(MAX(parlamentarier.updated_date_unix), MAX(interessenbindung.updated_date_unix)) as combined_updated_date_unix,
NOW() as refreshed_date
FROM `v_parlamentarier_simple` parlamentarier
LEFT JOIN `in_kommission` in_kommission ON parlamentarier.id = in_kommission.parlamentarier_id AND in_kommission.bis IS NULL
LEFT JOIN `kommission` kommission ON in_kommission.kommission_id=kommission.id
LEFT JOIN `v_partei` partei ON parlamentarier.partei_id=partei.id
LEFT JOIN `v_fraktion` fraktion ON parlamentarier.fraktion_id=fraktion.id
LEFT JOIN `v_mil_grad` mil_grad ON parlamentarier.militaerischer_grad_id=mil_grad.id
LEFT JOIN `v_kanton` kanton ON parlamentarier.kanton_id = kanton.id
LEFT JOIN `v_rat` rat ON parlamentarier.rat_id = rat.id
LEFT JOIN `v_interessengruppe` interessengruppe ON parlamentarier.beruf_interessengruppe_id = interessengruppe.id
-- LEFT JOIN `v_interessenbindung_medium_raw` interessenbindung ON parlamentarier.id = interessenbindung.parlamentarier_id AND interessenbindung.freigabe_datum > NOW()
GROUP BY parlamentarier.id;

--	DROP TABLE IF EXISTS `mv_parlamentarier_medium`;
--	CREATE TABLE IF NOT EXISTS `mv_parlamentarier_medium`
--	ENGINE = InnoDB
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_parlamentarier_medium'
--	AS SELECT * FROM `v_parlamentarier_medium_raw`;
--	ALTER TABLE `mv_parlamentarier_medium`
--	ADD PRIMARY KEY (`id`),
--	ADD KEY `idx_id_freigabe_bis` (`id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_id_bis` (`id`, `im_rat_bis`),
--	ADD KEY `idx_anzeige_name_freigabe_bis` (`anzeige_name`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_anzeige_name_bis` (`anzeige_name`, `im_rat_bis`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';
--
--	DROP TABLE IF EXISTS `mv_parlamentarier_medium_myisam`;
--	CREATE TABLE IF NOT EXISTS `mv_parlamentarier_medium_myisam`
--	ENGINE = MyISAM
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_parlamentarier_medium'
--	AS SELECT * FROM `v_parlamentarier_medium_raw`;
--	ALTER TABLE `mv_parlamentarier_medium_myisam`
--	ADD PRIMARY KEY (`id`),
--	ADD KEY `idx_anzeige_name_freigabe_bis` (`anzeige_name`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_anzeige_name_bis` (`anzeige_name`, `im_rat_bis`),
--	ADD FULLTEXT(`anzeige_name`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

--	CREATE OR REPLACE VIEW `v_parlamentarier_medium` AS
--	SELECT * FROM `mv_parlamentarier_medium_raw`;

CREATE OR REPLACE VIEW `v_parlamentarier_raw` AS
SELECT parlamentarier.*,
lobbyfaktor.anzahl_interessenbindung_tief,
lobbyfaktor.anzahl_interessenbindung_mittel,
lobbyfaktor.anzahl_interessenbindung_hoch,
lobbyfaktor.anzahl_interessenbindung_tief_nach_wahl,
lobbyfaktor.anzahl_interessenbindung_mittel_nach_wahl,
lobbyfaktor.anzahl_interessenbindung_hoch_nach_wahl,
lobbyfaktor.lobbyfaktor,
lobbyfaktor_max.lobbyfaktor_max,
ROUND(lobbyfaktor.lobbyfaktor / lobbyfaktor_max.lobbyfaktor_max, 3) as lobbyfaktor_percent_max,
lobbyfaktor_max.anzahl_interessenbindung_tief_max,
lobbyfaktor_max.anzahl_interessenbindung_mittel_max,
lobbyfaktor_max.anzahl_interessenbindung_hoch_max
FROM `v_parlamentarier_medium_raw` parlamentarier
LEFT JOIN `v_parlamentarier_lobbyfaktor_raw` lobbyfaktor ON parlamentarier.id = lobbyfaktor.id
, v_parlamentarier_lobbyfaktor_max_raw lobbyfaktor_max
GROUP BY parlamentarier.id;

DROP TABLE IF EXISTS `mv_parlamentarier`;
CREATE TABLE IF NOT EXISTS `mv_parlamentarier`
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
COMMENT='Materialzed view for v_parlamentarier'
AS SELECT * FROM `v_parlamentarier_raw`;
ALTER TABLE `mv_parlamentarier`
CHANGE `anzahl_interessenbindung_tief` `anzahl_interessenbindung_tief` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_mittel` `anzahl_interessenbindung_mittel` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_hoch` `anzahl_interessenbindung_hoch` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_tief_nach_wahl` `anzahl_interessenbindung_tief_nach_wahl` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_mittel_nach_wahl` `anzahl_interessenbindung_mittel_nach_wahl` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_hoch_nach_wahl` `anzahl_interessenbindung_hoch_nach_wahl` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `lobbyfaktor` `lobbyfaktor` SMALLINT UNSIGNED NULL DEFAULT NULL,
CHANGE `lobbyfaktor_max` `lobbyfaktor_max` SMALLINT UNSIGNED NULL DEFAULT NULL,
CHANGE `lobbyfaktor_percent_max` `lobbyfaktor_percent_max` DECIMAL(4,3) UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_tief_max` `anzahl_interessenbindung_tief_max` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_mittel_max` `anzahl_interessenbindung_mittel_max` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_interessenbindung_hoch_max` `anzahl_interessenbindung_hoch_max` TINYINT UNSIGNED NULL DEFAULT NULL,
ADD PRIMARY KEY (`id`),
-- DROP COLUMN makes trouble in resfresh from SELECT
-- DROP COLUMN `ratstyp`,
-- DROP COLUMN `kanton_abkuerzung`,
CHANGE `ratstyp` `ratstyp_BAD` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Not used, duplicate',
CHANGE `kanton_abkuerzung` `kanton_abkuerzung_BAD` ENUM( 'AG', 'AR', 'AI', 'BL', 'BS', 'BE', 'FR', 'GE', 'GL', 'GR', 'JU', 'LU', 'NE', 'NW', 'OW', 'SH', 'SZ', 'SO', 'SG', 'TI', 'TG', 'UR', 'VD', 'VS', 'ZG', 'ZH' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Not used, duplicate',
-- for sort lobbyfaktor, anzeige_name
ADD KEY `idx_freigabe_bis` (`freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_bis` (`im_rat_bis`),
--	ADD KEY `idx_lobbyfaktor` (`lobbyfaktor`, `anzeige_name`),
--	ADD KEY `idx_freigabe_bis_anzeige` (`freigabe_datum`, `im_rat_bis`, `anzeige_name`),
--	ADD KEY `idx_freigabe_anzeige` (`freigabe_datum`, `anzeige_name`),
--	ADD KEY `idx_bis_anzeige` (`im_rat_bis`, `anzeige_name`),
--	ADD KEY `idx_anzeige` (`anzeige_name`),
-- indexes for joins on web
ADD KEY `idx_rat_freigabe_bis` (`rat`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_rat_bis` (`rat`, `im_rat_bis`),
ADD KEY `idx_rat_id_freigabe_bis` (`rat_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_rat_id_bis` (`rat_id`, `im_rat_bis`),
ADD KEY `idx_kanton_freigabe_bis` (`kanton`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_kanton_bis` (`kanton`, `im_rat_bis`),
ADD KEY `idx_kanton_partei_freigabe_bis` (`kanton`, `partei`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_kanton_partei_bis` (`kanton`, `partei`, `im_rat_bis`),
ADD KEY `idx_kanton_id_freigabe_bis` (`kanton_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_kanton_id_bis` (`kanton_id`, `im_rat_bis`),
ADD KEY `idx_partei_freigabe_bis` (`partei`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_partei_bis` (`partei`, `im_rat_bis`),
ADD KEY `idx_partei_id_freigabe_bis` (`partei_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `idx_partei_id_bis` (`partei_id`, `im_rat_bis`),
-- ADD KEY `idx_kommissionen` (`kommissionen`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `beruf_interessengruppe_id` (`beruf_interessengruppe_id`, `im_rat_bis`),
ADD KEY `beruf_branche_id_freigabe` (`beruf_branche_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `beruf_branche_id` (`beruf_branche_id`, `im_rat_bis`),
ADD KEY `militaerischer_grad_freigabe` (`militaerischer_grad_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `militaerischer_grad` (`militaerischer_grad_id`, `im_rat_bis`),
ADD KEY `fraktion_freigabe_bis` (`fraktion`, `im_rat_bis`),
ADD KEY `fraktion_bis` (`fraktion`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `fraktion_id_freigabe_bis` (`fraktion_id`, `freigabe_datum`, `im_rat_bis`),
ADD KEY `fraktion_id_bis` (`fraktion_id`, `im_rat_bis`),
CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

--	DROP TABLE IF EXISTS `mv_parlamentarier_myisam`;
--	CREATE TABLE IF NOT EXISTS `mv_parlamentarier_myisam`
--	ENGINE = MyISAM
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_parlamentarier'
--	AS SELECT * FROM `v_parlamentarier_raw`;
--	ALTER TABLE `mv_parlamentarier_myisam`
--	ADD PRIMARY KEY (`id`),
--	ADD KEY `idx_lobbyfaktor` (`lobbyfaktor`, `anzeige_name`, `freigabe_datum` `im_rat_bis`),
--	ADD KEY `idx_anzeige_name_freigabe_bis` (`anzeige_name`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_anzeige_name_bis` (`anzeige_name`, `im_rat_bis`),
--	ADD KEY `idx_ratstyp_freigabe` (`ratstyp`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_ratstyp` (`ratstyp`, `im_rat_bis`),
--	ADD KEY `idx_rat_freigabe` (`rat`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_rat` (`rat`, `im_rat_bis`),
--	ADD KEY `idx_rat_id_freigabe` (`rat_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_rat_id` (`rat_id`, `im_rat_bis`),
--	ADD KEY `idx_kanton_freigabe` (`kanton`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_kanton` (`kanton`, `im_rat_bis`),
--	ADD KEY `idx_kanton_id_freigabe` (`kanton_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_kanton_id` (`kanton_id`, `im_rat_bis`),
--	ADD KEY `idx_partei_freigabe` (`partei`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_partei` (`partei`, `im_rat_bis`),
--	ADD KEY `idx_partei_id_freigabe` (`partei_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `idx_partei_id` (`partei_id`, `im_rat_bis`),
--	-- ADD KEY `idx_kommissionen` (`kommissionen`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `beruf_interessengruppe_id` (`beruf_interessengruppe_id`, `im_rat_bis`),
--	ADD KEY `beruf_branche_id_freigabe` (`beruf_interessengruppe_branche_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `beruf_branche_id` (`beruf_interessengruppe_branche_id`, `im_rat_bis`),
--	ADD KEY `militaerischer_grad_freigabe` (`militaerischer_grad_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `militaerischer_grad` (`militaerischer_grad_id`, `im_rat_bis`),
--	ADD KEY `fraktion_freigabe` (`fraktion`, `im_rat_bis`),
--	ADD KEY `fraktion` (`fraktion`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `fraktion_id_freigabe` (`fraktion_id`, `freigabe_datum`, `im_rat_bis`),
--	ADD KEY `fraktion_id` (`fraktion_id`, `im_rat_bis`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

CREATE OR REPLACE VIEW `v_parlamentarier` AS
SELECT *, rat as ratstyp, kanton as `kanton_abkuerzung` FROM `mv_parlamentarier`;

CREATE OR REPLACE VIEW `v_person` AS
SELECT
person.*
FROM `v_person_simple` person
;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_simple` AS
SELECT zutrittsberechtigung.*,
UNIX_TIMESTAMP(bis) as bis_unix, UNIX_TIMESTAMP(von) as von_unix,
UNIX_TIMESTAMP(zutrittsberechtigung.created_date) as created_date_unix, UNIX_TIMESTAMP(zutrittsberechtigung.updated_date) as updated_date_unix, UNIX_TIMESTAMP(zutrittsberechtigung.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(zutrittsberechtigung.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(zutrittsberechtigung.freigabe_datum) as freigabe_datum_unix
FROM `zutrittsberechtigung`;

-- Compatibility view simulating the previous v_zutrittsberechtigung_simple
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_simple_compat` AS
SELECT
person.anzeige_name,
person.anzeige_name_de,
person.anzeige_name_fr,
person.name,
person.name_de,
person.name_fr,
-- person.*,
person.id,
-- person.`parlamentarier_id` ,
person.`nachname` ,
person.`vorname` ,
person.`zweiter_vorname` ,
person.`beschreibung_de` ,
person.`beschreibung_fr` ,
person.`parlamentarier_kommissionen` ,
zutrittsberechtigung.`parlamentarier_kommissionen` as `parlamentarier_kommissionen_zutrittsberechtigung`,
person.`beruf` ,
person.`beruf_fr` ,
person.`beruf_interessengruppe_id` ,
person.`partei_id` ,
person.`geschlecht` ,
person.`arbeitssprache` ,
person.`email` ,
person.`homepage` ,
person.`twitter_name` ,
person.`linkedin_profil_url` ,
person.`xing_profil_name` ,
person.`facebook_name` ,
person.`telephon_1` ,
person.`telephon_2` ,
person.`erfasst` ,
person.`notizen` ,
person.`eingabe_abgeschlossen_visa` as `eingabe_abgeschlossen_visa_person`,
person.`eingabe_abgeschlossen_datum` as `eingabe_abgeschlossen_datum_person` ,
person.`kontrolliert_visa` as `kontrolliert_visa_person` ,
person.`kontrolliert_datum` as `kontrolliert_datum_person` ,
person.`autorisierung_verschickt_visa` ,
person.`autorisierung_verschickt_datum` ,
person.`autorisiert_visa` ,
person.`autorisiert_datum` ,
person.`freigabe_visa` as `freigabe_visa_person`,
person.`freigabe_datum` as `freigabe_datum_person`,
person.`created_visa` as `created_visa_person`,
person.`created_date` as `created_date_person`,
person.`updated_visa` as `updated_visa_person`,
person.`updated_date` as `updated_date_person`,
UNIX_TIMESTAMP(person.created_date) as created_date_unix_person, UNIX_TIMESTAMP(person.updated_date) as updated_date_unix_person, UNIX_TIMESTAMP(person.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix_person, UNIX_TIMESTAMP(person.kontrolliert_datum) as kontrolliert_datum_unix_person, UNIX_TIMESTAMP(person.freigabe_datum) as freigabe_datum_unix_person,
-- fields of zutrittsberechtigung
zutrittsberechtigung.`parlamentarier_id`,
zutrittsberechtigung.`person_id`,
zutrittsberechtigung.`id` as zutrittsberechtigung_id,
zutrittsberechtigung.`funktion`,
zutrittsberechtigung.`funktion_fr`,
zutrittsberechtigung.`von`,
zutrittsberechtigung.`bis`,
zutrittsberechtigung.`eingabe_abgeschlossen_visa` ,
zutrittsberechtigung.`eingabe_abgeschlossen_datum` ,
zutrittsberechtigung.`kontrolliert_visa` ,
zutrittsberechtigung.`kontrolliert_datum` ,
-- zutrittsberechtigung.`autorisierung_verschickt_visa` ,
-- zutrittsberechtigung.`autorisierung_verschickt_datum` ,
-- zutrittsberechtigung.`autorisiert_visa` ,
-- zutrittsberechtigung.`autorisiert_datum` ,
zutrittsberechtigung.`freigabe_visa` ,
zutrittsberechtigung.`freigabe_datum` ,
zutrittsberechtigung.`created_visa` ,
zutrittsberechtigung.`created_date` ,
zutrittsberechtigung.`updated_visa` ,
zutrittsberechtigung.`updated_date` ,
UNIX_TIMESTAMP(zutrittsberechtigung.bis) as bis_unix, UNIX_TIMESTAMP(zutrittsberechtigung.von) as von_unix,
UNIX_TIMESTAMP(zutrittsberechtigung.created_date) as created_date_unix, UNIX_TIMESTAMP(zutrittsberechtigung.updated_date) as updated_date_unix, UNIX_TIMESTAMP(zutrittsberechtigung.eingabe_abgeschlossen_datum) as eingabe_abgeschlossen_datum_unix, UNIX_TIMESTAMP(zutrittsberechtigung.kontrolliert_datum) as kontrolliert_datum_unix, UNIX_TIMESTAMP(zutrittsberechtigung.freigabe_datum) as freigabe_datum_unix
FROM `zutrittsberechtigung`
INNER JOIN v_person_simple person
  ON person.id = zutrittsberechtigung.person_id
;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_raw` AS
SELECT
zutrittsberechtigung.*,
-- fields of zutrittsberechtigung
-- zutrittsberechtigung.`parlamentarier_id`,
-- zutrittsberechtigung.`person_id`,
-- zutrittsberechtigung.`funktion`,
-- zutrittsberechtigung.`funktion_fr`,
-- zutrittsberechtigung.`von`,
-- zutrittsberechtigung.`bis`,
interessengruppe.branche_id as beruf_branche_id,
partei.abkuerzung AS partei,
partei.abkuerzung AS partei_de,
partei.abkuerzung_fr AS partei_fr,
parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.freigabe_datum as parlamentarier_freigabe_datum, UNIX_TIMESTAMP(parlamentarier.freigabe_datum) as parlamentarier_freigabe_datum_unix,
lobbyfaktor.anzahl_mandat_tief,
lobbyfaktor.anzahl_mandat_mittel,
lobbyfaktor.anzahl_mandat_hoch,
lobbyfaktor.lobbyfaktor,
lobbyfaktor_max.lobbyfaktor_max,
ROUND(lobbyfaktor.lobbyfaktor / lobbyfaktor_max.lobbyfaktor_max, 3) as lobbyfaktor_percent_max,
lobbyfaktor_max.anzahl_mandat_tief_max,
lobbyfaktor_max.anzahl_mandat_mittel_max,
lobbyfaktor_max.anzahl_mandat_hoch_max,
NOW() as refreshed_date
FROM `v_zutrittsberechtigung_simple_compat` zutrittsberechtigung
-- LEFT JOIN `v_person` person
-- ON person.id = zutrittsberechtigung.person_id
LEFT JOIN `v_partei` partei
ON zutrittsberechtigung.partei_id=partei.id
LEFT JOIN `v_parlamentarier_raw` parlamentarier
ON parlamentarier.id = zutrittsberechtigung.parlamentarier_id
LEFT JOIN `v_zutrittsberechtigung_lobbyfaktor_raw` lobbyfaktor ON zutrittsberechtigung.person_id = lobbyfaktor.person_id
LEFT JOIN `v_interessengruppe` interessengruppe ON zutrittsberechtigung.beruf_interessengruppe_id = interessengruppe.id
, v_zutrittsberechtigung_lobbyfaktor_max_raw lobbyfaktor_max
;

DROP TABLE IF EXISTS `mv_zutrittsberechtigung`;
CREATE TABLE IF NOT EXISTS `mv_zutrittsberechtigung`
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
COMMENT='Materialzed view for v_zutrittsberechtigung'
AS SELECT * FROM `v_zutrittsberechtigung_raw`;
ALTER TABLE `mv_zutrittsberechtigung`
CHANGE `anzahl_mandat_tief` `anzahl_mandat_tief` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_mittel` `anzahl_mandat_mittel` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_hoch` `anzahl_mandat_hoch` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `lobbyfaktor` `lobbyfaktor` SMALLINT UNSIGNED NULL DEFAULT NULL,
CHANGE `lobbyfaktor_max` `lobbyfaktor_max` SMALLINT UNSIGNED NULL DEFAULT NULL,
CHANGE `lobbyfaktor_percent_max` `lobbyfaktor_percent_max` DECIMAL(4,3) UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_tief_max` `anzahl_mandat_tief_max` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_mittel_max` `anzahl_mandat_mittel_max` TINYINT UNSIGNED NULL DEFAULT NULL,
CHANGE `anzahl_mandat_hoch_max` `anzahl_mandat_hoch_max` TINYINT UNSIGNED NULL DEFAULT NULL,
ADD PRIMARY KEY (`zutrittsberechtigung_id`),
-- indexes for joins on web
ADD KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`, `freigabe_datum`, `bis`),
ADD KEY `idx_parlam_bis` (`parlamentarier_id`, `bis`),
ADD KEY `idx_partei_freigabe` (`partei`, `freigabe_datum`, `bis`),
ADD KEY `idx_partei` (`partei`, `bis`),
ADD KEY `idx_partei_id_freigabe` (`partei_id`, `freigabe_datum`, `bis`),
ADD KEY `idx_partei_id` (`partei_id`, `bis`),
ADD KEY `idx_beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`, `freigabe_datum`, `bis`),
ADD KEY `idx_beruf_interessengruppe_id` (`beruf_interessengruppe_id`, `bis`),
ADD KEY `idx_beruf_branche_id_freigabe` (`beruf_branche_id`, `freigabe_datum`, `bis`),
ADD KEY `idx_beruf_branche_id` (`beruf_branche_id`, `bis`),
CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

--	DROP TABLE IF EXISTS `mv_zutrittsberechtigung_myisam`;
--	CREATE TABLE IF NOT EXISTS `mv_zutrittsberechtigung_myisam`
--	ENGINE = MyISAM
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_zutrittsberechtigung'
--	AS SELECT * FROM `v_zutrittsberechtigung_raw`;
--	ALTER TABLE `mv_zutrittsberechtigung_myisam`
--	ADD PRIMARY KEY (`id`),
--	ADD KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`, `freigabe_datum`, `bis`, `lobbyfaktor`, `anzeige_name`),
--	ADD KEY `idx_parlam_bis` (`parlamentarier_id`, `bis`, `lobbyfaktor`, `anzeige_name`),
--	ADD KEY `idx_parlam_wirksamkeit` (`parlamentarier_id`, `lobbyfaktor`, `anzeige_name`),
--	ADD KEY `idx_parlam_anzeige` (`parlamentarier_id`, `anzeige_name`),
--	ADD KEY `idx_lobbyfaktor` (`lobbyfaktor`, `anzeige_name`),
--	ADD KEY `idx_anzeige_name` (`anzeige_name`),
--	ADD KEY `idx_partei_freigabe` (`partei`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_partei` (`partei`, `bis`),
--	ADD KEY `idx_partei_id_freigabe` (`partei_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_partei_id` (`partei_id`, `bis`),
--	ADD KEY `idx_beruf_interessengruppe_id_freigabe` (`beruf_interessengruppe_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_beruf_interessengruppe_id` (`beruf_interessengruppe_id`, `im_rat_bis`),
--	ADD KEY `idx_beruf_branche_id_freigabe` (`beruf_interessengruppe_branche_id`, `freigabe_datum`, `bis`),
--	ADD KEY `idx_beruf_branche_id` (`beruf_interessengruppe_branche_id`, `im_rat_bis`),
--	ADD FULLTEXT(`anzeige_name`),
--	CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

CREATE OR REPLACE VIEW `v_zutrittsberechtigung` AS
SELECT * FROM `mv_zutrittsberechtigung`;

CREATE OR REPLACE VIEW `v_mandat_raw` AS
SELECT mandat.*,
CASE mandat.wirksamkeit
WHEN 'hoch' THEN 3
WHEN 'mittel' THEN 2
WHEN 'tief' THEN 1
ELSE 0
END AS wirksamkeit_index,
organisation.lobbyeinfluss organisation_lobbyeinfluss,
-- parlamentarier.lobbyfaktor parlamentarier_lobbyfaktor,
NOW() as refreshed_date
FROM `v_mandat_medium_raw` mandat
INNER JOIN `v_organisation_raw` organisation
ON mandat.organisation_id = organisation.id;

DROP TABLE IF EXISTS `mv_mandat`;
CREATE TABLE IF NOT EXISTS `mv_mandat`
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
COMMENT='Materialzed view for v_mandat'
AS SELECT * FROM `v_mandat_raw`;
ALTER TABLE `mv_mandat`
ADD PRIMARY KEY (`id`),
-- indexes for joins on web
ADD KEY `idx_person_freigabe_bis` (`person_id`, `freigabe_datum`, `bis`, `organisation_id`),
ADD KEY `idx_person_bis` (`person_id`, `bis`, `organisation_id`),
ADD KEY `idx_person` (`person_id`, `organisation_id`),
ADD KEY `idx_org_freigabe_bis` (`organisation_id`, `freigabe_datum`, `bis`, `person_id`),
ADD KEY `idx_org_bis` (`organisation_id`, `bis`, `person_id`),
ADD KEY `idx_org` (`organisation_id`, `person_id`),
CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

CREATE OR REPLACE VIEW `v_mandat` AS
SELECT * FROM `mv_mandat`;

CREATE OR REPLACE VIEW `v_interessenbindung_raw` AS
SELECT interessenbindung.*,
CASE interessenbindung.wirksamkeit
WHEN 'hoch' THEN 3
WHEN 'mittel' THEN 2
WHEN 'tief' THEN 1
ELSE 0
END AS wirksamkeit_index,
organisation.lobbyeinfluss organisation_lobbyeinfluss,
parlamentarier.lobbyfaktor parlamentarier_lobbyfaktor,
NOW() as refreshed_date
FROM `v_interessenbindung_medium_raw` interessenbindung
INNER JOIN `v_organisation_raw` organisation
ON interessenbindung.organisation_id = organisation.id
INNER JOIN `v_parlamentarier_raw` parlamentarier
ON interessenbindung.parlamentarier_id = parlamentarier.id;

DROP TABLE IF EXISTS `mv_interessenbindung`;
CREATE TABLE IF NOT EXISTS `mv_interessenbindung`
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
COMMENT='Materialzed view for v_interessenbindung'
AS SELECT * FROM `v_interessenbindung_raw`;
ALTER TABLE `mv_interessenbindung`
ADD PRIMARY KEY (`id`),
-- indexes for joins on web
ADD KEY `idx_parlam_freigabe_bis` (`parlamentarier_id`, `freigabe_datum`, `bis`, `organisation_id`),
ADD KEY `idx_parlam_bis` (`parlamentarier_id`, `bis`, `organisation_id`),
ADD KEY `idx_parlam` (`parlamentarier_id`, `organisation_id`),
ADD KEY `idx_org_freigabe_bis` (`organisation_id`, `freigabe_datum`, `bis`, `parlamentarier_id`),
ADD KEY `idx_org_bis` (`organisation_id`, `bis`, `parlamentarier_id`),
ADD KEY `idx_org` (`organisation_id`, `parlamentarier_id`),
CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

CREATE OR REPLACE VIEW `v_interessenbindung` AS
SELECT * FROM `mv_interessenbindung`;

-- Kommissionen für Parlamentarier
-- Connector: in_kommission.parlamentarier_id
CREATE OR REPLACE VIEW `v_in_kommission_liste` AS
SELECT kommission.abkuerzung, kommission.name, kommission.typ, kommission.art, kommission.beschreibung, kommission.sachbereiche, kommission.mutter_kommission_id, kommission.parlament_url, in_kommission.*
FROM v_in_kommission_simple in_kommission
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
, `parlamentarier`.`rat`
, `parlamentarier`.`rat_de`
, `parlamentarier`.`rat_fr`
-- , `parlamentarier`.`ratstyp`
, `parlamentarier`.`kanton`
, `parlamentarier`.`vertretene_bevoelkerung`
, `parlamentarier`.`kommissionen_namen`
, `parlamentarier`.`kommissionen_abkuerzung`
, `parlamentarier`.`partei`
, `parlamentarier`.`partei_de`
, `parlamentarier`.`partei_fr`
, `parlamentarier`.`fraktion`
, `parlamentarier`.`militaerischer_grad`
, `parlamentarier`.`militaerischer_grad_de`
, `parlamentarier`.`militaerischer_grad_fr`
, in_kommission.*
FROM v_in_kommission_simple in_kommission
INNER JOIN v_parlamentarier parlamentarier
  ON in_kommission.parlamentarier_id = parlamentarier.id
ORDER BY parlamentarier.anzeige_name;

-- Interessenbindung eines Parlamentariers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste` AS
SELECT
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
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
, interessenbindung_jahr.verguetung
, interessenbindung_jahr.jahr as verguetung_jahr
, interessenbindung_jahr.beschreibung as verguetung_beschreibung
, interessenbindung_jahr.quelle as verguetung_quelle
, interessenbindung_jahr.quelle_url as verguetung_quelle_url
FROM v_interessenbindung interessenbindung
LEFT JOIN v_interessenbindung_jahr interessenbindung_jahr
  on interessenbindung_jahr.id = (
    SELECT
      ijn.id
    FROM v_interessenbindung_jahr ijn 
    WHERE ijn.interessenbindung_id = interessenbindung.id
    ORDER BY ijn.jahr DESC
    LIMIT 1
  )
INNER JOIN v_organisation organisation
  ON interessenbindung.organisation_id = organisation.id
ORDER BY interessenbindung.wirksamkeit, organisation.anzeige_name;

-- Indirekte Interessenbindungen eines Parlamentariers
-- Connector: interessenbindung.parlamentarier_id
CREATE OR REPLACE VIEW `v_interessenbindung_liste_indirekt` AS
SELECT 'direkt' as beziehung, interessenbindung_liste.* FROM v_interessenbindung_liste interessenbindung_liste
UNION
SELECT 'indirekt' as beziehung
, `organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
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

-- Mandate einer Person (INNER JOIN)
-- Connector: person_mandate.person_id
CREATE OR REPLACE VIEW `v_person_mandate` AS
SELECT
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
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
, person.anzeige_name as person_name
, mandat.*
FROM v_person person
INNER JOIN v_mandat mandat
  ON person.id = mandat.person_id
INNER JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
ORDER BY mandat.wirksamkeit, organisation.anzeige_name;

-- Mandate einer Zutrittsberechtigung (INNER JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mandate` AS
SELECT zutrittsberechtigung.parlamentarier_id
-- , zutrittsberechtigung.id as zutrittsberechtigung_id
, `organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
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
, person.anzeige_name as zutrittsberechtigung_name
, zutrittsberechtigung.funktion
, zutrittsberechtigung.funktion_fr
, mandat.*
, mandat_jahr.verguetung
, mandat_jahr.jahr as verguetung_jahr
, mandat_jahr.beschreibung as verguetung_beschreibung
, mandat_jahr.quelle as verguetung_quelle
, mandat_jahr.quelle_url as verguetung_quelle_url
FROM v_zutrittsberechtigung_simple zutrittsberechtigung
INNER JOIN v_mandat mandat
  ON zutrittsberechtigung.person_id = mandat.person_id
LEFT JOIN v_mandat_jahr mandat_jahr
  on mandat_jahr.id = (
    SELECT
      mjn.id
    FROM v_mandat_jahr mjn 
    WHERE mjn.mandat_id = mandat.id
    ORDER BY mjn.jahr DESC
    LIMIT 1
  )
INNER JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
INNER JOIN v_person person
  ON person.id = zutrittsberechtigung.person_id
ORDER BY mandat.wirksamkeit, organisation.anzeige_name;

-- Mandate einer Zutrittsberechtigung (LFET JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mit_mandaten` AS
SELECT
`organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
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
, person.anzeige_name as zutrittsberechtigung_name
, zutrittsberechtigung.funktion
, zutrittsberechtigung.parlamentarier_id
, mandat.*
FROM v_zutrittsberechtigung_simple zutrittsberechtigung
LEFT JOIN v_mandat_simple mandat
  ON zutrittsberechtigung.person_id = mandat.person_id
LEFT JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
LEFT JOIN v_person person
  ON person.id = zutrittsberechtigung.person_id
ORDER BY person.anzeige_name;

-- Indirekte Mandate einer Zutrittsberechtigung (INNER JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mit_mandaten_indirekt` AS
SELECT 'direkt' as beziehung, zutrittsberechtigung.* FROM v_zutrittsberechtigung_mit_mandaten zutrittsberechtigung
UNION
SELECT 'indirekt' as beziehung
, `organisation`.`anzeige_name` as `organisation_name`
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
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
, person.anzeige_name as zutrittsberechtigung_name
, zutrittsberechtigung.funktion
, zutrittsberechtigung.parlamentarier_id
, mandat.*
FROM v_zutrittsberechtigung_simple zutrittsberechtigung
INNER JOIN v_mandat_simple mandat
  ON zutrittsberechtigung.person_id = mandat.person_id
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON mandat.organisation_id = organisation_beziehung.organisation_id
INNER JOIN v_organisation organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
INNER JOIN v_person person
  ON person.id = zutrittsberechtigung.person_id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, organisation_name;

-- Organisationen für welche eine PR-Agentur arbeitet.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_arbeitet_fuer` AS
SELECT organisation.anzeige_name as organisation_name
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.anzeige_name_de, organisation.anzeige_name_fr, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation_simple organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, die eine PR-Firma beauftragt haben.
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_auftraggeber_fuer` AS
SELECT organisation.anzeige_name as organisation_name
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.anzeige_name_de, organisation.anzeige_name_fr, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation_simple organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY organisation.anzeige_name;

-- Organisationen, in welcher eine Organisation Mitglied ist.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglied_von` AS
SELECT organisation.anzeige_name as organisation_name
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.anzeige_name_de, organisation.anzeige_name_fr, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation_simple organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Mitgliedsorganisationen
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_mitglieder` AS
SELECT organisation.anzeige_name as organisation_name
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.anzeige_name_de, organisation.anzeige_name_fr, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation_simple organisation
  ON organisation_beziehung.organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'mitglied von'
ORDER BY organisation.anzeige_name;

-- Muttergesellschaften.
-- Connector: organisation_beziehung.organisation_id
CREATE OR REPLACE VIEW `v_organisation_beziehung_muttergesellschaft` AS
SELECT organisation.anzeige_name as organisation_name
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.anzeige_name_de, organisation.anzeige_name_fr, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation_simple organisation
  ON organisation_beziehung.ziel_organisation_id = organisation.id
WHERE
  organisation_beziehung.art = 'tochtergesellschaft von'
ORDER BY organisation.anzeige_name;

-- Tochtergesellschaften
-- Connector: organisation_beziehung.ziel_organisation_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_beziehung_tochtergesellschaften` AS
SELECT organisation.anzeige_name as organisation_name
, `organisation`.`anzeige_name_de` as `organisation_name_de`
, `organisation`.`anzeige_name_fr` as `organisation_name_fr`
, organisation_beziehung.organisation_id, organisation_beziehung.ziel_organisation_id, organisation_beziehung.art, organisation_beziehung.von, organisation_beziehung.bis, organisation_beziehung.freigabe_datum, organisation_beziehung.freigabe_datum_unix, organisation.id, organisation.name_de, organisation.rechtsform, organisation.anzeige_name, organisation.anzeige_name_de, organisation.anzeige_name_fr, organisation.ort
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_organisation_simple organisation
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
FROM v_interessenbindung_simple interessenbindung
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
FROM v_mandat_simple mandat
INNER JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON mandat.person_id = zutrittsberechtigung.person_id
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
INNER JOIN v_interessenbindung_simple interessenbindung
  ON organisation_beziehung.organisation_id = interessenbindung.organisation_id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
ORDER BY beziehung, parlamentarier_name;

-- Parlamentarier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben.
-- Connector: organisation_id oder parlamentarier_id
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_beide` AS
SELECT 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as person_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, interessenbindung.bis,  interessenbindung.organisation_id, interessenbindung.freigabe_datum, parlamentarier.im_rat_bis, parlamentarier.im_rat_bis_unix
FROM v_interessenbindung_simple interessenbindung
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
UNION
SELECT 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.person_id, person.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von, LEAST(IFNULL(zutrittsberechtigung.bis, mandat.bis), IFNULL(mandat.bis, zutrittsberechtigung.bis)), mandat.organisation_id, mandat.freigabe_datum, parlamentarier.im_rat_bis, parlamentarier.im_rat_bis_unix
FROM v_zutrittsberechtigung_simple zutrittsberechtigung
INNER JOIN v_mandat_simple mandat
  ON mandat.person_id = zutrittsberechtigung.person_id
INNER JOIN v_person person
  ON person.id = zutrittsberechtigung.person_id
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id;

-- Parlamentarier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben.
-- Connector: connector_organisation_id oder parlamentarier_id
-- Reverse Beziehung
-- ('arbeitet fuer','mitglied von','tochtergesellschaft von','partner von','beteiligt an')
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_beide_indirekt` AS
SELECT 'direkt' as beziehung, organisation_parlamentarier.verbindung, organisation_parlamentarier.parlamentarier_id, organisation_parlamentarier.parlamentarier_name, organisation_parlamentarier.ratstyp, organisation_parlamentarier.kanton, organisation_parlamentarier.partei_id, organisation_parlamentarier.partei, organisation_parlamentarier.kommissionen, organisation_parlamentarier.parlament_biografie_id, organisation_parlamentarier.person_id, organisation_parlamentarier.zutrittsberechtigter, organisation_parlamentarier.art, organisation_parlamentarier.von, organisation_parlamentarier.bis, NULL as zwischen_organisation_id, organisation_parlamentarier.organisation_id as connector_organisation_id, organisation_parlamentarier.freigabe_datum, organisation_parlamentarier.im_rat_bis, organisation_parlamentarier.im_rat_bis_unix
FROM v_organisation_parlamentarier_beide organisation_parlamentarier
UNION
SELECT CONCAT('indirekt: ', organisation_beziehung.art) as beziehung, 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as person_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, LEAST(IFNULL(interessenbindung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y')), IFNULL(organisation_beziehung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y'))), organisation_beziehung.organisation_id as zwischen_organisation_id, organisation_beziehung.ziel_organisation_id as connector_organisation_id, organisation_beziehung.freigabe_datum, parlamentarier.im_rat_bis, parlamentarier.im_rat_bis_unix
FROM v_parlamentarier parlamentarier
INNER JOIN v_interessenbindung_simple interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON organisation_beziehung.art IN ('arbeitet fuer', 'tochtergesellschaft von') AND organisation_beziehung.organisation_id = interessenbindung.organisation_id
UNION
SELECT CONCAT('indirekt: ', organisation_beziehung.art) as beziehung, 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.person_id as person_id, person.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von, LEAST(IFNULL(zutrittsberechtigung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y')), IFNULL(mandat.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y')), IFNULL(organisation_beziehung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y'))), organisation_beziehung.organisation_id as zwischen_organisation_id, organisation_beziehung.ziel_organisation_id as connector_organisation_id, organisation_beziehung.freigabe_datum, parlamentarier.im_rat_bis, parlamentarier.im_rat_bis_unix
FROM v_parlamentarier parlamentarier
INNER JOIN v_zutrittsberechtigung_simple zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
INNER JOIN v_mandat mandat
  ON mandat.person_id = zutrittsberechtigung.person_id
INNER JOIN v_person person
  ON person.id = zutrittsberechtigung.person_id
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON organisation_beziehung.art IN ('arbeitet fuer', 'tochtergesellschaft von') AND organisation_beziehung.organisation_id = mandat.organisation_id
UNION
-- other direction of 'tochtergesellschaft von'
SELECT CONCAT('indirekt: ', organisation_beziehung.art, ', reverse') as beziehung, 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as person_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, LEAST(IFNULL(interessenbindung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y')), IFNULL(organisation_beziehung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y'))), organisation_beziehung.ziel_organisation_id as zwischen_organisation_id, organisation_beziehung.organisation_id as connector_organisation_id, organisation_beziehung.freigabe_datum, parlamentarier.im_rat_bis, parlamentarier.im_rat_bis_unix
FROM v_parlamentarier parlamentarier
INNER JOIN v_interessenbindung_simple interessenbindung
  ON interessenbindung.parlamentarier_id = parlamentarier.id
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON organisation_beziehung.art IN ('tochtergesellschaft von') AND organisation_beziehung.ziel_organisation_id = interessenbindung.organisation_id
UNION
SELECT CONCAT('indirekt: ', organisation_beziehung.art, ', reverse') as beziehung, 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.person_id as person_id, person.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von,
/* Workaround: Combine to bis dates into one, problem are NULL values, replace them with a date in the very future */
LEAST(IFNULL(zutrittsberechtigung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y')), IFNULL(mandat.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y')), IFNULL(organisation_beziehung.bis, STR_TO_DATE('31.12.2100','%d.%m.%Y'))), organisation_beziehung.ziel_organisation_id as zwischen_organisation_id, organisation_beziehung.organisation_id as connector_organisation_id, organisation_beziehung.freigabe_datum, parlamentarier.im_rat_bis, parlamentarier.im_rat_bis_unix
FROM v_parlamentarier parlamentarier
INNER JOIN v_zutrittsberechtigung_simple zutrittsberechtigung
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
INNER JOIN v_mandat mandat
  ON mandat.person_id = zutrittsberechtigung.person_id
INNER JOIN v_person person
  ON person.id = zutrittsberechtigung.person_id
INNER JOIN v_organisation_beziehung organisation_beziehung
  ON organisation_beziehung.art IN ('tochtergesellschaft von') AND organisation_beziehung.ziel_organisation_id = mandat.organisation_id
  ;

-- SELECT * FROM v_organisation_parlamentarier_beide_indirekt WHERE connector_organisation_id = 19;

-- Authorisieurngsemail Interessenbindung für Parlamentarier
-- Connector: interessenbindung.parlamentarier_id
-- CREATE OR REPLACE VIEW `v_interessenbindung_authorisierungs_email` AS
-- SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, organisation.anzeige_name as organisation_name
-- , `organisation`.`anzeige_name_de` as `organisation_name_de`
-- , `organisation`.`anzeige_name_fr` as `organisation_name_fr`
-- , IFNULL(organisation.rechtsform,'') rechtsform, IFNULL(organisation.ort,'') ort, interessenbindung.art, interessenbindung.beschreibung
-- FROM v_interessenbindung_simple interessenbindung
-- INNER JOIN v_organisation organisation
--   ON interessenbindung.organisation_id = organisation.id
-- INNER JOIN v_parlamentarier_simple parlamentarier
--   ON interessenbindung.parlamentarier_id = parlamentarier.id
-- ORDER BY organisation.anzeige_name;

-- Authorisierungsemail Interessenbindung für Zutrittsberechtigte
-- Connector: interessenbindung.parlamentarier_id
-- CREATE OR REPLACE VIEW `v_zutrittsberechtigung_authorisierungs_email` AS
-- SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, zutrittsberechtigung.name zutrittsberechtigung_name, zutrittsberechtigung.funktion
-- FROM v_zutrittsberechtigung_simple zutrittsberechtigung
-- INNER JOIN v_parlamentarier_simple parlamentarier
--   ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
-- GROUP BY parlamentarier.id;

-- Authorisierungsemail Interessenbindung für Parlamentarier
-- Connector: interessenbindung.parlamentarier_id
-- CREATE OR REPLACE VIEW `v_interessenbindung_authorisierungs_email` AS
-- SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, organisation.anzeige_name as organisation_name
-- , `organisation`.`anzeige_name_de` as `organisation_name_de`
-- , `organisation`.`anzeige_name_fr` as `organisation_name_fr`
-- , IFNULL(organisation.rechtsform,'') rechtsform, IFNULL(organisation.ort,'') ort, interessenbindung.art, interessenbindung.beschreibung
-- FROM v_interessenbindung_simple interessenbindung
-- INNER JOIN v_organisation_simple organisation
--   ON interessenbindung.organisation_id = organisation.id
-- INNER JOIN v_parlamentarier parlamentarier
--   ON interessenbindung.parlamentarier_id = parlamentarier.id
-- GROUP BY parlamentarier.id
-- ORDER BY organisation.anzeige_name;

-- Authorisieurngsemail Interessenbindung für Parlamentarier
-- Connector: parlamentarier_id
-- DEPRECATED
-- CREATE OR REPLACE VIEW `v_parlamentarier_authorisierungs_email` AS
-- SELECT parlamentarier.id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.email,
-- CONCAT(
--   CASE parlamentarier.geschlecht
--     WHEN 'M' THEN CONCAT('<p>Sehr geehrter Herr ', parlamentarier.nachname, '</p>')
--     WHEN 'F' THEN CONCAT('<p>Sehr geehrte Frau ', parlamentarier.nachname, '</p>')
--     ELSE CONCAT('<p>Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '</p>')
--   END,
--   '<p>[Einleitung]</p>',
--   '<p>Ihre <b>Interessenbindungen</b>:</p>',
--   '<ul>',
--   GROUP_CONCAT(DISTINCT
--     CONCAT('<li>', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', IFNULL(interessenbindung.beschreibung, ''))
--     ORDER BY organisation.anzeige_name
--     SEPARATOR ' '
--   ),
--   '</ul>',
--   '<p>Ihre <b>Gäste</b>:</p>',
--   '<ul>',
--   GROUP_CONCAT(DISTINCT
--     CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion)
--     ORDER BY zutrittsberechtigung.name
--     SEPARATOR ' '
--   ),
--   '</ul>',
--   '<p><b>Mandate</b> der Gäste:</p>',
--   '<ul>',
--   GROUP_CONCAT(DISTINCT
--     CONCAT('<li>', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion,
--     IF (organisation2.id IS NOT NULL,
--       CONCAT(', ',
--         organisation2.anzeige_name
--         , IF(organisation2.rechtsform IS NULL OR TRIM(organisation2.rechtsform) = '', '', CONCAT(', ', organisation2.rechtsform)), IF(organisation2.ort IS NULL OR TRIM(organisation2.ort) = '', '', CONCAT(', ', organisation2.ort)), ', '
--         , IFNULL(mandat.art, ''), ', ', IFNULL(mandat.beschreibung, '')
--       ),
--       '')
--     )
--     ORDER BY zutrittsberechtigung.name, organisation2.anzeige_name
--     SEPARATOR ' '
--   ),
--   '</ul>',
--   '<p>Freundliche Grüsse<br></p>'
-- ) email_text_html,
-- UTF8_URLENCODE(CONCAT(
--   CASE parlamentarier.geschlecht
--     WHEN 'M' THEN CONCAT('Sehr geehrter Herr ', parlamentarier.nachname, '\r\n')
--     WHEN 'F' THEN CONCAT('Sehr geehrte Frau ', parlamentarier.nachname, '\r\n')
--     ELSE CONCAT('Sehr geehrte(r) Herr/Frau ', parlamentarier.nachname, '\r\n')
--   END,
--   '\r\n[Ersetze Text mit HTML-Vorlage]\r\n',
--   'Ihre Interessenbindungen:\r\n',
--   GROUP_CONCAT(DISTINCT
--     CONCAT('* ', organisation.anzeige_name, IF(organisation.rechtsform IS NULL OR TRIM(organisation.rechtsform) = '', '', CONCAT(', ', organisation.rechtsform)), IF(organisation.ort IS NULL OR TRIM(organisation.ort) = '', '', CONCAT(', ', organisation.ort)), ', ', interessenbindung.art, ', ', IFNULL(interessenbindung.beschreibung, ''), '\r\n')
--     ORDER BY organisation.anzeige_name
--     SEPARATOR ' '
--   ),
--   '\r\nIhre Gäste:\r\n',
--   GROUP_CONCAT(DISTINCT
--     CONCAT('* ', zutrittsberechtigung.name, ', ', zutrittsberechtigung.funktion, '\r\n')
--     ORDER BY organisation.anzeige_name
--     SEPARATOR ' '
--   ),
--   '\r\nMit freundlichen Grüssen,\r\n'
-- )) email_text_for_url
-- FROM v_parlamentarier_simple parlamentarier
-- LEFT JOIN v_interessenbindung_simple interessenbindung
--   ON interessenbindung.parlamentarier_id = parlamentarier.id AND interessenbindung.bis IS NULL
-- LEFT JOIN v_organisation_simple organisation
--   ON interessenbindung.organisation_id = organisation.id
-- LEFT JOIN v_zutrittsberechtigung_simple zutrittsberechtigung
--   ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id AND zutrittsberechtigung.bis IS NULL
-- LEFT JOIN v_mandat mandat
--   ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id AND mandat.bis IS NULL
-- LEFT JOIN v_organisation organisation2
--   ON mandat.organisation_id = organisation2.id
-- WHERE
--   parlamentarier.im_rat_bis IS NULL
-- GROUP BY parlamentarier.id;

-- search table

CREATE OR REPLACE VIEW `v_search_table_raw`
AS
  SELECT id, 'parlamentarier' as table_name, 'parlamentarier' as page, -20 + IF(im_rat_bis < NOW(), 5, 0) as table_weight,
  CONCAT_WS(', ', anzeige_name, CONCAT(IF(im_rat_bis < NOW(), 'Ex-', ''), rat_de), partei_de, kanton) as name_de,
  CONCAT_WS(', ', anzeige_name, CONCAT(IF(im_rat_bis < NOW(), 'Ex-', ''), rat_fr), partei_fr, kanton) as name_fr,
  CONCAT_WS(' ', nachname, vorname, CONCAT(nachname, ', ', vorname), zweiter_vorname, nachname, LEFT(vorname, 25), LEFT(zweiter_vorname, 1), LEFT(nachname, 27)) as search_keywords_de,
  CONCAT_WS(' ', nachname, vorname, CONCAT(nachname, ', ', vorname), zweiter_vorname, nachname, LEFT(vorname, 25), LEFT(zweiter_vorname, 1), LEFT(nachname, 27)) as search_keywords_fr,
  freigabe_datum, im_rat_bis as bis, -lobbyfaktor as weight, NOW() AS `refreshed_date` FROM v_parlamentarier
   UNION
  SELECT id, 'zutrittsberechtigung' as table_name, 'zutrittsberechtigter' as page, -15 as table_weight,
  anzeige_name as name_de,
  anzeige_name as name_fr,
  CONCAT_WS(' ', nachname, vorname, CONCAT(nachname, ', ', vorname), zweiter_vorname, nachname, LEFT(vorname, 25), LEFT(zweiter_vorname, 1), LEFT(nachname, 27)) as search_keywords_de,
  CONCAT_WS(' ', nachname, vorname, CONCAT(nachname, ', ', vorname), zweiter_vorname, nachname, LEFT(vorname, 25), LEFT(zweiter_vorname, 1), LEFT(nachname, 27)) as search_keywords_fr,
  freigabe_datum,
  /*Fix duplicate zutrittsberechtiung due to historization http://stackoverflow.com/questions/19763806/mysql-ignores-null-value-when-using-max*/
  IF(MAX(bis IS NULL) = 0, MAX(bis), NULL) AS bis,
  lobbyfaktor as weight, NOW() AS `refreshed_date` FROM v_zutrittsberechtigung
  -- Quick fix for duplicate zutrittsberechtiung due to historization
  -- WHERE (bis IS NULL OR bis > NOW())
  GROUP BY id
   UNION
  SELECT id, 'branche' as table_name, 'branche' as page, -10 as table_weight, anzeige_name_de as name_de, anzeige_name_fr as name_fr, anzeige_name_de as search_keywords_de, anzeige_name_fr as search_keywords_fr, freigabe_datum, NULL as bis, 0 as weight, NOW() AS `refreshed_date` FROM v_branche
   UNION
  SELECT id, 'interessengruppe' as table_name, 'lobbygruppe' as page, -5 as table_weight, anzeige_name_de as name_de, anzeige_name_fr as name_fr, CONCAT_WS('; ', anzeige_name_de, alias_namen) as search_keywords_de, CONCAT_WS('; ', anzeige_name_fr, alias_namen_fr) as search_keywords_fr, freigabe_datum, NULL as bis, 0 as weight, NOW() AS `refreshed_date` FROM v_interessengruppe
   UNION
  SELECT id, 'kommission' as table_name, 'kommission' as page, 0 as table_weight, anzeige_name_de as name_de, anzeige_name_fr as name_fr, anzeige_name_de as search_keywords_de, anzeige_name_fr as search_keywords_fr, freigabe_datum, NULL as bis, 0 as weight, NOW() AS `refreshed_date` FROM v_kommission
   UNION
  SELECT id, 'organisation' as table_name, 'organisation' as page, 15 as table_weight, anzeige_name_de as name_de, IFNULL(anzeige_name_fr, anzeige_name_de) as name_fr, CONCAT_WS('; ', anzeige_name_de, abkuerzung_de, uid, alias_namen_de) as search_keywords_de, CONCAT_WS('; ', anzeige_name_fr, abkuerzung_fr, uid, alias_namen_fr, anzeige_name_de) as search_keywords_fr, freigabe_datum, NULL as bis, -lobbyeinfluss_index as weight, NOW() AS `refreshed_date` FROM v_organisation
   UNION
  SELECT id, 'partei' as table_name, 'partei' as page, 20 as table_weight, anzeige_name_de as name_de, anzeige_name_fr as name_fr, anzeige_name_de as search_keywords_de, anzeige_name_fr as search_keywords_fr, freigabe_datum, NULL as bis, 0 as weight, NOW() AS `refreshed_date` FROM v_partei
;

DROP TABLE IF EXISTS `mv_search_table`;
CREATE TABLE IF NOT EXISTS `mv_search_table`
ENGINE = InnoDB
DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
COMMENT='Materialzed view for parlamentarier, zutrittsberechtigung, branche, interessengruppe, kommission, organisation, partei'
AS SELECT * FROM v_search_table_raw;
ALTER TABLE `mv_search_table`
ADD PRIMARY KEY (`id`, `table_name`),
ADD KEY `idx_search_str_de_long` (freigabe_datum, bis, table_weight, weight, `search_keywords_de`(200)),
ADD KEY `idx_search_str_de_medium` (freigabe_datum, table_weight, weight, `search_keywords_de`(200)),
ADD KEY `idx_search_str_de_short` (table_weight, weight, `search_keywords_de`(200)),
ADD KEY `idx_search_str_fr_long` (freigabe_datum, bis, table_weight, weight, `search_keywords_fr`(200)),
ADD KEY `idx_search_str_fr_medium` (freigabe_datum, table_weight, weight, `search_keywords_fr`(200)),
ADD KEY `idx_search_str_fr_short` (table_weight, weight, `search_keywords_fr`(200)),
CHANGE `refreshed_date` `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

--	DROP TABLE IF EXISTS `mv_search_table_myisam`;
--	CREATE TABLE IF NOT EXISTS `mv_search_table_myisam`
--	ENGINE = MyISAM
--	DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
--	COMMENT='Materialzed view for v_search_table'
--	AS SELECT * FROM `v_search_table_raw`;
--	ALTER TABLE `mv_search_table_myisam`
--	ADD PRIMARY KEY (`id`, `table_name`),
--	ADD KEY `idx_search_str_long` (`name`, freigabe_datum, bis, weight),
--	ADD KEY `idx_search_str_medium` (`name`, freigabe_datum, weight),
--	ADD KEY `idx_search_str_short` (`name`, weight),
--  ADD FULLTEXT(`name`),
--	ADD `refreshed_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Materialized View aktualisiert am';

CREATE OR REPLACE VIEW `v_search_table` AS
SELECT * FROM `mv_search_table`;
