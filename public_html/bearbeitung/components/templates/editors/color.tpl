<input
    {include file="editors/editor_options.tpl" Editor=$Editor}
    class="form-control"
    type="color"
    value="{$Editor->GetValue()}"
    {style_block}
        min-width: 100px;
        padding: 0;
    {/style_block}
>