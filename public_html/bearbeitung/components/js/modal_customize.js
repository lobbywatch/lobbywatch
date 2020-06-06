define(['bootstrap'], function () {

    var escapeHandler = function (e) {
        if (document.activeElement === document.body) {
            e.which == 27 && $('.modal').modal('hide');
        }
    }

    var $document = $(document);
    var origShow = $.fn.modal.Constructor.prototype.show;
    $.fn.modal.Constructor.prototype.show = function () {
        origShow.apply(this, arguments);

        var self = this;
        this.$element.one('shown.bs.modal', function () {
            var $input = self.$element.find('.form-control').get(0);
            if ($input) {
                $input.focus();
            }

            $document.on('keydown', escapeHandler);
        });

        this.$element.one('hidden.bs.modal', function () {
            $document.off('keydown', escapeHandler);
        });

    }

    var origHideModal = $.fn.modal.Constructor.prototype.hideModal;
    $.fn.modal.Constructor.prototype.hideModal = function () {
      var that = this;
      this.$element.hide();
      this.backdrop(function () {
        if (that.$body.find('.modal:visible').length === 0) {
            that.$body.removeClass('modal-open');
        }
        that.resetAdjustments();
        that.resetScrollbar();
        that.$element.trigger('hidden.bs.modal');
      })
    }

    $document.on('show.bs.modal', '.modal', function (event) {
        var parentModal = $document.find('.modal:visible').last();
        if (parentModal.length) {
            var parentWidth = parentModal.find('.modal-dialog').width();
            var $dialog = $(this).find('.modal-dialog');
            if (parentWidth === $dialog.width()) {
                $dialog.css('width', parentWidth - 40);
            }
        }

        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });
});
