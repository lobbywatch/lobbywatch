{strip}
<div
    {n}{if isset($id)}id="{$id}"{/if}
    {n}data-editor="{$Editor->getEditorName()}"
    {n}data-editor-name="{$Editor->GetName()}"
    {n}data-field-name="{$Editor->GetFieldName()}"
    {n}data-inline="{$Editor->IsInlineMode()}"
    {n}class="input-group"
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
            <label class="checkbox-inline js-value">
        {else}
            <div class="checkbox js-value">
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

    {if !isset($isSingleFieldForm) && isset($ColumnViewData.NestedInsertFormLink) && isset($ColumnViewData.LinkFieldName) && isset($ColumnViewData.DisplayFieldName)} 
        <a
            {n}href="#"
            {n}class="checkbox-group js-nested-insert"
            {n}data-content-link="{$ColumnViewData.NestedInsertFormLink}"
            {n}data-stored-field-name="{$ColumnViewData.LinkFieldName}"
            {n}data-display-field-name="{$ColumnViewData.DisplayFieldName}"
            {n}title="{$Captions->GetMessageString('InsertItem')}">
            <span class="icon-plus"></span> {$Captions->GetMessageString('InsertItem')}
        </a>
    {/if}
</div>
{/strip}
