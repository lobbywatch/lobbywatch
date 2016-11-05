<script type="text/html" id="filterBuilderGroupTemplate">
    <tr class="filter-builder-group{literal}<% if (!isEnabled) { %> filter-builder-group-disabled<% } %>{/literal} js-group js-component">
        <td colspan="4">
            <div class="filter-builder-group-wrapper">
                <div class="filter-builder-group-operator">
                    {$Captions->getMessageString('FilterBuilderGroupConditionBeforeRules')}
                    <div class="dropdown">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-underline js-group-operator-text"><%= operator %></span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="js-group-operator-select" data-operator="AND" data-translate="{$Captions->GetMessageString('OperatorAnd')}">{$Captions->GetMessageString('OperatorAnd')}</a></li>
                            <li><a href="#" class="js-group-operator-select" data-operator="OR" data-translate="{$Captions->GetMessageString('OperatorOr')}">{$Captions->GetMessageString('OperatorOr')}</a></li>
                            <li><a href="#" class="js-group-operator-select" data-operator="NONE" data-translate="{$Captions->GetMessageString('OperatorNone')}">{$Captions->GetMessageString('OperatorNone')}</a></li>
                        </ul>
                    </div>
                    {$Captions->getMessageString('FilterBuilderGroupConditionAfterRules')}

                    <div class="btn-group pull-right">
                        <a href="#" class="btn btn-default js-group-add-component" data-type="group" title="{$Captions->GetMessageString('AddGroup')}"><span class="icon-add-group"></span></a>
                        <a href="#" class="btn btn-default js-component-toggle" title="{literal}<% if (isEnabled) { %>{/literal}{$Captions->GetMessageString('DisableFilter')}{literal}<% } else { %>{/literal}{$Captions->GetMessageString('EnableFilter')}{literal}<% } %>{/literal}">
                            {literal}<span class="icon-<% if (isEnabled) { %>disable<%} else { %>enable<% } %>"></span>{/literal}
                        </a>
                        <a href="#" class="btn btn-default js-component-remove" title="{$Captions->GetMessageString('RemoveGroup')}"><span class="icon-remove"></span></a>
                    </div>
                </div>
                <table class="filter-builder-group-content js-group-content"></table>
                <div class="filter-builder-group-footer">
                    <a href="#" class="btn btn-default js-group-add-component" data-type="condition"><span class="icon-add-condition"></span>{$Captions->GetMessageString('AddCondition')}</a>
                </div>
            </div>
        </td>
    </tr>
</script>

<script type="text/html" id="filterBuilderConditionTemplate">
    <tr class="filter-builder-condition js-condition js-component">
        <td class="filter-builder-condition-field">
            <select class="form-control js-condition-field-select"{literal}<% if (!isEnabled) { %> disabled="disabled"<% } %>{/literal}>
                {literal}<% _.each(columns, function(column) { %>
                    <option value="<%= column.getName() %>"<% if (column.getName() === columnName) { %> selected="selected"<% } %>><%= column.getCaption() %></option>
                <% }) %>{/literal}
            </select>
        </td>
        <td class="filter-builder-condition-operator">
            <select class="form-control js-condition-operator-select"{literal}<% if (!isEnabled) { %> disabled="disabled"<% } %>{/literal}>
                {literal}
                <% _.each(operators, function(caption, operatorKey) { %>
                    <option value="<%=operatorKey %>"<% if (operatorKey === operator) { %> selected="selected"<% } %>><%=caption %></option>
                <% }) %>
                {/literal}
            </select>
        </td>
        <td class="filter-builder-condition-value js-value"></td>
        <td class="filter-builder-condition-actions">
            <div class="btn-group">
                <a href="#" class="btn btn-default js-component-toggle" title="{literal}<% if (isEnabled) { %>{/literal}{$Captions->GetMessageString('DisableFilter')}{literal}<% } else { %>{/literal}{$Captions->GetMessageString('EnableFilter')}{literal}<% } %>{/literal}">
                    {literal}<span class="icon-<% if (isEnabled) { %>disable<% } else { %>enable<% } %>"></span>{/literal}
                </a>
                <a href="#" class="btn btn-default js-component-remove" title="{$Captions->GetMessageString('RemoveCondition')}"><span class="icon-remove"></span></a>
            </div>
        </td>
    </tr>
</script>

{foreach from=$DataGrid.FilterBuilder->getColumns() item=column}
{assign var=operators value=$DataGrid.FilterBuilder->getOperators($column)}
    {foreach from=$operators item=editor key=operator}
        {if $editor}
            <script type="text/html" id="filter_builder_editor_{$operator}_{$column->getFieldName()|replace:' ':'_'}" data-editor="{$editor->getEditorName()}">
                {include file='editors/'|cat:$editor->getEditorName()|cat:'.tpl' Editor=$editor ViewData=$editor->getViewData() FormId=$DataGrid.Id}
            </script>
        {/if}
    {/foreach}
{/foreach}

<div class="modal fade filter-builder modal-top js-filter-builder-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$Captions->GetMessageString('FilterBuilder')}</h4>
            </div>
            <div class="modal-body">
                <table class="js-filter-builder-container">

                </table>
            </div>
            <div class="modal-footer">
                <div class="checkbox pull-left">
                    <label>
                        <input type="checkbox" class="js-filter-builder-disable"> {$Captions->GetMessageString('DisableFilter')}
                    </label>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Cancel')}</button>
                <button type="button" class="btn btn-primary js-filter-builder-commit">{$Captions->GetMessageString('ApplyAdvancedFilter')}</button>
            </div>
        </div>
    </div>
</div>