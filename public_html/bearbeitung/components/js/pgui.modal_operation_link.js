define(['pgui.shortcuts'], function (shortcuts) {

    var loadingContent = '<div class="modal-dialog modal-sm">'
        + '<div class="modal-content">'
        + '<div class="modal-body" style="text-align: center">'
        + '<img src="components/assets/img/loading.gif" />'
        + '</div></div>';

    function createModal(content) {
        return $('<div/>', {
            class: 'modal fade',
            tabIndex: '-1'
        }).append(content).appendTo($('body'));
    }

    function createModalLink(FormCollection, $link, $grid, doneCallback) {
        var url = $link.data('content-link');

        $link.click(function (e) {
            e.preventDefault();
            var $modal = createModal(loadingContent).modal();
            $.get(url, {is_modal: true}, function (content) {
                $modal.html(content);

                var formCollection = new FormCollection(
                    $modal,
                    $modal.find('.js-form-collection'),
                    url,
                    {
                        done: function (hasErrors, responses, params) {
                            return doneCallback($modal, hasErrors, responses, params);
                        }
                    }
                );

                shortcuts.push('form');
                $modal.one('hidden.bs.modal', function () {
                    formCollection.destroy();
                    $modal.remove();
                    shortcuts.pop();
                });
            });
        });
    }

    function done($modal, hasErrors, responses, params, $rows, $grid, callback) {
        if (typeof callback === 'function') {
            callback(hasErrors, responses, params);
        }

        $.each(responses, function (i, response) {
            if (response.success && $grid) {
                $grid.showMessage(response.message, response.messageDisplayTime);
            }
        });

        if (hasErrors) {
            return true;
        }

        $modal.modal('hide');

        if (responses.length === 1 && $rows.length === 1) {
            switch (params.action) {
                case 'edit':
                    $rows[0].find('[data-column-name=edit]').find('a').get(0).click();
                    break;
                case 'details':
                    location.href = responses[0].details[params.index];
                    break;
                default:
                    break;
            }
        }

        if (params.action === 'insert' && $grid) {
            $grid.container.find('.pgui-add:first').get(0).click();
        }

        return false;
    }

    return {
        createInsertLink: function (FormCollection, $link, $grid, callback) {
            return createModalLink(FormCollection, $link, $grid, function ($modal, hasErrors, responses, params) {
                $rows = $.map(responses.filter(function (r) { return r.success; }), function (response) {
                    return $grid ? $grid.insertRowAtBegin($(response.row)) : true;
                });

                return done($modal, hasErrors, responses, params, $rows, $grid, callback);
            });
        },
        createEditLink: function (FormCollection, $link, $grid, callback) {
            return createModalLink(FormCollection, $link, $grid, function ($modal, hasErrors, responses, params) {
                var $row = $();
                if (!hasErrors) {
                    $row = $(responses[0].row);
                    $link.closest('.pg-row').replaceWith($row);
                    if ($grid) {
                        $grid.integrateRows($row);
                    }
                }

                return done($modal, hasErrors, responses, params, [$row], $grid, callback);
            });
        }
    };

});
