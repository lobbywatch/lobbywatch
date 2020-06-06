define([
    'underscore',
    'pgui.editors/custom',
    'libs/select2',
    'locales/select2_locale'
], function (_, CustomEditor) {

    return CustomEditor.extend({
        init: function (rootElement, readyCallback) {
            this.queryFunction = function (term) { return {term: term}; };
            this._super(rootElement, readyCallback);

            var $el = $(rootElement);
            var maxSelectionSize = $el.attr('data-max-selection-size');
            var self = this;

            $el.on("change", this.doChanged.bind(this));

            $el.select2({
                multiple: true,
                maximumSelectionSize: maxSelectionSize,
                ajax: {
                    url: $el.attr('data-url'),
                    dataType: 'json',
                    results: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {id: item.id, text: item.value, fields: item.fields};
                            })
                        };
                    },
                    data: function (term) {
                        return self.queryFunction(term);
                    }
                },
                initSelection: function (element, callback) {
                    if (element.val().length === 0) {
                        callback([]);
                        return;
                    }

                    var ids = element.val().split(',');
                    var results = [];

                    _.each(ids, function (id, i) {
                        $.ajax({
                            url: $el.data("url"),
                            data: {id: id},
                            dataType: "json"
                        }).success(function (data) {
                            $.each(data, function (k, item) {
                                if (item.id == id) {
                                    results.push({
                                        id: item.id,
                                        text: item.value,
                                        fields: item.fields
                                    });

                                    if (results.length === ids.length) {
                                        callback(results);
                                        self.rootElement.trigger('select2-init');
                                    }

                                    return false;
                                }

                            });
                        });
                    });
                },
                width: '100%'
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

        isMultivalue: function () {
            return true;
        },

        setQueryFunction: function (fn) {
            this.queryFunction = fn;
            return this;
        }
    });
});
