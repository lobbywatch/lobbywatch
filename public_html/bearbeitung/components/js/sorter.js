define(['class'], function(Class) {
    return Class.extend({

        init: function(sortableColumns) {
            var self = this;
            this.sortingColumns = [];
            this.sortableColumns = [];

            $.each(sortableColumns, function(name, item) {
                var $col = $('.sortable[data-field-name="'+item.name+'"]');
                self.sortableColumns.push(item);

                $col.on('click', function(e) {
                    e.preventDefault();
                    var fieldName = $(this).data('field-name');
                    self._onClickSortableCellHandler(fieldName, e);
                });

                var sortIndex = $col.data('sort-index') || item.index;
                if (sortIndex == undefined) {
                    return;
                }

                self.sortingColumns[sortIndex] = ($col.data('sort-order') == 'desc' ? 'd' : 'a') + $col.data('field-name');
            });
        },

        _onClickSortableCellHandler: function(fieldName, event) {
            if (event.shiftKey) {
                this.addColumn(fieldName);
            } else if (event.ctrlKey || event.metaKey) {
                this.deleteColumn(fieldName);
            } else {
                this.sortByColumn(fieldName);
            }

            this.applySort();
        },

        getSortableColumns: function () {
            return this.sortableColumns;
        },

        applySort: function() {
            var self = this;
            require(['jquery.query'], function () {
                if (self.sortingColumns.length > 0) {
                    window.location = jQuery.query.set('order', self.sortingColumns);
                } else {
                    window.location = jQuery.query.remove('order').set('operation', 'resetorder');
                }
            });
        },

        _getColumnIndexByName: function(fieldName) {
            for (var i = 0; i < this.sortingColumns.length; i++) {
                if (this.sortingColumns[i] && this.sortingColumns[i].slice(1) == fieldName) {
                    return i;
                }
            }
        },

        addColumn: function(fieldName, order) {
            var index = this._getColumnIndexByName(fieldName);
            if (index == undefined) {
                this.sortingColumns.push((order || 'a') + fieldName);
                return;
            }

            if (this.sortingColumns[index].charAt(0) == 'a') {
                this.sortingColumns[index] = 'd' + fieldName;
            } else {
                this.sortingColumns[index] = 'a' + fieldName;
            }
        },

        deleteColumn: function(fieldName) {
            var index = this._getColumnIndexByName(fieldName);
            if (index !== undefined) {
                this.sortingColumns.splice(index, 1);
            }
        },

        sortByColumn: function(fieldName) {
            var index = this._getColumnIndexByName(fieldName);
            var orderType = 'd';

            if (index == undefined || this.sortingColumns[index].charAt(0) == 'd') {
                orderType = 'a';
            }

            this.sortingColumns = [orderType + fieldName];
        },

        clear: function () {
            this.sortingColumns = [];
        }
    });

});
