-- Public Views for usage with Lobbywatch data exports

CREATE OR REPLACE VIEW pv_branche AS
SELECT
branche.*,
branche.name as name_de,
branche.beschreibung as beschreibung_de,
branche.angaben as angaben_de,
branche.kommission_id as kommission1_id,
kommission.name as kommission1_name,
kommission.name as kommission1_name_de,
kommission.name_fr as kommission1_name_fr,
kommission.abkuerzung as kommission1_abkuerzung,
kommission.abkuerzung as kommission1_abkuerzung_de,
kommission.abkuerzung_fr as kommission1_abkuerzung_fr,
kommission2.name as kommission2_name,
kommission2.name as kommission2_name_de,
kommission2.name_fr as kommission2_name_fr,
kommission2.abkuerzung as kommission2_abkuerzung,
kommission2.abkuerzung as kommission2_abkuerzung_de,
kommission2.abkuerzung_fr as kommission2_abkuerzung_fr
FROM branche
LEFT JOIN kommission kommission
ON kommission.id = branche.kommission_id
LEFT JOIN kommission kommission2
ON kommission2.id = branche.kommission2_id;

CREATE OR REPLACE VIEW pv_interessengruppe AS
SELECT
interessengruppe.*,
branche.name_de as branche_name_de,
branche.name_fr as branche_name_fr,
branche.kommission_id as kommission_id,
branche.kommission_id as kommission1_id,
branche.kommission2_id as kommission2_id,
branche.kommission1_name as kommission1_name,
branche.kommission1_name_de as kommission1_name_de,
branche.kommission1_name_fr as kommission1_name_fr,
branche.kommission1_abkuerzung as kommission1_abkuerzung,
branche.kommission1_abkuerzung_de as kommission1_abkuerzung_de,
branche.kommission1_abkuerzung_fr as kommission1_abkuerzung_fr,
branche.kommission2_name as kommission2_name,
branche.kommission2_name_de as kommission2_name_de,
branche.kommission2_name_fr as kommission2_name_fr,
branche.kommission2_abkuerzung as kommission2_abkuerzung,
branche.kommission2_abkuerzung_de as kommission2_abkuerzung_de,
branche.kommission2_abkuerzung_fr as kommission2_abkuerzung_fr
FROM interessengruppe
LEFT JOIN pv_branche branche
ON branche.id = interessengruppe.branche_id;

CREATE OR REPLACE VIEW pv_organisation AS
SELECT
organisation.*,
interessengruppe1.branche_name_de as interessengruppe_branche_name_de,
interessengruppe1.branche_name_fr as interessengruppe_branche_name_fr,
interessengruppe1.branche_id as interessengruppe_branche_id,
interessengruppe1.kommission1_id as interessengruppe_branche_kommission1_id,
interessengruppe1.kommission1_abkuerzung as interessengruppe_branche_kommission1_abkuerzung,
interessengruppe1.kommission1_abkuerzung_de as interessengruppe_branche_kommission1_abkuerzung_de,
interessengruppe1.kommission1_abkuerzung_fr as interessengruppe_branche_kommission1_abkuerzung_fr,
interessengruppe1.kommission1_name as interessengruppe_branche_kommission1_name,
interessengruppe1.kommission1_name_de as interessengruppe_branche_kommission1_name_de,
interessengruppe1.kommission1_name_fr as interessengruppe_branche_kommission1_name_fr,
interessengruppe1.kommission2_id as interessengruppe_branche_kommission2_id,
interessengruppe1.kommission2_abkuerzung as interessengruppe_branche_kommission2_abkuerzung,
interessengruppe1.kommission2_abkuerzung_de as interessengruppe_branche_kommission2_abkuerzung_de,
interessengruppe1.kommission2_abkuerzung_fr as interessengruppe_branche_kommission2_abkuerzung_fr,
interessengruppe1.kommission2_name as interessengruppe_branche_kommission2_name,
interessengruppe1.kommission2_name_de as interessengruppe_branche_kommission2_name_de,
interessengruppe1.kommission2_name_fr as interessengruppe_branche_kommission2_name_fr,
interessengruppe1.id as interessengruppe1_id,
interessengruppe1.branche_name_de as interessengruppe1_branche_name_de,
interessengruppe1.branche_name_fr as interessengruppe1_branche_name_fr,
interessengruppe1.branche_id as interessengruppe1_branche_id,
REPLACE(REPLACE(IFNULL(interessengruppe1.kommission1_abkuerzung, interessengruppe1.kommission2_abkuerzung), '-NR', ''), '-SR', '') as interessengruppe1_branche_kommission_abkuerzung,
REPLACE(REPLACE(IFNULL(interessengruppe1.kommission1_abkuerzung_de, interessengruppe1.kommission2_abkuerzung_de), '-NR', ''), '-SR', '') as interessengruppe1_branche_kommission_abkuerzung_de,
REPLACE(REPLACE(IFNULL(interessengruppe1.kommission1_abkuerzung_fr, interessengruppe1.kommission2_abkuerzung_fr), '-CN', ''), '-CE', '') as interessengruppe1_branche_kommission_abkuerzung_fr,
CONCAT_WS(' / ', interessengruppe1.kommission1_abkuerzung, interessengruppe1.kommission2_abkuerzung) as interessengruppe1_branche_kommissionen_abkuerzung,
CONCAT_WS(' / ', interessengruppe1.kommission1_abkuerzung_de, interessengruppe1.kommission2_abkuerzung_de) as interessengruppe1_branche_kommissionen_abkuerzung_de,
CONCAT_WS(' / ', interessengruppe1.kommission1_abkuerzung_fr, interessengruppe1.kommission2_abkuerzung_fr) as interessengruppe1_branche_kommissionen_abkuerzung_fr,
interessengruppe1.kommission1_id as interessengruppe1_branche_kommission1_id,
interessengruppe1.kommission1_abkuerzung as interessengruppe1_branche_kommission1_abkuerzung,
interessengruppe1.kommission1_abkuerzung_de as interessengruppe1_branche_kommission1_abkuerzung_de,
interessengruppe1.kommission1_abkuerzung_fr as interessengruppe1_branche_kommission1_abkuerzung_fr,
interessengruppe1.kommission1_name as interessengruppe1_branche_kommission1_name,
interessengruppe1.kommission1_name_de as interessengruppe1_branche_kommission1_name_de,
interessengruppe1.kommission1_name_fr as interessengruppe1_branche_kommission1_name_fr,
interessengruppe1.kommission2_id as interessengruppe1_branche_kommission2_id,
interessengruppe1.kommission2_abkuerzung as interessengruppe1_branche_kommission2_abkuerzung,
interessengruppe1.kommission2_abkuerzung_de as interessengruppe1_branche_kommission2_abkuerzung_de,
interessengruppe1.kommission2_abkuerzung_fr as interessengruppe1_branche_kommission2_abkuerzung_fr,
interessengruppe1.kommission2_name as interessengruppe1_branche_kommission2_name,
interessengruppe1.kommission2_name_de as interessengruppe1_branche_kommission2_name_de,
interessengruppe1.kommission2_name_fr as interessengruppe1_branche_kommission2_name_fr,
interessengruppe2.branche_name_de as interessengruppe2_branche_name_de,
interessengruppe2.branche_name_fr as interessengruppe2_branche_name_fr,
interessengruppe2.branche_id as interessengruppe2_branche_id,
REPLACE(REPLACE(IFNULL(interessengruppe2.kommission1_abkuerzung, interessengruppe2.kommission2_abkuerzung), '-NR', ''), '-SR', '') as interessengruppe2_branche_kommission_abkuerzung,
REPLACE(REPLACE(IFNULL(interessengruppe2.kommission1_abkuerzung_de, interessengruppe2.kommission2_abkuerzung_de), '-NR', ''), '-SR', '') as interessengruppe2_branche_kommission_abkuerzung_de,
REPLACE(REPLACE(IFNULL(interessengruppe2.kommission1_abkuerzung_fr, interessengruppe2.kommission2_abkuerzung_fr), '-CN', ''), '-CE', '') as interessengruppe2_branche_kommission_abkuerzung_fr,
CONCAT_WS(' / ', interessengruppe2.kommission1_abkuerzung, interessengruppe2.kommission2_abkuerzung) as interessengruppe2_branche_kommissionen_abkuerzung,
CONCAT_WS(' / ', interessengruppe2.kommission1_abkuerzung_de, interessengruppe2.kommission2_abkuerzung_de) as interessengruppe2_branche_kommissionen_abkuerzung_de,
CONCAT_WS(' / ', interessengruppe2.kommission1_abkuerzung_fr, interessengruppe2.kommission2_abkuerzung_fr) as interessengruppe2_branche_kommissionen_abkuerzung_fr,
interessengruppe2.kommission1_id as interessengruppe2_branche_kommission1_id,
interessengruppe2.kommission1_abkuerzung as interessengruppe2_branche_kommission1_abkuerzung,
interessengruppe2.kommission1_abkuerzung_de as interessengruppe2_branche_kommission1_abkuerzung_de,
interessengruppe2.kommission1_abkuerzung_fr as interessengruppe2_branche_kommission1_abkuerzung_fr,
interessengruppe2.kommission1_name as interessengruppe2_branche_kommission1_name,
interessengruppe2.kommission1_name_de as interessengruppe2_branche_kommission1_name_de,
interessengruppe2.kommission1_name_fr as interessengruppe2_branche_kommission1_name_fr,
interessengruppe2.kommission2_id as interessengruppe2_branche_kommission2_id,
interessengruppe2.kommission2_abkuerzung as interessengruppe2_branche_kommission2_abkuerzung,
interessengruppe2.kommission2_abkuerzung_de as interessengruppe2_branche_kommission2_abkuerzung_de,
interessengruppe2.kommission2_abkuerzung_fr as interessengruppe2_branche_kommission2_abkuerzung_fr,
interessengruppe2.kommission2_name as interessengruppe2_branche_kommission2_name,
interessengruppe2.kommission2_name_de as interessengruppe2_branche_kommission2_name_de,
interessengruppe2.kommission2_name_fr as interessengruppe2_branche_kommission2_name_fr,
interessengruppe3.branche_name_de as interessengruppe3_branche_name_de,
interessengruppe3.branche_name_fr as interessengruppe3_branche_name_fr,
interessengruppe3.branche_id as interessengruppe3_branche_id,
REPLACE(REPLACE(IFNULL(interessengruppe3.kommission1_abkuerzung, interessengruppe3.kommission2_abkuerzung), '-NR', ''), '-SR', '') as interessengruppe3_branche_kommission_abkuerzung,
REPLACE(REPLACE(IFNULL(interessengruppe3.kommission1_abkuerzung_de, interessengruppe3.kommission2_abkuerzung_de), '-NR', ''), '-SR', '') as interessengruppe3_branche_kommission_abkuerzung_de,
REPLACE(REPLACE(IFNULL(interessengruppe3.kommission1_abkuerzung_fr, interessengruppe3.kommission2_abkuerzung_fr), '-CN', ''), '-CE', '') as interessengruppe3_branche_kommission_abkuerzung_fr,
CONCAT_WS(' / ', interessengruppe3.kommission1_abkuerzung, interessengruppe3.kommission2_abkuerzung) as interessengruppe3_branche_kommissionen_abkuerzung,
CONCAT_WS(' / ', interessengruppe3.kommission1_abkuerzung_de, interessengruppe3.kommission2_abkuerzung_de) as interessengruppe3_branche_kommissionen_abkuerzung_de,
CONCAT_WS(' / ', interessengruppe3.kommission1_abkuerzung_fr, interessengruppe3.kommission2_abkuerzung_fr) as interessengruppe3_branche_kommissionen_abkuerzung_fr,
interessengruppe3.kommission1_id as interessengruppe3_branche_kommission1_id,
interessengruppe3.kommission1_abkuerzung as interessengruppe3_branche_kommission1_abkuerzung,
interessengruppe3.kommission1_abkuerzung_de as interessengruppe3_branche_kommission1_abkuerzung_de,
interessengruppe3.kommission1_abkuerzung_fr as interessengruppe3_branche_kommission1_abkuerzung_fr,
interessengruppe3.kommission1_name as interessengruppe3_branche_kommission1_name,
interessengruppe3.kommission1_name_de as interessengruppe3_branche_kommission1_name_de,
interessengruppe3.kommission1_name_fr as interessengruppe3_branche_kommission1_name_fr,
interessengruppe3.kommission2_id as interessengruppe3_branche_kommission2_id,
interessengruppe3.kommission2_abkuerzung as interessengruppe3_branche_kommission2_abkuerzung,
interessengruppe3.kommission2_abkuerzung_de as interessengruppe3_branche_kommission2_abkuerzung_de,
interessengruppe3.kommission2_abkuerzung_fr as interessengruppe3_branche_kommission2_abkuerzung_fr,
interessengruppe3.kommission2_name as interessengruppe3_branche_kommission2_name,
interessengruppe3.kommission2_name_de as interessengruppe3_branche_kommission2_name_de,
interessengruppe3.kommission2_name_fr as interessengruppe3_branche_kommission2_name_fr
FROM organisation
LEFT JOIN pv_interessengruppe interessengruppe1
ON interessengruppe1.id = organisation.interessengruppe_id
LEFT JOIN pv_interessengruppe interessengruppe2
ON interessengruppe2.id = organisation.interessengruppe2_id
LEFT JOIN pv_interessengruppe interessengruppe3
ON interessengruppe3.id = organisation.interessengruppe3_id;

CREATE OR REPLACE VIEW pv_interessenbindung_wirksamkeit AS
SELECT
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
    AND branche.id IN (organisation.interessengruppe_branche_id, organisation.interessengruppe2_branche_id, organisation.interessengruppe3_branche_id)), 'hoch',
  IF(organisation.vernehmlassung IN ('immer', 'punktuell')
    AND interessenbindung.art IN ('geschaeftsfuehrend','vorstand','taetig','beirat','finanziell'), 'mittel', 'tief')
) wirksamkeit,
parlamentarier.im_rat_seit as parlamentarier_im_rat_seit
FROM interessenbindung
INNER JOIN pv_organisation organisation
ON interessenbindung.organisation_id = organisation.id
INNER JOIN parlamentarier
ON interessenbindung.parlamentarier_id = parlamentarier.id;
