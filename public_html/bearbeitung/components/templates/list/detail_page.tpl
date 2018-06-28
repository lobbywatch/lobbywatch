{capture assign="ExternalServicesLoadingBlock"}
    {include file="list/external_services.tpl"}
{/capture}

{assign var=ContentBlockClass value='col-md-12 grid-details'}

{capture assign="ContentBlock"}

    {if not $isInline}
    {include file="page_header.tpl" pageTitle=$PageTitle}

    {include file="page_description_block.tpl" Description=$Page->getDescription()}

    <p>{$Captions->GetMessageString('MasterRecord')}
        (<a href="{$Page->GetParentPageLink()|escapeurl}">{$Captions->GetMessageString('ReturnFromDetailToMaster')}</a>)
    </p>

        {$MasterGrid}

    {if count($SiblingDetails) > 1}
        <ul class="nav nav-tabs grid-details-tabs">
            {foreach from=$SiblingDetails item=SiblingDetail name=SiblingDetailsSection}
                <li class="{if $DetailPageName == $SiblingDetail.Name}active{/if}">
                    <a href="{$SiblingDetail.Link|escapeurl}">
                        {$SiblingDetail.Caption}
                    </a>
                </li>
            {/foreach}
        </ul>
    {/if}
    {/if}

    {include file="charts/collection.tpl" charts=$ChartsBeforeGrid chartsClasses=$ChartsBeforeGridClasses}

    {if not $isInline}
        {$PageNavigator1}
    {/if}

    {$Grid}

    {if not $isInline}
        {$PageNavigator2}
    {/if}

    {include file="charts/collection.tpl" charts=$ChartsAfterGrid chartsClasses=$ChartsAfterGridClasses}

    {if not $isInline}
        {include file="list/page_navigator_modal.tpl"}
    {/if}
{/capture}

{* Base template *}
{if not $isInline}
    {include file="common/list_page_template.tpl"}
{else}
    <p>
        {$Captions->GetMessageString('ShownFirstMofNRecords')|@sprintf:$Page->getFirstPageRecordCount():$Page->getTotalRowCount()}
        (<a href="{$Page->GetLink()}">{$Captions->GetMessageString('FullView')}</a>)
    </p>

    {$ContentBlock}
{/if}