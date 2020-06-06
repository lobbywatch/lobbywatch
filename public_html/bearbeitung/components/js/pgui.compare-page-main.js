define(['pgui.view-page-main'], function(initViewPage) {
    return function () {
        initViewPage();

        function updateMode() {
            var $this = $(this);
            if ($this.prop('checked')) {
                $('[data-diff=false]').toggle($this.data('mode') === 'all');
            }
        }

        $('.js-compare-mode').on('change', updateMode).each(updateMode);
    }
});
