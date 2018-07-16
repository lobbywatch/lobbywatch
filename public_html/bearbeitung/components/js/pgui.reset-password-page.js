define(['pgui.form-page-main'], function(initFormPage) {
    return function () {
        initFormPage();

        function validateForm(fieldValues, errorInfo) {
            if (fieldValues.password !== fieldValues.confirmedpassword) {
                errorInfo.SetMessage('Password and confirmation password must match');
                return false;
            }
            else {
                return true;
            }
        }

        window['resetPasswordFormValidation'] = validateForm;
    }
});
