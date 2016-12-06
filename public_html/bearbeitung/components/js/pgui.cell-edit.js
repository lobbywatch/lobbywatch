define(['pgui.form_collection', 'pgui.shortcuts', 'jquery.popover'], function (FormCollection, shortcuts) {

    var buttonTemplate = '<span class="icon-edit pgui-cell-edit-button"></span>';

    function updatePopoverPosition($el) {
        var plugin = $el.data('plugin_webuiPopover');
        var elPos = plugin.getElementPosition();
        var info = plugin.getTargetPositin(
            elPos,
            plugin.getPlacement(elPos),
            plugin.$target[0].offsetWidth,
            plugin.$target[0].offsetHeight
        );
        plugin.$target.css(info.position);

        if (!info.arrowOffset) {
            info.arrowOffset = {left: '50%'};
        }

        plugin.$target.find('.webui-arrow').css(info.arrowOffset);
    }

    function getPlugin($el) {
        return $el.data('plugin_webuiPopover');
    }

    function destroyPlugin(plugin, $el, options) {
        plugin.hide(true);
        plugin.destroy();
        $el.webuiPopover(options);
    }

    return function ($cell, successCallback) {
        var url = $cell.data('edit-url');

        if (!url) {
            return;
        }

        var options = {
            cache: false,
            type: 'async',
            url: url,
            placement: 'vertical',
            style: 'cell-edit',
            backdrop: true,
            trigger: 'manual'
        };

        var $button = $(buttonTemplate);
        var escapeHanler = function (e) {
            if (e.keyCode === 27) {
                destroyPlugin(getPlugin($cell), $cell, options);
            }
        }

        $button.on('click', function () {
            getPlugin($cell).show();
        });

        $cell.webuiPopover(options);
        $cell.on('shown.webui.popover', function () {
            var plugin = getPlugin($cell);
            var $container = plugin.$target.find('.webui-popover-content');

            if ($container.find('form').length === 0) {
                return;
            }

            var formCollection = new FormCollection($container, $container, null, {
                init: function () {
                    updatePopoverPosition($cell);
                },
                done: function (hasErrors, responses) {
                    if (!hasErrors) {
                        destroyPlugin(plugin, $cell, options);
                        if (typeof successCallback === 'function') {
                            successCallback(responses[0]);
                        }
                    }

                    return true;
                }
            });

            $container.data('forms-ready', true);

            $([
                $('.webui-popover-backdrop').get(0),
                $container.find('.js-cancel').get(0)
            ]).one('click', function () {
                destroyPlugin(plugin, $cell, options);
            });

            $(document).on('keyup', escapeHanler);
            plugin.$target.find('input,select,textarea').on('keyup', escapeHanler);
            shortcuts.push(['quick_edit']);
        }).on('hidden.webui.popover', function () {
            var plugin = getPlugin($cell);
            if (plugin && plugin.hide) {
                destroyPlugin(plugin, $cell, options);
            }
            $(document).off('keyup', escapeHanler);
            shortcuts.pop();
        }).on('mouseenter', function () {
            $cell.append($button);
        }).on('mouseleave', function () {
            $button.detach();
        });

    }
});