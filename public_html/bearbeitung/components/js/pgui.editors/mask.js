define([
    'pgui.editors/plain',
    'jquery.maskedinput'
], function (PlainEditor) {

    return PlainEditor.extend({
        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            if (rootElement.attr('mask') != '') {
                rootElement.mask(rootElement.attr('mask'));
            }
        }
    });

});
