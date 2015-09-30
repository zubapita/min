<!-- <!--{$pageName}-->/includes/autoRecord.inc -->
<div class="table-responsive">
<table class="table table-striped table-hover">
<tbody>
{$v = $<!--{$modelName}-->}
<!--{foreach $columns as $column}-->
<!--{if $column['name']!='id'}-->
<tr>
	<th>{$l['<!--{$column['name']}-->']}</th>
	<td>{$v['<!--{$column['name']}-->']}</td>
</tr>
<!--{/if}-->
<!--{/foreach}-->
</tbody>
</table>
<form id="<!--{$className}-->Edit">
<button type="button" id="<!--{$className}-->EditButton" class="btn btn-primary btn-block">{$l['edit_button']}</button>
<button type="button" id="<!--{$className}-->ReturnButton" class="btn btn-default btn-block">{$l['return_button']}</button>
<input type="hidden" id="<!--{$className}-->Id" name="<!--{$className}-->Id" value="{$v['id']}" />
</form>
</div>
<!-- end of <!--{$pageName}-->/includes/autoRecord.inc -->
