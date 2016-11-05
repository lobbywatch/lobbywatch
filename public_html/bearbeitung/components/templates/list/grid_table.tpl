{capture assign=tableColumnHeaderTemplate}{$tableColumnHeaderTemplate|default:'list/grid_column_header.tpl'}{/capture}

{capture assign=GridContent}
    <table class="table text-center {$DataGrid.Classes}{if $DataGrid.TableIsBordered} table-bordered{/if}{if $DataGrid.TableIsCondensed} table-condensed{/if}">
        <thead class="js-column-filter-container{if $DataGrid.ColumnGroup->getDepth() > 1} header-bordered" data-has-groups="true{/if}">
            {section name=header loop=$DataGrid.ColumnGroup->getDepth()}
                {assign var=depth value=$smarty.section.header.index}
                <tr>
                    {if $depth == 0}
                        {if $DataGrid.AllowSelect}
                            <th style="width:1%;" rowspan="{$DataGrid.ColumnGroup->getDepth()}">
                                <div class="row-selection">
                                    <input type="checkbox">
                                </div>
                            </th>
                        {/if}

                        {if $DataGrid.HasDetails}
                            <th class="details" rowspan="{$DataGrid.ColumnGroup->getDepth()}">
                                <a class="expand-all-details js-expand-all-details collapsed link-icon" href="#" title="{$Captions->GetMessageString('ToggleAllDetails')}">
                                    <i class="icon-detail-plus"></i>
                                    <i class="icon-detail-minus"></i>
                                </a>
                            </th>
                        {/if}

                        {if $DataGrid.ShowLineNumbers}
                            <th style="width:1%;" rowspan="{$DataGrid.ColumnGroup->getDepth()}">#</th>
                        {/if}

                        {if $DataGrid.Actions and $DataGrid.Actions.PositionIsLeft}
                            <th style="width:1%;" rowspan="{$DataGrid.ColumnGroup->getDepth()}">
                                {$DataGrid.Actions.Caption}
                            </th>
                        {/if}
                    {/if}

                    {foreach from=$DataGrid.ColumnGroup->getAtDepth($depth) item=child}
                        {include
                            file=$tableColumnHeaderTemplate
                            childDepth=$child->getDepth()}
                    {/foreach}

                    {if $depth == 0}
                        {if $DataGrid.Actions and $DataGrid.Actions.PositionIsRight}
                            <th style="width:1%;" rowspan="{$DataGrid.ColumnGroup->getDepth()}">
                                {$DataGrid.Actions.Caption}
                            </th>
                        {/if}
                    {/if}
                </tr>
            {/section}
        </thead>
        <tbody class="pg-row-list">
            {include file=$SingleRowTemplate Columns=$DataGrid.ColumnGroup->getLeafs()}

            <tr class="empty-grid{if count($DataGrid.Rows) > 0} hidden{/if}">
                <td colspan="{$DataGrid.ColumnCount}">
                    <div class="alert alert-warning empty-grid">{$DataGrid.EmptyGridMessage}</div>
                </td>
            </tr>
        </tbody>

        <tfoot>
            {if $DataGrid.Totals}
                <tr class="data-summary">
                    {if $DataGrid.AllowSelect}
                        <td></td>
                    {/if}

                    {if $DataGrid.HasDetails}
                        <td></td>
                    {/if}

                    {if $DataGrid.ShowLineNumbers}
                        <td></td>
                    {/if}

                    {if $DataGrid.Actions and $DataGrid.Actions.PositionIsLeft}
                        <td></td>
                    {/if}

                    {foreach item=Total from=$DataGrid.Totals}
                        <td class="{$Total.Classes}">{$Total.Value}</td>
                    {/foreach}

                    {if $DataGrid.Actions and $DataGrid.Actions.PositionIsRight}
                        <td></td>
                    {/if}
                </tr>
            {/if}
        </tfoot>
    </table>
    <script id="{$DataGrid.Id}_row_template" type="text/html">
        <tr>
            <td class="pg-inline-edit-container" colspan="<%=getColumnCount()%>">
                <div class="col-md-10 col-md-offset-1 js-inline-edit-container pg-inline-edit-container-loading">
                    <img src="components/assets/img/loading.gif">
                </div>
            </td>
        </tr>
    </script>
{/capture}

{include file='list/grid.tpl'}