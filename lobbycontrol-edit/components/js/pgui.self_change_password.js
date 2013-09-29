(function () {
    PhpGenChangePasswordDialog.initialize(new PhpGenChangePasswordDialogUserStrategy(
        function(currentPassword, newPassword) {
          return PhpGenUserManagementApi.selfChangePassword(currentPassword, newPassword);
    }));
    $('#self-change-password').click(function() {PhpGenChangePasswordDialog.open();});
})();

