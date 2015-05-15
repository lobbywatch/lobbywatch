<div id="pgui-view-grid">
    <div class="page-header">
        <h1>{$Grid.Title}</h1>
    </div>

    <div class="container-fluid">

        <br>

        <div class="row-fluid">
            <div class="span6">
                <table class="table pgui-record-card">
                    <tbody>
                    {foreach from=$Grid.Row item=Cell}
                        <tr>
                            <td>
                                <strong>{$Cell.Caption}</strong>
                            </td>
                            <td>
                                {$Cell.DisplayValue}
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>

        </div>
        <div class="row-fluid">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <a class="btn btn-primary"
                       href="{$Grid.CancelUrl|escapeurl}">{$Captions->GetMessageString('BackToList')}</a>
                </div>

                {if count($Grid.Details) > 0}
                    <div class="btn-group">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            {$Captions->GetMessageString('ManageDetails')}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            {foreach from=$Grid.Details item=Detail}
                                <li>
                                    <a href="{$Detail.Link|escapeurl}">{$Detail.Caption|string_format:$Captions->GetMessageString('ManageDetail')}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}

                {if $Grid.PrintOneRecord}
                    <div class="btn-group">
                        <a class="btn" href="{$Grid.PrintRecordLink|escapeurl}">
                            <i class="pg-icon-print-page icon-white"></i>
                            {$Captions->GetMessageString('PrintOneRecord')}
                        </a>
                    </div>
                {/if}


            </div>
        </div>
    </div>
</div>