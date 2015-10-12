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
			
			$APP_ROOT = dirname(__DIR__);
			$logFile = $APP_ROOT.'/var/log/trace.log';
			Logger::configure(array(
				'rootLogger' => array(
					'appenders' => array('default'),
				),
				'appenders' => array(
					'default' => array(
						'class' => 'LoggerAppenderFile',
						'layout' => array(
							'class' => 'LoggerLayoutPattern',
							'params' => array(
								'conversionPattern' => '%date [%logger] %message%newline',
							),
						),
						'params' => array(
							'file' => $logFile,
							'append' => true,
							
						)
					)
				)
			));

			global $LOGGER;
			$LOGGER = Logger::getLogger("log");

			if (php_sapi_name()!='cli') {
				global $handler;
				$handler = PhpConsole\Handler::getInstance();
				$handler->start(); // start handling PHP errors & exceptions
				$handler->getConnector()->
					setSourcesBasePath($_SERVER['DOCUMENT_ROOT']);
			}
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

			global $LOGGER;
			$LOGGER->info($message);
		}
	}
	
	/**
	 * SQLのプレースホルダを値に置き換えてからメッセージ出力
	 * 
	 * @param string $sql プレースホルダ「?」を含むSQL
	 * @param array $values SQLで使用する値
	 * @return void
	 */
	public static function logSql($sql, $values)
	{
		$len = strlen($sql);
		$i = 0;
		while ($pos=strpos($sql, '?')) {
			$f = substr($sql, 0, $pos);
			$b = substr($sql, $pos+1);
			if (is_string($values[$i])) {
				$val = "'".$values[$i]."'";
			} else {
				$val = $values[$i];
			}
			$sql = $f.$val.$b;
			$i++;
		}
		Console::log($sql);
	}
	
	
}



