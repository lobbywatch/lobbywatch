<table
    {include file="editors/editor_options.tpl" Editor=$MultilevelEditor}
    class="pgui-multilvevel-autocomplete" style="">
<tbody>

{foreach from=$MultilevelEditor->getLevels() item=Editor name=Editors}
    <tr>
        <td><span>{$Editor->getCaption()}</span></td>
        <td>
            <input
                type="hidden"
                data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
                name="{$Editor->getName()}"
                search
                multi-autocomplete="true"
                data-minimal-input-length="{$MultilevelEditor->getMinimumInputLength()}"
                {if !$MultilevelEditor->getEnabled()}
                    disabled="disabled"
                {/if}
                {if $MultilevelEditor->getAllowClear()}
                    data-allowClear="true"
                {/if}
                {if $MultilevelEditor->GetReadOnly()}
                    readonly="readonly"`
                {/if}
                {if $Editor->getParentEditor()}
                    parent-autocomplete="{$Editor->getParentEditor()}"
                {/if}
                data-url="{$Editor->getDataURL()}"
                {if $smarty.foreach.Editors.last}
                    data-multileveledit-main="true"
                    {$Validators.InputAttributes}
                {/if}
                {if $Editor->getFormatResult()}
                    data-format-result="{$Editor->getFormatResult()|escape}"
                {/if}
                {if $Editor->getFormatSelection()}
                    data-format-selection="{$Editor->getFormatSelection()|escape}"
                {/if}
                value="{$Editor->getValue()}"
                data-init-text="{$Editor->getDisplayValue()}"
            />
        </td>
    </tr>
{/foreach}
</tbody>
</table>
