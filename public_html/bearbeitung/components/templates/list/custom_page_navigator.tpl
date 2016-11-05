<div class="pgui-pagination">
    <ul class="pagination">{strip}
        <li>
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

