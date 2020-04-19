define(['underscore', 'jquery.magnific-popup'], function (_) {

    function handleLinkedImages($container) {
        $container.find(".linked-images-retriever").on("click", function (e) {
            e.preventDefault();
            var $currentLink = $(this);
            var $currentLinkParent = $currentLink.parent();
            if ($currentLink.hasClass('retrieved')) {
                $currentLinkParent.find('a.gallery-item').first().click();
            } else {
                var url = $currentLink.data('url');
                $.get(url, function (content) {
                    $currentLinkParent.append(content);
                    $currentLinkParent.find('a.gallery-item').magnificPopup({
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
                    $currentLink.addClass('retrieved');
                    $currentLinkParent.find('a.gallery-item').first().click();
                });
            }
        });
    }

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
                $container.find('a.gallery-item[data-name="' + columnName + '"]').magnificPopup({
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

        handleLinkedImages($container);
    }
});