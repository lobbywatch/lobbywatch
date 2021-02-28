define([
    'pgui.editors/plain',
    'underscore',
    'pgui.editors/checkbox'
], function (PlainEditor, _, CheckBox) {

    var inputTemplateString = '<input'
        + ' type="checkbox"'
        + ' name="<%=editorName%>[]"'
        + ' value="<%=value%>">'
        + '<%=caption%>';

    var stackedItemTemplate = _.template(
        '<div class="checkbox js-value"><label>' + inputTemplateString + '</label></div>'
    );

    var inlineItemTemplate = _.template(
        '<label class="checkbox-inline js-value">' + inputTemplateString + '</label>'
    );

    return PlainEditor.extend({

        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            this.rootElement.find("input").change(_.bind(function() {
                this.doChanged();
            }, this));

            this.bind('submit.pgui.nested-insert', function ($insertButton, primaryKey, record) {
                this.addItem(
                    record[$insertButton.data('stored-field-name')],
                    record[$insertButton.data('display-field-name')],
                    true
                );
            }.bind(this));
        },

        getValue: function() {
            return _.toArray(this.rootElement.find('input:checked')
                .map(function(index, item) {
                    return $(item).attr('value');
                }));
        },

        getDisplayValue: function () {
            return _.toArray(this.rootElement.find('input:checked')
                .map(function (index, item) {
                    return $(item).closest('label').text();
                }));
        },

        setValue: function(value) {
            if (!value.split) {
                return;
            }

            var checkedValues = value.split(',');
            var index;

            this.rootElement.find("input").each(function(i, item) {
                var isChecked = false;
                for (index = 0; index < checkedValues.length; ++index) {
                    if ($(item).attr('value') == checkedValues[index]) {
                        $(item).prop('checked', true);
                        isChecked = true;
                        break;
                    }
                }

                if (!isChecked) {
                    $(item).prop('checked', false);
                }
            });
        },

        getEnabled: function() {
            return this.rootElement.find("input:enabled").length > 0;
        },

        setEnabled: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                var $currentCheckbox = new CheckBox($(item));
                $currentCheckbox.setEnabled(value);
            });
        },

        getReadonly: function() {
            return this.rootElement.find("input:enabled").length === 1;
        },

        setReadonly: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                var $currentCheckbox = new CheckBox($(item));
                $currentCheckbox.setReadonly(value);
            });
        },

        clear: function() {
            this.rootElement.find(".checkbox, .checkbox-inline").remove();
        },

        addItem: function(value, caption, checked) {
            var $editor = this.rootElement;
            var isInline = $editor.data('inline');
            var data = {
                editorName: $editor.attr('data-editor-name'),
                value: value,
                caption: caption
            };

            var $newItem = $(isInline ? inlineItemTemplate(data) : stackedItemTemplate(data));
            if (typeof(checked) === 'boolean' && checked) {
                $newItem.find("input").prop('checked', true);
            }

            if (this.getItemCount() > 0) {
                $editor.find('.js-value').last().after($newItem);
            } else {
                $editor.prepend($newItem);
            }

            return this;
        },

        removeItem: function(value) {
            this.rootElement.find("input[value='" + value + "']").parent().remove();
        },

        getItems: function()
        {
            return this.rootElement.find("input").map(function(i, item) {
                return { value: $(item).attr('value'), caption: $(item).closest('label').text() };
            });
        },

        getItemCount: function() {
            return  this.rootElement.find("input").length;
        },

        isMultivalue: function () {
            return true;
        },

        _getSetRequiredTarget: function () {
            return this.rootElement.find('input[type=checkbox]');
        }
    });

});
