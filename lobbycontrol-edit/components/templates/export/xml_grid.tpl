<Table name="{$TableName}">
{foreach item=Row from=$Rows name=RowsGrid}
    <Row>
{foreach item=RowColumn key=FieldName from=$Row}
        <{$FieldName}>{$RowColumn}</{$FieldName}>
{/foreach}
    </Row>
{/foreach}
</Table>