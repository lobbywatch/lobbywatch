<?php

exit(0);

require_once "../../common/settings.php";

// phpinfo();
/**
 * *****************************************************************************************************************************************************************
 * /*Adminpage From LobbyControl
 * /*
 * /*
 * /*
 * /****************************************************************************************************************************************************************
 */
header ( 'Content-type: text/html; charset=UTF-8' );
$optionen = array (
    PDO::ATTR_PERSISTENT => true
);
$db = new PDO ( 'mysql:host=localhost;dbname=' . $db_connection['database'] . ';charset=utf8', $db_connection['reader_username'], $db_connection['reader_password'], $optionen );
/* Schnellsuche auf lobbyorg.htm */
class LobbyOrgSuche {
  var $db = NULL;
  function __construct($db) {
    $this->db = $db;
    // $db -> exec("set names latin1");
  }
  // Fortschreitende Suche
  function lobbyorgFinden($name) {
    $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.id_lobbygroup,a.weblink,a.vernehmlassung,a.parlam_verbindung, c.name,c.vorname,c.ratstyp,c.partei,c.kanton,c.kommission,d.lt_kategorie FROM lobbyorganisationen a,parlamentarier c, interessenbindungen b,lobbytypen d WHERE  b.id_parlam=c.id_parlam  AND a.id_lobbyorg=b.id_lobbyorg AND b.id_lobbytyp=d.id_lobbytyp  AND a.lobbyname LIKE '%$name%' ORDER BY a.id_lobbyorg";
    $suche = $this->db->query ( $sql );
    $erg = $suche->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine Lobbyorganisation gefunden";
    }
  }
  // fortschreitende Suche auf dem QuicksearchPanel Lobbyorganisationen
  function quickSearchLobbys($term) {
    $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung,b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b WHERE a.id_lobbytyp=b.id_lobbytyp AND a.lobbyname LIKE '%$term%' ORDER BY id_lobbyorg";
    $quicksearch = $this->db->query ( $sql );
    $erg = $quicksearch->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine Lobbyorganisation gefunden";
    }
  }
  function verbindungen($id) {
    $sql = "SELECT a.id_parlam,a.name,a.vorname,a.partei,a.ratstyp,a.kanton, a.kommission, a.kleinbild,b.id_interessen FROM parlamentarier a, interessenbindungen b, lobbyorganisationen c  WHERE  a.id_parlam=b.id_parlam AND c.id_lobbyorg=b.id_lobbyorg AND c.id_lobbyorg='$id' ORDER BY a.name";
    $quicksearchnamen = $this->db->query ( $sql );
    $namen = $quicksearchnamen->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $namen );
    if ($anz > 0) {
      return $namen;
    } else {
      return "Keine Interessenbindungen zu dieser Organisation";
    }
  }

  // EinzelParlamentarier Kurzinfos Lobbyorg
  function lobbyOrgEinzel($id) {
    $sql = "SELECT lobbyname,lobbyorgtyp,vernehmlassung,parlam_verbindung,weblink FROM lobbyorganisationen WHERE id_lobbyorg='$id'";
    $lobbyorg = $this->db->query ( $sql );
    $erg = $lobbyorg->fetchAll ( PDO::FETCH_ASSOC );
    return $erg;
  }
  // Mehrfachverbindungen &uuml;ber Parlamentarier-Interface
  function lobbyOrgMehrfach($id) {
    $sql = "SELECT a.name,a.vorname,a.partei,a.ratstyp FROM parlamentarier a, interessenbindungen b WHERE a.id_parlam=b.id_parlam AND b.id_lobbyorg ='$id'";
    $mehrfach = $this->db->query ( $sql );
    $erg = $mehrfach->fetchAll ( PDO::FETCH_ASSOC );
    return $erg;
  }
  /**
   * ************************************************************************************************************************************************
   * Suchpanel Lobbyorg: bedeutung,lobbytyp,lobbygroup, thema 2 hoch 4 =16 Abfragen
   * *************************************************************************************************************************************************
   */
  function lobbyOrgGruppen($bedeutung, $lobbytyp, $lobbygroup, $thema) {
    /*
     * SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, c.name,c.vorname,c.ratstyp,c.partei,c.kanton,d.lt_kategorie FROM lobbyorganisationen a,parlamentarier c, interessenbindungen b,lobbytypen d WHERE b.id_parlam=c.id_parlam AND a.id_lobbyorg=b.id_lobbyorg AND b.id_lobbytyp=d.id_lobbytyp AND a.lobbyname LIKE '%$name%' ORDER BY a.id_lobbyorg";
     */
    // 0100
    if ($bedeutung == 'alle' and $lobbytyp !== 'alle' and $lobbygroup == 'alle') {
      $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.id_lobbytyp ='$lobbytyp'  ORDER BY a.lobbyname";
      // 1000
    } else if ($bedeutung != 'alle' and $lobbytyp == 'alle' and $lobbygroup == 'alle') {
      // 1000
      if ($bedeutung == 1) { // hoch
                         // Kommissionsbezug fehlt noch
        $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.lobbyorgtyp LIKE '%dezidierteLobby' AND (a.vernehmlassung='immer' OR a.vernehmlassung='punktuell') AND a.parlam_verbindung LIKE '%exekutiv%' ORDER BY b.lt_kategorie,a.lobbyname";
      }
      // mittel/0/0/0
      if ($bedeutung == 2) { // mittel
        $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.lobbyorgtyp LIKE '%dezidierteLobby' AND a.vernehmlassung='punktuell'  AND a.parlam_verbindung LIKE '%exekutiv%' ORDER BY b.lt_kategorie,a.lobbyname";
      }
      // gering/000
      if ($bedeutung == 3) { // gering
        $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp   AND a.vernehmlassung='nie'  ORDER BY b.lt_kategorie,a.lobbyname";
      }
    } else if ($bedeutung == 1 and $lobbytyp !== 'alle' and $lobbygroup == 'alle') {
      // hoch/1/0/0
      $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.lobbyorgtyp LIKE '%dezidierteLobby' AND (a.vernehmlassung='immer' OR a.vernehmlassung='punktuell') AND a.parlam_verbindung LIKE '%exekutiv%' AND a.id_lobbytyp='$lobbytyp'  ORDER BY a.lobbyname,a.vernehmlassung";
    } else if ($bedeutung == 2 and $lobbytyp !== 'alle' and $lobbygroup == 'alle') {
      // mittel/1/0/0
      $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.lobbyorgtyp LIKE '%dezidierteLobby' AND a.vernehmlassung='punktuell' AND a.parlam_verbindung LIKE '%exekutiv%' AND a.id_lobbytyp='$lobbytyp'  ORDER BY a.lobbyname";
    } else if ($bedeutung == 3 and $lobbytyp !== 'alle' and $lobbygroup == 'alle') {
      // gering/1/0/0
      $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie FROM lobbyorganisationen a,lobbytypen b  WHERE a.id_lobbytyp=b.id_lobbytyp   AND a.vernehmlassung='nie'  AND a.id_lobbytyp='$lobbytyp'  ORDER BY a.lobbyname";
    } else if ($bedeutung == 'alle' and $lobbytyp !== 'alle' and $lobbygroup != 'alle') {
      // alle/1/1 Mit Lobbygruppen
      $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie,c.lg_bezeichnung FROM lobbyorganisationen a,lobbytypen b , lobbygruppen c WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.id_lobbygroup=c.id_lobbygroup  AND a.id_lobbytyp='$lobbytyp' AND a.id_lobbygroup ='$lobbygroup' ORDER BY a.lobbyname";
    } else if ($bedeutung == 1 and $lobbytyp !== 'alle' and $lobbygroup != 'alle') {
      // hoch/1/1/0 //Mit Lobbygroup
      $sql = "SELECT a.id_lobbyorg,a.lobbyname,a.lobbydescription,a.lobbyorgtyp,a.weblink,a.vernehmlassung,a.parlam_verbindung, b.lt_kategorie,c.lg_bezeichnung FROM lobbyorganisationen a,lobbytypen b , lobbygruppen c WHERE a.id_lobbytyp=b.id_lobbytyp  AND a.id_lobbygroup=c.id_lobbygroup AND a.lobbyorgtyp LIKE '%dezidierteLobby' AND (a.vernehmlassung='immer' OR a.vernehmlassung='punktuell') AND (a.parlam_verbindung LIKE '%exekutiv%' OR a.parlam_verbindung='') AND a.id_lobbytyp='$lobbytyp' AND a.id_lobbygroup ='$lobbygroup' ORDER BY a.lobbyname,a.vernehmlassung";
    }

    /*
     * mysql> SELECT * FROM tbl_name WHERE FIND_IN_SET('value',set_col)>0; mysql> SELECT * FROM tbl_name WHERE set_col LIKE '%value%'; Die erste Anweisung findet Datensätze, bei denen set_col den Mitgliedswert value enthält. Die zweite ist ähnlich, aber nicht identisch: Sie findet Datensätze, bei denen set_col value an beliebiger Stelle (auch als Teil-String eines anderen Mitglieds) enthält. Die folgenden Anweisungen sind ebenfalls zulässig: mysql> SELECT * FROM tbl_name WHERE set_col & 1; mysql> SELECT * FROM tbl_name WHERE set_col = 'val1,val2';
     */

    // Vernehmlassung immer,punktuell,nie lobbyorgtyp:EinzelorganisationDachorganisation,dezidierteLobby

    $lobbygroup = $this->db->query ( $sql );
    $erg = $lobbygroup->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine Daten in dieser Suche";
    }
  }
  // Aus Suchergebnissen Lobbyorganisationen Parlamentarische Verbindung finden: Vorgabe: id_lobbyorg
  function lobbyOrgParlam($id) {
    $sql = "SELECT a.id_parlam,a.name,a.vorname,a.ratstyp,a.partei,a.kanton,a.kommission,a.kleinbild,a.sitzplatz FROM parlamentarier a,interessenbindungen b,lobbyorganisationen c WHERE a.id_parlam=b.id_parlam AND c.id_lobbyorg=b.id_lobbyorg AND c.id_lobbyorg='$id' ORDER BY a.partei,a.name";
    $bindung = $this->db->query ( $sql );
    $erg = $bindung->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine deklarierte Interessenverbindung vorhanden.";
    }
  }
  // Lobbyorganisationen aus Zugangsberechtigungen in den Suchergebnissen Lobbyorganisationen
  function lobbyOrgParlamentZugang($id) {
    $sql = "SELECT a.id_parlam,a.name,a.vorname,a.ratstyp,a.partei,a.kanton,a.kommission,a.kleinbild,a.sitzplatz, b.berech_name,berech_vorname FROM parlamentarier a,zugangsberechtigungen b, lobbyorganisationen c WHERE a.id_parlam=b.id_parlam AND c.id_lobbyorg=b.id_lobbyorg AND c.id_lobbyorg='$id' ORDER BY a.name,a.partei";
    $bindungzugang = $this->db->query ( $sql );
    $erg = $bindungzugang->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine aktuellen Zugangsberechtigungen";
    }
  }
}
class Parlamentarier {
  var $db = NULL;
  function __construct($db) {
    $this->db = $db;
  }
  // Einzelparmanebtarier und Interessenbindungen (alle nach Lobbytyp)nach Name
  function einzelParlamentarier($name) {

    /* $sql="SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,b.ib_description,b.id_lobbyorg, b.ib_status,c.lt_kategorie FROM parlamentarier a ,interessenbindungen b ,lobbytypen c WHERE a.id_parlam=b.id_parlam AND b.id_lobbytyp=c.id_lobbytyp AND a.name LIKE '$name%' ORDER BY c.lt_kategorie"; */
    $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild FROM parlamentarier a WHERE a.name LIKE '$name%' ORDER BY a.name";
    $einzelParlam = $this->db->query ( $sql );
    $erg = $einzelParlam->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Kein Datensatz mit dem Namen $name";
    }
  }

  // Interessenbindungen nach id des Parlamentariers finden
  function ibEinzelparlamentarier($idparl) {
    $sql = "SELECT a.ib_description,a.id_lobbyorg,a.ib_status,b.lt_kategorie FROM interessenbindungen a, lobbytypen b WHERE a.id_lobbytyp=b.id_lobbytyp AND a.id_parlam='$idparl' ORDER BY  b.lt_kategorie";
    $ibparl = $this->db->query ( $sql );
    $erg = $ibparl->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Kein Datensatz mit vorhanden";
    }
  }

  // Einzelparlamentarier nach Sitzplatznummer und Ratstyp f&uuml;r Sitzplatzfenster
  function sitzplatz($ratstyp, $sitzplatz) {
    $sql = "SELECT id_parlam,name,vorname,beruf,ratstyp,kanton,partei,parteifunktion,im_rat_seit,kommission,kleinbild FROM parlamentarier WHERE ratstyp='$ratstyp' AND sitzplatz='$sitzplatz'";
    $sitzplatz = $this->db->query ( $sql );
    $erg = $sitzplatz->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Kein Ratsmitglied gefunden";
    }
  }

  // Untermengen Parlamentarier-Suche
  function gruppenParlamentarier($partei, $kanton, $ratstyp, $komm) {
    // 100
    if ($partei != 'alleparteien' and $kanton == 'allekantone' and $ratstyp == 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz FROM parlamentarier a WHERE a.partei = '$partei' ORDER BY  a.name";

      // 010
    } else if ($partei == 'alleparteien' and $kanton != 'allekantone' and $ratstyp == 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz FROM parlamentarier a  WHERE a.kanton = '$kanton' ORDER BY  a.partei,a.name";
      // 001
    } else if ($partei == 'alleparteien' and $kanton == 'allekantone' and $ratstyp != 'alleraete') {
      $sql = "SELECT  id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz FROM parlamentarier a  WHERE  a.ratstyp = '$ratstyp' ORDER BY  a.partei,a.name";
      // 110
    } else if ($partei != 'alleparteien' and $kanton != 'allekantone' and $ratstyp == 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz FROM parlamentarier a  WHERE  a.partei = '$partei' AND a.kanton='$kanton' ORDER BY  a.kanton,a.name";
      // 101
    } else if ($partei != 'alleparteien' and $kanton == 'allekantone' and $ratstyp != 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz  FROM parlamentarier a WHERE  a.partei = '$partei' AND a.ratstyp='$ratstyp' ORDER BY  a.ratstyp,a.partei";
      // 011
    } else if ($partei == 'alleparteien' and $kanton != 'allekantone' and $ratstyp != 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz  FROM parlamentarier a WHERE  a.kanton = '$kanton' AND a.ratstyp='$ratstyp' ORDER BY  a.ratstyp,a.kanton";
      // 111
    } else if ($partei != 'alleparteien' and $kanton != 'allekantone' and $ratstyp != 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz  FROM parlamentarier a WHERE a.kanton = '$kanton' AND a.ratstyp='$ratstyp' AND a.partei='$partei' ORDER BY  a.partei,a.kanton";
      // 000
    } else if ($partei == 'alleparteien' and $kanton == 'allekantone' and $ratstyp == 'alleraete') {
      $sql = "SELECT a.id_parlam,a.name,a.vorname,a.beruf,a.ratstyp,a.kanton,a.partei,a.parteifunktion,a.im_rat_seit,a.kommission,a.kleinbild,a.sitzplatz  FROM parlamentarier a ORDER BY a.ratstyp,a.name";
    }

    $gruppe = $this->db->query ( $sql );
    $erg = $gruppe->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine Daten in dieser Abfrage";
    }
  }
  // Alle Parlamentarier
  function alleParlamentarier() {
    $sql = "SELECT * FROM parlamentarier WHERE kommission LIKE '%SGK-NR%' OR kommission LIKE '%SGK-SR%' ORDER BY ratstyp,name";
    $parlam = $this->db->query ( $sql );
    $erg = $parlam->fetchAll ( PDO::FETCH_ASSOC );
    $anz = count ( $erg );
    if ($anz > 0) {
      return $erg;
    } else {
      return "Keine Daten, Fehler in der Abfrage!";
    }
  }
  function kantonskuerzel() {
    $sql = "SELECT distinct kanton FROM parlamentarier ORDER BY kanton ASC";
    $kanton = $this->db->query ( $sql );
    $kt = $kanton->fetchAll ( PDO::FETCH_ASSOC );
    return $kt;
  }
  function parteikuerzel() {
    $sql = "SELECT distinct partei FROM parlamentarier ORDER BY partei ASC";
    $partei = $this->db->query ( $sql );
    $pk = $partei->fetchAll ( PDO::FETCH_ASSOC );
    return $pk;
  }
  function lobbytypen() {
    $sql = "SELECT id_lobbytyp,lt_kategorie FROM lobbytypen ORDER BY lt_kategorie ASC";
    $lobbytypen = $this->db->query ( $sql );
    $lt = $lobbytypen->fetchAll ( PDO::FETCH_ASSOC );
    return $lt;
  }
  function lobbytypen_einfach($id) { // Einfacher Array mit Lobbytypen
    $sql = "SELECT lt_kategorie FROM lobbytypen WHERE id_lobbytyp='$id'";
    $lobbytyp = $this->db->query ( $sql );
    $ltyp = $lobbytyp->fetchAll ( PDO::FETCH_ASSOC );
    foreach ( $ltyp as $val ) {
      $ltyp = $val ['lt_kategorie'];
    }
    return $ltyp; // Nur eine Kategorie
  }
  function lobbygruppen($lobbytyp) {
    $sql = "SELECT id_lobbygroup,lg_bezeichnung FROM lobbygruppen WHERE id_lobbytyp='$lobbytyp' ORDER BY lg_bezeichnung ASC";
    $lobbygruppen = $this->db->query ( $sql );
    $lg = $lobbygruppen->fetchAll ( PDO::FETCH_ASSOC );
    return $lg;
  }
  function lobbygruppen_einfach($id) { // Einfacher Array mit Lobbytypen
    $sql = "SELECT lg_bezeichnung FROM lobbygruppen WHERE id_lobbygroup='$id'";
    $lobbygruppe = $this->db->query ( $sql );
    $lgroup = $lobbygruppe->fetchAll ( PDO::FETCH_ASSOC );
    foreach ( $lgroup as $val ) {
      $lgroup = $val ['lg_bezeichnung'];
    }
    return $lgroup; // Nur eine Kategorie
  }
  // Zugangsberechtigungen nach ParlamentarierName: differenziert nach gew&ouml;hnlichen G&auml;sten ohne oder mit Lobbyorg
  function zugangsberechtigungen($id) { // $name
                                       // Quelle f&uuml;r solche SQLs:http://aktuell.de.selfhtml.org/artikel/datenbanken/fortgeschrittene-joins/mehrfachjoin2.htm
    $sql = "SELECT name,vorname,berech_name,berech_vorname,berech_organisation,id_lobbyorg,lt_kategorie FROM parlamentarier INNER JOIN (zugangsberechtigungen LEFT JOIN lobbytypen ON lobbytypen.id_lobbytyp=zugangsberechtigungen.id_lobbytyp) ON parlamentarier.id_parlam=zugangsberechtigungen.id_parlam  WHERE zugangsberechtigungen.id_parlam='$id' ORDER BY name,lt_kategorie";
    $zugang = $this->db->query ( $sql );
    $erg = $zugang->fetchAll ( PDO::FETCH_ASSOC );
    if (count ( $erg ) > 0) {
      return $erg;
    } else {
      return "Keine Zugangsberechtigungen gefunden";
    }
  }
  // Zugangsberechtigungen nach ParlamentarierID:Left JOIN f&uuml;r blosse G&auml;ste oder pers.Mitarbeiter: Nur wenn Lobbyorg ungleich Null ist
  function zugangsberechtigungenNachId($id) {
    $sql = "SELECT berech_name,berech_vorname,berech_organisation,lt_kategorie FROM zugangsberechtigungen LEFT JOIN lobbytypen ON lobbytypen.id_lobbytyp=zugangsberechtigungen.id_lobbytyp WHERE zugangsberechtigungen.id_parlam ='$id' ";
    $zugang = $this->db->query ( $sql );
    $erg = $zugang->fetchAll ( PDO::FETCH_ASSOC );
    if (count ( $erg ) > 0) {
      return $erg;
    } else {
      return "Aktuell keine aktiven Zugangsberechtigungen";
    }
  }
} // End class Parlamentarier
/*
 * ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// CLASS Statistik ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 */
class Statistik {
  var $db = NULL;
  function __construct($db) {
    $this->db = $db;
  }
  // Auswahl Parlamentarier
  function getPnamen($name) {
    $sql = "SELECT id_parlam,name,vorname FROM parlamentarier WHERE name LIKE '$name%'";
    $names = $this->db->query ( $sql );
    $erg = $names->fetchAll ( PDO::FETCH_ASSOC );
    if (count ( $erg ) > 0) {
      return $erg;
    } else {
      return "Keine Parlamentarier gefunden!";
    }
  }
  function ungewichtetEinzel($id) {
    $sql = "SELECT b.name,b.vorname, b.ratstyp,b.partei,b.kanton,c.lt_kategorie,count('a.id_lobbytyp') AS Anzahl FROM interessenbindungen a, parlamentarier b,lobbytypen c WHERE a.id_parlam=b.id_parlam AND a.id_lobbytyp=c.id_lobbytyp AND b.id_parlam ='$id' GROUP BY a.id_lobbytyp ORDER BY Anzahl DESC";
    $pie = $this->db->query ( $sql );
    $erg = $pie->fetchAll ( PDO::FETCH_ASSOC );
    if (count ( $erg ) > 0) {
      return $erg;
    } else {
      return "Keine Daten zu dieser ID";
    }
  }
} // End class Statistik
  // ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* Ausf&uuml;hrung von fortschreitende Suche nach Lobbyorganisationen (Nur f&uuml;r Teste zur Dateneingabe */
 /*Das ist nur f&uuml;r den Test, ob die Lobby schon existiert: In lobbyorg.php ->standalone*/
 if ($_REQUEST ['term'] != '') {
  // htmlentities(utf8_decode($_POST['parlamname']));
  $name = utf8_decode ( $_REQUEST ['term'] );

  $abfrage = new LobbyOrgSuche ( $db );
  $erg = $abfrage->lobbyorgFinden ( $name );
  $html = "<table border='1'><tr>";
  foreach ( $erg as $wert ) {
    $html .= "<td>$wert[id_lobbyorg]</td><td>$wert[lobbyname]</td><td>$wert[lobbyorgtyp]</td><td>$wert[lt_kategorie]</td><td>$wert[id_lobbygroup]</td><td>$wert[parlam_verbindung]</td><td>$wert[vernehmlassung]</td><td>$wert[name]</td><td>$wert[vorname]</td><td>$wert[ratstyp] $wert[partei] $wert[kanton] </td><td>$wert[kommission]</td></tr><tr>";
  }
  print $html;
}
// Fortschreitende Suche nach Lobbyorganisationen auf der Website
if ($_REQUEST ['fragment'] != '') {
  // htmlentities(utf8_decode($_POST['parlamname']));
  $name = utf8_decode ( $_REQUEST ['fragment'] );

  $abfrage = new LobbyOrgSuche ( $db );
  $erg = $abfrage->quickSearchLobbys ( $name );
  $html = "<table id='quicksearch'><tr>";

  foreach ( $erg as $wert ) {
    // viele
    if (count ( $erg ) > 1) {

      $html .= "<td style='width:400px' data-lobbyorg='$wert[id_lobbyorg]'><div  ><b style='cursor:pointer'><img style='cursor:pointer' src='./icons/mouseclick_mini.jpg' />$wert[lobbyname]</b><p style='margin:0;width:400px;height:100px;overflow:auto' class='description'>$wert[lobbydescription]</p></div></td><td style='width:200px'>Bereich: $wert[lt_kategorie]<br> $wert[lobbyorgtyp]<br>Vernehmlassung: $wert[vernehmlassung]<br>Verbindung: $wert[parlam_verbindung]<br><a href='$wert[weblink]'>Weblink</a></td></tr><tr>";
    } else if (count ( $erg ) == 1) {
      $kaskad = new LobbyOrgSuche ( $db );
      $ibv = $kaskad->verbindungen ( $wert ['id_lobbyorg'] );
      // Zugangsberechtigungen hier ebenfalls ber&uuml;cksichtigen
      $zgb = $kaskad->lobbyOrgParlamentZugang ( $wert ['id_lobbyorg'] );

      $html .= "<td style='width:400px' data-lobbyorg='$wert[id_lobbyorg]'><div ><b style='cursor:pointer'><img src='./icons/mouseclick_mini.jpg' />$wert[lobbyname]</b><p style='margin:0;width:400px;height:100px;overflow:auto' class='description'>$wert[lobbydescription]</p></div></td><td style='width:200px'>Bereich: $wert[lt_kategorie]<br> $wert[lobbyorgtyp]<br>Vernehmlassung: $wert[vernehmlassung]<br>Verbindung: $wert[parlam_verbindung]<br><a href='$wert[weblink]'>Weblink</a></td><td id='parlambild' style='width:auto'>";
      if (is_string ( $ibv ) and is_string ( $zgb )) {
        $html .= $ibv; // Fehlermeldung
      } else if (is_array ( $ibv ) or is_array ( $zgb )) {
        error_reporting ( 0 );
        // Interessenbindungen
        foreach ( $ibv as $bild ) {
          error_reporting ( 0 );
          $html .= "<img style='cursor:pointer' data-parlname='$bild[name]' title='" . $bild [name] . " " . $bild [vorname] . "\n" . $bild [partei] . " " . $bild [ratstyp] . " " . $bild [kanton] . "\nInteressenbindung' src='./parlamentarierBilder/" . $bild ['kleinbild'] . "' />";
        }
        // Zugangsberechtigungen
        foreach ( $zgb as $verb ) {
          // if(in_array($verb['kleinbild'],$ibv)==false){Schwierig abzufragen)
          $html .= "<img style='cursor:pointer' data-parlname='$verb[name]' title='" . $verb [name] . " " . $verb [vorname] . "\n" . $verb [partei] . " " . $verb [ratstyp] . " " . $verb [kanton] . "\nZutrittsberechtigung' src='./parlamentarierBilder/" . $verb ['kleinbild'] . "' />";
          // }
        }

        $html .= "</td>";
      }
    }
  }

  $html .= "</tr></table><div id='parlameximg'></div>";

  print $html;
}

/**
 * *********************************************************************************************
 * Suchpanel Parlamentarier
 *
 * *********************************************************************************************
 */
if (isset ( $_GET ['datatyp'] ) and $_GET ['datatyp'] == 'parlam') {
  $abfrage = new Parlamentarier ( $db );
  $erg = $abfrage->kantonskuerzel ();
  // print_r ($erg);
  $erg1 = $abfrage->parteikuerzel ();
  // print_r ($erg1);
  $html = "<div id='parlamsuche'><h3>Suchpanel Parlamentarier</h3>";
  $html .= "<form name='parlamsuche' id='parlamsuche'>";
  $html .= "<ul>";
  $html .= "<li><label for='parlamname'>Suche nach Name</label><br> <input type='text' name='parlamname' id='parlamname' /></li>";
  $html .= "<li><label for=partei>Partei</label><br> <select name='partei' id='partei' size='1' >";
  $html .= "<option value='alleparteien'>Alle</option>";
  foreach ( $erg1 as $wert ) {

    $html .= "<option value={$wert['partei']}>{$wert['partei']} </option>";
  }
  $html .= "</select></li>";
  $html .= "<li><label for='kanton'>Kanton </label><br> <select name='kanton' id='kanton' size='1' >";
  $html .= "<option value='allekantone'>Alle</option>";
  foreach ( $erg as $wert ) {

    $html .= "<option value={$wert['kanton']}>{$wert['kanton']}</option>";
  }
  $html .= "</select></li>";
  $html .= "<li><label for='ratstyp'>Ratstyp</label><br><select name='ratstyp' id='ratstyp' size='1' >";
  $html .= "<option value='alleraete'>Beide R&auml;te</option>";
  $html .= "<option value='NR'>NR</option>";
  $html .= "<option value='SR'>SR</option>";
  $html .= "</select><li>";

  $html .= "<li><label for='kommission'>Kommission</label><br><select name='komm' id='komm' size='1'>";
  $html .= "<option value='SGK'>Kommission f&uuml;r soziale Sicherheit und Gesundheit (SGK)</option>";
  $html .= "</select></li>";
  $html .= "<li><label for='darstellung'>Darstellung</label><br><input type='radio' id='darstellung' name='darstellung' value='tabelle' /> Tabelle ";
  $html .= "<input type='radio'  id='darstellung' name='darstellung' checked value='sitzordnung' /> Sitzordnung </li>";
  $html .= "<br><input type='submit' value='Suchen' /></li>";
  $html .= "</ul></form> </div>";
  print $html;
}

/**
 * *************************************************************************
 */
/*
 * Suche im Parlamentarierpanel /***************************************************************************
 */
if ((isset ( $_POST ['parlamname'] ) and strlen ( utf8_decode ( $_POST ['parlamname'] ) ) >= 2) or isset ( $_POST ['parlam'] )) {
  // $variable = htmlentities( utf8_decode($_POST['variable']) ); // right
  if ($_POST ['parlamname']) {

    $name = utf8_decode ( $_POST ['parlamname'] );
  } else {
    $name = utf8_decode ( $_POST ['parlam'] );
  }
  // print($name);//OK ö und ä funtionieren hier
  $einzelparlam = new Parlamentarier ( $db );
  $erg = $einzelparlam->einzelParlamentarier ( $name );
  // $zutritte=$einzelparlam->zugangsberechtigungen($name);
  // print_r($zutritte);
  // print_r($erg);
  if (count ( $erg ) > 1) {
    foreach ( $erg as $wert ) {
      $ar [] = "<div style='float:left;height:100px;margin:15px;cursor:pointer' class='static' data-id={$wert['id_parlam']} ><img src='./parlamentarierBilder/" . $wert ['kleinbild'] . "' title='$wert[name]' /><span>{$wert['name']} {$wert['vorname']}<br> {$wert['ratstyp']}, {$wert['kanton']}, {$wert['partei']}, {$wert['parteifunktion']}<br>{$wert['kommission']}  </span></div>";
    }
    $html = '';
    foreach ( $ar as $wert ) {
      $html .= $wert;
    }
  } else {
    foreach ( $erg as $wert ) {
      $idp = $wert ['id_parlam'];
      $ibs = new Parlamentarier ( $db );
      $erg = $ibs->ibEinzelparlamentarier ( $idp );
      $zutritte = $ibs->zugangsberechtigungen ( $idp );

      $html = "<div  class='static' data-id={$wert['id_parlam']} >";
      $html .= "<img src='./parlamentarierBilder/" . $wert ['kleinbild'] . "' title='$wert[name]' />";
      $html .= "<span>{$wert['name']} {$wert['vorname']}<br> {$wert['ratstyp']}, {$wert['kanton']}, {$wert['partei']}, {$wert['parteifunktion']}<br>{$wert['kommission']}  </span></div>";
    }

    $html .= "<h4 title='Rechtsformen: Stiftung (Stift), AG, Verein(Ve), GmbH\nSitiftungsrat (Sr), Verwaltungsrat(VR), Vorstand (V)\nM: (Mitglied), P: (Pr&auml;sident), VP (Vizepr&auml;sident '>Deklarierte Interessenbindungen </h4><ol class='interessenbindungen'>";

    foreach ( $erg as $wert ) {
      // deklariert
      if ($wert ['ib_status'] == 'd') {
        $ib [] = $wert ['ib_description'] . ' Bereich: ' . $wert ['lt_kategorie'] . "<img src='./icons/mouseclick_mini.jpg' />";
        $lorgnr [] = $wert ['id_lobbyorg'];
        // nicht-deklariert
      } else if ($wert ['ib_status'] == 'n') {
        $ibn [] = $wert ['ib_description'] . ' Bereich: ' . $wert ['lt_kategorie'] . "<img src='./icons/mouseclick_mini.jpg' />";
        $lorgnrn [] = $wert ['id_lobbyorg'];
      }
    }
    // print_r($ib);//OK
    // deklarierte ib
    for($i = 0; $i < count ( $ib ); $i ++) {

      $html .= "<li id='$lorgnr[$i]' style='cursor:pointer'>$ib[$i]</li>";
    }
    // nicht-deklarierte
    if (count ( $ibn ) > 0) {
      $html .= "</ol><h4 title='Rechtsformen: Stiftung (Stift), AG, Verein(Ve), GmbH\nSitiftungsrat (Sr), Verwaltungsrat(VR), Vorstand (V)\nM: (Mitglied), P: (Pr&auml;sident), VP (Vizepr&auml;sident '>Weitere Interessenbindungen (Teledata u.a. Quellen)</h4><ol class='interessenbindungen'>";
      for($i = 0; $i < count ( $ibn ); $i ++) {

        $html .= "<li id='$lorgnrn[$i]' style='cursor:pointer'>$ibn[$i]</li>";
      }
    }

    if (is_array ( $zutritte )) {

      foreach ( $zutritte as $wert ) {
        $kat = $wert ['lt_kategorie'] != '' ? "Bereich: $wert[lt_kategorie]" : '';
        $zt [] = $wert ['berech_name'] . ' ' . $wert ['berech_vorname'] . ', ' . $wert ['berech_organisation'] . ' ' . $kat;
        if ($wert ['id_lobbyorg'] != '') {
          $lid [] = $wert ['id_lobbyorg'];
          // print_r($lid);//OK einer oder zwei drin
          // print_r($zt);//1-2 drin OK
        } else if ($wert ['id_lobbyorg'] == '') {
          $lid [] = 'leer';
        }
      }
      // print_r($lid);//OK einer oder zwei drin
    } else {
      $zt = "Keine aktuellen Zugangsberechtigungen";
    }
    $html .= "</ol><h4>Zugangsberechtigungen ('G&ouml;tti')</h4><ul class='zugangsberechtigungen'>";
    // Problem: wenn nur eine Organisation mit Lobbytyp verbunden ist L&ouml;sung wahrscheinlich gut.
    if (is_array ( $zt )) {
      $z = 0;
      foreach ( $zt as $ber ) {
        if ($lid [$z] != 'leer') {
          // Zutritte mit Lobbyorg
          $html .= "<li style='cursor:pointer' id='" . $lid [$z] . "'>$ber <img src='./icons/mouseclick_mini.jpg' /></li>";
          $z ++;
        } else if ($lid [$z] == 'leer') {
          // Nur Zutritte ohne spezifische Lobbyorg
          $html .= "<li >$ber</li>";
          $z ++;
        }
      }
    } else {
      $html .= "<li>$zt</li>";
    }
    $html .= "</ul></div>";
  }
  print $html;
}
// Infofensnter Lobbyorginfo bei Einzelparlamentariern
if (isset ( $_POST ['loId'] )) {
  $id = $_POST ['loId'];
  $lobbyorg = new LobbyOrgSuche ( $db );
  $erg = $lobbyorg->lobbyOrgEinzel ( $id );
  $doppel = $lobbyorg->lobbyOrgMehrfach ( $id );
  // print_r($doppel);
  $html = "<div id='lobbykurzinfo'>";
  foreach ( $erg as $wert ) {
    $web = $wert ['weblink'] == '' ? "Keine Webverbindung gefunden" : "<a href='" . $wert ['weblink'] . "'>Weblink</a>";
    $html .= "<b>Organisationstyp:</b> <br>{$wert['lobbyorgtyp']}<br><b>Vernehmlassung:</b><br>{$wert['vernehmlassung']}<br><b>$web</b><br>";
    $html .= "<b>Parlamentarische Verbindung:</b><br>{$wert['parlam_verbindung']}<br>";
  }
  if (count ( $doppel ) > 1) {
    $anz = count ( $doppel );
    $html .= "Insgesamt <b>$anz Interessenbindungen </b> dieser Organisation: <br>";
    foreach ( $doppel as $namen ) {
      $html .= "{$namen['name']} {$namen['vorname']} {$namen['partei']} {$namen['ratstyp']}<br>";
    }
  }
  $html .= "</div>";
  print $html;
}

// Gruppensuche im Infopanel Parlamentarier
if (isset ( $_POST ['partei'] )) {
  // print_r($_POST);
  $partei = $_POST ['partei'];
  $kanton = $_POST ['kanton'];
  $ratstyp = $_POST ['ratstyp'];
  $komm = $_POST ['komm'];
  $darstellung = $_POST ['darstellung'];
  $gruppen = new Parlamentarier ( $db );
  $gruppe = $gruppen->gruppenParlamentarier ( $partei, $kanton, $ratstyp, $komm );

  if (is_string ( $gruppe )) { // Keine daten in dieser Abfrage

    // print_r($gruppe);
    $html = "<h3>Abfrage nach Partei: $partei Kanton: $kanton Ratstyp: $ratstyp, Kommission: $komm<br>$gruppe</h3>";
  } else if (is_array ( $gruppe ) and $darstellung == 'tabelle') {
    $html = "<h3>Abfrage nach Partei: $partei Kanton: $kanton Ratstyp: $ratstyp, Kommission: $komm</h3>";
    $html .= "<div id='gruppen' style='position:relative'>";
    $postop = 10;
    $posleft = 0;
    $width = 200;
    $height = 200;
    $counter = 0;

    foreach ( $gruppe as $wert ) {
      // $posleft +=$width;

      $ar [] = "<div class='static' data-id='" . $wert ['id_parlam'] . "' style='border:1px dotted grey;height:200px;width:200px;position:absolute;left:{$posleft}px;top:{$postop}px;' ><img src='./parlamentarierBilder/" . $wert ['kleinbild'] . "' title=\'{$wert['name']}\' /><span>{$wert['name']}  {$wert['vorname']}<br> {$wert['ratstyp']}, {$wert['kanton']}, {$wert['partei']}, {$wert['parteifunktion']}<br>{$wert['kommission']}  </span><h4 style='cursor:pointer' id='ibaccord' data-id='" . $wert ['id_parlam'] . "' title='Rechtsformen: Stiftung (Stift), AG, Verein(Ve), GmbH\nSitiftungsrat (Sr), Verwaltungsrat(VR), Vorstand (V)\nM: (Mitglied), P: (Pr&auml;sident), VP (Vizepr&auml;sident '>Interessenbindungen <img src='./icons/mouseclick_mini.jpg' /></h4><ol style='display:none' class='interessenbindungen'></ol><h4 id='zugangaccord' style='cursor:pointer' data-id='" . $wert ['id_parlam'] . "'>Zugangsberechtigungen  <img src='./icons/mouseclick_mini.jpg' /></h4><ul style='display:none' class='zugangsberechtigungen'</ul></div>";
      $posleft += $width;
      $counter ++;
      if ($counter >= 4) {
        $postop += $height;
        $posleft = 0;
        $counter = 0;
      }
    }
    // print_r($ar);
    foreach ( $ar as $w ) {

      $html .= $w;
    }
    $html .= "</div>";

    print $html;
    //
    // Darstellug der Sitzordnung bei Gruppensuche im Suchpanel Parlamentarier
  }
  if ($darstellung == 'sitzordnung' and ($ratstyp == 'alleraete' or $ratstyp == NR or $ratstyp == 'SR')) {
    $html = "<h3>Abfrage nach Partei: $partei Kanton: $kanton Ratstyp: $ratstyp, Kommission: $komm</h3>";
    foreach ( $gruppe as $rat ) {
      if ($rat ['ratstyp'] == 'NR') {
        $testreihe [] = $rat ['sitzplatz'];
        $testreihe = array_unique ( $testreihe );
      } else if ($rat ['ratstyp'] == 'SR') {
        $testreihesr [] = $rat ['sitzplatz'];
        $testreihesr = array_unique ( $testreihesr );
      }
    } // foreach
      // print_r($testreihe);
      // print_r($testreihesr);
    $nr = count ( $testreihe );
    $sr = count ( $testreihesr );
    if ($nr > 0 and $sr > 0) {
      print $html;
      include "sitzordnung_NR.php";
      include "sitzordnung_SR.php";
    } else if ($nr > 0 and $sr == 0) {
      $html .= "Keine St&auml;nder&auml;te";
      print $html;
      include "sitzordnung_NR.php";
    } else if ($nr == 0 and $sr > 0) {
      $html .= 'Keine Nationalr&auml;te';
      print $html;
      include "sitzordnung2_SR.php";
    } else {
      $html .= "Keine Daten in dieser Abfrage";
      print $html;
    }
  }
} // else von ganz oben
  // Daten zum Fenster im Sitzpanel onmouseover
if (isset ( $_GET ['platz'] )) {
  $sitzplatz = $_GET ['platz'];
  $ratstyp = $_GET ['rat'];
  // print $sitzplatz.$ratstyp;//OK
  $sitzinfo = new Parlamentarier ( $db );
  $sitz = $sitzinfo->sitzplatz ( $ratstyp, $sitzplatz );
  // print_r($sitz);
  $html = "<div style='font:bold 13px Verdana;padding:1px'>";
  if (is_string ( $sitz )) {
    $html .= "$sitz </div>";
  } else if (is_array ( $sitz )) {
    foreach ( $sitz as $wert ) {
      $html .= "<img id='" . $wert ['id_parlam'] . "' src='./parlamentarierBilder/" . $wert ['kleinbild'] . "' style='text-align:center' /><br>";
      $html .= "{$wert['name']} {$wert['vorname']} <br>{$wert['partei']} {$wert['kanton']}<br>";
      $html .= "Lobbyfaktor: xyz <br>";
    }
  }
  $html .= "</div>";
  print $html;
}
// //Daten zum Fenster im Sitzpanel onclick
if (isset ( $_GET ['platznr'] )) {
  $sitzplatz = $_GET ['platznr'];
  $ratstyp = $_GET ['ratstyp'];
  $sitzinfo = new Parlamentarier ( $db );
  $sitz = $sitzinfo->sitzplatz ( $ratstyp, $sitzplatz );
  $html = "<div style='font:bold 13px Verdana;padding:1px'>";
  if (is_string ( $sitz )) {
    $html .= "$sitz </div>";
  } else if (is_array ( $sitz )) {
    foreach ( $sitz as $wert ) {
      $idp = $wert ['id_parlam'];
      $ibs = new Parlamentarier ( $db );
      $erg = $ibs->ibEinzelparlamentarier ( $idp );
      $zutritte = $ibs->zugangsberechtigungen ( $idp );
      $html = "<div  class='static' data-id={$wert['id_parlam']} style='float:left'>";
      $html .= "<img src='./parlamentarierBilder/" . $wert ['kleinbild'] . "' title='$wert[name]' style='margin:0 5px 20px 0;vertical-align:top;display:block;float:left' />";
      $html .= "<span style='width:150px'>{$wert['name']} {$wert['vorname']}<br> {$wert['ratstyp']}, {$wert['kanton']}, {$wert['partei']}, {$wert['parteifunktion']}<br>{$wert['kommission']}  </span></div>";
    }
    $html .= "<h4 style='clear:both;text-align:center' title='Rechtsformen: Stiftung (Stift), AG, Verein(Ve), GmbH\nSitiftungsrat (Sr), Verwaltungsrat(VR), Vorstand (V)\nM: (Mitglied), P: (Pr&auml;sident), VP (Vizepr&auml;sident '>Deklarierte Interessenbindungen </h4><ol class='interessenbindungensitz'>";

    foreach ( $erg as $wert ) {
      // deklariert
      if ($wert ['ib_status'] == 'd') {
        $ib [] = $wert ['ib_description'] . ' Bereich: ' . $wert ['lt_kategorie'] . "<img src='./icons/mouseclick_mini.jpg' />";
        $lorgnr [] = $wert ['id_lobbyorg'];
        // nicht-deklariert
      } else if ($wert ['ib_status'] == 'n') {
        $ibn [] = $wert ['ib_description'] . ' Bereich: ' . $wert ['lt_kategorie'] . "<img src='./icons/mouseclick_mini.jpg' />";
        $lorgnrn [] = $wert ['id_lobbyorg'];
      }
    }
    for($i = 0; $i < count ( $ib ); $i ++) {

      $html .= "<li id='$lorgnr[$i]' style='cursor:pointer'>$ib[$i]</li>";
    }
    // nicht-deklarierte
    if (count ( $ibn ) > 0) {
      $html .= "</ol><h4 style='text-align:center' title='Rechtsformen: Stiftung (Stift), AG, Verein(Ve), GmbH\nSitiftungsrat (Sr), Verwaltungsrat(VR), Vorstand (V)\nM: (Mitglied), P: (Pr&auml;sident), VP (Vizepr&auml;sident '>Weitere Interessenbindungen (Teledata u.a. Quellen)</h4><ol class='interessenbindungensitz'>";
      for($i = 0; $i < count ( $ibn ); $i ++) {

        $html .= "<li id='$lorgnrn[$i]' style='cursor:pointer'>$ibn[$i]</li>";
      }
    }
    if (is_array ( $zutritte )) {

      foreach ( $zutritte as $wert ) {
        $kat = $wert ['lt_kategorie'] != '' ? "Bereich: $wert[lt_kategorie]" : '';
        $zt [] = $wert ['berech_name'] . ' ' . $wert ['berech_vorname'] . ', ' . $wert ['berech_organisation'] . ' ' . $kat;
        if ($wert ['id_lobbyorg'] != '') {
          $lid [] = $wert ['id_lobbyorg'];
          // print_r($lid);//OK einer oder zwei drin
          // print_r($zt);//1-2 drin OK
        } else if ($wert ['id_lobbyorg'] == '') {
          $lid [] = 'leer';
        }
      }
      // print_r($lid);//OK einer oder zwei drin
    } else {
      $zt = "Keine aktuellen Zugangsberechtigungen";
    }
    $html .= "</ol><h4 style='text-align:center'>Zugangsberechtigungen ('G&ouml;tti')</h4><ul class='zugangsberechtigungen'>";
    // Problem: wenn nur eine Organisation mit Lobbytyp verbunden ist L&ouml;sung wahrscheinlich gut.
    if (is_array ( $zt )) {
      $z = 0;
      foreach ( $zt as $ber ) {
        if ($lid [$z] != 'leer') {
          // Zutritte mit Lobbyorg
          $html .= "<li style='cursor:pointer' id='" . $lid [$z] . "'>$ber <img src='./icons/mouseclick_mini.jpg' /></li>";
          $z ++;
        } else if ($lid [$z] == 'leer') {
          // Nur Zutritte ohne spezifische Lobbyorg
          $html .= "<li >$ber</li>";
          $z ++;
        }
      }
    } else {
      $html .= "<li>$zt</li>";
    }
    $html .= "</ul></div>";
  }

  /*
   * foreach($sitz as $wert){ $html .="<img id='".$wert['id_parlam']."' src='./parlamentarierBilder/".$wert['kleinbild']."' style='text-align:center' /><br>"; $html .="{$wert['name']} {$wert['vorname']} <br>{$wert['partei']} {$wert['kanton']}<br>"; $html .="Lobbyfaktor: xyz <br>"; } } $html .="</div>";
   */

  print $html;
}

// Nachtr&auml;gliche Suche bach IBS und Zugangsberechtigungen in Grppendarstellung der Parlamentarier
if (isset ( $_GET ['parid'] )) {
  $idparl = $_GET ['parid'];
  // print_r($idparl);
  $ibgroup = new Parlamentarier ( $db );
  $ibs = $ibgroup->ibEinzelparlamentarier ( $idparl );
  // $html="<ol class='interessenbindungen'>";
  foreach ( $ibs as $val ) {
    $html .= "<li style='cursor:pointer' id='{$val['id_lobbyorg']}'>{$val['ib_description']} Bereich: {$val['lt_kategorie']}</li>";
  }
  // $html .="</ol>";
  print $html;
}
// Nachtr&auml;gliche Suche nach Zugangsberechtigungen bei Gruppendarstellung Parlamentarier
if (isset ( $_GET ['paridzu'] )) {
  $idparl = $_GET ['paridzu'];
  // print_r($idparl);
  $zggroup = new Parlamentarier ( $db );
  $zgs = $zggroup->zugangsberechtigungenNachId ( $idparl );
  // print_r($zgs);
  if (is_string ( $zgs )) {
    $html = $zgs;
  } else if (is_array ( $zgs )) {
    foreach ( $zgs as $val ) {
      $html .= "<li> {$val['berech_name']} {$val['berech_vorname']}, {$val['berech_organisation']} Bereich: {$val['lt_kategorie']}</li>";
    }
  }
  // $html .="</ol>";
  print $html;
}

/**
 * **********************************************************************
 * Suchpanel Lobbyorganisationen
 *
 *
 * ***********************************************************************
 */
if (isset ( $_GET ['datatyp'] ) and $_GET ['datatyp'] == 'lobbyorg') {
  $abfr = new Parlamentarier ( $db );
  $lobbytyp = $abfr->lobbytypen ();
  // print_r ($lt);
  // $lobbygroup=$abfr->lobbygruppen();
  // print_r ($lg);

  $html = "<div id='lobbyorgsuche'><h3>Suchpanel Lobbyorganisationen</h3>";
  $html .= "<form name='lobbysuche' id='lobbysuche'>";
  $html .= "<ul>";
  $html .= "<li><label for='lobbyname'>LobbyOrg Quicksearch</label><br> <input type='text' name='lobbyname' id='lobbyname' /></li>";
  $html .= "<li><label for=bedeutung>Bedeutung</label><br> <select name='bedeutung' id='bedeutung' size='1' >";
  $html .= "<option value='alle'>alle</option>";
  $html .= "<option value='1'>hoch</option>";
  $html .= "<option value='2'>mittel</option>";
  $html .= "<option value='3'>gering</option>";
  $html .= "</select></li>";
  $html .= "<li><label for='lobbytyp'>Lobbytyp</label><br> <select name='lobbytyp' id='lobbytyp' size='1' >";
  $html .= "<option value='alle'>Alle</option>";
  foreach ( $lobbytyp as $val ) {
    $html .= "<option value={$val['id_lobbytyp']}>{$val['lt_kategorie']}</option>";
  }
  $html .= "</select></li>";
  $html .= "<li><label for='lobbygroup'>Lobbygruppe</label><br> <select name='lobbygroup' id='lobbygroup' size='1' >";
  $html .= "<option value='alle'>Alle</option>";
  $html .= "</select></li>";
  $html .= "<li><label for='gruppierung'>Gruppierung</label><br><input type='radio' id='gruppierung' name='gruppierung' value='gruppe' /> Gruppe ";
  $html .= "<input type='radio'  id='gruppierung' name='gruppierung' checked value='einzel' /> Einzel </li>";

  $html .= "<li><label for='darstellung'>Darstellung</label><br><input type='radio' id='darstellung' name='darstellung' value='tabelle' /> Tabelle ";
  $html .= "<input type='radio'  id='darstellung' name='darstellung' checked value='sitzordnung' /> Sitzordnung </li><br>";
  /*
   * $html .="<li><label for='thema'>Themensuche</label><br> <select name='thema' id='thema' size='1' >"; $html .="<option value='alle'>alle</option>"; $html .="</select></li><br>";
   */
  $html .= "<li><input type='submit' value='Suchen' /></li>";
  $html .= "</ul></form> </div>";
  print $html;
}
/**
 * *****************************************************************************************************************
 * Abfrage lobbyorganisationen
 * *****************************************************************************************************************
 */
if (isset ( $_POST ['bedeutung'] )) {
  $bedeutung = $_POST ['bedeutung'];
  $lobbytyp = $_POST ['lobbytyp'];
  $lobbygroup = $_POST ['lobbygroup'];
  $thema = $_POST ['thema'];
  $darstellung = $_POST ['darstellung'];
  $gruppierung = $_POST ['gruppierung'];
  // print $gruppierung;
  // print $darstellung;
  $lobbygruppe = new LobbyOrgSuche ( $db );
  $erg = $lobbygruppe->lobbyOrgGruppen ( $bedeutung, $lobbytyp, $lobbygroup, $thema );
  $anz = count ( $erg );
  // Suchinfo: Lobbytypen
  $einzelkat = new Parlamentarier ( $db );
  if ($lobbytyp != 'alle') {
    $lobtyp = $einzelkat->lobbytypen_einfach ( $lobbytyp );
  } else {
    $lobtyp = $lobbytyp;
  }
  // Suchinfo Lobbygruppen
  $einzelgruppe = new Parlamentarier ( $db );
  if ($lobbygroup != 'alle') {
    $logruppe = $einzelgruppe->lobbygruppen_einfach ( $lobbygroup );
  } else {
    $logruppe = $lobbygroup;
  }

  // Lobbyorgtyp besser Darstellen
  function lobbyorgtyp($orgtyp) {
    $orgar = explode ( ',', $orgtyp );
    $orgstr = '';
    for($i = 0; $i < count ( $orgar ); $i ++) {
      $orgstr .= $orgar [$i] . '<br>';
    }
    return $orgstr;
  }

  // print_r($erg);
  if (is_string ( $erg )) { // Keine daten in dieser Abfrage

    // print_r($gruppe);
    $html = "<h3> $erg</h3>";
  } else if (is_array ( $erg ) and $darstellung == 'tabelle') { // in der Tabellendarstellung wird die Gruppierung nicht ber&uuml;cksichtigt

    switch ($bedeutung) {
      case '1' :
        $bedeutung = 'hoch';
        break;
      case '2' :
        $bedeutung = 'mittel';
        break;
      case '3' :
        $bedeutung = 'gering';
        break;
      default :
        $bedeutung = 'alle';
    }

    $html = "<h3>Darstellung: einzel, Tabelle. Abfrage nach Bedeutung: $bedeutung Lobbytyp: $lobtyp Lobbygruppe: $logruppe  Anzahl: $anz </h3>";
    $html .= "<div id='lobbygruppen' >";
    $html .= "<table id='lobbyabfrage'><tr>";
    $html .= "<th>Name</th><th style='width:400px'>Zielsetzungen</th><th>Organisationstyp</th><th>Lobbytyp & Lobbygruppe</th><th>Vernehmlassung</th><th>Web</th><th>Parl. Verbindung</th></tr><tr>";
    foreach ( $erg as $val ) {
      $html .= "<td style='width:100px' id='{$val['id_lobbyorg']}'>{$val['lobbyname']}</td><td  ><p style='margin:0;width:400px;height:100px;overflow:auto'>{$val['lobbydescription']}</p></td>";
      // function lobbyorgtyp() einsetzen
      $lot = lobbyorgtyp ( $val ['lobbyorgtyp'] );

      // $lobbygruppe=$lobbygroup=='alle'?"Alle Lobbygruppen":"{$val['lg_bezeichnung']}";
      $html .= "<td style='width:150px'>$lot</td><td width='150'>{$val['lt_kategorie']}<br>Lobbygruppe: $logruppe</td><td width='100'>{$val['vernehmlassung']}</td><td width='50'><a href='{$val['weblink']}'>Weblink</a></td><td id='{$val['id_lobbyorg']}' data-darstellung='$darstellung' style='width:130px;cursor:pointer' class='parlamlink' >{$val['parlam_verbindung']} <img src='./icons/mouseclick_mini.jpg' /></td></tr><tr>";
    }
    $html .= "</tr><table></div>";

    print $html;
  } else if (is_array ( $erg ) and $gruppierung == 'einzel' and $darstellung == 'sitzordnung') {
    switch ($bedeutung) {
      case '1' :
        $bedeutung = 'hoch';
        break;
      case '2' :
        $bedeutung = 'mittel';
        break;
      case '3' :
        $bedeutung = 'gering';
        break;
      default :
        $bedeutung = 'alle';
    }
    $html = "<h3>Darstellung: $gruppierung, Sitzplan. Abfrage nach Bedeutung: $bedeutung Lobbytyp: $lobtyp Lobbygruppe: $logruppe  Anzahl: $anz </h3>";
    if (is_string ( $erg )) { // Keine daten in dieser Abfrage

      $html = "<h3>$erg</h3>";
    } else {
      $html .= "<div style='margin-bottom:30px'><form id='lobbys' name='lobbys' >";
      $html .= "<select id='einzellobbys' name='einzellobbys' >";
      $html .= "<option>Auswahl</option>";
      foreach ( $erg as $val ) {

        $html .= "<option value='{$val['id_lobbyorg']}'>{$val['lobbyname']}</option>";
      }
      $html .= "</select></form></div>";
      $html .= "<div id='sitze'>";
      print $html; /* wird ab Label:IB_sitzordnung ausgewertet */
    }
  } else if (is_array ( $erg ) and $gruppierung == 'gruppe' and $darstellung == 'sitzordnung') {
    switch ($bedeutung) {
      case '1' :
        $bedeutung = 'hoch';
        break;
      case '2' :
        $bedeutung = 'mittel';
        break;
      case '3' :
        $bedeutung = 'gering';
        break;
      default :
        $bedeutung = 'alle';
    }
    $html = "<h3>Darstellung: $gruppierung, Sitzplan. Abfrage nach Bedeutung: $bedeutung Lobbytyp: $lobtyp Lobbygruppe: $logruppe  Anzahl: $anz </h3>";
    if (is_string ( $erg )) { // Keine daten in dieser Abfrage

      $html = "<h3>$erg</h3>";
    } else {
      foreach ( $erg as $val ) {
        $orgid [] = $val ['id_lobbyorg'];
      }
      // print_r($orgid);OK
      // Datenbank nach Parlamentarieren abfragen
      $parlambindungen = new LobbyOrgSuche ( $db );

      foreach ( $orgid as $id ) {
        $parl [] = $parlambindungen->lobbyOrgParlam ( $id );
      }
      // print_r($parl) ;
      // Datenbank nach Zugangsberechtigungen abfragen
      foreach ( $orgid as $id ) {
        $zber [] = $parlambindungen->lobbyOrgParlamentZugang ( $id );
      }
      // print_r($zber);
      if (count ( $parl ) > 0) {
        error_reporting ( 0 );
        foreach ( $parl as $v ) {
          foreach ( $v as $w ) {

            if ($w ['ratstyp'] == 'NR') {
              $testreihe [] = $w ['sitzplatz'];
              $testreihe = array_unique ( $testreihe );
            } else if ($w ['ratstyp'] == 'SR') {
              $testreihesr [] = $w ['sitzplatz'];
              $testreihesr = array_unique ( $testreihesr );
            }
          } // foreach aussen
        } // foreach innen
      }
      if (count ( $zber ) > 0) {
        error_reporting ( 0 );
        foreach ( $zber as $v ) {
          foreach ( $v as $w ) {

            if ($w ['ratstyp'] == 'NR') {
              $testreihe [] = $w ['sitzplatz'];
              $testreihe = array_unique ( $testreihe );
            } else if ($w ['ratstyp'] == 'SR') {
              $testreihesr [] = $w ['sitzplatz'];
              $testreihesr = array_unique ( $testreihesr );
            }
          } // foreach aussen
        } // foreach innen
      }
      // print_r($testreihe);
      // print_r($testreihesr);
      $nr = count ( $testreihe );
      $sr = count ( $testreihesr );
      if ($nr > 0 and $sr > 0) {
        $html .= "<br>National-und St&auml;nder&auml;te";
        $html .= "</div>";
        print $html;
        include "sitzordnung_NR.php";
        include "sitzordnung_SR.php";
      } else if ($nr > 0 and $sr == '') {
        $html .= "<br>Keine St&auml;nder&auml;te";
        $html .= "</div>";
        print $html;
        include "sitzordnung_NR.php";
      } else if ($nr == '' and $sr > 0) {
        $html .= '<br>Keine Nationalr&auml;te';
        $html .= "</div>";
        print $html;
        include "sitzordnung2_SR.php";
      } else if ($nr == 0 and $sr == 0) {
        $html .= "Keine Daten in dieser Abfrage";
        $html .= "</div>";
        print $html;
      }
      // print $html;
    }
  }
}
// Tabelle, Einzel Suche nach parlamentarischer Verbindung aus Suchpergebnissen aus Suchpanel Lobbyorganisationen (#dialog)->2. Verwendung
if (isset ( $_POST ['lobbyId'] )) {
  $idlobby = $_POST ['lobbyId'];
  $sitzordnung = $_POST ['sitzordnung'];
  // print $sitzordnung;
  $parlambindungen = new LobbyOrgSuche ( $db );
  $erg = $parlambindungen->lobbyOrgParlam ( $idlobby );
  // print_r($erg);
  $zugang = $parlambindungen->lobbyOrgParlamentZugang ( $idlobby );
  // print_r($zugang);
  $anzahlbind = count ( $erg );
  $anzahlberech = count ( $zugang );

  $html = "<div id='parlambindungen'>";
  if (is_string ( $erg )) {
    $html .= "<div>$erg</div>";
  } else {

    $html .= "Anzahl <b>Bindungen</b> zu dieser Organisation: $anzahlbind <br>";
    foreach ( $erg as $val ) {
      $html .= "<b>{$val['name']} {$val['vorname']}</b> {$val['partei']} {$val['ratstyp']} {$val['kanton']} <br>";
      $html .= "{$val['kommission']}<br>";
    }
  }
  if (is_string ( $zugang )) {
    $html .= "$zugang <br>";
  } else {
    $html .= "Anzahl <b>Zutrittsberechtigungen</b> dieser Organisation: $anzahlberech <br>";
    foreach ( $zugang as $val ) {
      $html .= "<b>{$val['name']} {$val['vorname']}</b> {$val['partei']} {$val['ratstyp']} {$val['kanton']} <br>";
      $html .= "{$val['kommission']}<br>";
      $html .= "Zutritt von {$val['berech_name']} {$val['berech_vorname']}<br>";
    }
  }
  print $html;

  $html .= "</div>";
}
// Label:IB_Sitzordnung Sitzordnung von Organisationen: Parlamentarier f&uuml;r die Sitzordnung Einzelorganisationen
if (isset ( $_GET ['lobby'] )) {
  $lobbyid = $_GET ['lobby'];
  // print $lobbyid;
  $parlambindungen = new LobbyOrgSuche ( $db );
  $parl = $parlambindungen->lobbyOrgParlam ( $lobbyid );
  // print_r($parl);
  $zutritt = $parlambindungen->lobbyOrgParlamentZugang ( $lobbyid );
  // print_r($zutritt);
  $html = '';
  // ein logischer Fehler
  if (count ( $parl ) > 0) {
    error_reporting ( 0 );

    foreach ( $parl as $rat ) {

      if ($rat ['ratstyp'] == 'NR') {
        $testreihe [] = $rat ['sitzplatz'];
        $testreihe = array_unique ( $testreihe );
      } else if ($rat ['ratstyp'] == 'SR') {
        $testreihesr [] = $rat ['sitzplatz'];
        $testreihesr = array_unique ( $testreihesr );
      }
    } // foreach
  }
  // Zugangsberechtigungen
  if (count ( $zutritt ) > 0) {

    error_reporting ( 0 );
    foreach ( $zutritt as $verb ) {
      if ($verb ['ratstyp'] == 'NR') {
        $testreihe [] = $verb ['sitzplatz'];
        $testreihe = array_unique ( $testreihe );
      } else if ($verb ['ratstyp'] == 'SR') {
        $testreihesr [] = $verb ['sitzplatz'];
        $testreihesr = array_unique ( $testreihesr );
      }
    } // foreach
  }
  // print_r($testreihe);
  // print_r($testreihesr);
  $nr = count ( $testreihe );
  $sr = count ( $testreihesr );
  if ($nr > 0 and $sr > 0) {
    $html .= "<br>National-und St&auml;nder&auml;te";
    $html .= "</div>";
    print $html;
    include "sitzordnung_NR.php";
    include "sitzordnung_SR.php";
  } else if ($nr > 0 and $sr == '') {
    $html .= "<br>Keine St&auml;nder&auml;te";
    $html .= "</div>";
    print $html;
    include "sitzordnung_NR.php";
  } else if ($nr == '' and $sr > 0) {
    $html .= '<br>Keine Nationalr&auml;te';
    $html .= "</div>";
    print $html;
    include "sitzordnung2_SR.php";
  } else if ($nr == 0 and $sr == 0) {
    $html .= "Keine Daten in dieser Abfrage";
    $html .= "</div>";
    print $html;
  }
  // print $html;
}
// Suchpanel Lobbyorganisationen: Lobbytyp erzeugt Select-Optionen fuer Lobbygroup
if (isset ( $_GET ['optionen'] ) and $_GET ['optionen'] != 'alle') {
  $lotyp = $_GET ['optionen'];
  $lobbygroups = new Parlamentarier ( $db );
  $opts = $lobbygroups->lobbygruppen ( $lotyp );
  // print_r($opts);
  $html = '';
  if ($opts != '') {

    foreach ( $opts as $val ) {
      $html .= "<option value={$val['id_lobbygroup']}>{$val['lg_bezeichnung']}</option>";
    }
  } else {
    break;
  }
  print $html;
}
/**
 * ******************************************************************************************
 * Interface: Statistik
 * Funktionen: ???
 * Gewichtetes arithmetisches Mittel: gering 1, mittel 3, hoch 5 Maximum: 5.0
 * Formel: (g * 1 + m * 3 + h * 5)/ (g + m + h)= LObbyfaktor
 *
 * *******************************************************************************************
 */
if (isset ( $_GET ['datatyp'] ) and $_GET ['datatyp'] == 'statistik') {
  $html = "<div id='statistik'><h3>Statistisches Interface von LobbyControl </h3>";
  $html .= "Das statistische Interface von LobbyControl sieht eine Vielzahl von Abfragem&ouml;glichkeiten f&uuml;r die journalistische Recherche oder rein informativen Charakters vor.";
  // $html .="<h4>Sicht der Parlamentarier</h4>";
  $html .= "<table border='1' width='60%'>";
  $html .= "<tr><th colspan='4'>Parlamentarier</th></tr>";
  $html .= "<tr><td>Parlamentarier</td><td>ungewichtet</td><td>gewichtet</td></tr>";
  $html .= "</table>";
  // $html .="<h4>Sicht der Lobbyorganisationen</h4>";
  $html .= "<table border='1' width='60%'>";
  $html .= "<tr><th colspan='4'>Lobbyorganisation</th></tr>";
  $html .= "</table>";
  print $html;
}

if ((isset ( $_POST ['pname'] ) and strlen ( utf8_decode ( $_POST ['pname'] ) ) >= 2)) {
  // $variable = htmlentities( utf8_decode($_POST['variable']) ); // right
  $name = utf8_decode ( $_POST ['pname'] );
  // print $name;
  // Abfrage nach name
  $stat = new Statistik ( $db );
  $liste = $stat->getPnamen ( $name );
  // print_r($liste);
  $html = "<form id='parlauswahl' >";
  $html .= "<select id='statistikwahl' name='statistikwahl' >";
  foreach ( $liste as $eintrag ) {
    $html .= "<option value={$eintrag['id_parlam']}>{$eintrag['name']} {$eintrag['vorname']}</option>";
  }
  $html .= "</select></form>";

  print $html;
}

// Erstellung Daten f&uuml;r PIE ungewichtet Einzelparlamentarier
if (isset ( $_GET ['abfid'] )) {
  $id = $_GET ['abfid'];
  // print $id;
  $pieabfrage = new Statistik ( $db );
  $piedaten = $pieabfrage->ungewichtetEinzel ( $id );
  // print_r($piedaten);
  foreach ( $piedaten as $anz ) {
    $anzahl [] = $anz ['Anzahl'];
  }
  $anzahl = array_sum ( $anzahl );
  $html = "<div>Achtung, das ist experimentell </div>";
  $html .= "<h4>{$piedaten[0]['name']} {$piedaten[0]['vorname']}, {$piedaten[0]['ratstyp']} {$piedaten[0]['partei']}  {$piedaten[0]['kanton']} Interessenbindungen nach Lobbytypen absolut (ungewichtet) Anzahl: $anzahl <button id='draw'>DrawPie</button></h4>";
  // $html="<script>";
  foreach ( $piedaten as $val ) {
    $beschriftung [] = $val ['lt_kategorie'];
    $werte [] = $val ['Anzahl'] * 10;
  }

  $beschriftung = json_encode ( $beschriftung );
  $werte = json_encode ( $werte );
  // print ($beschriftung);
  // print($werte);
  $html .= "<script>";
  $html .= "var werte=" . $werte;
  $html .= ";var beschriftung=" . $beschriftung;
  // Global definierte Variablen
  $html .= ";var fps =30; ";
  $html .= "var animationSpeed =100; ";
  $html .= "var radius =50;";

  // var colors = ["#bed6c7","#adc0b4","#8a7e66","#a79b83","#bbb2a1"]
  $html .= "var colors=['red','blue','green','lightblue','yellow','magenta','lightgreen','silver','lightblue'];";
  $html .= "var wdh = 0; ";
  $html .= "var total = 0; ";
  $html .= "var i = 0; ";
  $html .= "var scale = 0 ;";
  $html .= "var startwinkel = 0;";
  $html .= "var endwinkel = 0;";
  // $html .="initPie();";
  $html .= "</script>";

  print $html;
}

/**
 * *****************************************************************************************************
 * Konzept LobbyControl: Datengrundlagen etc.
 * ******************************************************************************************************
 */
if (isset ( $_GET ['datatyp'] ) and $_GET ['datatyp'] == 'konzept') {
  $html = "<div id='konzept'><h2>Interessenbindungen im eidgen&ouml;ssischen Parlament</h2>";
  $html .= "<h3>AdressatInnen dieser Website</h3>";
  $html .= "Diese Website richtet sich an Journalisten und Journalistinnen, PolitologInnen, Organisationen und Einzelpersonen, welche sich mit der Wirkung von <b>Lobbyorganisationen auf die politische Meinungsbildung</b> befassen.<br>";
  $html .= "<h3>Datengrundlagen und -Quellen</h3>";
  $html .= "Das politische System der Schweiz beruht, wie alle andern auch auf einer Delegation von Machtbefugnissen.<br>";
  $html .= "Die gew&auml;hlten Parlamentsmitglieder repr&auml;sentieren in erster Linie das <b>Interessenspektrum</b> ihrer Partei.";
  $html .= " Hinzu kommen die individuellen Interessenbindungen an Organisationen, welche jedes Parlamentsmitglied nach Parlamentsgesetz transparent (&ouml;ffentlich) machen muss.";
  $html .= " In dieser Selbstdeklaration sind <b>Funktion</b> und <b>Status </b> in der jeweiligen Organisation anzugeben. Dieser Verpflichtung kommen jedoch nicht alle Parlamentarier gleichermassen nach. Deshalb ist die Liste der <b>deklarierten Interessenbindungen </b> von unterschiedlicher Qualit&auml;t.<br>";
  $html .= "Interessant sind also auch die <b>nicht-deklarierten Interessenbindungen.</b>";
  $html .= " Sie sind schwierig zu erheben und verlangen weitergehende Recherchen. Der gr&ouml;sste Teil davon sind im <b>Handelsregister</b> zu finden (z.B. teledata.ch), allerdings nur, wenn ein Handelsregistereintrag existiert. Verschiedene Organisationen (vor allem Vereine, Einzelfirmen etc.) haben keinen Handelsregistereintrag.<br>";
  $html .= "Eine weitere Datenquelle erschliesst sich aus dem <b>Zulassungssystem</b>, bestehend aus einer Liste von Personen, welche <b>Zutritt ins Parlamentsgeb&auml;ude</b> haben. Jeder Parlamentarier kann zwei Personen das Recht dazu geben. Das im Volksmund ('G&ouml;tti'-System) genannte Zulassungsverfahren wird jeden Monat neu erstellt.";
  $html .= "Aus dieser Liste ergeben sich ebenfalls aufschlussreiche Informationen, wer sich in der 'Lobby' des Parlaments frei bewegen kann.<br>Eine N&auml;here Analyse zeigt, dass das 'G&ouml;ttisystem' auch dazu verwendet wird, einzelnen Organisationsvertretern einen <b>Mehrfachstatus</b> an Zutritten zu verschaffen, welche mit den Interessenbindungen der einzelnen Ratsmitgliedern nichts zu tun haben.<br>";
  $html .= "Eine weitere interessante Datenquelle ist die Organisation selbst, welche in den meisten F&auml;llen eine Webadresse hat. Auch diese Datenquelle wird von LobbyControl ausgewertet.";
  $html .= "<h3>Von der Interessenbindung zur Lobbyorganisation</h3>";
  $html .= "Die Interessenbindung eines Ratsmitglieds zu einer bestimmten Organisation bedeutet f&uuml;r diese Organisation noch lange nicht, dass sie in der &ouml;ffentlichen Meinungsbildung einen <b>besonders bedeutsamen</b> Status erh&auml;lt. H&auml;ufig sind solche Interessenbindungen rein individuelle Vorlieben einzelner Parlamentarier, ohne jegliche Absicht, damit die Schweiz  nachhaltig zu ver&auml;ndern.<br> Viele Interessenbindungen sind also eher 'diffuse' Solidarit&auml;tsbekundungen von Parlamentariern, welche damit die Aufmerksamkeit f&uuml;r die  vertretene Organisation etwas erh&ouml;hen<br>";

  $html .= "LobbyControl beurteilt jede Interessenverbindung nach einem transparenten, nachvollziehbaren Schema:";
  $html .= "<ol><li>Der M&auml;chtigkeit im Meinungsbildungsprozess</li><li>Der Beziehungsdichte im Parlament</li></ol>";
  $html .= "Daraus ergibt sich die <b>Bedeutung</b> der jeweiligen Organisation.";

  print $html;
}
?>
