{if not $Editor->GetReadOnly()}
    {if $RenderText}
        <textarea
            data-editor="true"
            data-editor-class="HtmlEditor"
            data-field-name="{$Editor->GetFieldName()}"
            data-editable="true"
            class="html_wysiwyg"
            id="{$Editor->GetName()}"
            name="{$Editor->GetName()}"
            {$Validators.InputAttributes}>
        {$Editor->GetValue()}
        </textarea>
    {/if}
{else}
    {if $RenderText}
        <span
            data-editor="true"
            data-editor-class="HtmlEditor"
            data-field-name="{$Editor->GetFieldName()}"
            data-editable="false">
        {$Editor->GetValue()}
        </span>
    {/if}
{/if}