<th
    colspan="{$child->getColSpan()}"
    rowspan="{math equation='x-y-z' x=$DataGrid.ColumnGroup->getDepth() y=$childDepth z=$depth}"
    {if $childDepth == 0}
        class="{$child->GetGridColumnClass()}{if $child->allowSorting() and $DataGrid.allowSortingByClick and not $isInline} sortable{/if}{if $DataGrid.ColumnFilter and $DataGrid.ColumnFilter->hasColumn($child->getName())} filterable{/if}"

        {if $child->GetFixedWidth()}
            style="width: {$child->GetFixedWidth()};"
        {/if}
        data-field-caption="{$child->getCaption()}"
        data-name="{$child->getName()}"
        data-field-name="{$child->getFieldName()}"
        data-column-name="{$child->getFieldName()}"
        data-sort-index="{$child->getSortIndex()}"
        {if $child->getSortOrderType() == 'ASC'}
            data-sort-order="asc"
        {elseif $child->getSortOrderType() == 'DESC'}
            data-sort-order="desc"
        {/if}
        {if $child->getDescription()}data-comment="{$child->GetDescription()}"{/if}>
        {assign var="keys" value=$child->GetActualKeys()}
        {if $keys.Primary and $keys.Foreign}
            <i class="icon-keys-pk-fk"></i>
        {elseif $keys.Primary}
            <i class="icon-keys-pk"></i>
        {elseif $keys.Foreign}
            <i class="icon-keys-fk"></i>
        {/if}
    {else}class="js-column-group {$child->GetGridColumnClass()}" data-visibility="{to_json value=$child->getVisibilityMap() escape=true}">{/if}
    {strip}
        <span{if $childDepth == 0 and $child->getDescription()} class="commented"{/if}>{$child->getCaption()}</span>
        {if $childDepth === 0 and not $isInline and not $isMasterGrid}
            {if $child->getSortOrderType() == 'ASC'}
                <i class="icon-sort-asc"></i>
            {elseif $child->getSortOrderType() == 'DESC'}
                <i class="icon-sort-desc"></i>
            {/if}
            {if $DataGrid.ColumnFilter and $DataGrid.ColumnFilter->hasColumn($child->getName())}
                <a href="#" class="column-filter-trigger{if $DataGrid.ColumnFilter->isColumnActive($child->getName())} column-filter-trigger-active{/if} js-filter-trigger" title="{$DataGrid.ColumnFilter->toStringFor($child->getName(), $Captions)}">
                    <i class="icon-filter"></i>
                </a>
            {/if}
        {/if}
    {/strip}
</th>