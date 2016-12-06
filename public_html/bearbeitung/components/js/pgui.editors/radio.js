define(['pgui.editors/custom', 'underscore'], function (CustomEditor, _) {

    var inputTemplateString = '<input'
        + ' type="radio"'
        + ' name="<%=editorName%>"'
        + ' value="<%=value%>">'
        + '<%=caption%>';

    var stackedItemTemplate = _.template(
        '<div class="radio js-value"><label>' + inputTemplateString + '</label></div>'
    );

    var inlineItemTemplate = _.template(
        '<label class="radio-inline js-value">' + inputTemplateString + '</label>'
    );

    return CustomEditor.extend({

        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            this.rootElement.find("input").change(_.bind(function() {
                this.doChanged();
            }, this));

            this.bind('submit.pgui.nested-insert', function ($insertButton, primaryKey, record) {
                this.addItem(
                    primaryKey,
                    record[$insertButton.data('display-field-name')]
                );
                this.setValue(primaryKey);
            }.bind(this));
        },

        getValue: function() {
            return this.rootElement.find("input:checked").val();
        },

        setValue: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                if ($(item).attr('value') == value) {
                    $(item).attr('checked', true);
                }
            });

            return this;
        },

        getEnabled: function() {
            return this.rootElement.find("input:enabled").length > 0;
        },

        setEnabled: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                if (!value) {
                    $(item).attr('disabled', true);
                }
                else {
                    $(item).removeAttr('disabled');
                }
            });

            return this;
        },

        getReadonly: function() {
            return this.rootElement.find("input:enabled").length === 1;
        },

        setReadonly: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                if (value) {
                    if (!($(item).attr('checked'))) {
                        $(item).attr('disabled', true);
                    }
                } else {
                    $(item).removeAttr('disabled');
                }
            });

            return this;
        },

        clear: function() {
            this.rootElement.find("label").remove();
            return this;
        },

        addItem: function(value, caption) {
            var $editor = this.rootElement;
            var isInline = $editor.data('inline');
            var data = {
                editorName: $editor.attr('data-editor-name'),
                value: value,
                caption: caption
            };

            $editor.find('.js-value').last().after($(
                isInline ? inlineItemTemplate(data) : stackedItemTemplate(data)
            ));

            return this;
        },

        removeItem: function(value) {
            this.rootElement.find("input[value='" + value + "']").parent().remove();
            return this;
        },

        getItems: function() {
            return this.rootElement.find("input").map(function(i, item) {
                return { value: $(item).attr('value'), caption: $(item).closest('label').text() };
            });
        },

        getItemCount: function() {
            return  this.rootElement.find("input").length;
        },

        getCaption: function() {
            return this.rootElement.find("input:checked").parent().text();
        },

        _getSetRequiredTarget: function () {
            return this.rootElement.find('input[type=radio]');
        }
    });

});
