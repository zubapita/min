<?php
//namespace Zubapita\Quicty;
/**
 * DBアクセス用クラスファイルを生成
 */
class MakeDbClassFileLib extends AppCtl
{
	private $DbOperator;

	public function __construct()
	{
		parent::__construct();
		//echo "\nmake DB Class File.\n\n";
	}

	public function main()
	{
		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();

		$_->usage = $_->getUsage();
		$params = $_->getDbParams();

		if (!empty($params['dbName'])) {
			$_->saveDbClassFile($params);
		} else {
			echo "Plaese set database name.\n";
			die($_->usage);
		}

		exit;

	} /* /main */



	/**
	 * データベースクラスファイルを作成する
	 */
	public function saveDbClassFile($params) {
		$_ = $this;
		$DbOperator = new DbOperatorContext($params['systemName']);
		$template = $DbOperator->getDbClassTemplate();

		$classCode = $_->getDbClassCode($params, $template);
		$dbname = $params['dbName'];
		$dbFilePath = $_->APP_ROOT."/model/_def/db/$dbname.php";
		file_put_contents($dbFilePath, $classCode);

		echo "------------------------------\n";
		echo "save db class file $dbname.php\n";
		echo "\n";
		echo $classCode;
		echo "\n";
	}


	/**
	 * 使い方の文字列（usage）を返す
	 *
	 */
	function getUsage()
	{
		$_ = $this;
		$usages = $_->getDbUsage();
		$usage = "usage: ".cmdLibs::scriptName()."\n";
		foreach ($usages as $switch=>$value) {
			$usage .= ' '.$switch.' '.$value."\n";
		}
		return $usage;
	}

	function getDbUsage()
	{
		$dbUsages = array(
			'-d'=>'[dbname]',
			'-s'=>'[mysql|pgsql|sqlite]',
			'-D'=>'[dbDir(for sqlite. From App Root.)]',
			'-u'=>'[user]',
			'-p'=>'[password](option)',
		);
		return $dbUsages;
	}


	/**
	 * DB関連のコマンドライン パラメータを返す
	 */
	function getDbParams()
	{
		$_ = $this;
		$params = array();
		if ($dbName = cmdLibs::getParam('-d')) {
			echo "use database : $dbName.\n";
			$params['dbName'] = $dbName;

			if(!$systemName = cmdLibs::getParam('-s')) {
				echo "Plaese set database system name.\n";
				die($_->usage);
			} else {
				$systems = array('mysql', 'pgsql', 'sqlite');
				$systemName = strtolower($systemName);
				if(in_array($systemName, $systems)!==false) {
					echo "use database system : $systemName.\n";
					$params['systemName'] = $systemName;

					if($systemName=='sqlite') {
						if(!$dbDir = cmdLibs::getParam('-D')) {
							echo "Plaese set database dir(from App Root).\n";
							die($_->usage);
						} else {
							echo "use database file $dbDir\n";
							$params['dbDir'] = $dbDir;
						}
					} else {
						if(!$userName = cmdLibs::getParam('-u')) {
							echo "Plaese set database user name.\n";
							die($_->usage);
						} else {
							echo "use database user: $userName.\n";
							$params['userName'] = $userName;
						}

						if($password = cmdLibs::getParam('-p')) {
							echo "use password for database.\n";
							$params['password'] = $password;
						} else {
							echo "no password for database.\n";
							$params['password'] = '';
						}
						$params['dbDir'] = '';
					}

				} else {
					echo "please set valid database system name"
						." [mysql|pgsql|sqlite]\n";
					die($_->usage);
				}
			}
		}

		return $params;
	}


	/**
	 * DBクラスファイル用のPHPコードを返すｓ
	 */
	function getDbClassCode($params, $template)
	{
		$_ = $this;

		$_->view->assign('dbname', $params['dbName']);
		$_->view->assign('systemName', $params['systemName']);
		$_->view->assign('user', $params['userName']);
		$_->view->assign('password', $params['password']);
		if(!empty($params['dbDir'])) {
			$_->view->assign('dbdir', $params['dbDir']);
		}

		$classCode = $_->view->fetch("string:$template");

		return $classCode;
	}

}
