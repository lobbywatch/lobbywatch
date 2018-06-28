<div class="input-group" style="width: 100%">
	<input
		type="hidden"
		class="form-control{if $ColumnViewData.NestedInsertFormLink} form-control-nested-form{/if}"
		{include file="editors/editor_options.tpl" Editor=$Editor}
		data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
		data-url="{$Editor->GetDataUrl()}"
		data-minimal-input-length="{$Editor->getMinimumInputLength()}"
		{if $Editor->getFormatResult()}
			data-format-result="{$Editor->getFormatResult()|escape}"
		{/if}
		{if $Editor->getFormatSelection()}
			data-format-selection="{$Editor->getFormatSelection()|escape}"
		{/if}
		{if $Editor->GetReadonly()}readonly="readonly"{/if}
		{if $Editor->getAllowClear()}data-allowClear="true"{/if}
		value="{$Editor->GetValue()}"
	/>
	{include file='editors/nested_insert_button.tpl' NestedInsertFormLink=$ColumnViewData.NestedInsertFormLink LookupDisplayFieldName=$ColumnViewData.DisplayFieldName}
</div>