define(function(require, exports)
{
    var Class       = require('class'),
        localizer   = require('pgui.localizer').localizer,
        _           = require('underscore');

    exports.ModalViewLink = Class.extend({
        init: function(container)
        {
            this.container = container;
            this.modalViewLink = this.container.attr('content-link');
            this.dialogTitle  = this.container.attr('dialog-title');

            this.container.click(_.bind(function(event)
            {
                event.preventDefault();
                this._invokeModalViewDialog();
            }, this));
        },

        _invokeModalViewDialog: function()
        {
            $.get(this.modalViewLink,
                _.bind(function(data)
                {
                    this._displayModalViewDialog($(data));
                }, this));
        },

        _displayModalViewDialog: function(content)
        {
            var cardViewContainer = $('<div class="modal"></div>');
            $('body').append(cardViewContainer);
            cardViewContainer.hide();
            cardViewContainer.append(content);
            cardViewContainer.find('.modal-header .title').text(this.dialogTitle);

            cardViewContainer.find('.close-button').click(function() {
                cardViewContainer.modal("hide");
            });

            cardViewContainer.modal({
                modal: true,
                backdrop: 'static'
            });

            cardViewContainer.on('hidden', function () {
                cardViewContainer.remove();
            })

        }
    });

});