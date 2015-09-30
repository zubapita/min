<?php
/**
 * コマンドラインプログラム用ユーティリティ・ライブラリを提供するクラス
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class CmdLibs
{
	
	/**
	 * 現在のMinアプリの絶対ディレクトリパス
	 * 
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	static $APP_ROOT;

	/**
	 * アプリのrootを返す。
	 * app_root/lib の中にCmdLibs.phpがあるときは、$depth=1
	 * 
	 * @param $depth int
	 * @return absolute file path of application root
	 */
	static function getAppRoot($depth)
	{
		$dir_path = __DIR__;
		$p = explode('/', $dir_path);
		for ($i=1; $i<=$depth; $i++) {
			$dmy = array_pop($p);
		}
		$APP_ROOT = implode('/',$p);
		return $APP_ROOT;
	}

	/**
	 * 指定したディレクトリの親ディレクトリを取得
	 * 
	 * @param string $dir ディレクトリパス
	 * @return absolute file path of parent of application root
	 */
	static function getParentPath($dir)
	{
		$pathArray = explode('/', $dir);
		$dmy = array_pop($pathArray);
		$parentPath = implode('/', $pathArray);
		return $parentPath;
	}


	/**
	 * クラス間のデータ交換に使うDataBridgeクラスをインスタンス化し、
	 * グローバル変数 dataBridgeに保存する。
	 * またアプリのルートディレクトリなどを保存する
	 * CmdLib.phpがあるディレクトリがアプリのルートの直下なら$depth=1
	 * 
	 * @param integer $depth=1 
	 */
	static function setDataBridge($depth=1)
	{
		global $dataBridge;
		$dataBridge = new DataBridge;
		$APP_ROOT = self::getAppRoot($depth);
		$dataBridge->APP_ROOT = $APP_ROOT;
		$dataBridge->dispatch_path = '/';
		$dataBridge->dispatch_class = 'CmdApp';		
		$dataBridge->dispatch_action = 'main';
		
		self::$APP_ROOT = $APP_ROOT;
	}

	/**
	 * コマンドラインの指定のパラメータの値を返す
	 * 
	 * @example $a = CmdLib::getParam('-a');
	 * @param string $switch スイッチ名
	 * @return (string|boolean) スイッチが指定されていればその値。なければfalse
	 */
	static function getParam($switch)
	{
		$argv = $_SERVER['argv'];
		$value = false;
		foreach ($argv as $key=>$param) {
			if ($param==$switch) {
				if (isset($argv[$key+1])) {
					$value = $argv[$key+1];
					break;
				}
			}
		}
		return $value;
	}

	/**
	 * コマンドラインの指定のパラメータが存在するかを返す
	 * 
	 * @example if ($a = CmdLib::getParam('-a')) {...}
	 * @param string $switch スイッチ名
	 * @return boolean スイッチが指定されていればtrue。なければfalse
	 */
	static function checkParam($switch)
	{
		$argv = $_SERVER['argv'];
		$value = false;
		foreach ($argv as $key=>$param) {
			if ($param==$switch) {
				$value = true;
				break;
			}
		}
		return $value;
	}

	/**
	 * 実行中のスクリプト名を返す
	 * 
	 * @return string 現在のスクリプトのファイル名
	 */
	static function scriptName()
	{
		$argv = $_SERVER['argv'];
		return $argv[0];
	}

	/**
	 * メッセージバナー（大型）を標準エラー出力に表示
	 * 
	 * @param string $message 表示するメッセージ
	 * @return void
	 */
	static function bannerBig($message)
	{
		$banner = <<<EOS

====*====*====*====*====*====*====*====*====*====*====*====*
　　　$message
====*====*====*====*====*====*====*====*====*====*====*====*


EOS;
		fputs(STDERR, $banner);
	}

	/**
	 * メッセージバナー（中型）を標準エラー出力に表示
	 * 
	 * @param string $message 表示するメッセージ
	 * @return void
	 */
	static function bannerMid($message)
	{
		$banner = <<<EOS

***************************************
　　　$message
***************************************


EOS;
		fputs(STDERR, $banner);
	}

	/**
	 * メッセージバナー（小型）を標準エラー出力に表示
	 * 
	 * @param string $message 表示するメッセージ
	 * @return void
	 */
	static function bannerSmall($message)
	{
		$banner = <<<EOS

--- $message ---
　　　

EOS;
		fputs(STDERR, $banner);
	}
}

