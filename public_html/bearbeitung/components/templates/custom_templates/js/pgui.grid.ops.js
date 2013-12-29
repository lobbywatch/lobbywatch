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

            this.$authorizeSelectedButton.click(function() {

                require(['bootbox.min'], function() {

                    var nRows = self.countSelectedRows();
                    bootbox.animate(false);
                    bootbox.prompt( nRows + ' markierte Einträge autorisieren?<small><br><br>Bitte Autorisierungsdatum eingeben (leer = heute):</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
//                         console.log(date);
//                         console.log(self.isDateValid(date));
                        if (date !== null) {
                          if (date === '' || self.isDateValid(date)) {
                              self.operateSelectRows('autsel', date);
                            } else {
                              bootbox.alert('Bitte Datum als DD.MM.YYYY eingeben');
                            }
                        }
                    });

                });
            });

            this.$deauthorizeSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
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
                  bootbox.animate(false);
                  bootbox.confirm( nRows + ' markierte Einträge freigeben?' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('relsel');
                      }
                  });

                });
            });

            this.$dereleaseSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  var nRows = self.countSelectedRows();
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
                bootbox.animate(false);
                bootbox.prompt( '&quot;Im Rat bis&quot; bei ' + nRows + ' Parlamentarieren setzen?<small><br><br>Bitte &quot;Im Rat bis&quot; eingeben (leer = heute):</small>'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(date) {
                  //                         console.log(date);
                  //                         console.log(self.isDateValid(date));
                  if (date !== null) {
                    if (date === '' || self.isDateValid(date)) {
                      self.operateSelectRows('setimratbissel', date);
                    } else {
                      bootbox.alert('Bitte Datum als DD.MM.YYYY eingeben');
                    }
                  }
                });

              });
            });

            this.$clearImRatBisSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                var nRows = self.countSelectedRows();
                bootbox.animate(false);
                bootbox.confirm( '&quot;Im Rat bis&quot; bei ' + nRows + ' Einträgen entfernen?'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                  if (confirmed) {
                    self.operateSelectRows('clearimratbissel');
                  }
                });

              });
            });


        },


    });

});
