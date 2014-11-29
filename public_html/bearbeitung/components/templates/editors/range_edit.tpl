<input
    {include file="editors/editor_options.tpl" Editor=$RangeEdit}
    type="range"
    value="{$RangeEdit->GetValue()}"
    {if $RangeEdit->GetUseConstraints()}
        min="{$RangeEdit->GetMinValue()}"
        max="{$RangeEdit->GetMaxValue()}"
    {/if}
>