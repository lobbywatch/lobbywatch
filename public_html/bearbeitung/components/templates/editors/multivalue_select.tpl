<select class="form-control" multiple
    {include file="editors/editor_options.tpl" Editor=$Editor Multiple=true}
    data-max-selection-size="{$Editor->getMaxSelectionSize()}">
    {foreach key=value item=displayValue from=$Editor->getChoices()}
        <option value="{$value}"{if $Editor->hasValue($value)} selected{/if}>{$displayValue}</option>
    {/foreach}
</select>