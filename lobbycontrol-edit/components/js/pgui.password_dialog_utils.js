var PhpGenPasswordDialogUtils = {
    validateConfirmedPassword: function (password, confirmedPassword, errorControl) {
        if (password !== confirmedPassword) {
            errorControl.show();
            return false;
        } else {
            errorControl.hide();
            return true;
        }
    }
};
