define(['pgui.editors/plain'], function (PlainEditor) {

    return PlainEditor.extend({
        getPlaceholder: function() {
            return this._getAttribute('placeholder');
        },

        setPlaceholder: function(value) {
            this._setAttribute('placeholder', value);
            return this;
        }
    });
});
