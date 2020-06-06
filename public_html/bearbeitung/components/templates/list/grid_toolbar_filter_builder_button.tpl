{if $DataGrid.FilterBuilder->hasColumns()}
    <div class="btn-group">
        <button type="button" class="btn btn-default js-filter-builder-open" title="{if $IsActiveFilterEmpty}{$Captions->GetMessageString('CreateFilter')}{else}{$Captions->GetMessageString('EditFilter')}{/if}">
            <i class="icon-filter-alt"></i>
        </button>
    </div>
{/if}

