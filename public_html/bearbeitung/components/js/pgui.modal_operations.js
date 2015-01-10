define(function(require, exports, module)
{
    var Class       = require('class'),
        pv          = require('pgui.validation'),
        _           = require('underscore');

    function destroyDialog(formContainer) {
        require(['pgui.controls'], function (ctrls) {
            ctrls.destroyEditors(formContainer, function () {
                formContainer.modal('hide');
                formContainer.remove();
            });
        });
    }

    exports.ModalOperationLink = Class.extend({
        init: function(container, parentGrid)
        {
            this.parentGrid = parentGrid;
            this.container = container;
            this.contentLink = container.attr('content-link');
            this.$row = this.container.closest('.pg-row');
            var self = this;
            this.container.click(function(event)
                {
                    event.preventDefault();
                    self._invokeModalDialog();
                });
        },

        _doOkCreateButton: function(container, formContainer, errorContainer)
        {
            return null;
        },

        _doValidateForm: function(form)
        {
            return null;
        },

        _doUpdateGridAfterCommit: function(response, successCallback)
        {
            return null;
        },

        _invokeModalDialog: function(){
            $.get(this.contentLink, {},
                _.bind(function(data) {
                    this._showModalDialog($(data));
                }, this));
        },

        _bindButtonEvents: function($formContainer, errorContainer) {
            $formContainer.find('.dropdown-toggle').dropdown();

            $formContainer.find('.cancel-button').click(function(e) {
                e.preventDefault();
                destroyDialog($formContainer)
            });
        },

        _showModalDialog: function(content)
        {
            var self = this;
            require(['pgui.controls'], function(ctrls) {

                /**
                 * Container for form
                 * @type {*|jQuery|HTMLElement}
                 */
                var formContainer = $('#modalFormContainer');
                if(formContainer.length === 0){
                    formContainer = $('<div/>', {
                        class: 'modal hide wide-modal',
                        style: 'overflow: visible',
                        id: 'modalFormContainer'
                    })
                        .appendTo($('body'))
                        .append(content);
                }

                self._applyUnobtrusive(formContainer);

                var errorContainer = self._createErrorContainer(formContainer);
                formContainer.find('.modal-body').css('overflow', 'visible');
                formContainer.find('.modal-body').css('max-height', 'inherit');

                self._applyFormValidator(formContainer, errorContainer);

                if (formContainer.height() > $(window).height() - 300)
                    formContainer.addClass('modal-big-length');

                formContainer.find('.title').html(self.container.attr('dialog-title'));
                formContainer.modal({
                    modal: true,
                    show: false,
                    backdrop: 'static'
                });
                ctrls.initEditors(formContainer, function() {
                    self._bindButtonEvents(formContainer, errorContainer);
                    formContainer.modal('show');
                });
            });

        },

        _createButtons: function(dialog, formContainer, errorContainer)
        {
            var uiDialogButtonPane = $('<div></div>')
                    .addClass('ui-dialog-buttonpane')
                    .addClass('ui-widget-content')
                    .addClass('ui-helper-clearfix');

            var uiButtonSet = $( "<div></div>" )
                    .addClass( "ui-dialog-buttonset" )
                    .appendTo( uiDialogButtonPane );

            var cancelButtonBlock = $('<div></div>').css('float', 'right').appendTo(uiButtonSet);

            var cancelButton =
                    $('<button type="button">Cancel</button>')
                            .click(function() { dialog.dialog('close'); })
                            .appendTo(cancelButtonBlock);
            cancelButton.button();

            var saveButtonBlock = $('<div></div>');
            saveButtonBlock.addClass('drop-down-list-margin-fix-wrapper');

            var saveButtonElement = this._doOkCreateButton(saveButtonBlock, formContainer, errorContainer);

            saveButtonBlock.appendTo(uiButtonSet);

            dialog.dialog('widget').append(uiDialogButtonPane);
            dialog.dialog('widget').css('overflow', 'visible');

            //var saveButton = new PhpGen.DropDownButton(saveButtonElement);
        },

        _applyUnobtrusive: function(formContainer)
        {
            //controls.initEditors(formContainer);
        },

        _createErrorContainer: function(formContainer)
        {
            /*var errorContainer = $('<ul class="modal-editing-error-box">');
            formContainer.append(errorContainer);
            errorContainer.hide();
            return errorContainer;*/
            return formContainer.find('.error-container');
        },

        _applyFormValidator: function(formContainer, errorContainer)
        {
            var $form = formContainer.find('form');
            $form.pgui_validate_form({ });
        },

        _beforeFormSubmit: function(formContainer, errorContainer)
        {
            var form = formContainer.find("form");

            if (!form.valid()) {
                return false;
            }
            return pv.ValidateSimpleForm(form, errorContainer, false);
        },

        _showError: function(formContainer, message)
        {
            var $errorContainer = formContainer.find('.error-container');
            var $errorMessage =
                $('<div class="alert alert-error">')
                    .appendTo($errorContainer);
            $errorMessage.html(message);
            $errorMessage.prepend(
                $('<button class="close" type="button"><i class="icon-remove"></i></button>')
                    .click(function(e){
                        $errorMessage.remove();
                    }));

        },

        _processCommit: function(formContainer, errorContainer, success)
        {
            var dialog = formContainer;
            var form = formContainer.find("form");

            require(['jquery/jquery.form'], _.bind(function()
            {
                form.ajaxSubmit(
                {
                    dataType: 'xml',

                    beforeSubmit : _.bind(function()
                    {
                        if (!this._beforeFormSubmit(formContainer, errorContainer))
                            return false;
                        $("body").css("cursor", "wait");
                    }, this),

                    success:_.bind(function(response)
                    {
                        if ($(response).find('type').text() == 'error')
                        {
                            this._showError(formContainer, $(response).find('error_message').text())
                        }
                        else
                        {
                            this._doUpdateGridAfterCommit(response, success);
                            destroyDialog(formContainer);
                        }

                        $("body").css("cursor", "auto");
                    }, this)

                });
            }, this));

        }
    });
});