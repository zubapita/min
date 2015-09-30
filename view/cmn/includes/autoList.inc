<!-- cmn/includes/autoList.inc -->
<div class="table">
<table>
{foreach $table_view as $row name="table_view"}
{if $smarty.foreach.table_view.first}
	<tr>
{foreach $row as $key=>$value}
		<th>{$key}</th>
{/foreach}
	</tr>
{/if}
	<tr>
{foreach $row as $key=>$value}
{if $key=='id'}
		<td><a href="display?id={$value}">{$value}</a></td>
{else}
		<td>{$value}</td>
{/if}
{/foreach}
	</tr>
{foreachelse}
	<tr>
		<td>Empty.</td>
	</tr>
{/foreach}
</table>
</div>
<!-- cmn/includes/autoList.inc -->
