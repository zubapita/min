#!/usr/local/bin/php
<?php
//namespace Zubapita\Quicty;
/**
 * DBアクセス用クラスファイルを生成
 */
require_once __DIR__.'/../lib/autoload.php';
class CmdApp extends MakeDbClassFileLib
{
	public function __construct()
	{
		CmdLibs::setDataBridge();
		parent::__construct();
	}
}
$CmdApp = new CmdApp();
$CmdApp->main();
exit;
