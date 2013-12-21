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

            this._bindHandlers();
        },

        /**
         * See AuthorizeSelectedGridState::ProcessMessages
         */
        operateSelectRows: function(operation) {
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
            $form.submit();
        },

        getOperateSelectedAction: function() {
            // We can use the same action as for delete
            return this.container.attr('data-delete-selected-action');
        },

        _bindHandlers: function() {
            var self = this;

            this.$authorizeSelectedButton.click(function() {

                require(['bootbox.min'], function() {

                    bootbox.animate(false);
                    bootbox.confirm( 'Autorisieren?'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                        if (confirmed) {
                            self.operateSelectRows('autsel');
                        }
                    });

                });
            });
            
            this.$deauthorizeSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  bootbox.animate(false);
                  bootbox.confirm( 'Autorisierung entfernen?'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('deautsel');
                      }
                  });

              });
          });
          
            this.$releaseSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  bootbox.animate(false);
                  bootbox.confirm( 'Freigeben?'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('relsel');
                      }
                  });

              });
          });

            this.$dereleaseSelectedButton.click(function() {

              require(['bootbox.min'], function() {

                  bootbox.animate(false);
                  bootbox.confirm( 'Freigabe entfernen?'/*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
                      if (confirmed) {
                          self.operateSelectRows('derelsel');
                      }
                  });

              });
          });

            
        },


    });

});