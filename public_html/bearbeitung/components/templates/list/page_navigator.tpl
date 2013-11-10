<div class="pgui-pagination" data-total-record-count="{$PageNavigator->GetRowCount()}">

    <div class="pagination">
        <ul>{foreach item=PageNavigatorPage from=$PageNavigatorPages}{strip}
            {if $PageNavigatorPage->GetPageLink()}
            <li {if $PageNavigatorPage->IsCurrent()}class="active"{/if}>
                <a
                  {n} href="{$PageNavigatorPage->GetPageLink()|escapeurl}"
                    {n} title="{$PageNavigatorPage->GetHint()}"
                    {n} {if $PageNavigatorPage->HasShortCut()}pgui-shortcut="{$PageNavigatorPage->GetShortCut()}"{/if}
                    >
                    {$PageNavigatorPage->GetPageCaption()}
                </a>
            </li>
            {else}
                <li class="pagination-spacer">
                    <a
                        {n} href="#"
                        {n} title="{$PageNavigatorPage->GetHint()}"
                        {n} onclick="return false;">
                        {$PageNavigatorPage->GetPageCaption()}
                    </a>
                </li>
            {/if}
        {/strip}{/foreach}</ul>
        <ul><li><a class="define-page-size-button" href="#">{$Captions->GetMessageString('DefaultPageSize')}</a></ul>
    </div>

    <div class="modal hide pagination-size">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
            <h3>{$Captions->GetMessageString('ChangePageSizeTitle')}</h3>
        </div>

        <div class="modal-body">
            {assign var="row_count" value=$PageNavigator->GetRowCount()}
            <p>{eval var=$Captions->GetMessageString('ChangePageSizeText')}</p>

            <table class="table table-bordered">
                <tr>
                    <th>{$Captions->GetMessageString('RecordsPerPage')}</th>
                    <th>{$Captions->GetMessageString('TotalPages')}</th>
                </tr>
                {foreach from=$PageNavigator->GetRecordsPerPageValues() key=name item=value}
                    <tr>
                        <td>
                            <div class="controls">
                                <label class="radio">
                                    <input type="radio" value="{$name}" name="recperpage">
                                    {$value}
                                </label>
                            </div>
                        </td>
                        <td>{$PageNavigator->GetPageCountForPageSize($name)}</td>
                    </tr>
                {/foreach}
                <tr>
                    <td>
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" value="custom" name="recperpage" checked="checked">
                                {$Captions->GetMessageString('UseCustomPageSize')}
                            </label>
                            <label class="text">
                                <input type="text" value="{$PageNavigator->GetRowsPerPage()|escape:html}" class="input-medium pgui-custom-page-size">
                            </label>
                        </div>

                    </td>
                    <td><span class="custom_page_size_page_count">{$PageNavigator->GetPageCount()}</span></td>
                </tr>
            </table>

        </div>

        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">{$Captions->GetMessageString('Cancel')}</a>
            <a href="#" class="save-changes-button btn btn-primary">{$Captions->GetMessageString('SaveChanges')}</a>
        </div>
    </div>


</div>