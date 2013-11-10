{if $Page->GetShowPageList()}
{capture assign="SideBar"}

    {$PageList}

{/capture}
{/if}

{capture assign="ContentBlock"}
    {$Grid}
{/capture}

{capture assign="DebugFooter"}{$Variables}{/capture}

{assign var="JavaScriptMain" value=$App.MainScript}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}


{* Base template *}
{include file=$LayoutTemplateName}

