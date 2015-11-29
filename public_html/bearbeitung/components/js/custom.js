// define(function(require, exports, module) {

//     var
//     pc  = require('pgui.pagination'),
//         dtp = require('pgui.datetimepicker'),
//         Class = require('class'),
//         sc = require('pgui.shortcuts');

//   	alert('Ha');

    $(function() {
//         var $body = $('body');

    function setWSValue(field, wsVal) {
  	var oldVal = $(field).val();
//   	console.log(oldVal + ' | ' + wsVal);
  	var isEmptyOldVal = !oldVal; // Workaround: check for empty values http://stackoverflow.com/questions/5515310/is-there-a-standard-function-to-check-for-null-undefined-or-blank-variables-in
  	var isEmptyWsVal = !wsVal;
  	console.log(oldVal + " (" + !isEmptyOldVal + ') → ' + wsVal + " (" + !isEmptyWsVal + ")");
  	if (oldVal == wsVal || (isEmptyOldVal && isEmptyWsVal)) {
  	  $(field).removeClass('ws-changed-value');
  	} else {
  	  // val() works also for select dropdowns, http://stackoverflow.com/questions/1280499/jquery-set-select-index
  	  $(field).val(wsVal).addClass('ws-changed-value');
  	}
    }
  	$('#btn-ws-uid').click(function() {

  	  var uid_raw = $('#uid_edit').val();
//   	  var uid = uid_raw.replace(/CHE-/, '').replace(/\./g,'');
  	  // TODO check format, length
  	  // 111111111

  	  if (!uid_raw) {
  	  $('#error-message').append('<br>UID is empty').show();
  	  return;
  	  }

//   	    alert('Ho!! ' + uid);
  	    $('#info-message').append('<p>Fetch data: ' + uid_raw + '</p>').show();
  	    http://stackoverflow.com/questions/8551229/showing-loading-image-with-getjson
  	    // http://stackoverflow.com/questions/16599915/loading-indicator-on-synchronous-ajax
  	    // http://stackoverflow.com/questions/807408/showing-loading-animation-in-center-of-page-while-making-a-call-to-action-method
  	    // http://api.jquery.com/category/ajax/global-ajax-event-handlers/
  	  	$('#ws-uid-indicator').show();
  	  	$.getJSON('/de/data/interface/v1/json/ws/uid/flat/uid/' + uid_raw, function(data, textStatus, jqXHR){
//    	  	  alert(data.data);
//   	  	  $('#adresse_strasse_edit').val('123');
//   	  	  $('#name_de_edit').val('555');
  	  	  if (data && data.success && data.data) {
//   	  	  $('#adresse_strasse_edit').val(data.data.uid);
//   	  	  $('#name_de_edit').val(data.data.name_de);
  	  	  setWSValue('#name_de_edit', data.data.name_de);
  	  	  setWSValue('#land_id_edit', data.data.land_id);
//   	  	  alert('uid ws fetched successful: ' + textStatus);
  	  	  setWSValue('#adresse_strasse_edit', data.data.adresse_strasse);
  	  	  setWSValue('#adresse_zusatz_edit', data.data.adresse_zusatz);
  	  	  setWSValue('#adresse_plz_edit', data.data.adresse_plz);
  	  	  setWSValue('#ort_edit', data.data.ort);
  	  	  setWSValue('#rechtsform_edit', data.data.rechtsform);
  	  	  $('#info-message').append('<p>State: ' + textStatus + '</p>').show();
  	  	  } else {
//   	  	  alert('failed');
  	  	  $('#error-message').append('<br>Failed: ' + data.message + ' (Call state: ' + textStatus + ')').show();
  	  	  }
  	    })
  	    .fail(function( jqxhr, textStatus, error ) {
  	  	var err = textStatus + ", " + error;
  	  	console.log( "Request Failed: " + err );
  	  	$('#error-message').append('<br>Failed: ' + err).show();
  	    })
  	    .always(function( jqxhr, textStatus, error ) {
  	  	$('#ws-uid-indicator').hide();
  	    });

  	  // https://github.com/doedje/jquery.soap
//             require(["jquery/jquery.soap"], function() {
//   	    alert('Ho!');
//   	  	$.soap({
//   	  	  url: 'https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc?wsdl',
//   	  	  method: 'GetByUID',
//
//   	  	  data: {
//   	  	    uidOrganisationIdCategorie: 'CHE',
//   	  	    uidOrganisationId: '107810911'
//   	  	  },
//
//   	  	  success: function (soapResponse) {
//   	  	  alert('response');
//   	  	    // do stuff with soapResponse
//   	  	    // if you want to have the response as JSON use soapResponse.toJSON();
//   	  	    // or soapResponse.toString() to get XML string
//   	  	    // or soapResponse.toXML() to get XML DOM
//   	  	  },
//   	  	  error: function (SOAPResponse) {
//   	  	    // show error
//   	  	  alert('error');
//   	  	  }
//   	    });
//   	    $.getJSON('http://whateverorigin.org/get?url=' + encodeURIComponent('https://www.uid-wse-a.admin.ch/V3.0/PublicServices.svc?wsdl') + '&callback=?', function(data){
//   	  	  alert(data.contents);
//   	    });

//             });
  	});

//         pc.setupPaginationControls($body);
//         dtp.setupCalendarControls($body);
//
//         $('[data-pg-typeahead=true]').each(function() {
//             var typeHeadInput = $(this);
//
//             require(['pgui.typeahead'], function(pt) {
//                 (new pt.PgTypeahead(typeHeadInput));
//             })
//         });

//         require(['pgui.layout'], function(instance){
//             instance.updatePopupHints($body);
//         });
//         //if (IsBrowserVersion({msie: 8, opera: 'none'}))
//         //{
//         if ($('table.pgui-grid.fixed-header').length > 0) {
//             require(["jquery/jquery.fixedtableheader"], function() {
//                 if ($.browser.msie) {
//                     $('table.grid th.row-selection').width('1%');
//                 }
//
//                 $('table.pgui-grid').fixedtableheader({
//                     headerrowsize: 3,
//                     top: 0//$('.navbar.navbar-fixed-top').height()
//                 });
//             });
//         }
//
//         //}
//
//         sc.initializeShortCuts($body);
    });
// });
