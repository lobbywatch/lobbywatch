<div class="row">
{foreach item=Group from=$Grid.FormLayout->getGroups()}
    {if count($Group->getRows()) > 0}
    <{if $isViewForm}div{else}fieldset{/if} class="col-md-{$Group->getWidth()}">
        {if $Group->getName()}<legend>{$Group->getName()}</legend>{/if}
        {foreach item=Row from=$Group->getRows()}
            <div class="row">
                {foreach item=Col from=$Row->getCols()}
                    {assign var='ColumnViewData' value=$Col->getViewData()}
                    {assign var='Editor' value=$ColumnViewData.EditorViewData.Editor}

                    {if $Editor}
                        {assign var='editorId' value=$Grid.FormId|cat:'_'|cat:$Editor->getName()}
                    {/if}

                    <div class="form-group {if $Grid.FormLayout->isHorizontal()}col-sm-{$Col->getLabelWidth()} form-group-label{else}col-sm-{$Col->getWidth()}{/if}"{if $Editor and not $Editor->getVisible()} style="display: none"{/if}>

                        {if $Grid.FormLayout->isHorizontal() or not $ColumnViewData.EditorViewData or not $Editor->isInlineLabel()}
                            {include file='forms/field_label.tpl' editorId=$editorId}
                        {/if}

                    {if $Grid.FormLayout->isHorizontal()}
                        </div>
                        <div class="form-group col-sm-{$Col->getInputWidth()}"{if $Editor and not $Editor->getVisible()} style="display: none"{/if}>
                    {/if}

                        {if not $isViewForm}
                            <div class="col-input" style="width:100%;max-width:{$Editor->getMaxWidth()}" data-column="{$ColumnViewData.FieldName}">
                                {include file='editors/'|cat:$Editor->getEditorName()|cat:'.tpl' Editor=$Editor ViewData=$ColumnViewData.EditorViewData FormId=$Grid.FormId id=$editorId}

                                {if not $Grid.FormLayout->isHorizontal() and $Editor->isInlineLabel()}
                                    {include file='forms/field_label.tpl' editorId=$editorId}
                                {/if}
                            </div>
                        {else}
                            {assign var='ColumnName' value=$Col->getName()}
                            {assign var='CellEditUrl' value=$Grid.CellEditUrls[$ColumnName]}

                            <div class="form-control-static{if $CellEditUrl} pgui-cell-edit{/if}"{if $CellEditUrl} data-column-name="{$ColumnName}" data-edit-url="{$CellEditUrl}"{/if}>
                                {$Col->getDisplayValue($Renderer)}
                            </div>
                        {/if}
                    </div>
                {/foreach}
            </div>
        {/foreach}
    </{if $isViewForm}div{else}fieldset{/if}>
    {/if}
{/foreach}
</div>

{if not $isViewForm}
    <div class="row">
        <div class="{if $Grid.FormLayout->isHorizontal()}col-sm-9 col-sm-offset-3{else}col-md-12{/if}">
            <span class="required-mark">*</span> - {$Captions->GetMessageString('RequiredField')}
        </div>
    </div>

    {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
        <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}" />
    {/foreach}
{/if}