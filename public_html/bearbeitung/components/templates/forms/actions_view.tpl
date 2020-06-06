{capture assign='ActionsContent'}
    <div class="btn-group">
        <a class="btn btn-primary"
           title="{$Captions->GetMessageString('BackToList')}"
           href="{$Grid.CancelUrl|escapeurl}">
            <i class="icon-arrow-left"></i>
            <span class="hidden-sm hidden-xs">{$Captions->GetMessageString('BackToList')}</span>
        </a>
    </div>

    {if $Grid.HasEditGrant}
        <div class="btn-group">
            <a class="btn btn-default"
               title="{$Captions->GetMessageString('Edit')}"
               href="{$Grid.EditUrl|escapeurl}">
                <i class="icon-edit"></i>
                <span class="hidden-sm hidden-xs">{$Captions->GetMessageString('Edit')}</span>
            </a>
        </div>
    {/if}

    {if count($Grid.Details) > 0}
        <div class="btn-group">
            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#"
                title="{$Captions->GetMessageString('ManageDetails')}">
                <span class="icon-list"></span>
                <span class="hidden-sm hidden-xs">{$Captions->GetMessageString('ManageDetails')}</span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                {foreach from=$Grid.Details item=Detail}
                    <li>
                        <a href="{$Detail.Link|escapeurl}">{$Detail.Caption|string_format:$Captions->GetMessageString('ManageDetail')}</a>
                    </li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <div class="btn-group">
        {include file="view/export_buttons.tpl" buttons=$Grid.ExportButtons spanClasses="hidden-sm hidden-xs"}

        {if $Grid.PrintOneRecord}
            <div class="btn-group">
                <a class="btn btn-default"
                   href="{$Grid.PrintRecordLink|escapeurl}"
                   title="{$Captions->GetMessageString('PrintOneRecord')}"{$Grid.PrintLinkTarget}>
                    <i class="icon-print-page"></i>
                    <span class="hidden-sm hidden-xs">{$Captions->GetMessageString('PrintOneRecord')}</span>
                </a>
            </div>
        {/if}
    </div>
{/capture}

{include file='forms/actions_wrapper.tpl' ActionsContent=$ActionsContent isHorizontal=$isHorizontal top=$top}