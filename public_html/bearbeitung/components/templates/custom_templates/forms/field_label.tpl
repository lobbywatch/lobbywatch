<label class="control-label"{if not $isViewForm} for="{$EditorId}"{/if} data-column="{$ColumnViewData.FieldName}">
    {$Col->getCaption()}

    {if not $isViewForm}
        <span class="required-mark"{if not $ColumnViewData.Required} style="display: none"{/if}>*</span>{if $MinimalFields[$ColumnViewData.FieldName]}<span class="minimal-mark">(*)</span>{/if}{if $ImportedFields[$ColumnViewData.FieldName]}<span class="minimal-mark" title="From webservices imported and synchronized field. DO NOT CHANGE, since changes will be overwritten with next import."><sup>&lt;</sup></span>{/if}&nbsp;{if $Hints[$ColumnViewData.FieldName]}<img src="img/icons/information{if $FrFieldNames[$ColumnViewData.FieldName] != $Col->getCaption()}-balloon{/if}.png" alt="Hinweis" data-hint="{$Hints[$ColumnViewData.FieldName]}" data-hinttitle="{$FrFieldNames[$ColumnViewData.FieldName]}">{/if}
        {if $FrFieldNames[$ColumnViewData.FieldName] != "" && $FrFieldNames[$ColumnViewData.FieldName] != $Col->getCaption()}<br><span class="text-fr">{$FrFieldNames[$ColumnViewData.FieldName]|truncate:20:"&nbsp;…":false}</span>{/if}
        {include file="edit_field_options.tpl" Column=$ColumnViewData}
    {else}
        {* Duplicated code! *}
        &nbsp;{if $Hints[$ColumnViewData.FieldName]}<img src="img/icons/information{if $FrFieldNames[$ColumnViewData.FieldName] != $Col->getCaption()}-balloon{/if}.png" alt="Hinweis" data-hint="{$Hints[$ColumnViewData.FieldName]}" data-hinttitle="{$FrFieldNames[$ColumnViewData.FieldName]}">{/if}
        {if $FrFieldNames[$ColumnViewData.FieldName] != "" && $FrFieldNames[$ColumnViewData.FieldName] != $Col->getCaption()}<br><span class="text-fr">{$FrFieldNames[$ColumnViewData.FieldName]|truncate:20:"&nbsp;…":false}</span>{/if}
    {/if}
    {*if $Column.Hint}<img src="img/icons/information.png" alt="Hinweis" data-hint="{$Column.Hint}" data-hinttitle="{$Column.Caption}">{/if*}
    {*if $Hints[$Column.FieldName]}<img src="img/icons/information.png" alt="Hinweis" data-hint="{$Hints[$Column.FieldName]}" data-hinttitle="{$Column.Caption}">{/if*}
</label>
