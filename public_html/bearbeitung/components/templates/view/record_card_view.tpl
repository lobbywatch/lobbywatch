<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
    <h3 class="title">aa</h3>
</div>
<div class="modal-body">

        <div >
            <table class="table pgui-record-card">
                <tbody>
                {section name=RowGrid loop=$ColumnCount}
                <tr {if $RowCssStyles[$smarty.foreach.RowsGrid.index] != ''} style="{$RowCssStyles[$smarty.foreach.RowsGrid.index]}"{/if}>
                    <td>
                        <b>{$Columns[$smarty.section.RowGrid.index]->GetCaption()}</b>
                    </td>
                    <td>
                        {$Row[$smarty.section.RowGrid.index]}
                    </td>
                </tr>
                {/section}
                </tbody>
            </table>
        </div>


</div>

<div class="modal-footer">

    <div class="btn-toolbar">

        <div class="btn-group">
            <button class="btn close-button">Close</button>
        </div>

    </div>

</div>