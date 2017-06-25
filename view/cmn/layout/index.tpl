<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/cmn/css/cmn.css" type="text/css" media="screen" title="cmn.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    {block name="head"}{/block}
    <title>{block name="title"}{/block}</title>
</head>
<body>
{include file='cmn/includes/header.inc'}
<div id="wrapper" class="container">

{block name="body"}{/block}

</div><!-- /#wrapper -->

{*<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal">
  Launch demo modal
</button>*}


<!-- Modal dialog -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalLabel">Modal title</h4>
      </div>
      <div id="modalBody" class="modal-body"></div>
      <div id="modalFooter" class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {*<button type="button" class="btn btn-primary">Another Button</button>*}
      </div>
    </div>
  </div>
</div>
<!-- /Modal dialog -->


    <script src="//code.jquery.com/jquery-3.2.1.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {* /controller/cmn/cmnCtl.php CmnCtl::conf()が生成するjsを読み込み *}
    <script src="/cmn/conf" type="text/javascript" charset="utf-8"></script>
    <script src="/cmn/js/ajax.js" type="text/javascript" charset="utf-8"></script>
    
    {block name="footer"}{/block}
</body>
</html>