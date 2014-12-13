<input
    {include file="editors/editor_options.tpl" Editor=$SpinEdit}
    type="number"
    value="{$SpinEdit->GetValue()}"
    {if $SpinEdit->GetUseConstraints()}
        min="{$SpinEdit->GetMinValue()}"
        max="{$SpinEdit->GetMaxValue()}"
    {/if}
    {if $SpinEdit->GetStep() != 1}
        step="{$SpinEdit->GetStep()}"
    {/if}
>