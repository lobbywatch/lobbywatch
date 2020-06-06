<div class="row">
    {if $Grid.FormLayout->tabsEnabled()}
        {include file='forms/form_tabs.tpl' NavStyle=$Grid.FormLayout->getTabbedNavigationStyle() TabType=$Grid.FormLayout->getTabType()}
    {else}
        {include file='forms/form_sheet_layout.tpl' FormSheet=$Grid.FormLayout}
    {/if}
</div>
