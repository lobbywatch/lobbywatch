(function($) {

$(function() {
  
//  console.log("ready");
  
  var baseVisualPath = '/de/daten/grafik';

  //location.search
  var urlParlamentOverview = baseVisualPath + "/uebersicht?" + getFilterCriteria();
  $('#lnkParlamentOverview').attr('href', urlParlamentOverview);

  var filterOptions = "";
  // TODO RKU filterOptions += "&filterBranche=" + getParameterByName("filterBranche");
  // TODO RKU filterOptions += "&filterKommission=" + getParameterByName("filterKommission");
  // TODO RKU filterOptions += "&filterOrganisation=" + getParameterByName("filterOrganisation");
  // TODO RKU filterOptions += "&filterPerson=" + getParameterByName("filterPerson");

  // read JSON data
  var id = getParameterByName("id");
  // test bl
  // TODO RKU if (id == null || id == "") {
  // TODO RKU    id = 19; //12, 94, 98, 154, 261, 411; Mandat: 19, 263, 349, 360, 390
  // TODO RKU    filterOptions = "";
  // TODO RKU    filterOptions += "&filterBranche=" + "0";
  // TODO RKU    filterOptions += "&filterKommission=" + "0";
  // TODO RKU }


  datasource = "/de/data/interface/v1/json/table/organisation/aggregated/id/" + id + filterOptions;

  // console.log(datasource);

  var urlData = datasource;

  d3.json(urlData, function (error, response) {
     if (error) {
        // console.warn(error);
        alert("Error reading JSON data from");
     }
     var organisationData = response.data;
     if (!organisationData) {
        alert("Error reading JSON data from");
        return;
     }

     // console.log(organisationData);

     var str = organisationData.name_de + " ("  + organisationData.id + ")";
     $("#lblSubjectDetail").html(str);

     var str = "Branche: " + organisationData.branche;

     /* TODO RKU if (organisationData.interessengruppe_list.length > 0) {
        str += ", Interessengruppe(n): ";
        for (var i = 0; i < organisationData.interessengruppe_list.length; i++) {
           str += organisationData.interessengruppe_list[i].name;
           if (i < organisationData.interessengruppe_list.length - 1) str += ", ";
        }
     }*/
     $("#lblSubjectDetail2").html(str);

     var viewerWidth = $(document).width();
     var viewerHeight = $(document).height();

     var rootStartX = 80;
     var rootStartY = 190;

     var parlamentarierIconWidth = 100;
     var parlamentarierIconHeight = 100;
     var parlamentarierIconTextOffsetY = 60;
     var gastIconWidth = 100;
     var gastIconHeight = 100;
     var gastIconTextOffsetY = 60;
     var organisationIconWidth = 140;
     var organisationIconHeight = 100;
     var organisationIconTextOffsetY = 30;

     var personR = 60;
     var parlamentarierStartX = 310;
     var parlamentarierStartY = 50;
     var parlamentarierPadding = 20;

     var zutrittsberechtigteStartX = 310;
     var zutrittsberechtigteStartY = rootStartY + 160;
     var zutrittsberechtigtePadding = 20;

     var zutrittsberechtigteParlamentarierStartX = zutrittsberechtigteStartX + 20;
     var zutrittsberechtigteParlamentarierStartY = zutrittsberechtigteStartY + 140;
     var zutrittsberechtigteParlamentarierPadding = zutrittsberechtigtePadding;

     var organisationWidth = 140;
     var organisationHeight = 100;
     var lineHeight = 14;


     var elem = d3.select("#chart");

     var canvas = elem.append("svg")
                 .attr("width", viewerWidth)
                 .attr("height", viewerHeight)
                 .attr("fill", "#eeeeee");

     var group = canvas.append("g");

     // Clear canvas background
     group.append("rect")
       .attr("width", viewerWidth)
       .attr("height", viewerHeight)
       .attr("style", "fill:white;stroke:lightblue;stroke-width:2;");

     var nodeRoot = group.append("svg:image")
        .attr("y", rootStartY)
        .attr("x", rootStartX)
        .attr("width", organisationIconWidth)
        .attr("height", organisationIconHeight)
        .attr("xlink:href", "images/organisation.gif")

     //var nodeRoot = group.append("rect")
     //   .attr("class", "root")
     //   .attr("rx", 10)
     //   .attr("rx", 10)
     //   .attr("width", organisationWidth)
     //   .attr("height", organisationHeight)
     //   .attr("x", rootStartX)
     //   .attr("y", rootStartY);

     var text = group.append("text")
        .attr("class", "organisationLabel")
        .attr("y", rootStartY + organisationIconTextOffsetY)
        .attr("x", rootStartX + organisationWidth/2)
        .text(function (d, i) {
           var txt = organisationData.name_de + " ("  + organisationData.id + ")";
           return txt;
        })
        .call(FitTextToRect, organisationWidth - 4);


     var sourceRightX = Number(nodeRoot.attr("x")) + organisationIconWidth / 2;
     var sourceRightY = Number(nodeRoot.attr("y"));
     var sourceRightX2 = Number(nodeRoot.attr("x")) + organisationIconWidth / 2;
     var sourceRightY2 = Number(nodeRoot.attr("y")) + organisationIconHeight;

     // Parlamentarier
     var lstParlamentarier = jsonPath(organisationData, "$.parlamentarier[*]");

     // console.log(organisationData);
     // console.log(lstParlamentarier);
     group.selectAll(".parlamentarier")
        .data(lstParlamentarier)
        .enter()
        .append("svg:image")
           .attr('x', function (d, i) {
              var posX = i * (parlamentarierIconWidth + parlamentarierPadding) + parlamentarierStartX;
              return posX;
           })
           .attr('y', parlamentarierStartY)
           .attr('width', parlamentarierIconWidth)
           .attr('height', parlamentarierIconHeight)
           .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/parlamentarier.gif")
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              var options = "id=" + d.id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/parlamentarier?" + options;
           });

     group.selectAll(".parlamentarierLabel")
        .data(lstParlamentarier)
        .enter()
        .append("text")
           .attr("class", "parlamentarierLabel")
           .attr("y", parlamentarierStartY + parlamentarierIconTextOffsetY)
           .attr("x", function (d, i) {
              var posX = i * (parlamentarierIconWidth + parlamentarierPadding) + parlamentarierStartX + parlamentarierIconWidth / 2;
              return posX;
           })
           .text(function (d, i) {
              var txt = d.nachname + ", " + d.vorname;
              txt += ", " + d.partei;
              return txt;
           })
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              var options = "id=" + d.id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/parlamentarier?" + options;
           })
           .call(FitParlamentarierTextToRect, parlamentarierIconWidth);

     var diagInteressenbindung = d3.svg.diagonal()
        .source({ x: sourceRightX, y: sourceRightY })
        .target(function (d, i) {
           var x = i * (parlamentarierIconWidth + parlamentarierPadding) + parlamentarierStartX + parlamentarierIconWidth / 2;
           var y = parlamentarierStartY + parlamentarierIconHeight;
           var pos = {};
           pos["x"] = x;
           pos["y"] = y;
           return pos;
        });

     group.selectAll(".interessenbindungpath")
        .data(lstParlamentarier)
        .enter()
        .append("path")
        .attr("class", "interessenbindungpath")
        .attr("fill", "none")
        .attr("stroke", function (d, i) {
           var relationColor = "#37D6FA";   // default color
           if (d.interessenbindung_art == "vorstand" || d.interessenbindung_art == "geschaeftsfuehrend") {
              relationColor = "#FA5737";   // default color
           }
           return relationColor;
        })
        .attr("stroke-width", 3)
        .attr("d", diagInteressenbindung)
        .style("cursor", "pointer")
        .append("svg:title")
        .text(function (d, i) { return d.interessenbindung_art; });


     // Zutrittsberechtigte
     var lstZutrittsberechtigte = jsonPath(organisationData, "$..zutrittsberechtigte[*]");

     group.selectAll(".zutrittsberechtigte")
        .data(lstZutrittsberechtigte)
        .enter()
        .append("svg:image")
           .attr('x', function (d, i) {
              var posX = i * (gastIconWidth + zutrittsberechtigtePadding) + zutrittsberechtigteStartX;
              return posX;
           })
           .attr('y', zutrittsberechtigteStartY)
           .attr('width', gastIconWidth)
           .attr('height', gastIconHeight)
           .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/gast.gif")
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              var options = "id=" + d.parlamentarier_id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/parlamentarier?" + options;
           });

     group.selectAll(".zutrittsberechtigteLabel")
        .data(lstZutrittsberechtigte)
        .enter()
        .append("text")
           .attr("class", "zutrittsberechtigteLabel")
           .attr("y", zutrittsberechtigteStartY + gastIconTextOffsetY)
           .attr("x", function (d, i) {
              var posX = i * (gastIconWidth + zutrittsberechtigtePadding) + zutrittsberechtigteStartX + gastIconWidth / 2;
              return posX;
           })
           .text(function (d, i) {
              var txt = d.nachname + ", " + d.vorname + " ("  + d.id + ")";
              return txt;
           })
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              var options = "id=" + d.id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/parlamentarier?" + options;
           })
           .call(FitTextToRect, (2 * personR) - 4);

     var diagZutrittsberechtigte = d3.svg.diagonal()
        .source({ x: sourceRightX2, y: sourceRightY2 })
        .target(function (d, i) {
           var x = i * (gastIconWidth + zutrittsberechtigtePadding) + zutrittsberechtigteStartX + gastIconWidth/2;
           var y = zutrittsberechtigteStartY;
           var pos = {};
           pos["x"] = x;
           pos["y"] = y;
           return pos;
        });

     group.selectAll(".zutrittsberechtigtepath")
        .data(lstZutrittsberechtigte)
        .enter()
        .append("path")
        .attr("class", "zutrittsberechtigtepath")
        .attr("fill", "none")
        .attr("stroke", function (d, i) {
           var relationColor = "#37D6FA";   // default color
           if (d.mandat_art == "vorstand" || d.mandat_art == "geschaeftsfuehrend") {
              relationColor = "#FA5737";   // default color
           }
           return relationColor;
        })
        .attr("stroke-width", 3)
        .attr("d", diagZutrittsberechtigte)
        .style("cursor", "pointer")
        .append("svg:title")
        .text(function (d, i) { return d.mandat_art; });

     group.selectAll(".zutrittsberechtigteParlamentarier")
        .data(lstZutrittsberechtigte)
        .enter()
        .append("svg:image")
           .attr('x', function (d, i) {
              var posX = i * (parlamentarierIconWidth + zutrittsberechtigteParlamentarierPadding) + zutrittsberechtigteParlamentarierStartX;
              return posX;
           })
           .attr('y', zutrittsberechtigteParlamentarierStartY)
           .attr('width', parlamentarierIconWidth)
           .attr('height', parlamentarierIconHeight)
           .attr("xlink:href", "/sites/all/modules/lobbywatch/lobbywatch_visual/images/parlamentarier.gif")
           .style("cursor", "pointer")
           .on("click", function (d, i) {
              var options = "id=" + d.parlamentarier_id + "&" + getFilterCriteria();
              window.location.href = baseVisualPath + "/parlamentarier?" + options;
           });

     group.selectAll(".zutrittsberechtigteParlamentarierLabel")
        .data(lstZutrittsberechtigte)
        .enter()
        .append("text")
           .attr("class", "zutrittsberechtigteLabel")
           .attr("y", zutrittsberechtigteParlamentarierStartY + parlamentarierIconTextOffsetY)
           .attr("x", function (d, i) {
              var posX = i * (parlamentarierIconWidth + zutrittsberechtigteParlamentarierPadding) + zutrittsberechtigteParlamentarierStartX + parlamentarierIconWidth / 2;
              return posX;
           })
           .text(function (d, i) {
              var txt = d.parlamentarier.nachname + ", " + d.parlamentarier.vorname + " (" + d.parlamentarier.id + ")";
              txt += ", " + d.parlamentarier.partei;
              return txt;
           })
           .call(FitTextToRect, parlamentarierIconWidth);

     var diagZutrittsberechtigteParlamentarier = d3.svg.diagonal()
        .source(function (d, i) {
           var pos = {};
           pos["x"] = i * (gastIconWidth + zutrittsberechtigtePadding) + zutrittsberechtigteStartX + gastIconWidth / 2;
           pos["y"] = zutrittsberechtigteStartY + gastIconWidth;
           return pos;
        })
        .target(function (d, i) {
           var pos = {};
           pos["x"] = i * (parlamentarierIconWidth + zutrittsberechtigteParlamentarierPadding) + zutrittsberechtigteParlamentarierStartX + parlamentarierIconWidth / 2;
           pos["y"] = zutrittsberechtigteParlamentarierStartY;
           return pos;
        });

     group.selectAll(".zutrittsberechtigteParlamentarierPath")
        .data(lstZutrittsberechtigte)
        .enter()
        .append("path")
        .attr("class", "zutrittsberechtigteParlamentarierPath")
        .attr("fill", "none")
        .attr("stroke", function (d, i) {
           var relationColor = "#aaaaaa";   // default color
           return relationColor;
        })
        .attr("stroke-width", 3)
        .attr("d", diagZutrittsberechtigteParlamentarier)
        .style("cursor", "pointer")
        .append("svg:title")
        .text(function (d, i) { return d.funktion; });

  });
  
});

// ---
  
  d3.selection.prototype.moveToFront = function () {
    return this.each(function () {
       this.parentNode.appendChild(this);
    });
 };

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
       words = organisationData.organisation.branche.split(/\s+/).reverse();
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

       // Company
       line = [];
       tspan = text
                  .append("tspan")
                  .attr("x", posX)
                  //.attr("y", y)
                  .attr("dy", dy);

       words = organisationData.organisation.name_de.split(/\s+/).reverse();
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

 function FitParlamentarierTextToRect(text, width) {
    text.each(function () {
       var text = d3.select(this);
       var data = text.property("__data__");
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

       // Partei
       words = data.partei.split(/\s+/).reverse();
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

       // Name
       line = [];
       tspan = text
                  .append("tspan")
                  .attr("x", posX)
                  //.attr("y", y)
                  .attr("dy", dy);

       words = (data.nachname + ", " + data.vorname).split(/\s+/).reverse();
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

})(jQuery);