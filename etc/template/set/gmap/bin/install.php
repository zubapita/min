#!/usr/local/bin/php
<?php
/**
 * Google Maps表示セットのインストール
 */
require_once __DIR__.'/../../../../../lib/autoload.php';

class CmdApp extends SetInstallLib
{
	
	public function main() {
		$_ = $this;
		$_->SET_ROOT = realpath(__DIR__.'/..');

		CmdLibs::bannerBig('install Google Maps display set.');
		echo "APP_ROOT=>".$_->APP_ROOT."\n";
		echo "SET_ROOT=>".$_->SET_ROOT."\n";
		
		$params = $_->getparams();
		$pageName = $params['pageName'];
		$_->view->assign('pageName', $pageName);
		$className = ucfirst($pageName);
		$_->view->assign('className', $className);

		$subdirs = [
			"controller/gmap"=>
				[
					"dir" => "controller/{$pageName}",
					"permission" => 0755,
				],
			"view/gmap"=>
				[
					"dir" => "view/{$pageName}",
					"permission" => 0755,
				],
		];
		$_->installFiles($subdirs);
		
		rename($_->APP_ROOT."/controller/{$pageName}/GmapCtl.php", $_->APP_ROOT."/controller/{$pageName}/{$className}Ctl.php");

		CmdLibs::bannerSmall('done.');

	}
	
	/**
	 * 使い方のパラメータを定義する
	 *
	 */
	function getAppUsage()
	{
		$appUsages = array(
			'-p' => '[page name]',
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
		if (!$pageName = CmdLibs::getParam('-p')) {
			//die($_->getUsage());
			$pageName = "gmap";
		}
		echo "Install Google Maps display set to : $pageName.\n";
		$params['pageName'] = $pageName;

		return $params;
	}
	
}
$CmdApp = new CmdApp();
$CmdApp->main();
exit;
