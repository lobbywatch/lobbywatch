{if $ReCaptcha}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    {if $ReCaptcha->isInvisibleCaptcha()}
        <script>
            {literal}
            function onReCaptchaFormSubmit(token) {
                $('#submit-recaptcha').hide();
                $('#submit-form').show().click();
            }
            function onReCaptchaExpired() {
                $('#submit-recaptcha').show();
                $('#submit-form').hide();
            }
            {/literal}
        </script>
    {/if}
{/if}
