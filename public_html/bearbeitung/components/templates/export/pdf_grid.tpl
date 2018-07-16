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
                {foreach item=Cell from=$TableHeader.Cells}
                    <td
                        {n}{attr name=align value=$Row[$Cell.Name].Align}
                        {n}{attr name=style value=$Row[$Cell.Name].Style}
                    >
                        {$Row[$Cell.Name].Value}
                    </td>
                {/foreach}
            </tr>
        {/foreach}
        {include file='list/totals.tpl'}
    </tbody>
</table>
{/strip}