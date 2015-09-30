<?php
/**
 * ModelクラスファイルをDBから自動生成する
 * 既存のクラスファイルがある場合は上書きしない
 * パラメータ：
 * -d データベース名 （必須）
 * -t テーブル名  （オプション）
 */
class MakeModelClassFilesLib extends AppCtl {
	
	public function __construct()
	{
		parent::__construct();
		echo "\nmake  Class file from database;.\n\n";
	}
	
	function main()
	{
		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();
		
		if(!$dbname = cmdLibs::getParam('-d')) {
			die("usage: ".cmdLibs::scriptName()." -d dbname -t tableName\n");
		} else {
			echo "use database $dbname.\n\n";
		}
		
		if($targetTable = cmdLibs::getParam('-t')) {
			echo "target table is '$targetTable'.\n\n";
		} else {
			echo "all tables in DB are target.\n\n";
		}
		
		if(!$DB = $_->getDB($dbname)) {
			die("error: '$dbname' class file is not exists in ../model/db/\n");
		}
		
		$DbOperator = new DbOperatorContext($DB::$SYSTEM);

		$DbOperator->setDb($DB);
		$tables = $DbOperator->getTables();
		
		foreach($tables as $table) {
			
			if(!empty($targetTable) && $table!=$targetTable) {
				continue;
			}

			$dirPath = $_->APP_ROOT."/model/$table";
			if(!file_exists($dirPath)) {
				mkdir($dirPath, 0777, true);
			}

			//
			// List Model
			$columns = $DbOperator->getColumns($table);
			$_->view->assign('db', $dbname);
			$_->view->assign('table', $table);
			$_->view->assign('columns', $columns);
			
			$className = ucfirst($table).'List';
			$_->view->assign('className', $className);
			
			$filePath = $dirPath.'/'.$className.".php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
			} else {
				$templateFile = $_->APP_ROOT.'/etc/template/model/dataList.php';
				$classCode = $_->view->fetch($templateFile);
			
				file_put_contents($filePath, $classCode);
			
				echo "------------------------------\n";
				echo "save class file $table.php\n";
				echo "\n";
				echo $classCode;
				echo "\n";
			}
		
			// Record Model
			$className = ucfirst($table).'Record';
			$_->view->assign('className', $className);
			
			$filePath = $dirPath.'/'.$className.".php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
			} else {
				$templateFile = $_->APP_ROOT.'/etc/template/model/dataRecord.php';
				$classCode = $_->view->fetch($templateFile);
			
				file_put_contents($filePath, $classCode);
			
				echo "------------------------------\n";
				echo "save class file $table.php\n";
				echo "\n";
				echo $classCode;
				echo "\n";
			}
		
		}
		
	}
	
}
