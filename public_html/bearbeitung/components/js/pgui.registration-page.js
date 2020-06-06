define(['pgui.form-page-main', 'pgui.validation-utils'], function(initFormPage, validationUtils) {
    return function () {
        initFormPage();

        window['registrationFormValidation'] = validationUtils.validatePasswordBasedForm;
    }

});
