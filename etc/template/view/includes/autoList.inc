<!-- <!--{$pageName}-->/includes/autoList.inc -->
<div class="table-responsive">
<table class="table table-striped table-hover">
	<thead>
		<tr>
<!--{foreach $columns as $column}-->
			<th>{$l['<!--{$column['name']}-->']}</th>
<!--{/foreach}-->
		</tr>
	</thead>
	<tbody>
{foreach $<!--{$modelName}--> as $row name="<!--{$modelName}-->"}
		<tr>
<!--{foreach $columns as $column}-->
<!--{if $column['name']=='id'}-->
			<td><a href="./record/?id={$row['id']}">{$row['id']}</a></td>
<!--{else}-->
			<td>{$row['<!--{$column['name']}-->']|truncate:40}</td>
<!--{/if}-->
<!--{/foreach}-->
		</tr>
{/foreach}
	</tbody>
</table>
</div>
<!-- end of <!--{$pageName}-->/includes/autoList.inc -->
