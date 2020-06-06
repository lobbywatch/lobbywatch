define([
    'underscore',
    'pgui.editors/custom',
    'libs/select2',
    'locales/select2_locale'
], function (_, CustomEditor) {

    return CustomEditor.extend({
        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;
            var $el = $(rootElement);
            var maxSelectionSize = $el.attr('data-max-selection-size');
            var placeholder = $el.attr('data-placeholder');

            $el.select2({
                width: '100%',
                maximumSelectionSize: maxSelectionSize,
                placeholder: placeholder
            });

            // use readonly emulation for <select>
            if ($el.attr('readonly')) {
                this.setReadonly(true);
            }

            $('label[for=' + $el.attr('id') + ']').on('click', function () {
                $el.select2('focus');
            });

            $el.on('change', function () {
                if (typeof $el.valid === 'function' ) {
                    var $form  = $(this.form);
                    if ($form.length && _.keys($form.data('validator').submitted).length) {
                        $el.valid();
                    }
                }

                self.doChanged();
            });
        },

    	getValue: function () {
    		return this.rootElement.select2("val");
    	},

        getDisplayValue: function () {
            return _.map(this.rootElement.select2('data'), function (item) {
                return item.text;
            });
        },

    	setValue: function (value) {
            if (value.split) {
                this.rootElement.select2("val", value.split(","));
            } else if (_.isArray(value)) {
                this.rootElement.select2("val", value);
            } else {
                console.error("'value' must be string or array, '" + typeof(value) + "' given");
            }

            return this;
    	},

    	getEnabled: function () {
    		return !this.rootElement.prop("disabled");
    	},

    	setEnabled: function (value) {
    		this.rootElement.prop('disabled', !value);
    		this.rootElement.select2('enable', !!value);
            return this;
    	},

    	getReadonly: function () {
    		return !!this.rootElement.data('readonly');
    	},

    	setReadonly: function (value) {
    		this.rootElement.select2('readonly', !!value);
    		this.rootElement.data('readonly', !!value);
            return this;
    	},

    	addItem: function (value, caption) {
    		this.rootElement.append($("<option value='"+value+"'>"+caption+"</option>"));
            return this;
    	},

    	removeItem: function (value) {
    		this.rootElement.find("option[value="+value+"]").remove();
            return this;
    	},

    	getItems: function () {
    		return this.rootElement.find("option").map(function (i, option) {
    			var $option = $(option);
    			return {value: $option.val(), caption: $option.text()};
    		});
    	},

    	getItemCount: function () {
    		return this.rootElement.find("option").length;
    	},

        isMultivalue: function () {
            return true;
        }
    });
});
