{strip}
<div
    {n}id="{$RadioEdit->GetName()}"
    {n}data-editor="true"
    {n}data-editor-class="{$RadioEdit->GetDataEditorClassName()}"
    {n}data-editor-name="{$RadioEdit->GetName()}"
    {n}data-field-name="{$RadioEdit->GetFieldName()}"
    {if not $RadioEdit->getVisible()}
        {n}data-editor-visible="false"
    {/if}
    {if $RadioEdit->getCustomAttributes()}
        {n}{$RadioEdit->getCustomAttributes()}
    {/if}
    {style_block}
        {n}{$RadioEdit->getInlineStyles()}
    {/style_block}>
    {foreach key=Value item=Name from=$RadioEdit->GetValues()}
        <label class="radio{if $RadioEdit->IsInlineMode()} inline{/if}">
            <input
                {n}type="radio"
                {n}name="{$RadioEdit->GetName()}"
                {n}value="{$Value}"
                {if $RadioEdit->GetSelectedValue() eq $Value}
                    {n}checked="checked"
                {/if}
                {if not $RadioEdit->getEnabled()}
                    {n}disabled="disabled"
                {/if}
                {if $RadioEdit->GetReadonly() and $RadioEdit->getEnabled()}
                    {if not ($RadioEdit->GetSelectedValue() eq $Value)}
                        {n}disabled="disabled"
                    {/if}
                {/if}
                {n}{$Validators.InputAttributes}
            />
            {$Name}
        </label>
    {/foreach}
</div>
{/strip}