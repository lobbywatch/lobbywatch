define(['pgui.localizer'], function(localizer) {
    return {
        validatePasswordBasedForm: function (fieldValues, errorInfo) {
            if (fieldValues.password !== fieldValues.confirmedpassword) {
                errorInfo.SetMessage(localizer.getString('PasswordAndConfirmationPasswordMustMatch'));
                return false;
            }
            else {
                return true;
            }
        }
    }
});
