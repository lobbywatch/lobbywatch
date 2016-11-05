<table {include file="editors/editor_options.tpl" Editor=$Editor} class="pgui-multilvevel-autocomplete">
<tbody>
{foreach from=$Editor->getLevels() item=LevelEditor name=Editors}
    <tr>
        <td><span>{$LevelEditor->getCaption()}</span></td>
        <td>
            <input
                type="hidden"
                data-id="{$FormId}_{$LevelEditor->GetName()}"
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
                {if $LevelEditor->getParentEditor()}
                    data-parent-autocomplete="{$FormId}_{$LevelEditor->getParentEditor()}"
                {/if}
                data-url="{$LevelEditor->getDataURL()}"
                {if $smarty.foreach.Editors.last}
                    data-multileveledit-main="true"
                    {$Validators.InputAttributes}
                {/if}
                {if $LevelEditor->getFormatResult()}
                    data-format-result="{$LevelEditor->getFormatResult()|escape}"
                {/if}
                {if $LevelEditor->getFormatSelection()}
                    data-format-selection="{$LevelEditor->getFormatSelection()|escape}"
                {/if}
                value="{$LevelEditor->getValue()}"
                data-init-text="{$LevelEditor->getDisplayValue()}"
                {if $smarty.foreach.Editors.last}
                    {$ViewData.Validators.InputAttributes}
                {/if}
            />
        </td>
    </tr>
{/foreach}
</tbody>
</table>