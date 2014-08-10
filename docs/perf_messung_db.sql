SELECT tables.* FROM (
SELECT 'parlamentarier' as page, CONCAT_WS(', ', anzeige_name, rat, partei, kanton) as name, id FROM v_parlamentarier WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'zutrittsberechtigter' as page, anzeige_name as name, id FROM v_zutrittsberechtigung_simple WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'organisation' as page, anzeige_name as name, id FROM v_organisation_simple WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'branche' as page, anzeige_name as name, id FROM v_branche WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'lobbygruppe' as page, anzeige_name as name, id FROM v_interessengruppe WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'kommission' as page, anzeige_name as name, id FROM v_kommission WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'partei' as page, anzeige_name as name, id FROM v_partei WHERE anzeige_name LIKE '%Haus%') tables
LIMIT 20;

-- 0.0042s
-- 0.0099s
-- 0.0064s


SELECT tables.* FROM (
SELECT 'parlamentarier' as page, CONCAT_WS(', ', anzeige_name, rat, partei, kanton) as name, id FROM mv_parlamentarier_medium_myisam WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'zutrittsberechtigter' as page, anzeige_name as name, id FROM mv_zutrittsberechtigung_myisam WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'organisation' as page, anzeige_name as name, id FROM mv_organisation_medium_myisam WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'branche' as page, anzeige_name as name, id FROM v_branche WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'lobbygruppe' as page, anzeige_name as name, id FROM v_interessengruppe WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'kommission' as page, anzeige_name as name, id FROM v_kommission WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'partei' as page, anzeige_name as name, id FROM v_partei WHERE anzeige_name LIKE '%Haus%') tables
LIMIT 20;

-- 0.0107s
-- 0.0100s
-- 0.0063s


SELECT tables.* FROM (
SELECT 'parlamentarier' as page, CONCAT_WS(', ', anzeige_name, rat, partei, kanton) as name, id FROM mv_parlamentarier_myisam WHERE MATCH(`anzeige_name`) AGAINST('Haus')
 UNION
SELECT 'zutrittsberechtigter' as page, anzeige_name as name, id FROM mv_zutrittsberechtigung_myisam WHERE MATCH(`anzeige_name`) AGAINST('Haus')
 UNION
SELECT 'organisation' as page, anzeige_name as name, id FROM mv_organisation_myisam WHERE MATCH(`anzeige_name`) AGAINST('Haus')
 UNION
SELECT 'branche' as page, anzeige_name as name, id FROM v_branche WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'lobbygruppe' as page, anzeige_name as name, id FROM v_interessengruppe WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'kommission' as page, anzeige_name as name, id FROM v_kommission WHERE anzeige_name LIKE '%Haus%'
 UNION
SELECT 'partei' as page, anzeige_name as name, id FROM v_partei WHERE anzeige_name LIKE '%Haus%') tables
LIMIT 20;

SELECT id, page, name
FROM v_search_table
WHERE
name LIKE '%Haus%' AND freigabe_datum < NOW()
ORDER BY table_weight, weight
LIMIT 20;

-- 0.0023
-- 0.0032
