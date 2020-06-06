{capture assign="ContentBlock"}
<div class="well pgui-login">
    <div class="page-header">
        <h3>{$Captions->GetMessageString('ResendVerificationEmail')}</h3>
    </div>
    <div class="js-form-container">
        <div class="js-form-collection">
            <form id="recoveringPasswordForm" method="post">
                <div class="alert alert-info">
                    {$Captions->GetMessageString('ResendVerificationEmailPageInfoMessage')}
                </div>

                <div class="form-group">
                    <input required="true" placeholder="{$Captions->GetMessageString('Email')}" type="text" name="email" class="form-control" id="email" data-field-name="email"
                           data-validation="required email" data-required-error-message="Email is required" data-email-error-message="Please enter a valid email address">
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
                        <button id="submit-recaptcha" class="btn btn-primary js-recaptcha g-recaptcha" data-sitekey="{$ReCaptcha->getSiteKey()}" data-callback='onReCaptchaFormSubmit' data-expired-callback='onReCaptchaExpired'{if $ReCaptcha->getUseDarkColorTheme()} data-theme="dark"{/if}>{$Captions->GetMessageString('Resend')}</button>
                        <button id="submit-form" class="btn btn-primary js-save" data-action="open" data-url="login.php" style="display: none">{$Captions->GetMessageString('Resend')}</button>
                    {else}
                        <button class="btn btn-primary js-save" data-action="open" data-url="login.php">{$Captions->GetMessageString('Resend')}</button>
                    {/if}
                    &nbsp;<a href="login.php" class="btn btn-default">{$Captions->GetMessageString('Cancel')}</a>
                </div>
            </form>
        </div>
    </div>
</div>
{/capture}

{* Base template *}
{include file=$layoutTemplate}