{capture assign="ContentBlock"}
    {$Renderer->Render($RegistrationForm)}
{/capture}

{* Base template *}
{include file=$layoutTemplate}