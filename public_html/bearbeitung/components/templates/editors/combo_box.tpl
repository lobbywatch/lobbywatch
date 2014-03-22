{if $RenderText}
    {if !$ComboBox->GetReadOnly()}
        <select
        data-editor="true"
        data-editor-class="ComboBox"
        data-field-name="{$ComboBox->GetFieldName()}"
        data-editable="true"
        {style_block}
        {$ComboBox->GetCustomAttributes()}
        {/style_block}
        id="{$ComboBox->GetName()}" name="{$ComboBox->GetName()}" {$Validators.InputAttributes}>
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
    {else}
        {foreach key=Value item=Name from=$ComboBox->GetValues()}
            {if ($ComboBox->IsSelectedValue($Value))}
                <span
                        data-editor="true"
                        data-editor-class="ComboBox"
                        data-field-name="{$ComboBox->GetFieldName()}"
                        data-editable="false"
                        >{$Name}</span>
            {/if}
        {/foreach}
    {/if}
{/if}