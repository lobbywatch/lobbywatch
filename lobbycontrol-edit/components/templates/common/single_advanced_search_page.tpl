{capture assign="SideBar"}

    {$PageList}

{/capture}

{capture assign="ContentBlock"}

<table>

    <tr>    

        <td valign="top">
            <div style="margin-bottom: 10px;">
                <h2 class="page_header" style="margin: 0px;">{$Page->GetCaption()}</h2>
            </div>

            {if $Page->GetAdvancedSearchAvailable()}{$AdvancedSearch}{/if}

            {$ContentBlock}

        </td>
    </tr>
</table>

{$Page->GetFooter()}

</body>
{/capture}

{* Base template *}
{include file="common/layout.tpl"}