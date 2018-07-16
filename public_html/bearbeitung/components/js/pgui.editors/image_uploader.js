define(['pgui.editors/plain'], function (PlainEditor) {
    return PlainEditor.extend({
        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);

            var $formGroup = rootElement.closest('.form-group');
            var $btnGroup = $formGroup.find('.btn-group[data-toggle-name]');
            var $toggleTarget = $formGroup.find('[name=' + $btnGroup.data('toggle-name') + ']');

            this.$replaceButton = $btnGroup.find('.js-replace').first();
            this.$image = $formGroup.find('img').first();
            this.isImageBased = this.$image.length == 1;
            this.oldImageSource = this.$image.attr('src');
            this.newImageSource = this.oldImageSource;

            var self = this;

            $btnGroup.on('click', 'button', function () {
                $btnGroup.find('button').removeClass('active');
                var $button = $(this).addClass('active');
                $toggleTarget.val($button.val());
                if (self.isImageBased) {
                    self._setImageProperties($button.val());
                }
            });

            $btnGroup.find('button[value=' + $toggleTarget.val() + ']').addClass('active');
        },

        doChanged: function() {
            if (this.isImageBased) {
                this._processNewImage();
            } else {
                this.$replaceButton.prop('disabled', false);
                this.$replaceButton.click();
            }
            this._super();
        },

        _setImageProperties: function(action) {
            this.$image.toggle(action != 'Remove');
            if (action == 'Keep') {
                this.$image.attr('src', this.oldImageSource);
            } else if (action == 'Replace') {
                this.$image.attr('src', this.newImageSource);
            }
        },

        _processNewImage: function() {
            var self = this;
            var reader  = new FileReader();
            reader.addEventListener("load", function () {
                self.newImageSource = reader.result;
                self.$replaceButton.prop('disabled', false);
                self.$replaceButton.click();
            }, false);

            var file = this.rootElement.get(0).files[0];
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    });
});