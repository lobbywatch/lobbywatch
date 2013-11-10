{strip}
<div
            {n}data-editor="true"
            {n}data-editor-class="RadioGroup"
            {n}data-editor-name="{$RadioEdit->GetName()}"
            {n}data-field-name="{$RadioEdit->GetFieldName()}"
            {n}data-editable="{if $RadioEdit->GetReadOnly()}false{else}true{/if}"
            {n}id="{$RadioEdit->GetName()}"
    {style_block}
        {$RadioEdit->GetCustomAttributes()}
    {/style_block}>
    {if !$RadioEdit->GetReadOnly()}
        {foreach key=Value item=Name from=$RadioEdit->GetValues()}
            <label class="radio{if $RadioEdit->IsInlineMode()} inline{/if}">
                <input
                            {n}name="{$RadioEdit->GetName()}"
                            {n}value="{$Value}"{if $RadioEdit->GetSelectedValue() eq $Value} checked="checked"{/if}
                            {n}type="radio" {$Validators.InputAttributes}/>
                {$Name}
            </label>
        {/foreach}
        {else}
        {foreach key=Value item=Name from=$RadioEdit->GetValues()}
            {if $RadioEdit->GetSelectedValue() eq $Value}{$Name}{/if}
        {/foreach}
    {/if}
</div>
{/strip}
