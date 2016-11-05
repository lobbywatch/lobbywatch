define([
    'pgui.localizer',
    'pgui.sidebar',
    'pgui.list-page-main',
    'pgui.view-page-main',
    'pgui.compare-page-main',
    'pgui.form-page-main',
    'pgui.admin-page-main',
    'pgui.self_change_password',
    'pgui.user_management_api',
    'pgui.change_password_dialog',
    'pgui.password_dialog_utils',
    'jquery',
    'bootstrap',
    'modal_customize'
], function (
    localizer,
    initSidebar,
    initListPage,
    initViewPage,
    initComparePage,
    initFormPage,
    initAdminPage,
    initSelfChangePassword
) {

    require(['user'], function () {

        if (typeof(window.beforePageLoad) === 'function') {
            window.beforePageLoad();
        }

        $(function () {
            var $body = $('body');
            var pageType = $body.data('page-entry');

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
                }
            });

            if (typeof(window.afterPageLoad) === 'function') {
                window.afterPageLoad();
            }
        });

    });

});