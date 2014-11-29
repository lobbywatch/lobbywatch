<table
    data-editor="true"
    data-editor-class="{$MultilevelEditor->GetDataEditorClassName()}"
    data-editable="true"
    data-field-name="{$MultilevelEditor->GetFieldName()}"
    class="pgui-multilvevel-autocomplete" style="">
<tbody>
{foreach from=$Editors item=Editor name=Editors}
    <tr>
        <td><span>{$Editor.Caption}</span></td>
        <td>
            <select data-placeholder="{$Captions->GetMessageString('PleaseSelect')}"
                name="{$Editor.Name}"
                search
                multi-autocomplete="true"
                {if !$MultilevelEditor->getEnabled()}
                    disabled="disabled"
                {/if}
                {if $MultilevelEditor->GetReadonly()}
                    disabled="disabled"
                {/if}
                {if $Editor.ParentEditor}
                    parent-autocomplete="{$Editor.ParentEditor}"
                {/if}
                data-url="{$Editor.DataURL}"
                {if $smarty.foreach.Editors.last}
                    data-multileveledit-main="true"
                    {$Validators.InputAttributes}
                {/if}
            ><option value="{$Editor.Value}">{$Editor.DisplayValue}</option>
            </select>
        </td>
    </tr>
{/foreach}
</tbody>
</table>
