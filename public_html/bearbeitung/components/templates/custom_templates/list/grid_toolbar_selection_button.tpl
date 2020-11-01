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

<div class="btn-group">
    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" title="{$Captions->GetMessageString('Favorite Filters')}">
        <i class="icon-filter-alt"></i>
        <span class="{$spanClasses}">{$Captions->GetMessageString('Favorite Filters')}</span>
        <span class="caret"></span>
    </a>

    <ul class="dropdown-menu" style="width:25em;">
      {php}
        $this->assign('loop', [1,2,3,4,5]);
      {/php}
      {foreach from=$loop key="key" item="i"}
          {assign var="favorite_filter_key" value="`$DataGrid.SelectionId`filter_builder_favorite-filter-`$i`"}
          {assign var="save_favorite_filter_key" value="save-favorite-filter-`$i`"}
          {php}
            $i = $this->get_template_vars("i");
            $favorite_filter_key = $this->get_template_vars("favorite_filter_key");
            $save_favorite_filter_key = $this->get_template_vars("save_favorite_filter_key");
            // print("favorite_filter_key=$favorite_filter_key\n");
            // $all = $this->get_template_vars();
            // foreach ($all as $key => $value) print("KEYYY: $key=" /*. substr($value, 0 , 30)*/ . "\n");
            // foreach ($all['DataGrid'] as $key => $value) print("KEYYYG: $key=" /*. substr($value, 0 , 30)*/ . "\n");
            // $_SESSION for accessing session data
            // Cookies are only available the next call, use $_POST directly
            $this->assign('favorite_filter_key_available', isset($_POST[$save_favorite_filter_key]) || isset($_COOKIE[$favorite_filter_key]));
            $favorite_filter_name_from_post = htmlspecialchars(trim($_POST[$save_favorite_filter_key . "-name"]), ENT_HTML5 | ENT_QUOTES) ?? null;
            $favorite_filter_name_from_post = !empty($favorite_filter_name_from_post) ? $favorite_filter_name_from_post : null;
            $this->assign('favorite_filter_name', $favorite_filter_name_from_post ?? htmlspecialchars(trim($_COOKIE[$favorite_filter_key . "_name"]), ENT_HTML5 | ENT_QUOTES) ?? "");
          {/php}
          {if $favorite_filter_key_available}
            <li>
                <form action="" method="post">
                  <input type="submit" name="restore-favorite-filter-{$i}" value="Restore filter #{$i}: {$favorite_filter_name}">
                </form>
            </li>
          {/if}
      {/foreach}
      <li class="divider"></li>
      {foreach from=$loop key="key" item="i"}
          {assign var="favorite_filter_key" value="`$DataGrid.SelectionId`filter_builder_favorite-filter-`$i`"}
          {assign var="save_favorite_filter_key" value="save-favorite-filter-`$i`"}
          {php}
            $i = $this->get_template_vars("i");
            $favorite_filter_key = $this->get_template_vars("favorite_filter_key");
            $save_favorite_filter_key = $this->get_template_vars("save_favorite_filter_key");
            $favorite_filter_name_from_post = htmlspecialchars(trim($_POST[$save_favorite_filter_key . "-name"]), ENT_COMPAT) ?? null;
            $favorite_filter_name_from_post = !empty($favorite_filter_name_from_post) ? $favorite_filter_name_from_post : null;
            $this->assign('favorite_filter_name', $favorite_filter_name_from_post ?? htmlspecialchars(trim($_COOKIE[$favorite_filter_key . "_name"]), ENT_COMPAT) ?? "");
          {/php}
          <li>
              <form action="" method="post">
                <input name="{$save_favorite_filter_key}-name" placeholder="Filter name" value="{$favorite_filter_name}">
                <input type="submit" name="{$save_favorite_filter_key}" value="Save filter #{$i}">
              </form>
          </li>
      {/foreach}
    </ul>
</div>
