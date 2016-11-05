{if $ColumnViewData.NestedInsertFormLink and not $isSingleFieldForm}
    <div class="btn-group input-group-btn">
        <button
            type="button"
            class="btn btn-default js-nested-insert"
            data-content-link="{$ColumnViewData.NestedInsertFormLink}"
            data-display-field-name="{$ColumnViewData.DisplayFieldName}"
            title="{$Captions->GetMessageString('InsertRecord')}">
            <span class="icon-plus"></span>
        </button>
    </div>
{/if}