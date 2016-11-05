define(['class', 'pgui.text_highlight'], function (Class, highlight) {

    return Class.extend({

        init: function ($container) {
            var self = this;
            this.$input = $container.find('.js-input');

            $container
                .on('click', '.js-submit', function () {
                    self.submit();
                })
                .on('click', '.js-reset', function () {
                    self.$input.val('');
                    self.submit();
                })
                .on('keyup', '.js-input', function (e) {
                    if (e.keyCode === 13) {
                        self.submit();
                    }
                });
        },

        setColumnNames: function (columnNames) {
            this.columnNames = columnNames;
            return this;
        },

        getValue: function () {
            return this.$input.val();
        },

        highlight: function ($container) {
            highlight.HighlightTextInAllGrid(
                $container.find($.map(this.columnNames, function (name) {
                    return '[data-column-name="' + name + '"]';
                }).join(',')),
                this.getValue(),
                'ALL'
            );
            return this;
        },

        reset: function () {
            this.$input.val('');
            return this;
        },

        submit: function () {
            $('<form method="GET">').append(this.$input.clone()).hide().appendTo('body').submit();
            return this;
        }

    });

});
