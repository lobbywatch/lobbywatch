define([
    'pgui.field-embedded-video',
    'pgui.cell-edit',
    'pgui.utils',
    'pgui.image_popup'
], function(showFieldEmbeddedVideo, initCellEdit, utils, initImagePopup) {
    var $body = $('body');

    return function init(item) {
        if (item.data('modal-view')) {
            return;
        }
        var $modalContainer = $('<div class="modal fade"></div>');
        var contentUrl = item.data('content-link');
        item.click(function (e) {
            e.preventDefault();
            $.get(contentUrl, function (content) {
                $body.append($modalContainer);
                $modalContainer
                    .html(content)
                    .one('hidden.bs.modal', function () {
                        this.remove();
                    })
                    .modal();

                showFieldEmbeddedVideo($modalContainer, false, false);
                initImagePopup($modalContainer);

                $modalContainer.find('[data-edit-url]').each(function (i, el) {
                    var $el = $(el);
                    var columnName = $el.data('column-name');
                    initCellEdit($el, function (response) {
                        $el.html(response.columns[columnName]);
                        $modalContainer.find('.js-message-container').append(utils.buildDismissableMessage(
                            'success',
                            response.message,
                            response.messageDisplayTime
                        ));
                    });
                });

                $modalContainer.find('[data-modal-operation=view]').each(function (i, el) {
                    init($(el));
                });
            });
        });
        item.data('modal-view', true);
    }
});
