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
            this.readOnly = this.rootElement.attr('data-editable') == 'false';
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
            return this.readOnly;
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

        enabled: function(value) { return true; }
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

        getValue: function()
        {
            return this.rootElement.val();
        },

        setValue: function(value)
        {
            this.rootElement.val(value);
        },

        isReadOnly: function()
        {
            return this.rootElement.attr('data-editable') == 'false';
        },

        enabled: function(value)
        {
            if (_.isUndefined(value))
            {
                return !this.rootElement.hasAttr('disabled');
            }
            else
            {
                if (this.enabled() != value)
                {
                    if (!value)
                    {
                        this.rootElement.addClass('disabled-editor');
                        this.rootElement.attr('disabled', 'true');
                    }
                    else
                    {
                        this.rootElement.removeClass('disabled-editor');
                        this.rootElement.removeAttr('disabled');
                    }
                }
            }
        }
    });

    exports.CheckBox =  exports.PlainEditor.extend({
        getValue: function()
        {
            return this.rootElement.is(':checked');
        },

        setValue: function(value)
        {
            this.rootElement.attr('checked', value);
        }
    });

    exports.TextEdit =  exports.PlainEditor.extend({  });

    exports.TextArea =  exports.PlainEditor.extend({  });

    exports.SpinEdit =  exports.PlainEditor.extend({  });

    exports.htmlEditorGlobalNotifier = new exports.EditorsGlobalNotifier();

    exports.HtmlEditor =  exports.CustomEditor.extend({
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
            /*exports.htmlEditorGlobalNotifier.onValueChanged(function(fieldName)
            {
                if (this.getFieldName() == fieldName)
                    this.doChanged();
            }.bind(this));*/
        },

        enabled: function(value)
        {
            if (_.isUndefined(value))
                return this.editor.getDoc().designMode.toLowerCase() == 'on';
            else
                this.editor.getDoc().designMode = (value ? 'On' : 'Off');
        },

        finalizeEditor: function()
        {
            /*if (tinyMCE)
                if (tinyMCE.get(this.getRootElement().attr('id')))
                    tinyMCE.get(this.getRootElement().attr('id')).remove();*/
        },

        getValue: function()
        {
            return this.editor.getContent();
        },

        setValue: function(value)
        {
            //this.rootElement.html(value);
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
        }
    });

    exports.ComboBoxEditor =  exports.PlainEditor.extend({

        getValue: function()
        {
            if (this.isReadOnly())
                return this.rootElement.text();
            else
                return this._super();
        },

        clear: function()
        {
            if (!this.isReadOnly())
            {
                this.rootElement.find("option:enabled[value!='']").remove();
            }
        },

        addItem: function(value, caption)
        {
            if (!this.isReadOnly())
            {
                this.rootElement.append($("<option></option>").attr('value', value).html(caption));
            }
        },

        getItems: function()
        {
            if (!this.isReadOnly())
            {
                return this.rootElement.find("option:enabled[value!='']").map(function(i, item)
                {
                    return { value: $(item).attr('value'), caption: $(item).text() };
                });
            }
            return [];
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

        clear: function()
        {
            if (!this.isReadOnly())
            {
                this.rootElement.find("label").remove();
            }
        },

        addItem: function(value, caption)
        {
            if (!this.isReadOnly())
            {
                this.rootElement.append(
                    $("<label></label>")
                        .append(
                            $("<input>")
                                .attr('value', value)
                                .attr('type', 'radio')
                                .attr('name', this.rootElement.attr('data-editor-name'))
                        )
                        .append($('<span></span>').text(caption))
                );
            }
        },

        getItems: function()
        {
            if (!this.isReadOnly())
            {
                return this.rootElement.find("input").map(function(i, item)
                {
                    return { value: $(item).attr('value'), caption: $(item).closest('label').text() };
                });
            }
            return [];
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
            if (_.isString(value))
            {
                this.rootElement.find("input").each(function(i, item)
                {
                    if ($(item).attr('value') == value)
                        $(item).attr('checked', true);
                });
            }
            else
            {
                this.rootElement.find("input").each(function(i, item)
                {
                    if ($(item).attr('value') == value)
                        $(item).attr('checked', true);
                });
            }

        },

        clear: function()
        {
            if (!this.isReadOnly())
            {
                this.rootElement.find("label").remove();
            }
        },

        addItem: function(value, caption)
        {
            if (!this.isReadOnly())
            {
                this.rootElement.append(
                    $("<label></label>")
                        .append(
                            $("<input>")
                                .attr('value', value)
                                .attr('type', 'checkbox')
                                .attr('name', this.rootElement.attr('data-editor-name'))
                                .attr('id', this.rootElement.attr('data-editor-name'))
                        )
                        .append($('<span></span>').text(caption))
                );
            }
        },

        getItems: function()
        {
            if (!this.isReadOnly())
            {
                return this.rootElement.find("input").map(function(i, item)
                {
                    return { value: $(item).attr('value'), caption: $(item).closest('label').text() };
                });
            }
            return [];
        }
    });

    exports.autoCompleteGlobalNotifier = new exports.EditorsGlobalNotifier();

    exports.AutoComplete =  exports.PlainEditor.extend({

        init: function(rootElement)
        {
            this._super(rootElement);
            /*exports.autoCompleteGlobalNotifier.onValueChanged(function(fieldName)
             {
             if (this.getFieldName() == fieldName)
             this.doChanged();

             }.bind(this));*/
        },

        getValue: function()
        {
            return this._super();
            /*return this.rootElement.find("input.autocomplete-hidden").attr('value');*/
        },

        setValue: function(value)
        {
            this._super(value);
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

        getValue: function()
        {
            return this.rootElement.find("[data-multileveledit-main]").val();
        },

        setValue: function(value)
        {
        }
    });



    exports.MaskEdit =  exports.PlainEditor.extend({  });

    exports.TimeEdit =  exports.PlainEditor.extend({  });

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
