{strip}
<div
    {n}{if $id}id="{$id}"{/if}
    {n}data-editor="{$Editor->getEditorName()}"
    {n}data-editor-name="{$Editor->GetName()}"
    {n}data-field-name="{$Editor->GetFieldName()}"
    {n}data-inline="{$Editor->IsInlineMode()}"
    {if not $Editor->getVisible()}
        {n}data-editor-visible="false"
    {/if}
    {if $Editor->getCustomAttributes()}
        {n}{$Editor->getCustomAttributes()}
    {/if}
    {n}{$ViewData.Validators.InputAttributes}
    {n}{style_block}
        {$Editor->getInlineStyles()}
    {/style_block}
>
    {foreach key=value item=displayValue from=$Editor->getChoices()}
        {if $Editor->IsInlineMode()}
            <label class="checkbox-inline">
        {else}
            <div class="checkbox">
                <label>
        {/if}
                <input
                    {n}type="checkbox"
                    {n}name="{$Editor->GetName()}[]"
                    {n}value="{$value}"
                    {if $Editor->hasValue($value)}
                        {n}checked="checked"
                    {/if}
                    {if not $Editor->getEnabled()}
                        {n}disabled="disabled"
                    {/if}
                    {if $Editor->GetReadonly()}
                        {n}readonly="readonly"
                        {n}onClick="return false"
                    {/if}
                />
                {$displayValue}
            </label>
        {if not $Editor->IsInlineMode()}
        </div>
        {/if}
    {/foreach}
</div>
{/strip}