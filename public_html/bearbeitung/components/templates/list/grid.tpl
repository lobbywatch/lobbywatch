<div{if $DataGrid.MaxWidth} style="max-width: {$DataGrid.MaxWidth}"{/if}
        class="grid grid-table{if $isMasterGrid} grid-master{/if}{if $GridClass} {$GridClass}{/if} js-grid"
        id="{$DataGrid.Id}"
        data-selection-id="{$DataGrid.SelectionId}"
        data-is-master="{$isMasterGrid}"
        data-grid-hidden-values="{$DataGrid.HiddenValuesJson|escape:'html'}"
        data-sortable-columns="{if not $isInline}{$DataGrid.SortableColumnsJSON|escape}{else}[]{/if}"
        data-column-count="{$DataGrid.ColumnCount}"
        {if $DataGrid.ReloadPageAfterAjaxOperation}data-reload-page-after-ajax-operation="true"{/if}
        {$DataGrid.Attributes}>

    {include file=$GridToolbarTemplate}

    {$GridContent}

    {include file="list/grid_common.tpl"}
</div>