<table
        data-editor="true"
        data-editor-class="MultiLevelAutocomplete"
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
                {if $Editor.ParentEditor}
                    parent-autocomplete="{$Editor.ParentEditor}"
                {/if}
                    data-url="{$Editor.DataURL}"
                {if $smarty.foreach.Editors.last}
                    data-multileveledit-main="true"
                    {$Validators.InputAttributes}
                {/if}
                    ><option value="{$Editor.Value}">{$Editor.DisplayValue}</option></select>

        </td>
    </tr>
{/foreach}
</tbody></table>
{*
{if $RenderText}
{if !$MultilevelEditor->GetReadOnly()}
{foreach from=$Editors item=Editor name=Editors}
    <div
        {n}data-editor="true"
        {n}data-editor-class="MultiLevelAutocomplete"
        {n}data-editable="true"
        {n}data-field-name="{$MultilevelEditor->GetFieldName()}"
        {n}class="pgui-autocomplete-container-container" {style_block} {$MultilevelEditor->GetCustomAttributes()} {/style_block}>
        <div class="pgui-caption">{$Editor.Caption}:</div>

        <table class="pgui-autocomplete-container">
            <tbody>
                <tr>
                    <td>
                        <input
                            type="text"
                            id="{$Editor.Name}_selector"
                            value="{$Editor.DisplayValue}"
                            class="pgui-autocomplete"

                            {if $Editor.ParentEditor != null}
                            parent-autocomplete="#{$Editor.ParentEditor}_selector"
                            {/if}

                            autocomplete="true"
                            data-url="{$Editor.DataURL}"
                            copy-id-to="#{$Editor.Name}"
                                />
                        <input type="hidden" id="{$Editor.Name}" name="{$Editor.Name}" value="{$Editor.Value}"
                            {if $smarty.foreach.Editors.last}
                                {$Validators.InputAttributes}
                            {/if}/>
                    </td>
                    <td class="button">
                        <div class="pgui-ml-autocomplete-button"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
{/foreach}
{else}

<div class="pgui-autocomplete-readonly">
{foreach from=$Editors item=Editor}
    <div class="pgui-autocomplete-level">
        <span class="caption">{$Editor.Caption}:</span>
        <span class="value">{$Editor.DisplayValue}</span>
    </div>
{/foreach}
</div>

{/if}
{/if}
*}