$(function() {

  function setWSValue(field, wsVal, field_name, overwrite, ignore_new_empty) {
    const fieldType = $(field).attr('type');
    var oldVal = $(field).val();
    const isEmptyOldVal = !oldVal; // Workaround: check for empty values http://stackoverflow.com/questions/5515310/is-there-a-standard-function-to-check-for-null-undefined-or-blank-variables-in
    const isEmptyWsVal = !wsVal;
    if (oldVal == wsVal || (isEmptyOldVal && isEmptyWsVal) || (!overwrite && !isEmptyOldVal)) {
      $(field).removeClass('ws-changed-value');
    } else if (fieldType === 'checkbox') {
      oldVal = $(field).prop("checked");
      if (oldVal !== wsVal) {
        $(field).prop("checked", wsVal).addClass('ws-changed-value');
        $(field).parent().addClass('ws-changed-value');
        $('#info-message').append('<p><span class="ws-update-val ws-update-val-old">' + field_name + ': ' + oldVal + '</span> → <span class="ws-update-val ws-update-val-new">' + wsVal + '</span></p>').show();
      }
    } else if (!ignore_new_empty || !isEmptyWsVal) {
      // val() works also for select dropdowns, http://stackoverflow.com/questions/1280499/jquery-set-select-index
      $(field).val(wsVal).addClass('ws-changed-value');
      if (!isEmptyOldVal) {
        $('#info-message').append('<p><span class="ws-update-val ws-update-val-old">' + field_name + ': ' + oldVal + '</span> → <span class="ws-update-val ws-update-val-new">' + wsVal + '</span></p>').show();
      }
    }
  }

  function setWSFieldValue(formId, field_name, wsVal, overwrite = true, ignore_new_empty = true) {
    return setWSValue('#' + formId + '_' + field_name + '_edit', wsVal, field_name, overwrite, ignore_new_empty);
  }

  $('#btn-ws-uid').click(function() {
    $(".row form").each(function() {
      const formId = $(this).attr("id");
      const uid_raw = $('#' + formId + '_uid_edit').val();

      if (!uid_raw) {
        $('#error-message').append('<p>Failed: Empty UID.<br>Search UID on <a href="https://www.uid.admin.ch/" title="Opens UID-Register@BFS webpage" target="_blank">UID-Register</a>.</p>').show();
        return;
      }

        $('#info-message').append('<p>Fetch data from uid webservice for ' + uid_raw + '</p>').show();
        // http://stackoverflow.com/questions/8551229/showing-loading-image-with-getjson
        // http://stackoverflow.com/questions/16599915/loading-indicator-on-synchronous-ajax
        // http://stackoverflow.com/questions/807408/showing-loading-animation-in-center-of-page-while-making-a-call-to-action-method
        // http://api.jquery.com/category/ajax/global-ajax-event-handlers/
        $('#btn-ws-uid').addClass('btn-loading');

       $.getJSON(`/de/data/interface/v1/json/ws/uid/flat/uid/${uid_raw}?access_key=cdaVCGjrDqbuR0kuD0lx4eHF9eJmiWBUP6OgLA4t`, function(data, textStatus, jqXHR) {
          if (data && data.success && data.data) {
            setWSFieldValue(formId, 'name_de', data.data.name_de);
            setWSFieldValue(formId, 'abkuerzung_de', data.data.abkuerzung_de);
            setWSFieldValue(formId, 'in_handelsregister', data.data.in_handelsregister);
            setWSFieldValue(formId, 'land_id', data.data.land_id);
            setWSFieldValue(formId, 'adresse_strasse', data.data.adresse_strasse);
            setWSFieldValue(formId, 'adresse_zusatz', data.data.adresse_zusatz);
            setWSFieldValue(formId, 'adresse_plz', data.data.adresse_plz);
            setWSFieldValue(formId, 'ort', data.data.ort);
            setWSFieldValue(formId, 'bfs_gemeinde_nr', data.data.bfs_gemeinde_nr);
            setWSFieldValue(formId, 'rechtsform', data.data.rechtsform);
            setWSFieldValue(formId, 'rechtsform_zefix', data.data.rechtsform_zefix);
            setWSFieldValue(formId, 'rechtsform_handelsregister', data.data.rechtsform_handelsregister);
            setWSFieldValue(formId, 'beschreibung', data.data.zweck, false);
            if (data.data.inaktiv || data.data.inaktiv === false) setWSFieldValue(formId, 'inaktiv', data.data.inaktiv, false);
            $('#info-message').append('<p>State: ' + textStatus + '</p>').show();
          } else {
            $('#error-message').append('<p>Failed: ' + data.message + ' (Call state: ' + textStatus + ')</p>').show();
          }
        })
        .fail(function( jqxhr, textStatus, error ) {
          const err = textStatus + ", " + error;
          $('#error-message').append('<p>Request failed: ' + err + '</p>').show();
        })
        .always(function( jqxhr, textStatus, error ) {
          $('#btn-ws-uid').removeClass('btn-loading');
        });
    });
  });
});
