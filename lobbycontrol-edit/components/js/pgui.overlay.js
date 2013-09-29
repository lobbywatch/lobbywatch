define(function(require, exports) {

    require('jquery/jquery.tools');
    
    var _ = require('underscore');

    var exposureElement = exports.exposureElement =
        $('<div class="modal-backdrop  in hide"></div>').appendTo($('body'));

    exports.showExposure = function() {
        exposureElement.show();
    };

    exports.hideExposure = function() {
        exposureElement.hide();
    };

    exports.showOverlay = function(image, text, useExposure) {
        useExposure = useExposure || false;

        var overlayContainer =
            $('<div id="hui" class="pgui-overlay"></div>').css('z-index', '1050');
        overlayContainer.appendTo($('body'));

        overlayContainer.
            append(
                $('<div>')
                    .addClass('comment')
                    .html(text)
            );
        var options = {
            top: 260,
            closeOnClick: false,
            load: true
        };
        if (useExposure)
            options = _.extend(options, {mask: {
                color: 'black',
                loadSpeed: 200,
                opacity: 0.8
            }});
        $('#hui').overlay(options);

    };

    exports.hideOverlay = function() {

    };

});