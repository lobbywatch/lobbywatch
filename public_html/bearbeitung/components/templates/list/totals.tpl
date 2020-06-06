{if $Totals}
    <tr>
        {foreach item=Total from=$Totals}
            <td {if $Total.Align != null} align="{$Total.Align}"{/if}>{$Total.Value}</td>
        {/foreach}
    </tr>
{/if}
