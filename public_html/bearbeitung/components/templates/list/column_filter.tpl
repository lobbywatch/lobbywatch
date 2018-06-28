<script type="text/html" id="columnFilterGroupTemplate">
{literal}
<div class="column-filter-component js-component js-group<% if (isDateTreePart){%> js-date-tree<%}%>">
    <a href="#" class="column-filter-choices-caret-wrapper js-caret">
        <span class="column-filter-choices-caret"></span>
    </a>
    <label title="<%=_.escape(label.trim()) %>">
        <input type="checkbox" class="js-toggle-component"<% if (checked){%> checked="checked"<%}%> />
        <%=label.trim() %>
    </label>
    <div class="column-filter-choices-children js-children"></div>
</div>
{/literal}
</script>

<script type="text/html" id="columnFilterConditionTemplate">
{literal}
<div class="column-filter-component js-component">
    <% if (hasDivider) { %><hr><% } %>
    <label title="<%=_.escape(label.trim()) %>">
        <input type="checkbox" class="js-toggle-component<% if (ignoreSelectAll){%> js-ignore-select-all<%}%>"<% if (checked){%> checked="checked"<%}%> />
        <%=label.trim() %>
    </label>
</div>
{/literal}
</script>

<script type="text/html" id="columnFilterContentTemplate">
<div class="column-filter">
    <button data-dismiss="alert" class="close" type="button" style="margin-top: -3px">&times;</button>
    <label>
        <input type="checkbox" class="js-select-all"> {$Captions->getMessageString('SelectAll')}
    </label>
    <div class="column-filter-search input-group input-group-sm js-search">
        <input type="text" class="js-search-input form-control column-filter-search-input" placeholder="{$Captions->getMessageString('Search')}">
        <a href="#" class="column-filter-search-clear js-search-clear">
            <i class="icon-remove"></i>
        </a>
    </div>
    <hr class="column-filter-separator">
    <div class="column-filter-choices js-content"></div>
    <div class="column-filter-search-empty js-search-empty">{$Captions->getMessageString('SearchEmptyResult')}</div>
    <div class="column-filter-searching js-searching">
        <img src="components/assets/img/loading.gif">&nbsp;
        <span class="js-searching-title">{$Captions->getMessageString('Select2Searching')}</span>
        <span class="js-data-loading-title">{$Captions->getMessageString('DataLoading')}</span>
    </div>
    <div class="btn-toolbar pull-right">
        <button type="reset" class="btn btn-sm btn-default js-reset">{$Captions->getMessageString('ResetAdvancedFilter')}</button>
        <button type="submit" class="btn btn-sm btn-primary js-apply">{$Captions->getMessageString('ApplyAdvancedFilter')}</button>
    </div>
</div>
</script>
