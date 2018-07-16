define(['pgui.editors/plain', 'trumbowyg', 'trumbowyg.colors', 'underscore'], function (PlainEditor) {

    $.trumbowyg.svgPath = 'components/assets/img/trumbowyg-icons.svg';

    return PlainEditor.extend({
        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;
            rootElement.trumbowyg(
                {
                    resetCss: true,
                    btns: [
                        ['viewHTML'],
                        ['undo', 'redo'],
                        ['formatting'],
                        'btnGrp-design',
                        ['link'],
                        ['insertImage'],
                        'btnGrp-justify',
                        'btnGrp-lists',
                        ['preformatted'],
                        ['horizontalRule'],
                        ['removeformat'],
                        ['foreColor', 'backColor'],
                        ['fullscreen']
                    ]
                }
            );
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
