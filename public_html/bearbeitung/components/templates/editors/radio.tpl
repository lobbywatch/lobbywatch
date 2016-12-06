{strip}
<div
    {n}{if $id}id="{$id}"{/if}
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
    {style_block}
        {n}{$Editor->getInlineStyles()}
    {/style_block}
>
    {foreach key=value item=displayValue from=$Editor->getChoices()}
        {if $Editor->IsInlineMode()}
            <label class="radio-inline js-value">
        {else}
            <div class="radio js-value">
                <label>
        {/if}

            <input
                {n}type="radio"
                {n}name="{$Editor->GetName()}"
                {n}value="{$value}"
                {if $Editor->getValue() == $value}
                    {n}checked="checked"
                {/if}
                {if not $Editor->getEnabled()}
                    {n}disabled="disabled"
                {/if}
                {if $Editor->GetReadonly() and $Editor->getEnabled()}
                    {if $Editor->getValue() !== $value}
                        {n}disabled="disabled"
                    {/if}
                {/if}
            />
                {$displayValue}

            </label>

        {if not $Editor->IsInlineMode()}
            </div>
        {/if}
    {/foreach}

    {if $ColumnViewData.NestedInsertFormLink}
        <a
            {n}href="#"
            {n}class="radio js-nested-insert"
            {n}data-content-link="{$ColumnViewData.NestedInsertFormLink}"
            {n}data-display-field-name="{$ColumnViewData.DisplayFieldName}"
            {n}title="{$Captions->GetMessageString('InsertRecord')}">
            <span class="icon-plus"></span> {$Captions->GetMessageString('InsertRecord')}
        </a>
    {/if}
</div>
{/strip}