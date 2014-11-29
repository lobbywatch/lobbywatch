define(function(require, exports)
{
    var Class   = require('class'),
        events  = require('microevent'),
        _       = require('underscore');

    exports.EditorsGlobalNotifier =  Class.extend({

        valueChanged: function(fieldName)
        {
            this.trigger('onValueChangedEvent', fieldName, 0);
        },

        onValueChanged: function(callback)
        {
            this.bind('onValueChangedEvent', callback);
        }
    });
    events.mixin(exports.EditorsGlobalNotifier);

    exports.CustomEditor =  Class.extend({

        /**
         * @param rootElement jQuery
         */
        init: function(rootElement)
        {
            this.rootElement = rootElement;
            this.fieldName = this.rootElement.attr('data-field-name');
        },

        doChanged: function()
        {
            this.trigger('onChangeEvent', this, 0);
        },

        getValue: function() { return null; },

        setValue: function(value) { },

        onChange: function(callback)
        {
            this.bind('onChangeEvent', callback);
        },

        finalizeEditor: function()
        { },

        getRootElement: function()
        {
            return this.rootElement;
        },

        getFieldName: function()
        {
            return this.fieldName;
        },

        isReadOnly: function()
        {
            return this.readonly();
        },

        visible: function(value) {
            var controlContainer = this.rootElement.closest('.control-group');
            if (controlContainer.length === 0) {
                return;
            }
            if (_.isUndefined(value)) {
                return controlContainer.is(':visible');
            }
            else {
                if (this.visible() != value) {
                    if (value) {
                        controlContainer.show();
                    }
                    else {
                        controlContainer.hide();
                    }
                }
            }
        },

        enabled: function(value) {
            if (_.isUndefined(value))
            {
                return this.getEnabled();
            }
            else
            {
                if (this.getEnabled() != value)
                {
                    this.setEnabled(value);
                }
            }
        },

        getEnabled: function() {
            return true;
        },

        setEnabled: function(value) {
            // nothing here
        },

        readonly: function(value) {
            if (_.isUndefined(value))
            {
                return this.getReadonly();
            }
            else
            {
                if (this.getReadonly() != value)
                {
                    this.setReadonly(value);
                }
            }
        },

        getReadonly: function() {
            return false;
        },

        setReadonly: function(value) {
            // nothing here
        },

        updateState: function() {
            if (this.rootElement.attr('disabled')) {
                this.setEnabled(false);
            }
            if (this.rootElement.attr('readonly')) {
                this.setReadonly(true);
            }
        }

    });
    events.mixin(exports.CustomEditor);

    exports.PlainEditor = exports.CustomEditor.extend({
        init: function(rootElement)
        {
            this._super(rootElement);
            this.rootElement.change(
                _.bind(function() { this.doChanged(); }, this)
            );
        },

        _getAttribute: function(attrName) {
            return this.rootElement.attr(attrName);
        },

        _setAttribute: function(attrName, value) {
            this.rootElement.attr(attrName, value);
        },

        getValue: function()
        {
            return this.rootElement.val();
        },

        setValue: function(value)
        {
            this.rootElement.val(value);
        },

        getEnabled: function() {
            return !this.rootElement.hasAttr('disabled');
        },

        setEnabled: function(value) {
            if (!value)
            {
                this.rootElement.attr('disabled', 'true');
            }
            else
            {
                this.rootElement.removeAttr('disabled');
            }
        },

        getReadonly: function() {
            return this.rootElement.hasAttr('readonly');
        },

        setReadonly: function(value) {
            if (value)
            {
                this.rootElement.attr('readonly', 'true');
            }
            else
            {
                this.rootElement.removeAttr('readonly');
            }
        }
    });

    var CheckBox = exports.CheckBox =  exports.PlainEditor.extend({
        getValue: function()
        {
            return this.rootElement.is(':checked');
        },

        setValue: function(value)
        {
            this.rootElement.attr('checked', value);
        },

        setReadonly: function(value) {
            this._super(value);
            var $editor = this.rootElement;
            require(['jquery.bind-first'], function() {
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
                }
                else {
                    $editor.off("click.SQLMaestro");
                    $editor.off("change.SQLMaestro");
                }
            });
        }
    });

    exports.TextEdit =  exports.PlainEditor.extend({

        getPlaceholder: function() {
            return this._getAttribute('placeholder');
        },

        setPlaceholder: function(value) {
            this._setAttribute('placeholder', value);
        }
    });

    exports.TextArea =  exports.PlainEditor.extend({

        getPlaceholder: function() {
            return this._getAttribute('placeholder');
        },

        setPlaceholder: function(value) {
            this._setAttribute('placeholder', value);
        }
    });

    exports.SpinEdit =  exports.PlainEditor.extend({  });

    exports.MaskEdit =  exports.PlainEditor.extend({  });

    exports.ColorEdit =  exports.PlainEditor.extend({  });

    exports.RangeEdit =  exports.PlainEditor.extend({  });

    exports.htmlEditorGlobalNotifier = new exports.EditorsGlobalNotifier();

    exports.HtmlEditor =  exports.PlainEditor.extend({
        init: function(rootElement)
        {
            this._super(rootElement);
            this.editor = null;
            var self = this;
            if (this.rootElement.data('editor-class')) {
                this.editor = this.rootElement.data('editor-class');
                this.editor.onChange(function() {
                    self.doChanged();
                });
            }
        },

        getEditorComponent: function() {
            return this.rootElement.ckeditorGet();
        },

        getEnabled: function() {
            return !this.getEditorComponent().readOnly;
        },

        setEnabled: function(value) {
            this._super(value);
            this.getEditorComponent().setReadOnly(!value);
        },

        getReadonly: function() {
            return this.getEditorComponent().readOnly;
        },

        setReadonly: function(value) {
            this._super(value);
            this.setEnabled(!value);
        }

    });

    exports.dateTimeGlobalNotifier = new exports.EditorsGlobalNotifier();

    exports.DateTimeEdit =  exports.PlainEditor.extend({

        init: function(rootElement)
        {
            this._super(rootElement);
            exports.dateTimeGlobalNotifier.onValueChanged(_.bind(function(fieldName)
            {
                if (this.getFieldName() == fieldName)
                    this.doChanged();
            }, this));
        },

        _enableCalendarButton: function(value) {
            var $calendarButton =  this._getCalendarButton();
            if ($calendarButton.length !== 0) {
                if (!value) {
                    $calendarButton.attr('disabled', true);
                }
                else {
                    $calendarButton.removeAttr('disabled');
                }
            }
        },

        _getCalendarButton: function() {
            return this.rootElement.next('button.pgui-date-time-edit-picker');
        },

        setEnabled: function(value) {
            this._super(value);
            this._enableCalendarButton(value);
        },

        setReadonly: function(value) {
            this._super(value);
            this._enableCalendarButton(!value);
        }
    });

    exports.TimeEdit =  exports.PlainEditor.extend({

        _hideShowSpinner: function(value) {
            if (value) {
                this.rootElement.next('.timeEntry-control').hide();
            }
            else {
                this.rootElement.next('.timeEntry-control').show();
            }
        },

        setReadonly: function(value) {
            this._super(value);
            this._hideShowSpinner(value);
        },

        setEnabled: function(value) {
            this._super(value);
            this._hideShowSpinner(!value);
        }

    });

    exports.ComboBoxEditor =  exports.PlainEditor.extend({

        clear: function()
        {
            this.rootElement.find("option:enabled[value!='']").remove();
        },

        addItem: function(value, caption)
        {
            this.rootElement.append($("<option></option>").attr('value', value).html(caption));
        },

        removeItem: function(value) {
            this.rootElement.find("option[value='" + value + "']").remove();
        },

        getItems: function()
        {
            return this.rootElement.find("option:enabled[value!='']").map(function(i, item) {
                return { value: $(item).attr('value'), caption: $(item).text() };
            });
        },

        getItemCount: function() {
           return  this.rootElement.find("option[value!='']").length;
        },

        setReadonly: function(value) {
            this._super(value);
            var $editor =  this.rootElement;
            this.rootElement.find("option").each(function(i, item)
            {
                if (value) {
                    if ($(item).attr('value') !== $editor.val()) {
                        $(item).attr('disabled', true);
                    }
                }
                else {
                    $(item).removeAttr('disabled');
                }
            });
        }
    });

    exports.RadioEdit =  exports.CustomEditor.extend({

        init: function(rootElement)
        {
            this._super(rootElement);
            this.rootElement.find("input").change(_.bind(function() {
                this.doChanged();
            }, this));
        },

        getValue: function() {
            return this.rootElement.find("input:checked").val();
        },

        setValue: function(value) {
            this.rootElement.find("input").each(function(i, item)
            {
                if ($(item).attr('value') == value)
                    $(item).attr('checked', true);
            });
        },

        getEnabled: function() {
            return this.rootElement.find("input:enabled").length > 0;
        },

        setEnabled: function(value) {
            this.rootElement.find("input").each(function(i, item)
            {
                if (!value) {
                    $(item).attr('disabled', true);
                }
                else {
                    $(item).removeAttr('disabled');
                }
            });
        },

        getReadonly: function() {
            return this.rootElement.find("input:enabled").length === 1;
        },

        setReadonly: function(value) {
            this.rootElement.find("input").each(function(i, item)
            {
                if (value) {
                    if (!($(item).attr('checked'))) {
                        $(item).attr('disabled', true);
                    }
                }
                else {
                    $(item).removeAttr('disabled');
                }
            });
        },

        clear: function()
        {
            this.rootElement.find("label").remove();
        },

        addItem: function(value, caption)
        {
            this.rootElement.append(
                $("<label></label>")
                    .append(
                        $("<input>")
                            .attr('name', this.rootElement.attr('data-editor-name'))
                            .attr('value', value)
                            .attr('type', 'radio')
                            .attr('data-legacy-field-name', this.getFieldName())
                            .attr('data-pgui-legacy-validate', true)
                    )
                    .addClass('radio')
                    .append(caption)
            );
        },

        removeItem: function(value) {
            this.rootElement.find("input[value='" + value + "']").parent().remove();
        },

        getItems: function()
        {
            return this.rootElement.find("input").map(function(i, item)
            {
                return { value: $(item).attr('value'), caption: $(item).closest('label').text() };
            });
        },

        getItemCount: function() {
            return  this.rootElement.find("input").length;
        }
    });

    exports.CheckBoxGroup =  exports.CustomEditor.extend({

        init: function(rootElement)
        {
            this._super(rootElement);
            this.rootElement.find("input").change(_.bind(function() {
                this.doChanged();
            }, this));
        },

        getValue: function()
        {
            return _.toArray(this.rootElement.find("input:checked").map(function(index, item)
                {
                    return $(item).attr('value');
                }));
        },

        setValue: function(value)
        {
            var checkedValues = value.split(',');
            var index;
            this.rootElement.find("input").each(function(i, item) {
                for (index = 0; index < checkedValues.length; ++index) {
                    if ($(item).attr('value') == checkedValues[index])
                        $(item).attr('checked', true);
                }
            });
        },

        getEnabled: function() {
            return this.rootElement.find("input:enabled").length > 0;
        },

        setEnabled: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                var $currentCheckbox = new CheckBox($(item));
                $currentCheckbox.setEnabled(value);
            });
        },

        getReadonly: function() {
            return this.rootElement.find("input:enabled").length === 1;
        },

        setReadonly: function(value) {
            this.rootElement.find("input").each(function(i, item) {
                var $currentCheckbox = new CheckBox($(item));
                $currentCheckbox.setReadonly(value);
            });
        },

        clear: function()
        {
            this.rootElement.find("label").remove();
        },

        addItem: function(value, caption)
        {
            var $editor = this.rootElement;
            $editor.append(
                $("<label></label>")
                    .append(
                        $("<input>")
                            .attr('type', 'checkbox')
                            .attr('name', $editor.attr('data-editor-name') + '[]')
                            .attr('value', value)
                            .attr('data-legacy-field-name', this.getFieldName())
                            .attr('data-pgui-legacy-validate', true)
                    )
                    .addClass('checkbox')
                    .append(caption)
            );
        },

        removeItem: function(value) {
            this.rootElement.find("input[value='" + value + "']").parent().remove();
        },

        getItems: function()
        {
            return this.rootElement.find("input").map(function(i, item)
            {
                return { value: $(item).attr('value'), caption: $(item).closest('label').text() };
            });
        },
        getItemCount: function() {
            return  this.rootElement.find("input").length;
        }
    });

    exports.autoCompleteGlobalNotifier = new exports.EditorsGlobalNotifier();

    exports.AutoComplete =  exports.PlainEditor.extend({

        setValue: function(value)
        { /* undone yet */ },

        setReadonly: function(value) {
            this.setEnabled(!value);
        }

    });

    exports.multiLevelAutoCompleteGlobalNotifier = new exports.EditorsGlobalNotifier();

    exports.MultiLevelAutoComplete =  exports.CustomEditor.extend({

        init: function(rootElement)
        {
            this._super(rootElement);
            exports.multiLevelAutoCompleteGlobalNotifier.onValueChanged(_.bind(function(fieldName)
            {
                if (this.getFieldName() == fieldName)
                    this.doChanged();

            }, this));
        },

        _getMainControl: function() {
            return this.rootElement.find("[data-multileveledit-main]");
        },

        getValue: function()
        {
            return this._getMainControl().val();
        },

        setValue: function(value)
        { /* undone yet */ },

        getEnabled: function() {
            return !this._getMainControl().attr('disabled');
        },

        setEnabled: function(value) {
            this._super(value);
            if (!value) {
                this.rootElement.find('select').attr('disabled', true);
            }
            else {
                this.rootElement.find('select').removeAttr('disabled');
            }
        },

        getReadonly: function() {
            return !this.getEnabled();
        },

        setReadonly: function(value) {
            this.setEnabled(!value);
        }
    });

    exports.EditorsController =  Class.extend({

        init: function(context)
        {
            this.context = context;
            this.editors = {};
            this.editorClasses =
            {
                TextEdit: exports.TextEdit,
                TextArea: exports.TextArea,
                SpinEdit: exports.SpinEdit,
                ColorEdit: exports.ColorEdit,
                RangeEdit: exports.RangeEdit,
                HtmlEditor: exports.HtmlEditor,
                DateTimeEdit: exports.DateTimeEdit,
                ComboBox: exports.ComboBoxEditor,
                MaskEdit: exports.MaskEdit,
                TimeEdit: exports.TimeEdit,
                CheckBox: exports.CheckBox,
                RadioGroup: exports.RadioEdit,
                CheckBoxGroup: exports.CheckBoxGroup,
                Autocomplete: exports.AutoComplete,
                MultiLevelAutocomplete: exports.MultiLevelAutoComplete
            };
        },

        finalizeEditors: function()
        {
            for (var name in this.editors)
            {
                this.editors[name].finalizeEditor();
            }
        },

        captureEditors: function()
        {
            this._processEditors();
            this.trigger('oninitdEvent', this.editors);
        },

        _getEditorClassByString: function(className)
        {
            for(var name in this.editorClasses) {
                if (name == className)
                    return this.editorClasses[name];
            }
        },

        _processEditors: function()
        {
            this.context.find("*[data-editor='true']").each(_.bind(function(index, item)
            {
                var dataEditorClassStr = $(item).attr('data-editor-class');
                var editor = new (this._getEditorClassByString(dataEditorClassStr))($(item));

                this.editors[editor.getFieldName()] = editor;
                editor.onChange(_.bind(function(sender) {
                    this.trigger('onEditorValueChangeEvent', sender, this.editors)
                }, this));
            }, this));
        },

        oninitd: function(callback)
        {
            this.bind('oninitdEvent', callback);
        },

        onEditorValueChange: function(callback)
        {
            this.bind('onEditorValueChangeEvent', callback);
        }
    });
    events.mixin(exports.EditorsController);

    exports.DataOperation = {
        Edit: 1,
        Insert: 2
    };

    exports.InitEditorsController = function(operation, context)
    {
        var callBacks =
        {
            EditorValuesChanged: function(){},
            InitForm: function(){}
        };

        if (operation == exports.DataOperation.Edit)
        {
            if (_.isFunction(window.EditForm_EditorValuesChanged))
                callBacks.EditorValuesChanged = EditForm_EditorValuesChanged;
            if (_.isFunction(window.EditForm_initd))
                callBacks.InitForm = EditForm_initd;
        }
        else if (operation == exports.DataOperation.Insert)
        {
            if (_.isFunction(window.InsertForm_EditorValuesChanged))
                callBacks.EditorValuesChanged = InsertForm_EditorValuesChanged;
            if (_.isFunction(window.InsertForm_initd))
                callBacks.InitForm = InsertForm_initd;
        }

        var editorsController = new exports.EditorsController(context);
        editorsController.oninitd(function(editors)
        {
            callBacks.InitForm(editors);
        });
        editorsController.onEditorValueChange(function(sender, editors)
        {
            callBacks.EditorValuesChanged(sender, editors);
        });
        editorsController.captureEditors();
        return editorsController;
    }

});
