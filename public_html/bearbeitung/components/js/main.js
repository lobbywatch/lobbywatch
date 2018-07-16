define([
    'pgui.localizer',
    'pgui.sidebar',
    'pgui.list-page-main',
    'pgui.view-page-main',
    'pgui.compare-page-main',
    'pgui.form-page-main',
    'pgui.admin-page-main',
    'pgui.registration-page',
    'pgui.reset-password-page',
    'pgui.self_change_password',
    'pgui.utils',
    'pgui.user_management_api',
    'pgui.change_password_dialog',
    'pgui.password_dialog_utils',
    'jquery',
    'bootstrap',
    'custom/custom-require', // Afterburner
    'modal_customize'
], function (
    localizer,
    initSidebar,
    initListPage,
    initViewPage,
    initComparePage,
    initFormPage,
    initAdminPage,
    initRegistrationPage,
    initResetPasswordPage,
    initSelfChangePassword,
    utils
) {

    require(['user'], function () {

        if (typeof(window.beforePageLoad) === 'function') {
            window.beforePageLoad();
        }

        $(function () {
            var $body = $('body');
            var pageType = $body.data('page-entry');
            var inactivityTimeout = $body.data('inactivity-timeout');

            if (pageType != '' && inactivityTimeout > 0) {
                require(['inactivity_timeout'], function () {
                    initInactivityTimeout(inactivityTimeout);
                });
            }

            initSidebar($body);
            initSelfChangePassword();

            localizer.load().done(function () {
                switch (pageType) {
                    case 'list':
                        initListPage();
                        break;
                    case 'view':
                        initViewPage();
                        break;
                    case 'compare':
                        initComparePage();
                        break;
                    case 'form':
                        initFormPage();
                        break;
                    case 'admin':
                        initAdminPage();
                        break;
                    case 'register':
                        initRegistrationPage();
                        break;
                    case 'reset-password':
                        initResetPasswordPage();
                        break;
                }

                if ($body.data('inactivity-timeout-expired')) {
                    utils.showMessage(localizer.getString('InactivityTimeoutExpired'));
                }
            });

            if (typeof(window.afterPageLoad) === 'function') {
                window.afterPageLoad();
            }
        });

    });

});