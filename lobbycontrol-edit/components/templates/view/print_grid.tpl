<table>
{section name=RowGrid loop=$ColumnCount}
    <tr>
        <td><b>{$Columns[$smarty.section.RowGrid.index]->GetCaption()}</b></td>
        <td>{$Row[$smarty.section.RowGrid.index]}</td>
    </tr>
{/section}
</table>
</div>
