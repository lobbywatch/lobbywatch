{if $RenderText}
{if !$CheckBox->GetReadOnly()}
<input
    data-editor="true"
    data-editor-class="CheckBox"
    data-editable="true"
    data-field-name="{$CheckBox->GetFieldName()}"
    {style_block}
        {$CheckBox->GetCustomAttributes()}
    {/style_block}
    type="checkbox"
    name="{$CheckBox->GetName()}"
    id="{$CheckBox->GetName()}"
    value="on" {if $CheckBox->Checked()}
    checked="checked"{/if}
    {$Validators.InputAttributes}>
{else}
{if $CheckBox->Checked()}
<img
    data-editor="true"
    data-editor-class="CheckBox"
    data-field-name="{$TextEdit->GetFieldName()}"
    data-editable="false"
    data-value="1"
    src="images/checked.png" />
{else}
<span
    data-editor="true"
    data-editor-class="CheckBox"
    data-field-name="{$CheckBox->GetFieldName()}"
    data-editable="false"
    data-value="0"></span>
{/if}
{/if}
{/if}