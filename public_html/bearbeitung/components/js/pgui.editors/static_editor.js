define([
    'pgui.editors/custom'
], function (CustomEditor) {

    return CustomEditor.extend({

        getValue: function() {
            return this.rootElement.html();
        },

        setValue: function(value) {
            this.rootElement.html(value);
            return this;
        }

    });

});
