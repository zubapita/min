/**
 * <!--{$pageName}--> のAjaxデータ送信
 * @see lib/AjaxCtl.php
 * @see view/cmn/js/ajax.js
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
function <!--{$className}-->Post()
{
	/**
	 * クラス内部から自分自身を参照するための変数
	 */
	var self = this;
	
	/**
	 * AJAXで指定されたページにForm内のデータを送信する
	 * 
	 * @param 
	 * @return boolean 常にfalse
	 */
	this.save = function ()
	{
		var data = {
<!--{foreach $columns as $column}-->
			<!--{$column['name']}-->: $('#<!--{$className}--><!--{ucfirst($column['name'])}-->').val(),
<!--{/foreach}-->
			token: $('#Session-token').val(),
		};
		var url = '/<!--{$pageName}-->/save';
		var success = function(data) {
			if (data.result) {
				var id = data.id;
				location.href='/<!--{$pageName}-->/?id='+id;
			} else if (data.message) {
				alert(data.message);
			}
		}
		AJAX.post(url, data, success);
	
		return false;
	};
	
	/**
	 * 初期化
	 */
	this.init = function ()
	{
		$(document).ready(function()
		{
			
			// ボタン動作設定
			if ($('#<!--{$className}-->CancelButton')[0]) {
				var cancelButtonDomId = '#<!--{$className}-->CancelButton';
				$(cancelButtonDomId).on('click', function ()
				{
					history.back();
				});
			}

			if ($('#<!--{$className}-->EditButton')[0]) {
				var editButtonDomId = '#<!--{$className}-->EditButton';
				$(editButtonDomId).on('click', function ()
				{
					var GET = AJAX.getGETParam();
					location.href='/<!--{$pageName}-->/edit?id='+GET['id'];
				});
			}

			if ($('#<!--{$className}-->ReturnButton')[0]) {
				var returnButtonDomId = '#<!--{$className}-->ReturnButton';
				$(returnButtonDomId).on('click', function ()
				{
					history.back();
				});
			}

			if ($('#<!--{$className}-->SaveButton')[0]) {
				var saveButtonDomId = '#<!--{$className}-->SaveButton';
				$(saveButtonDomId).on('click', function ()
				{
					$('#<!--{$className}-->Form').bootstrapValidator('validate');
				});
			}

			// バリデーション設定
			if ($('#<!--{$className}-->Form')[0]) {
				$('#<!--{$className}-->Form').bootstrapValidator({
					onSuccess: function(e) {
						self.save();
					},
					feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
					},
					live: 'enabled',
					fields: {
<!--{foreach $columns as $column}-->
<!--{if $column['name']!='id'}-->
						<!--{$className}--><!--{ucfirst($column['name'])}-->: {
							validators: {
								notEmpty: { message: '<!--{$column['name']}-->は必須です' }
							}
						},
<!--{/if}-->
<!--{/foreach}-->
					}
				});
			}

		});
	}
	
} /* end of <!--{$className}-->Post */

var <!--{$className}-->Post = new <!--{$className}-->Post();

<!--{$className}-->Post.init();