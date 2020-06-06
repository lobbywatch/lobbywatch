<div class="well pgui-login">

    <p class="text-center"><img class="pgui-login-avatar" src="components/assets/img/login_avatar.png" alt="User avatar" /></p>

    {if !is_null($SecurityFeedbackPositive)}
    <div class="alert alert-success">
        <button data-dismiss="alert" class="close" type="button">&times;</button>
        {$SecurityFeedbackPositive}
    </div>
    {/if}
    {if !is_null($SecurityFeedbackNegative)}
    <div class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button">&times;</button>
        {$SecurityFeedbackNegative}
    </div>
    {/if}

    <form method="post">
        <div class="form-group">
            <input placeholder="{$Captions->GetMessageString('Username')}" type="text" name="username" class="form-control" id="username">
        </div>

        <div class="form-group">
            <input placeholder="{$Captions->GetMessageString('Password')}" type="password" name="password" class="form-control" id="password">
        </div>

        {if $ReCaptcha && $ReCaptcha->isCheckboxCaptcha()}
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="{$ReCaptcha->getSiteKey()}"{if $ReCaptcha->getUseDarkColorTheme()} data-theme="dark"{/if}></div>
            </div>
        {/if}

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="saveidentity" id="saveidentity" {if $LoginControl->GetLastSaveidentity()} checked="checked"{/if}>
                    {$Captions->GetMessageString('RememberMe')}
                </label>
            </div>
        </div>

        {if $LoginControl->GetErrorMessage() != '' }
            <div class="alert alert-danger">
                {$LoginControl->GetErrorMessage()}
            </div>
        {/if}

        <div class="form-group text-center">
            {if $ReCaptcha && $ReCaptcha->isInvisibleCaptcha()}
                <button id="submit-recaptcha" class="btn btn-primary g-recaptcha" data-sitekey="{$ReCaptcha->getSiteKey()}" data-callback='onReCaptchaFormSubmit' data-expired-callback='onReCaptchaExpired'{if $ReCaptcha->getUseDarkColorTheme()} data-theme="dark"{/if}>{$Captions->GetMessageString('Login')}</button>
                <button id="submit-form" class="btn btn-primary" type="submit" style="display: none">{$Captions->GetMessageString('Login')}</button>
            {else}
                <button class="btn btn-primary" type="submit">{$Captions->GetMessageString('Login')}</button>
            {/if}
            {if $LoginControl->CanLoginAsGuest()}
                &nbsp;<a href="{$LoginControl->GetLoginAsGuestLink()|escapeurl}" class="btn btn-default">{$Captions->GetMessageString('LoginAsGuest')}</a>
            {/if}
        </div>

    </form>

    {if $LoginControl->getEmailBasedFeaturesEnabled()}
    <div class="pgui-login-footer">
        {if $LoginControl->getRecoveringPasswordEnabled()}
            <p class="text-center">
                {$Captions->GetMessageString('ForgotPassword')}
            </p>
        {/if}
        {if $LoginControl->getSelfRegistrationEnabled()}
            <p class="text-center">
                {$Captions->GetMessageString('RegisterHere')}
            </p>
            <p class="text-center">
                {$Captions->GetMessageString('ResendVerificationEmailHere')}
            </p>
        {/if}
    </div>
    {/if}

</div>