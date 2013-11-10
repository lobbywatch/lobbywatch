{strip}
<table border="1" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
        {foreach item=Cell from=$TableHeader.Cells}
            <th>{$Cell.Caption}</th>
        {/foreach}
        </tr>
    </thead>
    <tbody>
        {foreach item=Row from=$Rows name=Rows}
        <tr class="{parity name=Rows}">
            {foreach item=RowColumn from=$Row}
                <td
                    {n}{attr name=align value=$RowColumn.Align}
                    {n}{attr name=style value=$RowColumn.Style}
                    >
                    {$RowColumn.Value}
                </td>
            {/foreach}
        </tr>
        {/foreach}
    </tbody>
</table>
{/strip}