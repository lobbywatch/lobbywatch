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
        '<div class="checkbox"><label>' + inputTemplateString + '</label></div>'
    );

    var inlineItemTemplate = _.template(
        '<label class="checkbox-inline">' + inputTemplateString + '</label>'
    );

    return PlainEditor.extend({

        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            this.rootElement.find("input").change(_.bind(function() {
                this.doChanged();
            }, this));
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
                for (index = 0; index < checkedValues.length; ++index) {
                    if ($(item).attr('value') == checkedValues[index]) {
                        $(item).attr('checked', true);
                    }
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
            this.rootElement.find("label").remove();
        },

        addItem: function(value, caption) {
            var $editor = this.rootElement;
            var isInline = $editor.data('inline');
            var data = {
                editorName: $editor.attr('data-editor-name'),
                value: value,
                caption: caption
            };

            $editor.append(
                ($(isInline ? inlineItemTemplate(data) : stackedItemTemplate(data)))
            );

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
