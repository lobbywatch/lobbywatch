{if $DataGrid.ActionsPanel.CreateVerguetungstransparenzListButton}
    <div class="btn-group js-selection-actions-container js-actions-container-always-visible">
        <a href="#" class="btn btn-default js-action" data-type="create-verguetungstransparenzliste" data-url="{$Page->getLink()}"
            title="Erstelle für ein Stichdatum eine Vergütungstransparenzliste (Schnappschuss der Parlamentarierliste)">
            <i class="icon-plus"></i>
            <span class="visible-lg-inline">Erstelle Vergütungstransparenzliste</span>
        </a>
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

                {if $DataGrid.ActionsPanel.ZahlendSelectedButton}
                    {if $DataGrid.AllowDeleteSelected}
                        <li class="divider"></li>
                    {/if}

                    <li>
                        <a href="#" class="js-action" data-type="set-zahlend-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-set-zahlend-selected"></i>
                            Vergütung bezahlendes Mitglied (-1) setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {if $DataGrid.ActionsPanel.EhrenamtlichSelectedButton}
                    {if $DataGrid.AllowDeleteSelected && !$DataGrid.ActionsPanel.ZahlendSelectedButton}
                        <li class="divider"></li>
                    {/if}

                    <li>
                        <a href="#" class="js-action" data-type="set-ehrenamtlich-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-set-ehrenamtlich-selected"></i>
                            Vergütung ehrenamtlich (0) setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}


                {if $DataGrid.ActionsPanel.BezahltSelectedButton}
                    {if $DataGrid.AllowDeleteSelected && !($DataGrid.ActionsPanel.EhrenamtlichSelectedButton || $DataGrid.ActionsPanel.ZahlendSelectedButton)}
                        <li class="divider"></li>
                    {/if}
                    <li>
                        <a href="#" class="js-action" data-type="set-bezahlt-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-set-bezahlt-selected"></i>
                            Vergütung bezahlt (1) setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {*if $DataGrid.AllowDeleteSelected and $DataGrid.ActionsPanel.ImRatBisSelectedButton}
                    <li class="divider"></li>
                {/if*}

                {if $DataGrid.ActionsPanel.ImRatBisSelectedButton}

                    {if $DataGrid.AllowDeleteSelected}
                        <li class="divider"></li>
                    {/if}

                    <li>
                        <a href="#" class="js-action" data-type="set-imratbis-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-set-imratbis-selected"></i>
                            &quot;Im Rat bis&quot; setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="js-action" data-type="clear-imratbis-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-clear-imratbis-selected"></i>
                            &quot;Im Rat bis&quot; entfernen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {if $DataGrid.ActionsPanel.InputFinishedSelectedButton}

                    {if $DataGrid.AllowDeleteSelected || $DataGrid.MultiEditAllowed}
                        <li class="divider"></li>
                    {/if}

                    <li>
                        <a href="#" class="js-action" data-type="input-finished-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-input-finished-selected"></i>
                            &quot;Eingabe abgeschlossen&quot; setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="js-action" data-type="de-input-finished-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-de-input-finished-selected"></i>
                            &quot;Eingabe abgeschlossen&quot; entfernen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {if $DataGrid.ActionsPanel.ControlledSelectedButton}
                    <li>
                        <a href="#" class="js-action" data-type="controlled-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-controlled-selected"></i>
                            &quot;Kontrolliert&quot; setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="js-action" data-type="de-controlled-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-de-controlled-selected"></i>
                            &quot;Kontrolliert&quot; entfernen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {if $DataGrid.ActionsPanel.AuthorizationSentSelectedButton}
                    <li>
                        <a href="#" class="js-action" data-type="authorization-sent-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-authorization-sent-selected"></i>
                            &quot;Autorisierung verschickt&quot; setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="js-action" data-type="de-authorization-sent-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-de-authorization-sent-selected"></i>
                            &quot;Autorisierung verschickt&quot; entfernen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {if $DataGrid.ActionsPanel.AuthorizeSelectedButton}
                    <li>
                        <a href="#" class="js-action" data-type="authorize-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-authorize-selected"></i>
                            &quot;Autorisiert&quot; setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="js-action" data-type="de-authorize-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-de-authorize-selected"></i>
                            &quot;Autorisiert&quot; entfernen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

                {if $DataGrid.ActionsPanel.ReleaseSelectedButton}
                    <li>
                        <a href="#" class="js-action" data-type="release-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-release-selected"></i>
                            &quot;Veröffentlicht&quot; setzen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="js-action" data-type="de-release-selected" data-url="{$Page->getLink()}">
                            <i class="pg-icon-de-release-selected"></i>
                            &quot;Veröffentlicht&quot; entfernen{*$Captions->GetMessageString('DeleteSelected')*}
                        </a>
                    </li>
                {/if}

            </ul>
        </div>
    </div>
{/if}
