{capture assign="SideBar"}
    {$PageList}
{/capture}

{capture assign="ContentBlock"}

<br>

<div class="alert alert-error">
<strong>{$Captions->GetMessageString('ErrorsDuringDataRetrieving')}</strong>
    <br>
    <br>
{$ErrorMessage}
</div>
{/capture}

{* Base template *}
{include file="common/layout.tpl"}