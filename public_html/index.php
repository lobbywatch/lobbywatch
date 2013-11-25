<?php
require_once 'settings/settings.php';
include_once 'common/build_date.php';
?>
<!DOCTYPE html >
<html>
<head>
<title>LobbyControl <?php print "$env";?></title>
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
  <h1>Lobbycontrol <?php print "$env";?></h1>
  <?php if ($env === 'production' || $env === 'dev'): ?>
    <p><b>Dies ist die "richtige Seite" (production) zum Bearbeiten.</b></p>
    <p>Webseite und Datenmodell wurden teilweise zum Bearbeiten freigegeben.</p>
    <p><b>Die Tabellen <i>In_kommission</i> und <i>Kommission</i> können bearbeitet werden.</b></p>
    <p><b>Die anderen Änderungen gehen wieder verloren.</b></p>
  <?php endif; ?>
  <?php if ($env === 'test' || $env === 'dev'): ?>
    <p><b>Dies ist die Testseite. Alle Daten werden regelmässig überschrieben.</b></p>
  <?php endif;?>
  <p>
    <a href="/<?php print "$env_dir";?>auswertung">Auswertung</a> (Neue Daten, durchgestrichene Menus funktionieren nicht, andere rudimentär getestet)
    <br>
    <a href="/<?php print "$env_dir";?>bearbeitung/interessenbindung.php">Bearbeitung</a>
    <br>
    <a href="/oldauswertung">Alte Auswertung</a> (alte Daten, zum Vergleich/zur Kontrolle)
  </p>
  <h2>Umgebung</h2>
  <p>
    <a href="/">Lobbycontrol (Richtige Seite)</a>
    <br>
    <a href="/test/">Lobbycontrol (Testseite)</a>
  </p>
  <h2>Informationen</h2>
  <p>
    <a href="/<?php print "$env_dir";?>lobbycontrol_er.pdf">Datenmodell (PDF)</a><br/>
    <a href="/wiki/tiki-index.php?page=Wertebereiche">Wertebereiche auf Wiki</a><br/>
  </p>
  <p>
    <a href="/wiki">Wiki</a><br/>
  </p>
  <footer><p>Build date: <?php print $build_date;?></p></footer>
  </body>
</html>
