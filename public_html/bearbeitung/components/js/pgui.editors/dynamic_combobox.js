define([
    'pgui.editors/custom',
    'pgui.editors/select2_format',
    'locales/select2_locale',
    'libs/select2'
], function (CustomEditor, FormatFunctions) {

    return CustomEditor.extend({

        init: function (rootElement, readyCallback) {
            this.queryFunction = function (term) { return {term: term}; };
            this._super(rootElement, readyCallback);
            this.formatFunctions = new FormatFunctions();
            this.formatFunctions.setFormatCallbacksFromElement($(rootElement));
            this.processElement(rootElement);

            this.bind('submit.pgui.nested-insert', function ($insertButton, primaryKey) {
                this.setValue(primaryKey);
            }.bind(this));
        },

        processElement: function () {
            var self = this;
            var $el = $(this.rootElement);

            $el.on("change", function (e, ignoreInternal) {
                if (typeof $el.valid === 'function' ) {
                    var $form  = $(this.form);
                    if ($form.length && _.keys($form.data('validator').submitted).length) {
                        $el.valid();
                    }
                }

                if (!ignoreInternal) {
                    self.doChanged();
                }
            });

            $('label[for=' + $el.attr('id') + ']').on('click', function () {
                $el.select2('focus');
            });

            $el.select2({
                ajax: {
                    url: $el.attr('data-url'),
                    dataType: 'json',
                    results: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {id: item.id, text: item.value, formatted_value: item.formatted_value, fields: item.fields};
                            })
                        };
                    },
                    data: function (term) {
                        return self.queryFunction(term);
                    }
                },
                initSelection: function (element, callback) {
                    var id = element.val();

                    $.ajax({
                        url: $el.data("url"),
                        data: {id: id},
                        dataType: "json"
                    }).success(function (data) {
                        $.each(data, function (k, item) {
                            if (item.id == id) {
                                callback({
                                    id: item.id,
                                    text: item.value,
                                    formatted_value: item.formatted_value,
                                    fields: item.fields
                                });
                                self.rootElement.trigger('select2-init');
                                return false;
                            }
                        });
                    });
                },
                width: '100%',
                minimumInputLength: $el.data('minimal-input-length'),
                allowClear: $el.data('allowclear'),
                formatSelection: self.formatFunctions.getFormatClojure('selection'),
                formatResult: self.formatFunctions.getFormatClojure('result')
            });
        },

        setValue: function(id) {
            this.rootElement.val(id);
            if (this.rootElement.select2) {
                this.rootElement.select2('val', this.rootElement.val());
                this.rootElement.trigger("change");
            }
            return this;
        },

        getValue: function () {
            return this.rootElement.select2('val');
        },

        getDisplayValue: function () {
            var data = this.getData();
            return data && data.text
                ? data.text
                : this.getValue();
        },

        getData: function () {
            return this.rootElement.select2('data');
        },

        setData: function (data) {
            this.rootElement.select2('data', data);
            return this;
        },

        setEnabled: function (value) {
            this.rootElement.prop('disabled', !value);
            this.rootElement.select2('enable', !!value);
            return this;
        },

        getEnabled: function () {
            return !this.rootElement.prop('disabled');
        },

        getReadonly: function () {
            return !!this.rootElement.prop('readonly');
        },

        setReadonly: function (value) {
            this.rootElement.select2('readonly', !!value);
            this.rootElement.prop('readonly', !!value);
            return this;
        },

        setFormatSelection: function (callback) {
            this.formatFunctions.setCallback('selection', callback);
            $(this.rootElement).trigger('change', true);
            return this;
        },

        setFormatResult: function (callback) {
            this.formatFunctions.setCallback('result', callback);
            $(this.rootElement).trigger('change', true);
            return this;
        },

        setQueryFunction: function (fn) {
            this.queryFunction = fn;
            return this;
        }
    });
});