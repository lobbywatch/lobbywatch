define([
    'class',
    'pgui.filter_builder',
    'pgui.column_filter',
    'pgui.quick_filter',
    'pgui.localizer',
    'microevent',
    'pgui.overlay',
    'underscore',
    'multiple_sorting',
    'sorter',
    'pgui.shortcuts',
    'pgui.utils',
    'pgui.field-embedded-video',
    'pgui.autohide-message',
    'pgui.selection',
    'pgui.selection-handler',
    'pgui.cell-edit',
    'pgui.modal_operation_link',
    'pgui.modal_delete_link',
    'pgui.modal_view',
    'pgui.inline-edit',
    'pgui.column_group',
    'pgui.grid-details',
    'pgui.form_collection',
    'pgui.image_popup'
], function(
    Class,
    FilterBuilder,
    ColumnFilter,
    QuickFilter,
    localizer,
    events,
    overlay,
    _,
    MultipleSorting,
    Sorter,
    shortcuts,
    utils,
    showFieldEmbeddedVideo,
    autoHideMessage,
    Selection,
    SelectionHandler,
    initCellEdit,
    modalLink,
    initModalDelete,
    initModalView,
    inlineLink,
    initColumnGroup,
    initDetails,
    FormCollection,
    initImagePopup
) {

    function padNumber(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    };

    return Class.extend({
        init: function(container, options) {
            var self = this;
            this.container = container;
            this.container.data('grid-class', this);

            this.filters = {};
            this.options = options ? options : {};

            this.$header = this.container.find('thead').first();
            this.selectionActions = new SelectionHandler(
                new Selection(this.getSelectionId()),
                this.container.find('.js-selection-actions-container'),
                this.$header.find('.row-selection input[type=checkbox]'),
                this.container.find('.pg-row .row-selection input[type=checkbox]'),
                true
            );

            this._initActions();
            this.integrateRows(this.container.find('.pg-row'));
            this._recalculateRowNumbers();
            this._createFilters();
            this._bindHandlers();
            this._processHiddenValues();

            if (this.$header.length && this.$header.data('has-groups')) {
                initColumnGroup(this.$header);
            }

            if (!this.container.data('is-master')) {
                var sortableColumns = this.container.data('sortable-columns');
                var sorter = new Sorter(sortableColumns);

                var $sortDialog = $('#multiple-sorting-' + this.getId());
                new MultipleSorting(sorter, $sortDialog);
            }

            $.each(this.container.find('.alert'), function (i, alert) {
                autoHideMessage($(alert));
            });
        },

        getId: function () {
            return this.container.attr('id');
        },

        isCard: function () {
            return this.container.hasClass('grid-card');
        },

        getSelectionId: function () {
            return this.container.data('selection-id');
        },

        getRowTemplate: function () {
            return $(_.template($('#' + this.getId() + '_row_template').html())(this));
        },

        getColumnCount: function() {
            return this.container.data('column-count');
        },

        getQuickFilter: function () {
            return this.filters.quickFilter;
        },

        getFilterBuilder: function () {
            return this.filters.filterBuilder;
        },

        getColumnFilter: function () {
            return this.filters.columnFilter;
        },

        getRows: function () {
            return this.container.find('.pg-row-list:first > .pg-row');
        },

        removeRow: function($row) {
            if ($row.data('details')) {
                $row.data('details').remove();
            }

            var $checkbox = $row.find('.row-selection input[type=checkbox]').first();
            if ($checkbox.length && $checkbox.prop('checked')) {
                $checkbox.click();
            }

            var $editLink = $row.find('[data-inline-operation=edit]');
            if ($editLink.length && $editLink.data('form-container')) {
                $editLink.data('form-container').remove();
            }

            $row.remove();

            this.updateEmptyGridMessage();
        },

        updateEmptyGridMessage: function() {
            this.container.find('.pg-row-list:first .empty-grid').toggle(this.container.find('.pg-row') > 0);
        },

        insertRowAtBegin: function($row) {
            var row = this.container.find('.pg-row-list:first > .pg-row').first();
            var emptyGrid = this.container.find('.pg-row-list:first .empty-grid').closest('tr');
            if (row.length == 0) {
                row = emptyGrid;
            }
            row.before($row);
            emptyGrid.remove();
            this.integrateRows($row);

            return $row;
        },

        addHiddenValue: function(name, value) {
            this.hiddenValues[name] = value;
        },

        showMessage: function (message, displayTime, isError) {
            if (!message) {
                return;
            }

            var $messageContainer = this.container.find('.js-grid-message-container').first();
            $messageContainer.append(utils.buildDismissableMessage(
                isError ? 'danger' : 'success',
                message,
                displayTime
            ));
        },

        integrateRows: function($rows) {
            var self = this;

            this.selectionActions.setCheckboxes(
                this.$header.find('.row-selection input[type=checkbox]'),
                this.container.find('.pg-row .row-selection input[type=checkbox]')
            );

            showFieldEmbeddedVideo($rows);
            initImagePopup($rows);

            $rows.find('[data-edit-url]').each(function (i, el) {
                var $el = $(el);
                initCellEdit($(el), function (response) {
                    self.showMessage(response.message, response.messageDisplayTime, !response.success);
                    var $row = $(response.row);
                    $el.closest('.pg-row').replaceWith($row);
                    self.integrateRows($row);
                });
            });

            $rows.find('[data-modal-operation=edit]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('modal-edit')) {
                    $item.data('modal-edit', modalLink.createEditLink(FormCollection, $item, self));
                }
            });

            $rows.find('[data-modal-operation=copy]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('modal-copy')) {
                    $item.data('modal-copy', modalLink.createInsertLink(FormCollection, $item, self));
                }
            });

            $rows.find('[data-modal-operation=view]').each(function (index, item) {
                var $item = $(item);
                if ($item.data('modal-view')) {
                    return;
                }

                initModalView($item);
                $item.data('modal-view', true);
            });

            var modalDeleteLinks = $rows.find('[data-modal-operation=delete]');
            if (modalDeleteLinks.length > 0) {
                initModalDelete($rows, self);
            }

            $rows.find('[data-inline-operation=edit]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('inline-edit')) {
                    $item.data('inline-edit', inlineLink.createEditLink($item, self));
                }
            });

            $rows.find('[data-inline-operation=copy]').each(function (index, item) {
                var $item = $(item);
                if (!$item.data('inline-copy')) {
                    $item.data('inline-copy', inlineLink.createInsertLink($item, self));
                }
            });

            initDetails(this, this.container.find('.js-expand-all-details').first(), this.getRows());

            utils.updatePopupHints($rows);
        },

        _createFilters: function () {
            this.filters = {
                filterBuilder: FilterBuilder.create(
                    this.container.find('.js-filter-builder-modal')
                ),
                columnFilter: ColumnFilter.create(
                    this.container.find('.js-column-filter-container').first()
                ),
                quickFilter: new QuickFilter(
                    this.container.find('.js-quick-filter').first()
                )
            };

            this.container.find('.js-filter-builder-open').on('click', _.bind(function (e) {
                this.filters.filterBuilder.show();
                e.preventDefault();
            }, this));
        },

        _bindFilterStatusHandlers: function ($container) {
            var self = this;

            function getFilterByEvent(event) {
                return self.filters[$(event.currentTarget).data('id')];
            }

            $container
                .on('click', '.js-edit-filter', function (e) {
                    getFilterByEvent(e).show();
                    e.preventDefault();
                })
                .on('click', '.js-toggle-filter', function (e) {
                    getFilterByEvent(e)
                        .setEnabled($(e.currentTarget).data('value'))
                        .submit();
                    e.preventDefault();
                })
                .on('click', '.js-reset-filter', function (e) {
                    getFilterByEvent(e).reset().submit();
                    e.preventDefault();
                })
            ;
        },

        _processHiddenValues: function() {
            var self = this;
            this.hiddenValues = {};
            _.each(this.container.data('grid-hidden-values'), function(value, name) {
                self.addHiddenValue(name, value);
            });
        },

        _recalculateRowNumbers: function() {
            var self = this;
            var startNumber = parseInt(this.container.data('start-line-number'));
            var padCount = 0;
            var lineNumberCells = this.container.find('tbody > tr > td.line-number');

            var maxNumber = startNumber + lineNumberCells.length;
            padCount = maxNumber.toString().length;

            lineNumberCells.each(function(index){
                $(this).html(padNumber(index + startNumber, padCount) );
            });
        },

        _initActions: function() {
            var self = this;

            var $actions = this.container.find('.js-actions').first();

            $actions.find('[data-modal-insert=true]').each(function (i, el) {
                var $el = $(el);
                if (!$el.data('modal-insert-object')) {
                    $el.data('modal-insert-object', modalLink.createInsertLink(FormCollection, $el, self));
                }
            });

            $actions.find('[data-inline-insert=true]').each(function (i, el) {
                var $el = $(el);
                if (!$el.data('inline-insert-object')) {
                    $el.data('inline-insert-object', inlineLink.createInsertLink($el, self, $el.data('count') || 1));
                }
            });
        },

        _bindHandlers: function() {
            var self = this;

            self.$header.find('th[data-comment]').each(function() {
                $(this).popover({
                    placement: 'top',
                    container: 'body',
                    trigger: 'hover',
                    title: $(this).attr('data-field-caption'),
                    content: $(this).attr('data-comment')
                });
            });

            this._bindFilterStatusHandlers(this.container.find('.js-filter-status').first());
        }

    });
});