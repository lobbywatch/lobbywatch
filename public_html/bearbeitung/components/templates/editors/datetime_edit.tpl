<span>
    <div class="input-append pgui-datetime-editor">
        <input
            {include file="editors/editor_options.tpl" Editor=$DateTimeEdit}
            class="pgui-date-time-edit"
            type="text"
            value="{$DateTimeEdit->GetValue()}"
            data-picker-format="{$DateTimeEdit->GetFormat()}"
            data-picker-show-time="{if $DateTimeEdit->GetShowsTime()}true{else}false{/if}"
            data-picker-first-day-of-week="{$DateTimeEdit->GetFirstDayOfWeek()}"
        ><button
            class="btn pgui-date-time-edit-picker"
            id="{$DateTimeEdit->GetName()}_trigger"
            {if $DateTimeEdit->GetReadonly() or !$DateTimeEdit->getEnabled()}
                disabled="disabled"
            {/if}
            onclick="return false;">
            <i class="pg-icon-datetime-picker"></i>
        </button>
    </div>
</span>
