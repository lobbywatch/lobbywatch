{if !$SpinEdit->GetReadOnly()}
<input
    data-editor="true"
    data-editor-class="SpinEdit"
    data-field-name="{$SpinEdit->GetFieldName()}"
    data-editable="true"
    {style_block}
        {$SpinEdit->GetCustomAttributes()}
    {/style_block}
    spinedit="true"
    type="number"
    id="{$SpinEdit->GetName()}"
    name="{$SpinEdit->GetName()}"
    value="{$SpinEdit->GetValue()}"
    {if $SpinEdit->GetUseConstraints()}
        min-value="{$SpinEdit->GetMinValue()}"
        max-value="{$SpinEdit->GetMaxValue()}"
    {/if}
    {$Validators.InputAttributes}
>
{else}
<span
    data-editor="true"
    data-editor-class="SpinEdit"
    data-field-name="{$SpinEdit->GetFieldName()}"
    data-editable="false"
    >
{$SpinEdit->GetValue()}
</span>

{/if}