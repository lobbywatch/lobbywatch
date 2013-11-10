<!-- <Pages> -->
<div class="page_navigator">
{foreach item=SubPageNavigator from=$PageNavigator->GetPageNavigators()}
<div class="page_navigator">
{$Renderer->Render($SubPageNavigator)}
</div>
{/foreach}
</div>
<!-- </Pages> -->
