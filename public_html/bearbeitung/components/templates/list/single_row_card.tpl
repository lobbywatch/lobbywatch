{if count($DataGrid.Rows) > 0}

    {foreach item=Row from=$DataGrid.Rows name=RowsGrid}

        {*  The same code is used in single_row.tpl *}
        {if $Row.Classes}
            {assign var="rowClasses" value="pg-row "|cat:$Row.Classes}
        {else}
            {assign var="rowClasses" value="pg-row"}
        {/if}

        <div class="grid-card-item {if $isMasterGrid}col-md-12{else}{$DataGrid.CardClasses}{/if} {$rowClasses}">

            <div class="well" style="{$Row.Style}">

                {if $DataGrid.AllowSelect}
                    <div class="row-selection pull-left">
                        <input id="record_{$DataGrid.InternalId}_{'_'|@implode:$Row.PrimaryKeys|@escape}" type="checkbox" name="rec{$smarty.foreach.RowsGrid.index}" data-value="{to_json value=$Row.PrimaryKeys escape=true}" />
                    </div>
                {/if}

                {if $DataGrid.ShowLineNumbers or $DataGrid.AllowSelect or $DataGrid.HasDetails or $DataGrid.Actions}
                <div class="grid-card-item-control pull-right">
                {/if}

                    {if $DataGrid.ShowLineNumbers}
                        <div class="line-number pull-left" style="{$Row.Style}">#{$Row.LineNumber}</div>
                    {/if}

                    {if $DataGrid.HasDetails}
                        <div dir="ltr" class="details pull-left" style="{$Row.Style}">
                            {include file="list/details_icon.tpl" Details=$Row.Details}
                        </div>
                    {/if}

                    {if $DataGrid.Actions}
                        <div class="operation-column pull-left">{include file="list/action_list.tpl" Actions=$Row.ActionsDataCells}</div>
                    {/if}

                {if $DataGrid.ShowLineNumbers or $DataGrid.AllowSelect or $DataGrid.HasDetails or $DataGrid.Actions}
                </div>
                {/if}

                <div class="grid-card-item-data">
                    <table class="table">
                        {foreach item=Cell from=$Row.DataCells name=Cells}
                            <tr>
                                <th>{$Cell.ColumnCaption}</th>
                                {include file="list/data_cell.tpl"}
                            </tr>
                        {/foreach}
                    </table>
                </div>
            </div>

        </div>
    {/foreach}

{/if}