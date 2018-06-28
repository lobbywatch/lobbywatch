{if !$isMultiEditOperation}
<div class="pgui-field-options btn-group btn-group-xs btn-group-justified" data-toggle="buttons">
    {if $Column.DisplaySetToNullCheckBox}
        <label class="btn btn-default{if $Column.IsValueNull} active{/if}">
            <input type="checkbox" name="{$Column.SetNullCheckBoxName}"{if $Column.IsValueNull} checked{/if} autocomplete="off" value="1"> {$Captions->GetMessageString('SetToNull')}
        </label>
    {/if}

    {if $Column.DisplaySetToDefaultCheckBox}
        <label class="btn btn-default">
            <input type="checkbox" name="{$Column.SetDefaultCheckBoxName}" autocomplete="off" value="1"> {$Captions->GetMessageString('SetToDefault')}
        </label>
    {/if}
</div>
{/if}