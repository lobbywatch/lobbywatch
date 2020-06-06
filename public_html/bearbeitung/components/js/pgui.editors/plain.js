define([
    'underscore',
    'pgui.editors/custom'
], function (_, CustomEditor) {

    return CustomEditor.extend({
        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            this.rootElement.change(
                _.bind(function() { this.doChanged(); }, this)
            );
        },

        _getAttribute: function(attrName) {
            return this.rootElement.attr(attrName);
        },

        _setAttribute: function(attrName, value) {
            this.rootElement.attr(attrName, value);
        },

        getValue: function() {
            return this.rootElement.val();
        },

        setValue: function(value) {
            this.rootElement.val(value);
            return this;
        },

        getEnabled: function() {
            return !this.rootElement.prop('disabled');
        },

        setEnabled: function(value) {
            this.rootElement.prop('disabled', !value);
            return this;
        },

        getReadonly: function() {
            return Boolean(this.rootElement.attr('readonly'));
        },

        setReadonly: function(value) {
            this.rootElement.prop('readonly', value);
            return this;
        },

        doChanged: function() {
            this.trigger('onChangeEvent', this, 0);
        },

        onChange: function(callback) {
            this.bind('onChangeEvent', callback);
            return this;
        }

    });
});
