{if not $Editor.ReadOnly}
    <input
        id="{$Editor.Name}"
        name="{$Editor.Name}"
        value="{$Editor.DisplayValue}"
        class="input-xlarge"
        {$Editor.ControllerAttributes}
        {$Editor.AdditionalAttributes}
        {$Validators.InputAttributes}
        {style_block}
            {$Editor.CustomStyle}
            width: auto;
        {/style_block}
    >
{else}
    {if not $Editor.PasswordMode}
        <span {$Editor.ControllerAttributes}>{$Editor.Value}</span>
    {else}
        <span {$Editor.ControllerAttributes}>*************</span>
    {/if}
{/if}
