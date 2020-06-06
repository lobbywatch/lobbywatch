{capture assign="ContentBlock"}
<div class="well pgui-login">
    <div class="page-header">
        <h3>{$Captions->GetMessageString('PasswordRecovery')}</h3>
    </div>
    <div class="js-form-container">
        <div class="js-form-collection">
            <form id="recoveringPasswordForm" method="post">
                <div class="alert alert-info">
                    {$Captions->GetMessageString('RecoveringPasswordInfo')}
                </div>

                <div class="form-group">
                    <input required="true" placeholder="{$Captions->GetMessageString('UsernameOrEmail')}" type="text" name="account-name" class="form-control" id="account-name"
                           data-validation="required" data-required-error-message="Username is required">
                </div>

                {if $ReCaptcha && $ReCaptcha->isCheckboxCaptcha()}
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{$ReCaptcha->getSiteKey()}"{if $ReCaptcha->getUseDarkColorTheme()} data-theme="dark"{/if}></div>
                    </div>
                {/if}

                <div class="form-error-container">
                </div>

                <div class="form-group text-center">
                    {if $ReCaptcha && $ReCaptcha->isInvisibleCaptcha()}
                        <button id="submit-recaptcha" class="btn btn-primary js-recaptcha g-recaptcha" data-sitekey="{$ReCaptcha->getSiteKey()}" data-callback='onReCaptchaFormSubmit' data-expired-callback='onReCaptchaExpired' {if $ReCaptcha->getUseDarkColorTheme()} data-theme="dark"{/if}>{$Captions->GetMessageString('SendPasswordResetLink')}</button>
                        <button id="submit-form" class="btn btn-primary js-save" data-action="open" data-url="login.php" style="display: none">{$Captions->GetMessageString('SendPasswordResetLink')}</button>
                    {else}
                        <button class="btn btn-primary js-save" data-action="open" data-url="login.php">{$Captions->GetMessageString('SendPasswordResetLink')}</button>
                    {/if}
                    &nbsp;
                    <a href="login.php" class="btn btn-default">{$Captions->GetMessageString('Cancel')}</a>
                </div>
            </form>
        </div>
    </div>
</div>
{/capture}

{* Base template *}
{include file=$layoutTemplate}