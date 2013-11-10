<div class="well">
    <form method="post" action="login.php" class="pgui-login-form">

        <fieldset>
            <div class="control-group">
                <label class="control-label" for="username">{$Captions->GetMessageString('Username')}</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" name="username" id="username">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="password">{$Captions->GetMessageString('Password')}</label>
                <div class="controls">
                    <input type="password" class="input-xlarge" name="password" id="password">
                </div>
            </div>

            <div class="control-group">
                <label class="checkbox" >
                    <input type="checkbox" name="saveidentity" id="saveidentity" {if $LoginControl->GetLastSaveidentity()} checked="checked"{/if}>
                    {$Captions->GetMessageString('RememberMe')}
                </label>
            </div>
        </fieldset>

        {if $LoginControl->GetErrorMessage() != '' }
            <div class="alert alert-error">
                {$LoginControl->GetErrorMessage()}
            </div>
        {/if}

        <div class="form-actions">
            <button class="btn btn-large btn-primary" type="submit">{$Captions->GetMessageString('Login')}</button>
            {if $LoginControl->CanLoginAsGuest()}
            <a href="{$LoginControl->GetLoginAsGuestLink()|escapeurl}" class="btn btn-large">Login as guest</a>
            {/if}
        </div>

    </form>
</div>