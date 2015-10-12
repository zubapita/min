<?php
//namespace Zubapita\Quicty;
/**
 * 新規Quictyアプリを生成
 */
class MakeNewAppLib extends MakeDbClassFileLib
{
	private $DbOperator;

	public function __construct()
	{
		parent::__construct();
		echo "\nmake New Min Application.\n\n";
	}

	public function main()
	{
		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();

		$_->usage = $_->getUsage();
		$params = $_->getParams();
		
		if ($params['rootDir']) {
			$parentPath = $params['rootDir'];
		} else {
			$parentPath = CmdLibs::getParentPath($_->APP_ROOT);
		}
		echo "parentPath=$parentPath\n";
		$newAppRoot = $parentPath.'/'.$params['appName'];

		if(!file_exists($newAppRoot)) {
			mkdir($newAppRoot, 0755, true);

			$filename = 'composer.json';
			copy($_->APP_ROOT.'/'.$filename, "$newAppRoot/$filename");

			$subdirs = array(
				"bin"=>0755,
				"controller"=>0755,
				"controller/cmn"=>0755,
				"etc"=>0755,
				"etc/template"=>0755,
/*
				"etc/template/bin"=>0755,
				"etc/template/controller"=>0755,
				"etc/template/model"=>0755,
				"etc/template/set"=>0755,
				"etc/template/set/auth"=>0755,
				"etc/template/test"=>0755,
				"etc/template/test/controller"=>0755,
				"etc/template/test/model"=>0755,
				"etc/template/view"=>0755,
				"etc/template/view/includes"=>0755,
				"etc/template/view/js"=>0755,
				"etc/template/view/lang"=>0755,
*/
				"htdocs"=>0755,
				"htdocs/cmn"=>0755,
				"htdocs/cmn/img"=>0755,
				"lib"=>0755,
				"lib/Datatype"=>0755,
				"lib/DbOperator"=>0755,
				"lib/share"=>0755,
				"lib/Util"=>0755,
				"model"=>0755,
				"model/_def"=>0755,
				"model/_def/api"=>0755,
				"model/_def/db"=>0755,
				"model/amazon"=>0755,
				"model/excel"=>0755,
				"test"=>0755,
				"test/controller"=>0755,
				"test/model"=>0755,
				"test/model/amazon"=>0755,
				"test/model/excel"=>0755,
				"var"=>0755,
				"var/compiled"=>0777,
				"var/log"=>0777,
				"view"=>0755,
				"view/cmn"=>0755,
				"view/cmn/css"=>0755,
				"view/cmn/includes"=>0755,
				"view/cmn/js"=>0755,
				"view/cmn/lang"=>0755,
				"view/cmn/layout"=>0755,
				//"view/cmn/template"=>0755,
			);

			$oldMask = umask(0);
			foreach ($subdirs as $subdir=>$permission) {
				mkdir("$newAppRoot/$subdir", $permission, true);
				if($subdir=='var/compiled') continue;

				$files = glob($_->APP_ROOT."/$subdir/*");
				if (!empty($files)) {
					foreach ($files as $filepath) {
						if (!is_dir($filepath)) {
							$filename = basename($filepath);
							copy($filepath, "$newAppRoot/$subdir/$filename");
						}
					}
				}
			}


			$_->copySubdirs("etc/template", $newAppRoot);



			$_->view->assign('APP_NAME', $params['appName']);
			$_->view->assign('APP_ROOT', $newAppRoot);
			$localVh = $_->view->fetch($_->APP_ROOT.'/etc/local_vh.conf');
			$vhFilePath = "$newAppRoot/etc/local_vh.conf";
			file_put_contents($vhFilePath, $localVh);

			chmod("$newAppRoot/bin/makeNewApp.php", 0755);
			chmod("$newAppRoot/bin/makeDbClassFile.php", 0755);
			chmod("$newAppRoot/bin/makeTableClassFiles.php", 0755);
			chmod("$newAppRoot/bin/makeModelClassFiles.php", 0755);
			chmod("$newAppRoot/bin/makeCtlAndView.php", 0755);
			chmod("$newAppRoot/bin/test.sh", 0755);
			chmod("$newAppRoot/bin/genTest.sh", 0755);
			chmod("$newAppRoot/bin/backSyncToMin.sh", 0755);
			umask($oldMask);
		}

		if (!empty($params['dbName'])) {
			$_->saveDbClassFile($params);
		}

		echo "\n\nPlease run 'composer install' at new app root.\n";

		exit;

	} /* /main */


	function copySubdirs($thisDir, $newAppRoot)
	{
		$_ = $this;
		echo "thisDir=$thisDir\n";
		
		$permission = 0755;
		$files = glob($_->APP_ROOT."/$thisDir/*");
		foreach ($files as $filepath) {
			if(is_dir($filepath)) {
				$tmp = explode("/", $filepath);
				$subdir = array_pop($tmp);
				mkdir("$newAppRoot/$thisDir/$subdir", $permission, true);
				$_->copySubdirs("$thisDir/$subdir", $newAppRoot);
			} else {
				$filename = basename($filepath);
				copy($filepath, "$newAppRoot/$thisDir/$filename");
			}
		}
		return;
	}

	/**
	 * 使い方の文字列（usage）を返す
	 *
	 */
	function getUsage()
	{
		$_ = $this;
		//$usages = array_merge($_->getAppUsage(), $_->getDbUsage());
		$usages = $_->getAppUsage();
		$usage = "usage: ".CmdLibs::scriptName()."\n";
		foreach ($usages as $switch=>$value) {
			$usage .= ' '.$switch.' '.$value."\n";
		}
		return $usage;
	}

	/**
	 * 使い方のパラメータを定義する
	 *
	 */
	function getAppUsage()
	{
		$appUsages = array(
			'-a' => '[app name]',
			'-r' => '[root dir name(option)] ex)/Users/user/workspace',
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
		if (!$appName = CmdLibs::getParam('-a')) {
			die($_->getUsage());
		} else {
			echo "make application : $appName.\n";
			$params['appName'] = $appName;
			$params['rootDir'] = CmdLibs::getParam('-r');
		}
		$params = array_merge($params, $_->getDbParams());

		return $params;
	}


}
