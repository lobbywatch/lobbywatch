function PhpGenChangePasswordDialogAdminStrategy(onChangePasswordCallback) {
    this.onChangePasswordCallback = onChangePasswordCallback;

    this.initializeDialog = function() {
        $("#pg-change-password-dialog-header-admin").show();
    };

    this.callChangePasswordCallback = function (currentPassword, newPassword) {
        return this.onChangePasswordCallback(newPassword);
    };
}

function PhpGenChangePasswordDialogUserStrategy(onChangePasswordCallback) {
    this.onChangePasswordCallback = onChangePasswordCallback;

    this.initializeDialog = function() {
        $("#pg-change-password-dialog-header-user").show();
        $("#pg-change-password-dialog-current-password-control-group").show();
    };

    this.callChangePasswordCallback = function (currentPassword, newPassword) {
        return this.onChangePasswordCallback(currentPassword, newPassword);
    };
}

var PhpGenChangePasswordDialog = {
    initialize: function(userStrategy) {
        this.userStrategy = userStrategy;

        this.dialog = $("#pg-change-password-dialog");
        this.currentPassword = $("#pg-change-password-dialog-current-password");
        this.newPassword = $("#pg-change-password-dialog-new-password");
        this.confirmedPassword = $("#pg-change-password-dialog-confirmed-password");
        this.confirmedPasswordErrorControl = $("#pg-change-password-dialog-confirmed-password-error");

        this.dialog.modal('hide');
        this.userStrategy.initializeDialog();

        $("#pg-change-password-dialog-ok-button").click(function(e) {this.onChangePasswordClick(e);}.bind(this));
    },

    setLocalizer: function(localizer) {
      this.localizer = localizer;
    },

    open: function() {
        this.clearPasswordElements();
        this.confirmedPasswordErrorControl.hide();
        this.dialog.modal('show');
        this.dialog.find('input:visible:first').focus();
    },

    clearPasswordElements: function() {
        this.currentPassword.val('');
        this.newPassword.val('');
        this.confirmedPassword.val('');
    },

    onChangePasswordClick: function(e) {
        e.preventDefault();

        if (!PhpGenPasswordDialogUtils.validateConfirmedPassword(
                this.newPassword.val(), this.confirmedPassword.val(), this.confirmedPasswordErrorControl)) {
            return;
        }

        this.userStrategy.callChangePasswordCallback(this.currentPassword.val(), this.newPassword.val()).fail(
            function (message) {
                alert(message);
        }).done(
            function () {
                this.dialog.modal('hide');
                alert(this.localizer.getString('PasswordChanged'));
        }.bind(this));
    }
};

requirejs(['pgui.localizer'],
    function   (localizer) {
        PhpGenChangePasswordDialog.setLocalizer(localizer.localizer);
    }
);
