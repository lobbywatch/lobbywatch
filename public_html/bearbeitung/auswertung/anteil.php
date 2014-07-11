<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Bearbeitungsanteil</title>
   <script src="scripts/d3.js"></script>

   <style type="text/css">
      body
      {
         background-color: #fff;
         border-top: solid 10px #000;
         color: #909090;
         font-size: .85em;
         font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
         margin: 0;
         padding: 0;
      }

      .slice path
      {
         stroke: #fff;
         stroke-width: 2px;
      }

      .textTop
      {
         /*font-family: 'Arial';
         font-weight: bold;*/
         font-size: 12pt;
      }

      .textBottom
      {
         /*font-family: 'Arial';
         font-weight: bold;*/
         font-size: 12pt;
      }

      h1, h2, h3,
      h4, h5, h6
      {
         color: #909090;
         margin-bottom: 0;
         padding-bottom: 0;
      }

      h1
      {
         font-size: 2em;
      }

      h2
      {
         font-size: 1.75em;
      }

      h3
      {
         font-size: 1.2em;
      }

      h4
      {
         font-size: 1.1em;
      }

      h5, h6
      {
         font-size: 1em;
      }

      .pageHeader
      {
         padding-left: 20px;
      }
   </style>
</head>
<body>
   <div id="pageHeader" class="pageHeader">
      <p>
         <h2>Bearbeitungsanteil</h2>
      </p>
      <p>
         <h3 id="pagetitle">Titel</h3>
      </p>
   </div>
   <div id="piechart">
   </div>

   <script type="text/javascript">
      // helper function: extract Request parameter
      function getParameterByName(name) {
         name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
         var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
             results = regex.exec(location.search);
         return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
      }


      // Aufruf:
      // http://localhost/VisualLobby/SimplyPieChart.html?option=ParlamentInKommissionStatus
      // http://localhost/VisualLobby/SimplyPieChart.html?option=ParlamentNachParteien

      // supported 'option' values:
      // option=ParlamentInKommissionStatus
      // option=ParlamentNachParteien

      var pageTitle = "";

      var option = getParameterByName("option");
      var id = parseInt(getParameterByName("id"), 10);
      if (!option) option = "bearbeitungsanteil";

      if (option == "bearbeitungsanteil") {
         pageTitle = "Bearbeitete Datensätze nach Personen (bearbeitung_abgeschlossen_visa)";
      } else if (option == "erstellungsanteil") {
         pageTitle = "Erstellte Datensätze nach Personen (created_visa)";
      } else if (option == "kommission") {
         pageTitle = "Bearbeitungs-Status aller Parlamentarier in Kommission " + (!isNaN(id) ? id : "") + " (Nationalrat und Ständerat)";
      } else if (option == "ParlamentNachParteien") {
         pageTitle = "Parlamentarier nach Parteien im Nationalrat";
      }

      d3.select("#pagetitle").text(pageTitle);

      var urlData = "GetData.php?option=" + option + (!isNaN(id) && id != null ? "&id=" + id : '');

      //alert (urlData);
      
      d3.json(urlData, function (error, pieData) {
         if (error) {
            console.warn(error);
         }
         if (!pieData) {
            alert("Error reading JSON data from: " + urlData);
            return;
         }

         var r = 200;
         var innerwidth = 600;
         var innerheight = 600;

         var total = d3.sum(pieData, function (d) {
            return d3.sum(d3.values(d));
         });

         // assign color if color is null
         //alert(JSON.stringify(pieData));
         var default_colors = d3.scale.category20();
         //alert(default_colors);
         for (var i = 0, colorIndex = 0 ; i < pieData.length; i++) {
            if (pieData[i].color === null) {
              pieData[i].color = default_colors(colorIndex++);
            }
         }

         // extract data with value > 0
         var pieDataWithoutZero = [];
         for (var i = 0 ; i < pieData.length; i++) {
            var item = pieData[i];
            if (pieData[i].value > 0) {
               pieDataWithoutZero.push(item);
            }
         }

         var elem = d3.select("#piechart");

         var canvas = elem.append("svg")
                     .attr("width", innerwidth)
                     .attr("height", innerheight);

         var group = canvas.append("g")
            .attr("transform", "translate(" + innerwidth / 2 + "," + innerheight / 2 + ")");

         var textTop = group.append("text")
             .attr("dy", ".35em")
             .style("text-anchor", "middle")
             .attr("class", "textTop")
             .text("TOTAL")
             .attr("y", -10);

         var textBottom = group.append("text")
             .attr("dy", ".35em")
             .style("text-anchor", "middle")
             .attr("class", "textBottom")
             .text(total)
             .attr("y", 10);

         var arc = d3.svg.arc()
            .innerRadius(r - 120)
            .outerRadius(r);

         var pie = d3.layout.pie()
            .value(function (d) {
               return d.value;
            })
            .startAngle(0)
            .endAngle(2 * Math.PI);

         var arcs = group.selectAll(".slice")
            .data(pie(pieDataWithoutZero))
            .enter()
            .append("g")
               .attr("class", "slice");

         arcs.append("path")
            .attr("d", arc)
            .attr("fill", function (d) {
               return d.data.color;
            })

         arcs.append("svg:text")
            .attr("transform", function (d) {
               return "translate(" + arc.centroid(d) + ")";
            })
            .attr("text-anchor", "middle")
            .attr("font-size", "12px")
            .attr("font-family", "Arial")
            .attr("font-weight", "bold")
            .text(function (d, i) { return d.data.value; });

         var legend = elem.append("svg")
            .attr("class", "legend")
            .attr("width", r)
            .attr("height", r * 2)
            .selectAll("g")
            .data(pieData)
               .enter().append("g")
               .attr("transform", function (d, i) { return "translate(0," + i * 20 + ")"; });

         legend.append("rect")
             .attr("width", 18)
             .attr("height", 18)
             .style("fill", function (d, i) {
                return d.color;
             });

         legend.append("text")
             .attr("x", 24)
             .attr("y", 9)
             .attr("dy", ".35em")
             .text(function (d) {
                return d.label + " (" + d.value + ")";
             });
      });
   </script>

</body>
</html>
