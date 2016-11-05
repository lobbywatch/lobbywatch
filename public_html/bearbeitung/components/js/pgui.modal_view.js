define([
    'pgui.field-embedded-video',
    'pgui.cell-edit',
    'pgui.utils'
], function(showFieldEmbeddedVideo, initCellEdit, utils) {
    var $body = $('body');
    var $modalContainer = $('<div class="modal fade"></div>');

    return function(item) {
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

                $modalContainer.find('[data-edit-url]').each(function (i, el) {
                    var $el = $(el)
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
            });
        });
    }
});
