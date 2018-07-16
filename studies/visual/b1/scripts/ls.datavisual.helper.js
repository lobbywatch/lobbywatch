//
// helper functions
//

// 
function Tooltip(tooltipId, width) {
   var tooltipId = tooltipId;
   $("body").append("<div class='tooltip' id='" + tooltipId + "'></div>");

   if (width) {
      $("#" + tooltipId).css("width", width);
   }

   hideTooltip();

   function showTooltip(content, event) {
      $("#" + tooltipId).html(content);
      $("#" + tooltipId).show();

      updatePosition(event);
   }

   function hideTooltip() {
      $("#" + tooltipId).hide();
   }

   function updatePosition(event) {
      var ttid = "#" + tooltipId;
      var xOffset = 20;
      var yOffset = 10;

      var toolTipW = $(ttid).width();
      var toolTipeH = $(ttid).height();
      var windowY = $(window).scrollTop();
      var windowX = $(window).scrollLeft();
      var curX = event.pageX;
      var curY = event.pageY;
      var ttleft = ((curX) < $(window).width() / 2) ? curX - toolTipW - xOffset * 2 : curX + xOffset;
      if (ttleft < windowX + xOffset) {
         ttleft = windowX + xOffset;
      }
      var tttop = ((curY - windowY + yOffset * 2 + toolTipeH) > $(window).height()) ? curY - toolTipeH - yOffset * 2 : curY + yOffset;
      if (tttop < windowY + yOffset) {
         tttop = curY + yOffset;
      }
      $(ttid).css('top', tttop + 'px').css('left', ttleft + 'px');
   }

   return {
      showTooltip: showTooltip,
      hideTooltip: hideTooltip,
      updatePosition: updatePosition
   }
}


// helper function: extract Request parameter
function getParameterByName(name) {
   name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
   var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
       results = regex.exec(location.search);
   return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

//
function wordwrap(text, max) {
   var regex = new RegExp(".{0," + max + "}(?:\\s|$)", "g");
   var lines = []

   var line
   while ((line = regex.exec(text)) != "") {
      lines.push(line);
   }

   return lines
}
