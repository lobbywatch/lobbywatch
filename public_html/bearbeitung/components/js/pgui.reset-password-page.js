define(['pgui.form-page-main', 'pgui.validation-utils'], function(initFormPage, validationUtils) {
    return function () {
        initFormPage();

        window['resetPasswordFormValidation'] = validationUtils.validatePasswordBasedForm;
    }
});
