/*global $, define*/

/* ToDo: rename to pgui.utils */

define(function (require, exports) {

    exports.updatePopupHints = function ($container) {
        $container.find('.more_hint').each(function () {
            var $hintLink = $(this);
            $hintLink.find('a:first').popover({
                title: '',
                placement: function () {
                    if ($hintLink.offset().top - $(window).scrollTop() < $(window).height() / 2)
                        return 'bottom';
                    else
                        return 'top';
                },
                html: true,
                content: $hintLink.find('.box_hidden').html()
            });
        });
    };

    exports.fixLayout = function () {

        /** @function onScrollHandler Handler for window scroll event */
        function onScrollHandler() {
            adjustNavbarWidth();
        }

        /** @function onResizeHandler Handler for window resize event*/
        function onResizeHandler() {
            adjustNavbarWidth();
        }

        $(document).ready(function () {
            adjustNavbarWidth();
            $(window).resize(onResizeHandler);
            $(window).scroll(onScrollHandler);
        });

    };
    /**
     * @function adjustNavbarWidth
     * @param offset {number=0}
     */
    function adjustNavbarWidth(offset) {
        /** @type {*|jQuery|HTMLElement} */
        var $navbar = $('#navbar');
        /** @type {*|jQuery|HTMLElement} */
        var $window = $(window);
        /** @type {*|jQuery|HTMLElement} */
        var $document = $(document);
        var width = $window.innerWidth() + $window.scrollLeft();

        if (width > $document.outerWidth()) {
            width = $document.outerWidth();
        }

        if (typeof offset == 'number') {
            width += offset;
        }

        $navbar.width(width);
    }

});
