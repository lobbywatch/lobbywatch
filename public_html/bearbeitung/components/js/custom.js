$(function() {

  function setWSValue(field, wsVal) {
    var oldVal = $(field).val();
  //       console.log(oldVal + ' | ' + wsVal);
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

    if (!uid_raw) {
      $('#error-message').append('<p>Failed: Empty UID</p>').show();
      return;
    }

      $('#info-message').append('<p>Fetch data from uid webservice for ' + uid_raw + '</p>').show();
      http://stackoverflow.com/questions/8551229/showing-loading-image-with-getjson
      // http://stackoverflow.com/questions/16599915/loading-indicator-on-synchronous-ajax
      // http://stackoverflow.com/questions/807408/showing-loading-animation-in-center-of-page-while-making-a-call-to-action-method
      // http://api.jquery.com/category/ajax/global-ajax-event-handlers/
      $('#ws-uid-indicator').show();
      $.getJSON('/de/data/interface/v1/json/ws/uid/flat/uid/' + uid_raw, function(data, textStatus, jqXHR){
//                alert(data.data);
        if (data && data.success && data.data) {
//               alert('uid ws fetched successful: ' + textStatus);
          setWSValue('#name_de_edit', data.data.name_de);
          setWSValue('#land_id_edit', data.data.land_id);
          setWSValue('#adresse_strasse_edit', data.data.adresse_strasse);
          setWSValue('#adresse_zusatz_edit', data.data.adresse_zusatz);
          setWSValue('#adresse_plz_edit', data.data.adresse_plz);
          setWSValue('#ort_edit', data.data.ort);
          setWSValue('#rechtsform_edit', data.data.rechtsform);
          $('#info-message').append('<p>State: ' + textStatus + '</p>').show();
        } else {
//               alert('failed');
          $('#error-message').append('<p>Failed: ' + data.message + ' (Call state: ' + textStatus + ')</p>').show();
        }
      })
      .fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.log( "Request Failed: " + err );
        $('#error-message').append('<p>Request failed: ' + err + '</p>').show();
      })
      .always(function( jqxhr, textStatus, error ) {
        $('#ws-uid-indicator').hide();
      });
  });
});
