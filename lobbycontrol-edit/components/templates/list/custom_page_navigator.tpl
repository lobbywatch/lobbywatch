<div class="pgui-pagination">
    <div class="pagination">
        <ul>{strip}
            <li class="disabled">
                <a>{$PageNavigator->GetCaption()}:</a>
            </li>
            {/strip}{foreach item=PageNavigatorPage from=$PageNavigatorPages}{strip}
                <li {if $PageNavigatorPage->IsCurrent()}class="active"{/if}>
                    <a href="{$PageNavigatorPage->GetPageLink()|escapeurl}">
                        {$PageNavigatorPage->GetPageCaption()}
                    </a>
                </li>
            {/strip}{/foreach}
        </ul>
    </div>
</div>

