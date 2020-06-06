{assign var="ContentBlockClass" value="col-md-8 col-md-offset-2"}

{capture assign="ContentBlock"}
    <div class="alert alert-danger">
        <h4>{$Message}</h4>
        {$Description}
    </div>
{/capture}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}

{* Base template *}
{include file="common/layout.tpl"}
