<input
    {include file="editors/editor_options.tpl" Editor=$SpinEdit}
    spinedit="true"
    type="number"
    value="{$SpinEdit->GetValue()}"
    {if $SpinEdit->GetUseConstraints()}
        min-value="{$SpinEdit->GetMinValue()}"
        max-value="{$SpinEdit->GetMaxValue()}"
    {/if}
>