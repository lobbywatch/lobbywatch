/*global $, define*/
define(function (require, exports) {
    exports.fixLayout = function () {
        $(function () {
            var $navbar = $('#navbar'),
                $loginPanel = $('#login-panel'),
                loginPanelStartingPosition,
                loginPanelOffset = 2;  // offset for correct display in Chrome browser;

            function loginPanelExists() {
                return $loginPanel.length > 0;
            }

            function alignLoginPanel() {
                if (loginPanelExists()) {
                    $loginPanel.offset({left: $(window).width() - $loginPanel.outerWidth(true) - loginPanelOffset});
                    loginPanelStartingPosition = parseInt($loginPanel.css('left'), 10);
                }
            }

            function followTheHorizontalScroll() {
                if (loginPanelExists()) {
                    $loginPanel.offset({left: loginPanelStartingPosition + $(window).scrollLeft()});
                }
            }

            $navbar.width($(document).width());

            if (loginPanelExists()) {
                $loginPanel.css('position', 'absolute');
            }
            alignLoginPanel();

            $('.sidebar-nav-fixed').css('top',
                Math.max(0, $navbar.outerHeight() - $(window).scrollTop())
                );

            $navbar.find('img').load(function () {
                $('.sidebar-nav-fixed').css('top',
                    Math.max(0, $('#navbar').outerHeight() - $(window).scrollTop())
                    );
            });

            $(window).resize(function () {
                alignLoginPanel();
                followTheHorizontalScroll();
            });

            $(window).scroll(function () {
                $('.sidebar-nav-fixed').css('top',
                    Math.max(0, $navbar.outerHeight() - $(window).scrollTop())
                    );
                followTheHorizontalScroll();
            });
        });
    };
});
