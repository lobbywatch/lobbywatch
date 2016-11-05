<div class="btn-group text-nowrap">
    <a class="expand-details collapsed js-expand-details link-icon" data-info="{$Details.JSON}" href="#" title="{$Captions->GetMessageString('ToggleDetails')}"><i class="icon-detail-plus"></i><i class="icon-detail-minus"></i></a><a data-toggle="dropdown" class="link-icon" href="#" title="{$Captions->GetMessageString('GoToMasterDetailPage')}"><i class="icon-detail-additional"></i></a>
    <ul class="dropdown-menu">
        {foreach from=$Details.Items item=Detail}
            <li><a href="{$Detail.SeparatedPageLink|escapeurl}">{$Detail.caption}</a></li>
        {/foreach}
    </ul>
</div>