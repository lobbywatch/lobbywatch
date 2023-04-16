<input
    {include file="editors/editor_options.tpl" Editor=$Editor}
    type="checkbox"
    value="on"
    class = "pgui-toggle-checkbox"
    {if $Editor->isChecked()}
        checked="checked"
    {/if}
    {if $Editor->GetReadonly()}
        onClick="return false"
    {/if}
    data-is-toggle="true"
    data-toggle-on-caption="{$Editor->getOnToggleCaption()}"
    data-toggle-off-caption="{$Editor->getOffToggleCaption()}"
    data-toggle-on-style="{$Editor->getOnToggleStyle()}"
    data-toggle-off-style="{$Editor->getOffToggleStyle()}"
    data-toggle-size="{$Editor->getToggleSize()}"
>
