<?php
$html ="<div style='width:1040px;position:relative'>";
$anz=32;
$deg=180/$anz;
$radius=300;
//$left=15;
//$top=15;
$deg=round($deg,2);
$top=$radius;
$left=0;
$sitzplatz=1;
//print $deg;



for($n=0;$n<=$anz;$n++){
  $left=$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 
 if($n <=5 OR $n==12 OR $n==24 OR $n==36 OR $n>42) {
 

 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";

 /*else if($n==24){
 $html .="<div style='position:absolute;-moz-transform:rotate({0deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";*/
 }else if($n < 12){ 
 $html .="<div data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' title='SPS'/></div>"; 
 $sitzplatz++;
 }else if($n >12 AND $n <24){
  $html .="<div data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' title='CVP' /></div>"; 
$sitzplatz++;
 }else if($n >24 AND $n <36){
  $html .="<div data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' title='FDP' /></div>"; 
$sitzplatz++;
 }else if($n > 36){
 $html .="<div data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'><img src='./icons/.png' title='SVP'/></div>"; 
$sitzplatz++;
 }
 }
   $html .="</div>";
print $html;



?>