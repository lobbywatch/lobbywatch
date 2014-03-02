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
CREATE OR REPLACE VIEW `v_last_updated_zutrittsberechtigung_anhang` AS
  (SELECT
  'zutrittsberechtigung_anhang' table_name,
  'Zutrittsberechtigteranhang' name,
  (select count(*) from `zutrittsberechtigung_anhang`) anzahl_eintraege,
  t.`updated_visa` AS last_visa,
  t.`updated_date` last_updated,
  t.id last_updated_id
  FROM
  `zutrittsberechtigung_anhang` t
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
SELECT * FROM v_last_updated_zutrittsberechtigung_anhang
UNION
SELECT * FROM v_last_updated_partei
UNION
SELECT * FROM v_last_updated_fraktion
UNION
SELECT * FROM v_last_updated_zutrittsberechtigung;

CREATE OR REPLACE VIEW `v_last_updated_tables` AS
SELECT * FROM `v_last_updated_tables_unordered`
ORDER BY last_updated DESC;

-- VIEWS

CREATE OR REPLACE VIEW `v_country` AS SELECT c.name_de as anzeige_name, c.* FROM `country` c;

CREATE OR REPLACE VIEW `v_interessenraum` AS SELECT r.name as anzeige_name, r.* FROM `interessenraum` r ORDER BY r.`reihenfolge` ASC;

CREATE OR REPLACE VIEW `v_kommission` AS SELECT CONCAT(t.name, ' (', t.abkuerzung, ')') AS anzeige_name, t.* FROM `kommission` t;

CREATE OR REPLACE VIEW `v_partei` AS SELECT CONCAT(t.name, ' (', t.abkuerzung, ')') AS anzeige_name, t.* FROM `partei` t;

CREATE OR REPLACE VIEW `v_fraktion` AS SELECT CONCAT_WS(', ', t.abkuerzung, t.name) AS anzeige_name, t.* FROM `fraktion` t;

CREATE OR REPLACE VIEW `v_interessenbindung` AS SELECT t.* FROM `interessenbindung` t;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung` AS
SELECT CONCAT(z.nachname, ', ', z.vorname) AS anzeige_name, CONCAT(z.vorname, ' ', z.nachname) AS name, z.*, partei.abkuerzung AS partei
FROM `zutrittsberechtigung` z
LEFT JOIN v_partei partei ON z.partei_id=partei.id;

CREATE OR REPLACE VIEW `v_organisation` AS SELECT CONCAT_WS('; ', t.name_de , t.name_fr, t.name_it) AS anzeige_name, CONCAT_WS('; ', t.name_de , t.name_fr, t.name_it) AS name, t.* FROM `organisation` t;

CREATE OR REPLACE VIEW `v_interessengruppe` AS SELECT CONCAT(t.name) AS anzeige_name, t.* FROM `interessengruppe` t;

CREATE OR REPLACE VIEW `v_branche` AS SELECT CONCAT(t.name) AS anzeige_name,  t.* FROM `branche` t;

CREATE OR REPLACE VIEW `v_mandat` AS SELECT t.* FROM `mandat` t;

CREATE OR REPLACE VIEW `v_in_kommission` AS SELECT t.*, parlamentarier.ratstyp, parlamentarier.partei_id, parlamentarier.fraktion_id, parlamentarier.kanton FROM `in_kommission` t INNER JOIN parlamentarier ON t.parlamentarier_id = parlamentarier.id;

CREATE OR REPLACE VIEW `v_organisation_beziehung` AS SELECT t.* FROM `organisation_beziehung` t;

CREATE OR REPLACE VIEW `v_parlamentarier_anhang` AS SELECT t.parlamentarier_id as parlamentarier_id2, t.* FROM `parlamentarier_anhang` t;

CREATE OR REPLACE VIEW `v_zutrittsberechtigung_anhang` AS SELECT t.zutrittsberechtigung_id as zutrittsberechtigung_id2, t.* FROM `zutrittsberechtigung_anhang` t;

CREATE OR REPLACE VIEW `v_user` AS SELECT CONCAT_WS(' ', u.vorname, u.nachname ) as anzeige_name, u.* FROM `user` u;

CREATE OR REPLACE VIEW `v_user_permission` AS SELECT t.* FROM `user_permission` t;

CREATE OR REPLACE VIEW `v_mil_grad` AS SELECT t.* FROM `mil_grad` t ORDER BY `ranghoehe` ASC;

CREATE OR REPLACE VIEW `v_parlamentarier` AS
SELECT CONCAT(p.nachname, ', ', p.vorname) AS anzeige_name, CONCAT(p.vorname, ' ', p.nachname) AS name, p.*, GROUP_CONCAT(DISTINCT CONCAT(k.name, '(', k.abkuerzung, ')') ORDER BY k.abkuerzung SEPARATOR ', ') kommissionen_namen, GROUP_CONCAT(DISTINCT k.abkuerzung ORDER BY k.abkuerzung SEPARATOR ', ') kommissionen_abkuerzung, partei.abkuerzung AS partei, fraktion.abkuerzung AS fraktion, mil_grad.name as militaerischer_grad
FROM `parlamentarier` p
LEFT JOIN v_in_kommission ik ON p.id = ik.parlamentarier_id AND ik.bis IS NULL LEFT JOIN v_kommission k ON ik.kommission_id=k.id
LEFT JOIN v_partei partei ON p.partei_id=partei.id
LEFT JOIN v_fraktion fraktion ON p.fraktion_id=fraktion.id
LEFT JOIN v_mil_grad mil_grad ON p.militaerischer_grad_id=mil_grad.id
GROUP BY p.id;



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
SELECT parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.partei, in_kommission.*
FROM v_in_kommission in_kommission
INNER JOIN v_parlamentarier parlamentarier
  ON in_kommission.parlamentarier_id = parlamentarier.id
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

-- Mandate einer Zutrittsberechtigung (INNER JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mandate` AS
SELECT zutrittsberechtigung.parlamentarier_id, organisation.anzeige_name as organisation_name, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion, mandat.*
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_mandat mandat
  ON zutrittsberechtigung.id = mandat.zutrittsberechtigung_id
INNER JOIN v_organisation organisation
  ON mandat.organisation_id = organisation.id
ORDER BY organisation.anzeige_name;

-- Mandate einer Zutrittsberechtigung (LFET JOIN)
-- Connector: zutrittsberechtigung.parlamentarier_id
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_mit_mandaten` AS
SELECT organisation.anzeige_name as organisation_name, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion, zutrittsberechtigung.parlamentarier_id, mandat.*
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
SELECT 'indirekt' as beziehung, organisation.name as organisation_name, zutrittsberechtigung.anzeige_name as zutrittsberechtigung_name, zutrittsberechtigung.funktion, zutrittsberechtigung.parlamentarier_id, mandat.*
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

-- Parlamenterier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben.
-- Connector: organisation_id oder parlamentarier_id
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_beide` AS
SELECT 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as zutrittsberechtigung_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, interessenbindung.bis,  interessenbindung.organisation_id
FROM v_interessenbindung interessenbindung
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
UNION
SELECT 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.id as zutrittsberechtigung_id, zutrittsberechtigung.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von, mandat.bis, mandat.organisation_id
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_mandat mandat
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id;

-- Parlamenterier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben.
-- Connector: connector_organisation_id oder parlamentarier_id
-- Reverse Beziehung
CREATE OR REPLACE VIEW `v_organisation_parlamentarier_beide_indirekt` AS
SELECT 'direkt' as beziehung, organisation_parlamentarier.verbindung, organisation_parlamentarier.parlamentarier_id, organisation_parlamentarier.parlamentarier_name, organisation_parlamentarier.ratstyp, organisation_parlamentarier.kanton, organisation_parlamentarier.partei_id, organisation_parlamentarier.partei, organisation_parlamentarier.kommissionen, organisation_parlamentarier.parlament_biografie_id, organisation_parlamentarier.zutrittsberechtigung_id, organisation_parlamentarier.zutrittsberechtigter, organisation_parlamentarier.art, organisation_parlamentarier.von, organisation_parlamentarier.bis, NULL as zwischenorganisation_id, organisation_parlamentarier.organisation_id as connector_organisation_id
FROM v_organisation_parlamentarier_beide organisation_parlamentarier
UNION
SELECT 'indirekt' as beziehung, 'interessenbindung' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, NULL as zutrittsberechtigung_id, NULL as zutrittsberechtigter, interessenbindung.art, interessenbindung.von, interessenbindung.bis, organisation_beziehung.organisation_id as zwischenorganisation_id, organisation_beziehung.ziel_organisation_id as connector_organisation_id
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_interessenbindung interessenbindung
  ON organisation_beziehung.organisation_id = interessenbindung.organisation_id
INNER JOIN v_parlamentarier parlamentarier
  ON interessenbindung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer'
UNION
SELECT 'indirekt' as beziehung, 'zutritt-mandat' as verbindung, parlamentarier.id as parlamentarier_id, parlamentarier.anzeige_name as parlamentarier_name, parlamentarier.ratstyp, parlamentarier.kanton, parlamentarier.partei_id, parlamentarier.partei, parlamentarier.kommissionen, parlamentarier.parlament_biografie_id, zutrittsberechtigung.id as zutrittsberechtigung_id, zutrittsberechtigung.anzeige_name as zutrittsberechtigter, mandat.art, mandat.von, mandat.bis, organisation_beziehung.organisation_id as zwischenorganisation_id, organisation_beziehung.ziel_organisation_id as connector_organisation_id
FROM v_organisation_beziehung organisation_beziehung
INNER JOIN v_mandat mandat
  ON organisation_beziehung.organisation_id = mandat.organisation_id
INNER JOIN v_zutrittsberechtigung zutrittsberechtigung
  ON mandat.zutrittsberechtigung_id = zutrittsberechtigung.id
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
WHERE
  organisation_beziehung.art = 'arbeitet fuer';

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
CREATE OR REPLACE VIEW `v_zutrittsberechtigung_authorisierungs_email` AS
SELECT parlamentarier.name as parlamentarier_name, IFNULL(parlamentarier.geschlecht, '') geschlecht, zutrittsberechtigung.name zutrittsberechtigung_name, zutrittsberechtigung.funktion
FROM v_zutrittsberechtigung zutrittsberechtigung
INNER JOIN v_parlamentarier parlamentarier
  ON zutrittsberechtigung.parlamentarier_id = parlamentarier.id
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
