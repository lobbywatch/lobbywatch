define([
    'underscore',
    'pgui.editors/custom',
    'pgui.editors/select2_format',
    'locales/select2_locale',
    'libs/select2'
], function (_, CustomEditor, FormatFunctions) {

    return CustomEditor.extend({

        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            this.formatFunctions = new FormatFunctions();
            this._processElement(rootElement);
        },

        _processElement: function (rootElement) {
            var $rootElement = $(rootElement);
            var self = this;

            var $inputs = $rootElement.find("input");
            $inputs.each(function (index, el) {
                var $el = $(el);
                var parentAttr = $el.data('parent-autocomplete');
                var $parentAutoComplete = $rootElement.find('[data-id=' + parentAttr +']');

                var refreshWaitingForParent = function () {
                    var isReadonly = !$parentAutoComplete.val() || $parentAutoComplete.prop('readonly');
                    $el.prop('readonly', isReadonly);
                    $el.select2('readonly', isReadonly);
                };

                var clearInput = function () {
                    $el.trigger("change", !$el.val());
                    $el.select2("val", null);
                };

                if (parentAttr && $parentAutoComplete) {
                    $parentAutoComplete.on('change select2-data-change', function () {
                        refreshWaitingForParent();
                        clearInput();
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
                                params.term2 = $parentAutoComplete.val();
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
                    width: 'resolve',
                    allowClear: $el.data('allowclear'),
                    minimumInputLength: $el.data('minimal-input-length'),
                    formatSelection: self.formatFunctions.getFormatClojure('selection', index),
                    formatResult: self.formatFunctions.getFormatClojure('result', index)
                });
            });

            $inputs.eq($inputs.length - 1).on("change", function (e, ignoreInternal) {
                self.setState('normal');

                if (!ignoreInternal) {
                    self.doChanged();
                }
            });
        },

        _getMainControl: function() {
            return this.rootElement.find("[data-multileveledit-main]");
        },

        getValue: function() {
            return this._getMainControl().val();
        },

        getData: function () {
            return this.rootElement.find('input[type=hidden]').map(function (i, el) {
                return $(el).select2('data');
            });
        },

        setData: function (data) {
            this.rootElement.find('input[type=hidden]').each(function (i, el) {
                if (data[i]) {
                    $(el).select2('data', data[i]).trigger('select2-data-change');
                }
            }.bind(this));

            return this;
        },

        getEnabled: function() {
            return !this.rootElement.find('input[name]').first().prop('disabled');
        },

        setEnabled: function(value) {
            var $inputs = this.rootElement.find('input[name]');
            $inputs.prop('disabled', !value);
            $inputs.select2('enable', !!value);
            return this;
        },

        getReadonly: function() {
            return !!this.rootElement.find('input[name]').first().prop('readonly');
        },

        setReadonly: function(value) {
            var $inputs = this.rootElement.find('input[name]');
            $inputs.prop('readonly', !!value);
            $inputs.select2('readonly', !!value);
            return this;
        },

        setFormatSelection: function (level, callback) {
            this.formatFunctions.setCallback('selection', callback, level);
            this._triggerChangeLevel(level);
            return this;
        },

        setFormatResult: function (level, callback) {
            this.formatFunctions.setCallback('result', callback, level);
            this._triggerChangeLevel(level);
            return this;
        },

        _getSetRequiredTarget: function () {
            return this.rootElement.find('input[data-id]').last();
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