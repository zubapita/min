<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" media="screen">
	{*<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">*}
	<link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/cerulean/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/cmn/css/cmn.css" type="text/css" media="screen" title="cmn.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	{block name="head"}{/block}
	<title>{block name="title"}{/block}|Site name</title>
</head>
<body>
<div id="wrapper" class="container">
{include file='cmn/includes/header.inc'}

{block name="body"}{/block}
</div><!-- /#wrapper -->

	<script src="http://code.jquery.com/jquery-1.11.2.js"></script>
	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	{* /controller/cmn/cmnCtl.php CmnCtl::conf()が生成するjsを読み込み *}
	<script src="/cmn/conf" type="text/javascript" charset="utf-8"></script>
	<script src="/cmn/js/ajax.js" type="text/javascript" charset="utf-8"></script>
	
	{block name="footer"}{/block}
</body>
</html>