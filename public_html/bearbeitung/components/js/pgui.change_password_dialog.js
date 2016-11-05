define(['pgui.utils', 'pgui.localizer', 'bootstrap'], function(utils, localizer) {

    window.PhpGenChangePasswordDialogAdminStrategy = function(onChangePasswordCallback) {
        this.onChangePasswordCallback = onChangePasswordCallback;

        this.initializeDialog = function () {
            $("#pg-change-password-dialog-header-admin").show();
            $("#pg-change-password-dialog-header-user").hide();
            $("#pg-change-password-dialog-current-password-form-group").hide();
        };

        this.callChangePasswordCallback = function (currentPassword, newPassword) {
            return this.onChangePasswordCallback(newPassword);
        };
    };

    window.PhpGenChangePasswordDialogUserStrategy = function(onChangePasswordCallback) {
        this.onChangePasswordCallback = onChangePasswordCallback;

        this.initializeDialog = function () {
            $("#pg-change-password-dialog-header-admin").hide();
            $("#pg-change-password-dialog-header-user").show();
            $("#pg-change-password-dialog-current-password-form-group").show();
        };

        this.callChangePasswordCallback = function (currentPassword, newPassword) {
            return this.onChangePasswordCallback(currentPassword, newPassword);
        };
    };

    window.PhpGenChangePasswordDialog = {

        initialize: function (userStrategy) {
            this.dialog = $("#pg-change-password-dialog");

            this.userStrategy = userStrategy;
            this.currentPassword = $("#pg-change-password-dialog-current-password");
            this.newPassword = $("#pg-change-password-dialog-new-password");
            this.confirmedPassword = $("#pg-change-password-dialog-confirmed-password");
            this.confirmedPasswordErrorControl = $("#pg-change-password-dialog-confirmed-password-error");

            this.dialog.modal('hide');
            this.userStrategy.initializeDialog();

            $("#pg-change-password-dialog-ok-button").off('click').on('click', function (e) {
                this.onChangePasswordClick(e);
            }.bind(this));
        },

        open: function () {
            this.clearPasswordElements();
            this.confirmedPasswordErrorControl.hide();
            this.dialog.modal('show');
            this.dialog.find('input:visible:first').focus();
        },

        clearPasswordElements: function () {
            this.currentPassword.val('');
            this.newPassword.val('');
            this.confirmedPassword.val('');
        },

        onChangePasswordClick: function (e) {
            e.preventDefault();

            if (!PhpGenPasswordDialogUtils.validateConfirmedPassword(
                    this.newPassword.val(), this.confirmedPassword.val(), this.confirmedPasswordErrorControl)) {
                return;
            }

            this.userStrategy.callChangePasswordCallback(this.currentPassword.val(), this.newPassword.val()).fail(
                function (message) {
                    utils.showErrorMessage(message);
                }).done(
                function () {
                    this.dialog.modal('hide');
                    utils.showSuccessMessage(localizer.getString('PasswordChanged'));
                }.bind(this));
        }
    };

});