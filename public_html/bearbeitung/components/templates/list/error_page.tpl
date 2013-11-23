{capture assign="SideBar"}
    {$PageList}
{/capture}

{capture assign="ContentBlock"}

<div class="alert alert-error">
<h3>{$Captions->GetMessageString('ErrorsDuringDataRetrieving')}</h3>
{$ErrorMessage}

{if ($DisplayDebugInfo eq 1)}
<hr/>
<h3>Additional exception info:</h3>
<strong>File:</strong> {$File} <br/>
<strong>Line:</strong> {$Line} <br/>
<strong>Trace:</strong> {$Trace}
{/if}

</div>
{/capture}

{* Base template *}
{include file="common/layout.tpl"}