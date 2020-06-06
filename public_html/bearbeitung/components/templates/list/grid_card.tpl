{assign var=GridClass value='grid-card'}

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