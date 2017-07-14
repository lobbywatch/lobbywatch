<html>
<head>
<title><?php print($_SERVER['HTTP_HOST']); ?></title>
</head>
<body>
<h1><?php print($_SERVER['HTTP_HOST']); ?></h1>
<?php

$last_lines = ['', '', ''];
$fh = fopen(dirname(__FILE__) . '/run_update_ws_parlament.sh.log','r');
while ($line = fgets($fh)) {
  // echo($line);
  array_shift($last_lines);
  $last_lines[] = $line;
}
fclose($fh);
print('<p><pre>' . implode("", $last_lines) . '</pre></p>');
?>
<hr>
<?php print $_SERVER['SERVER_SOFTWARE']; ?>
</body>
</html>
