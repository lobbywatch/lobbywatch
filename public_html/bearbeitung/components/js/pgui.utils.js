define([
    'pgui.autohide-message',
    'bootbox'
], function(autoHideMessage) {

    function _buildMessage(message, alertType) {
        return $('<div>')
            .addClass('alert').addClass('alert-' + alertType)
            .css('margin-bottom', 0)
            .html('<p>' + message + '</p>');
    }

    function _showBootBoxAlert(messageToDisplay) {
        bootbox.alert({
            closeButton: false,
            message: messageToDisplay
        });
    }

    return {
        showInfoMessage: function(message) {
            _showBootBoxAlert(_buildMessage(message, 'info'));
        },
        showSuccessMessage: function(message) {
            _showBootBoxAlert(_buildMessage(message, 'success'));
        },
        showWarningMessage: function(message) {
            _showBootBoxAlert(_buildMessage(message, 'warning'));
        },
        showErrorMessage: function(message) {
           _showBootBoxAlert(_buildMessage(message, 'danger'));
        },
        showMessage: function (message) {
            _showBootBoxAlert(message);
        },
        updatePopupHints: function ($container) {
            $container.find('.js-more-hint').each(function () {
                var $hintLink = $(this);
                var $hintMessage = $hintLink.siblings('.js-more-box').html();
                $hintLink
                    .on('click', function() {
                        $(this).popover('hide');
                        _showBootBoxAlert($hintMessage);
                        return false;
                    })
                    .popover({
                        title: '',
                        placement: function () {
                            return $hintLink.offset().top - $(window).scrollTop() < $(window).height() / 2
                                ? 'bottom'
                                : 'top';
                        },
                        html: true,
                        trigger: 'hover',
                        content: $hintMessage
                    });
            });
        },
        buildDismissableMessage: function (className, message, messageDisplayTime) {
            if (!message) {
                return null;
            }

            var $message = $('<div class="alert alert-' + className + ' alert-dismissable">'
                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>'
                + message + '</div>');

            autoHideMessage($message, messageDisplayTime);

            return $message;
        },
        createLoadingModalDialog: function(operationName) {
            var operationNameContent = (operationName !== undefined) ? ('&nbsp&nbsp<span>' + operationName + '</span>') : '';
            var loadingContent = '<div class="modal-dialog modal-sm">'
                + '<div class="modal-content">'
                + '<div class="modal-body" style="text-align: center">'
                + '<img src="components/assets/img/loading.gif" />'
                + operationNameContent
                + '</div></div>';

            return $('<div/>', {
                class: 'modal fade',
                tabIndex: '-1',
                'data-backdrop': 'static'
            }).append(loadingContent).appendTo($('body'));
        },
        replaceRow: function($oldRow, $newRow) {
            var $oldRowDetails = $oldRow.find('.details').first();
            var $newRowDetails = $newRow.find('.details').first();
            if (($oldRowDetails.length > 0) && ($newRowDetails.length > 0)) {
                $newRowDetails.replaceWith($oldRowDetails);
            }
            $oldRow.replaceWith($newRow);
        }
    }
});
