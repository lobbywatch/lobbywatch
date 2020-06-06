{if $DataGrid.SelectionFilter->isActive()}
<div class="filter-status-value filter-status-value-selection-filter" title="{$DataGrid.SelectionFilter->toString()}">
    <i class="filter-status-value-icon icon-selection-filter"></i>
    <span class="filter-status-value-expr">{$DataGrid.SelectionFilter->toString()}</span>
    <div class="filter-status-value-controls">
        <a href="#" class="js-reset-selection-filter" title="{$Captions->GetMessageString('ResetFilter')}">
            <i class="icon-remove"></i>
        </a>
    </div>
</div>
{/if}
