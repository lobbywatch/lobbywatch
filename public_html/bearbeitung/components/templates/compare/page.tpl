{capture assign="ContentBlock"}
    {include file="page_header.tpl" pageTitle=$Page->GetTitle()}
    {$Grid}
{/capture}

{* Base template *}
{include file=$layoutTemplate}