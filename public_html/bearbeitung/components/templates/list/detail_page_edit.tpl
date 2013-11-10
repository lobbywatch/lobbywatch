{capture assign="ContentBlock"}

    <div class="page-header">
        <h1>{$Page->GetCaption()}</h1>
    {include file="export-button.tpl" Items=$Page->GetExportButtonsViewData()}
    </div>
{*
        <table>
            <tr>
                <td><h1>{$Page->GetShortCaption()}</h1></td>
                <td style="padding-left: 10px;">

                    <div class="btn-group">
                    <button style="position: relative; top: 4px;" class="btn btn-mini dropdown-toggle" data-toggle="dropdown" >
                        Export
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#">Export to excel</a></li>
                        <li><a href="#">Export to word</a></li>
                        <li><a href="#">Export to csv</a></li>
                        <li><a href="#">Export to xml</a></li>
                        <li><a href="#">Export to pdf</a></li>
                    </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div> *}

    {if $Page->GetGridHeader() != ''}
        <div class="well">
            {$Page->GetGridHeader()}
        </div>
    {/if}


<h5>{$Captions->GetMessageString('MasterRecord')}
    (<a href="{$Page->GetParentPageLink()|escapeurl}">{$Captions->GetMessageString('ReturnFromDetailToMaster')}</a>)
</h5>

{$MasterGrid}
<br />

    {if count($SiblingDetails) > 1}
        <ul class="nav nav-tabs">
            {foreach from=$SiblingDetails item=SiblingDetail name=SiblingDetailsSection}
                <li class="{if $DetailPageName == $SiblingDetail.Name}active{/if}">
                    <a href="{$SiblingDetail.Link|escapeurl}">
                        {$SiblingDetail.Caption}
                    </a>
                </li>
            {/foreach}
        </ul>
    {/if}


    {$PageNavigator}

    {$Grid}

    {$PageNavigator2}
{/capture}

{if $Page->GetShowPageList()}
{capture assign="SideBar"}

    {$PageList}

{/capture}
{/if}

{capture assign="DebugFooter"}{$Variables}{/capture}

{capture assign="Footer"}

    {$Page->GetFooter()}

{/capture}


{* Base template *}
{include file="common/list_page_template.tpl"}