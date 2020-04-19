<table {include file="editors/editor_options.tpl" Editor=$Editor} class="pgui-cascading-editor">
<tbody>
{foreach from=$Editor->getLevels() item=LevelEditor name=Editors}
    <tr>
        <td><span>{$LevelEditor->getCaption()}</span></td>
        <td>
            <div class="input-group" style="width: 100%">
            <input
                type="hidden"
                {if $LevelEditor->getNestedInsertFormLink()}
                class="form-control-nested-form"
                {/if}
                data-id="{$FormId}_{$LevelEditor->getName()}"
                data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
                name="{$LevelEditor->getName()}"
                data-minimal-input-length="{$Editor->getMinimumInputLength()}"
                {if !$Editor->getEnabled()}
                    disabled="disabled"
                {/if}
                {if $Editor->getAllowClear()}
                    data-allowClear="true"
                {/if}
                {if $Editor->GetReadOnly()}
                    readonly="readonly"
                {/if}
                {if $LevelEditor->getParentLevelName()}
                    data-parent-level="{$FormId}_{$LevelEditor->getParentLevelName()}"
                    data-parent-link-field-name="{$LevelEditor->getParentLinkFieldName()}"
                {/if}
                data-url="{$LevelEditor->getDataURL()}"
                {if $smarty.foreach.Editors.last}
                    data-multileveledit-main="true"
                    {$ViewData.Validators.InputAttributes}
                {/if}
                {if $LevelEditor->getFormatResult()}
                    data-format-result="{$LevelEditor->getFormatResult()|escape}"
                {/if}
                {if $LevelEditor->getFormatSelection()}
                    data-format-selection="{$LevelEditor->getFormatSelection()|escape}"
                {/if}
                value="{$LevelEditor->getValue()}"
                data-init-text="{$LevelEditor->getDisplayValue()}"
            />
                {include file='editors/nested_insert_button.tpl' NestedInsertFormLink=$LevelEditor->getNestedInsertFormLink() LookupDisplayFieldName=$LevelEditor->GetCaptionFieldName()}
            </div>
        </td>
    </tr>
{/foreach}
</tbody>
</table>