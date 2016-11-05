<div class="nav">
    {$PageNavigator->GetCaption()}:
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {foreach item=PageNavigatorPage from=$PageNavigatorPages}
                {if $PageNavigatorPage->IsCurrent()} {$PageNavigatorPage->GetPageCaption()} {/if}
            {/foreach}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            {foreach item=PageNavigatorPage from=$PageNavigatorPages}
                <li{if $PageNavigatorPage->IsCurrent()} class="active"{/if}>
                    <a href="{$PageNavigatorPage->GetPageLink()}">{$PageNavigatorPage->GetPageCaption()}</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>