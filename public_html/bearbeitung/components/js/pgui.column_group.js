define(['libs/match-media'], function () {
    var PHONE = 1;
    var TABLET = 2;
    var DESKTOP = 3;
    var LARGE_DESKTOP = 4;

    var PHONE_MAX_WIDTH = 767;
    var TABLET_MAX_WIDTH = 991;
    var DESKTOP_MAX_WIDTH = 1200;

    function match(maxWidth) {
        return window.matchMedia('(max-width: ' + maxWidth + 'px)').matches;
    }

    return function ($container) {
        var $columnGroups = $container.find('.js-column-group');

        function updateGroups() {
            var windowWidth = window.outerWidth;
            var visibility = LARGE_DESKTOP;

            if (match(PHONE_MAX_WIDTH)) {
                visibility = PHONE
            } else if (match(TABLET_MAX_WIDTH)) {
                visibility = TABLET;
            } else if (match(DESKTOP_MAX_WIDTH)) {
                visibility = DESKTOP;
            }

            $columnGroups.each(function (i, el) {
                var $el = $(el);
                $el.attr('colspan', $el.data('visibility')[visibility]);
            });
        }

        $(window).on('resize', updateGroups);
        updateGroups();
    };
});