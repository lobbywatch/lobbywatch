<label class="control-label"{if not $isViewForm} for="{$EditorId}"{/if} data-column="{$ColumnViewData.FieldName}">
    {$Col->getCaption()}

    {if not $isViewForm}
        <span class="required-mark"{if not $ColumnViewData.Required} style="display: none"{/if}>*</span>
        {include file="edit_field_options.tpl" Column=$ColumnViewData}
    {/if}
</label>