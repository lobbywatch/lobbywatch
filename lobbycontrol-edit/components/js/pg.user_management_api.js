var PhpGenUserManagementApi = {
    _invokeApiFunction:function (functionShortName, data) {
        var result = new $.Deferred();

        $.get(
                'user_management_api.php',
                $.extend({},
                    { 'hname':functionShortName },
                    data))
            .success(
            function (data) {
                if (data.status == 'error')
                    result.reject(data.result);
                else {
                    if (data.result)
                        result.resolve(data.result);
                    else
                        result.resolve();
                }
            })
            .error(
            function (xhr, status, errorMessage) {
                result.reject(status + ': ' + errorMessage);
            });

        return result.promise();
    },

    addUser:function (id, name, password) {
        return this._invokeApiFunction('au',
            {
                'id':id,
                'username':name,
                'password':password
            });
    },

    removeUser:function (id) {
        return this._invokeApiFunction('ru',
            {
                'user_id':id
            });
    },

    changeUserName:function (id, username) {
        return this._invokeApiFunction('eu',
            {
                'user_id':id,
                'username':username
            });
    },

    changeUserPassword:function (id, password) {
        return this._invokeApiFunction('cup',
            {
                'user_id':id,
                'password':password
            });
    },

    getUserGrants:function (userId) {
        return this._invokeApiFunction('gug',
            {
                'user_id':userId
            });
    },

    addUserGrant:function (userId, pageName, grantName) {
        return this._invokeApiFunction('aug',
            {
                'user_id':userId,
                'page_name':pageName,
                'grant':grantName
            });
    },

    removeUserGrant:function (userId, pageName, grantName) {
        return this._invokeApiFunction('rug',
            {
                'user_id':userId,
                'page_name':pageName,
                'grant':grantName
            });
    },

    selfChangePassword: function (currentPassword, newPassword) {
        return this._invokeApiFunction('self_change_password',
            {
                'current_password':currentPassword,
                'new_password':newPassword
            });
    }
};

