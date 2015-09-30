#!/usr/local/bin/php
<?php
/**
 * テーブルクラスファイルをDBから自動生成する
 * 既存のテーブルクラスファイルがある場合は上書きしない
 * パラメータ：
 * -d データベース名 （必須）
 * -t テーブル名  （オプション）
 */
require_once __DIR__.'/../lib/autoload.php';
class CmdApp extends MakeTableClassFilesLib
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
