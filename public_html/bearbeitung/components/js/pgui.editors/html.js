define(['pgui.editors/plain', 'trumbowyg', 'underscore'], function (PlainEditor) {

    $.trumbowyg.svgPath = 'components/assets/img/trumbowyg-icons.svg';

    return PlainEditor.extend({
        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;
            rootElement.trumbowyg({resetCss: true});
            this.rootElement.on('tbwchange', function () {
                self.doChanged();
            });
        },

        getValue: function (value) {
            return this.rootElement.trumbowyg('html');
        },

        setValue: function (value) {
            this.rootElement.trumbowyg('html', value);
            return this;
        },

        setEnabled: function(value) {
            this._super(value);
            this.rootElement.trumbowyg(value ? 'enable' : 'disable');
            return this;
        },

        setReadonly: function(value) {
            this._super(value);
            this.setEnabled(!value);
            return this;
        },

        destroy: function() {
            this.rootElement.trumbowyg('destroy');
        }
    });

});
