define(['pgui.editors/plain'], function (PlainEditor) {
    return PlainEditor.extend({
        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);

            var $formGroup = rootElement.closest('.form-group');
            var $btnGroup = $formGroup.find('.btn-group[data-toggle-name]');
            var $toggleTarget = $formGroup.find('[name=' + $btnGroup.data('toggle-name') + ']');

            $btnGroup.on('click', 'button', function () {
                $btnGroup.find('button').removeClass('active');
                var $button = $(this).addClass('active');
                $toggleTarget.val($button.val());
            });

            $btnGroup.find('button[value=' + $toggleTarget.val() + ']').addClass('active');
        },

        doChanged: function() {
            this.rootElement.closest('.col-input').find('button[value=Replace]').click();
            this._super();
        }
    });
});