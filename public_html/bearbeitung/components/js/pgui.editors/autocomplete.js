define(function (require, exports) {
	
    require('pgui.editors/select2_localizer');

    var CustomEditor = require('pgui.editors/custom').CustomEditor; 
    var FormatFunctions = require('pgui.editors/select2_format').FormatFunctions; 
    var EditorsGlobalNotifier = require('pgui.editors/global_notifier').EditorsGlobalNotifier;

	exports.autoCompleteGlobalNotifier = new EditorsGlobalNotifier();
    
    exports.AutoComplete = CustomEditor.extend({

        init: function (rootElement) {
            this._super(rootElement);
            this.formatFunctions = new FormatFunctions();
            this.formatFunctions.setFormatCallbacksFromElement($(rootElement));
            this.processElement(rootElement);
        },

        processElement: function () {
            var self = this;
            var $el = $(this.rootElement);

            $el.on("change", function (e, ignoreInternal) {
                if (!ignoreInternal) {
                    self.doChanged();
                }
            });

            require(['libs/select2'], function () {
                $el.select2({
                    ajax: {
                        url: $el.attr('data-url'),
                        dataType: 'json',
                        results: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {id: item.id, text: item.value};
                                })
                            };
                        },
                        data: function (term) {
                            return {term: term};
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
                                        text: item.value
                                    });
                                    return false;
                                }
                            });
                        });
                    },
                    width: function () {
                        return $el.width();
                    },
                    minimumInputLength: $el.data('minimal-input-length'),
                    allowClear: $el.data('allowclear'),
                    formatSelection: self.formatFunctions.getFormatClojure('selection'),
                    formatResult: self.formatFunctions.getFormatClojure('result')
                });
            });
        },

        setValue: function(id) {
            this.rootElement.val(id);
            if (this.rootElement.select2) {
                this.rootElement.select2('val', this.rootElement.val());
                this.rootElement.trigger("change");
            }
        },

        getValue: function () {
            return this.rootElement.select2('val');
        },

        setEnabled: function (value) {
            this.rootElement.prop('disabled', !value);
            this.rootElement.select2('enable', !!value);
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
        },

        setFormatSelection: function (callback) {
            this.formatFunctions.setCallback('selection', callback);
            $(this.rootElement).trigger('change', true);
        },

        setFormatResult: function (callback) {
            this.formatFunctions.setCallback('result', callback);
            $(this.rootElement).trigger('change', true);
        }
    });
});