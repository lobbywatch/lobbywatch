define(function(require, exports, module) {

    var Class               = require('class'),
        fb                  = require('pgui.advanced_filter'),
        localizer           = require('pgui.localizer').localizer,
        events              = require('microevent'),
        overlay             = require('pgui.overlay'),
        async               = require('async'),
        _                   = require('underscore'),
        InputEvents         = require('pgui.events').InputEvents,
        setupInputEvents    = require('pgui.events').setupInputEvents;

    var GridOps = exports.GridOps = Class.extend({

        /**
         * @param {jQuery} container $(table#<table_name>Grid) See grid.tpl, Grid::GetId
         * @param options
         */
        init: function(container, options) {
//            alert('GridOps.init');
            var self = this;
            this.container = container;
            this.container.data('grid-class', this);

            this.$inputFinishedSelectedButton = this.container.find('.input-finished-selected');
            this.$deinputFinishedSelectedButton = this.container.find('.de-input-finished-selected');
            this.$controlledSelectedButton = this.container.find('.controlled-selected');
            this.$decontrolledSelectedButton = this.container.find('.de-controlled-selected');
            this.$authorizationSentSelectedButton = this.container.find('.authorization-sent-selected');
            this.$deauthorizationSentSelectedButton = this.container.find('.de-authorization-sent-selected');
            this.$authorizeSelectedButton = this.container.find('.authorize-selected');
            this.$deauthorizeSelectedButton = this.container.find('.de-authorize-selected');
            this.$releaseSelectedButton = this.container.find('.release-selected');
            this.$dereleaseSelectedButton = this.container.find('.de-release-selected');
            this.$setImRatBisSelectedButton = this.container.find('.set-imratbis-selected');
            this.$clearImRatBisSelectedButton = this.container.find('.clear-imratbis-selected');

            this._bindHandlers();
        },

        countSelectedRows: function() {
          var selectedRows = this.container
            .find('.pg-row')
            .filter(function() {
              return $(this).find('td.row-selection input[type=checkbox]').prop('checked') ? true : false;
            });
          return selectedRows.length;
        },

        /**
         * See OperateSelectedGridState::ProcessMessages
         */
        operateSelectRows: function(operation, date) {
            var selectedRows = this.container
                .find('.pg-row')
                .filter(function() {
                    return $(this).find('td.row-selection input[type=checkbox]').prop('checked') ? true : false;
                });
            var $form = $('<form>')
                .addClass('hide')
                .attr('method', 'post')
                .attr('action', this.getOperateSelectedAction())
                .append($('<input name="operation" value="' + operation + '">'))
                .append(
                    $('<input name="recordCount">')
                        .attr('value', this.container.find('.pg-row').length))
                .appendTo($('body'));

            selectedRows.each(function() {
                $(this).find('td.row-selection input').clone().appendTo($form);
            });
            $form.append($('<input name="date" value="' + date + '">'));
            $form.submit();
        },

        getOperateSelectedAction: function() {
            // We can use the same action as for delete
            return this.container.attr('data-delete-selected-action');
        },

        // Ref: http://snipplr.com/view/13666/
        isDateValid: function(date) {
          //(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(19|20)\d\d
          var regex = /^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d$/;
          return date.match(regex) !== null;
        },

        _bindHandlers: function() {
            var self = this;

            this.$inputFinishedSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( nRows + ' markierte Einträge abschliessen?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('finsel');
                      }
                  });

                });
            });

            this.$deinputFinishedSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( '"Eingabe abgeschlossen" bei ' + nRows + ' Einträgen entfernen?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('definsel');
                      }
                  });

                });
            });

            this.$controlledSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( nRows + ' markierte Einträge kontrolliert?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('consel');
                      }
                  });

                });
            });

            this.$decontrolledSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( '"Kontrolliert" bei ' + nRows + ' Einträgen entfernen?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('deconsel');
                      }
                  });

                });
            });

            this.$authorizationSentSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
//                  bootbox.animate(false);
//                  bootbox.confirm( 'Bei ' + nRows + ' markierten Einträgen eine Autorisierungsanfrage verschickt?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
//                      if (confirmed) {
//                          self.operateSelectRows('sndsel');
//                      }
//                  });
                  bootbox.animate(false);
                  bootbox.prompt( 'Bei ' + nRows + ' markierten Einträgen eine Autorisierungsanfrage verschickt?<small><br><br>Bitte Sendedatum eingeben (leer = heute):</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
//                       console.log(date);
//                       console.log(self.isDateValid(date));
                      if (date !== null) {
                        if (date === '' || self.isDateValid(date)) {
                            self.operateSelectRows('sndsel', date);
                          } else {
                            bootbox.alert('Bitte Datum als TT.MM.JJJJ eingeben');
                          }
                      }
                  });

                });
            });

            this.$deauthorizationSentSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( '"Autorisierungsanfrage verschickt" bei ' + nRows + ' Einträgen entfernen?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('desndsel');
                      }
                  });

                });
            });

            this.$authorizeSelectedButton.click(function() {

                require(['bootbox.min'], function() {

                    var nRows = self.countSelectedRows();
                    if (nRows == 0) {
                      bootbox.animate(false);
                      bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                      return false;
                    }
                    bootbox.animate(false);
                    bootbox.prompt( nRows + ' markierte Einträge autorisieren?<small><br><br>Bitte Autorisierungsdatum eingeben (leer = heute):</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
//                         console.log(date);
//                         console.log(self.isDateValid(date));
                        if (date !== null) {
                          if (date === '' || self.isDateValid(date)) {
                              self.operateSelectRows('autsel', date);
                            } else {
                              bootbox.alert('Bitte Datum als TT.MM.JJJJ eingeben');
                            }
                        }
                    });

                });
            });

            this.$deauthorizeSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( 'Autorisierung bei ' + nRows + ' Einträgen entfernen?'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('deautsel');
                      }
                  });

                });
            });

            this.$releaseSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                var nRows = self.countSelectedRows();
                if (nRows == 0) {
                  bootbox.animate(false);
                  bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                  return false;
                }
                bootbox.animate(false);
                bootbox.prompt( nRows + ' markierte Einträge freigeben?<small><br><br>Bitte Freigabedatum eingeben (leer = heute):</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
//                     console.log(date);
//                     console.log(self.isDateValid(date));
                    if (date !== null) {
                      if (date === '' || self.isDateValid(date)) {
                          self.operateSelectRows('relsel', date);
                        } else {
                          bootbox.alert('Bitte Datum als TT.MM.JJJJ eingeben');
                        }
                    }
                });

              });
            });

            this.$dereleaseSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
                  if (nRows == 0) {
                    bootbox.animate(false);
                    bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                    return false;
                  }
                  bootbox.animate(false);
                  bootbox.confirm( 'Freigabe bei ' + nRows + ' Einträgen entfernen?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('derelsel');
                      }
                  });

                });
            });

            this.$setImRatBisSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                var nRows = self.countSelectedRows();
                if (nRows == 0) {
                  bootbox.animate(false);
                  bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                  return false;
                }
                bootbox.animate(false);
                bootbox.prompt( '&quot;Im Rat bis&quot; bei ' + nRows + ' Parlamentarieren setzen?<small><br><br>Der Zugang der Gäste erlischt. Das Bis-Datum der Zutrittsberechtigten wird ebenfalls gesetzt.<br><br>Bitte &quot;Im Rat bis&quot; eingeben (leer = heute):</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
                  //                         console.log(date);
                  //                         console.log(self.isDateValid(date));
                  if (date !== null) {
                    if (date === '' || self.isDateValid(date)) {
                      self.operateSelectRows('setimratbissel', date);
                    } else {
                      bootbox.alert('Bitte Datum als TT.MM.JJJJ eingeben');
                    }
                  }
                });

              });
            });

            this.$clearImRatBisSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                var nRows = self.countSelectedRows();
                if (nRows == 0) {
                  bootbox.animate(false);
                  bootbox.alert('Nichts selektiert.<br>Bitte Einträge selektieren.');
                  return false;
                }
                bootbox.animate(false);
                bootbox.confirm( '&quot;Im Rat bis&quot; bei ' + nRows + ' Einträgen entfernen?<small><br><br>Das Bis-Datum der Zutrittsberechtigten wird ebenfalls entfernt, sofern das Datum gleich wie beim Parlamentarier ist.</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                  if (confirmed) {
                    self.operateSelectRows('clearimratbissel');
                  }
                });

              });
            });


        },


    });

});
