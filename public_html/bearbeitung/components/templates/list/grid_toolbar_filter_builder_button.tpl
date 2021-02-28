{if $DataGrid.FilterBuilder->hasColumns()}
    <div class="btn-group">
        <button type="button" class="btn btn-default js-filter-builder-open" title={$Captions->GetMessageString('EditFilter')}">
            <i class="icon-filter-alt"></i>
        </button>
    </div>
{/if}
