<!-- <Pages> -->
<div class="page_navigator">
    {foreach item=SubPageNavigator from=$PageNavigator->GetPageNavigators()}
        {$Renderer->Render($SubPageNavigator)}
    {/foreach}
</div>
<!-- </Pages> -->
