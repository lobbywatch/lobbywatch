{if $DataGrid.ActionsPanel.RefreshButton and not $isInline}
    <div class="btn-group">
        <a class="btn btn-default" href="{$DataGrid.Links.Refresh|escapeurl}" title="{$Captions->GetMessageString('Refresh')}">
            <i class="icon-page-refresh"></i>
            <span class="visible-lg-inline">{$Captions->GetMessageString('Refresh')}</span>
        </a>
    </div>
{/if}

