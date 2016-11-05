<table>
    {foreach key=Key item=Column from=$Columns}
        <tr>
            <td><b>{$Column->GetCaption()}</b></td>
            <td>{$Rows[0][$Key]}</td>
        </tr>
    {/foreach}
</table>