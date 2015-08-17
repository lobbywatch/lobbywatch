<input
	type="hidden"
	{include file="editors/editor_options.tpl" Editor=$AutocompleteComboBox}
	data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
	autocomplete="true"
	data-url="{$AutocompleteComboBox->GetDataUrl()}"
	data-minimal-input-length="{$AutocompleteComboBox->getMinimumInputLength()}"
	{if $AutocompleteComboBox->getFormatResult()}
		data-format-result="{$AutocompleteComboBox->getFormatResult()|escape}"
	{/if}
	{if $AutocompleteComboBox->getFormatSelection()}
		data-format-selection="{$AutocompleteComboBox->getFormatSelection()|escape}"
	{/if}
	{if $AutocompleteComboBox->GetReadonly()}readonly="readonly"{/if}
	{if $AutocompleteComboBox->getAllowClear()}data-allowClear="true"{/if}
	value="{$AutocompleteComboBox->GetValue()}"
	{style_block}width: {$AutocompleteComboBox->GetSize()}{/style_block}
/>
