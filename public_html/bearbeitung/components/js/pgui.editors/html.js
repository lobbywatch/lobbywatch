define(['pgui.editors/plain', 'underscore', 'trumbowyg',
    'trumbowyg.colors', 'trumbowyg.specialchars', 'trumbowyg.base64', 'trumbowyg.fontfamily', 'trumbowyg.fontsize',
    'trumbowyg.table', 'trumbowyg.preformatted', 'trumbowyg.history', 'trumbowyg.template'
    ], function (PlainEditor) {

    $.trumbowyg.svgPath = 'components/assets/img/vendor/trumbowyg/trumbowyg-icons.svg';

    return PlainEditor.extend({
        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;
            var templates = [];
            rootElement.closest('.form-group').find('.html-templates').children().each(function (i, el) {
                var $el = $(el);
                templates.push({name: $el.data('template-name'), html: $el.html()})
            });

            rootElement.trumbowyg(
                {
                    resetCss: true,
                    btnsDef: {
                        // Create a new dropdown
                        image: {
                            dropdown: ['insertImage', 'base64'],
                            ico: 'insertImage'
                        }
                    },
                    btns: [
                        ['viewHTML'],
                        ['historyUndo', 'historyRedo'],
                        ['formatting'],
                        ['strong', 'em', 'underline', 'del'],
                        ['superscript', 'subscript'],
                        ['fontfamily', 'fontsize'],
                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ['unorderedList', 'orderedList'],
                        ['link'],
                        ['table', 'image', 'specialChars'],
                        ['template'],
                        ['foreColor', 'backColor'],
                        'btnGrp-justify',
                        'btnGrp-lists',
                        ['preformatted'],
                        ['horizontalRule'],
                        ['removeformat'],
                        ['fullscreen']
                    ],
                    plugins: {
                        templates: templates
                    }
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
