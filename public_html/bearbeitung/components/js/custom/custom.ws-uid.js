$(function() {

  function setWSValue(field, wsVal) {
    var oldVal = $(field).val();
  //       console.log(oldVal + ' | ' + wsVal);
    var isEmptyOldVal = !oldVal; // Workaround: check for empty values http://stackoverflow.com/questions/5515310/is-there-a-standard-function-to-check-for-null-undefined-or-blank-variables-in
    var isEmptyWsVal = !wsVal;
    // console.log(field + ": " + oldVal + " (" + !isEmptyOldVal + ') → ' + wsVal + " (" + !isEmptyWsVal + ")");
    if (oldVal == wsVal || (isEmptyOldVal && isEmptyWsVal)) {
      $(field).removeClass('ws-changed-value');
    } else {
      // var wsValClean = typeof wsVal === 'string' || wsVal instanceof String ? wsVal.replace("'", "\\'") : wsVal;
      // val() works also for select dropdowns, http://stackoverflow.com/questions/1280499/jquery-set-select-index
      $(field).val(wsVal).addClass('ws-changed-value').prop("disabled", false);
      // $(field).val(wsVal).addClass('ws-changed-value').prop("disabled", false).
      //   find("option[value='" + wsValClean + "'][disabled]").prop("disabled", false).prop("selected", true);
//       $("option" + field + "[value='" + wsVal + "'][disabled]").prop("disabled", false);
      if (!isEmptyOldVal) {
        $('#info-message').append('<p><span class="ws-update-val ws-update-val-old">' + oldVal + '</span> → <span class="ws-update-val ws-update-val-new">' + wsVal + '</span></p>').show();
      }
    }
  }

  $('#btn-ws-uid').click(function() {

    $(".row form").each(function() {
      var formId = $(this).attr("id");
//      console.log("FormId: " + formId);
      var uid_raw = $('#' + formId + '_uid_edit').val();

      if (!uid_raw) {
        $('#error-message').append('<p>Failed: Empty UID</p>').show();
        return;
      }

        $('#info-message').append('<p>Fetch data from uid webservice for ' + uid_raw + '</p>').show();
        http://stackoverflow.com/questions/8551229/showing-loading-image-with-getjson
        // http://stackoverflow.com/questions/16599915/loading-indicator-on-synchronous-ajax
        // http://stackoverflow.com/questions/807408/showing-loading-animation-in-center-of-page-while-making-a-call-to-action-method
        // http://api.jquery.com/category/ajax/global-ajax-event-handlers/
        // $('#ws-uid-indicator').show();
        $('#btn-ws-uid').addClass('btn-loading');


        // console.log("Start uid fetching...");

        // // using Fetch API
        // var myHeaders = new Headers();
        // myHeaders.append("Authorization", "Basic b3BlcmF0aW9uQGxvYmJ5d2F0Y2guY2g6Y3ZweGY3OGI=");
        // fetch('https://www.zefixintg.admin.ch/ZefixPublicREST/api/v1/company/uid/CHE' + uid_raw, {
        //     credentials: "include",
        //     headers: myHeaders
        // }).then(function (response) {
        //     return response.json();
        // }).then(function (json) {
        //     console.log(json);
        // });

        // $.ajaxSetup({
        //   headers: {"Authorization": "Basic b3BlcmF0aW9uQGxvYmJ5d2F0Y2guY2g6Y3ZweGY3OGI="}
        // });

        // $.ajax({
        //   dataType: "json",
        //   // TODO improve: get pw securely
        //   // username: "operation@lobbywatch.ch",
        //   // password: "cvpxf78b",
        //   // beforeSend: function (xhr) {
        //   //   xhr.setRequestHeader("Authorization", "Basic b3BlcmF0aW9uQGxvYmJ5d2F0Y2guY2g6Y3ZweGY3OGI=");
        //   // },
        //   headers: {"Authorization": "Basic b3BlcmF0aW9uQGxvYmJ5d2F0Y2guY2g6Y3ZweGY3OGI="},
        //   url: 'https://www.zefixintg.admin.ch/ZefixPublicREST/api/v1/company/uid/CHE' + uid_raw,
        //   // data: data,
        //   success: function(data, textStatus, jqXHR) {
        //     console.log("fetch data", textStatus, jqXHR, data);
        //   },
        //   error: function(jqxhr, textStatus, error) {
        //     var err = textStatus + ", " + error;
        //     console.log( "Request Failed: " + err );
        //     $('#error-message').append('<p>Request failed: ' + err + '</p>').show();
        //   },
        //   complete: function(jqxhr, textStatus, error) {
        //     $('#ws-uid-indicator').hide();
        //     console.log("End uid fetching");
        //   }
        // });

       $.getJSON(`/de/data/interface/v1/json/ws/uid/flat/uid/${uid_raw}?access_key=cdaVCGjrDqbuR0kuD0lx4eHF9eJmiWBUP6OgLA4t`, function(data, textStatus, jqXHR) {
  //                alert(data.data);
          if (data && data.success && data.data) {
  //               alert('uid ws fetched successful: ' + textStatus);
            setWSValue('#' + formId + '_name_de_edit', data.data.name_de);
            setWSValue('#' + formId + '_land_id_edit', data.data.land_id);
            setWSValue('#' + formId + '_adresse_strasse_edit', data.data.adresse_strasse);
            setWSValue('#' + formId + '_adresse_zusatz_edit', data.data.adresse_zusatz);
            setWSValue('#' + formId + '_adresse_plz_edit', data.data.adresse_plz);
            setWSValue('#' + formId + '_ort_edit', data.data.ort);
            setWSValue('#' + formId + '_rechtsform_edit', data.data.rechtsform);
            setWSValue('#' + formId + '_rechtsform_zefix_edit', data.data.rechtsform_zefix);
            setWSValue('#' + formId + '_rechtsform_handelsregister_edit', data.data.rechtsform_handelsregister);
            setWSValue('#' + formId + '_beschreibung_edit', data.data.zweck);
            $('#info-message').append('<p>State: ' + textStatus + '</p>').show();
          } else {
  //               alert('failed');
            $('#error-message').append('<p>Failed: ' + data.message + ' (Call state: ' + textStatus + ')</p>').show();
          }
        })
        .fail(function( jqxhr, textStatus, error ) {
          var err = textStatus + ", " + error;
          // console.log( "Request Failed: " + err );
          $('#error-message').append('<p>Request failed: ' + err + '</p>').show();
        })
        .always(function( jqxhr, textStatus, error ) {
          // $('#ws-uid-indicator').hide();
          $('#btn-ws-uid').removeClass('btn-loading');
        });
    });
  });
});
