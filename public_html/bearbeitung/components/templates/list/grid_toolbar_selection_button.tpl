{if $DataGrid.AllowSelect}
    <div class="btn-group js-selection-actions-container fade" style="display: none">
        <div class="btn-group">
            <button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">
                                {$Captions->GetMessageString('ItemsSelected')|@sprintf:'<span class="js-count">0</span>'}
                            </span>
                <span class="js-count visible-xs-inline">0</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" class="js-action" data-type="clear">{$Captions->GetMessageString('Clear')}</a></li>
                {if $DataGrid.SelectionFilterAllowed}
                    <li class="divider"></li>
                    <li class="dropdown dropdown-sub-menu">
                        <a href="#">{$Captions->GetMessageString('SelectionFilter')}</a>
                        <ul class="dropdown-menu sub-menu">
                            <li><a href="#" class="js-action" data-type="select" data-condition="selected" data-url="{$Page->getLink()}">{$Captions->GetMessageString('ShowSelectedOnly')}</a></li>
                            <li class="divider"></li>
                            <li><a href="#" class="js-action" data-type="select" data-condition="unselected" data-url="{$Page->getLink()}">{$Captions->GetMessageString('ShowUnselectedOnly')}</a></li>
                            <li class="divider"></li>
                            <li><a href="#" class="js-action" data-type="select" data-condition="all" data-url="{$Page->getLink()}">{$Captions->GetMessageString('ShowAll')}</a></li>
                        </ul>
                    </li>
                {/if}
                {if $DataGrid.AllowCompare}
                    <li class="divider"></li>
                    <li><a href="#" class="js-action" data-type="compare" data-url="{$Page->getLink()}">{$Captions->GetMessageString('CompareSelected')}</a></li>
                {/if}
                {if $DataGrid.AllowExportSelected}
                    <li class="divider"></li>
                    <li class="dropdown dropdown-sub-menu">
                        <a href="#">{$Captions->GetMessageString('Export')}</a>
                        <ul class="dropdown-menu sub-menu">
                            {foreach from=$Page->getExportSelectedRecordsViewData() item=Item}
                                <li><a href="#"{$Item.Target} class="js-action" data-type="export" data-export-type="{$Item.Type}" data-url="{$Page->getLink()}">{$Item.Caption}</a></li>
                            {/foreach}
                        </ul>
                    </li>
                {/if}
                {if $DataGrid.AllowPrintSelected}
                    <li class="divider"></li>
                    <li><a href="#"{$DataGrid.PrintLinkTarget} class="js-action" data-type="print" data-url="{$Page->getLink()}">{$Captions->GetMessageString('PrintSelected')}</a></li>
                {/if}
                {if $DataGrid.MultiEditAllowed}
                    <li class="divider"></li>
                    <li><a href="#" class="js-action" data-type="update" data-url="{$Page->getLink()}" {if $DataGrid.UseModalMultiEdit}data-modal-operation="multiple-edit" data-multiple-edit-handler-name="{$Page->GetGridMultiEditHandler()}"{/if}>{$Captions->GetMessageString('Update')}</a></li>
                {/if}
                {if $DataGrid.AllowDeleteSelected}
                    <li class="divider"></li>
                    <li><a href="#" class="js-action" data-type="delete" data-url="{$Page->getLink()}">{$Captions->GetMessageString('DeleteSelected')}</a></li>
                {/if}
            </ul>
        </div>
    </div>
{/if}
