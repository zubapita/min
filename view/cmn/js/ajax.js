/**
 * Ajaxをコンパクトに記述するためのクラス
 * @see lib/AjaxCtl.php
 * @see view/cmn/template/js/pagingAndSearch.js
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
function AjaxFunctions()
{

	/**
	 * クラス内のメソッドから、同じクラスのメソッドやプロパティを参照するための変数
	 */
	var self = this;
	
	/**
	 * Ajax通信中はtrueとなるフラグ
	 */
	var loadingFlag = false;
	
	/**
	 * Ajaxをコンパクトに記述するためのメソッド
	 *
	 * @example AJAX.post('/getList', data, success, failed);
	 * @param string url サーバのエンドポイント
	 * @param mix data Ajaxで送信するデータ
	 * @param function success Ajax成功時にコールバックするメソッド
	 * @param function failed Ajax失敗時にコールバックするメソッド
	 * @return void
	 */
	this.post = function(url, data, success, failed)
	{
		self.loadingFlag = true;
		self.displayLoadingMark();
		
		$.ajax({
		    url: url,			// request URL
		    data: data,			// data for send to server
			type: "POST",		// POST
		    dataType: "json",	// json
		    cache: false,		// diable cache
			success: function(response, status, xhr) {
				if (response.status==true) {
					if (response.html) {
						success(response.html);
					} else if (response.data) {
						success(response.data);
					} else if (response.message) {
						success(response.message);
					}
				} else {
					if (failed) {
						failed(response.message);
					} else if (response.message) {
						if (CONFIG.debugMode) {
							console.log(response.message);
						}
					} else {
						if (CONFIG.debugMode) {
							console.log(url +': response error:'+status);
						}
					}
				}
				self.hideLoadingMark();
				self.loadingFlag = false;
			},
			error: function (xhr ,status,errorThrown) {
				if (CONFIG.debugMode) {
					console.log(url +': transmit error');
				}
				self.hideLoadingMark();
				self.loadingFlag = false;
			}
		});
	};

	/**
	 * Ajaxのデバッグ用メソッド
	 * Test method for Ajax.
	 * 
	 * @param string url サーバのエンドポイント
	 * @param mix data Ajaxで送信するデータ
	 * @param function success Ajax成功時にコールバックするメソッド
	 * @return void
	 */
	this.postCheck = function(url, data, success)
	{
		self.loadingFlag = true;
		self.displayLoadingMark();

		$.ajax({
		    url: url,			// request URL
		    data: data,			// data for send to server
			type: "POST",		// POST
		    dataType: "text",	// text
		    cache: false,		// diable cache
			success: function(response, status, xhr) {
				success(response);
				self.hideLoadingMark();
				self.loadingFlag = false;
			},
			error: function (xhr ,status,errorThrown) {
				if (CONFIG.debugMode) {
					console.log('postCheck:'+url +': transmit error:'+status);
				}
				self.hideLoadingMark();
				self.loadingFlag = false;
			}
		});
	};
	
	/**
	 * Ajaxでファイルも含めたフォームを送信
	 *
	 * type="file"を含むinput要素は、FormDataオブジェクトで取得
	 * FormDataオブジェクトは、キーと値のセットを収集
	 * FormDataオブジェクトはIE9・Android2.3より前のバージョンでは使えない
	 *
	 *
	 * @example AJAX.filePost('/getList', formId, success, failed);
	 * @param string url サーバのエンドポイント
	 * @param string formId Ajaxで送信するフォーム。ファイルも含められる
	 * @param function success Ajax成功時にコールバックするメソッド
	 * @param function failed Ajax失敗時にコールバックするメソッド
	 * @return void
	 */
	this.filePost = function(url, formId, success, failed)
	{
		self.loadingFlag = true;
		self.displayLoadingMark();
		
	    var form = $('#'+formId).get()[0];
	    var formData = new FormData(form);
	
		$.ajax({
		    url: url,			// request URL
		    data: formData,		// data for send to server
			type: "POST",		// POST
		    dataType: "json",	// json
		    cache: false,		// diable cache
			processData: false,	// GETメソッドのクエリ文字への変換をしない
			contentType: false, // FormDataは適切なcontentTypeが設定される
			success: function(response, status, xhr) {
				if (response.status==true) {
					if (response.html) {
						success(response.html);
					} else if (response.data) {
						success(response.data);
					} else if (response.message) {
						success(response.message);
					}
				} else {
					if (failed) {
						failed(response.message);
					} else if (response.message) {
						if (CONFIG.debugMode) {
							console.log(response.message);
						}
					} else {
						if (CONFIG.debugMode) {
							console.log(url +': response error:'+status);
						}
					}
				}
				self.hideLoadingMark();
				self.loadingFlag = false;
			},
			error: function (xhr ,status,errorThrown) {
				if (CONFIG.debugMode) {
					console.log(url +': transmit error');
				}
				self.hideLoadingMark();
				self.loadingFlag = false;
			}
		});
	};
	
	/**
	 * ローディング中マークの表示
	 * Display mark for loading.
	 * 
	 * @return void
	 */
	this.displayLoadingMark = function() {
		 var loadingMark = $('<img>')
		 	.attr('id','loading-mark')
			.attr('src','/cmn/img/loading.gif')
		 	.css('position','fixed')
			.css('top','40%').css('left','45%');

		 $('body').append(loadingMark);
	};

	this.hideLoadingMark = function() {
		$('#loading-mark').remove();
	};

	

	/**
	 * 無限スクロール用メソッドを登録する
	 * Regist method for endless scroll.
	 * 
	 * @param function callback 無限スクロール用のメソッド
	 * @return void
	 */
	this.endlessScroller = function (callback)
	{
		$(document).ready(function(){
			$(window).scroll(function() {
			     var current = $(window).scrollTop() + window.innerHeight;
			     if (current < $(document).height() - 50) return;
				 
				 // Nothing to do while loading. 
			     if (self.loadingFlag) return;
				 
			     callback();
			});
		});
	};
	

	/**
	 * Ajax検索用メソッドを登録する
	 * Regist method for Ajax search.
	 * 
	 * @example AJAX.ajaxSearch('#keyword-form', '#select-date', searchMethod())
	 * @param string keyupDomId onKeyupで発火させるフォームのID
	 * @param string changeDomId onChangeで発火させるフォームのID
	 * @param function callback
	 * @return void
	 */
	this.ajaxSearch = function (keyupDomId,changeDomId,callback)
	{
		$(document).ready(function(){

			var preFunc = null;
			var preInput = '';
			var input = '';

			// onKeyupで発火させるidを登録
			// ex) keyupDomId = '#keyword-form'
			$(keyupDomId).on('keyup search', function() {
				input = $.trim($(this).val());
				if (preInput !== input) {
					clearTimeout(preFunc);
					preFunc = setTimeout(callback(), 500);
				}
				preInput = input;
			});
		
			// onChangeで発火させるidを登録
			// ex) changeDomId = '#select-form'
			if (changeDomId) {
				$(changeDomId).on('change', function(){
					callback();
				});
			}
		});
	};
	
	
	// Parse GET query string
	/**
	 * URLのQueryストリングをパースすてGET配列を返す
	 */
	this.getGETParam = function ()
	{
		var queryString = document.URL.replace(/.*?\?(.*)/,"$1");
		var query = queryString.split('&');
		var GETParam = new Object();
		for (var i = 0; i < query.length; i++) {
			tmp = query[i].split('=');
			GETParam[tmp[0]] = tmp[1];
		}

		return GETParam;
	};
	

}

var AJAX = new AjaxFunctions();

