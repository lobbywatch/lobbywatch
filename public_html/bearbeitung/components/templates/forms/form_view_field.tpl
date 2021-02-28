{assign var='ColumnName' value=$Col->getName()}
{if isset($Grid.CellEditUrls[$ColumnName])}
    {assign var='CellEditUrl' value=$Grid.CellEditUrls[$ColumnName]}
{/if}

{capture assign='ViewControl'}
    <div class="form-control-static{if isset($CellEditUrl)} pgui-cell-edit{/if}"{if isset($CellEditUrl)} data-column-name="{$ColumnName}" data-edit-url="{$CellEditUrl}"{/if}>
        {$Col->getDisplayValue($Renderer)}
    </div>
{/capture}

{include file='forms/form_field_layout.tpl' FormControl=$ViewControl FormControlStyles=''}
