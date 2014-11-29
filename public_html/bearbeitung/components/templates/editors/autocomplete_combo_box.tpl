<select
    {include file="editors/editor_options.tpl" Editor=$AutocompleteComboBox}
    data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
    search
    autocomplete="true"
    data-url="{$AutocompleteComboBox->GetDataUrl()}"
    {if $AutocompleteComboBox->GetReadonly()}disabled="disabled"{/if}
    ><option value="{$AutocompleteComboBox->GetValue()}">{$AutocompleteComboBox->GetDisplayValue()}</option>
</select>
