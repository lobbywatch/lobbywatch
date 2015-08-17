define(function(require, exports) {
    var sprintf = require("libs/sprintf").sprintf;
    var localizer = require('pgui.localizer').localizer;

    require(['libs/select2'], function () {

        $.fn.select2.locales['php_gen'] = {
            formatMatches: function (matches) {
                if (matches === 1) {
                    return localizer.getString('Select2MatchesOne');
                }
                return sprintf(localizer.getString('Select2MatchesMoreOne'), matches);
            },
            formatNoMatches: function () {
                return localizer.getString('Select2NoMatches');
            },
            formatAjaxError: function (jqXHR, textStatus, errorThrown) {
                return localizer.getString(localizer.getString('Select2AjaxError'));
            },
            formatInputTooShort: function (input, min) {
                var n = min - input.length;
                return sprintf(localizer.getString('Select2InputTooShort'), n);
            },
            formatInputTooLong: function (input, max) {
                var n = input.length - max;
                return sprintf(localizer.getString('Select2InputTooLong'), n);
            },
            formatSelectionTooBig: function (limit) {
                return sprintf(localizer.getString('Select2SelectionTooBig'), limit);
            },
            formatLoadMore: function (pageNumber) {
                return localizer.getString('Select2LoadMore');
            },
            formatSearching: function () {
                return localizer.getString('Select2Searching');
            }
        };

        $.extend($.fn.select2.defaults, $.fn.select2.locales['php_gen']);

    });
});