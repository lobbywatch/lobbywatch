define(['pgui.editors/plain', 'jquery.bind-first', 'bootstrap.toggle'], function (PlainEditor) {

    return PlainEditor.extend({
        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);

            if (rootElement.attr('data-editor') === 'toggle') {
                var toggleOptions  = {
                    on: rootElement.data('toggle-on-caption'),
                    off: rootElement.data('toggle-off-caption'),
                    size: rootElement.data('toggle-size'),
                    onstyle: rootElement.data('toggle-on-style'),
                    offstyle: rootElement.data('toggle-off-style')
                };
                if (rootElement.data('toggle-width')) {
                    toggleOptions.width = rootElement.data('toggle-width');
                }
                if (rootElement.data('toggle-height')) {
                    toggleOptions.height = rootElement.data('toggle-height');
                }
                rootElement.bootstrapToggle(toggleOptions);
            }
        },

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
                });
            } else {
                $editor.off("click.AUX");
                $editor.off("change.AUX");
            }

            return this;
        }
    });
});
