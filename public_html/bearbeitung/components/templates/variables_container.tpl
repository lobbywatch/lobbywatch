<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Variable name</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
{foreach from=$Variables key=name item=value}
	<tr>
		<td>{$name}</td>
		<td>{$value}</td>
	</tr>
{/foreach}
</tbody>
</table>