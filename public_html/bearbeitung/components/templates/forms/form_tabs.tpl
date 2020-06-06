<div class="form-tabs-container">
    <ul class="nav {$NavStyle}" role="tablist">
        {foreach from=$Grid.FormLayout->getNonEmptyTabs() key=TabNum item=Tab name=Tabs}
            {assign var=TabId value="`$Grid.FormId`_tab`$TabNum`"}
            <li role="presentation" {if $smarty.foreach.Tabs.first}class="active"{/if}>
                <a href="#{$TabId}" aria-controls="{$TabId}" role="tab" data-toggle="{$TabType}">
                    {$Tab->getName()}
                </a>
            </li>
        {/foreach}
    </ul>
</div>

<div class="tab-content">
    {foreach from=$Grid.FormLayout->getNonEmptyTabs() key=TabNum item=Tab name=Tabs}
        {assign var=TabId value="`$Grid.FormId`_tab`$TabNum`"}
        <div id="{$TabId}" class="tab-pane{if $smarty.foreach.Tabs.first} active in{/if}" role="tabpanel">
            {include file='forms/form_sheet_layout.tpl' FormSheet=$Tab}
        </div>
    {/foreach}
</div>
