{capture assign="ContentBlock"}
<div class="well pgui-login">
    <div class="page-header">
        <h3>{$Captions->GetMessageString('ResetPassword')}</h3>
    </div>
    <div class="js-form-container">
        <div class="js-form-collection">
            <form id="resetPasswordForm" method="post">
                <div class="form-group">
                    <input placeholder="{$Captions->GetMessageString('NewPassword')}" type="password" name="password" class="form-control" id="password"
                           data-editor="text" data-pgui-legacy-validate="true" data-legacy-field-name="password"
                           data-validation="required" data-required-error-message="Password is required">
                </div>

                <div class="form-group">
                    <input placeholder="{$Captions->GetMessageString('ConfirmPassword')}" type="password" name="confirmed-password" class="form-control" id="confirmed-password"
                           data-editor="text" data-pgui-legacy-validate="true" data-legacy-field-name="confirmedpassword"
                           data-validation="required" data-required-error-message="Confirmed password is required">
                </div>

                <div class="form-error-container">
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary js-save" data-action="open" data-url="login.php">{$Captions->GetMessageString('Reset')}</button>
                    &nbsp;<a href="login.php" class="btn btn-default">{$Captions->GetMessageString('Cancel')}</a>
                </div>
            </form>
        </div>
    </div>
</div>
{/capture}

{* Base template *}
{include file=$layoutTemplate}