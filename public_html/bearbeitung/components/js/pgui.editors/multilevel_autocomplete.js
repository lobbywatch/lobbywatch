define(function (require, exports) {
    
    require('pgui.editors/select2_localizer');

    var _ = require('underscore');
    var CustomEditor = require('pgui.editors/custom').CustomEditor;
    var FormatFunctions = require('pgui.editors/select2_format').FormatFunctions; 
    var EditorsGlobalNotifier = require('pgui.editors/global_notifier').EditorsGlobalNotifier;

	exports.multiLevelAutoCompleteGlobalNotifier = new EditorsGlobalNotifier();
    exports.MultiLevelAutoComplete = CustomEditor.extend({

        init: function(rootElement)
        {
            this._super(rootElement);
            this.formatFunctions = new FormatFunctions();

            exports.multiLevelAutoCompleteGlobalNotifier.onValueChanged(_.bind(function(fieldName)
            {
                if (this.getFieldName() == fieldName)
                    this.doChanged();

            }, this));
            
            this._processElement(rootElement);
        },

        _processElement: function (rootElement) {
            var $rootElement = $(rootElement);
            var self = this;

            require(['libs/select2'], function () {
                var $inputs = $rootElement.find("input");
                $inputs.each(function (index, el) {
                    var $el = $(el);
                    var parentAttr = $el.attr('parent-autocomplete');
                    var parentAutoComplete = $('[name="' + parentAttr + '"]');

                    var refreshWaitingForParent = function () {
                        var isReadonly = !parentAutoComplete.val() || parentAutoComplete.prop('readonly');
                        $el.prop('readonly', isReadonly);
                        $el.select2('readonly', isReadonly);
                    };

                    var clearInput = function () {
                        $el.trigger("change", !$el.val());
                        $el.select2("val", null);
                    };

                    if (parentAttr && parentAutoComplete) {
                        parentAutoComplete.on('change', function () {
                            clearInput();
                            refreshWaitingForParent();
                        });

                        refreshWaitingForParent();
                    }

                    self.formatFunctions.setFormatCallbacksFromElement($el, index);

                    $el.select2({
                        ajax: {
                            url: $el.attr('data-url'),
                            data: function (term) {
                                var params = {term: term};
                                if (parentAttr) {
                                    params.term2 = parentAutoComplete.val();
                                }

                                return params;
                            },
                            dataType: 'json',
                            method: 'GET',
                            results: function (data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {id: item.id, text: item.value};
                                    })
                                };
                            }
                        },
                        initSelection: function (item, callback) {
                            callback({
                                id: item.val(),
                                text: item.data('init-text')
                            });
                        },
                        width: function () {
                            // 10 is a magic number.
                            return $el.width() + 10;
                        },
                        allowClear: $el.data('allowclear'),
                        minimumInputLength: $el.data('minimal-input-length'),
                        formatSelection: self.formatFunctions.getFormatClojure('selection', index),
                        formatResult: self.formatFunctions.getFormatClojure('result', index)
                    });
                });

                $inputs.eq($inputs.length - 1).on("change", function (e, ignoreInternal) {
                    if (!ignoreInternal) {
                        self.doChanged();
                    }
                });
            });
        },

        _getMainControl: function() {
            return this.rootElement.find("[data-multileveledit-main]");
        },

        getValue: function()
        {
            return this._getMainControl().val();
        },

        setValue: function(value)
        {

        },

        getEnabled: function() {
            return !this.rootElement.find('input[name]').first().prop('disabled');
        },

        setEnabled: function(value) {
            var $inputs = this.rootElement.find('input[name]');
            $inputs.prop('disabled', !value);
            $inputs.select2('enable', !!value);
        },

        getReadonly: function() {
            return !!this.rootElement.find('input[name]').first().prop('readonly');
        },

        setReadonly: function(value) {
            var $inputs = this.rootElement.find('input[name]');
            $inputs.prop('readonly', !!value);
            $inputs.select2('readonly', !!value);
        },

        setFormatSelection: function (level, callback) {
            this.formatFunctions.setCallback('selection', callback, level);
            this._triggerChangeLevel(level);
        },

        setFormatResult: function (level, callback) {
            this.formatFunctions.setCallback('result', callback, level);
            this._triggerChangeLevel(level);
        },

        _triggerChangeLevel: function (level) {
            var $el = $(this.rootElement).find('input[type=hidden]').eq(level);
            
            if (!$el) {
                console.error("invalid level " + level);
                return;
            }

            if ($el.select2) {
                $el.select2('data', $el.select2('data'));
            }
        }
    });

});