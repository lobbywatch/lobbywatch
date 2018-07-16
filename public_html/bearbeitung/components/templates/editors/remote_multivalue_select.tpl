<div class="input-group" style="width: 100%">
    <input
        type="hidden"
        class="form-control"
        {include file="editors/editor_options.tpl" Editor=$Editor}
        data-max-selection-size="{$Editor->getMaxSelectionSize()}"
        data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
        data-url="{$Editor->GetDataUrl()}"
        value="{$Editor->GetValue()}"
    />
</div>
