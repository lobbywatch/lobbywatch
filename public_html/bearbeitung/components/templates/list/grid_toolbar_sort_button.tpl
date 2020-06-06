{if $DataGrid.EnableSortDialog}
    <div class="btn-group">
        <button id="multi-sort-{$DataGrid.Id}" class="btn btn-default" title="{$Captions->GetMessageString('Sort')}" data-toggle="modal" data-target="#multiple-sorting-{$DataGrid.Id}">
            <i class="icon-sort"></i>
        </button>
    </div>
{/if}

