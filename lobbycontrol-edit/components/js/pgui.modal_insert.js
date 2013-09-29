define(function(require, exports, module)
{
    var modalOperations = require('pgui.modal_operations'),
        localizer       = require('pgui.localizer').localizer,
        forms           = require('pgui.forms'),
        _               = require('underscore');

    exports.ModalInsertLink = modalOperations.ModalOperationLink.extend({

        /*_doValidateForm: function(form)
        {   
            //return Grid_ValidateForm(form, true);
        },*/

        _bindButtonEvents: function($formContainer, errorContainer) {
            var self = this;
            
            $formContainer.find('.dropdown-toggle').dropdown();

            $formContainer.find('.cancel-button').click(function(e) {
                e.preventDefault();
                $formContainer.modal('hide');
            });

            $formContainer.find('.submit-button,#save').click(function(e) {
                e.preventDefault();
                self._processCommit($formContainer, errorContainer, function() { });
            });

            $formContainer.find('#saveedit').click(function(e) {
                e.preventDefault();
                self._processCommit($formContainer, errorContainer, function(newRow){
                    _.delay(_.bind((function() {
                        var modalEditLink = newRow.find('a[modal-edit=true]');
                        modalEditLink.click();
                    }), self), 100);
                });
            });

            $formContainer.find('#saveinsert').click(function(e) {
                e.preventDefault();
                self._processCommit($formContainer, errorContainer, function(newRow){
                    _.delay(_.bind((function() {
                        self.container.click();
                    }), self), 100);
                });
            });



            this.form = new forms.InsertForm($formContainer);
        },

        _insertNewRow: function(response) {
            var newRow = $($(response).find('row').text());
            this.parentGrid.insertRowAtBegin(newRow);
            return newRow;
        },

        _doUpdateGridAfterCommit: function(response, successCallback) {
            var newRow = this._insertNewRow(response);
            successCallback(newRow);
        },

        _doOkCreateButton: function(container, formContainer, errorContainer) {
            var self = this;
            return PhpGen.createDropDownButton(container,
                {
                    defaultButtonCaption: localizer.getString('Save'),
                    buttons:
                        [
                            {
                                caption: localizer.getString('SaveAndBackToList'),
                                value: 'save',
                                click: function()
                                {
                                    self._processCommit(formContainer, errorContainer, function() { });
                                },
                                isDefault: true
                            },
                            {
                                caption: localizer.getString('SaveAndInsert'),
                                click: function()
                                {
                                    self._processCommit(formContainer, errorContainer, function() {
                                        (_.bind(function() { this.container.click() }, self)).delay(10)
                                    });
                                },
                                value: 'saveinsert',
                                isDefault: false
                            }
                        ]
                });
        }

    });
});