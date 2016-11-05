{capture assign="ContentBlock"}

    {$BeforePageList}

    {assign var=ListObj value=$Page->getReadyPageList()}
    {assign var=List value=$ListObj->GetViewData()}
    {assign var=SelectedGroup value=$Page->getSelectedGroup()}

    {foreach item=Group from=$List.Groups}
        {if is_null($SelectedGroup) or $Group == $SelectedGroup}
            <div class="row pgui-home-row{if $Group == 'Default'} pgui-home-group-default{/if}">
            {if $Group != 'Default'}
                <h2 class="col-md-12 col-sm-12 col-xs-12 pgui-home-group">{$Group}</h2>
            {/if}

            {foreach item=PageListPage from=$List.Pages}

                {if $PageListPage.GroupName == $Group}
                    {if $PageListPage.BeginNewGroup}
                        </div>
                        <hr>
                        <div class="row pgui-home-group">
                    {/if}

                    <div class="col-md-4 col-sm-6 col-xs-12 pgui-home-col">
                        <div class="pgui-home-item-wrapper">
                            <div class="pgui-home-item{if $PageListPage.ClassAttribute} {$PageListPage.ClassAttribute}{/if}">
                                <a href="{$PageListPage.Href|escapeurl}" title="{$PageListPage.Hint}">
                                    {$PageListPage.Caption}
                                </a>

                                {if $PageListPage.Description}
                                    <p>{$PageListPage.Description}</p>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/if}
            {/foreach}
            </div>
        {/if}
    {/foreach}

    {$AfterPageList}

{/capture}

{* Base template *}
{include file=$layoutTemplate}