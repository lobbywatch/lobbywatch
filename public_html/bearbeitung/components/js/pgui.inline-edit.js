define(['pgui.form_collection', 'pgui.utils', 'jquery.query'], function (FormCollection, utils) {

    function createContainer(grid, cancelCallback) {
        return grid.getRowTemplate().on('click', '.js-cancel', function (e) {
            if (typeof cancelCallback === 'function') {
                cancelCallback();
            }
            $(e.delegateTarget).remove();
        });
    }

    function getUrl($link) {
        return $.query
            .load($link.data('content-link'))
            .set('is_inline', 'true')
            .toString();
    }

    function putContent($container, content) {
        $container.find('.js-inline-edit-container').first()
            .removeClass('pg-inline-edit-container-loading')
            .html(content);
    }

    function showMessage(grid, responses) {
        if (responses[0].success && grid) {
            grid.showMessage(responses[0].message, responses[0].messageDisplayTime);
        }
    }

    function createInsertForm($container, grid, content, newFormUrl, options) {
        putContent($container, content);

        return new FormCollection(
            $container,
            $container,
            newFormUrl,
            $.extend({
                done: function (hasErrors, responses, params) {
                    showMessage(grid, responses);
                    if (!hasErrors) {
                        var $newRow = $(responses[0].row);
                        $container.after($newRow);
                        grid.integrateRows($newRow);
                        $container.remove();

                        if (params && params.action == 'edit') {
                            $newRow.find('[data-inline-operation=edit]').first().click();
                        }
                        if (params.action === undefined && grid.getReloadPageAfterAjaxOperation()) {
                            location.reload();
                        }
                    }

                    return true;
                },
                copy: function ($sourceForm, copyContent) {
                    $copiedContainer = createContainer(grid);
                    $container.after($copiedContainer);
                    createInsertForm(
                        $copiedContainer,
                        grid,
                        copyContent,
                        newFormUrl,
                        {init: function (formCollection) {
                            formCollection.copyEditorsValues(
                                $sourceForm,
                                formCollection.get(0)
                            );
                        }}
                    );
                }
            }, options)
        );
    }

    return {
        createInsertLink: function ($link, grid, count) {
            count = count || 1;
            var url = getUrl($link);
            $link.on('click', function (e) {
                e.preventDefault();
                var containers = [];
                for (var i = 0; i < count; i++) {
                    var $container = createContainer(grid);
                    grid.container.find('.pg-row-list:first').prepend($container);
                    containers.push($container);
                }

                $.get(url, function (content) {
                    $.each(containers, function (i, $container) {
                        createInsertForm($container, grid, content, url, {});
                    });
                });
            });
        },
        createEditLink: function ($link, grid) {
            var $row = $link.closest('.pg-row');

            $link.data('form-container', null);

            $link.on('click', function (e) {
                e.preventDefault();

                if ($link.data('form-container')) {
                    $link.data('form-container').remove();
                    $link.data('form-container', null);
                    return;
                }

                var $container = createContainer(grid, function () {
                    $link.data('form-container', null);
                    $row.show();
                });

                $link.data('form-container', $container);

                if (grid.isCard()) {
                    $row.hide();
                }

                $row.after($container);

                $.get(getUrl($link), function (content) {
                    putContent($container, content);

                    new FormCollection($container, $container, null, {
                        done: function (hasErrors, responses, params) {
                            showMessage(grid, responses);
                            if (!hasErrors) {
                                var $newRow = $(responses[0].row);
                                utils.replaceRow($row, $newRow);
                                $row.show();
                                grid.integrateRows($newRow);

                                $link.data('form-container', null);
                                $container.remove();

                                if (params && params.action == 'edit') {
                                    $newRow.find('[data-column-name=edit] > a').first().click();
                                }
                                if (params.action === undefined && grid.getReloadPageAfterAjaxOperation()) {
                                    location.reload();
                                }
                            }

                            return true;
                        }
                    });
                });
            });

        }
    };
});