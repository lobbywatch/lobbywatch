<?php

  require_once dirname(__FILE__) . "/../../settings/settings.php";
  require_once dirname(__FILE__) . "/../../common/utils.php";

    $username = $db_connection['reader_username'];
    $password = $db_connection['reader_password'];
    $host = $db_connection['server'];
    $database = $db_connection['database'];

  $optionen = array (
    PDO::ATTR_PERSISTENT => true
  );

  $db = new PDO ( 'mysql:host=' . $host .';dbname=' . $database . ';charset=utf8mb4', $username, $password, $optionen );

  $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

   $option = urldecode($_GET["option"]);
  //    $id = urldecode($_GET["id"]);

   $cmd = "";
   $color_map = array();

   if ($option == "kommission") {
      $kommission_id = (int) @urldecode($_GET["id"]);
      $kommission_id = !isset($kommission_id) || !is_int($kommission_id) || $kommission_id == 0 ? 1 : $kommission_id;
      $cmd = "select 'parlamentarier' as type, count(*) as value, 'nicht bearbeitet' as label, null as color
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id =  $kommission_id) and
         p.eingabe_abgeschlossen_datum is null and
         p.kontrolliert_datum is null and
         p.autorisierung_verschickt_datum is null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
         and p.bis is null
      union
      select 'parlamentarier' as type, count(*) as value, 'Erfasst' as label, null as color
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id =  $kommission_id) and
         p.eingabe_abgeschlossen_datum is not null and
         p.kontrolliert_datum is null and
         p.autorisierung_verschickt_datum is null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
         and p.bis is null
      union
      select 'parlamentarier' as type, count(*) as value, 'Kontrolliert' as label, null as color
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id =  $kommission_id) and
         p.kontrolliert_datum is not null and
         p.autorisierung_verschickt_datum is null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
         and p.bis is null
      union
      select 'parlamentarier' as type, count(*) as value, 'Verschickt' as label, null as color
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id =  $kommission_id) and
         p.autorisierung_verschickt_datum is not null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
         and p.bis is null
      union
      select 'parlamentarier' as type, count(*) as value, 'Autorisiert' as label, null as color
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id =  $kommission_id) and
         p.autorisiert_datum is not null and
         p.freigabe_datum is null
         and p.bis is null
      union
      select 'parlamentarier' as type, count(*) as value, 'Freigegeben' as label, null as color
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id =  $kommission_id) and
         p.freigabe_datum is not null
         and p.bis is null
         ";

      /*
      nicht bearbeitet: #FFFFB1
      erfasst: #FFFF00
      Kontrolliert: #FFA500
      Verschickt: #0000FF
      Autorisiert: #ADD8E6
      Freigegeben: #019E59
      */

      $color_map["nicht bearbeitet"] = "#FFFFBB";
      $color_map["Erfasst"] = "#FFFF00";
      $color_map["Kontrolliert"] = "#FFA500";
      $color_map["Verschickt"] = "#0000FF";
      $color_map["Autorisiert"] = "#ADD8E6";
      $color_map["Freigegeben"] = "#019E59";
   } elseif ($option == "ParlamentNachParteien") {
      $cmd = "
      select pa.abkuerzung as label, count(*) as value, pa.farbcode as color, '' as type
      from v_parlamentarier p
      inner join v_partei pa on pa.id = p.partei_id
      where p.ratstyp = 'NR'
      group by pa.abkuerzung
      order by 2, 1
      ";

      $color_map["CSP"] = "#FB7407";
      $color_map["MCR"] = "#0A7D3A";
      $color_map["EVP"] = "#FB7407";
      $color_map["Lega"] = "#0A7D3A";
      $color_map["BDP"] = "#FFE543";
      $color_map["GLP"] = "#88487F";
      $color_map["GPS"] = "#07F61E";
      $color_map["CVP"] = "#FB7407";
      $color_map["FDP"] = "#0A4BD6";
      $color_map["SP"] = "#FF0505";
      $color_map["SVP"] = "#0A7D3A";
   } elseif (utils_startsWith($option,"bearbeitungsanteil")) {
     if (utils_endsWith($option,"-periode")) {

       $periodeStart = getSettingValue('erfassungsPeriodeStart', false, '01.03.2015');
       $periodeEnde = getSettingValue('erfassungsPeriodeEnde', false, null);
       if ($periodeEnde == null) {
         $periodeEnde = '31.12.2030';
       }

       $periodeSQL = " WHERE eingabe_abgeschlossen_datum BETWEEN STR_TO_DATE('$periodeStart','%d.%m.%Y') AND  STR_TO_DATE('$periodeEnde','%d.%m.%Y') ";
       } else {
       $periodeSQL = '';
     }

     $cmd = "
SELECT '' as type, visa as label, COUNT(visa) as value, NULL as color  FROM (
SELECT visa
FROM (
SELECT 'branche' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM branche $periodeSQL
UNION ALL
SELECT 'interessenbindung' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM interessenbindung $periodeSQL
UNION ALL
SELECT 'interessenbindung' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM interessenbindung_jahr $periodeSQL
UNION ALL
SELECT 'interessengruppe' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM interessengruppe $periodeSQL
UNION ALL
SELECT 'in_kommission' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM in_kommission $periodeSQL
UNION ALL
SELECT 'kommission' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM kommission $periodeSQL
UNION ALL
SELECT 'mandat' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM mandat $periodeSQL
UNION ALL
SELECT 'mandat' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM mandat_jahr $periodeSQL
UNION ALL
SELECT 'organisation' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM organisation $periodeSQL
UNION ALL
SELECT 'organisation_beziehung' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM organisation_beziehung $periodeSQL
UNION ALL
SELECT 'organisation_jahr' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM organisation_jahr $periodeSQL
UNION ALL
SELECT 'parlamentarier' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM parlamentarier $periodeSQL
UNION ALL
SELECT 'partei' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM partei $periodeSQL
UNION ALL
SELECT 'fraktion' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM fraktion $periodeSQL
UNION ALL
SELECT 'rat' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM rat $periodeSQL
UNION ALL
SELECT 'kanton' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM kanton $periodeSQL
UNION ALL
SELECT 'kanton_jahr' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM kanton_jahr $periodeSQL
UNION ALL
SELECT 'zutrittsberechtigung' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM zutrittsberechtigung $periodeSQL
UNION ALL
SELECT 'person' as table_name, id, lower(eingabe_abgeschlossen_visa) as visa FROM person $periodeSQL
) union_query
) total_visa
GROUP BY label
ORDER BY value DESC;
                                             ";
         } elseif (utils_startsWith($option, "erstellungsanteil")) {
  if (utils_endsWith(
      $option,
      "-periode")) {

      $periodeStart = getSettingValue(
        'erfassungsPeriodeStart',
        false,
        '01.03.2015');
    $periodeEnde = getSettingValue(
        'erfassungsPeriodeEnde',
        false,
        null);
    if ($periodeEnde==
        null) {
      $periodeEnde = '31.12.2030';
    }

    $periodeSQL = " WHERE created_date BETWEEN STR_TO_DATE('$periodeStart','%d.%m.%Y') AND  STR_TO_DATE('$periodeEnde','%d.%m.%Y') ";
  } else {
    $periodeSQL = '';
  }

           $cmd = "
SELECT '' as type, visa as label, COUNT(visa) as value, NULL as color  FROM (
SELECT visa
FROM (
SELECT 'branche' as table_name, id, lower(created_visa) as visa FROM branche $periodeSQL
UNION ALL
SELECT 'interessenbindung' as table_name, id, lower(created_visa) as visa FROM interessenbindung $periodeSQL
UNION ALL
SELECT 'interessenbindung' as table_name, id, lower(created_visa) as visa FROM interessenbindung_jahr $periodeSQL
UNION ALL
SELECT 'interessengruppe' as table_name, id, lower(created_visa) as visa FROM interessengruppe $periodeSQL
UNION ALL
SELECT 'in_kommission' as table_name, id, lower(created_visa) as visa FROM in_kommission $periodeSQL
UNION ALL
SELECT 'kommission' as table_name, id, lower(created_visa) as visa FROM kommission $periodeSQL
UNION ALL
SELECT 'mandat' as table_name, id, lower(created_visa) as visa FROM mandat $periodeSQL
UNION ALL
SELECT 'mandat' as table_name, id, lower(created_visa) as visa FROM mandat_jahr $periodeSQL
UNION ALL
SELECT 'organisation' as table_name, id, lower(created_visa) as visa FROM organisation $periodeSQL
UNION ALL
SELECT 'organisation_beziehung' as table_name, id, lower(created_visa) as visa FROM organisation_beziehung $periodeSQL
UNION ALL
SELECT 'organisation_jahr' as table_name, id, lower(created_visa) as visa FROM organisation_jahr $periodeSQL
UNION ALL
SELECT 'parlamentarier' as table_name, id, lower(created_visa) as visa FROM parlamentarier $periodeSQL
UNION ALL
SELECT 'partei' as table_name, id, lower(created_visa) as visa FROM partei $periodeSQL
UNION ALL
SELECT 'fraktion' as table_name, id, lower(created_visa) as visa FROM fraktion $periodeSQL
UNION ALL
SELECT 'rat' as table_name, id, lower(created_visa) as visa FROM rat $periodeSQL
UNION ALL
SELECT 'kanton' as table_name, id, lower(created_visa) as visa FROM kanton $periodeSQL
UNION ALL
SELECT 'kanton_jahr' as table_name, id, lower(created_visa) as visa FROM kanton_jahr $periodeSQL
UNION ALL
SELECT 'zutrittsberechtigung' as table_name, id, lower(created_visa) as visa FROM zutrittsberechtigung $periodeSQL
UNION ALL
SELECT 'person' as table_name, id, lower(created_visa) as visa FROM person $periodeSQL
UNION ALL
SELECT 'parlamentarier_anhang' as table_name, id, lower(created_visa) as visa FROM parlamentarier_anhang $periodeSQL
UNION ALL
SELECT 'organisation_anhang' as table_name, id, lower(created_visa) as visa FROM organisation_anhang $periodeSQL
UNION ALL
SELECT 'person_anhang' as table_name, id, lower(created_visa) as visa FROM person_anhang $periodeSQL
UNION ALL
SELECT 'settings' as table_name, id, lower(created_visa) as visa FROM settings $periodeSQL
UNION ALL
SELECT 'settings_category' as table_name, id, lower(created_visa) as visa FROM settings_category $periodeSQL
) union_query
) total_visa
GROUP BY label
ORDER BY value DESC;
                           ";
         } else {
           $cmd = '';
         }

    $stmt = $db->prepare($cmd);

    $stmt->execute(array());
    $result = $stmt->fetchAll ( PDO::FETCH_ASSOC );

   if (!$result) {
      print_r($db->errorInfo());
      die;
   }

   $data = array();

   foreach($result as $row) {
      $rowdata = [
         "type" => $row["type"],
         "label" => $row["label"],
         "value" => $row["value"],
         "color" => $row["color"] != null ? $row["color"] : @$color_map[$row["label"]]
      ];

      $data[] = $rowdata;
   }

   echo json_encode($data);

   $db = null;
