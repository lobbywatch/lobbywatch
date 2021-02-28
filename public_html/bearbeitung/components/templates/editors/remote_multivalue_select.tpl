<div class="input-group" style="width: 100%;max-width:100%">

{assign var="NestedInsertFormLink" value=$Editor->getNestedInsertFormLink()}
<input
    type="hidden"
    class="form-control{if $NestedInsertFormLink} form-control-nested-form{/if}"
    {include file="editors/editor_options.tpl" Editor=$Editor}
    data-max-selection-size="{$Editor->getMaxSelectionSize()}"
    data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
    data-url="{$Editor->GetDataUrl()}"
    value="{$Editor->GetValue()}"
>

{include file='editors/nested_insert_button.tpl' StoredFieldName=$Editor->getStoredFieldName()}

</div>
