<?php
/**
 * デバッグモードの設定などをJS側に伝える
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
class CmnCtl extends AppCtl
{
	
	/**
	 * 初期化
	 */
	public function __construct()
	{
		$_ = $this;
		parent::__construct();
		// viewの初期化
		$_->initView();
	}
	
	/**
	 * PHPの変数をJavaScriptのCONF クラスのプロパティ変数として渡す
	 */
	function conf()
	{
		$_ = $this;
	
		// modelの出力をviewに接続
		$_->view->assign('debugMode', Console::$debugMode);
		
		// 新しいviewの実体（HTML）を生成
		$js = $_->view->fetch('cmn/includes/conf.js');

		// viewへの送信（表示）
		$_->sendJS($js);
	}

}

