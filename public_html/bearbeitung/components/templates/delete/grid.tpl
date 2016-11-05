<div align="center" style="width: auto">
    <table class="grid" style="width: 500px">
        <tr>
            <th class="even" colspan=2>
                {$Title}
            </th>
        </tr>
        {foreach from=$Grid->GetErrorMessages() item=Message}
            <tr>
                <td class="odd grid_error_row" colspan="4">
                    <div class="grid_error_message grid_message">
                        <strong>{$Captions->GetMessageString('ErrorsDuringDeleteProcess')}</strong><br><br>
                        {$Message}
                    </div>
                </td>
            </tr>
        {/foreach}
        {section name=RowGrid loop=$ColumnCount}
            <tr class="{if $smarty.section.RowGrid.index is even}even{else}odd{/if}"{if $RowCssStyles[$smarty.foreach.RowsGrid.index] != ''} style="{$RowCssStyles[$smarty.foreach.RowsGrid.index]}"{/if}>
                <td class="odd" style="padding-left:20px;"><b>{$Columns[$smarty.section.RowGrid.index]->GetCaption()}</b></td>
                <td class="even" style="padding-left:10px;">
                    {$Row[$smarty.section.RowGrid.index]}
                </td>
            </tr>
        {/section}
        <tr height="40" class="editor_buttons"><td colspan=2 align=center valign=middle>
            <form name="deleteform" style="margin: 0px; padding: 0px;" enctype="multipart/form-data" method="POST" action="{$Grid->GetEditPageAction()}">
                {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
                    <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}" class="form-control">
                {/foreach}
                <input class="sm_button" type="submit" value="{$Captions->GetMessageString('Delete')}">
                <input class="sm_button" type="button" value="{$Captions->GetMessageString('BackToList')}" onclick="window.location.href='{$Grid->GetReturnUrl()}'">
            </form>
        </td></tr>
    </table>
</div>