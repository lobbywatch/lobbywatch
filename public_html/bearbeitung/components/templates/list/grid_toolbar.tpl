{if $DataGrid.ActionsPanelAvailable}
    <div class="addition-block js-actions">
        <div class="btn-toolbar addition-block-left pull-left">
                {if $DataGrid.ActionsPanel.AddNewButton}
                    <div class="btn-group">
                        {if $DataGrid.ActionsPanel.AddNewButton eq 'modal' or $DataGrid.ActionsPanel.AddNewButton eq 'inline'}
                            <button class="btn btn-default pgui-add"
                                    data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}"
                                    data-{$DataGrid.ActionsPanel.AddNewButton}-insert="true"
                                    title="{$Captions->GetMessageString('AddNewRecord')}">
                                <i class="icon-plus"></i>
                                <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
                            </button>
                        {else}
                            <a class="btn btn-default pgui-add" href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}"
                               title="{$Captions->GetMessageString('AddNewRecord')}">
                                <i class="icon-plus"></i>
                                <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
                            </a>
                        {/if}
                        {if $DataGrid.AddNewChoices}
                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$DataGrid.AddNewChoices item=choice}
                                    <li>
                                        {if $DataGrid.ActionsPanel.AddNewButton eq 'modal'}
                                            <a href="#"
                                                data-modal-insert="true"
                                                data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}&count={$choice}">
                                                {$Captions->GetMessageString('AddMultipleRecords')|@sprintf:$choice}
                                            </a>
                                        {elseif $DataGrid.ActionsPanel.AddNewButton eq 'inline'}
                                            <a href="#"
                                                data-inline-insert="true"
                                                data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}"
                                                data-count="{$choice}">{$choice}</a>
                                        {else}
                                            <a href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}&count={$choice}">
                                                {$Captions->GetMessageString('AddMultipleRecords')|@sprintf:$choice}
                                            </a>
                                        {/if}
                                    </li>
                                {/foreach}
                            </ul>
                        {/if}
                    </div>
                {/if}

                {if $DataGrid.AllowMultiUpload}
                    <div class="btn-group">
                        <a class="btn btn-default pgui-multi-upload" href="{$DataGrid.Links.MultiUpload|escapeurl}" title="{$Captions->GetMessageString('UploadFiles')}">
                            <i class="icon-upload"></i>
                            <span class="visible-lg-inline">{$Captions->GetMessageString('UploadFiles')}</span>
                        </a>
                    </div>
                {/if}

                {if $DataGrid.ActionsPanel.RefreshButton and not $isInline}
                    <div class="btn-group">
                        <a class="btn btn-default" href="{$DataGrid.Links.Refresh|escapeurl}" title="{$Captions->GetMessageString('Refresh')}">
                            <i class="icon-page-refresh"></i>
                            <span class="visible-lg-inline">{$Captions->GetMessageString('Refresh')}</span>
                        </a>
                    </div>
                {/if}

            {assign var="pageTitleButtons" value=$Page->GetExportListButtonsViewData()}

            {if $pageTitleButtons}
                <div class="btn-group export-button">

                    {if $Page->getExportListAvailable()}
                        {include file="view/export_buttons.tpl" buttons=$pageTitleButtons spanClasses="visible-lg-inline"}
                    {/if}

                    {if $Page->getPrintListAvailable()}
                    {include file="view/print_buttons.tpl" buttons=$pageTitleButtons spanClasses="visible-lg-inline"}
                    {/if}

                    {if $Page->GetRssLink()}
                        <a href="{$Page->GetRssLink()}" class="btn btn-default" title="RSS">
                            <i class="icon-rss"></i>
                            <span class="visible-lg-inline">RSS</span>
                        </a>
                    {/if}

                </div>

            {/if}

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
                                                <li><a href="#" class="js-action" data-type="export" data-export-type="{$Item.Type}" data-url="{$Page->getLink()}">{$Item.Caption}</a></li>
                                            {/foreach}
                                        </ul>
                                </li>
                            {/if}
                            {if $DataGrid.AllowPrintSelected}
                                <li class="divider"></li>
                                <li><a href="#" class="js-action" data-type="print" data-url="{$Page->getLink()}">{$Captions->GetMessageString('PrintSelected')}</a></li>
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
        </div>

        {if not $isInline}
        <div class="addition-block-right pull-right">

            {if $DataGrid.FilterBuilder->hasColumns()}
                <div class="btn-group">
                    <button type="button" class="btn btn-default js-filter-builder-open" title="{if $IsActiveFilterEmpty}{$Captions->GetMessageString('CreateFilter')}{else}{$Captions->GetMessageString('EditFilter')}{/if}">
                        <i class="icon-filter-alt"></i>
                    </button>
                </div>
            {/if}

            {if $DataGrid.EnableSortDialog}
                <div class="btn-group">
                    <button id="multi-sort-{$DataGrid.Id}" class="btn btn-default" title="{$Captions->GetMessageString('Sort')}" data-toggle="modal" data-target="#multiple-sorting-{$DataGrid.Id}">
                        <i class="icon-sort"></i>
                    </button>
                </div>
            {/if}

            {if $PageNavigator or $DataGrid.EnableRunTimeCustomization}
                <div class="btn-group">
                    <button class="btn btn-default" title="{$Captions->GetMessageString('PageSettings')}" data-toggle="modal" data-target="#page-settings">
                        <i class="icon-settings"></i>
                    </button>
                </div>
            {/if}

            {if $Page->getDetailedDescription()}
                <div class="btn-group">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#detailedDescriptionModal" title="{$Captions->GetMessageString('PageDescription')}"><i class="icon-question"></i></button>
                </div>

                <div class="modal fade" id="detailedDescriptionModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                {$Page->getDetailedDescription()}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Close')}</button>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
        </div>
        {/if}

        {if $DataGrid.QuickFilter->hasColumns() and not $isInline}
            {include file="list/quick_filter.tpl" filter=$DataGrid.QuickFilter}
        {/if}
    </div>

{/if}

{$GridBeforeFilterStatus}

{if ($DataGrid.FilterBuilder->hasColumns() or $DataGrid.ColumnFilter->hasColumns() or $DataGrid.QuickFilter->hasColumns())}
    <div class="filter-status js-filter-status">
        {$FilterStatus}

        {include file='list/selection_filter_status.tpl'}

        {include file='list/filter_status_value.tpl'
            filter=$DataGrid.FilterBuilder
            id='filterBuilder'
            typeClass='filter-builder'
            isEditable=true
            isToggable=true
            icon='filter-alt'
            ignoreDisabled=false
            stringRepresentation=$DataGrid.FilterBuilder->toString($Captions, '<span class="filter-status-value-disabled-component">%s</span>')}

        {include file='list/filter_status_value.tpl'
            filter=$DataGrid.ColumnFilter
            id='columnFilter'
            typeClass='column-filter'
            isEditable=false
            isToggable=true
            icon='filter'
            ignoreDisabled=true
            stringRepresentation=$DataGrid.ColumnFilter->toString($Captions)}
    </div>
{/if}

{include file='common/messages.tpl' type='danger' dismissable=true messages=$DataGrid.ErrorMessages displayTime=$DataGrid.MessageDisplayTime}
{include file='common/messages.tpl' type='success' dismissable=true messages=$DataGrid.Messages displayTime=$DataGrid.MessageDisplayTime}

<div class="js-grid-message-container"></div>