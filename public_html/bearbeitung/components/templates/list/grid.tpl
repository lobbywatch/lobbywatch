<table
    id="{$DataGrid.Id}"
    class="pgui-grid grid legacy {$DataGrid.Classes}"
    data-grid-hidden-values="{$DataGrid.HiddenValuesJson|escape:'html'}"
    data-inline-edit="{ldelim} &quot;enabled&quot;:&quot;{jsbool value=$DataGrid.UseInlineEdit}&quot;, &quot;request&quot;:&quot;{$DataGrid.Links.InlineEditRequest|escapeurl}&quot{rdelim}"
    {style_block}
        {$DataGrid.Styles}
    {/style_block}
    {$DataGrid.Attributes}>
<thead>
    {if $DataGrid.ActionsPanelAvailable}
    <tr>
        <td colspan="{$DataGrid.ColumnCount}" class="header-panel">
            <div class="btn-toolbar pull-left">
                <div class="btn-group">
                {if $DataGrid.ActionsPanel.InlineAdd}
                    <button class="btn inline_add_button" href="#">
                        <i class="pg-icon-add-record"></i>
                        {$Captions->GetMessageString('AddNewRecord')}
                    </button>
                {/if}

                {if $DataGrid.ActionsPanel.AddNewButton}
                    {if $DataGrid.ActionsPanel.AddNewButton eq 'modal'}
                        <button class="btn"
                                dialog-title="{$Captions->GetMessageString('AddNewRecord')}"
                                content-link="{$DataGrid.Links.ModalInsertDialog}"
                                modal-insert="true">
                            <i class="pg-icon-add-record"></i>
                            {$Captions->GetMessageString('AddNewRecord')}
                        </button>
                    {else}
                        <a class="btn" href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}">
                            <i class="pg-icon-add-record"></i>
                            {$Captions->GetMessageString('AddNewRecord')}
                        </a>
                    {/if}
                {/if}

                    {if $DataGrid.ActionsPanel.DeleteSelectedButton}
                        <button class="btn delete-selected">
                            <i class="pg-icon-delete-selected"></i>
                            {$Captions->GetMessageString('DeleteSelected')}
                        </button>
                    {/if}

                    {if $DataGrid.ActionsPanel.RefreshButton}
                        <a class="btn" href="{$DataGrid.Links.Refresh|escapeurl}">
                            <i class="pg-icon-page-refresh"></i>
                            {$Captions->GetMessageString('Refresh')}
                        </a>
                    {/if}
                </div>
            </div>

            {if $DataGrid.AllowQuickFilter}
            <div id="quick-filter-toolbar" class="btn-toolbar pull-right">
                <div class="btn-group">
                    <div class="input-append" style="float: left; margin-bottom: 0;">
                        <input placeholder="{$Captions->GetMessageString('QuickSearch')}" type="text" size="16" class="quick-filter-text" value="{$DataGrid.QuickFilter.Value|escape:html}"><button type="button" class="btn quick-filter-go"><i class="pg-icon-quick-find"></i></button><button type="button" class="btn quick-filter-reset"><i class="pg-icon-filter-reset"></i></button>
                    </div>
                </div>
            </div>
            {/if}
        </td>
    </tr>
    {/if}

    <tr class="addition-block messages hide">
        <td colspan="{$DataGrid.ColumnCount}">
            {if $DataGrid.ErrorMessage}
            <div class="alert alert-error">
                <button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
                {$DataGrid.ErrorMessage}
            </div>
            {/if}
            {if $DataGrid.GridMessage}
                <div class="alert alert-success">
                    <button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
                    {$DataGrid.GridMessage}
                </div>
            {/if}

        </td>
    </tr>

    <tr class="header">
    {if $DataGrid.AllowDeleteSelected}
        <th class="row-selection"><input type="checkbox"></th>
    {/if}

    {if $DataGrid.HasDetails}
        <th>
            <a class="expand-all-details collapsed" href="#">
                <i class="toggle-detail-icon"></i>
            </a>
        </th>
    {/if}

    {if $DataGrid.ShowLineNumbers}
        <th>#</th>
    {/if}
        
    <!-- <Grid Head Columns> -->
    {foreach item=Band from=$DataGrid.Bands}
        {if $Band.ConsolidateHeader and $Band.ColumnCount > 0}
            <th colspan="{$Band.ColumnCount}">
                {$Band.Caption}
            </th>
        {else}
            {foreach item=Column from=$Band.Columns}
                <th class="{$Column.Classes}"
                    {$Column.Attributes}
                    {style_block}{$Column.Styles}{/style_block}
                    data-sort-url="{$Column.SortUrl|escapeurl}"
                    data-field-caption="{$Column.Caption}"
                    data-comment="{$Column.Comment}">
                    <i class="additional-info-icon"></i>
                    <span {if $Column.Comment}class="commented"{/if}>{$Column.Caption}</span>
                    <i class="sort-icon"></i>
                </th>
            {/foreach}
        {/if}
    {/foreach}
    </tr>

    {if $DataGrid.AllowFilterRow and count($DataGrid.FilterRow.Columns) > 0}
    <tr class="addition-block search-line"  dir="ltr" timer-interval="{$DataGrid.FilterRow.TimerInterval}">
        {if $DataGrid.AllowDeleteSelected}
            <td></td>
        {/if}

        {if $DataGrid.HasDetails}
            <td></td>
        {/if}

        {if $DataGrid.ShowLineNumbers}
            <td></td>
        {/if}

        {foreach item=SearchColumn from=$DataGrid.FilterRow.Columns}
            {if $SearchColumn.ResetButtonPlacement}
            <td>
                <div style="text-align: {$SearchColumn.ResetButtonAlignment}">
                    <a href="#" class="reset-filter-row" title="{$Captions->GetMessageString('ResetFilterRow')}"><i  class="pg-icon-filter-builder-reset"></i></a>
                </div>
            </td>
            {else}
            <td class="column-filter">
                {if $SearchColumn}
                <table style="padding: 0;">
                    <tr>
                        <td style="padding: 0;">
                            <div style="white-space: nowrap; margin: 0;" class="input-append btn-group filter-control" data-field-name="{$SearchColumn.FieldName}" data-operator="{$SearchColumn.CurrentOperator.Name}">
                                <input type="text" class="input" value="{$SearchColumn.Value|escape:html}" {$SearchColumn.Attributes}>
                            </div>
                        </td>

                        <td style="padding: 0; overflow: visible;">
                            <div class="btn-group">
                            <a style="white-space: nowrap; " class="btn dropdown-toggle operator-dropdown" data-toggle="dropdown" href="#">
                                <i class="{$SearchColumn.CurrentOperator.ImageClass}"></i>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu pull-right operator-menu">
                                {foreach from=$SearchColumn.Operators item=Operator}
                                    <li><a href="#" data-operator="{$Operator.Name}">
                                        <i class="{$Operator.ImageClass}"></i>
                                        {$Operator.Caption}</a>
                                    </li>
                                {/foreach}
                            </ul>
                            </div>
                        </td>
                    </tr>
                </table>
                {/if}
            </td>
            {/if}

        {/foreach}
    </tr>
    {/if}
</thead>

<tbody>
	<tr class="new-record-row" style="display: none;" data-new-row="false">

        {if $DataGrid.AllowDeleteSelected}
            <td data-column-name="sm_multi_delete_column"></td>
        {/if}

        {if $DataGrid.HasDetails}
            <td class="details">
                <a class="expand-details collapsed" href="#"><i class="toggle-detail-icon"></i></a>
            </td>
        {/if}

        {if $DataGrid.ShowLineNumbers}
            <td class="line-number"></td>
        {/if}

        {foreach item=Band from=$DataGrid.Bands}
            {foreach item=Column from=$Band.Columns}
                <td data-column-name="{$Column.Name}"></td>
            {/foreach}
        {/foreach}
    </tr>


    {include file=$SingleRowTemplate}

    <tr class="empty-grid{if count($DataGrid.Rows) > 0} hide{/if}">
        <td colspan="{$DataGrid.ColumnCount}" class="empty-grid">
            {$DataGrid.EmptyGridMessage}
        </td>
    </tr>

</tbody>

<tfoot>
    {if $DataGrid.Totals}
    <tr class="data-summary">
        {if $DataGrid.AllowDeleteSelected}
            <td></td>
        {/if}

        {if $DataGrid.HasDetails}
            <td></td>
        {/if}

        {if $DataGrid.ShowLineNumbers}
            <td></td>
        {/if}

        {foreach item=Total from=$DataGrid.Totals}
            <td>{$Total.Value}</td>
        {/foreach}
    </tr>
    {/if}

    {if $DataGrid.FilterBuilder}
    <tr>
        <td colspan="{$DataGrid.ColumnCount}" class="addition-block filter-builder-row">
            {if $IsActiveFilterEmpty}
                <i class="pg-icon-filter-new"></i>
                <a class="create-filter" href="#">
                {$Captions->GetMessageString('CreateFilter')}
            </a>
            {else}
                <i class="pg-icon-filter"></i>
                <a class="edit-filter" href="#">
                    {$ActiveFilterBuilderAsString|escapeurl}
                </a>

                <i class="pg-icon-filter-builder-reset"></i>
                <a class="reset-filter" href="#">
                    {$Captions->GetMessageString('ResetFilter')}
                </a>
            {/if}
        </td>
    </tr>
    {/if}
</tfoot>

</table>

<script type="text/javascript">

    {if $AdvancedSearchControl}
    {literal}
            require(['pgui.text_highlight'], function(textHighlight) {
    {/literal}
    {foreach from=$AdvancedSearchControl->GetHighlightedFields() item=HighlightFieldName name=HighlightFields}
        textHighlight.HighlightTextInGrid(
                '#{$DataGrid.Id}', '{$HighlightFieldName}',
                {$TextsForHighlight[$smarty.foreach.HighlightFields.index]},
                '{$HighlightOptions[$smarty.foreach.HighlightFields.index]}');
    {/foreach}
    {literal}
    });
    {/literal}
    {/if}


    {literal}
    $(function() {
        var gridId = '{/literal}{$DataGrid.Id}{literal}';
        var $gridContainer = $('#' + gridId);

        require(['pgui.grid', 'pgui.advanced_filter'], function(pggrid, fb) {

            var grid = new pggrid.Grid($gridContainer);

            grid.onConfigureFilterBuilder(function(filterBuilder) {
            {/literal}
                {foreach item=FilterBuilderField from=$FilterBuilder.Fields}
                filterBuilder.addField(
                        {jsstring value=$FilterBuilderField.Name},
                        {jsstring value=$FilterBuilderField.Caption},
                        fb.FieldType.{$FilterBuilderField.Type},
                        fb.{$FilterBuilderField.EditorClass},
                        {$FilterBuilderField.EditorOptions});
                {/foreach}
            {literal};
            });

            var activeFilterJson = {/literal}{$ActiveFilterBuilderJson}{literal};
            var activeFilter = new fb.Filter();
            activeFilter.fromJson(activeFilterJson);
            grid.setFilter(activeFilter);
        });
    });
    {/literal}
</script>