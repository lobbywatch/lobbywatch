{assign var="ContentBlockClass" value="col-md-8 col-md-offset-2"}
{capture assign="ContentBlock"}
<div class="alert alert-danger">
    <h3>{$ErrorTitle}</h3>

    {$ErrorContent}

    {if ($DisplayDebugInfo eq 1)}
        <hr>
        <h3>Additional exception info:</h3>
        <strong>File:</strong> {$File}<br>
        <strong>Line:</strong> {$Line}
        <p>
            <strong>Trace:</strong><br>
            <pre>{$Trace}</pre>
        </p>
    {/if}
</div>
{/capture}

{include file="common/layout.tpl"}
