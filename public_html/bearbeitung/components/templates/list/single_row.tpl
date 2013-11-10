{if count($DataGrid.Rows) > 0}

    {foreach item=Row from=$DataGrid.Rows name=RowsGrid}

    <tr class="pg-row" style="{$Row.Style}">
        {if $DataGrid.AllowDeleteSelected}
            <td class="row-selection" style="{$Row.Style}">
                <input type="checkbox" name="rec{$smarty.foreach.RowsGrid.index}" >
                {foreach item=PkValue from=$Row.PrimaryKeys name=CPkValues}
                    <input type="hidden" name="rec{$smarty.foreach.RowsGrid.index}_pk{$smarty.foreach.CPkValues.index}" value="{$PkValue|escapeurl}" />
                {/foreach}
            </td>
        {/if}

        {if $DataGrid.HasDetails}
            <td dir="ltr" class="details" style="{$Row.Style}; width: 40px;">
                <div class="btn-group detail-quick-access" style="display: inline-block;" >
                <a class="expand-details collapsed"
                   style="display: inline-block;"
                   data-info="{$Row.Details.JSON}"
                   href="#"><i class="toggle-detail-icon"></i>
                </a><a data-toggle="dropdown" href="#"><i class="pg-icon-detail-additional"></i></a><ul class="dropdown-menu">
                        {foreach from=$Row.Details.Items item=Detail}
                            <li><a href="{$Detail.SeperatedPageLink|escapeurl}">{$Detail.caption}</a></li>
                        {/foreach}
                    </ul>
                </div>
            </td>
        {/if}


        {if $DataGrid.ShowLineNumbers}
            <td class="line-number" style="{$Row.Style}">{$Row.LineNumber}</td>
        {/if}

        {foreach item=Cell from=$Row.DataCells name=Cells}
            <td data-column-name="{$Cell.ColumnName}" style="{$Cell.Style}" class="{$Cell.Classes}">{$Cell.Data}</td>
        {/foreach}
    </tr>

    {/foreach}

{/if}

{* {strip}
{if count($Rows) > 0}
    {foreach item=Row from=$Rows name=RowsGrid}
        <tr class="{if $smarty.foreach.RowsGrid.index is even}even{else}odd{/if}"{if $RowCssStyles[$smarty.foreach.RowsGrid.index] != ''} style="{$RowCssStyles[$smarty.foreach.RowsGrid.index]}"{/if}>

        {if $ShowLineNumbers}
            <td class="odd pgui-line-number"></td>
        {/if}
        {if $AllowDeleteSelected}
        {strip}
        <td class="odd" {if $RowCssStyles[$smarty.foreach.RowsGrid.index] != ''} style="{$RowCssStyles[$smarty.foreach.RowsGrid.index]}"{/if}>
            <input type="checkbox" name="rec{$smarty.foreach.RowsGrid.index}" id="rec{$smarty.foreach.RowsGrid.index}" />
            {foreach item=PkValue from=$RowPrimaryKeys[$smarty.foreach.RowsGrid.index] name=CPkValues}
                <input type="hidden" name="rec{$smarty.foreach.RowsGrid.index}_pk{$smarty.foreach.CPkValues.index}" value="{$PkValue}" />
            {/foreach}
        </td>
        {/strip}
        {/if}

        {foreach item=RowColumn from=$Row name=RowColumns}
        {strip}
            <td data-column-name="{$ColumnsNames[$smarty.foreach.RowColumns.index]}" char="{$RowColumnsChars[$smarty.foreach.RowsGrid.index][$smarty.foreach.RowColumns.index]}" class="{if $smarty.foreach.RowColumns.index is even}even{else}odd{/if}" {if $RowColumnsCssStyles[$smarty.foreach.RowsGrid.index][$smarty.foreach.RowColumns.index] != ''}style="{$RowColumnsCssStyles[$smarty.foreach.RowsGrid.index][$smarty.foreach.RowColumns.index]}"{/if}>
                {$RowColumn}
            </td>
        {/strip}
        {/foreach}

        </tr>
        <tr pgui-details="true" style="border: none; height: 0px;">
            <td colspan="{$ColumnCount}" style="border: none; padding: 0px; height: 0px;">
            {foreach item=AfterRow from=$AfterRows[$smarty.foreach.RowsGrid.index]}
                {$AfterRow}
            {/foreach}
            </td>
        </tr>

    {/foreach}
{/if}
{/strip} *}