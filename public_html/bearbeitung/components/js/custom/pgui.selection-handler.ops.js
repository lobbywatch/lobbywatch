define([
    '../pgui.selection-handler',
    '../pgui.localizer',
    'underscore',
    'jquery.query',
    'bootbox'
], function (SelectionHandler, localizer) {
//    console.log("ops");

    // http://ejohn.org/blog/simple-javascript-inheritance/
    return SelectionHandler.extend({
      init: function (selection, $container, $allCheckbox, $checkboxes, hideContainer) {
          this._super(selection, $container, $allCheckbox, $checkboxes, hideContainer);
//          console.log("ops.init()");
      },

      _handleAction: function (e) {
//        console.log("ops._handleAction()");
        var $el = $(e.currentTarget);
        var type = $el.data('type');
        e.preventDefault();
        var action = this._super(e);
        if (action == undefined) {
//          console.log("ops._handleAction():switch");
          var nRows = this.selection.getData().length;
          var url = $el.data('url');
          switch (type) {
//                case 'compare':
//                    return this._compare($el.data('url'));
//                case 'compare-remove':
//                    return this._compareRemove($el.attr('href'), $el.data('value'));
//                case 'delete':
//                    return this._delete($el.data('url'));
              case 'set-imratbis-selected':
                  return this._op_date('setimratbissel', '&quot;Im Rat bis&quot; bei ' + nRows + ' Parlamentarieren setzen?<small><br><br>Der Zugang der Gäste erlischt. Das Bis-Datum der Zutrittsberechtigten wird ebenfalls gesetzt.<br><br>Bitte &quot;Im Rat bis&quot; eingeben (leer = heute):</small>', url);
              case 'clear-imratbis-selected':
                return this._op_confirm('clearimratbissel', '&quot;Im Rat bis&quot; bei ' + nRows + ' Einträgen entfernen?<small><br><br>Das Bis-Datum der Zutrittsberechtigten wird ebenfalls entfernt, sofern das Datum gleich wie beim Parlamentarier ist.</small>', url);
              case 'input-finished-selected':
                return this._op_confirm('finsel', nRows + ' markierte Einträge abschliessen?', url);
              case 'de-input-finished-selected':
                return this._op_confirm('definsel', '"Eingabe abgeschlossen" bei ' + nRows + ' Einträgen entfernen?', url);
              case 'controlled-selected':
                return this._op_confirm('consel', nRows + ' markierte Einträge kontrolliert?', url);
              case 'de-controlled-selected':
                return this._op_confirm('deconsel', '"Kontrolliert" bei ' + nRows + ' Einträgen entfernen?', url);
              case 'authorization-sent-selected':
                return this._op_date('sndsel', 'Bei ' + nRows + ' markierten Einträgen eine Autorisierungsanfrage verschickt?<small><br><br>Bitte Sendedatum eingeben (leer = heute):</small>', url);
              case 'de-authorization-sent-selected':
                return this._op_confirm('desndsel', '"Autorisierungsanfrage verschickt" bei ' + nRows + ' Einträgen entfernen?', url);
              case 'authorize-selected':
                return this._op_date('autsel', nRows + ' markierte Einträge autorisieren?<small><br><br>Bitte Autorisierungsdatum eingeben (leer = heute):</small>', url);
              case 'de-authorize-selected':
                return this._op_confirm('deautsel', 'Autorisierung bei ' + nRows + ' Einträgen entfernen?', url);
              case 'release-selected':
                return this._op_date('relsel', nRows + ' markierte Einträge freigeben?<small><br><br>Bitte Freigabedatum eingeben (leer = heute):</small>', url);
              case 'de-release-selected':
                return this._op_confirm('derelsel', 'Freigabe bei ' + nRows + ' Einträgen entfernen?', url);
          }
        }
      },

      _op_date: function (op, text, url) {
        var self = this;
        var selectionData = self.selection.getData();
        bootbox.prompt(text/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
          // console.log(date); console.log(self.isDateValid(date));
          if (date !== null) {
            if (date === '' || self.isDateValid(date)) {
              self.operateSelectRows(op, selectionData, url, date);
            } else {
              bootbox.alert('Bitte Datum als TT.MM.JJJJ eingeben');
            }
          }
        });
      },
      
      _op_confirm: function (op, text, url) {
        var self = this;
        var selectionData = self.selection.getData();
        bootbox.confirm(text/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
          if (confirmed) {
            self.operateSelectRows(op, selectionData, url, undefined);
          }
        });
      },

      /**
       * See OperateSelectedGridState::ProcessMessages
       */
      operateSelectRows: function(operation, selectionData, url, date) {
        var self = this;
        var formData = {
            operation: operation,
            recordCount: selectionData.length,
            date: date,
        };
        
        _.each(selectionData, function (keys, i) {
          formData['rec' + i] = null;
          
          _.each(keys, function (value, pk) {
            formData['rec' + i + '_pk' + pk] = value;
          });
          
          self.selection.remove(keys);
        });
        
        this.buildForm(url, formData).submit();
      },
      
      // Copied from pgui.selection-handler.js
      buildForm: function(url, data) {
        var $form = $('<form>').hide()
            .attr('method', 'POST')
            .attr('action', url);

        _.each(data, function (value, name) {
            $form.append($('<input name="' + name + '" value="' + value + '">'));
        });

        return $form.appendTo($('body'));
      },
      
      // Ref: http://snipplr.com/view/13666/
      isDateValid: function(date) {
        //(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(19|20)\d\d
        var regex = /^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d$/;
        return date.match(regex) !== null;
      },

    });

});
