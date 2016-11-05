<input
    {include file="editors/editor_options.tpl" Editor=$Editor}
    class="form-control"
    masked="true"
    mask="{$Editor->GetMask()}"
    type="text"
    value="{$Editor->GetValue()}"
>
<div class="masked-edit-hint">{$Editor->GetHint()}</div>