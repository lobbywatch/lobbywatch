<style>
.sitzplatz{cursor:pointer}
</style>
<?php

//$testreihe=array(186,185,194,195,196,197,198,200,201,202);
//$testreihe=array(0);
//Reihe 1
$html="<div id='nr' style='margin-left:0;'>";
$html .="<div style='width:1040px;position:relative;'>";
$anz=48;
$deg=180/$anz;
$radius=390;
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
 //Auswahl der Abfrage
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/sps_sitz.png' title='SPS'/>";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$platzbild}</div>"; 
 $sitzplatz++;
 }else if($n >12 AND $n <24){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/cvp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/cvp_sitz.png' title='CVP' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$platzbild}</div>"; 
$sitzplatz++;
 }else if($n >24 AND $n <36){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/fdp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/fdp_sitz.png' title='FDP' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$platzbild}</div>"; 
$sitzplatz++;
 }else if($n > 36){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}'  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'>{$platzbild}</div>"; 
$sitzplatz++;
 }
 }
   $html .="</div>";
 //Reihe 2
 $html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=44;
 $deg=round((180/$anz),2);
 $radius=342;//44*10.8
 $toptop=45;
 $leftleft=45;
 
 
 for($n=0;$n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==0 OR $n==11 OR $n==22 OR $n==33 OR $n > 43 ) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n <11) {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/sps_sitz.png' title='SPS' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz++; 
 }else if($n >11 AND $n <22) {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/cvp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/cvp_sitz.png' title='CVP' />";
 $html .="<div class='sitzplatz'  data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz++; 
 }else if(($n > 22 AND $n <26)OR ($n>26 AND $n < 33)){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/fdp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/fdp_sitz.png' title='FDP' />";
 
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz++;
 }else if($n==26){
 
 $html .="<div data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/einzel_sitz.png' title='unbesetzt' /></div>";
$sitzplatz++;
 
 }else if($n > 33){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz++; 
}
}
   //$html .="</div>";
   //Reihe 3
 //$html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=40;
 $deg=round((180/$anz),2);
 $radius=298;//39*10.8
 $toptop=90;
 $leftleft=90;
 
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==0 OR $n==10 OR $n==20 OR $n==30 OR $n==40 ) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n <10) {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/sps_sitz.png' title='SPS' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++; 
 } else if($n >10 AND $n <18){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/cvp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/cvp_sitz.png' title='CVP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++; 
 }else if($n >=18 AND $n <23){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruenliberale_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruenliberale_sitz.png' title='Gr&uuml;nliberale' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++; 
 }else if($n >=23 AND $n < 30){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/fdp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/fdp_sitz.png' title='FDP' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;
  }else if($n > 30){
  $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;
  }
  }
   $html .="</div>";
   //Reihe 4 1.Haelfte
 $html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=17;
 $deg=round((95/$anz),2);//Stellschraube
 $radius=250;//300
 $toptop=130;//140
 $leftleft=135;
 $sitzplatz=111;
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if( $n==8 OR $n>15) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n < 8){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/sps_sitz.png' title='SPS' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++; 
 
 }else if($n >8  ){
  $deg +=0.022;
 $left -=2;//5
 $top +=2; 
  if($n==9){
  $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruene_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruene_sitz.png' title='Gr&uuml;ne' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;
  }if($n >9 )
  $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruenliberale_sitz_auswahl.png' title='click for details'  class='auswahl' />":"<img src='./icons/gruenliberale_sitz.png' title='Gr&uuml;nliberale' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;   

 }
 }
 //Reihe 4 2. Haelfte
 $anz=17;
 $deg=round((90/$anz),2);//Stellschraube
 $radius=250;//317
 $toptop=130;//140
 $leftleft=133;
 $sitzplatz=142;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==8 OR  $n>15) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n < 8){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz--;
 }else if($n >8 AND $n <15){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/bdp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/bdp_sitz.png' title='BDP' />";
 $deg +=0.04; 
 $left -=2;
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
 $sitzplatz--;
  }if($n >13 AND $n <16){
  $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruenliberale_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruenliberale_sitz.png' title='Gruenliberale' />";
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
 $sitzplatz--;  

}
} 
$html .="</div>";
 //Reihe 5 1.haelfte
  
$html .="<div style='width:800px;position:relative';margin-top:50px>";

 $anz=13;
 $deg=round((94/$anz),2);//Stellschraube
 $radius=210;//280
 $toptop=165;//160
 $leftleft=180;
 $sitzplatz=143;
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if( $n==6 OR $n>=12) {
 
 $html .="<div  style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
  }else if($n < 6){
  $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/sps_sitz.png' title='SPS' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;  
 }else if($n >6){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruene_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruene_sitz.png' title='Gr&uuml;ne' />";
 $deg +=0.09;
 $left -=1;
 $top +=8; 
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;  
 /*}else {
 
 $html .="<div data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";
 $sitzplatz++; */
 }
 }
 //Reihe 5 2.Haelfte
 
  $anz=13;
 $deg=round((94/$anz),2);//Stellschraube
 $radius=210;//280
 $toptop=165;//165
 $leftleft=170;
 $sitzplatz=164;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 //Umgekehrte Darstellung: $n=rechts unten
 if($n==6 OR $n >=12  ) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
} else if($n <6 ){
$platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SPS' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz--;  
 
 }else if($n >6){
 
 $deg +=0.09; 
 $left +=7;
 $top +=6;
 }if($n==7){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>"; 
$sitzplatz--; 
 }if($n >7 AND $n <12) {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/bdp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/bdp_sitz.png' title='BDP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
 $sitzplatz--;
}
} 
$html .="</div>";
 //Reihe 6 1.haelfte
  
$html .="<div style='width:800px;position:relative';margin-top:50px>";

 $anz=11;
 $deg=round((97/$anz),2);//Stellschraube
 $radius=160;//238
 $toptop=215;//195
 $leftleft=230;
 
 $sitzplatz=165;
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if( $n==5 OR $n>=10) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n < 5){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/sps_sitz.png' title='SPS' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
  $sitzplatz++;  
 }else if($n >5){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruene_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruene_sitz.png' title='Gr&uuml;ne' />";
$deg +=0.0;
$left +=2;
$top +=10; 
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
 $sitzplatz++;  
 }else {
 
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/svp_sitz.png' /></div>";
$sitzplatz++; 
 }
 }
 //Reihe 6 2.Haelfte
 
  $anz=11;
 $deg=round((97/$anz),2);//Stellschraube
 $radius=158;//220
 $toptop=215;//195
 $leftleft=230;
 $sitzplatz=182;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 //Umgekehrte Darstellung: $n=rechts unten
 if($n==5 OR $n >=10  ) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 
 }else if($n >5){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $deg +=0.0;
$left -=1;
$top +=10; 
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz--;  
 }else {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz--;
} 
}
$html .="</div>";
 //Reihe 7 1.haelfte
  
$html .="<div style='width:800px;position:relative';margin-top:50px>";

 $anz=6;
 $deg=round((78/$anz),2);//Stellschraube
 $radius=102;
 $toptop=260;
 $leftleft=290;
 $sitzplatz=183;
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==0 OR $n==3 OR $n>=6) {
 
 $html .="<div class='sitzplatz' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
  
 }else if($n >3 AND $n <6 ){
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruene_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruene_sitz.png' title='Gr&uuml;ne' />";
 $deg +=0.60;
 $left +=5;
 $top +=6; 
  $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz++;  
 }else {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/gruene_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/gruene_sitz.png' title='Gr&uuml;ne' />";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz++; 
 }
 }
 //Reihe 7 2.Haelfte
 
  $anz=6;
 $deg=round((83/$anz),2);//Stellschraube
 $radius=97;
 $toptop=260;
 $leftleft=290;
 $sitzplatz=190;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 //Umgekehrte Darstellung: $n=rechts unten
 if($n==0 OR $n==3 OR $n>=6  ) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:1px solid blue;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/svp_sitz.png' /></div>";

}else if($n >=3){
$platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 $deg +=0.60; 
 $left +=5;
 $top +=6;
$html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>"; 
$sitzplatz--; 
 }else {
 $platzbild=in_array($sitzplatz,$testreihe)?"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />":"<img src='./icons/svp_sitz.png' title='SVP' />";
 
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'>{$platzbild}</div>";
$sitzplatz--;
}
}

$html .="</div>";
//1.Reihe:Stimmenz&auml;hler 6
$html .="<div style='width:160px;top:410px;left:330px;position:relative'>";
$toptop=500;
$leftleft=280;
$anz=6;
$sitzplatz=191;
$stimmenzaehler=array("<img src='./icons/cvp_sitz.png' />","<img src='./icons/sps_sitz.png' />","<img src='./icons/svp_sitz.png' />","<img src='./icons/sps_sitz.png' />","<img src='./icons/cvp_sitz.png' />","<img src='./icons/fdp_sitz.png' />");
$wahlbilder=array("<img src='./icons/cvp_sitz_auswahl.png' title='click for details' class='auswahl' />","<img src='./icons/sps_sitz_auswahl.png' title='click for details' class='auswahl' />","<img src='./icons/svp_sitz_auswahl.png'title='click for details' class='auswahl' />","<img src='./icons/sps_sitz_auswahl.png' class='auswahl' />","<img src='./icons/cvp_sitz_auswahl.png'  class='auswahl' />","<img src='./icons/fdp_sitz_auswahl.png'title='click for details' class='auswahl' />");
for($i=0;$i<$anz;$i++){
$platzbild=in_array($sitzplatz,$testreihe)?"{$wahlbilder[$i]}":"{$stimmenzaehler[$i]}";
$html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:5px;width:;border:none;'>{$platzbild}</div>";
$sitzplatz++;
}

$html .="</div>";
//2.Reihe vorn:3 BR 5 SZ 3 BR
$html .="<div style='width:500px;top:460px;left:90px;position:relative'>";
$anz=13;
$sitzplatz=197;
$stimmz=array(8=>"<img src='./icons/svp_sitz.png' />",9=>"<img src='./icons/fdp_sitz.png' />");
$stimmza=array(8=>"<img src='./icons/svp_sitz_auswahl.png' class='auswahl' />",9=>"<img src='./icons/fdp_sitz_auswahl.png' title='click for details' class='auswahl' />");
for($n=1;$n<=$anz;$n++){

if($n==4 OR $n==10  ) {
$html .="<div  style='float:left;margin-right:5px;width:;border:none;visibility:hidden'><img src='./icons/sitz.png' /></div>";
}else if( $n ==8 OR $n==9){
$platzbild=in_array($sitzplatz,$testreihe)?"{$stimmza[$n]}":"{$stimmz[$n]}";
$html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:5px;width:;border:none;'>$platzbild</div>";
$sitzplatz++;
}else if($n==7){
$html .="<div   style='float:left;margin-right:5px;width:;border:none;'><img src='./icons/rednerpult.jpg' /></div>";
}else{
$html .="<div  style='float:left;margin-right:5px;width:;border:none;'><img src='./icons/sitz.png' /></div>";
}
}
$html .="</div>";

//Reihe 3 vorn 3,3 2
$html .="<div style='width:800px;top:490px;left:-213px;position:relative;'>";
$anz=9;
$sitzplatz=199;
$praesidium=array(5=>"<img src='./icons/svp_sitz.png' />",6=>"<img src='./icons/gruene_sitz.png' />",8=>"<img src='./icons/cvp_sitz.png' />",9=>"<img src='./icons/einzel_sitz.png' />");
$praesidiuma=array(5=>"<img src='./icons/svp_sitz_auswahl.png' title='click for details' class='auswahl' />",6=>"<img src='./icons/gruene_sitz_auswahl.png' class='auswahl' />",8=>"<img src='./icons/cvp_sitz_auswahl.png' class='auswahl' />",9=>"<img src='./icons/einzel_sitz_auswahl.png'title='click for details' class='auswahl' />");
for($n=1;$n<=$anz;$n++){
 if($n==3 OR $n==7){
 $html .="<div  style='float:left;margin-right:5px;width:;border:none;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }if($n==4){
 
 $html .="<div  style='float:left;margin-right:30px;width:;border:none;'><img src='./icons/sitz.png' /></div>";
 }else if($n ==5 ){
 $platzbild=in_array($sitzplatz,$testreihe)?"{$praesidiuma[$n]}":"{$praesidium[$n]}";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:30px;width:;border:none;'>$platzbild</div>";
$sitzplatz++;
 }else if($n==6){
 $platzbild=in_array($sitzplatz,$testreihe)?"{$praesidiuma[$n]}":"{$praesidium[$n]}";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:10px;width:;border:none;'>$platzbild</div>";
$sitzplatz++;
 }else if($n >7){
 $platzbild=in_array($sitzplatz,$testreihe)?"{$praesidiuma[$n]}":"{$praesidium[$n]}";
 $html .="<div class='sitzplatz' data-sitzplatz='{$sitzplatz}' style='float:left;margin-right:5px;width:;border:none;'>$platzbild</div>";
$sitzplatz++;
 }else if($n==1 OR $n==2){
 $html .="<div  style='float:left;margin-right:5px;width:;border:none;'><img src='./icons/sitz.png' /></div>";
 }
 }
  $html .="</div></div>";

 print $html; 
 
 
 
  
  
?>