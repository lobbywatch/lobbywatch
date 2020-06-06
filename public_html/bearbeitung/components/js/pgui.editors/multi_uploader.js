define(['pgui.editors/plain', 'pgui.localizer', 'bootstrap-file-input'], function (PlainEditor, localizer) {

    return PlainEditor.extend({
        setLoading: function (isLoading) {
            var $buttons = $('.fileinput-upload').prop('disabled', isLoading);

            if (isLoading) {
                $buttons.addClass('btn-loading');
            } else {
                $buttons.removeClass('btn-loading');
            }
        },

        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;
            var uploadUrl = rootElement.closest('form').attr('action');
            rootElement.fileinput({
                theme: 'explorer-fa',
                uploadUrl: uploadUrl,
                initialPreviewAsData: true,
                showUpload: false,
                showCancel: false,
                uploadAsync: true,
                fileActionSettings: {
                    showUpload: false,
                    showDownload: false,
                    showZoom: false,
                    removeIcon: '<i class="icon-remove"></i>'
                },
                browseIcon: '<i class="icon-folder-open"></i>&nbsp;',
                browseClass: 'btn btn-default',
                removeIcon: '<i class="icon-remove"></i>',
                removeLabel: localizer.getString('RemoveAll'),
                msgValidationErrorIcon: '<i class="icon-exclamation"></i>'
            });

            rootElement.on('filebatchuploadcomplete', function(event, files, extra) {
                window.location.href = $('.js-close-form').first().attr('href');
            });
            rootElement.on('filepreajax', function(event, previewId, index) {
                self.setLoading(true);
            });
            rootElement.on('fileuploaderror', function(event, previewId, index) {
                self.setLoading(false);
            });
        }
    });

});
