{if $ColumnViewData.NestedInsertFormLink}
<div class="input-group" style="width: 100%">
{/if}

    <select
        class="form-control {if $ColumnViewData.NestedInsertFormLink}form-control-nested-form{/if}"
        {include file="editors/editor_options.tpl" Editor=$Editor}>

        {if $Editor->hasEmptyChoice()}
            <option value="">{$Captions->GetMessageString('PleaseSelect')}</option>
        {/if}

        {if $Editor->HasMFUChoices()}
            {foreach key=value item=displayValue from=$Editor->getMFUChoices()}
                <option value="{$value}">{$displayValue}</option>
            {/foreach}
            <option value="----------" disabled="disabled">----------</option>
        {/if}

        {foreach key=value item=displayValue from=$Editor->getChoices()}
            {if $value !== ''}
                <option value="{$value}"{if $Editor->getValue() == $value} selected{/if}>{$displayValue}</option>
            {/if}
        {/foreach}
    </select>
    {include file='editors/nested_insert_button.tpl' NestedInsertFormLink=$ColumnViewData.NestedInsertFormLink LookupDisplayFieldName=$ColumnViewData.DisplayFieldName}

{if $ColumnViewData.NestedInsertFormLink}
</div>
{/if}
