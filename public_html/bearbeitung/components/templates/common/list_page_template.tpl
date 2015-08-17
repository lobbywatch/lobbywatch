{capture assign="Scripts"}
    {$App.ClientSideEvents.OnBeforeLoadEvent}
{literal}
    $(function() {
{/literal}{$App.ClientSideEvents.OnAfterLoadEvent}{literal}
    });
{/literal}
{/capture}

{capture name="SideBar"}{/capture}

{assign var="JavaScriptMain" value="pgui.list-page-main"}

{assign var="HideSideBarByDefault" value=$HideSideBarByDefault}

{if $Page->GetShowPageList()}
    {capture assign="SideBar"}
        {$PageList}
    {/capture}
{/if}

{capture assign="DebugFooter"}{$Variables}{/capture}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}

{* Base template *}
{include file=$LayoutTemplateName}