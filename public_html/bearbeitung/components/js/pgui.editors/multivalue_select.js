define(function (require, exports) {
	
    var _ = require('underscore');
	var CustomEditor = require('pgui.editors/custom').CustomEditor;
	var EditorsGlobalNotifier = require('pgui.editors/global_notifier').EditorsGlobalNotifier;
    require('pgui.editors/select2_localizer');

	exports.multiValueSelectGlobalNotifier = new EditorsGlobalNotifier();
    
    exports.MultiValueSelect = CustomEditor.extend({
        init: function (rootElement) {
            this._super(rootElement);
            var self = this;
            var $el = $(rootElement);
            var maxSelectionSize = $el.attr('data-max-selection-size');
            require(['libs/select2'], function () {
                $el.select2({
                    width: function () {
                        // 10 is a magic number
                        return $el.width() + 10;
                    },
                    maximumSelectionSize: maxSelectionSize
                });

                $el.on("change", function () {
                    self.doChanged();
                });
            });
        },

    	getValue: function () {
    		return this.rootElement.select2("val");
    	},

    	setValue: function (value) {
            if (value.split) {
                this.rootElement.select2("val", value.split(","));
            } else if (_.isArray(value)) {
                this.rootElement.select2("val", value);
            } else {
                console.error("'value' must be string or array, '" + typeof(value) + "' given");
            }
    	},

    	getEnabled: function () {
    		return !this.rootElement.prop("disabled");
    	},

    	setEnabled: function (value) {
    		this.rootElement.prop('disabled', !value);
    		this.rootElement.select2('enable', !!value);
    	},

    	getReadonly: function () {
    		return !!this.rootElement.data('readonly');
    	},

    	setReadonly: function (value) {
    		this.rootElement.select2('readonly', !!value);
    		this.rootElement.data('readonly', !!value);
    	},

    	addItem: function (value, caption) {
    		this.rootElement.append($("<option value='"+value+"'>"+caption+"</option>"));
    	},

    	removeItem: function (value) {
    		this.rootElement.find("option[value="+value+"]").remove();
    	},

    	getItems: function () {
    		return this.rootElement.find("option").map(function (i, option) {
    			var $option = $(option);
    			return {value: $option.val(), caption: $option.text()};
    		});
    	},

    	getItemCount: function () {
    		return this.rootElement.find("option").length;
    	}
    });
});