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

                <div class="form-error-container">
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary js-save" data-action="open" data-url="login.php">{$Captions->GetMessageString('Resend')}</button>
                    &nbsp;<a href="login.php" class="btn btn-default">{$Captions->GetMessageString('Cancel')}</a>
                </div>
            </form>
        </div>
    </div>
</div>
{/capture}

{* Base template *}
{include file=$layoutTemplate}