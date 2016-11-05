define([
    'underscore',
    'pgui.localizer',
    'bootbox',
    'jquery.query'
], function (_, localizer) {

    var template = _.template(
        '<img class="pgui-field-embedded-video-thumb pgui-field-embedded-video-fade" src="<%=thumbnail_url %>" />'
        + '<span class="pgui-field-embedded-video-icon pgui-field-embedded-video-fade icon-play"></span>'
    );

    var templatePreloader = '<img class="pgui-field-embedded-video-preloader" src="components/assets/img/loading.gif">';

    function closeCallback () {
        return true;
    }

    function injectAutoplay(src) {
        src = src.split('?');
        var params = (src[1] || '').split('&');
        params.push('autoplay=1');

        return src[0] + '?' + params.join('&');
    }

    function getIframe(response, autoplay) {
        var $iframe = $(response.html);
        $iframe
            .removeClass()
            .addClass('pgui-field-embedded-video-iframe')
            .removeAttr('width')
            .removeAttr('height');

        if (autoplay) {
            $iframe.attr('src', injectAutoplay($iframe.attr('src')));
        }

        return $iframe;
    }

    function openModal($el) {
        if ($el.data('api')) {
            var api = $el.data('api');
            bootbox.dialog({
                message: getIframe(api, $el.data('autoplay')),
                title: api.title || null,
                closeButton: true,
                backdrop: true,
                onEscape: closeCallback,
                buttons: {
                    cancel: {
                        label: localizer.getString('Close'),
                        callback: closeCallback
                    }
                }
            });
        } else {
            $el.append(templatePreloader);
            getApi($el, function() {
                $el.find('.pgui-field-embedded-video-preloader').remove();
                openModal($el);
            });
        }
    }

    function getApi($el, cb) {
        var url = $el.data('url');
        $.getJSON('https://noembed.com/embed', {url: url}).success(function(response) {
            $el.data('api', response);
            if (response.type !== 'video') {
                $el.html(url);
                $el.find('.pgui-field-embedded-video-preloader').remove();
                return;
            }
            cb(response);
        });
    }

    return function (container, autoplay, allWithoutModal) {
        autoplay = autoplay || true;
        container.find('.pgui-field-embedded-video').each(function (i, el) {
            var $el = $(el);
            if (!$el.data('url')) {
                return;
            }
            $el.data('autoplay', autoplay);

            var $wrapper = $el.closest('.pgui-embedded-video-wrapper');
            var withoutModal = allWithoutModal || $wrapper.data('without-modal');

            if (withoutModal) {
                getApi($el, function(response) {
                    $el.html(getIframe(response));
                    $el.find('.pgui-field-embedded-video-preloader').remove();
                });
            }
            else {
                $el.on('click', function () {
                    openModal($el);
                    return false;
                });

                if (!$el.find('.pgui-field-embedded-video-thumb').length) {
                    getApi($el, function(response) {
                        if (typeof($el.data('thumb')) === 'undefined' || $el.data('thumb')) {
                            $el.prepend($(template(response)));
                        }
                        setTimeout(function() {
                            $el.find('.pgui-field-embedded-video-fade').removeClass('pgui-field-embedded-video-fade');
                            $el.find('.pgui-field-embedded-video-preloader').addClass('pgui-field-embedded-video-fade');
                        }, 10);
                    });
                }
            }

        });
    }

});