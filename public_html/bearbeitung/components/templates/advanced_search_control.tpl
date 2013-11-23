<div>
    <input type="hidden" name="operation" value="asearch" >
    <input type="hidden" id="AdvancedSearch" name="AdvancedSearch" value="1">
    <input type="hidden" id="ResetFilter" name="ResetFilter" value="0">

    <form method="POST" class="form-horizontal">
    <fieldset>
        {foreach item=Column from=$AdvancedSearchControl->GetSearchColumns() name=ColumnsIterator}
            <div class="control-group">
                <label class="control-label" for="{$Column->GetFilterTypeInputName()}">{$Column->GetCaption()}</label>
                <div class="controls">
                    <input type="text" class="input-xlarge" id="{$Column->GetFilterTypeInputName()}">
                </div>
            </div>
        {/foreach}
    </fieldset>
    </form>
</div>

{*
<div {n}
    align="center" {n}
    id="searchControl" {n}
    {if $AdvancedSearchControl->GetHidden()}
    style="display: none; height: 0px;" {n}
    {/if}>

    <form method="POST" {n}
        {if $AdvancedSearchControl->GetTarget() != ''}
        action="{$AdvancedSearchControl->GetTarget()}" {n}
        {/if}
        id="AdvancedSearchForm" {n}
        name="AdvancedSearchForm"  {n}
        style="padding: 0px; margin: 0px;">

        <input type="hidden" name="operation" value="asearch" >
        <input type="hidden" id="AdvancedSearch" name="AdvancedSearch" value="1">
        <input type="hidden" id="ResetFilter" name="ResetFilter" value="0">

        <table class="adv_filter">
        
            {if $AdvancedSearchControl->GetAllowOpenInNewWindow()}
            <tr class="adv_filter_top_panel adv_filter_type">
                <td colspan="5">
                    <a href="{$AdvancedSearchControl->GetOpenInNewWindowLink()|escapeurl}">Open in new page</a>
                </td>
            </tr>
            {/if}

        <tr class="adv_filter_title">
            <td colspan="5">
                {$Captions->GetMessageString('AdvancedSearch')}
            </td>
        </tr>

        <tr class="adv_filter_type">
            <td colspan="5">
                {$Captions->GetMessageString('SearchFor')}:
                <input {n}
                    type="radio" {n}
                    name="SearchType" {n}
                    value="and" {n}
                    {if $AdvancedSearchControl->GetIsApplyAndOperator()}
                    checked {n}
                    {/if}>
                    {$Captions->GetMessageString('AllConditions')}
                &nbsp;&nbsp;&nbsp;
                <input {n}
                    type="radio" {n}
                    name="SearchType" {n}
                    value="pr" {n}
                    {if not $AdvancedSearchControl->GetIsApplyAndOperator()}
                    checked {n}
                    {/if}>{$Captions->GetMessageString('AnyCondition')}
            </td>
        </tr>

    <tr class="adv_filter_head">
        <td class="adv_filter_field_head">&nbsp;</td>
        <td class="adv_filter_not_head">{$Captions->GetMessageString('Not')}</td>
        <td colspan="3" class="adv_filter_editors_head">&nbsp;</td>
    </tr>
{foreach item=Column from=$AdvancedSearchControl->GetSearchColumns() name=ColumnsIterator}
    <tr class="adv_filter_row">
        <td class="adv_filter_field_name_cell">{$Column->GetCaption()}</td>
        <td class="adv_filter_not_cell">
            <input  {n}
                type="checkbox" {n}
                name="{$Column->GetNotMarkInputName()}" {n}
                value="{$FilterTypeIndex}" {n}
                {if $Column->IsApplyNotOperator()} 
                checked="checked" {n}
                {/if}>
        </td>
        <td class="adv_filter_operator_cell">
            <select {n}
                class="sm_comboBox adv_filter_type" {n}
                style="width: 120px;" {n}
                id="{$Column->GetFilterTypeInputName()}" {n}
                name="{$Column->GetFilterTypeInputName()}" {n}
                onchange="if ($('#{$Column->GetFilterTypeInputName()}').val() == 'between') $('#{$Column->GetFieldName()}_second').show(); else $('#{$Column->GetFieldName()}_second').hide(); if ($('#{$Column->GetFilterTypeInputName()}').val() == 'IS NULL') $('{$Column->GetFieldName()}_value').hide(); else $('#{$Column->GetFieldName()}_value').show();">
{foreach key=FilterTypeName item=FilterTypeCaption from=$Column->GetAvailableFilterTypes()}
                <option {n}
                    value="{$FilterTypeName}" {n}
                    {if $Column->GetActiveFilterIndex() eq $FilterTypeName} 
                    selected {n}
                    {/if}>
                    {$FilterTypeCaption}
                </option>
{/foreach}
            </select>
        </td>

        <td class="adv_filter_editor1_cell">
            {$Renderer->Render($Column->GetEditorControl())}
        </td>

        <td class="adv_filter_editor2_cell">
            <span id="{$Column->GetFieldName()}_second">
                {$Renderer->Render($Column->GetSecondEditorControl())}
            </span>
        </td>
    </tr>
{/foreach}
    <tr class="adv_filter_footer">
        <td colspan="5" style="padding: 5px;">
            <input {n}
                id="advsearch_submit" {n}
                class="sm_button" {n}
                type="submit" {n}
                value="{$Captions->GetMessageString('ApplyAdvancedFilter')}" />
            <input {n}
                class="sm_button" {n}
                type="button"  {n}
                value="{$Captions->GetMessageString('ResetAdvancedFilter')}" {n}
                onclick="javascript: document.forms.AdvancedSearchForm.ResetFilter.value = '1'; document.forms.AdvancedSearchForm.submit();"/>
        </td>
    </tr>
</table>


<script language="javascript">

{foreach item=Column from=$AdvancedSearchControl->GetSearchColumns() name=ColumnsIterator}
    if ($('#{$Column->GetFilterTypeInputName()}').val() == 'between')
        $('#{$Column->GetFieldName()}_second').show();
    else
        $('#{$Column->GetFieldName()}_second').hide();

    if ($('#{$Column->GetFilterTypeInputName()}').val() == 'IS NULL')
        $('#{$Column->GetFieldName()}_value').hide();
    else
        $('#{$Column->GetFieldName()}_value').show();
{/foreach}

{if $AdvancedSearchControl->IsActive()}
$(document).ready(function(){ldelim}

{literal}
require(PhpGen.ModuleList([PhpGen.Module.PG.TextHighlight]), function(textHighlight) {
{/literal}
    {foreach from=$AdvancedSearchControl->GetHighlightedFields() item=HighlightFieldName name=HighlightFields}
        textHighlight.HighlightTextInGrid('.grid', '{$HighlightFieldName}',
            '{$TextsForHighlight[$smarty.foreach.HighlightFields.index]}',
            '{$HighlightOptions[$smarty.foreach.HighlightFields.index]}');
    {/foreach}
{literal}
});
{/literal}

{rdelim});    
{/if}

$(function()
{ldelim}
	$('#advsearch_submit').click(function()
	{ldelim}
		var hasNotEmpty = false;
		$('table.adv_filter').find('td.adv_filter_editor1_cell').find('input,select').each(function()
		{ldelim}
			if ($(this).closest('tr.adv_filter_row').find('.adv_filter_operator_cell').find('select').val() == 'IS NULL')
				hasNotEmpty = true;	
			if ($(this).val() != '')
				hasNotEmpty = true;	
		{rdelim});
		if (!hasNotEmpty) 
			ShowOkDialog(
				'{$Captions->GetMessageString('EmptyFilter_MessageTitle')}', 
				'{$Captions->GetMessageString('EmptyFilter_Message')}');
		return hasNotEmpty;
	{rdelim});
{rdelim});

</script>
</form>
</div>

*}

