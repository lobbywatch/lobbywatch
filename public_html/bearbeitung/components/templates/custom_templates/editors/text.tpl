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

    {if $Editor->GetHTMLValue()|strpos:'http' === 0}<!-- Check starts with http -->
        <a href="{$Editor->GetHTMLValue()}" title="Open this URL in a new tab" target="_blank">Follow link: {$Editor->GetHTMLValue()}</a>
    {/if}
    {if $Editor->GetHTMLValue()|strpos:'CHE-' === 0}<!-- Check starts with CHE- -->
        <a href="https://www.uid.admin.ch/Detail.aspx?uid_id={$Editor->GetHTMLValue()}" title="Show this UID on UID-Register@BFS in a new tab" target="_blank">UID-Register@BFS</a> |
        <a href="https://zefix.ch/WebServices/Zefix/Zefix.asmx/SearchFirm?id={$Editor->GetHTMLValue()}" title="Show this UID on Zefix in a new tab" target="_blank">Zefix</a>
    {/if}
    {if $id|strpos:'uid_edit' === $id|strlen - 8 && $Editor->GetHTMLValue()|strpos:'CHE-'!==0}<!-- Check is uid field and does not start with CHE- -->
      Search UID on <a href="https://www.uid.admin.ch/" title="Opens UID-Register@BFS webpage" target="_blank">UID-Register</a>
    {/if}

{/capture}

{include file="editors/text_editor_wrapper.tpl" TextEditorContent=$TextEditorContent}
