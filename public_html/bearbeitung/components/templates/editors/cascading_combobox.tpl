<table {include file="editors/editor_options.tpl" Editor=$Editor} class="pgui-cascading-editor">
    <tbody>
    {foreach from=$Editor->getLevels() item=LevelEditor name=Editors}
    <tr>
        <td><span>{$LevelEditor->getCaption()}</span></td>
        <td>
            {if $LevelEditor->getNestedInsertFormLink()}
            <div class="input-group" style="width: 100%">
            {/if}
                <select
                    class="form-control {if $LevelEditor->getNestedInsertFormLink()}form-control-nested-form{/if}"
                    data-id="{$FormId}_{$LevelEditor->GetName()}"
                    name="{$LevelEditor->getName()}"
                    {if $LevelEditor->getParentLevelName()}
                        data-parent-level="{$FormId}_{$LevelEditor->getParentLevelName()}"
                        data-parent-link-field-name="{$LevelEditor->getParentLinkFieldName()}"
                    {/if}
                    data-url="{$LevelEditor->getDataURL()}"
                    {if $smarty.foreach.Editors.last}
                    data-editor-main="true"
                    {$ViewData.Validators.InputAttributes}
                    {/if}
                    value="{$LevelEditor->getValue()}">
                    <option value="">{$Captions->GetMessageString('PleaseSelect')}</option>
                    {foreach key=value item=displayValue from=$LevelEditor->getValues()}
                        {if $value !== ''}
                            <option value="{$value}"{if $LevelEditor->getValue() == $value} selected{/if}>{$displayValue}</option>
                        {/if}
                    {/foreach}
                </select>
                {include file='editors/nested_insert_button.tpl' NestedInsertFormLink=$LevelEditor->getNestedInsertFormLink() LookupDisplayFieldName=$LevelEditor->GetCaptionFieldName()}
            {if $LevelEditor->getNestedInsertFormLink()}
            </div>
            {/if}
        </td>
    </tr>
    {/foreach}
    </tbody>
</table>