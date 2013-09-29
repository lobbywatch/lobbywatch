{capture assign="SideBar"}{/capture}

{capture assign="ContentBlock"}

<div class="row-fluid">

    <div class="span2"></div>

    <div class="span8">
        <div class="alert">
            <strong>{$Message}</strong>
            <br>
            {$Description}
        </div>
    </div>

</div>

{/capture}

{capture assign="Footer"}
    {$Page->GetFooter()}
{/capture}

{* Base template *}
{include file="common/layout.tpl"}