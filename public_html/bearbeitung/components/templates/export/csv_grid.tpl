{foreach item=HeaderCaption from=$HeaderCaptions name=HeaderIterator}
{$HeaderCaption}{if !$smarty.foreach.HeaderIterator.last}{$Delimiter}{/if}
{/foreach}

{foreach item=Row from=$Rows name=RowsGrid}
{foreach item=RowColumn from=$Row name=DataFieldIterator}"{$RowColumn}"{if !$smarty.foreach.DataFieldIterator.last}{$Delimiter}{/if}{/foreach}

{/foreach}
