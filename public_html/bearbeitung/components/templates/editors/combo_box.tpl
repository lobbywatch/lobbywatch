<select
    {include file="editors/editor_options.tpl" Editor=$ComboBox}>
    {if $ComboBox->ShowEmptyValue()}
        <option value="">{$ComboBox->GetEmptyValue()}</option>
    {/if}
    {if $ComboBox->HasMFUValues()}
        {foreach key=Value item=Name from=$ComboBox->GetMFUValues()}
            <option value="{$Value}">{$Name}</option>
        {/foreach}
        <option value="----------" disabled="disabled">----------</option>
    {/if}
    {foreach key=Value item=Name from=$ComboBox->GetDisplayValues()}
        <option value="{$Value}"{if ($ComboBox->IsSelectedValue($Value))} selected{/if}>{$Name}</option>
    {/foreach}
</select>