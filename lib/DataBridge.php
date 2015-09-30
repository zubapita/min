<?php
/**
 * クラス間のデータ交換用クラス
 * グローバル変数 $dataBridgeにインスタンスを保存して使用する
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class DataBridge
{
	
	/**
	 * 現在のMinアプリの絶対ディレクトリパス
	 * 
	 * @see Dispatcher::dispatch()
	 * @see CmdLibs::setDataBridge()
	 */
	public $APP_ROOT;
	
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

}

