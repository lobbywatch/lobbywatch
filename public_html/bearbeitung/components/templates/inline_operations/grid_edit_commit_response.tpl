<?xml version="1.0" {if $encoding}encoding="{$encoding}"{/if}?>
<fieldvalues>
{foreach from=$ColumnValues key=name item=Column name=Values}
    <fieldvalue name="{$name}">
        <value>
            <![CDATA[
            <div>{$Column.Value}</div>
            ]]>
        </value>
        <style>
            <![CDATA[
            {$Column.Style}
            ]]>
        </style>
    </fieldvalue>
{/foreach}
</fieldvalues>
