<div class="input-checkbox">
    <input
        {include file="editors/editor_options.tpl" Editor=$Editor}
        type="checkbox"
        value="on"
        {if $Editor->isChecked()}
            checked="checked"
        {/if}
        {if $Editor->GetReadonly()}
            onClick="return false"
        {/if}>
</div>
