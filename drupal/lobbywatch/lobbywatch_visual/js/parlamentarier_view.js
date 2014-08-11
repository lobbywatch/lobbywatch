(function($) {

$(function() {
  
  var baseVisualPath = '/de/daten/grafik';

//  console.log("ready");

  $('#mainForm').on('change', 'input[type=checkbox]', function (e) {
     if (this.id == "chkFilterOnOff") {
        var filterOnOff = $('#chkFilterOnOff:checked').val() ? 1 : 0;

        // save location to hidden field
        var search = "?id=" + getParameterByName("id");
        search += "&" + getFilterCriteria();
        if (filterOnOff == "0") search += "&filterOnOff=0";
        window.location.href = location.protocol + "//" + location.host + location.pathname + search;
     }
  });

  //location.search
  var urlParlamentOverview = baseVisualPath + "/uebersicht?" + getFilterCriteria();
  $('#lnkParlamentOverview').attr('href', urlParlamentOverview);

  var filterOnOff = (getParameterByName("filterOnOff") != "0") ? true : false;

  $('#chkFilterOnOff').prop('checked', filterOnOff);


  var filterBranche = getParameterByName("filterBranche");
  var filterKommission = getParameterByName("filterKommission");
  var filterBrancheText = getParameterByName("filterBrancheText");
  var filterKommissionText = getParameterByName("filterKommissionText");
  var optionFunction1 = getParameterByName("optionFunction1") == "1" ? true : false;
  var optionFunction2 = getParameterByName("optionFunction2") == "1" ? true : false;
  var optionDeep1 = getParameterByName("optionDeep1") == "1" ? true : false;
  var optionDeep2 = getParameterByName("optionDeep2") == "1" ? true : false;

  var filterJSONData = "";
  /* TODO RKU filterJSONData += "&filterBranche=" + getParameterByName("filterBranche");
  filterJSONData += "&filterKommission=" + getParameterByName("filterKommission");
  filterJSONData += "&optionFunction1=" + getParameterByName("optionFunction1");
  filterJSONData += "&optionFunction2=" + getParameterByName("optionFunction2");
  filterJSONData += "&optionDeep1=" + getParameterByName("optionDeep1");
  filterJSONData += "&optionDeep2=" + getParameterByName("optionDeep2");
  filterJSONData += "&filterOrganisation=" + getParameterByName("filterOrganisation");
  filterJSONData += "&filterPerson=" + getParameterByName("filterPerson");*/

  var id = getParameterByName("id");
  // test bl
  if (id == null || id == "") {
     id = 7;

     filterOnOff = false;
  }

  if (filterOnOff == false) {
     filterBranche = "0";
     filterKommission = "0";
     filterBrancheText = "Alle";
     filterKommissionText = "Alle";
     optionFunction1 = true;
     optionFunction2 = true;
     optionDeep1 = true;
     optionDeep2 = true;

     filterJSONData = "";
     // TODO RKU filterJSONData += "&filter_branche_id=" + filterBranche;
     // TODO RKU filterJSONData += "&filter_kommission_id=" + filterKommission;
     // TODO RKU filterJSONData += "&optionFunction1=" + (optionFunction1 ? "1" : "0");
     // TODO RKU filterJSONData += "&optionFunction2=" + (optionFunction2 ? "1" : "0");
     // TODO RKU filterJSONData += "&optionDeep1=" + (optionDeep1 ? "1" : "0");
     // TODO RKU filterJSONData += "&optionDeep2=" + (optionDeep2 ? "1" : "0");
  }

  var optionText = "";
  if (optionDeep1) optionText += "direkt, ";
  if (optionDeep2) optionText += "Zutrittsberechtigte";
  optionText = optionText.trim();
  if (optionText.slice(-1) == ",") optionText = optionText.slice(0, -1);
  $("#cellCriteriaOptionsDeep").html(optionText);
  optionText = "";
  if (optionFunction1) optionText += "einfach, ";
  if (optionFunction2) optionText += "exekutiv";
  optionText = optionText.trim();
  if (optionText.slice(-1) == ",") optionText = optionText.slice(0, -1);
  $("#cellCriteriaOptionsFunction").html(optionText);
  $("#cellFilterOptionsBranche").html(filterBrancheText);
  $("#cellFilterOptionsKommission").html(filterKommissionText);

  // read JSON data
  var datasource = "/de/data/interface/v1/json/table/parlamentarier/aggregated/id/" + id + '?includeInactive=1' + filterJSONData;

//  console.log(datasource);
  //var urlData = "http://localhost/VisualLobby/" + datasource;
  //datasource = "http://localhost/VisualLobby/JFehrData_test2.json";
  var urlData = datasource;

  d3.json(urlData, function (error, parlamentarierData) {
//    console.log("json");
//    console.log(parlamentarierData);
    
     if (error) {
        // console.warn(error);
        alert("Error reading JSON data from");
     }
     if (!parlamentarierData) {
        alert("Error reading JSON data from");
        return;
     }

     var tooltip = Tooltip("relations", 230);

     var showDetails = function (d) {
        var content;
        content = '<p>' + d + '</p>';
        tooltip.showTooltip(content, d3.event);
     };

     var hideDetails = function (d, i) {
        tooltip.hideTooltip();
     };

     var str = parlamentarierData.data.nachname + ", " + parlamentarierData.data.vorname + " (" + parlamentarierData.data.id + ")";
     $("#lblSubjectDetail").html(str);

     var str = parlamentarierData.data.ratstyp + ", Partei: " + parlamentarierData.data.partei + ", Kanton: " + parlamentarierData.data.kanton;
     $("#lblSubjectDetail2").html(str);

     var viewerWidth = $(document).width();
     var viewerHeight = $(document).height();

     var rootStartX = 80;
     var rootStartY = 250;

     var organisationStartX = 250;
     var organisationStartY = 50;
     var organisationWidth = 140;
     var organisationHeight = 100;
     var organisationPadding = 20;

     var parlamentarierIconWidth = 100;
     var parlamentarierIconHeight = 100;
     var parlamentarierIconTextOffsetY = 60;
     var gastIconWidth = 100;
     var gastIconHeight = 100;
     var gastIconTextOffsetY = 60;
     var organisationIconWidth = 140;
     var organisationIconHeight = 100;
     var organisationIconTextOffsetY = 30;

     var gastStartX = 310;
     var gastStartY = 500;
     var gastPadding = 120;
     var mandatStartX = gastStartX + 150;
     var mandatStartY = gastStartY - 170;

     // calculate canvas size
     var canvasWidth = viewerWidth;
     var canvasHeight = viewerHeight;

     // console.log(parlamentarierData);
     var lstInteressenbindung = jsonPath(parlamentarierData, "$..data.interessenbindungen[*]");
     // console.log(lstInteressenbindung);
     if (lstInteressenbindung && lstInteressenbindung.length > 0) {
        var width = organisationStartX + (lstInteressenbindung.length * (organisationWidth + organisationPadding));
        canvasWidth = Math.max(canvasWidth, width + 20);
     }
     var lstZutrittsberechtigung = jsonPath(parlamentarierData, "$..data.zutrittsberechtigungen[*]");
     // console.log(lstZutrittsberechtigung);
     if (lstZutrittsberechtigung && lstZutrittsberechtigung.length > 0) {
        for (var iZutrittsberechtigung = 0; iZutrittsberechtigung < lstZutrittsberechtigung.length; iZutrittsberechtigung++) {
           var lstMandat = jsonPath(lstZutrittsberechtigung[iZutrittsberechtigung], "$..mandate[*]");
           // console.log(lstMandat);
           var width = mandatStartX + (lstZutrittsberechtigung.length * (organisationWidth + organisationPadding));
        }

        var height = gastStartY + ((lstZutrittsberechtigung.length) * (gastIconHeight + gastPadding));
        canvasHeight = Math.max(canvasHeight, height + 20);
     }

     var elem = d3.select("#chart");

     var canvas = elem.append("svg")
                 .attr("width", canvasWidth)
                 .attr("height", canvasHeight)
//                 .attr("fill", "#eeeeee")
//                 .attr("fill-opacity", "1.0")
                 ;
     
//     var raphElem = Raphael(elem);
//     raphElem.toFront();

     // test
     //canvas.append("rect")
     //   .attr("width", canvasWidth)
     //   .attr("height", canvasHeight)
     //   .attr("stroke", "#111111")
     //   .attr("fill", "none");


     var group = canvas.append("g");

     // Clear canvas background
     group.append("rect")
       .attr("width", canvasWidth)
       .attr("height", canvasHeight)
       .attr("style", "fill:white;stroke:lightblue;stroke-width:2;");
     
//     group.attr("opacity", "1.0");

     var nodeRoot = group.append("svg:image")
        .attr('x', rootStartX)
        .attr('y', rootStartY)
        .attr('width', parlamentarierIconWidth)
        .attr('height', parlamentarierIconHeight)
        .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/parlamentarier.gif");

     var sourceRightX = Number(nodeRoot.attr("x")) + parlamentarierIconWidth/2;
     var sourceRightY = Number(nodeRoot.attr("y"));
     var sourceBottomX = Number(nodeRoot.attr("x")) + parlamentarierIconWidth / 2;
     var sourceBottomY = Number(nodeRoot.attr("y")) + parlamentarierIconHeight;

     var lineHeight = 14;

     group.selectAll(".interessenbindung")
        .data(lstInteressenbindung)
        .enter()
        .append("svg:image")
           .attr("y", organisationStartY)
           .attr("x", function (d, i) {
              var posX = i * (organisationWidth + organisationPadding) + organisationStartX;
              return posX;
           })
           .attr("width", organisationIconWidth)
           .attr("height", organisationIconHeight)
           .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/organisation.gif")
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              // TODO RKU var options = "id=" + d.organisation.id + "&" + getFilterCriteria();
              var options = "id=" + d.organisation_id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/organisation?" + options;
           });

     group.selectAll(".interessenbindungLabel")
        .data(lstInteressenbindung)
        .enter()
        .append("text")
           .attr("class", "interessenbindungLabel")
           .attr("y", organisationStartY + organisationIconTextOffsetY)
           .attr("x", function (d, i) {
              var posX = i * (organisationWidth + organisationPadding) + (organisationWidth / 2) + organisationStartX;
              return posX;
           })
           .text(function (d, i) {
              // TODO RKU var txt = d.organisation.branche + " ";
              // TODO RKU txt += d.organisation.name_de;
              var txt = d.branche + " ";
              txt += d.name_de;
              return txt;
           })
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              // TODO RKU var options = "id=" + d.organisation.id + "&" + getFilterCriteria();
              var options = "id=" + d.organisation_id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/organisation?" + options;
           })
           .call(FitOrganisationTextToRect, organisationWidth - 4);

     var diag = d3.svg.diagonal()
        .source({ x: sourceRightX, y: sourceRightY })
        .target(function (d, i) {
           var x = (i * (organisationWidth + organisationPadding) + organisationStartX) + (organisationIconWidth / 2);
           var y = organisationStartY + organisationHeight;
           var pos = {};
           pos["x"] = x;
           pos["y"] = y;
           return pos;
        });

     group.selectAll("path")
        .data(lstInteressenbindung, function (d) {
           // key
           // TODO RKU var id = "interessenbindung" + "-" + d.organisation.id;
           var id = "interessenbindung" + "-" + d.organisation_id;
           return id;
        })
        .enter()
        .append("path")
        .attr("fill", "none")
        .attr("stroke", function (d, i) {
          // TODO RKU
//           var relationColor = "#37D6FA";   // default color
//           if (d.art == "vorstand" || d.art == "geschaeftsfuehrend") {
//              relationColor = "#FA5737";   // default color
//           }
           var relationColor = "#68B3C6";   // default color
           if (d.wirksamkeit == 'hoch') {
              relationColor = "#E20025";
           } else if (d.wirksamkeit == 'mittel') {
             relationColor = "#F49E00";
           } else {
             relationColor = "#68B3C6";
           }
           return relationColor;
        })
        .attr("stroke-width", 3)
        .attr("d", diag)
        .style("cursor", "pointer")
        .append("svg:title")
        .text(function (d, i) { return d.art; });

     // Zutrittsberechtigung

     group.selectAll(".zutrittsberechtigung")
        .data(lstZutrittsberechtigung)
        .enter()
        .append("svg:image")
           .attr('x', gastStartX)
           .attr('y', function (d, i) {
              var cy = (i * (gastIconHeight + gastPadding)) + gastStartY;
              return cy;
           })
           .attr('width', gastIconWidth)
           .attr('height', gastIconHeight)
           .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/gast.gif");

     group.selectAll(".zutrittsberechtigungLabel")
        .data(lstZutrittsberechtigung)
        .enter()
        .append("text")
           .attr("class", "zutrittsberechtigungLabel")
           .attr("y", function (d, i) {
              var y = (i * (gastIconHeight + gastPadding) + gastStartY) + gastIconTextOffsetY;
              return y;
           })
           .attr("x", gastStartX + gastIconWidth/2)
           .text(function (d, i) {
              var txt = d.nachname + ", " + d.vorname;
              return txt;
           })
           .call(FitTextToRect, gastIconWidth);

     var diagZutrittsberechtigung = d3.svg.diagonal()
        .source({ x: sourceBottomX, y: sourceBottomY })
        .target(function (d, i) {
           var x = gastStartX + gastIconWidth/2;
           var y = i * (gastIconHeight + gastPadding) + gastStartY;
           var pos = {};
           pos["x"] = x;
           pos["y"] = y;
           return pos;
        });

     group.selectAll(".zutrittsberechtigungpath")
        .data(lstZutrittsberechtigung)
        .enter()
        .append("path")
        .attr("class", "zutrittsberechtigungpath")
        .attr("fill", "none")
        .attr("stroke", function (d, i) {
           var relationColor = "#aaaaaa";   // default color
           return relationColor;
        })
        .attr("stroke-width", 3)
        .attr("d", diagZutrittsberechtigung)
        .style("cursor", "pointer")
        .append("svg:title")
        .text(function (d, i) { return d.funktion; });


     for (var iZutrittsberechtigung = 0; iZutrittsberechtigung < lstZutrittsberechtigung.length; iZutrittsberechtigung++) {
        var lstMandat = jsonPath(lstZutrittsberechtigung[iZutrittsberechtigung], "$..mandate[*]");

        if (lstMandat != null && lstMandat.length > 0) {
           group.selectAll(".mandat")
              .data(lstMandat, function (d) {
                 // key
                 var randNumber = Math.floor((Math.random() * 1000) + 1);
                 // TODO RKU var key = "mandat-" + randNumber + "-" + lstZutrittsberechtigung[iZutrittsberechtigung].id + "-" + d.organisation.id;
                 var key = "mandat-" + randNumber + "-" + lstZutrittsberechtigung[iZutrittsberechtigung].id + "-" + d.organisation_id;
                 return key;
              })
              .enter()
              .append("svg:image")
                 .attr('x', function (d, i) {
                    var posX = i * (organisationWidth + organisationPadding) + mandatStartX;
                    return posX;
                 })
                 .attr('y', function (d, i) {
                    var y = (iZutrittsberechtigung * (organisationIconHeight + gastPadding) + mandatStartY);
                    return y;
                 })
                 .attr('width', organisationIconWidth)
                 .attr('height', organisationIconHeight)
                 .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/organisation.gif")
                 .style("cursor", "pointer")
                 .on("click", function (d, i) {
                   // TODO RKU var options = "id=" + d.organisation.id + "&" + getFilterCriteria();
                    var options = "id=" + d.organisation_id + "&" + getFilterCriteria();
                    window.location.href = "OrganisationView.html?" + options;
                 });

           group.selectAll(".mandatLabel")
              .data(lstMandat, function (d) {
                 // key
                 var randNumber = Math.floor((Math.random() * 1000) + 1);
                 // TODO RKU var key = "mandat-" + randNumber + "-" + lstZutrittsberechtigung[iZutrittsberechtigung].id + "-" + d.organisation.id;
                 var key = "mandat-" + randNumber + "-" + lstZutrittsberechtigung[iZutrittsberechtigung].id + "-" + d.organisation_id;
                 return key;
              })
              .enter()
              .append("text")
                 .attr("class", "mandatLabel")
                 .attr("y", function (d, i) {
                    var y = ((iZutrittsberechtigung * (organisationIconHeight + gastPadding) + mandatStartY)) + organisationIconTextOffsetY;
                    return y;
                 })
                 .attr("x", function (d, i) {
                    var x = i * (organisationWidth + organisationPadding) + (organisationWidth / 2) + mandatStartX;
                    return x;
                 })
                 .text(function (d, i) {
                    // TODO RKU var txt = d.organisation.branche + " ";
                    // TODO RKU txt += d.organisation.name_de;
                    var txt = d.branche ? d.branche + " " : '';
                    txt += d.name_de;
                    return txt;
                 })
                 .style("cursor", "pointer")
                 .on("click", function (d, i) {
                    // TODO RKU var options = "id=" + d.organisation.id + "&" + getFilterCriteria();
                    var options = "id=" + d.organisation_id + "&" + getFilterCriteria();
                    window.location.href = baseVisualPath + "/organisation?" + options;
                 })
                 .call(FitOrganisationTextToRect, organisationWidth - 4);


           var diagMandat = d3.svg.diagonal()
              .source(function (d, i) {
                 var x = gastStartX + (gastIconWidth/2 + 5);
                 var y = iZutrittsberechtigung * (gastIconHeight + gastPadding) + gastStartY;
                 var pos = {};
                 pos["x"] = x;
                 pos["y"] = y;
                 return pos;
              })
              .target(function (d, i) {
                 var x = i * (organisationWidth + organisationPadding) + mandatStartX + (organisationIconWidth / 2);
                 var y = ((iZutrittsberechtigung * (organisationIconHeight + gastPadding) + mandatStartY)) + organisationHeight;
                 var pos = {};
                 pos["x"] = x;
                 pos["y"] = y;
                 return pos;
              });

           group.selectAll(".mandatpath")
              .data(lstMandat, function (d) {
                 // key
                 var randNumber = Math.floor((Math.random() * 1000) + 1);
                 // TODO RKU var key = "mandat-" + randNumber + "-" + lstZutrittsberechtigung[iZutrittsberechtigung].id + "-" + d.organisation.id;
                 var key = "mandat-" + randNumber + "-" + lstZutrittsberechtigung[iZutrittsberechtigung].id + "-" + d.organisation_id;
                 return key;
              })
              .enter()
              .append("path")
              .attr("class", "mandatpath")
              .attr("fill", "none")
              .attr("stroke", function (d, i) {
//                 var relationColor = "#37D6FA";   // default color
//                 if (d.art == "vorstand" || d.art == "geschaeftsfuehrend") {
//                    relationColor = "#FA5737";   // default color
//                 }
                  var relationColor = "#68B3C6";   // default color
                  if (d.wirksamkeit == 'hoch') {
                     relationColor = "#E20025";
                  } else if (d.wirksamkeit == 'mittel') {
                    relationColor = "#F49E00";
                  } else {
                    relationColor = "#68B3C6";
                  }
                 return relationColor;
              })
              .attr("stroke-width", 3)
              .attr("d", diagMandat)
              .style("cursor", "pointer")
              .append("svg:title")
              .text(function (d, i) { return d.art; });
        }
     }

     nodeRoot.moveToFront();
     if (nodeRoot) {
        var label = parlamentarierData.data.nachname + ", " + parlamentarierData.data.vorname
        var lines = wordwrap(label, 12);

        lineHeight = 15;

        var text = group.append("text")
           .attr("class", "parlamentarierLabel")
           .attr("y", rootStartY + parlamentarierIconTextOffsetY)
           .attr("x", rootStartX + parlamentarierIconWidth/2)
           .text(function (d, i) {
              var txt = parlamentarierData.data.nachname + ", " + parlamentarierData.data.vorname;
              return txt;
           })
           .call(FitTextToRect, parlamentarierIconWidth);
     }
  });
});

function getFilterCriteriaAll() {
  var options = "";

  var options = "optionFunction1=" + "1";
  options = options + "&optionFunction2=" + "1";
  options = options + "&optionDeep1=" + "1";
  options = options + "&optionDeep2=" + "1";
  options = options + "&optionDeep3=" + "1";
  options = options + "&filterBranche=" + "0";
  options = options + "&filterKommission=" + "0";
  options = options + "&filterBrancheText=" + "Alle";
  options = options + "&filterKommissionText=" + "Alle";

  return options;
}

function getFilterCriteria() {
  var options = "";

  var optionFunction1 = getParameterByName("optionFunction1");
  var optionFunction2 = getParameterByName("optionFunction2");
  var optionDeep1 = getParameterByName("optionDeep1");
  var optionDeep2 = getParameterByName("optionDeep2");
  var optionDeep3 = getParameterByName("optionDeep3");
  var filterBranche = getParameterByName("filterBranche");
  var filterKommission = getParameterByName("filterKommission");

  var filterBrancheText = getParameterByName("filterBrancheText");
  var filterKommissionText = getParameterByName("filterKommissionText");

  var filterOrganisation = getParameterByName("filterOrganisation");
  var filterPerson = getParameterByName("filterPerson");

  /* TODO RKU var options = "optionFunction1=" + optionFunction1;
  options = options + "&optionFunction2=" + optionFunction2;
  options = options + "&optionDeep1=" + optionDeep1;
  options = options + "&optionDeep2=" + optionDeep2;
  options = options + "&optionDeep3=" + optionDeep3;
  options = options + "&filterBranche=" + filterBranche;
  options = options + "&filterKommission=" + filterKommission;
  options = options + "&filterBrancheText=" + filterBrancheText;
  options = options + "&filterKommissionText=" + filterKommissionText;
  options = options + "&filterOrganisation=" + filterOrganisation;
  options = options + "&filterPerson=" + filterPerson;*/

  return options;
}

d3.selection.prototype.moveToFront = function () {
   return this.each(function () {
      this.parentNode.appendChild(this);
   });
};

function wrap(text, width) {
   text.each(function () {
      var text = d3.select(this),
          words = text.text().split(/\s+/).reverse(),
          word,
          line = [],
          lineNumber = 0,
          lineHeight = 1.1, // ems
          y = text.attr("y"),
          dy = parseFloat(text.attr("dy")),
          tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
      while (word = words.pop()) {
         line.push(word);
         tspan.text(line.join(" "));
         if (tspan.node().getComputedTextLength() > width) {
            line.pop();
            tspan.text(line.join(" "));
            line = [word];
            tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
         }
      }
   });
}

function FitTextToRect(text, width) {
   text.each(function () {
      var text = d3.select(this);
      var words = text.text().split(/\s+/).reverse();
      var word;
      var line = [];
      var lineNumber = 0;
      var lineHeight = 14;

      var posX = text.attr("x");
      var y = text.attr("y");
      var dy = lineHeight;
      var tspan = text
                  .text(null)
                  .append("tspan")
                  .attr("x", posX)
                  //.attr("y", y)
                  .attr("dy", dy);

      while (word = words.pop()) {
         line.push(word);
         tspan
            .text(line.join(" "));
         if (tspan.node().getComputedTextLength() > width) {
            line.pop();
            tspan
               .text(line.join(" "));
            line = [word];
            tspan = text.append("tspan")
                     .attr("x", posX)
                     .attr("dy", lineHeight)
                     .text(word);
         }
      }
   });
}

function FitOrganisationTextToRect(text, width) {
   text.each(function () {
      var text = d3.select(this);
      var organisationData = text.property("__data__");
      // TODO RKU var branche = organisationData.organisation.branche;
      // TODO RKU var company = organisationData.organisation.name_de;
      //console.log(organisationData);
      var branche = organisationData.branche;
      var company = organisationData.name_de;
      var words = text.text().split(/\s+/).reverse();
      var word;
      var line = [];
      var lineNumber = 0;
      var lineHeight = 14;

      var posX = text.attr("x");
      var y = text.attr("y");
      var dy = lineHeight;
      var tspan = text
                  .text(null)
                  .append("tspan")
                  .style("font-weight", "bold")
                  .attr("x", posX)
                  //.attr("y", y)
                  .attr("dy", dy);

      // Branche
      // TODO RKU words = organisationData.organisation.branche.split(/\s+/).reverse();
      if (organisationData.branche) {
        words = organisationData.branche.split(/\s+/).reverse();
        while (word = words.pop()) {
           line.push(word);
           tspan
              .text(line.join(" "));
           if (tspan.node().getComputedTextLength() > width) {
              line.pop();
              tspan
                 .text(line.join(" "));
              line = [word];
              tspan = text.append("tspan")
                       .style("font-weight", "bold")
                       .attr("x", posX)
                       //.attr("y", y)
                       .attr("dy", lineHeight)
                       .text(word);
           }
        }
     }
      // Company
      line = [];
      tspan = text
                 .append("tspan")
                 .attr("x", posX)
                 //.attr("y", y)
                 .attr("dy", dy);

   // TODO RKU words = organisationData.organisation.name_de.split(/\s+/).reverse();
      words = organisationData.name_de.split(/\s+/).reverse();
      while (word = words.pop()) {
         line.push(word);
         tspan
            .text(line.join(" "));
         if (tspan.node().getComputedTextLength() > width) {
            line.pop();
            tspan
               .text(line.join(" "));
            line = [word];
            tspan = text.append("tspan")
                     .attr("x", posX)
                     //.attr("y", y)
                     .attr("dy", lineHeight)
                     .text(word);
         }
      }
   });
}

})(jQuery);