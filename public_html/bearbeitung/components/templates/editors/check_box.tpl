<input
    {include file="editors/editor_options.tpl" Editor=$CheckBox}
    type="checkbox"
    value="on"
    {if $CheckBox->Checked()}
        checked="checked"
    {/if}
    {if $CheckBox->GetReadonly()}
        onClick="return false"
    {/if}
>