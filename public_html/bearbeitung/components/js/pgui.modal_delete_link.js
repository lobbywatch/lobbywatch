define([
    'pgui.localizer',
    'pgui.utils',
    'bootbox',
], function(localizer, utils) {

    return function ($container, grid) {
        $container
            .off('click', 'a[data-modal-operation=delete]')
            .on('click', 'a[data-modal-operation=delete]', function (e) {
                e.preventDefault();
                var $button = $(this);
                bootbox.confirm(localizer.getString('DeleteRecordQuestion'), function(confirmed) {
                    if (!confirmed) {
                        return;
                    }

                    var $modal = utils.createLoadingModalDialog(localizer.getString('Deleting')).modal();
                    $modal.one('hidden.bs.modal', function () {
                        $modal.remove();
                    });

                    var url = $button.attr('href');
                    var handlerName = $button.attr('data-delete-handler-name');

                    $.ajax({
                        url: url + "&hname=" + handlerName,
                        dataType: 'json',
                        success: function (response) {
                            if (!response.success) {
                                utils.showErrorMessage(response.message);
                                return;
                            }

                            grid.removeRow($button.closest('.pg-row'));
                            grid.showMessage(response.message, response.messageDisplayTime);
                            if (grid.getReloadPageAfterAjaxOperation()) {
                                location.reload();
                            }
                        },
                        complete: function() {
                            $modal.modal('hide');
                        }
                    });
                });
            });
    }
});
