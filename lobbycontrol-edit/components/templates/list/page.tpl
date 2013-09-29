{capture assign="Scripts"}
    {$App.ClientSideEvents.OnBeforeLoadEvent}
{literal}
    $(function() {
        {/literal}{$App.ClientSideEvents.OnAfterLoadEvent}{literal}
    });
{/literal}
{/capture}

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

{if $Page->GetShowPageList()}
{capture assign="SideBar"}

    {$PageList}

{/capture}
{/if}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}

{capture assign="DebugFooter"}{$Variables}{/capture}

{* Base template *}
{include file="common/list_page_template.tpl"}