<label class="control-label"{if not $isViewForm} for="{$EditorId}"{/if} data-column="{$ColumnViewData.FieldName}">
    {if not $isViewForm && $ColumnViewData.EditorHint}
        <span class="commented js-hint" data-hint="{$ColumnViewData.EditorHint}">{$Col->getCaption()}</span>
    {else}
        {$Col->getCaption()}
    {/if}

    {if not $isViewForm}
        <span class="required-mark"{if not $ColumnViewData.Required} style="display: none"{/if}>*</span>
        {include file="edit_field_options.tpl" Column=$ColumnViewData}
    {/if}
</label>
