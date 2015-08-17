{capture assign="ContentBlock"}
    <div class="page-header">
        <h1>
            {$Page->GetCaption()}
        </h1>
        {include file="export-button.tpl" Items=$Page->GetExportButtonsViewData()}
    </div>

    {include file="page_description_block.tpl" Description=$Page->GetGridHeader()}

    {$PageNavigator}

    {$Grid}

    {$PageNavigator2}
{/capture}

{* Base template *}
{include file="common/list_page_template.tpl"}