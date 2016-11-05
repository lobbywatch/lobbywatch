<p>
    <a href="{$Page->getLink()}">&larr; {$Captions->getMessageString('ReturnFromDetailToMaster')}</a>
</p>

{if $DataGrid.isDiffers}
    <p class="btn-group" data-toggle="buttons">
        <label class="btn btn-default active">
            <input type="radio" name="compare_mode" class="js-compare-mode" data-mode="diff" autocomplete="off" checked> {$Captions->getMessageString('CompareShowDiff')}
        </label>
        <label class="btn btn-default">
            <input type="radio" name="compare_mode" class="js-compare-mode" data-mode="all" autocomplete="off"> {$Captions->getMessageString('CompareShowAll')}
        </label>
    </p>
{/if}

{if count($DataGrid.records) > 0}
    <table class="table table-bordered">
    {if count($DataGrid.columns.HeaderColumns) > 0}
        <tr>
            <th>
            </th>
            {foreach from=$DataGrid.records item=record}
                <th>
                    {foreach from=$DataGrid.columns.HeaderColumns item=column}
                        {assign var=columnName value=$column->getName()}
                        <div>{$record.HeaderColumns[$columnName].Data}</div>
                    {/foreach}
                </th>
            {/foreach}
        </tr>
    {/if}
    {foreach from=$DataGrid.columns.DataColumns item=column}
        {assign var=columnName value=$column->getName()}
        <tr{if not $DataGrid.columnsDiff[$columnName]} class="success"{if $DataGrid.isDiffers} style="display: none"{/if}{/if} data-diff="{if $DataGrid.columnsDiff[$columnName]}true{else}false{/if}">
            <th>{$column->getCaption()}</th>
            {foreach from=$DataGrid.records item=record}
                {assign var=recordColumn value=$record.DataColumns[$columnName]}
                <td>{$recordColumn.Data}</td>
            {/foreach}
        </tr>
    {/foreach}
    <tr class="js-selection-actions-container" data-selection-id="{$DataGrid.SelectionId}">
        <th></th>
        {foreach from=$DataGrid.records item=record}
            <td><a href="{$record.RemoveLink}" class="js-action" data-type="compare-remove" data-value="{to_json value=$record.Keys escape=true}">{$Captions->getMessageString('CompareRemove')}</a></td>
        {/foreach}
    </tr>
    </table>
{else}
    <div class="alert alert-warning">{$Captions->getMessageString('NoDataToDisplay')}</div>
{/if}