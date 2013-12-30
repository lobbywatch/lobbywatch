<?php
  include_once 'utils.php';
?>
<!DOCTYPE html >
<html>
<head>
<title>Lobbywatch in Wartung</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="/favicon.png" type="image/png" />
<style></style>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-45624114-1', 'lobbywatch.ch');
  ga('send', 'pageview');
</script>
</head>
<body>
  <h1>Lobbywatch in Wartung</h1>
  <p><b>Wartungsarbeiten werden an Lobbywatch vorgenommen.</b></p>
  <p><b>Lobbywatch steht deshalb nicht zur Verfügung.</b></p>
  <p>
    <a href="/">Startseite</a><br>
    <a href="/wiki">Wiki</a><br/>
    <a href="/sites/lobbywatch.ch/app<?php print "$env_dir";?>lobbywatch_datenmodell_1page.pdf">Datenmodell (PDF)</a><br/>
  </p>
  <footer><p>Version: <?php print $version;?> / Deploy date: <?php print $deploy_date;?> / Build date: <?php print $build_date;?></p></footer>
  </body>
</html>
