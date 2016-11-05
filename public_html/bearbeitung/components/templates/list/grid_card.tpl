{assign var=GridClass value='grid-card'}

{capture assign=GridBeforeFilterStatus}
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

    {$GridBeforeFilterStatus}
{/capture}

{capture assign=GridContent}
    <div class="row">
        <div class="{$DataGrid.Classes} col-md-12" {$DataGrid.Attributes}>

            <div class="alert alert-warning empty-grid{if count($DataGrid.Rows) > 0} hidden{/if}">
                {$DataGrid.EmptyGridMessage}
            </div>

            <div class="pg-row-list row">
                {include file=$SingleRowTemplate}
            </div>

            <div>
                {if $DataGrid.Totals}
                    <table class="table table-bordered table-totals">
                        {foreach item=Total from=$DataGrid.Totals}
                            {if $Total.Value}
                                <tr>
                                    <th>{$Total.Caption}</th>
                                    <td>{$Total.Value}</td>
                                </tr>
                            {/if}
                        {/foreach}
                    </table>
                {/if}
            </div>
        </div>
    </div>

    <script id="{$DataGrid.Id}_row_template" type="text/html">
        <div class="grid-card-item pg-row {if $isMasterGrid}col-md-12{else}{$DataGrid.CardClasses}{/if}">
            <div class="well">
                <div class="js-inline-edit-container pg-inline-edit-container-loading">
                    <img src="components/assets/img/loading.gif">
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </script>
{/capture}

{include file='list/grid.tpl'}