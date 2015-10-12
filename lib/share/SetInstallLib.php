<?php
/**
 * モジュールセットのインストール
 */


// クラス本体
class SetInstallLib extends AppCtl
{
	
	public $SET_ROOT;
	
	public function __construct()
	{
		$_ = $this;
		CmdLibs::setDataBridge();
		parent::__construct();
	}
	

	/**
	 * モジュールに必要なテーブルをデータベースに作成する
	 *
	 * @param string $dbName
	 */
	public function createTables($dbName)
	{
		$_ = $this;
		
		$DBSpec = new $dbName();

		$files = glob($_->SET_ROOT."/etc/sql/*.sql");
		foreach ($files as $file) {
			$sql = file_get_contents($file);
			$DB = new StdDB($DBSpec); // 連続してcreate tableするため、毎回リセット
			$result = $DB->query($sql);
			var_dump($result);
		}
	}

	/**
	 * モジュールに必要なテーブルのモデルファイルを生成する
	 *
	 * @param string $dbName
	 */
	public function createTableModelFiles($dbName)
	{
		$_ = $this;
		
		$MakeTableFiles = new MakeTableClassFilesLib;

		$files = glob($_->SET_ROOT."/etc/sql/*.sql");
		foreach ($files as $file) {
			$tableName = basename($file, ".sql");
			echo "tableName=$tableName\n";
			$MakeTableFiles->makeTableFiles($dbName, $tableName);
		}
	}

	/**
	 * モジュールに必要なテーブルを操作するモデルファイルを生成する
	 *
	 * @param string $dbName
	 */
	public function createModelFiles($dbName)
	{
		$_ = $this;
		
		$MakeModelFiles = new MakeModelClassFilesLib;

		$files = glob($_->SET_ROOT."/etc/sql/*.sql");
		foreach ($files as $file) {
			$tableName = basename($file, ".sql");
			echo "tableName=$tableName\n";
			$MakeModelFiles->makeTableModels($dbName, $tableName);
		}
	}
	
	/**
	 * モジュールを構成するファイルを実行ディレクトリのコピーする
	 *
	 * @param array $subdirs
	 */
	public function copyFiles($subdirs)
	{
		$_ = $this;


		$oldMask = umask(0);
		foreach ($subdirs as $subdir=>$permission) {
			if (!file_exists($_->APP_ROOT."/$subdir")) {
				mkdir($_->APP_ROOT."/$subdir", $permission, true);
			}
			$files = glob($_->SET_ROOT."/$subdir/*");
			if (!empty($files)) {
				foreach ($files as $filepath) {
					if (!is_dir($filepath)) {
						$filename = basename($filepath);
						copy($filepath, $_->APP_ROOT."/$subdir/$filename");
					}
				}
			}
		}

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


}
