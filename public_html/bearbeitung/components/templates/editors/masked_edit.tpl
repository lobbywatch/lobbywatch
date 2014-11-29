<input
    {include file="editors/editor_options.tpl" Editor=$MaskedEdit}
    class="sm_text"
    masked="true"
    mask="{$MaskedEdit->GetMask()}"
    type="text"
    value="{$MaskedEdit->GetValue()}"
>
<div class="masked-edit-hint">{$MaskedEdit->GetHint()}</div>