<?php
/**
 * リクエストされたURLをパースして、コントローラーを決定して起動する
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class Dispatcher
 {

	/**
	 * Webアプリとして実行したときのルートディレクトリからのパス
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	 protected static $path;

	/**
	 * 起動するクラス
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	protected static $class;

	/**
	 * 起動するクラスのファイル名
	 */
	protected static $classFile;

	/**
	 *  起動するアクション
	 *
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	protected static $action;

	/**
	 * デバッグ用トレース指定
	 *
	 */
	protected static $trace = false;

	/**
	 * 表示言語
	 *
	 */
	protected static $lang = 'jp';

	/**
	 * リクエストされたURLをパースして、ClassとActionを決定
	 *
	 * @param string $APP_ROOT 現在のMinアプリの絶対ディレクトリパス
	 * @return void
	 */
	protected static function setPathAndAction($APP_ROOT)
	{
		# cssフォルダとjsフォルダのファイルはそのまま吐き出す
		if (preg_match("!^/.*/(css|js)/.*!", $_SERVER['SCRIPT_NAME'], $m)) {
			$filePath = $APP_ROOT.'/view'.$_SERVER['SCRIPT_NAME'];

			if (file_exists($filePath)) {
				$time = filemtime($filePath);
				header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');

				if ($m[1]=='css') {
					header("Content-Type: text/css");
				} elseif ($m[1]=='js') {
					header("Content-Type: text/javascript");
				}
				readfile($filePath);
			} else {
				header("HTTP/1.0 404 Not Found");
				echo "404 CSS|JS Not Found";
			}

			exit;
		}

		# （/imgフォルダ外の）JPEG、PNG、GIF画像を吐き出す
		if (preg_match("!^/(.*)\.(jpeg|JPEG|jpg|JPG|png|PNG|gif|GIF)!", $_SERVER['SCRIPT_NAME'], $m)) {
			$filePath = $APP_ROOT.'/var/images'.$_SERVER['SCRIPT_NAME'];
			Console::log($filePath);
			if (file_exists($filePath)) {
				self::sendImage($filePath);
			} else {
				header("HTTP/1.0 404 Not Found");
				echo "404 Image Not Found";
			}

			exit;
		}
		
		# 言語を選択
		if (preg_match("!^/(en|fr)/(.*)!", $_SERVER['SCRIPT_NAME'], $m)) {
			$SCRIPT_NAME = '/'.$m[2];
			
			self::$lang = $m[1];
		} else {
			$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
			self::$lang = 'jp';
		}

		# '/' にmatch
		if ($SCRIPT_NAME=='/') {

			if (self::$trace) {
				Console::log('Dispatcher:A');
				Console::log($m);
			}

			self::$path = '/';
			self::$class = 'IndexCtl';
			self::$classFile = '/IndexCtl.php';
			self::$action = 'index'; // action = function index()
			return;
		}

		# '/abcd' にmatch
		if (preg_match("|^/([^/]*)$|", $SCRIPT_NAME, $m)) {

			if (self::$trace) {
				Console::log('Dispatcher:B');
				Console::log($m);
			}

			self::$path = '/';
			self::$class = 'IndexCtl';
			self::$classFile = '/IndexCtl.php';
			self::$action = $m[1]; // action = function abcd()
			return;
		}

		# '/abcd/' にmatch (actionを省略)
		if (preg_match("|^/([^/]*)/$|", $SCRIPT_NAME, $m)) {

			if (self::$trace) {
				Console::log('Dispatcher:C');
				Console::log($m);
			}

			self::$path = $SCRIPT_NAME; // pass = /abcd/
			self::$class = ucfirst($m[1]).'Ctl'; // class = class AbcdCtl
			self::$classFile = self::$path.self::$class.'.php'; // file = /abcd/AbcdCtl.php
			self::$action = 'index'; // action = function index()
			return;
		}

		# '/abcd/efg'や'/abcd/efg/hij' にmatch
		if (preg_match("|^/(.*)/([^/]*?[^/])$|", $SCRIPT_NAME, $m)) {

			if (self::$trace) {
				Console::log('Dispatcher:D');
				Console::log($m);
			}

			self::$path = '/'.$m[1].'/'; // pass = /abcd/efg/
			$tArray = explode('/', $m[1]);
			//self::$class = array_pop($tArray).'Ctl'; // class = class efgCtl

			foreach($tArray as $key=>$val) {
				$tArray[$key] = ucfirst($val);
			}
			self::$class = implode('',$tArray).'Ctl'; //  class = class AbcdEfgCtl

			self::$classFile = self::$path.self::$class.'.php'; // file = /abcd/efg/AbcdEfgCtl.php
			self::$action = $m[2]; // action = function hij()
			return;
		}

		# '/abcd/efg/'や'/abcd/efg/hij/' にmatch (actionを省略)
		if (preg_match("|^/(.*)/([^/]*?)/$|", $SCRIPT_NAME, $m)) {

			if (self::$trace) {
				Console::log('Dispatcher:E');
				Console::log($m);
			}

			self::$path = '/'.$m[1].'/'.$m[2].'/'; // pass = /abcd/efg/hij/
			$tArray = explode('/', $m[1].'/'.$m[2]);
			foreach($tArray as $key=>$val) {
				$tArray[$key] = ucfirst($val);
			}
			self::$class = implode('',$tArray).'Ctl'; //  class = class AbcdEfgHijCtl

			self::$classFile = self::$path.self::$class.'.php'; // file = /abcd/efg/hij/AbcdEfgHij.php
			self::$action = 'index'; // action = function index()
			return;
		}
	}


	/**
	 * リクエストされたURLに対応するコントローラーを起動する
	 *
	 * @return void
	 */
	public static function dispatch()
	{
		$tArray = explode('/', __DIR__);
		array_pop($tArray);
		$APP_ROOT = implode('/', $tArray);
		self::setPathAndAction($APP_ROOT);

		if (self::$trace) {
			Console::log(date("Y-m-d H:i:s"));
			Console::log('classFile='.self::$classFile);
			Console::log('action='.self::$action);
			Console::log('path='.self::$path);
		}

		global $dataBridge;
		$dataBridge = new DataBridge;
		$dataBridge->APP_ROOT = $APP_ROOT;
		$dataBridge->dispatch_path = self::$path;
		$dataBridge->dispatch_class = self::$class;
		$dataBridge->dispatch_action = self::$action;
		$dataBridge->dispatch_lang = self::$lang;
		$dataBridge->dispatch_trace = self::$trace;

		$class = new self::$class;
		$action = self::$action;
		$class->$action();
	}
	
	/**
	 * 画像ファイルを送信する
	 * 
	 * $file['name'] = 送出する画像ファイルのファイル名
	 * $file['mtime'] = 送出する画像ファイルの最新更新日時
	 * $file['size'] = 送出する画像ファイルのファイルサイズ
	 * $file['mimetype'] = 送出する画像ファイルのMIMETYPE
	 * 
	 * @param array $file 送信するファイル情報配列
	 * @return false|void
	 */
	public static function sendImage($filePath)
	{
		$file = self::getImageFileInfo($filePath);
		if (!$file) {
			return false;
		}
		
		// レスポンスヘッダ取得
		// getallheaders関数が使えない環境に配慮
		$_REQUEST_HEADER =
		  ( function_exists("getallheaders") ? getallheaders() : array() );

		// Not Modified でいいのかどうか確認
		$not_modified = (
		  isset($_REQUEST_HEADER["If-Modified-Since"]) &&
		  strtotime($_REQUEST_HEADER["If-Modified-Since"])
		      == strtotime($file["mtime"]) ? true : false
		);

		// 最終更新日が変わっていなければ、Not Modified
		if( $not_modified )
		  header("HTTP/1.1 304 Not Modified");

		header("Content-Type: " . $file["mimetype"]);
		header("Content-Transfer-Encoding: binary");
		header("Content-Disposition: inline; filename=" . $file["name"]);
		header("Content-Length: " . $file["size"]);

		// ファイルの最終更新日を明示して、次回からIf-Modified-Sinceを送ってくるように仕向ける。
		header("Last-Modified: " . gmstrftime("%a, %d %b %Y %H:%M:%S GMT", strtotime($file["mtime"])));

		// 作法として、現在から1年後の日付を有効期限にする。
		header("Expires: " . gmstrftime("%a, %d %b %Y %H:%M:%S GMT", time() + 60 * 60 * 24 * 365));

		// 必ずキャッシュが新鮮かどうか確認させる。
		header("Cache-Control: must-revalidate");

		// 初めてか、最終更新日が変わっている場合はデータ送信
		if( !$not_modified )
			readfile($filePath);

		exit;
	}


	/**
	 * 画像ファイルの情報を取得する
	 * 
	 * $file['name'] = 送出する画像ファイルのファイル名
	 * $file['mtime'] = 送出する画像ファイルの最新更新日時
	 * $file['size'] = 送出する画像ファイルのファイルサイズ
	 * $file['ext'] = 送出する画像ファイルの拡張子
	 * $file['mimetype'] = 送出する画像ファイルのMIMETYPE
	 * 
	 * @param string $filePath 送信するファイルのパス
	 * @return false|array
	 */
	public static function getImageFileInfo($filePath)
	{
		if (!file_exists($filePath)) {
			return false;
		}
		
		$file = array();
		$file['name'] = basename($filePath);
		$file['mtime'] = filemtime($filePath);
		$file['size'] = filesize($filePath);
		
		$pathParts = pathinfo($filePath);
		$file['ext'] = strtolower($pathParts['extension']);
	
		switch($file['ext']) {
			case 'gif':
				$file['mimetype'] = 'image/gif';
				break;
			case 'jpeg':
			case 'jpg':
				$file['mimetype'] = 'image/jpeg';
				break;
			case 'png':
				$file['mimetype'] = 'image/png';
				break;
		}
		
		return $file;
	}


	public static function setTrace($flag)
	{
		return self::$trace = $flag;
	}

}



