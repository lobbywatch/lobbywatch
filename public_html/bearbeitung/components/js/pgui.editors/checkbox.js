define(['pgui.editors/plain', 'jquery.bind-first'], function (PlainEditor) {

    return PlainEditor.extend({
        getValue: function() {
            return this.rootElement.is(':checked');
        },

        setValue: function(value) {
            this.rootElement.prop('checked', !!value);
            return this;
        },

        setReadonly: function(value) {
            this._super(value);
            var $editor = this.rootElement;

            if (value) {
                $editor.onFirst("click.AUX", function() {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                });
                $editor.onFirst("change.AUX", function(event) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                })
            } else {
                $editor.off("click.AUX");
                $editor.off("change.AUX");
            }

            return this;
        }
    });
});
