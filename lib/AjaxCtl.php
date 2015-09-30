<?php
/**
 * AjaxでWebブラウザと非同期通信するための機能を提供するクラス
 *
 * @see view/cmn/js/ajax.js
 * @see view/cmn/template/js/pagingAndSearch.js
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class AjaxCtl extends AppCtl
{
	
	/**
	 * AjaxでPOSTされたデータを配列で返す
	 *
	 * 変数名の'-'は'_'に変換される
	 *
	 * @return array
	 **/
	function getPostedData() {
		$postedData = array();
	
		if($this->C['PRODUCTION_RUN']) {
			$POSTS = $_POST;
		} else {
			$POSTS = $_REQUEST;
		}
	
		if(!empty($POSTS)) {
			foreach($POSTS as $key=>$value) {
				$regularedKey = str_replace('-','_',$key);
				$postedData[$regularedKey] = $value;
			}
		}
		return $postedData;
	}

	/**
	 * json形式で構造データ（基本的には配列）を送信
	 *
	 * これは基底の共通メソッドなので、通常の構造データ送信には
	 * sendData()を使用すること。
	 *
	 * @param array $data 送信するデータ
	 * @return boolean 成功した場合はtrue
	 **/
	function sendJson($data) {
		$jsonValue = json_encode($data);
		header( 'Content-Type: text/javascript; charset=utf-8' );
		echo $jsonValue;
		return true;
	}

	/**
	 * json形式で表示用HTMLを送信
	 *
	 * $debug_dateには適時、JS側でチェックしたい値を入れる。
	 *
	 * @param string $html
	 * @param mix $debug_data
	 * @return boolean 成功した場合はtrue
	 **/
	function sendHtml($html, $debug_data='-') {
		$response = new StdClass;
		$response->status = true;
		$response->html = $html;
		$response->debug = $debug_data;
		return $this->sendJson($response);
	}

	/**
	 * json形式で構造データ（基本的には配列）を送信
	 *
	 * $debug_dateには適時、JS側でチェックしたい値を入れる。
	 *
	 * @param array $data
	 * @param mix $debug_data
	 * @return boolean 成功した場合はtrue
	 **/
	function sendData($data, $debug_data='-') {
		$response = new StdClass;
		$response->status = true;
		$response->data = $data;
		$response->debug = $debug_data;
		return $this->sendJson($response);
	}

	/**
	 * json形式でメッセージ文字列を送信
	 *
	 * 主にアラート表示用
	 * $debug_dateには適時、JS側でチェックしたい値を入れる。
	 *
	 * @param string $message
	 * @param mix $debug_data
	 * @return boolean 成功した場合はtrue
	 **/
	function sendNotice($message, $debug_data='-') {
		$response = new StdClass;
		$response->status = true;
		$response->message = 'notice:'.$message;
		$response->debug = $debug_data;
		return $this->sendJson($response);
	}

	/**
	 * json形式でエラーメッセージ文字列を送信
	 *
	 * 主にアラート表示用
	 * $debug_dateには適時、JS側でチェックしたい値を入れる。
	 *
	 * @param string $message
	 * @param mix $debug_data
	 * @return boolean 成功した場合はtrue
	 **/
	function sendError($message, $debug_data='-') {
		$response = new StdClass;
		$response->status = false;
		$response->message = 'error:'.$message;
		$response->debug = $debug_data;
		return $this->sendJson($response);
	}
	
}

