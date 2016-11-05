<textarea
    {include file="editors/editor_options.tpl" Editor=$Editor}
    class="form-control"
    {if $Editor->getPlaceholder()}
        placeholder="{$Editor->getPlaceholder()}"
    {/if}
    {if $Editor->GetColumnCount()}
        cols="{$Editor->GetColumnCount()}"
    {/if}
    {if $Editor->GetRowCount()}
        rows="{$Editor->GetRowCount()}"
    {/if}>{$Editor->GetValue()}</textarea>