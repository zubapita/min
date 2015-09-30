<!-- <!--{$pageName}-->/includes/autoAddForm.inc -->
<form id="<!--{$className}-->Form">
<!--{foreach $columns as $column}-->
<!--{if $column['name']!='id'}-->
<div class="form-group">
	<label class="sr-only" for="<!--{$className}-->-<!--{$column['name']}-->">{$l['<!--{$column['name']}-->']}</label>
	<input type="text" class="form-control input-lg" id="<!--{$className}--><!--{ucfirst($column['name'])}-->" name="<!--{$className}--><!--{ucfirst($column['name'])}-->" placeholder="{$l['<!--{$column['name']}-->']}" value="" />
</div>
<!--{/if}-->
<!--{/foreach}-->
<button type="button" id="<!--{$className}-->SaveButton" class="btn btn-primary btn-block">{$l['save_button']}</button>
<button type="button" id="<!--{$className}-->CancelButton" class="btn btn-default btn-block">{$l['cancel_button']}</button>
<input type="hidden" id="<!--{$className}-->Id" name="<!--{$className}-->Id" value="" />
<input type="hidden" id="Session-token" name="Session-token" value="{$token}" />
</form>
<!-- end of <!--{$pageName}-->/includes/autoAddForm.inc -->
