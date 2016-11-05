define([
    'class',
    'microevent',
    'underscore',
    'pgui.editors/autocomplete',
    'pgui.editors/checkbox',
    'pgui.editors/checkboxgroup',
    'pgui.editors/combobox',
    'pgui.editors/custom',
    'pgui.editors/datetime',
    'pgui.editors/html',
    'pgui.editors/image_uploader',
    'pgui.editors/multilevel_autocomplete',
    'pgui.editors/multivalue_select',
    'pgui.editors/remote_multivalue_select',
    'pgui.editors/plain',
    'pgui.editors/radio',
    'pgui.editors/text',
    'pgui.editors/mask'
], function (
    Class,
    events,
    _,
    Autocomplete,
    Checkbox,
    CheckboxGroup,
    Combobox,
    Custom,
    DateTime,
    Html,
    ImageUploader,
    MultiLevelAutocomplete,
    MultiValueSelect,
    RemoteMultiValueSelect,
    Plain,
    Radio,
    Text,
    Mask
) {

    var editorNames = {
        autocomplete: Autocomplete,
        checkbox: Checkbox,
        checkboxgroup: CheckboxGroup,
        color: Plain,
        combobox: Combobox,
        datetime: DateTime,
        html_wysiwyg: Html,
        imageuploader: ImageUploader,
        masked: Mask,
        multilevel_selection: MultiLevelAutocomplete,
        multivalue_select: MultiValueSelect,
        remote_multivalue_select: RemoteMultiValueSelect,
        radio: Radio,
        range: Plain,
        spin: Plain,
        text: Text,
        textarea: Text,
        time: DateTime
    };

    var EditorsController = events.mixin(Class.extend({
        init: function (context) {
            this.context = context;
            this.editors = {};
        },

        captureEditors: function (readyCallback) {
            var $editors = this.context.find('[data-editor]');
            var pendingEditorsCount = $editors.length;

            $editors.each(_.bind(function (index, item) {
                var $item = $(item);
                var editorName = $item.data('editor');

                var editor = new editorNames[editorName]($item, _.bind(function (editor) {
                    pendingEditorsCount--;

                    this.editors[editor.getFieldName()] = editor;
                    $item.data('editor', editor);

                    if (pendingEditorsCount <= 0) {
                        this.trigger('oninitdEvent', this.editors);

                        if (_.isFunction(readyCallback)) {
                            readyCallback(this);
                        }
                    }
                }, this));

                editor.onChange(_.bind(function (sender) {
                    this.trigger('onEditorValueChangeEvent', sender, this.editors)
                }, this));
            }, this));
        },

        oninitd: function (callback) {
            this.bind('oninitdEvent', callback);
        },

        onEditorValueChange: function (callback) {
            this.bind('onEditorValueChangeEvent', callback);
        },

        destroy: function () {
            _.each(this.editors, function (editor) {
                editor.destroy();
            });
        }
    }));

    return {
        init: function ($container, readyCallback) {
            var formId = $container.is('form')
                ? $container.attr('id')
                : $container.find('form').first().attr('id');

            var editorValuesChangedCallback = window[formId + '_EditorValuesChanged'];
            var initFormCallback = window[formId + '_initd'];
            var editorsController = new EditorsController($container);

            editorsController.oninitd(function (editors) {
                if (_.isFunction(initFormCallback)) {
                    try {
                        initFormCallback(editors);
                    } catch (err) {
                        console.error(err);
                    }
                }
            });

            editorsController.onEditorValueChange(function (sender, editors) {
                if (_.isFunction(editorValuesChangedCallback)) {
                    try {
                        editorValuesChangedCallback(sender, editors);
                    } catch (err) {
                        console.error(err);
                    }
                }
            });

            editorsController.captureEditors(readyCallback);

            return editorsController;
        },

        getEditorByName: function (name) {
            return editorNames[name];
        }
    };
});
