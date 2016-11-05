{assign var="HideSideBarByDefault" value=$HideSideBarByDefault}

{capture assign="DebugFooter"}{$Variables}{/capture}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}

{* Base template *}
{include file=$LayoutTemplateName}