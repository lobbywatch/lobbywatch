<div class="sidebar-nav">
    <ul class="nav nav-list pg-page-list">

        <li class="nav-header">{$Captions->GetMessageString('PageList')}</li>

        {foreach item=PageListPage from=$List.Pages}
            {if $PageListPage.BeginNewGroup}<li class="divider"></li>{/if}

            {if $PageListPage.IsCurrent}
                <li class="active">

                    <a href="#" title="{$PageListPage.Hint}" onclick="return false;" style="cursor: default;">
                        <i class="page-list-icon"></i>
                        {$PageListPage.Caption}
                        {if $List.RSSLink}
                        <span class="pull-right" style="cursor: pointer;" onclick="window.location.href={jsstring value=$List.RSSLink};">
                            <i class="pg-icon-rss"></i>
                        </span>
                        {/if}
                    </a>

                </li>
            {else}
                <li><a href="{$PageListPage.Href|escapeurl}" title="{$PageListPage.Hint}">
                    <i class="page-list-icon"></i>
                    {$PageListPage.Caption}
                </a></li>
            {/if}
        {/foreach}
    </ul>
</div>