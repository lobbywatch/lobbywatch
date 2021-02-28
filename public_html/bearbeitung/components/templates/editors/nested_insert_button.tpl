{if isset($NestedInsertFormLink) and !isset($isSingleFieldForm)}
    <div class="btn-group input-group-btn">
        <button
            type="button"
            class="btn btn-default js-nested-insert"
            data-content-link="{$NestedInsertFormLink}"
            {if isset($LookupDisplayFieldName)}
                data-display-field-name="{$LookupDisplayFieldName}"
	    {/if}
            {if isset($StoredFieldName)}
                data-stored-field-name="{$StoredFieldName}"
	    {/if}
            title="{$Captions->GetMessageString('InsertRecord')}">
            <span class="icon-plus"></span>
        </button>
    </div>
{/if}
