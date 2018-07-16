<!DOCTYPE html>
<html>
<head>
<?php // http://lobbywatch.dev/sites/lobbywatch.ch/app/status.php ?>
<title><?php print($_SERVER['HTTP_HOST']); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
</head>
<body>
<h1><?php print($_SERVER['HTTP_HOST']); ?></h1>

<h2>Import status</h2>
<pre>
<?php
$ansi_codes = array(
    "\e[0;30m" => '<span style="color:black;">',
    "\e[1;30m" => '<span style="color:black;weight:bold;">',
    "\e[0;32m" => '<span style="color:green;">',
    "\e[1;32m" => '<span style="color:green;weight:bold;">',
    "\e[0;31m" => '<span style="color:red;">',
    "\e[1;31m" => '<span style="color:red;weight:bold;">',
    "\e[0;44m" => '<span style="color:blue;">',
    "\e[1;44m" => '<span style="color:blue;weight:bold;">',
    "\e[0;43m" => '<span style="color:yellow;">',
    "\e[1;43m" => '<span style="color:yellow;weight:bold;">',
    "\e[1;32m" => '<span style="color:green;weight:bold;">',
    "\e[0m"   => '</span>',
);

$last_lines = ['', '', ''];
$fh = fopen(dirname(__FILE__) . '/run_update_ws_parlament.sh.log','r');
while ($line = fgets($fh)) {
  // echo($line);
  array_shift($last_lines);
  $html_line = str_replace(array_keys($ansi_codes), $ansi_codes, $line);
  $last_lines[] = $html_line;
}
fclose($fh);
print(implode("", $last_lines));
?>
</pre>

<h2>Server status</h2>
<p><?php echo shell_exec('uptime');?>
<?php // https://lokir.wordpress.com/2013/01/05/raspberry-pi-cpu-temperature-and-current-speed/ ?>
<p><?php echo shell_exec('MHZ0=$(cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq);MHZ1=$(cat /sys/devices/system/cpu/cpu1/cpufreq/scaling_cur_freq);MHZ2=$(cat /sys/devices/system/cpu/cpu2/cpufreq/scaling_cur_freq);MHZ3=$(cat /sys/devices/system/cpu/cpu3/cpufreq/scaling_cur_freq);TEMPE=$(cat /sys/class/thermal/thermal_zone0/temp);echo "CPU Temp $(($TEMPE/1000)) °C | CPU Speed $(($MHZ0/1000)) MHz"');?>
<p><pre style='overflow-x: auto;'><?php print(shell_exec('free -ht'));?></pre>
<p><pre style='overflow-x: auto;'><?php print(shell_exec('df -h'));?></pre>

<hr>
<?php // phpinfo(); ?>

<hr>

<?php print $_SERVER['SERVER_SOFTWARE']; ?>
</body>
</html>
