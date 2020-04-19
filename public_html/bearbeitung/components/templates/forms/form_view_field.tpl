{assign var='ColumnName' value=$Col->getName()}
{assign var='CellEditUrl' value=$Grid.CellEditUrls[$ColumnName]}

{capture assign='ViewControl'}
    <div class="form-control-static{if $CellEditUrl} pgui-cell-edit{/if}"{if $CellEditUrl} data-column-name="{$ColumnName}" data-edit-url="{$CellEditUrl}"{/if}>
        {$Col->getDisplayValue($Renderer)}
    </div>
{/capture}

{include file='forms/form_field_layout.tpl' FormControl=$ViewControl}