{assign var='Editor' value=$ColumnViewData.EditorViewData.Editor}
{assign var='EditorId' value=$Grid.FormId|cat:'_'|cat:$Editor->getName()}
{if $Editor->getVisible()}
    {assign var='EditorControlStyles' value=''}
{else}
    {assign var='EditorControlStyles' value=' style="display: none"'}
{/if}

{capture assign='EditControl'}
    <div class="col-input" style="width:100%;max-width:{$Editor->getMaxWidth()}" data-column="{$ColumnViewData.FieldName}">
        {include file='editors/'|cat:$Editor->getEditorName()|cat:'.tpl' Editor=$Editor ViewData=$ColumnViewData.EditorViewData FormId=$Grid.FormId id=$EditorId}
    </div>
{/capture}

{include file='forms/form_field_layout.tpl' FormControl=$EditControl FormControlStyles=$EditorControlStyles}
