{if ($DataGrid.FilterBuilder->hasColumns() or $DataGrid.ColumnFilter->hasColumns() or $DataGrid.QuickFilter->hasColumns() or $FilterStatus)}
    <div class="filter-status js-filter-status">
        {$FilterStatus}

        {include file='list/selection_filter_status.tpl'}

        {include file='list/filter_status_value.tpl'
        filter=$DataGrid.FilterBuilder
        id='filterBuilder'
        typeClass='filter-builder'
        isEditable=true
        isToggable=true
        icon='filter-alt'
        ignoreDisabled=false
        stringRepresentation=$DataGrid.FilterBuilder->toString($Captions, '<span class="filter-status-value-disabled-component">%s</span>')}

        {include file='list/filter_status_value.tpl'
        filter=$DataGrid.ColumnFilter
        id='columnFilter'
        typeClass='column-filter'
        isEditable=false
        isToggable=true
        icon='filter'
        ignoreDisabled=true
        stringRepresentation=$DataGrid.ColumnFilter->toString($Captions)}
    </div>
{/if}

