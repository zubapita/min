#!/usr/local/bin/php
<?php
/**
  * バッチコマンド サンプル
 * 
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
require_once __DIR__.'/../lib/autoload.php';
class CmdApp extends AppCtl
{
	/**
	 * 初期化
	 * @return void
	 */
	public function __construct()
	{
		$_ = $this;
		$_->initView();
		parent::__construct();
		CmdLibs::setDataBridge();
	}

	/**
	 * メインルーチン
	 * @return void
	 */
	{
		$_ = $this;
		CmdLibs::bannerBig('batch command sample');

		// データの取得
		$MODEL = $_->getModel('sampleDB');
		$list = $MODEL->get(1);
		
		// データの表示
		$_->view->assign('list', $list);
		$template = $_->getTemplate();
		echo $_->view->fetch("string:$template");
	}
	
	/**
	 * Smartyによるテンプレート
	 * 変数は$ではなく#で修飾する（ヒアドキュメントの状態で評価されないように）
	 */
	function getTemplate()
	{
		$template = <<<EOS
{foreach #list as #key=>#row}
		{#row['name']}
{/foreach}
EOS;
	return str_replace('#', '$', $template);
	}
		
}
$CmdApp = new CmdApp();
$CmdApp->main();
exit;
