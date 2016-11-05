<div data-id="{$chart.id}_{$uniqueId}" class="pgui-chart pgui-chart-{$chart.id}" style="height: {$chart.height}px">
    <img class="pgui-chart-loading" src="components/assets/img/loading.gif">
</div>

<script type="text/javascript">
{literal}
    window['chartData_{/literal}{$chart.id}_{$uniqueId}{literal}'] = {
        id: '{/literal}{$chart.id}_{$uniqueId}{literal}',
        type: '{/literal}{$type}{literal}',
        options: {/literal}{to_json value=$chart.options}{literal},
        data: {/literal}{to_json value=$chart.data}{literal}
    };
{/literal}
</script>