{assign var="pageTitleButtons" value=$Page->GetExportListButtonsViewData()}

{if $pageTitleButtons}
    <div class="btn-group export-button">

        {if $Page->getExportListAvailable()}
            {include file="view/export_buttons.tpl" buttons=$pageTitleButtons spanClasses="visible-lg-inline"}
        {/if}

        {if $Page->getPrintListAvailable()}
            {include file="view/print_buttons.tpl" buttons=$pageTitleButtons spanClasses="visible-lg-inline"}
        {/if}

        {if $Page->GetRssLink()}
            <a href="{$Page->GetRssLink()}" class="btn btn-default" title="RSS">
                <i class="icon-rss"></i>
                <span class="visible-lg-inline">RSS</span>
            </a>
        {/if}

    </div>
{/if}

