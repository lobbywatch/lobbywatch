{assign var='ColumnViewData' value=$Col->getViewData()}

{if $isViewForm}
    {include file='forms/form_view_field.tpl'}
{else}
    {include file='forms/form_edit_field.tpl'}
{/if}