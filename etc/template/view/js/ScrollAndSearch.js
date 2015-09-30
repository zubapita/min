/**
 * <!--{$pageName}--> の無限スクロールとAJAX検索
 * @see lib/AjaxCtl.php
 * @see view/cmn/js/ajax.js
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
function <!--{$className}-->ScrollAndSearch()
{
	/**
	 * クラス内部から自分自身を参照するための変数
	 */
	var self = this;

	/**
	 * AJAXで指定されたページのデータ一覧を取得する
	 * 
	 * @param integer pageNum 取得するページ番号
	 * @return boolean 常にfalse
	 */
	this.getList = function(pageNum)
	{
		var searchKeyword = $('#searchKeyword').val();
		//var dateSelect = $('#dateSelect').val();
		var clearList = false;

		if(pageNum==0) {
			var currentPage = $('#currentPage').val();
			var maxPage = parseInt($('#maxPage').val());
			pageNum = parseInt(currentPage) + 1;
			if(pageNum > maxPage) {
				return false;
			}
		} else {
			clearList = true;
		}
	
		var url = '/<!--{$pageName}-->/getList';
		var data = {
			searchKeyword: searchKeyword,
		//	dateSelect: dateSelect,
			pageNum: pageNum
		};
		var success = function(data) {
			if(clearList) $('#<!--{$className}-->List').empty();
			$('#<!--{$className}-->List').append(data.listHtml);
			$('#allItemsNum').text(data.pager.allItemsNum);
			$('#currentPage').val(data.pager.currentPage);
			$('#maxPage').val(data.pager.maxPage);
		}
		AJAX.post(url, data, success);
	
		return false;
	};
	
	
} /* end of <!--{$className}-->ScrollAndSearch */

var <!--{$className}-->SAS = new <!--{$className}-->ScrollAndSearch();

AJAX.endlessScroller(function(){
	<!--{$className}-->SAS.getList(0);
});

var keyup = '#searchKeyword';
var change = '';
var callback = function() {
	<!--{$className}-->SAS.getList(1);
};

AJAX.ajaxSearch(keyup, change, callback);
