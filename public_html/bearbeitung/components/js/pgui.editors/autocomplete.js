define([
    'pgui.editors/plain',
    'jquery.autocomplete'
], function (PlainEditor) {

    return PlainEditor.extend({

        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;

            rootElement.autocomplete({
                serviceUrl: rootElement.data('url'),
                minChars: rootElement.data('minimum-input-length'),
                onSelect: function (suggestion) {
                    self.doChanged();
                }
            });
        }

    });

});
