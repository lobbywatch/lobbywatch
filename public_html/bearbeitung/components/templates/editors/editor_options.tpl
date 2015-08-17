id="{$Editor->GetName()}"
name="{$Editor->GetName()}{if $Multiple}[]{/if}"
data-editor="true"
data-editor-class="{$Editor->GetDataEditorClassName()}"
data-field-name="{$Editor->GetFieldName()}"
{if not $Editor->getEnabled()}
    disabled="disabled"
{/if}
{if not $Editor->getVisible()}
    data-editor-visible="false"
{/if}
{if $Editor->GetReadonly()}
    readonly="readonly"
{/if}
{if $Editor->getCustomAttributes()}
    {$Editor->getCustomAttributes()}
{/if}
{style_block}
    {$Editor->getInlineStyles()}
{/style_block}
{$Validators.InputAttributes}