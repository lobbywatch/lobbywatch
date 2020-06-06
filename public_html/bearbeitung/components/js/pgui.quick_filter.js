define(['class', 'pgui.text_highlight', 'pgui.editors/multivalue_select'], function (Class, highlight, multivalue_select) {

    return Class.extend({

        init: function ($container) {
            this.container = $container;
            this._initializeEditors();
            this._initializeEvents();
        },

        _initializeEditors: function() {
            this.$input = this.container.find('.js-input');
            this.$filterConditionEditor = this.container.find('#quick-filter-operator');

            var $columnsEditor = this.container.find('#quick-filter-fields');
            this.$selectedColumnsEditor = new multivalue_select($columnsEditor);
            $columnsEditor.off('change');
        },

        _initializeEvents: function() {
            var self = this;

            this.container
                .on('click', '.js-submit', function () {
                    self._submit();
                })
                .on('click', '.js-reset', function () {
                    self._reset()._submit();
                })
                .on('keyup', '.js-input', function (e) {
                    if (e.keyCode === 13) {
                        self._submit();
                    }
                })
                .on('click', '.js-cancel', function (e) {
                    var $dropdownToggleButton = self.container.find('.dropdown-toggle');
                    if ($dropdownToggleButton.length) {
                        $dropdownToggleButton.trigger('click');
                    }
                });
        },

        setColumnNames: function (columnNames) {
            this.columnNames = columnNames;
            return this;
        },

        highlight: function ($container) {
            highlight.HighlightTextInAllGrid(
                $container.find($.map(this._getColumnsToHighlight(), function (name) {
                    return 'td[data-column-name="' + name + '"]';
                }).join(',')),
                this._getValue(),
                'ALL'
            );
            return this;
        },

        _getColumnsToHighlight: function () {
            var result = [];
            var selectedColumnNames = this.$selectedColumnsEditor.getValue();
            if (_.isArray(selectedColumnNames)) {
                for (var i = 0; i < this.columnNames.length; i++) {
                    if (selectedColumnNames.indexOf(this.columnNames[i]) > -1) {
                        result.push(this.columnNames[i]);
                    }
                }
            }
            return result.length > 0 ? result : this.columnNames;
        },

        _getValue: function () {
            return this.$input.val();
        },

        _getFilterCondition: function () {
            return this.$filterConditionEditor.val();
        },

        _getSelectedColumns: function () {
            return this.$selectedColumnsEditor.getValue();
        },

        _reset: function () {
            this.$input.val('');
            return this;
        },

        _submit: function () {
            var query = jQuery.query;
            query = query.set('quick_filter', this._getValue());
            query = query.set('quick_filter_operator', this._getFilterCondition());
            query = query.set('quick_filter_fields', this._getSelectedColumns());
            window.location = query;
        }

    });

});
