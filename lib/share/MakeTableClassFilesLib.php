<?php
/**
 * テーブルクラスファイルをDBから自動生成する
 * 既存のテーブルクラスファイルがある場合は上書きしない
 * パラメータ：
 * -d データベース名 （必須）
 * -t テーブル名  （オプション）
 */
class MakeTableClassFilesLib extends AppCtl {
	
	public function __construct()
	{
		parent::__construct();
		echo "\nmake table Class file from database;.\n\n";
	}
	
	function main()
	{
		$_ = $this;
		$_->APP_ROOT = CmdLibs::getAppRoot(1);
		$_->initView();
		
		if(!$dbname = cmdLibs::getParam('-d')) {
			die("usage: ".cmdLibs::scriptName()." -d dbname \n");
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
		$template = $DbOperator->getTableClassTemplate();
		$columnTypes = $DbOperator->getColumnTypes();
		
		$dirPath = $_->APP_ROOT."/model/_def/db/$dbname";
		if(!file_exists($dirPath)) {
			mkdir($dirPath, 0777, true);
		}

		$id_column = 'id'; // default
		foreach($tables as $table) {
			
			if(!empty($targetTable) && $table!=$targetTable) {
				continue;
			}
			
			$filePath = $dirPath."/$table.php";
			if(file_exists($filePath)) {
				echo "error: $filePath is exists. can not save file.\n\n";
				continue;
			}
			
			$columns = $DbOperator->getColumns($table);
			foreach($columns as $key=>$column) {
				if($column['extra']=='auto_increment') {
					$id_column = $column['name'];
				}
				foreach($columnTypes as $mysqlType=>$datatype) {
					if(preg_match("/^$mysqlType/", $column['type'])) {
						$columns[$key]['type'] = $datatype;
						break;
					}
				}
			}

			$_->view->assign('table', $table);
			$_->view->assign('id_column', $id_column);
			$_->view->assign('columns', $columns);
			$classCode = $_->view->fetch("string:$template");
			
			file_put_contents($filePath, $classCode);
			
			echo "------------------------------\n";
			echo "save class file $table.php\n";
			echo "\n";
			echo $classCode;
			echo "\n";
		
		}
		
	}
	
}
