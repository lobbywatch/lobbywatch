<div class="modal multiple-sorting modal-top fade" id="multiple-sorting-{$GridId}" tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$Captions->GetMessageString('MultipleColumnSorting')}</h4>
            </div>

            <div class="modal-body">
                <div class=fixed-table-container>
                    <table class="table table-vcenter multiple-sorting-table">
                        <thead>
                            <tr>
                                <td colspan="3" class="header-panel">
                                    <div class="btn-toolbar pull-left">
                                        <div class="btn-group">
                                            <button class="btn btn-default add-sorting-level">
                                                <i class="icon-plus"></i>
                                                {$Captions->GetMessageString('AddLevel')}
                                            </button>
                                            <button class="btn btn-default delete-sorting-level">
                                                <i class="icon-minus"></i>
                                                {$Captions->GetMessageString('DeleteLevel')}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="header">
                                <th style="width: 6em;"></th>
                                <th>{$Captions->GetMessageString('Column')}</th>
                                <th>{$Captions->GetMessageString('Order')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach item=Level key=Index from=$Levels}
                            <tr class="sorting-level">
                                {if $Index == 0}
                                    <td>{$Captions->GetMessageString('SortBy')}</td>
                                {else}
                                    <td>{$Captions->GetMessageString('ThenBy')}</td>
                                {/if}
                                <td>
                                    <select class="multi-sort-name form-control">
                                    {foreach from=$SortableHeaders item="value" key="key"}
                                        <option value="{$key}" {if ($Level->getFieldName() == $key)} selected{/if}>{$value.caption}</option>
                                    {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <select class="multi-sort-order form-control">
                                        <option value="a"{if ($Level->getShortOrderType() == 'a')} selected{/if}>{$Captions->GetMessageString('Ascending')}</option>
                                        <option value="d"{if ($Level->getShortOrderType() == 'd')} selected{/if}>{$Captions->GetMessageString('Descending')}</option>
                                    </select>
                                </td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Cancel')}</button>
                <button type="button" class="sort-button btn btn-primary">{$Captions->GetMessageString('Sort')}</button>
            </div>
        </div>
    </div>

</div>
