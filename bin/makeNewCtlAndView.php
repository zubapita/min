#!/usr/local/bin/php
<?php
/**
 * 表示用のControllerとViewを生成する
 * 既存のクラスファイルがある場合は上書きしない
 * パラメータ：
 * -m モデル名 （必須）
 * -p ページ名  （オプション。省略時はモデル名）
 */
require_once __DIR__.'/../lib/autoload.php';
class CmdApp extends MakeNewCtlAndViewLib
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
