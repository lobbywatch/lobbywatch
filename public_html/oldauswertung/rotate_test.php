<?php
//Reihe 1
$html ="<div style='width:1040px;position:relative'>";
$anz=48;
$deg=180/$anz;
$radius=390;
//$left=15;
//$top=15;
$deg=round($deg,2);
$top=$radius;
$left=0;
//print $deg;



for($n=0;$n<=$anz;$n++){
  $left=$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 
 if($n <=5 OR $n==12 OR $n==24 OR $n==36 OR $n>42) {
 
 //print  round($left,2) .", ".round($top,2)."<br>";
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";

 /*else if($n==24){
 $html .="<div style='position:absolute;-moz-transform:rotate({0deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";*/
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
 }
 }
   $html .="</div>";
 //Reihe 2
 $html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=44;
 $deg=round((180/$anz),2);
 $radius=344;//44*10.8
 $toptop=45;
 $leftleft=45;
 
 for($n=0;$n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==0 OR $n==11 OR $n==22 OR $n==33 OR $n > 43 ) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
 }
 }
   //$html .="</div>";
   //Reihe 3
 //$html .="<div style='width:800px;position:relative';margin-top:50px>";
 $anz=40;
 $deg=round((180/$anz),2);
 $radius=300;//39*10.8
 $toptop=90;
 $leftleft=90;
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==0 OR $n==10 OR $n==20 OR $n==30 OR $n==40 ) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
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
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if( $n==8 OR $n>15) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=8){
 $deg +=0.022;
 $left -=2;//5
 $top +=2; 
  $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
 }
 }
 //Reihe 4 2. Haelfte
 $anz=17;
 $deg=round((90/$anz),2);//Stellschraube
 $radius=250;//317
 $toptop=130;//140
 $leftleft=133;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==8 OR  $n>15) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=8){
 $deg +=0.04; 
 $left -=2;
  $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";
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
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if( $n==6 OR $n>=12) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=6){
 $deg +=0.09;
 $left -=1;
 $top +=8; 
  $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
 }
 }
 //Reihe 5 2.Haelfte
 
  $anz=13;
 $deg=round((94/$anz),2);//Stellschraube
 $radius=210;//280
 $toptop=165;//165
 $leftleft=170;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 //Umgekehrte Darstellung: $n=rechts unten
 if($n==6 OR $n >=12  ) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=6){
 $deg +=0.09; 
 $left +=7;
 $top +=6;
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";
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
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if( $n==5 OR $n>=10) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=5){
$deg +=0.0;
$left +=2;
$top +=10; 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
 }
 }
 //Reihe 6 2.Haelfte
 
  $anz=11;
 $deg=round((97/$anz),2);//Stellschraube
 $radius=158;//220
 $toptop=215;//195
 $leftleft=230;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 //Umgekehrte Darstellung: $n=rechts unten
 if($n==5 OR $n >=10  ) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=5){
 $deg +=0.0;
$left -=1;
$top +=10; 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";
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
 
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius-($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 if($n==0 OR $n==3 OR $n>=6) {
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
 }else if($n >=3){
 $deg +=0.60;
 $left +=5;
 $top +=6; 
  $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>"; 
 }
 }
 //Reihe 7 2.Haelfte
 
  $anz=6;
 $deg=round((83/$anz),2);//Stellschraube
 $radius=97;
 $toptop=260;
 $leftleft=290;
 //top:139.34px;left:561.52px
 for($n=0; $n<=$anz;$n++){
  $left=$leftleft+$radius+($radius*(cos($n*pi()/180*$deg)));//x
  $top=$toptop+$radius-($radius*(sin($n*pi()/180*$deg)));//y
 $left=round($left,2);
 $top=round($top,2); 
 //Umgekehrte Darstellung: $n=rechts unten
 if($n==0 OR $n==3 OR $n>=6  ) {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:1px solid blue;top:{$top}px;left:{$left}px;visibility:hidden'><img src='./icons/sitz.png' /></div>";
}else if($n >=3){
 $deg +=0.60; 
 $left +=5;
 $top +=6;
$html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";  
 }else {
 
 $html .="<div style='position:absolute;-moz-transform:rotate({$deg}deg);-moz-transform-origine:0 0;width:;border:none;top:{$top}px;left:{$left}px;'><img src='./icons/sitz.png' /></div>";
}
} 
$html .="</div>";

 print $html; 
 
 
  
  //$L=(2*500)*pi()*(180/180);
  //print($L/40);
  function Sitzordnung($deg,$top){
  
  
  }
  
  
?>