{if $PageNavigator}
    {assign var="totalRecords" value=$PageNavigator->GetRowCount()}
    {assign var="countPerPage" value=$PageNavigator->GetRowsPerPage()}
{else}
    {assign var="totalRecords" value=0}
    {assign var="countPerPage" value=0}
{/if}

{if $PageNavigator or $EnableRunTimeCustomization}
    <div id="page-settings" class="modal modal-top fade js-page-settings-dialog" data-total-record-count="{$totalRecords}" data-record-count-per-page="{$countPerPage}" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">{$Captions->GetMessageString('PageSettings')}</h3>
                </div>

                <div class="modal-body">

                    <h4>{$Captions->GetMessageString('Appearance')}</h4>

                    <table class="table table-bordered table-condensed form-inline">
                        {assign var="Grid" value=$Page->GetGrid()}
                        <tr>
                            <td class="page-settings-label-container">
                                <label for="page-settings-viewmode-control">{$Captions->GetMessageString('ViewMode')}</label>
                            </td>
                            <td class="page-settings-control-container" colspan="2">
                                <select id="page-settings-viewmode-control" class="form-control js-page-settings-viewmode-control">
                                    {assign var="CurrentViewMode" value=$Grid->GetViewMode()}
                                    {foreach from=$ViewModes item=caption key=mode}
                                        <option value="{$mode}" data-name="{$caption}"{if $CurrentViewMode == $mode} selected="selected"{/if}>{$Captions->GetMessageString($caption)}</option>
                                    {/foreach}
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="5" class="page-settings-label-container">
                                <label>{$Captions->GetMessageString('CardRowCount')}</label>
                                <p>{$Captions->GetMessageString('CardRowCountDescription')}</p>
                            </td>
                        </tr>

                        {assign var="AvailableCardCountInRow" value=$Grid->GetAvailableCardCountInRow()}
                        {assign var="CardCountInRow" value=$Grid->GetCardCountInRow()}

                        {foreach from=$Grid->getAvailableScreenSizes() item=size}
                            <tr class="page-settings-hightlight-{$size}">
                                <td style="width: 25%;">
                                    <label for="page-settings-card-column-count-{$size}">
                                        {assign var=SizeName value=$size|@ucfirst}
                                        {assign var=SizeTranslateString value='ScreenSize'|@cat:$SizeName}
                                        {$Captions->GetMessageString($SizeTranslateString)}
                                    </label>
                                </td>
                                <td class="page-settings-control-container">
                                <select class="form-control js-page-settings-card-column-count" data-size="{$size}" id="page-settings-card-column-count-{$size}">
                                    {foreach from=$AvailableCardCountInRow item=Count}
                                        <option{if $CardCountInRow[$size] == $Count} selected="selected"{/if} value="{$Count}">{$Count}</option>
                                    {/foreach}
                                </select>
                                </td>
                            </tr>
                        {/foreach}

                    </table>

                    {if $PageNavigator}
                        <h4>{$Captions->GetMessageString('ChangePageSizeTitle')}</h4>

                        {assign var="row_count" value=$PageNavigator->GetRowCount()}
                        <p>{eval var=$Captions->GetMessageString('ChangePageSizeText')}</p>

                        <table class="table table-bordered table-condensed form-inline">
                            <tr>
                                <th style="width:50%;"> <label for="page-settings-page-size-control">{$Captions->GetMessageString('RecordsPerPage')}</label></th>
                                <td class="page-settings-control-container">
                                    <span class="js-page-settings-page-size-container ">
                                        <select id="page-settings-page-size-control" class="form-control js-page-settings-page-size-control">
                                            {foreach from=$PageNavigator->GetRecordsPerPageValues() key=name item=value}
                                                {assign var="record_count" value=$value}
                                                {assign var="page_count" value=$PageNavigator->GetPageCountForPageSize($name)}
                                                <option value="{$name}">{eval var=$Captions->GetMessageString('CountRowsWithCountPages')}</option>
                                            {/foreach}
                                            <option value="custom">{$Captions->GetMessageString('CustomRecordsPerPage')}</option>
                                        </select>
                                    </span>

                                    <span class="js-page-settings-custom-page-size-container" style="display: none">
                                        <input type="number" min="1" max="{$PageNavigator->GetRowCount()}" value="{$PageNavigator->GetRowsPerPage()|escape:html}" id="page-settings-custom-page-size-control" class="js-page-settings-custom-page-size-control form-control">
                                        <span style="margin-left: .5em;">
                                            {assign var="current_page_count" value='<span class="js-page-settings-custom-page-size-pager"></span>'}
                                            {eval var=$Captions->GetMessageString('CurrentPageCount')}
                                        </span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    {/if}

                </div>

                <div class="modal-footer">
                    <a href="#" class="js-page-settings-cancel btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Cancel')}</a>
                    <a href="#" class="js-page-settings-save btn btn-primary">{$Captions->GetMessageString('SaveChanges')}</a>
                </div>

            </div>
        </div>
    </div>
{/if}