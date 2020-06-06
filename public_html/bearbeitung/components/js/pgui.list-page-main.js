define([
    'underscore',
    'pgui.page_settings',
    'pgui.shortcuts',
    'pgui.grid',
    'pgui.filter_common',
    'pgui.filter_builder',
    'pgui.column_filter',
    'pgui.charts',
    'jquery.stickytableheaders'
], function (_, pageSettings, shortcuts, Grid, Filter, FilterBuilder, ColumnFilter, charts) {

    function initGrids($grids) {
        $grids.each(function (i, el) {
            var $grid = $(el);
            var grid = new Grid($grid);
            var id = grid.getId();
            var gridData = window['gridData_' + id];

            // Filter builder
            var filterBuilder = grid.getFilterBuilder();
            _.each(gridData.filterBuilder.columns, function (columnData) {
                filterBuilder.addColumn(new Filter.Column(
                    columnData.fieldName,
                    columnData.caption,
                    columnData.operators
                ));
            });

            var filterBuilderGroup = new FilterBuilder.Group();
            filterBuilderGroup.deserialize(filterBuilder.getColumns(), gridData.filterBuilder.data);
            filterBuilder.setFilterComponent(filterBuilderGroup);

            // Column filter
            var columnFilter = grid.getColumnFilter();
            _.each(gridData.columnFilter.columns, function (columnData) {
                columnFilter.addColumn(new ColumnFilter.Column(
                    columnData.fieldName,
                    columnData.caption,
                    columnData.typeIsDateTime
                ));
            });

            var columnFilterGroup = new ColumnFilter.Group();
            columnFilterGroup.deserialize(columnFilter.getColumns(), gridData.columnFilter.data);
            columnFilter.setFilterComponent(columnFilterGroup);
            columnFilter.setDefaultsEnabled(gridData.columnFilter.isDefaultsEnabled);
            columnFilter.attach();

            // Quick filter
            var quickFilter = grid.getQuickFilter();
            quickFilter.setColumnNames(gridData.quickFilter.columns);
            quickFilter.highlight($grid);
        });
    }

    return function () {
        var $body = $('body');

        pageSettings($body);

        var $tableToFixHeader = $('table.table.fixed-header');
        if ($tableToFixHeader.length > 0) {
            var $navbar = $('.navbar');
            var marginTop = 0;

            if ($navbar.hasClass('navbar-fixed-top')) {
                marginTop += $navbar.outerHeight();
            }

            $tableToFixHeader.stickyTableHeaders({
                selector: 'thead:first',
                marginTop: marginTop
            });
        }

        shortcuts.push(['grid']);

        initGrids($body.find('.js-grid'));
        charts.init($body);
    }
});
