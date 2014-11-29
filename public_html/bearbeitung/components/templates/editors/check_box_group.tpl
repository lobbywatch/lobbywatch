{strip}
<div
    {n}data-editor="true"
    {n}data-editor-class="{$CheckBoxGroup->GetDataEditorClassName()}"
    {n}data-editor-name="{$CheckBoxGroup->GetName()}"
    {n}data-field-name="{$CheckBoxGroup->GetFieldName()}"
    {if not $CheckBoxGroup->getVisible()}
        {n}data-editor-visible="false"
    {/if}
    {n}xmlns="http://www.w3.org/1999/html">
    {if $CheckBoxGroup->getCustomAttributes()}
        {n}{$CheckBoxGroup->getCustomAttributes()}
    {/if}
    {style_block}
        {n}{$CheckBoxGroup->getInlineStyles()}
    {/style_block}
    {foreach key=Value item=Name from=$CheckBoxGroup->GetValues()}
        <label class="checkbox{if $CheckBoxGroup->IsInlineMode()} inline{/if}">
            <input
                {n}type="checkbox"
                {n}name="{$CheckBoxGroup->GetName()}[]"
                {n}value="{$Value}"
                {if $CheckBoxGroup->IsValueSelected($Value)}
                    {n}checked="checked"
                {/if}
                {if not $CheckBoxGroup->getEnabled()}
                    {n}disabled="disabled"
                {/if}
                {if $CheckBoxGroup->GetReadonly()}
                    {n}readonly="readonly"
                    {n}onClick="return false"
                {/if}
                {n}{$Validators.InputAttributes}
            />
            {$Name}
        </label>
    {/foreach}
</div>
{/strip}