{capture assign="ContentBlock"}
    {$Grid}
{/capture}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}


{* Base template *}
{include file=$LayoutTemplateName}