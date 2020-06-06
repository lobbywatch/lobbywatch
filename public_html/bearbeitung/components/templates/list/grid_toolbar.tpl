{if $DataGrid.ActionsPanelAvailable}
    <div class="addition-block js-actions">
        <div class="btn-toolbar addition-block-left pull-left">
            {include file="list/grid_toolbar_add_button.tpl"}
            {include file="list/grid_toolbar_multi_upload_button.tpl"}
            {include file="list/grid_toolbar_refresh_button.tpl"}
            {include file="list/grid_toolbar_export_print_rss_buttons.tpl"}
            {include file="custom_templates/list/grid_toolbar_selection_button.tpl"}
        </div>

        {if not $isInline}
            <div class="addition-block-right pull-right">
                {include file="list/grid_toolbar_filter_builder_button.tpl"}
                {include file="list/grid_toolbar_sort_button.tpl"}
                {include file="list/grid_toolbar_settings_button.tpl"}
                {include file="list/grid_toolbar_description_button.tpl"}
            </div>
            {include file="list/quick_filter.tpl" filter=$DataGrid.QuickFilter}
        {/if}
    </div>
{/if}

{if $GridViewMode == 'card'}
    {include file='list/column_filters.tpl'}
{/if}

{* The string below is retained for compatibility *}
{$GridBeforeFilterStatus}

{include file='list/grid_filters_status.tpl'}

{include file='common/messages.tpl' type='danger' dismissable=true messages=$DataGrid.ErrorMessages displayTime=$DataGrid.MessageDisplayTime}
{include file='common/messages.tpl' type='success' dismissable=true messages=$DataGrid.Messages displayTime=$DataGrid.MessageDisplayTime}

<div class="js-grid-message-container"></div>