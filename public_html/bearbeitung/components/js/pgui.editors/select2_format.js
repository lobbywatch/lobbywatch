define(['class'], function (Class) {

    return Class.extend({

        init: function () {
            this._formatCallbacks = [];
        },

        setCallback: function (prop, callback, level) {
            if (typeof(callback) !== 'function') {
                console.error("select2: callback argument must be a valid function");
                return;
            }

            level = level || 0;
            if (!this._formatCallbacks[level]) {
                this._formatCallbacks[level] = {};
            }

            this._formatCallbacks[level][prop] = callback;
        },

        setFormatCallbacksFromElement: function ($el, level) {
            level = level || 0;

            var attrFormatResult = this._evalFormatFunction($el.data("format-result"));
            var attrFormatSelection = this._evalFormatFunction($el.data("format-selection"));

            if (attrFormatResult) {
                this.setCallback('result', attrFormatResult, level);
            }
            if (attrFormatSelection) {
                this.setCallback('selection', attrFormatSelection, level);
            }
        },

        getFormatClojure: function (prop, level) {
            var self = this;
            level = level || 0;
            return function (item) {
                if (self._hasFormatCallback(prop, level)) {
                    return self._formatCallbacks[level][prop](item);
                }
                return item.text;
            };
        },

        _hasFormatCallback: function (prop, level) {
            return !!this._formatCallbacks[level]
                && !!this._formatCallbacks[level][prop];
        },

        _evalFormatFunction: function (expr) {
            if (!expr) {
                return null;
            }

            var result = undefined;
            eval("var result = function (item) { " + expr + " };");
            return result;
        }
    });

});
