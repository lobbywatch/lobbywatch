{if $DataGrid.AllowMultiUpload}
    <div class="btn-group">
        <a class="btn btn-default pgui-multi-upload" href="{$DataGrid.Links.MultiUpload|escapeurl}" title="{$Captions->GetMessageString('UploadFiles')}">
            <i class="icon-upload"></i>
            <span class="visible-lg-inline">{$Captions->GetMessageString('UploadFiles')}</span>
        </a>
    </div>
{/if}

