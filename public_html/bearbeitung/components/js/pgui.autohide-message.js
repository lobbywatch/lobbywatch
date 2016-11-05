define([], function () {
    return function ($el, messageDisplayTime, slideTime) {
        slideTime = slideTime || 1000;
        messageDisplayTime = parseInt(messageDisplayTime || $el.data('display-time') || 0);
        if (messageDisplayTime > 0) {
            setTimeout(function () {
                $el.slideUp(slideTime);
            }, parseInt(messageDisplayTime) * 1000);
        }
    }
});