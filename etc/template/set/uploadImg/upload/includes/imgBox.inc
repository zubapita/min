	<script id="imgBoxTemplate" type="text/jarty">
	{literal}
		<div class="itemBox">
			<div class="imageBox">
				<img src="/{$filename}" width="{$width}" height="{$height}" alt="" />
			</div>
		</div>
	{/literal}
	</script>

	<style>
	.imageBox {
		border :solid 1px #444;
		margin:5px;
		padding:5px;
		background-color:#eee;
		text-align:center;
	}
	.itemBox {
		float:left;
	}
	</style>
