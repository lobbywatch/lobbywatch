{capture assign='TextEditorContent'}

    <input
        {include file="editors/editor_options.tpl"}
        class="form-control"
        value="{$Editor->GetHTMLValue()}"
        {if $Editor->getPlaceholder()}
            placeholder="{$Editor->getPlaceholder()}"
        {/if}
        {if $Editor->GetPasswordMode()}
            type="password"
        {else}
            type="text"
        {/if}
        {if $Editor->GetMaxLength()}
            maxlength="{$Editor->GetMaxLength()}"
        {/if}
        {if $AdditionalProperties}
            {$AdditionalProperties}
        {/if}
    >

{/capture}

{include file="editors/text_editor_wrapper.tpl" TextEditorContent=$TextEditorContent}

