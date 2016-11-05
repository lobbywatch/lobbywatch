<input
    {include file="editors/editor_options.tpl" Editor=$Editor}
    type="number"
    class="form-control"
    value="{$Editor->GetValue()}"
    {if $Editor->GetUseConstraints()}
        min="{$Editor->GetMinValue()}"
        max="{$Editor->GetMaxValue()}"
    {/if}
    {if $Editor->GetStep() != 1}
        step="{$Editor->GetStep()}"
    {/if}
>