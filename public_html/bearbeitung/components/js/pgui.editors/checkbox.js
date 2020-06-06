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
                $editor.onFirst("click.SQLMaestro", function() {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                });
                $editor.onFirst("change.SQLMaestro", function(event) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    return false;
                })
            } else {
                $editor.off("click.SQLMaestro");
                $editor.off("change.SQLMaestro");
            }

            return this;
        }
    });
});
