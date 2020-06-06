define(['pgui.shortcuts', 'libs/match-media'], function () {
    return function($body) {
        function toggleSidebar() {
            var className = window.matchMedia('(max-width: 991px)').matches
                ? 'sidebar-active'
                : 'sidebar-desktop-active';

            $body.toggleClass(className);
        }

        $body.on('click touchstart', '.sidebar-backdrop', toggleSidebar);
        $body.on('click', '.toggle-sidebar', toggleSidebar);
    }
});
