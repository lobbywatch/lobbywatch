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

            <div class="btn-group">

                {if $DataGrid.ActionsPanel.RefreshButton and not $isInline}
                    <a class="btn btn-default" href="{$DataGrid.Links.Refresh|escapeurl}" title="{$Captions->GetMessageString('Refresh')}">
                        <i class="icon-page-refresh"></i>
                        <span class="visible-lg-inline">{$Captions->GetMessageString('Refresh')}</span>
                    </a>
                {/if}
            </div>

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

            <div class="btn-group">
              {assign var="opClass" value="set-imratbis-selected"}
              {assign var="opIcon" value="pg-icon-set-imratbis-selected"}
              {assign var="opCaption" value="Setze &quot;im Rat bis&quot;"}
              {assign var="opDesc" value="Setze &quot;im Rat bis&quot;"}
              {if $DataGrid.ActionsPanel.ImRatBisSelectedButton eq 'modal' or $DataGrid.ActionsPanel.ImRatBisSelectedButton eq 'inline'}
                  <button class="btn btn-default {$opClass}"
                          {*data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}"*}
                          data-content-link="#"
                          {*data-{$DataGrid.ActionsPanel.AddNewButton}-insert="true"*}
                          title="{$opDesc}{*$Captions->GetMessageString('AddNewRecord')*}">
                      <i class="{$opIcon}"></i>
                      <span class="visible-lg-inline">{$opCaption}{*$Captions->GetMessageString('AddNewRecord')*}</span>
                  </button>
              {else}
                  <a class="btn btn-default {$opClass}"
                     {*href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}"*}
                     href="#"
                     title="{$opDesc}{*$Captions->GetMessageString('AddNewRecord')*}">
                      <i class="{$opIcon}"></i>
                      <span class="visible-lg-inline">{$opCaption}{*$Captions->GetMessageString('AddNewRecord')*}</span>
                  </a>
              {/if}
            
                {*if $DataGrid.ActionsPanel.ImRatBisSelectedButton}
                    <button class="btn set-imratbis-selected">
                        <i class="pg-icon-set-imratbis-selected"></i>
                        Setze &quot;im Rat bis&quot;
                        {* $Captions->GetMessageString('AuthorizeSelected') * }
                    </button>
                {/if*}

                {if $DataGrid.ActionsPanel.ImRatBisSelectedButton}
                    <button class="btn clear-imratbis-selected">
                        <i class="pg-icon-clear-imratbis-selected"></i>
                        Lösche &quot;im Rat bis&quot;
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.InputFinishedSelectedButton}
                    <button class="btn input-finished-selected">
                        <i class="pg-icon-input-finished-selected"></i>
                        Eingabe abgeschlossen
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.InputFinishedSelectedButton}
                    <button class="btn de-input-finished-selected">
                        <i class="pg-icon-de-input-finished-selected"></i>
                        Ent-Eingabe abgeschlossen
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.ControlledSelectedButton}
                    <button class="btn controlled-selected">
                        <i class="pg-icon-controlled-selected"></i>
                        Kontrolliert
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.ControlledSelectedButton}
                    <button class="btn de-controlled-selected">
                        <i class="pg-icon-de-controlled-selected"></i>
                        Ent-Kontrolliert
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.AuthorizationSentSelectedButton}
                    <button class="btn authorization-sent-selected">
                        <i class="pg-icon-authorization-sent-selected"></i>
                        Autorisierung verschickt
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.AuthorizationSentSelectedButton}
                    <button class="btn de-authorization-sent-selected">
                        <i class="pg-icon-de-authorization-sent-selected"></i>
                        Ent-Autorisierung verschickt
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.AuthorizeSelectedButton}
                    <button class="btn authorize-selected">
                        <i class="pg-icon-authorize-selected"></i>
                        Autorisieren
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.AuthorizeSelectedButton}
                    <button class="btn de-authorize-selected">
                        <i class="pg-icon-de-authorize-selected"></i>
                        Ent-Autorisieren
                        {* $Captions->GetMessageString('AuthorizeSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.ReleaseSelectedButton}
                    <button class="btn release-selected">
                        <i class="pg-icon-release-selected"></i>
                        Veröffentlichen
                        {* $Captions->GetMessageString('ReleaseSelected') *}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.ReleaseSelectedButton}
                    <button class="btn de-release-selected">
                        <i class="pg-icon-de-release-selected"></i>
                        Un-Veröffentlichen
                        {* $Captions->GetMessageString('ReleaseSelected') *}
                    </button>
                {/if}
            </div>


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
                            <li><a href="#" class="js-action" data-type="clear">{$Captions->GetMessageString('ClearSelection')}</a></li>
                            <li class="divider"></li>

                            {if $DataGrid.AllowCompare}
                                <li><a href="#" class="js-action" data-type="compare" data-url="{$Page->getLink()}">{$Captions->GetMessageString('CompareSelected')}</a></li>
                            {/if}

                            {if $DataGrid.AllowCompare and $DataGrid.AllowDeleteSelected}
                                <li class="divider"></li>
                            {/if}

                            {if $DataGrid.AllowDeleteSelected}
                                <li>
                                    <a href="#" class="js-action" data-type="delete" data-url="{$Page->getLink()}">
                                        {$Captions->GetMessageString('DeleteSelected')}
                                    </a>
                                </li>
                            {/if}
                            
                            {if $DataGrid.AllowDeleteSelected and $DataGrid.ActionsPanel.ImRatBisSelectedButton}
                                <li class="divider"></li>
                            {/if}

                            {if $DataGrid.ActionsPanel.ImRatBisSelectedButton}
                                <li>
                                    <a href="#" class="js-action" data-type="set-imratbis-selected" data-url="{$Page->getLink()}">
                                        Setze &quot;im Rat bis&quot;{*$Captions->GetMessageString('DeleteSelected')*}
                                    </a>
                                </li>
                            {/if}

                            {if $DataGrid.ActionsPanel.ImRatBisSelectedButton}
                                <li>
                                    <a href="#" class="js-action" data-type="clear-imratbis-selected" data-url="{$Page->getLink()}">
                                        Lösche &quot;im Rat bis&quot;{*$Captions->GetMessageString('DeleteSelected')*}
                                    </a>
                                </li>
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
            <div class="addition-block-right pull-right js-quick-filter">
                <div class="quick-filter-toolbar btn-group">
                    <div class="input-group js-filter-control">
                        <input placeholder="{$Captions->GetMessageString('QuickSearch')}" type="text" size="16" class="js-input form-control" name="quick_filter" value="{$DataGrid.QuickFilter->getValue()|escape:html}">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default js-submit" title="{$Captions->GetMessageString('QuickSearchApply')}"><i class="icon-search"></i></button>
                            <button type="button" class="btn btn-default js-reset" title="{$Captions->GetMessageString('QuickSearchClear')}"><i class="icon-filter-reset"></i></button>
                        </div>
                    </div>
                </div>
                <span class="hidden-xs">&thinsp;</span>
            </div>
        {/if}
    </div>

{/if}

{$GridBeforeFilterStatus}

{if ($DataGrid.FilterBuilder->hasColumns() or $DataGrid.ColumnFilter->hasColumns() or $DataGrid.QuickFilter->hasColumns())}
    <div class="filter-status js-filter-status">
        {$FilterStatus}

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
