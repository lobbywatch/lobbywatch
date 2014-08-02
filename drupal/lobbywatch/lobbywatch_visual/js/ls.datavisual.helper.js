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

//
//helper functions
//

//
function Tooltip(tooltipId, width) {
var tooltipId = tooltipId;
jQuery("body").append("<div class='tooltip' id='" + tooltipId + "'></div>");

if (width) {
   jQuery("#" + tooltipId).css("width", width);
}

hideTooltip();

function showTooltip(content, event) {
   jQuery("#" + tooltipId).html(content);
   jQuery("#" + tooltipId).show();

   updatePosition(event);
}

function hideTooltip() {
   jQuery("#" + tooltipId).hide();
}

function updatePosition(event) {
   var ttid = "#" + tooltipId;
   var xOffset = 20;
   var yOffset = 10;

   var toolTipW = jQuery(ttid).width();
   var toolTipeH = jQuery(ttid).height();
   var windowY = jQuery(window).scrollTop();
   var windowX = jQuery(window).scrollLeft();
   var curX = event.pageX;
   var curY = event.pageY;
   var ttleft = ((curX) < jQuery(window).width() / 2) ? curX - toolTipW - xOffset * 2 : curX + xOffset;
   if (ttleft < windowX + xOffset) {
      ttleft = windowX + xOffset;
   }
   var tttop = ((curY - windowY + yOffset * 2 + toolTipeH) > jQuery(window).height()) ? curY - toolTipeH - yOffset * 2 : curY + yOffset;
   if (tttop < windowY + yOffset) {
      tttop = curY + yOffset;
   }
   jQuery(ttid).css('top', tttop + 'px').css('left', ttleft + 'px');
}

return {
   showTooltip: showTooltip,
   hideTooltip: hideTooltip,
   updatePosition: updatePosition
}
}



(function($) {





})(jQuery);