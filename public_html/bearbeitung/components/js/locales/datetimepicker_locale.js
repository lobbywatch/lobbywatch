define(['pgui.localizer', 'moment'], function (localizer, moment) {

    localizer.load().done(function () {
        var localeData = {
            months : localizer.getString('CalendarMonths').split('_'),
            monthsShort : localizer.getString('CalendarMonthsShort').split('_'),
            weekdays : localizer.getString('CalendarWeekdays').split('_'),
            weekdaysShort : localizer.getString('CalendarWeekdaysShort').split('_'),
            weekdaysMin : localizer.getString('CalendarWeekdaysMin').split('_'),
            longDateFormat : {
                LT : 'HH:mm',
                LTS : 'HH:mm:ss',
                L : 'DD/MM/YYYY',
                LL : 'D MMMM YYYY',
                LLL : 'D MMMM YYYY HH:mm',
                LLLL : 'dddd, D MMMM YYYY HH:mm'
            },
            calendar : {
                sameDay : '[Today at] LT',
                nextDay : '[Tomorrow at] LT',
                nextWeek : 'dddd [at] LT',
                lastDay : '[Yesterday at] LT',
                lastWeek : '[Last] dddd [at] LT',
                sameElse : 'L'
            },
            relativeTime : {
                future : 'in %s',
                past : '%s ago',
                s : 'a few seconds',
                m : 'a minute',
                mm : '%d minutes',
                h : 'an hour',
                hh : '%d hours',
                d : 'a day',
                dd : '%d days',
                M : 'a month',
                MM : '%d months',
                y : 'a year',
                yy : '%d years'
            },
            ordinalParse: /\d{1,2}(st|nd|rd|th)/,
            ordinal : function (number) {
                var b = number % 10,
                    output = (~~(number % 100 / 10) === 1) ? 'th' :
                    (b === 1) ? 'st' :
                    (b === 2) ? 'nd' :
                    (b === 3) ? 'rd' : 'th';
                return number + output;
            },
            week : {
                dow : localizer.getFirstDayOfWeek(),
                doy : 4  // The week that contains Jan 4th is the first week of the year.
            }
        };

        moment.locale('php_gen', localeData);
    });

});