<h1>{$Grid.Title}</h1>
<table border="1" cellpadding="0" cellspacing="0" width="100%">
    {foreach item=Column from=$Grid.Row}
        <tr>
            <td style="background-color:#ccc;width:100px;">{$Column.Caption}</td>
            <td style="text-align:left">{$Column.DisplayValue}</td>
        </tr>
    {/foreach}
</table>