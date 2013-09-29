{if !$DateTimeEdit->GetReadOnly()}
{if $RenderText}
<span>
    <div class="input-append pgui-datetime-editor">
    <input
        data-editor="true"
        data-editor-class="DateTimeEdit"
        data-field-name="{$DateTimeEdit->GetFieldName()}"
        data-editable="true"

        data-picker-format="{$DateTimeEdit->GetFormat()}"
        data-picker-show-time="{if $DateTimeEdit->GetShowsTime()}true{else}false{/if}"
        data-picker-first-day-of-week="{$DateTimeEdit->GetFirstDayOfWeek()}"

        type="text"
        {style_block}
            {$DateTimeEdit->GetCustomAttributes()}
        {/style_block}
        class="pgui-date-time-edit"
        name="{$DateTimeEdit->GetName()}"
        id="{$DateTimeEdit->GetName()}"
        value="{$DateTimeEdit->GetValue()}"
        {$Validators.InputAttributes}><button class="btn pgui-date-time-edit-picker" id="{$DateTimeEdit->GetName()}_trigger" onclick="return false;"><i class="pg-icon-datetime-picker"></i></button>
    </div>
</span>
{/if}
{if $RenderScripts}
{if $RenderText}
<script type="text/javascript">
{/if}
{if $RenderText}
</script>
{/if}
{/if}
{else}
{if $RenderText}
<span
    data-editor="true"
    data-editor-class="DateTimeEdit"
    data-field-name="{$DateTimeEdit->GetFieldName()}"
    data-editable="false"
    >{$DateTimeEdit->GetValue()}</span>
{/if}
{/if}