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

    {if $Editor->GetHTMLValue()|strpos:'http'===0}<!-- Check starts with http -->
        <a href="{$Editor->GetHTMLValue()}" target="_blank">Follow link: {$Editor->GetHTMLValue()}</a>
    {/if}
    {if $Editor->GetHTMLValue()|strpos:'CHE-'===0}<!-- Check starts with CHE- -->
        <a href="http://zefix.ch/WebServices/Zefix/Zefix.asmx/SearchFirm?id={$Editor->GetHTMLValue()}" target="_blank">Follow link: {$Editor->GetHTMLValue()}</a>
    {/if}

{/capture}

{include file="editors/text_editor_wrapper.tpl" TextEditorContent=$TextEditorContent}

