<select multiple
    {include file="editors/editor_options.tpl" Editor=$MultiValueSelect Multiple=true}
    data-max-selection-size="{$MultiValueSelect->getMaxSelectionSize()}">

    {foreach key=Value item=Name from=$MultiValueSelect->GetValues()}
        <option value="{$Value}"{if ($MultiValueSelect->IsValueSelected($Value))} selected{/if}>{$Name}</option>
    {/foreach}
</select>
