{if !$ComboBox->GetReadOnly()}
{if $RenderText}
    <select data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
            data-editor="true"
            data-editor-class="Autocomplete"
            data-editable="true"
            data-field-name="{$ComboBox->GetFieldName()}"
            name="{$ComboBox->GetName()}"
            search
            autocomplete="true"
            data-url="{$ComboBox->GetDataUrl()}"
            {$Validators.InputAttributes}
            ><option value="{$ComboBox->GetValue()}">{$ComboBox->GetDisplayValue()}</option></select>
{/if}
{else}
{$ComboBox->GetDisplayValue()}
{/if}
