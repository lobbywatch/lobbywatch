<div class="input-group pgui-date-time-edit js-datetime-editor-wrap">
    <input {include file="editors/editor_options.tpl" Editor=$Editor}
            class="form-control"
            type="text"
            value="{$Editor->GetValue()}"
            data-picker-format="{$Editor->GetFormat()}"
            >
    <span class="input-group-addon" style="cursor: pointer">
        <span class="icon-time"></span>
    </span>
</div>
