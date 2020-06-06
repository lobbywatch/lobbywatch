<table class="wide text-center">
    <tr>
        {foreach item=Column from=$Columns}
            <td><b>{$Column->GetCaption()}</b></td>
        {/foreach}
    </tr>
    {foreach item=Row from=$Rows name=RowsGrid}
        <tr>
            {foreach item=RowColumn from=$Row}
                <td>
                    {$RowColumn}
                </td>
            {/foreach}
        </tr>
    {/foreach}
    {include file='list/totals.tpl'}
</table>
