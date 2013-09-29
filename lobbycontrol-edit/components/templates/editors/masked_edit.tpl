    <input
        data-editor="true"
        data-editor-class="MaskEdit"
        data-field-name="{$Editor->GetFieldName()}"
        data-editable="true"
        masked="true"
        mask="{$Editor->GetMask()}"
        class="sm_text"
        type="text"
        id="{$Editor->GetName()}"
        name="{$Editor->GetName()}"
        value="{$Editor->GetValue()}"
        {$Validators.InputAttributes}
        {style_block}
            {$Editor->GetCustomAttributes()}
        {/style_block}
    >
    <div class="masked-edit-hint">{$Editor->GetHint()}</div>