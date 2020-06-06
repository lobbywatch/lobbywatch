<div class="input-group pgui-date-time-edit js-datetime-editor-wrap">
    <input
        {include file="editors/editor_options.tpl" Editor=$Editor}
        class="form-control"
        type="text"
        value="{$Editor->GetValue()}"
        data-picker-format="{$Editor->GetFormat()}"
        data-picker-show-time="{if $Editor->GetShowsTime()}true{else}false{/if}">
    <span class="input-group-addon" style="cursor: pointer">
        <span class="icon-datetime-picker"></span>
    </span>
</div>
