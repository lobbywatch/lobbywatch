define(['jquery.plainoverlay'], function() {

    window.overlay = {
        showOverlay: function(image, text) {
            $('body').plainOverlay('show', {
                progress: function () {
                    return $(
                        '<div class="pgui-overlay">' +
                            '<div class="comment">' + text + '</div>' +
                        '</div>'
                    );
                }
            });
        },
        hideOverlay: function() {
            $('body').plainOverlay('hide');
        }
    };

    return window.overlay;

});