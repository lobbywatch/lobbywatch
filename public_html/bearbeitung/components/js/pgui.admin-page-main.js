define([
    'pgui.user_management_api',
    'pgui.localizer',
    'libs/sprintf',
    'pgui.utils',
    'pgui.change_password_dialog',
    'bootbox',
    'mootools-core',
    'jquery.tmpl',
    'knockout'
], function (PhpGenUserManagementApi, localizer, sprintf, utils) {

    return function () {

        window.PhpGenAdmin.Utils =
        {
            showProgressCursor: function () {
                $('body').addClass('cursor-progress');
            },

            hideProgressCursor: function () {
                $('body').removeClass('cursor-progress');
            }
        };

        window.PhpGenAdmin.UserViewModel = new Class({
            initialize: function (api, id, name, password, email, status, editable) {
                this.api = api;
                this.id = id;
                this.name = ko.observable(name);
                this.password = password;
                this.email = ko.observable(email);
                this.status = ko.observable(status);
                this.editable = ko.observable(editable);
                this.grantsLoaded = ko.observable(false);
                this.grantsExpanded = ko.observable(false);
                this.grants = ko.observableArray([]);
            },

            _loadGrants: function () {
                this.api.getUserGrants(this.id).done(function (grants) {
                    for (var i = 0; i < grants.length; i++) {
                        this.grants.push(
                            new PhpGenAdmin.UserPageGrantsViewModel(
                                this.api,
                                this.id,
                                grants[i].name,
                                grants[i].caption,
                                grants[i].selectGrant,
                                grants[i].updateGrant,
                                grants[i].insertGrant,
                                grants[i].deleteGrant,
                                grants[i].adminGrant
                            )
                        );
                    }
                    this.grantsLoaded(true);
                }.bind(this));
            },

            collapseGrants: function () {
                this.grantsExpanded(false);
            },

            expandGrants: function () {
                if (!this.grantsExpanded()) {
                    this.grantsExpanded(true);
                    if (!this.grantsLoaded())
                        this._loadGrants();
                }
            },

            toggleGrantsExpanded: function () {
                if (!this.grantsExpanded()) {
                    this.grantsExpanded(true);
                    if (!this.grantsLoaded())
                        this._loadGrants();
                }
                else {
                    this.grantsExpanded(false);
                }
            }
        });

        window.PhpGenAdmin.UserPageGrantsViewModel = new Class({
            initialize: function (api, userId, pageName, caption, selectGrant, updateGrant, insertGrant, deleteGrant, adminGrant) {
                this.api = api;
                this.userId = userId;
                this.pageName = ko.observable(pageName);
                this.caption = ko.observable(caption);
                this.selectGrant = ko.observable(selectGrant);
                this.updateGrant = ko.observable(updateGrant);
                this.insertGrant = ko.observable(insertGrant);
                this.deleteGrant = ko.observable(deleteGrant);
                this.adminGrant = ko.observable(adminGrant);

                this.selectGrant.subscribe(function (newValue) {
                    this._updateUserGrant('SELECT', newValue)
                }.bind(this));
                this.updateGrant.subscribe(function (newValue) {
                    this._updateUserGrant('UPDATE', newValue)
                }.bind(this));
                this.insertGrant.subscribe(function (newValue) {
                    this._updateUserGrant('INSERT', newValue)
                }.bind(this));
                this.deleteGrant.subscribe(function (newValue) {
                    this._updateUserGrant('DELETE', newValue)
                }.bind(this));
                this.adminGrant.subscribe(function (newValue) {
                    this._updateUserGrant('ADMIN', newValue)
                }.bind(this));
            },

            isApplication: function() {
                return this.pageName().length === 0;
            },

            _updateUserGrant: function (grantName, newValue) {
                PhpGenAdmin.Utils.showProgressCursor();
                (function () {
                    if (newValue)
                        return this.api.addUserGrant(this.userId, this.pageName(), grantName);
                    else
                        return this.api.removeUserGrant(this.userId, this.pageName(), grantName);
                }.bind(this))()
                    .always(function () {
                        PhpGenAdmin.Utils.hideProgressCursor();
                    })
                    .fail(function (message) {
                        utils.showErrorMessage(message);
                    });

            }
        });

        window.PhpGenAdmin.AdminPanelViewModel = function (api) {
            this.api = api;

            this.newUser =
            {
                id: ko.observable(''),
                name: ko.observable('New user'),
                password: ko.observable(''),
                confirmedPassword: ko.observable(''),
                email: ko.observable('')
            };

            this.changePasswordUser =
            {
                id: ko.observable(''),
                name: ko.observable('')
            };

            this.editUser =
            {
                id: ko.observable(''),
                name: ko.observable('Edit user'),
                password: ko.observable(''),
                email: ko.observable(''),
                status: ko.observable('')
            };

            this.currentUserRoles = ko.observableArray([]);

            this.invokeRemoveUserDialog = function (/*UserViewModel*/user) {
                var self = this;

                bootbox.confirm(
                    sprintf.sprintf(localizer.getString('DeleteUserConfirmation'), user.name()),
                    function (isConfirmed) {
                        if (!isConfirmed) {
                            return;
                        }

                        PhpGenAdmin.Utils.showProgressCursor();
                        self.api.removeUser(user.id)
                            .done(function (result) {
                                self.users.remove(user);
                            }.bind(this))
                            .always(function () {
                                PhpGenAdmin.Utils.hideProgressCursor();
                            }).fail(function (message) {
                                utils.showErrorMessage(message);
                            });
                    }
                );

            };

            this.invokeChangeUserPasswordDialog = function (user) {
                var self = this;
                self.changePasswordUser.id(user.id);
                self.changePasswordUser.name(user.name());
                PhpGenChangePasswordDialog.open();
            };

            this.invokeEditUserDialog = function (user) {
                var self = this;
                var dialog = $("#pg-admin-edit-user-dialog");

                self.editUser.id(user.id);
                self.editUser.name(user.name());
                self.editUser.password(user.password);
                self.editUser.email(user.email());
                self.editUser.status(user.status());

                $('#save-edit-user-dialog').bind('click', function (e) {
                    e.preventDefault();

                    if (self.emailBasedFeaturesEnabled) {
                        self.api.updateUser(self.editUser.id(), self.editUser.name(), self.editUser.email(), self.editUser.status())
                            .fail(function (message) {
                                utils.showErrorMessage(message);
                            })
                            .done(function (result) {
                                dialog.modal('hide');
                                user.name(result.name);
                                user.email(result.email);
                                user.status(result.status);
                            });
                    } else {
                        self.api.changeUserName(self.editUser.id(), self.editUser.name())
                            .fail(function (message) {
                                utils.showErrorMessage(message);
                            })
                            .done(function (result) {
                                dialog.modal('hide');
                                user.name(result.username);
                            });
                    }
                });

                dialog.modal('show');
                $('#user-username').focus();
                dialog.bind('hidden.bs.modal', function () {
                    $('#save-edit-user-dialog').unbind('click');
                    dialog.unbind('hidden.bs.modal');
                });
            };

            this.expandAllGrants = function () {
                for (var i = 0; i < this.users().length; i++) {
                    this.users()[i].expandGrants();
                }
            };

            this.collapseAllGrants = function () {
                for (var i = 0; i < this.users().length; i++) {
                    this.users()[i].collapseGrants();
                }
            };

            this.invokeAddUserDialog = function () {
                var self = this;
                var dialog = $("#pg-admin-create-user-dialog");
                var confirmedPasswordErrorControl = $("#newuser-confirmed-password-error");

                self.newUser.id('');
                self.newUser.name('New user');
                self.newUser.password('');
                self.newUser.confirmedPassword('');
                confirmedPasswordErrorControl.hide();
                self.newUser.email('');

                $('#save-create-user-dialog').bind('click', function (e) {
                    e.preventDefault();

                    if (!PhpGenPasswordDialogUtils.validateConfirmedPassword(
                            self.newUser.password(), self.newUser.confirmedPassword(),
                            confirmedPasswordErrorControl)) {
                        return;
                    }

                    if (self.emailBasedFeaturesEnabled) {
                        self.api.addUserEx(self.newUser.name(), self.newUser.password(), self.newUser.email())
                            .fail(function (message) {
                                utils.showErrorMessage(message);
                            })
                            .done(function (result) {
                                dialog.modal('hide');
                                self.users.push(
                                    new PhpGenAdmin.UserViewModel(
                                        self.api,
                                        result.id,
                                        result.name,
                                        result.password,
                                        result.email,
                                        0,
                                        true
                                    ));
                            });
                    } else {
                        self.api.addUser(self.newUser.name(), self.newUser.password())
                            .fail(function (message) {
                                utils.showErrorMessage(message);
                            })
                            .done(function (result) {
                                dialog.modal('hide');
                                self.users.push(
                                    new PhpGenAdmin.UserViewModel(
                                        self.api,
                                        result.id,
                                        result.name,
                                        result.password,
                                        '', 0,
                                        true
                                    ));
                            });
                    }
                });

                dialog.modal('show');
                $('#newuser-id').focus();

                dialog.bind('hidden.bs.modal', function () {
                    $('#save-create-user-dialog').unbind('click');
                    dialog.unbind('hidden.bs.modal');
                });
            };

            this.users = ko.observableArray([]);

            this.usersOnCurrentPage = ko.dependentObservable(function () {
                return this.users.slice(0, this.users().length).sort(function (a, b) {
                    if (a.id === -1 || a.id === 0) {
                        if (b.id === 0) {
                            return 1;
                        }

                        return -1;
                    }

                    if (a.name() < b.name()) {
                        return -1;
                    } else if (a.name() > b.name()) {
                        return 1
                    }

                    return 0;
                });
            }, this);
        };

        var api = PhpGenUserManagementApi;
        window.PhpGenAdmin.adminPanelViewModel = new PhpGenAdmin.AdminPanelViewModel(api);
        PhpGenAdmin.adminPanelViewModel.emailBasedFeaturesEnabled = PhpGenAdmin.EmailBasedFeaturesEnabled;

        var i;
        for (i = 0; i < PhpGenAdmin.CurrentUsers.length; i++)
            PhpGenAdmin.adminPanelViewModel.users.push(
                new PhpGenAdmin.UserViewModel(
                    api,
                    PhpGenAdmin.CurrentUsers[i].id,
                    PhpGenAdmin.CurrentUsers[i].name,
                    PhpGenAdmin.CurrentUsers[i].password,
                    PhpGenAdmin.CurrentUsers[i].email,
                    PhpGenAdmin.CurrentUsers[i].status,
                    PhpGenAdmin.CurrentUsers[i].editable
                ));

        ko.applyBindings(PhpGenAdmin.adminPanelViewModel);

        PhpGenChangePasswordDialog.initialize(new PhpGenChangePasswordDialogAdminStrategy(
            function (newPassword) {
                return api.changeUserPassword(PhpGenAdmin.adminPanelViewModel.changePasswordUser.id, newPassword);
            }));

        $("#pg-admin-create-user-dialog").modal({show: false});
        $("#pg-admin-edit-user-dialog").modal({show: false});

    };

});
