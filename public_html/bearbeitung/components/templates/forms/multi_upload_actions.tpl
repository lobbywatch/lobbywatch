{capture assign='ActionsContent'}
<div class="btn-group">
    <button class="btn btn-primary fileinput-upload">
        {$Captions->GetMessageString('Upload')}
    </button>
</div>
<div class="btn-group">
    <a class="btn btn-default js-close-form" href="{$Grid.CancelUrl}">{$Captions->GetMessageString('Cancel')}</a>
</div>
{/capture}
{include file="forms/actions_wrapper.tpl" ActionsContent=$ActionsContent top=$top isHorizontal=true}
