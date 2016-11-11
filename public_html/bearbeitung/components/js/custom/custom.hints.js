define(function(require, exports, module) {

    // Copied from pgui.grid.js
    $("div[data-hint], img[data-hint], th[data-hint]").each(function() {
//         console.log('custom.hints each');
        // http://getbootstrap.com/javascript/#tooltips
        // http://getbootstrap.com/javascript/#popovers
        $(this).popover({
            placement: 'right',
            container: 'body',
            trigger: 'hover',
            html: true,
            title: $(this).attr('data-hinttitle'),
            content: $(this).attr('data-hint')
        });
    });

});
