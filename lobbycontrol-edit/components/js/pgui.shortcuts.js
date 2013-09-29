define(function(require, exports)
{

    require('jquery/jquery.hotkeys');
    var inputControlFocused = false;

    $(function()
    {
        $('input').blur(function() {
            inputControlFocused = false;
        });
        $('input').focus(function() {
            inputControlFocused = true;
        });
    });

    exports.initializeShortCuts = function(container)
    {
        container.find('[pgui-shortcut]').each(function() {

            var item = $(this);
            var shortCut = item.attr('pgui-shortcut');

            $(document).bind('keydown', shortCut, function() {
                if (!inputControlFocused) {
                    if (item.is('a'))
                        window.location.href = item.attr('href');
                }
            });

        });
    };
});