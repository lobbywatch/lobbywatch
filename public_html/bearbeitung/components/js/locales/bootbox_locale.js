require(['pgui.localizer', 'libs/bootbox.min', 'jquery'], function (localizer) {

    localizer.load().done(function () {
        bootbox.addLocale('php_gen', {
            OK: localizer.getString('Ok'),
            CANCEL: localizer.getString('Cancel'),
            CONFIRM: localizer.getString('Ok'),
        });
        bootbox.setLocale('php_gen');
    });
});