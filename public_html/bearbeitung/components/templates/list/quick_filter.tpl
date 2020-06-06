{if $DataGrid.QuickFilter->hasColumns()}
<div class="addition-block-right pull-right js-quick-filter">
    <div class="quick-filter-toolbar btn-group">
        <div class="input-group js-filter-control">
            <input placeholder="{$Captions->GetMessageString('QuickSearch')}" type="text" size="16" class="js-input form-control" name="quick_filter" value="{$DataGrid.QuickFilter->getValue()|escape:html}">
            <div class="input-group-btn dropdown dropdown-lg">
                <button id="toggle-dropdown" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <form class="form-horizontal quick-filter">
                        <div class="form-group form-group-sm">
                            <label class="control-label" for="quick-filter-fields">{$Captions->GetMessageString('Columns')}</label>
                            <div class="col-input input-group-sm" style="width:100%;max-width:100%" data-column="quick_filter_fields">
                                <select id="quick-filter-fields" class="form-control" name="quick_filter_fields[]" multiple data-max-selection-size="0" data-placeholder="{$Captions->GetMessageString('All')}" data-editor="multivalue_select">
                                    {foreach item=column from=$filter->getColumns()}
                                        <option value="{$column->getFieldName()}" {if $column->getFieldName()|in_array:$filter->getSelectedFieldNames()} selected{/if}>{$column->getCaption()}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label class="control-label" for="quick-filter-operator">{$Captions->GetMessageString('SearchCondition')}</label>
                            <div class="col-input input-group-sm" style="width:100%;max-width:100%" data-column="quick_filter_operator">
                                <select id="quick-filter-operator" class="form-control" name="quick_filter_operator">
                                    {foreach item=operator from=$filter->getAvailableOperators()}
                                        <option value="{$operator}" {if $operator == $filter->getOperator()} selected{/if}>
                                            {assign var=operatorCaption value='FilterOperator'|cat:$operator}
                                            {$Captions->GetMessageString($operatorCaption)}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <hr class="quick-filter-separator">
                        <span class="pull-right">
                            <button type="button" class="js-cancel btn btn-sm btn-default">{$Captions->GetMessageString('Cancel')}</button>
                            <button type="button" class="js-submit btn btn-sm btn-primary">{$Captions->GetMessageString('Apply')}</button>
                        </span>
                    </form>
                </div>
                <button type="button" class="btn btn-default js-submit" title="{$Captions->GetMessageString('QuickSearchApply')}"><i class="icon-search"></i></button>
                <button type="button" class="btn btn-default js-reset" title="{$Captions->GetMessageString('QuickSearchClear')}"><i class="icon-filter-reset"></i></button>
            </div>
        </div>
    </div>
    <span class="hidden-xs">&thinsp;</span>
</div>
{/if}
