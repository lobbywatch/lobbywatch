define(function(require, exports, module) {

    var Class               = require('class'),
        fb                  = require('pgui.advanced_filter'),
        localizer           = require('pgui.localizer').localizer,
        events              = require('microevent'),
        overlay             = require('pgui.overlay'),
        async               = require('async'),
        _                   = require('underscore'),
        InputEvents         = require('pgui.events').InputEvents,
        setupInputEvents    = require('pgui.events').setupInputEvents;


    var FilterRow = Class.extend({
        /**
         *
         * @param {jQuery} $row tr.search-line list/grid.tpl
         */
        init: function($row) {
            this.$row = $row;
            this.lastModification = 0;
            this._bindHandlers();
            this._updateEditorsEnability();
            this._storeInitialValues();
            this.timerInterval = this._calculateTimerInterval();
        },

        _calculateTimerInterval: function () {
            var result = 1000;
            var valueFromAttribute = +this.$row.attr('timer-interval');
            if (!(_.isUndefined(valueFromAttribute))) {
                result = valueFromAttribute;
            }
            return result;
        },

        _storeInitialValues: function() {
            this.$row.find('.column-filter').each(function() {
                var filterControlInput = $(this).find('.filter-control input');
                filterControlInput.attr('data-initial-value', filterControlInput.val());
            });
        },

        _updateEditorsEnability: function() {
            this.$row.find('.column-filter').each(function() {
                var columnFilter = $(this);
                var filterControl = $(this).find('.filter-control');
                var filterControlInput = $(this).find('.filter-control input');

                if (filterControl.attr('data-operator') == 'IS NULL' ||
                    filterControl.attr('data-operator') == 'IS NOT NULL' ||
                    filterControl.attr('data-operator') == '')
                {
                    filterControlInput.addClass('disabled');
                    filterControlInput.attr('disabled', '');
                }
                else {
                    filterControlInput.removeClass('disabled');
                    filterControlInput.removeAttr('disabled');
                }
            });
        },

        _bindHandlers: function() {
            var self = this;

            this.$row.find('a.reset-filter-row').click(function(e) {
                e.preventDefault();
                self._postFilterReset();
            });

            this.$row.find('.column-filter').each(function() {
                var columnFilter = $(this);
                var filterControl = columnFilter.find('.filter-control');
                if (filterControl.length == 0)
                    return;
                var filterControlInput = filterControl.find('input');

                function inputChangeHandler() {
                    if (self.timerInterval === 0) {
                        return;
                    }
                    self.lastModification++;
                    if (!filterControlInput.hasClass('editing'))
                        (function(modification) {
                            _.delay(
                                function() {
                                    if (
                                        (self.lastModification == modification) &&
                                            (filterControlInput.val() != filterControlInput.attr('data-initial-value'))
                                        ) {
                                        self._postFilter();
                                    }
                                }, self.timerInterval)
                        })(self.lastModification);
                }

                setupInputEvents(filterControlInput);

                filterControlInput.data('pg-events').onChange(inputChangeHandler);

                filterControlInput
                    .keydown(function(e) {
                        if (e.keyCode == 13) {
                            if (!filterControlInput.hasClass('editing'))
                                self._postFilter();
                        }
                    }).
                    keyup(inputChangeHandler);

                $(this).find('.operator-menu').each(function() {
                    $(this).find('li a').click(function() {
                        filterControl.attr('data-operator', $(this).attr('data-operator'));
                        columnFilter.find('.operator-dropdown i').removeClass();
                        columnFilter.find('.operator-dropdown i').addClass($(this).find('i').attr('class'));
                        self._updateEditorsEnability();
                        if (filterControl.attr('data-operator') == 'IS NULL' || filterControl.attr('data-operator') == 'IS NOT NULL' || filterControl.attr('data-operator') == '')
                            self._postFilter();
                    })
                });
            });
        },

        _postFilterReset: function() {
            overlay.showOverlay('', localizer.getString('Loading') + '...', true);
            var $form = $('<form>')
                .addClass('hide')
                .attr('action', window.location.href)
                .attr('method', 'POST')
                .append(
                    $('<input>')
                        .attr('name', 'AdvancedSearch')
                        .val('1')
                )
                .append(
                    $('<input>')
                        .attr('name', 'ResetFilter')
                        .val('1')
                )
                .append(
                    $('<input>')
                        .attr('name', 'operation')
                        .val('asearch')
                );

            $form.appendTo($('body'));
            $form.submit();
        },

        _postFilter: function() {
            overlay.showOverlay('', localizer.getString('Loading') + '...', true);
            var $form = $('<form>')
                .addClass('hide')
                .attr('action', window.location.href)
                .attr('method', 'POST')
                .append(
                    $('<input>')
                        .attr('name', 'AdvancedSearch')
                        .val('1')
                )
                .append(
                    $('<input>')
                        .attr('name', 'ResetFilter')
                        .val('0')
                )
                .append(
                    $('<input>')
                        .attr('name', 'SearchType')
                        .val('and')
                )
                .append(
                    $('<input>')
                        .attr('name', 'operation')
                        .val('asearch')
                );


            this.$row.find('.column-filter').each(function() {
                if ($(this).find('.filter-control').length > 0) {
                    var input = $(this).find('input');
                    var value =
                        (input.attr('data-post-value') || (input.attr('data-post-value') === '')) ?
                            input.attr('data-post-value') :
                            $(this).find('input').val();
                    var rawValue = $(this).find('input').val();
                    var fieldName = $(this).find('.filter-control').attr('data-field-name');
                    var operator = $(this).find('.filter-control').attr('data-operator');
                    $form
                        .append($('<input>')
                            .attr('name', 'filtertype_' + fieldName)
                            .val(operator)
                        )
                        .append($('<input>')
                            .attr('name', fieldName + '_value')
                            .val(value)
                        )
                        .append($('<input>')
                            .attr('name', fieldName + '_value_raw')
                            .val(rawValue)
                        );
                }
            });
            $form.appendTo($('body'));
            $form.submit();
        }
    });

    var DetailPanel = Class.extend({
        init: function(container, detailsInfo) {
            this.container = container;
            this.detailsInfo = detailsInfo;
            this.container.data('DetailPanel-class', this);
            this.$loadingPanel = $('<div class="detail-loading">')
                .hide()
                .appendTo(this.container)
        },

        showLoadingPanel: function(callback) {
            this.$loadingPanel.show();
            callback();
        },

        hideLoadingPanel: function(callback) {
            callback = callback || function() { };
            this.$loadingPanel.hide();
            callback();
        },

        showDetailsPanel: function(callback) {
            callback = callback || function() { };
            this.$list.show();
            this.$content.show();
            callback();
        },

        hideDetails: function() {
            this.container.hide();
        },

        showDetails: function() {
            this.container.show();
        },

        loadDetails: function(callback) {
            var self = this;
            async.forEach(this.$content.find('.tab-pane').get(),
                function(detailContent, callback) {
                    $.get($(detailContent).attr('url'),
                        function(data) {
                            $(detailContent).append(data);
                            callback();
                        },
                        'html'
                    ).error(function(jqXHR, textStatus) { callback(textStatus); });
                },
                callback
            );
        },

        constructPanel: function(callback) {
            var container = this.container;

            var self = this;
            this.$list = $('<ul>')
                .hide()
                .addClass('nav nav-tabs')
                .appendTo(this.container);
            this.$content = $('<div>')
                .hide()
                .addClass('tab-content')
                .appendTo(this.container);

            async.forEach(this.detailsInfo, function(detailInfo, callback) {
                    async.series([
                        function(callback) {
                            //require(['bootstrap/bootstrap-tab'], callback);
                            callback();
                        },
                        function(callback) {
                            var $item = $('<li>')
                                .appendTo(self.$list);
                            var $link = $('<a>')
                                .attr('href', '#' + detailInfo.detailId)
                                .html(detailInfo.tabCaption)
                                .appendTo($item);

                            var $detailContent = $('<div class="tab-pane">')
                                .attr('id', detailInfo.detailId)
                                .attr('url', detailInfo.gridLink)
                                .appendTo(self.$content);

                            $link.tab();

                            $link.click(function(e) {
                                e.preventDefault();
                                $(this).tab('show');
                            });
                            callback();
                        }
                    ], callback);
                },
                function() {
                    self.$list.find('a').first().tab('show');
                    callback();
                });
        }
    });

    var smParseJSON = function(jsonText) {

        if (!JSON) {
            return eval('(' + jsonText + ')');
        }
        else {
            return JSON.parse(jsonText);
        }

    };

    var Grid = exports.Grid = Class.extend({

        /**
         * @param {jQuery} container $(table#<table_name>Grid) See grid.tpl, Grid::GetId
         * @param options
         */
        init: function(container, options) {
            var self = this;
            this.container = container;
            this.container.data('grid-class', this);

            var inlineEditJson = smParseJSON(this.container.attr('data-inline-edit'));
            this.options = _.defaults(options ? options : {}, {
                inlineEdit: inlineEditJson.enabled,
                inlineEditRequestsAddress: inlineEditJson.request
            });
            

            this.$filterBuilderRow = this.container.find('.filter-builder-row');
            this.$createFilterButton = this.$filterBuilderRow.find('a.create-filter');
            this.$editFilterButton = this.$filterBuilderRow.find('a.edit-filter');
            this.$resetFilterButton = this.$filterBuilderRow.find('a.reset-filter');
            this.$quickFilterInput = this.container.find('thead > tr > td.header-panel .quick-filter-text');
            this.$quickFilterGoButton = this.container.find('thead > tr > td.header-panel .quick-filter-go');
            this.$quickFilterResetButton = this.container.find('thead > tr > td.header-panel .quick-filter-reset');
            this.$headerRow = this.container.find('thead > tr.header');
            this.$deleteSelectedButton = this.container.find('.delete-selected');

            this.currentFilter = new fb.Filter();
            this.configureFilterBuilderCallback = function() { };

            this._bindHandlers();
            this._enableRowHighlighting(this.container.hasClass('row-hover-highlight'));
            this.hiddenValues = {};
            this._processHiddenValues();

            this.integrateRows(this.container.find('.pg-row'));
            this._processInlineInsert();
            this._recalculateRowNumbers();
            this._updateMessagesRow();
            this.container.find('tr.messages').find('button.close').click(function() {
                async.nextTick(function() {
                    self._updateMessagesRow();
                });
            });
            this._fixColumnWidths();
            this.filterRow = new FilterRow(this.container.find('.search-line'));
            this._highLightQuickFilterValue();
        },

        _highLightQuickFilterValue: function() {
            var self = this;
            var quickFilterValue = this.container.attr('data-grid-quick-filter-value');
            if (quickFilterValue && quickFilterValue != '') {
                require(['pgui.text_highlight'], function(textHighlight) {
                    textHighlight.HighlightTextInAllGrid(
                        self.container.find('> tbody > tr.pg-row > td'), 
                        quickFilterValue, 
                        'ALL'
                        );

                });
            }
        },

        removeRow: function($row) {
            var nextRow = $row.nextAll('tr').first();
            if (nextRow.is('.detail')) {
                nextRow.remove();
            }
            $row.remove();
            this.updateEmptyGridMessage();
        },

        _processHiddenValues: function() {
            var self = this;
            var hiddenValuesJson = JSON.parse(this.container.attr('data-grid-hidden-values'));
            _.each(hiddenValuesJson, function(value, name) {
                self.addHiddenValue(name, value);
            });
        },

        _fixColumnWidths: function() {
            /*this.container.find('thead > tr.header > th').each(function() {
                $(this).css('width', $(this).innerWidth());
            });*/
        },

        updateEmptyGridMessage: function() {
            var rows = this.container.find('.pg-row');
            if (rows.length == 0) {
                this.container.find('tr.empty-grid').removeClass('hide');
            }
            else {
                this.container.find('tr.empty-grid').addClass('hide');
            }
        },

        _updateMessagesRow: function() {
            if (this.container.find('tr.messages .alert').length > 0) {
                this.container.find('tr.messages').removeClass('hide');
            }
            else {
                this.container.find('tr.messages').addClass('hide');
            }
        },

        _padNumber: function (number, length) {
            var str = '' + number;
            while (str.length < length) {
                str = '0' + str;
            }
            return str;
        },

        _recalculateRowNumbers: function() {
            var self = this;
            var startNumber = parseInt(this.container.attr('start-line-number'));
            var padCount = 0;
            var lineNumberCells = this.container.find('tbody > tr:not(.new-record-row) > td.line-number');

            var maxNumber = startNumber + lineNumberCells.length;
            padCount = maxNumber.toString().length;

            lineNumberCells.each(function(index){
                $(this).html(self._padNumber(index + startNumber, padCount) );
            });
        },

        insertRowAtBegin: function($row) {
            var row = this.container.find('tbody > .pg-row').first();
            var emptyGrid = this.container.find('tbody .empty-grid').closest('tr');
            if (row.length == 0) {
                row = emptyGrid;
            }
            row.before($row);
            emptyGrid.remove();
            this.integrateRows($row);
        },

        _processInlineInsert: function() {
            var self = this;
            var modalInsertLinks = this.container.find('[modal-insert=true]');

            async.forEach(modalInsertLinks.get(), function(item, callback) {

                async.waterfall([
                    function(callback) {
                        require(['pgui.modal_insert'], function(m) { callback(null, m); });
                    },
                    function(modalInsert, callback) {

                        var modalInsertLink = new modalInsert.ModalInsertLink($(item), self);
                        $(item).data('modal-insert', modalInsertLink);
                        callback();
                    }
                ], callback);
            });
        },

        /**
         * @param {jQuery} $rows tr.pg-row
         */
        integrateRows: function($rows) {
            var self = this;
            var modalEditLinks = $rows.find('a[modal-edit=true]');

            // See Renderer::RenderImageViewColumn
            require(['jquery/jquery.lightbox'], function() {
                self.container.find('[rel=zoom]').lightbox();
            });

            async.forEach(modalEditLinks.get(), function(item, callback) {
                async.waterfall([
                    function(callback) {
                        require(['pgui.modal_edit'], function(modalEdit) { callback(null, modalEdit); });
                    },
                    function(modalEdit, callback) {
                        var modalEditLink = new modalEdit.ModalEditLink($(item), self);
                        $(item).data('modal-edit', modalEditLink);
                        callback();
                    }
                ], callback);
            });

            var modalDeleteLinks = $rows.find('a[data-modal-delete=true]');
            if (modalDeleteLinks.length > 0)
                require(['pgui.modal_editing'], function(m) {
                    m.setupModalEditors($rows, self);
                });


            var modalCopyLinks = $rows.find('a[modal-copy=true]');
            async.forEach(modalCopyLinks.get(), function(item, callback) {
                async.waterfall([
                    function(callback) {
                        require(['pgui.modal_copy'], function(m) { callback(null, m); });
                    },
                    function(modalCopy, callback) {
                        var modalCopyLink = new modalCopy.ModalCopyLink($(item), self);
                        $(item).data('modal-copy', modalCopyLink);
                        callback();
                    }
                ], callback);
            });

            var modalViewLinks = $rows.find('a[modal-view=true]');
            async.forEach(modalViewLinks.get(), function(item, callback) {
                async.waterfall([
                    function(callback) {
                        require(['pgui.modal_view'], function(m) { callback(null, m); });
                    },
                    function(modalView, callback) {
                        var modalViewLink = new modalView.ModalViewLink($(item));
                        $(item).data('modal-view', modalViewLink);
                        callback();
                    }
                ], callback);
            });

            $rows.find('td.details > div > a.expand-details').click(function(e) {
                e.preventDefault();
                self._toggleDetailClickHandler($(this));
            });

            $rows.find('td.details').click(function(e) {
                var detailCell = $(this);
                //require([], function() {
                    var dropdown = detailCell.find('.dropdown-menu');
                    var link = detailCell.find('.details-quick-access');
                    link.dropdown();
                //});
            });

            this.container.find('a.expand-all-details').click(function(e) {
                e.preventDefault();
                self._toggleAllDetails($(this));
            });

            if (this.options.inlineEdit) {
                require(['pgui.inline_grid_edit'], function() {
                    self.container.sm_inline_grid_edit({
                        cancelButtonHint: localizer.getString('Cancel'),
                        commitButtonHint: localizer.getString('Commit'),
                        requestAddress: self.options.inlineEditRequestsAddress,
                        useBlockGUI: true,
                        useImagesForActions: true,
                        editingErrorMessageHeader: localizer.getString('ErrorsDuringUpdateProcess')
                    });
                });
            }
        },

        _toggleAllDetails: function(button) {
            var self = this;
            if (button.hasClass('collapsed'))
                this.container.find('> tbody > tr.pg-row').each(function() {
                    self._expandDetail($(this));
                });
            else
                this.container.find('> tbody > tr.pg-row').each(function() {
                    self._collapseDetails($(this));
                });
        },

        expandDetails: function($row) {
            this._expandDetail($row);
        },

        addHiddenValue: function(name, value) {
            this.hiddenValues[name] = value;
        },

        _enableRowHighlighting: function(enabled) {
            if (enabled) {
                this.container.find('tr.even,tr.odd')
                    .mouseover(function()
                    {
                        $(this).addClass("highlited");
                    })
                    .mouseout(function()
                    {
                        $(this).removeClass("highlited");
                    });
            }
        },

        /**
         * See DeleteSelectedGridState::ProcessMessages
         */
        deleteSelectRows: function() {
            var rowsToDelete = this.container
                .find('.pg-row')
                .filter(function() {
                    return $(this).find('td.row-selection input[type=checkbox]').prop('checked') ? true : false;
                });
            var $form = $('<form>')
                .addClass('hide')
                .attr('method', 'post')
                .attr('action', this.getDeleteSelectedAction())
                .append($('<input name="operation" value="delsel">'))
                .append(
                    $('<input name="recordCount">')
                        .attr('value', this.container.find('.pg-row').length))
                .appendTo($('body'));

            rowsToDelete.each(function() {
                $(this).find('td.row-selection input').clone().appendTo($form);
            });
            $form.submit();
        },

        getDeleteSelectedAction: function() {
            return this.container.attr('data-delete-selected-action');
        },

        _bindHandlers: function() {
            var self = this;


            self.$headerRow.find('>th').each(function() {
                if ($(this).attr('data-comment')) {
                    $(this).popover({
                        placement: 'top',
                        title: $(this).attr('data-field-caption'),
                        content: $(this).attr('data-comment')
                    });
                }
            });


            this.$deleteSelectedButton.click(function() {

                require(['bootbox.min'], function() {

                    bootbox.animate(false);
                    bootbox.confirm(localizer.getString('DeleteSelectedRecordsQuestion'), function(confirmed) {
                        if (confirmed) {
                            self.deleteSelectRows();
                        }
                    });

                });
            });

            this.$headerRow.find('th.row-selection input[type=checkbox]').change(function() {
                var checked = $(this).prop('checked');
                self.container.find('.pg-row td.row-selection input[type=checkbox]').prop('checked', checked);
            });

            this.$headerRow.find('>th.sortable').click(function(e) {
                e.preventDefault();
                window.location.href = $(this).attr('data-sort-url');
            });
            this.$quickFilterResetButton.click(function(e) {
                e.preventDefault();
                self.$quickFilterInput.val('');
                self._resetQuickFilter();
            });

            this.$quickFilterInput.keyup(function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    self._quickFilterGoButtonClickHandler();
                }
            });

            this.$quickFilterGoButton.click(function(e) {
                e.preventDefault();
                self._quickFilterGoButtonClickHandler();
            });
            this.$resetFilterButton.click(function(e) {
                e.preventDefault();
                self._resetFilterClickHandler(e);
            });
            this.$createFilterButton.click(function(e) {
                self._createFilterClickHandler(e);
            });
            this.$editFilterButton.click(function(e) {
                self._createFilterClickHandler(e);
            });
        },

        _getColumnCount: function() {
            var result = 0;
            this.$headerRow.children('th').each(function(){
                result += $(this).attr('colspan') ? parseInt($(this).attr('colspan')) : 1;
            });
            return result;
        },

        _expandDetail: function($row) {
            var button = $row.find('a.expand-details');

            if (!button.hasClass('collapsed')) {
                return;
            }

            button.removeClass('collapsed');
            button.addClass('expanded');
            this._updateToggleAllDetailsButton();
            if (button.data('DetailsPanel-class')) {
                button.data('DetailsPanel-class').showDetails();
                return;
            }


            var $detailsRow = $('<tr>')
                .addClass('detail');
            var $detailsCell = $('<td>')
                .appendTo($detailsRow);
            $row.after($detailsRow);
            $detailsCell.attr('colspan', this._getColumnCount());
            var detailsInfo = eval(button.attr('data-info'));
            var detailsPanel = new DetailPanel($detailsCell, detailsInfo);
            button.data('DetailsPanel-class', detailsPanel);

            async.series([
                function(callback) {
                    detailsPanel.showLoadingPanel(callback);
                },
                function(callback) {
                    detailsPanel.constructPanel(callback);
                },
                function(callback) {
                    detailsPanel.loadDetails(callback);
                },
                function(callback) {
                    detailsPanel.showDetailsPanel(callback);
                }
            ],
                function(err) {
                    detailsPanel.hideLoadingPanel();
                });
        },

        _collapseDetails: function($row) {
            var button = $row.find('a.expand-details');

            if (button.hasClass('collapsed')) {
                return;
            }

            if (button.data('DetailsPanel-class')) {
                button.addClass('collapsed');
                button.removeClass('expanded');
                button.data('DetailsPanel-class').hideDetails();
                this._updateToggleAllDetailsButton();
            }
        },

        _updateToggleAllDetailsButton: function() {
            var expandAllButton = this.container.find('> thead > tr > th a.expand-all-details');

            if (this.container.find('> tbody > tr.pg-row td a.expand-details.collapsed').length == 0) {
                expandAllButton.removeClass('collapsed');
                expandAllButton.addClass('expanded');
            }
            if (this.container.find('> tbody > tr.pg-row td a.expand-details.expanded').length == 0) {
                expandAllButton.removeClass('expanded');
                expandAllButton.addClass('collapsed');
            }

        },

        _toggleDetailClickHandler: function(button) {
            if (button.hasClass('collapsed')) {
                this._expandDetail(button.closest('tr'));
            }
            else {
                this._collapseDetails(button.closest('tr'));
            }
        },

        _quickFilterGoButtonClickHandler: function() {
            var text = this.$quickFilterInput.val();
            this._postQuickFilter(text);
        },

        _resetQuickFilter: function() {
            var $form = $('<form>')
                .attr('method', 'GET')
                .appendTo($('body'));
            $form.append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'quick-filter-reset')
            );
            for(var valueName in this.hiddenValues) {
                if (this.hiddenValues.hasOwnProperty(valueName)) {
                    $form.append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', valueName)
                            .val(this.hiddenValues[valueName])
                    );
                }
            }
            $form.submit();
        },

        _postQuickFilter: function(filterText) {
            var $form = $('<form>')
                .attr('method', 'GET');
            $form.append(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'quick-filter')
                        .val(filterText)
                    )
                .appendTo($('body'));
            for(var valueName in this.hiddenValues) {
                if (this.hiddenValues.hasOwnProperty(valueName)) {
                    $form.append(
                            $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', valueName)
                            .val(this.hiddenValues[valueName])
                        );
                }
            }
            $form.submit();
        },

        _postFilter: function(filter) {
            overlay.showOverlay('', localizer.getString('Loading') + '...');
            var $form = $('<form>')
                .attr('method', 'POST')
                .attr('action', window.location.href)
                .appendTo($('body'));
            $form.append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'filter_json')
                    .val(filter.asJson())
            );
            $form.submit();
        },

        _createFilterClickHandler: function(event) {
            event.preventDefault();
            var self = this;
            self._showFilterBuilder(function(err, newFilter) {
                if (newFilter) {
                    self.setFilter(newFilter);
                    self._postFilter(newFilter);
                }
            });
        },

        _resetFilterClickHandler: function(event) {
            var self = this;
            var emptyFilter = new fb.Filter();
            self.setFilter(emptyFilter);
            self._postFilter(emptyFilter);
        },

        setFilter: function(filter) {
            this.currentFilter = filter;
            if (!filter.isEmpty()) {
                this.$createFilterButton.hide();
                this.$editFilterButton.find('.filter-text').html(filter.asString());
                this.$editFilterButton.show();
            }
            else {
                this.$createFilterButton.show();
                this.$editFilterButton.children('.filter-text').html('');
                this.$editFilterButton.hide();
            }
        },

        onConfigureFilterBuilder: function(callback) {
            this.configureFilterBuilderCallback = callback;
        },

        _showFilterBuilder: function(callback) {
            var $filterBuilderWrapper = $('<div>')
                .appendTo($('body'));
            var $filterBuilderContainer = $('<span>')
                .appendTo($filterBuilderWrapper);

            var filterBuilder = new fb.FilterBuilder($filterBuilderContainer, this.currentFilter);
            this.configureFilterBuilderCallback(filterBuilder);
            filterBuilder.activate();

            var isOkPressed = false;
            require(['bootbox.min'], function() {

                overlay.showExposure();
                bootbox.animate(false);
                bootbox.dialog($filterBuilderWrapper, [
                    {
                        label: localizer.getString('Cancel'),
                        "callback": function() {

                            $filterBuilderWrapper.remove();
                            callback('Dialog canceled');
                        }
                    },
                    {
                        "OK": function() {
                            isOkPressed = true;
                            $filterBuilderWrapper.remove();
                            callback(null, filterBuilder.getFilter());
                        }
                    }], {
                        backdrop: false,
                        header: localizer.getString('FilterBuilder')
                    }
                ).on('hidden', function () {
                    if (!isOkPressed)
                        overlay.hideExposure();
                });
            });
        }

    });

});