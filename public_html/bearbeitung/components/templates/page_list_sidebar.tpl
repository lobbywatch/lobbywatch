<div class="sidebar-nav">

    <ul class="nav nav-pills nav-stacked">
        {$BeforeSidebarList}
        {foreach item=Group key=index from=$List.Groups}
            {assign var=GroupCaption value=$Group->getCaption()}

            {if $GroupCaption != 'Default'}
                <li class="sidebar-nav-head">
                    {assign var='GroupIsActive' value=false}
                    {foreach item=PageListPage from=$List.Pages}
                        {if $PageListPage.GroupName == $GroupCaption and $PageListPage.IsCurrent}
                            {assign var='GroupIsActive' value=true}
                        {/if}
                    {/foreach}

                    <span data-toggle="collapse" data-target="#menu{$index}" class="sidebar-nav-item{if not $GroupIsActive} collapsed{/if}">
                        <i class="icon-folder-o"></i>
                        {$GroupCaption}
                        <span class="caret"></span>
                    </span>

                    <ul class="nav nav-pills nav-stacked collapse{if $GroupIsActive} in{/if}" id="menu{$index}">
            {/if}
            {foreach item=PageListPage from=$List.Pages}
                {if $PageListPage.GroupName == $GroupCaption}

                    {if $PageListPage.BeginNewGroup}
                        <li class="nav-divider"></li>
                    {/if}

                    {if $PageListPage.IsCurrent}
                        <li class="active{if $PageListPage.ClassAttribute} {$PageListPage.ClassAttribute}{/if}" title="{$PageListPage.Hint}">
                            <span class="sidebar-nav-item">
                                {$PageListPage.Caption}
                                {if $List.RSSLink}
                                    <a href="{$List.RSSLink}" class="pull-right link-icon">
                                        <i class="icon-rss"></i>
                                    </a>
                                {/if}
                            </span>
                        </li>
                    {else}
                        <li{if $PageListPage.ClassAttribute} class="{$PageListPage.ClassAttribute}"{/if}>
                            <a class="sidebar-nav-item" href="{$PageListPage.Href|escapeurl}" title="{$PageListPage.Hint}"{if $PageListPage.Target} target="{$PageListPage.Target}"{/if}>
                                {$PageListPage.Caption}
                            </a>
                        </li>
                    {/if}

                {/if}
            {/foreach}
            {if $GroupCaption != 'Default'}
                    </ul>
                </li>
            {/if}
        {/foreach}
        {$AfterSidebarList}
    </ul>

</div>