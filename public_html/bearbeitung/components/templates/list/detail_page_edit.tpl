{capture assign="ContentBlock"}
    <div class="page-header">
        <h1>
            {$Page->GetCaption()}
        </h1>
    {include file="export-button.tpl" Items=$Page->GetExportButtonsViewData()}
    </div>

    {include file="page_description_block.tpl" Description=$Page->GetGridHeader()}

<h5>{$Captions->GetMessageString('MasterRecord')}
    (<a href="{$Page->GetParentPageLink()|escapeurl}">{$Captions->GetMessageString('ReturnFromDetailToMaster')}</a>)
</h5>

{$MasterGrid}
<br />

    {if count($SiblingDetails) > 1}
        <ul class="nav nav-tabs">
            {foreach from=$SiblingDetails item=SiblingDetail name=SiblingDetailsSection}
                <li class="{if $DetailPageName == $SiblingDetail.Name}active{/if}">
                    <a href="{$SiblingDetail.Link|escapeurl}">
                        {$SiblingDetail.Caption}
                    </a>
                </li>
            {/foreach}
        </ul>
    {/if}


    {$PageNavigator}

    {$Grid}

    {$PageNavigator2}
{/capture}

{* Base template *}
{include file="common/list_page_template.tpl"}