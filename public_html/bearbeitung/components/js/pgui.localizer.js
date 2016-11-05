define([], function() {
    var deferred = null;
    var locale = {translations: {}};

    return {
        load: function () {
            deferred = deferred || $.getJSON('components/js/jslang.php').done(function (data) {
                locale = data;
            });
            return deferred;
        },
        getString: function (code, defaultValue) {
            return typeof(locale.translations[code]) !== 'undefined'
                ? locale.translations[code]
                : defaultValue || code;
        },

        getFirstDayOfWeek: function () {
            return locale.firstDayOfWeek || 0;
        }
    };
});