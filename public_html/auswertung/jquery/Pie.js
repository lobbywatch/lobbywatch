//Kuchendiagramm (PIE)
// Global definierte Variablen
/*var fps = 30; // frames per second
var animationSpeed = 100; // je kleiner, desto schneller
var radius = 50; // Radius der Torte in Pixeln
//var werte = [50 , 110, 140, 60, 90]; //
//var werte=[1,4,5,15]
//var beschriftung = ["Dell", "HP", "Apple", "Acer", "LG"];
//var colors = ["#bed6c7","#adc0b4","#8a7e66","#a79b83","#bbb2a1"]
var colors=['red','blue','green','lightblue','yellow'];

// Deklarationen
var wdh = 0; // Animationsdurchläufe
var total = 0; //Summe aller Werte
var i = 0; // Zählvariable der Tortenstücke
var scale = 0;
var startwinkel = 0;
var endwinkel = 0;*/


function initPie() {	
	context = document.getElementById("canvasElement").getContext('2d');

	// Summe aller Werte berechnen
	for (var z=0; z < werte.length; z++) {
	 total += werte [z];	
	}	
	//startAnimation();
	startAnimation();
}


function drawPie  () {		
	// einen neuen Pfad initialisieren
	context.beginPath();
	// Startposition festlegen 
	context.moveTo(200, 150);
		
	// Summe bisher gezeichneter Segmente
			summe = 0;
			for (var j=0; j <= i; j++) {
				 summe += werte [j];
			}

		startwinkel = toRad((summe - werte[i]) / total * 360);
		endwinkel = toRad(summe  / total * 360);
		aktuellerwinkel = startwinkel + (endwinkel - startwinkel) * scale;

	
	context.arc(200, 150, radius, startwinkel, aktuellerwinkel,false);
	context.closePath();

	context.fillStyle = colors[i];
	context.fill();
	
}


function looptime () {
	wdh++;
    scale = wdh / animationSpeed *  total / werte[i];

       // Ende?
       if(scale >= 1) {
		   drawPie(); // Animation ein letztes Mal durchführen
           scale = 0; // Am Ende zurücksetzen
		   wdh = 0;
           clearInterval(timer); // Schleife abbrechen
		   showLegend(); // Legende anzeigen
		   i++; // Segmentzähler hochzählen
		   
		   // gibt es noch weitere Segmente zum Darstellen?
			if (i < werte.length){
				startAnimation ()	
			}		
      } else {
	      drawPie();
	  }
}


function startAnimation() {
       timer = setInterval(looptime, 1000 / fps );
}
   

function showLegend () {
	strCode = "<tr class='werte'><td style='width: 25px; background-color:"+colors[i]+"'></td><td>"+beschriftung[i]+"</td><td> "+werte[i]/10+"</td></tr>";
	document.getElementById('legende').innerHTML += strCode;
}
// Umrechung Grad -> Radiant
function toRad (x) {
	return (x*Math.PI)/180;	
}
function clearCanvas(){

//alert(context.width);//undefined
  //context.width = context.width;
  context.clearRect(0, 0, 300, 300);
  }