define(function (require, exports) {
    var _ = require('underscore');
    var CustomEditor = require('pgui.editors/custom').CustomEditor;

    exports.PlainEditor = CustomEditor.extend({
        init: function(rootElement)
        {
            this._super(rootElement);
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

        getValue: function()
        {
            return this.rootElement.val();
        },

        setValue: function(value)
        {
            this.rootElement.val(value);
        },

        getEnabled: function() {
            return !this.rootElement.hasAttr('disabled');
        },

        setEnabled: function(value) {
            if (!value) {
                this.rootElement.attr('disabled', 'true');
            } else {
                this.rootElement.removeAttr('disabled');
            }
        },

        getReadonly: function() {
            return this.rootElement.hasAttr('readonly');
        },

        setReadonly: function(value) {
            if (value) {
                this.rootElement.attr('readonly', 'true');
            } else {
                this.rootElement.removeAttr('readonly');
            }
        },

        doChanged: function() {
            this.trigger('onChangeEvent', this, 0);
        },

        onChange: function(callback) {
            this.bind('onChangeEvent', callback);
        }

    });
});