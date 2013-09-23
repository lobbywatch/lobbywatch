<style>
.sitzplatzsr{cursor:pointer}
</style>
<?php
//$sitzverteilung=array(0,1,1,1,2,2,2,3,3,3,4,4,4,3,3,3,3,1,1,1,1,3,3,3,3,3,3,3,2,2,2,1,0,1,1,4,4,4,4,5,6,6,2,2);
//$testreihesr=array(9,16,18,20,22,26,27,29,32,34,35,39,42);
//$testreihesr=array('0');
$standbild=array("<img src='./icons/gruene_sitz.png' title='Gr&uuml;ne'/>","<img src='./icons/sps_sitz.png' title='SPS'/>","<img src='./icons/cvp_sitz.png' title='CVP'/>","<img src='./icons/fdp_sitz.png' title='FDP'/>","<img src='./icons/svp_sitz.png' title='SVP'/>","<img src='./icons/bdp_sitz.png' title='BDP'/>","<img src='./icons/gruenliberale_sitz.png' title='Gr&uuml;mliberale'/>");
$auswahlbild=array("<img src='./icons/gruene_sitz_auswahl.png' title='click for details' class='auswahlsr' />","<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahlsr' />","<img src='./icons/cvp_sitz_auswahl.png' title='click for details' class='auswahlsr' />","<img src='./icons/fdp_sitz_auswahl.png' title='click for details' class='auswahlsr' />","<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahlsr' />","<img src='./icons/bdp_sitz_auswahl.png' title='click for details' class='auswahlsr' />","<img src='./icons/gruenliberale_sitz_auswahl.png' title='click for details' class='auswahlsr' />");
//1. reihe 4,3,3,4
$html ="<div id='sr' style=';margin-top:0;margin-left:180px'>";
$html .="<div style='width:1040px;position:relative'>";
$anz=25;
$deg=173/$anz;
$radius=230;
//$left=15;
//$top=15;
$deg=round($deg,2);
$top=100;
$left=0;
$sitzplatz=1;
//print $deg;



for($n=1;$n<=$anz;$n++){
  $left=$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 
 if(($n >8 AND $n <18) OR $n==5 OR $n==21) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' title='SPS'/></div>";
 }else if($n==1){//Gruene
 $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[0]} ":"{$standbild[0]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
  }else if($n > 1 AND $n < 5){//SPS
  $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[1]}":"{$standbild[1]} ";
  $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
  }else if(($n >4 AND $n <9)OR($n >20) ){//CVP
  $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[2]}":"{$standbild[2]} ";
  $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
 
 }else if($n > 17 OR $n <20){//FDP
 $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[3]}":"{$standbild[3]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
 
 }
 }
   $html .="</div>";
//2.Reihe 3,4,4,3
$html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=21;
 $deg=round((171/$anz),2);
 $radius=185;//44*10.8
 $toptop=45;
 $leftleft=45;
 $sitzplatz=15;
 
 
 for($n=1;$n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if(($n >8 AND $n <14) OR $n==4 OR $n==18 ) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n <6 ){//SPS
 $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[1]}":"{$standbild[1]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0   0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n >5 AND $n < 9 ){//FDP
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[3]}":"{$standbild[3]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0   0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
 }else if($n >13 AND $n < 18){//FDP
 $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[3]}":"{$standbild[3]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0   0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n >18){//CVP
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[2]}":"{$standbild[2]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0   0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
 }
 }
   $html .="</div>";
//3.Reihe   
$html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=17;
 $deg=round((168/$anz),2);
 $radius=140;//44*10.8
 $toptop=90;
 $leftleft=90;
 $sitzplatz=29;
 
 for($n=1;$n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==3 OR $n==9 OR $n==15 ) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if(($n==1) OR ($n >3 AND $n <6)){//SPS
 $bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[1]}":"{$standbild[1]} ";
 
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n==2) {//Gruene
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[0]}":"{$standbild[0]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n>5 AND $n < 12) {//SVP
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[4]}":"{$standbild[4]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n==12) {//BDP
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[5]}":"{$standbild[5]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n>12 AND $n < 15) {//Gruenliberale
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[6]}":"{$standbild[6]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
}else if($n>14) {//CVP
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[2]}":"{$standbild[2]} ";
 $html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$bild}</div>"; 
$sitzplatz++;
 }
 }
   $html .="</div>";
//Front: Reihe 1 /8 Sitze/ 2 Stimmenz&auml;hler
$html .="<div style='width:300px;top:250px;left:130px;position:relative'>";
$anz=10;
$sitzplatz=43;
$stimmz=array(5=>"<img src='./icons/sps_sitz.png'/>",6=>"<img src='./icons/svp_sitz.png' />");
for($i=1;$i<=$anz;$i++){
if($i==4 OR $i==7){
$html .="<div  style='float:left;margin-right:5px;width:;border:none;visibility:hidden'><img src='./icons/sitz.png' /></div>";
}else if( $i ==5 ){
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[1]}":"{$standbild[1]} ";
$html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:5px;width:;border:none;'>{$bild}</div>";
$sitzplatz++;
}else if( $i ==6 ){
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[4]}":"{$standbild[4]} ";
$html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:5px;width:;border:none;'>{$bild}</div>";
$sitzplatz++;

}else{
$html .="<div  style='float:left;margin-right:5px;width:;border:none;'><img src='./icons/sitz.png' title='Bundesrat'/></div>";
}
}
$html .="</div>";
//$html .="<span style='clear:both'></span>";
//reihe 2 vorn
$html .="<div style='width:600px;top:280px;left:-100px;position:relative'>";
$anz=7;
$sitzplatz=45;
//$srpraes=array(4=>"<img src='./icons/fdp_sitz.png' />",6=>"<img src='./icons/cvp_sitz.png' />");
//$stimmz=array(5=>"<img src='./icons/sps_sitz.png'/>",6=>"<img src='./icons/svp_sitz.png' />");
for($i=1;$i<=$anz;$i++){
if($i==3 OR $i==5){
$html .="<div  style='float:left;margin-right:5px;width:;border:none;visibility:hidden'><img src='./icons/sitz.png' /></div>";
}else if( $i ==4 ){
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[3]}":"{$standbild[3]} ";
$html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}' style='float:left;margin:0 15px;width:;border:none;'>{$bild}</div>";
$sitzplatz++;
}else if($i==6){
$bild=in_array($sitzplatz,$testreihesr)?"{$auswahlbild[2]}":"{$standbild[2]} ";
$html .="<div class='sitzplatzsr' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:5px;width:;border:none;'>{$bild}</div>";
}else{
$html .="<div  style='float:left;margin-right:7px;width:;border:none;'><img src='./icons/sitz.png' title=''/></div>";
}
}
$html .="</div></div>";
 

print $html;



?>