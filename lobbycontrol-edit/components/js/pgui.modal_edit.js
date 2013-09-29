define(function(require, exports, module)
{
    var modalOperations = require('pgui.modal_operations'),
        localizer       = require('pgui.localizer').localizer,
        forms           = require('pgui.forms'),
        _               = require('underscore');

    exports.ModalEditLink = modalOperations.ModalOperationLink.extend({
        _doValidateForm: function(form)
        {
            return Grid_ValidateForm(form, false);
        },

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


            this.form = new forms.EditForm($formContainer);
        },

        _doOkCreateButton: function(container, formContainer, errorContainer)
        {
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
                                caption: localizer.getString('SaveAndEdit'),
                                click: function()
                                {
                                    self._processCommit(formContainer, errorContainer, function(newRow){
                                        (function() {
                                            var modalEditLink = newRow.find('a[modal-edit=true]');
                                            modalEditLink.click();
                                        }.bind(self)).delay(10)
                                    });
                                },
                                value: 'saveedit',
                                isDefault: false
                            }
                        ]
                });
        },

        _doUpdateGridAfterCommit: function(response, successCallback)
        {
            var newRow = this._updateRow(response);
            successCallback(newRow);
        },

        _updateRow: function(response)
        {
            var newRow = $($(response).find('row').text());
            var oldRow = this.$row;

            newRow.insertAfter(oldRow);
            oldRow.remove();
            this.parentGrid.integrateRows(newRow);

            //newRow.pgui_effects();
            //newRow.pgui_unobtrusive();

            /* this.container.closest('table').sm_inline_grid_edit(
                {
                    row: newRow,
                    useBlockGUI: true
                });

            SetupModalEditors(newRow);
            ReloadImageColumns();

            RecalculateGridLineNumbers(); */

            return newRow;
        }
    });
});