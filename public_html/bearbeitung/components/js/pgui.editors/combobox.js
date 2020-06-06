define([
    'pgui.editors/plain',
], function (PlainEditor) {

    return PlainEditor.extend({

        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);

            this.setReadonly(this.getReadonly());
            this.bind('submit.pgui.nested-insert', function ($insertButton, primaryKey, record) {
                this.addItem(
                    primaryKey,
                    record[$insertButton.data('display-field-name')]
                );
                this.setValue(primaryKey);
            }.bind(this));
        },

        clear: function() {
            this.rootElement.find("option:enabled[value!='']").remove();
            return this;
        },

        addItem: function(value, caption) {
            this.rootElement.append($("<option></option>").attr('value', value).html(caption));
            return this;
        },

        removeItem: function(value) {
            this.rootElement.find("option[value='" + value + "']").remove();
            return this;
        },

        getItems: function() {
            return this.rootElement.find("option:enabled[value!='']").map(function(i, item) {
                return { value: $(item).attr('value'), caption: $(item).text() };
            });
        },

        getItemCount: function() {
           return  this.rootElement.find("option[value!='']").length;
        },

        setValue: function (value) {
            this.setReadonly(false);
            this._super(value);
            this.setReadonly(this.getReadonly());
        },

        setReadonly: function(isReadonly) {
            this._super(isReadonly);
            var value = this.getValue();
            this.rootElement.find("option").each(function(i, item) {
                var $item = $(item);
                $item.prop(
                    'disabled',
                    isReadonly && $item.attr('value') != value
                );
            });

            return this;
        },

        getCaption: function() {
            return this.rootElement.find("option:selected").text();
        },

        getDisplayValue: function () {
            return this.rootElement.find('option:selected').first().text();
        }

    });

});
