{if $DataGrid.ColumnFilter->hasColumns()}
    <ul class="nav nav-pills pull-right js-column-filter-container grid-card-column-filter">
        {foreach item=Column from=$DataGrid.ColumnFilter->getColumns()}
            <li data-name="{$Column->getFieldName()}"{if $DataGrid.ColumnFilter->isColumnActive($Column->getFieldName())} class="active"{/if}>
                <a href="#" class="js-filter-trigger" title="{$DataGrid.ColumnFilter->toStringFor($Column->getFieldName(), $Captions)}">
                    <i class="icon-filter"></i>&nbsp;
                    {$Column->getCaption()}
                </a>
            </li>
        {/foreach}
    </ul>
{/if}

<div class="clearfix"></div>
