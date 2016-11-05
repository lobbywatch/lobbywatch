define([
    'pgui.user_management_api',
    'pgui.change_password_dialog'
], function (PhpGenUserManagementApi) {
    return function initSelfChangePassword() {
        PhpGenChangePasswordDialog.initialize(new PhpGenChangePasswordDialogUserStrategy(
            function(currentPassword, newPassword) {
              return PhpGenUserManagementApi.selfChangePassword(currentPassword, newPassword);
            }
        ));

        $('#self-change-password').click(function() {PhpGenChangePasswordDialog.open();});
    };
});

