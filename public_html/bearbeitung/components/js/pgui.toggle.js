define(['pgui.utils', 'bootstrap.toggle'], function (utils) {

    return function ($container, $grid) {
        $container.find('.pgui-toggle-checkbox').each(function (i, el) {
            var $el = $(el);
            var toggleOptions  = {
                on: $el.data('toggle-on-caption'),
                off: $el.data('toggle-off-caption'),
                size: $el.data('toggle-size'),
                onstyle: $el.data('toggle-on-style'),
                offstyle: $el.data('toggle-off-style')
            };
            if ($el.data('toggle-width')) {
                toggleOptions.width = $el.data('toggle-width');
            }
            if ($el.data('toggle-height')) {
                toggleOptions.height = $el.data('toggle-height');
            }
            $el.bootstrapToggle(toggleOptions);
            if ($el.data('toggle-disabled')) {
                $el.bootstrapToggle('disable');
            }
            var changeFailed = false;
            $el.change(function() {
                if (changeFailed) {
                    changeFailed = false;
                    return;
                }
                var currentElement = $(this);
                var data = currentElement.data('pk-values') ? currentElement.data('pk-values') : {};
                data['column'] = currentElement.closest('td').data('column-name');
                var fieldName = currentElement.data('editor-name');
                if (currentElement.is(':checked')) {
                    data[fieldName] = 1;
                }
                $.ajax({
                    method: "POST",
                    url: currentElement.data('editing-link'),
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        if (!response.success) {
                            changeFailed = true;
                            $el.bootstrapToggle('toggle');
                            utils.showErrorMessage(response.message);
                            return;
                        }
                        if (typeof $grid !== 'undefined') {
                            $grid.showMessage(response.message, response.messageDisplayTime);
                            var $row = $(response.row);
                            utils.replaceRow(currentElement.closest('.pg-row'), $row);
                            $grid.integrateRows($row);
                        }
                    }
                });
            })
        });

    }
});
