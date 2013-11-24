<?php
require_once 'settings/settings.php';
include_once 'common/build_date.php';
?>
<!DOCTYPE html >
<html>
<head>
<title>LobbyControl</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="favicon.png" type="image/png" />
<style></style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45624114-1', 'lobbycontrol.ch');
  ga('send', 'pageview');
</script>
</head>
<body>
  <h1>Lobbycontrol</h1>
  <p><b>Webseite und Datenmodell sind noch in der Entwicklung. Alle Daten werden regelmässig überschrieben.</b></p>
  <p><b>Deshalb noch keine dauerhaften Änderungen vornehmen.</b></p>
  <p>
    <a href="/auswertung">Auswertung</a> (Neue Daten, durchgestrichene Menus funktionieren nicht, andere rudimentär getestet)
    <br>
    <a href="/bearbeitung/interessenbindung.php">Bearbeitung</a>
    <br>
    <a href="/oldauswertung">Alte Auswertung</a> (alte Daten, zum Vergleich, zur Kontrolle)
  </p>
  <p>
    <a href="/lobbycontrol_er.pdf">Datenmodell (PDF)</a><br/>
    <a href="/wiki/tiki-index.php?page=Wertebereiche">Wertebereiche auf Wiki</a><br/>
  </p>
  <p>
    <a href="/wiki">Wiki</a><br/>
  </p>
  <footer><p>Build date: <?php print $build_date;?></p></footer>
  </body>
</html>
