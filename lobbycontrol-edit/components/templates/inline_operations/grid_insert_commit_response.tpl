<?xml version="1.0" encoding="utf-8" ?>
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