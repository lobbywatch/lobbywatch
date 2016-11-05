{capture assign="ContentBlock"}
    {$Renderer->Render($LoginControl)}
{/capture}

{* Base template *}
{include file=$layoutTemplate}