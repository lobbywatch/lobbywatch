define(['underscore', 'jquery.magnific-popup'], function (_) {
    return function initImagePopup($container) {
        _.chain($container.find('a.gallery-item'))
            .reduce(function (acc, el) {
                var columnName = $(el).data('name');
                if (acc.indexOf(columnName) === -1) {
                    acc.push(columnName);
                }

                return acc;
            }, [])
            .each(function (columnName) {
                $container.find('a.gallery-item[data-name=' + columnName + ']').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true,
                        preload: [0,1]
                    },
                    image: {
                        titleSrc: function(item) {
                            return item.el.attr('title');
                        }
                    }
                });
            });
    }
});