{if not $TextArea->GetReadOnly()}
<textarea
    data-editor="true"
    data-editor-class="TextArea"
    data-field-name="{$TextArea->GetFieldName()}"
    data-editable="true"
    class="input-xxlarge"
    {style_block}
        {$TextArea->GetCustomAttributes()}
    {/style_block}
    id="{$TextArea->GetName()}"
    name="{$TextArea->GetName()}"
    {if $TextArea->GetColumnCount() != null}
    cols="{$TextArea->GetColumnCount()}"{/if}
    {if $TextArea->GetRowCount() != null}
    rows="{$TextArea->GetRowCount()}"{/if}
    {$Validators.InputAttributes}>{$TextArea->GetValue()}</textarea>
{else}
<span
    data-editor="true"
    data-editor-class="TextArea"
    data-field-name="{$TextArea->GetFieldName()}"
    data-editable="false">{$TextArea->GetValue()}
</span>
{/if}