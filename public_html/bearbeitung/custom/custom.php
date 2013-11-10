<?php
function setupRSS($page, $dataset) {
  $title = ucwords($page->GetCaption ());
  $table = str_replace ( '`', '', $dataset->GetName () );
  switch ($table) {
    case 'parlementarier' :
      $rss_title = '%vorname% %nachname% by %updated_visa%';
      $rss_body = 'Beruf: %beruf%<br>Kanton: %kanton%<br>Partei: %partei%';
      break;
    case 'interessenbindung' :
      $rss_title = '%beschreibung% by %updated_visa%';
      $rss_body = '';
      break;
    case 'zugangsberechtigung' :
      $rss_title = '%vorname% %nachname% by %updated_visa%';
      $rss_body = 'Funktion: %funktion%';
      break;
    case 'lobbyorganisation' :
      $rss_title = '%name% by %updated_visa%';
      $rss_body = '%beschreibung%<br>Typ: %typ%<br>Vernehmlassung: %vernehmlassung%';
      break;
    default :
      $rss_title = 'ID %id% by %updated_visa%';
      $rss_body = '';
  }
  
  $rss_body .= "<br>$title ID %id%<br>Updated by %updated_visa% at %updated_date%";
  
  $base_url = "http://$_SERVER[HTTP_HOST]";
  $generator = new DatasetRssGenerator ( $dataset, $title . ' RSS', $base_url, 'Änderungen der Lobbycontrol-Datenbank als RSS Feed', $rss_title, $table . ' at %id%', $rss_body );
  $generator->SetItemPublicationDateFieldName ( 'updated_date' );
  $generator->SetOrderByFieldName ( 'updated_date' );
  $generator->SetOrderType ( otDescending );
  
  return $generator;
}

function dc($msg) {
  print("<!-- $msg -->");
}

function dcXXX($msg) {
  // Disabled debug comment: do nothing
}































