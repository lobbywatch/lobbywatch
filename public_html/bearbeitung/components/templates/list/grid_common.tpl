{if not $isInline}
    {if not $isMasterGrid and $DataGrid.EnableSortDialog}
        {include file="list/multiple_sorting.tpl" GridId=$DataGrid.Id Levels=$DataGrid.DataSortPriority SortableHeaders=$DataGrid.SortableColumns}
    {/if}
    {if $DataGrid.FilterBuilder->hasColumns()}
        {include file="list/filter_builder.tpl"}
    {/if}

    {if $DataGrid.ColumnFilter->hasColumns()}
        {include file="list/column_filter.tpl"}
    {/if}
{/if}

<script type="text/javascript">{strip}
    {literal}
(function () {
    var gridData = {
        filterBuilder: {
            columns: [],
            data: {}
        },
        columnFilter: {
            columns: [],
            isSearchEnabled: {},
            excludedComponents: {},
            data: {}
        },
        quickFilter: {
            columns: []
        }
    };
    {/literal}

    {if !$isInline}
        {literal}
            gridData.filterBuilder.data = {/literal}{to_json value=$DataGrid.FilterBuilder->serialize()}{literal};
        {/literal}

        {foreach item=column from=$DataGrid.FilterBuilder->getColumns()}
        {assign var=operators value=$DataGrid.FilterBuilder->getOperators($column)}
        {literal}
            gridData.filterBuilder.columns.push({
                {/literal}
                fieldName: '{$column->getFieldName()|escape:"quotes"}',
                caption: '{$column->getCaption()|escape:"quotes"}',
                operators: {to_json value=$operators|@array_keys}
                {literal}
            });
        {/literal}
        {/foreach}{literal}

        gridData.columnFilter.data = {/literal}{to_json value=$DataGrid.ColumnFilter->serialize()}{literal};
        gridData.columnFilter.isDefaultsEnabled = {/literal}{to_json value=$DataGrid.ColumnFilter->getDefaultsEnabled()}{literal};

        {/literal}{foreach item=column from=$DataGrid.ColumnFilter->getColumns()}
        {literal}
            gridData.columnFilter.columns.push({
                fieldName: {/literal}'{$column->getFieldName()|escape:"quotes"}'{literal},
                caption: {/literal}'{$column->getCaption()|escape:"quotes"}'{literal},
                typeIsDateTime: {/literal}'{$column->typeIsDateTime()}'{literal}
            });
        {/literal}
        {/foreach}

        gridData.quickFilter.columns = {to_json value=$DataGrid.QuickFilter->getColumnNames()};
    {/if}
{literal}
    window.gridData_{/literal}{$DataGrid.Id}{literal} = gridData;
})();
{/literal}
{/strip}</script>
