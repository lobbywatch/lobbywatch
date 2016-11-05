<div class="pgui-pagination" data-total-record-count="{$PageNavigator->GetRowCount()}">

    <ul class="pagination">
        <li{if not $PageNavigator->HasPreviousPage()} class="disabled"{/if}>
            <a href="{$PageNavigator->GetPreviousPageLink()|escapeurl}" aria-label="Previous" class="pgui-pagination-prev">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        {foreach item=PageNavigatorPage from=$PageNavigatorPages}{strip}
            {if $PageNavigatorPage->GetPageLink()}
                <li class="{if $PageNavigatorPage->IsCurrent()}active{else}hidden-xs{/if}">
                    <a href="{$PageNavigatorPage->GetPageLink()|escapeurl}" title="{$PageNavigatorPage->GetHint()}"
                       {if $PageNavigatorPage->HasShortCut()} data-pgui-shortcut="{$PageNavigatorPage->GetShortCut()}"{/if}>
                        {$PageNavigatorPage->GetPageCaption()}
                    </a>
                </li>
            {else}
                <li class="pagination-spacer hidden-xs">
                    <a href="#" title="{$PageNavigatorPage->GetHint()}" onclick="return false;">
                        {$PageNavigatorPage->GetPageCaption()}
                    </a>
                </li>
            {/if}
        {/strip}{/foreach}
        <li{if not $PageNavigator->HasNextPage()} class="disabled"{/if}>
            <a href="{$PageNavigator->GetNextPageLink()|escapeurl}" aria-label="Next" class="pgui-pagination-next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>

</div>