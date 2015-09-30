<?php
/**
 * PHPConsoleを使用したデバッグ環境
 * Console::log(str)で、JSのconsole.log(str)と同じように
 * Google Chromeの JavaScriptコンソールにデバッグ情報を表示できる
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
PhpConsole\Helper::register(); 
class Console
{
	/**
	 * 開発/デバッグ中はtrue、本番運用中はfalseとなるフラグ
	 * 
	 * @see Console::__construct()
	 */
	public static $debugMode = true;

	/**
	 * Minアプリの設定を保存する配列
	 * 
	 * @see /etc/conf.php appConfigure::get()
	 * @see Console::__construct()
	 */
	public $C = array();
	
	/**
	 * PHPConsoleを初期化、登録
	 * 
	 * @return void
	 */
	public function __construct() 
	{

		$this->C = appConfigure::get();
		if ($this->C['PRODUCTION_RUN']) {
			Console::$debugMode = 'false';

		} else {
			Console::$debugMode = 'true';
			//Console::log($this->C);
			
			global $handler;
			$handler = PhpConsole\Handler::getInstance();
			$handler->start(); // start handling PHP errors & exceptions
			$handler->getConnector()->
				setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
			
		}
		
	}
	
	/**
	 * ChromeのJavaScript コンソールにメッセージを出力
	 * 
	 * @param string $message 出力する文字列
	 * @return void
	 */
	public static function log($message)
	{
		if (self::$debugMode) {
			PC::db($message);
		}
	}
}



