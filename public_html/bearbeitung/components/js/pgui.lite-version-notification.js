define([], function () {
    return function (purchaseUrl) {
        $.ajax({
            url: "components/templates/lite_version_notification.html",
            dataType: "html",
            success: function (data) {
                bootbox.dialog({
                    title: 'PHP Generator Lite Edition Notification',
                    message: data,
                    backdrop: true,
                    closeButton: true,
                    size: 'large',
                    buttons: {
                        buy: {
                            label: 'Buy now',
                            className: 'btn-primary',
                            callback: function() {
                                window.open(purchaseUrl, '_blank');
                            }
                        },
                        cancel: {
                            label: 'Close',
                            className: 'btn-default'
                        }
                    }
                });
            }
        });
    }
});
