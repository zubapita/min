<?php
/**
 * Minアプリの基礎となるクラス
 *
 * Webアプリのコントローラとモデル、コマンドライン版のアプリは
 * いずれもこのAppCtlクラスを継承しなければならない
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
abstract class AppCtl
{
	/**
	 * 現在のMinアプリの絶対ディレクトリパス
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	public $APP_ROOT;

	/**
	 * Minアプリの設定を保存する配列
	 *
	 * @see /etc/conf.php appConfigure::get()
	 * @see Console::__construct()
	 */
	public $C = array();

	/**
	 * Webアプリとして実行したときのルートディレクトリからのパス
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	public $dispatch_path;

	/**
	 * Dispatcherによって起動されたクラス
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	public $dispatch_class;

	/**
	 *  Dispatcherによって起動されたアクション
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	public $dispatch_action;

	/**
	 *  Dispatcherによって設定された言語
	 *
	 * @see Dispatcher::dispatch()
	 */
	public $dispatch_lang;

	/**
	 *  デバッグ用トレース設定
	 *
	 * @see Dispatcher::dispatch()
	 */
	public $dispatch_trace;

	/**
	 * DB接続のハンドラを保存する配列
	 */
	static private $db_instances = array();

	/**
	 * テーブルを操作するためのインスタンスを保存する配列
	 */
	static private $table_instances = array();

	/**
	 * モデルのインスタンスを保存する配列
	 */
	static private $model_instances = array();

	/**
	 * AppCtlクラスのコンストラクタ。
	 * クラス間のデータ交換に使うDataBridgeクラスをインスタンス化し、
	 * グローバル変数 dataBridgeに保存する。
	 * またアプリのルートディレクトリなどを保存する
	 *
	 */
	public function __construct()
	{
		global $dataBridge;
		$this->APP_ROOT = $dataBridge->APP_ROOT;
		$this->dispatch_path = $dataBridge->dispatch_path;
		$this->dispatch_class = $dataBridge->dispatch_class;
		$this->dispatch_action = $dataBridge->dispatch_action;
		$this->dispatch_lang = $dataBridge->dispatch_lang;
		$this->dispatch_trace = $dataBridge->dispatch_trace;

		$this->C = appConfigure::get();
	}

	/**
	 * DBをインスタンス化して返す。
	 * 予め、model/db/ の下にDBクラスの定義ファイルを作成しておく。
	 *
	 * @see bin/makeNewApp.php
	 * @see bin/makeDbClassFile.php
	 * @param string $dbname データベース名
	 * @return (object|boolean) DBクラスのインスタンス。失敗したらfalse
	 */
	function getDB($dbname)
	{

		if (isset(self::$db_instances[$dbname])) {
			return self::$db_instances[$dbname];
		} else {
			$filePath = $this->APP_ROOT."/model/_def/db/$dbname.php";

			if (file_exists($filePath)) {
				if (!class_exists($dbname)) {
					require_once $filePath;
				}
				self::$db_instances[$dbname] = new $dbname;
				return self::$db_instances[$dbname];
			} else {
				return false;
			}
		}
	}

	/**
	 * DB内のTABLEをインスタンス化して返す。
	 * 予め、model/db/ の下にTABLEクラスの定義ファイルを作成しておく。
	 *
	 * @see bin/makeTableClassFiles.php
	 * @param object $db_instance DBインスタンス
	 * @param string $tableName テーブル名
	 * @return (object|boolean) TABLEクラスのインスタンス。失敗したらfalse
	 */
	function getTable($db_instance, $tableName)
	{
		$dbname = get_class($db_instance);

		if (isset(self::$table_instances[$dbname][$tableName]) &&
			is_object(self::$table_instances[$dbname][$tableName])) {
			return self::$table_instances[$dbname][$tableName];
		} else {
			$filePath = $this->APP_ROOT."/model/_def/db/$dbname/$tableName.php";

			if (file_exists($filePath)) {
				if (!class_exists($tableName)) {
					require_once $filePath;
				}
				self::$table_instances[$dbname][$tableName] = new $tableName($db_instance);
				return self::$table_instances[$dbname][$tableName];
			} else {
				Console::log("Error! : table Clsss file $filePath is not found.");
				return false;
			}
		}
	}

	/**
	 * MODELをインスタンス化して返す。
	 * 予め、model/ の下にMODELクラスの定義ファイルを作成しておく。
	 * MODELがDBを扱う場合はDataListやDataRecordを継承したクラスにする。
	 *
	 * @see lib/DataList.php
	 * @see lib/DataRecord.php
	 * @param string $modelName モデル名
	 * @return (object|boolean) MODELクラスのインスタンス。失敗したらfalse
	 */
	function getModel($modelName)
	{
		if (isset(self::$model_instances[$modelName]) &&
			is_object(self::$model_instances[$modelName])) {
			return self::$model_instances[$modelName];
		} else {

			$pathChecker = function($my, $modelName) {
				$separator = '_';    //区切り文字
				$tmp = preg_replace('/([a-z])([A-Z])/', "$1$separator$2", $modelName); // "sampleModelList" => "sample_Model_List"
				$tmpArray = explode('_', $tmp);

				do {
					$dmy = array_pop($tmpArray);
					$modelGroup = lcfirst(implode('', $tmpArray));
					$filePath =
						$my->APP_ROOT."/model/$modelGroup/$modelName.php";
					if(file_exists($filePath)) {
						return $filePath;
					}
				} while (!empty($tmpArray));

				Console::log("Error! : modelClassFile $filePath is not found.");
				return false;
			};

			if ($filePath = $pathChecker($this, $modelName)) {
				if (!class_exists($modelName)) {
					require_once $filePath;
				}
				self::$model_instances[$modelName] = new $modelName();
				return self::$model_instances[$modelName];
			} else {
				Console::log("Error! : model $modelName is not found.");
				return false;
			}
		}
	}

	/**
	 * テンプレートエンジンSmartyのインスタンスを保存する
	 */
	public $view;

	/**
	 * Smartyのテンプレートのファイル名を保存する
	 */
	public $view_template;

	/**
	 * VIEW = Smartyの初期化
	 *
	 * @return void
	 */
	public function initView()
	{
		$this->view = new Smarty();
		$this->view->setTemplateDir($this->APP_ROOT.'/view');
		$this->view->setCompileDir($this->APP_ROOT.'/var/compiled');
		$this->view_template =
			 substr($this->dispatch_path,1).$this->dispatch_action.'.html';

		$this->view->assign('g', $_GET);
		$this->view->assign('p', $_POST);
		$this->view->assign('r', $_REQUEST);
		$this->view->assign('s', $_SERVER);
		if (isset($_SESSION)) { $this->view->assign('ss', $_SESSION); }
		$this->view->assign('APP_ROOT', $this->APP_ROOT);
		$this->view->assign('dispatch_path', $this->dispatch_path);
		$this->view->assign('dispatch_class', $this->dispatch_class);
		$this->view->assign('dispatch_action', $this->dispatch_action);

		if ($this->dispatch_lang=='jp') {
			$this->view->assign('lang', '');
		} else {
			$this->view->assign('lang', '/'.$this->dispatch_lang);
		}
		$l = $this->getLangResource();
		$this->view->assign('l', $l);

		$this->view->assign('debugMode', Console::$debugMode);
	}

	/**
	 * 言語リソースの取得
	 *
	 * @return array
	 */
	public function getLangResource()
	{
		# 共通リソース
		$jpCmnResource = include $this->APP_ROOT."/view/cmn/lang/jp.php";
		if ($this->dispatch_lang=='jp') {
			$cmnResource = $jpCmnResource;
		} else {
			$lang = $this->dispatch_lang;
			if (file_exists($this->APP_ROOT."/view/cmn/lang/$lang.php")) {
				$langCmnResource = include $this->APP_ROOT."/view/cmn/lang/$lang.php";
			} else {
				$langCmnResource = array();
			}
			$cmnResource = array_merge($jpCmnResource, $langCmnResource);
		}
		
		# クラス別
		$path = $this->dispatch_path;
		if (file_exists($this->APP_ROOT."/view".$path."lang/jp.php")) {
			$jpResource = include $this->APP_ROOT."/view".$path."lang/jp.php";
		} else {
			$jpResource = array();
		}
		
		if ($this->dispatch_lang=='jp') {
			$classResource = $jpResource;
		} else {
			$lang = $this->dispatch_lang;
			if (file_exists($this->APP_ROOT."/view".$path."lang/$lang.php")) {
				$langResource = include $this->APP_ROOT."/view".$path."lang/$lang.php";
			} else {
				$langResource = array();
			}
			$classResource = array_merge($jpResource, $langResource);
		}
		
		return array_merge($cmnResource, $classResource);

	}

	/**
	 * （Webサーバからクライアントに）XMLを送信する
	 *
	 * @param string $xml 送信するXML
	 * @return void
	 */
	public function sendXML($xml) {
		header('Last-Modified: ' . date("D M j G:i:s T Y"));
		header('Content-type: application/xml; charset=UTF-8');
		echo $xml;
	}


	/**
	 * （Webサーバからクライアントに）JavaScriptを送信する
	 *
	 * @param string $js 送信するJavaScript
	 * @return void
	 */
	public function sendJS($js) {
		header('Last-Modified: ' . date("D M j G:i:s T Y"));
		header("Content-Type: text/javascript");
		echo $js;
	}

	/**
	 * （Webサーバからクライアントに）CSSを送信する
	 *
	 * @param string $css 送信するCSS
	 * @return void
	 */
	public function sendCSS($css) {
		header('Last-Modified: ' . date("D M j G:i:s T Y"));
		header("Content-Type: text/css");
		echo $css;
	}


	/**
	 * Ajax操作クラスのインスタンスを保存する
	 */
	public $ajax;

	/**
	 * Ajaxクラスの初期化（生成と保存）ｓ
	 */
	public function initAjax() {
		$this->ajax = new AjaxCtl;
	}

	/**
	 * 認証用Authクラスのインスタンスを保存する
	 */
	public $auth;

	/**
	 * Authクラスの初期化（生成と保存）ｓ
	 */
	public function initAuth() {
		$this->auth = new AuthCtl;
	}


	/**
	 * http GETから数値を取得する
	 *
	 * @param string $name 数値を取得したいGET変数の名前
	 * @param mix $defaultValue GET変数が空|存在しない場合の初期設定値。
	 * @return mix 取得したGET変数の値。もしくは初期設定値。
	 */
	function getGETNumValue($name, $defaultValue) {
		if(isset($_GET[$name]) && !empty($_GET[$name])) {
			$value = $_GET[$name];
		} else {
			$value = $defaultValue;
		}
		return $value;
	}

	/**
	 * http GETから文字列を取得する
	 *
	 * @param string $name 取得したいGET変数の名前
	 * @param mix $defaultValue GET変数が空|存在しない場合の初期設定値。
	 * @return mix 取得したGET変数の値。もしくは初期設定値。
	 */
	function getGETStrValue($name, $defaultValue) {
		if(isset($_GET[$name])) {
			$value = $_GET[$name];
		} else {
			$value = $defaultValue;
		}
		return $value;
	}

	/**
	 * http POSTから数値を取得する
	 *
	 * @param string $name 数値を取得したいPOST変数の名前
	 * @param mix $defaultValue POST変数が空|存在しない場合の初期設定値。
	 * @return mix 取得したPOST変数の値。もしくは初期設定値。
	 */
	function getPOSTNumValue($name, $defaultValue) {
		if(isset($_POST[$name]) && !empty($_POST[$name])) {
			$value = $_POST[$name];
		} else {
			$value = $defaultValue;
		}
		return $value;
	}

	/**
	 * http POSTから文字列を取得する
	 *
	 * @param string $name 取得したいPOST変数の名前
	 * @param mix $defaultValue POST変数が空|存在しない場合の初期設定値。
	 * @return mix 取得したPOST変数の値。もしくは初期設定値。
	 */
	function getPOSTStrValue($name, $defaultValue) {
		if(isset($_POST[$name])) {
			$value = $_POST[$name];
		} else {
			$value = $defaultValue;
		}
		return $value;
	}

	/**
	 * 指定のURLにリダイレクトする
	 *
	 * @param string $url リダイレクト先のURL
	 */
	function redirect($url) {
	    header("HTTP/1.1 301 Moved Permanently");
	    header("Location: ".$url);
		exit;
	}


}



