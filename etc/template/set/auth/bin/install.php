#!/usr/local/bin/php
<?php
/**
 * ユーザー認証セットのインストール
 */
require_once __DIR__.'/../../../../../lib/autoload.php';

class CmdApp extends SetInstallLib
{
	
	public function main() {
		$_ = $this;
		$_->SET_ROOT = realpath(__DIR__.'/..');

		CmdLibs::bannerBig('install Auth set.');
		echo "APP_ROOT=>".$_->APP_ROOT."\n";
		echo "SET_ROOT=>".$_->SET_ROOT."\n";
		
		$params = $_->getparams();
		$dbName = $params['dbName'];
		$_->createTables($dbName);
		$_->createTableModelFiles($dbName);
		$_->createModelFiles($dbName);

		$subdirs = [
			"controller"=>0755,
			"controller/oauth"=>0755,
			"controller/userauth"=>0755,
			"etc"=>0755,
			"etc/sql"=>0755,
			"model"=>0755,
			"model/_def"=>0755,
			"model/_def/api"=>0755,
			"view"=>0755,
			"view/userauth"=>0755,
		];
		$_->copyFiles($subdirs);

		CmdLibs::bannerSmall('done.');

	}
	
	/**
	 * 使い方のパラメータを定義する
	 *
	 */
	function getAppUsage()
	{
		$appUsages = array(
			'-d' => '[database name]',
		);
		return $appUsages;
	}

	/**
	 * コマンドライン パラメータを返す
	 */
	function getparams()
	{
		$_ = $this;
		$params = array();
		if (!$dbName = CmdLibs::getParam('-d')) {
			die($_->getUsage());
		} else {
			echo "Install auth set to database: $dbName.\n";
			$params['dbName'] = $dbName;
		}

		return $params;
	}
	
}
$CmdApp = new CmdApp();
$CmdApp->main();
exit;
