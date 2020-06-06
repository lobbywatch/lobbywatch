{if $chart.generateTooltipFunctionCode}
{literal}
window['chartData_{/literal}{$uniqueId}{literal}'].generateTooltip = function (row, size, value) {
    var table = window['chartData_{/literal}{$uniqueId}{literal}'].dataTable;
    {/literal}{$chart.generateTooltipFunctionCode}{literal}
}
{/literal}
{/if}