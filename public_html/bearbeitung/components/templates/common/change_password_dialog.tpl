<div id="pg-change-password-dialog" class="modal hide">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
        <h3 id="pg-change-password-dialog-header-user" class="hide">{$Captions->GetMessageString('ChangeYourPassword')}</h3>
        <h3 id="pg-change-password-dialog-header-admin" class="hide">{$Captions->GetMessageString('ChangePasswordForUser')} '<span data-bind="text: changePasswordUser.name"></span>'</h3>
    </div>

    <div class="modal-body">

        <form class="form-horizontal">
            <fieldset>
                <div class="control-group hide" id="pg-change-password-dialog-current-password-control-group">
                    <label class="control-label" for="pg-change-password-dialog-current-password">{$Captions->GetMessageString('CurrentPassword')}</label>
                    <div class="controls">
                        <input id="pg-change-password-dialog-current-password"
                               type="password"
                               name="currentPassword"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="pg-change-password-dialog-new-password">{$Captions->GetMessageString('NewPassword')}</label>
                    <div class="controls">
                        <input id="pg-change-password-dialog-new-password"
                               type="password"
                               name="newPassword"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="pg-change-password-dialog-confirmed-password">{$Captions->GetMessageString('ConfirmPassword')}</label>
                    <div class="controls">
                        <input id="pg-change-password-dialog-confirmed-password"
                               type="password"
                               name="confirmedPassword"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="alert alert-error" id="pg-change-password-dialog-confirmed-password-error">
                        <p>{$Captions->GetMessageString('ConfirmedPasswordError')}</p>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">{$Captions->GetMessageString('Close')}</a>
        <a href="#" class="btn btn-primary" id="pg-change-password-dialog-ok-button">{$Captions->GetMessageString('ChangePassword')}</a>
    </div>
</div>
