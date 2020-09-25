define(['moment'], function (moment) {
    var charts = [];

    function getTable(chart) {
        var table = new google.visualization.DataTable();
        $.each(chart.data.columns, function (i, column) {
            // annotations and tooltips
            if (column.role !== 'data') {
                table.addColumn({
                    role: column.role,
                    type: 'string',
                    p: {'html': true}
                });
                return;
            }

            table.addColumn({
                type: transformType(column.type),
                label: column.label
            });
        });

        $.each(chart.data.rows, function (i, rowData) {
            var row = [];

            $.each(chart.data.columns, function (j, col) {
                var value = rowData[j];

                if ((col.type === 'date' || col.type === 'datetime') && (value !== null)) {
                    value = moment(value).toDate();
                }

                row.push(value);
            });

            table.addRow(row);
        });

        applyFormatters(chart.data.columns, table);

        return table;
    }


    function applyFormatters(columns, table) {
        var formatterClasses = {
            'number': google.visualization.NumberFormat,
            'date': google.visualization.DateFormat,
            'datetime': google.visualization.DateFormat
        };

        $.each(columns, function (i, column) {
            var Formatter = formatterClasses[transformType(column.type)];
            if (!column.format || !Formatter) {
                return;
            }

            (new Formatter({
                pattern: column.format
            })).format(table, i);
        });
    }

    function transformType(type) {
        type = type || 'string';
        switch (type.toLowerCase()) {
            case 'string':
                return 'string';
            case 'date':
                return 'date';
            case 'datetime':
                return 'datetime';
            default:
                return 'number';
        }
    }

    function mergeStyles($container, chartType, options) {
        var textColor = $container.css('color');
        var gridlinesColor = $container.css('border-color');
        var result = $.extend(true,
            {
                backgroundColor: 'none',
                titleTextStyle: {
                    color: textColor
                },
                vAxis: {
                    gridlines: {color: gridlinesColor}
                },
                hAxis: {
                    gridlines: {color: gridlinesColor}
                }
            },
            options
        );

        if (chartType != 'Geo') {
            result = $.extend(true,
                {
                    legend: {
                        textStyle: {color: textColor}
                    }
                },
                result
            );
        }

        if (chartType == 'Pie') {
            return result;
        }

        var axisStyles = {
            titleTextStyle: {color: textColor},
            textStyle: {color: textColor}
        };

        return $.extend(true,
            {},
            {
                hAxis: axisStyles,
                vAxis: axisStyles
            },
            result
        );
    }

    function googleObjectAvailable() {
        return (typeof google !== 'undefined' && google);
    }

    function drawCharts() {
        if (!googleObjectAvailable()) {
            return;
        }

        $.each(charts, function (i, chartConfig) {
            chartConfig.table = chartConfig.table || getTable(chartConfig);

            var $container = $('[data-id=' + chartConfig.id + ']');
            chartConfig.options = mergeStyles($container, chartConfig.type, chartConfig.options);

            if (chartConfig.type == 'TreeMap' && ('generateTooltip' in chartConfig)) {
                window['chartData_' + chartConfig.id].dataTable = chartConfig.table;
                chartConfig.options.generateTooltip = chartConfig.generateTooltip;
            }
            var chartClass = chartConfig.type;
            if (!['Histogram', 'Gantt', 'Timeline', 'TreeMap'].includes(chartClass)) {
                chartClass += 'Chart';
            }
            var chart = new google.visualization[chartClass]($container.get(0));
            chart.draw(chartConfig.table, chartConfig.options);
        });
    }

    function register(chartConfig) {
        charts.push(chartConfig);
    }

    return {
        init: function ($container) {

            var $charts = $container.find('.pgui-chart');

            $charts.each(function (i, el) {
                var $chart = $(el);

                if ($chart.data('ready')) {
                    return;
                }

                register(window['chartData_' + $chart.data('id')]);
                $chart.data('ready', true);
            });

            if (googleObjectAvailable())
                google.charts.setOnLoadCallback(drawCharts);

            $(window).off('resize', drawCharts).on('resize', drawCharts);
        },
        draw: drawCharts
    };
});
