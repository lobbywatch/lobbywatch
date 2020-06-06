define(['class', 'pgui.charts', 'underscore'], function (Class, charts, _) {

    function rand() {
        return (Math.ceil(Math.random() * 100000000));
    }

    var RowDetails = Class.extend({
        init: function (grid, $row, $button, $expandAll, details) {
            this.$row = $row;
            this.$button = $button;
            this.$expandAll = $expandAll;
            this.grid = grid;
            this.details = details;
            this.$container = grid.isCard()
                ? createCardContainer(grid, $row)
                : createTableContainer(grid, $row);
            this.$container.find('.js-content').append(createCloseButton()).append(createLoader());
            this.isLoaded = false;
            this.isLoading = false;
        },

        open: function () {
            this._load();
            this.$container.show();
            this.$container.find('.js-close').first().on('click', _.bind(this.close, this));
            window.scroll(window.scrollX, this.$container.offset().top - $('#navbar').height() - 32);
            charts.draw();

            this.toggleButtons(true);
        },

        close: function () {
            this.$container.hide();
            this.toggleButtons(false);
        },

        toggle: function (isOpen) {
            if (isOpen) {
                this.open();
            } else {
                this.close();
            }
        },

        toggleButtons: function (isOpen) {
            var $rows = this.grid.getRows();

            if (this.grid.isCard()) {
                if (isOpen) {
                    $rows.find('.js-expand-details.expanded').click();
                }

                this.$row.toggleClass('grid-card-item-details-expanded', isOpen);
            }

            this.$button
                .toggleClass('collapsed', !isOpen)
                .toggleClass('expanded', isOpen);

            var isAllOpened = $rows.length === $rows.find('.js-expand-details.expanded').length;
            this.$expandAll
                .toggleClass('collapsed', !isAllOpened)
                .toggleClass('expanded', isAllOpened);
        },

        _load: function () {
            if (this.isLoaded || this.isLoading) {
                return;
            }

            this.isLoading = true;

            $.when.apply($, _.map(this.details, function (detail) {
                return $.get(detail.gridLink, {inline: true})
                    .then(function (html) {
                        return {
                            caption: detail.caption,
                            id: detail.detailId,
                            $el: $(html)
                        };
                    });
            })).done(_.bind(function () {
                this.isLoaded = true;
                this.isLoading = false;
                this.$container.find('.js-loading').hide();
                this.$container.find('.js-content').append(
                    createContent([].slice.call(arguments))
                );

                this._initGrids();
            }, this));
        },

        _initGrids: function () {
            this.$container.find('.js-grid').each(_.bind(function (i, el) {
                new this.grid.constructor($(el));
            }, this));

            charts.init(this.$container);
        },

        remove: function() {
            this.$container.remove();
        }
    });

    function createTableContainer(grid, $row) {
        var $container = $('<tr class="grid-details grid-details-table detail"><td class="js-content" colspan="' + grid.getColumnCount() + '"></td></tr>');
        $row.after($container);
        return $container;
    }

    function createCardContainer(grid, $row) {
        var $container = $('<div class="grid-card-item col-xs-12"><div class="well grid-details grid-details-card js-content"></div></div>');
        var $rows = grid.getRows();

        function attachContainer() {
            var listWidth = grid.container.find('.pg-row-list').width();
            var cardWidth = $row.width();
            var cardsInARow = Math.floor(listWidth / cardWidth);
            var index = $rows.index($row);
            var beforeDetailsIndex = Math.min(
                $rows.length - 1,
                (index + cardsInARow - (index % cardsInARow)) - 1
            );

            $container.detach();
            $($rows.get(beforeDetailsIndex)).after($container);
        }

        $(window).on('resize', attachContainer);
        attachContainer();

        return $container;
    }

    function createLoader() {
        return $('<div class="grid-details-loading js-loading"><img src="components/assets/img/loading.gif"></div>');
    }

    function createCloseButton() {
        return $('<button class="close js-close" type="button">&times;</button>');
    }

    function createContent(details) {
        var $tabs = $('<ul class="nav nav-tabs grid-details-tabs">');
        var $panes = $('<div class="tab-content">');

        _.each(details, function (detail, i) {
            var id = detail.id + rand();
            $tabs.append(
                $('<li>')
                    .toggleClass('active', i === 0)
                    .append($('<a href="#' + id + '" data-toggle="tab">' + detail.caption + '</a>'))
            );

            $panes.append(
                $('<div class="tab-pane" id="' + id + '">')
                    .toggleClass('active', i === 0)
                    .append(detail.$el)
            );
        });

        $tabs.find('[data-toggle="tab"]').on('shown.bs.tab', function () {
            charts.draw();
        });

        return $('<div>').append($tabs).append($panes);
    }

    return function (grid, $expandAll, $rows) {

        $expandAll.off('click').on('click', function (e) {
            var isOpened = $expandAll.hasClass('expanded');

            $rows.find('.js-expand-details')
                .filter(isOpened ? '.expanded' : '.collapsed')
                .click();

            $expandAll
                .toggleClass('collapsed', isOpened)
                .toggleClass('expanded', !isOpened);

            e.preventDefault();
        });

        $rows.each(function (i, row) {
            var $row = $(row);
            var $button = $row.find('.js-expand-details').first();

            if ($button.data('details')) {
                return;
            }

            $button.off('click').on('click', function (e) {
                var isOpened = $button.hasClass('expanded');
                var nextIsOpened = !isOpened;
                var details = $button.data('details');

                if (!details) {
                    details = new RowDetails(grid, $row, $button, $expandAll, $button.data('info'));
                    $button.data('details', details);
                    $row.data('details', details);
                }

                details.toggle(nextIsOpened);

                e.preventDefault();
            });
        });
    }
});
