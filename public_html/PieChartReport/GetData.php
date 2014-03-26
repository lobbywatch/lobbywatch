<?php

  require_once dirname(__FILE__) . "/../settings/settings.php";
  require_once dirname(__FILE__) . "/../common/utils.php";

//    $username = "root";
//    $password = "mysql";
//    $host = "localhost";
//    $database="lobbywatch";

   $username = $db_connection['reader_username'];
   $password = $db_connection['reader_password'];
   $host = $db_connection['server'];
   $database=$db_connection['database'];


//    $server = mysqli_connect($host, $username, $password);
//    $connection = mysqli_select_db($database, $server);

  $connection = new mysqli($host, $username, $password, $database);

   $option = urldecode($_GET["option"]);
//    $id = urldecode($_GET["id"]);

   $cmd = "";
   $color_map = array();

   if ($option == "ParlamentInKommissionStatus") {
      $cmd = "
      select count(*) as value, 'nicht bearbeitet' as label
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id = 1) and
         p.eingabe_abgeschlossen_datum is null and
         p.kontrolliert_datum is null and
         p.autorisierung_verschickt_datum is null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
      union
      select count(*) as value, 'Erfasst' as label
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id = 1) and
         p.eingabe_abgeschlossen_datum is not null and
         p.kontrolliert_datum is null and
         p.autorisierung_verschickt_datum is null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
      union
      select count(*) as value, 'Kontrolliert' as label
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id = 1) and
         p.kontrolliert_datum is not null and
         p.autorisierung_verschickt_datum is null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
      union
      select count(*) as value, 'Verschickt' as label
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id = 1) and
         p.autorisierung_verschickt_datum is not null and
         p.autorisiert_datum is null and
         p.freigabe_datum is null
      union
      select count(*) as value, 'Autorisiert' as label
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id = 1) and
         p.autorisiert_datum is not null and
         p.freigabe_datum is null
      union
      select count(*) as value, 'Freigegeben' as label
      from v_parlamentarier p
      where
         exists (select * from in_kommission ik where ik.parlamentarier_id = p.id AND ik.kommission_id = 1) and
         p.freigabe_datum is not null
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
      select pa.abkuerzung as label, count(*) as value, '#FFE543' as color
      from v_parlamentarier p
      inner join partei pa on pa.id = p.partei_id
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
   }

   $query = $connection->query($cmd);

   if (!$query) {
      echo $connection->error;
      die;
   }

   $data = array();

   while ($row = $query->fetch_array(MYSQLI_ASSOC)) {
      /*echo "Label: {$row["label"]}, value: {$row["value"]}, color:{$row["color"]} \n";*/

      /* set color */
      $color = $color_map[$row["label"]];

      $rowdata = [
         "label" => $row["label"],
         "value" => $row["value"],
         "color" => $color
      ];

      $data[] = $rowdata;
   }

   /*
   for ($x = 0; $x < mysql_num_rows($query); $x++) {
      $data[] = mysql_fetch_assoc($query);
   }
   */

   echo json_encode($data);

   $connection->close();
