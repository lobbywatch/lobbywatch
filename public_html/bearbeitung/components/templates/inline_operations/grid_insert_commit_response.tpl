<?xml version="1.0" {if $encoding}encoding="{$encoding}"{/if}?>
<fieldvalues>
{if $AllowDeleteSelected}
    <fieldvalue name="sm_multi_delete_column">
		<value>
			<![CDATA[
			<input type="checkbox" name="inline_inserted_rec_{$RecordUID}" id="inline_inserted_rec_{$RecordUID}">
			{foreach item=PrimaryKeyValue from=$PrimaryKeys name=CPkValues}
				<input type="hidden" name="inline_inserted_rec_{$RecordUID}_pk{$smarty.foreach.CPkValues.index}" value="{$PrimaryKeyValue}">
			{/foreach}
			]]>
		</value>
    </fieldvalue>
{/if}
{if $HasDetails}
	<fieldvalue name="details">
		<value>
			<![CDATA[
			<div class="btn-group detail-quick-access" style="display: inline-block;" >
                <a class="expand-details collapsed"
                   style="display: inline-block;"
                   data-info="{$Details.JSON}"
                   href="#"><i class="toggle-detail-icon"></i>
                </a><a data-toggle="dropdown" href="#"><i class="pg-icon-detail-additional"></i></a><ul class="dropdown-menu">
                    {foreach from=$Details.Items item=Detail}
                        <li><a href="{$Detail.SeperatedPageLink|escapeurl}">{$Detail.caption}</a></li>
                    {/foreach}
                </ul>
            </div>
			]]>
		</value>
	</fieldvalue>
{/if}
{foreach from=$Columns key=name item=Column}
    <fieldvalue name="{$name}">
		<value>
			<![CDATA[
				{$Column.Value}
			]]>
		</value>
		<afterrowcontrol>
			<![CDATA[
				{$Column.AfterRowControl}
			]]>
		</afterrowcontrol>
		<style>
			<![CDATA[
				{$Column.Style}
			]]>
		</style>
    </fieldvalue>
{/foreach}
</fieldvalues>