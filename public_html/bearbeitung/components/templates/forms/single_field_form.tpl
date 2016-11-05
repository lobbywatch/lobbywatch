<form id="{$Grid.FormId}" enctype="multipart/form-data" method="POST" action="{$Grid.FormAction}">

    {assign var='ColumnViewData' value=$Column->getViewData()}
    <div class="col-input">
        <div class="form-group">
            {include file='editors/'|cat:$ColumnViewData.EditorViewData.Editor->getEditorName()|cat:'.tpl' Editor=$ColumnViewData.EditorViewData.Editor ViewData=$ColumnViewData.EditorViewData FormId=$Grid.FormId isSingleFieldForm=true}

        </div>

        <div style="display: none">
            {foreach item=CurrentColumn key=CurrentColumnName from=$Columns}
                {if $CurrentColumnName != $Column->getName()}
                    {assign var='CurrentColumnViewData' value=$CurrentColumn->getViewData()}
                    {include file='editors/'|cat:$CurrentColumnViewData.EditorViewData.Editor->getEditorName()|cat:'.tpl' Editor=$CurrentColumnViewData.EditorViewData.Editor ViewData=$CurrentColumnViewData.EditorViewData FormId=$Grid.FormId isSingleFieldForm=true}
                {/if}
            {/foreach}            
        </div>
    </div>

    <div class="form-error-container"></div>

    <span class="pull-right">
        <button type="button" class="js-cancel btn btn-default">{$Captions->GetMessageString('Cancel')}</button>
        <button type="button" class="js-save btn btn-primary">{$Captions->GetMessageString('Save')}</button>
    </span>

    <div class="clearfix"></div>

    {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
        <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}" />
    {/foreach}

    {include file='forms/form_scripts.tpl'}

</form>